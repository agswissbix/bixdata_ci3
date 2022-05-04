<?php
$uniqueid=time().rand();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$link_originale=$data['link_originale'];
$link_visualizzatore=$data['link_visualizzatore'];
$extension=$data['extension'];
$extension=  strtolower($extension);
$settings=$data['settings'];
$cliente_id=$settings['cliente_id'];
?>
<script type="text/javascript">
    $(document).ready(function(i){
        var el=$('#visualizzatore_<?=$uniqueid?>');
        console.info(($(el).attr('id')));
         $(el).find('.img_preview').each(function(i){
            var css;
            var ratio=$(this).width() / $(this).height();
            var pratio=$(this).parent().width() / $(this).parent().height();
            if (ratio<pratio) css={width:'auto', height:'100%'};
            else css={width:'100%', height:'auto'};
            console.log(ratio, pratio, css);
            $(this).css(css);

        });
        
    });
    
    function prossimo_allegato(el)
    {
        var visualizzatore=$(el).closest('.visualizzatore');
        var tableid=$(visualizzatore).data('tableid');
        var recordid=$(visualizzatore).data('recordid');
        var filename=$(visualizzatore).data('filename');
        var url=controller_url+'ajax_load_block_visualizzatore_prossimo_allegato/'+tableid+'/'+recordid+'/'+filename;
             $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data=='ultimo')
                    {
                        alert('Ultimo allegato');
                    }
                    else
                    {
                        $(el).closest(".visualizzatore_popup_content").html(data);
                    }
                },
                error:function(){
                    alert('Errore');
                }
            });
    }
    
    function precedente_allegato(el)
    {
        var visualizzatore=$(el).closest('.visualizzatore');
        var tableid=$(visualizzatore).data('tableid');
        var recordid=$(visualizzatore).data('recordid');
        var filename=$(visualizzatore).data('filename');
        var url=controller_url+'ajax_load_block_visualizzatore_precedente_allegato/'+tableid+'/'+recordid+'/'+filename;
             $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data=='primo')
                    {
                        alert('Primo allegato');
                    }
                    else
                    {
                        $(el).closest(".visualizzatore_popup_content").html(data);
                    }
                },
                error:function(){
                    alert('Errore');
                }
            });
    }
    
   

</script>
<div class="block_popup">
                
</div>
<div id="visualizzatore_<?=$uniqueid?>" class="visualizzatore  ui-widget-content scheda" style="overflow-y: scroll" data-tableid='<?=$tableid?>' data-recordid='<?=$recordid?>' data-fileid='<?=$fileid?>' data-filename='<?=$fileid?>' >
<div id="visualizzatore_popup_<?=$uniqueid?>" class="popup visualizzatore_popup" style="" data-uniqueid="<?=$uniqueid?>">
    <div class="button b-close" style="z-index: 1000;position: absolute;right: -4px;top: -10px;text-align: center;width: 20px;background-color: #54ace0;cursor: pointer">
        X
    </div>
    <div id="visualizzatore_popup_content_<?=$uniqueid?>" class="visualizzatore_popup_content" style="height: 100%;width: 100%">

    </div>
