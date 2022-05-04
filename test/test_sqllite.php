<?php

$myPDO = new PDO('sqlite:CtiServerDatabasejournal.db');
$result = $myPDO->query("SELECT * FROM journal ORDER BY StartTime desc");
$rows = [];
while ($row = $result->fetchObject()) {
    $rows[] = $row;
}
        
var_dump($rows);
?>