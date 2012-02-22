<?php
require_once("NNPG/Utils/FileProxy.php");

class NNPG_Filter_CollectorKTMPost
{
    protected $_params;
    protected $_name = 'KTMPost';
    
    public function __construct($params)
    {
        $this->_checkParams($params);
        $this->_params = $params;
    }
    
    public function process()
    {
        return array(
            'inPaths' => $this->_downloadAndGenerateFileList(),
            'outPath' => FILE_PATH . '/combined/' . $this->_name . '/' . 
                date('Y-m-d', strtotime($this->_params['date'])) . '.pdf',
        );
    }
    
    protected function _checkParams($params)
    {
        // required : date (any format that is recognized by strtotime)
    }
    
    protected function _downloadAndGenerateFileList()
    {
        $date = date('jnY', strtotime($this->_params['date']) );
        $dateInFilename = date('Y-m-d', strtotime($this->_params['date']));
        // %1$s = date, %2$d = pagenumber
        $urlTpl = 'http://epaper.ekantipur.com/ktpost/%1$s/epaperpdf/%1$s-md-hr-%2$d.pdf';
        $numOfPages = 16;
        $destPath = '/tmp';
        $fileList = array();
        
        for ($i = 1; $i <= $numOfPages; $i++) {
            $filePath = 'single/' . $this->_name . "/$dateInFilename/p$i.pdf";
            
            $url = sprintf($urlTpl, $date, $i);
            $fileProxy = new NNPG_Utils_FileProxy();
            $fileProxy->setName($filePath);
            $fileProxy->setUrl($url);
            $fileProxy->download();
            $fileList[] = $fileProxy->getPath();
        }
        
        return $fileList;
    }
}