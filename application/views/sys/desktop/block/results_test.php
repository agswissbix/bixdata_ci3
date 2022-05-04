<?php
if($contesto=='stampa_elenco')
{
    $table_settings['risultati_border']='1px solid black !important';
}

    $overflow='overflow: scroll';
    $results_height='height: 100%';
    $table_container_height='height: calc(100% - 50px)';
    $font='';
    if($contesto=='stampa_elenco')
    {
        $overflow='';
        $results_height='';
        $table_container_height='';
        $font="font-family: Calibri !important;";
    }
?>
<style type="text/css">
    
    .corner {
  width: 0; 
  height: 0; 
  border-top: 10px solid red;
  border-bottom: 10px solid transparent;
  border-left: 10px solid transparent;
}

.corner span {
  position:absolute;
  top: 35px;
  width: 10px;
  left: 5px;
  text-align: center;
  color: #ffffff;
  text-transform: uppercase;
  font-size: 14px;
  transform: rotate(45deg);
  display:block;
}

    table{
        border-collapse: collapse;
    }
    .page-item.active{
        background-color: #A4D1F1;
    }
    .results th{
        padding: 4px;
        border: <?=$table_settings['risultati_border']?>;
        font-size: <?=$table_settings['risultati_font_size']?>px;
        border-bottom:  1px solid #dedede;
        text-align: center;
    }
    .results td{
        
        padding: 4px;
        padding-top: 2px;
        padding-bottom: 2px;
        font-size: <?=$table_settings['risultati_font_size']?>px;
        white-space: nowrap;
        height: 50px !important;
        border: <?=$table_settings['risultati_border']?>;
        border-bottom:  1px solid #dedede;
        text-align:  center;
        
    }
    .results td:hover{
        border: 1px double black !important;
    }
    
    .results td input{
        background: none !important;
    }
    .results tbody>tr:hover{
        background-color: #8DB3E2 !important;
    }
    
    .fieldcontainer:hover .btn_fa{
        opacity: 1 !important;
    }
    .results tr{
        
        height: 40px;
        overflow: none;
    }
    .results tr:nth-child(even) {
        background: white
    }
    .results tr:nth-child(odd) {
        background: white
    }
    
    input{
        outline: none !important;
        border: none !important;
    }
    
    .pagination li,.pagination li a {
    color: #000;
    text-decoration: none;
    display: block;
    float: left;
    margin: 5px;
}

.value_linkedmaster{
    cursor: pointer;
    border: 1px solid transparent;
}
.value_linkedmaster:hover{
    border: 1px solid black;
}
</style>


<script type="text/javascript">
    
