<?php
if(!class_exists('wpg_Video')){
	//PHP class to easily add image sizes
	class wpg_Video{
		
		public $args = array(
			'id', 
			'thumbSize', 
			'thumb_id', 
			'add_class', 
			'title',
			'echo',
			'height',
			'width',
			'urldata'			
		);
	
		public function __construct($args=array()){
			
			//set default values
			$urldata = array(
				'modestbranding' => 1,
				'showinfo' => 0,
				'autoplay' => 1,
				'autohide' => 1,
				'rel' => 0,
				'wmode' => 'transparent'
			);
			
			$args = array_merge(array(
				'id' => 'qpMvS1Q1sos',
				'thumbSize' => 'medium',
				'thumb_id' => false,
				'replace_thumb' => false,
				'add_class' => false,
				'title' => 'Video',
				'echo' => true,
				'height' => false,
				'width' => false,
				'urldata' => $urldata
			), $args);
	
			//make values easily available	
			foreach($args as $key => $value){
				$this->$key = $value;			
			}//foreach

			
			
		}//end constructor
		public function output($output, $function, $echo='default'){
			if($echo == 'default'){ $echo = $this->echo; }
			$output = apply_filters('wpg_video_' . $function, $output, $this);
			if($echo){echo $output;}
			return $output;
		}
		
		public function lightbox($type){
			wp_enqueue_style('wpg_colorgox_styles');

			switch($type){
				default://YouTube
				$output .= '<a class="wpg_video_lightbox" title="' . $this->title . '" href="http://www.youtube.com/embed/' . $this->id . $this->querystring() . '">' . $this->thumb($type) . '</a>';
			}//switch
			
			return $this->output($output, 'lighbox');			
			
		}//end lighbox	

		public function inline($type){
			switch($type){
				default: //YouTube
				$output .= '<iframe class="wpg_youtube_video" ' .  $this->style() . ' src="http://www.youtube.com/embed/' .  $this->id . $this->querystring() .'"></iframe>';
			}//switch
			
			return $this->output($output, 'inline');
			
		}//end inline
		
		public function channel($number=1){
			//TODO
			//$str = 'https://gdata.youtube.com/feeds/users/'. $this->id .'/uploads?max-results=1';
		}//channel 
		
		public function querystring(){
			return $this->output('?' . http_build_query($this->urldata), 'querystring', false);
//			return '?modestbranding=1&amp;showinfo=0&amp;autohide=1&amp;rel=0&amp;wmode=transparent';
		}
		
		public function thumb(){
			if($this->thumb_id){
				$img = wp_get_attachment_image_src( $this->thumb_id, $this->thumbSize); // returns an array
				$finalThumb = $img[0];
			}else{
				switch($type){	
					default: //YouTube			
					$finalThumb = 'http://img.youtube.com/vi/' . $this->id . '/hqdefault.jpg';
				}//switch
			}//if

			$tag = '<img ' .  $this->style() . ' src="' . $finalThumb . '" alt="' .  $this->title . '" class="wpg_video_thumb" />';
			
			return $this->output($tag, 'thumb', false);

		}//thumb
		
		public function style($styles = ""){
			$return = false;
			
			if($this->width){
				if (strpos($this->width,'px') === false && strpos($this->width,'%') === false){
					$this->width .= 'px';
				}
					
				$styles .= "width: " . $this->width . "; ";
				}
			if($this->height){
				if (strpos($this->height,'px') === false && strpos($this->height,'%') === false){
					$this->height .= 'px';
				}
					
				$styles .= "height: " . $this->height . ";";
				}
			if($styles){
				$return = 'style="'. $styles . '"';
			}	
			return $this->output($return, 'style', false);	
		}//styles
		
	}//wpg_Video

}//if(!class_exists('wpg_Video')){

?>