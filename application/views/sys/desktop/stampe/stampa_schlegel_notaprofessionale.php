
<?php
require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/schlegel/NotaProfessionale.docx");


$document->setValue('cognomenome', $cognomenome);
$document->setValue('titcortesia', $titcortesia);
$document->setValue('indirizzo', $indirizzo);
$document->setValue('cap', $cap);
$document->setValue('paese', $paese);
$document->setValue('data', $data);
$document->setValue('nomepratica', $nomepratica);
$document->setValue('dal', $periodo_dal);
$document->setValue('al', $periodo_al);
$document->setValue('spese', $spese);
$document->setValue('ore', $ore);
$document->setValue('min', $minuti);
$document->setValue('tar', $tariffa);
$document->setValue('onorario', $onorario);
$document->setValue('esborsi', $esborsi);
$document->setValue('acconto', $acconto);
$document->setValue('perc', $percsconto);
$document->setValue('sconto', $sconto);
$document->setValue('iva', $iva);
$document->setValue('totale', $totale);








if(!file_exists("./stampe")){
    mkdir("../JDocServer/stampe");
}
if(!file_exists("../JDocServer/stampe/".$userid)){
    mkdir("../JDocServer/stampe/".$userid);
}
$document->save('../JDocServer/stampe/'.$userid.'/NotaProfessionale.docx');
echo urlencode("NotaProfessionale.docx");
?>
