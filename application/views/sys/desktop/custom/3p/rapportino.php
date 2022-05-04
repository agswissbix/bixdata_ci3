<?php
$disabled='';
if($table_settings['edit']=='false')
{
   $disabled='disabled'; 
}
?>
<script type="text/javascript" src="<?php echo base_url('/assets/EasyAutocomplete/jquery.easy-autocomplete.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.themes.min.css") ?>?v=<?=time();?>" />
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.min.css") ?>?v=<?=time();?>" />

<style type="text/css">
    
    #rapportino input{
        background-color: transparent;
    }
    
    #rapportino table{
        
        width: 100%;
    }
    #rapportino th{
        border: 1px solid black;
    }
    #rapportino td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }
    #rapportino td:nth-child(1) {
        font-weight: bold;
    }
    #rapportino td:nth-child(3) {
        background-color: #CBEBDC;
    }
    #rapportino td:nth-child(4) {
        background-color: #CBEBDC;
    }
    
    .visto_dir
    {
        background-color: LightGreen ;
    }
    
    .visto_amm
    {
        background-color: Yellow ;
    }
    
    .input_ora{
    }
    
    input[type="time"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
}

input[type="time"]::-webkit-clear-button {
  -webkit-appearance: none;
}

input[type="time"]::-webkit-clear-button {
    display: none;
}

