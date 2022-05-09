<?php

use Jumbojett\OpenIDConnectClient;

class Bix_datagateway_controller extends CI_Controller {
    
    
    
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
        echo 'index';
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
    
    function sync_record($tableid,$fields,$sync_key_fieldid)
    {
        $origin_key_value=$fields[$sync_key_fieldid];
        $bixdata_row= $this->db_get_row('user_'.$tableid,'recordid_',"$sync_key_fieldid='$origin_key_value'");
        if($bixdata_row!=null)
        {
            $recordid=$bixdata_row['recordid_'];
            $this->update_record($tableid,1,$fields,"recordid_='$recordid'");
        }
        else
        {
            $fields['id']= $this->Sys_model->generate_id($tableid);
            $this->insert_record($tableid,1,$fields);
        }
    }
    
    public function syncdata_company()
    {
        $this->syncdata('user_aziende','company','id_bexio');
    }
    
    public function syncdata_deal()
    {
        $this->syncdata('user_hubspotdeals','deal','id_hubspot');
    }
    
    public function syncdata_dealline()
    {
        $this->syncdata('user_hubspotlineitems','dealline','id_hubspot');
    }
    
    public function syncdata($bixdata_table='')
    {
        $sync_table= $this->db_get_value('sys_table', 'sync_table', "id='$bixdata_table'");
        $sync_field= $this->db_get_value('sys_table', 'sync_field', "id='$bixdata_table'");
        $servername = "10.0.0.23";
        $username = "vtenext";
        $password = "Jbt$5qNbJXg";
        $database= "jdoc";
        $conn = new mysqli($servername, $username, $password, $database);
        $bixdata_fields=array();
        $rows=$this->db_get('sys_field','*',"tableid='$bixdata_table'");
        foreach ($rows as $key => $row) {
            $bixdata_fields[$row['sync_fieldid']]=$row['fieldid'];
        }
        $condition="WHERE deal_id='4617503473'";
        if($sync_table=='user_aziende')
        {
           $condition="WHERE bexioid is not null"; 
        }
        $rows=$this->conn_select($conn,"SELECT * FROM $sync_table $condition");
        foreach ($rows as $key => $row) {
            $sync_fields=array();
            foreach ($row as $key => $field) {
                if(array_key_exists($key, $bixdata_fields))
                {
                    $sync_fields[$bixdata_fields[$key]]=$field;
                }
            }
            var_dump($sync_fields);
            $this->sync_record($bixdata_table, $sync_fields,$sync_field);
        }
        
        $sys_table_link_rows=$this->db_get('sys_table_link','*',"tableid='$bixdata_table'");
        foreach ($sys_table_link_rows as $key => $sys_table_link_row) {
            $linked_tableid=$sys_table_link_row['tablelinkid'];
            $this->link_records($bixdata_table,$linked_tableid);
        }
    }
    
    public function link_records($master_tableid='',$link_tableid='')
    {
        $master_field=$this->db_get_value('sys_field', 'master_field', "tableid='$link_tableid' AND tablelink='$master_tableid'");
        $linked_field=$this->db_get_value('sys_field', 'linked_field', "tableid='$link_tableid' AND tablelink='$master_tableid'");
        $sql="
            UPDATE user_$link_tableid
            INNER join user_$master_tableid ON user_$link_tableid.$linked_field=user_$master_tableid.$master_field
            SET user_$link_tableid.recordid".$master_tableid."_=user_$master_tableid.recordid_
            WHERE true
            ";
        echo $sql;
        $this->execute_query($sql);
    }
    
    

    
    function conn_select($conn,$sql) {
        $result = $conn->query($sql);
         $rows = array();
        if($result)
        {
            while($row = $result -> fetch_array(MYSQLI_ASSOC))
            {
                $rows[]=$row;
            }
        }
        return $rows;
    }
    
    public function generate_recordid($idarchivio){
        $tablename='user_'.strtolower($idarchivio);
        $sql="SELECT recordid_ FROM $tablename WHERE recordid_ NOT LIKE '1%' ORDER BY recordid_ DESC LIMIT 1";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
        $recordid=$result[0]['recordid_'];
        $intrecordid=  intval($recordid);
        $new_intrecordid=$intrecordid+1;
        $new_recordid_short=  strval($new_intrecordid);
        }
        else
        {
            $new_recordid_short='1';
        }
        $new_recordid_short_lenght=  strlen($new_recordid_short);
        $new_recordid='';
        for($i=0;$i<(32-$new_recordid_short_lenght);$i++)
        {
            $new_recordid=$new_recordid.'0';
        }
        $new_recordid=$new_recordid.$new_recordid_short;;
        return $new_recordid;
    }
    
    
    public function update_deals()
    {
        $servername = "10.0.0.25";
        $username = "vtenextremote";
        $password = "DfCEVixXETLf$";
        $database= "vte_swissbix";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);
        
        $deals=$this->Sys_model->db_get('user_deal');
        foreach($deals as $deal)
        {
            $fields=array();
            $id_hubspot=$deal['id_hubspot'];
            $recordid_deal=$deal['recordid_'];
            $dealuser=$deal['dealuser'];
            $leadoriginuser=$deal['leadoriginuser'];
            $leadoriginuser=strtok($leadoriginuser,  ' ');
            $expectedmargin=$deal['expectedmargin'];
            $results=$this->conn_select($conn,"SELECT * FROM vte_potentialscf WHERE cf_p8h_2831='$id_hubspot' ");
            if(count($results)>0)
            {
                $effectivemargin=$results[0]['cf_z00_2896'];
                $bexioinvoicenr=$results[0]['cf_z00_2899'];
                $invoice=$this->Sys_model->db_get_row('user_invoice','*',"documentnr='$bexioinvoicenr'");
                $recordid_invoice=$invoice['recordid_'];
                $this->execute_query("UPDATE user_invoice SET recordiddeal_='$recordid_deal' WHERE recordid_='$recordid_invoice'");
                if($this->isempty($effectivemargin))
                {
                    $effectivemargin=$expectedmargin;
                }
                $fields['effectivemargin']=$effectivemargin;
                $deal_commission=0;
                $lead_commission=0;
                if(($this->isempty($leadoriginuser))||($dealuser==$leadoriginuser))
                {
                    $deal_commission=$effectivemargin*0.20;
                }
                else
                {
                    $deal_commission=$effectivemargin*0.10;
                    $lead_commission=$effectivemargin*0.10;
                }
                $fields['dealcommission']=$deal_commission;
                $fields['leadcommission']=$lead_commission;
                $fields['invoicedocumentnr']=$bexioinvoicenr;
                echo "$id_hubspot:<br/> Effective margin: $effectivemargin <br/> Deal commission: $deal_commission <br/> Lead commission: $lead_commission<br/>"; 
                $this->update_record('deal',1,$fields,"recordid_='$recordid_deal'");
            }

        }
    }
    
}

?>