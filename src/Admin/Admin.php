<?php

namespace Petpoint\Admin;

use Petpoint\Core\Plugin;
use Petpoint\Core\WebApi;
use Petpoint\Admin\Settings;
use Petpoint\Frontend\Frontend;

class Admin
{

    public function __construct()
    {
        $settings = new Settings;
        $this->settings = $settings->settings;

        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'adminInit']);
    }

    public function addSettingsPage()
    {
        $title = 'Petpoint Integration';
        add_options_page(
            $title,
            $title,
            'manage_options',
            Settings::$adminPage,
            [$this, 'createAdminPage']
        );
    }

    public function adminInit()
    {
        register_setting(
            'petpoint_settings_group', // Option group
            
            //  Settings key!
            Settings::$optionName, // Option name
            [
                'sanitize_callback' => [$this, 'sanitize']
            ]
                
        );

        add_settings_section(
            'petpoint_settings_section', // ID
            'Petpoint Integration', // Title
            [$this, 'SectionInfoCallback'], // Callback
            'petpoint-settings-admin' // Page
        );
        
        add_settings_field(
            'authkey', // ID
            'Petpoint Auth Key', // Title
            [$this, 'authkeyCallback'], // Callback
            'petpoint-settings-admin', // Page
            'petpoint_settings_section', // Section
            ['class' => 'ppi-authkey']
        );        
        
        $this->enqueue_styles();
        $this->enqueue_scripts();
    }

    public function createAdminPage()
    {        
        $optionName = Settings::$optionName;
        include(__DIR__ . ('/html/admin-page.html'));
    }

    public function sanitize($input)
    {
        // error_log('Called Sanitize on :' . json_encode($input));

        $new_input = [];
    
        if (isset($input['authkey'])) {
            $new_input['authkey'] = sanitize_text_field($input['authkey']);
            // $new_input['authkey'] = 'Chicken';
        }
        return $new_input;
    }

    public function SectionInfoCallback()
    {

        $webapi = new WebApi;
        
        ?>
        <h2>Default Plug In settings information:</h2>
        <ul>
            <li>Plugin Version: <?php  echo  Plugin::$version; ?></li>
        <li>Plugin API Version: <?php echo WebApi::API_VERSION; ?> </li>
        <li>Frontend Version: <?php echo Frontend::VERSION; ?> </li>
        </ul>

<?php
     
        $testSearch = false;
        $testDetails = false;

        if (WP_DEBUG) {
        echo 'Saved Settings: <pre class="json-data">'
            . json_encode($this->settings) . '</pre>';
        


        if ($testSearch) {
            // $testAdoptableSearch = $webapi->_testPost(WebApi::$search)[1];

            $searchParams =[
                'speciesID' => 1
            ];
            $Adoptable = $webapi->getAdoptable($searchParams);
            echo '<h3>Adoptable Animals: ' . count($Adoptable) . ' animals found</h3>';
            echo '<pre class="xml-data">' . print_r($Adoptable, true ) . '</pre>';

        }
        
        if ($testDetails) {
            $AdoptableDetails = $webapi->getDetails(45972776);
            echo 'Test Details Results: <pre class="xml-data">';
            echo  print_r($AdoptableDetails, true);
            echo '</pre>';
        }
    }
}

    public function authkeyCallback()
    {
        $disabled = (
            (Settings::$authkeyLocation === Settings::SET_WP) ?
            'disabled="disabled"' : ''
        );

        printf(
            "<input name='%s[authkey]' size='60' {$disabled} value='%s'>"
            , Settings::$optionName, Settings::getAuthkey()
        );
    }
    
    //
    function _pageCallback()
    {
        // 
    }
    
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cpws_s_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cpws_s_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            Plugin::$name,
            plugin_dir_url( __FILE__ ) . 'css/'. Settings::$adminPage . '.css',
            [], Plugin::$version, 'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cpws_s_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cpws_s_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            Plugin::$name,
            plugin_dir_url( __FILE__ ) . 'js/'.  Settings::$adminPage . '.js',
            [ 'jquery' ], Plugin::$version, false
        );
    }

    public function no_longer_needed_validate($input) {
    }
}

// EOF
