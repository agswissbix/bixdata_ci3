<?php

class Rest_controller extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_tables_menu()
    {
        $fissi=$this->Sys_model->get_archive_menu();
        echo json_encode($fissi);
    }
    
    public function get_records()
    {
        $post=$_POST;
        $table=$post['table'];
        
        $columns=  $this->Sys_model->get_results_columns($table, 1);
        $return['columns']= $columns; 
        $sql="";
        foreach ($columns as $key => $column) {
            if(($column['id']!='recordid_')&&($column['id']!='recordstatus_')&&($column['id']!='recordcss_'))
            {
                if($sql=='')
                {
                    $sql="select risultati.* FROM (SELECT recordid_, recordstatus_,  '' as recordcss_,";
                }
                else
                {
                    $sql=$sql.",";
                }
                $sql=$sql.$column['id'];
            }
        }
        $sql=$sql." FROM user_$table WHERE true AND (recordstatus_ is null OR recordstatus_!='temp') ) AS risultati LEFT JOIN user_".$table."_owner ON risultati.recordid_=user_".$table."_owner.recordid_ where ownerid_ is null OR ownerid_=1 ";
        $return['records']=$this->Sys_model->get_records($table,$sql,'recordid_','desc',0,50);
        echo json_encode($return);
    }
    
    public function get_fissi()
    {
        $fissi=$this->Sys_model->get_fissi('company', '00000000000000000000000000000500');
        echo json_encode($fissi);
    }
    
    public function get_record_labels()
    {
        $fissi=$this->Sys_model->get_labels_table('company', 'scheda', '00000000000000000000000000000500', 1);
        echo json_encode($fissi);
    }
    
    public function get_record_fields()
    {
        $fissi=$this->Sys_model->get_fields_table('company','Dati', '00000000000000000000000000000500');
        echo json_encode($fissi);
    }
}
?>