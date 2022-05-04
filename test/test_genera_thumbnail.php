<?php
$command='cd ../tools/gs9.01/bin && gswin32c.exe -dNOPAUSE -sDEVICE=jpeg -r300 -sOUTPUTFILE="test_thumbnail.jpg" "test.pdf"';
        
exec($command);

?>