<form class="selezione_viste_report">
<?php
foreach ($views as $key => $view) {
    ?>
    <input type="checkbox" value="<?=$view['id']?>" name="views[]"><?=$view['name']?><br/>
    <?php
}
?>
    <div class="btn_scritta" onclick="selezione_viste_report_salva(this,'<?=$tableid?>','<?=$reportid?>')">Salva</div>
</form>