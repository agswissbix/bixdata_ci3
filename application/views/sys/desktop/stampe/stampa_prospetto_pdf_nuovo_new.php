<?php
$page_counter=2;
?>
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
        font-size: 10pt;
        position: relative;
        height: 187.3mm;
        width: 120mm;
        padding-left: 10mm;
        padding-right: 10mm;
        padding-top: 10mm;
        margin-left: -2mm;
        overflow: hidden;
        border: 1px solid red;
    }
    
    .rotate{
    -ms-transform: translate(50%, 50%) rotate(90deg); /* IE 9 */
    -webkit-transform: translate(50%, 50%) rotate(90deg); /* Chrome, Safari, Opera */
    transform: translate(50%, 50%) rotate(90deg) ;
}
    



</style>

<!-- COPERTINA INIZIO -->
<div class="print_page" style=''>
        <br />
        <img src="<?=base_url().$header_logo?>" style="margin-left: 15%;width: 70%">
        <br />
        <br/>
        <br/>
        <br/>
        <div style="font-size: 18pt;font-weight: bold;text-align: center;">
       <?=$paese?> <br/>
            <?php
            if(isnotempty($titolo))
            {
                echo "$titolo";
            }
            ?>
        </div>
        <br/>
        <br />
        <div style="font-size: 14pt;font-weight: bold;text-align: center;"><?=$tipo?></div>
        <br />
        <div style="font-size: 14pt;font-weight: bold;text-align: center"><?=$titoletto?></div>
        <br />
        <div style="text-align: center">
            <?=$descrizione_copertina?>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <img src="<?=domain_url().$foto_copertina['path']?>" style="max-height: 200mm;width: 100%;">
</div>
<!-- COPERTINA FINE -->

<!-- LOCATION INIZIO -->
<?php
if(isnotempty($foto_paese)||isnotempty($descrizione_location))
{
?>
    <div class="print_page"  >
        <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
            <div style="float: left"><?=$titolo?> | Location</div>
            <div style="float: right"><?=$page_counter?></div>
        </div>
        <br/>
        <br/>
        <div style="height: 40mm;overflow: hidden">
            <?=$descrizione_location?>
        </div>
        <br/>
        <br/>
            <div>
                <?php
                $url_encoded=  urlencode("https://maps.googleapis.com/maps/api/staticmap?center=<?=$paese?>,<?=$via?>&zoom=16&size=640x400&markers=color:red%7C<?=$paese?>,<?=$via?> 11&style=feature:road|element:labels|visibility:off&key=AIzaSyCF8ykBXsHolbLlzp0oydQhArp0bpT1GzU");
                ?>
                <img src="<?=$url_encoded?>" style="width: 100%;">
            </div>
            <br/>
            <div>
                <?php
                if(isnotempty($foto_paese))
                {
                ?>
                    <img src="<?=domain_url().$foto_paese?>" style="height: 100mm;width: 100%;">
                <?php
                }
                ?>
            </div>
    </div>
<?php
}
?>
<!-- LOCATION FINE -->

<!-- FOTO INIZIO -->
    
    <?php
    $photos_interniesterni['Interni']=$foto_interni;
    $photos_interniesterni['Esterni']=$foto_esterni;
    foreach ($photos_interniesterni as $photos_interniesterni_key => $photos) 
    {
        if($photos!=null)
        {
            $foto_per_pagina=5;
            $foto_pages=  ceil(count($photos)/$foto_per_pagina);
            if(($foto_pages-(count($photos)/$foto_per_pagina))==0.2)
            {
                $foto_pages=$foto_pages-1;
            }
            for ($foto_pages_index = 0; $foto_pages_index < $foto_pages; $foto_pages_index++) 
            {
                $page_counter++;
                ?>
                <div class="print_page" >
                    <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
                        <div style="float: left"><?=$titolo?> | Foto <?=$photos_interniesterni_key?></div>
                        <div style="float: right"><?=$page_counter?></div>
                    </div>
                    <br/>
                    <br/>
                    <?php
                    $photos_array_index=$foto_per_pagina * $foto_pages_index;
                    $foto_rimanenti=count($photos)-$photos_array_index;
                    if($foto_rimanenti>=5)
                    {
                        for ($foto_per_pagina_index = 0; $foto_per_pagina_index < $foto_per_pagina; $foto_per_pagina_index++) 
                        {

                            $photo=$photos[$photos_array_index];
                            if($foto_per_pagina_index==2)
                            {
                            ?>
                                <img style="height: 79mm;width: 118mm;margin: 1mm;float: left;" src="<?=domain_url().$photo?>">
                            <?php
                            }
                            else
                            {
                            ?>  
                                <img style="height: 39mm;width: 58mm;margin: 1mm;float: left;" src="<?=domain_url().$photo?>">
                            <?php
                            }
                            $photos_array_index++;
                        }
                    }
                    else
                    {
                        if($foto_rimanenti==1)
                        {
                        ?>
                            <img style="width: 100%;" src="<?=domain_url().$photos[$photos_array_index];?>"> 
                        <?php
                        }
                        if($foto_rimanenti==2)
                        {
                        ?>
                            <img style="height: 110mm;width: 168mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index];?>"> 
                            <img style="height: 110mm;width: 168mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+1];?>"> 
                        <?php
                        }
                        if($foto_rimanenti==3)
                        {
                        ?>
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index];?>"> 
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+1];?>"> 
                            <img style="height: 110mm;width: 168mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+2];?>"> 
                        <?php
                        }
                        if($foto_rimanenti==4)
                        {
                        ?>
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index];?>"> 
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+1];?>"> 
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+2];?>">
                            <img style="height: 55mm;width: 83mm;margin: 1mm;float: left;" src="<?=domain_url().$photos[$photos_array_index+3];?>">
                        <?php
                        }
                    ?>   

                    <?php  
                    }
                    ?>
                </div>
            <?php
            }
        }
    }
    ?>
