<?php
namespace Bnoger\Entity\Accessor;

class DataAccessor
{

    protected $elementDao;

    protected $cadeauDao;

    protected $cadeauElementsDao;

    protected $typeDao;

    /**
     *
     * @access public
     * @param array $params            
     */
    public function __construct($params = array())
    {}

    /**
     * Ferme l'accès aux données
     * 
     * @access public
     */
    public function close()
    {}
    
    // setters / getters
}
