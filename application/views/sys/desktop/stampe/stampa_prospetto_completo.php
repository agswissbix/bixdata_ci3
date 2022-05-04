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
$table->addCell(10900)->addText(strtoupper($paese),array('size'=>36,'bold'=>true,'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,'color'=>'#4A442A'),array('align'=>'center'));

//TITOLO
$table->addRow(400);
$table->addCell(10900,array('valign'=>'center'))->addText(strtoupper($titolo),array('size'=>20,'bold'=>true,'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,'color'=>'#4A442A'),array('align'=>'center'));

$section->addTextBreak();

//FOTO COPERTINA
$table=$section->addTable();

if($foto_copertina!=null)
{
    /*$image_size=getimagesize($foto_copertina);
    $image_width=$image_size[0];
    $image_height=$image_size[1];
    $twips_height=$image_size[1]*15;*/
    $table->addRow(7500,array("exactHeight" => true));
    $cell = $table->addCell(null,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A'));
    //$cell = $table->addCell(10900,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
    //$textrun = $cell->createTextRun();
    //$textrun->addImage($foto_copertina,array('width'=>727,'height'=>400,'align'=>'center'));
    $cell->addImage($foto_copertina, array('width' => 727, 'height' => 500));
}

$section->addTextBreak();

//PREZZO
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('prezzo CHF '.strtoupper($prezzo),array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));
$section->addPageBreak();


//foto interni
if($foto_interni!=null)
{
    $table=$section->addTable();
    foreach ($foto_interni as $key => $foto_interno) {
        if ( $key & 1 ) { 
            
        } else {
            //righe pari va a capo creando una riga
            $table->addRow(500,array("exactHeight" => true));
            $table->addCell(450);
            $table->addCell(4800);
            $table->addCell(450);
            $table->addCell(4800);
            $table->addRow(4050,array("exactHeight" => true));
        } 
        
        $cell = $table->addCell(450,array('valign'=>'center','spacing'=>0,'spaceAfter=>'=>0));
        $cell = $table->addCell(4800,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A'));
        $cell->addImage($foto_interno,array('width'=>320,'height'=>270,'align'=>'center'));
        
    }
   
}

// foto esterni
if($foto_esterni!=null)
{
    $section->addPageBreak();
    $table=$section->addTable();
    foreach ($foto_esterni as $key => $foto_esterno) {
        if ( $key & 1 ) { 
            
        } else {
            //righe pari va a capo creando una riga
            $table->addRow(500,array("exactHeight" => true));
            $table->addCell(450);
            $table->addCell(4800);
            $table->addCell(450);
            $table->addCell(4800);
            $table->addRow(4050,array("exactHeight" => true));
        } 
        $cell = $table->addCell(450,array('valign'=>'center'));
        $cell = $table->addCell(4800,array('valign'=>'center','borderSize'=>18,'borderColor'=>'#4A442A'));
        $cell->addImage($foto_esterno,array('width'=>320,'height'=>270,'align'=>'center'));
    }
   
}

//descrizione
$section->addPageBreak();
$table=$section->addTable();
$table->addRow(400);
$table->addCell(10900)->addText('DESCRIZIONE',array('size'=>30,'bold'=>true,'color'=>'#4A442A'),array('align'=>'center'));
$section->addText(conv_text($descrizione),array('color'=>'#4A442A'));
$section->addText("Prezzo di vendita CHF ".strtoupper($prezzo));
$section->addText(conv_text('Per avere una più ampia visualizzazione dei nostri oggetti potete collegarvi al sito www.dimensioneimmobiliare.ch - 091 922 74 00 '),array('color'=>'#4A442A'));


//scheda
$section->addPageBreak();
foreach ($sublabels as $key => $sublabel) {
    if($sublabel['sublabelname']!='')
    {
        if((strpos($sublabel['sublabelname'], 'vendita')===FALSE)&&(strpos($sublabel['sublabelname'], 'Ulteriori')===FALSE))
        {
            if(array_key_exists($sublabel['sublabelname'], $fields_by_sublabel))
            {
                $fields=$fields_by_sublabel[$sublabel['sublabelname']];
                if(count($fields)>0)
                {
                    $table=$section->addTable();
                    $table->addRow(400);
                    $table->addCell(10900,array('valign'=>'bottom'))->addText(strtoupper($sublabel['sublabelname']),array('size'=>14,'bold'=>true,'color'=>'#4A442A'),array('align'=>'left'));

                    $table=$section->addTable();
                    $table->addRow(400);
                    $column=0;
                    foreach ($fields as $key => $field) 
                    {
                        $field_id=$field['fieldid'];
                        $field_desc=$field['description'];
                        $field_value=$field['valuecode'][0]['value'];
                        if(($field_value!='')&&($field_value!=null))
                        {
                            if($column==2)
                            {
                                $table->addRow(400);
                                $column=0;
                            }
                            $table->addCell(3000,array('valign' => 'center','borderSize'=>'5','borderColor'=>'dedede'))->addText($field_desc,array('color'=>'#4A442A'));
                            $table->addCell(6000,array('valign' => 'center','borderSize'=>'5','borderColor'=>'dedede'))->addText($field_value,array('color'=>'#4A442A'));
                            $column++;
                            
                        }
                    }
                }
            }
        }
    }
}

/*$table=$section->addTable();
foreach ($fields as $key => $field) 
{
    $field_id=$field['fieldid'];
    $field_desc=$field['description'];
    $field_value=$field['valuecode'][0]['value'];
    if(($field_value!='')&&($field_value!=null))
    {
        $table->addRow(400);
        $table->addCell(3000)->addText($field_desc,array('color'=>'#4A442A'));
        $table->addCell(6000)->addText($field_value,array('color'=>'#4A442A'));
    }
}    
*/

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
        $cell->addImage($foto_piantina,array('width'=>727,'align'=>'center'));
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
$filename='prospetto_completo';
//$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');
$writers = array('Word2007' => 'docx');
echo write($PHPWord,$userid, $filename, $writers);


