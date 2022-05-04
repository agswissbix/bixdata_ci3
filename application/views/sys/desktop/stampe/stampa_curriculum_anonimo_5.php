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

$Stile_Intestazione=array('size'=>12,'bold'=>true);

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

$titolo_sezione_font=array('bold'=>true);
$titolo_sezione_par=array('align'=>'right');

$table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>0,'cellMarginBottom'=>0,'borderColor'=>'FFFFFF'));
$table->addRow(400);

$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('CV ANONIMO',array('size'=>18,'align'=>'center'));
$cell->addTextBreak(1);

$table->addRow(60);

$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addTextBreak(1);
$cell->addText('DATI PERSONALI',$titolo_sezione_font,$titolo_sezione_par);

$table->addRow(30);
$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Nr. Riferimento',array('size'=>12),array('align'=>'right'));

$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText($candidato['dati']['rif']);

if($candidato['dati']['attinenza']!=''){
    $table->addRow(30);
    $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Attinenza',array('size'=>12),array('align'=>'right'));
    
    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText(utf8_decode(replace1252($candidato['dati']['attinenza'])));
}

$table->addRow(30);

$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText(utf8_decode(replace1252('Domicilio')),array('size'=>12),array('align'=>'right'));

$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

/*
if($candidato['dati']['nazionalita']!='Altro'){    
    $cell->addText(utf8_decode(replace1252($candidato['dati']['nazionalita'])));
} else{
    $cell->addText(utf8_decode(replace1252($candidato['dati']['altranezionalita'])));
}*/
$cell->addText(utf8_decode(replace1252($candidato['dati']['localita'])));

if ($candidato['dati']['permesso']<>''){
    $table->addRow(30);

    $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Permesso',array('size'=>12),array('align'=>'right'));

    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText(utf8_decode($candidato['dati']['permesso']));
}

$table->addRow(30);

$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText('Data di nascita',array('size'=>12),array('align'=>'right'));

$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText(utf8_decode($candidato['dati']['datanascita']));

if($candidato['dati']['statocivile']!=''){
    $table->addRow(30);

    $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText('Stato Civile',array('size'=>12),array('align'=>'right'));

    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText(utf8_decode($candidato['dati']['statocivile']));    
}

$patente="";
$altrepatenti="";
//if ($candidato['dati']['tipopatente']<>"")
//    $patente="Patente: " .$candidato['dati']['tipopatente'] . " ";
if ($candidato['dati']['altrepatenti']<>"")
    $altrepatenti="Altre Patenti: " .$candidato['dati']['altrepatenti'];

$table->addRow(30);
$cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

if($candidato['dati']['automunito']=='Si'){    
    if ($candidato['dati']['sesso']=='Maschile'){            
        $cell->addText('Automunito ',array('size'=>12),array('align'=>'right'));
    }    
    else {
        $cell->addText('Automunita ',array('size'=>12),array('align'=>'right'));
    }
} else {
        $cell->addText("",array('size'=>12),array('align'=>'right'));
}
$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText($patente.$altrepatenti);

//Per gestire i caratteri accentati: 
//$test = "è ò ù";
//$cell->addText('Accenti:' . utf8_decode($test));

$recordNumber = count($candidato['studi']);
if ($recordNumber>0){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('FORMAZIONE SCOLASTICA',$titolo_sezione_font,$titolo_sezione_par);

    foreach ($candidato['studi'] as $key => $studio) {
        $table->addRow(30);

        $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($studio['annoinizio'].' - '.trim($studio['annofine']),array('size'=>12),array('align'=>'right'));

        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        //$cell->addText(chr(9).utf8_decode($studio['tiposcuola'] . ", " . $studio['nomescuola']. ", " . $studio['paese']));
        $pos = strpos($studio['nomescuolaelementare'], "Scuole Elementari");
        $ScuolaElem="";
        if ($studio['estero']!="Si"){
            if ($studio['tiposcuola']=="Scuole Elementari"){
                if ($pos === false)
                    $ScuolaElem="Scuole Elementari ";
            } 
            //$cell->addText(chr(9).$ScuolaElem.utf8_decode($studio['nomescuolaelementare']. $studio['nomescuolamedia']. $studio['nomescuolaprofessionale']. $studio['nomescuolasuperiore']. $studio['nomeuniversita']));
            $cell->addText($ScuolaElem.utf8_decode(replace1252($studio['nomescuolaelementare']. $studio['nomescuolamedia']. $studio['nomescuolaprofessionale']. $studio['nomescuolasuperiore']. $studio['nomeuniversita'])));
            if ($studio['paese']!="") $cell->addText(" " . $studio['paese']);
        } else {
            //$cell->addText(chr(9).utf8_decode($studio['tiposcuola'] . ", " . $studio['nomescuola'] . " " . $studio['paese']));
            $cell->addText(utf8_decode(replace1252($studio['tiposcuola'] . " " . $studio['nomescuola'] . " " . $studio['paese'])));
        }  
    }    
}

