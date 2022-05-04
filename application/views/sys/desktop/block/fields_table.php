<?php
$funzione = $data['funzione'];
$tableid = $data['tableid'];
$recordid = $data['recordid'];
$label=$data['label'];
$type=$data['type'];
$table_index=$data['table_index'];
$tabindex=0;
$id_fields_table_container="fields_table_$funzione"."_"."$tableid"."_"."t"."_"."$table_index"."_"."$label"."_"."$recordid"."_"."container_".  time();
$sublabels=$data['sublabels'];
?>
<script type="text/javascript">
$('#<?=$id_fields_table_container?>').ready(function(){
    //$('.tooltip').tooltip();
    var block_dati_labels_container=$('#<?=$id_fields_table_container?>').closest('.block_dati_labels_container');
    var block_dati_labels_container_height=$(block_dati_labels_container).height();
    var fields_table_container=$('#<?=$id_fields_table_container?>');

    $(fields_table_container).find('.field').each(function(i){
        field_update_showedby(this);
    });
    
    $(fields_table_container).find('.field').blur(function() { 
        field_blurred($(this));
        var fieldValueContainer=$(this).closest('.fieldValueContainer');
        $(fieldValueContainer).removeClass('fieldFocused');
        <?php
        if(($table_settings['autosave']=='true')&&(($funzione=='modifica')||($funzione=='scheda')))
        {
        ?>
                autosave($(this),'<?=$tableid?>','<?=$recordid?>');
        <?php
        }
        ?>
    });

    
    $('#<?=$id_fields_table_container?>').find('.field').focus(function(){
        <?php
        if($table_settings['fields_autoscroll']=='true')
        {
        ?>
            $(block_dati_labels_container).scrollTo(this,300,
            {
                offset: -(block_dati_labels_container_height/3),
              }
            );
        <?php
        }
        ?>
        var fieldValueContainer=$(this).closest('.fieldValueContainer');
        $(fieldValueContainer).addClass('fieldFocused');
        var fieldcontainer=$(this).closest(".fieldcontainer");
        
        var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
        if(fieldtypeid=='Lookuptable')
        {
            lookuptable_click2(this,'sys_recent');
        }
        if(fieldtypeid=='Utente')
        {
            $(this).autocomplete( "search", "" );
        }
        
        
    });
    
    $('#scheda_dati_ricerca').find('input').keydown(function (e) {
    if (e.which == 13)
        {
            console.info('enter');
            field_blurred($(this));
            $('#btnCerca').click();
        }
    })
    
    $('select').keydown(function (e) {
    if (e.which == 13)
        {
            console.info('enter');
            field_blurred($(this));
            $('#btnCerca').click();
        }
    })
    $('select').focus(function(){
        var fieldValueContainer=$(this).closest('.fieldValueContainer');
        $(fieldValueContainer).addClass('fieldValueContainerFocused');
    });
    
    $('textarea').focus(function(){
        var fieldValueContainer=$(this).closest('.fieldValueContainer');
        $(fieldValueContainer).addClass('fieldValueContainerFocused');
    });
        
        $( ".field_option_list" ).menu();
        
        $('.fieldcontainer').mouseleave(function(){
            $(this).find('.field_option_list').hide();
        })
    $('select').change(function(){
    //alert('test');
        field_changed($(this));
        <?php
        if(($table_settings['autosave']=='true')&&(($funzione=='modifica')||($funzione=='scheda')))
        {
        ?>
                autosave($(this),'<?=$tableid?>','<?=$recordid?>');
        <?php
        }
        ?>
        
    })    
    
})


function svuota_campo(el,funzione)
    {
        var fieldcontainer=$(el).closest('.fieldcontainer');
        var field=$(fieldcontainer).find('.field');
        var fieldValue0=$(fieldcontainer).find('.fieldValue0');
        var fieldinput=$(fieldcontainer).find('.fieldinput');
        var field_param=$(fieldcontainer).find('.field_param');
        var field_layer=$(fieldcontainer).find('.field_layer');
        //$(field_param).val("");
        $(field).val("");
        $(fieldValue0).val("");
        field_changed(field);
        var table_container=$(el).closest(".table_container");
        var tableid=$(table_container).data('tableid');
        var recordid=$(table_container).data('recordid');
        <?php
        if(($table_settings['autosave']=='true')&&(($funzione=='modifica')||($funzione=='scheda')))
        {
        ?>
                autosave(el,tableid,recordid);
        <?php
        }
        ?>
        
        //$(fieldcontainer).hide();
        //var fieldcontainerid=$(fieldcontainer).attr('id');
        //var scheda_dati_ricerca_container=$(el).closest('.scheda_dati_ricerca_container');
        //var block_riepilogo=$(scheda_dati_ricerca_container).find('#block_riepilogo');
        //var finded=$(block_riepilogo).find('#'+fieldcontainerid);
        //$(finded).remove();
        
    }
    
