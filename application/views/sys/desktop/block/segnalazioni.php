<?php
$settore=$data['settore'];
$recordid_azienda=$data['recordid_azienda'];
$recordid_progetto=$data['recordid_progetto'];
$segnalatore=$data['segnalatore'];
$userid=$data['userid'];
?>
<div class="blocco scheda" id='segnalazioni' style="margin-top: 10%;" >
    <div style="width: 50%;margin: auto;">
        <h3 style="color: #467bbd">Segnalazione</h3>
        <form id='form_segnalazione' enctype="multipart/form-data" method="post">
            <input type="hidden" name="settore" value="<?=$settore?>">
            <input type="hidden" name="recordid_azienda" value="<?=$recordid_azienda?>">
            <input type="hidden" name="recordid_progetto" value="<?=$recordid_progetto?>">
            <input type="hidden" name="segnalatore" value="<?=$segnalatore?>">
            <input type="hidden" name="userid" value="<?=$userid?>">
            <label for="tipo">Tipo segnalazione</label>
            <select name='tipo' id='tipo'>
                <option value="modifica">Modifica</option>
                <option value="errore">Errore</option>
                <option value="nuovafunzionalita">Nuova funzionalità</option>
            </select>
            <br/><br/>
            <label for="priorita">Priorità</label>
            <select name='priorita' id='priorita'>
                <option value="bassa">Bassa</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
            </select>
            <br/><br/>
            <textarea name='testo' id='testo'></textarea>
            <br/>
            <br/>
            Allegati: <input type="file" multiple name="allegati[]">
            <br/>
            <br/>
            <div>
                <div class="btn_scritta" onclick="invia_segnalazione(this)">Invia segnalazione</div>
                <div class="clearboth"></div>
            </div>
            <br/>
            <div>
                <div class="btn_scritta" onclick="riepilogo_segnalazioni_popup(this)">Riepilogo segnalazioni</div>
                <div class="clearboth"></div>
            </div>
        </form>
    </div>
</div>
