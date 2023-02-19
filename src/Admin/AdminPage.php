<?php

namespace Petpoint\Admin;
use Petpoint\Admin\Settings;

if (!defined('WPINC')) die();

class AdminPage() {

    function _construct() {
        // error_log('AdminPage init');
        $this->settings = new Settings;
    }

    public function display() {
        // error_log('AdminPage Display function called :)');
        // include(locate_template('html/admin-page.html'));
        // Set class property

        $this->options = $this->settings->getAllSettings();
        // $this->options = get_option('tld_mkto_settings'); ?>
        <div class="wrap petpoint-settings">
        <form method="post" action="options.php">
<?php
        settings_fields('petpoint_settings_group');
        do_settings_sections('petpoint-settings-admin');
        submit_button(); ?>
        </form>
        </div>
<?php

    }

    public function admin_save_options() {
        if (empty($_POST["form_name"])) {
            return;
        }
        $form_name = $_POST['form_name'];
    
        if ($form_name == 'cpws_gp01') {
            // Refactor to use field names from core defaults
            $this->cpws_gp01['AuthKey']          = $_POST["AuthKey"];
            $this->cpws_gp01['detail_url']       = $_POST["detail_url"];  
            $this->cpws_gp01['sponsor_url']      = $_POST["sponsor_url"];   
            $this->cpws_gp01['sponsor_url_id']   = $_POST["sponsor_url_id"];
            $this->cpws_gp01['sponsor_url_name'] = $_POST["sponsor_url_name"];
            $this->cpws_gp01['inquire_url']      = $_POST["inquire_url"];  
            $this->cpws_gp01['inquire_url_id']   = $_POST["inquire_url_id"];
            $this->cpws_gp01['inquire_url_name'] = $_POST["inquire_url_name"];
        
            update_option( 'cpws_gp01', $this->cpws_gp01);

        } else if ( $form_name == 'cpws_as01' ) {
            // Refactor to use field names from core defaults (Adoptable Search)
        
            $this->cpws_as01['Row_Labels']           = $_POST["Row_Labels"];
            $this->cpws_as01['Icon_Row']             = $_POST["Icon_Row"];
            $this->cpws_as01['Sponsor_Button']       = $_POST["Sponsor_Button"];
            $this->cpws_as01['Inquire_Button']       = $_POST["Inquire_Button"];
            $this->cpws_as01['ID']                   = $_POST["ID"];
            $this->cpws_as01['Header_Name']          = $_POST["Header_Name"];                   
            $this->cpws_as01['Name']                 = $_POST["Name"];
            $this->cpws_as01['Species']              = $_POST["Species"];
            $this->cpws_as01['Sex']                  = $_POST["Sex"];
            $this->cpws_as01['PrimaryBreed']         = $_POST["PrimaryBreed"];
            $this->cpws_as01['SecondaryBreed']       = $_POST["SecondaryBreed"];
            $this->cpws_as01['SN']                   = $_POST["SN"];
            $this->cpws_as01['Age']                  = $_POST["Age"];
            $this->cpws_as01['Photo']                = $_POST["Photo"];
            $this->cpws_as01['Location']             = $_POST["Location"];
            $this->cpws_as01['OnHold']               = $_POST["OnHold"];
            $this->cpws_as01['SpecialNeeds']         = $_POST["SpecialNeeds"];
            $this->cpws_as01['NoDogs']               = $_POST["NoDogs"];
            $this->cpws_as01['NoCats']               = $_POST["NoCats"];
            $this->cpws_as01['NoKids']               = $_POST["NoKids"];
            $this->cpws_as01['BehaviorResult']       = $_POST["BehaviorResult"];
            $this->cpws_as01['MemoList']             = $_POST["MemoList"];
            $this->cpws_as01['ARN']                  = $_POST["ARN"];
            $this->cpws_as01['BehaviorTestList']     = $_POST["BehaviorTestList"];
            $this->cpws_as01['Stage']                = $_POST["Stage"];
            $this->cpws_as01['AnimalType']           = $_POST["AnimalType"];
            $this->cpws_as01['AgeGroup']             = $_POST["AgeGroup"];
            $this->cpws_as01['WildlifeIntakeInjury'] = $_POST["WildlifeIntakeInjury"];
            $this->cpws_as01['WildlifeIntakeCause']  = $_POST["WildlifeIntakeCause"];
            $this->cpws_as01['BuddyID']              = $_POST["BuddyID"];
            $this->cpws_as01['Featured']             = $_POST["Featured"];
            $this->cpws_as01['Sublocation']          = $_POST["Sublocation"];
            $this->cpws_as01['ChipNumber']           = $_POST["ChipNumber"];                         
            update_option( 'cpws_as01', $this->cpws_as01);            
        } else if ($form_name == 'cpws_ad01') {
            // Refactor to use field names from core defaults (Adoptable Details)
            $this->cpws_ad01['Row_Labels']           = $_POST["Row_Labels"];
            $this->cpws_ad01['Header_Name']          = $_POST["Header_Name"];
            $this->cpws_ad01['Icon_Row']             = $_POST["Icon_Row"];
            $this->cpws_ad01['Sponsor_Button']       = $_POST["Sponsor_Button"];
            $this->cpws_ad01['Inquire_Button']       = $_POST["Inquire_Button"];
            $this->cpws_ad01['CompanyID']            = $_POST["CompanyID"];
            $this->cpws_ad01['ID']                   = $_POST["ID"];
            $this->cpws_ad01['AnimalName']           = $_POST["AnimalName"];
            $this->cpws_ad01['Species']              = $_POST["Species"];
            $this->cpws_ad01['Sex']                  = $_POST["Sex"];
            $this->cpws_ad01['Altered']              = $_POST["Altered"];
            $this->cpws_ad01['PrimaryBreed']         = $_POST["PrimaryBreed"];
            $this->cpws_ad01['SecondaryBreed']       = $_POST["SecondaryBreed"];
            $this->cpws_ad01['PrimaryColor']         = $_POST["PrimaryColor"];
            $this->cpws_ad01['SecondaryColor']       = $_POST["SecondaryColor"];
            $this->cpws_ad01['Age']                  = $_POST["Age"];
            $this->cpws_ad01['Size']                 = $_POST["Size"];
            $this->cpws_ad01['Housetrained']         = $_POST["Housetrained"];
            $this->cpws_ad01['Declawed']             = $_POST["Declawed"];
            $this->cpws_ad01['Price']                = $_POST["Price"];
            $this->cpws_ad01['LastIntakeDate']       = $_POST["LastIntakeDate"];
            $this->cpws_ad01['Location']             = $_POST["Location"];
            $this->cpws_ad01['Dsc']                  = $_POST["Dsc"];
            $this->cpws_ad01['Photo1']               = $_POST["Photo1"];
            $this->cpws_ad01['Photo2']               = $_POST["Photo2"];
            $this->cpws_ad01['Photo3']               = $_POST["Photo3"];
            $this->cpws_ad01['OnHold']               = $_POST["OnHold"];
            $this->cpws_ad01['SpecialNeeds']         = $_POST["SpecialNeeds"];
            $this->cpws_ad01['NoDogs']               = $_POST["NoDogs"];
            $this->cpws_ad01['NoCats']               = $_POST["NoCats"];
            $this->cpws_ad01['NoKids']               = $_POST["NoKids"];
            $this->cpws_ad01['BehaviorResult']       = $_POST["BehaviorResult"];
            $this->cpws_ad01['Site']                 = $_POST["Site"];
            $this->cpws_ad01['TimeInFormerHome']     = $_POST["TimeInFormerHome"];
            $this->cpws_ad01['ReasonForSurrender']   = $_POST["ReasonForSurrender"];
            $this->cpws_ad01['ReasonForSurrender']   = $_POST["ReasonForSurrender"];
            $this->cpws_ad01['PrevEnvironment']      = $_POST["PrevEnvironment"];
            $this->cpws_ad01['LivedWithChildren']    = $_POST["LivedWithChildren"];
            $this->cpws_ad01['LivedWithAnimals']     = $_POST["LivedWithAnimals"];
            $this->cpws_ad01['LivedWithAnimalTypes'] = $_POST["LivedWithAnimalTypes"];
            $this->cpws_ad01['BodyWeight']           = $_POST["BodyWeight"];
            $this->cpws_ad01['DateOfBirth']          = $_POST["BodyWeight"];
            $this->cpws_ad01['ARN']                  = $_POST["ARN"];
            $this->cpws_ad01['VideoID']              = $_POST["VideoID"];
            $this->cpws_ad01['BehaviorTestList']     = $_POST["BehaviorTestList"];
            $this->cpws_ad01['Stage']                = $_POST["Stage"];
            $this->cpws_ad01['AnimalType']           = $_POST["AnimalType"];
            $this->cpws_ad01['AgeGroup']             = $_POST["AgeGroup"];
            $this->cpws_ad01['WildlifeIntakeInjury'] = $_POST["WildlifeIntakeInjury"];
            $this->cpws_ad01['WildlifeIntakeCause']  = $_POST["WildlifeIntakeCause"];
            $this->cpws_ad01['BuddyID']              = $_POST["BuddyID"];
            $this->cpws_ad01['Featured']             = $_POST["Featured"];
            $this->cpws_ad01['Sublocation']          = $_POST["Sublocation"];
            $this->cpws_ad01['ChipNumber']           = $_POST["ChipNumber"];
            $this->cpws_ad01['ColorPattern']         = $_POST["ColorPattern"];
            $this->cpws_ad01['BannerURL']            = $_POST["BannerURL"];
            update_option( 'cpws_ad01', $this->cpws_ad01);            
        }
    }
}

// EOF
