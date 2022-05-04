<script type="text/javascript">
    
    function custom_update_list_refresh()
    {
        $('#custom_update_list').html('Refresh');
        $.ajax({
            url: controller_url+"/get_custom_update_list/",
            success:function(data){
                $('#custom_update_list').html(data);
                setTimeout(function(){
                        custom_update_list_refresh();
                    },5000);

            },
                error:function(){
                    alert("ERRORE RICHIESTA AJAX");
                }
            });
    }

    


function custom_update_output(text)
{
    var d = new Date();
    var hours = d.getHours();
    var minutes=d.getMinutes();
    var seconds=d.getSeconds();
    var now=hours+':'+minutes+':'+seconds;
   $('#custom_update_output').append(now+"  "+text+'<br/>'); 
}

function custom_update()
{
            
            $.ajax({
                url: controller_url+"/get_custom_update/" ,
                success:function(data){
                    var custom_update_row = JSON.parse(data);
                    if(custom_update_row!=null)
                    {
                        var funzione=custom_update_row['funzione'];
                        var custom_update_recordid=custom_update_row['recordid'];
                        var today = new Date();
                        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                        custom_update_output("Inizio <b>"+funzione+"</b> "+custom_update_recordid+': '+time+'<br/>');
                        $.ajax({
                                url: controller_url+"/"+funzione+"/"+custom_update_recordid ,
                                success:function(data){
                                    custom_update_output('Output funzione <br/>'+data);
                                    $.ajax({
                                    url: controller_url+"/custom_update_done/"+custom_update_row['id'] ,
                                    success:function(data){
                                        var today = new Date();
                                        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                        custom_update_output("Fine <b>"+funzione+"</b> "+custom_update_recordid+': '+time+'<br/><br/>');
                                        custom_update();
                                    },
                                        error:function(){
                                            alert("ERRORE RICHIESTA AJAX");
                                        }
                                    });
                                },
                                error:function(){
                                    alert("ERRORE RICHIESTA AJAX");
                                }
                            });
                    }
                    else
                    {
                        setTimeout(function(){
                            custom_update();
                        },5000);

                    }

                },
                error:function(){
                    alert("ERRORE RICHIESTA AJAX");
                }
            });
        
              
        
    
}


function custom_update_delete()
{
            
            $.ajax({
                url: controller_url+"/custom_update_delete/" ,
                success:function(data){
                    alert(' Custom update eliminati');
                },
                error:function(){
                    alert("ERRORE RICHIESTA AJAX");
                }
            });
        
              
        
    
}

$(document).ready(function(){
        
        custom_update_list_refresh();
        
    
  });
  
</script>



<div style="width: 45%;float: left;">
    <button onclick="custom_update()">RUN</button><button onclick="$('#custom_update_output').html('');">Clear</button><button onclick="custom_update_delete(this)">Delete</button> <br/><br/>
    <div id="custom_update_output" style="border: 1px solid black;width: 100%;height: 500px;overflow: scroll;background-color: white;">

    </div>
</div>
<div style="width: 45%;float: left;">
    <div id="custom_update_list" style="border: 1px solid black;width: 100%;height: 500px;overflow: scroll;background-color: white;">

    </div>
</div>