<?php
$block_lista_files=$data['block']['lista_files'];
$funzione=$data['funzione'];
$tableid=$data['tableid'];
$recordid=$data['recordid'];
$funzione='inserimento';
?>
<script type="text/javascript">
$(document).ready(function(i){
    $('#btn_scarica_allegati_container').hover(function (){
        $(this).find('#menu_scarica_allegati').show();
    })
    
    $('#btn_scarica_allegati_container').mouseleave(function (){
        $(this).find('#menu_scarica_allegati').hide();
    })
});
</script>
<div id="stampa_selezionati_popup" class="popup">
    <!--<div class="btn_scritta" onclick="conferma_stampa_selezionati(this)">Conferma stampa</div>-->
    <div style="float: right;height: 100%;width: 100%">
        <iframe id="MergedPDFtoPrint" src="" style="height: 100%;width: 100%" ></iframe>
    </div>
</div>
<div id="block_allegati" class="block_allegati scheda  scheda_allegati" data-tableid="<?= $data['tableid'] ?>" data-recordid="<?=$data['recordid']?>" data-funzione="<?=$funzione?>" data-schedaid="scheda_allegati">
    <div id="menu_scheda_campi" class="menu_mid ui-widget-header" style="">
        <div id="btn_scarica_allegati_container" class="btn_scritta menu_list_button"  style="float: right;">
                <div id="btn_scarica_allegati" class="btn_fa fa fa-download tooltip"  style="float: right" onclick=""  title="Scarica allegati selezionati"></div>
                <div id="menu_scarica_allegati" class="menu_list" style="left: -90px;width: 150px;border: 1px solid #dedede;background-color: white;">
                    <div onclick="scarica_allegati_selezionati(this,'<?=$userid?>','originale')">Scarica originali</div>
                    <div onclick="scarica_allegati_selezionati(this,'<?=$userid?>','preview')">Scarica alleggeriti</div>
                </div>
        </div>
        <div style="float: left;"><input type="checkbox" onclick="seleziona_tutti_allegati(this)"></div>
        <div class="btn_fa fa fa-tags" onclick="$(this).next().toggle()" style="color: black;cursor: pointer;float: right"></div> 
        <div class='check_category' style="width: 300px;position: absolute;right: 0px;top: 28px;background-color: white;border: 1px solid #dedede;z-index: 100;display: none;padding: 5px;overflow: visible;">
            <?php
            foreach ($categories as $key => $category) 
            {

            ?>
                <div>
                    <input class="checkbox" type="checkbox" name="category[]" value="<?=$category['cat_id']?>" ><span style="color: black;margin-left: 5px;"><?=$category['cat_description']?> (<?=$category['cat_counter']?>)</span>
                </div>
            <?php
            }
            ?>
            <br/>
            <div class="btn_scritta" style="color: black" onclick="set_pages_category(this)">Applica</div>
            <?php
            if($userid==1)
            {
            ?>
            <div class="tooltip btn_fa fa fa-plus" title="Aggiungi categoria all'elenco" onclick="add_page_category(this)" style="float: right"></div>
            <?php
            }
            ?>
        </div>
        <div class="clearboth"></div>
    </div>
    <?php
    if(($funzione=='modifica')||($funzione=='inserimento'))
    {
        echo $data['block_upload_files'];
    }
    ?>
    
    <div id="block_lista_files_container" class="container schedabody" style="height: calc(100% - 50px)">
        <div class="block_container">
            <?php
            echo $block_lista_files;
            ?>
        </div>
    </div>
</div>