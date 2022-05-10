<?php
$extraheader=null;
$menu=null;
$content=null;
//$theme=$data['settings']['theme'];

$theme='default';
$menu_show_tables_list=$user_settings['menu_show_tables_list'];
$menu_show_custom_report=$user_settings['menu_show_custom_report'];
$menu_show_custom_resetaccess=$user_settings['menu_show_custom_resetaccess'];
$menu_show_settings=$user_settings['menu_show_settings'];
$archives=$data['settings']['archive'];
$userid=$data['settings']['userid'];
$lastname=$data['settings']['lastname'];
$firstname=$data['settings']['firstname'];
$description=$data['settings']['description'];
$cliente_id=$data['settings']['cliente_id'];
$cliente_nome=$data['settings']['cliente_nome'];
$content_container_class='content_container_simenu';
if($cliente_id=='Work&Work')
{
    $theme='ww';
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.js') ?>"></script>
        
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/jquery-ui.css") ?>" />
        
        <link rel="stylesheet" href="<?php echo base_url("/assets/js/TableTools/css/dataTables.tableTools.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/js/Jcrop/jquery.Jcrop.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/js/chosen/chosen.min.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/jquery.toast/jquery.toast.min.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/fullcalendar/fullcalendar.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/jQuery-contextMenu/dist/jquery.contextMenu.css") ?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/material.css") ?>?v=<?=time();?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/select2/select2.min.css") ?>?v=<?=time();?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/commonstyle.css") ?>?v=<?=time();?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/theme/$theme/customstyle.css") ?>?v=<?=time();?>" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@0.6.2/dist/gridstack.min.css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <style type="text/css">
            body{
                font-size: 14px !important;
            }
            .ui-widget, .custom-table {
                font-size: 14px !important;
            }
            .field{
                font-size: 14px !important;
            }
                
            .wrappe{
                background-image: url('<?php echo base_url('/assets/images/logo_ww_grande2.png') ?>')
            }

            
            <?php
            if($cliente_id=='Swissbix')
            {
            ?>
            .btn_scritta{
                background-color:  #bf4545 !important;
                color: white !important;
            }
            
            #menu_pulsanti .btn_fa{
                color:  #ca112e !important;
            }
            
            #menu_pulsanti .btn_material{
                color:  #ca112e !important;
            }
            
            .menu_btn_container .menu_btn_description{
                color:black;
            }
            
            .label_icona{
                background-color:  #bf4545 !important;
            }
            
            .menu_top .btn_fab{
                background-color: black;
            }
            
            .svg-inline--fa.fa-square.fa-w-14.btn_fa{
                background-color:  #bf4545 !important;
                color:  #bf4545 !important;
            }
            
            .ui-tabs-anchor{
                color: black !important;
                font-weight: bold !important;
            }
            
            tr.even:hover td{
                color: black !important;
                background-color: #ca6d6d !important ;
            }

            tr.odd:hover td {
                color: black !important;
                background-color: #ca6d6d !important ;
            }
            
            .DTTT_selected td {
                color: white !important;
                background-color: #ca112e !important;
            }
            
            tr.odd.DTTT_selected:hover td{
                color: white !important;
                background-color: #ca112e !important;
            }
            tr.even.DTTT_selected:hover td{
                color: white !important;
                background-color: #ca112e !important;
            }
            
            .btn_popup_linkedmaster{
                color: black !important;
            }
            
            .ui-tabs .ui-tabs-nav .ui-corner-top.ui-tabs-active{
            border-bottom: 1px solid black !important
            }
            
            .report_name{
                 color:  #ca112e !important;
            }
            
            .calendar_name{
                 color:  #ca112e !important;
                 font-size: 16px;
                 font-weight: bold;
                 margin-bottom: 10px;
            }
            
            
          
            
            <?php
            }
            ?>

        </style>
        
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.bpopup.min.js') ?>"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/formatter/lib/jquery.formatter.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.dataTables.js') ?>?v=<?=time();?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/dataTables.date-eu.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/TableTools/js/dataTables.tableTools.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/Jcrop/Jquery.Jcrop.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/chosen/chosen.jquery.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.togglepanel.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.scrollTo-1.4.3.1.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/Chart.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/jquery.toast/jquery.toast.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/fullcalendar/lib/moment.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/fullcalendar/fullcalendar.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/select2/select2.full.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/jQuery-contextMenu/dist/jquery.contextMenu.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/jQuery-contextMenu/dist/jquery.ui.position.js') ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/gridstack@0.6.2/dist/gridstack.all.js"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/iro.js-master/dist/iro.js') ?>"></script>
        
        <!--<script type="text/javascript" src="<?php echo base_url('/assets/js/material.js') ?>?v=<?=time();?>"></script>-->
        <script type="text/javascript" src="<?php echo base_url('/assets/js/JDocWebScript.js') ?>?v=<?=time();?>"></script>
        <!-- BOOTSTRAP -->
        <script type="text/javascript" src="<?php echo base_url("/assets/bootstrap-4.0.0/js/bootstrap.min.js") ?>?v=<?=time();?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/assets/esimakin-twbs-pagination/jquery.twbsPagination.min.js") ?>?v=<?=time();?>"></script>
        
        <!--  summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-lite.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-lite.min.js"></script>
        
        <!-- Gridstack -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@0.6.2/dist/gridstack.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/gridstack@0.6.2/dist/gridstack.all.js"></script>
        
        
        
