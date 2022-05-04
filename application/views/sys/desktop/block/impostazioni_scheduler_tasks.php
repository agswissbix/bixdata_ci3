<div id="block_scheduler_task" class="block">
    scheduler task <br/>
    <?php
    foreach ($scheduler_tasks as $scheduler_task_key => $scheduler_task) {
        echo $scheduler_task['funzione'];
        ?>
    <div onclick="scheduler_task_run(<?=$scheduler_task['id']?>)">Avvia</div>
    <?php
    }
    ?>

</div>