</div>        
    <div id="menu_allegato" class="menu_small" style="overflow: visible">
            <?php
            if($data['extension']!='')
            {
            ?>
            
                <div id="btn_allarga_allegato" class="btn_fa fa fa-download tooltip"  style="float: right" onclick="window.open('<?=site_url()?>/sys_viewcontroller/download_allegato/<?=$tableid?>/<?=$data['nomefile']?>', '_blank')"  title="Scarica allegato originale"></div>
                
                <!--<div id="btn_allarga_allegato" class="btn_icona btn_print tooltip" style="float: right"  onclick="stampa_allegato(this,'<?=$link_originale?>')" title="Stampa allegato" ></div>-->
                <?php
                if(true)
                {
                ?>
                    <div id="btn_allarga_allegato" class="btn_fa fa fa-crop tooltip" title="Imposta immagine di riferimento" style="float: right"  onclick="ajax_load_block_jpgcrop(this,'<?=$data['recordid']?>','<?=$data['cartella']?>','<?=$data['nomefile']?>')" data-allargato="false"></div>
                    
                    <!--<div id="btn_allarga_allegato"    class="btn_scritta"  style="float: right;" onclick="allarga_allegato(this)" data-allargato="false">^</div>-->
                <?php
                }
                ?>
                
                <div id="page_categories" style="float: right;position: relative">
                    
                    <div class="btn_fa fa fa-tags" onclick="$(this).next().toggle()" style="color: black;cursor: pointer;"></div> 
                    <div class='check_category' style="width: 100px;position: absolute;right: -60px;top: 20px;background-color: white;border: 1px solid #dedede;z-index: 100;display: none;padding: 5px;overflow: visible;">
                        <?php
                        foreach ($categories as $key => $category) 
                        {
                            $checked='';
                            if(in_array($category['cat_id'], $selected_categories))
                            {
                                $checked='checked';
                            }
                        ?>
                            <div>
                                <input class="checkbox" type="checkbox" name="category[]" value="<?=$category['cat_id']?>" <?=$checked?>><span style="color: black;margin-left: 5px;"><?=$category['cat_description']?></span>
                            </div>
                        <?php
                        }
                        ?>
                        <br/>
                        <div class="btn_scritta" style="color: black" onclick="set_page_category(this)">Applica</div>
                        <?php
                        if($userid==1)
                        {
                        ?>
                        <div class="tooltip btn_fa fa fa-plus" title="Aggiungi categoria all'elenco" onclick="add_page_category(this)" style="float: right"></div>
                        <?php
                        }
                        ?>
                    </div>
                    <!--<select class="select_category" style="min-width: 150px;" onchange="set_page_category(this)">
                        <option value=""></option>
                    <?php
                        foreach ($categories as $key => $category) {
                    ?>
                        <option value="<?=$category['cat_id']?>"><?=$category['cat_description']?></option>
                    <?php
                        }
                    ?>
                    </select>-->
                    
                </div>
            <?php
            }
            ?>
        </div>
        
    <div id="visualizzatore_allegato" class="" style="margin-top: 5px;width: 100%;height: calc(100% - 30px);position: relative"> 
        <div style="position: absolute;top: 20px;left: 20px;" onclick="precedente_allegato(this)"><i class="far fa-arrow-alt-circle-left" style="font-size: 40px;cursor: pointer"></i></div>
        <div style="position: absolute;top: 20px;right: 20px;" onclick="prossimo_allegato(this)"><i class="far fa-arrow-alt-circle-right" style="font-size: 40px;cursor: pointer"></i></div>
            <?php
            if($data['extension']!='')
            {
                if(($link_visualizzatore!='null')&&($link_visualizzatore!='error'))
                {
                    if(($extension=='jpg')||($extension=='png'))
                    {
                    ?>
                        <img class="img_preview"src="<?=$link_visualizzatore?>?v=<?=time();?>" style="position: relative;display: block;margin: auto;height: 100%"></img>
                    <?php
                    } 
                    if(($extension=='pdf')||($extension=='doc')||($extension=='docx')||($extension=='tif')||($extension=='tiff'))
                    {
                    ?>
                        <iframe id="PDFtoPrint" src="<?=$link_visualizzatore?>?v=<?=time();?>" style="height: 100%;width: 100%" ></iframe>


                        <!--<object id="allegato_object"  data="<?=$link_visualizzatore?>"height="100%" width="100%" type="application/pdf" > file da visualizzare non trovato
                        </object> -->
                    <?php
                    }
                    if(($extension=='mp4'))
                    {
                    ?>
                    <video controls style="height: 100%;width: 100%">
                        <source src="<?=$link_visualizzatore?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                        
                <?php   
                    }
                }
                if(($link_visualizzatore=='null'))
                {
                ?>
                <div style="height: 60%;width: 60%;margin: auto;border: 1px solid #54b1e4; border-radius: 4px;margin-top: 50px;" >
                    <a href="<?=site_url()?>/sys_viewcontroller/download_allegato/<?=$tableid?>/<?=$data['nomefile']?>" target="blank_" style="display: block;width: 100%;height: 100%;text-align: center;line-height: 100%;padding-top: 30%;">
                        Visualizzatore non disponibile<br>
                        <span style="font-weight: bold">Clicca per scaricare il file originale</span>
                    </a>
                </div>
                <?php
                }
                if(($link_visualizzatore=='error'))
                {
                ?>
                
                <?php
                }
            }
            ?>
        </div>
</div>