<script type="text/javascript">
var controller_url="<?php echo site_url('sys_viewcontroller/'); ?>/"; 
var script_url="<?php echo site_url('script_controller/'); ?>/"; 
var scheduler_controller_url="<?php echo site_url('scheduler_controller/'); ?>/"; 
var assets_url="<?php echo base_url('assets/'); ?>/";
var custom_3p_giorno_cliccato='';

<?php
/*$project_url=base_url();
$project_url=  str_replace("JDocWeb/", "", $project_url);
$project_url=  str_replace("jdocweb/", "", $project_url);
$project_url=  str_replace("JDocWeb_test/", "", $project_url);
$project_url=  str_replace("JDocWeb_update/", "", $project_url);
$project_url=  str_replace("jdocweb_update/", "", $project_url);*/

$project_url=  domain_url();
$jdocserver_url=  server_url();
?>
var project_url="<?=$project_url ?>/";
var jdocserver_url="<?=$jdocserver_url?>/";
var cliente_id="<?=$cliente_id?>";
var upimage="<?php echo base_url("/assets/css/sys/desktop/theme/$theme/images/icon/up.png") ?>";
var downimage="<?php echo base_url("/assets/css/sys/desktop/theme/$theme/images/icon/down.png") ?>";
var lastval="";

  
var screen_width;
var screen_height;
var viewport_height;
var viewport_width;
var scheda_dati_ricerca_container_width;
var scheda_dati_inserimento_container_width;
var scheda_record_container_width;
var scheda_riepilgo_width;
var scheda_risultati_allargata_width;
var scheda_risultati_compatta_width; 
var scheda_container_visualizzatore;
var bPopup=[];
var bPopup_generico;
var bPopup_generico_small;
var bPopup_linkedrecords;
var bPopup_rapportino;
var bPopup_segnalazione;
var bPopup_gestione_lookuptable;
var bPopup_riepilogo_segnalazioni;
var bPopup_permessi_record;
var bPopup_pushup;
var bPopup_visualizzatore;
var prestampa_popup=null;
var anteprima_prestampa_popup=null;
var iframe_popup=null;
var ultimascheda="";


$(document).ready(function(){
    $("body").css('cursor', 'progress !important');
    screen_width=screen.width;
    screen_height=screen.height;
    window_width=$(window).width();
    window_height=$(window).height();
    
    <?php
   /* if($cliente_id=='Work&Work')
    {
    ?>
        content_container_height=window_height;
    <?php
    }
    else
    {
    ?>
        content_container_height=window_height-60;
    <?php
    }*/
    ?>
    //content_container_height=screen_height-5;
    content_container_height=window_height-60;
    scheda_dati_ricerca_container_width=screen_width*0.16;
    scheda_dati_inserimento_container_width=screen_width*0.43-20;
    scheda_record_container_width=screen_width*0.43-20;
    scheda_risultati_allargata_width=screen_width*0.55;
    
    
    scheda_risultati_compatta_width=screen_width*0.50;
    scheda_riepilgo_width=screen_width*0.16;
    scheda_container_visualizzatore=screen_width-scheda_dati_inserimento_container_width-320;

    
    //$('#content_container').height(content_container_height);    
    
    $( ".menu_list" ).menu();
    $('select').chosen({
        placeholder_text_single:"test"
    });
    
    $( ".menu_list_button" ).hover(
            function() {
              $(this).find('.menu_list').show();
            }, function() {
              $(this).find('.menu_list').hide();
            }
          );
    $( ".menu_btn_container" ).hover(
            function() {
              $(this).find('.btn_fab').show();
              $(this).find('.menu_btn_description_scomparsa').show();
            }, function() {
              $(this).find('.btn_fab').hide();
              $(this).find('.menu_btn_description_scomparsa').hide();
            }
          );
        $('#scheda_record_container_hidden').width(scheda_record_container_width);
        /*$('.tooltip').tooltip({
        position: { my: "center bottom", at: "center-50 top" }
      });*/
    <?php
    $active=true;
    if(($cliente_id=='3p')&&(($userid==2))&&$active)
    {
    ?>
    $.ajax({
        
                url: controller_url+"custom3p_check_servizi",
                dataType:'html',
                success:function(data){
                    if(data==0)
                    {
                        $('#status_servizi').css("background-color","green");
                        $('#status_servizi').html("");
                    }
                    else
                    {
                        $('#status_servizi').css("background-color","yellow");
                        $('#status_servizi').html(data)
                    }

                },
                error:function(){
                    console.info('errore');
                }
            });
            
    setInterval(function(){ 
        $.ajax({
        
                url: controller_url+"custom3p_check_servizi",
                dataType:'html',
                success:function(data){
                    if(data==0)
                    {
                        $('#status_servizi').css("background-color","green");
                        $('#status_servizi').html("");
                    }
                    else
                    {
                        $('#status_servizi').css("background-color","yellow");
                        $('#status_servizi').html(data)
                    }

                },
                error:function(){
                    console.info('errore');
                }
            });
    }, 1000000);
    <?php
    }
    ?>
})

