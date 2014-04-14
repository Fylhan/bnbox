<?
define('PUN_ROOT', '');

  include $s_urlGeshi.'config.php';

  switch ($syn_engine){
    case SYN_GESHI:
      include_once($s_urlGeshi.'geshi/geshi.php');

      $geshi = new GeSHi($code, $codename, $s_urlGeshi.'geshi/geshi');
      $geshi->set_header_type(GESHI_HEADER_DIV);
//      $geshi->enable_classes(true);
//      $geshi->set_overall_class(true);
      $geshi->set_code_style('');
      $geshi->set_line_style('');
      $geshi->tab_width = 2;
      $geshi->set_code_style('');
//     $geshi->encoding
      if ($syn_line_numbers)
        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      $code = $geshi->parse_code();
      break;

    case SYN_FSHL:
      require_once($s_urlGeshi.'fshl/fshl.php');
      $fshl_options = P_TAB_INDENT;
      if ($syn_line_numbers)
        $fshl_options = $fshl_options | P_LINE_COUNTER;
      $parser = new fshlParser('HTML', $fshl_options);
      $code = $parser->highlightString(strtoupper($codename), $code);

      break;

    default:
      global $cache_syn_objects;

      $syn_class = $codename.'_syn';
      @$syn = $cache_syn_objects[$syn_class];
      if (!is_object($syn)){
        if (!class_exists($syn_class))
        {
          include_once($s_urlGeshi.'phpMSH/classes.php');
          $syn_file=$s_urlGeshi.'phpMSH/codes/'.$codename.'.php';
          if (file_exists($syn_file))
            require_once($syn_file);
          else
            $syn_class='plain_code_syn';
        }
        $syn = new $syn_class;
        $cache_syn_objects[$syn_class] = $syn;
      }
      $code = $syn->highlight_code($code);

      break;
//    unset($syn);//free unused object
  }
?>