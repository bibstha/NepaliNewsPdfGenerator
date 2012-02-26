<?php
date_default_timezone_set('Asia/Kathmandu');
if (!defined('NNPG_PATH')) define('NNPG_PATH', realpath(dirname(__FILE__)));
if (!defined('FILE_PATH')) define('FILE_PATH', realpath(NNPG_PATH . '/../files'));

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
        $this->initIncludePath();
        $this->initAutoload();
    }
    
    public function initIncludePath()
    {
        set_include_path( 
            realpath(NNPG_PATH) .
            PATH_SEPARATOR .
            get_include_path()
        );
    }
    
    public function initAutoload()
    {
        require_once('Autoloader.php');
        $autoloader = new Autoloader();
    }
}

$bootStrap = new BootStrap();
$bootStrap->main();
