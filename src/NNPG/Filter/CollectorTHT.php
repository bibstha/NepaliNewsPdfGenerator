<?php
class NNPG_Filter_CollectorTHT extends NNPG_Filter_CollectorAbstract
{
    protected $_name = 'THT';
    protected $_pages = array();
    
    protected function _getUrl($pageNumber)
    {
        $date1 = $this->_getDate();
        return sprintf('http://www.epaper.thehimalayantimes.com/PUBLICATIONS/THT/THT/%1$s/PagePrint/%2$s.pdf',
            $date1, $this->_pages[$pageNumber-1]);
    }

    protected function _checkAndFilterParams($params)
    {
        if (!isset($params['date'])) throw new Exception("Parameter 'date' should be set");
    }

    protected function _processInput()
    {
        $this->_crawlAndExtract();
        $date = $this->_getDate();
        $dateInFilename = $this->_getDateForFileName();
        $numOfPages = $this->_getPageCount();
        
        $fileList = array();
        $filePathTpl = 'single/%1$s/%2$s/%3$s';
        for ($i = 1; $i <= $numOfPages; $i++) {
            $filePath = sprintf($filePathTpl, $this->_name, $dateInFilename, "p$i.pdf");
            print "Downloading : " . $filePath . "\n";
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
        return date('Y/m/d', strtotime($this->_params['date']));
    }
    
    protected function _crawlAndExtract()
    {
        $xml = file_get_contents( sprintf('http://www.epaper.thehimalayantimes.com/PUBLICATIONS/THT/THT/%s/index.shtml', 
            $this->_getDate()) );
        preg_match_all("#pagethumb/([0-9_]+)\.JPG#U", $xml, $matches);
        $this->_pages = $matches[1];
    }

    protected function _getPageCount()
    {
        return count($this->_pages);
    }
}
