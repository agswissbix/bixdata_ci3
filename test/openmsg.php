<?php
header("Pragma: public");
header("Expires: 0");
header('Content-Encoding: UTF-8');
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-type: application/vnd.ms-outlook;charset=UTF-8"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=sample.msg");
header("Content-Transfer-Encoding: binary ");
    readfile("sample.msg"); 
    ?>