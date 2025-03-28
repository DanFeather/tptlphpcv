<?php

 define('WP_DEBUG', true);
 define('WP_DEBUG', false);

// recreates the doctype section, html5boilerplate.com style with conditional classes
// http://scottnix.com/html5-header-with-thematic/
function childtheme_create_doctype() {
    $content = "<!doctype html>" . "\n";
    $content .= '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->'. "\n";
    $content .= '<!--[if IE 8]> <html class="no-js lt-ie9" dir="' . get_bloginfo ('text_direction') . '" lang="'. get_bloginfo ('language') . '"> <![endif]-->' . "\n";
    $content .= "<!--[if gt IE 8]><!-->" . "\n";
    $content .= "<html class=\"no-js\"";
    return $content;
}
add_filter('thematic_create_doctype', 'childtheme_create_doctype', 11);

// creates the head, meta charset, and viewport tags
function childtheme_head_profile() {
    $content = "<!--<![endif]-->";
    $content .= "\n" . "<head>" . "\n";
    $content .= "<meta charset=\"utf-8\" />" . "\n";
    $content .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />" . "\n";
    return $content;
}
add_filter('thematic_head_profile', 'childtheme_head_profile', 11);

// remove meta charset tag, now in the above function
function childtheme_create_contenttype() {
    // silence
}
add_filter('thematic_create_contenttype', 'childtheme_create_contenttype', 11);



// clear useless garbage for a polished head
// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');


// remove built in drop down theme javascripts
// thematictheme.com/forums/topic/correct-way-to-prevent-loading-thematic-scripts/
// function childtheme_remove_superfish() {
//    remove_theme_support('thematic_superfish');
// }
// add_action('wp_enqueue_scripts', 'childtheme_remove_superfish', 9);


// script manager template for registering and enqueuing files
// http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes

function childtheme_script_manager() {
	
	if(!is_admin()){
		wp_deregister_script( 'jquery' );
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
		wp_enqueue_script('jquery');
	}
	
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );
    
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);
    wp_register_script('fitvids-js', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), false, true);
    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), false, true);
	wp_register_script('bxslider', get_stylesheet_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), false, true);
    wp_register_style( 'bx-slider-style', get_stylesheet_directory_uri() . '/jquery.bxslider.css', array(), '1.0', false, true );
    

    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script ('fitvids-js');
	wp_enqueue_script ('bxslider');
    wp_enqueue_style( 'bx-slider-style' );
		
    //always enqueue this last!
    wp_enqueue_script ('custom-js');

}

add_action('wp_enqueue_scripts', 'childtheme_script_manager');

// add favicon to site, add 16x16 or 32x32 "favicon.ico" or .png image to child themes main folder
function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');


