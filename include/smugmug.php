<?php

require_once(SMUGMUGPATH . "include/conduit.php");
require_once(SMUGMUGPATH . "include/buttons.php");
require_once(SMUGMUGPATH . "include/widget.php");
require_once(SMUGMUGPATH . "include/config.php");
require_once(SMUGMUGPATH . "include/gallery.php");
require_once(SMUGMUGPATH . "include/admin_panel.php");

if (!class_exists("SmugMug")) {
    class SmugMug {
        var $sm_widget      = null;
        var $sm_admin_panel = null;
        var $sm_config      = null;
        var $sm_gallery     = null;
        var $sm_buttons     = null;

        //constructor
        function SmugMug() {}

        function widget() {
            if( is_null($this->sm_widget) ) {
                $this->sm_widget = new SMWidget( $this->config() );
            }

            return $this->sm_widget;
        }

        function admin_panel() {
            if( is_null($this->sm_admin_panel) ) {
                $this->sm_admin_panel = new SMAdminPanel( $this->config() );
            }

            return $this->sm_admin_panel;
        }

        function config() {
            if( is_null($this->sm_config) ) {
                $this->sm_config = new SMConfig();
            }

            return $this->sm_config;
        }

        function gallery() {
            if( is_null($this->sm_gallery) ) {
                $this->sm_gallery = new SMGallery( $this->config() );
            }

            return $this->sm_gallery;
        }

        function buttons() {
            if( is_null($this->sm_buttons) ) {
                $this->sm_buttons = new SMButtons( $this->config() );
            }

            return $this->sm_buttons;
        }

        function add_headers() {
            $this->add_css_headers();
            $this->add_js_headers();
        }

        function add_admin_headers() {
            $this->add_css_headers();
            $this->add_admin_js_headers();
        }

        function add_css_headers() {
           $path = SMUGMUGPATH . "/css";
           $dir = opendir($path);
           while( $file = readdir($dir) ) {
               if( preg_match( "/^[A-Za-z0-9]+.css$/", $file ) ) {
                   $url = SMUGMUGURL . "/css/$file";
                   echo '<link rel="stylesheet" href="' . $url . '" type="text/css" />' . "\n";
               }
           }
        }

        function add_admin_js_headers() {
           $path = SMUGMUGPATH . "/js/tinymce";
           $dir = opendir($path);
           while( $file = readdir($dir) ) {
           error_log( "Reading from $file" );
               if( preg_match( "/^[A-Za-z0-9]+.js$/", $file ) ) {
                   $url = SMUGMUGURL . "/js/tinymce/$file";
                   echo '<script type="text/javascript" src="' . $url . '"></script>' . "\n";
               }
           }
        }

        function add_js_headers() {
           $path = SMUGMUGPATH . "/js";
           $dir = opendir($path);
           while( $file = readdir($dir) ) {
               if( preg_match( "/^[A-Za-z0-9]+.js$/", $file ) ) {
                   $url = SMUGMUGURL . "/js/$file";
                   echo '<script type="text/javascript" src="' . $url . '"></script>' . "\n";
                   error_log( '<script type="text/javascript" src="' . $url . '"></script>' . "\n" );
               }
           }
        }

        function create_admin_panel() {
            if (function_exists('add_options_page')) {
                add_options_page( 'SmugPress', 'SmugPress', 9,
                                  basename(__FILE__),
                                  array($this->admin_panel(), 'print_admin_panel') );
            }
        }

        function add_tinymce_buttons() {
            if( $this->buttons() )
                $this->sm_buttons->init();
        }

    }

}

?>
