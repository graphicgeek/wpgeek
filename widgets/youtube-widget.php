<?php
/**
 * Images widget class
 */error_reporting(0);
class wp_Geek_Widget_YouTube extends WP_Widget {
	
	public $fields = array(
		'id', 
		'thumbSize', 
		'thumb_id', 
		'title',
		'height',
		'width',
		'title',
		'subhead',
		'lightbox',
		'before_thumbnail',
		'after_thumbnail',
		'link',
		'customlink',
		'link_title',
		'readmore'
	);
	
    function wp_Geek_Widget_YouTube(){
        $widget_ops = array('classname' => 'wp_Geek_youtube', 'description' => __( "Show a YouTube Video, with optional accompanying text.", 'wp_Geek_YouTube_widget') );
        $this->WP_Widget('wpg-youtube-widget', __('YouTube Widget', 'wp_Geek_YouTube_widget'), $widget_ops);
    }//wp_Geek_Widget_YouTube
	
	/*--------------------------Front End Display------------------------------------------------------*/
    function widget($args, $instance) {
		wp_enqueue_style('wpg_styles');
		wp_enqueue_script('wpg_scripts');
		extract($args);
		
		$videoargs = array();
		
		foreach($this->fields as $key){ $this->$key = $instance[$key]; $videoargs[$key] = $instance[$key]; }//foreach
		
		error_log(print_r($videoargs,true));
		
		$video = new wpg_Video($videoargs);
		
		if($this->link){ $this->link = get_permalink($this->link); $target = ''; }
		if($this->customlink){ $this->link = $this->customlink; $target = ' target="_blank"'; }
		
		echo $before_widget . $before_title;
		
		if($this->link && $this->link_title){ echo '<a href="' . $this->link . '"' . $target . '>'; }
			echo $this->title;
		if($this->link && $this->link_title){ echo '</a>'; }
		
        echo $after_title; 
		
		if($this->subhead){ ?><h4><?php echo $this->subhead; ?></h4><?php }

        if($this->before_thumbnail){ ?>
			<div class="custom_excerpt before_widget_thumb">
                <?php echo do_shortcode(wpautop($this->before_thumbnail)); ?>
			</div>
		<?php } 
		
		if($this->lightbox){ $video->lightbox(); } else { $video->inline(); }

        if($this->after_thumbnail || ($this->readmore && $this->link)){ ?>
			<div class="custom_excerpt after_widget_thumb">
                <?php echo do_shortcode(wpautop($this->after_thumbnail));
				if($this->link && $this->readmore){ echo '<p class="readmore"><a href="' . $this->link . '"' . $target . $link_class . '>' . $this->readmore . '</a><p>'; }
				 ?>
			</div>
		<?php }
		
		echo $after_widget; 
} //end Front End Display

	/*--------------------------save info------------------------------------------------------*/
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
		foreach($this->fields as $field){
			$instance[$field] = strip_tags($new_instance[$field]);
		}//foreach
	
		$instance['before_thumbnail'] = stripslashes($new_instance['before_thumbnail']);
		$instance['after_thumbnail'] = stripslashes($new_instance['after_thumbnail']);	
		
		return $instance;
    }
	/*--------------------------Admin Form------------------------------------------------------*/
	function form( $instance ) {
		wp_enqueue_media();
		wp_enqueue_script('wpg_widget_admin');
		
		$title = array(
			'name' => $this->get_field_name('title'),
			'label' => 'Title: ',
			'placeholder' => 'Title',
			'id' => $this->get_field_id('title'),
			'value' => $instance['title'],
			'class' => 'widefat'
		);

		$subhead = array(
			'name' => $this->get_field_name('subhead'),
			'label' => 'Subhead: ',
			'placeholder' => 'Subhead',
			'id' => $this->get_field_id('subhead'),
			'value' => $instance['subhead'],
			'class' => 'widefat'
		);
		
		$id = array(
			'name' => $this->get_field_name('id'),
			'label' => 'YouTube ID: ',
			'placeholder' => 'YouTube ID',
			'id' => $this->get_field_id('id'),
			'value' => $instance['id'],
			'class' => 'widefat'
		);		
		
		$before_thumbnail = array(
			'type' => 'textarea',
			'name' => $this->get_field_name('before_thumbnail'),
			'label' => 'Text before thumbnail: ',
			'id' => $this->get_field_id('before_thumbnail'),
			'value' => $instance['before_thumbnail'],
			'class' => 'widefat'
		);	

		$after_thumbnail = array(
			'type' => 'textarea',
			'name' => $this->get_field_name('after_thumbnail'),
			'label' => 'Text after thumbnail: ',
			'id' => $this->get_field_id('after_thumbnail'),
			'value' => $instance['after_thumbnail'],
			'class' => 'widefat'
		);	

		$lightbox = array(
			'type' => 'checkbox',
			'name' => $this->get_field_name('lightbox'),
			'label' => 'Open Image in Lightbox: ',
			'id' => $this->get_field_id('lightbox'),
			'value' => $instance['lightbox'],
			'check_value' => 'yes'
		);	
		
		$link = array(
			'type' => 'content_selector',
			'name' => $this->get_field_name('link'),
			'label' => 'Link: ',
			'id' => $this->get_field_id('link'),
			'value' => $instance['link'],
			'class' => 'widefat'
		);								
	
		$customlink = array(
			'name' => $this->get_field_name('customlink'),
			'label' => 'Custom Link: ',
			'placeholder' => 'Custom Link',
			'id' => $this->get_field_id('customlink'),
			'value' => $instance['customlink'],
			'class' => 'widefat'
		);


		$link_title = array(
			'type' => 'checkbox',
			'name' => $this->get_field_name('link_title'),
			'label' => 'Link Title: ',
			'id' => $this->get_field_id('link_title'),
			'value' => $instance['link_title'],
			'check_value' => 'yes'
		);	

		$readmore = array(
			'name' => $this->get_field_name('readmore'),
			'label' => 'Read More Text: ',
			'placeholder' => 'Read More Text',
			'id' => $this->get_field_id('readmore'),
			'value' => $instance['readmore'],
			'class' => 'widefat'
		);		

		$thumbnail = array(
			'name' => $this->get_field_name('thumbnail'),
			'id' => $this->get_field_id('thumbnail'),
			'type' => 'upload',
			'value' => $instance['thumbnail'],
			'auto_initiate' => false
		);
		
		$size = array(
			'name' => $this->get_field_name('size'),
			'id' => $this->get_field_id('size'),
			'type' => 'image_size_select',
			'value' => $instance['size']
		);

		$maxwidth = array(
			'name' => $this->get_field_name('maxwidth'),
			'label' => 'Max Width: ',
			'placeholder' => 'Max Width',
			'id' => $this->get_field_id('maxwidth'),
			'value' => $instance['maxwidth'],
			'class' => 'widefat'
		);
				
		$thumbnailgroup = array(
			'label' => 'Select Image: ',
			'fields' => array($thumbnail, $size, $maxwidth),
			'type' => 'group'
		);

		$fields = array($title, $subhead, $id, $before_thumbnail, $after_thumbnail, $lightbox, $link, $customlink, $link_title, $readmore, $thumbnailgroup);
		$formargs = array('fields' => $fields, 'submit_button' => '', 'before_field' => '<p>', 'after_field' => '</p>');						
		
		$form = new WP_Geek_Form($formargs);
		
		echo $form->fields();

    }
}

function register_wp_Geek_YouTube_widget() {
    register_widget('wp_Geek_Widget_YouTube');
}
add_action('widgets_init', 'register_wp_Geek_YouTube_widget');
?>