<?php
$block = $data['block'];
$schede=$data['schede'];
$tableid=$data['tableid'];
$cliente_id=$data['settings']['cliente_id'];
?>


<script type="text/javascript"> 
    $('#content_ricerca').ready(function(){
        $('.smartsearch_field_data').change(function(i){
            var value=$(this).val();
            $(this).next().next().val(value);
        })
        
        $('#smartsearch').find('input').keydown(function (e) {
        if (e.which == 13)
            {
                $('#btn_smartsearch_cerca').click();
            }
        })

        $('#nome_ccl').select2({
            placeholder: '',
            width: '100%',
            allowClear: true
          });
    });
//varabili globali
var schedaid=0;    //numero per identificare le schede aperte

var pinned=false;
var ultimascheda="";
var lastnav=$('#nav_risultati');







function apply(){

    //$( ".tooltip" ).tooltip();   
}




function move_scrollbar(ele)
{
    var nav_id=ele.id;
    /*var position=$("#"+id).attr('data-position');
        $('#content_ricerca').animate({
                scrollLeft: position 
                }, 500); */
        target_id=$("#"+nav_id).attr('data-target_id');
        target=$('#'+target_id);
        $('#content_ricerca').scrollTo(target,500);
        
}





 
 
 
 
 
    
    window.onload=function(){
        //apply();
        //$('#content_ricerca').show();
        
    }; 
    
    
</script>

<script type="text/javascript"> 
    
