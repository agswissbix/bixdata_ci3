<?php
$datatable_records_container_id="datatable_records_".rand(). time();
$tableid=$data['archivio'];
$settings=$data['settings'];
$order=$table_settings['risultati_order'];

$cliente_id=$settings['cliente_id'];
$aaSorting="[[ 3, '".$order."' ]]";
if($cliente_id=='Uniludes')
{
    $aaSorting="[[ 0, 'desc' ]]";
}
if($tableid=='immobili')
{
    $aaSorting="[[ 3, 'asc' ]]";
}
if($tableid=='rapportidilavoro')
{
    $aaSorting="[[ 3, 'asc' ]]";
}
$contesto=$data['contesto'];
$columns=$data['columns'];
$id_datatable="table-elenco_".$contesto."_".$tableid.  rand();
$oTable_name="oTable_name_".rand().'_'.$tableid;
$query=$data['query'];

?>
<script type="text/javascript">
function custom_risultati_<?=$contesto?>_<?=$tableid?>(table,nRow, aData, iDataIndex)
{
    <?php
foreach ($css_rows as $key => $css_column) {
    ?>
    var index=Lookup_RealColumnIndex(table, '<?=$css_column['column']?>') ;
    if(typeof(index) !== 'undefined')
    {
        if(aData[index]=='<?=$css_column['value']?>')
        {
           $('td', nRow).css({
                             <?=$css_column['css']?>
                           }); 
        } 
    }
    
<?php
}
    ?>
    
}
    var oTable;
    $('#datatable_records_container_id').ready(function() {
	var <?=$oTable_name?>=$('#<?=$id_datatable?>').dataTable( {
                "stateSave": true,
                "bJQueryUI": true,
                
                <?php
                if($contesto=='risultati_ricerca')
                {
                ?>
                "iDisplayLength": <?=$table_settings['risultati_limit']?>,
                "scrollY": 100,
                "scrollX": 100,
                //"scrollCollapse": true,
                <?php
                }
                ?>
                <?php
                if($contesto=='dashboard')
                {
                ?>
                "iDisplayLength": 20,
                "scrollY": 100,
                "scrollX": 100,
                //"scrollCollapse": true,
                <?php
                }
                ?>
                <?php
                if($contesto=='records_linkedtable')
                {
                    //custom Dimensione Immobiliare
                    if($tableid=='immobile_documenti')
                    {
                    ?>
                        "iDisplayLength": 100,
                    <?php
                    }
                    else
                    {
                ?>
                "iDisplayLength": <?=$table_settings['linked_rows']?>,
                //"scrollX": 100,
                <?php
                    }
                }
                ?>            
                "sPaginationType": "full_numbers",
                "bFilter": false,
                "lengthChange": false,
                "bAutoWidth":true,
                "aaSorting": <?=$aaSorting?>,
                "columnDefs": [ 
                <?php
                $cont=0;
                $index=0;
                foreach ($columns as $key => $column) 
                {
                    if(($column['fieldtypeid']=='Sys')||(($master_tableid!='')&&($column['linkedtableid']==$master_tableid)))
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{bVisible: false,targets: $index }";
                        $cont=$cont+1;
                    }
                    if($column['desc']=='Da Contattare')
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{bVisible: false,targets: $index }";
                        $cont=$cont+1;
                    }
                    if ($column['fieldtypeid']=='Data') 
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{type: 'date-eu',targets: $index}";
                        $cont=$cont+1;
                    }
                    if (($column['desc']=='wwws')||($column['desc']=='pflash')) 
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{bVisible: false,targets: $index}";
                        $cont=$cont+1;
                    }
                    if ($column['desc']=='Con') 
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{sWidth: '10px',targets: $index}";
                        $cont=$cont+1;
                    }
                    if ($column['desc']=='Anteprima') 
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{sWidth: '150px',targets: $index}"; //TEMP TODO da sistemare. la width dipende dal formato dell'immagine di anteprima
                        $cont=$cont+1;
                    }
                    if ($column['desc']=='Valid') 
                    {
                        if($cont>0)
                        {
                            echo ',';
                        }
                        echo "{sWidth: '10px',targets: $index}";
                        $cont=$cont+1;
                    }
                    $index=$index+1;
                }
                  
                 ?>
            ],
		"bProcessing": false,
		"bServerSide": true,
		"sAjaxSource": "<?php echo site_url('sys_viewcontroller/ajax_search_result')."/".$data['archivio']; ?>/",
                "fnInitComplete": function() {
                                    <?=$oTable_name?>.fnAdjustColumnSizing();
                                   },
                "fnServerData": function ( sSource, aoData, fnCallback, oSettings ) {
                                <?php
                                if($contesto=='risultati_ricerca')
                                {
                                ?>
                                    var query=$('.scheda_dati_ricerca').find('#query').val();
                                <?php
                                }
                                if(($contesto=='records_linkedtable')||($contesto=='dashboard'))
                                {
                                ?>
                                    var query="<?=$query?>";
                                <?php
                                }
                                ?>
                                query=query.replace('%','|percent|');
                                aoData.push( { "name": "query", "value": query } );
                                oSettings.jqXHR = $.ajax( {
                                  "dataType": 'json',
                                  "type": "POST",
                                  "url": sSource,
                                  "data": aoData,
                                  "success": function(json){
                                      console.info(aoData);
                                      console.info('success');
                                                fnCallback(json);
                                            },
                                  "error":function (json){
                                      console.info(json)
                                  }
                                } )},
                "fnDrawCallback": function() {
                                <?php
                                
                                if($contesto=='risultati_ricerca')
                                {
                                ?>
                                    $('#<?=$id_datatable?>').dataTable()._fnScrollDraw();      
                                    /*var schedabody=$('#<?=$id_datatable?>').closest(".schedabody");
                                    var schedabody_height=$(schedabody).height();
                                    var dataTables_scrollHead_height=$(schedabody).find('.dataTables_scrollHead').height();
                                    $(schedabody).find(".dataTables_scrollBody").height(schedabody_height-dataTables_scrollHead_height-55);*/
                                    
                                    
                                    var datatable_records_container=$('#<?=$id_datatable?>').closest(".datatable_records_container");
                                    console.info('datatable container');
                                    console.info(datatable_records_container);
                                    var datatable_records_container_height=$(datatable_records_container).height();
                                    var dataTables_scrollHead_height=$(datatable_records_container).find('.dataTables_scrollHead').height();
                                    $(datatable_records_container).find(".dataTables_scrollBody").height(datatable_records_container_height-dataTables_scrollHead_height-55);
                                <?php
                                }
                                ?>
                                
                                <?php
                                
                                if($contesto=='dashboard')
                                {
                                ?>
                                    var datatable_records_container=$('#<?=$id_datatable?>').closest(".datatable_records_container");
                                    var datatable_records_container_height=$(datatable_records_container).height();
                                    var dataTables_scrollHead_height=$(datatable_records_container).find('.dataTables_scrollHead').height();
                                    $(datatable_records_container).find(".dataTables_scrollBody").height(datatable_records_container_height-85);
                                <?php
                                }
                                ?>
                                
                                <?php
                                if($contesto=='records_linkedtable')
                                {
                                ?>
                                    var datatable_records_container=$('#<?=$id_datatable?>').closest(".datatable_records_container");
                                    var datatable_records_container_height=$(datatable_records_container).height();
                                    var dataTables_scrollHead_height=$(datatable_records_container).find('.dataTables_scrollHead').height();
                                    $(datatable_records_container).find(".dataTables_scrollBody").height(datatable_records_container_height);
                                <?php
                                }
                                ?>
                               },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                                 $(nRow).click(function(){
                                     <?php
                                     $target=$table_settings['risultati_open'];
                                     $layout=$table_settings['scheda_layout'];
                                     $contesto_click=$contesto;
                                     if(($contesto=='records_linkedtable')||($contesto=='dashboard'))
                                     {
                                         $contesto_click='records_linkedtable';
                                         $target=$table_settings['linked_open'];
                                     }
                                     if($target=='popup')
                                     {
                                         $layout=$table_settings['popup_layout'];
                                     }
                                     
                                     ?>
                                     <?php
                                     if(($tableid=='ccl')&&(false))
                                     {
                                     ?>
                                         report_ccl(this,aData[0]);
                                     <?php
                                     }
                                     else
                                     {
                                     ?>
                                        TableRowSelected(<?=$oTable_name?>,nRow,aData, '<?=$tableid?>','<?=$contesto_click?>','<?=$target?>','<?=$layout?>');
                                     <?php
                                     }
                                     ?>
                                 })  
                                 <?php
                                 if(true/*$layout=='records_preview'*/){
                                 ?>
                                    datatable_records_preview(<?=$oTable_name?>,nRow, aData, iDataIndex,'<?=$tableid?>');
                                <?php
                                 }
                                 ?>
                                 <?php
                                 // TEMP
                                 if($tableid=='CANDID'){
                                 ?>
                                    custom_risultati_CANDID(<?=$oTable_name?>,nRow, aData, iDataIndex);

                                <?php
                                 }
                                 ?>
                                                 
                                <?php
                                 if($tableid=='aziende'){
                                 ?>
                                    custom_risultati_aziende(<?=$oTable_name?>,nRow, aData, iDataIndex,'<?=  date('Y-m-d')?>');
                                <?php
                                 }
                                 ?>
                                custom_risultati(<?=$oTable_name?>,nRow, aData, iDataIndex);
                                //custom_risultati_<?=$contesto?>_<?=$tableid?>(<?=$oTable_name?>,nRow, aData, iDataIndex);
                              },
                               "sDom": 'T<"clear">lfrtip',
                 "oTableTools": {
                        "sSwfPath": "<?php echo base_url("/assets/js/TableTools/swf/copy_csv_xls_pdf.swf") ?>",
			"aButtons": [],
                        <?php
                        if($contesto=="risultati_ricerca")
                        {
                        ?>
                        "sRowSelect": "single",
                        <?php
                        }
                        ?>
                        <?php
                        if(($contesto=="records_linkedtable")||($contesto=='dashboard'))
                        {
                        ?>
                        "sRowSelect": "multi",
                        <?php
                        }
                        ?>
                        "fnRowSelected": function ( nodes ) {
                               // TableRowSelected(<?=$oTable_name?>,nodes, '<?=$tableid?>','<?=$contesto?>');
                        }}               
                
                
        } );
