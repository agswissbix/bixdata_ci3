<div id="content_impostazioni_preferenze" class="content">
    <div style="margin-top: 10px;">
        <!--<div style="width: 50px; height: 50px; display: inline-block;" id="settings_user"></div>-->
        <div class="btn_scritta"  id="settings_campi_preferiti" onclick="ShowCampiPreferenza();">Archivi</div>
        <div class="btn_scritta" id="settings_layout" onclick="load_block_impostazioni(this,'archivi');">Archivi2</div>
        <div class="btn_scritta"  id="settings_campi_preferiti" onclick="load_block_impostazioni(this,'layout');">Layout</div>
        <div class="btn_scritta"  id="settings_campi_preferiti" onclick="load_block_impostazioni(this,'dashboard');">Dashboard</div>
        <div class="btn_scritta"  id="settings_campi_preferiti" onclick="load_block_impostazioni(this,'utente');">Sistema</div>
         <div class="btn_scritta"  id="settings_campi_preferiti" onclick="load_block_impostazioni(this,'utente');">Utente</div>
        <div class="btn_scritta"  id="settings_campi_preferiti" onclick="load_block_impostazioni_dati_menu(this);">Dati</div>
        <!--<div class="btn_scritta" id="settings_layout" onclick="ShowLayoutSettings();">Layout</div>-->
        <div class="btn_scritta" id="settings_layout" onclick="load_block_impostazioni(this,'layout');">Layout</div>
        <div class="btn_scritta" id="settings_layout" onclick="load_block_impostazioni(this,'scheduler');">Scheduler</div>
        <div class="btn_scritta" id="settings_layout" onclick="load_block_impostazioni(this,'script');">Script</div>
        <div class="btn_scritta" id="settings_layout" onclick="clickMenu(this,'ajax_load_content_test')">Test</div>
        <div class="btn_scritta" id="settings_layout" onclick="clickMenu(this,'ajax_load_content_services')">Servizi</div>
        <div class="btn_scritta" id="settings_layout" onclick="clickMenu(this,'ajax_load_content_gestioneutenti')">Gestione utenti</div>
        <!--<div style="width: 50px; height: 50px; display: inline-block;" id="settings_layout" onclick="ShowSuperuserSettings();"></div>-->
    </div>
    <br><br>
    <div id='sottogruppo' class='blocco' style="width: 20%; height: 80%; float: left; overflow-y: scroll; ">
    </div>
    <div id='centrale' class='blocco' style='width: 80%; height: 90%; float: left; '></div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    $(function()
    {
       $( "#accordion" ).accordion({
           heightStyle: "content"
       });
     });
      $(function() {
        $( "#accordion1" ).accordion({
            heightStyle: "content"
        });
      });
  });
  //ChangeArchives('risultatiricerca','selectrisultati');
</script>