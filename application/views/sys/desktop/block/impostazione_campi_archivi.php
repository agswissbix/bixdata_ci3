<?php
    $content_data['archives_list']=$data;
?>
<div class="develop">block-impostazione_campi_archivi</div>
<p align="center">
                <b><label for="archivio">Scegli Archivio:</label></b>
                <select name="archivio" id="archivio" onchange="ChangeArchivesCampiArchivi();">
                    <option value="null">Null</option>
                    <?php
                        //inserisco nell'elenco tutti gli archivi
                        foreach($content_data['archives_list'] as $archivio){
                    ?>
                    <option value="<?php echo $archivio['idarchivio'] ?>" ><?php echo $archivio['nomearchivio'] ?></option>
                    <?php } ?>
                </select>
            </p>
            <br>
            <div id="campipreferenze"></div>