<?php

if (!class_exists("SMAdminPanel")) {
    class SMAdminPanel {
        var $config      = null;

        //constructor
        function SMAdminPanel( $config ) {
            $this->config = $config;
        }

        function valid_options() {
            if (isset($_POST['smugmug_cache_expire']) &&
                ! preg_match( "/^\d+$/", $_POST['smugmug_cache_expire'] ) ) {
                $message = "Expire time must be a valid number.";

?>
<div class="error updated">
  <p>
    <strong><?php _e("Invalid Options", "SmugMug");?>:</strong>
    <?php echo $message ?>
  </p>
</div>
<?php
            }

            return strlen($message) ? false : true;
        }

    //Prints out the admin panel
    function print_admin_panel() {
        $devOptions = $this->config->get_options();

        if( isset($_POST['smugmug_update_cache']) ) {
            $widget = new SMWidget( $this->config );
            if( $widget->driver ) {
                $widget->driver->reset_cache();
                $widget->driver->update_cache();
            }
?>
<div class="updated">
  <p>
    <strong><?php _e("Cache Updated.", "SmugMug");?></strong>
  </p>
</div>
<?php
        }

        if (isset($_POST['smugmug_update']) && $this->valid_options() ) {
            $devOptions['smugmug_user'] = $_POST['smugmug_user'];
            $devOptions['smugmug_widget_criteria_type'] =
                                               $_POST['smugmug_widget_criteria_type'];

            $devOptions['smugmug_show_buttons'] = $_POST['smugmug_show_buttons'];
            $devOptions['smugmug_use_cache'] = $_POST['smugmug_use_cache'];
            $devOptions['smugmug_cache_expire'] = (int)$_POST['smugmug_cache_expire'];

            $widget = new SMWidget( $this->config );
            if( $widget->driver ) {
                $widget->driver->reset_cache();
                $widget->driver->update_cache();
            }

            update_option($this->config->optionsName, $devOptions);

?>
<div class="updated">
  <p>
    <strong><?php _e("Setting Updated.", "SmugMug");?></strong>
  </p>
</div>
<?php
        }

        $cache_expire;
        $cache_expire_stamp = $this->config->get( "smugmug_cache_expire_time" );
        if( $cache_expire_stamp ) {
            $cache_expire = date( "r", $cache_expire_stamp );
        }
?>
<div class=wrap>
    <h2>SmugPress SmugMug Plugin</h2>

   <p>
     <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
       Cache Expire: <?php echo $cache_expire ?>&nbsp;
       <input type="submit" name="smugmug_update_cache" value="<?php _e('Force Cache Update', 'SmugMug') ?>" />
     </form>
   </p>
   <hr />
   <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
   <p>
     Smugmug Nickname: <input type="text" name="smugmug_user" value="<?php echo $devOptions['smugmug_user'] ?>" />
   </p>

   <p>
     Add smugmug buttons: <input type="checkbox" name="smugmug_show_buttons" value="1" <?php echo $devOptions['smugmug_show_buttons'] ? 'CHECKED' : "" ?> /><br />
   </p>
   <p>
     Use feed cache: <input type="checkbox" name="smugmug_use_cache" value="1" <?php echo $devOptions['smugmug_use_cache'] ? 'CHECKED' : "" ?> /><br />
     Cache expiration (in seconds): <input size="8" type="text" name="smugmug_cache_expire" value="<?php echo $devOptions['smugmug_cache_expire'] ?>" />
   </p>

   <hr />
   <p>
      Widget Gallery Type:
      <SELECT NAME="smugmug_widget_criteria_type">
        <OPTION VALUE="rss_galleries" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'rss_galleries' ? 'SELECTED' : "" ?> >RSS Recent Galleries
        <OPTION VALUE="rss" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'rss' ? 'SELECTED' : "" ?> >RSS Feeds
        <!--
        <OPTION VALUE="all" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'all' ? 'SELECTED' : "" ?> >All Galleries
        <OPTION VALUE="select" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'select' ? 'SELECTED' : "" ?> >Select Galleries
        <OPTION VALUE="recent" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'recent' ? 'SELECTED' : "" ?> >Recent Galleries
        <OPTION VALUE="after" <?php echo $devOptions['smugmug_widget_criteria_type'] == 'after' ? 'SELECTED' : "" ?> >Galleries Older Than ...
        -->
      </SELECT>
   </p>

    <input type="submit" name="smugmug_update" value="<?php _e('Update Settings', 'SmugMug') ?>" />
  </form>
</div>

<?php
        }


    }
} //End Class SmugMug

?>
