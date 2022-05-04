<?php
$files=$data['files'];
$funzione=$data['funzione'];
$originefiles=$data['originefiles'];
$filetype_class='';
$thumb_background='';
$idcoda=$data['idcoda'];
$recordid=$data['recordid'];
$tableid=$data['tableid'];
$project_url=base_url();
$project_url=  str_replace("JDocWeb/", "", $project_url);
$project_url=  str_replace("jdocweb/", "", $project_url);
$project_url=  str_replace("JDocWeb_test/", "", $project_url);
$project_url=  str_replace("JDocWeb_update/", "", $project_url);
$host_url=  domain_url();
$site_url=  site_url();
$base_url= base_url();
$JDocServer_basepath=  substr($base_url,0,strrpos($base_url, "/"));
$JDocServer_basepath=  substr($JDocServer_basepath,0,strrpos($JDocServer_basepath, "/")+1);
?>
<style>
  #files_container .file_container { 
      
      float: left; 
       
  }
  </style>
<script type="text/javascript">
    
    
    $("#block_lista_files_<?=$tableid?>_<?=$recordid?>_<?=$idcoda?>").ready(function(){
    $( ".connectedSortable" ).sortable({
                      connectWith: ".connectedSortable",
                      update: function( event, ui ) {
                          if (this === ui.item.parent()[0]) {
                          update_connectedSortable(event, ui);
                      }
                      }
                    }).disableSelection();
        
        
        
                //var file_container_width=$("#files_container").width();
                //$('#files_container').find('li').width(file_container_width*0.8);
    /*$('.tooltip').tooltip(
        {
          track: true
        }
    );*/
        
        $('.allegato_0').click();  
        $('.allegato_0').closest('.file_container').find('.file_checkbox').click();
        var aspectratio=('<?=$pages_thumbnail_aspectratio?>');
        var aspectratio_array=aspectratio.split(':');
        var aspectratio1=aspectratio_array[0];
        var aspectratio2=aspectratio_array[1];
        var width=$('.files_container').width();
        $('.file_container').each(function(){
            
            var width=$(this).width();
            
            <?php
            $height='(width * parseInt(aspectratio2)) / parseInt(aspectratio1)';
            $filecontainer_type='thumbnail';
            if($filecontainer_type=='thumbnail')
            {
                $height='(width * parseInt(aspectratio2)) / parseInt(aspectratio1)';
            }
            if($filecontainer_type=='details')
            {
               $height="'60px'"; 
            }
            ?>
            var height=<?=$height?>;
            $(this).height(height);
        })
        
        
        
    });
    
    
</script>
<div id="block_lista_files_<?=$tableid?>_<?=$recordid?>_<?=$idcoda?>" class="block block_lista_files" style="width: 100%;height: 100%" >
<?php
//echo $funzione;
//echo $originefiles;
if($idcoda!='sys_batch_temp')
{
?>
    
<form id='form_riepilogo' class="form_riepilogo" >

    
<div id="files_container" class="connectedSortable" style="height: 90%;width: 100%;overflow-x: hidden;overflow-y: scroll;">
<?php
    }
