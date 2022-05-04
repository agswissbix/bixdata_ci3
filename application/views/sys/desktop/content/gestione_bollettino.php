<?php
$block = $data['block'];
$schede=$data['schede'];
$tableid=$data['tableid'];
$cliente_id=$data['settings']['cliente_id'];
?>

<style type="text/css">
    #check_profili span
    {
        margin-left: 16px;
    }
</style>
<script type="text/javascript">    
//varabili globali
var schedaid=0;    //numero per identificare le schede aperte

var pinned=false;
var ultimascheda="";
var lastnav=$('#nav_risultati');
var bPopup_attesa_genera_bollettino;






function apply(){

    //$( ".tooltip" ).tooltip();   
}




function move_scrollbar(ele)
{
    var nav_id=ele.id;
    /*var position=$("#"+id).attr('data-position');
        $('#content_ricerca').animate({
                scrollLeft: position 
                }, 500); */
        target_id=$("#"+nav_id).attr('data-target_id');
        target=$('#'+target_id);
        $('#content_ricerca').scrollTo(target,500);
        
}





 
 
 
 
 
    
    window.onload=function(){
        //apply();
        $('#content_ricerca').show();
        
    }; 
</script>

<script type="text/javascript"> 
var urlprint_bollettino="<?php echo site_url('sys_viewcontroller/stampa_bollettino/'); ?>/";
var urldownload_bollettino="<?php echo site_url('sys_viewcontroller/download_bollettino'); ?>/";   


function genera_bollettino()
    {
        var codicebollettino=$('#codicebollettino').val();
        //var confirmation=confirm("Attenzione, questa operazione rigenera la lista candidati e le relative qualifiche e profili per il bollettino "+codicebollettino+". Eventuali modifiche manuali a questi dati verranno perse. Proseguire?");
            $('#attesa_genera_bollettino').html('');
            $('#attesa_genera_bollettino').html('Attendere <br/>Generazione in corso della lista candidati per il bollettino '+codicebollettino);
            bPopup_attesa_genera_bollettino=$('#attesa_genera_bollettino').bPopup();
            var url="<?php echo site_url('sys_viewcontroller/genera_tutti_candidati_bollettino'); ?>/"+codicebollettino;  
            $.ajax({
                url: url,
                success:function(data){
                    //alert('lista candidati per il bollettino'+codicebollettino+' generati');
                    bPopup_attesa_genera_bollettino.close();
                    var input=$("[id*=fields-codicebollettino]").first().find('.fieldValue0').first();
                    field_changed(input);
                    //refresh_risultati_ricerca();
                },
                error:function(){
                    alert("ERRORE RICHIESTA AJAX");
                }
            });
            
        
    }
    
