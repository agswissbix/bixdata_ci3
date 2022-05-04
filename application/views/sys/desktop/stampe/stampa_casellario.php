
<?php
$nome=$dati['nome'];
$cognome=$dati['cognome'];
$sesso=$dati['sesso'];
$forma1='al.';
$forma2='sig.';
if($sesso=='F')
{
    $forma1='alla';
    $forma2='sig.ra';
}


require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate("stampe/modelli/WS/$tipodoc nome cognome.docx");


$document->setValue('cognome', $cognome);
$document->setValue('nome', $nome);
$document->setValue('forma1', $forma1);
$document->setValue('forma2', $forma2);



if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$userid)){
    mkdir("./stampe/".$userid);
}
$document->save($folder."/".$nome_casellario_generato.".docx");
?>
