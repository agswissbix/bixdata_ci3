<?php
$columns=$data['columns'];
    $records=$data['records'];
    $filename="sheet.csv";
    header ("Content-Type: text/csv");
     header ("Content-Disposition: inline; filename=$filename");

   /* header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header("Cache-Control: max-age=0");*/
?>
"prefix","Employee Name","Contact"
"Mr.","John","07868785831"
"Miss","Linda","0141-2244-5566"
"Master","Jack","0142-1212-1234"
"Mr.","Bush","911-911-911"
