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

$header = $section->createHeader();
$header->addPreserveText('CURRICULUM VITAE',$Stile_Intestazione);
//$header->addText($candidato['dati']['cognome'],array('size'=>18));

$titolo_sezione_font=array('bold'=>true);
$titolo_sezione_par=array('align'=>'left');

//$section->addText('CURRICULUM VITAE',array('size'=>18));
//$section->addTextBreak(2);
$section->addTextBreak(1);
$section->addText('Dati personali',$titolo_sezione_font,$titolo_sezione_par);

$table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>0,'cellMarginBottom'=>0,'borderColor'=>'FFFFFF'));

$cellRowSpan = array('vMerge' => 'restart');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2);

$table->addRow(400);

$cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Nome:');
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$textrun=$cell->createTextRun();
$textrun->addText(utf8_decode(replace1252($candidato['dati']['nome'])) );

$cell = $table->addCell(1, $cellRowSpan)->addImage('..//'.$foto, array('width'=>125,'height'=>125,'align'=>'left'));
//Se $mostrafotocv=='no' || $mostrafotocv=='' || $mostrafotocv===NULL ¦¦ !file_exists la foto è vuota !!

$table->addRow(10);
$cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Cognome:');
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$textrun=$cell->createTextRun();
$textrun->addText(utf8_decode(replace1252($candidato['dati']['cognome'])));
$cell = $table->addCell(null, $cellRowContinue);


$table->addRow(10);
$cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Data di nascita:');
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$textrun=$cell->createTextRun();
$textrun->addText($candidato['dati']['datanascita']);
$cell = $table->addCell(null, $cellRowContinue);

if($candidato['dati']['attinenza']!=''){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Attinenza:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['attinenza'])));   
    $cell = $table->addCell(null, $cellRowContinue);
}

$table->addRow(10);
$cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText(conv_text('Nazionalità:'));
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$textrun=$cell->createTextRun();
if($candidato['dati']['nazionalita']!='Altro'){    
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['nazionalita'])));
} else{
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['altranezionalita'])));
}
$cell = $table->addCell(null, $cellRowContinue);

//$cell = $table->addCell(1000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
//$cell->addText('');

if($candidato['dati']['permesso']!=''){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Permesso:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['permesso'])));   
    $cell = $table->addCell(null, $cellRowContinue);
}

if($candidato['dati']['statocivile']!=''){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Stato civile:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['statocivile'])));   
    $cell = $table->addCell(null, $cellRowContinue);
}

$table->addRow(10);
$cell = $table->addCell(5500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Indirizzo:');
$cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText(utf8_decode(replace1252($candidato['dati']['via'] . " " . $candidato['dati']['ncivico'])));  
$cell->addText(utf8_decode(replace1252($candidato['dati']['nap'] . " " . $candidato['dati']['localita'])));   
//$textrun=$cell->createTextRun();
//$textrun->addText(utf8_decode(replace1252($candidato['dati']['via'] . " " . $candidato['dati']['ncivico'])));  
//$textrun->addText(utf8_decode(replace1252($candidato['dati']['nap'] . " " . $candidato['dati']['localita'])));   
$cell = $table->addCell(null, $cellRowContinue);

if($candidato['dati']['cellulare']!=''){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Cellulare:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['cellulare'])));   
    $cell = $table->addCell(null, $cellRowContinue);
} elseif($candidato['dati']['telefonofisso']!=''){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Telefono:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['telefonofisso'])));   
    $cell = $table->addCell(null, $cellRowContinue);
}

$table->addRow(10);
$cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('E-mail:');
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$textrun=$cell->createTextRun();
$textrun->addText(utf8_decode(replace1252($candidato['dati']['email'] )));  
$cell = $table->addCell(null, $cellRowContinue);

if ($candidato['dati']['paginaweb']<>""){
    $table->addRow(10);
    $cell = $table->addCell(5500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Pagina web:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['paginaweb'] )));  
    $cell = $table->addCell(null, $cellRowContinue); 
}

if ($candidato['dati']['linkedin']<>""){
    $table->addRow(10);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('LinkedIn:');
    $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['linkedin'] )));  
    $cell = $table->addCell(null, $cellRowContinue); 
}

$patente="";
$altrepatenti="";

if ($candidato['dati']['altrepatenti']<>"")
    $altrepatenti="Altre Patenti: " .$candidato['dati']['altrepatenti'];

$table->addRow(10);
$cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));


