<div id="impostazioni_archivio_settings" class="block" style="overflow: scroll">
    Settings<br/>
    <form id="impostazioni_archivio_settings_form">
    <?php
    foreach ($settings as $setting_key => $setting) {
    ?>
        <br />
        <?=$setting_key?>: 
        <?php
        if(count($setting['options'])>1)
        {
        ?>
        <select name="<?=$setting_key?>">
            <option  value="" ></option>
            <?php
                $selected='';
                foreach ($setting['options'] as $option_key => $option) 
                {
                    $selected='';
                    if($setting['currentvalue']==$option_key)
                    {
                        $selected='selected';
                    }
                ?>
            <option <?=$selected?> value="<?=$option_key?>" ><?=$option?></option>
                <?php
                }
            ?>
        </select>
    <?php
        }
        else
        {
         
        ?>
        <input type="text" name="<?=$setting_key?>" value="<?=$setting['currentvalue']?>">
        <?php
        }
    }
    ?>
    </form>
    <div onclick="salva_impostazioni_archivio_settings(this,'<?=$idarchivio?>')">Salva</div>
    
</div>