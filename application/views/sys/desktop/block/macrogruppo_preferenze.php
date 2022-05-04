<style>
    #feedback { font-size: 1.4em; }
    #selectable .ui-selecting { background: #FECA40; }
    #selectable .ui-selected { background: #F39814; color: white; }
    #selectable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #selectable li { margin: 3px; padding: 0.4em; font-size: 1.4em; height: 18px; }
</style>
  <script>
    var idutente="<?php echo $this->session->userdata('idutente'); ?>";
    var SelectableIndexMacrogruppoPreferenze;
    $(function() {
      $( "#selectable" ).selectable({
        stop: function() {
          $( ".ui-selected", this ).each(function() {
            SelectableIndexMacrogruppoPreferenze = $( "#selectable li" ).index( this );
          });
        }
      });
    });
</script>
<div class="develop">block-macrogruppo_preferenze</div>
<div align="center">
    <ol id="selectable">
        <li class="ui-widget-content" onclick="ChangeSelection('campiFissi');" data-chiave="campiFissi">Campi Fissi</li>
        <li class="ui-widget-content" onclick="ChangeSelection('risultatiricerca');" data-chiave="risultatiricerca">Colonne Risultati</li>
        <li class="ui-widget-content" onclick="ChangeSelection('risultatilinked');" data-chiave="risultatilinked">Colonne Linked</li>
        <li class="ui-widget-content" onclick="ChangeSelection('schedanavigatore');" data-chiave="schedanavigatore">Navigatore Schede</li>
        <li class="ui-widget-content" onclick="ChangeSelection('keylabel');" data-chiave="keylabel">Key Label</li>
        <li class="ui-widget-content" onclick="ChangeSelection('keylabel_scheda');" data-chiave="keylabel_scheda">Key Label Scheda</li>
        <li class="ui-widget-content" onclick="ChangeSelection('campiricerca');" data-chiave="campiricerca">Campi Ricerca</li>
        <li class="ui-widget-content" onclick="ChangeSelection('campischeda');" data-chiave="campischeda">Campi Scheda</li>
        <!--<li class="ui-widget-content" onclick="ChangeSelection('campiInserimento');" data-chiave="campiInserimento">Campi Inserimento</li>-->
        <li class="ui-widget-content" onclick="ChangeSelection('keylabel_inserimento');" data-chiave="keylabel_inserimento">Key Label Inserimento</li>
        <li class="ui-widget-content" onclick="LoadCollegamentiTabelle();" data-chiave="collega_tabelle">Collegamento Tabelle</li>
        <li class="ui-widget-content" onclick="LoadCreazioneCampi();" data-chiave="creazione_campi">Gestione Campi</li>
        <li class="ui-widget-content" onclick="LoadCreazioneLabel();" data-chiave="creazione_label">Creazione Label</li>
        <li class="ui-widget-content" onclick="CreazioneArchivi();" data-chiave="creazione_archivi">Creazione Archivi</li>
    </ol>
</div>
