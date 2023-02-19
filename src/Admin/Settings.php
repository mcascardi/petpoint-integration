<?php

namespace Petpoint\Admin;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Settings
{

    const SET_DB = 0;
    const SET_WP = 1;
    
    // General Settings
    public $settings = [];
    public static $authkeyLocation;
    public static $adminPage = 'petpoint-admin';
    static $optionName = 'petpoint_settings';
    
    function __construct() {
        self::$authkeyLocation = ((defined('PPI_AUTH_KEY')) ? self::SET_WP :  self::SET_DB);
        // $this->settings['authkey'] = $this->getAuthkey();                          
        $this->settings = $this->getAllSettings();
    }
    
    public static function getDefaults() {
        return [
            'authkey' => "Set default authkey"
            // 'detail_url' => 'place details url here',
            // 'sponsor_url' => 'place sponsor url here',
            // 'sponsor_url_id' => 'ID',
            // 'sponsor_url_name' => 'Name',
            // 'inquire_url' => 'place inquire url here',
            // 'inquire_url_id' => 'ID',
            // 'inquire_url_name' => 'Name',      
        ];
    }

    public static function getAuthkey() {
        return ((self::$authkeyLocation === self::SET_WP) ?
                PPI_AUTH_KEY :
                self::getSetting('authkey')
        );
    }

    public static function getSetting($setting)
    {
        $options = get_option(self::get_option_name());
        if ($options === false) {
            error_log(sprintf("Settings error: The option set %s was not found", self::get_option_name()));
        }
        
        if (!is_array($options)) {
            error_log(sprintf("Settings error: %s options was not an array", self::get_option_name()));
            return;
        }
        // if (!array_key_exists($setting, $options)) {
        //     error_log("Settings error: {$setting} was not an option found in ".json_encode($options));
        // }
        //if (isset($options[$setting]) && array_key_exists($setting, $options)) {
        return $options[$setting];
        //}
    }

    public function getAllSettings() {
        $options = get_option(self::get_option_name());
        if ($options === false)  {
            error_log(sprintf("Settings: option %s not found", self::get_option_name()));
            return;
        }
        
        return $options;
    }

    public static function get_option_name() {
        return self::$optionName;
    }

}
