<?php
foreach ($contratto_fornitura as $key => $value) {
    if($key!='tariffaoraria')
    {        
        if(isempty($value))
        {
            $contratto_fornitura[$key]='<span style="color:red">Valore mancante</span> ';
        }
    }
}
?>
<style type="text/css">
    @font-face { 
    font-family: IDAutomationHC39M;  
    src: url(<?php echo base_url("/assets/css/sys/font/IDAutomationHC39M.woff")?>);
    }
    @page {
            margin-top: 0.0em;
            margin-bottom: 0.0em;
            margin-left: 0.0em;
            margin-right: 0.0em;
        }
        
    .print_page
    {
        font-family: "Arial" !important;
        font-size: 13pt;
        position: relative;
        <?php
        if($funzione=='modifica')
        {
        ?>
            height: auto;
        <?php
        }
        else
        {
        ?>
            height: 98%;
        
        <?php
        }
        ?>
        width: 100%;
        padding-left: 4mm;
        padding-right: 4mm;
        padding-top: 10mm;
        overflow: hidden;
        <?php
        if($funzione=='modifica')
        {
        ?>
        border: 1px solid #dedede;
        margin: auto;
        <?php
        }
        ?>
    }
    
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
    .line{
        border-bottom: 1px solid black;
        height: 10px;
        width: 100%;
        margin-bottom: 5px;
    }
    .row{
        margin-bottom: 5px;
        
    }
    
    .row.h2{
        margin-bottom: 10px;
    }
    .row.h3{
        margin-bottom: 15px;
    }
    .row.h4{
        margin-bottom: 20px;
    }
    
    
    .row .col {
        float: left;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 0 0.75rem;
        text-align: left;
    }
    .row .col.s1 {
        width: 8.33333%;
    }
    .row .col.s2 {
        width: 18.66666%;
    }
    .row .col.s3 {
        width: 24.99999%;
    }
    .row .col.s4 {
        width: 33.33332%;
    }
    .row .col.s5 {
        width: 41.66665%;
    }
    .row .col.s6 {
        width: 49.99998%;
    }
    .row .col.s7 {
        width: 58.33331%;
    }
    .row .col.s8 {
        width: 66.66664%;
    }
    .row .col.s9 {
        width: 74.99997%;
    }
    .row .col.s10 {
        width: 83.33333%;
    }
    .row .col.s11 {
        width: 91.66663%;
    }
    .row .col.s12 {
        width: 99.99996%;
    }
    
    .row .col.offset-s1 {
        margin-left: 8.33333%;
    }
    .row .col.offset-s2 {
        margin-left: 18.66666%;
    }
    .row .col.offset-s3 {
        margin-left: 24.99999%;
    }
    .row .col.offset-s4 {
        margin-left: 33.33332%;
    }
    .row .col.offset-s5 {
        margin-left: 41.66665%;
    }
    .row .col.offset-s6 {
        margin-left: 49.99998%;
    }
    .row .col.offset-s7 {
        margin-left: 58.33331%;
    }
    .row .col.offset-s8 {
        margin-left: 66.66664%;
    }
    .row .col.offset-s9 {
        margin-left: 74.99997%;
    }
    .row .col.offset-s10 {
        margin-left: 83.33333%;
    }
    .row .col.offset-s11 {
        margin-left: 91.66663%;
    }
    .row .col.offset-s12 {
        margin-left: 99.99996%;
    }
    
    .title{
        font-weight: bold;
        text-align: center;
        font-size: 17pt;
        
    }
    
    #contratto_fornitura_content input {
        width: 100%;
        
        outline: none ;
        border: none ; 
        border-width: 0 ; 
        box-shadow: none ;
        <?php
        if($funzione=='modifica')
        {
        ?>
        border-bottom: 1px solid black !important;
        <?php
        }
        ?>
    }   
        
        
</style>

<script type="text/javascript">

    

</script>



