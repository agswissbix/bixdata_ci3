<div class="develop">block-risultati_ricerca</div>
<?php
$tableid=$data['archivio'];
$aaSorting="[[ 1, 'desc' ]]";
$settings=$data['settings'];
$cliente_id=$settings['cliente_id'];
$fields=$data['fields'];
$export_list=$data['export_list'];
$active_tab=0;
$risultati_layout=$table_settings['risultati_layout'];
if($risultati_layout=='righe')
{
   $active_tab=0; 
}
if($risultati_layout=='table')
{
   $active_tab=0; 
}
if($risultati_layout=='report')
{
   $active_tab=1; 
}
if($risultati_layout=='calendar')
{
   $active_tab=2 ;
}
?>

<script type="text/javascript">
$('#risultati_ricerca').ready(function(){
    $('#tabs_risultati_ricerca').tabs({
        active: <?=$active_tab?>,
        activate: function( event, ui ) {
            var activetab_id=$(ui.newTab).attr('aria-controls');
            refresh_risultati_ricerca(activetab_id);
        }
    });
    
    //$(".tooltip").tooltip();
    $('.report_table').DataTable({
        "bFilter": false,
        "lengthChange": false,
    });
    $( ".menu_list" ).menu();
$( ".menu_list_button" ).hover(
            function() {
              $(this).find('.menu_list').show();
            }, function() {
              $(this).find('.menu_list').hide();
            }
          );
var scheda_dati_ricerca=$('#scheda_dati_ricerca');
var form_riepilogo_savedview=$(scheda_dati_ricerca).find('.form_riepilogo_savedview').find('span').html();  
$('#risultati_riepilogo_filtri').append(form_riepilogo_savedview);
refresh_risultati_ricerca();
});

