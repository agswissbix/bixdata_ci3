<?php
    $filename="sheet.xls";
    header ("Content-Type: application/vnd.ms-excel");
    header ("Content-Disposition: inline; filename=$filename");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang=it><head>
<title>Titolo</title></head>
<body>
<table border="1">
<?php
$records=array();
if(count($records)>0)
{
    echo '<tr>';
    foreach ($columns as $key => $column) {
        if(($key!=0)&&($key!=1)&&($key!=2))
        {
            $fielddesc=conv_text($column['desc']);
            echo '<td>';
            echo $fielddesc;
            echo '</td>';
        }
    }
    echo '</tr>';
    foreach ($records as $key => $record) {
        echo '<tr>';
        foreach ($record as $key => $value) {
            if(($key!=0)&&($key!=1)&&($key!=2))
            {
                    $value_array= explode('|:|', $value);
                    $value=$value_array[0];
                    $value=  conv_text($value);
                    echo '<td>';
                    echo $value;
                    echo '</td>';
            }
        }
        echo '</tr>';
    }
    
}
?>
</table>
</body>
</html>