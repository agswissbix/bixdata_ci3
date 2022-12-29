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
            echo "UPDATE RECORD <br/>";
            $recordid=$bixdata_row['recordid_'];
            $this->update_record($tableid,1,$fields,"recordid_='$recordid'");
        }
        else
        {
            echo "INSERT RECORD <br/>";
            $fields['id']= $this->Sys_model->generate_id($tableid);
            $recordid=$this->insert_record($tableid,1,$fields);
        }
        
        if($tableid=='salesorderline')
        {
            $this->update_salesorderline($recordid);
        }
        if($tableid=='invoiceline')
        {
            $this->update_invoiceline($recordid);
        }
        
    }
    
    public function update_salesorderline($recordid_salesorderline)
    {
        ini_set('max_execution_time', 3600);
        echo "update_salesorderline: $recordid_salesorderline";
        $updated_field=array();
        $salesorderline= $this->db_get_row('user_salesorderline','*',"recordid_=$recordid_salesorderline");
        $recordid_salesorder=$salesorderline['recordidsalesorder_'];
        $salesorder=$this->db_get_row('user_salesorder','*',"recordid_='$recordid_salesorder'");
        
        $repetition_type=$salesorder['repetitiontype'];
        $recordid_company=$salesorder['recordidcompany_'];
        
        $updated_field['repetitiontype']=$repetition_type;
        $updated_field['recordidcompany_']=$recordid_company;
        
        if(($repetition_type=='Monthly'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price']*12;
        }
        if(($repetition_type=='Bimonthly'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price']*6;
        }
        if(($repetition_type=='Quarterly'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price']*4;
        }
        if(($repetition_type=='Yearly'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price'];
        }
        if(($repetition_type=='Biennial'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price']/2;
        }
        if(($repetition_type=='Triennial'))
        {
            $updated_field['total_net_yearly']=$salesorderline['price']/2;
        }
        
        $account=$salesorderline['account'];
        $updated_field['accountgroup']='Altro';
        if($account=='Consulenze IT')
        {
            $updated_field['accountgroup']='Assistenza ICT'; 
        }
        if($account=='Consulenze Software')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Ricavi da Noleggio Hardware')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Ricavi da Noleggio Stampanti')
        {
            $updated_field['accountgroup']='Printing'; 
        }
        if($account=='Vendita Assistenza Prioritaria')
        {
            $updated_field['accountgroup']='ICT'; 
        }
        if($account=='Vendita BE ALL Antivirus')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita BE ALL Assistance (All-In)')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita BE ALL Backup')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita DCS e Servizi Cloud')
        {
            $updated_field['accountgroup']='DCS e Cloud'; 
        }
        if($account=='Vendita Hardware e Software')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Licenze ADIUTO')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Vendita PBX Assistenza')
        {
            $updated_field['accountgroup']='PBX'; 
        }
        if($account=='Vendita PBX Maintenance')
        {
            $updated_field['accountgroup']='PBX'; 
        }
        if($account=='Vendita Prodotti')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Servizi')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Servizi BeAll Monitoring Only')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita Servizi di Assistenza')
        {
            $updated_field['accountgroup']='ICT'; 
        }
        if($account=='Vendita Servizi Hosting')
        {
            $updated_field['accountgroup']='Hosting'; 
        }
        if($account=='Vendita Sviluppo Software')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Vendita Telefonia')
        {
            $updated_field['accountgroup']='Telefonia'; 
        }
        
        
        
        var_dump($updated_field);
        $this->update_record('salesorderline',1,$updated_field,"recordid_='$recordid_salesorderline'");
    }
    
    public function update_invoiceline($recordid_invoiceline)
    {
        ini_set('max_execution_time', 3600);
        echo "update_invoiceline: $recordid_invoiceline";
        $updated_field=array();
        $invoiceline= $this->db_get_row('user_invoiceline','*',"recordid_=$recordid_invoiceline");
        $recordid_invoice=$invoiceline['recordidinvoice_'];
        $invoice=$this->db_get_row('user_invoice','*',"recordid_='$recordid_invoice'");
        
        $recordid_company=$invoice['recordidcompany_'];
        
        $updated_field['recordidcompany_']=$recordid_company;
        
               
        $account=$invoiceline['account'];
        $updated_field['accountgroup']='Altro';
        if($account=='Consulenze IT')
        {
            $updated_field['accountgroup']='Assistenza ICT'; 
        }
        if($account=='Consulenze Software')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Ricavi da Noleggio Hardware')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Ricavi da Noleggio Stampanti')
        {
            $updated_field['accountgroup']='Printing'; 
        }
        if($account=='Vendita Assistenza Prioritaria')
        {
            $updated_field['accountgroup']='ICT'; 
        }
        if($account=='Vendita BE ALL Antivirus')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita BE ALL Assistance (All-In)')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita BE ALL Backup')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita DCS e Servizi Cloud')
        {
            $updated_field['accountgroup']='DCS e Cloud'; 
        }
        if($account=='Vendita Hardware e Software')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Licenze ADIUTO')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Vendita PBX Assistenza')
        {
            $updated_field['accountgroup']='PBX'; 
        }
        if($account=='Vendita PBX Maintenance')
        {
            $updated_field['accountgroup']='PBX'; 
        }
        if($account=='Vendita Prodotti')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Servizi')
        {
            $updated_field['accountgroup']='Hardware e Software'; 
        }
        if($account=='Vendita Servizi BeAll Monitoring Only')
        {
            $updated_field['accountgroup']='BE ALL'; 
        }
        if($account=='Vendita Servizi di Assistenza')
        {
            $updated_field['accountgroup']='ICT'; 
        }
        if($account=='Vendita Servizi Hosting')
        {
            $updated_field['accountgroup']='Hosting'; 
        }
        if($account=='Vendita Sviluppo Software')
        {
            $updated_field['accountgroup']='Software'; 
        }
        if($account=='Vendita Telefonia')
        {
            $updated_field['accountgroup']='Telefonia'; 
        }
        
        
        
        var_dump($updated_field);
        $this->update_record('invoiceline',1,$updated_field,"recordid_='$recordid_invoiceline'");
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
        $sync_condition= $this->db_get_value('sys_table', 'sync_condition', "id='$bixdata_table'");
        $sync_order= $this->db_get_value('sys_table', 'sync_order', "id='$bixdata_table'");
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
        if($this->isempty($sync_condition))
        {
            $condition="";
        }
        else
        {
            $condition="WHERE $sync_condition";
        }
        
        if($this->isempty($sync_order))
        {
            $order="";
        }
        else
        {
            $order="ORDER BY $sync_order";
        }
        
  
        $rows=$this->conn_select($conn,"SELECT * FROM $sync_table $condition $order");
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
                echo "$id_hubspot:<br/> Effective margin: $effectivemargin <br/> Deal commission: $deal_commission <br/> Lead commission: $lead_commission<br/>"; 
            }
            
            $invoice=$this->db_get_row('user_invoice','*',"recordiddeal_='$recordid_deal'","ORDER BY date desc");
            if($invoice!=null)
            {
                $fields['lastinvoicedate']=$invoice['date'];
                echo "$id_hubspot: lastinvoicedate: ".$invoice['date']."<br/>";
            }
            $this->update_record('deal',1,$fields,"recordid_='$recordid_deal'");
            

        }
    }
    
    function set_plannedinvoice_orders()
    {
        $sql="DELETE FROM user_salesorderplannedinvoice";
        $this->execute_query($sql);
        
        $orders= $this->db_get('user_salesorder','*',"repetitiontype is not null and repetitiontype!=''");
        foreach ($orders as $key => $order) {
            $recordid_salesorder=$order['recordid_'];
            $this->set_plannedinvoice_order($recordid_salesorder);
        }
    }
    
    function set_plannedinvoice_order($recordid_salesorder)
    {
        //$sql="DELETE FROM user_salesorderplannedinvoice WHERE recordidsalesorder_='$recordid_salesorder'";
        //$this->execute_query($sql);
                
        $salesorder= $this->db_get_row('user_salesorder','*',"recordid_='$recordid_salesorder'");
        echo "Elaborazione ".$salesorder['title']."<br/>";
        $fields['name']=$salesorder['title'];
        $fields['totalnet']=$salesorder['totalnet'];
        $fields['totalgross']=$salesorder['totalgross'];
        $fields['sector']=$salesorder['sector'];
        $fields['status']=$salesorder['status'];
        $fields['documentnr']=$salesorder['documentnr'];
        $fields['recordidsalesorder_']=$recordid_salesorder;
        $fields['recordidcompany_']=$salesorder['recordidcompany_'];
        
        $datainizio=$salesorder['repetitionstartdate'];
        echo "Data inizio ordine: $datainizio <br/>";
        $data=$datainizio;
        $repetition_type=$salesorder['repetitiontype'];
        if($repetition_type=='Monthly')
        {
            for($x=0;$x<120;$x++)
            {
                $multi=$x;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
        if($repetition_type=='Bimonthly')
        {
            for($x=0;$x<60;$x++)
            {
                $multi=$x*2;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
        if($repetition_type=='Quarterly')
        {
            for($x=0;$x<40;$x++)
            {
                $multi=$x*3;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
        if($repetition_type=='Yearly')
        {
            for($x=0;$x<10;$x++)
            {
                $multi=$x*12;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
        if($repetition_type=='Biennial')
        {
            for($x=0;$x<5;$x++)
            {
                $multi=$x*24;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
        if($repetition_type=='Triennial')
        {
            for($x=0;$x<3;$x++)
            {
                $multi=$x*36;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['date']=$data;
                $fields['id']= $this->Sys_model->generate_seriale('salesorderplannedinvoice', 'id');
                $this->insert_record('salesorderplannedinvoice',1, $fields);
            }
        }
    }
    
}

?>