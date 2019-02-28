<?php
/*
Plugin Name: FADA Blog
Plugin URI: http://www.pulse-creative.com/
Description: FADA.org Blog.
Version: 1.0
Author: Ashraful Kabir(sky)
Author URI: http://www.pulse-creative.com/
License: GPLv2
Date: 10/20/2015
*/
?>
<?php

add_action( 'init', 'create_blog_post' );

function create_blog_post() {
    register_post_type( 'fada-blog',
        array(
            'labels' => array(
                'name' => 'FADA Blog',
                'singular_name' => 'FADA Blog',
                'add_new' => 'Add Blog Post',
                'add_new_item' => 'Add New Blog Post',
                'edit' => 'Edit',
                'edit_item' => 'Edit Blog',
                'new_item' => 'New Blog',
                'view' => 'View',
                'view_item' => 'View Blog',
                'search_items' => 'Search Blog',
                'not_found' => 'No Blog Post found',
                'not_found_in_trash' => 'No Blog post found in Trash',
                'parent' => 'Parent Director'
            ),
 
            'public' => true,
            'menu_position' => 15,
           // 'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'supports' => array( 'title','thumbnail','editor','excerpt'),
            'taxonomies' => array('post_tag'),
            'hierarchical' =>true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'rewrite' => false,
            'public'=>true,
            'menu_icon' => plugins_url( 'images/blog.png', __FILE__ ),
            'has_archive' => true
        )
    );
    
    flush_rewrite_rules();
}



?>
