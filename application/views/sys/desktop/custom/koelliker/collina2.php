<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script type="text/javascript">
     // Or with jQuery

 $(document).ready(function(){
    $('select').formSelect();
    $('input.autocomplete').autocomplete({
      data: {
        "Abisi Mark Davis": null,
        "Birk Gregory": null,
        "Beckwith Laube": null,
        "Perrani Camilla":null
      },
      onAutocomplete:function(){
          $('#famigliari').show();
      }
    });
  });
  
  function crea()
  {
        $('#modal1').modal('open');
        var serialized=$('#generazionedocumenti').find("select,textarea,input").serializeArray();
        $.ajax( {
            type: "POST",
            url: '<?=site_url('sys_viewcontroller/custom_easywork_generadocumenti')?>',
            data: serialized,
            success: function( response ) {
            },
            error:function(){
            }
        } );
  }
  
 
</script>

<div class="container" style="max-width: none !important">   
    <nav>
         <div class="nav-wrapper" style="background-color: #53bac8 !important;padding-left: 20px">
            <a href="#" class="brand-logo">Caricamento dati</a>
        </div>
    </nav>
    <div id="generazionedocumenti"  class="row">
        <div class="col s9">
            <iframe src="<?=base_url()."assets/documents/DOC.pdf"?>" width="100%" height="80%"></iframe>
        </div>
        <div class="card col s3" style="padding: 20px">
            <div class="input-field col s12">
                <select>
                  <option value="" >Tipo documento</option>
                  <option value="1">Cassa malati</option>
                  <option value="2">Certificato di matrimonio</option>
                  <option value="3">Certificato di nascita</option>
                  <option value="3">Contratto di affitto</option>
                  <option value="3">Documento ID/Passaporto</option>
                  <option value="3" selected>Notifica di arrivo</option>
                  <option value="3">Permesso</option>
                  <option value="3">Tessera assicurazione malati</option>
                </select>
            <label>Materialize Select</label>
            </div>
             <div class="input-field col s12">
                 <input type="text" id="autocomplete-input" class="autocomplete" value="Abisi Mark Davis">
                <label for="autocomplete-input">Capofamiglia</label>
              </div>
            <div id="famigliari" class="input-field col s12" style="">
                <p>
                <label>
                  <input type="checkbox" />
                  <span>Ella Grace Abisi</span>
                </label>
              </p>
              <p>
                <label>
                  <input type="checkbox" />
                  <span>Katlyn Anne Abisi</span>
                </label>
              </p>
            </div>
            <div class="input-field col s12">
                <a class="waves-effect waves-light btn" href="<?=base_url()."/index.php/sys_viewcontroller/custom_koelliker_collina2"?>">Salva e prosegui<i class="material-icons right">send</i> </a>
                
            </div>
        </div>
    </div>
</div>




