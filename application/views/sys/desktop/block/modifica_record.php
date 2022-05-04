<?php
$tableid=$data['tableid'];
$recordid=$data['recordid'];
?>

<script type="text/javascript">
    $(document).ready(function(){
        $( "#tabs" ).tabs();
    });
</script>
<div class="develop">block-modifica_record</div>
<div class="menu_mid ui-widget-header">
            <div class="tooltip btn_icona btn_close" title="chiudi la scheda"  onclick="chiudi_scheda(this);" style="float: right;"></div> 
            <div class="tooltip btn_icona btn_pin" title="blocca la scheda"  onclick="set_pinned();" style="float: right;" ></div> 
            <!--<div class="tooltip btn_icona btn_save" title="salva la scheda"  onclick="salva_scheda('<?=$data['tableid']?>','<?=$data['recordid']?>');" style="float: right;"   ></div> -->
            <div class="tooltip btn_icona btn_delete" title="elimina record"  onclick="elimina_record(this,'<?=$data['tableid']?>','<?=$data['recordid']?>');" style="float: right;"   ></div> 
            <div class="tooltip btn_icona btn_edit" title="modifica record"  onclick="modifica_record(this,'<?=$data['tableid']?>','<?=$data['recordid']?>');"  style="float: right;"  ></div> 
            <div class="tooltip btn_icona btn_save" title="salva modifiche"  onclick="salva_modifiche_record(this,'<?= $tableid ?>','<?= $recordid ?>')"  style="float: right;"  ></div> 
            
                <?php if($data['tableid']=='CANDID'){?>
                    
                  <div class="btn_scritta menu_list_button">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','flash')">Profilo</a></li>
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','cifrato');">Profilo cifrato</a></li>
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','ws_lgl');">Profilo WS lgl</a></li>
                    </ul>
                  </div> 
                    

            <?php } ?>
            <div class="clearboth"></div>
            </div>
<div id="scheda_record_content" style="height: 97%;z-index: 1;position: relative;">


    <div id="dati_e_allegati" style="height: 97%;overflow: hidden;">
        <div id="tabs" style="overflow: hidden;height: 98%;">
            <ul >
                <li style="width: 48%;"><a href="#dati" style="display: block;width: 100%;">Dati</a></li>
                <li style="width: 48%;"><a href="#allegati" style="display: block;width: 100%;">Allegati</a></li>

            </ul>

            <div id="dati" style="margin:auto; width: 98%;padding: 0px;" >
                <?php
                echo $data['block']['block_modifica_record_dati'];
                ?> 

            </div>

            <div id="allegati" style="margin:auto; width: 98%;padding: 0px;">
                <?php
                echo $data['block']['block_modifica_record_allegati'];
                ?> 
            </div>

        </div> 


    </div>



</div>