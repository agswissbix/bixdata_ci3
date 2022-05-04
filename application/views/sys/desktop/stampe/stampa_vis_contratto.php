<?php
$dati=$data['dati'];
//$dati['asd']='<w:sym w:font="Wingdings" w:char="F06F"/>'; casella non barrata
//$dati['asd']='<w:sym w:font="Wingdings" w:char="F078"/>'; casella barrata
//$dati['asd']='<w:rPr><w:strike/></w:rPr><w:t>Testo barrato</w:t>'; testo barrato
$data['userid']=1;

//Mandante
$dati[1]='test'; 
//Check consigliere amministrazione
$dati[2]='<w:sym w:font="Wingdings" w:char="F06F"/>'; 
//Stringa consigliere amministrazione
$dati[3]='consigliere di amministrazione / amministratore unico / consigliere di fondazione';
//Check procura amministrativa
$dati[4]='<w:sym w:font="Wingdings" w:char="F06F"/>'; 
//Stringa procura amministrativa
$dati[5]='procura amministrativa'; 
//check procura generale
$dati[6]='<w:sym w:font="Wingdings" w:char="F06F"/>'; 
//stringa procura generale
$dati[7]='procura generale';

require_once 'phpword/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('stampe/modelli/VIS/Contratto_VIS.docx');

for ($i = 1; $i <= 7; $i++) {
    $document->setValue(strval($i), $dati[$i]);
}


$filename='Contratto_VIS.docx';
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$document->save('stampe/'.$data['userid'].'/Contratto_VIS.docx');
echo $filename;
?>
