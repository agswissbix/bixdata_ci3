<div class="develop">block-preload_preferences_temi</div>
<div align="center"><h3>ATTUALI</h3></div>
<br><br>
<?php foreach($data as $dato){ ?>
<div align="center">
    <div style="width: 180px; height: 180px;">
        <img src="" width="150" height="150" border="1">
        <br>Tema: <?php echo $dato['tema']; ?>
    </div>
</div>
<?php } ?>