<?php

class Script_controller extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function add_lookup_settori()
    {
        $this->Sys_model->add_lookup_settori();
        echo 'settori caricati';
    }
    
    function add_lookup_subsettori()
    {
        $this->Sys_model->add_lookup_subsettori();
        echo 'settori caricati';
    }
    
    function copy_mittentidestinatari()
    {
        $this->Script_model->copy_mittentidestinatari();
    }
    
    
    function google_calendar_sync()
    {
        $data=array();
        $data['calendarId'] = 'en716e44gssets8l0ukc87aheg@group.calendar.google.com';
        $data['start_date']="2016-11-16";
        $data['start_time']="08:00:00";
        $data['end_date']="2016-11-16";
        $data['end_time']="12:00:00";
        $data['titolo']='test caricamento calendario da php';
        $data['descrizione']='Descrizione test';
        echo $this->load->view('google_api/GCServiceAccount',$data,true);
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
    
    function reload_id_iscrizioni()
    {
        $this->Sys_model->script_reload_id_iscrizioni();
    }
    
    
    function affitto_di()
    {
        $this->Sys_model->affitto_di();
    }
    
    function migrazione_prodotti_multifunzione()
    {
        $this->Sys_model->script_migrazione_prodotti_multifunzione();
    }
    
    function migrazione_prodotti_accessori()
    {
        $this->Sys_model->script_migrazione_prodotti_accessori();
    }
    
    function migrazione_contrattimultifunzione_commesse ()
    {
        
        $this->Sys_model->script_migrazione_contrattimultifunzione_commesse();
    }
    
    function sync_nav()
    {
        //$soapURL="http://Dynamic1.aboutx.local:7047/DynamicsNAV90/WS/About-X/Codeunit/WsManager";
        //var_dump(file_get_contents($soapURL));
        $soapURL = "http://localhost:8822/jdocweb/test/test.wsdl" ;
        //libxml_disable_entity_loader(false);
        //$context = ['socket' => ['bindto' => '10.0.0.8']];
        //$soapParameters = Array('login' => "a.galli", 'password' => "Eraclea2014.1",'stream_context' => stream_context_create($context)) ;

        //$oapClient = new SoapClient($soapURL, $soapParameters);
        $config['wsdl'] = $soapURL;
        $config['namespace'] = $soapURL;
        $config['username'] = 'a.galli';
        $config['password'] = 'Eraclea2014.1';
        $config['soap_options'] = array(
        'soap_version'=>SOAP_1_2,
        'exceptions'=>true,
        'trace'=>1,
        'cache_wsdl'=>WSDL_CACHE_NONE
        );
        //new SoapAccess($config);
        $this->load->library('NtlmClient');
        $NtlmClient = new NtlmClient($soapURL,[
            'login'      => 'aboutx\a.galli',
            'password'   => 'Eraclea2014.1',
            'exceptions' => true
        ]);
        $arguments=array(
            'pNo'  => '',
            'pName' => 'azienda test soap 11',
            'pName2' => '',
            'pAddress' => '',
            'pAddress2' => '',
            'pPostCode' => '',
            'pCity' => '',
            'pCounty' => '',
            'pCountryRegion' => '',
            'pMail' => '',
            'pContact' => '',
            'pPhoneNo' => '',
            'pTelexNo' => '',
            'pFaxNo' => '',
            'pVatRegNo' => '',
            'pBlocked' => '',
            'pCustomerModel' => 'CLIE01',
            'returnCustNo' => ''
        );
        $testws_result=$NtlmClient->__soapCall('CustomerInsertUpdate', array('parameters' => $arguments)) ;
        $returnCustNo=$testws_result['returnCustNo'];
    }
    
    function script_update_segnalazioni_totore()
    {
        $this->Sys_model->script_update_segnalazioni_totore();
    }
    
    function script_migrazione_campi()
    {
        $this->Sys_model->script_migrazione_campi();
    }
    
    
    public function temp_timesheet()
    {
        $current_timesheets=  $this->Sys_model->db_get("user_timesheet",'*',"recordidvendite_='00000000000000000000000000000805'");
        foreach ($current_timesheets as $key => $current_timesheet) {
            $current_timesheet_recordid=$current_timesheet['recordid_'];
            $bak_timesheet=  $this->Sys_model->db_get_row("user_timesheetbak3",'*',"recordid_='$current_timesheet_recordid'");
            $bak_recordidvendite=$bak_timesheet['recordidvendite_'];
            $sql="
                UPDATE user_timesheet
                SET recordidvendite_='$bak_recordidvendite'
                WHERE recordid_='$current_timesheet_recordid'
                ";
            $this->Sys_model->execute_query($sql);
        }
    }
    
    
    public function reindex($tableid,$fieldid)
    {
        $table_name="user_".strtolower($tableid);
        $rows=$this->Sys_model->db_get($table_name,'recordid_',"true","ORDER BY recordid_ asc");
        $counter=0;
        foreach ($rows as $key => $row) {
            $counter=$counter+1;
            $recordid=$row['recordid_'];
            $sql="UPDATE $table_name SET $fieldid='$counter' WHERE recordid_='$recordid' ";
            $this->Sys_model->execute_query($sql);
        }
    }
    
    
        
}
?>