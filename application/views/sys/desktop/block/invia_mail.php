<div id="block_invia_mail" class="blocco" style="width: 90%;margin: auto;">
    <form id="form_invia_mail" name="form_invia_mail" method="post" action="<?php echo site_url('sys_viewcontroller/'); ?>/esporta_xls" style="">
        <input id="tableid" type="hidden" name="tableid" value="<?=$data['tableid']?>">
        <textarea id="query" type="text" name="query" value="null" style="display: none"></textarea>
        Tipo di invio:<br/>
        <input type="radio" name="tipoinvio" value="generico" checked="checked"/> Generico
        <input type="radio" name="tipoinvio" value="bollettino"/> Bollettino
        <input type="radio" name="tipoinvio" value="pushup"/> Pushup
        <br/><br/>
        <div>
            Oggetto Mail:<br/>
            <input id="mail_subject" name="mail_subject" type="text" style="width: 100%">
            <br/><br/>
            Testo Mail:<br/>
            <textarea id="mail_body" name="mail_body" style="width: 100%;height: 100px;"></textarea>
            <br/><br/>
        </div>
        <div class="btn_scritta"  onclick="ajax_crea_lista_indirizzi()">Crea lista indirizzi</div>
        <br/><br/>
        <textarea id="lista_indirizzi" style="width: 100%;height: 200px;"></textarea>
        <div class="btn_scritta" onclick="invia_blocchi()">Invia tutti i blocchi</div>
    </form>
</div>