input[type="time"] {
  -webkit-appearance: none;
}
</style>
<script type="text/javascript">
    $(document).ready(function(){
            $('input').keyup(function(e){
            var current_class=$(this).attr('class');
            var current_td=$(this).closest('td');
            var current_tr=$(this).closest('tr');
            
            if(e.altKey && e.keyCode == 81)
            {
               $(current_tr).prev().find('.'+current_class).focus();
            }
            if(e.altKey && e.keyCode == 65)
            {
                $(current_tr).next().find('.'+current_class).focus();
            }
        });
    })
    
    
    
    function ajax_salva_rapportino_settimanale(el)
    {
        var popup=$(el).closest('.popup');
        $(popup).html('     Salvataggio...');
        var rapportino=$(el).closest('#rapportino');
        var form=$(rapportino).find('form');
        var serialized=$(form).serializeArray();

        var url=controller_url+'ajax_salva_rapportino_settimanale';
        $(custom_3p_giorno_cliccato).find('.td_value').html('<i class="fas fa-sync"></i>');
        bPopup_generico.close();
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                
                //refresh_risultati_ricerca();

                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
    
    function repeat_row(el)
    {
        var current_tr=$(el).closest('tr');
        var prev_tr=$(current_tr).prev();
        
        var prev_situazione_val=$(prev_tr).find('.input_situazione').val();
        $(current_tr).find('.input_situazione').val(prev_situazione_val);
        
        var prev_situazione2_val=$(prev_tr).find('.input_situazione2').val();
        $(current_tr).find('.input_situazione2').val(prev_situazione2_val);
        
        var prev_laboratorio_val=$(prev_tr).find('.input_laboratorio').val();
        $(current_tr).find('.input_laboratorio').val(prev_laboratorio_val);
        
        var prev_azienda_label_val=$(prev_tr).find('.input_azienda_label').val();
        $(current_tr).find('.input_azienda_label').val(prev_azienda_label_val);
        
        var prev_azienda_recordid_val=$(prev_tr).find('.input_azienda_recordid').val();
        $(current_tr).find('.input_azienda_recordid').val(prev_azienda_recordid_val);
        
        var prev_durata_val=$(prev_tr).find('.input_durata').val();
        $(current_tr).find('.input_durata').val(prev_durata_val);
        
        var prev_luogo_val=$(prev_tr).find('.input_luogo').val();
        $(current_tr).find('.input_luogo').val(prev_luogo_val);
        
        var prev_via_val=$(prev_tr).find('.input_via').val();
        $(current_tr).find('.input_via').val(prev_via_val);
        
        var prev_orainiziomattina_val=$(prev_tr).find('.input_orainiziomattina').val();
        $(current_tr).find('.input_orainiziomattina').val(prev_orainiziomattina_val);
        
        var prev_orafinemattina_val=$(prev_tr).find('.input_orafinemattina').val();
        $(current_tr).find('.input_orafinemattina').val(prev_orafinemattina_val);
        
        var prev_orainiziopomeriggio_val=$(prev_tr).find('.input_orainiziopomeriggio').val();
        $(current_tr).find('.input_orainiziopomeriggio').val(prev_orainiziopomeriggio_val);
        
        var prev_orafinepomeriggio_val=$(prev_tr).find('.input_orafinepomeriggio').val();
        $(current_tr).find('.input_orafinepomeriggio').val(prev_orafinepomeriggio_val);
        
        var prev_orafinepomeriggio_val=$(prev_tr).find('.input_entrata3').val();
        $(current_tr).find('.input_entrata3').val(prev_orafinepomeriggio_val);
        
        var prev_orafinepomeriggio_val=$(prev_tr).find('.input_uscita3').val();
        $(current_tr).find('.input_uscita3').val(prev_orafinepomeriggio_val);
        
        var prev_orafinepomeriggio_val=$(prev_tr).find('.input_entrata4').val();
        $(current_tr).find('.input_entrata4').val(prev_orafinepomeriggio_val);
        
        var prev_orafinepomeriggio_val=$(prev_tr).find('.input_uscita4').val();
        $(current_tr).find('.input_uscita4').val(prev_orafinepomeriggio_val);
        
        
    }
    
    
  
    
     var autocomplete_options = [
      { label: "Nessuna azienda", id: "", category: "" },
      <?php
        $aziende2=array();
        foreach ($aziende as $key_azienda => $azienda) {
            $aziende2[$azienda['itemcode']]=$azienda['itemdesc'];
        ?>
            { label: "<?=$azienda['itemdesc']?>", id: "<?=$azienda['itemcode']?>", category: "" },
        <?php
        }
        ?>
      
    ];

$( ".aziendaautocomplete" ).autocomplete({
        source: autocomplete_options,
        minLength: 0,
        select: function(event, ui) {
                 $(this).closest('div').find('.recordidazienda').val(ui.item.id);
             }
    });
    
    
    
    $( ".aziendaautocomplete" ).click(function(){
        $( this ).autocomplete( "search", '' );
    });
 
</script>
<div id="rapportino" style="margin: 20px;margin-top: 10px;position: relative">
    <div onclick="chiudi_popup_generico(this)" style="position: absolute;top: 0px;right: 0px;cursor: pointer;font-size: 18px" ><i class="fas fa-times"></i></div>
    <img id="logo_rapportino" src="<?php echo base_url("/assets/images/custom/3p/3p_rapportino_intestazione.png") ?>?v=<?=time();?>" style="width: 90%;margin: auto"></img>
    <form id="form_rapportino" name="form_rapportino" method="post">
        <input type="hidden" name="recordid_presenzemensili" value="<?=$recordid_presenzemensili?>">
        <table>
            <thead>
                <th></th>
                <th>Giorno</th>
                <th>Data</th>
                <th>Situazione</th>
                <th style="padding-left: 5px;padding-right: 5px;">Situazione2</th>
                <th style="padding-left: 5px;padding-right: 5px;">Lab</th>
                <th>Azienda</th>
                <th>Durata</th>
                
                <th>dalle</th>
                <th>alle</th>
                <th>dalle</th>
                <th>alle</th>
                
                <th>dalle</th>
                <th>alle</th>
                <th>dalle</th>
                <th>alle</th>
                
                <th>Luogo di lavoro</th>
                <th>Via</th>
                
            </thead>
            <tbody>
                
                    <?php
            foreach ($rapportidilavoro as $key => $rapportodilavoro) 
            {
            ?>
            <tr>
                
                <input type="hidden" name="rapportino[<?=$key?>][recordid]" value="<?=$rapportodilavoro['recordid_']?>">
                <?php
                if($key==1)
                {
                ?>
                    <td class="visto_<?=$rapportodilavoro['visto']?>"></td>
                <?php
                }
                else
                {
                ?>
                    <td onclick="repeat_row(this)" style="cursor: pointer" class="visto_<?=$rapportodilavoro['visto']?>">
                        <div style="width: 15px">
                        <i class="fas fa-arrow-down"></i>
                        </div>
                    </td>
                    
                <?php
                }
                ?>
                <td>
                    <?=$rapportodilavoro['giornodellasettimana']?>
                </td>
                <td>
                    <!--<input type="date" name="rapportino[<?=$key?>][data]" value="<?=$rapportodilavoro['data']?>" disabled style="color: black !important"> -->
                    <?=date('d.m.Y', strtotime($rapportodilavoro['data']))?>
                </td>
                <td>
                    <select class="input_situazione" name="rapportino[<?=$key?>][situazione]" <?=$disabled?>>
                        <option value=""></option>
                        <?php
                        foreach ($situazioni as $key_situazione => $situazione) {
                            $selected='';
                            if($situazione['itemcode']==$rapportodilavoro['situazione'])
                            {
                                $selected='selected';
                            }
                        ?>
                            <option value="<?=$situazione['itemcode']?>" <?=$selected?>><?=$situazione['itemdesc']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="input_situazione2" name="rapportino[<?=$key?>][situazione2]" <?=$disabled?>>
                        <option value=""></option>
                        <?php
                        foreach ($situazioni2 as $key_situazione2 => $situazione2) {
                            $selected='';
                            if($situazione2['itemcode']==$rapportodilavoro['situazione2'])
                            {
                                $selected='selected';
                            }
                        ?>
                            <option value="<?=$situazione2['itemcode']?>" <?=$selected?>><?=$situazione2['itemdesc']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="input_laboratorio" name="rapportino[<?=$key?>][laboratorio]" style="width: 90%" <?=$disabled?>>
                        <option value=""></option>
                        <?php
                        foreach ($laboratori as $key_laboratorio => $laboratorio) {
                            $selected='';
                            if($laboratorio['itemcode']==$rapportodilavoro['laboratorio'])
                            {
                                $selected='selected';
                            }
                        ?>
                            <option value="<?=$laboratorio['itemcode']?>" <?=$selected?>><?=$laboratorio['itemdesc']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td style="width: 400px;">
                    <div class="azienda">
                        <?php
                        $label_azienda='';
                        if(array_key_exists($rapportodilavoro['recordidazienda_'], $aziende2))
                        {
                            $label_azienda=$aziende2[$rapportodilavoro['recordidazienda_']];
                        }
                        ?>
                        <input id="aziendaautocomplete_<?=$key?>" class="aziendaautocomplete input_azienda_label" value="<?=$label_azienda?>" <?=$disabled?>>
                        <br/>
                        <input class="recordidazienda input_azienda_recordid" type="hidden" name="rapportino[<?=$key?>][recordidazienda_]" value="<?=$rapportodilavoro['recordidazienda_']?>">
                    </div>
                </td>
                <td>
                    <input type="time" class="input_durata" name="rapportino[<?=$key?>][duratalavoro]" value="<?=$rapportodilavoro['duratalavoro']?>" <?=$disabled?> >
                </td>
                
                <td>
                    <input type="time" class="input_orainiziomattina input_ora" name="rapportino[<?=$key?>][orainiziomattina]" value="<?=$rapportodilavoro['orainiziomattina']?>" <?=$disabled?> >
                </td>
                <td>
                    <input type="time" class="input_orafinemattina input_ora" name="rapportino[<?=$key?>][orafinemattina]" value="<?=$rapportodilavoro['orafinemattina']?>" <?=$disabled?> >
                </td>
                <td>
                    <input type="time" class="input_orainiziopomeriggio input_ora" name="rapportino[<?=$key?>][orainiziopomeriggio]" value="<?=$rapportodilavoro['orainiziopomeriggio']?>" <?=$disabled?> >
                </td>
                <td>
                    <input type="time" class="input_orafinepomeriggio input_ora" name="rapportino[<?=$key?>][orafinepomeriggio]" value="<?=$rapportodilavoro['orafinepomeriggio']?>" <?=$disabled?> >
                </td>
                
                <td>
                    <input type="time" class="input_entrata3 input_ora" name="rapportino[<?=$key?>][entrata3]" value="<?=$rapportodilavoro['entrata3']?>" <?=$disabled?>  >
                </td>
                <td>
                    <input type="time" class="input_uscita3 input_ora" name="rapportino[<?=$key?>][uscita3]" value="<?=$rapportodilavoro['uscita3']?>" <?=$disabled?>  >
                </td>
                <td>
                    <input type="time" class="input_entrata4 input_ora" name="rapportino[<?=$key?>][entrata4]" value="<?=$rapportodilavoro['entrata4']?>" <?=$disabled?>  >
                </td>
                <td>
                    <input type="time" class="input_uscita4 input_ora" name="rapportino[<?=$key?>][uscita4]" value="<?=$rapportodilavoro['uscita4']?>" <?=$disabled?>  >
                </td>
                
                <td >
                    <input type="text" class="input_luogo" style="width: 100px;" name="rapportino[<?=$key?>][luogolavoro]" value="<?=$rapportodilavoro['luogolavoro']?>" <?=$disabled?> >
                </td>
                <td >
                    <input type="text" class="input_via" style="width: 100px;" name="rapportino[<?=$key?>][via]" value="<?=$rapportodilavoro['via']?>" <?=$disabled?> >
                </td>
                    
            </tr>
            <?php
            }
            ?>

            </tbody>
        </table>
        <br/><br/>
    </form>
    <div class="menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 90%;margin: auto;">
        <?php
                    if(count($alias)>0)
                    {
                        ?>
        <span style="color: red;font-weight: bold">CONTRATTO ALIAS PRESENTE NEL MESE CORRENTE. VERIFICARE</span>
    <?php
                    }
        ?>
        <?php
        if($table_settings['edit']=='true')
        {
        ?>
            <div id="btnSalvaContinua" class="btn_scritta"  style="width: 130px;padding: 0px;" onclick="ajax_salva_rapportino_settimanale(this)"><i class="fas fa-save"></i>   Salva</div>
        <?php
        }
        ?>
    </div>
    
</div>