?>    
    
     <!--<input name='originefiles' id='originefiles' type='text' value='<?=$originefiles?>' style='display:none'></input>-->
     <?php
        if(($originefiles=='coda')&&(($funzione=='inserimento')||($funzione=='modifica')))
        {
        ?>
            <!--<div class="btn_scritta" onclick="allega_tutti_file(this);">Allega tutti</div>-->
    <?php
        }
    ?>
            <!--<div class="clearboth"></div>-->
    <?php
    if(($originefiles=='allegati')&&($funzione=='scheda'))
    {
    ?>
        <!--<div class="tooltip" title="Seleziona tutti" style="float: left;margin: 2px;">
            <input type="checkbox" onclick="seleziona_tutti(this)" style="float: left;">
        </div>
        <div class="tooltip btn_fa fa fa-print" title="stampa selezionati"  onclick="stampa_selezionati(this,'<?=$tableid?>','<?=$recordid?>')"  style="float: right;"  ></div>
            <div class="clearboth"></div>-->
    <?php
    }
    ?>
            
    <?php
    $index=0;
    $category='';
    foreach ($files as $key => $file) {
        
        
        
        if($originefiles=='coda')
        {
            $path=$file['batchid'];
            if($file['batchid']=='sys_batch_temp')
            {
                $path_ajax='batch/'.$file['batchid'].'/'.$file['creatorid'].'/';
                $file_web_path=$host_url."JDocServer/batch/".$file['batchid']."/".$file['creatorid']."/".$file['filename'].".".$file['fileext'];
            }
            else
            {
                $path_ajax='batch/'.$path.'/';
                $file_web_path=$host_url."JDocServer/batch/".$path."/".$file['filename'].".".$file['fileext'];
            }
            
            $fileid=$file['fileid'];
            
            $filename=$file['filename'];
            $filename=  str_replace("%20", "", $filename);
            
            //$nome_allegato=$file['description'];
            //$nome_allegato=  str_replace("%20", " ", $nome_allegato);
            $originalname=$file['description'];
            $fileext=$file['fileext'];

            //$filedescription=$file['description'];

            $thumbnail=str_replace("\\", "/", $path ).'/'.$filename.'_thumbnail.jpg';
            $thumbnail_relative_path="../JDocServer/batch/".$thumbnail;
            $thumbnail_web_path=$host_url."JDocServer/batch/".$thumbnail;
            
        }
        
        if($originefiles=='autobatch')
        {
            $path=$file['batchid'];
            $path_ajax='autobatch/'.$file['batchid'].'/';
            $fileid=$file['fileid'];
            $filename=$file['filename'];
            $filename=  str_replace("%20", "", $filename);
            $originalname=$file['description'];
            $fileext=$file['fileext'];
            $thumbnail=str_replace("\\", "/", $path ).'/'.$filename.'_thumbnail.jpg';
            $thumbnail_relative_path="../JDocServer/autobatch/".$thumbnail;
            $thumbnail_web_path=$host_url."JDocServer/autobatch/".$thumbnail;
            $file_web_path=$host_url."JDocServer/autobatch/".$file['batchid']."/".$file['filename'].".".$file['fileext'];

        }
        
        if($originefiles=='allegati')
        {
            $category=$file['category'];
            $path=$file['path_'];
            $path_ajax=$file['path_'];//str_replace("\\", "-", $file['path_'] );
            $path_ajax= str_replace('\\', '/', $path_ajax);
            $file_web_path=$host_url."JDocServer/".$file['path_']."/".$file['filename_'].".".$file['extension_'];
            $fileid=$file['filename_']; 
            
            $filename=$file['filename_'];
            $filename=  str_replace("%20", "", $filename);
            
            $originalname=$file['original_name'];
            /*if(($original_name==null)||($original_name==''))
            {
                $filedescription=$file['filename_'];
                $nome_allegato=$file['filename_']; 
            }
            else
            {
                $filedescription=$original_name;
                $nome_allegato=  substr($original_name, 0,12).'..';
            }*/
            $JDocServer_basepath=  substr($base_url,0,strrpos($base_url, "/"));
            $JDocServer_basepath=  substr($JDocServer_basepath,0,strrpos($JDocServer_basepath, "/")+1);
            $fileext=$file['extension_'];
            
            $thumbnail=str_replace("\\", "/", $path ).'/'.$filename.'_thumbnail.jpg';
            $pos=  strpos($path, 'Appl');
            
            if($pos === false) {      
                $thumbnail_relative_path="../JDocServer/".$thumbnail;
                $thumbnail_web_path=$JDocServer_basepath."JDocServer/".$thumbnail;
            }
            else
            {
                $thumbnail_relative_path="../Neuteck/docusys/".$thumbnail;
                $thumbnail_web_path=$JDocServer_basepath."Neuteck/docusys/".$thumbnail;
            }

        }
        $file_web_path=str_replace("\\", "/", $file_web_path );
        if($originalname=='')
        {
            $originalname=$filename;
        }
        $filecontainer_title=$originalname;
        if($filecontainer_type=='details')
        {
            $filecontainer_title='';
        }
        $fileext=  str_replace(" ", "", $fileext);
        $fileext=  str_replace("%20", "", $fileext);
        $fileext=  strtolower($fileext);
        if($fileext=='pdf')
        {
            $filetype_class='allegato_pdf';
        }
        if($fileext=='tif')
        {
            $filetype_class='allegato_tiff';
        }
        if($fileext=='xls')
        {
            $filetype_class='allegato_xls';
        }
        if($fileext=='jpg')
        {
            $filetype_class='allegato_jpg';
        }
        if($fileext=='png')
        {
            $filetype_class='allegato_png';
        }
        if(($fileext=='doc')||($fileext=='docx'))
        {
            $filetype_class='allegato_doc';
        }
        if(($fileext=='xls')||($fileext=='xlsx'))
        {
            $filetype_class='allegato_xls';
        }
        if($fileext=='dwg')
        {
            $filetype_class='allegato_dwg';
        }
        if($fileext=='mp4')
        {
            $filetype_class='allegato_mp4';
        }
            
            if(file_exists($thumbnail_relative_path))
            {
                $thumb_background="background-image: url('$thumbnail_web_path?v=".time()."');";
            }
            else
            {
               if(($fileext=='jpg')||($fileext=='png'))
               {
                   $thumb_background="background-image: url('$file_web_path?v=".time()."');";
               }
               else
               {
                    $thumb_background='';
               }
               
            }
            
            
        ?>
         
        <div class="file_container" title="<?=$originalname?>"  data-delete='false' style="border: 1px solid #dedede;float: left;margin: 0px;width: 130px; " data-fileid="<?=$fileid?>">
        <div class="menu_small file_menu" style="height: 16px;">
                <?php
                if($originefiles=='allegati')
                {
                ?>
            <div class="" style="float: left;padding-right: 2px;">
                        <input type="checkbox" class='checkbox file_checkbox' name="files[checked][<?=$fileid?>]" value="<?=$fileid?>">
                    </div>
                <?php
                }
                ?>

                <div class="file_category" style="height: 20px;width: calc(100% - 45px);overflow: hidden;float: left;line-height: 20px;">
                    <?=$filecontainer_title?>
                </div>
                
                <?php
                if(($funzione=='gestione_code')||($funzione=='modifica')||($funzione=='inserimento'))
                {
                ?>
                    <div class="file_menu_btn tooltip btn_fa fas fa-trash-alt" title="elimina allegato"  onclick="elimina_file(this)" style="float: right;margin-right: 0px;margin-left: 0px;color: red"   ></div> 

                <?php
                }
                if((($funzione=='inserimento')||($funzione=='modifica'))&&(($originefiles=='coda')||($originefiles=='autobatch')))
                {
                    /*
                ?>
                    <div style="display: inline-block;float: left;" class="left_icon btn_icona tooltip" title="allega"  onclick="allega_file(this);"></div>
                    <div class="tooltip btn_fa fa fa-times" title="Non allegare"  onclick="nonallegare_file(this);" style="float: right;margin-right: 0px;"   ></div> 
                <?php
                     * */
                }
                ?>

                <div class="clearboth"></div>
            </div>
            
            <?php
            if($filecontainer_type=='thumbnail')
            {
            ?>
            <div id="<?php echo $path_ajax.'-'.$filename.'-'.$fileext; ?>" class="allegato thumb <?=$filetype_class?> allegato_<?=$key?>"  onclick="apri_allegato(this,'<?=$recordid?>', '<?=$path_ajax?>', '<?=$filename?>', '<?=$fileext?>')" ondblclick="popup_allegato(this,'<?=$recordid?>', '<?=$path_ajax?>', '<?=$filename?>', '<?=$fileext?>')" data-fileid="<?=$fileid?>" data-selected="false"  style="height: calc(100% - 25px);position: relative; z-index: 0;<?=$thumb_background?>"  data-category="<?=$category?>"  >
                <?php
                if($thumb_background!='')
                {
                ?>
                    <div class="minithumb <?=$filetype_class?>" style="height: 20px;width: 16px;position: absolute;top: 0px;left: 0px;z-index: 10;"></div>
                <?php
                }
                ?>
                    <div class="file_categories" style="width: 100%;position: absolute;bottom: 0px;left: 0px;z-index: 10;background-color: rgba(250, 250, 250, 0.6);">
                        <?php
                        $category_array=  explode("|;|", $category);
                        foreach ($category_array as $key => $single_category) {
                        ?>
                        <div style="font-size: 12px;line-height: 14px;"><?=$single_category?></div>
                        <?php
                        }
                        ?>
                    </div>
                    
            </div> 
            <?php
            }
            ?>
            <?php
            if($filecontainer_type=='details')
            {
            ?>
            <div id="<?php echo $path_ajax.'-'.$filename.'-'.$fileext; ?>" class="allegato thumb allegato_<?=$key?>"  onclick="apri_allegato(this,'<?=$recordid?>', '<?=$path_ajax?>', '<?=$filename?>', '<?=$fileext?>')" ondblclick="popup_allegato(this,'<?=$recordid?>', '<?=$path_ajax?>', '<?=$filename?>', '<?=$fileext?>')" data-fileid="<?=$fileid?>" data-selected="false"  style="height: calc(100% - 25px);position: relative; z-index: 0;overflow: hidden"  data-category="<?=$category?>"  >
                <?=$originalname?>
            </div>
            <?php
            }
            ?>
            
            <!--<div class="originalname" style="height: 20px;width: 100%;overflow: hidden;margin-left: 2px;height: 10%;">
                <?=$originalname?>
           </div>-->
            
            
            <input id="<?=$filename?>" class="file fileid" type="text" name="files[update][<?=$fileid?>][fileid]" style="display: none" value="<?=$fileid?>" >
            <input id="<?=$filename?>" class="file fileparam" type="text" name="files[update][<?=$fileid?>][fileparam]" style="display: none" value="" >
            <input id="<?=$filename?>" class="file fileorigine" type="text" name="files[update][<?=$fileid?>][fileorigine]" style="display: none" value="<?=$originefiles?>" >
            <input id="<?=$filename?>" class="file fileindex" type="text" name="files[update][<?=$fileid?>][fileindex]" style="display: none" value="<?=$index?>" >
            <input id="<?=$filename?>" class="file filecategory" type="text" name="files[update][<?=$fileid?>][filecategory]" style="display: none" value="" >
            <div class="clearboth"></div>
        
            </div>
    <?php   
    $index++;
    }
    ?>
 
<?php
if($idcoda!='sys_batch_temp')
{
?>        
</div>
    </form> 
<?php
if($funzione=='gestione_code')
    {
?>
<div class="ui-widget-header menu_big">
    <div id="btnCerca" class="btn_scritta" onclick="salva_modifiche_lista_files(this)" style="float: right;width: 100px;">Salva modifiche</div>
            <div class="clearboth"></div>
</div>
<?php
    }
    ?>
<?php
}
?>

    

</div>