if($candidato['dati']['automunito']=='Si'){    
    if ($candidato['dati']['sesso']=='Maschile'){        
        //$cell->addText('Automunito'.chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti);
        
        $cell->addText('Automunito');
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        $textrun->addText(utf8_decode(replace1252($patente.$altrepatenti))); 
    }    
    else{
        //$cell->addText('Automunita'.chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti);
        $cell->addText('Automunita');
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        $textrun->addText(utf8_decode(replace1252($patente.$altrepatenti))); 
    }     
} else {
        //$cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti); Problema se NON c'e' Automunito
        $cell->addText('');
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        $textrun->addText(utf8_decode(replace1252($patente.$altrepatenti))); 
}
$cell = $table->addCell(null, $cellRowContinue); 

/*
$cell = $table->addCell(9800,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));

//Invece degli spazi aggiunti approssimativamente --> meglio usare TAB

//$cell->addText('Nome:'.chr(9).chr(9).chr(9).chr(9) . utf8_decode(replace1252($candidato['dati']['nome'])));
$cell->addText('Cognome:'.chr(9).chr(9).chr(9).chr(9) . utf8_decode(replace1252($candidato['dati']['cognome'])));
$cell->addText('Data di nascita:'.chr(9).chr(9).chr(9) . utf8_decode(replace1252($candidato['dati']['datanascita'])));
if($candidato['dati']['attinenza']!=''){
    $cell->addText('Attinenza:'.chr(9).chr(9).chr(9).chr(9) . utf8_decode(replace1252($candidato['dati']['attinenza'])));
}
if ($candidato['dati']['mostrafotocv']=='Si'){
    $cell = $table->addCell(2000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
    //$cell->addImage("assets/images/logo_about.png", array('width'=>150,'height'=>150,'align'=>'left'));
    $cell->addImage('..//'.$foto, array('width'=>125,'height'=>125,'align'=>'left'));
}
$table->addRow(250);

$cell = $table->addCell(10800,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));

if($candidato['dati']['nazionalita']!='Altro'){    
    $cell->addText(conv_text('Nazionalità:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['nazionalita']));
} else{
    $cell->addText(conv_text('Nazionalità:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['altranezionalita']));
}

if ($candidato['dati']['permesso']<>''){
    $cell->addText(conv_text('Permesso:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['permesso']));
}

if($candidato['dati']['statocivile']!=''){
    $cell->addText('Stato civile:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['statocivile']);
}

$cell->addText('Indirizzo:'.chr(9).chr(9).chr(9).chr(9) . utf8_decode(replace1252($candidato['dati']['via'] . " " . $candidato['dati']['ncivico'])));
$cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['nap'] . " " . $candidato['dati']['localita']);

if ($candidato['dati']['cellulare']!="")
    $cell->addText('Telefono:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['cellulare']);  
else
    $cell->addText('Telefono:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['telefonofisso']);

$cell->addText('E-mail:'.chr(9).chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['email'] ); 

if ($candidato['dati']['paginaweb']<>""){
    $cell->addText('Pagina web:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['paginaweb'] ); 
}

$cell = $table->addCell(2000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));

//$table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    
$table->addRow(250);

$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));

if ($candidato['dati']['linkedin']<>""){
    //$cell->addText('LinkedIn:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['linkedin'] );
    $cell->addText('LinkedIn:');
    /*
    $table->addRow(10);
    $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('LinkedIn:');
    $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['linkedin'])));
}
$cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10));
    $cell->addText($candidato['dati']['linkedin']);

$patente="";
$altrepatenti="";
//if ($candidato['dati']['tipopatente']<>"")
//    $patente="Patente: " .$candidato['dati']['tipopatente']. " ";
if ($candidato['dati']['altrepatenti']<>"")
    $altrepatenti="Altre Patenti: " .$candidato['dati']['altrepatenti'];

if($candidato['dati']['automunito']=='Si'){    
    if ($candidato['dati']['sesso']=='Maschile'){
        //$cell->addText('Automunito:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['automunito']);
        $cell->addText('Automunito'.chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti);
    }    
    else{
        //$cell->addText('Automunita:'.chr(9).chr(9).chr(9).chr(9) . $candidato['dati']['automunito']);
        $cell->addText('Automunita'.chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti);
    }     
} else {
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).$patente.$altrepatenti);
}
*/
//Per gestire i caratteri accentati: 
//$test = "è ò ù";
//$cell->addText('Accenti:' . utf8_decode($test));
//if($candidato['dati']['']!=''){

$recordNumber = count($candidato['studi']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Formazione scolastica',$titolo_sezione_font,$titolo_sezione_par);

    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

    foreach ($candidato['studi'] as $key => $studio) {
        
        $table->addRow(10);
        $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($studio['annoinizio'].' - '.trim($studio['annofine']));
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();

        $pos = strpos($studio['nomescuolaelementare'], "Scuole Elementari");
        if ($studio['estero']!="Si"){            
            if ($studio['tiposcuola']=="Scuole Elementari"){ 
                if ($pos === false) 
                    $textrun->addText("Scuole elementari " );
            }             
            $textrun->addText(utf8_decode(replace1252($studio['nomescuolaelementare']. $studio['nomescuolamedia']. $studio['nomescuolaprofessionale']. $studio['nomescuolasuperiore']. $studio['nomeuniversita'])));
            if ($studio['paese']!="") $textrun->addText(" " . $studio['paese']);
        } else {            
           $textrun->addText(utf8_decode(replace1252($studio['tiposcuola'] . " " . $studio['nomescuola'] . " " . $studio['paese'])));        
        }         
    }
}

