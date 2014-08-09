<?php
/**
 * Images widget class
 */
class wp_Geek_Widget_Images extends WP_Widget {
	
	public $fields = array(
		'title',
		'subhead',
		'image',
		'size',
		'lightbox',
		'before_image',
		'after_image',
		'link',
		'customlink',
		'maxwidth',
		'link_title',
		'readmore'
	);
	
    function wp_Geek_Widget_Images(){
        $widget_ops = array('classname' => 'wp_Geek_image', 'description' => __( "Show a single image, with optional accompanying text.", 'wp_Geek_Images_widget') );
        $this->WP_Widget('wpg-image-widget', __('Image Widget', 'wp_Geek_Images_widget'), $widget_ops);
    }//wp_Geek_Widget_Images
	
	/*--------------------------Front End Display------------------------------------------------------*/
    function widget($args, $instance) {
		wp_enqueue_style('wpg_styles');
		wp_enqueue_script('wpg_scripts');
        extract($args);
		
		foreach($this->fields as $key){ $this->$key = $instance[$key]; }//foreach
		
		if($this->link){ $this->link = get_permalink($this->link); $target = ''; }
		if($this->customlink){ $this->link = $this->customlink; $target = ' target="_blank"'; }

		if($this->lightbox){
			wp_enqueue_style('wpg_colorgox_styles');
			$this->link = wp_get_attachment_url($this->image);	
			$link_class = ' class="wpg_lightbox"';
		}
		
		echo $before_widget;
		
		echo $before_title;
		if($this->link && $this->link_title){ echo '<a href="' . $this->link . '"' . $target . $link_class . '>'; }
			echo $this->title;
		if($this->link && $this->link_title){ echo '</a>'; }
        echo $after_title; 
		
		if($this->subhead){ ?><h4><?php echo $this->subhead; ?></h4><?php }

        if($this->before_image){ ?>
			<div class="custom_excerpt before_widget_thumb">
                <?php echo do_shortcode(wpautop($this->before_image)); ?>
			</div>
		<?php } 
				
		if($this->image){ 
			$img = wp_get_attachment_image_src($this->image, $this->size); // returns an array
			$alt = get_post_meta($this->image, '_wp_attachment_image_alt', true);
			if(!$alt){$alt = $this->title;}
			if($this->maxwidth){ 
				$style = ' style="max-width:' . $this->maxwidth  . '; height:auto;"';
				}
			 else { $style ='';}
			 
			if($this->link){ ?> <a href="<?php echo $this->link; ?>" <?php echo $target . $link_class; ?>> <?php } ?>
				<span itemscope itemtype="https://schema.org/WebPageElement"><img<?php echo $style; ?> class="widget_thumb" itemprop="photo" src="<?php echo $img[0]; ?>" alt="<?php echo $alt; ?>" <?php WP_Geek::img_dimensions($img); ?> /></span>
		<?php if($this->link){ ?></a> <?php } 
		}//if($thumb)

        if($this->after_image || ($this->readmore && $this->link)){ ?>
			<div class="custom_excerpt after_widget_thumb">
                <?php echo do_shortcode(wpautop($this->after_image));
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
	
		$instance['before_image'] = stripslashes($new_instance['before_image']);
		$instance['after_image'] = stripslashes($new_instance['after_image']);
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

		$before_image = array(
			'type' => 'textarea',
			'name' => $this->get_field_name('before_image'),
			'label' => 'Text before image: ',
			'id' => $this->get_field_id('before_image'),
			'value' => $instance['before_image'],
			'class' => 'widefat'
		);	

		$after_image = array(
			'type' => 'textarea',
			'name' => $this->get_field_name('after_image'),
			'label' => 'Text after image: ',
			'id' => $this->get_field_id('after_image'),
			'value' => $instance['after_image'],
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

		$image = array(
			'name' => $this->get_field_name('image'),
			'id' => $this->get_field_id('image'),
			'type' => 'upload',
			'value' => $instance['image'],
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
			'fields' => array($image, $size, $maxwidth),
			'type' => 'group'
		);

		$fields = array($title, $subhead, $before_image, $after_image, $lightbox, $link, $customlink, $link_title, $readmore, $imagegroup);
		$formargs = array('fields' => $fields, 'submit_button' => '', 'before_field' => '<p>', 'after_field' => '</p>');						
		
		$form = new WP_Geek_Form($formargs);
		
		echo $form->fields();

    }
}

function register_wp_Geek_Images_widget() {
    register_widget('wp_Geek_Widget_Images');
}
add_action('widgets_init', 'register_wp_Geek_Images_widget');
?>