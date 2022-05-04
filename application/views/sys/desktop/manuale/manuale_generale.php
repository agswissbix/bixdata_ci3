<script type="text/javascript">
    $('h1,h2,h3').click(function (){
        $(this).next().slideToggle(500);
    });
    
        
</script>
<style type="text/css">
    .manuale{
        background-color: #EEEEEE;
        padding: 20px;
        overflow: scroll;
        height: 100%;
        font-size: 10px;
    }
    .manuale div{
        width: 100%;
    }
    
    .manuale .macrosezione{
        display: none;
        padding-left: 20px;

    }
    
    .manuale .sezione{
        display: none;
        padding-left: 20px;
    }
    
    .manuale .subsezione{
        padding: 10px;
        padding-left: 20px;
        display: none;
        border-radius: 2px;
        border: 0px;
        background-color: white;
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.14);
        width: 90%
    }
    .manuale img{
        width: 70%;
        min-height: 50px;
        margin: 5px;
        margin-left: 35px;
    }

    .manuale ul{
        list-style: initial;
        margin: initial;
        padding: 0 0 0 40px;
        margin-top: 5px;
        margin-bottom: 5px;
        margin-left: 40px;
    }

    .manuale li{
        display: list-item;
        list-style: circle;
    }


    h1,h2,h3,h4,h5{
        line-height: 120%;
    }
    h1,h2,h3{
        margin: 5px;
        cursor: pointer;
    }
    h1{
        margin-top: 20px;
        font-size: 18px;
        border-bottom: 1px solid black;
        font-weight: bold;
    }
    h2{
        font-size: 14px;
        font-weight: bold;
    }
    h3{
        font-size: 12px;
        border-bottom: 1px solid #EEEEEE;
    }
    h3:hover{
        border-bottom: 1px solid #54ace0;
    }
    h4{
        font-size: 10px;
    }
    
    .annotazioni{
        font-style: oblique;
    }
