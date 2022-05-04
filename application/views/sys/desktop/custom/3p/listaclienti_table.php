<table>
          <thead>
                <th>N°</th>
                <th>Collocatore</th>
                <th>Stato</th>
                <th>Cliente</th>
                <th>1° prezzo</th>
                <th>2° prezzo</th>
                <th>3° prezzo</th>
                <th>4° prezzo</th>
                <th>5° prezzo</th>
                <th>CCL</th>
                <th>CQ Data</th>
                <th>Doc1</th>
                <th>Doc2</th>
                <th>Doc3</th>
                <th>Note</th>
          </thead>
          <tbody>
                <?php
                $counter=0;
                foreach ($clienti as $key => $cliente) {
                    $counter++;
                ?>
                    <tr>
                        <td><?=$counter?></td>
                        <td><?php
                        echo $cliente['origin1'];
                        if(isnotempty($cliente['origin2']))
                        {
                            echo ",".$cliente['origin2'];
                        }
                        if(isnotempty($cliente['origin3']))
                        {
                            echo ",".$cliente['origin3'];
                        }
                        ?>
                        </td>
                        <td><?=$cliente['stato']?></td>
                        <td style="color: blue;background-color: #fce9da"><?=$cliente['cliente']?></td>
                        <td style="background-color: #fce9da"></td>
                        <td style="background-color: #fce9da"></td>
                        <td style="background-color: #fce9da"></td>
                        <td style="background-color: #fce9da"></td>
                        <td style="background-color: #fce9da"></td>
                        <td style="background-color: #fce9da;font-weight: bold"><?=$cliente['ccl']?></td>
                        <td><?php
                        if(isnotempty($cliente['cqdata']))
                        {
                            echo Date('d.m.Y', strtotime($cliente['cqdata']));
                        }
                        ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
                }
                ?>
          </tbody>
      </table>