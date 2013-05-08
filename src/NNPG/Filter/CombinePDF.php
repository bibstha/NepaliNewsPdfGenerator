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
    const TAG = "Filter_CombinePDF";

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
        $thumbnailPath = $this->_params['thumbnailPath'];
        $inPaths = $this->_input;

        // Generate thumbnail
        if (!empty($inPaths[0]))
        {
            if (!file_exists(dirname($thumbnailPath)))
            {
                NNPG_Utils_Log::d(self::TAG, "Creating directory " . dirname($thumbnailPath));
                mkdir(dirname($thumbnailPath), 0777, true);
            }
            if (!file_exists($thumbnailPath))
            {
                $thumbnailTpl = 'convert -flatten -thumbnail 170 %1$s %2$s';
                $thumbnailCmd = sprintf($thumbnailTpl, $inPaths[0], $thumbnailPath);
                NNPG_Utils_Log::d(self::TAG, "Creating thumbnail at $thumbnailPath. Executing command \n\t" . $thumbnailCmd);
                exec($thumbnailCmd);
            }
            else
            {
                NNPG_Utils_Log::d(self::TAG, "Thumbnail already exists at $thumbnailPath");
            }
        }
        
        NNPG_Utils_Log::d(self::TAG, "Trying to generating combined PDF : " . $outPath);
        
        if (file_exists($outPath))
        {
            NNPG_Utils_Log::d(self::TAG, "PDF file already exist : " . $outPath);
            return false;
        }
        
        if (!file_exists(dirname($outPath)))
        {
            NNPG_Utils_Log::d(self::TAG, "Creating directory : " . dirname($outPath));
            mkdir(dirname($outPath), 0777, true);
        }
        

        $thumbnailTpl = 'convert -flatten -thumbnail 170 %1$s %2$s';
        $commandTpl = 'gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=%1$s -dBATCH %2$s';
//        $commandTpl = 'pdftk %2$s cat output %1$s';
        $command = sprintf($commandTpl, $outPath, implode(' ', $inPaths));
        NNPG_Utils_Log::d(self::TAG, "Executing command \n\t" . $command . "\n");
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