<?php
$percorso_jpg=$data['percorso_jpg'];
$recordid=$data['recordid'];
list($width, $height) = getimagesize($percorso_jpg);
?>
<script language="Javascript">
        var scheda_record_scheda_visualizzatore_width;
        $('#block_jpgcrop').ready(function(i){
            scheda_record_scheda_visualizzatore_width=$('#block_jpgcrop').width()-50;
        
    });
    <?php
    if($cliente_id=='Work&Work')
    {
    ?>
        $('#imgToCrop').Jcrop({
            aspectRatio: 1,
            boxWidth: scheda_record_scheda_visualizzatore_width, 
            //trueSize: [<?=$width?>,<?=$height?>],
            keySupport: false,
            onSelect: updateCoords
            
        });
    <?php
    }
    else
    {
    ?>
        $('#imgToCrop').Jcrop({
            aspectRatio: null,
            boxWidth: scheda_record_scheda_visualizzatore_width, 
            //trueSize: [<?=$width?>,<?=$height?>],
            keySupport: false,
            setSelect:   [ 0, 0, $('#imgToCrop').width(), $('#imgToCrop').height() ],
            onSelect: updateCoords
            
        });
    <?php
    }
    ?>
        
        
        function updateCoords(c) { 
            $('#x').val(c.x); 
            $('#y').val(c.y); 
            $('#w').val(c.w); 
            $('#h').val(c.h); 
        };



</script>
<div id="block_jpgcrop" class="block" style="height: 100%;width: 100%">
    <div style="height: calc(100% - 50px);width: 100%;overflow: scroll">
        <img id="imgToCrop"  src="<?=$percorso_jpg?>"></img>
    </div>

    <form action="" method="post" onsubmit="return checkCoords();"> 

        <input type="hidden" id="x" name="x" /> <input type="hidden" id="y" name="y" /> 
        <input type="hidden" id="w" name="w" /> <input type="hidden" id="h" name="h" />
        <input type="hidden" id="percorso_jpg" name="percorso_jpg" value="<?=$percorso_jpg?>" /> 
        <br/>
        <div class="btn_scritta"  onclick="ajax_cropImg(this,'<?=$recordid?>','<?=$data['cartella']?>')">Ritaglia immagine </div>

    </form>
</div>
