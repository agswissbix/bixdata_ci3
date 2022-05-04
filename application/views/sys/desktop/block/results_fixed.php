<style type="text/css">
    .users {
  table-layout: fixed;
  width: 100%;
  white-space: nowrap;
  
}
.users td {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

th{
    width: 50px;
}

.th0{
    width: 100px;
}
</style>
    
        <table class='users'  style="table-layout: fixed" border="1"  >
            <thead>
                <tr>
                    <th class="th0" >record</th>
                    <?php
                    foreach ($columns as $key => $column) {
                    ?>
                            <th class="th<?=$key?>" style=""><?=$key."-".$column['desc']?></th>
                        
                    <?php       
                    }
                    ?>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($records as $key => $record) {
                ?>
                <tr class="results_record" style="">
                    
                    <?php
                    foreach ($record as $key => $value) {
                     ?>
                    <td><?=$value?></td>
                    <?php
                    }
                    ?>
                </tr>

                <?php
                }
                ?>
            </tbody>
        </table>

    
