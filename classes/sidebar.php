<?php
	if(!class_exists('WP_Geek_sidebars')){

		class WP_Geek_sidebars extends WP_Geek{

			public $widget_areas = array(
				'main-sidebar' => array(
					'name' => 'Main Sidebar',
					'description' => 'Default sidebar for normal pages'
					),
				'blog-sidebar' => array(
					'name' => 'Blog Sidebar',
					'description' => 'Default sidebar for single posts, archives, and blog pages'
					)
			);

			public function add_actions(){
				add_action('widgets_init', array($this, 'register'));
				add_action('get_sidebar', array($this, 'sidebar'));
			}//add_actions

			public function register(){
				$this->widget_areas = apply_filters('wpg_widget_areas', $this->widget_areas);
//error_log(print_r($this->widget_areas, true));
				foreach ($this->widget_areas as $id => $info) {

					$sidebar = array(
						'name'          => $info['name'],
						'id'            => $id,
						'description'   => $info['description'],
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>'
					);
					$sidebar =  apply_filters('wpg_register_sidebar', $sidebar);//filter for all sidebars
					$sidebar =  apply_filters('wpg_register_' . $id, $sidebar);//filter for this sidebar

					//error_log(print_r($sidebar, true));

					register_sidebar( $sidebar );					
				}//foreach
			}//register

			public function sidebar(){

				$classes= array('sidebar');

				$class_list = '';

				foreach (apply_filters('wpg_sidebar_class', $classes) as $class) {
					$class_list .= $class . ' ';
				}

				if ( is_front_page() && is_home() ) {
					$sidebar = 'blog-sidebar';
				  // Default homepage
				} elseif ( is_front_page() ) {
					$sidebar = 'main-sidebar';
				  // static homepage
				} elseif ( is_home() ) {
					$sidebar = 'blog-sidebar';
				  // blog page
				} else {
				$sidebar = 'main-sidebar';
				  //everything else
				}

	/*			if(is_archive()){
					
				} else {	
					$settings = new wpgSettingsMeta();
					$settings->setdata();
					$sidebar = $settings->show_sidebar;
				}*/
				do_action('before_wpg_sidebar');
			 ?>
				<section id="wpg_sidebar" class="<?php echo $class_list; ?>">
				<?php
					do_action('before_wpg_widgets');
				 	
				 	dynamic_sidebar($sidebar);
					
					do_action('after_wpg_widgets');
				  ?>
				</section>
			<?php
				do_action('after_wpg_sidebar');
			}//sidebar
				
		}//WP_Geek_sidebars
	}//if(!class_exists('WP_Geek_sidebars'))
	//
?>