<!-- FOTO FINE --> 


<!-- DESCRIZIONE INIZIO -->
<div class="print_page" >
    <?php
    $page_counter++;
    ?>
    <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
        <div style="float: left"><?=$titolo?> | Descrizione</div>
        <div style="float: right"><?=$page_counter?></div>
    </div>
    <br />
    <br />
        <div style="width: 100%;height: 170mm;overflow: hidden;">
            <div style="font-weight: bold;"><?=$titoletto?></div>
            <br/>
            <div>
                <?=  $descrizione?>
            </div>
            <?php
            if(($prezzo!='.--')&&($prezzo!=''))
            {
            ?>
                <div  style="font-size: 16px;width: 190mm">
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
        </div>
        <br/>
        <br/>
        <div>
            <?php
            if(isnotempty($foto_paese))
            {
            ?>
                <img src="<?=domain_url().$foto_descrizione?>" style="max-height: 80mm;margin-left: 15%;width: 70%;">
            <?php
            }
            ?>
        </div>
</div>

<!-- DESCRIZIONE FINE -->


<!-- PIANTINE INIZIO -->
<?php
if($foto_piantine!=null)
{
?>
    <?php
        //$foto_per_pagina=6;
        //$foto_piantine_pages=  ceil(count($foto_piantine)/$foto_per_pagina);
        foreach ($foto_piantine as $key => $foto_piantina) 
        { 
            $page_counter++;
        ?>
        <div class="print_page">
            <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
                <div style="float: left"><?=$titolo?> | Piantina</div>
                <div style="float: right"><?=$page_counter?></div>
            </div>
            <?php
            if($key==0)
            {
            ?>
            <?php
            }
            if($foto_piantina['widht']>$foto_piantina['height'])
            {
            ?>
                <div style="width: 100%;">
                    <img  style="width: 100%;max-height: 180mm;" src="<?=domain_url().$foto_piantina['path_rotated']?>">
                </div>
            <?php
            }
            else
            {
            ?>
                <div style="height:  100%">
                    <img  style="height:180mm;" src="<?=domain_url().$foto_piantina['path']?>">
                </div>
            <?php
            }
            
            ?>
            

        </div>

        <?php
        }
        
}
?>

<!-- PIANTINE FINE -->
   
    
    
<!-- SCHEDA INIZIO -->
    
<div class="print_page" style="overflow: visible" >
    <?php
    $fields_counter=0;
    $page_counter++;
    ?>
    <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
            <div style="float: left"><?=$titolo?> | Informazioni</div>
            <div style="float: right"><?=$page_counter?></div>
        </div>
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
                        <div style="font-size: 16px;font-weight: bold;"><?=  $sublabel['sublabelname']?></div>
                        <br/><br/>
                        <div style="width: 90%;margin: auto;">
                            <table style="border-collapse: collapse;width: 100%;">
                                <tr>
                            <?php
                            $column=0;
                            foreach ($fields as $key => $field) 
                            {
                                $fields_counter++;
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

                                        <td style="padding: 5px;width: 25%;font-size: 12px;"><?=$field_desc?></td>
                                        <td style="padding: 5px;width: 25%;font-size: 12px;"><?=$field_value?></td>

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
<?php
    if($fields_counter>40)
    {
        $page_counter++;
    ?>
        <div id="scheda" class="print_page" >
            
        </div>
    <?php
    }
    ?>

<!-- SCHEDA FINE -->

<!-- ULTIMA PAGINA INIZIO -->
<div class="print_page" style="height: 187.1">
    <?php
    $page_counter++;
    ?>
    <div style="border-bottom: 1px solid #4A442A;height: 5mm;font-size: 10pt;">
            <div style="float: left"><?=$titolo?> | Contatti</div>
            <div style="float: right"><?=$page_counter?></div>
        </div>
    <br/>
    <br/>
    <div>
        <i>Con piacere le trasmetto la presente documentazione, in cui troverà tutte le informazioni rilevanti sull'immobile. Spero l‘oggetto in questione attiri il suo interesse.
        Naturalmente una documentazione non sostituisce una visita. Con grande piacere le presenterò personalmente l'immobile. 
        Mi permetterò, pertanto, di chiamarla per fissare un appuntamento.
        </i>
    </div>
<br/>
<br />
<br />
<br />
    <div style="width: 130mm">
        <div style="float: left;width: 100mm">
            <div style="font-weight: bold"><?=$consulente['firstname']?> <?=$consulente['lastname']?></div>
            <br/><br/>
            <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block">Email: <?=$consulente['email']?></div>
            <div style="clear: both"></div>
            <br/>
            <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block">Tel: <?=$consulente['telefono']?></div>
            <div style="clear: both"></div>
        </div>
        <div style="float: left;width: 30mm">
            <img style="width: 100%" src="<?=domain_url().'/JDocServer/avatar/'.$consulente['id'].'.jpg'?>">
        </div>
        <div style="clear: both"></div>
    </div>

    <div style="width: 130mm;margin-top: 70mm">
        <?php
        if($consulente['group']==11)
        {
        ?>
            <div style="float: left;width: 90mm">
            
                <div style="font-weight: bold">Sede sottoceneri</div>
                Dimensione Immobiliare SA
                <br/><br/>
                Via Carlo Maderno, 9
                <br/>
                CH - 6900 Lugano
                <br/>
                Svizzera
                <br/><br/>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: info@dimensioneimmobiliare.ch</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 922 74 00</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                        
                <div style="clear: both"></div>
            </div>
            <div style="float: left;width: 40mm">
                <img style="width: 100%" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_lugano.jpg'?>">
            </div>
            <br/>
            <br/>
            <div style="float: left;width: 90mm;margin-top: 30px;">
                    <div style="font-weight: bold">Sede Sopraceneri</div>
                    Dimensione Immobiliare Sopraceneri Sagl
                    <br/><br/>
                    Via Bellinzona, 1
                    <br/>
                    CH - 6512 Giubiasco
                    <br/>
                    Svizzera
                    <br/><br/>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: sopraceneri@dimensioneimmobiliare.ch</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 857 19 07</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                <div style="clear: both"></div>
            </div>
            <div style="float: left;width: 40mm;margin-top: 30px;">
                <img style="width: 100%" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_giubiasco.jpg'?>">
            </div>
        <?php
        }
        if($consulente['group']==12)
        {
        ?>
            <div style="float: left;width: 90mm">
                    <div style="font-weight: bold">Sede Sopraceneri</div>
                    Dimensione Immobiliare Sopraceneri Sagl
                    <br/><br/>
                    Via Bellinzona, 1
                    <br/>
                    CH - 6512 Giubiasco
                    <br/>
                    Svizzera
                    <br/><br/>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: sopraceneri@dimensioneimmobiliare.ch</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 857 19 07</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                <div style="clear: both"></div>
            </div>
            <div style="float: left;width: 40mm">
                <img style="width: 100%" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_giubiasco.jpg'?>">
            </div>
            <br/>
            <br/>
            <div style="float: left;width: 90mm;margin-top: 30px;">
            
                <div style="font-weight: bold">Sede sottoceneri</div>
                Dimensione Immobiliare SA
                <br/><br/>
                Via Carlo Maderno, 9
                <br/>
                CH - 6900 Lugano
                <br/>
                Svizzera
                <br/><br/>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: info@dimensioneimmobiliare.ch</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 922 74 00</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                        
                <div style="clear: both"></div>
            </div>
            <div style="float: left;width: 40mm;margin-top: 30px;">
                <img style="width: 100%" src="<?=domain_url().'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_lugano.jpg'?>">
            </div>
        <?php
        }
        ?>
        <div style="clear: both"></div>
    </div>
    
</div>
<!-- ULTIMA PAGINA FINE -->
  