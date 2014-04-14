<?php
namespace Bnbox\Entity;

use Bnoger\Service\FeedWriter\FeedItem;

class Actualite
{

    /**
     *
     * @var integer
     * @access public
     */
    public $id;

    /**
     *
     * @var string
     * @access public
     */
    public $titre;

    /**
     *
     * @var string
     * @access public
     */
    public $contenu;

    /**
     *
     * @var string
     * @access public
     */
    public $extrait;

    /**
     *
     * @var string
     * @access public
     */
    public $dateDebut;

    /**
     *
     * @var string
     * @access public
     */
    protected $dateDebutString;

    /**
     *
     * @var string
     * @access public
     */
    public $dateModif;

    /**
     *
     * @var string
     * @access public
     */
    public $dateFin;

    /**
     *
     * @var string
     * @access public
     */
    protected $dateFinString;

    /**
     *
     * @var boolean
     * @access public
     */
    public $etat = 1;
    
    // -- Constructor
    public function fill($params = array())
    {
        $this->id = isset($params['id']) ? $params['id'] : 0;
        $this->titre = @$params['titre'];
        $this->contenu = @$params['contenu'];
        $this->dateDebut = isset($params['dateDebut']) ? \DateTime::createFromFormat('d/m/Y', $params['dateDebut']) : new \DateTime();
        $this->dateModif = isset($params['dateModif']) ? \DateTime::createFromFormat('d/m/Y', $params['dateModif']) : new \DateTime();
        if (isset($params['dateFin'])) {
            $this->dateFin = \DateTime::createFromFormat('d/m/Y', $params['dateFin']);
        } else {
            $this->dateFin = new \DateTime();
            $this->dateFin->modify('+1 month');
        }
        $this->etat = isset($params['etat']) ? $params['etat'] : 0;
    }
    
    // --- Get/Set
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    public function getExtrait()
    {
        if (NULL == $this->extrait || '' == $this->extrait) {
            $this->computeExtrait();
        }
        return $this->extrait;
    }

    public function setExtrait($extrait)
    {
        $this->extrait = $extrait;
    }

    public function computeExtrait()
    {
        // $pattern = '&lt;\!\-\-More\-\-&gt;';
        $pattern = '\[Lire la suite\]';
        $this->extrait = preg_replace('!^(.*)(?:<br>)?\s*' . $pattern . '(.*)$!isU', '$1<br /><a href="evenement-' . $this->id . '.html" title="Lire la suite' . (NULL != $this->titre && NULL != $this->titre ? ' de &quot;' . str_replace('"', '&quot;', $this->titre) . '&quot;' : '') . '" class="liresuite">Lire la suite...</a></p>', $this->contenu);
        $this->contenu = preg_replace('!^(.*)' . $pattern . '\s*(?:<br>)?(.*)$!is', '$1$2', $this->contenu);
    }

    public function getDateDebut()
    {
        if (! ($this->dateDebut instanceof \DateTime)) {
            if (NULL == $this->dateDebut || '' == $this->dateDebut) {
                $dateDebut = new \DateTime();
            } else {
                $dateDebut = \DateTime::createFromFormat('d/m/Y', $this->dateDebut);
                if (! $dateDebut) {
                    $dateDebut = \DateTime::createFromFormat('Y-m-d', $this->dateDebut);
                }
            }
            $this->dateDebut = $dateDebut;
        }
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut)
    {
        if (! ($dateDebut instanceof \DateTime)) {
            if (NULL == $dateDebut || '' == $dateDebut) {
                $dateDebut = new \DateTime();
            } else {
                $dateDebutTmp = \DateTime::createFromFormat('d/m/Y', $dateDebut);
                if (! $dateDebutTmp) {
                    $dateDebutTmp = \DateTime::createFromFormat('Y-m-d', $dateDebut);
                }
                $dateDebut = $dateDebutTmp;
            }
        }
        $this->dateDebut = $dateDebut;
    }

    public function getDateDebutString()
    {
        if (NULL == $this->dateDebutString || '' == $this->dateDebutString) {
            $this->dateDebutString = dateFr($this->getDateDebut()->getTimestamp(), false, true);
        }
        return $this->dateDebutString;
    }

    public function setDateDebutString($dateDebutString)
    {
        $this->dateDebutString = $dateDebutString;
    }

