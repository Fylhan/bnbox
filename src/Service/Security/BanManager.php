<?php
namespace Bnoger\Service\Security;

class BanManager
{

    private $banFilename;

    private $faillureNbAuthorized;

    private $banishementTimeout;

    private $banList;

    /**
     *
     * @param string $banFilename
     *            Path to the file that stores data about ban management
     * @param int $faillureTryAuthorized
     *            Number of faillure authorized before banish the IP
     * @param int $banishementTimeout
     *            Time of the banishment
     */
    public function __construct($banFilename, $faillureNbAuthorized = 10, $banishementTimeout = 1800)
    {
        $this->banFilename = $banFilename;
        $this->faillureNbAuthorized = $faillureNbAuthorized;
        $this->banishementTimeout = $banishementTimeout;
        $this->banList = json_decode(file_get_contents($this->banFilename));
    }

    /**
     * Signal a failed login.
     * Will ban the IP if too many failures:
     */
    public function addFaillure()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (false === ($ipKey = array_search($ip, $this->banList->faillure))) {
            $this->banList->faillure[] = $ip;
            $this->banList->faillureNb[] = 0;
            $ipKey = array_search($ip, $this->banList->faillure);
        }
        $this->banList->faillureNb[$ipKey] ++;
        if ($this->banList->faillureNb[$ipKey] > ($this->faillureNbAuthorized - 1)) {
            $this->banList->banished[] = $ip;
            $this->banList->banishedTimeout[] = time() + $this->banishementTimeout;
        }
        $this->updateBanFile(json_encode($this->banList));
    }

    /**
     * Signals a successful login.
     * Resets failed login counter.
     */
    public function resetIp()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (false !== ($ipKey = array_search($ip, $this->banList->faillure))) {
            array_splice($this->banList->faillure, $ipKey);
            array_splice($this->banList->faillureNb, $ipKey);
        }
        if (false !== ($ipKey = array_search($ip, $this->banList->banished))) {
            array_splice($this->banList->banished, $ipKey);
            array_splice($this->banList->banishedTimeout, $ipKey);
        }
        $this->updateBanFile(json_encode($this->banList));
    }

    /**
     * Checks if the user CAN login.
     * If 'true', the user can try to login.
     */
    public function isBannished()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (false !== ($ipKey = array_search($ip, $this->banList->banished))) {
            // User is banned. Check if the ban has expired:
            if ($this->banList->banishedTimeout[$ipKey] <= time()) {
                // Ban expired, user can try to login again.
                array_splice($this->banList->banished, $ipKey);
                array_splice($this->banList->banishedTimeout, $ipKey);
                if (false !== ($ipKey = array_search($ip, $this->banList->faillure))) {
                    array_splice($this->banList->faillure, $ipKey);
                    array_splice($this->banList->faillureNb, $ipKey);
                }
                $this->updateBanFile(json_encode($this->banList));
                return false; // Ban has expired, user can login.
            }
            return true; // User is banned.
        }
        return false; // User is not banned.
    }

    private function updateBanFile($content)
    {
        file_put_contents($this->banFilename, $content);
    }
}
