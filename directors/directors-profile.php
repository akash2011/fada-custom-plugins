<?php
/*
Plugin Name: FADA Board Of Directors
Plugin URI: http://www.pulse-creative.com/
Description: FADA.org Board of Directors Profile.
Version: 1.0
Author: Ashraful Kabir(sky)
Author URI: http://www.pulse-creative.com/
License: GPLv2
*/
?>
<?php

add_action( 'init', 'create_directors_profile' );

function create_directors_profile() {
    register_post_type( 'fada_directors',
        array(
            'labels' => array(
                'name' => 'FADA Director',
                'singular_name' => 'FADA Director',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Director',
                'edit' => 'Edit',
                'edit_item' => 'Edit Director',
                'new_item' => 'New Director',
                'view' => 'View',
                'view_item' => 'View Director',
                'search_items' => 'Search Director',
                'not_found' => 'No Director found',
                'not_found_in_trash' => 'No Director found in Trash',
                'parent' => 'Parent Director',
                'capability_type' => 'fada_directors',
                    'capabilities' => array(
                    'publish_posts' => 'publish_fada_directors',
                    'edit_posts' => 'edit_fada_directors',
                    'edit_others_posts' => 'edit_others_fada_directors',
                    'delete_posts' => 'delete_fada_directors',
                    'delete_others_posts' => 'delete_fada_directors',
                    'read_private_posts' => 'read_private_fada_directors',
                    'edit_post' => 'edit_fada_directors',
                    'delete_post' => 'delete_fada_directors',
                    'read_post' => 'read_fada_directors'
                ),
                    'map_meta_cap'    => true,
                ),
         
            'public' => true,
            'menu_position' => 15,
           // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
             'supports' => array( 'title','thumbnail','editor','custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/director-profile.png', __FILE__ ),
            'has_archive' => true
        )
    );
}


add_filter( 'map_meta_cap', 'director_meta_cap', 10, 4 );

function director_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a fada director, get the post and post type object. */
	if ( 'edit_fada_directors' == $cap || 'delete_fada_directors' == $cap || 'read_fada_directors' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );

		/* Set an empty array for the caps. */
		$caps = array();
	}

	/* If editing a fada_directors, assign the required capability. */
	if ( 'edit_fada_directors' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->edit_posts;
		else
			$caps[] = $post_type->cap->edit_others_posts;
	}

	/* If deleting a fada_directors, assign the required capability. */
	elseif ( 'delete_fada_directors' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->delete_posts;
		else
			$caps[] = $post_type->cap->delete_others_posts;
	}

	/* If reading a private fada_directors, assign the required capability. */
	elseif ( 'read_fada_directors' == $cap ) {

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
