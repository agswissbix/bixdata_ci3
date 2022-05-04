

<script type="text/javascript">
    $(document).ready(function(){
        $('#summernote_jobdescription').summernote({
            height: 600,
            toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'fontsize']],
              ['color', ['forecolor','backcolor']],
              ['paragraph',['paragraph','height','ul','ol']],
              ['table',['table']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
          });
          $('#summernote_jobdescription').summernote('code','<?=$jobdescription?>');
          
          $('#summernote_note').summernote({
            height: 600,
            toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'fontsize']],
              ['color', ['forecolor','backcolor']],
              ['paragraph',['paragraph','height','ul','ol']],
              ['table',['table']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
          });
          $('#summernote_note').summernote('code','<?=$note?>');
          
          

    });
    
    function ajax_salva_jobdescription(el)
    {
        var sendresponse=confirm("Sicuro di voler salvare?");
        if(sendresponse==true)
        {

            var serialized=[];
            var htmlcontent_jobdescription=$('#summernote_jobdescription').summernote('code');
            var htmlcontent_note=$('#summernote_note').summernote('code');

            serialized.push({name: 'jobdescription', value: htmlcontent_jobdescription});
            serialized.push({name: 'note', value: htmlcontent_note});
            serialized.push({name: 'recordid_richiestericercapersonale', value: '<?=$recordid_richiestericercapersonale?>'});
            var popup=$(el).closest('.popup');
            $(popup).html('     Salvataggio...');
            var url=controller_url+'salva_custom_3p_jobdescription';
            $.ajax( {
                type: "POST",
                url: url,
                data: serialized,
                success: function( response ) {

                    setTimeout(function(){
                        bPopup_generico.close();
                        var sendresponse=confirm("Vuoi inviare notifica email?");
                        if(sendresponse==true)
                        {
                            if(response=='new')
                            {
                                custom3p_prepara_notifica_email(el,'<?=$recordid_richiestericercapersonale?>','nuovo');
                            }
                            else
                            {
                                custom3p_prepara_notifica_email(el,'<?=$recordid_richiestericercapersonale?>','modificato');
                            }
                        }
                    },1500);

                },
                error:function(){
                    alert('errore');
                }
            } );
        }
    }
</script>


<div id='note' style="">
    <div onclick="chiudi_popup_generico(this)" style="position: absolute;top: 10px;right: 0px;cursor: pointer;font-size: 18px" ><i class="fas fa-times"></i></div>
    <div style="width: 100%;margin-top: 50px;padding-left: 2%;">
        <div style="width: 47%;float: left;">
            <div style="text-align: center;font-weight: bold">JOB DESCRIPTION</div>
            <div id="summernote_jobdescription" ></div>
        </div>
        <div style="width: 47%;float: left;margin-left: 2%">
            <div style="text-align: center;font-weight: bold">NOTE</div>
            <div id="summernote_note" ></div>
        </div>
        
        
        
     
    </div>
    <br/><br/>
    <div class="menu_big menu_bottom" style="position: absolute;bottom: 0px;width: 100%;margin: auto;">
        <div id="btnSalvaContinua" class="btn_scritta"  style="width: 130px;padding: 0px;float: right;margin-right: 50px;margin-bottom: 20px;" onclick="ajax_salva_jobdescription(this)"><i class="fas fa-save"></i>   Salva</div>
    </div>
</div>

