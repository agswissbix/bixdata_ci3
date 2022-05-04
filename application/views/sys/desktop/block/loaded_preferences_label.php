<style>
  #accordion1 { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #accordion1 li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; }
  #accordion1 li span { position: absolute; margin-left: -1.3em; }
</style>
<script type="text/javascript">
    $(document).ready(function(){ 
    $(function() {
        $( "#accordion" ).accordion({
        heightStyle: "content"
    });
  });
    });
</script>
<div class="develop">block-loaded_preferences_label</div>
    <br><br><br>
        <ul id="riordinabile">
            <div id="accordion1" style="width: 100%;">
                <h3 style="background-color: white; border: 1px solid #ccc;" align="center"><font color="orange">ATTUALI</font></h3>
                <br><br>
                <div>
                    <ul id="sortable">
                    <?php
                        $i=0;
                       /* foreach($data as $dato){
                    ?>
                        <li class="ui-state-default" id="<?= $dato['label'].'-'.$idarchivio; ?>"><?php echo $dato['label']; ?>
                            <input data-type="campodescription" type="text" name="fields[<?= $i; ?>][description]" style="width: 100%; " value="<?php echo $dato['label']; ?>" >
                            <input data-type="campofieldid" type="hidden" name="fields[<?= $i; ?>][fieldid]" value="<?php echo $dato['label']; ?>" >
                            <input data-type="campoidarchivio" type="hidden" name="fields[<?= $i; ?>][idarchivio]" value="<?php echo $idarchivio; ?>" >
                            <input data-type="campoposition" type="hidden" name="fields[<?= $i; ?>][position]" value="<?= $i; ?>" >
                            <input data-type="campoinsertorupdate" type="hidden" name="fields[<?= $i ?>][insertorupdate]" value="update" >
                        </li>
                    <?php $i++; }*/ ?>
                    </ul>
                </div>
            </div>
        </ul>