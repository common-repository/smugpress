<?php

if (!class_exists("SMWidgetRss")) {
    require_once( SMUGMUGPATH . "include/widget_drivers/base.php" );

    class SMWidgetRss extends SMWidgetBase {

        //constructor
        function SMWidgetRss( $config ) {
            $this->config = $config;
            $this->widget_type = "rss";
        }

        function widget_control() {
            parent::widget_control();
            $options = $this->config->getall();

            if ( $_POST['smugmug-submit'] ) {
                $this->config->set( "smugmug_widget_rss_feeds",
                                    $_POST['smugmug_widget_rss_feeds']
                );
            }

            $title = $this->config->get( "smugmug_widget_title" );
            $feeds = $this->config->get( "smugmug_widget_rss_feeds" );

?>
<div>
  <label for="smugmug-widget-title" style="line-height:35px;display:block;">Widget title: <input type="text" id="smugmug-widget-title" name="smugmug_widget_title" value="<?php echo $title; ?>" /></label>

  <label for="smugmug-widget-rss" style="line-height:35px;display:block;">
    RSS Url (comma delimeted):
    <textarea rows=4 cols=40 id="smugmug-widget-rss" name="smugmug_widget_rss_feeds"><?php echo $feeds; ?></textarea>
  </label>

  <input type="hidden" name="smugmug-submit" id="smugmug-submit" value="1" />
</div>
<?php
        }


        function fetch() {
            $feeds = $this->config->get("smugmug_widget_rss_feeds");
            $delim = "/[\s,]+/";
            $items = array();

            $urls = preg_split( $delim, $feeds );

            return $this->config->conduit->get_images_from_rss_url( $urls );
        }

    }
} //End Class SmugMug


?>
