<?php
$funzione = $data['funzione'];
$tableid = $data['tableid'];
$recordid = $data['recordid'];
$type=$data['type'];
$table_index=$data['table_index'];
$table_param=$data['table_param'];
$scheda_container=$data['scheda_container'];
$label=$data['label'];
$settings=$data['settings'];
$cliente_id=$settings['cliente_id'];
if($table_param=='null')
{
        $table_param='';
}

if($funzione=='ricerca')
{
    $hidden_table_param_name="tables[" . $tableid . "][search][t_".$table_index."][table_param]";
    $hidden_table_type_name="tables[" . $tableid . "][search][t_".$table_index."][table_type]";
}
else
{
   $hidden_table_param_name="tables[" . $tableid . "][insert][t_".$table_index."][table_param]"; 
   $hidden_table_type_name="tables[" . $tableid . "][insert][t_".$table_index."][table_type]";
}
?>
<script type="text/javascript">
$('#<?= $tableid.'-'.$label.'-'.$table_index ?>').ready(function(){
   var table_menu=$(this).find('.table_menu');
   var block_dati_labels_width=$(table_menu).closest('.block_dati_labels').width();
   //$(table_menu).width(block_dati_labels_width-12);
   //$(this).find('.table_menu').width(table_menu_width);
   var tables_container=$('#<?= $tableid.'-'.$label.'-'.$table_index ?>').closest('.tables_container');
    var last=$(tables_container).find('.last');
    $(last).keydown(function (e) {
        if (e.which == 9)
            {
                e.preventDefault();
                //$('#testfocus').focus();
               last_tab(this); 
            }
    });
    
     //$(".tooltip").tooltip();
    
});

</script>
<div id="<?= $tableid.'-'.$label.'-'.$table_index ?>" class=" table_container tablecontainer table_container_<?=$funzione?> toupdate ui-widget block_table table_block" data-index="t_<?=$table_index?>" data-funzione="<?=$funzione?>" data-tableid='<?=$tableid?>' data-recordid='<?=$recordid?>'  > 
    <input type="hidden" id="hidden_table_param" class="toupdate" name=<?= $hidden_table_param_name ?> value='<?=$table_param?>'>
    <input type="hidden" id="hidden_table_type" class="toupdate" name=<?= $hidden_table_type_name ?> value='<?=$type?>'>
    <?php
    if(($funzione=='scheda')||($funzione=='modifica'))
    {
    ?>    
    <div class="table_menu" style="width: 100%">
            <?php
            if($cliente_id!='Work&Work')
            {
            ?>
                <?php
                if(($type=='linked')&&(($funzione=='scheda')||($funzione=='modifica'))) 
                {
                ?>
                    <i class="btn_popup_linkedmaster btn_fa fas fa-arrow-right tooltip" title="apri scheda a destra" onclick="apri_scheda_record(this,'<?=$tableid?>','<?=$recordid?>','right','standard_allegati')" style="float: right;" ></i>
                <?php
                }
                ?>
            <?php
            }
            ?>
            
                    
            <?php
            if(($type=='linked')&&(($funzione=='scheda')||($funzione=='modifica')))
            {
            ?>
                <?php
                if($table_settings['delete']=='true')
                {
                ?>
                    <div type="button" class="btn_fa fas fa-trash-alt tooltip" style="float: right" title="elimina"  onclick="elimina_linked_record(this,'<?=$tableid?>','<?=$recordid?>')" ></div>
                <?php
                }
                ?>
            <?php
            }
            ?>

             
                <?php
            if((($funzione=='scheda')||($funzione=='modifica'))&&(($type=='master')||($type=='linked')))
            {
            ?>
            <!--<div type="button" class="btn_fa fa fa-pencil tooltip" style="float: right;" title="modifica" onclick="ajax_load_table(this,'<?=$tableid?>','<?=$label?>','modifica','<?=$recordid?>')"> </div>-->
                <?php
               if(($type=='linked')&&(($funzione=='scheda')||($funzione=='modifica'))) 
               {
               ?>
                   <i class="btn_popup_linkedmaster btn_fa fas fa-external-link-alt tooltip" title="apri scheda popup" onclick="apri_scheda_record(this,'<?=$tableid?>','<?=$recordid?>','popup','allargata')" style="float: left;" ></i>
               <?php
               }
               ?>
                   
                <?php
                if($cliente_id=='3p')
                {
                   if($funzione=='scheda')
                   {
                ?>
                   <div style="font-size: 12px;text-align: left;" class="btn_scritta tuttiicampi" onclick="ajax_load_table(this,'<?=$tableid?>','<?=$label?>','modifica','<?=$recordid?>')">TUTTI I CAMPI</div>                    
                    <script type="text/javascript">
                    $(document).ready(function(){   
                        
                            //ajax_load_table($('rapportidilavoro'),'<?=$tableid?>','<?=$label?>','modifica','<?=$recordid?>');
                            ajax_load_table(this,'<?=$tableid?>','<?=$label?>','modifica','<?=$recordid?>');
                            
                    });
                    </script>    
                <?php
                    }
                } else {
                ?>
                   <?php
                   if($funzione=='scheda')
                   {
                   ?>
                   <div style="font-size: 12px;text-align: left;" class="btn_scritta tuttiicampi" onclick="ajax_load_table(this,'<?=$tableid?>','<?=$label?>','modifica','<?=$recordid?>')">Mostra tutti i campi</div>
                   <?php
                   }
                   ?>
               
               <?php
               }
               ?>
                
            <?php
            }
            ?>
               
             <?php
            if(($type=='linked')&&((($funzione=='scheda')||($funzione=='modifica'))&&($tableid=='timesheet')))
            {
            ?>
                    <div type="button" class="btn_fa fa fa-print tooltip" style="float: right" title="Stampa rapportino"  onclick="stampa_rapportino_pdf(this,'<?=$recordid?>')" ></div>
                    
            <?php
            }
            ?>
            <?php

            if(((($funzione=='modifica'))||(($funzione=='inserimento')&&(($type=='linked'))))&&($scheda_container!='scheda_dati_inserimento'))
            {
            ?>
             <!--<div class="btn_fab btn_fab_save" style="float: left;"><div class="material-icons md_save" title="salva" onclick="salva_linked_record(this,'<?=$tableid?>','<?=$recordid?>')" data-origine='linked_table'>save</div></div>-->
             <?php
            }
            ?>
             <?php
              if(($funzione=='modifica')&&(($type=='master')||($type=='linked')))
            {
            ?>
                    <div style="font-size: 12px;text-align: left;" class="btn_scritta tuttiicampi" onclick="ajax_load_table(this,'<?=$tableid?>','<?=$label?>','scheda','<?=$recordid?>')">Nascondi campi vuoti</div>
             <?php
            }
            ?>

                 <?php
            if(($type=='linked')&&(($funzione=='scheda')||($funzione=='modifica'))&&($cliente_id=='Work&Work'))
            {
            ?>
             <div type="button" class="btn_scritta tooltip" title="Valida" onclick="valida_record(this,'<?=$tableid?>','<?=$recordid?>')" style="float: right;">V</div>   
             <?php
            }
             ?>
            
             <?php
            if($cliente_id!='Work&Work')
            {
            ?>
             <?php
             if(($type=='null')&&($funzione=='scheda'))
            {
            ?>
                <div type="button" class="btn_icona btn_open tooltip" title="apri scheda" onclick="apri_linkedtable(this,'<?=$recordid?>','<?=$tableid?>','navigator_field')" style="float: right;" ></div>
            <?php
            }
            ?>
            
            <?php
            if(($type=='linkedmaster')&&($funzione=='scheda'))
            {
            ?>
                <div class="btn_custom btn_plus_popup tooltip" title="apri scheda" onclick="apri_linkedtable(this,'<?=$recordid?>','<?=$tableid?>','navigator_field')" style="float: right;" ><div class="plus_popup"></div></div>
            <?php
            }
            ?>
            <?php
            }
            ?>
        </div>
        
         
         
        
         
        
        <div class="clearboth"></div>
   
    <?php
    }
    ?>
    <?php
    if($funzione=='ricerca')
    {
    ?>
        <div class="table_menu" style="width: 100%">
            <div style="font-size: 12px;text-align: left;" class="btn_scritta tuttiicampi" onclick="showall_fields(this);">Mostra tutti i campi</div>
        </div>
        <div class="clearboth"></div>
    <?php
    }
    ?>
    <!-- Parametri della tabella -->
    <?php
    if($table_param=='and')
    {
    ?>
    <div class="fieldconnector tableand" >e anche</div>
    <?php
    }
    ?>
    <?php
    if($table_param=='or')
    {
    ?>
    <div class="fieldconnector tableor">oppure</div>
    <?php
    }
    ?>
    <?php
    //identificatore univoco della tabella nella pagina
    $tableid = $tableid . "-t_".$table_index."";
    if (($type == "linked")&&($funzione=="ricerca")) 
    {
        // bottoni e campi aggiuntivi da aggiungere a un blocco nel caso si tratti di una tabella collegata
        ?>
        <?php if ($table_index > 1) { ?>
        <div  class="btn_fa fa fa-minus"  onclick="delete_table_onclick(this)" style="float: right;opacity: 1;"></div>
        <?php } ?>
        <div style="clear: both;">
        </div>
        <?php
    }
    ?>
    <div id="<?=$recordid.'-'.$tableid ?>_container" class="toupdate fields_container" data-index="t_<?=$table_index?>" tabindex="0"> 
            <?php
            if(array_key_exists('fields', $data))
            {
                echo $data['fields'];
            }
            ?>
    </div>       
