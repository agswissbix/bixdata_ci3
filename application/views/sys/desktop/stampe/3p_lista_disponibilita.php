<style type="text/css">

    
    th, td {
    padding: 3mm !important;
    text-align: left;
  }
</style>
<?php
foreach ($pages as $key => $page) {
    $rows=$page;

?>
<div class="print_page">
<div style="background-color: #ffe29e">
<div style="color: red"> 1a LISTA DELLE DISPONIBILITA'</div>
</div>
<table style="width: 100%;">
    <thead style="background-color: #ffe29e">
    <th>ID</th><th>Cognome</th><th>Nome</th><th>Disponibile dal</th><th>Zone lavorative</th><th>Prima professione</th><th>Seconda professione</th><th>Terza professione</th><th>Note</th>
    </thead>
    <tbody>
    
<?php

foreach ($rows as $key => $row) {
    $tr_css='';
    if($row['visto']=='dir')
    {
       $tr_css='background-color:LightGreen'; 
    }
    if($row['visto']=='amm')
    {
        $tr_css='background-color:yellow';
    }
    if($row['dipendenteinterno']=='si')
    {
        $tr_css='background-color:coral';
    }
?>
    <tr style="<?=$tr_css?>">
        <td><?=$row['id']?></td><td><?=$row['cognome']?></td><td><?=$row['nome']?></td><td style="color: red"><?=date('d/m/Y',  strtotime ($row['disponibilita']))?></td><td><?=$row['zonelavorative']?></td><td></td><td></td><td></td><td><?=$row['notegenerali']?></td>
    </tr>
<?php    
}
?>
    </tbody>
</table>
</div>
<?php
}
?>