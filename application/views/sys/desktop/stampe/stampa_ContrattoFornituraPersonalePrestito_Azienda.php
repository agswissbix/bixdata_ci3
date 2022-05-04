
<?php
$dati=$data['dati'];
$wwws=$data['wwws'];

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/contratti$wwws/ContrattoFornituraPersonalePrestito_Azienda.docx");


$document->setValue('riferimen', $dati['riferimen']);
$document->setValue('datacontr', $dati['datacontr']);
$document->setValue('ragsoc', $dati['ragsoc']);
$document->setValue('indirizzo', $dati['indirizzo']);
$document->setValue('aziendnpa', $dati['aziendnpa']);
$document->setValue('aziendcitta', $dati['aziendcitta']);
$document->setValue('contranome', $dati['contranome']);
$document->setValue('contracognome', $dati['contracognome']);
$document->setValue('qualprof', $dati['qualprof']);
$document->setValue('funzione', $dati['funzione']);
$document->setValue('luogolav', $dati['luogolav']);
$document->setValue('datainiz', $dati['datainiz']);
$document->setValue('ccl', $dati['ccl']);
$document->setValue('orariolav', $dati['orariolav']);
$document->setValue('prezzoon', $dati['prezzoon']);
$document->setValue('terminipagamento', $dati['terminipagamento']);


if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/ContrattoFornituraPersonalePrestito_Azienda.docx');
?>
