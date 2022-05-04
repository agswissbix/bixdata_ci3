<div class="develop">block-preload_preferences_schede</div>
<div align="center"><h3>ATTUALI</h3></div>
<br><br><br><br>
<fieldset>
    <div align="center">
        <div style="border: 1px solid black;"><b><i>DATI</i></b></div>
        <input type="radio" name="datiattuali" value="mostra" <?php if($data[0]['dati']=='mostra') echo "checked"; ?> disabled>Mostra
        <?php $i=0; while($i<50){echo "&nbsp;"; $i++;} ?>
        <input type="radio" name="datiattuali" value="nascondi" <?php if($data[0]['dati']=='nascondi') echo "checked"; ?> disabled> Nascondi<br>
    </div>
</fieldset>
<br><br><br><br><br>
<fieldset>
    <div align="center">
        <div style="border: 1px solid black;"><b><i>ALLEGATI</i></b></div>
        <input type="radio" name="allegatiattuali" value="mostra" <?php if($data[0]['allegati']=='mostra') echo "checked"; ?> disabled>Mostra
        <?php $i=0; while($i<50){echo "&nbsp;"; $i++;} ?>
        <input type="radio" name="allegatiattuali" value="nascondi" <?php if($data[0]['allegati']=='nascondi') echo "checked"; ?> disabled> Nascondi<br>
    </div>
</fieldset>