$(document).ready(function(){
   $(function () {
        var obj = $('#pagination').twbsPagination({
            totalPages: 35,
            visiblePages: 10,
            startPage:<?=$page?>,
            initiateStartPageClick: false,
            onPageClick: function (event, page) {
                $('#results_currentpage').val(page);
                refresh_risultati_ricerca('risultati_ricerca_results');
            }
        });
    }); 
    
        
        
        $('.input2update').change(function(){
            results_field_changed(this);
        })
        
        $('.linked_input').each(function(i){
            results_set_autocomplete_linked($(this));
        })
        
        $('.linked_input').click(function(i){
            $(this).val('');
            $(this).autocomplete( "search", 'sys_recent' );
        });
        <?php
        if($tableid=='presenzemensili')
        {
        ?>
        $.contextMenu({
            // define which elements trigger this menu
            selector: "#results_<?=$tableid?> td",
            // define the elements of the menu
            build: function(trigger, e) {
                contextmenu_el=trigger;
                contextmenu_recordid=$(trigger).closest('tr').data('recordid');
                contextmenu_fieldid=$(trigger).closest('td').data('fieldid');
            },
            items: {
                    
                    note: {name: "Note", callback: function(key, opt){ contextMenu_apri_note(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    <?php
                    if($table_settings['edit']=='true')
                    {
                    ?>
                    segnafestivita: {name: "Segna festività rossa", callback: function(key, opt){ contextMenu_segna_festivita(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    annullafestivita: {name: "Annulla festività rossa", callback: function(key, opt){ contextMenu_annulla_festivita(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    coloraverde: {name: "Colora giorno di prova", callback: function(key, opt){ contextMenu_colora_verde(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    annullaverde: {name: "Annulla giorno di prova", callback: function(key, opt){ contextMenu_annulla_verde(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    colorafestivopagato: {name: "Colora festivo pagato", callback: function(key, opt){ contextMenu_colora_festivopagato(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    annullafestivopagato: {name: "Annulla festivo pagato", callback: function(key, opt){ contextMenu_annulla_festivopagato(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    coloraviola: {name: "Colora viola", callback: function(key, opt){ contextMenu_colora_viola(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    annullaviola: {name: "Annulla viola", callback: function(key, opt){ contextMenu_annulla_viola(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    aprirapportodilavoro: {name: "Apri rapporto giornaliero", callback: function(key, opt){ contextMenu_apri_rapportodilavoro(contextmenu_el,'presenzemensili',contextmenu_recordid,contextmenu_fieldid) }},
                    <?php
                    }
                    ?>
            }
            // there's more, have a look at the demos and docs...
        });
        
        
            
        <?php
        }
        ?>
                
                $('.results_rows').scrollTop(<?=$scrollTop?>);
                
                setTimeout(function(){
                    $('#test_table').find('.thlink').width( $('#results_table').find('.thlink').width());
                    <?php
                    for($i=0;$i<=50;$i++)
                    {
                    ?>
                            $('#test_table').find('.th<?=$i?>').width( $('#results_table').find('.th<?=$i?>').width());
                    <?php
                    }
                    ?>
                },2000);
                
                
                
                //$('#results_table').find('thead').css('opacity', '0');
    })
    
    function results_set_autocomplete_linked(el)
    {
        console.info('fun:results_set_autocomplete_lookuptable');
        var results=$(el).closest('.results');
        var master_tableid=$(results).data('tableid');
        
        var results_record=$(el).closest('.results_record');
        var master_recordid=$(results_record).data('recordid');
        
        var results_field=$(el).closest('.results_field');
        var fieldid=$(results_field).data('fieldid');
        
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var linkedmaster_tableid=$(el).data('tableid');
        
        var id=$(el).attr('id');
        
        $(el).autocomplete({
        source: function( request, response ) {
                    $.ajax( {
                        url: controller_url +'ajax_get_records_linkedmaster/'+linkedmaster_tableid+'/'+master_tableid,
                        dataType: "json",
                        data: {
                          term: request.term
                        },
                        success: function( data ) {
                          response( data );
                        },
                        error:function(){
                            alert('errore');
                        }
                    } );
                  },
        minLength: 2,
        appendTo: $(fieldcontainer),
        position: {  
            collision: "flip"
              },
        select: function( event, ui ) {
            event.preventDefault();
            $(el).val(ui.item.label);
            var serialized_data=[];
            serialized_data.push({name: 'tableid', value: master_tableid});
            serialized_data.push({name: 'recordid', value: master_recordid});
            serialized_data.push({name: 'fieldid', value: fieldid});
            serialized_data.push({name: 'value', value: ui.item.value});

            $.ajax({
                url: controller_url+'ajax_results_field_changed',
                data: serialized_data,
                type: 'post',
                success:function(data){
                    $.toast('Salvato');
                    
                },
                error:function(){
                    alert('errore');
                }
            });
            
            
        },
        create: function( event, ui ) {
        },
      });
    }
    
    function results_field_changed(el)
    {

        var results=$(el).closest('.results');
        var tableid=$(results).data('tableid');
        var results_record=$(el).closest('.results_record');
        var recordid=$(results_record).data('recordid');
        var results_field=$(el).closest('.results_field');
        var fieldid=$(results_field).data('fieldid');
        var value=$(el).val();
        var serialized_data=[];
        serialized_data.push({name: 'tableid', value: tableid});
        serialized_data.push({name: 'recordid', value: recordid});
        serialized_data.push({name: 'fieldid', value: fieldid});
        serialized_data.push({name: 'value', value: value});

        $.ajax({
            url: controller_url+'ajax_results_field_changed',
            data: serialized_data,
            type: 'post',
            success:function(data){
                $.toast('Salvato');
                if(tableid=='rapportidilavoro')
                {
                    var el_duratalavoro=$(results_record).find('.class_duratalavoro').find('input');
                    $(el_duratalavoro).val(data);
                }
                
                /*if((fieldid=='orainiziomattina')||(fieldid=='orafinemattina')||(fieldid=='orainiziopomeriggio')||(fieldid=='orafinepomeriggio'))
                {
                    var orainiziomattina=$(results_record).find('.class_orainiziomattina').find('input').val();
                    var orafinemattina=$(results_record).find('.class_orafinemattina').find('input').val();
                    var orainiziopomeriggio=$(results_record).find('.class_orainiziopomeriggio').find('input').val();
                    var orafinepomeriggio=$(results_record).find('.class_orafinepomeriggio').find('input').val();
                    
                    var duratalavoro=calc_duratalavoro(orainiziomattina,orafinemattina,orainiziopomeriggio,orafinepomeriggio);
                    
                    var el_duratalavoro=$(results_record).find('.class_duratalavoro').find('input');
                    $(el_duratalavoro).val(duratalavoro);
                    //results_field_changed(el_duratalavoro)
                }*/
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    
    
    function apri_schedarecord_rapportino(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_get_recordid_rapportino/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                var recordid_rapportino=data;
                apri_scheda_record(el,'rapportidilavoro',recordid_rapportino,'popup','standard_dati');



            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function apri_schedarecord_dipendente(el,recordid_presenzemensili)
    {
        $.ajax({

            url: controller_url+"ajax_get_recordid_dipendente/"+recordid_presenzemensili,
            dataType:'html',
            success:function(data){
                var recordid_dipendente=data;
                apri_scheda_record(el,'dipendenti',recordid_dipendente,'popup','standard_dati');



            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function apri_note_dipendente(el,recordid_presenzemensili)
    {
        
       
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_note_dipendente/"+recordid_presenzemensili,
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });

            
        
    }
    
    function apri_zonelavorative_dipendente(el,recordid_presenzemensili)
    {
        
       
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_zonelavorative_dipendente/"+recordid_presenzemensili,
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });

            
        
    }
     
   
    
    function apri_rapportino_settimanale(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $('.bPopup_generico').html('Caricamento');
        bPopup_generico=$('.bPopup_generico').bPopup();
        $.ajax({

            url: controller_url+"ajax_load_custom_3p_rapportino/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $('.bPopup_generico').html(data);
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_apri_situazione(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $('.bPopup_generico').html('Caricamento');
        bPopup_generico=$('.bPopup_generico').bPopup();
        $.ajax({

            url: controller_url+"ajax_load_custom_3p_situazione/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $('.bPopup_generico').html(data);
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    
    function note_mouseoverBAK(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $('.bPopup_generico').html('Caricamento');
        bPopup_generico=$('.bPopup_generico').bPopup();
        $.ajax({

            url: controller_url+"ajax_load_custom_3p_note/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $('.bPopup_generico').html(data);
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    function note_mouseover(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $(el).parent().find('.note_preview').html('Caricamento...');
        $(el).parent().find('.note_preview').show();
        $.ajax({

            url: controller_url+"ajax_load_custom_3p_note_preview/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).parent().find('.note_preview').html(data);
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    function note_mouseout(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $(el).find('.note_preview').hide();
    }
    
    function contextMenu_apri_note(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $('.bPopup_generico').html('Caricamento');
        bPopup_generico=$('.bPopup_generico').bPopup();
        $.ajax({

            url: controller_url+"ajax_load_custom_3p_note/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $('.bPopup_generico').html(data);
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_apri_rapportodilavoro(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_get_recordid_rapportodilavoro/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                apri_scheda_record(this,'rapportidilavoro',data,'popup','standard_dati','risultati_ricerca','scheda')
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_segna_festivita(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_custom_3p_segna_festivita/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).data('original-html',$(el).html());
                $(el).html('X');
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    function contextMenu_annulla_festivita(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_custom_3p_annulla_festivita/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).html($(el).data('original-html'));
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    
    function contextMenu_colora_verde(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_custom_3p_colora_verde/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).data('original-background-color',$(el).css('background-color'));
                $(el).css('background-color','green');
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_annulla_verde(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({
            url: controller_url+"ajax_custom_3p_annulla_verde/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).css('background-color',$(el).data('original-background-color'));
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    function contextMenu_colora_festivopagato(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_custom_3p_colora_festivopagato/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).data('original-background-color',$(el).css('background-color'));
                $(el).css('background-color','#41ce41');
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_annulla_festivopagato(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({
            url: controller_url+"ajax_custom_3p_annulla_festivopagato/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).css('background-color',$(el).data('original-background-color'));
            },
            error:function(){
                alert('errore');
            }
        });
    }
    
    function contextMenu_colora_viola(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({

            url: controller_url+"ajax_custom_3p_colora_viola/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).data('original-background-color',$(el).css('background-color'));
                $(el).css('background-color','violet');
            },
            error:function(){
                alert('errore');
            }
        });

    }
    
    function contextMenu_annulla_viola(el,tableid,recordid_presenzemensili,fieldid_giorno)
    {
        $.ajax({
            url: controller_url+"ajax_custom_3p_annulla_viola/"+recordid_presenzemensili+"/"+fieldid_giorno,
            dataType:'html',
            success:function(data){
                $(el).css('background-color',$(el).data('original-background-color'));
            },
            error:function(){
                alert('errore');
            }
        });
    }
</script>

<div id="results_<?=$tableid?>" class="results" style="<?=$results_height?>;<?=$font?>;width: 100%;" data-tableid="<?=$tableid?>">
    <input id="results_currentpage" type="hidden" value="<?=$page?>">
    <table id="test_table">
            <thead>
                <tr>
                    <th class="thlink" ></th>
                    <?php
                    foreach ($columns as $key => $column) {
                        if($column['fieldtypeid']!='Sys')
                        {
                            $span='';
                            if($column['id'][0]=='g')
                            {
                                if(strlen($column['desc'])==1)
                                {
                                    $span='xxxx';
                                }
                                else
                                {
                                    $span='xxx';
                                } 
                            }

                        ?>
                    <th class="th<?=$key?>" style=""><?=$column['desc']?><span style="color: transparent"><?=$span?></span></th>
                        <?php
                        }

                    }
                    ?>
                </tr>
            </thead>
        </table>
    <div class="results_rows" style="<?=$table_container_height?>;<?=$overflow?>">
        
        <table id="results_table" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="thlink"></th>
                    <?php
                    foreach ($columns as $key => $column) {
                        if($column['fieldtypeid']!='Sys')
                        {
                            $span='';
                            if($column['id'][0]=='g')
                            {
                                if(strlen($column['desc'])==1)
                                {
                                    $span='xxxx';
                                }
                                else
                                {
                                    $span='xxx';
                                } 
                            }

                        ?>
                    <th class="th<?=$key?>" style=""><?=$column['desc']?><span style="color: transparent"><?=$span?></span></th>
                        <?php
                        }

                    }
                    ?>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($records as $key => $record) {
                    $record_css=$record[2];
                    $recordid=$record[0];
                ?>
                <tr class="results_record" data-recordid="<?=$recordid?>" style="">
                    <?php
                    //CUSTOM 3P
                    if($tableid=='contrattofornitura')
                    {
                    ?>
                    <td style="cursor: pointer;height: 40px;" onclick="ajax_compilazione_contratto_fornitura(this,'<?=$record[0]?>','popup','standard_dati','risultati_ricerca','scheda');"><i class="fas fa-external-link-alt" title="Apri"></i></td>

                    <?php
                    }
                    else
                    {
                    ?>
                    <td style="cursor: pointer;height: 40px;" onclick="apri_scheda_record(this,'<?=$tableid?>','<?=$record[0]?>','popup','standard_dati','risultati_ricerca','scheda');"><i class="fas fa-external-link-alt" title="Apri"></i></td>
                    <?php
                    }
                    ?>
                    <?php
                    foreach ($record as $key => $value) {
                        $sync=false;
                        $cell_css='';
                        $fieldid=$columns[$key]['id'];
                        $type=$columns[$key]['results_fieldtypeid'];
                        $memo='';
                        if($type!='Sys')
                        {
                        //CUSTOM 3P INIZIO
                            $onclick_function='';
                            if($cliente_id=='3p')
                            {
                                if($tableid=='rapportidilavoro')
                                {
                                    if(($fieldid=='duratalavoromodificata')&&($value=='Si'))
                                    {
                                        $cell_css=$cell_css.';border-bottom: 2px solid black;';
                                    }
                                    if(($fieldid=='visto')&&($value=='B'))
                                    {
                                        $cell_css=$cell_css.';background-color: gray';
                                    }
                                    if(($fieldid=='visto')&&($value=='A'))
                                    {
                                        $cell_css=$cell_css.';background-color: yellow';
                                    }
                                    if(($fieldid=='visto')&&($value=='D'))
                                    {
                                        $cell_css=$cell_css.';background-color: LightGreen';
                                    }
                                }
                                if($tableid=='contratti')
                                {
                                    if(($fieldid=='visto')&&($value=='A'))
                                    {
                                        $cell_css=$cell_css.';background-color: yellow';
                                    }
                                    if(($fieldid=='visto')&&($value=='D'))
                                    {
                                        $cell_css=$cell_css.';background-color: LightGreen';
                                    }
                                    if($fieldid=='media_oresup2')
                                    {
                                        if(isnotempty($value))
                                        {
                                            if($value[0]=='-')
                                            {
                                                $cell_css=$cell_css.';background-color: OrangeRed';
                                            }
                                            else
                                            {
                                                $cell_css=$cell_css.';background-color: LightGreen';
                                            }
                                        }
                                        
                                    }
                                }
                                if($tableid=='presenzemensili')
                                {
                                    if (strpos($value, '@sync') !== false)
                                    {
                                        $sync=true;
                                        $value= str_replace('@sync', '', $value);
                                    }
                                    $value_array= explode("#", $value);
                                    $colore_font='';
                                    $colore_sfondo='';
                                    $colore_bordo='';
                                    $memo='';
                                    if(array_key_exists(0, $value_array))
                                    {
                                        $value=$value_array[0];
                                    }
                                    if(array_key_exists(1, $value_array))
                                    {
                                        $colore_font=$value_array[1];
                                    }
                                    if(array_key_exists(2, $value_array))
                                    {
                                        $colore_sfondo=$value_array[2];
                                    }
                                    $memo='';
                                    $bordinotifica='';
                                    if(array_key_exists(3, $value_array))
                                    {
                                        $parametro3=$value_array[3];
                                        if(strpos($parametro3, 'Memo:') !== false) 
                                        {
                                            $memo=$value_array[3];
                                            if(array_key_exists(4, $value_array))
                                            {
                                                $bordinotifica=$value_array[4];
                                            }
                                        }
                                        else
                                        {
                                            $bordinotifica=$value_array[3];
                                        }
                                    }
                                    
                                    $memo= str_replace('Memo:', '', $memo);
                                    
                                    
                                    if($colore_font=='Nero')
                                    {
                                       $cell_css=$cell_css.'color:black;'; 
                                    }
                                    if($colore_font=='Rosso')
                                    {
                                       $cell_css=$cell_css.'color:OrangeRed;'; 
                                    }
                                    if($colore_sfondo=='Trasparente')
                                    {
                                       $cell_css=$cell_css.'background-color: transparent;'; 
                                    }
                                    if($colore_sfondo=='Grigio')
                                    {
                                       $cell_css=$cell_css.'background-color: #dedede;'; 
                                    }
                                    if($colore_sfondo=='Arancione')
                                    {
                                       $cell_css=$cell_css.'background-color: Coral;'; 
                                    }
                                    if($colore_sfondo=='Giallo')
                                    {
                                       $cell_css=$cell_css.'background-color: Yellow;'; 
                                    }
                                    if($colore_sfondo=='Azzurro')
                                    {
                                       $cell_css=$cell_css.'background-color: #B2DAF3;'; 
                                    }
                                    if($colore_sfondo=='Verde')
                                    {
                                       $cell_css=$cell_css.'background-color: #84C260;'; 
                                    }
                                    if($colore_sfondo=='VerdeChiaro')
                                    {
                                       $cell_css=$cell_css.'background-color: LightGreen;'; 
                                    }
                                    if($colore_sfondo=='VerdeScuro')
                                    {
                                       $cell_css=$cell_css.'background-color: Green;'; 
                                    }
                                    if($colore_sfondo=='Viola')
                                    {
                                       $cell_css=$cell_css.'background-color: Violet;'; 
                                    }
                                    
                                    if($colore_sfondo=='Rosso')
                                    {
                                       $cell_css=$cell_css.'background-color:OrangeRed;'; 
                                    }
                                    
                                    if($bordinotifica=='')
                                    {
                                       //$cell_css=$cell_css.'border-color:transparent;'; 
                                    }
                                    if($bordinotifica=='start')
                                    {
                                       $cell_css=$cell_css.'border-left:4px solid #f97b91;border-top:4px solid #f97b91;border-bottom:4px solid #f97b91;'; 
                                    }
                                    if($bordinotifica=='up_down')
                                    {
                                       $cell_css=$cell_css.'border-top:4px solid #f97b91;border-bottom:4px solid #f97b91;'; 
                                    }
                                    if($bordinotifica=='end')
                                    {
                                       $cell_css=$cell_css.'border-right:4px solid #f97b91;border-top:4px solid #f97b91;border-bottom:4px solid #f97b91;'; 
                                    }
                                    if($bordinotifica=='start_end')
                                    {
                                       $cell_css=$cell_css.'border-left:4px solid #f97b91;border-right:4px solid #f97b91;border-top:4px solid #f97b91;border-bottom:4px solid #f97b91;'; 
                                    }
                                
                                    if(($fieldid[0]=='g')&&($fieldid[strlen($fieldid) - 1]=='d'))
                                    {
                                        
                                            $onclick_function="apri_rapportino_settimanale(this,'presenzemensili','$recordid','$fieldid')";
                                        
                                    }
                                    if(($fieldid=='id')||($fieldid=='cognome')||($fieldid=='nome'))
                                    {
                                        $onclick_function="apri_schedarecord_dipendente(this,'$recordid')";
                                    }
                                    if($fieldid=='zonelavorative')
                                    {
                                        $onclick_function="apri_zonelavorative_dipendente(this,'$recordid')";
                                    }
                                    if($fieldid=='note')
                                    {
                                        $onclick_function="apri_note_dipendente(this,'$recordid')";
                                    }
                                }
                                
                            }
                            
                            



                        //CUSTOM 3P FINE
                        
                    ?>

                    <td class="results_field class_<?=$fieldid?>" style="position: relative; <?=$record_css?><?=$cell_css?> ;" data-fieldid="<?=$fieldid?>" onclick="<?=$onclick_function?>" >
                       <?php
                       if($memo!='')
                       {
                       ?>
                        <div style="position: absolute;top: 0px;right: 0px;overflow: visible;" onmouseout="note_mouseout(this)">
                            <div class="corner" onmouseover="note_mouseover(this,'presenzemensili','<?=$recordid?>','<?=$fieldid?>')" style="position: relative;z-index: 20;">
                                
                            </div>
                            <textarea  class="note_preview" style="display: none;position: absolute;bottom: 0px;right: -200px;height: 40px;width: 200px;background-color: white;border: 1px solid black;z-index: 100;word-wrap:break-word;">
                                
                            </textarea>
                        </div>
                        <?php
                       }
                       ?>
                        <?php
                        if(($table_settings['risultati_edit']=='true')&&($contesto!='stampa_elenco'))
                        {
                        ?>
                            
                                <?php
                                if($type=='Seriale')
                                {
                                ?>
                                   <?=$value?>
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Numero')
                                {
                                ?>
                                <input type="number" value="<?=$value?>">

                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Parola')
                                {
                                    /*
                                     <div class="fieldcontainer">
                                        <input id="<?= $recordid."_".$fieldid ?>" type="text" value="<?=$value?>">-->
                                        <script type="text/javascript">
                                            $('#<?= $fieldid . "-layer" ?>').ready(function(){
                                                  set_autocomplete_lookuptable($('#<?= $recordid."_".$fieldid ?>'));
                                            });
                                        </script>
                                    </div>
                                     */
                                ?>
                                <input id="" type="text" value="<?=$value?>">


                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Lookup')
                                {
                                    $options=$columns[$key]['options'];
                                ?>
                                    <!--<select>
                                        <option selected></option>
                                        <?php
                                        foreach ($options as $key => $option) {
                                            $selected='';
                                            if($option['itemdesc']==$value)
                                            {
                                                $selected='selected';
                                            }
                                        ?>
                                            <option <?=$selected?>><?=$option['itemdesc']?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>-->
                                <div><?=$value?></div>
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Memo')
                                {
                                ?>
                                <input class="input2update" type="text" value="<?=$value?>">
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Data')
                                {
                                ?>
                                   <?=$value?>
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='Ora')
                                {
                                ?>
                                   <input class="input2update" type="time" value="<?=$value?>">
                                <?php
                                }
                                ?>
                        
                                   
                                   
                                <?php
                                if($type=='record_preview')
                                {
                                    $img_src= server_url()."record_preview/$tableid/$recordid.jpg";
                                    if(!file_exists("../JDocServer/record_preview/$tableid/$recordid.jpg"))
                                    {
                                        $img_src=base_url()."assets/images/document.png";
                                    }
                                ?>
                                   <div><img src="<?=$img_src?>" style="height: 100%;"></div>
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='linked')
                                {
                                    $value_array= explode("|:|", $value);
                                    $value_description=$value_array[0];
                                    $value_recordid=$value_array[1];
                                    $value_tableid=$value_array[2];
                                    $display_linked_input='';
                                    if($value_description!='')
                                    {
                                        $display_linked_input='display: none;';
                                    }
                                ?>
                                   <!--<div class="value_linkedmaster" onclick="apri_scheda_record(this,'<?=$value_tableid?>','<?=$value_recordid?>','popup','standard_dati');"><?=$value_description?></div>-->

                                <div class="fieldcontainer">
                                    <input id="<?=$recordid.$fieldid?>" class="linked_input" style="<?=$display_linked_input?>" type="text" value="<?=$value_description?>" data-recordid="<?=$value_recordid?>" data-tableid="<?=$value_tableid?>">
                                    <?php
                                    if($value_description!='')
                                    {
                                        $underline='text-decoration: underline';
                                        if($contesto=='stampa_elenco')
                                        {
                                           $underline=''; 
                                        }
                                    ?>
                                            <span class="linked_link" style="<?=$underline?>;cursor: pointer" onclick="apri_scheda_record(this,'<?=$value_tableid?>','<?=$value_recordid?>','popup','standard_dati');"><?=$value_description?></span>
                                            <div style="display: inline-block;width: 20px;height: 20px;">
                                                <i class="btn_fa fas fa-edit" title="Modifica" style="opacity: 0" onclick="edit_linked(this)"></i>
                                            </div>  
                                    <?php
                                    }
                                    ?>

                                        <div style="display: inline-block;width: 20px;height: 20px;">
                                            <i class="btn_fa fas fa-times" title="Svuota" style="opacity: 0" onclick="results_annulla_linked(this)"></i>
                                        </div> 
                                </div>

                                <?php
                                }
                                ?>
                            
                        <?php
                     }
                     else
                     {
                        if($sync)
                        {
                        ?>
                                   <i class="fas fa-sync"></i>
                        <?php
                        }
                        else
                        {
                     ?>
                                <?php
                                if($type=='record_preview')
                                {
                                    $img_src= server_url()."record_preview/$tableid/$recordid.jpg";
                                    if(!file_exists("../JDocServer/record_preview/$tableid/$recordid.jpg"))
                                    {
                                        $img_src=base_url()."assets/images/document.png";
                                    }
                                ?>
                                   <div><img src="<?=$img_src?>" style="height: 100%;"></div>
                                <?php
                                }
                                ?>
                                <?php
                                if($type=='linked')
                                {
                                    $value_array= explode("|:|", $value);
                                    $value=$value_array[0];
                                }
                                ?>
                                <div><?=$value?></div>
                    <?php
                         }
                     }
                        ?>       
                        </td>
                    <?php
                        }
                    }
                    ?>
                </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div style="height: 50px;">
        <ul class="pagination justify-content-center" id="pagination" style="text-align: center" ></ul>
        <div style="width: 100%;margin: auto" >
            <ul class="pagination justify-content-center" id="pagination" style="text-align: center" ></ul>
        </div>
    </div>
    
</div>