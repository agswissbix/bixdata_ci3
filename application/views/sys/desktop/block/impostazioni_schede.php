<div class="develop">block-impostazioni_schede</div>
<div align="right"><button onclick="SavePreferencesLayoutSchede();">Salva Preferenze</button></div>
<br><br><br><br>
<fieldset>
    <div align="center">
        <div style="border: 1px solid black;"><b><i>DATI</i></b></div>
        <input type="radio" name="dati" value="mostra" checked>Mostra
        <?php $i=0; while($i<50){echo "&nbsp;"; $i++;} ?>
        <input type="radio" name="dati" value="nascondi"> Nascondi<br>
    </div>
</fieldset>
<br><br><br><br><br>
<fieldset>
    <div align="center">
        <div style="border: 1px solid black;"><b><i>ALLEGATI</i></b></div>
        <input type="radio" name="allegati" value="mostra" checked>Mostra
        <?php $i=0; while($i<50){echo "&nbsp;"; $i++;} ?>
        <input type="radio" name="allegati" value="nascondi"> Nascondi<br>
    </div>
</fieldset>