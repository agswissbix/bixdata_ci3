
<?php
require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/schlegel/LetteraAccompagnamento.docx");


$document->setValue('cognomenome', $cognomenome);
$document->setValue('titcortesia', $titcortesia);
$document->setValue('indirizzo', $indirizzo);
$document->setValue('cap', $cap);
$document->setValue('paese', $paese);
$document->setValue('data', $data);
$document->setValue('nomepratica', $nomepratica);









if(!file_exists("./stampe")){
    mkdir("../JDocServer/stampe");
}
if(!file_exists("../JDocServer/stampe/".$userid)){
    mkdir("../JDocServer/stampe/".$userid);
}
$document->save('../JDocServer/stampe/'.$userid.'/LetteraAccompagnamento.docx');
echo urlencode("LetteraAccompagnamento.docx");
?>
