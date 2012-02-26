<?php
require_once('Autoloader.php');
class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testGetFileNameFromClassName()
    {
        $autoloader = new Autoloader();
        
        $className = 'TestClass';
        $expected = 'TestClass.php';
        $fileName = $autoloader->getFileNameFromClassName($className);
        $this->assertEquals($expected, $fileName);
        
        $fileName = $autoloader->getFileNameFromClassName('TestPackage_TestClass');
        $this->assertEquals('TestPackage/TestClass.php', $fileName);
    }
    
    public function testLoad()
    {
        $oldIncludePath = get_include_path();
        set_include_path( 
            $oldIncludePath .
            PATH_SEPARATOR .
            realpath(dirname(__FILE__) . '/AutoloaderTest')
        );
        $autoloader = new Autoloader();
        $object = new AutoloaderTest_TestClass();
        
        // cleanup
        set_include_path($oldIncludePath);
    }
}