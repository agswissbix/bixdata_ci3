
  <div>
      <div class="print_page" style="background-color: red;height: 296.9mm;width: 209.9mm;">
           <br />
            <img src="<?=base_url().$header_logo?>" style="height: 296.9mm;width: 209.9mm;">
            <br />
            <div style="color:#4A442A !important;font-size: 36px;font-weight: bold;text-align: center"><?=$paese?></div>
            <br />
            <div style="color:#4A442A !important;font-size: 20px;font-weight: bold;text-align: center"><?=$titolo?></div>
            <br />
            <img src="<?=domain_url().$foto_copertina?>" style="height: 100mm;width: 209.9mm;border: 3px solid #4A442A">
            <br/>
            <div style="color:#4A442A;font-size: 36px;text-align: center">Prezzo CHF: <?=$prezzo?></div>
            <img style="position: absolute;bottom: 0mm;height: 30mm;width: 209.9mm;" src="<?=base_url("/assets/images/DimensioneImmobilare_footer_prospetto.png")?>"> 
    </div>
      

    <!-- Pagina foto interni -->
    <?php
    if($foto_interni!=null)
    {
    ?>
    <div class="print_page" style="position: relative">
        <br/>
        <br/>
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
        <?php
        //foto interni

            foreach ($foto_interni as $key => $foto_interno) {
        ?>
            <img style="height: 270px;width: 320px;float: left;margin-left: 12mm;margin-top: 12mm;border: 3px solid #4A442A" src="<?=domain_url().$foto_interno?>">
        <?php

            }

        
        ?>
    </div>
    <?php
    }
    ?>
    
    <!-- Pagina foto esterni -->
    <?php
    if($foto_esterni!=null)
    {
    ?>
    <div class="print_page" style="position: relative">
        <br />
        <br/>
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
        <?php
        //foto interni
        
            foreach ($foto_esterni as $key => $foto_esterno) {
                if($key>6)
                {
                    break;
                }
        ?>
        
            <img style="height: 270px;width: 320px;float: left;margin-left: 25px;margin-top: 25px;border: 3px solid #4A442A" src="<?=domain_url().$foto_esterno?>">
        <?php

            }

        
        ?>
    </div>
    <?php
    }
    ?>
        
    <!-- Descrizione -->
    <div class="print_page" style="position: relative;">
        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
        <div style="font-size: 30px;position: absolute;left: 10mm;top: 10mm;width: 190mm">DESCRIZIONE</div>
        <div style="font-size: 16px;position: absolute;left: 10mm;top: 30mm;width: 190mm;height: 220mm;">
            <?=$descrizione?>
        </div>
        <div  style="font-size: 16px;position: absolute;left: 10mm;top: 260mm;width: 190mm;height: 20mm;">
            Prezzo di vendita CHF <?=$prezzo?>
        </div>
        <div style="font-size: 16px;position: absolute;left: 10mm;top: 280mm;width: 190mm">
            Per avere una più ampia visualizzazione dei nostri oggetti potete collegarvi al sito www.dimensioneimmobiliare.ch - 091 922 74 00 
        </div>
    </div>
        
        
    <!-- Scheda -->
    <div class="print_page" style="position: relative;">

        <img style="height: 200mm;position: absolute;right: 0px;top: 50px;z-index: -1" src="<?=base_url("/assets/images/DI_prospetto_background.png")?>">
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
                            <div style="font-size: 24px"><?=  strtoupper($sublabel['sublabelname'])?></div>
                            <br/>
                            <table>
                                <tr>
                            <?php
                            $column=0;
                            foreach ($fields as $key => $field) 
                            {
                                $field_desc=$field['description'];
                                $field_value=$field['valuecode'][0]['value'];
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

                                    <td style="border:1px solid #dedede !important;font-weight: bold;padding: 5px;"><?=$field_desc?></td>
                                    <td style="border:1px solid #dedede !important;padding: 5px;"><?=$field_value?></td>

                                <?php
                                }
                            }
                            ?>
                                    </tr>
                            </table>
                            <?php
                        }
                    }
                }
            }
        }
        ?>
    </div>
  </div>
  