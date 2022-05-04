<?php

    function conv_text($texto)
    {
            return html_entity_decode(iconv('UTF-8', 'windows-1252',$texto));
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
    $tableParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

    $cellFontStyle=array();
    $cellParagraphStyle=array('spaceBefore'=>0,'spaceAfter'=>0,'spacing'=>0);

    $section = $PHPWord->createSection($sectionStyle);

    // Add header
    $header = $section->createHeader();

    // Create table element
    $table=$header->addTable(array('cellMarginLeft'=>80));

    // Add a row as normal.
    $table->addRow(400);

    //  The important thing to note here is that you when you create the cell 
    // that you want to have the multi-line on, is that you don't add text to it.
    $cell = $table->addCell(1700,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','spacing'=>0,'spaceAfter'=>0));
   $cell->addImage("assets/images/logo_ww.png", array('width'=>90,'height'=>90,'align'=>'center'));
   $cell->addText('WS', array('color'=>'B50012','size'=>72,'name'=>'Bodoni MT Poster Compressed','spacing'=>0,'spaceAfter'=>0), array('align'=>'center','spacing'=>0,'spaceAfter'=>0));
    
    $cell = $table->addCell(7180,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','bgColor'=>'f2f2f2','spacing'=>0,'spaceAfter'=>0));


    // Add as many text to the cell as you want lines.  
    $cell->addText("Dossier Confidenziale ID: ".strtoupper($dati['intestazione']['idDossier']), array('bold'=>true,'size'=>11), array('spacing'=>0,'spaceAfter'=>0));
    $cell->addText("Cognome: ".strtoupper($dati['intestazione']['cognome']), array('bold'=>true,'size'=>11), array('spacing'=>0,'spaceAfter'=>0));
    $cell->addText("Nome: ".$dati['intestazione']['nome'], array('bold'=>true,'size'=>11), array('spacing'=>0,'spaceAfter'=>0));
    $cell->addText("Qualifica: ".strtoupper($dati['intestazione']['qualifica']), array('bold'=>true,'size'=>11), array('spacing'=>0,'spaceAfter'=>0));
    $cell->addText("Data: ".strtoupper($dati['intestazione']['data']), array('bold'=>true,'size'=>11), array('spacing'=>0,'spaceAfter'=>0));

    if($foto_path!="")
    {
    $cell = $table->addCell(2500,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7F312F','bgColor'=>'E4E5E0','spacing'=>0,'spaceAfter=>'=>0));
    $cell->addImage($foto_path,array('width'=>130,'height'=>100,'align'=>'center','valign'=>'center'));
    }


    //INFO GENERALI inizio

    /*$section->addTextBreak();
    $section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
    $section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);*/
    //$section->addText('_',$distanziatore4FontStyle,$distanziatore4ParagraphStyle);
    $section->addTextBreak();
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'ffe697','spacing'=>0,'spaceAfter'=>0))->addText('INFO GENERALI',array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);


    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2100,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'ffe697','spacing'=>0,'spaceAfter'=>0))->addText('Dati anagrafici',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));


    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);


    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(3633,array('valign'=>'center','borderTopSize'=>13,'borderLeftSize'=>13,'borderColor'=>'7B7B7B'))->addText('Anno di nascita: '.$dati['datianagrafici']['annonascita'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderTopSize'=>13,'borderColor'=>'7B7B7B'))->addText('Sesso: '.$dati['datianagrafici']['sesso'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText(conv_text('Nazionalità: ').$dati['datianagrafici']['nazionalita'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(3633,array('valign'=>'center','borderLeftSize'=>13,'borderColor'=>'7B7B7B'))->addText('Stato civile: '.$dati['datianagrafici']['statocivile'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderColor'=>'7B7B7B'))->addText('Patente: '.$dati['datianagrafici']['patente'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Auto: '.$dati['datianagrafici']['auto'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(3633,array('valign'=>'center','borderLeftSize'=>13,'borderColor'=>'7B7B7B'))->addText('Permesso: '.$dati['datianagrafici']['permesso'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderColor'=>'7B7B7B'))->addText('Domicilio: '.$dati['datianagrafici']['domicilio'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('_'.$dati['datianagrafici']['auto'],array('color'=>'FFFFFF'), $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(3633,array('valign'=>'center','borderBottomSize'=>13,'borderLeftSize'=>13,'borderColor'=>'7B7B7B'))->addText('N. figli: '.$dati['datianagrafici']['numfigli'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Anno di nascita figli: '.$dati['datianagrafici']['annonascitafigli'],$tableFontStyle, $tableParagraphStyle);
    $table->addCell(3633,array('valign'=>'center','borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('_'.$dati['datianagrafici']['auto'],array('color'=>'FFFFFF'), $tableParagraphStyle);


    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2100,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'ffe697'))->addText('Area personale',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Presenza: '.$dati['areapersonale']['presenza'],$tableFontStyle,$tableParagraphStyle);
    
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Corporatura: '.$dati['areapersonale']['corporatura'],$tableFontStyle, $tableParagraphStyle);


    /*$table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Abbigliamento: '.$dati['areapersonale']['abbigliamento'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText(conv_text('Proprietà di linguaggio: ').$dati['areapersonale']['proprietalinguaggio'],$tableFontStyle, $tableParagraphStyle);*/

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2100,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'ffe697'))->addText('Area professionale',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    /*$table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Motivazioni al cambiamento: '.$dati['areaprofessionale']['motivazionicambiamento'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Motivazioni alla posizione: '.$dati['areaprofessionale']['motivazioniposizione'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Aspettative: '.$dati['areaprofessionale']['aspettative'],$tableFontStyle, $tableParagraphStyle);*/

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderTopSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText(conv_text('Flessibilità orario: ').$dati['areaprofessionale']['flexorario'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    if(isset($dati['areaprofessionale']['referenze'])) //CHIEDERE AD ALE
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText(conv_text('Referenze: ').$dati['areaprofessionale']['referenze'],$tableFontStyle, $tableParagraphStyle);
    else
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText(conv_text('Referenze: '),$tableFontStyle, $tableParagraphStyle);

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    /*$table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2100,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'ffe697',))->addText('Termini',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(300);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText(conv_text('Disponibilità: ').$dati['termini']['disponibilita'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Parametro salariale: '.$dati['termini']['parametrosalariale'],$tableFontStyle, $tableParagraphStyle);

    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'spacing'=>0,'spaceAfter'=>0,'borderColor'=>'7B7B7B'))->addText('Salario desiderato: '.$dati['termini']['salariodesiderato'],$tableFontStyle, $tableParagraphStyle);

    //INFO GENERALI fine
    //
    //Page Break
    $section->addPageBreak();

    //FORMAZIONE inizio
    //$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'ddd9c3','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('FORMAZIONE',array('bold'=>true),array('spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);


    //-------- INSERIRE QUESTO PEZZO DI CODICE IN UN CICLO PER INSERIRE TUTTE LE SCUOLE SEGUITE----------------------//

    foreach($dati['formazione'] as $formazione){


        $table=$section->addTable(array('cellMarginLeft'=>80));
        $table->addRow(400);
        $table->addCell(3450,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderColor'=>'7B7B7B'))->addText('Anno: '.$formazione['anno'],$tableFontStyle, $tableParagraphStyle);
        $table->addCell(7450,array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'bgColor'=>'ddd9c3','borderColor'=>'7B7B7B'))->addText('Titolo: '.$formazione['titolo'],$tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(10900, array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2))->addText('Corso: '.$formazione['corso'],array('bold'=>true), $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(5450, array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Istituto: '.$formazione['istituto'],$tableFontStyle, $tableParagraphStyle);
        $table->addCell(5450, array('valign'=>'center','borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Luogo: '.$formazione['luogo'],$tableFontStyle, $tableParagraphStyle);

        $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
    }
    //------------------------------- FINE CICLO --------------------------------------------------//

    //FORMAZIONE fine

    $section->addPageBreak();

    //LINGUE inizio

    //$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'b8cce4','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('LINGUE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    //----------------------------- METTERE ANCHE QUESTO PEZZO DI CODICE IN UN CICLO PER OGNI LINGUA CONOSCIUTA DAL CANDIDATO
    foreach($dati['lingue'] as $lingua){

        $table=$section->addTable(array('cellMarginLeft'=>80));
        $table->addRow(400);
        $table->addCell(1816, array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'bgColor'=>'b8cce4','borderColor'=>'7B7B7B'))->addText($lingua['lingua'],array('bold'=>true),$tableParagraphStyle);
        $table->addCell(5450, array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Diplomi Conseguiti: '.$lingua['diplomiconseguiti'],$tableFontStyle, $tableParagraphStyle);
        //$table->addCell(7268, array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'gridSpan'=>4,'borderColor'=>'7B7B7B'))->addText($lingua['diplomiconseguiti'], null, array('spacing'=>0,'spaceAfter'=>0));

        $table->addRow(400);
        $table->addCell(3633, array('valign'=>'center','borderBottomSize'=>13,'borderLeftSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2))->addText('Conversazione: '.$lingua['conversazione'], $tableFontStyle, $tableParagraphStyle);
        $table->addCell(3633, array('valign'=>'center','borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Scrittura: '.$lingua['scrittura'], $tableFontStyle, $tableParagraphStyle);
        $table->addCell(3633, array('valign'=>'center','borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Lettura: '.$lingua['lettura'], $tableFontStyle, $tableParagraphStyle);

        $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    }
    //------------------------------- FINE CICLO --------------------------------------------------//

    //LINGUE fine
*/
    $section->addPageBreak();

    //ESPERIENZE PROFESSIONALI inizio

    //$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'ffff99','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('ESPERIENZE PROFESSIONALI',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    //------------ ANCHE QUESTO BLOCCO IN UN CICLO PER TUTTE LE ESPERIENZE PROFESSIONALI DEL CANDIDATO
        foreach($dati['esperienzeprofessionali'] as $esperienzaprofessionale){
            $table=$section->addTable(array('cellMarginLeft'=>80));

            $table->addRow(400);
            $table->addCell(2725, array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderColor'=>'7B7B7B'))->addText('Dal: '.$esperienzaprofessionale['dal'].'  Al: '.$esperienzaprofessionale['al'],$tableFontStyle, $tableParagraphStyle);
            $table->addCell(8175, array('valign'=>'center','borderRightSize'=>13,'borderTopSize'=>13,'bgColor'=>'ffff99','borderColor'=>'7B7B7B','gridSpan'=>2))->addText(strtoupper($esperienzaprofessionale['qualifica']),array('bold'=>true), $tableParagraphStyle);

            $table->addRow(400);
            $table->addCell(5450,array('valign'=>'center','borderLeftSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2))->addText('Ragione Sociale: '.$esperienzaprofessionale['ragionesociale'], $tableFontStyle, $tableParagraphStyle);
            $table->addCell(5450,array('valign'=>'center','borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Luogo: '.$esperienzaprofessionale['luogo'], $tableFontStyle, $tableParagraphStyle);

            /*$table->addRow(400);
            $table->addCell(5450,array('valign'=>'center','borderLeftSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2))->addText('Settore: '.$esperienzaprofessionale['settore'], $tableFontStyle, $tableParagraphStyle);
            $table->addCell(5450,array('valign'=>'center','borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('N. dipendenti: '.$esperienzaprofessionale['Ndipendenti'], $tableFontStyle, $tableParagraphStyle);

            $table->addRow(400);
            $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Subordinato a: '.$esperienzaprofessionale['subordinatoa'], $tableFontStyle, $tableParagraphStyle);

            $table->addRow(400);
            $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Responsabile di: '.$esperienzaprofessionale['responsabiledi'], $tableFontStyle, $tableParagraphStyle);*/

            $table->addRow(400);
            $cell=$table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3));
            $exploded = explode("\n", $esperienzaprofessionale['competenze']);
            $cell->addText('Competenze: ', $cellFontStyle, $cellParagraphStyle);
            foreach ($exploded as $part) {
              $cell->addText($part, $cellFontStyle, $cellParagraphStyle);
            }

            /*$table->addRow(400);
            $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Carriera: '.$esperienzaprofessionale['carriera'], $tableFontStyle, $tableParagraphStyle);

            $table->addRow(400);
            $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Lingue utilizzate: '.$esperienzaprofessionale['lingueutilizzate'], $tableFontStyle, $tableParagraphStyle);

            $table->addRow(400);
            $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>3))->addText('Referenze: '.$esperienzaprofessionale['referenze'], $tableFontStyle, $tableParagraphStyle);*/

            $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

        }
    //------------------------------- FINE CICLO --------------------------------------------------//

    //ESPERIENZE PROFESSIONALI fine

    $section->addPageBreak();

    //COMPETENZE inizio

    //$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'CCC0D9','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('COMPETENZE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    //----------------------------------- CICLO --------------------------------------//
    foreach($dati['competenze'] as $competenza){    

        $table=$section->addTable(array('cellMarginLeft'=>80));
        $table->addRow(400);
        $table->addCell(3725,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'ccc0d9'))->addText('Area: '.$competenza['area'], array('bold'=>true), $tableParagraphStyle);
        $table->addCell(7175, array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Livello d\'esperienza: '.$competenza['livelloesperienza'], $tableFontStyle, $tableParagraphStyle);
        //$table->addCell(2725, array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText($competenza['livellodiesperienza'], null, array('spacing'=>0,'spaceAfter'=>0));

        $table->addRow(400);
        $cell=$table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B','gridSpan'=>2));
            $exploded = explode("\n", $competenza['elencocompetenze']);
            $cell->addText('Elenco delle competenze: ', $cellFontStyle, $cellParagraphStyle);
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
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'ccc0d9','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('COMPETENZE IT',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
    //------------------------------------------CICLO------------------------------------------------------------//
    foreach($dati['competenzeit'] as $competenzait){    
        $table=$section->addTable(array('cellMarginLeft'=>80));
        $table->addRow(400);
        $table->addCell(2725,array('valign'=>'center','borderLeftSize'=>13,'borderTopSize'=>13,'bgColor'=>'ccc0d9','borderColor'=>'7B7B7B'))->addText('Software: '.$competenzait['software'],$tableFontStyle, $tableParagraphStyle);
        $table->addCell(8175,array('valign'=>'center','borderTopSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Livello di esperienza: '.$competenzait['livelloesperienza'],$tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(5450,array('valign'=>'center','borderLeftSize'=>13,'borderBottomSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Versione: '.$competenzait['versione'],$tableFontStyle, $tableParagraphStyle);
        $table->addCell(5450,array('valign'=>'center','borderBottomSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Anno: '.$competenzait['anno'],$tableFontStyle, $tableParagraphStyle);

        $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);
    }
    //----------------------------------------------------------- FINE CICLO ----------------------------------------//

    //COMPETENZE IT fine

    /*$section->addPageBreak();

    //VALUTAZIONE inizio

    //$section->addText('_',$distanziatore3FontStyle,$distanziatore3ParagraphStyle);
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900,array('valign'=>'center','borderSize'=>18,'borderColor'=>'7B7B7B','bgColor'=>'d6e3bc','spacing'=>0,'spaceAfter'=>0,'cellMarginLeft'=>10))->addText('VALUTAZIONE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('spacing'=>0,'spaceAfter'=>0));
    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    //DATI CLIENTE inizio
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2725,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'d6e3bc'))->addText('DATI CLIENTE',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

        $daticliente=$dati['daticliente'];
        $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);
        $table=$section->addTable(array('cellMarginLeft'=>80));
        $table->addRow(400);
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderTopSize'=>13,'borderColor'=>'7B7B7B'))->addText('Ragione sociale:', $tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Posizione ricercata:', $tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Tipologia contrattuale:', $tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderColor'=>'7B7B7B'))->addText('Luogo di lavoro:', $tableFontStyle, $tableParagraphStyle);

        $table->addRow(400);
        $table->addCell(10900,array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Retribuzione annua lorda:', $tableFontStyle, $tableParagraphStyle);

    //DATI CLIENTE fine

    $section->addTextBreak();

    //ANALISI PROFILO inizio

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2725,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'d6e3bc'))->addText('ANALISI PROFILO',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(10900, array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderTopSize'=>13,'borderColor'=>'7B7B7B'))->addText('Elementi a favore:', $tableFontStyle, $tableParagraphStyle);
    $table->addRow(400);
    $table->addCell(10900, array('valign'=>'center','borderLeftSize'=>13,'borderRightSize'=>13,'borderBottomSize'=>13,'borderColor'=>'7B7B7B'))->addText('Elementi di discussione:', $tableFontStyle, $tableParagraphStyle);

    //ANALISI PROFILO fine

    $section->addText('_',$distanziatore2FontStyle,$distanziatore2ParagraphStyle);

    //GIUDIZIO inizio
    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow(400);
    $table->addCell(2725,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B','bgColor'=>'d6e3bc'))->addText('GIUDIZIO',array('bold'=>true,'name'=>'Calibri','size'=>10),array('align'=>'center','spacing'=>0,'spaceAfter'=>0));

    $section->addText('_',$distanziatoreFontStyle,$distanziatoreParagraphStyle);

    $table=$section->addTable(array('cellMarginLeft'=>80));
    $table->addRow();
    $table->addCell(10900,array('valign'=>'center','borderSize'=>13,'borderColor'=>'7B7B7B'));

    //GIUDIZIO FINE

    //VALUTAZIONE fine

    $section->addTextBreak();*/


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
    $filename=$dati['intestazione']['idDossier'].'_'.substr($dati['datianagrafici']['sesso'], 0, 1).'_'.$dati['intestazione']['cognome'].'_'.date("m-d-Y").'_ws_lgl.docx';
    $filename=  str_replace(" ", "", $filename);
    if(!file_exists("./stampe")){
    mkdir("./stampe");
    }
    if(!file_exists("./stampe/".$data['userid'])){
        mkdir("./stampe/".$data['userid']);
    }
    $objWriter->save('stampe/'.$dati['userid'].'/'.$filename);
    echo $filename;
?>