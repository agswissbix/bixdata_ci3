<?php
$lista_code=$data['lista_code'];
$funzione=$data['funzione'];
$coda_precaricataid=$data['coda_precaricataid'];
?>
<script type="text/javascript">
    var coda_precaricata='<?=$coda_precaricataid?>'
    $("#block_lista_code").ready(function(){
        if(coda_precaricata!='')
        {
        load_coda($('#select_lista_code'),'gestione_code');
        }
    });
 
</script>
<div id="block_lista_code" class="scheda scheda_code" data-schedaid="scheda_code">
    <div id="menu_scheda_campi" class="menu_mid ui-widget-header">
        CODE
         <?php
        if($funzione=='gestione_code')
        {
        ?> 
            <div class="tooltip btn_icona btn_save" title="salva modifiche"  onclick="salva_modifiche_coda(this)"  style="float: right;"  ></div> 
        <?php
        }
        ?>
        <div class="clearboth"></div>
    </div>
    <div class="schedabody schedabody_menu_bottom">
        <select id="select_lista_code" class="lista_code select_lista_code" style="width: 100%;margin:auto;margin-top: 4px;" onchange="load_coda(this,'<?=$funzione?>');">Coda:
            <option id="" title="" >Selezionare</option>
        <?php
        
        foreach ($lista_code as $coda) {
            $selected='';
            if($coda['id']==$coda_precaricataid)
            {
                $selected='selected';
            }
            ?>
            <option id="<?=$coda['id']?>" title="<?=$coda['description']?>" <?=$selected?>><?=$coda['id']?></option>
        <?php
        }
        ?>
        </select> 
        <?php
        if($funzione=='gestione_code')
        {
            echo $data['block_upload_files'];
        }
        ?>
        <div id="files_coda_container" class="files_caricati schedabody" style="height: calc(100% - 55px);overflow-y: hidden">

        </div>
    </div>
    <?php
        if($funzione=='gestione_code')
        {
        ?> 
    <div class="menu_big menu_bottom">
        <div id="btnSalva" class="btn_scritta" onclick="salva_modifiche_coda(this)" style="width: 100px;">Salva</div>
        <div class="clearboth"></div>
    </div>
    <?php
        }
    ?>
</div>