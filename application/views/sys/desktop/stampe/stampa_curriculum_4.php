<?php
//width totale 12000. 9800 tenendo i margini a 1100

require_once 'phpword/PHPWord.php';
// New Word Document
$PHPWord = new PHPWord();

$PHPWord->setDefaultFontName('Arial'); 
$PHPWord->setDefaultFontSize(11);

$sectionStyle = array('orientation' => null,
                      'marginLeft' => 1100,
                      'marginRight' => 1100,
                      'marginBottom' => 0,
                      );

$title_textFontStyle=array('size'=>3,'color'=>'#FFFFFF');
$title_textParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$myfontstyle=array('size'=>12);

$tableFontStyle=array();
$table_label_FontStyle=array('bold'=>true,'size'=>10);
$table_value_FontStyle=array();
$tableParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);
$tableTextRunParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$cellStyle=array('valign'=>'top');
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

$header = $section->createHeader();
$header->addText(utf8_decode(replace1252($candidato['dati']['cognome']. " " . $candidato['dati']['nome'])),array('color'=>'4F81BD','size'=>16));
$header->addText($candidato['dati']['via']. " " . $candidato['dati']['ncivico'],array('size'=>12));
$header->addText($candidato['dati']['nap']. " " . $candidato['dati']['localita'],array('size'=>12));

if ($candidato['dati']['cellulare']!="")
    $header->addText($candidato['dati']['cellulare']. ($candidato['dati']['paginaweb']<>"" ? ' - Pagina web: '.$candidato['dati']['paginaweb'] : "")
    . ($candidato['dati']['paginaweb']<>"" ? ' - LinkedIn: '.$candidato['dati']['linkedin'] : "" ),array('size'=>12)); 
elseif($candidato['dati']['telefonofisso']!='')
    $header->addText($candidato['dati']['telefonofisso']. ($candidato['dati']['paginaweb']<>"" ? ' - Pagina web: '.$candidato['dati']['paginaweb'] : "")
    . ($candidato['dati']['paginaweb']<>"" ? ' - LinkedIn: '.$candidato['dati']['linkedin'] : "" ),array('size'=>12));

$textrun = $header->createTextRun();
//$textrun->addText($candidato['dati']['email'].chr(9).chr(9).chr(9).'CURRICULUM VITAE',array('color'=>'4F81BD','size'=>16)); 

$textrun->addText($candidato['dati']['email'].chr(9).chr(9).chr(9).chr(9) ,array('size'=>12));
$textrun->addText('CURRICULUM VITAE',array('color'=>'4F81BD','size'=>16));
$header->addText('________________________________________________________________________',array('size'=>12));

$titolo_sezione_font=array('bold'=>true);
$titolo_sezione_par=array('align'=>'left');

//$section->addTextBreak(2);

$table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>0,'cellMarginBottom'=>0,'borderColor'=>'FFFFFF'));
$table->addRow(300);

$cell = $table->addCell(700,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

$cell->addImage('..//'.$foto, array('width'=>135,'height'=>135,'align'=>'left')); 
//Se $mostrafotocv=='no' || $mostrafotocv=='' || $mostrafotocv===NULL ¦¦ !file_exists la foto è vuota !!

$cell = $table->addCell(7600,array('valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>10, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
//
//Invece degli spazi aggiunti approssimativamente --> meglio usare TAB

$maschio_femmina='o';
if($candidato['dati']['sesso']=='Maschile'){
    $maschio_femmina='o';
}
if($candidato['dati']['sesso']=='Femminile'){
    $maschio_femmina='a';
} 

$cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).conv_text('Nat'.$maschio_femmina.' il '. $candidato['dati']['datanascita']), $myfontstyle, array('align' => 'right'));

if($candidato['dati']['nazionalita']!='Altro'){    
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).conv_text('Nazionalità ' . $candidato['dati']['nazionalita']), $myfontstyle, array('align' => 'right'));
} else{
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).conv_text('Nazionalità ' . $candidato['dati']['altranezionalita']), $myfontstyle, array('align' => 'right'));
}

if($candidato['dati']['attinenza']!=''){
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).'Attinenza ' .utf8_decode(replace1252($candidato['dati']['attinenza'])), $myfontstyle, array('align' => 'right'));
}
if ($candidato['dati']['permesso']<>''){
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).'Permesso ' . $candidato['dati']['permesso'], $myfontstyle, array('align' => 'right'));
}
$patente="";
$altrepatenti="";
//if ($candidato['dati']['tipopatente']<>"")
//    $patente=chr(9).chr(9) ."Patente: " .$candidato['dati']['tipopatente'];
if ($candidato['dati']['altrepatenti']<>"")
    $altrepatenti="Altre Patenti: " .$candidato['dati']['altrepatenti'];

