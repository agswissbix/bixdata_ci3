<?php
$funzione=$data['funzione'];
$tableid=$data['tableid'];
$query=$data['query'];
if(array_key_exists('recordid', $data))
{
    $recordid=$data['recordid'];
}
else
{
    $recordid='null';
}
?>
<script type="text/javascript">
    


    
    
    
    function range_field_onclick(el){
        var field_container=$(el).parent();
        var field_container_cloned=field_container.clone();
        var name_original;
        var counter=field_container.parent().attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        field_container.parent().attr("data-counter",counter_modified);
        var index_modified="f_"+counter_modified;
        var name_modified;
        var id_original;
        var id_modified;
        
        var index_original;
        index_original=field_container.attr("data-index");
        field_container_cloned.attr("data-index",index_modified);
        
        id_original=field_container_cloned.attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        field_container_cloned.attr("id",id_modified);
            
        field_container_cloned.find("input").each(function(i) {
            name_original=$(this).attr("name");
            name_modified=name_original.replace(index_original,index_modified);
            $(this).attr("name",name_modified);
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
        });
        field_container_cloned.find("select").each(function(i) {
            name_original=$(this).attr("name");
            name_modified=name_original.replace(index_original,index_modified);
            $(this).attr("name",name_modified);
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
        });
        field_container_cloned.find("textarea").each(function(i) {
            name_original=$(this).attr("name");
            name_modified=name_original.replace(index_original,index_modified);
            $(this).attr("name",name_modified);
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
        });
        field_container.find('[name*="param"]').attr("value", "from");
        field_container_cloned.find('[name*="param"]').attr("value", "to");
        field_container_cloned.find('[name*="value"]').val("");
        field_container_cloned.attr('data-cloned', 'true');
        field_container.before('<span style="margin-left:130px">Da</span>');
        field_container.after(field_container_cloned);
        field_container.after('<span style="margin-left:130px">A</span>');
        field_container.parent().attr('data-multiplefields','true');
        field_container.parent().css("border", "1px dotted #CCCCCC");
        field_container.parent().css("margin-top", "5px");
        field_container.parent().css("margin-bottom", "5px");
        $(field_container_cloned).find('.fieldtoblur').blur(function() { 
            field_blurred($(this));
        });
        apply();
        
        container.before('<span style="margin-left:130px">Da</span>');
        container.after('<span style="margin-left:130px">A</span>');
        
    }
    
    
  
    


$( "#fields_<?=$funzione?>_<?=$recordid?>" ).ready(function(){
    console.info(this);
    $('#ocrs_container').find('.fieldtoblur').blur(function() { 
        field_blurred($(this));
    });
           //accordion per i blocchi di campi
    /*$( ".accordion" ).accordion({ 
        heightStyle: "content",
        active:1,
        collapsible: true,
        activate: function( event, ui ) {
            panel_activate(event, ui);
            
        },
        create: function( event, ui ) {
            var header=ui['newHeader'];
            labelclick(header);
        }
    });*/
    $( "#fields_<?=$funzione?>_<?=$recordid?>" ).find('.accordion').togglepanels();

 
   
    //$( ".datepicker" ).datepicker();
    $( "#fields_<?=$funzione?>_<?=$recordid?>" ).find('#scheda_campi_content').scrollTop(0);
    
    <?php 
    if($funzione=='ricerca')
    {
    ?>
            $( "#fields_<?=$funzione?>_<?=$recordid?>" ).find('#tablelabel_1').click();
    <?php
    }
    else
    {
    ?>
        $( "#fields_<?=$funzione?>_<?=$recordid?>" ).find('#tablelabel_0').click();
    <?php
    }
    ?>
    
    //$( "#autosearch" ).buttonset();

    
});

</script>



