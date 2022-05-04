<div class="develop">block-impostazioni_dashboard</div>
<div id="impostazioni_dashboard">
    <form id="form_impostazioni_dashboard">
        <div style="float: left;width: 20%;">
            Dashboard
            <select name="view">
                <?php
                foreach ($dashboards as $key => $dashboard) {
                ?>
                <option value="<?=$dashboard['id']?>"><?=$dashboard['name']?></option>
                <?php
                }
                ?>

            </select>
        </div>
        <div style="float: left;">
            Table
            <select>
                <option>

                </option>
            </select>
        </div>
        <div style="float: left;">
            View
            <select name="view">
                <?php
                foreach ($views as $key => $view) {
                ?>
                <option value="<?=$view['id']?>"><?=$view['name']?></option>
                <?php
                }
                ?>

            </select>
        </div>
        <div style="float: left;">
            Report
            <select name="report">
                <?php
                foreach ($reports as $key => $report) {
                ?>
                <option value="<?=$report['id']?>"><?=$report['name']?></option>
                <?php
                }
                ?>

            </select>
        </div>
        <div class="btn_scritta" onclick="salva_impostazioni_dashboard(this)">Salva</div>
    </form>
</div>