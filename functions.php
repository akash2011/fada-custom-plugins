<?php
if (!session_id()) {
    session_start();
}

update_option('siteurl','http://www.newsite.fada.org/');
update_option('home','http://www.newsite.fada.org/');

/**
 * FADA theme
 * 
 * @package bootstrap FADA theme
 */

function alter_content($content) {
	//Set locations
	$oldDir = 'http://pulse-devel3.com/fada';
	$newDir = 'http://www.newsite.fada.org';
	$content = str_replace($oldDir,$newDir,$content);
}
add_filter('the_content', alter_content);

remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

/* log out redirect to home page url */
add_action('wp_logout','go_home');
function go_home(){
  wp_redirect( home_url() );
  exit();
}

/**
 * Required WordPress variable.
 */
if (!isset($content_width)) {
	$content_width = 1170;
}

wp_enqueue_script( 'custom_js', get_template_directory_uri(). '/js/main.js', array( 'jquery'), '', true ); wp_localize_script( 'custom_js', 'ajax_posts', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'noposts' => __('No older posts found', 'fireproduct'), ));

/* for removing widget from dashboard home page */
function remove_dashboard_meta() {
    //remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Removes the 'incoming links' widget
   // remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Removes the 'plugins' widget
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'WordPress News' widget
    //remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); //Removes the secondary widget
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Removes the 'Quick Draft' widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Removes the 'Recent Drafts' widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
}
add_action('admin_init', 'remove_dashboard_meta');


function get_short_title(){
   $title = get_the_title();
   if(strlen($title) >27){
	$title = substr($title,0,27);
	if($title[26]==' '){
		$title = substr($title,0,26);
	}
	$title = $title . '...';
   }
   return $title;
}

// Create Slider Post Type
require( get_template_directory() . '/slider/slider_post_type.php' );

// Create Slider
require( get_template_directory() . '/slider/slider.php' );

add_filter('pre_get_posts', 'nav_post_type');
function nav_post_type($query) {
  if(is_category() || is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('nav_menu_item','post','articles','tag','archive', 'fada-blog');
    $query->set('post_type',$post_type);
	return $query;
    }
}
// Changing excerpt length
function new_excerpt_length($length) {
return 100;
}
add_filter('excerpt_length', 'new_excerpt_length');

// Changing excerpt more
function new_excerpt_more($more) {
return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

function get_home_slider_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_sliderexcerpt = substr($excerpt, 0,200);
    return $the_sliderexcerpt;
}
function get_the_events_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str = substr($excerpt, 0,300);
    return $the_str;
}
function get_home_events_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str2 = substr($excerpt, 0,150);
    return $the_str2;
}
function get_the_blog_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str3 = substr($excerpt, 0, 200);
    return $the_str3;
}
function get_the_blogindex_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str4 = substr($excerpt, 0, 200);
    return $the_str4;
}
function get_the_blogfeature_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str5 = substr($excerpt, 0, 400);
    return $the_str5;
}
function get_the_blogsingle_excerpt(){
    $excerpt = get_the_content();
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $the_str6 = substr($excerpt, 0, 400);
    return $the_str6;
}


// FADA directors profile    
    add_shortcode( 'fada_directors', 'display_fada_director' );

    function display_fada_director(){
        $args = array(
            'post_type' => 'fada_directors',
            'post__not_in' => array(376),
            'post_status' => 'publish',
            'order'=>'asc'
        );
    
        
        $string = '';
        $query = new WP_Query( $args );
        if( $query->have_posts() ){
            $string .= '<div class="row director-container">';
			$counter=0;
            while( $query->have_posts() ){
            $query->the_post();
			$fields = get_fields();
			$quote="'";
			if($counter%3==0){
			$string .= '<div class="col-lg-4 col-md-4 director-box director-start">';	
			}else{
            $string .= '<div class="col-lg-4 col-md-4 director-box">';
			}
            
                if (has_post_thumbnail( $post->ID ) ){ 
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                $string .='<div class="director-thumb"><img src="'.$image[0].'" width="100%" alt="FADA.org" id="director-thumb"><img src="'.$fields['roolover_image'].'" width="100%" alt="FADA.org" id="director-thumb"></div><div class="director-text"><h3 class="director-title">'. get_the_title().'</h3>';
                
                }
                if(get_the_content()){
                $string .= '<p class="designation">'.get_the_content().'</p>';
                
                }
                $string .= '</div></div>';
				$counter = $counter + 1;
            }
            $string .= '</div>';

        }
        wp_reset_query();
        return $string;
    }

