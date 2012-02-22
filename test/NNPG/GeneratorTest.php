<?php 
require_once("NNPG/Generator.php");

class NNPG_GeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $obj = new NNPG_Generator();
        $obj->generate('KTMPost', date('Y-m-d'));
    }
}