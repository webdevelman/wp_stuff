<?php
/*
Plugin Name: Gaa Divi Extension
Plugin URI:  http://webdevgaa.ga/
Description: Social Media Share buttons for your pages and posts.
Version:     1.0.0
Author:      Anton Gurievsky
Author URI:  http://webdevelman.ga
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gaex-gaa-extension
Domain Path: /languages

Gaa Extension is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Gaa Extension is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Gaa Extension. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if ( ! function_exists( 'gaex_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function gaex_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/GaaExtension.php';
}
add_action( 'divi_extensions_init', 'gaex_initialize_extension' );
endif;
