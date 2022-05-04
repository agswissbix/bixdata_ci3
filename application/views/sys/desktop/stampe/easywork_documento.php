<?php
require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("../JDocServer/Modelli/$modello");


$document->setValue('nomecognome', $nomecognome);
$document->setValue('cognomenome', $cognomenome);
$document->setValue('nome', $nome);
$document->setValue('cognome', $cognome);
$document->setValue('nomecognomemaiusc', $nomecognomemaiusc);
$document->setValue('email', $email);
$document->setValue('dataassunzione', $dataassunzione);
$document->setValue('indirizzo', $indirizzo);
$document->setValue('cap', $cap);
$document->setValue('paese', $paese);




$document->save('../JDocServer/DocumentiGenerati/'.$nomecognome.'-'.$id.'-'.$documento);
?>
