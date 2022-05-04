<?php
if($funzione!='modifica')
{
    if(isempty($dipendente['indirizzo']))
    {
        $dipendente['indirizzo']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($azienda['ccl']))
    {
        $azienda['ccl']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($dipendente['nome']))
    {
        $dipendente['nome']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($dipendente['cognome']))
    {
        $dipendente['cognome']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($dipendente['domicilio']))
    {
        $dipendente['domicilio']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($azienda['indirizzo']))
    {
        $azienda['indirizzo']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($azienda['npa']))
    {
        $azienda['npa']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($azienda['localita']))
    {
        $azienda['localita']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($professione['cclapplicato']))
    {
        $professione['cclapplicato']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($fields['datainizio']))
    {
        $fields['datainizio']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($fields['datafirma']))
    {
        $fields['datafirma']='<span style="color:red">Valore mancante</span> ';
    }
    if(isempty($dipendente['datacontrattoquadroclc']))
    {
        $dipendente['datacontrattoquadroclc']='<span style="color:red">Valore mancante</span> ';
    }
    
}
?>
<script type="text/javascript" src="<?php echo base_url('/assets/EasyAutocomplete/jquery.easy-autocomplete.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.themes.min.css") ?>?v=<?=time();?>" />
<link rel="stylesheet" href="<?php echo base_url("/assets/EasyAutocomplete/easy-autocomplete.min.css") ?>?v=<?=time();?>" />

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
    
    .print_page_content
    {
        <?php
        if($funzione!='modifica')
        {
        ?>
            height: 83%;
        <?php
        }
        ?>
        overflow: hidden;
    }
    
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
    .line{
        border-bottom: 1px solid black;
        height: 10px;
        width: 96%;
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
        /*padding: 0 0.75rem;*/
        text-align: left;
    }
    .row .col.s1 {
        width: 8.333333333333333%;
    }
    .row .col.s2 {
        width: 16.66666666666667%;
    }
    .row .col.s3 {
        width: 25%;
    }
    .row .col.s4 {
        width: 33.33333333333333%;
    }
    .row .col.s5 {
        width: 41.66666666666667%;
    }
    .row .col.s6 {
        width: 50%;
    }
    .row .col.s7 {
        width: 58.33333333333333%;
    }
    .row .col.s8 {
        width: 66.66666666666666%;
    }
    .row .col.s9 {
        width: 75%;
    }
    .row .col.s10 {
        width: 83.33333333333333%;
    }
    .row .col.s11 {
        width: 91.66666666666666%;
    }
    .row .col.s12 {
        width: 100%;
    }
    
    .row .col.offset-s1 {
        margin-left: 8.333333333333333%;
    }
    .row .col.offset-s2 {
        margin-left: 16.66666666666667%;
    }
    .row .col.offset-s3 {
        margin-left: 25%;
    }
    .row .col.offset-s4 {
        margin-left: 33.33333333333333%;
    }
    .row .col.offset-s5 {
        margin-left: 41.66666666666667%;
    }
    .row .col.offset-s6 {
        margin-left: 50%;
    }
    .row .col.offset-s7 {
        margin-left: 58.33333333333333%;
    }
    .row .col.offset-s8 {
        margin-left: 66.66666666666666%;
    }
    .row .col.offset-s9 {
        margin-left: 75%;
    }
    .row .col.offset-s10 {
        margin-left: 83.33333333333333%;
    }
    .row .col.offset-s11 {
        margin-left: 91.66666666666666%;
    }
    .row .col.offset-s12 {
        margin-left: 100%;
    }
    
    .title{
        font-weight: bold;
        text-align: center;
        font-size: 17pt;
        
    }
    
    #contratto_missione_content input {
        width: 100%;
        
        outline: none ;
        border: none ; 
        border-width: 0 ; 
        box-shadow: none ;
        <?php
        if($funzione=='modifica')
        {
        ?>
            border-bottom: 1px solid #dedede !important;
        <?php
        }
        ?>
    } 
    
    #contratto_missione_content select{
        border: 0px;
        border-bottom: 1px solid #dedede;
    }
        
    #fasciaoraria input{
        width: 85px;
        border: 0px !important;
    }
        
</style>

<script type="text/javascript">
    


    
var autocomplete_azienda_options = [
      { label: "Nessuna azienda", id: "", category: "" },
      <?php
        $aziende2=array();
        foreach ($aziende as $key_azienda => $azienda_temp) {
            $aziende2[$azienda_temp['itemcode']]=$azienda_temp['itemdesc'];
        ?>
            { label: "<?=$azienda_temp['itemdesc']?>", id: "<?=$azienda_temp['itemcode']?>", category: "" },
        <?php
        }
        ?>
      
    ];

