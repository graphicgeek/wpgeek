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
		'before_image',
		'after_image',
		'link',
		'customlink',
		'maxwidth'
	);
	
    function wp_Geek_Widget_Images(){
        $widget_ops = array('classname' => 'wp_Geek_image', 'description' => __( "Show a single image, with optional accompanying text.", 'wp_Geek_Images_widget') );
        $this->WP_Widget('wpg-image-widget', __('Image Widget', 'wp_Geek_Images_widget'), $widget_ops);
    }//wp_Geek_Widget_Images
	
	/*--------------------------Front End Display------------------------------------------------------*/
    function widget($args, $instance) {
        extract($args);
		
		foreach($this->fields as $key){ $this->$key = $instance[$key]; }//foreach
		
		if($this->link){ $this->link = get_permalink($this->link); $target = ''; }
		if($this->customlink){ $this->link = $this->customlink; $target = ' target="_blank"'; }
		
		echo $before_widget;
		
		echo $before_title . $this->title . $after_title; 
		
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
			if($this->link){ ?> <a href="<?php echo $this->link; ?>" <?php echo $target; ?>> <?php } ?>
				<img<?php echo $style; ?> class="widget_thumb" src="<?php echo $img[0]; ?>" alt="<?php echo $alt; ?>" <?php WP_Geek::img_dimensions($img); ?> />
		<?php if($this->link){ ?></a> <?php } 
		}//if($thumb)

        if($this->after_image){ ?>
			<div class="custom_excerpt after_widget_thumb">
                <?php echo do_shortcode(wpautop($this->after_image)); ?>
			</div>
		<?php }
		
		echo $after_widget; 
} //end Front End Display

	/*--------------------------save info------------------------------------------------------*/
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['subhead'] = $new_instance['subhead'];
		$instance['link'] = $new_instance['link'];	
		$instance['customlink'] = $new_instance['customlink'];			
		$instance['image'] = $new_instance['image'];	
		$instance['size'] = $new_instance['size'];		
		$instance['before_image'] = stripslashes($new_instance['before_image']);
		$instance['after_image'] = stripslashes($new_instance['after_image']);
		$instance['maxwidth'] = stripslashes($new_instance['maxwidth']);		
		
		return $instance;
    }
	/*--------------------------Admin Form------------------------------------------------------*/
	function form( $instance ) {
		
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

		$image = array(
			'name' => $this->get_field_name('image'),
			'id' => $this->get_field_id('image'),
			'type' => 'upload',
			'value' => $instance['image']
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

		$fields = array($title, $subhead, $before_image, $after_image, $link, $imagegroup);
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