$recordNumber = count($candidato['soggiornilinguistici']);
if ($recordNumber>0){
    $table->addRow(60);    
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('SOGGIORNI LINGUISTICI',$titolo_sezione_font,$titolo_sezione_par);
 
    foreach ($candidato['soggiornilinguistici'] as $key => $soggiornolinguistico) {
        $table->addRow(30);
        
        $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($soggiornolinguistico['meseinizio'].".".$soggiornolinguistico['annoinizio'].' - '.$soggiornolinguistico['mesefine'].".".$soggiornolinguistico['annofine'],array('size'=>12),array('align'=>'right'));

        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

        $soggiorno="";
        if ($soggiornolinguistico['citta']!="")
            $soggiorno=$soggiorno.utf8_decode(replace1252($soggiornolinguistico['citta'])) . ", ";
            //$cell->addText(utf8_decode(replace1252($soggiornolinguistico['citta']) . ", ");
        if ($soggiornolinguistico['nazione']!="Altra")
            $soggiorno=$soggiorno.utf8_decode(replace1252($soggiornolinguistico['nazione'])) . " ";
            //$textrun->addText(utf8_decode(replace1252($soggiornolinguistico['nazione']) . " ");
        else
            $soggiorno=$soggiorno.utf8_decode(replace1252($soggiornolinguistico['altranazione'])) . " ";
            //$textrun->addText(utf8_decode(replace1252($soggiornolinguistico['altranazione']) . " ");
        if ($soggiornolinguistico['diploma']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['diploma']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['diploma']),array('bold'=>true));      
        if ($soggiornolinguistico['esameen']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esameen']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameen']));
        if ($soggiornolinguistico['esameaus']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esameaus']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameaus']));
        if ($soggiornolinguistico['esameusa']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esameusa']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esameusa']));
        if ($soggiornolinguistico['esamede']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esamede']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamede']));
        if ($soggiornolinguistico['esamefr']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esamefr']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamefr']));
        if ($soggiornolinguistico['esamees']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['esamees']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['esamees']));        
        if ($soggiornolinguistico['altroesame']!="")
            $soggiorno=$soggiorno." - " . utf8_decode(replace1252($soggiornolinguistico['altroesame']));
            //$textrun->addText(" - " . utf8_decode(replace1252($soggiornolinguistico['altroesame']));         
        
        $cell->addText($soggiorno);
        //$textrun->addText(utf8_decode($soggiornolinguistico['citta']) . " " . $soggiornolinguistico['nazione'] . " - ");
        //$textrun->addText(chr(13).chr(10).chr(9).chr(9).$soggiornolinguistico['diploma'],array('bold'=>true));
        //$textrun->addText(" - " . utf8_decode($soggiornolinguistico['esame']));
    }    
}

$recordNumber = count($candidato['esperienzeprofessionali']);
if ($recordNumber>0){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('ESPERIENZE PROFESSIONALI',$titolo_sezione_font,$titolo_sezione_par);

    foreach ($candidato['esperienzeprofessionali'] as $key => $esperienzaprofessionale) {
        $table->addRow(30);

        $periodo="Dal ". $esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio']." ";
        
        $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        //$cell->addText("Dal ". $esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio'].' - '."al ".$esperienzaprofessionale['mesefine'].".".$esperienzaprofessionale['annofine'],array('size'=>12),array('align'=>'right'));
        //$cell->addText("Dal ". $esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio'].' - ',array('size'=>12),array('align'=>'right'));
        
        if ($esperienzaprofessionale['mesefine']=="" && $esperienzaprofessionale['annofine']=="") 
            if ($esperienzaprofessionale['adoggi']=="Si") 
                $periodo=$periodo.'ad oggi';
            else
                ;
        else
            $periodo=$periodo."al ".$esperienzaprofessionale['mesefine'].".".$esperienzaprofessionale['annofine'];       
        
        /*
        if ($esperienzaprofessionale['annofine']<>"")
            $periodo=$periodo."al ".$esperienzaprofessionale['mesefine'].".".$esperienzaprofessionale['annofine'];
            //$cell->addText("al ".$esperienzaprofessionale['mesefine'].".".$esperienzaprofessionale['annofine'],array('size'=>12),array('align'=>'right'));
        else    //ad oggi
            $periodo=$periodo.$esperienzaprofessionale['mesefine'];
            //$cell->addText($esperienzaprofessionale['mesefine'],array('size'=>12),array('align'=>'right'));
        */
        
        $cell->addText($periodo,array('size'=>12),array('align'=>'right'));
        
        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        $textrun->addText("",array('bold'=>true));        
        if ($esperienzaprofessionale['tipolavoro']<>"Indeterminato") 
            $textrun->addText(" (" . $esperienzaprofessionale['tipolavoro']. ") "); 
        
        $table->addRow(30);

        $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("Mansione ",array('size'=>12),array('align'=>'right'));

        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        //$textrun=$cell->createTextRun();
        
        $exploded = explode("\n", $esperienzaprofessionale['parolechiave']);
        foreach ($exploded as $part) {
          $cell->addText(str_replace("?","-",utf8_decode(replace1252($part))), $cellFontStyle, $cellParagraphStyle);
        }  
        $cell->addText("");
        //$textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['parolechiave'])));

        $table->addRow(30);

        $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("Carica ",array('size'=>12),array('align'=>'right'));

        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        $textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['carica'])));  
    }    
}