/* commentato perchü creava conflitti con summernote, intercettando il backspace e non permettendo di cancellare
$(document).keydown(function(e) {
var element = e.target.nodeName.toLowerCase();
if (e.keyCode === 8) {
if (element != 'input' && element != 'textarea') {
    
        return false;
    }
}
});
*/

</script>
        <title>Jdoc Web</title>
        <?php
        if($cliente_id=='3p')
        {
        ?>
            <LINK REL="SHORTCUT ICON" HREF="<?php echo base_url("assets/images/custom/3p/logo_3p.png"); ?>" >
        <?php
        }
        else
        {
        ?>
            <LINK REL="SHORTCUT ICON" HREF="<?php echo base_url("assets/images/JDocWeb_icon_128.png"); ?>" >
        <?php
        }
        ?>
        
        <link rel="stylesheet" href="<?php echo base_url("assets/css/sys/desktop/theme/$theme/popup.css");?>" >
    </head>

    <body id="ricerca" class="ui-widget" style="overflow: hidden">
        <form id="hidden_form" method="post" style="display: none;" action="">
            
        </form>
        <div id="onlinecv_popup" class="popup" >
            Esito caricamento: <span style="font-weight: bold" id="response"></span>
            <br /><br />
            Indirizzo per aggiornare il profilo del candidato:
            <br />
            <a style="font-weight: bold;" id="jdoconline_url" href="" target="_blank"></a>
            <div style="margin-top: 50px;">
                <div id="btn_invia_mail" class="btn_scritta">Invia mail al candidato</div>
                <div class="clearboth"></div>
            </div>
        </div>
        <div id="prestampa" class="popup" style="width: 80% !important;background-color: white;overflow: scroll">
        </div>
        <div id="anteprima_prestampa_popup" class="popup" style="width: 100% !important;background-color: white;overflow: scroll">
        </div>
        <div id="iframe_popup" class="popup" style="width: 80% !important;background-color: white;overflow: scroll">
            <iframe src="https://www.w3schools.com"></iframe>
        </div>
        <div id="selezione_viste_report_popup" class="popup" style="width: 50% !important;background-color: white; padding: 20px;" ></div>
        
        <div id="gestione_lookuptable" class="popup" style="width: 50% !important;left: 25% !important">
        </div>
        
        <div id="sPopup" class="popup" style="width: 20% !important;background-color: white;overflow: scroll;height: 20%">
            
        </div>
        
        
        <?php
        if($cliente_id=='Work&Work')
        {
        ?>
        <div id="invio_pushup" class="popup" style="width: 80% !important; height: 90% !important;">
        </div>
        <?php
        }
        ?>
        
        <div id="stampa" style="min-height: 100%;width: 100%;position: absolute;top: 0px;left: 0px;z-index: 100;background-color: white;display: none;">
        </div>
    <div id="jdocweb_wrapper" class="wrapper" data-popuplvl="0" >
                
    <div class="header" id="menu" style="position: relative;z-index: 10; border-bottom: 1px solid #BCBCBC;height: 60px;" data-visible="true" >
    <div id="" style="display: inline-block; float: left;cursor: pointer" >
    <?php
    if($cliente_id=='3p')
    {
    ?>
            <img id="logo_header" src="<?php echo base_url("/assets/images/custom/3p/logo_3p.png") ?>?v=<?=time();?>" onclick="window.location.href = ''//clickMenu(this, 'ajax_load_content_home/desktop')"></img>
    <?php
    }
    else
    {
    ?>
        
            <img id="logo_header" src="<?php echo base_url("/assets/images/logo_JDoc.png") ?>" onclick="window.location.href = ''//clickMenu(this, 'ajax_load_content_home/desktop')"></img>
        
    <?php
    }
    ?>
    </div>
    <div id="menu_pulsanti" style="display: inline-block;float: left;margin-top: 8px; margin-left: 10px;" >
                        <!-- HOME -->
