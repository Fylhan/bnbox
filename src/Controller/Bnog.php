<?php

namespace Controller;

/**
 * Bnog controller
 *
 * @package  controller
 * @author   Fylhan
 */
class Bnog extends Base
{
    /**
     * Home page
     *
     * @access public
     */
    public function index()
    {
        $sections = $this->section->getList();
        
        $this->response->html($this->template->layout('bnog/test', array(
            'sections' => $sections,
        )));
    }
}
