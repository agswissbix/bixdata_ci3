<?php
$settings=$data['settings'];
$userid=$settings['userid'];
$cliente_nome=$settings['cliente_nome'];
?>
<style type="text/css">
    .scheda .report_header
    {
        display: none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tabs').tabs({ 
            active: <?=$activetab?>,
            beforeActivate: function( event, ui ) {
                var dashboardid=$('#'+ui.newPanel.attr('id')).data('dashboardid');
                clickMenu(this,'ajax_load_content_home/desktop/'+dashboardid)
            }
        }        
            );
        
        $('#helper').bind('click', function(e) {
            

                // Prevents the default action to be triggered. 
                e.preventDefault();

                // Triggering bPopup when click event is fired
                $('#helper_popup').bPopup();

            });
            $('.contentmenu').hide();
            
            <?php
            
            if($settings['cliente_id']=='Interfida2')
            {
            ?>
                var url=controller_url+'ajax_load_block_calendar_dashboard/agenda/';
                $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                        $('#dashboard_calendar').html(data);


                    },
                    error:function(){
                        alert('error');
                    }
                });
                
                var url=controller_url+'ajax_load_block_datatable_records/matching/';
                $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                        $('#dashboard_matching').html(data);


                    },
                    error:function(){
                        alert('error');
                    }
                });
            <?php
            }
            ?>
                    
                    <?php
            if(($settings['cliente_id']=='APF-HEV Ticino'))
            {
            ?>
                var url=controller_url+'ajax_load_block_calendar_dashboard/agenda/';
                $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                        $('#dashboard_calendar').html(data);


                    },
                    error:function(){
                        alert('error');
                    }
                });
                
                var url=controller_url+'ajax_load_block_datatable_records/telefonate/';
                $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                        $('#dashboard_matching').html(data);


                    },
                    error:function(){
                        alert('error');
                    }
                });
            <?php
            }
            ?>
                    
            
    }
);
</script>
            <div id="helper_popup" class="popup" style="height: 70%;width: 70%;">
                
            </div>

<?php
if($settings['cliente_id']=='About-x')
{
?>
    <?php
    if(($userid=='5')||($userid=='6')||($userid=='7')||($userid=='23'))
    {
    ?>
        <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_about.png') ?>')" class="content">
    <?php
    }
    else
    {
    ?>
        <?php
        if($userid=='3')
        {
        ?>
            <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_about.png') ?>')" class="content">
        <?php
        }
        else
        {
        ?>
            <?php
            if($userid=='25')
            {
            ?>
                <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_demo.png') ?>')" class="content">
            <?php
            }
            else
            {
            ?> 
                <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_about.png') ?>')" class="content">
            <?php
            }
        }
            ?>
    <?php
    }
    ?>
<?php
}
?>
                    
    <?php
if($settings['cliente_id']=='Swissbix')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_swissbix.png') ?>'); background-size: 1000px 253px; " class="content"> 
<?php
}
?>

                    <?php
if($settings['cliente_id']=='')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logouniludes.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='Work&Work')
{
    if($userid==100)
    {
    ?>
      <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_wswork.png') ?>'); background-size: 500px 500px; " class="content">   
    <?php
    }
    else
    {
?>
        <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_ww_grande.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
    }
}
?>
    
<?php
if($settings['cliente_id']=='MC Agustoni')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logomcagustoni.jpg') ?>'); background-size: 891px 160px; " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='Piona')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_piona.png') ?>'); background-size: 1024px 309px; " class="content"> 
<?php
}
?>
    

    
    <?php
if($settings['cliente_id']=='AVinformatica')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_avinformatica.png') ?>'); background-size: 1120px 337px; " class="content"> 
<?php
}
?>
    
    <?php
if($settings['cliente_id']=='3p')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_3p.png') ?>'); background-size: 545px 468px; " class="content"> 
<?php
}
?>
     <?php
if($settings['cliente_id']=='18-24')
{
?>
<div id="home_content" style="background-color:white;background-image: url('<?php //echo base_url('/assets/images/logo_18-24.png') ?>'); background-size: 389px 343px; " class="content"> 
<?php
}
?>
    <?php