<!-- HOME -->
    <!--<div class="menu_divisore divisore"></div>
    <div class="menu_button"  id="menu_home_icon" onclick="clickMenu(this, 'ajax_load_content_home/desktop')" ></div>      
    <div class="menu_divisore divisore"></div>-->
    <!--<div class="btn_fa fa fa-home tooltip" title="Dashboard" onclick="clickMenu(this, 'ajax_load_content_home/desktop')"></div>
    <!-- ARCHIVI -->
    <?php
    if((($cliente_id!='Dimensione Immobiliare')&&($cliente_id!='Uniludes')&&($cliente_id!='APF-HEV Ticino'))||($userid==1))
    {
    ?>
    <?php
    if(true)
    {
    ?>
    <div style="position: relative;float: left;" onmouseover="$(this).find('.menu_list').show();" onmouseout="$(this).find('.menu_list').hide();">
        <span class="btn_fa fa fa-database menu_list_button "  > </span>

        <div class="menu_list" style="position: absolute;top: 40px;left: 0px;z-index: 100;background-color: white;">
                <?php
        foreach ($archives as $workspace => $workspace_archives) {

                        ?>
                <div class="menu_workspace"><?=$workspace?></div>
                    <?php
                            foreach ($workspace_archives as $key => $workspace_archive) {
                                $table_settings=$workspace_archive['settings'];
                                if($table_settings['hidden']!='true')
                                {
                                if(($workspace_archive['description']!='test')||($userid==1))
                                {
                                    ?>
                <div class="menu_archive"  >

                    <div style="float: left;width: calc(100% - 28px)" onclick="clickMenu(this,'ajax_load_content_ricerca/<?=$workspace_archive['id']?>/desktop')"><?=$workspace_archive['description']?></div>
                        <?php
                        if($table_settings['edit']=='true')
                        {
                        ?>
                        <button class="btn_fab menu_list_button" style="float: right;display: none;background-color: red;height: 26px;width: 26px;margin-top: 1px;" onclick="apri_scheda_record(this,'<?=$workspace_archive['id']?>','null','popup','allargata')"><i class="material-icons">add</i></button>
                        <?php
                        }
                        ?>
                    <div class="clearboth"></div>
                </div>

                <?php
                                }
                                }
                            }
                    }

                    ?>
                
                
            </div>
    </div>
    <?php
    }
    ?>
    <?php
    foreach ($archives as $workspace => $workspace_archives) 
    {
        foreach ($workspace_archives as $key => $workspace_archive) {
            $table_settings=$workspace_archive['settings'];
            if(($table_settings['menu']=='true')&&($table_settings['hidden']=='false'))
            {
            ?>
                <div class="menu_btn_container">
                    <?php
                    $display='';
                    $scomparsa='menu_btn_description_scomparsa';
                    if($table_settings['icon']=='database')
                    {
                        $display='display: block';
                        $scomparsa='';
                    }
                    ?>
                    <div class="menu_btn_description <?=$scomparsa?>" style="<?=$display?>"><?=$workspace_archive['description']?></div>
                    <?php
                    if($table_settings['icon_type']=='fontawesome')
                    {
                    ?>
                        <div class="btn_fa <?=$table_settings['icon']?> tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/<?=$workspace_archive['id']?>/desktop/')" ></div> 
                    <?php
                    }
                    ?>
                    <?php
                    if($table_settings['icon_type']=='material')
                    {
                    ?>
                        <div class="btn_material" onclick="clickMenu(this,'ajax_load_content_ricerca/<?=$workspace_archive['id']?>/desktop/')" ><i class="material-icons"><?=$table_settings['icon']?></i></div>
                    <?php
                    }
                    ?>
                        <?php
                        if($table_settings['edit']=='true')
                        {
                        ?>
                    <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'<?=$workspace_archive['id']?>','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
                    <?php
                        }
                    ?>
                </div>
            <?php
            }
        }
    }
    ?>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description menu_btn_description_scomparsa">CALENDARIO</div>
        <div class="btn_fa fa fa-calendar tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/agenda/desktop')" ></div> 
    </div>
    <div class="menu_btn_container">
        <div class="menu_btn_description menu_btn_description_scomparsa">DASHBOARD</div>
        <div class="btn_fa fa fa-line-chart tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_dashboard')" ></div> 
    </div>-->
    <?php
    ?>
    
    <?php
    }
    ?>
    <?php
    //CUSTOM
    if($cliente_id=='Dimensione Immobiliare')
    {
    ?>
    
    <!--<div class="menu_list_button" style="width: 60px;float: left;"  > 
        <button class="btn_fab menu_list_button" style="background-color: red"><i class="material-icons">add</i></button>
            <ul class="menu_list" >
            <?php
        foreach ($archives as $workspace => $workspace_archives) {
                    ?>
            <li style="font-weight: bold;"><?=$workspace?></li>
                <?php
                        foreach ($workspace_archives as $key => $workspace_archive) {
                            if(($workspace_archive['description']!='test')||($userid==1))
                            {
                            ?>
.                            <li><a href="#" onclick="apri_scheda_record(this,'<?=$workspace_archive['id']?>','null','popup','allargata','home_menu')"><?=$workspace_archive['description']?></a></li>


            <?php
                            }
                        }
                }
                ?>
        </ul>
    </div>-->
    <div class="menu_btn_container" style="width: 65px">
        <div class="menu_btn_description" style="display: block">VENDITA CH</div>
        <div class="btn_fa fa fa-home tooltip " style="margin-left: 10px" title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="margin-left: 10px" style="" onclick="apri_scheda_record(this,'immobili','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container" style="width: 65px">
        <div class="menu_btn_description" style="display: block">AFFITTO CH</div>
        <div class="btn_fa fa fa-home tooltip " style="margin-left: 10px" title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili/desktop/31')" ></div> 
        <button class="btn_fab menu_list_button" style="margin-left: 10px" style="" onclick="apri_scheda_record(this,'immobili','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container" style="width: 65px">
        <div class="menu_btn_description" style="display: block">VENDITA IT</div>
        <div class="btn_fa fa fa-home tooltip " style="margin-left: 10px" title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili/desktop/53')" ></div> 
        <button class="btn_fab menu_list_button" style="margin-left: 10px" style="" onclick="apri_scheda_record(this,'immobili','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container" style="width: 65px">
        <div class="menu_btn_description" style="display: block">AFFITTO IT</div>
        <div class="btn_fa fa fa-home tooltip " style="margin-left: 10px" title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili/desktop/54')" ></div> 
        <button class="btn_fab menu_list_button" style="margin-left: 10px" style="" onclick="apri_scheda_record(this,'immobili','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">CONTATTI</div>
        <div class="btn_fa fa fa-user tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'contatti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description" style="display: block">RICHIESTE</div>
        <div class="btn_fa fa fa-user tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_richiesti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'immobili_richiesti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <div class="menu_btn_container">
        <div class="btn_fa fa fa-address-book tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'contatti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
     </div>
     <div class="menu_btn_container">
        <div class="btn_fa far fa-handshake tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_proposti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'immobili_richiesti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
     </div>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">PROPOSTE</div>
        <div class="btn_fa fa fa-check-circle-o tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_proposti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'immobili_proposti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">AGENDA</div>
        <div class="btn_fa fa fa-calendar tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/agenda/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'agenda','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <div class="menu_btn_container">
        <div class="menu_btn_description">TELEMARKETING</div>
        <div class="btn_fa fa fa-phone tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/telemarketing/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'telemarketing','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">TELEMARKETING</div>
        <div class="btn_fa fa fa-phone-square tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/telemarketing/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'telemarketing','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">DOCUMENTI</div>
        <div class="btn_fa fa fa-file-text-o tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/documenti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'documenti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <!-- CUSTOM Dimensione Immobiliare -->
    <?php
    if($cliente_id=='Dimensione Immobiliare')
    {
    ?>
    <div style="position: relative;float: left;" onmouseover="$(this).find('.menu_list').show();" onmouseout="$(this).find('.menu_list').hide();">
        <span class="btn_fa fas fa-print menu_list_button "  > </span>
        <div class="menu_list" style="position: absolute;top: 40px;left: 0px;z-index: 100;background-color: white;">
            <?php
            $citta="Lugano";
            if(($userid==1)||($userid==2)||($userid==4)||($userid==5))
            {
                $citta="Lugano";
            }
            if(($userid==3)||($userid==6)||($userid==7)||($userid==8)||($userid==9)||($userid==10))
            {
                $citta="Sopraceneri";
            }
            ?>
                <div style="font-weight: bold;">Vendita <?=$citta?></div>  
                <?php
                $files=scandir("stampe/modelli/DimensioneImmobiliare/NonCompilati/Vendita/$citta"); 
                foreach ($files as $key => $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                    ?>
                    <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Vendita-<?=$citta?>','<?=$file?>')"><?=$file?></a></li>
                    <?php
                    }
                }
                ?>  
                <li style="font-weight: bold;">Affitto <?=$citta?></li>  
                <?php
                $files=scandir("stampe/modelli/DimensioneImmobiliare/NonCompilati/Affitto/$citta"); 
                foreach ($files as $key => $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                    ?>
                    <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Affitto-<?=$citta?>','<?=$file?>')"><?=$file?></a></li>
                    <?php
                    }
                }
                ?>     
            <?php
            if(($userid==1)||($userid==2))
            {
                $citta="Sopraceneri";
            ?>
                    <li style="font-weight: bold;">Vendita <?=$citta?></li>  
                <?php
                $files=scandir("stampe/modelli/DimensioneImmobiliare/NonCompilati/Vendita/$citta"); 
                foreach ($files as $key => $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                    ?>
                    <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Vendita-<?=$citta?>','<?=$file?>')"><?=$file?></a></li>
                    <?php
                    }
                }
                ?>  
                <li style="font-weight: bold;">Affitto <?=$citta?></li>  
                <?php
                $files=scandir("stampe/modelli/DimensioneImmobiliare/NonCompilati/Affitto/$citta"); 
                foreach ($files as $key => $file) {
                    if(($file!='.')&&($file!='..'))
                    {
                    ?>
                    <li><a href="#" onclick="stampa_template('DimensioneImmobiliare-NonCompilati-Affitto-<?=$citta?>','<?=$file?>')"><?=$file?></a></li>
                    <?php
                    }
                }
                ?>
            <?php
            }
            ?>

                
        </div>
    </div> 
    <?php
    }
    ?>
    
    
    
    
        
    <?php
    }
    ?>
    
    <?php
    //CUSTOM
    if(($cliente_id=='APF-HEV Ticino'))
    {
    ?>
    
    <!--<div class="menu_list_button" style="width: 60px;float: left;"  > 
        <button class="btn_fab menu_list_button" style="background-color: red"><i class="material-icons">add</i></button>
            <ul class="menu_list" >
            <?php
        foreach ($archives as $workspace => $workspace_archives) {
                    ?>
            <li style="font-weight: bold;"><?=$workspace?></li>
                <?php
                        foreach ($workspace_archives as $key => $workspace_archive) {
                            if(($workspace_archive['description']!='test')||($userid==1))
                            {
                            ?>
.                            <li><a href="#" onclick="apri_scheda_record(this,'<?=$workspace_archive['id']?>','null','popup','allargata','home_menu')"><?=$workspace_archive['description']?></a></li>


            <?php
                            }
                        }
                }
                ?>
        </ul>
    </div>-->
    <div class="menu_btn_container">
        <div class="menu_btn_description">CONTATTI</div>
        <div class="btn_fa fa fa-user tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'contatti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container">
        <div class="menu_btn_description">IMMOBILI</div>
        <div class="btn_fa fa fa-home tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'immobili','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">CONTATTI</div>
        <div class="btn_fa fa fa-user tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/contatti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'contatti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <div class="menu_btn_container">
        <div class="menu_btn_description">EVENTI</div>
        <div class="btn_fa fa fa-wrench tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/eventi/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'eventi','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <!--<div class="menu_btn_container">
        <div class="menu_btn_description">PROPOSTE</div>
        <div class="btn_fa fa fa-check-circle-o tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/immobili_proposti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'immobili_proposti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>-->
    <div class="menu_btn_container">
        <div class="menu_btn_description">AGENDA</div>
        <div class="btn_fa fa fa-calendar tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/agenda/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'agenda','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container">
        <div class="menu_btn_description">TELEFONATE</div>
        <div class="btn_fa fa fa-phone tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/telefonate/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'telefonate','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    <div class="menu_btn_container">
        <div class="menu_btn_description">DOCUMENTI</div>
        <div class="btn_fa fa fa-file-text tooltip " title=""   onclick="clickMenu(this,'ajax_load_content_ricerca/documenti/desktop')" ></div> 
        <button class="btn_fab menu_list_button" style="" onclick="apri_scheda_record(this,'documenti','null','popup','allargata','home_menu')"><i class="material-icons">add</i></button>
    </div>
    
    <?php
    }
    ?>
    
    <?php
    //CUSTOM WORK&WORK
    if($cliente_id=='Work&Work')
    {
    ?>
    <div class="btn_fa fa fa-user tooltip btn_menu_custom" title="Candidati"   onclick="clickMenu(this,'ajax_load_content_ricerca/CANDID/desktop')"  ></div> 
     <div class="btn_fa fa fa-envelope-o tooltip btn_menu_custom" title="Invio modulo online"   onclick="invio_mail_modulo(this)"  ></div>
    <div class="btn_fa fa fa-building tooltip btn_menu_custom" title="Aziende"   onclick="clickMenu(this,'ajax_load_content_ricerca/AZIEND/desktop')"  ></div> 
    <div class="btn_fa fa fa-file-text-o tooltip btn_menu_custom" title="Contratti"   onclick="clickMenu(this,'ajax_load_content_ricerca/CONTRA/desktop')"  ></div>
    <?php
    }
    ?>
    
    <?php