if($candidato['dati']['automunito']=='Si'){    
    if ($candidato['dati']['sesso']=='Maschile'){
        $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).'Automunito', $myfontstyle, array('align' => 'right'));
    }    
    else{
        $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).'Automunita', $myfontstyle, array('align' => 'right'));
    }
} 
//if ($candidato['dati']['tipopatente']<>"")
//    $cell->addText(chr(9).chr(9).chr(9).$patente);
if ($candidato['dati']['altrepatenti']<>"")
    $cell->addText(chr(9).chr(9).chr(9).chr(9).chr(9).$altrepatenti, $myfontstyle, array('align' => 'right'));

//Per gestire i caratteri accentati: 
//$test = "è ò ù";
//$cell->addText('Accenti:' . utf8_decode(replace1252($test));

$recordNumber = count($candidato['studi']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Formazione scolastica',array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

    foreach ($candidato['studi'] as $key => $studio) {
		
        $table->addRow(10);
        $cell = $table->addCell(2100,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($studio['annoinizio'].' - '.trim($studio['annofine']));
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();

        $pos = strpos($studio['nomescuolaelementare'], "Scuole Elementari");
        if ($studio['estero']!="Si"){            
            if ($studio['tiposcuola']=="Scuole Elementari"){ 
                if ($pos === false) 
                    $textrun->addText("Scuole Elementari " );
            }             
            $textrun->addText(utf8_decode(replace1252($studio['nomescuolaelementare']. $studio['nomescuolamedia']. $studio['nomescuolaprofessionale']. $studio['nomescuolasuperiore']. $studio['nomeuniversita'])));
            if ($studio['paese']!="") $textrun->addText(" " . $studio['paese']);
        } else {            
           $textrun->addText(utf8_decode(replace1252($studio['tiposcuola'] . " " . $studio['nomescuola'] . " " . $studio['paese'])));        
        }     
		/*
        $textrun=$section->createTextRun();
        $textrun->addText($studio['annoinizio'].' - ');
        $textrun->addText($studio['annofine']);
        $textrun->addText(chr(9).chr(9) );
        //$textrun->addText(utf8_decode(replace1252($studio['tiposcuola'] . ", " . $studio['nomescuola']. ", " . $studio['paese']));
        $pos = strpos($studio['nomescuolaelementare'], "Scuole Elementari");
        if ($studio['estero']!="Si"){            
            if ($studio['tiposcuola']=="Scuole Elementari"){
                if ($pos === false)
                    $textrun->addText("Scuole Elementari: " );
            }             
            $textrun->addText(utf8_decode(replace1252($studio['nomescuolaelementare']. $studio['nomescuolamedia']. $studio['nomescuolaprofessionale']. $studio['nomescuolasuperiore']. $studio['nomeuniversita']));
            if ($studio['paese']!="") $textrun->addText(", " . $studio['paese']);
        } else {            
           $textrun->addText(utf8_decode(replace1252($studio['tiposcuola'] . ", " . $studio['nomescuola'] . " " . $studio['paese']) );        
        }  
*/		
    }    
}

$recordNumber = count($candidato['soggiornilinguistici']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Soggiorni linguistici',array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

    foreach ($candidato['soggiornilinguistici'] as $key => $soggiornolinguistico) {
		
        $table->addRow(10);
        $cell = $table->addCell(2100,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText($soggiornolinguistico['meseinizio'].".".$soggiornolinguistico['annoinizio'].' - '.$soggiornolinguistico['mesefine'].".".$soggiornolinguistico['annofine']);
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
/*		
        $textrun=$section->createTextRun();
        $textrun->addText($soggiornolinguistico['meseinizio'].".".$soggiornolinguistico['annoinizio'].' - ');
        $textrun->addText($soggiornolinguistico['mesefine'].".".$soggiornolinguistico['annofine']);
        $textrun->addText(chr(9)); 
*/		
        if ($soggiornolinguistico['citta']!="")
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['citta']) . ", "));
        if ($soggiornolinguistico['nazione']!="Altra")
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['nazione']) . " "));
        else
            $textrun->addText(utf8_decode(replace1252($soggiornolinguistico['altranazione']) . " "));
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
    $section->addText('Esperienze professionali',array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));

    foreach ($candidato['esperienzeprofessionali'] as $key => $esperienzaprofessionale) {
		
        $table->addRow(10);
        $cell = $table->addCell(2100,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
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
        
        $textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['azienda'] . (strlen($esperienzaprofessionale['carica'])>0 ? " - ":"") . $esperienzaprofessionale['carica'])),array('bold'=>true));
        if ($esperienzaprofessionale['tipolavoro']<>"Indeterminato") 
            $textrun->addText(" (" . $esperienzaprofessionale['tipolavoro']. ") ");
        //$textrun=$cell->createTextRun();
        
        $exploded = explode("\n", $esperienzaprofessionale['parolechiave']);
        foreach ($exploded as $part) {
          $cell->addText(str_replace("?","-",utf8_decode(replace1252($part))), $cellFontStyle, $cellParagraphStyle);
        } 
        $cell->addText("");
        //$textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['parolechiave'])));
		
		/*
        $textrun=$section->createTextRun();
        $textrun->addText($esperienzaprofessionale['meseinizio'].".".$esperienzaprofessionale['annoinizio'].' - ');
        if ($esperienzaprofessionale['mesefine']=="" && $esperienzaprofessionale['annofine']=="") 
            $textrun->addText(chr(9)); 
        else {
            $textrun->addText($esperienzaprofessionale['mesefine']);    //.".".$esperienzaprofessionale['annofine']);  
            if ($esperienzaprofessionale['annofine']<>"")
                $textrun->addText(".".$esperienzaprofessionale['annofine']);
        }
        $textrun->addText(chr(9));
        $textrun->addText(utf8_decode(replace1252($esperienzaprofessionale['azienda']." - ".$esperienzaprofessionale['carica']),array('bold'=>true));
        if ($esperienzaprofessionale['tipolavoro']<>"Indeterminato") 
            $textrun->addText(" (" . $esperienzaprofessionale['tipolavoro']. ") ");   
        $textrun=$section->createTextRun();
        $textrun->addText(chr(10).chr(13).chr(10).chr(13).chr(9).chr(9).chr(9).utf8_decode(replace1252($esperienzaprofessionale['parolechiave']));
 */
	}    
}

