<style>
    #feedback { font-size: 1.4em; }
    #selectableCreazioneCampi .ui-selecting { background: #FECA40; }
    #selectableCreazioneCampi .ui-selected { background: #F39814; color: white; }
    #selectableCreazioneCampi { list-style-type: none; margin: 0; padding: 0; width: 90%; }
    #selectableCreazioneCampi li { margin: 3px; padding: 0.4em; font-size: 1.4em; }
</style>
 <script>
     var ContatoreElementiCreati;
     var indiceCategoria;
     $(document).ready(function(){
         indiceCategoria = 0;
         ContatoreElementiCreati = 0;
        $(function() {
          $("#selectableCreazioneCampi").selectable();
        });
    });
 </script>
 <div align="center">
    <h4>TIPI DI CAMPO:</h4>
    <ul id="selectableCreazioneCampi">
        <li class="ui-widget-content campoimpost" id="text" onclick="ClonaElemento(this);" data-numfield="0">TEXT
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Parola" placeholder="tipocampo" >
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="data" onclick="ClonaElemento(this);" data-numfield="0">DATA
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Data" placeholder="tipocampo">
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="memo" onclick="ClonaElemento(this);" data-numfield="0">MEMO
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Memo" placeholder="tipocampo">
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="numeric" onclick="ClonaElemento(this);" data-numfield="0">NUMERIC
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Numero" placeholder="tipocampo">
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="numeric" onclick="ClonaElemento(this);" data-numfield="0">SERIALE
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Seriale" placeholder="tipocampo">
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="categoria" onclick="ClonaCategoria(this);" data-numfield="0">CATEGORIA
            <div><!-- AGGIUNGO LA PARTE CHE RIGUARDA LA TABELLA SYS_LOOKUP_TABLE_ITEM -->
                <input type="text" placeholder="fieldid" style="display: none; border: 1px solid black;" value=''>
                <input type="text" placeholder="description" style="display: none; border: 1px solid black;" value="">
                <select style="display: none;" placeholder="label"></select>
                <input type="hidden" value="categoria" placeholder="tipocampo">
                <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
                <input type="hidden" value="" placeholder="idarchivio">
                <input type="hidden" placeholder="insertorupdate" value="insert" >
            </div>
            <input type="button" value="+" style='display: none;' onclick='AggiungiInput(this);' data-numcampo="1" data-numoption="0"><br>
            <!--<div id="CampiAggiunti" style="border: 1px solid black; display: none;"></div>-->
        </li>
        <li class="ui-widget-content campoimpost" id="text" onclick="ClonaElemento(this);" data-numfield="0">ORA
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Ora" placeholder="tipocampo" >
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="text" onclick="ClonaElemento(this);" data-numfield="0">UTENTE
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="Utente" placeholder="tipocampo" >
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
        <li class="ui-widget-content campoimpost" id="text" onclick="ClonaElemento(this);" data-numfield="0">WEB
            <input type="text" placeholder="fieldid" style="display: none;" value=''>
            <input type="text" placeholder="description" style="display: none;" value=''>
            <select style="display: none;" placeholder="label"></select>
            <input type="hidden" value="web" placeholder="tipocampo" >
            <input type="hidden" data-type="campoposition" value="" placeholder="campoposition" >
            <input type="hidden" value="" placeholder="idarchivio" >
            <input type="hidden" placeholder="insertorupdate" value="insert" >
        </li>
    </ul>
 </div>