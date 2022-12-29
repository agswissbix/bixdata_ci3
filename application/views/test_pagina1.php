<!--JQuery-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    var controller_url = "<?php echo base_url('index.php/sys_viewcontroller/'); ?>/";

    function pagina2() {
        $.ajax({
            url: controller_url + 'get_pagina2',
            success: function(response) {
                console.info(response);
                $("#pagina").html(response);
            },
            error: function() {
                alert('error');
            }
        });
    }
</script>

<div id="pagina">
    <button onclick="pagina2()">Passa a pagina 2</button>
</div>
