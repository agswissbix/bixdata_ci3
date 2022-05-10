//BASE
//
//

function imageExists(url, callback) {
    var img = new Image();
    img.onload = function() { callback(true); };
    img.onerror = function() { callback(false); };
    img.src = url;
  }
  
//MENU
function show_menu(){
    if($('#menu').data('visible')){ 
        hide_menu()
    }
    else
    {
        $('.btn_showmenu').css('background-image','url('+upimage+')');
        $('#menu').show(500,function(i){
            $('#menu').data('visible',true)
        });
    }

}


function hide_menu(){
    $('#pulsantino').attr('src',downimage);
    $('#menu').hide(500);
    $('#menu').data('visible',false)
}

function clickMenu(el,funzione){
    $.ajax({
        
                url: controller_url+funzione,
                dataType:'html',
                success:function(data){
                    $('#content_container').css('display', 'none')
                    $('#content_container').html('');
                    $('#content_container').html(data);
                    $('#content_container').show();
                    $(el).closest('.menu_list_button').mouseout();

                    
                    
                },
                error:function(){
                    alert('errore');
                }
            });
}

//CUSTOM work&work
function invio_mail_modulo(el){
    var scheda_corrente=$(el).closest('.scheda');
    var scheda_corrente_container=$(scheda_corrente).closest('.scheda_container');
    var cloned_scheda_record_container=$('#scheda_record_container_hidden').clone();
    popuplvl_new=1;
    var popuplvl_corrente=$(scheda_corrente).data('popuplvl');
    var popuplvl_new=popuplvl_corrente+1;
    var url=controller_url+'invio_mail_modulo';
    $.ajax({
        url: url,
        dataType:'html',
        success:function(data){              
            //carico la scheda nel relativo container
            cloned_scheda_record_container.html("");
            cloned_scheda_record_container.html(data);
                $('.wrapper').append(cloned_scheda_record_container);
                $(cloned_scheda_record_container).addClass('popup');
                bPopup[popuplvl_new] =$(cloned_scheda_record_container).bPopup({
                    onClose: function() { 
                        $(cloned_scheda_record_container).remove();
                    }
                });
            cloned_scheda_record_container.show();
        },
        error:function(){
            alert('errore');
            $('#dettaglio_record').html('fallimento');
        }
    });
}

function signout()
{
    var url= controller_url+'logout';
    window.location.replace(url);
}

function ajax_report_carenze(el)
{
    var anno = prompt("Anno?");
    var mese = prompt("Mese?");
    ajax_load_content(el,"report_carenza/"+anno+"/"+mese);
}

function ajax_load_content(el,funzione)
{
    $('#content_container').html('Caricamento...');
    $.ajax({
        
                url: controller_url+funzione,
                dataType:'html',
                success:function(data){
                    //$('#content_container').css('display', 'none')
                    
                    $('#content_container').css('display', 'none')
                    $('#content_container').html(data);
                    $('#content_container').fadeIn(2000);

                },
                error:function(){
                    alert('errore');
                }
            });
}

//RICERCA
//


function labelclickbak(el,scroll){
    if(typeof scroll==='undefined')
    {
        scroll=false;
    }
    el=$(el).closest('.tablelabel');
    console.info('fun labelclick');
    var tables_labelcontainer=$(el).next();
    var block_dati_labels_container=$(el).closest('.block_dati_labels_container');
    var block_dati_labels_container_height=$(block_dati_labels_container).height();
    

    
    
    
    var opened=$(el).data('opened');
    $(el).toggleClass("ui-state-active");
    if(opened)
    {
       $(el).data('opened',false);
       $(tables_labelcontainer).toggle( "blind", 500,function(){} );
       console.info('opened false');
    }
    else
    {
            $(block_dati_labels_container).scrollTo(el,300,
            {
                offset: -(block_dati_labels_container_height/3),
              }
            );
        $(el).data('opened',true);
        console.info('opened false');
        
        var loaded=$(el).data('loaded');
        if(loaded)
        {
            $(tables_labelcontainer).toggle( "blind", 500,function(){} );
            if(scroll)
            {
                $(block_dati_labels_container).scrollTo(el,500);
            }
            //tempodemo$(tables_labelcontainer).find('.first').first().focus();
        }
        else
        {
            tablesloading=true;
            
            $(tables_labelcontainer).toggle( "blind", 500,function(){} );
            load_tables_labelcontainer(tables_labelcontainer,'null',scroll);
            if((typeof(block_dati_labels_container) !== 'undefined')||(typeof(el) !== 'undefined'))
            {
                waittablesloading(block_dati_labels_container,el,scroll);
            }
            $(el).data('loaded','true');
        }
         //$(tables_labelcontainer).find('.first').first().focus();
    }
    

}

function labelclick(el,scroll){
    console.info('fun labelclick');
    if(typeof scroll==='undefined')
    {
        scroll=false;
    }
    el=$(el).closest('.tablelabel');
    
    var tables_labelcontainer=$(el).next();
    

    
    load_tables_labelcontainer(tables_labelcontainer,'null',scroll);
    $(tables_labelcontainer).toggle( "blind", 500,function(){} );
    var opened=$(el).data('opened');
    $(el).toggleClass("ui-state-active");
    if(opened)
    {
       //$(el).data('opened',false);
       //$(tables_labelcontainer).toggle( "blind", 500,function(){} );
       //console.info('opened false');
    }
    else
    {
          
        

        //load_tables_labelcontainer(tables_labelcontainer,'null',scroll);

        //$(tables_labelcontainer).toggle( "blind", 500,function(){} );

        $(el).data('opened','true');
        $(el).data('loaded','true');
    }
    

}

function showall_fields(el)
{
    var tables_labelcontainer=$(el).closest('.tables_container');
    load_tables_labelcontainer(tables_labelcontainer,'showall',false);
}

function waittablesloading(block_dati_labels_container,el,scroll){
    if(typeof scroll==='undefined')
    {
        scroll=true;
    }
    if(!tablesloading)
    {
        if(scroll)
        {
            $(block_dati_labels_container).scrollTo(el,500);
        }
        waittablesloading_counter=0;
        return;
    }
    else{
        setTimeout(function(){
            waittablesloading_counter=waittablesloading_counter+1;
            console.info("waitsaving_counter:"+waittablesloading_counter)
            if(waittablesloading_counter>10)
            {
                if(scroll)
                {
                    $(block_dati_labels_container).scrollTo(el,500);
                }
                tablesloading=false;
                waittablesloading_counter=0;
                return;
            }
            else
            {
                waittablesloading(block_dati_labels_container,el,scroll);
            }
        },500);
    }
}

//APERTURA SCHEDA LABEL
function labelclick2(el){
        console.info('fun labelclick2');
        var label=$(el).prev();
     /*   var block_dati_labels_container=$(label).closest('.block_dati_labels_container');
    var opened=$(label).data('opened');
    if(opened)
    {
       $(label).data('opened',false); 
       console.info('opened false');
    }
    else
    {
        $(label).data('opened',true);
        console.info('opened true');
        $(block_dati_labels_container).scrollTo(label,500);
    }*/
        
        var loaded=$(label).data('loaded');
        if(!loaded)
        {
           load_tables_labelcontainer(el,'null');
        }
        else
        {
            //tempdemo$(el).find('.first').first().focus();
        }
}

//FIELDS_TABLE
//cambio selezione in una lookup table
function select_changed(el) {
    alert('select changed');
            var selectedoption=$('#'+$(el).attr('id')+' option:selected');
            var selectedvalue=selectedoption.attr('data-linkvalue');
            var selectedlink=selectedoption.attr('data-link');
            var selectedlinkfield=selectedoption.attr('data-linkfield');
            var table_container=$(el).closest('.table_container');
            if(selectedlink=='true')
                {
                    var finded_select=table_container.find('[data-linkedfield_select*="'+selectedlinkfield+'"]');
                    $(finded_select).prop('selectedIndex', 0);    
                    var next_finded=finded_select.next();
                    if(next_finded.attr("id")!=finded_select.attr("id"))
                    {
                        var cloned_select=finded_select.clone();
                        cloned_select.css("display","none");
                        cloned_select.attr("data-linkedfield_select","");
                        cloned_select.attr("name","");
                        finded_select.after(cloned_select);
                        var next_finded=cloned_select;
                    }
                   // var cloned_select=$(next_finded).clone();
                    //cloned_select.attr("data-linkedfield_select",selectedlinkfield);
                    $(finded_select).html($(next_finded).html());
                    finded_select.find("option").each(function(i) {
                        //$(this).show();
                        /*if ($(this).parent("span").length) {
                            $(this).unwrap();
                        }*/
                       var templinkedvalue=$(this).attr('data-linkedvalue');

                               if(templinkedvalue!==undefined)
                               {
                               if((templinkedvalue.toUpperCase()!=selectedvalue.toUpperCase())&&($(this).attr('class')!='emptyOption'))
                                    {
                                       $(this).remove();
                                       //$(this).hide();
                                       //$(this).wrap('<span/>');
                                    }
                                }
                               
                           
                    });
                }
            $(el).removeClass("notselected");
            $(el).addClass("selected");
            //field_blurred(el);
        }
        
function field_blurred(el)
{
    console.info('field_blurred('+el+')');
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var funzione=$(block_dati_labels).data('funzione');
    var mastertableid=$(block_dati_labels).data('tableid');
    var masterrecordid=$(block_dati_labels).data('recordid');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var fieldValueContainer=$(el).closest(".fieldValueContainer");
    var lastval= $(el).data('lastval');
    $(fieldValueContainer).removeClass('fieldValueContainerFocused');
    var val=$(el).val();
    if((val!=lastval)&&(val!='loading')&&(!$(el).hasClass('fieldLookup'))&&(!$(el).hasClass('fieldUtente')))
    {
        //lastval=$(el).attr('id')+':'+$(el).val();
        $(el).data('lastval',val);
        field_changed(el);
    }
    if((scheda_container=='scheda_dati_inserimento')&&(masterrecordid!=null))
    {
        timeoutID = setTimeout(function () {
            var focused = $( document.activeElement );
            var blurred_container=$(el).closest('.table_container');
            var focused_container=$(focused).closest('.table_container');
            if($(blurred_container).attr('id')!=$(focused_container).attr('id'))
            {
                var table_container=$(el).closest('.table_container');
                var tableid=$(table_container).data('tableid');
                var recordid=$(table_container).data('recordid');
                salva_modifiche_record(el,tableid,recordid);

            }
        }, 50);
    }
}

function calc_duratalavoro(orainiziomattina,orafinemattina,orainiziopomeriggio,orafinepomeriggio)
    {
        var diff_mattina=calc_diff_ore(orainiziomattina,orafinemattina);
        var diff_pomeriggio=calc_diff_ore(orainiziopomeriggio,orafinepomeriggio);
        var diff_m = diff_mattina.split(':');
        var diff_p = diff_pomeriggio.split(':');
        var duratalavoro_m=parseInt(diff_m[1])+parseInt(diff_p[1]);
        var duratalavoro_h=parseInt(diff_m[0])+parseInt(diff_p[0]);
        duratalavoro_m=duratalavoro_m-60;
        if(duratalavoro_m<=0)
        {
            duratalavoro_m=duratalavoro_m*(-1);
        }
        else
        {
            duratalavoro_h=duratalavoro_h+1;
        }
    }
    function calc_diff_ore(start,end)
    {
        var s = start.split(':');
        var e = end.split(':');
        var min = e[1]-s[1];
        var hour_carry = 0;
        if(min < 0){
            min += 60;
            hour_carry += 1;
        }
        var hour = e[0]-s[0]-hour_carry;
        min = ((min/60)*100).toString()
        var diff = hour + ":" + min.substring(0,2);
        return diff;
    }
  
function field_update_showedby(field_input)
{
    console.info('funzione:field_changed('+field_input+')');
    
    var block_dati_labels=$(field_input).closest('.block_dati_labels');
    var table_block=$(field_input).closest('.table_block');
    var funzione=$(table_block).data('funzione');
    var mastertableid=$(block_dati_labels).data('tableid');
    var placeholder=$(field_input).attr("placeholder");
    var value=$(field_input).val();
    var table_container=$(field_input).closest(".table_container");
    var tableid=$(table_container).data('tableid');
    var recordid=$(table_container).data('recordid');
    var fieldcontainer=$(field_input).closest(".fieldcontainer");
    var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
    var fieldid=$(fieldcontainer).data('fieldid');
    var fieldValue0=$(fieldcontainer).find('.fieldValue0');
    var fieldLabel_html=$(fieldcontainer).find('.fieldlabel').html();
    var scheda_dati_ricerca=$(field_input).closest('.scheda_dati_ricerca');
    var form_riepilogo=$(scheda_dati_ricerca).find('#form_riepilogo');
    var form_riepilogo_fields=$(form_riepilogo).find('.form_riepilogo_fields');
    var fieldValue1=$(fieldcontainer).find('.fieldvalue1');
    
    if(fieldtypeid=='Lookuptable')
    {
        var selectedvalue=fieldValue0.val();
        var fields_showedby=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"]');
        $(fields_showedby).each(function(i){
            $(this).hide();
        })
        var fields_showedby_value=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"][data-showedbyvalue*="'+selectedvalue+'"]');
        $(fields_showedby_value).each(function(i){
            $(this).show();
        })
    }
}
function field_changed(field_input)
{
    console.info('funzione:field_changed('+field_input+')');
    
    var block_dati_labels=$(field_input).closest('.block_dati_labels');
    var table_block=$(field_input).closest('.table_block');
    var funzione=$(table_block).data('funzione');
    var mastertableid=$(block_dati_labels).data('tableid');
    var placeholder=$(field_input).attr("placeholder");
    var value=$(field_input).val();
    var table_container=$(field_input).closest(".table_container");
    var tableid=$(table_container).data('tableid');
    var recordid=$(table_container).data('recordid');
    var fieldcontainer=$(field_input).closest(".fieldcontainer");
    var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
    var fieldid=$(fieldcontainer).data('fieldid');
    var fieldValue0=$(fieldcontainer).find('.fieldValue0');
    var fieldLabel_html=$(fieldcontainer).find('.fieldlabel').html();
    var scheda_dati_ricerca=$(field_input).closest('.scheda_dati_ricerca');
    var form_riepilogo=$(scheda_dati_ricerca).find('#form_riepilogo');
    var form_riepilogo_fields=$(form_riepilogo).find('.form_riepilogo_fields');
    var fieldValue1=$(fieldcontainer).find('.fieldvalue1');
    //if(($(field_input).hasClass('fieldValue0'))&&($(field_input).hasClass('fieldRange'))&&($(field_input).val()!='')&&($(fieldValue1).val()==''))
    if(($(field_input).hasClass('fieldValue0'))&&($(field_input).hasClass('fieldRange'))&&($(field_input).val()!=''))
    {
        $(fieldValue1).val($(field_input).val());
    }
    if(funzione=='ricerca')
    {
        var checkbox_mantienivista=$(scheda_dati_ricerca).find('.checkbox_mantienivista');
        if(!$(checkbox_mantienivista).is(':checked'))
        {
            var view_selected=$(scheda_dati_ricerca).find('.view_selected');
            $(view_selected).data('saved_view_id','');
        }
        
        console.info('field_changed:ricerca');
        change_field_name(fieldcontainer,'search');
        
        //update_query(field_input,mastertableid);
    }
    if(funzione=='inserimento')
    {
        console.info('field_changed:inserimento');
        change_field_name(fieldcontainer,'insert');
    }
    if((funzione=='modifica')||(funzione=='scheda'))
    {
        console.info('field_changed:modifica o scheda');
        change_field_name(fieldcontainer,'update');
        //autosave(field_input,tableid,recordid);
    }
    
    if(fieldtypeid=='Lookuptable')
    {
        var selectedvalue=fieldValue0.val();
        var fields_showedby=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"]');
        $(fields_showedby).each(function(i){
            $(this).hide();
        })
        var fields_showedby_value=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"][data-showedbyvalue*="'+selectedvalue+'"]');
        $(fields_showedby_value).each(function(i){
            $(this).show();
        })
    }
    
    $(form_riepilogo_fields).html('');
    $('#risultati_riepilogo_filtri').html('');
    var form_riepilogo_savedview=$(scheda_dati_ricerca).find('.form_riepilogo_savedview').find('span').html();
    scheda_dati_ricerca.find('.fieldcontainer').each(function(i)
        {
            var fieldValue0=$(this).find('.fieldValue0').val();
            var fieldValue1=$(this).find('.fieldValue1').val();
            var field_param=$(this).find('.field_param').val();
            var fieldLabel_html=$(this).find('.fieldlabel').html();
            if(fieldValue0!='')
            {   
                 $(form_riepilogo_fields).append("<span style='color:#545454;font-weight: bold;'>"+fieldLabel_html+"</span>: "+fieldValue0+''); 
                 $('#risultati_riepilogo_filtri').append(form_riepilogo_savedview);
                 $('#risultati_riepilogo_filtri').append("<span style='color:#545454;font-weight: bold;'>"+fieldLabel_html+"</span>: "+fieldValue0+''); 
            }
        });
        
   
}

function field_changedBAK(field_input)
{
    console.info('funzione:field_changed('+field_input+')');
    //$('#btnMostraTutti').html('Annulla Filtri');
    if($(field_input).attr('id')!='saved_view_select')
    {
        $('#saved_view_select').val('');
    }
    var block_dati_labels=$(field_input).closest('.block_dati_labels');
    var table_block=$(field_input).closest('.table_block');
    var funzione=$(table_block).data('funzione');
    var mastertableid=$(block_dati_labels).data('tableid');
    var masterrecordid=$(block_dati_labels).data('recordid');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var placeholder=$(field_input).attr("placeholder");
    var tables_container=$(field_input).closest(".tables_container");
    var table_container=$(field_input).closest(".table_container");
    var tableid=$(table_container).data('tableid');
    var recordid=$(table_container).data('recordid');
    var fieldcontainer=$(field_input).closest(".fieldcontainer");
    var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
    var fieldid=$(fieldcontainer).data('fieldid');
    var fieldValue0=$(fieldcontainer).find('.fieldValue0');
    
    var fieldValue1=$(fieldcontainer).find('.fieldvalue1');
    //if(($(field_input).hasClass('fieldValue0'))&&($(field_input).hasClass('fieldRange'))&&($(field_input).val()!='')&&($(fieldValue1).val()==''))
    if(($(field_input).hasClass('fieldValue0'))&&($(field_input).hasClass('fieldRange'))&&($(field_input).val()!=''))
    {
        $(fieldValue1).val($(field_input).val());
    }
    if(funzione=='ricerca')
    {
        change_field_name(fieldcontainer,'search');
        //custom Work&Work
        /*if((placeholder=="parlato")||(placeholder=="letto")||(placeholder=="scritto"))
        {
            var value=$(field_input).val();
            if(value=='O')
            {
                or_field_onclick(field_input,true,'M');
            }
            if(value=='B')
            {
                or_field_onclick(field_input,true,'O');
                or_field_onclick(field_input,true,'M');
            }
            if(value=='S')
            {
                or_field_onclick(field_input,true,'B');
                or_field_onclick(field_input,true,'O');
                or_field_onclick(field_input,true,'M');
            }

        }*/
        var tables_container_cloned=tables_container.clone();
        tables_container_cloned.find("button").remove();
        /*tables_container_cloned.find('[name*="value"]').each(function(i)
        {
            var valore=$(block_dati_labels).find("#"+$(this).attr("id")).val();
            $(this).val(valore);
            var fieldscontainer=$(this).closest(".fieldscontainer");
            var fieldcontainer=$(this).closest(".fieldcontainer");
            var field_param=$(fieldcontainer).find('.field_param');
            var fieldvalueContainer=$(this).closest(".fieldValueContainer");
            if(($(this).val()=='')&&($(field_param).val()==''))
            {   
                $(fieldcontainer).remove();
            }
        });*/
        tables_container_cloned.find('.fieldcontainer').each(function(i)
        {
            var fieldValue0=$(this).find('.fieldValue0');
            var fieldValue1=$(this).find('.fieldValue1');
            var valore0=$(block_dati_labels).find("#"+$(fieldValue0).attr("id")).val();
            var valore1=$(block_dati_labels).find("#"+$(fieldValue1).attr("id")).val();
            $(fieldValue0).val(valore0);
            $(fieldValue1).val(valore1);
            //var fieldscontainer=$(this).closest(".fieldscontainer");
            //var fieldcontainer=$(this).closest(".fieldcontainer");
            var field_param=$(this).find('.field_param');
            //var fieldvalueContainer=$(this).closest(".fieldValueContainer");
            if(($(fieldValue0).val()=='')&&($(fieldValue1).val()=='')&&($(field_param).val()==''))
            {   
                $(this).remove();
            }
        });
        tables_container_cloned.prepend("<h3>"+tables_container_cloned.attr("data-labelName")+"</h3>");
        var scheda_dati_ricerca=$(field_input).closest('.scheda_dati_ricerca');
        var form_riepilogo=$(scheda_dati_ricerca).find('#form_riepilogo');
        var finded_container=$(form_riepilogo).find("#"+tables_container.attr("id"));
        if(finded_container.size()>0)
        {
            finded_container.replaceWith(tables_container_cloned);
        }
        else
        {

            $(form_riepilogo).append(tables_container_cloned);                    
        }
        update_query(field_input,mastertableid);
    }
    if(funzione=='inserimento')
    {
        change_field_name(fieldcontainer,'insert');
        console.info('field_changed:inserimento');
    }
    if((funzione=='modifica')||(funzione=='scheda'))
    {
        change_field_name(fieldcontainer,'update');
        autosave(field_input,tableid,recordid);
    }
    
    if(fieldtypeid=='Lookuptable')
    {
        var selectedvalue=fieldValue0.val();
        var fields_showedby=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"]');
        $(fields_showedby).each(function(i){
            $(this).hide();
        })
        var fields_showedby_value=$(table_container).find('[data-showedbyfieldid*="'+fieldid+'"][data-showedbyvalue*="'+selectedvalue+'"]');
        $(fields_showedby_value).each(function(i){
            $(this).show();
        })
    }
}

function change_field_name(fieldcontainer,fun)
{
    var fieldscontainer=$(fieldcontainer).closest('.fieldscontainer');
    $(fieldscontainer).find('.fieldInput').each(function(i){
        var original_name=$(this).attr('name');
        var new_name=original_name.replace('[null]', '['+fun+']');
        $(this).attr('name',new_name);
    })
}
/*
function autoOr(el,value){
        
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldcontainer_cloned=fieldcontainer.clone(true,true);
        $(fieldcontainer_cloned).addClass('fieldcontainer_cloned');
        var fieldscontainer=fieldcontainer.closest(".fieldscontainer");
        var counter=fieldscontainer.attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        fieldscontainer.attr("data-counter",counter_modified);
        var index_original=fieldcontainer.attr("data-index");
        var index_modified="f_"+counter_modified;
        fieldcontainer_cloned.attr("data-index",index_modified);
        id_original=$(fieldcontainer).attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        fieldcontainer_cloned.attr("id",id_modified);
        var name_original;
        var name_modified;
        var id_original;
        var id_modified;
        fieldcontainer_cloned.find('[id*="'+index_original+'"]').each(function(i) {
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
            if($(this).is('[name]'))
            {
                name_original=$(this).attr("name");
                name_modified=name_original.replace(index_original,index_modified);
                $(this).attr("name",name_modified);
            }
        });
        var field_cloned=$(fieldcontainer_cloned).find('.field');
        $(field_cloned).val(value);
        $(field_cloned).show();
        var field_param_cloned=$(fieldcontainer_cloned).find('.field_param');
        $(field_param_cloned).val("");
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');
        $(field_layer_cloned).hide();
        $(field_param_cloned).val('or');
        $(fieldcontainer_cloned).find('.or_layer').show();
        fieldcontainer_cloned.attr('data-cloned', 'true');
        $(fieldcontainer_cloned).hide();
        $(fieldcontainer).after(fieldcontainer_cloned);
     }
    */
    
function clear_field_click(el)
{
    var value_container=$(el).parent();
    var field=$(value_container).find('.field');
    var label=$(value_container).find('.label');
    var clear_field=$(value_container).find('.clear_field');
    $(field).val('');
    $(label).hide();
    $(clear_field).hide();
    $(field).width($(field).width()+$(label).width());
    field_changed(el);
    
}
//RISULTATI RICERCA
function TableRowSelected(table,nRow,aData,tableid,contesto,target,layout){
    var dom_row=nRow;
    var data_row = aData;
    var recordid=data_row[0];
    //CUSTOM WORK&WORK
    var navigator_field=data_row[2];
    if(tableid=='CANDID')
        {
            var navigator_index=Lookup_RealColumnIndex(table, 'Cognome')
            navigator_field=data_row[navigator_index];
        }
    if(tableid=='AZIEND')
        {
            var navigator_index=Lookup_RealColumnIndex(table, 'Ragione Sociale')
            navigator_field=data_row[navigator_index];
        }
    if(contesto=='risultati_ricerca')
    {
        //record_click(nRow,recordid,tableid,navigator_field);
        apri_scheda_record(nRow,tableid,recordid,target,layout);
    }
    if(contesto=='records_linkedtable')
    {
        if(target=='down')
        {
            ajax_load_fields_record_linkedtable(dom_row,tableid,'scheda',recordid,data_row.length);
        }
        else
        {
            if(tableid=='contratti')
            {
                //apri_scheda_record(dom_row,tableid,recordid,target,'allargata');
                var popup_container=$(dom_row).closest('.popup');
                if($(popup_container).hasClass('bPopup_linkedrecords'))
                {
                    ajax_compilazione_contratto_missione(dom_row,recordid);
                }
                else
                {
                    apri_scheda_record(dom_row,tableid,recordid,target,'allargata');
                }
            }
            else
            {
                apri_scheda_record(dom_row,tableid,recordid,target,'allargata');
            }
           
        }
        
        
    }
    //table.fnAdjustColumnSizing();
   
}

function TableRowSelectedBAK(table,nodes,tableid,contesto){
    var dom_row=nodes[0];
    var data_row = table.fnGetData( nodes[0] );
    var recordid=data_row[0];
    //CUSTOM WORK&WORK
    var navigator_field=data_row[2];
    if(tableid=='CANDID')
        {
            var navigator_index=Lookup_RealColumnIndex(table, 'Cognome')
            navigator_field=data_row[navigator_index];
        }
    if(tableid=='AZIEND')
        {
            var navigator_index=Lookup_RealColumnIndex(table, 'Ragione Sociale')
            navigator_field=data_row[navigator_index];
        }
    if(contesto=='risultati_ricerca')
    {
        record_click(nodes[0],recordid,tableid,navigator_field);
    }
    if(contesto=='records_linkedtable')
    {
        ajax_load_fields_record_linkedtable(dom_row,tableid,'scheda',recordid,data_row.length);
    }
    //table.fnAdjustColumnSizing();
   
}
function apri_linkedtable(el,recordid_,tableid,navigatorField){
    set_pinned();
    var tr=$(el).closest('tr');
    var prev_tr=$(tr).prev();
    var array_td=$(prev_tr).find('td');
    var td=$(array_td)[0];
    var navigatorField=$(td).html();
    record_click(el,recordid_,tableid,navigatorField);
    set_pinned();
}
function apri_mastertable(el,recordid_,tableid,navigatorField){
    set_pinned();
    record_click(el,recordid_,tableid,navigatorField);
    set_pinned();
}

var global_tableid;

