<?php
/*
Plugin Name: FADA Galleries
Plugin URI: http://www.pulse-creative.com/
Description: FADA.org Members Galleries.
Version: 1.0
Author: Ashraful Kabir(sky)
Author URI: http://www.pulse-creative.com/
Date:10/13/2015
License: GPLv2
*/
?>
<?php


add_action( 'init', 'create_galleries' );

function create_galleries() {
    register_post_type( 'fada-galleries',
        array(
            'labels' => array(
                'name' => 'FADA Gallery',
                'singular_name' => 'FADA Gallery',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Gallery',
                'edit' => 'Edit',
                'edit_item' => 'Edit Gallery',
                'new_item' => 'New Gallery',
                'view' => 'View',
                'view_item' => 'View Gallery',
                'search_items' => 'Search Gallery',
                'not_found' => 'No Gallery found',
                'not_found_in_trash' => 'No Gallery found in Trash',
                'parent' => 'Parent Gallery',
                'capability_type' => 'fada-galleries',
                'capabilities' => array(
				'publish_posts' => 'publish_fada-galleries',
				'edit_posts' => 'edit_fada-galleries',
				'edit_others_posts' => 'edit_others_fada-galleries',
				'delete_posts' => 'delete_fada-galleries',
				'delete_others_posts' => 'delete_fada-galleries',
				'read_private_posts' => 'read_private_fada-galleries',
				'edit_post' => 'edit_fada-galleries',
				'delete_post' => 'delete_fada-galleries',
				'read_post' => 'read_fada-galleries',
			),
                'map_meta_cap'    => true,
            ),
 
            'public' => true,
            'menu_position' => 15,
           // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'supports' => array( 'title','thumbnail','editor'),
            'taxonomies' => array('category','post_tag'), 
            'menu_icon' => plugins_url( 'images/gallery.png', __FILE__ ),
            'has_archive' => true
        )
    );

    flush_rewrite_rules();
}

add_filter( 'map_meta_cap', 'my_map_meta_cap', 10, 4 );

function my_map_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a fada-galleries, get the post and post type object. */
	if ( 'edit_fada-galleries' == $cap || 'delete_fada-galleries' == $cap || 'read_fada-galleries' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );

		/* Set an empty array for the caps. */
		$caps = array();
	}

	/* If editing a fada-galleries, assign the required capability. */
	if ( 'edit_fada-galleries' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->edit_posts;
		else
			$caps[] = $post_type->cap->edit_others_posts;
	}

	/* If deleting a fada-galleries, assign the required capability. */
	elseif ( 'delete_fada-galleries' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->delete_posts;
		else
			$caps[] = $post_type->cap->delete_others_posts;
	}

	/* If reading a private fada-galleries, assign the required capability. */
	elseif ( 'read_fada-galleries' == $cap ) {

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