<?php
    if($table_settings['edit']=='true')
    {
    ?>
$( ".aziendaautocomplete" ).autocomplete({
        source: autocomplete_azienda_options,
        minLength: 0,
        select: function(event, ui) {
                var recordid_azienda=ui.item.id;
                $('#recordid_azienda').val(ui.item.id);
                aggiorna_campi_azienda();
             }
    });
    
    
    
    
    $( ".aziendaautocomplete" ).click(function(){
        $( this ).autocomplete( "search", '' );
    });
    
    
    
    function aggiorna_campi_azienda()
    {
        var recordid_azienda=$('#recordid_azienda').val();
        if(recordid_azienda!=='')
        {
            var serialized_data=[];
                    serialized_data.push({name: 'recordid_azienda', value: recordid_azienda});

                    $.ajax({
                        url: controller_url+'ajax_get_record/azienda/'+recordid_azienda,
                        data: serialized_data,
                        type: 'post',
                        dataType:"json",
                        success:function(response){
                            console.info(response);
                            var indirizzo=response['indirizzo'];
                            var npa=response['npa'];
                            var localita=response['localita'];
                            var ccl=response['ccl'];
                            $('#azienda_indirizzo').html(indirizzo);
                            $('#azienda_npa').html(npa);
                            $('#azienda_localita').html(localita);
                            $('#ccl_cliente').html(ccl);
                            $('#luogomissione').html(response['localita']);

                        },
                        error:function(){
                            alert('errore');
                        }
                    });
        }
    }
    
    <?php
    }
    ?>
    
    
    
    
    
    
    var autocomplete_dipendente_options = [
      { label: "Nessun dipendente", id: "", category: "" },
      <?php
        $dipendenti2=array();
        foreach ($dipendenti as $key_dipendente => $dipendente_temp) {
            $dipendenti2[$dipendente_temp['itemcode']]=$dipendente_temp['itemdesc'];
        ?>
            { label: "<?=$dipendente_temp['itemdesc']?>", id: "<?=$dipendente_temp['itemcode']?>", category: "" },
        <?php
        }
        ?>
      
    ];

<?php
    if($table_settings['edit']=='true')
    {
    ?>
$( ".dipendente_autocomplete" ).autocomplete({
        source: autocomplete_dipendente_options,
        minLength: 0,
        select: function(event, ui) {
                var recordid_dipendente=ui.item.id;
                $('#recordid_dipendente').val(ui.item.id);
                aggiorna_campi_dipendente();
             }
    });
    
        
    
    
    $( ".dipendente_autocomplete" ).click(function(){
        $( this ).autocomplete( "search", '' );
    });
    
    function aggiorna_campi_dipendente()
    {
        var recordid_dipendente=$('#recordid_dipendente').val();
        if(recordid_dipendente!=='')
        {
            var serialized_data=[];
                    serialized_data.push({name: 'recordid_dipendente', value: recordid_dipendente});

                    $.ajax({
                        url: controller_url+'ajax_get_record/dipendenti/'+recordid_dipendente,
                        data: serialized_data,
                        type: 'post',
                        dataType:"json",
                        success:function(response){
                            $('#dipendente_nome').html(response['nome']);
                            $('#dipendente_cognome').html(response['cognome']);
                            $('#dipendente_indirizzo').html(response['indirizzo']);
                            $('#dipendente_domicilio').html(response['domicilio']);
                            check_valori_mancanti();
                        },
                        error:function(){
                            alert('errore');
                        }
                    });
        }
    }
    
    <?php
    }
    ?>
    
    var autocomplete_professioni_options = [
      { label: "Nessuna professione", id: "", category: "" },
      <?php
        $professioni2=array();
        foreach ($professioni as $key_professione => $professione_temp) {
            if($funzione=='modifica')
            {
                $professioni2[$professione_temp['itemcode']]=$professione_temp['itemdesc'];
            }
            else
            {
                $professioni2[$professione_temp['itemcode']]=$professione_temp['itemdesc_noccl'];
            }
        ?>
            { label: "<?=$professione_temp['itemdesc']?>", id: "<?=$professione_temp['itemcode']?>", category: "" },
        <?php
        }
        ?>
      
    ];