$recordNumber = count($candidato['soggiornilinguistici']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Soggiorni linguistici',$titolo_sezione_font,$titolo_sezione_par);
    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
	
    foreach ($candidato['soggiornilinguistici'] as $key => $soggiornolinguistico) {
        
        $table->addRow(10);
        $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($soggiornolinguistico['meseinizio'].".".$soggiornolinguistico['annoinizio'].' - '.$soggiornolinguistico['mesefine'].".".$soggiornolinguistico['annofine']);
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        
        if ($soggiornolinguistico['citta']!="")
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['citta'])) . ", ");
        if ($soggiornolinguistico['nazione']!="Altra")
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['nazione'])) . " ");
        else
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['altranazione'])) . " ");
        if ($soggiornolinguistico['diploma']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['diploma'])),array('bold'=>true));        
        if ($soggiornolinguistico['esameen']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameen'])));
        if ($soggiornolinguistico['esameaus']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameaus'])));
        if ($soggiornolinguistico['esameusa']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameusa'])));
        if ($soggiornolinguistico['esamede']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamede'])));
        if ($soggiornolinguistico['esamefr']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamefr'])));
        if ($soggiornolinguistico['esamees']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamees'])));        
        if ($soggiornolinguistico['altroesame']!="")
            $textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['altroesame']))); 
    }   
}

$recordNumber = count($candidato['esperienzeprofessionali']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Esperienze professionali',$titolo_sezione_font,$titolo_sezione_par);
	
    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
	
    foreach ($candidato['esperienzeprofessionali'] as $key => $esperienzaprofessionale) {
        
        $table->addRow(10);
        $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $dateEsperienze=$esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio'];
                //$cell->addText($esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio'].' - ');        
        if ($esperienzaprofessionale['mesefine']=="" && $esperienzaprofessionale['annofine']=="") 
            if ($esperienzaprofessionale['adoggi']=="Si") 
                $dateEsperienze=$dateEsperienze.' - ad oggi';
            else                    
                $dateEsperienze=$dateEsperienze.chr(9);
            //$cell->addText(chr(9)); 
        else {
            //$cell->addText($esperienzaprofessionale['mesefine']);    //.".".$esperienzaprofessionale['annofine']); 
            $dateEsperienze=$dateEsperienze.' - '.$esperienzaprofessionale['mesefine'];
            if ($esperienzaprofessionale['annofine']<>"")
                $dateEsperienze=$dateEsperienze.".".$esperienzaprofessionale['annofine'];
                //$cell->addText(".".$esperienzaprofessionale['annofine']);
        }
        
        $cell->addText($dateEsperienze);
        
        $cell = $table->addCell(7000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        
        //$textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['azienda'] . " - ". $esperienzaprofessionale['carica'])),array('bold'=>true));
        $textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['azienda'] . (strlen($esperienzaprofessionale['carica'])>0 ? " - ":"") . $esperienzaprofessionale['carica'])),array('bold'=>true));
        
        if ($esperienzaprofessionale['tipolavoro']<>"Indeterminato") 
            $textrun->addText(" (" . $esperienzaprofessionale['tipolavoro']. ") ");
        //$textrun=$cell->createTextRun();
        
        $exploded = explode("\n", $esperienzaprofessionale['parolechiave']);
        foreach ($exploded as $part) {
          //$cell->addText(utf8_decode(replace1252($part)), $cellFontStyle, $cellParagraphStyle);
          //NON riesco a mostrare il punto elenco • : viene convertito in ? --> lo sostituisco con -
          //$cell->addText(html_entity_decode(htmlentities(utf8_decode($part))), $cellFontStyle, $cellParagraphStyle);
          //$cell->addText(str_replace("&bull;","&#8226;",(htmlentities($part))), $cellFontStyle, $cellParagraphStyle);
          //$cell->addText(str_replace("&bull;","•",(htmlentities($part))), $cellFontStyle, $cellParagraphStyle);
          //$cell->addText(replace1252((str_replace("&bull;",chr(149),mb_convert_encoding($part, 'HTML-ENTITIES', 'UTF-8')))), $cellFontStyle, $cellParagraphStyle);
          //$cell->addText(((mb_convert_encoding($part, "UTF-8", mb_detect_encoding($part, "UTF-8, ISO-8859-1, ISO-8859-15", true)))), $cellFontStyle, $cellParagraphStyle);
          //$cell->addText(utf8_decode(replace1252(str_replace("&bull;","°",(htmlentities($part))))), $cellFontStyle, $cellParagraphStyle);  NON mostra piu' gli apostrofi
          $cell->addText(str_replace("?","-",utf8_decode(replace1252($part))), $cellFontStyle, $cellParagraphStyle);
        } 
        $cell->addText("");
        
        //$textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['parolechiave'])));
        //$cell->addText(htmlspecialchars($esperienzaprofessionale['parolechiave']), $cellFontStyle, $cellParagraphStyle);

    }
}

