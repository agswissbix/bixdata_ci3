<?php
$tableid=$data['tableid'];
$reports=$data['block']['reports'];
$fields=$data['fields'];
$userid=$data['userid'];
?>
<div id="reports_relativi"  >
<?php
if($userid==1)
{
?>   
    <br>
<div class="btn_scritta" onclick="$('#new_report').toggle()">Crea nuovo report</div>
<?php
}
?>
<div class="clearboth"></div><br/>
    <div id="new_report" style="display: none;padding: 20px;border: 1px solid #dedede;">
        <form>
        Nome report:
        <input type="text" name="report_name" value=""><br/><br/>
        Operazione:
        <select id="operation0" name="operation[0]">
            <option value=""></option>
            <option value="conta">Conta</option>
            <option value="somma">Somma</option>
            <option value="media">Media</option>
            <option value="massimo">Massimo</option>
            <option value="minimo">Minimo</option>
        </select>
        <select id="operation1" name="operation[1]">
            <option value=""></option>
            <option value="conta">Conta</option>
            <option value="somma">Somma</option>
            <option value="media">Media</option>
            <option value="massimo">Massimo</option>
            <option value="minimo">Minimo</option>
        </select>
        <br/><br/>
        Eseguita su:
        <select id="fieldid0" name="fieldid[0]">
            <option value=""></option>
            <?php
            foreach ($fields as $key => $field) {
                ?>
            <option value="<?=$field['fieldid']?>"><?=$field['description']?></option>
            <?php
            }
            ?>
        </select>
        <select id="fieldid1" name="fieldid[1]">
            <option value=""></option>
            <?php
            foreach ($fields as $key => $field) {
                ?>
            <option value="<?=$field['fieldid']?>"><?=$field['description']?></option>
            <?php
            }
            ?>
        </select>
        <br/><br/>
        Raggruppati per
        <select id="groupby" name="groupby">
            <option value=""></option>
            <?php
            foreach ($fields as $key => $field) {
                ?>
            <option value="<?=$field['fieldid']?>"><?=$field['description']?></option>
            <?php
            }
            ?>
        </select>
        <br/><br/>
        <select id="groupby" name="layout[0]">
            <option value=""></option>
            <option value="table">Tabella</option>
            <option value="barchart">Colonne</option>
            <option value="doughnut">Ciambella</option>
            <option value="line">Linea</option>
        </select>
        <select id="groupby" name="layout[1]">
            <option value=""></option>
            <option value="table">Tabella</option>
            <option value="barchart">Colonne</option>
            <option value="doughnut">Ciambella</option>
            <option value="line">Linea</option>
        </select>
        <br/><br/>
        Layout
        <div class="btn_scritta" onclick="save_report(this,'<?=$tableid?>')">Crea report</div>
        <br/>
        </form>

    </div>
    <?php
            foreach ($reports as $key => $report) {
                ?>
    <div class="report_container scheda" style="height: auto;width: 90%;float: left;margin-left: 10px;margin-bottom: 10px;padding: 5px;">
        <?=$report?>
    </div>
                <?php
            }
    ?>
    <div class="clearboth"></div>    
</div>    
    