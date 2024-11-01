<?php
// Increase fetch_rss timeout
define('MAGPIE_FETCH_TIME_OUT', 200);

if (!class_exists("SMConduit")) {
    class SMConduit {

        var $rss_retry = 3;
        var $rss_gallery_uri = 'http://www.smugmug.com/hack/feed.mg?Type=nickname&Data=%s&format=atom03';
        var $rss_album_uri =   'http://www.smugmug.com/hack/feed.mg?Type=gallery&Data=%s&format=atom03';

        //constructor
        function SMConduit() {
        }

        function get_galleries_from_rss( $nickname, $count = '' ) {
            $feed = sprintf( $this->rss_gallery_uri, $nickname );

            $success = false;
            for( $j = 1; $j <= $this->rss_retry && $success == false ; $j++ ) {
                $rss = fetch_rss( $feed );

                error_log( sprintf( "Fetching (attemt %d of %d): %s",
                                    $j, $this->rss_retry, $feed ) );

                if( !$rss || !$rss->items ) {
                    error_log( "Failed Fetch (attempt $j): $feed" );
                }
                else {
                    $suceess = true;
                    break;
                }
            }

            if( isset($count) && $count > 0 ) {
                error_log( "Limiting to  " . $count . " records" );
                $items = array_slice( $rss->items, 0, $count );
            } else {
                $items = $rss->items;
            }

            error_log( "Found " . sizeof($items) . " Albums" );

            for( $i = 0; $i < sizeof($items); $i++ ) {
                $url = $items[$i]["id"] ? $items[$i]["id"] : $items[$i]["guid"];
                $gallery = preg_replace( '/.*gallery\/(\d+).*/is', '$1', $url );
                $key     = preg_replace( '/.*gallery\/\d+_(.*)/is', '$1', $url );

                error_log( "Gallery Found: $gallery" );
                $items[$i]['gallery_id']  = $gallery;
                $items[$i]['gallery_key'] = $key;
            }

            return $items;
        }

        function get_gallery_ids_from_rss( $nickname, $count = '' ) {
            $galleries = $this->get_galleries_from_rss( $nickname, $count );

            for( $i = 0; $i < sizeof($galleries); $i++ ) {
                $id[$i] = $galleries[$i]['gallery_id'] . '_' . $galleries[$i]['gallery_key'];
            }

            return $id;
        }

        function get_images_from_rss( $gallery_ids ) {
            error_log( "RSS album id fetch from rss. " . sizeof($gallery_ids) . " Albums" );
            for( $i = 0; $i < sizeof($gallery_ids); $i++ ) {
                $feed = sprintf( $this->rss_album_uri, $gallery_ids[$i] );
                $urls[$i] = $feed;
            }

            return $this->get_images_from_rss_url( $urls );
        }


        function get_images_from_rss_url( $urls ) {
            error_log( "RSS URL fetch from rss. " . sizeof($urls) . " URLS" );
            for( $i = 0; $i < sizeof($urls); $i++ ) {


                $success = false;
                for( $j = 1; $j <= $this->rss_retry && $success == false ; $j++ ) {
                    error_log( sprintf( "Fetching (attemt %d of %d): %s",
                                        $j, $this->rss_retry, $urls[$i] ) );
                    $rss = fetch_rss( $urls[$i], $count );

                    if( !$rss || !$rss->items ) {
                        error_log( "Failed Fetch (attempt $j): $urls[$i]");
                    }
                    else {
                        $suceess = true;
                        $items = $items ? array_merge($items, $rss->items) : $rss->items;
                        break;
                    }
                }

            }

            for( $i = 0; $i < sizeof($items); $i++ ) {
                $items[$i]["image"] = $items[$i]["id"] ?
                                          $items[$i]["id"] : $items[$i]["guid"];
            }

            return $items;
        }
    }
} //End Class SMConduit


?>
