<?php

class Sys_viewcontroller_phone extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->load->view('sys/phone/content/index');
    }
    
    public function get_archives_list()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        $data=$this->Sys_model->get_archive_list_searchable();
        echo json_encode($data);
    }
    
    public function get_lista_etichette($idarchivio)
    {
        $data=$this->Sys_model->get_label_list($idarchivio);
        echo json_encode($data);
    }
    
    /**
     * Funzione che ritorna la lista delle etichette o dei campi già precompilati o meno in base ad un parametro
     * @param type $tableid la tabella per la quale si richiedono i campi
     * @param type $funzione ricerca,inserimento eccetera
     * @param type $recordid
     * @param type $userid utente collegato
     * @param type $type master o linked della tabella
     * @param type $precompilati indica se si vogliono i campi già precompilati
     * @param type $mastertableid (da usare nel caso in cui si vogliono i campi di una tabella linked). Indica la tabella master di riferimento
     * @param type $label
     */
    public function get_lista_campi_etichetta($tableid,$funzione,$recordid=null,$userid,$type='master',$precompilati='0',$mastertableid=null,$label=null)
    {
        $data=null;
        $data2=null;
        if($type=='master')
        {
            if($precompilati=='0'){
                //$data=$this->Sys_model->get_fields_labels($tableid, $funzione,$recordid,$userid);
                $data=$this->Sys_model->get_labels_table($tableid, $funzione,$recordid);
            }
            if($precompilati=='1'){
                //$data=$this->Sys_model->get_filledfields($tableid,$recordid,$userid);
                //$data=$this->Sys_model->get_filledfields_table($tableid,$label,$recordid,$funzione,$type);
                $data=$this->Sys_model->get_fields_table($tableid,$label,$recordid,$funzione,$type);
                //$data2=$this->Sys_model->get_filledfields
            }
        }
        if($type=='linked')
        {
            if($precompilati=='0')
            {
                $data=$this->Sys_model->get_fields_table($tableid,$label,'null','ricerca','linked');
            }
            if($precompilati=='1')
            {
                $data=$this->get_records_and_columns_linkedtable ($tableid, $mastertableid, $recordid, $userid);
            }
        }
        echo json_encode($data);
        //var_dump($data);
    }
    
    public function get_records_and_columns_linkedtable($linkedtableid,$master_tableid,$master_recordid,$userid)
    {
        $data['columns'] = $this->Sys_model->get_colums($linkedtableid, $userid);
        $data['records'] = $this->Sys_model->get_records_linkedtable($linkedtableid, $master_tableid, $master_recordid,$userid);
        return $data;
        //echo json_encode($data);
        //var_dump($data);
    }
    
    public function execute_login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        $username=$_POST['username'];
        $password=$_POST['password'];
        $data=$this->Sys_model->get_user_login($username);
        if(count($data)==1)
        {
            if($data[0]['password']==$password)
            {
                $this->session->set_userdata('idutente',$data[0]['id']);
                $this->session->set_userdata('userid',$data[0]['id']);
                echo $data[0]['id'];
            }
            else{ echo 'no'; }
        }
        else{ echo 'no'; }
    }
    
    public function get_option_lookuptable($tableid)
    {
       $data=$this->Sys_model->get_lookuptable($tableid);
       echo json_encode($data);
    }
    
    public function esegui_ricerca($tableid,$aggiungiPost=true,$limit=null)
    {
        if($aggiungiPost)
            $post=$_POST;
        else
            $post = array();
        $data=$this->Sys_model->get_search_query($tableid, $post);
        echo $data;
    }
    
    public function get_lista_risultati($tableid,$order_key,$limit='')
    {
        $query=$_POST["query"];
        //$order_key=$_POST['order_key'];
        $ascdesc='DESC';
        //$risultati = $this->Sys_model->select($query);
        //tableid,query,order_key(
        $risultati=$this->Sys_model->get_search_result($tableid,$query,$order_key,$ascdesc,$limit);
        //var_dump($risultati);
        echo json_encode($risultati);
    }
    
    public function get_intestazioni_colonne($idarchivio,$idutente)
    {
        $data=$this->Sys_model->get_colums($idarchivio,$idutente);
        //var_dump($data);
        echo json_encode($data);
    }
    
    public function get_lista_allegati($tableid,$recordid)
    {
        $data=$this->Sys_model->get_allegati($tableid, $recordid);
        echo json_encode($data);
    }
    
    public function get_fissi($tableid,$recordid)
    {
        $data=$this->Sys_model->get_fissi($tableid, $recordid);
        echo json_encode($data);
        //var_dump($data);
    }
    
    public function get_foto_path($tableid,$recordid)
    {
        echo $this->Sys_model->get_foto_path($tableid, $recordid);
    }
    
    public function salva_modifiche_record($tableid,$recordid=null)
    {
        $post=$_POST;
        $this->Sys_model->salva_modifiche_record($tableid, $recordid, $post);
        //echo "OK";
    }
}
?>