if($settings['cliente_id']=='MunicipioGiubiasco')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_municipiogiubiasco.png') ?>'); background-size: 124px 190px; " class="content"> 
<?php
}
?>
    
     <?php
if($settings['cliente_id']=='Clinica Santa Croce')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_santacroce.png') ?>'); background-size: 90% 138px; " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='VIS')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_vis.jpg') ?>'); background-size: 891px 160px; " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='Schlegel')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/custom/Schlegel/logo_schlegel.png') ?>'); background-size: 600px  " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='ptm')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/custom/ptm/logo_ptm.png') ?>'); background-size: 369px  " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='Uniludes')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logouniludes.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
}
?>
    
<?php
if($settings['cliente_id']=='BB Kapital')
{
?>
<div id="home_content"  class="content">
    <div style="font-size: 100;color: #A9A9A9;border: 1px solid #9bd3f1;padding: 20px;border-radius: 4px;position: absolute;top: 30%;left: 50%;">BB Kapital</div>
<?php
}
?>
    
<?php
if($settings['cliente_id']=='Cortesi')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_cortesi.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
}
?>
    

    <?php
if($settings['cliente_id']=='svicom')
{
?>
<div id="home_content"  class="content">
    <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_svicom.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
}
?>
        
<?php
if(($settings['cliente_id']=='Dimensione Immobiliare'))
{
?>
<div id="home_content"  class="content">
    <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_dimensioneimmobiliare.png') ?>'); background-size: 850px 230px; " class="content"> 
        
    <?php
}
?>
        
                    <?php
if(($settings['cliente_id']=='APF-HEV Ticino'))
{
?>
<div id="home_content"  class="content">
    <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/Logo_APF-HEV_Ticino.png') ?>'); background-size: 850px 230px; " class="content"> 
        <div id="dashboard_calendar" class=" dashboard_calendar scheda" style="height: 500px;width: 600px;margin: 20px;margin-right: 50px;float: right">
            
        </div>    
        <div id="dashboard_matching" class="dashboard_matching scheda" style="height: 350px;width: 600px;margin: 20px;float: left">
            
        </div> 
    <?php
}
?>
    
            
        
<?php
if($settings['cliente_id']=='Intervia')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_intervia.png') ?>'); background-size: 500px 500px; " class="content"> 
<?php
}
?>    
    
    <?php
if($settings['cliente_id']=='Interfida')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url("/assets/images/logo_interfida.png?v=".time()) ?>'); background-size: 800px 200px; background-position: bottom; " class="content"> 
<?php
}
?>    
<?php
if($settings['cliente_id']=='NewTrends')
{
?>
<div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_newtrends.png') ?>?v=<?=time();?>'); background-position: center;background-repeat: no-repeat;background-size: 300px 300px " class="content"> 
<?php
}
?>  
    
<?php
if($settings['cliente_id']=='Sea Trade')
{
?>
<div id="home_content"  class="content"> 
    <div id="home_content" style="background-image: url('<?php echo base_url('/assets/images/logo_seatrade.jpg') ?>'); background-size: 1000px 500px; " class="content"> 
        

<?php
}
?>
    
<div id="tabs" style="height: 100%;background: none !important;">
    <ul>
<?php

$counter=0;
$blocchi=array('','','','');
$dashboards=$data['dashboards'];
 
foreach ($dashboards as $key => $dashboard) {
    ?>
   
        <li><a href="#tabs-<?=$key?>">Dashboard</a></li>
    
<?php
}
?>
        </ul>
    
<?php
foreach ($dashboards as $key => $dashboard) {
    if(array_key_exists('block', $dashboard))
    {
        ?>
        <div id="tabs-<?=$key?>" style="height: 85%;" data-dashboardid="<?=$key?>">
        <?php
        $blocks=$dashboard['block'];
        foreach ($blocks as $block_key => $block ) {
        $blocchi[$counter]=$block;      
        $counter++;
        ?>
            <div class="scheda" style="margin: 10px;float: left;width: <?=$block['width']?>%;height: <?=$block['height']?>%;background-color: rgba(255, 255, 255, 0.9) !important">
                <div style="font-weight: bold;height: 20px;">
                    <?=$block['title']?>
                </div>
                <div class="block_container  datatable_records_container container" style="height:calc(100% - 20px);width: 100%;"  >
                    <?=$block['content']?>
                </div>
            </div>


        <?php
        }
        ?>
        </div>
        <?php
    }

}
?>
        
