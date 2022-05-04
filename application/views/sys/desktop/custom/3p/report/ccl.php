<?php
            $height=$settings['printpage_portrait_height'];
            $width=$settings['printpage_portrait_width'];
            
            if(count($blocchi)==0)
            {
                $blocco['sfondo']='rgba(0, 0, 0, 0)';
                $blocco['contenuto']='<div style="text-align: center; line-height: 1.6;"><span style="font-size: 36px;">'.$ccl['nomeccl'].'</span><span style="font-size: 36px;">﻿</span></div>';
                $blocco['gsx']=0;
                $blocco['gsy']=0;
                $blocco['gswidth']=12;
                $blocco['gsheight']=1;
                $blocchi['header'][0]=$blocco;
            }
            $blockcounter_header=count($blocchi['header'])-1;
            $blockcounter_footer=count($blocchi['footer'])-1;
            
?>
<?php
if($print)
{
?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/jquery-ui.css") ?>" />

<?php
}
?>




<style type="text/css">
    
    
    #report_ccl_container{
        
        font-family: Calibri;
        margin: auto;
        position: relative;
        padding: 50px;
    }
    
    #report_ccl_title{
        height: 50px;;
        line-height: 50px;
        text-align: center;
        font-size: 20px;
        width: 100%;
        box-shadow: 
        2px 0 0 0 black, 
        0 2px 0 0 black, 
        2px 2px 0 0 black,   /* Just to fix the corner */
        2px 0 0 0 black inset, 
        0 2px 0 0 black inset;
        
    }
    .report_ccl_blocchi{
        width: 100%;
        
    }
    #report_ccl_table{
        width: 100%;
    }
    
    #report_ccl_table td{
        text-align: center;
        padding: 5px;
    }
    
 

 
    
    #report_ccl_footer{

    }
    

    #report_ccl .grid-stack-item{
        box-shadow: 
        2px 0 0 0 black, 
        0 2px 0 0 black, 
        2px 2px 0 0 black,   /* Just to fix the corner */
        2px 0 0 0 black inset, 
        0 2px 0 0 black inset;
    }
    
    .block_menu{
        background-color: white;
        width: 97%;
        padding-top: 5px;
        padding-left: 5px;
        position: absolute;
        top: 0px;
        left: 0px;
        height: 30px;
        border: 1px solid black;
        display: none;
    }

    
    .grid-stack>.grid-stack-item>.grid-stack-item-content{
        overflow: hidden;
    }
</style>

