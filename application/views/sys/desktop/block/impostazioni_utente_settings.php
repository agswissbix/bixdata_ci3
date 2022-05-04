<div id="impostazioni_user_settings" class="block" >
    Settings<br/>
    <form id="impostazioni_user_settings_form" style="height: 80%;overflow: scroll">
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
    <div onclick="genera_stampa('test_stampa_portrait')">Test stampa portrait</div>
    <div onclick="genera_stampa('test_stampa_landscape')">Test stampa landscape</div>
    <div onclick="salva_impostazioni_user_settings(this)">Salva</div>
    
</div>