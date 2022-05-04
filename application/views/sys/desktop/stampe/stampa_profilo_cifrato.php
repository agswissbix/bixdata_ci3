<?php

function conv_text($texto)
{
    
    //return iconv('UTF-8', 'windows-1252//IGNORE',$texto);
        return html_entity_decode(@iconv('UTF-8', "windows-1252//IGNORE",$texto));
}
//dichiarazione array contenente dati
$dati=$data['dati'];
$foto_path=$dati['foto'];

require_once 'phpword/PHPWord.php';
// New Word Document
$PHPWord = new PHPWord();

//Set default font
$PHPWord->setDefaultFontName('Calibri');
$PHPWord->setDefaultFontSize(11);

// New portrait section
$sectionStyle = array('orientation' => null,
                      'marginLeft' => 1100,
                      'marginRight' => 1100,
                      'marginBottom' => 0,
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

// Add header
$header = $section->createHeader();

// Create table element
$table=$header->addTable(array('cellMarginLeft'=>80,'cellMarginTop'=>180));

// Add a row as normal.
$table->addRow(400);

//  The important thing to note here is that you when you create the cell 
// that you want to have the multi-line on, is that you don't add text to it.
$cell = $table->addCell(1700,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
//$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$cell->addImage("assets/images/logo_ww.png", array('width'=>90,'height'=>90,'align'=>'center'));

$cell = $table->addCell(7180,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','bgColor'=>'EFEFEF','spacing'=>0,'spaceAfter'=>0));


// Add as many text to the cell as you want lines.  
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
//dossier confidenziale
$textrun->addText("Dossier Confidenziale ID: ", array('bold'=>true,'size'=>10), array('spacing'=>0,'spaceAfter'=>0));
$textrun->addText(strtoupper($dati['intestazione']['idDossier']),array(), array());

//cognome
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText("Cognome: ", array('bold'=>true,'size'=>10), array('spacing'=>0,'spaceAfter'=>0));
$textrun->addText(strtoupper($dati['intestazione']['cognome']),array(), array());

//nome
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText("Nome: ", array('bold'=>true,'size'=>10), array('spacing'=>0,'spaceAfter'=>0));
$textrun->addText($dati['intestazione']['nome'],array(), array());

//qualifica
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText("Qualifica: ", array('bold'=>true,'size'=>10), array('spacing'=>0,'spaceAfter'=>0));
$textrun->addText(strtoupper($dati['intestazione']['qualifica']),array(), array());

//data
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText("Data: ", array('bold'=>true,'size'=>10), array('spacing'=>0,'spaceAfter'=>0));
$textrun->addText(strtoupper($dati['intestazione']['data']),array(), array());

$cell = $table->addCell(2500,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','bgColor'=>'DEDFD8','spacing'=>0,'spaceAfter=>'=>0));
if(($foto_path!="")&&(strpos($foto_path,"JDocServer") !== false))
{
$cell->addImage("../".$foto_path,array('width'=>100,'height'=>100,'align'=>'center','valign'=>'center'));
}


//INFO GENERALI inizio


$section->addTextBreak();
$section->addTextBreak();
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//INFO GNEERALI
$table->addCell(10900,$titolo_sezione)->addText('INFO GENERALI',array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);


$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//DATI ANAGRAFICI
$table->addCell(2100,$titolo_sottosezione)->addText('Dati anagrafici',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable('table');
$table->addRow(400);
//anno di nascita
$cell=$table->addCell(3633,array('valign'=>'center'));
//$cell->addText('Anno di nascita: ',array(), array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0));

$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText('Anno di nascita: ',$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['annonascita'],$table_value_FontStyle, $tableParagraphStyle);

//sesso
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText('Sesso: ',$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['sesso'],$tableFontStyle, $tableParagraphStyle);
//nazionalità
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Nazionalità: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['nazionalita'],$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//stato civile
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Stato civile: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['statocivile'],$table_value_FontStyle, $tableParagraphStyle);

//patente
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Patente: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['patente'],$table_value_FontStyle, $tableParagraphStyle);

//auto
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Auto: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['auto'],$table_value_FontStyle, $tableParagraphStyle);


$table->addRow(400);
//permesso
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Permesso: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['permesso'],$table_value_FontStyle, $tableParagraphStyle);

//domicilio
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Domicilio: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['domicilio'],$table_value_FontStyle, $tableParagraphStyle);

