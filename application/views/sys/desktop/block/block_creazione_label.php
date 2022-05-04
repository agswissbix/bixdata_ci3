<script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            url: controller_url + 'ajax_load_block_impostazioni_campi',
            success:function(data){
                $('#ElencoArchivi').html(data);
                $('#archivio').attr('onchange','').unbind('onchange');
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX CAMPI");
            }
        });
    });
    
    function SalvaLabel()
    {
        $.ajax({
           url: controller_url + 'create_new_label',
           type: 'post',
           data: "idarchivio=" + $('#archivio').val() + "&textlabel=" + $('#label').val(),
           success:function()
           {
               alert("CREAZIONE NUOVA LABEL AVVENUTA CORRETTAMENTE");
           },
           error:function(){
               alert("ERRORE SALVATAGGIO LABEL");
           }
        });
        //alert("TESTO LABEL: " + $('#label').val() + "\nARCHIVIO SELEZIONATO: " + $('#archivio').val());
    }
</script>
<div>
    <div align="left">
        <button onclick="SalvaLabel();">Salva Label</button>
    </div><br>
    <div id="NuovaLabel">
        <label for="label">Testo Label:</label>&nbsp;
        <input type="text" name="label" id="label">
    </div>
    <div id="ElencoArchivi"></div>
</div>