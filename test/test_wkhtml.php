<?php

$command='cd ./tools/wkhtmltopdf/bin && wkhtmltopdf.exe --dpi 10 --image-dpi 600 --image-quality 65 -T 0 -B 0 -L 0 -R 0 --orientation portrait --zoom 1.9  "../../../test/test_font.html" "../../../test/test_font.pdf" ';
exec($command);

?>