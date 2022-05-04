
<?php
$dati=$data['dati'];
$wwws=$data['wwws'];

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/contratti$wwws/ContrattoAssunzionePersonalePrestito_WW.docx");


$document->setValue('azienda', $dati['azienda']);
$document->setValue('riferimen', $dati['riferimen']);
$document->setValue('datacontr', $dati['datacontr']);
$document->setValue('contracognome', $dati['contracognome']);
$document->setValue('contranome', $dati['contranome']);
$document->setValue('domicilio', $dati['domicilio']);
$document->setValue('candidnpa', $dati['candidnpa']);
$document->setValue('candidcitta', $dati['candidcitta']);
$document->setValue('funzione', $dati['funzione']);
$document->setValue('luogolav', $dati['luogolav']);
$document->setValue('datainiz', $dati['datainiz']);
$document->setValue('datafin', $dati['datafin']);
$document->setValue('duratalav', $dati['duratalav']);
$document->setValue('periodoprova', $dati['periodoprova']);
$document->setValue('orariolav', $dati['orariolav']);
$document->setValue('perclavorativa', $dati['perclavorativa']);
$document->setValue('retribora', $dati['retriboraria']);
$document->setValue('percferie', $dati['percferie']);
$document->setValue('ferie', $dati['ferie']);
$document->setValue('percindennita', $dati['percindennita']);
$document->setValue('indennita', $dati['indennita']);
$document->setValue('perc13esima', $dati['perc13esima']);
$document->setValue('13esima', $dati['13esima']);
$document->setValue('indennitapranzo', $dati['indennitapranzo']);
$document->setValue('salario', $dati['salario']);
$document->setValue('assegnifamiliari', $dati['assegnifamiliari']);



if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/ContrattoAssunzionePersonalePrestito_WW.docx');
?>
