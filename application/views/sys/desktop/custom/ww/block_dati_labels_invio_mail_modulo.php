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

        
        $($block_dati_labels).find('h3').hover(function() { 
            $(this).toggleClass("ui-state-hover"); 
        })
        console.info('recordid');
        console.info(funzione);
        console.info(recordid);
        if((funzione=='inserimento')&&(recordid=='null'))
        {
            $( "#<?=$id_block_dati_labels?>" ).find('.label_linkedmaster').each(function(i){
                    labelclick(this,false);
            })
        }
        if((funzione=='inserimento')||(funzione=='scheda')||(funzione=='modifica'))
        {

            var first_to_open=$($block_dati_labels).find('.first_to_open');
            labelclick(first_to_open,false);
            //$(first_to_open).click(); 
        }

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

                // ciclo su tutti i campi raggruppati per label. $key_label è la label che raggruppa i campi. $fields_group è l'insieme dei campi sotto una label
                foreach ($data['fields'] as $key_label => $fields_group) 
                    {
                    $first='';
                    $counter=$counter+1;
                    if($counter==1)
                    {
                       $first='first_label'; 
                    }
                    ?>
                <div id="label_table_<?=$fields_group['tableid']?>" class="<?=$first?>">
                    <?php
                    //CUSTOM WORK&WORK
                        $class_label_vuota="";
                        if(($fields_group['counter']==0)||($fields_group==null))
                        {
                            $class_label_vuota="label_vuota";
                        }
                        ?>
                        <!-- titolo della label per il blocco accordion -->
                        <?php
                        if(true)
                        {
                        ?>
                            <?php
                            if($fields_group['type']=='linkedmaster')
                            {   
                                $label_pieces=  explode(':', $fields_group['label']);
                                
                                if(count($label_pieces)>1)
                                {
                            ?>
                                <div id="tablelabel_<?=$tabindex_counter?>" class="tablelabel  <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom " onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>">
                                    <span><?=$label_pieces[0]?>:</span>
                                    <span style="color: black;font-weight: normal !important;"><?=$label_pieces[1]?></span>
                                </div>
                            <?php
                                }
                                else
                                {
                                ?>
                                <div id="tablelabel_<?=$tabindex_counter?>" class="tablelabel  <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>">
                                    <div class="label_icona" style="float: left"></div>
                                    <div class="label_title"><?=$label_pieces[0]?></div>
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
                                if($first_to_open)
                                {
                                    $first_to_open_class='first_to_open';
                                    $first_to_open=false;
                                }
                                else
                                {
                                    $first_to_open_class='';
                                }

                                ?>
                                    <div id="tablelabel_<?=$tabindex_counter?>" class="tablelabel <?=$new_class?> <?=$first_to_open_class?> <?=$class_label_vuota?> label_<?=$fields_group['type']?> ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom" onclick="labelclick(this);" data-opened="false" data-loaded="false" data-table_index="<?=$tabindex_counter?>"  >
                                        <div class="label_icona" style="float: left"></div>
                                        <div class="label_title"><?= $fields_group['label'] ?></div>
                                        <div class="clearboth"></div>
                                    </div>
                                <?php
                                //$tabindex_counter=$tabindex_counter+1;

                            }
                            ?>
                        
                        <!-- Container di un insieme di tabelle dello stesso tipo-->
                        <div id="<?=$tableid?>_<?=$recordid?>_<?= str_replace(' ', '', $fields_group['label']) ?>_tables_container" class="tables_container ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" data-counter="0" data-table_index="0" data-label_index="<?=$tabindex_counter?>" data-linkedtableid="<?=$fields_group['tableid']?>" data-mastertableid="<?=$data['tableid']?>" data-funzione="<?=$data['funzione']?>" data-recordid="<?=$recordid?>" data-type="<?=$fields_group['type']?>"  data-labelname="<?= $fields_group['label'] ?>" style="display: none">
                           <?php
                           //echo $mastertable;
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

    </div>
    <form class="form_dati_labels" style="border: 1px solid red;min-height: 100px;min-width: 100px; display: none;">
    </form>
        
        
</div>
    
