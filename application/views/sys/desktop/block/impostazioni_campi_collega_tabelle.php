<style>
  #accordion { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #accordion li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #accordion li span { position: absolute; margin-left: -1.3em; }
</style>
<script>
  $(function() {
    $( "#accordion" ).accordion({
        heightStyle: "content"
    });
  });
</script>
<div class="develop">block-impostazioni_campi_labels</div>
            <ul id="sort">
                    <div id="accordion" style="width: 100%;">
                        <h3>Elenco</h3>
                        <div>
                            <?php foreach($data['labels'] as $etichetta){?>
                                    <li class="ui-state-default" id="<?php echo $etichetta.'-'.$data['idarchivio']; ?>"><span></span><?php echo $etichetta; ?> </li>
                            <?php } ?>
                        </div>
                    </div>
            </ul>