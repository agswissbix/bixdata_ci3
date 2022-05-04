<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$table = $section->addTable(array('cellMargin'=>30));
$table->addRow(400);
$table->addCell(500,array('borderSize'=>1,'valign'=>'center'))->addText('ID',null,array('align'=>'center'));
$table->addCell(1000,array('borderSize'=>1,'valign'=>'center'))->addText('23776',null,array('align'=>'center'));
$table->addCell(8000,array('bgColor'=>'FFFFFF','borderBottomSize'=>1,'valign'=>'center'))->addText('DOSSIER CONFIDENZIALE',array('bold'=>'Bold'),array('align'=>'center'));
$table->addCell(1500,array('borderSize'=>1,'valign'=>'center'))->addText('20.07.2012',null,array('align'=>'center'));
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('test2.docx');
?>
