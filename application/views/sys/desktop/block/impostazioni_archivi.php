<div id="block_scheduler_menu" class="block">
    <div class="btn_scritta" onclick="">Crea nuovo</div><br/><br/>
    Archivi
    <?php
    foreach ($archivi as $key => $archivio) {
    ?>
    <div class="" onclick="load_block_impostazioni_archivio('<?=$archivio['idarchivio']?>')"><?=$archivio['nomearchivio']?></div>
    <?php
    }
    ?>
    
</div>