$("#<?=$id_datatable?> tr").on('click',function(event) {
            //alert('row selected');
            //$("#table-elenco_<?=$contesto?> tbody tr").removeClass('row_selected');		
            //$(this).addClass('row_selected');
	});

//set_order($("#<?=$id_datatable?>"), '<?=$columns[2]['id']?>');
set_orderkey($("#<?=$id_datatable?>"), '<?=$columns[2]['id']?>');

});
    


</script>

<div id="<?=$datatable_records_container_id?>">
    <div id="blocco_datatable_<?=$contesto?>_<?=$tableid?>" class="block" style="height: 100%;width: 100%;" >

        <table id="<?=$id_datatable?>" CELLSPACING="0" class="datatable custom-table ui-widget" style="width: 100%;">
            <thead>
           <?php
            foreach ($columns as $key => $column) {
                ?>
                <th id="column_<?=$key?>" onclick="set_order(this,'<?=$column['id']?>')" data-fieldid="<?=$column['id']?>" data-fieldtypeid="<?=$column['fieldtypeid']?>" data-linkedtableid="<?=$column['linkedtableid']?>" ><?=$column['desc']?></th>
                <?php
            }
            ?>
            </thead> 
            <tbody>
            </tbody>
        </table>
    </div>
</div>
    

                            
