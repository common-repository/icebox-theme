<?php
/**
* Plugin Name: IceBox Theme
* Plugin URI#: http://www.prrple.com
* Description: An admin theme to freshen up your WordPress back end.
* Version: 1.0.2
* Author: Alex B
* Author URI#: http://www.prrple.com
*/

// ==================================================================
// ADD CUSTOM STYLES TO BACK END
// ==================================================================

function icebox_stylesheet() {
  wp_enqueue_style( 'prefix-style', plugins_url('css/icebox.styles.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'icebox_stylesheet' );
add_action( 'login_enqueue_scripts', 'icebox_stylesheet' );

// ==================================================================
// DEFINE ICEBOX COLOR SCHEME
// ==================================================================

function icebox_color_schemes() {
  $suffix = is_rtl() ? '-rtl' : '';
  $theme_dir = get_template_directory_uri();
  wp_admin_css_color(
    'icebox',
    _x( 'IceBox', 'admin color scheme' ),
    plugins_url('css/icebox.colors.css', __FILE__),
    array( '#23282d', '#32373c', '#3ecfd9', '#22a9b2' )
  );
}
add_action( 'admin_init', 'icebox_color_schemes');

// REMOVE AN EXISTING COLOUR SCHEME
function my_limit_admin_color_options(){
  global $_wp_admin_css_colors;
  unset($_wp_admin_css_colors['ectoplasm']);
}
add_action( 'admin_init', 'my_limit_admin_color_options', 1 );

// SET DEFAULT FOR NEW USERS
function set_default_admin_color($user_id) {
  $args = array(
    'ID' => $user_id,
    'admin_color' => 'icebox'
  );
  wp_update_user( $args );
}
add_action('user_register', 'set_default_admin_color');

// ==================================================================
// FILTER ADMIN MENU ORDER
// ==================================================================

function icebox_menu_order( $menu_order ) {
  // define desired menu positions
  $new_positions = array(
    'edit.php?post_type=page' => 2, // pages
    'edit.php' => 3, // posts
    'upload.php' => 4, // media
    'edit-comments.php' => 5, // comments
  );
  // helper function to move an element inside an array
  function move_element(&$array, $a, $b) {
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
  }
  // move the items if found in the original menu_positions
  foreach( $new_positions as $value => $new_index ) {
    if( $current_index = array_search( $value, $menu_order ) ) {
      move_element($menu_order, $current_index, $new_index);
    }
  }
  return $menu_order;
};
add_filter('custom_menu_order', function() { return true; });
add_filter('menu_order', 'icebox_menu_order');

// ==================================================================
// HIDE ADMIN TOOLBAR ITEMS
// ==================================================================

function icebox_toolbar_links() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');          // Remove the Wordpress logo
  $wp_admin_bar->remove_menu('about');            // Remove the about Wordpress link
  $wp_admin_bar->remove_menu('wporg');            // Remove the Wordpress.org link
  $wp_admin_bar->remove_menu('documentation');    // Remove the Wordpress documentation link
  $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
  $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
  // $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
  // $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
  // $wp_admin_bar->remove_menu('updates');          // Remove the updates link
  // $wp_admin_bar->remove_menu('comments');         // Remove the comments link
  // $wp_admin_bar->remove_menu('new-content');      // Remove the content link
  // $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
  // $wp_admin_bar->remove_menu('my-account');       // Remove the user details tab
}
add_action( 'wp_before_admin_bar_render', 'icebox_toolbar_links' );

// ==================================================================
// REMOVE DASHBOARD PANELS
// ==================================================================

// Remove WP admin dashboard widgets
function isa_disable_dashboard_widgets() {
  remove_meta_box('dashboard_primary', 'dashboard', 'core'); // Remove WordPress News and Events
  // remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Remove "At a Glance"
  // remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Remove "Activity" which includes "Recent Comments"
  // remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Remove Quick Draft
}
add_action('admin_menu', 'isa_disable_dashboard_widgets');
remove_action('welcome_panel', 'wp_welcome_panel');

// ==================================================================
// REMOVE FOOTER
// ==================================================================

function icebox_footer_left() {
  // echo '<span id="footer-thankyou"></span>';
}
add_filter('admin_footer_text', 'icebox_footer_left');

function icebox_footer_right() {
  remove_filter( 'update_footer', 'core_update_footer' );
}
add_action( 'admin_menu', 'icebox_footer_right' );

// ==================================================================
// WRAP IFRAMES WITH DIV
// ==================================================================

// function div_wrapper($content) {
//   $pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
//   preg_match_all($pattern, $content, $matches);
//   foreach ($matches[0] as $match) {
//     $wrappedframe = '<div class="embed-wrap">' . $match . '</div>';
//     $content = str_replace($match, $wrappedframe, $content);
//   }
//   return $content;
// }
// add_filter('the_content', 'div_wrapper');

?>
