<style type="text/css">
    #gestioneutenti .btn_scritta{
        height: 26px;
        line-height: 26px;
        background-color: #54ace0;
        color: white;
        padding: 2px;
        border: medium none;
        border-radius: 2px;
        outline: 0 none;
        padding: 0 2rem;
        text-transform: uppercase;
        vertical-align: middle;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        float: none;
        margin: 5px;
    }
    
    #gestioneutenti .element{
        border: 1px solid #aaaaaa;
        background: #ffffff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x;
        color: #222222;
        margin: 3px;
        padding: 0.4em;
        font-size: 1.4em;
        height: 18px;
        cursor: pointer;
    }
    #gestioneutenti .element:hover{
        background-color: #dedede;
    }
    
    #gestioneutenti .card{
            border-radius: 2px;
            border: 0px;
            background-color: white;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.14);
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 4px;
            padding: 10px;
    }
</style>
<script type="text/javascript">
    function ajax_load_block_scheda_utente(userid)
    {
        $.ajax( {
            dataType: 'html',
            url: controller_url + '/ajax_load_block_scheda_utente/'+userid,
            success: function( response ) {
                $('#scheda_utente').html(response);
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
</script>

<div id="gestioneutenti">
    <div style="width: 30%;height: 100%;float: left">
        <?php
        foreach ($users as $key => $user) {
        ?>
        <div class="element" onclick="ajax_load_block_scheda_utente('<?=$user['id']?>')"><?=$user['username']?></div>
        <?php
        }
        ?>
        <div class="btn_scritta" onclick="ajax_load_block_scheda_utente(null)">Nuovo utente</div>
    </div>

    <div id="scheda_utente" class="card" style="width: 65%;height: 90%;float: left;">
        Dettagli utente
    </div>
</div>