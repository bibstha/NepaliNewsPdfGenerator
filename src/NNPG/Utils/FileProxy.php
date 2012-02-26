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
        // @todo change this caching mechanism
        $this->setPath(FILE_PATH . '/' . $this->_name);
        if (file_exists($this->_path)) return;
        if (!file_exists(dirname($this->_path)))
            mkdir(dirname($this->_path), 0777, true);
        $fp = fopen($this->_path, 'w+');
        $ch = curl_init($this->_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        if ($httpCode == 404) {
            unlink($this->getPath());
            throw new Exception("Expected file not found : " . $this->getPath());
        }
    }
}