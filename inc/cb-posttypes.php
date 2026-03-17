<?php
/**
 * Custom Post Types Registration
 *
 * This file contains the code to register custom post types for the theme.
 *
 * @package cb-starbox2026
 */

/**
 * Register custom post types for the theme.
 *
 * @return void
 */
function cb_register_post_types() {

	register_post_type(
		'case_study',
		array(
			'labels'          => array(
				'name'               => 'Case Studies',
				'singular_name'      => 'Case Study',
				'add_new_item'       => 'Add New Case Study',
				'edit_item'          => 'Edit Case Study',
				'new_item'           => 'New Case Study',
				'view_item'          => 'View Case Study',
				'search_items'       => 'Search Case Studies',
				'not_found'          => 'No case studies found',
				'not_found_in_trash' => 'No case studies in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-portfolio',
			'supports'        => array( 'title', 'editor', 'thumbnail' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'showcase',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'team_member',
		array(
			'labels'          => array(
				'name'               => 'Team Members',
				'singular_name'      => 'Team Member',
				'add_new_item'       => 'Add New Team Member',
				'edit_item'          => 'Edit Team Member',
				'new_item'           => 'New Team Member',
				'view_item'          => 'View Team Member',
				'search_items'       => 'Search Team Members',
				'not_found'          => 'No team members found',
				'not_found_in_trash' => 'No team members in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 27,
			'menu_icon'       => 'dashicons-groups',
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'team',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'testimonial',
		array(
			'labels'          => array(
				'name'               => 'Testimonials',
				'singular_name'      => 'Testimonial',
				'add_new_item'       => 'Add New Testimonial',
				'edit_item'          => 'Edit Testimonial',
				'new_item'           => 'New Testimonial',
				'view_item'          => 'View Testimonial',
				'search_items'       => 'Search Testimonials',
				'not_found'          => 'No testimonials found',
				'not_found_in_trash' => 'No testimonials in trash',
			),
			'has_archive'     => false,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_position'   => 28,
			'menu_icon'       => 'dashicons-format-quote',
			'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'rewrite'         => array(
				'slug'       => 'testimonials',
				'with_front' => false,
			),
		)
	);
}

add_action( 'init', 'cb_register_post_types' );
