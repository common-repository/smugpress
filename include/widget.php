<?php

if (!class_exists("SMWidget")) {
    class SMWidget {
        var $config;
        var $driver;

        //constructor
        function SMWidget( $config ) {
            $this->config = $config;
            $type = $this->config->get( "smugmug_widget_criteria_type" );

            switch ($type) {
            case "all":
                $file  = 'rss.php';
                $class = 'SMWidgetRSS';
                break;
            case "rss":
                $file  = 'rss.php';
                $class = 'SMWidgetRSS';
                break;
            case "rss_galleries":
                $file  = 'rss_gallery.php';
                $class = 'SMWidgetRSSGallery';
                break;
            case "select":
                $file  = 'rss.php';
                $class = 'SMWidgetRSS';
                break;
            case "recent":
                $file  = 'rss.php';
                $class = 'SMWidgetRSS';
                break;
            case "after":
                $file  = 'rss.php';
                $class = 'SMWidgetRSS';
                break;
            };

            if( $file ) {
                require_once( SMUGMUGPATH . "include/widget_drivers/$file" );
                $this->driver = new $class($config);
            }
        }



        function init() {
            register_sidebar_widget('SmugMug', array(&$this, 'print_widget') );
            register_widget_control('SmugMug', array(&$this, 'widget_control') );
        }

        function print_widget( $args ) {
            extract($args);
            $title = $this->config->get( "smugmug_widget_title" );

            echo $before_widget;
            echo $before_title . $title . $after_title;
            $this->print_widget_image();
            echo $after_widget;
        }

        function print_widget_image() {
            if( ! isset($this->driver) ) {
                echo "[ Object Creation Error ]";
                return;
            }

            $display_title = $this->config->get( "smugmug_widget_display_title" );

            $image = $this->driver->get_image();

            if( ! isset($image) ) {
                echo "[ Feed Read Error ]";
                return;
            }


            echo '<div id="smugmug_image">' . "\n";

            if( $display_title && $image["title"] ) {
                echo '<div class="sm_title">' . "\n";
                echo $image["title"] . "\n";
                echo '</div>' . "\n";
            }

            if( $image["image"] ) {
                echo '<div class="sm_image">' . "\n";

                if( $image["link"] )
                   echo '<a href="' . $image["link"] . '">';

                echo '<img src="' . $image["image"] . '" />';

                if( $image["link"] )
                   echo '</a>';

                echo '</div>' . "\n";
            }

            if( $image["comment"] ) {
                echo '<div class="sm_comment">' . "\n";
                echo $image["comment"] . "\n";
                echo '</div>' . "\n";
            }

            echo '</div>' . "\n";
        }

        function widget_control() {
            $this->driver->widget_control();
        }

        function print_widget_options() {
            $this->driver->print_widget_options();
        }
    }
} //End Class SmugMug


?>
