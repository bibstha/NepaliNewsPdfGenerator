<?php
date_default_timezone_set('Asia/Kathmandu');
if (!defined('APP_PATH')) define('APP_PATH', realpath(dirname(__FILE__)));
if (!defined('FILE_PATH')) define('FILE_PATH', realpath(APP_PATH . '/../files'));

/**
 * Class to define initialization of the application
 * 
 * @author Bibek Shrestha <bibekshrestha [at] gmail [dot] com>
 *
 */
class BootStrap
{
    public function main()
    {
        $this->initAutoload();
    }
    
    public function initAutoload()
    {
        require_once('Autoloader.php');
        $autoloader = new Autoloader();
    }
}

$bootStrap = new BootStrap();
$bootStrap->main();
