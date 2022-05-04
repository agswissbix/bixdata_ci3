<?php

class Bix_datagateway_model extends CI_Model {
    
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
    
     function execute_query($sql)
    {
        $query = $this->db->query($sql);
        return $query;
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
     * Helper per fare select dal database
     */
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
    
    function isempty($value)
    {
        if(($value=='')||($value==null))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function insert_record($tableid,$userid,$fields)
    {
        $tablename='user_'.strtolower($tableid);
        $new_recordid = $this->generate_recordid($tableid);
        $now = date('Y-m-d H:i:s');
        $insert = "INSERT INTO $tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_";
        $values = " VALUES ('$new_recordid',$userid,'$now',$userid,'$now',0,'N'";
        foreach ($fields as $field_key => $field_value) {
            $field_value=  str_replace("'", "''", $field_value);
            $insert=$insert.",$field_key";
            if(($field_value==null)||($field_value=='null'))
            {
                $values=$values.",null";
            }
            else
            {
                $values=$values.",'$field_value'";
            }
        }
        $insert=$insert.")";
        $values=$values.")";
        $sql=$insert." ".$values;
        $this->execute_query($sql);
        return $new_recordid;
    }
    
    function update_record($tableid,$userid,$fields,$condition)
    {
        $now = date('Y-m-d H:i:s');
        $sql="UPDATE user_$tableid SET lastupdaterid_=$userid,lastupdate_='$now'";
        foreach ($fields as $key => $field_value) {
            $field_value=  str_replace("'", "''", $field_value);
            if(($field_value==null)||($field_value=='null'))
            {
                 $value="null";
            }
            else
            {
                 $value="'$field_value'";
            }
            $sql=$sql.",$key=$value";
           
        }
        $sql=$sql." WHERE $condition";
        //echo $sql."<br/>";
        $this->execute_query($sql);
        
    }
    
    function sync_record($tableid,$fields,$condition)
    {
        
    }
    
}
?>