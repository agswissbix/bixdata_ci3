<?php
$tableid=$data['tableid'];
$recordid=$data['recordid'];
$funzione=$data['funzione'];
$mode=$data['mode'];
$layout_scheda= $data['settings']['layout_scheda'];
$popuplvl=$data['popuplvl'];
//custom demo svicom
if(($layout_scheda=='standard_dati')&&($tableid=='contratti'))
{
    $layout_scheda='standard_allegati';
}
$target=$data['target'];
$navigatorField=$data['navigatorField'];
$id_scheda_record="scheda_record_$recordid"."_".$layout_scheda."_".time();
?>
<script>
    var fissi_height;
    var lista_allegati_height;
    var dati_e_allegati_height;
    var allegati_height;
    var allegati_height2;
    var allegato_height;
    var allegato_height2;
    
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_profilo'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_profilo'); ?>/";
    
    
    

    function salva_scheda(tableid,recordid){
        userid="<?php echo $this->session->userdata('idutente') ?>";
        url="<?php echo site_url('sys_viewcontroller/salvascheda/') ?>" + "/" + tableid + "/" + recordid + "/" + userid;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function () {
                        alert("Scheda salvata correttamente!");
                    },
                    error : function () {
                        alert("Si è verificato un errore ajax");
                    }
                });
    }
    
    
    

