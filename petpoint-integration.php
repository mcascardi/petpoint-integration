<?php
/**
 * Plugin Name: Petpoint Integration Plugin
 * Description: Add Adoptable Pet listings using the PetPoint Webservices API
 * Text Domain: petpoint-integration
 * Domain Path: /languages/
 * Version: 2.0.2
 * Author: Matt Cascardi <mattcascardi@gmail.com>
 * Author URI: https://www.cascardimedia.com
 */

if (!defined('WPINC')) {
    die();
}

require_once plugin_dir_path(__FILE__) . '/composer/autoload.php';
Petpoint\Core\Bootstrap::init('2.0.2');
Petpoint\Core\Intl::init();

register_activation_hook(__FILE__, ['Petpoint\\Core\\Bootstrap', 'activate']);
register_deactivation_hook(__FILE__, ['Petpoint\\Core\\Bootstrap', 'deactivate']);
register_uninstall_hook(__FILE__, ['Petpoint\\Core\\Bootstrap', 'uninstall']);

