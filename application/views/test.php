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
        
        <!-- multiple email input -->
        <link rel="stylesheet" href="<?php echo base_url("/assets/emails-input-master/src/css/app.css") ?>?v=<?=time();?>" />
        <link rel="stylesheet" href="<?php echo base_url("/assets/emails-input-master/src/css/lib/email.css") ?>?v=<?=time();?>" />
        <script src="<?php echo base_url('/assets/emails-input-master/src/js/lib/utils.js') ?>"></script>
        <script src="<?php echo base_url('/assets/emails-input-master/src/js/lib/emails-input.js') ?>"></script>
        <script src="<?php echo base_url('/assets/emails-input-master/src/js/app.js') ?>"></script>
        
        
        <script type="text/javascript" src="<?php echo base_url('/assets/EasyAutocomplete/jquery.easy-autocomplete.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.themes.min.css") ?>?v=<?=time();?>" />
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.min.css") ?>?v=<?=time();?>" />


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
    
    var autocomplete_azienda_options = [
            { label: "Nessuna azienda", id: "", category: "" },
            { label: "Nessuna azienda2", id: "", category: "" }

          ];
          
          $( ".aziendaautocomplete" ).autocomplete({
                source: autocomplete_azienda_options,
                minLength: 0,
                select: function(event, ui) {
                     }
            });
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
    scheda_dati_ricerca_container_width=screen_width*0.40-30;
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
    if(($cliente_id=='3p')&&(($userid==1)||($userid==2))&&$active)
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
        
        
        
        
        
        
        <input id='label_azienda'  class="aziendaautocomplete input_azienda_label" value="">
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
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
