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

    public static $platforms = array(
        array(
            'name' => 'Facebook',
            'base_url' => 'http://www.facebook.com/sharer.php?u=',
            'color' => '#3B579D'
        ),
        array(
            'name' => 'Twitter',
            'base_url' => 'https://twitter.com/share?url=',
            'color' => '#2AA9E0'
        ),
        array(
            'name' => 'Reddit',
            'base_url' => 'http://reddit.com/submit?url=',
            'color' => '#FF4500'
        ),
        array(
            'name' => 'XING',
            'base_url' => 'https://www.xing.com/spi/shares/new?url=',
            'color' => '#00605E'
        ),
        array(
            'name' => 'Pinterest',
            'base_url' => 'http://pinterest.com/pin/create/button/?url=',
            'color' => '#E6001A'
        )
    );

    /**
     * GAEX_GaaExtension constructor.
     *
     * @param string $name
     * @param array  $args
     */
    public function __construct($name = 'gaa-extension', $args = array())
    {
        $this->plugin_dir = plugin_dir_path(__FILE__);
        $this->plugin_dir_url = plugin_dir_url($this->plugin_dir);

        $this->_builder_js_data = array(
            'social_share_platforms' => self::$platforms
        );

        parent::__construct($name, $args);
    }
}

new GAEX_GaaExtension();