$cell=$table->addCell(3633);

$table->addRow(400);
//N. figli
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('N. figli: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['numfigli'],$table_value_FontStyle, $tableParagraphStyle);

//anno di nascita figli
$cell=$table->addCell(3633,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Anno di nascita figli: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['datianagrafici']['annonascitafigli'],$table_value_FontStyle, $tableParagraphStyle);

$cell=$table->addCell(3633);

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//AREA PERSONALE
$table->addCell(2100,$titolo_sottosezione)->addText('Area personale',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable('table');
$table->addRow(400);
//presenza
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Presenza: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areapersonale']['presenza'],$table_value_FontStyle, $tableParagraphStyle);


$table->addRow(400);
//abbigliamento
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Abbigliamento: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areapersonale']['abbigliamento'],$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//proprietà di linguaggio
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Proprietà di linguaggio: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areapersonale']['proprietalinguaggio'],$table_value_FontStyle, $tableParagraphStyle);


$table->addRow(400);
//indole
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Indole: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areapersonale']['indole'],$table_value_FontStyle, $tableParagraphStyle);

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//AREA PROFESSIONALE
$table->addCell(2100,$titolo_sottosezione)->addText('Area professionale',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable('table');
$table->addRow(400);
//motivazioni al cambiamento
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Motivazioni al cambiamento: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areaprofessionale']['motivazionicambiamento'],$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//motivazioni alla posizione
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Motivazioni alla posizione: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areaprofessionale']['motivazioniposizione'],$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//aspettative
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Aspettative: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText($dati['areaprofessionale']['aspettative'],$table_value_FontStyle, $tableParagraphStyle);


$table->addRow(400);
//flessibilità orario
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Flessibilità orario: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['areaprofessionale']['flexorario']),$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//flessibilità salario
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Flessibilità salario: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['areaprofessionale']['flexsalario']),$table_value_FontStyle, $tableParagraphStyle);

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//TERMINI
$table->addCell(2100,$titolo_sottosezione)->addText('Termini',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable('table');
$table->addRow(400);

//disponibilità
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Disponibilità: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['termini']['disponibilita']),$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//giorni di preavviso
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Giorni di preavviso: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['termini']['giornidipreavviso']),$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//parametro salariale
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Parametro salariale: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['termini']['parametrosalariale']),$table_value_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//salario desiderato
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Salario desiderato: '),$table_label_FontStyle, $tableParagraphStyle);
$textrun->addText(conv_text($dati['termini']['salariodesiderato']),$table_value_FontStyle, $tableParagraphStyle);

//INFO GENERALI fine
//
//Page Break
$section->addPageBreak();

//FORMAZIONE inizio
//$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//FORMAZIONE
$table->addCell(10900,$titolo_sezione)->addText('FORMAZIONE',array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);


//-------- INSERIRE QUESTO PEZZO DI CODICE IN UN CICLO PER INSERIRE TUTTE LE SCUOLE SEGUITE----------------------//

foreach($dati['formazione'] as $formazione){
    

    $table=$section->addTable('table');
    $table->addRow(400);
    //titolo
    $cell=$table->addCell(7850,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Titolo: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($formazione['titolo']),$table_value_FontStyle, $tableParagraphStyle);
    
    //anno
    $cell=$table->addCell(3050,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Anno: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($formazione['anno']),$table_value_FontStyle, $tableParagraphStyle);


    $table->addRow(400);
    $table->addCell(10900,array_merge($cellStyle,array('bgColor'=>'EFEFEF','gridSpan'=>2)))->addText(strtoupper($formazione['corso']),array('bold'=>true), $tableParagraphStyle);

    $table->addRow(400);
    //istituto
    $cell=$table->addCell(7850,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Istituto: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($formazione['istituto']),$table_value_FontStyle, $tableParagraphStyle);
    
    //luogo
    $cell=$table->addCell(3050,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Luogo: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($formazione['luogo']),$table_value_FontStyle, $tableParagraphStyle);
    
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
}
//------------------------------- FINE CICLO --------------------------------------------------//

