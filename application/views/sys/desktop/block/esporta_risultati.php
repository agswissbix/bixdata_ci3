<form id="form_esporta" name="form_esporta" method="post" action="<?php echo site_url('sys_viewcontroller/'); ?>/esporta_xls" style="">
    <input id="tableid" type="hidden" name="tableid" value="<?=$data['tableid']?>">
    <input id="query" type="hidden" name="query" value="null">
    <input id="esporta_recordid" type="hidden" name="esporta_recordid" value="null">
    <!--<input type="hidden" name="columns[]" value="recordid_" checked="true"/> -->
    <fieldset style="padding: 10px;">
        <legend>Colonne da esportare</legend>
        <!--<input type="checkbox" value="" checked="true" onclick="$('.field_check').prop('checked', !$('.field_check').prop('checked'));"/> <br/>-->
        <?php
        foreach ($data['columns'] as $key => $column) {
           // if(($column['tablelink']=='')||($column['tablelink']==null))
           // {
               // if(strtolower($column['label'])!='old')
                //{
            if(($column['fieldid']!='dacontattare')&&($column['fieldid']!='ultimocontatto'))//CUSTOM ABOUT-X
            {
         ?>
            
            <input class="field_check" type="checkbox" name="columns[]" value="<?=$column['fieldid']?>" checked="true"/> <?=$column['description']?>
            <br /> 
        <?php
            }
                //}
           // }
        }
        ?>
        
    </fieldset>
    <br/>
    <div class="btn_scritta" onclick="$('#form_esporta').submit()">
        Esporta Excel
    </div>
</form>
<br/>

<br/>
