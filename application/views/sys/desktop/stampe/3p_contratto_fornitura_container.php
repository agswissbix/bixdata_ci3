
<script type="text/javascript">
    function ajax_salva_contratto_fornitura(el)
    {
        console.info('fun: ajax_salva_contratto_fornitura');
        var contratto_fornitura=$(el).closest('#3p_contratto_fornitura');
        var form=$(contratto_fornitura).find('form');
        var serialized=$(form).serializeArray();

        var url=controller_url+'ajax_salva_contratto_fornitura';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                setTimeout(function(){
                    bPopup_generico.close();
                    //alert(response);
                    //ajax_compilazione_contratto_fornitura(this,response)
                },10000);
                
            },
            error:function(){
                alert('errore');
            }
        } );
    }
</script>
<style type="text/css">

</style>

<div id="3p_contratto_fornitura" class="visualizzatore  ui-widget-content "    >
     <?php
     if($funzione=='modifica')
     {
     ?>
    <div id="menu_3p_contratto_fornitura" class="menu_small" style="overflow: visible">
        <div style="float: left;margin-right: 20px;color: #54ace0;font-weight: bold">CONTRATTO DI FORNITURA</div>
        <div class="btn_scritta" style="float: left"onclick="ajax_passa_a_contratto_missione(this,'<?=$recordid_contratto?>')">Passa a contratto missione</div>
        <!--<div class="btn_scritta" style="float: right"onclick="ajax_salva_contratto_fornitura(this)">Salva</div>-->
        <?php
        if($table_settings['edit']=='true')
        {
        ?>
            <div class="btn_scritta" style="float: right"onclick="ajax_salva_contratto_fornitura(this)">Salva</div>
        <?php
        }
        ?>
        <?php
        if($recordid_contratto!='')
        {
        ?>
        <div class="btn_scritta" style="float: right"onclick="ajax_stampa_pdf_contratto_fornitura(this,'<?=$recordid_contratto?>','false')">Stampa senza intenstazione</div>
        <div class="btn_scritta" style="float: right"onclick="ajax_stampa_pdf_contratto_fornitura(this,'<?=$recordid_contratto?>','true')">Stampa con intenstazione</div>
        <?php
        }
        ?>
    </div>
    <?php
     }
    ?>
   
        
    <div id="3p_contratto_fornitura_content" class="schedabody" style="margin: auto;margin-top: 5px;height: auto;width: 265mm;">
        <form>
        <?=$content?>
        </form>
    </div>
    
</div>


