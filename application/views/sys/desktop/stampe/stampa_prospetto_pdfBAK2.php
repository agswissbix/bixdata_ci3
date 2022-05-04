
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


      <div class="print_page" style='position: relative;height: 296.9mm;width: 209.9mm;'>
            <br />
            <img src="<?=base_url().$header_logo?>" style="margin-left: 10%;width: 80%">
            <br /><br />
            <div style="color:#4A442A !important;font-size: 36px;font-weight: bold;text-align: center;font-weight: bold;"><?=$paese?></div>
            <br /><br/>
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$categoria?></div>
            <br />
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$locali?></div>
            <br />
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$sul?></div>
            <br/><br/>
            <?php
            $rapporto=1;
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


    <!-- Pagina foto interni -->
    <?php
    if($foto_interni!=null)
    {
        $foto_per_pagina=6;
        $foto_interni_pages=  ceil(count($foto_interni)/$foto_per_pagina);
        for ($i = 0; $i < $foto_interni_pages; $i++) 
        {            
    ?>
        <div class="print_page" style="position: relative;height: 296.9mm;width: 209.9mm;">
            <br/>
            <div style="font-size: 30px;text-align: center;">FOTO INTERNI</div>
            <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
            <?php
            //foto interni
                
                $counter=$foto_per_pagina * $i;
                for ($x = 0; $x < $foto_per_pagina; $x++) 
                {
                    if(array_key_exists($counter, $foto_interni))
                    {
                        $foto_interno=$foto_interni[$counter]
            ?>  
                    <img style="height: 270px;width: 320px;float: left;margin-left: 12mm;margin-top: 12mm;border: 3px solid #4A442A" src="<?=domain_url().$foto_interno?>">
            <?php
                    }
                    $counter++;
                }
            ?>
        </div>
    <?php
        }
    }
    ?>

    
    <!-- Pagina foto esterni -->
    <?php
    if($foto_esterni!=null)
    {
        $foto_per_pagina=6;
        $foto_esterni_pages=  ceil(count($foto_esterni)/$foto_per_pagina);
        for ($i = 0; $i < $foto_esterni_pages; $i++) 
        {
    ?>
    <div class="print_page" style="position: relative;height: 296.9mm;width: 209.9mm;">
        <br/>
        <div style="font-size: 30px;text-align: center;">FOTO ESTERNI</div>
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
        <?php
        //foto interni
        
            $counter=$foto_per_pagina * $i;
                for ($x = 0; $x < $foto_per_pagina; $x++) 
                {
                    if(array_key_exists($counter, $foto_esterni))
                    {
                        $foto_esterno=$foto_esterni[$counter]
            ?>
                        <img style="height: 270px;width: 320px;float: left;margin-left: 12mm;margin-top: 12mm;border: 3px solid #4A442A" src="<?=domain_url().$foto_esterno?>">
            <?php
                    }
                    $counter++;
            }

        
        ?>
    </div>
    <?php
        }
    }
    ?>

    <!-- Descrizione -->
    <div class="print_page" style="position: relative;height: 296.9mm;width: 209.9mm;">
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
        <br/>
        <div style="font-size: 30px;text-align: center;">DESCRIZIONE</div>
        <div style="font-size: 12px;position: absolute;left: 10mm;top: 30mm;width: 190mm;height: 220mm;">
            <div>
                <?=  $descrizione?>
            </div>
            <br/>
            <br/>
            
        </div>
        
        <?php
        if(($prezzo!='.--')&&($prezzo!=''))
        {
        ?>
            <div  style="font-size: 16px;position: absolute;left: 10mm;top: 250mm;width: 190mm">
                <?php
                if($prezzo=='PREZZO SU RICHIESTA')
                {
                    echo $prezzo;
                }
                else
                {
                ?>
                    Prezzo di vendita CHF <?=$prezzo?>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
        <div style="font-size: 12px;position: absolute;left: 10mm;top: 280mm;width: 190mm">
            <?='Per avere una più ampia visualizzazione dei nostri oggetti potete collegarvi al sito www.dimensioneimmobiliare.ch - 091 922 74 00'?> 
        </div>
    </div>
        
        
    <!-- Scheda -->
    
    <div class="print_page" style="position: relative;height: 296.9mm;width: 209.9mm;">
       
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
         <br/>
        <?php
        foreach ($sublabels as $key => $sublabel) {
            if($sublabel['sublabelname']!='')
            {
                if((strpos($sublabel['sublabelname'], 'vendita')===FALSE)&&(strpos($sublabel['sublabelname'], 'Ulteriori')===FALSE))
                {
                    if(array_key_exists($sublabel['sublabelname'], $fields_by_sublabel))
                    {
                        $fields=$fields_by_sublabel[$sublabel['sublabelname']];
                        if(count($fields)>0)
                        {
                            ?>
                            <br/>
                            <div style="font-size: 18px;position: absolute;left: 10mm;"><?=  strtoupper($sublabel['sublabelname'])?></div>
                            <br/><br/>
                            <div style="width: 90%;margin: auto;">
                                <table style="border-collapse: collapse;width: 100%;">
                                    <tr>
                                <?php
                                $column=0;
                                foreach ($fields as $key => $field) 
                                {
                                    if(($field['fieldid']!='lat')&&($field['fieldid']!='lng')&&($field['fieldid']!='doccaricati')&&($field['fieldtypeid']!='Memo')) //TODO TEMP
                                        {
                                        $field_desc=  $field['description'];
                                        $field_value=  $field['valuecode'][0]['value'];
                                        if(($field_value!='')&&($field_value!=null))
                                        {
                                            if($column==2)
                                            {
                                            ?>
                                            </tr>
                                            <tr>
                                            <?php
                                                $column=0;
                                            }
                                            $column++;
                                        ?>

                                            <td style="border:1px solid #dedede !important;font-weight: bold;padding: 5px;width: 25%;font-size: 12px;"><?=$field_desc?></td>
                                            <td style="border:1px solid #dedede !important;padding: 5px;width: 25%;font-size: 12px;"><?=$field_value?></td>

                                        <?php
                                        }
                                    }
                                }
                                ?>
                                        </tr>
                                </table>
                            </div>
                            <?php
                        }
                    }
                }
            }
        }
        ?>
    </div>
    
    <!-- Pagina foto piantine -->
    <?php
    if($foto_piantine!=null)
    {
        ?>
    
    <?php
        //$foto_per_pagina=6;
        //$foto_piantine_pages=  ceil(count($foto_piantine)/$foto_per_pagina);
        foreach ($foto_piantine as $key => $foto_piantina) 
        {            
        ?>
        <div class="print_page" style="position: relative;height: 296.9mm;width: 209.9mm;">
            <br />
            <?php
            if($key==0)
            {
            ?>
            <div style="font-size: 30px;text-align: center;">PIANTINE</div>
            <?php
            }
            ?>
            <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
            <img style="width: 90%;max-height: 250mm;margin-left: 4%;margin-top: 20mm;border: 3px solid #4A442A" src="<?=domain_url().$foto_piantina?>">
        </div>
        <?php
        }
    }
    ?>
      

  