
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function(){
                    load_block_scheduler_log(this);;
                },10000);
});
</script>
<div id="block_scheduler_log" class="block" style="overflow: scroll;height: 100%;">
    scheduler log <br/>
    <?php
    if(count($scheduler_log)>0)
    {
    ?>
    <table style="border-collapse: collapse;border: 1px solid black;">
        <thead>
           <?php
               foreach ($scheduler_log[0] as $key => $value) {
                ?>
                <th><?=$key?></th>
                <?php
               }
           ?>
        </thead>
        <tbody>
        <?php
        foreach ($scheduler_log as $key => $log_row) {
        ?>
            <tr>
                <?php
            foreach ($log_row as $key => $value) {
                ?>
                <td style="border: 1px solid black;padding: 5px;"><?=$value?></td>
                <?php
            }
                ?>
            </tr>
        <?php
        }
        ?>
        </tbody>
        
    </table>
    <?php
        
    }
    
    ?>
</div>