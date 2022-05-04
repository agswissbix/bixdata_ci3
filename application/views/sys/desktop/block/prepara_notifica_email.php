
<link rel="stylesheet" href="<?php echo base_url("/assets/emails-input-master/src/css/app.css") ?>?v=<?=time();?>" />
<link rel="stylesheet" href="<?php echo base_url("/assets/emails-input-master/src/css/lib/email.css") ?>?v=<?=time();?>" />
<script src="<?php echo base_url('/assets/emails-input-master/src/js/lib/utils.js') ?>"></script>
<script src="<?php echo base_url('/assets/emails-input-master/src/js/lib/emails-input.js') ?>"></script>


<style type="text/css">
    .listaemail{
        border-radius:0.25rem !important;
        border:1px solid #c3c2cf !important;
        box-sizing:border-box !important;
        padding:0.375rem !important;
        line-height:1.5rem !important;
        font-size:0.875rem !important;
    }
</style>
<script type="text/javascript">
    
            
    $(document).ready(function(){
        
        
        var autocomplete_azienda_options = [
            
            <?php
            foreach ($users as $key => $user) {
            ?>   
                  { label: "<?=$user['firstname']?> <?=$user['lastname']?>", id: "<?=$user['email']?>", category: "user" },      
            <?php    
            }
            ?>
                        
            <?php
            foreach ($groups as $key => $group) {
            ?>
                { label: "<?=$group['label']?>", id: "<?=$group['email']?>", category: "group" }, 
            <?php    
            }
            ?>


          ];

          $( "#listaemail" ).autocomplete({
                source: autocomplete_azienda_options,
                minLength: 0,
                select: function(event, ui) {
                        var e = ui.item;
                        var category=e.category;
                        if(category=='group')
                        {
                            var str_array = ui.item.id.split(';');
                            for(var i = 0; i < str_array.length; i++) {
                                emailsInput.add(str_array[i]); 
                            }
                            
                        }
                        else
                        {
                            emailsInput.add(e.id); 
                        }
                        
                        $(this).val('');
                        return false; 
                     }
                     
            });

            $( "#listaemail" ).click(function(){
                $( this ).autocomplete( "search", '' );
            });
            
        $('#summernote_testoemail').summernote({
            height: 300,
            toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'fontsize']],
              ['color', ['forecolor','backcolor']],
              ['paragraph',['paragraph','height','ul','ol']],
              ['table',['table']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
            });
            $('#summernote_testoemail').summernote('code','<?=$mailbody?>');
       
       (function(EmailsInput, random) {
        'use strict'

          const inputContainerNode = document.querySelector('#emails-input')
          const emailsInput = EmailsInput(inputContainerNode,{
                    placeholder: 'Aggiungi email...'
              })

          // expose instance for quick access in playground
          window.emailsInput = emailsInput

          document.querySelector('[data-action="add-email"]')
            .addEventListener('click', function() { 
                var email = $("#listaemail").getSelectedItemIndex();
                emailsInput.add(email) 
            })


      }(window.lib.EmailsInput, window.lib.utils.random))
      
      
      
            
    });
    
    
    function custom3p_invia_notifica_email(el,recordid)
{
    var serialized=$('#dati_notifica_email').serializeArray();
    serialized.push({name: 'recordid', value: recordid});
    serialized.push({name: 'mailbody', value: $('#summernote_testoemail').summernote('code')});
    serialized.push({name: 'emails', value: emailsInput.getValue()});
    $.ajax( {
        type: "POST",
        url: controller_url + '/custom3p_invia_notifica_email/',
        data: serialized,
        success: function( response ) {
            alert('Mail inviata');
            bPopup_generico.close();
        },
        error:function(){
            alert('errore');
        }
    } ); 
}

</script>

<div id="prepara_notifica_email" style="width: 100%">
        
    <div>
    <form id="dati_notifica_email" style="width: 100%" >
        <br/>
        
        <div style="width: 80%;margin: auto;">
            <b>Seleziona utente o gruppo</b><br/>
            <input id='listaemail'  class="listaemail" style="width: 30%;" type="search">
        </div>
        <br/>
        <div id="emails-input" style="height: 25px;width: 80%;margin: auto" ></div>
        <br/><br/>
        <div style="width: 80%;margin: auto;">
            <div id="summernote_testoemail" ></div>
        </div>
    </form>
    </div>
    
    <div class="menu_big menu_bottom" style="width: 80%;margin: auto;">
        <br/><br/>
        <div class="btn_scritta" style="margin: auto;float: left" onclick="custom3p_invia_notifica_email(this,'<?=$recordid?>')">Invia</div>
    </div>
    
    
</div>