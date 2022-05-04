<script type="text/javascript">
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_bollettino/1510'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_bollettino'); ?>/";
    
    $(document).ready(function(){
        update_risultati_bollettino();
    });
    
    function update_risultati_bollettino()
    {
        var url=controller_url+'ajax_load_block_datatable_records/stampebollettini_candidati/risultati_ricerca';
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#form_bollettino').serialize(),
            success: function( response ) {
                $('#risultati_ricerca').html('');
                $('#risultati_ricerca').append(response);
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_bollettino()
    {
        alert('stampa bollettino');
        $.ajax({
            url: urlprint,
            success:function(data){
                window.location.href = urldownload + data;
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });
    }
</script>
<div class="content" id="content_gestione_bollettino">
    gestione bollettino
    <form id="form_bollettino">
        
    </form>
    <?=$block['dati_bollettino']?>
    
    <div class="scheda_dati_ricerca" >
        <textarea id="query" >
             select risultati.* FROM (SELECT recordid_, recordstatus_, idcandidato, cognome, qualifiche, profiloflash FROM user_stampebollettini_candidati WHERE true) AS risultati LEFT JOIN user_stampebollettini_candidati_owner ON risultati.recordid_=user_stampebollettini_candidati_owner.recordid_ where ownerid_ is null OR ownerid_=1
        </textarea>
    </div>
    <div onclick="stampa_bollettino(this)">Stampa bollettino</div>
    <div id="scheda_risultati" class="blocco scheda_container ">
            <div id="risultati_ricerca">

            </div>
    </div>
</div>