<div id="fields_<?=$funzione?>_<?=$recordid?>" class="block_container block_fields" data-tableid="<?=$data['tableid']?>" data-recordid="<?=$data['recordid']?>" data-funzione="<?=$funzione?>" style="overflow: none">
    <div class="develop">block-fields</div>
    <?php
    if(($funzione=='ricerca')||($funzione=='inserimento'))
    {
    ?>
    <div id="menu_scheda_campi" class="menu_mid ui-widget-header">
        <div style="float: left;margin-right: 20px;">
        DATI
        </div>
        <?php
        if($funzione=='ricerca')
        {
        ?>
        <div style="float: left;line-height: 20px;font-weight: normal">

        Auto: &nbsp;
        </div>
        <div id="autosearch" style="float: left;line-height: 20px;font-weight: normal" >
            <input type="radio" id="autosearchTrue" name="autosearch" checked="checked"  /><label for="autosearchTrue" style="width: 30px;">On</label>
            <input type="radio" id="autosearchFalse" name="autosearch" /><label for="autosearchFalse" style="width: 30px;">Off</label>
        </div>
        
        <?php
        }
        ?>
        
        <?php
        if(($funzione=='ricerca')||($funzione=='inserimento')){
        ?>   
            <div class="tooltip btn_icona btn_right" style="float: right;" title="mostra il riepilogo"  onclick="show_riepilogo(this)"></div> 
            <div class="tooltip btn_icona btn_reset" style="float: right;margin-right: 5px;;" title="azzera i parametri di ricerca"  onclick="reload_fields(this,'<?=$tableid?>','<?=$funzione?>');"></div> 
        <?php 
        }
        ?>
        

        <br/><br/>

        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    <div class="fields_container fields_container_<?=$funzione?> block_content" style="overflow-y: scroll;float: left;margin-left: 3px;">
    <div id="fields" class="accordion " style="height: 2000px;width: 100%">
        <?php 
                $tabindex_counter=0;
                    if($funzione=='ricerca')
                    {
                        
                        $fieldname= "ocr[f_0]" ;
                        $fieldid="ocr-f_0" ;
                    ?>
                <h3>Ocr</h3>
                <div id="ocrs_container" data-counter="0" class="label_container tablescontainer tables_container" data-labelName="ocr" data-table_index="<?=$tabindex_counter?>">
                    <div id="ocr-container" class="fieldscontainer table_container" data-counter="0" style="padding: 5px;padding-left: 26px;"> 
                        <div class='fieldlabel'>ocr</div>
                        <div id="ocr-container-0" class="fieldcontainer" data-index="f_0">
                        <div class="fieldconnector fieldor" style="display: none">oppure</div>
                            <div class="fieldValueContainer fieldValueContainerEmpty">
                                <span class='fieldLabel' style='margin-left:10px;display: none'>OCR</span>
                                <input  class="field fieldtoblur" style="margin-left: 10px;" id="<?= $fieldid."-value" ?>" name=<?= $fieldname."[value]" ?> type="text" placeholder="ocr"  >
                            </div>
                            <input type="hidden" id="<?= $fieldid."-table" ?>" name=<?= $fieldname."[table]" ?> value='ocr-table'>
                            <input type="hidden" id="<?= $fieldid."-type" ?>" name=<?= $fieldname."[type]" ?> value='parola-testolibero'>
                            <input type="hidden" id="<?= $fieldid."-label" ?>" name=<?= $fieldname."[label]" ?> value=''ocr-label'>
                            <input type="hidden" id="<?= $fieldid."-param" ?>" name=<?= $fieldname."[param]" ?> value=''>
                            <input type="hidden" id="<?= $fieldid."-field_counter" ?>" name=<?= $fieldname."[field_counter]" ?> value="0">
                            <button type="button" class="btn-small-rounded btn_field_hidden btn-or" onclick="or_field_onclick(this)"><div style="font-size: 8px;">OR</div></button> 
                            <button  type="button" class="btn-delete btn-small-rounded btn_field_hidden" onclick="delete_field_onclick(this)" style="display: none;"><div style="font-size: 24px;line-height: 12px;">-</div></button>

                        </div>
                        <div class="clearboth"></div>
                    </div>
                </div>
                <?php 
                $tabindex_counter=$tabindex_counter+1;
                } ?>
                <?php 
                // ciclo su tutti i campi raggruppati per label. $key_label è la label che raggruppa i campi. $fields_group è l'insieme dei campi sotto una label
                
                foreach ($data['fields'] as $key_label => $fields_group) 
                    { 
                    //CUSTOM WORK&WORK
                        $class_label_vuota="";
                        if(($fields_group['counter']==0)||($fields_group==null))
                        {
                            $class_label_vuota="label_vuota";
                        }
                        ?>
                        <!-- titolo della label per il blocco accordion -->
                        <?php
                        if(($fields_group['type']!='linked')||($recordid!='null')||($funzione!='scheda'))
                        {
                        ?>
                        <div id="tablelabel_<?=$tabindex_counter?>" class="tablelabel <?=$class_label_vuota?>" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>"  ><?= $fields_group['label'] ?></div>
                                <!-- Container di un insieme di tabelle dello stesso tipo-->
                               <div id="<?= str_replace(' ', '', $fields_group['label']) ?>_tables_container" class="tables_container" data-counter="0" data-table_index="0" data-label_index="<?=$tabindex_counter?>" data-linkedtableid="<?=$fields_group['tableid']?>" data-mastertableid="<?=$data['tableid']?>" data-funzione="<?=$data['funzione']?>" data-recordid="<?=$recordid?>" data-type="<?=$fields_group['type']?>"  data-labelname="<?= $fields_group['label'] ?>">
                                   <?php
                                   //echo $mastertable;
                                   ?>
                               </div>

                        <?php
                        }
                    $tabindex_counter=$tabindex_counter+1;
                }
                ?>
    
        
    
    </div>
    </div>
    <div id="block_riepilogo" class="blocco" style="height: 95%;display: none;float: left;">
        <h3> Riepilogo</h3>
        <button style="float: right;width: 20px;height: 20px;opacity: 0;" onclick="$('#query').show()"></button>
        
        <div id="riepilogo" class="riepilogo" style=" overflow-y: scroll; height: 80%;">
            <form id="form_riepilogo" class="form_riepilogo" >
                <textarea id="query" name="query" style="width: 100%;height: 200px;overflow: scroll;display: block;">
                    <?=$query?>
                </textarea>
                
                
                <input id="file_allegato_hidden" class="file_allegato" type="text" style="display: none" value="" >
            </form> 
        </div>
        
    </div>
    
</div>
    
