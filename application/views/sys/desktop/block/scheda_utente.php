<style type="text/css">
    #scheda_utente th{
        font-weight: bold;
        padding: 5px;
        text-align: left;
    }
    #scheda_utente td{
        padding: 5px;
    }
    
    #scheda_utente td{
        padding: 5px;
    }
    
    
</style>
<script type="text/javascript">
    function salva_scheda_utente(el)
    {
        var userid=<?=$userid?>;
        $.ajax( {
            type: "POST",
            url: controller_url + '/salva_scheda_utente/'+userid,
            data: $('#form_scheda_utente').serialize(),
            success: function( response ) {
                alert('salvato');
            },
            error:function(){
                alert('errore');
            }
        } ); 
    }
    function elimina_utente(el)
    {
        var userid=<?=$userid?>;
        var confirmation=confirm("Sicuro di voler eliminare l'utente?")
            

        if(confirmation)
        {
            $.ajax( {
            type: "POST",
            url: controller_url + '/elimina_utente/'+userid,
            data: $('#form_scheda_utente').serialize(),
            success: function( response ) {
                alert('Eliminato');
            },
            error:function(){
                alert('errore');
            }
            } ); 
        }
    }
</script>
<div id="scheda_utente" style="background-color: white">
    <div class="btn_scritta"  onclick="salva_scheda_utente(this)">Salva</div>
    <div class="btn_scritta"  onclick="elimina_utente(this)">Elimina</div>
    <div style="width: 100%;height: 90%;overflow: scroll">
        <form id="form_scheda_utente">
            <input type="hidden" name="newuser" value="<?=$newuser?>">
            <br/>
            <table id="scheda_utente_dati">
                <tr>
                    <td style="font-weight: bold">Utente</td>
                    <?php
                    if($newuser)
                    {
                    ?>
                    <td><input type="text" name="user[username]" value=""></td>
                    <?php
                    }
                    else
                    {
                    ?>
                        <td><?=$user['username']?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td style="font-weight: bold">Nome</td>
                    <td><input type="text" name="user[firstname]" value="<?=$user['firstname']?>"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Cognome</td>
                    <td><input type="text" name="user[lastname]" value="<?=$user['lastname']?>"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Password</td>
                    <td><input type="password" name="user[password]" value="<?=$user['password']?>"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">email</td>
                    <td><input type="text" name="user[email]" value="<?=$user['email']?>"></td>
                </tr>

            </table>
            <br/>
            <br/><br/>
        <table id="scheda_utente_permessi">
            <thead>
            <th>Archivio</th>
            <th>Visualizzare</th>
            <th>Modificare</th>
            <th>Eliminare</th>
            <th>Menu</th>
            <th>Lista risultati custom</th>
            <th>Scheda record custom</th>

            </thead>
        <?php
        foreach ($tables as $key => $table) {
            $tableid=$table['id'];
        ?>

            <tr style="text-align: center">
            <td><?=$table['description']?></td>
            <td>
                <?php
                $checked='';
                if($table['settings']['hidden']=='false')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][hidden]" value='true'>
                <input type="checkbox" name="tables_settings[<?=$tableid?>][hidden]" <?=$checked?> value="false">
            </td>
            <td>
                <?php
                $checked='';
                if($table['settings']['edit']=='true')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][edit]" value='false'>
                <input type="checkbox" name="tables_settings[<?=$tableid?>][edit]" <?=$checked?> value="true">
            </td>
            <td>
                <?php
                $checked='';
                if($table['settings']['delete']=='true')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][delete]" value='false'>
                <input type="checkbox" <?=$checked?> name="tables_settings[<?=$tableid?>][delete]" value="true">
            </td>
            <td>
                <?php
                $checked='';
                if($table['settings']['menu']=='true')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][menu]" value='false'>
                <input type="checkbox" <?=$checked?> name="tables_settings[<?=$tableid?>][menu]" value="true">
            </td>
            <td>
                <?php
                $checked='';
                if($table['settings']['customview_list']=='true')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][customview_list]" value='false'>
                <input type="checkbox" <?=$checked?> name="tables_settings[<?=$tableid?>][customview_list]" value="true">
            </td>
            <td>
                <?php
                $checked='';
                if($table['settings']['customview_card']=='true')
                {
                    $checked='checked';
                }
                ?>
                <input type='hidden'  name="tables_settings[<?=$tableid?>][customview_card]" value='false'>
                <input type="checkbox" <?=$checked?> name="tables_settings[<?=$tableid?>][customview_card]" value="true">
            </td>
        </tr>
        <?php
        }
        ?>
        </table>
            <br/><br/><br/>
        <table>
            <thead>
                <th>Report</th>
                <th>Visualizzare</th>
            </thead>
            <tbody>
                    <tr>
                        <td>Elenco telefonico</td>
                        <td>
                            <?php
                            $checked='';
                            if($user_settings['elenco_telefonico']=='true')
                            {
                                $checked='checked';
                            }
                            ?>
                            <input type='hidden'  name="user_settings[elenco_telefonico]" value='false'>
                            <input type="checkbox" <?=$checked?>  name="user_settings[elenco_telefonico]" value="true">
                        </td>
                    </tr>
                    <tr>
                        <td>Lista disponibilità</td>
                        <td>
                            <?php
                            $checked='';
                            if($user_settings['lista_disponibilita']=='true')
                            {
                                $checked='checked';
                            }
                            ?>
                            <input type='hidden'  name="user_settings[lista_disponibilita]" value='false'>
                            <input type="checkbox" <?=$checked?>  name="user_settings[lista_disponibilita]" value="true">
                        </td>
                    </tr>
                    <tr>
                        <td>Dipendenti da chiudere</td>
                        <td>
                            <?php
                            $checked='';
                            if($user_settings['dipendenti_da_chiudere']=='true')
                            {
                                $checked='checked';
                            }
                            ?>
                            <input type='hidden' name="user_settings[dipendenti_da_chiudere]" value='false'>
                            <input type="checkbox" <?=$checked?> name="user_settings[dipendenti_da_chiudere]" value="true">
                        </td>
                    </tr>
                    <tr>
                        <td>Dipendenti da riaprire</td>
                        <td>
                            <?php
                            $checked='';
                            if($user_settings['dipendenti_da_riaprire']=='true')
                            {
                                $checked='checked';
                            }
                            ?>
                            <input type='hidden'  name="user_settings[dipendenti_da_riaprire]" value='false'>
                            <input type="checkbox" <?=$checked?>  name="user_settings[dipendenti_da_riaprire]" value="true">
                        </td>
                    </tr>
            </tbody>

        </table>
    </form>
    </div>
</div>