// For executive directors
 add_shortcode( 'fada_executive_directors', 'display_fada_execdirector' );

    function display_fada_execdirector(){
        $args2 = array(
            'post_type' => 'fada_directors',
            'p' =>376,
            'post_status' => 'publish'
        );    
        
        $string2 = '';
        $query = new WP_Query( $args2 );
       if( $query->have_posts() ){
            $string2 .= '<div class="row director-container"><h2 class="exe-director-title">EXECUTIVE DIRECTOR</h2>';
            while( $query->have_posts() ){
            $query->the_post();
			$quote="'";
            $fields = get_fields();
            $string2 .= '<div class="col-lg-4 col-md-3 director-box">';
            
            
                if (has_post_thumbnail( $post->ID ) ){ 
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                $string2 .='<div class="exdirector-thumb"><img src="'.$image[0].'" width="100%" alt="'.get_the_title().'"><img src="'.$fields['roolover_image'].'" width="100%" alt="FADA.org" id="exdirector-thumb"></div><div class="exdirector-text"><h3 class="director-title"><a href="#">'. get_the_title().'</a></h3>';
                
                }
               
                $string2 .= '<div class="designation">'.$fields['email'].'</div>';
                $string2 .= '<div class="designation" style="margin-top:2px;">'.$fields['phone'].'</div>';
                
                                                
                 

                                  
                $string2 .= '</div></div>';
               
            }
           // $string2 .= '</div>';
        }
        
        wp_reset_query();
        return $string2;
    }
// directors profile end

// FADA galleries
   add_shortcode( 'fada_galleries', 'display_fada_galleries' );
    function display_fada_galleries(){
        $args3 = array(
            'post_type' => 'fada-galleries',
            'posts_per_page' =>-1,
            'post_status' => 'publish',
            'order'=>'asc'
        );   
        
        $string3 = '';
        $query3 = new WP_Query( $args3 );
        if( $query3->have_posts() ){
            $string3 .= '<div class="row">';
            while( $query3->have_posts() ){
            $query3->the_post();
            
            $string3 .= '<div class="col-lg-4 col-md-4 gallery-box">';
                               
                if (has_post_thumbnail( $post->ID ) ){ 
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                $string3 .='<div class="gallery-indexbox-content"><p class="location">no</p><h3 class="gallery-index-title"><a href="'.esc_url(get_permalink()).'">'. get_the_title().'</a></h3></div><img src="'.$image[0].'" width="100%" alt="FADA.org">';
                
                }
                                                
                $string3 .= '</div>';
               
            }
            $string3 .= '</div>';
        }
        wp_reset_query();
        return $string3;
    }


/* Shortcode for gallery Event index page */
add_shortcode( 'fada-events', 'display_fada_events' );

    function display_fada_events(){
        $args4 = array(
            'post_type' => 'fada-events',
            'posts_per_page' =>20,
            'post_status' => 'publish',
            'order'=>'asc'
        );
    
        
        $string4 = '';
        $query4 = new WP_Query($args4);
        if( $query4->have_posts() ){
            $string4 .= '<div class="row">';
            while( $query4->have_posts() ){
            $query4->the_post();
            
            $string4 .= '<div class="col-lg-4 col-md-4 gallery-box">';
                               
                if (has_post_thumbnail( $post->ID ) ){ 
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                $string4 .='<div class="gallery-indexbox-content"><p class="location">'.the_field('location').'</p><h3 class="gallery-index-title"><a href="'.esc_url(get_permalink()).'">'. get_the_title().'</a></h3></div><img src="'.$image[0].'" width="100%" alt="FADA.org">';
                
                }
                                                
                $string4 .= '</div>';
               
            }
            $string4 .= '</div>';
        }
        wp_reset_query();
        return $string4;
    }


