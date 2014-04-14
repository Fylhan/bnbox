<?php
namespace Bnoger\Entity\Exception;

class MsgException extends \Exception
{

    public static $OK_REDIRECTION = 1;

    public static $OK_NONBLOQUANT = 2;

    public static $OK_BLOQUANT = 3;

    public static $ERREUR_BLOQUANT = 0;

    public static $ERREUR_NONBLOQUANT = - 1;

    public static $ERREUR_REDIRECTION = - 2;

    /**
     *
     * @access public
     * @param string $message[optional]            
     * @param integer $code[optional]            
     * @param Exception $previous[optional]            
     */
    public function __construct($message = '', $code, $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
}
