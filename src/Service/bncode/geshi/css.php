<?

if (!defined('PUN_ROOT'))
        exit('The constant PUN_ROOT must be defined and point to a valid PunBB installation root directory.');

  include $s_urlGeshi.'config.php';

  switch ($syn_engine){
  case SYN_GESHI:
//geshi not use general css file
    break;
  case SYN_FSHL:
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $s_urlGeshi; ?>fshl/styles/COHEN_style.css" media="all" />
<?
    break;
  default:
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $s_urlGeshi; ?>phpMSH/syn.css" />
<?
  }
?>