//FORMAZIONE fine

$section->addPageBreak();

//LINGUE inizio

//$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//LINGUE
$table->addCell(10900,$titolo_sezione)->addText('LINGUE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//----------------------------- METTERE ANCHE QUESTO PEZZO DI CODICE IN UN CICLO PER OGNI LINGUA CONOSCIUTA DAL CANDIDATO
foreach($dati['lingue'] as $lingua){
    
    $table=$section->addTable('table');
    $table->addRow(400);
    //lingua
    $table->addCell(3633, array_merge($cellStyle,array('bgColor'=>'EFEFEF')))->addText(strtoupper($lingua['lingua']),array('bold'=>true),  array_merge($tableParagraphStyle,array('align'=>'center')));
    //diplomi conseguiti
    $cell=$table->addCell(7267,array_merge($cellStyle,array('gridSpan'=>2)));
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Diplomi Conseguiti: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($lingua['diplomiconseguiti']),$table_value_FontStyle, $tableParagraphStyle);
    
    
    $table->addRow(400);
    //Conversazione
    $cell=$table->addCell(3633,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Conversazione: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($lingua['conversazione']),$table_value_FontStyle, $tableParagraphStyle);
    
    //scrittura
    $cell=$table->addCell(3633,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Scrittura: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($lingua['scrittura']),$table_value_FontStyle, $tableParagraphStyle);
    
    //lettura
    $cell=$table->addCell(3633,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Lettura: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($lingua['lettura']),$table_value_FontStyle, $tableParagraphStyle);
    
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

}
//------------------------------- FINE CICLO --------------------------------------------------//

//LINGUE fine

$section->addPageBreak();

//ESPERIENZE PROFESSIONALI inizio

//$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//ESPERIENZE PROFESSIONALI
$table->addCell(10900,$titolo_sezione)->addText('ESPERIENZE PROFESSIONALI',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//------------ ANCHE QUESTO BLOCCO IN UN CICLO PER TUTTE LE ESPERIENZE PROFESSIONALI DEL CANDIDATO
    foreach($dati['esperienzeprofessionali'] as $esperienzaprofessionale){
        $table=$section->addTable('table');
        $table->addRow(400);
        //qualifica
        $table->addCell(8075, array_merge($cellStyle,array('bgColor'=>'EFEFEF')))->addText(strtoupper($esperienzaprofessionale['qualifica']),array('bold'=>true), array_merge($tableParagraphStyle,array('align'=>'center')));
        //Dal al
        $cell=$table->addCell(2825,array_merge($cellStyle,array('bgColor'=>'EFEFEF')));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Dal: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['dal']),$table_value_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text(' Al: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['al']),$table_value_FontStyle, $tableParagraphStyle);


        $table->addRow(400);
        //ragione sociale
        $cell=$table->addCell(8075,array_merge($cellStyle,array('bgColor'=>'EFEFEF')));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Ragione Sociale: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['ragionesociale']),$table_value_FontStyle, $tableParagraphStyle);

        //luogo
        $cell=$table->addCell(2825,array_merge($cellStyle,array('bgColor'=>'EFEFEF')));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Luogo: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['luogo']),$table_value_FontStyle, $tableParagraphStyle);

        $table->addRow(400);
        //settore
        $cell=$table->addCell(8075,$cellStyle);
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Settore: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['settore']),$table_value_FontStyle, $tableParagraphStyle);

        //n. dipendenti
        $cell=$table->addCell(2825,$cellStyle);
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('N. dipendenti: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['Ndipendenti']),$table_value_FontStyle, $tableParagraphStyle);

        //subordinato a
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Subordinato a: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['subordinatoa']),$table_value_FontStyle, $tableParagraphStyle);

        //responsabile di
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Responsabile di: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['responsabiledi']),$table_value_FontStyle, $tableParagraphStyle);

        //competenze
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $exploded = explode("\n", conv_text($esperienzaprofessionale['competenze']));
        $cell->addText('Competenze: ', $table_label_FontStyle, $cellParagraphStyle);
        foreach ($exploded as $part) {
          $cell->addText($part, $cellFontStyle, $cellParagraphStyle);
        }
        
        //carriera
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Carriera: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['carriera']),$table_value_FontStyle, $tableParagraphStyle);
        
        //lingue utilizzate
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Lingue utilizzate: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['lingueutilizzate']),$table_value_FontStyle, $tableParagraphStyle);

        //causa termine
        $table->addRow(400);
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Causa termine: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['causatermine']),$table_value_FontStyle, $tableParagraphStyle);

        $table->addRow(400);
        //referenze
        $cell=$table->addCell(10900,array_merge($cellStyle,array('gridSpan'=>2)));
        $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
        $textrun->addText(conv_text('Referenze: '),$table_label_FontStyle, $tableParagraphStyle);
        $textrun->addText(conv_text($esperienzaprofessionale['referenze']),$table_value_FontStyle, $tableParagraphStyle);

        $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    }
