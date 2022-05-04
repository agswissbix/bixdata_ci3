<?php
$report=$data['report'];
$reportid=$report['reportid'];
$name=$report['name'];
$layout=$report['layout'];
$columns=$report['columns'];
$results=$report['results'];
$fieldtype=$report['fieldtype'];
$labels='';
foreach ($results as $key => $row) {
    $label=$row[0];
    if($key!=0)
    {
      $labels=$labels.',';  
    }
    if($label=='')
    {
        $label='non assegnato';
    }
    $label=  str_replace("'", "", $label);
    $labels=$labels."'$label'";
}
$values='';
foreach ($results as $key => $row) {
    $value=$row[1];
    if($value=='')
    {
        $value=0;
    }
    

    if($fieldtype=='Calcolato')
        {
            sscanf($value, "%d:%d", $hours, $minutes);
            $value = $hours * 60 + $minutes;
        }
    
    if($key!=0)
    {
      $values=$values.',';  
    }
    $value=  str_replace("'", "", $value);
    $values=$values."'$value'";
}
?>
    <script>
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var barChartData_<?=$reportid?> = {
            labels : [<?=$labels?>],
            datasets : [
                    {
                            fillColor : "#467bbd",
                            strokeColor : "rgba(220,220,220,0.8)",
                            highlightFill: "#9bd3f1",
                            highlightStroke: "rgba(220,220,220,1)",
                            data : [<?=$values?>]
                    }
            ]

    }
    $('#canvas_<?=$reportid?>').ready(function(){
            var ctx_<?=$reportid?> = document.getElementById("canvas_<?=$reportid?>").getContext("2d");
            window.myBar = new Chart(ctx_<?=$reportid?>).Bar(barChartData_<?=$reportid?>, {
                    responsive : true
            });
    });

    </script>
    <div id="report_table" class="report">
        <div class="report_header">
            <div class="report_name" style="float: left"><?=$name?></div>
            <div style="float: right">
                <div class="btn_scritta" onclick="selezione_viste_report(this,'<?=$tableid?>','<?=$reportid?>')">Viste</div>
                <div class="btn_scritta" onclick="elimina_report(this,'<?=$reportid?>')">Del</div>
                <div class="btn_scritta" title="<?=$reportid?>">i</div>
            </div>
        </div>
        <div class="clearboth"></div>
        <canvas id="canvas_<?=$reportid?>" style="height: calc(90%);width: 100%"></canvas>
    </div>
	

