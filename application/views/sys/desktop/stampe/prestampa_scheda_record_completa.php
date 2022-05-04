<?php
$labels=$data['labels'];
$tableid=$data['tableid'];
$recordid=$data['recordid'];
?>
<div id="prestampa_scheda_record_completa" style="padding: 50px;background-color: white">
    <form>
        <?php
        foreach ($labels as $label_key => $label) {
        ?>
        <input type="checkbox" checked="" name="<?=$label_key?>"><?=$label?><br/>
        <?php
        }
        ?>
        <br/><br/>
        <div class="btn_scritta" onclick="stampa_scheda_record_completa(this,'<?=$tableid?>','<?=$recordid?>')">Stampa selezionati</div>
    </form>
</div>