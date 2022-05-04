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
<div style="color: red"> LISTA DIPENDENTI DA RIAPRIRE</div>
</div>
<table style="width: 100%;">
    <thead style="background-color: #ffe29e">
    <th>ID</th><th>Cognome</th><th>Nome</th><th>Dal</th><th>Zone lavorative</th><th>Prima professione</th><th>Seconda professione</th><th>Terza professione</th><th>Note</th>
    </thead>
    <tbody>
    
<?php

foreach ($rows as $key => $row) {
    /*$data=$row['datachiusura'];
    if(isnotempty($row['datachiusura2']))
    {
        $data=$row['datachiusura2'];
    }
    if(isnotempty($row['datachiusura3']))
    {
        $data=$row['datachiusura3'];
    }
    if(isnotempty($row['datachiusura4']))
    {
        $data=$row['datachiusura4'];
    }
    
    $aperture=$row['aperture'];
    foreach ($aperture as $key => $apertura) {
        $datachiusura=$apertura['datachiusura'];
        if(isnotempty($datachiusura))
        {
            $data=$datachiusura;
        }
    }*/
    
    $data=$row['ultimadatachiusura'];
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
        <td><?=$row['id']?></td><td><?=$row['cognome']?></td><td><?=$row['nome']?></td><td style="color: red"><?=date('d/m/Y',  strtotime ($data.' +1 days'))?></td><td><?=$row['zonelavorative']?></td><td></td><td></td><td></td><td><?=$row['ultimenote']?></td>
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