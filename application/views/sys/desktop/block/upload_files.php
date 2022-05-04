<div class="develop">block-upload_files</div>
<?php
$funzione=$data['funzione'];
$popuplvl=$data['popuplvl'];
?>
<script type="text/javascript">
    $(".block_upload_files").ready(function(){
        <?php
        if(($funzione=='inserimento')&&($pages_show_autobatch=='si'))
        {
        ?>
            //load_coda($('#select_lista_code'),'<?=$funzione?>');
                $('.fileUploadCoda').click();
            
            
        
        <?php
        }
        ?>
    });
    
</script>
<div class="block_upload_files menu_upload_files" >
    <form id="form_upload" enctype="multipart/form-data" method="post" target="upload_target" action="<?php echo site_url('sys_viewcontroller/uploadfile/'.$popuplvl) ?>">
        <div class="fileUpload btn_scritta" style="width: calc(100% - 60px);margin: auto;margin-bottom: 5px;height: 20px;line-height: 20px;">
            CARICA FILE
            <input  type="file" name="allegati[]" id="file_toupload" class="upload" onchange="UploadFile(this);" multiple="multiple" style="width: 100%;" >
        </div>

        <div class="fileUpload fileUploadCoda btn_scritta" style="margin:auto auto 5px;width:calc(100% - 60px);float: left;" onclick="apri_blocco_autobatch(this)">
            Coda
        </div>
        <div class="clearboth"></div>
    </form>
    <div id="form_upload_loading" style="color: #467bbd;display: none;">Caricando...</div>
    <div id="files_sys_batch_temp" style="display: none;height: 100px;"></div>
</div>