<?php
if(!class_exists('Graphic_Geek')){
	class Graphic_Geek{
		public static $options;
		private static $instance = null;


		public static function init(){
			add_action('plugins_loaded', array(self::instance(), 'add_actions'));
		}
	
		public static function instance()
		{
			// create a new object if it doesn't exist.
			is_null(self::$instance) && self::$instance = new self;
			return self::$instance;
		}
	
		public function add_actions(){
			add_action( 'wp_head', array( $this, 'favicon' ) );
		}
	
		public function option($option){
			if(!self::$options) { self::$options = get_option('gg_options'); }
		
			if(!isset(self::$options[$option])){ return false; }
			
			return self::$options[$option];
		}//option
			
		public function favicon() {

			if($this->option('icon')){
				echo '<link rel="Shortcut Icon" type="image/x-icon" href="'. wp_get_attachment_url($this->option('icon')) .'" />';
			}
		}//favicon

		public static function logo($size='full', $echo = true){

			$img = false;
			$logo = null;			

			do_action('gg_before_logo', $this);

			if(self::option('logo')){
				$img = wp_get_attachment_image_src(self::option('logo'), apply_filters('gg_logo_size', $size)); // returns an array
			}//if($this->options['logo'])
			
			if(self::option('logo_svg')){
				global $is_IE;

				if(($is_IE && self::browser_version() >= 9) || !$is_IE){
					$img = wp_get_attachment_image_src( self::option('logo_svg'), $size);
				}//if(($is_IE && self::browser_version() >= 9) || !$is_IE)
				
			}//if($this->options['logo_svg'])
			
			if($img){
				$logo = '<span class="gg_logo_box" itemscope itemtype="http://schema.org/Organization">
				<a itemprop="url" href="' . home_url() . '"><img itemprop="logo" class="fp_logo" src="' . $img[0] . '" alt="logo" />' . self::logo_tagline(false) . '</a>
				</span>';		
			}//if($img)
			
			if($echo){ echo apply_filters('gg_logo',$logo); }
						
			do_action('gg_after_logo', $this);
			
			return apply_filters('gg_logo',$logo);
			
		}//logo		
		
		public static function logo_tagline($echo=true){
			if(self::option('tagline')){ 
				$return = '<span class="gg_tagline">' . self::option('tagline') . '</span>';
				if($echo){ echo $return;}
				return $return;
			}				
		}//logo_tagline
		
		public static function browser_version($var='current_version'){
			if(empty($_SERVER['HTTP_USER_AGENT'])){ return false; }
		
			$key = md5($_SERVER['HTTP_USER_AGENT']);
		
			if(false === ($response = get_site_transient('gg_browser_' . $key))){
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
		
				set_site_transient( 'gg_browser_' . $key, $response, WEEK_IN_SECONDS );
			}//if(false === ($response = get_site_transient('gg_browser_' . $key)))
		
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
				
				return 	apply_filters('gg_state_array', $statelist);
		} //state_array		
		
	}//Graphic_Geek
	
	Graphic_Geek::init();

	
}//if(!class_exists('Graphic_Geek'))
?>