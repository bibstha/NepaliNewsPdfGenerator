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
class NNPG_Filter_CombinePDF
{
    protected $params;
    
    public function __construct($params)
    {
        if (!isset($params['inPaths'])) throw new Exception('inPaths not set');
        if (!isset($params['outPath'])) throw new Exception('outPath not set');
        
        if (!is_string($params['inPaths']) && !is_array($params['inPaths']))
            throw new Exception('inPaths should be either string or array');
            
        if (is_string($params['inPaths']))
            $params['inPaths'] = explode('\n', $params['inPaths']);
        
        $this->params = $params;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function process()
    {
        $this->_checkInPaths();
        $this->_generateOutFile();
    }
    
    protected function _checkInPaths()
    {
        foreach ($this->params['inPaths'] as $path) {
            if (!file_exists($path)) {
                throw new Exception('File does not exist : ' . $path);
            }
        }
        return true;
    }
    
    protected function _generateOutFile()
    {
        if (!file_exists(dirname($this->params['outPath']))) 
            mkdir(dirname($this->params['outPath']), 0777, true);
            
        $commandTpl = 'gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=%s -dBATCH %s';
        $command = sprintf($commandTpl, $this->params['outPath'], implode(' ', $this->params['inPaths']));
        
        exec($command, $output, $retVal);
        
        if ($retVal !== 0) {
            throw new Exception('Cannot complete task due to error');
        }
    }
}