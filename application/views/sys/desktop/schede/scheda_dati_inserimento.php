<?php
$tableid=$data['tableid'];
$funzione=$data['funzione'];
$scheda_container=$data['scheda_container'];
$recordid=$data['recordid'];
?>
<script type="text/javascript">
$( "#scheda_dati_inserimento" ).ready(function(){
    var tablelabel_0=$( "#block_dati_labels_container" ).find('#tablelabel_0');
    console.info(tablelabel_0);
    $(tablelabel_0).click(); 
});
</script>

<div id="scheda_dati_inserimento" class="block_container block_fields scheda scheda_dati_inserimento" data-tableid="<?=$data['tableid']?>" data-funzione="<?=$funzione?>" data-scheda_container="<?=$scheda_container?>" data-schedaid="scheda_dati_inserimento" style="overflow: none;position: relative;">
    <div id="menu_scheda_campi" class="menu_mid menu_top ui-widget-header">
        <div style="float: left;font-weight: bold;margin-left: 10px;height: 100%">Inserimento <span style="color: black"><?=$tableid?></span></div>
        <div class="tooltip btn_icona btn_reset" style="float: right;margin-right: 5px;;" title="azzera i parametri di ricerca"  onclick="reload_fields(this,'<?=$tableid?>','<?=$funzione?>');"></div> 
        <br/><br/>
        <div class="clearboth"></div>
    </div>
    <div class="schedabody schedabody_with_menu_bottom">
    <div class="fields_container fields_container_<?=$funzione?>  block_dati_labels_container" >
        <?php
        echo $data['block']['block_dati_labels'];
        ?>
    </div>
    <div class="clearboth"></div>
    </div>
    <div class="menu_big menu_bottom">
                <?php
                //CUSTOM WORK&WORK
                if($data['tableid']=='CONTRA')
                {
                ?>
                <div id="contra_check" style="float: left;">
                    <input type="checkbox" name="custom[contra][destinatario][azienda]" value="Azienda"> Azienda
                    <input type="checkbox" name="custom[contra][destinatario][dipendente]" value="Dipendente">  Dipendente
                    <input type="radio" name="custom[contra][wwws]" checked="checked" value="WW"> WW
                    <input type="radio" name="custom[contra][wwws]" value="WS">  WS
                </div>
                <?php
                }
                ?>
             
                <div id="btnNuovo" class="btn_scritta" onclick="inserisci(this,'<?=$data['tableid']?>','nuovo')" style="width: 100px;">Salva e Nuovo</div>
                <div id="btnInserisci" class="btn_scritta" onclick="inserisci(this,'<?=$data['tableid']?>','continua')" style="width: 100px;">Salva</div>
                <div class="clearboth"></div>
    </div>
    
    <div id="block_riepilogo" class="blocco" style="height: 95%;display: none;float: left;">
        <h3> Riepilogo</h3>
        <button style="float: right;width: 20px;height: 20px;opacity: 0;" onclick="$('#query').show()"></button>
        <div id="riepilogo" class="riepilogo" style=" overflow-y: scroll; height: 80%;">
            <form id="form_riepilogo" class="form_riepilogo" >
                <input id="file_allegato_hidden" class="file_allegato" type="text" style="display: none" value="" >
            </form> 
        </div>
    </div>
</div>
    
