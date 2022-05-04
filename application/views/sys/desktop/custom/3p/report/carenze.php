<div style="width: 100%;height: 70%;overflow: scroll;background-color: white">
    <table border="1" style="border-collapse: collapse;">
        <thead>
            <th>no</th>
            <th>id</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Carenza CLC</th>
            <th>Carenza INDUSTRIA</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>totore</th>
        </thead>
    <?php

    foreach ($carenze_dipendenti as $key => $carenza_dipendente) {
        $no=$carenza_dipendente['no'];
        $id=$carenza_dipendente['id'];
        $nome=$carenza_dipendente['nome'];
        $cognome=$carenza_dipendente['cognome'];
        $totore=$carenza_dipendente['totore'];
        $carenza_clc=$carenza_dipendente['carenza_clc'];
        $carenza_industria=$carenza_dipendente['carenza_industria'];
    ?>
        <tr>
            <td><?=$no?></td>
            <td><?=$id?></td>
            <td><?=$nome?></td>
            <td><?=$cognome?></td>

            <td><?=$carenza_clc?></td>
            <td><?=$carenza_industria?></td>
            <td></td>
            <td></td>
            <td><?=$totore?></td>

        </tr>
        <?php
       }
        ?>    
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totale_carenza_clc?></td>
            <td><?=$totale_carenza_industria?></td>
            <td></td>
            <td></td>
            <td><?=$totale_totore?></td>
        </tr>
        
    </table>
</div>
