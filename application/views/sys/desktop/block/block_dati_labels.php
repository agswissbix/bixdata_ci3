<?php
$tableid=$data['tableid'];
$recordid=$data['recordid'];
$funzione=$data['funzione'];
$scheda_container=$data['scheda_container'];
$block_container=$data['block_container'];
$first_to_open_class='';
$first_to_open=true;
$cliente_id=$data['settings']['cliente_id'];
$id_block_dati_labels="block_dati_$tableid"."_"."$recordid"."_"."$funzione"."_".time();
$counter=0;
?>
<script type="text/javascript">
    var tablesloading=false
    var waittablesloading_counter=0;
    $( "#<?=$id_block_dati_labels?>" ).ready(function(){
        var funzione='<?=$funzione?>';
        var recordid='<?=$recordid?>';
        var $block_dati_labels=$( "#<?=$id_block_dati_labels?>" );
        console.info(this);
        $('#ocrs_container').find('.fieldtoblur').blur(function() { 
            field_blurred($(this));
        });
       /* $($block_dati_labels).find('.accordion').togglepanels(function(){
            alert('toggled');
            if((funzione=='inserimento')||(funzione=='scheda')||(funzione=='modifica'))
            {
                var tablelabel_0=$($block_dati_labels).find('#tablelabel_0');
                $(tablelabel_0).click(); 
            }

            if(funzione=='ricerca')
            {
                var tablelabel_1=$($block_dati_labels).find('#tablelabel_1');
                $(tablelabel_1).click(); 
            }
        });*/
        
        $($block_dati_labels).find('h3').hover(function() { 
            $(this).toggleClass("ui-state-hover"); 
        })
        console.info('recordid');
        console.info(funzione);
        console.info(recordid);
        if((funzione!='ricerca'))
        {
            $( "#<?=$id_block_dati_labels?>" ).find('.label_linkedmaster').each(function(i){
                    labelclick(this,false);
            })
        }
        if((funzione=='inserimento')||(funzione=='scheda')||(funzione=='modifica'))
        {
            /*var tablelabel_0=$($block_dati_labels).find('#tablelabel_0');
            console.info('tablelabel_0');
            console.info(tablelabel_0);
            var label_linkedmaster=$(tablelabel_0).hasClass( 'label_linkedmaster' );
            if(!label_linkedmaster)
            {
                $(tablelabel_0).click(); 
            }*/
            var first_to_open=$($block_dati_labels).find('.first_to_open');
            setTimeout(function(){
                labelclick(first_to_open,false);
            },100);
            
            //$(first_to_open).click(); 
        }

        if(funzione=='ricerca')
        {
            var tablelabel_tutti=$($block_dati_labels).find('#tablelabel_tutti');
            //var tablelabel_1=$($block_dati_labels).find('#tablelabel_1');
            //var label_linkedmaster=$(tablelabel_1).hasClass( 'label_linkedmaster' );
            /*if(!label_linkedmaster)
            {
                labelclick(tablelabel_1,false);
            }*/
            //labelclick(tablelabel_tutti,false);
            
           
        }
        
   //var first_to_open=$($block_dati_labels).find('.first_to_open');
    //labelclick(first_to_open,false);
    
    var linked_label_opened=$($block_dati_labels).find('.linked_label_opened').each(function(x){
        var label=this;
        setTimeout(function(){
                labelclick(label,false);
            },100);
    });
    
    
    var block_dati_labels_container=$( "#<?=$id_block_dati_labels?>" ).closest('.block_dati_labels_container');
    console.info('prescroll');
    console.info(block_dati_labels_container);
    $(block_dati_labels_container).scrollTo(0);
    });
</script>

