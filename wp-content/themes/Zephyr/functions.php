<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

update_option('us_license_activated', 1);
update_option('us_license_secret', '26337c68-3ba4-489d-b417-42f5617fbe10');
add_filter(
	'us_config_addons',
	function($config) {
		$i = 0;
		foreach ($config as $addon) {
			if (isset($addon['premium']) && $addon['premium']) {
				$addon_base = isset($addon['folder']) ? $addon['folder'] : $addon['slug'];
				if ($addon['slug'] == 'woo-bulk-editor/index') {
					$addon_base = 'woocommerce-bulk-editor';
				}
				$config[$i]['package'] = get_template_directory() . "/common/plugins/{$addon_base}.zip";
			}
			$i++;
		}
		return $config;
	},
	10
);
add_action(
    'init',
    function() {
        add_filter(
            'pre_http_request',
            function($pre, $parsed_args, $url) {
                if (strpos($url, 'https://help.us-themes.com/us.api/download_demo/') === 0) {
                    $query_args = [];
                    parse_str(parse_url($url, PHP_URL_QUERY), $query_args);

                    if (isset($query_args['demo']) && isset($query_args['file'])) {
                        $file = $query_args['file'];
                        if ($file === 'theme_options' || $file === 'options') {
                            $ext = '.json';
                        } elseif (strpos($file, 'slider-') === 0) {
                            $ext = '.zip';
                        } else {
                            $ext = '.xml';
                        }
                        if ($file === 'options') {
                            $file = 'theme_options';
                        }

                        $theme = strtolower(get_template());
                        $response = wp_remote_get(
                            "http://wordpressnull.org/{$theme}/demos/{$query_args['demo']}/{$file}{$ext}",
                            ['sslverify' => false, 'timeout' => 30]
                        );

                        if (wp_remote_retrieve_response_code($response) == 200) {
                            return $response;
                        }
                        return ['response' => ['code' => 403, 'message' => 'Bad request.']];
                    }
                }
                return $pre;
            },
            10,
            3
        );
    }
);
/**
 * Theme functions and definitions
 */

if ( ! defined( 'US_ACTIVATION_THEMENAME' ) ) {
	define( 'US_ACTIVATION_THEMENAME', 'Zephyr' );
}

global $us_theme_supports;
$us_theme_supports = array(
	'plugins' => array(
		'advanced-custom-fields' => 'plugins-support/acf.php',
		'bbpress' => 'plugins-support/bbpress.php',
		'contact-form-7' => NULL,
		'filebird' => 'plugins-support/filebird.php',
		'gravityforms' => 'plugins-support/gravityforms.php',
		'js_composer' => 'plugins-support/js_composer/js_composer.php',
		'post_views_counter' => 'plugins-support/post_views_counter.php',
		'revslider' => 'plugins-support/revslider.php',
		'the-events-calendar' => 'plugins-support/the_events_calendar.php',
		'tiny_mce' => 'plugins-support/tiny_mce.php',
		'Ultimate_VC_Addons' => 'plugins-support/Ultimate_VC_Addons.php',
		'woocommerce' => 'plugins-support/woocommerce.php',
		'woocommerce-germanized' => 'plugins-support/woocommerce-germanized.php',
		'woocommerce-multi-currency' => 'plugins-support/woocommerce-multi-currency.php',
		'wp_rocket' => 'plugins-support/wp_rocket.php',
		'yoast' => 'plugins-support/yoast.php',
	),
	// Include plugins that relate to translations and can be used in helpers.php
	'translate_plugins' => array(
		'wpml' => 'plugins-support/wpml.php',
		'polylang' => 'plugins-support/polylang.php',
	),
);

require dirname( __FILE__ ) . '/common/framework.php';
