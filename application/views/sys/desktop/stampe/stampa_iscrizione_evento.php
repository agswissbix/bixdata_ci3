<style type="text/css">
    @page {
            margin-top: 0.0em;
            margin-bottom: 0.0em;
            margin-left: 0.0em;
            margin-right: 0.0em;
        }
    @font-face {
        font-family: test;
        font-weight: normal;
        src: url('test.ttf');
      }
    .print_page
    {
        font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, AppleGothic, sans-serif !important;
        position: relative;
        height: 296mm;
        width: 209.9mm;
    }
</style>


<div class="print_page" >
    <br/>
    <br/>
    <img style="width: 300;float: left" src="<?php echo base_url("/assets/images/custom/About-X/securing.jpg") ?>">
    <div style="float: right;margin-left: 25px;">
        <img style="width: 150px;float: left;" src="<?php echo base_url("/assets/images/logo_about.png") ?>">
        
    </div>
    <div style="clear: both;"></div>
    <div >
        <h4 style="border-bottom: 1px solid black">Informazioni sull'evento</h4>
        <div style="float: left">
            <table style="border: 0px">
                <tr>
                    <td style="width: 200px">
                        Indirizzo
                    </td>
                    <td>
                        Gravesano, Via al Fiume 1
                    </td>
                </tr>
                <tr>
                    <td style="width: 200px">
                        Telefono
                    </td>
                    <td>
                        +41 91 612 85 85
                    </td>
                </tr>
                <tr>
                    <td>
                        Organizzato da
                    </td>
                    <td>
                        About-X
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <div>
        <h4 style="border-bottom: 1px solid black">Biglietto</h4>
        <table style="border: 0px;float: left;">
            <tr>
                <td style="width: 200px">
                    Assegnatario
                </td>
                <td>
                    <?=$cognome?> <?=$nome?> < <?=$mail?> >
                </td>
            </tr>
            <tr>
                <td>
                    Workshop
                </td>
                <td>
                    <ul>
                    <?php
                    foreach ($workshop_list as $key => $workshop) {
                    ?>
                        <li><?=$workshop?></li>
                    <?php
                    }   
                    ?>
                    </ul>
                </td>
            </tr>
        </table>
        <img style="float: right;margin-right: 25px" src="<?php echo server_url()."/generati/$qrname" ?>"> 
        
    </div>
       
</div>

