<?php

class Scheduler_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function select($sql)
    {
        $query=$this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    /**
     * helper per eseguire update o insert passando l'sql
     * @param type $sql testuale
     * @return string ultimo id inserito se la query va a buon fine
     */
     function execute_query($sql)
    {
        $query = $this->db->query($sql);
        return $query;
    }
    
    function db_get($tableid,$columns='*',$conditions='true',$order='',$limit='')
    {
        $sql="
            SELECT $columns
            FROM $tableid
            WHERE $conditions 
            $order 
            $limit
                ";
        $result=  $this->select($sql);
        return $result;
    }
    
    /**
     * 
     * @param type $tableid
     * @param type $columns
     * @param type $conditions
     * @param type $limit
     * @param type $order
     * @return type
     * @author Alessandro Galli
     * 
     * Helper per ottenere l'array con la prima riga trovata dalla select generata
     */
    function db_get_row($tableid,$columns='*',$conditions='true',$order='',$limit='')
    {
        $rows=$this->db_get($tableid, $columns, $conditions, $order, $limit);
        if(count($rows)>0)
        {
            $return=$rows[0];
        }
        else
        {
            $return=null;
        }
        return $return;
    }
    
    function db_get_value($tableid,$column='Codice',$conditions='true',$order='')
    {
        $row=  $this->db_get_row($tableid, $column, $conditions,$order);
        if($row!=null)
        {
            $column=  str_replace('"', '', $column);
            $return=$row[$column]; 
        }
        else
        {
            $return=null;
        }
        return $return;
    }
    
    function db_get_count($tableid,$conditions='true')
    {
        $row=  $this->db_get_row($tableid, "count(*) as counter", $conditions);
        if($row!=null)
        {
            $return=$row['counter']; 
        }
        else
        {
            $return=0;
        }
        return $return;
    }
    
    function set_schedule_status($schedule_id,$status)
    {
        $sql="
                UPDATE sys_scheduler_tasks
                SET status='$status'
                WHERE id=$schedule_id
            ";
        $this->execute_query($sql);
    }
    function get_scheduled($schedule_id=null)
    {
        if($schedule_id==null)
        {
            $sql="
            SELECT *
            FROM sys_scheduler_tasks
            ";
            $return=  $this->select($sql);
            return $return;
        }
        else
        {
            $sql="
                SELECT *
                FROM sys_scheduler_tasks
                WHERE id=$schedule_id
                ";  
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                return $result[0];
            }
            else
            {
                return null;
            }
        }
        
        
    }
    
    function set_scheduler_log($testo)
    {
        $testo=  str_replace("'", "''", $testo);
        $sql="
            INSERT INTO sys_scheduler_log (description)
            VALUES ('$testo')
            ";
        $this->execute_query($sql);
    }
    
    
    
    
    
}
?>