<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying the Search page
 */

$search_page = get_post( us_get_option( 'search_page' ) );

// Output specific page
if ( $search_page ) {
	if ( has_filter( 'us_tr_object_id' ) ) {
		$search_page = get_post( (int) apply_filters( 'us_tr_object_id', $search_page->ID, 'page', TRUE ) );
	}

	get_header();
	?>
	<main id="page-content" class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>

		<?php
		do_action( 'us_before_page' );

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {

			// Titlebar, if it is enabled in Theme Options
			us_load_template( 'templates/titlebar' );

			// START wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'before' ) );
		}

		if ( $content_area_id = us_get_page_area_id( 'content' ) AND get_post_status( $content_area_id ) != FALSE ) {
			global $us_is_search_page_block;
			$us_is_search_page_block = TRUE;
			us_load_template( 'templates/content' );
		} else {
			us_open_wp_query_context();

			us_add_page_shortcodes_custom_css( $search_page->ID );

			us_close_wp_query_context();

			// Set search page ID as $us_page_block_id for grid shortcodes
			us_add_to_page_block_ids( $search_page->ID );

			echo apply_filters( 'the_content', $search_page->post_content );

			us_remove_from_page_block_ids();
		}

		if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {
			// AFTER wrapper for Sidebar
			us_load_template( 'templates/sidebar', array( 'place' => 'after' ) );
		}

		do_action( 'us_after_page' );
		?>

	</main>
	<?php

	get_footer();

	// Output default archive layout
} else {
	us_load_template( 'templates/archive' );
}
