<?php
foreach ($campagne as $key => $campagna) {
?>
<div onclick="campagna_carica_telemarketing('<?=$campagna['recordid_']?>')">
    <?=$campagna['titolo']?>
</div>
<?php
}
?>