if($cliente_id=='Work&Work')
{
?>
        <div class="btn_fa fa fa-print menu_list_button btn_menu_custom"  > 
            <ul class="menu_list">
                <li><a onclick="clickMenu(this,'ajax_load_content_gestione_bollettino/desktop')">Bollettino</a></li>
            </ul>
        </div>
<?php
}
?>
<?php
    //CUSTOM WORK&WORK
    if(($cliente_id=='Work&Work')||($cliente_id=='Uniludes'))
    {
    ?>    
<!-- RICERCA -->
<div class="btn_fa fa fa-search menu_list_button "  > 
    
        <ul class="menu_list">
            <?php
    foreach ($archives as $workspace => $workspace_archives) {
                
                    ?>
            <li style="font-weight: bold;"><?=$workspace?></li>
                <?php
                        foreach ($workspace_archives as $key => $workspace_archive) {
                            if(($workspace_archive['description']!='test')||($userid==1))
                            {
                                ?>
                                <li><a href="#" onclick="clickMenu(this,'ajax_load_content_ricerca/<?=$workspace_archive['id']?>/desktop')"><?=$workspace_archive['description']?></a></li>
                            
            <?php
                            }
                        }
                }
                
                ?>
        </ul>
</div>
       

<!-- INSERIMENTO -->
<div class="btn_fa fa fa-pencil menu_list_button"  > 
        <ul class="menu_list">
        <?php
foreach ($archives as $workspace => $workspace_archives) {
                ?>
        <li style="font-weight: bold;"><?=$workspace?></li>
            <?php
                    foreach ($workspace_archives as $key => $workspace_archive) {
                        if(($workspace_archive['description']!='test')||($userid==1))
                        {
                        ?>
                        <!--<li><a href="#" onclick="clickMenu(this,'ajax_load_content_inserimento/<?=$workspace_archive['id']?>/desktop')"><?=$workspace_archive['description']?></a></li>-->
                        <li><a href="#" onclick="apri_scheda_record(this,'<?=$workspace_archive['id']?>','null','popup','allargata','home_menu')"><?=$workspace_archive['description']?></a></li>


        <?php
                        }
                    }
            }
            ?>
    </ul>
</div>
<?php
}
?>
<?php
if(false&&($cliente_id!='Dimensione Immobiliare')&&($cliente_id!='Interfida'))
{
?>
<div class="btn_fa fa fa-files-o tooltip" title="Code"  onclick="autobatchimport(this)"  ></div> 
<?php
}
?>

