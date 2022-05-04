<?php
//width totale 12000. 9800 tenendo i margini a 1100


require_once 'phpword/PHPWord.php';
// New Word Document
$PHPWord = new PHPWord();

$PHPWord->setDefaultFontName('Century Gothic'); 
$PHPWord->setDefaultFontSize(11);

$sectionStyle = array('orientation' => null,
                      'marginLeft' => 1100,
                      'marginRight' => 1100,
                      'marginBottom' => 0,
                      );

$title_textFontStyle=array('size'=>3,'color'=>'#FFFFFF');
$title_textParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$tableFontStyle=array();
$table_label_FontStyle=array('bold'=>true,'size'=>10);
$table_value_FontStyle=array();
$tableParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);
$tableTextRunParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$cellStyle=array('valign'=>'center');
$cellFontStyle=array();
$cellParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$Stile_Intestazione=array('size'=>20);

$titolo_sezione=array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'D9D9D9','spacing'=>0,'spaceAfter'=>0);
$titolo_sottosezione=array('valign'=>'center','borderSize'=>12,'borderColor'=>'686868','bgColor'=>'EFEFEF','spacing'=>0,'spaceAfter'=>0);

$styleTable = array('borderColor'=>'7B7B7B',
                    'borderTopSize'=>12,
                    'borderLeftSize'=>12,
                    'borderRightSize'=>12,
                    'borderBottomSize'=>12,
                    'borderInsideHSize'=>6,
                    'borderInsideVSize'=>6,    
                    'cellMarginLeft'=>80
                    );
$PHPWord->addTableStyle('table', $styleTable);

$section = $PHPWord->createSection($sectionStyle);

$section->addText("Test PHPWord");
// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename='test_phpword.docx';
$filename= str_replace(" ", "", $filename);
$filename= utf8_decode($filename);
$filename= utf8_encode($filename);
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$userid)){
    mkdir("./stampe/".$userid);
}
$objWriter->save('stampe/'.$userid.'/'.$filename);
echo urlencode($filename);
?>
