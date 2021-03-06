<?php

/* ==========================================================================
   General Setup
   ========================================================================== */

if ( ! function_exists( '_s_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function _s_setup() {

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on _s, use a find and replace
     * to change '_s' to the name of your theme in all the template files
     */
    load_theme_textdomain( '_s', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ) );

    /**
     * Enable support for Post Thumbnails on posts and pages
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    add_image_size( '640x', 640 );

    /**
     * Add custom image sizes to display settings in Media Library
     * @param  array $sizes list of sizes
     * @return array
     */
    function _s_media_library_image_options( $sizes ) {

        $new_sizes = array_merge( $sizes, array(
            // name must be string, not integer
            '640x' => __( 'Main Content' ),
        ) );

        return $new_sizes;

    }
    add_filter( 'image_size_names_choose', '_s_media_library_image_options' );

    /**
     * This theme uses wp_nav_menu() in one location.
     */
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', '_s' ),
    ) );

}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );





/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
    $GLOBALS['content_width'] = apply_filters( '_s_content_width', 640 );
}
add_action( 'after_setup_theme', '_s_content_width', 0 );





/**
 * Set X-UA-Compatible for IE
 *
 * Sends headers to browser in an attempt to have IE render the website using
 * their latest rendering engine (i.e. IE=edge). Additionally, attempts to
 * activate Chrome Frame add-on if it exists.
 *
 * IE browser may show compatibility icon in address bar when using HTML5 Boilerplate's
 * heading markup which contains conditional comments on HTML tag.
 *
 * Setting the X-UA-Compatible meta tag will not work if placed after the HTML
 * conditional comments.
 *
 * While there are workarounds, the preferred method is to send headers, not markup.
 *
 * IE should attempt to render website using its "stable" engine. I believe as
 * of IE10, this is edge.
 *
 * @link Explanation of values: http://stackoverflow.com/a/14637972/3163972
 * @link Solution: http://stackoverflow.com/a/9624500/3163972
 * @link HTML5 Boilerplate's Take: https://github.com/h5bp/html5-boilerplate/issues/378
 * @link Reasons Compatibility Mode may be set: http://stackoverflow.com/a/3726605/3163972
 */
function _s_add_header_xua() {
    header( 'X-UA-Compatible: IE=edge,chrome=1' );
}
add_action( 'send_headers', '_s_add_header_xua' );





/**
 * Disable Emojis
 *
 * WordPress 4.2 introduced emoji JS and CSS in the page's head
 */
function _s_disable_emoji() {

    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_tinymce_emoji' );

}
add_action( 'init', '_s_disable_emoji', 1 );
/**
 * filter function used to remove the tinymce emoji plugin
 */
function disable_tinymce_emoji( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
}





/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function _s_widgets_init() {

    register_sidebar( array(
        'name'          => __( 'Sidebar', '_s' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget__heading">',
        'after_title'   => '</h3>',
    ) );

}
add_action( 'widgets_init', '_s_widgets_init' );





/**
 * Enqueue scripts and styles
 */
function _s_scripts() {

    if ( ! is_admin() ) {

        // Styles
        wp_enqueue_style( 'googlefonts', add_query_arg( 'family', 'Open+Sans:400,300,700|Roboto:400,100,900italic', '//fonts.googleapis.com/css' ) );
        wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/dist/css/main.min.css' );
        wp_enqueue_style( '_s-style', get_stylesheet_uri(), array( 'main' ) );

        // IE Conditional
        wp_register_style( 'no-mq', get_template_directory_uri() . '/assets/dist/css/no-mq.css' );
        $GLOBALS['wp_styles']->add_data( 'no-mq', 'conditional', 'lte IE 8' );
        wp_enqueue_style( 'no-mq' );

        // Scripts
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/dist/js/plugins/modernizr-3.0.0.min.js' );
        wp_enqueue_script( 'plugins', get_template_directory_uri() . '/assets/dist/js/plugins.min.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/dist/js/main.min.js', array( 'jquery', 'plugins' ), false, true );

        // values available to js
        wp_localize_script( 'main', 'ELEV', array(
                'siteUrl'      => site_url(),
                'directoryUrl' => get_template_directory_uri()
            )
        );
    }

    // comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

}
add_action( 'wp_enqueue_scripts', '_s_scripts' );





/**
 * Remove inline styling for recent comments widget
 */
function _s_remove_recent_comments_style() {

        global $wp_widget_factory;

        remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

    }
add_action( 'widgets_init', '_s_remove_recent_comments_style' );





/**
 * Remove Link When Adding Media
 *
 * Adding media to a WYSIWYG editor can sometimes automatically include a link
 * to the file. This sets the default behavior for all users to none.
 *
 * @return void
 */
function _s_media_linking() {

    $setting = get_option( 'image_default_link_type' );

    if ( $setting !== 'none' ) {
        update_option( 'image_default_link_type', 'none' );
    }

}
add_action( 'admin_init', '_s_media_linking', 10 );





/* ==========================================================================
   Project Specific
   ========================================================================== */

/**
 * Order Menu Items
 */
function _s_custom_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;
    return array(
        'index.php', // Dashboard
        'edit.php?post_type=page', // Pages
        'edit.php', // Posts
        'gf_edit_forms', // Forms
        'upload.php' // Media
    );
}
add_filter('custom_menu_order', '_s_custom_menu_order');
add_filter('menu_order', '_s_custom_menu_order');





