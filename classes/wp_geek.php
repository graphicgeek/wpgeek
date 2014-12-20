<?php
if(!class_exists('WP_Geek')){
	class WP_Geek{
		public static $options;
		private static $instance = null;

		public static function init(){
			add_action('plugins_loaded', array(self::instance(), 'add_actions'));
			add_action('admin_init', array(self::instance(), 'register_admin_scripts'));
			add_action('admin_init', array(self::instance(), 'admin_styles'));
			add_action('init', array(self::instance(), 'register_scripts'));
			add_action('init', array(self::instance(), 'styles'));
			add_shortcode( 'sub_page_list',  array(self::instance(), 'sub_page_list') );
		}
	
		public static function instance(){
			// create a new object if it doesn't exist.
			is_null(self::$instance) && self::$instance = new self;
			return self::$instance;
		}//instance
	
		public function add_actions(){
			add_action( 'wp_head', array( $this, 'favicon' ) );
		}//instance
	
		public static function option($option){
			if(!self::$options) { self::$options = get_option('wpg_options'); }
			
			if(!isset(self::$options[$option])){ return false; }
			
			return self::$options[$option];
		}//option
			
		public function favicon() {

			if(self::option('icon')){
				echo '<link rel="Shortcut Icon" type="image/x-icon" href="'. wp_get_attachment_url(self::option('icon')) .'" />';
			}
		}//favicon
		
		public function register_admin_scripts(){
			wp_register_script( 'wpg_admin', WP_GEEK_URI . '/js/wpg-admin.js', array('jquery'), '1.0.0', true );
			wp_register_script( 'wpg_media_uploader', WP_GEEK_URI . '/js/wpg-uploads.js', array('wpg_admin'), '1.0.0', true );
			wp_register_script( 'wpg_widget_admin', WP_GEEK_URI . '/js/wpg-widget-admin.js', array('wpg_media_uploader'), '1.0.0', true );		
			wp_register_style('wpg_admin_styles', WP_GEEK_URI . '/css/wpg-admin.css');	
			wp_localize_script( 'wpg_admin', 'date_format', $this->js_date_format() );			
		}

		public function js_date_format() {
			$php_format = get_option('date_format');
		    $PHP_matching_JS = array(
		            // Day
		            'd' => 'dd',
		            'D' => 'D',
		            'j' => 'd',
		            'l' => 'DD',
		            'N' => '',
		            'S' => '',
		            'w' => '',
		            'z' => 'o',
		            // Week
		            'W' => '',
		            // Month
		            'F' => 'MM',
		            'm' => 'mm',
		            'M' => 'M',
		            'n' => 'm',
		            't' => '',
		            // Year
		            'L' => '',
		            'o' => '',
		            'Y' => 'yy',
		            'y' => 'y',
		            // Time
		            'a' => '',
		            'A' => '',
		            'B' => '',
		            'g' => '',
		            'G' => '',
		            'h' => '',
		            'H' => '',
		            'i' => '',
		            's' => '',
		            'u' => ''
		    );

		    $js_format = "";
		    $escaping = false;

		    for($i = 0; $i < strlen($php_format); $i++)
		    {
		        $char = $php_format[$i];
		        if($char === '\\') // PHP date format escaping character
		        {
		            $i++;
		            if($escaping) $js_format .= $php_format[$i];
		            else $js_format .= '\'' . $php_format[$i];
		            $escaping = true;
		        }
		        else
		        {
		            if($escaping) { $js_format .= "'"; $escaping = false; }
		            if(isset($PHP_matching_JS[$char]))
		                $js_format .= $PHP_matching_JS[$char];
		            else
		            {
		                $js_format .= $char;
		            }
		        }
		    }

		    return $js_format;
		} //date_format_php_to_js

		public function admin_styles(){
			wp_enqueue_style('wpg_admin_styles');	
		}		
		
		public function register_scripts(){
			wp_register_script( 'wpg_colorgox', WP_GEEK_URI . '/js/jquery.colorbox-min.js', array('jquery'), '1.5.9', true );
			wp_register_script( 'wpg_scripts', WP_GEEK_URI . '/js/wpg.js', array('wpg_colorgox'), '1.0.0', true );
				
			wp_register_style('wpg_styles', WP_GEEK_URI . '/css/wpg.css');
			wp_register_style('wpg_colorgox_styles', WP_GEEK_URI . '/css/colorbox.css');
		}

		public function styles(){
			wp_enqueue_style('wpg_styles');	
		}		
		
		public static function logo($size='full', $echo = true){

			$img = false;
			$logo = null;			

			do_action('wpg_before_logo', self::instance());
			
			if(self::option('logo')){
				$img = wp_get_attachment_image_src(self::option('logo'), apply_filters('wpg_logo_size', $size)); // returns an array
			}//if(self::options['logo'])
			
			if(self::option('logo_svg')){
				global $is_IE;

				if(($is_IE && self::browser_version() >= 9) || !$is_IE){
					$img = wp_get_attachment_image_src( self::option('logo_svg'), $size);
				}//if(($is_IE && self::browser_version() >= 9) || !$is_IE)
				
			}//if(self::options['logo_svg'])
			
			if($img){
				$logo = '<span class="wpg_logo_box" itemscope itemtype="http://schema.org/Organization">
				<a itemprop="url" href="' . home_url() . '"><img itemprop="logo" class="wpg_logo" src="' . $img[0] . '" alt="logo" />' . self::logo_tagline(false) . '</a>
				</span>';		
			}//if($img)
			
			if($echo){ echo apply_filters('wpg_logo',$logo); }
						
			do_action('wpg_after_logo', self::instance());
			
			return apply_filters('wpg_logo',$logo);
			
		}//logo		
		
		public static function logo_tagline($echo=true){
			if(self::option('tagline')){ 
				$return = '<span class="wpg_tagline">' . self::option('tagline') . '</span>';
				if($echo){ echo $return;}
				return $return;
			}				
		}//logo_tagline
		
		public static function browser_version($var='current_version'){
			if(empty($_SERVER['HTTP_USER_AGENT'])){ return false; }
		
			$key = md5($_SERVER['HTTP_USER_AGENT']);
		
			if(false === ($response = get_site_transient('wpg_browser_' . $key))){
				global $wp_version;
		
				$options = array(
					'body'			=> array( 'useragent' => $_SERVER['HTTP_USER_AGENT'] ),
					'user-agent'	=> 'WordPress/' . $wp_version . '; ' . home_url()
				);
		
				$response = wp_remote_post( 'http://api.wordpress.org/core/browse-happy/1.1/', $options );
		
				if(is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)){ return false; }
		
				/**
				 * Response should be an array with:
				 *  'name' - string - A user friendly browser name
				 *  'version' - string - The most recent version of the browser
				 *  'current_version' - string - The version of the browser the user is using
				 *  'upgrade' - boolean - Whether the browser needs an upgrade
				 *  'insecure' - boolean - Whether the browser is deemed insecure
				 *  'upgrade_url' - string - The url to visit to upgrade
				 *  'img_src' - string - An image representing the browser
				 *  'img_src_ssl' - string - An image (over SSL) representing the browser
				 */
				$response = json_decode( wp_remote_retrieve_body( $response ), true );
		
				if(!is_array($response)){ return false; }
		
				set_site_transient( 'wpg_browser_' . $key, $response, WEEK_IN_SECONDS );
			}//if(false === ($response = get_site_transient('wpg_browser_' . $key)))
		
			return $response[$var];			
		}//browser_version	

		public function html_content_type(){
			return 'text/html';
		}//html_content_type

		public static function state_array(){
			$statelist = array(
					'AL'=>'Alabama',
					'AK'=>'Alaska',
					'AZ'=>'Arizona',
					'AR'=>'Arkansas',
					'CA'=>'California',
					'CO'=>'Colorado',
					'CT'=>'Connecticut',
					'DE'=>'Delaware',
					'DC'=>'District of Columbia',
					'FL'=>'Florida',
					'GA'=>'Georgia',
					'HI'=>'Hawaii',
					'ID'=>'Idaho',
					'IL'=>'Illinois',
					'IN'=>'Indiana',
					'IA'=>'Iowa',
					'KS'=>'Kansas',
					'KY'=>'Kentucky',
					'LA'=>'Louisiana',
					'ME'=>'Maine',
					'MD'=>'Maryland',
					'MA'=>'Massachusetts',
					'MI'=>'Michigan',
					'MN'=>'Minnesota',
					'MS'=>'Mississippi',
					'MO'=>'Missouri',
					'MT'=>'Montana',
					'NE'=>'Nebraska',
					'NV'=>'Nevada',
					'NH'=>'New Hampshire',
					'NJ'=>'New Jersey',
					'NM'=>'New Mexico',
					'NY'=>'New York',
					'NC'=>'North Carolina',
					'ND'=>'North Dakota',
					'OH'=>'Ohio',
					'OK'=>'Oklahoma',
					'OR'=>'Oregon',
					'PA'=>'Pennsylvania',
					'RI'=>'Rhode Island',
					'SC'=>'South Carolina',
					'SD'=>'South Dakota',
					'TN'=>'Tennessee',
					'TX'=>'Texas',
					'UT'=>'Utah',
					'VT'=>'Vermont',
					'VA'=>'Virginia',
					'WA'=>'Washington',
					'WV'=>'West Virginia',
					'WI'=>'Wisconsin',
					'WY'=>'Wyoming',
				);	
				
				return 	apply_filters('wpg_state_array', $statelist);
		} //state_array		
		
		public static function img_dimensions($img, $echo=true){
			$dimensions = '';
			if($img[1]){
				$dimensions .= ' width="' . $img[1] . '"';
				}
			if($img[2]){
				$dimensions .= ' height="' . $img[2] . '"';
				}		
			if($echo){echo $dimensions;}
			
			return $dimensions;
						
		}//img_dimensions
		
		public static function sub_page_list($atts, $content=""){
			global $post;
			extract( shortcode_atts( array(
					'child_of' => 'ID',
					'title_li' => '',
					'echo' => 0
				), $atts ) );			
			
			
			$args = array(
				'child_of' => $post->$child_of,
				'title_li' => $title_li,
				'echo' => $echo
			); 		
		
			return	'<ul class="wpg_sub_page_list"><li>' . get_the_title($post->$child_of) . ': </li>' . wp_list_pages($args) . '</ul>';
				
		}//sub_page_list
		
	}//WP_Geek
	
	WP_Geek::init();

}//if(!class_exists('WP_Geek'))
?>