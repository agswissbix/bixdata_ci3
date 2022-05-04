<style type="text/css">
    th{
        padding: 5px;
        border-bottom: 1px solid black;
    }
    td{
        padding: 5px;
    }
</style>
<div style="font-family: Arial;padding: 20px;font-size: 10px;padding-top: 50px">
    <div style="float: left;">
        <?=$azienda['ragionesociale']['value']?><br/>
        <?=$azienda['paese']['value']?><br/>
        <?=$azienda['indirizzo']['value']?><br/>
    </div>
    <div style="float: right">
        <b>Fattura FV-111017</b><br/>
        Pregassona
    </div>
    <div style="clear: both">
    <div style="margin-top: 80px;">
        <table style="text-align: left;font-size: 10px;">
            <thead>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Prezzo Unitario</th>
                <th>Prezzo Totale</th>
            </thead>
            <tbody>
                <?php
                foreach ($vendita_righe as $key => $vendita_riga) {
                ?>
                <tr>
                    <td><?=$vendita_riga['prodotto']['value']?></td>
                    <td><?=$vendita_riga['quantita']['value']?></td>
                    <td><?=$vendita_riga['prezzo']['value']?></td>
                    <td><?=$vendita_riga['totale']['value']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 50px">
        <table style="text-align: left;font-size: 10px;">
            <thead>
            <th>% IVA</th>
            <th>Imponibile IVA</th>
            <th>Importo IVA</th>
            <th>Totale IVA incl.</th>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $importo=$vendita['importo']['value'];
                    $importo_iva=$importo*0.08;
                    $totale=$importo+$importo_iva;
                    ?>
                    <td>8%</td>
                    <td><?=$importo?></td>
                    <td><?=$importo_iva?></td>
                    <td><?=$totale?></td>
                </tr>
            </tbody>
        </table>
        
    </div>
</div>