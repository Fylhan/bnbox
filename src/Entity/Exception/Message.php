<?php
namespace Bnoger\Entity\Exception;

class Message
{

    /**
     * Données à afficher
     * Soit directement un message
     * Soit un ID corresponant à un message en base de données
     * Soit l'url de redirection (si $type = 2)
     */
    protected $data;

    /**
     * Type de message
     * erreur : pour un message d'erreur
     * ok : pour un message de succès
     * neutre : pour un message d'informations
     */
    protected $type;

    /**
     * Etat de message
     * 0 : non-bloquant, sans redirection
     * 1 : bloquant, sans redirection
     * 2 : redirection (donc bloquant)
     */
    protected $etat;

    /**
     * Endroit sur la page où afficher les données
     * Si vide, à la position par défaut
     * Si non vide, à la position où displayMsgInfo($message, $position)
     */
    protected $position;

    public function __construct($data = '', $type = '', $fonction = 1)
    {
        $this->data = $data;
        $this->type = $type;
        $this->etat = $fonction;
        // $this->position = $position;
    }

    /**
     *
     * @return string
     */
    public function toString()
    {
        $message = NULL;
        if (! $this->isVide()) {
            $message = '<div class="msgInfo ' . $this->getTypeString() . '">
					<h3>' . $this->getTypeHumanString() . '</h3>
					' . $this->getData() . '
				</div>';
        }
        return $message;
    }

    /**
     * Affiche un message d'information ou d'erreur
     */
    public function display($position = '', $return = 0)
    {
        if (! $this->isVide()) {
            // Si on a une data, on regarde si elle existe en BDD pour afficher le message correspondant
            // $qry = 'SELECT type, data, position FROM msg_info WHERE data_id="'.parserS($this->getData()).'" LIMIT 1';
            // @$data = query($qry);
            $data = array();
            
            // Si on a une data et que la position est vide, on cherche la position en base
            $getPositionMsgInfo = $this->getPosition();
            if (count($data) == 1 && ($this->getPosition() == '' || $this->getPosition() == NULL)) {
                $getPositionMsgInfo = $data[0]['position'];
            }
            
            // Si on doit afficher à cet endroit là
            if ($position == $getPositionMsgInfo) {
                // Si on a une data en base
                if (count($data) == 1) {
                    $res = '<div class="msgInfo ' . $data[0]['type'] . '">
						' . $data[0]['data'] . '
					</div>';
                }                 // Sinon, on affiche cette data (c'est peut-être un ID qui n'existe pas en base, mais tant pis)
                else {
                    $res = '<div class="msgInfo ' . ($this->getType() != NULL ? $this->getType() : 'erreur') . '">
						' . $this->getData() . '
					</div>';
                }
                if ($return == 0) {
                    echo $res;
                    // Si c'est un message bloquant, on arrête là
                    if ($this->isBloquant()) {
                        exit();
                    }
                } else {
                    return $res;
                }
            }
        }
    }

    /**
     * Redirige et affiche un message d'information ou d'erreur
     *
     * @param $msgInfo Message
     *            info contenant l'url de redirection et l'id du message à afficher ensuite
     */
    public function rediriger()
    {
        if ($this->isRedirect()) {
            if (! $this->isVide()) {
                header('Location: ' . $this->getData());
                // La valeur du champ "msg" sera affiché sur la page de redirection
            } else {
                header('Location: index.php?msg=ERREUR_REDIRECT');
            }
            exit();
        }
    }
    
    // Accessor
    public function isBloquant()
    {
        return ($this->etat == 1 || $this->etat == 2);
    }

    public function isRedirect()
    {
        return ($this->etat == 2);
    }

    public function isVide()
    {
        return (NULL == $this->data || '' == $this->data);
    }

    public function hasPosition()
    {
        return ($this->position == NULL);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getType()
    {
        return (NULL != $this->type ? $this->type : ERREUR);
    }

    public function getTypeString()
    {
        switch ($this->type) {
            case OK:
                return 'ok';
                break;
            case NEUTRE:
                return 'neutre';
                break;
            default:
                return 'erreur';
        }
    }

    public function getTypeHumanString()
    {
        switch ($this->type) {
            case OK:
                return 'Information';
                break;
            case NEUTRE:
                return 'Information';
                break;
            default:
                return 'Hum...';
        }
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function getNomEtat()
    {
        return ($this->etat == 0 ? 'non bloquant' : ($this->etat == 1 ? 'bloquant' : 'redirection'));
    }

    public function getPosition()
    {
        return $this->position;
    }
    
    // Mutator
    public function setNonBloquant($data = '', $position = '', $type = '')
    {
        $this->etat = 0;
        $this->data = $data;
        $this->type = $type;
        $this->position = $position;
    }

    public function setBloquant($data = '', $position = '', $type = '')
    {
        $this->etat = 1;
        $this->data = $data;
        $this->type = $type;
        $this->position = $position;
    }

    public function setRedirect($url, $data = '', $position = '', $type = '')
    {
        $this->etat = 2;
        $this->data = $url . (preg_match('!\?!', $url) ? '&' : '?') . 'data=' . base64_encode(htmlspecialchars($data)) . '&position=' . base64_encode($position) . '&type=' . $type;
        $this->type = $type;
        $this->position = $position;
    }

    /**
     * Modifie l'état du message
     * 0 : non-bloquant, sans redirection
     * 1 : bloquant, sans redirection
     * 2 : redirection (donc bloquant)
     *
     * @param $msgInfo Message
     *            info à modifier
     * @param $etat Nouvel
     *            état du message
     */
    public function setEtat($etat)
    {
        $this->etat = intval($etat);
    }

    public function setPosition($position)
    {
        $this->position = trim($position);
    }

    public function setType($type)
    {
        $this->type = trim($type);
    }

    public function setData($data)
    {
        $this->data = trim($data);
    }
}

