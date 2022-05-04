<style type='text/css'>
    #note table {
        width: 100%;
        border: 1px solid black;
        font-size: 14px;
    }
    #note td{
        padding: 10px;
        border: 1px solid black;
    }
    #note th{
        padding: 10px;
    }
    
    #note td textarea{
        width: 100%;
        height: 100px;
        border: 0px;
    }
</style>

<script type="text/javascript">
    function ajax_salva_note_dipendente(el)
    {
        var popup=$(el).closest('.popup');
        $(popup).html('     Salvataggio...');
        var note=$(el).closest('#note');
        var form=$(note).find('form');
        var serialized=$(form).serializeArray();

        var url=controller_url+'ajax_salva_note_dipendente';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                setTimeout(function(){
                    refresh_risultati_ricerca();
                    bPopup_generico.close();
                },1500);
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
    
    function cancella_nota_temporizzata(el)
    {
        var recordid_nota=$(el).closest('tr').data('recordid');
        var serialized=[];
        
        serialized.push({name: 'recordid_nota', value: recordid_nota});
        var url=controller_url+'ajax_cancella_nota_temporizzata';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                bPopup_generico.close();
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_note_dipendente/<?=$recordid_presenzemensili?>",
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
    
    function termina_nota_temporizzata(el)
    {
        var recordid_nota=$(el).closest('tr').data('recordid');
        var serialized=[];
        
        serialized.push({name: 'recordid_nota', value: recordid_nota});
        $('.bPopup_generico').html('Salvataggio in corso');
        var url=controller_url+'ajax_termina_nota_temporizzata';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                bPopup_generico.close();
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_note_dipendente/<?=$recordid_presenzemensili?>",
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
    
    function salva_nota_temporizzata(el)
    {
        var recordid_dipendente=$('#recordid_dipendente').val();
        var recordid_nota=$(el).closest('tr').data('recordid');
        var row=$(el).closest('tr');
        var nota=$(row).find('.nota').val();
        var dal=$(row).find('.dal').val();
        var al=$(row).find('.al').val();
        
        var serialized=[];
        
        serialized.push({name: 'recordid_nota', value: recordid_nota});
        $.ajax( {
            type: "POST",
            url: controller_url+'ajax_termina_nota_temporizzata',
            data: serialized,
            success: function( response ) {
            },
            error:function(){
                alert('errore');
            }
        } );
        
        
        
        var serialized=[];
        
        serialized.push({name: 'recordid_dipendente', value: recordid_dipendente});
        serialized.push({name: 'recordid_nota', value: ''});
        serialized.push({name: 'nota', value: nota});
        serialized.push({name: 'dal', value: dal});
        serialized.push({name: 'al', value: al});
        var url=controller_url+'ajax_salva_nota_temporizzata';
        $('.bPopup_generico').html('Salvataggio in corso');
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                bPopup_generico.close();
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_note_dipendente/<?=$recordid_presenzemensili?>",
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
    
    
</script>
<div id='note' style=""  >
    <div onclick="chiudi_popup_generico(this)" style="position: absolute;top: 10px;right: 0px;cursor: pointer;font-size: 18px" ><i class="fas fa-times"></i></div>
    <div style="width: 80%;margin: auto;margin-top: 100px;">
        <b>Note</b> <br/>
        <form>
            <input type="hidden" id='recordid_dipendente' name='recordid_dipendente' value="<?=$recordid_dipendente?>">
            <input type="hidden" name='recordid_presenzemensili' value="<?=$recordid_presenzemensili?>">
            <div style="width: 100%;min-height: 100px;" name="note" ><?=$notetempo?></div>
            
        </form>
        <br/><br/>
        <b>Note temporizzate</b>
        <table>
            <thead>
                <th>Inserita da</th>
                <th>Nota</th>
                <th>Dal</th>
                <th>Al</th>
                <th></th>
            </thead>
        <?php
        foreach ($note_temporizzate as $key => $nota_temporizzata) {
         ?>
            <tr data-recordid='<?=$nota_temporizzata['recordid_']?>'>
                    <td><?=$nota_temporizzata['inseritada']?></td>
                    <td><?=$nota_temporizzata['nota']?></td>
                    <td><?=date("d.m.Y",strtotime($nota_temporizzata['dal']))?></td>
                    <td><?php
                    if($nota_temporizzata['al']!=null)
                    {
                        echo date("d.m.Y",strtotime($nota_temporizzata['al']));
                    }
                    ?></td>
                    <td style="text-align: center"><div class="btn_scritta" onclick="$(this).closest('tr').hide();$(this).closest('tr').next().show();">Modifica</div><div class="btn_scritta" onclick="termina_nota_temporizzata(this)">Termina</div></td>
            </tr>
            <tr data-recordid='<?=$nota_temporizzata['recordid_']?>' style="display: none">
                    <td></td>
                    <td><textarea class="nota" name="nota"  ><?=$nota_temporizzata['nota']?></textarea></td>
                    <td><input class='dal' name="dal" type="date" value='<?=$nota_temporizzata['dal']?>'></td>
                    <td><input class='al' name="al" type="date" value="<?=$nota_temporizzata['al']?>"></td>
                    <td style="text-align: center"><div class="btn_scritta" onclick="salva_nota_temporizzata(this)">Salva</div></td>
            </tr>
        <?php
        }
        ?>
            <tr id="new" data-recordid=''>
                <td></td>
                <td><textarea class="nota" name="nota" ></textarea></td>
                <td><input class='dal' name="dal" type="date" value='<?=date("Y-m-d")?>'></td>
                <td><input class='al' name="al" type="date"></td>
                <td style="text-align: center"><div class="btn_scritta" onclick="salva_nota_temporizzata(this)">Salva</div></td>
            </tr>
        </table>
    </div>
    <br/><br/>
    <div class="menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 80%;margin: auto;">
        <!--<div id="btnSalvaContinua" class="btn_scritta"  style="width: 130px;padding: 0px;" onclick="ajax_salva_note_dipendente(this)"><i class="fas fa-save"></i>   Salva</div>-->
    </div>
</div>