</style>
<div id="manuale_generale" class="manuale" >
    v. 03/11/2017
    <h1 onclick="">Manuale generale</h1>
    <div class="macrosezione">
            <h2>Inserimento dati</h2>
            <div class="sezione">
                <h3>Inserimento popup direttamente dal menu principale</h3>
                <div class="subsezione">
                    <ul>
                        <li>Selezionare il pulsante di inserimento</li>
                        <li>Selezionare l'archivio in cui fare l'inserimento</li>
                    </ul>
                    <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento1.png') ?>">
                </div>

                <h3>Inserimento popup dalla scheda dei risultati</h3>
                <div class="subsezione">
                    <ul>
                        <li>Premere sul pulsante + nella scheda dei risultati</li>
                    </ul>
                    <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento2.png') ?>">
                    <ul>
                        <li>Inserire i dati</li>
                    </ul>    
                    <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento_dati_popup.png') ?>">
                    <ul>
                        <li>Inserire eventuali allegati</li>
                    </ul>
                </div>
            </div>
            <h2>Inserimento allegati</h2>
            <div class="sezione">
                <h3>Selezione da File</h3>
                <div class="subsezione">
                    <ul>
                        <li>Premere su file e selezionare uno o più file da allegare</li>
                    </ul>
                    <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento_allegati_file1.png') ?>">
                    <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento_allegati_file2.png') ?>">
                </div>
                <h3>Selezione da Coda</h3>
                <div class="subsezione">
                    <ul>
                        <li>Premere su Coda</li>
                        <li>Dal menu laterale selezionare la coda desiderata</li>
                        <li>Trascinare i file da allegare dalla coda alla sezione degli allegati</li>
                        <img src="<?php echo base_url('/assets/images/manuale/generale/inserimento_allegati_coda.png') ?>">
                    </ul>
                </div>
            </div>
            <h2>Salvataggio</h2>
            <div class="sezione">
                <ul>
                    <li>Salvare con uno dei pulsanti di salvataggio in basso a destra nella scheda</li>
                </ul>
                <h3>Salva e modifica</h3>
                <div class="subsezione">
                    <ul>
                        <li>Per salvare dati e allegati e continuare nella modifica della scheda o per aggiungere informazioni collegate</li>
                    </ul>
                </div>
                <h3>Salva e nuovo</h3>
                <div class="subsezione">
                    <ul>
                        <li>Per salvare dati e allegati e iniziare un nuovo inserimento</li>
                    </ul>
                </div>
                <h3>Salva e chiudi</h3>
                <div class="subsezione">
                    <ul>
                        <li>Per salvare dati e allegati e chiudere la scheda</li>
                    </ul>
                </div>
            </div>
            <h2>Ricerca</h2>
            <div class="macrosezione">
                <ul>
                    <li>Selezionare il pulsante di ricerca</li>
                    <li>Selezionare dal menu l'archivio da aprire e su cui eseguire una ricerca</li>
                    <img src="<?php echo base_url('/assets/images/manuale/generale/ricerca1.png') ?>">
                </ul>
                <h3>Visualizzazione di tutti i dati dell'archivio</h3>
                    <ul>
                        <li>Di default vengono visualizzati tutti i dati dell'archivio ordinati in ordine descrescente rispetto alla prima colonna</li>
                    </ul>
                    <img src="<?php echo base_url('/assets/images/manuale/generale/risultati_totale.png') ?>">
                <h3>Filtro sui dati in base ai campi impostati</h3>
                        <h4>Campi di testo</h4>
                            <ul>
                                <li>Inserire il testo, anche parziale, da ricercare</li>
                            </ul>
                        <h4>Campi tabella</h4>
                            <ul>
                                <li>Selezionare dalla tendina il valore da ricerca</li>
                            </ul>
                        <h4>Campi numerici</h4>
                            <ul>
                                <li>Per cercare un singolo valore, inserirlo nella parte sinistra del campo</li>
                                <li>Per cercare un intervallo di valori, inserire inizio e fine dell'intervallo nella parte sinistra e destra del campo</li>
                            </ul>
                        <h4>Campi data</h4>
                            <ul>
                                <li>Per cercare un singolo valore, inserirlo nella parte sinistra del campo (premere  sul triangolo per visualizzare un calendario)</li>
                                <li>Per cercare un intervallo di valori, inserire inizio e fine dell'intervallo nella parte sinistra e destra del campo</li>
                            </ul>
                        <img src="<?php echo base_url('/assets/images/manuale/generale/risultati_filtrati.png') ?>">
                <h3>Parametri sui campi di ricerca</h3>
                <ul>
                    <li>Premere X per azzerare il valore inserito in un campo</li>
                    <li>Premere + per aggiungere un ulteriore valore da cercare, in alternativa, per lo stesso campo</li>
                    <li>Premere i tre punti per ulteriori opzioni</li>
                    <ul>
                        <li>"diverso da" per cercare valori diversi da quello impostato</li>
                        <li>"almeno un valore" per cercare un qualsiasi valore in quel campo</li>
                        <li>"nessun valore" per cercare senza nessun valore in quel campo</li>
                    </ul>
                </ul>
            </div>
            <h2>Apertura</h2>
                <h3>Apertura a lato di una scheda</h3>
                    <ul>
                        <li>Selezionare una riga dai risultati della ricerca per aprire la scheda laterale corrispondente</li>
                    </ul>
                <h3>Apertura in popup di una scheda</h3>
                    <ul>
                        <li>Dalla scheda laterale premere sul pulsante in alto a sinistra per visualizzare la scheda in popup</li>
                    </ul>
            <h2>Modifica dati</h2>
                <h3>Apertura a lato di una scheda</h3>
                        <ul>
                            <li>All'interno della scheda, nella sezione dati, premere sull'icona di modifica per modificare i dati presenti e visualizzare ulteriori campi da inserire</li>
                            <img src="<?php echo base_url('/assets/images/manuale/generale/modifica_dati.png') ?>">
                            <li>Premere il pulsante di salvataggio (in alto a destra o basso a destra della sezione che si sta modificando) per salvare le modifiche</li>
                            <img src="<?php echo base_url('/assets/images/manuale/generale/modifica_dati_salva.png') ?>">
                        </ul>
                <h3>Modifica archivi collegati</h3>
                    <ul>
                        <li>Nelle sezioni degli archivi collegati premere sul tasto + per aggiungere una nuova voce di un archivio collegato</li>
                    </ul>
                <h3>Modifica campo collegato</h3>
                <ul>
                    <li>Scrivere nel campo per cercare e selezionare il valore di collegare</li>
                    <li>Premere su salva per salvare le modifiche apportate</li>
                </ul>
                <h3>Modifica allegati</h3>
                <ul>
                    <li>Nella sezione allegati di una scheda, aggiungere nuovi allegati da file o coda, come in fase di inserimento</li>
                    <li>Spostare trascinando per riordinare</li>
                    <li>Premere il pulsante elimina e confermare per eliminare</li>
                    <li>Le modifiche così apportare alla lista allegati sono automaticamente salvate</li>
                </ul>
    </div>
    <h1>Manuale cliente</h1>
    <div class="macrosezione">
        <h2>Gestione immobili</h2>
        <div class="sezione">
            <h3>Inserimento immobile</h3>
            <div class="subsezione">
                <ul>
                    <li>Premere sul pulsante + dell'archivio relativo agli immobili</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_immobili_1.png') ?>">
                    <li>Inserire i dati dell'immobile e salvare. Eventuamente aggiungere subito anche degli alegati</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_immobili_2.png') ?>">
                </ul>
            </div>
            <h3>Creazione prospetto</h3>
            <div class="subsezione">
                
            </div>
            <h3>Invio prospetto</h3>
            <div class="subsezione">
                
            </div>
            <h3>Aggiunta foto</h3>
            <div class="subsezione">
                
            </div>
            <h3>Gestione categorie delle foto</h3>
            <div class="subsezione">
                
            </div>
            <h3>Aggiunta di documenti</h3>
            <div class="subsezione">
                
            </div>
            <h3>Caricamento sui portali</h3>
            <div class="subsezione">
                
            </div>
        </div>
        <h2>Gestione contatti</h2>
        <div class="sezione">
            <h3>Inserimento richiesta</h3>
            <div class="subsezione">
                <ul>
                    <li>Aprire l'archivio dei contatti</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_1.png') ?>">
                    <li>Cercare nell'anagrafica se la persona che ha fatto la richiesta è già presente nel database</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_2.png') ?>">
                    <li>In caso non dovesse essere presente, premere sul + e procedere con l'inserimento dei dati anagrafici</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_3.png') ?>">
                    <li>Selezionare l'immobile richiesto</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_4.png') ?>">
                    <li>Eventualmente compilare i campi ulteriori della richiesta</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_5.png') ?>">
                    <li>Inviare o meno la protezione cliente</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_6.png') ?>">
                    <li>Confermare o meno l'invio della protezione cliente</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_richiesta_7.png') ?>">
                    
                </ul>
            </div>
            <h3>Inserimento attività relative alla richiesta</h3>
            <div class="subsezione">
                <ul>
                    <li>Aprire la scheda di una proposta e premere sul + della sezione dele attività</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/inserimento_attivita.png') ?>">
                    <li>Compilare i campi e premere sul'icona di salvataggio posta in basso a destra della sezione dele attività</li>
                    <div class="annotazioni">Il recall, lo stato richiesta, e le note richiesta inserite nell'ultima attività aggiornano i relativi campi della richiesta stessa. Le richieste hanno quindi questi parametri aggiornati con la relativa utima attività</div>
                </ul>
            </div>
            <h3>Invio prospetto</h3>
            <div class="subsezione">
                <ul>
                    <li>Selezionare la proposta relativa all'immobile di cui si deve inviare il prospetto e premere su invia prospetto</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/invio_prospetto_1.png') ?>">
                    <li>Verificare il prospetto generato nella sezione allegati e procedere con l'invio del prospetto</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/invio_prospetto_2.png') ?>">
                </ul>
            </div>
            <h3>Proposta immobili alternativi</h3>
            <div class="subsezione">
                <ul>
                    <li>Aprire la scheda di una proposta e usare la funzione nuova proposta</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/proposta_alternativa_1.png') ?>">
                    <li>Selezionare il nuovo immobile da proporre nella scheda così creata</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/proposta_alternativa_2.png') ?>">
                    <li>Inviare mail di protezione cliente relativa all'immobile proposto</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/proposta_alternativa_3.png') ?>">
                    <li>Inviare prospetto relativo all'immobile proposto</li>
                    <img src="<?php echo base_url('/assets/images/manuale/custom/DimensioneImmobiliare/proposta_alternativa_4.png') ?>">
                </ul>
                
            </div>
            <h3>Accesso rubrica contatti</h3>
            <div class="subsezione">
                
            </div>
            
        </div>
        <h2>Matching</h2>
        <div class="sezione">
        </div>
    </div>
</div>
