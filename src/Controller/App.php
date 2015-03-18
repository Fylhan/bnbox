<?php

namespace Controller;

/**
 * Bnbox app controller
 *
 * @package  controller
 * @author   Fylhan
 */
class App extends Base
{
    /**
     * Home page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->template->layout('app/home', array(
            'name' => 'Olivier',
        )));
    }
}
