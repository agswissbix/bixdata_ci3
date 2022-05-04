
<script type="text/javascript">
    $('#visualizzatore_content').ready(function(i){
    });
</script>

<div id="visualizzatore" class="visualizzatore scheda ui-widget-content scheda block" class="scheda ui-widget-content" style="position: relative"  >
     
    <div id="visualizzatore_allegato" class="schedabody" style="width: 80%;height: 60%;margin: auto;"> 
        <iframe id="PDFtoPrint" src="<?=$link?>?v=<?=time();?>" style="height: 100%;width: 100%" ></iframe>
    </div>
    <div>
        <div style="text-align: left;">
            <form id='mail_data'>
            Destinatario:<br/>
            <input id='mail_to' name='mail_to' type="text" style="width: 40%;" value="<?=$mail['to']?>">
            <br>
            Oggetto:<br/>
            <input id='mail_subject' name='mail_subject' type="text" style="width: 70%;" value="<?=$mail['subject']?>">
            <br>
            Testo mail:<br/>
            <textarea id='mail_body' name='mail_body'  style="width: 70%;height: 150px;"><?=$mail['body']?></textarea>
            <input type="hidden" name="mail_jdocattachment" value="<?=$path?>">
            <input type="hidden" name="recordid_richiesta" value="<?=$linkedmaster['recordid_richiesta']?>">
            <input type="hidden" name="recordid_immobile" value="<?=$linkedmaster['recordid_immobile']?>">
            <input type="hidden" name="recordid_contatto" value="<?=$linkedmaster['recordid_contatto']?>">
            </form>
        </div>
    </div>
    <div class="ui-widget-header menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 98%;margin: auto;">
        <div id="btnNuovo" class="btn_scritta" onclick="conferma_invio(this)" style="width: 130px;">Conferma invio</div>
    </div>
</div>

