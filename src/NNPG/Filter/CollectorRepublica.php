<?php
class NNPG_Filter_CollectorRepublica extends NNPG_Filter_CollectorAbstract
{
    const TAG = 'CollectorRepublica';
    protected $_name = 'Republica';
    protected $_pages = array();
    
    protected function _getUrl($pageNumber)
    {
        
    }

    protected function _checkAndFilterParams($params)
    {
        if (!isset($params['date']))
        {
            NNPG_Utils_Log::e(self::TAG, "Parameter 'date' should be set");
            throw new Exception("Parameter 'date' should be set");
        }
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
    
    /**
     * @Override
     * @see(NNPG_Filter_CollectorNagarik::_getDate)
     */
    protected function _getDate()
    {
        return strtotime($this->_params['date']);
    }

    protected function _getPageCount()
    {
        return count($this->_pages);
    }
    
    protected function _crawlAndExtractRepublica()
    {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                    "Accept-Encoding: gzip\r\n"
            )
        );
        $context = stream_context_create($opts);

        // page with link to individual prints
        NNPG_Utils_Log::d(self::TAG, "Downloading http://e.myrepublica.com/");
        $xml = file_get_contents('http://e.myrepublica.com', false, $context);
        preg_match_all('#href="/component/flippingbook/book/(.*-republica-(.*)/.*)"#U', $xml, $matches);
        
        NNPG_Utils_Log::d(self::TAG, "Check if we have date " . date('Y-m-d', $this->_getDate()));
        $dateFound = false;
        foreach ($matches[2] as $index => $possibleDate)
        {
            if ($this->_getDate() == strtotime($possibleDate))
            {
                $dateFound = true;
                break;
            }
        }
        NNPG_Utils_Log::d(self::TAG, $dateFound?"Date found.":"Date not found.");

        if ($dateFound)
        {
            // Change latestUrl to get date-wise-url
            $latestUrl = 'http://e.myrepublica.com/component/flippingbook/book/' . $matches[1][$index];
            
            // page with one print
            $xml = file_get_contents($latestUrl, false, $context);
            preg_match("#flippingBook[0-9]+.enlargedImages = \[(.*)\]#sU", $xml, $matches);
            $pages = $matches[1];
            preg_match_all("#/images/.*\.jpg#U", $pages, $matches);
            foreach ($matches[0] as $key => $match) {
                $this->_pages[$key] = 'http://e.myrepublica.com' . $match;
            }
        }
    }
}
