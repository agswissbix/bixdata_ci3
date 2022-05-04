<script type="text/javascript">
    
    $(document).ready(function() {
	 var table_riepilogo=$('#table_segnalazioni').dataTable({
            "aaSorting": [ [5,'asc'], [0,'desc'] ],
            "bJQueryUI": true,
            scrollY:        '70vh',
            scrollCollapse: true,
            "scrollCollapse": true,
            "bAutoWidth":true,
            paging: false,
            "bFilter": false,
            "columns": [
                { className: "my_class" },
                { className: "my_class" },
                { className: "TipoSegnalazione" },
                { className: "my_class" },
                { className: "my_class" },
                { className: "my_class" },
                { className: "my_class" },
                { className: "my_class" }
              ],
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                if(aData[5]=='4. Chiusa')
                {
                   $('td', nRow).css({
                                     "background-color": "#C6FBC6",
                                     "color":"black",
                                   }); 
                }
                if(aData[5]=='5. Non fattibile')
                {
                   $('td', nRow).css({
                                     "background-color": "#E1E1E1",
                                     "color":"black",
                                   }); 
                }
                if(aData[5]=='0. Annullata')
                {
                   $('td', nRow).css({
                                     "background-color": "#E1E1E1",
                                     "color":"black",
                                   }); 
                }
                if(aData[5]=='6. In attesa di feedback')
                {
                   $('td', nRow).css({
                                     "background-color": "rgb(255, 255, 177)",
                                     "color":"black",
                                   }); 
                }
                
            }
         }
        );
table_riepilogo.columns( '.TipoSegnalazione' ).search( 'Important' ).draw();
        
    });
</script>

<div class="blocco scheda" style="">
    <br/>
    <table id="table_segnalazioni" class="datatable custom-table ui-widget" style="">
        <thead>
            <th>
            Data
            </th>
            <th>
            Segnalatore
            </th>
            <th>
            TipoSegnalazione
            </th>
            <th>
            TipoIntervento
            </th>
            <th>
            Testo
            </th>
            <th>
            Stato
            </th>
            <th>
            Risposta
            </th>
            <th>
            Durata    
            </th>
        </thead>
        <tbody>
            <?php
            foreach ($segnalazioni as $key => $segnalazione) {
            ?>
            <tr>
                <td><?=$segnalazione['datasegnalazione']?></td>
                <td><?=$segnalazione['segnalatore']?></td>
                <td><?=$segnalazione['tipo']?></td>
                <td><?=$segnalazione['tipointervento']?></td>
                <td><?=$segnalazione['note']?></td>
                <td><?=$segnalazione['stato']?></td>
                <td><?=$segnalazione['risposta']?></td>
                <td><?=$segnalazione['totore']?></td>
                
            </tr>
            <?php
            }
            ?>
            
        </tbody>
    </table>
</div>