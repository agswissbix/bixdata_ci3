<div id="block_invio_pushup" class="blocco" style="width: 90%;margin: auto;">
    <form id="form_invio_pushup" name="form_invio_pushup" method="post" >
        <span style="font-weight: bold">Invio Pushup</span><br/>
        <br/><br/>
        Lista destinatari<br/>
        <div style="width: 100%;height: 30%;">
            <textarea id="lista_indirizzi" name="lista_indirizzi" style="width: 100%;height: 100%;border: 1px ssolid #BCBCBC !important"><?php
                $counter=0;
                foreach ($destinatari as $key => $destinatario) {
                    if($destinatario!='')
                    {
                        if($counter!=0)
                            echo ';';
                        echo $destinatario;
                        $counter++;
                    }

                }
                ?></textarea>
        </div>
        <br/><br/>
        <input id="mail_subject" name="mail_subject" type="text" style="width: 100%;border: 0px;border-bottom: 1px solid #BCBCBC;" value="<?=$subject?>">
        <br/><br/>
        <br/>
        <div id="anteprima_pushup_container" style="width: 100%;height: 45%; overflow: scroll;border: 1px solid #BCBCBC">
            <?=$anteprima_pushup?>
        </div>
        <br/>
        <div class="btn_scritta" style="content: auto;" onclick="invio_pushup('<?=$recordid?>')">INVIA</div>
        <!--<div class="btn_scritta" style="content: auto;" onclick="copia_mail_pushup(this)">Invia</div>-->
    </form>
</div>


