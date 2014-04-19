<?php
	if(!class_exists('gg_Option_Page')){

		class gg_Option_Page extends Graphic_Geek{

			public function add_actions(){
				parent::add_actions();
				add_action( 'admin_menu', array( $this, 'add_options' ) );
			}//add_actions
		
			public function add_options(){

				$page_title = 'Graphic Geek Site Options';
				$menu_title = 'Graphic Geek';
				$capability = 'manage_options';
				$menu_slug = 'gg_options';
				$function = 'gg_options';
				
				add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function );				
				
			}//add_options			
		}

		gg_Option_Page::init();
		
	}
?>