function stampa_elencoBAK(el)
{
    var btn_stampa_elenco_container=$(el);
    var order_key=$(btn_stampa_elenco_container).data('orderkey');
    var order_ascdesc=$(btn_stampa_elenco_container).data('orderascdesc');
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_elenco')."/".$data['archivio']; ?>/"+order_key+'/'+order_ascdesc;
    var urldownload="<?php echo site_url('sys_viewcontroller/download_elenco'); ?>/";
    $.ajax({
        type: "POST",
        url: urlprint,
        data: $('#form_riepilogo').serialize(),
        success:function(data){
            //$('#targetRisultatiRicerca').html(data);
            window.location.href = urldownload + data;
        },
        error:function()
        {
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}



</script>
<div id="invia_mail" class="popup" style="width: 40% !important; height: 90% !important;">
</div>
<div id="esporta_risultati" style="height: 80%;">
</div>
<div id="risultati_ricerca" class="scheda block_container ui-widget-content " style="" data-popuplvl="0">
    <div class="menu_mid menu_top ui-widget-header">
        
        <?php
        if(($cliente_id!='Work&Work')&&($cliente_id!='Uniludes'))    
        {
        ?> 
            <!--<div class="btn_custom btn_plus_right tooltip" title="Nuovo" onclick="apri_scheda_record(this,'<?=$tableid?>','null','right','standard_dati','risultati_ricerca','inserimento');" style="float: right;" ><div class="plus_right" s >+</div></div>-->
        <?php
        }
        ?>
        <?php
        if($cliente_id!='Work&Work' and $cliente_id!='18-24')    
        {
            $target=$table_settings['risultati_new'];
            $layout=$table_settings['scheda_layout'];
            if($target=='popup')
                $layout=$table_settings['popup_layout'];
            
        ?>    
            <?php
            if($table_settings['edit']=='true')
            {
            ?>
                 <button class="btn_fab menu_list_button" style="float: right;margin-top: -17px;margin-right: -13px" onclick="apri_scheda_record(this,'<?=$tableid?>','null','<?=$target?>','<?=$layout?>','risultati_ricerca','inserimento');"><i class="material-icons">add</i></button>
            <?php
            }
            ?>
        <!--<div class="btn_custom btn_plus_popup tooltip" title="Nuovo" onclick="apri_scheda_record(this,'<?=$tableid?>','null','popup','allargata','risultati_ricerca','inserimento');" style="float: right;margin-right: 5px;" ><div class="plus_popup">+</div></div>-->
        <?php
        }
        ?>
           <div style="float: left;margin-left: 10px;margin-right: 10px;color: black;text-transform: uppercase;font-weight: bold"><?=$table_description?></div> 
           <div id="risultati_riepilogo_filtri"  style="max-width: 50%;height: 18px;overflow: hidden;float: left">
           </div>
        <?php
        if($cliente_id=='Work&Work')    
        {
        ?> 
            <div id="btn_stampa_elenco_container" style="float: right" class="btn_scritta" data-orderkey="" data-orderascdesc="desc" onclick="stampa_elenco(this)">
                    Stampa elenco
            </div>
            
        <?php
        }
        ?>
        <?php
        if($cliente_id=='Work&Work')    
        {
        ?> 
            <div class="btn_scritta menu_list_button" onclick="ajax_load_block_invia_mail(this,'<?=$tableid?>')" style="float: right;" >
                Invia Mail
            </div>
        <?php
        }
        ?>
        <div class="btn_scritta menu_list_button" style="float: right;" >
                    Esporta
                    <ul id="menu_stampe" class="menu_list" style="width:250px;">
                        <li><a onclick="esporta_xls2(this,'<?=$tableid?>')">Esportazione dati excel</a></li>
                        <li><a onclick="stampa_elenco(this,'<?=$tableid?>')">Esportazione elenco</a></li>
                        <?php
                        if($cliente_id=='Work&Work')
                        {
                        ?>
                            <li><a onclick="esporta_file_lgl(this,<?=$userid?>)">Esporta file lgl</a></li>
                            <li><a onclick="esporta_excel_lgl(this)">Esporta excel lgl</a></li>
                        <?php
                        }
                        ?>
                        <?php
                                foreach ($export_list as $key => $export) {
                                    ?>
                                    <li><a onclick="esporta_xls(this,'<?=$export['id']?>')"><?=$export['name']?></a></li>
                        <?php
                                }
                        ?>
                        <?php
                        if(true)
                        {
                        ?> 
                            <li><a onclick="export_migrazione(this,'<?=$tableid?>')">Esportazione migrazione</a></li>
                        <?php
                        }
                        ?>
                    </ul>
        </div>
           
           <?php
           if($tableid=='mail_queue')
           {
           ?>
           <div class="btn_scritta menu_list_button" style="float: right;" onclick="send_queued_mail()">
               Invia
           </div>
           <?php
           }
           ?>
           <?php
           if(($cliente_id=='3p')&&($tableid=='contratti'))
            {
           ?>
           <div class="btn_scritta menu_list_button" style="float: right;" onclick="calcola_media_contratti_nuova(this,'')">
               Aggiorna medie
           </div>
           <div class="btn_scritta menu_list_button" style="float: right;" onclick="calcola_media_contratto_v2021(this,'')">
               Aggiorna medie 2
           </div>
           <?php
            }
           ?>
           
           <?php
           if(($cliente_id=='3p')&&($tableid=='rapportidilavoro'))
            {
           ?>
           <form id="form_calcola_media_periodo">
            <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">ID </span><input id="mediaperiodo_id" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;"  name="id" type="number" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
            <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data dal </span><input id="mediaperiodo_dal" class="smartsearch_field_data field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;"  name="dal" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
            <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data al </span><input id="mediaperiodo_al" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;"  name="al" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
            <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="calcola_mediaperiodo(this)">Media periodo</div>
            <div style="float: left;" id="mediaperiodo"></div>
           </form>
            <?php
            }
           ?>
           
           
           
           
 
           <div class="btn_scritta menu_list_button" style="float: right;" onclick="refresh_risultati_ricerca()">
               Refresh
                <?php
                if(($cliente_id=='3p')&&($tableid=='presenzemensili'))
                {
                ?>
                <ul id="menu_aggiorna" class="menu_list" style="width:250px;">
                        <li><a onclick="custom_3p_aggiornaPresenzemesevisualizzato(this,'<?=$tableid?>')">Aggiorna mese visualizzato</a></li>
                        <li><a onclick="custom_3p_aggiornaPresenzemesecorrente(this,'<?=$tableid?>')">Aggiorna presenze mese corrente</a></li>
                        <li><a onclick="custom_3p_aggiornaNmesecorrente(this,'<?=$tableid?>')">Aggiorna N. mese corrente</a></li>
                        <li><a onclick="custom_3p_aggiornaNdainizioanno(this,'<?=$tableid?>')">Aggiorna N. da inizio anno</a></li>
                        <li><a onclick="custom_3p_aggiorna3mesi(this,'<?=$tableid?>')">Aggiorna 3 mesi data scadenza</a></li>
                </ul>
                <?php
                }
                ?>
           </div>
           
           <?php
           if($cliente_id=='3p' && $tableid=='presenzemensili')
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="custom_3p_presenzemensili_alias(this)" data-mostra_alias="false">
               Mostra Alias
           </div>
           <?php
           }
           ?>
           <?php
           if($cliente_id=='3p' && $tableid=='calcolofatturato')
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="ajax_load_content(this,'ajax_load_content_calcolofatturato')" data-mostra_alias="false">
               Interfaccia custom
           </div>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="run_function(this,'genera_calcolofatturato')" data-mostra_alias="false">
               Ricalcola
           </div>
           <?php
           }
           ?>
           <?php
           if($cliente_id=='3p' && $tableid=='ccl')
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="ajax_load_content(this,'ajax_load_content_listaccl')" data-mostra_alias="false">
               Interfaccia custom
           </div>
   
           <?php
           }
           ?>
           <?php
           if(($tableid=='aziende')||($tableid=='contatti'))
           {
           ?>
           <div class="btn_scritta menu_list_button" style="float: right;" onclick="ajax_load_block_dem_select(this)">
               DEM
           </div>
           <!--<div class="btn_scritta menu_list_button" style="float: right;" onclick="ajax_load_block_campagne_select(this)">
               TELEMARKETING
           </div>-->
           <?php
           }
           ?>
           
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='freshdesk')
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="sincronizza_api('api_freshdesk_get_tickets')" data-mostra_alias="false">
               Sincronizza
           </div>
           <?php
           }
           ?>
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='freshdeskcontatti')
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="sincronizza_api('api_freshdesk_get_contacts')" data-mostra_alias="false">
               Sincronizza
           </div>
           <?php
           }
           ?>
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='aziende' && $userid==1)
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="sincronizza_api('api_bexio_get_contacts')" data-mostra_alias="false">
               <a href="index.php/sys_viewcontroller/api_bexio_get_contacts" target="_blank"> Sincronizza</a>
           </div>
           <?php
           }
           ?>
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='ordini' && $userid==1)
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" data-mostra_alias="false">
               <a href="index.php/sys_viewcontroller/api_bexio_get_orders" target="_blank"> Sincronizza</a>
           </div>
           <?php
           }
           ?>
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='vendite' && $userid==1)
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="sincronizza_api('api_bexio_get_offers')" data-mostra_alias="false">
               Sincronizza
           </div>
           <?php
           }
           ?>
           
           <?php
           if($cliente_id=='Swissbix' && $tableid=='fatture' && $userid==1)
           {
           ?>
           <div id="custom_3p_presenzemensili_alias" class="btn_scritta menu_list_button" style="float: right;" onclick="sincronizza_api('api_bexio_get_invoices')" data-mostra_alias="false">
               Sincronizza
           </div>
           <?php
           }
           ?>
           
        <!--<div class="btn_scritta" style="float: right;"  onclick="ajax_load_block_esporta_risultati(this,'<?=$tableid?>')">
                Esporta Doc
        </div>-->

        <?php
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='immobili_proposti'))    
        {
        ?> 
            
           <div id="" class="btn_scritta" style="float: right" onclick="apri_scheda_record(this,'immobili_richiesti','null','right','standard_dati','risultati_ricerca','inserimento');">Nuova richiesta</div>
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_richiesti/desktop')">Richieste</div>
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')">Rubrica</div>
        <?php
        }
        ?> 
        <?php
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='immobili_richiesti'))    
        {
        ?> 
            
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_proposti/desktop')">Proposte</div>
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')">Rubrica</div>
        <?php
        }
        ?> 
        <?php
        if(($cliente_id=='Dimensione Immobiliare')&&($tableid=='contatti'))    
        {
        ?> 
            
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_richiesti/desktop')">Richieste</div>
           <div id="" class="btn_scritta" style="float: right" onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_proposti/desktop')">Proposte</div>
        <?php
        }
        ?>
           <div class="clearboth"></div>
    </div>
    <div class="schedabody">
        <div id="tabs_risultati_ricerca" style="padding: 0px !important;border: 0px !important">    
            <ul>
                
                <?php
                $div_target='risultati_ricerca_datatable';
                if($risultati_layout=='righe') 
                {
                    $div_target='#risultati_ricerca_datatable';
                } 
                
                if($risultati_layout=='table')
                {
                    $div_target='#risultati_ricerca_results';  
                }
                ?>
                <?php
                if($cliente_id=='3p')
                {
                    foreach ($views as $key => $view) {
                    ?>     
                        <li style="width: 20%;" onclick="view_changed(this,'<?=$tableid?>','<?=$view['id']?>')"><a style="display: block;width: 100%" href="<?=$div_target?>"><?=$view['name']?></a></li>    
                    <?php
                    }
                }
                ?>

                <li style="width: 20%;" onclick="reload_fields(this,'<?=$tableid?>')"><a style="display: block;width: 100%" href="<?=$div_target?>">Tutti</a></li>
                <?php
                if($table_settings['risultati_showreport']=='true')
                {
                ?>
                    <li style="width: 20%;"><a style="display: block;width: 100%" href="#risultati_ricerca_report">Report</a></li>
                <?php
                }
                ?>
                
                <?php
                if($table_settings['risultati_showcalendar']=='true')
                {
                ?>
                    <li style="width: 20%;"><a style="display: block;width: 100%" href="#risultati_ricerca_calendar">Calendario</a></li>
                <?php
                }
                ?>
                
                
            </ul>
            <?php
                if($risultati_layout=='righe') 
                {
                ?>
            <div id="risultati_ricerca_datatable" class="ui-widget datatable_records_container" style="width: 100%;height: 100%;">
            
            </div>
           <?php
                }
           ?>
            
            <?php
            if($risultati_layout=='table') 
                {
                ?>
            <div id="risultati_ricerca_results" class="reports_relativi_container" style="width: 100%;height: 100%;overflow: scroll">

            </div>
            <?php
                }
            ?>
           
            <?php
            if($table_settings['risultati_showreport']=='true')
            {
            ?>
            <div id="risultati_ricerca_report" class="reports_relativi_container" style="width: 100%;height: 100%;overflow: scroll">
                report
            </div>
            <?php
            }
            ?>
            <?php
            if($table_settings['risultati_showcalendar']=='true')
            {
            ?>
            <div id="risultati_ricerca_calendar" class="ui-widget" style="width: 100%;height: 500px;overflow-y: scroll">
                calendario
            </div>
            <?php
            }
            ?>
           
           
        </div>
    </div>
</div>

                            
