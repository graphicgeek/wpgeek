<?php

	if(!class_exists('WP_Geek_metabox')){

		class WP_Geek_metabox{
			public $args = array(
				'id', 
				'title', 
				'posttype', 
				'context', 
				'priority',
				'params',
				'key',
				'form'		
			);
		
			public function __construct($args=array()){
				//set default values
				$args = array_merge(array(
					'id' => 'wp_geek',
					'title' => 'WP Geek',
					'posttype' => get_post_types(array('public' => true)),
					'context' => 'side',
					'priority' => 'low',
					'params' => '',
					'key' => 'wpg',
					'form' => new WP_Geek_Form(array('submit_button'=>''))
				), $args);
		
				//make values easily available	
				foreach($args as $key => $value){
					$this->$key = $value;			
				}//foreach

			}//end constructor

			public function box(){
				global $post;
				$this->post = $post;
				$this->nonce();
				$this->box_content();
				echo $this->form->fields();
			}

			public function box_content(){
				echo "Declare public box_content method in your child class";
			}

			public function nonce(){
				 echo '<input type="hidden" name="wpg_meta_nonce" id="wpg_meta_nonce" value="' . wp_create_nonce('wpg_meta_nonce') . '" />';
			}

			public function add_field($field, $name){
				$this->setdata();
				$field['name'] = $this->key . '[' . $name . ']';
				$field['value'] = $this->$name;
				$this->form->add_field($field);
			}

			public function setdata(){
				global $post;
				$data = get_metadata('post', $post->ID);
				foreach ($data as $key => $value) {
					$this->$key = $value[0];
				}//foreach
			}//setdata

			public function data($key){
				return $this->$key;
			}//data			

		}//WP_Geek_metabox

	}//if(!class_exists('WP_Geek_metabox'

	if(!class_exists('WP_Geek_meta')){

		class WP_Geek_meta {

			public $boxes;

			public function init(){
				add_action( 'add_meta_boxes', array($this, 'add_boxes') );
				add_action('save_post', array($this, 'save_posted'), 10, 2); // save the custom fields
			}//init

			public function ready(){

			}//ready

			public function add_boxes(){
				foreach ($this->boxes as $box) {
					add_meta_box( $box->id, $box->title, array($box, 'box'), $box->post_type, $box->context, $box->priority, $box->params );
				}
			}//add_boxes

			public function add_box($box){
				$this->boxes[] = $box;
			}//add_box

			public  function data($id){
				return get_metadata('post', $id);
				//get_post_meta()
			}//data

			public function save_meta($post, $box, $data){
				if($this->verify($post)){
					error_log('saving meta');
					foreach ($data as $key => $value) {
						update_post_meta($post->ID, $key, $value);
					}//foreach
				}
			}//save

			public function save_posted($post_id, $post){

				if($this->verify($post)){
					error_log('saving posted');
					foreach ($this->boxes as $box) {
						$this->save_meta($post, $box, $_POST[$box->key]);
					}//foreach
				}//if
			}//save_posted

			public function verify($post){
			    if (wp_verify_nonce( $_POST['wpg_meta_nonce'], 'wpg_meta_nonce') && current_user_can( 'edit_post', $post->ID )) {
			    	error_log('nonce verified');
				   return true;
			    }//if

			    error_log('nonce failed');

			    return false;
			}//verify

		}//WP_Geek_meta
	}//if(!class_exists('WP_Geek_meta'))

class wpgSettingsMeta extends WP_Geek_metabox
{
	public $args = array(
		'id' => 'wpg_settings',
		'title' => 'WordPress Geek Settings'
	);

	public function __construct(){
		parent::__construct($this->args);
	}//__construct

	public function box_content(){

		$field = array(
			'label' => 'Show Sidebar:',
			'type' => 'select',
			'options' => $this->widget_area_list());
		$this->add_field($field, 'show_sidebar');
	}//box_content

	public function widget_area_list(){
		global $wp_registered_sidebars;
		$widget_area_list = array();
		foreach ($wp_registered_sidebars as $sidebar){
			$widget_area_list[$sidebar['id']] = $sidebar['name'];
		}//foreach
		$widget_area_list['none'] = 'None';
		return $widget_area_list;
	}//widget_area_list

}//wpgSettingsMeta

	$test = new wpgSettingsMeta();

	$meta = new WP_Geek_meta();

	$meta->add_box($test);

	$meta->init();

	/*
function fp_widget_area_selector($selected=''){
			global $wp_registered_sidebars;
			
			if(isset($_GET['post_type']) || isset($_GET['page'])){
			$widgetarea = apply_filters('fp_default_sidebar','Main Widget Area');
				} else {
			$widgetarea =  apply_filters('fp_default_blog_sidebar','Blog Page');			
				}
				
		 	if(!$selected){$selected = $widgetarea;}
		 	
		 	foreach ($wp_registered_sidebars as $sidebar){ ?>
				<option value="<?php echo $sidebar['name']; ?>" <?php selected($sidebar['name'], $selected); ?>><?php echo $sidebar['name']; ?></option>
			<?php }//foreach
		 ?>
	     	<option value="none" <?php selected($selected, 'none'); ?>>None</option>
  	<?php	
}//fp_widget_area_selector
	*/
?>