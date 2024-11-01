<?php

if (!class_exists("SMWidgetRSSGallery")) {
    require_once( SMUGMUGPATH . "include/widget_drivers/base.php" );

    class SMWidgetRSSGallery extends SMWidgetBase {

        //constructor
        function SMWidgetRSSGallery( $config ) {
            $this->config = $config;
            $this->widget_type = "rss_gallery";
        }

        function widget_control() {
            parent::widget_control();
            $options = $this->config->getall();

            if ( $_POST['smugmug-submit'] ) {
                $this->config->set( "smugmug_widget_rss_gallery_feed",
                                    $_POST['smugmug_widget_rss_gallery_feed']
                );

                $this->config->set( "smugmug_widget_rss_gallery_count",
                                   (int) $_POST['smugmug_widget_rss_gallery_count']
                );
            }


            $title = $this->config->get( "smugmug_widget_title" );
            $feed  = $this->config->get( "smugmug_widget_rss_gallery_feed" );
            $count  = $this->config->get( "smugmug_widget_rss_gallery_count" );

?>
<div>
  <label for="smugmug-widget-title" style="line-height:35px;display:block;">Widget title: <input type="text" id="smugmug-widget-title" name="smugmug_widget_title" value="<?php echo $title; ?>" /></label>

  <label for="smugmug-widget-count" style="line-height:35px;display:block;">
  Limit to X most recent albums: <input type="text" id="smugmug-widget-count" name="smugmug_widget_rss_gallery_count" value="<?php echo $count; ?>" /></label>

  <input type="hidden" name="smugmug-submit" id="smugmug-submit" value="1" />
</div>
<?php
        }


        function fetch() {
            $user = $this->config->get("smugmug_user");
            $count = (int) $this->config->get("smugmug_widget_rss_gallery_count");

            $galleries = $this->config->conduit->get_gallery_ids_from_rss( $user, $count );
            $items = $this->config->conduit->get_images_from_rss( $galleries );

            return $items;
        }
    }
} //End Class SmugMug


?>