$recordNumber = count($candidato['conoscenzelinguistiche']);
if ($recordNumber>0){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('CONOSCENZE LINGUISTICHE',$titolo_sezione_font,$titolo_sezione_par);

    foreach ($candidato['conoscenzelinguistiche'] as $key => $conoscenzalinguistica) {
        
        $table->addRow(30);

        $cell = $table->addCell(4000,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        //$cell->addText($conoscenzalinguistica['lingua'],array('size'=>12),array('align'=>'right'));
        if ($conoscenzalinguistica['lingua']=="Altro"){
            $cell->addText($conoscenzalinguistica['linguaaltro'],array('size'=>12),array('align'=>'right'));
        }
        else{
            $cell->addText($conoscenzalinguistica['lingua'],array('size'=>12),array('align'=>'right'));
        }
        
        $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText("",array('align'=>'left'));

        $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        
        if ($conoscenzalinguistica['conoscenzaorale']=="Madrelingua")
            $cell->addText("Madrelingua");            
        else {   
            if ($conoscenzalinguistica['conoscenzaorale']=="Nessuna" && $conoscenzalinguistica['conoscenzascritta']!="Nessuna")
                $cell->addText("Scritto: " . $conoscenzalinguistica['conoscenzascritta']);    
            
            if ($conoscenzalinguistica['conoscenzaorale']!="Nessuna" && $conoscenzalinguistica['conoscenzascritta']=="Nessuna")
                $cell->addText("Orale: " . $conoscenzalinguistica['conoscenzaorale']);   
            
            if ($conoscenzalinguistica['conoscenzaorale']!="Nessuna" && $conoscenzalinguistica['conoscenzascritta']!="Nessuna")
                $cell->addText("Orale: " . $conoscenzalinguistica['conoscenzaorale'] . " / "."Scritto: " . $conoscenzalinguistica['conoscenzascritta']);   
        }       
    }    
}

$recordNumber = count($candidato['conoscenzeinformatiche']);
if ($recordNumber>0){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('CONOSCENZE INFORMATICHE',$titolo_sezione_font,$titolo_sezione_par);

    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    foreach ($candidato['conoscenzeinformatiche'] as $key => $conoscenzainformatica) {
        $textrun=$cell->createTextRun();
        //$textrun->addText(chr(9).$conoscenzainformatica['conoscenzeit'] . ": " . $conoscenzainformatica['livelloconoscenzeit']);
        
        if ($conoscenzainformatica['conoscenzeit']!="Altro")
            $textrun->addText($conoscenzainformatica['conoscenzeit']);
        else
            $textrun->addText($conoscenzainformatica['dettaglio']);        
        $textrun->addText(": " .substr($conoscenzainformatica['livelloconoscenzeit'],3)); 
    }    
}

if($candidato['dati']['hobby']!=''){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText('HOBBY E INTERESSI',$titolo_sezione_font,$titolo_sezione_par);
    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $textrun=$cell->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['hobby'])));    
}

if($candidato['dati']['disponibiledal']!='' or $candidato['dati']['disponibilitaimmediata']=='Si'){
    $table->addRow(60);
    $cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addTextBreak(1);
    $cell->addText(conv_text('DISPONIBILITÀ'),$titolo_sezione_font,$titolo_sezione_par);
    $cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
    $cell->addText("",array('align'=>'left'));

    $cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
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
$cell = $table->addCell(4000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addTextBreak(1);
$cell->addText('REFERENZE',$titolo_sezione_font,$titolo_sezione_par);

$cell = $table->addCell(500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addText("",array('align'=>'left'));

$cell = $table->addCell(8500,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
$cell->addTextBreak(1);
$textrun=$cell->createTextRun();
$textrun->addText('Su richiesta',array('size'=>12),array('align'=>'right'));

//Add Footer
$footer = $section->createFooter();
$footerFontStyle=array('color'=>'7F312F','bold'=>true);
$footerParagraphStyle=array('align'=>'right','spacing'=>0,'spaceAfter'=>0);
//$footer->addPreserveText('Associazione 18-24              Pagina {PAGE} di {NUMPAGES}.',$footerFontStyle,$footerParagraphStyle);

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename='18-24_'.$candidato['dati']['rif'].'.docx';
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
