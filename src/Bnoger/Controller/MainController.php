<?php
namespace Bnoger\Controller;

use Bnoger\Entity\Request;
use Bnoger\Entity\Response;
use Bnoger\Action\Filter\Filterable;
use Bnoger\Action\Filter\SecurityFilter;
use Bnoger\Action\Filter\CacheFilter;
use Bnoger\Entity\Exception\NotFoundActionException;

class MainController
{

    /**
     *
     * @var \Bnoger\Entity\Request
     */
    private $request;

    /**
     *
     * @var \Bnoger\Entity\Response
     */
    private $response;

    private $tplManager;

    private $route;

    private $filters = array();

    private static $_instance = null;
    
    // --- Constructor
    private function __construct()
    {
        $this->filters = array();
        $this->route = array(
            'bundle' => 'bnbox',
            'module' => 'home',
            'action' => 'home'
        );
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
        
        // Préparation du moteur de template
        $loader = new \Twig_Loader_Filesystem(array(
            TPL_PATH,
            WEB_PATH4SRC . '/js/'
        ));
        $this->tplManager = new \Twig_Environment($loader, array(
            'cache' => CACHE_PATH . '/twig',
            'debug' => DEBUG,
            'charset' => CHARSET
        ));
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    // --- Coeur
    public function dispatch($route = null)
    {
        try {
            // - Action par défaut
            if (null != $route && '' != $route && count($route) > 0) {
                $this->route = $route;
            }
            
            // - Analyse la requête reçue pour trouver l'action à effectuer
            $parsed = $this->request->route($this->route);
            
            // - Add filters
            // Cache
            $cacheFilter = new CacheFilter($this);
            $this->addFilter($cacheFilter);
            // Security
            $securityFilter = new SecurityFilter($this);
            $this->addFilter($securityFilter);
            
            // - Prefilter
            $pass = true;
            foreach ($this->filters as $filter) {
                $pass *= $filter->preFilter();
            }
            
            if ($pass) {
                // - Action
                $this->forward($parsed['bundle'], $parsed['module'], $parsed['action']);
                
                // - Postfilter
                foreach (array_reverse($this->filters) as $filter) {
                    $filter->postFilter();
                }
            }
        } catch (Exception $e) {
            $this->_launchException($e)->printOut();
        }
    }

    public function forward($bundle, $module, $action, $params = null)
    {
        $action = $this->_getAction($bundle, $module, ('default' == $module ? 'Static' : $action));
        $action->launch($params);
    }

    public function redirect($url, $end = true, $permanent = false)
    {
        $this->response->redirect($url, $end, $permanent);
    }

    public function render($tplPath, $params = array())
    {
        global $app;
        // -- Ajout des params par défaut
        $globalParams = array(
            'JS_PATH' => JS_PATH,
            'CSS_PATH' => CSS_PATH,
            'IMG_PATH' => IMG_PATH,
            'FEED_PATH' => FEED_PATH,
            'StyleEnabled' => StyleEnabled,
            'DebugEnabled' => DEBUG,
            'app' => $app,
            'page' => $this->response->getPage(),
            'flash' => $this->response->getFlash()
        );
        // -- Préparation des paramêtres
        $this->response->addVars($globalParams);
        $this->response->addVars($params);
        
        // -- Minify CSS and JS (TODO: move somewhere else)
        if (DEBUG) {
            $cssFiles = array(
                'ie',
                'style',
                'highlight-default',
                'redactor'
            );
            foreach ($cssFiles as $cssFile) {
                if (DEBUG || ! is_file(WEB_PATH4SRC . '/css/' . $cssFile . '.min.css')) {
                    file_put_contents(WEB_PATH4SRC . '/css/' . $cssFile . '.min.css', minifyCss(file_get_contents(WEB_PATH4SRC . '/css/' . $cssFile . '.css')));
                }
            }
            $jsFiles = array(
                'html5',
                'contact'
            );
            foreach ($jsFiles as $jsFile) {
                if (DEBUG || ! is_file(WEB_PATH4SRC . '/js/' . $jsFile . '.min.js')) {
                    file_put_contents(WEB_PATH4SRC . '/js/' . $jsFile . '.min.js', minifyJs(file_get_contents(WEB_PATH4SRC . '/js/' . $jsFile . '.js')));
                }
            }
        }
        
        // -- Création du template
        $tpl = $this->tplManager->loadTemplate($tplPath);
        $body = $tpl->render($this->response->getVars());
        // -- Ajout du body
        $this->response->setBody($body);
    }

    public function addFilter(Filterable $filter)
    {
        $this->filters[] = $filter;
    }

    private function _getAction($bundle, $module, $action)
    {
        if (! file_exists($path = SRC_PATH . '/' . ucfirst($bundle) . '/Action/' . ucfirst($module) . '/' . ucfirst($action) . 'Action.php')) {
            $this->response->addVar('bundle', $bundle);
            $this->response->addVar('module', $module);
            $this->response->addVar('action', $action);
            throw new NotFoundActionException('Action inconnue : ' . ucfirst($bundle) . '/Action/' . ucfirst($module) . '/' . ucfirst($action) . 'Action.php');
        }
        // require_once ($path);
        $class = '\\' . ucfirst($bundle) . '\\Action\\' . ucfirst($module) . '\\' . ucfirst($action) . 'Action';
        return new $class($this);
    }

    private function _launchException($e)
    {
        $error = $e->__toString();
        $this->response->addVar('error', $error);
        if ($e instanceof MVCException) {
            $this->response->addVar('metaTitle', 'Erreur - Page introuvable');
            $this->response->addVar('page', $e->getPage());
            $this->render('error/404.tpl');
        } else {
            $this->response->addVar('metaTitle', 'Erreur critique');
            $this->render('error/500.tpl');
        }
        logThatException($e);
        return $this->response;
    }

    /**
     *
     * @return \Bnoger\Entity\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     *
     * @return \Bnoger\Entity\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
?>