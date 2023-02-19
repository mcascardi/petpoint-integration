<?php

namespace Petpoint\Core;

use Petpoint\Core\Intl;
use Petpoint\Core\Loader;
use Petpoint\Frontend\Frontend;
    
// use Petpoint\Admin\Admin;
use Petpoint\Core\WebApi;
use Petpoint\Admin\Settings;    

if (!defined('WPINC')) die();

class Plugin {

    public static $name = 'petpoint-integration';
    public static $version;
    
    /**
     * @since 2.0.0
     */
    function __construct ($version) {
        // error_log('Plugin Init');
        // $this->loader = new Loader;
        self::$version = $version;
        // $this->settings = new Settings;
        Frontend::init();
    }
    
    /**
     * Runs on plugin activation
     */
    public static function activate() {

        /**
         * Sets the default values for the options array
         */
        self::_setDefaultOptions();
    }

    private static function _setDefaultOptions() {
        $optionName = Settings::get_option_name();
        $defaults = Settings::getDefaults();

        add_option($optionName, $defaults);        
    }
    
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n;
		add_action( 'plugins_loaded', $plugin_i18n, 'init' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		// $plugin_admin = new Admin( );

        // Admin scripts and styles
		add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
		// Add menu item
		add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
        add_action('admin_init', $plugin_admin, 'options_update');
	}

    static function plugin_action_links( $links, $file ) {
        $settings = sprintf(
            '<a href="options-general.php?page=%s">%s</a>', Settings::$adminPage,
            __('Settings', "petpoint-integration"));

        array_unshift($links, $settings);
        
        return $links;
    }
}
