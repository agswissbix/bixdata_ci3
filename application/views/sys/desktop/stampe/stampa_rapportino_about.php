
<?php

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/About/Rapportino_ICT.docx");


$document->setValue('data', $dati['data']);
$document->setValue('azienda', $dati['azienda']);
$document->setValue('tipointervento', $dati['tipointervento']);
$document->setValue('tipoassistenza', $dati['tipoassistenza']);
$document->setValue('descrizione', $dati['descrizione']);
$document->setValue('tecnico', $dati['tecnico']);
$document->setValue('dalle', $dati['dalle']);
$document->setValue('alle', $dati['alle']);
$document->setValue('durata', $dati['durata']);



if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$userid.'/rapportino.docx');
?>
