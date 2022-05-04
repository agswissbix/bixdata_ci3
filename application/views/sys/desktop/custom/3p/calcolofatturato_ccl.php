<?php
$vacanzecounter=array();
$lppcounter=array();
?>
<style type="text/css">
    table{
        font-family: Calibri;
        border-collapse: collapse;
        font-size: 12;
    }
    th{
        font-weight: normal !important;
        padding: 5px;
    }
    td{
        padding: 2px;
        white-space: nowrap;
    }
    .bt{
        border-top: 1px solid black;
    }
    .bb{
        border-bottom: 1px solid black;
    }
    .bl{
        border-left: 1px solid black;
    }
    .br{
        border-right: 1px solid black;
    }
    .btb{
       border-top: 1px solid black; 
       border-bottom: 1px solid black;
    }
</style>
<script type="text/javascript">
    function imposta_base(el)
    {
        $(el).html('Salva base');
        var td_base=$(el).closest('td');
        var recordid_fasciaeta=$(td_base).data('recordid_fasciaeta');
        $('.imposta').each(function(i){
            $(this).hide();
        });
        $('.check').each(function(i){
            $(this).show();
        });
        $(td_base).find('.check').hide();
        $(td_base).find('.salva').show();
        
        $('.fasciaeta_'+recordid_fasciaeta).each(function(i){
            $(this).find('.prezzovendita').find('.view').hide();
            $(this).find('.prezzovendita').find('.edit').show();
            var valore=$(this).find('.totfatt').html();
            valore=valore*10;
            valore=Math.ceil(valore);
            valore=valore/10;
            if($(this).find('.prezzovendita').find('input').val()==='')
            {
                $(this).find('.prezzovendita').find('input').val(valore);
            }
        });

    }
    
    function salva_base(el,recordid_fasciaeta)
    {
        var serialized=[];
        serialized.push({name: 'recordid_ccl', value: '<?=$recordid_ccl?>'});
        serialized.push({name: 'anno', value: '<?=$anno?>'});
        serialized.push({name: 'recordid_fasciaeta', value: recordid_fasciaeta});
        
        $('.fasciaeta_'+recordid_fasciaeta).each(function(i){
            var recordid_qualifica=$(this).data('recordid_qualifica');
            var valore=$(this).find('.prezzovendita').find('input').val();
            serialized.push({name: 'prezzivendita['+recordid_qualifica+']', value: valore});
        });
        
        $('.check').each(function(i){
             
             if($(this).find('input').is(":checked"))
             {
                 var recordid_fasciaeta_derivato=$(this).closest('td').data('recordid_fasciaeta');
                 serialized.push({name: 'fascieeta_derivate['+recordid_fasciaeta_derivato+']', value: recordid_fasciaeta_derivato});
             }

        });
        var url=controller_url + '/ajax_salva_base_calcolofatturato';
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                alert('salvato');
                ajax_load_content(this,'ajax_load_content_calcolofatturato_ccl/<?=$recordid_ccl?>/<?=$anno?>')
                
            }
        })
        
        
    }
</script>

