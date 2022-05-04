<?php
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
        font-family: "Tahoma", Avantgarde, "Century Gothic", CenturyGothic, AppleGothic, sans-serif !important;
        font-size: 10pt;
        position: relative;
        height: 272mm;
        width: 189.9mm;
        padding: 10mm;
        margin-left: -3mm;
        overflow: hidden;
    }



.jd-col
{
    min-height: 1mm;
    float: left;
}
.jd-col-1
{
    min-height: 1mm;
    float: left;
    width: 8.33%;
}
.jd-col-2
{
    min-height: 1mm;
    float: left;
    width: 16.66%;
}
.jd-col-3
{
    min-height: 1mm;
    float: left;
    width: 24.99%;
}
.jd-col-4
{
    min-height: 1mm;
    float: left;
    width: 33.32%;
}
.jd-col-5
{
    min-height: 1mm;
    float: left;
    width: 41.65%;
}
.jd-col-6
{
    min-height: 1mm;
    float: left;
    width: 49.98%;
}
.jd-col-7
{
    min-height: 1mm;
    float: left;
    width: 58.31%;
}
.jd-col-8
{
    min-height: 1mm;
    float: left;
    width: 66.64%;
}
.jd-col-9
{
    min-height: 1mm;
    float: left;
    width: 74.97%;
}
.jd-col-10
{
    min-height: 1mm;
    float: left;
    width: 83.3%;
}
.jd-col-11
{
    min-height: 1mm;
    float: left;
    width: 91.63%;
}
.jd-col-12
{
    min-height: 1mm;
    float: left;
    width: 99.96%;
}

.jd-emptyrow-5
{
    width: 100%;
    height: 5mm;
}

.jd-emptyrow-10
{
    width: 100%;
    height: 10mm;
}

.clearboth
{
    clear: both;
}

</style>

<div class="print_page" style="">
    <div>
        <div class="jd-col-5"></div>
        <div class="jd-col-2" style="margin: auto;">
            <img src="<?=base_url()?>/assets/images/logo_about.png" style="width: 100%" >
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="jd-emptyrow-10"></div>
    <div>
        <div class="jd-col jd-col-6" style="text-align: left">
            Data, <?=$dati['data']?>
        </div>
        <div class="jd-col jd-col-6" style="text-align: right">
            <div style="font-weight: bold"><?=$dati['ragionesociale']?></div>
            <div style="font-weight: normal"><?=$dati['indirizzo']?></div>
            <div style="font-weight: normal"><?=  ucfirst($dati['citta'])?></div>
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="jd-emptyrow-5"></div>
    <div style="text-align: center;font-weight: bold">
            RAPPORTO DI INTERVENTO TECNICO
    </div>
    <div class="jd-emptyrow-10"></div>
    <div class="jd-emptyrow-10"></div>
    <div>
        <div class="jd-col jd-col-4" style="text-align: center">
            <div style="color:red">
                Tipo di intervento
            </div>
            <div>
                <?=$dati['tipointervento']?>
            </div>
        </div>
        <div class="jd-col jd-col-6" style="float: right;text-align: center">
            <div style="color:red">
                Tipo di assistenza
            </div>
            <div>
                <?php
                
                echo $dati['tipoassistenza'];
                ?>
                <div style="font-size: 8pt;color: #545454 ">
                <?php
                if($dati['tipoassistenza_codice']=='Assistenza monte ore')
                {
                    if(isnotempty($dati['monteore']))
                    {
                        $ore = floor($dati['monteore']);      // 1
                        $fraction = $dati['monteore'] - $ore;
                        $minuti=$fraction*60;
                        echo "Monte ore restante: ".$ore.":".$minuti." / ".$dati['monteoretotale'];
                    }
                }
                if($dati['tipoassistenza_codice']=='Assistenza da remoto')
                {         
                    if(isnotempty($dati['assistenzaremotafine']))
                    {
                        echo "Scadenza assistenza da remoto: ".date("d/m/Y", strtotime($dati['assistenzaremotafine']));
                    }
                }
                if($dati['tipoassistenza_codice']=='Assistenza centralino')
                {
                    if(isnotempty($dati['assistenzacentralinofine']))
                    {
                        echo "Scadenza assistenza centralino: ".date("d/m/Y", strtotime($dati['assistenzacentralinofine']));
                    }
                }

                ?>
                </div>
            </div>
        </div>
        <div class="clearboth" ></div>
    </div>
    <div class="jd-emptyrow-5"></div>
    <div  style="font-weight: bold">
        PROBLEMI RISCONTRATI E/O INTERVENTI ESEGUITI
    </div>
    <div class="jd-emptyrow-5"></div>
    <div class="jd-row" style="border: 1px solid black;padding: 5px;height: 20mm;overflow: hidden">
        <?php
        $descrizione=$dati['descrizione'];
        $descrizione= nl2br($descrizione);

        echo $descrizione;
        ?>
    </div>
    <div class="jd-emptyrow-5"></div>
    <div>
        <span style="font-style: oblique">Tecnico incaricato dell'assistenza: Sig.</span> <?=$dati['tecnico']?>
    </div>
    <div class="jd-emptyrow-10"></div>
    <div>
        <div class="jd-col jd-col-9">
            <div class="jd-emptyrow-5"></div>
            <div>Intervento ordinario (dalle 08:00 alle 18:00)</div>
        </div>
        <div class="jd-col jd-col-1">
            <div style="color: red">DALLE</div> 
            <div class="jd-emptyrow-5"></div>
            <div><?=$dati['dalle']?></div>
        </div>
        <div class="jd-col jd-col-1">
            <div style="color: red">ALLE</div> 
            <div class="jd-emptyrow-5"></div>
            <div><?=$dati['alle']?></div>
        </div>
        <div class="jd-col jd-col-1">
            <div style="color: red">TOTALE</div> 
            <div class="jd-emptyrow-5"></div>
            <div><?=$dati['durata']?></div>
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="jd-emptyrow-10"></div>

    <div>
        <div class="jd-col-5"></div>
        <div class="jd-col-2" style="color: red;">
            NOTE AGGIUNTIVE
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="jd-emptyrow-5"></div>
    <div style="border: 1px solid black;padding: 5px;height: 20mm;overflow: hidden">
        <?php
        $noteaggiuntive=$dati['noteaggiuntive'];
        $noteaggiuntive= nl2br($noteaggiuntive);

        echo $noteaggiuntive;
        ?>
    </div>
    <div class="jd-emptyrow-10"></div>
    <div>
La firma del presente documento o, in alternativa, l'invio a mezzo e-mail, comporta l'accettazione dell'intervento eseguito, se non contestato entro otto giorni dalla data di emissione.<br/>
<br/>
L'emissione del presente documento vale solo come attestazione dell'attività eseguita e non comporta necessariamente addebiti.
<br/>
<br/>
<br/>
<br/>
<br/>
<div>
    <div style="float: left">Luogo e Data:________________________________</div>
    <div style="float: right">Firma:________________________________</div>
</div>
    </div>
    <div class="jd-emptyrow-10"></div>
    
    <div class="jd-emptyrow-10"></div>
    
    
    <div style="text-align: center">
        About-x SA - Via al fiume 1 - 6929 Gravesano Tel. +41 91 612 85 85 Fax +41 91 600 27 27
    </div>
</div>