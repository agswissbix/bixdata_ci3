<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var height=$('#scheda1').height();
        var height_print_page=$('#print_page_copertina').height();
        if(height>height_print_page)
        {
            $('#print_page_scheda2').show();
        }
        
    });
</script>

<?php
$page_counter=2;
$localhost=domain_url();//"http://localhost:8822/";
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
        font-family: "Arial" !important;
        font-size: 14pt;
        position: relative;
        height: 360.8mm;
        width: 242mm;
        padding-left: 10mm;
        padding-right: 10mm;
        padding-top: 10mm;
        margin-left: -2mm;
        overflow: hidden;
    }
    
    .rotate{
    -ms-transform: translate(50%, 50%) rotate(90deg); /* IE 9 */
    -webkit-transform: translate(50%, 50%) rotate(90deg); /* Chrome, Safari, Opera */
    transform: translate(50%, 50%) rotate(90deg) ;
}
img{
    height: 100%;
    width: 100%;
}

.intestazione_pagina{
    font-size: 12pt;
}
    



</style>

<!-- COPERTINA INIZIO -->
<div id="print_page_copertina" class="print_page" style=''>
        <div style="width: 169mm;height: 46mm;margin: auto;margin-top: 5mm;">
            <img src="<?=base_url().$header_logo?>"  >
        </div>
        
        <div style="height: 80mm;width: 70%;margin: auto;margin-top: 20mm;overflow: hidden">
            <div style="font-size: 38pt;font-weight: bold;text-align: center;">
                <?=$paese?>
            </div>
            <div style="font-size: 26pt;font-weight: bold;text-align: center;margin-top: 0mm"><?=$tipo?></div>
            <div style="font-size: 26pt;font-weight: bold;text-align: center;margin-top: 5mm"><?=$titoletto?></div>
            <div style="font-size: 16pt;text-align: center;margin-top: 5mm">
                <?=$descrizione_copertina?>
            </div>
        </div>
        
        <div style="height: 161mm;width: 242mm;margin: auto;margin-top: 10mm">
            <img src="<?=$localhost.$foto_copertina['path']?>" style="">
        </div>
        <div style="text-align: center;margin-top: 10mm;">
            <b>www.dimensioneimmobiliare.ch</b><br/>
            Sede sottoceneri: Via Carlo Maderno, 9 - CH - 6900 Lugano - Tel. +41 (0)91 922 74 00<br/>
            Sede sopraceneri: Via Bellinzona 1 - CH - 6512 Giubiasco - Tel. +41 (0)91 857 19 07
            
            
        </div>
</div>
<!-- COPERTINA FINE -->

