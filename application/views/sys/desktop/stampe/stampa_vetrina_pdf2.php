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
        <div class="print_page" style='position: relative;height: 208mm;width: 296.9mm;overflow: hidden'>
            <!--sfondo-->
            <img style="height: 90mm;position: absolute;left: -3mm;bottom: -70mm;z-index: -1" src="<?=base_url("/assets/images/DimensioneImmobilare_sfera_bottom.png")?>">
            <!--logo-->
            <img src="<?=base_url().$header_logo?>" style="position: absolute;height: 47mm;width: 150mm;left: 24mm;top: 0mm;">
            <!--foto1-->
            <img style="height: 140mm;width: 195mm;position: absolute;left: 3mm;bottom: 20mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina1?>">
            <!--prezzo sovrastante foto1-->
            <?php
            if($prezzo=='PREZZO SU RICHIESTA')
            {
            ?>
            <div style="padding-left: 2mm;font-size: 22px;line-height: 22px;position: absolute;width:60mm;height: 22px;left: 136.7mm;bottom: 20.3mm;background-color: white !important">
                PREZZO SU RICHIESTA
            </div>
            <?php
            }
            else
            {
            ?>
            <div style="padding-left: 2mm;font-size: 22px;line-height: 22px;position: absolute;height: 22px;right: 100mm;bottom: 20.5mm;background-color: white !important">
                CHF <?=$prezzo?>
            </div>
            <?php
            }
            ?>
            
            
            <!--foto2-->
            <?php
            if($foto_vetrina2!='')
            {
            ?>
                <img style="height: 60mm;width: 90mm;position: absolute;right: 2mm;top: 9mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina2?>">      
            <?php
            }
            ?>
           
            <!--foto3-->
            <?php
            if($foto_vetrina3!='')
            {
            ?>
                <img style="height: 60mm;width: 90mm;position: absolute;right: 2mm;top: 76mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina3?>">
            <?php
            }
            ?>
            <!--foto4-->
            <?php
            if($foto_vetrina4!='')
            {
            ?>
                <img style="height: 60mm;width: 90mm;position: absolute;right: 2mm;top: 143mm;border: 2px solid #4A442A;" src="<?=  domain_url().$foto_vetrina4?>">
            <?php
            }
            ?>
            <!--footer-->
            <div style="position: absolute;width: 100%;bottom: -2mm;left: 3mm">
                <div style="font-size: 24px;line-height: 24px;">
                    <?php
                    if(($tipo=='Vendita')||($tipo=='Affitto'))
                    {
                    ?>
                        <?=$paese?>  <?=$locali?> LOCALI <?=$categoria?>
                    <?php
                    }
                    ?>
                    <?php
                    if(($tipo=='Palazzina in vendita')||($tipo=='Terreno in vendita'))
                    {
                    ?>
                        <?=$paese?>  <?=$categoria?>
                    <?php
                    }
                    ?>
                </div>
                <div style="height: 1mm">
                    
                </div>
                <div style="font-size: 16px;line-height: 32px;">
                    www.dimensioneimmobiliare.ch  Via Bellinzona 1 CH - 6512 Giubiasco  Tel. +41 91 857 19 07
                </div>
            </div>
            <!--<div style="position: absolute;font-size: 14px;line-height: 16px;bottom: 0mm;right: 10mm;width: 60mm">
                <div>
                www.dimensioneimmobiliare.ch   
                </div>
                <div>
                    Via Bellinzona 1 CH - 6512 Giubiasco
                </div>
                <div>
                    Tel. +41 91 857 19 07
                </div>
            </div>-->
        </div>
        
        
  </div>
  