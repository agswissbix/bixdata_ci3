<style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
</style>
<div class="develop">block-preload_preferences_dashboard</div>
<div align="center"><h3>ATTUALI</h3></div>
<br><br>
<div align="center">
    <ul id="sortable">
        <?php foreach($data as $dato){ ?>
        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $dato['dashboard']; ?></li>
        <?php } ?>
    </ul>
</div>
