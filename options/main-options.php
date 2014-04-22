<?php

	if(!class_exists('WP_Geek_Options')){

		class WP_Geek_Options extends WP_Geek_Option_Page{
		
			public $args=array(
					'menu_slug' => 'wp_geek_admin',
					'menu_type' => 'menu',
					'page_title' => 'WordPress Geek Options'
				);
			
			public function __construct(){
				parent::__construct($this->args);
				
			}//__construct		

			function fields(){
				parent::fields();
				
				$return = '<div class="gg_options_section"><h2>Website Icon:</h2></div>';
			
				return $return;
			}			
							
		}//WP_Geek_Options
		
	}//if(!class_exists('WP_Geek_Options'))

?>