<div class="develop">block-impostazioni_temi</div>
<div align="right"><button onclick="SavePreferencesLayoutTemi();">Salva Preferenze</button></div>
<br>
<div>
    <div style="width: 180px;">
        <div align="center"><input type="radio" name="tema" value="default" id="default"> Default </div>
        <div align="center"><img src="<?php echo base_url('/assets/css/sys/desktop/theme/default/images/theme-example.png') ?>" width="400" height="200" border="1"></div>
    </div>
    <br>
    <div style="width: 180px; height: 180px;">
        <div align="center"><input type="radio" name="tema" value="acqua" id="acqua"> Acqua </div>
        <div align="center"><img src="<?php echo base_url('/assets/css/sys/desktop/theme/acqua/images/theme-example.png') ?>" width="400" height="200" border="1"></div>
    </div>
    <br>
    <div style="width: 180px; height: 180px;">
        <div align="center"><input type="radio" name="tema" value="peppergrinder" id="peppergrinder"> Pepper-Grinder </div>
        <div align="center"><img src="" width="400" height="200" border="1"></div>
    </div>

</div>
<script type="text/javascript">
    function SavePreferencesLayoutTemi()
    {
        var valore=""; 
        var AddressSalvataggio="<?php echo site_url('sys_viewcontroller/set_preferences_layout'); ?>" + '/';
            if ($('#default').is(':checked'))
             {
                valore = $('#default').val();
             }
            if ($('#acqua').is(':checked'))
            {
            valore = $('#acqua').val();
            }
            if ($('#peppergrinder').is(':checked'))
            {
            valore = $('#peppergrinder').val();
            }
        
        AddressSalvataggio = AddressSalvataggio + valore + "/temi";
        //alert(AddressSalvataggio);
        $.ajax({
            url: AddressSalvataggio,
            success:function()
            {
                alert("IMPOSTAZIONI SETTATE! ");
            },
            error:function()
            {
                alert("ERRORE RICHIESTA AJAX");
            }
        });
    }
</script>