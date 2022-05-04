<?php

require_once 'phpword/PHPWord.php';
$PHPWord = new PHPWord();
//Set default font
$PHPWord->setDefaultFontName('Calibri');
$PHPWord->setDefaultFontSize(11);

$sectionStyle = array('orientation' => null,
                      'marginLeft' => 400,
                      'marginRight' => 400,
                      'marginBottom' => 0,
                      );

$cell_id_width=800;
$cell_id_style=array('valign'=>'center','bgColor'=>'666666');
$text_id__font=array('bold'=>true,'color'=>'FFFFFF');
$text_id_par=array('spacing'=>0,'spaceBefore'=>0,'spaceAfter'=>0);

$cell_separatore_width=200;
$cell_separatore_style=array();


$cell_qualifiche_width=10106;
$cell_qualifiche_style=array('valign'=>'center','bgColor'=>'666666');
$text_qualifiche_font=array('bold'=>true,'color'=>'FFFFFF');
$text_qualifiche_par=array('spacing'=>0,'spaceBefore'=>0,'spaceAfter'=>0);

$cell_profiloflash_width=11106;
$cell_profiloflash_style=array('valign'=>'center','gridSpan'=>3);
$text_profiloflash_font=array('size'=>9);
$text_profiloflash_par=array('spacing'=>0,'spaceBefore'=>0,'spaceAfter'=>0);

$section = $PHPWord->createSection($sectionStyle);
// Add header
$header = $section->createHeader();
$table=$header->addTable(array('cellMarginLeft'=>80,'cellMarginTop'=>180));
// Add a row as normal.
$table->addRow(400);
$cell=$table->addCell(11106,array());
$cell->addImage("assets/images/logo_ww_scritta.jpg", array('width'=>241,'height'=>45,'align'=>'center'));



foreach ($profili as $key => $profilo) {
$candidati=$profilo['candidati'];
if(count($candidati)>0)
{
    $section->addText($intestazione_data,array('bold'=>true,'italic'=>true,'color'=>'#000000'));
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(300);
    $table->addCell(5553,array('borderBottomSize'=>'10','borderBottomColor'=>'#000000'));
    $titolo=$profilo['titolo'];
    $table->addCell(5553,array('valign'=>'center','bgColor'=>'000000','borderBottomSize'=>'10','borderBottomColor'=>'#000000'))->addText($titolo,array('bold'=>true,'color'=>'FFFFFF'),array('spacing'=>0,'spaceBefore'=>0,'spaceAfter'=>0,'align'=>'center'));

    $section->addTextBreak();

    $table=$section->addTable(array('cellMarginLeft'=>80));

    foreach ($candidati as $key => $candidato) {
        $table->addRow(50);
        $table->addCell($cell_id_width,$cell_id_style)->addText($candidato['idcandidato'],$text_id__font,$text_id_par);
        $table->addCell($cell_separatore_width,$cell_separatore_style);
        $table->addCell($cell_qualifiche_width,$cell_qualifiche_style)->addText(conv_text($candidato['qualifiche']),$text_qualifiche_font,$text_qualifiche_par);
        $table->addRow(800);
        $table->addCell($cell_profiloflash_width,$cell_profiloflash_style)->addText(conv_text($candidato['profiloflash']),$text_profiloflash_font,$text_profiloflash_par);
    }

    $section->addPageBreak();
}
}








// Save File
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$userid)){
    mkdir("./stampe/".$userid);
}

$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename="bollettino_$codicebollettino.docx";
$objWriter->save('stampe/'.$userid.'/'.$filename);
echo urlencode($filename);
?>