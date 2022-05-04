<script type="text/javascript">
    function ajax_salva_note_rapportino(el)
    {
        var popup=$(el).closest('.popup');
        $(popup).html('     Salvataggio...');
        var note=$(el).closest('#note');
        var form=$(note).find('form');
        var serialized=$(form).serializeArray();

        var url=controller_url+'ajax_salva_note_rapportino';
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
        <b>Note</b> <br/>
        <form>
            <input type="hidden" name="recordid_presenzemensili" value="<?=$recordid_presenzemensili?>">
            <input type="hidden" name="recordid_rapportodilavoro" value="<?=$recordid_rapportodilavoro?>">
            <textarea style="width: 100%;min-height: 200px;" name="osservazioni"><?=$osservazioni?></textarea>
        </form>
    </div>
    <br/><br/>
    <div class="menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 80%;margin: auto;">
        <div id="btnSalvaContinua" class="btn_scritta"  style="width: 130px;padding: 0px;" onclick="ajax_salva_note_rapportino(this)"><i class="fas fa-save"></i>   Salva</div>
    </div>
</div>

