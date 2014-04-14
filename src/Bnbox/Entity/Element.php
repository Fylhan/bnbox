<?php
namespace Bnbox\Entity;

class Element
{

    /**
     *
     * @var integer
     * @access protected
     */
    protected $id;

    /**
     *
     * @var string
     * @access protected
     */
    protected $libelle;

    /**
     * Montant en euro d'une unité de cet élément
     * 
     * @var real
     * @access protected
     */
    protected $montant;

    /**
     * Nombre d'élément à offrir
     * 
     * @var integer
     * @access protected
     */
    protected $nb;

    /**
     * Nombre d'élément restant à offrir
     * 
     * @var integer
     * @access protected
     */
    protected $nbRestant;

    /**
     * Nombre en lequel cet élément est partageable
     * Ex : 8 pour pour qu'il soit partegeable en 8
     * 
     * @var integer
     * @access protected
     */
    protected $nbFraction = 1;

    /**
     * Nombre de part d'un de cet élément restant
     * Ex : 6 s'il reste 6 morceaux sur 8
     * 
     * @var integer
     * @access protected
     */
    protected $nbFractionRestante = 1;

    /**
     * Type de l'élément
     * 
     * @var integer
     * @access protected
     */
    protected $type;
    
    // --- Constructor
    /**
     *
     * @access public
     * @param array $params            
     */
    public function __construct($params = array())
    {
        extract($params);
        $this->id = isset($id) ? $id : $this->id;
        $this->libelle = isset($libelle) ? $libelle : $this->libelle;
        $this->montant = isset($montant) ? $montant : $this->montant;
        $this->nb = isset($nb) ? $nb : $this->nb;
        $this->nbRestant = isset($nbRestant) ? $nbRestant : $this->nbRestant;
        $this->nbFraction = isset($nbFraction) ? $nbFraction : $this->nbFraction;
        $this->nbFractionRestante = isset($nbFractionRestante) ? $nbFractionRestante : $this->nbFractionRestante;
        $this->type = isset($type) ? $type : $this->type;
    }
    
    // --- Get/Set
    /**
     *
     * @return
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param
     *            $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return
     *
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     *
     * @param
     *            $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     *
     * @return
     *
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     *
     * @param
     *            $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     *
     * @return
     *
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     *
     * @param
     *            $nb
     */
    public function setNb($nb)
    {
        $this->nb = $nb;
    }

    /**
     *
     * @return
     *
     */
    public function getNbRestant()
    {
        return $this->nbRestant;
    }

    /**
     *
     * @param
     *            $nbRestant
     */
    public function setNbRestant($nbRestant)
    {
        $this->nbRestant = $nbRestant;
    }

    /**
     *
     * @return
     *
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param
     *            $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function getNbFraction()
    {
        return $this->nbFraction;
    }

    public function setNbFraction($nbFraction)
    {
        $this->nbFraction = $nbFraction;
    }

    public function getNbFractionRestante()
    {
        return $this->nbFractionRestante;
    }

    public function setNbFractionRestante($nbFractionRestante)
    {
        $this->nbFractionRestante = $nbFractionRestante;
    }
}
?>