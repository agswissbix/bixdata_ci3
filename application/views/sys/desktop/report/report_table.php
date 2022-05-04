<?php
$report=$data['report'];
$reportid=$report['reportid'];
$name=$report['name'];
$layout=$report['layout'];
?>
<div id="report_table" class="report" style="height: auto;">
    <div class="report_header">
        <div class="report_name" style="float: left"><?=$name?></div>
        <?php
        if($cliente_id!='3p')
        {
        ?>
        <div style="float: right">
            <div class="btn_scritta" onclick="selezione_viste_report(this,'<?=$tableid?>','<?=$reportid?>')">Viste</div>
            <div class="btn_scritta" onclick="elimina_report(this,'<?=$reportid?>')">Del</div>
            <div class="btn_scritta" title="<?=$reportid?>">i</div>
        </div>
        
        <?php
        }
        
    ?>
        <div class="clearboth"></div>
    </div>
    
        
        <table class="report_table">
    <?php
                foreach ($report['columns'] as $key => $column) {
                }
                ?>
                <tbody>
                <?php
                foreach ($report['results'] as $key => $row) {
                    echo "<tr style='font-size:28px'>";
                    foreach ($row as $key => $value) {
                        echo "<td style='padding:0px;'>".$value."</td>";
                    }
                    echo "</tr>";
                }
                ?>
                    <tbody>
        </table>
</div>
