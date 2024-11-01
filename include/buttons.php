<?php

if (!class_exists("SMButtons")) {
    class SMButtons {
        var $config;
        var $add_buttons;
        
        //constructor
        function SMButtons( $config ) {
            global $wp_db_version;
            
            $this->config = $config;
            
            $show_buttons = $config->get( "smugmug_show_buttons" );
            
            //  If we are wp version 2.1 or better, and we are using tinymce
            //  and the config option is set to add buttons.
            //
            //  Basically are all the stars aligned to see the buttons.
            if( $wp_db_version >= 3664 && get_user_option('rich_editing') == 'true' && $show_buttons )
                $this->add_buttons = true;
            else
                $this->add_buttons = false;
        }
        
        function enabled() {
            return $this->add_buttons ? true : false;
        }
        
        function init() {
            if( $this->enabled() ) {
                error_log( "Initializing tinymce buttons" );
                add_filter("mce_plugins", array( &$this, "smugmug_button_plugin" ), 0);  
                add_filter('mce_buttons', array( &$this, 'smugmug_button' ), 0);  
                add_action('tinymce_before_init', array( &$this, 'smugmug_button_script' ) );
            }
        }
        
        function smugmug_button( $buttons ) {
            error_log( "smugmug_button" );
            array_push($buttons, "separator", "smugmug_picture", "smugmug_gallery" );
            return $buttons;
        }
        
        function smugmug_button_plugin($plugins) {  
            error_log( "smugmug_button_plugin" );
            array_push($plugins, "-smugmug","bold");  
            return $plugins;  
        }
        
        function smugmug_button_script() {  
            $pluginURL = SMUGMUGURL . '/js/tinymce/';
            error_log( "smugmug_button_script: JSINC: " . $pluginURL);
            echo 'tinyMCE.loadPlugin("smugmug", "'.$pluginURL.'");'."\n";  
            return;  
        } 
    }
        
        
} //End Class SMButtons


?>
