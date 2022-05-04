<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function genera_preview($path_originale_noext,$ext)
{
    if(!file_exists("../JDocServer/".$path_originale_noext."_preview.jpg"))
    {
        if((strtolower($ext)=='jpg')||(strtolower($ext)=='png'))
        {
            img_resize($path_originale_noext.".$ext", 2048, 2048, $path_originale_noext."_preview.jpg");
        }

    }

}

function img_resize($path_originale,$width,$height,$path_destinazione)
{


    $command='cd ./tools/ImageMagick/ && convert "../../../JDocServer/'.$path_originale.'" -resize '.$width.'x'.$height.' -quality 50 "../../../JDocServer/'.$path_destinazione.'"';


    exec($command);

}


?>