<div id="calcolofatturato_ccl" style="height: 90%;width: 100%;padding: 10px; background-color: white;overflow: scroll">
    <i class="fas fa-arrow-circle-left" style="color: #11ba65;font-size: 28px;cursor: pointer" onclick="ajax_load_content(this,'ajax_load_content_calcolofatturato/<?=$anno?>')"></i>
    <div style="margin-top: 10px;">
        <div style="border: 1px solid black;text-align: center">
            Calcolo fatturato <?=$nomeccl?>
        </div>
        <table>
            <thead >
                <th class=""></th>
                <th class=""></th>
                <th class="" colspan="<?=count($vacanze)+3?>">Stipendio</th>
                <th class="" colspan="<?=count($lpp)?>">LPP</th>
                <th><?=$tipopensionamento?></th>
                <th colspan="2">3P</th>
                <th colspan="9">ONERI</th>
            </thead>
            <tbody>
                <tr >
                    <td class="bb">Classe</td>
                    <td class="bb">Età/esp</td>
                    <td class="btb bl">Base</td>
                    <?php
                    foreach ($vacanze as $key => $vacanza) {
                        ?>
                    <td class="btb">
                    vacanze<br/>
                    <?=$vacanza?>%
                    </td>
                    <?php
                    }
                    ?>
                    <td class="btb">giorni fest<br/>
                    <?=$festiviperc?>%
                    </td>
                    <td class="btb br">13°<br/>
                        <?=$tredperc?>%
                    </td>
                    <?php
                    foreach ($lpp as $key => $lpp_item) {
                        ?>
                    <td class="btb">
                    LPP<br/>
                    <?=$lpp_item?>%
                    </td>
                    <?php
                    }
                    ?>
                    <td class="btb bl br"><?=$tipopensionamento?><br/><?=$pensionamentoperc?>%</td>
                    <td class="btb">spese</td>
                    <td class="btb br">utile</td>
                    <td class="btb">AVS<br/><?=$avsperc?>%</td>
                    <td class="btb">AD<br/><?=$adperc?>%</td>
                    <td class="btb">inf.prof<br/><?=$infprofperc?>%</td>
                    <td class="btb">malattia<br/><?=$malattiaperc?>%</td>
                    <td class="btb">spese amm.<br/><?=$speseammperc?>%</td>
                    <td class="btb">Ass. figli<br/><?=$assfigliperc?>%</td>
                    <td class="btb">Ass. figli int.<br/><?=$assfigliintperc?>%</td>
                    <td class="btb">LorForm<br/><?=$lorformperc?>%</td>
                    <td class="btb">Form. Cont.<br/><?=$formcontperc?>%</td>
                    <td class="btb bl br">ind. pasto</td>
                    <td class="btb bl br">TOT Fatt.</td>
                    <td class="btb bl br">TOT UL.</td>
                    <td class="bb">RIC</td>
                    <td class="" style="text-align: center">Prezzo di <br/> vendita</td>
                </tr>
                <?php
                foreach ($fascieeta as $key => $fasciaeta) {
                    $recordid_fasciaeta=$fasciaeta['recordid_'];
                 ?>
                <tr>
                    <td colspan="2" class="bl bt br" style="text-align: center;font-weight: bold">
                        <?=$fasciaeta['descrizione']?>
                    </td>
                    <td class="bt" ></td>
                    <?php
                    $counter=0;
                    foreach ($vacanze as $key => $vacanza) {
                            if($counter % 2 == 0){ 
                                $color='#f9bb5d';
                            }
                            else
                            {
                                $color='#f9df83';
                            }
                            $counter++;
                            ?>
                    <td class="bt" style="background-color: <?=$color?>"></td>
                    <?php
                    }
                    ?>
                    <td class="br bt" colspan="2"></td>
                    <?php
                    $counter=0;
                    foreach ($lpp as $key => $lpp_item) {
                            if($counter % 2 == 0){ 
                                $color='#f9bb5d';
                            }
                            else
                            {
                                $color='#f9df83';
                            }
                            $counter++;
                            ?>
                    <td class="bt" style="background-color: <?=$color?>"></td>
                    <?php
                    }
                    ?>


                    <td class="bl br bt" ></td>
                    <td class="br bt" colspan="2"></td>
                    <td class="br bt" colspan="9"></td>
                    <td class="br bt" ></td>
                    <td class="br bt" ></td>
                    <td class="br bt" ></td>
                    <td class="bt br" ></td>

                    <td class="bt br" style="text-align: center;cursor: pointer"  data-recordid_fasciaeta="<?=$recordid_fasciaeta?>" onmouseover="$(this).find('.imposta').css('opacity','1')" onmouseout="$(this).find('.imposta').css('opacity','0')">
                        <div>
                            <?php
                            $record=reset($records[$recordid_fasciaeta]);
                            if($record['prezzovenditabase']!='')
                            {
                                if($record['prezzovenditabase']!='si')
                                {
                                    echo "Base ".$record['prezzovenditabase'];
                                }
                                else
                                {
                                    echo "";
                                }
                            }
                            ?>
                        </div>
                        <?php
                        if(($userid==2)||($userid==1)||($userid==7))
                        {
                        ?>
                        <div class="imposta" style="opacity: 0" class="imposta" onclick="imposta_base(this)">
                            Imposta come base
                        </div>
                        <div class="salva" onclick="salva_base(this,'<?=$recordid_fasciaeta?>')" style="display: none">
                            Salva
                        </div>
                        <div class="check" style="display: none">
                            <input  type="checkbox">
                        </div>
                        <?php
                        }
                        ?>
                    </td>



                </tr>
                <?php
                 foreach ($qualifiche as $key => $qualifica) {
                     $recordid_qualifica=$qualifica['recordid_'];
                     
                     if(array_key_exists($recordid_fasciaeta, $records))
                     {
                         if(array_key_exists($recordid_qualifica, $records[$recordid_fasciaeta]))
                         {
                             $record=$records[$recordid_fasciaeta][$recordid_qualifica];
                         ?>
                            <tr id="qualifica_<?=$recordid_qualifica?>_fasciaeta_<?=$recordid_fasciaeta?>" class="fasciaeta_<?=$recordid_fasciaeta?>" data-recordid_qualifica="<?=$recordid_qualifica?>" onmouseover="$(this).css('background-color','#aedcf7')" onmouseout="$(this).css('background-color','')">
                                <td class="bl"><?=$qualifica['qualifica']?></td>
                                <td class="br"><?=$qualifica['esperienza']?></td>
                                    <td><?=$record['base']?></td>
                                    <?php
                                    $vacanzeperc=$record['vacanzeperc'];
                                    $column_temp_counter=0;
                                    foreach ($vacanze as $key => $vacanza) {
                                        if($column_temp_counter % 2 == 0){ 
                                            $color='#f9bb5d';
                                        }
                                        else
                                        {
                                            $color='#f9df83';
                                        }
                                        if($vacanza==$vacanzeperc)
                                        {
                                        ?>
                                        <td style="background-color: <?=$color?>"><?=$record['vacanze']?></td>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                        <td style="background-color: <?=$color?>"></td>
                                        <?php
                                        }
                                      ?>

                                    <?php
                                    $column_temp_counter++;
                                    }
                                    ?>

                                    <td><?=$record['giornifest']?></td>
                                    <td class="br"><?=$record['tredicesima']?></td>
                                    <?php
                                    $lppperc=$record['lppperc'];
                                    $column_temp_counter=0;
                                    foreach ($lpp as $key => $lpp_item) {
                                        if($column_temp_counter % 2 == 0){ 
                                            $color='#f9bb5d';
                                        }
                                        else
                                        {
                                            $color='#f9df83';
                                        }
                                        if($lpp_item==$lppperc)
                                        {
                                        ?>
                                        <td style="background-color: <?=$color?>"><?=$record['lpp']?></td>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                        <td style="background-color: <?=$color?>"></td>
                                        <?php
                                        }
                                      ?>

                                    <?php
                                    $column_temp_counter++;
                                    }
                                    ?>
                                        <td class="bl br"><?=$record['pensionamento']?></td>
                                        <td><?=$record['spese']?></td>
                                        <td class="br"><?=$record['utile']?></td>
                                        <td><?=$record['avs']?></td>
                                        <td><?=$record['ad']?></td>
                                        <td><?=$record['infprof']?></td>
                                        <td><?=$record['malattia']?></td>
                                        <td><?=$record['speseamm']?></td>
                                        <td><?=$record['assfigli']?></td>
                                        <td><?=$record['assfigliint']?></td>
                                        <td><?=$record['lorform']?></td>
                                        <td class="br"><?=$record['formcont']?></td>
                                        <td class="br"><?=$record['indpasto']?></td>
                                        <td class="br totfatt"><?=$record['totfatt']?></td>
                                        <td class="br totul" style="background-color: #9fdc8a"><?=$record['totul']?></td>
                                        <td class="br"><?=$record['ric']?></td>
                                        <?php
                                        $background="";
                                        if($record['prezzovenditabase']=='si')
                                        {
                                            $background="background-color:#c4c6f5";
                                        }
                                        ?>
                                        <td class="br prezzovendita" style="text-align: center;<?=$background?> " >
                                            <div class="view">
                                            <?=$record['prezzovendita']?>
                                            </div>
                                            <div class="edit" style="display: none;">
                                                <input type="text" style="width: 40px" value="<?=$record['prezzovendita']?>">
                                            </div>
                                        </td>
                                </tr>
                         <?php
                             
                         }
                         else
                         {
                         ?>
                                <tr id="qualifica_<?=$recordid_qualifica?>_fasciaeta_<?=$recordid_fasciaeta?>" class="fasciaeta_<?=$recordid_fasciaeta?>" data-recordid_qualifica="<?=$recordid_qualifica?>" onmouseover="$(this).css('background-color','#aedcf7')" onmouseout="$(this).css('background-color','')">
                                <td class="bl"><?=$qualifica['qualifica']?></td>
                                <td class="br"><?=$qualifica['esperienza']?></td>
                                </tr>
                        <?php
                         }
                     }
                     else
                     {
                     ?>
                                <tr id="qualifica_<?=$recordid_qualifica?>_fasciaeta_<?=$recordid_fasciaeta?>" class="fasciaeta_<?=$recordid_fasciaeta?>" data-recordid_qualifica="<?=$recordid_qualifica?>" onmouseover="$(this).css('background-color','#aedcf7')" onmouseout="$(this).css('background-color','')">
                                <td class="bl"><?=$qualifica['qualifica']?></td>
                                <td class="br"><?=$qualifica['esperienza']?></td>
                                </tr>
                      <?php          
                         
                     }
                     ?>
                
                    <?php
                     }
                    ?>

                <?php
                }
                ?>
            <t>
                <td class="bt"></td>
                <td class="bt"></td>
                <td class="bt" colspan="<?=count($vacanze)+3?>"></td>
                <td class="bt" colspan="<?=count($lpp)?>"></td>
                <td class="bt"></td>
                <td class="bt" colspan="2"></td>
                <td class="bt" colspan="9"></td>
                <td class="bt"></td>
                <td class="bt"></td>
                <td class="bt"></td>
                <td class="bt" colspan="2"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>