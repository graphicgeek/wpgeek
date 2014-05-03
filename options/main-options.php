<?php

	if(!class_exists('WP_Geek_Options')){

		class WP_Geek_Options extends WP_Geek_Option_Page{
		
			public $args=array(
					'menu_slug' => 'wp_geek_admin',
					'menu_type' => 'menu',
					'page_title' => 'WordPress Geek Options',
					'data' => array('icon')
				);
			
			public function __construct(){
				parent::__construct($this->args);
				
			}//__construct		

			function fields(){
				parent::fields();
				wp_enqueue_media();
				wp_enqueue_script('wpg_media_uploader');				
									
				$icon = array(
					'name' => 'icon',
					'type' => 'upload',
					'value' => $this->option('icon')
				);
				
				$fields = array($icon);
				$formargs = array('fields' => $fields);
				$form = new WP_Geek_Form($formargs);
				
				$return = '<div class="wpg_options_section"><h2>Website Icon:</h2>';
				$return .= $form->fields();
				$return .= '</div>';
			
				return $return;
			}			
							
		}//WP_Geek_Options
		
	}//if(!class_exists('WP_Geek_Options'))

?>