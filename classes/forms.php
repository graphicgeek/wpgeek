<?php

	if(!class_exists('WP_Geek_Form')){

		class WP_Geek_Form{
			
			public function __construct($args=array()){
				$defaults = array(
					'method' => 'POST',
					'action' => get_permalink(),
					'fields' => array(),
					'submit_button' => '<input type="submit" id="fp_form_submit" name="fp_submit" value="submit" />',
					'id' => '',
					'class' => 'fp_custom_form'
				); 
				$args = array_merge($defaults, $args);				
				//make each variable available to read
				foreach($args as $key => $value){
					$this->$key = $value;
				}
								
			}//__construct
			
			public function display(){
				$return = '<form id="' . $this->id . '" class="' . $this->class . '" method="' . $this->method . '" action="' . $this->action . '">';
					$return .= $this->fields();
				$return .= '</form>';
				
				return $return;	
			}//display
			
			public function fields($return=''){
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
						'class' => 'fp_field',
						'wrapper_class' => false,
						'type' => 'text',
						'value' => '',
						'data' => false
					); 
					$field = array_merge($defaults, $field);
					
					if(!$field['wrapper_class']){
						$field['wrapper_class'] = 'fp_' . $field['type'] . '_field';
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
						$field['class'] .= ' fp_required';
						$field['wrapper_class'] .= ' fp_required_input'; 
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
				if($group['required']){$class = 'fp_input_group fp_required_group';}
				else {$class = 'fp_input_group';}
				
				$return = '<div class="' . $class . '" id="' . $group['id'] . '">';
				
				if($group['label']){
					$return .= '<p class="fp_input_group_label"><strong>' . $group['label'] . '</strong></p>';
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
				
				if($group['required']){$class = 'fp_checkbox_group fp_required_group';}
				else {$class = 'fp_checkbox_group';}
				
				if($group['min']){$data = ' data-min_checked="' . $group['min'] . '"';}
				else {$data = '';}
				
				$return = '<div class="' . $class . '" id="' . $group['id'] . '"' . $data . '>';
				
				if($group['label']){
					$return .= '<p class="fp_checkbox_group_label"><strong>' . $group['label'] . '</strong></p>';
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
				
				if($group['required']){$class = 'fp_radio_group fp_required_group';}
				else {$class = 'fp_radio_group';}
			
				$return = '<div class="' . $class . '" id="' . sanitize_html_class($group['id']) . '">';
				
				if($group['label']){
					$return .= '<p class="fp_radio_group_label"><strong>' . $group['label'] . '</strong></p>';
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
				$return = $field['label'] . '<select ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" name="' . sanitize_html_class($field['name']) . '"'.  $field['other'] . $field['data'] . ' >';
				foreach($field['options'] as $value => $label){
					$return .= '<option value="' . $value . '"' .  selected($value, $field['value'], false) . '>' . $label . '</option>';	
				}//foreach
				$return .= '</select>';
				return $return;
			}//select	
		
			
			public function text_input($field){
				
				if($field['type']=='date'){
					$field['type']='text';
					$field['class'] .= ' datepicker';
					}
				
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="' . $field['class'] . '" type="' . $field['type'] . '" name="' . sanitize_html_class($field['name']) . '" value="' . $field['value'] . '" '. $field['placeholder'] .  $field['other'] . $field['data'] . ' />';			
				return $return;	
			}//	text_input	
			
			public function upload_input($field){
					$defaults = array(
						'upload_type' => 'image'
					); 
					$field = array_merge($defaults, $field);
					
					if($field['upload_type'] == 'image'){
						$return ='<div>';
						if($field['value']) {
				            $img = wp_get_attachment_image_src( $field['value'], $field['name']);
				            $return .= '<span class="fp_delete">X</span>';
				            $return .= '<img id="' . sanitize_html_class($field['name']) . '_img" class="custom_media_image" src="' . $img[0] . '" />';
	            		} else { 
	            			$return .= '<img id="' . sanitize_html_class($field['name']) . '_img" class="custom_media_image" src="" />';
	            		}//end if($field['value'])
	
			            $return .= '<input class="fp_media_id" type="hidden" name="' . sanitize_html_class($field['name']) . '" id="' . sanitize_html_class($field['name']) . '_id" value="' . $field['value'] . '">';
		            	$return .= '</div>';            		
	            		                    
	        			$return .= '<button class="fp_media_upload" type="button" id="' . sanitize_html_class($field['name']) . '" data-uploader_button_text="Set Photo" data-uploader_title="Select a Photo">Upload ' . strip_tags($field['label']) . '</button>';
        			
					} else {
						//for non-image uploads
						$return ='<div>';
					        if($field['value']) {
					        	$return .= '<span class="fp_delete">X</span>';
								$doc = wp_get_attachment_url( $field['value']); // returns an array 
								$return .= '<p id="' . $field['upload_type'] . '_upload_result">URL: ' . $doc . '</p>';
							} else {
								$return .= '<p id="' . $field['upload_type'] . '_upload_result"></p>';
							}
							$return .= '<input class="fp_media_id" type="hidden" name="' . sanitize_html_class($field['name']) . '" id="' . $field['upload_type'] . '_id" value="' . $field['value'] . '">';
			                $return .= '</div>';    
							
							$return .= '<button class="fp_media_upload" type="button" id="' . $field['upload_type'] . '" data-uploader_button_text="Select ' . strip_tags($field['label']) . '" data-uploader_title="Select a ' . strip_tags($field['label']) . '" data-uploadtype="' . $field['upload_type'] . '">Upload ' . strip_tags($field['label']) . '</button>';
					
					}
		
				return $return;	
			}//	text_input				
			
			

			public function email_input($field){
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="fp_email_input ' . $field['class'] . '" type="email" name="' . sanitize_html_class($field['name']) . '" value="' . $field['value'] . '" '. $field['placeholder'] .  $field['other'] . $field['data'] . ' />';			
				return $return;	
			}//	text_input				

			public function phone($field){
				$return = $field['label'] . '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="fp_phone_input ' . $field['class'] . '" type="text" name="' . sanitize_html_class($field['name']) . '" value="' . $field['value'] . '" '. $field['placeholder'] . $field['other'] . $field['data'] . ' />';	
				return $return;			
			}//	phone
		
			public function checkbox($field){
				$return = '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="fp_checkbox_input ' . $field['class'] . '" type="checkbox" name="' . sanitize_html_class($field['name']) . '" value="' . $field['check_value'] . '" ' . checked($field['check_value'], $field['value'], false) . ' ' . $field['other'] . $field['data'] . ' />' . $field['label'];	
				return $return;			
			}//	checkbox

			public function radio($field){
				$return = '<input ' . $field['required'] . ' id="' . sanitize_html_class($field['id']) . '" class="fp_radio_input ' . $field['class'] . '" type="radio" name="' . sanitize_html_class($field['name']) . '" value="' . $field['radio_value'] . '" ' . checked($field['radio_value'], $field['value'], false) . ' ' . $field['other'] . $field['data'] . ' />' . $field['label'];	
				return $return;			
			}//	radio										
			
			public function add_field($field){
				$fields = $this->fields;
				$fields[] = $field;
				$this->fields = $fields;
			}//add_field

			
		}//WP_Geek_Form
		
	}//if(!class_exists('WP_Geek_Form')){
	
?>