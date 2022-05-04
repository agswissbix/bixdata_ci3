<?php
$risultati=$data['risultati'];
$archivi=$data['campi_ricerca']['tables'];
$archivio=$data['archivio'];
/*function conv_text($texto)
{
        return html_entity_decode(iconv('UTF-8', 'windows-1252',$texto));
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
                      'marginLeft' => 500,
                      'marginRight' => 500,
                      'marginBottom' => 0,
                      );

$distanziatoreFontStyle=array('size'=>3,'color'=>'#FFFFFF');
$distanziatoreParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$distanziatore2FontStyle=array('size'=>9,'color'=>'#FFFFFF');
$distanziatore2ParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$distanziatore3FontStyle=array('size'=>7,'color'=>'#FFFFFF');
$distanziatore3ParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$tableFontStyle=array();
$tableParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$cellFontStyle=array();
$cellParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

$ParametriArchivioFontStyle=array();
$ParametriArchivioParagraphStyle=array();
$section = $PHPWord->createSection($sectionStyle);

// Add header
$header = $section->createHeader();

// Create table element
$table=$header->addTable(array('cellMarginLeft'=>80));

// Add a row as normal.
$table->addRow(400);

//  The important thing to note here is that you when you create the cell 
// that you want to have the multi-line on, is that you don't add text to it.
$cell = $table->addCell(1700,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0));
$cell->addImage("assets/images/logo_ww.png", array('width'=>90,'height'=>90,'align'=>'center'));

$cell = $table->addCell(7000,array('valign'=>'center','spacing'=>0,'spaceAfter'=>0));


// Add as many text to the cell as you want lines.  
$cell->addText("Elenco selezione $archivio : ".count($risultati), array('bold'=>true,'size'=>16), array('spacing'=>0,'spaceAfter'=>0));

/*$ReportArchivi= array(); // qui ci sarà l'elenco degli archivi provenienti dal $_POST
$ReportArchivi = null;//prima resetto il reporto archivi (ad ogni ciclo)
$ReportCampi = array(); //qui ci sarà l'elenco dei campi di ogni archivio
$ReportCampi=null;
$ReportValoriCampi = array(); //Qui ci saranno i vari parametri di ogni singolo campo
$ReportValoriCampi=null;*/
$header->addTextBreak();
$section->addText("PARAMETRI DI RICERCA",array('bold'=>true),array());
$text_parametri="";
foreach($archivi as $keyarchivio => $valuearchivio)
{
    $table=$section->addTable();
    $t=$valuearchivio;
    $table->addRow(300);
    $table->addCell(300,array('borderSize'=>1))->addText($keyarchivio, array('bold'=>true));
    foreach($t as $keyT => $valueT)
    {
        //$table->addRow(300);
        if($valueT['table_param']=='or')
        {
            $text_parametri=$text_parametri." oppure";
        }
        if($valueT['table_param']=='and')
        {
            $text_parametri=$text_parametri." e anche";
        }
        $campi=$valueT['fields'];
        foreach($campi as $keyCampo => $valueCampo)
        {
            $table->addRow(300);
            $text_parametri=$text_parametri." ".  strtolower($keyCampo);
            $f=$valueCampo;
            foreach($f as $keyF => $valueF)
            {
                 if($valueF['param']=='or')
                 {
                  $text_parametri=$text_parametri." oppure";    
                 }
             
             $text_parametri=$text_parametri." ".strtoupper($valueF['value'][0]);
             if($valueF['value'][1]!='')
             {
                 $text_parametri=$text_parametri." - ".strtoupper($valueF['value'][1]);
             }
            }
            $table->addCell(10900,array('borderSize'=>1))->addText($text_parametri);
            $text_parametri="";
        }
        
    }
    //$text_parametri=$text_parametri.' )';
}
//$section->addText($text_parametri);


$section->addTextBreak(2);
$section->addPageBreak();
$section->addText("RISULTATI RICERCA",array('bold'=>true),array());
$table=$section->addTable();
 //metto dentro la variabile risultati l'array che arriva dalla funzione del sys_viewcontroller

