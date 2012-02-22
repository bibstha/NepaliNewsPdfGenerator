<?php
/**
 * parameters passed through array for constructor
 * array key = parameter name
 * 
 * 
 * 
 * Enter description here ...
 * @author bibek
 *
 */

require_once('NNPG/Filter/CombinePDF.php');
class NNPG_Filter_CombinePDFTest extends PHPUnit_Framework_TestCase
{
    protected $params;
    protected $outputFile = '/tmp/outputfile.pdf';
    
    public function setUp()
    {
        $this->params = array(
            'inPaths' => array(
                '/tmp/a.pdf',
                '/tmp/b.pdf',
            ),
            'outPath' => $this->outputFile,
        );
    }
    
    public function testConstructor()
    {
        $obj = new NNPG_Filter_CombinePDF($this->params);
        $observed = $obj->getParams();
        
        $this->assertEquals($this->params, $observed);
    }
    
    public function testProcess()
    {
        if (file_exists($this->outputFile)) {
            unlink($this->outputFile);
        }
        $obj = new NNPG_Filter_CombinePDF($this->params);
        $obj->process();
        $this->assertTrue(file_exists($this->outputFile));
        
        unlink($this->outputFile);
    }
}