function stampa_bollettino()
    {
        var codicebollettino=$('#codicebollettino').val();
        $.ajax({
            type: "POST",
            url: urlprint_bollettino+codicebollettino,
            data: $('#form_bollettino').serialize(),
            success:function(data){
                window.location.href = urldownload_bollettino + codicebollettino;
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });
    }
    
function codicebollettino_changed(el)
{
    var codicebollettino=$(el).val();
    console.info($("[id*=fields-codicebollettino]").first().find('.fieldValue0').first());
    var input=$("[id*=fields-codicebollettino]").first().find('.fieldValue0').first();
    $(input).val(codicebollettino);
    genera_bollettino();
    field_changed(input);
}    

$(document).ready(function(){
    ajax_load_block_risultati_ricerca($('#btnCerca'), '<?=$data['tableid']?>')
    

    //$( ".tooltip" ).tooltip();
    
    var options, a;
   $('#scheda_dati_ricerca_container').width(scheda_dati_ricerca_container_width);
    $('#scheda_riepilogo').width(scheda_riepilgo_width);
    $('#scheda_risultati').width(scheda_risultati_allargata_width);
    
    $('#ricerca_subcontainer').width(screen_width+scheda_record_container_width);


    $('#helper').bind('click', function(e) {
        e.preventDefault();
        $('#helper_popup').bPopup(); 
    });
    $('#dvLoading').fadeOut(1000);
    $('#content_ricerca').scrollTo(0,100);
    lastval='';
    var cloned_nav_button=$('#nav_scheda_hidden').clone();
    lastnav=cloned_nav_button;
    $('#nav_risultati').after(lastnav);
    $('.contentmenu').show();
    $('.contentmenu').find('.nav').each(function(i){
        if(($(this).attr('id')!='nav_ricerca')&&($(this).attr('id')!='nav_risultati')&&($(this).attr('id')!='nav_scheda_hidden'))
        {
            $(this).remove();
        }
    })

    //$(lastnav).remove();
});
</script>

<div id="helper_popup" class="popup" >
    Esito caricamento: <span style="font-weight: bold" id="response"></span>
    <br /><br />
    Indirizzo per aggiornare il profilo del candidato:
    <br />
    <a style="font-weight: bold;" id="jdoconline_url" href="" target="_blank"></a>
    <div style="margin-top: 50px;">
        <div id="btn_invia_mail" class="btn_scritta">Invia mail al candidato</div>
        <div class="clearboth"></div>
    </div>
</div>
<div class="content">
<div id="attesa_genera_bollettino" class="popup" style="width: 80% !important;height: 80% !important;padding: 10px;font-size: 20px;text-align: center;padding-top: 200px;">    
</div>
<div id="content_ricerca" class="contentbody" style=" width: 100%;overflow-x: scroll;overflow-y: hidden;">
 <div id="ricerca_subcontainer" class="subcontainer" style="height: 100%;" >
     <div style="float: left;">
         <div style="padding-left: 10px;">
        <form id="form_bollettino"> 
            <br/>
            <span style="margin-left: 5px;">Codice bollettino:</span>
            <select id="codicebollettino" name="codicebollettino" onchange="codicebollettino_changed(this)" style="width: 100px;">
                <option value=""></option>
                <option value="1712">1712</option>
                <option value="1711">1711</option>
                <option value="1710">1710</option>
                <option value="1709">1709</option>
                <option value="1708">1708</option>
                <option value="1707">1707</option>
                <option value="1706">1706</option>
                <option value="1705">1705</option>
                <option value="1704">1704</option>
                <option value="1703">1703</option>
                <option value="1702">1702</option>
                <option value="1701">1701</option>
                <option value="1612">1612</option>
                <option value="1611">1611</option>
                <option value="1610">1610</option>
                <option value="1609">1609</option>
                <option value="1608">1608</option>
                <option value="1607">1607</option>
                <option value="1606">1606</option>
                <option value="1605">1605</option>
                <option value="1604">1604</option>
                <option value="1603">1603</option>
                <option value="1602">1602</option>
                <option value="1601">1601</option>
                <option value="1512">1512</option>
                <option value="1511">1511</option>
                <option value="1510">1510</option>
                <option value="1509">1509</option>
                <option value="1508">1508</option>
                <option value="1507">1507</option>
                <option value="1506">1506</option>
                <option value="1505">1505</option>
                <option value="1504">1504</option>
                <option value="1503">1503</option>
                <option value="1502">1502</option>
                <option value="1501">1501</option>
            </select>
            <br/><br/>
            <div id="check_profili">
                <span>IMP     <input type="checkbox" name="profilo[Imp]" value="Imp"></span>
                <span>IT   <input type="checkbox" name="profilo[IT]" value="IT"></span>
                <span>FBAN    <input type="checkbox" name="profilo[FBan]" value="FBan"></span>
                <span>IND     <input type="checkbox" name="profilo[Pro]" value="Pro"></span>
                <span>ED     <input type="checkbox" name="profilo[EdMest]" value="EdMest"></span>
            </div>
            
            <br/>
            <div id="btnStampaBollettino" class="btn_scritta" onclick="stampa_bollettino(this)" style="float: none;background-color: white;">Stampa bollettino</div>
            <br/><br/>
         </form> 
      </div>
     <div id="scheda_dati_ricerca_container" class="scheda_container scheda_dati_ricerca_container"  style="float: left;height: 70%;">
          <?php
            echo $schede['scheda_dati_ricerca'];
            ?>
     </div>
     </div>
    <div id="scheda_risultati" class="blocco scheda_container " style="float: left;opacity: 0;">
             <div id="dvLoading_risultati" style="height: 100%;width: 100%;z-index: 1000;display: none">
                 <div class="blocco">
                     <h1>Caricamento</h1>
                 </div>
             </div>
         <div id="risultati_ricerca">
             <?php
             echo $block['block_risultati_ricerca'];
             ?> 
         </div>
     </div>
     
        
    </div>
</div>
</div>