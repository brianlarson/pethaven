<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-relative-urls');
  add_theme_support('soil-jquery-cdn');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage'),
    'secondary_navigation' => __('Secondary Navigation', 'sage'),
    'footer_navigation' => __('Footer Navigation', 'sage'),
    'mobile_navigation' => __('Mobile Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  //add_editor_style(Assets\asset_path('assets/css/app.min.css'));

}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [

    // The sidebar will NOT be displayed if ANY of the following return true.
    is_404(),
    is_front_page(),
    is_page_template('template-fullwidth.php'),
    is_page_template('template-fullwidth-without-jquery.php')

  ]);

  return apply_filters('sage/display_sidebar', $display);
}


/*

Theme assets

*/
function assets() {

  /* Comments */
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }


  if (!is_admin() ) {

    /* CSS */
    wp_enqueue_style('animate-css', Assets\asset_path('assets/css/vendor/animate.min.css'), false, null);
    wp_enqueue_style('font-raleway', 'https://fonts.googleapis.com/css?family=Raleway:400,700', [], null, true);
    wp_enqueue_style('css', Assets\asset_path('assets/css/app.min.css'), false, null);
    wp_enqueue_style('slick-slider', '//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css', false, null);

    /* JS */
    wp_enqueue_script('js-imagesloaded', '//unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js', [], null, true);
    wp_enqueue_script('js-isotope', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.4/isotope.pkgd.min.js', [], null, true);
    wp_enqueue_script('js-slick', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', [], null, true);
    wp_enqueue_script('js-fitvid', '//cdnjs.cloudflare.com/ajax/libs/fitvids/1.2.0/jquery.fitvids.min.js', [], null, true);
    wp_enqueue_script('modernizr', Assets\asset_path('assets/js/vendor/modernizr-custom.js'), [], null, true);
    wp_enqueue_script('js-vendor', Assets\asset_path('assets/js/vendor.min.js'), [], null, true);
    wp_enqueue_script('js', Assets\asset_path('assets/js/app.min.js?ver=123'), ['js-fitvid', 'modernizr', 'js-vendor', 'js-slick', 'js-isotope', 'js-imagesloaded'], null, true);

  }


}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);



function wpdocs_theme_add_editor_styles() {
  add_editor_style( Assets\asset_path('assets/css/admin.min.css') );
}
add_action( 'admin_init', __NAMESPACE__ . '\\wpdocs_theme_add_editor_styles' );




//
// Creating options page
//
if(function_exists('acf_add_options_page')) {

  acf_add_options_page(array(
    'page_title'  => 'Theme Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => 'theme-settings',
    'capability'  => 'edit_posts',
    'icon_url'    => 'dashicons-hammer',
    'redirect'    => false
  ));

}