function record_click(el,recordid,tableid,navigatorField,table) {
            global_tableid=tableid;
            
            apri_scheda_record(el,tableid,recordid,'right','standard_dati','risultati_ricerca');
            /*if((ultimascheda=="")||(pinned==true))
            {
                pinned=false;
                //genera navigatore
                
                var scroll_aggiuntivo=scheda_record_container_width*(schedaid-1);
                var newsposition=scheda_dati_ricerca_container_width*1+scheda_risultati_compatta_width*1+scroll_aggiuntivo;
                cloned_nav_button.attr("data-position", newsposition);
                lastnav=cloned_nav_button;
                $('#nav_risultati').after(cloned_nav_button);
                cloned_nav_button.show();
            }
            else
            {
                var container_ultimascheda=$('#'+ultimascheda);
                $(container_ultimascheda).remove();
                var newwidth=$('#ricerca_subcontainer').width()-scheda_record_container_width*2;
                $('#ricerca_subcontainer').width(newwidth);  
                var content=$('#'+ultimascheda);
                nuovoid_scheda=$(content).attr('id');
                var cloned_nav_button=$('#nav_scheda_hidden').clone();
                cloned_nav_button.attr("id", "nav_"+nuovoid_scheda);
                cloned_nav_button.attr("data-target_id", nuovoid_scheda);
                cloned_nav_button.html("");
                cloned_nav_button.html(navigatorField);
                lastnav.replaceWith(cloned_nav_button);
                lastnav=cloned_nav_button;
                cloned_nav_button.show();
                content.html("");
                content.html(data);
            }*/
            
                    
           
        
}
// APRI RECORD
function apri_record(el,tableid,recordid,target,layout,origine,funzione,callback)
{
    alert('test');
    console.info('fun: apri_record');
    if (typeof funzione === 'undefined') { funzione = 'scheda'; }
    if((funzione=='inserimento')&&(target=='down'))
    {
        linked_table_add(el);
    }
    else
    {
        var scheda_record=$(el).closest('.scheda_record');
        var scheda_corrente=$(el).closest('.scheda');
        var scheda_corrente_container=$(scheda_corrente).closest('.scheda_container');
        var cloned_scheda_record_container=$('#scheda_record_container_hidden').clone();
        if(origine==null)
        {
            origine='home_menu';
        }
        if(origine=='home_menu')
        {
            $(cloned_scheda_record_container).data('origine',origine);
        }
        if(origine=='risultati_ricerca')
        {
            var origine_id=$(el).closest('.scheda_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id);
        }
        if(origine=='linked_table')
        {
            var scheda_record_origine=$(el).closest('.scheda_record');
            var origine_tableid=$(scheda_record_origine).data('tableid');
            var origine_recordid=$(scheda_record_origine).data('recordid');
            var origine_id=$(el).closest('.tables_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id); 
            $(cloned_scheda_record_container).data('origine_tableid',origine_tableid);
            $(cloned_scheda_record_container).data('origine_recordid',origine_recordid);

        }
        if(origine=='records_linkedmaster')
        {
            var origine_id=$(el).closest('.table_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id); 
        }
        popuplvl_new=0;
        if(target=='popup')
        {
            var popuplvl_corrente=$(scheda_record).data('popuplvl');
            if(isNaN(popuplvl_corrente))
            {
                popuplvl_new=1;
            }
            else
            {
                var popuplvl_new=popuplvl_corrente+1;
            }
            
        }
        var url=controller_url+'ajax_load_block_scheda_record/'+tableid+'/'+recordid+'/'+layout+'/'+target+'/'+popuplvl_new+'/'+funzione+'/'+origine_tableid+'/'+origine_recordid;
        $.ajax({
            url: url,
            dataType:'html',
            success:function(data){              



                //carico la scheda nel relativo container
                cloned_scheda_record_container.html("");
                cloned_scheda_record_container.html(data);
                var cloned_scheda=$(cloned_scheda_record_container).find('.scheda');
                target=$(cloned_scheda).data('target');
                layout=$(cloned_scheda).data('layout');
                //gestisco l'id della nuova scheda
                //schedaid=schedaid+1;
                var id_cloned_scheda=$(cloned_scheda).attr('id');
                var nuovoid_scheda_container='scheda_record_container_'+id_cloned_scheda;
                cloned_scheda_record_container.attr('id', nuovoid_scheda_container);

                var cloned_nav_button=$('#nav_scheda_hidden').clone();
                cloned_nav_button.attr("id", "nav_"+nuovoid_scheda_container);
                cloned_nav_button.attr("data-target_id", nuovoid_scheda_container);
                cloned_nav_button.html("");
                var navigatorField=$(cloned_scheda).data('navigatorfield');
                cloned_nav_button.html(tableid+': '+navigatorField);





                if(target=='left')
                {
                    $(scheda_corrente_container).before(cloned_scheda_record_container);
                }
                if(target=='right')
                {
                    $('#scheda_dati_ricerca_container').data('displayed',false);
                    //allargo il div del content in modo da farci stare la nuova scheda
                    var newwidth=$('#ricerca_subcontainer').width()+scheda_record_container_width+50;
                    $('#ricerca_subcontainer').width(newwidth);  
                    //$('#content_ricerca').scrollTo($(scheda_corrente_container),500);

                        var scheda_successiva_container=$(scheda_corrente_container).next();
                        if($(scheda_successiva_container).attr('id')!='scheda_record_container_hidden')
                        {
                            var pinned=$(scheda_successiva_container).find('.scheda').data('pinned');
                            if(pinned)
                            {
                                $('#nav_risultati').after(cloned_nav_button);

                            }  
                            else
                            {
                                $(lastnav).after(cloned_nav_button);
                               $(scheda_successiva_container).remove();
                               $(lastnav).remove(); 
                            }
                        }

                    $(scheda_corrente_container).after(cloned_scheda_record_container);
                    

                }
                if(target=='popup')
                {
                    //var popuplvl_corrente=$(scheda_corrente).data('popuplvl');
                    //var popuplvl_new=popuplvl_corrente+1;

                    //$(cloned_scheda).data('popuplvl',popuplvl_new);
                    console.info(recordid);
                    var wrapper=$('.wrapper');
                    $('.wrapper').append(cloned_scheda_record_container);
                    $(cloned_scheda_record_container).addClass('popup');

                    
                    if(layout=='standard_dati')
                    {
                        var pop_width_variable=70- (popuplvl_new*5);
                        $(cloned_scheda_record_container).addClass('popup_'+pop_width_variable);
                    }
                    //$(cloned_scheda_record_container).css('cssText', 'height: calc(90% - '+minus+'px) !important;width: calc('+popup_width+' - '+minus+'px) !important;');
                    bPopup[nuovoid_scheda_container] =$(cloned_scheda_record_container).bPopup({
                        onClose: function() { 
                            $(cloned_scheda_record_container).remove();
                        }
                    });




                }
                if(target=='self')
                {

                    var scheda_container_id=$(scheda_corrente_container).attr('id');
                    try 
                    {
                        bPopup[scheda_container_id].close();
                        $(scheda_corrente_container).replaceWith(cloned_scheda_record_container);
                        $(cloned_scheda_record_container).bPopup();
                    }
                    catch(err)
                    {
                        $(scheda_corrente_container).replaceWith(cloned_scheda_record_container);
                        console.info('nopopup');
                    }


                    $(lastnav).after(cloned_nav_button);
                    $(lastnav).remove();
                }

                $(cloned_nav_button).show();
                //lastnav=cloned_nav_button;
                cloned_scheda_record_container.show();
                if (typeof callback !== 'undefined')
                {
                    var el_cb=$(cloned_scheda_record_container).find('.btn_loadToJDocOnlineCV');
                    callback(el_cb);
                }


            },
            error:function(){
                alert('errore');
                $('#dettaglio_record').html('fallimento');
            }
        });
    }
    
}
//APRI SCHEDA RECORD
        
function apri_scheda_record(el,tableid,recordid,target,layout,origine,funzione,callback)
{
    
    console.info('fun: apri_scheda_record');
    if (typeof funzione === 'undefined') { funzione = 'scheda'; }
    if((funzione=='inserimento')&&(target=='down'))
    {
        linked_table_add(el);
    }
    else
    {    
        var scheda_record=$(el).closest('.scheda_record');
        var scheda_corrente=$(el).closest('.scheda');
        var scheda_corrente_container=$(scheda_corrente).closest('.scheda_container');
        var cloned_scheda_record_container=$('#scheda_record_container_hidden').clone();
        if(origine==null)
        {
            origine='home_menu';
        }
        if(origine=='home_menu')
        {
            $(cloned_scheda_record_container).data('origine',origine);
        }
        if(origine=='risultati_ricerca')
        {
            var origine_id=$(el).closest('.scheda_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id);
        }
        if(origine=='linked_table')
        {
            var scheda_record_origine=$(el).closest('.scheda_record');
            var origine_tableid=$(scheda_record_origine).data('tableid');
            var origine_recordid=$(scheda_record_origine).data('recordid');
            var origine_id=$(el).closest('.tables_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id); 
            $(cloned_scheda_record_container).data('origine_tableid',origine_tableid);
            $(cloned_scheda_record_container).data('origine_recordid',origine_recordid);

        }
        if(origine=='records_linkedmaster')
        {
            var origine_id=$(el).closest('.table_container').attr('id');
            $(cloned_scheda_record_container).data('origine',origine);
            $(cloned_scheda_record_container).data('origine_id',origine_id); 
        }
        popuplvl_new=0;
        if(target=='popup')
        {
            var popuplvl_corrente=$(scheda_record).data('popuplvl');
            if(isNaN(popuplvl_corrente))
            {
                popuplvl_new=1;
            }
            else
            {
                var popuplvl_new=popuplvl_corrente+1;
            }
            
        }
        var url=controller_url+'ajax_load_block_scheda_record/'+tableid+'/'+recordid+'/'+layout+'/'+target+'/'+popuplvl_new+'/'+funzione+'/'+origine_tableid+'/'+origine_recordid;
        console.info('URL: '+url);
        $.ajax({
            url: url,
            dataType:'html',
            success:function(data){              



                //carico la scheda nel relativo container
                cloned_scheda_record_container.html("");
                cloned_scheda_record_container.html(data);
                var cloned_scheda=$(cloned_scheda_record_container).find('.scheda');
                target=$(cloned_scheda).data('target');
                layout=$(cloned_scheda).data('layout');
                //gestisco l'id della nuova scheda
                //schedaid=schedaid+1;
                var id_cloned_scheda=$(cloned_scheda).attr('id');
                var nuovoid_scheda_container='scheda_record_container_'+id_cloned_scheda;
                cloned_scheda_record_container.attr('id', nuovoid_scheda_container);

                var cloned_nav_button=$('#nav_scheda_hidden').clone();
                cloned_nav_button.attr("id", "nav_"+nuovoid_scheda_container);
                cloned_nav_button.attr("data-target_id", nuovoid_scheda_container);
                cloned_nav_button.html("");
                var navigatorField=$(cloned_scheda).data('navigatorfield');
                cloned_nav_button.html(tableid+': '+navigatorField);





                if(target=='left')
                {
                    $(scheda_corrente_container).before(cloned_scheda_record_container);
                }
                if(target=='right')
                {
                    //$('#scheda_dati_ricerca_container').data('displayed',false);
                    //allargo il div del content in modo da farci stare la nuova scheda
                    //var newwidth=$('#ricerca_subcontainer').width()+scheda_record_container_width+50;
                    //$('#ricerca_subcontainer').width(newwidth);  
                    //$('#content_ricerca').scrollTo($(scheda_corrente_container),500);
                    //$('#scheda_dati_ricerca_container').hide(); 

                        var scheda_successiva_container=$(scheda_corrente_container).next();
                        if($(scheda_successiva_container).attr('id')!='scheda_record_container_hidden')
                        {
                            var pinned=$(scheda_successiva_container).find('.scheda').data('pinned');
                            if(pinned)
                            {
                                $('#nav_risultati').after(cloned_nav_button);

                            }  
                            else
                            {
                                $(lastnav).after(cloned_nav_button);
                               $(scheda_successiva_container).remove();
                               $(lastnav).remove(); 
                            }
                        }

                    $(scheda_corrente_container).after(cloned_scheda_record_container);
                    

                }
                if(target=='popup')
                {
                    //var popuplvl_corrente=$(scheda_corrente).data('popuplvl');
                    //var popuplvl_new=popuplvl_corrente+1;

                    //$(cloned_scheda).data('popuplvl',popuplvl_new);
                    console.info(recordid);
                    var wrapper=$('.wrapper');
                    $('.wrapper').append(cloned_scheda_record_container);
                    $(cloned_scheda_record_container).addClass('popup');

                    
                    if(layout=='standard_dati')
                    {
                        var pop_width_variable=70- (popuplvl_new*5);
                        $(cloned_scheda_record_container).addClass('popup_'+pop_width_variable);
                    }
                    //$(cloned_scheda_record_container).css('cssText', 'height: calc(90% - '+minus+'px) !important;width: calc('+popup_width+' - '+minus+'px) !important;');
                    bPopup[nuovoid_scheda_container] =$(cloned_scheda_record_container).bPopup({
                        onClose: function() { 
                            $(cloned_scheda_record_container).remove();
                        }
                    });




                }
                if(target=='self')
                {

                    var scheda_container_id=$(scheda_corrente_container).attr('id');
                    try 
                    {
                        bPopup[scheda_container_id].close();
                        $(scheda_corrente_container).replaceWith(cloned_scheda_record_container);
                        $(cloned_scheda_record_container).bPopup();
                    }
                    catch(err)
                    {
                        $(scheda_corrente_container).replaceWith(cloned_scheda_record_container);
                        console.info('nopopup');
                    }


                    $(lastnav).after(cloned_nav_button);
                    $(lastnav).remove();
                }

                $(cloned_nav_button).show();
                //lastnav=cloned_nav_button;
                cloned_scheda_record_container.show();
                if (typeof callback !== 'undefined')
                {
                    var el_cb=$(cloned_scheda_record_container).find('.btn_loadToJDocOnlineCV');
                    callback(el_cb);
                }


            },
            error:function(error_data){
                console.info(error_data);
                alert('errore apri_scheda_record');
                $('#dettaglio_record').html('fallimento');
            }
        });
    }
    
}

//RICERCA
function update_risultati(el,archivio)
    {
        
        var block_container=$(el).closest('.block_container');
        $('#dvLoading_risultati').css('display', 'block');
        $('#scheda_risultati').animate({
                opacity: 1
                    }, 1000);
        var url=controller_url+'ajax_load_block_risultati_ricerca/'+archivio;
        $.ajax( {
            type: "POST",
            url: url,
            data: $(block_container).find('#form_riepilogo').serialize(),
            success: function( response ) {
                $('#risultati_ricerca').html('');
                $('#risultati_ricerca').append(response);
                $('#dvLoading_risultati').fadeOut(500);

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
function update_query(el,tableid)
    {
        var scheda_dati_ricerca=$(el).closest('.scheda_dati_ricerca');
        var funzione=$(scheda_dati_ricerca).data('funzione');
        
        var view_selected=$(scheda_dati_ricerca).find('.view_selected');
        var view_selected_id=$(view_selected).data('test');
        var form_riepilogo=$(scheda_dati_ricerca).find('.form_riepilogo');
        var serialized=$('#form_dati_ricerca').serializeArray();
        serialized.push({name: 'view_selected_id', value: view_selected_id});
        
        var url=controller_url+'ajax_load_query/'+tableid+'/'+funzione;
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,//$(form_riepilogo).serialize(),
            success: function( response ) {
                $(scheda_dati_ricerca).find('#query').val(response);
                if($(scheda_dati_ricerca).find('#autosearchTrue').is(':checked'))
                {   
                    //ajax_load_block_risultati_ricerca(el, tableid);
                    refresh_risultati_ricerca();
                }
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
   
//custom Work&Work
function ajax_load_block_risultati_ricerca_non_validati(el,tableid)
{
    var scheda_dati_ricerca=$(el).closest('.scheda_dati_ricerca');
    var sql="SELECT user_candid.recordid_,user_candid.recordstatus_,user_candid.id,disp.statodisp,disp.wwws,coll.validato,user_candid.pflash,user_candid.cognome,user_candid.nome,'temp' as qualifica,coll.giudizio,user_candid.consulente,user_candid.datanasc FROM user_candid LEFT JOIN    (SELECT    MAX(recordid_) as recordid_max,recordidcandid_ FROM      user_canddisponibilita GROUP BY  recordidcandid_) as disp_max ON (user_candid.recordid_ = disp_max.recordidcandid_) LEFT JOIN user_canddisponibilita as disp ON (disp.recordid_ = disp_max.recordid_max) LEFT JOIN    (SELECT    MAX(recordid_) as recordid_max,recordidcandid_ FROM      user_candcolloquio GROUP BY  recordidcandid_) as coll_max ON (user_candid.recordid_ = coll_max.recordidcandid_)LEFT JOIN user_candcolloquio as coll ON (coll.recordid_ = coll_max.recordid_max)WHERE user_candid.recordstatus_='new'";
    
    $(scheda_dati_ricerca).find('#query').val(sql);
    ajax_load_block_risultati_ricerca(el,tableid);
}

function ajax_load_block_risultati_ricerca(el,tableid)
{
    console.info('fun:ajax_load_block_risultati_ricerca');
    //var block_container=$(el).closest('.block_container');
    var scheda_dati_ricerca=$('#scheda_dati_ricerca');
        //$('#dvLoading_risultati').css('display', 'block');
        $('#scheda_risultati').animate({
                opacity: 1
                    }, 1000); 
        $('#risultati_ricerca').html('<br/><br/><div>Caricamento in corso</div>');
        var url=controller_url+'ajax_load_block_risultati_ricerca/'+tableid;
        $.ajax( {
            type: "POST",
            url: url,
            data: $(scheda_dati_ricerca).find('#form_riepilogo').serialize(),
            success: function( response ) {
                console.info(response);
                //$('#risultati_ricerca').html('');
                $('#risultati_ricerca').html(response);
                //$('#dvLoading_risultati').fadeOut(500);

            },
            error:function(){
                alert('errore');
            }
        } ); 
}

//SCHEDA
//AGGIORNAMENTO SCHEDA DOPO MODIFICHE
function update_scheda(el,recordid_,tableid) {
            var url=controller_url+'ajax_load_block_scheda_record/'+tableid+'/'+recordid_;
            $.ajax({
                url: url,
                dataType:'html',
                success:function(data){

                            $(el).html(data);
                },
                error:function(){
                    alert('errore');
                }
            });

                  
}






//MODIFICA
function modifica_record(el,tableid,recordid){
        var block_container=$(el).closest('.block_container');
        $(block_container).find('#campi_fissi').hide(1000);
        var tabs=$(block_container).find('#tabs');
        var active_tab=$(tabs).tabs("option","active");
        var url=controller_url+'ajax_load_block_modifica_record/'+tableid+'/'+recordid;
        $.ajax({
                    url : url,
                    success : function (response) {
                        $(block_container).html(response);
                        var tabs=$(block_container).find('#tabs');
                        console.info(tabs);
                        $(tabs).tabs("option","active",active_tab);
                    },
                    error : function () {
                        alert("Errore");
                    }
                });
        
    }
   
// chiamata da "salva modifiche" della scheda record
function salva_modifiche_record(el,tableid,recordid)
    {
        console.info("salva_modifiche_record "+el+" "+tableid+" "+recordid);
        //var recordid=$(scheda_record).data('recordid');
        //var tableid=$(scheda_record).data('tableid');
        var page=$(el).closest('.page');
        var block_dati_labels=$(el).closest('.block_dati_labels');
        var master_recordid=$(block_dati_labels).data('recordid');
        var master_tableid=$(block_dati_labels).data('tableid');
        var scheda_container=$(block_dati_labels).data('scheda_container');
        var tables_container=$(el).closest(".tables_container");
        var funzione=$(tables_container).data('funzione');
        //var tables_container_cloned=tables_container.clone();
        var table_container=$(el).closest('.table_container');
        var table_container_cloned=$(table_container).clone();
        var form_dati_labels=$(block_dati_labels).find('.form_dati_labels');
        //$(form_dati_labels).html('');
        var compiled=false;
        if((master_recordid==null)&&(scheda_container=='scheda_dati_inserimento'))
        {
            $(block_dati_labels).find('.tables_container').each(function(){
                var tables_container=this;
                $(form_dati_labels).append(tables_container);
            })
            var block_allegati=$(page).find('.block_allegati');
            var block_lista_files=$(block_allegati).find('.block_lista_files');
            var files_container=$(block_lista_files).find('.files_container');
            $(form_dati_labels).append(files_container);
            var serialized=$(form_dati_labels).serialize();
            compiled=true;
            
        }
        else
        {
            $(form_dati_labels).append(table_container_cloned);
            $(table_container).find(".field").each(function(i){
                var field_value=$(this).val();
                if(field_value!='')
                {
                    compiled=true;
                }
                
            });
            
            
           
            var serialized=$(table_container).find("select,textarea,input").serialize();
        }
        
       
        //var serialized=$(form_dati_labels).serialize();
        
        if(compiled)
        {
        var url=controller_url+'ajax_salva_modifiche_record/'+master_tableid+'/'+master_recordid;
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                if(master_recordid==null)
                {
                    master_recordid=response.replace(";","");
                    if(scheda_container=='scheda_dati_inserimento')
                    {
                        ajax_load_block_dati_labels(tableid,master_recordid,'modifica',scheda_container,block_dati_labels);
                        ajax_load_block_allegati(tableid,master_recordid,'modifica',block_allegati);
                    }
                    if(scheda_container=='scheda_record')
                    {
                        record_click(null,master_recordid,master_tableid,null);  
                    }
                     
                }
                else
                {
                    var scheda_record=$(el).closest('.scheda_record');
                    $(scheda_record).find('#form_scheda_record').html("");
                    load_tables_labelcontainer(tables_container,'refresh');
                }

                    //refresh_risultati_ricerca();
                
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
        }
        else
        {
           
        }
    }
    
    
function salva_linked_record(el,param)
    {

        var confirmation=confirm('Sicuro di voler salvare? sicuro?')
            

        if(confirmation)
        {
        console.info("salva_record ");
        var scheda_origine=$(el).closest('.scheda'); 
        var scheda_origine_container=$(el).closest('.scheda_container');
        var scheda_origine_container_id=$(scheda_origine_container).attr('id');
        var popuplvl=$(scheda_origine).data('popuplvl');
        var origine_target=$(scheda_origine).data('target');
        var block_scheda_container=$(el).closest('.scheda_container');
        var block_scheda_record=$(el).closest('.scheda_record');
        var funzione=$(block_scheda_record).data('funzione');
        var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
        var tipo_scheda_container=$(block_dati_labels).data('scheda_container');
        var origine=$(el).data('origine');
        var table_container=$(el).closest('.table_container');
        
        if(origine==null)
        {
                    origine=$(block_scheda_container).data('origine');
        }
        var origine_id=$(block_scheda_container).data('origine_id');

         var origine_recordid=$(block_scheda_record).data('recordid');
         var origine_tableid=$(block_scheda_record).data('tableid');
        if((origine=='linked_table'))
        {
            var tables_container=$('#'+origine_id);
            var origine_block_dati_labels=$(tables_container).closest('.block_dati_labels');
            var master_origine_field_value=$(block_dati_labels).find('.records_linkedmaster_field_'+origine_tableid);
            $(master_origine_field_value).val(origine_recordid);
        }
        var labels=$(block_dati_labels).find('.labels');
        var form_dati_labels=$(block_dati_labels).find('.form_dati_labels');
        var obbligatori_compilati=true;
        $(block_dati_labels).find(".field").each(function(i){
                var field_value=$(this).val();
    
                 if(($(this).data('obbligatorio')===true)&&(field_value==''))
                 {
                     
                     obbligatori_compilati=false;
                 }
            });
        if(obbligatori_compilati)
        {
            var recordid=$(table_container).data('recordid');
            var tableid=$(table_container).data('tableid')

            
                var table_container=$(el).closest('.table_container');
                var serialized=$(table_container).find("select,textarea,input").serializeArray();
            
            
            serialized.push({name: 'origine[tableid]', value: origine_tableid});
            serialized.push({name: 'origine[recordid]', value: origine_recordid});
            
            
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
 
                        recordid=response.replace(";","");
                        
                            
                            
                            
                                var tables_container=$(el).closest(".tables_container");
                                
                                
                                load_tables_labelcontainer(tables_container,'refresh');
                            
                                

                            
                            
                        
                    
                    if((tableid=='immobili_richiesti')||(tableid=='immobili_proposti'))
                    {
                        if(funzione=='inserimento')
                        { 
                            var linked_master_immobile=$(block_scheda_record).find('#records_linkedmaster_field_immobili_richiesti_immobili');
                            if($(linked_master_immobile).val()!=='')
                            {
                                var confirmation=confirm('Inviare mail di protezione cliente?')
                                if(confirmation)
                                {
                                    genera_mail_protezionecliente(tableid,recordid);
                                } 
                            }
                            
                        }
                        
                    }
                
                },
                error:function(){
                    alert('errore'); 
                }
            } ); 
        }
        else
        {
            
            alert('campi obbligatori mancanti');
        }

        }
    }  
    
    
    function salva_linkedmaster(el)
    {
         var form_dati_labels=$(el).closest('.form_dati_labels');
         var block_dati_labels=$(el).closest('.block_dati_labels');
         var block_scheda_record=$(el).closest('.scheda_record');
        var origine_recordid=$(block_scheda_record).data('origine_recordid');
        var origine_tableid=$(block_scheda_record).data('origine_tableid');
                var serialized=$(form_dati_labels).serializeArray();

            serialized.push({name: 'origine[tableid]', value: origine_tableid});
            serialized.push({name: 'origine[recordid]', value: origine_recordid});
            
             var recordid=$(block_dati_labels).data('recordid');
            var tableid=$(block_dati_labels).data('tableid')
            
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                    alert('salvato');
                },
                error:function(){
                    alert('errore'); 
                }
            } ); 
    }
    
    function allega_salva(el)
    {
        allega_coda(el);
        
        
        salva_record(el,'nuovo');
    }
    function allega_coda(el)
    {
        var file_container=$('#files_coda_container').find('.file_container').first();
        $('#block_lista_files_container').find('#files_container').prepend(file_container);
    }
    
    
function salva_record(el,param)
    {
        recordid='';
        console.info("salva_record ");
        var scheda_origine=$(el).closest('.scheda'); 
        var scheda_origine_container=$(el).closest('.scheda_container');
        var scheda_origine_container_id=$(scheda_origine_container).attr('id');
        var popuplvl=$(scheda_origine).data('popuplvl');
        var origine_target=$(scheda_origine).data('target');
        var block_scheda_container=$(el).closest('.scheda_container');
        var block_scheda_record=$(el).closest('.scheda_record');
        var funzione=$(block_scheda_record).data('funzione');
        var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
        var tipo_scheda_container=$(block_dati_labels).data('scheda_container');
        var origine=$(el).data('origine');
        
        
        if(origine==null)
        {
                    origine=$(block_scheda_container).data('origine');
        }
        var origine_id=$(block_scheda_container).data('origine_id');

        var origine_recordid=$(block_scheda_record).data('origine_recordid');
        var origine_tableid=$(block_scheda_record).data('origine_tableid');
        if((origine=='linked_table'))
        {
            var tables_container=$('#'+origine_id);
            var origine_block_dati_labels=$(tables_container).closest('.block_dati_labels');
            origine_recordid=$(block_scheda_record).data('origine_recordid');
            origine_tableid=$(block_scheda_record).data('origine_tableid');
            var master_origine_field_value=$(block_dati_labels).find('.records_linkedmaster_field_'+origine_tableid);
            $(master_origine_field_value).val(origine_recordid);
        }
        var labels=$(block_dati_labels).find('.labels');
        var form_dati_labels=$(block_dati_labels).find('.form_dati_labels');
        var obbligatori_compilati=true;
        $(block_dati_labels).find(".field").each(function(i){
                var field_value=$(this).val();
    
                 if(($(this).data('obbligatorio')===true)&&(field_value==''))
                 {
                     
                     obbligatori_compilati=false;
                 }
            });
        if(obbligatori_compilati)
        {
            var recordid=$(block_dati_labels).data('recordid');
            var tableid=$(block_dati_labels).data('tableid')

            if((origine=='linked_table')&&(origine_target=='right'))
            {
                var table_container=$(el).closest('.table_container');
                var serialized=$(table_container).find("select,textarea,input").serializeArray();
            }
            else
            {
                $(labels).find('.tables_container').each(function(index){
                    $(form_dati_labels).append(this);
                })
                var block_allegati=$(block_scheda_container).find('.block_allegati');
                var files_container=$(block_allegati).find('#files_container');
                $(files_container).find('.file_container').each(function(i){
                    var input_fileorigine=$(this).find('.fileorigine');
                    if(($(input_fileorigine).val()=='coda')||($(input_fileorigine).val()=='autobatch'))
                        {
                            $(this).find('input').each(function(i){
                            var original_name=$(this).attr('name');
                            var new_name=original_name.replace('[null]', '[insert]');
                            var new_name=new_name.replace('[update]', '[insert]');
                            $(this).attr('name',new_name);
                            })
                        }
                    $(form_dati_labels).append(this);
                });
                if(tableid=='CONTRA')
                {
                     $(form_dati_labels).append($('#contra_check'));
                }


                var serialized=$(form_dati_labels).find("select,textarea,input").serializeArray();
            }
            serialized.push({name: 'origine[tableid]', value: origine_tableid});
            serialized.push({name: 'origine[recordid]', value: origine_recordid});
            
            console.info(serialized);
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                    var response_array = response.split("-");
                    if(response_array[0]==='custom_3p_sovrapposizione_contratto')
                    {
                        alert('Attenzione, sovrapposizione con altri contratti del dipendente. Il contratto appena inserito non verrà salvato. Verificare i seguenti contratti:'+response_array[1]+" ed effettuare nuovamente l'inserimento");
                        chiudi_scheda(el);
                    }
                    else
                    {
                            recordid=response.replace(";","");
                            if(tipo_scheda_container=='scheda_record')
                            {

                                if(origine=='records_linkedmaster')
                                {
                                    var table_container=$('#'+origine_id);
                                    var master_origine_field=$(table_container).find('.field');
                                    $(master_origine_field).val(recordid); 
                                    var block_dati_labels=$(master_origine_field).closest('.block_dati_labels');
                                    var origine_recordid=$(block_dati_labels).data('recordid');
                                    var origine_tableid=$(block_dati_labels).data('tableid')
                                    autosave_linkedmaster(master_origine_field,origine_tableid,origine_recordid);
                                    var fissi_record_linkedmaster=$(table_container).find('.fissi_record_linkedmaster');
                                    var nessun_valore=$(table_container).find('.nessun_valore');
                                    var url=controller_url+'ajax_load_block_fissi/'+tableid+'/'+recordid;
                                    $.ajax({
                                                url: url,
                                                dataType:'html', 
                                                success:function(data){
                                                    chiudi_scheda(el);
                                                    $(fissi_record_linkedmaster).html(data);
                                                    $(nessun_valore).hide();
                                                    $(fissi_record_linkedmaster).show();

                                                },
                                                error:function(){
                                                    alert('errore');
                                                }
                                            });
                                }

                                if((origine==='home_menu')||(origine==='risultati_ricerca')||((origine=='linked_table')&&(origine_target=='popup')))
                                {
                                    if(param=='nuovo')
                                    {
                                        //record_click(null,'null',tableid,null);
                                        if(origine_target=='popup')
                                        {
                                            try {
                                                bPopup[scheda_origine_container_id].close();
                                                apri_scheda_record(el,tableid,'null','popup','allargata');
                                            }
                                            catch (e)
                                            {
                                                console.info(e);
                                            }
                                        }
                                        if(origine_target=='right')
                                        {
                                            chiudi_scheda(el);
                                            var risultati_ricerca=$('#risultati_ricerca');
                                            var menu_top=$(risultati_ricerca).find('.menu_top');
                                            apri_scheda_record(menu_top,tableid,'null','right','standard_dati','risultati_ricerca','inserimento'); 
                                        }

                                        /*if(origine_target=='popup')
                                        {
                                            //apri_scheda_record(el,tableid,'null','popup','allargata',origine);
                                            ajax_load_block_dati_labels(tableid,'null','inserimento',tipo_scheda_container,block_dati_labels);
                                            var scheda_container_visualizzatore=$(block_scheda_container).find('.scheda_container_visualizzatore');
                                            $(scheda_container_visualizzatore).html('');
                                            var campi_fissi=$(block_scheda_container).find('#campi_fissi');
                                            $(campi_fissi).remove();
                                            var allegato=$('.files_coda_container').find('.allegato').first();
                                            console.info('allegato:');
                                            console.info(allegato);
                                        }

                                        if(origine_target=='right')
                                        {
                                            var risultati_ricerca=$('#risultati_ricerca');
                                            var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                            apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,'null','right','standard_dati',origine);
                                        }*/
                                        //apri_scheda_record(el,tableid,'null','popup','allargata');
                                    }
                                        //record_click(null,recordid,tableid,null); 

                                    if(param=='continua')
                                    {
                                        console.info('continua');
                                        if(origine_target=='popup')
                                        {
                                            console.info('popup');
                                            apri_scheda_record(el,tableid,recordid,'popup','allargata',origine,'modifica');
                                            try {
                                            bPopup[scheda_origine_container_id].close();
                                            }
                                            catch (e)
                                            {
                                                console.info(e);
                                            }
                                        }

                                        if(origine_target=='right')
                                        {
                                            console.info('right');
                                            var risultati_ricerca=$('#risultati_ricerca');
                                            var menu_top=$(risultati_ricerca).find('.menu_top');
                                            apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');  
                                        }
                                    }

                                    if(param=='chiudi')
                                    {
                                        if(origine=='risultati_ricerca')
                                        {
                                            var risultati_ricerca=$('#risultati_ricerca');
                                            var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                            apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,recordid,'right','standard_dati',origine);  
                                        }
                                        chiudi_scheda(el);
                                        //custom 3p
                                        if(tableid=='richiestericercapersonale')
                                        {
                                            apri_jobdescription(this,recordid);
                                            
                                        }
                                        /*try {
                                        bPopup[popuplvl].close();
                                        }
                                        catch (e)
                                        {
                                            console.info(e);
                                        }*/

                                    }
                                    if(param=='ripeti')
                                    {
                                        if(origine=='risultati_ricerca')
                                        {
                                            var risultati_ricerca=$('#risultati_ricerca');
                                            var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                            apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,recordid,'right','standard_dati',origine);  
                                        }
                                        chiudi_scheda(el);
                                        ripeti_record(el,tableid,recordid);

                                    }
                                    if(param=='modificato')
                                    {
                                        var risultati_ricerca=$('#risultati_ricerca');
                                        var menu_top=$(risultati_ricerca).find('.menu_top');
                                        if(origine=='risultati_ricerca')
                                        {
                                            apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');
                                        }

                                    }

                                    //custom dimensione immobilaire
                                    if(param=='custom_immobili_richiesti')
                                    {
                                        if(origine_target=='right')
                                        {
                                            if(recordid=='')
                                            {
                                                chiudi_scheda(el);
                                            }
                                            else
                                            {
                                                console.info('right');
                                                var risultati_ricerca=$('#risultati_ricerca');
                                                var menu_top=$(risultati_ricerca).find('.menu_top');
                                                apri_scheda_record(menu_top,'immobili_proposti',recordid,'right','standard_dati','risultati_ricerca','scheda');  
                                            }
                                        }
                                    }

                                }

                                if(origine=='risultati_ricerca')
                                {
                                    if(param!='modificato')
                                    {
                                        refresh_risultati_ricerca();
                                        //ajax_load_block_risultati_ricerca(el,tableid);
                                    }
                                }


                                if((origine=='linked_table')&&(origine_target=='popup'))
                                {
                                    var tables_container=$('#'+origine_id);
                                    load_tables_labelcontainer(tables_container,'refresh');
                                }

                                if((origine=='linked_table')&&(origine_target=='right'))
                                {
                                    var tables_container=$(el).closest(".tables_container");
                                    load_tables_labelcontainer(tables_container,'refresh');
                                }


                            }

                        //custom
                        if((tableid=='immobili_richiesti')||(tableid=='immobili_proposti'))
                        {
                            if(funzione=='inserimento')
                            { 
                                var linked_master_immobile=$(block_scheda_record).find('#records_linkedmaster_field_immobili_richiesti_immobili');
                                if($(linked_master_immobile).val()!=='')
                                {
                                    var confirmation=confirm('Inviare mail di protezione cliente?')
                                    if(confirmation)
                                    {
                                        genera_mail_protezionecliente('immobili_proposti',recordid);
                                    } 
                                }

                            }

                        }
                        
                        
                        
                        
                    }
                
                
                },
                error:function(){
                    alert('errore salva_record'); 
                }
            } ); 
        }
        else
        {
            
            alert('campi obbligatori mancanti');
        }

    }
    
    function autosave(el,tableid,recordid)
    {
        var block_scheda_record=$(el).closest('.scheda_record');
        var autosave_status=$(block_scheda_record).find('.autosave_status');
        var table_container=$(el).closest('.table_container');
        var fieldscontainer=$(el).closest('.fieldscontainer');
        var origine_recordid=$(block_scheda_record).data('origine_recordid');
        var origine_tableid=$(block_scheda_record).data('origine_tableid');
        
        
        var serialized=$(fieldscontainer).find("select,textarea,input").serializeArray();
        serialized.push({name: 'origine[tableid]', value: origine_tableid});
        serialized.push({name: 'origine[recordid]', value: origine_recordid});

        if(recordid!=null)
        {
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                    console.info('salvato');
                    var d = new Date();
                    var h = d.getHours();
                    var m = d.getMinutes();
                    var s = d.getSeconds();
                    $(autosave_status).hide('');
                    $(autosave_status).html('salvato '+h+':'+m);
                    $(autosave_status).show('slow');
                },
                error:function(){
                    alert('errore'); 
                }
            } );
        }
    }
    
    function salva_modifiche_record2(el,tableid,recordid)
    {
        var confirmation=confirm('Sicuro di voler salvare?')
        if(confirmation)
        {

            var block_scheda_record=$(el).closest('.scheda_record');
            var origine_recordid=$(block_scheda_record).data('origine_recordid');
            var origine_tableid=$(block_scheda_record).data('origine_tableid');


            var serialized=$(block_scheda_record).find("select,textarea,input").serializeArray();
            serialized.push({name: 'origine[tableid]', value: origine_tableid});
            serialized.push({name: 'origine[recordid]', value: origine_recordid});

            if(recordid!=null)
            {
                var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
                $.ajax( {
                    type: "POST",
                    url: url,
                    data: serialized,
                    success: function( response ) {
                        var alert_response='Salvato';
                        var response_array = response.split("-");
                        if(response_array[0]==='custom_3p_sovrapposizione_contratto')
                        {
                            alert_response='Attenzione, sovrapposizione con altro contratto del dipendente. Verificare i seguenti contratti:'+response_array[1];
                            chiudi_scheda(el);
                        }
                        alert(alert_response);
                        if(tableid=='richiestericercapersonale')
                        {
                                
                                    if(response_array[0]==='custom_3p_richiestericercapersonale_statomodificato')
                                    {
                                        var sendresponse=confirm("Vuoi inviare notifica email?");
                                        if(sendresponse==true)
                                        {
                                            custom3p_prepara_notifica_email(el,response_array[1],'statomodificato');
                                        }
                                    }
                               
                            
                        }
                    },
                    error:function(){
                        alert('errore'); 
                    }
                } );
            }
        }
    }
    
    function autosave_linkedmaster(el,tableid,recordid)
    {
        var block_scheda_record=$(el).closest('.scheda_record');
        var autosave_status=$(block_scheda_record).find('.autosave_status');
        var table_container=$(el).closest('.table_container');
        var fieldscontainer=$(el).closest('.fieldscontainer');
        var origine_recordid=$(block_scheda_record).data('origine_recordid');
        var origine_tableid=$(block_scheda_record).data('origine_tableid');
        
        
        var serialized=$(table_container).find("select,textarea,input").serializeArray();
        serialized.push({name: 'origine[tableid]', value: origine_tableid});
        serialized.push({name: 'origine[recordid]', value: origine_recordid});

        if(recordid!=null)
        {
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                    console.info('salvato');
                    var d = new Date();
                    var h = d.getHours();
                    var m = d.getMinutes();
                    var s = d.getSeconds();
                    $(autosave_status).hide('');
                    $(autosave_status).html('salvato '+h+':'+m);
                    $(autosave_status).show('slow');
                },
                error:function(){
                    alert('errore'); 
                }
            } );
        }
    }
    