//------------------------------- FINE CICLO --------------------------------------------------//

//ESPERIENZE PROFESSIONALI fine
    
$section->addPageBreak();

//COMPETENZE inizio

//$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//COMPETENZE
$table->addCell(10900,$titolo_sezione)->addText('COMPETENZE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//----------------------------------- CICLO --------------------------------------//
foreach($dati['competenze'] as $competenza){    
    
    $table=$section->addTable('table');
    $table->addRow(400);
    //area
    $table->addCell(8000,array_merge($cellStyle,array('bgColor'=>'EFEFEF')))->addText('Area: '.$competenza['area'], array('bold'=>true), $tableParagraphStyle);
    
    //livello di esperienza
    $cell=$table->addCell(2900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Livello d\'esperienza: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($competenza['livelloesperienza']),$table_value_FontStyle, $tableParagraphStyle);

    
    $table->addRow(400);
    //elenco delle competenze
    $cell=$table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2));
        $exploded = explode("\n", $competenza['elencocompetenze']);
        $cell->addText('Elenco delle competenze: ', $table_label_FontStyle, $cellParagraphStyle);
        foreach ($exploded as $part) {
          $cell->addText($part, $cellFontStyle, $cellParagraphStyle);
        }
    
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
}
//---------------------------------FINE CICLO ----------------------------------------------------//

