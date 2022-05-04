
<?php
require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/DimensioneImmobiliare/AutoCompilati/Vendita/Mandato.doc");


$document->setValue('campo1', 'test');



if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$userid)){
    mkdir("./stampe/".$userid);
}
$document->save('stampe/'.$userid.'/Mandato.doc');
?>
