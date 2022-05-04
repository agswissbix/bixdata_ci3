<script type="text/javascript">
    $( function() {
    $( "#tabs" ).tabs();
  } );
  
  function apri_ccl(el,recordid)
  {
      
      <?php
      if($customview_card)
      {
      ?>
              report_ccl(el,recordid);
        <?php
      }
      else
      {
          ?>
                  apri_scheda_record(el,'ccl',recordid,'popup','standard_dati','risultati_ricerca','modifica');
        <?php
      }  
      ?>
      
  }
</script>
<style type="text/css">
    .btn_ccl{
        margin-top: 0px;
        font-weight: bold;
        font-size: 18px;
    }
    .btn_ccl:hover{
        cursor: pointer;
    }
</style>
<br/>

<div id="tabs" style="padding-left: 20px;overflow: scroll;height: 85%;width: 50%;margin: auto;background-color: white;padding: 10px;">
    <select id="listaccl_anno" style="padding: 2px;padding-left: 10px;padding-right: 10px;float: right;" onchange="ajax_load_content(this,'ajax_load_content_listaccl/'+$(this).val())">
        <?php
        foreach ($anni as $key => $anno) {
            $selected='';
            if($anno['anno']==$annoselezionato)
            {
                $selected="selected";
            }
        ?>
        <option value="<?=$anno['anno']?>" <?=$selected?> ><?=$anno['anno']?></option>
        <?php
        }
        ?>
    </select>
  <ul>
    <li><a href="#attivi">Attivi</a></li>
    <li><a href="#nuovi">Nuovi</a></li>
    <li><a href="#vecchi">Vecchi</a></li>
  </ul>
  <div id="attivi">
      <?php
        foreach ($ccl_attivi as $key => $ccl) {
        ?>
      <div title="<?="v.".$ccl['versione']."  ".$ccl['datainizio']."  ".$ccl['datafine']?>" class="btn_ccl" onclick="apri_ccl(this,'<?=$ccl['recordid_']?>')" style="padding: 5px;"><?=$ccl['nomeccl']?></div>
        <?php
        }
        ?>
  </div>
  <div id="nuovi">
      <?php
        foreach ($ccl_nuovi as $key => $ccl) {
        ?>
      <div title="<?="v.".$ccl['versione']."  ".$ccl['datainizio']."  ".$ccl['datafine']?>" class="btn_ccl" onclick="apri_ccl(this,'<?=$ccl['recordid_']?>')" style="padding: 5px;"><?=$ccl['nomeccl']?></div>
        <?php
        }
        ?>
  </div>
  <div id="vecchi">
      <?php
        foreach ($ccl_vecchi as $key => $ccl) {
        ?>
      <div title="<?="v.".$ccl['versione']."  ".$ccl['datainizio']."  ".$ccl['datafine']?>" class="btn_ccl" onclick="apri_ccl(this,'<?=$ccl['recordid_']?>')" style="padding: 5px;"><?=$ccl['nomeccl']?></div>
        <?php
        }
        ?>
  </div>
</div>
<div >


