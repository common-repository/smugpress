<?php
/*
Plugin Name: SmugPress
Plugin URI: http://www.grepsedia.com/development/smugpress
Description: Smugpress is a Wordpress plugin that simplifies SmugMug photo integration into your WordPress blog.  It has a editor plugin as well as a sidebar widget to display random images.  I wrote this plugin for my wife, this way I didn't have to write the posts for her.
Author: Bill French
Version: 0.2.5
Author URI: http://www.grepsedia.com

    SmugPress is released under the GNU General Public License (GPL)
    http://www.gnu.org/licenses/gpl.txt

    This is a WordPress plugin (http://wordpress.org) and widget
    (http://automattic.com/code/widgets/).
*/

define('SMUGMUGPATH', ABSPATH."wp-content/plugins/smugpress/");
define('SMUGMUGURL', get_bloginfo( 'url' ) . "/wp-content/plugins/smugpress");

/* Include some WP libs */
include_once(ABSPATH . WPINC . '/rss.php');
include_once(ABSPATH . '/wp-config.php');
include_once(ABSPATH . WPINC . '/wp-db.php');

/* Include our php */
require_once(SMUGMUGPATH . "include/smugmug.php");


/* Our main object */
if (class_exists("SmugMug")) {
    $smugmug = new SmugMug();
}


/* Our Actions and Filters */
if( isset($smugmug) ) {
    //Actions
    add_action('wp_head',        array(&$smugmug, 'add_headers'), 1);
    add_action('plugins_loaded', array( $smugmug->widget(), 'init' ));

    add_action('admin_menu',     array(&$smugmug, 'create_admin_panel'), 1);
    add_action('admin_head',     array(&$smugmug, 'add_admin_headers'), 1);
    add_action('init',           array(&$smugmug, 'add_tinymce_buttons'), 1);

    //Filters
    add_filter('the_content', array($smugmug->gallery(), 'filter'), 1);

    //Activation
    register_activation_hook( __FILE__, 'smugmug_activate' );
}

if( !function_exists('smugmug_activate') ) {
    function smugmug_activate() {
        $config = new SMConfig();
        $config->init();
    }
}
?>
