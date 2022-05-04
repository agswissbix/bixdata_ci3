<?php
$report=$data['report'];
$reportid=$report['reportid'];
$name=$report['name'];
$layout=$report['layout'];
$columns=$report['columns'];
$results=$report['results'];
$fieldtype=$report['fieldtype'];
$pie_data='';
foreach ($results as $key => $row) {
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    $label=$row[0];
    $value=$row[1];
    if($value=='')
    {
        $value=0;
    }
 else 
     {
    if($fieldtype=='Calcolato')
    {
        $value=  1;
    }
    }
    if($key!=0)
    {
      $pie_data=$pie_data.',';  
    }
    $pie_data=$pie_data."{value:$value,label:'$label',color:'$color',highlight:'#005887'}";
}


?>
    <script>
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var pieData = [<?=$pie_data?>];
    $('#canvas').ready(function(){
            var ctx = document.getElementById("chart-area").getContext("2d");
            window.myPie = new Chart(ctx).Pie(pieData);
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
            <div class="clearboth"></div>
        </div>
        
        <canvas id="chart-area" style="height: calc(100% - 30px);width: 100%"></canvas>
    </div>
	

