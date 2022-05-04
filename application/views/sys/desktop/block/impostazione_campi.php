<?php
    $content_data['archives_list']=$data;
?>
<div class="develop">block-impostazione_campi</div>
<p align="center">
    <b><font color="orange"><label for="archivio" style="background-color: white; border: 1px solid #ccc; width: 100%" align="center">Scegli Archivio:</label></font></b><br><br>
        <select name="archivio" id="archivio" onchange="ChangeArchives();" style="border: 1px solid #ccc; background-color: white; color:orange; width: 100%">
            <option value="null">Null</option>
                <?php
                foreach($content_data['archives_list'] as $archivio){
                ?>
            <option value="<?php echo $archivio['idarchivio'] ?>"><?php echo $archivio['nomearchivio'] ?></option>
            <?php } ?>
        </select>
</p>
<br>
<div id="campipreferenze"></div>