<div id="contratto_fornitura_content" class="print_page" style="">
    <input type="hidden" name="recordid_contratto" value="<?=$recordid_contratto?>">
    <?php
    $intestazione_opacity=1;
    if($intestazione=='false')
    {
        $intestazione_opacity=0;
    }
    ?>
    <div id="intestazione" style="opacity: <?=$intestazione_opacity?>">
        <img src="<?php echo base_url("/assets/images/custom/3p/3p_contratto_intestazione.png") ?>?v=<?=time();?>" style="width: 100%">
    </div>
    <div class="row h4"></div>
    <div class="row h2"></div>
    <div class="row"></div>
    <div class="row" style="position: absolute">
        <div style="width: 100%;height: 10px;position: relative;top: 0px;right: 0px;font-family: IDAutomationHC39M">
                <?=$contratto_fornitura['id']?>
        </div>
    </div>
    <div class="row">
        <div id="titolo" class="title">CONTRATTO DI FORNITURA DI PERSONALE A PRESTITO</div>
    </div>
    <div class="row">
        <div style="text-align: center">Secondo gli articoli 22 LC e 50 OC</div>
    </div>
    <div class="row h4"></div>
    <div class="row h2"></div>
    <div class="row"></div>
    <div class="row">
        <div class="col s6" >
            Tra (in qualità di prestatore)
        </div>
        <div class="col s1" style="width: 5%">
            E
        </div>
        <div class="col s3" >
            
        </div>
        <div class="s3">
            (in qualità di ditta acquisitrice)
        </div>
    </div>
    <div class="row h3"></div>
    <div class="row">
        <div class="col s6">
            3P clc Sagl
        </div>
        <div class="col s6">
            <?=$contratto_fornitura['nomecliente']?>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            Via sottomurata 1
        </div>
        <div class="col s6">
            <?=$contratto_fornitura['indirizzocliente']?>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            6934 Bioggio
        </div>
        <div class="col s6">
            <?=$contratto_fornitura['paesecliente']?>
        </div>
    </div>
    <div class="row h4"></div>
    <div class="row">
        <div class="col s12">
            Il Presente contratto concerne la fornitura di un lavoratore a prestito a un'impresa acquisitrice. Il prestatore è titolare di un'autorizzazione cantonale e federale di fornitura di personale a prestito rilasciata da: Sezione del Lavoro Ufficio Giuridico - Residenza Governativa - Piazza governo 7, 6501 Bellinzona e dalla SECO, Collocamento e Personale a prestito (PAVV), Holzikofenweg 36, 3003 Berna.
        </div>
    </div>
    <div class="row h4"></div>
    <div class="row">
        <div class="col s12">
            Il presente contratto definisce gli accordi tra le parti:
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px">
        <div class="line"></div>
    </div>
    <div class="row" style="font-weight: bold">
        <div class="col s6" style="font-weight: bold">
            Collaboratore 
        </div>
        <div class="col s6" style="font-weight: bold">
            Dati concernenti il cliente
        </div>
    </div>
    <div class="row">
        <div class="col s6">
             <?=$contratto_fornitura['nomedipendente']?> <?=$contratto_fornitura['cognomedipendente']?>
        </div>
        <div class="col s6" >
            <span style="text-decoration: underline">CCL:</span>  <?=$contratto_fornitura['cclcontratto']?>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
             <?=$contratto_fornitura['indirizzodipendente']?>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
             <?=$contratto_fornitura['paesedipendente']?>
        </div>
        <div class="col s6">
            <span style="text-decoration: underline">Luogo di lav:</span>  <?=$contratto_fornitura['luogolavoro']?>
        </div>
    </div>
    <div class="line"></div>
    <div class="row">
        <div class="col s6" style="font-weight: bold">
            Accordi contrattuali
        </div>
    </div>
    <div class="row">
        <div class="col s3" style="text-decoration: underline">
            Mansione:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['mansione']?>
        </div>
        <div class="col s3" style="text-decoration: underline">
            Inizio contratto:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['iniziocontratto']?>
        </div>
    </div>
    <div class="row">
        <div class="col s3" style="text-decoration: underline">
            Orario medio:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['orariomedio']?>
        </div>
        <div class="col s3" style="text-decoration: underline">
            Fine contratto:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['finecontratto']?>
        </div>
    </div>
    <div class="row" style="height: 130px">
        <div class="col s3" style="text-decoration: underline">
            Orario:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['fasciaoraria']?>
        </div>
    </div>
    <div class="line"></div>
    <div class="row">
        <div class="col s6" style="font-weight: bold">
            Altri accordi
        </div>
         <div class="col s6" style="font-weight: bold">
            Disdetta contrattuale
        </div>
    </div>
    <div class="row">
        <div class="col s3" style="text-decoration: underline">
            Tariffa oraria:
        </div>
        <div class="col s3">
            <input id="tariffaoraria" class=" " type="text" name="fields[tariffaoraria]" value="<?=$contratto_fornitura['tariffaoraria']?>">
            
        </div>
        <div class="col s3">
            
        </div>
        <div class="col s3">
            2 giorni durante i primi 3 mesi.
        </div>
    </div>
    <div class="row">
        <div class="col s3" style="text-decoration: underline">
            Fatturazione:
        </div>
        <div class="col s3">
            <?=$contratto_fornitura['fatturazione']?>
        </div>
        <div class="col s3">
            
        </div>
        <div class="col s3">
            7 giorni dal 4° al 6° mese.
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            Indennità pasto, indennità chilometriche, supplementi, straordinari,notturni non sono compresi nella summenzionata tariffa.
        </div>
        <div class="col s6">
            1 mese a partire dal 7° mese di lavoro, per lo stesso giorno del mese successivo.
        </div>
    </div>
    <div class="row h2">
        
    </div>
    <div class="row">
        <div class="col s12">
            Le disdette contrattuali del presente contratto devono pervenire per iscritto alla 3P clc Sagl. Nel caso di contratti concatenati tra di loro fa stato la data del primo contratto di lavoro per il calcolo della disdetta. Al termine del presente contratto se il dipendente continuerà a lavorare presso di voi, il presente contratto diventerà automaticamente un contratto a durata indeterminata.
        </div>
    </div>
    <div class="line"></div>
    <div class="row">
        <div class="col s12">
            Il contratto quadro che avete ricevuto in data <?=$contratto_fornitura['datacontrattoquadroazienda']?> , entra in vigore solo al momento della firma del presente contratto di fornitura di personale a prestito, di cui fa parte integrante.
        </div>
    </div>
    <div class="row h3">
    </div>
    <div class="row">
        <div class="col s8">
            3P clc Sagl <br/>
            <?=$contratto_fornitura['datamandato']?>
        </div>
        <div class="col s4">
            <?=$contratto_fornitura['nomecliente']?><br/>
            <?=$contratto_fornitura['datamandato']?>
        </div>
    </div>
    
        
        
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    
    <?php
    $intestazione_opacity=1;
    if($intestazione=='false')
    {
        $intestazione_opacity=0;
    }
    ?>
    <div class="row" style="opacity: <?=$intestazione_opacity?>;font-size: 12pt ">
        <div class="line" style="border-color: #19B965;"></div>
        <br/>
        <div class="col s3">
            <span style="font-weight: bold">CH-6934 Bioggio</span><br/>
            Via Sottomurata 1<br/>
            tel. +41 91 682 89 61<br/>
            fax +41 91 682 89 62
        </div>
        <div class="col s3">
            <span style="font-weight: bold">CH-6850 Mendrisio</span><br/>
            Via Penate 4<br/>
            tel. +41 91 682 89 63<br/>
            fax +41 91 682 89 64
        </div>
        <div class="col s3">
            <span style="font-weight: bold">CH-6572 Quartino</span><br/>
            Via Luserte 2<br/>
            tel. +41 91 682 89 70<br/>
            fax +41 91 682 89 71
        </div>
        <div class="col s3">
            <br/>
            <br/>
            <span style="font-weight: bold">www.3pclc.ch</span><br/>
            <span style="font-weight: bold">info@3pclc.ch</span>
        </div>
    </div>
    
    
    
</div>
