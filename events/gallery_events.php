<?php
/*
Plugin Name: FADA Events
Plugin URI: http://www.pulse-creative.com/
Description: FADA.org Gallery Events.
Version: 1.0
Author: Ashraful Kabir(sky)
Author URI: http://www.pulse-creative.com/
License: GPLv2
*/
?>
<?php

add_action( 'init', 'create_events' );

function create_events() {
    register_post_type( 'fada-events',
        array(
            'labels' => array(
                'name' => 'FADA Events',
                'singular_name' => 'FADA Events',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Event',
                'edit' => 'Edit',
                'edit_item' => 'Edit Event',
                'new_item' => 'New Event',
                'view' => 'View',
                'view_item' => 'View Event',
                'search_items' => 'Search Event',
                'not_found' => 'No Event found',
                'not_found_in_trash' => 'No Event found in Trash',
                'parent' => 'Parent Director',
                'capability_type' => 'fada-events',
                'capabilities' => array(
				'publish_posts' => 'publish_fada-events',
				'edit_posts' => 'edit_fada-events',
				'edit_others_posts' => 'edit_others_fada-events',
				'delete_posts' => 'delete_fada-events',
				'delete_others_posts' => 'delete_fada-events',
				'read_private_posts' => 'read_private_fada-events',
				'edit_post' => 'edit_fada-events',
				'delete_post' => 'delete_fada-events',
				'read_post' => 'read_fada-events',
			),
                'map_meta_cap'    => true
            ),
 
            'public' => true,
            'menu_position' => 15,
           // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'supports' => array( 'title','thumbnail','editor'),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/event.png', __FILE__ ),
            'has_archive' => true
        )
    );
    
    flush_rewrite_rules();
}

add_filter( 'map_meta_cap', 'my_map_meta_cap2', 10, 4 );

function my_map_meta_cap2( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a fada-events, get the post and post type object. */
	if ( 'edit_fada-events' == $cap || 'delete_fada-events' == $cap || 'read_fada-events' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );

		/* Set an empty array for the caps. */
		$caps = array();
	}

	/* If editing a fada-events, assign the required capability. */
	if ( 'edit_fada-events' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->edit_posts;
		else
			$caps[] = $post_type->cap->edit_others_posts;
	}

	/* If deleting a fada-events, assign the required capability. */
	elseif ( 'delete_fada-events' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->delete_posts;
		else
			$caps[] = $post_type->cap->delete_others_posts;
	}

	/* If reading a private fada-events, assign the required capability. */
	elseif ( 'read_fada-events' == $cap ) {

		if ( 'private' != $post->post_status )
			$caps[] = 'read';
		elseif ( $user_id == $post->post_author )
			$caps[] = 'read';
		else
			$caps[] = $post_type->cap->read_private_posts;
	}

	/* Return the capabilities required by the user. */
	return $caps;
}

?>
