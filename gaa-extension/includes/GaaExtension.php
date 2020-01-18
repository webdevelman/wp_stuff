<?php

class GAEX_GaaExtension extends DiviExtension
{
    /**
     * The gettext domain for the extension's translations.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $gettext_domain = 'gaex-gaa-extension';

    /**
     * The extension's WP Plugin name.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $name = 'gaa-extension';

    /**
     * The extension's version
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $version = '1.0.0';

    public static $platforms = array();

    public static $gettext_domain_name = '';

    protected function get_current_url() {
        global $wp;
        return esc_url( home_url( $wp->request ) . strtok( $_SERVER['REQUEST_URI'], '?' ) );
    }

    /**
     * GAEX_GaaExtension constructor.
     *
     * @param string $name
     * @param array  $args
     */
    public function __construct( $name = 'gaa-extension', $args = array() )
    {

        self::$gettext_domain_name = $this->gettext_domain;

        self::$platforms = array(
            array(
                'name'  => esc_html__( 'Facebook', $this->gettext_domain ),
                'url'   => esc_url( 'http://www.facebook.com/sharer.php?u=' . $this->get_current_url() ),
                'color' => esc_attr( '#3B579D' ),
            ),
            array(
                'name'  => esc_html__( 'Twitter', $this->gettext_domain ),
                'url'   => esc_url( 'https://twitter.com/share?url=' . $this->get_current_url() ),
                'color' => esc_attr( '#2AA9E0' ),
            ),
            array(
                'name'  => esc_html__( 'Reddit', $this->gettext_domain ),
                'url'   => esc_url( 'http://reddit.com/submit?url=' . $this->get_current_url() ),
                'color' => esc_attr( '#FF4500' ),
            ),
            array(
                'name'  => esc_html__( 'XING', $this->gettext_domain ),
                'url'   => esc_url( 'https://www.xing.com/spi/shares/new?url=' . $this->get_current_url() ),
                'color' => esc_attr( '#00605E' ),
            ),
            array(
                'name'  => esc_html__( 'Pinterest', $this->gettext_domain ),
                'url'   => esc_url( 'http://pinterest.com/pin/create/button/?url=' . $this->get_current_url() ),
                'color' => esc_attr( '#E6001A' ),
            ),
        );

        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

        $this->_builder_js_data = array(
            'social_share_platforms' => self::$platforms,
            'l10n' => array(
                'share_on' => esc_html__( 'Share On', $this->gettext_domain ),
            )
        );

        parent::__construct( $name, $args );
    }
}

new GAEX_GaaExtension();
