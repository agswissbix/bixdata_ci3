
<style type="text/css">
    @page {
            margin-top: 0.0em;
            margin-bottom: 0.0em;
            margin-left: 0.0em;
            margin-right: 0.0em;
        }
        
    
    .print_page
    {
        font-family: "Calibri" !important;
        font-size: <?=$settings['printpage_fontsize']?>mm !important;

        <?php
        if($orientation=='portrait')
        {
            $height=$settings['printpage_portrait_height'];
            $width="height:".$settings['printpage_portrait_width']."mm";
            $top=$settings['printpage_portrait_top'];
            $left=$settings['printpage_portrait_left'];
        }
        else
        {
            $height=$settings['printpage_landscape_height'];
            $width=$settings['printpage_landscape_width'];
            $top=$settings['printpage_landscape_top'];
            $left=$settings['printpage_landscape_left'];
        }
        ?>
        height: <?=$height?>mm;
        
        padding-top:<?=$settings['printpage_padding_top']?>mm;
        padding-left:<?=$settings['printpage_padding_top']?>mm;
        

    }
    
</style>
<div class="container_stampa">
<?=$content?>
</div>
