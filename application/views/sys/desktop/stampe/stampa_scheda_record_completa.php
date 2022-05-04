<?php
$labels=$data['labels'];
$fissi=$data['block']['fissi'];
?>
<link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/commonPrint.css") ?>?v=<?=time();?>" />
<div id="stampa_scheda_record_completa" style="background-color: white !important;border:1px solid rgb(192,192,192);margin:30px auto auto;min-width: 850px;width: 80%;">
<?php
echo $fissi;
?>
<?php
foreach ($labels as $key => $label) {
    ?>
    <div style="border-bottom: 1px solid rgb(223, 223, 223);margin: 20px;padding: 10px;">
        <div style="float: left;background-color: gray;height: 20px;width: 20px;"></div>
        <div style="float: left;margin-left: 10px;"><?=$label['label']?></div>
        <div style="clear: both"></div>
    </div>
    
    <?php
    if($label['type']=='master')
    {
        ?>
    <div class="print_tableContainer" style="margin: auto;width: 90%;margin-bottom: 25px;border: 1px solid #eaeaea;">
    <?php
        foreach ($label['fields'] as $key => $field) 
        {
            $field_id=$field['fieldid'];
            $field_desc=$field['description'];
            $field_value=$field['valuecode'][0]['value'];
            if(($field_value!='')&&($field_value!=null))
            {
        ?>
        <div class="print_fieldcontainer" style="background-color: #eaeaea;border-bottom: 1px solid #eaeaea;">
            <div class="print_fieldLabel" style="float: left;height: 30px;line-height: 30px;padding-right: 1%;text-align: right;width: 19%;">
                <?=$field_desc?>
            </div>
            <div class="print_fieldValueContainer">
                <?=$field_value?> <br/>
            </div>
            <div style="clear: both;"></div>
        </div>
             
        <?php
            }
        }
    ?>
    </div>  
    <?php
    }
    if($label['type']=='linked')
    {
        if(array_key_exists('records', $label))
        {
            $records=$label['records'];
            foreach ($records as $key => $record) {
                ?>
    <div class="print_tableContainer" style="margin: auto;width: 90%;margin-bottom: 25px;border: 1px solid #eaeaea;">
    <?php
                $fields=$record['fields'];
                foreach ($fields as $key => $field) 
                {
                    $field_id=$field['fieldid'];
                    $field_desc=$field['description'];
                    $field_value=$field['valuecode'][0]['value'];
                    if(($field_value!='')&&($field_value!=null))
                    {
                ?>
                    <div class="print_fieldcontainer" style="background-color: #eaeaea;border-bottom: 1px solid #eaeaea;">
                        <div class="print_fieldLabel" style="float: left;height: 30px;line-height: 30px;padding-right: 1%;text-align: right;width: 19%;">
                            <?=$field_desc?>
                        </div>
                        <div class="print_fieldValueContainer">
                            <?=$field_value?> <br/>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                <?php
                    }
                }
                ?>
    </div>
                    <?php
            }
        }
    }
    ?>
    <?php
}
?>
    
</div>