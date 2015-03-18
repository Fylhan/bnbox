<?php
namespace Bnoger\Entity\Accessor;

use Bnoger\Entity\Exception\DAOException;

class DBAccessor
{

    private static $_instance;

    private $db;
    
    // --- Constructor
    /**
     *
     * @access private
     * @param array $params            
     */
    private function __construct($params = array())
    {
        $this->open();
    }

    /**
     * Permet d'instancier la classe
     *
     * @access public
     * @param array $params            
     * @return DBAccessor
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setInstance($value)
    {
        $this->instance = $value;
    }

    /**
     *
     * @access public
     * @return void
     */
    public function open()
    {
        // Permet de remplacer & par &amp; pour la validation xhtml/CSS
        // ini_set('arg_separator.output','&amp;');
        // Un truc de session bizarre pour la validation xhtml/CSS
        // ini_set('url_rewriter.tags','get=fakeentry');
        try {
            $pdoOptions[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
            $this->db = new \PDO('mysql:host=' . BDD_HOST . ';dbname=' . BDD_NAME, BDD_USER, BDD_MDP, $pdoOptions);
        } catch (Exception $e) {
            throw new DAOException('Erreur lors de l\'accès à la base de données', '1', $e);
        }
    }

    /**
     *
     * @access public
     * @return void
     */
    public function close()
    {}

    /**
     * Exécute une requête et renvoie la liste d'objets désirés
     *
     * @access public
     * @param PDOStatement $preparedQuery
     *            Requête préparée
     * @param array $params
     *            Liste des paramètres de la requêtes
     */
    public function executeQuery(\PDOStatement $preparedQuery, $params)
    {
        $preparedQuery->execute($params);
        return $preparedQuery;
    }
    
    // --- Get/Set
    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }
}
