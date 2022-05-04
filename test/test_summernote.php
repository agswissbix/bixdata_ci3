<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!--  summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-lite.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#summernote').summernote({
            height: 200,
            toolbar: [
              // [groupName, [list of button]]
              ['style', ['bold', 'italic', 'underline', 'fontsize']],
              ['color', ['forecolor','backcolor']],
              ['paragraph',['paragraph','height','ul','ol']]
            ],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0']
          });
    });
</script>

<div id="summernote" ></div>