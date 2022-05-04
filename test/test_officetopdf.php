<?php
$command='cd ../tools/OfficeToPDF/ && OfficeToPDF.exe test.docx test.pdf';
        
exec($command,$output,$return_var);
                
                var_dump($output);
                var_dump($return_var);

?>