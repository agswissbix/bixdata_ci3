<?php


  //$data
   // $logged=false; //true,false
   // $errore=false//"Nome utente o password errati"; //"testo errore"
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="<?php echo base_url("/assets/css/style.css")?>" />
    <script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js')?>"></script>


            <title>home generale</title>
            <LINK REL="SHORTCUT ICON" HREF="<?php echo base_url("assets/images/logo_JDoc.ico"); ?>" >
    </head>

    <body>


        <div id="content">

            <h2>Home generale</h2>
                                    <hr>
            <div id="content_gestione_dati">                            
            <a class='button' href="<?php echo site_url()?>/sys_viewcontroller/view_home/desktop">sys desktop</a> <br/>
            
            <a class='button' href="<?php echo site_url()?>/sys_viewcontroller/view_home/tablet">sys tablet</a> <br/>

            <a class='button' href="<?php echo site_url()?>/sys_viewcontroller/view_home/phone">sys phone</a> <br/>
            
            <a class='button' href="<?php echo site_url()?>/crm_viewcontroller/view_home/tablet">crm tablet</a> <br/>
                     
            <a class='button' href="<?php echo site_url()?>/its_viewcontroller/view_home/tablet">its tablet</a>

                        
            </div>
        </div>
        <hr>
        </div>
        <div id="footer">
            Powered by About-X 2013
        </div>
    </body>
</html>