<!-- LOCATION INIZIO -->
<?php
if(isnotempty($foto_paese)||isnotempty($descrizione_location))
{
?>
    <div id="print_page_location" class="print_page"  >
        <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
            <div style="float: left"><?=$riferimento?> | Location</div>
            <div style="float: right"><?=$page_counter?></div>
        </div>
        <br/>
        <div style="height: 50mm;overflow: hidden">
            <?=$descrizione_location?>
        </div>
            <div style="height: 136mm;width: 242mm;margin-top: 5mm">
                <?php
                //$paese= urlencode(utf8_encode($paese));
                //$via= urlencode(utf8_encode($via));
                $url_encoded=  "https://maps.googleapis.com/maps/api/staticmap?center=<?=$paese?>,<?=$via?>&zoom=16&size=640x324&markers=color:red%7C<?=$paese?>,<?=$via?> 11&style=feature:road|element:labels|visibility:off&key=AIzaSyCF8ykBXsHolbLlzp0oydQhArp0bpT1GzU";
                ?>
                <img src="<?=$url_encoded?>" style="width: 100%;">
            </div>
            <div style="margin-top: 5mm">
                <?php
                if(isnotempty($foto_paese))
                {
                ?>
                    <div style="height: 136mm;width: 242mm;">
                        <img src="<?=$localhost.$foto_paese?>" >
                    </div>
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
            $diff=(count($photos)/$foto_per_pagina)-floor((count($photos)/$foto_per_pagina));
            if(abs($diff-0.2) < 0.001)
            {
                $foto_pages=$foto_pages-1;
            }
            for ($foto_pages_index = 0; $foto_pages_index < $foto_pages; $foto_pages_index++) 
            {
                $page_counter++;
                ?>
                <div class="print_page" >
                    <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
                        <div style="float: left"><?=$riferimento?> | Foto <?=$photos_interniesterni_key?></div>
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
                                <img style="height: 161mm;width: 241mm;margin: 0.5;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photo?>">
                            <?php
                            }
                            else
                            {
                            ?>  
                                <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photo?>">
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
                            <!--<img style="width: 100%;" src="<?=$localhost.$photos[$photos_array_index];?>"> -->
                        <?php
                        }
                        if($foto_rimanenti==2)
                        {
                        ?>
                            <img style="height: 161mm;width: 241mm;margin: 0.5;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index];?>"> 
                            <img style="height: 161mm;width: 241mm;margin: 0.5;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+1];?>"> 
                        <?php
                        }
                        if($foto_rimanenti==3)
                        {
                        ?>
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index];?>"> 
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+1];?>"> 
                            <img style="height: 161mm;width: 241mm;margin: 0.5;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+2];?>"> 
                        <?php
                        }
                        if($foto_rimanenti==4)
                        {
                        ?>
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index];?>"> 
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+1];?>"> 
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+2];?>">
                            <img style="height: 80mm;width: 120mm;float: left;margin-top: 1mm;margin-left: 1mm;margin-bottom: 1mm" src="<?=$localhost.$photos[$photos_array_index+3];?>">
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
<div id="print_page_descrizione" class="print_page" >
    <?php
    $page_counter++;
    ?>
    <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
        <div style="float: left"><?=$riferimento?> | Descrizione</div>
        <div style="float: right"><?=$page_counter?></div>
    </div>
    <br />
    <br />
    <?php
    if(isnotempty($foto_descrizione))
    {
        $descrizione_container_height="170mm";
        $descrizione_height="145mm";
    }
    else
    {
        $descrizione_container_height="330mm";
        $descrizione_height="305mm";
    }
    ?>
        <div style="width: 100%;height: <?=$descrizione_container_height?>;overflow: hidden;">
            <div style="font-size: 16pt;font-weight: bold;"><?=$titoletto?></div>
            <br/>
            <div style="max-height: <?=$descrizione_height?>;width: 100%;overflow: hidden;">
                <?=  $descrizione?>
            </div>
            <?php
            if(($prezzo!='.--')&&($prezzo!=''))
            {
            ?>
                <div  style="font-size: 18pt;width: 190mm;margin-top: 5mm;">
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
        <div>
            <?php
            if(isnotempty($foto_descrizione))
            {
            ?>
                <img src="<?=$localhost.$foto_descrizione?>" style="height: 161mm;width: 241mm;margin: auto;margin-top: 5mm;display: block">
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
            <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
                <div style="float: left"><?=$riferimento?> | Piantina</div>
                <div style="float: right"><?=$page_counter?></div>
            </div>
            <br/>
            <?php
            if($key==0)
            {
            ?>
            <?php
            }
            /*if($foto_piantina['widht']>$foto_piantina['height'])
            {
            ?>
                <div style="margin-top: 10mm">
                    <img  style="height: 330mm;width: auto;margin: auto;display: block;" src="<?=$localhost.$foto_piantina['path_rotated']?>">
                </div>
            <?php
            }
            else
            {
            ?>
                <div style="width: 100%">
                    <img  style="height: 250mm;" src="<?=$localhost.$foto_piantina['path']?>">
                </div>
            <?php
            }*/
            
            ?>
            <?php
            $rapporto=1;
            $width=$foto_piantina['widht'];
            $height=$foto_piantina['height'];
            if($width!=0)
            {
                $rapporto=$height/$width;
            }
            
            if($rapporto<1.4)
            {
            ?>
                <div>
                    <img  style="width: 242mm;height: auto;display: block;margin: auto" src="<?=$localhost.$foto_piantina['path']?>">
                </div>
            <?php
            }
            else
            {
            ?>
                <div>
                    <img  style="height: 340mm;width: auto;display: block;margin: auto" src="<?=$localhost.$foto_piantina['path']?>">
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
    
<div id="print_page_scheda1" class="print_page" style="overflow: visible" >
    <div id="scheda1">
    <?php
    $fields_counter=0;
    $page_counter++;
    ?>
    <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
            <div style="float: left"><?=$riferimento?> | Informazioni</div>
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
                        <div style="font-size: 16pt;font-weight: bold;"><?=  $sublabel['sublabelname']?></div>
                        <br/>
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

                                        <td style="border:1px solid #dedede !important;padding: 5px;width: 25%;font-size: 14pt;"><?=$field_desc?></td>
                                        <td style="border:1px solid #dedede !important;padding: 5px;width: 25%;font-size: 14pt;"><?=$field_value?></td>

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
</div>
<div id="print_page_scheda2" class="print_page" style="display: none;" >
            
</div>
    

<!-- SCHEDA FINE -->

<!-- ULTIMA PAGINA INIZIO -->
<div class="print_page" style="height: 357mm;">
    <?php
    $page_counter++;
    ?>
    <div class="intestazione_pagina" style="border-bottom: 1px solid #4A442A;height: 7mm;">
            <div style="float: left"><?=$riferimento?> | Contatti</div>
            <div style="float: right"><?=$page_counter?></div>
    </div>
    <br/>
    <br/>
    <div>
        <i>
            E’ stato un vero piacere poterle inviare la presente documentazione, nella quale troverà tutte le informazioni sull’immobile; sperando che l’oggetto in questione possa attirare il suo interesse.<br/>
            Ovviamente, nonostante la completezza della brochure, potrà apprezzare appieno le caratteristiche dell’immobile, solo mediante una visita in loco.<br/> 
            Augurandomi di avere il piacere di poterla conoscere personalmente, resto a sua disposizione
        </i>
    </div>
<br/>
<br />
<br />
<br />
    <?php
    if($consulente!=null)
    {
    ?>
        <div style="font-size: 16pt">
            <div style="float: left;width: 40mm;height: 60mm">
                <img style="width: 100%" src="<?=$localhost.'/JDocServer/avatar/'.$consulente['id'].'.jpg'?>">
            </div>
            <div style="float: left;margin-left: 5mm;">
                <div style="font-weight: bold"><?=$consulente['firstname']?> <?=$consulente['lastname']?></div>
                <br/><br/>
                <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block">Email: <?=$consulente['email']?></div>
                <div style="clear: both"></div>
                <br/>
                <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block">Tel: <?=$consulente['telefono']?></div>
                <div style="clear: both"></div>
            </div>
            <div style="clear: both"></div>
        </div>
    <?php
    }
    ?>

    <div style="width: 2000mm;height: 170mm;margin-top: 70mm;">
        <div style="height: 70mm;overflow: hidden;">
            <div style="float: left;width: 60mm;height: 60mm;">
                <img src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_lugano.jpg'?>">
            </div>
            <div style="float: left;width: 110mm;height: 100%;margin-left: 5mm;">
            
                <div style="font-weight: bold">Sede sottoceneri</div>
                Dimensione Immobiliare SA
                <br/><br/>
                Via Carlo Maderno, 9
                <br/>
                CH - 6900 Lugano
                <br/>
                Svizzera
                <br/><br/>
                <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: info@dimensioneimmobiliare.ch</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 922 74 00</div>
                <div style="clear: both"></div>
                <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                        
                <div style="clear: both"></div>
            </div>
            
            <div style="clear: both"></div>
        </div>
        <div style="height: 70mm;overflow: hidden;margin-top: 20mm;">
            <div style="float: left;width: 60mm;height: 60mm;">
                <img src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/maps_giubiasco.jpg'?>">
            </div>
            <div style="float: left;width: 110mm;height: 100%;margin-left: 5mm;">
                    <div style="font-weight: bold">Sede Sopraceneri</div>
                    Dimensione Immobiliare Sopraceneri Sagl
                    <br/><br/>
                    Via Bellinzona, 1
                    <br/>
                    CH - 6512 Giubiasco
                    <br/>
                    Svizzera
                    <br/><br/>
                    <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/email_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Email: sopraceneri@dimensioneimmobiliare.ch</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/phone_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Tel: +41 91 857 19 07</div>
                    <div style="clear: both"></div>
                    <img style="width: 20px;height: 20px;float: left" src="<?=$localhost.'/JDocWeb/assets/images/custom/DimensioneImmobiliare/printer_icon.png'?>"> <div style="float: left;margin-left: 5mm;line-height: 20px;display: inline-block;font-weight: bold">Fax: +41 91 922 74 02</div>
                <div style="clear: both"></div>
            </div>
            
            <div style="clear: both"></div>
        </div>
        
        <div style="clear: both"></div>
    </div>
    
</div>
<!-- ULTIMA PAGINA FINE -->
  