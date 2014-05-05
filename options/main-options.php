<?php

	if(!class_exists('WP_Geek_Options')){

		class WP_Geek_Options extends WP_Geek_Option_Page{
		
			public $args=array(
					'menu_slug' => 'wp_geek_admin',
					'menu_type' => 'menu',
					'page_title' => 'WordPress Geek Options',
					'data' => array('logo', 'icon'),
					'options_name' => 'wpg_options'
				);
			
			public function __construct(){
				parent::__construct($this->args);
				
			}//__construct		

			function fields(){
				parent::fields();
				wp_enqueue_media();
				wp_enqueue_script('wpg_media_uploader');				
									
				$logo = array(
					'name' => 'logo',
					'label' => 'Website Logo: ',
					'type' => 'upload',
					'value' => $this->option('logo')
				);	
				
				$icon = array(
					'name' => 'icon',
					'label' => 'Website Icon: ',
					'type' => 'upload',
					'value' => $this->option('icon')
				);
			
				$fields = array($logo, $icon);
				$formargs = array('fields' => $fields);
				$form = new WP_Geek_Form($formargs);
				
				$return = $form->fields();
	
			
				return $return;
			}			
							
		}//WP_Geek_Options
		
	}//if(!class_exists('WP_Geek_Options'))

?>