<?php
    if($table_settings['edit']=='true')
    {
    ?>
$( ".professione_autocomplete" ).autocomplete({
        source: autocomplete_professioni_options,
        minLength: 0,
        select: function(event, ui) {
                var recordid_professione=ui.item.id;
                $('#recordid_professione').val(ui.item.id);
                aggiorna_campi_professione();
             }
    });
    
    
    
    
    $( ".professione_autocomplete" ).click(function(){
        $( this ).autocomplete( "search", '' );
    });
    
    function aggiorna_campi_professione()
    {
        var recordid_professione=$('#recordid_professione').val();
        var recordid_dipendente=$('#recordid_dipendente').val();
        var recordid_contratto=$('#recordid_contratto').val();
        if(recordid_professione!=='')
        {
            var serialized_data=[];
                    serialized_data.push({name: 'recordid_professione', value: recordid_professione});
                    serialized_data.push({name: 'recordid_contratto', value: recordid_contratto});
                    serialized_data.push({name: 'recordid_dipendente', value: recordid_dipendente});
                    

                    $.ajax({
                        url: controller_url+'ajax_get_dati_ccl',
                        data: serialized_data,
                        type: 'post',
                        dataType:"json",
                        success:function(response){
                            $('#ccl_dipendente').html(response['cclapplicato']);
                            $('#salarioorariolordobase').val(response['base']);
                            $('#giornifestiviperc').val(response['festiviperc']);
                            $('#giornifestivi').val(response['festivi']);
                            $('#indennitavacanzaperc').val(response['vacanzeperc']);
                            $('#indennitavacanza').val(response['vacanze']);
                            $('#tredicesimaperc').val(response['tredperc']);
                            $('#tredicesima').val(response['tred']);
                            $('#salarioorariototale').val(response['totale']);
                            
                        },
                        error:function(){
                            alert('errore');
                        }
                    });
        }
    }
    
    <?php
    }
    ?>
        
    function aggiorna_datainizio(el)
    {
        $('#datainiziomissione').html($(el).val());
        $('#datafirma').val($(el).val());
        $('#datafirma2').html($(el).val());
    }
    
    function aggiorna_datafirma(el)
    {
       $('#datafirma2').html($(el).val()); 
    }
    
    
    
    
    
    
    function check_valori_mancanti()
    {   
        if($('#dipendente_indirizzo').html()==='')
        {
            $('#dipendente_indirizzo').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#dipendente_nome').html()==='')
        {
            $('#dipendente_nome').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#dipendente_cognome').html()==='')
        {
            $('#dipendente_cognome').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#dipendente_domicilio').html()==='')
        {
            $('#dipendente_domicilio').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#azienda_indirizzo').html()==='')
        {
            $('#azienda_indirizzo').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#azienda_npa').html()==='')
        {
            $('#azienda_npa').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#azienda_localita').html()==='')
        {
            $('#azienda_localita').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#ccl_cliente').html()==='')
        {
            $('#ccl_cliente').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#ccl_dipendente').html()==='')
        {
            $('#ccl_dipendente').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#datainiziomissione').html()==='')
        {
            $('#datainiziomissione').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#datafirma2').html()==='')
        {
            $('#datafirma2').html("<span style='color:red;'>Dato mancante</span>");
        }
        if($('#datacontrattoquadroclc').html()==='')
        {
            $('#datacontrattoquadroclc').html("<span style='color:red;'>Dato mancante</span>");
        }
        
        
       
    }
    
    function check_dipendenze()
    {
        var tipocontratto=$('#tipocontratto').val();
        if(tipocontratto==='det')
        {
            $('#spandatafine').show();
      
            
        }
        else
        {
            $('#spandatafine').hide();
                $('#periododiprova').val('3 mesi');
        }    
    }
    
    
    function set_periododiprova()
    {
        var periododiprova;
         var serialized_data=[];

        serialized_data.push({name: 'date1', value: $('#datainizio').val()});
        serialized_data.push({name: 'date2', value: $('#datafine').val()});
        $.ajax({
            url: controller_url+'ajax_get_datediff',
            data: serialized_data,
            type: 'post',
            dataType:"html",
            success:function(response){
                periododiprova=response;
                $('#periododiprova').val(periododiprova);
            },
            error:function(){
                alert('errore');
            }
        });
    }
        
    
    

    $('select').change(function(){
        check_valori_mancanti();
        check_dipendenze();
    })
    
    
    $('#datainizio').change(function(){
        set_periododiprova();
    })
    
    $('#datafine').change(function(){
        set_periododiprova();
    })
    
    
    
    
    <?php
    if($table_settings['edit']=='true')
    {
    ?>
        function fasciaoraria_add(el)
        {
            $(el).closest('div').next().css('opacity','1');
        }

        function fasciaoraria_del(el)
        {
            $(el).closest('div').css('opacity','0');
        }   
    <?php
    }
    ?>
    


    
$('#contratto_missione_content').ready(function(){
    check_valori_mancanti();
    check_dipendenze();
    <?php
    $disabled='';
    if($table_settings['edit']=='false')
    {
    ?>
       $('#contratto_missione_content').find('input').prop('readonly', true);
       $('#contratto_missione_content').find('select').prop('disabled', true);
    <?php
    }
    ?>
})


function cambio_orariomedio(el)
{
    var valore=$(el).val();
    if(valore==='calendario')
    {
        $('.orariomedio_noncalendario').hide();
        $('.orariomedio_calendario').show();
        $('#ore').val('');
    }
    if(valore==='suchiamata')
    {
        $('.orariomedio_noncalendario').hide();
        $('#ore').val('');
    }
    if((valore==='giorno')||(valore=='settimana'))
    {
        $('.orariomedio_noncalendario').show();
    }

    if(valore!=='calendario')
    {
        $('.orariomedio_calendario').hide();
    }

}
        

</script>



<div id="contratto_missione_content" class="print_page" style="">
    <input id="recordid_contratto" type="hidden" name="recordid_contratto" value="<?=$recordid_contratto?>">
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
    <div class='print_page_content'>

    <div class="row h4"></div>
    <div class="row h2"></div>
    <div class="row" style="position: absolute">
        <div style="width: 100%;height: 10px;position: relative;top: 0px;right: 0px;font-family: IDAutomationHC39M">
                <?=$fields['id']?>
        </div>
    </div>
    <div class="row">
        <div id="titolo" class="title">
            <?php
            if(isempty($fields['modificacontrattuale']))
            {
            ?>
            CONTRATTO DI MISSIONE (Complemento al contratto quadro)
            <?php
            }
            else
            {
            ?>
            CONTRATTO DI MISSIONE (Modifica contrattuale)
            <?php
            }
            ?>
            
        </div>
    </div>
    <div class="row h4"></div>
    <div class="row h2"></div>
    <div class="row"></div>
    <div class="row">
        <div class="col s6" >
            Tra (in qualità di datore di lavoro)
        </div>
        <div class="col s1" style="width: 5%">
            E
        </div>
        <div class="col s3" >
            <?php
            if($funzione=='modifica')
            {
            ?>
                <?php
                $label_dipendente='';
                if(array_key_exists($fields['recordiddipendenti_'], $dipendenti2))
                {
                    $label_dipendente=$dipendenti2[$fields['recordiddipendenti_']];
                }
                ?>
            
            <input id='label_dipendente'  class="dipendente_autocomplete " value="<?=$label_dipendente?>">
            <input id="recordid_dipendente" class=" " type="hidden" name="fields[recordiddipendenti_]" value="<?=$fields['recordiddipendenti_']?>">
            <?php
            }
            else
            {
            ?>
                <?=$dipendente['id']?>
            <?php
            }
            ?>
        </div>
        <div class="s3">
            (in qualità di lavoratore)
        </div>
    </div>
    <div class="row h3"></div>
    <div class="row">
        <div class="col s6">
            3P clc Sagl
        </div>
        <div class="col s6">
            <span id="dipendente_nome"><?=$dipendente['nome']?></span> <span id="dipendente_cognome"><?=$dipendente['cognome']?></span>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            Via sottomurata 1
        </div>
        <div class="col s6">
            <span id="dipendente_indirizzo"><?=$dipendente['indirizzo']?></span>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            6934 Bioggio
        </div>
        <div class="col s6">
            <span id="dipendente_domicilio"><?=$dipendente['domicilio']?></span>
        </div>
    </div>
    <div class="row h2"></div>
    <div class="row">
        <div class="col s12">
        Le presenti disposizioni regolano una missione temporanea presso il cliente(impresa acquisitrice) della 3P CLC Sagl, Bioggio.<br/>
        Di regola si applicano le disposizioni del contratto quadro, che vengono completate dalle condizioni e indicazioni seguenti:
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px">
        <div class="line"></div>
    </div>
    <div class="row">
        <div class="col s2">
            Impresa acquisitrice:  
        </div>
        <div class="col s3">
            <?php
            if($funzione=='modifica')
            {
            ?>
            <?php
            $label_azienda='';
            if(array_key_exists($fields['recordidazienda_'], $aziende2))
            {
                $label_azienda=$aziende2[$fields['recordidazienda_']];
            }
            ?>
            
            <input id='label_azienda'  class="aziendaautocomplete input_azienda_label" value="<?=$label_azienda?>">
            <input id="recordid_azienda" class="recordidazienda input_azienda_recordid" type="hidden" name="fields[recordidazienda_]" value="<?=$fields['recordidazienda_']?>">
            <?php
            }
            else
            {
            ?>
                <?=$azienda['ragionesociale']?>
            <?php
            }
            ?>
        </div>
        
        <div class="col s6 " >
            
            <?php
            if($azienda['ccl']=='Nessun CCL')
            {
            ?>
                L'impresa acquisitrice non sottostà a nessun contratto collettivo
            <?php
            }
            else
            {
            ?>
                L'impresa acquisitrice sottostà al <span id='ccl_cliente'><?=$azienda['ccl']?></span>
            <?php
            }
            ?>
            
        </div>
    </div>
    <div class="row">
        <div class="col s3 offset-s2">
            <span id="azienda_indirizzo"><?=$azienda['indirizzo']?></span><br/>
        </div>
        <div class="col s6">
            Il presente contratto sottostà al <span id='ccl_dipendente'><?=$professione['cclapplicato']?></span>
        </div>  
            
    </div>
    <div class="row">
        <div class="col s3 offset-s2">
            <span id="azienda_npa"><?=$azienda['npa']?></span><span id="azienda_localita"> <?=$azienda['localita']?></span>
        </div>
    </div>
    <div class="row">
        <div class="line"></div>
    </div>
    <div class="row">
        <div class="col s5">
            Luogo della missione:
        </div>
        <div class="col s5">
            <input id="luogomissione" class=" " type="text" name="fields[luogomissione]" value="<?=$fields['luogomissione']?>">
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Genere di lavoro da fornire
        </div>
        <div class="col s7">
            
            <?php
            $label_professione='';
            if(array_key_exists($fields['recordidprofessioni_'], $professioni2))
            {
                $label_professione=$professioni2[$fields['recordidprofessioni_']];
            }
            ?>
            <?php
            if($funzione=='modifica')
            {
            ?>
            <input id='label_professione'  class="professione_autocomplete " value="<?=$label_professione?>">
            <input id="recordid_professione" class=" " type="hidden" name="fields[recordidprofessioni_]" value="<?=$fields['recordidprofessioni_']?>">
            <?php
            }
            else
            {
            ?>
            <?=$label_professione?>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Entrata in servizio 1° giorno
        </div>
        <div class="col s1">
            Ore
        </div>
        <div class="col s4">
            <input id="orarioinizio" class=" " type="time" name="fields[orarioinizio]" value="<?=$fields['orarioinizio']?>">
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Tipo contratto:
        </div>
        <div class="col s5">
            <?php
            
            if($funzione=='modifica')
            {
            ?>
            <select id="tipocontratto" name="fields[tipocontratto]">
                <option value="det" >Determinato</option>
                <option value="indet">Indeterminato</option>
                <option value="suchiamatadet">Su chiamata determinato</option>
                <option value="suchiamataindet">Su chiamata indeterminato</option>
                
            </select>
            <script type="text/javascript">
                $('#tipocontratto').val('<?=$fields['tipocontratto']?>');
            </script>
            <?php
            }
            else
            {
                if($fields['tipocontratto']=='det')
                {
                    echo 'Determinato';
                }
                if($fields['tipocontratto']=='indet')
                {
                    echo 'Indeterminato';
                }
                if($fields['tipocontratto']=='suchiamatadet')
                {
                    echo 'Su chiamata determinato';
                }
                if($fields['tipocontratto']=='suchiamataindet')
                {
                    echo 'Su chiamata indeterminato';
                }
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Durata della missione
        </div>
        <div class="col s2">
            <?php
            if($funzione=='modifica')
            {
            ?>
            <span>Dal <input id="datainizio" class=" " style="width: 80%" type="date" name="fields[datainizio]" value="<?=$fields['datainizio']?>" onchange="aggiorna_datainizio(this)"></span>
            <?php
            }
            else
            {
                if(isnotempty($fields['datainizio']))
                    echo "Dal ".date('d/m/Y', strtotime($fields['datainizio']));  
            }
            ?>
        </div>
        <div class="col s2">
            <?php
            if($funzione=='modifica')
            {
            ?>
            <span id="spandatafine" >Al <input id="datafine" class=" " style="width: 80%" type="date" name="fields[datafine]" value="<?=$fields['datafine']?>"></span>
            <?php
            }
            else
            {
                if(isnotempty($fields['datafine']))
                    echo "<span id='spandatafine' >Al ".date('d/m/Y', strtotime($fields['datafine']))."</span>";
            }
            ?>
        </div>
    </div>
    <div class="row">
        
        <div class="col s5">
            Orario medio di lavoro per il lavoratore
        </div>
        <div class="col s5">
            <?php
            $tipoorario='';
            $selected_giorno='';
            $selected_settimana='';
            $selected_calendario='';
            $selected_suchiamata='';
            if($fields['tipoorario']=='giorno')
            {
                $selected_giorno="selected='selected'";
            }
            if($fields['tipoorario']=='settimana')
            {
                $selected_settimana="selected='selected'";
            }
            if($fields['tipoorario']=='calendario')
            {
                $selected_calendario="selected='calendario'";
            }
            if($fields['tipoorario']=='suchiamata')
            {
                $selected_suchiamata="selected='suchiamata'";
            }
            ?>
            <?php
            $orariomedio_calendario_display='';
            $orariomedio_noncalendario_display='';
            if($fields['tipoorario']=='calendario')
            {
                $orariomedio_calendario_display='';
                $orariomedio_noncalendario_display='style="display:none"';
            }
            else
            {
                $orariomedio_calendario_display='style="display:none"';
                $orariomedio_noncalendario_display='';
            }
            ?>
                    <?php
                    if(($fields['tipoorario']=='giorno')||($fields['tipoorario']=='settimana')|| isempty($fields['tipoorario']))
                    {
                    ?>
                        <span class="orariomedio_noncalendario"><input id="ore" class=" " type="text" name="fields[ore]" value="<?=$fields['ore']?>" style="width: 50%"> ore </span>
                    <?php
                    }
                    ?>
                        
                    <span class="orariomedio_calendario" <?=$orariomedio_calendario_display?>>Come da</span>
                    <?php
                    if($funzione=='modifica')
                    {
                    ?>

                    <select name="fields[tipoorario]" onchange="cambio_orariomedio(this)">
                        <option value="giorno" <?=$selected_giorno?>>al giorno</option>
                        <option value="settimana" <?=$selected_settimana?>>alla settimana</option>
                        <option value="calendario" <?=$selected_calendario?>>calendario</option>
                        <option value="suchiamata" <?=$selected_suchiamata?>>su chiamata</option>
                    </select>   
                    <?php
                    }
                    else
                    {
                        if($fields['tipoorario']=='giorno')
                        {
                            echo 'al giorno';
                        }
                        if($fields['tipoorario']=='settimana')
                        {
                            echo 'alla settimana';
                        }
                        if($fields['tipoorario']=='calendario')
                        {
                            echo 'calendario';
                        }
                        if($fields['tipoorario']=='suchiamata')
                        {
                            echo 'su chiamata';
                        }

                    }
                    ?>
                <span class="orariomedio_calendario" <?=$orariomedio_calendario_display?>>aziendale allegato</span>
 
                    
                    

            
        </div>
        
    </div>
    <div class="row">
        <div class="col s5">
            Periodo di prova
        </div>
        <div class="col s5">
            <input id="periododiprova" class=" " type="text" name="fields[periododiprova]" value="<?=$fields['periododiprova']?>">
        </div>
    </div>
    <div class="row">
        <div class="col s1" >
            Reparto:
        </div>
        <div class="col s4" >
            <input id="reparto" class=" " type="text" name="fields[reparto]" value="<?=$fields['reparto']?>">
        </div>
        <div class="col s1" >
            Note:
        </div>
        <div class="col s6" >
            <input id="note" class=" " type="text" name="fields[note]" value="<?=$fields['note']?>">
        </div>
    </div>
   
    <div class="row">
        <div class="col s5">
            Grado di occupazione:
        </div>
        <div class="col s1">
            <input id="percoccupazione" class=" " type="number" name="fields[percoccupazione]" value="<?=$fields['percoccupazione']?>" style="width: 50%"> %
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Responsabile Nr. di telefono:
        </div>
        <div class="col s5">
            <?=$azienda['telefono1']?>
        </div>
    </div>
    <div class="row">
        <div class="col s5">
            Inizio della missione:
        </div>
        <div id='datainiziomissione' class="col s5">
            <?php
            if(isnotempty($fields['datainizio']))
                echo date('d/m/Y', strtotime($fields['datainizio']));
            ?>
        </div>
    </div>
    
    <div class="row h4"></div>
    <div class="row">
        <div class="col s6">
            <div class="row">
                <div class="col s5" style="font-weight: bold">
                    Salario orario lordo di base:
                </div>
                <div class="col s2">
                    &nbsp
                </div>
                <div class="col s4" style="font-weight: bold">
                    <input id="salarioorariolordobase" type="text" name="fields[salarioorariolordobase]" value="<?=$fields['salarioorariolordobase']?>">
                </div>
            </div>
            <div class="row">
                <div class="col s5">
                    Giorni festivi:
                </div>
                <div class="col s2">
                    <input id="giornifestiviperc" style="width: 67%" type="number" name="fields[giornifestiviperc]" value="<?=$fields['giornifestiviperc']?>"> %
                </div>
                <div class="col s4">
                    <input id="giornifestivi" type="text" name="fields[giornifestivi]" value="<?=$fields['giornifestivi']?>">
                </div>
            </div>
            <div class="row">
                <div class="col s5">
                    Indennità vacanze:
                </div>
                <div class="col s2">
                    <input id="indennitavacanzaperc" style="width: 67%" type="number" name="fields[indennitavacanzaperc]" value="<?=$fields['indennitavacanzaperc']?>"> %
                </div>
                <div class="col s4">
                    <input id="indennitavacanza" type="text" name="fields[indennitavacanza]" value="<?=$fields['indennitavacanza']?>">
                </div>
            </div>
            <div class="row">
                <div class="col s5">
                    13° mensilità:
                </div>
                <div class="col s2">
                    <input id="tredicesimaperc" style="width: 67%" type="number" name="fields[tredicesimaperc]" value="<?=$fields['tredicesimaperc']?>"> %
                </div>
                <div class="col s4" style="margin-bottom: 1px solid black">
                    <input id="tredicesima" type="text" name="fields[tredicesima]" value="<?=$fields['tredicesima']?>">
                </div>
            </div>
            <div class="row">
                <div class="col s5">
                    Salario orario totale:
                </div>
                <div class="col s2">
                    &nbsp
                </div>
                <div class="col s4" style="font-weight: bold">
                    <input id="salarioorariototale" type="text" name="fields[salarioorariototale]" value="<?=$fields['salarioorariototale']?>">
                </div>
            </div>
        </div>
        <div class="col s6">
            <div class="row">
                <div class="col s12" style="text-align: right;padding-right: 12px;">Fascia oraria indicativa di lavoro giornaliero:</div>
            </div>
            <div class="row">
                <div id='fasciaoraria' class="col s12 " style="text-align: right">
                    <?php
                    if($fields['fasciaoraria11']!='')
                        $fields['fasciaoraria11']=date("H:i", strtotime($fields['fasciaoraria11']));
                    if($fields['fasciaoraria12']!='')
                        $fields['fasciaoraria12']=date("H:i", strtotime($fields['fasciaoraria12']));
                    if($fields['fasciaoraria13']!='')
                        $fields['fasciaoraria13']=date("H:i", strtotime($fields['fasciaoraria13']));
                    if($fields['fasciaoraria14']!='')
                        $fields['fasciaoraria14']=date("H:i", strtotime($fields['fasciaoraria14']));
                    if($fields['fasciaoraria21']!='')
                        $fields['fasciaoraria21']=date("H:i", strtotime($fields['fasciaoraria21']));
                    if($fields['fasciaoraria22']!='')
                        $fields['fasciaoraria22']=date("H:i", strtotime($fields['fasciaoraria22']));
                    if($fields['fasciaoraria23']!='')
                        $fields['fasciaoraria23']=date("H:i", strtotime($fields['fasciaoraria23']));
                    if($fields['fasciaoraria24']!='')
                        $fields['fasciaoraria24']=date("H:i", strtotime($fields['fasciaoraria24']));
                    if($fields['fasciaoraria31']!='')
                        $fields['fasciaoraria31']=date("H:i", strtotime($fields['fasciaoraria31']));
                    if($fields['fasciaoraria32']!='')
                        $fields['fasciaoraria32']=date("H:i", strtotime($fields['fasciaoraria32']));
                    if($fields['fasciaoraria33']!='')
                        $fields['fasciaoraria33']=date("H:i", strtotime($fields['fasciaoraria33']));
                    if($fields['fasciaoraria34']!='')
                    $fields['fasciaoraria34']=date("H:i", strtotime($fields['fasciaoraria34']));
                    if($fields['fasciaoraria41']!='')
                        $fields['fasciaoraria41']=date("H:i", strtotime($fields['fasciaoraria41']));
                    if($fields['fasciaoraria42']!='')
                        $fields['fasciaoraria42']=date("H:i", strtotime($fields['fasciaoraria42']));
                    if($fields['fasciaoraria42']!='')
                        $fields['fasciaoraria43']=date("H:i", strtotime($fields['fasciaoraria43']));
                    if($fields['fasciaoraria44']!='')
                        $fields['fasciaoraria44']=date("H:i", strtotime($fields['fasciaoraria44']));
                    ?>
                    <div><input id="fasciaoraria11" type="time" name="fields[fasciaoraria11]" value="<?=$fields['fasciaoraria11']?>">/<input id="fasciaoraria12" type="time" name="fields[fasciaoraria12]" value="<?=$fields['fasciaoraria12']?>">/<input id="fasciaoraria13" type="time" name="fields[fasciaoraria13]" value="<?=$fields['fasciaoraria13']?>">/<input id="fasciaoraria14" type="time" name="fields[fasciaoraria14]" value="<?=$fields['fasciaoraria14']?>"><?php if($funzione=='modifica'){?> <span onclick="fasciaoraria_add(this)">+</span><?php } ?></div>

                    <?php
                    $opacity=0;
                    if((isnotempty($fields['fasciaoraria21']))||(isnotempty($fields['fasciaoraria22']))||(isnotempty($fields['fasciaoraria23']))||(isnotempty($fields['fasciaoraria24'])))
                    {
                        $opacity=100;
                    }
                    ?>
                    <div style="opacity: <?=$opacity?>"><input id="fasciaoraria21" type="time" name="fields[fasciaoraria21]" value="<?=$fields['fasciaoraria21']?>">/<input id="fasciaoraria22" type="time" name="fields[fasciaoraria22]" value="<?=$fields['fasciaoraria22']?>">/<input id="fasciaoraria23" type="time" name="fields[fasciaoraria23]" value="<?=$fields['fasciaoraria23']?>">/<input id="fasciaoraria24" type="time" name="fields[fasciaoraria24]" value="<?=$fields['fasciaoraria24']?>"><?php if($funzione=='modifica'){?> <span onclick="fasciaoraria_add(this)">+</span><span onclick="fasciaoraria_del(this)" style="margin-left: 5px;">-</span><?php } ?></div>
                    <?php
                    $opacity=0;
                    if((isnotempty($fields['fasciaoraria31']))||(isnotempty($fields['fasciaoraria32']))||(isnotempty($fields['fasciaoraria33']))||(isnotempty($fields['fasciaoraria34'])))
                    {
                        $opacity=100;
                    }
                    ?>
                    <div style="opacity: <?=$opacity?>"><input id="fasciaoraria31" type="time" name="fields[fasciaoraria31]" value="<?=$fields['fasciaoraria31']?>">/<input id="fasciaoraria32" type="time" name="fields[fasciaoraria32]" value="<?=$fields['fasciaoraria32']?>">/<input id="fasciaoraria33" type="time" name="fields[fasciaoraria33]" value="<?=$fields['fasciaoraria33']?>">/<input id="fasciaoraria34" type="time" name="fields[fasciaoraria34]" value="<?=$fields['fasciaoraria34']?>"><?php if($funzione=='modifica'){?> <span onclick="fasciaoraria_add(this)">+</span><span onclick="fasciaoraria_del(this)" style="margin-left: 5px">-</span><?php } ?></div>
                    <?php
                    $opacity=0;
                    if((isnotempty($fields['fasciaoraria41']))||(isnotempty($fields['fasciaoraria42']))||(isnotempty($fields['fasciaoraria43']))||(isnotempty($fields['fasciaoraria44'])))
                    {
                        $opacity=100;
                    }
                    ?>
                    <div style="opacity: <?=$opacity?>"><input id="fasciaoraria41" type="time" name="fields[fasciaoraria41]" value="<?=$fields['fasciaoraria41']?>">/<input id="fasciaoraria42" type="time" name="fields[fasciaoraria42]" value="<?=$fields['fasciaoraria42']?>">/<input id="fasciaoraria43" type="time" name="fields[fasciaoraria43]" value="<?=$fields['fasciaoraria43']?>">/<input id="fasciaoraria44" type="time" name="fields[fasciaoraria44]" value="<?=$fields['fasciaoraria44']?>"><?php if($funzione=='modifica'){?><span onclick="fasciaoraria_del(this)">-</span><?php } ?></div>
                </div>
            </div>
        </div>
    </div>
        
    
    <div class="row" style="margin-bottom: 0px">
        <div class="col s2" style="font-weight: bold">
            Altre indennità
        </div>
        <div class="col s1">
            &nbsp
        </div>
    </div>
    <div class="row" style="margin-bottom: 0px">
        
        <div class="col s2">
            &nbsp
        </div>
        <div class="col s1">
            &nbsp
        </div>
    </div>
    <div class="row" style="margin-bottom: 0px">
        
        
        <div class="col s1">
            Kilometrica:
        </div>
        <div class="col s11">
            <input id="indennitachilometrca" type="text" name="fields[indennitachilometrca]" value="<?=$fields['indennitachilometrca']?>" style="margin-left: 20px;">
        </div>
    </div>
    <div class="row" style="margin-bottom: 0px">
        
        <div class="col s1">
            pasto:
        </div>
        <div class="col s11">
            <input id="indennitapasto" type="text" name="fields[indennitapasto]" value="<?=$fields['indennitapasto']?>" style="margin-left: 20px;">
        </div>
    </div>
    <div class="row" style="margin-bottom: 0px">
        <div class="col s1">
            altre:
        </div>
        <div class="col s11">
            <input id="indennitaaltre" type="text" name="fields[indennitaaltre]" value="<?=$fields['indennitaaltre']?>" style="margin-left: 20px;">
        </div>
    </div>
    <div class="line"></div>
    <div class="row h1"></div>
    <div class="row" style="font-size: 11pt">
        Il contratto quadro che ha ricevuto in data <span id='datacontrattoquadroclc'>
        <?php
        if(isnotempty($dipendente['datacontrattoquadroclc']))
        {
            echo date('d/m/Y', strtotime($dipendente['datacontrattoquadroclc']));
        }
        ?>
        </span> 
        <?php
        if($fields['id']>2583)
        {
        ?>
        ,rispettivamente le sue successive versioni, regolano tutte le altre condizioni del rapporto di lavoro
        <?php   
        }
        else
        {
        ?>
            entra in vigore al momento della firma del presente contratto di missione, di cui è parte integrante
        <?php
        }
        ?>
        
    </div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row">
        <div class="col s6">
            Luogo e data:<br/>
            Bioggio, 
            <?php
            if(isempty($fields['datafirma']))
            {
                $fields['datafirma']=$fields['datainizio'];
            }
            if($funzione=='modifica')
            {
            ?>
            <input id="datafirma" class=" " style="width: 40%" type="date" name="fields[datafirma]" value="<?=$fields['datafirma']?>" onchange="aggiorna_datafirma(this)"> <br/>
            <?php
            }
            else
            {
                if(isnotempty($fields['datafirma']))
                    echo date('d/m/Y', strtotime($fields['datafirma']));
            }
            ?>
            <br/>
            Il prestatore di lavoro:<br/>
            (prestatore di servizi)
        </div>
        <div class="col s6">
            Luogo e data:<br/>
            Bioggio,
            <span id="datafirma2" class=" " style="width: 40%" >
            <?php
            if(isempty($fields['datafirma']))
            {
                $fields['datafirma']=$fields['datainizio'];
            }
            if($funzione=='modifica')
            {
            ?>
            <input id="datafirma2" class=" " style="width: 40%" type="date"  value="<?=$fields['datafirma']?>" onchange="aggiorna_datafirma(this)"> / <?=$sigla_creatore?> <br/>
            <?php
            }
            else
            {
                if(isnotempty($fields['datafirma']))
                    echo date('d/m/Y', strtotime($fields['datafirma']));
            }
            ?> 
            / <?=$sigla_creatore?>
            <br/>
            Il lavoratore:
        </div>
    </div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    <div class="row h4"></div>
    </div>
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
