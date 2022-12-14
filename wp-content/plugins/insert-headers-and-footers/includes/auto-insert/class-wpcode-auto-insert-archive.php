<?php
/**
 * Class to auto-insert snippets on archive pages, taxonomies, etc.
 *
 * @package wpcode
 */

/**
 * Class WPCode_Auto_Insert_Archive.
 */
class WPCode_Auto_Insert_Archive extends WPCode_Auto_Insert_Type {

	/**
	 * Load the available options and labels.
	 *
	 * @return void
	 */
	public function init() {
		$this->label     = __( 'Categories, Archives, Tags, Taxonomies', 'insert-headers-and-footers' );
		$this->locations = array(
			'before_excerpt'      => __( 'Insert Before Excerpt', 'insert-headers-and-footers' ),
			'after_excerpt'       => __( 'Insert After Excerpt', 'insert-headers-and-footers' ),
			'between_posts'       => __( 'Between Posts', 'insert-headers-and-footers' ),
			'archive_before_post' => __( 'Before Post', 'insert-headers-and-footers' ),
			'archive_after_post'  => __( 'After Post', 'insert-headers-and-footers' ),
		);
	}

	/**
	 * Checks if we are on an archive page and we should be executing hooks.
	 *
	 * @return bool
	 */
	public function conditions() {
		return is_archive();
	}

	/**
	 * Add hooks specific to single posts.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'the_excerpt', array( $this, 'insert_before_excerpt' ) );
		add_filter( 'the_excerpt', array( $this, 'insert_after_excerpt' ) );
		add_action( 'the_post', array( $this, 'insert_between_posts' ), 10, 2 );
		add_action( 'the_post', array( $this, 'insert_before_after_post' ), 10, 2 );
	}

	/**
	 * Output snippet before excerpt on archive pages.
	 *
	 * @param string $excerpt The excerpt text.
	 *
	 * @return string
	 */
	public function insert_before_excerpt( $excerpt ) {
		$snippets = $this->get_snippets_for_location( 'before_excerpt' );

		foreach ( $snippets as $snippet ) {
			$excerpt = wpcode()->execute->get_snippet_output( $snippet ) . $excerpt;
		}

		return $excerpt;
	}

	/**
	 * Output snippet after excerpt on archive pages.
	 *
	 * @param string $excerpt The excerpt text.
	 *
	 * @return string
	 */
	public function insert_after_excerpt( $excerpt ) {
		$snippets = $this->get_snippets_for_location( 'after_excerpt' );

		foreach ( $snippets as $snippet ) {
			$excerpt .= wpcode()->execute->get_snippet_output( $snippet );
		}

		return $excerpt;
	}

	/**
	 * Output snippets between posts in a list of posts.
	 *
	 * @param WP_Post  $post_data The post.
	 * @param WP_Query $query The query.
	 *
	 * @return void
	 */
	public function insert_between_posts( $post_data, $query ) {
		// If the query has at least two posts to display snippets between.
		if ( $query->post_count < 2 ) {
			return;
		}
		// If the current post is not the first one in the list.
		if ( $query->current_post < 1 ) {
			return;
		}
		// If the current post is not the last one in the list.
		if ( $query->post_count <= $query->current_post ) {
			return;
		}
		$snippets = $this->get_snippets_for_location( 'between_posts' );

		foreach ( $snippets as $snippet ) {
			echo wpcode()->execute->get_snippet_output( $snippet );
		}
	}

	/**
	 * Output snippets before or after posts.
	 *
	 * @param WP_Post  $post_data The post.
	 * @param WP_Query $query The query.
	 *
	 * @return void
	 */
	public function insert_before_after_post( $post_data, $query ) {
		$snippets = $this->get_snippets_for_location( 'archive_before_post' );

		foreach ( $snippets as $snippet ) {
			$insert_number = $snippet->get_auto_insert_number();
			if ( $query->current_post === $insert_number - 1 ) {
				echo wpcode()->execute->get_snippet_output( $snippet );
			}
		}

		$snippets = $this->get_snippets_for_location( 'archive_after_post' );

		foreach ( $snippets as $snippet ) {
			$insert_number = $snippet->get_auto_insert_number();
			if ( $query->current_post === $insert_number ) {
				echo wpcode()->execute->get_snippet_output( $snippet );
			}
		}
	}
}
