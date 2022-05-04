<?php
$report=$data['report'];
$reportid=$report['reportid'];
$name=$report['name'];
$layout=$report['layout'];
$columns=$report['columns'];
$results=$report['results'];
$fieldtype=$report['fieldtype'];
$doughnut_data='';
?>
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
    <div style="float: left;width: 40%;overflow: hide;">
    <?php
    foreach ($results as $key => $row) {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
        $label=$row[0];
        $value=$row[1];
        $value_originale=$value;
        if($value=='')
        {
            $value=0;
        }
        if($label=='')
        {
            $label='non assegnato';
        }
        if($fieldtype=='Calcolato')
        {
            sscanf($value, "%d:%d", $hours, $minutes);
            $value = $hours * 60 + $minutes;
        }
        
        if($key!=0)
        {
          $doughnut_data=$doughnut_data.',';  
        }
        $value=  str_replace("'", "", $value);
        $label=  str_replace("'", "", $label);
        $doughnut_data=$doughnut_data."{value:$value,label:'$label $value_originale',color:'$color',highlight:'#9bd3f1'}";
        ?>
    <div style="margin-bottom: 3px;">
        <div style="height: 20px;width: 20px;background-color: <?=$color?>;float: left"></div>
        <div style="float: left"><?=$label?>: <?=$value_originale?></div>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
   </div> 
    <div style="float: left;margin-left: 10%; width: 40%;height: 90%">
       
        <canvas id="canvas_<?=$reportid?>" style="height: calc(90%);width: 100%"></canvas>
    </div>
    <div class="clearboth"></div>
</div>
	

<script>
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var doughnutData = [<?=$doughnut_data?>];
    $('#canvas_<?=$reportid?>').ready(function(){
            var ctx = document.getElementById("canvas_<?=$reportid?>").getContext("2d");
            window.myDoughnut = new Chart(ctx).Doughnut(doughnutData);
    });

    </script>