$(document).ready(function(){
    ajax_load_block_risultati_ricerca($('#btnCerca'), '<?=$data['tableid']?>')
    

    //$( ".tooltip" ).tooltip();
    
    var options, a;
   $('#scheda_dati_ricerca_container').width(scheda_dati_ricerca_container_width);
    $('#scheda_riepilogo').width(scheda_riepilgo_width);
    scheda_risultati_allargata_width=screen_width*0.<?=$table_settings['risultati_width']?>;
    $('#scheda_risultati').width(scheda_risultati_allargata_width);
    //$('#smartsearch').width(scheda_risultati_allargata_width - 20);
    
    $('#scheda_record_container_hidden').width(screen_width*0.<?=$table_settings['scheda_record_width']?>);
    
    $('#ricerca_subcontainer').width(screen_width+scheda_record_container_width);
    

    $('#helper').bind('click', function(e) {
        e.preventDefault();
        $('#helper_popup').bPopup(); 
    });
    $('#dvLoading').fadeOut(1000);
    $('#content_ricerca').scrollTo(0,100);
    lastval='';
    var cloned_nav_button=$('#nav_scheda_hidden').clone();
    lastnav=cloned_nav_button;
    $('#nav_risultati').after(lastnav);
    $('.contentmenu').show();
    $('.contentmenu').find('.nav').each(function(i){
        if(($(this).attr('id')!='nav_ricerca')&&($(this).attr('id')!='nav_risultati')&&($(this).attr('id')!='nav_scheda_hidden'))
        {
            $(this).remove();
        }
    })
   <?php
    if(($table_settings['scheda_ricerca_display']=='true'))
    {
   ?>
        ajax_load_block_dati_labels('<?=$tableid?>','null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));
    <?php
    }
    ?>
    //$(lastnav).remove();
});
</script>

<div id="helper_popup" class="popup" >
    Esito caricamento: <span style="font-weight: bold" id="response"></span>
    <br /><br />
    Indirizzo per aggiornare il profilo del candidato:
    <br />
    <a style="font-weight: bold;" id="jdoconline_url" href="" target="_blank"></a>
    <div style="margin-top: 50px;">
        <div id="btn_invia_mail" class="btn_scritta">Invia mail al candidato</div>
        <div class="clearboth"></div>
    </div>
</div>
<div style="width: 100%;position: relative">
    <button class="btn_fab menu_list_button" style="position: absolute;top: 3px;left: 3px;z-index: 100;background-color: black;height: 30px;width: 30px;i" onclick="visualizza_filtri(this,'<?=$tableid?>')"><i class="material-icons">search</i></button>
    <div id="content_ricerca" class="content contentbody" style=" width: 100%;overflow-x: scroll;overflow-y: hidden;">
        
        <div id="ricerca_subcontainer" class="subcontainer" style="height: 100%;" >
            <?php
            $display_false='';
            if(($table_settings['scheda_ricerca_display']=='false'))
            {
                $display_false='style="display: none;" data-displayed="false"';
            }
            ?>
            <div <?=$display_false?> id="scheda_dati_ricerca_container" class="scheda_container content_scheda_container scheda_dati_ricerca_container container"  > 
                <?php
                    echo $schede['scheda_dati_ricerca'];
                ?>
            </div>
            <div style="float: left">
                
                <div id="scheda_risultati" class="blocco scheda_container content_scheda_container container " style="opacity: 0;">
                        <div id="dvLoading_risultati" class="blocco" style="height: 100%;width: 100%;z-index: 1000;display: none">
                                <h1>Caricamento</h1>
                        </div>
                        <?php
                        if($tableid=='rapportidilavoro')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[dipendenti][type]" value="linkedmaster">
                                <input type="hidden" name="tables[dipendenti][fields][id][type]" value="Numero">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">ID </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][id][value][0]" type="number" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_id').val($(this).val())">
                                <input type="hidden" name="tables[dipendenti][fields][nome][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Cognome </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][cognome][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                <input type="hidden" name="tables[dipendenti][fields][cognome][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Nome </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][nome][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                <input type="hidden" name="tables[dipendenti][fields][data][type]" value="Data">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data dal </span><input class="smartsearch_field_data field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][data][value][0]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_dal').val($(this).val())">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data al </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][data][value][1]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_al').val($(this).val())">
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="smartsearch(this)">Cerca</div>
                                
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if($tableid=='richiestericercapersonale')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>"> 
                                <input type="hidden" name="tables[richiestericercapersonale][type]" value="master">
                                
                                <input type="hidden" name="tables[richiestericercapersonale][fields][cliente][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Cliente </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[richiestericercapersonale][fields][cliente][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[richiestericercapersonale][fields][consulente][type]" value="Utente">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Consulente </span>
                                <select id="consulente" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" name="tables[richiestericercapersonale][fields][consulente][value][0]">
                                    <option value=""></option>
                                    <?php
                                                            foreach ($utenti as $key => $utente) {
                                                                if(($utente['username']!='superuser')&&(strpos($utente['username'],"group") === false))
                                                                {
                                                            
                                                            ?>
                                    <option value="<?=$utente['id']?>"><?=$utente['username']?></option>
                                    <?php
                                                                }
                                    }
                                    ?>
                                    
                                </select>
                                
                                <div style="float:left;margin-left: 50px;margin-top: 5px;">
                                    Preventivi <input type="checkbox" name="custom_fields[stato][preventivi]"> 
                                </div>
                                <div style="float:left;margin-left: 50px;margin-top: 5px;">
                                    Attivi <input type="checkbox" name="custom_fields[stato][attivi]"> 
                                </div>
                                <div style="float:left;margin-left: 50px;margin-top: 5px;">
                                    Chiusi vinti <input type="checkbox" name="custom_fields[stato][chiusivinti]"> 
                                </div>
                                <div style="float:left;margin-left: 50px;margin-top: 5px;">
                                    Chiusi persi <input type="checkbox" name="custom_fields[stato][chiusipersi]"> 
                                </div>
                                <div style="float:left;margin-left: 50px;margin-top: 5px;">
                                    Sospesi <input type="checkbox" name="custom_fields[stato][sospesi]">
                                </div>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="customsearch(this)">Imposta filtro</div>
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if($tableid=='contratti')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[ccl][type]" value="linkedmaster">
                                <input type="hidden" name="tables[ccl][fields][nomeccl][type]" value="Parola">
                                
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Nome CCL </span>
                                <div style="float: left;width: 50%">
                                    <select id="nome_ccl" class="" style="" name="tables[ccl][fields][nomeccl][value][0]">
                                        <option value=""></option>
                                        <?php
                                        foreach ($ccl_list as $key => $ccl) {
                                        ?>
                                        <option value="<?=$ccl['nomeccl']?>"><?=$ccl['nomeccl']?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="margin-left: 20px;" onclick="customsearch(this)">Cerca</div>

                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if($tableid=='tempcontrol')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[tempcontrol][type]" value="master">
                                <input type="hidden" name="tables[tempcontrol][fields][cliente][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Cliente </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[tempcontrol][fields][cliente][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data dal </span><input class="smartsearch_field_data field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="custom_fields[datadal]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_dal').val($(this).val())">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data al </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="custom_fields[dataal]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_al').val($(this).val())">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Nome CCL </span>
                                <div style="float: left;width: 30%">
                                    <select id="nome_ccl" class="" style="" name="custom_fields[nomeccl]">
                                        <option value=""></option>
                                        <?php
                                        foreach ($ccl_list as $key => $ccl) {
                                        ?>
                                        <option value="<?=$ccl['nomeccl']?>"><?=$ccl['nomeccl']?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="ricalcola_tempcontrol(this)">Ricalcola</div>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="customsearch(this)">Cerca</div>
                                
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if($tableid=='listaclienti')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[tempcontrol][type]" value="master">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data dal </span><input class="smartsearch_field_data field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="custom_fields[datadal]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_dal').val($(this).val())">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Data al </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 130px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="custom_fields[dataal]" type="date" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="" onchange="$('#mediaperiodo_al').val($(this).val())">
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="ricalcola_listaclienti(this)">Ricalcola</div>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="ajax_load_content(this,'ajax_load_content_listaclienti')">Apri interfaccia custom</div>
                                
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <?php
                        if($tableid=='carenze')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[carenze][type]" value="master">
                                
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Anno </span>
                                <select id="presenze_anno" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" name="custom_fields[anno]">
                                    <option value=""></option>
                                    <?php
                                                            foreach ($anni as $key => $anno) {
                                                                
                                                            
                                                            ?>
                                    <option value="<?=$anno['anno']?>"><?=$anno['anno']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Mese </span>
                                <select id="presenze_mese" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" name="custom_fields[mese]">
                                    <option value=""></option>
                                    <option value="01">Gennaio</option>
                                    <option value="02">Febbraio</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Aprile</option>
                                    <option value="05">Maggio</option>
                                    <option value="06">Giugno</option>
                                    <option value="07">Luglio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Settembre</option>
                                    <option value="10">Ottobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Dicembre</option>
                                </select>
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="ricalcola_carenze(this)">Ricalcola</div>
                                
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                        
                        <?php
                        if($tableid=='salesorder')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[salesorder][type]" value="master">
                                <input type="hidden" name="tables[salesorder][fields][documentnr][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Document nr</span>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[salesorder][fields][documentnr][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                
                                <input type="hidden" name="tables[company][type]" value="linkedmaster">
                                <input type="hidden" name="tables[company][fields][companyname][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Company</span>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[company][fields][companyname][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[salesorder][type]" value="master">
                                <input type="hidden" name="tables[salesorder][fields][sector][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Sector</span>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[salesorder][fields][sector][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[salesorder][type]" value="master">
                                <input type="hidden" name="tables[salesorder][fields][status][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Status</span>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[salesorder][fields][status][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[salesorderline][type]" value="linked">
                                <input type="hidden" name="tables[salesorderline][fields][name][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Product description</span>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[salesorderline][fields][name][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="smartsearch(this)">Cerca</div>
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                    
                    
                    
                        <?php
                        if($tableid=='presenzemensili')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                <input type="hidden" name="tables[presenzemensili][type]" value="master">
                                <input type="hidden" name="tables[presenzemensili][fields][anno][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Anno </span>
                                <select id="presenze_anno" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 50px;padding-top: 0px;padding-bottom: 0px;" name="tables[presenzemensili][fields][anno][value][0]">
                                    <option value=""></option>
                                    <?php
                                                            foreach ($anni as $key => $anno) {
                                                                
                                                            
                                                            ?>
                                    <option value="<?=$anno['anno']?>"><?=$anno['anno']?></option>
                                    <?php
                        }
                                    ?>

                                </select>
                                <input type="hidden" name="tables[presenzemensili][fields][mese][type]" value="Lookup">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Mese </span>
                                <select id="presenze_mese" class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 80px;padding-top: 0px;padding-bottom: 0px;" name="tables[presenzemensili][fields][mese][value][0]">
                                    <option value=""></option>
                                    <option value="1">Gennaio</option>
                                    <option value="2">Febbraio</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Aprile</option>
                                    <option value="5">Maggio</option>
                                    <option value="6">Giugno</option>
                                    <option value="7">Luglio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Settembre</option>
                                    <option value="10">Ottobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Dicembre</option>
                                </select>
                                
                                <input type="hidden" name="tables[dipendenti][type]" value="linkedmaster">
                                <input type="hidden" name="tables[dipendenti][fields][id][type]" value="Numero">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">ID </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][id][value][0]" type="number" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[dipendenti][type]" value="linkedmaster">
                                <input type="hidden" name="tables[dipendenti][fields][nome][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Cognome </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][cognome][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[dipendenti][type]" value="linkedmaster">
                                <input type="hidden" name="tables[dipendenti][fields][cognome][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Nome </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="tables-dipendenti-search-t_1-fields-id-f_01520519577-value-0" name="tables[dipendenti][fields][nome][value][0]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[azienda][type]" value="custom">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Azienda </span><input class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;" id="custom-azienda" name="tables[azienda][ragionesociale]" type="text" placeholder="" value="" maxlength="254" tabindex="" data-last_field="" data-lastval="">
                                
                                <input type="hidden" name="tables[rapportidilavoro][type]" value="linked">
                                <input type="hidden" name="tables[rapportidilavoro][fields][situazione][type]" value="Lookup">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Situazione </span>
                                <select  class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" id="tables-rapportidilavoro-search-t_1-fields-situazione-f_01556107937-value-0" name="tables[rapportidilavoro][fields][situazione][value][0]">
                                    <option value=""></option>
                                    <option value="a">Assenza ingiustificata</option>
                                    <option value="d">Disponibile</option>
                                    <option value="fnp">Ferie non pagate</option>
                                    <option value="f">Ferie pagate</option>
                                    <option value="inf">Infortunio con certificato</option>
                                    <option value="kr">Malattia</option>
                                    <option value="lr">Lavoro ridotto</option>
                                    <option value="mal">Malattia con certificato</option>
                                    <option value="mat">Maternità</option>
                                    <option value="nd">Non disponibile</option>
                                    <option value="nr">Non reperibile</option>
                                    <option value="p">Permesso non pagato</option>
                                    <option value="r">Ritardo</option>
                                    <option value="fine">Termine del rapporto</option>
                                    <option value="½ F">½ Ferie</option>
                                    <option value="1° G">1° Giorno</option>
                                </select>
                                
                                
                                <input type="hidden" name="tables[rapportidilavoro][fields][situazione2][type]" value="Lookup">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Situazione 2 </span>
                                <select  class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" id="tables-rapportidilavoro-search-t_1-fields-situazione-f_01556107937-value-0" name="tables[rapportidilavoro][fields][situazione2][value][0]">
                                    <option value=""></option>
                                    <option value="d">Disponibile</option>
                                    <option value="Uscito">Uscito</option>
                                </select>
                                
                                
                                
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Colore </span>
                                <select  class="field fieldtoblur fieldInput fieldRange fieldValue0 ricerca first  " style="border: 1px solid #dedede !important;width: 100px;padding-top: 0px;padding-bottom: 0px;" id="tables-rapportidilavoro-search-t_1-fields-situazione-f_01556107937-value-0" name="custom_fields[colore]">
                                    <option value=""></option>
                                    <option value="VerdeIntermedio" style="background-color: #84C260">Festivo pagato</option>
                                    <option value="VerdeScuro" style="background-color: green">Giorno di prova</option>
                                    <option value="VerdeChiaro" style="background-color: lightgreen">Contratto approvato</option>
                                    <!--<option value="a" style="background-color: OrangeRed">Rosso</option>-->
                                    <option value="Grigio" style="background-color: #dedede">Festivo grigio</option>
                                    <option value="Arancione" style="background-color: Coral">Dipendente interno</option>
                                    <option value="Giallo" style="background-color: Yellow">Contratto da approvare</option>
                                    <option value="Viola" style="background-color: violet">Annuncio URC</option>
                                    
                                </select>
                                




                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="smartsearch(this)">Cerca</div>
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                        if($tableid=='immobili')
                        {
                        ?>
                        <div id="smartsearch" class="blocco scheda_container scheda" style="padding: 0px;height: 30px;width: calc(100% - 30px);margin-bottom: 0px;padding-left: 20;">
                            <form>
                                <input type="hidden" name="tableid" value="<?=$tableid?>">
                                
                                <input type="hidden" name="tables[immobili][fields][riferimento][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Rif </span>
                                <input class="field" style="border: 1px solid #dedede !important;width: 100px;" name="tables[immobili][fields][riferimento][value][0]" type="text" >
                                
                                <input type="hidden" name="tables[immobili][fields][paese][type]" value="Parola">
                                <span style="float: left;margin-right: 10px;margin-left: 10px;font-weight: bold">Paese </span>
                                <input class="field" style="border: 1px solid #dedede !important;width: 100px;" name="tables[immobili][fields][paese][value][0]" type="text" >
                                
                                <div id="btn_smartsearch_cerca" class="btn_scritta" style="float: left;margin-left: 20px;" onclick="smartsearch(this)">Cerca</div>
                            </form>
                        </div>
                        <?php
                        }
                        ?>
                        <div id="risultati_ricerca" class="block_container"  style="height: 100%;width: 100%;">

                        </div>
                 </div>
            </div>
        </div>
    </div>
</div>