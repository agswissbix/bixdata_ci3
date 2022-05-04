<?php
    $content_data['archives_list']=$data;
?>
    <div class="develop">block-impostazione_campi</div>
    <div style="float: left; width: 50%;">
        <b><font color="orange"><label for="archiviomaster" style="background-color: white; border: 1px solid #ccc; width: 100%" align="center">Archivio Master:</label></font></b><br><br><br>
            <select name="archiviomaster" id="archiviomaster" onchange="CambioSelezione();" style="border: 1px solid #ccc; background-color: white; color:orange; width: 100%;">
                <option value="null">Null</option>
                    <?php
                    foreach($content_data['archives_list'] as $archivio){
                    ?>
                <option value="<?php echo $archivio['idarchivio'] ?>"><?php echo $archivio['nomearchivio'] ?></option>
                <?php } ?>
            </select>
        <div id="campipreferenze"></div>
    </div>
    <div style="float: left; width: 50%;">
        <b><font color="orange"><label for="archiviolinked" style="background-color: white; border: 1px solid #ccc; width: 100%;" align="center">Archivio Linked:</label></font></b><br><br><br>
            <select name="archiviolinked" id="archiviolinked" style="border: 1px solid #ccc; background-color: white; color:orange; width: 100%;">
                <option value="null">Null</option>
                    <?php
                        foreach($content_data['archives_list'] as $archivio){
                    ?>
                <option value="<?php echo $archivio['idarchivio'] ?>"><?php echo $archivio['nomearchivio'] ?></option>
                <?php } ?>
            </select>
</div>

<script type="text/javascript">
    function CambioSelezione()
    {
        var indirizzo= controller_url + 'ajax_load_block_impostazioni_campi_collega_tabelle'; // variabile da inizializzare al primo script - mi servirà per la richiesta ajax di carico del blocco campi ricerca
        var url;
        if($('#archiviomaster').val()!="null"){
                url=indirizzo + '/' + $('#archiviomaster').val();
            $.ajax( {
                type: "POST",
                url: url,
                success: function( response ) {
                    var campipreferenze= $('#campipreferenze');
                    $(campipreferenze).html('');
                    $('#campipreferenze').append(response);
                },
                error: function()
                    {
                        alert("Errore Richiesta Ajax");
                    }
            } ); 
        }
    }
</script>