//COMPETENZE fine

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//COMPETENZE IT inizio
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//COMPETENZE IT
$table->addCell(10900,$titolo_sezione)->addText('COMPETENZE IT',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
//------------------------------------------CICLO------------------------------------------------------------//
foreach($dati['competenzeit'] as $competenzait){    
    $table=$section->addTable('table');
    $table->addRow(400);
    //software
    $cell=$table->addCell(2725,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Software: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($competenzait['software']),$table_value_FontStyle, $tableParagraphStyle);
    
    //livello di esperienza
    $cell=$table->addCell(8175,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Livello di esperienza: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($competenzait['livelloesperienza']),$table_value_FontStyle, $tableParagraphStyle);

    $table->addRow(400);
    //versione
    $cell=$table->addCell(5450,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Versione: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($competenzait['versione']),$table_value_FontStyle, $tableParagraphStyle);

    $cell=$table->addCell(5450,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Anno: '),$table_label_FontStyle, $tableParagraphStyle);
    $textrun->addText(conv_text($competenzait['anno']),$table_value_FontStyle, $tableParagraphStyle);

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
}
//----------------------------------------------------------- FINE CICLO ----------------------------------------//

//COMPETENZE IT fine

$section->addPageBreak();

//VALUTAZIONE inizio
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//VALUTAZIONE
$table->addCell(10900,$titolo_sezione)->addText('VALUTAZIONE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//DATI CLIENTE inizio
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//DATI CLIENTE
$table->addCell(2725,$titolo_sottosezione)->addText('DATI CLIENTE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $daticliente=$dati['daticliente'];
    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);
    $table=$section->addTable('table');
    $table->addRow(400);
    //ragione sociale
    $cell=$table->addCell(10900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Ragione sociale: '),$table_label_FontStyle, $tableParagraphStyle);

    $table->addRow(400);
    //Posizione ricercata
    $cell=$table->addCell(10900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Posizione ricercata: '),$table_label_FontStyle, $tableParagraphStyle);

    $table->addRow(400);
    //tipologia contrattuale
    $cell=$table->addCell(10900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Tipologia contrattuale: '),$table_label_FontStyle, $tableParagraphStyle);

    $table->addRow(400);
    //Luogo di lavoro
    $cell=$table->addCell(10900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Luogo di lavoro: '),$table_label_FontStyle, $tableParagraphStyle);

    $table->addRow(400);
    //retribuzione annua lorda
    $cell=$table->addCell(10900,$cellStyle);
    $textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
    $textrun->addText(conv_text('Retribuzione annua lorda: '),$table_label_FontStyle, $tableParagraphStyle);

//DATI CLIENTE fine
    
$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//ANALISI PROFILO inizio

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//ANALISI PROFILO
$table->addCell(2725,$titolo_sottosezione)->addText('ANALISI PROFILO',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable('table');
$table->addRow(400);
//elementi a favore
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Elementi a favore: '),$table_label_FontStyle, $tableParagraphStyle);

$table->addRow(400);
//elementi di discussione
$cell=$table->addCell(10900,$cellStyle);
$textrun = $cell->createTextRun($tableTextRunParagraphStyle); 
$textrun->addText(conv_text('Elementi di discussione: '),$table_label_FontStyle, $tableParagraphStyle);

//ANALISI PROFILO fine

$section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

//GIUDIZIO inizio
$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow(400);
//GIUDIZIO
$table->addCell(2725,$titolo_sottosezione)->addText('GIUDIZIO',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

$section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

$table=$section->addTable(array('cellMarginLeft'=>80));
$table->addRow();
$table->addCell(10900,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B'));

//GIUDIZIO FINE

//VALUTAZIONE fine

$section->addTextBreak();


//Add Footer
$footer = $section->createFooter();
$footerstyle=array('color'=>'7F312F','bold'=>true);
/*$table=$footer->addTable(array('cellMarginLeft'=>80));
$table->addRow();
$table->addCell(8900,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText('Work & Work SA - via Cantonale 2a, CH-6928 Manno',$footerstyle, array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$table->addCell(1800,array('valign'=>'bottom','spacing'=>0,'spaceAfter'=>0))->addPreserveText('Pagina {PAGE} di {NUMPAGES}.',array('color'=>'B50012'),array('spacing'=>0,'spaceAfter'=>0));*/

//$footer->addText('Work & Work SA - via Cantonale 2a, CH-6928 Manno',$footerstyle, array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
$footer->addPreserveText('Work & Work SA - via Cantonale 2a, CH-6928 Manno                                      Pagina {PAGE} di {NUMPAGES}.',array('color'=>'7F312F','bold'=>true),array('align'=>'right','spacing'=>0,'spaceAfter'=>0));
// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename=$dati['intestazione']['idDossier'].'_'.substr($dati['datianagrafici']['sesso'], 0, 1).'_'.$dati['intestazione']['cognome'].'_'.date("m-d-Y").'.docx';
$filename= str_replace(" ", "", $filename);
$filename= utf8_decode($filename);
$filename= utf8_encode($filename);
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$dati['userid'])){
    mkdir("./stampe/".$dati['userid']);
}
$objWriter->save('stampe/'.$dati['userid'].'/'.$filename);
echo urlencode($filename);
?>
