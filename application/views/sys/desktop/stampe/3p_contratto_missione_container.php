
<script type="text/javascript">
    function ajax_salva_contratto_missione(el)
    {
        var contratto_missione=$(el).closest('#3p_contratto_missione');
        var form=$(contratto_missione).find('form');
        var serialized=$(form).serializeArray();
        $('.bPopup_generico').html('attendere, salvataggio in corso');
        var url=controller_url+'ajax_salva_contratto_missione';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                console.info(response);
                alert('Salvato');
                bPopup_generico.close();
                ajax_compilazione_contratto_missione(this,response)
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
</script>
<style type="text/css">

</style>

<div id="3p_contratto_missione" class="visualizzatore ui-widget-content"    >
     <?php
     if($funzione=='modifica')
     {
     ?>
    <div id="menu_3p_contratto_missione" class="menu_small" style="overflow: visible">
        <div class="btn_scritta" style="float: left"onclick="ajax_modifica_contrattuale(this,'<?=$recordid_contratto?>')">Modifica contrattuale</div>
        <div class="btn_scritta" style="float: left"onclick="ajax_passa_a_contratto_fornitura(this,'<?=$recordid_contratto?>')">Passa a contratto fornitura</div>
        <?php
        if($table_settings['edit']=='true')
        {
        ?>
            <div class="btn_scritta" style="float: right"onclick="ajax_salva_contratto_missione(this)">Salva</div>
        <?php
        }
        ?>
        <?php
        if($recordid_contratto!='')
        {
        ?>
        <div class="btn_scritta" style="float: right"onclick="ajax_stampa_pdf_contratto_missione(this,'<?=$recordid_contratto?>','false')">Stampa senza intenstazione</div>
        <div class="btn_scritta" style="float: right"onclick="ajax_stampa_pdf_contratto_missione(this,'<?=$recordid_contratto?>','true')">Stampa con intenstazione</div>
        <?php
        }
        ?>
    </div>
    <?php
     }
    ?>
   
        
    <div id="3p_contratto_missione_content" class="schedabody" style="margin: auto;margin-top: 5px;height: auto;width: 265mm;">
        <form>
        <?=$content?>
        </form>
    </div>
    
</div>


