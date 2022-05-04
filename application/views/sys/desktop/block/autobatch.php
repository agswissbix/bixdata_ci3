<?php
$lista_code=$data['lista_code'];
$funzione=$data['funzione'];
$coda_precaricataid=$data['coda_precaricataid'];
$random=time().rand(0,100);
?>
<script type="text/javascript">
    var coda_precaricata='<?=$coda_precaricataid?>'
    $("#block_lista_code_<?=$random?>").ready(function(){
        var current_block=$("#block_lista_code_<?=$random?>");
        <?php
        if(true)
        {
        ?>
        if(coda_precaricata!='')
        {
            //load_coda($('#select_lista_code'),'<?=$funzione?>');
            $(current_block).find('#select_lista_code').val(coda_precaricata).trigger('change');
        }
        <?php
        }
        ?>
    });
 
</script>
<div id="block_lista_code_<?=$random?>" class="scheda scheda_code" data-schedaid="scheda_code">
    <div id="menu_scheda_campi" class="menu_mid ui-widget-header">
        CODE 
        <div class="clearboth"></div>
    </div>
    <div class="schedabody schedabody_menu_bottom">
        <select id="select_lista_code" class="lista_code select_lista_code" style="width: 100%;margin:auto;margin-top: 4px;" onchange="load_autobatch(this,'<?=$funzione?>');">Coda:
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

        <div id="files_coda_container" class="files_caricati schedabody" style="height: calc(100% - 10px);overflow-y: hidden">

        </div>
    </div>

</div>