<?php
   /* if((($funzione=='modifica')||(($funzione=='inserimento')&&(($recordid!='null')||($type=='linked'))))&&($scheda_container!='scheda_dati_inserimento'))
    {
    ?>
     <div class="menu_big">       
         <div class="btn_scritta tooltip" title="salva" onclick="salva_modifiche_record(this,'<?=$tableid?>','<?=$recordid?>')" >Salva </div>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    
    if((($funzione=='modifica')||(($funzione=='inserimento')&&($type=='linked')))&&($scheda_container=='scheda_dati_inserimento'))
    {
    ?>
     <div class="menu_big">     
        <div class="btn_scritta tooltip" title="salva" >Salva </div>
        <div class="clearboth"></div>
     </div>
    <?php
    }*/
     
    ?>
        <?php
        if((($type=='linked')&&($funzione=='inserimento'))||(($type=='linked')&&($table_settings['autosave']=='false')&&(($funzione=='inserimento')||($funzione=='modifica')||($funzione=='scheda'))))
        {
        ?>
        <div class="table_menu_bottom menu_bottom">
            <!--<div class="btn_fab btn_fab_save" style="float: left;"><div class="material-icons md_save" title="salva" onclick="salva_linked_record(this,'<?=$tableid?>','<?=$recordid?>')" data-origine='linked_table'>save</div></div>-->
            <div class="btn_scritta" style="float: right;height: 18px;margin-top: 2px;" title="salva" onclick="salva_linked_record(this,'<?=$tableid?>','<?=$recordid?>')" data-origine='linked_table'>Salva</div>
            <div class="clearboth"></div>
        </div>
        <?php
        }
        ?>
</div>
    
    
    
