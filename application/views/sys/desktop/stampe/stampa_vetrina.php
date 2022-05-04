<?php

include_once 'Header.php';

// New Word Document
$PHPWord = new \PhpOffice\PhpWord\PhpWord();


//Set default font
$PHPWord->setDefaultFontName('Arial');
$PHPWord->setDefaultFontSize(11);

// New portrait section
$sectionStyle = array('orientation' => null,
                      'marginLeft' => 453,
                      'marginRight' => 453,
                      'marginTop' => 0,
                      'marginBottom' => 2000,
                      'footerHeight' => 0
                      );

$distanziatoreFontStyle=array('size'=>3,'color'=>'#FFFFFF');
$distanziatoreParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$distanziatore2FontStyle=array('size'=>9,'color'=>'#FFFFFF');
$distanziatore2ParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$distanziatore3FontStyle=array('size'=>7,'color'=>'#FFFFFF');
$distanziatore3ParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$distanziatore4FontStyle=array('size'=>24,'color'=>'#FFFFFF');
$distanziatore4ParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$tableFontStyle=array();
$table_label_FontStyle=array('bold'=>true,'size'=>10);
$table_value_FontStyle=array();
$tableParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);
$tableTextRunParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$cellStyle=array('valign'=>'center');
$cellFontStyle=array();
$cellParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

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

//INIZIO HEADER
$header = $section->createHeader();

// Create table element
$table=$header->addTable(array('cellMarginLeft'=>80,'cellMarginTop'=>180));

// Add a row as normal.
$table->addRow(400);

$cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
$cell->addImage($header_logo, array('width'=>600,'height'=>150,'align'=>'center'));
//FINE HEADER

$table=$section->addTable();

//PAESE
$table->addRow(400);
$table->addCell(10900)->addText(strtoupper($paese),array('size'=>30,'bold'=>true,'underline'=>'Underline','color'=>'#4A442A'),array('align'=>'center'));

//TITOLO
$table->addRow(400);
$table->addCell(10900,array('valign'=>'center'))->addText(strtoupper($titolo),array('size'=>30,'bold'=>true,'underline'=>'Underline','color'=>'#4A442A'),array('align'=>'center'));

$section->addTextBreak();

//FOTO COPERTINA
$table=$section->addTable();

if($foto_copertina!=null)
{
    $table->addRow(5250,array("exactHeight" => true));
    //$cell = $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A','spacing'=>0,'spaceAfter=>'=>0));
    $cell = $table->addCell(1900,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
    $cell = $table->addCell(7950,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A','spacing'=>0,'spaceAfter=>'=>0));
    $cell->addImage($foto_copertina,array('width'=>530,'height'=>350,'align'=>'center'));
}

$section->addTextBreak();

//PREZZO
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('prezzo CHF '.strtoupper($prezzo),array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));


if($foto_esterni!=null)
{
    $table=$section->addTable();
    foreach ($foto_esterni as $key => $foto_esterno) {
        if($key<2)
        {
            if ( $key & 1 ) { 

            } else {
                //righe pari va a capo creando una riga
                $table->addRow(3000,array("exactHeight" => true));
            } 
            $cell = $table->addCell(450,array('valign'=>'center'));
            $cell = $table->addCell(4800,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A'));
            $cell->addImage($foto_esterno,array('width'=>320,'height'=>200,'align'=>'center'));
        }
    }
   
}




//INIZIO FOOTER
$footer = $section->createFooter();


// Create table element
$table=$footer->addTable(array('cellMarginLeft'=>80,'cellMarginTop'=>180));
$table->addRow(400);
$cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
$cell->addImage("assets/images/DimensioneImmobilare_footer_prospetto.png", array('width'=>700,'height'=>100,'align'=>'center'));
//FINE FOOTER




// Save file
$filename='vetrina';
//$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
$writers = array('Word2007' => 'docx');
echo write($PHPWord, $filename, $writers);