function salva_record_old(el,param)
    {
        console.info("salva_record ");
        var scheda_origine=$(el).closest('.scheda'); 
        var scheda_origine_container=$(el).closest('.scheda_container');
        var scheda_origine_container_id=$(scheda_origine_container).attr('id');
        var popuplvl=$(scheda_origine).data('popuplvl');
        var origine_target=$(scheda_origine).data('target');
        var block_scheda_container=$(el).closest('.scheda_container');
        var block_scheda_record=$(el).closest('.scheda_record');
        var funzione=$(block_scheda_record).data('funzione');
        var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
        var tipo_scheda_container=$(block_dati_labels).data('scheda_container');
        var origine=$(el).data('origine');
        
        if(origine==null)
        {
                    origine=$(block_scheda_container).data('origine');
        }
        var origine_id=$(block_scheda_container).data('origine_id');
        var origine_recordid='';
        var origine_tableid='';
        if((origine=='linked_table'))
        {
            var tables_container=$('#'+origine_id);
            var origine_block_dati_labels=$(tables_container).closest('.block_dati_labels');
            origine_recordid=$(block_scheda_record).data('recordid');
            origine_tableid=$(block_scheda_record).data('tableid');
            var master_origine_field_value=$(block_dati_labels).find('.records_linkedmaster_field_'+origine_tableid);
            $(master_origine_field_value).val(origine_recordid);
        }
        var labels=$(block_dati_labels).find('.labels');
        var form_dati_labels=$(block_dati_labels).find('.form_dati_labels');
        var obbligatori_compilati=true;
        $(block_dati_labels).find(".field").each(function(i){
                var field_value=$(this).val();
    
                 if(($(this).data('obbligatorio')===true)&&(field_value==''))
                 {
                     
                     obbligatori_compilati=false;
                 }
            });
        if(obbligatori_compilati)
        {
            var recordid=$(block_dati_labels).data('recordid');
            var tableid=$(block_dati_labels).data('tableid')

            if((origine=='linked_table')&&(origine_target=='right'))
            {
                var table_container=$(el).closest('.table_container');
                var serialized=$(table_container).find("select,textarea,input").serializeArray();
            }
            else
            {
                $(labels).find('.tables_container').each(function(index){
                    $(form_dati_labels).append(this);
                })
                var block_allegati=$(block_scheda_container).find('.block_allegati');
                var files_container=$(block_allegati).find('#files_container');
                $(files_container).find('.file_container').each(function(i){
                    var input_fileorigine=$(this).find('.fileorigine');
                    if(($(input_fileorigine).val()=='coda')||($(input_fileorigine).val()=='autobatch'))
                        {
                            $(this).find('input').each(function(i){
                            var original_name=$(this).attr('name');
                            var new_name=original_name.replace('[null]', '[insert]');
                            var new_name=new_name.replace('[update]', '[insert]');
                            $(this).attr('name',new_name);
                            })
                        }
                    $(form_dati_labels).append(this);
                });
                if(tableid=='CONTRA')
                {
                     $(form_dati_labels).append($('#contra_check'));
                }


                var serialized=$(form_dati_labels).serializeArray();
            }
            
            serialized.push({name: 'origine[tableid]', value: origine_tableid});
            serialized.push({name: 'origine[recordid]', value: origine_recordid});
            
            
            var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                        recordid=response.replace(";","");
                        if(tipo_scheda_container=='scheda_record')
                        {
                            
                            if(origine=='records_linkedmaster')
                            {
                                var table_container=$('#'+origine_id);
                                var master_origine_field=$(table_container).find('.field');
                                $(master_origine_field).val(recordid);
                                var fissi_record_linkedmaster=$(table_container).find('.fissi_record_linkedmaster');
                                var nessun_valore=$(table_container).find('.nessun_valore');
                                var url=controller_url+'ajax_load_block_fissi/'+tableid+'/'+recordid;
                                $.ajax({
                                            url: url,
                                            dataType:'html', 
                                            success:function(data){
                                                chiudi_scheda(el);
                                                $(fissi_record_linkedmaster).html(data);
                                                $(nessun_valore).hide();
                                                $(fissi_record_linkedmaster).show();
                                                
                                            },
                                            error:function(){
                                                alert('errore');
                                            }
                                        });
                            }
                            
                            if((origine==='risultati_ricerca')||((origine=='linked_table')&&(origine_target=='popup')))
                            {
                                if(param=='nuovo')
                                {
                                    //record_click(null,'null',tableid,null);
                                    if(origine_target=='popup')
                                    {
                                        //apri_scheda_record(el,tableid,'null','popup','allargata',origine);
                                        ajax_load_block_dati_labels(tableid,'null','inserimento',tipo_scheda_container,block_dati_labels);
                                        var scheda_container_visualizzatore=$(block_scheda_container).find('.scheda_container_visualizzatore');
                                        $(scheda_container_visualizzatore).html('');
                                        var campi_fissi=$(block_scheda_container).find('#campi_fissi');
                                        $(campi_fissi).remove();
                                        var allegato=$('.files_coda_container').find('.allegato').first();
                                        console.info('allegato:');
                                        console.info(allegato);
                                    }

                                    if(origine_target=='right')
                                    {
                                        var risultati_ricerca=$('#risultati_ricerca');
                                        var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                        apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,'null','right','standard_dati',origine);
                                    }
                                    //apri_scheda_record(el,tableid,'null','popup','allargata');
                                }
                                    //record_click(null,recordid,tableid,null); 

                                if(param=='continua')
                                {
                                    console.info('continua');
                                    if(origine_target=='popup')
                                    {
                                        console.info('popup');
                                        apri_scheda_record(el,tableid,recordid,'popup','allargata',origine,'modifica');
                                        try {
                                        bPopup[scheda_origine_container_id].close();
                                        }
                                        catch (e)
                                        {
                                            console.info(e);
                                        }
                                    }

                                    if(origine_target=='right')
                                    {
                                        console.info('right');
                                        var risultati_ricerca=$('#risultati_ricerca');
                                        var menu_top=$(risultati_ricerca).find('.menu_top');
                                        apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');  
                                    }
                                }

                                if(param=='chiudi')
                                {
                                    if(origine=='risultati_ricerca')
                                    {
                                        var risultati_ricerca=$('#risultati_ricerca');
                                        var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                        apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,recordid,'right','standard_dati',origine);  
                                    }
                                    chiudi_scheda(el);
                                    /*try {
                                    bPopup[popuplvl].close();
                                    }
                                    catch (e)
                                    {
                                        console.info(e);
                                    }*/

                                }
                                if(param=='modificato')
                                {
                                    var risultati_ricerca=$('#risultati_ricerca');
                                    var menu_top=$(risultati_ricerca).find('.menu_top');
                                    if(origine=='risultati_ricerca')
                                    {
                                        apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');
                                    }

                                }
                            }

                            if(origine=='risultati_ricerca')
                            {
                                if(param!='modificato')
                                {
                                    //ajax_load_block_risultati_ricerca(el,tableid);
                                }
                            }
                            
                            
                            if((origine=='linked_table')&&(origine_target=='popup'))
                            {
                                var tables_container=$('#'+origine_id);
                                load_tables_labelcontainer(tables_container,'refresh');
                            }
                                
                            if((origine=='linked_table')&&(origine_target=='right'))
                            {
                                var tables_container=$(el).closest(".tables_container");
                                load_tables_labelcontainer(tables_container,'refresh');
                            }
                            
                            
                        }
                    
                    if((tableid=='immobili_richiesti')||(tableid=='immobili_proposti'))
                    {
                        if(funzione=='inserimento')
                        { 
                            var linked_master_immobile=$(block_scheda_record).find('#records_linkedmaster_field_immobili_richiesti_immobili');
                            if($(linked_master_immobile).val()!=='')
                            {
                                var confirmation=confirm('Inviare mail di protezione cliente?')
                                if(confirmation)
                                {
                                    genera_mail_protezionecliente(tableid,recordid);
                                } 
                            }
                            
                        }
                        
                    }
                },
                error:function(){
                    alert('errore'); 
                }
            } ); 
        }
        else
        {
            
            alert('campi obbligatori mancanti');
        }


    }
    
    function genera_mail_protezionecliente(tableid,recordid)
    {
        var url=controller_url + '/ajax_genera_mail_protezionecliente/'+tableid+'/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                //$(block).html(response);
                apri_scheda_record(this,'mail_queue',response,'popup','scheda_dati','records_linkedmaster','modifica');
                //apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster')
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function genera_mail_protezionecliente_from_queued_mail(recordid_queued_mail)
    {
        var url=controller_url + '/genera_mail_protezionecliente_from_queued_mail/'+recordid_queued_mail;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                //$(block).html(response);
                apri_scheda_record(this,'mail_queue',response,'popup','scheda_dati','records_linkedmaster','modifica');
                //apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster')
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }

function salva_modifiche_allegati(el,tableid,recordid,funzione)
{
    console.info('funzione:salva_modifiche_allegati')
    console.info(el);
    //var scheda_allegati=$(el).closest('.scheda_allegati');
    var scheda=$(el).closest('.block_allegati');
    var schedaid=$(scheda).data('schedaid');
    
        var block_lista_files=$(el).closest(".block_lista_files");
        var form_riepilogo=$(block_lista_files).find('.form_riepilogo');
        if(schedaid!='scheda_code')
        {
            $(form_riepilogo).find('.file_container').each(function(i){
                var input_fileorigine=$(this).find('.fileorigine');

                    if(($(input_fileorigine).val()=='coda')||($(input_fileorigine).val()=='autobatch'))
                    {
                        $(this).find('input').each(function(i){
                        var original_name=$(this).attr('name');
                        var new_name=original_name.replace('[null]', '[insert]');
                        var new_name=new_name.replace('[update]', '[insert]');
                        $(this).attr('name',new_name);
                        })
                    }

            });
        }
        var url=controller_url+'ajax_salva_modifiche_allegati/'+tableid+'/'+recordid;
        $.ajax( {
            type: "POST",
            url: url,
            data: $(form_riepilogo).serialize(),
            success: function( response ) {
                //alert('allegati salvati');
                var target=scheda;
                /*var funzione=$(scheda_allegati).data('funzione');
                if(funzione=='modifica')
                {
                    funzione='modifica';
                }*/
                
                if(funzione!='ordinamento')
                {
                   ajax_load_block_allegati(tableid,recordid,'inserimento',target); 
                }
                
                //update_scheda(scheda_container, recordid, tableid);
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}


function ajax_load_block_dati_labels(tableid,recordid,funzione,scheda_container,target)
{
    var url=controller_url+'ajax_load_block_dati_labels/'+tableid+'/'+recordid+'/'+funzione+'/'+scheda_container+'/null';
        $.ajax( {
            dataType: "html",
            url: url,
            success: function( response ) {
                $(target).replaceWith(response);
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}

function tab_allegati_click(el)
{
    var scheda_record=$(el).closest('.scheda_record');
    var tableid=$(scheda_record).data('tableid');
    var recordid=$(scheda_record).data('recordid');
   var allegati_container=$(scheda_record).find('.allegati_container');
   $(allegati_container).html('Caricamento');
   var url=controller_url+'ajax_load_block_allegati/'+tableid+'/'+recordid+'/'+'inserimento';
    $.ajax( {
        dataType:'html',
        url: url,
        success: function( response ) {
            $(allegati_container).html(response);
        },
        error:function(){
            alert('errore'); 
        }
    } ); 
}

function ajax_load_block_allegati(tableid,recordid,funzione,target)
{
    var url=controller_url+'ajax_load_block_allegati/'+tableid+'/'+recordid+'/'+funzione;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $(target).replaceWith(response);
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}
//elimina record
function elimina_record(el,tableid,recordid){
        var risultati_ricerca=$('#risultati_ricerca');
        
        var confirmation=confirm('Sicuro di voler eliminare questo record?')
        if(confirmation)
            {
                var scheda_record=$(el).closest('.scheda_record');
                var url=controller_url+'ajax_elimina_record/'+tableid+'/'+recordid;
                $.ajax({
                            url : url,
                            success : function (response) {
                                var scheda=$(el).closest('.scheda_record');
                                var id=$(scheda).attr('id');
                                var scheda_container=$(el).closest('.scheda_container');
                                var scheda_container_id=$(scheda_container).attr('id');
                                try 
                                {
                                    bPopup[scheda_container_id].close();
                                }
                                catch(err)
                                {
                                    console.info('nopopup');
                                    var dataTables_wrapper=$(risultati_ricerca).find('.dataTables_wrapper');
                                    var datatable_id=$(dataTables_wrapper).attr('id');
                                    datatable_id=datatable_id.replace("_wrapper",""); 
                                    var datatable=$('#'+datatable_id).dataTable();
                                    datatable.api().row('.selected').remove().draw( false );
                                }
                                if(id==ultimascheda)
                                {
                                  ultimascheda="";
                                }
                                $('#nav_'+id).remove();
                                $(scheda_container).remove();
                                

                            },
                            error : function () {
                                alert("Errore");
                            }
                        });
            }
        
    }

function abilita_modifica_record(el)
{
    
    var scheda_record=$(el).closest('.scheda_record');
    var tableid=$(scheda_record).data('tableid');
    var recordid=$(scheda_record).data('recordid');
    
    var risultati_ricerca=$('#risultati_ricerca');
    var menu_top=$(risultati_ricerca).find('.menu_top');
    var scheda_corrente=$(el).closest('.scheda');
    var scheda_corrente_container=$(scheda_corrente).closest('.scheda_container');
    var scheda_origine_container_id=$(scheda_corrente_container).attr('id');
    var target=$(scheda_corrente).data('target');
    var origine=$(scheda_corrente_container).data('origine');
    if(target=='popup')
    {
        apri_scheda_record(el,tableid,recordid,'popup','allargata',origine,'modifica');
        try {
        bPopup[scheda_origine_container_id].close();
        }
        catch (e)
        {
            console.info(e);
        }
    }

    if(target=='right')
    {
        var risultati_ricerca=$('#risultati_ricerca');
        var risultati_ricerca_menu_top=$(risultati_ricerca).find('.menu_top');
        apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','modifica');  
    }
    /*var scheda_container_id=$(scheda_corrente_container).attr('id');
                try 
                {
                    bPopup[scheda_container_id].close();
                }
                catch(e)
                {
                    
                }
    apri_scheda_record(el,tableid,recordid,'self','standard_dati','risultati_ricerca','modifica');*/
    //$(scheda_record).find('.fa-times').click();
    /*var scheda_record=$(el).closest('.scheda_record');
    $(scheda_record).find('#scheda_record_content').each(function(i){
        var scheda_record_content=this;
        $(scheda_record_content).find('.fa-pencil').each(function (i){
            var btn_edit=this;
            $(btn_edit).click();
        })
    })
    var menu=$(el).closest('.menu_top');
    $(el).hide();
    $(menu).find('.fa-save').show();*/
    
}

function duplica_record(el,tableid,recordid)
{ 
    var confirmation=confirm('Sicuro di voler duplicare?')
    if(confirmation)
        {
     var url=controller_url+'ajax_duplica_record/'+tableid+'/'+recordid;
    $.ajax({
                url : url,
                success : function (response) {
                    recordid=response;
                    alert('record duplicato'); 
                    var menu_top=$(risultati_ricerca).find('.menu_top');
                    apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');  
                    refresh_risultati_ricerca();
                    
                },
                error : function () {
                    alert("Errore");
                }
            });
        }
}

function ripeti_record(el,tableid,recordid)
{ 
    console.info('fun: ripeti_record');
     var url=controller_url+'ajax_ripeti_record/'+tableid+'/'+recordid;
    $.ajax({
                url : url,
                success : function (response) {
                    recordid=response;
                    var menu_top=$(risultati_ricerca).find('.menu_top');
                    apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','modifica');  
                    refresh_risultati_ricerca();
                    
                },
                error : function () {
                    alert("Errore");
                }
            });
}
//custom Dimensione Immobiliare inizio
function nuova_proposta_immobile(el,tableid,recordid)
{ 
    var confirmation=confirm('Proporre un nuovo immobile?')
    if(confirmation)
        {
     var url=controller_url+'ajax_nuova_proposta_immobile/'+tableid+'/'+recordid;
    $.ajax({
                url : url,
                success : function (response) {
                    recordid=response;
                    alert('Creata nuova proposta. Associare il nuovo immobile da proporre'); 
                    var menu_top=$(risultati_ricerca).find('.menu_top');
                    apri_scheda_record(menu_top,tableid,recordid,'right','standard_dati','risultati_ricerca','scheda');  
                    refresh_risultati_ricerca();
                    
                },
                error : function () {
                    alert("Errore");
                }
            });
        }
}
//custom Dimensione Immobiliare fine

function salva_tutte_modifiche_record(el)
{
    var scheda_record=$(el).closest('.scheda_record');
    $(scheda_record).find('#scheda_record_content').each(function(i){
        var scheda_record_content=this;
        $(scheda_record_content).find('.fa-floppy-o').each(function (i){
            var btn_save=this;
            $(btn_save).click();
        })
    })
    var menu=$(scheda_record).find('.menu_scheda_record');
    $(el).hide();
    $(menu).find('.fa-pencil').show();
}
 
 //elimina record linkato
function elimina_linked_record(el,tableid,recordid){
        var confirmation=confirm('Sicuro di voler eliminare questo record?')
        if(confirmation)
            {
                var url=controller_url+'ajax_elimina_record/'+tableid+'/'+recordid;
                $.ajax({
                            url : url,
                            success : function (response) {
                                alert('record eliminato');
                                var tables_container=$(el).closest('.tables_container');
                                var fields_record_linkedtable=$(el).closest('.fields_record_linkedtable');
                                var tr=$(fields_record_linkedtable).prev();
                                $(fields_record_linkedtable).remove();
                                $(tr).remove();
                            },
                            error : function () {
                                alert("Errore");
                            }
                        });
            }
        
    }
    
    
function valida_record(el,tableid,recordid)
{
    var url=controller_url+'ajax_valida_record/'+tableid+'/'+recordid;
    $.ajax({
            url : url,
            success : function (response) {
                alert('record validato');
            },
            error : function () {
                alert("Errore");
            }
        });
}

function valida_tutto_record(el,tableid,recordid)
{
    var url=controller_url+'ajax_valida_tutto_record/'+tableid+'/'+recordid;
    $.ajax({
            url : url,
            success : function (response) {
                alert('record validato');
            },
            error : function () {
                alert("Errore");
            }
        });
}

//FIELDS
//MOSTRARE IL RIEPILOGO
function show_riepilogo(el)
{
    var scheda_dati_ricerca_container=$(el).closest('.scheda_dati_ricerca_container');
    var block_dati_labels_container=$(scheda_dati_ricerca_container).find('.block_dati_labels_container');
    var block_dati_labels_container_width=$(block_dati_labels_container).width();
    var block_riepilogo=$(scheda_dati_ricerca_container).find('#block_riepilogo');
    if($(block_riepilogo).is(':hidden'))
    {
        $(scheda_dati_ricerca_container).animate({width:$(scheda_dati_ricerca_container).width()*2},1000);
        $(block_dati_labels_container).width(block_dati_labels_container_width);
        $(block_riepilogo).width(block_dati_labels_container_width);
        $(block_riepilogo).show(1000);
        $(el).removeClass('btn_right');
        $(el).addClass('btn_left');
    }
    else
    {
        $(block_riepilogo).hide(1000);
        $(scheda_dati_ricerca_container).animate({width:$(scheda_dati_ricerca_container).width()*0.5},1000);
        $(el).removeClass('btn_left');
        $(el).addClass('btn_right');
    }
}

//RICARICA LA SCHEDA DEI CAMPI
/*function reload_fields(el,tableid,funzione)
{
    var scheda=$(el).closest('.scheda');
    var block_dati_labels=$(scheda).find('.block_dati_labels');
    var url=controller_url+'ajax_load_block_dati_labels/'+tableid+'/null/'+funzione+'/null/null';
    $.ajax( {
            dataType:'html',
            url: url,
            success: function( response ) {
                $(block_dati_labels).html(response);

            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}*/
function reload_fields(el,tableid,funzione)
{
    var scheda_dati_ricerca_container=$('.scheda_dati_ricerca_container');
    var url=controller_url+'ajax_load_scheda_dati_ricerca/'+tableid;
    $.ajax( {
            dataType:'html',
            url: url,
            success: function( response ) {
                $(scheda_dati_ricerca_container).html(response);
                ajax_load_block_dati_labels(tableid,'null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));
                refresh_risultati_ricerca();
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}

function panel_activate(event, ui)
{
    var header=ui['newHeader'];
    var newPanel=ui['newPanel'];
    var block_container=$(header).closest('.block_container');
    var fields_container=$(block_container).find('.fields_container');
    var first=$(newPanel).find('.first');
    $(fields_container).scrollTo(header,500);
    //$(first).focus();
}

function linked_table_add(el)
{
  
  //$(tables_container).data('funzione','inserimento');
  //$(el).hide();
  add_table(el,'add');  
}

// PARAMETRI TABELLA. AND
function table_param_onclick(el,param){
        add_table(el,param);
    }
    
 //CARICARE CONTENUTO LABEL
 function load_tables_labelcontainer(tables_container,table_param,scroll)
 {
     console.info('fun: load_tables_labelcontainer');
     if(typeof scroll==='undefined')
    {
        scroll=true;
    }
    var block_dati_labels=$(tables_container).closest('.block_dati_labels');
    var block_dati_labels_container=$(block_dati_labels).closest('.block_dati_labels_container');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var scheda_record=$(block_dati_labels).closest('.scheda_record');
    var scheda_record_container=$(scheda_record).closest('.scheda_record_container');
    var tableid=$(tables_container).data('linkedtableid');
    var type=$(tables_container).data('type');
    if(type=='master')
    {
        var label=$(tables_container).data('labelname'); 
    }
    else
    {
       label='null'; 
    }
    
    var mastertableid=$(tables_container).data('mastertableid');
    var funzione=$(tables_container).data('funzione');
    var recordid=$(tables_container).data('recordid');
    var table_index=$(tables_container).data('table_index');
    var new_table_index=table_index+1;
    $(tables_container).data('table_index',new_table_index);
    if(funzione=='ricerca')
    {
        var viewid=$('#saved_view_select').val();
        if((viewid=='')||(viewid==null))
        {
            viewid='null';
        }
    }
    else
    {
        viewid='null';
    }
    var origine_tableid=$(scheda_record_container).data('origine_tableid');
    var origine_recordid=$(scheda_record_container).data('origine_recordid');
    var link=controller_url+'ajax_load_block_tables_labelcontainer/'+tableid+'/'+label+'/'+new_table_index+'/'+table_param+'/'+type+'/'+mastertableid+'/'+funzione+'/'+recordid+'/'+scheda_container+'/'+viewid+'/desktop/'+origine_tableid+'/'+origine_recordid;
        $.ajax({
            url: link,
            dataType:'html',
            success:function(data){
                        $(tables_container).html("");
                        $(tables_container).html(data);
                        tablesloading=false;
                        
                        //apply();
                        if(((funzione=='inserimento')&&(type!='master'))||(funzione!='inserimento'))
                        {
                            var label=$(tables_container).prev();
                            $(label).data('loaded','true');
                            if(scroll)
                            {
                                $(block_dati_labels_container).scrollTo(label); 
                            }
                            //tempdemo $(tables_container).find('.first').first().focus();
                        }
                        
                            
            },
            error:function(){
                alert('errore');
            }
            });
 }   
 //AGGIUNGERE UNA TABELLA
 function add_table(el,table_param)
 {
    var tables_container=$(el).closest(".tables_container");
    var block_dati_labels=$(tables_container).closest('.block_dati_labels');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var tableid=$(tables_container).data('linkedtableid');
    var label=$(tables_container).data('labelName');
    var type=$(tables_container).data('type');
   var mastertableid=$(tables_container).data('mastertableid');
    var test=$(tables_container).data('funzione');
    var funzione=$(block_dati_labels).data('funzione');
    if(table_param=='add')
    {
        funzione='inserimento';
    }
    var recordid='null';
    var table_index=$(tables_container).data('table_index');
    var new_table_index=table_index+1;
    $(tables_container).data('table_index',new_table_index);
    var link=controller_url+'ajax_load_block_table/'+tableid+'/'+label+'/'+new_table_index+'/'+table_param+'/'+type+'/'+mastertableid+'/'+funzione+'/'+recordid+'/'+scheda_container;
        $.ajax({
            url: link,
            dataType:'html',
            success:function(data){
                if(type=='linked')
                {
                    var block_dati_labels_container=$(el).closest('.block_dati_labels_container');
                    if(table_param!='null')
                        {
                            //$(block_dati_labels_container).scrollTo(el,500, {offset: {top:-100} });
                            $(tables_container).find('.first').removeClass('first');
                            $(tables_container).find('.last').removeClass('last');
                            $(tables_container).find('.lastbutton').removeClass('lastbutton');
                            var menu_small=$(el).closest('.menu_small');
                            //$(data).insertBefore(menu_small);
                            $(tables_container).prepend(data);
                            apply();
                            var first=$(tables_container).find('.first');
                            var lastbutton=$(tables_container).find('.lastbutton');
                        }
                        else
                        {
                            //$(block_dati_labels_container).scrollTo(el,500, {offset: {top:-100} });
                            $(el).insertBefore(data);
                            apply();
                            var first=$(tables_container).find('.first');
                        }
                }
                if(type=='linkedmaster')
                {
                    $(tables_container).append(data);
                }
            },
            error:function(){
                alert('errore');
            }
            });
 }
 
 //AGGIUNGERE UNA TABELLA
 function linkedmaster_table_add(el)
 {
    var tables_container=$(el).closest(".tables_container");
    var block_dati_labels=$(tables_container).closest('.block_dati_labels');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var tableid=$(tables_container).data('linkedtableid');
    var label=$(tables_container).data('labelName');
    var type=$(tables_container).data('type');
    var mastertableid=$(tables_container).data('mastertableid');
    var funzione=$(block_dati_labels).data('funzione');
    var recordid='null';
    var table_index=$(tables_container).data('table_index');
    var new_table_index=table_index+1;
    $(tables_container).data('table_index',new_table_index);
    var link=controller_url+'ajax_load_block_table/'+tableid+'/'+label+'/'+new_table_index+'/'+table_param+'/'+type+'/'+mastertableid+'/'+funzione+'/'+recordid+'/'+scheda_container;
        $.ajax({
            url: link,
            dataType:'html',
            success:function(data){
                var block_dati_labels_container=$(el).closest('.block_dati_labels_container');
                if(table_param!='null')
                    {
                        //$(block_dati_labels_container).scrollTo(el,500, {offset: {top:-100} });
                        $(tables_container).find('.first').removeClass('first');
                        $(tables_container).find('.last').removeClass('last');
                        $(tables_container).find('.lastbutton').removeClass('lastbutton');
                        $(data).insertBefore(el);
                        apply();
                        var first=$(tables_container).find('.first');
                        var lastbutton=$(tables_container).find('.lastbutton');
                    }
                    else
                    {
                        //$(block_dati_labels_container).scrollTo(el,500, {offset: {top:-100} });
                        $(el).insertBefore(data);
                        apply();
                        var first=$(tables_container).find('.first');
                    }
            },
            error:function(){
                alert('errore');
            }
            });
 }
 
    function last_tab(el){
        var tables_container=$(el).closest('.tables_container');
        var next_label=$(tables_container).next();
        //$('#testfocus').focus();

        //labelclick2(next_label);
        $(next_label).click();
    }   
  function delete_field_onclick(el){
         var field_container=$(el).closest(".fieldcontainer");
         $('#form_riepilogo').find('#'+field_container.attr('id')).remove();
         field_container.remove();
         field_changed(el);
         
    }
    
    function delete_table_onclick(el){
         var table_container=$(el).closest(".tablecontainer");
         var block_container=$(el).closest('.block_container');
         var mastertableid=block_container.data('tableid');
         $('#form_riepilogo').find('#'+table_container.attr('id')).remove();
         table_container.remove();
         update_query(el, mastertableid);
    }
    
    
//RISULTATI RICERCA
//ORDINAMENTO PER LA STAMPA
function set_order(el,key){
   var scheda=$(el).closest('.scheda');
   var btn_stampa_elenco_container= $(scheda).find('#btn_stampa_elenco_container');
   if(typeof btn_stampa_elenco_container!=='undefined')
   {
    var old_ascdesc=$(btn_stampa_elenco_container).data('orderascdesc');
    var old_key=$(btn_stampa_elenco_container).data('orderkey');
    if(old_ascdesc=='desc'){
        $(btn_stampa_elenco_container).data('orderascdesc', 'asc');
    }
    else
        {
            $(btn_stampa_elenco_container).data('orderascdesc', 'desc');
        }
    $(btn_stampa_elenco_container).data('orderkey', key);
   }
}

function set_orderkey(el,key){
   var scheda=$(el).closest('.scheda');
   var btn_stampa_elenco_container= $(scheda).find('#btn_stampa_elenco_container');
   if(typeof btn_stampa_elenco_container!=='undefined')
   {
    $(btn_stampa_elenco_container).data('orderkey', key);
   }
}




//GESTIONE CODE
//
//CREA CODA
function crea_coda(el)
{
   var nome_coda=$('#nome_coda').val(); 
   var scheda_code_container=$('.scheda_code_container');
   var url=controller_url+'ajax_crea_coda/'+nome_coda;
    $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                alert('coda creata');
                ajax_load_block_code(response,scheda_code_container);
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}

function importacoda(el)
    {
        var nome_coda=$('#nome_coda').val();
         var url=controller_url+'importacoda/'+nome_coda;
        $.ajax({
            url: url,
            success:function(data){
                    alert("CODA CREATA CORRETTAMENTE!");
                    var content=$(el).closest('.content');
                    var scheda_code_container=$(content).find('.scheda_code_container');
                    ajax_load_block_code(data,scheda_code_container);
            },
            error:function(){
                alert("errore");
            }
        });
    }
    
function load_coda(el,funzione)
 {
     var id=$(el).val();
     var scheda=$(el).closest('.scheda');
     var url=controller_url+'ajax_load_block_lista_files/'+funzione+'/coda/'+id;
     $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    
                    $(scheda).find('#files_coda_container').html(data);
                   

                },
                error:function(){
                    console.info('fallimento');
                    $('#files_coda').html('fallimento');
                }
            });
            
 }
 
 function load_autobatch(el,funzione)
 {
     var id=$(el).val();
     var scheda=$(el).closest('.scheda');
     var url=controller_url+'ajax_load_block_lista_files/'+funzione+'/autobatch/'+id;
     $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    
                    $(scheda).find('#files_coda_container').html(data);
                   

                },
                error:function(){
                    alert('errore');
                }
            });
            
 }
//caricamento blocco code
function ajax_load_block_code(coda_precaricataid,target)
{
    var url=controller_url+'ajax_load_block_code/gestione_code/'+coda_precaricataid;
    $.ajax( {
            type: "url",
            url: url,
            success: function( response ) {
                $(target).html(response);
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}
//SALVA CODA
function salva_modifiche_coda(el)
{
    var scheda_code=$(el).closest('.scheda_code');
    var select_lista_code=$(scheda_code).find('.select_lista_code');
    var codaid=$(select_lista_code).val();
    var block_lista_files=$(scheda_code).find('.block_lista_files');
        $(block_lista_files).find('.file_container').each(function(i){

                var input_fileorigine=$(this).find('.fileorigine');
                if(($(input_fileorigine).val()=='coda')||($(input_fileorigine).val()=='autobatch'))
                    {
                        $(this).find('input').each(function(i){
                        var original_name=$(this).attr('name');
                        var new_name=original_name.replace('[null]', '[insert]');
                        var new_name=new_name.replace('[update]', '[insert]');
                        $(this).attr('name',new_name);
                        })
                    }
        });
    var url=controller_url+'ajax_salva_modifiche_coda/'+codaid;
    $.ajax( {
            type: "POST",
            url: url,
            data: $(block_lista_files).find('.form_riepilogo').serialize(),
            success: function( response ) {
                //alert('coda salvata');

            },
            error:function(){
                alert('errore'); 
            }
        } ); 
}


function salva_modifiche_lista_files(el)
{
     var block_container=$(el).closest('.block');
     var originefiles=$(block_container).find('#originefiles').val();
       /* $(block_container).find('.file_container').each(function(i){

                var input_fileorigine=$(this).find('.fileorigine');
                if($(input_fileorigine).val()=='coda')
                    {
                        $(this).find('input').each(function(i){
                        var original_name=$(this).attr('name');
                        var new_name=original_name.replace('[null]', '[insert]');
                        var new_name=new_name.replace('[update]', '[insert]');
                        $(this).attr('name',new_name);
                        })
                    }
        $(block_container).find('.form_riepilogo').append(this);
        });*/
    var block_lista_code=$(el).closest('#block_lista_code');
    var nome_coda=$(block_lista_code).find('#select_lista_code').val();
    var url="";
    if(originefiles=='coda')
    {
       url=controller_url+'ajax_salva_modifiche_coda/'+nome_coda; 
    }
    if(originefiles=='allegati')
    {
        url=controller_url+'ajax_salva_modifiche_allegati/'+nome_coda;
    }
    $.ajax( {
            type: "POST",
            url: url,
            data: $(block_container).find('#form_riepilogo').serialize(),
            success: function( response ) {
                alert('Modifiche apportate correttamente')

            },
            error:function(){
                alert('errore'); 
            }
        } );
}



function Lookup_RealColumnIndex(table,needle){
    for(var i = 0, m = null; i < table.fnSettings().aoColumns.length; ++i) {
        var title=table.fnSettings().aoColumns[i].sTitle
        if(title != needle)
            continue;
        return(i);
        break;
    }
}  

function custom_risultati(table,nRow, aData, iDataIndex)
{
    var recordcss=aData[2];
    $('td', nRow).attr('style',recordcss); 
                       
    var recordstatus_index=Lookup_RealColumnIndex(table, 'recordstatus_') ;
    if(aData[recordstatus_index]=='new')
    {
       $('td', nRow).css({
                         "background-color": "rgb(129, 52, 47)",
                         "color":"white",
                       }); 
    }
    //custom About-x
    var importoProposto_index=Lookup_RealColumnIndex(table, 'Importo proposto') ;
    if(typeof(importoProposto_index) !== 'undefined')
    {
        var column_index=importoProposto_index-3;
        $('td:eq('+column_index+')', nRow).css({
                        "text-align":"right"
                      }); 
    }
    
    var rapp_index=Lookup_RealColumnIndex(table, 'Rapp.') ;
    if(typeof(rapp_index) !== 'undefined')
    {
        var column_index=rapp_index-3;
        if(aData[rapp_index].toUpperCase()=='SI')
                    {
                        $('td:eq('+column_index+')', nRow).html( "<img src='"+assets_url+"images/pdf.png'></img>" );
                    }
        
    }
    
    var importoConcluso_index=Lookup_RealColumnIndex(table, 'Importo concluso') ;
    if(typeof(importoConcluso_index) !== 'undefined')
    {
        var column_index=importoConcluso_index-3;
        $('td:eq('+column_index+')', nRow).css({
                        "text-align":"right"
                      }); 
    }
    
    var importo_index=Lookup_RealColumnIndex(table, 'Importo') ;
    if(typeof(importo_index) !== 'undefined')
    {
        var column_index=importo_index-3;
        $('td:eq('+column_index+')', nRow).css({
                        "text-align":"right"
                      }); 
    }
    
    var dafatturare_index=Lookup_RealColumnIndex(table, 'Da fatturare') ;
    if(typeof(dafatturare_index) !== 'undefined')
    {
        var column_index=dafatturare_index-3;
        $('td:eq('+column_index+')', nRow).css({
                        "text-align":"right"
                      }); 
    }
}
// TEMP
function custom_risultati_CANDIDtemp(table,nRow, aData, iDataIndex)
{

        var Dis_index=Lookup_RealColumnIndex(table, 'Stato') ;
        var Dis_index2=0;
        var valid_index=Lookup_RealColumnIndex(table, 'Valid') ;
        var valid_index2=0;
        var pflash_index=Lookup_RealColumnIndex(table, 'pflash') ;
        var recordstatus_index=Lookup_RealColumnIndex(table, 'recordstatus_') ;
   if(aData[recordstatus_index]=='new')
   {
      $('td:eq(0)', nRow).css({
                        "background-color": "red",
                        "font-weight": "bold",
                        "color":"white",
                        "text-align":"center"
                      }); 
   }
   if(aData[Dis_index]!==null)
       {
            if ( aData[Dis_index].toUpperCase()=='D' )
            {
                Dis_index2=Dis_index-3;
                $('td:eq('+Dis_index2+')', nRow).css({
                        "background-color": "#92D050",
                        "font-weight": "bold",
                        "color":"black",
                        "text-align":"center"
                      });
              $('td:eq('+Dis_index2+')', nRow).html( 'D' );
            }
            if ( aData[Dis_index].toUpperCase()=='A' )
            {
                Dis_index2=Dis_index-3;
                $('td:eq('+Dis_index2+')', nRow).css({
                        "background-color": "#AAA580",
                        "font-weight": "bold",
                        "color":"black",
                        "text-align":"center"
                      });
              $('td:eq('+Dis_index2+')', nRow).html( 'A' );
            }
            if ( aData[Dis_index].toUpperCase()=='N' )
            {
                Dis_index2=Dis_index-3;
                $('td:eq('+Dis_index2+')', nRow).css({
                        "background-color": "#7F7F7F",
                        "font-weight": "bold",
                        "color":"black",
                        "text-align":"center"
                      });
              $('td:eq('+Dis_index2+')', nRow).html( 'N' );
            }
            if ( aData[Dis_index].toUpperCase()=='WW' )
            {
                Dis_index2=Dis_index-3;
                $('td:eq('+Dis_index2+')', nRow).css({
                        "background-color": "#C00000",
                        "font-weight": "bold",
                        "color":"black",
                        "text-align":"center"
                      });
                $('td:eq('+Dis_index2+')', nRow).html( 'WW' );
            }
            if ( aData[Dis_index].toUpperCase()=='WS' )
            {
                Dis_index2=Dis_index-3;
                $('td:eq('+Dis_index2+')', nRow).css({
                        "background-color": "#C00000",
                        "font-weight": "bold",
                        "color":"black",
                        "text-align":"center"
                      });
                $('td:eq('+Dis_index2+')', nRow).html( 'WS' );
            }
       }
       if(aData[valid_index]!==null)
       {
           if(aData[valid_index].toUpperCase()=='SI')
            {
                valid_index2=valid_index-3;
                if(aData[pflash_index]!=null)
                {
                    if(aData[pflash_index].toUpperCase()=='SI')
                    {
                        $('td:eq('+valid_index2+')', nRow).html( "<img src='"+assets_url+"images/custom/WW/dossier.png'></img>" );
                    }
                    else
                    {
                        $('td:eq('+valid_index2+')', nRow).html( 'V' );
                    }
                }
                else
                {
                    $('td:eq('+valid_index2+')', nRow).html( 'V' );
                }
            }
            else
            {
                valid_index2=valid_index-3;
                $('td:eq('+valid_index2+')', nRow).html( '-' );
            }
       }
}

function custom_risultati_CANDID(table,nRow, aData, iDataIndex)
{
        var Dis_index=Lookup_RealColumnIndex(table, 'Stato') ;
        var Dis_index2=0;
        var wwws_index=Lookup_RealColumnIndex(table, 'wwws') ;
        var valid_index=Lookup_RealColumnIndex(table, 'Valid') ;
        var valid_index2=0;
        var pflash_index=Lookup_RealColumnIndex(table, 'Dossier') ;
        var pflash_index2=0;
        var recordstatus_index=Lookup_RealColumnIndex(table, 'recordstatus_') ;
        
    if(aData[recordstatus_index]=='new')
    {
       $('td:eq(0)', nRow).css({
                         "background-color": "red",
                         "font-weight": "bold",
                         "color":"white",
                         "text-align":"center"
                       }); 
    }
    
    if(aData[Dis_index]!==null)
    {
         if ( aData[Dis_index]=='Disponibile' )
         {
             Dis_index2=Dis_index-3;
             $('td:eq('+Dis_index2+')', nRow).css({
                     "background-color": "#92D050",
                     "font-weight": "bold",
                     "color":"black",
                     "text-align":"center"
                   });
           $('td:eq('+Dis_index2+')', nRow).html( 'D' );
         }
         if ( aData[Dis_index]=='Archiviato' )
         {
             Dis_index2=Dis_index-3;
             $('td:eq('+Dis_index2+')', nRow).css({
                     "background-color": "#AAA580",
                     "font-weight": "bold",
                     "color":"black",
                     "text-align":"center"
                   });
           $('td:eq('+Dis_index2+')', nRow).html( 'A' );
         }
         if ( aData[Dis_index]=='Negativo' )
         {
             Dis_index2=Dis_index-3;
             $('td:eq('+Dis_index2+')', nRow).css({
                     "background-color": "#7F7F7F",
                     "font-weight": "bold",
                     "color":"black",
                     "text-align":"center"
                   });
           $('td:eq('+Dis_index2+')', nRow).html( 'N' );
         }
         if ( aData[Dis_index]=='Attivo WW' )
         {
             Dis_index2=Dis_index-3;
             $('td:eq('+Dis_index2+')', nRow).css({
                     "background-color": "#C00000",
                     "font-weight": "bold",
                     "color":"black",
                     "text-align":"center"
                   });
            $('td:eq('+Dis_index2+')', nRow).html( 'WW' );
         }
         if ( aData[Dis_index]=='Attivo WS' )
         {
             Dis_index2=Dis_index-3;
             $('td:eq('+Dis_index2+')', nRow).css({
                     "background-color": "#C00000",
                     "font-weight": "bold",
                     "color":"black",
                     "text-align":"center"
                   });
            $('td:eq('+Dis_index2+')', nRow).html( 'WS' );
         }
    }
    
    if(aData[valid_index]!==null)
    {
        valid_index2=valid_index-3;
         $('td:eq('+valid_index2+')', nRow).css({
              "text-align":"center"
            });
        if(aData[valid_index].toUpperCase()=='SI')
         {
             $('td:eq('+valid_index2+')', nRow).html( 'V' );
         }
         else
         {
             valid_index2=valid_index-3;
             $('td:eq('+valid_index2+')', nRow).html( '-' );
         }
    }
    
 
    if(aData[pflash_index]!=null)
    {
        console.info('test');
        pflash_index2=pflash_index-3;
        if(aData[pflash_index].toUpperCase()=='SI')
        {
            $('td:eq('+pflash_index2+')', nRow).html( "<img src='"+assets_url+"images/custom/WW/dossier.png'></img>" );
        }
        else
        {
            $('td:eq('+pflash_index2+')', nRow).html( '' );
        }
    }
 

}

function custom_risultati_Immobili(table,nRow, aData, iDataIndex)
{

        var Dis_index2=1;
            if(iDataIndex==0)
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8822/jdocwebtest/Immobile0.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(iDataIndex==1)
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8822/jdocwebtest/Immobile1.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(iDataIndex==2)
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8822/jdocwebtest/Immobile2.jpg" style="height:100px;width:100px;"></img>' );
          }
          console.info(aData);
          if(aData[6]=='disponibile')
          {
              $('td:eq(5)', nRow).css({
                        "color":"green"
                      });
          }
          
          if(aData[6]=='non disponibile')
          {
              $('td:eq(5)', nRow).css({
                        "color":"red"
                      });
          }
}

function datatable_records_preview(table,nRow, aData, iDataIndex,tableid)
{
   console.info(nRow);
    $(aData).each(function(i)
    {
        console.info(this);
        var dataTables_wrapper=$(table).closest('.dataTables_wrapper');
        var value=this;
        var content=value;
        var index=i-3;
        var column=$(dataTables_wrapper).find('#column_'+i);
        var fieldtypeid=$(column).data('fieldtypeid');
        var linkedtableid=$(column).data('linkedtableid');
        var fieldid=$(column).data('fieldid');
        if(fieldtypeid==='linked')
        {
            console.info('linked');
            var value_array=value.split('|:|');
            content=value_array[0];
            var linked_recordid=value_array[1];
            if(linked_recordid!=='')
            {
                var thumbnail_url=jdocserver_url+'record_preview/'+linkedtableid+'/'+linked_recordid+'.jpg';
                content=content.replace('record_preview','<img align="left" src="'+thumbnail_url+'?'+ new Date().getTime()+'" style="height:50px;width:50px;"></img>');
            }
            else
            {
                content=content.replace('record_preview','');
            }
        }
        if(fieldtypeid=='Numero')
        {
           content=content.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1'");
        }
        //var td=$(nRow).filter(function(){ return $(this).text() === value;})
        $(nRow).find('td').each(function(i){
            var tdValue=$(this).html();
            tdValue=tdValue.replace('&amp;','&');
            tdValue=tdValue.replace('&gt;','>');
            tdValue=tdValue.replace('&lt;','<');
            if(tdValue == value)
            {
                 console.info(content);
                $(this).html(content);
            }
        })
        //$(td).html('test');
        //$('td:eq('+index+')', nRow).html(content);
        
    })
    var record_preview_index=Lookup_RealColumnIndex(table, 'Anteprima') ;
    if(typeof(record_preview_index) !== 'undefined')
    {
        record_preview_index=record_preview_index-3;    
        var thumbnail_url=jdocserver_url+'record_preview/'+tableid+'/'+aData[0]+'.jpg';
        imageExists(thumbnail_url, function(exists) {
        if(exists)
        {
            $('td:eq('+record_preview_index+')', nRow).html( '<div><img src="'+thumbnail_url+'?'+ new Date().getTime()+'" style="height:110px;width:110px;"></img></div>' );
        }
        else
        {
            $('td:eq('+record_preview_index+')', nRow).html( '<div><img src="'+assets_url+'/images/document.png" style="height:110px;width:150px;"></img></div>' );
        }
      });
  }
    
        

}
 
function custom_risultati_Documenti(table,nRow, aData, iDataIndex)
{
        var Dis_index2=1;
            if(aData[0]=='00000000000000000000000000000002')
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8888/JDocServer/archivi/documenti/000/00000002_thumbnail.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(aData[0]=='00000000000000000000000000000003')
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8888/JDocServer/archivi/documenti/000/00000004_thumbnail.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(aData[0]=='00000000000000000000000000000001')
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8888/JDocServer/archivi/documenti/000/00000003_thumbnail.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(aData[0]=='00000000000000000000000000000004')
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8888/JDocServer/archivi/documenti/000/00000005_thumbnail.jpg" style="height:100px;width:100px;"></img>' );
          }
          if(aData[0]=='00000000000000000000000000000005')
            {
              $('td:eq('+Dis_index2+')', nRow).html( '<img src="http://localhost:8888/JDocServer/archivi/documenti/000/00000006_thumbnail.jpg" style="height:100px;width:100px;"></img>' );
          }
          
} 

//custom about
function custom_risultati_aziende(table,nRow, aData, iDataIndex,today)
{
        var dacontattare_index=Lookup_RealColumnIndex(table, 'Da Contattare') ;
        var dacontattare_index2=dacontattare_index-3;
        if(aData[dacontattare_index]=='no')
        {
            $('td:eq('+dacontattare_index2+')', nRow).css({
                      "color":"green"
                    });
        }
        if(aData[dacontattare_index]=='si')
        {
            $('td:eq('+dacontattare_index2+')', nRow).css({
                      "color":"red"
                    });
        }
}
 
 //ALLEGATI VISUALIZZATORE
 //APERTURA ALLEGATO
 function apri_allegato(el,recordid,path_,filename_,extension_) {
     console.info('apri_allegato');
            var file_container = $(el).closest('.file_container');
            var files_container=$(el).closest('#files_container');
            console.info(files_container);
            $(files_container).find('.file_checkbox').each(function(i){
                console.info('file checkbox');
                        $(this).prop('checked', false);
            });
            $(file_container).find('.file_checkbox').prop('checked', true);
            var scheda_record=$(el).closest('.scheda_record');
            var tableid=$(scheda_record).data('tableid');
            var pathcompleta;
            var allegati=$(el).closest('.allegati');
            var connected_sortable=$(el).closest('.connectedSortable');
            $(connected_sortable).find('.allegato_selected').each(function(i){
                $(this).removeClass('allegato_selected');
            });
            
            $(el).addClass('allegato_selected');
  
        
            filename_=filename_+'.'+extension_;
            var baseurl=controller_url+'ajax_load_block_visualizzatore';
            var serialized_data=[];
            serialized_data.push({name: 'path', value: path_});
   
            var url=baseurl+'/'+filename_+'/'+extension_+'/'+recordid+'/'+tableid;
            
            
            $.ajax({
                url: url,
                data: serialized_data,
                type: 'post',
                success:function(data){
                    var scheda_container_visualizzatore=$(allegati).find('.scheda_container_visualizzatore');
                    $(scheda_container_visualizzatore).show();
                    $(scheda_container_visualizzatore).html(data);
                    //$(el).closest('.file_container').find('.originalname').toggle();
                    //$(el).closest('.file_container').find('.file_container_menu').toggle();
                    
                    
                },
                error:function(){
                    $('#visualizzatore').html('fallimento');
                }
            });
        }
        
 function popup_allegato(el,recordid,path_,filename_,extension_) 
 {
            var scheda_record=$(el).closest('.scheda_record');
            var tableid=$(scheda_record).data('tableid');
            var pathcompleta;
            var allegati=$(el).closest('.allegati');
            var connected_sortable=$(el).closest('.connectedSortable');
            $(connected_sortable).find('.allegato_selected').each(function(i){
                $(this).removeClass('allegato_selected');
            });
            
            $(el).addClass('allegato_selected');
  
        
            filename_=filename_+'.'+extension_;
            var baseurl=controller_url+'ajax_load_block_visualizzatore';
            var url=baseurl+'/'+filename_+'/'+extension_+'/'+recordid+'/'+tableid;
            var serialized_data=[];
            serialized_data.push({name: 'path', value: path_});
            
            $.ajax({
                url: url,
                data: serialized_data,
                type: 'post',
                success:function(data){
                    var visualizzatore_popup=$(scheda_record).find(".visualizzatore_popup");
                    bPopup_visualizzatore=$(visualizzatore_popup).bPopup();
                    $(visualizzatore_popup).find(".visualizzatore_popup_content").html(data);
                    
                    
                },
                error:function(){
                    $('#visualizzatore').html('fallimento');
                }
            });
        }
        
  //STAMPA ALLEGATO
  function stampa_allegato(el,percorso)
  {
      PDFtoPrint=document.getElementById('PDFtoPrint'); 
      PDFtoPrint.contentWindow.print();
  }
  //LISTA FILES - SORTABLE
  
  function update_connectedSortable(event,ui)
  {
      
       var connectedSortable=$(ui.item).closest('.connectedSortable');
       console.info(connectedSortable);
       //console.info($(ui.item));
       //$(ui.item).find('.originalname').toggle();
       //$(ui.item).find('.file_container_menu').toggle();
       update_order_lista_files(connectedSortable,'ordinamento');
  }
  function update_order_lista_files(connectedSortable,funzione)
  {
      console.info('funzione:update_order_lista_files');
      var index=0;
      $(connectedSortable).find('.file_container').each(function(i){
          var input_fileid=$(this).find('.fileid');
          var input_fileparam=$(this).find('.fileparam');
          var input_fileindex=$(this).find('.fileindex');
          if($(input_fileparam).val()!='delete')
              {
                $(input_fileindex).val(index);
                index=index+1; 
              }
      });
      var scheda=$(connectedSortable).closest('.scheda');
      //var funzione=$(scheda).data('funzione');
      var tableid=$(scheda).data('tableid');
      var recordid=$(scheda).data('recordid');
      var schedaid=$(scheda).data('schedaid');
      /*if((funzione=='inserimento')&&(schedaid='scheda_allegati')&&(recordid!=null))
      {
          salva_modifiche_allegati(connectedSortable,tableid,recordid);
      }*/
      var scheda_record=$(connectedSortable).closest('.scheda_record')
    var recordid=$(scheda_record).data('recordid');
    if(schedaid=='scheda_allegati')
    {
        if((recordid!='null')&&(recordid!=null))
        {
           salva_modifiche_allegati(connectedSortable,tableid,recordid,funzione); 
        }
        else
        {
           //alert('salva modifiche allegato non allegato');
        }
    }
    if(schedaid=='scheda_code')
    {
        salva_modifiche_coda(connectedSortable,tableid,recordid); 
    }
  }
  
  function update_order_impostazioni_archivio_campi(event,ui)
  {
      var impostazioni_archivio_campi_form=$(ui.item).closest('#impostazioni_archivio_campi_form');
      $(impostazioni_archivio_campi_form).find('.impostazioni_fieldcontainer').each(function(i)
      {
          var fieldcontainer=$(this);
          var field_order=$(fieldcontainer).find('.order');
          $(field_order).val(i);
      });
  }
  
  function allega_file(el)
  {
      var allegati=$(el).closest('.allegati');
      var file_container=$(el).closest('.file_container');
      $(file_container).find('.left_icon').hide();
      //$(file_container).find('.right_icon').show();
      var scheda_allegati=$(allegati).find('.scheda_allegati');
      var files_container=$(scheda_allegati).find('#files_container');
      $(files_container).append(file_container);
      update_order_lista_files(files_container);
      
  }
  
  function allega_tutti_file(el)
  {
      var allegati=$(el).closest('.allegati');
      var scheda_allegati=$(allegati).find('.scheda_allegati');
      var files_container=$(scheda_allegati).find('#files_container');
      var connectedSortable=$(el).closest('.connectedSortable');
      $(connectedSortable).find('.file_container').each(function(i){
          $(this).find('.left_icon').hide();
        // $(this).find('.right_icon').show();
         $(files_container).append(this);
      });
      update_order_lista_files(files_container);
  }
  
  function UploadFile(el)
    {

            //$('#upload_target').html(''); //resetto il contenitore della lista caricata con ajax
            var scheda_record=$(el).closest('.scheda_record');
            var popuplvl=$(scheda_record).data('popuplvl');
            var url=controller_url+'uploadfile/'+popuplvl;
            var form=$(el).closest('#form_upload');
            var form_upload_loading=$(form).next();
            $(form).hide();
            $(form_upload_loading).show();
            
 
    $.ajax( {
      url: url,
      type: 'POST',
      data: new FormData($(form)[0]),
      processData: false,
      contentType: false,
      success: function( response ) {
                $(form).show();
                $(form_upload_loading).hide();
                update_sys_batch_temp(form);
            },
            error:function(){
                alert('errore'); 
            }
    } );

            /*
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#form_upload_'+uploadid).serialize(),
            success: function( response ) {
                updatelista(uploadid);

            },
            error:function(){
                alert('errore'); 
            }
        } ); */
            //setTimeout('updatelista()',1500);
        
    }
  
function updatelista(uploadid)
    {
       // if (primoupdate==false){
       var file_toupload=$('#file_toupload_'+uploadid);
        $('#file_toupload_'+uploadid).val(''); //resetto il valore dell'input type file
        var url=controller_url+'ajax_load_block_lista_files/inserimento/coda/sys_batch_temp/';
             $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    var files_sys_batch_creata_container_=$('#files_sys_batch_creata_container_'+uploadid);
                    $('#files_sys_batch_creata_container_'+uploadid).html(data);
                    //var allega_tutti=$('#files_sys_batch_creata_container').find('.allega_tutti_button')[0];    
                   // allega_tutti_file(allega_tutti);
                   caricaInLista($('#files_sys_batch_creata_container_'+uploadid));
                },
                error:function(){
                    alert('fallimento');
                    $('#files_sys_batch_creata_container_'+uploadid).html('fallimento');
                }
            });
       //}
      // primoupdate=false; //metto a false... questa procedura mi consente di evitare di caricare il contenuto della sys_temp_batch
    }
  function update_sys_batch_temp(form)
    {
       // if (primoupdate==false){
        $(form).find('#file_toupload').val(''); //resetto il valore dell'input type file
        var scheda_record=$(form).closest('.scheda_record');
        var popuplvl=$(scheda_record).data('popuplvl');
        var block_upload_files=$(form).closest('.block_upload_files');
        var url=controller_url+'ajax_load_block_sys_batch_temp/'+popuplvl;
             $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    var files_sys_batch_temp=$(block_upload_files).find('#files_sys_batch_temp');
                    $(files_sys_batch_temp).html(data);
                   caricaInLista(files_sys_batch_temp);
                },
                error:function(){
                    alert('fallimento');
                }
            });
       //}
      // primoupdate=false; //metto a false... questa procedura mi consente di evitare di caricare il contenuto della sys_temp_batch
    }
    
  function caricaInLista(el)
  {
     var scheda=$(el).closest('.scheda');
     var files_container=$(scheda).find('#files_container');
     $(files_container).find('.file_container').each(function(i){
         $(this).remove();
     });
     $(el).find('.file_container').each(function(i){
         $(this).find('input').each(function(i){
                        var original_name=$(this).attr('name');
                        var new_name=original_name.replace('[null]', '[insert]');
                        var new_name=new_name.replace('[update]', '[insert]');
                        $(this).attr('name',new_name);
                        })
          //$(this).find('.left_icon').hide();
         //$(this).find('.right_icon').show();
          $(files_container).append(this);
      });
      update_order_lista_files(files_container); 
      $( ".connectedSortable" ).sortable({
                      connectWith: ".connectedSortable",
                      update: function( event, ui ) {
                          update_connectedSortable(event, ui);
                      }
                    }).disableSelection();
  }
  
  function nonallegare_file(el)
  {
      var file_container=$(el).closest('.file_container');
      $(file_container).remove();
  }
  
  function elimina_file(el)
  {
      var confirmation=confirm('Sicuro di voler eliminare questo allegato?')
    if(confirmation)
    {
        var file_container=$(el).closest('.file_container');
        $(file_container).find('.fileparam').val('delete');
        $(file_container).find('input').each(function(i){
            var original_name=$(this).attr('name');
            var new_name=original_name.replace('[null]', '[delete]');
            var new_name=new_name.replace('[update]', '[delete]');
            $(this).attr('name',new_name);
        })
        var connectedSortable=$(el).closest('.connectedSortable');
        //$(file_container).css('opacity', '0.5');
        $(file_container).hide();
        $(file_container).data('delete','true');
        update_order_lista_files(connectedSortable);
    }
        
     /* var file_container=$(el).closest('.file_container');
      if($(file_container).data('delete'))
        {
            $(file_container).find('.fileparam').val('');
            $(file_container).find('input').each(function(i){
                var original_name=$(this).attr('name');
                var new_name=original_name.replace('[delete]', '[update]');
                $(this).attr('name',new_name);
            })
            var connectedSortable=$(el).closest('.connectedSortable');
            $(file_container).css('opacity', '1');
            update_order_lista_files(connectedSortable);
        }
        else
        {
            $(file_container).find('.fileparam').val('delete');
            $(file_container).find('input').each(function(i){
                var original_name=$(this).attr('name');
                var new_name=original_name.replace('[null]', '[delete]');
                var new_name=new_name.replace('[update]', '[delete]');
                $(this).attr('name',new_name);
            })
            var connectedSortable=$(el).closest('.connectedSortable');
            //$(file_container).css('opacity', '0.5');
            $(file_container).hide();
            $(file_container).data('delete','true');
            update_order_lista_files(connectedSortable);
        }
      */
                    
      
  }
  
  
  
  
  
  //INSERIMENTO
