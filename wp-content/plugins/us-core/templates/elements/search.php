<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output search element
 *
 * @var $text           string Placeholder Text
 * @var $layout         string Layout: 'simple' / 'modern' / 'fulwidth' / 'fullscreen'
 * @var $width          int Field width
 * @var $design_options array
 * @var $product_search bool Whether to search for WooCommerce products only
 * @var $classes        string
 * @var $id             string
 */

$_atts = array(
	'class' => 'w-search',
	'style' => '',
);
$_atts['class'] .= $classes ?? '';

// Force "Simple" layout for shortcode
if ( $us_elm_context == 'shortcode' ) {
	$layout = 'simple';

	// Add specific class for header context
} else {
	$_atts['class'] .= ' elm_in_header';
	$_atts['class'] .= ' ' . us_get_field_style_class( $us_field_style );
	$icon_pos = 'right';
}

$_atts['class'] .= ' layout_' . $layout;
$_atts['class'] .= ' iconpos_' . $icon_pos;

if ( us_get_option( 'ripple_effect' ) ) {
	$_atts['class'] .= ' with_ripple';
}

if ( ! empty( $el_id ) ) {
	$_atts['id'] = $el_id;
}
if ( us_amp() AND empty( $el_id ) ) {
	$_atts['id'] = str_replace( ':', '_', $id );;
}

if ( ! empty( $field_bg_color ) ) {
	$field_bg_color = us_get_color( $field_bg_color, /* Gradient */ TRUE );
	$_atts['style'] .= sprintf( '--inputs-background:%s;', $field_bg_color );
	$_atts['style'] .= sprintf( '--inputs-focus-background:%s;', $field_bg_color );
}
if ( ! empty( $field_text_color ) ) {
	$field_text_color = us_get_color( $field_text_color );
	$_atts['style'] .= sprintf( '--inputs-text-color:%s;', $field_text_color );
	$_atts['style'] .= sprintf( '--inputs-focus-text-color:%s;', $field_text_color );
}

// Output the element
$output = '<div' . us_implode_atts( $_atts ) . '>';

// Additional block for Fullscreen layout, when Ripple Effect is enabled
if ( $layout == 'fullscreen' AND us_get_option( 'ripple_effect' ) ) {
	$output .= '<div class="w-search-background"></div>';
}

// Add "Open" button
if ( $us_elm_context != 'shortcode' ) {
	$open_btn_atts = array(
		'class' => 'w-search-open',
		'role' => 'button',
		'aria-label' => us_translate( 'Search' ),
	);
	if ( us_amp() ) {
		$open_btn_atts['on'] = 'tap:' . $_atts['id'] . '.toggleClass(class=\'active\')';
	} else {
		$open_btn_atts['href'] = '#';
	}

	$output .= '<a' . us_implode_atts( $open_btn_atts ) . '>';
	if ( ! empty( $icon ) ) {
		$output .= us_prepare_icon_tag( $icon );
	}
	$output .= '</a>';
}

$output .= '<div class="w-search-form">';
$output .= '<form class="w-form-row for_text" role="search" action="' . esc_attr( home_url( '/' ) ) . '" method="get">';
$output .= '<div class="w-form-row-field">';

// Apply filter to button label
$text = us_replace_dynamic_value( $text );

$input_atts = array(
	'type' => 'text',
	'name' => 's',
	'placeholder' => $text,
	'aria-label' => $text,
	'value' => esc_html( get_query_var( 's', /* default */ '' ) ),
);
$output .= '<input' . us_implode_atts( $input_atts ) . '/>';

// Additional hidden inputs for specific post types
if ( ! empty( $search_post_type ) ) {

	$search_post_types = explode( ',', $search_post_type );

	if ( count( $search_post_types ) === 1 ) {
		$output .= '<input type="hidden" name="post_type" value="' . esc_attr( $search_post_types[0] ) . '" />';
	} else {
		foreach ( $search_post_types as $_post_type ) {
			$output .= '<input type="hidden" name="post_type[]" value="' . esc_attr( $_post_type ) . '" />';
		}
	}
}

// Additional hidden input for Polylang and WPML Language code
if ( has_filter( 'us_tr_current_language' ) ) {
	$output .= '<input type="hidden" name="lang" value="' . esc_attr( apply_filters( 'us_tr_current_language', NULL ) ) . '" />';
}

$output .= '</div>';

// Clickable button for "Simple" layout only
if ( $layout == 'simple' ) {
	$button_atts = array(
		'class' => 'w-search-form-btn w-btn',
		'type' => 'submit',
		'aria-label' => us_translate( 'Search' ),
	);

	// Add inline styles for shortcodes only
	if ( $us_elm_context == 'shortcode' ) {
		$button_atts['style'] = '';

		if ( ! empty( $icon_size ) ) {
			$button_atts['style'] .= sprintf( '--icon-size:%s;', $icon_size );
		}
		if ( ! empty( $field_text_color ) ) {
			$field_text_color = us_get_color( $field_text_color );
			$button_atts['style'] .= sprintf( 'color:%s!important;', $field_text_color );
		}
	}

	$output .= '<button' . us_implode_atts( $button_atts ) . '>';
	if ( ! empty( $icon ) ) {
		$output .= us_prepare_icon_tag( $icon );
	}
	$output .= '</button>';
}

// Add "Close" button
if ( $us_elm_context != 'shortcode' ) {
	$close_btn_atts = array(
		'aria-label' => us_translate( 'Close' ),
		'class' => 'w-search-close',
		'type' => 'button',
	);
	if ( isset( $open_btn_atts['on'] ) ) {
		$close_btn_atts['on'] = $open_btn_atts['on'];
	}
	$output .= '<button' . us_implode_atts( $close_btn_atts ) . '></button>';
}

$output .= '</form></div></div>';

// The next filter used by the Polylang plugin for proper subdomain redirections
echo apply_filters( 'get_search_form', $output );
