<?php
$block = $data['block'];
$recordid=$data['recordid'];
$tableid=$data['tableid'];
?>
<div class="develop">block-modifica_record_dati</div>
<div id="modifica" class=""  style="height: 95%;width: 100%;float: left;"> 

    <div class="block_container"> 

    <div id="scheda_campi_content" style=" overflow-y: scroll; height: 100%;">
        <?php
        echo $block['block_fields'];
        ?>
    </div>

    </div>
</div>
        