function inserisci(el,param)
{
    var block_scheda_container=$(el).closest('.scheda_container');
    var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
    var tipo_scheda_container=$(block_dati_labels).data('scheda_container');
    var origine=$(block_scheda_container).data('origine');
    var origine_id=$(block_scheda_container).data('origine_id');
    if(origine=='linked_table')
    {
        var tables_container=$('#'+origine_id);
        var origine_block_dati_labels=$(tables_container).closest('.block_dati_labels');
        var origine_recordid=$(origine_block_dati_labels).data('recordid');
        var origine_tableid=$(origine_block_dati_labels).data('tableid');
        var master_origine_field_value=$(block_dati_labels).find('#records_linkedmaster_field_'+origine_tableid);
        $(master_origine_field_value).val(origine_recordid);
    }
    var labels=$(block_dati_labels).find('.labels');
    var form_dati_labels=$(block_dati_labels).find('.form_dati_labels');
    var recordid=$(block_dati_labels).data('recordid');
    var tableid=$(block_dati_labels).data('tableid')
    if(recordid==null)
    {
        $(labels).find('.tables_container').each(function(index){
            $(form_dati_labels).append(this);
        })
        var block_allegati=$(block_scheda_container).find('.block_allegati');
        var files_container=$(block_allegati).find('.files_container');
        $(files_container).find('.file_container').each(function(i){
            var input_fileorigine=$(this).find('.fileorigine');
            if(($(input_fileorigine).val()=='coda')||($(input_fileorigine).val()=='autobatch'))
                {
                    $(this).find('input').each(function(i){
                    var original_name=$(this).attr('name');
                    var new_name=original_name.replace('[null]', '[insert]');
                    var new_name=new_name.replace('[update]', '[insert]');
                    $(this).attr('name',new_name);
                    })
                }
            $(form_dati_labels).append(this);
        });
        //$(form_dati_labels).append(files_container);
        //custom Work&Work contra
        if(tableid=='CONTRA')
        {
             $(form_dati_labels).append($('#contra_check'));
        }
        var url=controller_url+'ajax_salva_modifiche_record/'+tableid+'/null';
        $.ajax( {
            type: "POST",
            url: url,
            data: $(form_dati_labels).serialize(),
            success: function( response ) {
                    recordid=response.replace(";","");
                   /* if(tipo_scheda_container=='scheda_dati_inserimento')
                    {
                        
                        if(param=='nuovo')
                        {
                            ajax_load_block_dati_labels(tableid,'null','inserimento',tipo_scheda_container,block_dati_labels);
                            //ajax_load_block_allegati(tableid,'null','inserimento',block_allegati);
                            var scheda_allegati_container=$('#scheda_allegati_container');
                            var files_container=$(scheda_allegati_container).find('#files_container');
                            var scheda_container_visualizzatore=$('.scheda_container_visualizzatore');
                            $(scheda_container_visualizzatore).html('');
                            //$(files_container).find('.file_container').remove();
                            //ajax_load_content(el,'ajax_load_content_inserimento/'+tableid+'/desktop')
                        }
                        else
                        {
                            ajax_load_block_dati_labels(tableid,recordid,'inserimento',tipo_scheda_container,block_dati_labels);
                            ajax_load_block_allegati(tableid,recordid,'inserimento',block_allegati);
                            if(tableid=='CANDID')
                            {
                                $('#btnCaricaOnline').show();
                            }
                        }
                        //$('#btnNuovo').show();
                    }
                    */
                    if(tipo_scheda_container=='scheda_record')
                    {
                        var scheda_origine=$(el).closest('.scheda');
                        var popuplvl=$(scheda_origine).data('popuplvl');
                        var origine_target=$(scheda_origine).data('target');
                        
                        
                        if(param=='nuovo')
                        {
                            //record_click(null,'null',tableid,null);
                            if(origine_target=='popup')
                            {
                                //apri_scheda_record(el,tableid,'null','popup','allargata',origine);
                                ajax_load_block_dati_labels(tableid,'null','inserimento',tipo_scheda_container,block_dati_labels);
                                var scheda_container_visualizzatore=$(block_scheda_container).find('.scheda_container_visualizzatore');
                                $(scheda_container_visualizzatore).html('');
                            }
                            
                            if(origine_target=='right')
                            {
                                var risultati_ricerca=$('#risultati_ricerca');
                                var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,'null','right','standard_dati',origine);
                            }
                            //apri_scheda_record(el,tableid,'null','popup','allargata');
                        }
                            //record_click(null,recordid,tableid,null); 

                        if(param=='continua')
                        {
                            if(origine_target=='popup')
                            {
                                apri_scheda_record(el,tableid,recordid,'popup','allargata',origine,'modifica');
                                try {
                                bPopup[popuplvl].close();
                                }
                                catch (e)
                                {
                                    console.info(e);
                                }
                            }
                            
                            if(origine_target=='right')
                            {
                                var risultati_ricerca=$('#risultati_ricerca');
                                var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,recordid,'right','standard_dati',origine);  
                            }
                        }
                        
                        if(param=='chiudi')
                        {
                            if(origine=='risultati_ricerca')
                            {
                                var risultati_ricerca=$('#risultati_ricerca');
                                var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
                                apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,recordid,'right','standard_dati',origine);  
                            }
                            try {
                            bPopup[popuplvl].close();
                            }
                            catch (e)
                            {
                                console.info(e);
                            }
                            
                        }
                        
                        //CUSTOM WORK&WORK
                        if(param=='caricaonline')
                        {
                            /*apri_scheda_record(el,tableid,recordid,'popup','allargata',origine,'modifica',function(el_cb){
                                loadToJDocOnlineCV(el_cb,tableid,recordid);
                            });*/
                            var btn_loadToJDocOnlineCV=$(block_scheda_container).find('.btn_loadToJDocOnlineCV')
                            loadToJDocOnlineCV(btn_loadToJDocOnlineCV,tableid,recordid);
                            try {
                                bPopup[popuplvl].close();
                            }
                            catch (e)
                            {
                                console.info(e);
                            }
                        }
                        
                        if(origine=='risultati_ricerca')
                        {
                            ajax_load_block_risultati_ricerca(el,tableid);
                                                          
                        }
                        if(origine=='linked_table')
                        {
                            var tables_container=$('#'+origine_id);
                            load_tables_labelcontainer(tables_container,'refresh');
                        }
                    }
                    
            },
            error:function(){
                alert('errore'); 
            }
        } ); 
    }
    else
    {
        if(param=='nuovo')
        {
            ajax_load_content(el,'ajax_load_content_inserimento/'+tableid+'/desktop')
        }
    }
}





//VISUALIZZATORE
//ALLARGA ALLEGATO
function allarga_allegato(el)
{
    var scheda_container=$(el).closest('.scheda_container');
    var allargato=$(el).data('allargato');
    if(allargato==false)
    {
        
       
       var campi_fissi=$(scheda_container).find('#campi_fissi');
       campi_fissi.hide(500);
       var lista_allegati=$(scheda_container).find('#lista_allegati');
       lista_allegati.hide(500);
       var dati_e_allegati=$(scheda_container).find('#dati_e_allegati');
       dati_e_allegati.hide();

       
        var visualizzatore_content_clone=$(scheda_container).find('#visualizzatore_content').clone();
        $(visualizzatore_content_clone).find('#btn_allarga_allegato').data('allargato','true');
        $(visualizzatore_content_clone).attr('id', 'visualizzatore_content_clone');
        $(scheda_container).append(visualizzatore_content_clone);
    }
    else
    {
        var visualizzatore_content_clone=$(scheda_container).find('#visualizzatore_content_clone');
        $(visualizzatore_content_clone).remove(); 
       var campi_fissi=$(scheda_container).find('#campi_fissi');
       campi_fissi.show(500);
       var lista_allegati=$(scheda_container).find('#lista_allegati');
       lista_allegati.show(500);
       var dati_e_allegati=$(scheda_container).find('#dati_e_allegati');
       dati_e_allegati.show();

       $(el).data('allargato','true');
        
    }
 
}



//SCHEDA RECORD FISSI
//salva foto record
function salva_foto_record(el)
{
    alert('salva');
}