$disponibilita=array(); //dichiaro un array disponibilità che verrà ridimensionato ad ogni ciclo dell'array risultati
$giudizio=array();
if(count($risultati)>0)
{
    $table->addRow();
    foreach ($risultati[0] as $key => $value) {
        if($archivio=='CANDID')
        {
        if($key!='recordid_')
        {
            if($key=='id')
            {
                $key='ID';
                $table->addCell(1100, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));   
            }
            if($key=='stato')
            {
                $key='Stato';
                $table->addCell(500, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='validato')
            {
                $key='Valid';
                $table->addCell(500, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='cognome')
            {
                $key='Cognome';
                $table->addCell(2180, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='nome')
            {
                $key='Nome';
                $table->addCell(2180, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='qualifica')
            {
                $key='Qualifica';
                $table->addCell(3400, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='giudizio')
            {
                $key='Giud';
                $table->addCell(1600, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='consulente')
            {
                $key='Con';
                $table->addCell(1000, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='eta')
            {
                $key=iconv('UTF-8', 'windows-1252', 'Età');
                $table->addCell(2000, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
        }
        }
        
        if($archivio=='AZIEND')
        {
            if($key!='recordid_')
        {
            if($key=='id')
            {
                $key='ID';
                $table->addCell(1100, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));   
            }
            if($key=='ragsoc')
            {
                $key='Ragione Sociale';
                $table->addCell(500, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='distretto')
            {
                $key='Distretto';
                $table->addCell(500, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='settore')
            {
                $key='Settore';
                $table->addCell(2180, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            if($key=='aziendastato')
            {
                $key='Azienda stato';
                $table->addCell(2180, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            
            if($key=='consulente')
            {
                $key='Consulente';
                $table->addCell(1000, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($key,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));  
            }
            
        }
        }
        
    }
    
}
foreach($risultati as $Key => $value)
{
    $table->addRow(400);
    $numero = $value;
    $i=0; //contatore per l'array delle disponibilità
    foreach($numero as $keynumero => $valorenumero)
    {

        if(($keynumero!='recordid_')&&($keynumero!='recordstatus_')&&($keynumero!='wwws')&&($keynumero!='pflash'))
        {
            //CUSTOM WORK&WORK
            if($keynumero=='stato')//se la chiave dell'array risultati è cognome allora prima metti la disponibilità e la Validazione poi inserisco il congome
            {
                $valorenumero=  strtoupper($valorenumero);
                $colore='FFFFFF';
                $colore_font='000000';
                if($valorenumero=='D')
                    $colore='92D050';
                if($valorenumero=='A')
                    $colore='AAA580';
                if($valorenumero=='N')
                {
                    $colore='7F7F7F';
                    $colore_font='FFFFFF';
                }
                if($valorenumero=='W')
                {
                    $colore_font='FFFFFF';
                    if(array_key_exists('wwws', $numero))
                    {
                        if($numero['wwws']=='W')
                            $valorenumero='W';
                        if($numero['wwws']=='WS')
                            $valorenumero='WS';
                    }
                    $colore='C00000';
                }
                    
                $table->addCell(1, array('borderTopSize'=>1,'bgColor'=>$colore,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($valorenumero,array('spacing'=>0,'spaceAfter'=>0,'bold'=>'true','color'=>$colore_font),array('align'=>'center'));
                
               }
            if($keynumero=='validato')
            {
                if(strtoupper($valorenumero)=='SI')
                {
                    if(array_key_exists('pflash', $numero))
                    {
                        if($numero['pflash']=='Si')
                        {
                            $table->addCell(1, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addImage('./assets/images/custom/WW/dossier.png',array('width'=>15,'height'=>15,'align'=>'center'));     
                        }
                        else
                            $table->addCell(1, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText('V',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));
                    }
                }
                else
                {
                    $table->addCell(1, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText('-',array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));
                }

                        
            }
            
            if(($keynumero!='stato')&&($keynumero!='validato'))
                 $table->addCell(1, array('borderTopSize'=>1,'borderBottomSize'=>1,'valign'=>'center','spacing'=>0,'spaceAfter'=>0))->addText($valorenumero,array('spacing'=>0,'spaceAfter'=>0),array('align'=>'center'));
        }
        $i++;
    }
}
// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
if(!file_exists("./stampe")){
    mkdir("./stampe");
}
if(!file_exists("./stampe/".$data['userid'])){
    mkdir("./stampe/".$data['userid']);
}
$objWriter->save('stampe/'.$data['userid'].'/elenco.docx');
echo 'elenco.docx';
?>
