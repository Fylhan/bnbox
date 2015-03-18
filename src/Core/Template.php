<?php

namespace Core;

use LogicException;

/**
 * Template class
 *
 * @package core
 * @author  Frederic Guillot
 */
class Template extends Helper
{
    /**
     * Render a template
     *
     * Example:
     *
     * $template->render('template_name', ['bla' => 'value']);
     *
     * @access public
     * @params string   $__template_name   Template name
     * @params array    $__template_args   Key/Value map of template variables
     * @return string
     */
    public function render($__template_name, array $__template_args = array())
    {
        $__template_file = TPL_PATH.'/'.$__template_name.'.php';

        if (! file_exists($__template_file)) {
            throw new LogicException('Unable to load the template: "'.$__template_name.'"');
        }
        
        global $app;
        // -- Ajout des params par défaut
        $globalParams = array(
            'JS_PATH' => JS_PATH,
            'CSS_PATH' => CSS_PATH,
            'FONT_PATH' => FONT_PATH,
            'IMG_PATH' => IMG_PATH,
            'FEED_PATH' => FEED_PATH,
            'StyleEnabled' => StyleEnabled,
            'DebugEnabled' => DEBUG,
            'app' => $app
        );
        $__template_args = array_merge($__template_args, $globalParams);

        extract($__template_args);

        ob_start();
        include $__template_file;
        return ob_get_clean();
    }

    /**
     * Render a page layout
     *
     * @access public
     * @param  string   $template_name   Template name
     * @param  array    $template_args   Key/value map
     * @param  string   $layout_name     Layout name
     * @return string
     */
    public function layout($template_name, array $template_args = array(), $layout_name = 'layout')
    {
        return $this->render(
            $layout_name,
            $template_args + array('content_for_layout' => $this->render($template_name, $template_args))
        );
    }
}
