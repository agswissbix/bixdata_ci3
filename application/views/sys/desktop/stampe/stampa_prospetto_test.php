
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
        height: 296.6mm;
        width: 209.9mm;
        padding: 10mm;
    }
</style>


      <div class="print_page">
            <br />
            <img src="<?=base_url().$header_logo?>" style="margin-left: 10%;width: 80%"> 
            <br /><br />
            <div style="color:#4A442A !important;font-size: 36px;font-weight: bold;text-align: center;"><?=$paese?></div>
            <br /><br/>
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$categoria?></div>
            <br />
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$locali?></div>
            <br />
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$sul?></div>
            <br/><br/>
            <?php
            echo $foto_copertina;
            $rapporto=1;
            if($foto_copertina!=null)
            {
                $rapporto=$foto_copertina['width'] / $foto_copertina['height'];
            }
            if($rapporto>1.4)
            {
            ?>
                <img src="<?=domain_url().$foto_copertina['path']?>" style="display: block;margin: auto;max-height: 145mm;width: 94%;border: 3px solid #4A442A">
            <?php
            }
            else
            {
            ?>
                <img src="<?=domain_url().$foto_copertina['path']?>" style="display: block;height: 145mm;max-width: 94%;margin: auto;border: 3px solid #4A442A">
            <?php
            }
            ?>
            <br />
            <img style="position: absolute;bottom: 0mm;height: 30mm;width: 209.9mm;" src="<?=base_url("/assets/images/DimensioneImmobilare_footer_prospetto.png")?>"> 
    </div>



    

    <!-- Descrizione -->
    <div class="print_page" >
        
        <div style="font-size: 12px;">
                <?=  $descrizione?>

            
        </div>
        
    </div>
        
      