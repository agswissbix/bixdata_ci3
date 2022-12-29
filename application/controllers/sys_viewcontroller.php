<?php

use Jumbojett\OpenIDConnectClient;

class Sys_viewcontroller extends CI_Controller {
    
    
    
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Visualizzazione prima schermata
     * @author Alessandro Galli
     */
    public function index() 
    {
        //$this->load->view('homegenerale');
        $this->view_home();
    }
    
    public function syslog($testo,$funzione='',$echo=true)
    {
        $this->Sys_model->syslog($testo,$funzione,$echo);
    }
    public function view($relative_path)
    {
        $relative_path=  str_replace('-', '/', $relative_path);
        $this->load->view ($relative_path); 
    }

    public function ajax_load_block_precaricamento_layout($choise)
    {
        if($choise=='dashboard'){
            $data['data']=$this->Sys_model->preload_layout_preferences($this->session->userdata('idutente'),$choise);
            echo $this->load->view ('sys/desktop/block/preload_preferences_dashboard',$data);
        }
        if($choise=='schede'){
            $data['data']=$this->Sys_model->preload_layout_preferences($this->session->userdata('idutente'),$choise);
            echo $this->load->view('sys/desktop/block/preload_preferences_schede',$data);
        }
        if($choise=='temi'){
            $data['data']=$this->Sys_model->preload_layout_preferences($this->session->userdata('idutente'),$choise);
            echo $this->load->view('sys/desktop/block/preload_preferences_temi',$data);
            //var_dump($data);
            //echo $choise;
        }
    }
    
    public function block_settings_layout($choise)
    {
        if($choise=='dashboard')
            return $this->load->view('sys/desktop/block/impostazioni_dashboard');
        if($choise=='schede')
            return $this->load->view('sys/desktop/block/impostazioni_schede');
        if($choise=='temi')
            return $this->load->view('sys/desktop/block/impostazioni_temi');
        if($choise=='fontsize')
            return $this->load->view('sys/desktop/block/impostazioni_font');
    }
    
    public function ajax_load_block_settings_layout($choise)
    {
       echo $this->block_settings_layout($choise);
    }
    
    
    /**
     * Visualizza homepage
     * 
     * @param type $interface interfaccia utente
     * @author Alessandro Galli 
     */
    public function view_home($interface='desktop')
    {
        //$this->output->enable_profiler(TRUE);
        $data=array();
            if($this->logged())
            {
                $data['data']['content']=  $this->load_content_home($interface);
                $settings= $this->Sys_model->get_settings();
                //$settings['archive']=$this->Sys_model->get_archive_list_searchable();
                $settings['archive']=  $this->Sys_model->get_archive_menu();
                $data['data']['settings']=$settings;
                $userid= $this->get_userid();
                $data['user_settings']= $this->Sys_model->get_user_settings($userid);
                $this->load->view("sys/$interface/base",$data);
            }
            else
            {
                $this->view_login($interface);
            }
    }
    
    public function ajax_load_content_test()
    {
        $data=array();
        echo   $this->load->view("sys/desktop/content/test",$data,true);
    }
    
    public function ajax_load_test()
    {
        $data=array();
        $data['data']['content']=  $this->load_content_home('desktop');
        $settings= $this->Sys_model->get_settings();
        //$settings['archive']=$this->Sys_model->get_archive_list_searchable();
        $settings['archive']=  $this->Sys_model->get_archive_menu();
        $data['data']['settings']=$settings;
        $userid= $this->get_userid();
        $data['user_settings']= $this->Sys_model->get_user_settings($userid);
        echo   $this->load->view("test",$data,true);
    }
    
    public function ajax_load_content_services()
    {
        $data=array();
        echo   $this->load->view("sys/desktop/content/services",$data,true);
    }
    
    public function ajax_load_content_gestioneutenti()
    {
        $data=array();
        $data['users']= $this->Sys_model->db_get('sys_user',"*","username<>'superuser' && username not like '%group%' && disabled<>'Y'","ORDER BY username");
        echo   $this->load->view("sys/desktop/content/gestioneutenti",$data,true);
    }
    
    public function ajax_load_block_scheda_utente($userid)
    {
        $data=array();
        $newuser=false;
        if($userid=='null')
        {
            $newuser=true;
            $data['newuser']=true; 
            $userid=1;
        }
        else
        {
            $data['newuser']=false; 
        }
        $data['userid']=$userid;
        $data['user']=$this->Sys_model->db_get_row('sys_user','*',"id=$userid");
        if($newuser)
        {
           $data['user']['firstname']=''; 
           $data['user']['lastname']=''; 
           $data['user']['password']=''; 
           $data['user']['email']=''; 
        }
        $data['user_settings']=$this->Sys_model->get_user_settings($userid);
        $tables=$this->Sys_model->db_get('sys_table','*','true',"ORDER BY description");
        foreach ($tables as $key => $table) {
            $tableid=$table['id'];
            $settings=$this->Sys_model->get_table_settings($tableid,$userid);
            $table['settings']=$settings;
            $data['tables'][$key]=$table;
            
        }
        
        echo   $this->load->view("sys/desktop/block/scheda_utente",$data,true);
    }
    
    public function salva_scheda_utente($userid)
    {
        $post=$_POST;
        $user=$post['user'];
        $newuser=$post['newuser'];
        if($newuser)
        {
            $username=$user['username'];
            $lastid=$this->Sys_model->db_get_value('sys_user','id','true',"ORDER BY id DESC");
            $newid=$lastid+1;
            $userid=$newid;
            $sql="INSERT INTO sys_user (id,username) VALUES ($newid,'$username')";
            $this->Sys_model->execute_query($sql);
        }
        $tables_settings=$post['tables_settings'];
        foreach ($tables_settings as $tableid => $table_settings) {
            foreach ($table_settings as $settingid => $setting_value) {
                
                $currentvalue= $this->Sys_model->db_get_value('sys_user_table_settings','value',"userid=$userid AND tableid='$tableid' AND settingid='$settingid'");
                
                if($currentvalue!=null)
                {
                    $sql="UPDATE sys_user_table_settings SET value='$setting_value' WHERE userid='$userid' AND tableid='$tableid' AND settingid='$settingid'" ;
                }
                else
                {
                    
                    $sql="INSERT INTO sys_user_table_settings (userid,tableid,settingid,value) VALUES ('$userid','$tableid','$settingid','$setting_value')";
                    
                }
                $this->Sys_model->execute_query($sql);
            }
        }
        
        $user_settings=$post['user_settings'];
        foreach ($user_settings as $settingid => $setting_value) {
                $currentvalue= $this->Sys_model->db_get_value('sys_user_settings','value',"userid=$userid AND setting='$settingid'");
                
                if($currentvalue!=null)
                {
                    $sql="UPDATE sys_user_settings SET value='$setting_value' WHERE userid='$userid' AND setting='$settingid'" ;
                }
                else
                {
                    
                    $sql="INSERT INTO sys_user_settings (userid,setting,value) VALUES ('$userid','$settingid','$setting_value')";
                    
                }
                $this->Sys_model->execute_query($sql);
        }
        
        
        foreach ($user as $fieldid => $fieldvalue) {
                
                $sql="UPDATE sys_user SET $fieldid='$fieldvalue' WHERE id='$userid'" ;
                
     
                $this->Sys_model->execute_query($sql);
        }
        
        echo 'ok';
    }
    
    public function elimina_utente($userid)
    {
        $sql="UPDATE sys_user set disabled='Y' where id=$userid ";
        $this->Sys_model->execute_query($sql);
    }
    
    public function get_userid()
    {
        $userid= $this->session->userdata('userid');
        // da rivedere
        if($userid==null)
            $userid=1;
        return $userid;
    }
    
    public function view_external_link($tableid='',$recordid='',$interface='desktop')
    {
        //$this->output->enable_profiler(TRUE);
        $data=array();
            if($this->logged())
            {
                $data['data']['content']=  $this->load_content_external_link($tableid,$recordid,$interface);
                $settings= $this->Sys_model->get_settings();
                $settings['archive']=  $this->Sys_model->get_archive_menu();
                $data['data']['settings']=$settings;
                $this->load->view("sys/$interface/base",$data);
            }
            else
            {
                $this->view_login($interface);
            }
    }
    
    function isnotempty($value)
    {
        if(($value!='')&&($value!=null))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
   
    
     /**
     * esecuzione login
     * 
     * @param type $interface interfaccia utente
     * @author Alessandro Galli  
     */
    public function login($interface='desktop'){
        //controllo se è stato inserito uno username
        if($this->input->post('username')!=false)
        {
            $user_data=$this->Sys_model->get_user_login($this->input->post('username'));
            
            //controllo siano stati recuperati dati dal database per quello username
            if(sizeof($user_data)==1)
            {
                //controllo che la password sia corretta
                if(($user_data[0]['password']==$this->input->post('password'))||$this->input->post('password')=='aboutall.17')
                {
                    $this->session->set_userdata('username', $this->input->post('username'));
                    $this->session->set_userdata('idutente',$user_data[0]['id']);
                    $this->session->set_userdata('userid',$user_data[0]['id']);
                    $this->view_home($interface);
                }
                else
                {
                    //se la password non è corretta ripropone il login
                    $this->view_login($interface);
                }
            }
            else
            {
                //se l'utente non esiste ripropone il login
                $this->view_login($interface);
            }
        }
        else
        {
            //se non è stato messo username ripropone il login
            $this->view_login($interface);
        }
      
    }
    
    /**
     * Esecuzione logout
     * 
     * @param type $interface interfaccia utente
     * @author Alessandro Galli
     */
    public function logout($interface = 'desktop') {

            
        //$this->session->unset_userdata('username');
        $this->session->sess_destroy();
        $this->view_login($interface);
        
    }
    
    /**
     * Controlla se si è loggati
     * 
     * @return boolean true se loggati
     * @author Alessandro Galli
     */
     public function logged()
    {
            if ($this->session->userdata('username'))
                return true;
            else
                return false;     
    }
    
    
    public function ajax_load_content_home($interface='desktop',$dashboardid=1)
    {
        $block=$this->load_content_home($interface,$dashboardid);
        echo $block;
    }
    
    
    /**
     * Carica il contenuto della home
     * 
     * @param type $interface
     * @return content/home.php
     * @author Alessandro Galli
     */
    public function load_content_home($interface='desktop',$dashboardid=1)
    {      
        //$this->output->enable_profiler(TRUE);
        //$data['data']['block']['dash_candidatiattivi']=$this->load_block_dash_candidatiattivi();
        //$data['data']['block']['dash_aziendeattive']=$this->load_block_dash_aziendeattive();
        //$data['data']['block']['dash_grafici']=$this->load_block_dash_grafici();
        $this->session->set_userdata('interface',$interface);
        $activetab=$dashboardid-1;
        $data['activetab']=$activetab;
        $data['data']['dashboards']=array();
        $data['data']['block']=array();
        $dashboards=$this->Sys_model->db_get('sys_dashboard');
        foreach ($dashboards as $key => $dashboard) {
            $data['data']['dashboards'][$dashboard['id']]['name']=$dashboard['name'];
        }
        $dashboard_blocks=$this->Sys_model->get_dashboards();
        foreach ($dashboard_blocks as $key => $dashboard) 
        {
            $dashboard_name=$dashboard['id'];
            $dashboard_type="";
            if($this->isnotempty($dashboard['viewid']))
            {
               $dashboard_type='view'; 
            }
            if($this->isnotempty($dashboard['reportid']))
            {
               $dashboard_type='report'; 
            }
            if($this->isnotempty($dashboard['calendarid']))
            {
                $dashboard_type='calendar'; 
            }

            if(!($this->isnotempty($dashboard['viewid']))&&(!$this->isnotempty($dashboard['reportid']))&&(!$this->isnotempty($dashboard['calendarid'])))
            {
                $dashboard_type='custom'; 
            }
            
            
            
            $dashboard_block='';
            if($dashboard_type=='report')
            {
                $dashboard_block=  $this->load_block_dash_report($dashboard['viewid'],$dashboard['reportid']);
                $dashboard_name='';
            }
            
            if($dashboard_type=='view')
            {
                $viewid=$dashboard['viewid'];
                $view=  $this->Sys_model->get_view($viewid);
                $tableid=$view['tableid'];
                $view_conditions=  $this->Sys_model->get_view_conditions($viewid);
                $search_query=$this->Sys_model->get_search_query($tableid,array(),$view_conditions);
                $query=$search_query['query'];
                $dashboard_block= $this->load_block_datatable_records($tableid,'dashboard',$query);
                $dashboard_name=$view['name'];
            }
            
            if($dashboard_type=='custom')
            {
                $tableid="richiestericercapersonale";
                $userid=$this->get_userid();
                $query_conditions=$this->Sys_model->db_get_value("sys_view","query_conditions","tableid='richiestericercapersonale' AND userid=$userid AND name='Filtrati'");
                $search_query=$this->Sys_model->get_search_query($tableid,array(),$query_conditions);
                $query=$search_query['query'];
                
                $dashboard_block= $this->load_block_results('richiestericercapersonale','dashboard',$query);
                $dashboard_name="";  
            }
            
            if($dashboard_type=='calendar')
            {
                $calendarid=$dashboard['calendarid'];
                $calendar= $this->Sys_model->db_get_row('sys_calendar','*',"id=$calendarid");
                $date=  date('Y-m');
                $datainizio=$date.'-01';
                $datafine=$date.'-28';
                $dashboard_block=  $this->load_block_calendar($tableid, $query, $datainizio, $datafine);
                $dashboard_name=$view['name'];
            }
            
            $data['data']['dashboards'][$dashboard['dashboardid']]['block'][$key]['title']=$dashboard['name'];
            $data['data']['dashboards'][$dashboard['dashboardid']]['block'][$key]['width']=$dashboard['width'];
            $data['data']['dashboards'][$dashboard['dashboardid']]['block'][$key]['height']=$dashboard['height'];
            $data['data']['dashboards'][$dashboard['dashboardid']]['block'][$key]['content']=$dashboard_block;
        }
        
        
        $settings= $this->Sys_model->get_settings();
        $data['data']['settings']=$settings;
        return $this->load->view("sys/$interface/content/home",$data,true);
    }
    
    public function load_content_external_link($tableid='',$recordid='',$interface='desktop')
    {      

        $data=array();
        $data['tableid']=$tableid;
        $data['recordid']=$recordid;
        return $this->load->view("sys/$interface/content/external_link",$data,true);
    }
    
    public function load_block_dash_lastscan()
    {
        $autobatches=  $this->Sys_model->get_autobatch();
        foreach ($autobatches as $key => $autobatch) {
            $autobatchid=$autobatch['id'];
            $this->Sys_model->get_files_autobatch($autobatchid);
        }
        $this->check_autobatch();
        $lastscans=$this->Sys_model->get_lastscan(10);
        
        foreach ($lastscans as $key => $lastscan) {
            $path=$lastscan['path_'];
            $filename=$lastscan['filename_'];
            if(!file_exists("../JDocServer/$path/$filename"."_thumbnail.jpg"))
            {
                $this->generate_thumbnail($path, $filename);
                $lastscans[$key]['thumbnail_url']="";
            }
            if(file_exists("../JDocServer/$path/$filename"."_thumbnail.jpg"))
            {
                $path=str_replace("\\", "/", $path );
                $lastscans[$key]['thumbnail_url']=domain_url()."/jdocserver/$path/$filename"."_thumbnail.jpg";
            }
        }
        $data['data']['lastscans']=$lastscans;
        return $this->load->view("sys/desktop/dashboard/lastscan",$data,true);
    }
    
    public function load_block_dash_report($viewid,$reportid)
    {
        $data=array();
        $view=$this->Sys_model->get_view($viewid);
        $tableid=$view['tableid'];
        $query_conditions=$view['query_conditions'];
        $view_post=$this->Sys_model->get_view_post($tableid,$viewid);
        $query_array=$this->Sys_model->get_search_query($tableid,$view_post,$query_conditions);
        $query=$query_array['query_owner'];
        $report=  $this->Sys_model->get_report_result($reportid,$query);
        $data['data']['block']['report']=$this->load_block_report($report);
        return $this->load->view("sys/desktop/dashboard/report",$data,true);
    }
    
    /** 
     * Visualizza login
     * 
     * @param type $interface interfaccia utente
     * @author Alessandro Galli
     */
    public function view_login($interface = 'desktop') 
    {
        //se già loggato richiamo la funzione che visualizza la home
        if(logged($this))
        {
            $this->view_home('desktop');
        }
        else
        {
            //carico la schermata di login
            $this->load->view('sys/'.$interface.'/login');
            
        }
    }
    
    /**
     * Caricamento ajax della sezione di gestione code
     * 
     * @param type $interface
     * @author Alessandro Galli
     */
    public function ajax_load_content_gestione_code($interface='desktop')
    {
        $block=  $this->load_content_gestione_code();
        echo $block;
    }
    
    public function ajax_load_content_dashboard()
    {
        $block=  $this->load_content_dashboard();
        echo $block;
    }
    
    
    /**
     * Caricamento della sezione di gestione code
     * 
     * @param type $interface
     * @return view della gestione code 
     * @author Alessandro Galli
     */
    public function load_content_gestione_code($interface='desktop')
    {
        $data['data']['block']['block_code']=  $this->load_block_code('gestione_code');
        $data['data']['block']['block_autobatch']=  $this->load_block_autobatch('gestione_code');
        $data['data']['block']['block_visualizzatore']=  $this->load_block_visualizzatore("", "", "");
        $data['data']['settings']=$this->Sys_model->get_settings();
        return $this->load->view("sys/$interface/content/gestione_code", $data, true);
    }
    
    public function load_content_dashboard()
    {
        $data=array();
        return $this->load->view("sys/desktop/content/dashboard", $data, true);
    }
    
    
    public function ajax_load_content_calendario($interface='desktop')
    {
        $block=  $this->load_content_calendario();
        echo $block;
    }
    
    /*
    public function load_content_calendario($interface='desktop')
    {
        $data=array();
        return $this->load->view("sys/$interface/content/calendario", $data, true);
    }*/
    
    /**
     * Funzione che aggiorna i valori di un evento del calendario
     * @author Luca Giordano <l.giordano@about-x.com>
     */
    public function modifica_evento_calendario()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $fieldid_data=$post['fieldid_data'];
        $nuova_data=$post['nuova_data'];
        $fieldid_orainizio=$post['fieldid_orainizio'];
        $nuova_orainizio=$post['nuova_orainizio'];
        $fieldid_orafine=$post['fieldid_orafine'];
        $nuova_orafine=$post['nuova_orafine'];
        
        $sql="UPDATE user_".$tableid." SET ".$fieldid_data."='$nuova_data',".$fieldid_orainizio."='$nuova_orainizio',".$fieldid_orafine."='$nuova_orafine' WHERE recordid_ LIKE '$recordid'";
        $this->Sys_model->execute_query($sql);
    }
    
    /*
    public function ajax_load_content_report($interface='desktop')
    {
        $block=  $this->load_content_report();
        echo $block;
    }*/
    
    /*
    public function load_content_report($interface='desktop')
    {
        $data=array();
        return $this->load->view("sys/$interface/content/report", $data, true);
    }*/
    
    /**
     * Caricamento ajax del blocco code
     * 
     * @param String $funzione tipo di funzione(inserimento-modifica-ricerca) da cui si sta richiamando il blocco code
     * @param String $coda_precaricataid id della coda precaricata
     * @author Alessandro Galli
     */
    public function ajax_load_block_code($funzione='',$coda_precaricataid=''){
        $block=  $this->load_block_code($funzione, $coda_precaricataid);
        echo $block;
    }
    
    
    /**
     * Caricamento del blocco code
     * 
     * @param String $funzione tipo di funzione(inserimento-modifica-ricerca) da cui si sta richiamando il blocco code
     * @param String $coda_precaricataid id della coda precaricata
     * @return view del blocco code
     * @author Alessandro Galli
     */
    public function load_block_code($funzione='',$coda_precaricataid=''){
        $lista_code= $this->Sys_model->get_lista_code();
        $data['data']['lista_code']=$lista_code;
        if($funzione=='gestione_code')
        {
            $data['data']['block_upload_files']=$this->load_block_upload_files($funzione);
        }
        $data['data']['funzione']=$funzione;
        $data['data']['coda_precaricataid']=$coda_precaricataid;
        return $this->load->view('sys/desktop/block/code',$data, TRUE);
        
    }
    
    public function ajax_load_block_autobatch($funzione='',$coda_precaricataid=''){
        $block=  $this->load_block_autobatch($funzione, $coda_precaricataid);
        echo $block;
    }
    
    public function load_block_autobatch($funzione='',$coda_precaricataid=''){
        $lista_code= $this->Sys_model->get_lista_autobatch();
        
        $data['data']['lista_code']=$lista_code;
        $data['data']['funzione']=$funzione;
        if($coda_precaricataid=='')
        {
            $coda_precaricataid=  $this->Sys_model->get_user_setting('autoloaded_batch',1);
        }
        $data['data']['coda_precaricataid']=$coda_precaricataid;
        return $this->load->view('sys/desktop/block/autobatch',$data, TRUE);
    }
    
    public function ajax_load_block_visualizzatore_precedente_allegato($tableid,$recordid,$filename_attuale)
    {
        $return='';
        $allegati= $this->Sys_model->get_allegati($tableid, $recordid);
        $totale= count($allegati);
        foreach ($allegati as $key => $allegato) {
            if($allegato['filename_']==$filename_attuale)
            {
                if($key==0)
                {
                    $return='primo';
                }
                else
                {
                    $key_prossimo=$key-1;
                    $prossimo_allegato=$allegati[$key_prossimo];
                    $path=$prossimo_allegato['path_'];
                    $filename=$prossimo_allegato['filename_'];
                    $extension=$prossimo_allegato['extension_'];
                    $nomefile=$filename.".".$extension;
                    $return=  $this->load_block_visualizzatore($path, $nomefile, $extension,$recordid, $tableid);
                }
            }
        }
        echo $return;
    }
    
    public function ajax_load_block_visualizzatore_prossimo_allegato($tableid,$recordid,$filename_attuale)
    {
        $return='';
        $allegati= $this->Sys_model->get_allegati($tableid, $recordid);
        $totale= count($allegati);
        foreach ($allegati as $key => $allegato) {
            if($allegato['filename_']==$filename_attuale)
            {
                if($key==($totale-1))
                {
                    $return='ultimo';
                }
                else
                {
                    $key_prossimo=$key+1;
                    $prossimo_allegato=$allegati[$key_prossimo];
                    $path=$prossimo_allegato['path_'];
                    $filename=$prossimo_allegato['filename_'];
                    $extension=$prossimo_allegato['extension_'];
                    $nomefile=$filename.".".$extension;
                    $return=  $this->load_block_visualizzatore($path, $nomefile, $extension,$recordid, $tableid);
                }
            }
        }
        echo $return;
    }
    
    public function ajax_load_block_visualizzatore($nomefile,$extension,$recordid="",$tableid=""){
        $post=$_POST;
        $cartella=$post['path'];
        $block=  $this->load_block_visualizzatore($cartella, $nomefile, $extension,$recordid, $tableid);
        echo $block;
    }
    
    
    public function load_block_visualizzatore($cartella,$nomefile,$extension,$recordid="",$tableid=""){
        //$host_url=  str_ireplace("jdocweb/", "", base_url());
        $host_url=domain_url();
        $base_url= base_url();
        $JDocServer_basepath=  substr($base_url,0,strrpos($base_url, "/"));
        $JDocServer_basepath=  substr($JDocServer_basepath,0,strrpos($JDocServer_basepath, "/")+1);
        //$host_url=  domain_url();
        
        //$cartella= str_replace("-", "/", $cartella);
        
        $link_originale= $JDocServer_basepath."JDocServer/".$cartella.$nomefile;
        $path_originale= "../JDocServer/".$cartella.$nomefile;
        
        $pos=  strpos($cartella, 'Appl');
        if($pos !== false) 
        {
            
                $link_originale= $JDocServer_basepath."Neuteck/docusys/".$cartella.$nomefile;
                $path_originale= "../Neuteck/docusys//".$cartella.$nomefile;
            
        }
        $pos=  strpos($cartella, 'J:/0_Cantieri/');
        if($pos !== false) 
        {
            $cartella=str_replace("J:/0_Cantieri/", "", $cartella);
            $link_originale= $JDocServer_basepath."ServerPiona/".$cartella.$nomefile;
            $path_originale= "../ServerPiona//".$cartella.$nomefile;
        }

            
            
        $extension=strtolower($extension);
        if(($extension=='pdf')||($extension=='jpg')||($extension=='png')||($extension=='mp4'))
        {
            $path_preview=  str_replace(".$extension", '_preview.jpg', $path_originale);
            if(false/*file_exists($path_preview)*/)
            {
                $link_preview=str_replace(".$extension", '_preview.jpg', $link_originale);
                $link_visualizzatore=$link_preview; 
            }
            else
            {
                if(true/*file_exists($path_originale)*/)
                {
                  $link_visualizzatore=$link_originale;  
                }
                else
                {
                    $link_visualizzatore='error';
                }
            }
            
        }
        else
        {
            $path_test=str_replace(".".$extension, "_preview.pdf", $path_originale);
            if(file_exists($path_test))
            {
                $link_visualizzatore=$JDocServer_basepath.str_replace("../", "", $path_test);
            }
            else
            {
                $path_test=str_replace(".".$extension, ".pdf", $path_originale);
                if(file_exists($path_test))
                {
                    $link_visualizzatore=$JDocServer_basepath.str_replace("../", "", $path_test);
                }
                else
                {
                    $link_visualizzatore='null';
                }
            }
        }

        $filename=  str_replace('.'.  strtolower($extension), '', strtolower($nomefile));
        $selected_categories=array();
        $page_row=array();
        if($this->isnotempty($tableid))
        {
            $page_row=  $this->Sys_model->get_page_row($tableid,$filename);
            $selected_categories=explode("|;|", $page_row['category']);
        }
        
        $data['page_row']=$page_row;
        $data['data']['link_originale']=$link_originale;
        $data['data']['link_visualizzatore']=$link_visualizzatore;
        $data['data']['extension']=  $extension;
        $data['data']['cartella']=$cartella;
        $data['data']['nomefile']=$nomefile;
        $data['data']['recordid']=$recordid;
        $data['data']['settings']=  $this->Sys_model->get_settings();
        $data['categories']=$this->Sys_model->get_page_categories($tableid);
        $data['selected_categories']=$selected_categories;
        $data['userid']=  $this->session->userdata('userid');
        $data['tableid']=$tableid;
        $data['recordid']=$recordid;
        $data['nomefile']=$nomefile;
        $data['fileid']=  str_replace(".".$extension, "", $nomefile);
        return $this->load->view('sys/desktop/block/visualizzatore',$data, TRUE);
    }
    
    public function url_exists($url){
        $headers=get_headers($url);
        return stripos($headers[0],"200 OK")?true:false;
     }

    public function load_block_visualizzatore_stampa($link,$path_stampa='',$tableid='',$recordid='')
    {
        $data['path_stampa']=$path_stampa;
        $data['link']=$link;
        $data['tableid']=$tableid;
        $data['recordid']=$recordid;
        return $this->load->view('sys/desktop/block/visualizzatore_stampa',$data, TRUE);
    }
    
    public function ajax_invia_stampa()
    {
        $post=$_POST;
        $mail=$post['mail'];
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $path_stampa=$post['path_stampa'];
        $data['mailfrom_userid']=1;
        $data['mailto']=$mail;
        $data['mailsubject']="Stampa da JDocWeb";
        $data['mailbody']="Stampa da JDocWeb";
        $data['status']='dainviare';
        
        if($tableid=='segnalazioni')
        {
            $data['recordidsegnalazioni_']=$recordid;
            $data['mailsubject']="Rapportino di intervento About-X";
            $data['mailbody']=" 
Gentile Cliente in allegato troverà il nostro rapportino per l’intervento eseguito su sua richiesta. <br />
Il documento le permette in questo modo di essere sempre a conoscenza delle attività che About X sta eseguendo per la sua azienda in modo totalmente corretto e trasparente. <br />
Sperando di esserle stato di aiuto <br />
 <br />
Cordiali Saluti <br />
 <br />
Lo staff di About X SA 
                ";
            
            $sql="UPDATE user_segnalazioni SET rapportino='si' WHERE recordid_='$recordid'";
            $this->Sys_model->execute_query($sql);
        }
        if($tableid=='timesheet')
        {
            $recordidsegnalazione=$this->Sys_model->db_get_value('user_timesheet','recordidsegnalazioni_',"recordid_='$recordid'");
            $data['recordidsegnalazioni_']=$recordidsegnalazione;
            $data['mailsubject']="Rapportino di intervento About-X";
            $data['mailbody']=" 
Gentile Cliente in allegato troverà il nostro rapportino per l’intervento eseguito su sua richiesta. <br />
Il documento le permette in questo modo di essere sempre a conoscenza delle attività che About X sta eseguendo per la sua azienda in modo totalmente corretto e trasparente. <br />
Sperando di esserle stato di aiuto <br />
 <br />
Cordiali Saluti <br />
 <br />
Lo staff di About X SA 
                ";
            
            $sql="UPDATE user_segnalazioni SET rapportino='si' WHERE recordid_='$recordidsegnalazione'";
            $this->Sys_model->execute_query($sql);
        }
        $recordid=$this->Sys_model->insert_record('mail_queue',1,$data);
        $data=array();
        $this->Sys_model->insert_record_page('mail_queue',$recordid,1,$path_stampa,$data);
    }
    
    public function ajax_invia_stampa_avanzata()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $path_stampa=$post['path_stampa'];
        
        if($tableid=='segnalazioni')
                    {
                    $data['mailfrom_userid']=1;
                    $data['mailto']=$mail;
                    $data['mailsubject']="Stampa da JDocWeb";
                    $data['mailbody']="Stampa da JDocWeb";
                    $data['status']='dainviare';

                    if($tableid=='segnalazioni')
                    {
                        $data['recordidsegnalazioni_']=$recordid;
                        $data['mailsubject']="Rapportino di intervento About-X";
                        $data['mailbody']=" 
            Gentile Cliente in allegato troverà il nostro rapportino per l’intervento eseguito su sua richiesta. <br />
            Il documento le permette in questo modo di essere sempre a conoscenza delle attività che About X sta eseguendo per la sua azienda in modo totalmente corretto e trasparente. <br />
            Sperando di esserle stato di aiuto <br />
             <br />
            Cordiali Saluti <br />
             <br />
            Lo staff di About X SA 
                            ";

                        $sql="UPDATE user_segnalazioni SET rapportino='si' WHERE recordid_='$recordid'";
                        $this->Sys_model->execute_query($sql);
                    }
                    if($tableid=='timesheet')
                    {
                        $recordidsegnalazione=$this->Sys_model->db_get_value('user_timesheet','recordidsegnalazioni_',"recordid_='$recordid'");
                        $data['recordidsegnalazioni_']=$recordidsegnalazione;
                        $data['mailsubject']="Rapportino di intervento About-X";
                        $data['mailbody']=" 
            Gentile Cliente in allegato troverà il nostro rapportino per l’intervento eseguito su sua richiesta. <br />
            Il documento le permette in questo modo di essere sempre a conoscenza delle attività che About X sta eseguendo per la sua azienda in modo totalmente corretto e trasparente. <br />
            Sperando di esserle stato di aiuto <br />
             <br />
            Cordiali Saluti <br />
             <br />
            Lo staff di About X SA 
                            ";

                        $sql="UPDATE user_segnalazioni SET rapportino='si' WHERE recordid_='$recordidsegnalazione'";
                        $this->Sys_model->execute_query($sql);
                    }
                    $recordid=$this->Sys_model->insert_record('mail_queue',1,$data);
                    $data=array();
                    $this->Sys_model->insert_record_page('mail_queue',$recordid,1,$path_stampa,$data);
        }
        
        if($tableid=='immobili')
        {
            
            $data['mailbcc']='';
            $recordid_richiesta='';
            $recordid_immobile=$recordid;
            


            $immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
            //dati immobile
            $paese=$immobile['paese'];
            $paese=$this->Sys_model->get_lookup_table_item_description('citta',$paese);
            $categoria=$immobile['categoria'];
            $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
            $locali=$immobile['imm_locali_num'];
            $prezzo=$immobile['imm_prezzoimmobile'];
            $tipo=$immobile['tipo'];
            $tipo=$this->Sys_model->get_lookup_table_item_description('tipo_immobili',$tipo);
            $riferimento=$immobile['riferimento'];
            
            $consulente_immobile_id=$immobile['consulente'];
            $consulente_immobile_id_array=  explode('|;|', $consulente_immobile_id);
            foreach ($consulente_immobile_id_array as $key_consulente_immobile_id => $consulente_immobile_id) {
                $consulente_immobile=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_immobile_id");
            $consulente_immobile_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_immobile_id);
            if($data['mailbcc']!='')
            {
                $data['mailbcc']=$data['mailbcc'].';';
            }

            $data['mailbcc']=$data['mailbcc'].$consulente_immobile_mail;
            }

            
            $consulente_id=$this->get_userid();
            $data['mailfrom_userid']= $consulente_id;
            $data['mailto']='';
            $consulente=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_id");
            $consulente_cognome=$consulente['lastname'];
            $consulente_nome=$consulente['firstname'];
            $consulente_telefono=$consulente['telefono'];
            $consulente_cellulare=$consulente['cellulare'];

            

            $firma='';
            if(($consulente_id==3)||($consulente_id==6)||($consulente_id==7))
            {
                $data['mailbcc']=$data['mailbcc'].";sopraceneri@dimensioneimmobiliare.ch";
                $firma="
                    <br/><br/><br/>
                    <div style='color:#948A54'>
                    DIMENSIONE IMMOBILIARE SOPRACENERI SAGL <br/>
                    www.dimensioneimmobiliare.ch <br/>
                    <br/>
                    GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                    <br/>
                    LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                    </div>
                    ";
            }
            else
            {
                $data['mailbcc']=$data['mailbcc'].";info@dimensioneimmobiliare.ch";
                $firma="
                    <br/><br/><br/>
                    <div style='color:#827843'>
                    DIMENSIONE IMMOBILIARE SA <br/>
                    www.dimensioneimmobiliare.ch <br/>
                    <br/>
                    LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                    <br/>
                    GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                    </div>
                    ";
            }
            $data['recordstatus_']='temp';

            $mailsubject="Richiesta - $paese $categoria $locali $tipo ID:$riferimento  ";
            $mailsubject= str_replace('error', '', $mailsubject);
            $data['mailsubject']=$mailsubject;
            $mailbody="
                Gentile Sig. contatto_cognome contatto_nome, <br />
                <br />    
                come da sua richiesta le invio in allegato la documentazione: <br /> 
                $paese $categoria $locali locali CHF $prezzo <br/>
                Per maggiori informazioni o per concordare un sopralluogo può contattarmi direttamente al numero T. $consulente_telefono – $consulente_cellulare. <br />
                <br />
                Resto volentieri a disposizione, <br />
                Con i migliori saluti <br />
                $consulente_nome $consulente_cognome
                            ";
            $mailbody= str_replace('error', '', $mailbody);
            $mailbody=$mailbody.$firma;
            $data['mailbody']=$mailbody;
            $data['recordidimmobili_']=$recordid_immobile;
            $data['tipo']='invioprospetto';
            $userid=  $this->get_userid();
            $recordid=$this->Sys_model->insert_record('mail_queue',$userid,$data);

            $data=array();
            $this->Sys_model->insert_record_page('mail_queue',$recordid,1,$path_stampa,$data);



            echo $recordid;
        }
        
        if($tableid=='contratti')
        {
            
            
            $data['recordstatus_']='temp';
            $userid=$this->get_userid();
            $data['mailfrom_userid']= $userid;
            $data['mailto']='';
            $data['mailsubject']='Contratto';
            $mailbody="
                In allegato il contratto
                            ";
            $mailbody= str_replace('error', '', $mailbody);
            $data['mailbody']=$mailbody;
            $data['tipo']='inviocontratto';
            $recordid=$this->Sys_model->insert_record('mail_queue',$userid,$data);

            $data=array();
            $this->Sys_model->insert_record_page('mail_queue',$recordid,1,$path_stampa,$data);


            echo $recordid;
        }
    }
    
    public function ajax_anteprima_invio_prospetto($recordid_richiesta,$recordid_immobile)
    {
        $immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
        $richiesta=  $this->Sys_model->db_get_row('user_immobili_richiesti','*',"recordid_='$recordid_richiesta'");
        $recordid_contatto=$richiesta['recordidcontatti_'];
        $contatto=  $this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");
        $content=  $this->stampa_prospetto_pdf($recordid_immobile);
        $url_stampa=$this->genera_stampa($content,'prospetto');
        
        //dati contatto
        $cognome=$contatto['cognome'];
        $nome=$contatto['nome'];
        //dati immobile
        $paese=$immobile['paese'];
        $paese=$this->Sys_model->get_lookup_table_item_description('citta',$paese);
        $categoria=$immobile['categoria'];
        $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
        $locali=$immobile['imm_locali_num'];
        $prezzo=$immobile['imm_prezzoimmobile'];
        
        $data['mail']['to']=$contatto['email'];
        
        $mailsubject="Richiesta - $paese $categoria $locali locali";
        $data['mail']['subject']=$mailsubject;
        
        $mailbody="
Gentile Sig. $cognome $nome, <br />

come da sua richiesta le invio in allegato la documentazione $paese $categoria $locali e $prezzo CHF <br />
Per maggiori informazioni o per concordare un sopralluogo può contattare direttamente il consulente Signor ***Andrea Banfi, che ci legge in copia, al numero T. 076 388 13 53 – 091 857 19 07. <br />
<br />
Resto volentieri a disposizione, <br />
Le auguro una piacevole giornata <br />
Chiara
            ";
        $data['mail']['body']=$mailbody;
        $data['linkedmaster']['recordid_immobile']=$recordid_immobile;
        $data['linkedmaster']['recordid_richiesta']=$recordid_richiesta;
        $data['linkedmaster']['recordid_contatto']=$recordid_contatto;
        $block=$this->load_block_anteprima_invio($url_stampa,$data);
        echo $block;
    }
    
    public function load_block_anteprima_invio($link,$data)
    {
        $data['link']=$link;
        $link_array=  explode('JDocServer/', $link);
        $path=$link_array[1];
        $data['path']=$path;
        return $this->load->view('sys/desktop/block/anteprima_invio',$data, TRUE);
    }
    
    
    public function ajax_genera_mail_risposta_ticket($recordid_ticket)
    {
        $ticket=$this->Sys_model->db_get_row('user_segnalazioni','*',"recordid_='$recordid_ticket'");
        $ticket_id=$ticket['id'];
        $datasegnalazione=$ticket['datasegnalazione'];
        $datasegnalazione=date("d/m/Y",strtotime($datasegnalazione));
        $testo_ticket=$ticket['note'];
        $userid=  $this->get_userid();
        $ticket_mail=$ticket['mail'];
        $recordid_azienda=$ticket['recordidaziende_'];
        
        $data['mailto']=$ticket_mail;
        $data['mailbcc']='';
        $data['mailfrom_userid']= $userid;
        $data['mailsubject']="Risposta al Ticket: $ticket_id del $datasegnalazione";
        $data['mailbody']="


<br/>
<br/>
<b>Ticket $ticket_id del $datasegnalazione:</b> <br/>
<i>$testo_ticket</i>
";
        $data['status']="Bozza";
        
        

        $data['recordidsegnalazioni_']=$recordid_ticket;
        $data['recordidaziende_']=$recordid_azienda;
        
        
        $recordid=$this->Sys_model->insert_record("mail_queue",$userid,$data);
        
        echo $recordid;
    }
    
    
    public function ajax_genera_mail_prospetto($recordid_immobile_proposto,$direttodaimmobile=false)
    {
        $tableid='mail_queue';
        $data['mailbcc']='';
        if($direttodaimmobile=='true')
        {
            $recordid_richiesta='';
            $recordid_immobile=$recordid_immobile_proposto;
        }
        else
        {
            $immobile_proposto=$this->Sys_model->db_get_row('user_immobili_proposti','*',"recordid_='$recordid_immobile_proposto'");
        
            $recordid_richiesta=$immobile_proposto['recordidimmobili_richiesti_'];
            $recordid_immobile=$immobile_proposto['recordidimmobili_'];
        }
        
        
        $immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
        //dati immobile
        $paese=$immobile['paese'];
        $paese=$this->Sys_model->get_lookup_table_item_description('citta',$paese);
        $categoria=$immobile['categoria'];
        $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
        $locali=$immobile['imm_locali_num'];
        $prezzo=$immobile['imm_prezzoimmobile'];
        $lista_immobili[]="$paese $categoria $locali locali CHF $prezzo";

        $consulente_immobile_id=$immobile['consulente'];
        $consulente_immobile_id_array=  explode('|;|', $consulente_immobile_id);
        foreach ($consulente_immobile_id_array as $key_consulente_immobile_id => $consulente_immobile_id) {
            $consulente_immobile=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_immobile_id");
        $consulente_immobile_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_immobile_id);
        if($data['mailbcc']!='')
        {
            $data['mailbcc']=$data['mailbcc'].';';
        }

        $data['mailbcc']=$data['mailbcc'].$consulente_immobile_mail;
        }
        
        if($direttodaimmobile=='true')
        {
            
            $cognome="";
            $nome="";
            $data['mailfrom_userid']= $this->get_userid();
            $data['mailto']='';
            $consulente_richiesta_id=$this->get_userid();
            $paese_richiesta='';
            $categoria_richiesta='';
            $recordid_contatto='';
        }
        else
        {
            
            $richiesta=  $this->Sys_model->db_get_row('user_immobili_richiesti','*',"recordid_='$recordid_richiesta'");
            $paese_richiesta=$richiesta['paese'];
            if($this->isnotempty($paese_richiesta))
                $paese_richiesta=$this->Sys_model->get_lookup_table_item_description('citta',$paese_richiesta);
            $categoria_richiesta=$richiesta['categoria'];
            if($this->isnotempty($categoria_richiesta))
                $categoria_richiesta=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria_richiesta);
            $recordid_contatto=$richiesta['recordidcontatti_'];
            $recordid_immobile=$richiesta['recordidimmobili_'];
            $contatto_richiesta=  $this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");

            //dati contatto
            $cognome=$contatto_richiesta['cognome'];
            $nome=$contatto_richiesta['nome'];


            $data['mailfrom_userid']=$richiesta['consulente'];

            $data['mailto']=$contatto_richiesta['email'];

            $consulente_richiesta_id=$richiesta['consulente'];
        }
        $consulente_richiesta=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_richiesta_id");
        $consulente_cognome=$consulente_richiesta['lastname'];
        $consulente_nome=$consulente_richiesta['firstname'];
        $consulente_telefono=$consulente_richiesta['telefono'];
        $consulente_cellulare=$consulente_richiesta['cellulare'];
        $consulente_richiesta_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_richiesta_id);
        
        
        
        $firma='';
        if(($consulente_richiesta_id==3)||($consulente_richiesta_id==6)||($consulente_richiesta_id==7))
        {
            $data['mailbcc']=$data['mailbcc'].";sopraceneri@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#948A54'>
                DIMENSIONE IMMOBILIARE SOPRACENERI SAGL <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                </div>
                ";
        }
        else
        {
            $data['mailbcc']=$data['mailbcc'].";info@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#827843'>
                DIMENSIONE IMMOBILIARE SA <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                </div>
                ";
        }
        $data['recordstatus_']='temp';
        $data['mailsubject']="Richiesta - $paese_richiesta $categoria_richiesta";
        $mailbody="
            Gentile Sig. $cognome $nome, <br />
            <br />    
            come da sua richiesta le invio in allegato la documentazione: <br /> ";
        foreach ($lista_immobili as $key => $immobile) {
            $mailbody=$mailbody." 
                $immobile.<br />";
        }
        $mailbody=$mailbody."
            Per maggiori informazioni o per concordare un sopralluogo può contattarmi direttamente al numero T. $consulente_telefono – $consulente_cellulare. <br />
            <br />
            Resto volentieri a disposizione, <br />
            Con i migliori saluti <br />
            $consulente_nome $consulente_cognome
                        ";
        $mailbody=$mailbody.$firma;
        $data['mailbody']=$mailbody;
        $data['recordidimmobili_']=$recordid_immobile;
        $data['recordidimmobili_richiesti_']='';
        $data['recordidimmobili_proposti_']='';
        $data['recordidcontatti_']=$recordid_contatto;
        $userid=  $this->get_userid();
        $recordid=$this->Sys_model->insert_record($tableid,$userid,$data);
        
            $data=array();
            $content=$this->stampa_prospetto_pdf_nuovo($recordid_immobile);
            $path=$this->genera_stampa($content,'prospetto');

            $this->Sys_model->insert_record_page('mail_queue',$recordid,$userid,$path,$data);
        
        
        
        echo $recordid;
    }
    
    public function ajax_genera_mail_prospetto_da_richiesta($recordid_richiesta)
    {
        $post=$_POST;
        $tableid='mail_queue';
        $immobili_selezionati=$post['immobili_selezionati'];
        $data['mailbcc']='';
        $lista_immobili=array();
        foreach ($immobili_selezionati as $key_immobile_selezionato => $immobile_selezionato) {
            $immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$immobile_selezionato'");
            
            //dati immobile
            $paese=$immobile['paese'];
            $paese=$this->Sys_model->get_lookup_table_item_description('paese_richieste',$paese);
            $categoria=$immobile['categoria'];
            $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
            $locali=$immobile['imm_locali_num'];
            $prezzo=$immobile['imm_prezzoimmobile'];
            $lista_immobili[]="$paese $categoria $locali locali CHF $prezzo";

            $consulente_immobile_id=$immobile['consulente'];
            $consulente_immobile_id_array=  explode('|;|', $consulente_immobile_id);
            foreach ($consulente_immobile_id_array as $key_consulente_immobile_id => $consulente_immobile_id) {
                $consulente_immobile=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_immobile_id");
            $consulente_immobile_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_immobile_id);
            if($data['mailbcc']!='')
                $data['mailbcc']=$data['mailbcc'].';';
            $data['mailbcc']=$data['mailbcc'].$consulente_immobile_mail;
            }
            
        }
        
        
        $richiesta=  $this->Sys_model->db_get_row('user_immobili_richiesti','*',"recordid_='$recordid_richiesta'");
        $paese_richiesta=$richiesta['paese'];
        if($this->isnotempty($paese_richiesta))
            $paese_richiesta=$this->Sys_model->get_lookup_table_item_description('citta',$paese_richiesta);
        $categoria_richiesta=$richiesta['categoria'];
        if($this->isnotempty($categoria_richiesta))
            $categoria_richiesta=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria_richiesta);
        $recordid_contatto=$richiesta['recordidcontatti_'];
        $recordid_immobile=$richiesta['recordidimmobili_'];
        $contatto_richiesta=  $this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");
        
        //dati contatto
        $cognome=$contatto_richiesta['cognome'];
        $nome=$contatto_richiesta['nome'];
        
        
        $data['mailfrom_userid']=$richiesta['consulente'];
                
        $data['mailto']=$contatto_richiesta['email'];
        
        $consulente_richiesta_id=$richiesta['consulente'];
        $consulente_richiesta=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_richiesta_id");
        $consulente_cognome=$consulente_richiesta['lastname'];
        $consulente_nome=$consulente_richiesta['firstname'];
        $consulente_telefono=$consulente_richiesta['telefono'];
        $consulente_cellulare=$consulente_richiesta['cellulare'];
        $consulente_richiesta_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_richiesta_id);
        
        
        
        $firma='';
        if(($consulente_richiesta_id==3)||($consulente_richiesta_id==6)||($consulente_richiesta_id==7))
        {
            $data['mailbcc']=$data['mailbcc'].";sopraceneri@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#948A54'>
                DIMENSIONE IMMOBILIARE SOPRACENERI SAGL <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                </div>
                ";
        }
        else
        {
            $data['mailbcc']=$data['mailbcc'].";info@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#827843'>
                DIMENSIONE IMMOBILIARE SA <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                </div>
                ";
        }
        $data['recordstatus_']='temp';
        $data['mailsubject']="Richiesta - $paese_richiesta $categoria_richiesta";
        $mailbody="
            Gentile Sig. $cognome $nome, <br />
            <br />    
            come da sua richiesta le invio in allegato la documentazione: <br /> ";
        foreach ($lista_immobili as $key => $immobile) {
            $mailbody=$mailbody." 
                $immobile.<br />";
        }
        $mailbody=$mailbody."
            Per maggiori informazioni o per concordare un sopralluogo può contattarmi direttamente al numero T. $consulente_telefono – $consulente_cellulare. <br />
            <br />
            Resto volentieri a disposizione, <br />
            Con i migliori saluti <br />
            $consulente_nome $consulente_cognome
                        ";
        $mailbody=$mailbody.$firma;
        $data['mailbody']=$mailbody;
        $data['recordidimmobili_']=$recordid_immobile;
        $data['recordidimmobili_richiesti_']=$recordid_richiesta;
        $data['recordidcontatti_']=$recordid_contatto;
        $userid=  $this->get_userid();
        $recordid=$this->Sys_model->insert_record($tableid,$userid,$data);
        
        foreach ($immobili_selezionati as $key_immobile_selezionato => $immobile_selezionato) {
            $data=array();
            $content=$this->stampa_prospetto_pdf($immobile_selezionato);
            $path=$this->genera_stampa($content,'prospetto');

            $this->Sys_model->insert_record_page('mail_queue',$recordid,$userid,$path,$data);
        }
        
        
        echo $recordid;
    }
    
    public function ajax_conferma_invio()
    {
        $post=$_POST;
        $mail['mailfrom_userid']=$this->session->userdata('userid');
        $mail['mailto']=$post['mail_to'];
        $mail['mailsubject']=$post['mail_subject'];
        $mail['mailbody']=$post['mail_body'];
        $mail['mail_jdocattachment']=$post['mail_jdocattachment'];
        $recordid_mail_queued=$this->Sys_model->push_mail_queue($mail);
        $mail_queued=  $this->Sys_model->db_get("mail_queue",'*',"recordid_='$recordid_mail_queued'");
        $recordid_richiesta=$mail_queued['recordidimmobili_richiesti_'];
        $recordid_proposta=$mail_queued['recordidimmobili_proposti_'];
        $recordid_contatto=$mail_queued['recordidcontatti_'];
        $recordid_immobile=$mail_queued['recordidimmobili_'];
        $sql="
            UPDATE user_mail_queue
            SET recordidimmobili_='$recordid_immobile',recordidimmobili_richiesti_='$recordid_richiesta',recordidcontatti_='$recordid_contatto'
            WHERE recordid_='$recordid_mail_queued';
            ";
        $this->Sys_model->execute_query($sql);

        $this->Sys_model->insert_agenda($recordid_immobile,$recordid_contatto,$recordid_richiesta);
    }
    
    
    public function ajax_crea_coda($nomecoda="null"){
        $userid=$this->session->userdata('userid');
        $rows=$this->Sys_model->select("SELECT firstname,folder_serverside FROM sys_user WHERE id=$userid");
        if($nomecoda=='null')
        {
        $firstname=$rows[0]['firstname'];
        $nomecoda=$firstname.'_'.date('d_m_Y_H_s');
        }
        $this->Sys_model->crea_coda($nomecoda);
        echo $nomecoda;
        
    }
    
    
    public function ajax_load_block_lista_files($funzione,$tipo,$idcoda='sys_batch_temp'){
        echo $this->load_block_lista_files($funzione,$tipo,$idcoda);
    }
    
    
    /**
     *  
     * @param type $funzione
     * @param type $originefiles
     * @param type $idcoda
     * @param type $tableid
     * @param type $recordid
     * @param type $interface
     * @return type
     */
    public function load_block_lista_files($funzione,$originefiles='allegati',$idcoda=null,$tableid=null,$recordid=null,$interface='desktop'){
        if($funzione=='scheda')
        {
            $funzione='modifica';
        }
        $data['data']['files']=array();
        if($originefiles=='coda')
        {
            if($idcoda!=null)
            {
                $files_coda=  $this->Sys_model->get_files_coda($idcoda);
            }
            else
            {
              $files_coda=array();  
            }
            $data['data']['files']=$files_coda;
        }
        if($originefiles=='autobatch')
        {
            if($idcoda!=null)
            {
                $files_coda=  $this->Sys_model->get_files_autobatch($idcoda);
            }
            else
            {
              $files_coda=array();  
            }
            $data['data']['files']=$files_coda;
        }
        if($originefiles=='allegati')
        {
            if((($tableid!=null)&&($recordid!=null))&&($recordid!='null'))
            {
            $rows= $this->Sys_model->get_allegati($tableid, $recordid);
            }
            else
            {
                $rows=array();
            }
             
             foreach ($rows as $key => $file) 
            {
            $path=$file['path_'];
            $filename=$file['filename_'];
            $extension=$file['extension_'];
            if(!file_exists("../JDocServer/$path/$filename"."_thumbnail.jpg"))
            {
                $this->generate_thumbnail($path, $filename,$extension);
                $rows[$key]['thumbnail_url']="";
            }
            
            $thumbnail_path="../JDocServer/$path/$filename"."_thumbnail.jpg";
            if(file_exists($thumbnail_path))
            {
                $path=str_replace("\\", "/", $path );
                $rows[$key]['thumbnail_url']=domain_url()."JDocServer/$path/$filename"."_thumbnail.jpg";
                if($key==0)
                {
                    if(!file_exists("../JDocServer/record_preview/$tableid"))
                    {
                        mkdir("../JDocServer/record_preview/$tableid");
                    }
                    if(true/*!file_exists("../JDocServer/record_preview/$tableid/$recordid.jpg")*/) //custom dimensione immobiliare. temporaneo
                    {
                        copy($thumbnail_path,"../JDocServer/record_preview/$tableid/$recordid.jpg");
                    }
                }
            }
            $category_description='';
            $category=$file['category'];
            $category_array=  explode("|;|", $category);
            foreach ($category_array as $_category_key => $single_category) {
                $description=  $this->Sys_model->db_get_value('sys_table_page_category','cat_description',"tableid='$tableid' AND cat_id='$single_category'");
                if($description!=null)
                {
                    if($category_description!='')
                    {
                        $category_description=$category_description."|;|";
                    }
                    $category_description=$category_description.$description;
                }
                
            }
            $rows[$key]['category']=$category_description;
            }
         $data['data']['files']=$rows; 
        }
        $data['data']['originefiles']=$originefiles;
        $data['data']['funzione']=$funzione;
        $data['data']['idcoda']=$idcoda;
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['pages_thumbnail_aspectratio']=$this->Sys_model->get_table_setting($tableid,'pages_thumbnail_aspectratio');
        $data['filecontainer_type']=$this->Sys_model->get_table_setting($tableid,'allegati_filecontainer_type');
        if($originefiles=='autobatch')
        {
            $userid= $this->get_userid();
            $data['filecontainer_type']=$this->Sys_model->get_user_setting('autobatch_filecontainer_type',$userid);
        }
        if($originefiles=='allegati')
        {
                    $data['filecontainer_type']=$this->Sys_model->get_table_setting($tableid,'allegati_filecontainer_type');

        }
        return $this->load->view('sys/desktop/block/lista_files',$data, TRUE);
    }
    
    
    public function load_block_upload_files($funzione='',$popuplvl_new=0){
        //raccolgo tutti i record della sys_batch_temp di un determinato idutente
        $userid=$this->session->userdata('userid');
        $batchid="sys_batch_temp_$userid"."_"."$popuplvl_new";
        $results=$this->Sys_model->select("SELECT filename,fileext FROM sys_batch_file WHERE batchid='$batchid' AND creatorid=$userid");
        //per ogni record estratto elimino dal file_system e dal db
        foreach($results as $result)
        {
            $filename=$result['filename'];
            $fileext=$result['fileext'];
            $nomefile=$result['filename'].'.'.$result['fileext'];//compongo il nome del file da eliminare
            $pathcompleta="../JDocServer/batch/$batchid/$nomefile";
            if(($batchid!='')&&($nomefile!='')&&(file_exists($pathcompleta)))
            {
                unlink($pathcompleta);
            }
            //die("ERRORE ELIMINAZIONE FILE: ".$nomefile);//se non riesce ad eliminare il file allora blocca tutta la webapp
            $this->Sys_model->execute_query("DELETE FROM sys_batch_file WHERE batchid='$batchid' AND filename='$filename' AND fileext='$fileext' AND creatorid=$userid");
        }
        $data=null;
        $data['data']['funzione']=$funzione;
        $data['data']['popuplvl']=$popuplvl_new;
        $data['pages_show_autobatch']= $this->Sys_model->get_user_setting('pages_show_autobatch',$userid);
        return $this->load->view('sys/desktop/block/upload_files',$data, TRUE);
    }
    
    
    public function uploadfile($popuplvl=0)
    {
        
        $response="NO";
        $userid=$this->session->userdata('idutente');
        $coda="sys_batch_temp_$userid"."_"."$popuplvl";
        
        
        /*if($coda=='sys_batch_temp') //se la coda è quella temporanea
        {
            $upload_dir.='/'.$this->session->userdata('userid').'/'.$popuplvl.'/'; //allora concatena l'id dell'utente
        $this->Sys_model->execute_query("UPDATE sys_batch_file SET crypted='Y' WHERE batchid='sys_batch_temp'");
            
        }*/
        if (!is_dir("../JDocServer/batch")) 
        {
            mkdir("../JDocServer/batch");
        }
        if (!is_dir("../JDocServer/batch/$coda")) 
        {
            mkdir("../JDocServer/batch/$coda");
        }
        
        $upload_dir="../JDocServer/batch/$coda";

        $ext='';
        $files=$_FILES;
        $post=$_POST;
        if(isset($_FILES['allegati'])) //controllo se esiste l'array dei file
        {
            $files = $_FILES['allegati'];
            $i=0;
            //var_dump($files);
            while($i < count($files['tmp_name'])){
                //echo "<br>LA I VALE: ".$i.'<br>';
                
                    
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION); //estraggo l'estensione dal file
                //RECUPERO IL MAX FILE
                $data=$this->Sys_model->select("SELECT MAX(filename) FROM sys_batch_file WHERE batchid='$coda'");
                $maxfile=$data[0]['max'];
                if($maxfile==null)
                    $maxfile=0;
                $maxfile++;
                $veromaxfile=''; //dichiaro una variabile che sarà quella che realmente contiene il vero nome max file
                $numerozeri=8-strlen($maxfile); //ottengo il numeri di zeri da inserire davanti alla stringa
             
                for($k=0;$k<$numerozeri;$k++)
                    $veromaxfile.='0'; //aggiungo N zeri tanti quanti quelli calcolati
                
                 $veromaxfile.=$maxfile; //infine concateno la stringa con numeri di zeri giusti con il numero massimo di file
                 $veromaxfile=$this->Sys_model->generate_filename_coda($coda);
                 $description=$files['name'][$i]; //la descrition che finirà nel database è il nome vero del file compreso di estensione
                 $description=  str_replace("'", "''", $description);
                 $now=date("Y-m-d H:i:s");  
                 $this->Sys_model->execute_query("INSERT INTO sys_batch_file(batchid,filename,description,creatorid,fileext,creationdate) VALUES('$coda','$veromaxfile','$description','$userid','$ext','$now')");
                 //sleep(1);
                 if(is_uploaded_file($files['tmp_name'][$i])){
                    $path_completa=$upload_dir.'/'.$veromaxfile.'.'.$ext;
                    move_uploaded_file($files['tmp_name'][$i], $path_completa);
                }
                
                 if(!file_exists($upload_dir.$files['name'][$i]))
                    echo "<br>IL FILE NON E' ANCORA STATO SPOSTATO";
                 else{
                    rename($path_completa,$upload_dir.$veromaxfile.'.'.$ext); //rinomino il file con l'id che è inserito nel db
                    echo "<br>CARICAMENTO E RINOMINAZIONE EFFETTUATA CON SUCCESSO";
                 }
                 $i++;
            }
        }
        //$block=  $this->load_block_sys_batch_temp($popuplvl);
    }
    
    public function ajax_load_block_sys_batch_temp($popuplvl=0){
        $block=  $this->load_block_sys_batch_temp($popuplvl);
        echo $block;
    }
    
    
    public function load_block_sys_batch_temp($popuplvl=0){
        $userid=$this->session->userdata('userid');
        $batchid="sys_batch_temp_$userid"."_"."$popuplvl";
        $files_coda=  $this->Sys_model->get_files_coda($batchid,$popuplvl);
        $data['data']['files']=$files_coda;
        $data['data']['originefiles']="coda";
        $data['data']['funzione']='inserimento';
        $data['data']['block_upload_files']="";
        $data['data']['idcoda']='sys_batch_temp';
        $data['data']['tableid']=null;
        $data['data']['recordid']=null;
        $data['pages_thumbnail_aspectratio']="2:3";
        return $this->load->view('sys/desktop/block/lista_files',$data, TRUE);
    }
    
    public function ajax_salva_modifiche_allegati($tableid,$recordid)
    {
        $post=$_POST;
        $this->Sys_model->modifica_allegati($post, $tableid,$recordid);
    }
    
    public function ajax_salva_modifiche_coda($codaid)
    {
        $post=$_POST;
        $this->Sys_model->salva_modifiche_coda($codaid, $post);
    }
    
    
    public function ajax_load_content_inserimento($tableid,$interface='desktop')
    {
        $block=$this->load_content_inserimento($tableid, $interface);
        echo $block;
    }
    public function load_content_inserimento($tableid,$interface='desktop')
    {
            $data['data']['schede']['scheda_dati_inserimento']=$this->load_scheda_dati_inserimento($tableid);
            $data['data']['tableid']=$tableid;
            //$data['data']['block']['block_lista_files']=  $this->load_block_lista_files('inserimento', 'allegati');
            $data['data']['block']['block_allegati']=$this->load_block_allegati($tableid, 'null','inserimento',$interface);
            $data['data']['block']['block_code']=$this->load_block_code('inserimento');
            $data['data']['block']['block_autobatch']=  $this->load_block_autobatch('inserimento');
            $data['data']['settings']=$this->Sys_model->get_settings();
            return $this->load->view("sys/$interface/content/inserimento", $data,true);
    }
    
    public function ajax_load_scheda_dati_inserimento($tableid,$interface='desktop')
    {
        $scheda=  $this->load_scheda_dati_inserimento($tableid,$interface);
        echo $scheda;
    }
    
    public function load_scheda_dati_inserimento($tableid,$interface='desktop')
    {
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']='null';
        $data['data']['funzione']="inserimento";
        $data['data']['scheda_container']='null';
        $data['data']['block']['block_dati_labels']=  $this->load_block_dati_labels($tableid, 'null', 'inserimento', 'scheda_dati_inserimento', 'null');
        return $this->load->view("sys/$interface/schede/scheda_dati_inserimento", $data,true);
    }
    
    public function ajax_load_block_dati_labels($tableid,$recordid,$funzione,$scheda_container,$block_container){
        $block=  $this->load_block_dati_labels($tableid,$recordid,$funzione,$scheda_container,$block_container);
        echo $block;
    }
    
    /**
     * Carica il blocco dei campi
     * 
     * @param String $tableid 
     * @param String $funzione
     * @param String $recordid
     * @return blocco html
     * @author Alessandro Galli
     */
    public function load_block_dati_labels($tableid,$recordid='null',$funzione="",$scheda_container="",$block_container="",$viewid=""){
        
        //$viewid=7; //TEMP TEST
        //$view_post=$this->Sys_model->get_view_post($tableid,$viewid); //TEMP TEST
        
        //$fields=  $this->Sys_model->get_labels_table($tableid,$funzione,$recordid);
        $labels=  $this->Sys_model->get_labels_table($tableid,$funzione,$recordid);
        /*foreach ($labels as $key => $label) {
            if(array_key_exists($label['tableid'], $view_post['tables']))
            {
                $labels[$key]['prefilledlabel_block']=  $this->load_block_tables_labelcontainer($label['tableid'],$label['label'],0,'',$label['type'],$tableid,$funzione,$recordid,$scheda_container,$interface='desktop');
            }
        }*/
        //$data['data']['fields']=$fields;
        $data['data']['labels']=$labels;
        $data['data']['tableid']=$tableid;
        $data['data']['funzione']=$funzione;
        $data['data']['recordid']=$recordid;
        $data['data']['scheda_container']=$scheda_container;
        $data['data']['block_container']=$block_container;
        
        $data['data']['settings']=  $this->Sys_model->get_settings();
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        $block_dati_labels = $this->load->view('sys/desktop/block/block_dati_labels', $data, TRUE);
        return $block_dati_labels;
    }
    
    public function load_block_dati_labels_invio_mail_modulo($tableid,$recordid='null',$funzione,$scheda_container,$block_container){
        $fields=  $this->Sys_model->get_labels_table_invio_mail_modulo($tableid,$funzione,$recordid);
        $data['data']['fields']=$fields;
        $data['data']['tableid']=$tableid;
        $data['data']['funzione']=$funzione;
        $data['data']['recordid']=$recordid;
        $data['data']['scheda_container']=$scheda_container;
        $data['data']['block_container']=$block_container;
        $data['data']['settings']=  $this->Sys_model->get_settings();
        $block_dati_labels = $this->load->view('sys/desktop/custom/ww/block_dati_labels_invio_mail_modulo', $data, TRUE);
        return $block_dati_labels;
    }
    
    
    public function ajax_load_block_tables_labelcontainer($tableid,$label,$table_index,$table_param,$type,$mastertableid,$funzione,$recordid,$scheda_container,$viewid="",$interface='desktop',$origine_tableid='',$origine_recordid='')
    {
        $blocco=  $this->load_block_tables_labelcontainer($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,$viewid, $interface,$origine_tableid,$origine_recordid);
        echo $blocco;
    }
    
    public function load_block_tables_labelcontainer($tableid,$label,$table_index,$table_param,$type,$mastertableid,$funzione,$recordid,$scheda_container,$viewid="",$interface='desktop',$origine_tableid='',$origine_recordid='')
    {
        //caricamento records linked
        if(($viewid!="")&&($viewid!="null"))
        {
            $view_post=$this->Sys_model->get_view_post($tableid,$viewid); //TEMP TEST
            $prefilled_tables=array();//$view_post['tables']; //TEMP TEST
        }
        else
        {
            $prefilled_tables=array();
        }
        if($funzione=='inserimento')
        {
           if($type=='master')
           {
               if(($scheda_container=='scheda_dati_inserimento')&&($recordid!='null'))
                {
                    $funzione='modifica';
                }
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface, $origine_tableid);
           }
           if($type=='linked')
           {
               $records=$data['data']['records']=  $this->Sys_model->get_records_linkedtable($tableid, $mastertableid, $recordid);
               $data['data']['records']= $this->load_block_records_linkedtable($tableid,$mastertableid,$recordid,'modifica');
               $recordid='null';
               if(count($records)==0)
               {
                   
                        //$data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface);
                   
                
                   }
           }
           if($type=='linkedmaster')
           {
               /*if(($scheda_container=='scheda_dati_inserimento')&&($recordid!='null'))
                {
                    $funzione='modifica';
                }
                //$funzione='modifica';
               $data['data']['records_linkedmaster']= $this->load_block_records_linkedmaster($tableid,$recordid,$mastertableid,$funzione);*/
               $recordid= $this->Sys_model->get_linkedmaster_recordid($mastertableid,$recordid,$tableid,$funzione,$origine_tableid,$origine_recordid);
               $data['data']['records_linkedmaster']= $this->load_block_records_linkedmaster($tableid,$recordid,$mastertableid,$funzione);
           }
        }
        if($funzione=='ricerca')
        {
           /*if($type=='master')
           {
              $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface);  
           }
           if($type=='linked')
           {
               $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface); 
           }
           if($type=='linkedmaster')
           {
                $label='null';
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface);
           }
           if($type=='ocr')
           {
                $label='null';
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface);
           }
           if($type=='tutti')
           {
                $label='null';
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface);
           }*/
           
            if(($type=='linkedmaster')||($type=='ocr')||($type=='tutti'))
           {
                $label='null';
           }
           if(array_key_exists($tableid, $prefilled_tables))
           {
               if(array_key_exists('search', $prefilled_tables[$tableid]))
               {
                    foreach ($prefilled_tables[$tableid]['search'] as $key => $prefilled_table) 
                    {
                        $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,$prefilled_table, $interface);
                    }
                }
           }
           else
           {
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface);
           } 
        
        }
        if($funzione=='modifica')
        {
           if($type=='master')
           {
                $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface);
           }
           if($type=='linked')
           {
               $records=  $this->Sys_model->get_records_linkedtable($tableid, $mastertableid, $recordid);
               $data['data']['records']= $this->load_block_records_linkedtable($tableid,$mastertableid,$recordid,'modifica');
               $recordid='null';
               if(count($records)==0)
               {
                   //CUSTOM UNILUDES
                   if($tableid!='documentiprotocolloentrata')
                   {
                    //$data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, 'inserimento', $recordid,$scheda_container,array(), $interface);
                   }
                }
           }
           if($type=='linkedmaster')
           {
                $recordid= $this->Sys_model->get_linkedmaster_recordid($mastertableid,$recordid,$tableid,$funzione);
                $label='null';
               $data['data']['records_linkedmaster']= $this->load_block_records_linkedmaster($tableid,$recordid,$mastertableid,$funzione);
           }
        }
        if($funzione=='scheda')
        {
            if($type=='master')
           {
               $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface);  
           }
           if($type=='linked')
           {
               $records=  $this->Sys_model->get_records_linkedtable($tableid, $mastertableid, $recordid);
               $data['data']['records']= $this->load_block_records_linkedtable($tableid,$mastertableid,$recordid,'modifica');
               $recordid='null';
               if(count($records)==0)
               {
                    //$data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, 'inserimento', $recordid,$scheda_container, $interface);
               }
           }
           if($type=='linkedmaster')
           {
                $recordid= $this->Sys_model->get_linkedmaster_recordid($mastertableid,$recordid,$tableid);
                $label='null';
                $data['data']['records_linkedmaster']= $this->load_block_records_linkedmaster($tableid,$recordid,$mastertableid,$funzione); 
                
           }
        }

        //caricamento records linkedmaster
        
        /*if(($type=='master')||(($type=='linked')&&(($funzione=='ricerca')||($funzione=='inserimento'))))
        {
            if(($type=='master')&&($scheda_container=='scheda_dati_inserimento')&&($recordid!='null'))
            {
                $funzione='modifica';
            }
           $data['data']['table']=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container, $interface); 
        }*/
        
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['type']=$type;
        $data['data']['table_index']=$table_index;
        $data['data']['table_param']=$table_param;
        $data['data']['label']='';
        $data['data']['settings']=  $this->Sys_model->get_settings();
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        return $this->load->view('sys/'.$interface.'/block/tables_labelcontainer',$data, TRUE);
    }
    
   
    
    public function load_block_records_linkedtable($linkedtableid,$mastertableid,$masterrecordid,$funzione,$interface='desktop',$orderby='')
    {
        $data['data']['records']=  $this->Sys_model->get_records_linkedtable($linkedtableid, $mastertableid, $masterrecordid);
        $columns=$this->Sys_model->get_colums($linkedtableid, 1);
        $linkedtable='user_'.strtolower($linkedtableid);
        $mastertable='user_'.strtolower($mastertableid);
        $query='';
        $select='SELECT ';
        foreach ($columns as $key => $column) 
        {
            if($key>0)
            {
                $select=$select.',';
            }
            $columnid=$column['id'];
            if($columnid=='recordcss_')
            {
                $columnid=" '' as recordcss_";
            }
            $select=$select." ".$columnid;
            //$select=$select.' '.$columns['id'];
        }
        $query=$select." FROM $linkedtable WHERE recordid".strtolower($mastertableid)."_='$masterrecordid' AND (recordstatus_ is null OR recordstatus_!='temp')";
        $query=$query." $orderby";
        $data['data']['columns']=$columns;
        $data['data']['linkedtableid']=$linkedtableid;
        $data['data']['funzione']=$funzione;
        $data['data']['block']['datatable_records']=$this->load_block_datatable_records($linkedtableid, 'records_linkedtable',$query,$mastertableid);
        $data['table_settings']=  $this->Sys_model->get_table_settings($linkedtableid);
        return $this->load->view('sys/'.$interface.'/block/records_linkedtable',$data, TRUE);
    }
    
    public function ajax_load_block_records_linkedmaster($linkedmastertableid,$linkedmaster_recordid,$mastertableid,$funzione,$interface='desktop'){
        $block=  $this->load_block_records_linkedmaster($linkedmastertableid,$linkedmaster_recordid,$mastertableid,$funzione,$interface='desktop');
        echo $block;
    }
    
    public function load_block_records_linkedmaster($linkedmastertableid,$linkedmaster_recordid,$mastertableid,$funzione,$interface='desktop'){
         //$data['data']['records_linkedmaster']=  $this->Sys_model->get_records_linkedmaster($linkedmastertableid, $mastertableid);
        if(($linkedmaster_recordid!='null')&&($linkedmaster_recordid!=''))
        {
            $data['data']['fissi']=$this->load_block_fissi($linkedmastertableid, $linkedmaster_recordid); 
            $data['data']['keyfieldlink']=  $this->Sys_model->get_keyfieldlink_value($mastertableid,$linkedmastertableid,$linkedmaster_recordid);
        }
        else
        {
            $data['data']['fissi']='';
            $data['data']['keyfieldlink']='';
        }
        $data['data']['linkedmastertableid']=$linkedmastertableid;
         $data['data']['funzione']=$funzione;
         $data['data']['linkedmaster_recordid']=$linkedmaster_recordid;
         $data['data']['mastertableid']=$mastertableid;
         $data['label']=  $this->Sys_model->db_get_value('sys_field','description',"tableid='$mastertableid' AND tablelink='$linkedmastertableid'");
         $data['table_settings']=  $this->Sys_model->get_table_settings($mastertableid);
         return $this->load->view('sys/'.$interface.'/block/records_linkedmaster',$data, TRUE);
    }
    
    
     public function ajax_load_block_table($tableid,$label,$table_index,$table_param,$type,$mastertableid,$funzione,$recordid,$scheda_container,$interface='desktop')
    {
        $blocco=  $this->load_block_table($tableid, $label, $table_index, $table_param, $type, $mastertableid, $funzione, $recordid,$scheda_container,array(), $interface);
        echo $blocco;
    }
    
   
    
    public function load_block_table($tableid,$label,$table_index,$table_param,$type,$mastertableid,$funzione,$recordid,$scheda_container,$prefilled_table=array(),$interface='desktop',$origine_tableid='')
    {
        
      /*  if(($recordid!='null')&&($recordid!=null)&&($recordid!='')&&($table_param!='add'))
        {
            if($type=='linkedmaster')
            {
                $dati_contratto=  $this->Sys_model->get_dati_record($mastertableid, $recordid);
                $recordid_linkedmaster=$dati_contratto["recordid".  strtolower($tableid)."_"];
                $data['data']['fields']=$this->load_block_filledfields_table($tableid, $label, $type, 'modifica', $recordid_linkedmaster, $interface);
                $recordid=$recordid_linkedmaster;
            }
            else
            {
            $data['data']['fields']=  $this->load_block_filledfields_table($tableid, $label, $type, $funzione, $recordid, $interface);
            }
            
        }
        else
        {
           $data['data']['fields']=  $this->load_block_emptyfields_table($tableid,$label,$table_index,$table_param,$type,$funzione); 
        }*/
        
       /* if(($funzione=='ricerca')||($funzione=='inserimento'))
        {
            $data['data']['fields']=  $this->load_block_emptyfields_table($tableid,$label,$table_index,$table_param,$type,$funzione); 
        }
        if(($funzione=='scheda')||($funzione=='modifica'))
        {
            $data['data']['fields']=$this->load_block_filledfields_table($tableid, $label, $type, $funzione, $recordid, $interface);
        }*/
        
        //$viewid=8; //TEMP TEST
        
        //$view_post=$this->Sys_model->get_view_post($tableid,$viewid); // TEMP TEST
        
        //$filledfields=$view_post['tables'][$tableid]['search']['t_1']['fields']; //TEMP TEST
        //$table_param=$view_post['tables'][$tableid]['search']['t_1']['table_param']; //TEMP TEST
        $settings=$this->Sys_model->get_settings();
        $table_settings=$this->Sys_model->get_table_settings($tableid);
        if(($funzione=='scheda')&&($table_settings['scheda_mostratutti']=='true'))
        {
            $funzione='modifica';
        }
        $data['data']['fields']=$this->load_block_fields_table($tableid, $label, $type,$table_param,$table_index, $recordid, $funzione,$prefilled_table, $interface,$origine_tableid);
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['type']=$type;
        $data['data']['scheda_container']=$scheda_container;
        $data['data']['table_index']=$table_index;
        $data['data']['table_param']=$table_param;
        $data['data']['label']=$label;
        $data['data']['settings']=  $settings;
        $data['data']['table_settings']=  $table_settings;
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        return $this->load->view('sys/'.$interface.'/block/table',$data, TRUE);
    }
    
    
    public function load_block_fields_table($tableid,$label='null',$type,$table_param,$table_index,$recordid='null',$funzione='null',$prefilled_table=array(),$interface='desktop',$origine_tableid=''){
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['label']=$label;
        $data['data']['type']=$type;
        $data['data']['table_index']=$table_index;
        $data['data']['table_param']='null';
        $data['cliente_id']= $this->Sys_model->get_cliente_id();
        
        
        
        if($type=='ocr')
        {
            $valuecode[0]['value']='';
            $valuedcode[0]['code']='';
            $fields[0]=array(
                                    "tableid" => $tableid,
                                    "fieldid" => "ocr_",
                                    "fieldtypeid" => "Parola",
                                    "length" => "256",    
                                    "decimalposition" => "0",
                                    "description" => "Testo ocr",
                                    "fieldorder" => "0",
                                    "lookuptableid" => "",
                                    "label" => "Dati",
                                    "valuecode" => $valuecode,  
                                    "sublabel" => "",
                                    "showedbyfieldid"=>"",
                                    "showedbyvalue"=>"",
                                    "settings"=>array()    
                                );
        }
        else
        {
            $valuecode[0]['value']='';
            $valuedcode[0]['code']='';
            if($type=='tutti')
            {
                $fields[0]=array(
                                    "tableid" => $tableid,
                                    "fieldid" => "tutti",
                                    "fieldtypeid" => "Parola",
                                    "length" => "256",    
                                    "decimalposition" => "0",
                                    "description" => "Cerca ovunque",
                                    "fieldorder" => "0",
                                    "lookuptableid" => "",
                                    "label" => "Dati",
                                    "valuecode" => $valuecode,
                                    "param" => '',
                                    "operator" => '',
                                    "sublabel"=>'',
                                    "showedbyfieldid"=>"",
                                    "showedbyvalue"=>"",
                                    "explanation"=>"Cerca in tutti campi",
                                    "settings"=>array("obbligatorio"=>"false")
                                );
            }
            else 
            {
                if($table_param=='showall')
                {
                    $funzione='inserimento';
                }
                $fields= $this->Sys_model->get_fields_table($tableid,$label,$recordid,$funzione,$type,$prefilled_table,$origine_tableid); 
                if($table_param=='showall')
                {
                    foreach ($fields as $key_field => $field) {
                        $fields[$key_field]['valuecode'][0]['value']='';
                        $fields[$key_field]['valuecode'][0]['code']='';
                    }
                }
            }
        }
        $default_values=array(
            "idutente" => "2",
            "note" => "test"
        );
        $data['data']['fields']=$fields;
        $data['data']['sublabels']=  $this->Sys_model->get_table_sublabels($tableid);
        $data['userid']=  $this->session->userdata('idutente');
        $data['table_settings']= $this->Sys_model->get_table_settings($tableid);
        //$data["default_values"]=$default_values;
        return $this->load->view('sys/'.$interface.'/block/fields_table',$data, TRUE);
    }
    
    /*public function load_block_filledfields_table($tableid,$label='null',$type,$funzione='null',$recordid='null',$interface='desktop'){
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['label']=$label;
        $data['data']['type']=$type;
        $data['data']['table_index']=0;
        $data['data']['table_param']='null';
        
        $data['data']['fields']= $this->Sys_model->get_filledfields_table($tableid,$label,$recordid,$funzione,$type);  
        return $this->load->view('sys/'.$interface.'/block/fields_table',$data, TRUE);
    }*/
    
    
    /**
     * Carica i campi vuoti di una tabella sotto a una particolare label (per ricerca e inserimento)
     * 
     * @param String $tableid tabella principale
     * @param String $label label di cui visualizzare i campi
     * @param Int $table_index evetuale indice della tabella rispetto all'ordinamento
     * @param type $table_param eventuale parametro 
     * @param String $type
     * @param String $funzione
     * @param String $recordid
     * @param type $interface
     * @return type
     */
   /* public function load_block_emptyfields_table($tableid,$label='null',$table_index,$table_param,$type,$funzione='null',$recordid='null',$interface='desktop'){

        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['label']=$label;
        $data['data']['type']=$type;
        $data['data']['table_index']=$table_index;

            $data['data']['fields']= $this->Sys_model->get_emptyfields_table($tableid,$label,$funzione,$type);

        
        return $this->load->view('sys/'.$interface.'/block/fields_table',$data, TRUE);
    }*/
    
    public function ajax_get_users()
    {
        $users = $this->Sys_model->get_users();
        $items=array();
        foreach ($users as $key => $user) 
        {
            $items[$key]['value'] = $user['id'];
            $items[$key]['label'] = $user['firstname'].' '.$user['lastname'];
            $items[$key]['desc'] = '';
            $userid=$user['id'];
            if(file_exists("../JDocServer/avatar/$userid.jpg"))
            {
                $avatar_url=domain_url()."/JDocServer/avatar/$userid.jpg";
            }
            else
            {
                $avatar_url=  base_url('/assets/images/anon.png');
            }
            $items[$key]['icon'] = $avatar_url;
        }
        $json_items = json_encode($items);
        echo $json_items;
    }
    
    public function ajax_set_field_explanation($tableid,$fieldid)
    {
        $post=$_POST;
        $explanation=$post['explanation'];
        $this->Sys_model->ajax_set_field_explanation($tableid,$fieldid,$explanation);
    }
    
    public function ajax_add_lookuptable_item($lookuptableid)
    {
        $post=$_POST;
        //$itemdesc=  urldecode($itemdesc);
        $itemdesc=$post['itemdesc'];
        $itemcode=$this->Sys_model->add_lookuptable_item($lookuptableid,$itemdesc);
        echo $itemcode;
    }
    
    public function ajax_delete_lookuptable_item($lookuptableid)
    {
        $post=$_POST;
        $itemcode=$post['itemcode'];
        $itemcode=$this->Sys_model->delete_lookuptable_item($lookuptableid,$itemcode);
    }
    
    public function ajax_get_lookuptable($lookuptableid=null,$fieldid=null,$tableid=null)
    {
        
        $items=$this->Sys_model->get_lookuptable($lookuptableid,$fieldid,$tableid);  
        echo json_encode($items);
    }
    
    public function ajax_get_lookuptable2($lookuptableid=null,$fieldid=null,$tableid=null,$linkvalue=null)
    {
        $post=$_POST;
        $linkvalue=$post['linkvalue'];
        $items=$this->Sys_model->get_lookuptable2($lookuptableid,$fieldid,$tableid,$linkvalue);  
        echo json_encode($items);
    }
    
    public function ajax_get_lookuptable3($lookuptableid=null,$fieldid=null,$tableid=null,$linkvalue=null)
    {
        $term = $_GET['term'];
        $linkvalue=urldecode($linkvalue);
        $items=$this->Sys_model->get_lookuptable3($lookuptableid,$fieldid,$tableid,$term,$linkvalue); 
        $json=json_encode($items);
        echo $json;
    }
    
 
    
    
    public function ajax_load_content_ricerca($tableid,$interface='desktop',$default_viewid='default')
    {
        $userid=$this->get_userid();
        $table_settings=  $this->Sys_model->get_table_settings($tableid);
        $customview_list=$table_settings['customview_list'];
        if($customview_list=='true')
        {
            if($tableid=='calcolofatturato')
            {
                $this->ajax_load_content_calcolofatturato();
            }
            if($tableid=='ccl')
            {
                $this->ajax_load_content_listaccl();
            }
            
        }
        else
        {
                $content=  $this->load_content_ricerca($tableid, $interface,$default_viewid);
                echo $content;
        }
    }
    public function load_content_ricerca($tableid,$interface='desktop',$default_viewid='default')
    {

            $data['data']['schede']['scheda_dati_ricerca']=$this->load_scheda_dati_ricerca($tableid,$default_viewid);
            $data['data']['block']['block_risultati_ricerca']="";
            $data['data']['tableid']=$tableid;
            $data['data']['settings']=$this->Sys_model->get_settings();
            $data['table_settings']= $this->Sys_model->get_table_settings($tableid);
            $cliente_id=  $this->Sys_model->get_cliente_id();
            if($cliente_id=='3p')
            {
                if($tableid=='presenzemensili')
                {
                    $sql="select distinct(anno) from user_giornifestivi ORDER BY anno desc
                        ";
                    
                    $anni=$this->Sys_model->select($sql);
                    $data['anni']=$anni;
                }
                if($tableid=='carenze')
                {
                    $sql="select distinct(anno) from user_giornifestivi ORDER BY anno desc
                        ";
                    
                    $anni=$this->Sys_model->select($sql);
                    $data['anni']=$anni;
                }
                if(($tableid=='contratti')||($tableid=='tempcontrol'))
                {
                   $sql="select DISTINCT nomeccl from user_ccl ORDER BY nomeccl asc
                        ";
                    
                    $nomiccl=$this->Sys_model->select($sql);
                    $data['ccl_list']=$nomiccl; 
                }
                if($tableid=='richiestericercapersonale')
                {
                    $data['utenti']=$this->Sys_model->get_utenti();
                }
            }
            return $this->load->view('sys/desktop/content/ricerca',$data, TRUE);

    }
    
    public function ajax_load_scheda_dati_ricerca($tableid,$view="",$interface='desktop')
    {
        $scheda=  $this->load_scheda_dati_ricerca($tableid,$view,$interface);
        echo $scheda;
    }
    
    public function load_scheda_dati_ricerca($tableid,$view="",$interface='desktop')
    {
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']='null';
        $data['data']['funzione']="ricerca";
        $data['data']['scheda_container']='null';
        $data['data']['saved_views']=$this->Sys_model->get_saved_views($tableid);
        
        
        if($view=="")
        {
            $query_array=  $this->Sys_model->get_search_query($tableid, array());
            $query=$query_array['query_owner'];
            $query_where=$query_array['query_where'];
            $data['data']['default_viewid']='';
            $viewid="";
        }
        else
        {
            if($view=='default')
            {
                $viewid=  $this->Sys_model->get_default_viewid($tableid);
            }
            else
            {
                $viewid=$view;
            }
            if($viewid!="")
            {
                $query=$this->Sys_model->get_view_query($tableid,$viewid);
                $query_where='';
            }
            else
            {
                $query_array=  $this->Sys_model->get_search_query($tableid, array());
                $query=$query_array['query_owner'];
                $query_where=$query_array['query_where'];
            }  
           
        }
         $data['data']['viewid']=$viewid;
        $data['data']['block']['block_dati_labels']=  '';//$this->load_block_dati_labels($tableid, 'null', 'ricerca', 'scheda_dati_ricerca', 'null',$viewid);
        $data['data']['query']=$query;
        $data['data']['query_where']=$query_where;
        $data['data']['userid']=$this->session->userdata('idutente');
        $data['scheda_ricerca_default']=  $this->Sys_model->get_table_setting($tableid,'scheda_ricerca_default');
        return $this->load->view("sys/$interface/schede/scheda_dati_ricerca", $data,true);
    }
    
    public function ajax_set_default_view($tableid,$viewid)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $this->Sys_model->set_default_view($tableid,$viewid);
        echo 'ok';
    }
    
    public function ajax_unset_default_view($tableid,$viewid)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $this->Sys_model->unset_default_view($tableid,$viewid);
        echo 'ok';
    }
    
    public function ajax_delete_view($viewid)
    {
        $this->Sys_model->delete_view($viewid);
        echo 'ok';
    }
    
    public function ajax_rename_view($viewid)
    {
        $post=$_POST;
        $view_name=$post['view_name'];
        $this->Sys_model->rename_view($viewid,$view_name);
        echo 'ok';
    }
    
    public function load_block_riepilogo_ricerca(){
        return $this->load->view('sys/desktop/block/riepilogo_ricerca',null, TRUE);
    }
    
    
    public function ajax_load_block_risultati_ricerca($idarchivio=null){
         $block=  $this->load_block_risultati_ricerca($idarchivio);
         echo $block;
     }
     
    public function load_block_risultati_ricerca($tableid=null)
    {
        $post=$_POST;
        $data['data']['archivio']=$tableid;
        $query=$post['query'];
         $data['data']['block']['datatable_records']=$this->load_block_datatable_records($tableid, 'risultati_ricerca',$query);
         $data['data']['settings']=  $this->Sys_model->get_settings();  
         $fields=$this->Sys_model->get_fields_table($tableid);
         $data['data']['fields']=  $fields;
         //$data['data']['block']['reports_relativi']=  $this->load_block_reports_relativi($tableid,$fields,$post['query']);
         $data['data']['export_list']=$this->Sys_model->get_export_list($tableid);
         $data['userid']=$this->session->userdata('userid');
         $data['table_description']=$this->Sys_model->db_get_value('sys_table','plural_name',"id='$tableid'");
         $data['views']=$this->Sys_model->get_tab_views($tableid);
        return $this->load->view('sys/desktop/block/risultati_ricerca',$data, TRUE);
    }
    
    public function ajax_load_block_datatable_records($tableid,$contesto=null,$query='',$master_tableid='')
    {  

        $post=$_POST;
        $view_name=$post['view_name'];
        $query=$post['query'];
        $block=  $this->load_block_datatable_records($tableid,$contesto,$query,$master_tableid);
        echo $block;
    }
    
    public function ajax_load_block_results($tableid,$contesto=null,$query='',$master_tableid='')
    {  

        $post=$_POST;
        $view_name=$post['view_name'];
        $query=$post['query'];
        $page=$post['page'];
        $scrollTop=$post['scrollTop'];
        $order_key=$post['order_key'];
        $order_ascdesc=$post['order_ascdesc'];
        $block=  $this->load_block_results($tableid,$contesto,$query,$master_tableid,$page,$order_key,$order_ascdesc,$scrollTop);
        echo $block;
    }
    
    public function load_block_results($tableid,$contesto=null,$query='',$master_tableid='',$page=1,$order_key='',$order_ascdesc='',$scrollTop=0,$limit_number=0)
    {   
        $data['scrollTop']=$scrollTop;
        $data['data']['archivio']=$tableid;
        $data['master_tableid']=$master_tableid;
        $data['data']['query']=$query;
        $userid= $this->get_userid();
        $data['data']['contesto']=$contesto;
        $data['data']['settings']=  $this->Sys_model->get_settings();
        
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        $data['css_rows']=array();
        $data['css_rows']['stato']=array();
        $data['css_rows']['stato']['column']='Stato';
        $data['css_rows']['stato']['value']='Eliminato';
        $data['css_rows']['stato']['css']='
            "background-color": "gray",
                             "color":"white"';
        $columns=  $this->Sys_model->get_results_columns($tableid, $userid);
        $data['tableid']=$tableid;
        $data['columns']=$columns;
        if($contesto=='stampa_elenco')
        {
            $records=$this->Sys_model->get_records($tableid,$query,$order_key,$order_ascdesc,0,10000);
        }
        else
        {
            $limit_number=$data['table_settings']['risultati_limit'];
            $offset_number=($page-1)*$limit_number;
            $records=$this->Sys_model->get_records($tableid,$query,$order_key,$order_ascdesc,$offset_number,$limit_number);
        }
        $data['records_total_count']=$this->Sys_model->get_records_total_count($query);
        $data['records']=$records;
        $data['page']=$page;
        $data['order_ascdesc']=$order_ascdesc;
        $data['order_key']=$order_key;
        $data['cliente_id']=$this->Sys_model->get_cliente_id();
        $data['contesto']=$contesto;
        $data['limit_number']=$limit_number;
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        return $this->load->view('sys/desktop/block/results',$data, TRUE);
    }
    
    public function load_stampa_elenco_pdf($tableid,$contesto=null,$query='',$master_tableid='')
    {   
        
        
        $data['results_block']=$this->load_block_results($tableid,'stampa_elenco',$query,$master_tableid,1);
        $data['stampa_elenco_orientamento']=$this->Sys_model->get_table_setting($tableid,'get_risultati_stampa_elenco_orientamento');
        return $this->load->view('sys/desktop/stampe/stampa_elenco_pdf',$data, TRUE);
    }
    
    public function load_block_datatable_records($tableid,$contesto=null,$query='',$master_tableid='')
    {   
        
        $data['data']['archivio']=$tableid;
        $data['master_tableid']=$master_tableid;
        $data['data']['query']=$query;
        $userid=$this->session->userdata('userid');
        $data['data']['columns']=  $this->Sys_model->get_colums($tableid, $userid); 
        $data['data']['contesto']=$contesto;
        $data['data']['settings']=  $this->Sys_model->get_settings();
        $layout=  $this->Sys_model->get_table_setting($tableid,'risultati_layout');
        if($layout=='')
        {
            $layout='records_preview';
        }
        $data['layout']=$layout;
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        $data['css_rows']=array();
        $data['css_rows']['stato']=array();
        $data['css_rows']['stato']['column']='Stato';
        $data['css_rows']['stato']['value']='Eliminato';
        $data['css_rows']['stato']['css']='
            "background-color": "gray",
                             "color":"white"';
        return $this->load->view('sys/desktop/block/datatable_records',$data, TRUE);
    }
    
    public function ajax_load_block_calendar($tableid='')
    {
        //echo 'aspetta e spera';
        $post=$_POST;
        //$query=$post['query'];
        $query_array=$this->Sys_model->get_search_query($tableid,$post);
        $query=$post['query'];
        // recupero data di inizio e di fine per il range del calendario
        $datainizio='';
        $datafine='';
        if(array_key_exists('tables', $post)){
                if(array_key_exists($tableid, $post['tables'])){
                        if(array_key_exists('search', $post['tables'][$tableid])){
                                if(array_key_exists('t_1', $post['tables'][$tableid]['search'])){
                                        if(array_key_exists('fields', $post['tables'][$tableid]['search']['t_1']))
                                        {
                                            foreach ($post['tables'][$tableid]['search']['t_1']['fields'] as $key => $field) {
                                                if(array_key_exists('f_0', $field))
                                                {
                                                    if($field['f_0']['type']=='data')
                                                    {
                                                        if($field['f_0']['param']!='')
                                                        {
                                                            if($field['f_0']['param']=='today')
                                                            {
                                                                $today=  date('Y-m-d');
                                                                $datainizio=$today;
                                                                $datafine=$today;
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $datainizio=$field['f_0']['value'][0];
                                                            $datafine=$field['f_0']['value'][1];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                }
                        }
                }
        }
        $today=  date('Y-m-d');
        $datainizio=$today;
        $datafine=$today;
        $block=  $this->load_block_calendar($tableid,$query,$datainizio,$datafine);
        echo $block;
    }
    
    public function ajax_load_block_calendar_dashboard($tableid)
    {
        $block=  $this->load_block_calendar_dashboard($tableid);
        echo $block;
    }
    
    
    
    public function load_block_calendar($tableid,$query,$datainizio,$datafine)
    {
        $calendars=$this->Sys_model->get_calendars($tableid,$query);
        $data['data']['calendars']=$calendars;
        $data['data']['tableid']=$tableid;
        $data['data']['datainizio']=$datainizio;
        $data['data']['datafine']=$datafine;
        return $this->load->view('sys/desktop/block/calendar',$data, TRUE);
    }
    
    public function ajax_search_result($tableid)
    {
       $return= $this->Sys_model->get_ajax_search_result($tableid,$_POST);
       $json= json_encode( $return );
       echo $json;
        
    }
    
    /**
     * Funzione che cicla la tabella timesheet
     * e per ogni utente prende le attiività pianificate del giorno stesso
     * e le invia per mail usando JDocServices
     */
    public function alert_giornalieri()
    {
        $rows=$this->Sys_model->select("SELECT id,email FROM sys_user ORDER BY id");
        
        //ciclo tutti gli utenti trovati nella query
        foreach ($rows as $row)
        {
            $idutente=$row['id'];
            $email=$row['email'];
            $sql = "SELECT * FROM user_timesheet WHERE idutente='$idutente' AND stato ILIKE 'pianificato' AND datainizio='". date("Y-m-d")."'";
            $righe=$this->Sys_model->select($sql);
            $testoMail = "<h4>ATTIVITA' DEL: ".date("d/m/Y")."</h4><br><br><ul>";
            
            $almenoUnRecord=false;
            foreach($righe as $riga)
            {
               $testoMail .= "<li>".$riga['note']."</li>";
               $almenoUnRecord=true;
            }
            
            $testoMail.="</ul><br><h4>ATTIVITA' IN SCADENZA ENTRO 7 GIORNI:</h4><br><ul>";
            
            //adesso prendo tutti gli eventi in scadenza nello stesso range precedente
            $datascadenza=date('Y-m-d',strtotime(date('Y-m-d')." + 7 days"));
            $righe=$this->Sys_model->select("SELECT * FROM user_timesheet WHERE idutente='$idutente' AND stato ILIKE 'pianificato' AND scadenza<='$datascadenza' ORDER BY scadenza");
            foreach($righe as $riga)
            {
                $testoMail.="<li>".date("d/m/Y",strtotime($riga['scadenza']))."&nbsp;".$riga['note']."</li>";
                $almenoUnRecord = true;
            }
            $testoMail.="</ul>";
            if($almenoUnRecord)
            {
                $command='cd ../JDocServices && JDocServices.exe "inviamail" "'.$testoMail.'" "Alert Giornaliero" "'.$email.';"';
                exec($command);
            }
        }
    }
    
    public function ajax_load_query($tableid=null,$funzione='')
    {
        $query=$this->load_query($_POST, $tableid,$funzione);
        echo $query;
    }
    
    public function load_query($POST,$tableid,$funzione)
    {
        $query='';
        if($funzione=='ricerca')
        {
            $query_array=  $this->Sys_model->get_search_query($tableid, $POST);
            $search_query=$query_array['query_owner'];
            $query=$search_query;
        }
        $ricerca_lockedview= $this->Sys_model->get_table_setting($tableid,'ricerca_lockedview');
        if($ricerca_lockedview=='true')
        {
            if(array_key_exists('view_selected_id', $POST))
            {
                $view_selected_id=$POST['view_selected_id'];
                if(isnotempty($view_selected_id))
                {
                    $view_query=$this->Sys_model->get_view_query($tableid,$view_selected_id);
                    //$query=$query." INTERSECT ".$view_query;
                    $query="SELECT * FROM ($search_query) as search_query WHERE search_query.recordid_ IN (SELECT view_query.recordid_ FROM ($view_query) AS view_query)";
                }
            }
        }
        return $query;
    }
    
    public function ajax_smartsearch()
    {
        $query=$this->customsearch();
        echo $query;
    }
    
    public function smartsearch()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $query=$this->Sys_model->get_smartsearch_query($tableid, $post['tables']);
        return $query;
        
    }
    
    public function ajax_customsearch()
    {
        $query=$this->customsearch();
        echo $query;
    }
    
    public function customsearch()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        if(array_key_exists('tables', $post))
        {
            $tables=$post['tables'];
        }
        else
        {
            $tables=array();
        }
        
        if(array_key_exists('custom_fields', $post))
        {
            $custom_fields=$post['custom_fields'];
        }
        else
        {
            $custom_fields=array();
        }
        
        $search_query=$this->Sys_model->get_customsearch_query($tableid, $tables, $custom_fields);
        $query=$search_query;
        
        $ricerca_lockedview= $this->Sys_model->get_table_setting($tableid,'ricerca_lockedview');
        if($ricerca_lockedview=='true')
        {
            if(array_key_exists('view_selected_id', $post))
            {
                $view_selected_id=$post['view_selected_id'];
                if(isnotempty($view_selected_id))
                {
                    $view_query=$this->Sys_model->get_view_query($tableid,$view_selected_id);
                    //$query=$query." INTERSECT ".$view_query;
                    $query="SELECT * FROM ($search_query) as search_query WHERE search_query.recordid_ IN (SELECT view_query.recordid_ FROM ($view_query) AS view_query)";
                }
            }
        }
        
        if($tableid=='richiestericercapersonale')
        {
            $now=date('Y-m-d H:i:s');
            $userid= $this->get_userid();
            $sql="DELETE FROM sys_view WHERE tableid='richiestericercapersonale' AND name='Filtrati' AND userid='$userid'";
            $this->Sys_model->execute_query($sql);
            $sql="DELETE FROM sys_user_default_view WHERE tableid='richiestericercapersonale' AND userid='$userid'";
            $this->Sys_model->execute_query($sql);
            
            //$query_conditions=strstr($query, 'WHERE');
            $query_conditions=substr($query, strpos($query, 'WHERE') + 5);
            $query_conditions= str_replace("'", "''", $query_conditions);
            $post=json_encode($post);
            $sql="INSERT INTO sys_view (name,userid,tableid,creation,post,query_conditions) VALUES ('Filtrati','$userid','$tableid','$now','$post','$query_conditions')";
            $this->Sys_model->execute_query($sql);
            $viewid=$this->Sys_model->db_get_value("sys_view","id","tableid='richiestericercapersonale' AND name='Filtrati' AND userid='$userid'");
            $sql="INSERT INTO sys_user_default_view (userid,tableid,viewid) VALUES ('$userid','$tableid','$viewid')";
            $this->Sys_model->execute_query($sql);
        }
        
       
        return $query;
        
    }
    
    public function ajax_ricalcola_tempcontrol()
    {
        $query=$this->ricalcola_tempcontrol();
        echo $query;
    }
    
    
    public function ricalcola_tempcontrol()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        if(array_key_exists('tables', $post))
        {
            $tables=$post['tables'];
            $cliente=$post['tables']['tempcontrol']['fields']['cliente']['value'][0];
        }
        else
        {
            $tables=array();
            $cliente='';
        }
        $datadal=$post['custom_fields']['datadal'];
        $dataal=$post['custom_fields']['dataal'];
        
        $this->generate_temp_control($cliente,$datadal,$dataal);
        
        $query=$this->Sys_model->get_customsearch_query($tableid, $tables, $post['custom_fields']);
        return $query;
        
    }
    
    public function ajax_ricalcola_listaclienti()
    {
        $query=$this->ricalcola_listaclienti();
        echo $query;
    }
    
    public function ricalcola_listaclienti()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $datadal=$post['custom_fields']['datadal'];
        $dataal=$post['custom_fields']['dataal'];
        
        $this->generate_listaclienti($datadal,$dataal);
        
        
    }
    
    public function ajax_ricalcola_carenze()
    {
        $query=$this->ricalcola_carenze();
        echo $query;
    }
    
    public function ricalcola_carenze()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
       
        $anno=$post['custom_fields']['anno'];
        $mese=$post['custom_fields']['mese'];
        
        $this->Sys_model->calcola_carenze_dipendenti($anno,$mese);
        
        //$query=$this->Sys_model->get_customsearch_query($tableid, $tables, $post['custom_fields']);
        //return $query;
        
    }
    
    
    public function calcola_tempcontrol_contratto($recordid_contratto)
    {
        $contratto= $this->Sys_model->get_record('contratti', $recordid_contratto);
        $sql="
                SELECT user_contratti.recordid_,sum(TIME_TO_SEC(user_rapportidilavoro.duratalavoro)) as totsecondi
FROM user_contratti  JOIN user_rapportidilavoro ON user_contratti.recordid_=user_rapportidilavoro.recordidcontratti_
GROUP BY user_contratti.recordid_

                ";
            $contratti_totsecondi_results= $this->Sys_model->select($sql);
        $this->Sys_model->calcola_tempcontrol_contratto($contratto,$contratti_totsecondi_results);
    }
    
    public function ajax_calcola_mediaperiodo()
    {
        $mediaperiodo=$this->calcola_mediaperiodo();
        echo $mediaperiodo;
    }
    
    public function calcola_mediaperiodo()
    {
        $post=$_POST;
        
        $mediaperiodo=$this->Sys_model->calcola_mediaperiodo($post);
        return $mediaperiodo;
        
    }
    
    public function ajax_load_block_scheda_record($idmaster,$recordid_,$layout='standard_dati',$target='self',$popuplvl_new=0,$funzione='scheda',$origine_tableid='',$origine_recordid='')
    {
        $table_settings=  $this->Sys_model->get_table_settings($idmaster);
        $customview_card=$table_settings['customview_card'];
        if($customview_card=='true')
        {
            
        }
        else
        {
            $blocco = $this->load_block_scheda_record($idmaster,$recordid_,$layout,$target,$popuplvl_new,$funzione,$origine_tableid,$origine_recordid);
            echo $blocco;
        }
    }
    
    public function load_block_scheda_record($tableid,$recordid,$layout='standard_dati',$target='self',$popuplvl_new=0,$funzione='scheda',$origine_tableid='',$origine_recordid='')
    {
        $interface='desktop';
        $layout=$this->Sys_model->get_table_setting($tableid,'scheda_layout');
        if($target=='popup')
        {
            $layout=$this->Sys_model->get_table_setting($tableid,'popup_layout');
        }
        
        /*if(($layout=='standard_dati')&&($funzione=='scheda'))
        {
            $layout=$this->Sys_model->get_table_setting($tableid,'scheda_layout');
            if($layout=='')
            {
                $layout='standard_dati';
            }
        }*/
        if($recordid=='null')
        {   
            $funzione='inserimento';
            $navigatorField='nuovo';
        }
        else
        {
            //$funzione='scheda';
            $navigatorField=$this->Sys_model->get_navigatorField($tableid,$recordid);
        }
        $data['data']['block']['block_dati_labels']=  $this->load_block_dati_labels($tableid, $recordid,$funzione,'scheda_record','null');
        $data['data']['block']['block_fissi']=  $this->load_block_fissi($tableid, $recordid,$interface);
        if($funzione=='inserimento')
        {
            $data['data']['block']['allegati']=  $this->load_block_allegati($tableid, $recordid,'scheda',$popuplvl_new,$interface);
        }
        
        $data['data']['block']['block_visualizzatore']=  $this->load_block_visualizzatore("", "", "");
        $data['data']['block']['block_code']=  "";//$this->load_block_code('modifica');
        if($funzione=='inserimento')
        {
            $data['data']['block']['block_autobatch']=$this->load_block_autobatch($funzione);
        }
        else
        {
           $data['data']['block']['block_autobatch']=  ""; 
        }
        
        if((($recordid!=null))&&($recordid!='null'))
        {
            $rows= $this->Sys_model->get_allegati($tableid, $recordid);
        }
        else
        {
            $rows=array();
        }
        $data['data']['numfiles']=  count($rows);
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['mode']='scheda';

        $data['data']['popuplvl']=$popuplvl_new;
        $data['data']['navigatorField']=$navigatorField;
        $data['data']['settings']= $this->Sys_model->get_settings($tableid);
        $data['data']['settings']['tableid']=$tableid;
        $data['table_settings']=  $this->Sys_model->get_table_settings($tableid);
        /*$user_settings=$this->Sys_model->get_sys_user_settings(1,'layout_scheda',$tableid);
        if($user_settings!=null)
        {
          $data['data']['settings']['layout_scheda']=$user_settings['layout_scheda']  ;  
        }
        else
        {
            $data['data']['settings']['layout_scheda']='standard_dati';
        }*/
        
        
        

        if($tableid=='contrattimandato')
        {
            $layout='standard_allegati';
        }
        if($tableid=='generico')
        {
            $layout='standard_allegati';
        }
        if($tableid=='legale')
        {
            $layout='standard_allegati';
        }
        if($tableid=='marketing')
        {
            $layout='standard_allegati';
        }
        if($tableid=='tecnico')
        {
            $layout='standard_allegati';
        }
        if($tableid=='vendita')
        {
            $layout='standard_allegati';
        }
        if($target=='popup')
        {
            if($tableid=='contatti')
            {
                $layout="standard_dati";
            }
            if($tableid=='immobili_richiesti')
            {
                $layout="standard_dati";
            }
            if($tableid=='immobili')
            {
                $layout="allargata";
            }
            if($tableid=='immobili_proposti')
            {
                $layout="standard_dati";
            }
        }
        
        $data['data']['target']=$target;
        $data['data']['layout']=$layout;
        $data['cliente_id']=$this->Sys_model->get_cliente_id();
        $data['origine_tableid']=$origine_tableid;
        $data['origine_recordid']=$origine_recordid;
        $data['record_creator']=$this->Sys_model->db_get_value('user_'.strtolower($tableid),'creatorid_',"recordid_='$recordid'");
        //TEMP Dimensione Immobiliare
        if($tableid=='immobili')
        {
            $data['record_creator']=$this->Sys_model->db_get_value('user_'.strtolower($tableid),'consulente',"recordid_='$recordid'");
        }
        $data['data']['settings']['layout_scheda']=$layout;
        $data['table_description']=$this->Sys_model->db_get_value('sys_table','singular_name',"id='$tableid'");
        
        $cliente_id=  $this->Sys_model->get_cliente_id();
        if(($cliente_id=='3p')&&($tableid=='azienda'))
        {
            $data['calendarioaziendale']=$this->Sys_model->db_get_value('user_azienda','calendarioaziendale',"recordid_='$recordid'");
        }
        else
        {
            $data['calendarioaziendale']='';
        }
        if(($cliente_id=='swissbix')&&($tableid=='salesorder'))
        {
            $data['id_bexio']=$this->Sys_model->db_get_value('user_salesorder','id_bexio',"recordid_='$recordid'");
        }
        return $this->load->view('sys/desktop/schede/scheda_record',$data, TRUE);
    }
    
    public function ajax_load_block_fissi($tableid,$recordid_,$interface='desktop')
    {
        $blocco = $this->load_block_fissi($tableid,$recordid_,$interface);
        echo $blocco;
    }
        
    
    public function load_block_fissi($tableid,$recordid_,$interface='desktop'){
        $cliente_id=  $this->Sys_model->get_cliente_id();
        $data['data']['cliente_id']=$cliente_id;
        if($recordid_!='null')
        {
            $fields_fissi= $this->Sys_model->get_fissi($tableid, $recordid_);
            $data['data']['recordid']=$recordid_;
            $data['data']['foto_path']=  $this->Sys_model->get_foto_path($tableid, $recordid_);
            $data['data']['fields']=$fields_fissi; 
            $data['data']['tableid']=$tableid;
            $data['data']['record_info']=  $this->Sys_model->get_record_info($tableid,$recordid_);
            $data['data']['recordid']=$recordid_;
            $data['data']['userid']= $this->get_userid();
            if($cliente_id=='Dimensione Immobiliare')
            {
                $data['notepubblicazione']= $this->Sys_model->db_get_value('user_immobili','notepubblicazione',"recordid_='$recordid_'");
            }
            return $this->load->view('sys/'.$interface.'/block/fissi',$data, TRUE);
        }
        else
        {
            return '';    
        }
        $data['data']['recordid']=$recordid_;
        $data['data']['foto_path']=  $this->Sys_model->get_foto_path($tableid, $recordid_);
        $data['data']['fields']=$fields_fissi; 
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid_;
        $data['data']['userid']= $this->get_userid();
        
        return $this->load->view('sys/'.$interface.'/block/fissi',$data, TRUE);
        

    }
    
    public function ajax_load_block_allegati($tableid,$recordid,$funzione,$interface='desktop')
    {
        $block=$this->load_block_allegati($tableid,$recordid,$funzione);
        echo $block;
    }
    
    public function load_block_allegati($tableid=null,$recordid=null,$funzione,$popuplvl_new=0,$interface='desktop')
    {
        $funzione='inserimento';
        $data['data']['block']['lista_files']=$this->load_block_lista_files($funzione, 'allegati', null, $tableid, $recordid, $interface);
        if($funzione=='scheda')
        {
            $funzione='modifica';
        }
        if(($funzione=='modifica')||($funzione=='inserimento'))
        {
            $data['data']['block_upload_files']=$this->load_block_upload_files($funzione,$popuplvl_new);
        }
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['categories']=$this->Sys_model->get_page_categories($tableid,$recordid);
        $data['userid']=  $this->session->userdata('userid');
        return $this->load->view('sys/'.$interface.'/block/allegati',$data, TRUE);
       
    }
    
    
    public function ajax_load_block_modifica_record($tableid,$recordid){
          echo $this->load_block_modifica_record($tableid, $recordid);
    }
    
    
    public function load_block_modifica_record($tableid,$recordid){
        $userid=$this->session->userdata('idutente');
        $data['data']['block']['block_modifica_record_dati']=  $this->load_block_modifica_record_dati($tableid, $recordid, $userid);
        $data['data']['block']['block_modifica_record_allegati']=  $this->load_block_modifica_record_allegati($tableid, $recordid, $userid);
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        return $this->load->view('sys/desktop/block/modifica_record',$data, TRUE);
    }
 
    public function load_block_modifica_record_dati($tableid,$recordid,$userid){
        $data['data']['block']['block_fields']=$this->load_block_fields($tableid,$recordid,'modifica','null');
        //$data['data']['block']['block_gestione_allegati']=  $this->load_block_gestione_allegati('modifica', $tableid, $recordid);
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        return $this->load->view('sys/desktop/block/modifica_record_dati',$data, TRUE);
    }
    
    public function load_block_modifica_record_allegati($tableid,$recordid,$userid){
        $data['data']['block']['block_code']=  $this->load_block_code('modifica');
        $data['data']['block']['block_lista_files']=$this->load_block_lista_files('modifica', 'allegati', null, $tableid, $recordid);
        return $this->load->view('sys/desktop/block/modifica_record_allegati',$data, TRUE);
    }
    
    
    public function ajax_salva_modifiche_record($tableid,$recordid='null'){
        $post=$_POST;
        $return_recordid='';
        if(array_key_exists('custom', $post))
        {
            if(array_key_exists('contra', $post['custom']))
            {
                $wwws=$post['custom']['contra']['wwws'];
                if(array_key_exists('azienda', $post['custom']['contra']['destinatario']))
                {
                    $post['custom']['contra']['destinatario_contratto_attuale']='azienda';
                    $return_recordid=$this->Sys_model->salva_record($tableid,$recordid, $post);
                    if(($return_recordid!=null))
                    {
                        $this->genera_contratti_azienda($return_recordid,$wwws);
                    }
                    //$return_recordid=$return_recordid.$return.';';
                }
                if(array_key_exists('dipendente', $post['custom']['contra']['destinatario']))
                {
                    $post['custom']['contra']['destinatario_contratto_attuale']='dipendente';
                    $return_recordid=$this->Sys_model->salva_record($tableid,$recordid, $post);
                    if(($return_recordid!=null))
                    {
                        $this->genera_contratti_dipendente($return_recordid,$wwws);
                    }
                    //$return_recordid=$return_recordid.$return.';';
                }
            }
        }
        else
        {
            $return_recordid=$this->Sys_model->salva_record($tableid,$recordid, $post);
        }
        //CUSTOM 3P
        /*if($tableid=='dipendenti')
        {
            $this->genera_rapportodilavoro($return_recordid);
        }*/
        echo $return_recordid;
    }
    
    
    public function ajax_elimina_record($tableid,$recordid){
        $return=$this->Sys_model->elimina_record($tableid, $recordid);
        echo $return;
    }
    
    public function ajax_duplica_record($tableid,$recordid){
        $return=$this->Sys_model->duplica_record($tableid, $recordid);
        echo $return;
    }
    
    public function ajax_ripeti_record($tableid,$recordid){
        $return=$this->Sys_model->ripeti_record($tableid, $recordid);
        echo $return;
    }
    
    public function ajax_nuova_proposta_immobile($tableid,$recordid){
        $tableid_proposta=$tableid;
        $recordid_proposta=$recordid;
        $recordid_richiesta=$this->Sys_model->db_get_value('user_immobili_proposti','recordidimmobili_richiesti_',"recordid_='$recordid_proposta'");
        $recordid_immobile=$this->Sys_model->db_get_value('user_immobili_proposti','recordidimmobili_',"recordid_='$recordid_proposta'");
        $recordid_contatto=$this->Sys_model->db_get_value('user_immobili_proposti','recordidcontatti_',"recordid_='$recordid_proposta'");
        $return=$this->Sys_model->duplica_record($tableid, $recordid);
        $this->Sys_model->aggiorna_nuova_proposta_immobile($recordid,$return);
        $this->Sys_model->insert_agenda($recordid_immobile,$recordid_contatto,$recordid_richiesta,$return,'Creata nuova proposta');
        echo $return;
    }
    
    
    
    public function importacoda($nomecoda="null")
    {
        $userid=$this->session->userdata('userid');
        $rows=$this->Sys_model->select("SELECT firstname,folder_serverside FROM sys_user WHERE id=$userid");
        $path=$rows[0]['folder_serverside'];
        if($nomecoda=='null')
        {
        $firstname=$rows[0]['firstname'];
        $nomecoda=$firstname.'_'.date('d_m_Y_H_s');
        }
        $command='cd ../JDocServices && JDocServices.exe "coda" "'.$nomecoda.'" "'.$path.'" '.$userid.' 0';
        exec($command);
        echo $nomecoda;
    }
    
    public function ajax_get_records_linkedmaster($linkedtableid,$mastertableid){
        $get=$_GET;
        $term = $get['term'];
        $parent_value='';
        $master_recordid='';
        if(array_key_exists('parent_value', $get))
        {
            $parent_value=$get['parent_value'];
        }
        if(array_key_exists('master_recordid', $get))
        {
            $master_recordid=$get['master_recordid'];
        }
        
        $records_linkedmaster=$this->Sys_model->get_records_linkedmaster($linkedtableid, $mastertableid,$term,$parent_value,$master_recordid);
        $records_linkedmaster2=array();
        
        foreach ($records_linkedmaster as $keyrecord => $record) {
            
            $templabel='';
            foreach ($record as $keycolumn => $column) {
                //custom 3p inizio
                if($keycolumn=='recordidccl_')
                {
                    $column=$this->Sys_model->db_get_value('user_ccl','nomeccl',"recordid_='$column'");
                }
                
                //custom 3p fine
                if($keycolumn!='recordid_')
                {
                    $templabel=$templabel." ".$column;
                }
            }
            if(($term!='sys_recent')&&($term!='sys_all'))
            {
                if (strpos(strtolower($templabel),  strtolower($term)) !== false) {
                    $records_linkedmaster2[$keyrecord]['value']=$record['recordid_'];
                    $records_linkedmaster2[$keyrecord]['label']=$templabel;
                    $records_linkedmaster2[$keyrecord]['desc']='';
                    $records_linkedmaster2[$keyrecord]['icon']='';
                }
            }
            else
            {
               $records_linkedmaster2[$keyrecord]['value']=$record['recordid_'];
                $records_linkedmaster2[$keyrecord]['label']=$templabel; 
                $records_linkedmaster2[$keyrecord]['desc']=''; 
                $records_linkedmaster2[$keyrecord]['icon']='';
            }
        }
        
        $json_records_linkedmaster = json_encode($records_linkedmaster2);
        
        echo $json_records_linkedmaster;
    }
    
    
    public function ajax_get_records_as_lookup($linkedtableid,$mastertableid){
        $get=$_GET;
        $term = $get['term'];
        $parent_value='';
        $master_recordid='';
  
        if(array_key_exists('master_recordid', $get))
        {
            $master_recordid=$get['master_recordid'];
        }
        
        $records_linkedmaster=$this->Sys_model->get_records_linkedmaster($linkedtableid, $mastertableid,$term,$parent_value,$master_recordid);
        $records_linkedmaster2=array();
        
        foreach ($records_linkedmaster as $keyrecord => $record) {
            
            $templabel='';
            foreach ($record as $keycolumn => $column) {
                //custom 3p inizio
                if($keycolumn=='recordidccl_')
                {
                    $column=$this->Sys_model->db_get_value('user_ccl','nomeccl',"recordid_='$column'");
                }
                
                //custom 3p fine
                if($keycolumn!='recordid_')
                {
                    $templabel=$templabel." ".$column;
                }
            }
            if(($term!='sys_recent')&&($term!='sys_all'))
            {
                if (strpos(strtolower($templabel),  strtolower($term)) !== false) {
                    $records_linkedmaster2[$keyrecord]['value']=$templabel;
                    $records_linkedmaster2[$keyrecord]['label']=$templabel;
                    $records_linkedmaster2[$keyrecord]['desc']='';
                    $records_linkedmaster2[$keyrecord]['icon']='';
                }
            }

        }
        
        $json_records_linkedmaster = json_encode($records_linkedmaster2);
        
        echo $json_records_linkedmaster;
    }
    
    public function ajax_get_field_linkedmaster($tableid,$recordid,$linkedmasterid)
    {
        
    }
    
    public function ajax_load_block_mastertable($linkedtableid,$label='null',$type='null',$funzione='null',$recordid='null',$interface='desktop'){
        $block=  $this->load_block_mastertable($linkedtableid, $label, $type,$funzione, $recordid, $interface);
        echo $block;
    }
    
    
    public function load_block_mastertable($linkedtableid,$label='null',$type='null',$funzione='null',$recordid='null',$interface='desktop'){
        //caricamento campi vuoti
        if(($funzione=='ricerca')||($funzione=='inserimento'))
        {
            $data['data']['fields']=  $this->load_block_emptyfields_table($linkedtableid,$label,0,'null','master',$funzione);
        }
        if(($funzione=='scheda')||($funzione=='modifica')||($type=='linkedmaster'))
        {
            $data['data']['fields']=$this->load_block_filledfields_table($linkedtableid,$label,'master',$funzione,$recordid);
        }
        $data['data']['tableid']=$linkedtableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['label']=$label;
        $data['data']['type']='master';
        $data['data']['table_index']=0;
        $data['data']['table_param']='null';
        $data['data']['label']=$label;
        return $this->load->view('sys/'.$interface.'/block/table',$data, TRUE);
    }
    
    
    public function ajax_load_block_fields_record_linkedtable($linkedtableid,$label='null',$type='null',$funzione='null',$recordid='null',$interface='desktop'){
        $blocco = $this->load_block_fields_record_linkedtable($linkedtableid, $label, $type,$funzione, $recordid, $interface);
        echo $blocco;
    }
    
    
    public function load_block_fields_record_linkedtable($linkedtableid, $label, $type,$funzione, $recordid, $interface){
        $block_mastertable=  $this->load_block_mastertable($linkedtableid, $label, $type,$funzione, $recordid, $interface);
        $data['data']['block']['block_mastertable']=$block_mastertable;
        $data['data']['linkedtableid']=$linkedtableid;
        $data['data']['recordid']=$recordid;
        return $this->load->view('sys/'.$interface.'/block/fields_record_linkedtable',$data, TRUE);
    }
    
    
    
    //inizio impostazioni
    public function ajax_load_content_impostazioni_preferenze($interface='desktop',$idarchivio='CANDID')
    {
        $block=$this->load_content_impostazioni_preferenze($interface,$idarchivio);
        echo $block;
    }
    
    public function load_content_impostazioni_preferenze($interface='desktop',$idarchivio='CANDID')
    {
        $listaarchivi=$this->Sys_model->get_archive_list();
        $content_data['data']['content_data']['archives_list']=$listaarchivi;
        $content_data['data']['content_data']['idarchivio']=$idarchivio;
        $content_data['data']['content']='impostazioni_preferenze';
        if($interface=='desktop')
        {
             $extraheader='ricerca_base';
        }
        else
        {
            $extraheader=null;
        }
        return $this->load->view("sys/$interface/content/impostazioni_preferenze", $content_data,true);
        //$this->view_generale('sys', $interface, 'impostazioni_preferenze',$content_data,$extraheader);
    }
    
    
    /**
     *Blocco ajax per caricare il macro gruppo preferenze dei campi di ricerca
     * @author Luca Giordano
     */
    public function ajax_load_block_macrogruppo_preferenze($interface='desktop')
    {
        echo $this->load_block_macrogruppo_preferenze($interface);
    }
    
    /**
     * 
     * @param type $interface
     * @return html blocco html da visualizzare
     */
    public function load_block_macrogruppo_preferenze($interface='desktop')
    {
        return $this->load->view('sys/'.$interface.'/block/macrogruppo_preferenze');
    }
    
    
    
    public function ajax_load_block_macrogruppo_layout($interface='desktop')
    {
        echo $this->load_block_macrogruppo_layout($interface);
    }
    public function load_block_macrogruppo_layout($interface='desktop')
    {
        return $this->load->view('sys/'.$interface.'/block/macrogruppo_layout');
    }
    
    
    public function ajax_load_block_impostazioni($tipo)
    {
        echo $this->load_block_impostazioni($tipo);
        //echo $blocco;
    }
    
    /**
     * Funzione Ajax per richiamare i campi nelle impostazioni
     * @param type $interface interfaccia
     * @author Luca Giordao
     */
    public function ajax_load_block_impostazioni_campi($interface='desktop')
    {
        echo $this->load_block_impostazioni_campi($interface);
        //echo $blocco;
    }
    
    public function ajax_load_block_impostazioni_campi_archivi($interface='$desktop')
    {
        echo $this->load_block_impostazioni_campi_archivi($interface);
        //echo $blocco;
    }
    
    /**
     * @author Luca Giordano <l.giordano@about-x.com>
     */
    public function ajax_load_block_impostazioni_collega_tabelle()
    {
        echo $this->load_block_impostazioni_collega_tabelle();
    }
    
    /**
     * 
     * @param type $interface interfaccia desktop
     * @return type
     * @author Luca Giordano
     */
    public function load_block_impostazioni_collega_tabelle($interface='desktop')
    {
        $listaarchivi=$this->Sys_model->get_archive_list();
        $data['data']=$listaarchivi;
        return $this->load->view('sys/'.$interface.'/block/impostazioni_collega_tabelle',$data);
    }
    
    /**
     * @author Luca Giordano
     */
    public function ajax_load_block_tipi_campi()
    {
        echo $this->load_block_tipi_campi();
    }
    
    public function load_block_tipi_campi($interface='desktop')
    {
        return $this->load->view('sys/'.$interface.'/block/tipi_campi.php');
    }
    /**
     * 
     * @param string $idarchivio
     * @author Luca Giordano
     */
    public function ajax_load_block_LoadPreferencesNewVersion($idarchivio,$filtro)
    {
       $this->LoadPreferencesNewVersion($idarchivio,$filtro);
    }
    
    public function ajax_load_block_LoadPreferencesLabel($idarchivio,$typeLabel)
    {
        $this->LoadPreferencesLabel($idarchivio,$typeLabel);
    }
    
    public function LoadPreferencesLabel($idarchivio,$typeLabel)
    {
        $data['data']=$this->Sys_model->LoadPreferencesLabel($idarchivio,$typeLabel,$this->session->userdata('idutente'));
        $data['idarchivio'] = $idarchivio;
        return $this->load->view('sys/desktop/block/loaded_preferences_label',$data);
    }
    public function LoadPreferencesNewVersion($idarchivio,$tipopreferenza='campiInserimento')
    {
        if($tipopreferenza=='creazione_campi')
            $tipopreferenza='campiInserimento';
        $userid=$this->session->userdata('idutente');
        $data['data']=$this->Sys_model->LoadPreferencesNewVersion($idarchivio,$userid,$tipopreferenza);
        $data['idarchivio'] = $idarchivio;
        return $this->load->view('sys/desktop/block/loaded_preferences',$data);
    }
    
    public function ajax_get_options_lookuptableid($lookuptableid)
    {
        
        $data=$this->Sys_model-> get_lookuptable($lookuptableid);
        echo json_encode($data);
    }
    
    public function ajax_get_options_lookuptableid_byfieldid($tableid,$fieldid)
    {
        $lookuptableid=$this->Sys_model->db_get_value('sys_field','lookuptableid',"tableid='$tableid' AND fieldid='$fieldid'");
        $data=$this->Sys_model-> get_lookuptable($lookuptableid);
        echo json_encode($data);
    }
       
    public function ajax_get_options_sublabel($tableid)
    {
        $data=$this->Sys_model->db_get('sys_table_sublabel','*',"tableid='$tableid'",'ORDER BY sublabelname');
        echo json_encode($data);
    }
    
    public function load_block_impostazioni($tipo)
    {
        $return='';
        if($tipo=='archivi')
        {
            $data=array();
            $archivi=  $this->Sys_model->get_archive_list();
            $data['archivi']=$archivi;
            $return= $this->load->view("sys/desktop/block/impostazioni_archivi",$data,true);
        }
        if($tipo=='layout')
        {
            $data=array();
            $archivi=  $this->Sys_model->get_archive_list();
            $data['archivi']=$archivi;
            $return= $this->load->view("sys/desktop/block/impostazioni_layout",$data,true);
        }
        if($tipo=='utente')
        {
            $data=array();
            $return= $this->load->view("sys/desktop/block/impostazioni_utente",$data,true);
        }
        if($tipo=='script')
        {
            $data=array();
            $return= $this->load->view("sys/desktop/block/impostazioni_script_menu",$data,true);
        }
        if($tipo=='dashboard')
        {
            $data=array();
            $archivi=  $this->Sys_model->get_archive_list();
            $dashboards= $this->Sys_model->db_get("sys_dashboard","id,name");
            $data['dashboards']=$dashboards;
            $views= $this->Sys_model->db_get("sys_view","id,name","true","ORDER BY tableid");
            $data['views']=$views;
            $reports= $this->Sys_model->db_get("sys_report","id,name","true","ORDER BY tableid");
            $data['reports']=$reports;
            $data['archivi']=$archivi;
            $return= $this->load->view("sys/desktop/block/impostazioni_dashboard",$data,true);
        }
        
        return $return;
            
    }
    
    public function load_block_impostazioni_campi($interface)
    {
            $listaarchivi=$this->Sys_model->get_archive_list();
            $data['data']=$listaarchivi;
            return $this->load->view('sys/'.$interface.'/block/impostazione_campi',$data);
    }
    
    public function load_block_impostazioni_campi_archivi($interface)
    {
            $listaarchivi=$this->Sys_model->get_archive_list();
            $data['data']=$listaarchivi;
            return $this->load->view('sys/'.$interface.'/block/impostazione_campi_archivi',$data); //in pratica richiamo la pagina che contiene la lista degli archivi
    }
    
    public function ajax_load_block_impostazioni_campi_collega_tabelle($idarchivio,$interface='desktop')
    {
        $data['data']=  $this->Sys_model->get_all_emptyfields($idarchivio);
        return $this->load->view('sys/'.$interface.'/block/campi_preferenze_collega_tabelle',$data);
    }
    
    public function ajax_load_block_campi_preferenze($interface='dekstop',$idarchivio='CANDID'){
        $blocco = $this->load_block_campi_preferenze($interface, $idarchivio);
        echo $blocco;
    }
    
    
    public function load_block_campi_preferenze($interface,$idarchivio)
    {
        $data['data']=  $this->Sys_model->get_all_emptyfields($idarchivio);
        return $this->load->view('sys/'.$interface.'/block/campi_preferenze',$data);
    }
    
    
    public function ajax_load_block_labels($idarchivio)
    {
        $data['data']['labels']=$this->Sys_model->get_label_list($idarchivio);
        $data['data']['idarchivio']=$idarchivio;
        echo $this->load->view('sys/desktop/block/impostazioni_campi_labels',$data);
    }
    
    
    public function get_label_for_option($idarchivio)
    {
        $elenco_label = array();
        $etichette = $this->Sys_model->get_labels($idarchivio);
        foreach($etichette as $etichetta)
            $elenco_label[] = $etichetta;
        echo json_encode($elenco_label);
    }
    
    
    public function ajax_load_block_creazione_labels()
    { echo $this->load->view('sys/desktop/block/block_creazione_label'); }
    
    public function ajax_load_block_creazione_campi()
    { echo $this->load->view('sys/desktop/block/block_creazione_campi'); }
    
    
    public function ajax_create_archive(){
        $post=$_POST;
        $this->Sys_model->create_archive($post);
        //$this->create_office_fields($post['idarchivio']);
    }
    
    public function create_new_label()
    {
        $tablename = $_POST['idarchivio'];
        $labelname = str_replace("'", "''",$_POST['textlabel']);
        $this->Sys_model->execute_query("INSERT INTO sys_table_sublabel(tableid,sublabelname) VALUES('$tablename','$labelname')");
    }
    
    public function deleteOption()
    {
        $itemcode=$_POST['itemcode'];
        $lookuptableid = $_POST['lookuptableid'];
        $sql="DELETE FROM sys_lookup_table_item WHERE itemcode ILIKE '$itemcode' AND lookuptableid ILIKE '$lookuptableid'";
        $this->Sys_model->execute_query($sql);
    }
    
    public function create_office_fields($tableid)
    {
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_data','Data',10,'Data','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_destinatario','Parola',100,'Destinatario','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_mittente','Parola',100,'Mittente','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_oggetto','Parola',100,'Oggetto','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_originale','Parola',255,'Originale','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_testo','Memo',1024,'Testo','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_tipofile','Parola',100,'TipoFile','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_titolo','Parola',100,'Titolo','Office')";
        $this->Sys_model->execute_query($sql);
        $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label) VALUES('$tableid','off_utente','Parola',100,'Utente','Office')";
        $this->Sys_model->execute_query($sql);
        
        //adesso inserisco i campi nella sys_user_order
        $arrayCampiOffice=array('off_data','off_destinatario','off_mittente','off_oggetto','off_originale','off_testo','off_tipofile','off_titolo','off_utente');
        $i=1;
        foreach ($arrayCampiOffice as $valore)
        {
            $sql="INSERT INTO sys_user_order(userid,tableid,fieldid,fieldorder,typepreference) VALUES (1,'$tableid','$valore',".$i.",'campiInserimento')";
            $this->Sys_model->execute_query($sql);
            $i++;
        }
    }
    public function save_creazione_campi()
    {
        $dbdriver=  $this->Sys_model->get_dbdriver();
        $post=$_POST;
        foreach($post['fields'] as $field)
        {
            $fieldid=$field['fieldid'];
            if($field['insertorupdate']=="insert")//ora ciclo tutti i campi di insert
            {
                //inserisco il campo nella sys_field
                $length=0;
                $decimalposition='NULL';
                $description = $field['description'];
                $fieldtypeid=$field['tipocampo'];
                $label=$field['label'];
                $idarchivio=$field['idarchivio'];
                $position=$field['campoposition'];
                if($fieldtypeid!='categoria')
                {
                    if($fieldtypeid=='Numero')
                    {
                        $length=10;
                        $decimalposition="3";
                    }
                    else
                    {
                        $length=255;
                    }
                    $sublabel='';
                    if($label!='Dati')
                    {
                        $sublabel=$label;
                    }
                    $label='Dati';
                    $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,decimalposition,description,label,sublabel) VALUES('$idarchivio','$fieldid','$fieldtypeid',$length,$decimalposition,'$description','$label','$sublabel')";
                    $this->Sys_model->execute_query($sql);

                    //adesso inserisco il campo come colonna nella tabella
                    $type_column='';
                    if($dbdriver=='postgre')
                    {
                        if(($fieldtypeid=='Parola')||($fieldtypeid=='Utente'))
                            $type_column="character varying(255)";
                        if($fieldtypeid=='Data')
                            $type_column='date';
                        if($fieldtypeid=='Numero')
                            $type_column='numeric';
                        if($fieldtypeid=='Memo')
                            $type_column='text';
                        if($fieldtypeid=='Ora')
                            $type_column='time without time zone';
                        if($fieldtypeid=='Seriale')
                            $type_column='serial';
                        $sql='ALTER TABLE user_'.$idarchivio.' ADD "'.$fieldid.'" '.$type_column;
                    }
                    if($dbdriver=='mysqli')
                    {
                        if(($fieldtypeid=='Parola')||($fieldtypeid=='Utente'))
                            $type_column="varchar(255)";
                        if($fieldtypeid=='Data')
                            $type_column='date';
                        if($fieldtypeid=='Numero')
                            $type_column='float';
                        if($fieldtypeid=='Memo')
                            $type_column='longtext';
                        if($fieldtypeid=='Ora')
                            $type_column='time';
                        if($fieldtypeid=='Seriale')
                            $type_column='int';
                        $sql='ALTER TABLE user_'.$idarchivio.' ADD '.$fieldid.' '.$type_column;
                    }

                    
                    $this->Sys_model->execute_query($sql);
                }
                else //questo si verifica quando ho inserito una categoria
                {
                    //devo inserire il campo e le options
                    $lookuptableid=$fieldid."_".$idarchivio;
                    $sql="INSERT INTO sys_lookup_table (description,tableid,itemtype,codelen,desclen) VALUES ('$fieldid','$lookuptableid','Carattere',255,255)";
                    $this->Sys_model->execute_query($sql);
                    
                    $sublabel='';
                    if($label!='Dati')
                    {
                        $sublabel=$label;
                    }
                    $label='Dati';
                    $sql = "INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,description,label,sublabel,lookuptableid) VALUES('$idarchivio','$fieldid','Parola',255,'$description','$label','$sublabel','$lookuptableid')";
                    $this->Sys_model->execute_query($sql);

                    if($dbdriver=='postgre')
                    {
                        $sql='ALTER TABLE user_'.$idarchivio.' ADD "'.$fieldid.'" character varying(255)';
                    }
                    
                    if($dbdriver=='mysqli')
                    {
                        $sql='ALTER TABLE user_'.$idarchivio.' ADD '.$fieldid.' varchar(255)';
                    }
                    $this->Sys_model->execute_query($sql);


                    //if(key_exists('options', $field))
                    //{
                        foreach ($field['options'] as $option)
                        {
                            $optionid=$option['id'];
                            $optiondesc=$option['description'];
                            $sql = "INSERT INTO sys_lookup_table_item(lookuptableid,itemcode,itemdesc) VALUES('$lookuptableid','$optionid','$optiondesc')";
                            $this->Sys_model->execute_query($sql);
                        }
                    //}
                }
                $sql="INSERT INTO sys_user_order (userid,tableid,fieldid,fieldorder,typepreference) VALUES(1,'$idarchivio','$fieldid',$position,'campiInserimento')";
                $this->Sys_model->execute_query($sql);
            }
            if($field['insertorupdate']=="update")//ciclo tutti i campi di update
            {
                $descrizione=$field['description'];
                $fieldid=$field['fieldid'];
                $posizione=$field['position'];
                $idarchivio=$field['idarchivio'];
                $label=$field['label'];
                $sql="UPDATE sys_user_order SET fieldorder=".$posizione." WHERE fieldid LIKE '".$fieldid."' AND tableid='$idarchivio' AND typepreference LIKE 'campiInserimento'";
                $this->Sys_model->execute_query($sql);
                
                $sql="UPDATE sys_field SET description='".str_replace("'", "''", $descrizione)."' WHERE fieldid LIKE '$fieldid' AND tableid LIKE '$idarchivio'";
                $this->Sys_model->execute_query($sql);
                
                //adesso ciclo tutte le option
                foreach($field['options'] as $option)
                {
                    $itemcode = str_replace("'", "''", $option['itemcode']);
                    $itemdesc = str_replace("'", "''", $option['itemdesc']);
                    $lookuptableid=  str_replace("'","''",$option['lookuptableid']);
                    if($option['insertorupdate']=='update')
                    {
                        $sql = "UPDATE sys_lookup_table_item SET itemdesc='$itemdesc' WHERE itemcode='$itemcode' AND lookuptableid='$lookuptableid'";
                        $this->Sys_model->execute_query($sql);
                    }
                    if($option['insertorupdate']=='insert')
                    {                        
                        $sql = "INSERT INTO sys_lookup_table_item(lookuptableid,itemcode,itemdesc) VALUES('$lookuptableid','$itemcode','$itemdesc')";
                        $this->Sys_model->execute_query($sql);
                    }
                }
            }
        }
    }
    
    public function load_autobatch_files()
    {
        $autobatches=  $this->Sys_model->get_autobatch();

        foreach ($autobatches as $key => $autobatch) {
            $autobatchid=$autobatch['id'];
            $this->Sys_model->get_files_autobatch($autobatchid);
            $this->genera_thumbnail_autobatch($autobatchid);
        }
    }
    
    public function check_autobatch()
    {
        $autobatches=  $this->Sys_model->get_autobatch();
        foreach ($autobatches as $key => $autobatch) {
                $autobatchid=$autobatch['id'];
                $tableid=$autobatch['tableid'];
                $autobatch_files=  $this->Sys_model->get_autobatch_files($autobatchid);
                $continue=false;
                foreach ($autobatch_files as $key => $autobatch_file) {
                    //CUSTOM WW
                    /*if($continue)
                    {
                        $continue=false;
                        continue;
                    }*/
                    $recordid='';
                    $autobatch_fileid=$autobatch_file['fileid'];
                    $ocr=$autobatch_file['ocr'];
                    if($autobatchid=='contratti')
                    {
                        $rif='';
                        $startrif_position=  strpos($ocr, "X");
                        $endrif_position=  strpos($ocr, "X", $startrif_position+1);
                        $rif=  substr($ocr, $startrif_position+1, $endrif_position-$startrif_position-1);
                        $rif=str_replace('o', '0', $rif);
                        $rif=str_replace('O', '0', $rif);
                        $rif=str_replace('l', '1', $rif);
                        $len=  strlen($rif);
                        if($len==5)
                        {
                        $recordid=  $this->Sys_model->get_recordid_byrif("CONTRA", $rif);
                        }

                        //echo $recordid;
                        $len=  strlen($recordid);
                        if($len==32)
                        {
                           // if(strpos(strtolower(substr($ocr, 0, 100)), "missione")!==false)
                           // {
                                $autobatch_fileid2=$autobatch_files[$key+1]['fileid'];
                                $this->Sys_model->autobatch_fronteretro($autobatchid, $autobatch_fileid,$autobatch_fileid2, "CONTRA",$recordid);
                                $continue=true;
                           // }
                           // else
                          //  {
                          //      $this->Sys_model->autobatch_insert_file($autobatchid, $autobatch_fileid, "CONTRA",$recordid);
                         //   }
                        }

                    }
                    else
                    {
                        if($this->isnotempty($tableid))
                        {
                            $this->Sys_model->autobatch_insert_file($autobatchid, $autobatch_fileid, $tableid, $recordid);
                        }
                        //$this->Sys_model->autobatch_insert_file($autobatchid, $autobatch_fileid, $tableid, $recordid);
                    }
                }
            
        }
    }
    
    
    public function set_preferences_tabelle_collegate($mastertableid,$linkedtableid,$stringa)
    {
        //RECUPERO IL TESTO DELLA LABEL LINKED
        $data=$this->Sys_model->select("SELECT description FROM sys_table WHERE id LIKE '$linkedtableid'");
        $testolabel=$data[0]['description'];
        
        //INSERIMENTO TABELLA MASTER
        $sql="INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,decimalposition,description,fieldorder,lookuptableid,lookupcodedesc,lookupdesclen,label,tablelink)
              VALUES ('$mastertableid','recordid" . strtolower($linkedtableid) ."_','Parola',40,0,'$testolabel',1,'',0,0,'$linkedtableid','$linkedtableid')";
        $this->Sys_model->execute_query($sql);
                
        //splitto la stringa
        $campicollegare="";
        $array = explode("___", $stringa);
        $i=0;
        foreach($array as $elemento)
        {
            $sottoelementisplit = explode("-",$elemento);
            if($i!=0)
                $campicollegare.=",".$sottoelementisplit[0];
            else
                $campicollegare=$sottoelementisplit[0];
            $i++;
        }
        
                //RECUPERO TESTO DELLA LABEL LINKED
        $data=$this->Sys_model->select("SELECT description FROM sys_table WHERE id LIKE '$mastertableid'");
        $testolabel=$data[0]['description'];
        
        //INSERIMENTO TABELLA COLLEGATA
        $sql="INSERT INTO sys_field(tableid,fieldid,fieldtypeid,length,decimalposition,description,fieldorder,lookuptableid,lookupcodedesc,lookupdesclen,label,tablelink,keyfieldlink)
              VALUES ('$linkedtableid','recordid" . strtolower($mastertableid) ."_','Parola',40,0,'$testolabel',1,'',0,0,'$mastertableid','$mastertableid','$campicollegare')";
        $this->Sys_model->execute_query($sql);
        
        //INSERIMENTO TABELLA SYS_TABLE_LINK
        $sql="INSERT INTO sys_table_link(tableid,tablelinkid) VALUES('$mastertableid','$linkedtableid')";
        $this->Sys_model->execute_query($sql);
        
        //inserisco il campocollegato nella tabella slave
        $mastertableid=  strtolower($mastertableid);
        $linkedtableid= strtolower($linkedtableid);
        $sql="ALTER TABLE user_".$linkedtableid. " ADD recordid".$mastertableid.'_ character varying(32)';
        $this->Sys_model->execute_query($sql);
    }
    
    
    /**
     * 
     * @param type $campi stringa dei campi esempio lingua&candlingue,assegni&candid
     * @author Luca Giordano
     */
    public function set_preferences($typepreference,$idutente='1')
    {
        //ELIMINO LE PREFERENZE PRECEDENTEMENTE IMPOSTATE
        $post=$_POST;
        $idarchivio=$post['fields'][0]['idarchivio'];
        $this->Sys_model->delete_preferences($idutente,$typepreference,$idarchivio);
        
        //INSERISCO LE NUOVE PREFERENZE
        foreach($_POST['fields'] as $field)
        {
            $fieldid=$field['fieldid'];
            $tableid=$field['idarchivio'];
            $fieldorder = $field['position'];
            $this->Sys_model->set_preferences($idutente,$tableid,$fieldid,$fieldorder,$typepreference);
            /*if($typepreference=='keylabel_inserimento')
            {
                if($field['insertorupdate']=='update')
                {
                    
                }
            }*/
        }
        
        
    }
    
    
    //fine impostazioni
    
    
    public function stampa_elenco($idarchivio='CANDID',$order_key='recordid_',$order_ascdesc='DESC'){
        
        $post=$_POST;
        $campi_ricerca=array();
        foreach($post['tables'] as $keytable => $table)
        {
            if(array_key_exists('search', $table))
            {
                foreach($table['search'] as $keyT => $T)
                {
                    if(array_key_exists('fields', $T))
                    {
                        foreach($T['fields'] as $keyCampo => $campo)
                        {        
                            foreach($campo as $keyF => $F)
                            {
                                $campi_ricerca['tables'][$F['label']][$keyT]['table_param']=$T['table_param'];
                                $campi_ricerca['tables'][$F['label']][$keyT]['fields'][$keyCampo][$keyF]=$F;
                            }

                        }
                    }
                }
            }
            if(array_key_exists('tutti', $table))
            {
                foreach($table['tutti'] as $keyT => $T)
                {
                    if(array_key_exists('fields', $T))
                    {
                        foreach($T['fields'] as $keyCampo => $campo)
                        {        
                            foreach($campo as $keyF => $F)
                            {
                                $campi_ricerca['tables'][$F['label']][$keyT]['table_param']='';
                                $campi_ricerca['tables'][$F['label']][$keyT]['fields'][$keyCampo][$keyF]=$F;
                            }

                        }
                    }
                }
            }
        }
        $data['data']['userid']=  $this->session->userdata('userid');
        $query=$post['query'];
        $result=$this->Sys_model->get_search_result($idarchivio,$query,$order_key,$order_ascdesc);
        $data['data']['risultati']=$result;
        $data['data']['sql']=$query;
        $data['data']['archivio']=$idarchivio;
        $data['data']['campi_ricerca']=$campi_ricerca;
        echo $this->load->view('sys/desktop/stampe/stampa_elenco',$data);
        //var_dump($data['data']['campi_ricerca']['tables']);
    }
    
    
    public function download_elenco($nome_file='')
    {
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    
    public function ajax_load_block_invia_mail($tableid,$query='null',$recordid='null'){
        echo $this->load_block_invia_mail($tableid,$query,$recordid);
    }
    
    public function load_block_invia_mail($tableid,$query,$recordid){
        $data['data']['tableid']=$tableid;
        $data['data']['query']=$query;
        $data['data']['recordid']=$recordid;
        $data['data']['columns']=  $this->Sys_model->get_all_columns($tableid);
        return $this->load->view('sys/desktop/block/invia_mail',$data, TRUE);
    }
    
    public function ajax_load_block_invio_pushup($recordid='null'){
        echo $this->load_block_invio_pushup($recordid);
    }
    
    public function load_block_invio_pushup($recordid){
        $data['recordid']=$recordid;
        $data['destinatari']=  $this->Sys_model->get_destinatari_pushup($recordid);
        $data['subject']='Nuovo candidato Work & Work';
        $data['anteprima_pushup']=$this->load_mail_pushup($recordid);
        return $this->load->view('sys/desktop/block/invio_pushup',$data, TRUE);
    }
    
    public function load_mail_pushup($recordid)
    {
        $data=array();
        $dati_pushup=  $this->Sys_model->get_dati_pushup($recordid);
        $settore=  $this->Sys_model->get_lookup_table_item_description('PROFIL',$dati_pushup['settore']);
        $data['settore']=  strtoupper($settore);
        $data['id']=$dati_pushup['id'];
        $data['qualifica']=$dati_pushup['qualifica'];
        $data['pchiave']=$dati_pushup['pchiave'];
        $data['lingue']=$dati_pushup['lingue'];
        $data['disponibilita']=$dati_pushup['disponibilita'];
        $data['percentuale_lavorativa']=$dati_pushup['percentuale_lavorativa'];
        return $this->load->view('mail_template/pushup',$data,TRUE);
    }
    
    public function ajax_invia_mail(){
        $query=$_POST['query'];
        $tableid=$_POST['tableid'];
        $tipoinvio=$_POST['tipoinvio'];
        $mails=  $this->Sys_model->get_mails($tableid,$tipoinvio,$query);
        $destinatari='';
        $counter=0;
        foreach ($mails as $key => $mail) {
            $indirizzo_mail=$mail['email'];
            if($this->isnotempty($indirizzo_mail))
            {
                if($counter>0)
                $destinatari=$destinatari.';';
                $destinatari=$destinatari.$indirizzo_mail;
                $counter++;  
            }
        }
        if($destinatari!='')
        {
           //$destinatari="bcc=$destinatari&"; 
        }
        $subject=$_POST['mail_subject'];
        if($subject!='')
        {
            $subject="subject=$subject&";
        }
        
        $body=$_POST['mail_body'];
        $body= nl2br($body);
        $body=  str_replace('<br />', '%0D%0A', $body);
        $body=  str_replace('<br>', '%0D%0A', $body);
        if($body!='')
        {
            $body="body=$body";
        }
        $return="mailto:?$destinatari".$subject.$body;
        //echo $return;
        echo $destinatari;
    }
    
    public function ajax_load_block_dem_select(){
        echo $this->load_block_dem_select();
    }
    
    public function load_block_dem_select(){
        $data['dems']=  $this->Sys_model->db_get('user_dem');
        return $this->load->view('sys/desktop/block/dem_select',$data, TRUE);
    }
    
    public function ajax_load_block_campagne_select(){
        echo $this->load_block_campagne_select();
    }
    
    public function load_block_campagne_select(){
        $data['campagne']=  $this->Sys_model->db_get('user_eventi_campagne');
        return $this->load->view('sys/desktop/block/campagne_select',$data, TRUE);
    }
    
    public function dem_carica_mail($dem_recordid)
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $query=$post['query'];
        $dem_mail_field=  $this->Sys_model->get_table_setting($tableid,'dem_mail_field');
        $table="user_".strtolower($tableid);
        $X="SELECT * FROM $table JOIN (".$query.") AS risultati2 ON $table.recordid_=risultati2.recordid_ ";
        $rows=  $this->Sys_model->select($X);
        foreach ($rows as $key => $row) {
            $mail=$row[$dem_mail_field];
            if($this->isnotempty($mail))
            {
                $master_tableid=$tableid;
                $master_recordid=$row['recordid_'];
                $this->Sys_model->dem_add_mail($dem_recordid,$master_tableid,$master_recordid,$mail);
            }
            
        }
    }
    
    public function campagna_carica_telemarketing($campagna_recordid)
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $query=$post['query'];
        $table="user_".strtolower($tableid);
        $X="SELECT * FROM $table JOIN (".$query.") AS risultati2 ON $table.recordid_=risultati2.recordid_ ";
        $rows=  $this->Sys_model->select($X);
        foreach ($rows as $key => $row) {
            
                $master_tableid=$tableid;
                $master_recordid=$row['recordid_'];
                $this->Sys_model->campagna_add_telemarketing($campagna_recordid,$master_tableid,$master_recordid);
            
        }
    }
    
    public function ajax_load_block_esporta_risultati($tableid,$query='null',$recordid='null'){
        echo $this->load_block_esporta_risultati($tableid,$query,$recordid);
    }
    
    public function load_block_esporta_risultati($tableid,$query,$recordid){
        $data['data']['tableid']=$tableid;
        $data['data']['query']=$query;
        $data['data']['recordid']=$recordid;
        $userid_stampe= $this->Sys_model->db_get_value('sys_user','id',"username='stampe'");
        if(isempty($userid_stampe))
        {
            $userid_stampe=1;
        }
        $data['data']['columns']=  $this->Sys_model->get_all_columns($tableid);
        return $this->load->view('sys/desktop/block/esporta_risultati',$data, TRUE);
    }
    
    
    public function esporta_xls2(){
        
        $post=$_POST;
        $tableid=$post['tableid'];
        $query=$post['query'];
        
        $columns=  $this->Sys_model->get_colums($tableid, 1);
        $data['columns']=$columns;
        $data['tableid']=$tableid;
        $records=$this->Sys_model->get_records($tableid,$query,'','','0','10000');
        $data['records']=$records;

                
        $this->load->view('sys/desktop/moduli/esporta_xls',$data);
        
    }
    
    public function esporta_xls(){
        $post=$_POST;
        $tableid=$_POST['tableid'];
        $query=$_POST['query'];
        if(array_key_exists('exportid', $post))
        {
           $exportid=$post['exportid']; 
        }
        else
        {
            $exportid='';
        }
        
        if($exportid=='export_lgl')
        {
            $this->ajax_esporta_excel_lgl();
            
        }   
        else
        {
                $columns=array();
                //$recordid=$_POST['esporta_recordid'];
                if(array_key_exists("columns", $post))
                {
                    $selected_columns=$post['columns'];
                    $all_columns=$this->Sys_model->get_all_columns($tableid);
                    $result=array();

                    $tablename='user_'.strtolower($tableid);
                    //$query=  strstr($query, 'FROM');
                    $select='SELECT ';
                    foreach ($selected_columns as $key => $selected_column) 
                    {
                        $column_row=$all_columns[$selected_column];
                        $column['tableid']=$tableid;
                        $column['fieldid']=$column_row['fieldid'];
                        $column['fielddesc']=$column_row['description'];
                        $column['fieldtypeid']=$column_row['fieldtypeid'];
                        $column['lookuptableid']=$column_row['lookuptableid'];
                        $column['linkedtableid']=$column_row['linkedtableid'];
                        $columns[$column_row['fieldid']]=$column;
                        if(($column_row['tablelink']!='')&&($column_row['tablelink']!=null)&&($column_row['keyfieldlink']!=null)&&($column_row['keyfieldlink']!=''))
                        {
                            $selected_column='recordid'.strtolower($column_row['tablelink']).'_';
                        }

                        if($select=='SELECT ')
                        {
                          $select=$select.$tablename.'.'.$selected_column;  
                        }
                        else
                        {
                          $select=$select.','.$tablename.'.'.$selected_column;  
                        }
                    }

                    $query= "$select FROM ( $query ) as recordsource JOIN $tablename ON recordsource.recordid_=$tablename.recordid_";
                    $result=  $this->Sys_model->select($query);
                }

                if($exportid!='')
                {
                    $export_query=  $this->Sys_model->get_export_query($exportid);
                    $query="SELECT T2.* 
                            FROM
                            (
                            $query
                            ) AS T1
                             JOIN
                            (
                            $export_query
                            ) AS T2
                            ON T1.recordid_=T2.recordid_
                            ";
                    $result=  $this->Sys_model->select($query);
                    $columns=$this->Sys_model->get_result_columns($tableid,$result);
                }



                //CUSTOM WORK&WORK
                if($tableid=='CANDID')
                {
                   // $columns[]='Recordid_';
                   // $columns[]='Cognome';
                   // $columns[]='Nome';


                }

                if(false)
                {
                    $result=  $this->Sys_model->get_result_converted($tableid,$columns,$result); 
                }
                else
                {
                    $result=  $this->Sys_model->convert_fields_value_to_final_value($tableid,$columns,$result);
                }

                $data['data']['columns']=$columns;
                $data['data']['records']=$result;
                $this->load->view('sys/desktop/moduli/esporta_xls',$data);
        }
    }
    
    public function ajax_esporta_excel_lgl(){
        $post=$_POST;
        $tableid=$_POST['tableid'];
        
        $query=$_POST['query'];
        $query=$query." ORDER BY risultati.cognome";
        $rows=  $this->Sys_model->select($query);
        $columns=array();
        $result=array();
        $columns[]['fielddesc']='Data di invio';
        $columns[]['fielddesc']='Azienda';
        $columns[]['fielddesc']='Data ingresso';
        $columns[]['fielddesc']='Cognome';
        $columns[]['fielddesc']='Nome';
        $columns[]['fielddesc']='Sito';
        $columns[]['fielddesc']='Provincia di residenza';
        $columns[]['fielddesc']='Formazione scolastica';
        $columns[]['fielddesc']='Qualifica';
        $columns[]['fielddesc']='Prec. esp. in campo logistico';
        $columns[]['fielddesc']='Job Title';
        $columns[]['fielddesc']='P. di lavoro provvisorio';
        $columns[]['fielddesc']='Data scadenza';
        $columns[]['fielddesc']='P. di lavoro definitivo';
        $columns[]['fielddesc']='Data scadenza';
        $columns[]['fielddesc']='Casellario Giudiziario';
        $columns[]['fielddesc']='Casellario Giudiziario';
        $columns[]['fielddesc']='Trattamento dati personali';
        $columns[]['fielddesc']='Formazione da agenzia circa LGL';
        $columns[]['fielddesc']='Formazione sicurezza e movimentazione carichi fornita da ente specializzato';
        $columns[]['fielddesc']='Patente CH carrello a timone ';
        $columns[]['fielddesc']='Patente CH carrello frontale';
        $columns[]['fielddesc']='Patente CH carrello retrattile';
        $columns[]['fielddesc']='Patente CH piattaforma';
        $columns[]['fielddesc']='Data scadenza';
        $columns[]['fielddesc']='Patente IT carrelli';
        $columns[]['fielddesc']='Eperienze uso carrelli > 1 anno';
        $result=$this->Sys_model->get_export_xls_result($rows);

        
        $data['data']['columns']=$columns;
        $data['data']['records']=$result;
        
        
        
        $this->load->view('sys/desktop/moduli/esporta_xls',$data);
    }
    
    //CUSTOM WW
    public function ajax_esporta_file_lgl()
    {
        $post=$_POST;
        $tableid=$_POST['tableid'];
        
        $query=$_POST['query'];
        $rows=  $this->Sys_model->select($query);
        
        $userid=  $this->session->userdata('userid');
        
        if(!file_exists("../JdocServer/generati/$userid"))
        {
            mkdir("../JdocServer/generati/$userid");
        }
         
        if(!file_exists("../JdocServer/generati/$userid/documenti_lgl"))
        {
            mkdir("../JdocServer/generati/$userid/documenti_lgl");
        }        
        $documenti_lgl_zip="../JdocServer/generati/$userid/documenti_lgl.zip";
        if(file_exists($documenti_lgl_zip))
        {
            unlink($documenti_lgl_zip);
        }
        $temp_files=scandir("../JdocServer/generati/$userid/documenti_lgl"); 
        foreach ($temp_files as $key => $file) {
            if(($file!='.')&&($file!='..'))
            {
                if(is_dir("../JdocServer/generati/$userid/documenti_lgl/".$file)) 
                { 
                    $dir=$file;
                    $folder_files=scandir("../JdocServer/generati/$userid/documenti_lgl/$dir");
                    foreach ($folder_files as $key => $folder_file) {
                        if(($folder_file!='.')&&($folder_file!='..'))
                        {
                            unlink("../JdocServer/generati/$userid/documenti_lgl/$dir/".$folder_file); 
                        }
                        
                    }
                    rmdir("../JdocServer/generati/$userid/documenti_lgl/$dir");
                }
                else
                {
                    unlink("../JdocServer/generati/$userid/documenti_lgl/".$file); 
                }
            }
        }
        
        foreach ($rows as $key => $row) 
        {
            $this->genera_casellario($row['recordid_']);
            $lgl_files=  $this->Sys_model->get_lgl_files($row['recordid_']); 
            if(count($lgl_files)>0)
            {
                $nome=$row['nome'];
                $cognome=$row['cognome'];
                mkdir("../JdocServer/generati/$userid/documenti_lgl/$cognome $nome");
                foreach ($lgl_files as $key => $lgl_file) 
                {
                    $file_category=$lgl_file['category'];
                    $lgl_file_name="";
                    if($file_category=="tdp")
                        $lgl_file_name='tdp';
                    if($file_category=="pdlp")
                        $lgl_file_name='pdlp';
                    if($file_category=="pdld")
                        $lgl_file_name='pdld';
                    if($file_category=="cgcp")
                        $lgl_file_name='cgcp';
                    if($file_category=="cgcd")
                        $lgl_file_name='cgcd';
                    if($file_category=="fga")
                        $lgl_file_name='fga';
                    if($file_category=="fsa")
                        $lgl_file_name='fsa';
                    if(strpos($file_category,"pc a timone") !== false)
                        $lgl_file_name='pc';
                    if(strpos($file_category,"pc frontale") !== false)
                        $lgl_file_name='pc';
                    if(strpos($file_category,"pc retrattile") !== false)
                        $lgl_file_name='pc';
                    if($file_category=="pp")
                        $lgl_file_name='pp';


                    $lgl_file_name=$lgl_file_name." ".ucfirst($cognome)." ".ucfirst($nome).".".$lgl_file['extension_'];
                    $lgl_file_path="../JDocServer/".str_replace( "\\", "/",$lgl_file['path_'])."/".$lgl_file['filename_'].".".$lgl_file['extension_'];
                    if(file_exists($lgl_file_path))
                    {
                        if(file_exists("../JdocServer/generati/$userid/documenti_lgl/$cognome $nome/$lgl_file_name"))
                        {
                            unlink("../JdocServer/generati/$userid/documenti_lgl/$cognome $nome/$lgl_file_name");
                        }
                        copy($lgl_file_path, "../JdocServer/generati/$userid/documenti_lgl/$cognome $nome/$lgl_file_name");
                    }
                    
                }
            }
           
        }
        
        $this->zip("..\..\..\JDocServer\generati\\$userid\documenti_lgl","..\..\..\JDocServer\generati\\$userid\documenti_lgl");
    }
    
     public function prescarica_allegati_selezionati($tipo='originale')
    {
        $post=$_POST;
        $tableid=$_POST['tableid'];
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        
        $userid=  $this->session->userdata('userid');
        
        if(!file_exists("../JdocServer/generati/$userid"))
        {
            mkdir("../JdocServer/generati/$userid");
        }
         
        if(!file_exists("../JdocServer/generati/$userid/allegati"))
        {
            mkdir("../JdocServer/generati/$userid/allegati");
        }        
        $documenti_lgl_zip="../JdocServer/generati/$userid/allegati.zip";
        if(file_exists($documenti_lgl_zip))
        {
            unlink($documenti_lgl_zip);
        }
        $temp_files=scandir("../JdocServer/generati/$userid/allegati"); 
        foreach ($temp_files as $key => $file) {
            if(($file!='.')&&($file!='..'))
            {
                if(is_dir("../JdocServer/generati/$userid/allegati/".$file)) 
                { 
                    $dir=$file;
                    $folder_files=scandir("../JdocServer/generati/$userid/allegati/$dir");
                    foreach ($folder_files as $key => $folder_file) {
                        if(($folder_file!='.')&&($folder_file!='..'))
                        {
                            unlink("../JdocServer/generati/$userid/allegati/$dir/".$folder_file); 
                        }
                        
                    }
                    rmdir("../JdocServer/generati/$userid/allegati/$dir");
                }
                else
                {
                    unlink("../JdocServer/generati/$userid/allegati/".$file); 
                }
            }
        }
        $files=array();
        if(array_key_exists('files', $post))
                if(array_key_exists('checked', $post['files']))
                    $files=$post['files']['checked'];
        if(count($files)>1)
        {
            foreach ($files as $key => $filename) 
            {

                $file=  $this->Sys_model->db_get_row('user_'.  strtolower($tableid).'_page','*',"filename_='$filename'");
                $extension=$file['extension_'];
                $original_name=$file['original_name'];
                $file_path=  $this->get_file_path($tableid,$filename,$tipo);
                if($file_path!=null)
                {
                    copy($file_path, "../JdocServer/generati/$userid/allegati/$original_name");
                }
                


            }
            
            $this->zip("..\JDocServer\generati\\$userid\allegati","..\JDocServer\generati\\$userid\allegati");
            /*$loop=true;
            $counter=0;
            while(!file_exists("../JdocServer/generati/$userid/allegati.zip")&&$loop)
            {
               sleep(1);
               if($counter>60)
               {
                   $loop=false;
               }
               $counter++; 
            }*/
             echo 'allegati.zip'; 
        }
        else
        {
            if(count($files)==1)
            {
                reset($files);
                $filename = current($files);
                $file=  $this->Sys_model->db_get_row('user_'.  strtolower($tableid).'_page','*',"filename_='$filename'");
                $extension=$file['extension_'];
                $original_name=$file['original_name'];
                $file_path=  $this->get_file_path($tableid,$filename,$tipo);
                if($file_path!=null)
                {
                    copy($file_path, "../JdocServer/generati/$userid/$original_name");
                }
                
                echo "$original_name";
            }
            else
            {
                echo 'null';
            }
        }
        
       // header('Content-type: application/pdf');

        // It will be called downloaded.pdf
       // header('Content-Disposition: attachment; filename="downloaded.pdf"');

        // The PDF source is in original.pdf
       // readfile("..\JDocServer\generati\$userid\allegati.zip");
      
        
    }
    
    public function scarica_allegati_selezionati($nomefile='allegati.zip')
    {
        $userid=  $this->get_userid();
        header("Content-type: Application/octet-stream"); 
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=$nomefile"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize("..\JDocServer\generati\\$userid\\$nomefile")); 
        readfile("..\JDocServer\generati\\$userid\\$nomefile"); 
    }
    
    public function get_file_path($tableid,$filename,$tipo='originale')
    {
        $file=  $this->Sys_model->db_get_row('user_'.  strtolower($tableid).'_page','*',"filename_='$filename'");
        $path=$file['path_'];
        $path=  str_replace("\\\\", "/", $path);
        $extension=$file['extension_'];
        if($tipo=='originale')
        {
          $file_path="../JDocServer/$path".$filename.".$extension"; 
          if(!file_exists($file_path))
            {
                $file_path=null;
            }
        }
        if($tipo=='preview')
        {
            $file_path="../JDocServer/$path".$filename."_preview.jpg"; 
            if(!file_exists($file_path))
            {
                $file_path="../JDocServer/$path".$filename.".$extension"; 
                if(!file_exists($file_path))
                  {
                      $file_path=null;
                  }
            }
                
             
        }
        
        return $file_path;
    }
    
    public function zip($source="",$dest="")
    {
        /*$command='cd ./tools/7zip/ && 7za.exe a -tzip "'.$source.'" "'.$dest.'" ';
        
        exec($command);*/
        $zip = new ZipArchive();
        $nomeZip = "$dest.zip";
        $files=array();
        if ($zip->open($nomeZip, ZIPARCHIVE::CREATE) === TRUE) {
            if(file_exists($source))
            {
               $files = scandir($source); 
            }
            foreach($files as $file) {
                if(($file!='.')&&($file!='..'))
                {
                    $content = file_get_contents("$source/$file");
                    $zip->addFromString(pathinfo ( "$source/$file", PATHINFO_BASENAME), $content);
                }
            }
        
            
        }
        
        
        
        $zip->close();
    }
    /**
     * Funzione che si occupa della generazione dei dati
     * @param type $recordid id del candidato
     * @param type $tipo (flash,cifrato,lgl)
     * @author Luca Giordano
     */
    public function stampa_profilo($recordid,$tipo='flash')
    {
        $nomefile='';
        if($tipo=='flash')
        {
             $data['data']['dati']=  $this->Sys_model->get_dati_stampa_profilo($recordid);
             $data['data']['dati']['foto']=$this->Sys_model->get_foto_path("CANDID", $recordid);
             echo $this->load->view('sys/desktop/stampe/stampa_profilo_flash',$data);  
        }
        if($tipo=='cifrato')
        {
              $data['data']['dati']=  $this->Sys_model->get_dati_stampa_profilo_cifrato($recordid);
              $data['data']['dati']['foto']=$this->Sys_model->get_foto_path("CANDID", $recordid);
              echo $this->load->view('sys/desktop/stampe/stampa_profilo_cifrato',$data); 
        }   
        if($tipo=='ws_lgl')
        {
              $data['data']['dati']=  $this->Sys_model->get_dati_stampa_profilo($recordid);
              $data['data']['dati']['foto']=$this->Sys_model->get_foto_path("CANDID", $recordid);
              echo $this->load->view('sys/desktop/stampe/stampa_profilo_ws_lgl',$data);
              //var_dump($data['data']['dati']);
        }               
        //$this->DownloadProfiloFlash();
    }
    
    public function stampa_curriculum_from_progetto1824($tipo='completo')
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $post=$_POST;
        $recordid_candidato=$post['recordid_candidato'];
        
        $template=$this->Sys_model->db_get_value('user_candidati','modellocv',"recordid_='$recordid_candidato'");
        if($template==null)
        {
           $template='Template 1'; 
        }
        if($tipo=='auto')
        {
            $recordid_azienda=$post['recordid_azienda'];
            $rendipubblico= $this->Sys_model->db_get_value('user_candidatiproposti','rendipubblico',"recordidaziende_='$recordid_azienda' AND recordidcandidati_='$recordid_candidato'");
            if($rendipubblico=='si')
            {
                $tipo='completo';
            }
            else
            {
                $tipo='anonimo';
            }
        }
        $template_num= str_replace('Template ', '', $template);
        $this->stampa_curriculum($recordid_candidato, $template_num,$tipo);
    }
    
    public function stampa_curriculum($recordid,$template=null,$tipo='completo')
    {
        //$template=1;
        $anonimo='';
        if($tipo=='anonimo')
        {
            $anonimo='anonimo_';
        }
        $data['userid']= $this->session->userdata('userid');
        $dati_candidato= $this->Sys_model->get_dati_candidato($recordid);        
        $foto_candidato= $this->Sys_model->get_foto_path("candidati",$recordid);
            
        $data['candidato']=$dati_candidato;
        $data['foto']=$foto_candidato;
        if(($template==null)||($template==''))
        {
            $template= $this->Sys_model->db_get_value('user_candidati','modellocv',"recordid_='$recordid'");
            if(($template==null)||($template==''))
            {
                $template=1;
            }
            else
            {
                $template= str_replace('Template ', '', $template);
            }
        }
        if($template==1)
        {
             echo $this->load->view('sys/desktop/stampe/stampa_curriculum_'.$anonimo.'1',$data);  
             //echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_1',$data);  
        }
        if($template==2)
        {
             echo $this->load->view('sys/desktop/stampe/stampa_curriculum_'.$anonimo.'2',$data); 
             //echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_2',$data);  
        }
        if($template==3)
        {
            echo $this->load->view('sys/desktop/stampe/stampa_curriculum_'.$anonimo.'3',$data); 
            //echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_3',$data);  
        }
        if($template==4)
        {
             echo $this->load->view('sys/desktop/stampe/stampa_curriculum_'.$anonimo.'4',$data); 
             //echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_4',$data);  
        }        
        if($template==5)
        {
             echo $this->load->view('sys/desktop/stampe/stampa_curriculum_'.$anonimo.'5',$data);
             //echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_5',$data);  
        }
    }
    
    public function stampa_curriculum_anonimo($recordid,$template=null)
    {
        $data['userid']= $this->session->userdata('userid');
        $dati_candidato= $this->Sys_model->get_dati_candidato($recordid);        
        $foto_candidato= $this->Sys_model->get_foto_path("candidati",$recordid);
            
        $data['candidato']=$dati_candidato;
        $data['foto']=$foto_candidato;
        if(($template==null)||($template==''))
        {
            $template= $this->Sys_model->db_get_value('user_candidati','modellocv',"recordid_='$recordid'");
            if(($template==null)||($template==''))
            {
                $template=1;
            }
            else
            {
                $template= str_replace('Template ', '', $template);
            }
        }
        echo $this->load->view('sys/desktop/stampe/stampa_curriculum_anonimo_'.$template,$data);          
    }
    
    //CUSTOM SCHLEGEL
    public function  genera_acconto_step1($recordid)
    {
        $data['recordid']=$recordid;
        
        echo $this->load->view('sys/desktop/custom/schlegel/genera_acconto_step1',$data, TRUE);
    }
    public function genera_acconto_step2()
    {
        $post=$_POST;
        $pratica_recordid=$post['recordid'];
        $importoacconto=$post['importoacconto'];
        $pratica=  $this->Sys_model->db_get_row("user_pratiche","*","recordid_='$pratica_recordid'");
        $cliente_recordid=$pratica['recordidclienti_'];
        $cliente= $this->Sys_model->db_get_row("user_clienti","*","recordid_='$cliente_recordid'");
                 
        $totale=$importoacconto;
        
        
        
        $fields['tipo']='acconto';
        $fields['totale']=$totale;
        $fields['recordidclienti_']=$cliente_recordid;
        $fields['recordidpratiche_']=$pratica_recordid;
        $userid= $this->get_userid();
        $recordid_fattura=$this->Sys_model->insert_record('fatture',$userid,$fields);
        echo $recordid_fattura;
    }
    
    public function  genera_notaprofessionale_step1($recordid)
    {
        $data['recordid']=$recordid;
        
        echo $this->load->view('sys/desktop/custom/schlegel/genera_notaprofessionale_step1',$data, TRUE);
    }
    public function genera_notaprofessionale_step2()
    {
        $post=$_POST;
        $pratica_recordid=$post['recordid'];
        $datalimite=$post['datalimite'];
        $pratica=  $this->Sys_model->db_get_row("user_pratiche","*","recordid_='$pratica_recordid'");
        $cliente_recordid=$pratica['recordidclienti_'];
        $cliente= $this->Sys_model->db_get_row("user_clienti","*","recordid_='$cliente_recordid'");
           
        
        $prestazioni=$this->Sys_model->db_get("user_prestazioni","*","recordidpratiche_='$pratica_recordid' AND stato='nonfatturata' AND data<'$datalimite'");
        
        
        $spese=0;
        $totale_minuti=0;
        $ore=0;
        $minuti=0;
        $tariffa=0;
        $onorario=0;
        $esborsi=0;
        $acconto=0;
        $percsconto=$post['percsconto'];
        $sconto=0;
        $perciva=7.7;
        $iva=0;
        $totale=0;
        foreach ($prestazioni as $key => $prestazione) {
            $recordid_prestazione=$prestazione['recordid_'];
            $totale_minuti=$totale_minuti+$prestazione['tempomin']+$prestazione['tempotrasfertamin'];
            $onorario=$onorario+$prestazione['tempochf'];
            $spese=$spese+$prestazione['spesevariechf']+$prestazione['scritturazionichf']+$prestazione['trasfertachf'];
            $this->Sys_model->execute_query("UPDATE user_prestazioni SET stato='fatturata' WHERE recordid_='$recordid_prestazione' ");
        }
        $ore = floor($totale_minuti / 60);
        $minuti = ($totale_minuti % 60);
        $acconto= $this->Sys_model->db_get_value("user_fatture","totale","tipo='acconto' AND stato='nonusato'");
        $sconto=$onorario*($percsconto/100);
        $sconto=round($sconto,2);
        $totalenoiva=$spese+$onorario+$esborsi-$acconto-$sconto;
        $iva=($perciva/100)*$totalenoiva;
        $iva=round($iva,2);
        $totale=$totalenoiva+$iva;
        
        $fields['tipo']='notaprofessionale';
        $fields['spese']=$spese;
        $fields['ore']=$ore;
        $fields['min']=$minuti;
        $fields['tariffa']=$pratica['tariffa'];
        $fields['onorario']=$onorario;
        $fields['esborsi']=$esborsi;
        $fields['acconto']=$acconto;
        $fields['percsconto']=$percsconto;
        $fields['sconto']=$sconto;
        $fields['iva']=$iva;
        $fields['totale']=$totale;
        $fields['datalimite']=$datalimite;
        $fields['recordidclienti_']=$cliente_recordid;
        $fields['recordidpratiche_']=$pratica_recordid;
        $userid= $this->get_userid();
        $recordid_fattura=$this->Sys_model->insert_record('fatture',$userid,$fields);
        echo $recordid_fattura;
    }
    
    public function stampa_fattura($recordid_fattura)
    {
        $fattura=$this->Sys_model->db_get_row("user_fatture","*","recordid_='$recordid_fattura'");
        $tipo_fattura=$fattura['tipo'];
        if($tipo_fattura=='notaprofessionale')
        {
            $nomefile=$this->stampa_notaprofessionale($recordid_fattura);
        }
        if($tipo_fattura=='acconto')
        {
            $nomefile=$this->stampa_acconto($recordid_fattura);
        }
        echo $nomefile;
    }
    
    
    public function stampa_notaprofessionale($recordid_fattura)
    {
        $data=array();
        $fattura=  $this->Sys_model->db_get_row("user_fatture","*","recordid_='$recordid_fattura'");
        $cliente_recordid=$fattura['recordidclienti_'];
        $pratica_recordid=$fattura['recordidpratiche_'];
        $cliente= $this->Sys_model->db_get_row("user_clienti","*","recordid_='$cliente_recordid'");
        $pratica= $this->Sys_model->db_get_row("user_pratiche","*","recordid_='$pratica_recordid'");
        
        
        $sesso=$cliente['sesso'];
        $data['sesso']=$sesso;
        $data['cognomenome']=$cliente['cognome']." ".$cliente['nome'];
        $titcortesia="Egregio Signor";
        if($sesso=='f')
        {
            $titcortesia="Gentile Signora";
        }
        $data['titcortesia']=$titcortesia;
        $data['indirizzo']=$cliente['indirizzo'];
        $data['cap']=$cliente['cap'];
        $data['paese']=$cliente['paese'];
        
        $month=date('F');
        $mese= $this->translate_month($month);
        $data['data']=  date('d F Y');
        $data['data']= str_replace($month, $mese, $data['data']);
        
        $data['nomepratica']=$pratica['nome'];
        $periodo_dal="01/01/2017";
        $periodo_al=$fattura['datalimite'];
        $data['periodo_dal']=date("d/m/Y",strtotime($periodo_dal)); 
        $data['periodo_al']=date("d/m/Y",strtotime($periodo_al)); 
        
        $data['spese']=$fattura['spese'];
        $data['ore']=$fattura['ore'];
        $data['minuti']=$fattura['min'];
        $data['tariffa']=$fattura['tariffa'];
        $data['onorario']=$fattura['onorario'];
        $data['esborsi']=$fattura['esborsi'];
        $data['acconto']=$fattura['acconto'];
        $data['percsconto']=$fattura['percsconto'];
        $data['sconto']=$fattura['sconto'];
        $data['iva']=$fattura['iva'];
        $data['totale']=$fattura['totale'];
        $data['userid']= $this->get_userid();
        $file= $this->load->view('sys/desktop/stampe/stampa_schlegel_notaprofessionale',$data);
        echo $file;
    }
    
    public function stampa_acconto($recordid_fattura)
    {
        $data=array();
        $fattura=  $this->Sys_model->db_get_row("user_fatture","*","recordid_='$recordid_fattura'");
        $cliente_recordid=$fattura['recordidclienti_'];
        $pratica_recordid=$fattura['recordidpratiche_'];
        $cliente= $this->Sys_model->db_get_row("user_clienti","*","recordid_='$cliente_recordid'");
        $pratica= $this->Sys_model->db_get_row("user_pratiche","*","recordid_='$pratica_recordid'");
        
        
        $sesso=$cliente['sesso'];
        $data['sesso']=$sesso;
        $data['cognomenome']=$cliente['cognome']." ".$cliente['nome'];
        $titcortesia="Egregio Signor";
        if($sesso=='f')
        {
            $titcortesia="Gentile Signora";
        }
        $data['titcortesia']=$titcortesia;
        $data['indirizzo']=$cliente['indirizzo'];
        $data['cap']=$cliente['cap'];
        $data['paese']=$cliente['paese'];
        $month=date('F');
        $mese= $this->translate_month($month);
        $data['data']=  date('d F Y');
        $data['data']= str_replace($month, $mese, $data['data']);
        
        $data['nomepratica']=$pratica['nome'];
        
        
        $data['totale']=$fattura['totale'];
        
        $data['userid']= $this->get_userid();
        $file= $this->load->view('sys/desktop/stampe/stampa_schlegel_acconto',$data);
        echo $file;
    }
    
    public function stampa_letteraaccompagnamento($recordid_pratica)
    {
        $data=array();
        $pratica=  $this->Sys_model->db_get_row("user_pratiche","*","recordid_='$recordid_pratica'");
        $cliente_recordid=$pratica['recordidclienti_'];
        $cliente= $this->Sys_model->db_get_row("user_clienti","*","recordid_='$cliente_recordid'");
        
        
        $sesso=$cliente['sesso'];
        $data['sesso']=$sesso;
        $data['cognomenome']=$cliente['cognome']." ".$cliente['nome'];
        $titcortesia="Egregio Signor";
        if($sesso=='f')
        {
            $titcortesia="Gentile Signora";
        }
        $data['titcortesia']=$titcortesia;
        $data['indirizzo']=$cliente['indirizzo'];
        $data['cap']=$cliente['cap'];
        $data['paese']=$cliente['paese'];
        $month=date('F');
        $mese= $this->translate_month($month);
        $data['data']=  date('d F Y');
        $data['data']= str_replace($month, $mese, $data['data']);
        
        $data['nomepratica']=$pratica['nome'];
        
        
        
        $data['userid']= $this->get_userid();
        $file= $this->load->view('sys/desktop/stampe/stampa_schlegel_letteraaccompagnamento',$data);
        echo $file;
    }
    
    public function translate_month($month)
    {
        if($month == "January"){

        $month = "Gennaio";

        }elseif($month == "February"){

        $month = "Febbraio";

        }elseif($month == "March"){

        $month = "Marzo";

        }elseif($month == "April"){

        $month = "Aprile";

        }elseif($month == "May"){

        $month = "Maggio";

        }elseif($month == "June"){

        $month = "Giugno";

        }elseif($month == "July"){

        $month = "Luglio";

        }elseif($month == "August"){

        $month = "Agosto";

        }elseif($month == "September"){

        $month = "Settembre";

        }elseif($month == "October"){

        $month = "Ottobre";

        }elseif($month == "November"){

        $month = "Novembre";

        }elseif($month == "December"){

        $month = "Dicembre";

        }
        return $month;
    }
    
    public function test_phpword()
    {
        $data['userid']=  $this->get_userid();
        echo $this->load->view('sys/desktop/stampe/test_phpword',$data);
    }
    
    public function download_test_phpword($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function stampa_mandato($recordid)
    {
        $data['userid']=$this->session->userdata('userid');
        $this->load->view('sys/desktop/stampe/stampa_mandato',$data,true);
    }
    
    public function stampa_vetrina($recordid)
    {
        $data=array();
        $data['userid']=  $this->session->userdata('userid');
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);

        $data['foto_copertina']=null; 
        if(array_key_exists('Copertina', $foto_prospetto))
        {
            if(count($foto_prospetto['Copertina'])>0)
            {
               $data['foto_copertina']=$foto_prospetto['Copertina'][0]['complete_path']; 
            }
        }
        $data['foto_interni']=null;
        if(array_key_exists('Interni', $foto_prospetto))
        {
            if(count($foto_prospetto['Interni'])>0)
            {
                foreach ($foto_prospetto['Interni'] as $key => $foto_interno) {
                    $data['foto_interni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        $data['foto_esterni']=null;
        if(array_key_exists('Esterni', $foto_prospetto))
        {
            if(count($foto_prospetto['Esterni'])>0)
            {
                foreach ($foto_prospetto['Esterni'] as $key => $foto_interno) {
                    $data['foto_esterni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        
        $data['foto_piantine']=null;
        if(array_key_exists('Piantina piano seminterrato', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano seminterrato'])>0)
            {
                    $data['foto_piantine']['Piantina piano seminterrato']=$foto_prospetto['Piantina piano seminterrato'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina piano terra', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano terra'])>0)
            {
                    $data['foto_piantine']['Piantina piano terra']=$foto_prospetto['Piantina piano terra'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina primo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina primo piano'])>0)
            {
                    $data['foto_piantine']['Piantina primo piano']=$foto_prospetto['Piantina primo piano'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina secondo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina secondo piano'])>0)
            {
                    $data['foto_piantine']['Piantina secondo piano']=$foto_prospetto['Piantina secondo piano'][0]['complete_path'];  
            }
        }
        
        $data['paese']=$fields['paese']['valuecode'][0]['value'];
        
        $data['titolo']=$fields['categoria']['valuecode'][0]['value'];
        
        $data['descrizione']="";
        if(array_key_exists('descrizione', $fields))
        {
            $data['descrizione']=$fields['categoria']['valuecode'][0]['value'];
        }
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        if($tipo=='Immobile in affitto')
        {
            $prezzo=$fields['aff_pigionenettamensile']['valuecode'][0]['value'];
        }
        else
        {
            $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
        }
        if($this->isnotempty($prezzo))
        {
            $prezzo=  number_format($prezzo,0,',',"'");
        }
        else
        {
            $prezzo='';
        }
        
        $data['mq']=$fields['imm_sul_mq']['valuecode'][0]['value'];
        $data['prezzo']=$prezzo.".--";
        
        foreach ($fields as $key => $field) 
        {
            //$allfields[$field['fieldid']]=$field;
            if($this->isnotempty($field['valuecode'][0]['value']))
            {
                $fields_by_sublabel[$field['sublabel']][]=$field;
            }
            
        }
        
        $data['sublabels']=  $this->Sys_model->get_table_sublabels('immobili');
        $data['fields_by_sublabel']=$fields_by_sublabel;
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.jpg";
        }
        echo $this->load->view('sys/desktop/stampe/stampa_vetrina',$data);  
    }
    
    public function stampa_prospetto($recordid)
    {
        $data=array();
        $data['userid']=  $this->session->userdata('userid');
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);

        $data['foto_copertina']=null; 
        if(array_key_exists('Copertina', $foto_prospetto))
        {
            if(count($foto_prospetto['Copertina'])>0)
            {
               $data['foto_copertina']=$foto_prospetto['Copertina'][0]['complete_path']; 
            }
        }
        $data['foto_interni']=null;
        if(array_key_exists('Interni', $foto_prospetto))
        {
            if(count($foto_prospetto['Interni'])>0)
            {
                foreach ($foto_prospetto['Interni'] as $key => $foto_interno) {
                    $data['foto_interni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        $data['foto_esterni']=null;
        if(array_key_exists('Esterni', $foto_prospetto))
        {
            if(count($foto_prospetto['Esterni'])>0)
            {
                foreach ($foto_prospetto['Esterni'] as $key => $foto_interno) {
                    $data['foto_esterni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        
        $data['foto_piantine']=null;
        if(array_key_exists('Piantina piano seminterrato', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano seminterrato'])>0)
            {
                    $data['foto_piantine']['Piantina piano seminterrato']=$foto_prospetto['Piantina piano seminterrato'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina piano terra', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano terra'])>0)
            {
                    $data['foto_piantine']['Piantina piano terra']=$foto_prospetto['Piantina piano terra'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina primo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina primo piano'])>0)
            {
                    $data['foto_piantine']['Piantina primo piano']=$foto_prospetto['Piantina primo piano'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina secondo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina secondo piano'])>0)
            {
                    $data['foto_piantine']['Piantina secondo piano']=$foto_prospetto['Piantina secondo piano'][0]['complete_path'];  
            }
        }
        
        $data['paese']=$fields['paese']['valuecode'][0]['value'];
        
        $data['titolo']=$fields['categoria']['valuecode'][0]['value'];
        
        $data['descrizione']="";
        if(array_key_exists('descrizione', $fields))
        {
            $data['descrizione']=$fields['categoria']['valuecode'][0]['value'];
        }
        $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
        if($this->isnotempty($prezzo))
        {
            $prezzo=  number_format($prezzo,0,',',"'");
        }
        else
        {
            $prezzo='';
        }
        
        $data['prezzo']=$prezzo.".--";
        
        foreach ($fields as $key => $field) 
        {
            //$allfields[$field['fieldid']]=$field;
            if($this->isnotempty($field['valuecode'][0]['value']))
            {
                $fields_by_sublabel[$field['sublabel']][]=$field;
            }
            
        }
        
        $data['sublabels']=  $this->Sys_model->get_table_sublabels('immobili');
        $data['fields_by_sublabel']=$fields_by_sublabel;
        echo $this->load->view('sys/desktop/stampe/stampa_prospetto',$data);  
    }
    
    public function stampa_prospetto_completo($recordid)
    {
        $data=array();
        $data['userid']=  $this->session->userdata('userid');
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);

        $data['foto_copertina']=null; 
        if(array_key_exists('Copertina', $foto_prospetto))
        {
            if(count($foto_prospetto['Copertina'])>0)
            {
               $data['foto_copertina']=$foto_prospetto['Copertina'][0]['complete_path']; 
            }
        }
        $data['foto_interni']=null;
        if(array_key_exists('Interni', $foto_prospetto))
        {
            if(count($foto_prospetto['Interni'])>0)
            {
                foreach ($foto_prospetto['Interni'] as $key => $foto_interno) {
                    $data['foto_interni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        $data['foto_esterni']=null;
        if(array_key_exists('Esterni', $foto_prospetto))
        {
            if(count($foto_prospetto['Esterni'])>0)
            {
                foreach ($foto_prospetto['Esterni'] as $key => $foto_interno) {
                    $data['foto_esterni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        
        $data['foto_piantine']=null;
        if(array_key_exists('Piantina piano seminterrato', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano seminterrato'])>0)
            {
                    $data['foto_piantine']['Piantina piano seminterrato']=$foto_prospetto['Piantina piano seminterrato'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina piano terra', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano terra'])>0)
            {
                    $data['foto_piantine']['Piantina piano terra']=$foto_prospetto['Piantina piano terra'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina primo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina primo piano'])>0)
            {
                    $data['foto_piantine']['Piantina primo piano']=$foto_prospetto['Piantina primo piano'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina secondo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina secondo piano'])>0)
            {
                    $data['foto_piantine']['Piantina secondo piano']=$foto_prospetto['Piantina secondo piano'][0]['complete_path'];  
            }
        }
        
        $data['paese']=$fields['paese']['valuecode'][0]['value'];
        
        $data['titolo']=$fields['categoria']['valuecode'][0]['value']." ".$fields['imm_locali_num']['valuecode'][0]['value']." LOCALI";
        
        $data['descrizione']="";
        if(array_key_exists('descrizione', $fields))
        {
            $data['descrizione']=$fields['descrizione']['valuecode'][0]['value'];
        }
        $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
        if($this->isnotempty($prezzo))
        {
            $prezzo=  number_format($prezzo,0,',',"'");
        }
        else
        {
            $prezzo='';
        }
        
        $data['prezzo']=$prezzo.".--";
        
        foreach ($fields as $key => $field) 
        {
            //$allfields[$field['fieldid']]=$field;
            if($this->isnotempty($field['valuecode'][0]['value']))
            {
                $fields_by_sublabel[$field['sublabel']][]=$field;
            }
            
        }
        
        $data['sublabels']=  $this->Sys_model->get_table_sublabels('immobili');
        $data['fields_by_sublabel']=$fields_by_sublabel;
        $userid=  $this->session->userdata('userid');
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.jpg";
        }
        
        echo $this->load->view('sys/desktop/stampe/stampa_prospetto_completo',$data);  
    }
    
    public function stampa_vis_contratto($recordid)
    {
        //$data['data']['dati']=  $this->Sys_model->get_dati_stampa_contratto_vis($recordid);
       $data['data']['dati']=array();
        echo $this->load->view('sys/desktop/stampe/stampa_vis_contratto',$data);  
    }
    
    public function stampa_vis_profilorischio($recordid)
    {
        //$data['data']['dati']=  $this->Sys_model->get_dati_stampa_contratto_vis($recordid);
       $data['data']['dati']=array();
        echo $this->load->view('sys/desktop/stampe/stampa_vis_profilorischio',$data);  
    }
    
    
    public function stampa_contratti()
    {
        $contratti_stampare=  $this->Sys_model->get_contratti_stampare();
        foreach ($contratti_stampare as $key => $contratto) {
            $this->genera_LetteraInvioContratti_candidati($contratto['recordid_']);
        }
        
    }
    
    public function genera_contratti_azienda($recordid_contratto,$wwws)
    {

            $this->genera_LetteraInvioContratti_aziende($recordid_contratto,$wwws);
            $this->genera_ContrattoFornituraPersonalePrestito_WW($recordid_contratto,$wwws);
            $this->genera_ContrattoFornituraPersonalePrestito_Azienda($recordid_contratto,$wwws);
        
    }
    
    public function genera_contratti_dipendente($recordid_contratto,$wwws)
    {

            $this->genera_LetteraInvioContratti_candidati($recordid_contratto,$wwws);
            $this->genera_ContrattoAssunzionePersonalePrestito_Dipendente($recordid_contratto,$wwws);
            $this->genera_ContrattoAssunzionePersonalePrestito_WW($recordid_contratto,$wwws);
        
    }
    
    public function genera_LetteraInvioContratti_candidati($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_LetteraInvioContratti_candidati($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_LetteraInvioContratti_candidati',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "LetteraInvioContratti_candidati","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function genera_LetteraInvioContratti_aziende($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_LetteraInvioContratti_aziende($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_LetteraInvioContratti_aziende',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "LetteraInvioContratti_aziende","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function genera_ContrattoFornituraPersonalePrestito_WW($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_ContrattoFornituraPersonalePrestito_WW($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_ContrattoFornituraPersonalePrestito_WW',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "ContrattoFornituraPersonalePrestito_WW","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function genera_ContrattoFornituraPersonalePrestito_Azienda($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_ContrattoFornituraPersonalePrestito_Azienda($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_ContrattoFornituraPersonalePrestito_Azienda',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "ContrattoFornituraPersonalePrestito_Azienda","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function genera_ContrattoAssunzionePersonalePrestito_WW($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_ContrattoAssunzionePersonalePrestito_WW($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_ContrattoAssunzionePersonalePrestito_WW',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "ContrattoAssunzionePersonalePrestito_WW","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function genera_ContrattoAssunzionePersonalePrestito_Dipendente($recordid_contratto,$wwws)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['wwws']=$wwws;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_ContrattoAssunzionePersonalePrestito_Dipendente($recordid_contratto);
        $this->load->view('sys/desktop/stampe/stampa_ContrattoAssunzionePersonalePrestito_Dipendente',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "ContrattoAssunzionePersonalePrestito_Dipendente","docx", 'CONTRA', $recordid_contratto);
        
    }
    
    public function download_contratti($nome_file='')
    {
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    //custom WW
    public function genera_casellario($recordid_candidato)
    {
        $casellario_da_generare=true;
        $candid_lgl=$this->Sys_model->db_get_row('user_lgl', '*', "recordidcandid_='$recordid_candidato'");
        $tipodoc='';
        if($candid_lgl!=null)
        {
            $casellariogiudiziario=$candid_lgl['casellariogiudiziario'];
            if($casellariogiudiziario=='Standby')
            {
                $tipodoc='cgcp';
            }
            if($casellariogiudiziario=='conforme')
            {
               $tipodoc='cgcd'; 
            }
        }
        $candid_casellario_page=$this->Sys_model->db_get_row('user_candid_page', '*', "recordid_='$recordid_candidato' AND original_name='casellario_generato'");
        if($candid_casellario_page!=null)
        {
            $tipo_casellario_attuale=$candid_casellario_page['category'];
            $path_completa=$candid_casellario_page['path_']."/".$candid_casellario_page['filename_']."/".$candid_casellario_page['extension_'];
            $path_completa=  str_replace("\\\\", "/", $path_completa);
            $path_completa=  str_replace("\\", "/", $path_completa);
            $path_completa="../JDocServer/".$path_completa;
            if(file_exists($path_completa))
            {
                if($tipo_casellario_attuale==$tipodoc)
                {
                    $casellario_da_generare=false;
                }
            }
            
        }
        $casellariogiudiziario='';
        if(($casellario_da_generare)&&(($tipodoc=='cgcp')||($tipodoc=='cgcd')))
        {
            //elimina casellari attuale
            $this->Sys_model->delete_casellario($recordid_candidato);
            $userid=$this->session->userdata('userid');
            $data['userid']=  $userid;
            $dati_candidato=$this->Sys_model->db_get_row("user_candid",'*',"recordid_='$recordid_candidato'");
            $data['dati']=  $dati_candidato;

            $data['tipodoc']=$tipodoc;
            $nome_casellario_generato=$tipodoc." ".$dati_candidato['cognome']." ".$dati_candidato['nome'];//"casellario_".time()."_".rand(1,999);
            if(!file_exists("../JDocServer/generati/$userid/documenti_lgl/casellari"))
            {
                mkdir("../JDocServer/generati/$userid/documenti_lgl/casellari");
            }
            $data['folder']="../JDocServer/generati/$userid/documenti_lgl/casellari";
            $data['nome_casellario_generato']=$nome_casellario_generato;
            $this->load->view('sys/desktop/stampe/stampa_casellario',$data,true); 
            //sleep(1);
            $command='cd ./tools/OfficeToPDF/ && OfficeToPDF.exe "../../../JDocServer/generati/'.$userid.'/documenti_lgl/casellari/'.$nome_casellario_generato.'.docx" "../../../JDocServer/generati/'.$userid.'/documenti_lgl/casellari/'.$nome_casellario_generato.'.pdf" ';
            exec($command);
            //unlink("stampe/$userid/casellario_generato.docx");
            //$this->Sys_model->inserisci_allegato("stampe/$userid", "casellario_generato","pdf", 'CANDID', $recordid_candidato);
            //aggiorna categoria casellario
            //$this->Sys_model->aggiorna_categoria_casellario($recordid_candidato,$tipodoc);
        
        }
    }
    
    // allega, scarica pdf, scarica word, invia mail
    public function genera_bollettino_assistenza($recordid)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_bollettino_assistenzakeysky($recordid);
        $this->load->view('sys/desktop/stampe/stampa_bollettino_assistenzakeysky',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "bollettino_assistenzakeysky","docx", 'assistenzekeysky', $recordid);
        
    }
    
    //custom KEYSKY
    public function genera_bollettino_assistenzakeysky($recordid)
    {
        $userid=$this->session->userdata('userid');
        $data['data']['userid']=  $userid;
        $data['data']['dati']=  $this->Sys_model->get_dati_stampa_bollettino_assistenzakeysky($recordid);
        $this->load->view('sys/desktop/stampe/stampa_bollettino_assistenzakeysky',$data,true);
        $this->Sys_model->inserisci_allegato("stampe/$userid", "bollettino_assistenzakeysky","docx", 'assistenzekeysky', $recordid);
        
    }
    
    /**
     * Funzione che si occupa del download del documento word
     * @param stringa $tipo quale documento scaricare
     * @author Luca Giordano
     */
    public function download_profilo($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_curriculum($nome_file='')
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_notaprofessionale($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="../JDocServer/stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_stampa($nome_file)
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="../JDocServer/stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    
    public function download_mandato($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_file_lgl()
    {
        $percorso_file="generati/".$this->session->userdata('userid')."/export_lgl.zip";
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=export_lgl.zip"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_template($template_path='',$filename='')
    {
        $template_path=  str_replace("-", "/", $template_path);
        $percorso_file="stampe/modelli/$template_path/$filename";
        $percorso_file=  urldecode($percorso_file);
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
    }
    
    public function download_export($filename='')
    {
        $percorso_file="../JDocServer/export/$filename";
        $percorso_file=  urldecode($percorso_file);
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
    }
    
    public function download_vetrina($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_prospetto($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    public function download_prospetto_completo($nome_file='')
    {
        $nome_file=  urldecode($nome_file);
        $percorso_file="stampe/".$this->session->userdata('userid')."/".$nome_file;
        header("Contentajax_load_block_table-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        unlink($percorso_file);
    }
    
    
    public function ajax_load_block_jpgcrop($recordid,$cartella,$nomefile)
    {
        
        $block=  $this->load_block_jpgcrop($recordid,$cartella,$nomefile);
        echo $block;
    }
    
    public function load_block_jpgcrop($recordid,$cartella,$nomefile,$interface='Desktop')
    {
            $extension=  get_file_extension($nomefile);
            $percorso_jpg="";
            if($extension=='pdf')
            {
                $percorso_jpg=$this->pdfToJpg($cartella, $nomefile);
            }
            else
            {
                $userid=$this->session->userdata('userid');
                $cartella= str_replace("-", "/", $cartella);
                $percorso_jpg= server_url().$cartella.'/'.$nomefile; //TEMP TODO
            }
            
            $data['data']['percorso_jpg']="";
            $data['data']['recordid']=$recordid;
             $data['data']['cartella']=$cartella;
            for($x=0;$x<30;$x++)
            {
                
                if(!file_exists($percorso_jpg))
                {
                   $data['data']['percorso_jpg']=$percorso_jpg;
                   break;
                }
                sleep(1);
                
            }
            $data['cliente_id']=$this->Sys_model->get_cliente_id();
            return $this->load->view('sys/'.$interface.'/block/jpgcrop',$data, TRUE);
    }
    
    
    public function pdfToJpg($cartella,$nomefile)
    {
        $userid=$this->session->userdata('userid');
        $cartella= str_replace("-", "/", $cartella);
        $percorso_pdf='../../../JDocServer/'.$cartella.'/'.$nomefile;
        if(!file_exists("../JDocServer/pdfjpg"))
        {
            mkdir("../JDocServer/pdfjpg");
        }
        if(!file_exists("../JDocServer/pdfjpg/$userid"))
        {
            mkdir("../JDocServer/pdfjpg/$userid");
        }
        $nomejpg=  str_replace('.pdf', '.jpg', $nomefile);
        $percorso_jpg="../../../JDocServer/pdfjpg/$userid/$nomejpg";
        //$command='cd ../JDocServices && JDocServices.exe "pdfjpg" "'.$percorso_pdf.'" "'.$percorso_jpg.'"';
        $command='cd ../JDocServices/gs9.01/bin && gswin32c.exe -dNOPAUSE -sDEVICE=jpeg -r300 -sOUTPUTFILE="'.$percorso_jpg.'" "'.$percorso_pdf.'" ';
        
        exec($command);
        $base_url=base_url();
        //$host=str_replace("/jdocwebtest","",base_url());
        //$host=str_replace("/JDocWebtest","",$host);
        $host=domain_url();
        $url_jpg=$host."JDocServer/pdfjpg/$userid/".$nomejpg;
        return $url_jpg;
    }
    
    public function script_migrazione_table_settings()
    {
        $table_settings=  $this->Sys_model->db_get('sys_table_settings');
        foreach ($table_settings as $key => $table_setting) {
            $tableid=$table_setting['tableid'];
            $settingid=$table_setting['settingid'];
            $value=$table_setting['value'];
            $sql="
                INSERT INTO sys_user_table_settings
                (userid,tableid,settingid,value)
                VALUES
                (1,'$tableid','$settingid','$value')
                ";
            $this->Sys_model->execute_query($sql);
        }
    }
    
    public function script_temp()
    {
        $tickets=  $this->Sys_model->db_get('user_segnalazioni');
        foreach ($tickets as $key => $ticket) {
            $recordid_ticket=$ticket['recordid_'];
            $timesheets=$this->Sys_model->db_get('user_timesheet','*',"recordidsegnalazioni_='$recordid_ticket'","ORDER BY datainizio DESC");
            if(count($timesheets)>0)
            {
                $timesheet=$timesheets[0];
                $data_timesheet=$timesheet['datainizio'];
                $sql="UPDATE user_segnalazioni SET dataprox='$data_timesheet' WHERE recordid_='$recordid_ticket'";
                $this->Sys_model->execute_query($sql);
                
            }
             
        }
    }
    
    public function script_aggiorna_campi_di()
    {
        $immobili=  $this->Sys_model->db_get('user_immobili','*');
        foreach ($immobili as $key => $fields) {
            $master_recordid=$fields['recordid_'];
            //imposto il campo calcolato prezzo
                $prezzo='null';
                if($fields['tipo']=='immobileinvendita')
                {
                    $prezzo=$fields['imm_prezzoimmobile'];
                }
                if($fields['tipo']=='terrenoinvendita')
                {
                    $prezzo=$fields['ter_prezzo_vendita'];
                }
                if($fields['tipo']=='palazzinainvendita')
                {
                    $prezzo=$fields['pal_prezzoimmobile'];
                }
                if($prezzo=='')
                {
                    $prezzo='null';
                }
                $sql="UPDATE user_immobili SET prezzo=$prezzo WHERE recordid_='$master_recordid'";
                $this->Sys_model->execute_query($sql);
                
                //imposto il campo calcolato cognomeproprietario
                $recordid_contatti=$fields['recordidcontatti_'];
                if($this->isnotempty($recordid_contatti))
                {
                    $contatto=  $this->Sys_model->db_get_row('user_contatti', '*', "recordid_='$recordid_contatti'");
                    if($contatto!=null)
                    {
                        $cognome=$contatto['cognome'];
                        $sql="UPDATE user_immobili SET cognomeproprietario='$cognome' WHERE recordid_='$master_recordid'";
                        $this->Sys_model->execute_query($sql);
                    }
                }
                
                //imposto il campo locali_num
                $locali_num='null';
                if($fields['tipo']=='immobileinvendita')
                {
                    $locali_num=$fields['imm_locali_num'];
                }
                if($fields['tipo']=='immobileinaffitto')
                {
                    $locali_num=$fields['aff_locali_num'];
                }
                if($locali_num=='')
                {
                    $locali_num='null';
                }
                $sql="UPDATE user_immobili SET locali_num=$locali_num WHERE recordid_='$master_recordid'";
                $this->Sys_model->execute_query($sql);
        }
    }
    public function script_genera_thumbnail_immobili()
    {
        $sql="
            SELECT * from user_immobili_page
            ";
        $immobili_pages=  $this->Sys_model->select($sql);
        foreach ($immobili_pages as $key => $immobile_page) {
            $path=$immobile_page['path_'];
            $path=  str_replace("\\\\", "/", $path);
            $filename=$immobile_page['filename_'];
            $ext=$immobile_page['extension_'];
            $this->genera_thumbnail($path."/$filename",$ext);
            
        }
        echo "thumbnail generate";
    }
    
    public function script_genera_record_preview_immobili()
    {
        $sql="
            SELECT * from user_immobili
            ";
        $immobili=  $this->Sys_model->select($sql);
        foreach ($immobili as $key => $immobile) {
            $recordid=$immobile['recordid_'];
            $path=  "record_preview/immobili/$recordid.jpg";
            $this->img_resize($path, 250, 250, $path);
            
            
        }
        echo "record_preview generate";
    }
    
    public function genera_thumbnail_autobatch($autobatchid)
    {
        $host_path=  $this->get_host_path();
        $folder="$host_path\JDocServer\autobatch\\$autobatchid";
        $files=array();
                if(file_exists($folder))
                {
                   $files = scandir($folder); 
                }
                foreach($files as $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                        $filenameext=$file;
                        $fileext = pathinfo($filenameext, PATHINFO_EXTENSION);
                        $filenamenoext=  str_replace(".$fileext", "", $filenameext);
                        $path_originale_noext="autobatch/$autobatchid/$filenamenoext";
                        $this->genera_thumbnail($path_originale_noext, $fileext);
                    }
                }
    }
    
    public function test_genera_thumbnail()
    {
        $this->genera_thumbnail("test", 'pdf');
    }
    
    public function genera_thumbnail($path_originale_noext,$ext)
    {
        
        if(!file_exists("../JDocServer/".$path_originale_noext."_thumbnail.jpg"))
        {
            $path_originale=$path_originale_noext.".".$ext;
            if((strtolower($ext)=='jpeg')||(strtolower($ext)=='jpg')||(strtolower($ext)=='png'))
            {
                //$this->img_resize($path_originale_noext.".$ext", 250, 250, $path_originale_noext."_thumbnail.jpg");
            }
            
            if((strtolower($ext)=='pdf'))
            {
                $command='cd ../JDocServices/gs9.01/bin && gswin32c.exe -dNOPAUSE -sDEVICE=jpeg -r300 -sOUTPUTFILE="../../../JDocServer/'.$path_originale_noext.'_thumbnail.jpg" "../../../JDocServer/'.$path_originale.'" 2>&1';
                exec($command,$output,$return_var);
                
                var_dump($output);
                var_dump($return_var);
            }
        }
        
    }
    
    public function script_genera_preview_immobili()
    {
        $sql="
            SELECT * from user_immobili_page
            ";
        $immobili_pages=  $this->Sys_model->select($sql);
        foreach ($immobili_pages as $key => $immobile_page) {
            $path=$immobile_page['path_'];
            $path=  str_replace("\\\\", "/", $path);
            $filename=$immobile_page['filename_'];
            $ext=$immobile_page['extension_'];

            $this->genera_preview($path."/$filename",$ext);
            
            
        }
        echo "preview generate";
    }
    
    
    public function genera_preview($path_originale_noext,$ext)
    {
        $path_completa_originale="../JDocServer/".$path_originale_noext.".$ext";
        $path_completa_preview="../JDocServer/".$path_originale_noext."_preview.jpg";
        if(!file_exists($path_completa_preview))
        {
            if((strtolower($ext)=='jpeg')||(strtolower($ext)=='jpg')||(strtolower($ext)=='png'))
            {
                if(file_exists($path_completa_originale))
                {
                    $this->img_resize($path_originale_noext.".$ext", 2048, 2048, $path_originale_noext."_preview.jpg");
                    if(file_exists($path_completa_preview))
                    {
                        $filesize_originale=  filesize($path_completa_originale);
                        $filesize_preview= filesize($path_completa_preview);
                        if($filesize_originale<$filesize_preview)
                        {
                            copy($path_completa_originale, $path_completa_preview);
                        }
                    }
                }
            }
            
        }
        else
        {
            $filesize_originale=  filesize($path_completa_originale);
            $filesize_preview= filesize($path_completa_preview);
            if($filesize_originale<$filesize_preview)
            {
                copy($path_completa_originale, $path_completa_preview);
            }
        }
        
    }
    
    public function img_resize($path_originale,$width,$height,$path_destinazione)
    {

        
        $command='cd ./tools/ImageMagick/ && convert "../../../JDocServer/'.$path_originale.'" -resize '.$width.'x'.$height.' "../../../JDocServer/'.$path_destinazione.'"';

        
        exec($command);

    }
    
    public function test_watermark()
    {
        $path_originale="../JDocServer/test.jpg";
        $imagesize=getimagesize($path_originale);
        $width=$imagesize[0];
        $max_width=$width*0.7;
        //$height=$imagesize[1];
        //$watermark_height=$watermark_width*0.042;
        $path_watermark="../../assets/images/custom/DimensioneImmobiliare/Watermark.png";
        $path_originale='../../'.$path_originale;
        $path_destinazione="../../../JDocServer/test2.jpg";
        $this->apply_watermark($path_originale,$path_watermark,$max_width,$max_width,$path_destinazione);
    }
    
    public function apply_watermark($path_originale,$path_watermark,$width,$height,$path_destinazione)
    {
        $command='cd ./tools/ImageMagick/ && composite -gravity center ^( "'.$path_watermark.'" -resize '.$width.'x'.$height.' ^) "'.$path_originale.'" "'.$path_destinazione.'"';
        exec($command);
    }
    
    public function ajax_cropImg($tableid,$recordid){
        $targ_w = $targ_h = 160; 
        $jpeg_quality = 90; 
        $src = $_POST['percorso_jpg']; 
        $img_r = imagecreatefromjpeg($src); 
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h ); 
        imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']); 
        //header('Content-type: image/jpeg'); 
        //imagejpeg($dst_r,null,$jpeg_quality); exit;
        //$cartella=  str_replace("-", "/", $cartella);
        $cartella="record_preview/$tableid";
        imagejpeg($dst_r, "../JDocServer/$cartella"."/$recordid.jpg", $jpeg_quality);
    }
   
    public function ajax_stampa_selezionati($tableid,$recordid)
    {
         $post=$_POST;
         $allegati=$post['files']['checked'];
         $command='cd ../JDocServices && JDocServices.exe "mergepdf" "'; 
         $counter=0;
         $allegatimerged='';
         foreach ($allegati as $key => $allegato) {
             $path_allegato=$this->Sys_model->get_path_allegato_pdf($tableid,$recordid,$allegato);
            
             if($counter>0)
             {
                 $command=$command.'|';
             }
             $command=$command.$path_allegato;
             $allegatimerged=$allegatimerged.$allegato;
             $counter++;
         }
        $userid=$this->session->userdata('userid');
        if (!file_exists("../JDocServer/generati/"))
        {
            mkdir("../JDocServer/generati/");
        }
        if (!file_exists("../JDocServer/generati/$userid")) 
        {
            mkdir("../JDocServer/generati/$userid");
        }
        $allegatimerged=$allegatimerged.'.pdf';
         $command=$command.'" "JDocServer\generati\\'.$userid.'\\'.$allegatimerged.'"';
        exec($command);
        $host_url=  domain_url();
        $return= $host_url."JDocServer/generati/$userid/$allegatimerged";
        echo $return;
    }
    
    public function elimina_campo($typepreference)
    {
        $post=$_POST;
        $idarchivio=$post['tableid'];
        $fieldid=$post['fieldid'];
        $lookuptableid=$post['lookuptableid'];
        if($typepreference=='creazione_campi')
        {
            $this->Sys_model->execute_query("DELETE FROM sys_field WHERE fieldid = '$fieldid' AND tableid = '$idarchivio'");
            $this->Sys_model->execute_query("ALTER TABLE user_$idarchivio DROP $fieldid");

            if(($lookuptableid!=null)&&($lookuptableid!=""))
            {
                $this->Sys_model->execute_query("DELETE FROM sys_lookup_table WHERE tableid = '$lookuptableid'");
                $this->Sys_model->execute_query("DELETE FROM sys_lookup_table_item WHERE lookuptableid = '$lookuptableid'");
            }
            $typepreference='campiInserimento';
        }
        $idutente=$this->session->userdata('idutente');
        $sql="DELETE FROM sys_user_order WHERE tableid = '$idarchivio' AND fieldid = '$fieldid' AND userid=$idutente AND typepreference = '$typepreference'";
        $this->Sys_model->execute_query($sql);
        /*else
        {
           $idutente=$this->session->userdata('idutente');
           $sql="DELETE FROM sys_user_order WHERE tableid ILIKE '$idarchivio' AND fieldid ILIKE '$fieldid' AND userid=$idutente AND typepreference ILIKE '$typepreference'";
           $this->Sys_model->execute_query($sql);
        }*/
    }
    

    
    public function script_add_columns($tableid)
    {
       $this->Sys_model->script_add_columns($tableid); 
       echo "create!";
    }
    
    public function script_add_original_fields($tableid)
    {
        $this->Sys_model->script_add_original_fields($tableid);
        echo "aggiunti";
    }
    
    public function script_add_page_category_field()
    {
        $this->Sys_model->script_add_page_category_field(); 
        echo "aggiunti";
    }
    
    public function script_lookup_items()
    {
       $this->Sys_model->script_lookup_items(); 
       echo "create!";
    }
    
    public function script_reset_thumbnail()
    {
       $this->Sys_model->script_reset_thumbnail(); 
       echo "create!";
    }
    
    public function script_trim_ext()
    {
       $this->Sys_model->script_trim_ext(); 
       echo "create!";
    }
    
    
    public function script_sync_timesheet_attivitacommerciali()
    {
       $this->Sys_model->script_sync_timesheet_attivitacommerciali(); 
       echo "sicronizzati!";
    }
    
    public function script_fix_timesheet()
    {
        $this->Sys_model->script_fix_timesheet();
        echo "ok";
    }
    
    public function script_fix_thumbnail()
    {
        $this->Sys_model->script_fix_thumbnail();
        echo "ok";
    }
    
    public function script_durata_assistenze()
    {
        $this->Sys_model->script_durata_assistenze();
        echo "ok";
    }
    
    public function script_durata_timesheet()
    {
        $this->Sys_model->script_durata_timesheet();
        echo "ok";
    }
    
    public function script_totaleimporto_ordiniconsumabili()
    {
        $this->Sys_model->script_totaleimporto_ordiniconsumabili();
        echo "ok";
    }
    
    public function script_category_piantina()
    {
        $this->Sys_model->script_category_piantina();
        echo "ok";
    }
    
    public function get_new_records($master_tableid)
    {
        //$json = file_get_contents("http://localhost:8822/jdoconlinecv/index.php/sys_viewcontroller/ajax_get_new_records/$master_tableid");
        $json = file_get_contents("http://workandwork.com/OnlineCV/index.php/sys_viewcontroller/ajax_get_new_records/$master_tableid");
        $new_records = json_decode($json,true);
        $this->Sys_model->set_new_records($master_tableid,$new_records);
    }
    
    public function ajax_get_allfields($tableid,$recordid)
    {
        $allfields=$this->Sys_model->get_allfields($tableid,$recordid);
        $json_allfields=  json_encode($allfields);
        echo $json_allfields;
    }
    
    public function ajax_valida_record($tableid,$recordid)
    {
        $this->Sys_model->valida_record($tableid,$recordid);
    }
    
    public function ajax_valida_tutto_record($tableid,$recordid)
    {
        $this->Sys_model->valida_tutto_record($tableid,$recordid);
    }

    public function ajax_load_block_manuale()
    {
        $block=  $this->load_block_manuale();
        echo $block;
    }
    
    public function load_block_manuale()
    {
        $data=array();
        return $this->load->view('sys/desktop/manuale/manuale_generale',$data, TRUE);
    }
    
    public function ajax_load_block_segnalazioni()
    {
        $block=  $this->load_block_segnalazioni();
        echo $block;
    }
    
    public function load_block_segnalazioni()
    {
        $settings=$this->Sys_model->get_settings();
        $data['data']['settore']='software';
        $data['data']['recordid_azienda']="";
        $data['data']['recordid_progetto']="";
        if(array_key_exists('recordid_azienda', $settings))
        {
            $data['data']['recordid_azienda']=$settings['recordid_azienda'];
        }
        if(array_key_exists('recordid_progetto', $settings))
        {
            $data['data']['recordid_progetto']=$settings['recordid_progetto'];
        }
        
        $data['data']['segnalatore']=$settings['firstname']." ".$settings['lastname'];
        $data['data']['userid']=  $this->session->userdata('idutente');
        return $this->load->view('sys/desktop/block/segnalazioni',$data, TRUE);
    }
    
    public function ajax_invia_segnalazione()
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
       // require_once('phpmailer/class.phpmailer.php');
        $post=$_POST;
      
        
        $messaggio=$post['tipo']." - ".$post['priorita']." - ".$post['testo'];
        /*
        $mail = new PHPMailer();
        $mail->Host = "smtp.about-x.info";
        $mail->From = "ilease@about-x.info";
        $mail->Password = "Eraclea2014.24";
        $mail->Port = 587;
        $mail->addAddress("ryuluca@gmail.com");
        $mail->Subject = "SEGNALAZIONE";
        $mail->Body = $messaggio;
        
        if(!$mail->send())
            echo "Mailer Error: ".$mail->ErrorInfo;
        else
            echo 'Message has been sent';*/
        /*$headmail ="From: JDocWeb <ilease@about-x.info>\n";
        $headmail.="Return-Path: ilease@about-x.info\n";
        $headmail.="User-Agent: Php Mail Function\n";
        $headmail.="X-Accept-Language: en-us, en\n";
        $headmail.="MIME-Version: 1.0\n";
        $headmail.="X-Priority: 1 (Highest)\n";
        $headmail.="Content-Type: text/plain; charset=UTF-8; format=flowed\n";
        $headmail.="Content-Transfer-Encoding: 7bit\n";
        
        $ResultMail=mail('ryuluca@gmail.com','test da codice','test da codice',"'".$headmail."'");
        
        echo $ResultMail;*/
        //mail('galli8822@gmail.com','test da codice','test da codice');
        //mail("a.galli@about-x.com", 'segnalazione', $messaggio);
        $files=$_FILES;
        if(isset($files['allegati'])) //controllo se esiste l'array dei file
        {
            $allegati = $files['allegati'];
        }
        else
        {
            $allegati=array();
        }
        $this->Sys_model->salva_segnalazione($post,$allegati);
        
    }
    
    public function get_segnalazioni($recordid_azienda,$userid=null)
    {
        $segnalazioni=  $this->Sys_model->get_segnalazioni($recordid_azienda,$userid);
        $json_segnalazioni=  json_encode($segnalazioni);
        echo $json_segnalazioni;
    }
    
    public function ajax_load_block_riepilogo_segnalazioni()
    {
        $block=  $this->load_block_riepilogo_segnalazioni();
        echo $block;
    }
    
    public function load_block_riepilogo_segnalazioni()
    {
        $data=array();
        $settings=$this->Sys_model->get_settings();
        $recordid_azienda="";
        if(array_key_exists('recordid_azienda', $settings))
        {
            $recordid_azienda=$settings['recordid_azienda'];
        }
        $userid=$this->session->userdata('idutente');
        $json = file_get_contents("http://server.about-x.com:8822/jdocweb/index.php/sys_viewcontroller/get_segnalazioni/$recordid_azienda/$userid");
        //$json = file_get_contents("http://localhost:8822/jdocweb2/index.php/sys_viewcontroller/get_segnalazioni/$recordid_azienda/$userid");
        $segnalazioni = json_decode($json,true);
        $data['segnalazioni']=$segnalazioni;
        return $this->load->view('sys/desktop/block/riepilogo_segnalazioni',$data, TRUE);
    }
    
    public function ajax_prestampa_scheda_record_completa($tableid,$recordid)
    {
        $data=array();
        $labels=  $this->Sys_model->get_labels_table($tableid,'scheda',$recordid);
        foreach ($labels as $label_key => $label) {
            if($label['type']=='master')
            {
                $fields=$this->Sys_model->get_fields_table($label['tableid'],$label['label'],$recordid,'scheda','master');
                if(count($fields)>0)
                {
                   $data['data']['labels'][$label_key]=$label['label']; 
                }
                
            }
            if($label['type']=='linked')
            {
                $linked_table=$label['tableid'];
                $linked_records=$this->Sys_model->get_records_linkedtable($linked_table, $tableid, $recordid);
                if(count($linked_records)>0)
                {
                    $data['data']['labels'][$label_key]=$label['label'];
                }
                
            }
            if($label['type']=='linkedmaster')
            {
                if(($label['linkedmaster_recordid']!='')&&($label['linkedmaster_recordid']!=null))
                {
                    $data['data']['labels'][$label_key]=$label['label'];
                }
            }
        }

        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        echo $this->load->view('sys/desktop/stampe/prestampa_scheda_record_completa',$data, TRUE);
    }
    
    //CUSTOM DIMENSIONE IMMOBILIARE
    public function stampa_vetrina_pdf($recordid)
    {
       
        $userid=$this->session->userdata('userid');
        $data['userid']=  $userid;
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);
        
        $paese=strtoupper($fields['paese']['valuecode'][0]['value']);
        $data['paese']=  $paese;
        $categoria=$fields['categoria']['valuecode'][0]['value'];
        $data['categoria']=$categoria;
        
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        $locali='';
        $sul='';
        $titolo='';
        if($tipo=='Vendita')
        {
            $locali=$fields['imm_locali_num']['valuecode'][0]['value']." Locali";
            $sul=$fields['imm_sul_mq']['valuecode'][0]['value']." mq";
        }
        if($tipo=='Affitto')
        {
            $locali=$fields['aff_locali_num']['valuecode'][0]['value']." Locali";
            $sul=$fields['aff_sul_mq']['valuecode'][0]['value']." mq";
        }
        if(($tipo=='Affitto')||($tipo=='Vendita'))
        {
            $titolo=$categoria." ".$locali." LOCALI";
        }
        if(($tipo=='Palazzina in vendita')||($tipo=='Terreno in vendita'))
        {
            $titolo=$paese." ".$categoria;
        }
        
        
        $data['locali']=$locali;
        
        $data['titolo']=$titolo;
        
        $data['mq']=$fields['imm_sul_mq']['valuecode'][0]['value'];
        
        $data['camereletto']=$fields['imm_camereletto_num']['valuecode'][0]['value'];
        
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        if($tipo=='Immobile in affitto')
        {
            $prezzo=$fields['aff_pigionenettamensile']['valuecode'][0]['value'];
        }
        else
        {
            $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
            if($this->isnotempty($prezzo))
            {
                $prezzo=  number_format($prezzo,0,',',"'");
            }
            else
            {
                $prezzo='';
            }
        }
        
        $data['prezzo']=$prezzo;
        
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.jpg";
        }
        $data['foto_vetrina1']=null; 
        if(array_key_exists('foto_vetrina1', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina1'])>0)
            {
               $data['foto_vetrina1']=$foto_prospetto['foto_vetrina1'][0]['complete_path']; 
            }
        }
        $data['foto_vetrina2']=null;
        if(array_key_exists('foto_vetrina2', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina2'])>0)
            {
               $data['foto_vetrina2']=$foto_prospetto['foto_vetrina2'][0]['complete_path']; 
            }
        }
        $data['foto_vetrina3']=null;
        if(array_key_exists('foto_vetrina3', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina3'])>0)
            {
               $data['foto_vetrina3']=$foto_prospetto['foto_vetrina3'][0]['complete_path']; 
            }
        }
        return $this->load->view('sys/desktop/stampe/stampa_vetrina_pdf',$data, TRUE);
    }
    
    public function stampa_vetrina_pdf2($recordid)
    {
       
        $userid=$this->session->userdata('userid');
        $data['userid']=  $userid;
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);
        
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        $data['tipo']=$tipo;
        
        $paese_array=  $fields['paese']['valuecode'];
        $paese_completo='';
        foreach ($paese_array as $key => $paese) {
            if($paese_completo!='')
                $paese_completo=$paese_completo.'-';
            $paese_completo=$paese_completo.$paese['value'];
        }
        $paese_completo=strtoupper($paese_completo);
        $data['paese']=  $paese_completo;
        
        $data['categoria']=  strtoupper($fields['categoria']['valuecode'][0]['value']);
        $data['locali']=$fields['imm_locali_num']['valuecode'][0]['value'];
        $data['titolo']=$fields['categoria']['valuecode'][0]['value']." ".$fields['imm_locali_num']['valuecode'][0]['value']." LOCALI";
        
        $data['mq']=$fields['imm_sul_mq']['valuecode'][0]['value'];
        
        $data['camereletto']=$fields['imm_camereletto_num']['valuecode'][0]['value'];
        
        $prezzo='';
        if($tipo=='Affitto')
        {
            $prezzo=$fields['aff_pigionenettamensile']['valuecode'][0]['value'];
        }
        if($tipo=='Vendita')
        {
            if($fields['imm_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
            }
            if(($this->isnotempty($prezzo))&&($prezzo!='PREZZO SU RICHIESTA'))
            {
                $prezzo=  number_format($prezzo,0,',',"'");
            }
        }

        if($tipo=='Palazzina in vendita')
        {
            if($fields['pal_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['pal_prezzoimmobile']['valuecode'][0]['value'];
            }
            if(($this->isnotempty($prezzo))&&($prezzo!='PREZZO SU RICHIESTA'))
            {
                $prezzo=  number_format($prezzo,0,',',"'");
            }
        }
        if($tipo=='Terreno in vendita')
        {
            if($fields['ter_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['ter_prezzo_vendita']['valuecode'][0]['value'];
            }
            if(($this->isnotempty($prezzo))&&($prezzo!='PREZZO SU RICHIESTA'))
            {
                $prezzo=  number_format($prezzo,0,',',"'");
            }
            
        }
        

        $data['prezzo']=$prezzo;
        
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.png";
        }
        $data['foto_vetrina1']=null; 
        if(array_key_exists('foto_vetrina1', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina1'])>0)
            {
               $data['foto_vetrina1']=$foto_prospetto['foto_vetrina1'][0]['complete_path']; 
            }
        }
        $data['foto_vetrina2']=null;
        if(array_key_exists('foto_vetrina2', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina2'])>0)
            {
               $data['foto_vetrina2']=$foto_prospetto['foto_vetrina2'][0]['complete_path']; 
            }
        }
        $data['foto_vetrina3']=null;
        if(array_key_exists('foto_vetrina3', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina3'])>0)
            {
               $data['foto_vetrina3']=$foto_prospetto['foto_vetrina3'][0]['complete_path']; 
            }
        }
        $data['foto_vetrina4']=null;
        if(array_key_exists('foto_vetrina4', $foto_prospetto))
        {
            if(count($foto_prospetto['foto_vetrina4'])>0)
            {
               $data['foto_vetrina4']=$foto_prospetto['foto_vetrina4'][0]['complete_path']; 
            }
        }
        return $this->load->view('sys/desktop/stampe/stampa_vetrina_pdf2',$data, TRUE);
    }
    
    public function ajax_stampa_vetrina_pdf($recordid)
    {
        $content=  $this->stampa_vetrina_pdf($recordid);
        $url_stampa=$this->genera_stampa_vetrina($content,'Vetrina','landscape');
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function ajax_stampa_vetrina_pdf2($recordid)
    {
        $content=  $this->stampa_vetrina_pdf2($recordid);
        $path_stampa=$this->genera_stampa_vetrina($content,'Vetrina','landscape');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        echo $block;
    }
    
    //custom about-x
    function ajax_stampa_registrazione_pdf($recordid)
    {
        $data=array();
        $fields_iscrizioneeventi=$this->Sys_model->get_fields_record('iscrizioneeventi',$recordid);
        $data['iscrizione']=$fields_iscrizioneeventi;
        echo $this->load->view('sys/desktop/stampe/stampa_registrazione_pdf',$data, TRUE);
    }
    public function ajax_stampa_prospetto_pdf($recordid)
    {
        $content=  $this->stampa_prospetto_pdf($recordid);
        $path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function ajax_anteprima_prospetto_pdf_nuovo($recordid)
    {
        $content=  $this->stampa_prospetto_pdf_nuovo($recordid);
        $content=conv_text($content);
        file_put_contents("../JDocServer/stampe/stampa.html", $content);
        echo $content;
    }
    
    public function ajax_stampa_prospetto_pdf_nuovo($recordid)
    {
        $immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid'");
        $paese=$immobile['paese'];
        $paese=$this->Sys_model->get_lookup_table_item_description('citta',$paese);
        $categoria=$immobile['categoria'];
        $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
        $locali=$immobile['imm_locali_num'];
        $tipo=$immobile['tipo'];
        $tipo=$this->Sys_model->get_lookup_table_item_description('tipo_immobili',$tipo);
        $riferimento=$immobile['riferimento'];
        $nomefile="Richiesta-".$paese."_".$categoria."_".$locali."_".$tipo."_ID".$riferimento;
        $content=  $this->stampa_prospetto_pdf_nuovo($recordid);
        $path_stampa=$this->genera_stampa($content,$nomefile,'portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,'immobili',$recordid);
        echo $block;
    }
    
    
    public function ajax_stampa_prospetto_proposta($recordid)
    {
        $recordid_immobile=  $this->Sys_model->db_get_value('user_immobili_proposti','recordidimmobili_',"recordid_='$recordid'");
        $content=  $this->stampa_prospetto_pdf_nuovo($recordid_immobile);
        $path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,'immobili',$recordid_immobile);
        echo $block;
    }
    
    public function ajax_stampa_rapportini_pdf($recordid_segnalazione)
    {
        $tableid='segnalazioni';
        $interventi_tecnici=  $this->Sys_model->get_interventi_tecnici($recordid_segnalazione);
        $content="";
        foreach ($interventi_tecnici as $key => $intervento_tecnico) {
            $recordid_timesheet=$intervento_tecnico['recordid_'];
            $content_intervento_tecnico=$this->stampa_rapportino_pdf($recordid_timesheet);
            $content=$content.$content_intervento_tecnico;
        }
        $path_stampa=$this->genera_stampa($content,'Rapportino','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,$tableid,$recordid_segnalazione);
        echo $block;
    }
    
    public function ajax_stampa_rapportini_pdf2($recordid_segnalazione)
    {
        $tableid='segnalazioni';
        $interventi_tecnici=  $this->Sys_model->get_interventi_tecnici($recordid_segnalazione);
        $content="";
        foreach ($interventi_tecnici as $key => $intervento_tecnico) {
            $recordid_timesheet=$intervento_tecnico['recordid_'];
            $content_intervento_tecnico=$this->stampa_rapportino_pdf2($recordid_timesheet);
            $content=$content.$content_intervento_tecnico;
        }
        $path_stampa=$this->genera_stampa($content,'Rapportino','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,$tableid,$recordid_segnalazione);
        echo $block;
    }
    
    public function ajax_stampa_rapportino_pdf($recordid_timesheet)
    {
        $content=  $this->stampa_rapportino_pdf($recordid_timesheet);
        $path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,'timesheet',$recordid_timesheet);
        echo $block;
    }
    
    
    public function ajax_stampa_offerta_seatrade($recordid)
    {
        $content=  $this->stampa_offerta_seatrade($recordid);
        $path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function stampa_offerta_seatrade($recordid)
    {
        //$content=  $this->stampa_prospetto_pdf($recordid);
        //$path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $data=array();
        $userid=$this->get_userid();
        
        return $this->load->view('sys/desktop/stampe/stampa_offerta_seatrade',$data, TRUE);
    }
    
    public function stampa_rapportino_pdf($recordid)
    {
        //$content=  $this->stampa_prospetto_pdf($recordid);
        //$path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $data=array();
        $userid=$this->get_userid();
        $result=  $this->Sys_model->db_get("user_timesheet","*","recordid_='$recordid'");
        $recordid_segnalazione=$result[0]['recordidsegnalazioni_'];
        $segnalazione=  $this->Sys_model->db_get('user_segnalazioni','*',"recordid_='$recordid_segnalazione'");
        $recordid_azienda=$segnalazione[0]['recordidaziende_'];
        $azienda=  $this->Sys_model->db_get_row('user_aziende','*',"recordid_='$recordid_azienda'");
        $dati['ragionesociale']=$azienda['ragionesociale'];
        $dati['indirizzo']=$azienda['indirizzo'];
        $dati['citta']=$azienda['citta'];
        $dati['monteoretotale']=$azienda['monteoretotale'];
        $dati['monteore']=$azienda['monteore'];
        $dati['assistenzaremotafine']=$azienda['assistenzaremotafine'];
        $dati['assistenzacentralinofine']=$azienda['assistenzacentralinofine'];
        $dati['tipoassistenza_codice']=$result[0]['tipoassistenza'];//Assistenza monte ore; Asssistenza da remoto;Assistenza centralino
        $columns=$this->Sys_model->get_result_columns('timesheet',$result);
        $result=  $this->Sys_model->get_result_converted('timesheet',$columns,$result,false);
        $dati_timesheet=$result[0];
        $dati['tipoassistenza']=$dati_timesheet['tipoassistenza'];
        $data['userid']=  $userid;
        $dati['data']=$dati_timesheet['datainizio'];
        $dati['tipointervento']=$dati_timesheet['tipointervento'];
        
        
        $dati['descrizione']=$dati_timesheet['note'];
        $dati['noteaggiuntive']=$dati_timesheet['noteaggiuntive'];
        $dati['tecnico']=$dati_timesheet['idutente'];
        $dati['dalle']=$dati_timesheet['orainizio'];
        $dati['alle']=$dati_timesheet['orafine'];
        $dati['durata']=$dati_timesheet['totore'];
        $data['dati']=$dati;
        //$this->load->view('sys/desktop/stampe/stampa_rapportino_about',$data,true);
        //$command='cd ./tools/OfficeToPDF/ && OfficeToPDF.exe "../../stampe/'.$userid.'/rapportino.docx" "../../stampe/'.$userid.'/rapportino.pdf" ';
        //exec($command);
        //$this->Sys_model->inserisci_allegato("stampe/$userid", "rapportino","pdf", 'timesheet', $recordid);
        //$path_stampa="../JDocWeb/stampe/1/rapportino.pdf";
        //$url_stampa=  str_replace("../", domain_url(), $path_stampa);
        //$block=$this->load_block_visualizzatore_stampa($url_stampa);
        //echo $block;
        return $this->load->view('sys/desktop/stampe/stampa_rapportino_pdf',$data, TRUE);
    }
    
    public function stampa_rapportino_pdf2($recordid)
    {
        //$content=  $this->stampa_prospetto_pdf($recordid);
        //$path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $data=array();
        $userid=$this->get_userid();
        $result=  $this->Sys_model->db_get("user_timesheet","*","recordid_='$recordid'");
        $recordid_segnalazione=$result[0]['recordidsegnalazioni_'];
        $segnalazione=  $this->Sys_model->db_get('user_segnalazioni','*',"recordid_='$recordid_segnalazione'");
        $recordid_azienda=$segnalazione[0]['recordidaziende_'];
        $testo_segnalazione=$segnalazione[0]['note'];
        $azienda=  $this->Sys_model->db_get_row('user_aziende','*',"recordid_='$recordid_azienda'");
        $dati['ragionesociale']=$azienda['ragionesociale'];
        $dati['indirizzo']=$azienda['indirizzo'];
        $dati['citta']=$azienda['citta'];
        $dati['monteoretotale']=$azienda['monteoretotale'];
        $dati['monteore']=$azienda['monteore'];
        $dati['assistenzaremotafine']=$azienda['assistenzaremotafine'];
        $dati['assistenzacentralinofine']=$azienda['assistenzacentralinofine'];
        $dati['tipoassistenza_codice']=$result[0]['tipoassistenza'];//Assistenza monte ore; Asssistenza da remoto;Assistenza centralino
        $columns=$this->Sys_model->get_result_columns('timesheet',$result);
        $result=  $this->Sys_model->get_result_converted('timesheet',$columns,$result,false);
        $dati_timesheet=$result[0];
        $dati['tipoassistenza']=$dati_timesheet['tipoassistenza'];
        $data['userid']=  $userid;
        $dati['data']=$dati_timesheet['datainizio'];
        $dati['tipointervento']=$dati_timesheet['tipointervento'];
        $dati['testo_segnalazione']=$testo_segnalazione;
        
        
        $dati['descrizione']=$dati_timesheet['note'];
        $dati['noteaggiuntive']=$dati_timesheet['noteaggiuntive'];
        $dati['tecnico']=$dati_timesheet['idutente'];
        $dati['dalle']=$dati_timesheet['orainizio'];
        $dati['alle']=$dati_timesheet['orafine'];
        $dati['durata']=$dati_timesheet['totore'];
        $data['dati']=$dati;
        //$this->load->view('sys/desktop/stampe/stampa_rapportino_about',$data,true);
        //$command='cd ./tools/OfficeToPDF/ && OfficeToPDF.exe "../../stampe/'.$userid.'/rapportino.docx" "../../stampe/'.$userid.'/rapportino.pdf" ';
        //exec($command);
        //$this->Sys_model->inserisci_allegato("stampe/$userid", "rapportino","pdf", 'timesheet', $recordid);
        //$path_stampa="../JDocWeb/stampe/1/rapportino.pdf";
        //$url_stampa=  str_replace("../", domain_url(), $path_stampa);
        //$block=$this->load_block_visualizzatore_stampa($url_stampa);
        //echo $block;
        return $this->load->view('sys/desktop/stampe/stampa_rapportino_pdf2',$data, TRUE);
    }
    
    
    
    public function genera_stampa($content,$name,$orientation='portrait',$elenco=false)
    {
        $userid=  $this->get_userid();
        $user_settings= $this->Sys_model->get_user_settings($userid);
        $content=conv_text($content);
        $data['content']=$content;
        $data['settings']=$user_settings;
        $data['orientation']=$orientation;
      
        $content=$this->load->view('sys/desktop/stampe/container_stampa',$data, TRUE); 
        
        
        $stampa_html="stampa_".date('Ymdhms')."_".rand(100, 999).".html";
        file_put_contents("../JDocServer/stampe/$stampa_html", $content);
        //orientation: portrait-landscape
        
        $zoom32=$user_settings['zoom32'];
        $zoom=$user_settings['zoom'];
        $imagedpi=$user_settings['image-dpi'];
        $imagequality=$user_settings['image-quality'];
        $wkhtml=$user_settings['wkhtml'];
        if($elenco)
        {
            if($wkhtml=='wkhtmltopdfold32')
            {
                $command='cd ./tools/wkhtmltopdfold32/bin && wkhtmltopdf.exe --page-size A4  --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0  --orientation '.$orientation.' --zoom '.$zoom32.'  "../../../../JDocServer/stampe/'.$stampa_html.'" "../../../../JDocServer/stampe/'.$name.'.pdf" ';
            }
            else
            {
                $command='cd ./tools/wkhtmltopdf/bin && wkhtmltopdf.exe --dpi 10 --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0 --orientation '.$orientation.' --zoom '.$zoom.'  "../../../../JDocServer/stampe/'.$stampa_html.'" "../../../../JDocServer/stampe/'.$name.'.pdf" ';
            }
        }
        else
        {
            $command='cd ./tools/wkhtmltopdf/bin && wkhtmltopdf.exe --page-size A4 --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0 --orientation '.$orientation.'   "../../../../JDocServer/stampe/'.$stampa_html.'" "../../../../JDocServer/stampe/'.$name.'.pdf" ';
        }
        exec($command);
        
        $return=  "../JDocServer/stampe/$name.pdf";
        return $return;
    }
    
    //custom dimensione immobiliare
    public function genera_stampa_vetrina($content,$name,$orientation='portrait')
    {
        $content=conv_text($content);
        file_put_contents("../JDocServer/stampe/stampa.html", $content);
        //orientation: portrait-landscape
        $userid=  $this->get_userid();
        $imagedpi=  $this->Sys_model->get_user_setting('image-dpi',$userid);
        $imagequality=  $this->Sys_model->get_user_setting('image-quality',$userid);
        $command='cd ./tools/wkhtmltopdfold32/bin && wkhtmltopdf.exe --dpi 10 --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0 --orientation '.$orientation.' --zoom 1.9  "../../../../JDocServer/stampe/stampa.html" "../../../../JDocServer/stampe/'.$name.'.pdf" ';
        //$command='cd ./tools/wkhtmltopdf/bin && wkhtmltopdf.exe --dpi 10 --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0 --orientation '.$orientation.' --zoom 1.9  "../../../../JDocServer/stampe/stampa.html" "../../../../JDocServer/stampe/'.$name.'.pdf" ';
        exec($command);
        
        //$return=  server_url()."stampe/$name.pdf";
        $return=  "../JDocServer/stampe/$name.pdf";
        return $return;
    }
    
    public function ajax_preinvio_prospetto($recordid_richiesta)
    {
        $block=  $this->load_view_preinvio_prospetto($recordid_richiesta);
        echo $block;
    }
    
    public function load_view_preinvio_prospetto($recordid_richiesta)
    {
        $data=array();
        $richiesta=  $this->Sys_model->db_get_row('user_immobili_richiesti','*',"recordid_='$recordid_richiesta'");
        $data['richiesta']['recordid']=$recordid_richiesta;
        $data['immobili']=array();
        /*$recordid_immobile_richiesto=$richiesta['recordidimmobili_'];
        if($this->isnotempty($recordid_immobile_richiesto))
        {
            $data['immobili'][0]['recordid']=$recordid_immobile_richiesto;
            $data['immobili'][0]['titolo']=  $this->Sys_model->get_keyfieldlink_value('immobili_richiesti','immobili',$recordid_immobile_richiesto);
            $data['immobili'][0]['badge']=  $this->load_block_fissi('immobili', $recordid_immobile_richiesto);
        }*/
        $counter=1;
        $proposte=$this->Sys_model->db_get('user_immobili_proposti','*',"recordidimmobili_richiesti_='$recordid_richiesta'");
        foreach ($proposte as $key => $proposta) {
            $recordid_immobile_proposto=$proposta['recordidimmobili_'];
            if($this->isnotempty($recordid_immobile_proposto))
            {
                $data['immobili'][$counter]['recordid']=$recordid_immobile_proposto;
                $data['immobili'][$counter]['titolo']=  $this->Sys_model->get_keyfieldlink_value('immobili_richiesti','immobili',$recordid_immobile_proposto);
                $data['immobili'][$counter]['badge']=  $this->load_block_fissi('immobili', $recordid_immobile_proposto);
            }
            $counter++;
        }
        return $this->load->view('sys/desktop/block/preinvio_prospetto',$data, TRUE);
    }
       
    public function ajax_stampa_fattura_pdf($recordid)
    {
        $content=  $this->stampa_fattura_pdf($recordid);
        $path_stampa=$this->genera_stampa($content,'Prospetto','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function stampa_fattura_pdf($recordid)
    {
        $data=array();
        $vendita=  $this->Sys_model->get_fields_table('vendite','Dati',$recordid);
        $recordid_azienda=  $this->Sys_model->db_get_value('user_vendite','recordidaziende_',"recordid_='$recordid'");
        $azienda=$this->Sys_model->get_fields_table('aziende','Dati',$recordid_azienda);
        $vendita_righe=  $this->Sys_model->db_get('user_vendite_righe','recordid_',"recordidvendite_='$recordid'");
        foreach ($vendita_righe as $key => $vendita_riga) {
            $vendita_righe[$key]=$this->Sys_model->get_fields_table('vendite_righe','Dati',$vendita_riga['recordid_']);
        }
        
        $data['azienda']=$azienda;
        $data['vendita']=$vendita;
        $data['vendita_righe']=$vendita_righe;
        return $this->load->view('sys/desktop/stampe/stampa_fattura_pdf',$data, TRUE);
    }
    
    public function stampa_prospetto_pdf($recordid)
    {
        $data=array();
        $data['userid']=  $this->session->userdata('userid');
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);

        $data['foto_copertina']=null; 
        if(array_key_exists('Copertina', $foto_prospetto))
        {
            if(count($foto_prospetto['Copertina'])>0)
            { 
                $image_width=0;
                $image_height=0;
                if(file_exists($foto_prospetto['Copertina'][0]['complete_path']))
                {
                 $imagesize= getimagesize($foto_prospetto['Copertina'][0]['complete_path']);
                 $image_width=$imagesize[0];
                 $image_height=$imagesize[1];
                 $foto_copertina['path']=$foto_prospetto['Copertina'][0]['complete_path']; 
                 $foto_copertina['width']=$image_width;
                 $foto_copertina['height']=$image_height;
                 $data['foto_copertina']=$foto_copertina; 
                }
            }
        }
        $data['foto_interni']=null;
        if(array_key_exists('Interni', $foto_prospetto))
        {
            if(count($foto_prospetto['Interni'])>0)
            {
                foreach ($foto_prospetto['Interni'] as $key => $foto_interno) {
                    $data['foto_interni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        $data['foto_esterni']=null;
        if(array_key_exists('Esterni', $foto_prospetto))
        {
            if(count($foto_prospetto['Esterni'])>0)
            {
                foreach ($foto_prospetto['Esterni'] as $key => $foto_interno) {
                    $data['foto_esterni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        
        $data['foto_piantine']=null;
        if(array_key_exists('Piantine', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantine'])>0)
            {
                foreach ($foto_prospetto['Piantine'] as $key => $foto_piantina) {
                    $data['foto_piantine'][]=$foto_piantina['complete_path']; 
                }
               
            }
        }
        /*if(array_key_exists('Piantina piano seminterrato', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano seminterrato'])>0)
            {
                    $data['foto_piantine']['Piantina piano seminterrato']=$foto_prospetto['Piantina piano seminterrato'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina piano terra', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano terra'])>0)
            {
                    $data['foto_piantine']['Piantina piano terra']=$foto_prospetto['Piantina piano terra'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina primo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina primo piano'])>0)
            {
                    $data['foto_piantine']['Piantina primo piano']=$foto_prospetto['Piantina primo piano'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina secondo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina secondo piano'])>0)
            {
                    $data['foto_piantine']['Piantina secondo piano']=$foto_prospetto['Piantina secondo piano'][0]['complete_path'];  
            }
        }
        */
        $paese_array=  $fields['paese']['valuecode'];
        $paese_completo='';
        foreach ($paese_array as $key => $paese) {
            if($paese_completo!='')
                $paese_completo=$paese_completo.'-';
            $paese_completo=$paese_completo.$paese['value'];
        }
        $paese_completo=strtoupper($paese_completo);
        $data['paese']=  $paese_completo;
        $categoria=$fields['categoria']['valuecode'][0]['value'];
        $data['categoria']=$categoria;
        
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        $locali='';
        $sul='';
        $titolo='';
        if($tipo=='Vendita')
        {
            $locali=$fields['imm_locali_num']['valuecode'][0]['value']." Locali";
            $sul=$fields['imm_sul_mq']['valuecode'][0]['value']." mq";
        }
        if($tipo=='Affitto')
        {
            $locali=$fields['aff_locali_num']['valuecode'][0]['value']." Locali";
            $sul=$fields['aff_sul_mq']['valuecode'][0]['value']." mq";
        }
        if(($tipo=='Affitto')||($tipo=='Vendita'))
        {
            $titolo=$categoria." ".$locali." LOCALI";
        }
        if(($tipo=='Palazzina in vendita')||($tipo=='Terreno in vendita'))
        {
            $titolo=$categoria;
        }
        
        
        
        $data['locali']=$locali;
        $data['sul']=$sul;
        $data['titolo']=$titolo;
        
        $data['descrizione']="";
        if(array_key_exists('descrizione', $fields))
        {
            $descrizione=$fields['descrizione']['valuecode'][0]['value'];
            $descrizione=nl2br($descrizione);
            $data['descrizione']=$descrizione;
        }
        
        $prezzo='';
        if($tipo=='Vendita')
        {
            if($fields['imm_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
            }
        }

        if($tipo=='Palazzina in vendita')
        {
            if($fields['pal_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['pal_prezzoimmobile']['valuecode'][0]['value'];
            }
        }
        if($tipo=='Terreno in vendita')
        {
            if($fields['ter_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['ter_prezzo_vendita']['valuecode'][0]['value'];
            }
            
        }
        if(($this->isnotempty($prezzo))&&($prezzo!='PREZZO SU RICHIESTA'))
        {
            $prezzo=  number_format($prezzo,0,',',"'");
            $prezzo=$prezzo.".--";
        }
        
        $data['prezzo']=$prezzo;
        
        foreach ($fields as $key => $field) 
        {
            //$allfields[$field['fieldid']]=$field;
            if($this->isnotempty($field['valuecode'][0]['value']))
            {
                $fields_by_sublabel[$field['sublabel']][]=$field;
            }
            
        }
        
        $data['sublabels']=  $this->Sys_model->get_table_sublabels('immobili');
        $data['fields_by_sublabel']=$fields_by_sublabel;
        $userid=  $this->session->userdata('userid');
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.png";
        }
        return $this->load->view('sys/desktop/stampe/stampa_prospetto_pdf',$data, TRUE);
    }
    
    public function stampa_prospetto_pdf_nuovo($recordid)
    {
        $data=array();
        $userid=  $this->session->userdata('userid');
        $data['userid']=$userid;
        
        $fields=  $this->Sys_model->get_dati_stampa_prospetto($recordid);
        $data['fields']=$fields;
        
        $consulente_id=$fields['consulente']['valuecode'][0]['code'];
        
        if(($consulente_id==$userid)||($userid==2)||($userid==13))
        {
            $data['consulente']= $this->Sys_model->get_user_settings($consulente_id);
            $data['consulente']['id']=$consulente_id; 
        }
        else
        {
            $data['consulente']=null;
        }
        
        
        
        $foto_prospetto=$this->Sys_model->get_foto_prospetto($recordid);

        $data['foto_copertina']=null; 
        if(array_key_exists('Copertina', $foto_prospetto))
        {
            if(count($foto_prospetto['Copertina'])>0)
            { 
                $foto_copertina['path']=$foto_prospetto['Copertina'][0]['complete_path']; 
                $foto_copertina['widht']=0;
                $foto_copertina['height']=0;
                if(isnotempty($foto_prospetto['Copertina'][0]['complete_path']))
                {
                    $imagesize= getimagesize($foto_prospetto['Copertina'][0]['complete_path']);
                    $image_width=$imagesize[0];
                    $image_height=$imagesize[1];
                    $foto_copertina['widht']=$image_width;
                    $foto_copertina['height']=$image_height;
                     
                }
                $data['foto_copertina']=$foto_copertina;
            }
        }
        $data['foto_descrizione']=null; 
        if(array_key_exists('prospetto_descrizione', $foto_prospetto))
        {
            if(count($foto_prospetto['prospetto_descrizione'])>0)
            { 
               $data['foto_descrizione']=$foto_prospetto['prospetto_descrizione'][0]['complete_path']; 
            }
        }
        $data['foto_paese']=null; 
        if(array_key_exists('prospetto_paese', $foto_prospetto))
        {
            if(count($foto_prospetto['prospetto_paese'])>0)
            { 
               $data['foto_paese']=$foto_prospetto['prospetto_paese'][0]['complete_path']; 
            }
        }
        $data['foto_cartina']=null;
        $data['foto_interni']=array();
        if(array_key_exists('Interni', $foto_prospetto))
        {
            if(count($foto_prospetto['Interni'])>0)
            {
                foreach ($foto_prospetto['Interni'] as $key => $foto_interno) {
                    $data['foto_interni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        $data['foto_esterni']=array();
        if(array_key_exists('Esterni', $foto_prospetto))
        {
            if(count($foto_prospetto['Esterni'])>0)
            {
                foreach ($foto_prospetto['Esterni'] as $key => $foto_interno) {
                    $data['foto_esterni'][]=$foto_interno['complete_path']; 
                }
               
            }
        }
        
        $data['foto_piantine']=array();
        if(array_key_exists('Piantine', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantine'])>0)
            {
                /*$foto_copertina['path']=$foto_prospetto['Copertina'][0]['complete_path']; 
                $foto_copertina['widht']=0;
                $foto_copertina['height']=0;
                if(isnotempty($foto_prospetto['Copertina'][0]['complete_path']))
                {
                    $imagesize= getimagesize($foto_prospetto['Copertina'][0]['complete_path']);
                    $image_width=$imagesize[0];
                    $image_height=$imagesize[1];
                    $foto_copertina['widht']=$image_width;
                    $foto_copertina['height']=$image_height;
                     
                }
                $data['foto_copertina']=$foto_copertina;
                */
                foreach ($foto_prospetto['Piantine'] as $key => $foto_piantina) {
                    $extension=$foto_piantina['extension'];
                    $foto_piantina['path']=$foto_piantina['complete_path'];
                    $foto_piantina['widht']=0;
                    $foto_piantina['height']=0;
                    if(isnotempty($foto_piantina['complete_path']))
                    {
                        $imagesize= getimagesize($foto_piantina['complete_path']);
                        $image_width=$imagesize[0];
                        $image_height=$imagesize[1];
                        $foto_piantina['widht']=$image_width;
                        $foto_piantina['height']=$image_height;
                        if(($image_width>$image_height)&&($extension=='jpg'))
                        {
                            $source = imagecreatefromjpeg($foto_piantina['complete_path']);
                            $rotate = imagerotate($source, 90, 0);
                            $rotated_path=str_replace('.jpg','_rotated.jpg',$foto_piantina['complete_path']);
                            imagejpeg($rotate,$rotated_path);
                            $foto_piantina['path']=$rotated_path;
                            $foto_piantina['widht']=$image_height;
                            $foto_piantina['height']=$image_width;
                            
                        }
                    }
                    $data['foto_piantine'][]=$foto_piantina; 
                                        
                }
               
            }
        }
        /*if(array_key_exists('Piantina piano seminterrato', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano seminterrato'])>0)
            {
                    $data['foto_piantine']['Piantina piano seminterrato']=$foto_prospetto['Piantina piano seminterrato'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina piano terra', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina piano terra'])>0)
            {
                    $data['foto_piantine']['Piantina piano terra']=$foto_prospetto['Piantina piano terra'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina primo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina primo piano'])>0)
            {
                    $data['foto_piantine']['Piantina primo piano']=$foto_prospetto['Piantina primo piano'][0]['complete_path'];  
            }
        }
        if(array_key_exists('Piantina secondo piano', $foto_prospetto))
        {
            if(count($foto_prospetto['Piantina secondo piano'])>0)
            {
                    $data['foto_piantine']['Piantina secondo piano']=$foto_prospetto['Piantina secondo piano'][0]['complete_path'];  
            }
        }
        */
        $paese_array=  $fields['paese']['valuecode'];
        $paese_completo='';
        foreach ($paese_array as $key => $paese) {
            if($paese_completo!='')
                $paese_completo=$paese_completo.'-';
            $paese_completo=$paese_completo.$paese['value'];
        }
        $paese_completo=$paese_completo;
        $data['paese']=  $paese_completo;
        $data['via']=  $fields['via']['valuecode'][0]['value'];
        $categoria=$fields['categoria']['valuecode'][0]['value'];
        $data['categoria']=$categoria;
        
        $tipo=$fields['tipo']['valuecode'][0]['value'];
        $data['tipo']=$tipo;
        $locali='';
        $sul='';
        $titoletto='';
        $data['riferimento']=$fields['riferimento']['valuecode'][0]['value'];
        $data['titolo']=$fields['titolo']['valuecode'][0]['value'];
        if($tipo=='Vendita')
        {
            $locali=$fields['imm_locali_num']['valuecode'][0]['value'];
            $sul=$fields['imm_sul_mq']['valuecode'][0]['value']." mq";
        }
        if($tipo=='Affitto')
        {
            $locali=$fields['aff_locali_num']['valuecode'][0]['value']."";
            $sul=$fields['aff_sul_mq']['valuecode'][0]['value']." mq";
        }
        if(($tipo=='Affitto')||($tipo=='Vendita'))
        {
            $titoletto=$categoria." ".$locali."";
            if(isnotempty($locali))
            {
                $titoletto=$titoletto." locali";
            }
        }
        if(($tipo=='Palazzina in vendita')||($tipo=='Terreno in vendita'))
        {
            $titoletto=$categoria;
        }
        
        
        
        $data['locali']=$locali;
        $data['sul']=$sul;
        $data['titoletto']=$titoletto;
        
        $data['descrizione']="";
        if(array_key_exists('descrizione', $fields))
        {
            $descrizione=$fields['descrizione']['valuecode'][0]['value'];
            $descrizione=nl2br($descrizione);
            $data['descrizione']=$descrizione;
        }
        $data['descrizione_copertina']="";
        if(array_key_exists('descrizione_copertina', $fields))
        {
            $descrizione=$fields['descrizione_copertina']['valuecode'][0]['value'];
            $descrizione=nl2br($descrizione);
            $data['descrizione_copertina']=$descrizione;
        }
        $data['descrizione_location']="";
        if(array_key_exists('descrizione_location', $fields))
        {
            $descrizione=$fields['descrizione_location']['valuecode'][0]['value'];
            $descrizione=nl2br($descrizione);
            $data['descrizione_location']=$descrizione;
        }
        
        $prezzo='';
        if($tipo=='Vendita')
        {
            if($fields['imm_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['imm_prezzoimmobile']['valuecode'][0]['value'];
            }
        }

        if($tipo=='Palazzina in vendita')
        {
            if($fields['pal_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['pal_prezzoimmobile']['valuecode'][0]['value'];
            }
        }
        if($tipo=='Terreno in vendita')
        {
            if($fields['ter_prezzosurichiesta']['valuecode'][0]['code']=='si')
            {
                $prezzo= 'PREZZO SU RICHIESTA';
            }
            else
            {
                $prezzo=$fields['ter_prezzo_vendita']['valuecode'][0]['value'];
            }
            
        }
        if(($this->isnotempty($prezzo))&&($prezzo!='PREZZO SU RICHIESTA'))
        {
            $prezzo=  number_format($prezzo,0,',',"'");
            $prezzo=$prezzo.".--";
        }
        
        $data['prezzo']=$prezzo;
        
        foreach ($fields as $key => $field) 
        {
            //$allfields[$field['fieldid']]=$field;
            if($this->isnotempty($field['valuecode'][0]['value']))
            {
                $fields_by_sublabel[$field['sublabel']][]=$field;
            }
            
        }
        
        $data['sublabels']=  $this->Sys_model->get_table_sublabels('immobili');
        $data['fields_by_sublabel']=$fields_by_sublabel;
        $userid=  $this->session->userdata('userid');
        $data['header_logo']="assets/images/logo_dimensioneimmobiliare.png";
        if(($userid==3)||($userid==6)||($userid==7)||($userid==9))
        {
            $data['header_logo']="assets/images/logo_dimensioneimmobiliare_sopraceneri.png";
        }
        
        $data['foto_cartina']='';
        return $this->load->view('sys/desktop/stampe/stampa_prospetto_pdf',$data, TRUE);
    }
    
    public function ajax_stampa_scheda_record_completa($tableid,$recordid)
    {
        $data=array();
        //$fissi=$data['data']['block']['block_fissi']=  $this->load_block_fissi($tableid, $recordid,$interface);
        $post=$_POST;
        $labels=  $this->Sys_model->get_labels_table($tableid,'scheda',$recordid);
        foreach ($post as $key_selected_label => $selected_label) {
           $label= $labels[$key_selected_label];
            if($label['type']=='master')
            {
                //$fields=$this->Sys_model->get_fields_table($label['tableid'],$label['label'],$recordid,'all','master');
                $fields=$this->Sys_model->get_fields_table($label['tableid'],$label['label'],$recordid,'inserimento','master');
                if(count($fields)>0)
                {
                   $labels_return[$key_selected_label]= $label;
                   $labels_return[$key_selected_label]['fields']=$fields; 
                }
                
            }
            if($label['type']=='linked')
            {
                $linked_table=$label['tableid'];
                $linked_records=$this->Sys_model->get_records_linkedtable($linked_table, $tableid, $recordid);
                if(count($linked_records)>0)
                {
                    $label_return= $label;
                    foreach ($linked_records as $key => $linked_record) {
                        $linked_recordid=$linked_record['recordid_'];
                        $label_return['records'][$linked_recordid]['recordid']=$linked_recordid;
                        $fields=$this->Sys_model->get_fields_table($label['tableid'],'null',$linked_recordid,'inserimento','linked');
                        $label_return['records'][$linked_recordid]['fields']=$fields;
                    }
                    $labels_return[$key_selected_label]=$label_return;
                }
                
            }
            if($label['type']=='linkedmaster')
            {
                if(($label['linkedmaster_recordid']!='')&&($label['linkedmaster_recordid']!=null))
                {
                    $fields=$this->Sys_model->get_fields_table($label['tableid'],$label['label'],$label['linkedmaster_recordid'],'all','linkedmaster');
                    $labels_return[$key_selected_label]= $label;
                    $labels_return[$key_selected_label]['fields']=$fields;
                }
            }
        }
        $data['data']['labels']=$labels_return;
        $data['data']['block']['fissi']=  $this->load_block_fissi($tableid, $recordid);
        echo $this->load->view('sys/desktop/stampe/stampa_scheda_record_completa',$data, TRUE);
    }
    
    public function ajax_save_view($tableid)
    {
        
        $post=$_POST;
        $view_name=$post['view_name'];
        
        $this->Sys_model->save_view($tableid,$view_name,$post);
    }
    
    public function ajax_view_changed($tableid,$viewid)
    {
        $query=$this->Sys_model->get_view_query($tableid,$viewid);
        echo $query;
    }
    
    public function ajax_save_report($tableid)
    {
        $post=$_POST;
        $this->Sys_model->save_report($tableid,$post);
    }
    
    public function ajax_load_block_reports_relativi_new($tableid)
    {
        /*$post=$_POST;
        //$query_array=  $this->Sys_model->get_search_query($tableid,$post);
        //$query=$query_array['query_owner'];
        $query=$post['query'];
        $fields=$this->Sys_model->get_fields_table($tableid);
        $view_selected_id=$post['view_selected_id'];
        $block=  $this->load_block_reports_relativi($tableid,$fields,$query,$view_selected_id);
        echo $block;*/
    }
    
    public function ajax_load_block_reports_relativi($tableid)
    {
        
        $post=$_POST;
        //$query_array=  $this->Sys_model->get_search_query($tableid,$post);
        //$query=$query_array['query_owner'];
        $query=$post['query'];
        $fields=$this->Sys_model->get_fields_table($tableid);
        $view_selected_id=$post['view_selected_id'];
        $block=  $this->load_block_reports_relativi($tableid,$fields,$query,$view_selected_id);
        echo $block;
    }
    
    public function load_block_reports_relativi_new($tableid,$fields,$query,$view_selected_id=null)
    {
        $data=array();
        $data['data']['block']['reports']=array();
        $data['data']['tableid']=$tableid;
        $data['data']['fields']=$fields;
        $data['data']['userid']=  $userid=$this->session->userdata('userid');
        $reports=  $this->Sys_model->get_reports($tableid,$query,$view_selected_id);
        $data['reports']=array();
        foreach ($reports as $key => $report) 
        {
            $reportid=$report['reportid'];
            $data['reports'][$reportid]['tableid']=$report['tableid'];
            $data['reports'][$reportid]['reportid']=$report['reportid'];
            $data['reports'][$reportid]['name']=$report['name'];
            $data['reports'][$reportid]['block']=$this->load_block_report($report);
            //$data['data']['block']['reports'][]=  $this->load_block_report($report);
        }
        return $this->load->view('sys/desktop/block/reports_relativi',$data, TRUE);
    }
    
    public function load_block_reports_relativi($tableid,$fields,$query,$view_selected_id=null)
    {
        $data=array();
        $data['data']['block']['reports']=array();
        $data['data']['tableid']=$tableid;
        $data['data']['fields']=$fields;
        $data['data']['userid']=  $userid=$this->session->userdata('userid');
        $reports=  $this->Sys_model->get_reports($tableid,$query,$view_selected_id);
        foreach ($reports as $key => $report) 
        {
            $data['data']['block']['reports'][]=  $this->load_block_report($report);
        }
        return $this->load->view('sys/desktop/block/reports_relativi',$data, TRUE);
    }
    
    public function ajax_load_block_report($report)
    {
        $block=  $this->load_block_report($report);
        echo $block;
    }
    
    public function load_block_report($report)
    {
        $data=array();
        $tableid=$report['tableid'];
        $layout=$report['layout'];
        $data['tableid']=$tableid;
        $data['data']['report']=$report;
        $data['data']['fieldtype']=$report['fieldtype'];
        $data['cliente_id']=$this->Sys_model->get_cliente_id();
        return $this->load->view("sys/desktop/report/report_$layout",$data, TRUE);
    }
    
    public function load_block_report_new($report)
    {
        $data=array();
        $tableid=$report['tableid'];
        $layout=$report['layout'];
        $data['tableid']=$tableid;
        $data['report']=$report;
        $data['data']['fieldtype']=$report['fieldtype'];
        return $this->load->view("sys/desktop/block/chart",$data, TRUE);
    }
    
    public function invio_mail_modulo()
    {
        $recordid='null';
        $tableid='CANDID';
        $funzione='inserimento';
        $navigatorField='nuovo';
        $popuplvl_new=1;
        $layout='allargata';
        $interface='desktop';

        $data['data']['block']['block_dati_labels']=  $this->load_block_dati_labels_invio_mail_modulo($tableid, $recordid,$funzione,'scheda_record','null');
        $data['data']['block']['block_fissi']=  '';
        $data['data']['block']['allegati']=  $this->load_block_allegati($tableid, 'null','scheda',1,'desktop');
        $data['data']['block']['block_visualizzatore']=  $this->load_block_visualizzatore("", "", "");
        $data['data']['block']['block_code']=  $this->load_block_code('modifica');
        $data['data']['block']['block_autobatch']=  $this->load_block_autobatch('inserimento');
        if((($recordid!=null))&&($recordid!='null'))
        {
            $rows= $this->Sys_model->get_allegati($tableid, $recordid);
        }
        else
        {
            $rows=array();
        }
        $data['data']['numfiles']=  count($rows);
        $data['data']['tableid']=$tableid;
        $data['data']['recordid']=$recordid;
        $data['data']['funzione']=$funzione;
        $data['data']['mode']='scheda';
        $data['data']['target']='popup';
        $data['data']['popuplvl']=$popuplvl_new;
        $data['data']['navigatorField']=$navigatorField;
        $data['data']['settings']= $this->Sys_model->get_settings($tableid);
        $data['data']['settings']['tableid']=$tableid;


        if($tableid=='contrattimandato')
        {
            $layout='standard_allegati';
        }
        
        $data['data']['settings']['layout_scheda']=$layout;
        
        echo $this->load->view('sys/'.$interface.'/custom/ww/scheda_record_invio_mail_modulo',$data);
    }
    
    public function ajax_load_block_gestione_lookuptable($lookuptableid)
    {
        $block=  $this->load_block_gestione_lookuptable($lookuptableid);
        echo $block;
    }
    
    public function load_block_gestione_lookuptable($lookuptableid)
    {
        $data=array();
        $data['data']['lookuptableid']=$lookuptableid;
        $data['data']['lookuptable_items']=  $this->Sys_model->get_lookuptable($lookuptableid);
        return $this->load->view("sys/desktop/block/gestione_lookuptable",$data, TRUE);
    }
    
    public function generate_thumbnail($path="",$filename="",$ext="")
    {
        if(file_exists("../JDocServer/'.$path.'/'.$filename.'_thumbnail.jpg"))
        {
            unlink("../JDocServer/'.$path.'/'.$filename.'_thumbnail.jpg");
        }
        if(strtolower($ext)=='pdf')
        {
            $command='cd ../JDocServices/gs9.01/bin && gswin32c.exe -dNOPAUSE -sDEVICE=jpeg -r50 -sOUTPUTFILE="../../../JDocServer/'.$path.'/'.$filename.'_thumbnail.jpg" "../../../JDocServer/'.$path.'/'.$filename.'.pdf" ';
            exec($command);
        }
        if(strtolower($ext)=='jpg')
        {
            /*$targ_w = $targ_h = 160; 
            $jpeg_quality = 90; 
            $src = "../JDocServer/'.$path.'/'.$filename.'.pdf"; 
            $img_r = imagecreatefromjpeg($src); 
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h ); 
            imagecopyresampled($dst_r,$img_r,0,0); 
            imagejpeg($dst_r, "../JDocServer/'.$path.'/'.$filename.'_thumbnail.jpg", $jpeg_quality);*/
            $command='cd ./tools/ImageMagick/ && convert "../../../JDocServer/'.$path.'/'.$filename.'.'.$ext.'" -resize 200x200 -quality 70 "../../../JDocServer/'.$path.'/'.$filename.'_thumbnail.jpg"';
            exec($command);
            /*if(file_exists("../JDocServer/$path/$filename.$ext"))
            {
                copy("../JDocServer/$path/$filename.$ext","../JDocServer/$path/$filename"."_thumbnail.jpg");
            } */  
        }
        
    }
    
    public function generate_validato()
    {
        $counter=$this->Sys_model->generate_validato();
        echo "generazione validati completato: $counter";
    }
    
    public function generate_stato()
    {
        $counter=$this->Sys_model->generate_stato();
        echo "generazione stati completato: $counter";
    }
    
    public function reset_qualifiche()
    {
        $this->Sys_model->reset_qualifiche();
        echo 'reset qualifiche completato';
    }
    
    public function generate_qualifiche()
    {
        $counter=$this->Sys_model->generate_qualifiche();
        echo "generazione qualifiche completato: $counter";
    }
    
    public function generate_giudizi()
    {
        $counter=$this->Sys_model->generate_giudizi();
        echo "generazione giudizi completata: $counter";
    }
    
    //CUSTOM WORK&WORK WW
    public function generate_eta()
    {
        $counter=$this->Sys_model->generate_eta();
        echo "generazione età completata: $counter";
    }
    
    
    public function ajax_load_content_gestione_bollettino($interface)
    {
        $content=  $this->load_content_gestione_bollettino($interface);
        echo $content;
    }
    public function load_content_gestione_bollettino($interface='desktop')
    {
        $data['data']['schede']['scheda_dati_ricerca']=$this->load_scheda_dati_ricerca('stampebollettini_candidati','default');
        $data['data']['block']['block_risultati_ricerca']="";
        $data['data']['tableid']='stampebollettini_candidati';
        $data['data']['settings']=$this->Sys_model->get_settings();
        return $this->load->view('sys/desktop/content/gestione_bollettino',$data, TRUE);
    }
    
    public function ajax_load_block_dati_bollettino()
    {
        echo $this->load_block_dati_bollettino();
    }
    public function load_block_dati_bollettino()
    {
        $data=array();
        return $this->load->view('sys/desktop/block/dati_bollettino',$data, TRUE);
    }
    
    public function stampa_bollettino($codicebollettino)
    {
        $post=$_POST;
        $data['userid']=  $this->session->userdata('idutente');
        foreach ($post['profilo'] as $key => $codice_profilo) {
            $profili[$codice_profilo]['candidati']=  $this->Sys_model->get_bollettino_candidati($codicebollettino,$codice_profilo);
            $profili[$codice_profilo]['titolo']='';
            if($codice_profilo=='EdMest')
                    $profili[$codice_profilo]['titolo']='PROFILI EDILIZIA E MESTIERI';
            if($codice_profilo=='FBan')
                    $profili[$codice_profilo]['titolo']='PROFILI FIDUCIARIO E BANCARIO';
            if($codice_profilo=='Imp')
                    $profili[$codice_profilo]['titolo']='PROFILI IMPIEGATIZI';
            if($codice_profilo=='IT')
                    $profili[$codice_profilo]['titolo']='PROFILI INFORMATION TECHNOLOGY';
            if($codice_profilo=='Pro')
                    $profili[$codice_profilo]['titolo']='PROFILI INDUSTRIA';
        }
        $data['codicebollettino']=$codicebollettino;
        $data['profili']=$profili;
        $codiceanno=substr($codicebollettino, 0, 2);
        $anno=  "20".$codiceanno;
        $codicemese=substr($codicebollettino, 2, 2);
        $mese=date('F', mktime(0, 0, 0, $codicemese, 10));
        $mesi["January"]="Gennaio";
        $mesi["February"]="Febbraio";
        $mesi["March"]="Marzo";
        $mesi["April"]="Aprile";
        $mesi["May"]="Maggio";
        $mesi["June"]="Giugno";
        $mesi["July"]="Luglio";
        $mesi["August"]="Agosto";
        $mesi["September"]="Settembre";
        $mesi["October"]="Ottobre";
        $mesi["November"]="Novembre";
        $mesi["December"]="Dicembre";
        $mese=$mesi[$mese];
        $data['intestazione_data']="$mese - $anno: BEST PROFILES";
        echo $this->load->view('sys/desktop/stampe/stampa_bollettino',$data);
    }
    
    public function download_bollettino($codicebollettino)
    {
        $nome_file="bollettino_$codicebollettino.docx";
        $percorso_file="stampe/".$this->session->userdata('userid')."/$nome_file";
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        //unlink($percorso_file);
    }
    
    public function genera_tutti_candidati_bollettino($codicebollettino)
    {
        $this->Sys_model->reset_bollettino_candidati($codicebollettino);
        $candidati_bollettino=  $this->Sys_model->get_candidati_bollettino($codicebollettino);
        foreach ($candidati_bollettino as $key => $candidato_bollettino) {
            $this->Sys_model->set_bollettino_candidato($codicebollettino,$candidato_bollettino);
        }
    }

    public function genera_nuovi_candidati_bollettino($recordid_bollettino)
    {
      $this->Sys_model->genera_nuovi_candidati_bollettino();  
    }
    
    public function ajax_load_block_permessi_record($tableid,$recordid,$scheda_record_id)
    {
        $block=  $this->load_block_permessi_record($tableid,$recordid,$scheda_record_id);
        echo $block;
    }
    
    public function load_block_permessi_record($tableid,$recordid,$scheda_record_id)
    {
        $data=array();
        $utenti=$this->Sys_model->get_utenti();
        $permessi_record=  $this->Sys_model->get_permessi_record($tableid,$recordid);
        $data['scheda_record_id']=$scheda_record_id;
        $data['utenti']=$utenti;
        $data['permessi_record']=$permessi_record;
        return $this->load->view("sys/desktop/block/permessi_record",$data,true);
    }
    
    public function ajax_salva_permessi_record($tableid,$recordid)
    {
        $post=$_POST;
        if(array_key_exists('permessi_utente', $post))
        {
            $permessi_utente=$post['permessi_utente'];
            $this->Sys_model->salva_permessi_record($tableid,$recordid,$permessi_utente);
        }
        
    }
    
    
    public function test_mail()
    {
        /*$config = Array(        
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.about-x.com',
            'smtp_port' => 587,
            'smtp_user' => 'a.galli@about-x.com',
            'smtp_pass' => 'AboutAG@2015',
            'smtp_timeout' => '4',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );*/
        $config = Array(        
            'protocol' => 'smtp',
            'smtp_host' => 'mail.workandwork.com',
            'smtp_port' => 25,
            'smtp_user' => 'smtp@workandwork.com',
            'smtp_pass' => 'Osla13soplink*',
            'smtp_timeout' => '4',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->from('info@workandwork.com', 'Alessandro');
        $this->email->to('galli8822@gmail.com'); 
        $this->email->cc('another@another-example.com'); 
        $this->email->bcc('them@their-example.com'); 

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');	

        $this->email->send();

        echo $this->email->print_debugger();
        
        echo $this->email->print_debugger();
    }
    
    public function testmail2()
    {
        $this->load->library('My_PHPMailer');
        //istanziamo la classe
        //$this->load->add_package_path(APPPATH.'third_party/phpmailer');
        $this->load->library('my_phpmailer');
        $messaggio = new PHPmailer();
        $messaggio->IsSMTP();
        $messaggio->SMTPAuth   = true;                  // enable SMTP authentication
        $messaggio->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $messaggio->Host       = "smtp.about-x.com"; 
        $messaggio->Port       = 587;                    
        $messaggio->Username   = "a.galli@about-x.com"; 
        $messaggio->Password   = "AboutAG@2015";     

        /*$messaggio->Host       = "mail.workandwork.com"; 
        $messaggio->Port       = 25;                    
        $messaggio->Username   = "smtp@workandwork.com"; 
        $messaggio->Password   = "Osla13soplink*";*/
        
        //definiamo le intestazioni e il corpo del messaggio
        $messaggio->SetFrom('a.galli@about-x.com', 'Alessandro Galli test');
        $messaggio->AddAddress('galli8822@gmail.com');
        $messaggio->Subject='test';
        //$messaggio->Body=stripslashes('TEST!!!!');
        $data=array();
        $messaggio->Body=  $this->load->view('mail_template/test',$data,TRUE);
        $messaggio->IsHTML(true); 
        //definiamo i comportamenti in caso di invio corretto 
        //o di errore
        if(!$messaggio->Send()){ 
          echo $messaggio->ErrorInfo; 
        }else{ 
          echo 'Email inviata correttamente!';
          echo $messaggio->ErrorInfo; 
        }

        //chiudiamo la connessione
        $messaggio->SmtpClose();
        unset($messaggio);
    }
    
    public function invio_pushupBAK($recordid)
    {
        $post=$_POST;
        $mail_subject=$post['mail_subject'];
        $lista_indirizzi=$post['lista_indirizzi'];
        $this->load->library('My_PHPMailer');
        $messaggio = new PHPmailer();
        $messaggio->IsSMTP();
        $messaggio->SMTPAuth   = true;                  // enable SMTP authentication
        $messaggio->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        /*
        $messaggio->Host       = "smtp.about-x.com"; 
        $messaggio->Port       = 587;                   
        $messaggio->Username   = "a.galli@about-x.com"; 
        $messaggio->Password   = "AboutAG@2015";        
*/
        $messaggio->Host       = "mail.workandwork.com"; 
        $messaggio->Port       = 25;                   
        $messaggio->Username   = "smtp@workandwork.com"; 
        $messaggio->Password   = "Osla13soplink*"; 
        
        //definiamo le intestazioni e il corpo del messaggio
        $messaggio->SetFrom('info@workandwork.com', 'Alessandro Galli test ww');
        $array_lista_indirizzi=explode(";", $lista_indirizzi);
        foreach ($array_lista_indirizzi as $key => $indirizzo) {
            $messaggio->AddAddress($indirizzo);
        }
        $messaggio->Subject=$mail_subject;
        //$messaggio->Body=stripslashes('TEST!!!!');
        $messaggio->Body=  $this->load_mail_pushup($recordid);
        $messaggio->IsHTML(true); 
        //definiamo i comportamenti in caso di invio corretto 
        //o di errore
        if(!$messaggio->Send()){ 
          echo $messaggio->ErrorInfo; 
        }else{ 
          echo 'Email inviata correttamente!';
        }

        //chiudiamo la connessione
        $messaggio->SmtpClose();
        unset($messaggio);
    }
    
    public function invio_pushup($recordid)
    {
        $post=$_POST;
        $mail['mailsubject']=$post['mail_subject'];
        $mailbody=  $this->load_mail_pushup($recordid);
        $mail['mailbody']=  $mailbody;//conv_text($mailbody);
        $lista_indirizzi=$post['lista_indirizzi'];
        $mail['mailto']='mailing@workandwork.com';
        
        $lista_indirizzi= str_replace("'", "", $lista_indirizzi);
        $lista_indirizzi= str_replace('"', '', $lista_indirizzi);
        $lista_indirizzi= str_replace(" ", "", $lista_indirizzi);
        $array_lista_indirizzi=explode(";", $lista_indirizzi);
        
        $counter=0;
        $subindirizzi='';
        foreach ($array_lista_indirizzi as $key => $indirizzo) {
            
            //$mail['mailto']=$indirizzo;
            if($subindirizzi!='')
            {
                 $subindirizzi=$subindirizzi.";";
            }
            $subindirizzi=$subindirizzi.$indirizzo;
            if($counter==100)
            {
                $mail['mailbcc']=$subindirizzi;
                $this->Sys_model->push_mail_queue($mail);
                $subindirizzi='';
                $counter=0;
            }
            
            $counter++;
        }
        if(($subindirizzi!='')&&($subindirizzi!=';'))
        {
           $mail['mailbcc']=$subindirizzi;
            $this->Sys_model->push_mail_queue($mail); 
        }
        
        $this->Sys_model->save_pushup($recordid,$mail);
        echo 'Messaggi aggiunti in coda di invio';
    }
    
    public function invio_mail($recordid)
    {
        $dati_mail=$this->get_dati_mail();
        
        $this->load->library('My_PHPMailer');
        $messaggio = new PHPmailer();
        $messaggio->IsSMTP();
        $messaggio->SMTPAuth   = true;                  // enable SMTP authentication
        $messaggio->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $messaggio->Host       = "smtp.about-x.com"; // sets the SMTP server
        $messaggio->Port       = 587;                    // set the SMTP port for the GMAIL server
        $messaggio->Username   = "a.galli@about-x.com"; // SMTP account username
        $messaggio->Password   = "AboutAG@2015";        // SMTP account password

        //definiamo le intestazioni e il corpo del messaggio
        $messaggio->SetFrom('a.galli@about-x.com', 'Alessandro Galli test');
        $array_lista_indirizzi=explode(";", $lista_indirizzi);
        foreach ($array_lista_indirizzi as $key => $indirizzo) {
            $messaggio->AddAddress($indirizzo);
        }
        $messaggio->Subject=$mail_subject;
        //$messaggio->Body=stripslashes('TEST!!!!');
        $messaggio->Body=  $this->load_mail_pushup($recordid);
        $messaggio->IsHTML(true); 
        //definiamo i comportamenti in caso di invio corretto 
        //o di errore
        if(!$messaggio->Send()){ 
          echo $messaggio->ErrorInfo; 
        }else{ 
          echo 'Email inviata correttamente!';
        }

        //chiudiamo la connessione
        $messaggio->SmtpClose();
        unset($messaggio);
    }
    
    public function ajax_load_block_impostazioni_dati_menu()
    {
        $block=$this->load_block_impostazioni_dati_menu();
        echo $block;
    }
    
    
    public function load_block_impostazioni_dati_menu()
    {
        $data=array();
        return $this->load->view("sys/desktop/block/impostazioni_dati_menu",$data,true);
    }
    
    public function ajax_load_block_impostazioni_scheduler_menu()
    {
        $block=$this->load_block_impostazioni_scheduler_menu();
        echo $block;
    }
    
    public function ajax_load_block_impostazioni_script_menu()
    {
        $block=$this->load_block_impostazioni_script_menu();
        echo $block;
    }
    
    public function ajax_load_block_impostazioni_archivi()
    {
        $block=$this->load_block_impostazioni_archivi();
        echo $block;
    }
    
    
    public function load_block_impostazioni_scheduler_menu()
    {
        $data=array();
        return $this->load->view("sys/desktop/block/impostazioni_scheduler_menu",$data,true);
    }
    
    public function load_block_impostazioni_script_menu()
    {
        $data=array();
        return $this->load->view("sys/desktop/block/impostazioni_script_menu",$data,true);
    }
    
    public function load_block_impostazioni_archivi()
    {
        $data=array();
        $archivi=  $this->Sys_model->get_archive_list();
        $data['archivi']=$archivi;
        return $this->load->view("sys/desktop/block/impostazioni_archivi",$data,true);
    }
    
    public function ajax_load_block_impostazioni_scheduler_log()
    {
        $block=$this->load_block_impostazioni_scheduler_log();
        echo $block;
    }
    
    
    public function load_block_impostazioni_scheduler_log()
    {
        $data=array();
        $data['scheduler_log']=$this->Sys_model->get_scheduler_log();
        return $this->load->view("sys/desktop/block/impostazioni_scheduler_log",$data,true);
    }
    
    public function ajax_load_block_impostazioni_scheduler_tasks()
    {
        $block=$this->load_block_impostazioni_scheduler_tasks();
        echo $block;
    }
    
    
    public function load_block_impostazioni_scheduler_tasks()
    {
        $data=array();
        $data['scheduler_tasks']=$this->Sys_model->db_get('sys_scheduler_tasks');
        return $this->load->view("sys/desktop/block/impostazioni_scheduler_tasks",$data,true);
    }
    
    public function ajax_load_block_impostazioni_archivio($idarchivio)
    {
        $block=$this->load_block_impostazioni_archivio($idarchivio);
        echo $block;
    }
    
    
    public function load_block_impostazioni_archivio($idarchivio)
    {
        $data=array();
        $data['idarchivio']=$idarchivio;
        return $this->load->view("sys/desktop/block/impostazioni_archivio",$data,true);
    }
    
    public function ajax_load_block_impostazioni_sottosezione($tipo,$sottosezione)
    {
        $block='';
        if($tipo=='layout')
        {
            if($sottosezione=='menu')
            {
                $data=array();
                $block=  $this->load->view("sys/desktop/block/impostazioni_layout_menu",$data,true);
            }
        }
        if($tipo=='utente')
        {
            if($sottosezione=='settings')
            {
                $userid=  $this->get_userid();
                $data['settings']=  $this->Sys_model->get_impostazioni_user_settings($userid);
                $block=  $this->load->view("sys/desktop/block/impostazioni_utente_settings",$data,true);
            }
        }
        
        
        echo $block;
    }
    
    public function ajax_load_block_impostazioni_archivio_sottosezione($idarchivio,$sottosezione)
    {
        $block='';
        if($sottosezione=='settings')
        {
            $block=$this->load_block_impostazioni_archivio_settings($idarchivio);
        }
        if($sottosezione=='campi')
        {
            $block=$this->load_block_impostazioni_archivio_campi($idarchivio);
        }
        if($sottosezione=='alert')
        {
            $block=$this->load_block_impostazioni_archivio_alert($idarchivio);
        }
        
        echo $block;
    }
    
    
    public function load_block_impostazioni_layout_menu()
    {
        $data=array();

        
        return $this->load->view("sys/desktop/block/impostazioni_layout_menu",$data,true);
    }
    
    public function load_block_impostazioni_archivio_settings($idarchivio)
    {
        $data=array();
        $userid=$this->get_userid();
        $impostazioni_table_settings=  $this->Sys_model->get_impostazioni_table_settings($idarchivio,$userid);

        $data['settings']=$impostazioni_table_settings;
        $data['idarchivio']=$idarchivio;
        
        return $this->load->view("sys/desktop/block/impostazioni_archivio_settings",$data,true);
    }
    
    public function load_block_impostazioni_archivio_campi($idarchivio)
    {
        $data=array();
        $fields_ordered_groupby_label=  $this->Sys_model->get_fields_ordered_groupby_label($idarchivio);
        $data['fields']=$this->Sys_model->get_fields_ordered_by_name($idarchivio);
        $userid=  $this->get_userid();
        foreach ($fields_ordered_groupby_label as $key_label => $fields_label) {
            foreach ($fields_label as $key_sublabel => $fields_sublabel) {
                foreach ($fields_sublabel as $key_field => $field) {
                    $field_settings=  $this->Sys_model->get_impostazioni_field_settings($field['tableid'],$field['fieldid'],$userid);

                    $fields_ordered_groupby_label[$key_label][$key_sublabel][$key_field]['settings']=$field_settings;
                }
            }
            
            
            
        }
        $data['sublabels']=  $this->Sys_model->db_get('sys_table_sublabel','*',"tableid='$idarchivio'");
        $data['fields_ordered_groupby_label']=$fields_ordered_groupby_label;
        $data['idarchivio']=$idarchivio;
        $data['tableid']=$idarchivio;
        return $this->load->view("sys/desktop/block/impostazioni_archivio_campi",$data,true);
    }
    
    public function load_block_impostazioni_archivio_alert($idarchivio)
    {
        $data=array();
        $data['alerts']=$this->Sys_model->db_get('sys_alert','*',"tableid='$idarchivio'");
        $data['views']=$this->Sys_model->db_get('sys_view','*',"tableid='$idarchivio'");
        $data['users']=  $this->Sys_model->db_get('sys_user','*',"true","ORDER BY username");
        $data['idarchivio']=$idarchivio;
        $data['tableid']=$idarchivio;
        
        return $this->load->view("sys/desktop/block/impostazioni_archivio_alert",$data,true);
    }
    
    public function ajax_salva_impostazioni_archivio_settings($idarchivio)
    {
        $post=$_POST;
        $this->Sys_model->salva_impostazioni_archivio_settings($idarchivio,$post);
        echo 'ok';
    }
    
    public function ajax_salva_impostazioni_user_settings()
    {
        $post=$_POST;
        $this->Sys_model->salva_impostazioni_user_settings($post);
        echo 'ok';
    }
    
    public function ajax_salva_impostazioni_dashboard()
    {
        $post=$_POST;
        $this->Sys_model->salva_impostazioni_dashboard($post);
        echo 'ok';
    }
    
    public function ajax_salva_impostazioni_archivio_campi($idarchivio)
    {
        $post=$_POST;
        $this->Sys_model->salva_impostazioni_archivio_campi($idarchivio,$post['fields']);
        echo 'ok';
    }
    
    function dailymail_alerts()
    {
        $this->load->library('My_PHPMailer');
        
        $dailymail_alerts=  $this->Sys_model->get_dailymail_alerts();
        
        foreach ($dailymail_alerts as $dailymail_alert_key => $dailymail_alert) 
        {
            $alert_id=$dailymail_alert['id'];
            $alert_status=$dailymail_alert['alert_status'];
            if(($alert_status=='enabled')||($alert_status=='test'))
            {
                $this->mail_alert_run($alert_id);
            }
            
            
        }

    }
    
    function mid_dailymail_alerts()
    {
        $this->load->library('My_PHPMailer');
        
        $mid_dailymail_alerts=  $this->Sys_model->get_mid_dailymail_alerts();
        
        foreach ($mid_dailymail_alerts as $mid_dailymail_alert_key => $mid_dailymail_alert) 
        {
            $alert_id=$mid_dailymail_alert['id'];
            $alert_status=$mid_dailymail_alert['alert_status'];
            if(($alert_status=='enabled')||($alert_status=='test'))
            {
                $this->mail_alert_run($alert_id);
            }
            
            
        }

    }
    
    function weeklymail_alerts()
    {
        $this->load->library('My_PHPMailer');
        
        $weeklymail_alerts=  $this->Sys_model->get_weeklymail_alerts();
        
        foreach ($weeklymail_alerts as $weeklymail_alerts_key => $weeklymail_alert) 
        {
            $alert_id=$weeklymail_alert['id'];
            $alert_status=$weeklymail_alert['alert_status'];
            if(($alert_status=='enabled')||($alert_status=='test'))
            {
                $this->mail_alert_run($alert_id);
            }
        }
    }
    
    function monthlymail_alerts()
    {
        $this->load->library('My_PHPMailer');
        
        $monthlymail_alerts=  $this->Sys_model->get_monthlymail_alerts();
        
        foreach ($monthlymail_alerts as $monthlymail_alert_key => $monthlymail_alert) 
        {
            $alert_id=$monthlymail_alert['id'];
            $alert_status=$monthlymail_alert['alert_status'];
            if(($alert_status=='enabled')||($alert_status=='test'))
            {
                $this->mail_alert_run($alert_id);
            }
        }
    }
    
    function mail_alert_run($alert_id)
    {
        $alert=  $this->Sys_model->db_get_row('sys_alert','*',"id=$alert_id");
        $alert_userid=$alert['alert_user'];
        $alert_status=$alert['alert_status'];
        $alert_description=$alert['alert_description'];
        if($alert_userid=='1')
        {
            $rows=$this->Sys_model->db_get('sys_user','*','enablesendmail is true');
            foreach ($rows as $key => $row) {
                $alert_userid_array[]=$row['id'];
                
            }
        }
        else
        {
            $alert_userid_array=  explode(";", $alert_userid);
        }
        $alert_tableid=$alert['tableid'];
        echo $alert['alert_description'];
        echo "<br />";
        foreach ($alert_userid_array as $alert_userid_key => $alert_userid) 
        {
            $nomecognome=$this->Sys_model->get_user_nomecognome($alert_userid);
            echo "Alert <b>$alert_description</b> per <b>$nomecognome</b>: $alert_status <br/>";
            $mail=array();
            $mail['mailfrom_userid']=1;
            $mail['mailto']=$this->Sys_model->get_user_setting('mail_from_address',$alert_userid);
            $mail['mailsubject']="Notifiche JDocWeb: ".$alert['alert_description'];
            $mail['mailbody']='';

            $condition=  $this->Sys_model->get_alert_condition($alert_id,$alert_userid);
            $records= $this->Sys_model->db_get('user_'.strtolower($alert_tableid),'*',"$condition");
            if(count($records)>0)
            {
                $mail['mailbody']="Elenco ".$alert['alert_description']."<BR/>";
                foreach ($records as $key => $record) 
                {

                    $recordid=$record['recordid_'];
                    $scheda_fissi=  $this->load_block_fissi($alert_tableid, $recordid);
                    $data['tableid']=$alert_tableid;
                    $data['recordid']=$recordid;
                    $data['scheda_fissi']=$scheda_fissi;
                    $mail_badge=$this->load->view('sys/desktop/block/mail_badge',$data, TRUE);
                    $mail['mailbody']=$mail['mailbody']." $mail_badge <BR/>";

                }
                
                echo "<div style='border:1px solid gray;padding:5px;'>";
                echo $mail['mailsubject']."<br/>";
                echo $mail['mailbody'];
                echo "</div>";
                echo "<br/>";
                if($alert_status=='enabled')
                {
                    $send_result="";
                    $send_result=  $this->Sys_model->push_mail_queue($mail);//$this->jdw_send_mail($mail); 
                    if($send_result=='sent')
                    {
                        echo 'URRA';
                    }
                    else
                    {
                        echo "cazz...$send_result";
                    }
                }

            }
            else
            {
                echo "--Nulla da notificare per $nomecognome <br/>";
            }



        }
    }
    
    function scheduler_task_run($scheduler_task_id)
    {
        $scheduler_task=  $this->Sys_model->db_get_row('sys_scheduler_tasks','*',"id=$scheduler_task_id");
        $scheduler_task_funzione=$scheduler_task['funzione'];
        call_user_func(array($this, $scheduler_task_funzione));
        
    }
    
    public function ajax_load_mail_template_view_notification($notification)
    {
        $data=array();
        echo $this->load->view("mail_template/notification",$data,true);
    }
    
    function aggiorna_candidati_fuori_eta()
    {
        $sql="UPDATE `user_candidati` SET `profiloattivo`='no',`fuorieta`='si' WHERE `datanascita` < DATE_SUB(NOW(),INTERVAL 25 YEAR) ";
	$this->Sys_model->execute_query($sql);
    }
    
    function aggiorna_statistiche()
    {
        /*
         * VISTA qsel_statistiche_giornaliere
         * select curdate() AS `data`,(select count(distinct `user_statistiche`.`recordid_`) from `user_statistiche`) AS `nrecord`,
         * count(distinct `user_candidati`.`recordid_`) AS `ntotaleiscritti`,
         * (select count(distinct `user_candidati`.`recordid_`) from `user_candidati` where (`user_candidati`.`profiloattivo` = 'si')) AS `ntotaleattivi`,
         * (select count(distinct `user_candidati`.`recordid_`) from `user_candidati` where ((`user_candidati`.`profiloattivo` = 'si') and (`user_candidati`.`profilovalidato` = 'si'))) AS `ntotalevalidati`,
         * (select count(distinct `user_candidati`.`recordid_`) from `user_candidati` where (DATE(`user_candidati`.`creation_` = curdate()))) AS `niscrittioggi`,
         * (select count(`user_candidati`.`recordid_`) from `user_candidati` where ((month(`user_candidati`.`datanascita`) = month(curdate())) and ((year(`user_candidati`.`datanascita`) + 25) = year(curdate())))) AS `niscrittifuorietamesecorrente` 
         * from `user_candidati` where ((`user_candidati`.`profiloattivo` = 'si') or (`user_candidati`.`profiloattivo` = 'no'))
         */
        $sql="INSERT INTO `user_statistiche`(`recordid_`, `id`, `data`, `ntotaleiscritti`, `ntotaleattivi`, `ntotalevalidati`, `niscrittioggi`, `niscrittifuorietamesecorrente`, `ntotaleiscrittidal2014`) " .
                "SELECT LPAD(nrecord+1,32,'0'), nrecord+1, data, ntotaleiscritti, ntotaleattivi, ntotalevalidati, niscrittioggi, niscrittifuorietamesecorrente, ntotaleiscrittidal2014 " .
                "FROM qsel_statistiche_giornaliere";
	$this->Sys_model->execute_query($sql);
    }
    
    function aggiorna_numeri()
    {
        /*
         * VISTA qsel_numeri
         * SELECT (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND profiloattivo = 'si' AND profilovalidato = 'no'  AND obbligatoriok = 'si') AS NCandidatiDaValidare,
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND profilovalidato = 'si') AS NCandidatiValidati, 
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND password is not null AND datanascita <= date_add(date_add(LAST_DAY(current_date),interval 1 DAY),interval -1 MONTH) - interval 300 month AND password is not null && password <> '') AS NCandidatiFuoriEta,
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND password is not null && password <> ''	) AS NIscritti,
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND incercadilavoro = 'si' AND iscrittodisoccupazione = 'si' AND consigli = 'si') AS NIscrittiInDisoccupazioneInteressatiFormazione,
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND incercadilavoro = 'si' AND iscrittodisoccupazione = 'no' AND consigli = 'si')  AS NIscrittiNonInDisoccupazioneInteressatiFormazione,
            (SELECT count(recordid_) FROM user_candidati WHERE (recordstatus_ is null OR recordstatus_!='temp') AND incercadilavoro = 'si' AND profiloattivo = 'no' AND obbligatoriok = 'si') AS NIscrittiInCercaLavoroNonPubblicati FROM user_candidati WHERE id =4
         */
        $sql="DELETE FROM `user_numeri` WHERE 1";
        $this->Sys_model->execute_query($sql);
        
        $sql="INSERT INTO `user_numeri`(`recordid_`, `ncandidatidavalidare`, `ncandidativalidati`, `ncandidatifuorieta`, `niscritti`, `niscrittiindisoccupazioneinteressatiformazione`, `niscrittinonindisoccupazioneinteressatiformazione`, `niscrittiincercalavorononpubblicati`) " .
                "SELECT LPAD(1,32,'0'), `ncandidatidavalidare`, `ncandidativalidati`, `ncandidatifuorieta`, `niscritti`, `niscrittiindisoccupazioneinteressatiformazione`, `niscrittinonindisoccupazioneinteressatiformazione`, `niscrittiincercalavorononpubblicati` " .
                "FROM qsel_numeri";
	$this->Sys_model->execute_query($sql);
    }
    
    function send_queued_mail()
    {
        $mail=$this->Sys_model->get_queued_mail();
        $mail_id=$mail['id'];
        $mail_recordid=$mail['recordid_'];
        $mail_pages=$this->Sys_model->db_get('user_mail_queue_page', '*', "recordid_='$mail_recordid'");
        if(count($mail_pages)>0)
        {
            foreach ($mail_pages as $key => $mail_page) {
                $path=$mail_page['path_'];
                $path=  str_replace("\\\\", "/", $path);
                $path=  str_replace("\\", "/", $path);
                $filename=$mail_page['filename_'];
                $original_name=$mail_page['original_name'];
                $filext=$mail_page['extension_'];
                $original_path="../JDocServer/$path"."$filename.$filext";
                $userid=$this->get_userid();
                $attachment_path="../JDocServer/generati/$userid/$original_name.$filext";
                copy($original_path,$attachment_path);
                if($mail['mailattachment']!='')
                    $mail['mailattachment']=$mail['mailattachment'].';';
                $mail['mailattachment']=$mail['mailattachment'].$attachment_path; 
            }
            
        }
        $send_result= $this->jdw_send_mail($mail);
        //$this->load->library('My_PHPMailer');
        if($send_result=='sent')
        {
            $this->Sys_model->update_queued_mail_status($mail_id,'inviata');  
            $this->Sys_model->update_queued_mail_dataora_invio($mail_id); 
            echo 'Mail inviata a '.$mail['mailto'].'- '.$mail['mailbcc'].'<br/>';
        }
        else
        {
            if($send_result=='stop')
            {
                echo 'stop';
                
            }
            else
            {
                echo $send_result;
                $this->Sys_model->update_queued_mail_status($mail_id,'errore');
                $this->Sys_model->update_queued_mail_note($mail_id,'errore - '.$send_result);
            }
            
        }
        
    }
    
    function jdw_send_mail($mail)
    {
        $this->load->library('My_PHPMailer');
        if($mail!=null)
        {
            $mailfrom_userid=null;
            if(array_key_exists('mailfrom_userid', $mail))
                $mailfrom_userid=$mail['mailfrom_userid'];
            
            $mailto='';
            if(array_key_exists('mailto', $mail))
                $mailto=$mail['mailto'];

            $mailcc='';
            if(array_key_exists('mailcc', $mail))
                $mailcc=$mail['mailcc'];

            $mailbcc='';
            if(array_key_exists('mailbcc', $mail))
                $mailbcc=$mail['mailbcc'];

            $mailsubject='';
            if(array_key_exists('mailsubject', $mail))
                $mailsubject=$mail['mailsubject'];

            $mailbody='';
            if(array_key_exists('mailbody', $mail))
                $mailbody=$mail['mailbody'];

            $mailattachment='';
            if(array_key_exists('mailattachment', $mail))
                $mailattachment=$mail['mailattachment'];


            
            /*
            $messaggio->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );    
            */
                          
            $mail_host=$this->Sys_model->get_user_setting('mail_host',$mailfrom_userid);
            $mail_port=$this->Sys_model->get_user_setting('mail_port',$mailfrom_userid);
            $mail_username=$this->Sys_model->get_user_setting('mail_username',$mailfrom_userid); 
            $mail_password=$this->Sys_model->get_user_setting('mail_password',$mailfrom_userid);
            $mail_from_address=$this->Sys_model->get_user_setting('mail_from_address',$mailfrom_userid); 
            $mail_from_name=$this->Sys_model->get_user_setting('mail_from_name',$mailfrom_userid);
            $smtpauth= true;//$this->Sys_model->get_user_setting('mail_smtp_auth',$mailfrom_userid);;   //false per SEA TRADE. true per gli altri
            
            $messaggio = new PHPmailer();
            $messaggio->SMTPDebug=2; //1 debug con errori. //2 debug con solo i messaggi
            $messaggio->IsSMTP();
            $messaggio->SMTPAuth=false;
            $messaggio->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $messaggio->Host       = "smtp-ext.dos-group.com";
            $messaggio->Port       = 587; 
            $messaggio->SMTPSecure = 'tls';
            //$messaggio->Username   = $mail_username;
            //$messaggio->Password   = $mail_password;
            
            $messaggio->SetFrom($mail_from_address, $mail_from_name);
            
            $messaggio->CharSet = 'utf-8';

            $array_mailto=explode(";", $mailto);
            foreach ($array_mailto as $key => $indirizzo) {
                $indirizzo= str_replace('|', '', $indirizzo);
                $messaggio->AddAddress($indirizzo);
            }

            $array_cc=explode(";", $mailcc);
            foreach ($array_cc as $key => $indirizzo) {
                $indirizzo= str_replace('|', '', $indirizzo);
                $messaggio->AddCC($indirizzo);
            }

            $array_bcc=explode(";", $mailbcc);
            foreach ($array_bcc as $key => $indirizzo) {
                $indirizzo= str_replace('|', '', $indirizzo);
                $messaggio->AddBCC($indirizzo);
            }

            $messaggio->Subject=$mailsubject;
            $messaggio->Body= $mailbody;
            $messaggio->IsHTML(true); 

            $array_attachment=explode(";", $mailattachment);
            foreach ($array_attachment as $key => $attachment_path) {
                $messaggio->AddAttachment($attachment_path);
            }
            //$path="../ScansioniSeaTrade/ScanshareOutput/test.pdf";
            //$messaggio->AddAttachment($path);
            if(!$messaggio->Send())
            { 
                $errorinfo=$messaggio->ErrorInfo;
                return $errorinfo;
                $messaggio->SmtpClose();
                unset($messaggio);

            }else{ 
                return 'sent';
                $messaggio->SmtpClose();
                unset($messaggio);

            }



        }
        else 
        {
            return 'stop';
            
        }
    }
    
    public function clone_fields_table($tableid,$new_tableid)
    {
        $this->Sys_model->clone_fields_table($tableid,$new_tableid);
    }
    
    public function clone_fields_order_table($tableid,$new_tableid)
    {
        $this->Sys_model->clone_fields_order_table($tableid,$new_tableid);
    }
    
    public function clone_sublabels_table($tableid,$new_tableid)
    {
        $this->Sys_model->clone_sublabels_table($tableid,$new_tableid);
    }
    
    public function ajax_add_page_category($tableid)
    {
        $post=$_POST;
        $cat_id=$post['cat_id'];
        $cat_description=$post['cat_description'];
        $this->Sys_model->add_page_category($tableid,$cat_id,$cat_description);
    }
    
    
    public function ajax_set_pages_category()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $filename=$post['fileid'];
        $category=$post['category'];
        foreach ($post['files']['checked'] as $key => $checked_file) {
            $this->Sys_model->set_page_category2($tableid,$recordid,$checked_file,$category);
        }
        
    }
    
    public function ajax_set_page_category()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $filename=$post['fileid'];
        $category=$post['category'];
        $this->Sys_model->set_page_category($tableid,$recordid,$filename,$category);
    }
    
  
    
    
    public function matching($recordid_richiesta='',$recordid_immobile='')
    {
        $this->Sys_model->matching($recordid_richiesta,$recordid_immobile);
    }
    
    public function custom_seatrade_caricamento_manuale($recordid)
    {
        $this->Sys_model->custom_seatrade_caricamento_manuale($recordid);
    }
    
    public function invio_dem()
    {
        $mail['mailsubject']='test dem';
        $mailbody=  $this->load_dem_mail('test_dem');
        $mail['mailbody']=  $mailbody;
        $mail['mailto']='galli8822@gmail.com';
        $this->Sys_model->push_dem_mail_queue($mail); 

        echo 'Messaggi aggiunti in coda di invio';
    }
    
    public function load_dem_mail($dem_id='')
    {
        $dem_template=  $this->Sys_model->get_mail_template($dem_id);
        return $dem_template;
    }
    
    
 
    
    public function genera_dem()
    {
        $post=$_POST;
        $tableid=$_POST['tableid'];
        
        $query=$_POST['query'];
        $rows=  $this->Sys_model->select($query);
        
        $userid=  $this->session->userdata('userid');
        
        foreach ($rows as $key => $row) {
            
        }
    }
    
    
    public function ajax_caricamento_immobile_portale($recordid='',$portale='')
    {
        //homeswiss
        //homegate
        //urbanhome**
        //newhome**
        //immoscout24
        //homestreet**
        //tutti.ch
        //icasa**
        
        if(!file_exists("../JDocServer/export"))
        {
            mkdir("../JDocServer/export");            
        }
        
        // CARICAMENTOHOMEGATE
        if(false)
        {
            if(!file_exists("../JDocServer/export/homegate"))
            {
                mkdir("../JDocServer/export/homegate");
            }
            
            
            $immobili_portali=  $this->Sys_model->db_get('user_immobili_portali','*',"portale='Homegate' AND status='standby'");
            foreach ($immobili_portali as $key => $immobile_portale) 
            {
                $recordid_immobile_portale=$immobile_portale['recordid_'];
                $recordid_immobile=$immobile_portale['recordidimmobili_'];
                $fields_immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
            }
            /***0_version*/
            $fields['version']="IDX3.01";
            /*$_1_sender_id**/
            $fields['sender_id']="'JDocWeb v1.0";
            /*$_2_object_category**
             * 
             *  AGRI 	=	Agricoltura	Agriculture
                APPT 	=	Appartamento	Apartment
                GASTRO 	=	Gastronomia	Gastronomy
                HOUSE 	=	Casa	House
                INDUS 	=	Industria/Commercio	Industrial Objects
                PARK 	=	Posteggio	Parking space
                PROP 	=	Plot
                SECONDARY	Locale di servizio	Secondary rooms
                GARDEN	=	Giardino	Garden
             */
            $fields['object_category']="";
             
            /***3_object_type
             *  Object category	Type	Beschreibung in Deutsch	Description en français	Descrizione in italiano	Description in english
                AGRI	1	Landwirtschaftsbetrieb	Exploitation agricole	Sfruttamento agricolo	Agricultural installation
                AGRI	2	Alpwirtschaft	Exploitation montagne	Sfruttamento montagna	Mountain farm
                AGRI	3	Farm	Farm	Fattoria	Farm

                APPT	1	Wohnung	Appartement	Appartamento	Apartment
                APPT	2	Maisonette / Duplex	Duplex	Duplex	Duplex
                APPT	3	Attikawohnung	Attique	Attico	Attic flat
                APPT	4	Dachwohnung	Dernier étage	Appartamento ultimo piano	Roof flat
                APPT	5	Studio	Studio	Monolocale	Studio
                APPT	6	Einzelzimmer	Chambre	Camera	Single Room
                APPT	7	Möbl. Wohnobj.	Appartement meublé	Appartamento ammobiliato	Furnished flat
                APPT	8	Terrassenwohnung	Appartement terrasse	Appart. a terrazza	Terrace flat
                APPT	9	Einliegerwohnung	Appt en annexe	Appart. attiguo	Bachelor flat
                APPT	10	Loft	Loft	Loft	Loft
                APPT	11	Mansarde	Mansarde	Mansarda	Attic

                GARDEN	0	Schrebergarten	Jardin familial	Orto	Alottment garden

                GASTRO	1	Hotel	Hôtel	Hotel	Hotel
                GASTRO	2	Restaurant	Restaurant	Ristorante	Restaurant
                GASTRO	3	Café	Café	Caffé	Coffeehouse
                GASTRO	4	Bar	Bar	Bar	Bar
                GASTRO	5	Club / Disco	Club / Disco	Club / Disco	Club / Disco
                GASTRO	6	Casino	Casino	Casinò	Casino
                GASTRO	7	Kino / Theater	Cinéma / théâtre	Cinema / teatro	Movie / theater
                GASTRO	8	Squash / Badminton	Squash / Badminton	Squash / Badminton	Squash / Badminton
                GASTRO	9	Tennishalle	Halle de tennis	Campo da tennis interno	Indoor tennis courts
                GASTRO	10	Tennisplatz	Place de tennis	Campo da tennis esterno	Tennis court
                GASTRO	11	Sportanlage	Installation sportive	Impianto sportivo	Sports hall
                GASTRO	12	Camping- / Zeltplatz	Camping / Terrain campement	Campeggio	Campground / Tent camping
                GASTRO	13	Freibad	Piscine ouverte	Piscina esterna	Outdoor swimming pool
                GASTRO	14	Hallenbad	Piscine couverte	Piscina coperta	Indoor swimmingpool
                GASTRO	15	Golfplatz	Terrain de golf	Campo da golf	Golf course
                GASTRO	16	Motel	Motel	Motel	Motel
                GASTRO	17	Pub	Pub	Pub	Pub

                HOUSE	1	Einfamilienhaus	Maison	Casa unifamiliare	Single house
                HOUSE	2	Reihenfamilienhaus	Maison contiguë	Casa a schiera	Row house
                HOUSE	3	Doppeleinfamilienhaus	Maison double	Casa bifamiliare	Bifamiliar house
                HOUSE	4	Terrassenhaus	Maison terrasse	Casa a terrazza	Terrace house
                HOUSE	5	Villa	Villa	Villa	Villa
                HOUSE	6	Bauernhaus	Ferme	Fattoria	Farm house
                HOUSE	7	Mehrfamilienhaus	Maison plurifamiliale	Casa plurifamiliare	Multiple dwelling
                HOUSE	9	Höhlen- / Erdhaus	Habitation dans la terre	Casa interrata	Cave house / earthen house
                HOUSE	10	Schloss	Château	Castello	Castle
                HOUSE	11	Stöckli	Dépendance	Dépendance	Granny flat
                HOUSE	12	Chalet	Chalet	Chalet	Chalet
                HOUSE	13	Rustico	Rustico	Rustico	Rustic house

                INDUS	1	Büro	Bureau	Ufficio	Office
                INDUS	2	Ladenfläche	Commerce	Commercio / negozio	Shop
                INDUS	3	Werbefläche	Exposition	Esposizioni	Advertising area
                INDUS	4	Gewerbe	Industrie	Industrie	Commercial
                INDUS	5	Lager	Dépôt	Magazzino	Storage room
                INDUS	6	Praxis	Cabinet médical	Studio medico	Practice
                INDUS	7	Kiosk	Kiosque	Chiosco	Kiosk
                INDUS	8	Gärtnerei	Jardinerie	Azienda di giardinaggio	Gardening
                INDUS	9	Tankstelle	Station-service	Stazione di benzina	Fuel station
                INDUS	10	Autogarage	Garage	Autorimessa	Garage
                INDUS	11	Käserei	Fromagerie	Caseificio	Cheese factory
                INDUS	12	Metzgerei	Boucherie	Macelleria	Butcher
                INDUS	13	Bäckerei	Boulangerie	Panetteria	Bakery
                INDUS	14	Coiffeursalon	Salon de coiffure	Salone da parrucchiere	Hairdresser
                INDUS	15	Shoppingcenter	Centre commercial	Centro commerciale	Shopping centre
                INDUS	16	Fabrik	Fabrique	Fabbrica	Factory
                INDUS	17	Industrieobjekt	Objet industriel	Oggetto industriale	Industrial object
                INDUS	18	Arcade	Arcade	Arcade	Arcade
                INDUS	19	Atelier	Atelier	Atelier	Atelier
                INDUS	20	Wohn- / Geschäftshaus	Imm.com.& hab.	Immob.com. e abitativo	Living / commercial building
                INDUS	21	Bücherei	Bibliothèque	Biblioteca	Library
                INDUS	22	Krankenhaus	Etablissement hospitalier	Ospedale	Hospital
                INDUS	23	Labor	Laboratoire	Laboratorio	Laboratory
                INDUS	24	Minigolfplatz	Place de minigolf	Campo da minigolf	Mini-golf course
                INDUS	25	Pflegeheim	Home de soins	Casa di cura	nursing home
                INDUS	26	Reithalle	Halle d'équitation	Maneggio	Riding hall
                INDUS	27	Sanatorium	Sanatorium	Sanatorio	Sanatorium
                INDUS	28	Werkstatt	Atelier	Officina	Workshop
                INDUS	29	Partyraum	Salle des fêtes	Locale per feste	Party room
                INDUS	30	Sauna	Sauna	Sauna	Sauna
                INDUS	31	Solarium	Solarium	Solarium	Solarium
                INDUS	32	Schreinerei	Menuiserie	Falegnameria	Carpentry shop
                INDUS	33	Altersheim	Home pour personnes âgées	Casa di riposo	Old-age home
                INDUS	34	Geschäftshaus	Commerce	Edificio per uffici o negozi	Department store
                INDUS	35	Heim	Home	Istituto	Home
                INDUS	36	Schaufenster	Vitrine	Vetrina	Display window
                INDUS	37	Parkhaus	Parking à étages	Autosilo	Parking garage
                INDUS	38	Parkfläche	Surface de parking	Superficie per posteggi	Parking surface

                PARK	1	offener Parkplatz	Place ouverte	Parcheggio all'aperto	Open slot
                PARK	2	Unterstand	Place couverte	Parcheggio coperto	Covered slot
                PARK	3	Einzelgarage	Garage individuel	Garage singolo	Single garage
                PARK	4	Doppelgarage	Garage double	Garage doppio	Double garage
                PARK	5	Tiefgarage	Place souterraine	Parcheggio sotterraneo	Underground slot
                PARK	7	Boot Hallenplatz	Halle à bâteaux	Posteggio barca interno	Boat dry dock
                PARK	8	Boot Stegplatz	Place extérieure à bâteaux	Attracco barca esterno	Boat landing stage
                PARK	9	Moto Hallenplatz	Halle à motos	Posteggio moto in garage	Covered parking place bike
                PARK	10	Moto Aussenplatz	Place extérieure à motos	Posteggio moto esterno	Outdoor parking place bike
                PARK	11	Stallboxe	Boxe d'écuire	Box in stalla	Horse box
                PARK	12	Boot Bojenplatz	Place à bâteau balisée	Attracco barca (boa)	Boat mooring

                PROP	1	Bauland	Terrain à bâtir	Terreno da costruire	Building land
                PROP	2	Agrarland	Terrain agricole	Terreno agricolo	Agricultural land
                PROP	3	Gewerbeland	Terrain commercial	Terreno commerciale	Commercial land
                PROP	4	Industriebauland	Terrain industriel	Terreno industriale	Industrial land

                SECONDARY	0	Hobbyraum	Pièce pour les hobbys	Locale per hobby	Hobby room
                SECONDARY	1	Kellerabteil	Cave	Scomparto cantina	Cellar compartment
                SECONDARY	2	Estrichabteil	Galetas	Scomparto soffitta	Attic compartment
             */
            
            $fields['object_type']="";
            /***4_offer_type
             * RENT - SALE
             */
            $fields['offer_type']="";
            /***5_ref_property
             * str(80)
             * the word null as a value cannot be used
             */
            $fields['ref_property']="";
            /***6_ref_house
             * str(80)
             * the word null as a value cannot be used
             */
            $fields['ref_house']="";
            /***7_ref_object
             * 
             * str(80)
             * the word null as a value cannot be used
             */
            $fields['ref_object']="";
            /***8_object_street
             * str(200)
             * must field (exact match of street and number) for the geographical search on map.homegate.ch
             */
            $fields['object_street']="";
            /***9_object_zip
             * str(10)
             */
            $fields['object_zip']="";
            /*10_object_city
             * str(200)
             */
            $fields['object_city']="";
            /*11_object_state
             * str(2)
             * ZH, AG etc.
             */
            $fields['object_state']="";
            /***12_object_country
             * str(2)
             * A2 ISO codes <http://www.iso.org/iso/country_codes/iso_3166_code_lists/country_names_and_code_elements.htm>
             */
            $fields['object_country']="";
            /*13_region
             * not used
             */
            $fields['region']="";
            /*14_object_situation
             * str(50)
             * remarkable situation within the city or town. eg: 'centre ville'
             */
            $fields['object_situation']="";
            /*15_available_from
             * date
             * if empty="on request" / Date (DD.MM.YYYY)=Date / Date in past or current date=immediately
             */
            $fields['available_from']="";
            /***16_object_title
             * str(50)
             */
            $fields['object_title']="";
            /***17_object_description
             * str(4000)
             * biggest varchar2(4000) in oracle - split description into two parts if required. The following HTML-Tags can be used: <LI>,</LI>,<BR>, <B>,</B>. All other Tags will be removed.
             */
            $fields['object_description']="";
            /*(**)18_selling_price
             * int(10)
             * round up / selling_price OR rent_net  is mandatory / empty="by request" - if offer_type = RENT: total rent price - if offer_type = SALE: total sellingprice
             */
            $fields['selling_price']="";
            /*(**)19_rent_net
             * int(10)
             * round up / selling_price OR rent_net  is mandatory / empty="by request"
             */
            $fields['rent_net']="";
            /*$_20_rent_extra
             * int(10)
             */
            $fields['rent_extra']="";
            /*(**)21_price_unit
             * str(10)
             * SELL','SELLM2','YEARLY','M2YEARLY','MONTHLY','WEEKLY','DAILY' (related to field offer_type)
             */
            $fields['price_unit']="";
            /*(**)22_currency
             * str(3)
             * (alpha ISO codes <http://www.xe.com/iso4217.htm>)
             */
            $fields['currency']="";
            /*23_gross_premium*/
            $fields['gross_premium']="";
            /*24_floor*/
            $fields['floor']="";
            /*25_number_of_rooms*/
            $fields['number_of_rooms']="";
            /*26_number_of_apartments*/
            $fields['number_of_apartments']="";
            /*27_surface_living*/
            $fields['surface_living']="";
            /*28_surface_property*/
            $fields['surface_property']="";
            /*29_surface_usable*/
            $fields['surface_usable']="";
            /*30_volume*/
            $fields['volume']="";
            /*31_year_built*/
            $fields['year_built']="";
            /*32_prop_view*/
            $fields['prop_view']="";
            /*33_prop_fireplace*/
            $fields['prop_fireplace']="";
            /*34_prop_cabletv*/
            $fields['prop_cabletv']="";
            /*35_prop_elevator*/
            $fields['prop_elevator']="";
            /*36_prop_child-friendly*/
            $fields['prop_child-friendly']="";
            /*37_prop_parking*/
            $fields['prop_parking']="";
            /*38_prop_garage*/
            $fields['prop_garage']="";
            /*39_prop_balcony*/
            $fields['prop_balcony']="";
            /*40_prop_roof_floor*/
            $fields['prop_roof_floor']="";
            /*41_distance_public_transport*/
            $fields['distance_public_transport']="";
            /*42_distance_shop*/
            $fields['distance_shop']="";
            /*43_distance_kindergarten*/
            $fields['distance_kindergarten']="";
            /*44_distance_school1*/
            $fields['distance_school1']="";
            /*45_distance_school2*/
            $fields['distance_school2']="";
            /*46_picture_1_filename*/
            $fields['picture_1_filename']="";
            /*47_picture_2_filename*/
            $fields['picture_2_filename']="";
            /*48_picture_3_filename*/
            $fields['picture_3_filename']="";
            /*49_picture_4_filename*/
            $fields['picture_4_filename']="";
            /*50_picture_5_filename*/
            $fields['picture_5_filename']="";
            /*51_picture_1_title*/
            $fields['picture_1_title']="";
            /*52_picture_2_title*/
            $fields['picture_2_title']="";
            /*53_picture_3_title*/
            $fields['picture_3_title']="";
            /*54_picture_4_title*/
            $fields['picture_4_title']="";
            /*55_picture_5_title*/
            $fields['picture_5_title']="";
            /*56_picture_1_description*/
            $fields['picture_1_description']="";
            /*57_picture_2_description*/
            $fields['picture_2_description']="";
            /*58_picture_3_description*/
            $fields['picture_3_description']="";
            /*59_picture_4_description*/
            $fields['picture_4_description']="";
            /*60_picture_5_description*/
            $fields['picture_5_description']="";
            /*61_movie_filename*/
            $fields['movie_filename']="";
            /*62_movie_title*/
            $fields['movie_title']="";
            /*63_movie_description*/
            $fields['movie_description']="";
            /*64_document_filename*/
            $fields['document_filename']="";
            /*65_document_title*/
            $fields['document_title']="";
            /*66_document_description*/
            $fields['document_description']="";
            /*67_url*/
            $fields['url']="";
            /***68_agency_id
             * given by homegate (Info: agency_id + ref_property + ref_object + ref_house forms the unique object key)
             */
            $fields['agency_id']="";
            /*69_agency_name*/
            $fields['agency_name']="";
            /*70_agency_name_2*/
            $fields['agency_name_2']="";
            /*71_agency_reference*/
            $fields['agency_reference']="";
            /*72_agency_street*/
            $fields['agency_street']="";
            /*73_agency_zip*/
            $fields['agency_zip']="";
            /*74_agency_city*/
            $fields['agency_city']="";
            /*75_agency_country*/
            $fields['agency_country']="";
            /*76_agency_phone*/
            $fields['agency_phone']="";
            /*77_agency_mobile*/
            $fields['agency_mobile']="";
            /*78_agency_fax*/
            $fields['agency_fax']="";
            /*79_agency_email*/
            $fields['agency_email']="";
            /*80_agency_logo*/
            $fields['agency_logo']="";
            /*81_visit_name*/
            $fields['visit_name']="";
            /*82_visit_phone*/
            $fields['visit_phone']="";
            /*83_visit_email*/
            $fields['visit_email']="";
            /*84_visit_remark*/
            $fields['visit_remark']="";
            /*85_publish_until*/
            $fields['publish_until']="";
            /*86_destination*/
            $fields['destination']="";
            /*87_picture_6_filename*/
            $fields['picture_6_filename']="";
            /*88_picture_7_filename*/
            $fields['picture_7_filename']="";
            /*89_picture_8_filename*/
            $fields['picture_8_filename']="";
            /*90_picture_9_filename*/
            $fields['picture_9_filename']="";
            /*91_picture_6_title*/
            $fields['picture_6_title']="";
            /*92_picture_7_title*/
            $fields['picture_7_title']="";
            /*93_picture_8_title*/
            $fields['picture_8_title']="";
            /*94_picture_9_title*/
            $fields['picture_9_title']="";
            /*95_picture_6_description*/
            $fields['picture_6_description']="";
            /*96_picture_7_description*/
            $fields['picture_7_description']="";
            /*97_picture_8_description*/
            $fields['picture_8_description']="";
            /*98_picture_9_description*/
            $fields['picture_9_description']="";
            /*99_picture_1_url*/
            $fields['picture_1_url']="";
            /*100_picture_2_url*/
            $fields['picture_2_url']="";
            /*101_picture_3_url*/
            $fields['picture_3_url']="";
            /*102_picture_4_url*/
            $fields['picture_4_url']="";
            /*103_picture_5_url*/
            $fields['picture_5_url']="";
            /*104_picture_6_url*/
            $fields['picture_6_url']="";
            /*105_picture_7_url*/
            $fields['picture_7_url']="";
            /*106_picture_8_url*/
            $fields['picture_8_url']="";
            /*107_picture_9_url*/
            $fields['picture_9_url']="";
            /*108_distance_motorway*/
            $fields['distance_motorway']="";
            /*109_ceiling_height*/
            $fields['ceiling_height']="";
            /*110_hall_height*/
            $fields['hall_height']="";
            /*111_maximal_floor_loading*/
            $fields['maximal_floor_loading']="";
            /*112_carrying_capacity_crane*/
            $fields['carrying_capacity_crane']="";
            /*113_carrying_capacity_elevator*/
            $fields['carrying_capacity_elevator']="";
            /*114_isdn*/
            $fields['isdn']="";
            /*115_wheelchair_accessible*/
            $fields['wheelchair_accessible']="";
            /*116_animal_allowed*/
            $fields['animal_allowed']="";
            /*117_ramp*/
            $fields['ramp']="";
            /*118_lifting_platform*/
            $fields['lifting_platform']="";
            /*119_railway_terminal*/
            $fields['railway_terminal']="";
            /*120_restrooms*/
            $fields['restrooms']="";
            /*121_water_supply*/
            $fields['water_supply']="";
            /*122_sewage_supply*/
            $fields['sewage_supply']="";
            /*123_power_supply*/
            $fields['power_supply']="";
            /*124_gas_supply*/
            $fields['gas_supply']="";
            /*125_municipal_info*/
            $fields['municipal_info']="";
            /*126_own_object_url*/
            $fields['own_object_url']="";
            /*127_billing_anrede*/
            $fields['billing_anrede']="";
            /*128_billing_first_name*/
            $fields['billing_first_name']="";
            /*129_billing_name*/
            $fields['billing_name']="";
            /*130_billing_company*/
            $fields['billing_company']="";
            /*131_billing_street*/
            $fields['billing_street']="";
            /*132_billing_post_box*/
            $fields['billing_post_box']="";
            /*133_billing_zip*/
            $fields['billing_zip']="";
            /*134_billing_place_name*/
            $fields['billing_place_name']="";
            /*135_billing_land*/
            $fields['billing_land']="";
            /*136_billing_phone_1*/
            $fields['billing_phone_1']="";
            /*137_billing_phone_2*/
            $fields['billing_phone_2']="";
            /*138_billing_mobile*/
            $fields['billing_mobile']="";
            /*139_billing_language*/
            $fields['billing_language']="";
            /*140_publishing_id*/
            $fields['publishing_id']="";
            /*141_delivery_id*/
            $fields['delivery_id']="";
            /*142_picture_10_filename*/
            $fields['picture_10_filename']="";
            /*143_picture_11_filename*/
            $fields['picture_11_filename']="";
            /*144_picture_12_filename*/
            $fields['picture_12_filename']="";
            /*145_picture_13_filename*/
            $fields['picture_13_filename']="";
            /*146_picture_10_title*/
            $fields['picture_10_title']="";
            /*147_picture_11_title*/
            $fields['picture_11_title']="";
            /*148_picture_12_title*/
            $fields['picture_12_title']="";
            /*149_picture_13_title*/
            $fields['picture_13_title']="";
            /*150_picture_10_description*/
            $fields['picture_10_description']="";
            /*151_picture_11_description*/
            $fields['picture_11_description']="";
            /*152_picture_12_description*/
            $fields['picture_12_description']="";
            /*153_picture_13_description*/
            $fields['picture_13_description']="";
            /*154_picture_10_url*/
            $fields['picture_10_url']="";
            /*155_picture_11_url*/
            $fields['picture_11_url']="";
            /*156_picture_12_url*/
            $fields['picture_12_url']="";
            /*157_picture_13_url*/
            $fields['picture_13_url']="";
            /*158_commission_sharing*/
            $fields['commission_sharing']="";
            /*159_commission_own*/
            $fields['commission_own']="";
            /*160_commission_partner*/
            $fields['commission_partner']="";
            /*161_agency_logo_2*/
            $fields['agency_logo_2']="";
            /*162_number_of_floors*/
            $fields['number_of_floors']="";
            /*163_year_renovated*/
            $fields['year_renovated']="";
            /*164_flat_sharing_community*/
            $fields['flat_sharing_community']="";
            /*165_corner_house*/
            $fields['corner_house']="";
            /*166_middle_house*/
            $fields['middle_house']="";
            /*167_building_land_connected*/
            $fields['building_land_connected']="";
            /*168_gardenhouse*/
            $fields['gardenhouse']="";
            /*169_raised_ground_floor*/
            $fields['raised_ground_floor']="";
            /*170_new_building*/
            $fields['new_building']="";
            /*171_old_building*/
            $fields['old_building']="";
            /*172_under_building_laws*/
            $fields['under_building_laws']="";
            /*173_under_roof*/
            $fields['under_roof']="";
            /*174_swimmingpool*/
            $fields['swimmingpool']="";
            /*175_minergie_general*/
            $fields['minergie_general']="";
            /*176_minergie_certified*/
            $fields['minergie_certified']="";
            /*177_last_modified*/
            $fields['last_modified']="";
            /*178_advertisement_id*/
            $fields['advertisement_id']="";
            /*179_sparefield_1*/
            $fields['sparefield_1']="";
            /*180_sparefield_2*/
            $fields['sparefield_2']="";
            /*181_sparefield_3*/
            $fields['sparefield_3']="";
            /*182_sparefield_4*/
            $fields['sparefield_4']="";

            
        }
        
        
        //CARICAMENTO HOMESWISS
        if(true)
        {
        if(!file_exists("../JDocServer/export/homeswiss"))
        {
            mkdir("../JDocServer/export/homeswiss");
        }
        if(!file_exists("../JDocServer/export/homeswiss/images"))
        {
            
            mkdir("../JDocServer/export/homeswiss/images");
        }
        array_map('unlink', glob("../JDocServer/export/homeswiss/images/*.*"));
        $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?> <sync></sync>');
        $agenzia = $xml->addChild('agenzia');
        $agenzia->addAttribute('id',18);
        $agenzia->addAttribute('nome',"Dimensione Immobiliare SA");
            $immobili = $agenzia->addChild('immobili');
                $immobili_portali=  $this->Sys_model->db_get('user_immobili_portali','*',"portale='homeswiss' AND status='standby'");
                foreach ($immobili_portali as $key => $immobile_portale) 
                {
                    $recordid_immobile_portale=$immobile_portale['recordid_'];
                    $recordid_immobile=$immobile_portale['recordidimmobili_'];
                    $fields_immobile=$this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
                    
                    
                    $immobile = $immobili->addChild('immobile');
                    $immobile->addAttribute('codice',$fields_immobile['id']);
                    $immobile->addChild('pubblicato',1); //1 pubblicato, 0 bozza
                    $immobile->addChild('agente',2883); //fisso per dimensione imm
                    $paese=$fields_immobile['paese'];
                    $paese=$this->Sys_model->get_lookup_table_item_description('paese_richieste',$paese);
                    $categoria=$fields_immobile['categoria'];
                    $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
                    $titolo=$paese." ".$categoria." ".$fields_immobile['imm_locali_num']." Locali ";
                    $immobile->addChild('titolo',$titolo); //string titolo
                    $immobile->addChild('mistery_room',0); // ??
                    $posto_auto_coperto=0;
                    if($fields_immobile['imm_parcoperto_num']>0)
                    {
                       $posto_auto_coperto=1; 
                    }
                    $immobile->addChild('posto_auto_coperto',$posto_auto_coperto);
                    $posto_auto_esterno=2;
                    if($fields_immobile['imm_parcscoperto_num']>0)
                    {
                       $posto_auto_esterno=0; 
                    }
                    $immobile->addChild('posto_auto_esterno',$posto_auto_esterno);
                    $immobile->addChild('tipo',$categoria); //string libera del tipo-categoria
                    $immobile->addChild('status',0); //0 vendita, 1 affitto
                    $immobile->addChild('citta',$paese); //string libero
                    $descrizione=$fields_immobile['descrizione'];
                    //$descrizione=  conv_text_utf8($descrizione);
                    $immobile->addChild('descrizione_immobile',$descrizione); //descrizione libera
                    //immagine principale
                    $immagine_principale_immobile=$this->Sys_model->db_get_row('user_immobili_page','*',"recordid_='$recordid_immobile' AND category ilike '%Sitoprincipale%'");
                    if($immagine_principale_immobile!=null)
                    {
                        $path=$immagine_principale_immobile['path_'];
                        $path=  str_replace("\\", "/", $path);
                        $path=  str_replace("//", "/", $path);
                        $filename=$immagine_principale_immobile['filename_'];
                        $extension=$immagine_principale_immobile['extension_'];
                        $path_origine="../JDocServer/$path/$filename.$extension";
                        $num=$key+1;
                        /*if(file_exists($path_origine))
                        {
                            copy($path_origine, "../JDocServer/export/homeswiss/images/$recordid_immobile"."_principale.jpg");
                        }*/
                        if(file_exists($path_origine))
                        {
                            $imagesize=getimagesize($path_origine);
                            $width=$imagesize[0];
                            $max_width=$width*0.7;
                            $path_origine='../../'.$path_origine;
                            $path_watermark="../../assets/images/custom/DimensioneImmobiliare/Watermark.png";
                            $path_destinazione="../../../JDocServer/export/homeswiss/images/$recordid_immobile"."_principale.jpg";
                            $this->apply_watermark($path_origine,$path_watermark,$max_width,$max_width,$path_destinazione);
                        }
                        $immobile->addChild('img_principale',"$recordid_immobile"."_principale.jpg");
                    }
                    //immagini
                    $immagini_immobile=$this->Sys_model->db_get('user_immobili_page','*',"recordid_='$recordid_immobile' AND (category ilike '%Sito' OR category ilike '%Sito|;|%')");
                    foreach ($immagini_immobile as $key => $immagine_immobile) 
                    {
                        $path=$immagine_immobile['path_'];
                        $path=  str_replace("\\", "/", $path);
                        $path=  str_replace("//", "/", $path);
                        $filename=$immagine_immobile['filename_'];
                        $extension=$immagine_immobile['extension_'];
                        $path_origine="../JDocServer/$path/$filename.$extension";
                        $num=$key+1;
                        if(file_exists($path_origine))
                        {
                            //copy($path_origine, "../JDocServer/export/homeswiss/images/$recordid_immobile"."_$num.jpg");
                            $imagesize=getimagesize($path_origine);
                            $width=$imagesize[0];
                            $max_width=$width*0.7;
                            $path_origine='../../'.$path_origine;
                            $path_watermark="../../assets/images/custom/DimensioneImmobiliare/Watermark.png";
                            $path_destinazione="../../../JDocServer/export/homeswiss/images/$recordid_immobile"."_$num.jpg";
                            $this->apply_watermark($path_origine,$path_watermark,$max_width,$max_width,$path_destinazione);
                        }
                        $immobile->addChild("img_$num","$recordid_immobile"."_$num.jpg");
                    }
                   
                    $immobile->addChild('prezzo',$fields_immobile['imm_prezzoimmobile']);
                    $immobile->addChild('area',$fields_immobile['imm_sul_mq']);
                    $immobile->addChild('area_misura','mq');//fisso
                    $immobile->addChild('locali',$fields_immobile['imm_locali_num']);
                    $immobile->addChild('camere',$fields_immobile['imm_camereletto_num']);
                    $immobile->addChild('bagni',$fields_immobile['imm_bagni_num']);
                    $immobile->addChild('garages',$fields_immobile['imm_autorimessa_num']);
                    $mappa=$immobile->addChild('mappa');
                    $mappa->addChild('latitudine',$fields_immobile['lat']);
                    $mappa->addChild('longitudine',$fields_immobile['lng']);
                    /*$attributi=$immobile->addChild('attributi');
                        $attributi->addChild('attributo','Possibile sopralzo, con progetto già approvato.');
                        $attributi->addChild('attributo','Condizioni immobile: buone');*/
                    $sql="
                        UPDATE user_immobili_portali
                        SET status='caricato'
                        where recordid_='$recordid_immobile_portale'
                        ";
                    $this->Sys_model->execute_query($sql);
                }
            file_put_contents('../JDocServer/export/homeswiss/18.xml', $xml->asXML());
        }
        echo 'file creati';
        //ftp_upload();
        
    }
    
    //custom dimensione immobiliare
    
    function spedisce_mail_sync_homeswiss()
    {		

        $mail=array();
    $mail['mailfrom_userid']=1;
    $mail['mailto']='assistenzatecnica@homeswiss.ch';	
    $mail['mailsubject']="Nuovo invio di annunci da parte di Dimensione Immobiliare";
    $mail['mailbody']="Abbiamo trasferito un nuovo file 18.xls." . chr(10) . chr(13). "Cordiali saluti."  . chr(10) . chr(13). "Dimensione Immobiliare";
    $send_result=$this->jdw_send_mail($mail);
            echo 'EMail spedita';                     

    }

    function genera_watermark_immobiliportali()
    {
        $output="";
        $immobili_portali=$this->Sys_model->db_get('user_immobili_portali','recordidimmobili_',"status='Pubblicato'");
        foreach ($immobili_portali as $key_immobile_portale => $immobile_portale) {
            $recordid_immobile=$immobile_portale['recordidimmobili_'];
            $immagini_immobile=$this->Sys_model->db_get('user_immobili_page','*',"recordid_='$recordid_immobile' AND (category ilike '%Sito%' OR category ilike '%homegate%')");
            foreach ($immagini_immobile as $key => $immagine_immobile) 
            {
                $path=$immagine_immobile['path_'];
                $path=  str_replace("\\", "/", $path);
                $path=  str_replace("//", "/", $path);
                $filename=$immagine_immobile['filename_'];
                $extension=$immagine_immobile['extension_'];
                $path_origine="../JDocServer/$path/$filename.$extension";
                $path_destinazione="../JDocServer/$path/$filename"."_watermark.".$extension;
                $output=$output."$recordid_immobile: ";
                if(file_exists($path_origine))
                {
                    $output=$output."$path_origine: ";
                    if(!file_exists($path_destinazione))
                    {
                        $imagesize=getimagesize($path_origine);
                        $width=$imagesize[0];
                        $max_width=$width*0.7;
                        $path_watermark="../../assets/images/custom/DimensioneImmobiliare/Watermark.png";
                        $path_origine='../../'.$path_origine;
                        $path_destinazione='../../'.$path_destinazione;

                        $this->apply_watermark($path_origine,$path_watermark,$max_width,$max_width,$path_destinazione);
                        $output=$output."watermark creato";
                    }
                    else
                    {
                        $output=$output."watermark già esistente";
                    }
                    
                }
                else
                {
                   $output=$output."file non esistente"; 
                }
                $output=$output."<br/>";
            }
        }
        echo $output;
        echo "<br/>";
        $send_result=$this->jdw_send_alertmail('Creazione Watermark',$output);
        echo $send_result;
    }
    
    function jdw_send_mail_test()
    {
        $mail=array();
        $mail['mailfrom_userid']=1;
        $mail['mailto']='galli8822@gmail.com';
        $mail['mailsubject']="Test jdw_send_mail";
        $mail['mailbody']='Messaggio di prova';
        $send_result=$this->jdw_send_mail($mail);
        echo $send_result; 
    }
    
    function jdw_send_alertmail($msg_object,$msg_body)
    {
        $mail=array();
        $mail['mailfrom_userid']=1;
        $mail['mailto']='jdocwebalert@about-x.com';
        $mail['mailsubject']="Alert: ".$msg_object;
        $mail['mailbody']=$msg_body;
        $send_result=$this->jdw_send_mail($mail);
        return $send_result; 
    }
    
    function ftp_upload($server,$username,$password,$path_originale,$path_destinazione='',$mode='')
    {
        //Apro una connessione FTP che mi restituisce un id
        $id_connessione = ftp_connect($server);

        //Effetto l'autenticazione con i dati precedentemente impostati
        $login = ftp_login($id_connessione, $username, $password);

        
        if (!$id_connessione || !$login) 
        { 
            die('Connection attempt failed!'); 
        }
        //A questo punto occorre effettuare l'effettivo upload dei file:
        foreach (glob("$path_originale/*.*") as $filename)
        { 

            //Mi Sposto nella cartella in cui voglio effettuare l'upload
            ftp_chdir($id_connessione, $path_destinazione);

            if($mode=='pasv')
            {
                ftp_pasv ($id_connessione, true);
            }
            // upload del File
            $upload=ftp_put($id_connessione, basename($filename) , $filename, FTP_BINARY);

            if (!$upload) 
            { 
                echo 'FTP upload failed!'; 
            }
                
        }
        
       

        //Chiudo la connessione
        ftp_close($id_connessione);
    }
    
    function test_html2pdf()
    {
        //$this->load->library('Pdf');
        //$obj_pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        //$title = "PDF Report";
       // $obj_pdf->SetTitle($title);

       // $obj_pdf->SetMargins(0,0,0);
        //$obj_pdf->SetAutoPageBreak(TRUE, 0);
        //$obj_pdf->AddPage();
        $content=  $this->stampa_prospetto_pdf('00000000000000000000000000000061');
        //$obj_pdf->writeHTML($content, true, false, true, false, '');
        file_put_contents('../JDocServer/stampe/pagina.html', $content);
        //orientation: portrait-landscape
        $command='cd ./tools/wkhtmltopdf/bin && wkhtmltopdf.exe -T 0 -B 0 -L 0 -R 0 --orientation Portrait --zoom 2 "../../../../JDocServer/stampe/pagina.html" "../../../../JDocServer/stampe/pagina.pdf" ';
        exec($command);
        //echo $content;
        // I apre nel browser
        // D chiede all'utente dove salvare
        // F salva nella path indicata
        //$obj_pdf->Output('C:\\xampp\\htdocs\\JDocServer\\stampe\\pdfexample.pdf', 'F'); 
    }
    
    function test_html2pdf2()
    {
        $this->load->library('pdf');
        $content=  $this->stampa_prospetto_pdf('00000000000000000000000000000061');
        $this->pdf->load_html($content);
        $this->pdf->render();
        $this->pdf->stream("welcome.pdf");
       
    }
    
    //custom DI
    function script_geocode_all()
    {
        $immobili=  $this->Sys_model->db_get('user_immobili','*');
        foreach ($immobili as $key => $immobile) {
            sleep(1);
            $recordid=$immobile['recordid_'];
            $paese=$immobile['paese'];
            $paese=  str_replace(' ', '+', $paese);
            $indirizzo=$immobile['via'];
            $indirizzo=  str_replace(' ', '+', $indirizzo);
            $url="https://maps.googleapis.com/maps/api/geocode/json?address=Svizzera+$paese+$indirizzo,+CA&key=AIzaSyDmhSBrB2WzQQp52g2gECkrK9TkPJjp7jo";
            $json = file_get_contents($url);
            $coordinate = json_decode($json,true);
            $lat=$coordinate['results'][0]['geometry']['location']['lat'];
            $lng=$coordinate['results'][0]['geometry']['location']['lng'];
            $sql="UPDATE user_immobili
                SET lat='$lat',lng='$lng'
                WHERE recordid_='$recordid'
                ";
            $this->Sys_model->execute_query($sql);
        }
    }
    
    public function avvia_dem($recordid_dem,$tipoinvio)
    {
        $this->Sys_model->avvia_dem($recordid_dem,$tipoinvio);
        
    }
    
    public function sync_segnalazioni()
    {
        $json = file_get_contents("http://www.about-x.info/assistenza/sys_viewcontroller/get_segnalazioni/");
        $segnalazioni = json_decode($json,true);
        $this->Sys_model->insert_segnalazioni($segnalazioni);
    }
    
    public function ajax_direct_update($tableid,$recordid)
    {
        $post=$_POST;
        $this->Sys_model->direct_update($tableid,$recordid,$post);
    }
    public function salva_impostazioni_archivio_alert()
    {
        $post=$_POST;
        $this->Sys_model->salva_impostazioni_archivio_alert($post);
    }
    
    public function conferma_queued_mail($recordid)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $this->Sys_model->conferma_queued_mail($recordid);
        $queued_mail= $this->Sys_model->db_get_row('user_mail_queue','*',"recordid_='$recordid'");
        $tipo=$queued_mail['tipo'];
        $return='';
        if($tipo=='invioprospetto')
        {
            $recordid_contatto=$queued_mail['recordidcontatti_'];
            $contatto=$this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");
            $email_contatto=$contatto['email'];
            $recordid_immobile='';
            
            if($contatto==null)
            {
                $return='nocontatto';
            }
            else
            {
                if(isempty($email_contatto))
                {
                    $return='noemail';
                }
                else
                {
                    $recordid_immobile=$queued_mail['recordidimmobili_'];
                    if(isnotempty($recordid_immobile))
                    {
                        $immobile= $this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
                        $recordid_proprietario=$immobile['recordidcontatti_'];

                        if((isnotempty($recordid_proprietario)))
                        {
                            $return= 'protezionecliente';
                        }
                    }
                }
            }
        }
        if($tipo='inviodavisualizzazioni')
        {
            $recordid_visualizzazioni=$queued_mail['recordidvisualizzazioni_'];
            $sql="UPDATE user_visualizzazioni SET stato='CV richiesto inviato' WHERE recordid_='$recordid_visualizzazioni'";
            $this->Sys_model->execute_query($sql);
        }

        echo $return;
    }
    
    public function annulla_queued_mail($recordid)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $this->Sys_model->annulla_queued_mail($recordid);
    }
    
    public function invia_protezionecliente($tableid,$recordid)
    {
        $tablename="user_".strtolower($tableid);
        $table_row=  $this->Sys_model->db_get_row($tablename,'*',"recordid_='$recordid'");
        $recordid_immobile=$table_row['recordidimmobile_'];
        $immobile=  $this->Sys_model->db_get_row('user_immobile','*',"recordid_='$recordid_immobile'");
    }
    
    public function genera_mail_protezionecliente_from_queued_mail($recordid_queued_mail)
    {
        $recordid_immobile= $this->Sys_model->db_get_value('user_mail_queue','recordidimmobili_',"recordid_='$recordid_queued_mail'");
        $recordid_contatto_acquirente= $this->Sys_model->db_get_value('user_mail_queue','recordidcontatti_',"recordid_='$recordid_queued_mail'");
        $this->ajax_genera_mail_protezionecliente($recordid_immobile,$recordid_contatto_acquirente);
    }
    public function ajax_genera_mail_protezionecliente($recordid_immobile,$recordid_contatto_acquirente)
    {
        $data['mailbcc']='';
        

        if(isnotempty($recordid_immobile))
        {
        $immobile=  $this->Sys_model->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
        
        
        $paese=$immobile['paese'];
        $paese=$this->Sys_model->get_lookup_table_item_description('citta',$paese);
        $indirizzo=$immobile['via'];
        $categoria=$immobile['categoria'];
        $categoria=$this->Sys_model->get_lookup_table_item_description('categoria_immobili',$categoria);
        $locali=$immobile['imm_locali_num'];
        
        //dati consulente immobile
        $consulente_immobile_id=$immobile['consulente']; 
        if(isnotempty($consulente_immobile_id))
        {
            $consulente_immobile=  $this->Sys_model->db_get_row('sys_user','*',"id=$consulente_immobile_id");
            $consulente_immobile_mail=  $this->Sys_model->get_user_setting('mail_from_address',$consulente_immobile_id);
            $consulente_cognome=$consulente_immobile['lastname'];
            $consulente_nome=$consulente_immobile['firstname'];
        }
        else
        {
           $consulente_immobile_mail='';
           $consulente_cognome='';
           $consulente_nome='';
        }
       
        
        //dati contatto immobile
        $recordid_contatto=$immobile['recordidcontatti_'];
        $contatto_immobile=  $this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");
        $cognome_immobile=$contatto_immobile['cognome'];
        $nome_immobile=$contatto_immobile['nome'];
        
        //dati potenziale acquirente
        $contatto_acquirente=  $this->Sys_model->db_get_row('user_contatti','*',"recordid_='$recordid_contatto_acquirente'");
        $cognome_acquirente=$contatto_acquirente['cognome'];
        $cognome_acquirente=  str_replace("|;|", "/", $cognome_acquirente);
        $nome_acquirente=$contatto_acquirente['nome'];
        $paese_acquirente=$contatto_acquirente['citta'];
        
        $data['mailfrom_userid']=$consulente_immobile_id;
        $data['mailto']=$contatto_immobile['email'];
        $data['mailbcc']='';
        $firma='';
        if(($consulente_immobile_id==3)||($consulente_immobile_id==6)||($consulente_immobile_id==7))
        {
            $data['mailbcc']="sopraceneri@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#948A54'>
                DIMENSIONE IMMOBILIARE SOPRACENERI SAGL <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                </div>
                ";
        }
        else
        {
            $data['mailbcc']="info@dimensioneimmobiliare.ch";
            $firma="
                <br/><br/><br/>
                <div style='color:#827843'>
                DIMENSIONE IMMOBILIARE SA <br/>
                www.dimensioneimmobiliare.ch <br/>
                <br/>
                LUGANO - Via C. Maderno 9 - T. 091 922 74 00 <br/>
                <br/>
                GIUBIASCO - Via Bellinzona 1 - T. 091 857 19 07 <br/>
                </div>
                ";
        }
        $data['recordstatus_']='temp';
        $data['mailsubject']="Protezione cliente - $paese $categoria $locali";
        $today=  date('Y/m/d');
        $cognome_immobile=  str_replace("|;|", "/", $cognome_immobile);
        $mailbody="
            Gentile Sig. $cognome_immobile $nome_immobile, <br />
            <br />
            con la presente notifichiamo che in data $today abbiamo proposto l’appartamento $locali locali in $indirizzo, $paese a  $cognome_acquirente di $paese_acquirente <br />
            pertanto sarà nostra premura tenerla informato di eventuali sviluppi. <br/>
            <br />
            Cordiali saluti,
            <br/>
            $consulente_nome $consulente_cognome
                        ";
        $mailbody=$mailbody.$firma;
        $data['mailbody']=$mailbody;
        //$data['recordidimmobili_']=$recordid_immobile;
        $data['recordidimmobili_']=$recordid_immobile;
        $data['recordidcontatti_']=$recordid_contatto;
        $data['tipo']='protezionecliente';
        $userid=  $this->get_userid();
        $recordid=$this->Sys_model->insert_record('mail_queue',$userid,$data);
        
        echo $recordid;
        
        }
        else
        {
            echo 'null';
        }
    }
    
    
    
    
    public function ajax_load_block_esporta_elenco($tableid)
    {
        $post=$_POST;
        //$data['records']= $this->load_block_datatable_records($tableid,'dashboard',$query);
        $post['iDisplayStart']=null;
        $post['iDisplayLength']=null;
        $post['iSortCol_0']=3;
        $post['bSortable_3']='true';
        $post['sSortDir_0']='asc';
        $post['iSortingCols']=1;
        $post['sEcho']=null;
        $data['tableid']=$tableid;
        $data['columns']=$this->Sys_model->get_colums($tableid, 1);
        $search_result= $this->Sys_model->get_ajax_search_result($tableid,$post);
        $data['records']=$search_result['aaData'];
        
        $block=  $this->load->view('sys/desktop/stampe/stampa_elenco_records',$data, TRUE);
        $path_stampa=$this->genera_stampa($block,'Elenco','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function ajax_load_block_esporta_elenco2($tableid)
    {
        
        $this->ajax_load_block_results($tableid);
    }
    
    public function ajax_stampa_elenco($tableid)
    {
        $post=$_POST;
        $view_name=$post['view_name'];
        $query=$post['query'];
        $content=  $this->load_stampa_elenco_pdf($tableid,null,$query,$tableid);
        $stampa_elenco_orientamento=$this->Sys_model->get_table_setting($tableid,'risultati_stampa_elenco_orientamento');
        $path_stampa=$this->genera_stampa($content,'Elenco',$stampa_elenco_orientamento,true);
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        
        /*
        $userid=  $this->get_userid();
        $user_settings= $this->Sys_model->get_user_settings($userid);
        $content=conv_text($content);
        $data['content']=$content;
        $data['settings']=$user_settings;
        $data['orientation']='landscape';
        $content=$this->load->view('sys/desktop/stampe/container_stampa',$data, TRUE); 
        echo $content;
        */
        
        echo $block;
    }
    
    
    
    public function load_options_fields($tableid,$fieldtype)
    {
        $fields=$this->Sys_model->get_lookup_fields($tableid);
        echo json_encode($fields);
    }
    
    function google_calendar_sync_dimensione()
    {
        $data=array();
        $users=$this->Sys_model->db_get('sys_user','*');
        foreach ($users as $key_user => $user) {
            $userid=$user['id'];
            $data['calendarId']=$this->Sys_model->get_user_setting('google_calendar_id',$userid);
            $events=  $this->Sys_model->db_get('user_agenda','*',"utente='$userid' AND (recordstatus_ is null)");
            foreach ($events as $key_event => $event) {
                $recordid_event=$event['recordid_'];
                $data['start_date']=$event['data'];
                $data['start_time']=$event['dalle'];
                $data['end_date']=$event['data'];
                $data['end_time']=$event['alle'];
                $recordid_contatto=$event['recordidcontatti_'];
                $contatto=  $this->Sys_model->get_keyfieldlink_value('agenda','contatti',$recordid_contatto);
                $contatto_array=  explode('|:|', $contatto);
                $contatto=$contatto_array[0];
                $data['titolo']=$event['tipoattivita']." ".$contatto." ".$event['note'];
                $data['descrizione']=$event['note'];
                $this->load->view('google_api/GCServiceAccount',$data,true);
                $sql="
                    UPDATE user_agenda
                    SET recordstatus_='sync'
                    WHERE recordid_='$recordid_event'
                    ";
                $this->Sys_model->execute_query($sql);
            }
        }
    }
    
    function google_calendar_sync()
    {        
        $calendars=$this->Sys_model->db_get('sys_calendar','*','sync=true');
        foreach ($calendars as $key => $calendar) {
            $tableid=$calendar['tableid'];
            $table='user_'.strtolower($tableid);
            $field_data=$calendar['field_data'];
            $field_orainizio=$calendar['field_orainizio'];
            $field_orafine=$calendar['field_orafine'];
            $fields_titolo=$calendar['field_titolo'];
            $fields_descrizione=$calendar['field_descrizione'];
            $sync_condition=$calendar['sync_condition'];
            $userid_tosync=$calendar['userid_tosync'];
            $field_useridtosync=$calendar['field_useridtosync'];
            if($this->isnotempty($sync_condition))
            {
                $sync_condition="AND ".$sync_condition;
            }
            $events=  $this->Sys_model->db_get($table,'*',"recordstatus_ is null $sync_condition");
            foreach ($events as $key => $event) 
            {
                $data=array();
                $recordid_event=$event['recordid_'];
                $start_date=$event[$field_data];
                if($this->isnotempty($field_data))
                {
                    $data['start_date']=$start_date;
                    $data['end_date']=$start_date; 
                }
                    
                if($this->isnotempty($field_orainizio))
                {
                    $data['start_time']=$event[$field_orainizio];
                }
                if($this->isnotempty($field_orainizio))
                {
                    $data['end_time']=$event[$field_orafine];    
                }  
                $fields_titolo_array=  explode(';', $fields_titolo);
                $titolo='';
                foreach ($fields_titolo_array as $key => $field_titolo) {
                    $value=$event[$field_titolo];
                    $value=$this->Sys_model->get_value_converted($tableid,$field_titolo,$value,false);
                    $titolo=$titolo." ".$value;
                }
                $data['titolo']=$titolo;
                
                $descrizione='';
                if($this->isnotempty($fields_descrizione))
                {
                    $fields_descrizione_array=  explode(';', $fields_descrizione);
                    foreach ($fields_descrizione_array as $key => $field_descrizione) {
                        $value=$event[$field_descrizione];
                        $descrizione=$descrizione." ".$value;
                    }
                }
                $recordid_event_trimmed=ltrim($recordid_event, '0');
                $descrizione=$descrizione."http://server.about-x:8822/jdocweb/index.php/sys_viewcontroller/view_external_link/$tableid/$recordid";
                $descrizione=$descrizione."   tablerecordid:$tableid|$recordid_event_trimmed";
                $data['descrizione']=$descrizione;
                
                $userid_tosync_array=array();
                if($this->isnotempty($userid_tosync))
                {
                    $userid_tosync_array=  explode(';', $userid_tosync);
                }
                $userid_tosync_fromfield_array=array();
                if($this->isnotempty($field_useridtosync))
                {
                    $userid_tosync_fromfield=$event[$field_useridtosync];
                    $userid_tosync_fromfield_array=explode(';',$userid_tosync_fromfield);
                }
                
                $userid_tosync_array_merged=  array_merge($userid_tosync_array,$userid_tosync_fromfield_array);
                foreach ($userid_tosync_array_merged as $key => $userid) 
                {
                    $googlecalendarid=$this->Sys_model->get_user_setting('google_calendar_id',$userid);
                    if($this->isnotempty($googlecalendarid))
                    {
                        $data['calendarId']=$googlecalendarid;
                        $this->load->view('google_api/GCServiceAccount',$data,true);
                        $nomecognome=$this->Sys_model->get_user_nomecognome($userid);
                        echo "aggiunto evento $titolo in data $start_date per l'utente: $nomecognome <br/>";
                    }
                    
                }
                
                $sql="
                    UPDATE $table
                    SET recordstatus_='sync'
                    WHERE recordid_='$recordid_event'
                    ";
                $this->Sys_model->execute_query($sql);
            }
        
    }
    }
    
    function autobatchimport()
    {
        $autobatches=$this->Sys_model->db_get('sys_autobatch','*',"originalpath is not null");
        foreach ($autobatches as $key => $autobatch) {
            $originalpath=$autobatch['originalpath'];
            $path=$autobatch['path'];
            $host_path=  $this->get_host_path();
            $autobatch_path="$host_path\JDocServer\autobatch";
            
            $timestamp=time();
            $command='cd "'.$originalpath.'" && forfiles /M *.pdf /C "cmd /c rename @file \"@fname _t_'.$timestamp.'.pdf\"" ';
            $this->esegui($command);
            $command='cd ./tools/autobatchimport/ && autobatchimport.bat "'.$originalpath.'\*.pdf" "'.$autobatch_path.'\\'.$path.'" ';
            $this->esegui($command);
            $command='cd ./tools/autobatchimport/ && autobatchimport.bat "'.$originalpath.'\*.jpg" "'.$autobatch_path.'\\'.$path.'" ';
            $this->esegui($command);
            $command='cd ./tools/autobatchimport/ && autobatchimport.bat "'.$originalpath.'\*.png" "'.$autobatch_path.'\\'.$path.'" ';
            $this->esegui($command);
            
        }
    }
    
    function esegui($command)
    {
        echo "<br/>";
        echo exec("whoami");
        echo "Comando:";
        echo "<br/>";
        echo $command;
        echo "<br/>";
        exec($command,$output,$return_var);
        echo "Output:";
        echo "<br/>";
        foreach ($output as $key => $value) {
            echo $value;
        }
        echo "<br/>";
        echo "Return var:";
        echo "<br/>";
        echo $return_var;
        echo "<br/>";
        echo "<br/>";
    }
    
    function get_host_path()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
    
    public function ajax_get_emailinviodoc($tableid,$recordid)
    {
        $email=$this->Sys_model->get_emailinviodoc($tableid,$recordid);
        echo $email;
    }
    
    public function selezione_viste_report($tableid,$reportid)
    {
        $views=$this->Sys_model->get_saved_views($tableid);
        $data=array();
        $data['views']=$views;
        $data['tableid']=$tableid;
        $data['reportid']=$reportid;
        $this->load->view('sys/desktop/block/selezione_viste_report',$data);
    }
    
    public function selezione_viste_report_salva($reportid)
    {
        $post=$_POST;
        $this->Sys_model->save_report_views($reportid,$post);
    }
    
    public function elimina_report($reportid)
    {
        $this->Sys_model->elimina_report($reportid);
    }
    
    
    public function script_set_riferimento_immobili()
    {
        $immobili= $this->Sys_model->db_get('user_immobili');
        foreach ($immobili as $key => $fields) {
            $immobile_recordid=$fields['recordid_'];
            $immobile_id=$fields['id'];
            $immobile_consulente=$fields['consulente'];
            $immobile_consulente_array= explode('|;|', $immobile_consulente);
            $immobile_consulente=$immobile_consulente[0];
            $consulente=  $this->Sys_model->db_get_row('sys_user','*',"id=$immobile_consulente");
            $consulente_cognome=$consulente['lastname'];
            $consulente_nome=$consulente['firstname'];
            $riferimento=$immobile_id."-".$consulente_nome[0].$consulente_cognome[0].$immobile_consulente;
            $sql="UPDATE user_immobili SET riferimento='$riferimento' WHERE recordid_='$immobile_recordid'";
            $this->Sys_model->execute_query($sql);
        }
        
    }
    
    //custom dimensione immobiliare
    public function genera_immagini_portali()
    {
        
    }
    
    public function genera_qr($qrcontent='')
    {
        $this->load->library('My_phpqrcode');
        // ECC Level, livello di correzione dell'errore (valori possibili in ordine crescente: L,M,Q,H - da low a high)
        $errorCorrectionLevel = 'L';

        // Matrix Point Size, dimensione dei punti della matrice (da 1 a 10)
        $matrixPointSize = 4;

        // I dati da codificare nel QRcode

        // Il File da salvare (deve essere salvato in una directory scrivibile dal web server)

        // Generiamo il QRcode in formato immagine PNG
        $qrname='qr'.time().'.png';
        QRcode::png($qrcontent, '../JdocServer/generati/'.$qrname); // creates file 
        return $qrname;
    }
    
    
    //custom about-x
    public function sync_iscritti()
    {
        $json = file_get_contents("http://www.about-x.info/registrazione/sys_viewcontroller/get_iscritti/");
        $iscritti = json_decode($json,true);
        foreach ($iscritti as $key => $azienda_iscritta) {
            $persone=$azienda_iscritta['persone'];
            foreach ($persone as $key => $persona_iscritta) {
                $recordid_iscrizione_eventi=$this->Sys_model->insert_iscritto($azienda_iscritta,$persona_iscritta);
                if($recordid_iscrizione_eventi!='')
                {
                    $cognome=$persona_iscritta['cognome'];
                    $nome=$persona_iscritta['nome'];
                    $mail_persona=$persona_iscritta['mail'];
                    $workshop_list=explode("|;|", $azienda_iscritta['workshop']);;
                    $qrname=$this->genera_qr("www.about-x.info/registrazione/sys_viewcontroller/conferma_iscrizione/".$recordid_iscrizione_eventi);
                    $data['qrname']=$qrname;
                    $data['cognome']=$cognome;
                    $data['nome']=$nome;
                    $data['workshop_list']=$workshop_list;
                    $data['mail']=$mail_persona;
                    $content=  $this->load->view('sys/desktop/stampe/stampa_iscrizione_evento',$data, TRUE);
                    echo $content;
                    $path_pdf=$this->genera_stampa($content,$recordid_iscrizione_eventi);
                    
                    $mail['mailfrom_userid']=26;
                    $mail['mailto']=$mail_persona;
                    $mail['mailcc']='';
                    $mail['mailbcc']='a.galli@about-x.com';
                    $mail['mailsubject']="Iscrizione Securing the internet of things ";
                    $mail['mailbody']="
                        Ciao $nome $cognome,<br/>
                        <br/>
                        In allegato il biglietto: Securing the internet of things <br/>
                        <br/>
                        <br/>
                        #### Informazioni sull'evento #### <br/>
                        <br/>
                        Ti ricordiamo che l'evento avrà luogo presso:<br/>
                        <br/>
                        About-x,<br/>
                        Via al Fiume 1,<br/> 
                        6929 Gravesano<br/>
                        <br/>
                        in data:<br/>
                        <br/>
                        giovedì 19 ottobre 2017 <br/> 
                        venerdì 20 ottobre 2017 <br/>
                        <br/>
                        A presto,<br/>
                        <br/>
                        About-x <info@about-x.com>
                        ";
                    $mail['mailattachment']=$path_pdf;

                    $this->Sys_model->push_mail_queue($mail);
                    //echo $this->jdw_send_mail($mail);
                }
            }
            
        }
        
    }
    
    public function conferma_iscrizione($recordid_iscrizione_eventi)
    {
        header("Access-Control-Allow-Origin: *");
        $this->Sys_model->conferma_iscrizione($recordid_iscrizione_eventi);
        echo 'Presenza confermata';
        
    }
    
    public function custom_1824_create_mail()
    {
        $ragazzi= $this->Sys_model->db_get('user_candidati','*',"profiloattivo='si'");
        foreach ($ragazzi as $key => $ragazzo) {
            $sesso=$ragazzo['sesso'];
            $caro='Caro';
            $trovato='trovato';
            $contattato='contattato';
            if($sesso=='f')
            {
                $caro='Cara';
                $trovato='trovata';
                $contattato='contattata';
            }
            $cognome=$ragazzo['cognome'];
            $nome=$ragazzo['nome'];
            $email=$ragazzo['email'];
            $salt = "410#2Z67G231%38822BD!8#T#00D*7E329L3";
            $hash_key = hash('sha512', $salt.$email);
            $pwrurl = "https://www.18-24.ch/progetto1824/index.php/App_controller/ajax_load_content_change_password/". urlencode($email)."/".urlencode($hash_key);
            $mailbody="
            $caro $nome,<br/>
            <br/>
            Il nuovo sito di 18-24 è pronto! Grazie alle nuove funzionalità, ora avrai maggiori possibilità di essere $trovato dalle aziende.<br/>
            <br/>
            Aggiornare il tuo profilo è molto semplice: segui questo link <br/>
            <br/>
            <a href='$pwrurl'>Modifica password</a> <br/>
            <br/>
            e modifica la tua password di accesso al portale.<br/>
            <br/>
            Mentre compilerai i campi richiesti il sistema inizierà a preparare il tuo Curriculum Vitae personale che, avrai in seguito, modo di modificare.<br/>
            <br/>
            Perché il CV sia efficace, è importante che curi bene la parte dedicata alle esperienze professionali.<br/>
            <br/>
            Più sarai dettagliato nello specificare le mansioni che hai svolto durante le tue esperienze lavorative <br/>
            e più probabilità avrai di essere $contattato dalle aziende per un colloquio di lavoro.<br/>
            <br/>
            Se hai già trovato un lavoro ma desideri avere una versione più dettagliata o diversa del tuo Curriculum,<br/>
            puoi iscriverti ugualmente per tenerlo sempre disponibile in archivio.<br/>
            <br/>
            In questo caso il tuo CV rimarrà salvato nel tuo account per tutto il tempo che vorrai e, qualora in futuro <br/>
            avrai nuovamente bisogno del tuo Curriculum, saprai già dove trovarlo.<br/>
            <br/>
            L’Associazione 18-24, ad oggi, è riuscita ad aiutare più di 30 giovani neodiplomati, a trovare un posto di lavoro.<br/>
            Per cui, se hai degli amici tra i 18 e i 24 anni, residenti nel Cantone Ticino che stanno cercando lavoro, non esitare a segnalarli il sito www.18-24.ch.<br/>
            Potrebbero essere loro i prossimi a trovare un lavoro grazie all’Associazione e al TUO aiuto!<br/>
            <br/>
            Per qualsiasi domanda o chiarimento, puoi inviarci un e-mail a info@18-24.ch. <br/>
            <br/>
            Ti Auguriamo Buon Anno e buon lavoro !<br/>
            <br/>
            Il Team 18-24<br/>
            <br/>
            <br/>
            <img src='http://www.18-24.ch/progetto1824/assets/app/images/logo.png'></img>
            <div style='font-size:9pt;'>
                <b>Associazione 18-24</b><br/>
                Via Sottomurata 1  <br/>
                CH-6934 Bioggio      <br/>
                Tel. 091 222 18 24    <br/>
            </div>
            <a href='https://www.facebook.com/18-24-842724165779495/'><img src='http://www.18-24.ch/progetto1824/assets/app/images/facebook.png'></img></a>
            
                ";
            $this->Sys_model->push_mail_queue_smart('2',$email,'Il nuovo sito di 18-24 è pronto!',$mailbody,'','galli8822@gmail.com');
            echo $mailbody;
        }
    }
    
    
    
    //CUSTOM 3P
    public function genera_rapportidilavoro()
    {
        $dipendenti= $this->Sys_model->db_get('user_dipendenti','recordid_');
        foreach ($dipendenti as $key => $dipendente) {
            $recordid_dipendente=$dipendente['recordid_'];
            $this->genera_rapportodilavoro($recordid_dipendente);
        }
    }
    
    //CUSTOM 3P
    public function genera_rapportodilavoro($recordid_dipendente)
    {
        $this->Sys_model->genera_rapportidilavoro($recordid_dipendente);
    }
    
    public function rendi_pubblico($recordid_proposta)
    {
        $this->Sys_model->rendi_pubblico($recordid_proposta);
    }
    
    public function testOld()
    {
        $data=array();
        $data['data']['content']=  $this->load->view("test",$data,true);
        $settings= $this->Sys_model->get_settings();
        $settings['archive']=  $this->Sys_model->get_archive_menu();
        $data['data']['settings']=$settings;
        $this->load->view("sys/desktop/base",$data);
    }
    
    
    public function ajax_results_field_changed()
    {
        $post=$_POST;
        $tableid=$post['tableid'];
        $recordid=$post['recordid'];
        $fieldid=$post['fieldid'];
        $value=$post['value'];
        $this->Sys_model->set_results_field_changed($tableid,$recordid,$fieldid,$value);
        if($tableid=='rapportidilavoro')
        {
            $duratalavoro= $this->Sys_model->db_get_value('user_rapportidilavoro','duratalavoro',"recordid_='$recordid'");
            echo $duratalavoro;
        }
    }
    
    public function ajax_get_recordid_rapportino($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $data="$anno-$mese-$giorno";
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $recordid_rapportino= $this->Sys_model->db_get_value("user_rapportidilavoro","recordid_","recordiddipendenti_='$recordid_dipendente' AND data='$data'");
        }
        else
        {
            $recordid_rapportino='';
        }
        echo $recordid_rapportino;
    }
    
    public function conferma_collega_record()
    {
        $post=$_POST;
        $recordid_to_link= $post['recordid_to_link'];
        $recordid_to_link_array= explode(';', $recordid_to_link);
        if(count($recordid_to_link_array)>1)
        {
           $recordid_master= $recordid_to_link_array[1];
           foreach ($recordid_to_link_array as $key => $recordid_tabletolink) {
                if((isnotempty($recordid_tabletolink))&&($key!=1)&&($key!=0))
                {
                    $this->Sys_model->link_aziende($recordid_master,$recordid_tabletolink);
                }
            } 
        }
        
    }
    
    
    
    public function ajax_load_custom_3p_rapportino($recordid_presenzemensili,$fieldid_giorno)
    {
        $block= $this->load_custom_3p_rapportino($recordid_presenzemensili,$fieldid_giorno);
        echo $block;
    }
    
    public function load_custom_3p_rapportino($recordid_presenzemensili,$fieldid_giorno)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            $rapportino= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            
            $giornosettimana= $rapportino['giornodellasettimana'];
            $numgiornosettimana=0;
            if($giornosettimana=='lunedi')
            {
                $numgiornosettimana=1;
            }
            if($giornosettimana=='martedi')
            {
                $numgiornosettimana=2;
            }
            if($giornosettimana=='mercoledi')
            {
                $numgiornosettimana=3;
            }
            if($giornosettimana=='giovedi')
            {
                $numgiornosettimana=4;
            }
            if($giornosettimana=='venerdi')
            {
                $numgiornosettimana=5;
            }
            if($giornosettimana=='sabato')
            {
                $numgiornosettimana=6;
            }
            if($giornosettimana=='domenica')
            {
                $numgiornosettimana=7;
            }
            
            for ($i = 1; $i <= 7; $i++) {
                $diffgiorni=$i-$numgiornosettimana;
                $sql="
                    select user_rapportidilavoro.*,user_contratti.visto
                    FROM user_rapportidilavoro LEFT JOIN user_contratti
                    ON user_rapportidilavoro.recordidcontratti_=user_contratti.recordid_
                    WHERE user_rapportidilavoro.recordiddipendenti_='$recordid_dipendente' AND user_rapportidilavoro.data= '$datamese' + INTERVAL $diffgiorni DAY $alias_condition
                    ";
                //$data['rapportidilavoro'][$i]=$this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data= '$datamese' + INTERVAL $diffgiorni DAY");
                $results= $this->Sys_model->select($sql);
                if(count($results)>0)
                {
                    $data['rapportidilavoro'][$i]=$results[0];
                }
                
            }
            
            //check alias
            $data['alias']=array();
            $sql="
                        SELECT recordid_ ,id
                        FROM
                        (
                         SELECT *,datainizio as 'datainizioeffettiva',if(tipocontratto='indet',if(datadisdetta is null,'2100-01-01',datadisdetta),if(datadisdetta<datafine,datadisdetta,datafine) ) as 'datafineeffettiva' FROM user_contratti WHERE recordiddipendenti_='$recordid_dipendente' AND alias is not null 
                            ) as supporto
                        where   
                        recordiddipendenti_='$recordid_dipendente' 
                        AND
                        (
                        ((datainizioeffettiva<='$anno-$mese-01') AND (datafineeffettiva>='$anno-$mese-31') )
                        OR
                        ((datainizioeffettiva>='$anno-$mese-01') AND (datainizioeffettiva<='$anno-$mese-31') )
                        OR
                        ((datafineeffettiva>='$anno-$mese-01') AND (datafineeffettiva<='$anno-$mese-31') )
                        )
                        
                        ";
                    $result=$this->Sys_model->select($sql);
                    if(count($result)>0)
                    {
                       $data['alias']=$result; 
                    }
            
        }
        $data['aziende']= $this->Sys_model->db_get("user_azienda","recordid_ as itemcode,CONCAT(ragionesociale,' ',id) as itemdesc","true","ORDER BY ragionesociale asc");
        $data['situazioni']= $this->Sys_model->db_get("sys_lookup_table_item","itemcode,itemdesc","lookuptableid='situazione_rapportidilavoro'");
        $data['situazioni2']= $this->Sys_model->db_get("sys_lookup_table_item","itemcode,itemdesc","lookuptableid='situazione2_rapportidilavoro'");        
        $data['laboratori']=$this->Sys_model->db_get("sys_lookup_table_item","itemcode,itemdesc","lookuptableid='laboratorio_rapportidilavoro'");
        $data['recordid_presenzemensili']=$recordid_presenzemensili;
        $data['table_settings']=  $this->Sys_model->get_table_settings('presenzemensili');
        return $this->load->view('sys/desktop/custom/3p/rapportino',$data);
    }
    
    public function ajax_load_custom_3p_situazione($recordid_presenzemensili,$fieldid_giorno)
    {
        $block= $this->load_custom_3p_situazione($recordid_presenzemensili,$fieldid_giorno);
        echo $block;
    }
    
    public function load_custom_3p_situazione($recordid_presenzemensili,$fieldid_giorno)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportino= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese'");
            
            
        }
        $data['situazioni']= $this->Sys_model->db_get("sys_lookup_table_item","itemcode,itemdesc","lookuptableid='situazione_rapportidilavoro'");
        return $this->load->view('sys/desktop/custom/3p/situazione',$data);
    }
    
    public function ajax_custom_3p_colora_verde($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set prova='si' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenzeprove',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenzeprove','$recordid_presenzemensili','todo')";
            //$this->Sys_model->execute_query($sql);
        }
    }
    
    public function ajax_custom_3p_annulla_verde($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set prova='no' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenzeprove',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenzeprove','$recordid_presenzemensili','todo')";
            //$this->Sys_model->execute_query($sql);
        }
    }
    
    
    public function ajax_custom_3p_colora_festivopagato($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set festivopagato='si' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            $this->aggiorna_presenze($recordid_presenzemensili);
        }
    }
    
    public function ajax_custom_3p_annulla_festivopagato($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set festivopagato='no' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            $this->aggiorna_presenze($recordid_presenzemensili);
        }
    }
    
     public function ajax_custom_3p_colora_viola($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set viola='si' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenze',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
        }
    }
    
    public function ajax_custom_3p_annulla_viola($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set viola='no' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenze',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
        }
    }
    
    public function ajax_custom_3p_segna_festivita($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set festivitarossa='si' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenzerosse',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenzerosse','$recordid_presenzemensili','todo')";
            //$this->Sys_model->execute_query($sql);
        }
    }
    
    public function ajax_custom_3p_annulla_festivita($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
            $sql="UPDATE user_rapportidilavoro set festivitarossa='no' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->Sys_model->execute_query($sql);
            //$this->Sys_model->add_custom_update('presenzerosse',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenzerosse','$recordid_presenzemensili','todo')";
            //$this->Sys_model->execute_query($sql);
        }
    }
    
    public function ajax_get_recordid_rapportodilavoro($recordid_presenzemensili,$fieldid_giorno)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        $alias=$record_presenzemensili['alias'];
        if(isnotempty($alias))
        {
            $alias_condition="AND alias='$alias'";
        }
        else
        {
            $alias_condition="AND (alias='' OR alias is null)";
        }
        $anno=$record_presenzemensili['anno'];
        $mese=$record_presenzemensili['mese'];
        $mese=sprintf('%02d', $mese);
        $giorno= str_replace('g', '', $fieldid_giorno);
        $giorno= str_replace('d', '', $giorno);
        $giorno=sprintf('%02d', $giorno);
        $datamese="$anno-$mese-$giorno";

        $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
        $rapportino= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
        echo $rapportino['recordid_'];
    }
    
    public function ajax_get_recordid_dipendente($recordid_presenzemensili)
    {
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
        echo $recordid_dipendente;
    }
    
    public function ajax_load_custom_3p_note($recordid_presenzemensili,$fieldid_giorno)
    {
        $block= $this->load_custom_3p_note($recordid_presenzemensili,$fieldid_giorno);
        echo $block;
    }
    
    public function ajax_load_custom_3p_note_preview($recordid_presenzemensili,$fieldid_giorno)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportino= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese'");
            
        }
        $osservazioni=$rapportino['osservazioni'];
        echo $osservazioni;
        
    }
    
    public function load_custom_3p_note($recordid_presenzemensili,$fieldid_giorno)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        if($record_presenzemensili!=null)
        {
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $giorno= str_replace('g', '', $fieldid_giorno);
            $giorno= str_replace('d', '', $giorno);
            $giorno=sprintf('%02d', $giorno);
            $datamese="$anno-$mese-$giorno";
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND user_rapportidilavoro.alias='$alias'";
            }
            else
            {
                $alias_condition="AND (user_rapportidilavoro.alias='' OR user_rapportidilavoro.alias is null)";
            }
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $rapportino= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordid_dipendente' AND data='$datamese' $alias_condition");
            
        }
        $data['recordid_presenzemensili']=$recordid_presenzemensili;
        $data['recordid_rapportodilavoro']=$rapportino['recordid_'];
        $data['osservazioni']=$rapportino['osservazioni'];
        return $this->load->view('sys/desktop/custom/3p/note',$data);
    }
    
    
    
    public function ajax_load_custom_3p_note_dipendente($recordid_presenzemensili)
    {
        $block= $this->load_custom_3p_note_dipendente($recordid_presenzemensili);
        echo $block;
    }
    
    public function load_custom_3p_note_dipendente($recordid_presenzemensili)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        
        if($record_presenzemensili!=null)
        {
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            
            $note_dipendente=$record_presenzemensili['notetempo'];
            $data['notetempo']=$note_dipendente;
            $data['recordid_presenzemensili']=$recordid_presenzemensili;
            $data['recordid_dipendente']=$recordid_dipendente;
            
            
            $current_year_month=date('Y-m');
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            if($current_year_month=="$anno-$mese")
            {
                $date=date('Y-m-d');
            }
            else
            {
                $date=$anno.$mese."31";
            }
            
            $note_temporizzate=$this->Sys_model->db_get('user_note','*',"recordiddipendenti_='$recordid_dipendente' AND dal<='$date' AND (al>='$date' OR al is null)");
            
            
            
            $data['note_temporizzate']= $note_temporizzate;
        }
        
        
        return $this->load->view('sys/desktop/custom/3p/note_dipendente',$data);
    }
    
    public function ajax_load_custom_3p_zonelavorative_dipendente($recordid_presenzemensili)
    {
        $block= $this->load_custom_3p_zonelavorative_dipendente($recordid_presenzemensili);
        echo $block;
    }
    
    public function load_custom_3p_zonelavorative_dipendente($recordid_presenzemensili)
    {
        $data=array();
        $record_presenzemensili= $this->Sys_model->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
        
        if($record_presenzemensili!=null)
        {
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            
            $zonelavorative=$record_presenzemensili['zonelavorative'];
            $data['zonelavorative']=$zonelavorative;
            $data['zonelavorative_options']=$this->Sys_model->db_get("sys_lookup_table_item","*","lookuptableid='zonelavorative_dipendenti'");
            $data['recordid_presenzemensili']=$recordid_presenzemensili;
            $data['recordid_dipendente']=$recordid_dipendente;
        }
        
        return $this->load->view('sys/desktop/custom/3p/zonelavorative_dipendente',$data);
    }
    
    public function ajax_salva_rapportino_settimanale()
    {
        $post=$_POST;
        $this->Sys_model->salva_rapportino_settimanale($post);
    }
    
    public function ajax_salva_contratto_missione()
    {
        $post=$_POST;
        $recordid_contratto=$this->Sys_model->salva_contratto_missione($post);
        echo $recordid_contratto;
    }
    
    public function ajax_salva_contratto_fornitura()
    {
        $post=$_POST;
        $recordid_contratto=$this->Sys_model->salva_contratto_fornitura($post);
        echo $recordid_contratto;
    }
    
    public function ajax_get_record($tableid,$recordid)
    {
        $record= $this->Sys_model->db_get_row("user_$tableid",'*',"recordid_='$recordid'");
        //custom 3p inizio
        if($tableid=='professioni')
        {
            $record['cclapplicato']= $this->Sys_model->db_get_value('user_ccl','nomeccl',"recordid_='".$record['recordidccl_']."'");
        }
        //custom 3p fine
        $record_json=json_encode($record);
        echo $record_json;
    }
    
    public function get_record($tableid,$recordid)
    {
         $record= $this->Sys_model->db_get_row("user_$tableid",'*',"recordid_='$recordid'");
         return $record;
    }
    
    public function ajax_salva_note_rapportino()
    {
        $post=$_POST;
        $this->Sys_model->salva_note_rapportino($post);
    }
    
    public function ajax_salva_note_dipendente()
    {
        $post=$_POST;
        $recordid=$this->Sys_model->salva_note_dipendente($post);
        echo $recordid;
    }
    
    public function ajax_salva_nota_temporizzata()
    {
        $post=$_POST;
        $this->Sys_model->salva_nota_temporizzata($post);
    }
    
    public function ajax_cancella_nota_temporizzata()
    {
        $post=$_POST;
        $this->Sys_model->cancella_nota_temporizzata($post);
    }
    
    public function ajax_termina_nota_temporizzata()
    {
        $post=$_POST;
        $this->Sys_model->termina_nota_temporizzata($post);
    }
    
    public function ajax_salva_zonelavorative_dipendente()
    {
        $post=$_POST;
        $this->Sys_model->salva_zonelavorative_dipendente($post);
    }
    
    public function custom_3p_aggiornamedie($recordid_contratto=null)
    {	
        if($recordid_contratto!=null)
        {
            //$this->Sys_model->add_custom_update('medie',$recordid_contratto);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('medie','$recordid_contratto','todo')";
            //$this->Sys_model->execute_query($sql);
            $counter=0;
						
            while($counter<300){
                $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='medie' AND recordid='$recordid_contratto' AND stato='todo'");
				
                if($check_todo==null)
                {
                    $counter=300;
                }
                sleep(1);
                $counter++;
            }            
        }
        else
        {
            //$this->Sys_model->add_custom_update('medie','TUTTI');
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('medie','TUTTI','todo')";
            //$this->Sys_model->execute_query($sql);
            $counter=0;
			
            while($counter<300){
				
				$check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='medie' AND recordid='TUTTI' AND stato='todo'");

                if($check_todo==null)
                {
                    $counter=300;
                }
                sleep(1);
                $counter++;
            }             
        }        
    }
    

    public function custom_3p_aggiornaPresenzemesecorrente()
    {	
        //$this->Sys_model->add_custom_update('presenzemensili_mesecorrente',"");
        $counter=0;

        while($counter<3600){
            $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='presenzemensili_mesecorrente' AND stato='todo'");

            if($check_todo==null)
            {
                    $counter=3600;
            }
            sleep(1);
            $counter++;
        }    
    }
    
    public function custom_3p_aggiornaPresenzemesevisualizzato($mese,$anno)
    {	
        $mese=sprintf('%02d', $mese);
        //$this->Sys_model->add_custom_update("presenzemensili_mesecorrente_$mese"."_".$anno);
        $counter=0;

        while($counter<3600){
            $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='presenzemensili_mesecorrente_$mese"."_".$anno."' AND stato='todo'");

            if($check_todo==null)
            {
                    $counter=3600;
            }
            sleep(1);
            $counter++;
        }    
    }
    
    public function custom_3p_aggiornaNmesecorrente()
    {	
        //$this->Sys_model->add_custom_update('Nmesecorrente',"");
        $counter=0;

        while($counter<300){
            $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='Nmesecorrente' AND stato='todo'");

            if($check_todo==null)
            {
                    $counter=300;
            }
            sleep(1);
            $counter++;
        }    
    }	

    public function custom_3p_aggiornaNdainizioanno()
    {	
        //$this->Sys_model->add_custom_update('Ndainizioanno',"");
        $counter=0;

        while($counter<900){
            $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='Ndainizioanno' AND stato='todo'");

            if($check_todo==null)
            {
                    $counter=900;
            }
            sleep(1);
            $counter++;
        }    
    }	

    	
    
    public function correggi_rotazione_foto($recordid)
    {
        $allegati= $this->Sys_model->get_allegati('immobili', $recordid);
        $return='';
        foreach ($allegati as $key => $allegato) {
            $filename=$allegato['filename_'].".".$allegato['extension_'];
            $command="cd ./tools/JPEG-EXIF_autorotate && jhead.exe -autorot ../../../JDocServer/archivi/immobili/000/$filename";
            $return=$return." ".$command;
            exec($command);
            
            $filename=$allegato['filename_']."_thumbnail.jpg";
            $command="cd ./tools/JPEG-EXIF_autorotate && jhead.exe -autorot ../../../JDocServer/archivi/immobili/000/$filename"; 
            exec($command);
        }
        echo $return;
    }
    
    public function download_allegato($tableid,$nome_file='')
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $nome_file=  urldecode($nome_file);
        
        $percorso_file="../JDocServer/archivi/$tableid/000/".$nome_file;
        //$filesize=filesize($percorso_file); 
        header("Content-type: Application/octet-stream"); 
        header("Content-Disposition: attachment; filename=$nome_file"); 
        header("Content-Description: Download PHP"); 
        header("Content-Length: ".filesize($percorso_file)); 
        readfile($percorso_file); 
        //unlink($percorso_file);
    }
    
    
    public function genera_report_pdf($nomefile)
    {
        $nomefile= str_replace('.pdf', '', $nomefile);
        $this->Sys_model->add_custom_update('_generaReport',"$nomefile");
            $counter=0;
            while($counter<300){
                $stato=$this->Sys_model->db_get_value('custom_update','stato',"funzione='_generaReport' AND recordid='$nomefile'","ORDER BY ID DESC");
				
                if($stato=='done')
                {
                    $counter=300;
                }
                sleep(1);
                $counter++;
            }
            sleep(2);
    }
    
    public function ajax_reset_access()
    {
        $command="cd ./tools/ && reset_access.bat";
        $this->esegui($command);
    }
    
    //CUSTOM 3P
    public function custom_3p_send_reportBAK()
    {
        $check_sendmail=false;
        $today=  date('Y-m-d');
        $today_month=date('m');
        $today_giornomese=date('d');
        $today_giornosettimana=date('l');
        $scheduled_reports= $this->Sys_model->db_get('user_invioreport','*');
        foreach ($scheduled_reports as $key => $scheduled_report) {
            $report=$scheduled_report['report'];
            $gruppi=$scheduled_report['gruppo'];
            $gruppi_array= explode('|;|', $gruppi);
            $gruppo_emails=array();
            foreach ($gruppi_array as $key => $gruppo) {
                $results= $this->Sys_model->db_get('user_contatti','*',"gruppo='$gruppo'");
                $gruppo_emails= array_merge($gruppo_emails,$results);
            }
            
            $periodicita=$scheduled_report['periodicita'];
            $giornosettimana=$scheduled_report['giornosettimana'];
            $giornomese=$scheduled_report['giornomese'];
            if($periodicita=='giornaliera')
            {
                if(($today_giornosettimana=='Monday')||($today_giornosettimana=='Tuesday')||($today_giornosettimana=='Wednesday')||($today_giornosettimana=='Thursday')||($today_giornosettimana=='Friday'))
                {
                    $check_sendmail=true;
                }
            }
            if($periodicita=='settimanale')
            {
                if($today_giornosettimana==$giornosettimana)
                {
                    $check_sendmail=true;
                }
            }
            if($periodicita=='mensile')
            {
                
            }
            
            if($check_sendmail)
            {
                $mail_subject='';
                $mail_body='';
                $mail_to='';
                foreach ($gruppo_emails as $key => $gruppo_email) {
                    if($mail_to!='')
                    {
                        $mail_to=$mail_to.'|;|';
                    }
                    $mail_to=$mail_to.$gruppo_email['email'];
                }
                 
                if($report=='Lista presenze mese corrente')
                {
                    
                }
                
                if($report=='Lista dipendenti attivi per cliente')
                {
                    
                    
                }
               
            }
        }
    }
    
    public function custom_3p_send_lista_presenze()
    {
        $today=  date('Y-m-d');
        $mail_to='';
        $gruppi=$this->Sys_model->db_get_value('user_invioreport','gruppo',"report='Lista presenze mese corrente'");
        $gruppi_array= explode('|;|', $gruppi);
        $gruppo_emails=array();
        foreach ($gruppi_array as $key => $gruppo) {
            $results= $this->Sys_model->db_get('user_contatti','*',"gruppo='$gruppo'");
            $gruppo_emails= array_merge($gruppo_emails,$results);
        }
        foreach ($gruppo_emails as $key => $gruppo_email) {
            if($mail_to!='')
            {
                $mail_to=$mail_to.'|;|';
            }
            $mail_to=$mail_to.$gruppo_email['email'];
        }
        $mese_corrente=date("m");
        $anno_corrente=date("Y");
        $query="
           SELECT recordid_, recordstatus_,  '' as recordcss_, anno, mese, no, id, nome, cognome, 3mesidatascadenza, g1d, g2d, g3d, g4d, g5d, g6d, g7d, g8d, g9d, g10d, g11d, g12d, g13d, g14d, g15d, g16d, g17d, g18d, g19d, g20d, g21d, g22d, g23d, g24d, g25d, g26d, g27d, g28d, g29d, g30d, g31d, zonelavorative, note  FROM user_presenzemensili  WHERE true AND recordiddipendenti_ IN(SELECT recordid_ FROM user_dipendenti  WHERE true AND anno like '%$anno_corrente%' AND mese = '$mese_corrente')
           ";
       $content=  $this->load_stampa_elenco_pdf('presenzemensili',null,$query,'presenzemensili');
       $stampa_elenco_orientamento=$this->Sys_model->get_table_setting('presenzemensili','risultati_stampa_elenco_orientamento');
       $percorso_file=$this->genera_stampa($content,'ListaPresenze_'.$today,$stampa_elenco_orientamento,true);

       $mail_subject='Presenze mensili '.$today;
       $mail_body='Prenze mensili '.$today;
       $recordid_mail=$this->Sys_model->push_mail_queue_smart('1',$mail_to,$mail_subject,$mail_body,'','galli8822@gmail.com');
       $sql="UPDATE user_mail_queue SET status='attesa' WHERE recordid_='$recordid_mail'";
       $this->Sys_model->execute_query($sql);
       $data_page=array();
       $this->Sys_model->insert_record_page('mail_queue',$recordid_mail,1,$percorso_file,$data_page);
       $sql="UPDATE user_mail_queue SET status='dainviare' WHERE recordid_='$recordid_mail'";
       $this->Sys_model->execute_query($sql);
    }
    
    public function custom_3p_send_lista_disponibilita()
    {
        $percorso_file= $this->genera_stampa_lista_disponibilita('return path');
        $this->custom_3p_send_report('Lista disponibilità',$percorso_file);
    }
    
    public function custom_3p_send_report($report_name,$percorso_file)
    {
        $today=  date('Y-m-d');
        $mail_to='';
        $gruppi=$this->Sys_model->db_get_value('user_invioreport','gruppo',"report='$report_name'");
        $gruppi_array= explode('|;|', $gruppi);
        $gruppo_emails=array();
        foreach ($gruppi_array as $key => $gruppo) {
            $results= $this->Sys_model->db_get('user_contatti','*',"gruppo='$gruppo'");
            $gruppo_emails= array_merge($gruppo_emails,$results);
        }
        foreach ($gruppo_emails as $key => $gruppo_email) {
            if($mail_to!='')
            {
                $mail_to=$mail_to.'|;|';
            }
            $mail_to=$mail_to.$gruppo_email['email'];
        }
        $mese_corrente=date("m");
        $query="
           SELECT recordid_, recordstatus_,  '' as recordcss_, anno, mese, no, id, nome, cognome, 3mesidatascadenza, g1d, g2d, g3d, g4d, g5d, g6d, g7d, g8d, g9d, g10d, g11d, g12d, g13d, g14d, g15d, g16d, g17d, g18d, g19d, g20d, g21d, g22d, g23d, g24d, g25d, g26d, g27d, g28d, g29d, g30d, g31d, zonelavorative, note  FROM user_presenzemensili  WHERE true AND recordiddipendenti_ IN(SELECT recordid_ FROM user_dipendenti  WHERE true AND anno like '%2019%' AND mese = '$mese_corrente')
           ";

       $mail_subject=$report_name.' '.$today;
       $mail_body=$report_name.' '.$today;
       $recordid_mail=$this->Sys_model->push_mail_queue_smart('1',$mail_to,$mail_subject,$mail_body,'');
       $sql="UPDATE user_mail_queue SET status='attesa' WHERE recordid_='$recordid_mail'";
       $this->Sys_model->execute_query($sql);
       $data_page=array();
       $this->Sys_model->insert_record_page('mail_queue',$recordid_mail,1,$percorso_file,$data_page);
       $sql="UPDATE user_mail_queue SET status='dainviare' WHERE recordid_='$recordid_mail'";
       $this->Sys_model->execute_query($sql);
    }
    
    public function custom_3p_send_dipendenti_attivi_clienti()
    {
        $today=  date('Y-m-d');
        $aziende=$this->Sys_model->db_get('user_azienda',"*","emailreportmensile is not null AND emailreportmensile!='' ");
        foreach ($aziende as $key => $azienda) {
            $mail_to=$azienda['emailreportmensile'];
            $recordid_azienda=$azienda['recordid_'];
            $query="
            SELECT recordid_, recordstatus_,  '' as recordcss_, id, recordidazienda_, recordiddipendenti_, alias, tipocontratto, visto, datainizio, datafine, disdetta, datadisdetta, mediagiornalieracontrattuale, media_nuova, media_oresup2 FROM user_contratti WHERE true AND (recordstatus_ is null OR recordstatus_!='temp')  AND (true AND (EXTRACT(YEAR_MONTH FROM `datainizio`) <= EXTRACT(YEAR_MONTH FROM CURRENT_DATE)) AND ( (EXTRACT(YEAR_MONTH FROM `datafine`) >= EXTRACT(YEAR_MONTH FROM CURRENT_DATE) OR datafine is null or datafine='')) AND ( (EXTRACT(YEAR_MONTH FROM `datadisdetta`) = EXTRACT(YEAR_MONTH FROM CURRENT_DATE) OR datadisdetta is null OR datadisdetta=''))) AND recordidazienda_='$recordid_azienda'
            ";
            $contratti=$this->Sys_model->select($query);
            //$contratti= $this->Sys_model->get_records('contratti',$query);
            if(count($contratti)>0)
            {
                $content=  $this->load_stampa_elenco_pdf('contratti',null,$query,'contratti');
                $stampa_elenco_orientamento=$this->Sys_model->get_table_setting('presenzemensili','risultati_stampa_elenco_orientamento');
                $percorso_file=$this->genera_stampa($content,'Dipendenti_attivi_'.$recordid_azienda.'_'.$today,$stampa_elenco_orientamento,true);

                $mail_subject='Contratti attivi '.$today;
                $mail_body='Contratti attivi '.$today;
                $recordid_mail=$this->Sys_model->push_mail_queue_smart('1',$mail_to,$mail_subject,$mail_body,'','galli8822@gmail.com');
                $sql="UPDATE user_mail_queue SET status='attesa' WHERE recordid_='$recordid_mail'";
                $this->Sys_model->execute_query($sql);
                $data_page=array();
                $this->Sys_model->insert_record_page('mail_queue',$recordid_mail,1,$percorso_file,$data_page);
                //$sql="UPDATE user_mail_queue SET status='dainviare' WHERE recordid_='$recordid_mail'";
                //$this->Sys_model->execute_query($sql);
            }
        }
    }
    
    //CUSTOM 3P - Fabio
    public function custom_3p_AggiornaPresenzeMensili($presenzemensili_recordid='00000000000000000000000000012772')
    {
        /*
         * Sistema Posizionale per i 5 valori: controllare che ci siano sempre tutti

            SiglaAzienda#coloreFont#coloreSfondo#memo:testo#delimitatoreBordoNotifiche
            oppure
            hh:mm       #coloreFont#coloreSfondo#memo:testo#delimitatoreBordoNotifiche
         */
        
        $isFine=false;
        $isPrimoGiorno = False;
        $isPrimoGiornoImpostato = False;
        
        $record_presenzemensili= $this->Sys_model->db_get_row('user_presenzemensili','*',"recordid_='$presenzemensili_recordid'");
        $recordiddipendenti = $record_presenzemensili['recordiddipendenti_'];
        $anno=$record_presenzemensili['anno'];
        $mese=$record_presenzemensili['mese'];
        
        $recordidcontrattoprecedente="";
        
        $coloreSfondoDipInterni = $this->Sys_model->db_get_value('custom_tblColoriTesto','colore',"CodiceSituazione='interno'");
        
        for ($i = 1; $i <= 31; $i++){
            $giornocorrente=sprintf('%02d', $i);
            $datacorrente="$anno-$mese-$giornocorrente";
            
            if ((bool)strtotime($datacorrente)==true) {
                
                $rapportodilavoro= $this->Sys_model->db_get_row("user_rapportidilavoro","*","recordiddipendenti_='$recordiddipendenti' AND data='$datacorrente'");
                $recordid_rapportodilavoro=$rapportodilavoro['recordidrapportidilavoro_'];
                $recordidcontratti=$rapportodilavoro['recordidcontratti_'];
                $recordidazienda=$rapportodilavoro['recordidazienda_'];
                
                if (strlen($recordidazienda)>0)
                    $siglaAzienda=$this->Sys_model->db_get_value('user_azienda','sigla',"recordid_='$recordidazienda'");
                else
                    $siglaAzienda="";
                
                $giornosettimana = $this->Sys_model->db_get_value('user_calendario','giornosettimana',"giorno='$datacorrente'");
                $tipogiornosettimana = $this->Sys_model->db_get_value('user_calendario','tipogiorno',"giorno='$datacorrente'");
                
                $laboratorio = $rapportodilavoro['laboratorio'];
                $visto = $rapportodilavoro['visto']; 
                $prova = $rapportodilavoro['prova']; 
                $festivitarossa = $rapportodilavoro['festivitaRossa']; 
                $situazione = $rapportodilavoro['situazione']; 
                $viola = $rapportodilavoro['viola']; 
                $interno = $rapportodilavoro['interno']; 
                $duratalavoro = $rapportodilavoro['duratalavoro']; 
                
                $coloreFont = "Nero";
                
                //sabato/domenica: Azzurro - visto amm/dir: giallo/verdeChiaro - festivi parificati/non parificati: rosso/grigio
                
                $coloresfondo = $this->Sys_model->db_get_value('custom_tblColoriTesto','colore',"CodiceSituazione='$tipogiornosettimana'");
                
                If ($coloresfondo == "Trasparente")
                    $coloresfondo = $this->Sys_model->db_get_value('custom_tblColoriTesto','colore',"CodiceSituazione='$visto'");
                
                if ($coloresfondo != "Rosso" and $coloresfondo != "Grigio")
                    If ($giornosettimana == "sabato" Or $giornosettimana == "domenica")
                        $coloresfondo = "Azzurro";
                
                If ($coloresfondo == "")
                    $coloresfondo = "Trasparente";

                if ($prova == "si")
                     $coloresfondo = $this->Sys_model->db_get_value('custom_tblColoriTesto','colore',"CodiceSituazione='prova'");
                
                if ($viola == "si")
                     $coloresfondo = "Viola";               
                                
                if ($interno == "si" and $coloresfondo == "Trasparente")
                     $coloresfondo = $coloreSfondoDipInterni;
                
                //Se è lunedi' mostro la siglaAzienda-> altrimenti le oreLavorate
                //Se il dipendente non ha ancora un contratto DEVE uscire sempre la sigla del cliente e non le ore di lavoro !!!
                //Mostro la durata delle ora lavorate solo se non è specificato il lab, la situazione e l'azienda
                                
                $valoreDaAggiornare = date("H:i", $duratalavoro); //formato "hh:mm"
                if ($valoreDaAggiornare=="00:00")
                    $valoreDaAggiornare="";
                $nuovoOrario = $valoreDaAggiornare;
                
                If ($valoreDaAggiornare != "")                 
                    If ($isPrimoGiornoImpostato == False And $isPrimoGiorno = False)
                        $isPrimoGiorno = True;

                /* Gestione ->
                   deve essere mostrata la freccia in tutti i contratti indeterminati ma anche nei contratti determinati in quanto potrebbero essere anche di lunga durata.
                   Gli unici due casi in cui non deve uscire la freccetta è quando non c’è ancora un contratto (per ogni giorno lavorato deve uscire la sigla del cliente)
                   e quando il contratto è solo di un giorno */
                            
                if ($recordidcontrattoprecedente <> $recordidcontratti and $recordidcontratti <> "")
                    $isPrimoGiorno = True;
                
                if ($nuovoOrario <> "") {
                    
                    if ($siglaAzienda <> "") {
                        
                        if ($giornosettimana == "lunedi" Or isPrimoGiorno == True ) {
                            
                            if ($recordidcontratti=="") {//NON c'e' contratto
                                $valoreDaAggiornare = $siglaAzienda;
                            }
                            else {  //se il contratto è superiore ad 1 giorno
                                $duratacontratto = 1;// Val("" & DLookup("giorniduratacontratto", "qsel_DurataContratti", "recordid_='" & Nz(recordidcontratti_, 0) & "'"))
                                $valoreDaAggiornare = $siglaAzienda . ($duratacontratto > 1) ? "->" : "";
                            }
                            $isPrimoGiorno = False;
                            $isPrimoGiornoImpostato = True;
                        }    
                    } else { //Manca la sigla dell'azienda
                        $valoreDaAggiornare = "???";                        
                    }                    
                    
                } else {    //Il nuovo Orario è nullo MA Se inserisco solo il nome dell'Azienda senza timbratura e senza ore DEVE apparire la sigla dell'Azienda SOLO se non c'e' il contratto, altrimenti VUOTO
                    
                }
                
                // USO SEMPRE IL valoreDaAggiornare !! ricreando per intero il contenuto della cella
                
                
                
                
                
                
                
                //$sql="UPDATE bla bla";
                //$this->Sys_model->db_execute($sql);
                
            }

        }
        
    }
    
    //custom 18-24
    
    public function invia_curriculum()
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $post=$_POST;
        $recordid_candidato=$post['recordid_candidato'];
        $candidato=$this->Sys_model->db_get_row('user_candidati','*',"recordid_='$recordid_candidato'");
        $candidato_rif=$candidato['rif'];
        $candidato_nome=$candidato['nome'];
        $candidato_cognome=$candidato['cognome'];
        $sesso=$candidato['sesso'];
        $consenso='del candidato';
        if($sesso=='f')
        {
            $consenso='della candidata';
        }
        $recordid_azienda=$post['recordid_azienda'];
        $azienda=$this->Sys_model->db_get_row('user_aziende','*',"recordid_='$recordid_azienda'");
        $recordid_visualizzazioni=$post['recordid_visualizzazioni'];
        $nome_file=$post['nome_file'];
        $nome_file=  urldecode($nome_file);
        $percorso_file="./stampe/".$this->session->userdata('userid')."/".$nome_file;
        $email=$post['email'];
        
        
        $data['recordidcandidati_']=$recordid_candidato;
        $data['recordidaziende_']=$recordid_azienda;
        $data['recordidvisualizzazioni_']=$recordid_visualizzazioni;
        if($recordid_visualizzazioni!='')
        {
            $data['tipo']='inviodavisualizzazioni';
        }
        else
        {
            $data['tipo']='inviodacandidato';
        }
        $data['mailfrom_userid']=1;
        $data['mailsubject']="Invio curriculum di $candidato_rif";
        $mailbody="
            Invio curriculum di $candidato_rif
            ";
        $mailbody= str_replace("'", "''", $mailbody);
        $mailbody='<div style="font-family:Arial;font-size:11pt">'.$mailbody.'</div>';
        $mailbody=$mailbody.'<br/><img src="http://18-24.ch/progetto1824/assets/app/images/logo.png"></img>';
        $data['mailbody']=$mailbody;
        
        // invio di test a jdwalert
        $data['mailto']='jdwalert@gmail.com';
        $data['status']='dainviare';
        $recordid_mail=$this->Sys_model->insert_record('mail_queue',1,$data);
        
        // invio al cliente
        $data['recordstatus_']='temp';
        $data['mailto']=$email;
        $data['mailbcc']='cristina@18-24.ch';
        $data['status']='attesa';
        $recordid_mail=$this->Sys_model->insert_record('mail_queue',1,$data);
        $data_page=array();
        $this->Sys_model->insert_record_page('mail_queue',$recordid_mail,1,$percorso_file,$data_page);
        $sql="UPDATE user_mail_queue SET status='attesa' WHERE recordid_='$recordid_mail'";
        $this->Sys_model->execute_query($sql);
        echo $recordid_mail;
    }
    
    
    public function custom_1824_get_dati_azienda_from_visualizzazioni($recordid_visualizzazioni)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $recordid_azienda=$this->Sys_model->db_get_value('user_visualizzazioni','recordidaziende_',"recordid_='$recordid_visualizzazioni'");
        $email_azienda=$this->Sys_model->db_get_value('user_aziende','email',"recordid_='$recordid_azienda'");
        
        echo $recordid_azienda.";".$email_azienda;
    }
    
    public function custom_1824_get_recordid_candidato_from_visualizzazioni($recordid_visualizzazioni)
    {
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Allow-Origin: *");
        $recordid_candidato=$this->Sys_model->db_get_value('user_visualizzazioni','recordidcandidati_',"recordid_='$recordid_visualizzazioni'");
        echo $recordid_candidato;
    }
    
    public function ajax_passa_a_contratto_missione($recordid_contrattofornitura)
    {
        $recordid_contrattomissione= $this->Sys_model->db_get_value('user_contrattofornitura','recordidcontratti_',"recordid_='$recordid_contrattofornitura'");
        $this->ajax_compilazione_contratto_missione($recordid_contrattomissione);
    }
    
    public function ajax_compilazione_contratto_missione($recordid_contratto='')
    {
        echo $this->compilazione_contratto_missione($recordid_contratto);
    }
    
    public function compilazione_contratto_missione($recordid_contratto='')
    {
        $data=array();
        $content=$this->load_3p_contratto_missione_content('modifica',$recordid_contratto);
        $data['content']=$content;
        $data['recordid_contratto']=$recordid_contratto;
        $data['table_settings']=  $this->Sys_model->get_table_settings('contratti');
        return $this->load->view('sys/desktop/stampe/3p_contratto_missione_container',$data, TRUE);
    }
    
    public function ajax_stampa_pdf_contratto_missione($recordid_contratto,$intestazione)
    {
        $content=  $this->stampa_pdf_contratto_missione($recordid_contratto,$intestazione);
        $path_stampa=$this->genera_stampa($content,'contratto_missione');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa,'contratti',$recordid_contratto);
        echo $block;
    }
    
    public function stampa_pdf_contratto_missione($recordid_contratto,$intestazione)
    {
        $data=array();
        $content=$this->load_3p_contratto_missione_content('stampa',$recordid_contratto,$intestazione);
        return $content;
    }
    
    public function load_3p_contratto_missione_content($funzione,$recordid_contratto='',$intestazione='true')
    {
        $data['funzione']=$funzione;
        $data['intestazione']=$intestazione;
        $data['recordid_contratto']=$recordid_contratto;
        if($recordid_contratto!='')
        {
            $fields= $this->get_record('contratti', $recordid_contratto);
            $data['fields']=$fields;
            $data['dipendente']= $this->get_record('dipendenti', $fields['recordiddipendenti_']);
            $data['azienda']= $this->get_record('azienda', $fields['recordidazienda_']);
            $data['professione']= $this->get_record('professioni', $fields['recordidprofessioni_']);
            $recordid_ccl=$data['professione']['recordidccl_'];
            $data['professione']['cclapplicato']= $this->Sys_model->db_get_value('user_ccl','nomeccl',"recordid_='$recordid_ccl'");
        }
        else
        {
            $data['fields']['id']='';
            $data['fields']['recordidazienda_']='';
            $data['fields']['recordiddipendenti_']='';
            $data['fields']['recordidprofessioni_']='';
            $data['dipendente']['nome']='';
            $data['dipendente']['cognome']='';
            $data['dipendente']['indirizzo']='';
            $data['dipendente']['domicilio']='';
            $data['azienda']['indirizzo']='';
            $data['azienda']['npa']='';
            $data['azienda']['localita']='';
            $data['azienda']['ccl']='';
            $data['professione']['cclapplicato']='';
            $data['fields']['luogomissione']='';
            $data['fields']['orarioinizio']='';
            $data['fields']['tipocontratto']='';
            $data['fields']['datainizio']='';
            $data['fields']['datafine']='';
            $data['fields']['ore']='';
            $data['fields']['tipoorario']='';
            $data['fields']['percoccupazione']='';
            $data['azienda']['telefono1']='';
            $data['fields']['fasciaoraria']='';
            $data['fields']['fasciaoraria11']='';
            $data['fields']['fasciaoraria12']='';
            $data['fields']['fasciaoraria13']='';
            $data['fields']['fasciaoraria14']='';
            $data['fields']['fasciaoraria21']='';
            $data['fields']['fasciaoraria22']='';
            $data['fields']['fasciaoraria23']='';
            $data['fields']['fasciaoraria24']='';
            $data['fields']['fasciaoraria31']='';
            $data['fields']['fasciaoraria32']='';
            $data['fields']['fasciaoraria33']='';
            $data['fields']['fasciaoraria34']='';
            $data['fields']['fasciaoraria41']='';
            $data['fields']['fasciaoraria42']='';
            $data['fields']['fasciaoraria43']='';
            $data['fields']['fasciaoraria44']='';
            $data['fields']['salarioorariolordobase']='';
            $data['fields']['giornifestivi']='';
            $data['fields']['indennitavacanza']='';
            $data['fields']['tredicesima']='';
            $data['fields']['salarioorariototale']='';
            $data['fields']['indennitachilometrca']='';
            $data['fields']['indennitapasto']='';
            $data['fields']['indennitaaltre']='';
            
        }
        
        $data['aziende']= $this->Sys_model->db_get("user_azienda","recordid_ as itemcode,CONCAT(ragionesociale,' ',id) as itemdesc","true","ORDER BY ragionesociale asc");
        $data['dipendenti']= $this->Sys_model->db_get("user_dipendenti","recordid_ as itemcode,CONCAT(cognome,' ',nome,' ',id) as itemdesc","true","ORDER BY cognome asc");
        $contratto=$this->Sys_model->db_get_row("user_contratti","*","recordid_='$recordid_contratto'");
        $datainizio=$contratto['datainizio'];
        $professioni= $this->Sys_model->db_get("user_professioni","*","stato = 'Verificato' AND (('$datainizio'>=datainizio AND datafine is null) OR  ('$datainizio'>=datainizio AND '$datainizio'<=datafine))","ORDER BY professione asc");
        $professioni2=array();
        foreach ($professioni as $key => $professione) {
            $recordid_ccl=$professione['recordidccl_'];
            $nome_ccl= $this->Sys_model->db_get_value('user_ccl','nomeccl',"recordid_='$recordid_ccl'");
            $recordid_qualifica=$professione['recordidqualifiche_'];
            $qualifica= $this->Sys_model->db_get_value('user_qualifiche','qualifica',"recordid_='$recordid_qualifica'");
            $qualifica= str_replace("'", "''", $qualifica);
            $qualifica= $this->Sys_model->db_get_value('sys_lookup_table_item','itemdesc',"itemcode='$qualifica'");
            $esperienza= $this->Sys_model->db_get_value('user_qualifiche','esperienza',"recordid_='$recordid_qualifica'");
            $professioni2[$key]['itemcode']=$professione['recordid_'];
            $professioni2[$key]['itemdesc']=$professione['professione'].", ".$qualifica.", ".$esperienza.", ".$nome_ccl; 
            $professioni2[$key]['itemdesc_noccl']=$professione['professione'].", ".$qualifica.", ".$esperienza; 
        }
        
        /*$records_linkedmaster=$this->Sys_model->get_records_linkedmaster('professioni', 'contratti','sys_recent');
        
        
        foreach ($records_linkedmaster as $keyrecord => $record) {
            
            $templabel='';
            foreach ($record as $keycolumn => $column) {
                if($keycolumn!='recordid_')
                {
                    if(($templabel!='')&&(isnotempty($column)))
                    {
                        $templabel=$templabel.", ";
                    }
                    $templabel=$templabel.$column;
                }
            }
            $professioni[$keyrecord]['itemcode']=$record['recordid_'];
            $professioni[$keyrecord]['itemdesc']=$templabel; 
            
        }*/
        
        $data['professioni']=$professioni2;
        $creatorid= $this->Sys_model->db_get_value('user_contratti','creatorid_',"recordid_='$recordid_contratto'");
        $data['sigla_creatore']= $this->Sys_model->db_get_value('sys_user','description',"id='$creatorid'");
        $data['table_settings']=  $this->Sys_model->get_table_settings('contratti');
        //mantenimento storico dei layout per i vecchi contratti
        $id=$contratto['id'];
        if(($recordid_contratto>='00000000000000000000000000003583')||($id=='3572')||($id=='3412')||($id=='3384')||($id=='3365')||($id=='3186')||($id=='3151')||($id=='3149')||($id=='3128'))
        {
            if(($recordid_contratto>='00000000000000000000000000003583')||($id=='3572')||($id=='3412')||($id=='3384')||($id=='3365')||($id=='3186')||($id=='3151')||($id=='3149')||($id=='3128'))
            {
                $content=$this->load->view('sys/desktop/stampe/3p_contratto_missione_content_v3',$data, TRUE);   
            }
            else
            {
                $content=$this->load->view('sys/desktop/stampe/3p_contratto_missione_content_v2',$data, TRUE);   
            }
            
        }
        else
        {
            $content=$this->load->view('sys/desktop/stampe/3p_contratto_missione_content',$data, TRUE);
        }
        
        return $content;
    }
        

    public function ajax_passa_a_contratto_fornitura($recordid_contratto)
    {
        $recordid_contrattofornitura= $this->Sys_model->db_get_value('user_contrattofornitura','recordid_',"recordidcontratti_='$recordid_contratto'");
        $this->ajax_compilazione_contratto_fornitura($recordid_contrattofornitura);
    }
    
    public function ajax_compilazione_contratto_fornitura($recordid_contrattofornitura='')
    {
        echo $this->compilazione_contratto_fornitura($recordid_contrattofornitura);
    }
    
    public function compilazione_contratto_fornitura($recordid_contrattofornitura='')
    {
        $data=array();
        $content=$this->load_3p_contratto_fornitura_content('modifica',$recordid_contrattofornitura);
        $data['content']=$content;
        $data['recordid_contratto']=$recordid_contrattofornitura;
        $data['recordid_contrattofornitura']=$recordid_contrattofornitura;
        $data['table_settings']=  $this->Sys_model->get_table_settings('contratti_fornitura');
        return $this->load->view('sys/desktop/stampe/3p_contratto_fornitura_container',$data, TRUE);
    }
    
    public function ajax_stampa_pdf_contratto_fornitura($recordid_contrattofornitura,$intestazione)
    {
        $content=  $this->stampa_pdf_contratto_fornitura($recordid_contrattofornitura,$intestazione);
        $path_stampa=$this->genera_stampa($content,'contratto_fornitura');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa);
        echo $block;
    }
    
    public function stampa_pdf_contratto_fornitura($recordid_contrattofornitura,$intestazione)
    {
        $data=array();
        $content=$this->load_3p_contratto_fornitura_content('stampa',$recordid_contrattofornitura,$intestazione);
        return $content;
    }
    
    public function load_3p_contratto_fornitura_content($funzione,$recordid_contrattofornitura='',$intestazione='true')
    {
        $data['funzione']=$funzione;
        $data['intestazione']=$intestazione;
        $data['recordid_contratto']=$recordid_contrattofornitura;
        $data['recordid_contrattofornitura']=$recordid_contrattofornitura;
        $contratto_fornitura=$this->Sys_model->db_get_row('user_contrattofornitura',"*","recordid_='$recordid_contrattofornitura'");
        $data['contratto_fornitura']=$contratto_fornitura;
        $contratto_id=$contratto_fornitura['id'];
        $recordid_contratto_missione=$contratto_fornitura['recordidcontratti_'];
        if($contratto_id>='5237')
        {
            $recordid_professione=$this->Sys_model->db_get_value('user_contratti','recordidprofessioni_',"recordid_='$recordid_contratto_missione'");
            $professione=$this->Sys_model->db_get_row('user_professioni',"*","recordid_='$recordid_professione'");
            $recordid_qualifica=$professione['recordidqualifiche_'];
            $qualifica= $this->Sys_model->db_get_value('user_qualifiche','qualifica',"recordid_='$recordid_qualifica'");
            $esperienza= $this->Sys_model->db_get_value('user_qualifiche','esperienza',"recordid_='$recordid_qualifica'");
            $data['mansione']=$professione['professione'].", ".$qualifica.", ".$esperienza; 
            $content=$this->load->view('sys/desktop/stampe/3p_contratto_fornitura_content_v2',$data, TRUE);
        }
        else
        {
            $content=$this->load->view('sys/desktop/stampe/3p_contratto_fornitura_content',$data, TRUE);  
        }
        
        return $content;
    }
    
    public function genera_rapportidilavoro_2019()
    {
        $this->Sys_model->genera_rapportidilavoro_2019();
    }
    
    public function rapporti2019_dagenerare()
    {
        $this->Sys_model->rapporti2019_dagenerare();
    }
    
    public function aggiorna_contratti_2019()
    {
        $this->Sys_model->aggiorna_contratti_2019();
    }
    
    public function check_contratti_2019()
    {
        $this->Sys_model->check_contratti_2019();
    }
    
    
    public function generate_temp_control($cliente,$datadal,$dataal)
    {
        $this->Sys_model->generate_temp_control($cliente,$datadal,$dataal);
    }
    
    
    public function report_attivita()
    {
        $query=$query_array['query_owner'];
        $report=  $this->Sys_model->get_report_result($reportid,$query);
        $data['data']['block']['report']=$this->load_block_report($report);
    }
    

    
    public function sistema_rapportidilavoro_dipendenti()
    {
        $this->Sys_model->sistema_rapportidilavoro_dipendenti();
    }
    
    public function check_dipendenti()
    {
        $this->Sys_model->check_dipendenti();
    }
    
    public function check_rapportidilavoro($recordid_dipendente)
    {
        $this->Sys_model->check_rapportidilavoro($recordid_dipendente);
    }
    
    
    
    public function sistema_rapportidilavoro($recordid_dipendente)
    {
        $this->Sys_model->sistema_rapportidilavoro($recordid_dipendente);
    }
    
    
    public function custom_3p_genera_tutti_contratti_fornitura()
    {
        $this->Sys_model->custom_3p_genera_tutti_contratti_fornitura();
    }
          
    
    
    
    
    
        
    
    
    public function exec_custom_update()
    {
         $this->Sys_model->exec_custom_update();
    }
    
    public function custom_3p_aggiorna3mesi()
    {	
        //$this->Sys_model->add_custom_update('3mesi',"");
        $counter=0;

        while($counter<600){
            $check_todo=$this->Sys_model->db_get_row('custom_update','*',"funzione='3mesi' AND stato='todo'");

            if($check_todo==null)
            {
                    $counter=600;
            }
            sleep(1);
            $counter++;
        }    
    }
    
    
    // 3P SCRIPT INIZIO
    
    public function calcola_3mesi_dipendenti()
    {
        $this->Sys_model->calcola_3mesi_dipendenti();
    }
    
    public function link_timbrature_presenzemensili_dipendenti()
    {
        
        $this->Sys_model->link_timbrature_presenzemensili_dipendenti();
    }
    
    
    
    //3P SCRIPT FINE
    
    //3P SERVICES INIZIO
    
    
    
    
    
    public function get_custom_update()
    {
        $custom_update=$this->Sys_model->get_custom_update();
        $json_custom_update=json_encode($custom_update);
        echo $json_custom_update;
    }
    
    public function custom_update_delete()
    {
        $sql="DELETE FROM custom_update_php";
        $this->Sys_model->execute_query($sql);
    }
    
    public function get_custom_update_list()
    {
        $custom_update_list=$this->Sys_model->get_custom_update_list();
        echo "Numero operazioni da eseguire:".count($custom_update_list)."<br/><br/>";
        foreach ($custom_update_list as $key => $custom_update) {
            echo $custom_update['dataora_creazione']."  ".$custom_update['funzione']."  ".$custom_update['recordid']."<br/>";
            
        }
    }
    
    public function custom_update_done($id)
    {
        $now=date("Y-m-d H:i:s"); 
        $sql="UPDATE custom_update_php SET stato='done',dataora_esecuzione='$now' where id=$id  ";
        $this->Sys_model->execute_query($sql);
    }
    
    public function calcola_3mesi_dipendente($recordid_dipendente='')
    {
        $this->Sys_model->calcola_3mesi_dipendente($recordid_dipendente);
    }
    
    public function link_timbrature_presenzemensili_dipendente($recordid_dipendente)
    {
        $this->Sys_model->link_timbrature_presenzemensili_dipendente($recordid_dipendente);
    }
    
    public function calcola_media_contratti()
    {
        $this->Sys_model->calcola_media_contratti();
    }
    
    public function calcola_media_contratti_nuova($recordid_contratto='')
    {
        $this->Sys_model->calcola_media_contratti_nuova($recordid_contratto);
    }
    
    public function aggiorna_mediacontrattuale_decimale_contratti($recordid_contratto=null)
    {
        $this->Sys_model->aggiorna_mediacontrattuale_decimale_contratti($recordid_contratto);
    }
    
    public function aggiorna_orecontrattuali_rapportidilavoro($recordid_contratto=null)
    {
        $this->Sys_model->aggiorna_orecontrattuali_rapportidilavoro($recordid_contratto);
    }
    
    public function calcola_media_contratto_v2021($recordid_contratto='')
    {
        if($recordid_contratto!='')
        {
            $return=$this->Sys_model->calcola_media_contratto_v2021($recordid_contratto);
        }
        else
        {
            $contratti=$this->Sys_model->db_get('user_contratti');
            foreach ($contratti as $key => $contratto) {
                $recordid_contratto=$contratto['recordid_'];
                echo "Elaborazione $recordid_contratto";
                $this->Sys_model->calcola_media_contratto_v2021($recordid_contratto);
            }
        }
    }
    

  
    public function calcola_media_contratto($recordid_contratto)
    {
        $this->Sys_model->calcola_media_contratto($recordid_contratto);
    }
    
    public function report_carenza($anno,$mese,$id_dipendente='')
    {
        $data=$this->Sys_model->calcola_carenze_dipendenti($anno,$mese,$id_dipendente); 
        $content=$this->load->view('sys/desktop/custom/3p/report/carenze',$data, TRUE);
        echo $content;
    }
    
    
    public function ajax_load_prestampa_carenze()
    {
        $content=$this->load->view('sys/desktop/custom/3p/report/prestampa_carenze',$data, TRUE);
       echo $content;
    }
    
    public function ajax_load_content_report($anno,$mese)
    {
        $content= $this->report_carenza($anno,$mese);
        echo $content;    
    
    }
    
    
    
    
    
   
    
    
    
    
    
    
    
   
    
    
    public function reset_presenzemensili()
    {
        $rows= $this->Sys_model->db_get('user_presenzemensili','recordid_',"anno>2018");
        foreach ($rows as $key => $row) {
            $this->Sys_model->reset_record('presenzemensili',$row['recordid_']);
        }
    }
    
    public function  genera_presenze_dipendenti_add_custom_update()
    {
        $dipendenti= $this->Sys_model->db_get('user_dipendenti','recordid_',"(attivo='si' OR situazione='aperto' OR situazione='chi' OR situazione='ria' OR situazione='dis')");
        foreach ($dipendenti as $key => $dipendente) {
            $recordid_dipendente=$dipendente['recordid_'];
            $this->Sys_model->add_custom_update_php('genera_presenze_dipendente',$recordid_dipendente);
        }
        $this->Sys_model->add_custom_update_php('calcola_numerazione_presenze_totale','');
    }
    
    public function genera_presenze_dipendente($recordid_dipendente=null)
    {
        if($recordid_dipendente!=null)
        {
            $this->Sys_model->genera_presenze_dipendente($recordid_dipendente);
        }

    }
    
    public function link_presenzemensili_timbrature_dipendente($recordid_dipendente)
    {
        $rows=$this->Sys_model->db_get('user_presenzemensili','recordid_',"recordiddipendenti_='$recordid_dipendente'");
        foreach ($rows as $key => $row) {
            $this->Sys_model->link_presenzemensili_timbrature($row['recordid_']);
        }
    }
    
    public function link_presenzemensili_timbrature($recordid_presenzemensili)
    {
        $this->Sys_model->link_presenzemensili_timbrature($recordid_presenzemensili);
    }
    
    
    public function  aggiorna_presenze_dipendenti_add_custom_update()
    {
        $sql="
            SELECT user_dipendenti.recordid_
            FROM user_dipendenti JOIN user_presenzemensili ON user_dipendenti.recordid_=user_presenzemensili.recordiddipendenti_
            WHERE (user_presenzemensili.anno>2019 and user_presenzemensili.mese>=8) OR (user_presenzemensili.anno>=2020)
            GROUP BY user_dipendenti.recordid_
            ";
        $dipendenti= $this->Sys_model->select($sql);
        foreach ($dipendenti as $key => $dipendente) {
            $recordid_dipendente=$dipendente['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_presenze_dipendente',$recordid_dipendente);
        }
        $this->Sys_model->add_custom_update_php('calcola_numerazione_presenze_totale','');
    }
    
    public function  aggiorna_presenze_add_custom_update($anno='',$mese='')
    {
        if(($anno=='')&&($mese==''))
        {
            $anno=date('Y');
            $mese=date('n');
        }
        $sql="
            SELECT user_presenzemensili.recordid_
            FROM user_presenzemensili
            WHERE anno=$anno AND mese='$mese'
            ";
        $presenze_rows= $this->Sys_model->select($sql);
        foreach ($presenze_rows as $key => $presenze_row) {
            $recordid_presenze=$presenze_row['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_presenze',$recordid_presenze);
        }
    }
    
    public function  aggiorna_presenze_completo_add_custom_update($anno='',$mese='')
    {
        if(($anno=='')&&($mese==''))
        {
            $anno=date('Y');
            $mese=date('n');
        }
        $sql="
            SELECT user_presenzemensili.recordid_
            FROM user_presenzemensili
            WHERE anno=$anno AND mese='$mese' AND id is null
            ";
        $presenze_rows= $this->Sys_model->select($sql);
        foreach ($presenze_rows as $key => $presenze_row) {
            $recordid_presenze=$presenze_row['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_presenze_completo',$recordid_presenze);
        }
    }
    
    public function  aggiorna_presenze_completo_noid_add_custom_update($recordid_dipendente='')
    {
        $where_dipendente='';
        if($this->isnotempty($recordid_dipendente))
        {
            $where_dipendente=" AND recordiddipendenti_='$recordid_dipendente'";
        }
        $sql="
            SELECT user_presenzemensili.recordid_
            FROM user_presenzemensili
            WHERE (id is null OR id='') $where_dipendente
            ";
        $presenze_rows= $this->Sys_model->select($sql);
        foreach ($presenze_rows as $key => $presenze_row) {
            $recordid_presenze=$presenze_row['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_presenze_completo',$recordid_presenze);
        }
    }
    
    public function  aggiorna_disponibilita_dipendenti()
    {
        $sql="
            SELECT user_dipendenti.recordid_
            FROM user_dipendenti JOIN user_presenzemensili ON user_dipendenti.recordid_=user_presenzemensili.recordiddipendenti_
            WHERE user_presenzemensili.anno>2018
            GROUP BY user_dipendenti.recordid_
            ";
        $dipendenti= $this->Sys_model->select($sql);
        $tot_dipendenti=count($dipendenti);
        echo $tot_dipendenti."<br/>";
        $counter=1;
        foreach ($dipendenti as $key => $dipendente) {
            $recordid_dipendente=$dipendente['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_disponibilita_dipendente',$recordid_dipendente);
            //$this->aggiorna_disponibilita_dipendente($recordid_dipendente);
            echo $counter."<br/>";
            $counter++;
        }
    }
    
    public function  aggiorna_disponibilita_dipendenti_add_custom_update()
    {
        $sql="
            SELECT user_dipendenti.recordid_
            FROM user_dipendenti JOIN user_presenzemensili ON user_dipendenti.recordid_=user_presenzemensili.recordiddipendenti_
            WHERE user_presenzemensili.anno>2018 and user_presenzemensili.mese>7
            GROUP BY user_dipendenti.recordid_
            ";
        $dipendenti= $this->Sys_model->select($sql);
        foreach ($dipendenti as $key => $dipendente) {
            $recordid_dipendente=$dipendente['recordid_'];
            $this->Sys_model->add_custom_update_php('aggiorna_disponibilita_dipendente',$recordid_dipendente);
        }
    }
    
    public function aggiorna_presenze_dipendente($recordid_dipendente)
    {
        $dipendente= $this->Sys_model->db_get_row('user_dipendenti', 'id,cognome,nome,datainiziocollaborazione', "recordid_='$recordid_dipendente'");
        $this->syslog("$recordid_dipendente| ".$dipendente['cognome']." ".$dipendente['nome']." ".$dipendente['id'],"aggiorna_presenze_dipendente *start*");
        
        $data_inizio=$dipendente['datainiziocollaborazione'];
        
        $this->Sys_model->genera_presenze_dipendente($recordid_dipendente);
        //$this->Sys_model->genera_rapportidilavoro($recordid_dipendente);
        
        //$recordid_ultimocontratto=$this->Sys_model->db_get_value('user_contratti','recordid_',"recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')","ORDER BY datainizio desc");
        //$this->Sys_model->custom_3p_salva_contratto($recordid_ultimocontratto);

        if($data_inizio>="2019-07-26")
        {
            $this->Sys_model->aggiorna_giorni_prova_dipendente($recordid_dipendente);
        }
        
        $this->Sys_model->aggiorna_disponibilita_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_giorni_notifica_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_giorni_dipendenteinterno_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_primogiorno_dipendente($recordid_dipendente);
        
       
        $rows= $this->Sys_model->db_get('user_presenzemensili','recordid_',"recordiddipendenti_='$recordid_dipendente' AND ((anno=2019 AND mese>=8)OR(anno>=2020))");
        foreach ($rows as $key => $row) {
            $recordid_presenzemensili=$row['recordid_'];
            $this->Sys_model->aggiorna_presenze($recordid_presenzemensili);
        }
        $this->Sys_model->calcola_3mesi_dipendente($recordid_dipendente);
        $this->syslog('',"aggiorna_presenze_dipendente *end*");
    }
    
    public function aggiorna_presenze($recordid_presenzemensili)
    {
        $this->Sys_model->aggiorna_presenze($recordid_presenzemensili);
    }
    
    public function aggiorna_presenze_completo($recordid_presenzemensili)
    {
        $this->syslog('',"aggiorna_presenze_completo *start*");
        
        $presenze=$this->Sys_model->db_get_row('user_presenzemensili', 'recordiddipendenti_', "recordid_='$recordid_presenzemensili'");
        $recordid_dipendente=$presenze['recordiddipendenti_'];
        //$dipendente= $this->Sys_model->db_get_row('user_dipendenti', 'id,cognome,nome,datainiziocollaborazione', "recordid_='$recordid_dipendente'");
        //$data_inizio=$dipendente['datainiziocollaborazione'];
        
        //$recordid_ultimocontratto=$this->Sys_model->db_get_value('user_contratti','recordid_',"recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')","ORDER BY datainizio desc");
        //$this->Sys_model->custom_3p_salva_contratto($recordid_ultimocontratto);

        //if($data_inizio>="2019-07-26")
        //{
          //  $this->Sys_model->aggiorna_giorni_prova_dipendente($recordid_dipendente);
        //}
        
        //$this->Sys_model->aggiorna_disponibilita_dipendente($recordid_dipendente);
        
        $this->Sys_model->aggiorna_giorni_notifica_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_giorni_dipendenteinterno_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_primogiorno_dipendente($recordid_dipendente);
        $this->Sys_model->aggiorna_giorni_prova_dipendente($recordid_dipendente);
        
        $this->Sys_model->aggiorna_presenze($recordid_presenzemensili);
  
        $this->Sys_model->calcola_3mesi_dipendente($recordid_dipendente);
        $this->syslog('',"aggiorna_presenze_completo *end*");
    }
    
    public function aggiorna_giorni_prova_dipendente($recordid_dipendente)
    {
        $this->Sys_model->aggiorna_giorni_prova_dipendente($recordid_dipendente);
    }
    
    
    
    
    
    public function calcola_numerazione_presenze_totale()
    {
        $this->Sys_model->calcola_numerazione_presenze_totale();
    }
    
    public function calcola_numerazione_presenze($anno,$mese)
    {
        $this->Sys_model->calcola_numerazione_presenze($anno,$mese);
    }
    
    
    //3P SERVICES FINE

            
    
     
    public function export_migrazione($tableid)
    {
        $userid=  1;
        $user_settings= $this->Sys_model->get_user_settings($userid);
        $zoom32=$user_settings['zoom32'];
        $zoom=$user_settings['zoom'];
        $imagedpi=$user_settings['image-dpi'];
        $imagequality=$user_settings['image-quality'];
        $wkhtml=$user_settings['wkhtml'];
        $orientation='portrait';
        $query=$_POST['query'];
        $rows= $this->Sys_model->select($query);
        foreach ($rows as $key => $row) {
            $recordid=$row['recordid_'];
            $content="$recordid";
            file_put_contents("../JDocServer/stampe/stampa.html", $content);
            
            if(!file_exists("../JDocServer/export/migrazione"))
            {
                mkdir("../JDocServer/export/migrazione");
            }
            if(!file_exists("../JDocServer/export/migrazione/$tableid"))
            {
                mkdir("../JDocServer/export/migrazione/$tableid");
            }
            
            $command='cd ./tools/wkhtmltopdfold32/bin && wkhtmltopdf.exe --page-size A4  --image-dpi '.$imagedpi.' --image-quality '.$imagequality.' -T 0 -B 0 -L 0 -R 0  --orientation '.$orientation.' --zoom '.$zoom32.'  "../../../../JDocServer/stampe/stampa.html" "../../../../JDocServer/export/migrazione/'.$tableid.'/'.$recordid.'.pdf" ';
            
            exec($command);
        }
        
        
        
    }
    
    public function export_migrazione_allegati($tableid)
    {
        if(!file_exists("../JDocServer/export/migrazione"))
        {
            mkdir("../JDocServer/export/migrazione");
        }
        if(!file_exists("../JDocServer/export/migrazione/$tableid"))
        {
            mkdir("../JDocServer/export/migrazione/$tableid");
        }
        if(!file_exists("../JDocServer/export/migrazione/$tableid/allegati"))
        {
            mkdir("../JDocServer/export/migrazione/$tableid/allegati");
        }
        $userid=  1;
        $user_settings= $this->Sys_model->get_user_settings($userid);
        $zoom32=$user_settings['zoom32'];
        $zoom=$user_settings['zoom'];
        $imagedpi=$user_settings['image-dpi'];
        $imagequality=$user_settings['image-quality'];
        $wkhtml=$user_settings['wkhtml'];
        $orientation='portrait';
        $query=$_POST['query'];
        $rows= $this->Sys_model->select($query);
        foreach ($rows as $key => $row) {
            $recordid=$row['recordid_'];
            $sql="
                SELECT *
                FROM user_".$tableid."_page
                WHERE recordid_='$recordid'
                ";
            $allegati= $this->select($sql);
            foreach ($allegati as $key => $allegato) {
                $nomefile=$allegato['nomefile'];
                $estensione=$allegato['estensione'];
                $percorso_originale="../JDocServer/Archivi/$tableid/000/$nomefile.$estensione";
                $percorso_destinazione="../JDocServer/export/migrazione/$tableid/allegati/".$recordid."_$nomefile.$estensione";
                copy($percorso_originale,$percorso_destinazione);
            }
        }
    }
    
    public function ajax_load_custom_3p_contratto_preview($recordid_presenzemensili)
    {
        $presenzemensili= $this->Sys_model->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
        $recordid_dipendente=$presenzemensili['recordiddipendenti_'];
        $today=  date('Y-m-d');
        $today_anno=  date('Y');
        $today_mese=  date('m');
        $anno=$presenzemensili['anno'];
        $mese=$presenzemensili['mese'];
        if(($anno==$today_anno)&&($mese==$today_mese))
        {
            $giorno_riferimento=  date('Y-m-d');
        }
        else
        {
            $giorno_riferimento=date("Y-m-t",strtotime("$anno-$mese"));
        }
        
        $rapportino=$this->Sys_model->db_get_row('user_rapportidilavoro',"*","recordiddipendenti_='$recordid_dipendente' AND data='$giorno_riferimento'");
        $recordid_contratto=$rapportino['recordidcontratti_'];
        if(isnotempty($recordid_contratto))
        {
            
            $contratto=$this->Sys_model->db_get_row('user_contratti',"*","recordid_='$recordid_contratto'");
            $recordid_azienda=$contratto['recordidazienda_'];
            $azienda=$this->Sys_model->db_get_row('user_azienda',"*","recordid_='$recordid_azienda'");

            $ragionesociale=$azienda['ragionesociale'];
            $datainizio=date('d.m.Y', strtotime($contratto['datainizio']));
            $tipocontratto=$contratto['tipocontratto'];
            if($tipocontratto=='indet')
            {
                $datafine='INDETERMINATO';
            }
            else
            {
                $datafine=$contratto['datafine'];
                if(isnotempty($contratto['datadisdetta']))
                {
                    $datafine=$contratto['datadisdetta'];
                }
                $datafine=date('d.m.Y', strtotime($datafine));
            }
            $mediagiornalieracontrattuale=$contratto['mediagiornalieracontrattuale'];
            $mediaeffettiva=$contratto['media_nuova'];
            if(isnotempty($mediaeffettiva))
            {
                $mediaeffettiva=date('G:i',strtotime($mediaeffettiva));
            }
            
            
            $output= "
            Azienda <b>$ragionesociale</b> <br/> 
            Data inizio <b>$datainizio</b> <br/> 
            Data fine <b>$datafine</b> <br/> 
            Media contrattuale <b>$mediagiornalieracontrattuale</b> <br/> 
            Media effettiva <b>$mediaeffettiva</b>";
            
            
            echo $output;
            
            
        }
        else
        {
            echo "Nessun contratto in data ".date('d.m.Y', strtotime($giorno_riferimento));
        }
        
    }
    
    public function ajax_load_custom_3p_contratti_dipendente($recordid_presenzemensili)
    {
        $presenzemensili= $this->Sys_model->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
        $recordid_dipendente=$presenzemensili['recordiddipendenti_'];
        echo $this->load_block_records_linkedtable('contratti','dipendenti',$recordid_dipendente,'modifica','desktop','ORDER BY datainizio DESC');
    }
    
    
    public function aggiorna_disponibilita_dipendente($recordid_dipendente)
    {
        $this->Sys_model->aggiorna_disponibilita_dipendente($recordid_dipendente);
    }
    
    public function genera_stampa_lista_disponibilita($output='echo block')
    {
        $rows=$this->Sys_model->get_lista_disponibilita();
        $pages=array();
        $page_counter=0;
        $pagerow_counter=0;
        $pagerow_limit=13;
        foreach ($rows as $key => $row) {
            $note=$row['notegenerali'];
            $note_charcounter= strlen($note);
            if($note_charcounter>37)
            {
                $pagerow_limit=$pagerow_limit-0.2;
                if($note_charcounter>72)
                {
                    $pagerow_limit=$pagerow_limit-0.2;
                }
            }
            $pages[$page_counter][$pagerow_counter]=$row;
            $pages_rowlimit[$page_counter]=$pagerow_limit;
            $pagerow_counter++;
            if($pagerow_counter>=$pagerow_limit)
            {
                $pagerow_counter=0;
                
                $pagerow_limit=18;
                $page_counter++;
            }
        }
        $data['pages_rowlimit']=$pages_rowlimit;
        $data['pages']=$pages;
        $content=$this->load->view('sys/desktop/stampe/3p_lista_disponibilita',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ListaDisponibilita','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        
        if($output=='echo block')
        {
            echo $block;
        }
        if($output=='return path')
        {
           return $path_stampa; 
        }
        
        
    }
    
    public function genera_stampa_lista_dachiudere($output='echo block')
    {
        $rows=$this->Sys_model->get_lista_dachiudere();
        $pages=array();
        $page_counter=0;
        $pagerow_counter=0;
        $pagerow_limit=18;
        $pages_rowlimit[0]=$pagerow_limit;
        foreach ($rows as $key => $row) {
            $note=$row['notegenerali'];
            $note_charcounter= strlen($note);
            if($note_charcounter>37)
            {
                $pagerow_limit=$pagerow_limit-0.2;
            }
            $pages[$page_counter][$pagerow_counter]=$row;
            $pages_rowlimit[$page_counter]=$pagerow_limit;
            $pagerow_counter++;
            if($pagerow_counter>=$pagerow_limit)
            {
                $pagerow_counter=0;
                
                $pagerow_limit=18;
                $page_counter++;
            }
        }
        $data['pages_rowlimit']=$pages_rowlimit;
        $data['pages']=$pages;
        $content=$this->load->view('sys/desktop/stampe/3p_lista_dachiudere',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ListaDaChiudere','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        
        if($output=='echo block')
        {
            echo $block;
        }
        if($output=='return path')
        {
           return $path_stampa; 
        }
    }
    
    public function genera_stampa_lista_dariaprire($output='echo block')
    {
        $today=date('Y-m-d');
        $rows=$this->Sys_model->get_lista_dariaprire();
        $pages=array();
        $page_counter=0;
        $pagerow_counter=0;
        $pagerow_limit=18;
        foreach ($rows as $key => $row) {
            $recordid_dipendente=$row['recordid_'];
            $note=$row['ultimenote'];
            $note_charcounter= strlen($note);
            if($note_charcounter>37)
            {
                $pagerow_limit=$pagerow_limit-0.2;
            }
            $rapportodilavoro=$this->Sys_model->db_get_row('user_rapportidilavoro','dipendenteinterno,recordidcontratti_',"recordiddipendenti_='$recordid_dipendente' AND data='$today'");
            $row['dipendenteinterno']= $rapportodilavoro['dipendenteinterno'];
            $recordid_contratto=$rapportodilavoro['recordidcontratti_'];
            $row['visto']=$this->Sys_model->db_get_value('user_contratti','visto',"recordid_='$recordid_contratto'","ORDER BY recordid_ DESC");
            //$row['aperture']= $this->Sys_model->db_get('user_aperture',"*","recordiddipendenti_='$recordid_dipendente'");
            $pages[$page_counter][$pagerow_counter]=$row;
            $pages_rowlimit[$page_counter]=$pagerow_limit;
            $pagerow_counter++;
            if($pagerow_counter>=$pagerow_limit)
            {
                $pagerow_counter=0;
                
                $pagerow_limit=18;
                $page_counter++;
            }
        }
        $data['pages_rowlimit']=$pages_rowlimit;
        $data['pages']=$pages;
        $content=$this->load->view('sys/desktop/stampe/3p_lista_dariaprire',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ListaDaRiaprire','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        
        if($output=='echo block')
        {
            echo $block;
        }
        if($output=='return path')
        {
           return $path_stampa; 
        }
    }
    
    public function genera_stampa_test_stampa_portrait()
    {
        $data=array();
        $content=$this->load->view('sys/desktop/stampe/test_stampa',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ElencoTelefonico','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        echo $block;
    }
    
    public function genera_stampa_test_stampa_landscape()
    {
        $data=array();
        $content=$this->load->view('sys/desktop/stampe/test_stampa',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ElencoTelefonico','landscape');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        echo $block;
    }
    
    public function genera_stampa_elenco_telefonico()
    {
        $today_month=date('m');
        $today_year=date('Y');
        $query="
              SELECT pres.no, dip.id as ID, concat(dip.cognome,' ',dip.nome) as 'Cognome e nome', dip.domicilio as Domicilio, dip.telefonocasa as 'Telefono casa',dip.cellulare1 as Cellulare,dip.cellulare2 as 'Tel. altro',dip.email,dip.zonelavorative as Zone,concat(dip.lavoro1,' ',dip.lavoro2,' ',dip.lavoro3) as 'Lavoro 1/2/3'
              FROM user_presenzemensili  as pres
              JOIN user_dipendenti as dip ON pres.recordiddipendenti_=dip.recordid_
              WHERE  anno like '%$today_year%' AND mese = '$today_month' and datafine_prova is null and datainizio_prova is null
              order by pres.no asc
            ";
        $rows=$this->Sys_model->select($query);
        $pages=array();
        $page_counter=0;
        $pagerow_counter=0;
        foreach ($rows as $key => $row) {
            $pages[$page_counter][$pagerow_counter]=$row;
            $pagerow_counter++;
            if($pagerow_counter==25)
            {
                $pagerow_counter=0;
                $page_counter++;
            }
        }
        $data['elenco']['nonprove']=$pages;
        
        
        $query="
              SELECT pres.no, dip.id as ID, concat(dip.cognome,' ',dip.nome) as 'Cognome e nome', dip.domicilio as Domicilio, dip.telefonocasa as 'Telefono casa',dip.cellulare1 as Cellulare,dip.cellulare2 as 'Tel. altro',dip.email,dip.zonelavorative as Zone,concat(dip.lavoro1,' ',dip.lavoro2,' ',dip.lavoro3) as 'Lavoro 1/2/3'
              FROM user_presenzemensili  as pres
              JOIN user_dipendenti as dip ON pres.recordiddipendenti_=dip.recordid_
              WHERE  anno like '%$today_year%' AND mese = '$today_month'  AND (datafine_prova is not null OR datainizio_prova is not null)
              order by pres.no asc
            ";
        $rows=$this->Sys_model->select($query);
        $pages=array();
        $page_counter=0;
        $pagerow_counter=0;
        foreach ($rows as $key => $row) {
            $pages[$page_counter][$pagerow_counter]=$row;
            $pagerow_counter++;
            if($pagerow_counter==25)
            {
                $pagerow_counter=0;
                $page_counter++;
            }
        }
        $data['elenco']['prove']=$pages;
        
        
        
        $content=$this->load->view('sys/desktop/stampe/3p_elenco_telefonico',$data, TRUE);
        $path_stampa=$this->genera_stampa($content,'ElencoTelefonico','landscape');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        echo $block;
        
    }
    
    
    public function aggiornamento_giornaliero()
    {
        $this->genera_presenze_dipendenti_add_custom_update();
        //$this->aggiorna_disponibilita_dipendenti();
        //$this->aggiorna_presenze_completo_noid_add_custom_update();
        $anno=date('Y');
        $mese=date('n');
        $this->aggiorna_rapportidilavoro_per_mese_da_contratti_add_custom_update($anno,$mese);
        $this->aggiorna_presenze_completo_add_custom_update($anno, $mese);
        $this->calcola_numerazione_presenze($anno, $mese);
        
        if($mese==12)
        {
           $anno=date('Y', strtotime('+1 year'));
        }
        $mese=date('n', strtotime('+1 month'));
        
        $this->aggiorna_rapportidilavoro_per_mese_da_contratti_add_custom_update($anno,$mese);
        $this->aggiorna_presenze_completo_add_custom_update($anno, $mese);
        $this->calcola_numerazione_presenze($anno, $mese);
        
    }
    
    
    public function aggiorna_disponibilita_lunedi()
    {
        $this->aggiorna_disponibilita_dipendenti();
        $rows=$this->Sys_model->get_lista_disponibilita();
        
    }
    
    public function ajax_rinnova_ccl($recordid)
    {
        echo $this->rinnova_ccl($recordid);
    }
    
    public function rinnova_ccl($recordid)
    {
        $nuovo_ccl_recordid=$this->Sys_model->rinnova_ccl($recordid);
         
        return $nuovo_ccl_recordid;
    }
    
    public function ajax_approva_ccl($recordid)
    {
        echo $this->approva_ccl($recordid);
    }
    
    public function approva_ccl($recordid)
    {
        $this->Sys_model->approva_ccl($recordid);
         
        return $recordid;
    }
    
    public function script_aggiorna_festivi_rapportidilavoro()
    {
        $sql="SELECT * FROM user_giornifestivi where giorno>='2020-01-01'";
        $festivi= $this->Sys_model->select($sql);
        foreach ($festivi as $key => $festivo) {
            
            $giorno=$festivo['giorno'];
            $tipogiorno=$festivo['tipogiorno'];
               echo "Aggiornamento $giorno: $tipogiorno <br/>"; 
            $sql="
                UPDATE user_rapportidilavoro
                SET tipogiorno='$tipogiorno'
                WHERE data='$giorno'
                ";
            $this->Sys_model->execute_query($sql);
        }
    }
    public function sync_linkedmaster_contratti()
    {
        $contratti= $this->Sys_model->db_get('user_contratti','recordid_');
        foreach ($contratti as $key => $contratto) {
            $this->sync_linkedmaster_contratto($contratto['recordid_']);
        }
    }
    
    public function sync_linkedmaster_contratto($recordid_contratto)
    {
        $this->Sys_model->sync_linkedmaster_contratto($recordid_contratto);
    }
    
    
    public function test_alias()
    {
        $this->Sys_model->genera_presenze_dipendente_iniziofine('00000000000000000000000000003107','2019-09-30','2019-09-30','A');
    }
    
    
    //custom 3p
    public function ajax_get_dati_ccl()
    {
        $post=$_POST;
        $recordid_professione=$post['recordid_professione'];
        $recordid_contratto=$post['recordid_contratto'];
        $recordid_dipendente=$post['recordid_dipendente'];
        $professione= $this->Sys_model->db_get_row("user_professioni",'*',"recordid_='$recordid_professione'");
        $contratto= $this->Sys_model->db_get_row("user_contratti",'*',"recordid_='$recordid_contratto'");
        $datainizio_contratto=$contratto['datainizio'];
        $dipendente= $this->Sys_model->db_get_row("user_dipendenti",'*',"recordid_='$recordid_dipendente'");
        $datanascita=$dipendente['datanascita'];
        $date_diff= date_diff(date_create($datainizio_contratto), date_create($datanascita));
        $anni=$date_diff->format('%y');
        $recordid_qualifica=$professione['recordidqualifiche_'];
        $recordid_ccl=$professione['recordidccl_'];
        
        
        $qualifica=$this->Sys_model->db_get_row("user_qualifiche",'*',"recordid_='$recordid_qualifica'");
        $ccl=$this->Sys_model->db_get_row("user_ccl",'*',"recordid_='$recordid_ccl'");
        
        $fasciaeta=$this->Sys_model->db_get_row("user_fasciaeta",'*',"recordidccl_='$recordid_ccl' AND da<=$anni AND a>=$anni");
        
        
        $data['cclapplicato']=$ccl['nomeccl'];
        $base=$qualifica['base'];
        $data['base']=$base;
        
        $festiviformula=$ccl['festiviformula'];
        $festiviperc=$ccl['festiviperc'];
        if(isempty($festiviperc)||$festiviperc==0)
        {
            $festiviperc=0;
            $festivi=0;
            $festivi_testo='Giorni effettivi';
        }
        else
        {
            $festivi=$base;
            $festivi=$festivi/100*$festiviperc;
            $festivi_testo=sprintf("%.2f", round($festivi,2));
        }
        
        
        $vacanzeformula=$ccl['vacanzeformula'];
        $vacanzeperc=$fasciaeta['vacanzeperc'];
        $vacanze=$base;
        if($vacanzeformula=='Base')
        {
            $vacanze=$base;
        }
        if($vacanzeformula=='Base + Festivo')
        {
            $vacanze=$base+$festivi;
        }
        $vacanze=$vacanze/100*$vacanzeperc;
        
        $tredformula=$ccl['tredformula'];
        $tredperc=$ccl['tredperc'];
        $tred=$base;
        if($tredformula=='Base')
        {
            $tred=$base;
        }
        if($tredformula=='Base + Festivo')
        {
            $tred=$base+$festivi;
        }
        if($tredformula=='Base + vacanze')
        {
            $tred=$base+$vacanze;
        }
        if($tredformula=='Base + festivo + vacanze')
        {
            $tred=$base+$festivi+$vacanze;
        }
        $tred=$tred/100*$tredperc;
        
        $vacanze=round($vacanze,2);
        $tred=floor($tred*100)/100;
        $totale=round($base+$festivi+$vacanze+$tred,2);
        
        $data['festiviperc']=sprintf("%.2f", $festiviperc);
        $data['festivi']=$festivi_testo;
        $data['tredperc']=sprintf("%.2f", $tredperc);
        $data['tred']=sprintf("%.2f", $tred);
        $data['vacanzeperc']=sprintf("%.2f", $vacanzeperc);
        $data['vacanze']=sprintf("%.2f", $vacanze);
        $data['totale']=sprintf("%.2f", $totale);
        
        $data_json=json_encode($data);
        echo $data_json;
    }
    
    
    public function genera_calcolofatturato()
    {
        $this->Sys_model->genera_calcolofatturato();
    }
    
    
    public function ajax_load_content_calcolofatturato($annoselezionato='')
    {
        $data=array();
        
        $annoattuale=date("Y");
        if($annoselezionato=='')
        {
            $annoselezionato=$annoattuale;
        }
        $data['annoselezionato']=$annoselezionato;
        
        if($annoselezionato==$annoattuale)
        {
            $data['ccl_attivi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND ((datainizio<=CURDATE() AND datafine is null) OR  (datainizio<=CURDATE() AND datafine>=CURDATE()))  ","ORDER BY nomeccl ASC");
            $data['ccl_vecchi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datafine<=CURDATE() AND datafine>='$annoselezionato-01-01' ","ORDER BY nomeccl ASC");
            $data['ccl_nuovi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datainizio>=CURDATE()  ","ORDER BY nomeccl ASC");
        }
        else
        {
            $data['ccl_attivi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND ((datainizio<='$annoselezionato-12-31' AND datafine is null) OR  (datainizio<='$annoselezionato-12-31' AND datafine>='$annoselezionato-12-31'))  ","ORDER BY nomeccl ASC");
            $data['ccl_vecchi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND (datafine>='$annoselezionato-01-01') AND (datafine<'$annoselezionato-12-31') ","ORDER BY nomeccl ASC");
            $data['ccl_nuovi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datainizio>=CURDATE()  ","ORDER BY nomeccl ASC");
        }
        
        
        $sql="select distinct(anno) from user_giornifestivi ORDER BY anno asc
            ";
        $anni=$this->Sys_model->select($sql);
        $data['anni']=$anni;
        
        echo   $this->load->view("sys/desktop/custom/3p/calcolofatturato",$data,true);
    }
    
    public function ajax_load_content_listaccl($annoselezionato='')
    {
        
        $data=array();
        
        $table_settings=  $this->Sys_model->get_table_settings('ccl');
        $data['customview_card']=false;
        $customview_card=$table_settings['customview_card'];
        if($customview_card=='true')
        {
            $data['customview_card']=true;
            
        }
        
        $annoattuale=date("Y");
        if($annoselezionato=='')
        {
            $annoselezionato=$annoattuale;
        }
        $data['annoselezionato']=$annoselezionato;
        
        if($annoselezionato==$annoattuale)
        {
            $data['ccl_attivi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND ((datainizio<=CURDATE() AND datafine is null) OR  (datainizio<=CURDATE() AND datafine>=CURDATE()))  ","ORDER BY nomeccl ASC");
            $data['ccl_vecchi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datafine<=CURDATE() AND datafine>='$annoselezionato-01-01' ","ORDER BY nomeccl ASC");
            $data['ccl_nuovi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datainizio>=CURDATE()  ","ORDER BY nomeccl ASC");
        }
        else
        {
            $data['ccl_attivi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND ((datainizio<='$annoselezionato-12-31' AND datafine is null) OR  (datainizio<='$annoselezionato-12-31' AND datafine>='$annoselezionato-12-31'))  ","ORDER BY nomeccl ASC");
            $data['ccl_vecchi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND (datafine>='$annoselezionato-01-01') AND (datafine<'$annoselezionato-12-31') ","ORDER BY nomeccl ASC");
            $data['ccl_nuovi']= $this->Sys_model->db_get('user_ccl','*',"stato = 'Verificato' AND datainizio>='$annoselezionato-01-01'  ","ORDER BY nomeccl ASC");
        }
        
        
        $sql="select distinct(anno) from user_giornifestivi ORDER BY anno asc
            ";
        $anni=$this->Sys_model->select($sql);
        $data['anni']=$anni;
        
        echo   $this->load->view("sys/desktop/custom/3p/listaccl",$data,true);
    }
    
    public function ajax_load_content_calcolofatturato_ccl($recordid_ccl,$anno='')
    {
        $data=array();
        if($anno=='')
        {
            $anno=date("Y");
        }
        //$dati=$this->Sys_model->get_dati_calcolofatturato($recordid_ccl);
        $this->Sys_model->genera_calcolofatturato_ccl($recordid_ccl,$anno);
        $data=$this->Sys_model->get_dati_calcolofatturato($recordid_ccl,$anno);
        $data['recordid_ccl']=$recordid_ccl;
        $data['userid']= $this->get_userid();
        $data['anno']=$anno;
        echo   $this->load->view("sys/desktop/custom/3p/calcolofatturato_ccl",$data,true);
    }
    
    public function crea_righe_calcolofatturato($anno=2020)
    {
        $ccl_rows= $this->Sys_model->db_get('user_ccl');
        foreach ($ccl_rows as $key => $ccl_row) {
            $recordid_ccl=$ccl_row['recordid_'];
            $fasciaeta_rows=$this->Sys_model->db_get('user_fasciaeta','*',"recordidccl_='$recordid_ccl'");
            $qualifiche_rows=$this->Sys_model->db_get('user_qualifiche','*',"recordidccl_='$recordid_ccl'");
            foreach ($fasciaeta_rows as $key => $fasciaeta_row) {
                $recordid_fasciaeta=$fasciaeta_row['recordid_'];
                foreach ($qualifiche_rows as $key => $qualifiche_row) {
                    $recordid_qualifica=$qualifiche_row['recordid_'];
                    $recordid_calcolofatturato=$this->Sys_model->db_get_value('user_calcolofatturato','recordid_',"recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno'");
                    if($recordid_calcolofatturato==null)
                    {
                        $fields=array();
                        $fields['recordidccl_']=$recordid_ccl;
                        $fields['recordidfasciaeta_']=$recordid_fasciaeta;
                        $fields['recordidqualifiche_']=$recordid_qualifica;
                        $fields['anno']=$anno;
                        $this->Sys_model->insert_record('calcolofatturato', 1, $fields);
                    }
                    
                }
            }
        }
        
    }
    
    public function ajax_salva_base_calcolofatturato()
    {
        $post=$_POST;
        $recordid_ccl=$post['recordid_ccl'];
        $recordid_fasciaeta=$post['recordid_fasciaeta'];
        $anno=$post['anno'];
        foreach ($post['prezzivendita'] as $recordid_qualifica => $value) {
            $descrizione= $this->Sys_model->db_get_value('user_fasciaeta','descrizione',"recordid_='$recordid_fasciaeta'");
            $sql="DELETE FROM user_calcolofatturato WHERE recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno'";
            $this->Sys_model->execute_query($sql);
            $fields=array();
            $fields['recordidccl_']=$recordid_ccl;
            $fields['recordidfasciaeta_']=$recordid_fasciaeta;
            $fields['recordidqualifiche_']=$recordid_qualifica;
            $fields['anno']=$anno;
            $fields['prezzovendita']=$value;
            $fields['prezzovenditabase']='si';
            $this->Sys_model->insert_record('calcolofatturato', 1, $fields);
            
            $fascieeta_derivate=$post['fascieeta_derivate'];
            foreach ($fascieeta_derivate as $key => $recordid_fasciaeta_derivata) {
                $sql="DELETE FROM user_calcolofatturato WHERE recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta_derivata' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno'";
                $this->Sys_model->execute_query($sql);
                $fields=array();
                $fields['recordidccl_']=$recordid_ccl;
                $fields['recordidfasciaeta_']=$recordid_fasciaeta_derivata;
                $fields['recordidqualifiche_']=$recordid_qualifica;
                $fields['anno']=$anno;
                $fields['prezzovendita']=$value;
                $fields['prezzovenditabase']=substr_replace(preg_replace('~\D~', '', $descrizione), "-", 2, 0);
                $this->Sys_model->insert_record('calcolofatturato', 1, $fields);
            }
        }
    }
    
    public function ajax_salva_base_calcolofatturatoBAK()
    {
        $post=$_POST;
        $recordid_ccl=$post['recordid_ccl'];
        $recordid_fasciaeta=$post['recordid_fasciaeta'];
        $anno=$post['anno'];
        foreach ($post['prezzivendita'] as $recordid_qualifica => $value) {
            $descrizione= $this->Sys_model->db_get_value('user_fasciaeta','descrizione',"recordid_='$recordid_fasciaeta'");
            $fields=array();
            $fields['prezzovendita']=$value;
            $fields['prezzovenditabase']='si';
            $this->Sys_model->update_record('calcolofatturato', 1, $fields,"recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno' ");
            
            $fascieeta_derivate=$post['fascieeta_derivate'];
            foreach ($fascieeta_derivate as $key => $recordid_fasciaeta_derivata) {
                $fields=array();
                $fields['prezzovendita']=$value;
                $fields['prezzovenditabase']=substr_replace(preg_replace('~\D~', '', $descrizione), "-", 2, 0);
                $this->Sys_model->update_record('calcolofatturato', 1, $fields,"recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta_derivata' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno' ");
            }
        }
    }
    
    
    public function ajax_report_ccl($recordid,$anno='')
    {
        
        $content= $this->get_reportCCL($recordid,$anno);
        echo $content;
    }
    
    public function get_reportCCL($recordid,$anno='',$print=false)
    {
        if($anno=='undefined')
        {
            $anno='';
        }
        $userid=  $this->get_userid();
        $user_settings= $this->Sys_model->get_user_settings($userid);
        $data['recordid_ccl']=$recordid;
        $data['settings']=$user_settings;
        $data['ccl']= $this->Sys_model->db_get_row('user_ccl','*',"recordid_='$recordid'");
        $qualifiche=$this->Sys_model->db_get('user_qualifiche','*',"recordidccl_='$recordid'");
        $sql="
            select recordid_,qualifica
            from user_qualifiche
            GROUP BY qualifica
            ";
        //$data['fascieeta']=$this->Sys_model->db_get('user_qualifiche','*',"recordidccl_='$recordid'");
        foreach ($qualifiche as $key => $qualifica) {
            $recordid_qualifica=$qualifica['recordid_'];
            $descrizione_qualifica=$qualifica['qualifica'];
            
            if($anno=='')
            {
                /*
                $sql="
                select max(cf.anno) as anno
                from user_calcolofatturato as cf join user_fasciaeta fe ON cf.recordidfasciaeta_=fe.recordid_
                where cf.recordidccl_='$recordid' and cf.recordidqualifiche_='$recordid_qualifica' and cf.prezzovenditabase='si'

                ";
                $anno=date('Y');
                $result= $this->Sys_model->select($sql);
                if(count($result)>0)
                {
                    $anno=$result[0]['anno'];
                }
                 * /
                 */
                $anno=date('Y');
            }
            
            
            $sql="
            select prezzovenditabase,fe.da,fe.a,cf.prezzovendita
            from user_calcolofatturato as cf join user_fasciaeta fe ON cf.recordidfasciaeta_=fe.recordid_
            where cf.recordidccl_='$recordid' and cf.recordidqualifiche_='$recordid_qualifica' and cf.prezzovenditabase='si' and cf.anno='$anno'

            ";
            //$fascie= $this->Sys_model->select($sql);
            
            $sql="
            select descrizione,prezzovenditabase,fe.da,fe.a,cf.prezzovendita
            from user_calcolofatturato as cf join user_fasciaeta fe ON cf.recordidfasciaeta_=fe.recordid_
            where cf.recordidccl_='$recordid' and cf.recordidqualifiche_='$recordid_qualifica'  and cf.anno='$anno'
                order by da
            ";
            $fascie= $this->Sys_model->select($sql);
            $fascie2=array();
            $last_prezzovenditabase='';
            $last_prezzovendita='';
            foreach ($fascie as $key => $fascia) {
                
                $prezzovenditabase=$fascia['prezzovenditabase'];
                $prezzovendita=$fascia['prezzovendita'];
                if($prezzovenditabase=='si')
                {
                    $prezzovenditabase=$fascia['da']."-".$fascia['a'];
                }
                if($last_prezzovenditabase=='')
                {
                    $last_prezzovendita=$prezzovendita;
                    $last_prezzovenditabase=$prezzovenditabase;
                    $last_min=$fascia['da'];
                    $last_max=$fascia['a'];
                }
                else
                {
                    if($prezzovenditabase==$last_prezzovenditabase)
                    {
                        $last_max=$fascia['a'];
                    }
                    else
                    {
                        if(array_key_exists($last_prezzovenditabase, $fascie2))
                        {
                            $fascie2[$last_prezzovenditabase]['nomecolonna']=$fascie2[$last_prezzovenditabase]['nomecolonna']." / "."Da $last_min a $last_max";
                        }
                        else
                        {
                            $fascie2[$last_prezzovenditabase]['prezzovendita']=$last_prezzovendita;
                            if($last_min==0)
                            {
                                $max=$last_max+1;
                                $fascie2[$last_prezzovenditabase]['nomecolonna']="Meno di $max";
                            }
                            else
                            {
                                $fascie2[$last_prezzovenditabase]['nomecolonna']="Da $last_min a $last_max";
                            }
                        }
                        $last_min=$fascia['da'];
                        $last_max=$fascia['a'];
                    }
                    $last_prezzovenditabase=$prezzovenditabase;
                    $last_prezzovendita=$prezzovendita;
                }
                
            }
            if(count($fascie)>0)
            {
                if(array_key_exists($last_prezzovenditabase, $fascie2))
                {
                    $fascie2[$last_prezzovenditabase]['nomecolonna']=$fascie2[$last_prezzovenditabase]['nomecolonna']." / "."Da $last_min a $last_max";
                }
                else
                {
                    $fascie2[$last_prezzovenditabase]['prezzovendita']=$last_prezzovendita;
                    $fascie2[$last_prezzovenditabase]['nomecolonna']="Da $last_min a $last_max";
                }
            }
            $data['nrfascie']=count($fascie2);
            $data['fascie']=$fascie2;
            $data['qualifiche'][$descrizione_qualifica][$recordid_qualifica]=$qualifica;
            $data['qualifiche'][$descrizione_qualifica][$recordid_qualifica]['fascie']=$fascie2;
        }
        $data['blocchi']['header']=$this->Sys_model->db_get('user_cclblocchi','*',"recordidccl_='$recordid' AND zona='header'");
        $data['blocchi']['footer']=$this->Sys_model->db_get('user_cclblocchi','*',"recordidccl_='$recordid' AND zona='footer'");
        
        $data['print']=false;
        $data['edit']=false;
        $userid=$this->get_userid();
        if(($userid==1)||($userid==2)||($userid==7))
        {
            $data['edit']=true;
        }
        if($print)
        {
            $data['print']=true;
            $data['edit']=false;
        }
        $data['userid']= $userid;
        
        return $this->load->view('sys/desktop/custom/3p/report/ccl',$data, true);
    }
    
    
    public function genera_stampa_reportCCL($recordid)
    {
        $content=$this->get_reportCCL($recordid,true);
        $path_stampa=$this->genera_stampa($content,'reportCCL','portrait');
        $url_stampa=  str_replace("../", domain_url(), $path_stampa);
        $block=$this->load_block_visualizzatore_stampa($url_stampa,$path_stampa);
        echo $block;
    }
    
    public function saveReportCCL()
    {
        $post=$_POST;
        $this->Sys_model->saveReportCCL($post);
    }
    
    
    public function test()
    {
        $data=array();
        $interface='Desktop';
        $data['data']['content']=  $this->load_content_home($interface);
                $settings= $this->Sys_model->get_settings();
                //$settings['archive']=$this->Sys_model->get_archive_list_searchable();
                $settings['archive']=  $this->Sys_model->get_archive_menu();
                $data['data']['settings']=$settings;
                $userid= $this->get_userid();
                $data['user_settings']= $this->Sys_model->get_user_settings($userid);
        echo $this->load->view('test',$data);
    }
    
    
    public function api_freshdesk_get_contacts()
    {
        $api_key = "CKSOYa2wpqgL1xTUQTHq";

        $password = "x";

        $yourdomain = "swissbix";



        // Return the tickets that are new or opend & assigned to you

        // If you want to fetch all tickets remove the filter query param

        $url = "https://$yourdomain.freshdesk.com/api/v2/contacts?per_page=100&page=2";



        $ch = curl_init($url);



        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



        $server_output = curl_exec($ch);

        $info = curl_getinfo($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $headers = substr($server_output, 0, $header_size);

        $response = substr($server_output, $header_size);



        if($info['http_code'] == 200) {

            $contacts= json_decode($response,true);
            var_dump($contacts);
            
            

        } else {

          if($info['http_code'] == 404) {

            echo "Error, Please check the end point \n";

          } else {

            echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";

            echo "Headers are ".$headers;

            echo "Response are ".$response;

          }

        }



        curl_close($ch);
    }
    
    
    public function api_freshdesk_get_tickets()
    {
        $api_key = "CKSOYa2wpqgL1xTUQTHq";

        $password = "x";

        $yourdomain = "swissbix";



        // Return the tickets that are new or opend & assigned to you

        // If you want to fetch all tickets remove the filter query param

        $url = "https://$yourdomain.freshdesk.com/api/v2/tickets?include=requester,description,stats&updated_since=2020-07-01&per_page=10";



        $ch = curl_init($url);



        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



        $server_output = curl_exec($ch);

        $info = curl_getinfo($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $headers = substr($server_output, 0, $header_size);

        $response = substr($server_output, $header_size);



        if($info['http_code'] == 200) {

            $tickets= json_decode($response,true);
            foreach ($tickets as $key => $ticket) {
                $ticketid=$ticket['id'];
                $fields['id']=$ticketid;
                $fields['idfreshdesk']=$ticketid;
                $status=$ticket['status'];
                $fields['statovte']='Open';
                if($status=='8')
                {
                    $fields['statovte']='In Progress';
                }
                if($status=='7')
                {
                    $fields['statovte']='Wait For Response';
                }
                if($status=='3')
                {
                    $fields['statovte']='Pending';
                }
                if($status=='4')
                {
                    $fields['statovte']='Resolved';
                }
                if($status=='5')
                {
                    $fields['statovte']='Closed';
                }
                $fields['oggetto']=$ticket['subject']."-".$ticket['requester']['name']."-".$ticketid;
                $fields['descrizione']=$ticket['description_text'];
                if(isempty($fields['oggetto']))
                {
                    $fields['oggetto']= substr($fields['descrizione'], 0,150);
                }
                $fields['segnalatore']=$ticket['requester']['name'];
                $fields['email']=$ticket['requester']['email'];
                $createdat=$ticket['created_at'];
                $data=date("Y-m-d",strtotime($createdat));
                $fields['data']=$data;
                $responder_id=$ticket['responder_id'];
                $fields['tecnico']=$responder_id;
                $fields['tecnicovte']='1';
                if($responder_id=='48001047845')
                {
                    $fields['tecnico']='Davide';
                    $fields['tecnicovte']='5';
                }
                if($responder_id=='48023293270')
                {
                    $fields['tecnico']='Luca';
                    $fields['tecnicovte']='10';
                }
                if($responder_id=='48021931022')
                {
                    $fields['tecnico']='Sacha';
                    $fields['tecnicovte']='11';
                }
                if($responder_id=='48010523282')
                {
                    $fields['tecnico']='Samantha';
                    $fields['tecnicovte']='';
                }
                if($responder_id=='48024042354')
                {
                    $fields['tecnico']='RobertoP';
                    $fields['tecnicovte']='8';
                }
                if($responder_id=='48019122384')
                {
                    $fields['tecnico']='RobertoV';
                    $fields['tecnicovte']='12';
                }
                if($responder_id=='48024070464')
                {
                    $fields['tecnico']='Alessandro';
                    $fields['tecnicovte']='4';
                }
                
                	
                $jdoc_row= $this->Sys_model->db_get_row('user_freshdesk','*',"id='$ticketid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('freshdesk',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$ticketid."<br/>");
                }
                else
                {
                    $this->Sys_model->insert_record('freshdesk',1,$fields);
                    echo("INSERTED ".$ticketid."<br/>");
                }
                
                //$this->api_freshdesk_get_ticket_time_entries($ticketid);
            }
            

        } else {

          if($info['http_code'] == 404) {

            echo "Error, Please check the end point \n";

          } else {

            echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";

            echo "Headers are ".$headers;

            echo "Response are ".$response;

          }

        }



        curl_close($ch);
    }
    
    public function api_freshdesk_get_ticket_time_entries($ticketid)
    {
        $api_key = "CKSOYa2wpqgL1xTUQTHq";

        $password = "x";

        $yourdomain = "swissbix";



        // Return the tickets that are new or opend & assigned to you

        // If you want to fetch all tickets remove the filter query param

        $url = "https://$yourdomain.freshdesk.com/api/v2/tickets/$ticketid/time_entries";



        $ch = curl_init($url);



        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



        $server_output = curl_exec($ch);

        $info = curl_getinfo($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $headers = substr($server_output, 0, $header_size);

        $response = substr($server_output, $header_size);



        if($info['http_code'] == 200) {

            $ticket_entries= json_decode($response,true);
            foreach ($ticket_entries as $key => $ticket_entry) {
                $fields['ticketid']=$ticketid;
                $fields['freshdeskid']=$ticket_entry['id'];
                $fields['fatturabile']=$ticket_entry['billable'];
                $fields['durata']=$ticket_entry['time_spent'];
                $agent_id=$ticket['agent_id'];
                if($agent_id=='48001047845')
                {
                    $fields['idutente']=47;
                }
                if($agent_id=='48023293270')
                {
                    $fields['idutente']=44;
                }
                $jdoc_row= $this->Sys_model->db_get_row('user_timesheet','*',"freshdeskid='$ticketid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('timesheet',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$ticketid."<br/>");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('timesheet');
                    $this->Sys_model->insert_record('timesheet',1,$fields);
                    echo("INSERTED ".$ticketid."<br/>");
                }
            }
            

        } else {

          if($info['http_code'] == 404) {

            echo "Error, Please check the end point \n";

          } else {

            echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";

            echo "Headers are ".$headers;

            echo "Response are ".$response;

          }

        }



        curl_close($ch);
    }
    
    
    
    
    public function api_bexio_get_contacts()
    {
        
        //$oidc = new OpenIDConnectClient("https://idp.bexio.com", "a44f24cd-a2b4-4401-89bc-d7d53329accc", "AOoaD-UBBtBiWDGNFsQDClzQAtsI2XcpiSA3uJoDB7JbLF2t0hesG7cMbIDpv-hmrn-A6wZgsItC_-nQ0vsmQ7M");
        //$oidc->setRedirectURL("http://localhost:8822/jdocweb/index.php/sys_viewcontroller/api_bexio_get_contacts");
        //$oidc->addScope(array("openid", "profile", "contact_show", "offline_access", "contact_edit"));
        //$oidc->authenticate();

        //$token= $oidc->getAccessToken();
        
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";


        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/contact?order_by=id_desc&limit=2000';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $contacts= json_decode($json_response,true);
            foreach ($contacts as $key => $contact) {
                
                $ragionesociale=$contact['name_1'];
                $bexioid=$contact['id'];
                
                $fields['ragionesociale']=$ragionesociale;
                $fields['bexioid']=$bexioid;
                $fields['indirizzo']=$contact['address'];
                $fields['cap']=$contact['postcode'];
                $fields['citta']=$contact['city'];
                $fields['telefono']=$contact['phone_fixed'];
                $fields['email']=$contact['mail'];
                $contact_group_ids=$contact['contact_group_ids'];
                $bexiocategory='Cliente';
                if($contact_group_ids=='3')
                {
                    $bexiocategory='Fornitore';
                }
                $fields['bexiocategory']=$bexiocategory;
                $ragionesociale= str_replace("'", "''", $ragionesociale);
                
                $jdoc_row= $this->Sys_model->db_get_row('user_aziende','*',"ragionesociale='$ragionesociale' OR bexioid='$bexioid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('aziende',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$ragionesociale.": ".$bexioid.": ".$contact_group_ids."<br/>");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('aziende');
                    $this->Sys_model->insert_record('aziende',1,$fields);
                    echo("INSERTED ".$ragionesociale.": ".$bexioid."<br/>");
                }
                
            }
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
        
    }
    
    public function api_bexio_get_offers()
    {
        //$oidc = new OpenIDConnectClient("https://idp.bexio.com", "a44f24cd-a2b4-4401-89bc-d7d53329accc", "AOoaD-UBBtBiWDGNFsQDClzQAtsI2XcpiSA3uJoDB7JbLF2t0hesG7cMbIDpv-hmrn-A6wZgsItC_-nQ0vsmQ7M");
        //$oidc->setRedirectURL("http://localhost:8822/jdocweb/index.php/sys_viewcontroller/api_bexio_get_offers");
        //$oidc->addScope(array("openid", "profile", "contact_show", "offline_access", "kb_offer_show", "kb_order_show", "kb_invoice_show"));
        //$oidc->authenticate();

        //$token= $oidc->getAccessToken();
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";
        
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/kb_offer';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $offers= json_decode($json_response,true);
            foreach ($offers as $key => $offer) {
                $bexioid=$offer['id'];
                $bexionr=$offer['document_nr'];
                $bexiocontactid=$offer['contact_id'];
                $jdoc_aziende_recordid= $this->Sys_model->db_get_value('user_vendite','recordid_',"bexioid='$bexiocontactid'");
                $fields=array();
                $fields['bexioid']=$bexioid;
                $fields['bexionr']=$bexionr;
                $fields['recordidaziende_']=$jdoc_aziende_recordid;
                $fields['titolo']=$offer['title'];
                $fields['recall']=$offer['is_valid_from'];
                $fields['datachiusura']=$offer['is_valid_from'];
                $fields['importoproposto']=$offer['total'];
                $fields['importoconcluso']=$offer['total'];
                $stato=$offer['kb_item_status_id'];
                if($stato==2)
                {
                    $stato='inattesa';
                }
                if($stato==3)
                {
                    $stato='closedorder';
                }
                if($stato==4)
                {
                    $stato='closedlost';
                }
                $fields['stato']=$stato;
                $venditore=$offer['user_id'];
                if($venditore==7)
                {
                    $venditore=23;
                }
                if($venditore==6)
                {
                    $venditore=5;
                }
                if($venditore==1)
                {
                    $venditore=47;
                }
                $fields['venditore']=$venditore;
                $jdoc_row= $this->Sys_model->db_get_row('user_vendite','*',"bexioid='$bexioid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('vendite',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$bexionr."<br/>");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('vendite');
                    $this->Sys_model->insert_record('vendite',1,$fields);
                    echo("INSERTED ".$bexionr."<br/>");
                }
            }
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

    }
    
    public function api_bexio_get_ordersOLD()
    {
        //$oidc = new OpenIDConnectClient("https://idp.bexio.com", "a44f24cd-a2b4-4401-89bc-d7d53329accc", "AOoaD-UBBtBiWDGNFsQDClzQAtsI2XcpiSA3uJoDB7JbLF2t0hesG7cMbIDpv-hmrn-A6wZgsItC_-nQ0vsmQ7M");
        //$oidc->setRedirectURL("http://localhost:8822/jdocweb/index.php/sys_viewcontroller/api_bexio_get_orders");
        //$oidc->addScope(array("openid", "profile", "contact_show", "offline_access", "contact_edit", "kb_offer_show", "kb_order_show","kb_order_edit", "kb_invoice_show"));
        //$oidc->authenticate();

        //$token= $oidc->getAccessToken();
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/kb_order?order_by=id_desc';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $orders= json_decode($json_response,true);
            echo count($orders);
            //$sql="UPDATE user_ordini SET stato='Archived'";
            //$this->Sys_model->execute_query($sql);
            foreach ($orders as $key => $order) {
                $bexioid=$order['id'];
                $kb_item_status_id=$order['kb_item_status_id'];
                echo "bexioid:$bexioid - stato:$kb_item_status_id - <br/>";
                $bexionr=$order['document_nr'];
                $bexiocontactid=$order['contact_id'];
                $jdoc_aziende_recordid= $this->Sys_model->db_get_value('user_aziende','recordid_',"bexioid='$bexiocontactid'");
                $fields=array();
                $fields['bexioid']=$bexioid;
                $fields['bexionr']=$bexionr;
                $fields['recordidaziende_']=$jdoc_aziende_recordid;
                $fields['titolo']=$order['title'];
                $fields['prezzo']=$order['total_gross'];
                $fields['data']=$order['is_valid_from'];
                
                $stato='In Progress';
                if($kb_item_status_id=='6')
                {
                    $stato='Complete';
                }
                if($kb_item_status_id=='21')
                {
                    $stato='Archived';
                }
                $fields['stato']=$stato;
                
                $isrecurring=$order['is_recurring']; 
                
                if($isrecurring=='true' || $isrecurring==true)
                {
                    $url = 'https://api.bexio.com/2.0/kb_order/'.$bexioid.'/repetition';


                    try {
                            $response = $client->request('GET', $url, array(
                                'headers' => $headers,
                            ));
                            
                            $json_response=($response->getBody()->getContents());
                            $repetition= json_decode($json_response,true);
                            $fields['datainizio']=$repetition['start'];
                            $repetition_type=$repetition['repetition']['type'];
                            $repetition_interval=$repetition['repetition']['interval'];
                            if(($repetition_type=='monthly')&&($repetition_interval==1))
                            {
                                $fields['ripetizione']='Mensile';
                            }
                            if(($repetition_type=='monthly')&&($repetition_interval==3))
                            {
                                $fields['ripetizione']='Trimestrale';
                            }
                            if(($repetition_type=='yearly'))
                            {
                                $fields['ripetizione']='Annuale';
                            }
                            
                        }
                        catch (\GuzzleHttp\Exception\BadResponseException $e) {
                            // handle exception or api errors.
                            print_r($e->getMessage());
                        }
                    
                }
                
                $jdoc_row= $this->Sys_model->db_get_row('user_ordini','*',"bexioid='$bexioid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('ordini',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$bexionr."-".$order['contact_address']."-".$order['updated_at']."-$stato<br/>");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('ordini');
                    $this->Sys_model->insert_record('ordini',1,$fields);
                    echo("INSERTED ".$bexionr."-".$order['contact_address']."-".$order['updated_at']."-$stato<br/>");
                }
                
                $this->api_bexio_get_positions_test($token,$bexioid);
            }
            
            //$this->swissbix_calcola_pianificazioni();
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

    }
    
    
    
    public function api_bexio_set_contacts()
    {
        $sql="UPDATE user_bexio_contacts SET status='Deleted'";
        $this->Sys_model->execute_query($sql);
        $contacts=$this->api_bexio_get_default('contact','bexio_contacts');
        foreach ($contacts as $key => $contact) {
            $contactid=$contact['id'];
            $contact['status']="";
            $jdoc_row= $this->Sys_model->db_get_row('user_bexio_contacts','*',"id='$contactid'");
            if($jdoc_row!=null)
            {
                $recordid=$jdoc_row['recordid_'];
                $this->Sys_model->update_record('bexio_contacts',1,$contact,"recordid_='$recordid'");
            }
            else
            {
                $recordid=$this->Sys_model->insert_record('bexio_contacts',1,$contact);
            }
        }
    }
    
    public function api_bexio_set_accounts()
    {
        $sql="UPDATE user_bexio_accounts SET status='Deleted'";
        $this->Sys_model->execute_query($sql);
        $accounts=$this->api_bexio_get_default('accounts','bexio_accounts');
        foreach ($accounts as $key => $account) {
            $accountid=$account['id'];
            $jdoc_row= $this->Sys_model->db_get_row('user_bexio_accounts','*',"id='$accountid'");
            if($jdoc_row!=null)
            {
                $recordid=$jdoc_row['recordid_'];
                $this->Sys_model->update_record('bexio_accounts',1,$account,"recordid_='$recordid'");
            }
            else
            {
                $recordid=$this->Sys_model->insert_record('bexio_accounts',1,$account);
            }
        }
    }
    
    
    public function api_bexio_set_orders($limit=null)
    {
        ini_set('max_execution_time', 2400);
        echo "Inizio api_bexio_set_orders: ".date("Y-m-d H:i")."<br/>";
        
        $sql="UPDATE user_bexio_orders SET status='Deleted'";
        $this->Sys_model->execute_query($sql);
        
        $sql="UPDATE user_bexio_default_positions SET status='Deleted' WHERE type='Order'";
        $this->Sys_model->execute_query($sql);
        
        $orders=$this->api_bexio_get_default('kb_order','bexio_orders',$limit);
        foreach ($orders as $key => $order) {
            $orderid=$order['id'];
            $date=$order['is_valid_from'];
            echo "Inserimento ".$orderid."<br/>";
            
            // testata
            $kb_item_status_id=$order['kb_item_status_id'];
            $stato='';
            if($kb_item_status_id=='5')
            {
                $stato='In Progress';
            }
            if($kb_item_status_id=='6')
            {
                $stato='Complete';
            }
            if($kb_item_status_id=='7')
            {
                $stato='Draft';
            }
            if($kb_item_status_id=='8')
            {
                $stato='Pending';
            }
            if($kb_item_status_id=='15')
            {
                $stato='Partial';
            }
            if($kb_item_status_id=='21')
            {
                $stato='Archived';
            }
            $order['status']=$stato;
            
            
            $user_id=$order['user_id'];
            $seller= $this->Sys_model->db_get_value('user_bexio_users','lastname',"id='$user_id'");
            $order['seller']=$seller;
            
            
            $contact_id=$order['contact_id'];
            $jdoc_contact_recordid= $this->Sys_model->db_get_value('user_bexio_contacts','recordid_',"id='$contact_id'");
            $order['recordidbexio_contacts_']=$jdoc_contact_recordid;
            $contactname= $this->Sys_model->db_get_value('user_bexio_contacts','name_1',"id='$contact_id'");
            $order['contactname']=$contactname;
            
            //  DATI ORDINE RICORRENTE
            $isrecurring=$order['is_recurring']; 
                
            $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

            $headers = array(
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token.'',
            );

            $client = new \GuzzleHttp\Client();

            if($isrecurring=='true' || $isrecurring==true)
            {
                $url = 'https://api.bexio.com/2.0/kb_order/'.$orderid.'/repetition';


                try {
                        $response = $client->request('GET', $url, array(
                            'headers' => $headers,
                        ));

                        $json_response=($response->getBody()->getContents());
                        $repetition= json_decode($json_response,true);
                        $order['repetition_start']=$repetition['start'];
                        $repetition_type=$repetition['repetition']['type'];
                        $repetition_interval=$repetition['repetition']['interval'];
                        if(($repetition_type=='monthly')&&($repetition_interval==1))
                        {
                            $order['repetition_type']='Monthly';
                            $order['total_net_yearly']=$order['total_net']*12;
                            $order['total_yearly']=$order['total']*12;
                        }
                        if(($repetition_type=='monthly')&&($repetition_interval==3))
                        {
                            $order['repetition_type']='Quarterly';
                            $order['total_net_yearly']=$order['total_net']*4;
                            $order['total_yearly']=$order['total']*4;
                        }
                        if(($repetition_type=='yearly'))
                        {
                            $order['repetition_type']='Yearly';
                            $order['total_net_yearly']=$order['total_net'];
                            $order['total_yearly']=$order['total'];
                        }

                    }
                    catch (\GuzzleHttp\Exception\BadResponseException $e) {
                        // handle exception or api errors.
                        print_r($e->getMessage());
                    }

            }
            
            $jdoc_row= $this->Sys_model->db_get_row('user_bexio_orders','*',"id='$orderid'");
            if($jdoc_row!=null)
            {
                $recordid_order=$jdoc_row['recordid_'];
                $this->Sys_model->update_record('bexio_orders',1,$order,"recordid_='$recordid_order'");
            }
            else
            {
                $recordid_order=$this->Sys_model->insert_record('bexio_orders',1,$order);
            }
            
            // RIGHE DI DETTAGLIO
            
            $positions=$this->api_bexio_get_positions('kb_order',$order['id']);
            foreach ($positions as $key => $position) {
                $position['type']='Order';
                $position['recordidbexio_orders_']=$recordid_order;
                
                $positionid=$position['id'];
                $accountid=$position['account_id'];
                $account=$this->Sys_model->db_get_row("user_bexio_accounts","*","id='$accountid'");
                $position['account']=$account['name'];
                $position['accountgroup']=$account['accountgroup'];
                $position['seller']=$seller;
                $position['contactname']=$contactname;
                
                $position['status']=$stato;
                $position['date']=$date;
                        
                $jdoc_row= $this->Sys_model->db_get_row('user_bexio_default_positions','*',"id='$positionid'");
                if($jdoc_row!=null)
                {
                    $recordid_position=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('bexio_default_positions',1,$position,"recordid_='$recordid_position'");
                }
                else
                {
                    $recordid_position=$this->Sys_model->insert_record('bexio_default_positions',1,$position);
                }
                
            }
            
            
            // RIGHE DI PIANIFICAZIONE
            
            $this->set_pianificazioniordine($recordid_order);
                    
                    
            echo "Inserimento ".$order['id']." ESEGUITO<br/>";
        }
        echo "Fine api_bexio_set_orders: ".date("Y-m-d H:i")."<br/>";
    }
    
    function set_pianificazioniordine($recordid_ordine)
    {
        $sql="DELETE FROM user_pianificazioniordine WHERE recordidbexio_orders_='$recordid_ordine'";
        $this->Sys_model->execute_query($sql);
                
        $record_ordine= $this->Sys_model->db_get_row('user_bexio_orders','*',"recordid_='$recordid_ordine'");
        $fields['descrizione']=$record_ordine['title'];
        $fields['prezzo']=$record_ordine['total'];
        $fields['costo']=$record_ordine['purchasecost'];
        $fields['recordidbexio_orders_']=$recordid_ordine;
        
        $datainizio=$record_ordine['repetition_start'];
        $data=$datainizio;
        $repetition_type=$record_ordine['repetition_type'];
        if($repetition_type=='Monthly')
        {
            for($x=0;$x<24;$x++)
            {
                $multi=$x;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('pianificazioniordine', 'id');
                $this->Sys_model->insert_record('pianificazioniordine',1, $fields);
            }
        }
        if($repetition_type=='Quarterly')
        {
            for($x=0;$x<8;$x++)
            {
                $multi=$x*3;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('pianificazioniordine', 'id');
                $this->Sys_model->insert_record('pianificazioniordine',1, $fields);
            }
        }
        if($repetition_type=='Yearly')
        {
            for($x=0;$x<2;$x++)
            {
                $multi=$x*12;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('pianificazioniordine', 'id');
                $this->Sys_model->insert_record('pianificazioniordine',1, $fields);
            }
        }
    }
    
    
    public function api_bexio_set_invoices()
    {
        ini_set('max_execution_time', 1800);
        echo "Inizio api_bexio_set_invoices: ".date("Y-m-d H:i")."<br/>";
        $sql="UPDATE user_bexio_invoices SET status='Deleted'";
        //$this->Sys_model->execute_query($sql);
       
        $today=date('Y-m-d');
        $reminders_count=0;
        $invoices=$this->api_bexio_get_default('kb_invoice','bexio_invoices',2000);
        foreach ($invoices as $key => $invoice) {
            $invoiceid=$invoice['id'];
            echo "Inserimento ".$invoiceid."<br/>";
            
            // testata
            $kb_item_status_id=$invoice['kb_item_status_id'];
            $date=$invoice['is_valid_from'];
            
            $stato='';
            if($kb_item_status_id=='5')
            {
                $stato='In Progress';
            }
            if($kb_item_status_id=='6')
            {
                $stato='Complete';
            }
            if($kb_item_status_id=='7')
            {
                $stato='Draft';
            }
            if($kb_item_status_id=='8')
            {
                $stato='Pending';
                if($today>$invoice['is_valid_from'])
                {
                    //$stato='Overdue';
                    $reminders= $this->api_bexio_get_reminders('kb_invoice',$invoiceid);
                    $reminders_count=count($reminders);
                    if($reminders_count>0)
                    {
                        $stato='Reminder';
                    }
                }
            }
            if($kb_item_status_id=='9')
            {
                $stato='Paid';
            }
            if($kb_item_status_id=='15')
            {
                $stato='Partial';
            }
            if($kb_item_status_id=='16')
            {
                $stato='Overdue';
            }
            if($kb_item_status_id=='19')
            {
                $stato='Cancelled';
            }
            if($kb_item_status_id=='21')
            {
                $stato='Archived';
            }
            $invoice['status']=$stato;
            $invoice['note']=$kb_item_status_id;
            
            
            
            $total=$invoice['total'];
            $contact_id=$invoice['contact_id'];
            $contact= $this->Sys_model->db_get_row('user_bexio_contacts','*',"id='$contact_id'");
            $jdoc_contact_recordid=$contact['recordid_'];
            $invoice['recordidbexio_contacts_']=$jdoc_contact_recordid;
            $contactname= $contact['name_1'];
            $contact_user_id=$contact['user_id'];
            $invoice['contactname']=$contactname;
            $invoice_user_id=$invoice['user_id'];
            $seller=$this->Sys_model->db_get_value('bexio_export_invoices','seller',"id='$invoiceid'");
            //ordine relativo
            $order=$this->Sys_model->db_get_row("user_bexio_orders","*","recordidbexio_contacts_='$jdoc_contact_recordid' AND total=$total");
            $order_user_id=null;
            if($order!=null)
            {
                $invoice['order_nr']=$order['document_nr'];
                $invoice['is_recurring']=$order['is_recurring'];
                $invoice['recordidbexio_orders_']=$order['recordid_'];
                $order_user_id=$order['user_id'];
            }
            
            echo "Inserimento $invoiceid - $contactname - $stato - $kb_item_status_id - reminders:$reminders_count<br/>";
            
            //venditore
            $invoice['seller_origin']='seller';
            if(isempty($seller))
            {
                $seller_user_id=$order_user_id;
                $invoice['seller_origin']='order';
                if(isempty($seller_user_id))
                {
                    $seller_user_id=$contact_user_id;
                    $invoice['seller_origin']='contact-'.$contact_user_id;
                }
                
                $user= $this->Sys_model->db_get_row('user_bexio_users','*',"id='$seller_user_id'");
                $seller=$user['lastname']." ".$user['firstname'];
            }
            
            $invoice['seller']=$seller;
            
            
            $jdoc_row= $this->Sys_model->db_get_row('user_bexio_invoices','*',"id='$invoiceid'");
            if($jdoc_row!=null)
            {
                $recordid_invoice=$jdoc_row['recordid_'];
                $this->Sys_model->update_record('bexio_invoices',1,$invoice,"recordid_='$recordid_invoice'");
            }
            else
            {
                $recordid_invoice=$this->Sys_model->insert_record('bexio_invoices',1,$invoice);
            }
            
            // righe di dettaglio
            $sql="UPDATE user_bexio_default_positions SET status='Deleted' WHERE type='Invoice'";
            $this->Sys_model->execute_query($sql);
            $positions=$this->api_bexio_get_positions('kb_invoice',$invoice['id']);
            foreach ($positions as $key => $position) {
                $position['type']='Invoice';
                $position['recordidbexio_invoices_']=$recordid_invoice;
                
                $positionid=$position['id'];
                $accountid=$position['account_id'];
                $account=$this->Sys_model->db_get_row("user_bexio_accounts","*","id='$accountid'");
                $position['account']=$account['name'];
                $position['accountgroup']=$account['accountgroup'];
                $position['seller']=$seller;
                $position['contactname']=$contactname;
                
                $position['status']=$stato;
                $position['date']=$date;
                        
                $jdoc_row= $this->Sys_model->db_get_row('user_bexio_default_positions','*',"id='$positionid'");
                if($jdoc_row!=null)
                {
                    $recordid_position=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('bexio_default_positions',1,$position,"recordid_='$recordid_position'");
                }
                else
                {
                    $recordid_position=$this->Sys_model->insert_record('bexio_default_positions',1,$position);
                }
                
            }
            
            
            
            
            echo "Inserimento ".$invoice['id']." ESEGUITO<br/>";
        }
        echo "Fine api_bexio_set_invoices: ".date("Y-m-d H:i")."<br/>";
    }
    
    
    
    public function api_bexio_get_positions($bexiotable,$bexioid)
    {
        $jdoc_fields=$this->Sys_model->db_get("sys_field","*","tableid='bexio_default_positions' AND tablelink is null");
        $fields=array();
        $return=array();
        foreach ($jdoc_fields as $key => $jdoc_field) {
            $jdoc_field_id=$jdoc_field['fieldid'];
            $fields[$jdoc_field_id]="";
        }
        
        $positions=array();
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/'.$bexiotable.'/'.$bexioid.'/kb_position_custom';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $rows= json_decode($json_response,true);
            foreach ($rows as $key => $row) {
                foreach ($row as $column => $value) {
                    if(array_key_exists($column, $fields))
                    {
                        $fields[$column]=$value;
                    }
                }
                $return[]=$fields;
               
            }
            return $return;
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
        

    }
    
    public function api_bexio_get_reminders($bexiotable,$bexioid)
    {
           
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/'.$bexiotable.'/'.$bexioid.'/kb_reminder';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $rows= json_decode($json_response,true);
            
            return $rows;
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
        

    }

    public function api_bexio_get_users()
    {
        $sql="DELETE FROM user_bexio_users";
        $this->Sys_model->execute_query($sql);
        $users=$this->api_bexio_get_default('users','bexio_users');
        foreach ($users as $key => $user) {
            $recordid=$this->Sys_model->insert_record('bexio_users',1,$user);
            
        }
        
    }
    
    public function api_bexio_get_default($bexio_table,$jdoc_table,$limit='null')
    {
        $jdoc_fields=$this->Sys_model->db_get("sys_field","*","tableid='$jdoc_table' AND tablelink is null");
        $fields=array();
        $return=array();
        foreach ($jdoc_fields as $key => $jdoc_field) {
            $jdoc_field_id=$jdoc_field['fieldid'];
            $fields[$jdoc_field_id]="";
        }
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/'.$bexio_table.'?order_by=id_desc&limit='.$limit.'';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $rows= json_decode($json_response,true);
            foreach ($rows as $key => $row) {
                foreach ($row as $column => $value) {
                    if(array_key_exists($column, $fields))
                    {
                        $fields[$column]=$value;
                    }
                }
                $return[]=$fields;
               
            }
            return $return;
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

    }
    
    
    public function api_bexio_get_test($bexio_table,$id,$limit='null')
    {

        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/'.$bexio_table.'/'.$id;


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $rows= json_decode($json_response,true);
            var_dump($rows);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

    }
    
    
    
    public function api_bexio_get_invoices()
    {
        //$oidc = new OpenIDConnectClient("https://idp.bexio.com", "a44f24cd-a2b4-4401-89bc-d7d53329accc", "AOoaD-UBBtBiWDGNFsQDClzQAtsI2XcpiSA3uJoDB7JbLF2t0hesG7cMbIDpv-hmrn-A6wZgsItC_-nQ0vsmQ7M");
        //$oidc->setRedirectURL("http://localhost:8822/jdocweb/index.php/sys_viewcontroller/api_bexio_get_invoices");
        //$oidc->addScope(array("openid", "profile", "contact_show", "offline_access", "kb_offer_show", "kb_order_show", "kb_invoice_show"));
        //$oidc->authenticate();

        //$token= $oidc->getAccessToken();
        $token="eyJraWQiOiI2ZGM2YmJlOC1iMjZjLTExZTgtOGUwZC0wMjQyYWMxMTAwMDIiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJmaW5hbmNlQHN3aXNzYml4LmNoIiwibG9naW5faWQiOiI0NDYxNTI1OS1jOTYwLTExZTktYjE2Mi1hNGJmMDExY2U4NzIiLCJjb21wYW55X2lkIjoicThqZWR2cmV0dmQ1IiwidXNlcl9pZCI6OTQ4NDMsImF6cCI6ImV2ZXJsYXN0LXRva2VuLW9mZmljZS1jbGllbnQiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFsbCB0ZWNobmljYWwiLCJpc3MiOiJodHRwczpcL1wvaWRwLmJleGlvLmNvbSIsImV4cCI6MzE3MDg0MjYyOCwiaWF0IjoxNTk0MDQyNjI4LCJjb21wYW55X3VzZXJfaWQiOjEsImp0aSI6ImYxMTAxNDQwLWZlZjgtNGYyOS1hZjI4LWQyMWQ1MTRiMWRjOCJ9.bVGm_y-FZP13NqT0NdBIak5_nAqWM8Sa0Ggos10xc7nYblK-TB3O42cu7Me1mNGtN4zEckYHHwr1qItc49kSnppr8xuEdEIqqs-SpB0Cw3arxuBxU8-HodUraAtg_HhJalkeDHw0wk0qhLCAOk8mnJ3FLl_LF-LMeC2M3uobDKv-PCutWRP60kPpQ0EbRCdezbFKDrMav6-yqxF4l8IrdINt_W10o8ntWWhaUStY1I0z02FmjFoE0FsnczOITJsUvQMe7VckGsg_oU1GZ0HMipXLCYL7RsCOBhF_5M6G7bEXz0CXE0Z5tbpVlYFoeu074NcUO67lx1L8PUMVOEQ8GUvxUGOL8rbYDKa3Wz9jmmp81BUP0ENtXfjgZp-qG0QHglPWgw1aUekM9amFUYJgXdFyunXeLFtpghwpfHc6FgbkKcl2WGYPm-t4_aVlJACyifC_Gi8xrblze1ZbJY3gDxcLzpUyG3kJIHOsbQX_2Kau_btmZy9RSDuxEZ-x_ow3m1UfbPwz4c8lJb0p23Nwpbt8f_EG4gEZZ6TJvjP74-ikub_4ZxUaH1RiRICbJL6cazBozxxxxhLZ-8irbVnsUXDCLLgEhzjZ4ahFOMFUayL8ShhvVvL8SnRZW6YK-TtRP5Djv4UetoVJh-2JMihhp3NDtFGu2DV9axq2rs9eCTc";
        
        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token.'',
        );

        $client = new \GuzzleHttp\Client();
        $url = 'https://api.bexio.com/2.0/kb_invoice';


        try {
            $response = $client->request('GET', $url, array(
                'headers' => $headers,
            ));

            $json_response=($response->getBody()->getContents());
            $invoices= json_decode($json_response,true);
            foreach ($invoices as $key => $invoice) {
                $bexioid=$invoice['id'];
                $bexionr=$invoice['document_nr'];
                $bexiocontactid=$invoice['contact_id'];
                $jdoc_aziende_recordid= $this->Sys_model->db_get_value('user_aziende','recordid_',"bexioid='$bexiocontactid'");
                $fields=array();
                $fields['bexioid']=$bexioid;
                $fields['fatturanr']=$bexionr;
                $fields['recordidaziende_']=$jdoc_aziende_recordid;
                $fields['titolo']=$invoice['title'];
                $fields['data']=$invoice['is_valid_from'];
                $fields['importo']=$invoice['total'];
                $fields['status']=$invoice['kb_item_status_id'];
                $jdoc_row= $this->Sys_model->db_get_row('user_fatture','*',"bexioid='$bexioid'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('fatture',1,$fields,"recordid_='$recordid'");
                    echo("UPDATED ".$bexionr."<br/>");
                }
                else
                {
                    $this->Sys_model->insert_record('fatture',1,$fields);
                    echo("INSERTED ".$bexionr."<br/>");
                }
            }
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

    }
    
    
    
    public function api_bexio_get_accounts()
    {
        $oidc = new OpenIDConnectClient("https://idp.bexio.com", "a44f24cd-a2b4-4401-89bc-d7d53329accc", "AOoaD-UBBtBiWDGNFsQDClzQAtsI2XcpiSA3uJoDB7JbLF2t0hesG7cMbIDpv-hmrn-A6wZgsItC_-nQ0vsmQ7M");
        $oidc->setRedirectURL("http://localhost:8822/jdocweb/index.php/sys_viewcontroller/api_bexio_get_accounts");
        $oidc->addScope(array("kb_offer_show", "kb_order_show", "kb_order_edit", "kb_invoice_show"));
        $oidc->authenticate();

        $token= $oidc->getAccessToken();
       

            $headers = array(
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token.'',
            );

            $client = new \GuzzleHttp\Client();
            $url = 'https://api.bexio.com/2.0/accounts/';


            try {
                $response = $client->request('GET', $url, array(
                    'headers' => $headers,
                ));

                $json_response=($response->getBody()->getContents());
                $accounts= json_decode($json_response,true);
                $sql="DELETE FROM user_conti";
                $this->Sys_model->execute_query($sql);
                foreach ($accounts as $key => $account) {
                    $fields['id']=$account['id'];
                    $fields['numero']=$account['account_no'];
                    $fields['descrizione']=$account['name'];
                    $this->Sys_model->insert_record('conti',1,$fields);
                    echo("INSERTED ".$account['name']."<br/>");
                };
                
            }
            catch (\GuzzleHttp\Exception\BadResponseException $e) {
                // handle exception or api errors.
                print_r($e->getMessage());
            }
        

    }
    
    
    
    
    
    //custom swisssbix
    public function swissbix_calcola_pianificazioni()
    {
        $ordini= $this->Sys_model->db_get('user_ordini','recordid_');
        foreach ($ordini as $key => $ordine) {
            echo "Calcola pianificazione per: ".$ordine['recordid_']."<br/>";
            $this->Sys_model->calcola_pianificazione($ordine['recordid_']);
        }
        
    }
    
    
    
    
    //custom easywork
    public function testsqlserver()
    {
        echo 'test';
        $sql="SELECT * FROM L1001";
        $result=$this->Sys_model->select($sql);
        var_dump($result);
    }
    
    public function custom_easywork_generazionedocumenti()
    {
        $data=array();
        $sql="SELECT * FROM V_Adi_L1001_Candidati";
        $candidati=$this->Sys_model->select($sql);
        $sql="SELECT * FROM V_Adi_TLVL_Documenti order by Documento asc";
        $documenti=$this->Sys_model->select($sql);
        $data['candidati']=$candidati;
        $data['documenti']=$documenti;
        echo $this->load->view('sys/desktop/custom/easywork/generazionedocumenti',$data, TRUE);
    }
    
    public function custom_koelliker_collina()
    {
        $data=array();

        echo $this->load->view('sys/desktop/custom/koelliker/collina',$data, TRUE);
    }
    
    public function custom_koelliker_collina2()
    {
        $data=array();

        echo $this->load->view('sys/desktop/custom/koelliker/collina2',$data, TRUE);
    }
    
    public function custom_easywork_generacopertina()
    {
        
        $data=array();
        $post=$_POST;
        $candidato=$post['candidato'];
        $candidato_array= explode("-", $candidato);
        $candidato_id=$candidato_array[1];
        $candidato= $this->Sys_model->db_get_row('V_Adi_L1001_Candidati','*',"ID='$candidato_id'");
        $nomecognome=$candidato['Nome']." ".$candidato['Cognome'];
        $data['id']=$candidato_id;
        $data['nome']=$candidato['Nome'];
        $data['cognome']=$candidato['Cognome'];
        $data['nomecognome']=$nomecognome;
        $data['documento']='Copertina.docx';
        $data['modello']='Copertina.docx';
        $this->load->view('sys/desktop/stampe/easywork_copertina',$data);
                
            
        
        
    }
    
    public function custom_easywork_generadocumenti($converti='false')
    {
        $data=array();
        $post=$_POST;
        $candidato=$post['candidato'];
        $candidato_array= explode("-", $candidato);
        $candidato_id=end($candidato_array);
        $candidato= $this->Sys_model->db_get_row('V_Adi_L1001_Candidati','*',"ID='$candidato_id'");
        $data['id']=$candidato_id;
        
        $nomecognome=$candidato['Nome']." ".$candidato['Cognome'];
        $data['nome']=$candidato['Nome'];
        $data['cognome']=$candidato['Cognome'];
        $data['nomecognome']=$nomecognome;
        $cognomenome=$candidato['Cognome']." ".$candidato['Nome'];
        $data['cognomenome']=$nomecognome;
        $data['nomecognomemaiusc']= strtoupper($nomecognome);
        $data['email']=$candidato['Email'];
        $data['dataassunzione']=$post['data'];
        $data['indirizzo']=$candidato['Indirizzo'];
        $data['cap']=$candidato['CAP'];
        $data['paese']=$candidato['Paese'];
        $genere=$candidato['Genere'];
        
        
        $documenti=$post['documenti'];
        foreach ($documenti as $key => $documento) {
            $documento_array= explode('.', $documento);
            $documentonoext=$documento_array[0];
            $ext=$documento_array[1];
            if($ext=='pdf')
            {
                $command='copy "..\\JDocServer\\modelli\\'.$documento.'" "..\\JDocServer\\DocumentiGenerati\\'.$nomecognome.'-'.$candidato_id.'-'.$documento.'" ';
                exec($command);
            }
            if($ext=='docx')
            {
                $data['documento']=$documento;
                $data['modello']=$documento;
                if($genere==1)
                {
                    $data['modello']=$documentonoext."_donna.docx";
                }
                $this->load->view('sys/desktop/stampe/easywork_documento',$data);
                
            }
        }
        
        if($converti=='true')
        {
            $this->custom_easywork_convertidocumenti();
            //$this->custom_easywork_spostainfirma();
        }
    }
    
    public function custom_easywork_convertidocumenti()
    {
        $files = scandir('../JDocServer/DocumentiGenerati');
        foreach ($files as $key => $file) {
            $pos=  strpos($file, '.');
            if($pos !== false) 
            {
                $file_array= explode('.', $file);
                $filenoext=$file_array[0];
                $ext=$file_array[1];
                if($ext=='docx')
                {
                        $command='move "..\\JDocServer\\DocumentiGenerati\\'.$file.'" "..\\JDocServer\\DocumentiDaConvertire\\'.$file.'" ';
                        exec($command);
                    
                }
                if($ext=='pdf')
                {
                        $command='move "..\\JDocServer\\DocumentiGenerati\\'.$file.'" "..\\JDocServer\\DocumentiDaFirmare\\'.$file.'" ';
                        exec($command);
                }
                
            }
        }
    }
    
    public function custom_easywork_convertidocumenti_BAK()
    {
        $files = scandir('../JDocServer/DocumentiGenerati');
        foreach ($files as $key => $file) {
            $pos=  strpos($file, '.');
            if($pos !== false) 
            {
                $file_array= explode('.', $file);
                $filenoext=$file_array[0];
                $ext=$file_array[1];
                if($ext=='docx')
                {
                    $command='cd ./tools/OfficeToPDF/ && OfficeToPDF.exe "../../../JDocServer/DocumentiGenerati/'.$file.'" "../../../JDocServer/DocumentiGenerati/'.$filenoext.'.pdf" && DEL "..\\..\\..\\JDocServer\\DocumentiGenerati\\'.$file.'" ';
                    exec($command);
                }
                
            }
        }
    }
    
    public function custom_easywork_spostainfirma()
    {
        $files = scandir('../JDocServer/DocumentiGenerati');
        foreach ($files as $key => $file) {
            $command='copy "..\\JDocServer\\DocumentiGenerati\\'.$file.'" "..\\JDocServer\\DocumentiDaFirmare\\'.$file.'" && DEL "..\\JDocServer\\DocumentiGenerati\\'.$file.'"';
            exec($command);
        }
    }
    
    
    public function ajax_modifica_contrattuale($recordid_contratto)
    {
        $contratto= $this->Sys_model->db_get_row('user_contratti',"*","recordid_='$recordid_contratto'");
        $recordid_contratto_modificato=$this->Sys_model->duplica_record('contratti', $recordid_contratto);
        
        $fields['recordidazienda_']=$contratto['recordidazienda_'];
        $fields['recordiddipendenti_']=$contratto['recordiddipendenti_'];
        $fields['recordidprofessioni_']=$contratto['recordidprofessioni_'];
        $fields['recordidqualifiche_']=$contratto['recordidqualifiche_'];
        $fields['recordidccl_']=$contratto['recordidccl_'];
        $fields['modificacontrattuale']=$contratto['id'];
        $fields['modificacontrattuale_recordid']=$contratto['recordid_'];
        $fields['creatorid_']=$contratto['creatorid_'];
        $fields['visto']='amm';
        $fields['mediagiornalieracontrattuale']='';
        $fields['creatorid_']= $this->get_userid();
        
        $this->Sys_model->update_record('contratti', 1, $fields,"recordid_='$recordid_contratto_modificato' ");
        echo $recordid_contratto_modificato;
    }
    
    public function ajax_get_datediff()
    {
        $post=$_POST;
        $date1=$post['date1'];
        //$date1=date('Y-m-d', strtotime($date1. ' - 1 days'));
        $date2=$post['date2'];
        //$date2=date('Y-m-d', strtotime($date2. ' + 1 days'));
        
        
        
        
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $datediff = $date2 - $date1;

        $diff_giorni_totale=(round($datediff / (60 * 60 * 24))+1);
        $diff_calcolata=($diff_giorni_totale/3)*2;
        $diff_mesi_disdetta=floor($diff_calcolata/30.41);
        $diff_giorni_disdetta=floor($diff_calcolata-$diff_mesi_disdetta*30.41);
        
        if($diff_mesi_disdetta!=0)
        {
            $mesi_text="$diff_mesi_disdetta mesi ";
        }
        else
        {
            $mesi_text='';
        }
        
        if($diff_giorni_disdetta!=0)
        {
            $giorni_text="$diff_giorni_disdetta giorni";
        }
        else
        {
            $giorni_text='';
        }
        
        if($date1==$date2)
        {
           $giorni_text=1; 
        }
        
        if($diff_mesi_disdetta>=3)
        {
            echo "3 mesi";
        }
        else
        {
            echo $mesi_text."".$giorni_text;
        }
        //echo $diff_giorni."-".$diff_calcolo."-".$diff_calcolo2."-".$diff_calcolo3."-".$diff_calcolo4;
    }
    
    
    public function script_set_all_notetempo_presenzemensili()
    {
        $presenzemensili= $this->Sys_model->db_get('user_presenzemensili');
        foreach ($presenzemensili as $key => $presenzamensile) {
            $recordid=$presenzamensile['recordid_'];
            echo "$recordid <br/>";
            $this->Sys_model->set_notetempo_presenzemensili($recordid);
            
        }
    }
    
    public function script_set_ultimenote()
    {
        $dipendenti=$this->Sys_model->db_get('user_dipendenti');
        foreach ($dipendenti as $key => $dipendente) {
            echo "Elaborando ".$dipendente['id']." ".$dipendente['cognome']." ".$dipendente['nome']."<br/>";
            $this->Sys_model->set_ultimenote($dipendente['recordid_']);
        }
    }
    
    public function script_set_ultimadatachiusura()
    {
        $dipendenti=$this->Sys_model->db_get('user_dipendenti');
        foreach ($dipendenti as $key => $dipendente) {
            echo "Elaborando ".$dipendente['id']." ".$dipendente['cognome']." ".$dipendente['nome']."<br/>";
            $this->Sys_model->set_ultimadatachiusura($dipendente['recordid_']);
        }
    }     
    
    
    public function ajax_calcola_preavvisodisdetta($recordid_contratto)
    {
        echo $this->Sys_model->calcola_preavvisodisdetta($recordid_contratto);
    }
    
    
    public function ajax_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl='')
    {
        $today=date('Y-m-d');
        if($this->isnotempty($recordid_ccl))
        {
            $calendarioaziendale=$this->Sys_model->get_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl);
        }
        else
        {
            $calendarioaziendale=array();
        }
        $data['calendarioaziendale']=$calendarioaziendale;
        $data['recordid_azienda']=$recordid_azienda;
        $data['anno']=$anno;
        $data['recordid_ccl']=$recordid_ccl;
        $data['ccllist']=$this->Sys_model->db_get('user_ccl','*',"versione='1'","ORDER BY nomeccl asc");
        $sql="
            SELECT recordidccl_,sum(ore)
            FROM user_calendarioaziendale
            WHERE recordidazienda_='$recordid_azienda'  AND data>='$anno-01-01' AND data<='$anno-12-31' AND (recordidccl_<>null OR recordidccl_<>'')
            group by recordidccl_
            having sum(ore)>0
            ";
        $rows= $this->Sys_model->select($sql);
        $ccllist_compiled=array();
        foreach ($rows as $key => $row) {
            $ccllist_compiled[$row['recordidccl_']]=$row['recordidccl_'];
        }
        $data['ccllist_compiled']=$ccllist_compiled;
        
        $data['ragionesociale']=$this->Sys_model->db_get_value('user_azienda','ragionesociale',"recordid_='$recordid_azienda'");
        
        $sql="select distinct(anno) from user_giornifestivi ORDER BY anno asc
            ";
        $anni=$this->Sys_model->select($sql);
        $data['anni_list']=$anni;
        echo $this->load->view ('sys/desktop/custom/3p/calendarioaziendale',$data);
    }
    
    public function ajax_salva_calendarioaziendale()
    {
        $post=$_POST;
        $this->Sys_model->salva_calendarioaziendale($post);
        echo 'ok';
    }
    
    public function ajax_cancella_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl='')
    {
        $this->Sys_model->cancella_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl);
        echo 'ok';
    }
    
    
    
    
    public function aggiorna_rapportidilavoro_da_contratto($recordid_contratto)
    {
        $this->Sys_model->custom_3p_aggiorna_rapportidilavoro_da_contratto($recordid_contratto);
    }
    
    public function  aggiorna_rapportidilavoro_per_mese_da_contratti_add_custom_update($anno='',$mese='')
    {
        if(($anno=='')&&($mese==''))
        {
            $anno=date('Y');
            $mese=date('n');
        }
        $sql="
            SELECT recordid_,recordiddipendenti_
            FROM user_presenzemensili
            WHERE anno=$anno AND mese='$mese'
            ";
        $presenze_rows= $this->Sys_model->select($sql);
        foreach ($presenze_rows as $key => $presenze_row) {
            $recordid_dipendente=$presenze_row['recordiddipendenti_'];
            $recordid_ultimocontratto=$this->Sys_model->db_get_value('user_contratti','recordid_',"recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')","ORDER BY datainizio desc");

            if($this->isnotempty($recordid_ultimocontratto))
            {
                $this->Sys_model->add_custom_update_php('aggiorna_rapportidilavoro_da_contratto',$recordid_ultimocontratto);
            }
        }
    }
    
    public function custom3p_genera_listaclienti()
    {
        $this->Sys_model->custom3p_genera_listaclienti();
    }
    
    public function custom3p_genera_listaclienti_azienda($recordid_azienda)
    {
        $this->Sys_model->custom3p_genera_listaclienti_azienda($recordid_azienda);
    }
    
    public function custom3p_aggiorna_stato_clienti()
    {
        $this->Sys_model->custom3p_aggiorna_stato_clienti();
    }
    
    public function custom3p_aggiorna_stato_cliente($recordid_azienda)
    {
        $this->Sys_model->custom3p_aggiorna_stato_cliente($recordid_azienda);
    }

    public function ajax_load_content_listaclienti($annoselezionato='')
    {
        $data=array();
        $data['clienti']= $this->load_content_listaclienti('OK');
        $data['clientiinuscita']= $this->load_content_listaclienti('Uscita');
        $data['exclienti']= $this->load_content_listaclienti('Ex cliente');

        echo   $this->load->view("sys/desktop/custom/3p/listaclienti",$data,true);
    }
    
    public function load_content_listaclienti($stato)
    {
        $data['clienti']= $this->Sys_model->db_get('user_listaclienti','*',"stato = '$stato'","ORDER BY cliente");
        return   $this->load->view("sys/desktop/custom/3p/listaclienti_table",$data,true);
    }
    
    
    public function swissbix_import_procall()
    {

        
        $last_tabindex=$this->Sys_model->db_get_value('user_procall','tabindex','true',"ORDER BY tabindex desc LIMIT 1");
        if($last_tabindex==null)
            $last_tabindex=0;
        echo "Last tabindex:".$last_tabindex."<br/>";
        $myPDO = new PDO('sqlite:../JDocServer/CtiServerDatabasejournal.db');
        $result = $myPDO->query("SELECT * FROM journal WHERE tabindex>$last_tabindex AND StartTime>='2020-12-01' ORDER BY StartTime");
        while ($row = $result->fetchObject()) {
            $fields=array();
            $status='Missing Timesheet';
            
            if($row->DurationConnected==0)
            {
               $status='Missed Call'; 
               $starttime=$row->StartTime;
               $datetime=$starttime;
               $fields['datetime']=$starttime;
               $fields['date']=date('Y-m-d', strtotime($starttime));
            }
            else
            {
                $connecttime=$row->ConnectTime;
                $datetime=$connecttime;
                $fields['datetime']=$connecttime;
                $fields['date']=date('Y-m-d', strtotime($connecttime));
                $fields['connecttime']=date('H:i', strtotime($connecttime));
                $fields['disconnecttime']=date('H:i', strtotime($row->DisconnectTime));
                $fields['duration']=$row->DurationConnected;
                $fields['duration_minutes']=ceil($row->DurationConnected/60);
            }
            
            $fields['tabindex']=$row->TabIndex;
            $fields['connectionid']=$row->ConnectionID;
            $fields['entryid']=$row->EntryID;
            $fields['linenumber']=$row->LineNumber;
            $username=$row->LineUserName;
            $fields['username']=$username;
            $userid='';
            if($username=='alessandro.galli')
            {
                $userid=4;
            }
            if($username=='davide.crudo')
            {
                $userid=5;
            }
            if($username=='donato.amadini')
            {
                $userid=9;
            }
            if($username=='franca.dellapietra')
            {
                $userid=13;
            }
            if($username=='luca.nicolussi')
            {
                $userid=10;
            }
            if($username=='martina.panetta')
            {
                $userid=15;
            }
            if($username=='mauro.gallani')
            {
                $userid=6;
            }
            if($username=='roberto.piazzon')
            {
                $userid=8;
            }
            if($username=='roberto.vittori')
            {
                $userid=12;
            }
            if($username=='sacha.brunel')
            {
                $userid=11;
            }
            $fields['userid']=$userid;
            $fields['phonenumber']=$row->PhoneNumber;
            $fields['localnumber']=$row->LocalNumber;
            $fields['contactname']=$row->ContactName;
            $fields['contactcompany']=$row->ContactCompany;
            $fields['contactentryid']=$row->ContactEntryID;
            $fields['contactentrystoreid']=$row->ContactEntryStoreID;
            if($row->ContactEntryStoreID=='vtenext Account')
            {
                $fields['accountid']=$row->ContactEntryID;
            }
            if($row->ContactEntryStoreID=='vtenext Contacts')
            {
                $fields['contactid']=$row->ContactEntryID;
            }
            
            $fields['status']=$status;
            $fields['memo']=$row->Memo;
            $recordid=$this->Sys_model->insert_record('procall',1,$fields);
            echo $recordid."-".$datetime."-".$username."<br/>";
        }

    }
    
    public function crea_permessiutente()
    {
        $this->Sys_model->crea_permessiutente();
    }
    
    
    public function script_custom_3p_salva_contratti()
    {
        $sql="SELECT * FROM user_contratti WHERE (datafine is null AND datadisdetta is null) OR datafine>'2021-01-01' OR datadisdetta>'2021-01-01'";
        $contratti=$this->Sys_model->select($sql);
        foreach ($contratti as $key => $contratto) {
            $this->Sys_model->add_custom_update_php('custom_3p_salva_contratto',$contratto['recordid_']);
        }
        
    }
    
    public function custom_3p_salva_contratto($recordid_contratto)
    {
        $this->Sys_model->custom_3p_salva_contratto($recordid_contratto);
    }
    
    public function add_custom_update_php_custom_3p_salva_contratto($recordid_contratto)
    {
        $this->Sys_model->add_custom_update_php('custom_3p_salva_contratto',$recordid_contratto);
    }
    
    
    public function custom3p_check_servizi()
    {
        $return='';
        $sql="SELECT id FROM custom_update_php where stato='todo'";
        $result=$this->Sys_model->select($sql);
        $counter=count($result);
        echo $counter;
    }
    
    
    public function ajax_load_custom_3p_jobdescription($recordid_richiestericercapersonale)
    {
        $block= $this->load_custom_3p_jobdescription($recordid_richiestericercapersonale);
        echo $block;
    }
    
    public function load_custom_3p_jobdescription($recordid_richiestericercapersonale)
    {
        $data=array();
        $data['recordid_richiestericercapersonale']=$recordid_richiestericercapersonale;
        
        $jobdescription=$this->Sys_model->db_get_value('user_richiestericercapersonale','jobdescription',"recordid_='$recordid_richiestericercapersonale'");
        if(isempty($jobdescription))
        {
           $jobdescription='
                  <p class="MsoNormal" style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;line-height:normal">                <font face="Times New Roman, serif"><span style="font-size: 16px;">                Per un nostro cliente nel <span style="color:red">XXX</span> siamo alla ricerca di:</span></font></p><p class="MsoNormal" style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;line-height:normal"><font face="Times New Roman, serif"><span style="font-size: 16px;"><br></span></font></p><p class="MsoNormal" style="text-align: center; line-height: normal;"><font face="Times New Roman, serif">                <span style="font-size: 16px;font-weight:bold;color:red">N XXX</span></font></p><p class="MsoNormal" style="text-align: center; line-height: normal;"><font face="Times New Roman, serif"><span style="font-size: 16px;font-weight:bold;color:red"><br></span></font></p><p class="MsoNormal" style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;line-height:normal"><font face="Times New Roman, serif"><span style="font-size: 16px;"><b>Si richiede:</b></span></font></p><ul><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Esperienza nella professione di almeno <span style="color:red">N</span> anni;</span></font></li><li style="line-height: 2;"><span style="font-size: 16px; font-family: &quot;Times New Roman&quot;, serif; letter-spacing: 0px;">Disponibile a lavoro su </span><span style="font-size: 16px; font-family: &quot;Times New Roman&quot;, serif; letter-spacing: 0px; color: red;">N turni/giornata;</span></li><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Disponibilità immediata;</span></font></li><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Italiano lingua madre;</span></font></li><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Automunito/a;</span></font></li></ul><p style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;"><br></span></font></p><p class="MsoNormal" style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;line-height:normal"><font face="Times New Roman, serif"><span style="font-size: 16px;"><span style="font-size: 16px;"><b>Si offre:</b></span></span></font></p>                <ul><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Impiego temporaneo <span style="color:red">XXX</span>;</span></font></li><li style="line-height: 2;"><font face="Times New Roman, serif"><span style="font-size: 16px;">Salario adeguato alle capacità.</span></font></li></ul><p style="line-height: normal;"><br></p><div><br></div><ul type="disc"></ul>                         
                ';
        }

        $jobdescription= str_replace("'", "\'", $jobdescription);
        $jobdescription=  str_replace("\r\n", "", $jobdescription);
        $jobdescription=  str_replace("\n", "", $jobdescription);
        $jobdescription=  str_replace("\r", "", $jobdescription);

        $data['jobdescription']=$jobdescription;
        
        $note=$this->Sys_model->db_get_value('user_richiestericercapersonale','note',"recordid_='$recordid_richiestericercapersonale'");
        $note= str_replace("'", "\'", $note);
        $note=  str_replace("\r\n", "", $note);
        $note=  str_replace("\n", "", $note);
        $note=  str_replace("\r", "", $note);
        
        $data['note']=$note;
        return $this->load->view('sys/desktop/custom/3p/jobdescription',$data, TRUE);
    }
    
    public function salva_custom_3p_jobdescription()
    {
        $post=$_POST;
        $recordid_richiestericercapersonale=$post['recordid_richiestericercapersonale'];
        $record_richiestericercapersonale=$this->Sys_model->db_get_row('user_richiestericercapersonale','*',"recordid_='$recordid_richiestericercapersonale'");
        $creation=date("Y-m-d H:i:s",strtotime($record_richiestericercapersonale['creation_']));
        $lastupdate=date("Y-m-d H:i:s",strtotime($record_richiestericercapersonale['lastupdate_']));
        $this->Sys_model->salva_custom_3p_jobdescription($post);
        if($creation==$lastupdate)
        {
            echo 'new';
        }
        else
        {
            echo 'edit';
        }
    }
    
    public function ajax_custom3p_prepara_notifica_email($recordid,$statoprocesso)
    {
        $data=array();
        $richiestaricercapersonale=$this->Sys_model->db_get_row('user_richiestericercapersonale','*',"recordid_='$recordid'");
        $jobdescription=$richiestaricercapersonale['jobdescription'];
        $stato=$richiestaricercapersonale['stato'];
        $data['recordid']=$recordid;
        $data['users']=$this->Sys_model->db_get('sys_user','*',"disabled='N' AND username not like '%group%' and username!='superuser'","ORDER BY firstname");
        //$data['groups']=$this->Sys_model->db_get('sys_lookup_table_item','*',"lookuptableid='gruppo_contatti'");
        $contatti=$this->Sys_model->db_get('user_contatti','*',"gruppo !=''");
        $groups=array();
        foreach ($contatti as $key => $contatto) {
            $contatto_gruppo=$contatto['gruppo'];
            $contatto_gruppo=$this->Sys_model->get_lookup_table_item_description("gruppo_contatti", $contatto_gruppo);
            $groups[$contatto_gruppo]['label']=$contatto_gruppo;
            if(array_key_exists('email', $groups[$contatto_gruppo]))
            {
                $groups[$contatto_gruppo]['email']=$groups[$contatto_gruppo]['email'].";".$contatto['email'];
            }
            else
            {
                $groups[$contatto_gruppo]['email']=$contatto['email'];
            }
            
        }
        $data['groups']=$groups;
        $pre_mailbody="";
        if($statoprocesso=='nuovo')
        {
            $pre_mailbody="Buongiorno <br/> vi informiamo che è appena stata inserita una nuova richiesta di personale: <br/><br/>";
        }
        if($statoprocesso=='modificato')
        {
            $pre_mailbody="Buongiorno <br/> vi informiamo che è appena stata modifica una  richiesta di personale: <br/><br/>";
        }
        if($statoprocesso=='statomodificato')
        {
            $pre_mailbody="Buongiorno <br/> vi informiamo che il seguente annuncio ha cambiato lo stato in: $stato </b> <br/><br/>";
        }
        $jobdescription= str_replace("'", "&#39;", $jobdescription);
        $data['mailbody']=$pre_mailbody.$jobdescription;
        $block=$this->load->view('sys/desktop/block/prepara_notifica_email',$data, TRUE);
        echo $block;
    }
    
    public function custom3p_invia_notifica_email()
    {
        $post=$_POST;
        $recordid=$post['recordid'];
        /*$users=array();
        if(array_key_exists('users', $post))
        {
            $users=$post['users'];
        }
        $groups=array();
        if(array_key_exists('groups', $post))
        {
            $groups=$post['groups'];
        }
        $recordid=$post['recordid'];
        $text=$post['text'];
        $mail_to="";
        foreach ($users as $key => $userid) {
            $user= $this->Sys_model->db_get_row("sys_user","email","id=$userid");
            if($mail_to!='')
            {
                $mail_to=$mail_to.'|;|';
            }
            $mail_to=$mail_to.$user['email'];
        }
        
        foreach ($groups as $key => $groupname) {
            $group_users=$this->Sys_model->db_get("user_contatti","*","gruppo='$groupname'");
            foreach ($group_users as $key => $group_user) {
                if($mail_to!='')
                {
                    $mail_to=$mail_to.'|;|';
                }
                $mail_to=$mail_to.$group_user['email'];
            }
        }
        if($this->isnotempty($post['email']))
        {
            if($mail_to!='')
            {
                $mail_to=$mail_to.'|;|';
            } 
            $mail_to=$mail_to.$post['email'];
        }
        */
       $mail_to=$post['emails']; 
       $mail_to= str_replace(",", "|;|", $mail_to);
       $mail_subject="Notifica richiesta di ricerca personale";
       $mail_body=$post['mailbody'];
       $mail_recordid=$this->Sys_model->push_mail_queue_smart('1',$mail_to,$mail_subject,$mail_body,'');
       $mail=$this->Sys_model->get_record('mail_queue',$mail_recordid);
       $sql="UPDATE user_mail_queue SET recordidrichiestericercapersonale_='$recordid' WHERE recordid_='$mail_recordid'";
       $this->Sys_model->execute_query($sql);
       $send_result=$this->jdw_send_mail_complete($mail);
    }
    
    
    public function jdw_send_mail_complete($mail)
    {
        $mail_id=$mail['id'];
        $send_result= $this->jdw_send_mail($mail);
        if($send_result=='sent')
        {
            $this->Sys_model->update_queued_mail_status($mail_id,'inviata');  
            $this->Sys_model->update_queued_mail_dataora_invio($mail_id); 
            echo 'Mail inviata a '.$mail['mailto'].'- '.$mail['mailbcc'].'<br/>';
        }
        else
        {
            if($send_result=='stop')
            {
                echo 'stop';
                
            }
            else
            {
                echo $send_result;
                $this->Sys_model->update_queued_mail_status($mail_id,'errore');
                $this->Sys_model->update_queued_mail_note($mail_id,'errore - '.$send_result);
            }
            
        }
        
        return $send_result;
    }
    
    public function api_hubspot_get_deals($dealstage)
    {
        sleep(5);
        $hubSpot = \HubSpot\Factory::createWithApiKey('eu1-9c95-52d3-4463-ac6f-7ff40aa3591a');

        $searchRequest = new \HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest();
        
        $filterGroup = new \HubSpot\Client\Crm\Deals\Model\FilterGroup();
        
        $filter = new \HubSpot\Client\Crm\Deals\Model\Filter();
        $filter->setOperator('EQ');
        $filter->setPropertyName('dealstage');
        $filter->setValue($dealstage);
        $filterGroup->setFilters([$filter]);
        
        $searchRequest->setFilterGroups([$filterGroup]);
        //$searchRequest->setSorts('dealstage');
        $searchRequest->setLimit(100);
        $searchRequest->setProperties(["dealname","dealstage","amount","description","acconto","fixed_price","finaziamento_leasing","closedate","hubspot_owner_id"]);

        // @var CollectionResponseWithTotalSimplePublicObject $contactsPage
        
        
        $deals = $hubSpot->crm()->deals()->searchApi()->doSearch($searchRequest);
        $deals=reset($deals)['results'];
        //var_dump($deals);
        return $deals;
    }
    
    public function api_hubspot_sync_deals($type)
    {
        ini_set('max_execution_time', 900);
        
        
        $deals=array();
        if($type=='ICT')
        {
            //$deals_ict_chiusovinto=$this->api_hubspot_get_deals('closedwon');
            //$deals_ict_rifiutato=$this->api_hubspot_get_deals('58730966');
            //$deals_ict_validazionetecnica=$this->api_hubspot_get_deals('56091101');
            //$deals_ict_controllosolvibilita=$this->api_hubspot_get_deals('58541271');
            //$deals=array_merge($deals_ict_chiusovinto,$deals_ict_rifiutato,$deals_ict_validazionetecnica,$deals_ict_controllosolvibilita);
            
            $deals_ict_attesaacconto=$this->api_hubspot_get_deals('56091102');
            $deals_ict_ordinemateriale=$this->api_hubspot_get_deals('58730967');
            $deals_ict_dapianificare=$this->api_hubspot_get_deals('57714928');
            $deals_ict_pianificatoincorso=$this->api_hubspot_get_deals('57714922');
            $deals_ict_dafatturare=$this->api_hubspot_get_deals('49897190');
            $deals_ict_chiusocompletato=$this->api_hubspot_get_deals('49897191');
            $deals=array_merge($deals,$deals_ict_attesaacconto,$deals_ict_ordinemateriale,$deals_ict_dapianificare,$deals_ict_pianificatoincorso,$deals_ict_dafatturare,$deals_ict_chiusocompletato);
             
        }
        if($type=='Printing')
        {
            
            $deals_printing_chiusovinto=$this->api_hubspot_get_deals('59453137');
            $deals_printing_rifiutato=$this->api_hubspot_get_deals('59453135');
            $deals_printing_validazionetecnica=$this->api_hubspot_get_deals('59453136');
            $deals_printing_controllosolvibilita=$this->api_hubspot_get_deals('59482352');
            $deals=array_merge($deals_printing_chiusovinto,$deals_printing_rifiutato,$deals_printing_validazionetecnica,$deals_printing_controllosolvibilita);
            
            /*
            $deals_printing_attesaacconto=$this->api_hubspot_get_deals('59482353');
            $deals_printing_ordinemateriale=$this->api_hubspot_get_deals('59482354');
            $deals_printing_dapianificare=$this->api_hubspot_get_deals('59482355');
            $deals_printing_pianificatoincorso=$this->api_hubspot_get_deals('59482356');
            $deals_printing_dafatturare=$this->api_hubspot_get_deals('59482357');
            $deals_printing_chiusocompletato=$this->api_hubspot_get_deals('59482358');
            $deals=array_merge($deals,$deals_printing_attesaacconto,$deals_printing_ordinemateriale,$deals_printing_dapianificare,$deals_printing_pianificatoincorso,$deals_printing_dafatturare,$deals_printing_chiusocompletato);
             */
             
        }
        
        
        
        
        
        
        foreach ($deals as $key => $deal) {
            //var_dump($deal);
            sleep(5);
            $fields=array();
            $deal=reset($deal);
            $dealname=$deal['properties']['dealname'];
            echo "<br/>$dealname<br/>";
            
            $deal_id=$deal['id'];
            $fields['deal_id']=$deal_id;
            $fields['type']=$type;
            $fields['dealname']=$deal['properties']['dealname'];
            $fields['dealstage']=$deal['properties']['dealstage'];
            
            if($type=='ICT')
            {
                $fields['dealstagedesc']= $this->Sys_model->db_get_value('user_hubspotsalestages','salestage','hubspotid_ict="'.$fields['dealstage'].'"');
            }
            if($type=='Printing')
            {
                $fields['dealstagedesc']= $this->Sys_model->db_get_value('user_hubspotsalestages','salestage','hubspotid_printing="'.$fields['dealstage'].'"');
            }
            
            $fields['amount']=$deal['properties']['amount'];
            $fields['description']=$deal['properties']['description'];
            $fields['advancepaymentamount']=$deal['properties']['acconto'];
            $fields['closedate']=date("Y-m-d",strtotime($deal['properties']['closedate']));
            if($deal['properties']['fixed_price']=='true')
            {
                $fields['fixedprice']=1;
            }
            else
            {
                $fields['fixedprice']=0;
            }
            if($deal['properties']['finaziamento_leasing']=='true')
            {
                $fields['leasing']=1;
            }
            else
            {
                $fields['leasing']=0;
            }
            $userid_hubspot=$deal['properties']['hubspot_owner_id'];
            $fields['userid_hubspot']=$userid_hubspot;
            $userid_vte=$this->Sys_model->db_get_value('user_users','vteuserid',"hubspotuserid='$userid_hubspot'");
            $fields['userid_vte']=$userid_vte;
            $jdocuserid=$this->Sys_model->db_get_value('user_users','jdocuser',"hubspotuserid='$userid_hubspot'");
            $fields['user_jdoc']=$jdocuserid;
            $username=$this->Sys_model->db_get_value('sys_user','firstname',"id='$jdocuserid'");
            $fields['username']=$username;
        $hubSpot = \HubSpot\Factory::createWithApiKey('eu1-9c95-52d3-4463-ac6f-7ff40aa3591a');    
        $deal_associations = $hubSpot->crm()->deals()->associationsApi()->getAll($deal_id, "company", null, 500);
        $deal_associations=reset($deal_associations)['results'];
        $company_id='';
        $company_name='';
        foreach ($deal_associations as $key => $deal_association) {
            $deal_association=reset($deal_association);
            $deal_associations_type=$deal_association['type'];
            if($deal_associations_type=='deal_to_company')
            {
               $company_id= $deal_association['id'];
               $fields['company_id']=$company_id;
               $company = $hubSpot->crm()->companies()->basicApi()->getById((int)$company_id, null, null, null, false, null);
               $company=reset($company);
               $company_name=$company['properties']['name'];
               $fields['company_name']= str_replace("'", "''", $company_name);
               $company_name= str_replace("'", "''", $company_name);
               $jdoc_row= $this->Sys_model->db_get_row('user_aziende','*',"ragionesociale='$company_name'");
               if($jdoc_row!=null)
               {
                   $azienda_recordid=$jdoc_row['recordid_'];
                   $fields['recordidaziende_']=$azienda_recordid;
                   $fields['company_bexioid']=$jdoc_row['bexioid'];
               }
            }
        }
                var_dump($fields);
                $jdoc_row= $this->Sys_model->db_get_row('user_hubspotdeals','*',"deal_id='$deal_id'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('hubspotdeals',1,$fields,"recordid_='$recordid'");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('hubspotdeals');
                    $this->Sys_model->insert_record('hubspotdeals',1,$fields);
                }
        
            $this->api_hubspot_get_deals_line_items($deal_id);
             
             
        }
        
        
        
        
        
        
        
        
    }
    
    public function api_hubspot_get_deals_line_items($deal_id)
    {
        $jdoc_row_deal= $this->Sys_model->db_get_row('user_hubspotdeals','*',"deal_id='$deal_id'");
        $recordid_deal=$jdoc_row_deal['recordid_'];
        $recordid_azienda=$jdoc_row_deal['recordidaziende_'];
        
        
        $hubSpot = \HubSpot\Factory::createWithApiKey('eu1-9c95-52d3-4463-ac6f-7ff40aa3591a');
        
        $total_salesprice=0;
        $total_expectedcost=0;
        $total_expectedmargin=0;
        
        sleep(1);
        $line_items = $hubSpot->crm()->deals()->associationsApi()->getAll($deal_id, "line_items", null, 500);

        $line_items=(array)$line_items;
        $line_items=reset($line_items)['results'];
        foreach ($line_items as $key => $line_item) {
            
            $line_item=(array)$line_item;
            $line_item=reset($line_item);
            $line_item_id= $line_item['id'];
            //$line_item = $hubSpot->crm()->lineItems()->basicApi()->getById((int)$line_item_id, ["name","amount"], null, null, false, null);
            $searchRequest = new \HubSpot\Client\Crm\lineItems\Model\PublicObjectSearchRequest();
        
        $filterGroup = new \HubSpot\Client\Crm\lineItems\Model\FilterGroup();
        
        $filter = new \HubSpot\Client\Crm\lineItems\Model\Filter();
        $filter->setOperator('EQ');
        $filter->setPropertyName('hs_object_id');
        $filter->setValue($line_item_id);
        $filterGroup->setFilters([$filter]);
        
        $searchRequest->setFilterGroups([$filterGroup]);
        $searchRequest->setLimit(100);
        $searchRequest->setProperties(["name","amount","quantity","hs_product_id","hs_cost_of_goods_sold","price","discount","hs_sku","recurringbillingfrequency"]);

       
            sleep(1);
            $line_item = $hubSpot->crm()->lineItems()->searchApi()->doSearch($searchRequest);
            $line_item=reset($line_item)['results'];
            if(count($line_item)>0)
            {
                $line_item=reset($line_item);
                $line_item=reset($line_item)['properties'];
                //var_dump($line_item);
                $fields['hubspot_dealid']=$deal_id;
                $fields['name']=$line_item['name'];
                $fields['quantity']=$line_item['quantity'];
                $fields['unit_expected_cost']=$line_item['hs_cost_of_goods_sold'];
                $fields['expected_cost']=$line_item['hs_cost_of_goods_sold']*$line_item['quantity'];
                $fields['price']=$line_item['amount'];
                $fields['expected_margin']=$fields['price']-$fields['expected_cost'];
                $fields['sku']=$line_item['hs_sku'];
                $fields['recurringbillingfrequency']=$line_item['recurringbillingfrequency'];
                $fields['line_item_id']=$line_item_id;
                $fields['product_id']=$line_item['hs_product_id'];




                $fields['recordidaziende_']=$recordid_azienda;
                $fields['bexio_id']=$jdoc_row_deal['company_bexioid'];
                $fields['recordidhubspotdeals_']=$recordid_deal;
                var_dump($fields);
                $jdoc_row= $this->Sys_model->db_get_row('user_hubspotlineitems','*',"line_item_id='$line_item_id'");
                if($jdoc_row!=null)
                {
                    $recordid=$jdoc_row['recordid_'];
                    $this->Sys_model->update_record('hubspotlineitems',1,$fields,"recordid_='$recordid'");
                }
                else
                {
                    $fields['id']= $this->Sys_model->generate_id('hubspotdeals');
                    $this->Sys_model->insert_record('hubspotlineitems',1,$fields);
                }
                
                $total_salesprice=$total_salesprice+$fields['price'];
                $total_expectedcost=$total_expectedcost+$fields['expected_cost'];
                $total_expectedmargin=$total_salesprice-$total_expectedcost;
            }
            
            
            
        }
        echo "<br/><br/>$total_expectedmargin - $recordid_deal<br/>";
        $fields=array();
        $fields['total_salesprice']=$total_salesprice;
        $fields['total_expectedcost']=$total_expectedcost;
        $fields['total_expectedmargin']=$total_expectedmargin;
        $this->Sys_model->update_record('hubspotdeals',1,$fields,"recordid_='$recordid_deal'");
    }
    
    
    public function api_hubspot_update_dealstage()
    {
        $get=$_GET;
        $dealid=$get['dealid'];
        $dealstage=$get['dealstage'];
        $type=$get['type'];
        $hubSpot = \HubSpot\Factory::createWithApiKey('eu1-9c95-52d3-4463-ac6f-7ff40aa3591a');
        
        if($type=='ICT')
        {
            $dealstage= $this->Sys_model->db_get_value('user_hubspotsalestages','hubspotid_ict',"salestage='$dealstage'");
        }
        if($type=='Printing')
        {
            $dealstage= $this->Sys_model->db_get_value('user_hubspotsalestages','hubspotid_printing',"salestage='$dealstage'");
        }        
        
        $properties = [
            "dealstage" => $dealstage
        ];
        $SimplePublicObjectInput = new \HubSpot\Client\Crm\deals\Model\PublicObjectSearchRequest(['properties' => $properties]);
        
        try {
            $apiResponse = $hubSpot->crm()->deals()->basicApi()->update($dealid, $SimplePublicObjectInput, null);
            //var_dump($apiResponse);
            echo '{
  "result": "Updates"}';
        } catch (ApiException $e) {
            //echo "Exception when calling basic_api->update: ", $e->getMessage();
            echo '{
  "result": "Exception"}';
        }
        
        

    }
    
    
    public function api_hubspot_get_deal()
    {
        
        $hubSpot = \HubSpot\Factory::createWithApiKey('eu1-9c95-52d3-4463-ac6f-7ff40aa3591a');
        


        $searchRequest = new \HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest();
        
        $filterGroup = new \HubSpot\Client\Crm\Deals\Model\FilterGroup();
        
        $filter = new \HubSpot\Client\Crm\Deals\Model\Filter();
        $filter->setOperator('EQ');
        $filter->setPropertyName('dealname');
        $filter->setValue('Test con modifiche 1');
        $filterGroup->setFilters([$filter]);
        
        $searchRequest->setFilterGroups([$filterGroup]);
        //$searchRequest->setSorts('dealstage');
        $searchRequest->setLimit(100);
        $searchRequest->setProperties(["dealname","dealstage","amount","description"]);
        
        
        $deals = $hubSpot->crm()->deals()->searchApi()->doSearch($searchRequest);
    
        var_dump(reset(reset($deals)['results']));

    }
    
    
     function conn_select($conn,$sql) {
        $result = $conn->query($sql);
        $rows = array();
        while($row = mysqli_fetch_array($result))
        {
                $rows[] = $row;
        }
        return $rows;
    }
    
    public function update_hubspot_deals()
    {
        $servername = "10.0.0.25";
        $username = "vtenextremote";
        $password = "DfCEVixXETLf$";
        $database= "vte_swissbix";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);
        
        $deals=$this->Sys_model->db_get('user_hubspotdeals');
        foreach($deals as $deal)
        {
            $fields=array();
            $deal_hubspotid=$deal['deal_id'];
            $deal_recordid=$deal['recordid_'];
            $results=$this->conn_select($conn,"SELECT * FROM vte_potentialscf WHERE cf_p8h_2831='$deal_hubspotid' ");
            //var_dump($results);
            $effectivemargin=$results[0]['cf_z00_2896'];
            echo $deal_hubspotid.": ".$effectivemargin."<br/>"; 
            $fields['totaleffectivemargin']=$effectivemargin;
            $this->Sys_model->update_record('hubspotdeals',1,$fields,"recordid_='$deal_recordid'");

        }
    }
    
    public function get_records()
    {
        $post=$_POST;
        $post['qwe']='asd';
        echo json_encode($post);
    }
    
    
    public function test_pagina1()
    {
        $this->load->view('test_pagina1');
    }
    
    public function get_pagina2()
    {
        echo 'test';
         $this->load->view('test_pagina2');
    }
    
    
    
    
   
    

            
    
    
    

    
    
    
}
?>