</script>

<input type="hidden" id="tables-<?=$tableid?>-tutti" name="tables[<?=$tableid?>][recordid]" value="<?=$recordid?>">
<div id="<?=$id_fields_table_container?>" class="fields_table_container">
    <?php
 
        $last_field='';
        $last='';
        
        $field_counter=0;
        $fields_number=count($data['fields']);
        foreach ($data['fields'] as $key => $field) 
        {
            $allfields[$field['fieldid']]=$field;
            $fields_by_sublabel[$field['sublabel']][]=$field;
        }
        foreach ($sublabels as $key => $sublabel) {
        if(array_key_exists($sublabel['sublabelname'], $fields_by_sublabel))
        {
        ?>
        <div>
            <?php
            if($sublabel['sublabelname']!='')
            {
                $showedbyfieldid=$sublabel['showedbyfieldid'];
                $showedbyvalue=$sublabel['showedbyvalue'];
                $hidden='';
                if(($showedbyfieldid!=null)&&($showedbyvalue!=null))
                {
                    $hidden='hidden';
                    if(array_key_exists($showedbyfieldid, $allfields))
                    {
                        $showedbyfieldid_currentvalue=$allfields[$showedbyfieldid]['valuecode'][0]['code'];
                        if($showedbyfieldid_currentvalue==$showedbyvalue)
                        {
                            $hidden='';
                        }
                    }
                }
                /*if(($funzione!='scheda')&&($funzione!='modifica'))
                {
                    if($sublabel['showedbyfieldid']!='')
                    {
                        $hidden='hidden';
                    }
                    else
                    {
                        $hidden='';
                    }
                }*/
            ?>
            <div onclick="toggle_sublabel(this)" class="<?=$hidden?> sublabel" data-showedbyfieldid="<?=$sublabel['showedbyfieldid']?>" data-showedbyvalue="<?=$sublabel['showedbyvalue']?>">
                <div class="sublabel_icona" style="float: left;"></div>
                <div class="sublabel_title" style="float: left;"> <?=$sublabel['sublabelname']?></div>
                <div class="clearboth"></div>
            </div>     

            <div class="hidden">
            <?php
            }
            else
            {
            ?>
            <div>
        <?php
            }
        $fields=$fields_by_sublabel[$sublabel['sublabelname']];
        foreach ($fields as $key => $field) 
        {
           //$field['description']=$field['fieldid'];
           $field_settings=$field['settings']; 
           if ($field['lookuptableid'] != '') 
           {
               $field['fieldtypeid']='Lookuptable';
           } 
           if (array_key_exists('valuecode', $field)) 
            {
                $field_valuecodes = $field['valuecode'];
            } 
            else 
            {
                $field_valuecodes = array();
                $field_valuecodes['value']='';
                $field_valuecodes['code']='';
            }
           $hidden="";
           $readonly='';
           if(($field['showedbyfieldid']!='')&&($field_valuecodes[0]['value']==''))
           {
               $hidden='hidden';
           }
           if(element('nascosto',$field_settings)=='true')
           {
               $hidden='hidden';
           }
           if(element('calcolato',$field_settings)=='true')
           {
               
               
               if($funzione=='inserimento')
               {
                   $hidden='hidden';
               }
               if(($funzione=='modifica')||($funzione=='scheda'))
               {
                   $readonly='readonly'; 
               }
           }
           $field_valuecodes_counter=count($field_valuecodes);
           $field_valuecodes_counter=$field_valuecodes_counter-1;
        ?>
            <div id="<?= "tables-" . $field['tableid'] . "-insert-t_".$table_index."-fields-" . $field['fieldid'] . "-container" ?>" class="fieldscontainer <?=$hidden?>" data-counter="<?=$field_valuecodes_counter?>" data-showedbyfieldid="<?=$field['showedbyfieldid']?>" data-showedbyvalue="<?=$field['showedbyvalue']?>">
            <?php
            $field_index=0;
            
            $field_param='';
            if(array_key_exists('param', $field))
            {
                $field_param=$field['param'];
            }
            $field_operator='';
            if(array_key_exists('operator', $field))
            {
                $field_operator=$field['operator'];
            }
            
            
            //$field_value_exploded=  explode("|;|", $value);
            foreach ($field_valuecodes as $field_valuecode_key => $field_valuecode)  
            {
                $value=$field_valuecode['value'];
                $value=htmlentities($value);
                $hidden='';
                
                if(true)
                {
                    if($tableid=='protocollouscita')
                    {
                        if(($field['fieldid']=='oggettouscita')||($field['fieldid']=='dataregistrazione')||($field['fieldid']=='allegati')||($field['fieldid']=='creatoredocuscita'))
                        {
                            $hidden='hidden';
                        }
                    }
                    if($tableid=='protocolloentrata')
                    {
                        if(($field['fieldid']=='dataregistrazione'))
                        {
                            $hidden='hidden';
                        }
                    }
                    if($field['fieldid']=='record_preview')
                    {
                        $hidden='hidden';
                    }
                    if(($field['fieldid']=='emptyfield')||($field['fieldid']=='prot2013')||(($field['fieldid']=='annoprotocollo')||($field['fieldid']=='annonprot')||($field['fieldtypeid']=='Seriale')||($field['fieldtypeid']=='Calcolato'))&&(($funzione=='inserimento')||($funzione=='modifica')))
                    {
                        
                        $readonly='readonly'; 
                    }

                    if((($field['fieldid']=='numeroprotocollo')&&($userid!=1)&&($userid!=4))&&(($funzione=='inserimento')||($funzione=='modifica')))
                    {
                       $hidden='hidden'; 
                    }

                    if($field['fieldid']=='annonprot')
                    {
                        $hidden='hidden';
                    }
                    if(($field['fieldtypeid']=='Hidden'))
                    {
                        $hidden='hidden';
                    }
                $firstlast='';
                $length=$field['length']-1;
                $tabindex=$tabindex+1;
                if(($field['fieldid']!='tutti'))
                {
                    if($field_counter==0)
                    {
                        $firstlast='first';
                    }
                    $field_counter=$field_counter+1;
                    if($tabindex==$fields_number)
                    {
                        $firstlast='last';
                    }
                    if($fields_number==1)
                    {
                        $firstlast='first last';
                    }
                    if(($tabindex==$fields_number)&&($type=='master'))
                    {
                        $last='last';
                    }
                }
                else
                {
                    $firstlast='first';
                }
                $disabled='';
                
    //identificatore univoco del campo nella pagina
                if($funzione=='ricerca')
                {
                    if(($value!='')||($field_param!=''))
                    {
                        $fieldname = "tables[" . $field['tableid'] . "][search][t_".$table_index."][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                    }
                    else
                    {
                        $fieldname = "tables[" . $field['tableid'] . "][null][t_".$table_index."][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                    }
                    
                    $fieldid = "tables-" . $field['tableid'] . "-search-t_".$table_index."-fields-" . $field['fieldid'] . "-f_".$field_index.  time();
                }
                if($funzione=='inserimento')
                {
                    if((($value!=null)&&($value!=''))||($hidden=='hidden'))
                    {
                        $fieldname = "tables[" . $field['tableid'] . "][insert][t_".$table_index."][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                        $fieldid = "tables-" . $field['tableid'] . "-insert-t_".$table_index."-fields-" . $field['fieldid'] . "-f_".$field_index.  time();
                    }
                    else
                    {
                        $fieldname = "tables[" . $field['tableid'] . "][insert][t_".$table_index."][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                        $fieldid = "tables-" . $field['tableid'] . "-insert-t_".$table_index."-fields-" . $field['fieldid'] . "-f_".$field_index.  time();
                    }

                }
                if(($funzione=='modifica')||($funzione=='scheda'))
                {
                    $fieldname = "tables[" . $field['tableid'] . "][null][" . $recordid . "][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                    $fieldid = "tables-" . $field['tableid'] . "-update-" . $recordid . "-fields-" . $field['fieldid'] . "-f_".$field_index.  time();   
                }
                if(false/*$funzione=='scheda'*/)
                {
                    $fieldname = "tables[" . $field['tableid'] . "][null][" . $recordid . "][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                    $fieldid = "tables-" . $field['tableid'] . "-null-" . $recordid . "-fields-" . $field['fieldid'] . "-f_".$field_index.  time();  
                }
                /*if(($funzione=='inserimento')||($funzione=='modifica')||($funzione=='ricerca'))
                {
                    $fieldname = "tables[" . $field['tableid'] . "][null][t_".$table_index."][fields][" . $field['fieldid'] . "][f_0]";
                    $fieldid = "tables-" . $field['tableid'] . "-null-t_".$table_index."-fields-" . $field['fieldid'] . "-f_0";
                }*/
                if($field['fieldid']=='tutti')
                {
                  $fieldname = "tables[" . $field['tableid'] . "][tutti][t_".$table_index."][fields][" . $field['fieldid'] . "][f_".$field_index."]";
                  $fieldid = "tables-" . $field['tableid'] . "-tutti-t_".$table_index."-fields-" . $field['fieldid'] . "-f_".$field_index.  time();  
                }
            ?>

                 
                    <?php
                    $fieldcontainer='';
                    if ($field['fieldtypeid'] == 'Memo') 
                    {
                        $fieldcontainer_memo="fieldcontainer_memo";
                        $fieldvaluecontainer_memo='fieldValueContainer_memo';
                    }
                    else
                    {
                        $fieldvaluecontainer_memo=''; 
                        $fieldcontainer_memo='';
                    }
                    //verifica se solo visualizzazione
                    if(($table_settings['edit']=='false')&&($funzione != 'ricerca'))
                    {
                        if(($value!='')&&($value!=null))
                        {
                            if ($field['fieldtypeid'] == 'Data') 
                            {
                                $value=date('d/m/Y',  strtotime(str_replace('/', '-', $value)));
                            }
                            if ($field['fieldtypeid'] == 'Ora') 
                            {
                                $value=date('H:i',  strtotime(str_replace('/', '-', $value)));
                            }
                            if ($field['fieldtypeid'] == 'Calcolato') 
                            {
                                $value=date('H:i',  strtotime(str_replace('/', '-', $value)));
                            }
                            if ($field['fieldtypeid'] == 'Memo') 
                            {
                                $value=  nl2br($value);
                            }
                    ?>
                        <div id="<?= $fieldid . "-container" ?>" class="fieldcontainer fieldcontainer_view <?=$firstlast?> <?=$hidden?>" data-index="f_<?=$field_valuecode_key?>" data-fieldtypeid="<?=$field['fieldtypeid']?>">
                            <div class='fieldlabel fieldlabel_view' ><?= $field['description'] ?></div>
                            <div class="fieldContent" style="height: auto;">
                            <?php
                            if($field['fieldtypeid']=='Web')
                            {
                            ?>
                            <div class="fieldValueContainer fieldValueContainer_view " ><a href="<?=$value?>" target="_blank"><?=$value?></a></div>
                            <?php
                            }
                            else
                            {
                            ?>
                            <div class="fieldValueContainer fieldValueContainer_view <?=$fieldvaluecontainer_memo?>" onclick="$(this).toggleClass('showAll')">
                                    <?php
                                    if(false/*$field['fieldtypeid']=='Memo'*/)
                                    {
                                    ?>
                                
                                    <?php
                                    }
                                    else
                                    {
                                        echo html_entity_decode($value);
                                    }
                                    
                                    ?>
                            </div>    
                            <?php
                            }    


                            ?>
                            <div class="clearboth"></div>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    <?php
                        }
                    }
                    else
                    {
                        if(($funzione!='scheda')||(($value!='')&&($value!=null)))
                        {
                        $obbligatorio='';
                        if(($funzione=='inserimento')&&($field['settings']['obbligatorio']=='true'))
                        {
                            $obbligatorio='*';
                        }
                    ?>
                        <div id="<?= $fieldid . "-container" ?>" class="fieldcontainer fieldcontainer_edit <?=$fieldcontainer_memo?> <?=$hidden?> <?=$firstlast?>" data-index="f_<?=$field_valuecode_key?>" data-fieldtypeid="<?=$field['fieldtypeid']?>" data-fieldid="<?=$field['fieldid']?>"  style="position: relative">
                            <div style="width: 100%;">
                                <div class="or_layer field_layer" style="width: 120px;text-align: right;height: 14px;line-height: 14px;color: #467bbd;display: none;">
                                        Oppure
                                </div>
                                <!--<div class="from_layer field_layer" style="width: 120px;text-align: right;height: 25px;color: #467bbd;display: none;">
                                        Da
                                </div>
                                <div class="to_layer field_layer" style="width: 120px;text-align: right;height: 25px;color: #467bbd;display: none;">
                                        A
                                </div>    -->
                            </div>
                            <div class="clearboth"></div>
                            <?php
                            if($userid==1)
                            {
                                $field['explanation']=$field['fieldid'].": ".$field['explanation'] ;
                            }
                            
                            ?>
                            <div class='fieldlabel fieldlabel_edit tooltip' title="<?= $field['explanation'] ?>"  ><span style="color: red;font-size: 22px"><?= $obbligatorio?> </span><?=$field['description'] ?></div>
                            <div class="fieldContent">
                            <div class="fieldValueContainer fieldValueContainer_edit <?=$fieldvaluecontainer_memo?>"  style="z-index: 0;">
                                <input type="hidden" id="<?= $fieldid . "-param" ?>" class='field_param fieldInput' name=<?= $fieldname . "[param]" ?> value='<?=$field_param?>'>
                                <!--<div class="field_not_layer field_layer" onclick="not_field_onclick(this)" style="float: left;color:#467bbd;font-size: 20px;display: none;">
                                    &#x2260;
                                </div>
                                <div class="qualsiasi_layer field_layer" style="float: left;color: #467bbd;height: 25px;line-height: 25px;display: none;">
                                    Almeno un valore
                                </div>
                                <div class="nessuno_layer field_layer" style="float: left;color: #467bbd;height: 25px;line-height: 25px;display: none;">
                                    Nessun valore
                                </div>-->
                                <?php

                                if($field_param!='')
                                {
                                    $param_layer_display="";
                                    $field_display="displayNone";
                                }
                                else
                                {
                                    $param_layer_display="displayNone";
                                    $field_display="";
                                }
                                
                                ?>
                               
                               
                                <div class="param_layer field_layer <?=$param_layer_display?>" style="float: left;color: #467bbd;height: 25px;line-height: 25px;">
                                    <?php
                                    if($field_param=='currentuser')
                                    {
                                        echo 'Utente corrente';
                                    }
                                    if($field_param=='today')
                                    {
                                        echo 'Oggi';
                                    }
                                    if($field_param=='currentweek')
                                    {
                                        echo 'Questa settimana';
                                    }
                                    if($field_param=='currentmonth')
                                    {
                                        echo 'Questo mese';
                                    }
                                    ?>
                                </div>
                                
                            <?php 
                            if (array_key_exists('code', $field_valuecode)) 
                            {
                                $code = $field_valuecode['code'];
                            } else 
                            {
                                $code = '';
                            }
                            $placeholder="";
                            $description=$field['description'];
            // PAROLA                   
                            ?>
                            <?PHP
                            if (($field['fieldtypeid'] == 'Parola')||($field['fieldtypeid'] == 'Web')) {
                                if ($field['lookuptableid'] == '') 
                                    {
            // PAROLA 
                                        ?>

                                            <?php 
                                            //CUSTOM WORK&WORK

            // PAROLA (numero telefono)
                                            if(($field['fieldid']=='numero')||($field['fieldid']=='tel')||($field['fieldid']=='fax'))
                                            {
                                            ?>
                                                <input class="field fieldtoblur fieldInput fieldValue0 <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$field['length']?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                                <input type="hidden" class="field fieldtoblur fieldInput fieldValue1 <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$field['length']?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                            <?php 
                                            }  
                                            else
                                            {
            // PAROLA (testo libero)        ?>
                                            <?php
                                            if(($cliente_id=='3p')&&($field['tableid']=='richiestericercapersonale')&&($field['fieldid']=='cliente'))
                                            {
                                            ?>
                                                <script type="text/javascript">
                                                    $( "#<?=$fieldid?>-value-0" ).autocomplete({
                                                        source: function( request, response ) {  // less easy!
                                                            var parent_value='';
                                                            var master_recordid='<?=$recordid?>';

                                                              $.getJSON("<?php echo site_url("sys_viewcontroller/ajax_get_records_as_lookup/azienda/richiestericercapersonale"); ?>", 
                                                              { term: request.term,parent_value:parent_value,master_recordid:master_recordid}, response)
                                                        },
                                                        minLength: 2,
                                                        position: {  
                                                            collision: "none"
                                                              }
                                                      })
                                                </script>
                                            <?php
                                            }       
                                            ?>
                                            <?php
                                            if(($cliente_id=='3p')&&($field['tableid']=='dipendenteassociato')&&($field['fieldid']=='nomedipendente'))
                                            {
                                            ?>
                                                <script type="text/javascript">
                                                    $( "#<?=$fieldid?>-value-0" ).autocomplete({
                                                        source: function( request, response ) {  // less easy!
                                                            var parent_value='';
                                                            var master_recordid='<?=$recordid?>';

                                                              $.getJSON("<?php echo site_url("sys_viewcontroller/ajax_get_records_as_lookup/dipendenti/dipendenteassociato"); ?>", 
                                                              { term: request.term,parent_value:parent_value,master_recordid:master_recordid}, response)
                                                        },
                                                        minLength: 2,
                                                        position: {  
                                                            collision: "none"
                                                              }
                                                      })
                                                </script>
                                            <?php
                                            }       
                                            ?>
                                                <input class="field fieldtoblur fieldInput fieldValue0 <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>" <?=$readonly?>>
                                                <input type="hidden" class="field fieldtoblur fieldInput fieldValue1 <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                            <?php
                                            }
                                            ?>

                                        <input type="hidden" class="fieldInput" id="<?= $fieldid."-type" ?>" name=<?= $fieldname."[type]" ?> value='parola-testolibero'>
                                        <?php
                                    } 
                                    else 
                                    {
            // PAROLA LOOKUPTABLE
                                    ?>
                                   <!--<select  class="field fieldtoblur fieldInput fieldLookup select notselected fieldValue0 <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> placeholder="<?= $field['description'] ?>" onchange="select_changed(this)" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                        <option>loading</option>
                                    </select>
                                    <input type="hidden"  class="field fieldtoblur fieldInput notselected fieldValue1 <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> placeholder="<?= $field['description'] ?>" onchange="select_changed(this)" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                    <input type="hidden" class="fieldInput" id="<?= $fieldid."-type" ?>" name=<?= $fieldname."[type]" ?> value='parola-lookup'>
                                    <script type="text/javascript">
                                        $('#<?= $fieldid . "-value" ?>').ready(function(){
                                            ajax_get_lookuptable_items(this,'<?= $fieldid . "-value-0" ?>','<?=$field['lookuptableid']?>','<?=$field['fieldid']?>','<?=$field['tableid']?>','<?=$code?>');
                                        });
                                    </script> -->
                                    
                                        
                                    <?php
                                }
                            }
                            if($field['fieldtypeid']=='Lookuptable')
                            {
                                $field_fieldid=$field['fieldid'];
                                $field_lookuptableid=$field['lookuptableid'];
                                
                            ?> 
                                    <input  id="<?= $fieldid . "-layer"?>" class="field fieldUtente autocompleteInput fieldLayer fieldLayer_<?=$field['fieldid']?> <?=$field_display?>"   onclick="" value="<?= $value ?>" data-lastval="<?=$value?>" data-lookuptableid="<?=$field['lookuptableid']?>" data-fieldid="<?=$field['fieldid']?>" data-tableid="<?=$field['tableid']?>" data-linkfieldid="<?=$field['linkfieldid']?>" data-loaded='false' data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                    <input type="hidden" id="<?= $fieldid . "-value-0"?>" class="fieldInput fieldValue0 fiedlValue0_<?=$field['fieldid']?>"  name="<?= $fieldname . "[value][0]" ?>" value="<?= $code ?>" placeholder="<?= $field['description'] ?>" >
                                    <input type="hidden" id="<?= $fieldid . "-value-1"?>" class="fieldInput fieldValue1"  name="<?= $fieldname . "[value][1]" ?>" value="<?= $code ?>" placeholder="<?= $field['description'] ?>" >
                                    <input type="hidden" class="fieldInput" id="<?= $fieldid."-type" ?>" name=<?= $fieldname."[type]" ?> value='parola-lookup'>
                                    <script type="text/javascript">
                                        $('#<?= $fieldid . "-layer" ?>').ready(function(){
                                              set_autocomplete_lookuptable($('#<?= $fieldid . "-layer" ?>'));
                                        });
                                    </script>

                            <?php
                            }
            // DATA
                            if ($field['fieldtypeid'] == 'Data') 
                            {
                                if($value!='')
                                {
                                    $value=date('Y-m-d',  strtotime(str_replace('/', '-', $value)));
                                }
                                ?>
                                    <input type="hidden" class="field fieldInput fieldValueHidden" name="<?= $fieldname . "[value][hidden]" ?>" value="test" data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                    <input  type="date" class="field fieldtoblur fieldInput datepicker fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?>  value="<?=$value?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"   >
                                    <div class="fieldDivisore <?=$funzione?>"></div>
                                    <input  type="date" class="field fieldtoblur fieldInput datepicker fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?>  value="<?=$value?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"  >
                                    <input type="hidden"  id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='data'>

                                <?php
                            }
                            ?>
                                <?php
            // ORA 
                            if ($field['fieldtypeid'] == 'Ora') 
                            {
                                if($value!='')
                                {
                                    $value=date('H:i',  strtotime(str_replace('/', '-', $value)));
                                }
                            ?>
                                <input type="time"  class="field fieldtoblur fieldInput fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> value="<?=$value?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                <div class="fieldDivisore <?=$funzione?>"></div>
                                <input type="time"  class="field fieldtoblur fieldInput fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> value="<?=$value?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='ora'>
                            <?php
                            }
                            ?>

                            <?php

            // NUMERO
                            if ($field['fieldtypeid'] == 'Numero') {
                                $length=$field['length'];
                                $decimalposition=$field['decimalposition'];
                                $max="";
                                $step="";
                                $intlength=$length-$decimalposition;
                                for ($x=0; $x<$intlength; $x++) {
                                    $max=$max.'9';
                                }
                                if(($decimalposition!=null)&&($decimalposition!=""))
                                {
                                    if($decimalposition==0)
                                    {
                                        $step="1";
                                    }
                                    else
                                    {
                                        $decimalposition=$decimalposition-1;
                                        $step="0.";
                                        for ($x=0; $x<$decimalposition; $x++) {
                                          $step=$step."0";  
                                        }
                                        $step=$step."1";
                                    }
                                }
                                ?>
                                
                                
                                    <input  class="field fieldtoblur fieldInput fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="number" placeholder="<?= $placeholder ?>" max="<?=$max?>" step="<?=$step?>" onkeyup="if(!this.checkValidity()){this.value='';alert('Formato non corretto')};" value="<?= $value ?>"  tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                    <div class="fieldDivisore <?=$funzione?>"></div>
                                    <input  class="field fieldtoblur fieldInput fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="number" placeholder="<?= $placeholder ?>" max="<?=$max?>" step="<?=$step?>" onkeyup="if(!this.checkValidity()){this.value='';alert('Formato non corretto')};" value="<?= $value ?>"  tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"  >
                                
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='numero'>

                            <?php
                            }
                            ?>
                            <?php
            // MEMO 
                            if ($field['fieldtypeid'] == 'Memo') 
                            {
                            ?>
                                <textarea  class="field fieldtoblur fieldInput fieldValue0 <?=$firstlast?> <?=$field_display?>"  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> placeholder="<?= $placeholder ?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>" ><?=$value?></textarea>
                                <input type="hidden"  class="field fieldtoblur fieldInput fieldValue1 <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> placeholder="<?= $placeholder ?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='memo'>
                                <?php
                            }
                            ?>

                            <?php
            // UTENTE 
                            if ($field['fieldtypeid'] == 'Utente') 
                            {
                            ?>
                                <input  id="<?= $fieldid . "-layer"?>" class="field fieldUtente autocompleteInput fieldLayer <?=$field_display?>"  onclick='' value="<?= $value ?>" data-lastval="<?=$value?>" data-obbligatorio="<?=$field['settings']['obbligatorio']?>">
                                <input type="hidden" id="<?= $fieldid . "-value-0"?>" class="fieldInput fieldValue0"  name="<?= $fieldname . "[value][0]" ?>" value="<?= $code ?>" >
                                <input type="hidden" id="<?= $fieldid . "-value-1"?>" class="fieldInput fieldValue1"  name="<?= $fieldname . "[value][1]" ?>" value="<?= $code ?>" >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='utente'>
                                <script type="text/javascript">
                                  $('#<?= $fieldid . "-layer" ?>').ready(function(){
                                        set_autocomplete($('#<?= $fieldid . "-layer" ?>'));
                                    });
                                </script>
                            <?php
                            }
                            ?>

                            <?php
        //SERIALE
                            if ($field['fieldtypeid'] == 'Seriale') 
                            {
                            ?>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"   <?=$readonly?>>
                                <div class="fieldDivisore <?=$funzione?>"></div>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"  >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='seriale'>
                                <?php
                            }
                            ?>
                            <?php
        //HIDDEN
                            if ($field['fieldtypeid'] == 'Hidden') 
                            {
                            ?>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"   >
                                <div class="fieldDivisore <?=$funzione?>"></div>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"  >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='seriale'>
                                <?php
                            }
                            ?>
                            <?php
        //CALCOLATO
                            if ($field['fieldtypeid'] == 'Calcolato') 
                            {
                            ?>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue0 <?=$funzione?> <?=$firstlast?> <?=$field_display?> "  id="<?= $fieldid . "-value-0" ?>" name=<?= $fieldname . "[value][0]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>" <?=$readonly?>  >
                                <div class="fieldDivisore <?=$funzione?>"></div>
                                <input class="field fieldtoblur fieldInput fieldRange fieldValue1 <?=$funzione?> <?=$firstlast?> "  id="<?= $fieldid . "-value-1" ?>" name=<?= $fieldname . "[value][1]" ?> type="text" placeholder="<?= $placeholder ?>" value="<?= $value ?>" maxlength="<?=$length?>" tabindex="" data-last_field="<?=$last_field?>" <?=$disabled?> data-lastval="<?=$value?>"  >
                                <input type="hidden" id="<?= $fieldid."-type" ?>" class="fieldInput" name=<?= $fieldname."[type]" ?> value='calcolato'>
                                <?php
                            }
                            ?>

                            <input type="hidden" id="<?= $fieldid . "-table" ?>" class="fieldInput" name=<?= $fieldname . "[table]" ?> value=<?= $field['tableid'] ?>>
                            <input type="hidden" id="<?= $fieldid . "-label" ?>" class="fieldInput" name=<?= $fieldname . "[label]" ?> value='<?= $field['label'] ?>'>
                            
                            <input type="hidden" id="<?= $fieldid . "-operator" ?>" class='field_operator fieldInput' name=<?= $fieldname . "[operator]" ?> value=''>
                            <input type="hidden" id="<?= $fieldid . "-field_counter" ?>" class="fieldInput" name=<?= $fieldname . "[field_counter]" ?> value="0">

                            <div class="clearboth"></div>
                        </div>
                        <div class="fieldOptions" style="visibility: hidden;float: left">
                            
                            <?php 
                            if (($funzione == 'inserimento')||($funzione == 'modifica'))
                            { 
                            ?>
                            <div style="float: right;position: relative">
                                    <span class="btn_fa fas fa-ellipsis-v  menu_list_button" onclick="$(this).next().show();" style="float: left;color: #606060"></span>
                                    <ul class="" style="position: absolute;top: 10px;right: 0px;z-index: 100;background-color: white;">
                                        <?php
                                        if($field['fieldtypeid']=='Lookuptable')
                                        {
                                        ?>
                                            <li><a href="#" onclick="add_lookuptable_item(this,'<?= $fieldid . "-value-0" ?>','<?=$field['lookuptableid']?>','<?=$field['fieldid']?>','<?=$field['tableid']?>','<?=$code?>')" >Aggiungi valore</a></li>
                                            <li><a href="#" onclick="manage_lookuptable(this,'<?=$field['lookuptableid']?>')" >Gestione valori</a></li>
                                        <?php
                                        }
                                        ?>
                                        <li><a href="#" onclick="set_field_explanation(this,'<?=$tableid?>','<?=$field['fieldid']?>')" >Descrizione campo...</a></li>
                                    </ul>
                            </div>
                                
                            <?php
                            }
                            ?>    
                            <?php 
                            if ($funzione == 'ricerca') 
                            { 
                            ?>
                            <div style="float: right;position: relative">
                                <span class="btn_fa fas fa-ellipsis-v menu_list_button" onclick="$(this).next().show();" style="float: right;color: #606060">
                                    
                                </span>
                                <ul class="field_option_list" style="position: absolute;top: 10px;right: 0px;z-index: 100;">
                                        <li><a href="#" onclick="param_field_onclick(this,'notnull')" >Almeno un valore</a></li>
                                        <li><a href="#" onclick="param_field_onclick(this,'null')">Nessun valore</a></li>
                                        <li><a href="#" onclick="not_field_onclick(this)">Diverso da..</a></li>
                                        <?php
                                        if ($field['fieldtypeid'] == 'Utente')
                                        {
                                        ?>   
                                            <li><a href="#" onclick="param_field_onclick(this,'currentuser')">Utente corrente</a></li>
                                        <?php
                                        }
                                        ?>
                                            
                                        <?php
                                        if ($field['fieldtypeid'] == 'Data')
                                        {
                                        ?>   
                                            <li><a href="#" onclick="param_field_onclick(this,'today')">Oggi</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'past')">Passato</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'overpastday')">Oltre x giorni passati</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'future')">Futuro</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'currentweek')">Questa settimana</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'currentmonth')">Questo mese</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'nextday')">Prossimi x giorni</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'prevday')">Precedenti x giorni</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'nextmonth')">Prossimi x mesi</a></li>
                                            <li><a href="#" onclick="param_field_onclick(this,'prevmonth')">Precedenti x mesi</a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                            </div>
                            <?php
                            }
                            ?>
                            <div class=" btn_fa fas fa-times tooltip" title="Cancella" style="float:left;color: #606060" onclick="svuota_campo(this,'<?=$funzione?>')"></div>
                            
                            <?php
                            if($funzione=='ricerca')
                            {
                            ?>
                                <div class="btn_fa fas fa-plus tooltip" title="oppure" style="float:left;color: #606060" onclick="or_field_onclick(this)"></div>
                            <?php
                            }   
                            ?>
                            <?php
                            if((($funzione=='inserimento')||($funzione=='modifica')||($funzione=='scheda'))&&(($field['fieldtypeid'] == 'Parola')||($field['fieldtypeid'] == 'Lookuptable')||($field['fieldtypeid'] == 'Utente')))
                            {
                            ?>
                                <div class="btn_fa fas fa-plus tooltip" title="e anche" style="float:left;color: #606060 " onclick="multi_field_onclick(this)"></div>
                            <?php
                            }   
                            ?>
                            
                            <div class="clearboth"></div>
                        </div>   
                            
                    <div class="clearboth"></div>
                    </div>
                            <div class="clearboth"></div>
                    </div>
                    <?php
                    }
                    }
                    ?>
                    <div class="clearboth"></div>
                
            <?php
            }
            $field_index=$field_index+1;
            }
            ?>
         </div>           
        <?php        
        }
        
        ?>
        </div>
    </div>
    <?php
        }
        }
        ?>
            
</div>

