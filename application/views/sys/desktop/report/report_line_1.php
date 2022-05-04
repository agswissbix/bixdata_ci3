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

    var lineChartData = {
            labels : [<?=$labels?>],
            datasets : [
                    {
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "rgba(151,187,205,1)",
                        pointColor: "rgba(151,187,205,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data : [<?=$values?>]
                    }
            ]

    }
    
    
    $('#canvas_<?=$reportid?>').ready(function(){
            /*var ctx = document.getElementById("canvas_<?=$reportid?>").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});*/
            var ctx = document.getElementById("canvas_<?=$reportid?>").getContext("2d");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                datasets: [
                        {
                            label: 'line Dataset',
                            backgroundColor: 'rgba(70, 123, 189, 0.3)',
                            data: [<?=$values?>]
                        }, 
                        {
                            label: 'Bar Dataset',
                            backgroundColor: 'rgba(80, 50, 189, 0.3)',
                            data: [10, 20, 70, 40],
                            type: 'line'
                        }
                    ],
                labels: [<?=$labels?>]
              },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
    });

    </script>
    
    <div id="report_table" class="report">
        <div class="report_name" style="float: left"><?=$name?></div>
        <div style="float: right">
            <div class="btn_scritta" onclick="selezione_viste_report(this,'<?=$tableid?>','<?=$reportid?>')">Viste</div>
            <div class="btn_scritta" onclick="elimina_report(this,'<?=$reportid?>')">Del</div>
        </div>
        <canvas id="canvas_<?=$reportid?>" style="height: calc(65%);width: 100%"></canvas>
    </div>
	

