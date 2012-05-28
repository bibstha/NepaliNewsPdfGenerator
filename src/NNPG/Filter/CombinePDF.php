<?php
/**
 * parameters passed through array for constructor
 * array key = parameter name
 * 
 * Parameter Information
 *   inPaths = string|array 
 *     if string, separate each path with new line
 *     if array, each entry is considered a path
 *   outPath = string
 *     the full path of the output generated file
 * 
 * @author bibek shrestha <bibekshrestha [at] gmail [dot] com>
 *
 */
class NNPG_Filter_CombinePDF implements NNPG_Filter_Interface
{
    protected $_name = 'CombinePDF';
    protected $_params;
    protected $_input;
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getName()
    {
        return $this->_name;
    }

    public function setParams($params)
    {
        $this->_checkParams($params);
        $this->_params = $params;
    }

    public function setInput($input)
    {
        $this->_input = $input;
    }
    
    public function process()
    {
        // do not continue if one of the process fails
        if (!$this->_checkInPaths()) return;
        if (!$this->_generateOutFile()) return;
    }
    
    protected function _checkInPaths()
    {
        $inPaths = $this->_input;
        if (empty($inPaths)) return false;

        foreach ($inPaths as $path) {
            if (!file_exists($path)) {
                throw new Exception('File does not exist : ' . $path);
            }
        }
        return true;
    }
    
    protected function _generateOutFile()
    {
        $outPath = $this->_params['outPath'];
        $inPaths = $this->_input;
        
        print "Generating Combined PDF : " . $outPath . "\n";
        
        if (file_exists($outPath)) return false;
        
        if (!file_exists(dirname($outPath))) 
            mkdir(dirname($outPath), 0777, true);
            
        $commandTpl = 'gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=%1$s -dBATCH %2$s';
//        $commandTpl = 'pdftk %2$s cat output %1$s';
        $command = sprintf($commandTpl, $outPath, implode(' ', $inPaths));
        exec($command, $output, $retVal);
        
        if ($retVal !== 0) {
            throw new Exception('Cannot complete task due to error');
        }
    }

    protected function _checkParams($params)
    {
        if (!isset($params['outPath'])) throw new Exception("Parameter 'outPath' should be set");
    }

}