<?php

	if(!class_exists('WP_Geek_Options')){

		class WP_Geek_Options extends WP_Geek_Option_Page{
		
			public $args=array(
					'menu_slug' => 'wp_geek_admin',
					'menu_type' => 'menu',
					'page_title' => 'WordPress Geek Options',
					'data' => array( //include names of all fields here
						'logo',
						'logo_custom_link',
						'icon',
						'load_bootstrap',
						'post_types'
						),
					'options_name' => 'wpg_options'
				);
			
			public function __construct(){
				
				error_log('custom link - ' . $this->option('logo_custom_link'));
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

				$logo_custom_link = array(
					'name' => 'logo_custom_link',
					'id' => 'logo_custom_link',
					'label' => 'Custom Link: ',
					'value' => $this->option('logo_custom_link')
				);
				
				$logo_group = array(
					'type' => 'group',
					'label' => 'Logo: ',
					'id' => 'logo_group',
					'fields' => array($logo, $logo_custom_link)
				);				
				
				$icon = array(
					'name' => 'icon',
					'label' => 'Website Icon: ',
					'type' => 'upload',
					'value' => $this->option('icon')
				);
				
				$load_bootstrap = array(
					'name' => 'load_bootstrap',
					'label' => 'Load Bootstrap: ',
					'type' => 'checkbox',
					'value' => $this->option('load_bootstrap'),
					'check_value' => 'yes'
				);

				$slideshow = array(
					'name' => 'post_types[]',
					'label' => 'Load Bootstrap: ',
					'type' => 'checkbox',
					'value' => $this->option('load_bootstrap'),
					'check_value' => 'yes'
				);				

				$post_types = array(
					'post_types[]' => 'Slideshows',
					'post_types[]' => 'Gallery',
				);	
																
					
				$post_types_group = array(
					'type' => 'checkbox_group',
					'label' => 'Post Types',
					'id' => 'post_types',
					'options' => $post_types
				);			
			
				$features_group = array(
					'type' => 'group',
					'label' => 'Features: ',
					'id' => 'features_group',
					'fields' => array($load_bootstrap, $post_types_group)
				);		
					
				$fields = array($logo_group, $icon, $features_group);
				$formargs = array('fields' => $fields);
				$form = new WP_Geek_Form($formargs);
				
				$return = $form->fields();
	
			
				return $return;
			}			
							
		}//WP_Geek_Options
		
	}//if(!class_exists('WP_Geek_Options'))

?>