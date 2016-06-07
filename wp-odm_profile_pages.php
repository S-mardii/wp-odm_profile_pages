<?php
/**
 * Plugin Name: ODM Profile Pages
 * Plugin URI: http://github.com/OpenDevelopmentMekong/odm_profile_pages
 * Description: Wordpress plugin for exposing custom content type for profile pages
 * Version: 1.0.0
 * Author: Alex Corbi (mail@lifeformapps.com)
 * Author URI: http://www.lifeformapps.com
 * License: GPLv3.
 */

// Require dependencies installed via composer
require_once plugin_dir_path(__FILE__).'vendor/autoload.php';

// Require utils
require_once plugin_dir_path(__FILE__).'utils/utils.php';

if (!class_exists('OpenDev_Profile_Pages_Plugin')) {
    class OpenDev_Profile_Pages_Plugin
    {
        private static $instance;

        private static $post_type;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            add_action('init', array($this, 'register_styles'));
            add_action('admin_notices', array($this, 'check_requirements'));

            require_once plugin_dir_path(__FILE__).'post-types/profile-pages.php';
            self::$post_type = new OpenDev_Profile_Pages_Post_Type();
        }

        public function register_styles()
        {
            wp_register_style('style',  plugin_dir_url(__FILE__).'css/profile-pages.css');
            wp_enqueue_style('style');
        }

        function check_requirements(){
          if (!check_requirements_profile_pages()):
            echo '<div class="error"><p>ODM Profile pages: WPCKAN plugin is missing, deactivated or missconfigured. Please check.</p></div>';
          endif;
        }

        public static function activate()
        {
            // Do nothing
        }

        public static function deactivate()
        {
            // Do nothing
        }
    }
}

if (class_exists('OpenDev_Profile_Pages_Plugin')) {
  register_activation_hook(__FILE__, array('OpenDev_Profile_Pages_Plugin', 'activate'));
  register_deactivation_hook(__FILE__, array('OpenDev_Profile_Pages_Plugin', 'deactivate'));
}

add_action('plugins_loaded', array('OpenDev_Profile_Pages_Plugin', 'get_instance'));
