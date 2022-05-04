
<script type="text/javascript">
    $('#visualizzatore_content').ready(function(i){
    });
</script>

<div id="visualizzatore" class="visualizzatore scheda ui-widget-content scheda" class="scheda ui-widget-content"  >
        
    <div id="menu_allegato" class="menu_small" style="overflow: visible">
        <div id="btn_allarga_allegato" class="btn_fa fa fa-download tooltip"  style="float: right" onclick="window.open('<?=$link?>', '_blank');"  title="Scarica allegato originale"></div>
    </div>
        
    <div id="visualizzatore_allegato" class="schedabody" style="margin-top: 5px;"> 
        <iframe id="PDFtoPrint" src="<?=$link?>?v=<?=time();?>" style="height: 100%;width: 100%" ></iframe>
    </div>
    
    <div id="menu_allegato" class="menu_small" style="overflow: visible">
        <div id="btn_allarga_allegato" class="btn_fa fa fa-download tooltip"  style="float: right" onclick="window.open('<?=$link?>', '_blank');"  title="Scarica allegato originale"></div>
    </div>
    
</div>

