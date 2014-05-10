<?php
/**
 * Images widget class
 */
class wp_Geek_Widget_Recent_Posts extends WP_Widget {
	
	public $fields = array(
		'title',
		'subhead',
		'readmore',
		'size',
		'thumb',
		'custom_text',
		'number',
		'customlink',
		'maxwidth',
		'link_title'
	);
	
    function wp_Geek_Widget_Recent_Posts(){
        $widget_ops = array('classname' => 'wp_Geek_recent_post', 'description' => __( "Show Recent Post(s)", 'wp_Geek_Recent_Post_widget') );
        $this->WP_Widget('wpg-recent-post-widget', __('WordPress Geek Recent Post', 'wp_Geek_Recent_Post_widget'), $widget_ops);
    }//wp_Geek_Widget_Recent_Posts
	
	/*--------------------------Front End Display------------------------------------------------------*/
    function widget($args, $instance) {
		wp_enqueue_style('wpg_styles');
		wp_enqueue_script('wpg_scripts');
        extract($args);
		
		foreach($this->fields as $key){ $this->$key = $instance[$key]; }//foreach
		
		if($this->link){ $this->link = get_permalink($this->link); $target = ''; }
		if($this->customlink){ $this->link = $this->customlink; $target = ' target="_blank"'; }

		echo $before_widget;
		
		echo $before_title;
			if($this->link && $this->link_title){ echo '<a href="' . $this->link . '"' . $target . '>'; }
				echo $this->title;
			if($this->link && $this->link_title){ echo '</a>'; }
        echo $after_title; 
		
		if($this->subhead){ ?><h4><?php echo $this->subhead; ?></h4><?php }
				
		if($this->thumb){ 
			$img = wp_get_attachment_image_src($this->thumb, $this->size); // returns an array
			$alt = get_post_meta($this->thumb, '_wp_attachment_image_alt', true);
			if(!$alt){$alt = $this->title;}
			if($this->maxwidth){ 
				$style = ' style="max-width:' . $this->maxwidth  . '; height:auto;"';
				}
			 else { $style ='';}
			 
			if($this->link){ ?> <a href="<?php echo $this->link; ?>" <?php echo $target . $link_class; ?>> <?php } ?>
				<img<?php echo $style; ?> class="widget_thumb" src="<?php echo $img[0]; ?>" alt="<?php echo $alt; ?>" <?php WP_Geek::img_dimensions($img); ?> />
		<?php if($this->link){ ?></a> <?php } 
		}//if($thumb)

        if($this->readmore && $this->link){ ?>
			<div class="custom_excerpt after_widget_thumb">
                <?php  echo '<p class="readmore"><a href="' . $this->link . '"' . $target . $link_class . '>' . $this->readmore . '</a><p>';  ?>
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
	
		$instance['custom_text'] = stripslashes($new_instance['custom_text']);	
		$instance['maxwidth'] = stripslashes($new_instance['maxwidth']);		
		
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

		$thumb = array(
			'name' => $this->get_field_name('thumb'),
			'id' => $this->get_field_id('thumb'),
			'type' => 'upload',
			'value' => $instance['thumb'],
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
				
		$imagegroup = array(
			'label' => 'Select Image: ',
			'fields' => array($thumb, $size, $maxwidth),
			'type' => 'group'
		);

		$fields = array($title, $subhead, $link, $link_title, $readmore, $imagegroup);
		$formargs = array('fields' => $fields, 'submit_button' => '', 'before_field' => '<p>', 'after_field' => '</p>');						
		
		$form = new WP_Geek_Form($formargs);
		
		echo $form->fields();

    }
}

function register_wp_Geek_Recent_Post_widget() {
    register_widget('wp_Geek_Widget_Recent_Posts');
}
add_action('widgets_init', 'register_wp_Geek_Recent_Post_widget');

?>