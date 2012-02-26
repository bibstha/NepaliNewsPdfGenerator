<?php 
require_once("NNPG/Generator.php");

class NNPG_GeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateTodayKTMPost()
    {
        $obj = new NNPG_Generator();
        $obj->generate('KTMPost', date('Y-m-d'));
    }
    
    public function testGenerateTodayKantipur()
    {
        $obj = new NNPG_Generator();
        $obj->generate('Kantipur', date('Y-m-d'));
    }
    
    public function testGenerateTodayRepublica()
    {
        $obj = new NNPG_Generator();
        $obj->generate('Republica', date('Y-m-d'));
    }
    
    public function testGenerateTodayNagarik()
    {
        $obj = new NNPG_Generator();
        $obj->generate('Nagarik', date('Y-m-d'));
    }
    
    public function testGenerateTodayTHT()
    {
        $obj = new NNPG_Generator();
        $obj->generate('THT', date('Y-m-d'));
    }
}
