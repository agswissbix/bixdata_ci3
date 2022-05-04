<?php
$funzione=$data['funzione'];
$scheda_container=$data['scheda_container'];
$tableid=$data['tableid'];
$query=$data['query'];
$saved_views=$data['saved_views'];
$viewid=$data['viewid'];
$userid=$data['userid'];
$active_tab=1;
if($scheda_ricerca_default=='filtri')
{
    $active_tab=0;
}
if($scheda_ricerca_default=='ricerche_salvate')
{
    $active_tab=1;
}   
$view_name='';
            foreach ($saved_views as $key => $saved_view) {
                $view_selected='';
                $selected='';
                if($saved_view['id']==$viewid)
                {
                    $view_name=$saved_view['name'];
                }
            }
?>
<script type="text/javascript">
$( "#scheda_dati_ricerca" ).ready(function(){
 $('#tabs_scheda_dati_ricerca').tabs({
     active:<?=$active_tab?>
 });
 
 $( ".field_option_list" ).menu();
});

</script>

<div id="scheda_dati_ricerca" class="scheda scheda_dati_ricerca" data-tableid="<?=$data['tableid']?>" data-recordid="<?=$data['recordid']?>" data-funzione="<?=$funzione?>" data-scheda_container="<?=$scheda_container?>" data-schedaid="scheda_dati_ricerca" style="overflow: none">

    <div id="menu_scheda_campi" class="menu_mid menu_top ui-widget-header">
        <!--<div onclick="ajax_load_block_dati_labels('<?=$tableid?>','null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));">test</div>-->
            <div id="autosearch" style="float: right;line-height: 20px;font-weight: normal;margin-right: 20px;display: none;" >
                Auto:
                <input type="radio" id="autosearchTrue" name="autosearch" checked="checked"  /><label for="autosearchTrue" style="width: 30px;">On</label>
                <input type="radio" id="autosearchFalse" name="autosearch" /><label for="autosearchFalse" style="width: 30px;">Off</label>
            </div>

        <div class="clearboth"></div>
    </div>
    <div class="schedabody schedabody_with_menu_bottom">
        
        <div id="tabs_scheda_dati_ricerca" style="padding: 0px !important;border: 0px !important">    
        <ul>
            <li style="width: 30%;"><a style="display: block;width: 100%" href="#dati_ricerca">Campi</a></li>
            <li style="width: 30%;"><a style="display: block;width: 100%" href="#ricerche_salvate">Viste</a></li>
            <?php
            if(true)
            {
            ?>
            <li style="width: 30%;"><a style="display: block;width: 100%" href="#block_riepilogo">Gestione</a></li>
            <?php
            }
            ?>
        </ul>
        <div id="dati_ricerca" class="block_dati_labels_container" style="float: left">
            
            <form id="form_dati_ricerca">
                <div class=" table_container tablecontainer" style="padding-bottom: 0px;">

                </div>
                <div id="block_dati_labels_container">
                </div>    

                <?php
               // echo $data['block']['block_dati_labels'];
                ?>
            </form>
        </div>
        <div id="ricerche_salvate" class="block_dati_labels_container" style="float: left">

            
            <?php
            foreach ($saved_views as $key => $saved_view) {
                $view_selected='';
                                            $selected='';
                                            if($saved_view['id']==$viewid)
                                            {
                                                $view_selected='view_selected';
                                            }
                                            ?>
            <div class="btn_ricerche_salvate <?=$view_selected?>" data-saved_view_id="<?=$saved_view['id']?>" data-test="<?=$saved_view['id']?>" >
                <div onclick="view_changed(this,'<?=$tableid?>','<?=$saved_view['id']?>')" style="width: 80%;float: left" title="<?=$saved_view['id']?>">
                    <?=$saved_view['name']?>
                </div>
                
                <div class=" set_default_saved_view fa fa-trash tooltip" title="Elimina" style="float: right;display: none;margin-right: 2px;"onclick="delete_view(this,'<?=$saved_view['id']?>')" data-tableid="<?=$tableid?>">
                </div>
                <div class=" set_default_saved_view fa fa-star tooltip" title="Rimuovi predefinito" style="float: right;display: none;margin-right: 2px;color:red"onclick="unset_default_view(this,'<?=$saved_view['id']?>')" data-tableid="<?=$tableid?>">
                </div>
                <div class=" set_default_saved_view fa fa-star tooltip" title="Imposta predefinito" style="float: right;display: none;margin-right: 2px;"onclick="set_default_view(this,'<?=$saved_view['id']?>')" data-tableid="<?=$tableid?>">
                </div>
                <div class=" set_default_saved_view fa fa-edit tooltip" title="Modifica nome" style="float: right;display: none;margin-right: 2px;"onclick="rename_view(this,'<?=$saved_view['id']?>')" data-tableid="<?=$tableid?>">
                </div>
                <div class=" set_default_saved_view fa fa-eyedropper tooltip" title="Imposta css" style="float: right;display: none;margin-right: 2px;"onclick="set_css_view(this,'<?=$saved_view['id']?>')" data-tableid="<?=$tableid?>">
                </div>
                
                <div class="clearboth"></div>
            </div>

                <?php
                                        }
                                        ?>
        </div>
        <div id="block_riepilogo" class="blocco" style="height: 100%;width: 100%;display: none;float: left;">
            <h3 onclick="show_query_riepilogo(this)"> Gestione</h3>
            <div id="riepilogo" class="riepilogo" style="" >
                <form id="form_riepilogo" class="form_riepilogo" method="post" action="<?php echo site_url('sys_viewcontroller/'); ?>/esporta_xls" >
                    <input type="hidden" id="view_name" name="view_name">
                    <input type="hidden" id="exportid" name="exportid">
                    <input type="hidden" id="tableid" name="tableid" value="<?=$tableid?>">
                    <textarea id="query" class="query_riepilogo" name="query" style="width: 100%;height: 200px;overflow: scroll;display: none;">
                        <?=$query?>
                    </textarea>
                    <input id="file_allegato_hidden" class="file_allegato" type="text" style="display: none" value="" >
                    <div>
                        <div style="float: left">
                            Vista attuale 
                        </div>
                        <div style="float: right;margin-left: 10px;">
                            <input class="checkbox_mantienivista" style="margin-right: 5px" type="checkbox"><span style="font-size: 12px;color: #54ace0;text-transform: uppercase">mantieni vista</span>
                        </div>
                        <div class="clearboth"></div>
                    </div>
                    <div class="form_riepilogo_savedview">
                        <span style='color:#545454;font-weight: bold;'>
                        <?php
                        if($view_name=='')
                        {
                        }
                        else
                        {
                            echo $view_name;  
                        }
                        ?>
                        </span>
                    </div>
                    <div>
                        <div style="float: left">
                            Filtri sui campi 
                        </div>
                        
                        <div class="clearboth"></div>
                    </div>
                    <div class="form_riepilogo_fields">
                        
                    </div>
                </form> 
            </div>
                
                <div class="btn_scritta" style="float: left;" onclick="save_view(this,'<?=$tableid?>')">Salva vista</div>
                <div class="clearboth"></div>
            
            

        </div>
    </div>
    <div class="clearboth"></div>
    
    </div>
    <div class=" menu_big menu_bottom">
        
        
        <div id="btnCerca" class="btn_scritta" onclick="update_query(this,'<?=$tableid?>')" style="width: 100px;">Cerca</div>
        <?php
        if($tableid=='CANDID')
        {
        ?>
            <div id="btnCerca" class="btn_scritta" onclick="ajax_load_block_risultati_ricerca_non_validati(this, '<?=$data['tableid']?>')" style="width: 100px;">Non validati</div>
        <?php
        }
        ?>
        <div id="btnMostraTutti" class="btn_scritta" onclick="reload_fields(this,'<?=$tableid?>')" style="width: 150px;">Annulla filtri</div>
        <div class="clearboth"></div>
    </div>
    
</div>
    
