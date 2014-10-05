<?php
	if(!class_exists('WP_Geek_sidebars')){

		class WP_Geek_sidebars extends WP_Geek{

			public $widget_areas = array(
				'main-sidebar' => array(
					'name' => 'Main Sidebar',
					'description' => 'Default sidebar for normal pages'
					)
			);

			public function add_actions(){
				add_action('widgets_init', array($this, 'register'));
				add_action('get_sidebar', array($this, 'sidebar'));
			}//add_actions

			public function register(){
				$this->widget_areas = apply_filters('wpg_widget_areas', $this->widget_areas);

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
					register_sidebar( $sidebar );					
				}//foreach
			}//register

			public function sidebar(){
				$settings = new wpgSettingsMeta();
				//$settings->setdata();
			 ?>
				<div id="wpg_sidebar">
				<?php dynamic_sidebar($settings->show_sidebar); ?>
				</div>
			<?php }//sidebar

		}//WP_Geek_sidebars
	}//if(!class_exists('WP_Geek_sidebars'))
	//
?>