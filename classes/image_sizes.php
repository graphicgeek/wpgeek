<?php
if(!class_exists('wpg_Image_Size')){
	//PHP class to easily add image sizes
	class wpg_Image_Size{
		
		public $args = array(
			'name', //the "display name" of the image size. For example: "Extra Large" or "Gallery Size", or whatever you want to call it.
			'id', //the name to use in the add_image_size function. This cannot have any spaces. See: http://codex.wordpress.org/Function_Reference/add_image_size
			'width', //in pixels
			'height', //in pixels
			'crop', //crop the image?
			'show_in_admin' //add the new size to the list of options in the media uploader
		);
	
		public function __construct($args=array()){
			
			//set default values
			$args = array_merge(array(
				'name'=> uniqid(), //this will generate a random id if none is entered
				'id' => uniqid(),
				'width' => 50,
				'height' => 50,
				'crop' => true,
				'show_in_admin' => true
			), $args); 
	
			//make values easily available	
			foreach($args as $key => $value){
				$this->$key = $value;			
			}//foreach
	
			add_image_size( $this->id, $this->width, $this->height, $this->crop);   
			//echo  $this->name .  $this->width . $this->height . $this->crop;
			
			if($this->show_in_admin){ add_filter( 'image_size_names_choose', array($this, 'admin_image_size') ); }
			
		}//end constructor
	
		public function admin_image_size($sizes){

			return array_merge($sizes, array(
			$this->id => $this->name,
			));
	
		}//end admin_image_size	 
		
	}//wpg_Image_Size

}//if(!class_exists('wpg_Image_Size')){

/*USAGE EXAMPLE:

	//this would add an 600px x 400px cropped image size
	$args = array(
		'name' => 'My Special Size',
		'id' => 'my_special_size',
		'width' => 600,
		'height' => 400
	);
	
	$size = new wpg_Image_Size($args);
	
	to display images of this size in a template file you could use the following:
	
	echo wp_get_attachment_image( $id, 'my_special_size' );
*/ 
?>