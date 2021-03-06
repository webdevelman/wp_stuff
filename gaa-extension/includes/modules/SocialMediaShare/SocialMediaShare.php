<?php

class GAEX_SocialMediaShare extends ET_Builder_Module
{
	public $slug = 'gaex_sm_share';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'http://webdevgaa.ga/',
		'author'     => 'Anton Gurievsky',
		'author_uri' => 'http://webdevelman.ga'
	);

	private $platforms;

	public function init() {
		$this->platforms = GAEX_GaaExtension::$platforms;
		$this->name = esc_html__( 'Social Media Share', GAEX_GaaExtension::$gettext_domain_name );
	}

	public function get_fields() {
		$options = array();
		foreach ( $this->platforms as $platform ) {
			$options[ $platform[ 'name' ] ] = ucfirst( $platform[ 'name' ] );
		}

		$default_array = array_fill( 0, count( $this->platforms ), 'off' );
		$default_str = implode( '|', $default_array );

		return array(
			'platfroms_list' => array(
				'label'       => esc_html__(
					'Available social media platforms',
					GAEX_GaaExtension::$gettext_domain_name
				),
				'type'        => 'multiple_checkboxes',
				'options'     => $options,
				'default'     => $default_str,
				'toggle_slug' => 'main_content',
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'background'     => false,
			'borders'        => false,
			'box_shadow'     => false,
			'button'         => false,
			'filters'        => false,
			'fonts'          => array(
				'options' => array(
					'hide_text_shadow' => true,
					'hide_line_height' => true,
					'hide_font'        => true
				),
			),
			'margin_padding' => false,
			'max_width'      => false,
		);
	}

	public function render( $attrs, $content = null, $render_slug ) {
		$platfroms_list = explode( '|', $this->props[ 'platfroms_list' ] );
		$render_string = '';

		foreach ( $platfroms_list as $index => $selection ) {
			if ( 'on' === $selection ) {
				$platform = $this->platforms[ $index ];

				$render_string .= sprintf(
					'<div class="platform" style="background-color:%1$s">
					<a target="_blank" href="%2$s">%3$s</a></div>',
					$platform[ 'color' ],
					$platform[ 'url' ],
					esc_html( ucfirst( $platform[ 'name' ] ), GAEX_GaaExtension::$gettext_domain_name )
				);
			}
		}

		if ( ! empty( $render_string ) ) {
			$output = '<div class="wp-smshare-wrapper">
            <div class="share-on">' . esc_html__( 'Share on: ', GAEX_GaaExtension::$gettext_domain_name ) . '</div>';
			$output .= $render_string;
			$output .= '<div class="wp-smshare-clear"></div></div>';

			return $output;
		}

		return;
	}
}

new GAEX_SocialMediaShare();
