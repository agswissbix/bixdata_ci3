<?php
$cliente_id=$data['cliente_id'];
$tableid=$data['tableid'];
$recordid=$data['recordid'];
$userid=$data['userid'];
$record_info=$data['record_info'];
$custom_background='background-color: white;';
if(($cliente_id=='About-x')&&($tableid=='aziende'))
{
    /*if($data['fields']['dacontattare']['value']=='si')
    {
        $custom_background='background-color:#FFAAAA;';
    }
    if($data['fields']['dacontattare']['value']=='no')
    {
        $custom_background='background-color:#C6FBC6;';
    }*/
}

//$data['fields']=array_values($data['fields']);
?>
<script type="text/javascript">  
    $(document).ready(function(){

        
        $("img").error(function () { 
            $(this).css({display:"none"}); 
});
        
    }); 

</script>
<div id='fissi_<?=$tableid?>_<?=$recordid?>' class='block_scheda fissi' style="position: relative;<?=$custom_background?> padding: 5px; width: calc(100% - 10px);overflow: hidden">
    <div class="dati_scheda_fissi" style="height: 110px;overflow: hidden">
    <?php
    if(true)
    {
        $data['fields']=array_values($data['fields']);
    ?>
        <div style="float: left;width: 35%;overflow: hidden;">
            <?php
            for ($i = 0; $i < 3; $i++) 
            {
                if(array_key_exists($i, $data['fields']))
                {
                    $field=$data['fields'][$i];
                    if($field['desc']!='Anteprima')
                    {
                        if(($field['value']!=null)&&($field['value']!=' ')&&($field['value']!=''))
                        {
                ?>
                            <div style="margin-top: 3px;">
                                <span style="font-weight: bold;"><?=$field['desc']?></span><span style="font-weight: normal;margin-left: 5px;"><?=$field['value']?></span>
                            </div>
            <?php
                        }
                    }
                }
            }
            ?>
            <?php
            if($cliente_id=='Dimensione Immobiliare')
            {
            ?>
                <div>
                    <b>Pubblicazione: <?=$notepubblicazione?></b>
                </div>
            <?php
            }
            ?>
        </div>
        <div style="float: left;width: 35%;overflow: hidden;">
            <?php
            for ($i = 3; $i < 6; $i++) 
            {
                if(array_key_exists($i, $data['fields']))
                {
                    $field=$data['fields'][$i];
                    if($field['desc']!='Anteprima')
                    {
                        if(($field['value']!=null)&&($field['value']!=' ')&&($field['value']!=''))
                        {
                ?>
                            <div style="margin-top: 3px;">
                                <span style="font-weight: bold;"><?=$field['desc']?></span><span style="font-weight: normal;margin-left: 5px;"><?=$field['value']?></span>
                            </div>
            <?php
                        }
                    }
                }
            }
            ?>
        </div>
        <div style="float: right;width: 25%;overflow: hidden;">
            <div>
                    <?php
                    if(file_exists("../JDocServer/record_preview/$tableid/$recordid.jpg"))
                    {
                    ?>
                        <img style="height: 100px;width:100px;" src="<?= domain_url()?>/JDocServer/record_preview/<?=$tableid?>/<?=$recordid?>.jpg?v=<?=time();?>"></img>
                    <?php
                    }
                    ?>
            </div>
        </div>
        <div class="clearboth"></div>
        

                

    
    <?php
    }
    ?>
    <?php
    if($data['tableid']=='CANDID')
    {
    ?>
    <div id="scheda_candid" style="text-align: center">
    <div style="width: 80px;float: left;border: 1px solid #d0d0d0;">
        <div style="width: 100%;height: 80px;border-bottom:1px solid #d0d0d0;">
            <?php

                $foto="";
                $foto= $data['foto_path'];
            ?>
            <div id="foto_record_container" onclick="salva_foto_record(this);" ><img src="<?php echo domain_url().$foto ?>?v=<?=time();?>" alt="" height="80px" width="80px"></img></div>
        </div>
        <div style="height: 20px;width: 100%">
            <?php
            $statodisp=$data['fields']['statodisp']['value'];
            if(strtoupper($statodisp)=='D')
            {    
            ?>
            <div style="background-color:#92D050 ;vertical-align: middle;text-align: center;height: 100%;width: 100%">
                Id: <b><?=$data['fields']['id']['value'] ?></b>
            </div>
            <?php
            }
            ?>
            <?php
            if(strtoupper($statodisp)=='A')
            {    
            ?>
            <div style="background-color:#AAA580 ;vertical-align: middle;text-align: center;height: 100%;width: 100%">
                Id: <b><?=$data['fields']['id']['value'] ?></b>
            </div>
            <?php
            }
            ?>
            <?php
            if(strtoupper($statodisp)=='N')
            {    
            ?>
            <div style="background-color:#7F7F7F ;vertical-align: middle;text-align: center;height: 100%;width: 100%">
                Id: <b><?=$data['fields']['id']['value'] ?></b>
            </div>
            <?php
            }
            ?>
            <?php
            if(strtoupper($statodisp)=='W')
            {    
            ?>
            <div style="background-color:#C00000 ;vertical-align: middle;text-align: center;height: 100%;width: 100%">
                Id: <b><?=$data['fields']['id']['value'] ?></b>
            </div>
            <?php
            }
            ?>
            <?php
            if(strtoupper($statodisp)=='WW')
            {    
            ?>
            <div style="background-color:#C00000 ;vertical-align: middle;text-align: center;height: 100%;width: 100%">
                Id: <b><?=$data['fields']['id']['value'] ?></b>
            </div>
            <?php
            }
            ?>

        </div>
    </div>
    <div style="float: left;border: 1px solid #d0d0d0;margin-left: 39px;width: calc(100% - 123px)">
        <div style="border-bottom: 1px solid #d0d0d0;">
            <div style="float: left;width: 45%;height: 16px;padding: 4px;">
                Cognome: <b><?=$data['fields']['cognome']['value']?></b>
            </div>
            <div style="float: left;width: 45%;border-left: 1px solid #d0d0d0;height: 16px;padding: 4px;">
                Nome: <b><?=$data['fields']['nome']['value']?></b>
            </div>
            <div class="clearboth"></div>
        </div>
        <div style="background-color: #F2F2F2;border-bottom: 1px solid #d0d0d0;padding: 5px;">
            <b><?=  strtoupper($data['fields']['qualifica']['value'])?></b>
        </div>
        <div style="border-bottom: 1px solid #d0d0d0;">
            <div style="float: left;width: 31%;height: 16px;padding: 4px;">
                Reg: <b><?=date('d/m/Y',  strtotime($data['record_info']['creation']))?></b>
            </div>
            <div style="float: left;width: 31%;border-left: 1px solid #d0d0d0;height: 16px;padding: 4px;">
                Cons: <b><?=$data['record_info']['creator']?></b>
            </div>
            <div style="float: left;width: 31%;border-left: 1px solid #d0d0d0;height: 16px;padding: 4px;">
                Dossier: <b><?=$data['fields']['pflash']['value']?></b>
            </div>
            <div class="clearboth"></div>
        </div>
        <div style="">
            <div style="float: left;width: 31%;height: 16px;padding: 4px;">
                Mod: <b><?=date('d/m/Y',  strtotime($data['record_info']['lastupdate']))?></b>
            </div>
            <div style="float: left;width: 31%;border-left: 1px solid #d0d0d0;height: 16px;padding: 4px;">
                Cons: <b><?=$data['record_info']['lastupdater']?></b>
            </div>
            <div style="float: left;width: 31%;border-left: 1px solid #d0d0d0;height: 16px;padding: 4px;">
                Disp: <b><?=$data['fields']['statodisp']['value']?></b>
            </div>
            <div class="clearboth"></div>
        </div>
    </div>
        <div class="clearboth"></div>
    </div>
    <?php
    }
    ?>
    <?php
    if($data['tableid']=='AZIEND')
    {
    ?>
    <div style="float: left;width: 35%;">
        Id: <b><?=$data['fields']['id']['value']?></b></br>
        Rag Sociale: <b><?=$data['fields']['ragsoc']['value']?></b></br>
        Consulente: <b><?= $data['fields']['consulente']['value'] ?></b></br>
        Data Reg: <b><?= $data['fields']['dataregistrazione']['value'] ?></b>
        
    </div>
    <div style="float: left;width: 35%;">
        Azienda Stato: <b><?=$data['fields']['aziendastato']['value'] ?></b></br>
        Stato ultimo contatto: <b><?= $data['fields']['statoultimocontatto']['value']?></b></br>
        Data ultimo contatto:<b><?= $data['fields']['dataultimocontatto']['value']?></b></br>
        Scadenza: <b><?=$data['fields']['scadenza']['value']?></b>
    </div>
    <?php 
    } 
    ?>
    <?php
    if($data['tableid']=='CONTRA')
    {
    ?>
    <div style="float: left;width: 35%;">
        Riferimento: <b><?=$data['fields']['riferimen']['value']?></b></br>
        Azienda: <b><?=$data['fields']['ragsoc']['value']?></b></br>
        Candidato: <b><?=$data['fields']['cognomenome']['value']?></b></br>
        Funzione: <b><?=$data['fields']['funzione']['value']?></b></br>
    </div>
    <div style="float: left;width: 35%;">
        Data inizio: <b><?=$data['fields']['datainiz']['value'] ?></b></br>
        Data fine: <b><?= $data['fields']['datafin']['value']?></b></br>
    </div>
    <?php 
    } 
    ?>

    

    <?php 
    
    if(($cliente_id=='About-x')&&($data['tableid']=='telemarketing'))
    {
        //var_dump($data['fields']);
    ?>
    <div style="float: left;width: calc(100% - 10px);overflow-y: scroll">
        <div style="float: left;width: 35%;">
            <b>Campagna:</b> <?=$data['fields']['campagne']['value'];?></br>
            <b>Stato:</b> <?=$data['fields']['statotelemarketing']['value']?></br>
            <b>Recall Date:</b> <?=$data['fields']['recalldate']['value']?></br>

        </div>
        <?php
        if(array_key_exists('ragionesociale', $data['fields']))
        {
        ?>
        <div style="float: left;width: 55%;">
            <b>Azienda:</b><?=$data['fields']['ragionesociale']['value']?></br>
            <b>Telefono: </b><?=$data['fields']['telefono']['value']?></br>
            <b>Mail: </b><?=$data['fields']['email']['value']?></br>
            <b>Città: </b><?=$data['fields']['citta']['value']?></br>
            <b>Indirizzo: </b><?=$data['fields']['indirizzo']['value']?></br>
        </div>
        <?php
        }
        ?>
        <div class="clearboth"></div>
        </br>
        <div>
            <?=$data['fields']['note_telemarketing']['value']?>
        </div>
    </div>
    <?php 
    }
    ?>
    <div class="clearboth"></div>
</div>
    
    
    <div class='record_info' style="margin-top: 3px;display: none;">
         <?php
        if($userid==1)
        {
        ?>
            <div style="float: left;height: 20px;">
                <div class='record_info_lastupdate' style="float: left;"><span style="color: #467bbd">Recordid: </span><?=$recordid?></div>
            </div>
        <?php
        }
        ?>
        <div style="float: left;height: 20px;margin-left: 5px;">
            <div class='record_info_creation' style="float: left;"><span style="color: #467bbd">Creato il</span> <?=$record_info['creation']?></div>
            <div class='record_info_creator' style="float: left;margin-left: 10px;"><span style="color: #467bbd">Da </span><?=$record_info['creator']?></div>
        </div>
        
        <div style="float: left;height: 20px;margin-left: 5px;">
            <div class='record_info_lastupdate' style="float: left;"><span style="color: #467bbd">Modificato il </span><?=$record_info['lastupdate']?></div>
            <div class='record_info_lastupdater' style="float: left;margin-left: 10px;"><span style="color: #467bbd">Da </span><?=$record_info['lastupdater']?></div>
        </div>
       
        
        <div class="clearboth"></div>
    </div>

    
</div>