/**
 * Modify main query
 */
function _s_modify_main_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    // Events Taxonomy
    // if ( is_tax( 'type' ) ) {
        // $query->set( 'meta_key', 'date' );
        // $query->set( 'orderby', 'date' );
    // }
    // Volunteers
    // if ( is_post_type_archive( 'volunteers' ) ) {
    //  $query->set( 'posts_per_page', -1 );
    //  $query->set( 'orderby', 'menu_order' );
    //  $query->set( 'order', 'ASC' );
    //  $query->set( 'meta_key', 'active' );
    //  $query->set( 'meta_value', true );
    // }
}
add_action( 'pre_get_posts', '_s_modify_main_query', 1 );





/**
* Add style dropdown to MCE editor
*/
function _s__mce_editor_buttons( $buttons ) {

   array_unshift( $buttons, 'styleselect' );
   return $buttons;
}
add_filter( 'mce_buttons_2', '_s__mce_editor_buttons' );





/**
* Add styles/classes to the "Styles" drop-down
*/
function _s__mce_before_init( $settings ) {

   $style_formats = array(
        array(
            'title'    => 'Subheading',
            'selector' => 'h1,h2,h3,h4,h5,h6',
            'classes'  => 'subheading'
        ),
        array(
           'title'    => 'Button: Black',
           'selector' => 'a',
           'classes'  => 'btn'
        ),
        array(
           'title'    => 'Button: Primary',
           'selector' => 'a',
           'classes'  => 'btn primary'
        ),
        array(
           'title'    => 'Button: Secondary',
           'selector' => 'a',
           'classes'  => 'btn secondary'
        ),
        array(
           'title'    => 'Button: Small',
           'selector' => 'a',
           'classes'  => 'small'
        ),
        array(
           'title'    => 'Button: Large',
           'selector' => 'a',
           'classes'  => 'large'
        ),
        // Primary Text Colors
        // array(
        //    'title'   => 'Text: Green',
        //    'inline'  => 'span',
        //    'classes' => 'text-conifer'
        // ),
        // array(
        //    'title'   => 'Text: Gray',
        //    'inline'  => 'span',
        //    'classes' => 'text-corduroy'
        // ),
        array(
           'title'   => 'Text: Fine Print',
           'inline'  => 'span',
           'classes' => 'fine-print'
        ),
        array(
           'title'    => 'Text: Capitalize',
           'selector' => 'h1,h2,h3,h4,h5,h6,p,div,dt,dd,li,address',
           'classes'  => 'text-capitalize'
        ),
        array(
           'title'    => 'Text: Uppercase',
           'selector' => 'h1,h2,h3,h4,h5,h6,p,div,dt,dd,li,address',
           'classes'  => 'text-upper'
        )
   );

   $settings['style_formats'] = json_encode( $style_formats );

   return $settings;

}
add_filter( 'tiny_mce_before_init', '_s__mce_before_init' );
