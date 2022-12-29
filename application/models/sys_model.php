<?php

class Sys_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    
    public function preload_layout_preferences($userid,$typepreference)
    {
        if($typepreference=='dashboard')
            return $this->select("SELECT dashboard FROM sys_user_preferences_layout WHERE userid=$userid AND typepreference='$typepreference'");
        if($typepreference=='schede')
            return $this->select("SELECT dati,allegati FROM sys_user_preferences_layout WHERE userid=$userid AND typepreference='$typepreference'");
        if($typepreference=='temi')
            return $this->select("SELECT tema FROM sys_user_preferences_layout WHERE userid=$userid AND typepreference='$typepreference'");
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
    
    function insert($table,$fields)
    {
        $x=0;
        $columns="";
        $values="";
        foreach ($fields as $key => $value) {
            if($x!=0)
            {
                $columns=$columns.",";
                $values=$values.",";
            }
            $columns=$columns."\"$key\"";

            if(($value=='')||$value==null)
            {
                $value='null';
            }
            else
            {
                $value=  str_replace("'", "''", $value);
                $value="'$value'";
            }
            $values=$values."$value";
            $x++;
        }
        $sql="INSERT INTO $table ($columns) VALUES ($values)";
        $this->execute_query($sql);
    }
    
    public function get_query_columns($tableid)
    {
        $sql="SELECT fieldid FROM sys_field WHERE tableid='$tableid'";
        $fields=  $this->select($sql);
        $columns="recordid_";
        foreach ($fields as $key => $field) {
            $columns=$columns.",".$field['fieldid'];
        }
        return $columns;
    }
    
    public function get_userid()
    {
        $userid= $this->session->userdata('userid');
        // da rivedere
        if($userid==null)
            $userid=1;
        return $userid;
    }
    
    public function get_username()
    {
        return $this->session->userdata('username');
    }
    
    public function get_usergroup($userid)
    {
        $sql="SELECT * FROM sys_group_user JOIN sys_group on sys_group_user.groupid=sys_group.id where sys_group_user.userid=$userid";
        $result=  $this->select($sql);
        $return['code']='';
        $return['value']='';
        if(count($result)>0)
        {
            $return['code']=$result['id'];
            $return['value']=$result['name'];
            
        }
        return $return;
    }
    
    public function get_dbdriver()
    {
        return $this->db->dbdriver;
    }
    
    public function syslog($testo,$funzione='',$echo=true)
    {
        $data=date("Y-m-d");
        $ora=date("H:i:s");
        $fields['id']= $this->generate_seriale('systemlog', 'id');
        $fields['data']=$data;
        $fields['ora']=$ora;
        $fields['funzione']=$funzione;
        $fields['testo']=$testo;
        if($echo)
        {
            echo $funzione." | ".$testo."<br/>";
        }
        $this->insert_record('systemlog', 1, $fields);
    }
    
    public function set_logquery($funzione,$sql_finale)
    {
        $sql_finale=  str_replace("'", "''", $sql_finale);
        //$sql_finale=  str_replace("\\", "\\\\", $sql_finale);
        $userid=$userid=$this->session->userdata('userid');
        if(($userid==null)||($userid==''))
        {
            $userid=0;
        }
        $date=  date('Y-m-d H:i:s');
        $logquery="INSERT INTO sys_logquery (userid,funzione,timestamp,query) VALUES ($userid,'$funzione','$date','$sql_finale')";
        $this->execute_query($logquery);
    }
    
    
    function get_like()
    {
        if($this->get_dbdriver()=='postgre')
        {
            $like='ilike';
        }
        else
        {
            $like='like';
        }
        return $like;
    }
    /**
     * ritorna dati utente per il login
     * 
     * @param type $username nome utente
     * @return  array[username;password,firstname,id]
     * @author Alessandro Galli
     */
    function get_user_login($username)
    {
        $sys_settings=  $this->get_sys_settings();
        $like=  $this->get_like();
        /*$like='like';
        if($this->get_dbdriver()=='postgre')
            $like='ilike';*/
        $sql="
            SELECT username,password,firstname,id 
            FROM sys_user
            WHERE username $like '$username' and disabled<>'Y'
        ";
        $result =  $this->select($sql);
        return $result;
    }
    
    function get_sys_settings()
    {
        $settings=array();
       $sql="SELECT * from sys_settings";
        $results=  $this->select($sql);
        foreach ($results as $key => $result) {
            $settings[$result['setting']]=$result['value'];
        }
        return $settings; 
    }
    
    function get_sys_user_settings($userid=null,$setting=null,$tableid=null,$fieldid=null)
    {
       $user_settings=array();
       $sql="SELECT * from sys_user_settings";
       $where=" WHERE true";
       if($userid!=null)
       {
           $where=$where." AND userid=$userid";
       } 
       if($setting!=null)
       {
           $where=$where." AND setting='$setting'";
       }
       if($tableid!=null)
       {
           $where=$where." AND tableid='$tableid'";
       }

        $sql=$sql.$where;
        $results=  $this->select($sql);
        if(count($results)>0)
        {
            foreach ($results as $key => $result) {
                $settings[$result['setting']]=$result['value'];
            }
            $return=$settings;
        }
        else
        {
            $return= null;
        }
        
        return $return ; 
    }
    /**
     * Ritorna le impostazioni del sistema e dell'utente
     * 
     * @param String $userid
     * @return array insieme delle impostazioni
     * @author Alessandro Galli
     * 
     */
    function get_settings()
    {
        //username
        $settings['username']=  $this->session->userdata('username');
        //userid
        $userid=$this->get_userid();
        $settings['userid']=$userid;
        //info utente
        $sql="SELECT * from sys_user WHERE id=$userid";
        $results=  $this->select($sql);
        $settings['lastname']=$results[0]['lastname'];
        $settings['firstname']=$results[0]['firstname'];
        $settings['description']=$results[0]['description'];
        //tema
        $sql="SELECT tema from sys_user_preferences_layout WHERE userid=$userid and typepreference='temi'";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $settings['theme']=$result[0]['tema'];
        }
        else
        {
          $settings['theme']='default';
        }
        //impostazioni generali
        $sql="SELECT * from sys_settings";
        $results=  $this->select($sql);
        foreach ($results as $key => $result) {
            $settings[$result['setting']]=$result['value'];
        }
        return $settings;
    }
    
    function get_user_setting($settingid=null,$userid=null)
    {
        $settings=  $this->Sys_model->get_user_settings($userid);
        if(array_key_exists($settingid, $settings))
        {
            $return=$settings[$settingid];
        }
        else
        {
            $return='';
        }
        return $return;
    }
    
    public function get_user_settings($userid)
    {
        $settings=array();
        //settings superuser(default per tutti gli utenti)
        $impostazioni_user_settings=  $this->get_impostazioni_user_settings(1);
        foreach ($impostazioni_user_settings as $key => $setting) {
            $settings[$key]=$setting['currentvalue'];
        }
        
        //settings gruppi
        $groups=  $this->Sys_model->db_get('sys_user_settings','*',"userid=$userid AND setting='group'");
        foreach ($groups as $key => $group) {
            $groupuserid=$group['value'];
            $impostazioni_user_settings=  $this->get_impostazioni_user_settings($groupuserid);
            foreach ($impostazioni_user_settings as $key => $setting) 
            {
                if(isnotempty($setting['currentvalue']))
                {
                     $settings[$key]=$setting['currentvalue'];
                }
               
            }
        }
        
    
        // settings utente
        $impostazioni_user_settings=  $this->get_impostazioni_user_settings($userid);
        foreach ($impostazioni_user_settings as $key => $setting) 
        {
            if(isnotempty($setting['currentvalue']))
            {
                $settings[$key]=$setting['currentvalue'];
            }
        }
    

        
        

        return $settings;
    }
    
    function get_user_setting_BAK($setting=null,$userid=null)
    {
        $setting_value=  $this->db_get_value('sys_user_settings','value',"setting='$setting' AND userid=$userid");
        return $setting_value;
    }
    
    function get_user_settings_bak($setting=null,$userid=null)
    {
        $settings=array();
        
        if($userid==null)
        {
            $userid=$this->session->userdata('userid');
        }
        
        if($userid==null)
        {
            $userid=1;
            $settings['userid']=1;
            $settings['username']=  'superuser';
        }
        else
        {
            $settings['userid']=$userid;
            //username
            $settings['username']=  $this->session->userdata('username');
        }
  
        if($setting!=null)
        {
            $sql="SELECT value from sys_user_settings WHERE userid=$userid AND setting='$setting'";
            $results=  $this->select($sql);
            if(count($results)>0)
            {
                $setting=$results[0]['value'];
            }
            else
            {
                $sql="SELECT value from sys_user_settings WHERE userid=1 AND setting='$setting'";
                $results=  $this->select($sql);
                if(count($results)>0)
                {
                    $setting=$results[0]['value'];
                }
                else
                {
                    $setting=null;
                }
            }
            if($this->isempty($setting))
            {
                $setting='';
            }
            $return=$setting;
            if($setting=='true')
            {
                $return=true;
            }
            if($setting=='false')
            {
                $return=false;
            }
            
        }
        else
        {
            $sql="SELECT * from sys_user_settings WHERE userid=$userid";
            $results=  $this->select($sql);
            if(count($results)>0)
            {
                foreach ($results as $key => $result) {
                    $settings[$result['setting']]=$result['value'];
                }  
            }
            else
            {
                $sql="SELECT * from sys_user_settings WHERE userid=1";
                $results=  $this->select($sql);
                foreach ($results as $key => $result) {
                    $settings[$result['setting']]=$result['value'];
                } 
            }
            
            $return=$settings;
        }
        
        return $return;
    }
    
    function get_field_setting($tableid,$fieldid,$settingid,$userid=null)
    {
        $settings=  $this->Sys_model->get_field_settings($tableid,$fieldid,$settingid,$userid);
        if(array_key_exists($settingid, $settings))
        {
            $return=$settings[$settingid];
        }
        else
        {
            $return='';
        }
        return $return;
    }
    function get_field_settings($tableid,$fieldid,$settingid=null,  $userid = null)
    {
        if($userid==null)
        {
            $userid=  $this->get_userid();
        }
        
       
        
        $settings=array();

        // settings di default di sistema e dell'utente
         $impostazioni_field_settings=$this->get_impostazioni_field_settings($tableid,$fieldid,$userid);
        foreach ($impostazioni_field_settings as $impostazioni_field_setting_key => $impostazioni_field_setting) {
            $settings[$impostazioni_field_setting_key]=$impostazioni_field_setting['currentvalue'];
        }
        
        //settings superuser(default per tutti gli utenti)
        $sql="
            SELECT settingid,value
            FROM sys_user_field_settings
            WHERE userid=1 AND tableid='$tableid' AND fieldid='$fieldid'
            ";
        $settings_superuser=  $this->select($sql);
        foreach ($settings_superuser as $key => $setting_superuser) {
            $settings[$setting_superuser['settingid']]=$setting_superuser['value'];
        }
        
        //settings gruppi
        $groups=  $this->Sys_model->db_get('sys_user_settings','*',"userid=$userid AND setting='group'");
        foreach ($groups as $key => $group) {
            $groupuserid=$group['value'];
            $sql="
            SELECT settingid,value
            FROM sys_user_field_settings
            WHERE userid=$groupuserid AND tableid='$tableid' AND fieldid='$fieldid'
            ";
            $settings_group=  $this->select($sql);
            foreach ($settings_group as $key => $setting_group) {
                $settings[$setting_group['settingid']]=$setting_group['value'];
            }
        }
        
        //settings utente
        
        $sql="
            SELECT settingid,value
            FROM sys_user_field_settings
            WHERE userid=$userid AND tableid='$tableid' AND fieldid='$fieldid'
            ";
        $settings_user=  $this->select($sql);
        foreach ($settings_user as $key => $setting_user) {
            $settings[$setting_user['settingid']]=$setting_user['value'];
        }
        
        return $settings;
    }
    
    function get_navigatorField($tableid,$recordid)
    {
         $fissi=$this->get_fissi($tableid, $recordid);
         if(count($fissi)>0)
         {
             $first=reset($fissi);
             return $first['value'];
         }
         else
         {
             return 'null';
         }
    }
    
    
    /**
     * Ritorna l'inseieme degli archivi su cui si possono effettuare ricerche,inserimenti ecc
     * 
     * @return array insieme degli archivi ricercabili
     * @author Alessandro Galli
     */
    function get_archive_list_searchable()
    {
        $this->db->select('description as nomearchivio,id as idarchivio');
        $query = $this->db->get('sys_table');
        $sql="
            SELECT description as nomearchivio,id as idarchivio
            FROM 
                sys_table
                JOIN
                sys_table_feature
                ON
                sys_table.id=sys_table_feature.tableid
            WHERE featureid=16 AND enabled='N'
            ORDER BY description
            ";
        return $this->select($sql);
        
    }
    
    function get_archive_menu()
    {
        $sql="
            SELECT *
            FROM 
                sys_table
                JOIN
                sys_table_feature
                ON
                sys_table.id=sys_table_feature.tableid
            WHERE featureid=16 AND enabled='N'
            ORDER BY workspaceorder,tableorder,description
            ";
        $archives= $this->select($sql);
        $return=array();
        foreach ($archives as $index => $archive) {
            $archive['settings']=  $this->get_table_settings($archive['id']);
            $return[$archive['workspace']][]=$archive;
        }
        return $return;
    }
    

    public function get_lista_code()
    {
        $sql="
            SELECT id,description
            FROM sys_batch 
            ORDER BY id ASC
            ";
        $result =  $this->select($sql);
        return $result;
    }
    
    public function get_lista_autobatch()
    {
        $sql="
            SELECT id,description
            FROM sys_autobatch 
            ORDER BY id ASC
            ";
        $result =  $this->select($sql);
        return $result;
    }
    
    
    public function crea_coda($nome_coda){
        $creatorid= $this->session->userdata('userid');
        $creationdate=date('Y-m-d H:i:s');
        $id=$nome_coda;
        $counter=1;
        $sql="SELECT * FROM sys_batch WHERE id='$id'";
        $result=  $this->select($sql);
        while (count($result) > 0) 
        {
            $id=$id.'_'.$counter;
            $sql="SELECT * FROM sys_batch WHERE id='$id'";
            $result=  $this->select($sql);
            $counter=$counter+1;
        } 
        $sql="INSERT INTO sys_batch (id,description,path,crypted,numfiles,lastfileposition,locked,creatorid,creationdate) VALUES ('$id','$nome_coda','$id','N',0,1,'N',$creatorid,'$creationdate')";
        $this->set_logquery('inserimento_coda',$sql);
        $this->execute_query($sql);  
    }
    
    
    public function get_files_coda($idcoda,$popuplvl=0)
    {
       /* if($idcoda=='sys_batch_temp')
        {
            $userid=$this->session->userdata('userid');
            $sql="
            SELECT *
            FROM sys_batch_file
            WHERE sys_batch_file.batchid='$idcoda-$userid-$popuplvl' AND sys_batch_file.creatorid=$userid
            ";
        }
        else
        {*/
        $sql="
            SELECT *
            FROM sys_batch_file
            WHERE batchid='$idcoda'
            ORDER BY sys_batch_file.fileposition
            ";
        
        //}
        $result =  $this->select($sql);
        return $result;
    }
    
    public function get_files_autobatch($idautobatch)
    {
        $sql="SELECT * FROM sys_autobatch WHERE id='$idautobatch'";
        $result=  $this->select($sql);
        
        if(count($result)>0)
        {
            $path=$result[0]['path'];
            $originalpath=$result[0]['originalpath'];
            if(true)
            {
                $sql="SELECT filename,fileext FROM sys_autobatch_file WHERE batchid='$idautobatch'";
                $result=  $this->select($sql);
                $file_attuali=array();
                foreach ($result as $key => $file) {
                    $filename=$file['filename'];
                    $fileext=$file['fileext'];
                    if(file_exists("../JDocServer/autobatch/$idautobatch/".$file['filename'].'.'.$file['fileext']))
                    {
                        $file_attuali[]=$file['filename'].'.'.$file['fileext'];
                    }
                    else
                    {
                        $sql="DELETE FROM sys_autobatch_file WHERE batchid='$idautobatch' AND filename='$filename' AND fileext='$fileext' ";
                        $this->execute_query($sql);
                    }
                }
                
                $folder="../JDocServer/autobatch/$path";
                $files=array();
                if(file_exists($folder))
                {
                   $files = scandir($folder); 
                }
                foreach($files as $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                        $filenameext=$file;
                        $fileext = pathinfo($filenameext, PATHINFO_EXTENSION);
                        $filename=  str_replace('.'.$fileext, '', $filenameext);
                        $filename=  str_replace("'", "''", $filename);
                        $new_filename=preg_replace('/[^[:alnum:]-._~]/', '', $filename);
                        $new_filename=  conv_text($new_filename);
                        $new_fileext=  strtolower($fileext);
                        $new_filenameext=$new_filename.".".$new_fileext;
                        $today=  date('Y-m-d');
                        
                        if(!in_array($filenameext,$file_attuali))
                        {
                            rename($folder."/".$filenameext, $folder."/".$new_filenameext);
                            $ocr='';
                            if($new_fileext=='pdf')
                            {
                                //$this->load->library('PdfParser');
                                //$ocr=  $this->pdfparser->parseFile($folder."/".$new_filenameext);
                                //$ocr=str_replace("'", "''", $ocr);
                                $ocr='';
                                $sql="INSERT INTO sys_autobatch_file (batchid,filename,fileext,description,fileposition,crypted,creatorid,creationdate,ocr) VALUES ('$idautobatch','$new_filename','$new_fileext','$new_filename',0,'N',1,'$today','$ocr')";
                                $this->execute_query($sql);
                            }
                        }
                    }
                  
                }
                
            }
        }
        $sql="
            SELECT *
            FROM sys_autobatch_file 
            WHERE batchid='$idautobatch'
            ORDER BY sys_autobatch_file.fileid
            ";
        
        
        $result =  $this->select($sql);
        return $result;
    }
    
    /**
     * Ritorna la lista degli allegati per uno specifico record
     * 
     * @param String $idarchive id dell'archivio
     * @param String $recordid_ id del record
     * @return array lista con le informazioni degli allegati
     * @author Alessandro Galli
     * 
     * @mobile
     */
    function get_allegati($idarchive,$recordid_)
    {     
        $idarchive=  strtolower($idarchive);
        $table='user_'.$idarchive.'_page';
        
        $select="SELECT * ";
        $from=" FROM ".$table;
        $where=" WHERE recordid_='$recordid_'";

        $sql_finale=$select.$from.$where." ORDER BY fileposition_";
        
        $rows=$this->select($sql_finale);
        return $rows;
    }
    
    
    public function salva_modifiche_coda($codaid,$post){
        if(array_key_exists('files', $post))
        {
        $sql="SELECT * FROM sys_autobatch WHERE id='$codaid'";
        $result=  $this->select($sql);
        $autobatch=$result[0];
        $files=$post['files'];
        if(array_key_exists('insert', $files))
        {
            foreach ($files['insert'] as $index => $file) {
                $fileid=$file['fileid'];
                $fileindex=$file['fileindex'];
                $fileparam=$file['fileparam'];
                $sql="
                SELECT sys_batch_file.fileid,sys_batch_file.filename,sys_batch_file.fileext,sys_batch_file.description,sys_batch_file.fileposition,sys_batch.path,sys_batch.numfiles,sys_batch.id,sys_batch_file.creatorid
                FROM sys_batch_file join sys_batch on sys_batch_file.batchid=sys_batch.id
                WHERE fileid=$fileid
                ";
                $result=  $this->select($sql);
                $path="";
                $filename="";
                $fileext="";
                if(count($result)==1)
                {
                  $path=$result[0]['path'];
                  $filename=$result[0]['filename'];
                  $description=$result[0]['description'];
                  $fileext=$result[0]['fileext'];
                  $batchnumfiles=$result[0]['numfiles'];
                  $batchid=$result[0]['id'];
                  $creatorid=$result[0]['creatorid'];
                }

                if($batchid=='sys_batch_temp')
                {
                    $fullpath_batch="../JDocServer/batch/$path/$creatorid/$filename.$fileext";
                }
                else
                {
                    $fullpath_batch="../JDocServer/batch/$path/$filename.$fileext";
                }
                $new_filename=  $this->generate_filename_coda($codaid);
                $fullpath_nuova_coda="../JDocServer/batch/$codaid/$new_filename.$fileext";

                //verifico ci sia la cartella per la coda
                if(!file_exists("../JDocServer/batch/$codaid"))
                {
                    mkdir("../JDocServer/batch/$codaid");
                }
                //sposto il file dalla coda nell'archivio
                if (copy($fullpath_batch,$fullpath_nuova_coda)) {
                    //elimino fisicamente il file
                    if(!file_exists("../JDocServer/trash"))
                    {
                        mkdir("../JDocServer/trash");
                    }
                    if(!file_exists("../JDocServer/trash/code"))
                    {
                        mkdir("../JDocServer/trash/code");
                    }
                    copy($fullpath_batch,"../JDocServer/trash/code/$filename.$fileext");
                    if(($this->isnotempty($path))&&($this->isnotempty($filename)))
                    {
                        unlink($fullpath_batch);
                    }
                  }

                //CUSTOM POSTGRES
                $now=date("Y-m-d H:i:s");  
                $sql="INSERT INTO sys_batch_file (batchid,filename,fileext,description,creationdate) VALUES ('$codaid','$new_filename','$fileext','$description','$now') ";
                $this->set_logquery('aggiornamento file coda',$sql);
                $this->execute_query($sql);

            }
        }
        
        if(array_key_exists('update', $files))
        { 
            foreach ($files['update'] as $filename => $file) {
                //recupero dati del file in coda
                $fileid=$file['fileid'];
                $fileindex=$file['fileindex'];
                $fileparam=$file['fileparam'];
                $fileposition=$fileindex+1;
                    //inserisco il file nel database come allegato del record
                    $sql="UPDATE sys_batch_file SET fileposition='$fileposition' WHERE batchid='$codaid' AND fileid='$fileid'  ";
                    $this->execute_query($sql);
            }
 
        }
        
        if(array_key_exists('delete', $files))
        {
            foreach ($files['delete'] as $filename => $file) {
                //recupero dati del file in coda
                $relative_path=$autobatch['path'];
                $autobatch_name=$autobatch['description'];
                $fileid=$file['fileid'];
                $fileindex=$file['fileindex'];
                $fileparam=$file['fileparam'];
                $fileposition=$fileindex+1;
                $sql="SELECT * FROM sys_autobatch_file WHERE batchid='$codaid' AND fileid=$fileid";
                $result=  $this->select($sql);
                $autobatch_file=$result[0];
                $filename=$autobatch_file['filename'];
                $fileext=$autobatch_file['fileext'];
                    //inserisco il file nel database come allegato del record
                $sql="DELETE FROM sys_autobatch_file  WHERE batchid='$codaid' AND fileid=$fileid";
                $this->set_logquery('eliminazione file coda',$sql);
                $this->execute_query($sql);
                $fullpath_archive="../JDocServer/autobatch/$relative_path/$filename.$fileext";

                //elimino fisicamente il file
                if(!file_exists("../JDocServer/trash"))
                {
                    mkdir("../JDocServer/trash");
                }
                if(!file_exists("../JDocServer/trash/$autobatch_name"))
                {
                    mkdir("../JDocServer/trash/$autobatch_name");
                }
                copy($fullpath_archive,"../JDocServer/trash/$autobatch_name/$filename.$fileext");
                if(($this->isnotempty($relative_path))&&($this->isnotempty($filename)))
                {
                    unlink($fullpath_archive);
                }
            }
                    
                    
                      

            }
            
             //aggiorno la sys_table
            $counter=  count($files['delete']);
            $new_numfilesfolder=$numfilesfolder-$counter;
            $sql="UPDATE sys_table SET numfilesfolder=$new_numfilesfolder WHERE id='$tableid'";
            $this->execute_query($sql);
        }
        
        
        
    }
    
    
    public function generate_filename_coda($batchid){
        $sql="SELECT filename FROM sys_batch_file WHERE batchid='$batchid' ORDER BY filename DESC LIMIT 1";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $filename=$result[0]['filename'];
            $intfilename=  intval($filename);
            $new_intfilename=$intfilename+1;
            $new_filename_short=  strval($new_intfilename);
            $new_filename_short_lenght=  strlen($new_filename_short);
            $new_filename='';
            for($i=0;$i<(8-$new_filename_short_lenght);$i++)
            {
                $new_filename=$new_filename.'0';
            }
            $new_filename=$new_filename.$new_filename_short;;
        }
        else
        {
           $new_filename='00000001'; 
        }
        return $new_filename;
    }
    
    function get_labels_tableTEST($masterstableid,$funzione,$recordid_=null,$userid=null)
    {
        $master_tableid=$masterstableid;
        $master_recordid=$recordid_;
        if($userid==null)
            $userid=$this->session->userdata ('userid');
        if($funzione=='ricerca')
        {
            $typepreference='campiricerca';
        }
        if($funzione=='inserimento')
        {
            $typepreference='campiInserimento';
        }
        if($funzione=='scheda')
        {
            $typepreference='campischeda';
        }
        if($funzione=='modifica')
        {
            $typepreference='campiInserimento';
        }
        $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql);
        if($fields_mastertable==null)
        {
           $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql); 
        }
        
        //$fields_mastertable=  $this->get_filledfields_table($masterstableid, 'null', $recordid_, $funzione, 'master');
        $fields_finali=array();
        $ordered_labels=$this->get_labels_ordered($masterstableid,$funzione,$userid);
        foreach ($ordered_labels as $ordered_label) {
            $label=$ordered_label['fieldid'];
            $fields_finali[$label]=null;
            $count_new_records=0;
            //custom Work&Work
            if(($master_recordid!='null')&&($master_recordid!=null)&&($master_tableid=='CANDID'))
            {
                $sql="SELECT tablelink FROM sys_field WHERE label='$label' AND tableid='$master_tableid'";
                $result=  $this->select($sql);
                if(count($result>0))
                {
                    $linked_tableid=$result[0]['tablelink'];
                    if(($linked_tableid!=null)&&($linked_tableid!=''))
                    {
                        $count_new_records=  $this->get_count_new_records($linked_tableid,$master_tableid,$master_recordid);
                    }

                }
            }
            if($count_new_records>0)
            {
                $fields_finali[$label]['status']='new';
            }
            else
            {
                $fields_finali[$label]['status']=''; 
            }
            $fields_finali[$label]['label']=$label;
            $fields_finali[$label]['tableid']=$masterstableid;
            $fields_finali[$label]['type']='master';
            $fields_finali[$label]['counter']=1;
        }
        
        return $fields_finali;
    }
    
    function get_labels_table($masterstableid,$funzione,$recordid=null,$userid=null)
    {   
        $return_labels=array();
        if($userid==null)
            $userid=$this->session->userdata ('userid');
        
        $singular_name=  $this->db_get_value("sys_table", "singular_name", "id='$masterstableid'");
        $ordered_labels=$this->get_labels_ordered($masterstableid,$funzione,$userid);
        foreach ($ordered_labels as $ordered_label) 
        {
            $labelname=$ordered_label['fieldid'];
            $return_labels[$labelname]=array(); 
            $row_label=  $this->db_get_row("sys_field", "*", "tableid='$masterstableid' AND label='$labelname'");
            
            $description=$labelname;
            if($description=='Dati')
            {
                $description="Dati ".$singular_name;
            }
            //default
            $return_labels[$labelname]['type']='master';
            $return_labels[$labelname]['label']=$labelname;   
            $return_labels[$labelname]['description']=$description;
            $return_labels[$labelname]['tableid']=$masterstableid;
            $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
            $return_labels[$labelname]['counter']=1;
            $return_labels[$labelname]['status']='';
            if(($row_label['tablelink']!=null)&&($row_label['keyfieldlink']==null))
            {
                if($funzione=='ricerca')
                {
                    $counter=1;
                    $labelname2=$labelname;
                }
                else
                {
                    $counter=  $this->db_get_count("user_".strtolower($labelname), "recordid".  strtolower($masterstableid)."_='$recordid' AND (recordstatus_ is null OR recordstatus_!='temp')");
                    $labelname2=$labelname." ($counter)";

                }
                $return_labels[$labelname]['type']='linked';
                $return_labels[$labelname]['label']=$labelname2; 
                $return_labels[$labelname]['description']=$row_label['description'];
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=$counter;
                $return_labels[$labelname]['status']='';
            }
            if(($row_label['tablelink']!=null)&&($row_label['keyfieldlink']!=null))
            {
                $return_labels[$labelname]['type']='linkedmaster';
                $return_labels[$labelname]['description']=$row_label['description'];
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=1;
                $return_labels[$labelname]['status']='';
            }
            $return_labels[$labelname]['linked_label_opened']=$this->get_table_setting($labelname,'linked_label_opened');
        }
        
        /*$sql="
            SELECT label,description
            FROM sys_field
            WHERE tableid='$masterstableid' AND label!='Old'
            GROUP BY label,description
            ";
        $labels =  $this->select($sql);
  
        foreach ($labels as $key => $label) 
        {
            $labelname=$label['label'];
            $description=$label['description'];
            if(array_key_exists($labelname, $return_labels))
            {
                $return_labels[$labelname]['type']='master';
                $return_labels[$labelname]['label']=$labelname;   
                $return_labels[$labelname]['description']=$description;
                $return_labels[$labelname]['tableid']=$masterstableid;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=1;
                $return_labels[$labelname]['status']='';
                
            }
        }
        
        $sql="
            SELECT tablelinkid
            FROM sys_table_link
            WHERE tableid='$masterstableid'
            ";
        
        $labels_linked=$this->select($sql);
        foreach ($labels_linked as $key => $label) 
        {
            $labelname=$label['tablelinkid'];
            if($funzione=='ricerca')
            {
                $counter=1;
                $labelname2=$labelname;
            }
            else
            {
                $counter=  $this->db_get_count("user_".strtolower($labelname), "recordid".  strtolower($masterstableid)."_='$recordid'");
                $labelname2=$labelname." ($counter)";
                
            }
            
            if(array_key_exists($labelname, $return_labels))
            {
                $return_labels[$labelname]['type']='linked';
                $return_labels[$labelname]['label']=$labelname2; 
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=$counter;
                $return_labels[$labelname]['status']='';
            }
        }
        
        $sql="
            SELECT tableid
            FROM sys_table_link
            WHERE tablelinkid='$masterstableid'
            ";
        $labels_linkedmaster=$this->select($sql);
        foreach ($labels_linkedmaster as $key => $label) 
        {
            $labelname=$label['tableid'];
            if(array_key_exists($labelname, $return_labels))
            {
                $linkedmaster_recordid=  $this->db_get_value('user_'.$masterstableid, "recordid".  strtolower($labelname)."_", "recordid_='$recordid'");
                $keyfieldlink=$this->get_keyfieldlink_value($masterstableid, $labelname, $linkedmaster_recordid);
                $return_labels[$labelname]['type']='linkedmaster';
                //$return_labels[$labelname]['label']=$labelname." ".$keyfieldlink; 
                $return_labels[$labelname]['label']=$label['tableid']."";
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=1;
                $return_labels[$labelname]['status']='';
            }
        }
        */
        
        return $return_labels;
    }
    
    function get_labels_tableBAK2($masterstableid,$funzione,$recordid=null,$userid=null)
    {   
        $return_labels=array();
        if($userid==null)
            $userid=$this->session->userdata ('userid');
        
        $ordered_labels=$this->get_labels_ordered($masterstableid,$funzione,$userid);
        foreach ($ordered_labels as $ordered_label) {
            $label=$ordered_label['fieldid'];
            $return_labels[$label]=array();      
        }
        
        $sql="
            SELECT label,description
            FROM sys_field
            WHERE tableid='$masterstableid' AND label!='Old'
            GROUP BY label,description
            ";
        $labels =  $this->select($sql);
  
        foreach ($labels as $key => $label) 
        {
            $labelname=$label['label'];
            $description=$label['description'];
            if(array_key_exists($labelname, $return_labels))
            {
                $return_labels[$labelname]['type']='master';
                $return_labels[$labelname]['label']=$labelname;   
                $return_labels[$labelname]['description']=$description;
                $return_labels[$labelname]['tableid']=$masterstableid;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=1;
                $return_labels[$labelname]['status']='';
                
            }
        }
        
        $sql="
            SELECT tablelinkid
            FROM sys_table_link
            WHERE tableid='$masterstableid'
            ";
        
        $labels_linked=$this->select($sql);
        foreach ($labels_linked as $key => $label) 
        {
            $labelname=$label['tablelinkid'];
            if($funzione=='ricerca')
            {
                $counter=1;
                $labelname2=$labelname;
            }
            else
            {
                $counter=  $this->db_get_count("user_".strtolower($labelname), "recordid".  strtolower($masterstableid)."_='$recordid'");
                $labelname2=$labelname." ($counter)";
                
            }
            
            if(array_key_exists($labelname, $return_labels))
            {
                $return_labels[$labelname]['type']='linked';
                $return_labels[$labelname]['label']=$labelname2; 
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=$counter;
                $return_labels[$labelname]['status']='';
            }
        }
        
        $sql="
            SELECT tableid
            FROM sys_table_link
            WHERE tablelinkid='$masterstableid'
            ";
        $labels_linkedmaster=$this->select($sql);
        foreach ($labels_linkedmaster as $key => $label) 
        {
            $labelname=$label['tableid'];
            if(array_key_exists($labelname, $return_labels))
            {
                $linkedmaster_recordid=  $this->db_get_value('user_'.$masterstableid, "recordid".  strtolower($labelname)."_", "recordid_='$recordid'");
                $keyfieldlink=$this->get_keyfieldlink_value($masterstableid, $labelname, $linkedmaster_recordid);
                $return_labels[$labelname]['type']='linkedmaster';
                //$return_labels[$labelname]['label']=$labelname." ".$keyfieldlink; 
                $return_labels[$labelname]['label']=$label['tableid']."";
                $return_labels[$labelname]['tableid']=$labelname;
                $return_labels[$labelname]['linkedmaster_recordid']=$recordid;
                $return_labels[$labelname]['counter']=1;
                $return_labels[$labelname]['status']='';
            }
        }
        
        
        return $return_labels;
    }
    
    function get_labels_tableBAK($masterstableid,$funzione,$recordid_=null,$userid=null)
    {
        $master_tableid=$masterstableid;
        $master_recordid=$recordid_;
        if($userid==null)
            $userid=$this->session->userdata ('userid');
        if($funzione=='ricerca')
        {
            $typepreference='campiricerca';
        }
        if($funzione=='inserimento')
        {
            $typepreference='campiInserimento';
        }
        if($funzione=='scheda')
        {
            $typepreference='campischeda';
        }
        if($funzione=='modifica')
        {
            $typepreference='campiInserimento';
        }
        $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql);
        if($fields_mastertable==null)
        {
           $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql); 
        }
        
        //$fields_mastertable=  $this->get_filledfields_table($masterstableid, 'null', $recordid_, $funzione, 'master');
        $view_post=$this->get_view_post($masterstableid,1);
        $fields_finali=array();
        $ordered_labels=$this->get_labels_ordered($masterstableid,$funzione,$userid);
        foreach ($ordered_labels as $ordered_label) {
            $label=$ordered_label['fieldid'];
            $fields_finali[$label]=null;
            $count_new_records=0;
            //custom Work&Work
            if(($master_recordid!='null')&&($master_recordid!=null)&&($master_tableid=='CANDID'))
            {
                $sql="SELECT tablelink FROM sys_field WHERE label='$label' AND tableid='$master_tableid'";
                $result=  $this->select($sql);
                if(count($result>0))
                {
                    $linked_tableid=$result[0]['tablelink'];
                    if(($linked_tableid!=null)&&($linked_tableid!=''))
                    {
                        $count_new_records=  $this->get_count_new_records($linked_tableid,$master_tableid,$master_recordid);
                    }

                }
            }
            if($count_new_records>0)
            {
                $fields_finali[$ordered_label['fieldid']]['status']='new';
            }
            else
            {
                $fields_finali[$ordered_label['fieldid']]['status']=''; 
            }
        }
        foreach ($fields_mastertable as $field) {
            if(array_key_exists($field['label'], $fields_finali))
            {
                if(($field['tablelink']==null)||($field['tablelink']==''))
                {
                    $label_key=$field['label'];

                    $fields_finali[$label_key]['label']=$field['label'];
                    $fields_finali[$label_key]['tableid']=$masterstableid;
                    $fields_finali[$label_key]['type']='master';
                    $fields_finali[$label_key]['counter']=1;
                    //$field['value']='';
                    //$fields_finali[$label_key]['fields'][]=$field;
                }
                else
                {   
                    if(($field['keyfieldlink']==null)||($field['keyfieldlink']==''))
                    {
                        //$fields_linkedtable=  $this->get_emptyfields_linkedtable_inserimento($field['tablelink']);
                        $label_key=$field['label'];
                        if($funzione=='scheda')
                        {
                            if($recordid_!='null')
                            {
                                $records=  $this->get_records_linkedtable($field['tablelink'], $masterstableid, $recordid_,$userid);
                                $fields_finali[$label_key]['counter']=  count($records);
                            }
                            else
                            {
                                $fields_finali[$label_key]['counter']=0;
                            }
                        }
                        else
                        {
                           $fields_finali[$label_key]['counter']= 1; 
                        }
                        $fields_finali[$label_key]['label']=$field['description'];
                        $fields_finali[$label_key]['tableid']=$field['tablelink'];
                        $fields_finali[$label_key]['type']='linked';
                       // $fields_finali[$label_key]['fields']=$fields_linkedtable;  
                    }
                    else
                    {
                         //$fields_linkedtable=  $this->get_emptyfields_linkedtable_inserimento($field['tablelink']);
                        $label_key=$field['label'];

                           
                           $keyfieldlink_value='';
                           //if(($funzione=='scheda')||($funzione=='modifica'))
                            // {
                                $linkedmaster_recordid= $this->get_linkedmaster_recordid($masterstableid,$recordid_,$field['tablelink']);
                                if(($linkedmaster_recordid!='null')&&($linkedmaster_recordid!='')&&($linkedmaster_recordid!=null))
                                {
                                    $keyfieldlink_value=  ': '.$this->get_keyfieldlink_value($masterstableid, $field['tablelink'], $linkedmaster_recordid);
                                }
                                $fields_finali[$label_key]['linkedmaster_recordid']=$linkedmaster_recordid;
                           //}
                        if($funzione=='scheda')
                        {
                            if($recordid_!='null')
                            {
                                if($keyfieldlink_value!='')
                                {
                                    $fields_finali[$label_key]['counter']= 1; 
                                }
                                else
                                {
                                    $fields_finali[$label_key]['counter']= 0;
                                }
                            }
                            else
                            {
                                $fields_finali[$label_key]['counter']=0;
                            }
                        }
                        else
                        {
                           $fields_finali[$label_key]['counter']= 1; 
                        }
                          $fields_finali[$label_key]['label']=$field['description'].$keyfieldlink_value;
                        $fields_finali[$label_key]['tableid']=$field['tablelink'];
                        $fields_finali[$label_key]['type']='linkedmaster';
                       // $fields_finali[$label_key]['fields']=$fields_linkedtable; 
                    }
                }
            }
        }
        return $fields_finali;
    }
    
    function get_labels_table_invio_mail_modulo($masterstableid,$funzione,$recordid_=null,$userid=null)
    {
        $master_tableid=$masterstableid;
        $master_recordid=$recordid_;
        if($userid==null)
            $userid=$this->session->userdata ('userid');
        if($funzione=='ricerca')
        {
            $typepreference='campiricerca';
        }
        if($funzione=='inserimento')
        {
            $typepreference='campiInserimento';
        }
        if($funzione=='scheda')
        {
            $typepreference='campischeda';
        }
        if($funzione=='modifica')
        {
            $typepreference='campiInserimento';
        }
        $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql);
        if($fields_mastertable==null)
        {
           $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$masterstableid' AND sys_field.label!='Old'
            ";
        $fields_mastertable =  $this->select($sql); 
        }
        
        //$fields_mastertable=  $this->get_filledfields_table($masterstableid, 'null', $recordid_, $funzione, 'master');
        $fields_finali=array();
        //$ordered_labels=$this->get_labels_ordered($masterstableid,$funzione,$userid);
        $ordered_labels[0]['fieldid']='Fissi';
        $ordered_labels[1]['fieldid']='Anagrafica';
        $ordered_labels[2]['fieldid']='candmail';
        $ordered_labels[3]['fieldid']='SKILL';
        foreach ($ordered_labels as $ordered_label) {
            $label=$ordered_label['fieldid'];
            $fields_finali[$label]=null;
            $count_new_records=0;
            //custom Work&Work
            if(($master_recordid!='null')&&($master_recordid!=null)&&($master_tableid=='CANDID'))
            {
                $sql="SELECT tablelink FROM sys_field WHERE label='$label' AND tableid='$master_tableid'";
                $result=  $this->select($sql);
                if(count($result>0))
                {
                    $linked_tableid=$result[0]['tablelink'];
                    if(($linked_tableid!=null)&&($linked_tableid!=''))
                    {
                        $count_new_records=  $this->get_count_new_records($linked_tableid,$master_tableid,$master_recordid);
                    }

                }
            }
            if($count_new_records>0)
            {
                $fields_finali[$ordered_label['fieldid']]['status']='new';
            }
            else
            {
                $fields_finali[$ordered_label['fieldid']]['status']=''; 
            }
        }
        foreach ($fields_mastertable as $field) {
            if(array_key_exists($field['label'], $fields_finali))
            {
                if(($field['tablelink']==null)||($field['tablelink']==''))
                {
                    $label_key=$field['label'];

                    $fields_finali[$label_key]['label']=$field['label'];
                    $fields_finali[$label_key]['tableid']=$masterstableid;
                    $fields_finali[$label_key]['type']='master';
                    
                    $fields_finali[$label_key]['counter']=1;
                    $field['value']='';
                    $fields_finali[$label_key]['fields'][]=$field;
                }
                else
                {   
                    if(($field['keyfieldlink']==null)||($field['keyfieldlink']==''))
                    {
                         //$fields_linkedtable=  $this->get_emptyfields_linkedtable_inserimento($field['tablelink']);
                        $label_key=$field['label'];
                        if($funzione=='scheda')
                        {
                            if($recordid_!='null')
                            {
                                $records=  $this->get_records_linkedtable($field['tablelink'], $masterstableid, $recordid_,$userid);
                                $fields_finali[$label_key]['counter']=  count($records);
                            }
                            else
                            {
                                $fields_finali[$label_key]['counter']=0;
                            }
                        }
                        else
                        {
                           $fields_finali[$label_key]['counter']= 1; 
                        }
                        $fields_finali[$label_key]['label']=$field['description'];
                        $fields_finali[$label_key]['tableid']=$field['tablelink'];
                        $fields_finali[$label_key]['type']='linked';
                       // $fields_finali[$label_key]['fields']=$fields_linkedtable;  
                    }
                    else
                    {
                         //$fields_linkedtable=  $this->get_emptyfields_linkedtable_inserimento($field['tablelink']);
                        $label_key=$field['label'];

                           
                           $keyfieldlink_value='';
                           //if(($funzione=='scheda')||($funzione=='modifica'))
                            // {
                                $linkedmaster_recordid= $this->get_linkedmaster_recordid($masterstableid,$recordid_,$field['tablelink']);
                                if(($linkedmaster_recordid!='null')&&($linkedmaster_recordid!='')&&($linkedmaster_recordid!=null))
                                {
                                    $keyfieldlink_value=  ': '.$this->get_keyfieldlink_value($masterstableid, $field['tablelink'], $linkedmaster_recordid);
                                }
                                $fields_finali[$label_key]['linkedmaster_recordid']=$linkedmaster_recordid;
                           //}
                        if($funzione=='scheda')
                        {
                            if($recordid_!='null')
                            {
                                if($keyfieldlink_value!='')
                                {
                                    $fields_finali[$label_key]['counter']= 1; 
                                }
                                else
                                {
                                    $fields_finali[$label_key]['counter']= 0;
                                }
                            }
                            else
                            {
                                $fields_finali[$label_key]['counter']=0;
                            }
                        }
                        else
                        {
                           $fields_finali[$label_key]['counter']= 1; 
                        }
                          $fields_finali[$label_key]['label']=$field['description'].$keyfieldlink_value;
                        $fields_finali[$label_key]['tableid']=$field['tablelink'];
                        $fields_finali[$label_key]['type']='linkedmaster';
                       // $fields_finali[$label_key]['fields']=$fields_linkedtable; 
                    }
                }
            }
        }
        return $fields_finali;
    }
    
    public function get_count_new_records($linked_tableid,$master_tableid,$master_recordid)
    {
        $sql="SELECT * FROM user_".  strtolower($linked_tableid)." WHERE recordid".  strtolower($master_tableid)."_='$master_recordid' AND recordstatus_='new'";
        $result=  $this->select($sql);
        return count($result);
    }
    
    function get_labels_ordered($tableid='',$funzione='',$userid)
    {        
        if($funzione=='ricerca')
        {
            $typepreference='keylabel';
        }
        if($funzione=='inserimento')
        {
            $typepreference='keylabel_inserimento';
        }
        if($funzione=='scheda')
        {
            $typepreference='keylabel_scheda';
        }
        if($funzione=='modifica')
        {
            //$typepreference='keylabel_inserimento';
            $typepreference='keylabel_inserimento';
        }
        
        $sql="
            SELECT fieldid
            FROM sys_user_order
            WHERE typepreference='$typepreference' AND tableid='$tableid' AND userid=$userid
            ORDER BY fieldorder
            ";
        $return= $this->select($sql);
        if($return==null)
        {
           $sql="
            SELECT fieldid
            FROM sys_user_order
            WHERE typepreference='$typepreference' AND tableid='$tableid' AND userid=1
            ORDER BY fieldorder
            ";
        $return= $this->select($sql); 
        }
        if($return==null)
        {
           $sql="
            SELECT fieldid
            FROM sys_user_order
            WHERE typepreference='keylabel_inserimento' AND tableid='$tableid' AND userid=$userid
            ORDER BY fieldorder
            ";
        $return= $this->select($sql); 
        }
        if($return==null)
        {
           $sql="
            SELECT fieldid
            FROM sys_user_order
            WHERE typepreference='keylabel_inserimento' AND tableid='$tableid' AND userid=1
            ORDER BY fieldorder
            ";
        $return= $this->select($sql); 
        }
        if($return==null)
        {
           $sql="
            SELECT label as fieldid
            FROM sys_field
            WHERE tableid='$tableid'
            GROUP BY label    
            ORDER BY label
            ";
        $return= $this->select($sql); 
        }
        
        return $return;
        
        
    }
    
    function get_labels_ordered2($tableid='')
    {        
        $sql="
            SELECT sys_user_order.fieldid,sys_field.label,sys_field.description, sys_field.tablelink,sys_field.keyfieldlink,sys_user_order.tableid
            FROM sys_user_order join sys_field on sys_user_order.fieldid=sys_field.label
            WHERE typepreference='keylabel' AND sys_user_order.tableid='$tableid' AND userid=1
            group by sys_user_order.fieldid
            ORDER BY sys_user_order.fieldorder
         ";
        $rows= $this->select($sql);  
        $return=array();
        foreach ($rows as $key => $row) 
        {
            if($row['tablelink']==null)
            {
                $return_el['label']=$row['label'];
                $return_el['type']='master';
                $return_el['tableid']=$tableid;
            }
            elseif ($row['keyfieldlink']==null) {
                $return_el['label']=$row['description'];
                $return_el['type']='linked';
                $return_el['tableid']=$row['tablelink'];
            }
            else
            {
                $return_el['label']=$row['description'];
                $return_el['type']='linkedmaster';
                $return_el['tableid']=$row['tablelink'];
            }
            $return[]=$return_el;
        }
        return $return;        
    }
    
    
    /**
     * Genera e restituisce la query in base al form di ricerca
     * 
     * @param String $idmaster id della tabella principale su cui si sta facendo la ricerca
     * @param Array $post valori del form di ricerca
     * @return string Query generata
     * @author Alessandro Galli
     * 
     * @mobile
     */
    function get_search_query($idmaster,$post=array(),$query_conditions='')
    {
        //var_dump($post);
        $dbdriver=$this->get_dbdriver();
        $limit=null;
        $userid=$this->get_userid();
        if($userid==0)
        {
            $userid=1;
        }
        $settings=  $this->get_settings();
        $cliente_id=$settings['cliente_id'];
        $like= $this->get_like();
        /*$db_driver=  $this->get_dbdriver();
        $like='like';
        if($db_driver=='postgre')
            $like='ilike';*/
        $tablemaster='user_'.strtolower($idmaster);

        //CUSTOM WORK&WORK
        $select="";
        $from="";
        $where="";
        // TEMP
        if($idmaster=='CANDIDtemp')
        {
            $select="SELECT user_candid.recordid_,user_candid.recordstatus_,user_candid.id,disp.statodisp,disp.wwws,coll.validato,user_candid.pflash,user_candid.cognome,user_candid.nome,'temp' as qualifica,coll.giudizio,user_candid.consulente,user_candid.datanasc";        
            $from=" 
                FROM user_candid
                LEFT JOIN    (
                            SELECT    MAX(recordid_) as recordid_max,recordidcandid_
                            FROM      user_canddisponibilita 
                            GROUP BY  recordidcandid_
                        ) as disp_max ON (user_candid.recordid_ = disp_max.recordidcandid_)
                LEFT JOIN user_canddisponibilita as disp ON (disp.recordid_ = disp_max.recordid_max)
                LEFT JOIN    (
                            SELECT    MAX(recordid_) as recordid_max,recordidcandid_
                            FROM      user_candcolloquio
                            GROUP BY  recordidcandid_
                        ) as coll_max ON (user_candid.recordid_ = coll_max.recordidcandid_)
                LEFT JOIN user_candcolloquio as coll ON (coll.recordid_ = coll_max.recordid_max)
                ";
        }
        else
        {
            if($idmaster=='AZIEND')
            {
                $select="SELECT user_aziend.recordid_,user_aziend.recordstatus_,user_aziend.id,user_aziend.ragsoc,user_azrecapiti.distretto,'temp' as settore,user_aziend.aziendastato,user_aziend.consulente";        
                $from=" 
                    FROM user_aziend
                    LEFT JOIN    (
                                SELECT    MAX(recordid_) as recordid_max,recordidaziend_
                                FROM      user_azrecapiti 
                                GROUP BY  recordidaziend_
                            ) as azrecapiti_max ON (user_aziend.recordid_ = azrecapiti_max.recordidaziend_)
                    LEFT JOIN user_azrecapiti ON (user_azrecapiti.recordid_ = azrecapiti_max.recordid_max)
                ";
            }
            else
            {
                //$select_colums=  $this->get_preference_colums($idmaster, $this->session->userdata('idutente'));
                $select_columns= $this->get_colums($idmaster, $userid);
                $select="SELECT";
                foreach ($select_columns as $key => $select_column) 
                {
                    if($select!='SELECT')
                    {
                        $select=$select.',';
                    }
                    $columnid=$select_column['id'];
                    if($columnid=='recordcss_')
                    {
                        $columnid=" '' as recordcss_";
                    }
                    $select=$select." ".$columnid;
                }
                $from=" FROM ".$tablemaster;
            }
        }
        $where=" WHERE true AND (recordstatus_ is null OR recordstatus_!='temp') ";
        $sql_finale='';
        //CUSTOM ABOUT
        /*if(($cliente_id=='About-x')&&($idmaster=='telemarketing')&&(count($post)==0))
        {
            $oggi=date('Y-m-d');
            $where=$where." AND recalldate is not null AND recalldate::date<='$oggi' AND (statotelemarketing='incorso' OR statotelemarketing='noniniziata')";
        }*/
        $counter=0;
        if(array_key_exists('tables', $post))
        {
            foreach ($post['tables'] as $tableid => $table_container) 
            {
                if(array_key_exists('search', $table_container))
                {
                    $first_table=reset($table_container['search']);
                    if(array_key_exists('fields', $first_table))
                    {
                        
                        $tablename='user_'.strtolower($tableid);
                        foreach($table_container['search'] as $table) 
                        {
                            $table_param=$table['table_param'];
                            if(($table_param=='and')||($table_param==''))
                            {
                                $where=$where." AND $tablemaster.recordid_ in(";
                            }
                            if($table_param=='or')
                            {
                                $table_param='UNION (';
                            }
                            
                            if($table_param=='showall')
                            {
                                $table_param='';
                            }

                            $table_type=$table['table_type'];
                            if($table_type=='master')
                            {
                                $sub_select=" SELECT recordid_";
                                $sub_from=" FROM ".$tablename;
                            }
                            if($table_type=='linked')
                            {
                                $sub_select=" SELECT recordid".strtolower($idmaster)."_";
                                $sub_from=" FROM ".$tablename;
                            }
                            if($table_type=='linkedmaster')
                            {
                                $sub_select='SELECT recordid_ FROM user_'.strtolower($idmaster).' WHERE recordid'.strtolower($tableid).'_ in (SELECT recordid_';
                                $sub_from=" FROM ".$tablename;
                            }
                            if($table_type=='ocr')
                            {
                                $sub_select='SELECT recordid_ ' ;
                                $sub_from='FROM user_'.strtolower($idmaster).'_page';
                            }
                            $sub_where=" WHERE true";
                            if(key_exists('fields', $table))
                            {  
                            foreach ($table['fields'] as $fieldid=>$fields_container)
                            {
                                if(($fields_container['f_0']['value'][0]!='')||($fields_container['f_0']['param']))
                                {
                                $sub_where=$sub_where." AND (";
                                foreach ($fields_container as $key => $field) 
                                {
                                    $value=$field['value'];
                                    $value[0]=  str_replace("'", "''", $value[0]);
                                    if(array_key_exists(1, $value))
                                    {
                                        $value[1]=  str_replace("'", "''", $value[1]);
                                    }
                                    else
                                    {
                                        $value[1]='';
                                    }
                                    $param=$field['param'];
                                    $operator=$field['operator'];
                                    $type=$field['type'];
                                    if(($value[0]!='')||($value[1]!='')||($this->isnotempty($param)))
                                    {
                                        if($operator=='or')
                                        {
                                            $sub_where=$sub_where." OR"; 
                                        }
                                        if($param=='notnull')
                                        {
                                            $sub_where=$sub_where." ($fieldid is not null && $fieldid <> '')"; 
                                        }
                                        if($param=='null')
                                        {
                                            $sub_where=$sub_where." $fieldid is null"; 
                                        }
                                        if($param=='currentuser')
                                        {
                                            //$userid=$this->get_userid();
                                            $sub_where=$sub_where." $fieldid = '".'$userid$'."'"; 
                                        }
                                        if($param=='today')
                                        {
                                            $today=date('Y-m-d');
                                            $sub_where=$sub_where." $fieldid = current_date"; 
                                        }
                                        if($param=='past')
                                        {
                                            $sub_where=$sub_where." $fieldid < current_date"; 
                                        }
                                        if($param=='future')
                                        {
                                            $sub_where=$sub_where." $fieldid > current_date"; 
                                        }
                                        if($param=='currentweek')
                                        {
                                            $monday = strtotime("last monday");
                                            $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;

                                            $sunday = strtotime(date("Y-m-d",$monday)." +4 days");

                                            $this_week_sd = date("Y-m-d",$monday);
                                            $this_week_ed = date("Y-m-d",$sunday);
                                            $sub_where=$sub_where." ($fieldid >= '$this_week_sd' AND $fieldid <='$this_week_ed')"; 
                                        }
                                        if($param=='currentmonth')
                                        {
                                            $dt = strtotime(date("Y-m-d"));
                                            $this_month_sd = date('Y-m-d', strtotime('first day of this month', $dt));
                                            $this_month_ed = date('Y-m-d', strtotime('last day of this month', $dt));
                                            $sub_where=$sub_where." ($fieldid >= '$this_month_sd' AND $fieldid <='$this_month_ed')"; 
                                        }
                                        if($param=='nextday')
                                        {
                                            $day_num=$value['hidden'];
                                            $sub_where=$sub_where." ($fieldid > current_date AND $fieldid < current_date + interval '$day_num day')"; 
                                        }
                                        if($param=='prevday')
                                        {
                                            $day_num=$value['hidden'];
                                            if($dbdriver=='postgre')
                                            {
                                                $sub_where=$sub_where." ($fieldid < current_date AND $fieldid > current_date - interval '$day_num day')"; 
                                            }
                                            if($dbdriver=='mysqli')
                                            {
                                                $sub_where=$sub_where." ($fieldid < current_date AND $fieldid > DATE_SUB(current_date,INTERVAL $day_num day))"; 
                                            }
                                                
                                        }
                                        if($param=='nextmonth')
                                        {
                                            $month_num=$value['hidden'];
                                            $sub_where=$sub_where." ($fieldid > current_date AND $fieldid < current_date + interval '$month_num month')"; 
                                        }
                                        if($param=='prevmonth')
                                        {
                                            $month_num=$value['hidden'];
                                            $sub_where=$sub_where." ($fieldid < current_date AND $fieldid > current_date - interval '$month_num month')"; 
                                        }
                                        if($param=='overpastday')
                                        {
                                            $day_num=$value['hidden'];
                                            $sub_where=$sub_where." (current_timestamp - to_timestamp(datalast::text, 'YYYY-MM-DD') > interval '$day_num day')"; 
                                        }
                                        if($type=='parola-testolibero')
                                        {
                                            if($param=='')
                                            {
                                                $sub_where=$sub_where." $fieldid $like '%$value[0]%'"; 
                                            }

                                            if($param=='not')
                                            {
                                                $sub_where=$sub_where." $fieldid not $like '%$value[0]%' OR $fieldid is null "; 
                                            }

                                        }
                                        if($type=='parola-lookup')
                                        {
                                            if($param=='')
                                            {
                                                $sub_where=$sub_where." $fieldid = '$value[0]' OR $fieldid like '%|;|$value[0]' OR $fieldid like '$value[0]|;|%' OR $fieldid like '%|;|$value[0]|;|%'"; 
                                                //$sub_where=$sub_where." $fieldid = '$value[0]'";
                                                //CUSTOM WW
                                                if($cliente_id=='Work&Work')
                                                {
                                                    if(($field=='parlato')||($field=='letto')||($fieldid='scritto'))
                                                    {
                                                        if($value[0]=='S')
                                                            $sub_where=$sub_where." OR parlato ilike 'B' OR parlato ilike 'O' OR parlato ilike 'M'";
                                                        if($value[0]=='B')
                                                            $sub_where=$sub_where." OR parlato ilike 'O' OR parlato ilike 'M'";
                                                        if($value[0]=='O')
                                                            $sub_where=$sub_where." OR parlato ilike 'M'";
                                                    }
                                                }
                                            }
                                            if($param=='not')
                                            {
                                                $sub_where=$sub_where." $fieldid not $like '$value[0]' OR $fieldid is null"; 
                                            }
                                        }
                                        if($type=='numero')
                                        {
                                            $field_from=$value[0];
                                            $field_to=$value[1];

                                            if($param=='')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_from AND $fieldid <= $field_to"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_from"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_to"; 
                                                } 
                                            }

                                            if($param=='not')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_from OR $fieldid >= $field_to OR $fieldid is null"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_from OR $fieldid is null"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_to OR $fieldid is null"; 
                                                }  
                                            }
                                        }
                                        if($type=='data')
                                        {

                                            $field_from=$value[0];
                                            $field_to=$value[1];
                                            if($param=='')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                    $date_from = date('Y-m-d', strtotime($field_from));
                                                    $date_to = date('Y-m-d', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid >= '$date_from' AND $fieldid <= '$date_to'"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                    $date_from = date('Y-m-d', strtotime($field_from));
                                                    $sub_where=$sub_where." $fieldid >= '$date_from'"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                    $date_to = date('Y-m-d', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid <= '$date_to'"; 
                                                } 
                                            }

                                            if($param=='not')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                    $date_from = date('Y-m-d', strtotime($field_from));
                                                    $date_to = date('Y-m-d', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid <= '$date_from' OR $fieldid >= '$date_to' OR $fieldid is null"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                    $date_from = date('Y-m-d', strtotime($field_from));
                                                    $sub_where=$sub_where." $fieldid <= '$date_from' OR $fieldid is null"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                    $date_to = date('Y-m-d', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid >= '$date_to' OR $fieldid is null"; 
                                                }  
                                            }

                                        }
                                        if($type=='ora')
                                        {
                                            //CUSTOM POSTGRES
                                            $field_from=$value[0];
                                            $field_to=$value[1];
                                            if($param=='')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                    $time_from = date('H:i', strtotime($field_from));
                                                    $time_to = date('H:i', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid >= '$time_from' AND $fieldid <= '$time_to'"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                    $time_from = date('H:i', strtotime($field_from));
                                                    $sub_where=$sub_where." $fieldid >= '$time_from'"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                    $time_to = date('H:i', strtotime($to));
                                                    $sub_where=$sub_where." $fieldid <= '$time_to'"; 
                                                } 
                                            }

                                            if($param=='not')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                    $time_from = date('H:i', strtotime($field_from));
                                                    $time_to = date('H:i', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid <= '$time_from' OR $fieldid >= '$time_to' OR $fieldid is null"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                    $time_from = date('H:i', strtotime($field_from));
                                                    $sub_where=$sub_where." $fieldid <= '$time_from' OR $fieldid is null"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                    $time_to = date('H:i', strtotime($field_to));
                                                    $sub_where=$sub_where." $fieldid >= '$time_to' OR $fieldid is null"; 
                                                }  
                                            }
                                        }
                                        if($type=='memo')
                                        {
                                            if($param=='')
                                            {
                                                $sub_where=$sub_where." $fieldid $like '%$value[0]%'"; 
                                            }

                                            if($param=='not')
                                            {
                                                $sub_where=$sub_where." $fieldid not $like '%$value[0]%' OR $fieldid is null"; 
                                            }
                                        }
                                        if($type=='seriale')
                                        {
                                            $field_from=$value[0];
                                            $field_to=$value[1];

                                            if($param=='')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_from AND $fieldid <= $field_to"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_from"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_to"; 
                                                } 
                                            }

                                            if($param=='not')
                                            {
                                                if(($field_from!='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_from OR $fieldid >= $field_to OR $fieldid is null"; 
                                                }
                                                if(($field_from!='')&&($field_to==''))
                                                {
                                                     $sub_where=$sub_where." $fieldid <= $field_from OR $fieldid is null"; 
                                                }
                                                if(($field_from=='')&&($field_to!=''))
                                                {
                                                     $sub_where=$sub_where." $fieldid >= $field_to OR $fieldid is null"; 
                                                }  
                                            }
                                        }
                                        if($type=='utente')
                                        {
                                            if($param=='')
                                            {
                                                $sub_where=$sub_where." ($fieldid = '$value[0]' OR $fieldid $like '$value[0]|;|%' OR $fieldid $like '%|;|$value[0]' OR $fieldid $like '%|;|$value[0]|;|%') "; 
                                            }
                                            if($param=='not')
                                            {
                                                $sub_where=$sub_where." $fieldid != '$value[0]' OR $fieldid is null"; 
                                            }

                                        }
                                    }
                                }
                                //if(sizeof($fields_container)>1)
                               // {
                                $sub_where=$sub_where.")";
                               // }
                            
                            }
                            }
                        }
                            $sub_query=$sub_select.$sub_from.$sub_where;
                            if($table_type=='linkedmaster')
                            {
                               $sub_query=$sub_query.')'; 
                            }
                            $where=$where."                      $sub_query                              ";
                            
                        
                            $where=$where.")";
                        }
                    
                    }
                    
                }
                if(array_key_exists('tutti', $table_container))
                {
                    $value=$table_container['tutti']['t_1']['fields']['tutti']['f_0']['value'][0];
                    if($value!='')
                    {
                    $where=$where." AND $tablemaster.recordid_ in(";
                    $sub_select=" SELECT recordid_";
                    $sub_from=" FROM ".$tablemaster;   
                    $sub_where=" WHERE";
                    $campi=  $this->get_all_emptyfields_linkedtable($idmaster);
                    foreach ($campi as $key => $campo) 
                    {
                        if($key!=0)
                        {
                           $sub_where=$sub_where.' OR '; 
                        }
                        $fieldid=$campo['fieldid'];
                        $sub_where=$sub_where." CAST($fieldid as TEXT)  $like '%$value%'"; 
                       /* if(($campo['lookuptableid']!=null)&&($campo['lookuptableid']!='')&&($cliente_id=='Work&Work'))
                        {
                            $sub_where=$sub_where." OR ".$fieldid."_ ilike '%$value%'";
                        }*/
                    }
                    $sub_query=$sub_select.$sub_from.$sub_where;
                    $where=$where." $sub_query";
                    $linkedtables=  $this->get_linkedtables($idmaster);
                    foreach ($linkedtables as $key => $linkedtable) 
                    {
                        $where=$where.' UNION ';
                        $sub_select=" SELECT recordid".strtolower($idmaster)."_";
                        $sub_from=" FROM user_".strtolower($linkedtable);
                        $sub_where=" WHERE";
                        $campi=  $this->get_all_emptyfields_linkedtable($linkedtable);
                        foreach ($campi as $key => $campo) 
                        {
                            if($key!=0)
                            {
                               $sub_where=$sub_where.' OR '; 
                            }
                            $fieldid=$campo['fieldid'];
                            $sub_where=$sub_where." CAST($fieldid as TEXT)  $like '%$value%'"; 
                            /*if(($campo['lookuptableid']!=null)&&($campo['lookuptableid']!='')&&($cliente_id=='Work&Work'))
                            {
                                $sub_where=$sub_where." OR ".$fieldid."_ ilike '%$value%'";
                            }*/
                        }
                        $sub_query=$sub_select.$sub_from.$sub_where;
                        $where=$where." $sub_query";
                    }
                    $where=$where.")";
                }
                }
            }
            
        }

            if(array_key_exists('more_conditions', $post))
            {
                $where=$where.' AND ('.$post['more_conditions'].')';
            }
            if($query_conditions!='')
            {
                $where=$where." AND ($query_conditions)";
            }
        //$view_where=$where;
        //$where=str_replace('$userid$', $this->get_userid(), $where);    
        $sql_finale=$select.$from.$where;
        if($limit!=null)
        {
         $sql_finale=$sql_finale." LIMIT $limit";  
        }
        $tablemaster_owner=$tablemaster."_owner";
        $sql_finale_owner="select risultati.*"
                . " FROM (".$sql_finale.")"
                . " AS risultati LEFT JOIN $tablemaster_owner ON risultati.recordid_=$tablemaster_owner.recordid_"
                . " where ownerid_ is null OR ownerid_=$userid";
        if($idmaster=='presenzemensili')
        {
            $sql_finale=$sql_finale." AND (alias is null OR alias='')";
            $sql_finale_owner=$sql_finale;
        }
        $return['query_owner']=$sql_finale_owner;
        $return['query']=$sql_finale;
        $return['query_where']=$where;
        //$return['view_where']=$view_where;
        
        
        return $return;

    }
    
    function get_smartsearch_query($search_tableid,$tables)
    {
        $userid= $this->get_userid();
        $like= $this->get_like();
        $sql='';
        
        
        $select_columns= $this->get_colums($search_tableid, $userid);
        $select="SELECT";
        foreach ($select_columns as $key => $select_column) 
        {
            if($select!='SELECT')
            {
                $select=$select.',';
            }
            $columnid=$select_column['id'];
            if($columnid=='recordcss_')
            {
                $columnid=" '' as recordcss_";
            }
            $select=$select." ".$columnid;
        }
        $from=" FROM user_". strtolower($search_tableid);
        $query=$select." ".$from;
        $where=' WHERE true';
        $tableid='';
        foreach ($tables as $tableid => $table) 
        {
            $table_type=$table['type'];
            if($table_type!='custom')
            {
                if($table_type=='master')
                {
                    $subquery='SELECT recordid_ FROM user_'. strtolower($tableid);
                }
                if($table_type=='linkedmaster')
                {
                    $subquery='SELECT recordid_ FROM user_'. strtolower($tableid);
                }
                if($table_type=='linked')
                {
                    $subquery='SELECT recordid'.$search_tableid.'_ FROM user_'. strtolower($tableid);
                }

                $subwhere=' WHERE true';
                $fields=$table['fields'];
                $fields_counter=0;
                foreach ($fields as $fieldid => $field) {
                    $field_value=$field['value'];
                    $field_value=str_replace("'", "''", $field_value);
                    $field_type=$field['type'];
                    if(isnotempty($field_value[0]))
                    {
                        $fields_counter++;
                        if($field_type=='Parola')
                        {
                            $field_value=$field_value[0];
                            $subwhere=$subwhere." AND $fieldid $like '%$field_value%'";
                        }
                        if($field_type=='Utente')
                        {
                            $field_value=$field_value[0];
                            $subwhere=$subwhere." AND ($fieldid = '$field_value' OR $fieldid like '%|;|$field_value' OR $fieldid like '$field_value|;|%' OR $fieldid like '%|;|$field_value|;|%')";
                        }
                        if($field_type=='Lookup')
                        {
                            $field_value=$field_value[0];
                            $subwhere=$subwhere." AND $fieldid = '$field_value'";
                        }
                        if($field_type=='Numero')
                        {
                            $field_value=$field_value[0];
                            $subwhere=$subwhere." AND $fieldid=$field_value";
                        }
                        if($field_type=='Data')
                        {
                            $field_value_dal=$field_value[0];
                            $field_value_al=$field_value[1];
                            $subwhere=$subwhere." AND ($fieldid>='$field_value_dal' AND $fieldid<='$field_value_al')";
                        }

                    }
                }
                $subquery=$subquery." ".$subwhere;

                if($table_type=='master')
                {
                    $where=$where." AND recordid_ IN(".$subquery.")";
                }
                if(($table_type=='linkedmaster')&&($fields_counter>0))
                {
                    $where=$where." AND recordid".$tableid."_ IN(".$subquery.")";
                }
                if(($table_type=='linked')&&($fields_counter>0))
                {
                    $where=$where." AND recordid_ IN(".$subquery.")";
                }
            }
            else
            {
                if(($search_tableid=='presenzemensili')&&($tableid=='azienda'))
                {
                    $ragionesociale=$table['ragionesociale'];
                    if(isnotempty($ragionesociale))
                    {
                        $subquery="
                            SELECT recordidpresenzemensili_
                            FROM user_rapportidilavoro JOIN user_azienda on user_rapportidilavoro.recordidazienda_=user_azienda.recordid_
                            WHERE user_azienda.ragionesociale like '%$ragionesociale%'
                            ";
                        $where=$where." AND recordid_ IN(".$subquery.")";
                    }
                }
            }
            
        }
        
        $sql=$select." ".$from." ".$where;
        if($tableid=='rapportidilavoro')
        {
            $sql=$sql." AND (alias is null OR alias='')";
        }
        return $sql;
    }
    
    function get_customsearch_query($search_tableid,$tables,$custom_fields)
    {
        $customsearch_query= $this->get_smartsearch_query($search_tableid, $tables);
        if($search_tableid=='tempcontrol')
        {
            $datadal=$custom_fields['datadal'];
            $dataal=$custom_fields['dataal'];
            $nomeccl=$custom_fields['nomeccl'];
            if(isnotempty($datadal)&& isnotempty($dataal))
            {
           
            $customsearch_query=$customsearch_query."
                AND recordidcontratti_ IN(
                SELECT recordid_
                FROM  
                (
                SELECT *,datainizio as 'datainizioeffettiva',if(tipocontratto='indet',if(datadisdetta is null,'2100-01-01',datadisdetta),if(datadisdetta<datafine,datadisdetta,datafine) ) as 'datafineeffettiva' 
                FROM user_contratti  
                ) as supporto
                WHERE

                    ((datainizioeffettiva<='$datadal') AND (datafineeffettiva>='$datadal') )
                        OR
                        ((datainizioeffettiva>='$datadal') AND (datainizioeffettiva<='$dataal') )


                )
                ";
            }
            if(isnotempty($nomeccl))
            {
                $nomeccl= str_replace("'", "''", $nomeccl);
               $customsearch_query=$customsearch_query."
                AND recordidcontratti_ IN(
                SELECT user_contratti.recordid_
                FROM user_contratti JOIN user_ccl ON user_ccl.recordid_=user_contratti.recordidccl_
                WHERE user_ccl.nomeccl like '%$nomeccl%'
                )
                "; 
            }
        }
        
        if($search_tableid=='presenzemensili')
        {
            $colore=$custom_fields['colore'];
            if(isnotempty($colore))
            {
            
                $customsearch_query=$customsearch_query."
                AND 
                (
                g1d like '%#$colore%' OR
                g2d like '%#$colore%' OR
                g3d like '%#$colore%' OR
                g4d like '%#$colore%' OR
                g5d like '%#$colore%' OR
                g6d like '%#$colore%' OR
                g7d like '%#$colore%' OR
                g8d like '%#$colore%' OR
                g9d like '%#$colore%' OR
                g10d like '%#$colore%' OR
                g11d like '%#$colore%' OR
                g12d like '%#$colore%' OR
                g13d like '%#$colore%' OR
                g14d like '%#$colore%' OR
                g15d like '%#$colore%' OR
                g16d like '%#$colore%' OR
                g17d like '%#$colore%' OR
                g18d like '%#$colore%' OR
                g19d like '%#$colore%' OR
                g20d like '%#$colore%' OR
                g21d like '%#$colore%' OR
                g22d like '%#$colore%' OR
                g23d like '%#$colore%' OR
                g24d like '%#$colore%' OR
                g25d like '%#$colore%' OR
                g26d like '%#$colore%' OR
                g27d like '%#$colore%' OR
                g28d like '%#$colore%' OR
                g29d like '%#$colore%' OR
                g30d like '%#$colore%' OR
                g31d like '%#$colore%'
                )
                ";
            }
        }
        
        if($search_tableid=='richiestericercapersonale')
        {
            
            if(array_key_exists('stato', $custom_fields))
            {
                $stato="stato='' ";
                if(array_key_exists('preventivi', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Preventivo'";
                }
                if(array_key_exists('attivi', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Attivo'";
                } 
                if(array_key_exists('sospesi', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Sospeso'";
                }  
                if(array_key_exists('chiusi', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Chiuso'";
                }
                if(array_key_exists('chiusivinti', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Chiuso vinto'";
                }
                if(array_key_exists('chiusipersi', $custom_fields['stato']))
                {
                    $stato=$stato." OR stato='Chiuso perso'";
                }
                $customsearch_query=$customsearch_query."
                AND ($stato)
                ";
            }
            
            
        }
        
        return $customsearch_query;
    }
    
    
    /**
     * 
     * @param type $linkedtableid
     * @param type $master_tableid
     * @param type $master_recordid
     * @return type
     * @author Alessandro Galli
     */
    function get_records_linkedtable($linkedtableid,$master_tableid,$master_recordid,$userid=null)
    {
        if($userid==null)
            $userid=$this->session->userdata('idutente');
        $linkedtable='user_'.strtolower($linkedtableid);
        $master_recordid_column=  "recordid".strtolower($master_tableid)."_";
        //@TODO select dalle nuove tabelle
        $columns=  $this->get_colums($linkedtableid,$userid);
        $select="SELECT";
        foreach ($columns as $key => $column) {
            if($select!='SELECT') 
            {
                $select=$select.',';
            }
            $columnid=$column['id'];
            if($columnid=='recordcss_')
            {
                $columnid=" '' as recordcss_";
            }
            $select=$select." ".$columnid;
        }
        $from=" FROM ".$linkedtable;
        $where=" WHERE $master_recordid_column='$master_recordid'";
        $orderby="";
        //CUSTOM WORK&WORK
        if($master_tableid=='CANDID')
        {
            if(strtolower($linkedtableid)=='candcontatti')
            {
                $orderby="ORDER BY user_candcontatti.data DESC";
            }
            if(strtolower($linkedtableid)=='candcolloquio')
            {
                $orderby="ORDER BY user_candcolloquio.datavalidazione DESC";
            }
            if(strtolower($linkedtableid)=='canddisponibilita')
            {
                $orderby="ORDER BY user_canddisponibilita.dataultimocontatto DESC";
            }
            if(strtolower($linkedtableid)=='candpr')
            {
                $orderby="ORDER BY user_candpr.dallanno DESC";
            }
            if(strtolower($linkedtableid)=='candformazione')
            {
                $orderby="ORDER BY user_candformazione.anno DESC";
            }
            if(strtolower($linkedtableid)=='competenzeit')
            {
                $orderby="ORDER BY user_competenzeit.anno DESC";
            }
        }
        if($master_tableid=='AZIEND')
        {
            if(strtolower($linkedtableid)=='azcont')
            {
                $orderby="ORDER BY user_azcont.data DESC";
            }
            if(strtolower($linkedtableid)=='azmissioni')
            {
                $orderby="ORDER BY user_azmissioni.dataapertura DESC";
            }
        }
        $sql_finale=$select.$from.$where.$orderby;
        
        $rows=$this->select($sql_finale);
        return $rows;  
    }
    
    
    /**
     * Restituisce le colonne dei risultati di una ricerca
     * 
     * @param String $idarchivio id dell'archivio
     * @param String $idutente id dell'utente
     * @return Array Campi da visualizzare come colonne dei risultati di una ricerca
     * @author Alessandro Galli
     * 
     * @mobile
     */
       public function get_colums($idarchivio,$idutente)
    {
        $colums=array();
        $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, $idutente, 'risultatiricerca');
        if(sizeof($preference_columns)==0)
        {
            $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, 1, 'risultatiricerca');
        }
        $column['id']='recordid_';
        $column['desc']='recordid_';
        $column['fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        $column['id']='recordstatus_';
        $column['desc']='recordstatus_';
        $column['fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        $column['id']='recordcss_';
        $column['desc']='recordcss_';
        $column['fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        foreach ($preference_columns as $key => $preference_column) {
            if(($preference_column['tablelink']!='')&&($preference_column['tablelink']!=null))
            {
                $column['id']="recordid".strtolower($preference_column['tablelink'])."_";
                $column['fieldtypeid']='linked';
                $column['linkedtableid']=$preference_column['tablelink'];
            }
            else
            {
                $column['id']=$preference_column['fieldid'];
                $column['fieldtypeid']=$preference_column['fieldtypeid'];
                $column['linkedtableid']='';
            }
            $column['desc']=$preference_column['description'];
            
            $colums[]=$column;
        }
        
       /* $column['id']='creatorid_';
        $column['fieldtypeid']='sys_Utente';
        $column['desc']='Creato da';
        $colums[]=$column;*/
        
        // TEMP
        if($idarchivio=='CANDIDtemp')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='statodisp';
            $column['desc']='Dis';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='wwws';
            $column['desc']='wwws';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='validato';
            $column['desc']='Valid';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='pflash';
            $column['desc']='pflash';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='cognome';
            $column['desc']='Cognome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='nome';
            $column['desc']='Nome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='qualifica';
            $column['desc']='Qualifica';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='giudizio';
            $column['desc']='Giud';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Con';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='datanasc';
            $column['desc']='Età';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
        }
        if($idarchivio=='AZIEND')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='ragsoc';
            $column['desc']='Ragione Sociale';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='distretto';
            $column['desc']='Distretto';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='settore';
            $column['desc']='settore';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='aziendastato';
            $column['desc']='Azienda Stato';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Consulente';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            
        }
        return $colums;
    }
    
    public function get_results_columns($idarchivio,$idutente)
    {
        $colums=array();
        $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, $idutente, 'risultatiricerca');
        if(sizeof($preference_columns)==0)
        {
            $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, 1, 'risultatiricerca');
        }
        $column['id']='recordid_';
        $column['desc']='recordid_';
        $column['fieldtypeid']='Sys';
        $column['results_fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        $column['id']='recordstatus_';
        $column['desc']='recordstatus_';
        $column['fieldtypeid']='Sys';
        $column['results_fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        $column['id']='recordcss_';
        $column['desc']='recordcss_';
        $column['fieldtypeid']='Sys';
        $column['results_fieldtypeid']='Sys';
        $column['linkedtableid']='';
        $colums[]=$column;
        foreach ($preference_columns as $key => $preference_column) {
            if(($preference_column['tablelink']!='')&&($preference_column['tablelink']!=null))
            {
                $column['id']="recordid".strtolower($preference_column['tablelink'])."_";
                $column['fieldtypeid']='linked';
                $column['results_fieldtypeid']='linked';
                $column['linkedtableid']=$preference_column['tablelink'];
            }
            else
            {
                $column['id']=$preference_column['fieldid'];
                $column['fieldtypeid']=$preference_column['fieldtypeid'];
                if(isnotempty($preference_column['lookuptableid']))
                {
                    $column['results_fieldtypeid']='Lookup';
                    $column['options']= $this->get_lookuptable3($preference_column['lookuptableid']);
                }
                else
                {
                    $column['results_fieldtypeid']=$preference_column['fieldtypeid'];
                    if($preference_column['fieldid']=='record_preview')
                    {
                        $column['results_fieldtypeid']='record_preview';
                    }
                }
               
                $column['linkedtableid']='';
            }
            $column['desc']=$preference_column['description'];
            
            $colums[]=$column;
        }
        
       /* $column['id']='creatorid_';
        $column['fieldtypeid']='sys_Utente';
        $column['desc']='Creato da';
        $colums[]=$column;*/
        
        // TEMP
        if($idarchivio=='CANDIDtemp')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='statodisp';
            $column['desc']='Dis';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='wwws';
            $column['desc']='wwws';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='validato';
            $column['desc']='Valid';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='pflash';
            $column['desc']='pflash';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='cognome';
            $column['desc']='Cognome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='nome';
            $column['desc']='Nome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='qualifica';
            $column['desc']='Qualifica';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='giudizio';
            $column['desc']='Giud';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Con';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='datanasc';
            $column['desc']='Età';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
        }
        if($idarchivio=='AZIEND')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='ragsoc';
            $column['desc']='Ragione Sociale';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='distretto';
            $column['desc']='Distretto';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='settore';
            $column['desc']='settore';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='aziendastato';
            $column['desc']='Azienda Stato';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Consulente';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            
        }
        return $colums;
    }
    
     public function get_colums_linked($idarchivio,$idutente)
    {
        $colums=array();
        $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, $idutente, 'risultatilinked');
        if(sizeof($colums)==0)
        {
            $preference_columns=  $this->LoadPreferencesNewVersion($idarchivio, 1, 'risultatilinked');
        }
        $column['id']='recordid_';
        $column['desc']='recordid_';
        $column['fieldtypeid']='Sys';
        $colums[]=$column;
        $column['id']='recordstatus_';
        $column['desc']='recordstatus_';
        $column['fieldtypeid']='Sys';
        $colums[]=$column;
        foreach ($preference_columns as $key => $preference_column) {
            if(($preference_column['tablelink']!='')&&($preference_column['tablelink']!=null))
            {
                $column['id']="recordid".strtolower($preference_column['tablelink'])."_";
                $column['fieldtypeid']='linked';
                $column['linkedtableid']=$preference_column['tablelink'];
            }
            else
            {
                $column['id']=$preference_column['fieldid'];
                $column['fieldtypeid']=$preference_column['fieldtypeid'];
            }
            $column['desc']=$preference_column['description'];
            
            $colums[]=$column;
        }
    
        return $colums;
    }
    
    public function get_dati_record($tableid,$recordid)
    {
        $condizioni[]="recordid_='$recordid'";
        $dati=$this->get_dati_firsrecord_bycondition($tableid, $condizioni);
        return $dati;
    }
    
    function get_table_sublabels($tableid)
    {
        $sql="
            SELECT *
            FROM sys_table_sublabel
            WHERE tableid='$tableid'
            order by sublabelorder
            ";
        $result= $this->select($sql);
        $return[0]=array(
                        "tableid" => $tableid,
                        "sublabelname" => "",
                        "sublabelorder" => "0",
                        "showedbytableid" => "",    
                        "showedbyfieldid" => "",
                        "showedbyvalue" => ""
                    );
        $return=  array_merge($return,$result);
        return $return;
    }
    
    function get_fields_table($tableid,$label='null',$recordid='null',$funzione='null',$type='null',$prefilledtable=array(),$origine_tableid='')
    {
        $userid=  $this->get_userid();
        $emptyfields=  $this->get_emptyfields_table($tableid,$label,$funzione,$type);
        $filledfields=$emptyfields;
        $values_fields_table=array();
        //$prefilledfields=array();
        if($recordid!='null')
        {
            $values_fields_table=  $this->get_values_fields_table($tableid, $recordid);
        }
        /*if($funzione=='ricerca')
        {
            $viewid=7; //TEMP TEST
            $view_post=$this->Sys_model->get_view_post($tableid,$viewid); // TEMP TEST
            $prefilledfields=$view_post['tables'][$tableid]['search']['t_1']['fields']; //TEMP TEST
        }*/

        foreach ($emptyfields as $key => $emptyfield) 
        {
            $filledfields[$key]['valuecode'][0]['value']='';
            $filledfields[$key]['valuecode'][0]['code']='';
            $filledfields[$key]['param']='';
            $filledfields[$key]['operator']='';
            $fieldid=$emptyfield['fieldid'];
            $filledfields[$key]['settings']=$this->get_field_settings($tableid,$fieldid);
            $fieldtypeid=$emptyfield['fieldtypeid'];
            $lookuptableid=$emptyfield['lookuptableid'];
            /*if(($funzione=='inserimento')&&($this->isnotempty($emptyfield['default'])))
            {
                
                //$filledfields[$key]['auto']=true;
                if(($fieldtypeid=='Parola')&&($lookuptableid!='')&&($lookuptableid!=null))
                {
                    $itemcode=$emptyfield['default'];
                    $filledfields[$key]['valuecode'][0]['code']=$itemcode;
                    $itemvalue=$this->get_lookup_table_item_description($lookuptableid, $itemcode);
                    $filledfields[$key]['valuecode'][0]['value']=$itemvalue;
                }
                
                if($fieldtypeid=='Utente')
                {
                    if($emptyfield['default']=='userid')
                    {
                        $userid=$this->get_userid();
                        $filledfields[$key]['valuecode'][0]['code']=$userid;
                        $filledfields[$key]['valuecode'][0]['value']=  $this->get_user_nomecognome($userid); 
                    }
                }
                
                if($fieldtypeid=='Data')
                {
                    if($emptyfield['default']=='today')
                    {
                       $filledfields[$key]['valuecode'][0]['code']= date('Y-m-d');
                       $filledfields[$key]['valuecode'][0]['value']=  date('Y-m-d');
                    }
                }
                
                if($fieldtypeid=='Gruppo')
                {
                    if($emptyfield['default']=='usergroup')
                    {
                       $userid=$this->get_userid();
                       $usergroup=$this->get_usergroup($userid); 
                       $filledfields[$key]['valuecode'][0]['code']= $usergroup['code'];
                       $filledfields[$key]['valuecode'][0]['value']=  $usergroup['value'];
                    }
                }
            }*/
            
            // DEFAULT VALUE INIZIO
            // 
            if($funzione=='inserimento')
            {
                $default_code='';
                $default_value='';
                // verifica del valore di default direttamente in sys_field inizio
                $default_code=$emptyfield['default'];
                // verifica del valore di default direttamente in sys_field fine
                
                if(isempty($default_code))
                {
                    $default_code=  $this->get_field_setting($tableid, $fieldid, 'default');
                }
                
                          
                
                
                if($this->isnotempty($default_code))
                {
                    
                    if(($fieldtypeid=='Parola')&&($lookuptableid!='')&&($lookuptableid!=null))
                    {
                        
                        $itemvalue=$this->get_lookup_table_item_description($lookuptableid, $default_code);
                        $default_value=$itemvalue;
                    }

                    if($fieldtypeid=='Utente')
                    {
                        if($default_code=='userid')
                        {
                            $userid=$this->get_userid();
                            $default_code= $userid;
                            $default_value=  $this->get_user_nomecognome($userid); 
                        }
                    }

                    if($fieldtypeid=='Data')
                    {
                        if($default_code=='today')
                        {
                           $default_code= date('Y-m-d');
                           $default_value=  date('Y-m-d');
                        }
                    }

                    if($fieldtypeid=='Gruppo')
                    {
                        if($default_code=='usergroup')
                        {
                           $userid=$this->get_userid();
                           $usergroup=$this->get_usergroup($userid); 
                           $default_code= $usergroup['code'];
                           $default_value=  $usergroup['value'];
                        }
                    }
                }
                
                $filledfields[$key]['valuecode'][0]['code']= $default_code;
                $filledfields[$key]['valuecode'][0]['value']=  $default_value;
                $filledfields[$key]['value']=  $default_value;
            }
            // DEFAULT VALUE FINE
            
            //CUSTOM ABOUT
            if(($funzione=='inserimento')&&($tableid=='telefonate')&&($key=='data'))
            {
               $filledfields[$key]['value']=  date('Y-m-d'); 
            }
            if(($funzione=='inserimento')&&($tableid=='telefonate')&&($key=='ora'))
            {
              // $filledfields[$key]['value']=  date('Y-m-d'); 
            }
            
            //CUSTOM  Uniludes
            if(($funzione=='inserimento')&&(($tableid=='protocolloentrata')||($tableid=='protocolloentrata2015')||($tableid=='protocollouscita')||($tableid=='protocollouscita2015'))&&($emptyfield['fieldid']=='utente'))
            {
               $userid=  $this->get_userid();
               $value='';
               if($userid==4)
                   $value='Antonella';
               if($userid==37)
                   $value='Pilo';
               if($userid==39)
                   $value='guarino';
               $filledfields[$key]['valuecode'][0]['value']=  $value; 
               $filledfields[$key]['value']=  $value; 
               $filledfields[$key]['valuecode'][0]['code']=$value;
            }
            
            $index=strtolower($emptyfield['fieldid']);
            if(array_key_exists($index, $values_fields_table))
            {
                $value_db=$values_fields_table[$index];
                $value_db_exploded=  explode("|;|", $value_db);
                foreach ($value_db_exploded as $value_db_key => $value_db_part) 
                {
                    $code=$value_db_part;
                    $value=$code;
                    if($emptyfield['lookuptableid']=='')
                    {
                        if($emptyfield['fieldtypeid']=='Utente')
                        {
                            $value=$this->get_user_nomecognome($code);
                        }
                        //CUSTOM WORK&WORK

                            if(($index=='salattlordo')||($index=='saldeslordo'))
                            {
                                if($code!='')
                                {
                                    $value=  number_format(intval($code), 0, '.', "'");
                                }
                                else
                                {
                                    $value='';
                                }
                            }
                            if($fieldtypeid=='Data')
                            {
                                if($code!='')
                                {
                                    $value=date('d/m/Y',  strtotime(str_replace('/', '-', $code)));
                                }
                            }
                        
                    }
                    else
                    {
                       if(($code!='')||($code!=null))
                       {
                        $lookuptableid=$emptyfield['lookuptableid'];   
                        if($lookuptableid!='VUOTA')
                        {
                            $value=  $this->get_lookup_table_item_description($lookuptableid, $code);
                        }
                       }
                    }
                    $filledfields[$key]['valuecode'][$value_db_key]['value']=$value;
                    $filledfields[$key]['value']=$value;
                    $filledfields[$key]['valuecode'][$value_db_key]['code']=$code;
                     
                }
            }
            if(array_key_exists('fields', $prefilledtable))
            {
                $prefilledfields=$prefilledtable['fields'];
                if(array_key_exists($index, $prefilledfields))
                {
                    $counter=0;
                    foreach ($prefilledfields[$index] as $prefilledfield_key => $prefilledfield) {
                        $filledfields[$key]['valuecode'][$counter]['value']=$prefilledfield['value'][0];
                        $filledfields[$key]['valuecode'][$counter]['code']=$prefilledfield['value'][0];
                        $filledfields[$key]['param']=$prefilledfield['param'];
                        $filledfields[$key]['operator']=$prefilledfield['operator'];

                        $counter++;
                    }
                }
            }
            
        }
        
        //var_dump($filledfields);
        return $filledfields;
    }
    
    
    /**
     * Ritorna i campi con relativo valore per una tabella e una specifica label
     * 
     * @param String $tableid id tabella
     * @param String $label label sotto cui si trovano i campi interessati 
     * @param String $recordid recordidi di riferimento per i valori
     * @param String $funzione funzionalità svolta (ricerca,inserimento ecc)
     * @return Array
     */
    function get_filledfields_table($tableid,$label='null',$recordid,$funzione='null',$type='null')
    {
        $emptyfields=  $this->get_emptyfields_table($tableid,$label,$funzione,$type);
        $filledfields=$emptyfields;
        $values_fields_table=  $this->get_values_fields_table($tableid, $recordid);
        
        foreach ($emptyfields as $key => $emptyfield) {
            $index=strtolower($emptyfield['fieldid']);
            if(array_key_exists($index, $values_fields_table))
            {
                if($emptyfield['lookuptableid']=='')
                {
                    $value=$values_fields_table[$index];
                    //CUSTOM WORK&WORK
                    if($funzione=='scheda')
                    {
                        if(($index=='salattlordo')||($index=='saldeslordo'))
                        {
                            $value=  number_format($value, 0, '.', "'");
                        }
                    }
                    $filledfields[$key]['value']=$value;
                }
                else
                {
                    $code=$values_fields_table[$index];
                   $filledfields[$key]['code']= $code ; 
                   if(($code!='')||($code!=null))
                   {
                   $filledfields[$key]['value']=  $this->get_lookup_table_item_description($index, $code);
                   }
                   /*if(array_key_exists($index.'_', $values_fields_table))
                   {
                       $filledfields[$key]['value']=$values_fields_table[$index.'_'];
                   }*/
                }
            }
            
        }
        
        //var_dump($filledfields);
        return $filledfields;
    }
    
    
    /**
     * Ritorna i campi vuoi (per ricerca e inserimento) di una certa tabella e sotto una certa label
     * 
     * @param String $tableid id tabella principale
     * @param String $label label di cui visualizzare i campi
     * @param String $funzione funzionalità che si sta eseguendo (inserimento,ricerca,modifica,scheda)
     * @return Array campi senza valore
     * 
     * @mobile
     */
    function get_emptyfields_table($tableid,$label='null',$funzione='null',$type='null')
    {
        $userid=  $this->get_userid();
        $like= $this->get_like();
        $typepreference='campiInserimento';
        if($funzione=='ricerca')
        {
            $typepreference='campiricerca';
        }
        if($funzione=='inserimento')
        {
            $typepreference='campiInserimento';
        }
        if($funzione=='scheda')
        {
            $typepreference='campischeda';
        }
        if($funzione=='modifica')
        {
            $typepreference='campiInserimento';
        }
        if($funzione=='fissi')
        {
            $typepreference='campiFissi';
        }
        if($funzione=='all')
        {
            $typepreference='all';
        }
        if(($label!='null')&&($label!='undefined')&&($type!='linked')&&($type!='linkedmaster'))
        {
            $label=  str_replace("%20", " ", $label);
            $label_condition="AND sys_field.label='$label'";
        }
        else
        {
            $label_condition='';
        }
        if($typepreference!='all')
        {
            //prendo i campi di preferenza dell'utente
            $sql="
                SELECT sys_field.*,sys_field.tableid,sys_field.fieldid,sys_field.fieldtypeid,sys_field.length,sys_field.decimalposition,sys_field.description,sys_field.fieldorder,sys_field.lookuptableid,sys_field.label,sys_field.tablelink,sys_user_order.fieldorder,sys_field.default
                FROM sys_field LEFT JOIN sys_user_order ON sys_field.fieldid=sys_user_order.fieldid
                WHERE sys_field.tableid $like '$tableid' AND sys_field.label!='Old' $label_condition AND ((sys_user_order.userid=$userid AND sys_user_order.tableid $like '$tableid' AND sys_user_order.typepreference='$typepreference'))
                ORDER BY sys_user_order.fieldorder
                ";
            $fields =  $this->select($sql);
            if($fields==null)
            {
                //prendo i campi di preferenza dai gruppi
                $groups=  $this->Sys_model->db_get('sys_user_settings','*',"userid=$userid AND setting='group'");
                foreach ($groups as $key => $group) {
                    $groupuserid=$group['value'];
                    $sql="
                    SELECT sys_field.*,sys_field.tableid,sys_field.fieldid,sys_field.fieldtypeid,sys_field.length,sys_field.decimalposition,sys_field.description,sys_field.fieldorder,sys_field.lookuptableid,sys_field.label,sys_field.tablelink,sys_user_order.fieldorder,sys_field.default
                    FROM sys_field LEFT JOIN sys_user_order ON sys_field.fieldid=sys_user_order.fieldid
                    WHERE sys_field.tableid $like '$tableid' AND sys_field.label!='Old' $label_condition AND ((sys_user_order.userid=$groupuserid AND sys_user_order.tableid $like '$tableid' AND sys_user_order.typepreference='$typepreference'))
                    ORDER BY sys_user_order.fieldorder
                    ";
                    $fields =  $this->select($sql);
                }
                
                if($fields==null)
                {   
                //prendo i campi di preferenza del superuser
                $sql="
                SELECT sys_field.*,sys_field.tableid,sys_field.fieldid,sys_field.fieldtypeid,sys_field.length,sys_field.decimalposition,sys_field.description,sys_field.fieldorder,sys_field.lookuptableid,sys_field.label,sys_field.tablelink,sys_user_order.fieldorder,sys_field.default
                FROM sys_field LEFT JOIN sys_user_order ON sys_field.fieldid=sys_user_order.fieldid
                WHERE sys_field.tableid $like '$tableid' AND sys_field.label!='Old' $label_condition AND ((sys_user_order.userid=1 AND sys_user_order.tableid $like '$tableid' AND sys_user_order.typepreference='$typepreference'))
                ORDER BY sys_user_order.fieldorder
                ";
                $fields =  $this->select($sql);
                }
                
                if($fields==null)
                {
                    //prendo i campi di inserimento del superuser
                    $sql="
                    SELECT sys_field.*,sys_field.tableid,sys_field.fieldid,sys_field.fieldtypeid,sys_field.length,sys_field.decimalposition,sys_field.description,sys_field.fieldorder,sys_field.lookuptableid,sys_field.label,sys_field.tablelink,sys_user_order.fieldorder,sys_field.default
                    FROM sys_field LEFT JOIN sys_user_order ON sys_field.fieldid=sys_user_order.fieldid
                    WHERE sys_field.tableid $like '$tableid' AND sys_field.label!='Old' $label_condition AND ((sys_user_order.tableid $like '$tableid' AND sys_user_order.typepreference='campiInserimento'))
                    ORDER BY sys_user_order.fieldorder
                    ";
                    $fields =  $this->select($sql);
                }
            }
        }
        else
        {
            $sql="SELECT * "
                    . "FROM sys_field "
                    . "WHERE sys_field.tableid $like '$tableid'";
            $fields =  $this->select($sql);
        }
        $return_fields=array();
        foreach ($fields as $key => $field) {
            $return_fields[$field['fieldid']]=$field;
        }
        return $return_fields;
    }
    
    
    function get_users()
    {
        $sql="
            SELECT id,firstname,lastname,'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM sys_user
            where disabled<>'Y'
            ORDER BY firstname  
            ";
        
        
        $result =  $this->select($sql);
        return $result;
    }
    
    /**
     * 
     * @param string id tabella
     * @return array lista campi della tabella
     * @author Alessandro Galli
     */
    function get_lookuptable($lookuptableid,$fieldid=null,$tableid=null)
    {
        $cliente_id= $this->get_cliente_id();
        $sql="
            SELECT itemcode,itemdesc,'false' as link, 'null' as linkfield, 'null' as linkvalue, 'null' as linkedfield, 'null' as linkedvalue
            FROM sys_lookup_table_item 
            where LOOKUPTABLEID='$lookuptableid'
            ORDER BY itemdesc    
            ";
        if(($fieldid=='SETTORE')&($tableid=='SKILL'))
        {
            $sql="
            SELECT settore as itemcode,settore as itemdesc, 'true' as link, 'SETTORE' as linkfield, codsettor as linkvalue
            FROM user_wsetto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='SKILL'))
        {
            $sql="
            SELECT subsettor as itemcode,subsettor as itemdesc,codsettor as linkedvalue,'SETTORE' as linkedfield, 'true' as link, 'SUBSETTOR' as linkfield, codsubset as linkvalue
            FROM user_wsubse
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='PROFESSIO')&($tableid=='SKILL'))
        {
            $sql="
            SELECT professio as itemcode,professio as itemdesc,codsubset as linkedvalue,'SUBSETTOR' as linkedfield,'false' as link
            FROM user_wprofe
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SETTORE')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSETTA'
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSUBSA'
            ORDER BY itemdesc  
            ";
        }
        

        
        if(($fieldid=='distretto')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,'true' as link, 'distretto' as linkfield, distretto as linkvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='comune')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc, distretto as linkedvalue,'distretto' as linkedfield
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='comune')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc, 'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='distretto')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        //CUSTOM SCHLEGEL INIZIO
        if(($cliente_id=='Schlegel')&&($tableid=='pratiche')&&(($fieldid=='controparte')||($fieldid=='autorita')))
        { 
            $sql="
            SELECT recordid_ as itemcode,CONCAT(ragionesociale,' ',cognome,' ',nome) as itemdesc,'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_clienti
            WHERE ragionesociale <> '' or cognome <> '' or nome <> ''
            ORDER BY itemdesc  
            ";
        }
        //CUSTOM SCHLEGEL FINE
        
        
        $result =  $this->select($sql);
        return $result;
    }
    
    function get_lookuptable2($lookuptableid,$fieldid=null,$tableid=null,$linkvalue)
    {
        $sql="
            SELECT itemcode,itemdesc,itemdesc as label,'false' as link, 'null' as linkfield, 'null' as linkvalue, 'null' as linkedfield, 'null' as linkedvalue
            FROM sys_lookup_table_item 
            where LOOKUPTABLEID='$lookuptableid'
            ORDER BY itemdesc    
            ";
        if(($fieldid=='SETTORE')&($tableid=='SKILL'))
        {
            $sql="
            SELECT settore as itemcode,settore as itemdesc,settore as label, 'true' as link, 'SETTORE' as linkfield, codsettor as linkvalue
            FROM user_wsetto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='SKILL'))
        {
            $where_link="TRUE";
            if($this->isnotempty($linkvalue))
            {
                $where_link="user_wsetto.settore='$linkvalue'";
            }
            $sql="
            SELECT subsettor as itemcode,subsettor as itemdesc,subsettor as label,user_wsubse.codsettor as linkedvalue,'SETTORE' as linkedfield,'SETTORE' as linkedfieldid, 'true' as link, 'SUBSETTOR' as linkfield, user_wsubse.codsettor as linkvalue,user_wsubse.settore
            FROM user_wsubse JOIN user_wsetto on user_wsubse.codsettor=user_wsetto.codsettor
            WHERE TRUE AND $where_link
            ORDER BY itemdesc
            ";
        }
        if(($fieldid=='PROFESSIO')&($tableid=='SKILL'))
        {
            $where_link="TRUE";
            if($this->isnotempty($linkvalue))
            {
                $where_link="user_wsubse.subsettor='$linkvalue'";
            }
            $sql="
            SELECT user_wprofe.professio as itemcode,user_wprofe.professio as itemdesc,user_wprofe.professio as label,user_wprofe.codsubset as linkedvalue,'SUBSETTOR' as linkedfield,'false' as link,user_wsubse.subsettor
            FROM user_wprofe JOIN user_wsubse on user_wprofe.codsubset=user_wsubse.codsubset
            WHERE TRUE AND $where_link
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SETTORE')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc,itemdesc as label
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSETTA'
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc,itemdesc as label
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSUBSA'
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='esclusa')&($tableid=='criteripushup'))
        {
            $sql="
            SELECT recordid_ as itemcode,ragsoc as itemdesc,ragsoc as label
            FROM user_aziend
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='distretto')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,distretto as label,'true' as link, 'distretto' as linkfield, distretto as linkvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='comune')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc,comune as label, distretto as linkedvalue,'distretto' as linkedfield
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='comune')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc,comune as label, 'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='distretto')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,distretto as label,'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        
        
        $result =  $this->select($sql);
        return $result;
    }
    
    function get_lookuptable3($lookuptableid,$fieldid=null,$tableid=null,$term='',$linkvalue=null)
    {
        $cliente_id=  $this->get_cliente_id();
        $where='TRUE';
        $limit='';
        $term= strtolower($term);
        if($term!='')
        {
            if($term=='sys_recent')
            {
              $limit='LIMIT 50';  
            }
            else
            {
                $where="LOWER(itemdesc) like '%$term%'";
            }
        }
        $sql="
            SELECT itemcode,itemdesc,itemdesc as label,'false' as link, 'null' as linkfield, 'null' as linkvalue, 'null' as linkedfield, 'null' as linkedvalue
            FROM sys_lookup_table_item 
            where LOOKUPTABLEID='$lookuptableid' AND $where
            ORDER BY itemdesc  
            $limit
            ";
        if(($fieldid=='SETTORE')&($tableid=='SKILL'))
        {
            $sql="
            SELECT settore as itemcode,settore as itemdesc,settore as label, 'true' as link, 'SETTORE' as linkfield, codsettor as linkvalue
            FROM user_wsetto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='SKILL'))
        {
            $where_link="TRUE";
            if($this->isnotempty($linkvalue))
            {
                $where_link="user_wsetto.settore='$linkvalue'";
            }
            $sql="
            SELECT subsettor as itemcode,subsettor as itemdesc,subsettor as label,user_wsubse.codsettor as linkedvalue,'SETTORE' as linkedfield,'SETTORE' as linkedfieldid, 'true' as link, 'SUBSETTOR' as linkfield, user_wsubse.codsettor as linkvalue,user_wsubse.settore
            FROM user_wsubse JOIN user_wsetto on user_wsubse.codsettor=user_wsetto.codsettor
            WHERE TRUE AND $where_link
            ORDER BY itemdesc
            ";
        }
        if(($fieldid=='PROFESSIO')&($tableid=='SKILL'))
        {
            $where_link="TRUE";
            if($this->isnotempty($linkvalue))
            {
                $where_link="user_wsubse.subsettor='$linkvalue'";
            }
            $sql="
            SELECT user_wprofe.professio as itemcode,user_wprofe.professio as itemdesc,user_wprofe.professio as label,user_wprofe.codsubset as linkedvalue,'SUBSETTOR' as linkedfield,'false' as link,user_wsubse.subsettor
            FROM user_wprofe JOIN user_wsubse on user_wprofe.codsubset=user_wsubse.codsubset
            WHERE TRUE AND $where_link
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SETTORE')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc,itemdesc as label
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSETTA'
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='SUBSETTOR')&($tableid=='AZSETT'))
        {
            $sql="
            SELECT itemdesc as itemcode,itemdesc,itemdesc as label
            FROM sys_lookup_table_item
            WHERE lookuptableid='WSUBSA'
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='esclusa')&($tableid=='criteripushup'))
        {
            $sql="
            SELECT recordid_ as itemcode,ragsoc as itemdesc,ragsoc as label
            FROM user_aziend
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='distretto')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,distretto as label,'true' as link, 'distretto' as linkfield, distretto as linkvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='comune')&($tableid=='candrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc,comune as label, distretto as linkedvalue,'distretto' as linkedfield
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        
        if(($fieldid=='comune')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT comune as itemcode, comune as itemdesc,comune as label, 'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_comune
            ORDER BY itemdesc  
            ";
        }
        if(($fieldid=='distretto')&($tableid=='azrecapiti'))
        {
            $sql="
            SELECT distretto as itemcode,distretto as itemdesc,distretto as label,'false' as link, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_distretto
            ORDER BY itemdesc  
            ";
        }
        
        //CUSTOM SCHLEGEL INIZIO
        if(($cliente_id=='Schlegel')&&($tableid=='pratiche')&&(($fieldid=='controparte')||($fieldid=='autorita')))
        { 
            $where='TRUE';
            $orderby='itemdesc ASC';
            $limit='';
            $term= strtolower($term);
            if($term!='')
            {
                if($term=='sys_recent')
                {
                  $orderby='itemdesc ASC';
                  $limit='';  
                }
                else
                {
                    $where="LOWER(ragionesociale) like '%$term%' OR LOWER(cognome) like '%$term%' OR LOWER(nome) like '%$term%'";
                }
            }
            $sql="
            SELECT recordid_ as itemcode,CONCAT_WS('',ragionesociale,' ',cognome,' ',nome) as itemdesc,CONCAT_WS('',ragionesociale,' ',cognome,' ',nome) as label,'false' as link, 'null' as linkfield, 'null' as linkvalue, 'null' as linkedfield, 'null' as linkedvalue
            FROM user_clienti 
            where $where
            ORDER BY $orderby  
            $limit
            ";
        }
        //CUSTOM SCHLEGEL FINE
        
        if(($cliente_id=='Dimensione Immobiliare')&($fieldid=='mandato')&($tableid=='documenti'))
        { 
            $where_link="TRUE";
            if($this->isnotempty($linkvalue))
            {
                $where_link="stabile='$linkvalue'";
                $sql="
                SELECT sys_lookup_table_item.itemcode,sys_lookup_table_item.itemdesc,sys_lookup_table_item.itemdesc as label,'false' as link, 'null' as linkfield, 'null' as linkvalue, 'null' as linkedfield, 'null' as linkedvalue
                FROM sys_lookup_table_item JOIN sys_lookup_table_item_stabile ON  sys_lookup_table_item.itemcode=sys_lookup_table_item_stabile.itemcode      
                WHERE TRUE AND $where_link
                ORDER BY itemdesc
                ";
            }
            
        }
        
        
        $result =  $this->select($sql);
        return $result;
    }
    
    public function add_lookuptable_item($lookuptableid,$itemdesc)
    {
        //$itemdesc=@iconv(mb_detect_encoding($itemdesc, mb_detect_order(), true), "UTF-8//IGNORE", $itemdesc);
        $itemdesc = @iconv("UTF-8","UTF-8//IGNORE",$itemdesc);
        $itemdesc=  str_replace("'", "''", $itemdesc);
        //return $itemdesc;
        $itemcode=$itemdesc;
        
        /*mb_internal_encoding('UTF-8');
        $itemcode=mb_strtolower($itemdesc);
        $itemcode=str_replace(' ','',$itemcode);   
        $itemcode=preg_replace('/\s+/', '', $itemcode);
        $itemcode=  str_replace('-', '', $itemcode);
        $itemcode=  str_replace("'", "", $itemcode);*/
        $sql="SELECT * FROM sys_lookup_table_item WHERE lookuptableid='$lookuptableid' AND itemcode='$itemcode'";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $return='null';
        }
        else
        {
            $sql="INSERT INTO sys_lookup_table_item (lookuptableid,itemcode,itemdesc) VALUES ('$lookuptableid','$itemcode','$itemdesc')  ";
            $this->set_logquery('inserimento lookup item',  $sql);
            $this->execute_query($sql); 
            $return=$itemcode;
            $return=  str_replace("''", "'", $return);
        }
        return $return;
    }
    
    public function delete_lookuptable_item($lookuptableid,$itemcode)
    {
        $itemcode=  str_replace("'", "''", $itemcode);
        $sql="DELETE FROM sys_lookup_table_item WHERE lookuptableid='$lookuptableid' AND itemcode='$itemcode'";
        $this->execute_query($sql);
    }
    
    
    /**
     * Esegue la query già generata per passare i risultati alla datatable(versione desktop)
     * 
     * @param String $idmaster id tabella su cui si esegue la ricerca
     * @return Array
     * @author Alessandro Galli
     */
    function get_ajax_search_result($idmaster,$post=array())
        {
        $cliente_id=  $this->get_cliente_id();
        //$post=$_POST;
	$sIndexColumn = "id";
        $aColumns = $this->get_colums($idmaster, 1);
        $query=$post['query'];
        $query=htmlspecialchars_decode($query);
        $query=  str_replace('|percent|', '%', $query);
        $query= str_replace('$userid$', $this->get_userid(), $query);
        $sOrder='';
        $sEcho='';
	$sLimit = "";
	if ( isset( $post['iDisplayStart'] ) && $post['iDisplayLength'] != '-1' )
	{
		$sLimit = " LIMIT ".$post['iDisplayLength']." OFFSET ".$post['iDisplayStart'];
	}
	if ( isset( $post['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY ";
		for ( $i=0 ; $i<intval( $post['iSortingCols'] ) ; $i++ )
		{
			if ( $post[ 'bSortable_'.intval($post['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $post['iSortCol_'.$i] ) ]['id']." ".$post['sSortDir_'.$i].", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
                else
                {
                    $sOrder=$sOrder.',recordid_ desc';
                }
                //CUSTOM ABOUT
                if(($cliente_id=='About-x')&&($idmaster=='telemarketing'))
                {
                    if($sOrder=='ORDER BY recalldate desc,recordid_ desc')
                    {
                        $sOrder='ORDER BY recalldate::date desc,recordid_ desc';
                    }
                }
                //CUSTOM UNILUDES
                if($idmaster=='protocolloentrata')
                {
                    $sOrder='ORDER BY annoprotocollo desc,numeroprotocollo desc';
                }
                if($idmaster=='protocollouscita')
                {
                    $sOrder='ORDER BY annoprotocollo desc,numeroprotocollo desc';
                }
                //CUSTOM DIMENSIONE IMMOBILIARE
                if(($cliente_id=='Dimensione Immobiliare')&&($idmaster=='contatti'))
                {
                    $sOrder='ORDER BY cognome asc';
                }
	}
	$result=  $this->select($query);
	$iFilteredTotal = count($result); //count($rResult);
	$iTotal = $iFilteredTotal;//count($rResultTotal);
        if(strpos($query, 'ORDER BY') !== false)
        {
            $query=$query.' '.$sLimit;
            
        }
        else
        {
            $query=$query.' '.$sOrder.' '.$sLimit;
        }
	
	$result=  $this->select($query);
	/*
	 * Output
	 */
        if ( isset( $post['sEcho'] ) )
        {
            $sEcho=$post['sEcho'];
        }
        
	$output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
        //$output['aaData'][1]['fieldtype']='text';
        //$output['aaData'][1]['value']='test';
	$result=  $this->convert_fields_value_to_final_value($idmaster,$aColumns, $result);
        $result=$this->check_alert_recordcss($idmaster,$result);
        $output['aaData']=$result;
	return $output;
    }
    
    public function check_alert_recordcss($tableid,$result)
    {
        $recordcss_alerts=$this->get_alert($tableid,'recordcss',1);
        foreach ($result as $result_key => $row) {
            $recordid=$row[0];
            foreach ($recordcss_alerts as $key => $recordcss_alert) {
                $alert_condition=$recordcss_alert['alert_condition'];
                if($this->isempty($alert_condition))
                {
                    $alert_condition='TRUE';
                }
                $viewid=$recordcss_alert['alert_viewid'];
                $view_query='';
                if($this->isnotempty($viewid))
                {
                    $view_query=  $this->get_view_conditions($viewid);
                }
                
                if($this->isempty($view_query))
                {
                    $view_query='TRUE';
                }
                $condition="$alert_condition AND $view_query";
                
                $recordcss=$recordcss_alert['alert_param'];
                $check=  $this->db_get_row('user_'.strtolower($tableid),'*',"recordid_='$recordid' AND $condition");
                if($check)
                {
                    $result[$result_key][2]=$recordcss;
                }
            }
        }
        return $result;
    }
    
    public function get_alert_condition($alertid,$userid)
    {
        $alert=  $this->db_get_row('sys_alert', '*', "id=$alertid");
        $alert_condition=$alert['alert_condition'];
        if($this->isempty($alert_condition))
        {
            $alert_condition='TRUE';
        }
        $viewid=$alert['alert_viewid'];
        $view_query='';
        if($this->isnotempty($viewid))
        {
            $view_query=  $this->get_view_conditions($viewid,$userid);
        }

        if($this->isempty($view_query))
        {
            $view_query='TRUE';
        }
        $condition="$alert_condition AND $view_query";
        return $condition;
    }
    
    public function get_alert($tableid,$alert_type,$usertoalert)
    {
        $result=  $this->db_get('sys_alert','*',"tableid='$tableid' AND alert_type='$alert_type' AND alert_user='$usertoalert'",'ORDER BY id ASC');
    
        return $result;
    }
    
    public function convert_field_value($tableid,$fieldid,$value)
    {
        $field=$value;
        $fieldrow= $this->db_get_row('sys_field', '*', "tableid='$tableid' AND fieldid='$fieldid'" );
        $field_type=$fieldrow['fieldtypeid'];
        $keyfieldlink=$fieldrow['keyfieldlink'];
        if(isnotempty($keyfieldlink))
        {
           $field_type='linkedmaster'; 
        }
        $tablelink=$fieldrow['tablelink'];
        $lookuptableid=$fieldrow['lookuptableid'];
        $fieldid=$fieldrow['fieldid'];
        if($field_type=='Utente')
        {
            $field=$this->get_user_nomecognome($field);
        }
        if($field_type=='Data')
        {

            if($field!='')
            {
                //$field=date("d/m/Y",strtotime($field));
                //custom ww
                if($fieldid!='datanasc')
                {
                    $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                }
            }
        }
        if($field_type=='Ora')
        {
            if($field!='')
            {
                //$field=date("H:i",strtotime($field));
                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                $field = $hours.":".$minutes;
            }
        }
                        if($field_type=='Calcolato')
        {
            if($field!='')
            {
                //$field=  date('H:i',strtotime($field));
                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                $field = $hours.":".$minutes;
            }
        }
        if($field_type=='Parola')
        {
            
            
            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
            {
                $field=  $this->get_descrizione_lookup($tableid, $fieldid, $field);
            }
        }
        if($field_type=='Memo')
        {
            $lenght=  strlen($field);
            /*if($lenght>255)
            {
                $field=  substr($field, 0, 512);
                $field=$field."...";
            }*/
        }
        if($field_type=='linkedmaster')
        {
            $field=  $this->get_keyfieldlink_value($tableid,$tablelink,$field,false);
        }
        
        return $field;
    }
    
    public function convert_records($tableid,$records)
    {
        if(count($records>0))
        {
            
        }
        $linkedcolumns=array();
        foreach ($columns as $key => $column) {
            if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }
            /*if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }*/
        }
        $emptyfields=  $this->get_emptyfields_table($idmaster,'null','all','null');
        foreach ($emptyfields as $key => $emptyfield) 
        {
            $emptyfields2[strtolower($emptyfield['fieldid'])]=$emptyfield;
        }
        $return=array();
        foreach ($result as $key => $result_row) 
         {
            $output_row=array();
            //CUSTOM WORK&WORK
            // TEMP
            if($idmaster=='CANDIDtemp')
            {
            $result_row['qualifica']=  $this->custom_generate_qualifica($result_row['recordid_']);
            $result_row['datanasc']=$this->custom_generate_eta($result_row['datanasc']);

            }
            if($idmaster=='AZIEND')
            {
            $result_row['settore']=  $this->custom_generate_settore($result_row['recordid_']);
            }
            foreach ($result_row as $key => $field_value_db) 
            {
                //CUSTOM ABOUT
                /*if($key=='recordidaziende_')
                {
                    $linkedmaster_tableid='aziende';
                    $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                }*/
                $field_value_exploded=  explode("|;|", $field_value_db);
                $field_finale="";
                $linkedmaster_tableid='';
                foreach ($field_value_exploded as $field_value_exploded_key => $field) 
                {
                    if(array_key_exists($key, $emptyfields2))
                    {
                        $field_type=$emptyfields2[$key]['fieldtypeid'];
                        if($this->isnotempty($emptyfields2[$key]['tablelink']))
                        {
                            $linkedmaster_tableid=$emptyfields2[$key]['tablelink'];
                            $field_type='linked';
                            if($this->isnotempty($emptyfields2[$key]['keyfieldlink']))
                            {
                                //$field_type='linkedmaster';
                            }
                        }
                        //custom about
                        if($emptyfields2[$key]['fieldid']=='ultimocontatto')
                        {
                            $recordid=$result_row['recordid_'];
                            $sql="SELECT * FROM user_attivitacommerciali WHERE recordidaziende_='$recordid' ORDER BY data DESC LIMIT 1";
                            $rows=  $this->select($sql);
                            if(count($rows)>0)
                            {
                                if(($rows[0]['data']!='')&&($rows[0]['data']!=null))
                                {
                                    $data=$rows[0]['data'];
                                    $field=date("Y-m-d", strtotime($data));
                                    $result_row['ultimocontatto']=$field;
                                }
                            }
                        }
                        if($emptyfields2[$key]['fieldid']=='dacontattare')
                        {
                            $data=$result_row['ultimocontatto'];
                            $date_diff=  date_diff(date_create($data), date_create(date('Y-m-d H:i:s')));
                            $diff=$date_diff->format('%M');
                            if($diff>4)
                            {
                                $field= 'si';
                            }
                            else
                            {
                                $field= 'no';
                            }
                        }
                        if($field_type=='Utente')
                        {
                            $field=$this->get_user_nomecognome($field);
                        }
                        if($field_type=='Data')
                        {
                            
                            if($field!='')
                            {
                                //$field=date("d/m/Y",strtotime($field));
                                //custom ww
                                if($key!='datanasc')
                                {
                                    $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                                }
                            }
                        }
                        if($field_type=='Ora')
                        {
                            if($field!='')
                            {
                                //$field=date("H:i",strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $hours=sprintf('%02d', $hours);
                                $minutes=sprintf('%02d', $minutes);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Calcolato')
                        {
                            if($field!='')
                            {
                                //$field=  date('H:i',strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Parola')
                        {
                            $lookuptableid=$emptyfields2[$key]['lookuptableid'];
                            $fieldid=$emptyfields2[$key]['fieldid'];
                            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
                            {
                                $field=  $this->get_descrizione_lookup($idmaster, $fieldid, $field);
                            }
                            //CUSTOM WW
                            if(($tableid='criteripushup')&&($fieldid=='esclusa')&&($field!=''))
                            {
                                $sql="
                                    SELECT ragsoc
                                    FROM user_aziend
                                    WHERE recordid_='$field'
                                    ";
                                $result=  $this->select($sql);
                                if(count($result)>0)
                                {
                                    $field=$result[0]['ragsoc'];
                                }
                                
                            }
                        }
                        if($field_type=='Memo')
                        {
                            $lenght=  strlen($field);
                            /*if($lenght>255)
                            {
                                $field=  substr($field, 0, 512);
                                $field=$field."...";
                            }*/
                        }
                        if($field_type=='linkedmaster')
                        {
                            $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                            if(array_key_exists($key, $linkedcolumns))
                            {
                                unset($linkedcolumns[$key]); // remove item at index 0
                                $linkedcolumns = array_values($linkedcolumns);
                            }
                        }
                    }
                    if(array_key_exists($key, $linkedcolumns))
                    {
                        $linkedmaster_tableid=$linkedcolumns[$key];
                        $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                    }
                    /*if($field_type=='sys_Utente')
                    {
                        $field=$this->get_user_nomecognome($field);
                    }*/
                    if($field_value_exploded_key==0)
                    {
                        $field_finale=$field_finale.$field;
                    }
                    else
                    {
                        $field_finale=$field_finale." - ".$field;
                    }
                }
                $output_row[]=$field_finale;
            }
            //$output_row[2]='background-color: rgb(129, 52, 47);color:white';
            $return[]=$output_row;
        }
        return $return;
    }
    
    public function convert_fields_value_to_final_value($idmaster,$columns,$result)
    {
        $linkedcolumns=array();
        foreach ($columns as $key => $column) {
            if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }
            /*if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }*/
        }
        $emptyfields=  $this->get_emptyfields_table($idmaster,'null','all','null');
        foreach ($emptyfields as $key => $emptyfield) 
        {
            $emptyfields2[strtolower($emptyfield['fieldid'])]=$emptyfield;
        }
        $return=array();
        foreach ($result as $key => $result_row) 
         {
            $output_row=array();
            //CUSTOM WORK&WORK
            // TEMP
            if($idmaster=='CANDIDtemp')
            {
            $result_row['qualifica']=  $this->custom_generate_qualifica($result_row['recordid_']);
            $result_row['datanasc']=$this->custom_generate_eta($result_row['datanasc']);

            }
            if($idmaster=='AZIEND')
            {
            $result_row['settore']=  $this->custom_generate_settore($result_row['recordid_']);
            }
            foreach ($result_row as $key => $field_value_db) 
            {
                //CUSTOM ABOUT
                /*if($key=='recordidaziende_')
                {
                    $linkedmaster_tableid='aziende';
                    $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                }*/
                $field_value_exploded=  explode("|;|", $field_value_db);
                $field_finale="";
                $linkedmaster_tableid='';
                foreach ($field_value_exploded as $field_value_exploded_key => $field) 
                {
                    if(array_key_exists($key, $emptyfields2))
                    {
                        $field_type=$emptyfields2[$key]['fieldtypeid'];
                        if($this->isnotempty($emptyfields2[$key]['tablelink']))
                        {
                            $linkedmaster_tableid=$emptyfields2[$key]['tablelink'];
                            $field_type='linked';
                            if($this->isnotempty($emptyfields2[$key]['keyfieldlink']))
                            {
                                $field_type='linkedmaster';
                            }
                        }
                        //custom about
                        if($emptyfields2[$key]['fieldid']=='ultimocontatto')
                        {
                            $recordid=$result_row['recordid_'];
                            $sql="SELECT * FROM user_attivitacommerciali WHERE recordidaziende_='$recordid' ORDER BY data DESC LIMIT 1";
                            $rows=  $this->select($sql);
                            if(count($rows)>0)
                            {
                                if(($rows[0]['data']!='')&&($rows[0]['data']!=null))
                                {
                                    $data=$rows[0]['data'];
                                    $field=date("Y-m-d", strtotime($data));
                                    $result_row['ultimocontatto']=$field;
                                }
                            }
                        }
                        if($emptyfields2[$key]['fieldid']=='dacontattare')
                        {
                            $data=$result_row['ultimocontatto'];
                            $date_diff=  date_diff(date_create($data), date_create(date('Y-m-d H:i:s')));
                            $diff=$date_diff->format('%M');
                            if($diff>4)
                            {
                                $field= 'si';
                            }
                            else
                            {
                                $field= 'no';
                            }
                        }
                        if($field_type=='Utente')
                        {
                            $field=$this->get_user_nomecognome($field);
                        }
                        if($field_type=='Data')
                        {
                            
                            if($field!='')
                            {
                                //$field=date("d/m/Y",strtotime($field));
                                //custom ww
                                if($key!='datanasc')
                                {
                                    $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                                }
                            }
                        }
                        if($field_type=='Ora')
                        {
                            if($field!='')
                            {
                                //$field=date("H:i",strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $hours=sprintf('%02d', $hours);
                                $minutes=sprintf('%02d', $minutes);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Calcolato')
                        {
                            if($field!='')
                            {
                                //$field=  date('H:i',strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Parola')
                        {
                            $lookuptableid=$emptyfields2[$key]['lookuptableid'];
                            $fieldid=$emptyfields2[$key]['fieldid'];
                            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
                            {
                                $field=  $this->get_descrizione_lookup($idmaster, $fieldid, $field);
                            }
                            //CUSTOM WW
                            if(($tableid='criteripushup')&&($fieldid=='esclusa')&&($field!=''))
                            {
                                $sql="
                                    SELECT ragsoc
                                    FROM user_aziend
                                    WHERE recordid_='$field'
                                    ";
                                $result=  $this->select($sql);
                                if(count($result)>0)
                                {
                                    $field=$result[0]['ragsoc'];
                                }
                                
                            }
                        }
                        if($field_type=='Memo')
                        {
                            $lenght=  strlen($field);
                            /*if($lenght>255)
                            {
                                $field=  substr($field, 0, 512);
                                $field=$field."...";
                            }*/
                        }
                        if($field_type=='linkedmaster')
                        {
                            $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                            if(array_key_exists($key, $linkedcolumns))
                            {
                                unset($linkedcolumns[$key]); // remove item at index 0
                                $linkedcolumns = array_values($linkedcolumns);
                            }
                        }
                    }
                    if(array_key_exists($key, $linkedcolumns))
                    {
                        $linkedmaster_tableid=$linkedcolumns[$key];
                        $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                    }
                    /*if($field_type=='sys_Utente')
                    {
                        $field=$this->get_user_nomecognome($field);
                    }*/
                    if($field_value_exploded_key==0)
                    {
                        $field_finale=$field_finale.$field;
                    }
                    else
                    {
                        $field_finale=$field_finale." - ".$field;
                    }
                }
                $output_row[]=$field_finale;
            }
            //$output_row[2]='background-color: rgb(129, 52, 47);color:white';
            $return[]=$output_row;
        }
        return $return;
    }
    
    public function convert_fields_value_to_final_value_BAK($idmaster,$columns,$result)
    {
        $linkedcolumns=array();
        foreach ($columns as $key => $column) {
            if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }
            /*if($column['fieldtypeid']=='linked')
            {
                $linkedcolumns[$column['id']]=$column['linkedtableid'];
            }*/
        }
        $emptyfields=  $this->get_emptyfields_table($idmaster,'null','all','null');
        foreach ($emptyfields as $key => $emptyfield) 
        {
            $emptyfields2[strtolower($emptyfield['fieldid'])]=$emptyfield;
        }
        $return=array();
        foreach ($result as $key => $result_row) 
         {
            $output_row=array();
            //CUSTOM WORK&WORK
            // TEMP
            if($idmaster=='CANDIDtemp')
            {
            $result_row['qualifica']=  $this->custom_generate_qualifica($result_row['recordid_']);
            $result_row['datanasc']=$this->custom_generate_eta($result_row['datanasc']);

            }
            if($idmaster=='AZIEND')
            {
            $result_row['settore']=  $this->custom_generate_settore($result_row['recordid_']);
            }
            foreach ($result_row as $key => $field_value_db) 
            {
                //CUSTOM ABOUT
                /*if($key=='recordidaziende_')
                {
                    $linkedmaster_tableid='aziende';
                    $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                }*/
                $field_value_exploded=  explode("|;|", $field_value_db);
                $field_finale="";
                $linkedmaster_tableid='';
                foreach ($field_value_exploded as $field_value_exploded_key => $field) 
                {
                    if(array_key_exists($key, $emptyfields2))
                    {
                        $field_type=$emptyfields2[$key]['fieldtypeid'];
                        if($this->isnotempty($emptyfields2[$key]['tablelink']))
                        {
                            $linkedmaster_tableid=$emptyfields2[$key]['tablelink'];
                            $field_type='linked';
                            if($this->isnotempty($emptyfields2[$key]['keyfieldlink']))
                            {
                                //$field_type='linkedmaster';
                            }
                        }
                        //custom about
                        if($emptyfields2[$key]['fieldid']=='ultimocontatto')
                        {
                            $recordid=$result_row['recordid_'];
                            $sql="SELECT * FROM user_attivitacommerciali WHERE recordidaziende_='$recordid' ORDER BY data DESC LIMIT 1";
                            $rows=  $this->select($sql);
                            if(count($rows)>0)
                            {
                                if(($rows[0]['data']!='')&&($rows[0]['data']!=null))
                                {
                                    $data=$rows[0]['data'];
                                    $field=date("Y-m-d", strtotime($data));
                                    $result_row['ultimocontatto']=$field;
                                }
                            }
                        }
                        if($emptyfields2[$key]['fieldid']=='dacontattare')
                        {
                            $data=$result_row['ultimocontatto'];
                            $date_diff=  date_diff(date_create($data), date_create(date('Y-m-d H:i:s')));
                            $diff=$date_diff->format('%M');
                            if($diff>4)
                            {
                                $field= 'si';
                            }
                            else
                            {
                                $field= 'no';
                            }
                        }
                        if($field_type=='Utente')
                        {
                            $field=$this->get_user_nomecognome($field);
                        }
                        if($field_type=='Data')
                        {
                            
                            if($field!='')
                            {
                                //$field=date("d/m/Y",strtotime($field));
                                //custom ww
                                if($key!='datanasc')
                                {
                                    $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                                }
                            }
                        }
                        if($field_type=='Ora')
                        {
                            if($field!='')
                            {
                                //$field=date("H:i",strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $hours=sprintf('%02d', $hours);
                                $minutes=sprintf('%02d', $minutes);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Calcolato')
                        {
                            if($field!='')
                            {
                                //$field=  date('H:i',strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Parola')
                        {
                            $lookuptableid=$emptyfields2[$key]['lookuptableid'];
                            $fieldid=$emptyfields2[$key]['fieldid'];
                            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
                            {
                                $field=  $this->get_descrizione_lookup($idmaster, $fieldid, $field);
                            }
                            //CUSTOM WW
                            if(($tableid='criteripushup')&&($fieldid=='esclusa')&&($field!=''))
                            {
                                $sql="
                                    SELECT ragsoc
                                    FROM user_aziend
                                    WHERE recordid_='$field'
                                    ";
                                $result=  $this->select($sql);
                                if(count($result)>0)
                                {
                                    $field=$result[0]['ragsoc'];
                                }
                                
                            }
                        }
                        if($field_type=='Memo')
                        {
                            $lenght=  strlen($field);
                            /*if($lenght>255)
                            {
                                $field=  substr($field, 0, 512);
                                $field=$field."...";
                            }*/
                        }
                        if($field_type=='linkedmaster')
                        {
                            $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                            if(array_key_exists($key, $linkedcolumns))
                            {
                                unset($linkedcolumns[$key]); // remove item at index 0
                                $linkedcolumns = array_values($linkedcolumns);
                            }
                        }
                    }
                    if(array_key_exists($key, $linkedcolumns))
                    {
                        $linkedmaster_tableid=$linkedcolumns[$key];
                        $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                    }
                    /*if($field_type=='sys_Utente')
                    {
                        $field=$this->get_user_nomecognome($field);
                    }*/
                    if($field_value_exploded_key==0)
                    {
                        $field_finale=$field_finale.$field;
                    }
                    else
                    {
                        $field_finale=$field_finale." - ".$field;
                    }
                }
                $output_row[]=$field_finale;
            }
            //$output_row[2]='background-color: rgb(129, 52, 47);color:white';
            $return[]=$output_row;
        }
        return $return;
    }
    
    public function get_result_converted($idmaster,$columns,$result,$linkedmaster_show_recordid=true)
    {
        $return=array();
        $tables=array();
        foreach ($columns as $key => $column) {
            $tableid=$column['tableid'];
            if(!in_array($tableid, $tables)){
                $tables[]=$tableid;
            }
        }
        foreach ($tables as $key => $tableid) {
            $emptyfields_table=  $this->get_emptyfields_table($tableid,'null','all','null');
            $emptyfields_temp=array();
            foreach ($emptyfields_table as $key => $emptyfield) 
            {
                $emptyfields_temp[strtolower($tableid."_t_".$emptyfield['fieldid'])]=$emptyfield;
            }
            $emptyfields_tables[$tableid]=$emptyfields_temp;
        }
        
        foreach ($result as $key => $result_row) 
         {
            $output_row=array();
            //CUSTOM WORK&WORK
            if($idmaster=='CANDID')
            {
                if(array_key_exists('qualifica', $result_row))
                {
                    $result_row['qualifica']=  $this->custom_generate_qualifica($result_row['recordid_']);
                }
                if(array_key_exists('datanasc', $result_row))
                {
                    $result_row['datanasc']=$this->custom_generate_eta($result_row['datanasc']);
                }                
            }
            if($idmaster=='AZIEND')
            {
                if(array_key_exists('settore', $result_row))
                {
                    $result_row['settore']=  $this->custom_generate_settore($result_row['recordid_']);
                }
            }
            foreach ($result_row as $key => $field_value_db) 
            {
                $field_value_exploded=  explode("|;|", $field_value_db);
                $field_finale="";
                $linkedmaster_tableid='';
                foreach ($field_value_exploded as $field_value_exploded_key => $field) 
                {
                    if(array_key_exists($key, $columns))
                    {
                        $tableid=$columns[$key]['tableid'];
                        //$emptyfields=$emptyfields_tables[$tableid];
                        $fieldid=$columns[$key]['fieldid'];
                        $field_type=$columns[$key]['fieldtypeid'];
                        if(isnotempty($columns[$key]['linkedtableid']))
                        {
                            $field_type='linkedmaster';
                        }
                        if(($fieldid!='recordid_')&&($fieldid!='recordstatus_')&&($fieldid!='recordpreview'))
                        {
                        //CUSTOM ABOUT-X
                        if($columns[$key]['fieldid']=='ultimocontatto')
                        {
                            $recordid=$result_row['recordid_'];
                            $sql="SELECT * FROM user_attivitacommerciali WHERE recordidaziende_='$recordid' ORDER BY data DESC LIMIT 1";
                            $rows=  $this->select($sql);
                            if(count($rows)>0)
                            {
                                if(($rows[0]['data']!='')&&($rows[0]['data']!=null))
                                {
                                    $data=$rows[0]['data'];
                                    $field=date("Y-m-d", strtotime($data));
                                    $result_row['ultimocontatto']=$field;
                                }
                            }
                        }
                        if($columns[$key]['fieldid']=='dacontattare')
                        {
                            $data=$result_row['ultimocontatto'];
                            $date_diff=  date_diff(date_create($data), date_create(date('Y-m-d H:i:s')));
                            $diff=$date_diff->format('%M');
                            if($diff>4)
                            {
                                $field= 'si';
                            }
                            else
                            {
                                $field= 'no';
                            }
                        }
                        if($field_type=='Utente')
                        {
                            $field=$this->get_user_nomecognome($field);
                        }
                        if($field_type=='Data')
                        {
                            
                            if($field!='')
                            {
                                //$field=date("d/m/Y",strtotime($field));
                                //custom ww
                                if($key!='datanasc')
                                {
                                    $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                                }
                            }
                        }
                        if($field_type=='Ora')
                        {
                            if($field!='')
                            {
                                $field=date("H:i",strtotime($field));
                            }
                        }
                        if($field_type=='Calcolato')
                        {
                            if($field!='')
                            {
                                //$field=  date('H:i',strtotime($field));
                                sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                                $field = $hours.":".$minutes;
                            }
                        }
                        if($field_type=='Parola')
                        {
                            //$lookuptableid=$emptyfields[$key]['lookuptableid'];
                            //$fieldid=$emptyfields[$key]['fieldid'];
                            
                            $lookuptableid=$columns[$key]['lookuptableid'];
                            $fieldid=$columns[$key]['fieldid'];
                            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
                            {
                                $field=  $this->get_descrizione_lookup($tableid, $fieldid, $field);
                            }
                        }
                        if($field_type=='linkedmaster')
                        {
                            $linkedmaster_tableid=$columns[$key]['linkedtableid'];
                            $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field,$linkedmaster_show_recordid);
                            /*if(array_key_exists($key, $linkedcolumns))
                            {
                                unset($linkedcolumns[$key]); // remove item at index 0
                                $linkedcolumns = array_values($linkedcolumns);
                            }*/
                        }
                    }
                    }
                    /*if(array_key_exists($key, $linkedcolumns))
                    {
                        $linkedmaster_tableid=$linkedcolumns[$key];
                        $field=  $this->get_keyfieldlink_value($idmaster,$linkedmaster_tableid,$field);
                    }*/
                    /*if($field_type=='sys_Utente')
                    {
                        $field=$this->get_user_nomecognome($field);
                    }*/
                    if($field_value_exploded_key==0)
                    {
                        $field_finale=$field_finale.$field;
                    }
                    else
                    {
                        $field_finale=$field_finale." - ".$field;
                    }
                }
                $output_row[$key]=$field_finale;
            }
            $return[]=$output_row;
        }
        return $return;
    }
    
    public function get_result_columns($master_tableid,$result){
        $like= $this->get_like();
        $columns=array();
        if(count($result)>0)
        {
            foreach ($result[0] as $key => $value) {
                $key_exploded=  explode("_t_", $key);
                if(count($key_exploded)>1)
                {
                    $tableid=$key_exploded[0];
                    $fieldid=$key_exploded[1];
                    
                }
                else
                {
                    $tableid=$master_tableid;
                    $fieldid=$key_exploded[0];
                    /*$fielddesc=$fieldid;
                    $fieldtypeid='Parola';
                    $lookuptableid='';
                    $linkedtableid='';*/
                }
                $fielddesc=$fieldid;
                $fieldtypeid="Parola";
                $lookuptableid="";
                $linkedtableid="";
                $sql="SELECT * FROM sys_field WHERE tableid $like '$tableid' AND fieldid $like '$fieldid' ";
                $fields=  $this->select($sql);
                if(count($fields)>0)
                {
                    $fielddesc=$fields[0]['description'];
                    $fieldtypeid=$fields[0]['fieldtypeid'];
                    $lookuptableid=$fields[0]['lookuptableid'];
                    $linkedtableid=$fields[0]['tablelink'];
                }
                $column['tableid']=$tableid;
                $column['fieldid']=$fieldid;
                $column['fielddesc']=$fielddesc;
                $column['fieldtypeid']=$fieldtypeid;
                $column['lookuptableid']=$lookuptableid;
                $column['linkedtableid']=$linkedtableid;
                $columns[$key]=$column;
            }
        }
        return $columns;
    }
    
    //CUSTOM WORK&WORK WW
    public function get_export_xls_result($rows)
    {
        $result=array();
        foreach ($rows as $key => $row) {
            $candid_recordid=$row['recordid_'];
            $candid=  $this->db_get_row('user_candid', '*', "recordid_='$candid_recordid'");
            $candid_lgl=$this->db_get_row('user_lgl', '*', "recordidcandid_='$candid_recordid'");
            //$result=array();
            //$result[0]=$candid_lgl;
            //$columns=$this->get_result_columns('lgl',$result);
            //$result= $this->get_result_converted('lgl', $columns, $result);
            //$candid_lgl=$result[0];
            $candid_last_contra=  $this->db_get_row('user_contra', '*', "recordidcandid_='$candid_recordid' AND destinat ilike 'D'","ORDER BY riferimen DESC");
            $candid_last_residenza=$this->db_get_row('user_candrecapiti', '*', "recordidcandid_='$candid_recordid' AND tipo='Res'");
            $candid_formazione=  $this->db_get('user_candformazione', '*', "recordidcandid_='$candid_recordid'");
            //Data di invio profilo operatore
            $result[$key][]=$candid_lgl['datainvioprofilo'];
            //azienda
            $result[$key][]='WS';
            //Data ingresso
            $result[$key][]=date('d/m/Y', strtotime($candid_last_contra['datainiz']));
            //cognome
            $result[$key][]=$candid['cognome'];
            //nome
            $result[$key][]=$candid['nome'];
            //sito
            $result[$key][]=$candid_lgl['sito'];
            //Provincia di residenza
            $result[$key][]=$candid_last_residenza['provincia'];
            //Formazione scolastica
             $formazione="";
            foreach ($candid_formazione as $key_formazione => $candid_formazione_row) {
                if($key_formazione!=0)
                {
                    $formazione=$formazione.', ';
                }
                $titolo=$candid_formazione_row['titolo'];
                $corso=$candid_formazione_row['corso'];
               
               if(($this->isnotempty($titolo))&&($this->isempty($corso)))
               {
                   $formazione=$formazione.$candid_formazione_row['titolo'];
               }
               if(($this->isempty($titolo))&&($this->isnotempty($corso)))
               {
                   $formazione=$formazione.$candid_formazione_row['corso'];
               }
               if(($this->isnotempty($titolo))&&($this->isnotempty($corso)))
               {
                   $formazione=$formazione.$candid_formazione_row['titolo'].":".$candid_formazione_row['corso'];
               }
               
               
            }
            $formazione=  conv_text($formazione);
            $result[$key][]=$formazione;
            //Qualifica
            $result[$key][]=$this->get_lookup_table_item_description('qualifica_lgl', $candid_lgl['qualifica']);
            //Precedenti esperienze in campo logistico
            $result[$key][]=$candid_lgl['esperienzecampologistico'];
            //Job Title
            $jobtitle=$candid_lgl['jobtitle'];
            if($this->isempty($jobtitle))
            {
                $jobtitle=$candid_last_contra['funzione'];
            }
            $result[$key][]=$candid_last_contra['funzione'];
            //Permesso di lavoro
            $tipo_permesso_provvisorio="";
            $scadenza_permesso_provvisorio="";
            $tipo_permesso_definitivo="";
            $scadenza_permesso_definitivo="";
            
            $tipo_permesso_codice=$candid['tipoperm'];
            $tipo_permesso_descrizione=  $this->get_lookup_table_item_description('PERMES', $tipo_permesso_codice);
            $scadenza_permesso=$candid['scadenzapermesso'];
            if(($tipo_permesso_codice=='N')||($tipo_permesso_codice=='V'))
            {
               $tipo_permesso_provvisorio= $tipo_permesso_descrizione;
               $scadenza_permesso_provvisorio=$scadenza_permesso;
            }
            else
            {
                $tipo_permesso_definitivo=$tipo_permesso_descrizione;
                $scadenza_permesso_definitivo=$scadenza_permesso;
            }
            
            //Permesso di lavoro provvisorio
            $result[$key][]=$tipo_permesso_provvisorio;
            $result[$key][]=$scadenza_permesso_provvisorio;
            //Permesso di lavoro definitivo
            $result[$key][]=$tipo_permesso_definitivo;
            $result[$key][]=$scadenza_permesso_definitivo;
            //Casellario giudiziario conformità
            $casellario_giudiziario=$candid_lgl['casellariogiudiziario'];
            $result[$key][]=$this->get_lookup_table_item_description('casellariogiudiziario_lgl', $casellario_giudiziario);
            //Casellario Giudiziario richiesta
            $casellario_giudiziario_richiesta='';
            if($casellario_giudiziario=='conforme')
            {
                $casellario_giudiziario_richiesta='SI';
            }
            if($casellario_giudiziario=='nonconforme')
            {
                $casellario_giudiziario_richiesta='NO';
            }
            $result[$key][]=$casellario_giudiziario_richiesta;
            //Trattamento dati personali autorizzazione
            $result[$key][]='si';
            //Formazione da agenzia circa LGL
            $result[$key][]='si';
            //eventuale formazione da parte di societa specializzata circa temi salute e sicurezza sul lavoro
            $result[$key][]=$candid_lgl['formsalutesicurezza'];
            //Patente CH per carrello a Timone
            $result[$key][]=$candid_lgl['pat_carrellotimone'];
            //Patente CH per carrello Frontale
            $result[$key][]=$candid_lgl['pat_carrellofrontale'];
            //Patente CH per carrello Retrattile
            $result[$key][]=$candid_lgl['pat_carrelloretrattile'];
            //Patente CH per Piattaforma
            $result[$key][]=$candid_lgl['pat_piattaforma'];
            //Scadenza Patente CH per Piattaforma
            $result[$key][]=$candid_lgl['scadenza_pat_piattaforma'];
            //Patente italiana per carrelli
            $result[$key][]=$candid_lgl['pat_ita_carrelli'];
            //Esperienza uso carrelli > 1 anno
            if($this->isempty($candid_lgl['esperienzacarrello']))
            {
                $esperienzacarrello='no';
            }
            else
            {
                $esperienzacarrello=$candid_lgl['esperienzacarrello'];
            }
            $result[$key][]=$esperienzacarrello;
        }
        
        return $result;
    }
    
    public function get_lgl_files($recordid)
    {
        $sql="
            SELECT *
            FROM user_candid_page
            WHERE recordid_='$recordid' AND category is not null AND category !=''
            ";
        $result=  $this->select($sql);
        return $result;
    }
    
    public function get_keyfieldlink_value($master_tableid,$linkedmaster_tableid,$linkedmaster_recordid,$linkedmaster_show_recordid=true)
    {
        /*$master_table=  'user_'.strtolower($master_tableid);
        $linkedmaster_table='user_'.strtolower($linkedmaster_tableid);
        $sql="SELECT keyfieldlink FROM sys_field WHERE tableid='$master_tableid' AND tablelink='$linkedmaster_tableid'" ;
        $result=  $this->select($sql);
        if(count($result)>0)
        {
          $keyfieldlink=$result[0]['keyfieldlink'];
        }
        $keyfieldlink= strtolower($keyfieldlink);
        $sql="SELECT $keyfieldlink FROM $linkedmaster_table WHERE recordid_='$linkedmaster_recordid'" ;
        $result=  $this->select($sql);
        $keyfieldlink_value='';
        if(count($result)>0)
        {
          foreach ($result[0] as $key => $value) {
              if($key=='record_preview')
              {
                  $value="record_preview";
              }
              $keyfieldlink_value=$keyfieldlink_value.' '.$value;
          }
          $keyfieldlink_value=$keyfieldlink_value."|:|$linkedmaster_recordid";
        }
        return $keyfieldlink_value;*/
        $keyfieldlink="";
        $master_table=  'user_'.strtolower($master_tableid);
        $linkedmaster_table='user_'.strtolower($linkedmaster_tableid);
        $sql="SELECT keyfieldlink FROM sys_field WHERE tableid='$master_tableid' AND tablelink='$linkedmaster_tableid'" ;
        $result=  $this->select($sql);
        if(count($result)>0)
        {
          $keyfieldlink=$result[0]['keyfieldlink'];
        }
        $keyfieldlink= strtolower($keyfieldlink);
        $keyfieldlink_array=  explode(",", $keyfieldlink);
        $keyfieldlink_return='';
        foreach ($keyfieldlink_array as $key => $keyfieldlink_field) {
            $keyfieldlink_field_value=0;
            if($keyfieldlink_field!='record_preview')
            {
                $sql="SELECT $keyfieldlink_field FROM $linkedmaster_table WHERE recordid_='$linkedmaster_recordid'" ;
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $keyfieldlink_field_value=$result[0][$keyfieldlink_field];
                    $keyfieldlink_field_value= $this->convert_field_value($linkedmaster_tableid, $keyfieldlink_field, $keyfieldlink_field_value);
                    if($keyfieldlink_return!='')
                    {
                        $keyfieldlink_return=$keyfieldlink_return." ";
                    }
                    $keyfieldlink_return=$keyfieldlink_return.$keyfieldlink_field_value;
                }
            }
            else
            {
                $keyfieldlink_return=$keyfieldlink_return."record_preview";
            }
        }
        if($linkedmaster_show_recordid)
        {
            $keyfieldlink_return=$keyfieldlink_return."|:|$linkedmaster_recordid"."|:|".$linkedmaster_tableid;
        }
        return $keyfieldlink_return;
    }
    
    public function get_linkedtables($master_tableid)
    {
        $sql="SELECT tablelinkid FROM sys_table_link WHERE tableid='$master_tableid'" ;
       $rows=  $this->select($sql); 
       $return=array();
       foreach ($rows as $key => $row) {
           $return[]=$row['tablelinkid'];
       }
       return $return;
    }
    
    public function get_linkedmastertables($master_tableid)
    {
        $sql="SELECT tableid FROM sys_table_link WHERE tablelinkid='$master_tableid'" ;
       $rows=  $this->select($sql); 
       $return=array();
       foreach ($rows as $key => $row) {
           $return[]=$row['tableid'];
       }
       return $return;
    }
    
    public function get_linkedmaster_recordid($master_tableid,$master_recordid,$linkedmaster_tableid,$funzione='',$origine_tableid='',$origine_recordid='')
    {
       $field_linkedmaster="recordid".strtolower($linkedmaster_tableid).'_'; 
       $master_table="user_".strtolower($master_tableid);
       $sql="SELECT $field_linkedmaster FROM $master_table WHERE recordid_='$master_recordid'" ;
       $result=  $this->select($sql); 
       if(count($result)>0)
       {
           $return= $result[0][$field_linkedmaster];
       }
       else
       {    
            $return='null';
            if($funzione=='inserimento')
            {
                if($master_tableid==$origine_tableid)
                {
                    $return=$origine_recordid;
                }
                $origine_linkedmaster_tables=  $this->db_get("sys_table_link","*","tablelinkid='$origine_tableid'");
                foreach ($origine_linkedmaster_tables as $key => $origine_linkedmaster_table) {
                    $origine_linkedmaster_tableid=$origine_linkedmaster_table['tableid'];
                    if($origine_linkedmaster_tableid==$linkedmaster_tableid)
                    {
                        $linkedmaster_recordid=  $this->db_get_value("user_".strtolower($origine_tableid), "recordid".strtolower($origine_linkedmaster_tableid)."_", "recordid_='$origine_recordid'");
                        $return=$linkedmaster_recordid;
                        
                    }
                }
            }
       }
       
       //custom Dimensione Immobiliare
       if(($funzione=='inserimento')&&($linkedmaster_tableid=='immobili')&&($origine_tableid=='immobili_richiesti'))
       {
           //$return='';
       }
       return $return;
    }
    
    
    public function custom_generate_qualifica($recordid)
    {
        $sql="SELECT qualifica FROM user_skill WHERE recordidcandid_='$recordid'";
        $rows=  $this->select($sql);
        $qualifica='';
        foreach ($rows as $index => $row) {
            if($index!=0)
            {
               $qualifica=$qualifica.'-'; 
            }
            if($row['qualifica']!=null)
            {
            $qualifica=$qualifica.$row['qualifica'];
            }
        }
        return $qualifica;
    }
    
    public function custom_generate_qualifica_bollettino($candidato)
    {
        $recordid_candidato=$candidato['recordid_'];
        $sql="SELECT qualifica FROM user_skill WHERE recordidcandid_='$recordid_candidato' and bollettino='Si'";
        $rows=  $this->select($sql);
        $qualifica='';
        foreach ($rows as $index => $row) {
            if($index!=0)
            {
               $qualifica=$qualifica.'-'; 
            }
            if($row['qualifica']!=null)
            {
            $qualifica=$qualifica.$row['qualifica'];
            }
        }
        return $qualifica;
    }
    
    public function custom_generate_eta($datanasc)
    {
        if($datanasc!=null)
        {
        $datacorrente=new DateTime();
        $datanascita=new DateTime(str_replace('/', '-', $datanasc));
        $eta=$datacorrente->diff($datanascita);
            return $eta->y;
        }
        else
        {
            return null;
        }
    }
    
    public function custom_generate_settore($recordid)
    {
        $sql="SELECT settore,subsettor,corebusiness FROM user_azsett WHERE recordidaziend_='$recordid'";
        $rows=  $this->select($sql);
        $settore='';
        foreach ($rows as $index => $row) {
            if($index!=0)
            {
               $settore=$settore.'-'; 
            }
            if($row['settore']!=null)
            {
                $settore=$settore.$row['settore'];
            }
            if($row['subsettor']!=null)
            {
                $settore=$settore.$row['subsettor'];
            }
            if($row['corebusiness']!=null)
            {
                $settore=$settore.$row['corebusiness'];
            }
        }
        return $settore;
    }
    
    
    public function LoadPreferencesNewVersion($idarchivio,$idutente,$tipopreferenza)
    {
        $sql="SELECT sys_field.*,sys_field.fieldid,sys_field.description,sys_field.fieldtypeid,sys_field.lookupcodedesc,sys_field.lookuptableid,sys_field.tableid,sys_field.tablelink,sys_field.label,sys_field.sublabel
              FROM sys_user_order INNER JOIN sys_field
                        ON sys_user_order.fieldid=sys_field.fieldid
              WHERE sys_field.tableid='$idarchivio' AND sys_user_order.tableid='$idarchivio' AND typepreference='$tipopreferenza' AND userid=$idutente
              ORDER BY sys_user_order.fieldorder";
        return $this->select($sql);
    }

    /**
     * ottieni la select con le colonne da visualizzare nelle tabelle per un determinato archivio
     * @param String $idarchivio
     * @param String $idutente
     * @return string testo della select con i relativi campi
     * @author Alessandro Galli
     */
    /*    public function get_preference_colums($idarchivio,$idutente)
    {
        $colums=  $this->LoadPreferencesNewVersion($idarchivio, $idutente, 'risultatiricerca');
        if(sizeof($colums)==0)
        {
            $colums=  $this->LoadPreferencesNewVersion($idarchivio, 1, 'risultatiricerca');
        }
        $select="";
        $table="user_".strtolower($idarchivio);
        foreach ($colums as $key => $colum) {
            
              if($colum['fieldtypeid']=='Data')
              {
                $select=$select.", to_char(".$table.".".$colum['fieldid'].", 'DD/MM/YYYY') as ".$colum['fieldid'];   
              }
              else
              {
                $select=$select.','.$table.".".$colum['fieldid'];  
              }
              
              if($colum['lookupcodedesc']==1)
              {
                $select=$select."_"; 
              }
            
        }

        return $select;
    }*/
    
    
    function get_fissi($tableid,$recordid)
    {
        $cliente_id=  $this->get_cliente_id();
        $fields_fissi=array();
        $fields_fissi_return=array();
        $fields_fissi=  $this->get_fields_table($tableid, 'null', $recordid, 'fissi', 'null');
        foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$field_fisso['fieldid'];
                $value="";
                if(array_key_exists('valuecode', $field_fisso))
                {
                    foreach ($field_fisso['valuecode'] as $field_fisso_key => $value_part) 
                    {
                        $value=$value." ".$value_part['value'];
                    }
                }
                if($field_fisso['fieldtypeid']=='Data')
                {
                    $value=  str_replace("/", "-", $value);
                    $value=date('d/m/Y', strtotime($value));
                }
                if($this->isnotempty($field_fisso['keyfieldlink']))
                {
                    $linkedmaster_tableid=$field_fisso['tablelink'];
                    $value=  str_replace(" ", "", $value);
                    $value=$this->get_keyfieldlink_value($tableid,$linkedmaster_tableid,$value);
                    $value_array=  explode("|:|", $value);
                    $value=$value_array[0];
                    $value=  str_replace('|;|', ', ', $value);
                }
                $fields_fissi_return[$key]['value']=$value;
                $fields_fissi_return[$key]['desc']=$field_fisso['description'];
                $fields_fissi_return[$key]['type']=$field_fisso['fieldtypeid'];
            }
        //CUSTOM WORK&WORK
        if($tableid=='CANDID')
        {
            $fields_fissi=array();
            $fields_fissi_return=array();
            $select="SELECT user_candid.id,user_candid.cognome,user_candid.nome,user_candid.sesso,to_char(user_candid.datareg, 'DD/MM/YYYY') as datareg,to_char(scadenza, 'DD/MM/YYYY') as scadenza,user_candid.consulente,user_candid.pflash";        
            $from=" FROM user_candid ";
            $where=" WHERE user_candid.recordid_='$recordid'";
        
            $sql_finale=$select.$from.$where;
            $result=$this->select($sql_finale);
            if(count($result)>0)
            {
                $fields_fissi=$result[0];
            }
            
            //statodisp
            $sql_finale="SELECT    statodisp
                            FROM      user_canddisponibilita 
                            WHERE  recordidcandid_='$recordid'
                            ORDER BY recordid_ DESC
                            ";
            $result=$this->select($sql_finale);
            $fields_fissi['statodisp']='';
            if(count($result)>0)
            {
                $fields_fissi['statodisp']=$result[0]['statodisp'];
            }
            
            
            //validato
            $sql_finale="SELECT    validato
                            FROM      user_candcolloquio 
                            WHERE  recordidcandid_='$recordid'
                            ORDER BY recordid_ DESC
                            ";
            $result=$this->select($sql_finale);
            $fields_fissi['validato']='';
            if(count($result)>0)
            {
                $fields_fissi['validato']=$result[0]['validato'];
            }
            
            //qualifica
            $fields_fissi['qualifica']=  $this->custom_generate_qualifica($recordid);
            foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$key;
                $fields_fissi_return[$key]['value']=$field_fisso;
                $fields_fissi_return[$key]['desc']=$key;
                $fields_fissi_return[$key]['type']='parola';
            }
        }
        if($tableid=='AZIEND')
        {
            $fields_fissi=array();
            $fields_fissi_return=array();
            $select="SELECT id,ragsoc,consulente,to_char(dataregistrazione, 'DD/MM/YYYY') as dataregistrazione,to_char(scadenza, 'DD/MM/YYYY') as scadenza,aziendastato";        
            $from=" FROM user_aziend ";
            $where=" WHERE user_aziend.recordid_='$recordid'";
        
            $sql_finale=$select.$from.$where;
            $result=$this->select($sql_finale);
            if(count($result)>0)
            {
                $fields_fissi=$result[0];
            }
            $fields_fissi['statoultimocontatto']='';
            $fields_fissi['dataultimocontatto']='';
            $last_row_azcontatti=  $this->get_last_record_linkedtable($recordid,'AZIEND','azcont');
            if($last_row_azcontatti!=null)
            {
                $fields_fissi['statoultimocontatto']=$last_row_azcontatti['stato'];
                $fields_fissi['dataultimocontatto']=date("d/m/Y",strtotime($last_row_azcontatti['data']));
            }
            foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$key;
                $fields_fissi_return[$key]['value']=$field_fisso;
                $fields_fissi_return[$key]['desc']=$key;
                $fields_fissi_return[$key]['type']='parola';
            }
        }
        if($tableid=='CONTRA')
        {
            $fields_fissi=array();
            $fields_fissi_return=array();
            $dati_contratto=$this->get_dati_record("contra", $recordid);
            $fields_fissi=$dati_contratto;
            $recordid_aziend=$dati_contratto['recordidaziend_'];
            $recordid_candid=$dati_contratto['recordidcandid_'];
            $dati_candid=$this->get_dati_record("candid", $recordid_candid);
            $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
            $fields_fissi['ragsoc']=$dati_aziend['ragsoc'];
            $fields_fissi['cognomenome']=$dati_candid['cognome'].' '.$dati_candid['nome'];
            $fields_fissi['datainiz']=date("d/m/Y",strtotime($fields_fissi['datainiz']));
            $fields_fissi['datafin']=date("d/m/Y",strtotime($fields_fissi['datafin']));
            foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$key;
                $fields_fissi_return[$key]['value']=$field_fisso;
                $fields_fissi_return[$key]['desc']=$key;
                $fields_fissi_return[$key]['type']='parola';
            }
        }
        if($tableid=='immobiliBAK')
        {
            $fields_fissi=array();
            $fields_fissi_return=array();
            $select="SELECT *";        
            $from=" FROM user_immobili ";
            $where=" WHERE user_immobili.recordid_='$recordid'";
        
            $sql_finale=$select.$from.$where;
            $result=$this->select($sql_finale);
            if(count($result)>0)
            {
                $fields_fissi=$result[0];
            }
            
            foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$key;
                $fields_fissi_return[$key]['value']=$field_fisso;
                $fields_fissi_return[$key]['desc']=$key;
                $fields_fissi_return[$key]['type']='parola';
            }
        }
        if(($cliente_id=='About-x')&&($tableid=='telemarketing'))
        {
            $fields_fissi=array();
            $fields_fissi_return=array();
            $select="SELECT campagne,statotelemarketing,to_char(recalldate, 'DD/MM/YYYY') as recalldate,note as note_telemarketing, recordidaziende_";        
            $from=" FROM user_telemarketing ";
            $where=" WHERE user_telemarketing.recordid_='$recordid'";
            $sql_finale=$select.$from.$where;
            $result=$this->select($sql_finale);
            if(count($result)>0)
            {
                $fields_fissi=$result[0];
            }
            $recordidazienda=$fields_fissi['recordidaziende_'];
            //$dati_azienda=$this->get_dati_record("aziende", $fields_fissi['recordidaziende_']);
            $dati_azienda=  $this->db_get_row('user_aziende','*' ,"recordid_='$recordidazienda'");
            if($dati_azienda!=null)
            {
                $fields_fissi=array_merge($fields_fissi,$dati_azienda);
            }
            foreach ($fields_fissi as $key => $field_fisso) {
                $fields_fissi_return[$key]['fieldid']=$key;
                $fields_fissi_return[$key]['value']=$field_fisso;
                $fields_fissi_return[$key]['desc']=$key;
                $fields_fissi_return[$key]['type']='parola';
            }
        }
        /*if(($cliente_id=='About-x')&&($tableid=='aziende'))
        {
            $fields_fissi_return['dacontattare']['value']='';
            $sql="SELECT * FROM user_attivitacommerciali WHERE recordidaziende_='$recordid' ORDER BY data DESC LIMIT 1";
            $rows=  $this->select($sql);
            if(count($rows)>0)
            {
                if(($rows[0]['data']!='')&&($rows[0]['data']!=null))
                {
                    $data=date("Y-m-d", strtotime($rows[0]['data']));
                    //echo $data;
                    $fields_fissi_return['data']['fieldid']='data';
                    $fields_fissi_return['data']['value']= $data;
                    $fields_fissi_return['data']['desc']='Ultimo appuntamento';
                    $fields_fissi_return['data']['type']='parola';

                    $fields_fissi_return['dacontattare']['fieldid']='dacontattare';
                    $date_diff=  date_diff(date_create($data), date_create(date('Y-m-d H:i:s')));
                    $diff=$date_diff->format('%M');
                    if($diff>4)
                    {
                        $fields_fissi_return['dacontattare']['value']= 'si';
                    }
                    else
                    {
                        $fields_fissi_return['dacontattare']['value']= 'no';
                    }
                }
            }
        }*/
        return $fields_fissi_return;        
    }
    
    
    public function get_last_record_linkedtable($master_recordid,$master_tableid,$linked_tableid)
    {
        $linked_table="user_".strtolower($linked_tableid);
        $subquery="SELECT MAX(recordid_) FROM $linked_table WHERE recordid".  strtolower($master_tableid)."_='$master_recordid'";
        $query="SELECT * FROM $linked_table WHERE recordid_ in ($subquery)";
        $result=  $this->select($query);
            if(count($result)>0)
            return $result[0];
        else
            return null;
        
    }
    
    /**
     * Conversione RGB - HEX
     * 
     * @param String $R
     * @param String $G
     * @param String $B
     * @return string HEXColor
     * @author Fabio Lanza
     
    function fromRGBtoHEX($R, $G, $B)
    {

        $R = dechex($R);
        if (strlen($R)<2)
        $R = '0'.$R;

        $G = dechex($G);
        if (strlen($G)<2)
        $G = '0'.$G;

        $B = dechex($B);
        if (strlen($B)<2)
        $B = '0'.$B;

        return '#' . $R . $G . $B;
    }
    */
    
    /**
     * Ritorna il percorso della foto
     * 
     * @param String $tableid
     * @param String $recordid
     * @return string percorso della foto
     * @author Alessandro Galli
     */
    function get_foto_path($tableid,$recordid)
    {
        $idarchive=  strtolower($tableid);
        $table='user_'.$idarchive.'_page';
        $return="";
        
        if(file_exists("../JDocServer/record_preview/$tableid/".$recordid.".jpg"))
        {
         $return="JDocServer/record_preview/$tableid/".$recordid.".jpg"; 
        }
        if($return=="")
        {
            
        $select="SELECT path_ ";
        $from=" FROM ".$table;
        $where=" WHERE recordid_='$recordid' GROUP BY path_";

        $sql_finale=$select.$from.$where;
        
        $rows=$this->select($sql_finale);
        
            
            foreach ($rows as $key => $row) {
                $path=  str_replace("\\", "/", $row['path_']);
                $filepath="../JDocServer/".$path.$recordid.".jpg";
                if(file_exists($filepath))
                {
                 $return="JDocServer/".$path.$recordid.".jpg"; 
                }
            }
        }
        
        if($tableid=='candidati')   //18-24
        {        
            $rows=$this->select("SELECT * FROM user_candidati WHERE recordid_='$recordid' ");
            
            if(count($rows)>0)
            {
                $mostrafotocv=strtolower($rows[0]['mostrafotocv']);
                
                if($mostrafotocv=='no' || $mostrafotocv=='' || $mostrafotocv===NULL){ 
                    $project_name=project_name();
                    $return="$project_name/assets/images/custom/18-24/Empty.png";
                }
                else
                {
                    if(!file_exists("../JDocServer/record_preview/$tableid/".$recordid.".jpg"))
                    {
                        $project_name=project_name();
                        $return="$project_name/assets/images/custom/18-24/Empty.png";
                    }
                }
            }  
        }
        
        if($tableid=='CANDID')      //WW
        {
        if($return=="")
        {
            $rows=$this->select("SELECT sesso FROM user_candid WHERE recordid_='$recordid' ");
            if(count($rows)>0)
            {
                $sesso=  strtolower($rows[0]['sesso']);
                if($sesso=='m')
                {
                   $return="JDocWeb/assets/images/custom/WW/man-icon.png"; 
                }
                if($sesso=='f')
                {
                    $return="JDocWeb/assets/images/custom/WW/woman-icon.png";
                }
            }
        }
        }
            
            return $return;
    }
    
    //custom Dimensione Immobiliare
    function get_foto_prospetto($recordid)
    {
        $table='user_immobili_page';
        $return="";
        
        //copertina
        $sql="
            SELECT *
            FROM user_immobili_page
            WHERE 
            (recordid_='$recordid')
            AND
            (category='Copertina' OR category like '%|;|Copertina|;|%' OR category like 'Copertina|;|%' OR category like '%|;|Copertina')
            ";
        $prospetto_pages['Copertina']=  $this->select($sql);
        $return=array();
        foreach ($prospetto_pages['Copertina'] as $key => $prospetto_page) {
            $path=$prospetto_page['path_'];
            $path=  str_replace("\\\\", "/", $path);
            $filename=$prospetto_page['filename_'];
            $extension=$prospetto_page['extension_'];
            $complete_path="../JDocServer/$path".$filename.".$extension";
            $prospetto_pages['Copertina'][$key]['complete_path']="";
            if(file_exists($complete_path))
            {
                /*if(!file_exists("../JDocServer/$path".$filename."_preview.jpg"))
                {
                    genera_preview("../JDocServer/$path".$filename, $extension);
                }*/
                $extension=strtolower($extension);
                if(($extension=='jpg')||($extension=='png'))
                {
                    //$complete_path="../JDocServer/$path".$filename."_preview.jpg";
                    $complete_path="../JDocServer/$path".$filename.".".$extension;
                    $prospetto_pages['Copertina'][$key]['complete_path']=$complete_path; 
                }
                
            }
        }
        
        //interni
        $prospetto_pages['Interni']=$this->get_foto_prospetto_categoria($recordid,'Interni');
        //esterni
        $prospetto_pages['Esterni']=$this->get_foto_prospetto_categoria($recordid,'Esterni');
        //Piantine
        $prospetto_pages['Piantine']=$this->get_foto_prospetto_categoria($recordid,'Piantina');
        
        $prospetto_pages['foto_vetrina1']=$this->get_foto_prospetto_categoria($recordid,'foto_vetrina1');
        
        $prospetto_pages['foto_vetrina2']=$this->get_foto_prospetto_categoria($recordid,'foto_vetrina2');
        
        $prospetto_pages['foto_vetrina3']=$this->get_foto_prospetto_categoria($recordid,'foto_vetrina3');
        
        $prospetto_pages['foto_vetrina4']=$this->get_foto_prospetto_categoria($recordid,'foto_vetrina4');
        
        $prospetto_pages['prospetto_descrizione']=$this->get_foto_prospetto_categoria($recordid,'prospetto_descrizione');
                
        $prospetto_pages['prospetto_paese']=$this->get_foto_prospetto_categoria($recordid,'prospetto_paese');
        
        
        
            
         return $prospetto_pages;
    }
    
    function get_foto_prospetto_categoria($recordid,$categoria)
    {
        $sql="
            SELECT *
            FROM user_immobili_page
            WHERE 
            (recordid_='$recordid')
            AND
            (category='$categoria' OR category like '%|;|$categoria|;|%' OR category like '$categoria|;|%' OR category like '%|;|$categoria')
            ORDER BY fileposition_    
            ";
        $prospetto_categoria_pages=  $this->select($sql);
        $return=array();
        foreach ($prospetto_categoria_pages as $key => $prospetto_categoria_page) {
            $path=$prospetto_categoria_page['path_'];
            $path=  str_replace("\\\\", "/", $path);
            $filename=$prospetto_categoria_page['filename_'];
            $extension=$prospetto_categoria_page['extension_'];
            $lowerextension=strtolower($extension);
            if(($lowerextension=='jpg')||($lowerextension=='png'))
            {
                $complete_path="../JDocServer/$path".$filename.".$extension";
                $return[$key]['complete_path']="";
                if(file_exists($complete_path))
                {
                    /*if(!file_exists("../JDocServer/$path".$filename."_preview.jpg"))
                    {
                        genera_preview("../JDocServer/$path".$filename, $extension);
                    }*/
                    $extension=$lowerextension;

                    //$complete_path="../JDocServer/$path".$filename."_preview.jpg";
                    $complete_path="../JDocServer/$path".$filename.".".$extension;
                    $return[$key]['complete_path']=$complete_path; 


                }
            }
            $return[$key]['extension']=$extension;
        }
        
        return $return;
    }
    
    
    /**
     * Ritorna i valori dei campi di un archivio per uno specifico record
     * @param String $mastertableid
     * @param String $recordid
     * @return array insieme dei valori
     * @author Alessandro Galli
     */
    function get_values_fields_table($tableid,$recordid)
    {
     $tableid=  strtolower($tableid);
        $table='user_'.$tableid;
        
        $select="SELECT * ";
        $from=" FROM ".$table;
        $where=" WHERE recordid_='$recordid'";

        $sql_finale=$select.$from.$where;
        
        $rows=$this->select($sql_finale);
        if(count($rows)==1)
        {
            return $rows[0];   
        }
        else
        {
            return array();
        }
    }
    
    function get_lookup_table_item_description($lookuptableid,$itemcode)
    {
        $itemcode=  str_replace("'", "''", $itemcode);
        $sql="SELECT itemdesc FROM sys_lookup_table_item WHERE lookuptableid='$lookuptableid' AND itemcode='$itemcode'";
        $result=  $this->select($sql);
        if(count($result)==1)
        {
            return $result[0]['itemdesc'];
        }
        else
        {
            return 'error';
        }
    }
    
    function get_cliente_id()
    {
        $cliente_id=  $this->db_get_value('sys_settings', 'value',"setting='cliente_id'");
        return $cliente_id;
    }
    
    function get_port()
    {
        $settings=  $this->get_settings();
        $port=$settings['port'];
        return $port;
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
    
    
    function reset_record($tableid,$recordid)
    {
        $sql="UPDATE user_$tableid SET ";
        $fields= $this->get_record($tableid, $recordid);
        $set='';
        foreach ($fields as $key => $field) {
            if($key!='recordid_')
            {
                if(($tableid=='presenzemensili')&&(($key=='anno')||($key=='mese')||($key=='recordiddipendenti_')||($key=='3mesidatascadenza')||($key=='no')||($key=='zonelavorative')||($key=='note')||($key=='datainizio_prova')||($key=='datafine_prova')||($key=='alias')))
                {
                    
                }
                else
                {
                    if($set!='')
                    {
                        $set=$set.",";
                    }
                    $set=$set."$key=null";
                }
            }
        }
        $sql=$sql.$set;
        $sql=$sql." WHERE recordid_='$recordid'";
        $this->execute_query($sql);
        //$this->syslog($sql);
    }
    
    function insert_record_page($tableid,$recordid,$userid,$original_path,$data=array())
    {
        $original_path=  str_replace("\\", "/", $original_path);
        if(file_exists($original_path))
        {
            
            $namefolder='000';
            $reversedParts = explode('/', strrev($original_path), 2);
            $filenameext='';
            $fileext='';
            if(count($reversedParts)>0)
            {
                $filenameext= strrev($reversedParts[0]);
                $fileext = pathinfo($filenameext, PATHINFO_EXTENSION);
            }
            $filename=  str_replace('.'.$fileext, '', $filenameext);
            $filename=  str_replace("'", "''", $filename);
            //$filename=preg_replace('/[^[:alnum:]-._~]/', '', $filename);
            $original_filename=  conv_text($filename);
            $new_filename=  $this->generate_filename($tableid);
            copy($original_path,"../JDocServer/archivi/$tableid/$namefolder/$new_filename.$fileext");

            $now = date('Y-m-d H:i:s');
            $page_tablename="user_".strtolower($tableid)."_page";
            
            
            $counter=  $this->db_get_value($page_tablename, 'counter_', "recordid_='$recordid'","ORDER BY counter_ DESC");
            if($counter==null)
                $counter=0;
            $counter=$counter++;

            $fileposition=  $this->db_get_value($page_tablename, 'fileposition_', "recordid_='$recordid'","ORDER BY fileposition_ DESC");
            if($fileposition==null)
                $fileposition=0;
            $fileposition=$fileposition++;

            $sql="INSERT INTO $page_tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,counter_,fileposition_,path_,filename_,extension_,original_name,filestatusid_,signed_,deleted_) 
                VALUES ('$recordid',$userid,'$now',$userid,'$now',$counter,$counter,'archivi\\\\".$tableid."\\\\".$namefolder."\\\\"."','$new_filename','$fileext','$original_filename','S','N','N') 
                ";
            $this->execute_query($sql);
        }

    }
    
    function salva_record($saved_tableid,$saved_recordid,$post)
    {
        $saved_table = "user_" . strtolower($saved_tableid);
        $settings=  $this->get_settings();
        $cliente_id=$settings['cliente_id'];
        $userid = $this->session->userdata('userid');
        $now = date('Y-m-d H:i:s');
        $funzione='modifica';
        if((isempty($saved_recordid))||($saved_recordid=='null'))
        {
            $funzione='inserimento';
            $saved_recordid=  $this->generate_recordid($saved_tableid);
        }
        else
        {
            $funzione='modifica';
            $prev_record=  $this->db_get_row('user_'.strtolower($saved_tableid),'*',"recordid_='$saved_recordid'");
        }
        if(array_key_exists('tables', $post))
        {
            $this->set_logquery('json salva_record',  json_encode($post['tables']));
            foreach ($post['tables'] as $tableid => $table_container) 
                {
                    $tablename='user_'.strtolower($tableid);
                   // $table_recordid=$table_container['recordid'];
                    if (array_key_exists('insert', $table_container)) {
                        foreach ($table_container['insert'] as $table) {
                            if (key_exists('fields', $table)) {
                                $new_recordid = "";
                                $totpages = 0;
                                
                                if (true) {
                                    
                                    $insert = "INSERT INTO $tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_";
                                    if($tableid==$saved_tableid)
                                    {
                                        $new_recordid = $saved_recordid;
                                    }
                                    else
                                    {
                                        $new_recordid =$this->generate_recordid($tableid);
                                    }
                                    $post['tables'][$tableid]['recordid']=$new_recordid;
                                    $values = " VALUES ('$new_recordid',$userid,'$now',$userid,'$now',$totpages,'N'";
                                    
                                    
                                        
                                    //CUSTOM Work&Work

                                    if ($tableid == "CANDID") {
                                        //CUSTOM POSTGRES
                                        //data registrazione
                                        $data_reg = date("Y-m-d 00:00:00");
                                        $insert = $insert . ",datareg";
                                        $values = $values . ",'$data_reg'";

                                        //CUSTOM POSTGRES
                                        //data scadenza
                                        $data_scadenza = date("Y-m-d", strtotime($data_reg . " +6 month"));
                                        $insert = $insert . ",scadenza";
                                        $values = $values . ",'$data_scadenza'";

                                        //id(NON recordid_) del nuovo candidato
                                        $id = $this->generate_id($tableid);
                                        $insert = $insert . ",id";
                                        $values = $values . ",$id";

                                        //consulente
                                        if(!array_key_exists('consulente',$table['fields']))
                                        {
                                            $result=$this->set_consulente($insert, $values);
                                            $insert=$result['insert'];
                                            $values=$result['values'];
                                        }
                                    }
                                    if ($tableid == "AZIEND") {
                                        //CUSTOM POSTGRES
                                        //data registrazione
                                        $data_reg = date("Y-m-d 00:00:00");
                                        $insert = $insert . ",dataregistrazione";
                                        $values = $values . ",'$data_reg'";

                                        //CUSTOM POSTGRES
                                        //data scadenza
                                        $data_scadenza = date("Y-m-d", strtotime($data_reg . " +6 month"));
                                        $insert = $insert . ",scadenza";
                                        $values = $values . ",'$data_scadenza'";

                                        //id azienda(non recordid_)
                                        $id = $this->generate_id($tableid);
                                        $insert = $insert . ",id";
                                        $values = $values . ",$id";

                                        //consulente

                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];


                                        //attiva
                                        //azienda stato
                                        //stato ultimo contatto
                                        //ultimo contatto
                                    }
                                    if ($tableid == "CONTRA") {

                                        //id(NON recordid_) del nuovo candidato
                                        $riferimen = $this->generate_riferimen($tableid);
                                        $insert = $insert . ",riferimen";
                                        $values = $values . ",$riferimen";
                                        if(array_key_exists('custom', $post))
                                        {
                                        if(array_key_exists('destinatario_contratto_attuale', $post['custom']['contra']))
                                        {
                                            if($post['custom']['contra']['destinatario_contratto_attuale']=='azienda')
                                            {
                                                $insert = $insert . ",destinat,destinat_";
                                                $values = $values . ",'A','Azienda'";
                                            }
                                            if($post['custom']['contra']['destinatario_contratto_attuale']=='dipendente')
                                            {
                                               $insert = $insert . ",destinat,destinat_";
                                               $values = $values . ",'D','Dipendente'";
                                            }
                                        }
                                        }
                                        if(array_key_exists('linkedmaster', $table_container['insert']))
                                        {
                                            //custom WW inizio
                                            if(array_key_exists('AZIEND', $table_container['insert']['linkedmaster']))
                                            {
                                                $aziend_recordid=$table_container['insert']['linkedmaster']['AZIEND']['value'];
                                                $fields_aziend=  $this->get_fields_record('AZIEND', $aziend_recordid);
                                                $insert = $insert . ",idazienda,ragsoc";
                                                $ragsoc=  str_replace("'", "''", $fields_aziend['ragsoc']);
                                                $values = $values . ",".$fields_aziend['id'].",'".$ragsoc."'";
                                            }
                                            
                                            if(array_key_exists('CANDID', $table_container['insert']['linkedmaster']))
                                            {
                                                $candid_recordid=$table_container['insert']['linkedmaster']['CANDID']['value'];
                                                $fields_candid=  $this->get_fields_record('CANDID', $candid_recordid);
                                                $insert = $insert . ",idcandid,cognome,nome";
                                                $cognome=  str_replace("'", "''", $fields_candid['cognome']);
                                                $nome=  str_replace("'", "''", $fields_candid['nome']);
                                                $values = $values . ",".$fields_candid['id'].",'".$cognome."','".$nome."'";
                                            }
                                            //custom WW fine
                                        }


                                    }

                                } 
                                else 
                                {
                                    $insert = "INSERT INTO $tablename (recordid_,recordid" . $saved_recordid . "_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_";
                                    $new_recordid = $this->generate_recordid($tableid);
                                    $values = " VALUES ('$new_recordid','$saved_recordid',$userid,'$now',$userid,'$now',$totpages,'N'";
                                    //inserimento automatico delle linkedmaster a cascata
                                    //TODO aggiungere un controllo per verificare che la linkedmaster che si inserirebbe automaticamente non sia già stata impostata forzata dall'utente.nel caso si tiene quella dell'utente
                                    $sql="select t1.tableid 
                                        from (select tableid from sys_table_link where tablelinkid='$tableid') as t1 join (select tableid from sys_table_link where tablelinkid='$saved_recordid') as t2 on t1.tableid=t2.tableid";
                                    $result=  $this->select($sql);
                                    foreach ($result as $key => $value) 
                                    {
                                        $linkedmaster_tableid=$value['tableid'];
                                        $linkedmaster_column="recordid".$linkedmaster_tableid."_";
                                        $sql="SELECT $linkedmaster_column  FROM user_".strtolower($master_tableid)." WHERE recordid_='$master_recordid'";
                                        $rows=  $this->select($sql);
                                        if(count($rows)>0)
                                        {
                                            if($this->isnotempty($rows[0][$linkedmaster_column]))
                                            {
                                                $linkedmaster_recordid=$rows[0][$linkedmaster_column];
                                                $insert=$insert.",$linkedmaster_column";
                                                $values=$values.",'$linkedmaster_recordid'";
                                            }
                                        }
                                    }
                                    if(strtoupper($tableid)=="AZCONT"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                    if(strtoupper($tableid)=="AZMISSIONI"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                    if(strtoupper($tableid)=="CANDCONTATTI"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                }

                                $sql_finale = '';
                                //CUSTOM UNILUDES INIZIO
                                $new_numeroprotocollo='';
                                $annocorrente=date('Y');
                                
                                if(array_key_exists('dataprotocollo', $table['fields']))
                                {
                                    $dataprotocollo=$table['fields']['dataprotocollo']['f_0']['value'][0];
                                    $annoprotocollo=date('Y', strtotime($dataprotocollo));
                                }
                                else
                                {
                                    $annoprotocollo=$annocorrente;
                                }

                                //CUSTOM UNILUDES FINE
                                
                                foreach ($table['fields'] as $fieldid => $fields_container) 
                                {
                                    $value="";
                                    foreach ($fields_container as $key => $field) 
                                    {
                                        $value_temp = $field['value'][0];
                                        $value_temp = str_replace("'", "''", $value_temp);
                                        if($key=='f_0')
                                        {
                                            $value=$value.$value_temp;
                                        }
                                        else
                                        {
                                            $value=$value."|;|".$value_temp;
                                        }
                                        $type = $field['type'];
                                    }
                                    if ($value != 'loading')
                                    {
                                        //$value = str_replace("'", "''", $value);
                                        $insert = $insert . ",$fieldid";
                                        if($this->isnotempty($value))
                                        {
                                            if ($type == 'parola-testolibero') 
                                            {
                                                $values = $values . ",'$value'";
                                            }
                                            if ($type == 'parola-lookup') 
                                            {
                                                $values = $values . ",'$value'";
                                                //inserisco campo di descrizione
                                                /*if(($cliente_id=='Work&Work')||(($cliente_id=='VIS')&&($tableid=='archiviodocumenti')))
                                                {
                                                    $descrizione = $this->get_descrizione_lookup($tableid, $fieldid, $value);
                                                    $descrizione = str_replace("'", "''", $descrizione);
                                                    if ($descrizione != null) {
                                                        $insert = $insert . ",$fieldid" . "_";
                                                        $values = $values . ",'$descrizione'";
                                                    }
                                                }*/
                                            }
                                            if ($type == 'numero') {
                                                $values = $values . " ,$value";
                                            }
                                            if ($type == 'data') {
                                                $date=date('Y-m-d', strtotime($value));
                                                $values = $values . " ,'$date'";
                                            }
                                            if ($type == 'ora') {
                                                $time=date('H:i', strtotime($value));
                                                $values = $values . " ,'$time'";
                                            }
                                            if ($type == 'memo') {
                                                $values = $values . " ,'$value'";
                                            }
                                            if ($type == 'utente') {
                                                $values = $values . " ,'$value'";
                                            }
                                            
                                        }
                                        else
                                        {
                                            $value='null';
                                            if ($type == 'seriale') {
                                                $value=  $this->generate_seriale($tableid, $fieldid);
                                            }
                                            
                                            //CUSTOM UNILUDES INIZIO
                                            if(($tableid=='protocolloentrata')||($tableid=='protocollouscita'))
                                            {
                                                if($fieldid=='annonprot')
                                                {

                                                        //$value="'$annoprotocollo-$new_numeroprotocollo'";
                                                        $value="'$annoprotocollo-$new_numeroprotocollo'";
                                                }
                                                if($fieldid=='annoprotocollo')
                                                {

                                                        $value="$annoprotocollo";
                                                }
                                            }
                                            
                                            if($fieldid=='numeroprotocollo')
                                            {
                                                if(($tableid=='protocolloentrata')||($tableid=='protocollouscita'))
                                                {                                  
                                                    
                                                    $tempsql="SELECT max(numeroprotocollo) as maxnum FROM user_".strtolower($tableid)." WHERE dataprotocollo <= '$annoprotocollo-12-31' AND dataprotocollo >= '$annoprotocollo-01-01'";

                                                    $tempresult=  $this->select($tempsql);
                                                    if(count($tempresult)>0)
                                                    {
                                                        $maxnum=$tempresult[0]['maxnum'];
                                                        $new_numeroprotocollo=$maxnum+1;
                                                        $value=$new_numeroprotocollo;
                                                    }
                                                    else
                                                    {
                                                        $value='null';
                                                    }
                                                }
                                                if($tableid=='documentiprotocolloentrata')
                                                {
                                                    $value='';
                                                    $temp_master_recordid=$table_container['insert']['linkedmaster']['protocolloentrata']['value'];
                                                    $tempsql="SELECT annonprot FROM user_protocolloentrata WHERE recordid_='$temp_master_recordid'";
                                                    $tempresult=  $this->select($tempsql);
                                                    if(count($tempresult)>0)
                                                    {
                                                        $annonprot=$tempresult[0]['annonprot'];
                                                        if($this->isnotempty($annonprot))
                                                        {
                                                            if( ($x_pos = strpos($annonprot, '-')) !== FALSE )
                                                                $annonprot = substr($annonprot, $x_pos + 1);
                                                            $value=$annonprot;
                                                        }
                                                    }
                                                }
                                            }
                                           
                                            if ($type == 'calcolato') {
                                                //$value="'01:30'";
                                            }
                                            
                                           $values = $values . " ,$value";     
                                        }
                                    }
                                    
                                }
                                $insert = $insert . ")";
                                $values = $values . ")";
                                $sql_finale = $insert . $values;
                                $this->set_logquery('inserimento', $sql_finale);
                                $this->execute_query($sql_finale);



                                //UPDATE SYS_TABLE
                                $sql_lastrecordid = "UPDATE sys_table SET lastrecordid='$new_recordid' WHERE id='$tableid'";
                                $this->set_logquery('inserimento',$sql_lastrecordid);
                                $this->execute_query($sql_lastrecordid);

                            $table_recordid=$new_recordid;
                            }
                        
                        }
                    if (array_key_exists('linkedmaster', $table_container['insert'])) {
                        
                        foreach ($table_container['insert']['linkedmaster'] as $linkedmaster_tableid => $linkedmaster_container) {
                            $linkedmaster_recordid = $linkedmaster_container['value'];
                            $linkedmaster_tableid=  strtolower($linkedmaster_tableid);
                            $sql_linkedmaster = "UPDATE $saved_table SET recordid".$linkedmaster_tableid."_='$linkedmaster_recordid' WHERE recordid_='$saved_recordid' ";
                            $this->set_logquery('inserimento',$sql_linkedmaster);
                            $this->execute_query($sql_linkedmaster);     
                        }                    
                        }
                    }


                //TABELLE GIA' ESISTENTI DA MODIFICARE
                //$new_recordid="";
                if(array_key_exists('update', $table_container))
                    {
                    $table_recordid=$saved_recordid; //;$table_container['recordid'];
                    foreach($table_container['update'] as $key=>$table) 
                    {
                        
                        if(key_exists('fields', $table))
                        {
                            //$new_recordid="";    
                            $totpages=0;
                            $lastupdate=  date('Y-m-d H:i:s');
                            $creatorid=$this->session->userdata('userid');
                            $update="UPDATE $tablename";
                            $set="SET lastupdaterid_=$userid,lastupdate_='$now'";
                            $where="WHERE true";
                            $sql_finale='';
                            foreach ($table['fields'] as $fieldid=>$fields_container)
                            {
                                $value="";
                                foreach ($fields_container as $key => $field) 
                                {
                                    $value_temp = $field['value'][0];
                                    $value_temp = str_replace("'", "''", $value_temp);
                                    if($value_temp!='')
                                    {
                                        if($value!='')
                                        {
                                            $value=$value."|;|";
                                        }
                                        $value=$value.$value_temp;
                                    }
                                    
  
                                    $type = $field['type'];
                                }

                                        if($value!='loading')
                                        {

                                            $set=$set.",".$fieldid."=";
                                            if($value=='')
                                            {
                                                $set=$set."null";
                                            }
                                            else
                                            {
                                                if($type=='parola-testolibero')
                                                {
                                                    $set=$set."'$value'";
                                                }

                                                if ($type == 'parola-lookup') 
                                                {
                                                    $set=$set."'$value'";
                                                    //inserisco campo di descrizione
                                                    if(($cliente_id=='Work&Work')||(($cliente_id=='VIS')&&($tableid=='archiviodocumenti')))
                                                    //if(true)
                                                    {
                                                        $descrizione = $this->get_descrizione_lookup($tableid, $fieldid, $value);
                                                        $descrizione = str_replace("'", "''", $descrizione);

                                                    }
                                                }
                                                if($type=='numero')
                                                {
                                                    $set=$set."$value";
                                                }
                                                if($type=='data')
                                                { 
                                                    $date=date('Y-m-d', strtotime($value));
                                                    $set=$set."'$date'";
                                                }
                                                if($type=='ora')
                                                { 
                                                    $set=$set."'$value'";
                                                }
                                                if($type=='memo')
                                                {
                                                    $set=$set."'$value'";
                                                }
                                                if($type=='utente')
                                                { 
                                                    $set=$set."'$value'";
                                                }

                                            }
                                        }
                                    

                            }  
                        $where="WHERE recordid_='$table_recordid'";    
                        $sql_finale=$update." ".$set." ".$where;
                        $this->set_logquery('modifica',$sql_finale);
                        $this->execute_query($sql_finale); 

                    }

                }
                }
            
            }
            
            //collego eventuali linkedmaster inserite direttamente dalla scheda del record principale
            foreach ($post['tables'] as $tableid => $table_container) 
            {
                if($tableid!=$saved_tableid)
                {
                    $sql="UPDATE user_".strtolower($saved_tableid)." SET recordid".strtolower($tableid)."_='".$table_container['recordid']."' where recordid_='$saved_recordid'";
                    $this->execute_query($sql);
                }
            }
            
        }
        
        // PERMESSI UTENTE inizio
        if(array_key_exists('permessi_utente', $post))
        {
            $tableowner='user_'.strtolower($tableid)."_owner";
            foreach ($post['permessi_utente'] as $key => $userid) {
                $sql="
                    INSERT INTO $tableowner 
                    (recordid_,ownerid_)
                    VALUES ('$saved_recordid',$userid)
                    ";
                $this->execute_query($sql);
            }
        }
        
        // PERMESSI UTENTE fine
        
        
        
        
        //PROPAGATION INIZIO
        
        if((array_key_exists('origine', $post)))
        {
            
            
            $origine_tableid=$post['origine']['tableid'];
            $origine_table='user_'.strtolower($origine_tableid);
            $origine_recordid=$post['origine']['recordid'];
            // aggiornamento con la tabella di origine inizio
            if(isnotempty($origine_tableid)&&  isnotempty($origine_recordid))
            {
  
                    $table="user_".strtolower($tableid);
                    $origine_column="recordid".strtolower($origine_tableid)."_";
                    $sql="UPDATE $table set $origine_column='$origine_recordid' where recordid_='$saved_recordid'";
                    $this->execute_query($sql);
                
                
            }
            // aggiornamento con la tabella di origine fine
            
            //aggiornamento delle linkedmaster condivise con la tabella di origine inizio
            $linkedmaster_tables_saved=  $this->get_linkedmastertables($saved_tableid);
            $linkedmaster_tables_origine=  $this->get_linkedmastertables($origine_tableid);
            foreach ($linkedmaster_tables_saved as $key_linkedmaster_table_saved => $linkedmaster_table_saved) {
                if(in_array($linkedmaster_table_saved, $linkedmaster_tables_origine))
                {
                    $linkedmaster_field='recordid'.  strtolower($linkedmaster_table_saved).'_';
                    $linkedmaster_table_saved_value=  $this->db_get_value($saved_table,$linkedmaster_field,"recordid_='$saved_recordid'");
                    if(isempty($linkedmaster_table_saved_value))
                    {
                        $linkedmaster_table_origine_value=  $this->db_get_value($origine_table,$linkedmaster_field,"recordid_='$origine_recordid'");
                        if(!isempty($linkedmaster_table_origine_value))
                        {
                            $sql="UPDATE $saved_table
                                SET $linkedmaster_field='$linkedmaster_table_origine_value'
                                WHERE recordid_='$saved_recordid'
                                ";
                            $this->execute_query($sql);
                        }
                    }
                }
            }
            
            //aggiornamento delle linkedmaster condivise con la tabella di origine fine
            
            
            

                $propagations=  $this->db_get('sys_propagation','*',"tableid='$saved_tableid' AND mastertableid='$origine_tableid'");
                $saved_record=  $this->db_get_row('user_'.strtolower($saved_tableid),'*',"recordid_='$saved_recordid'");
                foreach ($propagations as $key => $propagation) 
                {
                    $fieldid=$propagation['fieldid'];
                    $value=$saved_record[$fieldid];
                    $origine_fieldid=$propagation['masterfieldid'];
                    $origine_table="user_".strtolower($origine_tableid);
                    $value=  str_replace("'", "''", $value);
                    if(isnotempty($value))
                    {
                        $sql="
                                UPDATE $origine_table
                                SET $origine_fieldid='$value'
                                WHERE recordid_='$origine_recordid'

                            ";
                        $this->execute_query($sql);
                    }
                }
            
            
        }        
        //PROPAGATION FINE
        
     
        // SALVATAGGIO FILE INIZIO
        if(array_key_exists("files", $post))
        {
          $this->modifica_allegati($post, $saved_tableid, $saved_recordid);  
        }
        //SALVATAGGIO FILE FINE

        
        
        
        //CUSTOM ABOUT-X INIZIO
        if($cliente_id=='About-x')
        {
            
            
            if($saved_tableid=='timesheet')
            { 
                $sql="SELECT * FROM user_timesheet WHERE recordid_='$saved_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    //calcola durata assistenza
                    $orainizio=$fields['orainizio'];
                    $orafine=$fields['orafine'];
                    if((isnotempty($orainizio))&&(isnotempty($orafine)))
                    {
                        $date_diff=  $this->date_diff($orainizio, $orafine);
                        $durata='null';
                        if(($date_diff['h']!=0||$date_diff['m']!=0))
                        {
                            //aggiorno la durata del timesheet
                            $durata="'".$date_diff['h'].":".$date_diff['m']."'";
                            $sql="UPDATE user_timesheet SET totore=$durata WHERE recordid_='$saved_recordid'";
                            $this->execute_query($sql);
                            
                            //aggiorno il monteore
                            $recordid_azienda=$fields['recordidaziende_'];
                            if($fields['tipoassistenza']=='Assistenza monte ore')
                            {
                                $monteore_diff=$date_diff['h']+$date_diff['m']/60;
                                $sql="UPDATE user_aziende
                                    SET monteore=monteore - $monteore_diff
                                    WHERE recordid_='$recordid_azienda'
                                    ";
                                $this->execute_query($sql);
                            }
                            
                        }
                        

                        //aggiorno la segnalazione relativa
                        $recordid_segnalazione=$fields['recordidsegnalazioni_'];
                        if(isnotempty($recordid_segnalazione))
                        {
                            $this->aggiorna_segnalazione($recordid_segnalazione);
                        }
                    }
                }

            }
            
            if($saved_tableid=='segnalazioni')
            {
                $row=  $this->db_get_row('user_segnalazioni', '*', "recordid_='$saved_recordid'");
                $segnalatore=$row['segnalatore'];
                $recordid_azienda=$row['recordidaziende_'];
                if((isempty($segnalatore))&&(isnotempty($recordid_azienda)))
                {
                    $segnalatore=  $this->get_keyfieldlink_value('segnalazioni','aziende',$recordid_azienda,false);
                }
                $sql="
                    UPDATE user_segnalazioni
                    SET segnalatore='$segnalatore'
                    WHERE recordid_='$saved_recordid'
                    ";
                $this->execute_query($sql);

            }
        
            if($saved_tableid=='commessa_prodotti')
            {
                $row=  $this->db_get_row('user_commessa_prodotti', '*', "recordid_='$saved_recordid'");
                $recordid_prodotto=$row['recordidprodotti_'];
                $recordid_commessa=$row['recordidcommesse_'];
                $tipoprodotto=$row['tipoprodotto'];
                $quantita=1;
                if($this->isempty($quantita))
                {
                    $quantita=0;
                }

                $prezzovendita=  $row['prezzovendita'];
                if($this->isnotempty($prezzovendita))
                {
                    $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                    $importoriga=$prezzovendita*$quantita;
                    $sql="
                    INSERT INTO user_commessa_task
                    (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                    VALUES
                    ('$new_recordid_task_commessa','Vendita',$quantita,$prezzovendita,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                    ";
                $this->execute_query($sql);
                }

                if($tipoprodotto=='Multifunzione')
                {
                    $basechargemanutenzione=$row['basechargemanutenzione'];
                     if($this->isnotempty($basechargemanutenzione))
                    {
                        $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                        $importoriga=$basechargemanutenzione*$quantita;
                        $sql="
                        INSERT INTO user_commessa_task
                        (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                        VALUES
                        ('$new_recordid_task_commessa','Manutenzione',$quantita,$basechargemanutenzione,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                        ";
                    $this->execute_query($sql);
                    }

                    $importomensilenoleggio=$row['importomensilenoleggio'];
                    if($this->isnotempty($importomensilenoleggio))
                    {
                        $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                        $importoriga=$importomensilenoleggio*$quantita;
                        $sql="
                        INSERT INTO user_commessa_task
                        (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                        VALUES
                        ('$new_recordid_task_commessa','Noleggio',$quantita,$importomensilenoleggio,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                        ";
                    $this->execute_query($sql);
                    }

                    $importomensilexpps=$row['importomensilexpps'];
                    if($this->isnotempty($importomensilenoleggio))
                    {
                        $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                        $importoriga=$importomensilexpps*$quantita;
                        $sql="
                        INSERT INTO user_commessa_task
                        (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                        VALUES
                        ('$new_recordid_task_commessa','XPPS',$quantita,$importomensilexpps,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                        ";
                    $this->execute_query($sql);
                    }

                    $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                    $sql="
                    INSERT INTO user_commessa_task
                    (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                    VALUES
                    ('$new_recordid_task_commessa','Eccedenza copie',1,0,0,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                    ";
                    $this->execute_query($sql);
                }


            }
        }
        
        
        //CUSTOM ABOUT-X FINE
        
        
        //CUSTOM DIMENSIONE IMMOBILIARE inizio
        
        if(($cliente_id=='Dimensione Immobiliare'))
        { 
            if(($saved_tableid=='immobili'))
            {
                $sql="SELECT * FROM user_immobili WHERE recordid_='$saved_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    if($funzione=='inserimento')
                    {
                        if($fields['tipo']=='terrenoinvendita')
                        {

                            //mappale
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mappale' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Scheda calcolo stima
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Estratto Registro Fondiario
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);

                        }
                        if($fields['tipo']=='palazzinainvendita')
                        {
                            //Mandato
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Acuisizione
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Mappale
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mappale' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Estratto Registro Fondiario
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Regolamento condominiale
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Regolamento condominiale' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Relazione tecnica
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Spese generali
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese generali' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Copia assicurazione stabile
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Copia assicurazione stabile' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RASI
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RADON
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Importanti lavori eseguiti
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Importanti lavori eseguiti' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Scheda calcolo stima
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Contratti di locazione
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Contratti di locazione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);


                        }
                        if($fields['categoria']=='appartamento')
                        {
                            //Mandato
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Acuisizione
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Costituzione PPP
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Costituzione PPP' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Estratto Registro Fondiario
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Regolamento condominiale
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Regolamento condominiale' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Polizza assicurativa
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Polizza assicurativa' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Spese condominiali
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese condominiali' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Copia assicurazione stabile
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Copia assicurazione stabile' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Ultimi 2 verbali assemblea
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Ultimi 2 verbali assemblea' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Ultimi 2 conguagli
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Ultimi 2 conguagli' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Relazione tecnica
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RASI
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RADON
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);


                        }
                        if(strpos($fields['categoria'],'casa')!==FALSE)
                        {
                            //Mandato
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Acuisizione
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Estratto Registro Fondiario
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Relazione tecnica
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Estratto censuario
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto censuario' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Spese generali
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese generali' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Importanti lavori eseguiti
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Importanti lavori eseguiti' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Polizza assicurativa casa
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Polizza assicurativa casa' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RASI
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Certificato RADON
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                            //Scheda calcolo stima
                            $linked_recordid=  $this->generate_recordid('immobile_documenti');
                            $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$saved_recordid')";
                            $this->execute_query($sql);
                        }
                    }

                    //imposto il campo calcolato prezzo
                    $prezzo='null';
                    if($fields['tipo']=='immobileinvendita')
                    {
                        $prezzo=$fields['imm_prezzoimmobile'];
                    }
                    if($fields['tipo']=='terrenoinvendita')
                    {
                        $prezzo=$fields['ter_prezzo_vendita'];
                    }
                    if($fields['tipo']=='palazzinainvendita')
                    {
                        $prezzo=$fields['pal_prezzoimmobile'];
                    }
                    if($prezzo=='')
                    {
                        $prezzo='null';
                    }
                    $sql="UPDATE user_immobili SET prezzo=$prezzo WHERE recordid_='$saved_recordid'";
                    $this->execute_query($sql);

                    //imposto il campo calcolato cognomeproprietario
                    $recordid_contatti=$fields['recordidcontatti_'];
                    if($this->isnotempty($recordid_contatti))
                    {
                        $contatto=  $this->db_get_row('user_contatti', '*', "recordid_='$recordid_contatti'");
                        if($contatto!=null)
                        {
                            $cognome=$contatto['cognome'];
                            $sql="UPDATE user_immobili SET cognomeproprietario='$cognome' WHERE recordid_='$saved_recordid'";
                            $this->execute_query($sql);
                        }
                    }
                    
                    //imposta il campo calcolato riferimento 
                    $immobile_id=$fields['id'];
                    $immobile_consulente=$fields['consulente'];
                    $consulente=  $this->db_get_row('sys_user','*',"id=$immobile_consulente");
                    $consulente_cognome=$consulente['lastname'];
                    $consulente_nome=$consulente['firstname'];
                    $riferimento=$immobile_id."-".$consulente_nome[0].$consulente_cognome[0].$immobile_consulente;
                    $sql="UPDATE user_immobili SET riferimento='$riferimento' WHERE recordid_='$saved_recordid'";
                    $this->execute_query($sql);

                    //imposto il campo locali_num
                    $locali_num='null';
                    if($fields['tipo']=='immobileinvendita')
                    {
                        $locali_num=$fields['imm_locali_num'];
                    }
                    if($fields['tipo']=='immobileinaffitto')
                    {
                        $locali_num=$fields['aff_locali_num'];
                    }
                    if($locali_num=='')
                    {
                        $locali_num='null';
                    }


                    $sql="UPDATE user_immobili SET locali_num=$locali_num WHERE recordid_='$saved_recordid'";
                    $this->execute_query($sql);


                    //aggiorno i portali in base allo stato
                    if(($fields['stato']=='Eliminato')||($fields['stato']=='Venduto')||($fields['stato']=='Affittato'))
                    {
                        $portali=  $this->db_get('user_immobili_portali', '*', "recordidimmobili_='$saved_recordid'");
                        foreach ($portali as $key => $portale) {
                            $recordid_portale=$portale['recordid_'];
                            $sql="UPDATE user_immobili_portali SET status='Non pubblicato' WHERE recordid_='$recordid_portale'";
                            $this->execute_query($sql);
                        }
                    }
                } 
            }
        
            if(($saved_tableid=='telemarketing'))
            {
                $saved_telemarketing=$this->db_get_row('user_telemarketing', '*', "recordid_='$saved_recordid'");
                $note_telefonata=$saved_telemarketing['note_telefonata'];
                $immobile_recordid=$saved_telemarketing['recordidimmobili_'];
                $contatto_recordid=$saved_telemarketing['recordidcontatti_'];
                $new_recordid_agenda=  $this->generate_recordid('agenda');
                $userid=  $this->get_userid();
                $data=  date('Y-m-d');

                $sql="INSERT INTO user_agenda
                    (recordid_,utente,data,tipoattivita,note,recordidimmobili_,recordidcontatti_,recordidtelemarketing_)
                    VALUES
                    ('$new_recordid_agenda','$userid','$data','Telefonata','$note_telefonata','$immobile_recordid','$contatto_recordid','$saved_recordid');
                    ";
                $this->execute_query($sql);
            }
        
            if($saved_tableid=='immobili_richiesti')
            {
                if(($funzione=='inserimento'))
                {
                   

                    $new_recordid_proposta='';

                    $fields_richiesta=  $this->db_get_row('user_immobili_richiesti', '*', "recordid_='$saved_recordid'");
                    $immobile_richiesto_recordid=$fields_richiesta['recordidimmobili_'];
                    $recordid_contatti=$fields_richiesta['recordidcontatti_'];
                    $provenienza_richiesta=$fields_richiesta['provenienza'];
                    $recall_richiesta=$fields_richiesta['recall'];
                    $stato_richiesta=$fields_richiesta['stato'];
                    $consulente_richiesta=$fields_richiesta['consulente'];
                    
                    //aggiorno l'agenda relativamente alla richiesta
                    $new_recordid_agenda=  $this->generate_recordid('agenda');
                    $userid=  $this->get_userid();
                    $data=  date('Y-m-d');

                    $sql="INSERT INTO user_agenda
                        (recordid_,utente,data,tipoattivita,recordidimmobili_,recordidcontatti_,recordidimmobili_richiesti_)
                        VALUES
                        ('$new_recordid_agenda','$userid','$data','Inserimento richiesta','$immobile_richiesto_recordid','$recordid_contatti','$saved_recordid');
                        ";
                    $this->execute_query($sql);
                    
                    if(isnotempty($immobile_richiesto_recordid))
                    {
                        //aggiorno i dati della richiesta in base all'immobile selezionato nella richiesta
                        $immobile_richiesto=  $this->db_get_row('user_immobili','*',"recordid_='$immobile_richiesto_recordid'");
                        $paese=$immobile_richiesto['paese'];
                        $tipo=$immobile_richiesto['tipo'];
                        $categoria=$immobile_richiesto['categoria'];
                        $sql="
                            UPDATE user_immobili_richiesti
                            SET paese='$paese',tipo='$tipo',categoria='$categoria'
                            WHERE recordid_='$saved_recordid'
                            ";
                        $this->execute_query($sql);
                        
                        //aggiungo la proposta dell'immobile richiesto
                        $new_recordid_proposta=  $this->generate_recordid('immobili_proposti');
                        $today=  date('Y-m-d');
                        
                        $sql="INSERT INTO user_immobili_proposti
                            (recordid_,consulente,dataproposta,recall,statoproposta,provenienzaproposta,datalast,recordidimmobili_,recordidcontatti_,recordidimmobili_richiesti_)
                            VALUES
                            ('$new_recordid_proposta','$consulente_richiesta','$today','$recall_richiesta','$stato_richiesta','$provenienza_richiesta','$today','$immobile_richiesto_recordid','$recordid_contatti','$saved_recordid');
                            ";
                        $this->execute_query($sql);
                        
                        
                        //aggiorno l'agenda relativamente alla proposta
                        $new_recordid_agenda=  $this->generate_recordid('agenda');
                        $userid=  $this->get_userid();
                        $data=  date('Y-m-d');

                        $sql="INSERT INTO user_agenda
                            (recordid_,utente,data,tipoattivita,recordidimmobili_,recordidcontatti_,recordidimmobili_proposti_)
                            VALUES
                            ('$new_recordid_agenda','$userid','$data','Inserimento richiesta','$immobile_richiesto_recordid','$recordid_contatti','$new_recordid_proposta');
                            ";
                        $this->execute_query($sql);
                        
                    } 
                        
                    
                    //eseguo il matching
                    $this->matching_richiesta($saved_recordid);
                    
                    //cambio il $saved_recordid in modo da ritornare il recordid della proposta, perchè dopo il salvataggio della richiesta mostro all'utente la relativa proposta
                    $saved_recordid=$new_recordid_proposta;  
                    

                }


                
            }

            if($tableid=='immobili')
            {
                $this->matching_immobile($saved_recordid);
            }
        }
        
        
        
        
        
        //CUSTOM DIMENSIONE IMMOBILIARE fine
        
        
        // CUSTOM SCHLEGEL INIZIO
        if(($cliente_id=='Schlegel')&&($tableid=='pratiche'))
        { 
            $pratica=  $this->db_get_row('user_pratiche','*',"recordid_='$saved_recordid'");
            $id=$pratica['id'];
            $prefisso=$pratica['prefisso'];
            $numero= strtoupper($prefisso)."-".$id;
            $sql="UPDATE user_pratiche SET numero='$numero' WHERE recordid_='$saved_recordid'";
            $this->execute_query($sql);
        }
        if(($cliente_id=='Schlegel')&&($tableid=='prestazioni'))
        { 
            $prestazione=  $this->db_get_row('user_prestazioni','*',"recordid_='$saved_recordid'");
            $pratica_recordid=$prestazione['recordidpratiche_'];
            $pratica=$this->db_get_row('user_pratiche','*',"recordid_='$pratica_recordid'");
            $tariffa=$pratica['tariffa'];
            $tempomin=$prestazione['tempomin']+$prestazione['tempotrasfertamin'];
            $tempochf=($tempomin/60)*$tariffa;
            $tempochf=round($tempochf,2);
            $sql="UPDATE user_prestazioni SET tempochf='$tempochf' WHERE recordid_='$saved_recordid'";
            $this->execute_query($sql);
        }
        //CUSTOM SCHLEGEL FINE
        $return=$saved_recordid;
        
        // CUSTOM FUTURE ACCOUNTING INIZIO
        if(($cliente_id=='future')&&($tableid=='timesheet5'))
        { 
            $timesheet=  $this->db_get_row('user_timesheet5','*',"recordid_='$saved_recordid'");
            $orainizio=$timesheet['orainizio'];
            $orafine=$timesheet['orafine'];
            $date_diff=  $this->date_diff($orainizio, $orafine);
            $durata='null';
            if(($date_diff['h']!=0||$date_diff['m']!=0))
            {
                $durata="'".$date_diff['h'].":".$date_diff['m']."'";
            }
            $sql="UPDATE user_timesheet5 SET durata=$durata WHERE recordid_='$saved_recordid'";
            $this->execute_query($sql);
            
        }
        // CUSTOM FUTURE ACCOUNTING FINE
        
        // CUSTOM 18-24 INIZIO
            if($cliente_id=='18-24')
            {
                if(($tableid=='candidatiproposti')&&($funzione=='inserimento'))
                {
                    $proposta=  $this->db_get_row('user_candidatiproposti','*',"recordid_='$saved_recordid'");
                    $recordid_azienda= $proposta['recordidaziende_'];
                    $azienda=$this->db_get_row('user_aziende','*',"recordid_='$recordid_azienda'");
                    $mailfrom_userid=1;
                    $mailto=$azienda['email'];
                    $mailsubject="18-24 potenziale candidato";
                    $mailbody="Buongiorno, vi abbiamo appena inoltrato il profilo di un potenziale candidato che potrete visionare in formato anonimo nel vostro account aziendale <br/>
                        <a href='https://www.18-24.ch/progetto1824/index.php/App_controller/load_login_aziende/'>Area Aziende</a><br/>
                        <br/>
                        Qualora il Curriculum Vitae rispecchiasse il profilo da voi ricercato, vi chiediamo gentilmente di volercene dare comunicazione e noi provvederemo a fissarvi un incontro conoscitivo e ad inviarvi il CV completo di dati sensibili.<br/>
                        <br/>
                        Cordiali saluti<br/>
                        <br/>
                        Il Team 18-24
                        ";
                    $this->push_mail_queue_smart($mailfrom_userid, $mailto, $mailsubject, $mailbody);
                }
                
                if($tableid=='candidati')
                {
                    if(($fieldid=='profilovalidato')&&($field['value'][0]=='si'))
                    {
                        $candidato=  $this->db_get_row('user_candidati','*',"recordid_='$saved_recordid'");
                        $nome=$candidato['nome'];
                        $rif=$candidato['rif'];
                        $mailfrom_userid=1;
                        $mailto=$candidato['email'];
                        $mailsubject="Profilo pubblicato su 18-24";
                        $mailbody="
                            Ciao $nome, il tuo profilo è appena stato pubblicato.<br/>
                            Potrai trovarlo nella sezione “RICERCA CANDIDATI” con il numero di riferimento RIF $rif.<br/>
                            <br/>
                            Il Curriculum lo puoi modificare e aggiornare in qualsiasi momento attraverso il tuo account. <br/>
                            <br/>
                            Se un’azienda fosse interessata al tuo profilo ti contatteremo per fissare un colloquio di lavoro.<br/>
                            <br/>
                            Qualora dovessi trovare tu un impiego potrai disattivare la pubblicazione, in questo modo non sarai più visibile online.<br/>
                            Potrai comunque mantenere il suo Curriculum salvato nel tuo account, così saprai dove trovarlo ogni qualvolta ne avrai bisogno.<br/>
                            <br/>
                            Ti auguriamo un’ottima giornata.<br/>
                            <br/>
                            Il Team 18-24<br/>
                            <br/>
                            ";
                        $this->push_mail_queue_smart($mailfrom_userid, $mailto, $mailsubject, $mailbody,'','jdwalert@gmail.com');
                        $this->check_newsletter_aziende($saved_recordid);
                    }
                    
                }
                if($tableid=='visualizzazioni')
                {
                    /*if(($fieldid=='stato')&&($field['value'][0]=='Assunto'))
                    {
                        $stato=  $this->db_get_row('user_visualizzazioni','*',"recordid_='$saved_recordid'");
                        $recordid_azienda='';
                        $nome=$candidato['nome'];
                        $rif=$candidato['rif'];
                        $mailfrom_userid=1;
                        $mailto=$candidato['email'];
                        $mailsubject="Profilo pubblicato su 18-24";
                        $mailbody="
                            Spettabile *nomeazienda 
                            Vi ringraziamo per aver dato l'opportunità ad un giovane di inserirsi nel mondo del lavoro. 
                            Rimaniamo a vostra disposizione qualora necessitaste di nuovi profili
                            <br/>
                            Il Team<br/>
                            <br/>
                            18-24<br/>
                            ";
                        $this->push_mail_queue_smart($mailfrom_userid, $mailto, $mailsubject, $mailbody,'','jdwalert@gmail.com');
                        $this->check_newsletter_aziende($saved_recordid);
                    }*/
                    
                }
                
                
               
            }
                    
        // CUSTOM 18-24 FINE
            
        // CUSTOM 3P INIZIO
            if($cliente_id=='3p')
            {
                // CUSTOM 3P modifica contratto INIZIO
                if($tableid=='contratti')
                {
                    $return=$this->custom_3p_salva_contratto($saved_recordid);
                }
                
                // CUSTOM 3P modifica contratto FINE
                
                if($tableid=='rapportidilavoro')
                {
                    $rapporto=  $this->db_get_row('user_rapportidilavoro','*',"recordid_='$saved_recordid'");
                    $recordid_dipendente=$rapporto['recordiddipendenti_'];
                    $data=$rapporto['data'];
                    $mese=date("n", strtotime($data));
                    $giorno=date("j", strtotime($data));
                    $giorno_fieldid="g".$giorno."d";
                    $presenzemensili=$this->db_get_row('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente' AND mese='$mese'");
                    $presenzemensili_recordid= $presenzemensili['recordid_'];
                    
                    $giorno_value=$presenzemensili[$giorno_fieldid];
                    $giorno_value= str_replace('@sync', '', $giorno_value);
                    $giorno_value=$giorno_value."@sync";
                    $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$presenzemensili_recordid'";
                    $this->execute_query($sql);
                    
                    //$this->add_custom_update('presenze',$presenzemensili_recordid);
                    $this->aggiorna_presenze($presenzemensili_recordid);
                    //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$presenzemensili_recordid','todo')";
                    //$this->execute_query($sql);
                }
                
                if($tableid=='dipendenti')
                {
                    //$this->add_custom_update('dipendente',$saved_recordid);
                    $this->genera_rapportidilavoro($saved_recordid);
                    $this->add_custom_update_php('aggiorna_presenze_dipendente', $saved_recordid);
                    $this->add_custom_update_php('aggiorna_presenze_completo_noid_add_custom_update', $saved_recordid);
                    
                    $dipendente=$this->db_get_row('user_dipendenti','*',"recordid_='$saved_recordid'");
                    if($funzione=='inserimento')
                    {
                        $datainiziocollaborazione=$dipendente['datainiziocollaborazione'];
                        $fields['id']=$this->generate_seriale('aperture', 'id');
                        $fields['recordiddipendenti_']=$saved_recordid;
                        $fields['dataapertura']=$datainiziocollaborazione;
                        $this->insert_record('aperture',1, $fields);
                    }
                    
                }
                
                if($tableid=='dipendentiinterni')
                {
                    $dipendenteinterno=  $this->db_get_row('user_dipendentiinterni','*',"recordid_='$saved_recordid'");
                    $recordid_dipendente=$dipendenteinterno['recordiddipendenti_'];
                    $this->add_custom_update_php('aggiorna_presenze_dipendente', $recordid_dipendente);
                    //$this->add_custom_update('dipendenteinterno',$saved_recordid);
                }
                
                if($tableid=='notifiche')
                {
                    if($funzione=='inserimento')
                    {
                        $notifica=  $this->db_get_row('user_notifiche','*',"recordid_='$saved_recordid'");
                        $recordid_dipendente=$notifica['recordiddipendenti_'];
                        $this->add_custom_update_php('aggiorna_presenze_dipendente', $recordid_dipendente);
                        $this->add_custom_update('presenzenotifiche',$saved_recordid);
                    }
                }
                
                if($tableid=='ccl')
                {
                    $today=date('Y-m-d');
                    $stato= $this->db_get_value("user_$tableid",'stato',"recordid_='$saved_recordid'");
                    $versione=$this->db_get_value("user_$tableid",'versione',"recordid_='$saved_recordid'");
                    $dataversione=$this->db_get_value("user_$tableid",'dataversione',"recordid_='$saved_recordid'");
                    $datainizio= $this->db_get_value("user_$tableid",'datainizio',"recordid_='$saved_recordid'");
                    if(isempty($datainizio))
                    {
                        $datainizio='null';
                    }
                    else
                    {
                        $datainizio="'$datainizio'";
                    }
                   
                    $datafine= $this->db_get_value("user_$tableid",'datafine',"recordid_='$saved_recordid'");
                    if(isempty($datafine))
                    {
                        $datafine='null';
                    }
                    else
                    {
                        $datafine="'$datafine'";
                    }
                    
                    if(isempty($stato))
                    {
                        $sql="UPDATE user_$tableid SET stato='Verificato',versione=1,dataversione='$today' WHERE recordid_='$saved_recordid'";
                        $this->execute_query($sql);
                    }
                    $sql="
                        UPDATE user_fasciaeta
                        SET datainizio=$datainizio, datafine=$datafine, stato='$stato', versione=$versione, dataversione='$dataversione'
                        WHERE recordidccl_='$saved_recordid'
                        ";
                    $this->execute_query($sql);
                    $sql="
                        UPDATE user_qualifiche
                        SET datainizio=$datainizio, datafine=$datafine, stato='$stato', versione=$versione, dataversione='$dataversione'
                        WHERE recordidccl_='$saved_recordid'
                        ";
                    $this->execute_query($sql);
                    $sql="
                        UPDATE user_professioni
                        SET datainizio=$datainizio, datafine=$datafine, stato='$stato', versione=$versione, dataversione='$dataversione'
                        WHERE recordidccl_='$saved_recordid'
                        ";
                    $this->execute_query($sql);
                }
                
                if(($tableid=='fasciaeta')||($tableid=='qualifiche')||($tableid=='professioni'))
                {
                    $recordid_ccl= $this->db_get_value("user_$tableid",'recordidccl_',"recordid_='$saved_recordid'");
                    $ccl=$this->db_get_row('user_ccl','*',"recordid_='$recordid_ccl'");
                    $stato=$ccl['stato'];
                    $versione=$ccl['versione'];
                    $dataversione=$ccl['dataversione'];
                    $datainizio=$ccl['datainizio'];
                    if(isempty($datainizio))
                    {
                        $datainizio='null';
                    }
                    else
                    {
                        $datainizio="'$datainizio'";
                    }
                    $datafine=$ccl['datafine'];
                    if(isempty($datafine))
                    {
                        $datafine='null';
                    }
                    else
                    {
                        $datafine="'$datafine'";
                    }
                    $sql="UPDATE user_$tableid SET stato='$stato',versione=$versione,dataversione='$dataversione',datainizio=$datainizio,datafine=$datafine WHERE recordid_='$saved_recordid'";
                    $this->execute_query($sql);
                }
                
                if($tableid=='qualifiche')
                {
                    $saved_record=  $this->db_get_row('user_qualifiche','*',"recordid_='$saved_recordid'");
                    $saved_descrizione=$saved_record['descrizione'];
                    $saved_qualifica=$saved_record['qualifica'];
                    $saved_ccl_recordid=$saved_record['recordidccl_'];
                    if(isnotempty($saved_ccl_recordid))
                    {
                        if(isempty($saved_descrizione))
                        {
                            $record_qualifica_corrispondente=$this->db_get_row('user_qualifiche','*',"recordidccl_='$saved_ccl_recordid' AND qualifica='$saved_qualifica' AND (descrizione is not null OR descrizione<>'') ORDER BY ID DESC");
                            if($record_qualifica_corrispondente!=null)
                            {
                                $descrizione=$record_qualifica_corrispondente['descrizione'];
                            }
                        }
                        else
                        {
                            $descrizione=$saved_descrizione;
                        }
                        $descrizione=  str_replace("'", "''", $descrizione);
                        $sql="UPDATE user_qualifiche SET descrizione='$descrizione' WHERE recordidccl_='$saved_ccl_recordid' AND qualifica='$saved_qualifica'";
                        $this->execute_query($sql);
                    }
                }
                
                if($tableid=='aperture')
                {
                    $saved_record=  $this->db_get_row('user_aperture','*',"recordid_='$saved_recordid'");
                    $updatefields['situazione']=$saved_record['situazione'];
                    $updatefields['attivo']=$saved_record['attivo'];
                    $recordiddipendente=$saved_record['recordiddipendenti_'];
                    $this->update_record('dipendenti', $userid, $updatefields, "recordid_='$recordiddipendente'");
                }
                
                if($tableid=='richiestericercapersonale')
                {
                    if($funzione=='inserimento')
                    {
                        $updatefields['statoprocesso']='nuovo';
                        $this->update_record('richiestericercapersonale', $userid, $updatefields, "recordid_='$saved_recordid'");
                    }
                    if($funzione=='modifica')
                    {
                        $updatefields['statoprocesso']='modificato';
                        $this->update_record('richiestericercapersonale', $userid, $updatefields, "recordid_='$saved_recordid'");
                        if($saved_record['stato']!=$prev_record['stato'])
                        {
                            $return="custom_3p_richiestericercapersonale_statomodificato-$saved_recordid";
                        }
                    }
                    
                    
                }
                
            }
        // CUSTOM 3P FINE
            
            
        //CUSTOM SWISSBIX INIZIO
        if($cliente_id=='swissbix')
        {
            $this->custom_update_record($tableid,$saved_recordid);
            
        }
        //CUSTOM SWISSBIX FINE
        
        return $return;
        
    }
    
    function custom_update_record($tableid,$saved_recordid)
    {
        if($tableid=='salesorderline')
        {
            $saved_record=  $this->db_get_row('user_salesorderline','*',"recordid_='$saved_recordid'");
            $quantity=$saved_record['quantity'];
            $unitcost=$saved_record['unitcost'];
            $price=$saved_record['price'];
            $fields['cost']=$unitcost*$quantity;
            if(($unitcost!=null)&&($unitcost!=''))
            {
                $fields['margin']=$price-$fields['cost'];
                $recordid_salesorder=$saved_record['recordidsalesorder_'];
                $salesorder=$this->db_get_row('user_salesorder','*',"recordid_='$recordid_salesorder'");
                if($this->isnotempty($salesorder))
                {
                    $repetitiontype=$salesorder['repetitiontype'];
                    $multiplier=1;
                    if($repetitiontype=='Quarterly')
                    {
                        $multiplier=4;
                    }
                    if($repetitiontype=='Monthly')
                    {
                        $multiplier=12;
                    }
                    $fields['marginyearly']=$fields['margin']*$multiplier;
                }
            }
            else
            {
                $fields['margin']=null;
            }
            $this->update_record('salesorderline', 1, $fields, "recordid_='$saved_recordid'");
            $this->custom_update_record("salesorder",$recordid_salesorder);
        }
            
        if($tableid=='salesorder')
        {
            $saved_record=  $this->db_get_row('user_salesorder','*',"recordid_='$saved_recordid'");
            $totalprice=$saved_record['totalnet'];
            $repetitiontype=$saved_record['repetitiontype'];
            $multiplier=1;
            if($repetitiontype=='Quarterly')
            {
                $multiplier=4;
            }
            if($repetitiontype=='Monthly')
            {
                $multiplier=12;
            }
            $salesorderlines= $this->db_get('user_salesorderline', "*", "recordidsalesorder_='$saved_recordid'");
            $totalcost=0;
            foreach ($salesorderlines as $key => $salesorderline) {
                $totalcost=$totalcost+$salesorderline['cost'];
            }
            $fields['totalcost']=$totalcost;
            $fields['totalmargin']=$totalprice-$totalcost;
            $fields['totalmarginyearly']=$fields['totalmargin']*$multiplier;
            $this->update_record('salesorder', 1, $fields, "recordid_='$saved_recordid'");
        }
        
    }
    
    //CUSTOM SWISSBIX INIZIO
    function calcola_pianificazione($recordid_ordine)
    {
        $sql="DELETE FROM user_pianificazioniordine WHERE recordidordini_='$recordid_ordine'";
        $this->execute_query($sql);
                
        $record_ordine= $this->db_get_row('user_ordini','*',"recordid_='$recordid_ordine'");
        $fields['descrizione']=$record_ordine['titolo'];
        $fields['prezzo']=$record_ordine['prezzo'];
        $fields['costo']=$record_ordine['costo'];
        $fields['recordidordini_']=$recordid_ordine;
        
        $datainizio=$record_ordine['datainizio'];
        $data=$datainizio;
        $ripetizione=$record_ordine['ripetizione'];
        if($ripetizione=='Mensile')
        {
            for($x=0;$x<24;$x++)
            {
                $multi=$x;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->generate_seriale('pianificazioniordine', 'id');
                $this->insert_record('pianificazioniordine',1, $fields);
            }
        }
        if($ripetizione=='Trimestrale')
        {
            for($x=0;$x<8;$x++)
            {
                $multi=$x*3;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->generate_seriale('pianificazioniordine', 'id');
                $this->insert_record('pianificazioniordine',1, $fields);
            }
        }
        if($ripetizione=='Annuale')
        {
            for($x=0;$x<2;$x++)
            {
                $multi=$x*12;
                $data=date('Y-m-d', strtotime($datainizio. ' + '.$multi.' month'));
                $fields['data']=$data;
                $fields['id']= $this->generate_seriale('pianificazioniordine', 'id');
                $this->insert_record('pianificazioniordine',1, $fields);
            }
        }
    }
    //CUSTOM SWISSBIX FINE
    
    
    function custom_3p_salva_contratto($saved_recordid)
    {
        $return='';
        $recordid_contratto=$saved_recordid;
        $contratto=  $this->db_get_row('user_contratti','*',"recordid_='$saved_recordid'");
        $datainizio=$contratto['datainizio'];
        $datainizio_sovrapposizione=$datainizio;
        $datafine=$contratto['datafine'];
        $datafine_sovrapposizione=$datafine;
        $disdetta=$contratto['disdetta'];
        $datadisdetta=$contratto['datadisdetta'];
        $tipocontratto=$contratto['tipocontratto'];
        $visto=$contratto['visto'];
        $tipoorario=$contratto['tipoorario'];
        $ore=$contratto['ore'];
        $recordid_azienda=$contratto['recordidazienda_'];
        $recordid_dipendente=$contratto['recordiddipendenti_'];
        $modificacontrattuale=$contratto['modificacontrattuale'];
        $giornifestivi=$contratto['giornifestivi'];
        if(isempty($giornifestivi))
        {
           $sql="
                UPDATE user_contratti
                SET giornifestivi='Giorni effettivi'
                WHERE recordid_='$saved_recordid'
                ";
            $this->execute_query($sql); 
        }
        if($tipocontratto=='indet')
        {
            $datafine=null;
            $sql="
                UPDATE user_contratti
                SET datafine=null
                WHERE recordid_='$saved_recordid'
                ";
            $this->execute_query($sql);
        }
        if(isnotempty($datafine))
        {
            $date_condition="data >= '$datainizio' AND data <= '$datafine'";
        }
        else
        {
            $date_condition="data >= '$datainizio'";
            $datafine_sovrapposizione='2100-01-01';
        }
        if(isnotempty($datadisdetta))
        {
           $date_condition="data >= '$datainizio' AND data <= '$datadisdetta'"; 
           $datafine_sovrapposizione=$datadisdetta;

        }
        if($disdetta=='si')
        {
            $sql="UPDATE user_dipendenti SET 3mesi='x' WHERE recordid_='$recordid_dipendente'";
            $this->execute_query($sql);


        }



        //in caso di modifica contrattuale aggiorno la data fine del precedente contratto
        if($this->isnotempty($modificacontrattuale))
        {
            $datafine_modificacontrattuale=date("Y-m-d", strtotime($datainizio." -1 days"));
            $sql="UPDATE user_contratti SET datafine='$datafine_modificacontrattuale' WHERE id='$modificacontrattuale'";
            $this->execute_query($sql);
        }
        
        $alias=$contratto['alias'];
        if(isempty($alias))
        {
            $alias_condition="(alias is null OR alias='')";
        }
        else
        {
            $alias_condition="alias='$alias'";
        }

        
        //controllo sovrapposizione con altri contratti
        $sql="
            SELECT recordid_ ,id
            FROM
            (
             SELECT *,datainizio as 'datainizioeffettiva',if((tipocontratto='indet' AND datafine is null),if(datadisdetta is null,'2100-01-01',datadisdetta),if(datadisdetta<datafine,datadisdetta,datafine) ) as 'datafineeffettiva'  FROM user_contratti WHERE recordiddipendenti_='$recordid_dipendente' 
                ) as supporto
            where   
            recordiddipendenti_='$recordid_dipendente' 
            AND $alias_condition
            AND
            (
            ((datainizioeffettiva<='$datainizio_sovrapposizione') AND (datafineeffettiva>='$datafine_sovrapposizione') )
            OR
            ((datainizioeffettiva<='$datafine_sovrapposizione') AND (datafineeffettiva>='$datafine_sovrapposizione') )
            OR
            ((datainizioeffettiva<='$datainizio_sovrapposizione') AND (datafineeffettiva>='$datainizio_sovrapposizione') )
            OR
            ((datainizioeffettiva>='$datainizio_sovrapposizione') AND (datafineeffettiva<='$datafine_sovrapposizione') )
            )

            ";
        $result=$this->select($sql);
        if(count($result)>1)
        {
            $return='custom_3p_sovrapposizione_contratto-';
            foreach ($result as $key => $row) {
                $return=$return.$row['id'].".";
            }

        }
        else
        {
            if($datafine==null)
            {
                $datafine="null";
            }
            else
            {
                $datafine="'$datafine'";
            }
            //verifico esistano già i rapporti di lavoro per questo dipendente
            //$rapportodilavoro=$this->db_get_row('user_rapportidilavoro','recordid_',"recordiddipendenti_='$recordid_dipendente' AND $alias_condition");
            //se non esiste almeno una riga di rapporto di lavoro per quel dipendente con quell'alias allora ne genero i rapporti di lavoro
            //if(isempty($rapportodilavoro))
            //{
                //genero i rapporti di lavoro per l'alias
                $this->genera_presenze_dipendente($recordid_dipendente);
                $this->genera_rapportidilavoro($recordid_dipendente, $alias, $saved_recordid);
            //}
            // resetto i rapporti di lavoro collegati a questo contratto
            $sql="
                UPDATE user_rapportidilavoro
                SET tipocontratto=null,visto=null,tipoorario=null,ore=null,datainizio=null,datafine=null,recordidcontratti_=null,recordidazienda_=null
                WHERE recordidcontratti_='$saved_recordid'
                ";
            $this->execute_query($sql);

            // aggiorno i rapporti di lavoro da collegare a questo contratto
            $sql="
                UPDATE user_rapportidilavoro
                SET tipocontratto='$tipocontratto',visto='$visto',tipoorario='$tipoorario',ore='$ore',datainizio='$datainizio',datafine=$datafine,recordidcontratti_='$saved_recordid',recordidazienda_='$recordid_azienda'
                WHERE recordiddipendenti_='$recordid_dipendente' AND $date_condition AND $alias_condition
                ";
            $this->execute_query($sql); 
        } 


        if($return=='')
        {
            
            $contratto=  $this->db_get_row('user_contratti','*',"recordid_='$saved_recordid'");
            $recordid_dipendente=$contratto['recordiddipendenti_'];
            //$this->genera_presenze_dipendente($recordid_dipendente);
            $anno_inizio=date('Y', strtotime($datainizio));
            $mese_inizio=date('n', strtotime($datainizio));
            $presenze_mensili= $this->db_get("user_presenzemensili", "*", "anno>=$anno_inizio AND mese>=$mese_inizio AND recordiddipendenti_='$recordid_dipendente'");
            foreach ($presenze_mensili as $key => $presenza_mensile) {
                $recordid_presenzemensili=$presenza_mensile['recordid_'];
                $this->add_custom_update_php('aggiorna_presenze_completo', $recordid_presenzemensili);
            }
            //$this->add_custom_update('contratto',$saved_recordid);

            $this->custom_3p_genera_contratto_fornitura($saved_recordid);
            $return=$saved_recordid;
        }
        else
        {
            //$this->elimina_record('contratti', $saved_recordid); 
        }
        
        $this->sync_linkedmaster_contratto($saved_recordid);
        $this->aggiorna_disponibilita_dipendente($recordid_dipendente);
        $this->aggiorna_mediacontrattuale_contratti($saved_recordid);
        $this->aggiorna_orecontrattuali_rapportidilavoro($saved_recordid);
        $this->calcola_media_contratto_v2021($saved_recordid);
        
        
        return $return;
        //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('contratto','$saved_recordid','todo')";
        //$this->execute_query($sql);
        
    }
    
    public function custom_3p_aggiorna_rapportidilavoro_da_contratto($recordid_contratto)
    {
        $contratto=  $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");
        $datainizio=$contratto['datainizio'];
        $datainizio_sovrapposizione=$datainizio;
        $datafine=$contratto['datafine'];
        $datafine_sovrapposizione=$datafine;
        $disdetta=$contratto['disdetta'];
        $datadisdetta=$contratto['datadisdetta'];
        $tipocontratto=$contratto['tipocontratto'];
        $visto=$contratto['visto'];
        $tipoorario=$contratto['tipoorario'];
        $ore=$contratto['ore'];
        $recordid_azienda=$contratto['recordidazienda_'];
        $recordid_dipendente=$contratto['recordiddipendenti_'];
        $alias=$contratto['alias'];
        
        if(isnotempty($datafine))
        {
            $date_condition="data >= '$datainizio' AND data <= '$datafine'";
        }
        else
        {
            $date_condition="data >= '$datainizio'";
            $datafine_sovrapposizione='2100-01-01';
        }
        if(isnotempty($datadisdetta))
        {
           $date_condition="data >= '$datainizio' AND data <= '$datadisdetta'"; 
           $datafine_sovrapposizione=$datadisdetta;

        }
        if($tipocontratto=='indet')
        {
            $datafine=null;
        }
        if($datafine==null)
        {
            $datafine="null";
        }
        else
        {
            $datafine="'$datafine'";
        }
        
        if(isempty($alias))
        {
            $alias_condition="(alias is null OR alias='')";
        }
        else
        {
            $alias_condition="alias='$alias'";
        }
        
        
        // resetto i rapporti di lavoro collegati a questo contratto
            $sql="
                UPDATE user_rapportidilavoro
                SET tipocontratto=null,visto=null,tipoorario=null,ore=null,datainizio=null,datafine=null,recordidcontratti_=null,recordidazienda_=null
                WHERE recordidcontratti_='$recordid_contratto'
                ";
            $this->execute_query($sql);

            // aggiorno i rapporti di lavoro da collegare a questo contratto
            $sql="
                UPDATE user_rapportidilavoro
                SET tipocontratto='$tipocontratto',visto='$visto',tipoorario='$tipoorario',ore='$ore',datainizio='$datainizio',datafine=$datafine,recordidcontratti_='$recordid_contratto',recordidazienda_='$recordid_azienda'
                WHERE recordiddipendenti_='$recordid_dipendente' AND $date_condition AND $alias_condition
                ";
            $this->execute_query($sql); 
            
    }
    
    public function custom_3p_genera_tutti_contratti_fornitura()
    {
        $counter=0;
        $sql="SELECT recordid_ FROM user_contratti";
        $contratti= $this->select($sql);
        foreach ($contratti as $key => $contratto) {
            $recordid_contratto=$contratto['recordid_'];
            $this->custom_3p_genera_contratto_fornitura($recordid_contratto);
            $counter++;
        }
        echo $counter." contratti di fornitura generati";
    }
    
    public function custom_3p_genera_contratto_fornitura($recordid_contratto='')
    { 
        $contratto_fornitura= $this->db_get_row('user_contrattofornitura','*',"recordidcontratti_='$recordid_contratto'");
        $tariffaoraria='';
        if($contratto_fornitura!=null)
        {
           $recordid_contrattofornitura= $contratto_fornitura['recordid_'];
           $tariffaoraria=$contratto_fornitura['tariffaoraria']; 
           $sql="DELETE FROM user_contrattofornitura WHERE recordid_='$recordid_contrattofornitura'";
           $this->execute_query($sql);
        }
        else
        {
            $fatturazione='';
        }
        
        $contratto= $this->get_record('contratti', $recordid_contratto);
        $data['contratto']=$contratto;
        $recordid_dipendente=$contratto['recordiddipendenti_'];
        $dipendente= $this->get_record('dipendenti',$recordid_dipendente);
        $recordid_azienda=$contratto['recordidazienda_'];
        $azienda= $this->get_record('azienda',$recordid_azienda);
        $recordid_professione=$contratto['recordidprofessioni_'];
        $professione= $this->get_record('professioni',$recordid_professione);
        if($professione!=null)
        {
            
            $fields['mansione']=$professione['professione'];
        }

        $fields['cclcontratto']=$azienda['ccl'];
        $fields['id']=$contratto['id'];
        $fields['nomecliente']=$azienda['ragionesociale'];
        $fields['indirizzocliente']=$azienda['indirizzo'];
        $fields['paesecliente']=$azienda['npa']." ".$azienda['localita'];
        $fields['nomedipendente']=$dipendente['nome'];
        $fields['cognomedipendente']=$dipendente['cognome'];
        $fields['indirizzodipendente']=$dipendente['indirizzo'];
        $fields['paesedipendente']=$dipendente['domicilio'];
        $fields['datanascita']=date('d/m/Y', strtotime($dipendente['datanascita']));
        $fields['luogolavoro']=$contratto['luogomissione'];
        if($contratto['tipoorario']=='giorno')
        {
            $fields['orariomedio']=$contratto['ore']." ore al giorno";
        }
        if($contratto['tipoorario']=='settimana')
        {
            $fields['orariomedio']=$contratto['ore']." ore alla settimana";
        }
        if($contratto['tipoorario']=='calendario')
        {
            $fields['orariomedio']="Come da calendario aziendale allegato";
        }
        if($contratto['tipoorario']=='suchiamata')
        {
            $fields['orariomedio']="Su chiamata";
        }
        
        
        $fields['orario']=$contratto['fasciaoraria'];
        if(isnotempty($contratto['datainizio']))
        {
            $fields['iniziocontratto']=date('d/m/Y', strtotime($contratto['datainizio']));
        }
        else
        {
            $fields['iniziocontratto']='';
        }
        
        if($contratto['tipocontratto']=='indet')
        {
            $fields['finecontratto']='Indeterminato';
        }  
        else
        {
            if(isnotempty($contratto['datafine']))
            {
                $fields['finecontratto']=date('d/m/Y', strtotime($contratto['datafine']));
            }
            else
            {
                $fields['finecontratto']='';
            }
        }    
        
        
        $fields['tariffaoraria']=$tariffaoraria;
        $fields['fatturazione']=$azienda['fatturazione'];
        /*
        if(isnotempty($dipendente['datacontrattoquadroclc']))
        {
            $fields['datacontrattoquadro']=date('d/m/Y', strtotime($azienda['datacontrattoquadro']));
        }
        else
        {
            $fields['datacontrattoquadro']='';
        }
         * 
         */
        
        if(isnotempty($azienda['datacontrattoquadro']))
        {
            $fields['datacontrattoquadro']=date('Y-m-d', strtotime($azienda['datacontrattoquadro']));
        }
        else
        {
            $fields['datacontrattoquadro']='';
        }
        
        if(isnotempty($azienda['datacontrattoquadro']))
        {
            $fields['datacontrattoquadroazienda']=date('d/m/Y', strtotime($azienda['datacontrattoquadro']));
        }
        else
        {
            $fields['datacontrattoquadroazienda']='';
        }
        
        if(isnotempty($contratto['datafirma']))
        {
            $fields['datamandato']=date('d/m/Y', strtotime($contratto['datafirma']));
        }
        else
        {
            $fields['datamandato']='';
        }
        
        
        
        $fasciaoraria='';
        if(isnotempty($contratto['fasciaoraria11'])||isnotempty($contratto['fasciaoraria12'])||isnotempty($contratto['fasciaoraria13'])||isnotempty($contratto['fasciaoraria14']))
        {
            $fasciaoraria=$fasciaoraria.substr($contratto['fasciaoraria11'],0,-3)."/".substr($contratto['fasciaoraria12'],0,-3)."/".substr($contratto['fasciaoraria13'],0,-3)."/".substr($contratto['fasciaoraria14'],0,-3)." <br/><br/>";
        }
        if(isnotempty($contratto['fasciaoraria21'])||isnotempty($contratto['fasciaoraria22'])||isnotempty($contratto['fasciaoraria23'])||isnotempty($contratto['fasciaoraria24']))
        {
            $fasciaoraria=$fasciaoraria.substr($contratto['fasciaoraria21'],0,-3)."/".substr($contratto['fasciaoraria22'],0,-3)."/".substr($contratto['fasciaoraria23'],0,-3)."/".substr($contratto['fasciaoraria24'],0,-3)." <br/><br/>";
        }
        if(isnotempty($contratto['fasciaoraria31'])||isnotempty($contratto['fasciaoraria32'])||isnotempty($contratto['fasciaoraria33'])||isnotempty($contratto['fasciaoraria34']))
        {
            $fasciaoraria=$fasciaoraria.substr($contratto['fasciaoraria31'],0,-3)."/".substr($contratto['fasciaoraria32'],0,-3)."/".substr($contratto['fasciaoraria33'],0,-3)."/".substr($contratto['fasciaoraria34'],0,-3)." <br/><br/>";
        }
        if(isnotempty($contratto['fasciaoraria41'])||isnotempty($contratto['fasciaoraria42'])||isnotempty($contratto['fasciaoraria43'])||isnotempty($contratto['fasciaoraria44']))
        {
            $fasciaoraria=$fasciaoraria.substr($contratto['fasciaoraria41'],0,-3)."/".substr($contratto['fasciaoraria42'],0,-3)."/".substr($contratto['fasciaoraria43'],0,-3)."/".substr($contratto['fasciaoraria44'],0,-3)." <br/><br/>";
        }
        $fields['fasciaoraria']=$fasciaoraria;
        $fields['recordidazienda_']=$contratto['recordidazienda_'];
        $fields['recordiddipendenti_']=$contratto['recordiddipendenti_'];
        $fields['recordidprofessioni_']=$contratto['recordidprofessioni_'];
        $fields['recordidcontratti_']=$contratto['recordid_'];
        
        
        $this->insert_record('contrattofornitura',1, $fields);
        
    }
    
    function salva_modifiche_record($master_tableid,$master_recordid,$post)
    {
        $settings=  $this->get_settings();
        $table_recordid=$master_recordid;
        $cliente_id=$settings['cliente_id'];
        $userid=$settings['userid'];
        $now = date('Y-m-d H:i:s');
        $funzione='modifica';
        if($master_recordid=='null')
        {
            $funzione='inserimento';
            $master_recordid=  $this->generate_recordid($master_tableid);
        }
        if(array_key_exists('tables', $post))
        {
            $this->set_logquery('json modifiche record',  json_encode($post['tables']));
            foreach ($post['tables'] as $tableid => $table_container) 
                {
                    $tablename='user_'.strtolower($tableid);
                   // $table_recordid=$table_container['recordid'];
                    if (array_key_exists('insert', $table_container)) {
                        foreach ($table_container['insert'] as $table) {
                            if (key_exists('fields', $table)) {
                                $new_recordid = "";
                                $totpages = 0;
                                $userid = $this->session->userdata('userid');
                                if ($tableid == $master_tableid) {
                                    $insert = "INSERT INTO $tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_";
                                    if ($master_recordid != 'null') {
                                        $new_recordid = $master_recordid;
                                    } else {
                                        $new_recordid = $this->generate_recordid($tableid);
                                    }
                                    
                                        $values = " VALUES ('$new_recordid',$userid,'$now',$userid,'$now',$totpages,'N'";
                                    //CUSTOM Work&Work

                                    if ($tableid == "CANDID") {
                                        //CUSTOM POSTGRES
                                        //data registrazione
                                        $data_reg = date("Y-m-d 00:00:00");
                                        $insert = $insert . ",datareg";
                                        $values = $values . ",'$data_reg'";

                                        //CUSTOM POSTGRES
                                        //data scadenza
                                        $data_scadenza = date("Y-m-d", strtotime($data_reg . " +6 month"));
                                        $insert = $insert . ",scadenza";
                                        $values = $values . ",'$data_scadenza'";

                                        //id(NON recordid_) del nuovo candidato
                                        $id = $this->generate_id($tableid);
                                        $insert = $insert . ",id";
                                        $values = $values . ",$id";

                                        //consulente
                                        if(!array_key_exists('consulente',$table['fields']))
                                        {
                                            $result=$this->set_consulente($insert, $values);
                                            $insert=$result['insert'];
                                            $values=$result['values'];
                                        }
                                    }
                                    if ($tableid == "AZIEND") {
                                        //CUSTOM POSTGRES
                                        //data registrazione
                                        $data_reg = date("Y-m-d 00:00:00");
                                        $insert = $insert . ",dataregistrazione";
                                        $values = $values . ",'$data_reg'";

                                        //CUSTOM POSTGRES
                                        //data scadenza
                                        $data_scadenza = date("Y-m-d", strtotime($data_reg . " +6 month"));
                                        $insert = $insert . ",scadenza";
                                        $values = $values . ",'$data_scadenza'";

                                        //id azienda(non recordid_)
                                        $id = $this->generate_id($tableid);
                                        $insert = $insert . ",id";
                                        $values = $values . ",$id";

                                        //consulente

                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];


                                        //attiva
                                        //azienda stato
                                        //stato ultimo contatto
                                        //ultimo contatto
                                    }
                                    if ($tableid == "CONTRA") {

                                        //id(NON recordid_) del nuovo candidato
                                        $riferimen = $this->generate_riferimen($tableid);
                                        $insert = $insert . ",riferimen";
                                        $values = $values . ",$riferimen";
                                        if(array_key_exists('custom', $post))
                                        {
                                        if(array_key_exists('destinatario_contratto_attuale', $post['custom']['contra']))
                                        {
                                            if($post['custom']['contra']['destinatario_contratto_attuale']=='azienda')
                                            {
                                                $insert = $insert . ",destinat,destinat_";
                                                $values = $values . ",'A','Azienda'";
                                            }
                                            if($post['custom']['contra']['destinatario_contratto_attuale']=='dipendente')
                                            {
                                               $insert = $insert . ",destinat,destinat_";
                                               $values = $values . ",'D','Dipendente'";
                                            }
                                        }
                                        }
                                        if(array_key_exists('linkedmaster', $table_container['insert']))
                                        {
                                            if(array_key_exists('AZIEND', $table_container['insert']['linkedmaster']))
                                            {
                                                $aziend_recordid=$table_container['insert']['linkedmaster']['AZIEND']['value'];
                                                $fields_aziend=  $this->get_fields_record('AZIEND', $aziend_recordid);
                                                $insert = $insert . ",idazienda,ragsoc";
                                                $ragsoc=  str_replace("'", "''", $fields_aziend['ragsoc']);
                                                $values = $values . ",".$fields_aziend['id'].",'".$ragsoc."'";
                                            }
                                            if(array_key_exists('CANDID', $table_container['insert']['linkedmaster']))
                                            {
                                                $candid_recordid=$table_container['insert']['linkedmaster']['CANDID']['value'];
                                                $fields_candid=  $this->get_fields_record('CANDID', $candid_recordid);
                                                $insert = $insert . ",idcandid,cognome,nome";
                                                $cognome=  str_replace("'", "''", $fields_candid['cognome']);
                                                $nome=  str_replace("'", "''", $fields_candid['nome']);
                                                $values = $values . ",".$fields_candid['id'].",'".$cognome."','".$nome."'";
                                            }
                                        }


                                    }

                                } 
                                else 
                                {
                                    $insert = "INSERT INTO $tablename (recordid_,recordid" . $master_tableid . "_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_";
                                    $new_recordid = $this->generate_recordid($tableid);
                                    $values = " VALUES ('$new_recordid','$master_recordid',$userid,'$now',$userid,'$now',$totpages,'N'";
                                    //inserimento automatico delle linkedmaster a cascata
                                    //TODO aggiungere un controllo per verificare che la linkedmaster che si inserirebbe automaticamente non sia già stata impostata forzata dall'utente.nel caso si tiene quella dell'utente
                                    $sql="select t1.tableid 
                                        from (select tableid from sys_table_link where tablelinkid='$tableid') as t1 join (select tableid from sys_table_link where tablelinkid='$master_tableid') as t2 on t1.tableid=t2.tableid";
                                    $result=  $this->select($sql);
                                    foreach ($result as $key => $value) 
                                    {
                                        $linkedmaster_tableid=$value['tableid'];
                                        $linkedmaster_column="recordid".$linkedmaster_tableid."_";
                                        $sql="SELECT $linkedmaster_column  FROM user_".strtolower($master_tableid)." WHERE recordid_='$master_recordid'";
                                        $rows=  $this->select($sql);
                                        if(count($rows)>0)
                                        {
                                            if($this->isnotempty($rows[0][$linkedmaster_column]))
                                            {
                                                $linkedmaster_recordid=$rows[0][$linkedmaster_column];
                                                $insert=$insert.",$linkedmaster_column";
                                                $values=$values.",'$linkedmaster_recordid'";
                                            }
                                        }
                                    }
                                    if(strtoupper($tableid)=="AZCONT"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                    if(strtoupper($tableid)=="AZMISSIONI"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                    if(strtoupper($tableid)=="CANDCONTATTI"){
                                        $result=$this->set_consulente($insert, $values);
                                        $insert=$result['insert'];
                                        $values=$result['values'];
                                    }
                                }

                                $sql_finale = '';
                                //CUSTOM UNILUDES INIZIO
                                $new_numeroprotocollo='';
                                $annocorrente=date('Y');
                                
                                if(array_key_exists('dataprotocollo', $table['fields']))
                                {
                                    $dataprotocollo=$table['fields']['dataprotocollo']['f_0']['value'][0];
                                    $annoprotocollo=date('Y', strtotime($dataprotocollo));
                                }
                                else
                                {
                                    $annoprotocollo=$annocorrente;
                                }

                                //CUSTOM UNILUDES FINE
                                foreach ($table['fields'] as $fieldid => $fields_container) {
                                    $value="";
                                    foreach ($fields_container as $key => $field) 
                                    {
                                        $value_temp = $field['value'][0];
                                        $value_temp = str_replace("'", "''", $value_temp);
                                        if($key=='f_0')
                                        {
                                            $value=$value.$value_temp;
                                        }
                                        else
                                        {
                                            $value=$value."|;|".$value_temp;
                                        }
                                        $type = $field['type'];
                                    }
                                    if ($value != 'loading')
                                    {
                                        //$value = str_replace("'", "''", $value);
                                        $insert = $insert . ",$fieldid";
                                        if($this->isnotempty($value))
                                        {
                                            if ($type == 'parola-testolibero') 
                                            {
                                                $values = $values . ",'$value'";
                                            }
                                            if ($type == 'parola-lookup') 
                                            {
                                                $values = $values . ",'$value'";
                                                //inserisco campo di descrizione
                                                /*if(($cliente_id=='Work&Work')||(($cliente_id=='VIS')&&($tableid=='archiviodocumenti')))
                                                {
                                                    $descrizione = $this->get_descrizione_lookup($tableid, $fieldid, $value);
                                                    $descrizione = str_replace("'", "''", $descrizione);
                                                    if ($descrizione != null) {
                                                        $insert = $insert . ",$fieldid" . "_";
                                                        $values = $values . ",'$descrizione'";
                                                    }
                                                }*/
                                            }
                                            if ($type == 'numero') {
                                                $values = $values . " ,$value";
                                            }
                                            if ($type == 'data') {
                                                $date=date('Y-m-d', strtotime($value));
                                                $values = $values . " ,'$date'";
                                            }
                                            if ($type == 'ora') {
                                                $time=date('H:i', strtotime($value));
                                                $values = $values . " ,'$time'";
                                            }
                                            if ($type == 'memo') {
                                                $values = $values . " ,'$value'";
                                            }
                                            if ($type == 'utente') {
                                                $values = $values . " ,'$value'";
                                            }
                                            
                                        }
                                        else
                                        {
                                            $value='null';
                                            if ($type == 'seriale') {
                                                $value=  $this->generate_seriale($tableid, $fieldid);
                                            }
                                            
                                            //CUSTOM UNILUDES INIZIO
                                            if(($tableid=='protocolloentrata')||($tableid=='protocollouscita'))
                                            {
                                                if($fieldid=='annonprot')
                                                {

                                                        //$value="'$annoprotocollo-$new_numeroprotocollo'";
                                                        $value="'$annoprotocollo-$new_numeroprotocollo'";
                                                }
                                                if($fieldid=='annoprotocollo')
                                                {

                                                        $value="$annoprotocollo";
                                                }
                                            }
                                            
                                            if($fieldid=='numeroprotocollo')
                                            {
                                                if(($tableid=='protocolloentrata')||($tableid=='protocollouscita'))
                                                {                                  
                                                    
                                                    $tempsql="SELECT max(numeroprotocollo) as maxnum FROM user_".strtolower($tableid)." WHERE dataprotocollo <= '$annoprotocollo-12-31' AND dataprotocollo >= '$annoprotocollo-01-01'";

                                                    $tempresult=  $this->select($tempsql);
                                                    if(count($tempresult)>0)
                                                    {
                                                        $maxnum=$tempresult[0]['maxnum'];
                                                        $new_numeroprotocollo=$maxnum+1;
                                                        $value=$new_numeroprotocollo;
                                                    }
                                                    else
                                                    {
                                                        $value='null';
                                                    }
                                                }
                                                if($tableid=='documentiprotocolloentrata')
                                                {
                                                    $value='';
                                                    $temp_master_recordid=$table_container['insert']['linkedmaster']['protocolloentrata']['value'];
                                                    $tempsql="SELECT annonprot FROM user_protocolloentrata WHERE recordid_='$temp_master_recordid'";
                                                    $tempresult=  $this->select($tempsql);
                                                    if(count($tempresult)>0)
                                                    {
                                                        $annonprot=$tempresult[0]['annonprot'];
                                                        if($this->isnotempty($annonprot))
                                                        {
                                                            if( ($x_pos = strpos($annonprot, '-')) !== FALSE )
                                                                $annonprot = substr($annonprot, $x_pos + 1);
                                                            $value=$annonprot;
                                                        }
                                                    }
                                                }
                                            }
                                            //CUSTOM UNILUDES FINE
                                            
                                            //CUSTOM UNILUDES
                                           /* if($fieldid=='numeroprotocollo')
                                                {
                                                    if(($tableid=='protocolloentrata')||($tableid=='protocolloentrata2015')||($tableid=='protocollouscita')||($tableid=='protocollouscita2015'))
                                                    {
                                                        $tempsql="SELECT max(numeroprotocollo) as maxnum FROM user_".strtolower($tableid)."";
                                                        $tempresult=  $this->select($tempsql);
                                                        if(count($tempresult)>0)
                                                        {
                                                            $maxnum=$tempresult[0]['maxnum'];
                                                            $new_numeroprotocollo=$maxnum+1;
                                                            $value=$new_numeroprotocollo;

                                                        }
                                                        else
                                                        {
                                                            $value=0;
                                                        }
                                                    }
                                                    if($tableid=='documentiprotocolloentrata')
                                                    {
                                                        $temp_master_recordid=$table_container['insert']['linkedmaster']['protocolloentrata']['value'];
                                                        $tempsql="SELECT numeroprotocollo FROM user_protocolloentrata WHERE recordid_='$temp_master_recordid'";
                                                        $tempresult=  $this->select($tempsql);
                                                        if(count($tempresult)>0)
                                                        {
                                                           $value=$tempresult[0]['numeroprotocollo']; 
                                                        }
                                                    }
                                                    if($tableid=='documentiprotocolloentrata2015')
                                                    {
                                                        $temp_master_recordid=$table_container['insert']['linkedmaster']['protocolloentrata2015']['value'];
                                                        $tempsql="SELECT numeroprotocollo FROM user_protocolloentrata2015 WHERE recordid_='$temp_master_recordid'";
                                                        $tempresult=  $this->select($tempsql);
                                                        if(count($tempresult)>0)
                                                        {
                                                           $value=$tempresult[0]['numeroprotocollo']; 
                                                        }
                                                    }
                                                }
                                            if($fieldid=='annonprot')
                                                {
                                                    if(($tableid=='protocolloentrata')||($tableid=='protocollouscita'))
                                                    {
                                                        $value="'2014-$new_numeroprotocollo'";
                                                        $value="'2014-$new_numeroprotocollo'";
                                                    }
                                                    if(($tableid=='protocolloentrata2015')||($tableid=='protocollouscita2015'))
                                                    {
                                                        $value="'2015-$new_numeroprotocollo'";
                                                        $value="'2015-$new_numeroprotocollo'";
                                                    }
                                                }*/
                                            if ($type == 'calcolato') {
                                                $value="'01:30'";
                                            }
                                            
                                           $values = $values . " ,$value";     
                                           //$values = $values . " ,null"; 
                                        }
                                    }
                                    
                                }
                                $insert = $insert . ")";
                                $values = $values . ")";
                                $sql_finale = $insert . $values;
                                $this->set_logquery('inserimento', $sql_finale);
                                $this->execute_query($sql_finale);



                                //UPDATE SYS_TABLE
                                $sql_lastrecordid = "UPDATE sys_table SET lastrecordid='$new_recordid' WHERE id='$tableid'";
                                $this->set_logquery('inserimento',$sql_lastrecordid);
                                $this->execute_query($sql_lastrecordid);

                            $table_recordid=$new_recordid;
                            }
                        
                        }
                    if (array_key_exists('linkedmaster', $table_container['insert'])) {
                        $master_table = "user_" . strtolower($master_tableid);
                        foreach ($table_container['insert']['linkedmaster'] as $linkedmaster_tableid => $linkedmaster_container) {
                            $linkedmaster_recordid = $linkedmaster_container['value'];
                            $linkedmaster_tableid=  strtolower($linkedmaster_tableid);
                            $sql_linkedmaster = "UPDATE $master_table SET recordid".$linkedmaster_tableid."_='$linkedmaster_recordid' WHERE recordid_='$master_recordid' ";
                            $this->set_logquery('inserimento',$sql_linkedmaster);
                            $this->execute_query($sql_linkedmaster);     
                        }                    
                        }
                    }


                //TABELLE GIA' ESISTENTI DA MODIFICARE
                //$new_recordid="";
                if(array_key_exists('update', $table_container))
                    {
                    $table_recordid=$table_container['recordid'];
                    foreach($table_container['update'] as $key=>$table) 
                    {
                        
                        if(key_exists('fields', $table))
                        {
                            //$new_recordid="";    
                            $totpages=0;
                            $lastupdate=  date('Y-m-d H:i:s');
                            $creatorid=$this->session->userdata('userid');
                            $update="UPDATE $tablename";
                            $set="SET lastupdaterid_=$userid,lastupdate_='$now'";
                            $where="WHERE true";
                            $sql_finale='';
                            foreach ($table['fields'] as $fieldid=>$fields_container)
                            {
                                $value="";
                                foreach ($fields_container as $key => $field) 
                                {
                                    $value_temp = $field['value'][0];
                                    $value_temp = str_replace("'", "''", $value_temp);
                                    if($value_temp!='')
                                    {
                                        if($value!='')
                                        {
                                            $value=$value."|;|";
                                        }
                                        $value=$value.$value_temp;
                                    }
                                    
                                    
                                    /*if($key=='f_0')
                                    {
                                        $value=$value.$value_temp;
                                    }
                                    else
                                    {
                                        if($value_temp!='')
                                        {
                                            $value=$value."|;|".$value_temp;
                                        }
                                    }*/
                                    $type = $field['type'];
                                }
                                        //$value=$field['value'];
                                        //$value=  str_replace("'", "''", $value);
                                        //$type=$field['type'];
                                        if($value!='loading')
                                        {

                                            $set=$set.",".$fieldid."=";
                                            if($value=='')
                                            {
                                                $set=$set."null";
                                            }
                                            else
                                            {
                                                if($type=='parola-testolibero')
                                                {
                                                    $set=$set."'$value'";
                                                }

                                                if ($type == 'parola-lookup') 
                                                {
                                                    $set=$set."'$value'";
                                                    //inserisco campo di descrizione
                                                    if(($cliente_id=='Work&Work')||(($cliente_id=='VIS')&&($tableid=='archiviodocumenti')))
                                                    //if(true)
                                                    {
                                                        $descrizione = $this->get_descrizione_lookup($tableid, $fieldid, $value);
                                                        $descrizione = str_replace("'", "''", $descrizione);
                                                        /*if ($descrizione != null) {
                                                            $set=$set.",".$fieldid."_=";
                                                            $set=$set."'$descrizione'";
                                                        }*/
                                                    }
                                                }
                                                if($type=='numero')
                                                {
                                                    $set=$set."$value";
                                                }
                                                if($type=='data')
                                                { 
                                                    $date=date('Y-m-d', strtotime($value));
                                                    $set=$set."'$date'";
                                                }
                                                if($type=='ora')
                                                { 
                                                    $set=$set."'$value'";
                                                }
                                                if($type=='memo')
                                                {
                                                    $set=$set."'$value'";
                                                }
                                                if($type=='utente')
                                                { 
                                                    $set=$set."'$value'";
                                                }

                                            }
                                        }
                                    

                            }  
                        $where="WHERE recordid_='$table_recordid'";    
                        $sql_finale=$update." ".$set." ".$where;
                        $this->set_logquery('modifica',$sql_finale);
                        $this->execute_query($sql_finale); 

                    }

                }
                }
            
            }
            
        }
        if(array_key_exists('permessi_utente', $post))
        {
            $tableowner='user_'.strtolower($tableid)."_owner";
            foreach ($post['permessi_utente'] as $key => $userid) {
                $sql="
                    INSERT INTO $tableowner 
                    (recordid_,ownerid_)
                    VALUES ('$master_recordid',$userid)
                    ";
                $this->execute_query($sql);
            }
        }
        
        
        
        //PROPAGATION INIZIO
        
        if((array_key_exists('origine', $post)))
        {
            
            
            $origine_tableid=$post['origine']['tableid'];
            $origine_recordid=$post['origine']['recordid'];
            if(isnotempty($origine_tableid)&&  isnotempty($origine_recordid))
            {
                if($new_recordid!='undefined')
                {
                    $table="user_".strtolower($tableid);
                    $origine_column="recordid".strtolower($origine_tableid)."_";
                    $sql="UPDATE $table set $origine_column='$origine_recordid' where recordid_='$new_recordid'";
                    $this->execute_query($sql);
                }
                
            }
            
            if($funzione=='inserimento')
            {
                $propagations=  $this->db_get('sys_propagation','*',"tableid='$master_tableid' AND mastertableid='$origine_tableid'");
                $saved_record=  $this->db_get_row('user_'.strtolower($master_tableid),'*',"recordid_='$master_recordid'");
                foreach ($propagations as $key => $propagation) 
                {
                    $fieldid=$propagation['fieldid'];
                    $value=$saved_record[$fieldid];
                    $origine_fieldid=$propagation['masterfieldid'];
                    $origine_table="user_".strtolower($origine_tableid);
                    if(isnotempty($value))
                    {
                        $sql="
                                UPDATE $origine_table
                                SET $origine_fieldid='$value'
                                WHERE recordid_='$origine_recordid'

                            ";
                        $this->execute_query($sql);
                    }
                }
            }
            
        }
        
        
        
        
        
        
        
        
        //PROPAGATION FINE
        
        //CUSTOM ABOUT
        if($tableid=='telefonate')
        {
            $recalldate='';
           if (array_key_exists('insert', $table_container)) {
                foreach ($table_container['insert'] as $table) 
                {
                    if(array_key_exists('fields',$table))
                    {
                        foreach ($table['fields'] as $fieldid => $fields_container) {

                                    foreach ($fields_container as $key => $field) {
                                        if($field['value'][0]!='')
                                        {
                                            if($fieldid=='recalldate')
                                            {
                                                $recalldate_value=$field['value'][0];
                                                $sql="UPDATE user_telemarketing SET recalldate='$recalldate_value' WHERE recordid_='$master_recordid' ";
                                                $this->execute_query($sql);
                                                
                                            }
                                            if($fieldid=='stato_telefonata')
                                            {
                                                $stato_value=$field['value'][0];
                                                $sql="UPDATE user_telemarketing SET statotelemarketing='$stato_value' WHERE recordid_='$master_recordid' ";
                                                $this->execute_query($sql);
                                                
                                            }
                                        }
                                    }
                    }
                    }
                }
           }
           if (array_key_exists('update', $table_container)) {
                foreach ($table_container['update'] as $table) 
                {
                    if(array_key_exists('fields',$table))
                    {
                        foreach ($table['fields'] as $fieldid => $fields_container) {

                                    foreach ($fields_container as $key => $field) {
                                        if($field['value'][0]!='')
                                        {
                                            if($fieldid=='recalldate')
                                            {
                                                $recalldate_value=$field['value'][0];
                                                $sql="UPDATE user_telemarketing SET recalldate='$recalldate_value' WHERE recordid_='$master_recordid' ";
                                                $this->execute_query($sql);
                                                
                                            }
                                            if($fieldid=='stato_telefonata')
                                            {
                                                $stato_value=$field['value'][0];
                                                $sql="UPDATE user_telemarketing SET statotelemarketing='$stato_value' WHERE recordid_='$master_recordid' ";
                                                $this->execute_query($sql);
                                                
                                            }
                                        }
                                    }
                    }
                    }
                }
           }
        }
        
        if($tableid=='vendite')
        {
           $sql="SELECT * FROM user_vendite WHERE recordid_='$master_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    if($fields['stato']=='closedorder')
                    {
                        $sql="SELECT * FROM user_prodotti_offerta WHERE recordidvendite_='$master_recordid'";
                        $prodotti=  $this->select($sql);
                        //$this->add_ordiniclientikeysky($fields,$prodotti);
                        //$this->add_schedeordinekeysky($fields,$prodotti);
                    }
                } 
        }
        
        //CUSTOM DIMENSIONE IMMOBILIARE
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='immobili'))
        { 
           $sql="SELECT * FROM user_immobili WHERE recordid_='$master_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $fields=$result[0];
                if($funzione=='inserimento')
                {
                    
                    if($fields['tipo']=='terrenoinvendita')
                    {
                        
                        //mappale
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mappale' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Scheda calcolo stima
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Estratto Registro Fondiario
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        
                    }
                    if($fields['tipo']=='palazzinainvendita')
                    {
                        //Mandato
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Acuisizione
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Mappale
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mappale' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Estratto Registro Fondiario
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Regolamento condominiale
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Regolamento condominiale' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Relazione tecnica
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Spese generali
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese generali' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Copia assicurazione stabile
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Copia assicurazione stabile' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RASI
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RADON
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Importanti lavori eseguiti
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Importanti lavori eseguiti' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Scheda calcolo stima
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Contratti di locazione
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Contratti di locazione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        
                        
                    }
                    if($fields['categoria']=='appartamento')
                    {
                        //Mandato
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Acuisizione
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Costituzione PPP
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Costituzione PPP' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Estratto Registro Fondiario
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Regolamento condominiale
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Regolamento condominiale' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Polizza assicurativa
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Polizza assicurativa' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Spese condominiali
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese condominiali' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Copia assicurazione stabile
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Copia assicurazione stabile' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Ultimi 2 verbali assemblea
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Ultimi 2 verbali assemblea' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Ultimi 2 conguagli
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Ultimi 2 conguagli' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Relazione tecnica
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RASI
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RADON
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        
                        
                    }
                    if(strpos($fields['categoria'],'casa')!==FALSE)
                    {
                        //Mandato
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Mandato' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Acuisizione
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Acquisizione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Estratto Registro Fondiario
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto Registro Fondiario e Sommarione' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Relazione tecnica
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Relazione tecnica' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Estratto censuario
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Estratto censuario' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Spese generali
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Spese generali' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Importanti lavori eseguiti
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Importanti lavori eseguiti' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Polizza assicurativa casa
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Polizza assicurativa casa' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RASI
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RASI' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Certificato RADON
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Certificato RADON' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                        //Scheda calcolo stima
                        $linked_recordid=  $this->generate_recordid('immobile_documenti');
                        $sql="INSERT INTO user_immobile_documenti (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,id,tipo,record_preview,stato,recordidimmobili_) VALUES ('$linked_recordid',1,'2016-04-08 10:09:10',1,'2016-04-08 10:09:10',0,'N' ,2,'Scheda calcolo stima' ,null,'mancante','$master_recordid')";
                        $this->execute_query($sql);
                    }
                }
                
                //imposto il campo calcolato prezzo
                $prezzo='null';
                if($fields['tipo']=='immobileinvendita')
                {
                    $prezzo=$fields['imm_prezzoimmobile'];
                }
                if($fields['tipo']=='terrenoinvendita')
                {
                    $prezzo=$fields['ter_prezzo_vendita'];
                }
                if($fields['tipo']=='palazzinainvendita')
                {
                    $prezzo=$fields['pal_prezzoimmobile'];
                }
                if($prezzo=='')
                {
                    $prezzo='null';
                }
                $sql="UPDATE user_immobili SET prezzo=$prezzo WHERE recordid_='$master_recordid'";
                $this->execute_query($sql);
                
                //imposto il campo calcolato cognomeproprietario
                $recordid_contatti=$fields['recordidcontatti_'];
                if($this->isnotempty($recordid_contatti))
                {
                    $contatto=  $this->db_get_row('user_contatti', '*', "recordid_='$recordid_contatti'");
                    if($contatto!=null)
                    {
                        $cognome=$contatto['cognome'];
                        $sql="UPDATE user_immobili SET cognomeproprietario='$cognome' WHERE recordid_='$master_recordid'";
                        $this->execute_query($sql);
                    }
                }
                
                //imposto il campo locali_num
                $locali_num='null';
                if($fields['tipo']=='immobileinvendita')
                {
                    $locali_num=$fields['imm_locali_num'];
                }
                if($fields['tipo']=='immobileinaffitto')
                {
                    $locali_num=$fields['aff_locali_num'];
                }
                if($locali_num=='')
                {
                    $locali_num='null';
                }


                $sql="UPDATE user_immobili SET locali_num=$locali_num WHERE recordid_='$master_recordid'";
                $this->execute_query($sql);
                
                
                //aggiorno i portali in base allo stato
                if(($fields['stato']=='Eliminato')||($fields['stato']=='Venduto')||($fields['stato']=='Affittato'))
                {
                    $portali=  $this->db_get('user_immobili_portali', '*', "recordidimmobili_='$master_recordid'");
                    foreach ($portali as $key => $portale) {
                        $recordid_portale=$portale['recordid_'];
                        $sql="UPDATE user_immobili_portali SET status='Non pubblicato' WHERE recordid_='$recordid_portale'";
                        $this->execute_query($sql);
                    }
                }
            } 
        }
        
        if($master_tableid=='immobili_richiesti')
        {
            if (array_key_exists('linkedmaster', $table_container['insert']))
            {
                if (array_key_exists('immobili', $table_container['insert']['linkedmaster']))
                {
                    $row=  $this->db_get_row('user_immobili_richiesti', '*', "recordid_='$master_recordid'");
                    $immobile_recordid=$row['recordidimmobili_'];
                    if($this->isnotempty($immobile_recordid))
                    {
                        $immobile_richiesto=  $this->db_get_row('user_immobili','*',"recordid_='$immobile_recordid'");
                        $paese=$immobile_richiesto['paese'];
                        $tipo=$immobile_richiesto['tipo'];
                        $categoria=$immobile_richiesto['categoria'];
                        $sql="
                            UPDATE user_immobili_richiesti
                            SET paese='$paese',tipo='$tipo',categoria='$categoria'
                            WHERE recordid_='$master_recordid'
                            ";
                        $this->execute_query($sql);
                    }
                    
                }
            }
            
        }
        
        if(($funzione=='inserimento')&&($tableid=='immobili_richiesti'))
        {
            $immobili_proposti=$this->db_get('user_immobili_proposti','recordid_',"recordidimmobili_richiesti_='$master_recordid'");
            if(count($immobili_proposti)==0)
            {
                $fields_richiesta=  $this->db_get_row('user_immobili_richiesti', '*', "recordid_='$master_recordid'");
                $immobile_richiesto_recordid=$fields_richiesta['recordidimmobili_'];
                $recordid_contatti=$fields_richiesta['recordidcontatti_'];
                $provenienza_richiesta=$fields_richiesta['provenienza'];
                if(isnotempty($immobile_richiesto_recordid))
                {
                    $new_recordid_immobile_proposto=  $this->generate_recordid('immobili_proposti');
                    $sql="INSERT INTO user_immobili_proposti
                        (recordid_,statoproposta,provenienzaproposta,recordidimmobili_,recordidcontatti_,recordidimmobili_richiesti_)
                        VALUES
                        ('$new_recordid_immobile_proposto','Attiva','$provenienza_richiesta','$immobile_richiesto_recordid','$recordid_contatti','$master_recordid');
                        ";
                    $this->execute_query($sql);
                } 
                $new_recordid_agenda=  $this->generate_recordid('agenda');
                $userid=  $this->get_userid();
                $data=  date('Y-m-d');

                $sql="INSERT INTO user_agenda
                    (recordid_,utente,data,tipoattivita,recordidimmobili_,recordidcontatti_,recordidimmobili_richiesti_)
                    VALUES
                    ('$new_recordid_agenda','$userid','$data','Inserimento richiesta','$immobile_richiesto_recordid','$recordid_contatti','$master_recordid');
                    ";
                $this->execute_query($sql);
            }
            $this->matching_richiesta($master_recordid);
            
        }
        
        if($tableid=='immobili')
        {
            $this->matching_immobile($master_recordid);
        }
        
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='telefonate_principali'))
        {
            $sql="SELECT * FROM user_telefonate_principali WHERE recordid_='$master_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $fields=$result[0];
                $utente=$fields['utente'];
                $data=$fields['datatelefonata'];
                $ora=$fields['oratelefonata'];
                $note=$fields['note'];
                $per=$fields['perutente'];
                $recall=$fields['recall'];
                if($this->isnotempty($recall))
                {
                    $recall="'$recall'";
                }
                else
                {
                    $recall='null';
                }
                $recordidcontatti=$fields['recordidcontatti_'];
                if($this->isnotempty($per))
                {
                    $recordid_user_telefonate_principali=  $this->generate_recordid('telefonate_principali');
                    $sql="INSERT INTO user_telefonate_principali
                    (recordid_,datatelefonata,oratelefonata,utente,note,recall,recordidcontatti_)
                    VALUES
                    ('$recordid_user_telefonate_principali','$data',null,'$per','$note',$recall,'$recordidcontatti')
                    ";
                    $this->execute_query($sql);
                    
                    $recordid_telefonate=  $this->generate_recordid('telefonate');
                    $sql="INSERT INTO user_telefonate
                        (recordid_,data,ora,effettuatada,note,recall,recordidtelefonate_principali_,recordidcontatti_)
                        VALUES
                        ('$recordid_telefonate','$data',null,'$utente','$note',$recall,'$recordid_user_telefonate_principali','$recordidcontatti')
                        ";
                    $this->execute_query($sql);

                    $sql="
                        UPDATE user_telefonate_principali
                        SET recall=null
                        WHERE recordid_='$master_recordid'
                        ";
                    $this->execute_query($sql);
                    
                    
                }
                $recordid_telefonate=  $this->generate_recordid('telefonate');
                $sql="INSERT INTO user_telefonate
                    (recordid_,data,ora,effettuatada,note,recall,recordidtelefonate_principali_,recordidcontatti_)
                    VALUES
                    ('$recordid_telefonate','$data',null,'$utente','$note',$recall,'$master_recordid','$recordidcontatti')
                    ";
                $this->execute_query($sql);
            }
        }
        
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='telefonate'))
        {
            $sql="SELECT * FROM user_telefonate WHERE recordid_='$table_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $recall=$result[0]['recall'];
                if($this->isnotempty($recall))
                {
                    $recall="'$recall'";
                }
                else
                {
                    $recall='null';
                }
                $recordid_telefonate_principali=$result[0]['recordidtelefonate_principali_'];
                $sql="
                    UPDATE user_telefonate_principali
                    SET recall=$recall
                    WHERE recordid_='$recordid_telefonate_principali'
                    ";
                $this->execute_query($sql);
            }
            
        }
        
        
        
        //CUSTOM Work&Work
        if($tableid=='stampebollettini_candidati')
        {
            $sql="SELECT * FROM user_stampebollettini_candidati WHERE recordid_='$master_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $fields=$result[0];
                if($this->isnotempty($fields['recordidcandid_']))
                {
                    $recordid_candid=$fields['recordidcandid_'];
                    $codicebollettino=$fields['codicebollettino'];
                    $pchiave=$fields['pchiave'];
                    $sql="UPDATE user_candbollettino SET pchiave='$pchiave' WHERE recordidcandid_='$recordid_candid' AND codboll='$codicebollettino' ";
                    $this->execute_query($sql);
                    //$this->add_ordiniclientikeysky($fields,$prodotti);
                    //$this->add_schedeordinekeysky($fields,$prodotti);
                    $this->reset_bollettino_candidati($codicebollettino);
                    $candidati_bollettino=  $this->get_candidati_bollettino($codicebollettino);
                    foreach ($candidati_bollettino as $key => $candidato_bollettino) {
                        $this->set_bollettino_candidato($codicebollettino,$candidato_bollettino);
                    }
                }
            } 
        }
        
        //CUSTOM Work&Work
        if($master_tableid=='CANDID')
        {            
            $this->generate_stato($master_recordid);
            $this->generate_validato($master_recordid);
            $this->generate_qualifiche($master_recordid);
            $this->generate_giudizi($master_recordid);
            $this->generate_eta($master_recordid);
        }
       /* if($tableid=='timesheet')
        {
            $sql="SELECT * FROM user_attivitacommerciali WHERE recordid_='$new_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                
            }
        }*/
        
        //custom about
        if($tableid=='attivitacommerciali')
        { 
            //if((isset($post['tables']['attivitacommerciali']['insert']['t_1']['fields']['stato']['f_0']['value'])||(isset($post['tables']['attivitacommerciali']['update'][$master_recordid]['fields']['stato']['f_0']['value']))))
            //{
                $sql="SELECT * FROM user_attivitacommerciali WHERE recordid_='$new_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    if($fields['stato']=='conclusa')
                    {
                        $this->add_timesheet($tableid,$new_recordid, $fields);
                    }
                }
            //}
        }
        
        if($tableid=='sviluppi')
        { 
            $sql="SELECT * FROM user_sviluppi WHERE recordid_='$new_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $fields=$result[0];
                $recordidsegnalazioni=$fields['recordidsegnalazioni_'];
                if($this->isnotempty($recordidsegnalazioni!=''))
                {
                    $sql="UPDATE user_segnalazioni SET stato='aperta' WHERE recordid_='$recordidsegnalazioni'";
                    $this->execute_query($sql);
                }
            }
        }
        
        if($tableid=='attivitasviluppo')
        { 
                $sql="SELECT * FROM user_attivitasviluppo WHERE recordid_='$new_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    $this->add_timesheet($tableid,$new_recordid, $fields);
                    $recordidsviluppi=$fields['recordidsviluppi_'];
                    if($this->isnotempty($recordidsviluppi))
                    {
                        if(($fields['statosviluppo']!='completato')||($fields['statosviluppo']=='')||($fields['statosviluppo']==null))
                        {
                            $sql="UPDATE user_sviluppi SET statosviluppo='sviluppo' WHERE recordid_='$recordidsviluppi'";
                            $this->execute_query($sql);
                        } 
                        if($fields['statosviluppo']=='test')
                        {
                            $sql="UPDATE user_sviluppi SET statosviluppo='test' WHERE recordid_='$recordidsviluppi'";
                            $this->execute_query($sql);
                        }
                        if($fields['statosviluppo']=='rimandato')
                        {
                            $sql="UPDATE user_sviluppi SET statosviluppo='rimandato' WHERE recordid_='$recordidsviluppi'";
                            $this->execute_query($sql);
                        }
                        if($fields['statosviluppo']=='completato')
                        {
                            $sql="UPDATE user_sviluppi SET statosviluppo='completato' WHERE recordid_='$recordidsviluppi'";
                            $this->execute_query($sql);
                            $sql="SELECT * FROM user_sviluppi WHERE recordid_='$recordidsviluppi'";
                            $result=  $this->select($sql);
                            if(count($result)>0)
                            {
                                $fields=$result[0];
                                $recordidsegnalazioni=$fields['recordidsegnalazioni_'];
                                if($this->isnotempty($recordidsegnalazioni!=''))
                                {
                                    $sql="UPDATE user_segnalazioni SET stato='semichiusa' WHERE recordid_='$recordidsegnalazioni'";
                                    $this->execute_query($sql);
                                }
                            }
                        }
                    }
                }
        }
        
        if($tableid=='assistenze')
        { 
                $sql="SELECT * FROM user_assistenze WHERE recordid_='$table_recordid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $fields=$result[0];
                    $this->add_timesheet($tableid,$table_recordid, $fields);
                    //calcola durata assistenza
                    $orainizio=$fields['orainizio'];
                    $orafine=$fields['orafine'];
                    $date_diff=  $this->date_diff($orainizio, $orafine);
                    $durata='null';
                    if(($date_diff['h']!=0||$date_diff['m']!=0))
                    {
                        $durata="'".$date_diff['h'].":".$date_diff['m']."'";
                    }
                    $sql="UPDATE user_assistenze SET durata=$durata WHERE recordid_='$table_recordid'";
                    $this->execute_query($sql);
                    
                    $recordid_segnalazione=$fields['recordidsegnalazioni_'];
                    $data_assistenza=$fields['data'];
                    $today=date('Y-m-d');
                    if($this->isnotempty($recordid_segnalazione))
                    {
                        $sql="
                            UPDATE user_segnalazioni
                            set datalast='$today'
                            WHERE recordid_='$recordid_segnalazione'
                            ";
                        $this->execute_query($sql);
                        
                        if($this->isnotempty($data_assistenza))
                        {
                            $sql="
                            UPDATE user_segnalazioni
                            set dataprox='$data_assistenza'
                            WHERE recordid_='$recordid_segnalazione'
                            ";
                            $this->execute_query($sql);
                        }
                    }
                }
                
        }
        
        if($tableid=='timesheet')
        { 
            $sql="SELECT * FROM user_timesheet WHERE recordid_='$table_recordid'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $fields=$result[0];
                //calcola durata assistenza
                $orainizio=$fields['orainizio'];
                $orafine=$fields['orafine'];
                $date_diff=  $this->date_diff($orainizio, $orafine);
                $durata='null';
                if(($date_diff['h']!=0||$date_diff['m']!=0))
                {
                    $durata="'".$date_diff['h'].":".$date_diff['m']."'";
                }
                $sql="UPDATE user_timesheet SET totore=$durata WHERE recordid_='$table_recordid'";
                $this->execute_query($sql);
                
                $recordid_segnalazione=$fields['recordidsegnalazioni_'];
                if(isnotempty($recordid_segnalazione))
                {
                    $this->aggiorna_segnalazione($recordid_segnalazione);
                }
            }
            
        }
        
        if($tableid=='ordiniconsumabili_dettagli')
        {
            $this->script_totaleimporto_ordiniconsumabili();
        }
        
        //custom dimensione immobiliare
        if($tableid=='immobili_proposti')
        {
            if (array_key_exists('linkedmaster', $table_container['insert']))
            {
                if (array_key_exists('immobili', $table_container['insert']['linkedmaster']))
                {
                    $recordid_immobile=$table_container['insert']['linkedmaster']['immobili']['value'];
                    $immobile_preview_path="../JDocServer/record_preview/immobili/$recordid_immobile.jpg";
                    if(file_exists($immobile_preview_path))
                    {
                        if(!file_exists("../JDocServer/record_preview/immobili_proposti"))
                        {
                            mkdir("../JDocServer/record_preview/immobili_proposti");
                        }
                        copy($immobile_preview_path,"../JDocServer/record_preview/immobili_proposti/$master_recordid.jpg");
                    }
                }
            }
        }
        
        
        
        if($master_tableid=='segnalazioni')
        {
            $row=  $this->db_get_row('user_segnalazioni', '*', "recordid_='$table_recordid'");
            $segnalatore=$row['segnalatore'];
            $recordid_azienda=$row['recordidaziende_'];
            if(isempty($segnalatore)&&isnotempty($recordid_azienda))
            {
                $segnalatore=  $this->get_keyfieldlink_value('segnalazioni','aziende',$recordid_azienda,false);
            }
            $data=$now = date('Y-m-d');
            $sql="
                UPDATE user_segnalazioni
                SET datalast='$data',segnalatore='$segnalatore'
                WHERE recordid_='$master_recordid'
                ";
            $this->execute_query($sql);
            
        }
        
        if($tableid=='commessa_prodotti')
        {
            $row=  $this->db_get_row('user_commessa_prodotti', '*', "recordid_='$table_recordid'");
            $recordid_prodotto=$row['recordidprodotti_'];
            $recordid_commessa=$row['recordidcommesse_'];
            $tipoprodotto=$row['tipoprodotto'];
            $quantita=1;
            if($this->isempty($quantita))
            {
                $quantita=0;
            }
            
            $prezzovendita=  $row['prezzovendita'];
            if($this->isnotempty($prezzovendita))
            {
                $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                $importoriga=$prezzovendita*$quantita;
                $sql="
                INSERT INTO user_commessa_task
                (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                VALUES
                ('$new_recordid_task_commessa','Vendita',$quantita,$prezzovendita,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                ";
            $this->execute_query($sql);
            }
            
            if($tipoprodotto=='Multifunzione')
            {
                $basechargemanutenzione=$row['basechargemanutenzione'];
                 if($this->isnotempty($basechargemanutenzione))
                {
                    $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                    $importoriga=$basechargemanutenzione*$quantita;
                    $sql="
                    INSERT INTO user_commessa_task
                    (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                    VALUES
                    ('$new_recordid_task_commessa','Manutenzione',$quantita,$basechargemanutenzione,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                    ";
                $this->execute_query($sql);
                }

                $importomensilenoleggio=$row['importomensilenoleggio'];
                if($this->isnotempty($importomensilenoleggio))
                {
                    $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                    $importoriga=$importomensilenoleggio*$quantita;
                    $sql="
                    INSERT INTO user_commessa_task
                    (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                    VALUES
                    ('$new_recordid_task_commessa','Noleggio',$quantita,$importomensilenoleggio,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                    ";
                $this->execute_query($sql);
                }

                $importomensilexpps=$row['importomensilexpps'];
                if($this->isnotempty($importomensilenoleggio))
                {
                    $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                    $importoriga=$importomensilexpps*$quantita;
                    $sql="
                    INSERT INTO user_commessa_task
                    (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                    VALUES
                    ('$new_recordid_task_commessa','XPPS',$quantita,$importomensilexpps,$importoriga,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                    ";
                $this->execute_query($sql);
                }

                $new_recordid_task_commessa=  $this->generate_recordid('commessa_task');
                $sql="
                INSERT INTO user_commessa_task
                (recordid_,tipotask,quantita,prezzounitario,importoriga,recordidprodotti_,recordidcommesse_,recordidcommessa_prodotti_)
                VALUES
                ('$new_recordid_task_commessa','Eccedenza copie',1,0,0,'$recordid_prodotto','$recordid_commessa','$table_recordid');
                ";
                $this->execute_query($sql);
            }
            
            
        }
        
        if($tableid=='dem')
        {
            $row=  $this->db_get_row('user_dem', '*', "recordid_='$table_recordid'");
            $mail_body=$row['mail_body'];
            $ftp_folder=$row['ftp_folder'];
            $mail_body=  str_replace('src="images/', 'src="http://www.about-x.info/dem/'.$ftp_folder.'/images/', $mail_body);
            $mail_body=  str_replace("'", "''", $mail_body);
            $sql="
                UPDATE user_dem
                SET
                mail_body='$mail_body'
                WHERE recordid_='$table_recordid'
                ";
            $this->execute_query($sql);
        }
        
        
        
        //salva file
        if(array_key_exists("files", $post))
        {
          $this->modifica_allegati($post, $master_tableid, $master_recordid);  
        }
        
        
        
        
        
        //return 
        $return=$master_recordid;
        
        
        return $return;
        
    }
    
    //custom dimensione immobiliare TEMP
    public function insert_agenda($immobile_richiesto_recordid='',$recordid_contatti='',$recordid_richiesta='',$recordid_proposta='',$attivita)
    {
        $new_recordid_agenda=  $this->generate_recordid('agenda');
        $userid=  $this->get_userid();
        $data=  date('Y-m-d');

        $sql="INSERT INTO user_agenda
            (recordid_,utente,data,tipoattivita,recordidimmobili_,recordidcontatti_,recordidimmobili_richiesti_,recordidimmobili_proposti_)
            VALUES
            ('$new_recordid_agenda','$userid','$data','$attivita','$immobile_richiesto_recordid','$recordid_contatti','$recordid_richiesta','$recordid_proposta');
            ";
        $this->execute_query($sql);
    }
    
    public function salva_permessi_record($tableid,$recordid,$permessi_utente)
    {
        $tableowner='user_'.strtolower($tableid)."_owner";
        $sql="
            DELETE FROM $tableowner
            WHERE recordid_='$recordid'
            ";
        $this->execute_query($sql);
            foreach ($permessi_utente as $key => $userid) {
                $sql="
                    INSERT INTO $tableowner 
                    (recordid_,ownerid_)
                    VALUES ('$recordid',$userid)
                    ";
                $this->execute_query($sql);
            }
    }
    
    public function get_permessi_record($tableid,$recordid)
    {
        $table_owner="user_".strtolower($tableid)."_owner";
        $rows=  $this->db_get($table_owner, 'ownerid_', "recordid_='$recordid'");
        $return =array();
        foreach ($rows as $key => $row) {
            $return[]=$row['ownerid_'];
        }
        return $return;
    }
    
    public function modifica_allegati($post,$tableid,$recordid){
        $userid=$this->session->userdata('userid');
        $now = date('Y-m-d H:i:s');
        if(array_key_exists("files", $post))
        {
            $files=$post['files'];
        
        $sql="SELECT namefolder,numfilesfolder FROM sys_table where id='$tableid'";
        $result=  $this->select($sql);
        if(count($result)==1)
        {
            $namefolder=$result[0]['namefolder'];
            $numfilesfolder=$result[0]['numfilesfolder'];
        }
        $batchnumfiles=0;
        $batchid=null;
        if(array_key_exists('insert', $files))
        {
            $thumbnail_field="null";
            $fullpath_thumbnail='';
            $new_filename='';
            foreach ($files['insert'] as $index => $file) {
                //recupero dati del file in coda
                $fileid=$file['fileid'];
                $fileindex=$file['fileindex'];
                $fileparam=$file['fileparam'];
                $fileorigine=$file['fileorigine'];
                $filecategory=$file['filecategory'];
                $fullpath_batch_thumbnail='';
                if($fileorigine=='coda')
                {
                    $sql="
                    SELECT *
                    FROM sys_batch_file
                    WHERE fileid=$fileid
                    ";
                }
                if($fileorigine=='autobatch')
                {
                    $sql="
                    SELECT *
                    FROM sys_autobatch_file
                    WHERE fileid=$fileid
                    ";
                }
                    $result=  $this->select($sql);
                    $path="";
                    $filename="";
                    $fileext="";
                    if(count($result)==1)
                    {
                      $path=$result[0]['batchid'];
                      $filename=$result[0]['filename'];
                      $original_name=$result[0]['description'];
                      $fileext=$result[0]['fileext'];
                      //$batchnumfiles=$result[0]['numfiles'];
                      $batchid=$result[0]['batchid'];
                      $creatorid=$result[0]['creatorid'];
                    
                        if($fileorigine=='coda')
                        {
                            if($batchid=='sys_batch_temp')
                            {
                                $fullpath_batch="../JDocServer/batch/$path/$creatorid/$filename.$fileext";
                                

                            }
                            else
                            {
                                $fullpath_batch="../JDocServer/batch/$path/$filename.$fileext";
                            }
                        }
                        if($fileorigine=='autobatch')
                        {
                            $fullpath_batch="../JDocServer/autobatch/$path/$filename.$fileext";
                            if(file_exists("../JDocServer/autobatch/$path/$filename"."_thumbnail.jpg"))
                            {
                                $fullpath_batch_thumbnail="../JDocServer/autobatch/$path/$filename"."_thumbnail.jpg";
                            }
                        }
                        $new_filename=  $this->generate_filename($tableid);
                        $fullpath_archive="../JDocServer/archivi/$tableid/$namefolder/$new_filename.$fileext";
                        $fullpath_archive_thumbnail="../JDocServer/archivi/$tableid/$namefolder/$new_filename"."_thumbnail.jpg";

                        //sposto il file dalla coda nell'archivio
                        if(!file_exists("../JDocServer/archivi/$tableid"))
                            {
                                mkdir("../JDocServer/archivi/$tableid");
                            }
                        if(!file_exists("../JDocServer/archivi/$tableid/$namefolder"))
                            {
                                mkdir("../JDocServer/archivi/$tableid/$namefolder");
                            }
                        //copy($fullpath_batch,$fullpath_archive)
                        rename($fullpath_batch,$fullpath_archive);
                        if (true) 
                        {
                            if(!file_exists("../JDocServer/trash"))
                            {
                                mkdir("../JDocServer/trash");
                            }
                            if(!file_exists("../JDocServer/trash/code"))
                            {
                                mkdir("../JDocServer/trash/code");
                            }
                            //copy($fullpath_batch,"../JDocServer/trash/code/$filename.$fileext");
                            //unlink($fullpath_batch);
                            //rename($fullpath_batch,"../JDocServer/trash/code/$filename.$fileext");
                            if($fullpath_batch_thumbnail!='')
                            {
                                if (copy($fullpath_batch_thumbnail,$fullpath_archive_thumbnail)) 
                                {
                                    copy($fullpath_batch_thumbnail,"../JDocServer/trash/code/$filename"."_thumbnail.jpg");
                                    if(($this->isnotempty($path))&&($this->isnotempty($filename)))
                                    {
                                        unlink($fullpath_batch_thumbnail);
                                    }
                                    //rename($fullpath_batch_thumbnail,"../JDocServer/trash/code/$filename"."_thumbnail.jpg");
                                    $thumbnail_field="'$new_filename"."_thumbnail.jpg'";
                                }
                            }
                        }
                        //inserisco il file nel database come allegato del record
                        $page_tablename="user_".strtolower($tableid)."_page";
                        //CUSTOM POSTGRES
                        $fileindex=  $this->db_get_value($page_tablename, 'fileposition_', "recordid_='$recordid'","ORDER BY fileposition_ DESC");
                        $counter=$fileindex+1;
                        $original_name=  str_replace("'", "''", $original_name);
                        $sql="INSERT INTO $page_tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,counter_,fileposition_,path_,filename_,extension_,thumbnail,original_name,filestatusid_,signed_,deleted_,category) VALUES ('$recordid',$userid,'$now',$userid,'$now',$counter,$counter,'archivi\\\\".$tableid."\\\\".$namefolder."\\\\"."','$new_filename','$fileext',$thumbnail_field,'$original_name','S','N','N','$filecategory') ";
                        $this->set_logquery('inserimento allegato',$sql);
                        $this->execute_query($sql);



                        //recupero dati del record e li aggiorno
                        /*$tablename="user_".strtolower($tableid);
                        $sql="SELECT totpages_,firstpagefilename_ FROM $tablename WHERE recordid_='$recordid'";
                        $totpages=0;
                        $firstpagefilename="";
                        $result=  $this->select($sql);
                        if(count($result)==1)
                        {
                            $totpages=$result[0]['totpages_'];
                            $firstpagefilename=$result[0]['firstpagefilename_'];
                        }
                        $new_totpages=$totpages+$counter;
                        if(($firstpagefilename=="")||($firstpagefilename==null))
                        {
                            $firstpagefilename="test";
                        }
                        $sql="UPDATE $tablename SET totpages_=$totpages,firstpagefilename_='$firstpagefilename' WHERE recordid_='$recordid'";
                        $this->execute_query($sql);*/

                        //elimino record del file in coda dal database
                        if($fileorigine=='coda')
                        {
                            $sql="DELETE FROM sys_batch_file WHERE fileid=$fileid ";
                        }
                        if($fileorigine=='autobatch')
                        {
                            $sql="DELETE FROM sys_autobatch_file WHERE fileid=$fileid ";
                        }
                        $this->execute_query($sql);
                    }


            }
            //aggiorno sys_batch
            //$new_numfiles=$batchnumfiles-$counter;
            //$sql="UPDATE sys_batch SET numfiles=$new_numfiles WHERE id='$batchid'";
            //$this->execute_query($sql);
        
            //aggiorno la sys_table
            $new_numfilesfolder=$numfilesfolder+$counter;
            $intnewfilename=  intval($new_filename);
            $new_filename=  strval($intnewfilename);
            $sql="UPDATE sys_table SET numfilesfolder=$new_numfilesfolder,lastpageid='$new_filename' WHERE id='$tableid'";
            $this->execute_query($sql);
        }
        if(array_key_exists('update', $files))
        { 
            foreach ($files['update'] as $filename => $file) {
                $fileorigine=$file['fileorigine'];
                if($fileorigine=='allegati')
                {
                    //recupero dati del file in coda
                    $fileid=$file['fileid'];
                    $fileindex=$file['fileindex'];
                    $fileparam=$file['fileparam'];
                    $fileposition=$fileindex+1;
                    //inserisco il file nel database come allegato del record
                    $page_tablename="user_".strtolower($tableid)."_page";
                    $tablename="user_".strtolower($tableid);
                    $sql="UPDATE $page_tablename SET fileposition_='$fileposition' WHERE recordid_='$recordid' AND filename_='$filename'  ";
                    $this->execute_query($sql);


                    if($fileposition==1)
                    {

                    $sql="UPDATE $tablename SET firstpagefilename_='$fileid' WHERE recordid_='$recordid'";
                    $this->execute_query($sql);
                    }
                }
            }
 
        }
        if(array_key_exists('delete', $files))
        {
            foreach ($files['delete'] as $filename => $file) {
                //recupero dati del file in coda
                $fileid=$file['fileid'];
                $fileindex=$file['fileindex'];
                $fileparam=$file['fileparam'];
                $fileorigine=$file['fileorigine'];
                if($fileorigine=='allegati')
                {
                    $fileposition=$fileindex+1;
                    //inserisco il file nel database come allegato del record
                    $page_tablename="user_".strtolower($tableid)."_page";
                    $tablename="user_".strtolower($tableid);
                    
                    //recupero informazioni sul file allegato da eliminare
                    $sql="
                    SELECT *
                    FROM $page_tablename
                    WHERE recordid_='$recordid' AND filename_='$filename'
                    ";
                    $result=  $this->select($sql);
                    if(count($result)==1)
                    {
                        $relative_path=$result[0]['path_'];
                        $relative_path=  str_replace("\\", "/", $relative_path);
                        $relative_path=  str_replace("//", "/", $relative_path);
                        $fileext=$result[0]['extension_'];
                        //elimino dal database il file allegato
                        $sql="DELETE FROM $page_tablename  WHERE recordid_='$recordid' AND filename_='$filename'  ";
                        $this->set_logquery('eliminazione allegato',$sql);
                        $this->execute_query($sql);




                        $fullpath_archive="../JDocServer/$relative_path/$filename.$fileext";
                        $thumbnail_path="../JDocServer/$relative_path/$filename"."_thumbnail.jpg";

                        //elimino fisicamente il file
                        if(!file_exists("../JDocServer/trash"))
                        {
                            mkdir("../JDocServer/trash");
                        }
                        if(!file_exists("../JDocServer/trash/$tableid"))
                        {
                            mkdir("../JDocServer/trash/$tableid");
                        }
                        copy($fullpath_archive,"../JDocServer/trash/$tableid/$filename.$fileext");
                        if(($this->isnotempty($relative_path))&&($this->isnotempty($filename)))
                        {
                            unlink($fullpath_archive);
                            if(file_exists($thumbnail_path))
                            {
                                unlink($thumbnail_path);
                            }
                        }
                    }
                }
                if($fileorigine=='autobatch')
                {
                    $sql="
                    SELECT *
                    FROM sys_autobatch_file join sys_autobatch on sys_autobatch_file.batchid=sys_autobatch.id
                    WHERE fileid=$filename
                    ";
                    $result=  $this->select($sql);
                    if(count($result)==1)
                    {
                        $relative_path=$result[0]['path'];
                        $relative_path=  str_replace("\\", "/", $relative_path);
                        $fileext=$result[0]['extension_'];
                        //elimino dal database il file allegato
                        $sql="DELETE FROM sys_autobatch_file  WHERE fileid=$filename  ";
                        $this->set_logquery('eliminazione allegato coda',$sql);
                        $this->execute_query($sql);
                        $filename=$result[0]['filename'];
                        $fileext=$result[0]['fileext'];


                        $fullpath_autobatch="../JDocServer/autobatch/$relative_path/$filename.$fileext";

                        //elimino fisicamente il file
                        if(!file_exists("../JDocServer/trash"))
                        {
                            mkdir("../JDocServer/trash");
                        }
                        if(!file_exists("../JDocServer/trash/autobatch"))
                        {
                            mkdir("../JDocServer/trash/autobatch");
                        }
                        if(($this->isnotempty($relative_path))&&($this->isnotempty($filename)))
                        {
                            if(file_exists($fullpath_autobatch))
                            {
                                copy($fullpath_autobatch,"../JDocServer/trash/autobatch/$filename.$fileext");
                                unlink($fullpath_autobatch);
                            }
                        }
                    }
                }
                    
                    
                      

            }
            
             //aggiorno la sys_table
            $counter=  count($files['delete']);
            $new_numfilesfolder=$numfilesfolder-$counter;
            $sql="UPDATE sys_table SET numfilesfolder=$new_numfilesfolder WHERE id='$tableid'";
            $this->execute_query($sql);
        }
        
        }
        
        $cliente_id=  $this->get_cliente_id();
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='immobile_documenti'))
        {
               $sql="
                    UPDATE user_immobile_documenti SET stato='consegnato' WHERE recordid_='$recordid'
                    ";
                $this->execute_query($sql); 
        }
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
    
    
    public function generate_id($idarchivio){
        $tablename='user_'.strtolower($idarchivio);
        $sql="SELECT id FROM $tablename where id > 0 ORDER BY id DESC LIMIT 1";
        $result=  $this->select($sql);
        if(count($result)==0)
        {
            $new_id=1;
        }
        else
        {
            $id=$result[0]['id'];
            $new_id=$id+1;
        }
        return $new_id;
    }
    
    //custom WORK&WORK
    public function set_consulente($insert,$values){
        $creatorusername = $this->session->userdata('username');
        if (strtolower($creatorusername) == 'superuser') {
            $insert = $insert . ",consulente";
            $values = $values . ",'FC'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'FC'";
        }
        if (strtolower($creatorusername) == 'filippo') {
            $insert = $insert . ",consulente";
            $values = $values . ",'FC'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'FC'";
        }
        if (strtolower($creatorusername) == 'laura') {
            $insert = $insert . ",consulente";
            $values = $values . ",'LC'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'LC'";
        }
        if (strtolower($creatorusername) == 'francesca') {
            $insert = $insert . ",consulente";
            $values = $values . ",'FDO'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'FDO'";
        }
        if (strtolower($creatorusername) == 'tiziana') {
            $insert = $insert . ",consulente";
            $values = $values . ",'TP'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'TP'";
        }
        if (strtolower($creatorusername) == 'paola') {
            $insert = $insert . ",consulente";
            $values = $values . ",'PS'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'PS'";
        }
        if (strtolower($creatorusername) == 'alice') {
            $insert = $insert . ",consulente";
            $values = $values . ",'AM'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'AM'";
        }
        if (strtolower($creatorusername) == 'alberto') {
            $insert = $insert . ",consulente";
            $values = $values . ",'AG'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'AG'";
        }
        if (strtolower($creatorusername) == 'roberta') {
            $insert = $insert . ",consulente";
            $values = $values . ",'RDP'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'RDP'";
        }
        if (strtolower($creatorusername) == 'nicoletta') {
            $insert = $insert . ",consulente";
            $values = $values . ",'NV'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'NV'";
        }
        if (strtolower($creatorusername) == 'giulia') {
            $insert = $insert . ",consulente";
            $values = $values . ",'GB'";
            $insert = $insert . ",consulente_";
            $values = $values . ",'GB'";
        }
        $return['insert']=$insert;
        $return['values']=$values;
        return $return;
    }
    
    
    public function generate_riferimen($idarchivio){
        $tablename='user_'.strtolower($idarchivio);
        $sql="SELECT riferimen FROM $tablename where riferimen > 1 ORDER BY riferimen DESC LIMIT 1";
        $result=  $this->select($sql);
        $id=$result[0]['riferimen'];
        $new_id=$id+1;
        return $new_id;
    }
    
    
    function get_lookup_fields($tableid)
    {
        $fields=  $this->db_get('sys_field','fieldid,lookuptableid',"tableid='$tableid' AND lookuptableid!='' AND lookuptableid is not null");

        return $fields;
    }
    /**
     * 
     * @param type $tableid
     * @param type $recordid
     * @author Alessandro Galli
     */
    function get_fields_record($tableid,$recordid)
    {
       $table='user_'.strtolower($tableid); 
       $select="SELECT * ";
        $from=" FROM ".$table;
        $where=" WHERE recordid_='$recordid'";

        $sql_finale=$select.$from.$where;
        $return=  $this->select($sql_finale);
        if(count($return)>0)
        {
            return $return[0];
        }
        else
        {
            return false;
        }
    }
    
    function get_allrecords_linkedtable($tableid,$mastertableid,$mastertable_recordid)
    {
       $table='user_'.strtolower($tableid); 
       $select="SELECT * ";
        $from=" FROM ".$table;
        $where=" WHERE recordid".strtolower($mastertableid)."_='$mastertable_recordid'";

        $sql_finale=$select.$from.$where;
        $return=  $this->select($sql_finale);
        return $return;
    }
    
    
    public function get_descrizione_lookup($tableid,$fieldid,$fieldvalue){
        $fieldvalue=  str_replace("'", "''", $fieldvalue);
        $sql="
            SELECT itemdesc,lookupcodedesc 
            from sys_field JOIN sys_lookup_table_item ON sys_field.lookuptableid=sys_lookup_table_item.lookuptableid
            WHERE sys_field.tableid='$tableid' AND sys_field.fieldid='$fieldid' AND itemcode='$fieldvalue' ";
        $result= $this->select($sql);
        if(count($result)>0)
        {
        return $result[0]['itemdesc'];
        }
        else
        {
            return '';
        }
        
        
    }
    
    public function get_itemdesc_lookuptable($lookuptableid,$itemcode)
    {
        $sql="
            SELECT itemdesc
            FROM sys_lookup_table_item
            WHERE lookuptableid='$lookuptableid' AND itemcode='$itemcode'
            ";
        $rows=  $this->select($sql);
        if(count($rows)>0)
        {
            $row=$rows[0];
            return $row['itemdesc'];
        }
        else
        {
            return '';
        }
    }
    
    
    public function generate_filename($tableid){
        $page_tablename='user_'.strtolower($tableid)."_page";
        $sql="SELECT filename_ FROM $page_tablename ORDER BY filename_ DESC LIMIT 1";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $filename=$result[0]['filename_'];
        }
        else
        {
            $filename='00000000';
        }
        $intfilename=  intval($filename);
        $new_intfilename=$intfilename+1;
        $new_filename_short=  strval($new_intfilename);
        $new_filename_short_lenght=  strlen($new_filename_short);
        $new_filename='';
        for($i=0;$i<(8-$new_filename_short_lenght);$i++)
        {
            $new_filename=$new_filename.'0';
        }
        $new_filename=$new_filename.$new_filename_short;;
        return $new_filename;
    }
    
    public function generate_seriale($tableid,$fieldid){
        $tablename='user_'.strtolower($tableid);
        $sql="SELECT $fieldid FROM $tablename ORDER BY $fieldid DESC LIMIT 1";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $seriale=$result[0][$fieldid];
        }
        else
        {
            $seriale=0;
        }
        $new_seriale=$seriale+1;
        return $new_seriale;
    }
    
    
    public function elimina_record($tableid,$recordid){
        $cliente_id=$this->get_cliente_id();
        if($cliente_id=='3p')
        {
            // CUSTOM 3P modifica contratto INIZIO
            if($tableid=='contratti')
            {
                
                // resetto i rapporti di lavoro collegati a questo contratto
                $sql="
                    UPDATE user_rapportidilavoro
                    SET tipocontratto=null,visto=null,tipoorario=null,ore=null,datainizio=null,datafine=null,recordidcontratti_=null,recordidazienda_=null
                    WHERE recordidcontratti_='$recordid'
                    ";
                $this->execute_query($sql);



            }
            
            if($tableid=='presenzemensili')
            {
                $sql="SELECT mese, anno FROM user_presenzemensili WHERE recordid_='$recordid'" ;
                $result=  $this->select($sql);
                $mese_anno=$result[0]['mese']."-".$result[0]['anno'];
                
                //$this->add_custom_update('ordinamento',$mese_anno);
                //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('ordinamento','$mese_anno','todo')";
                //$this->execute_query($sql);
            }
            
            if($tableid=='notifiche')
            {
                $notifica=  $this->db_get_row('user_notifiche','*',"recordid_='$recordid'");
                $recordid_dipendente=$notifica['recordiddipendenti_'];
                $this->add_custom_update_php('aggiorna_presenze_dipendente', $recordid_dipendente);
                //$this->add_custom_update('presenzenotifiche_elimina',$recordid);
            }
        }
        $table="user_".strtolower($tableid);
        $sql="DELETE FROM $table WHERE recordid_='$recordid'";
        $this->set_logquery('eliminazione',$sql);
        $this->execute_query($sql);
        $linked_tables=  $this->get_linkedtables($tableid);
        foreach ($linked_tables as $key => $linked_table) {
            $linked_table="user_".strtolower($linked_table);
            $master_column="recordid".strtolower($tableid)."_";
            $sql="UPDATE $linked_table set $master_column= null WHERE $master_column='$recordid'";
            $this->set_logquery('aggiornamento post eliminazione',$sql);
            $this->execute_query($sql);
        }
        $page_tablename="user_".strtolower($tableid)."_page";
        $sql="SELECT * FROM $page_tablename WHERE recordid_='$recordid'";
        $files=  $this->select($sql);
            foreach ($files as $key => $file) 
            {
                $relative_path=$file['path_'];
                $relative_path=  str_replace("\\", "/", $relative_path);
                $fileext=$file['extension_'];
                $filename=$file['filename_'];
                //elimino dal database il file allegato
                $sql="DELETE FROM $page_tablename  WHERE recordid_='$recordid' AND filename_='$filename'";
                $this->set_logquery('eliminazione allegato',$sql);
                $this->execute_query($sql);
                $fullpath_archive="../JDocServer/$relative_path/$filename.$fileext";
                $thumbnail_path="../JDocServer/$relative_path/$filename"."_thumbnail.jpg";
                
                //elimino fisicamente il file
                if(!file_exists("../JDocServer/trash"))
                {
                    mkdir("../JDocServer/trash");
                }
                if(!file_exists("../JDocServer/trash/$tableid"))
                {
                    mkdir("../JDocServer/trash/$tableid");
                }
                copy($fullpath_archive,"../JDocServer/trash/$tableid/$filename.$fileext");
                if(($this->isnotempty($relative_path))&&($this->isnotempty($filename)))
                {
                    unlink($fullpath_archive);  
                    
                    if(file_exists($thumbnail_path))
                    {
                        unlink($thumbnail_path);
                    }
                }
                
            }
            
            $record_preview_path="../JDocServer/record_preview/$tableid/$recordid.jpg";
            if(file_exists($record_preview_path))
            {
                unlink($record_preview_path);
            }
             //aggiorno la sys_table
            //$counter=  count($files);
            //$new_numfilesfolder=$numfilesfolder-$counter;
            //$sql="UPDATE sys_table SET numfilesfolder=$new_numfilesfolder WHERE id='$tableid'";
            //$this->execute_query($sql);
    }
    
    public function duplica_record($tableid,$recordid){
        $new_recordid=  $this->generate_recordid($tableid);
        $fields=  $this->get_fields_record($tableid, $recordid);
        $fields2=$this->get_fields_table($tableid, 'null', $recordid);
        $insert="INSERT INTO user_".strtolower($tableid);
        $insert_fields="(recordid_ ";
        $insert_values="VALUES ('$new_recordid'";
        foreach ($fields2 as $key => $field) {
            $fieldid=$field['fieldid'];
            $fieldtypeid=$field['fieldtypeid'];
            $value=$field['valuecode'][0]['code'];
            if($value!=null)
            {
                $insert_fields=$insert_fields.",$fieldid";
                if(($fieldtypeid=='Numero')||($fieldtypeid=='Utente'))
                {
                   $insert_values=$insert_values.",$value"; 
                }
                if(($fieldtypeid=='Parola')||($fieldtypeid=='Data')||($fieldtypeid=='Ora')||($fieldtypeid=='Memo'))
                {
                    $value=  str_replace("'", "''", $value);
                    $insert_values=$insert_values.",'$value'";
                }
                if($fieldtypeid=='Seriale')
                {
                    $value=  $this->generate_seriale($tableid, $fieldid);
                    $insert_values=$insert_values.",$value";
                }
            }
            
        }
        
        $cliente_id= $this->get_cliente_id();
        if(($cliente_id=='3p')&&($tableid=='presenzemensili'))
        {
           $insert_fields=$insert_fields.",recordiddipendenti_"; 
           $recordid_dipendente= $this->db_get_value('user_presenzemensili','recordiddipendenti_',"recordid_='$recordid'");
           $insert_values=$insert_values.",'$recordid_dipendente'";
        }
        $insert_fields=$insert_fields.")";
        $insert_values=$insert_values.")";
        $sql=$insert." ".$insert_fields." ".$insert_values;
        $this->execute_query($sql);
        if(($cliente_id=='3p')&&($tableid=='presenzemensili'))
        {
            //$this->add_custom_update('presenze',$new_recordid);
            $this->aggiorna_presenze_completo($new_recordid);
        }
        return $new_recordid;
    }
    
    public function ripeti_record($tableid,$recordid){
        $new_recordid=  $this->generate_recordid($tableid);
        $fields=  $this->get_fields_record($tableid, $recordid);
        unset($fields['recordid_']);
        $seriali= $this->db_get('sys_field','fieldid',"tableid='$tableid' AND fieldtypeid='Seriale'");
        foreach ($seriali as $key => $seriale) {
            $fieldid=$seriale['fieldid'];
            $value=  $this->generate_seriale($tableid, $fieldid);
            $fields[$fieldid]=$value;
        }
        
        $insert="INSERT INTO user_".strtolower($tableid);
        $insert_fields="(recordid_ ";
        $insert_values="VALUES ('$new_recordid'";
        foreach ($fields as $fieldid => $value) {
            if($value!=null)
            {
                $insert_fields=$insert_fields.",$fieldid";
                $insert_values=$insert_values.",'$value'"; 
            }
            
        }
        $insert_fields=$insert_fields.")";
        $insert_values=$insert_values.")";
        $sql=$insert." ".$insert_fields." ".$insert_values;
        $this->execute_query($sql);
        return $new_recordid;
    }
    
    public function aggiorna_nuova_proposta_immobile($recordid_origine,$recordid_nuovo)
    {
        $proposta_origine=  $this->db_get_row('user_immobili_proposti', '*', "recordid_='$recordid_origine'");
        $recordidcontatti_origine=$proposta_origine['recordidcontatti_'];
        $recordidimmobili_richiesti_=$proposta_origine['recordidimmobili_richiesti_'];
        $dataproposta=  date("Y-m-d");
        $statoproposta='Attiva';
        $provenienza='Proposta da consulente';
        $note='';
        $sql="
            UPDATE user_immobili_proposti
            SET recordidcontatti_='$recordidcontatti_origine',recordidimmobili_richiesti_='$recordidimmobili_richiesti_',dataproposta='$dataproposta',statoproposta='$statoproposta',provenienzaproposta='$provenienza',note='$note'
            WHERE recordid_='$recordid_nuovo'
            ";
        $this->execute_query($sql);
    }
    function get_records_linkedmaster($linkedtableid,$master_tableid,$term='',$parent_value='',$master_recordid='')
    {
       $cliente_id=$this->get_cliente_id();
       
      $today=date("Y-m-d");

       
      $sql="SELECT keyfieldlink FROM sys_field WHERE tableid='$master_tableid' AND tablelink='$linkedtableid'" ;
      $result=  $this->select($sql);
      if(count($result)>0)
      {
          $keyfieldlink=$result[0]['keyfieldlink'];
      }
      $keyfieldlink= strtolower($keyfieldlink);
      $keyfieldlink_array=  explode(",", $keyfieldlink);
      $keyfieldlink_order=$keyfieldlink_array[0];
      $master_table="user_".strtolower($linkedtableid);
      $linkedtable="user_".strtolower($linkedtableid);
      
      
      $search_term='true';
      
      if(($linkedtableid=='qualifiche')&&($parent_value!=''))
      {
            $search_term=$search_term." AND recordidccl_ = '$parent_value' ";
      }
      
      if(($master_tableid<>'contratti')&&(($linkedtableid=='ccl')||($linkedtableid=='qualifiche')||($linkedtableid=='fasciaeta')||($linkedtableid=='professioni')))
      {
          $search_term=$search_term." AND stato = 'Verificato' AND (('$today'>=datainizio AND datafine is null) OR  ('$today'>=datainizio AND '$today'<=datafine))                          ";
      }
      
      if(($master_tableid=='contratti')&&(($linkedtableid=='ccl')||($linkedtableid=='qualifiche')||($linkedtableid=='fasciaeta')||($linkedtableid=='professioni')))
      {
            $contratto= $this->db_get_row('user_contratti', '*', "recordid_='$master_recordid'");
            $datainizio=$contratto['datainizio'];
            $search_term=$search_term." AND stato = 'Verificato' AND (('$datainizio'>=datainizio AND datafine is null) OR  ('$datainizio'>=datainizio AND '$datainizio'<=datafine))                          ";
      }
      
      if($term=="sys_recent")
      {
          $sql="SELECT recordid_,$keyfieldlink FROM $linkedtable WHERE $search_term AND recordid_ not like '1%' ORDER BY recordid_ LIMIT 100 ";
      }
      else
      {
         if($term=="sys_all")
         {
             $sql="SELECT recordid_,$keyfieldlink FROM $linkedtable WHERE $search_term ORDER BY $keyfieldlink_order ";
         }
         else
         {
             $sql="SELECT recordid_,$keyfieldlink FROM $linkedtable WHERE $search_term ORDER BY $keyfieldlink_order ";
         }
      }
      $result=  $this->select($sql);
      return $result;
    }
    
    function get_field_linkedmaster($tableid,$recordid,$linkedmasterid)
    {
      $sql="SELECT keyfieldlink FROM sys_field WHERE tableid='$tableid' AND tablelink='$linkedmasterid'" ;
      $result=  $this->select($sql);
      if(count($result)>0)
      {
          $keyfieldlink=$result[0]['keyfieldlink'];
      }
      $keyfieldlink= strtolower($keyfieldlink);
      $keyfieldlink_array=  explode(",", $keyfieldlink);
      $keyfieldlink_order=$keyfieldlink_array[0];
      
      $table="user_".strtolower($tableid);
      $sql="SELECT recordid".strtolower($linkedmasterid)."_ FROM sys_field WHERE tableid='$tableid' AND tablelink='$linkedmasterid'" ;
      $result=  $this->select($sql);
      if(count($result)>0)
      {
          $linkedmaster_recordid=$result[0]["recordid".strtolower($linkedmasterid)."_"];
      }
      
      $linkedmaster="user_".strtolower($linkedmasterid);
      $sql="SELECT recordid_,$keyfieldlink FROM $linkedmaster WHERE recordid_='$linkedmaster_recordid'";

      $result=  $this->select($sql);
      return $result;
    }
    
    
    // inizio impostazioni 
    
    /**
     * Ritorna l'elenco di tutti gli archivi(sia quelli su cui fare ricerche che quelli usati come lookup)
     * 
     * @return array lista archivi
     * @author Alessandro Galli
     * 
     */
    function get_archive_list()
    {
        $this->db->select('description as nomearchivio,id as idarchivio');
        $query = $this->db->get('sys_table');
        $sql="
            SELECT *,description as nomearchivio,id as idarchivio
            FROM sys_table
            ORDER BY nomearchivio
            ";
        return $this->select($sql);
        
    }

    
    
    
    /**
     * Ritorna i campi senza valore di un archivio specifico
     * 
     * @param string $archive nome archivio di cui recuperare i campi
     * @return array campi dell'archivio  
     * @author Alessandro Galli 
     */
    function get_all_emptyfields($masterstableid,$recordid_=null)
    {

        $sql="
            SELECT tableid,fieldid,fieldtypeid,description,fieldorder,lookuptableid,label,tablelink
            FROM sys_field
            WHERE tableid='$masterstableid' AND label <> 'Old'
            ";
        $fields_mastertable =  $this->select($sql);
        $fields_finali=array();
        
        foreach ($fields_mastertable as $field) {
                //if(($field['tablelink']==null)||($field['tablelink']==''))
                if(true)
                {
                    $label_key=$field['label'];
                    $fields_finali[$label_key]['label']=$field['label'];
                    $fields_finali[$label_key]['tableid']=$masterstableid;
                    $fields_finali[$label_key]['type']='master';
                    $fields_finali[$label_key]['fields'][]=$field;
                }
                else
                {   
                    $fields_linkedtable=  $this->get_all_emptyfields_linkedtable($field['tablelink']);
                    $label_key=$field['label'];
                    $fields_finali[$label_key]['label']=$field['description'];
                    $fields_finali[$label_key]['tableid']=$field['tablelink'];
                    $fields_finali[$label_key]['type']='linked';
                    $fields_finali[$label_key]['fields']=$fields_linkedtable;   
                }
            
        }
        $label_key='sys';
        $fields_finali[$label_key]['label']='sys';
        $fields_finali[$label_key]['tableid']=$masterstableid;
        $fields_finali[$label_key]['type']='master';
        $field=array();
        $field['tableid']=$masterstableid;
        $field['fieldid']='creatorid';
        $field['description']='creatorid';
        $fields_finali[$label_key]['fields'][0]=$field;
        return $fields_finali;
    }
    
    
    
    function get_all_emptyfields_linkedtable($linkedtableid)
    {

        $sql="
            SELECT *
            FROM sys_field 
            WHERE sys_field.tableid='$linkedtableid' AND sys_field.label!='Old' AND tablelink is null
            ";
        $fields_linkedtable =  $this->select($sql);
        $fields_linkedtable_return=array();
        return $fields_linkedtable;
    }
    
    
    public function get_label_list($masterstableid)
    {
        $labels_finali=array();
        $sql="
            SELECT label
            FROM sys_field
            WHERE tableid='$masterstableid' AND label!='Old'
            GROUP BY label
            order by label
            ";
        $labels =  $this->select($sql);
        foreach ($labels as $label)
            $labels_finali[]=$label['label'];   
        /*
        
        $sql="
            SELECT tablelinkid
            FROM sys_table_link
            WHERE tableid='$masterstableid'
            ";
        $labels=$this->select($sql);
        foreach ($labels as $label)
            $labels_finali[]=$label['tablelinkid'];  
        
        
        $sql="
            SELECT tableid
            FROM sys_table_link
            WHERE tablelinkid='$masterstableid'
            ";
        $labels=$this->select($sql);
        foreach ($labels as $label)
            $labels_finali[]=$label['tableid'];*/
        
        return $labels_finali;
    }
    
    
    public function LoadPreferencesLabel($idarchivio,$typeLabel,$idutente)
    {
        $sql="SELECT DISTINCT(label) AS label,sys_user_order.fieldorder
              FROM sys_user_order JOIN sys_field
                        ON sys_user_order.fieldid=sys_field.label
              WHERE sys_user_order.tableid='$idarchivio' AND sys_field.tableid='$idarchivio' AND typepreference='$typeLabel'
              ORDER BY sys_user_order.fieldorder";
        return $this->select($sql);
    }
    
    public function get_labels($masterstableid)
    {
        $sql="
            SELECT sublabelname as labelname
            FROM sys_table_sublabel
            WHERE tableid='$masterstableid'
            ";
        $fields_mastertable =  $this->select($sql);
        $fields_finali=array();
        foreach ($fields_mastertable as $field)
            $fields_finali[]=$field['labelname'];            
        return $fields_finali;
    }
    
    public function get_autobatch()
    {
        $sql="SELECT *
              FROM sys_autobatch";
        return $this->select($sql);
    }
    
    public function get_autobatch_files($autobatchid)
    {
        $sql="SELECT *
              FROM sys_autobatch_file
              WHERE batchid='$autobatchid'
              ORDER BY fileid ASC    
                ";
        return $this->select($sql);
    }
    
    public function get_recordid_byrif($tableid,$rif)
    {
        $table=  'user_'.strtolower($tableid);
        $sql="SELECT recordid_
                  FROM $table";
        $return='';
        if($tableid=='CONTRA')
        {
            $sql=$sql." WHERE riferimen=$rif";
            $result=$this->select($sql);
            if(count($result)==1)
            {
                $return=$result[0]['recordid_'];
            }
            else
            {
                $return='errore';
            }
            
        }
        return $return;
    }
    
    public function autobatch_insert_file($autobatchid,$autobatch_fileid,$tableid,$recordid){
                    $sql="SELECT * from sys_autobatch WHERE id='$autobatchid'";
                    $result=  $this->select($sql);
                    $autobatch_path=$result[0]['path'];
                    $cliente_id=$this->get_cliente_id();
                    $sql="SELECT namefolder FROM sys_table where id='$tableid'";
                    $result=  $this->select($sql);
                    if(count($result)==1)
                    {
                        $namefolder=$result[0]['namefolder'];
                    }
                    $sql="
                    SELECT *
                    FROM sys_autobatch_file join sys_autobatch on sys_autobatch_file.batchid=sys_autobatch.id
                    WHERE sys_autobatch_file.fileid=$autobatch_fileid
                    ";
                    $result=  $this->select($sql);
                    $path="";
                    $filename="";
                    $fileext="";
                    if(count($result)==1)
                    {
                      $path=$result[0]['path'];
                      $filename=$result[0]['filename'];
                      $description=$result[0]['description'];
                      $ocr=$result[0]['ocr'];
                      $fileext=$result[0]['fileext'];
                      $batchnumfiles=$result[0]['numfiles'];
                      $batchid=$result[0]['id'];
                      $creatorid=$result[0]['creatorid'];
                    }


                    $fullpath_batch="../JDocServer/autobatch/$path/$filename.$fileext";
                    
                    $new_filename=  $this->generate_filename($tableid);
                    $fullpath_archive="../JDocServer/archivi/$tableid/$namefolder/$new_filename.$fileext";

                    //creo le cartelle necessarie se ancora non esistenti
                    if(!file_exists("../JDocServer/archivi/$tableid"))
                    {
                        mkdir("../JDocServer/archivi/$tableid");
                    }
                    if(!file_exists("../JDocServer/archivi/$tableid/$namefolder"))
                    {
                        mkdir("../JDocServer/archivi/$tableid/$namefolder");
                    }
                    if(!file_exists("../JDocServer/trash"))
                    {
                        mkdir("../JDocServer/trash");
                    }
                    if(!file_exists("../JDocServer/trash/autobatch"))
                    {
                        mkdir("../JDocServer/trash/autobatch");
                    }
                    if(!file_exists("../JDocServer/trash/autobatch/$autobatchid"))
                    {
                        mkdir("../JDocServer/trash/autobatch/$autobatchid");
                    }
                    
                    //sposto il file dalla coda all'archivio
                    if (copy($fullpath_batch,$fullpath_archive)) 
                    {                      
                        
                         //inserisco il file nel database come allegato del record
                        $page_tablename="user_".strtolower($tableid)."_page";
                        $counter=1;
                        if($recordid=='')
                        {
                            $recordid=  $this->generate_recordid($tableid);
                            $tablename="user_".strtolower($tableid);
                            $now=date("Y-m-d H:i:s");
                            $today=date("Y-m-d");
                            $insert = "INSERT INTO $tablename (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,totpages_,deleted_,recordstatus_";
                            $values=" VALUES ('$recordid',1,'$now',1,'$now',1,'N','autobatched'";
                            if(($cliente_id=='NewTrends')&&($tableid=='documenti'))
                            {
                                $ocr=  str_replace("'", "''", $ocr);
                                $id=  $this->generate_seriale($tableid, 'id');
                                $tipo=$autobatch_path;
                                $insert=$insert.",id,tipo,titolo,datascan,ocr";
                                $values=$values.",$id,'$tipo','$description','$today','$ocr'";
                            }
                            if(($cliente_id=='BB Kapital')&&($tableid=='documenti'))
                            {
                                $ocr=  str_replace("'", "''", $ocr);
                                $id=  $this->generate_seriale($tableid, 'id');
                                $tipodoc=$autobatch_path;
                                $insert=$insert.",id,tipodoc,titolo,datascan,ocr";
                                $values=$values.",$id,'$tipodoc','$description','$today','$ocr'";
                            }
                            
                            //CUSTOM SEA TRADE inserimento file inizio
                            if(($cliente_id=='Sea Trade')&&($tableid=='documenti'))
                            {
                                //dati documento
                                $id=  $this->generate_seriale($tableid, 'id');
                                $tipo='';
                                $titolo=$filename;
                                $oggetto="";
                                $ref="";
                                $datascan=$today;
                                $stato='inviato';
                                $ocr_doc="";
                                $recordid_azienda_trovata="null";
                                
                                //dati mail
                                $mail_azienda_trovata='';
                                $mailto='';
                                $mailsubject='';
                                $mailbody='';
                                    
                                if(file_exists("../JDocServer/autobatch/$autobatchid/$filename.txt"))
                                {
                                    $ocr_doc = file_get_contents("../JDocServer/autobatch/$autobatchid/$filename.txt", FILE_USE_INCLUDE_PATH);
                                }
                                $ocr_doc=str_replace("'", " ", $ocr_doc);
                                
                                if($autobatchid=='ScanshareOutput_scanner')
                                {
                                    //DATI DOCUMENTO
                                    $tipo='Scanner';
                                    $ref='';
                                    //cerco il riferimento del documento
                                    $ocr_ref=  $this->get_value_from_key($ocr_doc,'OCR_REF');
                                    $ocr_ref=  str_replace("\r\n", "", $ocr_ref);
                                    $ocr_ref=  str_replace("\n", "", $ocr_ref);
                                    $ocr_ref=  str_replace("\r", "", $ocr_ref);
                                    $ref=  str_replace("OUR REF.:", "", $ocr_ref);
                                    $ref=  str_replace(" ", "", $ref);
                                    $ref=  substr($ref, 0,10);
                                    

                                    //cerco il codice e l'azienda relativa per avere anche la mail
                                    $mail_azienda_trovata='';
                                    $ocr_codice=  $this->get_value_from_key($ocr_doc,'OCR_CODICE');
                                    $codice=  str_replace("\r\n", "", $ocr_codice);
                                    $codice=  str_replace("\n", "", $codice);
                                    $codice=  str_replace("\r", "", $codice);
                                    $codice=  trim($codice);
                                    $codice=  str_replace(",", ".", $codice);
                                    $codice=  str_replace(" ", ".", $codice);
                                    $azienda=$this->db_get_row('user_aziende','*',"codicegestionale='$codice'");
                                    if($azienda!=null)
                                    {
                                        //trovata azienda con il codice
                                        $recordid_azienda=$azienda['recordid_'];
                                        $recordid_azienda_trovata="'$recordid_azienda'";
                                        $mail_azienda_trovata='';
                                        if($azienda['email']!='')
                                        {
                                            $mail_azienda_trovata=$azienda['email'];
                                        }
                                        else
                                        {
                                           $stato='nomail';
                                        }

                                    }
                                    else
                                    {
                                        $stato='noazienda';
                                    }

                                    //cerco il vessel
                                    $vessel='';
                                    $ocr_vessel=  $this->get_value_from_key($ocr_doc,'OCR_VESSEL');
                                    $ocr_vessel=  str_replace("\r\n", "", $ocr_vessel);
                                    $ocr_vessel=  str_replace("\n", "", $ocr_vessel);
                                    $ocr_vessel=  str_replace("\r", "", $ocr_vessel);
                                    $ocr_vessel_arr = explode("ON:", $ocr_vessel, 2);
                                    if(count($ocr_vessel_arr)>0)
                                    {
                                        $vessel = $ocr_vessel_arr[0]; 
                                        $vessel=  str_replace("VESSEL:", "VESSEL: ", $vessel);
                                        $oggetto=$vessel." REF: $ref";
                                        $vessel=  str_replace("'", "''", $vessel);

                                    }
                                    else
                                    {
                                        $stato='nosubject';
                                    }
                                    
                                    //DATI MAIL
                                    $mailto=$mail_azienda_trovata;
                                    $mailsubject=$oggetto;
                                    $mailbody='
                                    Dear Madams, dear Sirs, 
 
                                    enclosed you can find documents related to departure in subject. 

                                    For any further information please write to docs@seatradeinternational.ch 

                                    Many thanks and kind regards, 

                                    Sea Trade International SA 

                                    Viale G.Cattori 3 
                                    6900 Paradiso - Switzerland 
                                    Tel. +41 91 994 19 90 
                                    Fax +41 91 994 19 85 
                                    www.seatradeinternational.ch  
                                    ';
                                }
                                
                                
                                
                                if(($autobatchid=='ScanshareOutput_fatture'))
                                {
                                    $tipo='Fattura';
                                    $tipomail='emailfatture';
                                    $mailbody='
                                    Dear Madams, dear Sirs, 
 
                                    enclosed you can find our invoice as per details in subject. 
                                    This pdf document sent per email is to be kept as original and replaces any sending per ordinary mail. 

                                    For any further information please write to docs@seatradeinternational.ch 

                                    Many thanks and kind regards, 

                                    Sea Trade International SA 

                                    Viale G.Cattori 3 
                                    6900 Paradiso - Switzerland 
                                    Tel. +41 91 994 19 90 
                                    Fax +41 91 994 19 85 
                                    www.seatradeinternational.ch      
                                    ';
                                    //DATI DOCUMENTO
                                    // cerco ref
                                    $ocr_ref=  $this->get_value_from_key($ocr_doc,'OCR_REF');
                                    $ocr_ref=  str_replace("\r\n", "", $ocr_ref);
                                    $ocr_ref=  str_replace("\n", "", $ocr_ref);
                                    $ocr_ref=  str_replace("\r", "", $ocr_ref);
                                    $ocr_ref_arr=explode('OUR REF:', $ocr_ref, 2);
                                    if(count($ocr_ref_arr)>1)
                                    {
                                        $ref=$ocr_ref_arr[1];
                                        $ref_arr=  explode('(', $ref,2);
                                        $ref=$ref_arr[0];
                                        $ref=  str_replace(' ', '', $ref);
                                        $ref=  substr($ref, 0,10);
                                    }
                                    else
                                    {
                                        $stato='noref';
                                    }
                                    
                                     //cerco invoice e codice
                                    $invoice='';
                                    $codice='';
                                    $ocr_invoice_codice=  $this->get_value_from_key($ocr_doc,'OCR_INVOICE_CODICE');
                                    $invoice_pos=0;
                                    $invoice_pos=strpos($ocr_invoice_codice, 'Invoice');
                                    if($invoice_pos !== false)
                                    {
                                        $ocr_invoice_codice=substr($ocr_invoice_codice,  $invoice_pos);
                                    }
                                    $ocr_invoice_codice_arr=  explode("\n", $ocr_invoice_codice,2);
                                    if(count($ocr_invoice_codice_arr)>0)
                                    {
                                        $invoice=$ocr_invoice_codice_arr[0];
                                        $invoice=  str_replace("\r\n", "", $invoice);
                                        $invoice=  str_replace("\n", "", $invoice);
                                        $invoice=  str_replace("\r", "", $invoice);
                                        $codice=  str_replace($invoice, '', $ocr_invoice_codice);
                                        $codice=  str_replace("\r\n", "", $codice);
                                        $codice=  str_replace("\n", "", $codice);
                                        $codice=  str_replace("\r", "", $codice);
                                        $codice=  trim($codice);
                                        $codice=  str_replace(",", ".", $codice);
                                        $codice=  str_replace(" ", ".", $codice);
                                        
                                    }
                                    else
                                    {
                                        $stato='nosubject';
                                    }
                                    
                                    
                                    
                                    //cerco azienda e relativa mail
                                    $azienda=$this->db_get_row('user_aziende','*',"codicegestionale='$codice'");
                                    if($azienda!=null)
                                    {
                                        //trovata azienda con il codice
                                        $recordid_azienda=$azienda['recordid_'];
                                        $recordid_azienda_trovata="'$recordid_azienda'";
                                        $mail_azienda_trovata='';
                                        if($azienda[$tipomail]!='')
                                        {
                                            $mail_azienda_trovata=$azienda[$tipomail];
                                        }
                                        else
                                        {
                                           $stato='nomail';
                                        }

                                    }
                                    else
                                    {
                                        $stato='noazienda';
                                    }
                                    $oggetto=$invoice. " REF: ".$ref;

                                    //DATI MAIL
                                    $mailto=$mail_azienda_trovata;
                                    $mailsubject=$oggetto;
                                }
                                
                                if($autobatchid=='ScanshareOutput_bolle')
                                {
                                    $tipo='Bolla';
                                    $tipomail='emailbolle';
                                    $mailbody='
                                    Dear Madams, dear Sirs, 
 
                                    enclosed you can find our invoice(s) and your export declaration(s) 

                                    For any further information please write to docs@seatradeinternational.ch 

                                    Many thanks and kind regards, 

                                    Sea Trade International SA 

                                    Viale G.Cattori 3 
                                    6900 Paradiso - Switzerland 
                                    Tel. +41 91 994 19 90 
                                    Fax +41 91 994 19 85 
                                    www.seatradeinternational.ch 
                                        ';
                                    // cerco ref
                                    
                                     //cerco invoice e codice
                                    $invoice='';
                                    $codice='';
                                    $ocr_invoice_codice=  $this->get_value_from_key($ocr_doc,'OCR_INVOICE_CODICE');
                                    $invoice_pos=0;
                                    $invoice_pos=strpos($ocr_invoice_codice, 'Invoice');
                                    if($invoice_pos !== false)
                                    {
                                        $ocr_invoice_codice=substr($ocr_invoice_codice,  $invoice_pos);
                                    }
                                    $ocr_invoice_codice_arr=  explode("\n", $ocr_invoice_codice,2);
                                    if(count($ocr_invoice_codice_arr)>0)
                                    {
                                        $invoice=$ocr_invoice_codice_arr[0];
                                        $invoice=  str_replace("\r\n", "", $invoice);
                                        $invoice=  str_replace("\n", "", $invoice);
                                        $invoice=  str_replace("\r", "", $invoice);
                                        $codice=  str_replace($invoice, '', $ocr_invoice_codice);
                                        $codice=  str_replace("\r\n", "", $codice);
                                        $codice=  str_replace("\n", "", $codice);
                                        $codice=  str_replace("\r", "", $codice);
                                        $codice=  trim($codice);
                                        $codice=  str_replace(",", ".", $codice);
                                        $codice=  str_replace(" ", ".", $codice);
                                        
                                    }
                                    else
                                    {
                                        $stato='nosubject';
                                    }
                                    
                                    
                                    
                                    //cerco azienda e relativa mail
                                    $azienda=$this->db_get_row('user_aziende','*',"codicegestionale='$codice'");
                                    if($azienda!=null)
                                    {
                                        //trovata azienda con il codice
                                        $recordid_azienda=$azienda['recordid_'];
                                        $recordid_azienda_trovata="'$recordid_azienda'";
                                        $mail_azienda_trovata='';
                                        if($azienda[$tipomail]!='')
                                        {
                                            $mail_azienda_trovata=$azienda[$tipomail];
                                        }
                                        else
                                        {
                                           $stato='nomail';
                                        }

                                    }
                                    else
                                    {
                                        $stato='noazienda';
                                    }
                                    $oggetto=$invoice;

                                    //DATI MAIL
                                    $mailto=$mail_azienda_trovata;
                                    $mailsubject=$oggetto;
                                }
                                
                                if($autobatchid=='ScanshareOutput_bollenofattura')
                                {
                                    //DATI DOCUMENTO
                                    $tipo='BollaNoFattura';
                                    $tipomail='emailbolle';
                                    $mailbody='
                                    Dear Madams, dear Sirs, 
                                    
                                    enclosed you can find your export declaration(s) 
                                    
                                    For any further information please write to docs@seatradeinternational.ch 
                                    
                                    Many thanks and kind regards,

                                    Sea Trade International SA 
                                    
                                    Viale G.Cattori 3 
                                    6900 Paradiso - Switzerland 
                                    Tel. +41 91 994 19 90 
                                    Fax +41 91 994 19 85 
                                    www.seatradeinternational.ch
                                        ';
                                    $ref='';
                                     //cerco invoice e codice
                                    $mrn='';
                                    $codice='';
                                    $ocr_mrn_codice=  $this->get_value_from_key($ocr_doc,'OCR_INVOICE_CODICE');
                                    $mrn_pos=0;
                                    $mrn_pos=strpos($ocr_mrn_codice, 'MRN');
                                    if($mrn_pos !== false)
                                    {
                                        $ocr_mrn_codice=substr($ocr_mrn_codice,  $mrn_pos);
                                    }
                                    $ocr_mrn_codice_arr=  explode("\n", $ocr_mrn_codice,2);
                                    if(count($ocr_mrn_codice_arr)>0)
                                    {
                                        $mrn=$ocr_mrn_codice_arr[0];
                                        $mrn=  str_replace("\r\n", "", $mrn);
                                        $mrn=  str_replace("\n", "", $mrn);
                                        $mrn=  str_replace("\r", "", $mrn);
                                        $codice=  str_replace($mrn, '', $ocr_mrn_codice);
                                        $codice=  str_replace("\r\n", "", $codice);
                                        $codice=  str_replace("\n", "", $codice);
                                        $codice=  str_replace("\r", "", $codice);
                                        $codice=  trim($codice);
                                        $codice=  str_replace(",", ".", $codice);
                                        $codice=  str_replace(" ", ".", $codice);

                                    }
                                    else
                                    {
                                        $stato='noazienda';
                                    }
                                    //cerco azienda e relativa mail
                                    $azienda=$this->db_get_row('user_aziende','*',"codicegestionale='$codice'");
                                    if($azienda!=null)
                                    {
                                        //trovata azienda con il codice
                                        $recordid_azienda=$azienda['recordid_'];
                                        $recordid_azienda_trovata="'$recordid_azienda'";
                                        $mail_azienda_trovata='';
                                        if($azienda[$tipomail]!='')
                                        {
                                            $mail_azienda_trovata=$azienda[$tipomail];
                                        }
                                        else
                                        {
                                           $stato='nomail';
                                        }

                                    }
                                    else
                                    {
                                        $stato='noazienda';
                                    }
                                    $oggetto='Export Declaration(s) from Sea Trade International';

                                    //DATI MAIL
                                    $mailto=$mail_azienda_trovata;
                                    $mailsubject=$oggetto;
                                }
                                
                                
                                if($stato=='inviato')
                                {

                                    $mail=array();
                                    $mailto=  str_replace("|;|", ";", $mailto);
                                    $mail['mailto']=$mailto;//"a.galli@about-x.com";//$mail_azienda_trovata;
                                    $mail['mailcc']='';
                                    $mail['mailbcc']='jdocweb.seatrade@gmail.com;docs@seatradeinternational.ch';
                                    $mail['mailsubject']=$mailsubject;
                                    $mail['mailbody']=$mailbody;


                                    //$path=  str_replace('\\\\', "/", $path);
                                    $mail['linkedrecordid']=$recordid;
                                    $mail['mailattachment']=$fullpath_archive;
                                    $this->push_mail_queue($mail);
                                }
                                else
                                {

                                    $problema=  $this->get_lookup_table_item_description('stato_documenti', $stato);
                                    $mail=array();
                                    $mail['mailto']='docs@seatradeinternational.ch';
                                    $mail['mailcc']='a.galli@about-x.com';
                                    $mail['mailbcc']='';
                                    $mail['mailsubject']="Problemi: $problema";
                                    $mail['mailbody']="
                                        Problemi con il caricamento di una scansione: $problema.
                                        REF: $ref;
                                    ";
                                    $this->push_mail_queue($mail);
                                }
                                
                                $ocr_doc= str_replace("'","''",$ocr_doc);
                                
                                $insert=$insert.",id,tipodoc,titolo,oggetto,ref,datascan,stato,scansharezoneocr,recordidaziende_";
                                $values=$values.",$id,'$tipo','$titolo','$oggetto','$ref','$datascan','$stato','$ocr_doc',$recordid_azienda_trovata";
                                
                                
                            }
                            if(file_exists("../JDocServer/autobatch/$path/$filename.txt"))
                            {
                                unlink("../JDocServer/autobatch/$path/$filename.txt");
                            }
                                
                            $values=$values.")";
                            $insert=$insert.")";
                            $sql=$insert." ".$values;
                            $this->execute_query($sql);
                        }
                        
                        $path_string="archivi\\\\$tableid\\\\$namefolder\\\\";
                        $sql="INSERT INTO $page_tablename (recordid_,counter_,fileposition_,path_,filename_,extension_,filestatusid_,signed_,deleted_) VALUES ('$recordid',$counter,$counter,'$path_string','$new_filename','$fileext','S','N','N') ";
                        $this->set_logquery('inserimento allegato',$sql);
                        $this->execute_query($sql);

                        $sql="DELETE FROM sys_autobatch_file WHERE fileid=$autobatch_fileid ";
                        $this->execute_query($sql);
                        
                        
                        
                        
                        
                        
                        copy($fullpath_batch,"../JDocServer/trash/autobatch/$autobatchid/$filename.$fileext");
                        if(($this->isnotempty($path))&&($this->isnotempty($filename)))
                        {
                            unlink($fullpath_batch);
                            
                        }    
                    }


                   
    }
    
    public function get_value_from_key($text,$key)
    {
        $return=false;
        $key_pos=strpos($text, $key);
        if($key_pos !== false)
        {
            $key_ocr=substr($text,  $key_pos);
            $key_ocr_arr = explode("|----|", $key_ocr, 2);
            if(count($key_ocr_arr)>0)
            {
                $return = $key_ocr_arr[0]; 
                $return=  str_replace($key.":", "", $return);
            }
        }
        return $return;
    }
    
    public function autobatch_fronteretro($autobatchid,$autobatch_fileid,$autobatch_fileid2,$tableid,$recordid){
        $sql="
                    SELECT sys_autobatch_file.fileid,sys_autobatch_file.filename,sys_autobatch_file.fileext,sys_autobatch_file.description,sys_autobatch_file.fileposition,sys_autobatch.path,sys_autobatch.numfiles,sys_autobatch.id,sys_autobatch_file.creatorid
                    FROM sys_autobatch_file join sys_autobatch on sys_autobatch_file.batchid=sys_autobatch.id
                    WHERE sys_autobatch_file.fileid=$autobatch_fileid
                    ";
                    $result=  $this->select($sql);
                    $path="";
                    $filename1="";
                    $fileext="";
                    if(count($result)==1)
                    {
                      $path=$result[0]['path'];
                      $filename1=$result[0]['filename'];
                      $fileext=$result[0]['fileext'];
                      $batchnumfiles=$result[0]['numfiles'];
                      $batchid=$result[0]['id'];
                      $creatorid=$result[0]['creatorid'];
                    }


                    $fullpath_file1="../JDocServer/autobatch/$path/$filename1.$fileext";
        

         $sql="
                    SELECT sys_autobatch_file.fileid,sys_autobatch_file.filename,sys_autobatch_file.fileext,sys_autobatch_file.description,sys_autobatch_file.fileposition,sys_autobatch.path,sys_autobatch.numfiles,sys_autobatch.id,sys_autobatch_file.creatorid
                    FROM sys_autobatch_file join sys_autobatch on sys_autobatch_file.batchid=sys_autobatch.id
                    WHERE sys_autobatch_file.fileid=$autobatch_fileid2
                    ";
                    $result=  $this->select($sql);
                    $path="";
                    $filename2="";
                    $fileext="";
                    if(count($result)==1)
                    {
                      $path=$result[0]['path'];
                      $filename2=$result[0]['filename'];
                      $fileext=$result[0]['fileext'];
                      $batchnumfiles=$result[0]['numfiles'];
                      $batchid=$result[0]['id'];
                      $creatorid=$result[0]['creatorid'];
                    }


        $fullpath_file2="../JDocServer/autobatch/$path/$filename2.$fileext";
        $merged_filename="".$autobatch_fileid.$autobatch_fileid2;
        
        
        $fullpath_merged="../JDocServer/autobatch/contratti/$merged_filename.pdf";
        $this->merge_pdf($fullpath_file1, $fullpath_file2, $fullpath_merged);
        
        $sql="INSERT INTO sys_autobatch_file (batchid,filename,fileext) VALUES ('contratti','$merged_filename','pdf') ";
        $this->execute_query($sql);
        $sql="SELECT fileid FROM sys_autobatch_file WHERE filename='$merged_filename'";
        $result=  $this->select($sql);
        $merged_fileid=$result[0]['fileid'];
        
        $sql="DELETE FROM sys_autobatch_file WHERE fileid=$autobatch_fileid ";
        $this->execute_query($sql);
        unlink($fullpath_file1);
        
        $sql="DELETE FROM sys_autobatch_file WHERE fileid=$autobatch_fileid2 ";
        $this->execute_query($sql);
        unlink($fullpath_file2);
        
        $this->autobatch_insert_file($autobatchid, $merged_fileid, $tableid, $recordid);
        
        
    }
    

    public function merge_pdf($path1,$path2,$path_output)
    {
        $command='cd ../JDocServices && JDocServices.exe "mergepdf" "'.$path1.'|'.$path2.'" "'.$path_output.'"  ';
        exec($command);
    }
    
    public function create_archive($post)
    {
        $idarchivio=$post['idarchivio'];
        $descrizione=$post['descrizione'];
        $chklookupestesa=$post['chklookupestesa'];
        $now=date("Y-m-d H:i:s");
        $sql="INSERT INTO sys_table (id,description,lastrecordid,lastpageid,totpages,namefolder,numfilesfolder,creationdate) VALUES ('$idarchivio','$descrizione','0','0',0,'000',0,'$now')";
        $this->execute_query($sql);
        if($chklookupestesa=='true'){
            $sql="INSERT INTO sys_table_feature (tableid,featureid,enabled) VALUES ('$idarchivio',16,'Y')";
            $this->execute_query($sql);
        }
        else
        {
           $sql="INSERT INTO sys_table_feature (tableid,featureid,enabled) VALUES ('$idarchivio',16,'N')";
            $this->execute_query($sql); 
        }
        
        $idarchivio=  strtolower($idarchivio);
        $dbdriver=$this->get_dbdriver();
        if($dbdriver=='postgre')
        {
        $sql="
            CREATE TABLE user_$idarchivio
            (
              recordid_ character(32) NOT NULL,
              creatorid_ integer,
              creation_ timestamp without time zone DEFAULT ('now'::text)::timestamp(6) without time zone,
              lastupdaterid_ integer,
              lastupdate_ timestamp without time zone DEFAULT ('now'::text)::timestamp(6) without time zone,
              totpages_ integer,
              firstpagefilename_ character(255),
              recordstatus_ character varying(255),
              deleted_ character(1) NOT NULL DEFAULT 'N'::bpchar,
              CONSTRAINT user_".$idarchivio."_pkey PRIMARY KEY (recordid_),
              CONSTRAINT user_".$idarchivio."_deleted__check CHECK (deleted_ = 'Y'::bpchar OR deleted_ = 'N'::bpchar)
            )
            WITH (
              OIDS=TRUE
            );
            ALTER TABLE user_".$idarchivio."
              OWNER TO \"JDoc\"

                        ";
        }
        /*off_tipofile character varying(255),
              off_originale character varying(255),
              off_utente character varying(100),
              off_titolo character varying(255),
              off_oggetto character varying(255),
              off_mittente character varying(255),
              off_destinatario character varying(255),
              off_data timestamp without time zone DEFAULT now(),
              off_testo text,*/
        if($dbdriver=='mysqli')
        {
            $sql="
            CREATE TABLE `user_$idarchivio` (
                `recordid_` char(32) NOT NULL,
                `creatorid_` int(11) DEFAULT NULL,
                `creation_` datetime DEFAULT NULL,
                `lastupdaterid_` int(11) DEFAULT NULL,
                `lastupdate_` datetime DEFAULT NULL,
                `totpages_` int(11) DEFAULT NULL,
                `firstpagefilename_` varchar(255) DEFAULT NULL,
                `recordstatus_` varchar(255) DEFAULT NULL,
                `deleted_` char(1) NOT NULL DEFAULT 'N',
                
                PRIMARY KEY (`recordid_`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ";
        }
        /*`off_tipofile` varchar(255),
                `off_originale` varchar(255),
                `off_utente` varchar(100),
                `off_titolo` varchar(255),
                `off_oggetto` varchar(255),
                `off_mittente` varchar(255),
                `off_destinatario` varchar(255),
                `off_data` datetime DEFAULT NULL,
                `off_testo` text,*/
        $this->execute_query($sql);
        
        if($dbdriver=='postgre')
        {
        $sql="
            CREATE TABLE user_".$idarchivio."_owner
            (
              recordid_ character varying(32) NOT NULL,
              ownerid_ integer NOT NULL,
              deleted_ character(1) NOT NULL DEFAULT 'N'::bpchar,
              group_ character(1) NOT NULL DEFAULT 'N'::bpchar,
              lastupdate_ timestamp without time zone DEFAULT ('now'::text)::timestamp(6) without time zone,
              CONSTRAINT user_".$idarchivio."_owner_deleted__check CHECK (deleted_ = 'Y'::bpchar OR deleted_ = 'N'::bpchar)
            )
            WITH (
              OIDS=TRUE
            );
            ALTER TABLE user_".$idarchivio."_owner
              OWNER TO \"JDoc\";
            ";
        }
        if($dbdriver=='mysqli')
        {
           $sql="
            CREATE TABLE `user_".$idarchivio."_owner` (
                `recordid_` varchar(32) NOT NULL,
                `ownerid_` int(11) NOT NULL,
                `deleted_` char(1) NOT NULL DEFAULT 'N',
                `group_` char(1) NOT NULL DEFAULT 'N',
                `lastupdate_` datetime DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            "; 
        }
        
        $this->execute_query($sql);
        
        if($dbdriver=='postgre')
        {
        $sql="
            CREATE TABLE user_".$idarchivio."_page
        (
          recordid_ character varying(32) NOT NULL,
          creatorid_ integer,
          creation_ timestamp without time zone DEFAULT ('now'::text)::timestamp(6) without time zone,
          lastupdaterid_ integer,
          lastupdate_ timestamp without time zone DEFAULT ('now'::text)::timestamp(6) without time zone,
          recordstatus_ character(255),
          counter_ integer NOT NULL,
          fileposition_ integer,
          path_ character varying(255),
          filename_ character varying(255),
          extension_ character varying(6),
          ocr_ text,
          mediaid_ character varying(32),
          filestatusid_ character(1) DEFAULT 'S'::bpchar,
          deleted_ character(1) NOT NULL DEFAULT 'N'::bpchar,
          signed_ character(1) NOT NULL DEFAULT 'N'::bpchar,
          thumbnail character varying(255),
          original_name character varying(255),
          category character varying(255),
          CONSTRAINT user_".$idarchivio."_page_deleted__check CHECK (deleted_ = 'Y'::bpchar OR deleted_ = 'N'::bpchar),
          CONSTRAINT user_".$idarchivio."_page_signed__check CHECK (signed_ = 'Y'::bpchar OR signed_ = 'N'::bpchar)
        )
        WITH (
          OIDS=TRUE
        );
        ALTER TABLE user_".$idarchivio."_page
          OWNER TO \"JDoc\";
            ";
        }
        
        if($dbdriver=='mysqli')
        {
        $sql="
            CREATE TABLE `user_".$idarchivio."_page` (
                `recordid_` varchar(32) NOT NULL,
                `creatorid_` int(11) DEFAULT NULL,
                `creation_` datetime DEFAULT NULL,
                `lastupdaterid_` int(11) DEFAULT NULL,
                `lastupdate_` datetime DEFAULT NULL,
                `recordstatus_` char(255) DEFAULT NULL,
                `counter_` int(11) NOT NULL,
                `fileposition_` int(11) DEFAULT NULL,
                `path_` varchar(255) DEFAULT NULL,
                `filename_` varchar(255) DEFAULT NULL,
                `extension_` varchar(6) DEFAULT NULL,
                `ocr_` longtext,
                `mediaid_` varchar(32) DEFAULT NULL,
                `filestatusid_` char(1) DEFAULT 'S',
                `deleted_` char(1) NOT NULL DEFAULT 'N',
                `signed_` char(1) NOT NULL DEFAULT 'N',
                `thumbnail` varchar(255) DEFAULT NULL,
                `original_name` varchar(255) DEFAULT NULL,
                `category` varchar(255) DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

            ";
        }
        
        $this->execute_query($sql);
    }
    
    
    /**
     * 
     * @param type $userid
     * @param type $tableid
     * @param type $fieldid
     * @param type $fieldorder
     * @param type $typepreference
     * @author Luca Giordano
     */
    function delete_preferences($userid,$typepreference,$idarchivio)
    {
        $sql="  DELETE FROM sys_user_order
                WHERE userid=$userid 
                AND typepreference='$typepreference' AND tableid='$idarchivio'";
        $this->execute_query($sql);
    }
    
    /**
     * 
     * @param type $userid idutente
     * @param type $tableid nome archivio
     * @param type $fieldid nome campo
     * @param type $fieldorder ordine posizione
     * @author Luca Giordano
     */
    function set_preferences($userid,$tableid,$fieldid,$fieldorder,$typepreference)
    {
        $sql="INSERT INTO sys_user_order (userid,tableid,fieldid,fieldorder,typepreference)
               VALUES ('".$userid."','$tableid','$fieldid',".$fieldorder.",'$typepreference')";
        $this->execute_query($sql);
    }
    
    
    //fine impostazioni
    
    
        
    function get_records($tableid,$query,$order_key='',$order_ascdesc='',$offset_number='0',$limit_number='50')
    {
        $userid= $this->get_userid();
        $columns=  $this->get_colums($tableid, $userid);
        $table_settings= $this->get_table_settings($tableid);
        
        if($order_ascdesc=='')
        {
           $order_ascdesc=$table_settings['risultati_order']; 
        }    
        if($order_key=='')
        {
            $order_key=$columns[3]['id'];
        }
        $cliente_id= $this->get_cliente_id();
        if(($cliente_id=='3p')&&($tableid=='presenzemensili'))
        {
            //$order_key='mese,cognome,nome';
            $order_key='anno,mese,no';
        }
        $order="";
        $limit="";
        $order='ORDER BY '.$order_key;
        $limit='LIMIT '.$limit_number;
        $offset="OFFSET $offset_number";
        $sql=$query." $order $order_ascdesc $limit $offset ";
        $records=  $this->select($sql);
        $records=  $this->convert_fields_value_to_final_value($tableid,$columns, $records);
        $records=$this->check_alert_recordcss($tableid,$records);
        return $records;
    }
    
    function get_records_total_count($query)
    {
        $sql=$query;
        $records=  $this->select($sql);
        return count($records);
    }      
    
    function get_search_result($tableid,$query,$order_key='',$order_ascdesc='',$limit_number='')
    {
        $order="";
        $limit="";
        if($order_key!='')
        {
            $order='ORDER BY '.$order_key;
        }
        if($limit_number!='')
        {
            $limit='LIMIT '.$limit_number;
        }
        $sql=$query." $order $order_ascdesc $limit ";
        $result=  $this->select($sql);
        foreach ($result as $key => $result_row) {
                   $output_row=array();
                   //CUSTOM WORK&WORK 
                   if($tableid=='CANDIDtemp')
                   {
                       // TEMP
                   //$result[$key]['qualifica']=  $this->custom_generate_qualifica($result_row['recordid_']);
                   //$result[$key]['datanasc']=$this->custom_generate_eta($result_row['datanasc']);
                   
                   }
                   if($tableid=='AZIEND')
                   {
                   $result[$key]['settore']=  $this->custom_generate_settore($result_row['recordid_']);
                   }

                   //$output['aaData'][]=$output_row;
               }
        return $result;
    }
    
    
     public function get_all_columns($idarchivio)
    {
        $sql="
            SELECT *
            FROM sys_field
            WHERE tableid='$idarchivio' AND ((tablelink IS NULL) OR (keyfieldlink IS NOT NULL)) AND label!='Old'
            ORDER BY description    
            ";
        $all_columns =  $this->select($sql);
        $return_columns=array();
        
        $colums=array();

       /* $column['id']='recordid_';
        $column['desc']='recordid_';
        $column['fieldtypeid']='Sys';
        $colums[]=$column;
        $column['id']='recordstatus_';
        $column['desc']='recordstatus_';
        $column['fieldtypeid']='Sys';
        $colums[]=$column;*/
        foreach ($all_columns as $key => $preference_column) {
            $column=$preference_column;
            if(($preference_column['tablelink']!='')&&($preference_column['tablelink']!=null))
            {
                $column['id']="recordid".strtolower($preference_column['tablelink'])."_";
                $column['fieldtypeid']='linkedmaster';
                $column['linkedtableid']=$preference_column['tablelink'];
                $column['fieldid']="recordid".strtolower($preference_column['tablelink'])."_";
            }
            else
            {
                $column['id']=$preference_column['fieldid'];
                $column['fieldtypeid']=$preference_column['fieldtypeid'];
                $column['linkedtableid']='';
            }
            $column['desc']=$preference_column['description'];
            
            $colums[]=$column;
        }
        
       /* $column['id']='creatorid_';
        $column['fieldtypeid']='sys_Utente';
        $column['desc']='Creato da';
        $colums[]=$column;*/
        /*
        if($idarchivio=='CANDID')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='statodisp';
            $column['desc']='Dis';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='wwws';
            $column['desc']='wwws';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='validato';
            $column['desc']='Valid';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='pflash';
            $column['desc']='pflash';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='cognome';
            $column['desc']='Cognome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='nome';
            $column['desc']='Nome';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='qualifica';
            $column['desc']='Qualifica';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='giudizio';
            $column['desc']='Giud';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Con';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='datanasc';
            $column['desc']='Età';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
        }
        if($idarchivio=='AZIEND')
        {
            $colums=array();
            $column['id']='recordid_';
            $column['desc']='recordid_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='recordstatus_';
            $column['desc']='recordstatus_';
            $column['fieldtypeid']='Sys';
            $colums[]=$column;
            $column['id']='id';
            $column['desc']='ID';
            $column['fieldtypeid']='Numero';
            $colums[]=$column;
            $column['id']='ragsoc';
            $column['desc']='Ragione Sociale';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='distretto';
            $column['desc']='Distretto';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='settore';
            $column['desc']='settore';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='aziendastato';
            $column['desc']='Azienda Stato';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            $column['id']='consulente';
            $column['desc']='Consulente';
            $column['fieldtypeid']='Parola';
            $colums[]=$column;
            
        }*/
        foreach ($colums as $key => $column) {
            $return_columns[$column['fieldid']]=$column;
        }
        return $return_columns;
    }
    
    
    //CUSTOM WORK&WORK
    public function get_contratti_stampare()
    {
        //CUSTOM POSTGRES
        $sql="SELECT recordid_ from user_contra WHERE idazienda is null AND idcandid is null";
        $result=  $this->select($sql);
        return $result;
        
    }
    
    
    public function get_dati_stampa_LetteraInvioContratti_candidati($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Dom'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        if(!$dati_recapito)
        {
            $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Res'";
            $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        }
        $dati['cognome']="";
        $dati['cognome']=$dati_candid['cognome'];
        $dati['nome']="";
        $dati['nome']=$dati_candid['nome'];
        $dati['apertura']="";
        if(array_key_exists("sesso", $dati_candid))
        {
            $sesso=$dati_candid['sesso'];
            if(strtolower($sesso)=='m')
            {
                $dati['apertura']="Egregio signor";
            }
            else
            {
                $dati['apertura']="Gentile signora";
            }
        }
        $dati['candidnpa']=$dati_recapito['npa'];
        $dati['candidcitta']=$dati_recapito['citta'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        return $dati;
    }
    
    public function get_dati_stampa_LetteraInvioContratti_aziende($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        
        $condizioni_riferimento[]="numriferimento=1";
        $condizioni_riferimento[]="recordidaziend_='$recordid_aziend'";
        $dati_riferimento=  $this->get_dati_firsrecord_bycondition("azriferimenti", $condizioni_riferimento);
        $dati['ragsoc']=$dati_aziend['ragsoc'];
        $dati['titrif']=$dati_riferimento['titolorif'];
        $dati['azcognome']=$dati_riferimento['cognomerif'];
        $dati['aznome']=$dati_riferimento['nomerif'];
        $condizioni_recapito[]="recordidaziend_='$recordid_aziend' AND tiporecapito_='Principale'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("azrecapiti", $condizioni_recapito);
        $dati['indirizzo']=$dati_recapito['indirizzo'];
        $dati['aziendnpa']=$dati_recapito['npa'];
        $dati['aziendcitta']=$dati_recapito['citta'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        $dati['formcort']=$dati_riferimento['fcortesia_'];
        return $dati;
    }
    
    public function get_dati_stampa_ContrattoFornituraPersonalePrestito_WW($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        $condizioni_recapito[]="recordidaziend_='$recordid_aziend' AND tiporecapito_='Principale'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("azrecapiti", $condizioni_recapito);
        $dati['riferimen']=$dati_contratto['riferimen'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        $dati['ragsoc']=$dati_aziend['ragsoc'];
        $dati['indirizzo']=$dati_recapito['indirizzo'];
        $dati['aziendnpa']=$dati_recapito['npa'];
        $dati['aziendcitta']=$dati_recapito['citta'];
        $dati['contranome']=$dati_candid['nome'];
        $dati['contracognome']=$dati_candid['cognome'];
        $dati['qualprof']=$dati_contratto['qualprof'];
        $dati['funzione']=$dati_contratto['funzione'];
        $dati['luogolav']=$dati_contratto['luogolav'];
        $dati['datainiz']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datainiz'])));
        $dati['ccl']=$dati_contratto['ccl_'];
        $dati['orariolav']=$dati_contratto['orariolav'];
        $dati['prezzoon']=$dati_contratto['prezzoon'];
        $dati['terminipagamento']=$dati_contratto['terminipagamento'];
        return $dati;
    }
    
    public function get_dati_stampa_ContrattoFornituraPersonalePrestito_Azienda($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        $condizioni_recapito[]="recordidaziend_='$recordid_aziend' AND tiporecapito_='Principale'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("azrecapiti", $condizioni_recapito);
        $dati['riferimen']=$dati_contratto['riferimen'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        $dati['ragsoc']=$dati_aziend['ragsoc'];
        $dati['indirizzo']=$dati_recapito['indirizzo'];
        $dati['aziendnpa']=$dati_recapito['npa'];
        $dati['aziendcitta']=$dati_recapito['citta'];
        $dati['contranome']=$dati_candid['nome'];
        $dati['contracognome']=$dati_candid['cognome'];
        $dati['qualprof']=$dati_contratto['qualprof'];
        $dati['funzione']=$dati_contratto['funzione'];
        $dati['luogolav']=$dati_contratto['luogolav'];
        $dati['datainiz']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datainiz'])));
        $dati['ccl']=$dati_contratto['ccl_'];
        $dati['orariolav']=$dati_contratto['orariolav'];
        $dati['prezzoon']=$dati_contratto['prezzoon'];
        $dati['terminipagamento']=$dati_contratto['terminipagamento'];
        return $dati;
    }
    
    public function get_dati_stampa_ContrattoAssunzionePersonalePrestito_WW($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Dom'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        if(!$dati_recapito)
        {
            $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Res'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        }
        $dati['riferimen']=$dati_contratto['riferimen'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        $dati['contracognome']=$dati_candid['cognome'];
        $dati['contranome']=$dati_contratto['nome'];
        $dati['domicilio']=$dati_recapito['via'];
        $dati['candidnpa']=$dati_recapito['npa'];
        $dati['candidcitta']=$dati_recapito['citta'];
        $dati['azienda']=$dati_contratto['ragsoc'];
        $dati['funzione']=$dati_contratto['funzione'];
        $dati['luogolav']=$dati_contratto['luogolav'];
        
        if($dati_contratto['datainiz']!=null)
        {
            $dati['datainiz']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datainiz']))); 
        }
        else
        {
            $dati['datainiz']='';
        }
        
        if($dati_contratto['detindet']=='indeterminato')
        {
            $dati['datafin']='Contratto indeterminato';   
        }
        else
        {
            if($dati_contratto['datafin']!=null)
            {
                $dati['datafin']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datafin'])));
            }
            else
            {
                $dati['datafin']='';
            }
            
        }
        
        if($dati_contratto['duratalav']!=null)
        {
            $dati['duratalav']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['duratalav'])));
        }
        else
        {
            $dati['duratalav']='';
        }
        
        if($dati_contratto['provafinoa']!=null)
        {
            $dati['periodoprova']='Fino al '.date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['provafinoa'])));
        }
        else
        {
            $dati['periodoprova']='No';
        }
        
        $dati['orariolav']=$dati_contratto['orariolav'];
        $dati['perclavorativa']=$dati_contratto['perclavorativa'];
        $dati['retriboraria']=$dati_contratto['retriboraria'];
        $dati['indennita']=$dati_contratto['indennita'];
        $dati['indennitapranzo']=$dati_contratto['indennitapranzo'];
        $dati['percferie']=$dati_contratto['percferie'];
        $dati['ferie']=$dati_contratto['ferie'];//$dati_contratto['retriboraria']/100*$dati_contratto['percferie'];
        $dati['percindennita']=$dati_contratto['percindennita'];
        $dati['indennita']=$dati_contratto['indennita'];//($dati_contratto['retriboraria']+$dati['ferie'])/100*$dati_contratto['percindennita'];
        $dati['perc13esima']=$dati_contratto['perc13esima'];
        $dati['13esima']=$dati_contratto['tredicesima'];//($dati_contratto['retriboraria']+$dati['ferie']+$dati['indennita'])/100*$dati_contratto['perc13esima'];
        $dati['salario']=$dati_contratto['salario'];
        $dati['assegnifamiliari']=$dati_contratto['assegnifamiliari']; 
        return $dati;
    }
    
    public function get_dati_stampa_ContrattoAssunzionePersonalePrestito_Dipendente($recordid_contratto)
    {
        $dati_contratto=$this->get_dati_record("contra", $recordid_contratto);
        $recordid_aziend=$dati_contratto['recordidaziend_'];
        $recordid_candid=$dati_contratto['recordidcandid_'];
        $dati_candid=$this->get_dati_record("candid", $recordid_candid);
        $dati_aziend=$this->get_dati_record("aziend", $recordid_aziend);
        $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Dom'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        if(!$dati_recapito)
        {
            $condizioni_recapito[]="recordidcandid_='$recordid_candid' AND tipo='Res'";
        $dati_recapito=  $this->get_dati_firsrecord_bycondition("candrecapiti", $condizioni_recapito);
        }
        $dati['riferimen']=$dati_contratto['riferimen'];
        $dati['datacontr']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datacontr'])));
        $dati['contracognome']=$dati_candid['cognome'];
        $dati['contranome']=$dati_contratto['nome'];
        $dati['domicilio']=$dati_recapito['via'];
        $dati['candidnpa']=$dati_recapito['npa'];
        $dati['candidcitta']=$dati_recapito['citta'];
        $dati['azienda']=$dati_contratto['ragsoc'];
        $dati['funzione']=$dati_contratto['funzione'];
        $dati['luogolav']=$dati_contratto['luogolav'];
        if($dati_contratto['datainiz']!=null)
        {
            $dati['datainiz']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datainiz']))); 
        }
        else
        {
            $dati['datainiz']='';
        }
        
        if($dati_contratto['detindet']=='indeterminato')
        {
            $dati['datafin']='Contratto indeterminato';   
        }
        else
        {
            if($dati_contratto['datafin']!=null)
            {
                $dati['datafin']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['datafin'])));
            }
            else
            {
                $dati['datafin']='';
            }
            
        }
        
        if($dati_contratto['duratalav']!=null)
        {
            $dati['duratalav']=date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['duratalav'])));
        }
        else
        {
            $dati['duratalav']='';
        }
        
        if($dati_contratto['provafinoa']!=null)
        {
            $dati['periodoprova']='Fino al '.date('d/m/Y',  strtotime(str_replace('/', '-', $dati_contratto['provafinoa'])));
        }
        else
        {
            $dati['periodoprova']='No';
        }
        $dati['orariolav']=$dati_contratto['orariolav'];
        $dati['perclavorativa']=$dati_contratto['perclavorativa'];
        $dati['retriboraria']=$dati_contratto['retriboraria'];
        $dati['indennita']=$dati_contratto['indennita'];
        $dati['indennitapranzo']=$dati_contratto['indennitapranzo'];
        $dati['percferie']=$dati_contratto['percferie'];
        $dati['ferie']=$dati_contratto['ferie'];//$dati_contratto['retriboraria']/100*$dati_contratto['percferie'];
        $dati['percindennita']=$dati_contratto['percindennita'];
        $dati['indennita']=$dati_contratto['indennita'];//($dati_contratto['retriboraria']+$dati['ferie'])/100*$dati_contratto['percindennita'];
        $dati['perc13esima']=$dati_contratto['perc13esima'];
        $dati['13esima']=$dati_contratto['tredicesima'];//($dati_contratto['retriboraria']+$dati['ferie']+$dati['indennita'])/100*$dati_contratto['perc13esima'];
        $dati['salario']=$dati_contratto['salario'];
        $dati['assegnifamiliari']=$dati_contratto['assegnifamiliari']; 
        return $dati;
    }
    
    
    public function get_dati_stampa_bollettino_assistenzakeysky($recordid)
    {
        $dati['numrapporto']='';
        $dati['data']='';
        $dati['ragsoc']='';
        $dati['indirizzo']='';
        $dati['nazione']='';
        $dati['tipointervento']='';
        $dati['tipoassistenza']='';
        $dati['noteintervento']='';
        $dati['tecnico']='';
        $dati['orainizio']='';
        $dati['orafine']='';
        $dati['totale']="dev";
        $dati['istruzioniintervento']='';
        $dati['costoorario']='';
        $dati['dirittochiamata']='';
        $dati['noteaggiuntive']='';
        $valuta='';
        $rows_assistenzekeysky=$this->select("SELECT * FROM user_assistenzekeysky where recordid_='$recordid'");
        if(count($rows_assistenzekeysky)==1)
        {
            $row0_assistenzekeysky=$rows_assistenzekeysky[0];
            if(array_key_exists('recordidaziende_', $row0_assistenzekeysky))
            {
                $recordid_azienda=$row0_assistenzekeysky['recordidaziende_'];
                $rows_aziende=$this->select("SELECT * FROM user_aziende where recordid_='$recordid_azienda'");
                if(count($rows_aziende)==1)
                {
                    $row0_aziende=$rows_aziende[0];
                    $dati['ragsoc']=$row0_aziende['ragionesociale'];
                    $dati['indirizzo']=$row0_aziende['indirizzo'];
                    $nazione=$row0_aziende['nazione'];
                    if($nazione=='italia')
                    {
                        $dati['nazione']='ITALIA';
                        $valuta='€';
                    }
                    if($nazione=='svizzera')
                    {
                        $dati['nazione']='SVIZZERA';
                        $valuta='CHF';
                    }
                }
            }
            $dati['numrapporto']=$row0_assistenzekeysky['id'];
            $dati['data']=date('d/m/Y',  strtotime($row0_assistenzekeysky['data']));
            $tipointervento=$row0_assistenzekeysky['tipointervento'];
            if($tipointervento=='ordinario')
            {
                $dati['tipointervento']='Ordinario (programmato deciso con il cliente)';
                $dati['istruzioniintervento']="Intervento ordinario (dalle 8,00 alle 18,00)";
                $dati['costoorario']="$valuta 120,00";
            }
            if($tipointervento=='urgenza')
            {
                $dati['tipointervento']='Urgenza (entro le 24/48 ore dal ricevimento della richiesta)';
                $dati['istruzioniintervento']="Intervento urgente (dalle 8,00 alle 18,00)";
                $dati['costoorario']="$valuta 180,00";
            }
            if($tipointervento=='straordinario')
            {
                $dati['tipointervento']='Straordinario (sabato, festivi e notturni)';
                $dati['istruzioniintervento']="Intervento straordinario (sabato, festivi e notturni) ";
                $dati['costoorario']="$valuta 250,00";
            }
            if($tipointervento=='contratto')
            {
                $dati['tipointervento']='Contratto di assistenza / Monte ore a scalare ';
                $dati['istruzioniintervento']="Intervento compreso nel contratto di assistenza / Monte ore a scalare";
                $dati['costoorario']="";
            }
            $tipoassistenza=$row0_assistenzekeysky['tipoassistenza'];
            if($tipoassistenza=='onsite')
            {
                $dati['tipoassistenza']='Assistenza On site';
            }
            if($tipoassistenza=='remoto')
            {
                $dati['tipoassistenza']='Assistenza da remoto';
            }
            if($tipoassistenza=='telefonica')
            {
                $dati['tipoassistenza']='Assistenza telefonica';
            }
            $dati['noteintervento']=$row0_assistenzekeysky['noteintervento'];
            $dati['tecnico']=$this->get_user_nomecognome($row0_assistenzekeysky['tecnico']);
            $orainizio=$row0_assistenzekeysky['orainizio'];
            $orafine=$row0_assistenzekeysky['orafine'];
            $dati['orainizio']=$orainizio;
            $dati['orafine']=$orafine;
            $durata=  date_diff(date_create($orafine), date_create($orainizio));
            $dati['durata']=$durata->format('%H:%i');
            if($row0_assistenzekeysky['dirittochiamata']=='chf80')
            {
                $dati['dirittochiamata']='CHF 80,00 i.e.';
            }
            if($row0_assistenzekeysky['dirittochiamata']=='chilometrico')
            {
                $dati['dirittochiamata']='Rimborso chilometrico';
            }
            $dati['dirittochiamata']=$row0_assistenzekeysky['dirittochiamata'];
            $dati['noteaggiuntive']=$row0_assistenzekeysky['noteaggiuntive'];
        }
        return $dati;
    }
    
    public function get_dati_stampa_prospetto($recordid)
    {
        //$row=  $this->db_get_row('user_immobili', '*', "recordid_='$recordid'");
        $fields=$this->Sys_model->get_fields_table('immobili','Dati',$recordid,'inserimento','master');
        return $fields;
    }
    
    public function get_dati_stampa_profilo($recordid)
    {
        $dati=array();
        
        $dati['userid']= $this->session->userdata('userid');
        $rows_candid=$this->select("SELECT * FROM user_candid where recordid_='$recordid'");
        $row0_candid=$rows_candid[0];
        
        $rows_recapiti=$this->select("SELECT * FROM user_candrecapiti WHERE recordidcandid_='$recordid' AND tipo='Dom'  ORDER BY lastupdate_ DESC");
        if(count($rows_recapiti)==0)
        {
            $rows_recapiti=$this->select("SELECT * FROM user_candrecapiti WHERE recordidcandid_='$recordid' AND tipo='Res'  ORDER BY lastupdate_ DESC");
        }   
        if(count($rows_recapiti)>0)
            $row0_recapiti=$rows_recapiti[0];  
        else
            $row0_recapiti=array();
        
        $rows_colloquio=$this->select("SELECT * FROM user_candcolloquio where recordidcandid_='$recordid' ORDER BY lastupdate_ DESC");
        if(count($rows_colloquio)>0)
            $row0_colloquio=$rows_colloquio[0];
        else
            $row0_colloquio=array();
        
        $rows_disponibilita=$this->select("SELECT * FROM user_canddisponibilita where recordidcandid_='$recordid' ORDER BY lastupdate_ DESC");
        if(count($rows_disponibilita)>0)
            $row0_disponibilita=$rows_disponibilita[0];
        else
            $row0_disponibilita=array();
        
        $rows_lingue=$this->select("SELECT * FROM user_candlingue where recordidcandid_='$recordid' ORDER BY lastupdate_ DESC");
        
        $rows_formazione=$this->select("SELECT * FROM user_candformazione where recordidcandid_='$recordid' ORDER BY anno DESC");

        $rows_esperienzeprof=$this->select("SELECT * FROM user_candpr where recordidcandid_='$recordid' ORDER BY dallanno DESC");

        $rows_competenze=$this->select("SELECT * FROM user_SKILL where recordidcandid_='$recordid' ORDER BY lastupdate_ DESC");
     
        $rows_competenzeit=$this->select("SELECT * FROM user_competenzeit where recordidcandid_='$recordid' ORDER BY lastupdate_ DESC");

        //PREIMPOSTAZIONE
        
        //INTESTAZIONE
        $dati['intestazione']=array('idDossier'=>'','cognome'=>'','nome'=>'','qualifica'=>'','data'=>'');
        //DATIANAGRAFICI
        $dati['datianagrafici']=array('annonascita'=>'','sesso'=>'','nazionalita'=>'','statocivile'=>'','patente'=>'','auto'=>'','permesso'=>'','domicilio'=>'','numfigli'=>'','annonascitafigli'=>'');
        //AREA PERSONALE
        $dati['areapersonale']=array('presenza'=>'','corporatura'=>'','abbigliamento'=>'','proprietalinguaggio'=>'','indole'=>'');
        //AREA PROFESSIONALE
        $dati['areaprofessionale']=array('motivazionicambiamento'=>'','motivazioniposizione'=>'','aspettative'=>'','flexorario'=>'','flexsalario'=>'');
        //TERMINI
        $dati['termini']=array('disponibilita'=>'','giornidipreavviso'=>'','parametrosalariale'=>'','salariodesiderato'=>'');
        //FORMAZIONE
        
        
        //IMPOSTAZIONE CAMPI
        
        //INTESTAZIONE
        if(count($row0_candid)>0)
            //CUSTOM POSTGRES
            $qualifica=$this->custom_generate_qualifica($recordid);;
            $dati['intestazione']=array('idDossier'=>$row0_candid['id'],'cognome'=>$row0_candid['cognome'],'nome'=>iconv('UTF-8', 'windows-1252', $row0_candid['nome']),'qualifica'=>iconv('UTF-8', 'windows-1252', $qualifica),'data'=>  date("d.m.Y"));
        //DATIANAGRAFICI
        if(count($row0_candid)>0)
        {
             //CUSTOM POSTGRES
            if($row0_candid['datanasc']!='')
            {
                $dati['datianagrafici']['annonascita']=date('Y',  strtotime(str_replace('/', '-', $row0_candid['datanasc'])));
            }
            else
            {
                $dati['datianagrafici']['annonascita']='';
            }
            $dati['datianagrafici']['sesso']=  $this->get_descrizione_lookup('CANDID', 'SESSO', $row0_candid['sesso']);
            $dati['datianagrafici']['nazionalita']=iconv('UTF-8', 'windows-1252', $this->get_descrizione_lookup('CANDID', 'NAZIONASC',$row0_candid['nazionasc']));
            $dati['datianagrafici']['statocivile']=$this->get_descrizione_lookup('CANDID', 'STATOCIV',$row0_candid['statociv']);
            $dati['datianagrafici']['permesso']=$row0_candid['tipoperm'];
            $dati['datianagrafici']['numfigli']=$row0_candid['figli'];
            $dati['datianagrafici']['annonascitafigli']=$row0_candid['etafigli'];
        }     
        if(count($row0_disponibilita)>0)
        {
            $dati['datianagrafici']['patente']=$row0_disponibilita['patente'];
            $dati['datianagrafici']['auto']=$row0_disponibilita['mtrasp_'];
        }
        if(count($row0_recapiti)>0)
        {
            $dati['datianagrafici']['domicilio']=iconv('UTF-8', 'windows-1252', $row0_recapiti['citta']).', '.iconv('UTF-8', 'windows-1252', $row0_recapiti['nazione']);
        }
        //AREA PERSONALE
        if(count($row0_colloquio)>0)
            $dati['areapersonale']=array('presenza'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['presenza']),'corporatura'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['corporatura']),'abbigliamento'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['abbigliamento']),'proprietalinguaggio'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['proprietalinguaggio']),'indole'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['indolecarattere']));
        //AREA PROFESSIONALE
        if(count($row0_colloquio)>0)
            $dati['areaprofessionale']=array('motivazionicambiamento'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['motivialcambiamento']),'motivazioniposizione'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['motivazionilp']),'aspettative'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['aspettative']),'flexorario'=>$row0_colloquio['flessibilitaorari'],'flexsalario'=>$row0_colloquio['flessibilitasalario']);
        //TERMINI
        if(count($row0_disponibilita)>0)
            $dati['termini']=array('disponibilita'=>$row0_disponibilita['statodisp_'],'giornidipreavviso'=>$row0_disponibilita['giornidipreavviso'],'parametrosalariale'=>$row0_disponibilita['salattlordo'],'salariodesiderato'=>$row0_disponibilita['saldeslordo']);
        //FORMAZIONE
        $dati['formazione']=array();
        foreach ($rows_formazione as $row_formazione) {
           $dati['formazione'][]=array('anno'=>$row_formazione['anno'],'titolo'=>iconv('UTF-8', 'windows-1252', $row_formazione['titolo']),'corso'=>iconv('UTF-8', 'windows-1252', $row_formazione['corso']),'istituto'=>iconv('UTF-8', 'windows-1252', $row_formazione['istituto']),'luogo'=>iconv('UTF-8', 'windows-1252', $row_formazione['luogo'])); 
        }
        //LINGUE
         $dati['lingue']=array();
        foreach ($rows_lingue as $row_lingua) {
            $dati['lingue'][]=array('lingua'=>$this->get_descrizione_lookup('candlingue', 'lingua', $row_lingua['lingua']),'diplomiconseguiti'=>$row_lingua['note'],'conversazione'=>$this->get_descrizione_lookup('candlingue', 'parlato', $row_lingua['parlato']),'scrittura'=>$this->get_descrizione_lookup('candlingue', 'scritto', $row_lingua['scritto']),'lettura'=>$this->get_descrizione_lookup('candlingue', 'letto', $row_lingua['letto']));
        }
        //ESPERIENZE PROFESSIONALI
        $dati['esperienzeprofessionali']=array();
        foreach ($rows_esperienzeprof as $row_esperienzeprof) {
            $dati['esperienzeprofessionali'][]=array('dal'=>$row_esperienzeprof['dallanno'],'al'=>$row_esperienzeprof['allanno'],'qualifica'=>$row_esperienzeprof['mansioni'],'ragionesociale'=>$row_esperienzeprof['datorelav'],'luogo'=>$row_esperienzeprof['luogo'],'settore'=>$row_esperienzeprof['ramoattiv'],'Ndipendenti'=>$row_esperienzeprof['ndipendenti'],'subordinatoa'=>$row_esperienzeprof['subord'],'responsabiledi'=>$row_esperienzeprof['responsabiledi'],'competenze'=>$row_esperienzeprof['princmansioni'],'carriera'=>$row_esperienzeprof['carriera'],'referenze'=>$row_esperienzeprof['referenze'],'causatermine'=>$row_esperienzeprof['causainterruzione'],'lingueutilizzate'=>$row_esperienzeprof['lingueutilizzate']);    
        }
        
        //COMPETENZE
        $dati['competenze']=array();
        foreach ($rows_competenze as $row_competenze) {
        $dati['competenze'][]=array('area'=>@iconv('UTF-8', 'windows-1252//IGNORE', $row_competenze['settore'].' '.$row_competenze['subsettor']),'livelloesperienza'=>$row_competenze['esperienza_'],'elencocompetenze'=>html_entity_decode(@iconv('UTF-8', "windows-1252//IGNORE",$row_competenze['competenza'])));
        }
        
        $dati['competenzeit']=array();
        //COMPETENZE IT
        foreach ($rows_competenzeit as $row_competenzeit) {
            $dati['competenzeit'][]=array('software'=>iconv('UTF-8', 'windows-1252', $row_competenzeit['software']),'livelloesperienza'=>$row_competenzeit['livello'],'versione'=>$row_competenzeit['versione'],'anno'=>$row_competenzeit['anno']);
        }
        
        $dati['daticliente']=array('ragionesociale'=>'','posizionericercata'=>'','tipologiacontrattuale'=>'','luogolavoro'=>'','retribuzioneannualorda'=>'');
        $dati['analisiprofilo']=array('elementifavore'=>'','elementidiscussione'=>'');
        $dati['giudizio']=array('giudizio'=>'');

        return $dati;
    }
    
    public function get_dati_stampa_profilo_cifrato($recordid)
    {
        $dati=array();
        $dati['userid']= $this->session->userdata('userid');
        $rows_candid=$this->select("SELECT * FROM user_candid where recordid_='$recordid'");
        $row0_candid=$rows_candid[0];
        
        $rows_recapiti=$this->select("SELECT * FROM user_candrecapiti WHERE recordidcandid_='$recordid' AND tipo='Dom'  ORDER BY lastupdate_");
        if(count($rows_recapiti)==0)
        {
            $rows_recapiti=$this->select("SELECT * FROM user_candrecapiti WHERE recordidcandid_='$recordid' AND tipo='Res'  ORDER BY lastupdate_");
        }   
        if(count($rows_recapiti)>0)
            $row0_recapiti=$rows_recapiti[0];  
        else
            $row0_recapiti=array();
        
        $rows_colloquio=$this->select("SELECT * FROM user_candcolloquio where recordidcandid_='$recordid' ORDER BY lastupdate_");
        if(count($rows_colloquio)>0)
            $row0_colloquio=$rows_colloquio[0];
        else
            $row0_colloquio=array();
        
        $rows_disponibilita=$this->select("SELECT * FROM user_canddisponibilita where recordidcandid_='$recordid' ORDER BY lastupdate_");
        if(count($rows_colloquio)>0)
            $row0_disponibilita=$rows_disponibilita[0];
        else
            $row0_disponibilita=array();
        
        $rows_lingue=$this->select("SELECT * FROM user_candlingue where recordidcandid_='$recordid' ORDER BY lastupdate_");
        
        $rows_formazione=$this->select("SELECT * FROM user_candformazione where recordidcandid_='$recordid' ORDER BY anno DESC");

        $rows_esperienzeprof=$this->select("SELECT * FROM user_candpr where recordidcandid_='$recordid' ORDER BY dallanno DESC");

        $rows_competenze=$this->select("SELECT * FROM user_SKILL where recordidcandid_='$recordid' ORDER BY lastupdate_");
     
        $rows_competenzeit=$this->select("SELECT * FROM user_competenzeit where recordidcandid_='$recordid' ORDER BY lastupdate_");

        //PREIMPOSTAZIONE
        
        //INTESTAZIONE
        $dati['intestazione']=array('idDossier'=>'','cognome'=>'XXX','nome'=>'XXX','qualifica'=>'','data'=>'');
        //DATIANAGRAFICI
        $dati['datianagrafici']=array('annonascita'=>'XXX','sesso'=>'XXX','nazionalita'=>'XXX','statocivile'=>'XXX','patente'=>'','auto'=>'','permesso'=>'','domicilio'=>'XXX','numfigli'=>'XXX','annonascitafigli'=>'XXX');
        //AREA PERSONALE
        $dati['areapersonale']=array('presenza'=>'','corporatura'=>'','abbigliamento'=>'','proprietalinguaggio'=>'','indole'=>'');
        //AREA PROFESSIONALE
        $dati['areaprofessionale']=array('motivazionicambiamento'=>'','motivazioniposizione'=>'','aspettative'=>'','flexorario'=>'','flexsalario'=>'');
        //TERMINI
        $dati['termini']=array('disponibilita'=>'','giornidipreavviso'=>'','parametrosalariale'=>'','salariodesiderato'=>'');
        //FORMAZIONE
        
        
        //IMPOSTAZIONE CAMPI
        
        //INTESTAZIONE
        if(count($row0_candid)>0)
            //CUSTOM POSTGRES
            $qualifica=$this->custom_generate_qualifica($recordid);
            $dati['intestazione']=array('idDossier'=>$row0_candid['id'],'cognome'=>'XXX','nome'=>'XXX','qualifica'=>iconv('UTF-8', 'windows-1252', $qualifica),'data'=>  date("d.m.Y"));
        //DATIANAGRAFICI
        if((count($row0_recapiti)>0)&&(count($row0_recapiti)>0))
            $dati['datianagrafici']=array('annonascita'=>'XXX','sesso'=>'XXX','nazionalita'=>'XXX','statocivile'=>'XXX','patente'=>$row0_disponibilita['patente'],'auto'=>$row0_disponibilita['mtrasp_'],'permesso'=>$row0_candid['tipoperm'],'domicilio'=>'XXX','numfigli'=>'XXX','annonascitafigli'=>'XXX');
        //AREA PERSONALE
        if(count($row0_colloquio)>0)
            $dati['areapersonale']=array('presenza'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['presenza']),'corporatura'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['corporatura']),'abbigliamento'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['abbigliamento']),'proprietalinguaggio'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['proprietalinguaggio']),'indole'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['indolecarattere']));
        //AREA PROFESSIONALE
        if(count($row0_colloquio)>0)
            $dati['areaprofessionale']=array('motivazionicambiamento'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['motivialcambiamento']),'motivazioniposizione'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['motivazionilp']),'aspettative'=>iconv('UTF-8', 'windows-1252', $row0_colloquio['aspettative']),'flexorario'=>$row0_colloquio['flessibilitaorari'],'flexsalario'=>$row0_colloquio['flessibilitasalario']);
        //TERMINI
        if(count($row0_colloquio)>0)
            $dati['termini']=array('disponibilita'=>$row0_disponibilita['statodisp_'],'giornidipreavviso'=>$row0_disponibilita['giornidipreavviso'],'parametrosalariale'=>$row0_disponibilita['salattlordo'],'salariodesiderato'=>$row0_disponibilita['saldeslordo']);
        //FORMAZIONE
        $dati['formazione']=array();
        foreach ($rows_formazione as $row_formazione) {
           $dati['formazione'][]=array('anno'=>$row_formazione['anno'],'titolo'=>iconv('UTF-8', 'windows-1252', $row_formazione['titolo']),'corso'=>iconv('UTF-8', 'windows-1252', $row_formazione['corso']),'istituto'=>'XXX','luogo'=>'XXX'); 
        }
        //LINGUE
         $dati['lingue']=array();
        foreach ($rows_lingue as $row_lingua) {
            $dati['lingue'][]=array('lingua'=>$row_lingua['lingua_'],'diplomiconseguiti'=>$row_lingua['note'],'conversazione'=>$row_lingua['parlato_'],'scrittura'=>$row_lingua['scritto_'],'lettura'=>$row_lingua['letto_']);
        }
        //ESPERIENZE PROFESSIONALI
        $dati['esperienzeprofessionali']=array();
        foreach ($rows_esperienzeprof as $row_esperienzeprof) {
            $dati['esperienzeprofessionali'][]=array('dal'=>'XXX','al'=>'XXX','qualifica'=>$row_esperienzeprof['mansioni'],'ragionesociale'=>'XXX','luogo'=>'XXX','settore'=>$row_esperienzeprof['ramoattiv'],'Ndipendenti'=>$row_esperienzeprof['ndipendenti'],'subordinatoa'=>$row_esperienzeprof['subord'],'responsabiledi'=>$row_esperienzeprof['responsabiledi'],'competenze'=>$row_esperienzeprof['princmansioni'],'carriera'=>$row_esperienzeprof['carriera'],'referenze'=>$row_esperienzeprof['referenze'],'causatermine'=>$row_esperienzeprof['causainterruzione'],'lingueutilizzate'=>$row_esperienzeprof['lingueutilizzate']);  
        }
        
        //COMPETENZE
        $dati['competenze']=array();
        foreach ($rows_competenze as $row_competenze) {
        $dati['competenze'][]=array('area'=>iconv('UTF-8', 'windows-1252', $row_competenze['settore'].' '.$row_competenze['subsettor']),'livelloesperienza'=>$row_competenze['esperienza_'],'elencocompetenze'=>iconv('UTF-8', 'windows-1252', $row_competenze['competenza']));
        }
        
        $dati['competenzeit']=array();
        //COMPETENZE IT
        foreach ($rows_competenzeit as $row_competenzeit) {
            $dati['competenzeit'][]=array('software'=>iconv('UTF-8', 'windows-1252', $row_competenzeit['software']),'livelloesperienza'=>$row_competenzeit['livello'],'versione'=>$row_competenzeit['versione'],'anno'=>$row_competenzeit['anno']);
        }
        
        $dati['daticliente']=array('ragionesociale'=>'','posizionericercata'=>'','tipologiacontrattuale'=>'','luogolavoro'=>'','retribuzioneannualorda'=>'');
        $dati['analisiprofilo']=array('elementifavore'=>'','elementidiscussione'=>'');
        $dati['giudizio']=array('giudizio'=>'');

        return $dati;
    
    }
    //custom WW
    public function delete_casellario($recordid_candidato)
    {
        $sql="
            DELETE FROM user_candid_page
            WHERE r                                                                                                                                 ecordid_='$recordid_candidato' AND original_name='casellario_generato' 
            ";
        $this->execute_query($sql);
    }
    //custom WW
    public function aggiorna_categoria_casellario($recordid_candidato,$tipodoc)
    {
        $sql="
            UPDATE user_candid_page
            SET category='$tipodoc'
            WHERE recordid_='$recordid_candidato' AND original_name='casellario_generato'
            ";
        $this->execute_query($sql);
    }
    
    public function inserisci_allegato($origin_folder,$origin_filename,$origin_ext,$tableid,$recordid)
    {
        $sql="SELECT namefolder,numfilesfolder FROM sys_table where id='$tableid'";
        $result=  $this->select($sql);
        $namefolder=null;
        $numfilesfolder=null;
        if(count($result)==1)
        {
            $namefolder=$result[0]['namefolder'];
            $numfilesfolder=$result[0]['numfilesfolder'];
        }
        $new_filename=  $this->generate_filename($tableid);
        $fullpath_origin="$origin_folder/$origin_filename.$origin_ext";
        $fullpath_archive="../JDocServer/archivi/$tableid/$namefolder/$new_filename.$origin_ext";

        if(!file_exists("../JDocServer/archivi/$tableid"))
                        {
                            mkdir("../JDocServer/archivi/$tableid");
                        }
                    if(!file_exists("../JDocServer/archivi/$tableid/$namefolder"))
                        {
                            mkdir("../JDocServer/archivi/$tableid/$namefolder");
                        }
        if (copy($fullpath_origin,$fullpath_archive)) {
            if(!file_exists("../JDocServer/trash"))
            {
                mkdir("../JDocServer/trash");
            }
            if(!file_exists("../JDocServer/trash/$tableid"))
            {
                mkdir("../JDocServer/trash/$tableid");
            }
            copy($fullpath_origin,"../JDocServer/trash/$tableid/$origin_filename.$origin_ext");
            if(($this->isnotempty($origin_folder))&&($this->isnotempty($origin_filename)))
            {
                unlink($fullpath_origin);
            }
          }
          
        //inserisco il file nel database come allegato del record
        $page_tablename="user_".strtolower($tableid)."_page";
        //CUSTOM POSTGRES
        $sql="INSERT INTO $page_tablename (recordid_,counter_,fileposition_,path_,filename_,extension_,filestatusid_,signed_,deleted_,original_name) VALUES ('$recordid',0,0,'archivi\\\\".$tableid."\\\\".$namefolder."\\\\"."','$new_filename','$origin_ext','S','N','N','$origin_filename') ";
        $this->set_logquery('inserimento allegato',$sql);
        $this->execute_query($sql);
    }
    
    
    public function get_dati_firsrecord_bycondition($tableid,$condizioni)
    {
        $where='WHERE true';
        foreach ($condizioni as $key => $condizione) {
            $where=$where." AND $condizione";
        }
        $table="user_".strtolower($tableid);
        $sql="SELECT * FROM $table WHERE $condizione ";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            return $result[0];
        }
        else
        {
            return false;
        }
    }
    
    
    public function get_path_allegato_pdf($tableid,$recordid,$filename)
    {
        $path='';
        $tablepage=  'user_'.strtolower($tableid).'_page';
        $query="SELECT path_,filename_,extension_ FROM $tablepage WHERE recordid_='$recordid' AND filename_='$filename'";
        $result=  $this->select($query);
        if(count($result)>0)
        {
            $extension=$result[0]['extension_'];
        $path=$result[0]['path_'].$result[0]['filename_'].'.'.$extension;
        
        
        $pos=  strpos($path, 'Appl');
        if($pos === false) {
            $path= "JDocServer/".$path;
;
        }
        else
        {
            $path= "Neuteck/docusys/".$path; 

        }
        
        if(($extension=='pdf'))
            {
                $path=$path;
            }
            else
            {
                $path_test="../".str_replace(".".$extension, ".pdf", $path);
                if(file_exists($path_test))
                {
                    $path=str_replace("../", "", $path_test);
                }
                else
                {
                    if($extension=='jpg')
                    {
                        $command='cd ../JDocServices && JDocServices.exe "filetopdf" "../'.$path.'" "'.$path_test.'" '; 
                        exec($command);
                        if(file_exists($path_test))
                        {
                            $path=str_replace("../", "", $path_test);
                        }
                    }
                }
            }
        }
        else
        {
            $path= '';
        }
        return $path;
    }
    

    public function script_update_segnalazioni_totore()
    {
        $segnalazioni=$this->db_get('user_segnalazioni');
        foreach ($segnalazioni as $key => $segnalazione) {
            $recordid_segnalazione=$segnalazione['recordid_'];
            $this->aggiorna_segnalazione($recordid_segnalazione);
        }
        
    }
    
    public function script_migrazione_campi()
    {
        $sql="select *
        from sys_field
        where tablelink is null OR tablelink=''
        ORDER BY tableid,fieldorder";
        $fields= $this->select($sql);
        foreach ($fields as $key => $field) {
            $tableid=$field['tableid'];
            $fieldid=$field['fieldid'];
            $fieldorder=$field['fieldorder'];
            $sql="
                INSERT INTO sys_user_order
                (userid,tableid,fieldid,fieldorder,typepreference)
                VALUES
                (1,'$tableid','$fieldid','$fieldorder','campiInserimento')
                ";
            $this->execute_query($sql);
            
        }
    }
    
    public function aggiorna_segnalazione($recordid_segnalazione)
    {
        $today=  date('Y-m-d');
        $sql="SELECT sum(totore) as totore from user_timesheet WHERE recordidsegnalazioni_='$recordid_segnalazione'";
            $result=  $this->select($sql);
            if(count($result)>0)
            {
                $totore=$result[0]['totore'];
                if(isnotempty($totore))
                {
                    $sql="UPDATE user_segnalazioni SET totore='$totore',datalast='$today' WHERE recordid_='$recordid_segnalazione'";
                    $this->execute_query($sql);
                }
            }
    }
    
    public function script_add_columns($tableid)
    {
        $tablename=  "user_".strtolower($tableid);
        $tablename_page="user_".strtolower($tableid)."_page";
        
        $sql="ALTER TABLE $tablename ADD recordstatus_ character varying(255)";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename ADD creation_ timestamp without time zone DEFAULT now()";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename ADD lastupdaterid_ integer;";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD creatorid_ integer;";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD creation_ timestamp without time zone DEFAULT now()";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD thumbnail character varying(255)";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD lastupdaterid_ integer;";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD category character varying(255);";
        $this->execute_query($sql);
        
        $sql="ALTER TABLE $tablename_page ADD original_name character varying(255);";
        $this->execute_query($sql);

    }
    
    public function script_add_page_category_field()
    {
        $tables=  $this->db_get('sys_table');
        foreach ($tables as $key => $table) {
            $tablename_page=  "user_".strtolower($table['id'])."_page";
            $sql="ALTER TABLE $tablename_page ADD category character varying(255)";
            $this->execute_query($sql);
        }
        
    }
    
    public function script_category_piantina()
    {
        $immobili_pages=  $this->db_get('user_immobili_page','*',"category ilike '%Piantina%' ");
        foreach ($immobili_pages as $key => $immobile_page) {
            $recordid=$immobile_page['recordid_'];
            $filename=$immobile_page['filename_'];
            $category=$immobile_page['category'];
            
            $category=  str_replace("Piantina piano seminterrato", "Piantina", $category);
            $category=  str_replace("Piantina piano terra", "Piantina", $category);
            $category=  str_replace("Piantina primo piano", "Piantina", $category);
            $category=  str_replace("Piantina secondo piano", "Piantina", $category);
            $sql="UPDATE user_immobili_page SET category='$category' WHERE recordid_='$recordid' AND filename_='$filename'";
            $this->execute_query($sql);
        }
        
        
    }
    
    public function script_add_original_fields($tableid)
    {
        $sql="
            SELECT * FROM sys_field WHERE tableid='$tableid'
            ";
        $fields=  $this->select($sql);
        foreach ($fields as $key => $field) {
            $fieldid=$field['fieldid'];
            $fieldorder=$field['fieldorder'];
            $sql="INSERT INTO sys_user_order (userid,tableid,fieldid,fieldorder,typepreference) VALUES (1,'$tableid','$fieldid',$fieldorder,'campiInserimento')";
            $this->execute_query($sql);
        }
    }
    
    public function script_durata_assistenze()
    {
        $sql="SELECT * FROM user_assistenze ";
        $result=  $this->select($sql);
        foreach ($result as $key => $row) {
            $recordid=$row['recordid_'];
            $orainizio=$row['orainizio'];
            $orafine=$row['orafine'];
            $date_diff=  $this->date_diff($orainizio, $orafine);
            $durata='';
            if(($date_diff['h']!=0||$date_diff['m']!=0))
            {
                $durata=$date_diff['h'].":".$date_diff['m'];
                $sql="UPDATE user_assistenze SET durata='$durata' WHERE recordid_='$recordid'";
                $this->execute_query($sql);
            }
        }
          
    }
    
    public function script_durata_timesheet()
    {
        $sql="SELECT * FROM user_timesheet ";
        $result=  $this->select($sql);
        foreach ($result as $key => $row) {
            $recordid=$row['recordid_'];
            $orainizio=$row['orainizio'];
            $orafine=$row['orafine'];
            $durata='';
            $date_diff=  $this->date_diff($orainizio, $orafine);
            if(($date_diff['h']!=0||$date_diff['m']!=0))
            {
                $durata=$date_diff['h'].":".$date_diff['m'];
                $sql="UPDATE user_timesheet SET totore='$durata' WHERE recordid_='$recordid'";
                $this->execute_query($sql);
            }
        }
          
    }
    
    public function script_totaleimporto_ordiniconsumabili()
    {
        $sql="SELECT * FROM user_ordiniconsumbabili";
        $result=  $this->select($sql);
        foreach ($result as $key => $row) {
            $importototale_dettagli=0;
            $ordiniconsumabili_recordid=$row['recordid_'];
            $sql="SELECT * FROM user_ordiniconsumabili_dettagli WHERE recordidordiniconsumbabili_='$ordiniconsumabili_recordid'";
            $result_dettagli=  $this->select($sql);
            foreach ($result_dettagli as $key_dettaglio => $row_dettaglio) {
                $quantita=$row_dettaglio['quantita'];
                $prezzo=$row_dettaglio['prezzo'];
                $importototale_dettagli=$importototale_dettagli+($quantita*$prezzo);
            }
            $sql="UPDATE user_ordiniconsumbabili SET importototale=$importototale_dettagli WHERE recordid_='$ordiniconsumabili_recordid'";
            $this->execute_query($sql);
        }
    }
    
    function date_diff($date1, $date2)
    {
        $date1timestamp=  strtotime($date1);
        $date2timestamp=  strtotime($date2);
        $all = round(($date2timestamp - $date1timestamp) / 60);
        $d = floor ($all / 1440);
        $h = floor (($all - $d * 1440) / 60);
         $m = $all - ($d * 1440) - ($h * 60);
        return array('d' => $d, 'h' => $h, 'm' => $m);
    }
    
    public function script_lookup_items()
    {
        $sql="SELECT * FROM sys_lookup_table_item";
        $items=  $this->select($sql);
        foreach ($items as $key => $item) {
            $lookuptableid=$item['lookuptableid'];
            $itemcode=$item['itemcode'];
            $itemcode=  str_replace("'", "''", $itemcode);
            $sql="UPDATE sys_lookup_table_item SET itemdesc='$itemcode' where lookuptableid='$lookuptableid' and itemcode='$itemcode'";
            $this->execute_query($sql);    
            
        }
    }
    
    public function script_fix_timesheet()
    {
       $sql="SELECT * FROM user_timesheet";
       $rows=  $this->select($sql);
       foreach ($rows as $key => $row) {
           $recordid=$row['recordid_'];
           $dalle=$row['dalle'];
           $alle=$row['alle'];
           $datainizio=  date('Y-m-d H:i', strtotime($dalle));
           $orainizio= date('H:i', strtotime($dalle));
           $datafine=  date('Y-m-d H:i', strtotime($alle));
           $orafine= date('H:i', strtotime($alle));
           $sql="UPDATE user_timesheet SET datainizio='$datainizio',datafine='$datafine',orainizio='$orainizio',orafine='$orafine' WHERE recordid_='$recordid'";
           $this->execute_query($sql);
       }
    }
    
    public function script_sync_timesheet_attivitacommerciali()
    {
        $sql="SELECT * FROM user_attivitacommerciali";
        $rows=  $this->select($sql);
        foreach ($rows as $key => $row) 
        {
            
            $stato=$row['stato'];
            $data=$row['data'];
            if(($this->isnotempty($data))&&($stato=='conclusa'))
            {
                

                $recordid_timesheet=  $this->generate_recordid('timesheet');
                $userid=$row['creatorid_'];
                $recordidaziende='00000000000000000000000000003192';
                
                $data_formattata=date('Y-m-d', strtotime($data));
                $orainizio_formattata='08:00';
                $orafine_formattata='08:00';
                $totore='00:00';
                $servizio='commerciale';
                
                $note='';
                $note=$note.$row['titolo'];
                $note=$note.' - '.$row['note'];
                $note=  str_replace("'", "''", $note);
                $servizio='commerciale';
                if($this->isnotempty($fields['recordidaziende_']))
                {
                    $recordidaziende=$fields['recordidaziende_'];
                }
            
                $dalle=$data_formattata.' '.$orainizio_formattata;
                $alle=$data_formattata.' '.$orafine_formattata;
                $datainizio=$data_formattata;
                $datafine=$data_formattata;

                $ref_tableid='attivitacommerciali';
                $ref_recordid=$row['recordid_'];
                
                $sql="INSERT INTO user_timesheet (recordid_,id,creatorid_,idutente,dalle,alle,note,servizio,recordidaziende_,totore,lastupdaterid_,datainizio,datafine,ref_tableid,ref_recordid) "
                    . "VALUES ('$recordid_timesheet',0,$userid,$userid,'$dalle','$alle','$note','$servizio','$recordidaziende','$totore',$userid,'$datainizio','$datafine','$ref_tableid','$ref_recordid')";
                $this->execute_query($sql);
            }
        }
    }
    
    public function script_fix_thumbnail()
    {
       $tables=  $this->get_archive_list();
       foreach ($tables as $key => $table) {
           $tablename="user_".strtolower($table['idarchivio'])."_page";
           $sql="UPDATE $tablename SET thumbnail=null where thumbnail='null'";
           $this->execute_query($sql);
           echo "$tablename OK</br>";
           
       }
    }
    
    public function script_reset_thumbnail()
    {
       $tables=  $this->get_archive_list();
       foreach ($tables as $key => $table) {
           $tablename="user_".strtolower($table['idarchivio'])."_page";
           $sql="UPDATE $tablename SET thumbnail=null";
           $this->execute_query($sql);
           echo "$tablename OK</br>";
           
       }
    }
    
    public function script_trim_ext()
    {
       $tables=  $this->get_archive_list();
       foreach ($tables as $key => $table) {
           $tablename="user_".strtolower($table['idarchivio'])."_page";
           $sql="SELECT * FROM $tablename ";
           $rows=$this->select($sql);
           foreach ($rows as $key => $row) {
               $path=$row['path_'];
               $filename=$row['filename_'];
               $extension=$row['extension_'];
               $trimmed_extension=  trim(preg_replace('/\s+/', '', $extension));
               $sql="UPDATE $tablename SET extension_='$trimmed_extension' WHERE path_='$path' AND filename_='$filename' AND extension_='$extension'";
               $this->execute_query($sql);
           }
        echo "$tablename OK </br>" ;  
       }
    }
    
    public function get_record($tableid,$recordid)
    {
         $record= $this->db_get_row("user_$tableid",'*',"recordid_='$recordid'");
         return $record;
    }
    
    
    public function get_record_info($tableid,$recordid)
    {
        date_default_timezone_set('Europe/Berlin');
        $tablename="user_".strtolower($tableid);
        $sql="SELECT recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_ FROM $tablename where recordid_='$recordid'";
        $result=  $this->select($sql);
        $return=array();
        if(count($result)>0)
        {
            $creatorid=$result[0]['creatorid_'];
            $return['recordid']=$result[0]['recordid_'];
            $return['creatorid']=$creatorid;
            $return['creator']=  $this->get_user_nomecognome($creatorid);
            if(file_exists("../JDocServer/avatar/$creatorid.jpg"))
            {
               $return['creator_avatar']=domain_url()."/JDocServer/avatar/$creatorid.jpg";
            }
            else
            {
               $return['creator_avatar']=  base_url('/assets/images/anon.png');
            }
            $creation=$result[0]['creation_'];
            if($creation!='')
            {
                $creation=  str_replace("/", "-", $creation);
            $return['creation']=date('d-m-Y H:i', strtotime($creation));
            }
            else
            {
               $return['creation']='-'; 
            }
            $lastupdaterid=$result[0]['lastupdaterid_'];
            $return['lastupdaterid']=$lastupdaterid;
            $return['lastupdater']=  $this->get_user_nomecognome($lastupdaterid);
            if(file_exists("../JDocServer/avatar/$lastupdaterid.jpg"))
            {
                $return['lastupdater_avatar']=domain_url()."/JDocServer/avatar/$lastupdaterid.jpg";
            }
            else
            {
                $return['lastupdater_avatar']=  base_url('/assets/images/anon.png');
            }
            $lastupdate=$result[0]['lastupdate_'];
            if($lastupdate!='')
            {
                $lastupdate=str_replace("/", "-", $lastupdate);
                $return['lastupdate']=date('d-m-Y H:i', strtotime($lastupdate));
            }
            else
            {
                $return['lastupdate']='';
            }
        }
        return $return;
    }
    
    public function get_user_nomecognome($userid)
    {
        if(($userid!=null)&&($userid!=''))
        {
            $user_info=  $this->get_user_info($userid);
            return $user_info['firstname'].' '.$user_info['lastname'];
        }
        else
        {
            return '';
        }
    }
    public function get_user_info($userid)
    {
        $sql="SELECT * FROM sys_user where id=$userid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $return=$result[0];
        }
        else
        {
            $return['firstname']='error';
            $return['lastname']='error';
        }
        return $return;
    }
    
    
    
    public function sync_records()
    {
        $new_records=array();
        foreach ($new_records as $new_record_key => $new_record) 
        {
            $new_recordid=  $this->generate_recordid($new_record_key);
            $new_record['recordid_']=$new_recordid;
            foreach ($new_record as $value_key => $value) 
            {
                $value = str_replace("'", "''", $value); 
                if($key!='recordid_')
                {
                    $type=  $this->get_field_type($new_record_key, $value_key);
                    if($type!=null)
                    {
                        $insert=$insert."$value_key";
                        if($type=='Parola')
                        {
                            $values=$values."$value";
                        }
                        
                        
                    }
                }
            }
        }
    }
    
    function get_field_type($tableid,$fieldid)
    {
        $sql=" 
            SELECT * 
            FROM sys_field
            WHERE tableid='$tableid' AND fieldid='$fieldid'
        ";
        $result=  $this->select($sql);
        if(count($result)==1)
        {
            if($this->isnotempty($result[0]['tablelink']))
            {
                if($this->isnotempty($result[0]['keyfieldlink']))
                {
                    $return='linkedmaster';
                }
                else
                {
                    $return='linked';
                }
            }
            else
            {
               $return=$result[0]['fieldtypeid']; 
            }
            
        }
        else
        {
            $return=null;
        }
        return $return;
    }
    
    public function add_ordiniclientikeysky($fields_vendite,$prodotti_vendite)
    {
        $recordid_ordine=  $this->generate_recordid('ordiniclientikeysky');
        $recordid_vendite=$fields_vendite['recordid_'];
        $recordid_aziende=$fields_vendite['recordidaziende_'];
        $titolo=$fields_vendite['titolo'];
        $sql="INSERT INTO user_ordiniclientikeysky (recordid_,recordidaziende_,recordidvendite_,titolo) VALUES ('$recordid_ordine','$recordid_aziende','$recordid_vendite','$titolo')";
        $this->execute_query($sql);
        foreach ($prodotti_vendite as $key => $prodotto_vendite) {
            $recordid_prodotto_ordine=  $this->generate_recordid('prodotti_ordine');
            $prodotto=$prodotto_vendite['prodotto'];
            $sql="INSERT INTO user_prodotti_ordine (recordid_,recordidordiniclientikeysky_,prodotto) VALUES ('$recordid_prodotto_ordine','$recordid_ordine','$prodotto')";
            $this->execute_query($sql);
        }
    }
    
    public function add_schedeordinekeysky($fields_vendite,$prodotti)
    {
        $recordid=  $this->generate_recordid('schedeordinekeysky');
        $recordid_vendite=$fields_vendite['recordid_'];
        $recordid_aziende=$fields_vendite['recordidaziende_'];
        $titolo=$fields_vendite['titolo'];
        $sql="INSERT INTO user_schedeordinekeysky (recordid_,recordidaziende_,recordidvendite_,titolo) VALUES ('$recordid','$recordid_aziende','$recordid_vendite','$titolo')";
        $this->execute_query($sql);
    }
    
    
    public function add_timesheet($ref_tableid,$ref_recordid,$fields)
    {
        $recordid=  $this->generate_recordid('timesheet');
        $userid=$this->get_userid();
        $recordidaziende='00000000000000000000000000003192';
        $data='';
        $orainizio='';
        $orafine='';
        $note='';
        $servizio='';
        
        if($ref_tableid=='attivitacommerciali')
        { 
            $data=$fields['data'];
            $orainizio=$fields['orainizio'];
            $orafine=$fields['orafine'];  
            $note=$note.$fields['titolo'];
            $note=$note.' - '.$fields['note'];
            $servizio='commerciale';
            if($this->isnotempty($fields['recordidaziende_']))
            {
                $recordidaziende=$fields['recordidaziende_'];
            }
        }
        
        if($ref_tableid=='attivitasviluppo')
        { 
            $data=$fields['data'];
            $orainizio=$fields['dalle'];
            $orafine=$fields['alle'];  
            $titolo="";
            if($this->isnotempty($fields['recordidsviluppi_']))
            {
                $sql="SELECT titolo from user_sviluppi WHERE recordid_='".$fields['recordidsviluppi_']."'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $titolo=$result[0]['titolo'];
                }
            }
            
            $note=$titolo." ".$fields['note'];
            $servizio='sviluppo';
        }
        
        if($ref_tableid=='assistenze')
        { 
            $data=$fields['data'];
            $orainizio=$fields['orainiziotrasferta'];
            if(!$this->isnotempty($orainizio))
            {
                $orainizio=$fields['orainizio'];
            }
            $orafine=$fields['orafinetrasferta'];  
            if(!$this->isnotempty($orafine))
            {
                $orafine=$fields['orafine'];
            }
            $tipoassistenza=$fields['tipoassistenza'];
            $note=$tipoassistenza." ".$fields['note'];
            $servizio='assistenza';
            if($this->isnotempty($fields['recordidaziende_']))
            {
                $recordidaziende=$fields['recordidaziende_'];
            }
        }
        
        $note=  str_replace("'", "''", $note);
        if($this->isnotempty($data))
        {
            $data_formattata=date('Y-m-d', strtotime($data));
            if($this->isnotempty($orainizio))
            {
                $orainizio_formattata=date('H:i:s',  strtotime($orainizio));
            }
            else
            {
                $orainizio_formattata='08:00';
            }

            if($this->isnotempty($orafine))
            {
                $orafine_formattata=date('H:i:s',  strtotime($orafine));
            }
            else
            {
                $orafine_formattata=$orainizio_formattata;
            }
            $durata=  date_diff(date_create($orafine_formattata), date_create($orainizio_formattata));
            $totore=$durata->format('%H:%I');
            $dalle=$data_formattata.' '.$orainizio_formattata;
            $alle=$data_formattata.' '.$orafine_formattata;
            $datainizio=$data_formattata;
            $datafine=$data_formattata;
            $orainizio=$orainizio_formattata;
            $orafine=$orafine_formattata;
            
            $sql="INSERT INTO user_timesheet (recordid_,creatorid_,idutente,note,servizio,recordidaziende_,totore,lastupdaterid_,datainizio,orainizio,datafine,orafine,stato,ref_tableid,ref_recordid) "
                . "VALUES ('$recordid','$userid',$userid,'$note','$servizio','$recordidaziende','$totore',$userid,'$datainizio','$orainizio','$datafine','$orafine','completato','$ref_tableid','$ref_recordid')";
            $this->execute_query($sql);
        }
    }
    
    
    
    public function get_allfields($tableid,$recordid)
    {
        $linkedtables=  $this->get_linkedtables($tableid);
        $allfields[$tableid][0]['tableid']=$tableid;
        $allfields[$tableid][0]['fields']=  $this->get_fields_record($tableid, $recordid);
        foreach ($linkedtables as $key => $linkedtable) 
        {
             $records=$this->get_allrecords_linkedtable($linkedtable,$tableid, $recordid);
             foreach ($records as $key => $record) {
                 $allfields[$linkedtable][$key]['tableid']=$linkedtable;
                  $allfields[$linkedtable][$key]['fields']= $record;
             }
        }
        return $allfields;
    }
    
    public function set_new_records($master_tableid,$new_records)
    {
        if(array_key_exists('master', $new_records))
        {
            if(array_key_exists('records', $new_records['master'][$master_tableid]))
            {
                $tableid=$new_records['master'][$master_tableid]['tableid'];
                foreach ($new_records['master'][$master_tableid]['records'] as $key => $new_record) 
                {
                    $recordid=$new_record['recordid_'];
                    //aggiornamento dei dati
                    $sql="UPDATE user_".strtolower($tableid);
                    $counter=0;
                    foreach ($new_record as $key => $value) {
                        $value = str_replace("'", "''", $value);
                        if($value!='')
                        {
                            if($counter==0)
                            {
                                $sql=$sql." SET $key='$value'"; 
                            }
                            else
                            {
                                $sql=$sql." ,$key='$value'";
                            }
                            $counter++;
                        }
                    }
                    $sql=$sql."WHERE recordid_='$recordid'";
                    $this->execute_query($sql);
                    
                    //inserimento nuovi allegati
                    /*if(array_key_exists('allegati', $new_record))
                    {
                        $remote_allegati=$new_record['allegati'];
                        $sql="SELECT namefolder,numfilesfolder FROM sys_table where id='$tableid'";
                        $result=  $this->select($sql);
                        if(count($result)==1)
                        {
                            $namefolder=$result[0]['namefolder'];
                        }
                        
                        $sql="SELECT max(fileposition_) FROM user".  strtolower($tableid)."_page where recordid_='$recordid'";
                        $result=  $this->select($sql);
                        $counter=0;
                        if(count($result)>0)
                        {
                            $counter=$result[0]['fileposition_'];
                        }
                        
                        foreach ($remote_allegati as $key => $remote_allegato) {
                            $counter=$counter+1;
                            $now=date('Y-m-d H:i:s');
                            $remote_filename=$remote_allegato['filename_'];
                            $new_filename=  $this->generate_filename($tableid);
                            $fileext=$remote_allegato['fileext'];
                            $thumbnail_field=null;
                            $original_name=$remote_allegato['original_name'];
                            $path="archivi\\\\$tableid\\\\$namefolder\\\\";
                            $sql="INSERT INTO user_".  strtolower($tableid)."_page (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,counter_,fileposition_,path_,filename_,extension_,thumbnail,original_name,filestatusid_,signed_,deleted_) VALUES ('$recordid',1,'$now',$userid,'$now',$counter,$counter,'$path','$new_filename','$fileext',$thumbnail_field,'$original_name','S','N','N') ";
                            $this->set_logquery('inserimento allegato',$sql);
                            $this->execute_query($sql);
                            $remote_url="http://workandwork.com/OnlineCV/archivi/CANDID/$recordid/$remote_filename.$fileext";
                            $local_path="../JDocServer/archivi/CANDID/$namefolder/$new_filename.$fileext";
                            copy($remote_url, $local_path);
                        }
                    }
                    */
                    
                }
            }
        }
        if(array_key_exists('linked', $new_records))
        {
            foreach ($new_records['linked'] as $key => $linked_table) 
            {
                if(array_key_exists('records', $linked_table))
                {
                    $tableid=$linked_table['tableid'];
                    foreach ($linked_table['records'] as $key => $new_record) 
                    {
                        $new_recordid=  $this->generate_recordid($tableid);
                        $new_record['recordid_']=$new_recordid;
                        $sql="";
                        $insert="";
                        $values="";
                        $insert="INSERT INTO user_".strtolower($tableid);
                        $counter=0;
                        foreach ($new_record as $key => $value) 
                        {
                            $value = str_replace("'", "''", $value);
                            if($value!='')
                            {
                                if($counter==0)
                                {
                                    $insert=$insert."($key";
                                    $values=$values." VALUES ('$value'"; 
                                }
                                else
                                {
                                    $insert=$insert.",$key";
                                    $values=$values.",'$value'";
                                    if($key=='periodoin')
                                    {
                                        $dallanno=date('Y', strtotime($value));
                                        $insert=$insert.",dallanno";
                                        $values=$values.",'$dallanno'";
                                    }
                                    if($key=='periodofi')
                                    {
                                        $allanno=date('Y', strtotime($value));
                                        $insert=$insert.",allanno";
                                        $values=$values.",'$allanno'";
                                    }
                                }
                                $counter++;
                            }
                        }
                        $insert=$insert.")";
                        $values=$values.")";
                        $sql=$insert.$values;
                        $this->execute_query($sql);
                    }
                }
            }
        } 
        foreach ($new_records['record_preview'] as $key => $record_preview_partial_url) {
            //$record_preview_complete_url="http://localhost:8822/jdoconlinecv/".$record_preview_partial_url;
            $record_preview_complete_url="http://workandwork.com/OnlineCV/".$record_preview_partial_url;
            $record_preview_complete_path="../JDocServer/".$record_preview_partial_url;
            copy($record_preview_complete_url, $record_preview_complete_path);
        }
        
    }
    
    public function valida_record($tableid,$recordid)
    {
        $sql="UPDATE user_".strtolower($tableid)." SET recordstatus_='' WHERE recordid_='$recordid'";
        $this->execute_query($sql);
    }
    
    public function valida_tutto_record($master_tableid,$master_recordid)
    {
        $linked_tables=  $this->get_linkedtables($master_tableid);
        foreach ($linked_tables as $key => $linked_table) 
        {
            $sql="UPDATE user_".strtolower($linked_table)." SET recordstatus_='' WHERE recordid".strtolower($master_tableid)."_='$master_recordid' AND recordstatus_='new'"; 
            $this->execute_query($sql);
        }
        $sql="UPDATE user_".strtolower($master_tableid)." SET recordstatus_='' WHERE recordid_='$master_recordid' AND recordstatus_='new'";
        $this->execute_query($sql);
    }
    
    public function save_view($tableid,$view_name,$post)
    {
        $now=date('Y-m-d H:i:s');
        $userid=  $this->get_userid();
        
        $query=$post['query'];
        $start_where_pos=strpos($query,'true AND'); 
        $end_where_pos=strpos($query,') AS risultati');
        $query_conditions=  substr($query, $start_where_pos, $end_where_pos-$start_where_pos);
        $query_conditions=str_replace("'", "''", $query_conditions);
        
        $post=json_encode($post);
        $post=str_replace("'", "''", $post);
        
        $sql="INSERT INTO sys_view (name,userid,tableid,creation,post,query_conditions) VALUES ('$view_name','$userid','$tableid','$now','$post','$query_conditions')";
        $this->execute_query($sql);
    }
    
    public function get_saved_views($tableid)
    {
        $userid=  $this->get_userid();
        $superuser_view=array();
        $user_view=array();
        $sql="SELECT id,name from sys_view WHERE sys_view.tableid='$tableid' AND sys_view.userid='$userid' ORDER BY name";
        $user_view=$this->select($sql);
        if($userid!=1)
        {
            $sql="SELECT id,name from sys_view WHERE sys_view.tableid='$tableid' AND sys_view.userid='1' ORDER BY name";
            $superuser_view=$this->select($sql);
        }
        $views=  array_merge($user_view,$superuser_view);
        


        return $views;
    }
    
    public function get_tab_views($tableid)
    {
        $userid=  $this->get_userid();
        $superuser_view=array();
        $user_view=array();
        $sql="SELECT sys_view.id,name 
            FROM sys_view JOIN sys_user_default_view ON sys_view.id=sys_user_default_view.viewid
            WHERE sys_view.tableid='$tableid' AND (sys_view.userid='$userid' OR sys_view.userid='1') 
            ORDER BY sys_user_default_view.id
            ";
        $views=$this->select($sql);

        return $views;
    }
    
    public function save_report_views($reportid,$post)
    {
        $sql="DELETE FROM sys_report_views where reportid=$reportid";
        $this->execute_query($sql);
        $views=$post['views'];
        foreach ($views as $key => $view) {
            $sql="
                INSERT INTO sys_report_views
                (reportid,viewid,reportorder)
                VALUES
                ($reportid,$view,$key)
                ";
            $this->execute_query($sql);
        }
    }
    
    public function elimina_report($reportid,$post)
    {
        
        $sql="DELETE FROM sys_report_views WHERE reportid=$reportid";
        $this->execute_query($sql);
        $sql="DELETE FROM sys_report WHERE id=$reportid";
        $this->execute_query($sql);
    }
    public function set_default_view($tableid,$viewid)
    {
        $userid=  $this->get_userid();
        $sql="SELECT * FROM sys_user_default_view WHERE userid=$userid AND tableid='$tableid'";
        $result=  $this->select($sql);
        //if(count($result)>0)
        if(false)
        {
            $sql="UPDATE sys_user_default_view SET viewid='$viewid' WHERE userid=$userid AND tableid='$tableid'";
            $this->execute_query($sql);
        }
        else
        {
            $sql="INSERT INTO sys_user_default_view (userid,tableid,viewid) VALUES($userid,'$tableid',$viewid)";
            $this->execute_query($sql);
        }
    }
    
    public function unset_default_view($tableid,$viewid)
    {
        $userid=  $this->get_userid();
        $sql="DELETE FROM sys_user_default_view WHERE userid=$userid AND tableid='$tableid' AND viewid=$viewid";
        $this->execute_query($sql);
    }
    
    
    public function delete_view($viewid)
    {
        $sql="DELETE FROM sys_user_default_view where viewid='$viewid'";
        $this->execute_query($sql);
        $sql="DELETE FROM sys_alert where alert_viewid='$viewid'";
        $this->execute_query($sql);
        $sql="DELETE FROM sys_view where id='$viewid'";
        $this->execute_query($sql);
    }
    
    public function rename_view($viewid,$new_name)
    {

        $sql="UPDATE sys_view SET \"name\"='$new_name' where id='$viewid'";
        $this->execute_query($sql);
        
    }
    
    public function get_default_viewid($tableid)
    {
        $default_viewid='';
        // recupero la view di default per l'utente
        $userid=  $this->get_userid();
        $sql="SELECT viewid FROM sys_user_default_view WHERE userid=$userid AND tableid='$tableid'";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $default_viewid=$result[0]['viewid'];
        }
        else
        {
            $group=  $this->db_get_value('sys_user_settings','value',"userid=$userid AND setting='group'");
            if(isnotempty($group))
            {
                $sql="SELECT viewid FROM sys_user_default_view WHERE userid=$group AND tableid='$tableid'";
                $result=  $this->select($sql);
            }
            else
            {
                $result=array();
            }
            if(count($result)>0)
            {
                $default_viewid=$result[0]['viewid'];
            }
            else
            {
                $sql="SELECT viewid FROM sys_user_default_view WHERE userid=1 AND tableid='$tableid'";
                $result=  $this->select($sql);
                if(count($result)>0)
                {
                    $default_viewid=$result[0]['viewid'];
                }
            }
            
        }
        return $default_viewid;
    }
    
    public function get_view($viewid){
        $sql="SELECT * FROM sys_view WHERE id=$viewid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $return=$result[0];
        }
        else
        {
            $return=array();
        }
        return $return;
    }
    
    public function get_view_query($tableid,$viewid,$userid=null){
        $sql="SELECT * FROM sys_view WHERE id=$viewid";
        $result=  $this->select($sql);
        $query_conditions='';
        $post=array();
        if(count($result)>0)
        {
            $post=$result[0]['post'];
            if(($post==null)||($post==''))
            {
                $post=array();
            }
            else
            {
               $post=str_replace("''", "'", $post);
                $post=  json_decode($post, true); 
            }
            //$query_conditions=$result[0]['query_conditions'];
            
        }
        
        $query_conditions=  $this->get_view_conditions($viewid,$userid);
        $query_array=$this->get_search_query($tableid,$post,$query_conditions);
        $query=$query_array['query_owner'];
        return $query;
    }
    
    public function get_view_conditions($viewid,$userid=null){
        if($userid==null)
        {
            $userid=  $this->get_userid();
        }
        $query_where='';
        $sql="SELECT * FROM sys_view WHERE id=$viewid";
        $result=  $this->select($sql);
        $query_conditions='';
        $post=array();
        if(count($result)>0)
        {
            $tableid=$result[0]['tableid'];
            $post=$result[0]['post'];
            if(($post==null)||($post==''))
            {
                $post=array();
            }
            else
            {
               $post=str_replace("''", "'", $post);
                $post=  json_decode($post, true); 
            }
            $query_conditions=$result[0]['query_conditions'];
            //$query_array=$this->get_search_query($tableid,$post,$query_conditions);
            $query_where=$query_conditions;//  str_replace('WHERE true AND', '', $query_array['query_where']); //TODO sistemare perchè non va bene in tutti i casi. potrebbe togliere condizioni che non deve
        }
        
        $query_where=  str_replace('$userid$', $userid, $query_where);
        return $query_where;
    }
    
    public function get_view_post($tableid,$viewid){
        $sql="SELECT post FROM sys_view WHERE id=$viewid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $post=$result[0]['post'];
            if(isempty($post))
            {
                $return=array();
            }
            else
            {
                $post=str_replace("''", "'", $post);
                $post=  json_decode($post, true);
                $return=$post;
            }
        }
        else
        {
            $return= '';
        }
        
        return $return;
    }
    
    public function save_report($tableid,$post)
    {
        $userid=  $this->get_userid();
        $report_name=$post['report_name'];
        $operations='';
        foreach ($post['operation'] as $key => $operation) {
            if(isnotempty($operation))
            {
                if($operations!='')
                {
                    $operations=$operations."|;|";
                }
                $operations=$operations.$operation;
            }
        }
        $fieldids='';
        foreach ($post['fieldid'] as $key => $fieldid) {
            if(isnotempty($fieldid))
            {
                if($fieldids!='')
                {
                    $fieldids=$fieldids."|;|";
                }
                $fieldids=$fieldids.$fieldid;
            }
        }
        $groupby=$post['groupby'];
        $layouts='';
        foreach ($post['layout'] as $key => $layout) {
            if(isnotempty($layout))
            {
                if($layouts!='')
                {
                    $layouts=$layouts."|;|";
                }
                $layouts=$layouts.$layout;
            }
        }
        $sql="INSERT INTO sys_report (name,userid,tableid,fieldid,operation,groupby,layout) VALUES ('$report_name','$userid','$tableid','$fieldids','$operations','$groupby','$layouts')";
        $this->execute_query($sql);
    }
    
    public function get_reports($tableid,$query,$view_selected_id=null)
    {
        $report_list=  $this->get_reports_list($tableid,$view_selected_id);
        $report_return=array();
        foreach ($report_list as $key => $report) {
            $report_return[$key]=  $this->get_report_result($report['reportid'], $query);
        }
        return $report_return;
    }
    
    public function get_reports_list($tableid,$view_selected_id='')
    {
        $result=array();
        if(isnotempty($view_selected_id))
        {
            $sql="SELECT * FROM sys_report JOIN sys_report_views ON sys_report.id=sys_report_views.reportid WHERE sys_report_views.viewid=$view_selected_id AND tableid='$tableid' AND userid='1' order by \"order\" ";
            $result=  $this->select($sql);
            if(count($result)==0)
            {
                $sql="SELECT *,id as reportid FROM sys_report WHERE tableid='$tableid' AND userid='1' order by \"order\" ";
                $result=  $this->select($sql);
            }
            
        }
        else
        {
            $sql="SELECT *,id as reportid FROM sys_report WHERE tableid='$tableid' AND userid='1' order by \"order\" ";
            $result=  $this->select($sql);
        }
       
       
       return $result;
    }
    
    public function get_report_result($reportid,$query)
    {
        $sql="SELECT * FROM sys_report WHERE id=$reportid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $report=$result[0];
        }
        
        $operation=$report['operation'];
        $reportid=$report['id'];
        $fieldid=$report['fieldid'];
        $groupby=$report['groupby'];
        $tableid=$report['tableid'];
        $table="user_".strtolower($tableid);
        $layout=$report['layout'];
        $name=$report['name'];
        $report_result=array();
        //$query_columns=  $this->get_query_columns($tableid);
        //$reportquery_from=  "FROM (SELECT * FROM user_".strtolower($tableid)." ".strstr($query, ' WHERE '); 
        $query="SELECT $table.* FROM $table JOIN ($query) AS search_query ON $table.recordid_=search_query.recordid_";
        $reportquery_from=" FROM ($query) as searchresult";
       // $query_from=  strstr($query, ' FROM '); 
        /*FROM (SELECT recordid_, recordstatus_, id, dataiscrizione, recordidaziende_, contatto, presente FROM user_iscrizioneeventi WHERE true) AS risultati LEFT JOIN user_iscrizioneeventi_owner ON risultati.recordid_=user_iscrizioneeventi_owner.recordid_ where ownerid_ is null OR ownerid_=1*/
        $reportquery_where=" WHERE true";
        $reportquery_groupby="";
        $reportquery_orderby="";
        
        if($operation=='somma')
        {
            
            if($groupby!='')
            {
                $reportquery_select="SELECT $groupby,ROUND(sum($fieldid)) as $fieldid";
                
                $reportquery_groupby=" GROUP BY ".$groupby;
                
                $groupby_type=$this->get_field_type($tableid, $groupby);
                if($groupby_type=='Data')
                {
                    $dbdriver= $this->get_dbdriver();
                    if($dbdriver=='postgre')
                    {
                        $reportquery_select="SELECT extract(year from $groupby) || '-' || to_char($groupby,'Mon') mm,sum($fieldid) as $fieldid";
                        $reportquery_where=$reportquery_where." AND ".$groupby." is not null";
                        $reportquery_groupby=" GROUP BY extract(year from $groupby),extract(month from $groupby),mm";
                        $reportquery_orderby="ORDER BY extract(year from $groupby),extract(month from $groupby),mm";
                    }
                    else
                    {
                        $reportquery_select="SELECT DATE_FORMAT($groupby,'%Y-%m'),sum($fieldid) as $fieldid";
                        $reportquery_where=$reportquery_where." AND ".$groupby." is not null";
                        $reportquery_groupby=" GROUP BY DATE_FORMAT($groupby,'%Y-%m')";
                        $reportquery_orderby="ORDER BY DATE_FORMAT($groupby,'%Y-%m')";
                    }
                    
                }

            }
            else
            {
                $reportquery_select=  "SELECT ROUND(sum($fieldid),2) as $fieldid";
            }
            
            
            
            
            $reportquery=$reportquery_select." ".$reportquery_from." ".$reportquery_where." ".$reportquery_groupby." ".$reportquery_orderby;
            $report_result=  $this->select($reportquery);
        }
        if($operation=='conta')
        {

            if($groupby!='')
            {
                $reportquery_select="SELECT $groupby,count($fieldid) as $fieldid";
                
                $reportquery_groupby=" GROUP BY ".$groupby;
                
                $groupby_type=$this->get_field_type($tableid, $groupby);
               /* if($groupby_type=='Data')
                {
                    $query_select="SELECT extract(year from $groupby) || '-' || to_char($groupby,'Mon') mm,count($fieldid) as $fieldid";
                    $query_where="AND ".$groupby." is not null";
                    $query_groupby=" GROUP BY extract(year from $groupby),extract(month from $groupby),mm";
                    $query_orderby="ORDER BY extract(year from $groupby),extract(month from $groupby),mm";
                }*/

            }
            else
            {
                $reportquery_select=  "SELECT count($fieldid) as $fieldid";
            }
            $reportquery=$reportquery_select." ".$reportquery_from." ".$reportquery_where." ".$reportquery_groupby." ".$reportquery_orderby;
            $report_result=  $this->select($reportquery);
            
        }
        if($operation=='custom')
        {
            if($report['custom']!=null)
            {
                $custom=$report['custom'];
                $reportquery=  str_replace('query_from', $query_from, $custom);
                $report_result=  $this->select($report_query);
            }
        }
        if($groupby!='')
        {
            $column['id']=$groupby;
            $column['desc']=$groupby;
            $groupby_type=$this->get_field_type($tableid, $groupby);
            $column['fieldtypeid']=$groupby_type;
            $columns[]=$column;
            
        }
        
        $column['id']=$fieldid;
        $column['desc']=$fieldid;
        $column['fieldtypeid']="Parola";
        $columns[]=$column;
        
        
        $return['tableid']=$tableid;
        $return['reportid']=$reportid;
        $return['name']=$name;
        $fieldtype=  $this->get_field_type($tableid, $fieldid);
        $return['fieldtype']=$fieldtype;
        $return['layout']=$layout;
        $return['columns']=$columns;
        if(count($report_result)>0)
        {
            $return['results']=$this->convert_fields_value_to_final_value($tableid,$columns,$report_result);
        }
        else
        {
            $return['results']=array();
        }
        
        return $return;
    }
    
    public function get_report_resultBAK($reportid,$query)
    {
        $sql="SELECT * FROM sys_report WHERE id=$reportid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $report=$result[0];
        }
        
        $operation=$report['operation'];
        $reportid=$report['id'];
        $fieldid=$report['fieldid'];
        $groupby=$report['groupby'];
        $tableid=$report['tableid'];
        $layout=$report['layout'];
        $name=$report['name'];
        $report_result=array();
        $query_columns=  $this->get_query_columns($tableid);
        $query_from=  "FROM (SELECT * FROM user_".strtolower($tableid)." ".strstr($query, ' WHERE '); 
       // $query_from=  strstr($query, ' FROM '); 
        /*FROM (SELECT recordid_, recordstatus_, id, dataiscrizione, recordidaziende_, contatto, presente FROM user_iscrizioneeventi WHERE true) AS risultati LEFT JOIN user_iscrizioneeventi_owner ON risultati.recordid_=user_iscrizioneeventi_owner.recordid_ where ownerid_ is null OR ownerid_=1*/
        if($operation=='somma')
        {
            $query_where="";
            $query_groupby="";
            $query_orderby="";
            if($groupby!='')
            {
                $query_select="SELECT $groupby,sum($fieldid) as $fieldid";
                
                $query_groupby=" GROUP BY ".$groupby;
                
                $groupby_type=$this->get_field_type($tableid, $groupby);
                if($groupby_type=='Data')
                {
                    $query_select="SELECT extract(year from $groupby) || '-' || to_char($groupby,'Mon') mm,sum($fieldid) as $fieldid";
                    $query_where="AND ".$groupby." is not null";
                    $query_groupby=" GROUP BY extract(year from $groupby),extract(month from $groupby),mm";
                    $query_orderby="ORDER BY extract(year from $groupby),extract(month from $groupby),mm";
                }

            }
            else
            {
                $query_select=  "SELECT sum($fieldid) as $fieldid";
            }
            
            
            
            
            $report_query=$query_select." ".$query_from." ".$query_where." ".$query_groupby." ".$query_orderby;
            $report_result=  $this->select($report_query);
        }
        if($operation=='conta')
        {
            $query_where="";
            $query_groupby="";
            $query_orderby="";
            if($groupby!='')
            {
                $query_select="SELECT $groupby,count($fieldid) as $fieldid";
                
                $query_groupby=" GROUP BY ".$groupby;
                
                $groupby_type=$this->get_field_type($tableid, $groupby);
               /* if($groupby_type=='Data')
                {
                    $query_select="SELECT extract(year from $groupby) || '-' || to_char($groupby,'Mon') mm,count($fieldid) as $fieldid";
                    $query_where="AND ".$groupby." is not null";
                    $query_groupby=" GROUP BY extract(year from $groupby),extract(month from $groupby),mm";
                    $query_orderby="ORDER BY extract(year from $groupby),extract(month from $groupby),mm";
                }*/

            }
            else
            {
                $query_select=  "SELECT count($fieldid) as $fieldid";
            }
            
            
            
            
            $report_query=$query_select." ".$query_from." ".$query_where." ".$query_groupby." ".$query_orderby;
            $report_result=  $this->select($report_query);
        }
        if($operation=='custom')
        {
            if($report['custom']!=null)
            {
                $custom=$report['custom'];
                $report_query=  str_replace('query_from', $query_from, $custom);
                $report_result=  $this->select($report_query);
            }
        }
        if($groupby!='')
        {
            $column['id']=$groupby;
            $column['desc']=$groupby;
            $groupby_type=$this->get_field_type($tableid, $groupby);
            $column['fieldtypeid']=$groupby_type;
            $columns[]=$column;
            
        }
        
        $column['id']=$fieldid;
        $column['desc']=$fieldid;
        $column['fieldtypeid']="Parola";
        $columns[]=$column;
        
        
        $return['tableid']=$tableid;
        $return['reportid']=$reportid;
        $return['name']=$name;
        $fieldtype=  $this->get_field_type($tableid, $fieldid);
        $return['fieldtype']=$fieldtype;
        $return['layout']=$layout;
        $return['columns']=$columns;
        if(count($report_result)>0)
        {
            $return['results']=$this->convert_fields_value_to_final_value($tableid,$columns,$report_result);
        }
        else
        {
            $return['results']=array();
        }
        
        return $return;
    }
    
    public function get_calendars($tableid,$query)
    {
        $calendars_list=  $this->get_calendars_list($tableid);
        $calendars=array();
        foreach ($calendars_list as $key => $calendar) {
            $calendars[$calendar['name']]=  $this->get_calendar($calendar['id'], $query);
        }
        return $calendars;
    }
    
    public function get_calendars_list($tableid)
    {
       $sql="SELECT * FROM sys_calendar WHERE tableid='$tableid' AND userid='1' ";
       $result=  $this->select($sql);
       return $result;
    }
    
    public function get_calendar($calendarid,$query){
        $sql="SELECT * FROM sys_calendar WHERE id=$calendarid";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $calendar=$result[0];
            $tableid=$calendar['tableid'];
            $tablename="user_".strtolower($tableid);
            $name=$calendar['name'];
            $field_titolo=$calendar['field_titolo'];
            $field_data=$calendar['field_data'];
            $field_orainizio=$calendar['field_orainizio'];
            $field_orafine=$calendar['field_orafine'];
        }
        $calendar_return['tableid']=$tableid;
        $calendar_return['fieldid_titolo']=$field_titolo;
        $calendar_return['fieldid_data']=$field_data;
        $calendar_return['fieldid_orainizio']=$field_orainizio;
        $calendar_return['fieldid_orafine']=$field_orafine;
        $records=array();
        
        $query_select="SELECT $tablename.recordid_,$tablename.$field_data,$tablename.$field_titolo";
        //CUSTOM ABOUT-X INIZIO
        if($tableid=='timesheet')
        {
            $query_select="SELECT $tablename.recordid_,$tablename.$field_data,$tablename.$field_titolo,$tablename.recordidaziende_";
        }
        //CUSTOM ABOUT-X FINE
        //
        //CUSTOM Dimensione Immobiliare INIZIO
        if($tableid=='agenda')
        {
            $query_select="SELECT $tablename.recordid_,$tablename.$field_data,$tablename.$field_titolo,$tablename.recordidcontatti_";
        }
        //CUSTOM Dimiensione Immobiliare FINE
        if($field_orainizio!='')
        {
            $query_select=$query_select.",$tablename.$field_orainizio";
        }
        if($field_orafine!='')
        {
            $query_select=$query_select.",$tablename.$field_orafine";
        }
        //$query_from=  strstr($query, ' FROM ');
        $query_where="WHERE $tablename.$field_data is not null";  
        
        $calendar_query=$query_select." FROM(".$query.") as recordsource JOIN $tablename ON recordsource.recordid_=$tablename.recordid_  $query_where";
        $calendar_result=  $this->select($calendar_query);
        foreach ($calendar_result as $key => $row) {
            $record=array();
            $record['recordid_']=$row['recordid_'];
            $titolo=str_replace("'", "", $row[$field_titolo]);
            $titolo=str_replace("\r\n", "", $titolo);
            $titolo=str_replace("\r", "", $titolo);
            $titolo=str_replace("\n", "", $titolo);
            $titolo=str_replace("\\", "", $titolo);
            //CUSTOM ABOUT-X INIZIO
            if($tableid=='timesheet')
            {
                $azienda_recordid=$row['recordidaziende_'];
                $azienda=  $this->get_keyfieldlink_value('timesheet', 'aziende',$azienda_recordid );
                $azienda=  str_replace("'", "", $azienda);
                $titolo=$azienda.' '.$titolo;
            }
            //CUSTOM ABOUT-X FINE
            
            //CUSTOM Dimensione Immobiliare INIZIO
            if($tableid=='agenda')
            {
                $contatto_recordid=$row['recordidcontatti_'];
                $contatto=  $this->get_keyfieldlink_value('agenda', 'contatti',$contatto_recordid );
                $contatto=  str_replace("'", "", $contatto);
                $titolo=$contatto.' '.$titolo;
            }
            //CUSTOM Dimensione Immobiliare FINE
            
            $record['titolo']= $titolo;
            $record['data']=$row[$field_data];
            $record['orainizio']='';
            $record['orafine']='';
            if($field_orainizio!='')
            {
              $record['orainizio']=$row[$field_orainizio];  
            }
            if($field_orafine!='')
            {
                $record['orafine']=$row[$field_orafine];
            }
            $records[]=$record;
        }
        $calendar_return['records']=$records;
        return $calendar_return;
    }
    
    public function salva_segnalazione($post,$files=array())
    {
        $settore=$post['settore'];
        $recordidaziende_=$post['recordid_azienda'];
        $recordidprogetti_=$post['recordid_progetto'];
        $segnalatore=$post['segnalatore'];
        $tipo=$post['tipo'];
        $priorita=$post['priorita'];
        $testo=$post['testo'];
        $testo=  str_replace("'", "''", $testo);
        $datasegnalazione=date("Y-m-d");
        $orasegnalazione=date('H:i');
                
        $recordid=$this->Sys_model->generate_recordid("segnalazioni");
        $sql="INSERT INTO user_segnalazioni(recordid_,creatorid_,tipo,stato,priorita,datasegnalazione,note,segnalatore,recordidaziende_,settore,recordidprogetti_,orasegnalazione) VALUES('$recordid',1,'$tipo','nuova','$priorita','$datasegnalazione','$testo','$segnalatore','$recordidaziende_','$settore','$recordidprogetti_','$orasegnalazione')";
        $this->Sys_model->execute_query($sql);
        
        $i=0;
        while($i < count($files['tmp_name']))
        {      
            $userid=1;
            $now=date("Y-m-d H:i:s");
            $namefolder='000';
            $new_filename=  $this->generate_filename('segnalazioni');
            $folder="archivi\\\\segnalazioni\\\\".$namefolder."\\\\";
            $original_name=pathinfo($files['name'][$i], PATHINFO_FILENAME);
            $fileext=pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            if(is_uploaded_file($files['tmp_name'][$i])){
                $path_completa="../JDocServer/archivi/segnalazioni/$namefolder/$new_filename.$fileext";
                move_uploaded_file($files['tmp_name'][$i], $path_completa);
            }

            if(file_exists($path_completa))
            {
                $sql="INSERT INTO user_segnalazioni_page (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,counter_,path_,filename_,extension_,original_name,category,filestatusid_,signed_,deleted_) VALUES ('$recordid',$userid,'$now',$userid,'$now',$i,'$folder','$new_filename','$fileext','$original_name','$category','S','N','N') ";
                $this->execute_query($sql);
            }
            else
            {
                echo "<br>CARICAMENTO E RINOMINAZIONE EFFETTUATA CON SUCCESSO";
            }
            
            $i++;
        }
    }
    
    public function get_segnalazioni($recordid_azienda,$userid)
    {
       $sql="SELECT * FROM user_segnalazioni WHERE recordidaziende_='$recordid_azienda' ORDER BY stato asc,datasegnalazione asc"; 
       $result=  $this->select($sql);
       foreach ($result as $key => $row) {
           $stato=  $this->get_descrizione_lookup('segnalazioni', 'stato', $row['stato']);
           $result[$key]['stato']=$stato;
       }
       return $result;
    }
    
    public function get_export_list($tableid)
    {
        $sql="SELECT id,name FROM sys_export WHERE tableid='$tableid'";
        $result=  $this->select($sql);
        return $result;
    }

    public function get_export_query($exportid)
    {
        $sql="SELECT * FROM sys_export WHERE id=$exportid";
        $result=  $this->select($sql);
        $return='';
        if(count($result)>0)
        {
            $return= $result[0]['query'];
        }
        return $return;
    }
    
    public function get_lastscan($num)
    {
        $return=array();
        $cliente_id=  $this->get_cliente_id();
        if($cliente_id=='NewTrends')
        {
            $sql="
                SELECT user_generico.recordid_,user_generico.creation_,id,titolo,datascan,ocr, 'generico' as tableid, path_, filename_ 
                FROM user_generico JOIN user_generico_page ON user_generico.recordid_=user_generico_page.recordid_ 
                WHERE user_generico.recordstatus_='autobatched' 
                ORDER BY id 
                LIMIT 10
                ";
        }
        if($cliente_id=='BB Kapital')
        {
            $sql="
                SELECT user_documenti.recordid_,user_documenti.creation_,id,titolo,datascan,ocr,tipodoc, 'documenti' as tableid, path_, filename_ 
                FROM user_documenti JOIN user_documenti_page ON user_documenti.recordid_=user_documenti_page.recordid_ 
                WHERE user_documenti.recordstatus_='autobatched' 
                ORDER BY id 
                LIMIT 10
                ";
        }
        $result=  $this->select($sql);
        $return=  array_merge($return,$result);
        return $return;
    }
    
    public function get_dashboards()
    {
        $sql='SELECT * FROM sys_dashboard_block where userid=1 ORDER BY "order",id';
        $result=  $this->select($sql);
        return $result;
    }
    
    public function get_impostazioni_user_settings($userid=1)
    { 
        $sql="SELECT * FROM sys_user_settings WHERE userid=$userid";
        $rows=  $this->select($sql);
        $currentvalues=array();
        foreach ($rows as $key => $row) {
            $currentvalues[$row['setting']]=$row['value'];
        }
        
        $settings=array();
        
        $settings=$this->add_setting($settings, 'firstname', '', $currentvalues, '',$userid);
        if($this->isempty($settings['firstname']['currentvalue']))
        {
            $settings['firstname']['currentvalue']=  $this->db_get_value('sys_user', 'firstname',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'lastname', '', $currentvalues, '',$userid);
        if($this->isempty($settings['lastname']['currentvalue']))
        {
            $settings['lastname']['currentvalue']=  $this->db_get_value('sys_user', 'lastname',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'description', '', $currentvalues, '',$userid);
        if($this->isempty($settings['description']['currentvalue']))
        {
            $settings['description']['currentvalue']=  $this->db_get_value('sys_user', 'description',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'username', '', $currentvalues, '',$userid);
        if($this->isempty($settings['username']['currentvalue']))
        {
            $settings['username']['currentvalue']=  $this->db_get_value('sys_user', 'username',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'password', '', $currentvalues, '',$userid);
        if($this->isempty($settings['password']['currentvalue']))
        {
            $settings['password']['currentvalue']=  $this->db_get_value('sys_user', 'password',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'email', '', $currentvalues, '',$userid);
        if($this->isempty($settings['email']['currentvalue']))
        {
            $settings['email']['currentvalue']=  $this->db_get_value('sys_user', 'email',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'telefono', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'cellulare', '', $currentvalues, '',$userid);
        
        $settings=$this->add_setting($settings, 'enablesendmail', 'true;false', $currentvalues, '',$userid);
        if($this->isempty($settings['enablesendmail']['currentvalue']))
        {
            $settings['enablesendmail']['currentvalue']=  $this->db_get_value('sys_user', 'enablesendmail',"id=$userid");
        }
        $settings=$this->add_setting($settings, 'menu_show_tables_list', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'menu_show_custom_report', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'menu_show_custom_resetaccess', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'menu_show_settings', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'mail_host', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_port', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_smtp_port', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_username', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_password', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_from_address', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'mail_from_name', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'google_calendar_id', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'image-dpi', '', $currentvalues, '600',$userid);
        $settings=$this->add_setting($settings, 'image-quality', '', $currentvalues, '65',$userid);
        $settings=$this->add_setting($settings, 'zoom32', '', $currentvalues, '1',$userid);
        $settings=$this->add_setting($settings, 'zoom', '', $currentvalues, '1.9',$userid);
        $settings=$this->add_setting($settings, 'printpage_portrait_height', '', $currentvalues, '369',$userid);
        $settings=$this->add_setting($settings, 'printpage_portrait_width', '', $currentvalues, '260',$userid);
        $settings=$this->add_setting($settings, 'printpage_portrait_top', '', $currentvalues, '10',$userid);
        $settings=$this->add_setting($settings, 'printpage_portrait_left', '', $currentvalues, '10',$userid);
        $settings=$this->add_setting($settings, 'printpage_landscape_height', '', $currentvalues, '260',$userid);
        $settings=$this->add_setting($settings, 'printpage_landscape_width', '', $currentvalues, '369',$userid);
        $settings=$this->add_setting($settings, 'printpage_landscape_top', '', $currentvalues, '10',$userid);
        $settings=$this->add_setting($settings, 'printpage_landscape_left', '', $currentvalues, '10',$userid);
        $settings=$this->add_setting($settings, 'printpage_fontsize', '', $currentvalues, '8',$userid);
        $settings=$this->add_setting($settings, 'printpage_cell_padding', '', $currentvalues, '2',$userid);
        $settings=$this->add_setting($settings, 'autoloaded_batch', '', $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'pages_show_autobatch', 'si;no', $currentvalues, 'no',$userid);
        $settings=$this->add_setting($settings, 'autobatch_filecontainer_type', 'thumbnail;details', $currentvalues, 'thumbnail',$userid);
        $settings=$this->add_setting($settings, 'wkhtml', 'wkhtmltopdfold32;wkhtmltopdf', $currentvalues, 'wkhtmltopdfold32',$userid);
        $settings=$this->add_setting($settings, 'elenco_telefonico', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'lista_disponibilita', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'dipendenti_da_chiudere', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'dipendenti_da_riaprire', 'true;false', $currentvalues, 'true',$userid);
        $groups=  $this->db_get('sys_user', '*', "description='GROUP'");
        $group_options="";
        foreach ($groups as $key => $group) {
            
            if($group_options!='')
                $group_options=$group_options.";";
            $group_options=$group_options.$group['id']."|".$group['username'];
        }
        $settings=$this->add_setting($settings, 'group', $group_options, $currentvalues, '',$userid);

        return $settings;
    } 
    
    public function get_impostazioni_table_settings($tableid,$userid=1)
    { 
        $sql="SELECT * FROM sys_user_table_settings WHERE userid=$userid AND tableid='$tableid'";
        $rows=  $this->select($sql);
        $currentvalues=array();
        foreach ($rows as $key => $row) {
            $currentvalues[$row['settingid']]=$row['value'];
        }
        
        $cliente_id=$this->get_cliente_id();
        
        
        $settings=array();
        
        $settings=$this->add_setting($settings, 'edit', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'risultati_edit', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'autosave', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'delete', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'hidden', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'menu', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'icon_type', 'fontawesome;material', $currentvalues, 'fontawesome',$userid);
        $settings=$this->add_setting($settings, 'icon', '', $currentvalues, 'database',$userid);
        $settings=$this->add_setting($settings, 'customview_list', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'customview_card', 'true;false', $currentvalues, 'false',$userid);
        
        $settings=$this->add_setting($settings, 'scheda_layout', 'standard_dati;standard_allegati;allargata', $currentvalues, 'standard_dati',$userid);
        $settings=$this->add_setting($settings, 'scheda_mostratutti', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'popup_layout', 'standard_dati;standard_allegati;allargata', $currentvalues, 'standard_dati',$userid);
        $settings=$this->add_setting($settings, 'popup_width', '30;60;90', $currentvalues, '60',$userid);
        $settings=$this->add_setting($settings, 'scheda_record_width', '25;42;48;57;98', $currentvalues, '25',$userid);
        $settings=$this->add_setting($settings, 'allargata_dati_width', '', $currentvalues, '50',$userid);
        $settings=$this->add_setting($settings, 'scheda_ricerca_display', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'scheda_ricerca_width', '20;42;', $currentvalues, '20',$userid);
        $settings=$this->add_setting($settings, 'scheda_ricerca_default', 'filtri;ricerche_salvate', $currentvalues, 'filtri',$userid);
        $settings=$this->add_setting($settings, 'ricerca_lockedview', 'true;false', $currentvalues, 'false',$userid);
        
        $settings=$this->add_setting($settings, 'risultati_limit', '', $currentvalues, '50',$userid);
        $settings=$this->add_setting($settings, 'risultati_layout', 'righe;table;preview;badge;report;calendar', $currentvalues, 'righe',$userid);
        $settings=$this->add_setting($settings, 'risultati_width', '42;48;57;98', $currentvalues, '57',$userid);
        $settings=$this->add_setting($settings, 'risultati_border', '1px solid transparent;1px solid #dedede', $currentvalues, '1px solid transparent',$userid);
        $settings=$this->add_setting($settings, 'risultati_font_size', '6;8;10;12;14;16;18', $currentvalues, '14',$userid);
        $settings=$this->add_setting($settings, 'risultati_open', 'right;down;popup', $currentvalues, 'right',$userid);
        $settings=$this->add_setting($settings, 'risultati_new', 'right;down;popup', $currentvalues, 'right',$userid);
        $settings=$this->add_setting($settings, 'risultati_order', 'asc;desc;', $currentvalues, 'desc',$userid);
        $settings=$this->add_setting($settings, 'risultati_showreport', 'true;false;', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'risultati_showcalendar', 'true;false;', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'risultati_anteprima_aspectratio', '2:3;3:2;16:9', $currentvalues, '2:3',$userid);
        $settings=$this->add_setting($settings, 'risultati_stampa_elenco_orientamento', 'portrait;landscape;', $currentvalues, 'portrait',$userid);
        $settings=$this->add_setting($settings, 'linked_layout', 'righe;preview;badge', $currentvalues, 'righe',$userid);
        $settings=$this->add_setting($settings, 'linked_open', 'right;down;popup', $currentvalues, 'down',$userid);
        $settings=$this->add_setting($settings, 'linked_new', 'right;down;popup', $currentvalues, 'popup',$userid);
        $settings=$this->add_setting($settings, 'linked_rows', '5;10;15;20;25;30', $currentvalues, '5',$userid);
        $settings=$this->add_setting($settings, 'linked_label_opened', 'true;false', $currentvalues, 'false',$userid);
        $settings=$this->add_setting($settings, 'pages_display', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'pages_fileupload_display', 'true;false', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'pages_view', 'thumbnail;name;detail', $currentvalues, 'thumbnail',$userid);
        $settings=$this->add_setting($settings, 'pages_scheda_layout', 'list;grid;hidden', $currentvalues, 'list',$userid);
        $settings=$this->add_setting($settings, 'pages_popup_layout', 'list;grid;hidden', $currentvalues, 'list',$userid);
        $settings=$this->add_setting($settings, 'pages_thumbnail_show', 'true;false;', $currentvalues, 'true',$userid);
        $settings=$this->add_setting($settings, 'pages_thumbnail_aspectratio', '2:3;3:2;16:9', $currentvalues, '2:3',$userid);
        $settings=$this->add_setting($settings, 'pages_preview_aspectratio', '2:3;3:2;16:9', $currentvalues, '2:3',$userid);
        $settings=$this->add_setting($settings, 'allegati_filecontainer_type', 'thumbnail;details', $currentvalues, 'thumbnail',$userid);
        $settings=$this->add_setting($settings, 'stampa_elenco_limit_number', '', $currentvalues, '20',$userid);
        $settings=$this->add_setting($settings, 'default_save', 'salva;salva e chiudi;salva e nuovo;salva e nuovo-salva e chiudi;allega salva e nuovo;salva e ripeti', $currentvalues, 'salva',$userid);
        $fields=  $this->db_get('sys_field', '*', "tableid='$tableid'", 'ORDER BY fieldid');
        $dem_mail_field_options="";
        foreach ($fields as $key => $field) {
            
            if($dem_mail_field_options!='')
                $dem_mail_field_options=$dem_mail_field_options.";";
            $dem_mail_field_options=$dem_mail_field_options.$field['fieldid'];
        }
        $settings=$this->add_setting($settings, 'dem_mail_field', $dem_mail_field_options, $currentvalues, '',$userid);
        $settings=$this->add_setting($settings, 'fields_autoscroll', 'true;false', $currentvalues, 'false',$userid);
        
        $settings=$this->add_setting($settings, 'col_s', '', $currentvalues, '3',$userid);
        $settings=$this->add_setting($settings, 'col_m', '', $currentvalues, '3',$userid);
        $settings=$this->add_setting($settings, 'col_l', '', $currentvalues, '3',$userid);
        
       

        return $settings;
    }
    
    public function get_impostazioni_field_settings($tableid,$fieldid,$userid=1)
    {
        $sql="SELECT * FROM sys_user_field_settings WHERE tableid='$tableid' and fieldid='$fieldid' AND userid=$userid";
        $rows=  $this->select($sql);
        $currentvalues=array();
        foreach ($rows as $key => $row) {
            $currentvalues[$row['settingid']]=$row['value'];
        }
        $lookuptableid=$this->Sys_model->db_get_value('sys_field','lookuptableid',"tableid='$tableid' AND fieldid='$fieldid'");
        $lookup_options=$this->Sys_model-> get_lookuptable($lookuptableid);
        $lookup_options_string=";";
        foreach ($lookup_options as $key => $lookup_option) {
            $lookup_options_string=$lookup_options_string.";".$lookup_option['itemcode'];
        }
        $settings=array();
        $settings=$this->add_setting($settings, 'nascosto', 'true;false', $currentvalues, 'false');
        $settings=$this->add_setting($settings, 'calcolato', 'true;false', $currentvalues, 'false');
        $settings=$this->add_setting($settings, 'obbligatorio', 'true;false', $currentvalues, 'false');
        $settings=$this->add_setting($settings, 'default', $lookup_options_string, $currentvalues, '');
        $settings=$this->add_setting($settings, 'custom_1824_curriculumpubblico', 'true;false', $currentvalues, 'true');
       

        return $settings;
    }
    
    
    public function direct_update($tableid,$recordid,$post)
    {
        $fieldid=$post['field']['fieldid'];
        $value=$post['field']['value'];
        $table=  "user_".strtolower($tableid);
        $sql="
            UPDATE $table
            SET $fieldid='$value'
            WHERE recordid_='$recordid'
            ";
        $this->execute_query($sql);
        
    }
    public function get_fields_ordered_groupby_label($tableid)
    {
        /*$sublabels=  $this->db_get('sys_table_sublabel','*',"tableid='$tableid'",'ORDER BY sublabelorder');
        foreach ($sublabels as $key => $sublabel) {
            $fields_return[$sublabel['sublabelname']]=array();
        }*/
        $fields_return=array();
        $sql="
            select sys_field.*,sys_user_order.fieldorder AS ordine
            FROM
            sys_field JOIN sys_user_order 
            ON sys_field.fieldid=sys_user_order.fieldid AND sys_field.tableid=sys_user_order.tableid
            WHERE sys_field.tableid='$tableid' AND sys_user_order.userid=1 AND typepreference='campiInserimento'
            ORDER BY \"ordine\"
            ";
        $fields=  $this->select($sql);
        foreach ($fields as $key => $field) {
            $fieldid=$field['fieldid'];
            $field['campiricerca']=false;
            $campiricerca=$this->db_get_row('sys_user_order','*',"userid=1 AND tableid='$tableid' AND fieldid='$fieldid' AND typepreference='campiricerca'");
            if($campiricerca!=null)
            {
                $field['campiricerca']=true;
            }
            
            $fields_return[$field['label']][$field['sublabel']][]=$field;
        }
        return $fields_return;
    }
    
    public function get_fields_ordered_by_name($tableid)
    {
        $fields_return=array();
        $sql="
            select sys_field.*
            FROM
            sys_field 
            WHERE sys_field.tableid='$tableid'
            ORDER BY \"fieldid\"
            ";
        $fields=  $this->select($sql);
        foreach ($fields as $key => $field) {
            $field['campiricerca']=true;
            $fields_return[$field['fieldid']]=$field;
        }
        return $fields_return;
    }
    
    public function add_setting($settings,$idsetting,$options,$currentvalues,$default,$userid=1)
    {
        $settings[$idsetting]['options']=array();
        $options_array=  explode(';', $options);
        foreach ($options_array as $key => $option) {
                $option_array=  explode('|', $option);
                if(count($option_array)>1)
                {
                    $settings[$idsetting]['options'][$option_array[0]]=$option_array[1];  
                }
                else
                {
                    $settings[$idsetting]['options'][$option_array[0]]=$option_array[0]; 
                }
                          
        }
        
        $settings[$idsetting]['currentvalue']='';
        if(array_key_exists($idsetting, $currentvalues))
        {
                $settings[$idsetting]['currentvalue']=$currentvalues[$idsetting];
        }
        else
        {
            if($userid==1)
            {
                $settings[$idsetting]['currentvalue']=$default;
            }
        }
        return $settings;
    }
    
    public function get_table_settings($tableid,$userid=null)
    {
        if($userid==null)
        {
            $userid=  $this->get_userid();
        }
        
        $settings=array();
        //settings superuser(default per tutti gli utenti)
        $impostazioni_table_settings=  $this->get_impostazioni_table_settings($tableid,1);
        foreach ($impostazioni_table_settings as $key => $setting) {
            $settings[$key]=$setting['currentvalue'];
        }
        
        //settings gruppi
        $groups=  $this->Sys_model->db_get('sys_user_settings','*',"userid=$userid AND setting='group'");
        foreach ($groups as $key => $group) {
            $groupuserid=$group['value'];
            $impostazioni_table_settings=  $this->get_impostazioni_table_settings($tableid,$groupuserid);
            foreach ($impostazioni_table_settings as $key => $setting) 
            {
                if(isnotempty($setting['currentvalue']))
                {
                     $settings[$key]=$setting['currentvalue'];
                }
               
            }
        }
        
    
        // settings utente
        $impostazioni_table_settings=  $this->get_impostazioni_table_settings($tableid,$userid);
        foreach ($impostazioni_table_settings as $key => $setting) 
        {
            if(isnotempty($setting['currentvalue']))
            {
                $settings[$key]=$setting['currentvalue'];
            }
        }
    

        
        

        return $settings;
    }
    
    public function get_table_setting($tableid,$settingid)
    {
        $impostazioni_table_settings=  $this->get_impostazioni_table_settings($tableid);
        $return=false;
        if(array_key_exists($settingid, $impostazioni_table_settings))
        {
            $return=$impostazioni_table_settings[$settingid]['currentvalue'];
        }
        return $return;
    }
    
    
    
    public function reset_qualifiche()
    {
        $sql="UPDATE user_candid SET qualifica=''";
        $this->execute_query($sql);
    }
    
    //CUSTOM WW
    public function generate_qualifiche($recordid=null)
    {
        if($recordid==null)
        {
            $sql="SELECT recordid_ FROM user_candid where qualifica='' OR qualifica is null";
            $candidati=$this->select($sql);
            $counter=0;
            foreach ($candidati as $key => $candidato) 
            {
                $recordid=$candidato['recordid_'];
                $qualifica=$this->custom_generate_qualifica($recordid);
                $qualifica=str_replace("'", "''", $qualifica);
                if($this->isnotempty($qualifica))
                {
                    $counter++;
                    $sql="UPDATE user_candid SET qualifica='$qualifica' WHERE recordid_='$recordid'";
                    $this->execute_query($sql); 
                }
            }
            return $counter;
        }
        else
        {
            $qualifica=$this->custom_generate_qualifica($recordid);
            $qualifica=str_replace("'", "''", $qualifica);
            if($this->isnotempty($qualifica))
            {
                $sql="UPDATE user_candid SET qualifica='$qualifica' WHERE recordid_='$recordid'";
                $this->execute_query($sql); 
            }
        }
    }
    
    //CUSTOM WW
    public function generate_stato($recordid=null)
    {
        if($recordid==null)
        {
            $sql="SELECT recordid_ FROM user_candid where stato='' OR stato is null";
            $candidati=$this->select($sql);
            $counter=0;
            foreach ($candidati as $key => $candidato) 
            {
                $recordid=$candidato['recordid_'];
                $canddisponibilita=  $this->db_get_row('user_canddisponibilita', 'statodisp,wwws', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
                if($canddisponibilita!=null)
                {

                    $statodisp=$canddisponibilita['statodisp'];
                    $wwws=$canddisponibilita['wwws'];
                    $stato=$statodisp;
                    if(strtoupper($stato)=='W')
                    {
                        if(strtoupper($wwws)=='WW')
                        {
                            $stato='WW';
                        }
                        if(strtoupper($wwws)=='WS')
                        {
                            $stato='WS';
                        }
                    }
                    if($this->isnotempty($stato))
                    {
                        $counter++;
                        $sql="UPDATE user_candid SET stato='$stato' WHERE recordid_='$recordid'";
                        $this->execute_query($sql);
                    }
                }
            }
            return $counter;
        }
        else
        {
            $canddisponibilita=  $this->db_get_row('user_canddisponibilita', 'statodisp,wwws', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
            if($canddisponibilita!=null)
            {

                $statodisp=$canddisponibilita['statodisp'];
                $wwws=$canddisponibilita['wwws'];
                $stato=$statodisp;
                if(strtoupper($stato)=='W')
                {
                    //$stato='WW';
                    if(strtoupper($wwws)=='WW')
                    {
                        $stato='WW';
                    }
                    if(strtoupper($wwws)=='WS')
                    {
                        $stato='WS';
                    }
                }
                if($this->isnotempty($stato))
                {
                    $sql="UPDATE user_candid SET stato='$stato' WHERE recordid_='$recordid'";
                    $this->execute_query($sql);
                }
            }
        }
    }
    
    //CUSTOM WW
    public function generate_validato($recordid=null)
    {
        if($recordid==null)
        {
            $sql="SELECT recordid_ FROM user_candid where validato='' OR validato is null";
            $candidati=$this->select($sql);
            $counter=0;
            foreach ($candidati as $key => $candidato) 
            {

                $recordid=$candidato['recordid_'];
                $validato=  $this->db_get_value('user_candcolloquio', 'validato', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
                if($this->isnotempty($validato))
                {
                    $counter++;
                    $sql="UPDATE user_candid SET validato='$validato' WHERE recordid_='$recordid'";
                    $this->execute_query($sql); 
                }
            }
            return $counter;
        }
        else
        {
            $validato=  $this->db_get_value('user_candcolloquio', 'validato', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
            if($this->isnotempty($validato))
            {
                $sql="UPDATE user_candid SET validato='$validato' WHERE recordid_='$recordid'";
                $this->execute_query($sql); 
            }
        }
    }
    
    //CUSTOM WW
    public function generate_giudizi($recordid=null)
    {
        if($recordid==null)
        {
            $sql="SELECT recordid_ FROM user_candid where giudizio='' OR giudizio is null";
            $candidati=$this->select($sql);
            $counter=0;
            foreach ($candidati as $key => $candidato) 
            {
                $recordid=$candidato['recordid_'];
                $giudizio=  $this->db_get_value('user_candcolloquio', 'giudizio', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
                $giudizio=str_replace("'", "''", $giudizio);
                if($this->isnotempty($giudizio))
                {
                    $counter++;
                    $sql="UPDATE user_candid SET giudizio='$giudizio' WHERE recordid_='$recordid'";
                    $this->execute_query($sql); 
                }
            }
            return $counter;
        }
        else
        {
            $giudizio=  $this->db_get_value('user_candcolloquio', 'giudizio', "recordidcandid_='$recordid'",'ORDER BY recordid_ DESC');
            $giudizio=str_replace("'", "''", $giudizio);
            if($this->isnotempty($giudizio))
            {
                $sql="UPDATE user_candid SET giudizio='$giudizio' WHERE recordid_='$recordid'";
                $this->execute_query($sql); 
            }
        }
    }
    

    //CUSTOM WW
    public function generate_eta($recordid=null)
    {
        if($recordid==null)
        {
            $sql="
            UPDATE user_candid
            SET eta=null
            ";
            $this->execute_query($sql);
            $counter=0;
            $sql="SELECT recordid_,datanasc FROM user_candid where eta='' OR eta is null";
            $candidati=$this->select($sql);
            foreach ($candidati as $key => $candidato) 
            {
                $recordid=$candidato['recordid_'];
                $datanasc=$candidato['datanasc'];
                $eta=  $this->custom_generate_eta($datanasc);
                if($this->isnotempty($eta))
                {
                    $counter++;
                    $sql="UPDATE user_candid SET eta='$eta' WHERE recordid_='$recordid'";
                    $this->execute_query($sql); 
                }
            }
            return $counter;
        }
        else
        {
            $candidato=$this->db_get_row("user_candid", "*", "recordid_='$recordid'");
            $datanasc=$candidato['datanasc'];
            $eta=  $this->custom_generate_eta($datanasc);
            if($this->isnotempty($eta))
            {
                $sql="UPDATE user_candid SET eta='$eta' WHERE recordid_='$recordid'";
                $this->execute_query($sql); 
            }
        }
    }
    
    //CUSTOM WW
    public function get_candidati_bollettino($codicebollettino)
    {
      $sql="SELECT user_candid.*  
            FROM user_candid JOIN user_candbollettino ON user_candid.recordid_=user_candbollettino.recordidcandid_
            WHERE user_candbollettino.codboll=$codicebollettino
    ";  
      $result=  $this->select($sql);
      return $result;
    }
    
    public function reset_bollettino_candidati($codicebollettino)
    {
        $sql="
            DELETE FROM user_stampebollettini_candidati
            WHERE codicebollettino=$codicebollettino
            ";
        $this->execute_query($sql);
    }
    //CUSTOM WW
    public function set_bollettino_candidato($codicebollettino,$candidato_bollettino)
    {
        $recordid=  $this->generate_recordid('stampebollettini_candidati');
        $codicebollettino=$codicebollettino;
        $recordid_candidato=$candidato_bollettino['recordid_'];
        $idcandidato=$candidato_bollettino['id'];
        $cognome=$candidato_bollettino['cognome'];
        $cognome=  str_replace("'", "''", $cognome);
        $qualifiche=  $this->custom_generate_qualifica_bollettino($candidato_bollettino);
        $qualifiche=  str_replace("'", "''", $qualifiche);
        $qualifiche=  strtoupper($qualifiche);
        
        $profiloflash=  $this->custom_generate_profiloflash($codicebollettino,$candidato_bollettino);
        
        $profilo=  $this->db_get_value('user_candbollettino', 'profilo', "recordidcandid_='$recordid_candidato' AND codboll='$codicebollettino'");
        $pchiave= $this->db_get_value('user_candbollettino', 'pchiave', "recordidcandid_='$recordid_candidato' AND codboll='$codicebollettino'");
        $pchiave=  str_replace("'", "''", $pchiave);
        $sql="
            INSERT INTO user_stampebollettini_candidati
            (recordid_,creatorid_,codicebollettino,idcandidato,cognome,qualifiche,profiloflash,profilo,pchiave,recordidcandid_)
            VALUES
            ('$recordid',1,$codicebollettino,$idcandidato,'$cognome','$qualifiche','$profiloflash','$profilo','$pchiave','$recordid_candidato')
            ";
        $this->execute_query($sql);
    }
    
    
    //CUSTOM WW
    public function get_bollettino_candidati($codicebollettino,$profilo)
    {
        $sql="
            SELECT *
            FROM user_stampebollettini_candidati
            WHERE codicebollettino=$codicebollettino AND profilo='$profilo'
            ORDER BY idcandidato ASC
            ";
        $result=  $this->select($sql);
        return $result;
    }
    
    public function custom_generate_profiloflash($codicebollettino,$candidato)
    {
        $recordid_candidato=$candidato['recordid_'];
        $candbollettino= $this->db_get_row('user_candbollettino', '*', "recordidcandid_='$recordid_candidato' AND codboll='$codicebollettino'");
        if($candidato['id']==31215)
        {
            $test='test';
        }
        $anniesperienza=$candbollettino['anniesperienza'];
        if($anniesperienza==1)
        {
           $esperienza='1 anno di esperienza'; 
        }
        else
        {
            $esperienza="$anniesperienza anni di esperienza";
        }
        $pchiave=$candbollettino['pchiave'];
        $stringa_lingue=$this->custom_generate_lingua_bollettino($recordid_candidato);
        $datanasc=$candidato['datanasc'];
        $anni=  $this->custom_generate_eta($datanasc);
        $canddisponibilita= $this->db_get_row('user_canddisponibilita', '*', "recordidcandid_='$recordid_candidato' ");
        $disponibile= $canddisponibilita['statodisp'];
        $cercaft=$canddisponibilita['lavft'];
        $cercaft2='';
        if(($cercaft=='E')||($cercaft==null)||($cercaft==''))
            $cercaft2='Cerca lavori fissi e temporanei';
        if($cercaft=='F')
            $cercaft2='Cerca lavori fissi';
        if($cercaft=='T')
            $cercaft2='Cerca lavori temporanei';
        $return= "$esperienza / $pchiave / $stringa_lingue / $cercaft2";
        $return=  str_replace("'", "''", $return);
        return $return;
    }
    
    //CUSTOM WW
    public function custom_generate_lingua_bollettino($recordid_candidato)
    {
        $lingue_array=  $this->db_get('user_candlingue', '*', "recordidcandid_='$recordid_candidato' ");
        $lingue='';
        $lingue_raggruppate=array();
        $lingue_raggruppate['Madrelingua']=array();
        $lingue_raggruppate['Ottimo']=array();
        $lingue_raggruppate['Buono']=array();
        $lingue_raggruppate['Sufficiente']=array();
        $stringa_lingue='';
        foreach ($lingue_array as $key => $lingua) {
            $lingua_descrizione=$this->get_lookup_table_item_description('LINGUA', $lingua['lingua']);
            $lingua_livello_parlato=$this->get_lookup_table_item_description('CONLING2', $lingua['parlato']);
            $lingue_raggruppate[$lingua_livello_parlato][]=ucfirst($lingua_descrizione);
        }
        foreach ($lingue_raggruppate as $livello => $lingue_livello) {
            $x=0;
            if(count($lingue_livello)>0)
            {
            foreach ($lingue_livello as $key => $lingua) 
            {
                if($x>0)
                {
                    $stringa_lingue=$stringa_lingue.", ";
                }
                $stringa_lingue=$stringa_lingue.$lingua;
                $x++;
            }
            $stringa_lingue=$stringa_lingue." ($livello) ";
            }
        }
        return $stringa_lingue;
    }
    
    public function get_mails($tableid,$tipoinvio,$query)
    {
        $result=array();
        if($tipoinvio=='generico')
        {
            $query= "SELECT email FROM ( $query ) as recordsource JOIN user_aziend ON recordsource.recordid_=user_aziend.recordid_ WHERE email!='' AND email is not null";
            $result=  $this->Sys_model->select($query);
        }
        if($tipoinvio=='bollettino')
        {
            $query= "SELECT user_azriferimenti.email FROM ( $query ) as recordsource JOIN user_aziend ON recordsource.recordid_=user_aziend.recordid_ JOIN user_azriferimenti ON user_azriferimenti.recordidaziend_=user_aziend.recordid_ WHERE user_azriferimenti.inviobollettino='Si'";
            $result=  $this->Sys_model->select($query);
        }
        if($tipoinvio=='pushup')
        {
            $query= "SELECT user_azriferimenti.email FROM ( $query ) as recordsource JOIN user_aziend ON recordsource.recordid_=user_aziend.recordid_ JOIN user_azriferimenti ON user_azriferimenti.recordidaziend_=user_aziend.recordid_ WHERE user_azriferimenti.inviopushup='Si'";
            $result=  $this->Sys_model->select($query);
        }
        
        
        return $result;
    }
    
    public function get_utenti()
    {
        return $this->db_get('sys_user', '*', 'true', 'ORDER BY username ASC');
    }
    
    
    function add_lookup_settori()
    {
        $sql="
                INSERT INTO sys_lookup_table
                (description,tableid,itemtype,codelen,desclen,numitems)
                VALUES
                ('settore','settore','Carattere',255,255,0)
                ";
        $this->execute_query($sql);
        $settori=  $this->db_get('user_wsetto');
        foreach ($settori as $key => $settore) {
            $itemcode=$settore['codsettor'];
            $itemdesc=$settore['settore'];
            $sql="
                INSERT INTO sys_lookup_table_item
                (lookuptableid,itemcode,itemdesc)
                VALUES
                ('settore','$itemdesc','$itemdesc')
                ";
            $this->execute_query($sql);
        }
    }
    
    function add_lookup_subsettori()
    {
        
    }
    
    function add_lookup_professioni()
    {
        
    }
    
    //CUSTOM WW
    function get_destinatari_pushup($recordid)
    {
        $destinatari=array();
        $mail_escluse=array();
        $sql="
            SELECT *
            FROM user_criteripushup
            WHERE recordidcandid_='$recordid'
            ";
        $criteripushup=  $this->select($sql);
        foreach ($criteripushup as $key => $criteriopushup) 
        {
            $settore=$criteriopushup['settore'];
            $settore=  $this->db_get_value('sys_lookup_table_item', 'itemdesc', "lookuptableid='WSETTA' AND itemcode='$settore'");
            $subsettore=$criteriopushup['subsettore'];
            $subsettore=  $this->db_get_value('sys_lookup_table_item', 'itemdesc', "lookuptableid='WSUBSA' AND itemcode='$subsettore'");
            $esclusa=$criteriopushup['esclusa'];
            $check_settore=$this->isnotempty($settore);
            $check_subsettore=$this->isnotempty($subsettore);
            if(($check_settore)||($check_subsettore))
            {
                $where_settore='TRUE';
                $where_subsettore='TRUE';
                if($settore!='')
                {
                    $where_settore="user_azsett.settore='$settore'";
                }
                if($subsettore!='')
                {
                    $where_subsettore="user_azsett.subsettor='$subsettore'";
                }
                $sql="
                SELECT DISTINCT(user_azriferimenti.email)
                FROM user_aziend JOIN user_azsett on user_aziend.recordid_=user_azsett.recordidaziend_ JOIN user_azriferimenti on user_aziend.recordid_=user_azriferimenti.recordidaziend_ 
                where TRUE AND $where_settore AND $where_subsettore AND user_azriferimenti.inviopushup='Si'
                ";
                $result=$this->select($sql);
                $nuovi_destinatari=array();
                foreach ($result as $key => $value) {
                    $nuovi_destinatari[]=$value['email'];
                }
                $destinatari=  array_merge($destinatari,$nuovi_destinatari);
            
                
            }
            if($this->isnotempty($esclusa))
            {
                
                $sql="
                    SELECT DISTINCT(user_azriferimenti.email)
                    FROM user_aziend JOIN user_azriferimenti on user_aziend.recordid_=user_azriferimenti.recordidaziend_ 
                    WHERE user_aziend.recordid_='$esclusa' AND user_azriferimenti.inviopushup='Si'
                    ";
                $rows=  $this->select($sql);
                foreach ($rows as $key => $row) {
                    $mail_escluse[]=$row['email'];
                    
                }
            }
        }
        
        foreach ($mail_escluse as $key => $mail_esclusa) {
            if(($key = array_search($mail_esclusa, $destinatari)) !== false) 
            {
                unset($destinatari[$key]);
            }
        }
        
        return $destinatari;
    }
    
    //CUSTOM WW
    function get_dati_pushup($recordidcandid)
    {
        $last_bollettino=  $this->db_get_row('user_candbollettino', '*', "recordidcandid_='$recordidcandid'", 'ORDER BY recordid_ DESC');
        $last_disponibilita=  $this->db_get_row('user_canddisponibilita', '*', "recordidcandid_='$recordidcandid'", 'ORDER BY recordid_ DESC');
        
        $dati_pushup['settore']=$last_bollettino['profilo'];
        $dati_pushup['id']=  $this->db_get_value('user_candid', 'id', "recordid_='$recordidcandid'");
        //$qualifica=$this->db_get_value('user_skill', 'qualifica', "recordidcandid_='$recordidcandid' AND bollettino='Si'","ORDER BY recordid_ DESC");
        //$dati_pushup['qualifica']=  strtoupper($qualifica);
        $candidato['recordid_']=$recordidcandid;
        $qualifica=  $this->custom_generate_qualifica_bollettino($candidato);
        $qualifica=  str_replace("'", "''", $qualifica);
        $dati_pushup['qualifica']=  strtoupper($qualifica);
        
        $dati_pushup['pchiave']=$last_bollettino['pchiave'];
        $dati_pushup['lingue']=  $this->custom_generate_lingua_bollettino($recordidcandid);
        
        $fissotemporaneo="";
        $lavft=$last_disponibilita['lavft'];
        if($lavft=='F')
            $fissotemporaneo='fisso';
        if($lavft=='T')
            $fissotemporaneo='temporaneo';
        if($lavft=='E')
            $fissotemporaneo='fisso o temporaneo';
        
        $giornata="";
        $disporar=$last_disponibilita['disporar'];
        if($disporar='G')
                $giornata="su giornata";
        
        $turni="";
        $dispturni=$last_disponibilita['dispturni'];
        if($dispturni=='2T')
            $turni='2 turni';
        if($dispturni=='3T')
            $turni='3 turni';
        
        
        
        $disponibilita="Disponibilità da concordare. cerca $fissotemporaneo, disponibilità $giornata $turni";
        $dati_pushup['disponibilita']=$disponibilita;
        
        $percocc=$last_disponibilita['percocc'];
        $percentuale=  $this->get_itemdesc_lookuptable('PERCOC',$percocc);
         
        $dati_pushup['percentuale_lavorativa']=$percentuale;
        return $dati_pushup;
    }
    
    function save_pushup($recordidcandid,$mail)
    {
        $data=date('Y-m-d');
        $consulente=  $this->get_userid();
        $settori='';
        $escluse='';
        $sql="
            SELECT *
            FROM user_criteripushup
            WHERE recordidcandid_='$recordidcandid'
            ";
        $criteripushup=  $this->select($sql);
        foreach ($criteripushup as $key => $criteriopushup) 
        {
            $settore=$criteriopushup['settore'];
            $subsettore=$criteriopushup['subsettore'];
            $recordidesclusa=$criteriopushup['esclusa']; 
            $ragsoc_esclusa='';
            if($this->isnotempty($recordidesclusa))
            {
                $ragsoc_esclusa=$this->db_get_value('user_aziend', 'ragsoc', "recordid_='$recordidesclusa'");
            }
            
            if(($this->isnotempty($settore))||($this->isnotempty($subsettore)))
            {
                if($settori!='')
                    $settori=$settori.' , ';
                $settori=$settori.$settore.' '.$subsettore;
            }
            
            if($ragsoc_esclusa!='')
            {
                if($escluse!='')
                    $escluse=$escluse.' , ';
                $escluse=$escluse.$ragsoc_esclusa;
            }
            
            
                
        }
        $indirizzi=$mail['mailbcc'];
        $recordid=  $this->generate_recordid('pushup');
        $escluse=str_replace("'","''",$escluse);
        $sql="
            INSERT INTO user_pushup (recordid_,data,consulente,settori,escluse,indirizzi,recordidcandid_)
            VALUES ('$recordid','$data','$consulente','$settori','$escluse','$indirizzi','$recordidcandid')
            ";
        $this->execute_query($sql);
    }
    function push_mail_queue_smart($mailfrom_userid=1,$mailto='',$mailsubject='',$mailbody='',$mailcc='',$mailbcc='')
    {
        $recordid=  $this->generate_recordid('mail_queue');
        $id= $this->generate_seriale('mail_queue', 'id');
        $mailbody= str_replace("'", "''", $mailbody);
        $sql="
            INSERT INTO user_mail_queue (recordid_,id,mailfrom_userid,mailto,mailcc,mailbcc,mailsubject,mailbody,status)
            VALUES ('$recordid','$id','$mailfrom_userid','$mailto','$mailcc','$mailbcc','$mailsubject','$mailbody','dainviare')
            ";
        $this->execute_query($sql);

        return $recordid;
    }
        
    function push_mail_queue($mail)
    {
        $mailfrom_userid='';
        if(array_key_exists('mailfrom_userid', $mail))
            $mailfrom_userid=$mail['mailfrom_userid'];
        
        $mailto='';
        if(array_key_exists('mailto', $mail))
            $mailto=$mail['mailto'];

        $mailcc='';
        if(array_key_exists('mailcc', $mail))
            $mailcc=$mail['mailcc'];

        $mailbcc='';
        if(array_key_exists('mailbcc', $mail))
            $mailbcc=$mail['mailbcc'];

        $mailsubject='';
        if(array_key_exists('mailsubject', $mail))
            $mailsubject=$mail['mailsubject'];

        $mailbody='';
        if(array_key_exists('mailbody', $mail))
        {
            $mailbody=$mail['mailbody'];
            $mailbody=str_replace("'", "''", $mailbody);
            //$mailbody=  nl2br($mailbody);
        }

        $mailattachment='';
        if(array_key_exists('mailattachment', $mail))
            $mailattachment=$mail['mailattachment'];
        
        //CUSTOM SEA TRADE inizio
        $linkedtable="";
        $linkedrecordid='';
        if(array_key_exists('linkedrecordid', $mail))
        {
            $linkedrecordid=$mail['linkedrecordid'];
            $linkedrecordid=",'$linkedrecordid'";
            $linkedtable=",recordiddocumenti_";
        }
        //CUSTOM SEA TRADE fine
        
        
        $recordid=  $this->generate_recordid('mail_queue');
        $sql="
            INSERT INTO user_mail_queue (recordid_,mailfrom_userid,mailto,mailcc,mailbcc,mailsubject,mailbody,mailattachment,status$linkedtable)
            VALUES ('$recordid','$mailfrom_userid','$mailto','$mailcc','$mailbcc','$mailsubject','$mailbody','$mailattachment','dainviare'$linkedrecordid)
            ";
        $this->execute_query($sql);
        
        if(array_key_exists('mail_jdocattachment', $mail))
        {
            $userid=$this->session->userdata('userid');
            $now = date('Y-m-d H:i:s');
            $tableid='mail_queue';
            $sql="SELECT namefolder,numfilesfolder FROM sys_table where id='$tableid'";
            $result=  $this->select($sql);
            if(count($result)==1)
            {
                $namefolder=$result[0]['namefolder'];
                $numfilesfolder=$result[0]['numfilesfolder'];
            }

            $path=$mail['mail_jdocattachment'];
            $original_name='allegato';
            $fileext='pdf';
            $fullpath="../JDocServer/$path";

                        $new_filename=  $this->generate_filename($tableid);
                        $fullpath_archive="../JDocServer/archivi/$tableid/$namefolder/$new_filename.$fileext";

                        //sposto il file dalla coda nell'archivio
                        if(!file_exists("../JDocServer/archivi/$tableid"))
                            {
                                mkdir("../JDocServer/archivi/$tableid");
                            }
                        if(!file_exists("../JDocServer/archivi/$tableid/$namefolder"))
                            {
                                mkdir("../JDocServer/archivi/$tableid/$namefolder");
                            }
                        //copy($fullpath_batch,$fullpath_archive)
                        rename($fullpath,$fullpath_archive);

                        $fileindex=  $this->db_get_value("user_mail_queue_page", 'fileposition_', "recordid_='$recordid'","ORDER BY fileposition_ DESC");
                        $counter=$fileindex+1;
                        $sql="INSERT INTO user_mail_queue_page (recordid_,creatorid_,creation_,lastupdaterid_,lastupdate_,counter_,fileposition_,path_,filename_,extension_,thumbnail,original_name,filestatusid_,signed_,deleted_) VALUES ('$recordid',$userid,'$now',$userid,'$now',$counter,$counter,'archivi\\\\".$tableid."\\\\".$namefolder."\\\\"."','$new_filename','$fileext','','$original_name','S','N','N') ";
                        $this->set_logquery('inserimento allegato mail',$sql);
                        $this->execute_query($sql);



        }
        return $recordid;
    }
                    


            
        
        
    
    
    function push_dem_mail_queue($mail)
    {
        $mailto='';
        if(array_key_exists('mailto', $mail))
            $mailto=$mail['mailto'];

        $mailcc='';
        if(array_key_exists('mailcc', $mail))
            $mailcc=$mail['mailcc'];

        $mailbcc='';
        if(array_key_exists('mailbcc', $mail))
            $mailbcc=$mail['mailbcc'];

        $mailsubject='';
        if(array_key_exists('mailsubject', $mail))
            $mailsubject=$mail['mailsubject'];

        $mailbody='';
        if(array_key_exists('mailbody', $mail))
        {
            $mailbody=$mail['mailbody'];
            $mailbody=str_replace("'", "''", $mailbody);
        }

        $mailattachment='';
        if(array_key_exists('mailattachment', $mail))
            $mailattachment=$mail['mailattachment'];
        
        
        $recordid=  $this->generate_recordid('mail_queue');
        $sql="
            INSERT INTO user_mail_queue (recordid_,mailto,mailcc,mailbcc,mailsubject,mailbody,mailattachment,status)
            VALUES ('$recordid','$mailto','$mailcc','$mailbcc','$mailsubject','$mailbody','$mailattachment','dainviare')
            ";
        $this->execute_query($sql);
        
    }
    
    function get_scheduler_log()
    {
        $sql="
            SELECT *,to_char(dataora, 'DD/MM/YYYY HH24:MI') as dataora
            FROM sys_scheduler_log
            ORDER BY ID DESC
            LIMIT 100
            ";
        $return=  $this->select($sql);
        return $return;
    }
    
    function get_dailymail_alerts()
    {
        $sql="
            SELECT *
            FROM sys_alert
            where alert_type='dailymail'
            ";
        $dailymail_alerts=  $this->select($sql);

        return $dailymail_alerts;
    }
    
    function get_mid_dailymail_alerts()
    {
        $sql="
            SELECT *
            FROM sys_alert
            where alert_type='mid_dailymail'
            ";
        $mid_dailymail_alerts=  $this->select($sql);

        return $mid_dailymail_alerts;
    }
    
    function get_weeklymail_alerts()
    {
        $sql="
            SELECT *
            FROM sys_alert
            where alert_type='weeklymail'
            ";
        $weeklymail_alerts=  $this->select($sql);

        return $weeklymail_alerts;
    }
    
    function get_monthlymail_alerts()
    {
        $sql="
            SELECT *
            FROM sys_alert
            where alert_type='monthlymail'
            ";
        $monthlymail_alerts=  $this->select($sql);

        return $monthlymail_alerts;
    }
    
    
    function get_queued_mail()
    {
        $mail=null;
        $sql="
            SELECT *
            FROM user_mail_queue
            WHERE 
            status='dainviare'
            AND
            (recordstatus_ is null OR recordstatus_!='temp')
            ORDER BY prioritaria ASC,id ASC
            LIMIT 1
            ";
        $result=  $this->select($sql);
        if(count($result)>0)
        {
            $mail=$result[0];
        }
        return $mail;
    }
    
    function update_queued_mail_status($mail_id,$status)
    {
        $sql="
            UPDATE user_mail_queue
            SET status='$status'
            WHERE id=$mail_id
            ";
        $this->execute_query($sql);
        
    }
    
    function update_queued_mail_note($mail_id,$nota)
    {
        $sql="
            UPDATE user_mail_queue
            SET note='$nota'
            WHERE id=$mail_id
            ";
        $this->execute_query($sql);
        
    }
    
    function update_queued_mail_dataora_invio($mail_id)
    {
        $data_invio=date('Y-m-d');
        $ora_invio=date('H:i:s');
        $sql="
            UPDATE user_mail_queue
            SET data_invio='$data_invio',ora_invio='$ora_invio'
            WHERE id=$mail_id
            ";
        $this->execute_query($sql);
        
    }
    
    function clone_fields_table($tableid,$new_tableid)
    {
        $rows=  $this->db_get('sys_field', '*', "tableid='$tableid'");
        foreach ($rows as $key => $row) {
            $row['tableid']=$new_tableid;
            $this->insert('sys_field', $row);
        }
        
    }  
    function clone_fields_order_table($tableid,$new_tableid)
    {
        $rows=  $this->db_get('sys_user_order', '*', "tableid='$tableid'");
        foreach ($rows as $key => $row) {
            $row['tableid']=$new_tableid;
            unset($row['id']);
            $this->insert('sys_user_order', $row);
        }
        
    }  
    function clone_sublabels_table($tableid,$new_tableid)
    {
        $rows=  $this->db_get('sys_table_sublabel', '*', "tableid='$tableid'");
        foreach ($rows as $key => $row) {
            $row['tableid']=$new_tableid;
            $this->insert('sys_table_sublabel', $row);
        }
        
    }  
    
    function get_page_row($tableid,$filename)
    {
        $table_page_name="user_".strtolower($tableid)."_page";
        $result=$this->db_get_row($table_page_name, '*', "filename_='$filename'");
        return $result;
    }
    
    function get_page_categories($tableid,$recordid=null)
    {
        $table_page="user_".strtolower($tableid)."_page";
        $rows=$this->db_get("sys_table_page_category", '*', "tableid='$tableid'","order by cat_order");
        if($recordid!=null)
        {
            foreach ($rows as $key => $row) {
                $cat_id=$row['cat_id'];
                $sql="
                    SELECT count(*) as cat_counter
                    FROM $table_page
                    WHERE recordid_='$recordid' AND (category like '$cat_id' OR category like '%|;|$cat_id|;|%' OR category like '$cat_id|;|%' OR category like '%|;|$cat_id')    
                    ";
                $result=  $this->select($sql);
                $counter=0;
                if(count($result)>0)
                {
                    $counter=$result[0]['cat_counter'];
                }
                $rows[$key]['cat_counter']=$counter;
            }
        }
        return $rows;
    }
    
    function add_page_category($tableid,$cat_id,$cat_description)
    {
        $cat_id=str_replace("'", "''", $cat_id);
        $cat_description=str_replace("'", "''", $cat_description);
        $sql="
            INSERT INTO sys_table_page_category
            (tableid,cat_id,cat_description)
            VALUES
            ('$tableid','$cat_id','$cat_description')
            ";
        $this->execute_query($sql);
    }
    
    function set_page_category2($tableid,$recordid,$filename,$categories)
    {
        $tablepage_name="user_".strtolower($tableid)."_page";
        $category_string='';
        foreach ($categories as $key => $category) {
            if($category_string!='')
            {
                $category_string=$category_string.'|;|';
            }
            $category_string=$category_string.$category;
        }
        $category_attuale=$this->db_get_value($tablepage_name, 'category', "recordid_='$recordid' AND filename_='$filename'");
        if($this->isnotempty($category_attuale))
        {
            $category_string=$category_attuale.'|;|'.$category_string;
        }
        $sql="
            UPDATE $tablepage_name
            SET category='$category_string'
            WHERE recordid_='$recordid' AND filename_='$filename'    
            ";
        $this->execute_query($sql);
    }
    
    function set_page_category($tableid,$recordid,$filename,$categories)
    {
        $tablepage_name="user_".strtolower($tableid)."_page";
        $category_string='';
        foreach ($categories as $key => $category) {
            if($category_string!='')
            {
                $category_string=$category_string.'|;|';
            }
            $category_string=$category_string.$category;
        }
        $sql="
            UPDATE $tablepage_name
            SET category='$category_string'
            WHERE recordid_='$recordid' AND filename_='$filename'    
            ";
        $this->execute_query($sql);
    }
    
    public function matching_richiesta($recordid_richiesta)
    {
        $this->matching($recordid_richiesta,''); 
    }
    
    public function matching_immobile($recordid_immobile)
    {
        $this->matching('',$recordid_immobile); 
    }
    
    function matching($recordid_richiesta='',$recordid_immobile='')
    {
        if($recordid_richiesta!='')
        {
            $sql="
                DELETE FROM
                user_matching
                where
                recordidimmobili_richiesti_='$recordid_richiesta'
                ";
        $this->execute_query($sql);
        $richiesta=  $this->db_get_row('user_immobili_richiesti','*',"recordid_='$recordid_richiesta'");
        $recordid_contatto=$richiesta['recordidcontatti_'];
        $immobili=  $this->db_get('user_immobili');
        $matching_rows=  $this->db_get('sys_matching','*',"tableid1='immobili_richiesti'");
        
        foreach ($immobili as $key => $immobile) 
        {
            $recordid_immobile=$immobile['recordid_'];
            $rank=0;
            $note='';
            foreach ($matching_rows as $matching_row_key => $matching_row) {
                $fieldid1=$matching_row['fieldid1'];
                $fieldid2=$matching_row['fieldid2'];
                $value1=$richiesta[$fieldid1];
                $value2=$immobile[$fieldid2];
                if(($this->isnotempty($value1))&&($this->isnotempty($value2)))
                {
                    if($value1==$value2)
                    {
                        $rank++;
                        $note=$note."
                            $fieldid1: $value1
                            ";
                    }
                }

            }
            if($rank>0)
            {
                $recordid_matching=  $this->generate_recordid('matching');
                $note=  str_replace("'", "''", $note);
                $sql="
                    INSERT INTO
                    user_matching
                    (recordid_,recordidcontatti_,recordidimmobili_richiesti_,recordidimmobili_,rank,note)
                    VALUES
                    ('$recordid_matching','$recordid_contatto','$recordid_richiesta','$recordid_immobile',$rank,'$note')
                    ";
                $this->execute_query($sql);    
            }
        }

        }
        if($recordid_immobile!='')
        {
            $sql="
                DELETE FROM
                user_matching
                where
                recordidimmobili_='$recordid_immobile'
                ";
        $this->execute_query($sql);
            $richieste=  $this->db_get('user_immobili_richiesti');
            $immobile=  $this->db_get_row('user_immobili','*',"recordid_='$recordid_immobile'");
            $matching_rows=  $this->db_get('sys_matching','*',"tableid1='immobili_richiesti'");


            foreach ($richieste as $key => $richiesta) 
            {
                $recordid_richiesta=$richiesta['recordid_'];
                $recordid_contatto=$richiesta['recordidcontatti_'];
                    $rank=0;
                    $note='';
                    foreach ($matching_rows as $matching_row_key => $matching_row) {
                        $fieldid1=$matching_row['fieldid1'];
                        $fieldid2=$matching_row['fieldid2'];
                        $value1=$richiesta[$fieldid1];
                        $value2=$immobile[$fieldid2];
                        if(($this->isnotempty($value1))&&($this->isnotempty($value2)))
                        {
                            if($value1==$value2)
                            {
                                $rank++;
                                $note=$note."
                                    $fieldid1: $value1
                                    ";
                            }
                        }

                    }

                if($rank>0)
                {
                    $recordid_matching=  $this->generate_recordid('matching');
                    $note=  str_replace("'", "''", $note);
                    $sql="
                        INSERT INTO
                        user_matching
                        (recordid_,recordidcontatti_,recordidimmobili_richiesti_,recordidimmobili_,rank,note)
                        VALUES
                        ('$recordid_matching','$recordid_contatto','$recordid_richiesta','$recordid_immobile',$rank,'$note')
                        ";
                    $this->execute_query($sql);   
                }

            }
        }
        if(($recordid_immobile=='')&&($recordid_richiesta==''))
        {
            
        $sql="
                DELETE FROM
                user_matching
                ";
        $this->execute_query($sql);
            
        $richieste=  $this->db_get('user_immobili_richiesti');
        $immobili=  $this->db_get('user_immobili');
        $matching_rows=  $this->db_get('sys_matching','*',"tableid1='immobili_richiesti'");
        
        
        foreach ($richieste as $key => $richiesta) 
        {
            $recordid_richiesta=$richiesta['recordid_'];
            $recordid_contatto=$richiesta['recordidcontatti_'];
            foreach ($immobili as $key => $immobile) {
                $recordid_immobile=$immobile['recordid_'];
                $rank=0;
                $note='';
                foreach ($matching_rows as $matching_row_key => $matching_row) {
                    $fieldid1=$matching_row['fieldid1'];
                    $fieldid2=$matching_row['fieldid2'];
                    $value1=$richiesta[$fieldid1];
                    $value2=$immobile[$fieldid2];
                    if(($this->isnotempty($value1))&&($this->isnotempty($value2)))
                    {
                        if($value1==$value2)
                        {
                            $rank++;
                            $note=$note."
                                $fieldid1: $value1
                                ";
                        }
                    }
                    
                }
            
            $recordid_matching=  $this->generate_recordid('matching');
            $note=  str_replace("'", "''", $note);
            $sql="
                INSERT INTO
                user_matching
                (recordid_,recordidcontatti_,recordidimmobili_richiesti_,recordidimmobili_,rank,note)
                VALUES
                ('$recordid_matching','$recordid_contatto','$recordid_richiesta','$recordid_immobile',$rank,'$note')
                ";
            $this->execute_query($sql);    
            }
            
            
            /*$conditions=array();
            foreach ($richiesta as $key => $field_value) 
            {
                if($key=='paese')
                {
                    $field_value=  str_replace("'", "''", $field_value);
                    $conditions[]="paese='$field_value'";
                }
            }
            
            $sql="
            SELECT recordid_
            FROM user_immobili
            ";
            $where=" WHERE true";
            foreach ($conditions as $key => $condition) {
                $where=$where." AND $condition";
            }
            $sql=$sql.$where;
            $immobili_trovati=  $this->select($sql);
            
            
            foreach ($immobili_trovati as $key => $immobile_trovato) 
            {
                $immobile_trovato_recordid=$immobile_trovato['recordid_'];
                $matching_recordid=  $this->generate_recordid('matching');
                $sql="
                    INSERT INTO
                    user_matching
                    (recordid_,recordidcontatti_,recordidimmobili_richiesti_,recordidimmobili_,compatibilita)
                    VALUES
                    ('$matching_recordid','$recordid_contatto','$recordid_richiesta','$immobile_trovato_recordid','100')
                    ";
                $this->execute_query($sql);
            }*/
            
        }
        }
        
        
    }
    
    function custom_seatrade_caricamento_manuale($recordid)
    {
        $record_documento=  $this->db_get_row('user_documenti', '*', "recordid_='$recordid'");
        $documento_tipodoc=$record_documento['tipodoc'];
        $tipomail='email';
        if($documento_tipodoc=='Scanner')
            $tipomail='email';
        if($documento_tipodoc=='Bolla')
            $tipomail='emailbolle';
        if($documento_tipodoc=='BollaNoFattura')
            $tipomail='emailbolle';
        if($documento_tipodoc=='Fattura')
            $tipomail='emailfatture';
        $recordid_aziende=$record_documento['recordidaziende_'];
        $record_azienda=  $this->db_get_row('user_aziende', '*', "recordid_='$recordid_aziende'");
        $mail=array();
        $mail_aziende=$record_azienda[$tipomail];
        $mail_aziende=  str_replace("|;|", ";", $mail_aziende);
        $mail['mailto']=$mail_aziende;
        $mail['mailbcc']='jdocweb.seatrade@gmail.com;docs@seatradeinternational.ch';
        $mail['mailsubject']=$record_documento['oggetto'];
        if($documento_tipodoc=='Scanner')
        {
            $mail['mailbody']='
            Dear Madams, dear Sirs, 

            enclosed you can find documents related to departure in subject. 

            For any further information please write to docs@seatradeinternational.ch 

            Many thanks and kind regards, 

            Sea Trade International SA 

            Viale G.Cattori 3 
            6900 Paradiso - Switzerland 
            Tel. +41 91 994 19 90 
            Fax +41 91 994 19 85 
            www.seatradeinternational.ch   
            ';
        }
        if($documento_tipodoc=='Bolla')
        {
            $mail['mailbody']='
            Dear Madams, dear Sirs, 
 
            enclosed you can find our invoice(s) and your export declaration(s) 

            For any further information please write to docs@seatradeinternational.ch 

            Many thanks and kind regards, 

            Sea Trade International SA 

            Viale G.Cattori 3 
            6900 Paradiso - Switzerland 
            Tel. +41 91 994 19 90 
            Fax +41 91 994 19 85 
            www.seatradeinternational.ch     
            ';
        }
        if($documento_tipodoc=='BollaNoFattura')
        {
            $mail['mailbody']='
                    Dear Madams, dear Sirs, 

                    enclosed you can find your export declaration(s) 

                    For any further information please write to docs@seatradeinternational.ch 

                    Many thanks and kind regards,

                    Sea Trade International SA 

                    Viale G.Cattori 3 
                    6900 Paradiso - Switzerland 
                    Tel. +41 91 994 19 90 
                    Fax +41 91 994 19 85 
                    www.seatradeinternational.ch
                        ';
        }
        if($documento_tipodoc=='Fattura')
        {
            $mail['mailbody']='
            Dear Madams, dear Sirs, 
 
            enclosed you can find our invoice as per details in subject. 
            This pdf document sent per email is to be kept as original and replaces any sending per ordinary mail. 

            For any further information please write to docs@seatradeinternational.ch 

            Many thanks and kind regards, 

            Sea Trade International SA 

            Viale G.Cattori 3 
            6900 Paradiso - Switzerland 
            Tel. +41 91 994 19 90 
            Fax +41 91 994 19 85 
            www.seatradeinternational.ch     
            ';
        }
        
        $record_documento_page=  $this->db_get_row('user_documenti_page', '*', "recordid_='$recordid'","order by recordid_ desc");
        $path=$record_documento_page['path_'];
        $path=  str_replace("\\", "/", $path);
        $path=  str_replace("//", "/", $path);
        $filename=$record_documento_page['filename_'];
        $extension=$record_documento_page['extension_'];
        $fullpath="../JDocServer/$path/$filename.$extension";
        $mail['mailattachment']=$fullpath;
        $mail['linkedrecordid']=$recordid;
        $this->push_mail_queue($mail);
        
        $sql="
            UPDATE user_documenti
            SET stato='Inviato manualmente'
            WHERE recordid_='$recordid'
            ";
        $this->execute_query($sql);

    }
    
        public function get_mail_template($mail_template_id)
        {
            $sql="
                select *
                from sys_mail_template
                where mail_template_id='$mail_template_id'
                ";
            $result=  $this->select($sql);
            if(count($result>0))
            {
                $return=$result[0]['mail_template_body'];
            }
            else
            {
                $return="";
            }
            return $return;
        }
    
        public function insert_iscritto($azienda_iscritta,$persona_iscritta)
        {
            
                $ragionesociale=$azienda_iscritta['ragionesociale'];
                $telefono=$azienda_iscritta['telefono'];
                $mail_azienda=$azienda_iscritta['mail'];
                $citta=$azienda_iscritta['citta'];
                $indirizzo=$azienda_iscritta['indirizzo'];
                $workshop=$azienda_iscritta['workshop'];
                $appuntamento=$azienda_iscritta['appuntamento'];
                
                $dataiscrizione=$azienda_iscritta['dataiscrizione'];
                if($dataiscrizione!='0000-00-00 00:00:00')
                    $dataiscrizione="'".date('Y-m-d',  strtotime ($dataiscrizione))."'";
                else
                    $dataiscrizione='null';
                
                        $cognome=$persona_iscritta['cognome'];
                        $nome=$persona_iscritta['nome'];
                        $mail_persona=$persona_iscritta['mail'];
                        $sql="
                        SELECT *
                        FROM user_iscrizioneeventi
                        WHERE (azienda='$ragionesociale') AND (contatto='$cognome $nome') AND (recordideventi_campagne_='00000000000000000000000000000006')
                        
                        ";
                        $result=$this->select($sql);
                        $recordid_iscrizione_eventi='';
                        if(count($result)==0)
                        {
                            $recordid_iscrizione_eventi=  $this->generate_recordid('iscrizioneeventi');
                            $id=  $this->generate_id('iscrizioneeventi');
                            $ragionesociale=  str_replace("'", "''", $ragionesociale);
                            $citta=  str_replace("'", "''", $citta);
                            $indirizzo=  str_replace("'", "''", $indirizzo);
                            $cognome=  str_replace("'", "''", $cognome);
                            $nome=  str_replace("'", "''", $nome);
                            $sql="
                                INSERT INTO user_iscrizioneeventi
                                (recordid_,id,dataiscrizione,azienda,telefono,mailazienda,citta,indirizzo,workshop,appuntamento,contatto,mailcontatto,presente,iscritto_online,recordideventi_campagne_)
                                VALUES
                                ('$recordid_iscrizione_eventi',$id,$dataiscrizione,'$ragionesociale','$telefono','$mail_azienda','$citta','$indirizzo','$workshop','$appuntamento','$cognome $nome','$mail_persona','no','si','00000000000000000000000000000006')

                                ";
                            $this->execute_query($sql);
                        }
                        return $recordid_iscrizione_eventi;

                    
                
                /*else
                {
                    $sql="
                        SELECT *
                        FROM user_iscrizioneeventi
                        WHERE azienda='$ragionesociale'
                        
                        ";
                    $result=$this->select($sql);
                    if(count($result)==0)
                    {
                        $recordid_iscrizione_eventi=  $this->generate_recordid('iscrizioneeventi');
                        $id=  $this->generate_id('iscrizioneeventi');
                        $sql="
                            INSERT INTO user_iscrizioneeventi
                            (recordid_,id,dataiscrizione,azienda,telefono,mailazienda,citta,indirizzo,workshop,appuntamento,presente,iscritto_online,recordideventi_campagne_)
                            VALUES
                            ('$recordid_iscrizione_eventi',$id,$dataiscrizione,'$ragionesociale','$telefono','$mail_azienda','$citta','$indirizzo','$workshop','$appuntamento','no','si','00000000000000000000000000000001')

                            ";
                        $this->execute_query($sql);
                    }
                    
                }*/
                
            
        }
        
        public function insert_segnalazioni($segnalazioni)
        {
            foreach ($segnalazioni as $key => $segnalazione) {
                $recordidonline=$segnalazione['recordid_'];
                $settore=$segnalazione['settore'];
                $ragionesociale=$segnalazione['ragionesociale'];
                $cognomenome=$segnalazione['cognomenome'];
                $segnalatore=$ragionesociale." ".$cognomenome;
                $segnalatore=  str_replace("'", "''", $segnalatore);
                $telefono=$segnalazione['telefono'];
                $mail=$segnalazione['mail'];
                $notesegnalazione=$segnalazione['segnalazione'];
                $notesegnalazione=  str_replace("'", "''", $notesegnalazione);
                $datasegnalazioneonline=$segnalazione['datasegnalazione'];
                $datasegnalazione=date('Y-m-d',  strtotime($datasegnalazioneonline));
                $orasegnalazione=date('H:i:s',  strtotime($datasegnalazioneonline));
                $sql="
                SELECT *
                FROM user_segnalazioni
                WHERE recordidonline='$recordidonline' 
                ";
                $result=$this->select($sql);
                if(count($result)==0)
                {
                    $recordid_segnalazione=  $this->generate_recordid('segnalazioni');
                    $sql="
                        INSERT INTO user_segnalazioni
                        (recordid_,tipo,datasegnalazione,dataprox,orasegnalazione,settore,segnalatore,telefono,mail,note,stato,incaricato,recordidonline)
                        VALUES
                        ('$recordid_segnalazione','Segnalazione','$datasegnalazione','$datasegnalazione','$orasegnalazione','$settore','$segnalatore','$telefono','$mail','$notesegnalazione','nuova','27','$recordidonline')

                        ";
                    $this->execute_query($sql);
                }
            }
        
        }
        
        
        
        
        public function ajax_set_field_explanation($tableid,$fieldid,$explanation)
        {
            $explanation=  str_replace("'", "''", $explanation);
            $sql="
                UPDATE sys_field
                SET explanation='$explanation'
                WHERE tableid='$tableid' AND fieldid='$fieldid'    
                ";
            $this->execute_query($sql);
        }
        
        
        public function script_reload_id_iscrizioni()
        {
            $sql="
                SELECT *
                FROM user_iscrizioneeventi
                order by recordid_ asc
                ";
            $iscritti=  $this->select($sql);
            $counter=1;
            foreach ($iscritti as $key => $iscritto) {
                $recordid=$iscritto['recordid_'];
                $sql="
                    update user_iscrizioneeventi
                    set id=$counter
                    where recordid_='$recordid'
                    ";
                $this->execute_query($sql);
                $counter++;
            }
        }
        
        public function salva_impostazioni_archivio_settings($idarchivio,$settings)
        {
            $userid=  $this->get_userid();
            
            foreach ($settings as $setting_key => $setting) 
            {
                if(isnotempty($setting))
                {
                    $sql="
                    DELETE FROM sys_user_table_settings
                    WHERE userid=$userid AND tableid='$idarchivio' AND settingid='$setting_key'
                    ";
                    $this->execute_query($sql);
                    $sql="
                        INSERT INTO sys_user_table_settings
                        (userid,tableid,settingid,value)
                        VALUES
                        ($userid,'$idarchivio','$setting_key','$setting')
                        ";
                    $this->execute_query($sql);
                }
            }
        }
        
        public function salva_impostazioni_dashboard($settings)
        {
            $userid=  $this->get_userid();
            $dashboardid=$settings['dashboard'];
            $viewid=$settings['view'];
            $reportid=$settings['report'];
            $sql="
                        INSERT INTO sys_dashboard_block
                        (dashboardid,userid,viewid,reportid)
                        VALUES
                        ($dashboardid,$userid,$viewid,$reportid)
                        ";
                    $this->execute_query($sql);
        }
        
        public function salva_impostazioni_user_settings($settings)
        {
            $userid=  $this->get_userid();
            
            foreach ($settings as $setting_key => $setting) 
            {
                if(isnotempty($setting))
                {
                    $sql="
                    DELETE FROM sys_user_settings
                    WHERE userid=$userid AND setting='$setting_key'
                    ";
                    $this->execute_query($sql);
                    $sql="
                        INSERT INTO sys_user_settings
                        (userid,setting,value)
                        VALUES
                        ($userid,'$setting_key','$setting')
                        ";
                    $this->execute_query($sql);
                }
            }
        }
        
        public function salva_impostazioni_archivio_campi($tableid,$fields)
        {
            $userid=  $this->get_userid();
            
            $sql="
                DELETE FROM sys_user_order
                WHERE tableid='$tableid' AND userid=$userid AND (typepreference='campiInserimento' OR typepreference='campiricerca')
                ";
            $this->execute_query($sql);
            
            $fields_update=$fields['update'];
            foreach ($fields_update as $field_key => $field) {
                $fieldid=$field['fieldid'];
                $fieldorder=$field['fieldorder'];
                $description=$field['description'];
                $showedbyfieldid=$field['showedbyfieldid'];
                $showedbyvalue=$field['showedbyvalue'];
                $sublabel=$field['sublabel'];
                $settings=$field['settings'];
                
                $description=  str_replace("'", "''", $description);
                $sql="
                    INSERT INTO sys_user_order
                    (userid,tableid,fieldid,fieldorder,typepreference)
                    VALUES
                    ($userid,'$tableid','$fieldid','$fieldorder','campiInserimento')
                    ";
                $this->execute_query($sql);
                $sql="
                    UPDATE sys_field
                    SET description='$description',showedbyfieldid='$showedbyfieldid',showedbyvalue='$showedbyvalue',sublabel='$sublabel'
                    WHERE tableid='$tableid' AND fieldid='$fieldid'
                    ";
                $this->execute_query($sql);
                if(array_key_exists('campiricerca',$field))
                {
                    $sql="
                        INSERT INTO sys_user_order
                        (userid,tableid,fieldid,fieldorder,typepreference)
                        VALUES
                        (1,'$tableid','$fieldid',$fieldorder,'campiricerca')
                        ";
                    $this->execute_query($sql);
                }
                
                //settings
                $sql="
                DELETE FROM sys_user_field_settings
                WHERE tableid='$tableid' AND fieldid='$fieldid' AND userid='$userid'
                ";
                $this->execute_query($sql);
                foreach ($settings as $setting_key => $setting) {
                    $sql="
                        INSERT INTO sys_user_field_settings
                        (userid,tableid,fieldid,settingid,value)
                        VALUES
                        ('$userid','$tableid','$fieldid','$setting_key','$setting')
                        ";
                    $this->execute_query($sql);
                }
            }
            $fields_delete=$fields['delete'];
            foreach ($fields_delete as $field_key => $field) {
                
            }
            $fields_insert=$fields['insert'];
            foreach ($fields_insert as $field_key => $field) {
                
            }
            
        }
        
        
        public function script_migrazione_prodotti_multifunzione()
        {
            $multifunzioni=  $this->db_get('user_prodottimultifunzione');
            foreach ($multifunzioni as $key => $multifunzione) {
                $recordid=$multifunzione['recordid_'];
                $sigla=  db_convert_string($multifunzione['sigla']);
                $tipo= db_convert_string($multifunzione['tipo']);
                $stato=  db_convert_string($multifunzione['stato']);
                $famiglia=  db_convert_string($multifunzione['famiglia']);
                $formato=  db_convert_string($multifunzione['formato']);
                $prezzolistino= db_convert_number($multifunzione['prezzolistino']);
                $descrizione=  db_convert_string($multifunzione['descrizione']);
                $prezzoacquisto= db_convert_number($multifunzione['prezzoacquisto']);
                $percentualesconto= db_convert_number($multifunzione['percentualesconto']);
                $codiceprodotto=  db_convert_string($multifunzione['codiceprodotto']);
                $linkpaginaxerox=  db_convert_string($multifunzione['linkpaginaxerox']);
                $settore=  db_convert_string($multifunzione['settore']);
                
                
                $sql="
                    INSERT INTO user_prodotti
                    (recordid_,sigla,tipo,stato,famiglia,formato,prezzolistino,descrizione,prezzoacquisto,percentualesconto,codiceprodotto,linkpaginaxerox,settore)
                    VALUES
                    ('$recordid',$sigla,$tipo,$stato,$famiglia,$formato,$prezzolistino,$descrizione,$prezzoacquisto,$percentualesconto,$codiceprodotto,$linkpaginaxerox,$settore)
                    ";
                $this->execute_query($sql);
            }
        }
        
        public function script_migrazione_prodotti_accessori()
        {
            $accessori=  $this->db_get('sys_lookup_table_item', '*', "lookuptableid='accessori'");
            foreach ($accessori as $key => $accessorio) {
                $itemcode=$accessorio['itemcode'];
                $itemdesc=$accessorio['itemdesc'];
                $recordid=  $this->generate_recordid('prodotti');
                $sql="
                    INSERT INTO user_prodotti
                    (recordid_,sigla,tipo)
                    VALUES
                    ('$recordid','$itemcode','Accessorio')
                    ";
                $this->execute_query($sql);
                
            }
        }
        
        public function script_migrazione_contrattimultifunzione_commesse()
        {
            $contrattimulti=  $this->db_get('user_contrattimultifunzione');
            foreach ($contrattimulti as $key_contrattomulti => $contrattomulti) {
                //ddati contratto multifunzione
                $id=  db_convert_number($contrattomulti['id']);
                $categoriacontratto=  db_convert_string($contrattomulti['categoriacontratto']);
                $situazionecontratto=db_convert_string($contrattomulti['situazionecontratto']);
                $datainizio=  db_convert_date($contrattomulti['datainizio']);
                $datascadenza=  db_convert_date($contrattomulti['datascadenza']);
                $datainstallazionemacchina=  db_convert_date($contrattomulti['datainstallazionemacchina']);
                $importomensilenoleggio=  db_convert_number($contrattomulti['importomensilenoleggio']);
                $basechargemanutenzione=db_convert_number($contrattomulti['basechargemanutenzione']);
                $copieinclusebianconero=db_convert_number($contrattomulti['copieinclusebianconero']);
                $costocopiabna4=db_convert_number($contrattomulti['costocopiabna4']);
                $costocopiabna3=db_convert_number($contrattomulti['costocopiabna3']);
                $copieinclusecolori=db_convert_number($contrattomulti['copieinclusecolori']);
                $costocopiacolorea4=db_convert_number($contrattomulti['costocopiacolorea4']);
                $costocopiacolorea3=db_convert_number($contrattomulti['costocopiacolorea3']);
                $fatturazione=  db_convert_string($contrattomulti['fatturazione']);
                $rilevamentoautomaticodati=  db_convert_string($contrattomulti['rilevamentoautomaticodati']);
                $note=  db_convert_string($contrattomulti['note']);
                $recordidaziende=  db_convert_string($contrattomulti['recordidaziende_']);
                $numcontratto=  db_convert_string($contrattomulti['numcontratto']);
                $recordidprodottimultifunzione=  db_convert_string($contrattomulti['recordidprodottimultifunzione_']);
                $importomensilexpps=  db_convert_number($contrattomulti['importomensilexpps']);
                $seriale=  db_convert_string($contrattomulti['seriale']);
                $controllato=  db_convert_string($contrattomulti['controllato']);
                $servicecontract=  db_convert_string($contrattomulti['servicecontract']);
                $manutenzione=  db_convert_string($contrattomulti['manutenzione']);
                
               
                
                $recordid_commesse=  $contrattomulti['recordid_'];
                $sql="
                    INSERT INTO user_commesse
                    (recordid_,id,nrcontratto,datafirma,datainizio,datafine,agente,settore,situazionecontratto,durata,note,controllato,descrizione,datainstallazionemacchina,recordidaziende_)
                    VALUES
                    ('$recordid_commesse',$id,$numcontratto,null,$datainizio,$datascadenza,null,'xerox',$situazionecontratto,null,$note,$controllato,null,$datainstallazionemacchina,$recordidaziende)
                    ";
                $this->execute_query($sql);
                //inserimento prodotto multifunzione
                $recordid_commessa_prodotti =  $this->generate_recordid('commessa_prodotti');
                $sql="
                    INSERT INTO user_commessa_prodotti
                    (recordid_,seriale,descrizione,categoriaprodotto,servicecontract,rilevautodati,contatoreattuale,tipoprodotto,manutenzione,basechargemanutenzione,importomensilenoleggio,copieinclusebianconero,costocopiabna4,costocopiabna3,copieinclusecolori,costocopiacolorea4,costocopiacolorea3,importomensilexpps,fatturazione,note,prezzovendita,trasportoinstallazione,tassariciclaggio,istruzione,quantita,recordidcommesse_,recordidprodotti_)
                    VALUES
                    ('$recordid_commessa_prodotti',$seriale,'',$categoriacontratto,$servicecontract,$rilevamentoautomaticodati,null,'Multifunzione',$manutenzione,$basechargemanutenzione,$importomensilenoleggio,$copieinclusebianconero,$costocopiabna4,$costocopiabna3,$copieinclusecolori,$costocopiacolorea4,$costocopiacolorea3,$importomensilexpps,$fatturazione,'',null,null,null,null,null,'$recordid_commesse',$recordidprodottimultifunzione)
                    ";
                $this->execute_query($sql);
                $accessori=$contrattomulti['accessori'];
                $accessori_array=  explode("|;|", $accessori);
                foreach ($accessori_array as $key => $accessorio) {
                    $recordid_accessorio=  $this->db_get_value('user_prodotti', 'recordid_', "sigla='$accessorio'");
                    $recordid_commessa_prodotti =  $this->generate_recordid('commessa_prodotti');
                    $sql="
                        INSERT INTO user_commessa_prodotti
                        (recordid_,tipoprodotto,recordidcommesse_,recordidprodotti_)
                        VALUES
                        ('$recordid_commessa_prodotti','Accessorio','$recordid_commesse','$recordid_accessorio')
                        ";
                    $this->execute_query($sql);
                }

                
            }
            
        }
        
        public function conferma_iscrizione($recordid_iscrizione_eventi)
        {
            $sql="
                UPDATE
                user_iscrizioneeventi
                SET presente='si'
                WHERE recordid_='$recordid_iscrizione_eventi'
                
                ";
            $this->execute_query($sql);
        }
        
        public function avvia_dem($recordid_dem,$tipoinvio='test')
        {
            if($tipoinvio=='test')
                $stato='Test';
            if($tipoinvio=='produzione')
                $stato='Da inviare';
            $dem=$this->db_get_row('user_dem','*',"recordid_='$recordid_dem'");
            $nome_dem=$dem['nome_dem'];
             $now=date("Y-m-d H:i:s"); 
            
            $utente=$dem['utente'];
            $mail_subject=$dem['mail_subject'];
            $mail_subject=  str_replace("'", "''", $mail_subject);
            $mail_body=$dem['mail_body'];
            $mail_body=  str_replace("'", "''", $mail_body);
            $destinatari=$this->Sys_model->db_get('user_dem_listadestinatari','*',"stato='$stato' AND recordiddem_='$recordid_dem'");
            $destinatari_counter= count($destinatari);
            $counter=0;
            foreach ($destinatari as $key => $destinatario) {
                $recordid_destinatario=$destinatario['recordid_'];
                $recordid_azienda=$destinatario['recordidaziende_'];
                $recordid_contatto=$destinatario['recordidcontatti_'];
                $mail_destinatario=$destinatario['email'];
                $recordid_mailqueue=  $this->generate_recordid('mail_queue');
                $sql="
                    INSERT INTO user_mail_queue
                    (recordid_,creatorid_,mailfrom_userid,mailto,mailsubject,mailbody,status,recordiddem_,recordidaziende_,recordidcontatti_)
                    VALUES
                    ('$recordid_mailqueue',1,$utente,'$mail_destinatario','$mail_subject','$mail_body','dainviare','$recordid_dem','$recordid_azienda','$recordid_contatto')
                    ";
                $this->execute_query($sql);
                $counter++;
                $sql="
                    UPDATE user_dem_listadestinatari
                    SET
                    stato='Inviata'
                    WHERE recordid_='$recordid_destinatario'
                    ";
                $this->execute_query($sql);
            }
            echo $destinatari_counter."-".$counter;
        }
       
        public function salva_impostazioni_archivio_alert($post)
        {
            $alerts=$post['alert'];
            foreach ($alerts['update'] as $key_alert => $alert) {
                $set="";
                foreach ($alert as $key_alert_field => $alert_field) {
                    $alert_field=  str_replace("'", "''", $alert_field);
                    if($set!='')
                        $set=$set.',';
                    $set=$set."$key_alert_field='$alert_field'";
                }
                $sql="
                    UPDATE sys_alert
                    SET $set
                    WHERE id=$key_alert
                    ";
                $this->execute_query($sql);
            }
            
            $alert_new=$alerts['new'];
            if($this->isnotempty($alert_new['alert_description']))
            {
                $fields='';
                $values='';
                foreach ($alert_new as $key_alert_new_field => $alert_new_field) {
                    $alert_new_field=  str_replace("'", "''", $alert_new_field);
                    if($fields!='')
                        $fields=$fields.',';
                    if($values!='')
                        $values=$values.',';
                    $fields=$fields.$key_alert_new_field;
                    $values=$values."'$alert_new_field'";
                }
                $sql="
                    INSERT INTO sys_alert
                    ($fields)
                    VALUES 
                    ($values)
                    ";
                $this->execute_query($sql);
            }
            
        }
        
        public function dem_add_mail($recordid_dem,$master_tableid,$master_recordid,$mail)
        {
            $linkedmaster_field=  "recordid".strtolower($master_tableid)."_";
            $recordid_dem_listadestinatari=  $this->generate_recordid('dem_listadestinatari');
            $mail=  str_replace("'", "''", $mail);
            $sql="
                INSERT INTO
                user_dem_listadestinatari
                (recordid_,email,stato,recordiddem_,$linkedmaster_field)
                VALUES
                ('$recordid_dem_listadestinatari','$mail','Da inviare','$recordid_dem','$master_recordid')
                ";
            $this->execute_query($sql);
        }
        
        public function campagna_add_telemarketing($recordid_campagna,$master_tableid,$master_recordid)
        {
            $linkedmaster_field=  "recordid".strtolower($master_tableid)."_";
            $recordid_telemarketing=  $this->generate_recordid('telemarketing');
            $today=  date('Y-m-d');
            $userid=  $this->get_userid();
            $sql="
                INSERT INTO
                user_telemarketing
                (recordid_,statotelemarketing,recalldate,utente,recordideventi_campagne_,$linkedmaster_field)
                VALUES
                ('$recordid_telemarketing','noniniziata','$today','$userid','$recordid_campagna','$master_recordid')
                ";
            $this->execute_query($sql);
        }
        
        public function conferma_queued_mail($recordid)
        {
            
            if($this->get_cliente_id()=='Dimensione Immobiliare')
            {
                $queued_mail= $this->db_get_row('user_mail_queue','*',"recordid_='$recordid'");
                $contatto_email='';
                $mailbody=$queued_mail['mailbody'];
                $recordid_contatto= $queued_mail['recordidcontatti_'];
                if(isnotempty($recordid_contatto))
                {
                    $contatto=$this->db_get_row('user_contatti','*',"recordid_='$recordid_contatto'");
                    $contatto_email=$contatto['email'];
                    $contatto_nome=$contatto['nome'];
                    $contatto_cognome=$contatto['cognome'];
                    $mailbody= str_replace('contatto_nome', $contatto_nome, $mailbody);
                    $mailbody= str_replace('contatto_cognome', $contatto_cognome, $mailbody);
                }
                $mailbody= str_replace('error', '', $mailbody);
                $mailbody= str_replace('contatto_nome', '', $mailbody);
                $mailbody= str_replace('contatto_cognome', '', $mailbody);
                $mailbody= str_replace("'", "''", $mailbody);
                $sql="
                UPDATE user_mail_queue
                SET mailto='$contatto_email',mailbody='$mailbody'
                WHERE recordid_='$recordid'
                ";
            $this->execute_query($sql);
                
            }
            /*
            $sql="
                UPDATE user_mail_queue
                SET recordstatus_=null,status='dainviare'
                WHERE recordid_='$recordid'
                ";
             * 
             */
            $sql="
                UPDATE user_mail_queue
                SET recordstatus_=null
                WHERE recordid_='$recordid'
                ";
            $this->execute_query($sql);
            if($this->get_cliente_id()=='Dimensione Immobiliare')
            {
                $mail=  $this->db_get_row('user_mail_queue','*',"recordid_='$recordid'");
                $recordid_richiesta=$mail['recordidimmobili_richiesti_'];
                $recordid_proposta=$mail['recordidimmobili_proposti_'];
                $recordid_immobile=$mail['recordidimmobili_'];
                $recordid_contatto=$mail['recordidcontatti_'];
                $this->insert_agenda($recordid_immobile, $recordid_contatto, $recordid_richiesta,$recordid_proposta,'Invio prospetto');
            }
        }
        
        public function annulla_queued_mail($recordid)
        {
            $sql="
                DELETE FROM user_mail_queue
                WHERE recordid_='$recordid'
                ";
            $this->execute_query($sql);
            
            $sql="
                DELETE FROM user_mail_queue_page
                WHERE recordid_='$recordid'
                ";
            $this->execute_query($sql);
        }
        
        
        public function get_value_converted($tableid,$fieldid,$fieldvalue,$linkedmaster_show_record=true)
        {
            $field=  $this->db_get_row('sys_field','*',"tableid='$tableid' AND fieldid='$fieldid'");
            $fieldtypeid=$field['fieldtypeid'];
            $tablelink=$field['tablelink'];
            $keyfieldlink=$field['keyfieldlink'];
            $lookuptableid=$field['lookuptableid'];
            if(($lookuptableid!='')&&($lookuptableid!=null)&&($lookuptableid!='VUOTA'))
            {
                $fieldtypeid='Lookup';
            }
            if(isnotempty($tablelink)&&(isnotempty($keyfieldlink)))
            {
                $fieldtypeid='LinkedMaster';
            }
            if(isnotempty($tablelink)&&(isempty($keyfieldlink)))
            {
                $fieldtypeid='Linked';
            }

                $field_value_exploded=  explode("|;|", $fieldvalue);
                $field_finale="";
                foreach ($field_value_exploded as $field_value_exploded_key => $field) 
                {
                    if($fieldtypeid=='Utente')
                    {
                        $field=$this->get_user_nomecognome($field);
                    }
                    if($fieldtypeid=='Data')
                    {
                        if($field!='')
                        {
 
                            $field=date('d/m/Y',  strtotime(str_replace('/', '-', $field)));
                            
                        }
                    }
                    if($fieldtypeid=='Ora')
                    {
                        if($field!='')
                        {
                            $field=date("H:i",strtotime($field));
                        }
                    }
                    if($fieldtypeid=='Calcolato')
                    {
                        if($field!='')
                        {
                            //$field=  date('H:i',strtotime($field));
                            sscanf($field, "%d:%d:%d", $hours, $minutes, $seconds);
                            $field = $hours.":".$minutes;
                        }
                    }
                    if($fieldtypeid=='Parola')
                    {
                        
                    }
                    if($fieldtypeid=='Lookup')
                    {
                        
                        $field=  $this->get_descrizione_lookup($tableid, $fieldid, $field);
                    }
                    if($fieldtypeid=='Memo')
                    {
                        $lenght=  strlen($field);
                        /*if($lenght>255)
                        {
                            $field=  substr($field, 0, 512);
                            $field=$field."...";
                        }*/
                    }
                    if($fieldtypeid=='LinkedMaster')
                    {
                        $field=  $this->get_keyfieldlink_value($tableid,$tablelink,$field,$linkedmaster_show_record);

                    }

                    if($field_finale=='')
                    {
                        $field_finale=$field_finale.$field;
                    }
                    else
                    {
                        $field_finale=$field_finale." - ".$field;
                    }
                }
                return $field_finale;
        }
        
        
        //CUSTOM ABOUT
        function get_interventi_tecnici($recordid_segnalazione)
        {
            $result=$this->db_get('user_timesheet','*',"recordidsegnalazioni_='$recordid_segnalazione' AND servizio='Intervento tecnico'");
            return $result;
        }
        
        function get_emailinviodoc($tableid,$recordid)
        {
            if($tableid=='segnalazioni')
            {
                $recordid_azienda=  $this->db_get_value('user_segnalazioni', 'recordidaziende_', "recordid_='$recordid'");
            }
            if($tableid=='timesheet')
            {
                $recordid_azienda=  $this->db_get_value('user_timesheet', 'recordidaziende_', "recordid_='$recordid'");
            }
            $emailinviodoc=$this->db_get_value('user_aziende','emailinviodoc',"recordid_='$recordid_azienda'");
            return $emailinviodoc;
        }
        
        
        function get_dati_candidato($recordid)
        {
            $candidato['studi']=  $this->db_get('user_studi', '*', "recordidcandidati_='$recordid'"," ORDER BY annoinizio, mesefine"); //" ORDER BY annofine DESC, mesefine DESC"
            $candidato['soggiornilinguistici']=  $this->db_get('user_soggiornilinguistici', '*', "recordidcandidati_='$recordid' AND nazione is not null");
            $candidato['esperienzeprofessionali']=  $this->db_get('user_esperienzeprofessionali', '*', "recordidcandidati_='$recordid' AND azienda is not null"," ORDER BY annoinizio DESC, meseinizio DESC");
            //$candidato['conoscenzelinguistiche']=  $this->db_get('user_conoscenzelinguistiche', '*', "recordidcandidati_='$recordid' AND lingua is not null");
            $candidato['conoscenzelinguistiche']=  $this->db_get('(user_conoscenzelinguistiche INNER JOIN sys_lookup_table_item ON user_conoscenzelinguistiche.conoscenzaorale = sys_lookup_table_item.itemcode) INNER JOIN sys_lookup_table_item AS sys_lookup_table_item_1 ON user_conoscenzelinguistiche.lingua = sys_lookup_table_item_1.itemcode', 
                        'DISTINCT user_conoscenzelinguistiche.recordidcandidati_, user_conoscenzelinguistiche.lingua, user_conoscenzelinguistiche.linguaaltro, user_conoscenzelinguistiche.conoscenzaorale, user_conoscenzelinguistiche.conoscenzascritta, sys_lookup_table_item.order AS olivello, sys_lookup_table_item_1.order AS olingua', 
                        "recordidcandidati_='$recordid' AND lingua is not null AND sys_lookup_table_item.lookuptableid='conoscenzaorale_conoscenzelinguistiche' AND sys_lookup_table_item_1.lookuptableid='lingua_conoscenzelinguistiche'",
                        "ORDER BY recordidcandidati_, 
                            (case when conoscenzaorale ='Madrelingua' then olivello end) desc,
                            (case when conoscenzaorale <>'Madrelingua' then olingua end) asc");
            
            $candidato['conoscenzeinformatiche']=  $this->db_get('user_conoscenzeinformatiche', '*', "recordidcandidati_='$recordid' AND conoscenzeit is not null");
            
            foreach ($candidato as $key_label => $label) {
                foreach ($label as $key_table => $table) {
                    foreach ($table as $key_field => $field) {
                        //$candidato[$key_label][$key_table][$key_field]=$this->convert_field_value('soggiornilinguistici',$key_field,$field);
                        $candidato[$key_label][$key_table][$key_field]=$this->convert_field_value($key_label,$key_field,$field);
                    }
                }                
            }
            
            //Per ultima
            $candidato['dati']=  $this->db_get_row('user_candidati', '*', "recordid_='$recordid'");
            foreach ($candidato['dati'] as $key => $dato) {
                $candidato['dati'][$key]=$this->convert_field_value('candidati',$key,$dato);
            }
            
            return $candidato;
        }
        
        
        //CUSTOM 3p
        function genera_rapportidilavoro($recordid_dipendente,$alias='',$recordid_contratto='')
        {
            $dipendente= $this->db_get_row('user_dipendenti','*',"recordid_='$recordid_dipendente'");
            $datainiziocollaborazione=$dipendente['datainiziocollaborazione'];
            //$datachiusura=$dipendente['datachiusura'];
            $counter_rapporti=0;
            if(isempty($alias))
            {
                //$check_2019=$this->db_get('user_rapportidilavoro', 'data,recordiddipendenti_', "data='2019-01-01' AND recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')");
                //if(count($check_2019)==0)
                //{
                    $ultimepresenzemensili=$this->db_get_row('user_presenzemensili','anno,mese',"recordiddipendenti_='$recordid_dipendente'",'ORDER BY recordid_ desc');
                    $anno=$ultimepresenzemensili['anno'];
                    $mese=$ultimepresenzemensili['mese'];
                    /*$sql="
                        SELECT cal.*
                        FROM user_calendario as cal LEFT JOIN user_rapportidilavoro as rap ON cal.giorno=rap.giorno
                        WHERE rap.recordidipendenti='$recordid_dipendente' AND rap.id is not null AND cal.giorno<='$anno-$mese-31'
                        ";*/
                    //$giorni= $this->select($sql);
                    $sql="
                        UPDATE user_calendario
                        INNER JOIN user_giornifestivi ON user_calendario.giorno=user_giornifestivi.giorno
                        SET user_calendario.tipogiorno=user_giornifestivi.tipogiorno, user_calendario.descrizione=user_giornifestivi.descrizione
                        ";
                    $this->execute_query($sql);
                    $giorni= $this->db_get('user_calendario','*',"giorno<='$anno-$mese-31'");
                    foreach ($giorni as $key => $giorno) {
                        $data=$giorno['giorno'];

                        $check_giorno=$this->db_get('user_rapportidilavoro', 'data,recordiddipendenti_', "data='$data' AND recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')");
                        if(count($check_giorno)==0)
                        {
                            $recordid_rapportodilavoro= $this->generate_recordid('rapportidilavoro');
                            $tipogiorno=$giorno['tipogiorno'];
                            $nsettimana=$giorno['nsettimana'];
                            $giornosettimana=$giorno['giornosettimana'];
                            $sql="
                                INSERT INTO user_rapportidilavoro 
                                ( recordid_, data, recordiddipendenti_, TipoGiorno, giornodellasettimana, NSettimana, duratalavoro, alias)
                                VALUES
                                ( '$recordid_rapportodilavoro','$data','$recordid_dipendente','$tipogiorno','$giornosettimana','$nsettimana','00:00', '$alias')
                                ";
                            $this->execute_query($sql);
                            $counter_rapporti++;
                        }
                        
                    }
                //}
                
                
            }
            else
            {
                if($this->isnotempty($recordid_contratto))
                {
                    $contratto= $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");  
                
                    $datainizio=$contratto['datainizio'];
                    $datafine=$contratto['datafine'];
                    $datadisdetta=$contratto['datadisdetta'];
                    if(isnotempty($datadisdetta))
                    { 
                        $datafine=$datadisdetta;
                    }
                    if($this->isnotempty($datainizio))
                    {
                        $mese_inizio=date('m', strtotime($datainizio));
                        $anno_inizio=date('Y', strtotime($datainizio));
                        if($this->isnotempty($datafine))
                        {
                            $mese_fine=date('m', strtotime($datafine));
                            $anno_fine=date('Y', strtotime($datafine));
                            $giorni= $this->db_get('user_calendario','*',"giorno>='$anno_inizio-$mese_inizio-01' AND giorno<='$anno_fine-$mese_fine-31'" );

                        }
                        else
                        {
                            $giorni= $this->db_get('user_calendario','*',"giorno>='$anno_inizio-$mese_inizio-01'");
                        }
                    }
                    else
                    {
                        $giorni= $this->db_get('user_calendario');
                    }
                    foreach ($giorni as $key => $giorno) {
                        $data=$giorno['giorno'];
                        $check_giorno=$this->db_get('user_rapportidilavoro', 'data,recordiddipendenti_', "data='$data' AND recordiddipendenti_='$recordid_dipendente' AND alias='$alias'");
                        if(count($check_giorno)==0)
                        {
                            $recordid_rapportodilavoro= $this->generate_recordid('rapportidilavoro');
                            $tipogiorno=$giorno['tipogiorno'];
                            $nsettimana=$giorno['nsettimana'];
                            $giornosettimana=$giorno['giornosettimana'];
                            $sql="
                                INSERT INTO user_rapportidilavoro 
                                ( recordid_, data, recordiddipendenti_, TipoGiorno, giornodellasettimana, NSettimana, duratalavoro, alias)
                                VALUES
                                ( '$recordid_rapportodilavoro','$data','$recordid_dipendente','$tipogiorno','$giornosettimana','$nsettimana','00:00', '$alias')
                                ";
                            $this->execute_query($sql);
                            $counter_rapporti++;
                        }


                    }

                }
            }
            return $counter_rapporti;
                
                
        }
        
        function genera_rapportidilavoro_presenzemensili($recordid_presenzemensili)
        {
            $record_presenzemensili=$this->db_get_row("user_presenzemensili","*","recordid_='$recordid_presenzemensili'");
            
            $recordid_dipendente=$record_presenzemensili['recordiddipendenti_'];
            $alias=$record_presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND alias='$alias'";
            }
            else
            {
                $alias_condition="AND (alias='' OR alias is null)";
            }
            $anno=$record_presenzemensili['anno'];
            $mese=$record_presenzemensili['mese'];
            
            $giorni= $this->db_get('user_calendario','*',"giorno>='$anno-$mese-01' AND giorno<='$anno-$mese-31'" );

            foreach ($giorni as $key => $giorno) {
                $data=$giorno['giorno'];
                $check_giorno=$this->db_get('user_rapportidilavoro', 'recordid_,data,recordiddipendenti_', "data='$data' AND recordiddipendenti_='$recordid_dipendente' $alias_condition");
                if(count($check_giorno)==0)
                {
                    $recordid_rapportodilavoro= $this->generate_recordid('rapportidilavoro');
                    $tipogiorno=$giorno['tipogiorno'];
                    $nsettimana=$giorno['nsettimana'];
                    $giornosettimana=$giorno['giornosettimana'];
                    $sql="
                        INSERT INTO user_rapportidilavoro 
                        ( recordid_, data, recordiddipendenti_, TipoGiorno, giornodellasettimana, NSettimana, duratalavoro, alias)
                        VALUES
                        ( '$recordid_rapportodilavoro','$data','$recordid_dipendente','$tipogiorno','$giornosettimana','$nsettimana','00:00', '$alias')
                        ";
                    $this->execute_query($sql);
                }
                else
                {
                    $recordid_rapportodilavoro=$check_giorno[0]['recordid_'];
                    $tipogiorno=$giorno['tipogiorno'];
                    $nsettimana=$giorno['nsettimana'];
                    $giornosettimana=$giorno['giornosettimana'];
                    $sql="
                        UPDATE user_rapportidilavoro 
                        SET TipoGiorno='$tipogiorno',giornodellasettimana='$giornosettimana',NSettimana='$nsettimana'
                        WHERE recordid_='$recordid_rapportodilavoro'
                        ";
                    $this->execute_query($sql);
                }


            }
        }
        
        function rapporti2019_dagenerare()
        {
            $dipendenti= $this->db_get('user_dipendenti','*',"situazione <> 'chiuso'");
            echo count($dipendenti)."<br/>";
            $counter=0;
            foreach ($dipendenti as $key => $dipendente) {
                $recordid_dipendente=$dipendente['recordid_'];
                $check_2019=$this->db_get('user_rapportidilavoro', 'data,recordiddipendenti_', "data='2019-01-01' AND recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')");
                if(count($check_2019)==0)
                {
                    
                    echo $recordid_dipendente." - ".$dipendente['id']."<br/>";
                    $counter++;
                }
            }
            echo $counter;
            
        }
        
        function check_dipendenti()
        {
            $sql="
                SELECT user_dipendenti.recordid_,count(user_dipendenti.recordid_) as counter,user_dipendenti.id
                FROM user_dipendenti JOIN user_rapportidilavoro ON user_dipendenti.recordid_=user_rapportidilavoro.recordiddipendenti_
                WHERE (user_rapportidilavoro.alias is null OR user_rapportidilavoro.alias='') AND user_rapportidilavoro.data<='2019-12-31' AND user_rapportidilavoro.data>'2017-12-31'
                group by user_dipendenti.recordid_,user_dipendenti.id
                having count(user_dipendenti.recordid_)<>730 AND count(user_dipendenti.recordid_)<>365
                ";
            $dipendenti= $this->select($sql);
            echo count($dipendenti);
        }
        
        function sistema_rapportidilavoro_dipendenti()
        {
            $sql="
                SELECT user_dipendenti.recordid_,count(user_dipendenti.recordid_) as counter,user_dipendenti.id
                FROM user_dipendenti JOIN user_rapportidilavoro ON user_dipendenti.recordid_=user_rapportidilavoro.recordiddipendenti_
                WHERE  (user_rapportidilavoro.alias is null OR user_rapportidilavoro.alias='') AND user_rapportidilavoro.data<='2019-12-31' AND user_rapportidilavoro.data>'2017-12-31'
                group by user_dipendenti.recordid_,user_dipendenti.id
                having count(user_dipendenti.recordid_)<>730 AND count(user_dipendenti.recordid_)<>365
                ";
          $dipendenti= $this->select($sql);
            foreach ($dipendenti as $key => $dipendente) {
                $recordid_dipendente=$dipendente['recordid_'];
                
                
                 echo $recordid_dipendente." - ".$dipendente['id']." - ".$dipendente['counter'];  
                 $this->sistema_rapportidilavoro($recordid_dipendente);
                 echo " <br/><br/>";
                
                $sql="UPDATE user_dipendenti SET dataupdate='2019-03-15' WHERE recordid_='$recordid_dipendente'";
                $this->execute_query($sql);
            }  
        }
        
        
        
        
        
        function sistema_rapportidilavoro($recordid_dipendente)
        {
            // da aggiungere
            $counter_added=$this->genera_rapportidilavoro($recordid_dipendente);
            
            //da cancellare
            $counter_deleted=0;
            $sql="
                SELECT user_dipendenti.recordid_,user_dipendenti.id,user_rapportidilavoro.data,count(user_rapportidilavoro.data)
                FROM user_dipendenti JOIN user_rapportidilavoro ON user_dipendenti.recordid_=user_rapportidilavoro.recordiddipendenti_ AND (user_rapportidilavoro.alias is null OR user_rapportidilavoro.alias='') AND user_rapportidilavoro.data>'2020-08-01'
                WHERE user_dipendenti.recordid_='$recordid_dipendente'
                group by user_rapportidilavoro.data
                having count(user_rapportidilavoro.data)>1
                ";
            $giorni= $this->select($sql);
            //$giorni= $this->db_get('user_calendario','*',"giorno<='2019-12-31' AND giorno>'2017-12-31'");
            foreach ($giorni as $key => $giorno) {
                $data=$giorno['data'];
                $rapportodilavoro=$this->db_get('user_rapportidilavoro', '*', "data='$data' AND recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')");
                if(count($rapportodilavoro)>1)
                {
                    $giorno1=$rapportodilavoro[0];
                    $giorno2=$rapportodilavoro[1];
                    $recordid2=$giorno2['recordid_'];
                    //unset($giorno1['recordid_']);
                    //unset($giorno2['recordid_']);
                    //if($giorno1===$giorno2)
                    //{
                    if((isempty($giorno2['orainiziomattina']))&&(isempty($giorno2['orafinemattina']))&&(isempty($giorno2['orainiziopomeriggio']))&&(isempty($giorno2['orafinepomeriggio']))&&(isempty($giorno2['situazione'])))
                    {
                        $sql="
                            DELETE FROM user_rapportidilavoro
                            WHERE recordid_='$recordid2'
                            ";
                        $this->execute_query($sql);
                        $counter_deleted++;
                    }
                   /* else
                    {
                        
                            unset($giorno1['recordid_']);
                            unset($giorno2['recordid_']);
                            $equals=true;
                            foreach ($giorno1 as $key => $value) {
                                if($giorno2[$key]!=$value)
                                {
                                    if((isnotempty($value)||(isnotempty($value))))
                                    {
                                        $equals=false;
                                    }
                                }
                            }
                            if($equals)
                            {
                                $sql="
                                    DELETE FROM user_rapportidilavoro
                                    WHERE recordid_='$recordid2'
                                    ";
                                $this->execute_query($sql);
                                $counter_deleted++;
                            }
                        
                        
                    }
                    }*/
                }

            }
            echo "        deleted:".$counter_deleted." added:".$counter_added;
        }
        
        function check_rapportidilavoro($recordid_dipendente)
        {
            echo $recordid_dipendente."<br/>";
            $sql="
                SELECT user_dipendenti.recordid_,count(user_dipendenti.recordid_) as counter,user_dipendenti.id
                FROM user_dipendenti JOIN user_rapportidilavoro ON user_dipendenti.recordid_=user_rapportidilavoro.recordiddipendenti_
                WHERE (user_rapportidilavoro.alias is null OR user_rapportidilavoro.alias='') AND user_rapportidilavoro.data<='2019-12-31' AND user_rapportidilavoro.data>'2017-12-31'
                group by user_dipendenti.recordid_,user_dipendenti.id
                having count(user_dipendenti.recordid_)<>730 AND count(user_dipendenti.recordid_)<>365

                ";
            echo $sql."<br/><br/><br/>";
            $rapportidilavoro= $this->select($sql);
            echo count($rapportidilavoro)."<br/>";
            
            $counter=0;
            $giorni= $this->db_get('user_calendario','*',"giorno<='2019-12-31' AND giorno>'2017-12-31'");
            foreach ($giorni as $key => $giorno) {
                $data=$giorno['giorno'];
                $check_giorno=$this->db_get('user_rapportidilavoro', 'data,recordiddipendenti_', "data='$data' AND recordiddipendenti_='$recordid_dipendente' AND (alias is null OR alias='')");
                        if(count($check_giorno)>1)
                        {
                            $counter++; 
                        }
            }
            echo $counter;
        }
        
        
        
        function genera_rapportidilavoro_2019()
        {
            $dipendenti= $this->db_get('user_dipendenti','*',"situazione <> 'chiuso'");
            echo count($dipendenti)."<br/>";
            foreach ($dipendenti as $key => $dipendente) {
                $recordid_dipendente=$dipendente['recordid_'];
                echo $recordid_dipendente." - ".$dipendente['id']."<br/>";
                $counter_rapporti= $this->genera_rapportidilavoro($recordid_dipendente);
                echo $counter_rapporti."<br/>";
            }
            
        }
        
        function aggiorna_contratti_2019()
        {
            $contratti= $this->db_get('user_contratti','*',"(dataupdate is null OR dataupdate<>'2019-01-03') AND  ((tipocontratto='indet' AND (datadisdetta is null OR datadisdetta='')) OR (tipocontratto='indet' AND datadisdetta>'2018-12-31') OR (tipocontratto='det' AND datafine>'2018-12-31'))");
            echo count($contratti);
            foreach ($contratti as $key => $contratto) {
                $recordid_contratto=$contratto['recordid_'];
                $this->custom_3p_salva_contratto($recordid_contratto);
                $sql="UPDATE user_contratti SET dataupdate='2019-01-03' WHERE recordid_='$recordid_contratto'";
                $this->execute_query($sql);
            }
        }
        
        function check_contratti_2019()
        {
            $contratti= $this->db_get('user_contratti','*',"(dataupdate is null OR dataupdate<>'2019-01-03') AND  ((tipocontratto='indet' AND (datadisdetta is null OR datadisdetta='')) OR (tipocontratto='indet' AND datadisdetta>'2018-12-31') OR (tipocontratto='det' AND datafine>'2018-12-31'))");
            echo count($contratti);
        }
        
        
        
        
        
        function rendi_pubblico($recordid_proposta)
        {
            $sql="
                UPDATE user_candidatiproposti
                SET rendipubblico='si'
                WHERE recordid_='$recordid_proposta'
                ";
            $this->execute_query($sql);
            $recordid_azienda= $this->db_get_value('user_candidatiproposti','recordidaziende_',"recordid_='$recordid_proposta'");
            $mailfrom_userid=1;
            $mailto= $this->db_get_value('user_aziende','email',"recordid_='$recordid_azienda'");
            $mailsubject="18-24 Dati sensibili del potenziale candidato";
            $mailbody="Il profilo del candidato completo di dati sensibili, è ora visibile nell’area “Candidati Selezionati”.<br/>
<br/>                
Per visionarlo può accedere direttamente cliccando il seguente link: https://www.18-24.ch/progetto1824/index.php/App_controller/load_login_aziende<br/>
<br/>
Cordiali saluti<br/>
<br/>
Il Team 18-24
                ";
            $this->push_mail_queue_smart($mailfrom_userid, $mailto, $mailsubject, $mailbody);
        }
        
        
        function set_results_field_changed($tableid,$recordid,$fieldid,$value)
        {
            $table="user_".strtolower($tableid);
            $sql="
                UPDATE $table
                SET $fieldid='$value'
                WHERE recordid_='$recordid'
                ";
            $this->execute_query($sql);
            
            if(($fieldid=='orainiziomattina')||($fieldid=='orafinemattina')||($fieldid=='orainiziopomeriggio')||($fieldid=='orafinepomeriggio')||($fieldid=='orainiziopausamattina')||($fieldid=='orafinepausamattina')||($fieldid=='orainiziopausapomeriggio')||($fieldid=='orafinepausapomeriggio'))
            {
                $record= $this->db_get_row('user_rapportidilavoro','*',"recordid_='$recordid'");
                $entrata1=$record['orainiziomattina'];
                $uscita1=$record['orainiziopausamattina'];
                $entrata2=$record['orafinepausamattina'];
                $uscita2=$record['orafinemattina'];
                $entrata3=$record['orainiziopomeriggio'];
                $uscita3=$record['orainiziopausapomeriggio'];
                $entrata4=$record['orafinepausapomeriggio'];
                $uscita4=$record['orafinepomeriggio'];
                $date_diff[1]=  $this->date_diff($entrata1, $uscita1);
                $date_diff[2]=  $this->date_diff($entrata2, $uscita2);
                $date_diff[3]=  $this->date_diff($entrata3, $uscita3);
                $date_diff[4]=  $this->date_diff($entrata3, $uscita3);
                $minutes = 0; 
                foreach ($date_diff as $time) {
                    $hour=$time['h'];
                    if($hour==null)
                    {
                        $hour=0;
                    }
                    $minute=$time['m'];
                    if($minute==null)
                    {
                        $minute=0;
                    }
                    $minutes += $hour * 60;
                    $minutes += $minute;
                }

                $hours = floor($minutes / 60);
                $minutes -= $hours * 60;
                $durata=sprintf('%02d:%02d', $hours, $minutes);

                $sql="UPDATE user_rapportidilavoro SET duratalavoro='$durata' WHERE recordid_='$recordid'";
                $this->execute_query($sql);
                $sql="UPDATE user_rapportidilavoro SET duratalavoromodificata='no' WHERE recordid_='$recordid'";
                $this->execute_query($sql);
            }
            if($fieldid=='duratalavoro')
            {
                $sql="UPDATE user_rapportidilavoro SET duratalavoromodificata='si' WHERE recordid_='$recordid'";
                $this->execute_query($sql);
            }
            
            $cliente_id=$this->get_cliente_id();
            
            //CUSTOM 3P
            if($cliente_id=='3p')
            {
                if($tableid=='rapportidilavoro')
                {
                    $rapporto=  $this->db_get_row('user_rapportidilavoro','*',"recordid_='$recordid'");
                    $recordid_dipendente=$rapporto['recordiddipendenti_'];
                    $data=$rapporto['data'];
                    $mese=date("n", strtotime($data));
                    $giorno=date("j", strtotime($data));
                    $giorno_fieldid="g".$giorno."d";
                    $presenzemensili=$this->db_get_row('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente' AND mese='$mese'");
                    $presenzemensili_recordid= $presenzemensili['recordid_'];
                    
                    $giorno_value=$presenzemensili[$giorno_fieldid];
                    $giorno_value= str_replace('@sync', '', $giorno_value);
                    $giorno_value=$giorno_value."@sync";
                    $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$presenzemensili_recordid'";
                    $this->execute_query($sql);
                    
                    //$this->add_custom_update('presenze',$presenzemensili_recordid);
                    $this->aggiorna_presenze($presenzemensili_recordid);
                    //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$presenzemensili_recordid','todo')";
                    //$this->execute_query($sql);
                    
                    //Fabio
                    //$this->custom_3p_AggiornaPresenzeMensili_SingoloDipendente($presenzemensili_recordid);
                }
            }
            
        }
        
        public function link_aziende($recordid_tablemaster,$recordid_tabletolink)
        {
            $tabletolink_fields= $this->db_get_row('user_aziende','*',"recordid_='$recordid_tabletolink'");
            foreach ($tabletolink_fields as $tabletolink_field_key => $tabletolink_field_value) 
            {
                $master_value=$this->db_get_value('user_aziende',"$tabletolink_field_key","recordid_='$recordid_tablemaster'");
                if(isempty($master_value)&&(isnotempty($tabletolink_field_value)))
                {
                    $sql="UPDATE user_aziende SET $tabletolink_field_key='$tabletolink_field_value' WHERE recordid_='$recordid_tablemaster'";
                    $this->execute_query($sql);
                    
                }
            }
            $table_links= $this->db_get('sys_table_link','*',"tableid='aziende'");
            foreach ($table_links as $key => $table_link) {
                $tablelinkid=$table_link['tablelinkid'];
                $records= $this->db_get('user_'.$tablelinkid,'*',"recordidaziende_='$recordid_tabletolink'");
                foreach ($records as $key => $record) {
                    $recordid=$record['recordid_'];
                    $sql="UPDATE user_$tablelinkid SET recordidaziende_='$recordid_tablemaster' WHERE recordid_='$recordid'";
                    $this->execute_query($sql);
                            
                }
            }
            
        }
        
        function salva_rapportino_settimanale($post)
        {
            $rapportidilavoro=$post['rapportino'];
            $recordid_presenzemensili=$post['recordid_presenzemensili'];
            $recordid_presenzemensili_prec='';
            $recordid_presenzemensili_succ='';
            
            $presenzemensili= $this->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
            $recordid_dipendente=$presenzemensili['recordiddipendenti_'];
            $presenzemensili_mese=$presenzemensili['mese'];
            $presenzemensili_anno=$presenzemensili['anno'];
            
            $presenzemensili_mese_prec=$presenzemensili_mese-1;
            if($presenzemensili_mese_prec==0)
            {
                $presenzemensili_anno_prec=$presenzemensili_anno-1;
                $presenzemensili_mese_prec=12;
            }
            else
            {
                $presenzemensili_anno_prec=$presenzemensili_anno;
            }                
            $presenzemensili_prec= $this->db_get_row('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente' AND anno='$presenzemensili_anno_prec' AND mese='$presenzemensili_mese_prec'");

            
            $presenzemensili_mese_succ=$presenzemensili_mese+1;
            if($presenzemensili_mese_succ==13)
            {
                $presenzemensili_anno_succ=$presenzemensili_anno_succ+1;
                $presenzemensili_mese_succ=1;
            }
            else
            {
                $presenzemensili_anno_succ=$presenzemensili_anno;
            }
            $presenzemensili_succ= $this->db_get_row('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente' AND anno='$presenzemensili_anno_succ' AND mese='$presenzemensili_mese_succ'");

            
            foreach ($rapportidilavoro as $key => $rapportodilavoro) {
                $recordid_rapportodilavoro=$rapportodilavoro['recordid'];
                
                $sql="SELECT * FROM user_rapportidilavoro  WHERE recordid_='$recordid_rapportodilavoro'";
                $result= $this->select($sql);
                $rapportodilavoro_precedente=$result[0];
                
                    
                $orainiziomattina=$rapportodilavoro['orainiziomattina'];
                if($rapportodilavoro['orainiziomattina']==null)
                {
                    $orainiziomattina='null';
                }
                else
                {
                    $orainiziomattina="'$orainiziomattina'";
                }
                
                $orafinemattina=$rapportodilavoro['orafinemattina'];
                if($rapportodilavoro['orafinemattina']==null)
                {
                    $orafinemattina='null';
                }
                else
                {
                    $orafinemattina="'$orafinemattina'";
                }
                
                $orainiziopomeriggio=$rapportodilavoro['orainiziopomeriggio'];
                if($rapportodilavoro['orainiziopomeriggio']==null)
                {
                    $orainiziopomeriggio='null';
                }
                else
                {
                    $orainiziopomeriggio="'$orainiziopomeriggio'";
                }
                
                $orafinepomeriggio=$rapportodilavoro['orafinepomeriggio'];
                if($rapportodilavoro['orafinepomeriggio']==null)
                {
                    $orafinepomeriggio='null';
                }
                else
                {
                    $orafinepomeriggio="'$orafinepomeriggio'";
                }
                
                
                $entrata3='null';
                if($rapportodilavoro['entrata3']!=null)
                {
                    $entrata3="'".$rapportodilavoro['entrata3']."'";
                }
                
                
                $uscita3='null';
                if($rapportodilavoro['uscita3']!=null)
                {
                    $uscita3="'".$rapportodilavoro['uscita3']."'";
                }
                
                $entrata4='null';
                if($rapportodilavoro['entrata4']!=null)
                {
                    $entrata4="'".$rapportodilavoro['entrata4']."'";
                }
                
                
                $uscita4='null';
                if($rapportodilavoro['uscita4']!=null)
                {
                    $uscita4="'".$rapportodilavoro['uscita4']."'";
                }
                
                
                
                $sql="UPDATE user_rapportidilavoro SET 
                    situazione='".$rapportodilavoro['situazione']."',
                    situazione2='".$rapportodilavoro['situazione2']."',
                    laboratorio='".$rapportodilavoro['laboratorio']."',
                    duratalavoro='".$rapportodilavoro['duratalavoro']."',
                    luogolavoro='".$rapportodilavoro['luogolavoro']."',
                    via='".$rapportodilavoro['via']."',
                    orainiziomattina=$orainiziomattina,
                    orafinemattina=$orafinemattina,
                    orainiziopomeriggio=$orainiziopomeriggio,
                    orafinepomeriggio=$orafinepomeriggio,
                    entrata3=$entrata3,
                    uscita3=$uscita3,
                    entrata4=$entrata4,
                    uscita4=$uscita4,
                    recordidazienda_='".$rapportodilavoro['recordidazienda_']."'
                    WHERE recordid_='$recordid_rapportodilavoro'";
                $this->execute_query($sql);
                
                
                $rapportodilavoro=  $this->db_get_row('user_rapportidilavoro','*',"recordid_='$recordid_rapportodilavoro'");
                $recordid_dipendente=$rapportodilavoro['recordiddipendenti_'];
                $data=$rapportodilavoro['data'];
                $mese=date("n", strtotime($data));
                $giorno=date("j", strtotime($data));
                $giorno_fieldid="g".$giorno."d";
                
                if($mese==$presenzemensili_mese)
                {
                    $giorno_value=$presenzemensili[$giorno_fieldid];
                    $giorno_value= str_replace('@sync', '', $giorno_value);
                    $giorno_value=$giorno_value."@sync";
                    $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$recordid_presenzemensili'";
                    $this->execute_query($sql);
                }
                if($mese==$presenzemensili_mese_prec)
                {
                    $recordid_presenzemensili_prec=$presenzemensili_prec['recordid_'];
                    $giorno_value=$presenzemensili_prec[$giorno_fieldid];
                    $giorno_value= str_replace('@sync', '', $giorno_value);
                    $giorno_value=$giorno_value."@sync";
                    $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$recordid_presenzemensili_prec'";
                    $this->execute_query($sql);
                }
                if($mese==$presenzemensili_mese_succ)
                {
                    $recordid_presenzemensili_succ=$presenzemensili_succ['recordid_'];
                    $giorno_value=$presenzemensili_succ[$giorno_fieldid];
                    $giorno_value= str_replace('@sync', '', $giorno_value);
                    $giorno_value=$giorno_value."@sync";
                    $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$recordid_presenzemensili_succ'";
                    $this->execute_query($sql);
                }
                
                if(($rapportodilavoro['situazione']=='fine')&&($rapportodilavoro_precedente['situazione']!='fine'))
                {
                    $dipendente=$this->db_get_row('user_dipendenti','*',"recordid_='$recordid_dipendente'");
                    $apertura= $this->db_get_row('user_aperture','*',"recordiddipendenti_='$recordid_dipendente'","ORDER BY recordid_ desc");
                    if(isempty($apertura))
                    {
                        $datachiusura=$dipendente['datachiusura'];
                        $datachiusura2=$dipendente['datachiusura2'];
                        $datachiusura3=$dipendente['datachiusura3'];
                        $datachiusura4=$dipendente['datachiusura4'];

                        if(isempty($datachiusura))
                        {
                            $sql="UPDATE user_dipendenti SET situazione='chiuso',attivo='no',datachiusura='$data' WHERE recordid_='$recordid_dipendente'";
                            $this->execute_query($sql);
                        }
                        else
                        {
                            if(isempty($datachiusura2))
                            {
                               $sql="UPDATE user_dipendenti SET situazione='chiuso',attivo='no',datachiusura2='$data' WHERE recordid_='$recordid_dipendente'";
                                $this->execute_query($sql); 
                            }
                            else
                            {
                                if(isempty($datachiusura3))
                                {
                                   $sql="UPDATE user_dipendenti SET situazione='chiuso',attivo='no',datachiusura3='$data' WHERE recordid_='$recordid_dipendente'";
                                    $this->execute_query($sql); 
                                }
                                else
                                {
                                    if(isempty($datachiusura4))
                                    {
                                        $sql="UPDATE user_dipendenti SET situazione='chiuso',attivo='no',datachiusura4='$data' WHERE recordid_='$recordid_dipendente'";
                                        $this->execute_query($sql); 
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $recordid_apertura=$apertura['recordid_'];
                        
                        $sql="UPDATE user_dipendenti SET situazione='chiuso',attivo='no' WHERE recordid_='$recordid_dipendente'";
                        $this->execute_query($sql); 
                        
                        $sql="UPDATE user_aperture SET situazione='chiuso',attivo='no',datachiusura='$data' WHERE recordid_='$recordid_apertura'";
                        $this->execute_query($sql); 
                    }
                    $this->set_ultimadatachiusura($recordid_dipendente);
                    
                    $anno=date("Y", strtotime($data));
                    $mese=date("n", strtotime($data));
                    $presenzemensili=$this->db_get('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente' AND ((anno=$anno AND mese>$mese) OR (anno>$anno))");
                    $dipendente=$this->db_get_row('user_dipendenti','*',"recordid_='$recordid_dipendente'");
                    $dataapertura1=$dipendente['datainiziocollaborazione'];
                    $anno1=date("Y", strtotime($dataapertura1));
                    $mese1=date("n", strtotime($dataapertura1));
                    $dataapertura2=$dipendente['datariapertura'];
                    $anno2=date("Y", strtotime($dataapertura2));
                    $mese2=date("n", strtotime($dataapertura2));
                    $dataapertura3=$dipendente['dataapertura3'];
                    $anno3=date("Y", strtotime($dataapertura3));
                    $mese3=date("n", strtotime($dataapertura3));
                    $dataapertura4=$dipendente['dataapertura4'];
                    $anno4=date("Y", strtotime($dataapertura4));
                    $mese4=date("n", strtotime($dataapertura4));
                    
                    $sql="
                        SELECT recordid_ FROM user_presenzemensili
                        WHERE 
                        recordiddipendenti_='$recordid_dipendente' AND
                        ((anno=$anno AND mese>$mese) OR (anno>$anno)) AND
                        ((anno!=$anno1 AND mese>$mese1)) AND
                        ((anno!=$anno2 AND mese>$mese2)) AND
                        ((anno!=$anno3 AND mese>$mese3)) AND
                        ((anno!=$anno4 AND mese>$mese4))
                        ";
                    foreach ($presenzemensili as $key => $presenzemese) {
                        $recordid_presenzemese=$presenzemese['recordid_'];
                        $sql="DELETE FROM user_presenzemensili WHERE recordid_='$recordid_presenzemese'";
                        $this->execute_query($sql);
                    }
                }
                
                //aggiornamento immediato disponibilità
                /*
                if(($rapportodilavoro['situazione2']=='d')&&($rapportodilavoro_precedente['situazione2']!='d'))
                {
                    $sql="
                        UPDATE user_dipendenti
                        SET disponibiledal='$data'
                        WHERE recordid_='$recordid_dipendente'
                        ";
                    $this->execute_query($sql);
                }
                $nondisponibile=false;
                if(($rapportodilavoro['situazione2']=='Uscito')&&($rapportodilavoro_precedente['situazione2']!='Uscito'))
                {
                   $nondisponibile=true;
                }
                if(($rapportodilavoro['laboratorio']=='Uscito')&&($rapportodilavoro_precedente['situazione2']!='Uscito'))
                {
                    $nondisponibile=true;
                }
                if($nondisponibile)
                {
                    $sql="
                        UPDATE user_dipendenti
                        SET disponibiledal=null
                        WHERE recordid_='$recordid_dipendente'
                        ";
                    $this->execute_query($sql);
                }*/
             $this->aggiorna_disponibilita_dipendente($recordid_dipendente);


                
                
            }
            

            //$this->add_custom_update('presenze',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$recordid_presenzemensili','todo')";
            //$this->execute_query($sql);
            if($recordid_presenzemensili_prec!='')
            {
                //$this->add_custom_update('presenze',$recordid_presenzemensili_prec);
                $this->aggiorna_presenze($recordid_presenzemensili_prec);
                //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$recordid_presenzemensili_prec','todo')";
                //$this->execute_query($sql);
            }
            if($recordid_presenzemensili_succ!='')
            {
                //$this->add_custom_update('presenze',$recordid_presenzemensili_succ);
                $this->aggiorna_presenze($recordid_presenzemensili_succ);
                //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$recordid_presenzemensili_succ','todo')";
                //$this->execute_query($sql);
            }
        }
        
        function salva_contratto_missione($post)
        {
            $fields=$post['fields'];
            $recordid_contratto=$post['recordid_contratto'];
            if($this->isempty($recordid_contratto))
            {
                $recordid_contratto= $this->generate_recordid('contratti');
                $fields['id']= $this->generate_seriale('contratti', 'id');
                $this->insert_record('contratti',1, $fields);
            }
            else
            {
                $this->update_record('contratti',1,$fields,"recordid_='$recordid_contratto'");
            }
            $this->custom_3p_salva_contratto($recordid_contratto);
            $this->custom_3p_genera_contratto_fornitura($recordid_contratto);
            return $recordid_contratto;
            
        }
        
        function salva_contratto_fornitura($post)
        {
            $fields=$post['fields'];
            $recordid_contratto=$post['recordid_contratto'];
            $this->update_record('contrattofornitura',1,$fields,"recordid_='$recordid_contratto'");
            return $recordid_contratto;
            
        }
        
        function salva_note_rapportino($post)
        {
            $osservazioni=$post['osservazioni'];
            $osservazioni=str_replace("'", "''", $osservazioni);
            $recordid_rapportodilavoro=$post['recordid_rapportodilavoro'];
            $recordid_presenzemensili=$post['recordid_presenzemensili'];
            $presenzemensili= $this->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
            $rapportodilavoro=  $this->db_get_row('user_rapportidilavoro','*',"recordid_='$recordid_rapportodilavoro'");
            $data=$rapportodilavoro['data'];
            $mese=date("n", strtotime($data));
            $giorno=date("j", strtotime($data));
            $giorno_fieldid="g".$giorno."d";

            $giorno_value=$presenzemensili[$giorno_fieldid];
            $giorno_value= str_replace("'", "", $giorno_value);
            $giorno_value= str_replace('@sync', '', $giorno_value);
            $giorno_value=$giorno_value."@sync";
                
            $sql="UPDATE user_rapportidilavoro SET osservazioni='$osservazioni' WHERE recordid_='$recordid_rapportodilavoro'";
            $this->execute_query($sql);
                        
            $sql="UPDATE user_presenzemensili SET $giorno_fieldid='$giorno_value' WHERE recordid_='$recordid_presenzemensili'";
            $this->execute_query($sql);
                
            //$this->add_custom_update('presenze',$recordid_presenzemensili);
            $this->aggiorna_presenze($recordid_presenzemensili);
            //$sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('presenze','$recordid_presenzemensili','todo')";
            //$this->execute_query($sql);
        }
        
        function salva_note_dipendente($post)
        {
            $note=$post['note'];
            $note= str_replace("'", "''", $note);
            $recordid_dipendente=$post['recordid_dipendente'];
            $recordid_presenzemensili=$post['recordid_presenzemensili'];
            $presenzemensili= $this->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
                
            $sql="UPDATE user_dipendenti SET notegenerali='$note' WHERE recordid_='$recordid_dipendente'";
            $this->execute_query($sql);
                        
            $sql="UPDATE user_presenzemensili SET note='$note' WHERE recordiddipendenti_='$recordid_dipendente' AND recordid_>='$recordid_presenzemensili'";
            $this->execute_query($sql);
                
        }
        
        function salva_nota_temporizzata($post)
        {
            $userid= $this->get_userid();
            $username= $this->get_username();
            $fields['nota']=str_replace("'", "''", $post['nota']);
            $dal=$post['dal'];
            $fields['dal']=$dal;
            $anno_dal=date('Y',strtotime($dal));
            $mese_dal=date('n',strtotime($dal));
            $al=$post['al'];
            $fields['al']=$al;
            $anno_al=date('Y',strtotime($al));
            $mese_al=date('m',strtotime($al));
            $recordid_dipendente=$post['recordid_dipendente'];
            $fields['recordiddipendenti_']=$recordid_dipendente;
            $fields['inseritada']=$username;
            
            $recordid_nota=$post['recordid_nota'];
            if($this->isempty($recordid_nota))
            {
                $fields['id']= $this->generate_seriale('note', 'id');
            
                $recordid=$this->insert_record('note', $userid, $fields);
            }
            else
            {
                $this->update_record('note', $userid, $fields, "recordid_='$recordid_nota'");
                $recordid=$recordid_nota;
            }
            
            $presenzemensili=$this->db_get('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente'");
            foreach ($presenzemensili as $key => $presenzamensile) {
                $this->set_notetempo_presenzemensili($presenzamensile['recordid_']);
            }
            
            return $recordid;  
            
                
        }
        
        function cancella_nota_temporizzata($post)
        {
            $recordid_nota=$post['recordid_nota'];
            $sql="DELETE FROM user_note WHERE recordid_='$recordid_nota'";
            $this->execute_query($sql);
        }
        
        
        function termina_nota_temporizzata($post)
        {
            $recordid_nota=$post['recordid_nota'];
            $nota= $this->get_record('note',$recordid_nota);
            $recordid_dipendente=$nota['recordiddipendenti_'];
            $dal=$nota['dal'];
            $today=date("Y-m-d");
            if($dal==$today)
            {
                $fine=$today;
            }
            else
            {
                $fine=date("Y-m-d", strtotime("-1 day"));
            }
            
            
            $sql="UPDATE user_note SET al='$fine' WHERE recordid_='$recordid_nota'";
            $this->execute_query($sql);
            
            $presenzemensili=$this->db_get('user_presenzemensili','*',"recordiddipendenti_='$recordid_dipendente'");
            foreach ($presenzemensili as $key => $presenzamensile) {
                $this->set_notetempo_presenzemensili($presenzamensile['recordid_']);
            }
        }
        
        function set_notetempo_presenzemensili($recordid_presenze)
        {
            $notetempo='';
            $record_presenze= $this->get_record('presenzemensili', $recordid_presenze);
            $current_year_month=date('Y-m');
            $anno=$record_presenze['anno'];
            $mese=$record_presenze['mese'];
            $mese=sprintf('%02d', $mese);
            if($current_year_month=="$anno-$mese")
            {
                $date=date('Y-m-d');
            }
            else
            {
                $date=$anno.$mese."31";
            }
            
            $recordid_dipendente=$record_presenze['recordiddipendenti_'];
            $note=$record_presenze['note'];
            $notetempo=$notetempo.$note;
            $note_temporizzate=$this->db_get('user_note','*',"recordiddipendenti_='$recordid_dipendente' AND dal<='$date' AND (al>='$date' OR al is null)");
            foreach ($note_temporizzate as $key => $nota_temporizzata) {
                if($this->isnotempty($notetempo))
                {
                    $notetempo=$notetempo.", ";
                }
                $notetempo=$notetempo.$nota_temporizzata['nota'];
                
            }
            $notetempo= str_replace("'", "''", $notetempo);
            $sql="UPDATE user_presenzemensili SET notetempo='$notetempo' WHERE recordid_='$recordid_presenze'";
            $this->execute_query($sql);
            
            $this->set_ultimenote($recordid_dipendente);
            
        }
        
        
        function salva_zonelavorative_dipendente($post)
        {
            $zonelavorative=$post['zonelavorative'];
            $recordid_dipendente=$post['recordid_dipendente'];
            $recordid_presenzemensili=$post['recordid_presenzemensili'];
            $presenzemensili= $this->db_get_row('user_presenzemensili','*',"recordid_='$recordid_presenzemensili'");
                
            $sql="UPDATE user_dipendenti SET zonelavorative='$zonelavorative' WHERE recordid_='$recordid_dipendente'";
            $this->execute_query($sql);
                        
            $sql="UPDATE user_presenzemensili SET zonelavorative='$zonelavorative' WHERE recordiddipendenti_='$recordid_dipendente' AND recordid_>='$recordid_presenzemensili'";
            $this->execute_query($sql);
                
        }
        
        
        //custom 3p
        function add_custom_update($funzione,$recordid)
        {
            $row=$this->db_get_row("custom_update","*","funzione='$funzione' AND recordid='$recordid' AND stato='todo'");
            if($row==null)
            {
                $sql="INSERT INTO custom_update (funzione,recordid,stato) VALUES ('$funzione','$recordid','todo')";
                $this->execute_query($sql); 
            }
            
        }
        
        function add_custom_update_php($funzione,$recordid='')
        {
            $row=$this->db_get_row("custom_update_php","*","funzione='$funzione' AND recordid='$recordid' AND stato='todo'");
            if($row==null)
            {
                $sql="INSERT INTO custom_update_php (funzione,recordid,stato) VALUES ('$funzione','$recordid','todo')";
                $this->execute_query($sql); 
            }
            
        }
        
        function get_custom_update()
        {
            $custom_update=$this->db_get_row("custom_update_php","*","stato='todo'","order by ID asc"); 
            return $custom_update;
            
        }
        
        function get_custom_update_list()
        {
            $custom_update_list=$this->db_get("custom_update_php","*","stato='todo'","order by ID asc"); 
            return $custom_update_list; 
        }
        
        
        function calcola_mediaperiodo($post)
        {
            $id=$post['id'];
            $dal=$post['dal'];
            $al=$post['al'];
            $recordid= $this->db_get_value('user_dipendenti','recordid_',"id='$id'");
            $this->add_custom_update('mediePeriodo',"$recordid#$dal#$al");
            $counter=0;
            while($counter<300){
                $check_todo=$this->db_get_row('custom_update','*',"funzione='mediePeriodo' AND recordid='$recordid#$dal#$al' AND stato='todo'");
				
                if($check_todo==null)
                {
                    $counter=300;
                }
                sleep(1);
                $counter++;
            }
            $mediePeriodo=$this->db_get_value('user_dipendenti','mediasulperiodo',"recordid_='$recordid'");
            
            return $mediePeriodo;
        }
        
        
        //CUSTOM 18-24
        function  check_newsletter_aziende($recordid_nuovocandidato)
        {
            $newsletters= $this->db_get('user_newsletter_aziende');
            foreach ($newsletters as $key => $newsletter) 
            {
                $recordid_newsletter_azienda=$newsletter['recordid_'];
                $recordid_azienda=$newsletter['recordidaziende_'];
                $fields=$newsletter['fields'];
                $query=$newsletter['query'];
                $query= str_replace('ORDER BY id DESC', '', $query);
                $sql=$query." AND user_candidati.recordid_='$recordid_nuovocandidato'";
                $result= $this->select($sql);
                if(count($result)>0)
                {
                    $azienda=$this->db_get_row('user_aziende','ragionesociale,email',"recordid_='$recordid_azienda'");
                    $ragsoc_azienda=$azienda['ragionesociale'];
                    $email_azienda=$azienda['email'];
                    $fields= str_replace(';', '<br/>', $fields);
                    $mailfrom_userid=1;
                    $mailto=$email_azienda;
                    $mailsubject="18-24 potenziale candidato";
                    $annulla_url="https://www.18-24.ch/progetto1824/index.php/app_controller/annulla_newsletter_azienda/$recordid_newsletter_azienda/$recordid_azienda";
                    $fields_candidato=$this->get_fields_candidato($recordid_nuovocandidato);
                    $candidato_values='';
                    if(array_key_exists('rif',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['rif'];
                    }
                    if(array_key_exists('diploma',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['diploma'];
                    }
                    if(array_key_exists('localita',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['localita'];
                    }
                    if(array_key_exists('datanascita',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['datanascita'];
                    }
                    if(array_key_exists('sesso',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['sesso'];
                    }
                    if(array_key_exists('percentualeoccupazione',$fields_candidato))
                    {
                        $candidato_values=$candidato_values."<br/>".$fields_candidato['percentualeoccupazione'];
                    }
                    $mailbody="
                        Spettabile $ragsoc_azienda,
                        Si è appena iscritto un/una giovane su 18-24 con i seguenti requisiti<br/>
                        $fields
                        <br/>
                        CANDIDATO: <BR/>
                        $candidato_values
                        <br/>
                        <a href='$annulla_url'>Annulla notifiche email per questi requisiti</a>
                        Cordiali saluti<br/>
                        <br/>
                        Il Team 18-24
                        ";
                    $this->push_mail_queue_smart($mailfrom_userid, $mailto, $mailsubject, $mailbody,'','jdwalert@gmail.com');
                }
            }
            
        }
        
        function get_fields_candidato($recordid)
        {
            $fields=$this->db_get_row('user_candidati','*',"recordid_='$recordid'");
            $columns= $this->get_columns('candidati');
            //$fields= $this->convert_fields_value_to_final_value('candidati',$columns,$fields);
            $formazioni= $this->db_get('user_studi','*',"recordidcandidati_='$recordid' ");
            $diploma='';
            foreach ($formazioni as $key => $formazione) {
                $titoloaggiuntivo='';
                if(($formazione['tiposcuola']!='Scole elementari')&&($formazione['tiposcuola']!='Scuole medie'))
                {
                    if($formazione['nomescuolasuperiore']=='Scuola Cantonale di Commercio Bellinzona')
                    {
                        $titoloaggiuntivo=' Scuola Cantonale di Commercio Bellinzona ';
                    }
                    if($formazione['tipolaurea']=='Bachelor')
                    {
                        $titoloaggiuntivo=' Bachelor ';
                    }
                    if($formazione['tipolaurea']=='Master')
                    {
                        if(isempty($formazione['titoloconseguito_master']))
                        {
                            $titoloaggiuntivo=$titoloaggiuntivo.' Master ';
                        }
                    }
                    $titoloaggiuntivo=$titoloaggiuntivo.$formazione['titoloconseguito'].$formazione['nomeuniversita'].$formazione['titoloconseguito_bachelor'].$formazione['titoloconseguito_master'];

                }
                if(($diploma!='')&&($titoloaggiuntivo!=''))
                {
                    $diploma=$diploma.'-';
                }
                $diploma=$diploma.$titoloaggiuntivo;
            }
             
            $fields['diploma']=$diploma;
            return $fields;
        }
        
        public function get_columns($tableid)
        {
            $columns=array();
            $fields=$this->db_get('sys_field', '*', "tableid='$tableid'");
            foreach ($fields as $key => $field) {
                $column['id']=$field['fieldid'];
                $column['desc']=$field['description'];
                $column['fieldtypeid']=$field['fieldtypeid'];
                $column['linkedtableid']='';
                $column['lookuptableid']=$field['lookuptableid'];
                $columns[$field['fieldid']]=$column;
            }
            return $columns;

        }
        
        public function generate_temp_control($cliente='',$datadal='',$dataal='')
        {
            $sql="DELETE FROM user_tempcontrol";
            $this->execute_query($sql);
            $sql="
                SELECT con.*,dip.cognome,dip.nome,dip.tipopermesso,dip.datanascita,dip.avs,dip.nazionalita
                FROM (user_contratti as con JOIN user_dipendenti as dip  ON con.recordiddipendenti_=dip.recordid_) JOIN user_azienda as az ON con.recordidazienda_=az.recordid_
                WHERE true
                ";
            if($cliente!='')
            {
                $sql=$sql." AND az.ragionesociale like '%$cliente%'";
            }
            $rapportidilavoro_date_condition='';
            if(($datadal!='')&&($dataal!=''))
            {
                $sql=$sql."
                AND con.recordid_ IN(
                SELECT recordid_
                FROM  
                (
                SELECT *,datainizio as 'datainizioeffettiva',if(tipocontratto='indet',if(datadisdetta is null,'2100-01-01',datadisdetta),if(datadisdetta<datafine,datadisdetta,datafine) ) as 'datafineeffettiva' 
                FROM user_contratti  
                ) as supporto
                WHERE

                    ((datainizioeffettiva<='$datadal') AND (datafineeffettiva>='$datadal') )
                        OR
                        ((datainizioeffettiva>='$datadal') AND (datainizioeffettiva<='$dataal') )


                )
                ";
                $rapportidilavoro_date_condition=" AND (user_rapportidilavoro.data>='$datadal') AND (user_rapportidilavoro.data<='$dataal') ";
            }
            $contratti= $this->select($sql);
            
            $sql="
                SELECT user_contratti.recordid_,sum(TIME_TO_SEC(user_rapportidilavoro.duratalavoro)) as totsecondi
FROM user_contratti  JOIN user_rapportidilavoro ON user_contratti.recordid_=user_rapportidilavoro.recordidcontratti_
WHERE
    true 
    $rapportidilavoro_date_condition
GROUP BY user_contratti.recordid_  HAVING user_contratti.recordid_ 


                ";
            $contratti_totsecondi_results= $this->select($sql);
            
            
            
            foreach ($contratti as $key => $contratto) {
                
                    $this->calcola_tempcontrol_contratto($contratto,$contratti_totsecondi_results);
                }
            
            
        }
        
        function calcola_tempcontrol_contratto($contratto,$contratti_totsecondi_results)
        {
            
                
                
                
               $recordid_contratto=$contratto['recordid_'];
                $azienda= $this->get_record('azienda', $contratto['recordidazienda_']);
                $dipendente= $this->get_record('dipendenti', $contratto['recordiddipendenti_']);
                $professione=$this->get_record('professioni', $contratto['recordidprofessioni_']);
                $ccl=$this->get_record('ccl', $professione['recordidccl_']);
                
                $fields['id']= $this->generate_seriale('tempcontrol', 'id');
                $fields['ncontratto']=$contratto['id'];
                $fields['cognome']=$dipendente['cognome'];
                $fields['nome']=$dipendente['nome'];
                $fields['permesso']=$dipendente['tipopermesso'];
                $fields['datadinascita']=$dipendente['datanascita'];
                $diff = date_diff(date_create(), date_create($dipendente['datanascita']));
                $fields['eta']=$diff->y;
                $fields['filiale']='';
                $fields['avs']=$dipendente['avs'];
                $fields['nazionalita']=$dipendente['nazionalita'];
                $fields['cliente']=$azienda['ragionesociale'];
                $fields['luogoimpiego']=$contratto['luogomissione'];
                $fields['ccl']=$ccl['nomeccl'];
                $fields['inquadramento']=$professione['professione'];
                $fields['iniziomissione']=$contratto['datainizio'];
                $fields['finemissione']=$contratto['datafine'];
                if(isnotempty($contratto['datadisdetta']))
                {
                   $fields['finemissione']= $contratto['datadisdetta'];
                }
                
                
                $fields['salariolordobase']=$contratto['salarioorariolordobase'];
                $fields['indenvacanze']=$contratto['indennitavacanza'];
                $fields['indenvacanzeperc']=$contratto['indennitavacanzaperc'];
                if(isnotempty($fields['indenvacanzeperc']))
                {
                    $fields['indenvacanzeperc']=$fields['indenvacanzeperc']."%";
                }
                $fields['indenfestivita']=$contratto['giornifestivi'];
                $fields['indenfestivitaperc']=$contratto['giornifestiviperc'];
                if(isnotempty($fields['indenfestivitaperc']))
                {
                    $fields['indenfestivitaperc']=$fields['indenfestivitaperc']."%";
                }
                $fields['tredicesima']=$contratto['tredicesima'];
                $fields['tredicesimaperc']=$contratto['tredicesimaperc'];
                if(isnotempty($fields['tredicesimaperc']))
                {
                    $fields['tredicesimaperc']=$fields['tredicesimaperc']."%";
                }
                
                if((isnotempty($contratto['indennitachilometrca']))||(isnotempty($contratto['indennitapasto']))||(isnotempty($contratto['indennitaaltre'])))
                {
                    $fields['spese']='Si';
                }
                else
                {
                    $fields['spese']='No';
                }
                
                //calcolo ore inizio
                $totsecondi=0;
                $totore=0;
                
               /* $sql="
                    select sum(TIME_TO_SEC(user_rapportidilavoro.duratalavoro)) as totsecondi
                    from user_rapportidilavoro
                    where user_rapportidilavoro.recordidcontratti_='$recordid_contratto'
                    ";
                $results= $this->select($sql);
                
                if(count($results)>0)
                {
                    $totsecondi=$results[0]['totsecondi'];
                }*/
                $key = array_search($recordid_contratto, array_column($contratti_totsecondi_results, 'recordid_'));
                $totsecondi=$contratti_totsecondi_results[$key]['totsecondi'];
                echo $totsecondi.'<br/>';
                $fields['nore']=$totsecondi;
                $totore=$totsecondi/3600;
                echo $totore.'<br/>';
                $fields['nore']=round($totore,2);
                //$totore=intval($totore);
                //$totminuti=($totsecondi-($totore*3600))/60;
                
                //$fields['totore']=$totore.":".$totminuti;
                //calcolo ore fine
                
                $fields['recordidcontratti_']=$contratto['recordid_'];
                $fields['recordiddipendenti_']=$contratto['recordiddipendenti_'];
                $fields['recordidazienda_']=$contratto['recordidazienda_'];
                
                $this->insert_record('tempcontrol',1,$fields);
            
        }
        
        function calcola_3mesi_dipendenti()
        {
            $sql="SELECT recordid_,id FROM user_dipendenti ORDER BY recordid_ DESC";
            $results= $this->select($sql);
            foreach ($results as $key => $row) {
                $recordid_dipendente=$row['recordid_'];
                $id_dipendente=$row['id'];
                $this->add_custom_update_php('calcola_3mesi_dipendente', $recordid_dipendente);
            }
            echo "Dipendenti di cui fare calcola 3 mesi:".count($results);
        }
        
        
        function calcola_3mesi_dipendente($recordid_dipendente)
        {
            $this->syslog($recordid_dipendente,"calcola_3mesi_dipendente start");
            $sql="SELECT anno,mese FROM user_presenzemensili WHERE recordiddipendenti_='$recordid_dipendente'";
            $results= $this->select($sql);
            foreach ($results as $key => $row) {
                $anno=$row['anno'];
                $mese=$row['mese'];
                $this->calcola_3mesi($recordid_dipendente, $mese, $anno);
            }
            $this->syslog('',"calcola_3mesi_dipendente end");
        }
        
        function calcola_3mesi($recordid_dipendente,$mese,$anno)
        {
            
            $tremesi='';
            $data_finemese=date("Y-m-t", strtotime("$anno-$mese-01"));
            $data_oggi=date('Y-m-d');
            $sql="
                SELECT datainizio,datadisdetta,tipocontratto
                FROM user_contratti
                WHERE recordiddipendenti_='$recordid_dipendente' AND datainizio<'$anno-$mese-31'
                ORDER BY datainizio asc
                ";
           $contratti= $this->select($sql);
           if(count($contratti)<1)
           {
               $data_3mesi='NO Esterno';
           }
           else
           {
                $datainizio=$contratti[0]['datainizio'];
                $data_fine3mesi=date('d.m.Y', strtotime($datainizio . "+90 days") );
                $data_3mesi=$data_fine3mesi;
                
                $meseinizio=date('m', strtotime($datainizio));
                $annoinizio=date('Y', strtotime($datainizio));
                $meseoggi=date('m');
                $annooggi=date('Y');
                if(($mese==$meseoggi)&&($anno==$annooggi))
                {
                   $date1 = new DateTime(); 
                }
                else
                {
                    
                    $date1 = new DateTime($data_finemese); 
                }
                $date2 = new DateTime($datainizio);

                $diff = $date2->diff($date1);

                $diff= $diff->format('%r%a');
                $diff=(int)($diff);
                if($diff>=90)
                {
                    $data_3mesi='X';
                }
           }
           $indeterminato=false;
           $disdetta=false;
           foreach ($contratti as $key => $contratto) {
               
               $indeterminato=false;
               if($contratto['tipocontratto']=='indet')
               {
                   $indeterminato=true;
                   $disdetta=false;
                   $datadisdetta=$contratto['datadisdetta'];
                    if(isnotempty($datadisdetta))
                    {
                        $disdetta=true;
                    }
               }
               
           }
           if($indeterminato)
           {
               $data_3mesi='INDETERMINATO';
           }
           if(($disdetta)&&($datadisdetta<$data_finemese)&&($datadisdetta<date($data_oggi)))
           {
               $data_3mesi='X';
           }
           
           $sql="
               UPDATE user_presenzemensili SET 3mesidatascadenza='$data_3mesi' WHERE recordiddipendenti_='$recordid_dipendente' AND mese='$mese' AND anno='$anno'
               ";
           $this->execute_query($sql);
        }
        
        function aggiorna_giorni_prova_dipendente($recordid_dipendente)
        {
            $this->syslog($recordid_dipendente,'aggiorna_giorni_prova_dipendente start');
            $dipendente= $this->db_get_row('user_dipendenti', '*', "recordid_='$recordid_dipendente'");
            $data_inizio=$dipendente['datainiziocollaborazione'];
            $counter=0;
            $num_giorniprova=0;
            $condition='';
            while($num_giorniprova<5)
            {
                $data=date('Y-m-d', strtotime("+ $counter day", strtotime($data_inizio)));
                $timbratura= $this->db_get_row('user_rapportidilavoro','*',"recordiddipendenti_='$recordid_dipendente' AND data='$data'");
                if($timbratura['tipogiorno']=='feriale')
                {
                    if($condition!='')
                    {
                        $condition=$condition.' OR';
                    }
                    $condition=$condition." data='$data'";
                    $num_giorniprova=$num_giorniprova+1;
                    
                }
                $counter=$counter+1;
            }
            if($condition!='')
            {
                $sql="UPDATE user_rapportidilavoro
                    SET prova='si'
                    WHERE  recordiddipendenti_='$recordid_dipendente' AND ($condition)
                   ";
                $this->execute_query($sql);
            }
            $this->syslog("",'aggiorna_giorni_prova_dipendente end');
        }
        
        function aggiorna_giorni_notifica_dipendente($recordid_dipendente)
        {
            $this->syslog($recordid_dipendente,"aggiorna_giorni_notifica_dipendente start");
            $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica=null
                    where recordiddipendenti_='$recordid_dipendente'
                    ";
            $this->execute_query($sql);
            $notifiche= $this->db_get('user_notifiche',"*","recordiddipendenti_='$recordid_dipendente'");
            foreach ($notifiche as $key => $notifica) {
                $datainizio=$notifica['datainizio'];
                $datafine=$notifica['datafine'];
                $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-inizio'
                    where recordiddipendenti_='$recordid_dipendente' AND data='$datainizio'
                    ";
                $this->execute_query($sql);
                $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si'
                    where recordiddipendenti_='$recordid_dipendente' AND data>'$datainizio' AND data<'$datafine'
                    ";
                $this->execute_query($sql);
                $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-fine'
                    where recordiddipendenti_='$recordid_dipendente' AND  data='$datafine'
                    ";
                $this->execute_query($sql);
                
                
                if($datainizio==$datafine)
                {
                    $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-giornata'
                    where recordiddipendenti_='$recordid_dipendente' AND data='$datainizio'
                    ";
                $this->execute_query($sql);
                }
                
                //accorciamento
                $accorciamento_notifica=$notifica['accorciamento_notifica'];
                if(isnotempty($accorciamento_notifica))
                {
                    $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-fine'
                    where recordiddipendenti_='$recordid_dipendente' AND  data='$accorciamento_notifica'
                    ";
                    $this->execute_query($sql);
                    
                    $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-accorciamento'
                    where recordiddipendenti_='$recordid_dipendente' AND data>'$accorciamento_notifica' AND data<'$datafine'
                    ";
                    $this->execute_query($sql);
                    
                    $sql="
                    UPDATE user_rapportidilavoro
                    SET notifica='si-fine-accorciamento'
                    where recordiddipendenti_='$recordid_dipendente' AND data='$datafine'
                    ";
                    $this->execute_query($sql);
                    
                }

            }
            $this->syslog('',"aggiorna_giorni_notifica_dipendente end");
        }
        
        function aggiorna_giorni_dipendenteinterno_dipendente($recordid_dipendente)
        {
            $this->syslog($recordid_dipendente,"aggiorna_giorni_dipendenteinterno_dipendente start");
            $dipendentiinterni_periodi= $this->db_get('user_dipendentiinterni',"*","recordiddipendenti_='$recordid_dipendente'");
            foreach ($dipendentiinterni_periodi as $key => $dipendentiinterni_periodo) {
                $datainizio=$dipendentiinterni_periodo['dal'];
                $datafine=$dipendentiinterni_periodo['al'];
                $sql="
                    UPDATE user_rapportidilavoro
                    SET dipendenteinterno='si'
                    where recordiddipendenti_='$recordid_dipendente' AND data>='$datainizio' AND data<='$datafine'
                    ";
                $this->execute_query($sql);
                
            }
            
           $this->syslog('',"aggiorna_giorni_dipendenteinterno_dipendente end"); 
        }
       
        function aggiorna_primogiorno_dipendente($recordid_dipendente)
        {
            $this->syslog($recordid_dipendente,"aggiorna_primogiorno_dipendente start");
            $contratti= $this->db_get('user_contratti',"*","recordiddipendenti_='$recordid_dipendente'");
            foreach ($contratti as $key => $contratto) {
                $datainizio=$contratto['datainizio'];
                $sql="
                    UPDATE user_rapportidilavoro
                    SET primogiorno='si'
                    where recordiddipendenti_='$recordid_dipendente' AND data='$datainizio'
                    ";
                $this->execute_query($sql);
                
            }
            $this->syslog('',"aggiorna_primogiorno_dipendente end");
            
            
        }
        
        
        function link_timbrature_presenzemensili_dipendenti()
        { 
            $sql="
                select user_dipendenti.recordid_,user_dipendenti.id,user_dipendenti.cognome,user_dipendenti.nome 
from user_dipendenti join user_rapportidilavoro on user_dipendenti.recordid_=user_rapportidilavoro.recordiddipendenti_
where (user_rapportidilavoro.recordidpresenzemensili_ is null OR user_rapportidilavoro.recordidpresenzemensili_ = '')
group by user_dipendenti.recordid_,user_dipendenti.id,user_dipendenti.cognome,user_dipendenti.nome
                
                ";
            $dipendenti= $this->Sys_model->select($sql);
            foreach ($dipendenti as $key => $dipendente) {
                $recordid_dipendente=$dipendente['recordid_'];
                $id_dipendente=$dipendente['id'];
                $this->add_custom_update_php('link_timbrature_presenzemensili_dipendente', $recordid_dipendente);
            }
            echo "Dipendenti di cui fare link_tibrature_presenze:".count($dipendenti);
        }
        

        
        function link_timbrature_presenzemensili_dipendente($recordid_dipendente)
        {
            
            $sql="
                SELECT * FROM user_rapportidilavoro WHERE recordiddipendenti_='$recordid_dipendente' AND (recordidpresenzemensili_ is null OR recordidpresenzemensili_='')
                ";
            $timbrature= $this->select($sql);
            echo count($timbrature)." rapporti di lavoro da elaborare. <br/>";
            $counter=0;
            foreach ($timbrature as $key => $row) {
                
                $recordid=$row['recordid_'];
                $recordid_dipendente=$row['recordiddipendenti_'];
                $data=$row['data'];
                $mese=date('m', strtotime($data));
                $anno=date('Y', strtotime($data));
                $recordid_presenzemensili= $this->db_get_value('user_presenzemensili','recordid_',"mese='$mese' AND anno='$anno' AND recordiddipendenti_='$recordid_dipendente'");
                if($recordid_presenzemensili!=null)
                {
                    $counter++;
                    $sql="
                        UPDATE user_rapportidilavoro SET recordidpresenzemensili_='$recordid_presenzemensili' WHERE recordid_='$recordid'
                        ";
                    $this->execute_query($sql);
                }
                else
                {
                    $counter++;
                    $sql="
                        UPDATE user_rapportidilavoro SET recordidpresenzemensili_='' WHERE recordid_='$recordid'
                        ";
                    $this->execute_query($sql);
                }
                
               
            }
            echo "$counter rapporti di lavoro elaborati <br/>";
        }
        
        function link_presenzemensili_timbrature($recordid_presenzemensili)
        {
            $presenzemensili= $this->get_record('presenzemensili', $recordid_presenzemensili);
            $alias=$presenzemensili['alias'];
            if(isnotempty($alias))
            {
                $alias_condition="AND alias='$alias'";
            }
            else
            {
                $alias_condition="AND (alias='' OR alias is null)";
            }
            $anno=$presenzemensili['anno'];
            $mese=$presenzemensili['mese'];
            $mese=sprintf('%02d', $mese);
            $recordid_dipendente=$presenzemensili['recordiddipendenti_'];

            $sql="
                UPDATE user_rapportidilavoro
                SET recordidpresenzemensili_='$recordid_presenzemensili'
                where recordiddipendenti_='$recordid_dipendente' AND data>='$anno-$mese-01' AND data<='$anno-$mese-31' $alias_condition
                ";
            $this->execute_query($sql);
        }
        
        function calcola_media_contratti()
        {
            $contratti= $this->db_get('user_contratti','*',"tipoorario='giorno' OR tipoorario='settimana'",'ORDER BY id DESC','LIMIT 200');
            foreach ($contratti as $key => $contratto) {
                $this->add_custom_update_php('calcola_media_contratto', $contratto['recordid_']);
            }
            echo 'Aggiunti '.count($contratti).' contratti';
        }
        
        /*function calcola_media_contratto($recordid_contratto)
        {
            $contratto= $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");
            $tipoorario=$contratto['tipoorario'];
            if(($tipoorario=='giorno')||($tipoorario=='settimana'))
            {
                $orario=$contratto['ore'];
                
                $mediacontrattuale=null;
                if($tipoorario=='giorno')
                {
                    $mediacontrattuale= str_replace(".", ":", $orario);
                    if(strpos($mediacontrattuale,':')==FALSE)
                    {
                        $mediacontrattuale=$mediacontrattuale.":00";
                    }
                }
                if($tipoorario=='settimana')
                {
                    $mediacontrattuale= str_replace(".", ":", $orario);
                    if(strpos($mediacontrattuale,':')==FALSE)
                    {
                        $mediacontrattuale=$mediacontrattuale.":00";
                    }
                    $mediacontrattuale_decimale=time_to_decimal(str_replace('.', ':', $mediacontrattuale)) / 5;
                    $mediacontrattuale=decimal_to_time($mediacontrattuale_decimale);
                }
                
                $sql="
                        UPDATE user_contratti
                        SET mediagiornalieracontrattuale='$mediacontrattuale'
                        WHERE recordid_='$recordid_contratto'
                        ";
                $this->execute_query($sql);

                echo $recordid_contratto.'<br/>';
                echo $contratto['id'].'<br/>';

                $tipocontratto=$contratto['tipocontratto'];
                $datainizio_contratto=$contratto['datainizio'];
                $datafine_contratto=$contratto['datafine'];
                $datadisdetta=$contratto['datadisdetta'];
                if(isnotempty($datadisdetta))
                {
                    $datafine_contratto=$datadisdetta;
                }
                if(isempty($datafine_contratto))
                {
                    $datafine_contratto=date('Y-m-d');
                }
                $mediagiornaliera_contrattuale=$contratto['mediagiornalieracontrattuale'];
                $date_diff=  date_diff(date_create($datainizio_contratto), date_create($datafine_contratto));
                $days=$date_diff->days;
                if($days<=365)
                {
                    $datainizio_media=$datainizio_contratto;
                }
                else
                {
                    $datainizio_media=date('Y-m-d', strtotime('-12 month', strtotime(date('Y-m-d'))));
                }

                $mese_inizio=date('m', strtotime($datainizio_contratto));
                $mese_fine=date('m',strtotime($datafine_contratto));
                if($mese_inizio==$mese_fine)
                {
                    //determinato con mese inizio=mese fine
                    $datafine_media=$datafine_contratto;
                }
                else
                {
                    $today_day=date('j');
                    if($today_day<5)
                    {
                        $datafine_media=date("Y-m-t",strtotime('-2 month', strtotime(date('Y-m'))));
                    }
                    else
                    {
                        $datafine_media=date("Y-m-t",strtotime('-1 month', strtotime(date('Y-m'))));
                    }
                }



                //totale ore
                $sql="
                    SELECT recordidcontratti_,ROUND(sum(TIME_TO_SEC(durataxcalcolo)) / 3600,2) as ore
                    from(
                    SELECT recordidcontratti_,data,situazione,duratalavoro,duratalavoromodificata,if(situazione='a' OR situazione='f' OR situazione='fnp' OR situazione='inf' OR situazione='kr' OR situazione='mal' OR situazione='mat' OR situazione='nd' OR situazione='p' OR situazione='r',mediagiornalieracontrattuale,duratalavoro) as 'durataxcalcolo',tipogiorno,user_contratti.mediagiornalieracontrattuale

                    FROM user_rapportidilavoro JOIN user_contratti ON user_rapportidilavoro.recordidcontratti_=user_contratti.recordid_

                    WHERE recordidcontratti_='$recordid_contratto' AND data>='$datainizio_media' AND data<='$datafine_media' and tipogiorno<>'festivoparificato' AND (situazione is null OR (situazione<>'d' AND situazione<>'fine' AND situazione<>'nr'))  
                    ) as tabellaxcalcolo
                    ";
                $ore_results= $this->select($sql);
                $oreeffettive_decimali=$ore_results[0]['ore'];
                $oreeffettive= decimal_to_time($oreeffettive_decimali);

                //totale giorni
                $sql="
                    Select recordidcontratti_,count(data) as 'giorni'
                    From (
                    SELECT recordidcontratti_,data,situazione,tipogiorno

                    FROM user_rapportidilavoro 

                    WHERE recordidcontratti_='$recordid_contratto' AND data>='$datainizio_media' AND data<='$datafine_media' AND tipogiorno<>'festivo' AND tipogiorno<>'festivoparificato' 
                    )
                    as tabellaxcalcolo
                    ";
                $giorni_results= $this->select($sql);

                $giorni=$giorni_results[0]['giorni'];
                echo "<br/>";
                echo "Ore effettive decimali: ".$oreeffettive_decimali.'<br/>';
                echo "Giorni: ".$giorni.'<br/>';
                echo "<br/>";
                if($giorni>0)
                {
                    $mediaeffettiva_decimale=$oreeffettive_decimali/$giorni;
                    echo 'Media effettiva decimale: '.$mediaeffettiva_decimale.'<br/>';
                    $mediaeffettiva=decimal_to_time($mediaeffettiva_decimale);
                    echo 'Media effettiva: '.$mediaeffettiva.'<br/>';

                    $sql="
                        UPDATE user_contratti
                        SET mediatest='$mediaeffettiva'
                        WHERE recordid_='$recordid_contratto'
                        ";
                    $this->execute_query($sql);
                }
                $sql="
                    select *
                    from user_rapportidilavoro
                    where recordidcontratti_='$recordid_contratto'
                    ";
                $results= $this->select($sql);
                $numtimbrature=count($results);
                $sql="
                        UPDATE user_contratti
                        SET numtimbrature='$numtimbrature'
                        WHERE recordid_='$recordid_contratto'
                        ";
                    $this->execute_query($sql);

                 $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale);
                 echo 'Media contrattuale decimale: '.$mediacontrattuale_decimale.'<br/>';
                 echo 'Media contrattuale: '.$mediacontrattuale.'<br/>';
                 $orecontrattuali_decimali=$mediacontrattuale_decimale*$giorni;
                 $orecontrattuali= decimal_to_time($orecontrattuali_decimali);
                 echo "<br/>";
                 echo "Ore effettive decimali: ".$oreeffettive_decimali.'<br/>';
                 echo "Ore effettive: ".$oreeffettive.'<br/>';
                 echo 'Ore contrattuali decimali: '.$orecontrattuali_decimali.'<br/>';
                 echo 'Ore contrattuali: '.$orecontrattuali.'<br/>';
                 $oresupplementari_decimali=   $oreeffettive_decimali - $orecontrattuali_decimali;
                 if($oresupplementari_decimali<0)
                 {
                        $oresupplementari_decimali=$oresupplementari_decimali * -1;
                        $oresupplementari= decimal_to_time($oresupplementari_decimali);
                        $oresupplementari='-'.$oresupplementari;
                 }
                 else
                 {
                        $oresupplementari= decimal_to_time($oresupplementari_decimali);
                 }

                 echo 'Ore supplementari decimali: '.$oresupplementari_decimali.'<br/>';
                 echo 'Ore supplementari: '.$oresupplementari.'<br/>';
                 $sql="
                        UPDATE user_contratti
                        SET oresuptest='$oresupplementari'
                        WHERE recordid_='$recordid_contratto'
                        ";
                $this->execute_query($sql);
            }
        }
        */
        
        function calcola_media_contratti_nuova($recordid_contratto='')
        { 
            $recordid_contratto_condition='';
            if($recordid_contratto!='')
            {
                $recordid_contratto_condition="user_contratti.recordid_='$recordid_contratto' AND ";
            }
          
            $sql="
                        UPDATE user_contratti
                        set media_calcolabile=null,mediagiornalieracontrattuale = null,media_datainizio = null,media_datafine = null,media_totaleore_decimale = null,media_totalegiorni = null,media_nuova_decimale = null,media_nuova = null,media_totore_contra_dec = null, media_oresup = null,media_oresup2 = null
                        WHERE $recordid_contratto_condition TRUE
                        ";
                $this->execute_query($sql);
            
            $contratti= $this->db_get('user_contratti','*',"$recordid_contratto_condition (tipoorario='giorno' OR tipoorario='settimana')",'ORDER BY id DESC');
            foreach ($contratti as $key => $contratto) 
            {
                    $recordid_contratto=$contratto['recordid_'];
                    $contratto= $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");
                    $tipoorario=$contratto['tipoorario'];
                    $orario=$contratto['ore'];
                    $tipocontratto=$contratto['tipocontratto'];
                    $datainizio_contratto=$contratto['datainizio'];
                    $datafine_contratto=$contratto['datafine'];
                    $datadisdetta=$contratto['datadisdetta'];

                    // CALCOLO MEDIA CONTRATTUALE inizio
                    $mediacontrattuale=null;
                    if($tipoorario=='giorno')
                    {
                        $mediacontrattuale= str_replace(".", ":", $orario);
                        if(strpos($mediacontrattuale,':')==FALSE)
                        {
                            $mediacontrattuale=$mediacontrattuale.":00";
                        }
                    }
                    if($tipoorario=='settimana')
                    {
                        $mediacontrattuale= str_replace(".", ":", $orario);
                        if(strpos($mediacontrattuale,':')==FALSE)
                        {
                            $mediacontrattuale=$mediacontrattuale.":00";
                        }
                        $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale) / 5;
                        $mediacontrattuale=decimal_to_time($mediacontrattuale_decimale);
                    }
                    // CALCOLO MEDIA CONTRATTUALE fine


                    // CALCOLO DATA INIZIO E FINE CONTRATTO inizio
                    if(isnotempty($datadisdetta))
                    {
                        $datafine_contratto=$datadisdetta;
                    }
                    
                    // CALCOLO DATA INIZIO E FINE CONTRATTO fine
                    
                    
                    
                        
                    
                    $mese_inizio_contratto=date('Y-m', strtotime($datainizio_contratto));
                    $mese_fine_contratto=date('Y-m',strtotime($datafine_contratto));
                    
                    // in caso sia indeterminato e iniziato nello stesso mese corrente, o sia determinato ma inizia nello stesso mese e finisce in mese diverso non va calcolato fino al 5 del mese successivo
                    $today_month=date('Y-m');
                    $today_month_minus_one=date("Y-m",strtotime("-1 month"));
                    $today_day=date('j');
                    if(($tipocontratto=='indet')||(($tipocontratto=='det')&&($mese_inizio_contratto!=$mese_fine_contratto)))
                    {
                        if(($mese_inizio_contratto==$today_month)||(($mese_inizio_contratto==$today_month_minus_one)&&($today_day<5)))
                        {
                            $sql="
                                UPDATE user_contratti
                                set media_calcolabile='no'
                                WHERE recordid_='$recordid_contratto'
                                ";
                            $this->execute_query($sql);
                        }
                    }
                    
                    
                     // nel caso in cui sia indeterminato o non sia ancora raggiunta la fine del contratto tengo conto della data odierna come fine contratto per i calcoli successivi
                    $today= date('Y-m-d');
                    if(isempty($datafine_contratto)||strtotime($today)<strtotime($datafine_contratto))
                    {
                        $datafine_contratto=$today;
                    }
                    $mese_fine_contratto=date('Y-m',strtotime($datafine_contratto));
                    
                    // CALCOLO DATA FINE MEDIA inizio
                    
                    if($mese_inizio_contratto==$mese_fine_contratto)
                    {
                        $datafine_media=$datafine_contratto;
                        
                    }
                    else
                    {
                            
                            if(strtotime($datafine_contratto)<strtotime($today))
                            {
                                $datafine_media=$datafine_contratto;
                            }
                            else
                            {
                                
                                if($today_day<5)
                                {
                                    $datafine_media=date("Y-m-t",strtotime('-2 month', strtotime(date('Y-m'))));
                                } 
                                else
                                {
                                    $datafine_media=date("Y-m-t",strtotime('-1 month', strtotime(date('Y-m'))));
                                }
                            }
                    }
                    
                    // CALCOLO DATA FINE MEDIA fine
                    
                    
                    // CALCOLO DATA INIZIO MEDIA inizio
                    $date_diff=  date_diff(date_create($datainizio_contratto), date_create($datafine_media));
                    $days=$date_diff->days;
                    if($days<=365)
                    {
                        $datainizio_media=$datainizio_contratto;
                    }
                    else
                    {
                        $datainizio_media=date('Y-m-d', strtotime('-12 month + 1 day', strtotime($datafine_media)));
                    }

                    // CALCOLO DATA INIZIO MEDIA fine

      
                    
                    $sql="
                            UPDATE user_contratti
                            SET mediagiornalieracontrattuale='$mediacontrattuale',mediacontrattuale_decimale=$mediacontrattuale_decimale,media_datainizio='$datainizio_media',media_datafine='$datafine_media'
                            WHERE recordid_='$recordid_contratto'
                            ";
                    $this->execute_query($sql);
            }

                //totale ore
                $sql="
                    UPDATE user_contratti
INNER JOIN (
SELECT recordidcontratti_,ROUND(sum(TIME_TO_SEC(durataxcalcolo)) / 3600,2) as totaleore
                    from(
                    SELECT recordidcontratti_,data,situazione,duratalavoro,duratalavoromodificata,if(tipogiorno='festivo',duratalavoro,if(situazione='a' OR situazione='f' OR situazione='fnp' OR situazione='inf' OR situazione='kr' OR situazione='mal' OR situazione='mat' OR situazione='nd' OR situazione='p' OR situazione='r' OR situazione='lr',mediagiornalieracontrattuale,if(situazione='½ F',SEC_TO_TIME(TIME_TO_SEC(duratalavoro)+(TIME_TO_SEC(mediagiornalieracontrattuale)/2)),if((situazione='1° G' AND duratalavoro<mediagiornalieracontrattuale),mediagiornalieracontrattuale,duratalavoro)))) as 'durataxcalcolo',tipogiorno,user_contratti.mediagiornalieracontrattuale

                    FROM user_rapportidilavoro JOIN user_contratti ON user_rapportidilavoro.recordidcontratti_=user_contratti.recordid_

                    WHERE media_calcolabile is null AND $recordid_contratto_condition  data>=media_datainizio AND data<=media_datafine and tipogiorno<>'festivoparificato' 
                    ) as tabellaxcalcolo
                    group BY recordidcontratti_
    ) AS temp ON user_contratti.recordid_=temp.recordidcontratti_
SET user_contratti.media_totaleore_decimale=temp.totaleore
                    ";
                
                /*
                $contratto= $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");
                $mediagiornalieracontrattuale=$contratto['mediagiornalieracontrattuale'];
                if($recordid_contratto!='')
                {
                    $rapportidilavoro= $this->db_get("user_rapportidilavoro","*","recordidcontratti_='$recordid_contratto' AND data>='$datainizio_media' AND data<='$datafine_media' and tipogiorno<>'festivoparificato'");
                    foreach ($rapportidilavoro as $key => $rapportodilavoro) {
                        $recordid_azienda=$rapportodilavoro['recordidazienda_'];
                        $tipogiorno=$rapportodilavoro['tipogiorno'];
                        $situazione=$rapportodilavoro['situazione'];
                        $rapportodilavoro_recordid=$rapportodilavoro['recordid_'];
                        $rapportodilavoro_data=$rapportodilavoro['data'];
                        $duratalavoro=$rapportodilavoro['duratalavoro'];
                        $mediacontrattuale_calendarioaziendale= $this->db_get_value('user_calendarioaziendale','ore',"recordidazienda_='$recordid_azienda' AND data='$rapportodilavoro_data'");
                        if($mediacontrattuale_calendarioaziendale!=null)
                        {
                           $mediagiornalieracontrattuale= $mediacontrattuale_calendarioaziendale;
                        }
                        
                        $duratapercalcolo=$duratalavoro;
                        if($tipogiorno!='festivo')
                        {
                            if(($situazione=='a')||($situazione=='f')||($situazione=='fnp')||($situazione=='inf')||($situazione=='kr')||($situazione=='mal')||($situazione=='mat')||($situazione=='nd')||($situazione=='p')||($situazione=='r')||($situazione=='lr'))
                            {
                               $duratapercalcolo=$mediagiornalieracontrattuale;
                            }
                            if($situazione=='½ F')
                            {
                               $duratapercalcolo=date('H:i', strtotime($duratalavoro)+strtotime($mediagiornalieracontrattuale)/2);
                            }
                            if(($situazione=='1° G')&&($duratalavoro<$mediagiornalieracontrattuale))
                            {
                                $duratapercalcolo=$mediagiornalieracontrattuale;
                            }
                        }
                        
                        
                        $sql="
                            UPDATE user_rapportidilavoro
                            SET duratapercalcolo='$duratapercalcolo'
                            WHERE recordid_='$rapportodilavoro_recordid'
                            ";
                        $this->execute_query($sql);
                    }
                    $sql="
                    UPDATE user_contratti
                    INNER JOIN (
                    SELECT recordidcontratti_,ROUND(sum(TIME_TO_SEC(durataxcalcolo)) / 3600,2) as totaleore
                                        from(
                                        SELECT recordidcontratti_,data,situazione,duratalavoro,duratalavoromodificata,durataxcalcolo,tipogiorno,user_contratti.mediagiornalieracontrattuale

                                        FROM user_rapportidilavoro JOIN user_contratti ON user_rapportidilavoro.recordidcontratti_=user_contratti.recordid_

                                        WHERE media_calcolabile is null AND $recordid_contratto_condition  data>=media_datainizio AND data<=media_datafine and tipogiorno<>'festivoparificato' 
                                        ) as tabellaxcalcolo
                                        group BY recordidcontratti_
                        ) AS temp ON user_contratti.recordid_=temp.recordidcontratti_
                    SET user_contratti.media_totaleore_decimale=temp.totaleore
                    WHERE recordid_='$recordid_contratto'
                                        ";
                }
                 
                 */
                $this->execute_query($sql);

                //totale giorni
                
                $sql="
                    UPDATE user_contratti
                    INNER JOIN (
                    Select recordidcontratti_,count(data) as 'totalegiorni'
                                        From (
                                        SELECT recordidcontratti_,data,situazione,tipogiorno

                                        FROM user_rapportidilavoro JOIN user_contratti ON user_rapportidilavoro.recordidcontratti_=user_contratti.recordid_ 

                                        WHERE media_calcolabile is null AND $recordid_contratto_condition  data>=media_datainizio AND data<=media_datafine AND giornodellasettimana<>'sabato' AND giornodellasettimana<>'domenica'  AND tipogiorno<>'festivoparificato' 
                                        )
                                        as tabellaxcalcolo
                        group by recordidcontratti_
                        ) AS temp ON user_contratti.recordid_=temp.recordidcontratti_
                    SET user_contratti.media_totalegiorni=temp.totalegiorni
                    ";
                $this->execute_query($sql);



                $sql="
                    UPDATE user_contratti
                    SET media_nuova_decimale=if(user_contratti.media_totalegiorni>0,user_contratti.media_totaleore_decimale / user_contratti.media_totalegiorni,null)
                    WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                    ";
                $this->execute_query($sql);
                
                $sql="
                    UPDATE user_contratti
                    SET media_nuova=SEC_TO_TIME(media_nuova_decimale * 3600)
                    WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                    ";
                $this->execute_query($sql);
                
                

                 
                $sql="
                        UPDATE user_contratti
                        set media_totore_contra_dec = ROUND(TIME_TO_SEC(mediagiornalieracontrattuale) / 3600,2)*media_totalegiorni
                        WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                        ";
                $this->execute_query($sql);
                
                $modificacontrattuale_recordid=$contratto['modificacontrattuale_recordid'];
                $monteore=0;
                if($this->isnotempty($modificacontrattuale_recordid))
                {
                    //$this->calcola_media_contratti_nuova($modificacontrattuale_recordid);
                    //$contratto_precedente=$this->db_get_row('user_contratti','*',"recordid_='$modificacontrattuale_recordid'");
                    //$monteore=$contratto_precedente['media_oresup_dec'];
                }
                    
                /* $sql="
                        UPDATE user_contratti
                        set media_oresup_dec = $monteore + if(media_totaleore_decimale is null,0,media_totaleore_decimale) -  if(media_totore_contra_dec is null,0,media_totore_contra_dec)
                        WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                        ";
                 */
                $recordid_contratto_condition2= str_replace("user_contratti", "c1", $recordid_contratto_condition );
                $sql="
                        UPDATE user_contratti as c1 left join user_contratti as c2 on c1.modificacontrattuale=c2.id
                         set c1.media_oresup_dec=if(c2.media_oresup_dec is null,0,c2.media_oresup_dec) + (if(c1.media_totaleore_decimale is null,0,c1.media_totaleore_decimale) -  if(c1.media_totore_contra_dec is null,0,c1.media_totore_contra_dec))
                        WHERE c1.media_calcolabile is null AND $recordid_contratto_condition2 TRUE
                    ";
                $this->execute_query($sql);
                
                $sql="
                        UPDATE user_contratti
                        set media_oresup = SEC_TO_TIME(media_oresup_dec * 3600)
                        WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                        ";
                $this->execute_query($sql);
                
                 $sql="
                        UPDATE user_contratti
                        set media_oresup2 = SUBSTRING(media_oresup,     1,    CHAR_LENGTH(media_oresup) - 3)
                        WHERE media_calcolabile is null AND $recordid_contratto_condition TRUE
                        ";
                $this->execute_query($sql);
                
                
                
            }
            
            
            
            function aggiorna_mediacontrattuale_contratti($recordid_contratto=null)
            {
                if($recordid_contratto==null)
                {
                   $contratti= $this->db_get('user_contratti'); 
                }
                else
                {
                    $contratti=$this->db_get('user_contratti','*',"recordid_='$recordid_contratto'"); 
                }
                foreach ($contratti as $key => $contratto) {
                    
                            $recordid_contratto=$contratto['recordid_'];
                            $tipoorario=$contratto['tipoorario'];
                            $ore=$contratto['ore'];
                            $ore= str_replace(" ", "", $ore);
                            $mediacontrattuale_decimale=0;
                            if($tipoorario=='giorno')
                            {
                                $mediacontrattuale= str_replace(".", ":", $ore);
                                if(strpos($mediacontrattuale,':')==FALSE)
                                {
                                    $mediacontrattuale=$mediacontrattuale.":00";
                                }
                                $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale);
                            }
                            
                            if($tipoorario=='settimana')
                            {
                                $mediacontrattuale= str_replace(".", ":", $ore);
                                if(strpos($mediacontrattuale,':')==FALSE)
                                {
                                    $mediacontrattuale=$mediacontrattuale.":00";
                                }
                                $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale) / 5;
                            }
                            
                            $mediagiornalieracontrattuale= decimal_to_time($mediacontrattuale_decimale);
                            $sql="
                                UPDATE user_contratti
                                SET mediagiornalieracontrattuale='$mediagiornalieracontrattuale',mediacontrattuale_decimale=$mediacontrattuale_decimale
                                where recordid_='$recordid_contratto'
                                ";
                            $this->execute_query($sql);
                            //echo "Eseguito: $recordid_contratto: $mediacontrattuale_decimale <br/>";
                }
                
            }
            
            function aggiorna_orecontrattuali_rapportidilavoro($recordid_contratto=null)
            {
                
                $condition='true';
                if($recordid_contratto!=null)
                {
                    $condition="rap.recordidcontratti_='$recordid_contratto'";
                }
                $contratto= $this->db_get_row('user_contratti', '*', "recordid_='$recordid_contratto'");
                $tipoorario=$contratto['tipoorario'];
                $recordid_azienda=$contratto['recordidazienda_'];
                $recordid_ccl=$contratto['recordidccl_'];
                
                if($tipoorario=='calendario')
                {
                    $rows=$this->db_get('user_calendarioaziendale', '*', "recordidazienda_='$recordid_azienda' AND recordidccl_='$recordid_ccl'");
                    $calendario_aziendale=array();
                    foreach ($rows as $key => $row) {
                        $calendario_aziendale[$row['data']]['orecontrattuali']=$row['orecontrattuali'];
                    }

                    $sql="
                        SELECT *
                        FROM user_rapportidilavoro
                        WHERE recordidcontratti_='$recordid_contratto' AND tipogiorno<>'festivoparificato'
                        ";
                    $rapportidilavoro= $this->select($sql);
                    foreach ($rapportidilavoro as $key => $rapportodilavoro) {
                        $recordid_rapportodilavoro=$rapportodilavoro['recordid_'];
                        $data=$rapportodilavoro['data'];
                        if(array_key_exists($data, $calendario_aziendale))
                        {
                            $orecontrattuali=$calendario_aziendale[$data]['orecontrattuali'];
                        }
                        else
                        {
                            $orecontrattuali='';
                        }
                        $sql="UPDATE user_rapportidilavoro SET orecontrattuali='$orecontrattuali' WHERE recordid_='$recordid_rapportodilavoro' ";
                        $this->execute_query($sql);
                    }
                }
                else
                {
                    $sql="
                        UPDATE user_rapportidilavoro as rap join user_contratti as con on rap.recordidcontratti_=con.recordid_
                        set rap.orecontrattuali=con.mediacontrattuale_decimale
                        where $condition
                        ";
                    $this->execute_query($sql);
                }
                //echo "Eseguito";
            }
            
            function calcola_media_contratto_v2021($recordid_contratto,$inizio=null,$fine=null)
            {
                    $sql="
                        UPDATE user_contratti
                        SET mediaeffettiva2=null,oresupplementari2=null
                        WHERE recordid_='$recordid_contratto'
                            
                        ";
                    $this->execute_query($sql);
                    $today= date('Y-m-d');
                    $contratto= $this->db_get_row('user_contratti','*',"recordid_='$recordid_contratto'");
                    $recordid_dipendente=$contratto['recordiddipendenti_'];
                    $recordid_azienda=$contratto['recordidazienda_'];
                    $tipocontratto=$contratto['tipocontratto'];
                    $datainizio_contratto=$contratto['datainizio'];
                    $mese_inizio_contratto=date('Y-m', strtotime($datainizio_contratto));
                    $datafine_contratto=$contratto['datafine'];
                    $datafine_contratto_formale=$contratto['datafine'];
                    $mese_fine_contratto_formale=date('Y-m',strtotime($datafine_contratto_formale));
                    $datadisdetta=$contratto['datadisdetta'];
                    if(isnotempty($datadisdetta))
                    {
                        $datafine_contratto=$datadisdetta;
                    }
                    $datafine_media=$datafine_contratto;
                    if(isempty($datafine_contratto)||strtotime($today)<strtotime($datafine_contratto))
                    {
                        $datafine_contratto=$today;
                    }
                    $mese_fine_contratto=date('Y-m',strtotime($datafine_contratto));
                    // in caso sia indeterminato e iniziato nello stesso mese corrente, o sia determinato ma inizia nello stesso mese e finisce in mese diverso non va calcolato fino al 5 del mese successivo
                    $calcolare=true;
                    $today_month=date('Y-m');
                    $today_month_minus_one=date("Y-m",strtotime("-1 month"));
                    $today_day=date('j');
                    if(($tipocontratto=='indet')||(($tipocontratto=='det')&&($mese_inizio_contratto!=$mese_fine_contratto_formale)))
                    {
                        if(($mese_inizio_contratto==$today_month)||(($mese_inizio_contratto==$today_month_minus_one)&&($today_day<5)))
                        {
                            $calcolare=false;
                        }
                    }
                    
                    if($calcolare)
                    {
                        
                        
                        $contratti[]=$contratto;
                        $modificacontrattuale=$contratto['modificacontrattuale'];
                        
                        //verifico se ci sono modifiche contrattuali - inizio
                        while($this->isnotempty($modificacontrattuale))
                        {
                            $contratto= $this->db_get_row('user_contratti','*',"id='$modificacontrattuale'");
                            $datainizio_contratto=$contratto['datainizio'];
                            $datainizio_media=$datainizio_contratto;
                            $contratti[]=$contratto;
                            $modificacontrattuale=$contratto['modificacontrattuale'];
                        }
                        //verifico se ci sono modifiche contrattuali - fine
                        
                        $datainizio_media=$datainizio_contratto;
                        $mese_inizio_contratto=date('Y-m', strtotime($datainizio_contratto));
                        
                        
                        
                        
                        
                        if($mese_inizio_contratto==$mese_fine_contratto)
                        {
                            $datafine_media=$datafine_contratto;

                        }
                        else
                        {

                                if(strtotime($datafine_contratto)<strtotime($today))
                                {
                                    $datafine_media=$datafine_contratto;
                                }
                                else
                                {

                                    if($today_day<5)
                                    {
                                        $datafine_media=date("Y-m-t",strtotime('-2 month', strtotime(date('Y-m'))));
                                    } 
                                    else
                                    {
                                        $datafine_media=date("Y-m-t",strtotime('-1 month', strtotime(date('Y-m'))));
                                    }
                                }
                        }
                        
                        
                    $date_diff=  date_diff(date_create($datainizio_media), date_create($datafine_media));
                    $days=$date_diff->days;
                    if($days<=365)
                    {
                        $datainizio_media=$datainizio_media;
                    }
                    else
                    {
                        $datainizio_media=date('Y-m-d', strtotime('-12 month + 1 day', strtotime($datafine_media)));
                    }    
                        
                        
                    
                    
                    
                    
                    if($this->isnotempty($inizio))
                    {
                        $datainizio_media=$inizio;
                        $datafine_media=$fine;
                    }
                        
                    
                    
                    /*
                        foreach ($contratti as $key => $contratto) {
                            $recordid_contratto=$contratto['recordid_'];
                            $tipoorario=$contratto['tipoorario'];
                            $ore=$contratto['ore'];
                            if($tipoorario=='giorno')
                            {
                                $mediacontrattuale= str_replace(".", ":", $ore);
                                if(strpos($mediacontrattuale,':')==FALSE)
                                {
                                    $mediacontrattuale=$mediacontrattuale.":00";
                                }
                                $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale);
                            }
                            if($tipoorario=='settimana')
                            {
                                $mediacontrattuale= str_replace(".", ":", $ore);
                                if(strpos($mediacontrattuale,':')==FALSE)
                                {
                                    $mediacontrattuale=$mediacontrattuale.":00";
                                }
                                $mediacontrattuale_decimale=time_to_decimal($mediacontrattuale) / 5;
                            }
                            
                            $sql="
                                UPDATE user_rapportidilavoro
                                SET orecontrattuali=$mediacontrattuale_decimale
                                where recordidcontratti_='$recordid_contratto'
                                ";
                            $this->execute_query($sql);
                        }
                    
                        */
                    
                    // CALCOLO ORE GIORNALIERE
                    
                    $sql="
                        SELECT *
                        FROM user_rapportidilavoro
                        WHERE recordiddipendenti_='$recordid_dipendente' AND recordidazienda_='$recordid_azienda'  AND data>='$datainizio_media' AND data<='$datafine_media'  AND tipogiorno<>'festivoparificato'
                        ";
                    $rapportidilavoro= $this->select($sql);
                    $totale_orecontrattuali=0;
                    $totale_oreeffettive_decimale=0;
                    $totale_giorni=0;
                    foreach ($rapportidilavoro as $key => $rapportodilavoro) {
                        $duratalavoro=$rapportodilavoro['duratalavoro'];
                        $duratalavoro_decimale=time_to_decimal($duratalavoro);
                        $mediacontrattuale_decimale=$rapportodilavoro['orecontrattuali'];
                        $tipogiorno=$rapportodilavoro['tipogiorno'];
                        $situazione=$rapportodilavoro['situazione'];
                        if($tipogiorno=='festivo')
                        {
                            $duratalavoro_decimale=$duratalavoro_decimale;
                        }
                        else
                        {
                            if($situazione=='a'|| $situazione=='f' || $situazione=='fnp' || $situazione=='inf' || $situazione=='kr' || $situazione=='mal' || $situazione=='mat' || $situazione=='nd' || $situazione=='p' || $situazione=='r' || $situazione=='lr')
                            {
                              $duratalavoro_decimale=  $mediacontrattuale_decimale;
                            }
                            else
                            {
                                if($situazione=='½ F')
                                {
                                    $duratalavoro_decimale=$duratalavoro_decimale+$mediacontrattuale_decimale/2;
                                }
                                else
                                {
                                    if(($situazione=='1° G')&&($duratalavoro_decimale<$mediacontrattuale_decimale))
                                    {
                                        $duratalavoro_decimale=$mediacontrattuale_decimale;
                                    }
                                    else
                                    {
                                        $duratalavoro_decimale=$duratalavoro_decimale; 
                                    }
                                }
                            }
                            
                        }    
                                
                        
                        
                        
                        $totale_oreeffettive_decimale=$totale_oreeffettive_decimale+$duratalavoro_decimale;
                        if(($rapportodilavoro['giornodellasettimana']<>'sabato')&&($rapportodilavoro['giornodellasettimana']<>'domenica'))
                        {
                            $totale_giorni=$totale_giorni+1;
                            $totale_orecontrattuali=$totale_orecontrattuali+$mediacontrattuale_decimale;
                        }
                    }
                    
                    if($totale_giorni==0)
                    {
                        $media_decimale=0;
                        $media='';
                    }
                    else
                    {
                        $media_decimale=$totale_oreeffettive_decimale/$totale_giorni;
                        $media= decimal_to_time($media_decimale);
                    }
                    
                    $totale_oresupplementari_decimale=$totale_oreeffettive_decimale-$totale_orecontrattuali;
                    
                    if($totale_oresupplementari_decimale>=0)
                    {
                        $totale_oresupplementari=decimal_to_time($totale_oresupplementari_decimale);
                    }
                    else
                    {
                        $totale_oresupplementari=decimal_to_time($totale_oresupplementari_decimale*-1);
                        $totale_oresupplementari="-".$totale_oresupplementari;
                    }
                    
                   /* echo "
                        <b>Data inizio: </b>$datainizio_media <br/>
                        <b>Data fine: </b>$datafine_media <br/>
                        <b>Totale giorni: </b>$totale_giorni <br/>
                        <b>Totale ore contrattuali: </b>$totale_orecontrattuali <br/>
                        <b>Totale ore effettive: </b> $totale_oreeffettive_decimale<br/>
                        <b>Media effettiva decimale: </b>$media_decimale <br/>
                        <b>Media effettiva: </b>$media <br/>
                        <b>Ore supplementari decimale: </b>$totale_oresupplementari_decimale <br/>
                        <b>Ore supplementari : </b>$totale_oresupplementari <br/>
                        ";*/
                    $sql="
                        UPDATE user_contratti
                        SET mediaeffettiva2='$media',oresupplementari2='$totale_oresupplementari'
                        WHERE recordid_='$recordid_contratto'
                            
                        ";
                    $this->execute_query($sql);
                    
                    return true;
                    }
                    
                
            }
            
            
            function calcola_carenze_dipendenti($anno,$mese,$id_dipendente='')
            {
                $return=array();
                $sql="DELETE FROM user_carenze";
                $this->execute_query($sql);
                
                $datainizio_mese="$anno-$mese-01";
                $datafine_mese=date("Y-m-t",strtotime("$anno-$mese"));
                
               // $totale_carenza_clc=0;
               // $totale_carenza_industria=0;
               // $totale_totore=0;
               // $carenze_dipendenti=array();
               // $id_dipendente_condition='';
               // if($id_dipendente!='')
               // {
                //    $id_dipendente_condition=" AND totore.id='$id_dipendente'";
               // }
                

                
                //calcolo giorni lavorativi inizio        
                $sql="
                    SELECT count(giorno) as giornilavorativi
                    FROM user_calendario
                    WHERE giorno>='$datainizio_mese' AND giorno<='$datafine_mese' AND (giornosettimana='lunedi' OR giornosettimana='martedi' OR giornosettimana='mercoledi' OR giornosettimana='giovedi' OR giornosettimana='venerdi') AND tipogiorno<>'festivoparificato'
                    ";
                $result= $this->select($sql);
                $carenza_3p_giornilavorativi=$result[0]['giornilavorativi'];
                echo "Giorni lavorativi: ".$carenza_3p_giornilavorativi."<br/><br/>";
                $carenza_3p_oreteoriche=$carenza_3p_giornilavorativi*7.5;
                echo "Ore teoriche: $carenza_3p_oreteoriche <br/><br/>";
                
                //calcolo giorni lavorativi fine
                /*
                //CALCOLO totgiorni_laboratori inizio
                $sql="
                    SELECT presenze_filtrate.recordiddipendenti_,nome,cognome,count(presenze_filtrate.recordiddipendenti_) as totgiorni_laboratori
                    FROM
                            (
                    select recordiddipendenti_,nome,cognome
                            from user_presenzemensili
                            where user_presenzemensili.mese='$mese' AND user_presenzemensili.anno='$anno'
                            ) as presenze_filtrate
                     JOIN
                            (
                            select recordiddipendenti_,recordidazienda_,duratalavoro
                            from user_rapportidilavoro
                            WHERE user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese' AND user_rapportidilavoro.giornodellasettimana<>'Domenica' AND user_rapportidilavoro.giornodellasettimana<>'Sabato' AND  user_rapportidilavoro.tipogiorno<>'festivoparificato' AND (user_rapportidilavoro.laboratorio='b' OR user_rapportidilavoro.laboratorio='m' OR user_rapportidilavoro.laboratorio='q' )
                            ) as rapporti_filtrati
                    ON presenze_filtrate.recordiddipendenti_=rapporti_filtrati.recordiddipendenti_
                    GROUP BY recordiddipendenti_,nome,cognome
                    ";
                $results= $this->select($sql);
                $totgiorni_laboratori_table=array();
                foreach ($results as $key => $result) {
                    $totgiorni_laboratori_table[$result['recordiddipendenti_']]=$result['totgiorni_laboratori'];
                }
                
                
                
                
                //CALCOLO totgiorni_laboratori fine
                
                //calcolo carenze  inizio
                $sql="
                    SELECT totore.no,totore.id,totore.nome,totore.cognome,totore,totore.recordiddipendenti_,totore.recordidazienda_,totgiorni_sigle
FROM
(
	SELECT presenze_filtrate.no,presenze_filtrate.id,presenze_filtrate.recordiddipendenti_,nome,cognome,ROUND(sum(TIME_TO_SEC(rapporti_filtrati.duratalavoro)) / 3600,2) as totore,max(recordidazienda_) as recordidazienda_
	FROM
	(select no,id,recordiddipendenti_,nome,cognome
	from user_presenzemensili
	where user_presenzemensili.mese='$mese' AND user_presenzemensili.anno='$anno'
	) as presenze_filtrate
	LEFT JOIN
	(
	select recordiddipendenti_,recordidazienda_,duratalavoro
	from user_rapportidilavoro
	WHERE user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese'
	) as rapporti_filtrati
	ON presenze_filtrate.recordiddipendenti_=rapporti_filtrati.recordiddipendenti_
	GROUP BY recordiddipendenti_,nome,cognome
	) AS totore
LEFT JOIN
(
	SELECT presenze_filtrate.recordiddipendenti_,nome,cognome,count(presenze_filtrate.recordiddipendenti_) as totgiorni_sigle
	FROM
	(select recordiddipendenti_,nome,cognome
	from user_presenzemensili
	where user_presenzemensili.mese='$mese' AND user_presenzemensili.anno='$anno'
	) as presenze_filtrate
	JOIN
	(
	select recordiddipendenti_,recordidazienda_,duratalavoro
	from user_rapportidilavoro
	WHERE user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese' AND user_rapportidilavoro.giornodellasettimana<>'Domenica' AND user_rapportidilavoro.giornodellasettimana<>'Sabato' AND  user_rapportidilavoro.tipogiorno<>'festivoparificato' AND (user_rapportidilavoro.situazione='mal' OR user_rapportidilavoro.situazione='kr' OR user_rapportidilavoro.situazione='inf' OR user_rapportidilavoro.situazione='f' OR user_rapportidilavoro.situazione='mat')
	) as rapporti_filtrati
	ON presenze_filtrate.recordiddipendenti_=rapporti_filtrati.recordiddipendenti_
	GROUP BY recordiddipendenti_,nome,cognome
) as totgiorni_sigle
ON totore.recordiddipendenti_=totgiorni_sigle.recordiddipendenti_
WHERE TRUE $id_dipendente_condition

ORDER BY no ASC

                    ";
                
                $results= $this->select($sql); 
                $carenze_dipendenti=array();
                foreach ($results as $key => $result) {
                    $recordid_dipendente=$result['recordiddipendenti_'];
                    $carenze_dipendenti[$recordid_dipendente]=$result;
                }
                foreach ($carenze_dipendenti as $key => $carenze_dipendente) {
                    $totore=$carenze_dipendente['totore'];
                    $totale_totore=$totale_totore+$totore;
                    $totgiorni_sigle=$carenze_dipendente['totgiorni_sigle'];
                    $recordid_dipendente=$carenze_dipendente['recordiddipendenti_'];
                    $dipendente= $this->db_get_row('user_dipendenti', '*', "recordid_='$recordid_dipendente'");
                    
                    //calcolo giorni fuori collaborazione
                    $datainiziocollaborazione=$dipendente['datainiziocollaborazione'];
                    $datainizio=$datainiziocollaborazione;
                    $datachiusura=$dipendente['datachiusura'];
                    $datafine=$datachiusura;
                    if(isempty($datafine))
                    {
                        $datafine='2100-01-01';
                    }
                    $datariapertura=$dipendente['datariapertura'];
                    if(isnotempty($datariapertura))
                    {
                        $datainizio=$datariapertura;
                        $datafine='2100-01-01';
                    }
                    
                    $sql="
                        select count(user_rapportidilavoro.recordiddipendenti_) as totgiorni_fuoricollaborazione
                        FROM user_rapportidilavoro
                        WHERE user_rapportidilavoro.recordiddipendenti_='$recordid_dipendente' 
                        AND user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese' 
                        AND (user_rapportidilavoro.data< '$datainizio' OR user_rapportidilavoro.data>'$datafine')
                        ";
                    $result= $this->select($sql);
                    $totgiorni_fuoricollaborazione=0;
                    if(count($result)!=0)
                    {
                        $totgiorni_fuoricollaborazione=$result[0]['totgiorni_fuoricollaborazione'];
                    }
                    
                    $carenza_clc='';
                    if(isempty($carenze_dipendente['recordidazienda_']))
                    {
                       $carenza_clc='-';
                    }
                    else
                    {
                        $carenza_clc=FLOOR(30 - ((30/$carenza_3p_oreteoriche)*$totore) - $totgiorni_sigle - $totgiorni_fuoricollaborazione);
                        if($carenza_clc<=0)
                        {
                            $carenza_clc=0;
                        }
                    }
                    $carenze_dipendenti[$key]['carenza_clc']=$carenza_clc;
                    $totale_carenza_clc=$totale_carenza_clc+$carenza_clc;
                    
                    
                    
                    //calcolo carenza industria inizio
                    $carenza_industria='';
                    $totgiorni_laboratori=0;
                    if(array_key_exists($recordid_dipendente, $totgiorni_laboratori_table))
                    {
                        $totgiorni_laboratori=$totgiorni_laboratori_table[$recordid_dipendente];
                    }
                    if($totgiorni_laboratori==0)
                    {
                        $carenza_industria='-';
                    }
                    else
                    {
                        $carenza_industria=FLOOR(30 - ((30/$carenza_3p_giornilavorativi)*$totgiorni_laboratori) - $totgiorni_sigle - $totgiorni_fuoricollaborazione);
                        if($carenza_industria<0)
                        {
                            $carenza_industria=0;
                        }
                        
                    }
                    $carenze_dipendenti[$key]['carenza_industria']=$carenza_industria;
                    $totale_carenza_industria=$totale_carenza_industria+$carenza_industria;
                    
                    //calcolo carenza industria fine
                    
                    
                    //inserimento nella tabella
                    $fields['no']=$carenze_dipendenti[$key]['no'];
                    $fields['id']=$carenze_dipendenti[$key]['id'];
                    $fields['nome']=$carenze_dipendenti[$key]['nome'];
                    $fields['cognome']=$carenze_dipendenti[$key]['cognome'];
                    $fields['carenzaclc']=$carenze_dipendenti[$key]['carenza_clc'];
                    $fields['carenzaindustria']=$carenze_dipendenti[$key]['carenza_industria'];
                    $fields['totore']=$carenze_dipendenti[$key]['totore'];

                    $this->insert_record('carenze',1,$fields);
                }
                
                //calcolo carenze  FINE
                
                
                
                $return['carenze_dipendenti']=$carenze_dipendenti;
                $return['totale_totore']=$totale_totore;
                $return['totale_carenza_clc']=$totale_carenza_clc;
                $return['totale_carenza_industria']=$totale_carenza_industria;
                */
                $sql="
                    update user_rapportidilavoro
                    set orestimate=null
                    where data>='$datainizio_mese' AND data<='$datafine_mese'
                    
                    
                    ";
                
                $this->execute_query($sql);
                
                $sql="
                    update user_rapportidilavoro
                    set orestimate=orecontrattuali
                    where data>='$datainizio_mese' AND data<='$datafine_mese'
                    and tipogiorno='feriale' 
                    and (situazione is null or situazione='')
                    and orecontrattuali is not null 
                    and orecontrattuali!='' 
                    and orecontrattuali!=0 
                    and recordidazienda_!='00000000000000000000000000010442'
                    and (laboratorio=''  or laboratorio is null)
                    
                    ";
                
                $this->execute_query($sql);
                
                $sql="
                    update user_rapportidilavoro
                    set orestimate=7
                    where data>='$datainizio_mese' AND data<='$datafine_mese'
                    and tipogiorno='feriale'
                    and (situazione is null or situazione='')
                    and (orecontrattuali is null or orecontrattuali='' or orecontrattuali=0 ) 
                    and recordidazienda_ is not null  
                    and recordidazienda_!=''
                    and recordidazienda_!='00000000000000000000000000010442'
                    and (laboratorio=''  or laboratorio is null)
                    ";
                
                $this->execute_query($sql);
                
                $sql="
                    SELECT presenze_filtrate.recordid_,presenze_filtrate.no,presenze_filtrate.id,presenze_filtrate.recordiddipendenti_,nome,cognome,ROUND(sum(TIME_TO_SEC(rapporti_filtrati.duratalavoro)) / 3600,2) as totore,ROUND(sum(orestimate)) as totorestimate
                   FROM
                   (select recordid_,no,id,recordiddipendenti_,nome,cognome
                   from user_presenzemensili
                   where user_presenzemensili.mese='$mese' AND user_presenzemensili.anno='$anno'
                   ) as presenze_filtrate
                   LEFT JOIN
                   (
                   select recordiddipendenti_,recordidazienda_,duratalavoro,orestimate,recordidpresenzemensili_
                   from user_rapportidilavoro
                   WHERE user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese'
                   ) as rapporti_filtrati
                   ON presenze_filtrate.recordid_=rapporti_filtrati.recordidpresenzemensili_
                   GROUP BY recordiddipendenti_,nome,cognome	
                    ";
                $results_totore=$this->select($sql); 
                
                
                $sql="
                    
                SELECT presenze_filtrate.recordid_,presenze_filtrate.no,presenze_filtrate.id,presenze_filtrate.recordiddipendenti_,nome,cognome,ROUND(sum(TIME_TO_SEC(rapporti_filtrati.duratalavoro)) / 3600,2) as totore
	FROM
	(select recordid_,no,id,recordiddipendenti_,nome,cognome
	from user_presenzemensili
	where user_presenzemensili.mese='$mese' AND user_presenzemensili.anno='$anno'
	) as presenze_filtrate
	LEFT JOIN
	(
	select recordiddipendenti_,recordidazienda_,duratalavoro,recordidpresenzemensili_
	from user_rapportidilavoro
	WHERE user_rapportidilavoro.data>='$datainizio_mese' AND user_rapportidilavoro.data<='$datafine_mese' AND user_rapportidilavoro.recordidazienda_='00000000000000000000000000010442'
	) as rapporti_filtrati
	ON presenze_filtrate.recordid_=rapporti_filtrati.recordidpresenzemensili_
	GROUP BY recordiddipendenti_,nome,cognome
       
                    ";
                
                $results_totoreindustria=$this->select($sql); 
                $totore_industria=array();
                foreach ($results_totoreindustria as $key => $result_totoreindustria) {
                    $totore_industria[$result_totoreindustria['recordiddipendenti_']]=$result_totoreindustria['totore'];
                }
                
                
                
                foreach ($results_totore as $key => $result_totore) {
                    $fields=array();
                    $fields['no']=$result_totore['no'];
                    $fields['id']=$result_totore['id'];
                    $fields['nome']=$result_totore['nome'];
                    $fields['cognome']=$result_totore['cognome'];
                    $fields['totore']=$result_totore['totore'];
                    $fields['totorestimate']=$result_totore['totorestimate'];
                    if(array_key_exists($result_totore['recordiddipendenti_'], $totore_industria))
                    {
                        $fields['totoreindustria']=$totore_industria[$result_totore['recordiddipendenti_']];
                        
                    }  
                    else
                    {
                      $fields['totoreindustria']=0;  
                    }  
                    $fields['totoreclc']=$fields['totore']-$fields['totoreindustria'];
                    $this->insert_record('carenze',1,$fields);
                }
                
                
                
                
                return $return;
                
                
                
                
                
            }
            
            
            
            function genera_presenze_dipendente($recordid_dipendente)
            {
                //$this->syslog($recordid_dipendente,"genera_presenze_dipendente start");
                $dipendente= $this->get_record('dipendenti', $recordid_dipendente);
                
                
                $datainizio=$dipendente['datainiziocollaborazione'];
                $dataultimachiusura=$dipendente['ultimadatachiusura'];
                if(isnotempty($datainizio))
                {
                    $datafine=$dipendente['datachiusura'];
                    if(isempty($datafine)&&(isempty($dataultimachiusura)||($datainizio>$dataultimachiusura)))
                    {
                        $datafine=date('Y-m-d');
                    }
                    $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine);
                }
                
                $datainizio=$dipendente['datariapertura'];
                if(isnotempty($datainizio))
                {
                    $datafine=$dipendente['datachiusura2'];
                    if(isempty($datafine)&&(isempty($dataultimachiusura)||($datainizio>$dataultimachiusura)))
                    {
                        $datafine=date('Y-m-d');
                    }
                    $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine);
                }
                
                
                $datainizio=$dipendente['dataapertura3'];
                if(isnotempty($datainizio))
                {
                    $datafine=$dipendente['datachiusura3'];
                    if(isempty($datafine)&&(isempty($dataultimachiusura)||($datainizio>$dataultimachiusura)))
                    {
                        $datafine=date('Y-m-d');
                    }
                    $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine);
                }
                
                
                
                $datainizio=$dipendente['dataapertura4'];
                if(isnotempty($datainizio))
                {
                    $datafine=$dipendente['datachiusura4'];
                    if(isempty($datafine)&&(isempty($dataultimachiusura)||($datainizio>$dataultimachiusura)))
                    {
                        $datafine=date('Y-m-d');
                    }
                    $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine);
                }
                
                $aperture= $this->db_get("user_aperture","*","recordiddipendenti_='$recordid_dipendente'");
                foreach ($aperture as $key => $apertura) {
                    $datainizio=$apertura['dataapertura'];
                    if(isnotempty($datainizio))
                    {
                        $datafine=$apertura['datachiusura'];
                        if(isempty($datafine))
                        {
                            $datafine=date('Y-m-d');
                        }
                        $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine);
                    }
                }
                
                //$this->syslog('',"genera_presenze_dipendente alias start"); 
                $contratti_alias=$this->db_get("user_contratti","*","recordiddipendenti_='$recordid_dipendente' AND alias is not null AND alias<>''");
                foreach ($contratti_alias as $key => $contratto) {
                    $alias=$contratto['alias'];
                    $datainizio=$contratto['datainizio'];
                    $datafine=$contratto['datafine'];
                    $datadisdetta=$contratto['datadisdetta'];
                    if(isnotempty($datadisdetta))
                    {
                      $datafine=$datadisdetta;  
                    }
                    if(isempty($datafine))
                    {
                        $datafine=date('Y-m-d');
                    }
                    $this->genera_presenze_dipendente_iniziofine($recordid_dipendente,$datainizio,$datafine,$alias);
                }
                //$this->syslog('',"genera_presenze_dipendente alias end"); 
                
                //$this->syslog('',"genera_presenze_dipendente end"); 
            }
            
            
            function genera_presenze_dipendente_iniziofine($recordid_dipendente,$data_inizio,$data_fine,$alias='')
            {
                $today=date('Y-m-d');
                if(isnotempty($alias))
                {
                    $alias_condition="AND alias='$alias'";
                }
                else
                {
                    $alias_condition="AND (alias='' OR alias is null)";
                }
                    
                

                    $anno_inizio=date('Y',strtotime($data_inizio));
                    if($anno_inizio<2018)
                    {
                        $anno_inizio=2018;
                    }
                    $mese_inizio=date('n',strtotime($data_inizio));

                    $anno_fine=date('Y',strtotime($data_fine));
                    $mese_fine=date('n',strtotime($data_fine));
                    if($today==$data_fine)
                    {
                        $mese_fine=$mese_fine+1;
                        if($mese_fine==13)
                        {
                            $mese_fine=1;
                            $anno_fine=$anno_fine+1;
                        }
                    }



                    for($a=$anno_inizio;$a<=$anno_fine;$a++)
                    {
                        $m_inizio=1;
                        $m_fine=12;
                        if($a==$anno_inizio)
                        {
                            $m_inizio=$mese_inizio;
                        }
                        if($a==$anno_fine)
                        {

                            $m_fine=$mese_fine;
                        }

                        for($m=$m_inizio;$m<=$m_fine;$m++)
                        {
                            if(($a>=2018 && $m>= 1)||($a>=2020))
                            {
                                $sql="select recordid_ from user_presenzemensili where anno='$a' AND mese='$m' AND recordiddipendenti_='$recordid_dipendente' $alias_condition ";
                                $result= $this->select($sql);
                                if(count($result)==0)
                                {
                                    echo "Aggiunta record: $a-$m <br/>";
                                    $row= $this->db_get_row('user_presenzemensili','recordid_',"anno=$a AND mese='$m' AND recordiddipendenti_='$recordid_dipendente' $alias_condition");
                                    if(isempty($row))
                                    {
                                        $fields['anno']=$a;
                                        $fields['mese']=$m;
                                        $fields['recordiddipendenti_']=$recordid_dipendente;
                                        $fields['alias']=$alias;
                                        $recordid_presenzemensili=$this->insert_record('presenzemensili', 1, $fields);
                                        //$this->aggiorna_presenze($recordid_presenzemensili);
                                        //$this->calcola_3mesi_dipendente($recordid_dipendente);

                                    }


                                }
                            }
                        }
                    }
                
            }
            
            
            function aggiorna_presenze($presenze_recordid)
            {
               
                $presenze_record=$this->get_record('presenzemensili', $presenze_recordid);
                $presenze_record_anno=$presenze_record['anno'];
                $presenze_record_mese=$presenze_record['mese'];
                $dipendente_recordid=$presenze_record['recordiddipendenti_'];
                $dipendente= $this->get_record('dipendenti', $dipendente_recordid);
                $id_dipendente=$dipendente['id'];
                $nome_dipendente=$dipendente['nome'];
                $cognome_dipendente=$dipendente['cognome'];
                $alias=$presenze_record['alias'];
                if(isempty($alias))
                {
                    $alias_condition="(alias is null OR alias='')";
                }
                else
                {
                    $alias_condition="alias='$alias'";
                }
                
                $this->syslog("$presenze_record_anno-$presenze_record_mese | $presenze_recordid","aggiorna_presenze start");
                
                $this->syslog("Reset record presenze ");
                $this->reset_record('presenzemensili',$presenze_recordid);
                
                $this->syslog("Genera rapporti di lavoro");
                $this->genera_rapportidilavoro_presenzemensili($presenze_recordid);
                
                $this->syslog("Link presenze mensili-timbrature");
                $this->link_presenzemensili_timbrature($presenze_recordid);
                
                /*$this->syslog("Aggiorna festività");
                $sql="SELECT * FROM user_giornifestivi where giorno>='$presenze_record_anno-$presenze_record_mese-01'";
                $festivi= $this->Sys_model->select($sql);
                foreach ($festivi as $key => $festivo) {

                    $giorno=$festivo['giorno'];
                    $tipogiorno=$festivo['tipogiorno'];
                       echo "Aggiornamento $giorno: $tipogiorno <br/>"; 
                    $sql="
                        UPDATE user_rapportidilavoro
                        SET tipogiorno='$tipogiorno'
                        WHERE data='$giorno' and recordidpresenzemensili_='$presenze_recordid'
                        ";
                    $this->Sys_model->execute_query($sql);
                }*/
                
                
                $this->syslog("Aggiornamento presenze");

                $nome_dipendente=str_replace("'", "''", $nome_dipendente);
                $cognome_dipendente=str_replace("'", "''", $cognome_dipendente);
                if(isnotempty($alias))
                {
                    $cognome_dipendente=$cognome_dipendente."(".strtoupper($alias).")"; 
                }
                $sql="
                        UPDATE user_presenzemensili
                        SET id=$id_dipendente,nome='$nome_dipendente',cognome='$cognome_dipendente'
                        WHERE recordid_='$presenze_recordid'
                        ";
                    $this->execute_query($sql);
                /*$sql="
                    select *
                    from user_rapportidilavoro
                    where recordidpresenzemensili_='$presenze_recordid'
                    ";
                $timbrature= $this->select($sql);*/
                $set='';
                $termine_rapporto=false;
                
                $sql="
                    select *
                    from user_rapportidilavoro
                    where recordiddipendenti_='$dipendente_recordid' AND $alias_condition AND data>='$presenze_record_anno-$presenze_record_mese-01' AND data<='$presenze_record_anno-$presenze_record_mese-31'
                    ";
                $timbrature= $this->select($sql);
                if(count($timbrature)==0)
                {
                    $this->genera_rapportidilavoro($dipendente_recordid);
                }
                foreach ($timbrature as $key => $timbratura) 
                {
                    $azienda_sigla='';
                    $data=$timbratura['data'];
                    $timbratura_situazione=$timbratura['situazione'];
                    $timbratura_situazione2=$timbratura['situazione2'];
                    if($timbratura_situazione=='fine')
                    {
                        $termine_rapporto=true;
                    }
                    $timbratura_duratalavoro=$timbratura['duratalavoro'];
                    $timbratura_tipogiorno=$timbratura['tipogiorno'];
                    $timbratura_giornodelmese=date('d', strtotime($data));
                    $timbratura_giornodellasettimana=$timbratura['giornodellasettimana'];
                    $timbratura_laboratorio=$timbratura['laboratorio'];
                    $timbratura_osservazioni=$timbratura['osservazioni'];
                    $timbratura_viola=$timbratura['viola'];
                    $timbratura_festivopagato=$timbratura['festivopagato'];
                    $timbratura_prova=$timbratura['prova'];
                    $timbratura_notifica=$timbratura['notifica'];
                    $timbratura_primogiorno=$timbratura['primogiorno'];
                    $timbratura_dipendenteinterno=$timbratura['dipendenteinterno'];
                    $timbratura_festivitarossa=$timbratura['festivitarossa'];
                    
                    $timbratura_azienda_recordid=$timbratura['recordidazienda_'];
                    if($timbratura_azienda_recordid!=null)
                    {
                        $timbratura_azienda=$this->get_record('azienda', $timbratura_azienda_recordid);
                        $timbratura_azienda_sigla=$timbratura_azienda['sigla'];
                        $azienda_sigla=$timbratura_azienda_sigla;
                    }
                    
                    
                    $contratto_visto='';
                    $contratto_recordid=$timbratura['recordidcontratti_'];
                    
                    if($contratto_recordid!=null)
                    {
                        $contratto=$this->get_record('contratti', $contratto_recordid);
                        $contratto_visto=$contratto['visto'];
                        $contratto_datainizio=$contratto['datainizio'];
                        $contratto_datafine=$contratto['datafine'];
                        if(isnotempty($contratto['datadisdetta']))
                        {
                            $contratto_datafine=$contratto['datadisdetta'];
                        }
                        $contratto_azienda_recordid=$contratto['recordidazienda_'];
                        if($contratto_azienda_recordid<>null)
                        {
                            $contratto_azienda=$this->get_record('azienda', $contratto_azienda_recordid);
                            $contratto_azienda_sigla=$contratto_azienda['sigla'];
                            $azienda_sigla=$contratto_azienda_sigla;
                        }
                    }
                    
                    
                    
                    
                    
                    $valore_giorno='';
                    $testo='';
                    $colore_testo='Nero';
                    $colore_sfondo='Trasparente';
                    $memo='';
                    $bordo='';
                    
                    //testo

                    if($timbratura_situazione2!='')
                    {
                        $testo= strtoupper($timbratura_situazione2);
                        if($testo=='USCITO')
                        {
                            $testo='EXT';
                        }
                    }
                    
                    if($timbratura_duratalavoro!='00:00:00')
                    {
                        $timbratura_duratalavoro=substr($timbratura_duratalavoro, 0, -3);
                        $testo= $timbratura_duratalavoro;
                    }
                    
                    

                    if(isnotempty($timbratura_azienda_recordid))
                    {
                        if($azienda_sigla=='')
                        {
                            $azienda_sigla='???';
                        }
                        if(isempty($contratto_recordid))
                        {
                            $testo=$azienda_sigla;
                        }
                        else
                        {
                            if(($timbratura_giornodellasettimana=='lunedi')||($timbratura_giornodelmese=='01')||($timbratura_primogiorno=='si'))
                            {
                                $testo=$azienda_sigla;
                                if($contratto_datainizio!=$contratto_datafine)
                                {
                                    $testo=$testo."->";
                                }
                            }
                        }


                    }
                    
                    if($timbratura_festivopagato=='si')
                    {
                        $testo=$timbratura_duratalavoro;
                    }
                    
                    
                    if($timbratura_situazione!='')
                    {
                        $testo= strtoupper($timbratura_situazione);
                    }
                    if($timbratura_laboratorio!='')
                    {
                        $testo= strtoupper($timbratura_laboratorio);
                    }
                    if(($termine_rapporto)&&($timbratura_situazione!='fine'))
                    {
                        $testo='-----';
                    }

                    if($timbratura_festivitarossa=='si')
                    {
                        $testo='X';
                    }


                    //colore testo
                    if((isnotempty($timbratura_azienda_recordid))&&(isempty($contratto_recordid))&&(($timbratura_duratalavoro=='00:00:00')))
                    {
                        $colore_testo='Viola';
                    }
                    if((($timbratura_situazione!='')&&($timbratura_situazione!='fine'))||(($timbratura_situazione2!='')&&($timbratura_situazione!='fine')))
                    {
                        $colore_testo='Rosso';
                    }
                    
                    
                    
                    //colore sfondo
                    if($timbratura_dipendenteinterno=='si')
                    {
                        $colore_sfondo='Arancione';
                    }
                    
                    if($contratto_visto=='amm')
                    {
                        $colore_sfondo='Giallo';
                    }
                    if($contratto_visto=='dir')
                    {
                        $colore_sfondo='VerdeChiaro';
                    }
                    if($timbratura_prova=='si')
                    {
                       $colore_sfondo='VerdeScuro'; 
                    }

                    
                    
                    if($timbratura_tipogiorno=='festivo')
                    {
                        $colore_sfondo='Azzurro';
                    }
                    if($timbratura_tipogiorno=='festivoparificato')
                    {
                        $colore_sfondo='Rosso';
                        $colore_testo='Nero';
                    }
                    if($timbratura_tipogiorno=='festivononparificato')
                    {
                        $colore_sfondo='Grigio';
                    }
                    if($timbratura_viola=='si')
                    {
                        $colore_sfondo='Viola';
                    }
                    if($timbratura_festivopagato=='si')
                    {
                        $colore_sfondo='VerdeIntermedio';
                    }
                    
                    
                    //memo
                    $timbratura_osservazioni= str_replace("'", "''", $timbratura_osservazioni);
                    $memo="Memo:$timbratura_osservazioni";
                    
                    
                    //bordo
                    if($timbratura_notifica=='si-inizio')
                    {
                        $bordo='start';
                    }
                    if($timbratura_notifica=='si')
                    {
                        $bordo='up_down';
                    }
                    if($timbratura_notifica=='si-fine')
                    {
                        $bordo='end';
                    }
                    if($timbratura_notifica=='si-giornata')
                    {
                        $bordo='start_end';
                    }
                    if($timbratura_notifica=='si-accorciamento')
                    {
                        $bordo='up_down_dashed';
                    }
                    if($timbratura_notifica=='si-fine-accorciamento')
                    {
                        $bordo='end_dashed';
                    }
                    $valore_giorno="$testo#$colore_testo#$colore_sfondo#$memo#$bordo";
                    $num=date('j',strtotime($data));
                    if($set!='')
                    {
                        $set=$set.',';
                    }
                    $set=$set."g".$num."d='$valore_giorno'";
                }
                
                if($set!='')
                {
                    $sql="
                        UPDATE user_presenzemensili
                        SET $set
                         WHERE recordid_='$presenze_recordid'
                        ";
                    $this->execute_query($sql);
                }
                //$this->syslog($sql);
                $this->syslog('Aggiorna zone lavorative e note');
                $mesecorrente=date('n');
                if($presenze_record_mese==$mesecorrente)
                {
                    $zonelavorative=$dipendente['zonelavorative'];
                    $note=$dipendente['notegenerali'];
                    $sql="
                        UPDATE user_presenzemensili
                        SET  zonelavorative='$zonelavorative'
                         WHERE recordid_='$presenze_recordid'
                        ";
                    $this->execute_query($sql);
                    
                }
                
                $this->set_notetempo_presenzemensili($presenze_recordid);
                $this->syslog($presenze_recordid,"aggiorna_presenze end");
            }
            
            
            
            function calcola_numerazione_presenze_totale()
            {
                echo "Calcolo numerazione presenze totale<br/>";
                $today_year=date('Y');
                $today_month=date('n');
                for($a=2019;$a<=$today_year;$a++)
                {
                    for($m=01;$m<=12;$m++)
                    {
                        $this->calcola_numerazione_presenze($a,$m);
                    }
                }
            }
            
            function calcola_numerazione_presenze($anno,$mese)
            {
                echo "calcolo numerazione $anno-$mese <br/>";
                $sql="
                    UPDATE user_presenzemensili SET no=NULL
                    where anno=$anno AND mese='$mese'
                    ";
                $this->execute_query($sql);
                
                
                $sql="
                    UPDATE user_presenzemensili
                    JOIN 
                    (SELECT p.cognome,p.nome,p.id,p.recordid_,MAX(r.data) as max_data_prova,MIN(r.data) as min_data_prova
                    FROM user_presenzemensili as p JOIN user_rapportidilavoro as r ON p.recordid_=r.recordidpresenzemensili_

                    WHERE p.anno=$anno AND p.mese='$mese' AND r.prova='si' AND (p.alias is null OR p.alias='')
                    GROUP BY p.cognome,p.id,p.recordid_
                    ORDER BY max_data_prova ASC
                     ) as temp ON user_presenzemensili.recordid_=temp.recordid_
                    SET user_presenzemensili.datafine_prova=max_data_prova,user_presenzemensili.datainizio_prova=min_data_prova
                    "
                        ;
                $this->execute_query($sql);
                        
                $this->db->trans_start();
                $sql="
                    SET @ordering = 0;
                    ";
                $this->db->query($sql);

                
                $sql="UPDATE
                      user_presenzemensili SET no = (@ordering := @ordering + 1)
                    WHERE
                      anno=$anno AND mese='$mese' AND (alias is null OR alias='')
                    ORDER BY datafine_prova ASC,datainizio_prova ASC,cognome ASC,nome ASC
                    ";
                $this->db->query($sql);
                
                $this->db->trans_complete();    
                
                $sql="UPDATE
                      user_presenzemensili as p1 
                      JOIN 
                      (select recordiddipendenti_,alias,no from user_presenzemensili WHERE anno=$anno AND mese='$mese' AND (alias is null OR alias='')) as p2 
                       ON p1.recordiddipendenti_=p2.recordiddipendenti_
                       SET p1.no=p2.no
                    WHERE
                      p1.anno=$anno AND p1.mese='$mese' AND p1.alias is not null AND p1.alias!=''
                    ";
                $this->execute_query($sql);
                
                
                
            }
            
            function aggiorna_disponibilita_dipendente($recordid_dipendente)
            {
                
                $this->syslog($recordid_dipendente,"aggiorna_disponibilita_dipendente start",false);
                
                $today=date('Y-m-d');
                $setdisponibilita=false;
                
                $sql="
                    UPDATE user_dipendenti
                    SET disponibiledal=null
                    WHERE recordid_='$recordid_dipendente' 
                    ";
                $this->execute_query($sql);
                
                
                // check disponibilita
                $sql="
                    select 'disponibile',MAX(rl.data) as data
                    from user_rapportidilavoro as rl JOIN user_dipendenti as d ON rl.recordiddipendenti_=d.recordid_
                    where 
                    rl.recordiddipendenti_='$recordid_dipendente'
                    AND
                    rl.situazione2='d'
                    AND
                    d.situazione!='chiuso'
                    ";
                
                $results= $this->select($sql);
                $data_disponibilita=$results[0]['data'];
                
                if(isnotempty($data_disponibilita))
                {
                    
                    $sql="

                        select MIN(datalimite) as datalimite
                        FROM
                            (
                            select 'azienda',MIN(rl.data) as datalimite
                            from user_rapportidilavoro as rl
                            where 
                            rl.recordiddipendenti_='$recordid_dipendente' and rl.data>='$data_disponibilita'
                            AND
                            (rl.recordidazienda_!='' AND (rl.recordidcontratti_ IS null OR rl.recordidcontratti_ =''))
                            
                            UNION
                            
                            select 'azienda',MIN(rl.data) as datalimite
                            from user_rapportidilavoro as rl JOIN user_contratti con ON rl.recordidcontratti_=con.recordid_
                            where 
                            rl.recordiddipendenti_='$recordid_dipendente' and rl.data>='$data_disponibilita'
                            AND
                            (con.datainizio>='$data_disponibilita')

                            UNION

                            select 'Laboratorio',MIN(rl.data) as datalimite
                            from user_rapportidilavoro as rl
                            where 
                            rl.recordiddipendenti_='$recordid_dipendente' and rl.data>='$data_disponibilita'
                            AND
                            (rl.laboratorio!='' AND rl.laboratorio is not null )
                            
                            UNION 

                            select 'Fine',MIN(rl.data) as datalimite
                            from user_rapportidilavoro as rl
                            where 
                            rl.recordiddipendenti_='$recordid_dipendente' and rl.data>='$data_disponibilita'
                            AND
                            rl.situazione='fine'

                            UNION 

                            select 'Uscito',MIN(rl.data) as datalimite
                            from user_rapportidilavoro as rl
                            where 
                            rl.recordiddipendenti_='$recordid_dipendente' and rl.data>='$data_disponibilita'
                            AND
                            rl.situazione2='Uscito'
                            
                            
                            ) 
                        as x
                        ";
                    $results= $this->select($sql);
                    $datalimite=$results[0]['datalimite'];

                    

                    if(isnotempty($datalimite))
                    {
                        if($data_disponibilita>$datalimite)
                        {
                            $setdisponibilita=true;
                        }
                        else
                        {
                            if($today<$datalimite)
                            {
                                $setdisponibilita=true;
                            }
                        }
                    }
                    else
                    {
                        $setdisponibilita=true;
                    }



                    if($setdisponibilita)
                    {
                        $sql="
                            UPDATE user_dipendenti
                            SET disponibiledal='$data_disponibilita'
                            WHERE recordid_='$recordid_dipendente' 
                            ";
                        $this->execute_query($sql);
                    }
                }
                
                $this->syslog($recordid_dipendente,"aggiorna_disponibilita_dipendente end",false);
            }
            
            
            function get_lista_disponibilita()
            {
                $today=date('Y-m-d');
                $today_month=date('n');
                $today_year=date('Y');
                $sql="
                    SELECT d.id,d.cognome,d.nome,d.zonelavorative,pr.notetempo as notegenerali,d.disponibiledal as disponibilita,rl.laboratorio,rl.dipendenteinterno,con.visto
                    FROM user_dipendenti as d
                    LEFT JOIN user_rapportidilavoro as rl
                    ON rl.recordiddipendenti_=d.recordid_
                    LEFT JOIN user_contratti as con
                    ON rl.recordidcontratti_=con.recordid_
                    LEFT JOIN user_presenzemensili as pr
                    ON d.recordid_=pr.recordiddipendenti_
                    where disponibiledal is not null and disponibiledal != '0000-00-00' AND rl.data='$today' AND (rl.alias is null OR rl.alias='') AND (pr.alias is null OR pr.alias='')  AND (pr.mese='$today_month' and pr.anno='$today_year')
                    ORDER BY disponibiledal asc

                    ";
                $results= $this->select($sql);
                return $results;
            }
            
            function get_lista_dachiudere()
            {
                $today=date('Y-m-d');
                $today_month=date('n');
                $today_year=date('Y');
                
                $sql="
                    SELECT d.id,d.cognome,d.nome,d.zonelavorative,pr.notetempo as notegenerali,d.disponibiledal as disponibilita,rl.laboratorio,rl.dipendenteinterno,con.visto
                    FROM user_dipendenti as d
                    LEFT JOIN user_rapportidilavoro as rl
                    ON rl.recordiddipendenti_=d.recordid_
                    LEFT JOIN user_contratti as con
                    ON rl.recordidcontratti_=con.recordid_
                    LEFT JOIN user_presenzemensili as pr
                    ON d.recordid_=pr.recordiddipendenti_
                    where d.situazione='chi' AND rl.data='$today' AND (rl.alias is null OR rl.alias='') AND (pr.mese='$today_month' and pr.anno='$today_year')
                    ORDER BY disponibiledal asc
                    ";
                $results= $this->select($sql);
                return $results;
            }
            
            function get_lista_dariaprire()
            {
                $today=date('Y-m-d');
                $today_month=date('n');
                $today_year=date('Y');
                
                /*$sql="
                    SELECT d.id,d.cognome,d.nome,d.zonelavorative,d.notegenerali,rl.laboratorio,rl.dipendenteinterno,con.visto,d.datachiusura,d.datachiusura2,d.datachiusura3,d.datachiusura4
                    FROM user_dipendenti as d
                    LEFT JOIN user_rapportidilavoro as rl
                    ON rl.recordiddipendenti_=d.recordid_
                    LEFT JOIN user_contratti as con
                    ON rl.recordidcontratti_=con.recordid_
                    where d.situazione='ria' AND rl.data='$today' AND (rl.alias is null OR rl.alias='')
                    ORDER BY disponibiledal asc

                    ";*/
                $sql="
                    SELECT d.recordid_,d.id,d.cognome,d.nome,d.zonelavorative,d.ultimenote,d.ultimadatachiusura,d.situazione
                    FROM user_dipendenti as d
                    where d.situazione='ria'
                    ORDER BY ultimadatachiusura asc

                    ";
                
                
                $results= $this->select($sql);
                
                
                return $results;
            }
            
            
            function rinnova_ccl($recordid_ccl)
            {
                //duplicazione del ccl mettendolo in stato da verificare
                $recordid_ccl_new=$this->duplica_record('ccl', $recordid_ccl);
                $sql="UPDATE user_ccl SET stato='Da verificare',versione=versione+1,dataversione=CURRENT_DATE() WHERE recordid_='$recordid_ccl_new'";
                $this->execute_query($sql);
                
                //duplicazione blocchi associati al ccl
                $sql="SELECT * FROM user_cclblocchi WHERE recordidccl_='$recordid_ccl'";
                $cclblocchi_rows=$this->select($sql);
                foreach ($cclblocchi_rows as $key => $row) {
                    $recordid_cclblocchi=$row['recordid_'];
                    
                    
                    $recordid_cclblocchi_new=$this->duplica_record('cclblocchi', $recordid_cclblocchi);
                    
                    $sql="UPDATE user_cclblocchi SET recordidccl_='$recordid_ccl_new' WHERE recordid_='$recordid_cclblocchi_new'";
                    $this->execute_query($sql);
                }

                //duplicazione e archiviazione records fascia eta collegati al ccl
                $sql="SELECT * FROM user_fasciaeta WHERE recordidccl_='$recordid_ccl'";
                $rows=$this->select($sql);
                foreach ($rows as $key => $row) {
                    $recordid_fasciaeta=$row['recordid_'];
                    
                    
                    $recordid_fasciaeta_new=$this->duplica_record('fasciaeta', $recordid_fasciaeta);
                    
                    $sql="UPDATE user_fasciaeta SET stato='Da verificare',versione=versione+1,dataversione=CURRENT_DATE(),recordidccl_='$recordid_ccl_new' WHERE recordid_='$recordid_fasciaeta_new'";
                    $this->execute_query($sql);
                }
                
                //duplicazione e archiviazione records qualifiche collegati al ccl
                $sql="SELECT * FROM user_qualifiche WHERE recordidccl_='$recordid_ccl'";
                $rows=$this->select($sql);
                foreach ($rows as $key => $row) {
                    $recordid_qualifica=$row['recordid_'];
                    
                    
                    $recordid_qualifica_new=$this->duplica_record('qualifiche', $recordid_qualifica);
                    
                    $sql="UPDATE user_qualifiche SET stato='Da verificare',versione=versione+1,dataversione=CURRENT_DATE(),recordidccl_='$recordid_ccl_new' WHERE recordid_='$recordid_qualifica_new'";
                    $this->execute_query($sql);
                    
                    //duplicazione e archiviazione records professioni collegate alla qualifica
                    $sql="SELECT * FROM user_professioni WHERE recordidqualifiche_='$recordid_qualifica'";
                    $rows2=$this->select($sql);
                    foreach ($rows2 as $key2 => $row2) {
                        $recordid_professione=$row2['recordid_'];
                    

                        $recordid_professione_new=$this->duplica_record('professioni', $recordid_professione);

                        $sql="UPDATE user_professioni SET stato='Da verificare',versione=versione+1,dataversione=CURRENT_DATE(),recordidccl_='$recordid_ccl_new',recordidqualifiche_='$recordid_qualifica_new' WHERE recordid_='$recordid_professione_new'";
                        $this->execute_query($sql);
                        
                    }
                }
                

                
                
                return $recordid_ccl_new;
            }
            
            
            function approva_ccl($recordid_ccl)
            {
                $sql="
                    UPDATE user_ccl
                    SET stato='Verificato'
                    WHERE recordid_='$recordid_ccl'
                    ";
                $this->execute_query($sql);
                
                $sql="UPDATE user_fasciaeta SET stato='Verificato' WHERE recordidccl_='$recordid_ccl'";
                $this->execute_query($sql);
                
                $sql="UPDATE user_qualifiche SET stato='Verificato' WHERE recordidccl_='$recordid_ccl'";
                $this->execute_query($sql);
                
                $sql="UPDATE user_professioni SET stato='Verificato' WHERE recordidccl_='$recordid_ccl'";
                $this->execute_query($sql);
                
            }
            
            
            function sync_linkedmaster_contratto($recordid_contratto)
            {
                $recordid_professione= $this->db_get_value('user_contratti','recordidprofessioni_',"recordid_='$recordid_contratto'");
                $recordid_qualifica=$this->db_get_value('user_professioni','recordidqualifiche_',"recordid_='$recordid_professione'");
                $recordid_ccl=$this->db_get_value('user_professioni','recordidccl_',"recordid_='$recordid_professione'");
                $sql="
                    UPDATE
                    user_contratti
                    set recordidqualifiche_='$recordid_qualifica', recordidccl_='$recordid_ccl'
                    WHERE recordid_='$recordid_contratto';
                    ";
                $this->execute_query($sql);
            }
            
            
            function genera_calcolofatturato()
            {
                
            }
            
            function genera_calcolofatturato_ccl($recordid_ccl,$anno,$dati=null)
            {
                if($dati==null)
                {
                    $dati= $this->get_dati_calcolofatturato($recordid_ccl,$anno);
                }
                //$sql="DELETE from user_calcolofatturato where recordidccl_='$recordid_ccl'";
                //$this->execute_query($sql);
                $ccl= $this->db_get_row('user_ccl','*',"recordid_='$recordid_ccl'");
                $spese=$ccl['spese'];
                $festiviperc=$dati['festiviperc'];
                $tredperc=$dati['tredperc'];
                $resor=$ccl['pensionamento'];//RESOR
                $spese=$ccl['spese'];
                $utile=$ccl['utile'];
                $fasciaeta_rows=$this->db_get('user_fasciaeta', "*", "recordidccl_='$recordid_ccl'");
                $qualifiche_rows=$this->db_get('user_qualifiche', "*", "recordidccl_='$recordid_ccl'");
                foreach ($fasciaeta_rows as $key => $fasciaeta) {
                    $recordid_fasciaeta=$fasciaeta['recordid_'];
                    $vacanzeperc=$fasciaeta['vacanzeperc'];
                    $lppperc=$fasciaeta['lppperc'];
                    foreach ($qualifiche_rows as $key => $qualifica) {

                        $recordid_qualifica=$qualifica['recordid_'];
                        $calcolofatturato_row=$this->db_get_row("user_calcolofatturato","*","recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno'");
                        if($this->isempty($calcolofatturato_row))
                        {
                            $esperienza=$qualifica['esperienza'];
                            $base=$qualifica['base'];
                            $fields['recordidccl_']=$recordid_ccl;
                            $fields['recordidqualifiche_']=$recordid_qualifica;
                            $fields['recordidfasciaeta_']=$recordid_fasciaeta;
                            $fields['esperienza']=$esperienza;
                            $fields['base']=$base;
                            $fields['vacanzeperc']=$vacanzeperc;
                            $fields['vacanze']=round($base/100*$vacanzeperc,2);
                            $fields['giornifest']=round($base/100*$festiviperc);
                            $fields['tredicesima']=round($base/100*$tredperc);
                            $fields['lppperc']=$lppperc;
                            $fields['lpp']=sprintf("%.2f", round($base/100*$lppperc,2));
                            $fields['resor']=$resor;
                            $fields['spese']=$spese;
                            $fields['utile']=$utile;
                            $fields['avs']=round($base/100*$dati['avsperc'],2);
                            $fields['ad']=round($base/100*$dati['adperc'],2);
                            $fields['infprof']=round($base/100*$dati['infprofperc'],2);
                            $fields['malattia']=round($base/100*$dati['malattiaperc'],2);
                            $fields['speseamm']=round($base/100*$dati['speseammperc'],2);
                            $fields['assfigli']=round($base/100*$dati['assfigliperc'],2);
                            $fields['assfigliint']=round($base/100*$dati['assfigliintperc'],2);
                            $fields['lorform']=round($base/100*$dati['lorformperc'],2);
                            $fields['formcont']=round($base/100*$dati['formcontperc'],2);
                            $fields['indpasto']=0;
                            $fields['totfatt']=$base;
                            $fields['totul']=$base;
                            $fields['ric']=$base;
                            $fields['prezzovendita']='';
                            $fields['anno']=$anno;

                            $this->insert_record('calcolofatturato', 1, $fields);
                        }
                    }
                }
            }
            
            function get_dati_calcolofatturato($recordid_ccl,$anno)
            {
                $ccl= $this->db_get_row('user_ccl','*',"recordid_='$recordid_ccl'");
                $dati['nomeccl']=$ccl['nomeccl'];
                $tipopensionamento=$ccl['pensionamento'];//RESOR
                $pensionamentoperc=$ccl['pensionamentoperc'];
                $spese=$ccl['spese'];
                $spesemensile=$ccl['spesemensile'];
                $utile=$ccl['utile'];
                $utilemensile=$ccl['utilemensile'];
                $indpasto=$ccl['indpasto'];
                $festiviformula=$ccl['festiviformula'];
                $vacanzeformula=$ccl['vacanzeformula'];
                $tredformula=$ccl['tredformula'];
                $festiviperc_ccl=$ccl['festiviperc'];
                $dati['festiviperc']=$festiviperc_ccl;
                $tredperc_ccl=$ccl['tredperc'];
                $dati['tredperc']=$tredperc_ccl;
                $dati['fascieeta']=$this->db_get('user_fasciaeta', "*", "recordidccl_='$recordid_ccl'");
                $dati['qualifiche']=$this->db_get('user_qualifiche', "*", "recordidccl_='$recordid_ccl'");
                $dati['tipopensionamento']=$tipopensionamento;
                $dati['pensionamentoperc']=$pensionamentoperc;
                $dati['vacanze']=array();
                $dati['lpp']=array();
                
                $fascieta_vacanzeperc=$this->db_get('user_fasciaeta', "vacanzeperc", "recordidccl_='$recordid_ccl' GROUP BY vacanzeperc","ORDER BY vacanzeperc asc");
                foreach ($fascieta_vacanzeperc as $key => $fasciaeta) {
                    if(isnotempty($fasciaeta['vacanzeperc']))
                    {
                        $dati['vacanze'][$fasciaeta['vacanzeperc']]=$fasciaeta['vacanzeperc'];
                    }
                }
                
                $fascieta_lppperc=$this->db_get('user_fasciaeta', "lppperc", "recordidccl_='$recordid_ccl' GROUP BY lppperc","ORDER BY lppperc asc");
                foreach ($fascieta_lppperc as $key => $fasciaeta) {
                    if(isnotempty($fasciaeta['lppperc']))
                    {
                        $dati['lpp'][$fasciaeta['lppperc']]=$fasciaeta['lppperc'];
                    }
                }
                
                
                
                $dati['oneri']=array();
                $oneri= $this->db_get_row('user_oneri','*',"anno='$anno'");
                if($oneri!=null)
                {
                    $dati['avsperc']=$oneri['avs'];
                    $dati['adperc']=$oneri['ad'];
                    $dati['infprofperc']=$oneri['infprof'];
                    $dati['malattiaperc']=$oneri['malattia'];
                    $dati['speseammperc']=$oneri['speseamm'];
                    $dati['assfigliperc']=$oneri['assfigli'];
                    $dati['assfigliintperc']=$oneri['assfigliint'];
                    $dati['lorformperc']=$oneri['lorform'];
                    $dati['formcontperc']=$oneri['formcont'];
                    $importocoordinamentolpp=$oneri['importocoordinamentolpp'];
                    if(isempty($importocoordinamentolpp))
                    {
                        $importocoordinamentolpp=0;
                    }
                }
                else
                {
                    $dati['avsperc']=0;
                    $dati['adperc']=0;
                    $dati['infprofperc']=0;
                    $dati['malattiaperc']=0;
                    $dati['speseammperc']=0;
                    $dati['assfigliperc']=0;
                    $dati['assfigliintperc']=0;
                    $dati['lorformperc']=0;
                    $dati['formcontperc']=0;
                    $importocoordinamentolpp=0;
                }
                
                
                
                $dati['records']=array();
                foreach ($dati['fascieeta'] as $key => $fasciaeta) {
                    $recordid_fasciaeta=$fasciaeta['recordid_'];
                    $vacanzeperc_fasciaeta=$fasciaeta['vacanzeperc'];
                    $lppperc=$fasciaeta['lppperc'];
                    foreach ($dati['qualifiche'] as $key => $qualifica) {
                        $recordid_qualifica=$qualifica['recordid_'];
                        $esperienza=$qualifica['esperienza'];
                        $base=$qualifica['base'];
                        $tipostipendio=$qualifica['tipostipendio'];
                        
                        $calcolofatturato_row=$this->db_get_row("user_calcolofatturato","*","recordidccl_='$recordid_ccl' AND recordidfasciaeta_='$recordid_fasciaeta' AND recordidqualifiche_='$recordid_qualifica' AND anno='$anno'");
                        if($calcolofatturato_row!=null)
                        {
                        $prezzovendita= $calcolofatturato_row['prezzovendita'];
                        $prezzovenditabase= $calcolofatturato_row['prezzovenditabase'];
                        
                        if(isnotempty($calcolofatturato_row['vacanzeperc']))
                        {
                            $vacanzeperc= $calcolofatturato_row['vacanzeperc'];
                        }
                        else
                        {
                            $vacanzeperc=$vacanzeperc_fasciaeta;
                        }
                        
                        if(isnotempty($calcolofatturato_row['festiviperc']))
                        {
                            $festiviperc= $calcolofatturato_row['festiviperc'];
                        }
                        else
                        {
                            $festiviperc=$festiviperc_ccl;
                        }
                        
                        if(isnotempty($calcolofatturato_row['tredicesimaperc']))
                        {
                            $tredperc= $calcolofatturato_row['tredicesimaperc'];
                        }
                        else
                        {
                            $tredperc=$tredperc_ccl;
                        }
                        
                        
                        $fields['recordidccl_']=$recordid_ccl;
                        $fields['recordidqualifiche_']=$recordid_qualifica;
                        $fields['recordidfasciaeta_']=$recordid_fasciaeta;
                        $fields['esperienza']=$esperienza;
                        $fields['base']=$base;
                        
                        
                        $festivi=$base;
                        if(isempty($festiviperc))
                        {
                            $festiviperc=0;
                        }
                        $festivi=round($festivi/100*$dati['festiviperc'],2);
                        $fields['giornifest']=$festivi;
                        
                        $vacanze=$base;
                        if($vacanzeformula=='Base')
                        {
                            $vacanze=$base;
                        }
                        if($vacanzeformula=='Base + Festivo')
                        {
                            $vacanze=$base+$festivi;
                        }
                        $vacanze=round($vacanze/100*$vacanzeperc,2);
                        $fields['vacanzeperc']=$vacanzeperc;
                        $fields['vacanze']=$vacanze;
                        
                        
                        $tred=$base;
                        if($tredformula=='Base')
                        {
                            $tred=$base;
                        }
                        if($tredformula=='Base + Festivo')
                        {
                            $tred=$base+$festivi;
                        }
                        if($tredformula=='Base + vacanze')
                        {
                            $tred=$base+$vacanze;
                        }
                        if($tredformula=='Base + festivo + vacanze')
                        {
                            $tred=$base+$festivi+$vacanze;
                        }
                        $tred=$tred/100*$dati['tredperc'];

                        $fields['tredicesima']=floor($tred*100)/100;
                         
                        $totale=round($base+$festivi+$vacanze+$tred,2);
                        
                       
                        $fields['lppperc']=$lppperc;
                        $fields['lpp']=round(($totale-$importocoordinamentolpp)/100*$lppperc,2);
                        $fields['pensionamento']=round($totale/100*$dati['pensionamentoperc'],2);
                        if($tipostipendio=='mensile')
                        {
                            $fields['spese']=$spesemensile;
                            $fields['utile']=$utilemensile; 
                        }
                        else
                        {
                            $fields['spese']=$spese;
                            $fields['utile']=$utile; 
                        }
                        
                        $avs=round($totale/100*$dati['avsperc'],2);
                        $fields['avs']=$avs;
                        $fields['ad']=round($totale/100*$dati['adperc'],2);
                        $fields['infprof']=round($totale/100*$dati['infprofperc'],2);
                        $fields['malattia']=round($totale/100*$dati['malattiaperc'],2);
                        $fields['speseamm']=round($avs/100*$dati['speseammperc'],2);
                        $fields['assfigli']=round($totale/100*$dati['assfigliperc'],2);
                        $fields['assfigliint']=round($totale/100*$dati['assfigliintperc'],2);
                        $fields['lorform']=round($totale/100*$dati['lorformperc'],2);
                        $fields['formcont']=round($totale/100*$dati['formcontperc'],2);
                        $fields['indpasto']=$indpasto;
                        if($tipostipendio=='mensile')
                        {
                            $fields['totul']=$spesemensile+$utilemensile;
                        }
                        else
                        {
                           $fields['totul']=$spese+$utile; 
                        }
                        
                        $totfatt=$fields['base']+$fields['vacanze']+$fields['giornifest']+$fields['tredicesima']+$fields['lpp']+$fields['pensionamento']+$fields['avs']+$fields['ad']+$fields['infprof']+$fields['malattia']+$fields['speseamm']+$fields['assfigli']+$fields['assfigliint']+$fields['lorform']+$fields['formcont']+$fields['indpasto']+$spese+$utile;
                        $fields['totfatt']=$totfatt;
                        
                                              
                        $fields2=array();
                        foreach ($fields as $key => $field) {
                            $fields2[$key]=sprintf("%.2f", $field);
                            
                        }
                        
                        
                        $fields2['prezzovenditabase']=$prezzovenditabase;
                        if(isnotempty($prezzovendita))
                        {
                            $fields2['prezzovendita']=sprintf("%.2f", $prezzovendita);
                            $fields2['ric']=sprintf("%.2f", $prezzovendita+$fields['totul']-$totfatt);
                        }
                        else
                        {
                            $fields2['prezzovendita']='';
                            $fields2['ric']='';
                        }
                        $dati['records'][$recordid_fasciaeta][$recordid_qualifica]= $fields2;
                    }
                    }
                }
                
                
                return $dati;
                

            }
            
            
            function saveReportCCL($post)
            {
                $recordid_ccl=$post['recordid_ccl'];
                $blocchi=$post['block'];
                $sql="
                    DELETE FROM user_cclblocchi
                    where recordidccl_='$recordid_ccl'
                    ";
                $this->execute_query($sql);
                foreach ($blocchi as $key => $block) {
                    if($key!='grid-stack-item-template')
                    {
                        $fields=array();
                        $fields['recordidccl_']=$recordid_ccl;
                        $htmlcontent=$block['htmlcontent'];
                        $htmlcontent= str_replace("'", "''", $htmlcontent);
                        $fields['contenuto']=$htmlcontent;
                        
                        $gsx=$block['gsx'];
                        $fields['gsx']=$gsx;
                        
                        $gsx=$block['gsy'];
                        $fields['gsy']=$gsx;
                        
                        $gsheight=$block['gsheight'];
                        $fields['gsheight']=$gsheight;
                        
                        $gswidth=$block['gswidth'];
                        $fields['gswidth']=$gswidth;
                        
                        $sfondo=$block['sfondo'];
                        $fields['sfondo']=$sfondo;
                        
                        $fields['zona']=$block['zona'];
                        
                        $this->insert_record('cclblocchi',1,$fields);
                    }
                }
                
                
            }
            
            
            //custom 3p
            function script_sistema_presenzemensili()
            {
                $records=$this->Sys_model->db_get('user_presenzemensili','*',"id is null");
                foreach ($array as $key => $value) {
                    
                }
            }
            
            
            //custom 3p
            function set_ultimenote($recordid_dipendente)
            {
                $today_year=date('Y');
                $today_month=date('n');
                $note= $this->db_get_value('user_presenzemensili',"notetempo","recordiddipendenti_='$recordid_dipendente' AND (anno<='$today_year' OR (anno='$today_year' AND mese<='$today_month'))  ","ORDER BY recordid_ desc limit 1");
                $sql="
                    UPDATE user_dipendenti
                    SET ultimenote='$note'
                        where recordid_='$recordid_dipendente'
                    ";
                $this->execute_query($sql);
                
            }
            
            function set_ultimadatachiusura($recordid_dipendente)
            {
                $dipendente= $this->get_record('dipendenti',$recordid_dipendente);
                $datachiusura='';
                $sql="
                    select datachiusura
                    from user_aperture
                    where recordiddipendenti_='$recordid_dipendente' AND datachiusura is not null 
                    ";
                $aperture= $this->select($sql);
                foreach ($aperture as $key => $apertura) {
                    $datachiusura=$apertura['datachiusura'];
                }
                if($datachiusura=='')
                {
                    $datachiusura=$dipendente['datachiusura4'];
                }
                if($this->isempty($datachiusura))
                {
                    $datachiusura=$dipendente['datachiusura3'];
                }
                if($this->isempty($datachiusura))
                {
                    $datachiusura=$dipendente['datachiusura2'];
                }
                if($this->isempty($datachiusura))
                {
                    $datachiusura=$dipendente['datachiusura'];
                }
                
                $sql="
                    UPDATE user_dipendenti
                    SET ultimadatachiusura='$datachiusura'
                        WHERE recordid_='$recordid_dipendente'
                    ";
                $this->execute_query($sql);
            }
            
            
            function calcola_preavvisodisdetta($recordid_contratto)
            {
                $contratto= $this->get_record('contratti', $recordid_contratto);
                $datainizio=$contratto['datainizio'];
                $diff=  date_diff(date_create($datainizio), date_create(date('Y-m-d')));
                $mesi=$diff->format('%m');
                $giorni=$diff->format('%d');
                $preavvisodisdetta="$mesi mesi, $giorni giorni <br/>data";
                return $preavvisodisdetta;
                //$sql="UPDATE user_contratti SET preavvisodisdetta='$preavvisodisdetta' WHERE recordid_='$recordid_contratto'";
                //$this->execute_query($sql);
            }
            
            
            function get_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl)
            {
                $calendarioaziendale= $this->db_get('user_calendarioaziendale','*',"recordidazienda_='$recordid_azienda' AND data>='$anno-01-01' AND data<='$anno-12-31' AND recordidccl_='$recordid_ccl'");
                if(count($calendarioaziendale)==0)
                {
                    $giorni= $this->db_get('user_calendario','*',"giorno>='$anno-01-01' AND giorno<='$anno-12-31'" );
                    
                    foreach ($giorni as $key => $giorno) {
                        $data=$giorno['giorno'];
                        $fields['id']= $this->generate_seriale('calendarioaziendale', 'id');
                        $fields['data']=$data;
                        $fields['recordidazienda_']=$recordid_azienda;
                        $fields['recordidccl_']=$recordid_ccl;
                        $this->insert_record('calendarioaziendale', 1, $fields);
                    }
                    
                    
                }
                $calendarioaziendale=array();
                $totgiorni_anno=0;
                $totore_anno=0;
                for($mese=1;$mese<=12;$mese++)
                {
                    $lmese=sprintf('%02d', $mese);
                    $sql="SELECT user_calendarioaziendale.recordid_,user_calendarioaziendale.data,user_calendarioaziendale.ore,user_calendario.giornosettimana,user_calendario.tipogiorno,user_calendario.descrizione
                        FROM user_calendarioaziendale JOIN user_calendario ON user_calendarioaziendale.data=user_calendario.giorno
                        WHERE
                        recordidazienda_='$recordid_azienda' AND data>='$anno-$lmese-01' AND data<='$anno-$lmese-31' AND recordidccl_='$recordid_ccl'
                        ";
                    $calendarioaziendale['mesi'][$mese]['giorni']= $this->select($sql);
                    $calendarioaziendale['mesi'][$mese]['nome']= $this->db_get_value('sys_lookup_table_item','itemdesc',"lookuptableid='mese_presenzemensili' AND itemcode=$mese");
                    $sql="
                        select sum(ore) as totore
                        from user_calendarioaziendale
                        WHERE
                        recordidazienda_='$recordid_azienda' AND data>='$anno-$lmese-01' AND data<='$anno-$lmese-31' AND recordidccl_='$recordid_ccl'
                        ";
                    $result= $this->select($sql);
                    if(count($result)>0)
                    {
                        $totore=$result[0]['totore'];
                    }
                    if($totore==null)
                    {
                       $totore=0; 
                    }
                    $totore=round($totore,2);
                    $calendarioaziendale['mesi'][$mese]['totore']=$totore;
                    $totore_anno=$totore_anno+$totore;
                    
                    $sql="
                        select COUNT(giorno) as totgiorni
                        from user_calendario
                        WHERE
                        giorno>='$anno-$lmese-01' AND giorno<='$anno-$lmese-31' AND tipogiorno='feriale'
                        ";
                    $result= $this->select($sql);
                    if(count($result)>0)
                    {
                        $totgiorni=$result[0]['totgiorni'];
                    }
                    if($totgiorni==null)
                    {
                       $totgiorni=0; 
                    }
                    $calendarioaziendale['mesi'][$mese]['totgiorni']=$totgiorni;
                    $totgiorni_anno=$totgiorni_anno+$totgiorni;
                }
                $calendarioaziendale['totore_anno']=$totore_anno;
                $calendarioaziendale['totgiorni_anno']=$totgiorni_anno;
          
                
                return $calendarioaziendale;
            }
            
            function salva_calendarioaziendale($post)
            {
                $giorni=$post['giorno'];
                foreach ($giorni as $giorno_recordid => $giorno_ore) {
                    $sql="
                        UPDATE user_calendarioaziendale
                        SET ore=$giorno_ore
                        WHERE recordid_='$giorno_recordid'
                        ";
                    $this->execute_query($sql);
                }
                $ponti=$post['ponti'];
                foreach ($ponti as $ponte_recordid => $ponte) {
                    $sql="
                        UPDATE user_calendarioaziendale
                        SET ponte='si'
                        WHERE recordid_='$ponte_recordid'
                        ";
                    $this->execute_query($sql);
                }
            }
            
            function cancella_calendarioaziendale($recordid_azienda,$anno,$recordid_ccl)
            {
                $sql="
                    DELETE FROM user_calendarioaziendale
                    WHERE recordidazienda_='$recordid_azienda' AND data>='$anno-01-01' AND data<='$anno-12-31' AND recordidccl_='$recordid_ccl'
                    ";
                $this->execute_query($sql);
            }
            
            
            
            function custom3p_genera_listaclienti()
            {
                $sql="
                    DELETE FROM user_listaclienti
                    ";
                $this->execute_query($sql);
                $sql="
                    SELECT *
                    FROM user_azienda
                    ";
                $aziende= $this->select($sql);
                $clienti=array();
                foreach ($aziende as $key => $azienda) {
                    $this->add_custom_update_php('custom3p_genera_listaclienti_azienda', $azienda['recordid_']);
                    
                }
                return $clienti;        
            }
            
            function custom3p_aggiorna_stato_clienti()
            {

                $sql="
                    SELECT *
                    FROM user_azienda
                    ";
                $aziende= $this->select($sql);
                $clienti=array();
                foreach ($aziende as $key => $azienda) {
                    $this->add_custom_update_php('custom3p_aggiorna_stato_cliente', $azienda['recordid_']);
                    
                }
            }
            
            function custom3p_aggiorna_stato_cliente($recordid_azienda)
            {
                
                    $azienda= $this->get_record('azienda',$recordid_azienda);
                    $fields['statolistacliente']="Uscita";
                    
                    $datalimite=date('Y-m-d',strtotime('-12 months'));
                    $sql="
                        SELECT data
                        FROM user_rapportidilavoro AS r
                        WHERE r.recordidazienda_='$recordid_azienda' AND r.data>='$datalimite'
                        ORDER BY DATA DESC
                        LIMIT 1
                        ";
                    $result= $this->select($sql);
                    if($result!=null)
                    {
                        $fields['ultimadatalistacliente']=$result[0]['data'];
                        $fields['statolistacliente']='OK';
                    }
                    $this->update_record('azienda', 1, $fields, "recordid_='$recordid_azienda'");
            }
            
            function custom3p_genera_listaclienti_azienda($recordid_azienda)
            {
                    $azienda= $this->get_record('azienda',$recordid_azienda);
                    $fields['cliente']=$azienda['ragionesociale'];
                    $fields['stato']="Ex cliente";
                    $fields['cqdata']=$azienda['datacontrattoquadro'];
                    $fields['ccl']=$azienda['ccl'];
                    
                    $datalimite=date('Y-m-d',strtotime('-12 months'));
                    $sql="
                        SELECT data
                        FROM user_rapportidilavoro AS r
                        WHERE r.recordidazienda_='$recordid_azienda' AND r.data>='$datalimite'
                        ORDER BY DATA DESC
                        LIMIT 1
                        ";
                    $result= $this->select($sql);
                    if($result!=null)
                    {
                        $fields['ultimadata']=$result[0]['data'];
                        $fields['stato']='Cliente';
                    }
                    $fields['id']= $this->generate_seriale('listaclienti', 'ID');
                    $this->insert_record('listaclienti', "1", $fields);
            }
            
            function crea_permessiutente()
            {
                $users= $this->db_get('sys_user','*');
                foreach ($users as $key => $user) {
                    $userid=$user['id'];
                    $tables=$this->db_get('sys_table');
                    foreach ($tables as $key => $table) {
                        $tableid=$table['id'];
                        $table_settings=$this->get_impostazioni_table_settings($tableid,$userid);
                        if($table_settings['hidden']=='true')
                        {
                            $visualizzare='No';
                        }
                        else
                        {
                            $visualizzare='Si';
                        }
                        $current_value=$this->db_get_value('user_permessiutente','id',"utente='$userid' AND archivio='$tableid' AND tipopermesso='visualizzare'");
                        if($current_value==null)
                        {
                            $fields['id']=$this->generate_seriale('permessiutente', 'id');
                            $fields['utente']=$userid;
                            $fields['archivio']=$tableid;
                            $fields['tipopermesso']='visualizzare';
                            $fields['valore']=$visualizzare;
                            $this->insert_record("permessiutente", 1, $fields);
                        }
                        
                        if($table_settings['edit']=='true')
                        {
                            $modificare='Si';
                        }
                        else
                        {
                            $modificare='No';
                        }
                        $current_value=$this->db_get_value('user_permessiutente','id',"utente='$userid' AND archivio='$tableid' AND tipopermesso='modificare'");
                        if($current_value==null)
                        {
                            $fields['id']=$this->generate_seriale('permessiutente', 'id');
                            $fields['utente']=$userid;
                            $fields['archivio']=$tableid;
                            $fields['tipopermesso']='modificare';
                            $fields['valore']=$visualizzare;
                            $this->insert_record("permessiutente", 1, $fields);
                        }
                        
                        if($table_settings['delete'])
                        {
                             $eliminare='Si';
                        }
                        else
                        {
                             $eliminare='No';
                        }
                        $current_value=$this->db_get_value('user_permessiutente','id',"utente='$userid' AND archivio='$tableid' AND tipopermesso='eliminare'");
                        if($current_value==null)
                        {
                            $fields['id']=$this->generate_seriale('permessiutente', 'id');
                            $fields['utente']=$userid;
                            $fields['archivio']=$tableid;
                            $fields['tipopermesso']='eliminare';
                            $fields['valore']=$visualizzare;
                            $this->insert_record("permessiutente", 1, $fields);
                        }                        
                    }
                }
            }
            
            function salva_custom_3p_jobdescription($post)
            {
                $userid= $this->get_userid();
                $updatefields=array();
                $jobdescription=$post['jobdescription'];
                //$jobdescription= str_replace("'", "''", $jobdescription);
                $updatefields['jobdescription']=$jobdescription;
                $note=$post['note'];
                //$note= str_replace("'", "''", $note);
                $updatefields['note']=$note;
                $recordid_richiestericercapersonale=$post['recordid_richiestericercapersonale'];
                
                $this->update_record('richiestericercapersonale', $userid, $updatefields, "recordid_='$recordid_richiestericercapersonale'");
                


            }

        
}


?>
