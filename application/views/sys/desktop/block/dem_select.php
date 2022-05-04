<?php
foreach ($dems as $key => $dem) {
?>
<div onclick="dem_carica_mail('<?=$dem['recordid_']?>')">
    <?=$dem['nome_dem']?>
</div>
<?php
}
?>
