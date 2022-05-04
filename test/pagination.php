<script type="text/javascript" src="http://localhost:8822/jdocweb/assets/js/jquery.js"></script>
<script type="text/javascript" src="http://localhost:8822/jdocweb/assets/bootstrap-4.0.0/js/bootstrap.min.js?v=1517898789"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
<script type="text/javascript" src="http://localhost:8822/jdocweb/assets/esimakin-twbs-pagination/jquery.twbsPagination.min.js?v=1517898789"></script>

<script type="text/javascript">
    
$(document).ready(function(){
   $(function () {
        var obj = $('#pagination').twbsPagination({
            totalPages: 35,
            visiblePages: 10,
            onPageClick: function (event, page) {
                console.info(page);
            }
        });
        console.info(obj.data());
    }); 
    })
</script>
<ul class="pagination justify-content-center" id="pagination" style="text-align: center" ></ul>