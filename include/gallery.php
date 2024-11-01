<?php

if (!class_exists("SMGallery")) {
    class SMGallery {
        var $photo_url_template      = "http://%s.smugmug.com/photos/%s%s-%s.jpg";
        var $photo_home_url_template = "http://%s.smugmug.com/gallery/%s#%s";

        var $config;

        //constructor
        function SMGallery( $config ) { $this->config = $config; }

        function filter( $content = '' ) {
            $pattern = "/<!--\s*SmugMug(Picture|Gallery).+?-->/i";
            return preg_replace_callback($pattern, array(&$this, 'replace_tag'), $content );
            return preg_replace_callback( "/<!-- *SmugMug(Picture|Gallery).+?-->/ie",
                                          array( &$this, "replace_tag( '$0' )" ),
                                          $content );

            return $content;
        }

        function replace_tag( $matches ) {
            error_log( "Match: $matches[0]" );

            $command      = $matches[1];
            $params = $this->get_params_from_string( $matches[0] );

            if( ! $command )
                return "ERROR: Unknown Command";

            if( preg_match( "/^picture$/i", $command ) )
                return $this->get_image_insert_html( $params );
            if( preg_match( "/^gallery$/i", $command ) )
                return $this->get_gallery_insert_html( $params );
            else
                return "Error: Unknown Command";
        }

        function get_image_insert_html( $params ) {
            $nickname = $this->config->get( "smugmug_user" );

            if( ! $params['image'] )
                return "ERROR: Image Required";

            if( ! $params['size'] )
                return "ERROR: Size Required";

            if( ! $nickname )
                return "ERROR: Smugmug Nickname Not Set";

            if( strlen($params['link']) &&
                ! preg_match("/(n|no|false|0)/i", $params['link']) )
                $params['link'] = true;
            else
                $params['link'] = false;

            if( $params['link'] && ! $params['gallery'] )
                return "ERROR: Gallery Required";

            $size = strtoupper( trim($params['size']) );
            if( ! preg_match( "/^(Th|S|M|L|XL|X2|X3|D)$/i", $params['size'] ) )
                return "ERROR: Invalid Size, vaild sizes are( Th, S, M, L, XL, X2, X3, and D )";

            if( $size == 'TH' ) $size = 'Th';

            $desc = preg_replace( "/'/", '', $params['description'] );

            $src = sprintf( $this->photo_url_template, $nickname, $params['image'], $params['key']  ? "_".$params['key'] : "", $size );
            error_log( "Img Source: $src" );

            if( $params['link'] ) {
                $image = preg_replace( "/_.*/", "", $params['image'] );
                $link_url = sprintf( $this->photo_home_url_template,
                                     $nickname, $params['gallery'], $image );
                
                $result = "<a href='$link_url'>";
            }

            $result .= "<img src='$src' ";
            if( strlen($desc) )             $result .= " title='$desc' alt='$desc' ";
            if( strlen($params['height']) ) $result .= " height='" . $params['height'] . "'";
            if( strlen($params['width']) )  $result .= " width='"  . $params['width'] . "'";
            if( strlen($params['style']) )  $result .= " class='"  . $params['style'] . "'";
            if( strlen($params['align']) )  $result .= " align='"  . $params['align'] . "'";
            if( strlen($params['hspace']) ) $result .= " hspace='" . $params['hspace'] . "'";
            if( strlen($params['vspace']) ) $result .= " vspace='"  . $params['vspace'] . "'";
            $result .= "/>";

            if( $params['link'] )
                $result .= "</a>";

            return $result;
        }

        function get_gallery_insert_html( $params ) {

        }

        function get_params_from_string( $options ) {
            $options = preg_replace( "/<!--\s+SmugMug(Picture|Gallery)\s+/i", "", $options);
            $options = preg_replace( "/\s*-->/", "", $options);
            $options = preg_replace( "/(\w+\s*=)/", "%%SMUGMUG_DELIM%%$1", $options);
            error_log( "OPTIONS: $options" );
            $pairs = preg_split( "/%%SMUGMUG_DELIM%%/", $options );

            for( $i = 0; $i < sizeof($pairs); $i++ ) {
                error_log( "PAIR: $pairs[$i]" );
                $split = preg_split( "/\s*=>\s*/", $pairs[$i] );

                $key = strtolower( $split[0] );
                $key = trim($key);
                $value = trim($split[1]);

                if( $key && $value ){
                    $opts[$key] = $value;
                }
            }

            return $opts;
        }
    }

} //End Class SmugMug

?>
