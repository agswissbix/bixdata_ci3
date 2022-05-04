<div id="genera_notaprofessionale_step1" style="padding: 20px;">
    <form>
        <input type="hidden" name="recordid" value="<?=$recordid?>">
        <div>Data limite:</div>
        <input type="date" name="datalimite" value="<?= date('Y-m-d')?>">
        <div style="margin-top: 10px;"> Sconto:</div> 
        <input type="number" name="percsconto" value="0"> 
        <div style="margin-top: 20px;">
            <div class="btn_scritta" style="float: right" onclick="genera_notaprofessionale_step2(this)">Crea</div>
        </div>
        
    </form>
</div>