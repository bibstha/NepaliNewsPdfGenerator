<?php
require_once("NNPG/Utils/FileProxy.php");
require_once("NNPG/Utils/ImageToPDF.php");

class NNPG_Filter_CollectorRepublica
{
    protected $_params;
    protected $_name = 'Republica';
    
    public function __construct($params)
    {
        $this->_checkParams($params);
        $this->_filterParams($params);
        $this->_params = $params;
    }
    
    public function process()
    {
        return array(
            'inPaths' => $this->_downloadAndGenerateFileList(),
            'outPath' => FILE_PATH . '/combined/' . $this->_name . '/' . 
                $this->_getDateForFileName() . '.pdf',
        );
    }
    
    /**
     * Check if mandatory parameters are set or not, does not modify params variable
     * 
     * @param array $params
     * @throws Exception
     */
    protected function _checkParams($params)
    {
        // required : date (any format that is recognized by strtotime)
        if (!isset($params['date'])) throw new Exception("Parameter 'date' not found");
    }
    
    /**
     * Modification in $params can only occur in filterParams
     * 
     * @param array $params
     */
    protected function _filterParams(&$params)
    {
        $params['date'] = strtotime($params['date']);
        if (!isset($params['pageCount'])) $params['pageCount'] = 16;
    }
    
    protected function _downloadAndGenerateFileList()
    {
        $imgToPdf = new NNPG_Util_ImageToPDF();
        
        $dateInFilename = $this->_getDateForFileName();
        $numOfPages = $this->_params['pageCount'];

        $fileList = array();
        for ($i = 1; $i <= $numOfPages; $i++) {
            $dateForFileName = $this->_getDateForFileName();
            $imageFilePath = 'single/' . $this->_name . "/$dateForFileName/p$i.jpg";
            $url = $this->_getUrl($i);
            $fileProxy = new NNPG_Utils_FileProxy();
            $fileProxy->setName($imageFilePath);
            $fileProxy->setUrl($url);
            $isSuccess = $fileProxy->download();
            $imagePath = $fileProxy->getPath();
            $pdfPath = FILE_PATH . '/single/' . $this->_name . "/$dateForFileName/p$i.pdf";
            $imgToPdf->convert($imagePath, $pdfPath);
            $fileList[] = $pdfPath;
        }
        return $fileList;
    }
    
    protected function _getUrl($pageNumber)
    {
        $date = strtolower( date('Y_M_d', $this->_params['date']) );
        $urlTpl = 'http://e.myrepublica.com/images/flippingbook/%s/republica/rp_zoom_%02d.jpg';
        return sprintf($urlTpl, $date, $pageNumber);
    }
    
    protected function _getDateForFileName()
    {
        return date('Y-m-d', $this->_params['date']);
    }
}