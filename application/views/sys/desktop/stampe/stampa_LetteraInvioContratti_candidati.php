
<?php
$dati=$data['dati'];
$wwws=$data['wwws'];

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/contratti$wwws/LetteraInvioContratti_candidati.docx");


$document->setValue('apertura', $dati['apertura']);
$document->setValue('cognome', $dati['cognome']);
$document->setValue('nome', $dati['nome']);
$document->setValue('domicilio', $dati['domicilio']);
$document->setValue('candidnpa', $dati['candidnpa']);
$document->setValue('candidcitta', $dati['candidcitta']);
$document->setValue('datacontr', $dati['datacontr']);


if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/LetteraInvioContratti_candidati.docx');
?>
