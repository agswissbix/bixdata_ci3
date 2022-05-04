<style type="text/css">
    
    table{
        border-collapse: collapse;
    }
    th, td {
    padding: 2mm !important;
    text-align: left;
  }
  tr{
      border: 2px solid black !IMPORTANT;
  }
</style>
<?php
foreach ($elenco as $key_elenco => $pages) {
foreach ($pages as $key_pages => $page) {
    $rows=$page;

?>

<div class="print_page" style="">
    
    <div>
       
        <div style="float: left;width: 33%">
            Ufficio: + 41 (0)91 682 89 61
        </div>
        <div style="float: left;width: 33%">
            Laboratorio Bioggio: + 41 (0)79 515 22 05 
        </div>
        <div style="float: left;width: 33%">
            Pagani I: + 41 (0)79 379 27 28 <br/>
            Pagani S: + 41 (0)79 754 01 80
        </div>
        <div style="clear: both">

        </div>
    </div>
     <?php
        if($key_elenco=='prove')
        {
            ?>

            <div>
                <G>PROVE</G>
            </div>
            <?PHP
        }
        ?>
    <table style="width: 100%;" border="1">
        <thead >
        <?php
        
        foreach ($rows[0] as $column => $value) {
        ?>
        <th><?=$column?></th>
        <?php
        }
        ?>
        </thead>
        <tbody>

        <?php
        
        foreach ($rows as $key => $row) {
        ?>
            <tr>
                <?php
                foreach ($row as $column => $value) {
                ?>
                <td><?=$value?></td>
                <?php
                }
                ?>
            </tr>
        <?php    
        }
        ?>
        </tbody>
    </table>
    <div>
        <div style="float: left;width: 33%">
            3P CLC Sagl - Bioggio 
        </div>
        <div style="float: left;width: 33%">
            <?php
            $today=date('d/m/Y');
            echo $today;
            ?>
        </div>
        <div style="float: left;width: 33%">
            <?php
            $now=date('H:i');
            echo $now;
            ?>
        </div>
        <div style="clear: both">

        </div>
    </div>

</div>

<?php
}
}
?>