<!-- CODE 
<div class="btn_fa fa fa-files-o tooltip" title="Code"  onclick="clickMenu(this,'ajax_load_content_gestione_code/desktop')"  ></div> 
-->
<?php
if($cliente_id=='BaseUniludes')
{
?>
<!-- CALENDARIO -->
    <div class="menu_button"  id="menu_calendario_icon" onclick="clickMenu(this,'ajax_load_content_calendario/desktop')"  ></div> 
    <div class="menu_divisore divisore"></div>
<!-- REPORT -->
    <div class="menu_button"  id="menu_report_icon" onclick="clickMenu(this,'ajax_load_content_report/desktop')"  ></div> 
    <div class="menu_divisore divisore"></div>
<?php
}
?>
<?php
    //CUSTOM 3P
    if($cliente_id=='3p')
    {
    ?>
    <!--<div style="position: relative;float: left;" onmouseover="$(this).find('.menu_list').show();" onmouseout="$(this).find('.menu_list').hide();">
        <span class="btn_fa fas fa-print menu_list_button "  > </span>
        <div class="menu_list" style="position: absolute;top: 40px;left: 0px;z-index: 100;background-color: white;">
            <li><a href="#" onclick="download_export('Elenco_Telefonico_Dipendenti_Attivi.pdf')">Elenco telefonico dipendenti attivi</a></li>
            <li><a href="#" onclick="download_export('Lista_Disponibilita.pdf')">Lista disponibilità</a></li>
            <li><a href="#" onclick="download_export('Elenco_Dipendenti_DaChiudere.pdf')">Elenco dipendenti da chiudere</a></li>
            <li><a href="#" onclick="download_export('Elenco_Dipendenti_DaRiaprire.pdf')">Elenco dipendenti da riaprire</a></li>
                     
            

                
        </div>
    </div>-->
    
        
        <?php
        if($user_settings['elenco_telefonico']=='true')
        {
        ?>
            <div class="menu_btn_container" style="width: 65px">
                <div style="width: 100%;text-align: center"><a class="btn_fa fa fa-print" style="height: 30px" onclick="genera_stampa('elenco_telefonico')"></a></div>
                <div style="margin-top: 3px;width: 100%;text-align: center;color:#54ACE0;font-weight: bold;font-size: 10px;line-height: 8px">Elenco telefonico</div>
            </div>
        <?php
        }
        ?>
        <?php
        if($user_settings['lista_disponibilita']=='true')
        {
        ?>
        <div class="menu_btn_container" style="width: 65px">
            <div style="width: 100%;text-align: center"><a class="btn_fa fa fa-print" style="height: 30px" onclick="genera_stampa('lista_disponibilita')"></a></div>
            <div style="margin-top: 3px;width: 100%;text-align: center;color:#54ACE0;font-weight: bold;font-size: 10px;line-height: 8px">Lista disponibilità</div>
        </div>
        <?php
        }
        ?>
        <?php
        if($user_settings['dipendenti_da_chiudere']=='true')
        {
        ?>
        <div class="menu_btn_container" style="width: 65px">
            <div style="width: 100%;text-align: center"><a class="btn_fa fa fa-print" style="height: 30px" onclick="genera_stampa('lista_dachiudere')"></a></div>
            <div style="margin-top: 3px;width: 100%;text-align: center;color:#54ACE0;font-weight: bold;font-size: 10px;line-height: 8px">Dipendenti da chiudere</div>
        </div>
        <?php
        }
        ?>
        <?php
        if($user_settings['dipendenti_da_riaprire']=='true')
        {
        ?>
        <div class="menu_btn_container" style="width: 65px">
            <div style="width: 100%;text-align: center"><a class="btn_fa fa fa-print" style="height: 30px" onclick="genera_stampa('lista_dariaprire')"></a></div>
            <div style="margin-top: 3px;width: 100%;text-align: center;color:#54ACE0;font-weight: bold;font-size: 10px;line-height: 8px">Dipendenti da riaprire</div>
        </div>
        <?php
        }
        ?>
        


    
    <?php
    }
    ?>

