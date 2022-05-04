<?php
$funzione = $data['funzione'];
$tableid = $data['tableid'];
$recordid = $data['recordid'];
$type=$data['type'];
$table_index=$data['table_index'];
$table_param=$data['table_param'];
$label=$data['label'];
$settings=$data['settings'];
$cliente_id=$settings['cliente_id'];
//  echo $funzione;
if($table_param=='null')
        $table_param='';
?>
<?php
if(array_key_exists('records', $data))
{
    echo $data['records'];
}
?>
<?php
if(array_key_exists('records_linkedmaster', $data))
{
    echo $data['records_linkedmaster'];
}
?>
<?php
if(array_key_exists('table', $data))
{
    echo $data['table'];
}
?>
<div class="clearboth"></div>
<?php
if((($funzione == 'inserimento')||($funzione == 'modifica')||($funzione == 'scheda'))&&($type=='linked')/*&&!(array_key_exists('table', $data))*/)
{
?>
<div class="menu_small" style="border-bottom: 0px;padding: 1px;border-top: 0px;height: 27px;">
    
<?php
if($cliente_id!='Work&Work')    
{
    $target=$table_settings['linked_new'];
    $layout=$table_settings['scheda_layout'];
    if($target=='popup')
        $layout=$table_settings['popup_layout'];
?> 
    <!--<div class="add_linked btn_custom btn_plus_popup first" style="float: left;margin-left: 5px;display: none;" onclick="apri_scheda_record(this,'<?=$tableid?>','null','popup','allargata','linked_table')" tabindex="0"><div class="plus_popup">+</div></div>-->
    <button class="btn_fab menu_list_button new_linked" style="float: left;display: none;" onclick="apri_scheda_record(this,'<?=$tableid?>','null','<?=$target?>','<?=$layout?>','linked_table','inserimento')"><i class="material-icons">add</i></button>
<?php
}
?>
<?php
if($cliente_id!='Uniludes')    
{
?> 
    <!--<div class="btn_custom btn_plus_bottom  first" style="float: left;margin-left: 5px;" onclick="linked_table_add(this)" tabindex="0"><div class="plus_bottom">+</div></div>-->
<?php
}
?>
</div>
<?php
}
?>
    




<?php if (($funzione == 'ricerca')&&($type=='linked')) { ?>
<div class="menu_small">
                    <div class="btn_scritta " onclick="table_param_onclick(this,'and')" style="width: 40%;float: left;" tabindex="0">e anche</div>
                    <div class="btn_scritta last " onclick="table_param_onclick(this,'or')" style="width: 40%;float: left;" tabindex="0">oppure</div>   
</div>
            <?php } ?>
<div class="clearboth"></div>