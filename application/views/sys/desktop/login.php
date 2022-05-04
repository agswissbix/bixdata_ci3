<?php
$theme='default'
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.js') ?>"></script>


        <script type="text/javascript" src="<?php echo base_url('/assets/js/materialize.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/js/materializeInit.js') ?>"></script>
        <script type="text/javascript">
            var controller_url="<?php echo site_url('sys_viewcontroller/'); ?>/"; 
        </script>
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/materialize.min.css") ?>?v=<?=time();?>" />
        
        <link rel="stylesheet" href="<?php echo base_url("/assets/css/sys/desktop/commonstyle.css") ?>?v=<?=time();?>" />


        

            <title>JDoc Web</title>
            <LINK REL="SHORTCUT ICON" HREF="<?php echo base_url("assets/images/logo_JDoc.ico"); ?>" >
                
    </head>

    <body id="login">
        <script type="text/javascript">
            $(document).ready(function(){
        
            $('#password').keyup(function(e){
                if(e.keyCode == 13)
                {
                   $('#login_form').submit()
                }
            });
        });
        </script>
        <div class="wrapper" style="background-color: #f5f5f5">
        <div class="content">
            <?php
            if(isset($error)&&$error!=null)
            {
                    echo $error;
            }
            ?> 
            <div class="row" style="margin-top: 10%;">
                <div class="col s4 offset-s4">
                    <div class="card">
                        <div class="row header" style="margin-top: 20px;">
                            <div class="col s12">
                                <div style="margin: auto;height: 50px;width: 90px;"><img id="logo_header" style="" src="<?php echo base_url("/assets/images/logo_JDoc.png")?>"></img></div>

                            </div>
                        </div>
                        <form id="login_form" action="<?php echo site_url("/sys_viewcontroller/login/desktop")?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <input  id="username" name="username" type="text" >
                                            <label for="username" class=''>Utente</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <input  id="password" name="password" type="password" >
                                            <label for="password" class=''>Password</label>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
                                <div class="col s4 offset-s4">
                                    <button class="btn waves-effect waves-light" onclick="$('#login_form').submit()" type="button" name="action">Accedi
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            
            
           
            
             </div>
<div class="pushfooter"></div>
    </div>
    
        <div class="footer">
            Powered by About-X
        </div>
    </body>
</html>