<script type="text/javascript">
    var editingBlockID;
    var editingContentID;
    var colorWheel;
    $(document).ready(function() {
        var options = {
            verticalMargin: 0
            <?php
            if(!$edit)
            {
            ?>
                ,disableResize:true,
                disableDrag:true
            <?php
            }
            ?>
        };
        var GridStack=$('.grid-stack').gridstack(options);
        
        colorWheel = new iro.ColorPicker("#colorWheelDemo", {
            
        });
    });
    
    function editBlock(el)
    {
        //$('.bPopup_edit_ccl').bPopup();
        console.info('editBlock');
        var editingContent=$(el).closest('.grid-stack-item').find('.htmlcontent');
        var htmlcontent=$(editingContent).html();
        editingContentID=$(editingContent).attr('id');
        $('#summernote').summernote({
            height: 200,
            toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'fontsize']],
              ['color', ['forecolor','backcolor']],
              ['paragraph',['paragraph','height']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
          });
          $('#summernote').summernote('code', htmlcontent);
          $('#editBlock').show(500);
        
    }
    
    function editColor(el)
    {
        var editingBlock=$(el).closest('.grid-stack-item');
        editingBlockID=$(editingBlock).attr('id');
        $('#editColor').show(500);
    }
    
    function editRules(el)
    {
        $('#editRules').show(500);
    }
    
    function deleteBlock(el)
    {
        var block=$(el).closest('.grid-stack-item');
        var grid=$(el).closest('.grid-stack').data('gridstack');
        grid.removeWidget(block);
    }
    
    function saveBlock(el)
    {
       
        $(el).closest('#editBlock').hide(500);
        var htmlcontent=$('#summernote').summernote('code');
        $('#'+editingContentID).html(htmlcontent);
    }
    
    function saveColor(el)
    {
        var myColor = colorWheel.color.rgbString;
        $('#'+editingBlockID).css('background-color',myColor);
        $(el).closest('#editColor').hide(500);
        

    }
    
    function saveRules(el)
    {
        $(el).closest('#editRules').hide(500);
    }
  
  function addBlock(section)
  {
       var el=$('#grid-stack-item-template').clone();
        //var el  = jQuery( '<div class="grid-stack-item"><div class="grid-stack-item-content">test</div></div>' );
        if(section=='header')
        {
            var blockcounter_header=$('#blockcounter_header').val();
            blockcounter_header=Number(blockcounter_header)+1;
            $('#blockcounter_header').val(blockcounter_header);
            $(el).data('section','header');
            $(el).attr('id','grid-stack-item-header-'+blockcounter_header);
            $(el).find('.htmlcontent').attr('id','htmlcontent-header-'+blockcounter_header);
            var grid=$('#grid-stack-header').data('gridstack');
            grid.addWidget(el, 0, 0, 3, 2, true);
        }
        
        if(section=='footer')
        {
            var blockcounter_footer=$('#blockcounter_footer').val();
            blockcounter_footer=Number(blockcounter_footer)+1;
            $('#blockcounter_footer').val(blockcounter_footer);
            $(el).data('section','footer');
            $(el).attr('id','grid-stack-item-footer-'+blockcounter_footer);
            $(el).find('.htmlcontent').attr('id','htmlcontent-footer'+blockcounter_footer);
            var grid=$('#grid-stack-footer').data('gridstack');
            grid.addWidget(el, 0, 0, 3, 2, true);
        }
       
  }
  
  function saveReportCCL()
  {
      var serialized=[];
      serialized.push({name: 'recordid_ccl', value: '<?=$recordid_ccl?>'});
      $('.grid-stack-item').each(function(i) {
          var blockid=$(this).attr('id');
          var htmlcontent=$(this).find('.htmlcontent').html();
          var gsx=$(this).data('gs-x');
          var gsy=$(this).data('gs-y');
          var gswidth=$(this).data('gs-width');
          var gsheight=$(this).data('gs-height');
          var sfondo=$(this).css('background-color');
          var section=$(this).data('section');
          
          serialized.push({name: 'block['+blockid+'][htmlcontent]', value: htmlcontent});
          serialized.push({name: 'block['+blockid+'][gsx]', value: gsx});
          serialized.push({name: 'block['+blockid+'][gsy]', value: gsy});
          serialized.push({name: 'block['+blockid+'][gswidth]', value: gswidth});
          serialized.push({name: 'block['+blockid+'][gsheight]', value: gsheight});
          serialized.push({name: 'block['+blockid+'][sfondo]', value: sfondo});
          serialized.push({name: 'block['+blockid+'][zona]', value: section});
      });
      console.info(serialized);
        
        
        var url=controller_url + '/saveReportCCL/' ;
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                alert('Salvato');
            },
            error:function(){
                alert('errore');
            }
        })
  }
  
</script>
<input id="blockcounter_header" type="hidden"  value=<?=$blockcounter_header?>>
<input id="blockcounter_footer" type="hidden"  value=<?=$blockcounter_footer?>>
<?php
$class_print_page='';
if($print)
{
    $class_print_page='print_page';
}
?>
<div style="display: none">
    <div id="grid-stack-item-template" class="grid-stack-item" onmouseover="$(this).find('.block_menu').show()" onmouseout="$(this).find('.block_menu').hide()" data-section=''>
        <div class="grid-stack-item-content" style="padding-top: 5px;" >
            <div style="position: relative">
                <?php
                if($edit)
                {
                ?>
                <div class="block_menu" style="">
                        <button class="edit" onclick="editBlock(this)" >Contenuto</button>
                        <button class="edit" onclick="editColor(this)" >Sfondo</button>
                        <button class="edit" onclick="editRules(this)" >Regole</button>
                        <button class="edit" onclick="deleteBlock(this)" >Elimina</button>
                </div>
                <?php
                }
                ?>
                <div id="htmlcontent-template" class="htmlcontent">

                </div>
            </div>
        </div>
    </div>
</div>


