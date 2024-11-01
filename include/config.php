<?php

if (!class_exists("SMConfig")) {
    class SMConfig {
        var $optionsName = "SmugMugOptions";
        var $config;
        var $conduit;
        var $st_widget_cache_table = "smugmug_widget_cache";

        //constructor
        function SMConfig() {
            $this->read_options();
            $this->conduit = new SMConduit();
        }

        function init() {
            $this->get_options();
            //$this->create_cache_table();
        }

        //Returns an array of admin options
        function read_options() {
            $this->config = get_option($this->optionsName);
        }

        function get( $field ) {
            if( ! isset( $this->config ) )
                $this->read_options;

            return $this->config["$field"];
        }

        function set( $field, $value ) {
            if( ! isset( $this->config ) )
                $this->read_options;

            if( $value != $this->get( "$field" ) ) {
                $this->config["$field"] = $value;
            }

            $this->update( $this->config );
        }

        function getall( ) {
            if( ! isset( $this->config ) )
                $this->read_options;

            return $this->config;
        }

        function update( $options ) {
            update_option($this->optionsName, $options);
        }

        //Returns an array of admin options
        function get_options() {
            $smugmugOptions = array(
                'smugmug_user'                    => null,
                'smugmug_widget_criteria_type'    => 'rss_galleries',
                'smugmug_widget_title'            => 'SmugMug',
                'smugmug_show_buttons'            => true,
                'smugmug_use_cache'               => true,
                'smugmug_cache_expire'            => 3600,
            );

            $devOptions = get_option($this->optionsName);

            if( !empty($devOptions) ) {
                foreach( $devOptions as $key => $option )
                    $smugmugOptions[$key] = $option;
            }

            $this->update($smugmugOptions);
            return $smugmugOptions;
        }

        function widget_cache_table() {
            Global $wpdb;

            return $wpdb->prefix . $this->st_widget_cache_table;
        }

        function create_cache_table() {
            Global $wpdb;

            //error_log( "Creating Cache Tables" );
            $sql = "DROP TABLE IF EXISTS " . $this->widget_cache_table();
            //error_log( "Run query: $sql" );

            $wpdb->query( $sql );

            $sql = "CREATE TABLE `" . $this->widget_cache_table() . "` (" .
                      "`title` varchar(255) default NULL, " .
                      "`image` TEXT default NULL, " .
                      "`link`  TEXT default NULL, " .
                      "`create_date`  TIMESTAMP " .
                   ") ENGINE=MyISAM";

            //error_log( "Run query: $sql" );

            $wpdb->query( $sql );

            return true;
        }
    }
}

?>