if (!function_exists('bootstrapBasicSetup')) {
	/**
	 * Setup theme and register support wp features.
	 */
	function bootstrapBasicSetup() 
	{
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * 
		 * copy from underscores theme
		 */
		load_theme_textdomain('bootstrap-basic', get_template_directory() . '/languages');

		// add theme support post and comment automatic feed links
		add_theme_support('automatic-feed-links');

		// enable support for post thumbnail or feature image on posts and pages
		add_theme_support('post-thumbnails');

		// allow the use of html5 markup
		// @link https://codex.wordpress.org/Theme_Markup
		add_theme_support('html5', array('caption', 'comment-form', 'comment-list', 'gallery', 'search-form'));

		// add support menu
		register_nav_menus(array(
			'primary' => __('Primary Menu', 'bootstrap-basic'),
		));

		// add post formats support
		add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));

		// add support custom background
		add_theme_support(
			'custom-background', 
			apply_filters(
				'bootstrap_basic_custom_background_args', 
				array(
					'default-color' => 'ffffff', 
					'default-image' => ''
				)
			)
		);
	}// FADA Setup
}
add_action('after_setup_theme', 'bootstrapBasicSetup');


if (!function_exists('FADAWidgetsInit')) {
	/**
	 * Register widget areas
	 */
	function FADAWidgetsInit() 
	{
		register_sidebar(array(
			'name'          => __('Header right', 'bootstrap-fada'),
			'id'            => 'header-right',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));

		register_sidebar(array(
			'name'          => __('Navigation bar right', 'bootstrap-fada'),
			'id'            => 'navbar-right',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		));

		register_sidebar(array(
			'name'          => __('Sidebar left', 'bootstrap-fada'),
			'id'            => 'sidebar-left',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));

		register_sidebar(array(
			'name'          => __('Sidebar right', 'bootstrap-fada'),
			'id'            => 'sidebar-right',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        

		register_sidebar(array(
			'name'          => __('Footer First', 'bootstrap-fada'),
			'id'            => 'footer-first',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));

		register_sidebar(array(
			'name'          => __('Footer Second', 'bootstrap-fada'),
			'id'            => 'footer-second',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('Footer Third', 'bootstrap-fada'),
			'id'            => 'footer-third',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));

		register_sidebar(array(
			'name'          => __('Footer Fourth', 'bootstrap-fada'),
			'id'            => 'footer-fourth',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('Gallery Events', 'bootstrap-fada'),
			'id'            => 'gallery-events',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
         register_sidebar(array(
			'name'          => __('Most Recent Post', 'bootstrap-fada'),
			'id'            => 'most-recent-posts',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('Fine Art Members', 'bootstrap-fada'),
			'id'            => 'fineart-members',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('Newsletter', 'bootstrap-fada'),
			'id'            => 'newsletter',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('QUICK SEARCHES', 'bootstrap-fada'),
			'id'            => 'quick-search',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('FADA Gallery Location', 'bootstrap-basic'),
			'id'            => 'gallery-location',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
         register_sidebar(array(
			'name'          => __('FADA Gallery Index', 'bootstrap-fada'),
			'id'            => 'gallery-index',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('FADA Gallery Artist', 'bootstrap-fada'),
			'id'            => 'gallery-artist',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
         register_sidebar(array(
			'name'          => __('FADA Events Sub Menu', 'bootstrap-fada'),
			'id'            => 'events-submenu',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		));
        
        register_sidebar(array(
			'name'          => __('Blog Artcle Share', 'bootstrap-fada'),
			'id'            => 'blog-share',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="blog-share-title">',
			'after_title'   => '</p>',
		));
        
        
	}// FADAWidgetsInit
}
add_action('widgets_init', 'FADAWidgetsInit');


if (!function_exists('FadaEnqueueScripts')) {
	/**
	 * Enqueue scripts & styles
	 */
	function FadaEnqueueScripts() 
	{
		wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/css/bootstrap.min.css');
		wp_enqueue_style('bootstrap-theme-style', get_template_directory_uri() . '/css/bootstrap-theme.min.css');
        wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/css/fada-responsive.css');
		wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/css/font-awesome.min.css');
		wp_enqueue_style('main-style', get_template_directory_uri() . '/css/main.css');

		wp_enqueue_script('modernizr-script', get_template_directory_uri() . '/js/vendor/modernizr.min.js');
		wp_enqueue_script('respond-script', get_template_directory_uri() . '/js/vendor/respond.min.js');
		wp_enqueue_script('html5-shiv-script', get_template_directory_uri() . '/js/vendor/html5shiv.js');
		wp_enqueue_script('jquery');
		wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array(), false, true);
		wp_enqueue_script('main-script', get_template_directory_uri() . '/js/main.js', array(), false, true);
		wp_enqueue_style('bootstrap-basic-style', get_stylesheet_uri());
        
	}// FadaEnqueueScripts
}
add_action('wp_enqueue_scripts', 'FadaEnqueueScripts');


/**
 * admin page displaying help.
 */
if (is_admin()) {
	require get_template_directory() . '/inc/BootstrapBasicAdminHelp.php';
	$bbsc_adminhelp = new BootstrapBasicAdminHelp();
	add_action('admin_menu', array($bbsc_adminhelp, 'themeHelpMenu'));
	unset($bbsc_adminhelp);
}

function remove_admin_menu_items() {
	$remove_menu_items = array(__('Comments'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}

add_action('admin_menu', 'remove_admin_menu_items');

/**
 * Custom post type tags for fada-blog
 *
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if(is_category() || is_tag()) {
	$post_type = get_query_var('post_type');
	if($post_type)
		$post_type = $post_type;
	else
		$post_type = array('post','fada-blog');
	$query->set('post_type',$post_type);
	return $query;
	}
}
*/


function my_searchwp_include_only_category( $ids, $engine, $terms ) {
	// if and only if a category ID was submitted:
	// we only want to apply this limitation to the default search engine
	// if you would like to change that you can do so here
	if ( ! empty( $_GET['swp_category_limiter'] ) && 'default' == $engine ) {
		$category_id = absint( $_GET['swp_category_limiter'] );
		$category_args = array(
				'category' => $category_id,  // limit to the chosen category ID
				'fields'   => 'ids',         // we only want the IDs of these posts
                
			);
        
        $category_args = asort(	$category_args);
		$ids = get_posts( $category_args );
		// if there were no posts returned we need to force an empty result
		if ( 0 == count( $ids ) ) {
			$ids = array( 0 ); // this will force SearchWP to return zero results
		}
	}
	// always return our $ids
	return $ids;
}
add_filter( 'searchwp_include', 'my_searchwp_include_only_category', 50, 3 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';


/**
 * Custom dropdown menu and navbar in walker class
 */
require get_template_directory() . '/inc/BootstrapBasicMyWalkerNavMenu.php';


/**
 * Template functions
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * --------------------------------------------------------------
 * Theme widget & widget hooks
 * --------------------------------------------------------------
 */
require get_template_directory() . '/inc/widgets/BootstrapBasicSearchWidget.php';
require get_template_directory() . '/inc/template-widgets-hook.php';

// For more Blogs post
function more_post_ajax(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] :3;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;

    header("Content-Type: text/html");

    $args = array(
        'suppress_filters' => true,
        'post_type' => 'fada-blog',
        'posts_per_page' => $ppp,
        'paged'    => $page,
    );

    $loop = new WP_Query($args);

    $out = '';
    $i=1;
    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
    if($i % 3 == 0){
        $out .= '<div class="col-md-4 blog-index-post-right">';
    }else if( $i % 3 == 1){
		echo '<div class="col-md-4 blog-index-post blog-left">';
	}else{
        $out .= '<div class="col-md-4 blog-index-post">';
    }
    
    $out .= '<div class="blog-index-post-content">
         <p class="event-date">'.get_the_time('F j, Y').'</p>
        <h2><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_short_title().'</a></h2>
        <p class="blog-excerpt">'.get_the_blog_excerpt().'<a style="color:#20BBFC;" href="'.get_permalink().'">   [...]  Read more.</a></p></div>
        <p class="blog-post-thumb">
            <a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a>
        </p></div>';
    
    $i++;
    endwhile;
    endif;   
    wp_reset_postdata();
   
    die($out);
     echo '<div style="clear:both;"></div>';
    
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');

// For more Events post
function more_event(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] :0;

    header("Content-Type: text/html");

    $args = array(
        'suppress_filters' => true,
        'post_type' =>'fada-events',
        'posts_per_page' => $ppp,
        'offset'=>6,
        'paged' => $page,
        'order'=>'desc'
    );

    $loop = new WP_Query($args);

    $out = '';
    $i=1;
    if ($loop -> have_posts()){
        while ($loop -> have_posts()){
            $loop -> the_post();
        if($i % 3 == 0){
            $out .= '<div class="col-md-4 blog-index-post-right">';
        }
        else{
            $out .= '<div class="col-md-4 blog-index-post">';
        }

        $out .='<div class="blog-index-post-content">
            <p class="event-date">'.get_the_time('F j, Y').'</p>
            <h2><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_short_title().'</a></h2>
            <p class="events-excerpt">'.get_home_events_excerpt().'<a style="color:#20BBFC;" href="'.get_permalink().'">   [...]  Read more.</a>                    </p></div>
            <p class="blog-post-thumb"><a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a></p>';

        $out .='</div>';

        $i++;
        }
    }  
    wp_reset_postdata();
    // echo '<div style="clear:both;"></div>';
    die($out);
     echo '<div style="clear:both;"></div>';
    
}

add_action('wp_ajax_nopriv_more_event', 'more_event');
add_action('wp_ajax_more_event', 'more_event');

// For more galleries
function more_fada_galleries(){
    
$ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] :6;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] :0;

    header("Content-Type: text/html");
    
    //$do_not_duplicate = array(); // set befor loop variable as 
    
   // $do_not_duplicate[] = $post->ID; // remember ID's in loop

    $argsg = array(
        'suppress_filters' => true,
        'post_type' => 'fada-galleries',
        'posts_per_page' =>$ppp,
       // 'post_not_in'=>array(),
        'orderby' =>'title',
        'order' =>'asc',
        'paged'    => $page
    );

    $loop = new WP_Query($argsg);

    $outg = '';
    $i=1;
   // $do_not_duplicate = array(); // set befor loop variable as
    
    if ($loop -> have_posts()){
        while ($loop -> have_posts()){
            $loop -> the_post();            
            
            $fields = get_fields();
                       
            //echo $fields['slide_link'];
          //  if ( $post->ID == $do_not_duplicate ) continue;
            
            
        if($i % 3 == 0){
            $outg .= '<div class="col-md-4 gallery-index-post-right">';
        }
        else{
            $outg .= '<div class="col-md-4 gallery-index-post">';
        }
        
           // $outg.='<p>'.the_field('email').'</p>';
        $outg .='<div class="gallery-indexbox-content">
            <p class="location">'.$fields['location'].'</p>
            <h3 class="gallery-index-title"><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_short_title().'</a></h3>
            </div>
            <p class="blog-post-thumb"><a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a></p>';

        $outg .='</div>';

        $i++;
        }
    } 
    //echo the_field('location');
    
    wp_reset_postdata();
    // echo '<div style="clear:both;"></div>';
    die($outg);
   echo '<div style="clear:both;"></div>';
    
}

add_action('wp_ajax_nopriv_more_fada_galleries', 'more_fada_galleries');
add_action('wp_ajax_more_fada_galleries', 'more_fada_galleries');

add_shortcode( 'fada-galleries', 'more_fada_galleries' );
/* Custome gallery filter */

function get_gallery_custom_search() {
global $wpdb;

$keywords = preg_replace( '/^[0-9a-zA-Z-]/', '', $_GET['keyword'] ); // only allow letters, numbers and dashes...ie PG-13 and not PG#13
//$category = preg_replace( '/[^0-9]/', '', $_GET['cat'] ); // only allow numbers        
 
      
        if (keywords != '') {
                $keywords_sql = " AND wpostmeta1.meta_key = 'keywords' AND wpostmeta1.meta_value = '$keywords' ";
        }
           
 
        // This is based on a post from wordpress.org forums where the search terms were predefined in the query whereas mine pulls from a custom search form.  http://wordpress.org/support/topic/custom-fields-search#post-909384.
        $querystr = "
                SELECT DISTINCT * FROM fada-gallery as wposts WHERE wposts.ID = wpostmeta1.post_id".$keywords_sql." ORDER BY wposts.post_date DESC";
 
        $searched_posts = $wpdb->get_results($querystr);
 
        return $searched_posts;
        // By returning the seaerched posts, we can create a custom foreach loop on search.php
 
}