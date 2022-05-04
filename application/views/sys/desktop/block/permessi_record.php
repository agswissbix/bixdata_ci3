<div id="block_permessi_record" class="block scheda" style="height: 100%;" >
<div id="lista_permessi_utente" style="padding: 50px;height: calc(100% - 150px);overflow-y: scroll">
    <?php
    foreach ($utenti as $key => $utente) {
        $checked='';
        if(in_array($utente['id'], $permessi_record))
        {
            $checked='checked';
        }
        ?>
    <input class="field_check" type="checkbox" name="permessi_utente[]" value="<?=$utente['id']?>" <?=$checked?> /> <?=$utente['username']?>
    <br/>
    <?php
    }
    ?>
</div>
<br/>
<div class="btn_scritta" onclick="salva_permessi_record(this,'<?=$scheda_record_id?>')">Salva</div>
</div>