<?php
include_once( '../../../../wp-config.php' );
include_once(ABSPATH . WPINC . '/classes.php');
include_once(ABSPATH . WPINC . '/functions.php');
require_once("../smugpress.php");

if (!class_exists("SMAjax")) {
    class SMAjax {
        var $smugmug;

        //constructor
        function SMAjax( $smugmug ) {
            $this->smugmug = $smugmug;
        }

        function execute() {
            global $HTTP_GET_VARS;

            switch( $HTTP_GET_VARS['cmd'] )  {
                case "gallery_select":
                    return $this->gallery_select( $HTTP_GET_VARS['gallery_id'] );

                case 'image_select':
                    return $this->image_select( $HTTP_GET_VARS['gallery_id'],
                                                $HTTP_GET_VARS['image_id'] );

                case 'data_parse':
                    return $this->data_parse( $HTTP_GET_VARS['data'] );

                default:
                    header( "HTTP/1.0 300 Invalid Operation", false, 300 );
                    echo "Failure";
            }
        }

        function data_parse( $data ) {
            error_log( $data );
            $gallery = new SMGallery( '' );
            $params = $gallery->get_params_from_string( $data );
            $first = true;

            $keys = array( 'gallery', 'image', 'size', 'align', 'style',
	                       'width', 'height', 'alt', 'link', 'hspace', 'vspace' );

            echo "{";
            for( $i = 0; $i < sizeof( $keys ); $i++ ) {
                if( $first )
                    $first = false;
                else
                    echo ", ";

                echo "\"$keys[$i]\" : \"" . $params["$keys[$i]"] . "\"";
            }

            echo " }";
        }

        function gallery_select( $gallery_id ) {
            $conduit = new SMConduit();
            $user = $this->smugmug->sm_config->get( "smugmug_user" );

            if( ! $user ) {
                echo "ERROR: Smugmug user not set";
                return false;
            }

            $galleries = $conduit->get_galleries_from_rss( $user );

            echo "<select onChange='picture.gallery_select_save(); picture.image_select_load( this.form )' name='gallery_select'>\n";
            echo '<option value="">-- Not Set --</option>' . "\n";
            for( $i = 0; $i < sizeof($galleries); $i++ ) {
                $selected = $galleries[$i]['gallery_id'] == $gallery_id ?
                    "selected='selected'" : "";
                $title = $galleries[$i]['title'];
                $id    = $galleries[$i]['gallery_id'];
                $key   = "_" . $galleries[$i]['gallery_key'];

                printf( '<option value="%s%s" %s >%s (%s)</option>' . "\n",
                        $id, $key, $selected, $title, $id );

            }
            echo "</select>\n";
        }

        function image_select( $gallery_id, $image_id ) {
            $conduit = new SMConduit();

            if( ! $gallery_id ) {
                echo "ERROR: Gallery ID not set";
                return false;
            }

            $images = $conduit->get_images_from_rss( array($gallery_id) );


            echo "<table width='100%' >\n ";
            for( $i = 0; $i < sizeof($images); $i++ ) {

                $id = $images[$i]['image'];
                $id = preg_replace( "/.*photos\/(\d+_.*)-.*/", '$1', $id );
                $checked = $id == $image_id ? "checked " : "        ";
                error_log( "ID: $id" );

                if( $i % 2 == 0 ) echo "<tr class='select'>\n";

                echo "<TD class='select' >\n";
                echo "<center>";
                echo "<DIV align='center' class='select' >\n ";
                printf( '<input type="radio" onChange="picture.image_select_save()" name="image_select" value="%s" %s>&nbsp;<img class="select" src="%s" />' . "\n",
                        $id, $checked, $images[$i]['image'] );
                echo "</DIV>\n";
                echo "</center>";
                echo "</TD>\n";

                if( $i % 2 == 1 ) echo "</tr>\n";
            }

            if( $i % 2 == 1 ) echo "<td class='select'>&nbsp;</td></tr>\n";
            echo "</table>";

        }
    }

} //End Class SMAjax

$ajax = new SMAjax( $smugmug );
$ajax->execute();
?>
