<?php
/**
 * @package Graphic_Geek
 * @version 2
 */
/*
Plugin Name: Graphic Geek
Description: A collection of commonly used features.
Author: Graphic Geek
Plugin URI: http://www.graphicgeek.net
Version: 2
Author URI: http://www.graphicgeek.net
*/

define('GRAPHIC_GEEK', dirname(__FILE__));
define('GRAPHIC_GEEK_CORE', GRAPHIC_GEEK . '/core');
define('GRAPHIC_GEEK_ClASSES', GRAPHIC_GEEK . '/classes');
define('GRAPHIC_GEEK_OPTIONS', GRAPHIC_GEEK . '/options');

//Classes
require_once GRAPHIC_GEEK_ClASSES . '/graphic_geek.php';
require_once GRAPHIC_GEEK_ClASSES . '/options_page.php';

//option pages
require_once GRAPHIC_GEEK_OPTIONS . '/main-options.php';

?>