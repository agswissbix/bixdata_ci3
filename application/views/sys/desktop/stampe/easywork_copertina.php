<?php
require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("../JDocServer/Modelli/$modello");


$document->setValue('nome', $nome);
$document->setValue('cognome', $cognome);
$document->setValue('id', "*".$id."*");




$document->save('../JDocServer/DocumentiGenerati/'.$nomecognome.'-'.$id.'-'.$documento);
?>
