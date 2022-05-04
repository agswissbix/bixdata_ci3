<?php

define("NET_ERROR", "Errore+di+rete+impossibile+spedire+il+messaggio");
define("SENDER_ERROR", "Puoi+specificare+solo+un+tipo+di+mittente%2C+numerico+o+alfanumerico");

define("SMS_TYPE_CLASSIC", "classic");
define("SMS_TYPE_CLASSIC_PLUS", "classic_plus");
define("SMS_TYPE_BASIC", "basic");
define("SMS_TYPE_TEST_CLASSIC", "test_classic");
define("SMS_TYPE_TEST_CLASSIC_PLUS", "test_classic_plus");
define("SMS_TYPE_TEST_BASIC", "test_basic");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crm_viewcontroller extends CI_Controller {

    public function logged()
    {
        if($this->session->userdata('ruolo_its')!='cliente')
        {
            if ($this->session->userdata('username')/*=="a.galli"*/)
                return true;
            else
                return false;
        }
        else
            header("location: ".site_url('crm_viewcontroller/view_home/tablet'));
    }

    public function index() {
        //echo 'ciao';
        $this->view_login('tablet');
         
    }

    public function logout($interface = 'desktop') {
        $interface='tablet';
        //$this->session->unset_userdata('username');
        $this->session->sess_destroy();
        $this->load->view('crm/'.$interface . '/content/login_content');
    }

    public function view_home($interface = 'desktop')
    {
        $username=$this->session->userdata('username');
        if($username!=null)
        {
            $this->load->view('crm/'.$interface.'/content/home_content');
            /*$arg=array(
                'module'=>'crm',
                'interface'=>$interface,
                'menu'=>'home_menu',
                'content'=>'home_content'
            );
            view_general($this, $arg);*/
            //view($this, 'tablet', 'crm', 'home',null,null,null);
        }
        else
        {
            $this->view_login('tablet'); 
        }
    }
    
    private function manutenzione($interface){
        if($this->session->userdata('idutente')==1)
            {
                $data=null;
             $this->load->view($interface . '/home', $data);   
            }
            else
            {
            $this->session->unset_userdata('username');
            $this->load->view($interface . '/manutenzione');
            }
    }
    
        public function view_ricerca_avanzata($interface='desktop')
    {
            $data=null;
            if($this->session->userdata('ricercaavanzata')=='si')
            {
                $data=$this->input->post();
                $query="SELECT id,nome FROM user_clienti WHERE 1=1 ";
                if(isset($data['jdoc']))
                    $query.="AND jdocinstallato='si' ";
                if(isset($data['xda']))
                    $query.="AND xdainstallato='si' ";
                if(isset($data['xpps']))
                    $query.="AND xppsinstallato='si' ";
                if(isset($data['sc']))
                    $query.="AND scinstallato='si' ";
                $data['ricerche']=$this->select($query);
            }
            $arg=array(
                    'module'=>'crm',
                    'interface'=>$interface,
                    'content'=>'ricerche_avanzate',
                    'menu'=>'ricerche_avanzate_menu',
                    'content_data'=>$data,
                    'backmenu'=>'view_analisi_installazioni'
                );
            view_general($this, $arg);
            //$this->load->view($interface.'/ricerche_avanzate',$data);
    }
    
    public function view_login($interface = 'desktop') {
        if ($this->session->userdata('ruolo_its') != 'cliente') {
            if ($this->logged()) {
                $this->view_timesheet($interface);
            } else {
                $this->load->view('crm/tablet/content/login_content'); 
            }
        }
        else
            header("location: " . site_url('controllore_its/view_home/mobile'));
    }
    
    public function login($interface='desktop'){
        if($this->input->post('username')!=false)
        {
            $user_data=$this->Sys_model->get_user_login($this->input->post('username'));
            if(sizeof($user_data)==1)
            {
                if($user_data[0]['password']==$this->input->post('password'))
                {
                    $this->session->set_userdata('username', strtolower($this->input->post('username')));
                    $this->session->set_userdata('idutente',$user_data[0]['id']);
                    $this->session->set_userdata('userid',$user_data[0]['id']);
                    $this->view_timesheet($interface);
                }
                else
                {
                    echo 'errato';
                }
            }
            else
            {
                echo 'errato';
            }
        }
        else
            $this->view_login($interface);
    }

    public function view_impostazioni($interface = 'desktop') {
        if ($this->logged()) {
             $this->load->view($interface . '/impostazioni');
        } else {
            $this->load->view($interface . '/login');
        }
    }
    public function view_telefonate_menu($interface = 'desktop') {
            $arg=array(
                    'module'=>'crm',
                    'interface'=>$interface,
                    'content'=>'telefonate_menu',
                    'menu'=>'telefonate_menu'
                );
            view_general($this, $arg);
    }
    
     public function view_timesheet($interface = 'desktop')
    {
         if($this->logged())
            $this->load->view('crm/'.$interface.'/content/timesheet');
         else
            $this->load->view('crm/'.$interface . '/content/login_content');
    }
    public function view_timesheet_inserisci($interface='desktop')
    {
        if($this->logged())
        {
            date_default_timezone_set('Europe/Berlin');
            $idutente=  $this->session->userdata('idutente');
            $attuale=  date("Y-m-d");
            //echo strtotime($attuale);
            //echo $attuale;
            
            $sql="SELECT user_timesheet.recordid_,datainizio,datafine,orainizio,orafine,user_timesheet.note,user_aziende.ragionesociale,servizio,totore
                  FROM (user_timesheet LEFT JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_)
                  WHERE idutente='$idutente' AND user_timesheet.datainizio>='$attuale' AND user_timesheet.stato ILIKE 'completato' ORDER BY orainizio DESC";
            
            $data['timesheets']=$this->select($sql);
            /*$maxid=  $this->select("SELECT MAX(id)
                                    FROM timesheet");
            $data['ultimoinserimento']=  $this->select("SELECT dalle,alle,cliente.nome,servizio
                                                        FROM ");*/
            $data['clienti']=$this->select("SELECT user_aziende.recordid_,ragionesociale FROM user_aziende ORDER BY ragionesociale");
            $rows=$this->select("SELECT *
                                 FROM user_timesheet
                                 WHERE recordid_=(SELECT MAX(recordid_)
                                           FROM user_timesheet
                                           WHERE idutente='$idutente')");
            $data['clientepreselezionato']=$this->select("SELECT recordid_ FROM user_aziende WHERE ragionesociale ILIKE '%nessuna azienda selezionata%'");
            $data['lastinsert']=null;
            if(count($rows)>0)
            {
                $data['lastinsert']=$rows[0];
            }
            $this->load->view('crm/'.$interface.'/content/timesheet_inserisci',$data);
        }
        else
            $this->load->view('crm/'.$interface . '/content/login_content');
    }

    public function view_appuntamenti_menu($interface = 'desktop') {
        //view($this, 'tablet', 'crm', 'appuntamenti_menu', null, null);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'appuntamenti_menu',
                  'menu'=>'appuntamenti_menu'
                );
            view_general($this, $arg);
    }
    
    public function view_appuntamenti_inserisci($interface = 'desktop',$idcliente=null)
    {
        if ($this->logged()) {

            $rows = $this->select("SELECT * FROM sys_user ");
            foreach ($rows as $row)
            {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            if($idcliente!=null)
            {
               $sql = "SELECT * FROM user_clienti WHERE id=$idcliente";  
            }
             else 
             {
                $sql = "SELECT * FROM user_clienti ";
             }

            $rows = $this->select($sql);
            foreach ($rows as $row)
            {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['id'];
                $clienti[]=$cliente;
            }
            $data['clienti']=$clienti;
            $data['idcliente']=$idcliente;
            //view($this, $interface, 'crm', 'appuntamenti_inserisci', null, $data);
            //$this->load->view($interface . '/appuntamenti_inserisci',$data);
            //$this->load->view($interface . '/appuntamenti_inserisci',$data['idcliente']);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'appuntamenti_inserisci',
                  'content_data'=>$data,
                  'menu'=>'appuntamenti_inserisci_menu',
                  'backmenu'=>'view_appuntamenti_menu/tablet'
                );
            view_general($this, $arg);
        } 
        else 
                $this->load->view($interface . '/login');
    }
    
        public function view_appuntamenti_lista($interface = 'desktop') {
        if ($this->logged())
        {
          // error_reporting (E_ALL ^ E_NOTICE);
           // $username=$_COOKIE["user"];
            //echo $username;
            //echo $iduser;
           // $sql="SELECT * FROM APPUNTAMENTI WHERE IDUTENTE=6";
            $idutente=$this->session->userdata('idutente');
            $sql="SELECT user_clienti.nome AS nomecliente, dataappuntamento, appuntamenti.id AS idappuntamento FROM (user_clienti JOIN appuntamenti ON user_clienti.ID=appuntamenti.idcliente)
                 WHERE appuntamenti.idutente=$idutente AND concluso='NO'";
            $rows=$this->SELECT($sql);
           // $I=0;
                foreach($rows as $row)
                    $data['appuntamenti']=$rows;
                    //$I++;
                    //var_dump($data);
               // var_dump($data);
            //$this->load->view($interface . '/appuntamenti_lista',$data);
            //view($this, $interface, 'crm', 'appuntamenti_lista', null, $data);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'appuntamenti_lista',
                  'content_data'=>$data,
                  'menu'=>'appuntamenti_lista_menu',
                  'backmenu'=>'view_appuntamenti_menu/tablet'
                );
            view_general($this, $arg);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_ulteriori_dettagli($interface='deskrop')
    {
         if ($this->logged())
         {
            $data = $this->input->post();
            date_default_timezone_set('Europe/Berlin');
            if ($data['annopartenza'] == "" || $data['giornopartenza'] == "" || $data['mesepartenza'] == "") {
                $datapartenza = NULL;
            }
            else
            {
                $datapartenza = date("Y-m-d H:i:s", strtotime("" . $data['annopartenza'] . "-" . $data['mesepartenza'] . "-" . $data['giornopartenza'] . " " . $data['orepartenza'] . ":" . $data['minutipartenza'] . ":00"));
                //$datapartenza = "'" . $datapartenza . "'";
            }
            if ($data['annoarrivo'] == "" || $data['giornoarrivo'] == "" || $data['mesearrivo'] == "") {
                $dataarrivo = NULL;
            }
            else
            {
                $dataarrivo = date("Y-m-d H:i:s", strtotime("" . $data['annoarrivo'] . "-" . $data['mesearrivo'] . "-" . $data['giornoarrivo'] . " " . $data['orearrivo'] . ":" . $data['minutiarrivo'] . ":00"));
                //$dataarrivo = "'" . $dataarrivo . "'";
            }
                         

            if (($datapartenza==null)&&($dataarrivo==null))
            {
                $dataarrivo=  time();
                $datapartenza=$dataarrivo-(60*60*24*7);
                $dataarrivo=  date("Y-m-d H:i:s", $dataarrivo);
                $datapartenza=  date("Y-m-d H:i:s", $datapartenza);
                //QUERY PER NUMERO INSTALLAZIONI XDA
                
                $sql="SELECT COUNT(*) AS 'NUMERO'
                      FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                      WHERE XDAinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";

                $rows=  $this->select($sql);
                $data['installxda']=$rows[0]['NUMERO'];
                
                //QUERY INSTALLAZIONI XPPS
                $sql=   "SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE XPPSinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
               
                $rows=  $this->select($sql);
                $data['installxpps']=$rows[0]['NUMERO'];                
                //QUERY INSTALLAZIONE JDOC
                $sql="  SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE JDOCinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
            
                $rows=  $this->select($sql);
                $data['installjdoc']=$rows[0]['NUMERO'];
                
                //QUERY INSTALLAZIONE SC
                $sql="  SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE SCinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
            
                $rows=  $this->select($sql);
                $data['installsc']=$rows[0]['NUMERO'];
            }
            else{
                //QUERY PER NUMERO INSTALLAZIONI XDA
                
                $sql="SELECT COUNT(*) AS 'NUMERO'
                      FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                      WHERE XDAinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";

                $rows=  $this->select($sql);
                $data['installxda']=$rows[0]['NUMERO'];
                
                //QUERY INSTALLAZIONI XPPS
                $sql=   "SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE XPPSinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
               
                $rows=  $this->select($sql);
                $data['installxpps']=$rows[0]['NUMERO'];                
                //QUERY INSTALLAZIONE JDOC
                $sql="  SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE JDOCinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
            
                $rows=  $this->select($sql);
                $data['installjdoc']=$rows[0]['NUMERO'];
                
                //QUERY INSTALLAZIONE SC
                $sql="  SELECT COUNT(*) AS 'NUMERO'
                        FROM user_clienti JOIN appuntamenti ON user_clienti.id=appuntamenti.idcliente
                        WHERE SCinstallato='si' AND appuntamenti.timestampo BETWEEN '$datapartenza' AND '$dataarrivo'";
            
                $rows=  $this->select($sql);
                $data['installsc']=$rows[0]['NUMERO'];
            }
            $data['datapartenza']= strtotime($datapartenza);
            $data['dataarrivo']=  strtotime($dataarrivo);
            
            $this->load->view('crm/'.$interface.'/content/ulteriori_dettagli',$data);
            /*$arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'ulteriori_dettagli',
                  'content_data'=>$data,
                  'menu'=>'ulteriori_dettagli_menu',
                  'backmenu'=>'view_analisi_installazioni/'.$interface
                );
            view_general($this, $arg);*/
         }
        else
            $this->load->view($interface . '/login');
    }
    
    public function sistema_timesheet()
    {
        date_default_timezone_set('Europe/Berlin');
        $sql="SELECT * FROM user_timesheet";
        $timesheets=$this->select($sql);
            foreach($timesheets as $timesheet)
            {
                $datainizio=$timesheet['dalle'];
                $datafine=$timesheet['alle'];
                $id=$timesheet['id'];
                $totore=$timesheet['totore'];
                //---------------CALCOLARE ORE IN FORMATO SESSAGGESIMALE
                $datetime1 = new DateTime($datafine);
                $datetime2 = new DateTime($datainizio);
                $interval = $datetime1->diff($datetime2);
                $totore=$interval->format('%h:%I:00'); 
                //--------------FINE CALCOLO ORE SESSAGGESIMALE
                
               $sql="UPDATE user_timesheet
                    SET dalle='$datainizio',
                    alle='$datafine',
                    totore='$totore'
                    WHERE recordid_=$id";
               $this->execute_query($sql);
            }
            $interface='mobile';
            $this->load->view($interface . '/timesheet');
    }    
    public function view_timesheet_modificato($idtimesheet,$interface='desktop')
    {
        if ($this->logged())
        {
            date_default_timezone_set('Europe/Berlin');
            $idutente = $this->session->userdata('idutente');
            $data = $this->input->post();
            $cliente=$data['cliente'];
            $cliente=  addslashes($cliente);
            $note=$data['note'];
            $datastart = $data['datainizio'];
            $datafinish=$data['datafine'];
            $orainizio=$data['orepartenza'].':'.$data['minutipartenza'].':00';
            $orafine=$data['orearrivo'].':'.$data['minutiarrivo'].':00';
            
            $note=  str_replace("'", "''", $note);
            $servizio=$data['servizio'];
            
            $datainizio=$datastart.' '.$orainizio;
            $datafine=$datafinish.' '.$orafine;

            
        $datetime1 = new DateTime($datafine);
        $datetime2 = new DateTime($datainizio);
        $interval = $datetime1->diff($datetime2);
        $totore=$interval->format('%h:%I:00');            

        //$datainizio = "'" . $datainizio . "'";//rimetto le date in formato timestamp altrimenti non vengono prese nel database
        //$datafine = "'" . $datafine . "'";
            
         $sql="UPDATE user_timesheet
               SET recordidaziende_='$cliente',
               orainizio='$orainizio',
               orafine='$orafine',
               note='$note',
               servizio='$servizio',
               totore='$totore',
               modificatoda=$idutente,
               datainizio='$datastart',
               datafine='$datafinish'
               WHERE recordid_='$idtimesheet'";
         $this->execute_query($sql);
         
         //RICARICO LA PAGINA DELL'INSERIMENTO
            $attuale=  date("Y-m-d");
            $attuale.=' 00:00:00';
            
                $alle=  date("Y-m-d");
                $alle.=' 59:59:59';
                //echo strtotime($attuale);
                //echo $attuale;
                
                $sql="SELECT user_timesheet.recordid_,dalle,alle,user_timesheet.note,user_aziende.ragionesociale,servizio,totore
                    FROM (user_timesheet LEFT JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_)
                    WHERE idutente='$idutente' AND user_timesheet.dalle>='$attuale' AND user_timesheet.stato ILIKE 'completato'";
            
            $data['timesheets']=$this->select($sql);
            $data['clienti']=$this->select("SELECT recordid_,ragionesociale FROM user_aziende");
            //$interface='mobile';
            
                $urlpartenza=$this->session->userdata('urlpartenza');
                    if($urlpartenza=='view_timesheet_inserisci')
                        $this->view_timesheet_inserisci ($interface);
                    if($urlpartenza=='view_timesheet_lista')
                    {
                        $data1=$this->session->userdata('datapartenza');
                        $data2=$this->session->userdata('dataarrivo');
                        //ISTRUZIONI PER QUERY
                        $data['timesheets']=  $this->select("SELECT dalle,alle,user_timesheet.note,user_aziende.ragionesociale,user_timesheet.recordid_ AS ID,totore,servizio
                                   FROM (user_timesheet LEFT JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_)
                                   WHERE dalle >='$data1' AND alle <='$data2' AND idutente='$idutente' AND user_timesheet.stato ILIKE 'completato'
                                   ORDER BY idutente,dalle");
                        //view($this, $interface, 'crm', 'timesheet_lista', null, $data);
                    /*$arg=array(
                        'module'=>'crm',
                        'interface'=>'tablet',
                        'content'=>'timesheet_lista',
                        'content_data'=>$data
                        );
                    view_general($this, $arg);*/
                    $this->load->view($interface.'/timesheet_lista',$data);
                    }
                    if($urlpartenza=='view_timesheet_visualizza')
                        $this->view_timesheet_visualizza ($interface);            
        }
        else
            $this->load->view($interface . '/login');
    }
    
    public function view_esportazioni_avanzate($interface='desktop')
    {
        if ($this->logged())
        {
            date_default_timezone_set('Europe/Berlin');
            $data=$this->input->post();         
            $idutente=$data['utente'];
            $idutentecollegato=$this->session->userdata('userid');
            //prelevo il nome e congome dell'utente:
            $righe=$this->Sys_model->select("SELECT firstname,lastname FROM sys_user WHERE id=$idutente");
            $nomeutente=$righe[0]['lastname'].' '.$righe[0]['firstname'];
            $cliente=$data['cliente'];
            $servizio=$data['servizio'];
            
            $datainizio=$data['inizio'];
            $datafine=$data['fine'];
            $sql="SELECT sys_user.username,user_aziende.ragionesociale,user_timesheet.note,servizio,datainizio,datafine,orainizio,orafine,totore
                  FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recordidaziende_
                  WHERE 1=1 AND user_timesheet.stato ILIKE 'completato '";
            if($servizio!='null')
                $sql.="AND servizio='$servizio' ";
            if($idutente!='null')
                $sql.="AND user_timesheet.idutente=$idutente ";
            if($cliente!='null')
            {
                if($cliente!='00000000000000000000000000003192')
                    $sql.="AND user_timesheet.recordidaziende_='$cliente' ";
            }
            if(isset($datainizio))
                $sql.="AND datainizio>='$datainizio' AND orainizio>='00:00:00' ";
            if(isset($datafine))
                $sql.="AND datafine <='$datafine' AND orafine<='23:59:59' ";

            $sql.="ORDER BY datainizio,orainizio DESC";
            $rows=$this->select($sql);
            $data['esportazioni']=$rows;
            $data['nomeutente']=$nomeutente;
            $data['check']=1; //la variabile check serve per stampare la lista dei timesheet
            $data['raggruppamentiexist']=false;
            if(isset($data['sommaore']))
            {
                $sommaore=$data['sommaore'];
                if($sommaore=='si')
                {
                    //$query="SELECT sec_to_time(sum(time_to_sec(totore))) AS TOTORE FROM timesheet WHERE dalle >='$iniziotemp' AND alle <='$finetemp' ";
                    $query="SELECT sum(totore) AS TOTORE FROM user_timesheet WHERE (datainizio >='$datainizio' AND orainizio>='00:00:00') AND (datafine <='$datafine' AND orafine<='23:59:59') AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($cliente!='null')
                    {
                        if($cliente!='00000000000000000000000000003192')
                            $query.="AND recordidaziende_='$cliente' ";
                    }
                    if($servizio!='null')
                        $query.="AND servizio='$servizio'";
                    
                    $res=$this->select($query);
                    $data['sommaore']=$res[0]['totore'];
                }
                $this->session->set_userdata('sommaore','si');
            }
            else
            {
                $data['sommaore']=0;
                $this->session->set_userdata('sommaore','no');
            }

            if(isset($data['raggruppa']))
            {
                if($data['raggruppa']=='si')
                {
                    $data['raggruppamentiexist']=true;
                    $query="SELECT datainizio AS dalle,sum(totore) AS totore FROM user_timesheet WHERE (datainizio >='$datainizio' AND orainizio>='00:00:00') AND (datafine <='$datafine' AND orafine<='23:59:59') AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        if($cliente!='00000000000000000000000000003192')
                            $query.=" AND recordidaziende_='$cliente'";
                    
                    $query.=" GROUP BY datainizio ORDER BY datainizio";
                    $res=$this->select($query);
                    $data['raggruppamenti']=$res;
                    $data['esportazioni']=null;
                    $data['check']=0;
                    $this->session->set_userdata('raggruppamenti','si');
                }
            }
            else
            {
                $data['raggruppamenti']=0;
                $this->session->set_userdata('raggruppamenti','no');
            }
            if(isset($data['raggruppacliente']))
            {
                if($data['raggruppacliente']=='si')
                {
                    $data['raggruppamentiexist']=true;
                    $query="SELECT sum(totore) AS totore, user_aziende.ragionesociale AS cliente
                            FROM user_timesheet JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_ WHERE (datainizio >='$datainizio' AND orainizio>='00:00:00') AND (datafine <='$datafine' AND orafine<='23:59:59') AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        if($cliente!='00000000000000000000000000003192')
                            $query.=" AND recordidaziende_='$cliente'";
                    $query.=" GROUP BY ragionesociale";
                    $res=$this->select($query);
                    $data['raggruppaclienti']=$res;
                    $data['esportazioni']=null;
                    $data['check']=0;
                    $this->session->set_userdata('raggruppaclienti','si');
                }
            }
            else
            {
                $data['raggruppaclienti']=0;
                $this->session->set_userdata('raggruppaclienti','no');
            }
            if(isset($data['raggruppaservizio']))
            {
                if($data['raggruppaservizio']=='si')
                {
                    $data['raggruppamentiexist']=true;
                    $query="SELECT sum(totore) AS totore, servizio
                        FROM user_timesheet WHERE (datainizio >='$datainizio' AND orainizio>='00:00:00') AND (datafine <='$datafine' AND orafine <='23:59:59') AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        if($cliente!='00000000000000000000000000003192')
                            $query.=" AND recordidaziende_='$cliente'";
                    $query.=" GROUP BY servizio";
                    $res=$this->select($query);
                    $data['raggruppaservizi']=$res;
                    $data['esportazioni']=null;
                    $data['check']=0;
                    $this->session->set_userdata('raggruppaservizi','si');
                }
            }
                else
                {
                    $data['$raggruppaservizi']=0;
                    $this->session->set_userdata('raggruppaservizi','no');
                }
                
            //---PRIMA DI VISUALIZZARE LA PAGINA MI OCCUPO DI MANDARE LA MAIL SE NECESSARIO---//
            if(isset($data['inviamail']))
            {
                if($data['inviamail']=='si')
                {
                    $rows=$this->Sys_model->select("SELECT email FROM sys_user WHERE id=$idutentecollegato");
                    $dalle=$data['inizio'];
                    $alle=$data['fine'];
                    $email=$rows[0]['email'];
                    if($servizio=='null')
                        $servizio='';
                    if($cliente=='null')
                        $cliente=='';
                    $command='cd ../Timesheet && "Timesheet - Mail Automatiche.exe" "3" "'.$idutente.'" "'.$email.'" "'.$dalle.'" "'.$alle.'" "'.$servizio.'" "'.$cliente.'"';
                    
                    //----controllo se devo mandare le richieste di raggruppamento
                    if($sommaore=='si')
                        $command.=' "somma"';
                    if($data['raggruppa']=='si')
                        $command.=' "giorno"';
                    if($data['raggruppacliente']=='si')
                        $command.=' "cliente"';
                    if($data['raggruppaservizio']=='si')
                        $command.=' "servizio"';
                    shell_exec($command);
                }
            }
            $this->session->set_userdata('idutilizzato',$idutente);
            $this->session->set_userdata('iniziotemp',$iniziotemp);
            $this->session->set_userdata('finetemp',$finetemp);
            $this->session->set_userdata('cliente',$cliente);
            $this->session->set_userdata('servizio',$servizio);
            $this->load->view('crm/'.$interface.'/content/lista_esportazioni_avanzate',$data);
        }
        else
             $this->load->view('crm/'.$interface.'/content/login_content');
    }
    
    public function view_timesheet_inserito($interface='desktop')
    {
        if ($this->logged())
        {
            date_default_timezone_set('Europe/Berlin');
            $idutente = $this->session->userdata('idutente');
            $data = $this->input->post();
            $cliente=$data['cliente'];
            $cliente=  addslashes($cliente);
            $note=$data['note'];
            //$note=  addslashes($note);
            $note=  str_replace("'", "''", $note);
            $servizio=$data['servizio'];
            
            $datastart=$data['datainizio'];
            $datafinish=$data['datafine'];
            $orainizio=$data['oreinizio'].":".$data['minutiinizio'];
            $orafine=$data['orefine'].':'.$data['minutifine'];
            
            //ecco le date in formato timestamp che mi serviranno per calcolare la differenza
            $datainizio=$data['datainizio'].' '.$data['oreinizio'].':'.$data['minutiinizio'];
            $datafine=$data['datafine'].' '.$data['orefine'].':'.$data['minutifine'];
            
            //calcolo la differenza per sapere quanto dura l'inserimento
            $datetime1 = new DateTime($datafine);
            $datetime2 = new DateTime($datainizio);
            $interval = $datetime1->diff($datetime2);
            $totore=$interval->format('%h:%I:00');
            //$temp=  strtotime($totore);
            //$datainizio = "'" . $datainizio . "'";//rimetto le date in formato timestamp altrimenti non vengono prese nel database
            //$datafine = "'" . $datafine . "'";
            
            $recordid=$this->Sys_model->generate_recordid('timesheet');
            $sql="INSERT INTO user_timesheet (recordid_,idutente,recordidaziende_,note,servizio,totore,datainizio,datafine,orainizio,orafine,stato)
                              VALUES ('$recordid','$idutente','$cliente','$note','$servizio','$totore','$datastart','$datafinish','$orainizio','$orafine','completato')";
            $this->execute_query($sql);
            //view($this, $interface, 'crm', 'timesheet_inserisci',null);
            $this->view_timesheet_inserisci($interface);
        }
        else
            $this->load->view('crm/'.$interface . '/content/login_content');
    }
    
    public function view_timesheet_lista ($interface='desktop')
    {
        if($this->logged())
        {
            $data=$this->input->post();
            
            //carico la lista degli utenti da mostrare nella select
            $username=  $this->session->userdata('username');
            if(($username=='f.lanza')||($username=='m.prati')||($username=='p.beretta'))
                $rows = $this->select("SELECT * FROM sys_user ");
            else
                $rows = $this->select("SELECT * FROM sys_user WHERE username='$username'");
            foreach ($rows as $row)
            {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            $idutente=$data['utente'];
            $this->session->set_userdata('idutilizzato', $idutente);
            date_default_timezone_set('Europe/Berlin');
            $datapartenza=$data['datapartenza'];
            $dataarrivo=$data['dataarrivo'];
            $orainizio=$data['orepartenza'].':'.$data['minutipartenza'].':00';
            $orafine=$data['orearrivo'].':'.$data['minutiarrivo'].':00';

            if($idutente!=0)
            {
                $rows=  $this->select("SELECT datainizio,datafine,orainizio,orafine,user_timesheet.note,user_aziende.ragionesociale,user_timesheet.recordid_,totore,servizio
                                   FROM (user_timesheet JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_)
                                   WHERE (datainizio >= '$datapartenza' AND orainizio>='$orainizio') AND (datafine <= '$dataarrivo' AND orafine<='$orafine') AND idutente='$idutente' AND user_timesheet.stato ILIKE 'completato' ORDER BY dalle DESC");
            }
            else
            {
                 $rows=  $this->select("SELECT datainizio,datafine,orainizio,orafine,user_timesheet.note,user_aziende.ragionesociale,username,user_timesheet.recordid_,totore,servizio
                                   FROM (user_timesheet JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_)
                                   WHERE dalle >= '$datapartenza' AND alle <= '$dataarrivo' AND user_timesheet.stato ILIKE 'completato' ORDER BY dalle DESC");
            }
            $data['datapartenza']=$datapartenza;
            $data['dataarrivo']=$dataarrivo;
            $data['timesheets']=$rows;
            $this->session->set_userdata('datapartenza', $datapartenza);
            $this->session->set_userdata('dataarrivo', $dataarrivo);
            $this->session->set_userdata('urlpartenza','view_timesheet_lista');
            $this->load->view('crm/'.$interface. '/content/timesheet_visualizza',$data);
            //view($this, $interface, 'crm', 'timesheet_lista', null, $data);
                /*$arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'timesheet_lista',
                  'content_data'=>$data,
                  'backmenu'=>'view_timesheet_visualizza',
                  'menu'=>'timesheet_lista_menu'
                );
            view_general($this, $arg);*/
        }
        else
            $this->load->view('crm/'.$interface.'/content/login_content');
            //$this->load->view($interface . '/login');
    }
    
    public function esporta_avanzate()
    {
        date_default_timezone_set('Europe/Berlin');
        //$data=$this->input->post();         

        //RACCOLGO I DATI SETTATI DALLA FUNZIONE VIEW_ESPORTAZIONI_AVANZATE
        $idutente=$this->session->userdata('idutilizzato');
        $servizio=$this->session->userdata('servizio');
        $cliente=$this->session->userdata('cliente');
        $iniziotemp=  $this->session->userdata('iniziotemp');
        $finetemp=$this->session->userdata('finetemp');
        $sommaore=$this->session->userdata('sommaore');
        $raggruppamenti=$this->session->userdata('raggruppamenti');
        $raggruppaservizi=$this->session->userdata('raggruppaservizi');
        $raggruppaclienti=$this->session->userdata('raggruppaclienti');
        
        $sql="SELECT sys_user.lastname,user_aziende.ragionesociale,user_timesheet.note,servizio,dalle,alle,totore
              FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recordidaziende_
              WHERE 1=1 AND user_timesheet.stato ILIKE 'completato' ";
        if($servizio!='null')
            $sql.="AND servizio='$servizio' ";
        if($idutente!='null')
            $sql.="AND user_timesheet.idutente=$idutente ";
        if($cliente!='null')
            $sql.="AND user_timesheet.recordidaziende_='$cliente' ";
        if($iniziotemp)
            $sql.="AND dalle>='$iniziotemp' ";
        if($finetemp)
            $sql.="AND alle <='$finetemp' ";
        
        $sql.="ORDER BY dalle DESC";
        $rows=$this->select($sql);
        
            if($sommaore=='si')
            {
                    $query="SELECT sum(totore) AS totore FROM user_timesheet WHERE dalle >='$iniziotemp' AND alle <='$finetemp' AND user_timesheet.stato ILIKE 'completato'";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($cliente!='null')
                        $query.="AND recordidaziende_='$cliente' ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio'";
                    
                    $res=$this->select($query);
                $numero=$res[0]['totore'];
                //$numero=$res;
                $rows[]=array('','','','','','TOTALE ORE:',$numero);
            }
            
            if($raggruppamenti=='si')
            {
                $rows[]=array();//aggiungo 2 righew vuote
                $rows[]=array('','RANGE RAGGRUPPATO PER GIORNO CON TOTALE ORE');
                $rows[]=array();
                $query="SELECT to_char(dalle,'Mon-dd') AS dalle,sum(totore) AS totore FROM user_timesheet WHERE dalle >='$iniziotemp' AND alle <='$finetemp' AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        $query.=" AND recordidaziende_='$cliente'";
                    
                    $query.=" GROUP BY to_char(dalle,'Mon-dd')";                 
                    $res=$this->select($query);
                    //$data['raggruppamenti']=$res;
                $rows=  array_merge($rows,$res);
            }
            if($raggruppaclienti=='si')
            {
                $rows[]=array();//aggiungo 2 righe vuote
                $rows[]=array('','RANGE RAGGRUPPATO PER CLIENTE');
                $rows[]=array();
                    $query="SELECT sum(totore) AS totore, user_aziende.ragionesociale AS cliente
                            FROM user_timesheet JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_ WHERE dalle >='$iniziotemp' AND alle <='$finetemp' AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        $query.=" AND recordidaziende_='$cliente'";
                    $query.=" GROUP BY ragionesociale";
                    
                    $res=$this->select($query);
                    $rows=  array_merge($rows,$res);
            }
            if($raggruppaservizi=='si')
            {
                $rows[]=array();//aggiungo 2 righe vuote
                $rows[]=array('','RANGE RAGGRUPPATO PER SERVIZIO');
                $rows[]=array();
                    $query="SELECT sum(totore) AS totore, servizio
                        FROM user_timesheet WHERE dalle >='$iniziotemp' AND alle <='$finetemp' AND user_timesheet.stato ILIKE 'completato' ";
                    if($idutente!='null')
                        $query.="AND idutente=$idutente ";
                    if($servizio!='null')
                        $query.="AND servizio='$servizio' ";
                    if($cliente!='null')
                        $query.=" AND recordidaziende_='$cliente'";
                    $query.=" GROUP BY servizio";
                    
                    $res=$this->select($query);
                    $rows=  array_merge($rows,$res);
            }
        $this->esportacsv_array($rows);
    }
    
    public function csv_esporta_dieci()
    {
        date_default_timezone_set('Europe/Berlin');
        $idutente=$this->session->userdata('idutente');
         $sql="SELECT sys_user.firstname AS 'nome',lastname,dalle,alle,user_aziende.ragionesociale AS cliente,user_timesheet.note,servizio,totore
                  FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recoridaziende_
                  WHERE sys_user.id=$idutente AND user_timesheet.stato ILIKE 'completato'
                  ORDER BY dalle DESC
                  LIMIT 10";
         $rows=$this->select($sql);
         $this->esportacsv_array($rows);
    }
    
    public function view_timesheet_esportazioni($interface='desktop')
    {
        if($this->logged()){
            $username=  $this->session->userdata('username');
            if(($username=='f.lanza')||($username=='m.prati')||($username=='p.beretta'))
                $rows = $this->select("SELECT * FROM sys_user ORDER BY lastname,firstname");
            else
                $rows = $this->select("SELECT * FROM sys_user WHERE username='$username'");
            
            foreach ($rows as $row)
            {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utente['username']=$row['username'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            $data['clienti']=$this->select("SELECT recordid_,ragionesociale FROM user_aziende ORDER BY ragionesociale");
            $this->load->view('crm/'.$interface.'/content/timesheet_esportazioni',$data);
        }
        else
            $this->load->view('crm/'.$interface.'/content/login_content');
    }
    
    public function csv_esporta_range()
    {
        date_default_timezone_set('Europe/Berlin');
        $idutente=$this->session->userdata('idutilizzato');
        $datainizio=  $this->session->userdata('datapartenza');
        $datafine=  $this->session->userdata('dataarrivo');
        
        if($idutente!=0)
        {
            $sql="SELECT sys_user.firstname AS 'nome',lastname,dalle,alle,user_aziende.ragionesociale AS cliente,user_timesheet.note,servizio,totore
                  FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recordidaziende_
                  WHERE idutente=$idutente AND dalle>='$datainizio' AND alle <= '$datafine' ORDER BY dalle DESC";
            $rows=$this->select($sql);
        }
        else
        {
            $sql="SELECT sys_user.firstname AS 'nome',lastname,dalle,alle,user_clienti.nome AS 'cliente',user_timesheet.note,servizio,totore
                  FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_clienti ON user_aziende.recordid_=user_timesheet.recordidaziende_
                  WHERE dalle>='$datainizio' AND alle <= '$datafine' ORDER BY user_timesheet.idutente";
            $rows=$this->select($sql);
        }
        $this->esportacsv_array($rows);
    }
    
    public function csv_telemarketing($idtelefonata)
    {
        date_default_timezone_set('Europe/Berlin');       
        $sql="SELECT trasloco AS TONER
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $trasloco=$rows[0]['TONER'];
        
        $sql="SELECT rinnovomobilio
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=$this->select($sql);
        $rinnovomobilio=$rows[0]['rinnovomobilio'];
        
        $sql="SELECT rinnovosedute
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=$this->select($sql);
        $rinnovosedute=$rows[0]['rinnovosedute'];
        
        $sql="SELECT interessedocsedute
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interessedocsedute=$rows[0]['interessedocsedute'];
        
        $sql="SELECT interesseparetidivisorie
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interesseparetidivisorie=$rows[0]['interesseparetidivisorie'];
        
        $sql="SELECT dacontattarearredo
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $dacontattarearredo=$rows[0]['dacontattarearredo'];
        
        $sql="SELECT contrattiinscadenza
        FROM telefonate
        WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $contrattiinscadenza=$rows[0]['contrattiinscadenza'];
        
        $sql="SELECT interessefotocopiatrice
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interessefotocopiatrice=$rows[0]['interessefotocopiatrice'];
        
        $sql="SELECT interessemultifunzione
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interessemultifunzione=$rows[0]['interessemultifunzione'];
        
        $sql="SELECT dacontattarexerox AS XEROX
        FROM telefonate
        WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $dacontattarexerox=$rows[0]['XEROX'];
        
        $sql="SELECT interessetoner
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interesseconsumabili=$rows[0]['interessetoner'];
        
        $sql="SELECT interessecarta
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interessecarta=$rows[0]['interessecarta'];
        
        $sql="SELECT dacontattareoffice
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $dacontattareoffice=$rows[0]['dacontattareoffice'];
        
        $sql="SELECT interessearchiviazione
              FROM telefonate
              WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $interessearchiviazione=$rows[0]['interessearchiviazione'];
        
        $sql="SELECT note
        FROM telefonate
        WHERE id=$idtelefonata";
        $rows=  $this->select($sql);
        $note=$rows[0]['note'];
        
        $data=array(
          array('TRASLOCO','RINNOVO MOBILIO','RINNOVO SEDUTE','INTERESSE DOCUMENTAZIONE SEDUTE','INTERESSE PARETI DIVISORIE','CONTATTARE ARREDO','CONTRATTI IN SCADENZA','INTERESSE FOTOCOPIATRICE','INTERESSE MULTIFUNZIONE','CONTATTARE XEROX','INTERESSE TONER','INTERESSE CARTA','DA CONTATTARE OFFICE','INTERESSE ARCHIVIAZIONE','NOTE'),
          array($trasloco,$rinnovomobilio,$rinnovosedute,$interessedocsedute,$interesseparetidivisorie,$dacontattarearredo,$contrattiinscadenza,$interessefotocopiatrice,$interessemultifunzione,$dacontattarexerox,$interesseconsumabili,$interesseconsumabili,$dacontattareoffice,$interessearchiviazione,$note)
            );
        $this->esportacsv_array($data);
    }
    
    public function csv_statistiche()
    {
        date_default_timezone_set('Europe/Berlin');     
        $array=null;
        $sql="SELECT COUNT(XDAinstallato) AS 'NUMERO'
              FROM user_clienti
              WHERE XDAinstallato='si'";
        $rows=  $this->select($sql);
        $xda=$rows[0]['NUMERO'];
        //$data.='\n';        
        $sql="SELECT COUNT(XPPSinstallato) AS 'NUMERO'
        FROM user_clienti
        WHERE XPPSinstallato='si'";
        $rows=  $this->select($sql);
        $xpps=$rows[0]['NUMERO'];
        
        $sql="SELECT COUNT(JDOCinstallato) AS 'NUMERO'
        FROM user_clienti
        WHERE JDOCinstallato='si'";
        $rows=  $this->select($sql);
        $jdoc=$rows[0]['NUMERO'];
        
        $sql="SELECT COUNT(SCinstallato) AS 'NUMERO'
        FROM user_clienti
        WHERE SCinstallato='si'";
        $rows=  $this->select($sql);
        $sc=$rows[0]['NUMERO'];
        
        $sql="SELECT COUNT(giavisitato) AS 'NUMERO'
        FROM user_clienti
        WHERE giavisitato='si'";
        $rows=  $this->select($sql);
        $visitati=$rows[0]['NUMERO'];
        
        $sql="SELECT COUNT(giavisitato) AS 'NUMERO'
        FROM user_clienti
        WHERE giavisitato='no'";
        $rows=  $this->select($sql);
        $rimanenti=$rows[0]['NUMERO'];
        date_default_timezone_set('Europe/Berlin');
                    //calcolo la differenza di giorni tra il 31/03/13 e quella odierna
            $now = time(); // or your date as well
            $your_date = strtotime("2013-03-31");
            $dif=(($your_date-$now)/(60*60*24));
            //echo $dif.'<br>';
            $settimane=($dif)/7;
            //echo $settimane.'<br>';
            $mediadafare=$rimanenti/$settimane;
        
        $data=array(
          array('INSTALLAZIONI XDA','INSTALLAZIONI XPPS','INSTALLAZIONI JDOC','INSTALLAZIONI SC','CLIENTI VISITATI','DA VISITARE','MEDIA SETTIMANALE DA FARE'),
          array($xda,$xpps,$jdoc,$sc,$visitati,$rimanenti,$mediadafare)
        );
        
        $this->esportacsv_array($data);
    }
    
    public function esportacsv_array($array)
    {
        date_default_timezone_set('Europe/Berlin');
         /*$array = array(
         array('Last Name', 'First Name', 'Gender'),
         array('Furtado', 'Nelly', 'female'),
         array('Twain', 'Shania', 'female'),
         array('Farmer', 'Mylene', 'female')
                );*/       
        $newarray=  str_replace(",", ";", $array);
        $this->load->helper('csv');
        $this->load->helper('download');
        $csvtext=  str_replace(',', ';', array_to_csv($newarray)) ;
        $nomefile= 'file.csv';
        //echo $csvtext.'</br>';
        //echo $nomefile;
        force_download($nomefile, $csvtext);
    }
    
    public function view_esportacsv()
    {
        date_default_timezone_set('Europe/Berlin');
        $link = mysql_connect('127.0.0.1','JDoc','AgentSystem') or die('Could not connect: '.mysql_error());
        mysql_select_db('jdoc') or die('Could not select database: '.'jdoc');
        $query = "SELECT sys_user.firstname as 'NOME',sys_user.lastname as 'COGNOME',dalle AS DALLE,alle AS ALLE,user_clienti.nome AS 'CLIENTE',user_timesheet.note AS NOTE,servizio AS SERVIZIO,totore AS 'TOTALE ORE'
                  FROM (sys_user JOIN user_timesheet ON sys_user.id=user_timesheet.idutente) JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recordidaziende_
                  ORDER BY username"; 
        $result = mysql_query($query) or die("Error executing query: ".mysql_error());      
        $row = mysql_fetch_assoc($result);  
        $line = "";       
        $comma = "";       
        foreach($row as $name => $value)
        {
            $line .= $comma . '"' . str_replace('"', '""', $name) . '"';     
            $comma = ";"; 
        }
        $line .= "\n";
        $out = $line;
        mysql_data_seek($result, 0);
        while($row = mysql_fetch_assoc($result))
        {
            $line = "";
            $comma = "";
            foreach($row as $value)
            {   
                $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
                $comma = ";";
            }
            $line .= "\n";
            $out.=$line;
        }
        $nomefile='timesheet'.date("d/m/Y").'.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$nomefile");
        echo $out;
        exit;
    }
    
    public function timesheet_elimina($id,$interface='desktop')
    {
        date_default_timezone_set('Europe/Berlin');
        $idutente = $this->session->userdata('idutente');
        $sql="DELETE FROM user_timesheet
              WHERE recordid_='$id'";
        $this->execute_query($sql);
        $urlpartenza=$this->session->userdata('urlpartenza');
            if($urlpartenza=='view_timesheet_lista')
            {
                        $data1=$this->session->userdata('datapartenza');
                        $data2=$this->session->userdata('dataarrivo');
                        //ISTRUZIONI PER QUERY
                        $data['timesheets']=  $this->select("SELECT dalle,alle,user_timesheet.note,user_aziende.ragionesociale,username,user_timesheet.recordid_ AS ID,totore,servizio
                                   FROM (user_timesheet JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_) JOIN sys_user ON user_timesheet.idutente=sys_user.id
                                   WHERE dalle >='$data1' AND alle <='$data2' AND idutente=$idutente
                                   ORDER BY idutente,dalle");
                        //view($this, $interface, 'crm', 'timesheet_lista', null, $data);
                        $this->load->view('crm/'.$interface.'/content/timesheet_lista',$data);
            }
              if($urlpartenza=='view_timesheet_visualizza')
                $this->view_timesheet_visualizza ($interface);
              else
                $this->view_timesheet_inserisci ($interface);
    }
    
    public function view_timesheet_modifica($id,$interface='$desktop')
    {
        if($this->logged())
        {
            $this->session->set_userdata('idtimesheetscelto',$id);
            $sql="SELECT ragionesociale,servizio,user_timesheet.note,user_timesheet.recordid_ as id,user_aziende.recordid_ AS idcliente,datainizio,datafine,orainizio,orafine
                  FROM user_timesheet LEFT JOIN user_aziende ON user_timesheet.recordidaziende_=user_aziende.recordid_
                  WHERE user_timesheet.recordid_='$id'";
            $rows=$this->select($sql);
            $data['timesheet']=$rows[0];
            
            $data['clienti']=$this->select("SELECT recordid_,ragionesociale
                                            FROM user_aziende ORDER BY ragionesociale");
            //$interface='mobile';
            //$this->load->view($interface.'/timesheet_modifica',$data);
            //view($this, $interface, 'crm', 'timesheet_modifica', null, $data);
            //echo $url;
            $this->load->view('crm/'.$interface.'/content/timesheet_modifica',$data);
        }
        else
            $this->load->view($interface . '/login');
    }
    
    public function view_timesheet_visualizza ($interface='desktop')
    {
        if($this->logged()){
            $idutente=$this->session->userdata('idutente');
            $username=  $this->session->userdata('username');
            //$username='f.lanza';
            //$idutente=2;
            if(($username=='f.lanza')||($username=='m.prati')||($username=='p.beretta'))
                $rows = $this->select("SELECT * FROM sys_user ");
            else
                $rows = $this->select("SELECT * FROM sys_user WHERE username='$username'");
                foreach ($rows as $row)
                {
                    $utente['nome']=$row['lastname']." ".$row['firstname'];
                    $utente['id']=$row['id'];
                    $utenti[]=$utente;
                }
            $data['utenti']=$utenti;
            
            $sql="SELECT user_timesheet.recordid_,datainizio,datafine,orainizio,orafine,user_timesheet.note,user_aziende.ragionesociale,servizio,totore
                  FROM (user_timesheet LEFT JOIN user_aziende ON user_aziende.recordid_=user_timesheet.recordidaziende_)
                  WHERE idutente='$idutente' AND user_timesheet.stato ILIKE 'completato'
                  ORDER BY datainizio DESC,orainizio DESC
                  LIMIT 10 ";
            $rows=$this->select($sql);
            $data['timesheets']=$rows;
            $this->load->view('crm/'.$interface.'/content/timesheet_visualizza',$data);
        }
        else
            $this->load->view('crm/'.$interface.'/content/login_content',$data);
    }
    
    public function view_appuntamento_inserito($interface = 'desktop') {
        if ($this->logged()) {

            $data = $this->input->post();
            date_default_timezone_set('Europe/Berlin');
            $idcliente = $data['idcliente'];
            if ($data['annochiamata'] == "" || $data['giornochiamata'] == "" || $data['mesechiamata'] == "") {
                $dataappuntamento = "NULL";
            } else {
                $dataappuntamento = date("Y-m-d H:i:s", strtotime("" . $data['annochiamata'] . "-" . $data['mesechiamata'] . "-" . $data['giornochiamata'] . " " . $data['orechiamata'] . ":" . $data['minutichiamata'] . ":00"));
                $dataappuntamento = "'" . $dataappuntamento . "'";
            }
            if ($data['annoritorno'] == "" || $data['giornoritorno'] == "" || $data['meseritorno'] == "") {
                $dataritorno = "NULL";
            } else {
                $dataritorno = date("Y-m-d H:i:s", strtotime("" . $data['annoritorno'] . "-" . $data['meseritorno'] . "-" . $data['giornoritorno'] . " " . $data['oreritorno'] . ":" . $data['minutiritorno'] . ":00"));
                $dataritorno = "'" . $dataritorno . "'";
            }
            $idutente = $this->session->userdata('idutente');
            $sql = "INSERT INTO appuntamenti(
                idutente,
                idcliente,
                dataappuntamento,
                concluso,
                ritornare,
                dataritorno,
                emailxda,
                installazionexda,
                installazionexpps,
                installazionejdoc,
                installazionerifiutata,
                passatoconsulente,
                interessearchiviazione,
                notearchiviazione,
                responsabilearchiviazione,
                interessemobili,
                notemobili,
                responsabilemobili,
                interesseconsumabili,
                noteconsumabili,
                responsabileconsumabili,
                interessegenio,
                notegenio,
                responsabilegenio,
                note,
                idinseritoda
                ) 
                VALUES 
                (
                " . $data['idutente'] . ",
                " . $data['idcliente'] . ",
                " . $dataappuntamento . ",
                '" . $data['concluso'] . "',
                '" . $data['ritornare'] . "',
                " . $dataritorno . ",
                '" . $data['emailxda'] . "',
                '" . $data['installazionexda'] . "',
                '" . $data['installazionexpps'] . "',
                '" . $data['installazionejdoc'] . "',
                '" . $data['installazionerifiutata'] . "',
                '" . $data['passatoconsulente'] . "',
                '" . $data['interessearchiviazione'] . "',
                '" . addslashes($data['notearchiviazione']) . "',
                '" . addslashes($data['responsabilearchiviazione']) . "',
                '" . $data['interessemobili'] . "',
                '" . addslashes($data['notemobili']) . "',
                '" . addslashes($data['responsabilemobili']) . "',
                '" . $data['interesseconsumabili'] . "',
                '" . addslashes($data['noteconsumabili']) . "',
                '" . addslashes($data['responsabileconsumabili']) . "',
                '" . $data['interessegenio'] . "',
                '" . addslashes($data['notegenio']) . "',
                '" . addslashes($data['responsabilegenio']) . "',
                '" . addslashes($data['note']) . "',$idutente)";
            $this->execute_query($sql);
            

            //UPDATE CLIENTE
            $XDA = "";
            $XPPS = "";
            $JDOC = "";
            $interessearchiviazione = "";
            $interessemobili = "";
            $interesseconsumabili = "";
            $interessegenio = "";
            if ($data['installazionexda'] == 'si')
                $XDA = "XDAinstallato='" . $data['installazionexda'] . "',";
            if ($data['installazionexpps'] == 'si')
                $XPPS = "XPPSinstallato='" . $data['installazionexpps'] . "',";
            if ($data['installazionejdoc'] == 'si')
                $JDOC = "JDOCinstallato='" . $data['installazionejdoc'] . "',";
            if ($data['interessearchiviazione'] == 'si')
                $interessearchiviazione = "interessearchiviazione='" . $data['interessearchiviazione'] . "',";
            if ($data['interessemobili'] == 'si')
                $interessemobili = "interessemobili='" . $data['interessemobili'] . "',";
            if ($data['interessemobili'] == 'si')
                $interesseconsumabili = "interesseconsumabili='" . $data['interesseconsumabili'] . "',";
            if ($data['interessemobili'] == 'si')
                $interessegenio = "interessegenio='" . $data['interessegenio'] . "',";

            $sql = "UPDATE user_clienti 
                SET
                " . $XDA . "
                " . $XPPS . "
                " . $JDOC . "  
                " . $interessearchiviazione . "
                " . $interessemobili . "
                " . $interesseconsumabili . "  
                " . $interessegenio . "
                notearchiviazione=CONCAT(notearchiviazione,' " . addslashes($data['notearchiviazione']) . "'),
                
                notemobili=CONCAT(notemobili,' " . addslashes($data['notemobili']) . "'),
                
                noteconsumabili=CONCAT(noteconsumabili,' " . addslashes($data['noteconsumabili']) . "'),
                
                notegenio=CONCAT(notegenio,' " . addslashes($data['notegenio']) . "'),
                note=CONCAT(note,' " . addslashes($data['note']) . "')
                WHERE id=$idcliente";
            $this->execute_query($sql);
            
            if($data['concluso']=='si')
            {
                $sql="UPDATE user_clienti
                      SET giavisitato='si'
                      WHERE id=$idcliente";
                $this->execute_query($sql);
            }

            if ($data['ritornare'] == "si") {
                if ($data['annoritorno'] == "" || $data['giornoritorno'] == "" || $data['meseritorno'] == "") {
                    $dataappuntamento = "NULL";
                } else {
                    $dataappuntamento = date("Y-m-d H:i:s", strtotime("" . $data['annoritorno'] . "-" . $data['meseritorno'] . "-" . $data['giornoritorno'] . " " . $data['oreritorno'] . ":" . $data['minutiritorno'] . ":00"));
                    $dataappuntamento = "'" . $dataappuntamento . "'";
                }

                $concluso = 'no';

                $sql = "INSERT INTO appuntamenti(
                    idutente,
                    idcliente,
                    dataappuntamento,
                    concluso
                    ) 
                    VALUES 
                    (
                    " . $data['idutente'] . ",
                    " . $data['idcliente'] . ",
                    " . $dataappuntamento . ",
                    '$concluso'
                     )";
                $this->execute_query($sql);

            }
                $this->view_cliente_dettaglio($idcliente, $interface);
            } else {
                $this->load->view($interface . '/login');
            }
        }
    
    public function view_appuntamento_dettaglio($interface = 'desktop',$idappuntamento) {
            if ($this->logged())
            {
                //DETTAGLI APPUNTAMENTO
                $rows=  $this->select("SELECT * FROM appuntamenti WHERE id='$idappuntamento'");
                if(count($rows)>0)
                    $appuntamento=$rows[0];
                else
                    $appuntamento['errore'];
                $data['dettagliappuntamento']=$appuntamento;
                 //LISTA UTENTI
                $rows = $this->select("SELECT * FROM sys_user ");
                foreach ($rows as $row) {
                    $utente['nome']=$row['lastname']." ".$row['firstname'];
                    $utente['id']=$row['id'];
                    $utenti[]=$utente;
                }
                $data['utenti']=$utenti;
                //LISTA CLIENTI
                $sql = "SELECT * FROM user_clienti ";
                $rows = $this->select($sql);
                foreach ($rows as $row) {
                    $cliente['nome']=$row['nome'];
                    $cliente['id']=$row['id'];
                    $clienti[]=$cliente;
                }
                $data['clienti']=$clienti;
                $data['sys']['idutente']=$this->session->userdata('idutente');
                //view($this, $interface, 'crm', 'appuntamento_dettaglio', null, $data);
                //$this->load->view($interface . '/appuntamento_dettaglio',$data);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'appuntamento_dettaglio',
                  'content_data'=>$data,
                  'menu'=>'appuntamento_dettaglio_menu'
                );
            view_general($this, $arg);
        }
        else
            view($this, $interface, 'crm', 'login', null, null);
    }
    
      public function view_appuntamento_modifica($idappuntamento,$interface = 'desktop') {
        if ($this->logged())
            {
            //DETTAGLI APPUNTAMENTO
            $rows=  $this->select("SELECT * FROM appuntamenti WHERE id='$idappuntamento'");
            if(count($rows)>0)
            {
                $appuntamento=$rows[0];
            }
            else
                $appuntamento['errore'];
            $data['dettagliappuntamento']=$appuntamento;
            
             
             
             //LISTA UTENTI
            $rows = $this->select("SELECT * FROM sys_user ");
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            //LISTA CLIENTI
            $sql = "SELECT * FROM user_clienti ";
            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['id'];
                $clienti[]=$cliente;
            }
            
            $data['clienti']=$clienti;
            
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //$this->load->view($interface . '/appuntamento_modifica',$data);
            //view($this, $interface, 'crm', 'appuntamento_modifica', null, $data);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'appuntamento_modifica',
                  'content_data'=>$data,
                  'menu'=>'appuntamento_modifica_menu',
                  'backmenu'=>'view_appuntamenti_lista/'.$interface
                );
            view_general($this, $arg);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    public function view_appuntamento_elimina($idappuntamento,$interface='desktop')
    {
        $sql="DELETE FROM appuntamenti
              WHERE id=$idappuntamento";
        $this->execute_query($sql);
        $this->view_home($interface);
        //$this->load->view($interface.'/home');
    }
    public function view_cliente_elimina($idcliente,$interface='desktop')
    {
        $sql="DELETE FROM clienti
              WHERE id=$idcliente";
        $this->execute_query($sql);
        //ECHO "CLIENTE ELIMINATO CORRETTAMENTE";
        //$this->load->view($interface.'/home');
    }
        public function view_telefonata_elimina($idtelefonata,$interface='desktop')
    {
        $sql="DELETE FROM telefonate
              WHERE id=$idtelefonata";
        $this->execute_query($sql);
        //ECHO "TELEFONATA ELIMINATA CORRETTAMENTE";
        //sleep(5);
        //view($this, $interface, 'crm', 'home', null, null);
        $arg=array(
                'module'=>'crm',
                'interface'=>$interface,
                'menu'=>'home_menu',
                'content'=>'home_content'
            );
        view_general($this, $arg);
        //$this->load->view($interface.'/home');
    }
    public function view_cliente_modificato($interface = 'desktop')
    {
        if ($this->logged())
            {
           
            $data = $this->input->post();
            //dichiarazione variabili
            $nome=$data['nome'];
            $nome=addslashes($nome);
            $indirizzo=$data['indirizzo'];
            $indirizzo=addslashes($indirizzo);
            $cap=$data['cap'];
            $cap=addslashes($cap);
            $citta=$data['citta'];
            $citta=addslashes($citta);
            $telefono=$data['telefono'];
            $telefono=addslashes($telefono);
            $fax=$data['fax'];
            $fax=addslashes($fax);
            $email=$data['email'];
            $email=addslashes($email);
            $contatto=$data['contatto'];
            $contatto=addslashes($contatto);
            $note=$data['note'];
            $note=addslashes($note);
            $id=$data['id'];
            $notemobili=$data['notemobili'];
            $notemobili=  addslashes($notemobili);
            $notegenio=$data['notegenio'];
            $notegenio=  addslashes($notegenio);
            $notearchiviazione=$data['notearchiviazione'];
            $notearchiviazione=  addslashes($notearchiviazione);
            $noteconsumabili=$data['noteconsumabili'];
            $noteconsumabili=  addslashes($noteconsumabili);
            $giavisitato=$data['giavisitato'];
            $possiedemacchina=$data['possiedemacchina'];
            //if ($note==null)
                //$note=' ';
            $sql="UPDATE user_clienti
                  SET nome='$nome',
                  indirizzo='$indirizzo',
                  cap='$cap',
                  citta='$citta',
                  telefono='$telefono',
                  fax='$fax',
                  email='$email',
                  contatto='$contatto',
                  note='$note',
                  noteconsumabili='$noteconsumabili',
                  notearchiviazione='$notearchiviazione',
                  notegenio='$notegenio',
                  notemobili='$notemobili',
                  giavisitato='$giavisitato',
                  possiedemacchina='$possiedemacchina'
                  WHERE id='$id';";
            $this->execute_query($sql);
            //$interface='mobile';
            
          
            
            $this->view_cliente_dettaglio($id,$interface);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_appuntamento_modificato($idappuntamento,$interface = 'desktop')
    {
            $data = $this->input->post();
            $idutente=$this->session->userdata('idutente');
            $idcliente=$data['idcliente'];
            date_default_timezone_set('Europe/Berlin');
            if($data['annoappuntamento']==""||$data['giornoappuntamento']==""||$data['meseappuntamento']=="")
            {
             $dataappuntamento="NULL";   
            }
            else
            {
                $dataappuntamento=date("Y-m-d H:i:s", strtotime("".$data['annoappuntamento']."-".$data['meseappuntamento']."-".$data['giornoappuntamento']." ".$data['oreappuntamento'].":".$data['minutiappuntamento'].":00"));
                $dataappuntamento="'".$dataappuntamento."'";
            }
            //echo $dataappuntamento;
            $sql = "UPDATE appuntamenti
                SET
                idutente=".$data['idutente'].",
                idcliente=".$data['idcliente'].",
                dataappuntamento=".$dataappuntamento.",
                concluso='".$data['concluso']."',
                ritornare='".$data['ritornare']."',
                emailxda='".$data['emailxda']."',
                installazionexda='".$data['installazionexda']."',
                installazionexpps='".$data['installazionexpps']."',
                installazionejdoc='".$data['installazionejdoc']."',
                installazionerifiutata='".$data['installazionerifiutata']."',
                passatoconsulente='".$data['passatoconsulente']."',
                interessearchiviazione='".$data['interessearchiviazione']."',
                notearchiviazione='".  addslashes($data['notearchiviazione'])."',
                responsabilearchiviazione='".  addslashes($data['responsabilearchiviazione'])."',
                interessemobili='".$data['interessemobili']."',
                notemobili='".  addslashes($data['notemobili'])."',
                responsabilemobili='".  addslashes($data['responsabilemobili'])."',
                interesseconsumabili='".$data['interesseconsumabili']."',
                noteconsumabili='".  addslashes($data['noteconsumabili'])."',
                responsabileconsumabili='".  addslashes($data['responsabileconsumabili'])."',
                interessegenio='".$data['interessegenio']."',
                notegenio='".  addslashes($data['notegenio'])."',
                responsabilegenio='".  addslashes($data['responsabilegenio'])."',
                note='".  addslashes($data['note'])."',
                idinseritoda=".$idutente."
                WHERE id=$idappuntamento    
                ";
  
           $this->execute_query($sql);
            
            //UPDATE CLIENTE
            $XDA="";
            $XPPS="";
            $JDOC="";
            $interessearchiviazione="";
            $interessemobili="";
            $interesseconsumabili="";
            $interessegenio="";
            if($data['installazionexda']=='si')
                $XDA="XDAinstallato='".$data['installazionexda']."',";
            if($data['installazionexpps']=='si')
                $XPPS="XPPSinstallato='".$data['installazionexpps']."',";
            if($data['installazionejdoc']=='si')
                $JDOC="JDOCinstallato='".$data['installazionejdoc']."',";
            if($data['interessearchiviazione']=='si')
                $interessearchiviazione="interessearchiviazione='".$data['interessearchiviazione']."',";
            if($data['interessemobili']=='si')
                $interessemobili="interessemobili='".$data['interessemobili']."',";
            if($data['interessemobili']=='si')
                $interesseconsumabili="interesseconsumabili='".$data['interesseconsumabili']."',";
            if($data['interessemobili']=='si')
                $interessegenio="interessegenio='".$data['interessegenio']."',";
            
            $sql="UPDATE user_clienti 
                SET
                ".$XDA."
                ".$XPPS."
                ".$JDOC."  
                ".$interessearchiviazione."
                ".$interessemobili."
                ".$interesseconsumabili."  
                ".$interessegenio."
                notearchiviazione=CONCAT(notearchiviazione,' ".  addslashes($data['notearchiviazione'])."'),
                
                notemobili=CONCAT(notemobili,' ".  addslashes($data['notemobili'])."'),
                
                noteconsumabili=CONCAT(noteconsumabili,' ".  addslashes($data['noteconsumabili'])."'),
                
                notegenio=CONCAT(notegenio,' ".  addslashes($data['notegenio'])."'),
                note=CONCAT(note,' ".  addslashes($data['note'])."')
                WHERE id=$idcliente";
            $this->execute_query($sql);
            
            if($data['concluso']=='si')
            {
                $sql="UPDATE user_clienti
                      SET giavisitato='si'
                      WHERE id=$idcliente";
                $this->execute_query($sql);
            }
            
            if($data['ritornare']=="si")
            {
                if($data['annoritorno']==""||$data['giornoritorno']==""||$data['meseritorno']=="")
                {
                 $dataappuntamento="NULL";   
                }
                else
                {
                    $dataappuntamento=date("Y-m-d H:i:s", strtotime("".$data['annoritorno']."-".$data['meseritorno']."-".$data['giornoritorno']." ".$data['oreritorno'].":".$data['minutiritorno'].":00"));
                    $dataappuntamento="'".$dataappuntamento."'";
                }
                $concluso='no';
                $sql = "INSERT INTO appuntamenti(
                    idutente,
                    idcliente,
                    dataappuntamento,
                    concluso
                    ) 
                    VALUES 
                    (
                    ".$data['idutente'].",
                    ".$data['idcliente'].",
                    ".$dataappuntamento.",
                    '$concluso'
                     )";
           $this->execute_query($sql);
           //$this->view_appuntamento_dettaglio('tablet',1);
        }
        $this->view_appuntamento_dettaglio($interface,$idappuntamento);
    }

    public function view_telefonatexda_inserisci($interface = 'desktop',$idcliente=null) {
            $rows = $this->select("SELECT * FROM sys_user");
            //$data['utenti']=$this->Crm_model->get_lista_utenti();
            //$data['clienti']=$this->Crm_model->get_lista_clienti();
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            if($idcliente!=null)
               $sql = "SELECT * FROM user_clienti WHERE id=$idcliente";  
            else
                $sql = "SELECT * FROM user_clienti ";

            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['recordid_'];
                $clienti[]=$cliente;
            }
            
            $data['clienti']=$clienti;
            $data['idcliente']=$idcliente;
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //$this->load->view($interface . '/telefonatexda_inserisci',$data);
            //view($this, 'tablet', 'crm', 'telefonatexda_inserisci', null, $data);
               $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'telefonatexda_inserisci',
                  'content_data'=>$data,
                  'menu'=>'telefonatexda_inserisci_menu',
                  'backmenu'=>'view_telefonate_menu'
                );
            view_general($this, $arg);
    }
    
    public function view_telefonataxda_modifica($idtelefonata,$interface = 'desktop'){
        if ($this->logged())
            {
            
           // $sql = ;
            //$query = $this->db->query($sql);
           // $rows = $query->result();
           // 
            //DETTAGLI APPUNTAMENTO
            $rows=  $this->select("SELECT * FROM telefonate WHERE id='$idtelefonata'");
            if(count($rows)>0)
            {
                $telefonata=$rows[0];
            }
            else
                $telefonata['errore'];
            $data['dettaglitelefonata']=$telefonata;
            
             
             
             //LISTA UTENTI
            $rows = $this->select("SELECT * FROM sys_user ");
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            //LISTA CLIENTI
            $sql = "SELECT * FROM user_clienti ";
            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['id'];
                $clienti[]=$cliente;
            }
            
            $data['clienti']=$clienti;
            
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //view($this, $interface, 'crm', 'telefonataxda_modifica', null, $data);
            //$this->load->view($interface . '/telefonataxda_modifica',$data);
            $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'telefonataxda_modifica',
                  'content_data'=>$data,
                  'menu'=>'telefonataxda_modifica_menu',
                  'backmenu'=>'view_telefonate_lista/'.$interface
                );
            view_general($this, $arg);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_telefonataxda_modificato($idtelefonata,$interface='desktop')
    {
        
        if ($this->logged()) {
            
            $data = $this->input->post();
            date_default_timezone_set('Europe/Berlin');
            
            if($data['annochiamata']==""||$data['giornochiamata']==""||$data['mesechiamata']=="")
            {
                $datachiamata="NULL";   
            }
            else
            {
                $datachiamata=date("Y-m-d H:i:s", strtotime("".$data['annochiamata']."-".$data['mesechiamata']."-".$data['giornochiamata']." ".$data['orechiamata'].":".$data['minutichiamata'].":00"));
                $datachiamata="'".$datachiamata."'";
            }
            
            
            
            $idutente=$this->session->userdata('idutente');
            $sql = "UPDATE telefonate
                SET
                tipocontatto='Telefonata XDA',
                idcliente=".$data['idcliente'].",
                idoperatore=".$data['idoperatore'].",
                datachiamata=".$datachiamata.",
                chiamataeffettuata='".$data['conclusa']."',
                richiamano='".$data['richiamano']."',
                referenteinterno='".$data['referenteinterno']."',
                referenteesterno='".$data['referenteesterno']."',
                telerefesterno='".$data['telerefesterno']."',
                osserver='".$data['osserver']."',
                passwordserver='".$data['passwordserver']."',
                interessearchiviazione='".$data['interessearchiviazione']."',
                notearchiviazione='".  addslashes($data['notearchiviazione'])."',
                responsabilearchiviazione='".  addslashes($data['responsabilearchiviazione'])."',
                interessemobili='".$data['interessemobili']."',
                notemobili='".  addslashes($data['notemobili'])."',
                responsabilemobili='".  addslashes($data['responsabilemobili'])."',
                interesseconsumabili='".$data['interesseconsumabili']."',
                noteconsumabili='".  addslashes($data['noteconsumabili'])."',
                responsabileconsumabili='".  addslashes($data['responsabileconsumabili'])."',
                interessegenio='".$data['interessegenio']."',
                notegenio='".  addslashes($data['notegenio'])."',
                responsabilegenio='".  addslashes($data['responsabilegenio'])."',
                note='".  addslashes($data['note'])."',
                idinseritoda= $idutente,
                richiamano='".$data['richiamano']."'
                
                WHERE id=".$idtelefonata."";
                
  

            $this->execute_query($sql);
            
            
            
            if($data['appuntamento']=="si")
            {
                if($data['annoappuntamento']==""||$data['giornoappuntamento']==""||$data['meseappuntamento']=="")
                {
                 $dataappuntamento="NULL";   
                }
                else
                {
                    $dataappuntamento=date("Y-m-d H:i:s", strtotime("".$data['annoappuntamento']."-".$data['meseappuntamento']."-".$data['giornoappuntamento']." ".$data['oreappuntamento'].":".$data['minutiappuntamento'].":00"));
                    $dataappuntamento="'".$dataappuntamento."'";
                }

                $sql = "INSERT INTO appuntamenti(
                    idutente,
                    idcliente,
                    dataappuntamento,   
                    note,
                    idinseritoda
                    ) 
                    VALUES 
                    (
                    ".$data['idinstallatore'].",
                    ".$data['idcliente'].",
                    ".$dataappuntamento.",
                    '".  addslashes($data['noteappuntamento'])."',
                        $idutente)";
                echo "<br/>";

                $this->execute_query($sql);
            }
            
                        if($data['richiamare']=="si")
            {
                    if ($data['richiamata'] == "no" || $data['annorichiamata'] == "" || $data['giornorichiamata'] == "" || $data['meserichiamata'] == "") {
                        $datarichiamata = "NULL";
                    } 
                    else {
                        $datarichiamata = date("Y-m-d H:i:s", strtotime("" . $data['annorichiamata'] . "-" . $data['meserichiamata'] . "-" . $data['giornorichiamata'] . " " . $data['orerichiamata'] . ":" . $data['minutirichiamata'] . ":00"));
                        $datarichiamata = "'" . $datarichiamata . "'";
                    }

                $sql = "INSERT INTO telefonate(
                    idoperatore,
                    idcliente,
                    datachiamata,   
                    note,
                    idinseritoda
                    ) 
                    VALUES 
                    (
                    ".$idutente.",
                    ".$data['idcliente'].",
                    ".$datarichiamata.",
                    '".  addslashes($data['note'])."',
                    $idutente)";
                echo "<br/>";

                $this->execute_query($sql);
            }
            
            $idcliente=$data['idcliente'];
            
            //UPDATE CLIENTE
            /*$XDA="";
            $XPPS="";
            $JDOC="";*/
            $interessearchiviazione="";
            $interessemobili="";
            $interesseconsumabili="";
            $interessegenio="";
            /*if($data['installazionexda']=='si')
                $XDA="XDAinstallato='".$data['installazionexda']."',";
            if($data['installazionexpps']=='si')
                $XPPS="XPPSinstallato='".$data['installazionexpps']."',";
            if($data['installazionejdoc']=='si')
                $JDOC="JDOCinstallato='".$data['installazionejdoc']."',";
            if($data['interessearchiviazione']=='si')*/
                $interessearchiviazione="interessearchiviazione='".$data['interessearchiviazione']."',";
            if($data['interessemobili']=='si')
                $interessemobili="interessemobili='".$data['interessemobili']."',";
            if($data['interessemobili']=='si')
                $interesseconsumabili="interesseconsumabili='".$data['interesseconsumabili']."',";
            if($data['interessemobili']=='si')
                $interessegenio="interessegenio='".$data['interessegenio']."',";
            
            /*
             *                 ".$XDA."
                ".$XPPS."
                ".$JDOC." 
             */
            
            $sql="UPDATE user_clienti 
                SET 
                ".$interessearchiviazione."
                ".$interessemobili."
                ".$interesseconsumabili."  
                ".$interessegenio."
                notearchiviazione=CONCAT(notearchiviazione,' ".  addslashes($data['notearchiviazione'])."'),
                
                notemobili=CONCAT(notemobili,' ".  addslashes($data['notemobili'])."'),
                
                noteconsumabili=CONCAT(noteconsumabili,' ".  addslashes($data['noteconsumabili'])."'),
                
                notegenio=CONCAT(notegenio,' ".  addslashes($data['notegenio'])."'),
                note=CONCAT(note,' ".  addslashes($data['note'])."')
                WHERE id=$idcliente";
            $this->execute_query($sql);
            
     
           $this->view_cliente_dettaglio($data['idcliente'], $interface);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    public function view_cliente_modifica($idcliente,$interface = 'desktop')
    {
        if ($this->logged())
        {
            //$interface='mobile';
            $rows=  $this->select("SELECT * FROM user_clienti WHERE id='$idcliente'");
            if(count($rows)>0)
            {
                $cliente=$rows[0];
            }
            else
                $cliente['errore'];
            $data['dettaglicliente']=$cliente;
            //echo "sono entrato";
            //$this->load->view($interface . '/cliente_modifica',$data);
            $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'cliente_modifica',
                  'content_data'=>$data,
                  'menu'=>'cliente_modifica_menu',
                  'backmenu'=>'view_cliente_dettaglio/'.$this->session->userdata('idclientedettaglio')
                );
            view_general($this, $arg);
        } 
        else
        {
            $this->load->view($interface . '/login');
        }
    }
    
    
    public function view_telefonatatelemarketing_modifica($idtelefonata,$interface = 'desktop')
    {
                if ($this->logged())
            {
            
           // $sql = ;
            //$query = $this->db->query($sql);
           // $rows = $query->result();
           // 
            //DETTAGLI TELEFONATA
            $rows=  $this->select("SELECT * FROM telefonate WHERE id='$idtelefonata'");
            if(count($rows)>0)
            {
                $telefonata=$rows[0];
            }
            else
                $telefonata['errore'];
            $data['dettaglitelefonata']=$telefonata;
            
             
             
             //LISTA UTENTI
            $rows = $this->select("SELECT *
                                   FROM sys_user JOIN telefonate ON telefonate.idoperatore = sys_user.id
                                   WHERE telefonate.id=$idtelefonata");
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            //LISTA CLIENTI
            $sql = "SELECT *
                    FROM user_clienti JOIN telefonate ON user_clienti.id=telefonate.idcliente
                    WHERE telefonate.id=$idtelefonata";
            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['id'];
                $clienti[]=$cliente;
            }
            
            $data['clienti']=$clienti;
            $data['idtelefonata']=$idtelefonata;
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //echo "sono qui";
            //$interface='tablet';
            //view($this, $interface, 'crm', 'telefonatatelemarketing_modifica', null, $data);
            //$this->load->view($interface . '/telefonatatelemarketing_modifica',$data);
            $arg=array(
                'module'=>'crm',
                'interface'=>$interface,
                'backmenu'=>'view_telefonate_menu',
                'menu'=>'telefonatetelemarketing_modifica_menu',
                'content'=>'telefonatatelemarketing_modifica',
                'content_data'=>$data
            );
            view_general($this, $arg);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_telefonatatelemarketing_modificato($interface='desktop')
    {
        if ($this->logged())
        {
            $idtelefonata= $this->session->userdata('idtelefonata');
            $data = $this->input->post();//raccolgo i dati dal post
            $note=$data['note'];
             date_default_timezone_set('Europe/Berlin');
            
            
            if($data['annochiamata']==""||$data['giornochiamata']==""||$data['mesechiamata']=="")
            {
             $datachiamata="NULL";   
            }
            else
            {
                $datachiamata=date("Y-m-d H:i:s", strtotime("".$data['annochiamata']."-".$data['mesechiamata']."-".$data['giornochiamata']." ".$data['orechiamata'].":".$data['minutichiamata'].":00"));
                $datachiamata="'".$datachiamata."'";
            }
            //echo $datachiamata;
            $note=addslashes($note);//evito che mi generi problemi l'operazione di insert facendo l'addslashes
            
            $sql="UPDATE telefonate
                  SET datachiamata=$datachiamata,
                      trasloco='".$data['trasloco']."',
                      rinnovomobilio='".$data['rinnovomobilio']."',
                      rinnovosedute='".$data['rinnovosedute']."',
                      interessedocsedute='".$data['interessedocsedute']."',
                      interesseparetidivisorie='".$data['interesseparetidivisorie']."',
                      dacontattarearredo='".$data['dacontattarearredo']."',
                      contrattiinscadenza='".$data['contrattiinscadenza']."',
                      interessefotocopiatrice='".$data['interessefotocopiatrice']."',
                      interessemultifunzione='".$data['interessemultifunzione']."',
                      dacontattarexerox='".$data['dacontattarexerox']."',
                      interesseconsumabili='".$data['interessetoner']."',
                      interessecarta='".$data['interessecarta']."',
                      dacontattareoffice='".$data['dacontattareoffice']."',
                      dacontattaresoftware='".$data['dacontattaresoftware']."',
                      note='$note'
                  WHERE id='$idtelefonata';";
            
            $this->execute_query($sql);
            //echo"elaborazione terminata";
            $this->view_telefonata_dettaglio($interface,$idtelefonata);
        }
        else
            $this->load->view($interface . '/login');
    }
    
    public function view_telefonataxda_inserito($interface = 'desktop') {
        if ($this->logged())
        {
            
            $data = $this->input->post();
            date_default_timezone_set('Europe/Berlin');
            
            if($data['annochiamata']==""||$data['giornochiamata']==""||$data['mesechiamata']=="")
            {
             $datachiamata="NULL";   
            }
            else
            {
                $datachiamata=date("Y-m-d H:i:s", strtotime("".$data['annochiamata']."-".$data['mesechiamata']."-".$data['giornochiamata']." ".$data['orechiamata'].":".$data['minutichiamata'].":00"));
                $datachiamata="'".$datachiamata."'";
            }
            if($data['annorichiamata']==""||$data['giornorichiamata']==""||$data['meserichiamata']=="")
            {
                $datarichiamata="NULL";   
            }
            else
            {
                $datarichiamata=date("Y-m-d H:i:s", strtotime("".$data['annorichiamata']."-".$data['meserichiamata']."-".$data['giornorichiamata']." ".$data['orerichiamata'].":".$data['minutirichiamata'].":00"));
                $datarichiamata="'".$datarichiamata."'";
            }
            
            
            $idutente=$this->session->userdata('idutente');
            $sql = "INSERT INTO telefonate(
                tipocontatto,
                datarichiamare,
                idcliente,
                idoperatore,
                datachiamata,
                chiamataeffettuata,
                richiamano,
                referenteinterno,
                referenteesterno,
                telerefesterno,
                osserver,
                passwordserver,
                interessearchiviazione,
                notearchiviazione,
                responsabilearchiviazione,
                interessemobili,
                notemobili,
                responsabilemobili,
                interesseconsumabili,
                noteconsumabili,
                responsabileconsumabili,
                interessegenio,
                notegenio,
                responsabilegenio,
                note,
                idinseritoda
                ) 
                VALUES 
                (
                'Telefonata XDA',
                ".$datarichiamata.",
                ".$data['idcliente'].",
                ".$data['idoperatore'].",
                ".$datachiamata.",
                '".$data['conclusa']."',
                '".$data['richiamano']."',
                '".$data['referenteinterno']."',
                '".$data['referenteesterno']."',
                '".$data['telerefesterno']."',
                '".$data['osserver']."',
                '".$data['passwordserver']."',
                '".$data['interessearchiviazione']."',
                '".  addslashes($data['notearchiviazione'])."',
                '".  addslashes($data['responsabilearchiviazione'])."',
                '".$data['interessemobili']."',
                '".  addslashes($data['notemobili'])."',
                '".  addslashes($data['responsabilemobili'])."',
                '".$data['interesseconsumabili']."',
                '".  addslashes($data['noteconsumabili'])."',
                '".  addslashes($data['responsabileconsumabili'])."',
                '".$data['interessegenio']."',
                '".  addslashes($data['notegenio'])."',
                '".  addslashes($data['responsabilegenio'])."',
                '".  addslashes($data['note'])."',
                $idutente)";
            
            $this->execute_query($sql);
            
            if($data['appuntamento']=="si")
            {
                if($data['annoappuntamento']==""||$data['giornoappuntamento']==""||$data['meseappuntamento']=="")
                {
                 $dataappuntamento="NULL";   
                }
                else
                {
                    $dataappuntamento=date("Y-m-d H:i:s", strtotime("".$data['annoappuntamento']."-".$data['meseappuntamento']."-".$data['giornoappuntamento']." ".$data['oreappuntamento'].":".$data['minutiappuntamento'].":00"));
                    $dataappuntamento="'".$dataappuntamento."'";
                }

                $sql = "INSERT INTO appuntamenti(
                    tipocontatto,
                    idutente,
                    idcliente,
                    dataappuntamento,   
                    note,
                    idinseritoda
                    ) 
                    VALUES 
                    (
                    Telefonata XDA,
                    ".$data['idinstallatore'].",
                    ".$data['idcliente'].",
                    ".$dataappuntamento.",
                    '".  addslashes($data['noteappuntamento'])."',
                        $idutente)";
                echo "<br/>";

                $this->execute_query($sql);
            }
            
            if($data['richiamare']=="si")
            {
                    if ($data['richiamata'] == "no" || $data['annorichiamata'] == "" || $data['giornorichiamata'] == "" || $data['meserichiamata'] == "") {
                        $datarichiamata = "NULL";
                    } 
                    else {
                        $datarichiamata = date("Y-m-d H:i:s", strtotime("" . $data['annorichiamata'] . "-" . $data['meserichiamata'] . "-" . $data['giornorichiamata'] . " " . $data['orerichiamata'] . ":" . $data['minutirichiamata'] . ":00"));
                        $datarichiamata = "'" . $datarichiamata . "'";
                    }

                $sql = "INSERT INTO telefonate(
                    idoperatore,
                    idcliente,
                    datachiamata,   
                    note,
                    idinseritoda
                    ) 
                    VALUES 
                    (
                    ".$idutente.",
                    ".$data['idcliente'].",
                    ".$datarichiamata.",
                    '".  addslashes($data['note'])."',
                    $idutente)";
                echo "<br/>";

                $this->execute_query($sql);
            }
            
            $idcliente=$data['idcliente'];
            
            //UPDATE CLIENTE
            /*$XDA="";
            $XPPS="";
            $JDOC="";*/
            $interessearchiviazione="";
            $interessemobili="";
            $interesseconsumabili="";
            $interessegenio="";
            /*if($data['installazionexda']=='si')
                $XDA="XDAinstallato='".$data['installazionexda']."',";
            if($data['installazionexpps']=='si')
                $XPPS="XPPSinstallato='".$data['installazionexpps']."',";
            if($data['installazionejdoc']=='si')
                $JDOC="JDOCinstallato='".$data['installazionejdoc']."',";*/
            if($data['interessearchiviazione']=='si')
                $interessearchiviazione="interessearchiviazione='".$data['interessearchiviazione']."',";
            if($data['interessemobili']=='si')
                $interessemobili="interessemobili='".$data['interessemobili']."',";
            if($data['interessemobili']=='si')
                $interesseconsumabili="interesseconsumabili='".$data['interesseconsumabili']."',";
            if($data['interessemobili']=='si')
                $interessegenio="interessegenio='".$data['interessegenio']."',";
            

            $sql="UPDATE user_clienti 
                SET
                ".$interessearchiviazione."
                ".$interessemobili."
                ".$interesseconsumabili."  
                ".$interessegenio."
                notearchiviazione=CONCAT(notearchiviazione,' ".  addslashes($data['notearchiviazione'])."'),
                
                notemobili=CONCAT(notemobili,' ".  addslashes($data['notemobili'])."'),
                
                noteconsumabili=CONCAT(noteconsumabili,' ".  addslashes($data['noteconsumabili'])."'),
                
                notegenio=CONCAT(notegenio,' ".  addslashes($data['notegenio'])."'),
                note=CONCAT(note,' ".  addslashes($data['note'])."')
                WHERE recordid_=$idcliente";
            $this->execute_query($sql);
            
           /*    $arg=array(
                  'module'=>'crm',
                  'interface'=>'tablet',
                  'content'=>'cliente_dettaglio',
                  'content_data'=>$data
                );
            view_general($this, $arg);*/
           $this->view_cliente_dettaglio($data['idcliente'], $interface);
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_telefonatetelemarketing_inserisci($interface = 'desktop',$idcliente=null) {
            $rows = $this->select("SELECT * FROM sys_user ");
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            
            if($idcliente!=null)
            {
               $sql = "SELECT * FROM user_clienti WHERE id=$idcliente";  
            }
 else {
            $sql = "SELECT * FROM user_clienti ";
 }

            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['id'];
                $clienti[]=$cliente;
            }
            
            $data['clienti']=$clienti;
            $data['idcliente']=$idcliente;
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //view($this, 'tablet', 'crm', 'telefonatetelemarketing_inserisci', null, $data);
            //$this->load->view($interface . '/telefonatetelemarketing_inserisci',$data);
            $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'telefonatetelemarketing_inserisci',
                  'content_data'=>$data,
                  'menu'=>'telefonatetelemarketing_inserisci_menu',
                  'backmenu'=>'view_telefonate_menu'
                );
            view_general($this, $arg);
    }
    
    public function view_telefonatatelemarketing_inserito($interface = 'desktop'){
            
            $data = $this->input->post();
            date_default_timezone_set('Europe/Berlin');
            
            if($data['annochiamata']==""||$data['giornochiamata']==""||$data['mesechiamata']=="")
            {
             $datachiamata="NULL";   
            }
            else
            {
                $datachiamata=date("Y-m-d H:i:s", strtotime("".$data['annochiamata']."-".$data['mesechiamata']."-".$data['giornochiamata']." ".$data['orechiamata'].":".$data['minutichiamata'].":00"));
                $datachiamata="'".$datachiamata."'";
            }
            
            $idutente=$this->session->userdata('idutente');
            $sql = "INSERT INTO telefonate(
                idoperatore,
                idcliente,
                datachiamata,
                trasloco,
                rinnovomobilio,
                rinnovosedute,
                interessedocsedute,
                interesseparetidivisorie,
                dacontattarearredo,
                contrattiinscadenza,
                interessefotocopiatrice,
                interessemultifunzione,
                dacontattarexerox,
                interessetoner,
                interessecarta,
                dacontattareoffice,
                dacontattaresoftware,
                note,
                tipocontatto
                ) 
                VALUES 
                (
                ".$data['idoperatore'].",
                ".$data['idcliente'].",
                ".$datachiamata.",
                '".$data['trasloco']."',
                '".$data['rinnovomobilio']."',
                '".$data['rinnovosedute']."',
                '".$data['interessedocsedute']."',
                '".$data['interesseparetidivisorie']."',
                '".$data['dacontattarearredo']."',
                '".$data['contrattiinscadenza']."',
                '".$data['interessefotocopiatrice']."',
                '".  addslashes($data['interessemultifunzione'])."',
                '".  addslashes($data['dacontattarexerox'])."',
                '".$data['interessetoner']."',
                '".  addslashes($data['interessecarta'])."',
                '".  addslashes($data['dacontattareoffice'])."',
                '".$data['dacontattaresoftware']."',
                '".  addslashes($data['note'])."',
                'Telefonata Telemarketing'
                    )";
            
            $this->execute_query($sql);
            //$interface='tablet';
            $this->view_cliente_dettaglio($data['idcliente'], $interface);
    }

    public function view_telefonate_lista($interface = 'desktop') {
            $idutente=$this->session->userdata('idutente');
            $sql="SELECT user_clienti.nome AS nomecliente, telefono, telefonate.id AS idtelefonate,datachiamata, tipocontatto
                FROM user_clienti JOIN telefonate ON user_clienti.id=telefonate.idcliente
                 WHERE telefonate.idoperatore=$idutente AND chiamataeffettuata='no'";
            $rows=$this->SELECT($sql);
            
                foreach($rows as $row)
                    $data['telefonate']=$rows;
            
             $sql="SELECT user_clienti.nome AS nomecliente, telefono, telefonate.id AS idtelefonate
                   FROM user_clienti JOIN telefonate ON user_clienti.id=telefonate.idcliente
                   WHERE telefonate.idoperatore=$idutente AND telefonate.richiamano='si'";
             $rows=  $this->select($sql);
             
                foreach ($rows as $row)
                    $data['chiamano']=$rows;
            //view($this,'tablet','crm','telefonate_lista', null, $data);
            //$this->load->view($interface . '/telefonate_lista',$data);
               $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'telefonate_lista',
                  'content_data'=>$data,
                  'menu'=>'telefonate_lista_menu',
                  'backmenu'=>'view_telefonate_menu/'.$interface
                );
            view_general($this, $arg);
    }

    public function view_telefonata_dettaglio($interface = 'desktop',$idtelefonata) {          
           // $sql = ;
            //$query = $this->db->query($sql);
           // $rows = $query->result();
           // 
            //DETTAGLI TELEFONATA
            $rows=  $this->select("SELECT * FROM telefonate WHERE id='$idtelefonata'");
            if(count($rows)>0)
            {
                $telefonata=$rows[0];
            }
            else
                $telefonata['errore'];
            $data['dettaglitelefonata']=$telefonata;
             //LISTA UTENTI
            $rows = $this->select("SELECT *
                                   FROM sys_user JOIN telefonate ON telefonate.idoperatore = sys_user.id
                                   WHERE telefonate.id=$idtelefonata");
            foreach ($rows as $row) {
                $utente['nome']=$row['lastname']." ".$row['firstname'];
                $utente['id']=$row['id'];
                $utenti[]=$utente;
            }
            $data['utenti']=$utenti;
            //LISTA CLIENTI
            $sql = "SELECT user_clienti.id AS 'idcliente',user_clienti.nome as 'nome'
                    FROM user_clienti JOIN telefonate ON user_clienti.id=telefonate.idcliente
                    WHERE telefonate.id=$idtelefonata";
            $rows = $this->select($sql);
            foreach ($rows as $row) {
                $cliente['nome']=$row['nome'];
                $cliente['id']=$row['idcliente'];
                $clienti[]=$cliente;
            }
            $data['clienti']=$clienti;
            $data['idtelefonata']=$idtelefonata;
            $data['sys']['idutente']=$this->session->userdata('idutente');
            //var_dump($data['clienti']);
            //echo "sono qui";
            //$interface='tablet';
            //view($this, $interface, 'crm', 'telefonata_dettaglio', null, $data);
            //$this->load->view($interface . '/telefonata_dettaglio',$data);
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'telefonata_dettaglio',
                  'content_data'=>$data,
                  'menu'=>'telefonata_dettaglio_menu'
                );
            view_general($this, $arg);
    }

    public function view_clienti_lista($ordine,$interface = 'desktop') {
            if($ordine=="citta")
            {
                $ordine="citta,indirizzo";
            }
            if($this->session->userdata('username')=='s.taborelli')
                $sql = "SELECT * FROM user_clienti WHERE possiedemacchina <>'no' ORDER BY $ordine";
            else
                $sql = "SELECT * FROM user_clienti ORDER BY $ordine";
            $rows = $this->select($sql);
            $clienti = null;
            $cliente = null;
            foreach ($rows as $row) {
                $cliente["nome"] = $row["nome"];
                $cliente["citta"] = $row["citta"];
                $cliente["notegenio"]=$row["notegenio"];
                $cliente['noteconsumabili']=$row['noteconsumabili'];
                $cliente['notearchiviazione']=$row['notearchiviazione'];
                $cliente['notemobili']=$row['notemobili'];
                $idcliente=$row['id'];
                //$sql = "SELECT * FROM appuntamenti where idcliente='$idcliente'";
                //$righe = $this->select($sql);
                    $cliente["XDAinstallato"]=$row["xdainstallato"];
                    $cliente["XPPSinstallato"]=$row["xppsinstallato"];
                    $cliente["JDOCinstallato"]=$row["jdocinstallato"];
                    $cliente["scinstallato"]=$row["scinstallato"];
                    
                $cliente['giavisitato']=$row['giavisitato'];
                $cliente["id"]=$row["id"];
                $cliente["note"]=$row["note"];
                $cliente["indirizzo"]=$row["indirizzo"];
                $cliente["dacontattare"]=$row["dacontattare"];
                $clienti[] = $cliente;
                
                if(($cliente['XDAinstallato']!='')||($cliente['XPPSinstallato']!='')||($cliente['JDOCinstallato']!='')||($cliente['scinstallato']!='')||($cliente['note']!=''))
                    $cliente['giavisitato']='no';
            }
            $data['clienti'] = $clienti;
            $data['sys']['ordine']=$ordine;
            
            $arg=array(
                'module'=>'crm',
                'interface'=>$interface,
                'extraheader'=>'clienti_lista_extraheader',
                'backmenu'=>'view_home',
                'menu'=>'clienti_lista_menu',
                'content'=>'clienti_lista_content',
                'content_data'=>$data
            );
            view_general($this, $arg);
            //view($this, 'tablet', 'crm', 'clienti_lista_veloce',null,null,$data);
    }
    
    public function view_clienti_inserisci($interface = 'desktop') {
        if ($this->logged()) {
            $arg=array(
                'module'=>'crm',
                'interface'=>$interface,
                'backmenu'=>'view_clienti_lista/nome',
                'menu'=>'clienti_inserisci_menu',
                'content'=>'clienti_inserisci'
            );
            view_general($this, $arg);
            //$this->load->view($interface . '/clienti_inserisci');
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function view_cliente_inserito($interface = 'desktop') {
        if ($this->logged()) {
           
            $data = $this->input->post();
            $idcliente= $this->execute_query("
                INSERT INTO user_clienti
                (nome,
                idlogico,
                indirizzo,
                cap,
                citta,
                telefono,
                fax,
                email,
                contatto,
                note) 
                VALUES 
                ('".addslashes($data['nome'])."',
                '".addslashes($data['idlogico'])."',
                '".addslashes($data['indirizzo'])."',
                '".  addslashes($data['cap'])."',
                '".  addslashes($data['citta'])."',
                '".  addslashes($data['telefono'])."',
                '".  addslashes($data['fax'])."',
                '".  addslashes($data['email'])."',
                '".  addslashes($data['contatto'])."',
                '".  addslashes($data['note'])."'
                )");
            $this->view_cliente_dettaglio($idcliente, $interface);
        } else {
            $this->load->view($interface . '/login');
        }
    }

    public function view_cliente_dettaglio($id,$interface = 'desktop')
    {     
            $this->session->set_userdata('idclientedettaglio',$id);
            $rows = $this->select("SELECT * FROM user_clienti  WHERE id='$id'");
            $clienti=null;
            foreach ($rows as $row)
            {
             $cliente=$row;
             
             $clienti[]=$cliente;
            }
            $cliente=$clienti[0];
            $data['daticliente']=$cliente;
            
            //appuntamenti cliente
            $idcliente=$cliente['id'];
                $sql = "SELECT * FROM appuntamenti  WHERE idcliente='$id'";
            $rows = $this->select($sql);
            $appuntamenti=null;
            foreach ($rows as $row)
            {
             $appuntamento=$row;
             $appuntamenti[]=$appuntamento;
            }
            $data['appuntamenticliente']=$appuntamenti;
            $sql = "SELECT * FROM telefonate  WHERE idcliente='$id'";
            $rows = $this->select($sql);
            $telefonate=null;
            foreach ($rows as $row)
            {
             $telefonata=$row;
             $telefonate[]=$telefonata;
            }
            $data['telefonatexdacliente']=$telefonate;
            
             $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'cliente_dettaglio',
                  'content_data'=>$data,
                  'backmenu'=>'view_clienti_lista/nome',
                  'menu'=>'cliente_dettaglio_menu'
                );
            view_general($this, $arg);
    }
    
     public function view_analisidati_menu($interface = 'desktop') {
        if ($this->logged())
        {
            
            $this->load->view($interface . '/analisidati_menu');
        } 
        else
        {
            $this->load->view($interface . '/login');
        }
    }
    public function view_analisi_installazioni($interface='desktop')
    {
            $query="SELECT nome,notearchiviazione,notemobili,noteconsumabili,notegenio FROM user_clienti WHERE 1=1 ";//la variabile mi servirà come query del telemarketing
            $form=null;//questa è la variabile che contiene i checkbox provenienti dalla pagina
            date_default_timezone_set('Europe/Berlin');
            //query numero installazioni XDA
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE xdainstallato='si'";
            $rows=$this->select($sql);
            $numinstallxda=$rows[0]['NUMERO'];
            $data['numinstallxda']=$numinstallxda;
            //query per numero installazioni xpps
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE xppsinstallato='si'";
            $rows= $this->select($sql);
            $numinstallxpps=$rows[0]['NUMERO'];
            $data['numinstallxpps']=$numinstallxpps;
            //query per numero installazioni JDoc
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE jdocinstallato='si'";
            $rows=  $this->select($sql);
            $numinstalljdoc=$rows[0]['NUMERO'];
            $data['numinstalljdoc']=$numinstalljdoc;
            //query per numero installazioni SC
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE scinstallato='si'";
            $rows=  $this->select($sql);
            $numinstallsc=$rows[0]['NUMERO'];
            $data['numinstallsc']=$numinstallsc;
            //--------------------------------------------------------------
            //query numero di clienti senza installazioni precedenti
            //query numero senza installazioni XDA
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE xdainstallato='no'";
            $rows=$this->select($sql);
            $numinstallxdano=$rows[0]['NUMERO'];
            $data['numinstallxdano']=$numinstallxdano;
            //query per numero senza installazioni xpps
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE xppsinstallato='no'";
            $rows=  $this->select($sql);
            $numinstallxppsno= $rows[0]['NUMERO'];
            $data['numinstallxppsno']=$numinstallxppsno;
            //query per numero senza installazioni JDoc
            $sql="SELECT COUNT(*) AS NUMERO
                  FROM user_clienti
                  WHERE jdocinstallato='no'";
            $rows=  $this->select($sql);
            $numinstalljdocno=$rows[0]['NUMERO'];
            $data['numinstalljdocno']=$numinstalljdocno;
            //query per numero installazioni SC
            $sql="SELECT COUNT(*)AS NUMERO
                  FROM user_clienti
                  WHERE scinstallato='no'";
            $rows=$this->select($sql);
            $numinstallscno= $rows[0]['NUMERO'];
            $data['numinstallscno']=$numinstallscno;
    
            
            //---------------------------------------------
            //query per clienti da cui non si è mai andati
            $sql="  SELECT COUNT(*) AS NUMERO
                    FROM user_clienti
                    WHERE giavisitato!='si'";
            $rows= $this->select($sql);
            $clientivuoti=$rows[0]['NUMERO'];
            $data['clientivuoti']=$clientivuoti;
            
            //---------------query per l'ultimo aggiornamento---------------------//
            $sql="SELECT MAX(timestampo) AS MASSIMO
                  FROM appuntamenti";
            $rows=  $this->select($sql);
            $timestamp=$rows[0]['MASSIMO'];
            $data['timestamp']=$timestamp;
            //query per il numero di clienti visitati
            $sql="SELECT COUNT(*) AS VISITATI
            FROM user_clienti
            WHERE giavisitato='si'";
            
            $rows=$this->select($sql);
            $visitati=$rows[0]['VISITATI'];
            $data['visitati']=$visitati;
            //calcolo la differenza di giorni tra il 31/03/13 e quella odierna
            $now = time(); // or your date as well
            $your_date = strtotime("2013-03-31");
            $dif=(($your_date-$now)/(60*60*24));
            //echo $dif.'<br>';
            $settimane=($dif)/7;
            //echo $settimane.'<br>';
            $data['media']=$clientivuoti/$settimane;
            $data['timeend']=$your_date;
            //echo $data['media'];                //echo floor($datediff/(60*60*24));
            //view($this, 'tablet', 'crm', 'analisi_installazioni', null, $data);
            //$this->load->view($interface . '/analisi_installazioni',$data);
            
            //------------INIZIO CODICE PER TELEMARKETING
            $form=$this->input->post();
            if($form['archiviazione'])
                $query.="AND interessearchiviazione='si' ";
            if($form['mobili'])
                $query.="AND interessemobili='si' ";
            if($form['consumabili'])
                $query.="AND interesseconsumabili='si' ";
            if($form['genio'])
                $query.="AND interessegenio='si' ";
            $query.="ORDER BY nome";
            $data['interessati']=$this->select($query);
            
            if($form==null)
                $data['interessati']=null;
            //------------FINE CODICE PER TELEMARKETING
            
                $arg=array(
                  'module'=>'crm',
                  'interface'=>$interface,
                  'content'=>'analisi_installazioni',
                  'content_data'=>$data,
                  'menu'=>'analisi_installazioni_menu'
                  
                );
            view_general($this, $arg);
    }
    private function select($sql)
    {
        $query = $this->db->query($sql);
        $rows = $query->result();
        $rows_return=array();
        foreach ($rows as $row) {
            $row=(array)$row;
            $rows_return[]=$row;
        }
        return $rows_return;
    }
    
    private function execute_query($sql)
    {
       $query = $this->db->query($sql);
         if($query==true) 
         {
            $return = "true";
         }
         else
         {
             $return="false";
         }
         return $return;
    }

    private function view_url($url)
    {
       if ($this->logged()) {
            $this->load->view($interface . '/$url');
        } else {
            $this->load->view($interface . '/login');
        }
    }
    
    public function testajax(){
        echo 'test controllore ajax';
    }
    /*function diff_date_giorni($data1,$data2){

	//$data1 e $data2 in formato Y-m-d (1979-12-16), se vuote '' prende valore di oggi
	if(empty($data1)) $data1 = date('Y-m-d');
	if(empty($data2)) $data2 = date('Y-m-d');

	$a_1 = explode('-',$data1);
	$a_2 = explode('-',$data2);
	$mktime1 = mktime(0, 0, 0, $a_1[1], $a_1[2], $a_1[0]);
	$mktime2 = mktime(0, 0, 0, $a_2[1], $a_2[2], $a_2[0]);
	$secondi = $mktime1 - $mktime2;
	$giorni = intval($secondi / 86400); /*ovvero (24ore*60minuti*60seconi)*/
	//return ($giorni);




    public function test(){
        $this->load->view('mobile/test');
    }
    
    public function invia_mail_moreno()
    {
        require ('PHPMailer-master/class.phpmailer.php');
        require "PHPMailer-master/PHPMailerAutoload.php";
        $email = new PHPMailer();
        $email->isSMTP();
        $email->SMTPDebug = 0;
        $email->SMTPAuth = true;
        $email->SMTPSecure='ssl';
        $email->Host="smtp.gmail.com";
        $email->Port = 465;
        $email->Username="l.giordano.aboutx@gmail.com";
        $email->Password = "LGaboutx2013";
        $email->SetFrom("l.giordano.aboutx@gmail.com", "Luca Giordano");
        $email->Subject = "Timesheet giornaliero automatico";
        $email->Body = "Corpo del messaggio";
        $email->isHTML(true);
        $email->addAddress("l.giordano@about-x.com");
        if(!$email->Send()) {
            $error = 'errore mail: '.$email->ErrorInfo;
            return false;
            
        } else {
            $error = 'Messaggio inviato!';
            return true;
        }
    }
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
