<?php
namespace Bnoger\Action\Filter;

use Bnoger\Service\CacheManager;

class CacheFilter implements Filterable
{

    protected $_controler;

    protected $cacheManager;

    public function __construct($controler)
    {
        $this->_controler = $controler;
        $this->cacheManager = new CacheManager($this->_controler->getRequest()->getId(), $this->_controler->getRequest()->getModule(), ('administration' != $this->_controler->getRequest()->getModule() && 'upload' != $this->_controler->getRequest()->getModule() && 'POST' != $_SERVER['REQUEST_METHOD']));
    }

    public function preFilter()
    {
        // - Available in the cache
        if (null !== ($pageData = $this->cacheManager->cachedVersion())) {
            $this->_controler->getResponse()->setBody($pageData);
            $this->_controler->getResponse()->printOut();
            return false;
        }
        // - Generate
        // Start the output filter for cache
        ob_start();
        return true;
    }

    public function postFilter()
    {
        if ($this->cacheManager->isCaching()) {
            // - Cache the generated page
            $this->cacheManager->cache(ob_get_contents());
            // - Display the page
            ob_end_flush();
        }
    }
}
