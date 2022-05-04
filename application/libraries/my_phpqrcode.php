<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_phpqrcode {
    public function My_phpqrcode() {
        require_once('phpqrcode/phpqrcode.php');
    }
}