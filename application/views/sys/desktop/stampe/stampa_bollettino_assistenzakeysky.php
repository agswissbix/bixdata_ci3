
<?php
$dati=$data['dati'];

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/Keysky/bollettino_assistenzakeysky.docx");


$document->setValue('numrapporto', $dati['numrapporto']);
$document->setValue('data', $dati['data']);
$document->setValue('ragsoc', $dati['ragsoc']);
$document->setValue('indirizzo', $dati['indirizzo']);
$document->setValue('nazione', $dati['nazione']);
$document->setValue('tipointervento', $dati['tipointervento']);
$document->setValue('tipoassistenza', $dati['tipoassistenza']);
$document->setValue('noteintervento', $dati['noteintervento']);
$document->setValue('tecnico', $dati['tecnico']);
$document->setValue('orainizio', $dati['orainizio']);
$document->setValue('orafine', $dati['orafine']);
$document->setValue('durata', $dati['durata']);
$document->setValue('istruzioniintervento', $dati['istruzioniintervento']);
$document->setValue('costoorario', $dati['costoorario']);
$document->setValue('dirittochiamata', $dati['dirittochiamata']);
$document->setValue('noteaggiuntive', $dati['noteaggiuntive']);


if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/bollettino_assistenzakeysky.docx');
?>
