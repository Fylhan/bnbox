<?php

namespace Controller;

/**
 * Bnog administration controller
 *
 * @package  controller
 * @author   Fylhan
 */
class BbogAdmin extends Base
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
