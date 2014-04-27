<?php

	if(!class_exists('WP_Geek_Form')){

		class WP_Geek_Form extends WP_Geek{
			
			public function __construct($args=array()){
				$defaults = array(
					'method' => 'POST',
					'action' => get_permalink(),
					'fields' => array(),
					'submit_button' => '<input type="submit" id="wpg_form_submit" name="wpg_submit" value="submit" />',
					'id' => '',
					'class' => 'wpg_custom_form',
					'before_field' => '',
					'after_field' => '',
					'scripts' => false,
					'admin_scripts' => false,
					'widget_admin_scripts' => false
				); 
				$args = array_merge($defaults, $args);				
				//make each variable available to read
				foreach($args as $key => $value){
					$this->$key = $value;
				}
				
								
			}//__construct
			
			public function display($echo=false){
				$return = '<form id="' . $this->id . '" class="' . $this->class . '" method="' . $this->method . '" action="' . $this->action . '">';
					$return .= $this->fields();
				$return .= '</form>';
				if($echo){ echo $return;}
				return $return;	
			}//display
			
			public function fields($return=''){
				$this->widget_admin_scripts();
				foreach($this->fields as $field){

					switch($field['type']){
						case 'group':
							$return .= $this->parse_group($field);
						break;

						case 'checkbox_group':
							$return .= $this->parse_checkbox_group($field);
						break;	

						case 'radio_group':
							$return .= $this->parse_radio_group($field);
						break;	

						default:
						$return .= $this->parse_field($field);

					}//switch					
	
				}//foreach
				$return .= $this->submit_button;
				return $return;
			}//fields
			
			public function parse_field($field){

					$defaults = array(
						'id' => $field['name'],
						'placeholder' => $field['label'],
						'required' => '',
						'other' => '',
						'label' => '',
						'class' => 'wpg_field',
						'wrapper_class' => false,
						'type' => 'text',
						'value' => '',
						'data' => false
					); 
					$field = array_merge($defaults, $field);
					
					if(!$field['wrapper_class']){
						$field['wrapper_class'] = 'wpg_' . $field['type'] . '_field';
					}
					
					if(is_array($field['data'])){
						$data = '';
						foreach($field['data'] as $label => $value){
							$data .= ' data-' . $label . '="' . $value . '"';
						}
						$field['data'] = $data;
					}
					
					if($field['placeholder']){$field['placeholder'] = ' placeholder="' . $field['placeholder'] . '" ';}
					
					if($field['required']){
						$field['class'] .= ' wpg_required';
						$field['wrapper_class'] .= ' wpg_required_input'; 
						$field['required'] = ' required';}

					if($field['label']){$field['label'] = '<label>' . $field['label'] . '</label>';}

					$return = '';

					switch($field['type']){
						
						case 'content':
							$return .= $field['content'];
						break;
						
						case 'select':
							$return .= $this->select($field);
						break;
	
						case 'content_selector':
							$return .= $this->content_select($field);
						break;	
						
						case 'image_size_select':
							$return .= $this->image_size_selector($field);
						break;				

						case 'phone':
							$return .= $this->phone($field);
						break;	
						
						case 'checkbox':
							$return .= $this->checkbox($field);
						break;	

						case 'radio':
							$return .= $this->radio($field);
						break;													
						
						case 'email':
							$return .= $this->email_input($field);
						break;	

						case 'upload':
							$return .= $this->upload_input($field);
						break;	
	
						case 'textarea':
							$return .= $this->textarea($field);
						break;																							
						
						default:
						$return .= $this->text_input($field);

					}//switch
					return '<div class="' . $field['wrapper_class'] . '">' . $return . '</div>';		
			}//parse_field
			
			public function parse_group($group){
					$defaults = array(
						'id' => $group['label'] . '_group'
					); 
					$group = array_merge($defaults, $group);	
				if($group['required']){$class = 'wpg_input_group wpg_required_group';}
				else {$class = 'wpg_input_group';}
				
				$return = '<div class="' . $class . '" id="' . $group['id'] . '">';
				
				if($group['label']){
					$return .= '<p class="wpg_input_group_label"><strong>' . $group['label'] . '</strong></p>';
				}
				
				foreach($group['fields'] as $field){
					if($group['required']){$field['required'] = 'required';}
					$return .= $this->parse_field($field);
				}//foreach
				
				$return .= '</div>';
				return $return;				
			}//parse_group

			public function parse_checkbox_group($group){
					$defaults = array(
						'id' => $group['label'] . '_group'
					); 
					$group = array_merge($defaults, $group);	
				
				if($group['required']){$class = 'wpg_checkbox_group wpg_required_group';}
				else {$class = 'wpg_checkbox_group';}
				
				if($group['min']){$data = ' data-min_checked="' . $group['min'] . '"';}
				else {$data = '';}
				
				$return = '<div class="' . $class . '" id="' . $group['id'] . '"' . $data . '>';
				
				if($group['label']){
					$return .= '<p class="wpg_checkbox_group_label"><strong>' . $group['label'] . '</strong></p>';
				}
				$field = array('type'=>'checkbox');
				
				foreach($group['options'] as $name => $option){
					$field['label'] = $option;
					$field['check_value'] = $option;
					$field['name'] = $name;
					$return .= $this->parse_field($field);
				}//foreach
				
				$return .= '</div>';
				return $return;				
			}//parse_checkbox_group

			public function parse_radio_group($group){
					$defaults = array(
						'id' => $group['label'] . '_group',
						'name' => $group['id']
					); 
					$group = array_merge($defaults, $group);	
				
				if($group['required']){$class = 'wpg_radio_group wpg_required_group';}
				else {$class = 'wpg_radio_group';}
			
				$return = '<div class="' . $class . '" id="' . sanitize_html_class($group['id']) . '">';
				
				if($group['label']){
					$return .= '<p class="wpg_radio_group_label"><strong>' . $group['label'] . '</strong></p>';
				}
				$field = array('type'=>'radio', 'name'=> sanitize_html_class($group['name']));
				
				foreach($group['options'] as $value => $option){
					$field['label'] = $option;
					$field['radio_value'] = $value;
					
					$return .= $this->parse_field($field);
				}//foreach
				
				$return .= '</div>';
				return $return;				
			}//parse_radio_group			
			
			public function select($field){
				$return = $field['label'] . '<select ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" name="' . $field['name'] . '"'.  $field['other'] . $field['data'] . ' >';
				foreach($field['options'] as $value => $label){
					$return .= '<option value="' . $value . '"' .  selected($value, $field['value'], false) . '>' . $label . '</option>';	
				}//foreach
				$return .= '</select>';
				return $this->before_field . $return . $this->after_field;
			}//select	
		
			
			public function text_input($field){
				
				if($field['type']=='date'){
					$field['type']='text';
					$field['class'] .= ' datepicker';
				}
				
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" type="' . $field['type'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '" '. $field['placeholder'] .  $field['other'] . $field['data'] . ' />';			
				return $this->before_field . $return . $this->after_field;	
			}//	text_input	
			
			public function textarea($field){
				
				$return = $field['label'] . '<textarea ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" name="' . $field['name'] . '" '  .  $field['other'] . $field['data'] . '>' . $field['value'] . '</textarea>';			
				return $this->before_field . $return . $this->after_field;	
			}//	textarea	
						
			public function upload_input($field){
					$defaults = array(
						'upload_type' => 'image',
						'thumbsize' => 'thumbnail',
						'auto_initiate' => true
					); 
					$field = array_merge($defaults, $field);
					
					if($field['upload_type'] == 'image'){
						$return ='<div>';
						if($field['value']) {
				            $img = wp_get_attachment_image_src( $field['value'], $field['thumbsize']);
				            $return .= '<span class="wpg_delete">X</span>';
				            $return .= '<span id="' . $field['name'] . '_result"><img class="wpg_media_upload" src="' . $img[0] . '" /></span>';
	            		} else { 
	            			$return .= '<span id="' . $field['name'] . '_result"></span>';
	            		}//end if($field['value'])
	
			            $return .= '<input class="wpg_media_id" type="hidden" name="' . $field['name'] . '" id="' . $field['name'] . '_id" value="' . $field['value'] . '">';
		            	$return .= '</div>';            		
	            		                    
	        			$return .= '<button class="wpg_media_upload" type="button" id="' . $field['name'] . '" data-uploader_button_text="Set Photo" data-uploader_title="Select an Image" data-auto-initiate="' . $field['auto_initiate'] . '" >Upload ' . strip_tags($field['label']) . '</button>';
        			
					} else {
						//for non-image uploads
						$return ='<div>';
					        if($field['value']) {
					        	$return .= '<span class="wpg_delete">X</span>';
								$doc = wp_get_attachment_url( $field['value']); // returns an array 
								$return .= '<p id="' . $field['upload_type'] . '_upload_result">URL: ' . $doc . '</p>';
							} else {
								$return .= '<p id="' . $field['upload_type'] . '_upload_result"></p>';
							}
							$return .= '<input class="wpg_media_id" type="hidden" name="' . $field['name'] . '" id="' . $field['upload_type'] . '_id" value="' . $field['value'] . '">';
			                $return .= '</div>';    
							
							$return .= '<button class="wpg_media_upload" type="button" id="' . $field['upload_type'] . '" data-uploader_button_text="Select ' . strip_tags($field['label']) . '" data-uploader_title="Select a ' . strip_tags($field['label']) . '" data-uploadtype="' . $field['upload_type'] . '" data-auto-initiate="' . $field['auto_initiate'] . '">Upload ' . strip_tags($field['label']) . '</button>';
					
					}
		
				return $return;	
			}//	upload_input				
			
			public function email_input($field){
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="wpg_email_input ' . $field['class'] . '" type="email" name="' . $field['name'] . '" value="' . $field['value'] . '" '. $field['placeholder'] .  $field['other'] . $field['data'] . ' />';			
				return $return;	
			}//	upload_input				

			public function phone($field){
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="wpg_phone_input ' . $field['class'] . '" type="text" name="' . $field['name'] . '" value="' . $field['value'] . '" '. $field['placeholder'] . $field['other'] . $field['data'] . ' />';	
				return $this->before_field . $return . $this->after_field;			
			}//	phone
		
			public function checkbox($field){
				$return = '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="wpg_checkbox_input ' . $field['class'] . '" type="checkbox" name="' . $field['name'] . '" value="' . $field['check_value'] . '" ' . checked($field['check_value'], $field['value'], false) . ' ' . $field['other'] . $field['data'] . ' />' . $field['label'];	
				return $this->before_field . $return . $this->after_field;			
			}//	checkbox

			public function radio($field){
				$return = '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="wpg_radio_input ' . $field['class'] . '" type="radio" name="' . $field['name'] . '" value="' . $field['radio_value'] . '" ' . checked($field['radio_value'], $field['value'], false) . ' ' . $field['other'] . $field['data'] . ' />' . $field['label'];	
				return $this->before_field . $return . $this->after_field;			
			}//	radio										
			
			public function content_select($field){
				$return = $field['label'] . '<select ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" name="' . $field['name'] . '"'.  $field['other'] . $field['data'] . ' >';
				$return .= self::content_options_list($field['value']);
				$return .= '</select>';
				
				return $this->before_field . $return . $this->after_field;					
			}//content_select
			
			public static function content_options_list($id=false){
				
				$return = '<option>Select from existing content</option>';

				$post_per_page = -1; 
				$postArgs = array('public' => true);
				$postTypes = get_post_types($postArgs);
				
				$args = array(
				'post_type' => $postTypes,
				'posts_per_page' => $post_per_page
			  ); 
		
				$query = new WP_Query($args);				
				while ( $query->have_posts() ) {
						$query->the_post(); 
						$obj = get_post_type_object(get_post_type());
						$theType = $obj->rewrite['slug'];
						if ($theType == ""){$theType = get_post_type();}	
						$return .= '<option value="' . get_the_ID() . '" ' . selected($id, get_the_ID(), false) . '>' . get_the_title() . ' (' . $theType . ')</option>';
				 } wp_reset_postdata();	
				
				 return $return;
			 			
			}//content_options_list
			
			public function image_size_selector($field){
				$return = $field['label'] . '<select ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" name="' . $field['name'] . '"'.  $field['other'] . $field['data'] . ' >';
				$return .= self::image_size_options($field['value']);
				$return .= '</select>';
				
				return $this->before_field . $return . $this->after_field;		
				
			}//image_size_selector
			
			public static function image_size_options($selected='thumbnail'){
				$return = '';
				global $_wp_additional_image_sizes;
				$return = '<option value="full" ' . selected($selected, 'full', false) . '>Full</option>';
				foreach(get_intermediate_image_sizes() as $size ){
					$return .= '<option value="' . $size . '" ' . selected($selected, $size, false) . '>' . $size;
					
					if ($_wp_additional_image_sizes[$size]['width']){ $return .= ' (' . $_wp_additional_image_sizes[$size]['width'] . ' x ' . $_wp_additional_image_sizes[$size]['height'] . ')'; }
		  			
					$return .= '</option>';
				}//foreach
				
				return $return;
				
			}//image_size_selector
			
			public function add_field($field){
				$fields = $this->fields;
				$fields[] = $field;
				$this->fields = $fields;
			}//add_field

			
		}//WP_Geek_Form
		
	}//if(!class_exists('WP_Geek_Form')){
	
?>