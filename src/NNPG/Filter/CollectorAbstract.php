<?php
require_once("NNPG/Utils/FileProxy.php");
require_once("NNPG/Utils/ImageToPDF.php");

abstract class NNPG_Filter_CollectorAbstract implements NNPG_Filter_Interface
{
    protected $_params;
    protected $_name;
    protected $_input;
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setParams($params)
    {
        $this->_checkAndFilterParams($params);
        $this->_params = $params;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function setInput($input)
    {
        $this->_input = $input;
    }
    
    final public function process()
    {
        return $this->_processInput();
    }
    
    public function _getDateForFileName()
    {
        return date('Y-m-d', strtotime($this->_params['date']));
    }
    
    /**** Unimplemented Methods ****/
    abstract protected function _getUrl($pageNumber);
    
    /**
     * Check if mandatory parameters are set or not, does not modify params variable
     * 
     * @param array $params
     * @throws Exception
     */
    abstract protected function _checkAndFilterParams($params);
    
    abstract protected function _processInput();
    
    abstract protected function _getDate();
}
