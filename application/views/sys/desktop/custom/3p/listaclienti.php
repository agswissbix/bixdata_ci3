<script type="text/javascript">
    $( function() {
    $( "#tabs" ).tabs();
  } );
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
    td{
        padding: 5px;
    }
    th{
        padding: 5px;
        text-align: left;
    }
</style>
<br/>

<div id="tabs" style="padding-left: 20px;overflow: scroll;height: 85%;width: 80%;margin: auto;background-color: white;padding: 10px;">
  <ul>
    <li><a href="#clienti">Clienti</a></li>
    <li><a href="#clientiinuscita">Clienti in uscita</a></li>
    <li><a href="#exclienti">Ex clienti</a></li>
  </ul>
  <div id="clienti">
      <?=$clienti?>
  </div>
  <div id="clientiinuscita">
      <?=$clientiinuscita?>
  </div>
  <div id="exclienti">
      
      <?=$exclienti?>
        
  </div>
</div>
<div >


