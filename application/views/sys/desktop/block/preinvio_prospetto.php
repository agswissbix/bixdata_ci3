<?php

?>
<div class="block scheda" style="height: 100%;width: 100%;background-color: white;text-align: center;position: relative">
    <form>
    <div style="height: 90%;overflow-y: scroll">
       <input type="hidden" id="recordid_richiesta" value="<?=$richiesta['recordid']?>">
        Selezionare l'immobile di cui inviare il prospetto <br/><br/><br/>
        <?php
        foreach ($immobili as $key => $immobile) {
            ?>
        
        <div class="scheda" style="width: 50%;margin: auto;height: 100px;padding: 14px;position: relative;">
            <input type="checkbox" name="immobili_selezionati[<?=$immobile['recordid']?>]" value="<?=$immobile['recordid']?>" style="position: absolute;left: 0px;top:0px">
            <?=$immobile['badge']?>
        </div>
        <br/><br/>
        

        <?php
        }
        ?> 
    </div>
    
    <div class="ui-widget-header menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 98%;margin: auto;">
        <div id="btnNuovo" class="btn_scritta" onclick="genera_mail_prospetto(this)" style="width: 130px;">Invia prospetto</div>
    </div>
    </form>
</div>