<?php
$fieldid=0;
?>
<script type="text/javascript">
    
    
    $("#impostazioni_archivio_campi").ready(function(){
    $( ".connectedSortable" ).sortable({
                      connectWith: ".connectedSortable",
                      update: function( event, ui ) {
                          update_order_impostazioni_archivio_campi(event, ui);
                      }
                    });
        
        

    });
    
    
</script>
<div id="impostazioni_archivio_campi" class="block" data-field_total="<?=  count($fields)?>">
    <div style="width: 15%;float: left;overflow: hidden">
        <div class="connectedSortable">
            <div class="impostazioni_fieldcontainer card scheda" style="padding-left: 20px;margin: 5px;">
                testo
                <input class="order" type="text" name="fields[insert][fieldcounter][fieldorder]" value=""> 
            </div>
        </div>
    </div>
    <div style="width: 80%;float: left">
        <form id="impostazioni_archivio_campi_form">
            <div style="height: 90%;overflow: scroll">    
            <?php
            foreach ($fields_ordered_groupby_label as $label => $fields_label) 
            {
            ?>
                <div style="" onclick="$(this).next().toggle()">
                    <?=$label?>
                </div>
                <div>
                <?php
                foreach ($fields_label as $sublabel => $fields_sublabel) 
                {
                ?>
                    <div style="" onclick="$(this).next().toggle()">
                        <?=$sublabel?>
                    </div>
                    <div class="connectedSortable">
                    <?php
                    foreach ($fields_sublabel as $field_key => $field) 
                    {
                        $fieldid=$field['fieldid'];
                    ?>
                        <div class='impostazioni_fieldcontainer_container'>
                            <div class="impostazioni_fieldcontainer " style="margin: 5px;background-color: white; border: 1px solid #eeeeee" >
                                <input class="order" type="text" name="fields[update][<?=$fieldid?>][fieldorder]" value="<?=$field['ordine']?>" style="width: 25px"> 
                                <input type="text" name="fields[update][<?=$fieldid?>][fieldid]" value="<?=$field['fieldid']?>">
                                <input type="text" name="fields[update][<?=$fieldid?>][description]" value="<?=$field['description']?>">

                                <?php
                                if($field['campiricerca'])
                                {
                                    $checked='checked';
                                }
                                else
                                {
                                    $checked='';
                                }

                                ?>
                                <!--Ricerca:<input type="checkbox" <?=$checked?> name="fields[update][<?=$fieldid?>][campiricerca]">-->

                                <div class="btn_scritta" style="float: right;" onclick="show_field_settings(this)">Opzioni</div>
                                <?php
                                if($field['lookuptableid']!='')
                                {
                                    ?>
                                <div class="btn_scritta" style="float: right;" onclick="manage_lookuptable(this,'<?=$field['lookuptableid']?>')">valori</div>
                                <?php
                                }
                                ?>

                                <div class="field_settings" style="display: none;">
                                    <select name="fields[update][<?=$fieldid?>][showedbyfieldid]" class="option_showedbyfieldid" data-loaded="false" onclick="load_options_fields(this,'<?=$field['tableid']?>','lookup')" onchange="option_showedbyfield_changed(this)" style="min-width: 50px;">
                                        <option value="<?=$field['showedbyfieldid']?>"><?=$field['showedbyfieldid']?></option>
                                    </select>
                                    <select name="fields[update][<?=$fieldid?>][showedbyvalue]" class="option_showedbyvalue" data-loaded="false" onclick="load_options_lookupitems(this,'<?=$tableid?>')" style="min-width: 50px;">
                                        <option selected value="<?=$field['showedbyvalue']?>"><?=$field['showedbyvalue']?></option>
                                    </select>
                                    <select name="fields[update][<?=$fieldid?>][sublabel]" class="option_sublabel" data-loaded="false" onclick="load_options_sublabel(this,'<?=$tableid?>')" style="min-width: 50px;">
                                        <option selected value="<?=$field['sublabel']?>"><?=$field['sublabel']?></option>
                                    </select>

                                    <?php
                                    $settings=$field['settings'];
                                    foreach ($settings as $setting_key => $setting) {
                                    ?>
                                        <br />
                                        <?=$setting_key?>: 
                                        <select name="fields[update][<?=$fieldid?>][settings][<?=$setting_key?>]">
                                            <?php
                                                $selected='';
                                                foreach ($setting['options'] as $option_key => $option) 
                                                {
                                                    $selected='';
                                                    if($setting['currentvalue']==$option_key)
                                                    {
                                                        $selected='selected';
                                                    }
                                                ?>
                                            <option <?=$selected?> value="<?=$option_key?>" ><?=$option?></option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                    <?php
                                    }
                                    ?>
                                </div>    
                            </div>
                        </div>
                    <?php
                    $fieldid++;
                    }
                    ?>
                    </div>
                <?php
                }
                ?>
                </div>
            <?php    
            }
            ?>
            </div>
        </form>
    </div>
    <div onclick="salva_impostazioni_archivio_campi(this,'<?=$idarchivio?>')">Salva</div>
    
</div>