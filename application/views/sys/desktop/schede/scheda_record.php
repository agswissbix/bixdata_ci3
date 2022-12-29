<?php
$tableid=$data['tableid'];
$table_description=$this->Sys_model->db_get_value('sys_table','description',"id='$tableid'"); 
$recordid=$data['recordid'];
$funzione=$data['funzione'];
$mode=$data['mode'];
$layout_scheda= $data['layout'];
$popuplvl=$data['popuplvl'];
$userid=$this->session->userdata('idutente');

$target=$data['target'];
$navigatorField=$data['navigatorField'];
$default_save=$table_settings['default_save'];
$dati_width=$table_settings['allargata_dati_width']."%";
$allegati_width_int=100-$table_settings['allargata_dati_width'];
$allegati_width=$allegati_width_int."%";
if(($funzione=='inserimento')&&($tableid=='immobili_richiesti'))
{
    $default_save='custom_immobili_richiesti';
}
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


function stampa_vetrina(recordid)
{
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_vetrina'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_vetrina'); ?>/";
    $.ajax({
        url: urlprint + recordid,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}  


function stampa_prospetto(recordid)
{
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_prospetto'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_prospetto'); ?>/";
    $.ajax({
        url: urlprint + recordid,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}   

function stampa_prospetto_completo(recordid)
{
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_prospetto_completo'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_prospetto_completo'); ?>/";
    $.ajax({
        url: urlprint + recordid,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}  

function stampa_profilo(recordid,tipo)
{
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_profilo'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_profilo'); ?>/";
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

function calcola_preavvisodisdetta(el,recordid_contratto)
{
    $.ajax({
        url: controller_url+"/ajax_calcola_preavvisodisdetta/"+recordid_contratto,
        success:function(data){
            alert(data);
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



var allegati_container=$( "#<?=$id_scheda_record?>" ).find('.allegati_container'); 
var visualizzatore_container=$( "#<?=$id_scheda_record?>" ).find('.visualizzatore_container');


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

<div id="<?=$id_scheda_record?>" class="block_container scheda_record scheda scheda_record" style="position: relative;overflow: hidden" data-tableid="<?=$tableid?>" data-recordid="<?=$recordid?>" data-schedaid="scheda_record" data-navigatorfield="<?=$navigatorField?>" data-layout="<?=$layout_scheda?>" data-target="<?=$target?>" data-popuplvl="<?=$popuplvl?>" data-pinned="false" data-funzione="<?=$funzione?>" data-origine_tableid='<?=$origine_tableid?>' data-origine_recordid='<?=$origine_recordid?>'>

<div class="menu_mid menu_top ui-widget-header menu_scheda_record">
            <?php
            if($funzione!='inserimento')
            {
            ?>
                    <i class="btn_popup_linkedmaster btn_fa fas fa-external-link-alt tooltip" title="apri scheda" onclick="apri_scheda_record(this,'<?=$tableid?>','<?=$recordid?>','popup','allargata')" style="float: left;" ></i>
            <?php
            }
            ?>
            <div style="float: left;font-weight: bold;margin-left: 10px;"><span style="color: black"><?=strtoupper($table_description)?></span></div>
            <div class="tooltip btn_fa fa fa-times  " title="Chiudi la scheda"  onclick="chiudi_scheda(this);" style="float: right;"></div> 
            
            <?php
            if($funzione!='inserimento')
            {
            ?>
                <div class="tooltip btn_fa fa fa-info-circle" title="Mostra informazioni scheda"  onclick="toggle_info_scheda(this)" style="float: left;"   ></div>
                <?php
                if(($cliente_id=='About-x')&&($tableid=='aziende'))
                {
                ?>
                    <div class="tooltip btn_fa fas fa-link" title="Collega"  onclick="collega_record(this,'<?=$recordid?>')" style="float: left;"   ></div> 
                <?php
                }
                ?>
                <div class="tooltip btn_fa fas fa-thumbtack  " title="Blocca la scheda"  onclick="set_pinned(this);" style="float: right;"></div> 
            <?php
                //CUSTOM UNILUDES
                if(($userid==1)||($userid==4)||($userid==14)||($userid==32)||($userid==19)||($userid==22)||($userid==45))
                {
                ?>
                <div class="btn_fa fa fa-lock" style="float: right;" title="permessi" onclick="permessi_record_popup(this)">
                    <div id="permessi_record_popup" class="popup">

                    </div>
                    
                </div>
                <?php
                }
                
            
            ?>
            <?php
            //TEMP Dimensione Immobiliare 
            if(($table_settings['delete']!='false')||(($userid==1)))
            {
            ?>
                <div class="tooltip btn_fa far fa-trash-alt" title="Elimina scheda"  onclick="elimina_record(this,'<?=$data['tableid']?>','<?=$data['recordid']?>');" style="float: right;color: red"   ></div> 
            <?php
            }
            ?>
            
            
            <div class="tooltip btn_fa far fa-copy" title="Duplica"  onclick="duplica_record(this,'<?=$tableid?>','<?=$recordid?>')" style="float: right;"   ></div> 
            
            
            <div class="tooltip btn_fa fa fa-save" title="Salva scheda"  onclick="salva_tutte_modifiche_record(this)" style="float: right;display: none;"   ></div> 
            <?php
            if($tableid=='dem')
            {
            ?>
            <div class="btn_scritta menu_list_button"  style="float: right;">
                Avvio dem
                <ul id="menu_stampe" class="menu_list">
                    <li onclick="avvia_dem('<?=$recordid?>','test')">Test</li>
                    <li onclick="avvia_dem('<?=$recordid?>','produzione')">Produzione</li>
                </ul>
            </div>
            <?php
            }
            ?>
            
            <div class="btn_scritta menu_list_button" style="float: right;">
                    Funzioni
                    <ul id="menu_stampe" class="menu_list">
                        <?php
                        if($tableid=='immobili')
                        {
                        ?>
                            
                            <li><a onclick="correggi_rotazione_foto('<?=$recordid?>')">Correggi rotazione foto</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if(($data['settings']['cliente_id']=='Dimensione Immobiliare')&&($tableid=='immobili_proposti'))
                        {
                        ?>
                            <li><a onclick="nuova_proposta_immobile(this,'<?=$tableid?>','<?=$recordid?>')">Nuova proposta</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if(($data['settings']['cliente_id']=='Interfida')&&($tableid=='matching'))
                        {
                        ?>
                            <li><a onclick="">Proponi immobile</a></li>
                        <?php
                        }
                        ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='candidatiproposti'))
                        {
                        ?>
                            <li><a onclick="rendi_pubblico(this,'<?=$recordid?>')">Rendi pubblico</a></li>
                        <?php
                        }
                        ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='visualizzazioni'))
                        {
                        ?>
                            <li><a onclick="invia_curriculum_da_stato_candidato(this,'anonimo')">Invia curriculum anonimo</a></li>
                        <?php
                        }
                        ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='visualizzazioni'))
                        {
                        ?>
                            <li><a onclick="invia_curriculum_da_stato_candidato(this,'completo')">Invia curriculum completo</a></li>
                        <?php
                        }
                        ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='candidati'))
                        {
                        ?>
                            <li><a onclick="invia_curriculum_da_candidato(this,'anonimo')">Invia curriculum anonimo</a></li>
                        <?php
                        }
                        ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='candidati'))
                        {
                        ?>
                            <li><a onclick="invia_curriculum_da_candidato(this,'completo')">Invia curriculum completo</a></li>
                        <?php
                        }
                        ?>
                            
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='contratti'))
                        {
                        ?>
                            <li><a onclick="calcola_media_contratti_nuova(this,'<?=$recordid?>')">Calcola media contratto</a></li>
                            <li><a onclick="calcola_media_contratto_v2021(this,'<?=$recordid?>')">Calcola nuova media</a></li>
                            <li><a onclick="calcola_preavvisodisdetta(this,'<?=$recordid?>')"">Calcola preavviso disdetta</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='ccl'))
                        {
                        ?>
                            <li><a onclick="report_ccl(this,'<?=$recordid?>')">Apri report CCL</a></li>
                            <li><a onclick="genera_stampa_reportCCL('<?=$recordid?>')">Stampa report CCL</a></li>
                            <li><a onclick="rinnova_ccl(this,'<?=$recordid?>')">Rinnova CCL</a></li>
                            <li><a onclick="approva_ccl(this,'<?=$recordid?>')">Approva CCL</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='dipendenti'))
                        {
                        ?>
                            <li><a onclick="run_function(this,'aggiorna_presenze_dipendente','<?=$recordid?>')">Aggiorna presenze</a></li>
                            <li><a onclick="run_function(this,'aggiorna_disponibilita_dipendente','<?=$recordid?>')">Aggiorna disponibilità</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='azienda')&&($calendarioaziendale=='si'))
                        {
                        ?>
                            <li><a onclick="ajax_calendarioaziendale(this,'<?=$recordid?>','<?=date('Y')?>','')">Calendario aziendale</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='presenzemensili'))
                        {
                        ?>
                            <li><a onclick="run_function(this,'aggiorna_presenze','<?=$recordid?>')">Aggiorna presenze mensili</a></li>
                            <li><a onclick="run_function(this,'aggiorna_presenze_completo','<?=$recordid?>')">Aggiorna presenze mensili completo</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='mail_queue'))
                        {
                        ?>
                            <li><a onclick="direct_update(this,'mail_queue','<?=$data['recordid']?>','status','dainviare');alert('Salvato');">Conferma invio</a></li>

                        <?php
                        }
                        ?>
                            
                            
                        <?php
                        if(($data['settings']['cliente_id']=='About-x')&&($tableid=='segnalazioni'))
                        {
                        ?>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','incaricato','<?=$userid?>');direct_update(this,'segnalazioni','<?=$data['recordid']?>','stato','aperta');alert('Salvato');">Presa in carico</a></li>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','dafatturare','si');alert('Salvato');">Fatturabile</a></li>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','dafatturare','no');alert('Salvato');">Non fatturabile</a></li>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','fatturato','si');alert('Salvato');">Fatturato</a></li>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','fatturato','no');alert('Salvato');">Non fatturato</a></li>
                        <li><a onclick="direct_update(this,'segnalazioni','<?=$data['recordid']?>','dataprox','<?=date('Y-m-d');?>');alert('Salvato');">Da fare oggi</a></li>
                        <li><a onclick="genera_mail_risposta_ticket(this);">Invia risposta</a></li>
                        <?php
                        }
                        ?>
                        
                        <?php            
                        if(($data['settings']['cliente_id']=='Schlegel')&&($tableid=='pratiche'))
                        {
                        ?>
                        <li><a onclick="genera_acconto_step1(this,'<?=$data['recordid']?>')">Crea Acconto</a></li>
                        <li><a onclick="genera_notaprofessionale_step1(this,'<?=$data['recordid']?>')">Crea Nota professionale</a></li>
                         <?php
                        }
                        ?>
                        
                        <?php            
                        if(($data['settings']['cliente_id']=='3p')&&($tableid=='richiestericercapersonale'))
                        {
                        ?>
                            <li><a onclick="custom3p_prepara_notifica_email(this,'<?=$data['recordid']?>')">Invia notifica email</a></li>
                         <?php
                        }
                        ?>
                            
                        <?php            
                        if(($data['settings']['cliente_id']=='swissbix')&&($tableid=='salesorder'))
                        {
                        ?>
                            <li><a onclick="custom_swissbix_salesorder_apribexio(this,'<?=$id_bexio?>')">Apri in bexio</a></li>
                         <?php
                        }
                        ?>
                    </ul>
            </div>
            
            <div class="btn_scritta menu_list_button" style="float: right;">
                    Stampe
                    <ul id="menu_stampe" class="menu_list" style="">
                        <?php
                        if(($data['settings']['cliente_id']=='About-x')&&($tableid=='timesheet'))
                        {
                        ?>
                            <li><a onclick="stampa_rapportino_pdf(this,'<?=$data['recordid']?>')">Rapportino</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if(($data['settings']['cliente_id']=='About-x')&&($tableid=='segnalazioni'))
                        {
                        ?>
                            <li><a onclick="stampa_rapportini_pdf(this,'<?=$data['recordid']?>')">Rapportini</a></li>
                            <li><a onclick="stampa_rapportini_pdf2(this,'<?=$data['recordid']?>')">Rapportini 2</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if($cliente_id!='18-24')
                        {
                        ?>
                            <li><a onclick="prestampa_scheda_record_completa('<?=$tableid?>','<?=$data['recordid']?>');">Stampa scheda</a></li>
                        <?php  
                        }
                        ?>
                        <!--<li><a onclick="stampa_offerta_seatrade(this,'<?=$data['recordid']?>')">Stampa offerta</a></li>-->
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
                        <?php            
                        if(($data['settings']['cliente_id']=='18-24')&&($tableid=='candidati'))
                        {
                            if($userid==1)
                            {
                            ?>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','1')">Curriculum Tipo 1</a></li>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','2')">Curriculum Tipo 2</a></li>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','3')">Curriculum Tipo 3</a></li>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','4')">Curriculum Tipo 4</a></li>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','5')">Curriculum Tipo 5</a></li>
                            
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>','1')">Curriculum Anonimo Tipo 1</a></li>
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>','2')">Curriculum Anonimo Tipo 2</a></li>
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>','3')">Curriculum Anonimo Tipo 3</a></li>
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>','4')">Curriculum Anonimo Tipo 4</a></li>
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>','5')">Curriculum Anonimo Tipo 5</a></li>
                            <?php
                            }
                            else
                            {
                            ?>
                            <li><a onclick="stampa_curriculum('<?=$data['recordid']?>','')">CV Completo</a></li>
                            <li><a onclick="stampa_curriculum_anonimo('<?=$data['recordid']?>')">CV Anonimo</a></li>
                            <?php
                            }
                        }
                            ?>
                        <?php            
                        if(($data['settings']['cliente_id']=='Schlegel')&&($tableid=='fatture'))
                        {
                        ?>
                            <li><a onclick="stampa_fattura(this,'<?=$data['recordid']?>')">Fattura</a></li>
                         <?php
                        }
                        ?>
                            
                            <?php            
                        if(($data['settings']['cliente_id']=='Schlegel')&&($tableid=='pratiche'))
                        {
                        ?>
                            <li><a onclick="stampa_letteraaccompagnamento(this,'<?=$data['recordid']?>')">Lettera accompagnamento</a></li>
                         <?php
                        }
                        ?>
                            
                        <?php 
                        //custom DemoImmobiliare
                        if($data['settings']['cliente_id']=='About-x')
                        {/*
                        ?>
                        <li><a onclick="stampa_registrazione_pdf(this,'<?=$data['recordid']?>')">Registrazione pdf</a></li>
                        <?php
                         * */
                        }
                        ?>
                        <?php
                        if( (($data['settings']['cliente_id']=='Dimensione Immobiliare')||($data['settings']['cliente_id']=='Interfida'))&&(($tableid=='immobili_proposti')))
                        {
                        ?>
                            <li><a onclick="stampa_prospetto_proposta(this,'<?=$data['recordid']?>')">Prospetto pdf</a></li>
                        <?php
                        }
                        ?>
                        
                        
                        
                            
                        <?php 
                        //custom DemoImmobiliare
                        if((($data['settings']['cliente_id']=='Dimensione Immobiliare')||($data['settings']['cliente_id']=='Interfida'))&&($tableid=='immobili'))
                        {
                        ?>
                            <li><a onclick="stampa_vetrina_pdf2(this,'<?=$data['recordid']?>')">Vetrina pdf</a></li>
                            <!--<li><a onclick="stampa_prospetto_completo('<?=$data['recordid']?>')">Prospetto word</a></li>-->
                            <!--<li><a onclick="stampa_prospetto_pdf(this,'<?=$data['recordid']?>')">Prospetto pdf</a></li>-->
                            <li><a onclick="anteprima_prospetto_pdf_nuovo(this,'<?=$data['recordid']?>')">Anteprima Prospetto</a></li>
                            <li><a onclick="stampa_prospetto_pdf_nuovo(this,'<?=$data['recordid']?>')">Prospetto pdf</a></li>
                        <?php
                        }
                        ?>
                            
                            <?php 
                        //custom NuovaCalmed
                        if((($data['settings']['cliente_id']=='NuovaCalmed'))&&($tableid=='vendite'))
                        {
                        ?>
                            <li><a onclick="stampa_fattura_pdf(this,'<?=$data['recordid']?>')">Fattura pdf</a></li>
                            <li><a onclick="stampa_fattura_pdf(this,'<?=$data['recordid']?>')">Scontrino pdf</a></li>
                        <?php
                        }
                        ?>
                            
                        <?php
                        if(($data['settings']['cliente_id']=='Dimensione Immobiliare')&&($tableid=='immobili_richiesti'))
                        {
                        ?>
                            <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Vendita-Lugano','Mandato.pdf')">Mandato</a></li>
                            <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Vendita-Lugano','Mandato semplice.pdf')">Mandato semplice</a></li>
                        <?php
                        }
                        ?>
                    </ul>
            </div>
            <?php
            if(($data['settings']['cliente_id']=='3p')&&($tableid=='contratti'))
            {
            ?>
                <div class="btn_scritta " style="float: right;" onclick="ajax_compilazione_contratto_missione(this,'<?=$recordid?>')">Contratto di missione</div>
            <?php
            }
            ?>
                
            

            
            <?php
            if( (($data['settings']['cliente_id']=='Dimensione Immobiliare')||($data['settings']['cliente_id']=='Interfida'))&&(($tableid=='immobili_proposti')))
            {
            ?>
            <div class="btn_scritta menu_list_button" style="float: right;">
                    Invio
                    <ul id="menu_stampe" class="menu_list">
                        <!--<li><a onclick="preinvio_prospetto(this,'<?=$data['recordid']?>')">Prospetto</a></li>-->
                        <li><a onclick="genera_mail_prospetto(this,'false')">Prospetto</a></li>
                        <li><a onclick="genera_mail_protezionecliente('<?=$tableid?>','<?=$recordid?>')">Protezione cliente</a></li>
                    </ul> 
            </div>
            
            <?php
            }
            ?>

            <?php 
            //CUSTOM About-x
            if(($data['settings']['cliente_id']=='About-x')&&($tableid=='vendite'))
            {
                ?>
                <div class="btn_scritta menu_list_button" style="float: right;">
                    Genera
                    <ul id="menu_stampe" class="menu_list" style="width:150px;">
                        <li><a class="" onclick="">Offerta</a></li>
                    </ul>
                </div> 
            <?php 
            } 
            ?>
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
            ?>
            
            <?php 
            //custom Sea Trade
            if(($data['settings']['cliente_id']=='Sea Trade')&&($tableid=='documenti'))
            {
                ?>
                <div class="btn_scritta menu_list_button" onclick="custom_seatrade_caricamento_manuale(this)"style="float: right;">
                    Invio
                </div> 
            <?php 
            } 
            ?>
            
            <?php 
            //custom Work&Work
            if(($data['settings']['cliente_id']=='Work&Work')&&($tableid=='CANDID'))
            {
                ?>
            <div class="btn_scritta" style="float: right;" onclick="ajax_load_block_invio_pushup('<?=$recordid?>')">
                    Invio Push-up
            </div> 
            <?php 
            } 
            //fine custom Work&Work
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
            <?php
            }
            ?>
            <div class="clearboth"></div>
            </div>
