<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

//Set default font
$PHPWord->setDefaultFontName('Calibri');
$PHPWord->setDefaultFontSize(11);


// New portrait section
$sectionStyle = array('orientation' => null,
			    'marginLeft' => 400,
			    'marginRight' => 400,
			    'marginTop' => 900,
			    'marginBottom' => 900);
$section = $PHPWord->createSection($sectionStyle);

// Add header
$header = $section->createHeader();
$header->addImage( "logoww.png", array('width'=>'200','height'=>'30','align'=>'center') );

//titolo
$cellStyle=array('bgColor'=>'D3D3D3');
$table = $header->addTable(array('cellMargin'=>50));
$table->addRow(300);
$table->addCell(500,array('borderSize'=>1,'valign'=>'center'))->addText('ID',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(1000,array('borderSize'=>1,'valign'=>'center'))->addText('23776',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(8000,array('bgColor'=>'FFFFFF','borderBottomSize'=>1,'valign'=>'center'))->addText('DOSSIER CONFIDENZIALE',array('bold'=>'Bold'),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(1500,array('borderSize'=>1,'valign'=>'center'))->addText('20.07.2012',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$section->addTextBreak();

//content
$table=$section->addTable();
$table->addRow(300);
$table->addCell(2450,array('borderBottomSize'=>1,'valign'=>'center'))->addText('Nome:',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(3000,array('borderBottomSize'=>1,'valign'=>'center'))->addText('FRANCO',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(2450,array('borderBottomSize'=>1,'valign'=>'center'))->addText('Cognome:',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(3000,array('borderBottomSize'=>1,'valign'=>'center'))->addText('GIAMBARTOLOMEI',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$table->addRow();
$table->addCell(10900,array('borderBottomSize'=>1,'valign'=>'center','gridSpan'=>4))->addText('RESPONSABILE LOGISTICA-RESPONSABILE COMMERCIALE-RESPONSABILE VENDITE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$section->addTextBreak();

//DATI ANAGRAFICI
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('DATI ANAGRAFICI',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addTextBreak();
    $table=$section->addTable();
    
    $table->addRow(300);
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('Anno Di Nascita:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('1965',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(200)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('Domicilio:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(4160,array('bgColor'=>'D3D3D3'))->addText('Varese',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2180)->addText('Sesso:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('M',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(200)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Stato Civile:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(4160)->addText('Coniugato/a',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('Nazionalita\':',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('I',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(200)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('N. Figli:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(4160,array('bgColor'=>'D3D3D3'))->addText('0',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $table->addRow();
    $table->addCell(2180)->addText('Permesso:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('N',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(200)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Anno Di Nascita Figli:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(4160)->addText('',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('Patente:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('B',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(200)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3'))->addText('Autovettura:',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(4160,array('bgColor'=>'D3D3D3'))->addText('Si',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));    
}

$section->addTextBreak();
//FORMAZIONE
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('FORMAZIONE',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    $table=$section->addTable();
    
    $table->addRow(300);
    $table->addCell(2180)->addText('Anno',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Titolo',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Corso',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Istituto:',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180)->addText('Luogo',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2180,array('bgColor'=>'D3D3D3','spacing'=>0,'spaceAfter'=>0))->addText('',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3','spacing'=>0,'spaceAfter'=>0))->addText('',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3','spacing'=>0,'spaceAfter'=>0))->addText('',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3','spacing'=>0,'spaceAfter'=>0))->addText('',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2180,array('bgColor'=>'D3D3D3','spacing'=>0,'spaceAfter'=>0))->addText('',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));    
}

$section->addTextBreak();

//LINGUE
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('LINGUE',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center'))->addText('Lingua',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center'))->addText('Parlato',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center'))->addText('Letto',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center'))->addText('Scritto',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('Italiano',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('M',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('M',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('M',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2725,array('valign'=>'center'))->addText('Inglese',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('O',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('O',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('O',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('Spagnolo',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2725,array('valign'=>'center'))->addText('Francese',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('S',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(2725,array('valign'=>'center'))->addText('M (Madrelingua)',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('O (Ottimo)',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('B (Buono)',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('valign'=>'center'))->addText('S (Sufficiente)',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
}

$section->addTextBreak();

//DISPONIBILITA' SALARIO
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('DISPONIBILITA\' SALARIO',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak(2);
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(5370,array('borderBottomSize'=>1,'borderTopSize'=>1,'valign'=>'center'))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(150,array('valign'=>'center'))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(5380,array('borderBottomSize'=>1,'borderTopSize'=>1,'valign'=>'center'))->addText('Salario Desiderato CHF Lordi Annui',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
}

$section->addTextBreak();

//AREA LAVORO
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('AREA LAVORO',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(4250,array('borderBottomSize'=>1,'borderTopSize'=>1))->addText('Oggetto',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('borderBottomSize'=>1,'borderTopSize'=>1))->addText('Note',array('bold'=>true),array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3'))->addText('Motivazioni al cambiamento: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3'))->addText('Attualmente senza occupazione',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('valign'=>'center'))->addText('Motivazioni alla posizione: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('valign'=>'center'))->addText('',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3'))->addText('Aspettative: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3'))->addText('',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('valign'=>'center'))->addText('Flessibilita\' orario: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('valign'=>'center'))->addText('',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3','borderBottomSize'=>1))->addText('Flessibilita\' salario: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3','borderBottomSize'=>1))->addText('Note',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
}

$section->addTextBreak();

//AREA PERSONALE
{
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('AREA PERSONALE',array('bold'=>true,'spacing'=>0,'spaceAfter'=>0),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(4250,array('borderBottomSize'=>1,'borderTopSize'=>1))->addText('Oggetto',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('borderBottomSize'=>1,'borderTopSize'=>1))->addText('Note',array('bold'=>true),array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3'))->addText('Presenza: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3'))->addText('Buona',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('valign'=>'center'))->addText('Corporatura: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('valign'=>'center'))->addText('Normale',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3','valign'=>'center'))->addText('Abbigliamento: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3','valign'=>'center'))->addText('Formale, Curato',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250)->addText('Proprieta\' di linguaggio: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650)->addText('Buona',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(4250,array('bgColor'=>'D3D3D3','borderBottomSize'=>1))->addText('Indole: ',array('bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));;
    $table->addCell(6650,array('bgColor'=>'D3D3D3','borderBottomSize'=>1))->addText('Educato, Professionale, Motivato',null,array('align'=>'left','spacing'=>0,'spaceAfter'=>0));
}

$section->addTextBreak();

{
    //VALUTAZIONE
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('VALUTAZIONE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    $table=$section->addTable();
    
    $table->addRow(400);
    $table->addCell(10900,array('bgColor'=>'D3D3D3','borderTopSize'=>1))->addText("Riteniamo il / la...",array('bold'=>true));
    
    $table->addRow();
    $table->addCell(10900)->addText("Elementi a favore:",array('bold'=>true));
    
    $table->addRow();
    $table->addCell(10900,array('borderBottomSize'=>1))->addText("Elementi di discussione:",array('bold'=>true));
}

$section->addTextBreak();

{
    //ESPERIENZE LAVORATIVE
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('ESPERIENZE LAVORATIVE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    $table=$section->addTable();
    
    $table->addRow();
    $table->addCell(2725,array('bgColor'=>'D3D3D3','borderTopSize'=>1,'borderBottomSize'=>1))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3','borderTopSize'=>1,'borderBottomSize'=>1))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3','borderTopSize'=>1,'borderBottomSize'=>1))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3','borderTopSize'=>1,'borderBottomSize'=>1))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'gridSpan'=>2))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1,'gridSpan'=>2))->addText("N. Disp.",null,array('spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2725,array('borderTopSize'=>1,'gridSpan'=>1))->addText("Causa termine:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('gridSpan'=>3))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    

    $table->addRow();
    $table->addCell(2725)->addText("Subordinato a:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725)->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725)->addText("Responsabile di:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725)->addText('',null,array('spacing'=>0,'spaceAfter'=>0)); 
    
    $table->addRow();
    $table->addCell(2725,array('bgColor'=>'D3D3D3','gridSpan'=>1))->addText("Competenze:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3','gridSpan'=>3))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2725,array('gridSpan'=>1))->addText("Carriera:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('gridSpan'=>3))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow();
    $table->addCell(2725,array('borderBottomSize'=>1,'gridSpan'=>1))->addText("Referenze:",array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderBottomSize'=>1,'gridSpan'=>3))->addText('',null,array('spacing'=>0,'spaceAfter'=>0));
}

$section->addTextBreak();

{
    //COMPETENZE
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('COMPETENZE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(3633,array('borderTopSize'=>1,'valign'=>'center'))->addText('Qualifica/Area',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(3633,array('borderTopSize'=>1,'valign'=>'center'))->addText('Oggetto',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(3633,array('borderTopSize'=>1,'valign'=>'center'))->addText('Livello',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    $table=$section->addTable();
    $table->addRow(600);
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('LOGISTICA RESPONSABILE LOGISTICA',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(5900,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('');
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('');
    
    $table->addRow(600);
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center'))->addText('VENDITA RESPONSABILE COMMERCIALE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(5900,array('borderTopSize'=>1,'valign'=>'center'))->addText('');
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center'))->addText('');
    
    $table->addRow(600);
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('VENDITA RESPONSABILE VENDITE',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(5900,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('');
    $table->addCell(2500,array('borderTopSize'=>1,'valign'=>'center','bgColor'=>'D3D3D3'))->addText('');
}

$section->addTextBreak(2);

{
    //COMPETENZE IT
    $table=$section->addTable();
    $table->addRow(350);
    $table->addCell(2700,array('borderSize'=>1,'valign'=>'center'))->addText('COMPETENZE IT',array('bold'=>true),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $section->addTextBreak();
    
    $table=$section->addTable();
    $table->addRow(300);
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1))->addText('Software',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1))->addText('Versione',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1))->addText('Anno',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('borderTopSize'=>1,'borderBottomSize'=>1))->addText('Livello',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $table->addRow(300);
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    $table->addCell(2725,array('bgColor'=>'D3D3D3'))->addText('',null,array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
}

$section->addTextBreak(3);

//$styleCell=array('gridSpan' => 2);
/*$table=$section->addTable();
$table->addRow();
$table->addCell(100)->addText('1');
$table->addCell(100,array('vMerge' => 'fusion'))->addText('2');*/

/*$table->addRow();
$table->addCell(100,array('vMerge' => 'fusion'));
$table->addCell(100)->addText('3');*/
// Add footer
$footer = $section->createFooter();
$footerstyle=array('color'=>'B50012');
$footer->addText('Work & Work SA - Human Resources',$footerstyle, array('align'=>'center'));
$footer->addText('via Cantonale 2a – 6928 Manno (CH)',$footerstyle, array('align'=>'center'));

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('test.docx');
?>