// register two additional custom menu slots
function childtheme_register_menus() {
    if (function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('thematic_child_init', 'childtheme_register_menus');


// remove user agent sniffing from thematic theme
// this is what applies classes to the browser type and version body classes
function childtheme_show_bc_browser() {
    return FALSE;
}
add_filter('thematic_show_bc_browser', 'childtheme_show_bc_browser');

// completely remove nav above functionality
function childtheme_override_nav_above() {
    // silence
}

// featured image thumbnail sizing, default is 100x100 set by Thematic
function childtheme_post_thumb_size($size) {
    $size = array(300,300);
    return $size;
}
add_filter('thematic_post_thumb_size', 'childtheme_post_thumb_size');

// add 4th subsidiary aside, currently set up to be a footer widget (#footer-widget) underneath the 3 subs
function childtheme_add_subsidiary($content) {
    $content['Footer Widget Aside'] = array(
            'admin_menu_order' => 550,
            'args' => array (
            'name' => 'Footer Aside',
            'id' => '4th-subsidiary-aside',
            'description' => __('The 4th bottom widget area in the footer.', 'thematic'),
            'before_widget' => thematic_before_widget(),
            'after_widget' => thematic_after_widget(),
            'before_title' => thematic_before_title(),
            'after_title' => thematic_after_title(),
                ),
            'action_hook'   => 'widget_area_subsidiaries',
            'function'      => 'childtheme_4th_subsidiary_aside',
            'priority'      => 90
        );
    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_add_subsidiary');

// set structure for the 4th subsidiary aside
function childtheme_4th_subsidiary_aside() {
    if ( is_active_sidebar('4th-subsidiary-aside') ) {
        echo thematic_before_widget_area('footer-widget');
        dynamic_sidebar('4th-subsidiary-aside');
        echo thematic_after_widget_area('footer-widget');
    }
}

function remove_comments(){
	remove_action('thematic_comments_template','thematic_include_comments',5);
}
add_action('template_redirect','remove_comments');

function remove_branding() { 
	remove_action('thematic_header','thematic_brandingopen',1);
	remove_action('thematic_header','thematic_blogtitle',3);
	remove_action('thematic_header','thematic_blogdescription',5);
	remove_action('thematic_header','thematic_brandingclose',7);
}
add_action('init','remove_branding');

function remove_thematic_blogdesc() {
 remove_action('thematic_header','thematic_blogdescription', 5);
}
add_action('init','remove_thematic_blogdesc');

function remove_menu() {
	remove_action('thematic_header','thematic_access',9);
}
add_action('init', 'remove_menu');

function add_header_items() {
	echo "<div id=\"hdr-aux\">";
	echo "<div id=\"hdr-addy\"><strong>540-745-4428</strong><br />585 Floyd Hwy<br />Floyd, VA 23091</div>";
	echo "<div id=\"hdr-map\"><a href=\"https://goo.gl/maps/oDUSz\"><img src=\"".get_stylesheet_directory_uri()."/images/map.jpg\"></a></div>";
	echo "</div>";
	echo "<div id=\"hdr-logo\"><a href=\"".site_url()."\">The Pine Tavern Lodge</a></div>\n";
}
add_filter('thematic_header', 'add_header_items', 0);



function add_header_menu() {

	$params = array(
		'menu'            => 'main-nav',
		'container'       => 'div',
		'container_class' => 'menu',
		'menu_class'      => 'sf-menu',
		'menu_id'         => 'menu-top-nav',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>'
	);
	
	echo "<div id=\"access\">\n";
		wp_nav_menu( $params );
	echo "</div>";
}
add_filter('thematic_belowheader', 'add_header_menu', 3);

function childtheme_postheader() {
    global $post;
 
    if (is_page()) { 
        // nothing
    } elseif (is_404()) { ?>
        <h1 class="entry-title">Page Not Found</h1>
    <?php } elseif (is_single()) { 
		echo "<h1 class=\"entry-title\">".$post->post_title."</h1>";
	} else { 
		if ($post->post_type == 'post') { 
			echo "<h1 class=\"entry-title\">".$post->post_title."</h1>";
		} 
	}
}
add_filter ('thematic_postheader', 'childtheme_postheader');


function childtheme_override_postheader_postmeta() {
	// add back in case by case where needed---
	return '';
	// $postmeta = '<div class="entry-meta">';
	// $postmeta .= thematic_postmeta_entrydate();
	// $postmeta .= "</div><!-- .entry-meta -->\n";
	// 
	// return apply_filters('thematic_postheader_postmeta',$postmeta); 
}

 function remove_postfooter() { /* nothing */ }
 add_filter('thematic_postfooter', 'remove_postfooter');

 function add_footer() {
	 echo "<p>&copy; ".date('Y')." The Pine Tavern Lodge</p>";
 }
 add_filter('thematic_footer', 'add_footer');
 
 
// example for hiding unused widget areas inside the WordPress admin
function childtheme_hide_areas($content) {
    unset($content['Index Top']);
    unset($content['Index Insert']);
    unset($content['Index Bottom']);
    unset($content['Single Top']);
    unset($content['Single Insert']);
    unset($content['Single Bottom']);
    unset($content['Page Top']);
    unset($content['Page Bottom']);
    unset($content['1st Subsidiary Aside']);
    unset($content['2nd Subsidiary Aside']);
    unset($content['3rd Subsidiary Aside']);
    unset($content['Secondary Aside']);

    return $content;
}
add_filter('thematic_widgetized_areas', 'childtheme_hide_areas');
