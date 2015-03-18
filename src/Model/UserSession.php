<?php

namespace Model;

use Core\Translator;

/**
 * User Session
 *
 * @package  model
 * @author   Frederic Guillot
 */
class UserSession extends Base
{
    /**
     * Update user session information
     *
     * @access public
     * @param  array  $user  User data
     */
    public function refresh(array $user = array())
    {
        if (empty($user)) {
            $user = $this->user->getById($this->userSession->getId());
        }

        if (isset($user['password'])) {
            unset($user['password']);
        }

        $user['id'] = (int) $user['id'];
        $user['role'] = (bool) $user['role'];

        $this->session['user'] = $user;
    }

    /**
     * Return true if the logged user is admin
     *
     * @access public
     * @return bool
     */
    public function isAdmin()
    {
        return isset($this->session['user']['role']) && $this->session['user']['role'] === true;
    }

    /**
     * Get the connected user id
     *
     * @access public
     * @return integer
     */
    public function getId()
    {
        return isset($this->session['user']['id']) ? (int) $this->session['user']['id'] : 0;
    }

    /**
     * Check if the given user_id is the connected user
     *
     * @param  integer   $user_id   User id
     * @return boolean
     */
    public function isCurrentUser($user_id)
    {
        return $this->getId() == $user_id;
    }

    /**
     * Check is the user is connected
     *
     * @access public
     * @return bool
     */
    public function isLogged()
    {
        return ! empty($this->session['user']);
    }
}