$recordNumber = count($candidato['conoscenzelinguistiche']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Conoscenze linguistiche',$titolo_sezione_font,$titolo_sezione_par);

    $table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>180));

    foreach ($candidato['conoscenzelinguistiche'] as $key => $conoscenzalinguistica) {
        $textrun=$section->createTextRun();
        //if ($conoscenzalinguistica['conoscenzaorale']!="Nessuna")
        if ($conoscenzalinguistica['lingua']=="Altro"){
            $textrun->addText($conoscenzalinguistica['linguaaltro']);
        }
        else{
            $textrun->addText($conoscenzalinguistica['lingua']);
        }
        
        if (strlen($conoscenzalinguistica['lingua'])<=5)
            $textrun->addText(chr(9));
            
        $textrun->addText(chr(9).chr(9).chr(9));
        if ($conoscenzalinguistica['conoscenzaorale']=="Madrelingua")
            $textrun->addText("Madrelingua");
        else {
            if ($conoscenzalinguistica['conoscenzaorale']!="Nessuna")
                $textrun->addText("Orale: " . $conoscenzalinguistica['conoscenzaorale'] );
            if ($conoscenzalinguistica['conoscenzascritta']!="Nessuna")
                $textrun->addText(" / " . "Scritto: " . $conoscenzalinguistica['conoscenzascritta']);            
        }
    }
}

$recordNumber = count($candidato['conoscenzeinformatiche']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Conoscenze informatiche',$titolo_sezione_font,$titolo_sezione_par);

    $table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>180));

    foreach ($candidato['conoscenzeinformatiche'] as $key => $conoscenzainformatica) {        
        $table->addRow(10);
        $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText(substr($conoscenzainformatica['livelloconoscenzeit'],3));
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();        
        //$textrun=$section->createTextRun();        
        //$textrun->addText(substr($conoscenzainformatica['livelloconoscenzeit'],3));
        //if ($conoscenzainformatica['livelloconoscenzeit']=="01.Base" or $conoscenzainformatica['livelloconoscenzeit']=="03.Buono")
        //    $textrun->addText(chr(9));
        //$textrun->addText(chr(9).chr(9).chr(9).chr(9));
        if ($conoscenzainformatica['conoscenzeit']!="Altro")
            $textrun->addText($conoscenzainformatica['conoscenzeit']);
        else
            $textrun->addText($conoscenzainformatica['dettaglio']);  
    }
}

$table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

if($candidato['dati']['hobby']!=''){
    $table->addRow(60);
    $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('Hobby e Interessi',$titolo_sezione_font,$titolo_sezione_par);
    $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['hobby'])));    
}

if($candidato['dati']['disponibiledal']!='' or $candidato['dati']['disponibilitaimmediata']=='Si'){
    
    $table->addRow(60);
    $cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText(conv_text('Disponibilità'),$titolo_sezione_font,$titolo_sezione_par);
    $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $textrun=$cell->createTextRun();
    
    if($candidato['dati']['disponibilitaimmediata']=='Si'){
        $textrun->addText('Immediata');
    }else{
        $textrun->addText('Dal ' . $candidato['dati']['disponibiledal'] );
        //if($candidato['dati']['disponibileal']!=null){
        //    $textrun->addText(' al ' . $candidato['dati']['disponibileal'] );
        //}
    }        
}

$table->addRow(60);
$cell = $table->addCell(3000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addTextBreak(1);
$cell->addText('Referenze',$titolo_sezione_font,$titolo_sezione_par);
$cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addTextBreak(1);
$textrun=$cell->createTextRun();
$textrun->addText('Su richiesta');    

//Add Footer
$footer = $section->createFooter();
$footerFontStyle=array('color'=>'7F312F','bold'=>true);
$footerParagraphStyle=array('align'=>'center','spacing'=>0,'spaceAfter'=>0);
//$footer->addPreserveText('Associazione 18-24              Pagina {PAGE} di {NUMPAGES}',$footerFontStyle,$footerParagraphStyle);

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename='curriculum_1.docx';
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
