<div id="impostazioni_archivio" class="block">
    <div style="width: 30%;height: 100%;float: left;">
        <div onclick="alert('label')">Label</div>
        <div onclick="alert('label')">SubLabel</div>
        <div onclick="load_block_impostazioni_archivio_sottosezione('<?=$idarchivio?>','campi')">Campi</div>
        <div onclick="alert('label')">Collega archivio</div>
        <div onclick="load_block_impostazioni_archivio_sottosezione('<?=$idarchivio?>','settings')">Settings</div>
        <div onclick="load_block_impostazioni_archivio_sottosezione('<?=$idarchivio?>','alert')">Alert</div>
    </div>
    <div id="impostazioni_archivio_sottosezione" style="width: 70%;height: 100%;float: left;overflow: scroll">
        
    </div>
    
</div>