<div style="position: relative">
    <div onclick="chiudi_popup_generico(this)" style="position: absolute;top: 10px;right: 0px;cursor: pointer;font-size: 18px" ><i class="fas fa-times"></i></div>
    <div style="width: 80%;margin: auto;margin-top: 100px;">
        <b>Situazione:</b><br/>
        <select name="rapportino[<?=$key?>][situazione]" style="width: 100%">
            <option value=""></option>
            <?php
            foreach ($situazioni as $key_situazione => $situazione) {
                $selected='';
                if($situazione['itemcode']==$rapportodilavoro['situazione'])
                {
                    $selected='selected';
                }
            ?>
                <option value="<?=$situazione['itemcode']?>" <?=$selected?>><?=$situazione['itemdesc']?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>