<?php
	if(!class_exists('WP_Geek_Option_Page')){

		class WP_Geek_Option_Page extends WP_Geek{
			
			public $args;
			
			public function __construct($args=array()){
				$defaults = array(
					'page_title' => 'Options',
					'menu_type' => 'options',
					'menu_title' => 'Graphic Geek',
					'capability' => 'manage_options',
					'parent_slug' => false,
					'menu_slug' => false
				);
				
				$args = array_merge($defaults, $args); //merge defaults with user inputs
				
				//make values easily available	
				foreach($args as $key => $value){
					$this->$key = $value;			
				}//foreach				
			}//__construct

			public function add_actions(){
				add_action( 'admin_menu', array( $this, 'add_options' ) );
				
			}//add_actions
		
			public function add_options(){
				if(!$this->menu_slug){return;}
				
				$function = 'add_' . $this->menu_type . '_page';
				
				if($this->menu_type == 'submenu'){
					$function($this->parent_slug, $this->page_title, $menu_title, $capability, $this->menu_slug, array($this,'form'));
				}else{
					$function($this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array($this,'form'));
				}//if
				
			}//add_options		
			
			public function form(){
				if (!current_user_can($this->capability)){ wp_die('You do not have sufficient permissions to access this page.'); }		
				
				if(isset($_POST['submit']) && !wp_verify_nonce($_POST['wp_geek_nonce_name'], 'wp_geek_nonce' . plugin_basename(__FILE__) )){
					wp_die('Security Error. Try again.');
				}		
								
				$form = '<div class="wrap"><form id="wp_geek_options_form" method="post">';
					$form .= '<input type="hidden" name="wp_geek_nonce_name" id="wp_geek_nonce_name" value="' . wp_create_nonce( 'wp_geek_nonce' . plugin_basename(__FILE__) ) . '" />';
					$form .= '<h1>' . $this->page_title . '</h1><hr />';
					$form .= $this->fields();
				$form .= '</form></div>';
				
				echo $form;
				
			}//form_open
			
			public function fields(){

			}//fields
			
			public function section(){
				
			}//section
			
		}//WP_Geek_Option_Page

		
	}
?>