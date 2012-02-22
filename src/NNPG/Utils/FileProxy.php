<?php
class NNPG_Utils_FileProxy
{
    protected $_name;
    protected $_url;
    protected $_path;
    
    /**
     * @return the $_name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return the $_url
     */
    public function getUrl()
    {
        return $this->_url;
    }

	/**
     * @return the $_path
     */
    public function getPath()
    {
        return $this->_path;
    }

	/**
     * @param field_type $_name
     */
    public function setName($_name)
    {
        $this->_name = $_name;
    }

	/**
     * @param field_type $_url
     */
    public function setUrl($_url)
    {
        $this->_url = $_url;
    }

	/**
     * @param field_type $_path
     */
    public function setPath($_path)
    {
        $this->_path = $_path;
    }

    public function download()
    {
        $this->_downloadFile();
    }
    
    protected function _downloadFile()
    {
        $fp = fopen(FILE_PATH . '/' . $this->_name, 'w+');
        $ch = curl_init($this->_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }
}