$recordNumber = count($candidato['conoscenzelinguistiche']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Conoscenze linguistiche',array('color'=>'4F81BD','size'=>16));

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
        
        if (strlen($conoscenzalinguistica['lingua'])<=6)
            $textrun->addText(chr(9));
        
        $textrun->addText(chr(9).chr(9));
        if ($conoscenzalinguistica['conoscenzaorale']=="Madrelingua")
            $textrun->addText("Madrelingua");
        else {
            if ($conoscenzalinguistica['conoscenzaorale']!="Nessuna")
                $textrun->addText("Orale: " . $conoscenzalinguistica['conoscenzaorale']);
            if ($conoscenzalinguistica['conoscenzascritta']!="Nessuna")
                $textrun->addText(" / " . "Scritto: " . $conoscenzalinguistica['conoscenzascritta']);            
        }  
    }    
}

$recordNumber = count($candidato['conoscenzeinformatiche']);
if ($recordNumber>0){
    $section->addTextBreak(1);
    $section->addText('Conoscenze informatiche',array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>180));

    foreach ($candidato['conoscenzeinformatiche'] as $key => $conoscenzainformatica) {
        $table->addRow(10);
        $cell = $table->addCell(2100,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $cell->addText(substr($conoscenzainformatica['livelloconoscenzeit'],3));
        $cell = $table->addCell(7000,array('valign'=>'top','borderSize'=>0,'borderColor'=>'FFFFFF','spacing'=>0,'spaceAfter'=>0,'spaceBefore'=>0, 'cellMarginBottom'=>0, 'cellMarginTop'=>0));
        $textrun=$cell->createTextRun();
        
        //$textrun=$section->createTextRun();
        //$textrun->addText(substr($conoscenzainformatica['livelloconoscenzeit'],3));
        //if ($conoscenzainformatica['livelloconoscenzeit']=="01.Base" or $conoscenzainformatica['livelloconoscenzeit']=="03.Buono" or $conoscenzainformatica['livelloconoscenzeit']=="04.Ottimo")
        //    $textrun->addText(chr(9));
        //$textrun->addText(chr(9).chr(9));
        if ($conoscenzainformatica['conoscenzeit']!="Altro")
            $textrun->addText($conoscenzainformatica['conoscenzeit']);
        else
            $textrun->addText($conoscenzainformatica['dettaglio']);   
    }    
}

if($candidato['dati']['hobby']!=''){
    $section->addTextBreak(1);
    $section->addText('Hobby e Interessi',array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>180));

    $textrun=$section->createTextRun();
    $textrun->addText(utf8_decode(replace1252($candidato['dati']['hobby'])));    
}

if($candidato['dati']['disponibiledal']!='' or $candidato['dati']['disponibilitaimmediata']=='Si'){
    $section->addTextBreak(1);
    $section->addText(conv_text('Disponibilità'),array('color'=>'4F81BD','size'=>16));

    $table=$section->addTable(array('cellMarginLeft'=>0,'cellMarginTop'=>180));

    $textrun=$section->createTextRun();
    if($candidato['dati']['disponibilitaimmediata']=='Si'){
        $textrun->addText('Immediata');
    }else{
        $textrun->addText('Dal ' . $candidato['dati']['disponibiledal'] );
        //if($candidato['dati']['disponibileal']!=null){
        //    $textrun->addText(' al ' . $candidato['dati']['disponibileal'] );
        //}
    }    
}

$section->addTextBreak(1);
$section->addText('Referenze',array('color'=>'4F81BD','size'=>16));
$textrun=$section->createTextRun();
$textrun->addText('Su richiesta');

//Add Footer
$footer = $section->createFooter();
$footerFontStyle=array('color'=>'7F312F','bold'=>true);
$footerParagraphStyle=array('align'=>'right','spacing'=>0,'spaceAfter'=>0);
//$footer->addPreserveText('Associazione 18-24              Pagina {PAGE} di {NUMPAGES}.',$footerFontStyle,$footerParagraphStyle);

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$filename='curriculum_4.docx';
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
