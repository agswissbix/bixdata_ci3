<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$excel_array=array();
$result=array();
if (($handle = fopen("test_report.csv", "r")) !== FALSE) {
                    while ($data = fgetcsv($handle, 0, ";")) {
                        $excel_array[]=$data;
                    }
                }
foreach ($excel_array as $key_row => $row) {
    foreach ($row as $key_col => $col) {
        if($col=='Nr. commessa')
        {
            $nrcommessa=$row[2];
            $result[$nrcommessa]['nrcommessa']=$nrcommessa;
            $result[$nrcommessa]['seriali']=array();
        }
        if(strpos($col, 'Canoni') !== false) 
        {
            $seriale=  str_replace('Canoni ', '', $col);
            $result[$nrcommessa]['seriali'][$seriale]['seriale']=$seriale;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['canone_utilizzo']=0;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['canone_vendite']=0;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['eccedenze_utilizzo']=0;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['eccedenze_vendite']=0;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['totale_utilizzo']=0;
            $result[$nrcommessa]['seriali'][$seriale]['seriale']['totale_vendita']=0;
        }
        
    }
}
var_dump($result);
?>

