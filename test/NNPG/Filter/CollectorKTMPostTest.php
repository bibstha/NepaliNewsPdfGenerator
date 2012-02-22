<?php

require_once('NNPG/Filter/CollectorKTMPost.php');

class NNPG_Filter_CollectorKTMPostTest extends PHPUnit_Framework_TestCase
{
    protected $_params; 
    
    public function setUp()
    {
        $this->_params = array(
            'date' => '2012-2-22',
        );
    }
    
    public function testProcess()
    {
        $obj = new NNPG_Filter_CollectorKTMPost($this->_params);
        $obj->process();
        $this->assertTrue(true);
    }
}