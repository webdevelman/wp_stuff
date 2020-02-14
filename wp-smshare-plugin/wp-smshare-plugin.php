<?php
/**
 * Plugin Name: WP Social Share Platforms
 * Plugin URI: http://webdevgaa.ga/
 * Description: Social Media Share platforms for your pages and posts. Check the 'Social Media Share' menu in Settings.
 * Version: 1.0
 * Author: Anton Gurievsky
 * Author URI: http://webdevelman.ga
 */


if ( ! function_exists( 'add_action' ) || 'cli' === php_sapi_name() ) {
	die( 'Not allowed' );
}

if ( ! class_exists( 'WP_Smshare_Platforms' ) ) {
	class WP_Smshare_Platforms
	{
		private static $platforms = array();
		private $page_slug = 'wp_smshare';
		private $config_section = 'wp_smshare_config_section';
		private static $gettext_domain = 'wp_smshare_text_domain';
		private static $option_prefix = 'wp_smshare';
		private static $instance;

		private static function init_platforms()
		{

			self::$platforms = array(
				array(
					'name'     => esc_html__( 'Facebook', self::$gettext_domain ),
					'base_url' => 'http://www.facebook.com/sharer.php?u=',
					'color'    => '#3B579D',
				),
				array(
					'name'     => esc_html__( 'Twitter', self::$gettext_domain ),
					'base_url' => 'https://twitter.com/share?url=',
					'color'    => '#2AA9E0',
				),
				array(
					'name'     => esc_html__( 'Reddit', self::$gettext_domain ),
					'base_url' => 'http://reddit.com/submit?url=',
					'color'    => '#FF4500',
				),
				array(
					'name'     => esc_html__( 'XING', self::$gettext_domain ),
					'base_url' => 'https://www.xing.com/spi/shares/new?url=',
					'color'    => '#00605E',
				),
				array(
					'name'     => esc_html__( 'Pinterest', self::$gettext_domain ),
					'base_url' => 'http://pinterest.com/pin/create/button/?url=',
					'color'    => '#E6001A',
				),
			);
		}

		/**
		 *
		 * @return void
		 */
		private static function get_field_name( $button_name )
		{

			return self::$option_prefix . '_' . strtolower( $button_name );
		}

		/**
		 *
		 * @return void
		 */
		public static function wp_smshare_uninstall()
		{
			self::init_platforms();
			foreach ( self::$platforms as $platform ) {
				$option = self::get_field_name( $platform[ 'name' ] );
				delete_option( $option );
			}
		}

		/**
		 *
		 * @return void
		 */
		public function __construct()
		{
			if ( isset( self::$instance ) ) {
				wp_die( sprintf( esc_html__( '%s is a singleton class and you cannot create a second instance.', self::$gettext_domain ),
						get_class( $this ) )
				);
			}
			self::$instance = $this;
			$this->page_slug = $this->page_slug;

			self::init_platforms();
			// admin
			add_action( 'admin_menu', array( $this, 'wp_smshare_menu_item' ) );
			add_action( 'admin_init', array( $this, 'wp_smshare_settings' ), 10 );

			// front
			add_filter( 'the_content', array( $this, 'show_wp_smshare_platforms' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_smshare_front_scripts' ) );

			// plugin uninstallation
			register_uninstall_hook( __FILE__, 'wp_smshare_uninstall' );
		}


		/**
		 *
		 * @return void
		 */
		public function wp_smshare_menu_item()
		{
			add_options_page(
				esc_html__( 'Social media share platforms', self::$gettext_domain ),
				esc_html__( 'Social Media Share', self::$gettext_domain ),
				'manage_options',
				$this->page_slug,
				array( $this, "wp_smshare_page" )
			);
		}

		/**
		 *
		 * @return void
		 */
		public function wp_smshare_page()
		{
			echo '<div class="wrap">
                <h1>' . esc_html__( 'Available social media platforms', self::$gettext_domain ) . '</h1>
                <form action="options.php" method="POST">';

			settings_fields( $this->config_section );
			do_settings_sections( $this->page_slug );
			submit_button();

			echo '</form></div>';
		}

		/**
		 *
		 * @return void
		 */
		public function wp_smshare_settings()
		{
			add_settings_section(
				$this->config_section,
				"",
				null,
				$this->page_slug
			);

			foreach ( self::$platforms as $info ) {
				$field = self::get_field_name( $info[ 'name' ] );
				$args = array(
					'type' => $field
				);
				$callback = array( $this, 'print_input_field' );

				add_settings_field(
					$info[ 'name' ],
					$info[ 'name' ],
					$callback,
					$this->page_slug,
					$this->config_section,
					$args
				);
				register_setting( $this->config_section, $field, 'intval' );
			}
		}

		/**
		 * @param Array $args
		 * @return void
		 */
		public function print_input_field( array $args )
		{
			$type = $args[ 'type' ];
			printf( '<input type="checkbox" name="%1$s" value="1" %2$s />',
				esc_attr( $type ),
				checked( 1 === intval( get_option( $type ) ), true, false )
			);
		}

		/**
		 *
		 * @param String $content
		 * @return string
		 */
		public function show_wp_smshare_platforms( $content )
		{
			global $post;

			$render_string = '';
			$url = get_permalink( $post->ID );
			foreach ( self::$platforms as $platform ) {
				if ( 1 === intval( get_option( self::get_field_name( $platform[ 'name' ] ) ) ) ) {
					$render_string .= sprintf(
						'<div class="platform" style="background-color:%1$s">
						<a target="_blank" href="%2$s">%3$s</a></div>',
						esc_attr( $platform[ 'color' ] ),
						esc_url( $platform[ 'base_url' ] . $url ),
						esc_html( ucfirst( $platform[ 'name' ] ) )
					);
				}
			}

			if ( ! empty( $render_string ) ) {
				$output = '<div class="wp-smshare-wrapper">
                <div class="share-on">' . esc_html__( 'Share on:', self::$gettext_domain ) . ' </div>';
				$output .= $render_string;
				$output .= '<div class="wp-smshare-clear"></div></div>';

				return $content . $output;
			}

			return $content;
		}

		/**
		 *
		 * @return void
		 */
		public function wp_smshare_front_scripts()
		{
			wp_register_style(
				"wp-smshare-style-file",
				plugin_dir_url( __FILE__ ) . "/public/css/style.css"
			);
			wp_enqueue_style( "wp-smshare-style-file" );
		}
	}

}

function wp_smshare_init_plugin()
{
	new WP_Smshare_Platforms();
}

add_action( 'plugins_loaded', 'wp_smshare_init_plugin' );
