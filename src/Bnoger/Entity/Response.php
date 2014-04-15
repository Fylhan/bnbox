<?php
namespace Bnoger\Entity;

class Response
{

    private $_blocks = array();

    private $_vars = array();

    private $_page = array();

    private $_headers = array();

    private $_body;

    private static $_instance = null;

    private function __construct()
    {}

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function redirect($url, $end = true, $permanent = false)
    {
        if ($permanent) {
            $this->_headers['Status'] = '301 Moved Permanently';
        } else {
            $this->_headers['Status'] = '302 Found';
        }
        $this->_headers['location'] = $url;
        if (NULL != $this->_vars && count($this->_vars) > 0) {
            $this->_headers['location'] .= '?' . http_build_query($this->_vars);
        }
        
        if ($end) {
            $this->printOut();
            exit();
        }
    }

    public function printOut()
    {
        foreach ($this->_headers as $key => $value) {
            header($key . ':' . $value);
        }
        echo $this->_body;
    }

    public function addPage($key, $value)
    {
        $this->_page[$key] = $value;
    }

    public function getPageValue($key)
    {
        return $this->_page[$key];
    }

    public function getPage()
    {
        return $this->_page;
    }

    public function addVar($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    public function addVars($array)
    {
        $this->_vars = array_merge($this->_vars, $array);
    }

    public function getVar($key)
    {
        return array_key_exists($key, $this->_vars) ? $this->_vars[$key] : '';
    }

    public function getVars()
    {
        return $this->_vars;
    }

    public function setFlash($value)
    {
        $_SESSION['flashNb'] = 0;
        $_SESSION['flash'] = $value;
    }

    public function getFlash()
    {
        if (isset($_SESSION['flashNb']) && $_SESSION['flashNb'] >= 1) {
            unset($_SESSION['flash']);
            return '';
        }
        $_SESSION['flashNb'] = @$_SESSION['flashNb'] + 1;
        return @$_SESSION['flash'];
    }

    public function addHeader($key, $value)
    {
        $this->_headers[$key] = $value;
    }

    public function setBody($value)
    {
        $this->_body = $value;
    }
}
