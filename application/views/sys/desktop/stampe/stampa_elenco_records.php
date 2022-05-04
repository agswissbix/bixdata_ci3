<?php
$preview_key=null;
?>
<div class="block_container  datatable_records_container container" style="height: 100%;width: 100%;"  >

    <table style="width: 100%;border-collapse: collapse;font-family: calibri;font-size: 9px;">
        <?php
        foreach ($columns as $column_key => $column) {
            if($column['id']=='record_preview')
            {
               $preview_key=$column_key; 
            }
            if(($column_key!=0)&&($column_key!=1)&&($column_key!=2))
                    {
                    $column_desc=  conv_text($column['desc']);
        ?>
        <th style="text-align: left;">
            <?=$column_desc?>
        </th>
        <?php
                    }
        }
        ?>
        
        <?php
            foreach ($records as $record_key => $record) {
                $recordid=$record[0];
            ?>
        <tr style="border: 1px solid #d0d0d0;<?=$record['2']?>">
            <?php
                foreach ($record as $column_valuekey => $column_value) {
                    if(($column_valuekey!=0)&&($column_valuekey!=1)&&($column_valuekey!=2))
                    {
                ?>
                <td>
                    <?php
                    if($column_valuekey==$preview_key)
                    {
                    ?>
                    <img src="http://192.168.1.30:8822/JDocServer//record_preview/<?=$tableid?>/<?=$recordid?>.jpg?1480475454841" style="height:55px;width:75px;">
                    <?php
                    }
                    else
                    {
                        echo $column_value;
                    }
                    ?>
                </td>
                <?php
                    }
                }
            ?>
            
        </tr>
        <?php
            }
        ?>
        
    </table>
</div>