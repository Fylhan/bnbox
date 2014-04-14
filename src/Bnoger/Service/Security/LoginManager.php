<?php
namespace Bnoger\Service\Security;

use Bnoger\Entity\Exception\Message;

class LoginManager
{

    private $securityKey;

    private $parentController;

    /**
     *
     * @param string $securityKey            
     */
    public function __construct($securityKey, $parentController = NULL)
    {
        $this->securityKey = $securityKey;
        $this->parentController = $parentController;
    }

    public function isLoggedIn()
    {
        // -- No session, bad session, or session expired
        if (empty($_SESSION['uid']) || $_SESSION['ip'] != $this->allIPs() || $_SESSION['expiresOn'] <= time()) {
            $this->logout();
            return false;
        }
        // -- Logged: increase session duration and return true
        // Keep connected
        if (isset($_SESSION['keepConnected']) && $_SESSION['keepConnected']) {
            $_SESSION['expiresOn'] = time() + SessionTimeoutKeepConnected;
        }         // Normal (need activites in a SessionTimeoutNormal hours interval, and no browser closed, to stay connected)
        else {
            $_SESSION['expiresOn'] = time() + SessionTimeoutNormal;
        }
        return true;
    }

    public function login()
    {
        // -- Security key filled
        if ((isset($_POST['securityKey']) && NULL != $_POST['securityKey'] && '' != $_POST['securityKey'])) {
            // - Is banished
            $banManager = new BanManager(CACHE_PATH . '/ban.json', LoginTryNumber, LoginBanishedTimeout);
            if ($banManager->isBannished()) {
                // - Password error
                if (NULL != $this->parentController) {
                    $message = new Message('Trop d\'erreurs consécutives, vous voilà banni pour quelques temps ! Si vous avez un problème, vous savez qui contacter, non ?', ERREUR);
                    $this->parentController->getResponse()->addVar('Message', $message->toString());
                }
                return false;
            }
            
            $securityKey = parserS(trim($_POST['securityKey']));
            $passwordChecker = new Bcrypt();
            // - Password ok: save it and continue
            if ($passwordChecker->verify($securityKey, $this->securityKey)) {
                // Signal success to ban management
                $banManager->resetIp();
                // Store this in session
                $_SESSION['uid'] = sha1(uniqid('', true) . '_' . mt_rand()); // generate unique random number (different than phpsessionid)
                $_SESSION['ip'] = $this->allIPs(); // We store IP address(es) of the client to make sure session is not hijacked.
                                                   // $_SESSION['securityKey'] = SecurityKey;
                $_SESSION['role'] = 'Administrateur';
                // Keep connected
                if (isset($_POST['keepConnected']) && parserI($_POST['keepConnected'])) {
                    $_SESSION['keepConnected'] = true;
                    $_SESSION['expiresOn'] = time() + SessionTimeoutKeepConnected;
                    // Set session cookie expiration on client side and Send cookie with new expiration date to browser.
                    // Note: Never forget the trailing slash on the cookie path !
                    session_set_cookie_params(SessionTimeoutKeepConnected, dirname($_SERVER["SCRIPT_NAME"]) . '/');
                    session_regenerate_id(true); //
                }                 // Standard session expiration (=when browser closes)
                else {
                    $_SESSION['expiresOn'] = time() + SessionTimeoutNormal;
                    // Set session cookie expiration on client side and Send cookie with new expiration date to browser.
                    // Note: Never forget the trailing slash on the cookie path !
                    // 0 means "When browser closes"
                    session_set_cookie_params(0, dirname($_SERVER["SCRIPT_NAME"]) . '/');
                    session_regenerate_id(true);
                }
                // -- Continue after prefilter
                return true;
            }
            // - Password error
            // Signal faillure to ban management
            $banManager->addFaillure();
            // Signal error message
            if (NULL != $this->parentController) {
                $message = new Message('Arg, mot de passe incorrect.', ERREUR);
                $this->parentController->getResponse()->addVar('Message', $message->toString());
            }
        }
        return false;
    }

    public function logout()
    {
        if (isset($_SESSION)) {
            unset($_SESSION['uid']);
            unset($_SESSION['ip']);
            unset($_SESSION['role']);
        }
    }

    /**
     * Returns the IP address of the client (Used to prevent session cookie hijacking.)
     * From Shaarli: http://sebsauvage.net/wiki/doku.php?id=php:shaarli
     * Licence: http://www.opensource.org/licenses/zlib-license.php
     */
    private function allIPs()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        // Then we use more HTTP headers to prevent session hijacking from users behind the same proxy.
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $ip . '_' . $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $ip . '_' . $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }
}
