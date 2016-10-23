<?php

namespace gresnet\posttype;

class postType {

	private static $instance;

	protected function __construct() {
		add_action( 'init', array($this,'slide_init') );
		add_action( 'init', array($this,'taxonomies_init'));
		add_filter( 'post_updated_messages', array($this,'slide_updated_messages') );
	} 

	public function taxonomies_init() {
		register_taxonomy(
		'slider_tag',
		array('slide'),
		array(	'label' => __( 'Sliders', 'sssliderwp'  ),
				'public' => true,
				'show_ui'=> true,
				'show_admin_column' => true,
				'hierarchical' => false,
				
			)
		);
	}

	public function slide_init() {
		register_post_type( 'slide', array(
			'labels'            => array(
				'name'                => __( 'Slides', 'sssliderwp' ),
				'singular_name'       => __( 'Slide', 'sssliderwp' ),
				'all_items'           => __( 'All Slides', 'sssliderwp' ),
				'new_item'            => __( 'New slide', 'sssliderwp' ),
				'add_new'             => __( 'Add New', 'sssliderwp' ),
				'add_new_item'        => __( 'Add New slide', 'sssliderwp' ),
				'edit_item'           => __( 'Edit slide', 'sssliderwp' ),
				'view_item'           => __( 'View slide', 'sssliderwp' ),
				'search_items'        => __( 'Search slides', 'sssliderwp' ),
				'not_found'           => __( 'No slides found', 'sssliderwp' ),
				'not_found_in_trash'  => __( 'No slides found in trash', 'sssliderwp' ),
				'parent_item_colon'   => __( 'Parent slide', 'sssliderwp' ),
				'menu_name'           => __( 'Slides', 'sssliderwp' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'supports'          => array( 'title','editor','thumbnail' ),
			'has_archive'       => true,
			'rewrite'           => true,
			'query_var'         => true,
			'menu_icon'         => 'dashicons-images-alt',
			'show_in_rest'      => true,
			'rest_base'         => 'slide',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'taxonomies'			=> array('slider_tag','tags'),
		) );

	}
	

	public function slide_updated_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		$messages['slide'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Slide updated. <a target="_blank" href="%s">View slide</a>', 'sssliderwp'), esc_url( $permalink ) ),
			2 => __('Custom field updated.', 'sssliderwp'),
			3 => __('Custom field deleted.', 'sssliderwp'),
			4 => __('Slide updated.', 'sssliderwp'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Slide restored to revision from %s', 'sssliderwp'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Slide published. <a href="%s">View slide</a>', 'sssliderwp'), esc_url( $permalink ) ),
			7 => __('Slide saved.', 'sssliderwp'),
			8 => sprintf( __('Slide submitted. <a target="_blank" href="%s">Preview slide</a>', 'sssliderwp'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9 => sprintf( __('Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>', 'sssliderwp'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			10 => sprintf( __('Slide draft updated. <a target="_blank" href="%s">Preview slide</a>', 'sssliderwp'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);

		return $messages;
	}

	public static function instance() {
		if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
	}

}
