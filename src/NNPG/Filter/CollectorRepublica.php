<?php
class NNPG_Filter_CollectorRepublica extends NNPG_Filter_CollectorAbstract
{
    protected $_name = 'Republica';
    protected $_pages = array();
    
    protected function _getUrl($pageNumber)
    {
        
    }

    protected function _checkAndFilterParams($params)
    {
        if (!isset($params['date'])) throw new Exception("Parameter 'date' should be set");
    }

    protected function _processInput()
    {
        // Do initialization by crawling republica website
        $this->_crawlAndExtractRepublica();
        
        $imgToPdf = new NNPG_Utils_ImageToPDF();
        
        $dateInFilename = $this->_getDateForFileName();
        $numOfPages = $this->_getPageCount();
        
        $fileList = array();
        $filePathTpl = 'single/%1$s/%2$s/%3$s';
        for ($i = 1; $i <= $numOfPages; $i++) {
            $filePath = sprintf($filePathTpl, $this->_name, $dateInFilename, "p$i.jpg");
            print $filePath . "\n";
            $url = $this->_pages[$i-1];
            $fileProxy = new NNPG_Utils_FileProxy();
            $fileProxy->setName($filePath);
            $fileProxy->setUrl($url);
            $isSuccess = $fileProxy->download();
            $imagePath = $fileProxy->getPath();
            $pdfPath = FILE_PATH . '/single/' . $this->_name . "/$dateInFilename/p$i.pdf";
            $imgToPdf->convert($imagePath, $pdfPath);
            $fileList[] = $pdfPath;
        }
        
        return $fileList;
    }
    
    protected function _getDate()
    {
        // Not used
        // return date('jnY', strtotime($this->_params['date']) );
    }

    protected function _getPageCount()
    {
        return count($this->_pages);
    }
    
    protected function _crawlAndExtractRepublica()
    {
        // page with link to individual prints
        $xml = file_get_contents('http://e.myrepublica.com');
        preg_match_all('#href="/component/flippingbook/book/(.*)"#U', $xml, $matches);
        
        // Change latestUrl to get date-wise-url
        $latestUrl = 'http://e.myrepublica.com/component/flippingbook/book/' . $matches[1][0];
        
        // page with one print
        $xml = file_get_contents($latestUrl);
        preg_match("#flippingBook[0-9]+.enlargedImages = \[(.*)\]#sU", $xml, $matches);
        $pages = $matches[1];
        preg_match_all("#/images/.*\.jpg#U", $pages, $matches);
        foreach ($matches[0] as $key => $match) {
            $this->_pages[$key] = 'http://e.myrepublica.com' . $match;
        }
    }
}