    public function getDateDebutSql()
    {
        return $this->getDateDebut()->format('Y-m-d');
    }

    public function getDateModif()
    {
        if (! ($this->dateModif instanceof \DateTime)) {
            if (NULL == $this->dateModif || '' == $this->dateModif) {
                $dateModif = new \DateTime();
            } else {
                $dateModif = \DateTime::createFromFormat('d/m/Y', $this->dateModif);
                if (! $dateModif) {
                    $dateModif = \DateTime::createFromFormat('Y-m-d', $this->dateModif);
                }
            }
            $this->dateModif = $dateModif;
        }
        return $this->dateModif;
    }

    public function setDateModif($dateModif)
    {
        if (! ($dateModif instanceof \DateTime)) {
            if (NULL == $dateModif || '' == $dateModif) {
                $dateModif = new \DateTime();
            } else {
                $dateModifTmp = \DateTime::createFromFormat('d/m/Y', $dateModif);
                if (! $dateModifTmp) {
                    $dateModifTmp = \DateTime::createFromFormat('Y-m-d', $dateModif);
                }
                $dateModif = $dateModifTmp;
            }
        }
        $this->dateModif = $dateModif;
    }

    public function getDateModifSql()
    {
        return $this->getDateModif()->format('Y-m-d');
    }

    public function getDateFin()
    {
        if (! ($this->dateFin instanceof \DateTime)) {
            if (NULL == $this->dateFin || '' == $this->dateFin) {
                $dateFin = new \DateTime();
                $dateFin->modify('+1 month');
            } else {
                $dateFin = \DateTime::createFromFormat('d/m/Y', $this->dateFin);
                if (! $dateFin) {
                    $dateFin = \DateTime::createFromFormat('Y-m-d', $this->dateFin);
                }
            }
            $this->dateFin = $dateFin;
        }
        return $this->dateFin;
    }

    public function setDateFin($dateFin)
    {
        if (! ($dateFin instanceof \DateTime)) {
            if (NULL == $dateFin || '' == $dateFin) {
                $dateFin = new \DateTime();
            } else {
                $dateFinTmp = \DateTime::createFromFormat('d/m/Y', $dateFin);
                if (! $dateFinTmp) {
                    $dateFinTmp = \DateTime::createFromFormat('Y-m-d', $dateFin);
                }
                $dateFin = $dateFinTmp;
            }
        }
        $this->dateFin = $dateFin;
    }

    public function getDateFinString()
    {
        if (NULL == $this->dateFinString || '' == $this->dateFinString) {
            $this->dateFinString = dateFr($this->getDateFin()->getTimestamp(), false, false);
        }
        return $this->dateFinString;
    }

    public function setDateFinString($dateFinString)
    {
        $this->dateFinString = $dateFinString;
    }

    public function getDateFinSql()
    {
        return $this->getDateFin()->format('Y-m-d');
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    public function prepareToPrint()
    {
        $this->prepareToPrintForm();
    }

    public function prepareToPrintForm()
    {
        $this->id = parserI($this->id);
        $this->titre = deparserS($this->titre);
        $this->contenu = deparserS($this->contenu);
        $this->extrait = deparserS($this->extrait);
        $this->etat = parserI($this->etat);
    }

    public function prepareToSave()
    {
        $this->id = parserI($this->id);
        $this->titre = parserS($this->titre);
        $this->contenu = parserS($this->contenu);
        $this->extrait = parserS($this->extrait);
        $this->etat = parserI($this->etat);
    }

    public function toFeedItem($excerpt = false, $feedType = RSS2)
    {
        $newItem = new FeedItem($feedType);
        $newItem->setTitle($this->getTitre());
        $newItem->setLink(SITE_PATH . 'actualite-' . $this->getId() . '.html');
        $newItem->setDate($this->getDateDebut());
        $this->computeExtrait();
        $content = '';
        if ($excerpt) {
            $content = $this->getExtrait();
        } else {
            $content = $this->getContenu();
        }
        $content = preg_replace('!<a href="([^h].+)"!iU', '<a href="' . SITE_PATH . '$1"', $content);
        $newItem->setDescription($content);
        $newItem->addElement('author', 'CEP Saint-Maur');
        $newItem->addElement('guid', SITE_PATH . 'actualite-' . $this->getId() . '.html', array(
            'isPermaLink' => 'true'
        ));
        return $newItem;
    }
}
?>