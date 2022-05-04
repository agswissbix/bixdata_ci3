<?php
$block = $data['block'];
$cliente_id=$data['settings']['cliente_id'];
?>

<script type="text/javascript">
$('#content_gestione_code').ready(function(){
    
});
</script>

<div id="content_gestione_code" class="content">
    <!--  Menu content -->
    <div class="blocco ui-widget-header menu_big" >
                <?php
                if($cliente_id=='Work&Work')
                {
                ?>
                    <div onmouseover="show_menu();"  class="btn_showmenu btn_icona" style="float: left"> </div>
                <?php
                }
                ?>
                <div class="header_title" style="float: left;margin-left: 10px;margin-right: 10px;height: 100%;line-height: 25px">GESTIONE CODE</div>
                <div  class="helper_button btn_scritta" style="float: right">?</div>
                <div class="clearboth"></div>
    </div>  
    <div class="contentbody">
            <!-- scheda creazione coda -->
            <div class="scheda_container" style="float: left;width: 20%;">
                <div id="crea_coda" class="blocco scheda ui-widget-content" >
                    <!-- menu scheda creazione coda -->
                    <div id="menu_scheda_campi" class="menu_mid ui-widget-header">
                         NUOVA CODA
                         <div class="clearboth"></div>
                    </div>
                    <!-- creazione manuale -->
                    <div style="margin: 5px;">Nome coda: <input type="text" name="NuovaCoda" id="nome_coda" style="border: 1px solid #54b1e4"></div>


                    <div id="CreaSalvaCoda" class="btn_scritta" name="CreaSalvaCoda" onclick="crea_coda(this);" style="margin: 5px;" >
                        Crea coda vuota
                    </div>
                    <br/>
                    <br/>
                    <!-- creazione automatica -->
                    <div id="CreaSalvaCoda" class="btn_scritta" name="CreaSalvaCoda" onclick="importacoda(this);" style="margin: 5px;">
                        Crea coda importando scansioni
                    </div>
                </div>
            </div>
            <!-- allegati e visualizzatore della coda -->
            <div class="allegati">
                <!-- scheda  code -->
                <div id="scheda_code_container" class="scheda_container scheda_code_container" style="float: left;width: 160px;">
                    <?php
                    echo $block['block_code'];
                    ?>
                </div>
                <!-- scheda  autobatch -->
                <div id="scheda_autobatch_container" class="scheda_container scheda_autobatch_container" style="float: left;width: 160px;display: none">
                    <?php
                    echo $block['block_autobatch'];
                    ?>
                </div>
                <!-- scheda visualizzatore -->
                <div id="scheda_visualizzatore_container" class="scheda_container scheda_container_visualizzatore" style="float: left;width: 40%">
                    <?php
                    echo $block['block_visualizzatore'];
                    ?>    
                </div> 
            </div>
    </div>
</div>