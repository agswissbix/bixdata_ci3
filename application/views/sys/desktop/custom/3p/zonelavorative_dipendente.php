<script type="text/javascript">
    function ajax_salva_zonelavorative_dipendente(el)
    {
        var popup=$(el).closest('.popup');
        $(popup).html('     Salvataggio...');
        var note=$(el).closest('#note');
        var form=$(note).find('form');
        var serialized=$(form).serializeArray();

        var url=controller_url+'ajax_salva_zonelavorative_dipendente';
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
    
    
</script>
<div id='note' style="">
    <div onclick="chiudi_popup_generico(this)" style="position: absolute;top: 10px;right: 0px;cursor: pointer;font-size: 18px" ><i class="fas fa-times"></i></div>
    <div style="width: 80%;margin: auto;margin-top: 100px;">
        <b>Zone lavorative</b> <br/>
        <form>
            <input type="hidden" name='recordid_dipendente' value="<?=$recordid_dipendente?>">
            <input type="hidden" name='recordid_presenzemensili' value="<?=$recordid_presenzemensili?>">
            <select name="zonelavorative">
                <?php
                foreach ($zonelavorative_options as $key => $zonelavorative_option) {
                    $selected='';
                    if($zonelavorative_option['itemcode']==$zonelavorative)
                    {
                       $selected='selected'; 
                    }
                ?>
                <option value="<?=$zonelavorative_option['itemcode']?>" <?=$selected?>><?=$zonelavorative_option['itemdesc']?></option>
                <?php
                }
                ?>
            </select>
        </form>
    </div>
    <br/><br/>
    <div class="menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 80%;margin: auto;">
        <div id="btnSalvaContinua" class="btn_scritta"  style="width: 130px;padding: 0px;" onclick="ajax_salva_zonelavorative_dipendente(this)"><i class="fas fa-save"></i>   Salva</div>
    </div>
</div>

