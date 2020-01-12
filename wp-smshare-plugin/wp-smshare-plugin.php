<?php
/**
 * Plugin Name: WP Social Share Buttons
 * Plugin URI: http://webdevgaa.ga/
 * Description: Social Media Share buttons for your pages and posts. Check the 'Social Media Share' menu in Settings.
 * Version: 1.0
 * Author: Anton Gurievsky
 * Author URI: http://webdevelman.ga
 */

if (!class_exists('WP_smshare_buttons')) {
    class WP_smshare_buttons
    {
        protected $buttons = array(
            'wp_smshare_facebook' => array(
                'name' => 'Facebook',
                'base_url' => 'http://www.facebook.com/sharer.php?u=',
                'color' => '3B579D'
            ),
            'wp_smshare_twitter' => array(
                'name' => 'Twitter',
                'base_url' => 'https://twitter.com/share?url=',
                'color' => '2AA9E0'
            ),
            'wp_smshare_reddit' => array(
                'name' => 'Reddit',
                'base_url' => 'http://reddit.com/submit?url=',
                'color' => 'FF4500'
            ),
            'wp_smshare_xing' => array(
                'name' => 'XING',
                'base_url' => 'https://www.xing.com/spi/shares/new?url=',
                'color' => '00605E'
            ),
            'wp_smshare_pinterest' => array(
                'name' => 'Pinterest',
                'base_url' => 'http://pinterest.com/pin/create/button/?url=',
                'color' => 'E6001A'
            )
        );

        protected $page_slug = 'wp_smshare';

        protected $config_section = 'wp_smshare_config_section';

        /**
         *
         * @return void
         */
        public function __construct()
        {
            // admin
            add_action('admin_menu', array($this, 'wp_smshare_menu_item'));
            add_action('admin_init', array($this, 'wp_smshare_settings'), 10);

            // front
            add_filter(
                'the_content',
                array($this, 'show_wp_smshare_platforms'),
                10,
                1
            );
            add_action('wp_enqueue_scripts', array(
                $this,
                'wp_smshare_front_scripts'
            ));

            // plugin uninstallation
            register_uninstall_hook(__FILE__, 'wp_smshare_uninstall');
        }

        /**
         *
         * @return void
         */
        public static function wp_smshare_uninstall()
        {
            foreach (array_keys($this->buttons) as $platform) {
                delete_option($platform);
            }
        }

        /**
         *
         * @return void
         */
        public function wp_smshare_menu_item()
        {
            add_options_page(
                "Social media share buttons",
                "Social Media Share",
                "manage_options",
                $this->page_slug,
                array($this, "wp_smshare_page")
            );
        }

        /**
         *
         * @return void
         */
        public function wp_smshare_page()
        {
            echo '<div class = "wrap">
                <h1>Available social media platforms</h1>
                <form action = "options.php" method = "POST">';

            settings_fields($this->config_section);
            do_settings_sections($this->page_slug);
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

            foreach ($this->buttons as $field => $info) {
                $description = $info['name'];
                $args = array(
                    'type' => $field
                );
                $callback = array($this, 'print_input_field');

                add_settings_field(
                    $field,
                    $description,
                    $callback,
                    $this->page_slug,
                    $this->config_section,
                    $args
                );
                register_setting($this->config_section, $field);
            }
        }

        /**
         *
         * @return void
         */
        public function print_input_field(array $args)
        {
            $type = $args['type'];
            $checked = get_option($type) === '1' ? 'checked = "checked"' : '';
            echo '<input type = "checkbox" name = "' .
                $type .
                '" value = "1" ' .
                $checked .
                ' />';
        }

        /**
         *
         * @return string
         */
        public function show_wp_smshare_platforms($content)
        {
            global $post;

            $render_string = '';
            $url = esc_url(get_permalink($post->ID));
            foreach ($this->buttons as $field => $type_info) {
                if (get_option($field) == 1) {
                    $render_string .='<div class = "platform" style = "background-color: #' .
                        $type_info['color'] .
                        ';"><a target = "_blank"  href = "' .
                        $type_info['base_url'] .
                        $url .
                        '">' .
                        $type_info['name'] .
                        '</a></div>';
                }
            }

            if ($render_string != '') {
                $output = '<div class = "wp-smshare-wrapper"> <div class = "share-on">Share on: </div>';
                $output .= $render_string;
                $output .= '<div class = "clear"></div></div>';

                return $output;
            }

            return;
        }

        /**
         *
         * @return void
         */
        public function wp_smshare_front_scripts()
        {
            wp_register_style(
                "wp-smshare-style-file",
                plugin_dir_url(__FILE__) . "/public/css/style.css"
            );
            wp_enqueue_style("wp-smshare-style-file");
        }
    }
}

new WP_smshare_buttons();
