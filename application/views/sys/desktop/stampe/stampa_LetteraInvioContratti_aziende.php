
<?php
$dati=$data['dati'];
$wwws=$data['wwws'];

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/contratti$wwws/LetteraInvioContratti_aziende.docx");


$document->setValue('ragsoc', $dati['ragsoc']);
$document->setValue('titrif', $dati['titrif']);
$document->setValue('azcognome', $dati['azcognome']);
$document->setValue('aznome', $dati['aznome']);
$document->setValue('indirizzo', $dati['indirizzo']);
$document->setValue('aziendnpa', $dati['aziendnpa']);
$document->setValue('aziendcitta', $dati['aziendcitta']);
$document->setValue('datacontr', $dati['datacontr']);
$document->setValue('formcort', $dati['formcort']);


if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/LetteraInvioContratti_aziende.docx');
?>