<!-- IMPOSTAZIONI -->

        <?php
        if(($userid==1)||($userid==2))
        {
        ?>
            <div class="menu_btn_container">
                <div class="btn_fa fa fa-cog tooltip" title=""  onclick="clickMenu(this,'ajax_load_content_impostazioni_preferenze/desktop')"  ></div> 
            </div>  
        <?php
        }
        ?>

        
    </div>
        <div class="contentmenu" style="float: left;margin-top: 28px;margin-left: 10px;display: none;">
            <div id="navigatore" style="float: left">
                <div class="btn_scritta nav" id="nav_ricerca" data-position="0" data-target_id="nav_ricerca" onclick="$('#content_ricerca').scrollTo($('#scheda_dati_ricerca_container'),500);"  >Filtri</div>
                <div class="btn_scritta nav" id="nav_risultati" data-position="0" data-target_id="nav_risultati" onclick="$('#content_ricerca').scrollTo($('#scheda_risultati'),500);" >Risultati</div>
                <div class="btn_scritta nav" id="nav_scheda_hidden" data-position="0" onclick="move_scrollbar(this)" style="display: none;">Risultati</div>
            </div>

            <div class="clearboth"></div>
        </div> 
        <?php
    //CUSTOM WORK&WORK
    if($cliente_id=='Work&Work')
    {
    ?>
        <!--<div class="btn_scritta" onclick="ajax_get_new_records(this,'CANDID')">Sync</div>-->
        <?php
    }
        ?>
        <div style="float: right">
            <div class="tooltip btn_fa fa fa-question" title="Istruzioni" style="float: none;font-size: 14px !important;"  onclick="ajax_load_block_manuale()"></div><br/>
            <div class="tooltip btn_fa fa fa-bolt" title="" style="float: none;font-size: 14px !important;margin-top: 2px"  onclick="ajax_load_block_segnalazioni()"></div><br/>
            <div class="tooltip btn_fa fas fa-sign-out-alt" title="" style="float: none;font-size: 14px !important;margin-top: 2px"  onclick="signout()"></div>
        </div>
        <div id="" style="display: inline-block; float: right;height: 60px;margin-right: 5px; cursor: pointer;" onclick="" >
            <div id="menu_name" style="height: 30px;font-weight: bold;text-align: center"><?=$firstname?> <?=$lastname?></div>
            <div id="menu_description" style="text-align: center"><?=$description?></div>
        </div>
           <?php
        //CUSTOM WORK&WORK
        if($cliente_id=='Work&Work')
        {
        ?>
            <!--<div class="btn_scritta" style="float: right;" onclick="location.href = 'http://192.168.1.2/JDocWeb/'">Vecchia versione</div>-->
        <?php
        }
        ?>
            
        <div id="" style="display: inline-block; float: right;" >
            <?php
            if(file_exists("../JDocServer/avatar/$userid.jpg"))
            {
                $avatar_url=domain_url()."/JDocServer/avatar/$userid.jpg?v=".time();
            }
            else
            {
                $avatar_url=  base_url('/assets/images/anon.png');
            }
            ?>
            <img id="avatar" src="<?=$avatar_url?>"></img>
        </div>
            <?php
            if(($userid==1)||($userid==2))
            {
            ?>
                <div id="status_servizi" style="height:20px;width: 40px;background-color: green;display: inline-block; float: right;text-align: center">
                    </div>
            <?php
            }
            ?>           
            
            
            <?php
            if($cliente_id=='About-x')
            {
            ?>
                
            <div style="float: right" onclick="$(this).next().toggle()">
                link
            </div>
            <div style="display: none;position: relative;float: right">
                <div style="height: 300px;width: 300px;background-color: white;position: absolute;top: 0px;right: 0px;">
                    <br/>
                    <textarea id="recordid_to_link" name="recordid_to_link">
                        
                    </textarea>
                    <br/>
                    <br/>
                    <div class="btn_scritta" onclick="conferma_collega_record(this)">Collega</div>
                    <div class="btn_scritta" onclick="$('#recordid_to_link').val('')">Svuota</div>
                </div>
            </div>
            
                    
            <?php
            }
            ?>
        
        
        <div style="clear: both;">
                        
    </div>
                    
                </div>
                <div id="scheda_record_container_hidden" class="scheda_container content_scheda_container scheda_record_container scheda_record_container_hidden"  style="float: left;display: none">
           
            
                </div>
                <div id="manuale_container" class="popup"></div>
                <div id="segnalazioni_container" class="popup"></div>
                <div id="riepilogo_segnalazioni_container" class="popup"></div>
                <div id="" class="popup bPopup_generico" style="background-color: white;overflow: scroll"></div>
                <div id="" class="popup bPopup_generico_small" style="background-color: white;overflow: scroll;height: 20% !important;width: 20% !important;"></div>
                <div id="" class="popup bPopup_rapportino" style="background-color: white;overflow: scroll"></div>
                 <div id="" class="popup bPopup_linkedrecords" style="background-color: white;overflow: scroll"></div>
                <div id="content_container"classbPopup_generico="content_container" >
                <?=$data['content']?>

                </div>
                                    </div>


                                    </body>
                                    </html>
<script type="text/javascript">
        
        //questo pezzo di codice serve per forzare la posizione della freccia la prima volta
        if($('#menu').is(':hidden'))
           $('#pulsantino').attr('src',downimage);
        if($('#menu').is(':visible'))
           $('#pulsantino').attr('src',upimage);
       
        //questa funzione invece serve per impostare l'icona giusta nel momento in cui il mouse si allontana dal pulsantino
        $('#pulsantino').mouseleave(function(){
        if($('#menu').is(':hidden'))
           $('#pulsantino').attr('src',downimage);
       if($('#menu').is(':visible'))
           $('#pulsantino').attr('src',upimage);
   });
</script>
