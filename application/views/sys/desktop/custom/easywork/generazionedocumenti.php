<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script type="text/javascript">
     // Or with jQuery

  $(document).ready(function(){
    $('input.autocomplete').autocomplete({
      data: {
        <?php
        foreach ($candidati as $key => $candidato) {
        ?>
            "<?=$candidato['Cognome'].' '.$candidato['Nome'].'-'.$candidato['ID']?>": null,
        <?php
        }
        ?>

      }
    });
    
    $('.modal').modal();
    $('.datepicker').datepicker({
    format: 'dd/mm/yyyy'
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
  
  function converti()
  {
      $('#modal1').modal('open');
        var serialized=$('#generazionedocumenti').find("select,textarea,input").serializeArray();
        $.ajax( {
            type: "POST",
            url: '<?=site_url('sys_viewcontroller/custom_easywork_convertidocumenti')?>',
            data: serialized,
            success: function( response ) {
            },
            error:function(){
            }
        } );
  }
  
  function creaconverti()
  {
        $('#modal1').modal('open');
        var serialized=$('#generazionedocumenti').find("select,textarea,input").serializeArray();
        $.ajax( {
            type: "POST",
            url: '<?=site_url('sys_viewcontroller/custom_easywork_generadocumenti/true')?>',
            data: serialized,
            success: function( response ) {
            },
            error:function(){
            }
        } );
  }
  
    function creacopertina()
  {
        $('#modal1').modal('open');
        var serialized=$('#generazionedocumenti').find("select,textarea,input").serializeArray();
        $.ajax( {
            type: "POST",
            url: '<?=site_url('sys_viewcontroller/custom_easywork_generacopertina')?>',
            data: serialized,
            success: function( response ) {
            },
            error:function(){
            }
        } );
  }
</script>

<div class="container">   
     <nav>
         <div class="nav-wrapper" style="background-color: #2bbbad !important;padding-left: 20px">
      <a href="#" class="brand-logo">Generazione documenti</a>
    </div>
  </nav>
    <div id="generazionedocumenti"  class="row">
        <div class="col s8">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">account_circle</i>
              <input type="text" id="autocomplete-input" name="candidato" class="autocomplete">
              <label for="autocomplete-input">Candidato</label>
            </div>
          </div>
        <div class="row">
            <div class="col s4">
                <input type="text" placeholder="Data documenti" class="datepicker" name="data">
            </div>
        </div>
          <div class="row">
                <div class="card white darken-1">
                    <div class="card-content black-text">
                        <span class="card-title">Documenti</span>
                        <?php
                        foreach ($documenti as $key => $documento) {
                            $checked='';
                            $docid=$documento['ID'];
                            if( $docid=='1205' ||
                                    $docid=='1206' ||
                                    $docid=='1207' ||
                                    $docid=='1208' ||
                                    $docid=='1209' ||
                                    $docid=='1210' ||
                                    $docid=='1211' ||
                                    $docid=='1212' ||
                                    $docid=='1213' ||
                                    $docid=='1214' ||
                                    $docid=='1215' ||
                                    $docid=='1223' ||
                                    $docid=='1225' 
                                    )
                            {
                            $checked='checked';
                            }
                        ?>
                        <p>
                            <label>
                              <input type="checkbox" name="documenti[]" value="<?=$documento['Documento']?>" <?=$checked?>/>
                              <span><?=$documento['Documento']?></span>
                            </label>
                        </p>
                        <?php   
                        }
                        ?>
                    </div>
                </div>
            </div>  
        </div>
        <div class="col s4" style="padding: 50px;">
            
            <div class="row">
                <div class="btn waves-effect waves-light" onclick="creacopertina()" >Crea Copertina 
                    <i class="material-icons right">send</i>
                </div>
            </div>
            <div class="row">
                <div class="btn waves-effect waves-light" onclick="crea()">Crea Documenti
                    <i class="material-icons right">send</i>
                </div>
            </div>
            <div class="row">
                <div class="btn waves-effect waves-light" onclick="converti()">Converti
                    <i class="material-icons right">send</i>
                </div>
            </div>
            
           
            <div id="modal1" class="modal">
                <div class="modal-content">
                  Processo in corso. Verificare la cartella con i file
                </div>
                <div class="modal-footer">
                  <a href="#!" class="modal-close waves-effect waves-green btn-flat">Chiudi</a>
                </div>
            </div>
        </div>
    </div>
</div>




