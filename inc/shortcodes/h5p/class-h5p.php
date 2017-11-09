<?php

namespace Pressbooks\Shortcodes\H5P;

class H5P {

	/**
	 * Anchor we append to URLs to hint that it's external
	 */
	const ANCHOR = '#pb-h5p';

	/**
	 * Is this the HP5 plugin we're looking for?
	 *
	 * @return bool
	 */
	public function isActive() {
		if ( shortcode_exists( 'h5p' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Override H5P shortcode
	 */
	public function override() {
		remove_shortcode( 'h5p' );
		add_shortcode( 'h5p', [ $this, 'shortcodeHandler' ] );
		add_filter( 'h5p_embed_access', '__return_false' );
	}

	/**
	 * @see \H5P_Plugin::shortcode
	 *
	 * @param int $h5p_id
	 *
	 * @return array
	 */
	public function getContent( $h5p_id ) {
		$found = [];
		try {
			if ( class_exists( '\H5P_Plugin' ) ) {
				$content = \H5P_Plugin::get_instance()->get_content( $h5p_id );
				if ( is_array( $content ) ) {
					$found = $content;
				}
			}
		} catch ( \Exception $e ) {
			// Do nothing
		}
		return $found;
	}

	/**
	 * @see \H5P_Plugin::shortcode
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function shortcodeHandler( $atts ) {

		global $id; // This is the Post ID, [@see WP_Query::setup_postdata, ...]
		global $wpdb;

		$h5p_title = __( 'Interactive H5P Content', 'pressbooks' );
		$h5p_url = get_permalink( $id );

		if ( isset( $atts['slug'] ) ) {
			$suppress = $wpdb->suppress_errors();
			$row = $wpdb->get_row(
				$wpdb->prepare( "SELECT id FROM {$wpdb->prefix}h5p_contents WHERE slug=%s", $atts['slug'] ),
				ARRAY_A
			);
			if ( isset( $row['id'] ) ) {
				$atts['id'] = $row['id'];
			}
			$wpdb->suppress_errors( $suppress );
		}

		$h5p_id = isset( $atts['id'] ) ? intval( $atts['id'] ) : 0;

		if ( $h5p_id ) {
			$content = $this->getContent( $h5p_id );
			if ( ! empty( $content['title'] ) ) {
				$h5p_title = $content['title'];
			}
		}

		// HTML
		$anchor = self::ANCHOR;
		$html = "<p class='h5p'>{$h5p_title}:<br/><a href='{$h5p_url}{$anchor}'>{$h5p_url}</a></p>";

		return $html;
	}

}
