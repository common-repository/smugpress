<?php

if (!class_exists("SMWidgetBase")) {
    class SMWidgetBase {
        var $widget_type;
        var $config;

        //constructor
        function SMWidgetBase( $config ) { $this->config = $config; }

        function type() { return $this->widget_type; }

        function widget_admin_panel() {
        }

        function widget_control() {
            $options = $this->config->getall();

            if ( $_POST['smugmug-submit'] ) {
                $this->config->set( "smugmug_widget_title",
                    strip_tags(stripslashes($_POST['smugmug_widget_title']))
                );


                // Reset the cache
                $this->reset_cache();
                $this->update_cache();
            }
        }

        function reset_cache() {
                $this->config->set( "smugmug_cache_expire_time", 1 );
        }

        function is_cache_expired() {
            $expire = $this->config->get( "smugmug_cache_expire_time" );

            //error_log( "Testing cache if $expire > " . time() );

            return isset($expire) && $expire > time() ? false : true;
        }

        function update_cache() {
            Global $wpdb;
            //error_log( "Updating Widget Cache" );

            $this->config->create_cache_table();

            $images = $this->fetch();

            ////error_log( "Found " . sizeof($images) . " images" );

            $base_sql = "INSERT INTO " . $this->config->widget_cache_table() .
                        "( title, image, link ) VALUES ";

            for( $i = 0; $i < sizeof($images); $i++ ) {
                $insert .= strlen($insert) ? "," : "";
                $insert .= sprintf( "('%s', '%s', '%s')",
                                    $wpdb->escape($images[$i]["title"]),
                                    $wpdb->escape($images[$i]["image"]),
                                    $wpdb->escape($images[$i]["link"]) );

                // Limit query length to ~1MB
                if( strlen($insert) > 1047500 ) {
                    ////error_log( $base_sql . $insert );
                    $wpdb->query( $base_sql . $insert );
                    $insert = null;
                }
            }

            if( $insert ) {
                ////error_log( $base_sql . $insert );
                $wpdb->query( $base_sql . $insert );
                $insert = null;
            }

            $ttl = $this->config->get( "smugmug_cache_expire" );
            $expire = time() + $ttl;

            ////error_log( "Setting expire ttl: $ttl, time: $expire, current: " . time() );
            $this->config->set( "smugmug_cache_expire_time", $expire );
        }

        function get_image() {
            if( $this->config->get( "smugmug_use_cache" ) == 1 ) {
                return $this->get_image_from_cache();
            }
            else {
                return $this->get_image_no_cache();
            }
        }


        function get_image_no_cache() {
            $rss_images = $this->fetch();

            $index = rand( 0, sizeof($rss_images) - 1 );

            if( $rss_images[$index]["id"] || $rss_images[$index]["guid"] ) {
                $image["title"] = $rss_images[$index]["title"];
                $image["link"]  = $rss_images[$index]["link"];
                $image["image"] = $rss_images[$index]["id"] ?
                    $rss_images[$index]["id"] : $rss_images[$index]["guid"];
            }
            return $image;
        }


        function get_image_from_cache() {
            Global $wpdb;

            if( $this->is_cache_expired () )
                $this->update_cache();

            $sql = "SELECT title, image, link FROM " . $this->config->widget_cache_table() .
                   " ORDER BY RAND() LIMIT 1";

            error_log( "Query: $sql" );

            return $wpdb->get_row( $sql, ARRAY_A );
        }

    }
} //End Class SNWidgetBase


?>