<div id="" class="" style="background-color: white;">
    <div id="editBlock" style="display: none;">
        <button onclick="saveBlock(this)">Applica</button>
        <button onclick="$(this).closest('#editBlock').hide(500);">Annulla</button>
        <div id="summernote" ></div>
    </div>
    
    <div id='editColor' style="display: none">
        <button onclick="saveColor(this)">Applica</button>
        <button onclick="$(this).closest('#editColor').hide(500);">Annulla</button>
        <div class="wheel" id="colorWheelDemo"></div>
        <input type="text" value="">
    </div>
    
    <div id="editRules" style="display: none">
        <button onclick="saveRules(this)">Applica</button>
        <button onclick="$(this).closest('#editRules').hide(500);">Annulla</button>
        <div>Regole</div>
    </div>
</div>


<div class="report_ccl_container <?=$class_print_page?>">
<div id="report_ccl" style="padding-top: 50px;">
    <div id="report_ccl_container">
        <?php
        if($edit)
        {
        ?>
            <button onclick="saveReportCCL()" style="padding: 10px;float: right;">Salva</button>
        <?php
        }
        ?>
        <div style="clear: both"></div>
        <div id="report_ccl_blocchi_header" class="report_ccl_blocch">
            <?php
            if($edit)
            {
            ?>
                <button onclick="addBlock('header')">Aggiungi blocco</button>
            <?php
            }
            ?>
            <br/><br/>
            
            
            
            <div class="grid-stack" id="grid-stack-header" >
                <?php
                $counter=0;
                foreach ($blocchi['header'] as $key => $block) {
                ?>   
                <div id="grid-stack-item-header-<?=$counter?>" class="grid-stack-item" style="background-color: <?=$block['sfondo']?>; !important" onmouseover="$(this).find('.block_menu').show()" onmouseout="$(this).find('.block_menu').hide()" data-gs-x="<?=$block['gsx']?>" data-gs-y="<?=$block['gsy']?>" data-gs-width="<?=$block['gswidth']?>" data-gs-height="<?=$block['gsheight']?>" data-section="header" >
                    <div class="grid-stack-item-content"   >
                        <div style="position: relative">
                            <?php
                            if($edit)
                            {
                            ?>
                            <div class="block_menu" style="">
                                    <button class="edit" onclick="editBlock(this)" >Contenuto</button>
                                    <button class="edit" onclick="editColor(this)" >Sfondo</button>
                                    <button class="edit" onclick="editRules(this)" >Regole</button>
                                    <button class="edit" onclick="deleteBlock(this)" >Elimina</button>
                            </div>
                            <?php
                            }
                            ?>
                            <div id="htmlcontent-header-<?=$counter?>" class="htmlcontent">
                                <?php
                                $contenuto=$block['contenuto'];
                                $contenuto= str_replace("''", "'", $contenuto);
                                echo $contenuto;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $counter++;
                }
                ?>
                
                
                
            </div>
        </div>
        <div id="report_ccl_table" style="margin-top: 100px;">
            <table style="border-collapse: collapse;width: 100%;">
                <tbody>
                    <tr class="report_ccl_table_header">
                        <td style="border-left: 2px solid black;border-top: 2px solid black;border-bottom: 1px solid black;">
                            oneri
                        </td>
                        <td style="border-top: 2px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
                            <?=$ccl['utile']?>
                        </td>
                        <td style="border-top: 2px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">
                            <?=$ccl['spese']?>
                        </td>
                        <td style="border-top: 2px solid black;border-left: 1px solid black;border-bottom: 1px solid black;border-right: 1px solid black">
                            LPP
                        </td>
                        <td colspan="<?=3+$nrfascie?>" style="border-top: 2px solid black;border-bottom: 1px solid black;">
                            
                        </td>
                        <td style="border-top: 2px solid black;border-right:2px solid black; ">
                        </td>
                        
                    </tr>
                    <tr class="report_ccl_tr_header">
                        <td colspan="4" style="border-left: 2px solid black;border-bottom: 2px solid black;text-align: left">INQUADRAMENTO</td>
                        <td style="border-left: 1px solid black;border-bottom: 2px solid black;">ESP.</td>
                        <td style="border-left: 1px solid black;border-bottom: 2px solid black;">STIP.M.</td>
                        <td style="border-left: 1px solid black;border-bottom: 2px solid black;">STIP.ORARIO</td>
                        <?php
                                foreach ($fascie as $key_fascia => $fascia) {
                                ?>
                                    <td style="border-left: 1px solid black;border-bottom: 2px solid black;"><?=$fascia['nomecolonna']?></td>
                                <?php
                                }
                                ?>
                       
                        <?php
                        /*if(count($qualifiche)>0)
                        {
                            foreach ($qualifiche[0]['fascie'] as $key => $fascia) {
                            ?>
                                <td style="border-left: 1px solid black;border-bottom: 2px solid black;"><?=$fascia['da']?>-<?=$fascia['a']?></td>
                            <?php
                            }
                        } */
                        ?>
                        <td style="border-top: 1px solid black;border-bottom: 2px solid black;border-right:2px solid black; ">
                        </td>
                    </tr>
                    <?php
                    $count_qualifiche=count($qualifiche);
                    $counter_qualifiche=0;
                    foreach ($qualifiche as $descrizione_qualifica => $qualifica_gruppo) {
                        
                        $counter_group=0;
                        $tr_border='';
                        foreach ($qualifica_gruppo as $recordid_qualifica => $qualifica) {
                            $counter_group++;
                            if($counter_group==count($qualifica_gruppo))
                            {
                                $tr_border="border-bottom: 1px solid black;";
                            }
                            $fascie=$qualifica['fascie'];
                            $descrizione_qualifica=$qualifica['qualifica']."<br/>".$qualifica['descrizione'];
                            $bordertop='border-top: 2px solid black;';
                            $borderbottom='border-bottom: 2px solid black;';
                            
                            if($counter_group!=1)
                            {
                                $descrizione_qualifica='';
                                $borderbottom='';
                                $bordertop='';
                            }
                            if($counter_group==(count($qualifica_gruppo)-1))
                            {
                                $borderbottom='border-bottom: 2px solid black;';
                            }
                            ?>
                            <tr class="report_ccl_tr_body" style="<?=$tr_border?>" >
                                <td colspan="4" style="border-left:2px solid black;text-align: left;"><?=$descrizione_qualifica?></td>
                                <td style="white-space: nowrap;"><?=$qualifica['esperienza']?></td>
                                <td style="white-space: nowrap;"><?=$qualifica['stipmensile']?></td>
                                <td style="white-space: nowrap;"><?=$qualifica['base']?> CHF/ora</td>
                                <?php
                                foreach ($fascie as $key_fascia => $fascia) {
                                ?>
                                    <td style="border-left: 1px solid black;white-space: nowrap;"><?=$fascia['prezzovendita']?> fr/ora</td>
                                <?php
                                }
                                ?>
                                <td style="border-right:2px solid black;white-space: nowrap; ">
                                </td>
                            </tr>

                            <?php
                        
                        
                        }
                        $counter_qualifiche++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <br/><br/><br/>
            <div id="report_ccl_blocchi_footer" class="report_ccl_blocch">
                <?php
                if($edit)
                {
                ?>
                    <button onclick="addBlock('footer')">Aggiungi blocco</button>
                <?php
                }
                ?>
                <br/><br/>



                <div class="grid-stack" id="grid-stack-footer">
                    <?php
                    $counter=0;
                    foreach ($blocchi['footer'] as $key => $block) {
                    ?>   
                    <div id="grid-stack-item-<?=$counter?>" class="grid-stack-item" style="background-color: <?=$block['sfondo']?> !important" onmouseover="$(this).find('.block_menu').show()" onmouseout="$(this).find('.block_menu').hide()" data-gs-x="<?=$block['gsx']?>" data-gs-y="<?=$block['gsy']?>" data-gs-width="<?=$block['gswidth']?>" data-gs-height="<?=$block['gsheight']?>" data-section="footer" >
                            <div class="grid-stack-item-content" >
                                <div style="position: relative">
                                    <?php
                                    if($edit)
                                    {
                                    ?>
                                    <div class="block_menu" style="">
                                            <button class="edit" onclick="editBlock(this)" >Contenuto</button>
                                            <button class="edit" onclick="editColor(this)" >Sfondo</button>
                                            <button class="edit" onclick="editRules(this)" >Regole</button>
                                            <button class="edit" onclick="deleteBlock(this)" >Elimina</button>
                                    </div>
                                    <div id="htmlcontent-footer-<?=$counter?>" class="htmlcontent">
                                        <?php
                                        $contenuto=$block['contenuto'];
                                        $contenuto= str_replace("''", "'", $contenuto);
                                        echo $contenuto;
                                        ?>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    $counter++;
                    }
                    ?>



                </div>
            </div>
    </div>

        
</div>
</div>