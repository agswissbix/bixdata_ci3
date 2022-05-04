<style>
    #feedback { font-size: 1.4em; }
    #selectable .ui-selecting { background: #FECA40; }
    #selectable .ui-selected { background: #F39814; color: white; }
    #selectable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #selectable li { margin: 3px; padding: 0.4em; font-size: 1.4em; height: 18px; }
</style>
 <script>
     $(document).ready(function(){
        $(function() {
          $( "#selectable" ).selectable();
        });
     });
 </script>
 <div class="develop">block-macrogruppo_layout</div>
 <div align="center">
    <ol id="selectable">
        <li class="ui-widget-content" onclick="ChangeLayoutChoise('dashboard');">Imposta Dashboard</li>
        <!--<li class="ui-widget-content" onclick="ChangeLayoutChoise('schede');">Schede</li>!-->
        <li class="ui-widget-content" onclick="ChangeLayoutChoise('temi');">Temi</li>
        <li class="ui-widget-content" onclick="ChangeLayoutChoise('fontsize');">Font Size</li>
    </ol>
</div>