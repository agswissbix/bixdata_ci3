<?php
$calendars=$data['calendars'];
$DataInizioRange=$data['datainizio'];
$DataFineRange=$data['datafine'];
?>
<style type="text/css">
    #calendar h2{
        font-size: 16px;
    }
    .fc-toolbar{
        margin: 0px;
    }
</style>
<script type="text/javascript">
$('#calendar').ready(function() {
    $('#calendar').fullCalendar({
        height: 'auto',
        handleWindowResize: true,
        lang: 'it',
        weekends: true,
        businessHours: true,
        header:
	{
            left: 'prev,next,today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
	},
        events:
        [
            <?php
        foreach ($calendars as $key => $calendar) 
        {
            $tableid=$calendar['tableid'];
            $records=$calendar['records'];
            foreach($records as $record)
            {
                $orainizio=' '.$record['orainizio'];
                $orafine=$record['orafine'];
                if($orafine==null)
                {
                    $orafine=' '.date("H:i:s",strtotime($record['data'].' '.$record['orainizio']) + 3600);
                }
                else
                {
                    $orafine=' '.$orafine;
                }
            
            ?>
                {
                    id: '<?php echo $record['recordid_']; ?>',
                    title: '<?php echo $record['titolo']; ?>',
                    start: '<?php echo $record['data'];?>',
                    end: '<?php echo $record['data']?>',
                    allDay: <?php if($record['orainizio']==null) echo "true"; else echo "false"; ?>,
                    editable: true,
                    startEditable: true,
                    durationEditable: true,
                    overlap: true,
                    recordid: '<?php echo $record['recordid_']; ?>',
                    tableid: '<?=$tableid?>',
                    fieldid_data: '<?php echo $calendar['fieldid_data']; ?>',
                    fieldid_orainizio: '<?php echo $calendar['fieldid_orainizio']; ?>',
                    fieldid_orafine: '<?php echo $calendar['fieldid_orafine']; ?>'
                },
            <?php 
            }
        } ?>
        ],
        eventClick: function(calEvent, jsEvent, view)
	{
            apri_scheda_record(this,calEvent.tableid,calEvent.recordid,'popup','standard_dati','risultati_ricerca');
	},
        eventDrop: function(event,delta,revertFunc)
        {
           //nelle prossime righe recupero le informazioni necessarie da passare al controller per l'aggiornamento
            var fieldidData = event.fieldid_data;
            var nuova_data=event.start.format("YYYY-MM-DD");
            
            var NuovoOraInizio=event.start.format("HH:mm:ss");
            var fieldidOraInizio = event.fieldid_orainizio;
            
            var NuovoOraFine = event.end.format("HH:mm:ss");
            var fieldidOraFine = event.fieldid_orafine;
            
            var tableid = event.tableid;
            var recordid = event.recordid;
            
            //adesso parte la richiesta ajax che si occupa di salvare gli aggiornamenti
            var url=controller_url+"/modifica_evento_calendario";
            $.ajax({
               url: url,
               type: 'post',
               data: "tableid=" + tableid + "&recordid=" + recordid + "&fieldid_data=" + fieldidData + "&nuova_data=" + nuova_data + "&fieldid_orainizio=" + fieldidOraInizio + "&nuova_orainizio=" + NuovoOraInizio + "&fieldid_orafine=" + fieldidOraFine + "&nuova_orafine=" + NuovoOraFine,
               dataType:'text',
               success:function()
               {
                  //alert("aggiornamento eseguito"); 
               },
               error:function()
               {
                   revertFunc();
               }
            }); 
        },
        eventResize:function(event,delta,revertFunc,jsEvent,ui,view)
        {
            //nelle prossime righe recupero le informazioni necessarie da passare al controller per l'aggiornamento
            var fieldidData = event.fieldid_data;
            var nuova_data=event.start.format("YYYY-MM-DD");
            
            var NuovoOraInizio=event.start.format("HH:mm:ss");
            var fieldidOraInizio = event.fieldid_orainizio;
            
            var NuovoOraFine = event.end.format("HH:mm:ss");
            var fieldidOraFine = event.fieldid_orafine;
            
            var tableid = event.tableid;
            var recordid = event.recordid;
            
            //adesso parte la richiesta ajax che si occupa di salvare gli aggiornamenti
            var url=controller_url+"/modifica_evento_calendario";
            $.ajax({
               url: url,
               type: 'post',
               data: "tableid=" + tableid + "&recordid=" + recordid + "&fieldid_data=" + fieldidData + "&nuova_data=" + nuova_data + "&fieldid_orainizio=" + fieldidOraInizio + "&nuova_orainizio=" + NuovoOraInizio + "&fieldid_orafine=" + fieldidOraFine + "&nuova_orafine=" + NuovoOraFine,
               dataType:'text',
               success:function()
               {
                  //alert("aggiornamento eseguito"); 
               },
               error:function()
               {
                   revertFunc();
               }
            }); 
        },
        allDayText: 'Intera Giornata',
        axisFormat: 'HH:mm',
        slotDuration: '00:30:00',
        minTime: '00:00:00',
        maxTime: '24:00:00',
        defaultView: '<?php
            if(($DataInizioRange!=null)&&($DataInizioRange!="")){
                $datetimeInizio = new DateTime($DataInizioRange);
                $datetimeFine = new DateTime($DataFineRange);
                $interval = $datetimeFine->diff($datetimeInizio);
                $interval=$interval->d;
                if($interval==0)
                    echo "agendaDay";

                else if($interval>1 && $interval<=5)
                    echo "agendaWeek";

                else if($interval>5)
                    echo "month";
            }
            else
                echo "month";
        ?>',
        defaultDate:
        <?php
            if(($DataInizioRange=="")||($DataInizioRange==null))
                $DataInizioRange = date('Y-m-d');
            echo "'".$DataInizioRange."'";
        ?>,
        dayClick: function(date,jsEvent,view)
        {
            //alert("ciao");
            //alert("DATA CLICCATA " + date.format("YYYY-MM-DD HH:mm:ss"));
        }
    });
    
    $('.fc-next-button').click(function()
    {
        var view = $('#calendar').fullCalendar('getView')
        //alert("Data Inizio: " + view.start.format("YYYY-MM-DD") + "\r\nData Fine: " + view.end.format("YYYY-MM-DD"));
    });
    
    $('.fc-prev-button').click(function(){
        var view = $('#calendar').fullCalendar('getView');
        //alert("Data Inizio: " + view.start.format("YYYY-MM-DD") + "\r\nData Fine: " + view.end.format("YYYY-MM-DD"));
    });
});
</script>
<div class="calendar_name" style="">Interventi</div>
<div id='calendar' style="width: 100%" ></div>
