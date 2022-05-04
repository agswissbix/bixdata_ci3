<div id="impostazioni_archivio_alert" class="block" style="overflow: scroll;height: 100%;">
    <form id="impostazioni_archivio_settings_form">
    <?php
    foreach ($alerts as $alert_key => $alert) {
        $alert_id=$alert['id'];
    ?>
        <br />
        <div class="alert">
        
        <input type="text" name="alert[update][<?=$alert_id?>][alert_description]" value="<?=$alert['alert_description']?>">
        <br />
        <!-- ALERT TYPE -->
        <select id='alert_update_<?=$alert_id?>_alert_type' name="alert[update][<?=$alert_id?>][alert_type]">
            <option>recordcss</option>
            <option>dailymail</option>
            <option>mid_dailymail</option>
            <option>weeklymail</option>
            <option>monthlymail</option>
        </select>    
        <script type="text/javascript">
            $('#alert_update_<?=$alert_id?>_alert_type').ready(function (){
                $('#alert_update_<?=$alert_id?>_alert_type').val('<?=$alert['alert_type']?>');
            })
        </script>
        
        <!-- ALERT CONDITION -->
        <input type="text" name="alert[update][<?=$alert_id?>][alert_condition]" value="<?=$alert['alert_condition']?>">
        
        <!-- ALERT CONDITION -->
        <input type="text" name="alert[update][<?=$alert_id?>][alert_param]" value="<?=$alert['alert_param']?>">
        
        <!-- ALERT USER -->
        <select>
            <?php
                foreach ($users as $key_user => $user) {
                    ?>
            <option><?=$user['username']?>-<?=$user['id']?></option>
            <?php
                }
            ?>
        </select>
        <input type="text" name="alert[update][<?=$alert_id?>][alert_user]" value="<?=$alert['alert_user']?>">
        
        <!-- ALERT VIEW -->
        <select id='alert_update_<?=$alert_id?>_alert_viewid' name="alert[update][<?=$alert_id?>][alert_viewid]">
            <option value=""></option>
            <?php
                foreach ($views as $key => $view) {
                    ?>
                    <option value="<?=$view['id']?>"><?=$view['name']?></option>
            <?php
                }
            ?>
        </select>
        <script type="text/javascript">
            $('#alert_update_<?=$alert_id?>_alert_viewid').ready(function (){
                $('#alert_update_<?=$alert_id?>_alert_viewid').val('<?=$alert['alert_viewid']?>');
            })
        </script>
        
        
        <!-- ALERT STATUS -->
        <select id='alert_update_<?=$alert_id?>_alert_status' name="alert[update][<?=$alert_id?>][alert_status]">
            <option value="enabled">Enabled</option>
            <option value="disabled">Disabled</option>
            <option value="test">Test</option>
        </select>
        <script type="text/javascript">
            $('#alert_update_<?=$alert_id?>_alert_status').ready(function (){
                $('#alert_update_<?=$alert_id?>_alert_status').val('<?=$alert['alert_status']?>');
            })
        </script>
        <?php
        if(($alert['alert_type']=='dailymail')||($alert['alert_type']=='monthlymail')||($alert['alert_type']=='weeklymail'))
        {
        ?>
            <div onclick="mail_alert_run(<?=$alert['id']?>)">Avvia</div>
        <?php
        }
        ?>
        </div>
        
        
        
    <?php
    }
    ?>
        <!-- NUOVO -->
        
        <div class="alert">
            <br/>
        Nuovo <br/>
        
        <input type="hidden" name="alert[new][tableid]" value="<?=$tableid?>">
        <input type="text" name="alert[new][alert_description]" >
        <br />
        
        <!-- ALERT TYPE -->
        <select name="alert[new][alert_type]">
            <option>recordcss</option>
            <option>dailymail</option>
            <option>mid_dailymail</option>
            <option>weeklymail</option>
            <option>monthlymail</option>
        </select>    

        
        <!-- ALERT CONDITION -->
        <input type="text" name="alert[new][alert_condition]" >
        
        <!-- ALERT CONDITION -->
        <input type="text" name="alert[new][alert_param]" >
        
        <!-- ALERT USER -->
        <select>
            <?php
                foreach ($users as $key_user => $user) {
                    ?>
            <option><?=$user['username']?>-<?=$user['id']?></option>
            <?php
                }
            ?>
        </select>
        <input type="text" name="alert[new][alert_user]">
        
        <!-- ALERT VIEW -->
        <select name="alert[new][alert_viewid]">
            <option value=""></option>
            <?php
                foreach ($views as $key => $view) {
                    ?>
                    <option value="<?=$view['id']?>"><?=$view['name']?></option>
            <?php
                }
            ?>
        </select>

        
        
        <!-- ALERT STATUS -->
        <select name="alert[new][alert_status]">
            <option value="enabled">Enabled</option>
            <option value="disabled">Disabled</option>
            <option value="test">Test</option>
        </select>

        </div>
    </form>
    
    <br/><br/>
        <div onclick="salva_impostazioni_archivio_alert(this,'<?=$idarchivio?>')">Salva</div>
</div>