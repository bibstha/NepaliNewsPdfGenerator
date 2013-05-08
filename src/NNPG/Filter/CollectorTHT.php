<?php
class NNPG_Filter_CollectorTHT extends NNPG_Filter_CollectorAbstract
{
    const TAG = "Filter_CollectorTHT";

    protected $_name = 'THT';
    protected $_pages = array();
    
    /**
     * Calculates the URL of a single PDF page with pagenumber
     */
    protected function _getUrl($pageNumber)
    {
        $dateInFirstFormat = date('Y/m/d', $this->_getDate());
        $dateInSecondFormat = date('d_m_Y', $this->_getDate());
        $pageNumber = sprintf("%03d", $pageNumber);
        $plaintextSubString = $dateInSecondFormat . "_" . $pageNumber . "_pressguess";
        $encryptedUrlPart = sprintf("%s_%s_%s", $dateInSecondFormat, $pageNumber, md5($plaintextSubString));
        $url = sprintf('http://www.epaper.thehimalayantimes.com/PUBLICATIONS/THT/THT/%1$s/PagePrint/%2$s.pdf',
            $dateInFirstFormat, $encryptedUrlPart);
        return $url;

    }

    protected function _checkAndFilterParams($params)
    {
        if (!isset($params['date'])) throw new Exception("Parameter 'date' should be set");
    }

    protected function _processInput()
    {
        if ($this->_getDate() < strtotime("2013-05-02"))
        {
            NNPG_Utils_Log::d(self::TAG, "THT crawler only supports date after 2013-05-02");
            exit(0);
        }

        $this->_crawlAndExtract();
        
        // We do not know how many pages, we continue till we get a pdf
        $numOfPages = 40;
        $dateInFilename = $this->_getDateForFileName();
        $fileList = array();
        $filePathTpl = 'single/%1$s/%2$s/%3$s';
        for ($i = 1; $i <= $numOfPages; $i++) {
            $filePath = sprintf($filePathTpl, $this->_name, $dateInFilename, "p$i.pdf");
            print "Downloading : " . $filePath . "\n";
            try
            {
                $url = $this->_getUrl($i);
                $fileProxy = new NNPG_Utils_FileProxy();
                $fileProxy->setName($filePath);
                $fileProxy->setUrl($url);
                $fileProxy->download();
                $fileList[] = $fileProxy->getPath();
            }
            catch (Exception $e) {
                // We are expecting a lot of file not found exceptions
                NNPG_Utils_Log::d(self::TAG, $e->getMessage());
                continue;
            }
        }
        
        return $fileList;
    }
    
    protected function _getDate()
    {
        return strtotime($this->_params['date']);
    }
    
    protected function _crawlAndExtract()
    {
        // Current version doesn't know how many pages there are :(
        $this->_pages = $matches[0];
    }

    protected function _getPageCount()
    {
        return count($this->_pages);
    }
}
