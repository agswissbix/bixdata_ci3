<?php
$lastscans=$data['lastscans'];

?>
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function(){
            load_content('ajax_load_content_presenze_lezione');
        },5000);
    });
</script>
<div style="background-color: white;height: 100%;text-align: center;overflow-y: scroll" >
    <h3 style="margin: 5px;">Ultimi documenti</h3>
    <?php
    foreach ($lastscans as $key => $scan) {
    ?>
    <div style="width: calc(100% - 10px);margin-left: 5px;background-color: #f8f8f8;border: 1px solid #bcbcbc;margin-bottom: 5px;cursor: pointer" onclick="apri_scheda_record(this,'<?=$scan['tableid']?>','<?=$scan['recordid_']?>','popup','standard_allegati');">
        <div id="" class="allegato thumb allegato_pdf " style="float: left;z-index: 0;background-image: url('<?=$scan['thumbnail_url']?>')" data-selected="false" data-fileid="00000330" onclick="" > </div>
        <div style="float: left;text-align: left;margin: 5px;">
            <div><b>Data scansione:</b>  <?=$scan['datascan']?></div>
            <!--<div><b>Tipo:</b>  <?=$scan['tipodoc']?></div>-->
        </div>

        
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    
    
</div>