<div id="<?=$id_block_dati_labels?>" class="block_container block_fields block block_dati_labels" data-tableid="<?=$data['tableid']?>" data-recordid="<?=$data['recordid']?>" data-funzione="<?=$funzione?>" data-scheda_container="<?=$scheda_container?>" data-block_container="<?=$block_container?>" style="overflow: none">
    <div id="labels" class="accordion labels ui-accordion ui-accordion-icons ui-widget ui-helper-reset" style="height: 4000px;width: 100%" >
        <?php 
                $tabindex_counter=0;
                    if($funzione=='ricerca')
                    {
                        $fieldname= "ocr[f_0]" ;
                        $fieldid="ocr-f_0" ;
                    ?>
                <!--<h3 id="tablelabel_<?=$tabindex_counter?>" class="tablelabel <?=$class_label_vuota?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom">Ocr</h3>
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
                </div>-->
                <?php 
                
                } ?>
        
                <?php
               /* if(($funzione=='ricerca')&&($cliente_id='Work&Work'))
                {
                ?>
                <div id="tablelabel_tutti" class="tablelabel ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>" style="display: none;" >Ricerca in tutti i campi</div>
                    <div id="tutti_tables_container" class="tables_container ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" data-counter="0" data-table_index="0" data-label_index="<?=$tabindex_counter?>" data-linkedtableid="<?=$data['tableid']?>" data-mastertableid="<?=$data['tableid']?>" data-funzione="<?=$data['funzione']?>" data-recordid="<?=$recordid?>" data-type="tutti"  data-labelname="tutti" style="display: none">
                       <?php
                       //echo $mastertable;
                       ?>
                   </div>
                <?php
                }*/
                ?>
                <?php
                
                // ciclo su tutti i campi raggruppati per label. $key_label è la label che raggruppa i campi. $fields_group è l'insieme dei campi sotto una label
                foreach ($data['labels'] as $key_label => $fields_group) 
                {
                    $loaded='false';
                    if(array_key_exists('prefilledlabel_block', $fields_group))
                    {
                        $loaded='true';
                    }
                    $first='';
                    $counter=$counter+1;
                    if($counter==1)
                    {
                       $first='first_label'; 
                    }
                    ?>
                <?php
                $style_linkedmaster='';
                            if($fields_group['type']=='linkedmaster')
                            {  
                                $style_linkedmaster='display:none';
                                if($funzione=='ricerca')
                                {
                                    $style_linkedmaster='display:block';
                                }
                            }
                            ?>
                <div id="label_table_<?=$fields_group['tableid']?>" class="<?=$first?> label_table" >
                    <?php
                    //CUSTOM WORK&WORK
                        $class_label_vuota="";
                        if(($fields_group['counter']==0)||($fields_group==null))
                        {
                            //$class_label_vuota="label_vuota";
                            $class_label_vuota="";
                        }
                        ?>
                        <!-- titolo della label per il blocco accordion -->
                        <?php
                        if(($fields_group['type']!='linked')||($recordid!='null')||($funzione!='inserimento'))
                        {
                        ?>
                            <?php
                            if($fields_group['type']=='linkedmaster')
                            {   
                                $label_pieces=  explode(':', $fields_group['label']);
                                
                                if(count($label_pieces)>1)
                                {
                            ?>
                        <div id="tablelabel_<?=$tabindex_counter?>" class="tablelabel  <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom " onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>" style="<?=$style_linkedmaster?>">
                                    <?php
                                    if($funzione=='scheda')
                                    {
                                    ?>
                                    <div class="btn_custom btn_open_popup tooltip" title="apri scheda" onclick="apri_scheda_record(this,'<?=$fields_group['tableid']?>','<?=$fields_group['linkedmaster_recordid']?>','popup','allargata')" style="float: left;margin-right: 5px;" ><div class="open_popup"></div></div>
                                    <?php
                                    }
                                    ?>
                                    <span><?=$fields_group['description']?>:</span>
                                    <span style="color: black !important;font-weight: normal !important;"><?=$label_pieces[1]?></span>
                                </div>
                            <?php
                                }
                                else
                                {
                                    $linkedmaster_label_opened_class='';
                                    //CUSTOM 3p inizio
                                    if(($cliente_id=='3p')&&($fields_group['tableid']=='dipendenti')&&($funzione=='ricerca'))
                                    {
                                        $linkedmaster_label_opened_class='linked_label_opened';
                                    }
                                    //CUSTOM 3p fine
                                    ?>
                                    <div id="tablelabel_<?=$tabindex_counter?>" class="<?=$linkedmaster_label_opened_class?> tablelabel  <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>" style="<?=$style_linkedmaster?>">
                                        <div class="label_icona" style="float: left"></div>
                                        <div class="label_title"><?=$fields_group['description']?></div>
                                        <div class="clearboth"></div>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            {
                                $new_class='';
                                if($fields_group['status']=='new')
                                {
                                    $new_class='new_records';
                                }
                                $linked_label_opened_class='';
                                $first_to_open_class='';
                                if($first_to_open)
                                {
                                    //$first_to_open_class='first_to_open';
                                    $first_to_open=false;
                                    $linked_label_opened_class='linked_label_opened';
                                }
                                //CUSTOM DImensione nizio
                                if($fields_group['tableid']=='agenda')
                                {
                                    $linked_label_opened_class='linked_label_opened';
                                }
                                //CUSTOM Dimensione fine
                                
                                
                                
                                if(($fields_group['linked_label_opened']=='true')&&($fields_group['counter']>0)&&($funzione=='scheda'))
                                {
                                    $linked_label_opened_class='linked_label_opened';
                                }

                                ?>
                                    <div id="tablelabel_<?=$tabindex_counter?>"  class="tablelabel <?=$new_class?> <?=$first_to_open_class?>  <?=$linked_label_opened_class?> <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom"  data-opened="false" data-loaded="<?=$loaded?>" data-table_index="<?=$tabindex_counter?>" style="<?=$style_linkedmaster?>" >
                                        <div class="label_icona" style="float: left"></div>
                                        <div onclick="labelclick(this);" style="width: calc(100% - 70px);float: left;">
                                        <?php
                                        if($fields_group['type']=='linked')
                                        {
                                        ?>
                                            <div class="label_title"><?= $fields_group['description'] ?> (<?= $fields_group['counter'] ?>)</div>
                                            <!--<button class="btn_fab menu_list_button" style="float: right;" onclick="apri_scheda_record(this,'<?=$fields_group['tableid']?>','null','popup','allargata','linked_table')"><i class="material-icons">add</i></button>-->
                                            <!--<button class="btn_fab menu_list_button" style="float: right;" onclick="add_linked(this)"><i class="material-icons">add</i></button>-->
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                           <div class="label_title"><?= $fields_group['description'] ?></div> 
                                        <?php
                                        }
                                        ?>
                                        </div>
                                        <?php
                                        if(($fields_group['type']=='linked')&&(($funzione=='scheda')||($funzione=='modifica')))
                                        {
                                        ?>
                                        <div class="tablelabel_btn" style="float: left;width: 50px;display: none;line-height: 14px;" >
                                                <?php
                                                if($table_settings['edit']=='true')
                                                {
                                                ?>
                                                <button class="btn_fab menu_list_button" style="display: block" onclick="insert_linked(this);" ><i class="material-icons">add</i></button>
                                                <?php
                                                }
                                                ?>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if($funzione=='ricerca')
                                        /*{
                                        ?>
                                        <div class="tablelabel_btn" style="float: right;width: 50px;display: none;line-height: 14px;" onclick="showall_fields(this);">
                                            Tutti <br/> i campi
                                        </div>
                                        <?php
                                        }*/
                                        ?>
                                        <div class="clearboth"></div>
                                    </div>
                                <?php
                                //$tabindex_counter=$tabindex_counter+1;

                            }
                            ?>
                        
                        <!-- Container di un insieme di tabelle dello stesso tipo-->
                        <?php
                        $table_label=str_replace(' ', '', $fields_group['label']);
                        $table_label=str_replace('(', '_', $table_label);
                        $table_label=str_replace(')', '_', $table_label);
                        ?>
                        <div id="<?=$tableid?>_<?=$recordid?>_<?=$table_label  ?>_tables_container" class="tables_container ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" data-counter="0" data-table_index="0" data-label_index="<?=$tabindex_counter?>" data-linkedtableid="<?=$fields_group['tableid']?>" data-mastertableid="<?=$data['tableid']?>" data-funzione="<?=$data['funzione']?>" data-recordid="<?=$recordid?>" data-type="<?=$fields_group['type']?>"  data-labelname="<?= $fields_group['label'] ?>" style="display: none">
                           <?php
                           //echo $mastertable;
                           if(array_key_exists('prefilledlabel_block', $fields_group))
                           {
                               echo $fields_group['prefilledlabel_block'];
                           }
                           ?>
                        </div>

                        <?php
                        }
                    $tabindex_counter=$tabindex_counter+1;
                ?>
                    </div>
                <?php
                
                }
                ?>  
                        <?php
                        if(false)
                        {
                        ?>
                            <div id="tablelabel_ocr" class="tablelabel ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>"  >
                                <div class="label_icona" style="float: left"></div>
                                <div class="label_title" style="text-transform: lowercase">Allegati</div>
                                <div class="clearboth"></div>
                            </div>
                            <!-- Container di un insieme di tabelle dello stesso tipo-->
                            <div id="ocr_tables_container" class="tables_container ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" data-counter="0" data-table_index="0" data-label_index="<?=$tabindex_counter?>" data-linkedtableid="<?=$data['tableid']?>" data-mastertableid="<?=$data['tableid']?>" data-funzione="<?=$data['funzione']?>" data-recordid="<?=$recordid?>" data-type="ocr"  data-labelname="ocr" style="display: none">
                               <?php
                               //echo $mastertable;
                               ?>
                           </div>
                        <?php
                        }
                        ?>
    </div>
    <form class="form_dati_labels" style="border: 1px solid red;min-height: 100px;min-width: 100px; display: none;">
    </form>
        
        
</div>
    
