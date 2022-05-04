<style type="text/css">
    @page {
            margin-top: 0.0em;
            margin-bottom: 0.0em;
            margin-left: 0.0em;
            margin-right: 0.0em;
        }
        
    .print_page
    {
        <?php
        if($stampa_elenco_orientamento=='portrait')
        {
            $width='242mm;';
        }
        else
        {
            $width='360.8mm;';
        }
        ?>
        padding-left: 10mm;
        padding-right: 10mm;
        padding-top: 10mm;
        overflow: hidden;
    }
    



</style>

    <?=$results_block?>
