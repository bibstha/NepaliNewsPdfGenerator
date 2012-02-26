<?php
class Autoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'load'));
    }
    
    public function getFileNameFromClassName($className)
    {
        return str_replace('_', '/', $className) . '.php';
    }
    
    public function load($className)
    {
        $fileName = self::getFileNameFromClassName($className);
        require_once($fileName);
    }
}