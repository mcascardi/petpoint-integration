<?php

namespace Petpoint\Core;

use Petpoint\Core\Plugin;
use Petpoint\Admin\Admin;
use Petpoint\Admin\Settings;

if (!defined('WPINC')) {
    die;
}

class Bootstrap {

    public static function init($version) {
        new Plugin($version);
        new Admin;
    }

    public static function activate() {
        Plugin::activate();
    }

    public static function deactivate() {
        delete_option(Settings::get_option_name());
        delete_option('cat_room_list');
        delete_option('dog_room_list');
    }

    public static function uninstall() {
        // error_log('PP: Uninstall plugin');
        delete_option(Settings::get_option_name());
    }
}
