<?php
/**
 * @package WP_Geek
 * @version 2
 */
/*
Plugin Name: WordPress Geek
Description: A collection of commonly used features.
Author: Graphic Geek
Plugin URI: http://www.graphicgeek.net
Version: 2
Author URI: http://www.graphicgeek.net
*/

define('WP_GEEK', dirname(__FILE__));
define('WP_GEEK_CORE', WP_GEEK . '/core');
define('WP_GEEK_ClASSES', WP_GEEK . '/classes');
define('WP_GEEK_OPTIONS', WP_GEEK . '/options');
define('WP_GEEK_WIDGETS', WP_GEEK . '/widgets');
define('WP_GEEK_URI', plugins_url() . '/wpgeek');

//Classes
require_once WP_GEEK_ClASSES . '/wp_geek.php';
require_once WP_GEEK_ClASSES . '/forms.php';
require_once WP_GEEK_ClASSES . '/options_page.php';
require_once WP_GEEK_ClASSES . '/image_sizes.php';

//option pages
require_once WP_GEEK_OPTIONS . '/main-options.php';

//widgets
require_once WP_GEEK_WIDGETS . '/image-widget.php';
require_once WP_GEEK_WIDGETS . '/recent-posts.php';

foreach(get_declared_classes() as $class){			
	if(is_subclass_of($class,'WP_Geek')){
		
		$thisclass = new $class();
		add_action('plugins_loaded', array($thisclass, 'add_actions'));		 
	}//if(is_subclass_of
}//foreach				

?>