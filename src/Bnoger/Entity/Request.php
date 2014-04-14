<?php
namespace Bnoger\Entity;

class Request
{

    protected $_route;

    protected $_params;

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

    public function route($defaults = null)
    {
        // $requestUri = substr($_SERVER['REQUEST_URI'],
        // strpos($_SERVER['REQUEST_URI'],'/'.basename(__FILE__)) +
        // strlen('/'.basename(__FILE__))
        // );
        // if (empty($requestUri)) {
        // return array();
        // }
        
        // $path = parse_url($requestUri, PHP_URL_PATH);
        // preg_match('#^(/(?P<module>\w+))(/(?P<action>\w+)/?)?$#', $path, $matches);
        
        // $args = explode('&', parse_url($requestUri, PHP_URL_QUERY));
        // $matches['args'] = $args;
        $module = getModule();
        $action = getAction();
        $this->_route = array(
            'module' => (NULL != $module ? $module : $defaults['module']),
            'action' => (NULL != $action ? $action : $defaults['action'])
        );
        return $this->_route;
    }

    public function isParam($key, $value = NULL)
    {
        return (NULL != $this->getParam($key) && (NULL == $value || $value == $this->getParam($key)));
    }

    public function getParam($key, $type = 'string')
    {
        if (NULL != $this->getParams() && isset($this->_params[$key])) {
            $var = $this->_params[$key];
            if (0 == strcasecmp('int', $type) || 0 == strcasecmp('integer', $type)) {
                return parserI($var);
            } else 
                if (0 == strcasecmp('float', $type) || 0 == strcasecmp('double', $type)) {
                    return parserF($var);
                }
            return parserS($var);
        }
        return NULL;
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getModule()
    {
        return $this->_route['module'];
    }

    public function getAction()
    {
        return $this->_route['action'];
    }

    public function setRoute($_route)
    {
        $this->_route = $_route;
    }

    public function getId()
    {
        return getModule() . '_' . getAction() . '_' . (! empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '');
    }

    public function getParams()
    {
        // Not yet computed: secure vars
        if (NULL == $this->_params || count($this->_params) <= 0) {
            $this->_params = array();
            foreach ($_POST as $key => $val) {
                $this->_params[$key] = parserGeneric($val);
            }
            foreach ($_GET as $key => $val) {
                $this->_params[$key] = parserGeneric($val);
            }
        }
        return $this->_params;
    }
}
