<?php

class NNPG_Filter_CollectorKTMPost extends NNPG_Filter_CollectorAbstract
{
    protected $_name = 'KTMPost';
    
    protected function _getUrl($pageNumber)
    {
        $date = $this->_getDate();
        return sprintf('http://epaper.ekantipur.com/ktpost/%1$s/epaperpdf/%1$s-md-hr-%2$d.pdf', $date, $pageNumber);
    }

    protected function _checkAndFilterParams($params)
    {
        if (!isset($params['date'])) throw new Exception("Parameter 'date' should be set");
    }

    protected function _processInput()
    {
        $date = $this->_getDate();
        $dateInFilename = $this->_getDateForFileName();
        $numOfPages = $this->_getPageCount();
        
        $fileList = array();
        $filePathTpl = 'single/%1$s/%2$s/%3$s';
        for ($i = 1; $i <= $numOfPages; $i++) {
            $filePath = sprintf($filePathTpl, $this->_name, $dateInFilename, "p$i.pdf");
            print $filePath . "\n";
            $url = $this->_getUrl($i);
            $fileProxy = new NNPG_Utils_FileProxy();
            $fileProxy->setName($filePath);
            $fileProxy->setUrl($url);
            $fileProxy->download();
            $fileList[] = $fileProxy->getPath();
        }
        
        return $fileList;
    }
    
    protected function _getDate()
    {
        return date('jnY', strtotime($this->_params['date']) );
    }

    protected function _getPageCount()
    {
        $xml = file_get_contents( sprintf('http://epaper.ekantipur.com/ktpost/%s/pages.xml', $this->_getDate()) );
        $count = (int)substr_count($xml, '<page>');
        return $count?$count:16;
    }
}
