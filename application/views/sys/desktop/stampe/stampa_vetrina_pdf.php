<style type="text/css">
    @page {
            margin-top: 0.0em;
            margin-bottom: 0.0em;
            margin-left: 0.0em;
            margin-right: 0.0em;
        }
    .print_page
    {
        font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, AppleGothic, sans-serif !important;
    }
</style>
  <div>
        <!-- Pagina foto interni -->
        <div class="print_page" style='position: relative;height: 208mm;width: 296.9mm;'>
            <!--sfondo-->
            <img style="height: 180mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
            <!--logo-->
            <img src="<?=base_url().$header_logo?>" style="position: absolute;height: 40mm;width: 150mm;left: 14mm;top: 3mm;">
            <!--foto1-->
            <img style="height: 140mm;width: 180mm;position: absolute;left: 3mm;bottom: 20mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina1?>">
            <!--prezzo sovrastante foto1-->
            <div style="padding-left: 2mm;font-size: 22px;line-height: 22px;position: absolute;width:40mm;height: 22px;left: 142mm;bottom: 20.3mm;background-color: white !important">
                CHF <?=$prezzo?>
            </div>
            
            <!--foto2-->
            <img style="height: 72mm;width: 105mm;position: absolute;right: 3mm;top: 3mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina2?>">      
            <!--dati-->
            <div style="height: 60mm;width: 100mm;position: absolute;right: 3mm;top: 82mm;font-size: 24px;line-height: 38px;">
                <div><?=$paese?></div>
                <div><?=$categoria?></div>
                <div><?=$locali?> LOCALI</div>
            </div>
            <!--foto3-->
            <img style="height: 72mm;width: 105mm;position: absolute;right: 3mm;bottom: 20mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina3?>">
            <!--footer-->
            <div style="position: absolute;width: 100%;height: 3mm;bottom: 5mm;">
                <div style="font-size: 16px;position: absolute;left: 10mm;">
                www.dimensioneimmobiliare.ch   
                </div>
                <div style="font-size: 16px;position: absolute;left: 80mm;">
                    Via Bellinzona 1 CH - 6512 Giubiasco
                </div>
                <div style="font-size: 16px;position: absolute;left: 160mm;">
                    Tel. +41 91 857 19 07
                </div>
            </div>
        </div>
        
        
  </div>
  