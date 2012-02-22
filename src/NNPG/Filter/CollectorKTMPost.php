<?php
require_once("NNPG/Utils/FileProxy.php");

class NNPG_Filter_CollectorKTMPost
{
    protected $_params;
    
    public function __construct($params)
    {
        $this->_checkParams($params);
        $this->_params = $params;
    }
    
    public function process()
    {
        $this->_downloadAndGenerateFileList();
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
            $url = sprintf($urlTpl, $date, $i);
            $fileProxy = new NNPG_Utils_FileProxy();
            $fileProxy->setName("KTMPost-$dateInFilename-$i.pdf");
            $fileProxy->setUrl($url);
            $fileProxy->download();
            $fileList[] = $fileProxy->getPath();
        }
        
        return $fileList;
    }
}