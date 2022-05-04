<?php

$command='cd ../tools/wkhtmltopdf32/bin && wkhtmltopdf.exe --dpi 10 --image-dpi 600 --image-quality 65 -T 0 -B 0 -L 0 -R 0 --orientation portrait --zoom 1.9  "../../../test/test_font.html" "../../../test/test_font.pdf" ';
echo "<br/>";
        echo exec("whoami");
        echo "Comando:";
        echo "<br/>";
        echo $command;
        echo "<br/>";
        exec($command,$output,$return_var);
        echo "Output:";
        echo "<br/>";
        foreach ($output as $key => $value) {
            echo $value;
        }
        echo "<br/>";
        echo "Return var:";
        echo "<br/>";
        echo $return_var;
        echo "<br/>";
        echo "<br/>";

?>