<?php
//$records_linkedmaster=$data['records_linkedmaster'];
$linkedmastertableid=$data['linkedmastertableid'];
$mastertableid=$data['mastertableid'];
$recordid=$data['linkedmaster_recordid'];
$funzione=$data['funzione'];
$keyfieldlink=$data['keyfieldlink'];
?>
<script type="text/javascript">
    $('#records_linkedmaster_<?=$mastertableid?>_<?=$recordid?>_<?=$linkedmastertableid?>').ready(function() {
     var records_linkedmaster=$('#records_linkedmaster_<?=$mastertableid?>_<?=$recordid?>_<?=$linkedmastertableid?>');  
     var fieldcontainer_edit=$(records_linkedmaster).find('.fieldcontainer_edit');
      $( "#records_linkedmaster_field_<?=$mastertableid?>_<?=$linkedmastertableid?>" ).autocomplete({
      source: function( request, response ) {  // less easy!
          var parent_value='';
          var master_recordid=$(fieldcontainer_edit).closest('.scheda_record').data('recordid');
          
            <?php
            if($linkedmastertableid=='qualifiche')
            {
            ?>   
               parent_value=$('#records_linkedmaster_field_professioni_ccl').val();
            <?php
            }
            ?>
            $.getJSON("<?php echo site_url("sys_viewcontroller/ajax_get_records_linkedmaster/$linkedmastertableid/$mastertableid"); ?>", 
            { term: request.term,parent_value:parent_value,master_recordid:master_recordid}, response)
      },
      minLength: 2,
      appendTo: fieldcontainer_edit,
      position: {  
          collision: "none"
            },
      select: function( event, ui ) {
          var el=$( "#records_linkedmaster_field_<?=$mastertableid?>_<?=$linkedmastertableid?>" );
          $(el).val(ui.item.value);
          var table_container=$(el).closest('.table_container');
          $(table_container).find('.nessun_valore').html('');
          //ajax_load_fields_record_linkedmaster(el,'<?=$data['linkedmastertableid'] ?>','<?=$data['funzione']?>')
          $(el).blur();
          ajax_load_fissi_linkedmaster(el,'<?=$data['linkedmastertableid'] ?>');
          //salva_record(el,'continua');
          var scheda_record=$(el).closest('.scheda_record');
          var recordid=$(scheda_record).data('recordid');
            var tableid=$(scheda_record).data('tableid')
          <?php
          if(($table_settings['autosave']=='true')&&(($funzione=='modifica')||($funzione=='scheda'))) 
          {
          ?>
            autosave_linkedmaster(el,tableid,recordid);
        <?php
          }
          ?>
            
      },
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
            var li=$( "<li>" );
            $(li).append( "<a></a>");
            var a=$(li).find('a');
            if((item.icon!='')&&(item.icon!=null))
            {
                $(a).append("<img src='"+item.icon+"'></img>");
            }
            $(a).append(item.label);
            if((item.desc!='')&&(item.desc!=null))
            {
                $(a).append('<br>');
                $(a).append(item.desc);
            }
            
            
            //$(a).append(item.icon);
            $(li).appendTo( ul );
            return li;
            };
    });
    
    function search_<?=$linkedmastertableid?>(el,term){
        var block_dati_labels_container=$(el).closest('.block_dati_labels_container');
        var block_dati_labels_container_height=$(block_dati_labels_container).height();
        $(block_dati_labels_container).scrollTo(el,300,
            {
                offset: -(block_dati_labels_container_height/3),
              }
            );
        $( "#records_linkedmaster_field_<?=$mastertableid?>_<?=$linkedmastertableid?>" ).val('');
        
        
        $( "#records_linkedmaster_field_<?=$mastertableid?>_<?=$linkedmastertableid?>" ).autocomplete( "search", term );
        
    }
    
</script> 
<div id="records_linkedmaster_<?=$mastertableid?>_<?=$recordid?>_<?=$linkedmastertableid?>" style="margin-top: 10px;">
<div style="float: left;">
    <div class="btn_fa fa fa-square" style="color: #2987e3"></div><?=  strtoupper($label)?>
</div>
<div id="records_linkedmaster_<?=$mastertableid?>_<?=$recordid?>_<?=$linkedmastertableid?>" class=" table_container tablecontainer toupdate ui-widget records_linkedmaster" style="width: 80%;float: right">

    <?php
    if(($funzione=='modifica')||($funzione=='inserimento')||($funzione=='scheda'))
    {
    ?>
    <div class="fieldcontainer fieldcontainer_edit first" style="background-color: white;margin: auto;width: 85%;">
        <div class="fieldValueContainer" style="float: left;width: 100%;position: relative">
            <?php
            if($recordid=='null')
            {
                $recordid='';
            }
            ?>
            <?php
            if($table_settings['edit']=='true')
            {
            ?>
                <input id="records_linkedmaster_field_<?=$mastertableid?>_<?=$linkedmastertableid?>" class="autocompleteInput field records_linkedmaster_field_<?=$linkedmastertableid?>" name="tables[<?=$mastertableid?>][insert][linkedmaster][<?=$linkedmastertableid?>][value]" style="position: absolute;top: 0px;right: 0px;" onclick="search_<?=$linkedmastertableid?>(this,'sys_recent')" value="<?=$recordid?>" data-origine="linkedmaster">
            <?php
            }
            ?>
        </div>
        <!--<span class="autocompleteSpan" onclick="search_<?=$linkedmastertableid?>('sys_all')" style="float: left">▼</span>-->
        <!--<div class="btn_custom btn_plus_popup tooltip" title="Nuovo" onclick="apri_scheda_record(this,'<?=$linkedmastertableid?>','null','popup','allargata','records_linkedmaster');" style="float: right;margin-right: 5px;" ><div class="plus_popup">+</div></div>-->
        <?php
        //custom dimensione immobiliare
        if(($linkedmastertableid=='contatti')&&($mastertableid=='immobili_richiesti'))
        {
        ?>
            <button class="btn_fab menu_list_button" style="float: right" onclick="add_table(this,'add')"><i class="material-icons">add</i></button>
        <?php
        }
        else
        {
        ?>
            <?php
            if(($linkedmastertableid!='immobili')||($mastertableid!='immobili_richiesti'))
            {
            ?>
                <?php
                if($table_settings['edit']=='true')
                {
                ?>
                    <button class="btn_fab menu_list_button" style="float: right" onclick="apri_scheda_record(this,'<?=$linkedmastertableid?>','null','popup','allargata','records_linkedmaster');"><i class="material-icons">add</i></button>
                <?php
                }
                ?>
            <?php
            }
            ?>
        <?php
        }
        ?>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    
        
        <?php
        if($recordid!='')
        {
        ?>
            <div class="fissi_record_linkedmaster fissi_container" onclick="apri_scheda_record(this,'<?=$linkedmastertableid?>','<?=$recordid?>','popup','allargata','records_linkedmaster')" style="font-size: 12px;">
                <?=$data['fissi'];?>
            </div>
        <?php
        }
        else
        {
        ?>
            <!--<div class="nessun_valore" style="margin: 0px;margin-left: 50px;">Nessun valore</div>-->
            <?php
            if(($linkedmastertableid=='contatti')&&($mastertableid=='immobili_richiesti'))
            {
            ?>
                <div class="nessun_valore" style="margin: 0px;margin-left: 50px;">Ricerca in rubrica</div>
            <?php
            }
            ?>
            <?php
            if(($linkedmastertableid=='immobili')&&($mastertableid=='immobili_richiesti'))
            {
            ?>
                <div class="nessun_valore" style="margin: 0px;margin-left: 50px;">Ricerca nel database immobili</div>
            <?php
            }
            ?>
            <div class="fissi_record_linkedmaster fissi_container"  style="display: none;" >
            </div>
        <?php
        }
        
        ?>

    
    <?php
    if(($funzione=='modifica'))
    {
    ?>
        <!--<div class="btn_scritta tooltip fa-floppy-o" title="salva" style="float: right;display: none;" onclick="salva_modifiche_record(this,'<?=$mastertableid?>','<?=$recordid?>')" ></div>-->
    <?php
    }
    ?>
    <div class="clearboth"></div>
    
    

</div>
<div class="clearboth">
</div>
</div>