//SCHEDE SALVATE
function cambio_archivio_schede_salvate(el)
    {
        var tableid=$(el).val();
        var url=controller_url+'ajax_cambio_archivio_schede_salvate/'+tableid;
        $.ajax( {
            dataType:'html',
            url: url,
            success: function( response ) {
                $('#query').html(response);

                var url=controller_url+'ajax_load_block_risultati_ricerca/'+tableid;
                $.ajax( {
                    type: "POST",
                    url: url,
                    success: function( response ) {
                        $('#target').html('');
                        $('#target').append(response);

                    },
                    error:function(){
                        alert('errore');
                    }
                } ); 
                
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    
    


function ajax_load_fields_record_linkedtable_modifica(el,linkedtableid,funzione,recordid,numcol)
{
    var url=controller_url+'ajax_load_block_fields_record_linkedtable/'+linkedtableid+'/null/null/'+funzione+'/'+recordid;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data!='')
                    {
                        var td_container=$(el).closest('td');
                        $(td_container).html(data);
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_table(el,tableid,label,funzione,recordid)
{
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var tables_container=$(el).closest('.tables_container');
    var type=$(tables_container).data('type');
    var mastertableid=$(tables_container).data('mastertableid');
    var url=controller_url+'ajax_load_block_table/'+tableid+'/'+label+'/null/null/'+type+'/'+mastertableid+'/'+funzione+'/'+recordid+'/'+scheda_container;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data!='')
                    {
                        var table_container=$(el).closest('.table_container');
                        $(table_container).replaceWith(data);
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_records_linkedmaster(el,linkedmaster_tableid,linkedmaster_recordid,master_tableid,funzione)
{
    if(linkedmaster_recordid=='')
        linkedmaster_recordid='null';
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var url=controller_url+'ajax_load_block_records_linkedmaster/'+linkedmaster_tableid+'/'+linkedmaster_recordid+'/'+master_tableid+'/'+funzione;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data!='')
                    {
                        var table_container=$(el).closest('.table_container');
                        $(table_container).replaceWith(data);
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}


function ajax_load_allegati(el,tableid,recordid,funzione)
{
    var url=controller_url+'ajax_load_block_allegati/'+tableid+'/'+recordid+'/'+funzione;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    if(data!='')
                    {
                        var block_allegati=$(el).closest('.block_allegati');
                        $(block_allegati).replaceWith(data);
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}

//RECORDS_LINKEDMASTER


function ajax_load_fields_record_linkedmaster(el,tableid,funzione)
{
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var table_container=$(el).closest('.table_container');
    var fields_record_linkedmaster=$(table_container).find('.fields_record_linkedmaster');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var recordid=$(el).val();
    var url=controller_url+'ajax_load_block_table/'+tableid+'/null/null/null/null/null/scheda/'+recordid+'/'+scheda_container;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    $(fields_record_linkedmaster).html(data);
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_fissi_linkedmaster(el,tableid)
{
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var table_container=$(el).closest('.table_container');
    var fissi_record_linkedmaster=$(table_container).find('.fissi_record_linkedmaster');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var recordid=$(el).val();
    var url=controller_url+'ajax_load_block_fissi/'+tableid+'/'+recordid;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    $(fissi_record_linkedmaster).html(data);
                    $(fissi_record_linkedmaster).show();
                    $(fissi_record_linkedmaster).click(function(){
                        apri_scheda_record(this,tableid,recordid,'popup','allargata','records_linkedmaster');
                    });
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_fields_record_linkedtable(el,tableid,funzione,recordid,numcol)
{
    var block_dati_labels=$(el).closest('.block_dati_labels');
    var scheda_container=$(block_dati_labels).data('scheda_container');
    var label='null';
    var table_index='null';
    var table_param='null';
    var type='linked';
    var mastertableid='null';
    var url=controller_url+'ajax_load_block_table/'+tableid+'/'+label+'/'+table_index+'/'+table_param+'/'+type+'/'+mastertableid+'/'+funzione+'/'+recordid+'/'+scheda_container;
    $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    //$(el).toggleClass('selected');
                    if(data!='')
                    {
                        if($(el).data('selected'))
                            {
                                //$(el).removeClass('DTTT_selected');
                                var fields_record_linketable=$(el).next();
                                $(fields_record_linketable).remove();
                                $(el).data('selected',false);
                            }
                        else
                            {
                                //$(el).addClass('DTTT_selected');
                                $(el).data('selected',true)
                                $(el).after('<tr><td colspan="'+numcol+'" style="padding:0px;">'+data+'</td></tr>')
                            }
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}

//STAMPE
//STAMPA CONTRATTI
function stampa_contratti()
{
    var url=controller_url+'/stampa_contratti'
    var urldownload=controller_url+'/download_contratti/'
    $.ajax({
        url: url,
        success:function(data){
            //$('#target').html(data);
            alert('successo');
            //window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function stampa_vis_contratto(recordid){
    var url=controller_url+'/stampa_vis_contratto/'+recordid;
    var urldownload=controller_url+'/download_profilo/';
            $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    window.location.href = urldownload + data;
 
                },
                error:function(){
                    alert('errore');
                }
            });
}

function stampa_vis_profilorischio(recordid){
    var url=controller_url+'/stampa_vis_profilorischio/'+recordid;
    var urldownload=controller_url+'/download_profilo/';
            $.ajax({
                url: url,
                dataType:'html',
                success:function(data){
                    window.location.href = urldownload + data;
 
                },
                error:function(){
                    alert('errore');
                }
            });
}


        
        
  //IMPOSTAZIONI
  //mostra i campi preferiti
    function ShowCampiPreferenza()
    {
        var indirizzo_blocco_preferenze=controller_url+'ajax_load_block_macrogruppo_preferenze/desktop';
        $.ajax({
            url : indirizzo_blocco_preferenze,
            success : function (data) {
                $('#sottogruppo').html('');
                $("#sottogruppo").html(data);
            },
            error : function () {
                alert("ERRORE RICHIESTA AJAX!");
            }
        });
    }
    function ShowCampiPreferiti()
    {
        var indirizzo_blocco_preferenze=controller_url+'ajax_load_block_macrogruppo_preferenze/desktop';
        $.ajax({
            url : indirizzo_blocco_preferenze,
            success : function (data) {
                $('#sottogruppo').html('');
                $("#sottogruppo").html(data);
            },
            error : function () {
                alert("ERRORE RICHIESTA AJAX!");
            }
        });
    }
    
    function ChangeArchives()
    {
        var indirizzo= controller_url + 'ajax_load_block_campi_preferenze/desktop'; // variabile da inizializzare al primo script - mi servirà per la richiesta ajax di carico del blocco campi ricerca
        var chiave = $('.ui-selected').data("chiave");
        //alert(stringaSelezionata.toUpperCase());
        if ((chiave == "keylabel") || (chiave=='keylabel_scheda') || (chiave=='keylabel_inserimento'))
            indirizzo= controller_url + 'ajax_load_block_labels';
        var url;
        if($('#archivio').val()!="null"){
                url=indirizzo + '/' + $('#archivio').val();
            $.ajax( {
                type: "POST",
                url: url,
                success: function(response) {
                    if(chiave!='creazione_campi'){
                        var campipreferenze= $('#campipreferenze');
                        $(campipreferenze).html('');
                        $(campipreferenze).append(response);
                    }
                },
                error: function()
                    {
                        alert("Errore Richiesta Ajax");
                    }
            } ); 
        }
        if ((chiave == "keylabel") || (chiave=='keylabel_scheda') || (chiave=='keylabel_inserimento'))
            LoadSavedPreferencesLabel($('#archivio').val(),chiave);
        else
            LoadSavedPreferences_NewVersion($('#archivio').val());
    }
    
    function ChangeArchivesCampiArchivi()
    {
        var indirizzo= 'sys_viewcontroller/ajax_load_block_campi_archivi_preferenze/desktop'; // variabile da inizializzare al primo script - mi servirà per la richiesta ajax di carico del blocco campi ricerca
        var url;
        //alert("ciao");
        //alert($('#archivio').val());
        if($('#archivio').val()!="null"){
                url=indirizzo + '/' + $('#archivio').val();
           // alert(url);
            $.ajax( {
                type: "POST",
                url: url,
                success: function( response ) {
                    $('#campipreferenze').html('');
                    $('#campipreferenze').append(response);
                },
                error: function()
                    {
                        alert("Errore Richiesta Ajax");
                    }
            } ); 
        }
            LoadSavedPreferences_NewVersion($('#archivio').val());
    }
    
    function ShowLayoutSettings()
    {
        var indirizzo_blocco_layout=controller_url + 'ajax_load_block_macrogruppo_layout/desktop';
        $.ajax({
            url: indirizzo_blocco_layout,
            success : function (data) {
                $('#sottogruppo').html('');
                $("#sottogruppo").html(data);
            },
            error : function () {
                alert("ERRORE RICHIESTA AJAX!");
            }
        });
    }
    
    function ShowSuperuserSettings()
    {
        var processogit= controller_url + 'run_git';
        $.ajax({
           url: processogit,
           success:function(data)
           {
               alert("PROGRAMMA AVVIATO CORRETTAMENTE");
           },
           error:function(data)
           { alert("ERRORE RICHIESTA AJAX");}
        });
        /*var indirizzo_blocco_superuser_settings = controller_url + 'ajax_load_blocco_superuser_settings/';
        $.ajax({
            url: indirizzo_blocco_superuser_settings,
            success:function(data)
            {
                $('#sottogruppo').html('');
                $('#sottogruppo').html(data);
            },
            error:function()
            {
                alert("ERRORE RICHIESTA AJAX");
            }
        });*/
    }
    
    function LoadSavedPreferencesLabel(idarchivio,typeLabels)
    {
        var address=controller_url + 'ajax_load_block_LoadPreferencesLabel/' + idarchivio + '/' + typeLabels;
        $.ajax({
            url : address,
            success : function (data) {
                $("#precaricato").html("<div align='right'><button onclick='" + bottoneSalvataggio + "'><i><b>Salva Preferenze</b></i></button></div><br>" + data);
                $( "#sortable" ).sortable({
                    placeholder: "ui-state-highlight",
                    update: function(event, ui) {
                        newOrderMacroGruppoPreferenze = ""+$(this).sortable('toArray').toString();
                        $('#sortable').children().each(function()
                        {
                            var elementoLI = $(this);
                            $(elementoLI).children().each(function()
                            {
                                var sottoInput = $(this);
                                if($(sottoInput).data('type')=="campoposition")
                                {
                                    var posizione = $(elementoLI).index();
                                    $(sottoInput).val(posizione);
                                }
                            });
                            //console.log("New position: " + $(this).index());  
                        });
                    }
                });
                $( "#sortable" ).disableSelection();
            },
            error : function () {
                alert("ERRORE CARICAMENTO AJAX");
            }
        });
    }
    
    function LoadSavedPreferences_NewVersion(idarchivio)
    {
        //questa funzione è da riguardare perchè usa gli indici
        var address=controller_url + 'ajax_load_block_LoadPreferencesNewVersion' + '/' + idarchivio + '/' + $('.ui-selected').data('chiave');
        //alert(address);
        $.ajax({
            url : address,
            success : function (data) {
                $('#precaricato').html("<div align='right'><button onclick='" + bottoneSalvataggio + "'><i><b>Salva</b></i></button></div><br>" + data);
                $("#sortable").sortable({
                    placeholder: "ui-state-highlight",
                    update: function(event, ui) {
                        //newOrderMacroGruppoPreferenze = ""+$(this).sortable('toArray').toString();
                        //console.log("newOrder: " + newOrderMacroGruppoPreferenze);
                        $('#sortable').children().each(function()
                        {
                            var elementoLI = $(this);
                            $(elementoLI).children().each(function()
                            {
                                //var sottoInput = $(this);
                                var datatype = $(this).data('type');
                                if(datatype=="campoposition")
                                {
                                    var posizione = $(elementoLI).index();
                                    $(this).val(posizione);
                                }
                            });
                            //console.log("New position: " + $(this).index());  
                        });
                    }
                });
                $("#sortable").disableSelection();
            },
            error : function () {
                alert("ERRORE CARICAMENTO AJAX");
            }
        });
    }
    
//IMPOSTAZIONI - MACROGRUPPO PREFERENZE
    //var indirizzo_blocco_campi_tabella="<?php echo site_url('sys_viewcontroller/ajax_load_block_impostazioni_campi_tabella'); ?>";
    var newOrderMacroGruppoPreferenze;
    var bottoneSalvataggio="";
    var svuotaCampiPreferenze = false; //questa variabile serve per fare svuota il div "campipreferenze" (che viene riempito con l'evento changes archives)
    
    function ChangeSelection(typepreference){
    var IndirizzoLoadPreferences= controller_url + 'ajax_load_block_LoadSavedPreferences/';
    var indirizzo_blocco_campi= controller_url + 'ajax_load_block_impostazioni_campi';
    var IndirizzoLoadArchives= controller_url + 'ajax_load_block_archives_list';
   
    svuotaCampiPreferenze = false;//imposto l'ordine di svotare il div campiPreferenze quando faro' click su ChangesArchives
    //scelgo il bottone da mettere nel salva del blocchetto di sinistra
    switch(typepreference)
    {
        case 'campiFissi':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'campiricerca':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'campischeda':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'risultatiricerca':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'risultatilinked':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'schedanavigatore':
            bottoneSalvataggio='SavePreferencesNavigatoreSchede();';
            break;
        case 'keylabel':
            bottoneSalvataggio='GenericSavePreferences();';
            //indirizzo_blocco_campi=controller_url + 'ajax_load_block_labels/AZIEND';
            break;
        case 'keylabel_scheda':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'campiInserimento':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
        case 'keylabel_inserimento':
            bottoneSalvataggio='GenericSavePreferences();';
            break;
    }
    $.ajax({
            url : indirizzo_blocco_campi + '/' + 'desktop',
            success : function (data) {
                $('#centrale').html('');//svuoto la parte centrale della pagina impostazioni
                //e la divido in destra e sinistra
                $('#centrale').html("<div id='sinistra' style='width: 20%; height: 100%; float: left; border: 1px solid black; overflow-y: scroll;'></div><div id='destra' style='width: 75%; height: 100%; float: left; border: 1px solid black; overflow-y: scroll;'><div>");
                $('#destra').append("<form id='FormCreazioneCampi' onsubmit='return false'><div id='precaricato' style='width: 100%;'></div></form>");
                $('#sinistra').html('');//sinistra svuoto
                $("#sinistra").html(data);//ci metto l'elenco archivi
                //metto il sortable
                //$('#destra').html("<div align='right'><button onclick='" + bottoneSalvataggio + "'><i><b>Salva Preferenze</b></i></button></div><br><div align='center'><ul id='sortable'><li class='ui-state-default' id='placeholder'><span class='ui-icon ui-icon-arrowthick-3-n-s'></span>Add Element Here</li></ul></div>");
                /*$( "#sortable" ).sortable({
                    placeholder: "ui-state-highlight",
                    update: function(event, ui) {
                        newOrderMacroGruppoPreferenze = ""+$(this).sortable('toArray').toString();
                    }
                });
                $( "#sortable" ).disableSelection();*/
                },
            error : function () {
                alert("ERRORE RICHIESTA AJAX!");
            }
        });
    }
    
    function addElementCampiPreferiti(idelemento){ //questa funzione viene richiamata dalla pagina php lista campi
            if($(".ui-selected").data("chiave")=='schedanavigatore')
                $('#sortable').html('');
            
            var contaLiPresenti = $('#sortable li').length;
            var idSplittato = idelemento.split('-');
            var fieldid=$.trim(idSplittato[0]);
            var tableid=$.trim(idSplittato[1]);
            var description = $('#' + idelemento).text();
            description = $.trim(description);
            
            $('#accordion').find('#'+idelemento).remove();//rimuovo l'elemento nella colonna di sinistra
            $('#sortable').append('<li id="' + idelemento + '" class="ui-state-default">'+description+"</li>");
            //newOrderMacroGruppoPreferenze = ""+$('#sortable').sortable('toArray').toString();//aggiorno l'ordine del contenuti
            //imposto tutte le proprietà dell'elemento appena clonato come il name e tutti i campi hidden
            $('#'+idelemento).append('<input data-type="campodescription" type="text" style="width: 100%; display: none;" name="fields[' + contaLiPresenti + '][description]" value="' + description + '">');
            $('#'+idelemento).append('<input data-type="campofieldid" type="hidden" style="width: 100%;" name="fields[' + contaLiPresenti + '][fieldid]" value="' + fieldid + '">');
            $('#'+idelemento).append('<input data-type="campoidarchivio" type="hidden" style="width: 100%;" name="fields[' + contaLiPresenti + '][idarchivio]" value="' + tableid + '">');
            $('#'+idelemento).append('<input data-type="campoposition" type="hidden" style="width: 100%;" name="fields[' + contaLiPresenti + '][position]" value="' + contaLiPresenti + '">');
            
            if($(".ui-selected").data("chiave")=='schedanavigatore')
            {
                var response=confirm("Confermi questa opzione?");
                if(response==true)
                    GenericSavePreferences();
                if(response==false)
                    $('#sortable').html('');
            }
    }
    
    function SavePreferencesCampiPreferiti()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences/';
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE! ");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    
    function SavePreferencesCampiRicerca()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url= controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    
    function SavePreferencesCampiScheda()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    function SavePreferencesRisultatiRicerca()
    {
        var dati = $('#FormCreazioneCampi').serialize();
        var chiave = $('.ui-selected').data("chiave");
        var url=controller_url + 'set_preferences';
        /*var str;//stringa di appoggio
        var n; // nuova stringa (quella che sarà passata alla funzione in codeigniter
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");*/
        url=url+'/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    data: dati,
                    type: 'post',
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'è stato un errore");
                    }
                });
    }
    function SavePreferencesNavigatoreSchede()
    {
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter
        var url=controller_url + 'set_preferences_TabNavigator';
        str=newOrderMacroGruppoPreferenze;
        //alert(newOrder);
        n=str.replace(/,/g,"___");
        url=url+'/'+ n;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }    
    function SavePreferencesKeyLabel()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        //alert(NewOrder);
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    function SavePreferencesKeyLabelScheda()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio 
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        //alert(NewOrder);
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    function SavePreferencesCampiInserimento()
    {
        var chiave = $('.ui-selected').data("chiave");
        if(chiave=='creazione_campi')
            chiave='campiInserimento';
        var str;//stringa di appoggio 
        var n; // nuova stringa (quella che sarà passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        //alert(NewOrder);
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'è stato un errore");
                    }
                });
    }
    function SavePreferencesKeyLabelInserimento()
    {
        var chiave = $('.ui-selected').data("chiave");
        var str;//stringa di appoggio 
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences';
        str=newOrderMacroGruppoPreferenze;
        //alert(NewOrder);
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'√® stato un errore");
                    }
                });
    }
    
    function SaveCampiCollegati()
    {
        var str;//stringa di appoggio
        var n; // nuova stringa (quella che sar√† passata alla funzione in codeigniter)
        var url=controller_url + 'set_preferences_tabelle_collegate/' + $('#archiviomaster').val() + '/' + $('#archiviolinked').val();
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");
        url=url+'/'+ n + '/' + idutente;
        $.ajax({
                    url : url, 
                    success : function (data){
                        console.info(data);
                        alert ("IMPOSTAZIONI SETTATE! ");
                    },
                    error : function () {
                        alert("C'è stato un errore");
                    }
                });
    }
    
        function GenericSavePreferences()
    {
        var dati = $('#FormCreazioneCampi').serialize();
        var chiave = $('.ui-selected').data("chiave");
        var url=controller_url + 'set_preferences';
        /*var str;//stringa di appoggio
        var n; // nuova stringa (quella che sarà passata alla funzione in codeigniter
        str=newOrderMacroGruppoPreferenze;
        n=str.replace(/,/g,"___");*/
        url=url+'/' + chiave + '/' + idutente;
        //alert(url);
        $.ajax({
                    url : url,
                    data: dati,
                    type: 'post',
                    success : function (){
                        alert ("IMPOSTAZIONI SETTATE!");
                    },
                    error : function () {
                        alert("C'è stato un errore");
                    }
                });
    }
    
    function addElementCampiCollegaTabelle(idelemento)
    {
        $('#sortable').find( "#placeholder" ).remove();
        //alert($('#'+idelemento).text());
        $('#sortable').append('<li id="'+$('#'+idelemento).attr('id')+'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-3-n-s"></span>'+$('#'+idelemento).text()+"</li>");
        $('#accordion').find('#'+idelemento).remove();
        newOrderMacroGruppoPreferenze = ""+$('#sortable').sortable('toArray').toString();//aggiorno l'ordine del contenuti
    }
    
    function ChangeLayoutChoise(choise)
    {
        var indirizzo_settings_layout=controller_url + 'ajax_load_block_settings_layout';
        var indirizzo_precaricamento_layout=controller_url + 'ajax_load_block_precaricamento_layout';
        //RICHIESTA AJAX PER IMPOSTARE LA STRUTTURA
        $.ajax({
                    url : indirizzo_settings_layout + '/' + choise,
                    success : function(data) {
                        $("#centrale").html('');
                        $('#centrale').html('<div id="sinistra" style="width: 20%;height: 100%; float: left; border: 1px solid black; overflow-y: scroll;"></div><div id="destra" style="width: 75%;height: 100%; float: right; border: 1px solid black; overflow-y: scroll;"><div align="center"><h3>ATTUALI<h3></div></div>');
                        $('#sinistra').html(data);
                     },
                    error : function() {
                        alert("ERRORE AJAX SETTINGS LAYOUT");
                    }
                });
        /*RICHIESTA AJAX PER PRE-CARICARE LE IMPOSTAZIONI ATTUALI*/
        $.ajax({
                    url : indirizzo_precaricamento_layout + '/' + choise,
                    success : function(data) {
                        $('#destra').html(data);
                     },
                    error : function() {
                        alert("ERRORE PRECARICAMENTO LAYOUT");
                    }
                });
    }
    
    function SavePreferencesLayoutDashBoard()
    {
        var AddressSalvataggio=controller_url + 'set_preferences_layout';
        var temp='';
        
        if($('#ultimicandidati').is(':checked'))
            temp=temp + 'lasttenrecords_';
        
        if($('#candidatiscadenza').is(':checked'))
            temp=temp + 'candidatiscadenza_';
        
        if($('#compleanni').is(':checked'))
            temp=temp + 'happybirthdays_';
        
        if($('#updatesoftware').is(':checked'))
            temp=temp + 'softwareupdates_';
        
        if($('#candidatiattivi').is(':checked'))
            temp=temp + 'candidatiattivi_';
        
        if($('#aziendeattive').is(':checked'))
            temp=temp + 'aziendeattive_';
        
        var indice=temp.length - 1;
        var stringa=temp.substr(0,indice);
        $.ajax({
            url: AddressSalvataggio + '/' + stringa + '/dashboard',
            success:function(){
                alert('IMPOSTAZIONE SETTATE!');
            },
            error: function(){
                alert("ERRORE AJAX DASHBOARD");
            }
        });
    }
    
    function SavePreferencesLayoutSchede()
    {
        var AddressSalvataggio=controller_url + 'set_preferences_layout';
        var dati=$("input[name=dati]:checked").val();
        var allegati=$("input[name=allegati]:checked").val();
        var appoggio=AddressSalvataggio + '/' + dati + '_' + allegati + '/schede';
        $.ajax({
            url: appoggio,
            success : function(){
               alert('IMPOSTAZIONI SETTATE!');
            },
            error : function(){
                alert('ERRORE AJAX SCHEDE');
            }
        });
    }


function ajax_load_block_invia_mail(el,tableid){
    $.ajax({
        
                url: controller_url+'ajax_load_block_invia_mail/'+tableid,
                dataType:'html',
                success:function(data){
                    $('#invia_mail').html(data);
                    $('#form_invia_mail').find('#query').val($('#form_riepilogo').find('#query').val()); 
                    $('#invia_mail').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

//CUSTOM WORK&WORK
function ajax_load_block_invio_pushup(recordid){
    $.ajax({
        
                url: controller_url+'ajax_load_block_invio_pushup/'+recordid,
                dataType:'html',
                success:function(data){
                    $('#invio_pushup').html(data);
                    bPopup_pushup=$('#invio_pushup').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

function invio_pushup(recordid)
{
    $.ajax({
                url: controller_url+'invio_pushup/'+recordid,
                data:$('#form_invio_pushup').serialize(),
                type: 'post',
                success:function(data){
                    alert(data);
                    $('#invio_pushup').html('');
                    bPopup_pushup.close();
                },
                error:function(){
                    alert('errore');
                }
            });
}


function ajax_crea_lista_indirizzi(el,tableid)
{
    $.ajax({
        
        url: controller_url+'ajax_invia_mail/'+tableid,
        data:$('#form_invia_mail').serialize(),
        type: 'post',
        success:function(data){
            console.info(data);
            $('#lista_indirizzi').html(data);
            var lista_indirizzi=$('#lista_indirizzi').html().split(";");
            var remainder=0;
            var blocco_indirizzi='';
            var blocco_contatore=0;
            $.each(lista_indirizzi,function(i,indirizzo)
            {
                if(blocco_indirizzi!='')
                    blocco_indirizzi=blocco_indirizzi+';';
                blocco_indirizzi=blocco_indirizzi+indirizzo;

                remainder = ((i+1) % 80) / 100;

                if (remainder === 0)
                {
                    blocco_contatore=blocco_contatore+1;
                   // $('#block_invia_mail').append('<div class="blocco_invio"><textarea class="indirizzi_blocco_invio" style="display:none">'+blocco_indirizzi+'</textarea><div id="invio_'+blocco_contatore+'" class="btn_scritta btn_invio"  onclick="invia_mail(this)" style="margin-top:10px;">Invia mail blocco '+blocco_contatore+'</div></div>');
                    $('#block_invia_mail').append('<div class="blocco_invio"><textarea class="indirizzi_blocco_invio" style="display:none"></textarea><a id="link_invio_'+blocco_contatore+'" class="link_invio"  href="mailto:'+blocco_indirizzi+'" target="mailto">invio link '+blocco_contatore+'</a></div>');
                    $('#block_invia_mail').append('<iframe name="mailto" src="about:blank" style="display:none;"></iframe>');
                    $('#link_invio_'+blocco_contatore).ready(function(){
                       // $('#link_invio_'+blocco_contatore)[0].click();
                    });
                    blocco_indirizzi='';
                }
            })
            if(remainder!=0)
            {
                blocco_contatore=blocco_contatore+1;
               // $('#block_invia_mail').append('<div class="blocco_invio"><textarea class="indirizzi_blocco_invio" style="display:none">'+blocco_indirizzi+'</textarea><div id="invio_'+blocco_contatore+'" class="btn_scritta btn_invio"  onclick="invia_mail(this)" style="margin-top:10px;">Invia mail blocco '+blocco_contatore+'</div></div>');
                $('#block_invia_mail').append('<div class="blocco_invio"><textarea class="indirizzi_blocco_invio" style="display:none"></textarea><a id="link_invio_'+blocco_contatore+'" class="link_invio"  href="mailto:'+blocco_indirizzi+'" target="mailto2">invio link '+blocco_contatore+'</a></div>');
                $('#block_invia_mail').append('<iframe name="mailto2" src="about:blank" style="display:none;"></iframe>');
                $('#link_invio_'+blocco_contatore).ready(function(){
                   // $('#link_invio_'+blocco_contatore)[0].click();
                });
            }
            $('.blocco_invio').each(function(index,blocco_invio){                
                setTimeout(function(){
                    var link=$(blocco_invio).find('a');
                    console.info(link);
                   // $(link)[0].click();
                },500);
            })
        },
        error:function(){
            alert('errore');
        }
    });
    
}

function invia_blocchi()
{
   $('.blocco_invio').each(function(index,blocco_invio){  
                    setTimeout(function(){
                        var link=$(blocco_invio).find('a');
                        console.info(link);
                        $(link)[0].click();  
                    },5000*index);
            }) 
}
function invia_mail(el)
{
    var blocco_invio=$(el).closest('.blocco_invio');
    var lista_indirizzi=$(blocco_invio).find('.indirizzi_blocco_invio').html().split(";");
    var indirizzi_mailto='';
    var remainder=0;
    $.each(lista_indirizzi,function(i,indirizzo)
    {
        if(indirizzi_mailto!='')
            indirizzi_mailto=indirizzi_mailto+';';
        indirizzi_mailto=indirizzi_mailto+indirizzo;
        
    })

    var mail_subject=$('#mail_subject').val();
    var mail_body=$('#mail_body').val();
    mail_body=mail_body.replace(/\r\n|\r|\n/g,'%0D%0A');
    if(mail_subject!='')
        var mailto="mailto:"+indirizzi_mailto+"?subject="+mail_subject+"&body="+mail_body;
    else
        var mailto="mailto:"+indirizzi_mailto+"?body="+mail_body;
    window.location.replace(mailto);
    
}

function ajax_load_block_esporta_risultati(el,tableid){
    $.ajax({
        
                url: controller_url+'ajax_load_block_esporta_risultati/'+tableid,
                dataType:'html',
                success:function(data){
                    $('#esporta_risultati').html(data);
                    $('#form_esporta').find('#query').val($('#form_riepilogo').find('#query').val()); 
                    $('#esporta_risultati').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_block_esporta_elenco(el,tableid)
{
    var url=controller_url+'ajax_load_block_esporta_elenco/'+tableid;
    $.ajax( {
        data:$('#form_riepilogo').serialize(),
        type: 'post',
        url: url,
        success: function( response ) {
            $('#stampa').html(response);
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    //window.print(); 
                    //$('#stampa').hide();
                    //$('#jdocweb_wrapper').show();
                   
                },3000);*/
            $('#prestampa').html(response);
            prestampa_popup=$('#prestampa').bPopup();
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_load_block_esporta_elenco2(el,tableid)
{
    var url=controller_url+'ajax_load_block_esporta_elenco2/'+tableid;
    $.ajax( {
        data:$('#form_riepilogo').serialize(),
        type: 'post',
        url: url,
        success: function( response ) {
            $('#stampa').html(response);
                setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                   window.print(); 
                   $('#stampa').hide();
                   $('#jdocweb_wrapper').show();
                   
                },1000);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function stampa_elenco(el,tableid)
{ 
    $('#prestampa').html('attendere, generazione in corso');
    prestampa_popup=$('#prestampa').bPopup();
    var url=controller_url + '/ajax_stampa_elenco/'+tableid;
    $.ajax( {
        data:$('#form_riepilogo').serialize(),
        type: 'post',
        url: url,
        success: function( response ) {
            $('#prestampa').html(response);
            prestampa_popup=$('#prestampa').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}
    
function ajax_load_block_dem_select(el){
    $.ajax({
                url: controller_url+'ajax_load_block_dem_select',
                dataType:'html',
                success:function(data){
                    $('.bPopup_generico').html(data);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_load_block_campagne_select(el){
    $.ajax({
                url: controller_url+'ajax_load_block_campagne_select',
                dataType:'html',
                success:function(data){
                    $('.bPopup_generico').html(data);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

function SavePreferencesLayoutFont()
{
    var AddressSalvataggio = controller_url + 'set_preferences_layout';
    var dato=$("input[name=fontsize]:checked").val();
    var url=AddressSalvataggio + '/' + dato + '/font';
    $.ajax({
        url: url,
        success:function(){ alert("IMPOSTAZIONI SETTATE!");},
        error:function(){ alert("ERRORE AJAX FONT");}
    });     
}


    
function ajax_load_block_jpgcrop(el,recordid,cartella,nomefile)
{
    cartella=cartella.replace(/\//g,"-");
    var url=controller_url+'ajax_load_block_jpgcrop/'+recordid+'/'+cartella+'/'+nomefile;
    $.ajax({
        
                url: url,
                dataType:'html',
                success:function(data){
                    
                    //var scheda_container=$(el).closest('.scheda_container')
                    //$(scheda_container).html(data);
                    var visualizzatore=$(el).closest('.visualizzatore')
                    $(visualizzatore).html(data);
                    //$('.block_popup').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            });
}

function ajax_cropImg(el,recordid,cartella)
{
    var form=$(el).closest('form');
    //var scheda_record=$(el).closest('.scheda_record');
    //var tableid=$(scheda_record).data('tableid');
    var visualizzatore=$(el).closest('.visualizzatore');
    var tableid=$(visualizzatore).data('tableid');
    //var tableid=global_tableid;
   var url=controller_url+'ajax_cropImg/'+tableid+"/"+recordid;
    $.ajax({
        
                url: url,
                data:$(form).serialize(),
                type: 'post',
                success:function(data){
                    
                    alert('Immagine impostata');
                    //update_scheda(scheda_record, recordid, tableid);
                    //var pop=$('.block_popup').bPopup();
                    //pop.close();
                },
                error:function(){
                    alert('errore');
                }
            }); 
}

function LoadCollegamentiTabelle()
{
    var indirizzo_blocco_campi = controller_url + '/ajax_load_block_impostazioni_collega_tabelle';
    $.ajax({
        url : indirizzo_blocco_campi,
        success:function(data){
            $('#centrale').html("<div id='sinistra' style='width: 20%; height: 100%; float: left; border: 1px solid black; overflow-y:scroll;'></div><div id='destra' style='width: 75%; height: 100%; float: left; border: 1px solid black; overflow-y: scroll;'></div>");
            $('#sinistra').html(data);
            $('#destra').html("<div align='right'><button onclick='SaveCampiCollegati();'><i><b>Salva Preferenze</b></i></button></div><br><div align='center'><ul id='sortable'><li class='ui-state-default' id='placeholder'><span class='ui-icon ui-icon-arrowthick-3-n-s'></span>Add Element Here</li></ul></div>");
            $( "#sortable" ).sortable({
                placeholder: "ui-state-highlight",
                update: function(event, ui) {
                    newOrderMacroGruppoPreferenze = ""+$(this).sortable('toArray').toString();
                }
                });
            $( "#sortable" ).disableSelection();
        },
        error:function(data){
            alert(data);
        }
    });
}

function LoadCreazioneCampi()
{
    svuotaCampiPreferenze = true;
    var indirizzo_blocco_campi= controller_url + '/ajax_load_block_impostazioni_campi';
    //carico i tipi di campo
    var indirizzo_tipi_campo = controller_url + '/ajax_load_block_tipi_campi';
    $.ajax({
        url: indirizzo_tipi_campo,
        success:function(data)
        {
            $('#centrale').html("<div id='sinistra' style='width: 20%; height: 100%; float: left; border: 1px solid black; overflow-y:scroll;'></div><div id='destra' style='width: 75%; height: 100%; float: left; border: 1px solid black; overflow-y: scroll;'></div>");
            $('#sinistra').html(data);
            bottoneSalvataggio = "AddNewFieldsTable();";
                //faccio partire la richiesta ajax che carica gli archivi
            $.ajax({
                url: indirizzo_blocco_campi,
                success:function(data)
                {
                    $('#destra').html("<div id='ElencoArchivi' style='width: 100%;'></div>");
                    $('#destra').append("<form id='FormCreazioneCampi' onsubmit='return false'><div id='precaricato' style='width: 100%;'></div></form>");
                    $('#ElencoArchivi').html(data);
                    //creo il form e creo anche il tag ul per metterci dentro i vari <li> clonati
                },
                error:function()
                {
                    alert("ERRORE AJAX CARICAMENTO BLOCCO CAMPI");
                }
            });
        },
        error:function(){
            alert("ERRORE AJAX CARICAMENTO TIPO CAMPI");
        }
    });
}

function LoadCreazioneLabel()
{
    var indirizzo_blocco_creazione_label= controller_url + '/ajax_load_block_creazione_labels';
    $.ajax({
        url: indirizzo_blocco_creazione_label,
        success:function(data){
            $('#centrale').html(data);
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function CreazioneArchivi()
{
    var indirizzo_blocco_creazione_archivi = controller_url + '/ajax_load_block_creazione_campi';
    $.ajax({
        url: indirizzo_blocco_creazione_archivi,
        success:function(data){ $('#centrale').html(data); },
        error:function(){ alert("ERRORE RICHIESTA AJAX!");}
    });
}

    function CreaArchivio()
    {
        var idarchivio = $('#idarchivio').val();
        var descrizione=$('#descrizione').val();
        var chk = $('#chklookupestesa').prop('checked');
        if((idarchivio=='')||(descrizione==''))
            alert("IDARCHIVIO O DESCRIZIONE MANCANTE!");
        else{
            var data="idarchivio=" + idarchivio + "&descrizione=" + descrizione + "&chklookupestesa=" + chk;
            $.ajax({
                url:controller_url + "/ajax_create_archive",
                data: data,
                type: "post",
                success:function(){
                    alert("ARCHIVIO CREATO CORRETTAMENTE");
                    //---CREAZIONE DELLA LABEL---//
                    $.ajax({
                        url: controller_url + 'create_new_label',
                        type: 'post',
                        data: "idarchivio=" + idarchivio + "&textlabel=Dati",
                        success:function()
                        {
                            //alert("CREAZIONE NUOVA LABEL AVVENUTA CORRETTAMENTE");
                        },
                        error:function(){
                            alert("ERRORE SALVATAGGIO LABEL");
                        }
                     });
                     
                     $.ajax({
                        url: controller_url + 'create_new_label',
                        type: 'post',
                        data: "idarchivio=" + idarchivio + "&textlabel=Office",
                        success:function()
                        {
                            //alert("CREAZIONE NUOVA LABEL AVVENUTA CORRETTAMENTE");
                        },
                        error:function(){
                            alert("ERRORE SALVATAGGIO LABEL");
                        }
                     });
                     //---FINE CREAZIONE DELLA LABEL---//
                },
                error:function(){ alert("ERRORE AJAX SALVA DATI!"); }
            });
        }
        //alert('IDARCHIVIO: ' + idarchivio + "\nDESCRIZIONE: " + descrizione + "\nCHECKBOX: " + chk);
    }

function AddNewFieldsTable()
{
    if($('#archivio').val()=="null")
        alert("SCEGLIERE PRIMA L'ARCHIVIO");
    else{
        var dati=$("#FormCreazioneCampi").serialize();
        if(dati!=""){
            $.ajax({
                url: controller_url + '/save_creazione_campi',
                data: dati,
                type: 'post',
                success:function(){
                    alert("SALVATAGGIO AVVENUTO CORRETTAMENTE");
                    ChangeArchives();
                },
                error:function(){
                    alert("ERRORE INSERIMENTO AJAX");
                    //SavePreferencesCampiInserimento();
                }
            });
        }
        else
            SavePreferencesCampiInserimento();
    }
}

function AggiungiInput(elemento)
{
    
    var numcampo = $(elemento).data('numcampo');
    var numoption = $(elemento).data('numoption');
    var campoimpost=$(elemento).closest('.campoimpost');
    var numfield=$(campoimpost).data('numfield');
    var DivParent = $(elemento).parents();
    var liContainer = DivParent[0];
    var CampoID = $('<input type="text" value="" placeholder="id" style="border: 1px solid;">');
    //$(liContainer).append('Valore Option:<br>' + CampoID + '<br>');
    $(liContainer).append(CampoID);
    $(CampoID).attr('name','fields[' + numfield + '][options][' + numoption + '][id]');
    var CampoDescription = $('<input type="text" value="" placeholder="description" style="border: 1px solid black;">');
    //$(liContainer).append(CampoDescription + '<br>');
    $(liContainer).append(CampoDescription);
    $(CampoDescription).attr('name','fields[' + numfield + '][options][' + numoption + '][description]');
    numoption++;
    $(elemento).data('numoption',numoption);//adesso incremento il numoption anche nell'html
    //$('#CampiAggiunti').append("<input type='text' style='border: 1px solid black;'><br>");
    indiceCategoria++;
}

     

function ClonaCategoriaBAK(elemento)
    {
        var elementoCreato = $(elemento).clone();
        $(elementoCreato).attr('onclick','').unbind('click');//tolgo l'evento onclick dall'elemento clonato
        
        //abilito la visione dei degli element per la tabella sys_lookup_table
        $(elementoCreato).children().children().each(function(){
            $(this).css('display','inline');
            $(this).attr('name','nuovocampo[' + ContatoreElementiCreati + '][categorie][' + indiceCategoria + '][' + $(this).attr('placeholder') + ']');
        });
        
        //questo mi serve per rendere visibile il bottone +
        $(elementoCreato).children().each(function(){
            $(this).css('display','inline');
            if($(this).attr('type')=='button')
                $(this).attr('onclick','AggiungiInput(this,' + indiceCategoria + ');');
            $(this).data('numcampo',"'" + ContatoreElementiCreati + "'");
        });
        $('#NuoviCampi').append(elementoCreato);
        indiceCategoria++;
        ContatoreElementiCreati++;
    }

    function ClonaCategoria(elemento)
    {
        if($('#archivio').val()=='null')
            alert("SELEZIONARE PRIMA L'ARCHIVIO!");
        else{
            var IndiceNuovoCampo = $('#sortable').children().length;//dato che gli indici partono da zero, in pratica è il numero di elementi presenti
            var elementoCreato = $(elemento).clone(); //clono l'elemento e lo metto in elementoCreato
            $(elementoCreato).attr('onclick','').unbind('click');//tolgo l'evento onclick dall'elemento clonato
            $(elementoCreato).attr('id','tipocampo' + IndiceNuovoCampo);//questo è l'id del nuovo <li> clonato
            $(elementoCreato).data('numfield',IndiceNuovoCampo);

            //abilito la visione dei degli element per la tabella sys_lookup_table
            $(elementoCreato).children().children().each(function(){
                $(this).css('display','inline');
                $(this).attr('name','fields[' + IndiceNuovoCampo + '][' + $(this).attr('placeholder') + ']');
                if($(this).attr('placeholder')=='idarchivio')
                    $(this).val($('#archivio').val());
                if($(this).attr('placeholder')=='campoposition')
                    $(this).val(IndiceNuovoCampo);
            });

            //questo mi serve per rendere visibile il bottone +
            $(elementoCreato).children().each(function(){
                $(this).css('display','inline');
                if($(this).attr('type')=='button')
                    $(this).attr('onclick','AggiungiInput(this,' + IndiceNuovoCampo + ');');
                $(this).data('numcampo',"'" + IndiceNuovoCampo + "'");
            });

            $('#sortable').append(elementoCreato);
            
            $(elementoCreato).children().children().each(function(){
                var sottoElemento = $(this);
                var placeholder=$(this).attr('placeholder');
                if(placeholder=='label')
                {
                    //faccio partire la richiesta ajax che carica le label
                    var url= controller_url + '/get_label_for_option/' + $('#archivio').val();
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success:function(data)
                        {
                            var Options = "";
                            $.each(data,function(index,value){
                                Options=Options + "<option value='" + value +"'>" + value + "</option>";
                            });
                            $(sottoElemento).html(Options);
                        },
                        error:function()
                        {
                            alert("ERRORE CARICAMENTO OPTION LABEL");
                        }
                    });
                }
            });
            
            ContatoreElementiCreati++;
        }
    }
    
    function ClonaElemento(elemento)
    {
        if($('#archivio').val()=='null')
            alert("SELEZIONARE PRIMA L'ARCHIVIO!");
        else
        {
            var IndiceNuovoCampo = $('#sortable').children().length;//dato che gli indici partono da zero, in pratica è il numero di elementi presenti
            var elementoCreato = $(elemento).clone(); //clono l'elemento e lo metto in elementoCreato
            $(elementoCreato).attr('onclick','').unbind('click');//tolgo l'evento onclick dall'elemento clonato
            $(elementoCreato).attr('id','tipocampo' + IndiceNuovoCampo);//questo è l'id del nuovo <li> clonato
            $(elementoCreato).children().each(function(){
                $(this).css('display','inline');
                $(this).attr('name','fields[' + IndiceNuovoCampo + '][' + $(this).attr('placeholder') + ']');
                if($(this).attr('placeholder')=='idarchivio')
                    $(this).val($('#archivio').val());
                if($(this).attr('placeholder')=='campoposition')
                    $(this).val(IndiceNuovoCampo);
            });
            $('#sortable').append(elementoCreato);

            $(elementoCreato).children().each(function(){
                var sottoElemento = $(this);
                var placeholder=$(this).attr('placeholder');
                if(placeholder=='label')
                {
                    //faccio partire la richiesta ajax che carica le label
                    var url= controller_url + '/get_label_for_option/' + $('#archivio').val();
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success:function(data)
                        {
                            var Options = "";
                            $.each(data,function(index,value){
                                //alert(value);
                                Options=Options + "<option value='" + value +"'>" + value + "</option>";
                            });
                            $(sottoElemento).html(Options);
                        },
                        error:function()
                        {
                            alert("ERRORE CARICAMENTO OPTION LABEL");
                        }
                    });
                }
            });
            ContatoreElementiCreati++;
        }
    }
    
    
    function file_container_menu_toggle(el)
    {
       $(el).find('.originalname').toggle();
       $(el).find('.file_container_menu').toggle();
       
    }
    
    function apri_blocco_code(el)
    {
        var scheda_allegati=$(el).closest('.scheda_allegati');
        var scheda_container=$(scheda_allegati).parent().closest('.scheda');
        var scheda_container_id=$(scheda_container).data('schedaid');
        if(scheda_container_id=='scheda_inserimento')
        {
            var scheda_code_container=$(scheda_container).find('.scheda_code_container');
            $(scheda_code_container).toggle();   
        }
        if(scheda_container_id=='scheda_record')
        {
            var visualizzatore=$(scheda_container).find('#visualizzatore');
            var scheda_code_container=$(scheda_container).find('.scheda_code_container');
            $(scheda_code_container).toggle(); 
            if($(scheda_code_container).is(":visible"))
            {
                $(visualizzatore).width('calc(100% - 300px)');
            }
            else
            {
                $(visualizzatore).width('calc(100% - 155px)');
            }
        }
    }
    function apri_blocco_autobatch(el)
    {
        console.info('fun:apri_blocco_autobatch');
        var scheda_allegati=$(el).closest('.scheda_allegati');
        var scheda_container=$(scheda_allegati).parent().closest('.scheda');
        var scheda_container_id=$(scheda_container).data('schedaid');
        if(scheda_container_id=='scheda_inserimento')
        {
            var scheda_autobatch_container=$(scheda_container).find('.scheda_autobatch_container');
            $(scheda_autobatch_container).toggle();   
        }
        if(scheda_container_id=='scheda_record')
        {
            var visualizzatore=$(scheda_container).find('#visualizzatore');
            
            var scheda_autobatch_container=$(scheda_container).find('.scheda_autobatch_container');
            var funzione=$(scheda_container).data('funzione');
            var url=controller_url + '/ajax_load_block_autobatch/' + funzione;
            $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $(scheda_autobatch_container).html(response);
                $(scheda_autobatch_container).toggle();  
                if($(scheda_autobatch_container).is(":visible"))
                {
                    //$(visualizzatore).width('calc(100% - 300px)');
                    //$(visualizzatore).width(100);
                }
                else
                {
                   //$(visualizzatore).width('calc(100% - 155px)');
                   //$(visualizzatore).width(100);
                }
            }
            });
            
            
        }
    }
    
    /*function notnull_field_onclick(el){
        var fieldcontainer=$(el).closest(".fieldcontainer");
        param_field_onclick(el,fieldcontainer,'notnull');
        $(fieldcontainer).find('.qualsiasi_layer').show();
    }
    
    function null_field_onclick(el){
        var fieldcontainer=$(el).closest(".fieldcontainer");
        param_field_onclick(el,fieldcontainer,'null');
        $(fieldcontainer).find('.nessuno_layer').show();
    }
    
    function currentuser_field_onclick(el){
        param_field_onclick(el,fieldcontainer,'currentuser');
    }
    */
    function param_field_onclick(el,param)
    {
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var field_param=$(fieldcontainer).find('.field_param');
        var field_layer=$(fieldcontainer).find('.field_layer');
        var param_layer=$(fieldcontainer).find('.param_layer');
        $(field_layer).hide();
        var field=$(fieldcontainer).find('.field');
        $(field_param).val(param);
        if(param=='null')
        {
          $(param_layer).html('Nessun valore');  
        }
        if(param=='notnull')
        {
            $(param_layer).html('Almeno un valore');
        }
        if(param=='currentuser')
        {
            $(param_layer).html('Utente corrente');
        }
        if(param=='today')
        {
            $(param_layer).html('Oggi');
        }
        if(param=='past')
        {
            $(param_layer).html('Prima di oggi');
        }
        if(param=='future')
        {
            $(param_layer).html('Dopo oggi');
        }
        if(param=='currentweek')
        {
            $(param_layer).html('Questa settimana');
        }
        if(param=='currentmonth')
        {
            $(param_layer).html('Questo mese');
        }
        if(param=='nextmonth')
        {
            var num = prompt("Quanti mesi?");
            $(param_layer).html('Entro '+num+' mesi');
            var fieldValueHidden=$(fieldcontainer).find('.fieldValueHidden');
            $(fieldValueHidden).val(num);
        }
        if(param=='prevmonth')
        {
            var num = prompt("Quanti mesi?");
            $(param_layer).html('Nei precedenti '+num+' mesi');
            var fieldValueHidden=$(fieldcontainer).find('.fieldValueHidden');
            $(fieldValueHidden).val(num);
        }
        if(param=='nextday')
        {
            var num = prompt("Quanti giorni?");
            $(param_layer).html('Entro '+num+' giorni');
            var fieldValueHidden=$(fieldcontainer).find('.fieldValueHidden');
            $(fieldValueHidden).val(num);
        }
        if(param=='prevday')
        {
            var num = prompt("Quanti giorni?");
            $(param_layer).html('Nei precedenti '+num+' giorni');
            var fieldValueHidden=$(fieldcontainer).find('.fieldValueHidden');
            $(fieldValueHidden).val(num);
        }
        if(param=='overpastday')
        {
            var num = prompt("Oltre quanti giorni?");
            $(param_layer).html('Oltre '+num+' giorni passati');
            var fieldValueHidden=$(fieldcontainer).find('.fieldValueHidden');
            $(fieldValueHidden).val(num);
        }
        $(param_layer).show();
        $(field).hide();
        field_changed(field);
    }
    
    function not_field_onclick(el){
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var field_param=$(fieldcontainer).find('.field_param');
        var field_layer=$(fieldcontainer).find('.field_layer');
        var field_not_layer=$(fieldcontainer).find('.field_not_layer');
        $(field_layer).hide();
        var field=$(fieldcontainer).find('.field');
        var value=$(field).val();
        $(field_not_layer).show();
        $(field).show();
        $(field_param).val('not');
        if(value!="")
        {
            field_changed(field);
        }
    }
    
    function or_field_onclick_bak(el,autoOr,autoValue)
    {
        console.info('fun: or_field_onclick');
        if (typeof autoOr === 'undefined') { autoOr = false; }
        if (typeof autoValue === 'undefined') { autoValue = ""; }
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var field=$(fieldcontainer).find('.field');
        var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
        
        
        if((fieldtypeid=='Utente')||(fieldtypeid=='Lookuptable'))
        {
            if($(field).data('loaded'))
            {
                jQuery(field).autocomplete("destroy");
                jQuery(field).removeData('autocomplete');
                $(field).data('loaded',false);
            }
            
        }
        var fieldcontainer_cloned=fieldcontainer.clone(true,true);
        var field_cloned=$(fieldcontainer_cloned).find('.field');
        
        /*if((fieldtypeid=='Utente')||(fieldtypeid=='Lookuptable'))
        {
            set_autocomplete(field);
            set_autocomplete(field_cloned);
        }*/
        /*if(fieldtypeid=='Lookuptable')
        {
            ajax_get_lookuptable_items2(field);
            jQuery(field).autocomplete("destroy");
            jQuery(field).removeData('autocomplete');
        }
        
        if(fieldtypeid=='Utente')
        {
            set_autocomplete(field);
            set_autocomplete(field_cloned);
        }
        if(fieldtypeid=='Lookuptable')
        {
            set_autocomplete(field);
            set_autocomplete(field_cloned);
            //ajax_get_lookuptable_items2(field);
            //ajax_get_lookuptable_items2(field_cloned);
        }*/
        $(field_cloned).val("");
        $(field_cloned).show();
        $(fieldcontainer_cloned).addClass('fieldcontainer_cloned');
        var fieldscontainer=fieldcontainer.closest(".fieldscontainer");
        var counter=fieldscontainer.attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        fieldscontainer.attr("data-counter",counter_modified);
        var index_original=fieldcontainer.attr("data-index");
        var index_modified="f_"+counter_modified;
        fieldcontainer_cloned.attr("data-index",index_modified);
        id_original=$(fieldcontainer).attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        fieldcontainer_cloned.attr("id",id_modified);
        var name_original;
        var name_modified;
        var id_original;
        var id_modified;
        fieldcontainer_cloned.find('[id*="'+index_original+'"]').each(function(i) {
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
            if($(this).is('[name]'))
            {
                name_original=$(this).attr("name");
                name_modified=name_original.replace(index_original,index_modified);
                $(this).attr("name",name_modified);
            }
        });
        //var field_cloned=$(fieldcontainer_cloned).find('.field');
        
        var field_operator_cloned=$(fieldcontainer_cloned).find('.field_operator');
        $(field_operator_cloned).val("");
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');
        $(field_layer_cloned).hide();
        $(field_operator_cloned).val('or');
        $(fieldcontainer_cloned).find('.or_layer').show();
        fieldcontainer_cloned.attr('data-cloned', 'true');
        if(autoOr)
        {
            //$(field_cloned).val(autoValue);
            $(fieldcontainer_cloned).css('display','none');
            var fieldValue0=$(fieldcontainer_cloned).find('.fieldValue0');
            $(fieldValue0).val(autoValue);
        }
        $(fieldcontainer).after(fieldcontainer_cloned);
        $(fieldcontainer_cloned).find('.field').blur(function() { 
            field_blurred($(this));
        });
    }
    
    function or_field_onclick(el,autoOr,autoValue)
    {
        console.info('fun: or_field_onclick');
        if (typeof autoOr === 'undefined') { autoOr = false; }
        if (typeof autoValue === 'undefined') { autoValue = ""; }
        var fieldcontainer=$(el).closest(".fieldcontainer");

        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldscontainer=fieldcontainer.closest(".fieldscontainer");
        
        var field=$(fieldcontainer).find('.field');
        var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
        if((fieldtypeid=='Utente')||(fieldtypeid=='Lookuptable'))
        {
            jQuery(field).autocomplete("destroy");
            jQuery(field).removeData('autocomplete');
        }
        

        
        var fieldcontainer_cloned=fieldcontainer.clone(true,true);
        var field_cloned=$(fieldcontainer_cloned).find('.field');
        $(field_cloned).val("");
        $(field_cloned).show();
        //$(fieldcontainer_cloned).addClass('fieldcontainer_cloned');
        
        var counter=fieldscontainer.attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        fieldscontainer.attr("data-counter",counter_modified);
        
        var index_original=fieldcontainer.attr("data-index");
        var index_modified="f_"+counter_modified;
        fieldcontainer_cloned.attr("data-index",index_modified);
        
        id_original=$(fieldcontainer).attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        fieldcontainer_cloned.attr("id",id_modified);
        
        var name_original;
        var name_modified;
        var id_original;
        var id_modified;
        fieldcontainer_cloned.find('[id*="'+index_original+'"]').each(function(i) {
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
            if($(this).is('[name]'))
            {
                name_original=$(this).attr("name");
                name_modified=name_original.replace(index_original,index_modified);
                $(this).attr("name",name_modified);
            }
        });
        
        
        var field_param_cloned=$(fieldcontainer_cloned).find('.field_param');
        $(field_param_cloned).val("");
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');
        
        if(fieldtypeid=='Utente')
        {
            set_autocomplete(field);
            set_autocomplete(field_cloned);
        }
        
        if(fieldtypeid=='Lookuptable')
        {
            set_autocomplete_lookuptable(field);
            set_autocomplete_lookuptable(field_cloned);
        }
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');

        $(field_layer_cloned).hide();
        
        var field_operator_cloned=$(fieldcontainer_cloned).find('.field_operator');
        $(field_operator_cloned).val("");
        $(field_operator_cloned).val('or');
        $(fieldcontainer_cloned).find('.or_layer').show();
        fieldcontainer_cloned.attr('data-cloned', 'true');
        if(autoOr)
        {
            //$(field_cloned).val(autoValue);
            $(fieldcontainer_cloned).css('display','none');
            var fieldValue0=$(fieldcontainer_cloned).find('.fieldValue0');
            $(fieldValue0).val(autoValue);
        }
        $(fieldcontainer).after(fieldcontainer_cloned);
        $(fieldcontainer_cloned).find('.field').blur(function() { 
            field_blurred($(this));
        });
    }
    
    
    function multi_field_onclick(el){
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldscontainer=fieldcontainer.closest(".fieldscontainer");
        
        var field=$(fieldcontainer).find('.field');
        var fieldtypeid=$(fieldcontainer).data('fieldtypeid');
        if((fieldtypeid=='Utente')||(fieldtypeid=='Lookuptable'))
        {
            jQuery(field).autocomplete("destroy");
            jQuery(field).removeData('autocomplete');
        }
        

        
        var fieldcontainer_cloned=fieldcontainer.clone(true,true);
        var field_cloned=$(fieldcontainer_cloned).find('.field');
        $(field_cloned).val("");
        $(field_cloned).show();
        //$(fieldcontainer_cloned).addClass('fieldcontainer_cloned');
        
        var counter=fieldscontainer.attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        fieldscontainer.attr("data-counter",counter_modified);
        
        var index_original=fieldcontainer.attr("data-index");
        var index_modified="f_"+counter_modified;
        fieldcontainer_cloned.attr("data-index",index_modified);
        
        id_original=$(fieldcontainer).attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        fieldcontainer_cloned.attr("id",id_modified);
        
        var name_original;
        var name_modified;
        var id_original;
        var id_modified;
        fieldcontainer_cloned.find('[id*="'+index_original+'"]').each(function(i) {
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
            if($(this).is('[name]'))
            {
                name_original=$(this).attr("name");
                name_modified=name_original.replace(index_original,index_modified);
                $(this).attr("name",name_modified);
            }
        });
        
        
        var field_param_cloned=$(fieldcontainer_cloned).find('.field_param');
        $(field_param_cloned).val("");
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');
        
        if(fieldtypeid=='Utente')
        {
            set_autocomplete(field);
            set_autocomplete(field_cloned);
        }
        
        if(fieldtypeid=='Lookuptable')
        {
            set_autocomplete_lookuptable(field);
            set_autocomplete_lookuptable(field_cloned);
        }
        $(field_layer_cloned).hide();
        $(field_param_cloned).val('multi');
        fieldcontainer_cloned.attr('data-cloned', 'true');
        $(fieldcontainer_cloned).css('margin-top','-5px');
        $(fieldcontainer).after(fieldcontainer_cloned);
        $(fieldcontainer_cloned).find('.field').blur(function() { 
            field_blurred($(this));
        });
    }
    
    function range_field_onclick(el){
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldcontainer_cloned=fieldcontainer.clone(true,true);
        $(fieldcontainer_cloned).addClass('fieldcontainer_cloned');
        var fieldscontainer=fieldcontainer.closest(".fieldscontainer");
        var counter=fieldscontainer.attr("data-counter");
        var counter_modified=parseInt(counter)+1;
        fieldscontainer.attr("data-counter",counter_modified);
        var index_original=fieldcontainer.attr("data-index");
        var index_modified="f_"+counter_modified;
        fieldcontainer_cloned.attr("data-index",index_modified);
        id_original=$(fieldcontainer).attr("id");
        id_modified=id_original.replace(index_original,index_modified);
        fieldcontainer_cloned.attr("id",id_modified);
        var name_original;
        var name_modified;
        var id_original;
        var id_modified;
        fieldcontainer_cloned.find('[id*="'+index_original+'"]').each(function(i) {
            id_original=$(this).attr("id");
            id_modified=id_original.replace(index_original,index_modified);
            $(this).attr("id",id_modified);
            if($(this).is('[name]'))
            {
                name_original=$(this).attr("name");
                name_modified=name_original.replace(index_original,index_modified);
                $(this).attr("name",name_modified);
            }
        });
        var field_param=$(fieldcontainer).find('.field_param');
        var field_layer=$(fieldcontainer_cloned).find('.field_layer');
        $(field_layer).hide();
        $(field_param).val('from');
        $(fieldcontainer).find('.from_layer').show();
        var field_cloned=$(fieldcontainer_cloned).find('.field');
        $(field_cloned).val("");
        $(field_cloned).show();
        var field_param_cloned=$(fieldcontainer_cloned).find('.field_param');
        var field_layer_cloned=$(fieldcontainer_cloned).find('.field_layer');
        $(field_layer_cloned).hide();
        $(field_param_cloned).val('to');
        $(fieldcontainer_cloned).find('.to_layer').show();
        fieldcontainer_cloned.attr('data-cloned', 'true');
        $(fieldcontainer).after(fieldcontainer_cloned);
        $(fieldcontainer_cloned).find('.field').blur(function() { 
            field_blurred($(this));
        });
    }

  function show_query_riepilogo(el)
  {
      var scheda_dati_ricerca=$(el).closest('.scheda_dati_ricerca');
      var query_riepilogo=$(scheda_dati_ricerca).find('.query_riepilogo');
      $(query_riepilogo).toggle();
  }

function show_fieldMenu(el)
{
    var fieldContainer=$(el).closest('.fieldcontainer');
    var fieldMenu=$(fieldContainer).find('.fieldMenu');
    $(fieldMenu).toggle();
}

var NumeroUltimaOptionsCategoriaPreloaded = 0;

function InsertNewOption(numeroElencoOptions,lookuptableid)
{
    var NuovoNumOption = NumeroUltimaOptionsCategoriaPreloaded + 1;
    var indexName = $('#ElencoOptions' + numeroElencoOptions).data('indexname');
    var InputFieldid="<input type='text' name='fields[" + indexName + "][options][" + NuovoNumOption + "][itemcode]' placeholder='fieldid'>";
    var InputDescription = "<input type='text' name='fields[" + indexName + "][options][" + NuovoNumOption + "][itemdesc]' placeholder='description'>";
    var InputInsertOrUpdate = "<input type='hidden' name='fields[" + indexName + "][options][" + NuovoNumOption + "][insertorupdate]' value='insert'>";
    var InputLookuptableID = "<input type='hidden' name='fields[" + indexName + "][options][" + NuovoNumOption + "][lookuptableid]' value='" + lookuptableid + "'>";
    $('#ElencoOptions' + numeroElencoOptions).append(InputFieldid);
    $('#ElencoOptions' + numeroElencoOptions).append(InputDescription);
    $('#ElencoOptions' + numeroElencoOptions).append(InputInsertOrUpdate);
    $('#ElencoOptions' + numeroElencoOptions).append(InputLookuptableID);
    NumeroUltimaOptionsCategoriaPreloaded++;
}

function DeleteOption(indexname,indiceOptions,bottoneElimina)
{
    var itemcode=$('#fields_' + indexname + '__options__' + indiceOptions + '__itemcode_').val();
    var lookuptableid=$('#fields_' + indexname + '__options__' + indiceOptions + '__lookuptableid_').val();
    var url=controller_url + "/deleteOption";
    $.ajax({
        url: url,
        data: "itemcode=" + itemcode + "&lookuptableid=" + lookuptableid,
        type: 'post',
        success:function(){
            $('#fields_' + indexname + '__options__' + indiceOptions + '__itemcode_').remove();
            $('#fields_' + indexname + '__options__' + indiceOptions + '__itemdesc_').remove();
            $('#fields_' + indexname + '__options__' + indiceOptions + '__lookuptableid_').remove();
            $('#fields_' + indexname + '__options__' + indiceOptions + '__insertorupdate_').remove();
            $(bottoneElimina).remove();
            //alert("OPTION ELIMINATA CORETTAMENTE");
        },
        error:function(){alert("ERRORE DELETE OPTION");}
    });
}

function MostraOptions(elemento)
{
    var lookuptableid = $(elemento).data("lookuptableid");
    var lengthOptions = 0;
    var numciclo = $(elemento).data('numciclo');
    
    $(elemento).children().each(function(){
       var sottoElemento = $(this);
       var dataType = $(sottoElemento).data('type');
       if(dataType=="ElencoOptions"){
           var children = $(sottoElemento).children();
           lengthOptions = $(children).length;
       }
    });
    if((lookuptableid!="")&&(lookuptableid!=null))
    {
        if($(".butAddOption").length==0)
        {
            $(elemento).append("<button class='butAddOption' onclick='InsertNewOption(" + numciclo + ",\"" + lookuptableid + "\");' >+</button>");
        }
        $.ajax(
        {
            url: controller_url + "/ajax_get_options_lookuptableid/" + lookuptableid,
            dataType: 'json',
            success:function(data)
            {
                var indiceOptions = 0;
                $.each(data,function(index,value){
                    $(elemento).children().each(function(){
                        if(($(this).data("type")=="ElencoOptions") && (lengthOptions==0))
                        {
                            var indexName=$(this).data("indexname");
                            var eventoBottone = "DeleteOption(" + indexName + "," + indiceOptions + ",this);";
                            $(this).append("<input type='hidden' id='fields_" + indexName + "__options__" + indiceOptions +"__itemcode_' name='fields[" + indexName + "][options][" + indiceOptions +"][itemcode]' style='width: 100%;' value='" + value['itemcode'] + "'>");
                            $(this).append("<input type='text' id='fields_" + indexName + "__options__" + indiceOptions +"__itemdesc_' name='fields[" + indexName + "][options][" + indiceOptions +"][itemdesc]' style='width: 95%;' value='" + value['itemdesc'] + "'>");
                            $(this).append("<button onclick='" + eventoBottone + "' style='width: 5%;'>-</button>");
                            $(this).append("<input type='hidden' id='fields_" + indexName + "__options__" + indiceOptions +"__lookuptableid_' name='fields[" + indexName + "][options][" + indiceOptions +"][lookuptableid]' style='width: 100%;' value='" + lookuptableid +"'>");
                            $(this).append("<input type='hidden' id='fields_" + indexName + "__options__" + indiceOptions +"__insertorupdate_' name='fields[" + indexName + "][options][" + indiceOptions +"][insertorupdate]' style='width: 100%;' value='update'>");
                            NumeroUltimaOptionsCategoriaPreloaded = indiceOptions;
                        }
                    });
                    indiceOptions++;
                });
            },
            error:function()
            {
                alert("ERRORE AJAX MOSTRA OPTIONS!");
            }
        });
    }
}

function stampa_selezionati(el,tableid,recordid)
{
    MergedPDFtoPrint=document.getElementById('MergedPDFtoPrint');
    MergedPDFtoPrint.src='';
    var scheda_allegati=$(el).closest('.scheda');
    var form_riepilogo=$(scheda_allegati).find('.form_riepilogo');
    var counter=0;
    $(form_riepilogo).find('.file_checkbox').each(function(i){
        if($(this).prop('checked'))
            {
                counter++;
            }
    })
    if(counter>0)
    {
    var url=controller_url+'ajax_stampa_selezionati/'+tableid+'/'+recordid;
        $.ajax( {
            type: "POST",
            url: url,
            data: $(form_riepilogo).serialize(),
            success: function( response ) {
                $('#stampa_selezionati_popup').bPopup();
                
                MergedPDFtoPrint.src=response;
            },
            error:function(){
                alert('errore stampa selezionati');
            }
        } ); 
    }
    else
    {
        alert('Nessun allegato selezionato');
    }
}

  function conferma_stampa_selezionati(el)
  {
      MergedPDFtoPrint=document.getElementById('MergedPDFtoPrint'); 
      MergedPDFtoPrint.contentWindow.print();
  }

    function conferma_invio(el)
    {
        var mail_data=$(el).closest('.block').find('#mail_data');
        $.ajax( {
                    type: "POST",
                    url: controller_url + '/ajax_conferma_invio',
                    data: $(mail_data).serialize(),
                    success: function( response ) {
                        alert('Inviato');
                        
                    },
                    error:function(){
                        alert('errore');
                    }
                } );
    }
    function seleziona_tutti(el)
    {
        var files_container=$(el).closest('.files_container');
        $(files_container).find('.file_container').each(function(i){
            var checkbox=$(this).find('.file_checkbox');
            if($(el).prop('checked'))
            {
               $(checkbox).prop('checked', true); 
            }
            else
            {
                $(checkbox).prop('checked', false); 
            }
            
            
        })
    }
    
    function EliminaCampo(tableid,fieldid,lookuptableid,elementoLI,elementoBottone)
    {
        $.ajax({
            url: controller_url + '/elimina_campo/' + $('.ui-selected').data("chiave"),
            data: 'tableid=' + tableid + '&fieldid=' + fieldid + '&lookuptableid=' + lookuptableid,
            type: 'post',
            success:function(){
                $(elementoBottone).remove();
                $('#' + elementoLI).remove();
                alert("Campo Eliminato Correttamente");
            },
            error:function(){ alert("Errore ajax elimina campo"); }
        });
    }
    
    
    
    
    function add_lookuptable_item(el,el_field_id,lookuptableid,fieldid,tableid,code)
    {
        var itemdescval=prompt("Inserisci valore");
        if((itemdescval!=null)&&(itemdescval!=''))
        {
         $.ajax({
                url: controller_url + '/ajax_add_lookuptable_item/' + lookuptableid,
                data: { 
                        itemdesc: itemdescval, 
                      },
                type: 'post',
                success:function(data){ 
                    if(data!='null')
                    {
                       // alert(data);
                        alert('Inserito correttamente');
                        var fieldcontainer=$(el).closest('.fieldcontainer');
                        var fieldLayer=$(fieldcontainer).find('.fieldLayer');
                        var fieldvalue0=$(fieldcontainer).find('.fieldvalue0');
                        ajax_get_lookuptable_items2($(fieldLayer));
                        $(fieldLayer).val(data);
                        $(fieldvalue0).val(data);
                        field_changed(fieldvalue0);
                    }
                    else
                    {
                        alert('Valore già esistente');
                    }
                },
                error:function(){
                    
                    alert('error');
                }
            });
        }
    }
    
    function set_field_explanation(el,tableid,fieldid)
    {
        var field_explanation=prompt("Inserisci descrizione del campo");
        if((field_explanation!=null)&&(field_explanation!=''))
        {
         $.ajax({
                url: controller_url + '/ajax_set_field_explanation/' + tableid + '/' + fieldid,
                data: { 
                        explanation: field_explanation, 
                      },
                type: 'post',
                success:function(data){ 
                        alert('Descrizione impostata correttamente');
                },
                error:function(){
                    
                    alert('error');
                }
            });
        }
    }
    
    function add_page_category(el)
    {
        var cat_description=prompt("Inserisci categoria");
        var visualizzatore=$(el).closest('.visualizzatore');
        var select_category=$(visualizzatore).find('.select_category');
        var scheda_record=$(el).closest('.scheda_record');
        var tableid=$(scheda_record).data('tableid');
        if((cat_description!=null)&&(cat_description!=''))
        {
         $.ajax({
                url: controller_url + '/ajax_add_page_category/' + tableid,
                data: { 
                        cat_id: cat_description, 
                        cat_description: cat_description, 
                      },
                type: 'post',
                success:function(data){ 
                    if(data!='null')
                    {
                       // alert(data);
                        alert('Inserito correttamente');
                        $(select_category).append('<option value="'+cat_description+'">'+cat_description+'</option>');
                        $(select_category).val(cat_description);
                    }
                    else
                    {
                        alert('Valore già esistente');
                    }
                },
                error:function(){
                    
                    alert('error');
                }
            });
        }
    }
    
    function set_page_categorytBAK_SELECT(el)
    {
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var visualizzatore=$(el).closest('.visualizzatore');
        var select_category=$(visualizzatore).find('.select_category');
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var tableid=$(scheda_record).data('tableid');
        var recordid=$(scheda_record).data('recordid');
        var fileid=$(allegato_selected).data('fileid');
        var category=$(el).val();
        var tableid=$(scheda_record).data('tableid');
         $.ajax({
                url: controller_url + '/ajax_set_page_category/' + tableid,
                data: { 
                        tableid: tableid,
                        recordid: recordid,
                        fileid: fileid, 
                        category: category, 
                      },
                type: 'post',
                success:function(data){ 
                    var file_container=$(allegato_selected).closest('.file_container');
                    var file_category=$(file_container).find('.file_category');
                    $(file_category).html(category);
                },
                error:function(){
                    
                    alert('error');
                }
            });
    }
    
    function set_pages_category(el)
    {
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var block_allegati=$(el).closest('.block_allegati');
        var check_category=$(block_allegati).find('.check_category');
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var tableid=$(scheda_record).data('tableid');
        var recordid=$(scheda_record).data('recordid');
        var fileid=$(allegato_selected).data('fileid');
        var tableid=$(scheda_record).data('tableid');
        var serialized_data=$(block_allegati).find('.checkbox').serializeArray();
        serialized_data.push({name: 'tableid', value: tableid});
        serialized_data.push({name: 'recordid', value: recordid});
        serialized_data.push({name: 'fileid', value: fileid});
         $.ajax({
                url: controller_url + '/ajax_set_pages_category',
                data: serialized_data,
                type: 'post',
                success:function(data){ 
                    var file_container=$(allegato_selected).closest('.file_container');
                    var file_category=$(file_container).find('.file_category');
                    $(check_category).hide();
                    $(block_allegati).find('.checkbox').each(function(){
                        $(this).attr('checked', false);;
                    })
                    var target=$(el).closest('.scheda_record').find('.block_allegati');
                    console.info('target:');
                    console.info(target);
                    ajax_load_block_allegati(tableid,recordid,'inserimento',target);
                },
                error:function(){
                    
                    alert('error');
                }
            });
    }
    
    function set_page_category(el)
    {
        //var scheda_record=$(el).closest('.scheda_record');
        //var allegato_selected=$(scheda_record).find('.allegato_selected');
        var visualizzatore=$(el).closest('.visualizzatore');
        var check_category=$(visualizzatore).find('.check_category');
        //var scheda_record=$(el).closest('.scheda_record');
        //var allegato_selected=$(scheda_record).find('.allegato_selected');
        var tableid=$(visualizzatore).data('tableid');
        var recordid=$(visualizzatore).data('recordid');
        var fileid=$(visualizzatore).data('fileid');
        var serialized_data=$(check_category).find('.checkbox').serializeArray();
        serialized_data.push({name: 'tableid', value: tableid});
        serialized_data.push({name: 'recordid', value: recordid});
        serialized_data.push({name: 'fileid', value: fileid});
         $.ajax({
                url: controller_url + '/ajax_set_page_category',
                data: serialized_data,
                type: 'post',
                success:function(data){ 
                    //var file_container=$(allegato_selected).closest('.file_container');
                    //var file_category=$(file_container).find('.file_category');
                    $(check_category).hide();
                    var visualizzatore_popup=$(el).closest('.visualizzatore_popup');
                    var uniqueid=$(visualizzatore_popup).data('uniqueid');
                    var visualizzatore=$("#visualizzatore_"+uniqueid);
                    var target=$(visualizzatore).closest('.scheda_record').find('.block_allegati');
                    console.info(target);
                    ajax_load_block_allegati(tableid,recordid,'inserimento',target);
                },
                error:function(){
                    
                    alert('error');
                }
            });
    }
    
    function seleziona_tutti_allegati(el)
    {
        var block_allegati=$(el).closest('.block_allegati');
        if($(el).is(':checked'))
        {
            $(block_allegati).find('.checkbox').each(function(){
                $(this).prop('checked', true);
            })
        }
        else
        {
            $(block_allegati).find('.checkbox').each(function(){
                $(this).prop('checked', false);
            })
        }
        
    }
    function scarica_allegati_selezionati(el,userid,tipo)
    {
        
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var block_allegati=$(el).closest('.block_allegati');
        var check_category=$(block_allegati).find('.check_category');
        var scheda_record=$(el).closest('.scheda_record');
        var allegato_selected=$(scheda_record).find('.allegato_selected');
        var tableid=$(scheda_record).data('tableid');
        var recordid=$(scheda_record).data('recordid');
        var fileid=$(allegato_selected).data('fileid');
        var tableid=$(scheda_record).data('tableid');
        var serialized_data=$(block_allegati).find('.checkbox').serializeArray();
        serialized_data.push({name: 'tableid', value: tableid});
        serialized_data.push({name: 'recordid', value: recordid});
        serialized_data.push({name: 'fileid', value: fileid});
        $("body").css('cursor', 'progress !important');
         $.ajax({
                url: controller_url + '/prescarica_allegati_selezionati'+'/'+tipo,
                data: serialized_data,
                type: 'post',
                success:function(data){ 
                    $("body").css("cursor", "default");
                    if(data=='null')
                    {
                        alert('Nessun allegato selezionato');
                    }
                    if(data=='allegati.zip')
                    {
                        //var urldownload=controller_url+"/scarica_allegati_selezionati/"+data;
                            alert('Download pronto');
                            var urldownload=jdocserver_url+"generati/"+userid+"/allegati.zip"
                            window.location.href = urldownload 
                        
                    }
                    if((data!='null')&&(data!='allegati.zip'))
                    {
                        var urldownload=controller_url+"/scarica_allegati_selezionati/"+data;
                        window.location.href = urldownload 
                    }
                    
                },
                error:function(){
                    
                    alert('error');
                }
            });
    }
    
    function delete_lookuptable_item(el,lookuptableid,itemcodeval)
    {
        var confirmation=confirm('Sicuro di voler eliminare questo valore?')
        if(confirmation)
        {
            $.ajax({
                   url: controller_url + '/ajax_delete_lookuptable_item/' + lookuptableid,
                   data: { 
                           itemcode: itemcodeval, 
                         },
                   type: 'post',
                   success:function(data){ 
                       if(data!='null')
                       {
                           alert('Eliminato');
                           $(el).closest('.lookuptable_item_container').remove();
                       }
                       else
                       {
                           alert('Valore già esistente');
                       }
                   },
                   error:function(){

                       alert('error');
                   }
               });
        }
        
    }
    
    function chiudi_gestione_lookuptable(el)
    {
        bPopup_gestione_lookuptable.close();
    }
    
    
    function show_field_settings(el)
    {
        $(el).closest('.impostazioni_fieldcontainer').find('.field_settings').toggle(500);
    }
    function manage_lookuptable(el,lookuptableid)
    {
         $.ajax({
                url: controller_url + '/ajax_load_block_gestione_lookuptable/' + lookuptableid,
                dataType: "html",
                success:function(data){ 
                        $("#gestione_lookuptable").html('');
                        bPopup_gestione_lookuptable=$("#gestione_lookuptable").bPopup();
                        $("#gestione_lookuptable").html(data);
                        
                },
                error:function(){
                    
                    alert('error');
                }
            });
        
    }

    function ajax_get_lookuptable_items(el,el_field_id,lookuptableid,fieldid,tableid,code)
    {
        $.ajax({
                url: controller_url + '/ajax_get_lookuptable/' + lookuptableid + '/' + fieldid + '/' + tableid,
                dataType:'JSON',
                success:function(data){  
                    var fieldValue= $('#'+el_field_id);
                    $(fieldValue).html('');
                    $(fieldValue).append('<option  id="" class="emptyOption" value="" > &#9660;</option>');
                    var last_val;
                    var selected='';
                    var style=''
                    $.each(data, function(key, val){ 
                        selected='';
                        if(val.itemcode==code)
                        {
                            selected='selected="selected"';
                        }
                        $(fieldValue).append('<option ' + selected + ' value="' + val.itemcode + '" data-link="'+val.link+'" data-linkfield="'+val.linkfield+'" data-linkvalue="'+val.linkvalue+'" data-linkedfield="'+val.linkedfield+'" data-linkedvalue="'+val.linkedvalue+'">' + val.itemdesc + '</option>');
                        last_val=val;   
                        if(selected!='')
                            {
                                $(fieldValue).css('color', 'black');
                            }
                    })
                    $(fieldValue).attr("data-linkedfield_select",last_val.linkedfield);
                    //var table_container=$(fieldValue).closest('.table_container');
                    //var first=$(table_container).find('.first');
                    //$(first).focus();
                    $(fieldValue).autocomplete();
                },
                error:function(){
                    $('#'+el_field_id).html('<option id="-1">none available</option>');
                }
            });
    }
    
    function ajax_get_lookuptable_itemstemp(el,el_field_id,lookuptableid,fieldid,tableid,code)
    {
        $.ajax({
                url: controller_url + '/ajax_get_lookuptable/' + lookuptableid + '/' + fieldid + '/' + tableid,
                dataType:'JSON',
                success:function(data){  
                    var fieldValue= $('#'+el_field_id);
                    var fieldContainer=$(fieldValue).closest('.fieldcontainer');
                    var fieldLayer=$(fieldContainer).find('.fieldLayer');
                    var fieldValue0=$(fieldContainer).find('.fieldValue0');
                    var last_val;
                    var selected='';
                    var style=''
                    var items;
                    var item;
                    //$(fieldValue).attr("data-linkedfield_select",last_val.linkedfield);
                    $(fieldLayer).autocomplete({
                        source: data,
                        minLength: 0,
                        appendTo: fieldContainer,
                        position: {  
                            collision: "flip"
                              },
                        select: function( event, ui ) {
                        $(fieldValue0).val(ui.item.itemcode);
                        $(fieldLayer).val(ui.item.itemdesc);
                        field_changed($(fieldValue0));
                        return false;
                    }
                    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                          console.info(item);  
                          var li=$( "<li>" );
                          $(li).append( "<a></a>");
                          var a=$(li).find('a');
                          $(a).append(item.itemdesc);
                          
                          $(li).appendTo( ul );
                          return li;
                          };
                },
                error:function(){
                    $('#'+el_field_id).html('<option id="-1">none available</option>');
                }
            });
    }
    
    function lookuptable_click2(el,term){
        $(el).autocomplete( "search", term );
    }
    
    function lookuptable_click(el)
    {
        console.info('fun: lookuptable_click');
        
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldValueContainer=$(fieldcontainer).find('.fieldValueContainer');
        var fieldLayer=$(fieldcontainer).find('.fieldLayer');
        /*ajax_get_lookuptable_items2(el,function(){
                $(fieldLayer).autocomplete( "search", "" );
            });*/ 
        waitloading_lookuptable(el);
        /*console.info($(el).data('loaded'));
        if($(el).data('loaded'))
        {
            console.info('loaded');
            $(el).autocomplete( "search", "" );
        }
        else
        {
            waitloading_lookuptable(el);
        }*/
        
        
       
        
        //$(fieldLayer).autocomplete( "search", "" );
        //$(el).autocomplete( "search", "" );
    }
    
    function waitloading_lookuptable(el,waitcounter)
    {
        if(typeof waitcounter==='undefined')
        {
            waitcounter=1;
        }
        var problemi=true;
        setTimeout(function(){
                console.info(waitcounter);
                if($(el).data('loaded'))
                {
                    console.info('autocomplete');
                    $(el).autocomplete( "search", "" );
                    problemi=false;
                }
                else
                {
                    if(waitcounter>300)
                    {
                        //alert('Problemi nel caricamento del campo');
                    }
                    else
                    {
                        waitcounter=waitcounter+1;
                        waitloading_lookuptable(el,waitcounter);
                    }
                    
                }
        },10);
 
    }
    function ajax_get_lookuptable_items2(el,callback)
    {
        if(!$(el).data('loaded'))
        {
        console.info('fun: ajax_get_lookuptable_items2');
        var lookuptableid=$(el).data('lookuptableid');
        var fieldid=$(el).data('fieldid');
        var tableid=$(el).data('tableid');
        var linkfieldid=$(el).data('linkfieldid');
        
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldValueContainer=$(fieldcontainer).find('.fieldValueContainer');
        var fieldLayer=$(fieldcontainer).find('.fieldLayer');
        var fieldValue0=$(fieldcontainer).find('.fieldValue0');
        var table_container=$(el).closest('.table_container');
        
        var linkfiedlValue0=$(table_container).find('.fiedlValue0_'+linkfieldid);
        var linkvalue=$(linkfiedlValue0).val();
        if($(fieldLayer).data('ui-autocomplete'))
        {
            $(fieldLayer).autocomplete( "destroy" );
        }
        var postData = {
            'linkvalue' : linkvalue
          };
        postData=$.param(postData);
        $.ajax({
            url: controller_url +'/ajax_get_lookuptable2/' + lookuptableid + '/' + fieldid + '/' + tableid,
            type: 'POST',
            data:postData,
            dataType:'JSON',
            success:function(data){
                
                $(fieldLayer).click(function (){
                    $(this).val('');
                })
                $(fieldLayer).autocomplete({
                    minLength: 0,
                    source: data,
                    appendTo: fieldcontainer,
                    autoFocus: true,
                    position: {  collision: "none",of:fieldValueContainer  },
                    select: function( event, ui ) {
                        //var el_value=$('#<?= $fieldid . "-value-0" ?>');
                        $(fieldValue0).val(ui.item.itemcode);
                        //var el_layer=$('#<?= $fieldid . "-layer" ?>');
                        $(fieldLayer).val(ui.item.itemdesc);
                        field_changed($(fieldValue0));
                        return false;
                    },
                    change: function (event, ui) {
                        if (ui.item == null){ 
                         //here is null if entered value is not match in suggestion list
                            $(this).val((ui.item ? ui.item.id : ""));
                        }
                    }
                  })
                  .autocomplete( "instance" )._renderItem = function( ul, item ) {
                          var li=$( "<li>" );
                          $(li).append( "<a></a>");
                          var a=$(li).find('a');
                          if(item.linkfieldid!==null)
                          {
                             $(li).data('linkfieldid',item.linkfieldid); 
                          }
                          if(item.linkvalue!==null)
                          {
                              $(li).data('linkvalue',item.linkvalue);
                          }
                          if((item.icon!='')&&(item.icon!=null))
                          {
                              $(a).append("<img class='autocomplete_icon_small' src='"+item.icon+"'></img>");
                          }
                          $(a).append(item.itemdesc);
                          /*if((item.itemdesc!='')&&(item.itemdesc!=null))
                          {
                              $(a).append('<br>');
                              $(a).append(item.itemdesc);
                          }*/
                          $(li).appendTo( ul );
                          return li;
                          };
            //$(fieldLayer).autocomplete( "search", "" );
                $(fieldLayer).data('loaded',true);
                if(typeof callback!=='undefined')
                {
                    callback();
                }
            }
        });
        }
    }
    
    function set_autocomplete(el){
        $.ajax({
            url: controller_url +'/ajax_get_users',
            dataType:'JSON',
            success:function(data){
                var fieldcontainer=$(el).closest(".fieldcontainer");
                var fieldLayer=$(fieldcontainer).find('.fieldLayer');
                var fieldValue0=$(fieldcontainer).find('.fieldValue0');
                $(fieldLayer).autocomplete({
                    minLength: 0,
                    source: data,
                    appendTo: fieldcontainer,
                    position: {  collision: "flip"  },
                    select: function( event, ui ) {
                        //var el_value=$('#<?= $fieldid . "-value-0" ?>');
                        $(fieldValue0).val(ui.item.value);
                        //var el_layer=$('#<?= $fieldid . "-layer" ?>');
                        $(fieldLayer).val(ui.item.label);
                        field_changed($(fieldValue0));
                        return false;
                    }
                  })
                  .autocomplete( "instance" )._renderItem = function( ul, item ) {
                          var li=$( "<li>" );
                          $(li).append( "<a></a>");
                          var a=$(li).find('a');
                          if((item.icon!='')&&(item.icon!=null))
                          {
                              $(a).append("<img class='autocomplete_icon_small' src='"+item.icon+"'></img>");
                          }
                          $(a).append(item.label);
                          if((item.desc!='')&&(item.desc!=null))
                          {
                              $(a).append('<br>');
                              $(a).append(item.desc);
                          }
                          $(li).appendTo( ul );
                          return li;
                          };
            }
        });
        
    }
    
    function set_autocomplete_lookuptable(el)
    {
        console.info('fun:set_autocomplete_lookuptable');
        var fieldcontainer=$(el).closest(".fieldcontainer");
        var fieldLayer=$(fieldcontainer).find('.fieldLayer');
        var fieldValue0=$(fieldcontainer).find('.fieldValue0');
        var lookuptableid=$(el).data('lookuptableid');
        var tableid=$(el).data('tableid');
        var fieldid=$(el).data('fieldid');
        var linkvalue='';
        
        $(el).autocomplete({
        source: function( request, response ) {
                    var linkvalue='';
                    console.info('fieldid:'+fieldid);
                    if(fieldid=='mandato')
                    {
                        console.info(el);
                        var table_container=$(el).closest('.table_container');
                        console.info(table_container);
                        var stabile_field=$(table_container).find('input[data-fieldid*="numerocantiere"]');
                        console.info(stabile_field);
                        linkvalue=$(stabile_field).val();
                        console.info(linkvalue);
                    }
                    console.info('linkvalue:'+linkvalue);
                    linkvalue=encodeURIComponent(linkvalue);
                    $.ajax( {
                        url: controller_url +'ajax_get_lookuptable3/'+lookuptableid+'/'+fieldid+'/'+tableid+'/'+linkvalue,
                        dataType: "json",
                        data: {
                          term: request.term
                        },
                        success: function( data ) {
                            console.info('data:');
                            console.info(data);
                          response( data );
                        },
                        error:function(){
                            alert('errore');
                        }
                    } );
                  },
        minLength: 1,
        appendTo: $(fieldcontainer),
        position: {  
            collision: "flip"
              },
        select: function( event, ui ) {
            console.info(ui);
            $(fieldValue0).val(ui.item.itemcode);
            $(fieldLayer).val(ui.item.itemdesc);
            field_changed($(fieldValue0));
        },
        create: function( event, ui ) {
            var table_block=$(fieldValue0).closest('.table_block');
            var funzione=$(table_block).data('funzione');
            if(funzione=='inserimento')
            {
                field_changed($(fieldValue0));

            }
        },
      });
    }
    
    function results_set_autocomplete_lookuptable(el)
    {
        console.info('fun:results_set_autocomplete_lookuptable');
        var fieldcontainer=$(el).closest(".fieldcontainer");
        $(el).autocomplete({
        source: function( request, response ) {
                    $.ajax( {
                        url: controller_url +'ajax_get_lookuptable3/localita_azienda',
                        dataType: "json",
                        data: {
                          term: request.term
                        },
                        success: function( data ) {
                          response( data );
                        },
                        error:function(){
                            alert('errore');
                        }
                    } );
                  },
        minLength: 2,
        appendTo: $(fieldcontainer),
        position: {  
            collision: "flip"
              },
        select: function( event, ui ) {
        },
        create: function( event, ui ) {
        },
      });
    }
    
    
    
    function get_autocomplete_lookuptable_items(el,lookuptableid,fieldid,tableid,term)
    {
        
        var url=controller_url +'ajax_get_lookuptable3/'+lookuptableid+'/'+fieldid+'/'+tableid+'/'+linkvalue+'?term='+term;
        $.ajax( {
            type: "html",
            url: url,
            dataType: "json",
            success: function( response ) {
                console.info(response);
                return response;
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    function direct_update(el,tableid,recordid,fieldid,value)
    {
        var serialized=[];
        serialized.push({name: 'field[fieldid]', value: fieldid});
        serialized.push({name: 'field[value]', value: value});
        var url=controller_url + '/ajax_direct_update/' + tableid + '/' + recordid;
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                
            }
        })
    }
    
    
    function loadToJDocOnlineCV(el,tableid,recordid)
    {
        var input_allfields=$(el).find('#allfields');
        //var remote_url='http://localhost:8822/jdoconlinecv/index.php/sys_viewcontroller/ajax_set_allfields';
        var remote_url='http://workandwork.com/OnlineCV/index.php/sys_viewcontroller/ajax_set_allfields';
        var url=controller_url + '/ajax_get_allfields/' + tableid + '/' + recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                var obj = JSON.parse(response);
                var mail='';
                if("candmail" in obj)
                    if("0" in obj["candmail"])
                        if("fields" in obj["candmail"][0])
                            if("indirizzo" in obj["candmail"][0]["fields"])
                                mail=obj["candmail"][0]['fields']['indirizzo']
                $(input_allfields).val(response);
                $.ajax( {
                    type: "POST",
                    url: remote_url,
                    data: $(input_allfields).serialize(),
                    success: function( response ) {
                        //var obj = JSON.parse(response);
                        var onlinecv_popup=$('#onlinecv_popup');
                        $(onlinecv_popup).bPopup({},function(){
                            var divresponse=$(onlinecv_popup).find('#response');
                            $(divresponse).html(response);
                            var jdoconline_url=$(onlinecv_popup).find('#jdoconline_url');
                            $(jdoconline_url).attr('href','http://workandwork.com/OnlineCV/index.php/sys_viewcontroller/view_onlineform/'+tableid+'/'+recordid);
                            $(jdoconline_url).html('http://workandwork.com/OnlineCV/index.php/sys_viewcontroller/view_onlineform/'+tableid+'/'+recordid);
                            var btn_invia_mail=$(onlinecv_popup).find("#btn_invia_mail");
                            $(btn_invia_mail).unbind( "click" );    
                            $(btn_invia_mail).click(function(){
                                    window.open('mailto:'+mail+"?subject=Aggiornamento CV&body="+'http://workandwork.com/OnlineCV/index.php/sys_viewcontroller/view_onlineform/'+tableid+'/'+recordid);
                                })
                        });
                        


                        
                    },
                    error:function(){
                        alert('errore');
                    }
                } ); 
            },
            error:function(){
                alert('errore');
            }
        } ); 
        
        
    }
    
    function ajax_get_new_records(el,tableid)
    {
        var url=controller_url + '/get_new_records/' + tableid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                alert('Dati scaricati');
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }

function toggle_info_scheda(el){
        var scheda=$(el).closest('.scheda');
        var fissi_container=$(scheda).find('.fissi_container');
        var record_info=$(fissi_container).find('.record_info');
       $(record_info).toggle()();
    }
    
    
    function chiudi_popup_generico(el)
    {
        bPopup_generico.close();
    }
    
    function chiudi_scheda(el){
        var scheda=$(el).closest('.scheda');
        var scheda_container=$(el).closest('.scheda_container');
        var scheda_container_id=$(scheda_container).attr('id');
        var popuplvl=$(scheda).data('popuplvl');
        try 
        {
            bPopup[scheda_container_id].close();
        }
        catch(err)
        {
            console.info('nopopup');
        }
        var container=$(el).closest('.scheda_container');
        var id=$(container).attr('id');
        if(id==ultimascheda)
        {
          ultimascheda="";
        }
        $('#nav_'+id).remove();
        container.remove();  
    }
    
    function chiudi_nuovo(el)
    {
        chiudi_scheda(el);
        var risultati_ricerca=$('#risultati_ricerca');
        var risultati_ricerca_btn_plus_right=$(risultati_ricerca).find('.btn_plus_right');
        var block_scheda_container=$(el).closest('.scheda_container');
        var block_dati_labels=$(block_scheda_container).find('.block_dati_labels');
        var tableid=$(block_dati_labels).data('tableid')
        apri_scheda_record(risultati_ricerca_btn_plus_right,tableid,'null','popup','allargata','risultati_ricerca');
    }
    
    function set_pinned(el)
    {
        var scheda=$(el).closest('.scheda');
        if($(scheda).data('pinned'))
        {
            $(scheda).data('pinned',false);
            $(el).css("color", "black"); 
        }
        else
        {
            $(scheda).data('pinned',true);
            $(el).css("color", "orange"); 
        }
        
    }
    
    function prestampa_scheda_record_completa(tableid,recordid)
    {
      var url=controller_url + '/ajax_prestampa_scheda_record_completa/' + tableid + '/' + recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                
            },
            error:function(){
                alert('errore');
            }
        } );   
    }
    
    function stampa_prospetto_pdf(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_prospetto_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_fattura_pdf(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_fattura_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function anteprima_prospetto_pdf_nuovo(el,recordid)
    { 
        $('#anteprima_prestampa_popup').html('attendere, generazione in corso');
        anteprima_prestampa_popup=$('#anteprima_prestampa_popup').bPopup();
        
        var url=controller_url + '/ajax_anteprima_prospetto_pdf_nuovo/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                
                //$('#anteprima_prestampa_popup').html(response);
                
                anteprima_prestampa_popup.close();
                
                var win = window.open(jdocserver_url+'stampe/stampa.html', '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                }
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_prospetto_pdf_nuovo(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_prospetto_pdf_nuovo/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_prospetto_proposta(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_prospetto_proposta/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_rapportino_pdf_from_word(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_rapportino_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function invia_stampa(el,path_stampa)
    {
        var visualizzatore=$(el).closest('.visualizzatore');
        var tableid=$(visualizzatore).data('tableid');
        var recordid=$(visualizzatore).data('recordid');
        var mail_cliente='';
        var url=controller_url + '/ajax_get_emailinviodoc/'+tableid+'/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                mail_cliente=response;
                var mail = prompt("Mail a cui inviare?",mail_cliente);
                if((mail!='')&&(mail!=null))
                {
                    var serialized=[];
                    serialized.push({name: 'mail', value: mail});
                    serialized.push({name: 'path_stampa', value: path_stampa});
                    serialized.push({name: 'tableid', value: tableid});
                    serialized.push({name: 'recordid', value: recordid});


                        var url=controller_url+'ajax_invia_stampa/';
                        $.ajax( {
                            type: "POST",
                            url: url,
                            data: serialized,
                            success: function( response ) {
                                alert('stampa inviata');
                            },
                            error:function(){
                                alert('errore');
                            }
                        });
                }
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
        
        
    }
    
    function invia_stampa_avanzata(el,path_stampa)
    {
        var visualizzatore=$(el).closest('.visualizzatore');
        var tableid=$(visualizzatore).data('tableid');
        var recordid=$(visualizzatore).data('recordid');
        var serialized=[];
        serialized.push({name: 'path_stampa', value: path_stampa});
        serialized.push({name: 'tableid', value: tableid});
        serialized.push({name: 'recordid', value: recordid});

            $('#prestampa').html('attendere, generazione in corso');
            prestampa_popup=$('#prestampa').bPopup();
            var url=controller_url+'ajax_invia_stampa_avanzata/';
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {
                    alert(response);
                    prestampa_popup.close();
                    bPopup_generico.close();
                    apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster','modifica');
                    
                },
                error:function(){
                    alert('errore');
                }
            });
      
        
        
    }
    
    function stampa_rapportino_pdf(el,recordid)
    {
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_rapportino_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_rapportini_pdf(el,recordid)
    {
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_rapportini_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_rapportini_pdf2(el,recordid)
    {
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_rapportini_pdf2/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_offerta_seatrade(el,recordid)
    {
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_offerta_seatrade/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function genera_mail_prospetto(el,direttodaimmobile)
    {
        var block=$(el).closest('.block');
        var scheda_record=$(el).closest('.scheda_record');
        //var recordid_richiesta=$(block).find("#recordid_richiesta").val();
        var recordid_immobile=$(scheda_record).data('recordid');
        $(block).html('attendere, generazione in corso');
        var url=controller_url + '/ajax_genera_mail_prospetto/'+recordid_immobile+'/'+direttodaimmobile;
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        
        $.ajax( {
            type: "POST",
            url: url,
            data: $(el).closest('form').serialize(),
            success: function( response ) {
                console.info(response);
                prestampa_popup.close();
                apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster','modifica');
                //apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster')
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function genera_mail_risposta_ticket(el)
    {
        var block=$(el).closest('.block');
        var scheda_record=$(el).closest('.scheda_record');
        var recordid=$(scheda_record).data('recordid');
        $(block).html('attendere, generazione in corso');
        var url=controller_url + '/ajax_genera_mail_risposta_ticket/'+recordid;
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        
        $.ajax( {
            type: "POST",
            url: url,
            data: $(el).closest('form').serialize(),
            success: function( response ) {
                console.info(response);
                prestampa_popup.close();
                apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster','modifica');
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function preinvio_prospetto(el,recordid)
    { 
        
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_preinvio_prospetto/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                //$('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();

                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    /*
    function stampa_vetrina_pdf(el,recordid)
    { 
        var url=controller_url + '/ajax_stampa_vetrina_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#stampa').html(response);
                setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }*/
    
    function stampa_vetrina_pdf(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_vetrina_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_vetrina_pdf2(el,recordid)
    { 
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url=controller_url + '/ajax_stampa_vetrina_pdf2/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#prestampa').html(response);
                prestampa_popup=$('#prestampa').bPopup();
                /*setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);*/
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_registrazione_pdf(el,recordid)
    { 
        var url=controller_url + '/ajax_stampa_registrazione_pdf/'+recordid;
        $.ajax( {
            type: "html",
            url: url,
            success: function( response ) {
                $('#stampa').html(response);
                setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                    window.print(); 
                    $('#stampa').hide();
                    $('#jdocweb_wrapper').show();
                },1000);
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function stampa_scheda_record_completa(el,tableid,recordid)
    { 
        prestampa_popup.close();
        var url=controller_url + '/ajax_stampa_scheda_record_completa/' + tableid + '/' + recordid;
        $.ajax( {
            type: "POST",
            url: url,
            data: $(el).closest('form').serialize(),
            success: function( response ) {
                $('#stampa').html(response);
                setTimeout(function(){
                    $('#jdocweb_wrapper').hide();
                    $('#stampa').show();
                   window.print(); 
                   $('#stampa').hide();
                   $('#jdocweb_wrapper').show();
                   
                },1000);
                
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function ajax_load_block_manuale()
    {
        var url=controller_url+'ajax_load_block_manuale';
        $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                            $('#manuale_container').html(data);
                            $('#manuale_container').bPopup();
                            
                    },
                    error:function(){
                        alert('errore');
                    }
                });
    }
    function ajax_load_block_segnalazioni()
    {
        var url=controller_url+'ajax_load_block_segnalazioni';
        $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                            $('#segnalazioni_container').html(data);
                            bPopup_segnalazione=$('#segnalazioni_container').bPopup();
                    },
                    error:function(){
                        alert('errore');
                    }
                });
    }
    
    function riepilogo_segnalazioni_popup(el)
    {
        bPopup_segnalazione.close();
        var url=controller_url+'ajax_load_block_riepilogo_segnalazioni';
        bPopup_riepilogo_segnalazioni=$('#riepilogo_segnalazioni_container').bPopup();
        $('#riepilogo_segnalazioni_container').html('Caricamento');
        $.ajax({
                    url: url,
                    dataType:'html',
                    success:function(data){
                            
                            $('#riepilogo_segnalazioni_container').html(data);
                            
                    },
                    error:function(){
                        alert('errore');
                    }
                });
        
    }
    
    function invia_segnalazione(el)
    {
        var url='http://server.about-x.com:8822/jdocweb_test/index.php/sys_viewcontroller/ajax_invia_segnalazione';
        //var url=controller_url+'ajax_invia_segnalazione';
        var form=$(el).closest('form');
        $.ajax( {
            url: url,
            type: 'post',
            data: new FormData($(form)[0]),
            processData: false,
            contentType: false,
            success: function( response ) {
                alert('Segnalazione inviata');
                bPopup_segnalazione.close();
            },
            error:function(){
                alert("E' necessario contattare direttamente l'assistenza");
            }
        } ); 
    }
    
    function save_view(el,tableid){
        var view_name=prompt("Nome ricerca");
        if((view_name!=null)&&(view_name!=''))
        {
            var scheda_dati_ricerca=$(el).closest('.scheda_dati_ricerca');
            var url=controller_url+'ajax_save_view/'+tableid;
            var form_riepilogo=$(scheda_dati_ricerca).find('.form_riepilogo');
            $(form_riepilogo).find('#view_name').val(view_name);
            $.ajax( {
                type: "POST",
                url: url,
                data: $(form_riepilogo).serialize(),
                success: function( response ) {
                    alert('salvata');

                },
                error:function(){
                    alert('errore');
                }
            } ); 
        }
    }
    
    function view_changed(el,tableid,viewid)
    {
        //var viewid=$(el).val();
       /* var url=controller_url+'ajax_view_changed/'+tableid+'/'+viewid;
        $.ajax( {
            type: "url",
            url: url,
            success: function( response ) {
                var scheda_dati_ricerca=$(el).closest('.scheda_dati_ricerca');
                var form_riepilogo=$(scheda_dati_ricerca).find('.form_riepilogo');
                $(form_riepilogo).find('#query').val(response); 
                ajax_load_block_risultati_ricerca(el,tableid);
            },
            error:function(){
                alert("Errore");
            }
        } ); */
        var scheda_dati_ricerca_container=$('.scheda_dati_ricerca_container');
        var url=controller_url+'ajax_load_scheda_dati_ricerca/'+tableid+'/'+viewid;
        $.ajax( {
                dataType:'html',
                url: url,
                success: function( response ) {
                    $(scheda_dati_ricerca_container).html(response);
                    refresh_risultati_ricerca();
                    ajax_load_block_dati_labels(tableid,'null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));
                },
                error:function(){
                    alert('errore'); 
                }
            } ); 
    }
    
    function save_report(el,tableid)
    {
            var form=$(el).closest('form');
            var url=controller_url+'ajax_save_report/'+tableid;
            $.ajax( {
                type: "POST",
                url: url,
                data: $(form).serialize(),
                success: function( response ) {
                    alert('salvata');

                },
                error:function(){
                    alert('errore');
                }
            } ); 
        
    }
    
    function set_default_view(el,viewid)
    {
        var scheda=$(el).closest('.scheda');
        var tableid=$(scheda).data('tableid');
        //var saved_view_select=$(scheda).find('#saved_view_select');
        //var viewid=$(saved_view_select).val();
        var url=controller_url+'ajax_set_default_view/'+tableid+'/'+viewid;
            $.ajax( {
                type: "url",
                url: url,
                success: function( response ) {
                    if(response=='ok')
                    {
                        alert('salvata');
                    }
                    else
                    {
                        alert('errore: '+response);
                    }

                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function unset_default_view(el,viewid)
    {
        var scheda=$(el).closest('.scheda');
        var tableid=$(scheda).data('tableid');
        //var saved_view_select=$(scheda).find('#saved_view_select');
        //var viewid=$(saved_view_select).val();
        var url=controller_url+'ajax_unset_default_view/'+tableid+'/'+viewid;
            $.ajax( {
                type: "url",
                url: url,
                success: function( response ) {
                    if(response=='ok')
                    {
                        alert('salvata');
                    }
                    else
                    {
                        alert('errore: '+response);
                    }

                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function delete_view(el,viewid)
    {
        var scheda=$(el).closest('.scheda');
        var tableid=$(scheda).data('tableid');
        //var saved_view_select=$(scheda).find('#saved_view_select');
        //var viewid=$(saved_view_select).val();
        var url=controller_url+'ajax_delete_view/'+viewid;
            $.ajax( {
                dataType: "html", 
                url: url,
                success: function( response ) {
                    if(response=='ok')
                    {
                        alert('eliminata');
                    }
                    else
                    {
                        alert('errore: '+response);
                    }

                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function rename_view(el,viewid)
    {
        var scheda=$(el).closest('.scheda');
        var tableid=$(scheda).data('tableid');
        //var saved_view_select=$(scheda).find('#saved_view_select');
        //var viewid=$(saved_view_select).val();
        var view_name=prompt("Nome ricerca");
        if((view_name!=null)&&(view_name!=''))
        {
            var serialized=[];
            serialized.push({name: 'view_name', value: view_name});

            var url=controller_url+'ajax_rename_view/'+viewid;
                $.ajax( {
                    type: "POST",
                    url: url,
                    data: serialized,
                    success: function( response ) {
                        if(response=='ok')
                        {
                            alert('rinominata');
                        }
                        else
                        {
                            alert('errore: '+response);
                        }

                    },
                    error:function(){
                        alert('errore');
                    }
                } ); 
        }
        
    }
    
    function set_css_view(el,viewid)
    {
        var scheda=$(el).closest('.scheda');
        var tableid=$(scheda).data('tableid');
        //var saved_view_select=$(scheda).find('#saved_view_select');
        //var viewid=$(saved_view_select).val();
        var url=controller_url+'ajax_set_css_view/'+tableid+'/'+viewid;
            $.ajax( {
                type: "url",
                url: url,
                success: function( response ) {
                    if(response=='ok')
                    {
                        alert('css impostato');
                    }
                    else
                    {
                        alert('errore: '+response);
                    }

                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function custom_3p_presenzemensili_alias(el)
    {
        var mostra_alias=$(el).data('mostra_alias');
        var query=$('#query').val();
        if(mostra_alias==false)
        {
            $(el).data('mostra_alias',true);
            query=query.replace(" AND (alias is null OR alias='')",'');
            $(el).html('NASCONDI ALIAS');
        }
        if(mostra_alias==true)
        {
            $(el).data('mostra_alias',false);
            query=query+" AND (alias is null OR alias='')";
            $(el).html('MOSTRA ALIAS');
        }
        
        $('#query').val(query);
        refresh_risultati_ricerca();
        
    }
    function refresh_risultati_ricerca(activetab_id)
    { 
        console.info('fun:refresh_risultati_ricerca');
        var serialized=$('#form_riepilogo').serializeArray();
        var scheda_dati_ricerca=$('#scheda_dati_ricerca');
        var tableid=$(scheda_dati_ricerca).data('tableid');
        var page=$('#results_currentpage').val();
        console.info('results_currentpage: '+page);
        var results_order_key=$('#results_order_key').val();
        console.info('results_order_key: '+results_order_key);
        var results_order_ascdesc=$('#results_order_ascdesc').val();
        console.info('results_order_ascdesc: '+results_order_ascdesc);
        
        
        if(typeof activetab_id==='undefined')
        {
            activetab_id=$('#tabs_risultati_ricerca').find("[aria-selected='true']").attr('aria-controls')
        }
        
        var scrollTop = $('#'+activetab_id).find('.results_rows').scrollTop();
        if(typeof scrollTop=='undefined')
        {
            scrollTop=0;
        }
        $('#'+activetab_id).html('loading');
        
        
        var url='';
        if(activetab_id=='risultati_ricerca_datatable')
        {
            url=controller_url+'ajax_load_block_datatable_records/'+tableid+'/risultati_ricerca';
        }  
        if(activetab_id=='risultati_ricerca_results')
        {
            url=controller_url+'ajax_load_block_results/'+tableid+'/risultati_ricerca';
            serialized.push({name: 'order_key', value: results_order_key});
            serialized.push({name: 'order_ascdesc', value: results_order_ascdesc});
        }
        if(activetab_id=='risultati_ricerca_report')
        {
            url=controller_url+'ajax_load_block_reports_relativi/'+tableid;
        }   
        if(activetab_id=='risultati_ricerca_calendar')
        {
            url=controller_url+'ajax_load_block_calendar/'+tableid;
        }   
       
        var view_selected=$(scheda_dati_ricerca).find('.view_selected');
        var view_selected_id=$(view_selected).data('saved_view_id');
        var form_riepilogo=$(scheda_dati_ricerca).find('.form_riepilogo');
        
        serialized.push({name: 'view_selected_id', value: view_selected_id});
        serialized.push({name: 'scrollTop', value: scrollTop});
        
        
        if(typeof page==='undefined')
        {
            page=1;
        }
        serialized.push({name: 'page', value: page});
        
        
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                console.info(response);
                $('#'+activetab_id).html(response);
                
                
                
            },
            error:function(){
                alert('errore refresh_risultati_ricerca');
            }
        } ); 
        
        
        
    }
    
    function results_annulla_linked(el)
    {
        $(el).closest('.fieldcontainer').find('.linked_link').hide();
        var linked_input=$(el).closest('.fieldcontainer').find('.linked_input');
        $(linked_input).show();
        $(linked_input).val('');
        results_field_changed($(linked_input));
    }
    
    function edit_linked(el)
    {
        $(el).closest('.fieldcontainer').find('.linked_link').hide();
        var linked_input=$(el).closest('.fieldcontainer').find('.linked_input');
        $(linked_input).show();
        $(linked_input).click();
        $(linked_input).focus();
        
    }
    
    function esporta_xls2(el,tableid)
    {
        $('#hidden_form').html('');
        var tableid=$('#form_riepilogo').find('#tableid').val();
        var query=$('#form_riepilogo').find('#query').val();
        $('#hidden_form').append($("<input>").attr("type", "hidden").attr("name", "tableid").val(tableid));
        $('#hidden_form').append($("<input>").attr("type", "hidden").attr("name", "query").val(query));
        $('#hidden_form').attr('action', controller_url+'esporta_xls2/'); 
        $('#hidden_form').submit();
        /*var serialized=$('#form_riepilogo').serializeArray();
        var url=controller_url+'esporta_xls2/';
        
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                $("html").html(response);
            },
            error:function(){
                alert('errore');
            }
        } ); */
    }
    
    function esporta_xls(el,exportid)
    {
        $('#form_riepilogo').find('#exportid').val(exportid);
        $('#form_riepilogo').submit();
        
    }
    
    function esporta_excel_lgl(el)
    {
        $('#form_riepilogo').find('#exportid').val('export_lgl');
        $('#form_riepilogo').submit();
        /*var url=controller_url+'ajax_esporta_lgl/';
            $.ajax( {
                type: "POST",
                url: url,
                data: $('#form_riepilogo').serialize(),
                success: function( response ) {
                    var uri = "data:text/html," + encodeURIComponent(response);
                    var newWindow = window.open(uri);
                    //document.write(response);
                    //document.close();
                },
                error:function(){
                    alert('errore');
                }
            } );*/
    }
    
    function esporta_file_lgl(el,userid)
    {
        var urldownload=jdocserver_url+"generati/"+userid+"/documenti_lgl.zip"
        var url=controller_url+'ajax_esporta_file_lgl/';
            $.ajax( {
                type: "POST",
                url: url,
                data: $('#form_riepilogo').serialize(),
                success: function( response ) {
                    //window.location.href = urldownload
                    alert('ok');
                },
                error:function(){
                    alert('errore');
                }
            } );
    }
    
    
    function genera_dem(el)
    {
        var url=controller_url+'genera_dem/';
            $.ajax( {
                type: "POST",
                url: url,
                data: $('#form_riepilogo').serialize(),
                success: function( response ) {
                    alert('create');
                },
                error:function(){
                    alert('errore');
                }
            } );
    }
    
    function permessi_record_popup(el)
    {
        var scheda_record=$(el).closest('.scheda_record');
        var tableid=$(scheda_record).data('tableid');
        var recordid=$(scheda_record).data('recordid');
        var permessi_record_popup=$(scheda_record).find('#permessi_record_popup');
        var scheda_record_id=$(el).closest('.scheda_record').attr('id');
        var url=controller_url+'ajax_load_block_permessi_record/'+tableid+'/'+recordid+'/'+scheda_record_id;
        $.ajax( {
            type: "url",
            url: url,
            success: function( response ) {
                $(permessi_record_popup).html(response);
                bPopup_permessi_record= $(permessi_record_popup).bPopup();
            },
            error:function(){
                alert('errore');
            }
        } ); 
        
    }
    
    function salva_permessi_record(el,scheda_record_id)
    {
        var tableid=$('#'+scheda_record_id).data('tableid');
        var recordid=$('#'+scheda_record_id).data('recordid');
        var form_dati_labels=$('#'+scheda_record_id).find('.form_dati_labels');
        var block_permessi_record=$(el).closest('#block_permessi_record');
        var lista_permessi_utente=$(block_permessi_record).find('#lista_permessi_utente');
        if(recordid==null)
        {
            
            $(form_dati_labels).append(lista_permessi_utente);
            bPopup_permessi_record.close();
        }
        else
        {
            var url=controller_url+'ajax_salva_permessi_record/'+tableid+'/'+recordid;
            $.ajax( {
                type: "POST",
                url: url,
                data: $(lista_permessi_utente).find('.field_check').serialize(),
                success: function( response ) {
                    alert('Permessi impostati');
                    bPopup_permessi_record.close();
                },
                error:function(){
                    alert('errore');
                }
            } );
        }
        
    }
    


    function copia_mail_pushup(el)
    {
        $('#anteprima_pushup_container').selText();
        //window.prompt("Copy to clipboard: Ctrl+C, Enter", $('#anteprima_pushup_container').html());
    }
    
    function load_block_impostazioni_script_menu(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_script_menu/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    
    function load_block_impostazioni_scheduler_menu(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_scheduler_menu/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function send_queued_mail()
    {
            var url=controller_url+'send_queued_mail/';
            $.ajax( {
                dataType: "html",
                url: url,
                success: function( response ) {
                    $('.bPopup_generico').html(response);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni(el,tipo)
    {
        var url=controller_url+'ajax_load_block_impostazioni/'+tipo;
            $.ajax( {
                dataType: "html",
                url: url,
                success: function( response ) {
                    if(tipo=='dashboard')
                    {
                        $('#centrale').html('');
                        $('#centrale').html(response);
                    }
                    else
                    {
                        $('#sottogruppo').html('');
                        $('#sottogruppo').html(response);
                    }
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_archivi(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_archivi/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_menubase(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_menubase/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_dashboard(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_dashboard/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_utente(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_utente/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_dati_menu(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_dati_menu/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    function load_block_impostazioni_layout_menu(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_layout_menu/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function check_scheduled(el)
    {
            var url=scheduler_controller_url+'check_scheduled/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    alert('checked');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_scheduler_log(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_scheduler_log/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#centrale').hide();
                    $('#centrale').html('');
                    $('#centrale').html(response);
                    $('#centrale').show('slow');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_scheduler_tasks(el)
    {
            var url=controller_url+'ajax_load_block_impostazioni_scheduler_tasks/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#centrale').hide();
                    $('#centrale').html('');
                    $('#centrale').html(response);
                    $('#centrale').show('slow');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_archivio(idarchivio)
    {
            var url=controller_url+'ajax_load_block_impostazioni_archivio/'+idarchivio;
            $.ajax( {
                dataType: "html",
                url: url,
                success: function( response ) {
                    $('#centrale').hide();
                    $('#centrale').html('');
                    $('#centrale').html(response);
                    $('#centrale').show('slow');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_archivio_sottosezione(idarchivio,sottosezione)
    {
            var url=controller_url+'ajax_load_block_impostazioni_archivio_sottosezione/'+idarchivio+'/'+sottosezione;
            $.ajax( {
                dataType: "html",
                url: url,
                success: function( response ) {
                    $('#impostazioni_archivio_sottosezione').hide();
                    $('#impostazioni_archivio_sottosezione').html('');
                    $('#impostazioni_archivio_sottosezione').html(response);
                    $('#impostazioni_archivio_sottosezione').show('slow');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function load_block_impostazioni_sottosezione(tipo,sottosezione)
    {
            var url=controller_url+'ajax_load_block_impostazioni_sottosezione/'+tipo+'/'+sottosezione;
            $.ajax( {
                dataType: "html",
                url: url,
                success: function( response ) {
                    $('#centrale').hide();
                    $('#centrale').html('');
                    $('#centrale').html(response);
                    $('#centrale').show('slow');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function salva_impostazioni_archivio_settings(el,idarchivio)
    {
        var url=controller_url+'ajax_salva_impostazioni_archivio_settings/'+idarchivio;
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#impostazioni_archivio_settings_form').serialize(),
            success: function( response ) {
                alert('salvati');

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function salva_impostazioni_user_settings(el)
    {
        var url=controller_url+'ajax_salva_impostazioni_user_settings/';
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#impostazioni_user_settings_form').serialize(),
            success: function( response ) {
                alert('salvati');

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function salva_impostazioni_dashboard(el)
    {
        var url=controller_url+'ajax_salva_impostazioni_dashboard/';
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#form_impostazioni_dashboard').serialize(),
            success: function( response ) {
                alert('salvati');

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
    function salva_impostazioni_archivio_campi(el,idarchivio)
    {
        var url=controller_url+'ajax_salva_impostazioni_archivio_campi/'+idarchivio;
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#impostazioni_archivio_campi_form').serialize(),
            success: function( response ) {
                alert('salvati');

            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    
function download_export(filename)    
{
    
    var urldownload=controller_url+'download_export/'+filename;
    window.location.href = urldownload;

} 

function scarica_documento(path,filename)    
{
    
    var urldownload=controller_url+'download_template/'+template_path+'/'+filename;
    window.location.href = urldownload;

}

function stampa_mandato(recordid)
{
    var urlprint="<?php echo site_url('sys_viewcontroller/stampa_mandato'); ?>/";
    var urldownload="<?php echo site_url('sys_viewcontroller/download_mandato/Mandato.doc'); ?>/";
    $.ajax({
        url: urlprint + recordid,
        success:function(data){
            window.location.href = urldownload;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}


function visualizza_filtriBAK(el,tableid)
{
    var displayed= $('#scheda_dati_ricerca_container').data('displayed');
    if(displayed==false)
    {
        $('#content_ricerca').scrollTo($('#scheda_dati_ricerca_container'),500);
        $('#scheda_dati_ricerca_container').data('displayed',true);
        $('#scheda_dati_ricerca_container').show();
        if( $('#block_dati_labels_container').length )
        {
            ajax_load_block_dati_labels(tableid,'null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));
        }
    }
    else
    {
        $('#content_ricerca').scrollTo($('#scheda_risultati'),500,
        {
              }
                
                );
        //$('#scheda_dati_ricerca_container').data('displayed',false);
    }
    
    
}

function visualizza_filtri(el,tableid)
{
    
    $('#scheda_dati_ricerca_container').toggle(); 
    $('#content_ricerca').scrollTo(0,500);
    ajax_load_block_dati_labels(tableid,'null','ricerca','scheda_dati_ricerca',$('#block_dati_labels_container'));
    
    
}


function add_linked(el)
{
    var label_table=$(el).closest('.label_table');
    $(label_table).find('.add_linked').click();
    labelclick($(label_table).find('.tablelabel'));
}
    
function custom_seatrade_caricamento_manuale(el)
{
    var scheda_record=$(el).closest('.scheda_record');
    var recordid=$(scheda_record).data('recordid');
    var url=controller_url+'custom_seatrade_caricamento_manuale/'+recordid;
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    alert('Documento aggiunto in coda di invio');
                },
                error:function(){
                    alert('errore');
                }
            } ); 
}


function caricamento_immobile_portale(recordid,portale)
{
    var confirmation=confirm('Sicuro di voler caricare questo immobile su '+portale+' ?')
        if(confirmation)
            {
                var url=controller_url+'ajax_caricamento_immobile_portale/'+recordid+'/'+portale;
                $.ajax({
                            url : url,
                            success : function (response) {
                                alert(response);

                            },
                            error : function () {
                                alert("Errore");
                            }
                        });
            }
}


function load_options_fields(el,tableid,fieldtype)
{
    if(!$(el).data('loaded'))
    {
        var url=controller_url+'load_options_fields/'+tableid+'/'+fieldtype;
        $.ajax( {
            url: url,
            dataType: 'json',
            success: function( data ) {
                var options="<option value=''></option>";
                $.each(data,function(index,value){
                    options=options + "<option value='" + value['fieldid'] +"'>" + value['fieldid'] + "</option>";
                });
                console.info(options);
                $(el).html(options);
            },
            error:function(){
                alert('errore');
            }
        } );
    $(el).data('loaded',true);
    }
}

function option_showedbyfield_changed(el)
{
    var showedbyvalue=$(el).closest('.field_settings').find('.option_showedbyvalue');
    $(showedbyvalue).html('');
    $(showedbyvalue).data('loaded',false);
}


function load_options_lookupitems(el,tableid)
{
    if(!$(el).data('loaded'))
    {
        var fieldid=$(el).closest('.field_settings').find('.option_showedbyfieldid').val();
        var url=controller_url+'ajax_get_options_lookuptableid_byfieldid/'+tableid+'/'+fieldid;
        $.ajax( {
            url: url,
            dataType: 'json',
            success: function( data ) {
                var Options="<option value=''></option>";
                $.each(data,function(index,value){
                    Options=Options + "<option value='" + value['itemcode'] +"'>" + value['itemcode'] + "</option>";
                });
                $(el).html(Options);
            },
            error:function(){
                alert('errore');
            }
        } );
    $(el).data('loaded',true);
    }
}

function load_options_sublabel(el,tableid)
{
    if(!$(el).data('loaded'))
    {
        var url=controller_url+'ajax_get_options_sublabel/'+tableid;
        $.ajax( {
            url: url,
            dataType: 'json',
            success: function( data ) {
                var options="<option value=''></option>";
                $.each(data,function(index,value){
                    options=options + "<option value='" + value['sublabelname'] +"'>" + value['sublabelname'] + "</option>";
                });
                console.info(options);
                $(el).html(options);
            },
            error:function(){
                alert('errore');
            }
        } );
        $(el).data('loaded',true);
    }
}

function ajax_caricamento_immobile_portale(el)
    {
            var url=controller_url+'ajax_caricamento_immobile_portale/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }

function script_migrazione_campi(el)
    {
            var url=script_url+'script_migrazione_campi/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function script_add_columns(el)
    {
        var table=prompt("Table?");
            var url=controller_url+'script_add_columns/'+table;
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
function ajax_matching(el)
    {
            var url=controller_url+'matching/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
    function ajax_genera_preview(el)
    {
            var url=controller_url+'script_genera_preview_immobili/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('#sottogruppo').html('');
                    $('#sottogruppo').html(response);
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }

function google_calendar_sync(el)
    {
            var url=controller_url+'google_calendar_sync/';
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('.bPopup_generico').html(response);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
    
function avvia_dem(recordid,tipoinvio)
    {
            var url=controller_url+'avvia_dem/'+recordid+'/'+tipoinvio;
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    console.info(response);
                    alert("Mail dem caricate per l'invio");
                },
                error:function(){
                    alert('errore');
                }
            } ); 
    }
 function mail_alert_run(alert_id)
 {
     var url=controller_url+'mail_alert_run/'+alert_id;
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('.bPopup_generico').html(response);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            } ); 
 }
 function scheduler_task_run(scheduler_task_id)
 {
     var url=controller_url+'scheduler_task_run/'+scheduler_task_id;
            $.ajax( {
                type: "html",
                url: url,
                success: function( response ) {
                    $('.bPopup_generico').html(response);
                    $('.bPopup_generico').bPopup();
                },
                error:function(){
                    alert('errore');
                }
            } ); 
 }
 
 function salva_impostazioni_archivio_alert(el)
    {
        var impostazioni_archivio_settings_form=$('#impostazioni_archivio_settings_form');
        $.ajax( {
                    type: "POST",
                    url: controller_url + '/salva_impostazioni_archivio_alert',
                    data: $(impostazioni_archivio_settings_form).serialize(),
                    success: function( response ) {
                        alert('Salvato');
                    },
                    error:function(){
                        alert('errore');
                    }
                } );
    }

function dem_carica_mail(dem_recordid)
{
    var form_riepilogo=$('#form_riepilogo');
   var url=controller_url+'dem_carica_mail/'+dem_recordid;
    $.ajax({
        
                url: url,
                data:$(form_riepilogo).serialize(),
                type: 'post',
                success:function(data){
                    alert('Mail caricate');
                },
                error:function(){
                    alert('errore');
                }
            }); 
}

function campagna_carica_telemarketing(campagna_recordid)
{
    var form_riepilogo=$('#form_riepilogo');
   var url=controller_url+'campagna_carica_telemarketing/'+campagna_recordid;
    $.ajax({
        
                url: url,
                data:$(form_riepilogo).serialize(),
                type: 'post',
                success:function(data){
                    alert('Telemarketing caricati');
                },
                error:function(){
                    alert('errore');
                }
            }); 
}

function conferma_queued_mail(el,recordid)
{ 
    var url=controller_url+'conferma_queued_mail/'+recordid;
    $.ajax({
        
                url: url,
                dataType:'html',
                success:function(data){
                    if(data=='protezionecliente')
                    {
                        chiudi_scheda(el);
                        alert('Mail inviata')
                        var confirmation=confirm('Inviare mail di protezione cliente?')
                        if(confirmation)
                        {
                            genera_mail_protezionecliente_from_queued_mail(recordid);
                        } 
                    }
                    if(data=='nocontatto')
                    {
                        alert('Manca il destinatario');
                    }
                    if(data=='noemail')
                    {
                        alert('Il destinatario non ha una email in anagrafica');
                    }
                    if(data=='')
                    {
                        chiudi_scheda(el);
                        alert('Mail inviata');
                    }
                },
                error:function(){
                    alert('errore');
                }
            });
}

function conferma_queued_mail_prospetto_immobile(el,recordid,recordid_immobile)
{
    salva_record(el,'chiudi');
    var url=controller_url+'conferma_queued_mail/'+recordid;
    $.ajax({
        
                url: url,
                type: 'url',
                success:function(data){
                    alert('Mail inviata');
                },
                error:function(){
                    alert('errore');
                }
            });
}

function annulla_queued_mail(el,recordid)
{
    var scheda=$(el).closest('.scheda');
    var scheda_container=$(el).closest('.scheda_container');
    var scheda_container_id=$(scheda_container).attr('id');
    var url=controller_url+'annulla_queued_mail/'+recordid;
    $.ajax({
        
                url: url,
                type: 'url',
                success:function(data){
                    chiudi_scheda(el)
                },
                error:function(){
                    alert('errore');
                }
            });
}


function insert_linked(el)
{
    console.info('fun insert_linked');
    var opened=$(el).closest('.tablelabel').data('opened');
    if(!opened)
    {
        labelclick(el);
    }
    
    var label_table=$(el).closest('.label_table');
    setTimeout(function(){
        $(label_table).find('.new_linked').click();
    },500);
    
    //apri_scheda_record(this,'timesheet','null','popup','standard_dati','linked_table','inserimento')
}


function toggle_sublabel(el)
{
    var block_dati_labels_container=$(el).closest('.block_dati_labels_container');
    var block_dati_labels_container_height=$(block_dati_labels_container).height();
    $(block_dati_labels_container).scrollTo(el,300,
    {
        offset: -(block_dati_labels_container_height/3),
      }
    );
    $(el).next().toggle()
}


function show_hidden_btn(el)
{
    var menu=$(el).closest('.menu_bottom');
    $(menu).find('.btn_scritta').show(200);
}


function autobatchimport()
{
    var url=controller_url+'autobatchimport';
    $.ajax({

        url: url,
        type: 'url',
        success:function(data){
            alert('Documenti importati');
        },
        error:function(){
            alert('errore');
        }
    });
}

function selezione_viste_report(el,tableid,reportid)
{
    var selezione_viste_report_popup=$('#selezione_viste_report_popup').bPopup();
    var url=controller_url+'selezione_viste_report/'+tableid+'/'+reportid;
    $.ajax({

        url: url,
        type: 'url',
        success:function(response){
            $(selezione_viste_report_popup).html(response);
        },
        error:function(){
            alert('errore');
        }
    });
}

function elimina_report(el,reportid)
{
    var url=controller_url+'elimina_report/'+reportid;
    $.ajax({

        url: url,
        type: 'url',
        success:function(response){
            alert('report eliminato');
        },
        error:function(){
            alert('errore');
        }
    });
}

function selezione_viste_report_salva(el,tableid,reportid)
{
    var serialized=$(el).closest('form').serializeArray();
    var url=controller_url+'selezione_viste_report_salva/'+reportid;
    $.ajax({

        url: url,
        type: 'post',
        data: serialized,
        success:function(response){
            alert('salvato');
        },
        error:function(){
            alert('errore');
        }
    });
}

function stampa_curriculum(recordid,tipo)
{ 
    if(typeof(tipo)==='undefined')
    {
        tipo='';
    }
    var urlprint=controller_url+"stampa_curriculum/"+ recordid + '/' + tipo;
    console.info(urlprint);
    var urldownload=controller_url+"download_curriculum/";
    $.ajax({
        url: urlprint ,
        dataType:'html',
        cache: false,
        success:function(data){
            console.info(urldownload + data);
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function stampa_curriculum_anonimo(recordid,tipo)
{ 
    if(typeof(tipo)==='undefined')
    {
        tipo='';
    }
    var urlprint=controller_url+"stampa_curriculum_anonimo/"+ recordid + '/' + tipo;
    var urldownload=controller_url+"download_curriculum/";
    $.ajax({
        url: urlprint ,
        dataType:'html',
        cache: false,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function genera_acconto_step1(el,recordid)
{
   
    
    $.ajax({
        url: controller_url+'genera_acconto_step1/'+recordid,
        dataType:'html',
        success:function(data){   
            
             bPopup_generico=$('#sPopup').bPopup();
             $('#sPopup').html(data);
        },
        error:function(){
            alert('errore');
            $('#dettaglio_record').html('fallimento');
        }
    });
}

function genera_acconto_step2(el)
{
    var serialized=$('#genera_acconto_step1').find('form').serializeArray();
    $.ajax({
        url: controller_url+'genera_acconto_step2/',
        type:'post',
        data: serialized,
        success:function(recordid_fattura){  
                bPopup_generico.close();
                stampa_fattura(el,recordid_fattura);
        },
        error:function(){
            alert('errore');
            $('#dettaglio_record').html('fallimento');
        }
    });
}

function genera_notaprofessionale_step1(el,recordid)
{
   
    
    $.ajax({
        url: controller_url+'genera_notaprofessionale_step1/'+recordid,
        dataType:'html',
        success:function(data){   
            
             bPopup_generico=$('#sPopup').bPopup();
             $('#sPopup').html(data);
        },
        error:function(){
            alert('errore');
            $('#dettaglio_record').html('fallimento');
        }
    });
}

function genera_notaprofessionale_step2(el)
{
    var serialized=$('#genera_notaprofessionale_step1').find('form').serializeArray();
    $.ajax({
        url: controller_url+'genera_notaprofessionale_step2/',
        type:'post',
        data: serialized,
        success:function(recordid_fattura){ 
             bPopup_generico.close();
             stampa_fattura(el,recordid_fattura);
        },
        error:function(){
            alert('errore');
            $('#dettaglio_record').html('fallimento');
        }
    });
}

function stampa_fattura(el,recordid)
{
    $.ajax({
        url: controller_url+"/stampa_fattura/"+ recordid,
        success:function(data){
            window.location.href = controller_url+"/download_stampa/"+data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function stampa_notaprofessionale(el,recordid)
{
    var urlprint=controller_url+"/stampa_notaprofessionale/"+ recordid;
    var urldownload=controller_url+"/download_curriculum/";
    $.ajax({
        url: urlprint ,
        success:function(data){
            //alert(urldownload + data);
            window.location.href = "http://localhost:8822/jdocweb2/index.php/sys_viewcontroller/download_notaprofessionale/NotaProfessionale.docx";
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function stampa_acconto(recordid)
{
    var urlprint=controller_url+"/stampa_curriculum/"+ recordid + '/' + tipo;
    var urldownload=controller_url+"/download_curriculum/";
    $.ajax({
        url: urlprint ,
        success:function(data){
            
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function stampa_letteraaccompagnamento(el,recordid)
{
        var urlprint=controller_url+"/stampa_letteraaccompagnamento/"+ recordid;
        $.ajax({
            url: urlprint,
            success:function(data){
                window.location.href = controller_url+"/download_stampa/"+data;
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });
}

function test_phpword(el)
{
    var urlprint=controller_url+"/test_phpword/";
    var urldownload=controller_url+"/download_test_phpword/";
    $.ajax({
        url: urlprint ,
        success:function(data){
            window.location.href = urldownload + data;
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function genera_rapportidilavoro(el)
{
    var url=controller_url+"/genera_rapportidilavoro/";
    $.ajax({
        url: url ,
        success:function(data){
            alert('Rapporti di lavoro generati');
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function run_script(funzione)
{
    $('#centrale').html('Inizio esecuzione: '+funzione+'<br/>');
    $.ajax({
        url: controller_url+"/"+funzione,
        success:function(data){
            $('#centrale').append(data);
            $('#centrale').append('<br/>'+'Fine esecuzione: '+funzione);
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}

function run_function(el,funzione,recordid)
{
    if(typeof(recordid)==='undefined')
    {
        recordid='';
    }
    var url=controller_url+"/"+funzione+"/"+recordid;
    $.ajax({
        url: controller_url+"/"+funzione+"/"+recordid,
        success:function(data){
            alert('COMPLETATO');
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}


function rendi_pubblico(el,recordid_proposta)
{
    var url=controller_url+"/rendi_pubblico/"+recordid_proposta;
    $.ajax({
        url: url ,
        success:function(data){
            alert('Candidato proposto reso pubblico');
        },
        error:function(){
            alert("ERRORE RICHIESTA AJAX");
        }
    });
}


function smartsearch(el)
{

    $.ajax( {
        url: controller_url+'ajax_smartsearch/',
        type: "POST",
        data: $(el).closest('form').serializeArray(),
        success: function( response ) {
            $('.scheda_dati_ricerca').find('#query').val(response);
            refresh_risultati_ricerca();
            $('#custom_3p_presenzemensili_alias').html("MOSTRA ALIAS");
            $('#custom_3p_presenzemensili_alias').data('mostra_alias',false);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function customsearch(el)
{
    var view_selected=$('.view_selected');
    var view_selected_id=$(view_selected).data('test');
    var serialized=$(el).closest('form').serializeArray();
    serialized.push({name: 'view_selected_id', value: view_selected_id});
    $.ajax( {
        url: controller_url+'ajax_customsearch/',
        type: "POST",
        data: serialized,
        success: function( response ) {
            $('.scheda_dati_ricerca').find('#query').val(response);
            refresh_risultati_ricerca();
        },
        error:function(){
            alert('errore');
        }
    } ); 
}


function ajax_generate_temp_control()
{
    $.ajax({
        dataType: "html",
        url: controller_url + '/generate_temp_control/',
        data: $('#form_riepilogo').serialize(),
        success:function(data){
            alert('generati');
        },
        error:function()
        {
            alert("ERRORE RICHIESTA AJAX");
        }
    });

}

function ricalcola_tempcontrol(el)
{
    activetab_id=$('#tabs_risultati_ricerca').find("[aria-selected='true']").attr('aria-controls');
    $('#'+activetab_id).html('Ricalcolo');
    $.ajax( {
        url: controller_url+'ajax_ricalcola_tempcontrol/',
        type: "POST",
        data: $(el).closest('form').serializeArray(),
        success: function( response ) {
            $('.scheda_dati_ricerca').find('#query').val(response);
            customsearch(el);
        },
        error:function(){
            alert('errore');
        }
    } );
}

function ricalcola_listaclienti(el)
{
    activetab_id=$('#tabs_risultati_ricerca').find("[aria-selected='true']").attr('aria-controls');
    $('#'+activetab_id).html('Ricalcolo');
    $.ajax( {
        url: controller_url+'ajax_ricalcola_listaclienti/',
        type: "POST",
        data: $(el).closest('form').serializeArray(),
        success: function( response ) {
            $('.scheda_dati_ricerca').find('#query').val(response);
            customsearch(el);
        },
        error:function(){
            alert('errore');
        }
    } );
}


function ricalcola_carenze(el)
{
    activetab_id=$('#tabs_risultati_ricerca').find("[aria-selected='true']").attr('aria-controls');
    $('#'+activetab_id).html('Ricalcolo');
    $.ajax( {
        url: controller_url+'ajax_ricalcola_carenze/',
        type: "POST",
        data: $(el).closest('form').serializeArray(),
        success: function( response ) {
            alert('Carenze ricalcolate');
            refresh_risultati_ricerca();
        },
        error:function(){
            alert('errore');
        }
    } );
}


//CUSTOM ABOUT-X

function collega_record(el,recordid)
{
    var curval=$('#recordid_to_link').val();
    $('#recordid_to_link').val(curval+';'+recordid);
    if(curval=='')
    {
        alert('In lista per collegarci altre schede');
    }
    else
    {
        alert('In lista per essere collegato alla prima scheda');
    }
    
}
function conferma_collega_record()
{
    var confirmation=confirm('Sicuro di voler collegare queste schede?')
    if(confirmation)
    {
        var serialized=[];
        serialized.push({name: 'recordid_to_link', value: $('#recordid_to_link').val()});
        var url=controller_url + '/conferma_collega_record/' ;
        $.ajax( {
            type: "POST",
            url: url,
            data: serialized,
            success: function( response ) {
                alert('Collegati! (spero)');
            },
            error:function(){
                alert('errore');
            }
        })
    }
}

function calcola_media_contratti_nuova(el,recordid_contratto)
{
   alert('Aggiornamento medie in esecuzione');
    $.ajax( {
        url: controller_url+'calcola_media_contratti_nuova/'+recordid_contratto,
        dataType:'html',
        success: function( response ) {
            alert('Medie contratti aggiornate');
        },
        error:function(){
            alert('errore');
        }
    } );  
}


function calcola_media_contratto_v2021(el,recordid_contratto)
{
    $('.bPopup_generico_small').html('attendere, calcolo in corso');
    bPopup_generico_small = $('.bPopup_generico_small').bPopup();
    $.ajax( {
        url: controller_url+'calcola_media_contratto_v2021/'+recordid_contratto,
        dataType:'html',
        success: function( response ) {
            
             $('.bPopup_generico_small').html(response);
        },
        error:function(){
            alert('errore');
        }
    } );  
}

function calcola_media_contratto_v2021_2020(el,recordid_contratto)
{
    $('.bPopup_generico_small').html('attendere, calcolo in corso');
    bPopup_generico_small = $('.bPopup_generico_small').bPopup();
    $.ajax( {
        url: controller_url+'calcola_media_contratto_v2021_2020/'+recordid_contratto,
        dataType:'html',
        success: function( response ) {
            
             $('.bPopup_generico_small').html(response);
        },
        error:function(){
            alert('errore');
        }
    } );  
}

function custom_3p_aggiornamedie()
{
    alert('Aggiornamento medie in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiornamedie/',
        dataType:'html',
        success: function( response ) {
            alert('Medie contratti aggiornate');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function custom_3p_aggiornamedie_contratto(el,recordid_contratto)
{
    alert('Aggiornamento medie in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiornamedie/'+recordid_contratto,
        dataType:'html',
        success: function( response ) {
            alert('Medie contratto aggiornate');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function custom_3p_aggiornamedie_contratto_nuovo(el,recordid_contratto)
{
    $('.bPopup_generico').html('attendere, calcolo in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        url: controller_url+'calcola_media_contratto/'+recordid_contratto,
        dataType:'html',
        success: function( response ) {
            $('.bPopup_generico').html(response);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function custom_3p_aggiornaPresenzemesecorrente()
{
    alert('Aggiornamento delle presenze mensili del mese corrente in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiornaPresenzemesecorrente/',
        dataType:'html',
        success: function( response ) {
            alert('Presenze mensili del mese corrente aggiornate');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function custom_3p_aggiornaPresenzemesevisualizzato()
{
    
    var presenze_mese=$('#presenze_mese').val();
    var presenze_anno=$('#presenze_anno').val();

    if((presenze_mese!='')&&(presenze_anno!=''))
    {
        alert('Aggiornamento delle presenze mensili del mese visualizzato in esecuzione');
        $.ajax( {
            url: controller_url+'custom_3p_aggiornaPresenzemesevisualizzato/'+presenze_mese+'/'+presenze_anno,
            dataType:'html',
            success: function( response ) {
                alert('Presenze mensili del mese visualizzato aggiornate');
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    else
    {
        alert('Selezionare mese e anno per utilizzare questa funzionalità');
    }
    
}

function custom_3p_aggiornaNmesecorrente()
{
    alert('Aggiornamento della numerazione dipendenti del mese corrente in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiornaNmesecorrente/',
        dataType:'html',
        success: function( response ) {
            alert('Numerazione dipendenti del mese corrente aggiornata');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}	

function custom_3p_aggiornaNdainizioanno()
{
    alert('Aggiornamento della numerazione dipendenti da inizio anno in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiornaNdainizioanno/',
        dataType:'html',
        success: function( response ) {
            alert('Numerazione dipendenti da inizio anno aggiornata');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function custom_3p_aggiorna3mesi()
{
    alert('Aggiornamento 3 mesi data scadenza in esecuzione');
    $.ajax( {
        url: controller_url+'custom_3p_aggiorna3mesi/',
        dataType:'html',
        success: function( response ) {
            alert('3 mesi data scadenza aggiornata');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function correggi_rotazione_foto(recordid)
{
    $.ajax( {
        url: controller_url+'correggi_rotazione_foto/'+recordid,
        dataType:'html',
        success: function( response ) {
            console.info(response);
            alert('Foto corrette');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}


function open_report_pdf(filename)
{
    $('.bPopup_generico').html('<h4>Attendere, generazione report in corso...</h4>');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        url: controller_url+'genera_report_pdf/'+filename,
        dataType:'html',
        success: function( response ) {
            $('.bPopup_generico').html('<h4>Report generato, download in corso...</h4>');
            setTimeout(function(){
                bPopup_generico.close();
            },200);
            var url=jdocserver_url+'/export/'+filename;
            $('#content_container').html('');
            //alert(url+'?V='+Math.floor(Math.random() * 1000000));
            $('#content_container').html('<iframe  src="'+url+'?V='+Math.floor(Math.random() * 10000)+'" style="height: 100%;width: 100%" ></iframe>');
            $('#content_container').show();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
    
    
    //window.location.href = urldownload;
}

function genera_stampa(nomestampa)
{
    $('.bPopup_generico').html('<h4>Attendere, generazione report in corso...</h4>');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        url: controller_url+'genera_stampa_'+nomestampa,
        dataType:'html',
        success: function( response ) {
            $('.bPopup_generico').html(response);            
        },
        error:function(){
            alert('errore');
        }
    } ); 
    
    
    //window.location.href = urldownload;
}

function genera_stampa_reportCCL(recordid)
{
    $('.bPopup_generico').html('<h4>Attendere, generazione report in corso...</h4>');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        url: controller_url+'genera_stampa_reportCCL/'+recordid,
        dataType:'html',
        success: function( response ) {
            $('.bPopup_generico').html(response);            
        },
        error:function(){
            alert('errore');
        }
    } ); 
    
    
    //window.location.href = urldownload;
}

function calcola_mediaperiodo(el)
{
    $.ajax( {
        url: controller_url+'ajax_calcola_mediaperiodo/',
        type: "POST",
        data: $(el).closest('form').serializeArray(),
        success: function( response ) {
            alert(response);
            $('#mediaperiodo').html(response);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}



function reset_access(el)
{
    alert('Riavvio del sistema in corso');
    $.ajax( {
        url: controller_url+'ajax_reset_access/',
        dataType: "html",
        success: function( response ) {
            alert('Sistema riavviato');
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function invia_curriculum_anonimoBAK(el)
{
    var email=prompt("Mail a cui inviare il curriculum anonimo");
    if((email!=null)&&(email!=''))
    {
        
        var recordid_candidato=$(el).closest('.scheda_record').data('recordid');
        
        if(typeof(tipo)==='undefined')
        {
            tipo='';
        }
        var urlprint=controller_url+"stampa_curriculum_anonimo/"+ recordid_candidato + '/' + tipo;
        $.ajax({
            url: urlprint ,
            dataType:'html',
            cache: false,
            success:function(data){
                
                var serialized_data=[];
                serialized_data.push({name: 'recordid_candidato', value: recordid_candidato});
                serialized_data.push({name: 'nome_file', value: data});
                serialized_data.push({name: 'email', value: email});

                $.ajax({
                    url: controller_url+'invia_curriculum/anonimo',
                    data: serialized_data,
                    type: 'post',
                    success:function(data){
                        alert('curriculum inviato');

                    },
                    error:function(){
                        alert('errore');
                    }
                });
                
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });

        

    }
}

function invia_curriculum_da_stato_candidato(el,tipo)
{
    var email_azienda='';
    var recordid_azienda='';
    var recordid_candidato='';
    
    var recordid_visualizzazioni=$(el).closest('.scheda_record').data('recordid');
    
    $.ajax({
            url: controller_url+"custom_1824_get_dati_azienda_from_visualizzazioni/"+ recordid_visualizzazioni ,
            dataType:'html',
            cache: false,
            success:function(data){
                var splitted = data.split(";");
                recordid_azienda=splitted[0];
                email_azienda=splitted[1];
                $.ajax({
                    url: controller_url+"custom_1824_get_recordid_candidato_from_visualizzazioni/"+ recordid_visualizzazioni ,
                    dataType:'html',
                    cache: false,
                    success:function(data){
                        recordid_candidato=data;
                        
                        invia_curriculum(el,tipo,recordid_candidato,email_azienda,recordid_azienda,recordid_visualizzazioni);
                    },
                    error:function(){
                        alert("ERRORE RICHIESTA AJAX");
                    }
                });
                
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });
    
    
}

function invia_curriculum_da_candidato(el,tipo)
{
    var recordid_candidato=$(el).closest('.scheda_record').data('recordid');
    invia_curriculum(el,tipo,recordid_candidato);
}


function invia_curriculum(el,tipo,recordid_candidato,email,recordid_azienda,recordid_visualizzazioni)
{
    if(typeof recordid_azienda==='undefined')
    {
        recordid_azienda='';
    }
    if(typeof recordid_visualizzazioni==='undefined')
    {
        recordid_visualizzazioni='';
    }
    if(typeof email==='undefined')
    {
       var email=prompt("Mail a cui inviare il curriculum anonimo");
    }
    
        
        $('#prestampa').html('attendere, generazione in corso');
        prestampa_popup=$('#prestampa').bPopup();
        var url='';
        if(tipo=='anonimo')
        {
            url=controller_url+"stampa_curriculum_anonimo/"+ recordid_candidato
        }
        if(tipo=='completo')
        {
            url=controller_url+"stampa_curriculum/"+ recordid_candidato
        }
        $.ajax({
            url: url ,
            dataType:'html',
            cache: false,
            success:function(data){
                
                var serialized_data=[];
                serialized_data.push({name: 'recordid_candidato', value: recordid_candidato});
                serialized_data.push({name: 'nome_file', value: data});
                serialized_data.push({name: 'email', value: email});
                serialized_data.push({name: 'recordid_azienda', value: recordid_azienda});
                serialized_data.push({name: 'recordid_visualizzazioni', value: recordid_visualizzazioni});

                $.ajax({
                    url: controller_url+'invia_curriculum/',
                    data: serialized_data,
                    type: 'post',
                    success:function(response){
                        console.info(response);
                        prestampa_popup.close();
                        apri_scheda_record(this,'mail_queue',response,'popup','allargata','records_linkedmaster','modifica');

                    },
                    error:function(){
                        alert('errore');
                    }
                });
                
            },
            error:function(){
                alert("ERRORE RICHIESTA AJAX");
            }
        });
        
        
    
}

function ajax_passa_a_contratto_missione(el,recordid_contrattofornitura)
{
     $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_passa_a_contratto_missione/'+recordid_contrattofornitura,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_compilazione_contratto_missione(el,recordid_contratto)
{ 
    //$('#prestampa').html('attendere, generazione in corso');
    //prestampa_popup=$('#prestampa').bPopup();
    console.info(recordid_contratto);
    $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_compilazione_contratto_missione/'+recordid_contratto,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_calendarioaziendale(el,recordid_azienda,anno,recordid_ccl)
{
    $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_calendarioaziendale/'+recordid_azienda+'/'+anno+'/'+recordid_ccl,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_salva_calendarioaziendale(el,recordid_azienda,anno,recordid_ccl)
{
    
    var url=controller_url+'ajax_salva_calendarioaziendale/';
        $.ajax( {
            type: "POST",
            url: url,
            data: $('#form_calendarioaziendale').serialize(),
            success: function( response ) {
                bPopup_generico.close();
                ajax_calendarioaziendale(el,recordid_azienda,anno,recordid_ccl);
            },
            error:function(){
                alert('errore');
            }
        } ); 
}

function ajax_cancella_calendarioaziendale(el,recordid_azienda,anno,recordid_ccl)
{
    var confirmation=confirm('Sicuro di voler cancellare questo calendario aziendale?');
    if(confirmation)
    {
        $.ajax( {
            dataType: 'html',
            url: controller_url + '/ajax_cancella_calendarioaziendale/'+recordid_azienda+'/'+anno+'/'+recordid_ccl,
            success: function( response ) {
                alert('Calendario aziendale cancellato');
                bPopup_generico.close();
                ajax_calendarioaziendale(el,recordid_azienda,anno);
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
}

function ajax_stampa_pdf_contratto_missione(el,recordid_contratto,intestazione)
{ 
    bPopup_generico.close();
    $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_stampa_pdf_contratto_missione/'+recordid_contratto+'/'+intestazione,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_modifica_contrattuale(el,recordid_contratto)
{
    var confirmation=confirm('Sicuro di voler fare una modifica contrattuale?');
    if(confirmation)
    {
        var url=controller_url+'ajax_modifica_contrattuale/'+recordid_contratto;
        $.ajax({
                url : url,
                success : function (response) {
                    var recordid=response;
                    ajax_compilazione_contratto_missione(this,recordid);
                    
                },
                error : function () {
                    alert("Errore");
                }
            });
    }
}

function ajax_passa_a_contratto_fornitura(el,recordid_contrattofornitura)
{
     $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_passa_a_contratto_fornitura/'+recordid_contrattofornitura,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_compilazione_contratto_fornitura(el,recordid_contratto)
{ 
    //$('#prestampa').html('attendere, generazione in corso');
    //prestampa_popup=$('#prestampa').bPopup();
    $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico.close();
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_compilazione_contratto_fornitura/'+recordid_contratto,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function ajax_stampa_pdf_contratto_fornitura(el,recordid_contratto,intestazione)
{ 
    bPopup_generico.close();
    $('.bPopup_generico').html('attendere, generazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_stampa_pdf_contratto_fornitura/'+recordid_contratto+'/'+intestazione,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico = $('.bPopup_generico').bPopup();
            
        },
        error:function(){
            alert('errore');
        }
    } ); 
}


function export_migrazione(el,tableid)
{
    var query=$('#query').val();
    var serialized=[];
    serialized.push({name: 'query', value: query});
    var url=controller_url + '/export_migrazione/' + tableid;
    $.ajax( {
        type: "POST",
        url: url,
        data: serialized,
        success: function( response ) {
            alert('Esportati');
        }
    }) 
}

function report_ccl(el,recordid)
{ 
    var listaccl_anno=$('#listaccl_anno').val();

    $('.bPopup_generico').html('attendere, apertura in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_report_ccl/'+recordid+'/'+listaccl_anno,
        success: function( response ) {
            $('.bPopup_generico').html(response);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

function rinnova_ccl(el,recordid)
{ 
    var confirmation=confirm('Sicuro di voler rinnovare il CCL?')
    if(confirmation)
    {
    var url=controller_url+'ajax_rinnova_ccl/'+recordid;
    $.ajax({
                url : url,
                success : function (response) {
                    recordid=response;
                    alert('CCL rinnovato e in stato da verificare'); 
                    var menu_top=$(risultati_ricerca).find('.menu_top');
                    apri_scheda_record(menu_top,'ccl',recordid,'right','standard_dati','risultati_ricerca','scheda');  
                    refresh_risultati_ricerca();
                    
                },
                error : function () {
                    alert("Errore");
                }
            });
        }
}

function approva_ccl(el,recordid)
{
    var confirmation=confirm('Sicuro di voler approvare il CCL?')
    if(confirmation)
    {
        var url=controller_url+'ajax_approva_ccl/'+recordid;
        $.ajax({
            url : url,
            success : function (response) {
                recordid=response;
                alert('CCL approvato'); 
                var menu_top=$(risultati_ricerca).find('.menu_top');
                apri_scheda_record(menu_top,'ccl',recordid,'right','standard_dati','risultati_ricerca','scheda');  
                refresh_risultati_ricerca();

            },
            error : function () {
                alert("Errore");
            }
        });
    }
}


function sincronizza_api(funzione)
{ 

    $('.bPopup_generico').html('attendere, sincronizzazione in corso');
    bPopup_generico = $('.bPopup_generico').bPopup();
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/' + funzione,
        success: function( response ) {
            $('.bPopup_generico').html(response);
        },
        error:function(){
            alert('errore');
        }
    } ); 
}


function custom3p_prepara_notifica_email(el,recordid,funzione)
{
    $.ajax( {
        dataType: 'html',
        url: controller_url + '/ajax_custom3p_prepara_notifica_email/' + recordid + '/' + funzione,
        success: function( response ) {
            $('.bPopup_generico').html(response);
            bPopup_generico=$('.bPopup_generico').bPopup();
        },
        error:function(){
            alert('errore');
        }
    } ); 
}


//custom 3p
function apri_jobdescription(el,recordid_richiestaricercapersonale)
    {
        
       
                bPopup_generico=$('.bPopup_generico').bPopup();
                $.ajax({

                    url: controller_url+"ajax_load_custom_3p_jobdescription/"+recordid_richiestaricercapersonale,
                    dataType:'html',
                    success:function(data){
                        $('.bPopup_generico').html(data);
                    },
                    error:function(){
                        alert('errore');
                    }
                });

            
        
    }



