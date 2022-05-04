<?php
$reportid=$report['reportid'];
$name=$report['name'];
$layout=$report['layout'];
$columns=$report['columns'];
$results=$report['results'];
$fieldtype=$report['fieldtype'];

//IMPOSTO LABELS
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

//IMPOSTO VALORI
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

  
    
    $('#canvas_<?=$reportid?>').ready(function(){
            var ctx = document.getElementById("canvas_<?=$reportid?>").getContext("2d");
            var myChart = new Chart(ctx, {
                type: '<?=$layout?>',
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
    
    
        <canvas id="canvas_<?=$reportid?>" style="height: calc(65%);width: 100%"></canvas>
	

