<?php
	if(!class_exists('WP_Geek_Option_Page')){

		class WP_Geek_Option_Page extends WP_Geek{
			
			public $args;
			
			public function __construct($args=array()){
				$defaults = array(
					'page_title' => 'Options',
					'menu_type' => 'options',
					'menu_title' => 'WordPress Geek',
					'capability' => 'manage_options',
					'parent_slug' => false,
					'menu_slug' => false,
					'data' => false,
					'options_name' => false
				);
				
				$args = array_merge($defaults, $args); //merge defaults with user inputs
				//error_log('Args: ' . print_r($args, true));
				//make values easily available	
				foreach($args as $key => $value){
					$this->$key = $value;			
				}//foreach	
				
				if($this->options_name){
					$this->options = get_option($this->options_name); 
					
/*	error_log('data before- ' . print_r($this->data, true));
					if(is_array($options)){
						foreach($options as $key => $value){
							if($key){
								//error_log('key ' . $key . ' data ' . $data);
							$this->options[$key] = $value;
							}
						}//foreach							
					}//if
				
				error_log('data after- ' . print_r($this->data, true));*/
				}							
				
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
				
				if(isset($_POST['wpg_submit'])){ $this->update(); }	
												
				$form = '<div class="wrap"><form id="wp_geek_options_form" method="post">';
					$form .= '<input type="hidden" name="wp_geek_nonce_name" id="wp_geek_nonce_name" value="' . wp_create_nonce( 'wp_geek_nonce' . plugin_basename(__FILE__) ) . '" />';
					$form .= '<h1>' . $this->page_title . '</h1><hr />';
					$form .= $this->fields();
				$form .= '</form></div>';
				
				echo $form;
				
			}//form_open
			
			public function update(){
				
				if(!wp_verify_nonce($_POST['wp_geek_nonce_name'], 'wp_geek_nonce' . plugin_basename(__FILE__) )){
					
					wp_die('Security Error. Try again.');
					return false;	
				}
				
				if(!is_array($this->data)){ return false; }
			//	error_log('post - ' . print_r($_POST, true));
				
				
				foreach($this->data as $data){
					
					$this->options[$data] = $_POST[$data];
					
					//error_log('post data ' . $_POST[$data]);
					
				}//foreach
				
				update_option($this->options_name, $this->options);
										
			}//update
			
			public function option($key){
				if(isset($this->options[$key])){
					return $this->options[$key];
				}
				return false;
			}
			
			public function fields(){

			}//fields
			
			public function section(){
				
			}//section
			
		}//WP_Geek_Option_Page

		
	}
?>