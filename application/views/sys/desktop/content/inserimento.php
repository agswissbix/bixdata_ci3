<?php
$block = $data['block'];
$schede = $data['schede'];
$cliente_id=$data['settings']['cliente_id'];
?>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.scrollTo-1.4.3.1.js') ?>"></script>

<script type="text/javascript">
//varabili globali
    var schedaid = 0;    //numero per identificare le schede aperte
    var screen_width;
    var scheda_campi_width;
    var scheda_riepilgo_cowidth;
    var scheda_risultati_allargata_width;
    var scheda_risultati_compatta_width;
    var scheda_record_width;
    var pinned = false;
    var ultimascheda = "";





    function show(id)
    {
        $('#sortable1').hide();
        $('#sortable2').hide();
        $('#' + id).show();
    }






    function apply() {
        //$(".tooltip").tooltip();
    }



    function move_scrollbar(ele)
    {
        var nav_id = ele.id;
        target_id = $("#" + nav_id).attr('data-target_id');
        target = $('#' + target_id);
        $('#content_ricerca').scrollTo(target, 1000);

    }


    function set_pinned()
    {
        $('#' + ultimascheda).children('#pin_scheda').css("background-color", "black");
        $('#' + ultimascheda).children('#pin_scheda').css("color", "white");
        pinned = true;

    }




    function mostra_riepilogo() {


        if ($('#scheda_riepilogo').css("display") == "none")
        {

            $('#scheda_riepilogo').show(1000);
            $('#scheda_risultati').animate({
                width: scheda_risultati_compatta_width
            }, 1000);
        }
        else
        {
            $('#scheda_riepilogo').hide(1000);
            $('#scheda_risultati').animate({
                width: scheda_risultati_allargata_width
            }, 1000);
        }

    }


    function reset_form(block_container)
    {
        $(block_container).find('#form_riepilogo').html('');
    }

</script>

<script type="text/javascript">
    $(document).ready(function() {
       // var screen_width = $(window).width();
       // var scheda_record_width = screen_width * 0.47;
        //$('#scheda_record_hidden').width(scheda_record_width);

        $(function() {
            $("#selezione_allegati").tabs();
        });

        //var screen_width = $(window).width();
        $('.scheda_dati_inserimento_container').width(scheda_dati_inserimento_container_width);
        $('.scheda_container_visualizzatore').width(scheda_container_visualizzatore)
        //$('#scheda_campi').width(screen_width * 0.32);
        //$('#lista_allegati').width(screen_width*0.08);
        //$('#gestione_allegati_container').width(screen_width*0.08);
        //$('#visualizzatore').width(screen_width * 0.47);
        //$('#scheda_record').width(screen_width * 0.47);



        $('#helper').bind('click', function(e) {

            // Prevents the default action to be triggered. 
            e.preventDefault();

            // Triggering bPopup when click event is fired
            $('#helper_popup').bPopup();

        });

        apply();
        $("#autosearch").buttonset();
        $('#dvLoading').fadeOut(1000);
        $('#content_inserimento').scrollTo(0, 100);
    });

</script>
<div class="page content">
    <div class="blocco ui-widget-header menu_big" style="margin-left: 5;margin-right: 5;margin-top: 0px;margin-bottom: 0px; ">
        <?php
        if($cliente_id=='Work&Work')
        {
        ?>
            <div onmouseover="show_menu();"  class="btn_showmenu btn_icona" style="float: left"> </div>
        <?php
        }
        ?>
        <div class="header_title" style="float: left;margin-left: 10px;margin-right: 10px;height: 100%;line-height: 25px">INSERIMENTO</div>
        <div  class="helper_button btn_scritta" style="float: right">?</div>



        <div class="clearboth"></div>
    </div>    
    <div id="content_inserimento" class="contentbody">
        <div id="scheda_inserimento" class="scheda scheda_inserimento" data-schedaid="scheda_inserimento" style="border: 0px;margin-top: 0px;" >
            <div id="scheda_dati_inserimento_container" class="scheda_container scheda_dati_inserimento_container"  style="float: left;width: 30%;"> 
                <?php
                echo $schede['scheda_dati_inserimento'];
                ?>
            </div>
            <div id="allegati" class="allegati ">
                <div id="scheda_allegati_container" class="scheda_container scheda_allegati_container" style="height: 98%;width: 130px;float: left;">
                    <?php
                    echo $block['block_allegati'];
                    ?>
                </div>
                <div id="scheda_code_container" class="scheda_container scheda_code_container " style="height: 98%;width: 130px;float: left;display: none">
                    <?php
                    echo $block['block_code'];
                    ?>
                </div>
                <!-- scheda  autobatch -->
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="height: 98%;width: 130px;float: left;display: none">
                    <?php
                    echo $block['block_autobatch'];
                    ?>
                </div>
                <div id="" class="scheda_container scheda_container_visualizzatore" style="float:left;">
                    
                </div>
                <div class="clearboth"></div>
            </div>
          <!--  <div class="blocco scheda scheda_record scheda_container scheda ui-widget-content" id="scheda_record_hidden" style="display: none;float: left;">
            </div>-->
        </div>
    </div>
</div>