<?php
$dati=$data['dati'];
$dati['asd']='test';
$data['userid']=1;

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('stampe/modelli/VIS/profilorischio.docx');

$document->setValue('asd', $dati['asd']);
$filename='Contratto_VIS.docx';
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/ProfiloRischio.docx');
echo $filename;
?>
