<?php

class Crm_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get_lista_utenti()
    {
        $this->db->select();
        $this->db->from('sys_user');
        $query=$this->db->get();
        return $query;
    }
    function get_lista_clienti()
    {
        $this->db->select();
        $this->db->from('user_clienti');
        $query=$this->db->get();
        return $query;
        //$res=$this->select("SELECT * FROM user_clienti");
        //return $res;
    }
    
     function select($query)
    {
        $rows = $query->result();
        $rows_return=array();
        foreach ($rows as $row) {
            $row=(array)$row;
            $rows_return[]=$row;
        }
        return $rows_return;
    }
    
     function execute_query($sql)
    {
       $query = $this->db->query($sql);
         if($query==true) 
         {
            $return = mysql_insert_id();
         }
         else
         {
             $return="false";
         }
         return $return;
    }
    
    /*function get_user_login($username)
    {
        $this->db->select('username, password, firstname,id');
        $this->db->where('username',$username);
        $query = $this->db->get('sys_user');
        $result =  $this->select($query);
        return $result;
    }*/
    
    function get_test(){
        echo 'sono nel model';
    }
    
}
?>
