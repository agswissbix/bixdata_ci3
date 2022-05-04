<?php

/**
 * Genera il recordid per l'inserimento di nuovi record nella struttura di JDoc
 * 
 * @param type $record
 */
function generate_recordid($record)
{
    $temp=intval($record);
    $temp+=1;
    $record=strval($temp);
}

function domain_url()
{
    return "http://".$_SERVER['HTTP_HOST']."/";
}

function server_url()
{
    return domain_url()."JDocServer/";
}

function project_name()
{
    return str_replace(domain_url(), '', base_url());
}

/**
 * Verifica se l'utente è loggato
 * @param type $controller
 * @return boolean
 * @author Alessandro Galli 
 */
function logged($controller) 
{
        if ($controller->session->userdata('username')/*=="a.galli"*/) 
        {
            return true;
        } else 
        {
            return false;
        }
}

/**
 * Visualizza una view in base a diversi parametri
 * 
 * @param type $controller controllore da cui parte la richiesta
 * @param array $arg array di parametri presi in considerazione per la visualizzazione
 * @author Alessandro Galli
 */
function view_general($controller,$arg)
{     
    if(isset($arg['module']))
    {
        $module=$arg['module'];
    }
    else
    {
        $module='sys';
    }
    
    if(isset($arg['interface']))
    {
        $controller->template->set_template($module.'_'.$arg['interface']);
        $interface=$arg['interface'];

    }
    else
    {
        $interface='desktop';
    }
    
    
    if(isset($arg['content']))
    {
        $content=$arg['content'];

    }
    else
    {
        echo 'content non definito';
    }
    
    
    if(isset($arg['content_data']))
    {
        $data['data']['content_data']=$arg['content_data'];
    }
    else
    {
        $data['data']['content_data']=null;
    }
    
    if(isset($arg['sys_data']))
    {
        $data['data']['sys_data']=$arg['sys_data'];
    }
    else
    {
        $data['data']['sys_data']=null;
    }
    
    if(isset($arg['menu_data']))
    {
        $data['data']['menu_data']=$arg['menu_data'];
    }
    else
    {
        $data['data']['menu_data']=null;
    }
    
    if(isset($arg['block']))
    {
        $data['data']['block']=$arg['block'];
    }
    else
    {
        $data['data']['block']=null;
    }
    
    
    if(isset($arg['extraheader']))
    {
    $controller->template->write_view('extraheader', $module.'/'.$interface.'/extraheader/'.$arg['extraheader']);
    }

    if(isset($arg['backmenu']))
    {
        
        $controller->template->write('backmenu', createbackmenu($arg['backmenu'],$arg['module']) );
    }
    if(isset($arg['menu']))
    {
    $controller->template->write_view('menu', $module.'/'.$interface.'/menu/'.$arg['menu'],$menu_data);
    }
    
    if(($module=='sys')&&($interface=='desktop'))
    {
    $controller->template->write_view('sys_menu', $module.'/'.$interface.'/menu/sys_menu',$data);
    }
    
    $controller->template->write_view('content', $module.'/'.$interface.'/content/'.$content,$data);
    $controller->template->render(); 
}
    

/**
 * 
 * Genera il pulsante per tornare indietro nel menu mobile
 * 
 * @param type $backfun
 * @param type $modulo
 * @return string
 */

function replace1252 ($string) {
        $text = str_replace(
        array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
        array("'", "'", '"', '"', '-', '--', '...'),
        $string);
        // Next, replace their Windows-1252 equivalents.
        $text = str_replace(
        array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
        array("'", "'", '"', '"', '-', '--', '...'),
        $text);
        return $text;
}

function createbackmenu($backfun,$modulo='crm')
{
       $html='<a data-role="button" data-icon="back" href="'.site_url().'/'.$modulo.'_viewcontroller/'.$backfun.'/tablet">Indietro</a>';
       return $html;
}

function conv_text($texto)
{
    
    //return iconv('UTF-8', 'windows-1252//IGNORE',$texto);
        return html_entity_decode(@iconv('UTF-8', "windows-1252//IGNORE",$texto));
}

function conv_text_utf8($texto)
{
    
    return @iconv('windows-1252', "UTF-8//IGNORE",$texto);
}

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

function isempty($value)
{
    if(($value=='')||($value==null)||($value=='0000-00-00'))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function db_convert_date($value)
{
    if(isempty($value))
        $value="null";
    else
        $value="'$value'";
    return $value;
}

function db_convert_number($value)
{
    if(isempty($value))
        $value="null";
    else
        $value="$value";
    return $value;
}

function db_convert_string($value)
{
    $value=  str_replace("'", "''", $value);
    if(isempty($value))
        $value="null";
    else
        $value="'$value'";
    
    return $value;
}

function isnotempty($value)
    {
        if(($value!='')&&($value!='00:00:00')&&($value!=null)&&($value!='undefined'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function exec_output($command)
{
    echo "<br/>";
        echo exec("whoami");
        echo "Comando:";
        echo "<br/>";
        echo $command;
        echo "<br/>";
        exec($command,$output,$return_var);
        echo "Output:";
        echo "<br/>";
        foreach ($output as $key => $value) {
            echo $value;
        }
        echo "<br/>";
        echo "Return var:";
        echo "<br/>";
        echo $return_var;
        echo "<br/>";
        echo "<br/>";
}

function echo_flush($txt){
 //inizializzazione del buffer per l'output
 if (ob_get_level() == 0) ob_start();
 echo $txt;
 //per Chrome e Safari si deve aggiungere questa istruzione
 print str_pad('',4096)."\n";
 //invia il contenuto al buffer
 ob_flush();
    flush();
}

function hoursandmins($time, $format = '%02d:%02d')
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
 
function time_to_decimal($time) {
    if(isnotempty($time))
    {
        $timeArr = explode(':', $time);
        $decTime = ($timeArr[0]) + ($timeArr[1]/60);
    }
    else
    {
        $decTime='0.0';
    }
    return $decTime;
}

function decimal_to_time($decimal_hours) {
    if(isnotempty($decimal_hours))
    {
        $hours = floor($decimal_hours);
        $mins = round(($decimal_hours - $hours) * 60);
        $mins=sprintf('%02d', $mins);
        return $hours.":".$mins;
    }
    else
    {
        return '00:00';
    }
}

?>
