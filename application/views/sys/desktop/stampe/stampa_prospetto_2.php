<?php

/*function conv_text($texto)
{
    
    //return iconv('UTF-8', 'windows-1252//IGNORE',$texto);
        return html_entity_decode(@iconv('UTF-8', "windows-1252//IGNORE",$texto));
}*/
//dichiarazione array contenente dati


require_once 'phpword/PHPWord.php';
// New Word Document
$PHPWord = new PHPWord();

//Set default font
$PHPWord->setDefaultFontName('Calibri');
$PHPWord->setDefaultFontSize(11);

// New portrait section
$sectionStyle = array('orientation' => null,
                      'marginLeft' => 453,
                      'marginRight' => 453,
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
$cell->addImage("assets/images/logo_dimensioneimmobiliare.png", array('width'=>400,'height'=>90,'align'=>'center'));
//FINE HEADER

$section->addTextBreak();
$section->addTextBreak();
$table=$section->addTable();

//PAESE
$table->addRow(400);
$table->addCell(10900)->addText(strtoupper($dati['paese']),array('size'=>30,'bold'=>true,'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE,'color'=>'#4A442A'),array('align'=>'center'));

//TITOLO
$table->addRow(400);
$table->addCell(10900,array('valign'=>'center'))->addText('VILLA MODERNA',array('size'=>30,'bold'=>true,'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE,'color'=>'#4A442A'),array('align'=>'center'));

$section->addTextBreak();

//FOTO COPERTINA
$table=$section->addTable();

if($foto_copertina!=null)
{
    $table->addRow(400);
    //$cell = $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A','spacing'=>0,'spaceAfter=>'=>0));
    $cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
    //$textrun = $cell->createTextRun();
    $cell->addImage($foto_copertina,array('width'=>727,'height'=>400,'align'=>'center'));
}

$section->addTextBreak();

//PREZZO
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('prezzo CHF '.strtoupper($dati['imm_prezzoimmobile'].'.-'),array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));

//foto interni
$section->addPageBreak();

if($foto_interni!=null)
{
    $table=$section->addTable();
    foreach ($foto_interni as $key => $foto_interno) {
        if ( $key & 1 ) { 
            
        } else {
            //righe pari va a capo creando una riga
            $table->addRow(400);
        } 
        
        $cell = $table->addCell(600,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
        $cell = $table->addCell(4600,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
        $cell->addImage($foto_interno,array('width'=>270,'height'=>400,'align'=>'center'));
        
    }
   
}

// foto esterni
$section->addPageBreak();

if($foto_esterni!=null)
{
    $table=$section->addTable();
    foreach ($foto_esterni as $key => $foto_esterno) {
        if ( $key & 1 ) { 
            
        } else {
            //righe pari va a capo creando una riga
            $table->addRow(400);
        } 
        
        $cell = $table->addCell(4500,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
        $cell->addImage($foto_esterno,array('width'=>330,'height'=>400,'align'=>'center'));
        $cell = $table->addCell(500,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
    }
   
}

//descrizione
$section->addPageBreak();
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('DESCRIZIONE',array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));
$section->addText(conv_text('blablabl abalbalba geafa'),array('color'=>'#4A442A'));
$section->addText("Prezzo di vendita CHF ".strtoupper($dati['imm_prezzoimmobile']).".--");
$section->addText(conv_text('Per avere una più ampia visualizzazione dei nostri oggetti potete collegarvi al sito www.dimensioneimmobiliare.ch - 091 922 74 00 '),array('color'=>'#4A442A'));


//scheda
$section->addPageBreak();
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('SCHEDA IMMOBILE',array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));

//piantine

if($foto_piantine!=null)
{
    
    foreach ($foto_piantine as $key => $foto_piantina) {
        $section->addPageBreak();
        $table=$section->addTable();
        $table->addRow(400);
        $table->addCell(10900)->addText($key,array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));
        $table->addRow(400);
        $cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
        $cell->addImage($foto_piantina,array('width'=>727,'height'=>400,'align'=>'center'));
    }
   
}





//INIZIO FOOTER
$footer = $section->createFooter();


// Create table element
$footer->addText('test');
//$table=$footer->addTable(array('cellMarginLeft'=>80,'cellMarginTop'=>180));

//$table->addRow(400);

//$cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
//$cell->addImage("assets/images/DimensioneImmobilare_footer_prospetto.png", array('width'=>700,'height'=>100,'align'=>'center'));
//FINE FOOTER












// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename='prospetto.docx';
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
