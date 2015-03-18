<?php

namespace Controller;

/**
 * Application controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Contact extends Base
{
    /**
     * Home page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->template->layout('contact/show', array(
        )));
    }
}