<div id="scheda_record_content" class="schedabody" style="z-index: 1;overflow: hidden;">
    <form id="form_scheda_record" style="height: 100px;overflow: scroll;width: 90%;border: 1px solid red;display: none;">
        
    </form>
    
        <!-- layout standard scheda inizio -->
        <?php
        if(($layout_scheda=='standard_dati')||($layout_scheda=='standard_allegati'))
        {
            if(($data['block']['block_fissi']!='')&&($funzione!='modifica'))
            {
            ?>
                <div id="campi_fissi" class="block_container fissi_container" style="overflow: hidden;width: 100%;">
                    <div id="dettaglio_record_fissi"  style="width: 100%;">
                        <?php
                        echo $data['block']['block_fissi'];
                        ?> 
                    </div>
                </div>
            <?php
            }
            ?>
        <div id="tabs" class="tabs dati_e_allegati" style="overflow: hidden;border: 0px !important">
            <ul>
                <li style="width: 48%;"><a href="#dati" style="display: block;width: 100%;font-weight: bold;">Dati</a></li>
                <?php
                $descrizione_allegati="Allegati";
                //custom Dimensione Immobiliare
                if(($data['settings']['cliente_id']=='Dimensione Immobiliare')&&($tableid=='immobili'))
                {
                    $descrizione_allegati="Foto e Piantine";
                }
                ?>
                <li style="width: 48%;" onclick="tab_allegati_click(this)">
                <?php
                if($data['numfiles']>0)
                {
                ?>
                    <a href="#allegati" style="display: block;width: 100%;font-weight: bold;"><?=$descrizione_allegati?> (<?=$data['numfiles']?>)</a>
                <?php
                }
                else
                {
                ?>
                    <a href="#allegati" style="display: block;width: 100%;color: #9f9f9f;font-weight: bold;"><?=$descrizione_allegati?></a>
                <?php
                }
                ?>
                </li>
            </ul>
            
            <div id="dati" class="block_dati_labels_container">
                    <?php
                    echo $data['block']['block_dati_labels'];
                    ?> 
            </div>
            
            <div id="gestione_allegati" class="allegati gestione_allegati" style="height: calc(100% - 20px);">
                <?php
                
                
                
                if($table_settings['pages_scheda_layout']=='list')
                {
                    $display_allegati='inline-block';
                    $allegati_width='170px';
                    $visualizzatore_width="calc(100% - 190px)";
                    
                }
                if($table_settings['pages_scheda_layout']=='grid')
                {
                    $display_allegati="inline-block";
                    $allegati_width='96%';
                    $visualizzatore_width="calc(100% - 20px)";
                    
                }
                if($table_settings['pages_scheda_layout']=='hidden')
                {
                    $display_allegati="none";
                    $allegati_width='170px';
                    $visualizzatore_width="calc(100% - 20px)";
                }
                ?>
                <div style="display: <?=$display_allegati?>;float: left;width: <?=$allegati_width?>">
                    <div class="scheda_container allegati_container " style="float: left;height: calc(100% - 40px);width: 100%;padding: 10px;">
                        <?php
                            echo $data['block']['allegati'];
                            ?> 
                    </div>    
                    <div id="code" class="scheda_code_container scheda_container" style="height: 100%;width: 170px;float: left;display: none;">
                        <?php
                        echo $data['block']['block_code'];
                        ?> 
                    </div>
                    <!-- scheda  autobatch -->
                    <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: calc(100% - 40px);width: 170px;float: left;padding: 10px;display: none;">
                        <?php
                        //echo $data['block']['block_autobatch'];
                        ?>
                    </div>
                </div>
                <div id="visualizzatore" class="scheda_container scheda_container_visualizzatore visualizzatore_container" style="height: calc(100% - 40px);width: <?=$visualizzatore_width?>;padding: 10px;overflow: hidden;float: left;">
                    <?php
                    echo $data['block']['block_visualizzatore'];
                    ?> 
                </div>
                
            </div>

        </div> 
    <?php
    }
    ?>
    <!-- layout standard scheda fine -->
    
    <!-- layout allargata inizio -->
    <?php
    if($layout_scheda=='allargata')
    {
    ?>
    <div style="width: <?=$dati_width?>;float: left;">
        <?php
        if(($data['block']['block_fissi']!='')&&($funzione!='modifica'))
            {
        ?>
        <div id="campi_fissi" class="block_container fissi_container" style="overflow: hidden;width: 100%;height: 100px;">
                <div id="dettaglio_record_fissi" >
                    <?php
                    echo $data['block']['block_fissi'];
                    ?> 
                </div>
        </div>
        <?php
            }
        ?>
        <div id="dati" class="block_dati_labels_container scheda_container" style="width: 100%;float: left;">
                <?php
                echo $data['block']['block_dati_labels'];
                ?> 
        </div>
    </div>
    <div id="gestione_allegati" class="allegati gestione_allegati" style="width: <?=$allegati_width?>;height: 100%;float: left">
        <?php
            $allegati_width=170;
            
            $allegati_display='none';
            $autobatch_width=170;
            $autobatch_display='block';
            $visualizzatore_differenza_width=$autobatch_width;
        ?>
        <div class="scheda_container allegati_container" style="display: <?=$allegati_display?>;float: left;height: calc(100% - 20px);width: <?=$allegati_width?>px">
            test
        </div>    
        <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="display:none;float: left;height: calc(100% - 20px);width: <?=$autobatch_width?>px;">
            <?php
            echo $data['block']['block_autobatch'];
            ?>
        </div>
        <div id="visualizzatore" class="scheda_container scheda_container_visualizzatore visualizzatore_container" style="float: left;width: calc(100% - 200px)">
            <?php
            echo $data['block']['block_visualizzatore'];
            ?> 
        </div>
                
    </div>
    <?php
    }
    ?>
    <!-- ayout allargata fine -->
    <?php
    if($layout_scheda=='split')
    {
    ?>
    <div id="campi_fissi" class="block_container fissi_container" style="overflow: hidden;height: 100px">
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
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: 100%;width: 150px;float: left;display: none;">
                    <?php
                    //echo $data['block']['block_autobatch'];
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



<div class="ui-widget-header menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 98%;margin: auto;">
<?php
    
    if(($funzione=='inserimento')||($funzione=='modifica'))
    {
    ?>
    
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
            <?php
            if($table_settings['edit']=='true')
            {
            ?>
                <?php
                if($default_save=='salva')   
                {
                    if(($funzione!='modifica')||($tableid!='mail_queue'))
                    {
                ?>
                    <div id="btnSalvaContinua" class="btn_scritta" onclick="salva_record(this,'continua')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva</div>
                    <!--<div id="btnSAlvaNuovo" class="btn_scritta" onclick="salva_record(this,'nuovo')" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e Nuovo</div>-->
                <?php
                    }
                }
                ?>
                <?php
                if($default_save=='salva e chiudi')   
                {
                ?>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="salva_record(this,'chiudi')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e chiudi</div>
                <?php
                }
                ?>
                <?php
                if($default_save=='salva e nuovo')   
                {
                ?>
                    <div id="btnSalvaNuovo" class="btn_scritta" onclick="salva_record(this,'nuovo')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e Nuovo</div>
                    <div id="btnSalvaContinua" class="btn_scritta" onclick="salva_record(this,'continua')" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva</div>
                <?php
                }
                ?>
                <?php
                if($default_save=='salva e nuovo-salva e chiudi')   
                {
                ?>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="salva_record(this,'chiudi')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e chiudi</div>
                    <div id="btnSalvaNuovo" class="btn_scritta" onclick="salva_record(this,'nuovo')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e Nuovo</div>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="allega_coda(this)" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Allega</div>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="allega_salva(this)" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Allega e salva</div>

                <?php
                }
                ?>

                <?php
                if($default_save=='allega salva e nuovo')   
                {
                ?>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="allega_salva(this)" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Allega e salva</div>
                <?php
                }
                ?>

                <?php
                if($default_save=='salva e ripeti')   
                {
                ?>
                    <div id="btnSalvaNuovo" class="btn_scritta" onclick="salva_record(this,'nuovo')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e Nuovo</div>
                    <div id="btnSalvaChiudi" class="btn_scritta" onclick="salva_record(this,'ripeti')" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva e ripeti</div>
                <?php
                }
                ?>
            <?php
            }
            ?>
                    
            <?php
            if($default_save=='custom_immobili_richiesti')   
            {
            ?>
                <div id="btnSalvaContinua" class="btn_scritta" onclick="salva_record(this,'custom_immobili_richiesti')" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva</div>
            <?php
            }
            ?>
        
    <?php
    }
    ?>
    <?php
    if(($funzione=='modifica'))
    {
    ?>
        <?php
        if($tableid=='mail_queue')
        {
        ?>
                <div id="btnNuovo" class="btn_scritta" onclick="annulla_queued_mail(this,'<?=$recordid?>')" style="width: 100px;">Annulla</div>
                <div id="btnNuovo" class="btn_scritta" onclick="conferma_queued_mail(this,'<?=$recordid?>')" style="width: 150px;">Conferma invio</div>

        <?php
        }
        else
        {
        ?>
            <!--<div id="btnNuovo" class="btn_scritta" onclick="chiudi_scheda(this)" style="width: 100px;"><div class="fa fa-times"></div>Chiudi</div>-->
            <!--<div id="btnChiudi" class="btn_scritta" onclick="chiudi_scheda(this)" style="width: 130px;"><div class="fa fa-times"></div>Chiudi</div>-->
            <!--<div id="btnNuovo" class="btn_scritta" onclick="chiudi_nuovo(this)" style="width: 100px;">Nuovo</div>-->



        <?php
        }
    }
    ?>
    <?php
    if(($funzione=='scheda'))
    {
    ?>
            <?php
            if($table_settings['autosave']=='false')
            {
            ?>
                <?php
                if($table_settings['edit']=='true')
                {
                ?>
                    <div id="btnSalvaContinua" class="btn_scritta" onclick="salva_modifiche_record2(this,'<?=$tableid?>','<?=$recordid?>',true)" onmouseover="show_hidden_btn(this)" style="width: 130px;padding: 0px;"><div class="fa fa-floppy-o" style="pointer-events: none;"></div>Salva</div>
                <?php
                }
                ?>
            <?php
            }
            ?>
            <div id="btnChiudi" class="btn_scritta" onclick="chiudi_scheda(this)" style="width: 70px;">Chiudi</div>
            
            
            
    <?php
    }
    ?>
    <div class="autosave_status"  style="float: right;color: #54ace0 !important;font-size: 16px;text-transform: uppercase;margin-right: 20px;"></div>
    <div class="clearboth"></div>
</div>    
    
    
</div>

    
    
</div>
