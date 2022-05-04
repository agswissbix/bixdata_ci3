<script>
    $('#accordion').accordion({
        heightStyle: "content"
    });
</script>
<?php
    $content_data['ordered_fields']=$data;
?>
<div class="develop">block-campi_preferenze</div>
<div id="accordion">
    <?php foreach ($content_data['ordered_fields'] as $key_label => $fields_label) {
    if($fields_label['type']=='master'){ ?>
        <h3><?= $key_label ?></h3>
        <div>
            <ul style="list-style-type: none;">
            <?php
                $tableid=$fields_label['tableid'];
                //echo $tableid;
                foreach ($fields_label['fields'] as $key => $field) {
                    $descrizione= str_replace('.', '', $field['description']);//togli i punti
                    $descrizione=  str_replace(' ', '', $descrizione);//togli gli spazi
            ?>
            <li class="ui-state-default" id="<?= $field['fieldid']."-".$tableid; ?>" onclick="addElementCampiPreferiti('<?= $field['fieldid']."-".$tableid; ?>');" name="<?= $field['fieldid'] ?>">
                <?= $field['description'] ?>
            </li>
            <?php } ?>
            </ul>
        </div>
        <?php }
        
                }?>
</div>