function stampa_profilo(recordid,tipo)
{
    $.ajax({
        url: urlprint + recordid + '/' + tipo,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

$('#<?=$id_scheda_record?>').ready(function(){
        <?php
        if(($layout_scheda=='standard_dati')||($layout_scheda=='standard_allegati'))
        {
        ?>
            $( "#<?=$id_scheda_record?>" ).find('#tabs').tabs({
            <?php
            if($layout_scheda=='standard_allegati')
            {
            ?>
                active:1
            <?php
            }
            if($layout_scheda=='standard_dati')
            {
            ?>
                active:0
            <?php
            }
            ?>
            });
        <?php
        }
        ?>
        
        <?php
        if($layout_scheda=='allargata')
        {
        ?>
                $( "#scheda_record_<?=$data['recordid']?>" ).css('width',scheda_record_container_width*2);
        <?php
        }
        ?>

    
    /*$(".menu_btn_container").hover(function(){
       $(this).find('.menu_list').show(); 
        },
        function(){
         $(this).find('.menu_list').hide();
        })*/
    setTimeout(function () {
    var schedabody=$('#<?=$id_scheda_record?>').find('.schedabody');
    var schedabody_height=$(schedabody).height();
    var dati_e_allegati=$(schedabody).find('.dati_e_allegati');
    campi_fissi_height=$('#<?=$id_scheda_record?>').find('#campi_fissi').height();
    dati_e_allegati_height=schedabody_height-campi_fissi_height-20;
    $(dati_e_allegati).height(dati_e_allegati_height);    
    dati_e_allegati_height2=schedabody_height-20;
    }, 1000);
    
    var block_scheda_container=$('#<?=$id_scheda_record?>').closest('.scheda_container');
    var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
    var origine=$(block_scheda_container).data('origine');
    var origine_id=$(block_scheda_container).data('origine_id');
    if(origine=='linked_table')
    {
        var tables_container=$('#'+origine_id);
        var origine_block_dati_labels=$(tables_container).closest('.block_dati_labels');
        var origine_recordid=$(origine_block_dati_labels).data('recordid');
        var origine_tableid=$(origine_block_dati_labels).data('tableid');
        var master_label_table_=$(block_dati_labels).find('#label_table_'+origine_tableid);
        $(master_label_table_).hide();
    }


});

$( ".menu_list" ).menu();
$( ".menu_list_button" ).hover(
            function() {
              $(this).find('.menu_list').show();
            }, function() {
              $(this).find('.menu_list').hide();
            }
          );

</script>

<div id="<?=$id_scheda_record?>" class="block_container scheda_record scheda scheda_record" style="position: relative;overflow: hidden" data-tableid="<?=$tableid?>" data-recordid="<?=$recordid?>" data-schedaid="scheda_record" data-navigatorfield="<?=$navigatorField?>" data-layout="<?=$layout_scheda?>" data-target="<?=$target?>" data-popuplvl="<?=$popuplvl?>" data-pinned="false">
<div class="menu_mid menu_top ui-widget-header">
            <div class="btn_custom btn_open_popup tooltip" title="apri scheda" onclick="apri_scheda_record(this,'<?=$tableid?>','<?=$recordid?>','popup','allargata')" style="float: left;" ><div class="open_popup"></div></div>
            <div style="float: left;font-weight: bold;margin-left: 10px;"><span style="color: black"><?=$data['tableid']?></span></div>
            <div class="tooltip btn_fa fa fa-times  " title="Chiudi la scheda"  onclick="chiudi_scheda(this);" style="float: right;"></div> 
            <div class="tooltip btn_fa fa fa-thumb-tack  " title="Blocca la scheda"  onclick="set_pinned(this);" style="float: right;"></div> 
            <!--<div class="tooltip btn_icona btn_pin" title="blocca la scheda"  onclick="set_pinned();" style="float: right;" ></div> -->
            <!--<div class="tooltip btn_icona btn_save" title="salva la scheda"  onclick="salva_scheda('<?=$data['tableid']?>','<?=$data['recordid']?>');" style="float: right;"   ></div> -->
            <div class="tooltip btn_fa fa fa-trash-o" title="Elimina record"  onclick="elimina_record(this,'<?=$data['tableid']?>','<?=$data['recordid']?>');" style="float: right;"   ></div> 
            <!-- <div class="tooltip btn_icona btn_edit" title="modifica record"  onclick="modifica_record(this,'<?=$data['tableid']?>','<?=$data['recordid']?>');"  style="float: right;"  ></div> -->
            <!--<div class="tooltip btn_icona btn_save" title="salva modifiche"  onclick="salva_modifiche_record(this,'<?= $data['tableid'] ?>','<?=$data['recordid']?>')"  style="float: right;"  ></div> -->
            
            <div class="btn_scritta menu_list_button" style="float: right;">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a onclick="prestampa_scheda_record_completa('<?=$tableid?>','<?=$data['recordid']?>');">Stampa scheda</a></li>
                        <?php            
                        if(($data['settings']['cliente_id']=='Work&Work')&&($tableid=='CANDID'))
                        {
                            ?>
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','flash')">Profilo</a></li>
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','cifrato');">Profilo cifrato</a></li>
                        <li><a onclick="stampa_profilo('<?=$data['recordid']?>','ws_lgl');">Profilo WS lgl</a></li>
                        <?php
                        }
                        ?>
                    </ul>
            </div>
            <?php 
            //custom Work&Work
            if(($data['settings']['cliente_id']=='Work&Work')&&($tableid=='CANDID'))
            {
                ?>
                <div class="btn_scritta menu_list_button" style="float: right;">
                    OnlineCV
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a class="btn_loadToJDocOnlineCV" onclick="loadToJDocOnlineCV(this,'<?=$tableid?>','<?=$recordid?>')">Carica Online<input id="allfields" type="hidden" value="" name="allfields"></input></a></li>
                        <li><a onclick="valida_tutto_record(this,'<?=$tableid?>','<?=$recordid?>')">Valida tutto</a></li>
                    </ul>
                </div> 
            <?php 
            } 
            //fine custom Work&Work
            ?>
            
            
            <?php 
            //custom DemoImmobiliare
            if($data['settings']['cliente_id']=='DemoImmobiliare')
            {
                ?>
                    
                  <div class="btn_scritta menu_list_button">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a href="<?=  base_url("prospetto.doc")?>">Prospetto</a></li>
                    </ul>
                  </div> 
            <?php 
            } 
            //fine custom demo immobiliare
            ?>
            
            <?php 
            //custom VIS
            if($data['settings']['cliente_id']=='VIS')
            {
            if($data['settings']['tableid']=='contrattimandato'){
            ?>
            
                  <div class="btn_scritta menu_list_button">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a onclick="stampa_vis_contratto('<?=$data['recordid']?>');">Tutti i documenti</a></li>
                        <li><a onclick="stampa_vis_contratto('<?=$data['recordid']?>');">Contratto di mandato</a></li>
                        <li><a onclick="stampa_vis_contratto('<?=$data['recordid']?>');">Determinazione avente diritto economico </a></li>
                        <li><a onclick="stampa_vis_contratto('<?=$data['recordid']?>');">Opting out</a></li>
                        <li><a onclick="stampa_vis_contratto('<?=$data['recordid']?>');">Manleva</a></li>
                        
                    </ul>
                  </div> 
            <?php 
                }
                if($data['settings']['tableid']=='profilirischio'){
                ?>
                <div class="btn_scritta menu_list_button">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a onclick="stampa_vis_profilorischio('<?=$data['recordid']?>');">Profilo di rischio</a></li>
                    </ul>
                  </div> 
                <?php
                }
            } 
            //fine custom VIS
            ?>
            
            <div class="clearboth"></div>
            </div>
<div id="scheda_record_content" class="schedabody" style="z-index: 1;overflow: hidden;">
    <form id="form_scheda_record" style="height: 100px;overflow: scroll;width: 90%;border: 1px solid red;display: none;">
        
    </form>
    

        <?php
        if(($layout_scheda=='standard_dati')||($layout_scheda=='standard_allegati'))
        {
        ?>
        <div id="campi_fissi" class="block_container" style="overflow: hidden;">
            <div id="dettaglio_record_fissi" >
                <?php
                echo $data['block']['block_fissi'];
                ?> 
            </div>
        </div>
        <div id="tabs" class="tabs dati_e_allegati" style="overflow: hidden;border: 0px !important">
            <ul>
                <li style="width: 48%;"><a href="#dati" style="display: block;width: 100%;font-weight: bold;">Dati</a></li>
                <?php
                if($data['numfiles']>0)
                {
                ?>
                    <li style="width: 48%;"><a href="#allegati" style="display: block;width: 100%;font-weight: bold;">Allegati (<?=$data['numfiles']?>)</a></li>
                <?php
                }
                else
                {
                ?>
                    <li style="width: 48%;"><a href="#allegati" style="display: block;width: 100%;color: #9f9f9f;font-weight: bold;">Allegati</a></li>
                <?php
                }
                ?>
            </ul>
            
                <div id="dati" class="block_dati_labels_container">
                        <?php
                        echo $data['block']['block_dati_labels'];
                        ?> 
                </div>
            
            <div id="gestione_allegati" class="allegati gestione_allegati" style="height: calc(100% - 20px);">
                <div class="scheda_container" style="float: left;width: 130px;">
                    <?php
                        echo $data['block']['allegati'];
                        ?> 
                </div>    
                <div id="code" class="scheda_code_container scheda_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_code'];
                    ?> 
                </div>
                <!-- scheda  autobatch -->
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_autobatch'];
                    ?>
                </div>
                <div id="visualizzatore" class="scheda_container scheda_container_visualizzatore" style="width: calc(100% - 170px);overflow: hidden;float: left;">
                    <?php
                    echo $data['block']['block_visualizzatore'];
                    ?> 
                </div>
                
            </div>

        </div> 
    <?php
    }
    ?>
    
    <?php
    if($layout_scheda=='allargata')
    {
    ?>
    <div style="width: 50%;float: left;">
        <div id="campi_fissi" class="block_container" style="overflow: hidden;width: 100%">
                <div id="dettaglio_record_fissi" >
                    <?php
                    echo $data['block']['block_fissi'];
                    ?> 
                </div>
        </div>
        <div id="dati" class="block_dati_labels_container scheda_container" style="width: 100%;float: left;">
                <?php
                echo $data['block']['block_dati_labels'];
                ?> 
        </div>
    </div>
    <div id="gestione_allegati" class="allegati gestione_allegati" style="width: 50%;height: 100%;float: left">
                <div class="scheda_container" style="float: left;width: 130px;">
                    <?php
                        echo $data['block']['allegati'];
                        ?> 
                </div>    
                <div id="code" class="scheda_code_container scheda_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_code'];
                    ?> 
                </div>
                <!-- scheda  autobatch -->
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_autobatch'];
                    ?>
                </div>
                <div id="visualizzatore" class="scheda_container scheda_container_visualizzatore" style="width: calc(100% - 170px);overflow: hidden;float: left;">
                    <?php
                    echo $data['block']['block_visualizzatore'];
                    ?> 
                </div>
                
    </div>
    <?php
    }
    ?>
    
    <?php
    if($layout_scheda=='split')
    {
    ?>
    <div id="campi_fissi" class="block_container" style="overflow: hidden;">
            <div id="dettaglio_record_fissi" >
                <?php
                echo $data['block']['block_fissi'];
                ?> 
            </div>
        </div>
    <div style="width: 50%;float: left;">
        <div id="dati" class="block_dati_labels_container scheda_container" style="width: 100%;float: left;">
                <?php
                echo $data['block']['block_dati_labels'];
                ?> 
        </div>
    </div>
    <div id="gestione_allegati" class="allegati gestione_allegati" style="width: 50%;height: 100%;float: left">
                <div class="scheda_container" style="float: left;width: 130px;">
                    <?php
                        echo $data['block']['allegati'];
                        ?> 
                </div>    
                <div id="code" class="scheda_code_container scheda_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_code'];
                    ?> 
                </div>
                <!-- scheda  autobatch -->
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: 100%;width: 130px;float: left;display: none;">
                    <?php
                    echo $data['block']['block_autobatch'];
                    ?>
                </div>
                <div id="visualizzatore" class="scheda_container scheda_container_visualizzatore" style="width: calc(100% - 170px);overflow: hidden;float: left;">
                    <?php
                    echo $data['block']['block_visualizzatore'];
                    ?> 
                </div>
                
    </div>
    <?php
    }
    ?>




<?php
    if(($funzione=='inserimento'))
    {
    ?>
    <div class="ui-widget-header menu_big" style="position: absolute;bottom: 0px;width: 98%;margin: auto;">
        <div id="btnNuovo" class="btn_scritta" onclick="chiudi_scheda(this)" style="width: 130px;"><div class="fa fa-times"></div>Chiudi</div>
        <div id="btnNuovo" class="btn_scritta" onclick="salva_record(this,'chiudi')" style="width: 130px;"><div class="fa fa-floppy-o"></div>Salva e Chiudi</div>
        <div id="btnNuovo" class="btn_scritta" onclick="salva_record(this,'nuovo')" style="width: 130px;"><div class="fa fa-floppy-o"></div>Salva e Nuovo</div>
        <div id="btnInserisci" class="btn_scritta" onclick="salva_record(this,'continua')" style="width: 130px;"><div class="fa fa-floppy-o"></div>Salva</div>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    <?php
    if(($funzione=='modifica'))
    {
    ?>
    <div class="ui-widget-header menu_big" style="position: absolute;bottom: 0px;width: 98%;margin: auto;">
        <div id="btnNuovo" class="btn_scritta" onclick="chiudi_scheda(this)" style="width: 130px;"><div class="fa fa-times"></div>Chiudi</div>
        <div id="btnNuovo" class="btn_scritta" onclick="chiudi_nuovo(this)" style="width: 130px;"><div class="fa fa-floppy-o"></div>Nuovo</div>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    
</div>

    
    
</div>
