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
<div class="develop">block-loaded_preferences</div>
        <ul id="riordinabile">
            <div id="accordion1" style="width: 100%;">
                <h3 style="background-color: white; border: 1px solid #ccc;" align="center"><font color="orange">ATTUALI</font></h3>
                <br><br>
                <div style="width: 100%;">
                    <ul id="sortable" style="width: 100%;">
                        <?php
                            $i=0;
                            foreach($data as $dato){
                        ?>
                            <li id='li_<?php echo $i; ?>' data-numciclo="<?php echo $i; ?>" class="ui-widget-content" data-lookuptableid="<?php echo $dato['lookuptableid']; ?>" onclick="MostraOptions(this);" style="width: 100%; padding: 0px;">
                                <button data-type="null" onclick="EliminaCampo('<?php echo $idarchivio; ?>','<?php echo $dato['fieldid']; ?>','<?php echo $dato['lookuptableid']; ?>','li_<?php echo $i; ?>',this);">Elimina</button>
                                <input data-type="campodescription" type="text" name="fields[<?= $i; ?>][description]" value="<?php echo $dato['description']; ?>" >
                                <?php echo $dato['sublabel']."-".$dato['fieldid'].":".$dato['showedbyfieldid']."-".$dato['showedbyvalue']; ?>
                                <input data-type="campofieldid" type="hidden" name="fields[<?= $i; ?>][fieldid]" value="<?php echo $dato['fieldid']; ?>" >
                                <input data-type="campoidarchivio" type="hidden" name="fields[<?= $i; ?>][idarchivio]" value="<?php echo $idarchivio; ?>" >
                                <input data-type="campoposition" type="hidden" name="fields[<?= $i; ?>][position]" value="<?= $i; ?>" >
                                <input data-type="campoinsertorupdate" type="hidden" name="fields[<?= $i ?>][insertorupdate]" value="update" >
                                <div data-type="ElencoOptions" data-indexname="<?= $i; ?>" style="width: 100%;" id="ElencoOptions<?php echo $i; ?>"></div>
                            </li>
                            <!--<li class="ui-widget-content" id="<?= $dato['fieldid']."-".$idarchivio; ?>"><?php echo $dato['description']; ?></li>-->
                        <?php $i++; } ?>
                    </ul>
                </div>
            </div>
        </ul>