<?php

class Scheduler_controller extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function check_scheduled()
    {
        $this->Scheduler_model->set_scheduler_log('Check Scheduled');
        $scheduled=$this->Scheduler_model->get_scheduled();
        foreach ($scheduled as $key => $schedule) {
            $schedule_id=$schedule['id'];
            $active=$schedule['active'];
            $status=$schedule['status'];
            $limite=$schedule['limite'];
            $intervallo=$schedule['intervallo'];
            $secondi_intervallo = strtotime("1970-01-01 $intervallo UTC");
            if($active=='t')
            {
                if($status!='running')
                {
                    $this->Scheduler_model->set_schedule_status($schedule_id,'running');
                    for($x=0;$x<$limite;$x++)
                    {
                        
                        if($this->check_running($schedule_id))
                        {
                            sleep($secondi_intervallo); 
                            $this->Scheduler_model->set_scheduler_log('Call: '.$schedule['funzione']);
                            call_user_func(array($this, $schedule['funzione']),$schedule_id);
                        }
                        
                    }
                    $this->Scheduler_model->set_schedule_status($schedule_id,'stop');
                }
                else
                {
                    $this->Scheduler_model->set_scheduler_log($schedule['funzione'].' già in elaborazione<br/>');
                    echo $schedule['funzione'].' già in elaborazione<br/>';
                }
            }
        }
    }
    
    function check_running($schedule_id)
    {
        $scheduled=$this->Scheduler_model->get_scheduled($schedule_id);
        if($scheduled!=null)
        {
            if($scheduled['status']=='running')
                return true;
            else
                return false; 
        }
        else
        {
            return false;
        }
        

    }
    
    
    function send_queued_mail($schedule_id)
    {
        /*$mail=$this->Scheduler_model->get_queued_mail();
        $mail_id=$mail['id'];
        $send_result=  jdw_send_mail($mail);
        $this->load->library('My_PHPMailer');
        if($send_result=='sent')
        {
            $this->Scheduler_model->update_queued_mail_status($mail_id,'inviata');  
            $this->Scheduler_model->update_queued_mail_dataora_invio($mail_id); 
        }
        else
        {
            $this->Scheduler_model->update_queued_mail_status($mail_id,'errore');
            $this->Scheduler_model->update_queued_mail_note($mail_id,'errore - '.$errorinfo);
        }
        echo 'Mail inviata a '.$mailto.'<br/>';
        $this->Scheduler_model->set_scheduler_log('Mail inviata a '.$mailto);*/
        echo 'Invio mail in coda';
        $this->Scheduler_model->set_scheduler_log('Invio mail in coda');
        $feedback=file_get_contents(base_url()."/index.php/sys_viewcontroller/send_queued_mail");
        $this->Scheduler_model->set_scheduler_log("Feedback: ".$feedback);
        if($feedback=='stop')
        {
            $this->Scheduler_model->set_schedule_status($schedule_id,'stop');
            $this->Scheduler_model->set_scheduler_log('Non ci sono mail in coda da inviare <br/>');
        }
        
    }
    
    function dailymail_alerts()
    {
        if(date('H:i')=='09:00')
        {
            $this->Scheduler_model->set_scheduler_log('Caricamento delle mail di notifica giornaliere');
            $feedback=file_get_contents(base_url()."/index.php/sys_viewcontroller/dailymail_alerts");
            $this->Scheduler_model->set_scheduler_log('Feedback del caricamento delle mail di notifica giornaliere: '.$feedback);
            $this->Scheduler_model->set_scheduler_log('Mail di notifica giornaliere inviate');
        }
    }
    
    function mid_dailymail_alerts()
    {
        if(date('H:i')=='15:00')
        {
            $this->Scheduler_model->set_scheduler_log('Caricamento delle mail di notifica giornaliere pomeridiane');
            $feedback=file_get_contents(base_url()."/index.php/sys_viewcontroller/mid_dailymail_alerts");
            $this->Scheduler_model->set_scheduler_log('Feedback del caricamento delle mail di notifica giornaliere: '.$feedback);
            $this->Scheduler_model->set_scheduler_log('Mail di notifica giornaliere inviate');
        }
    }
    
     function weeklymail_alerts()
    {
        if((date('D')=='Mon')&&(date('H:i')=='07:30'))
        {
            $this->Scheduler_model->set_scheduler_log('Caricamento delle mail di notifica settimanale');
            $feedback=file_get_contents(base_url()."/index.php/sys_viewcontroller/weeklymail_alerts");
            $this->Scheduler_model->set_scheduler_log('Feedback del caricamento delle mail di notifica settimanale: '.$feedback);
            $this->Scheduler_model->set_scheduler_log('Mail di notifica settimanale inviate');
        }
    }
    
     function monthlymail_alerts()
    {
        if((date('d')=='01')&&(date('H:i')=='07:30'))
        {
            $this->Scheduler_model->set_scheduler_log('Caricamento delle mail di notifica mensile');
            $feedback=file_get_contents(base_url()."/index.php/sys_viewcontroller/monthlymail_alerts");
            $this->Scheduler_model->set_scheduler_log('Feedback del caricamento delle mail di notifica mensile: '.$feedback);
            $this->Scheduler_model->set_scheduler_log('Mail di notifica mensile inviate');
        }
    }
    
    function google_calendar_sync_dimensione()
    {
        $this->Scheduler_model->set_scheduler_log('Sincronizzazione calendario');
        $feedback=file_get_contents(base_url()."/index.php/script_controller/google_calendar_sync_dimensione");
        $this->Scheduler_model->set_scheduler_log('Feedback della sincronizzazione calendario: '.$feedback);
        $this->Scheduler_model->set_scheduler_log('Calendario sincronizzato');
    }
    
    function send_notification($check_value)
    {
        $notification_fields=$this->Sys_model->get_notification_fields();
        foreach ($notification_fields as $table_key => $table) {
            $tableid=$table_key;
            foreach ($table as $field_key => $field) {
                $fieldid=$field;
                $notification_records=  $this->Sys_model->get_notification_records($tableid,$fieldid);
            }
             
        }
    }
    
    function backup()
    {
        
    }
    
    function custom_seatrade_importscan()
    {
        $base_url=  base_url();
        file_get_contents("$base_url/index.php/sys_viewcontroller/load_autobatch_files");
        file_get_contents("$base_url/index.php/sys_viewcontroller/check_autobatch");
    }
    
    function sync_segnalazioni()
    {
        $base_url=  base_url();
        file_get_contents("$base_url/index.php/sys_viewcontroller/sync_segnalazioni");
    }
    
    function sync_iscritti()
    {
        $base_url=  base_url();
        file_get_contents("$base_url/index.php/sys_viewcontroller/sync_iscritti");
    }
    
    function autobatchimport()
    {
        $base_url=  base_url();
        file_get_contents("$base_url/index.php/sys_viewcontroller/autobatchimport");
    }
    
    function genera_autobatch_preview()
    {
        
    }
    
    function load_autobatch_files()
    {
        $base_url=  base_url();
        file_get_contents("$base_url/index.php/sys_viewcontroller/load_autobatch_files");
    }
    
    
    function custom_3p_send_report()
    {
        $base_url=  base_url();
        echo 'custom_3p_send_report check orario 14:00';
        if(date('H:i')=='14:14')
        {
            echo 'custom_3p_send_report';
            $this->Scheduler_model->set_scheduler_log('custom_3p_send_report');
            echo $base_url."index.php/sys_viewcontroller/custom_3p_send_report";
            $feedback=file_get_contents($base_url."index.php/sys_viewcontroller/custom_3p_send_report");
            echo $feedback;
            $this->Scheduler_model->set_scheduler_log('Feedback custom_3p_send_report: '.$feedback);
        }
    }
    
    
}
?>