</div>
    <?php
    if($settings['cliente_id']=='future')
    {
    ?>
        <div class="scheda" style="margin: 10px;float: left;width: 585px;height: 102;"> 
            <img src="<?php echo base_url('/assets/images/logo_futureaccounting.jpg') ?>" style="width: 585px;height: 102">
        </div>
    <?php
    }
    ?>
    
    <div class="clearboth"></div>
<!--
<div align="center" id="scheda_candidato" style="display: none; width: 50%; width: 50%;"></div>
<div class="blocco" style="margin: 30;margin-top:0; ">
    <img onclick="show_menu();" style="height: 20px;width: 20px;float: left" id="pulsantino" src="<?php echo base_url("/assets/css/sys/desktop/default/images/down_black.png") ?>" onmouseover="ChangeIcon();"></img>
    <div style="float: left"><h3>JDocWEB DashBoard</h3></div>
                    
             <div style="float: right" class="help_icon" id="helper"></div>
             <div class="clearboth"></div>
</div>-->





<!--
<div style="float:left;">
            <div class="blocco" style="height: 300px;width: 300px; border: 1px solid #dedede;margin: 20px;padding: 5px;border-radius: 4px;background-color: white">
                <h3 style="color:#467bbd;border-bottom: 1px solid #dedede">Tabelle</h3>
                <table class="custom-table">
                    <tr><td>11</td><td>12</td></tr>
                    <tr><td>21</td><td>22</td></tr>
                    <tr><td>11</td><td>12</td></tr>
                    <tr><td>21</td><td>22</td></tr>
                    <tr><td>11</td><td>12</td></tr>
                    <tr><td>21</td><td>22</td></tr>
                    <tr><td>11</td><td>12</td></tr>
                    <tr><td>21</td><td>22</td></tr>
                </table>
            </div>
</div>
<div style="float:left;">
    <div class="blocco" style="height: 300px;width: 500px; border: 1px solid #dedede;margin: 20px;padding: 5px;border-radius: 4px;background-color: white">
        <h3 style="color:#467bbd;border-bottom: 1px solid #dedede">Grafici</h3>
        <br/>
        <div style="height: 10px;width: 100px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
        <div style="height: 10px;width: 200px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
        <div style="height: 10px;width: 70px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
        <div style="height: 10px;width: 50px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
        <div style="height: 10px;width: 140px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
        <div style="height: 10px;width: 400px;background-color: #467bbd;border: 1px solid #467bbd; "></div><br/>
    </div>
</div>
-->

        

<!-- <div>
    <div style="width: 100%;height: 50%;">
        <div style="float:left;width:35%">
            <?=$blocchi[1]?>
        </div>
        <div style="float:right;width:35%;">
            <?=$blocchi[3]?>
        </div>
    </div>
    <div style="width: 100%;height: 50%;">    
        <div style="float:left;width:100%">
            <?=$blocchi[2]?>
        </div>
        <div style="float:right;width:0%">
            <?=$blocchi[3]?>
        </div>
    </div>
</div> -->
 


<script type="text/javascript">
    var url="<?php echo site_url('sys_viewcontroller/ajax_load_block_scheda_record/CANDID') ?>";
    function OpenTabCandidato(recordid)
    {
        var address;
        address=url + '/' + recordid + '/desktop/';
         $('#scheda_candidato').bPopup({
            content:'iframe {width:50%; height: 50%}', //'ajax', 'iframe' or 'image'
            loadUrl: address,//Uses jQuery.load()
            onOpen: function(){
                $('#scheda_candidato').html('');
            }
        });
    }
</script>
</div>