<?php

namespace Petpoint\Frontend;

use Petpoint\Core\WebApi;
use Petpoint\Core\Plugin;
use Petpoint\Admin\Settings;

use Petpoint\Frontend\Blocks\AdoptableSearch;

if (!defined('WPINC')) die();

class Frontend {

    const VERSION = '1.0.2';
    /**
     * @since 2.0.0
     */
    public static function init() {
        add_action('init', [self::class, 'register_shortcode']);
        // add_action('init', [self::class, '_registerBlocks']);
		add_action( 'wp_enqueue_scripts', [self::class, 'enqueue_scripts'] );
		add_action( 'wp_enqueue_scripts', [self::class, 'enqueue_styles'] );
    }

    static function register_shortcode() {
        add_shortcode('adoptable-search',  [self::class, 'AdoptableSearch']);
    }

    static function _registerBlocks() {
        // new AdoptableSearch;
        // self::gutenberg_examples_01_register_block();
        // self::innerBlockExample();
    }
    
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	static function enqueue_styles() {
        $style_src = plugin_dir_url( __FILE__ ) . 'css/' . Plugin::$name . '.css';
        wp_enqueue_style(Plugin::$name, $style_src, [], self::VERSION);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	static function enqueue_scripts() {
        $script_src =  plugin_dir_url( __FILE__ ) . 'js/' . Plugin::$name . '.js';
		wp_enqueue_script(Plugin::$name, $script_src, ['jquery' ], self::VERSION, false);
	}
  
    static function AdoptableSearch($attrs = []) {
        $webapi = new WebApi;

        $defaults = [
            'speciesID' => 0,
            'sex' => 'A',
            'ageGroup' => 'All',
            'location' => '',
            'site' => 0,
            'onHold' => 'A',
            'orderBy' => '',
            'primaryBreed' => 'All',
            'secondaryBreed' => 'All',
            'specialNeeds' => 'A',
            'noDogs' => 'A',
            'noCats' => 'A',
            'noKids' => 'A',
            'stageID' => 'A'
        ];

        // $attrs = self::translateOptions($attrs);
        $params = shortcode_atts($defaults, $attrs);
        // End format params
        
        // Calls the WebApi for AdoptableSearch
        $AdoptableSearch = $webapi->AdoptableSearch($params);

        // Render the html/template
        if (!is_admin() && ! wp_doing_ajax()) {
            include 'html/adoptable-search.html';
        }
    }


    static function translateOptions($attrs) {
        // This lets users enter the text version of param into the shortcode
        $valid = [
            'speciesID' => [
                'all' => 0,
                'dog' => 1,
                'cat' => 2,
                'rabbit' => 3,
                'horse' => 4,
                'small' => 5,
                'pig' => 6,
                'reptile' => 7,
                'bird' => 8,
                'barnyard' => 9,
                'other' => 1003
            ],
            'sex' => [
                'all' => 'A',
                'male' => 'm',
                'female' => 'f'
            ],
            'ageGroup' => [
                'all' => 'All',
                'over1' => 'OverYear',
                'under1' => 'UnderYear'
            ],
            'location' => [],
            'site' => [],
            'onHold' => [
                'yes' => 'Y',
                'no' => 'N',
                'either' => 'A'
            ],
            'orderBy' => [
                '' => '',
                'id' => 'ID',
                'name' => 'Name',
                'breed' => 'Breed',
                'sex' => 'Sex'
            ],
            'primaryBreed' => [],
            'secondaryBreed' => [],
            'specialNeeds' => [
                'yes' => 'Y',
                'no' => 'N',
                'either' => 'A'
            ],
            'noDogs' => [                
                'yes' => 'Y',
                'no' => 'N',
                'either' => 'A'
            ],
            'noCats' => [                
                'yes' => 'Y',
                'no' => 'N',
                'either' => 'A'
            ],
            'noKids' => [                
                'yes' => 'Y',
                'no' => 'N',
                'either' => 'A'
            ],
            'stageID' => []
        ];

        
        foreach ($attrs as $param => $value ) {
            if (isset($valid[$param])) {
                foreach ($valid[$param] as $v => $code) {
                    if (strtolower($value) === $v) {
                        $attrs[$param] = $code;
                    }
                }
            }
        }
        
        return $attrs;
    }
    
  
    public function get_pets_xml($atts_in){
        
        // Replace with something like \Petpoint\Core\WebApi::AdoptableSearch();
        
        $settings = new Settings;

        // assign parts of array to inviduals vars
        // assign the corresponding "$atts[]" value to "array[]" then create "$atts_new[]"
        // this array + $url will be used to generate the complete url for the post request
        // below listed are options in get request
  		$atts_new = shortcode_atts([
            // Replace with reference to \Petpoint\Core\WebApi::$auth_key
            'authkey' => $settings['authkey'], 
            'species' => '', 
            'speciesid' => '', 
            'sex' => '', 
            'agegroup' => '',
            'location' => '',
            'site' => '',
            'onhold' => '',      
            'orderby' => '',      
            'primarybreed' => '',      
            'secondarybreed' => '',      
            'specialneeds' => '',            
            'nodogs' => '',
            'nocats' => '',
            'nokids' => '',
            'stageid' => ''
        ], $atts_in);
        // convert shortcode speciesid for words to numbers

        switch ($atts_new['speciesid']) {
        case 'all':
            $atts_new['speciesid']=0;
            break;
        case 'dog':
            $atts_new['speciesid']=1;
            break;
        case 'cat':
            $atts_new['speciesid']=2;
            break;
        } 

        $build_url = array(
            'http' => array(
                'header'           => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'           => 'POST',
                'content'          => http_build_query($atts_new)),
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false),                
        );




        $post_url = esc_url($cpws_gp01['AdoptableSearch']       .'?'.$build_url['http']['content'],'http' );    // completed get request url browser friendly to be passed out of function in case of need testing  
        $context  = stream_context_create($build_url);                                                          // 

        // Replace with wp_remote_post();
        $url_result = file_get_contents($cpws_gp01['AdoptableSearch'], true, $context);                         // returns data 
        $dom = new DOMDocument;                                                                                 //
        $dom->loadXML($url_result);                                                                             //
        return $dom;                                                                                            //
    }                                                                                                       // end of get_pets_xml      

    //==============================================================================================================================================================
    public function AdoptableDetails_get_xml($pet_id) {

        // Replace with something like \Petpoint\Core\WebApi::AdoptableDetails();
        
        $cpws_gp01 = get_option('cpws_gp01');    
        // assign parts of array to inviduals vars
        // assign the corresponding "$atts[]" value to "array[]" then create "$atts_new[]"
        // this array + $url will be used to generate the complete url for the post request
        $query_data = 
                    array(
                        //'authkey' => $options['pp_authkey'], 
                        'authkey' => $cpws_gp01['AuthKey'],
                        'animalID' => $pet_id			             
                    );  
        $build_url = array(
            'http' => array(
                'header'           => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'           => 'POST',
                'content'          => http_build_query($query_data)
            ),
            'ssl'  => array(
                'verify_peer'      => false,
                'verify_peer_name' => false
            ),                
        );
        $post_url = esc_url($cpws_gp01['AdoptableDetails'].'?'.$build_url['http']['content'],'http' );    // completed get request url browser friendly to be passed out of function in case of need testing  
        $context  = stream_context_create($build_url);                                                    // 

        // Replace with wp_remote_post();
        $url_result = file_get_contents($cpws_gp01['AdoptableDetails'], true, $context);                  // returns data  
        if ($url_result === false) {
            print_r('animal not found'); //There is an error opening the file
            die;
        } 
  
        $dom = new DOMDocument;                                                                           //
        $dom->loadXML($url_result);                                                                       //
        $dom->preserveWhiteSpace = false;                                                                 //
        $dom->formatOutput = true;                                                                        //
        return $dom;                                                                                      //
    }                                                                                                 // end of get_pets_xml      
    
    //=========================================================================================================================================================================
    function pets_xml_data_change_photo_url($data_in_1) {
        return $this->pets_xml_data_change_default_url($data_in_1);
    }
  
    //======================================================================================================================================================================
    function pets_xml_data_change_default_url($data_in_1) {
        $selectors = ['//Photo'];
        return $this->fix_image_urls($data_in_1);
    }
  
    //========================================================================================================================================================================
    function AdoptableDetails_change_photo_urls($data_in_1){                                          // functions purpose is to rewrite all http urls to https
        $selectors = ['//Photo1', '//Photo2', '//Photo3'];
        return $this->fix_image_urls($data_in_1);
    }
  
    //====================================================================================================================================================================
    function takeout_pp_node($data_in_1, $goaway_node){                                                // removes a node from list usage 
        $xpath = new DOMXPath($data_in_1);                                                        // declare new Xpath for the dom object
        $arrgy = $xpath->query('//'.$goaway_node);                                                // create query '$goaway_node" for data_in, returns all instance of the node that are to go away
        foreach($arrgy as $item) {                                                                // cycle through all returned nodes
            //$item->nodeValue = '';                                                           // assign the node a value 
            $item->parentNode->removeChild($item);                                             // tells the parent node to remove this node
        }                                                                                         // end for each 
        return $data_in_1;                                                                                // return updates to where it came from
    }                                                                                                 // end of function takeout_pp_node  

    //================================================================================================================================================================
    function apply_stage_filter_lr($data_in_1,$stage_filter){                                         // removes a node from list usage : does a charcter match from left to right
        $xpath = new DOMXPath($data_in_1);                                                        // declare new Xpath for the dom object
        $arrgy = $xpath->query('//Stage');                                                        // create query '$goaway_node" for data_in, returns all instance of the node that are to go away        
        foreach($arrgy as $item) {                                                                // cycle through all returned nodes        
            $tempc =substr((string)$item->nodeValue,0, strlen($stage_filter));                   // cut down $item node value (stage) to same length as $stage_filter
            if(!($stage_filter==$tempc))  {                                                      // if stage filter is not what we want then remove it
                $temp1 = $item->parentNode->parentNode->parentNode;                                // assign granparent node to var 
                $temp2 = $item->parentNode->parentNode;                                            // assign parrent node to var
                $temp1->removeChild($temp2);                                                       // remove un-needed node (because grandchild node telling the grandparent to remove the parent didn't work)
            }                                                                                  // end of if
        }                                                                                         // end for each   
        return $data_in_1;                                                                                // return updates to where it came from
    }   
  
    //========================================================================================================================================================================
    function apply_stage_filter_rl($data_in_1,$stage_filter){                                         // removes a node from list usage: does a charcter match from right to left 
        $xpath = new DOMXPath($data_in_1);                                                        // declare new Xpath for the dom object
        $arrgy = $xpath->query('//Stage');                                                        // create query '$goaway_node" for data_in, returns all instance of the node that are to go away               
        foreach($arrgy as $item) {                                                                // cycle through all returned nodes      
            $tempc = substr((string)$item->nodeValue,strlen($item->nodeValue)-strlen($stage_filter),strlen($item->nodeValue));     //cut down $item node value (stage) to same length as $stage_filter     
            if(!($stage_filter==$tempc))  {                                                      // if stage filter is not what we want then remove it
                $temp1 = $item->parentNode->parentNode->parentNode;                                // assign granparent node to var 
                $temp2 = $item->parentNode->parentNode;                                            // assign parrent node to var
                $temp1->removeChild($temp2);                                                       // remove un-needed node (because grandchild node telling the grandparent to remove the parent didn't work)
            }
        } 
        return $data_in_1;                                                                                // return updates to where it came from
    }   
  
    //=======================================================================================================================================================================
    function remove_unwanted_categories($data_in_1){   
        $data_in_1 = $this->takeout_pp_node($data_in_1,'MemoList');                // this catagory will always be removed as it is a special purpose item   
        $check_display_options = get_option('cpws_as01');                   // load variable set in the admin screen    
        //$check_display_options = get_option($this->plugin_name);                   // load variable set in the admin screen  
        ($check_display_options['ID']                   ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'ID'):'');     
        ($check_display_options['ARN']                  ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'ARN'):'');  
        ($check_display_options['Name']                 ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Name'):'');                  
        ($check_display_options['Species']              ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Species'):'');              
        ($check_display_options['AnimalType']           ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'AnimalType'):'');  
        ($check_display_options['Sex']                  ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Sex'):'');            
        ($check_display_options['PrimaryBreed']         ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'PrimaryBreed'):'');            
        ($check_display_options['SecondaryBreed']       ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'SecondaryBreed'):'');            
        ($check_display_options['SN']                   ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'SN'):'');        
        ($check_display_options['Age']                  ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Age'):'');    
        ($check_display_options['AgeGroup']             ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'AgeGroup'):'');                
        ($check_display_options['Photo']                ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Photo'):'');    
        ($check_display_options['Location']             ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Location'):'');
        ($check_display_options['Sublocation']          ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Sublocation'):'');   
        ($check_display_options['OnHold']               ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'OnHold'):'');  
        ($check_display_options['NoDogs']               ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'NoDogs'):'');
        ($check_display_options['NoCats']               ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'NoCats'):'');
        ($check_display_options['NoKids']               ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'NoKids'):'');
        ($check_display_options['SpecialNeeds']         ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'SpecialNeeds'):'');  
        ($check_display_options['ChipNumber']           ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'ChipNumber'):'');      
        ($check_display_options['Stage']                ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Stage'):'');    
        ($check_display_options['BehaviorTestList']     ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'BehaviorTestList'):'');  
        ($check_display_options['BehaviorResult']       ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'BehaviorResult'):'');  
        ($check_display_options['Featured']             ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'Featured'):'');              
        ($check_display_options['BuddyID']              ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'BuddyID'):'');              
        ($check_display_options['WildlifeIntakeCause']  ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'WildlifeIntakeCause'):'');  
        ($check_display_options['WildlifeIntakeInjury'] ==0 ? $data_in_1 = $this->takeout_pp_node($data_in_1,'WildlifeIntakeInjury'):''); 
        return $data_in_1;
    } 

    //=======================================================================================================================================================================
    // function adjust_pp_results($stuff){         
    //     // filler
    //     return $stuff;
    // }

    //=======================================================================================================================================================================
    function mod_category_BehaviorResult($data_in_1){                                        // functions purpose is to rewrite all category of BehaviorResult
        $xpath = new DOMXPath($data_in_1); 
        $arrgy = $xpath->query('//BehaviorResult');                                      // defines search of $xpath to nodeName 'BehaviorResult"
        foreach($arrgy as $item) { 
            $item->nodeValue = ''.$item->nodeValue;                           // modifies each value
        } 
        return $data_in_1;                                                                       // return $data_in_1 as "foreach" was working with paths
    } 

    //=======================================================================================================================================================================
    function mod_category_Location($data_in_1){                                              // functions purpose is to rewrite all category of Location
        $xpath = new DOMXPath($data_in_1);
        $arrgy = $xpath->query('//Location');                                            // defines search of $xpath to nodeName 'Location"
        foreach($arrgy as $item) { 
            $item->nodeValue = '@'.$item->nodeValue;                                  // modifies each value
        } 
        return $data_in_1;                                                                       // return $data_in_1 as "foreach" was working with paths
    }
  
    //========================================================================================================================================================================
    function adjust_age_format($data_in_1){         
        $xpath = new DOMXPath($data_in_1);
        $arrgy = $xpath->query('//Age'); 
        foreach($arrgy as $item) {                                                       // iteration through symbolic path results
            switch ($item->nodeValue) {  
            case '0':                                                               // to avoid div by 0 errors
                $item->nodeValue = '0 yr & 1 m';                                    // asign default 1 month is 0 age is reported
                break;                                                              //
            default:                                                                // 
                $year = (int)($item->nodeValue/12);                                 // find age in years
                $month = ($item->nodeValue-($year*12));                             // find remander age in months
                $item->nodeValue = $year.' yr &amp; '.$month.' m';
            }                                                                              // end of switch
        } 
        return $data_in_1;                                                                       // return new data 
    }     
  
    //======================================================================================================================================================================
    function adds_detail_link($data_in_1){         
        // https://whs1pets.org/details/?ID=33804961
        //$option = get_option($this->plugin_name);                                      // load variable set in the admin screen  
        $option = get_option('cpws_gp01');                                        
        $display_url = $option['detail_url'];                                           // pull the url as set in the admin screen  
        //$display_url = $option ['cpws_wpsite_details_url'];                            // pull the url as set in the admin screen    
        $xpath = new DOMXPath($data_in_1);                                               // declare new Xpath for the dom object
        $arrgy = $xpath->query('//ID');                                                  // specific to the nodeName 'ID" here is defined the query/path
        foreach($arrgy as $item) {                                                       // cycle through all returned nodes              
            $make_url = $display_url.'?id='.$item->nodeValue;                           // create the complete url for the details link 
            $child = new DOMElement('DetailsUrl',$make_url);                            // create the new child element and assigns vale of new url
            $item->parentNode->appendChild($child);                                     // go up one to the parent and adds the new child node with data
        }
        return $data_in_1;                                                                       // return updates to where it came from
    }   

    //======================================================================================================================================================================
    function adds_sponsor_link($data_in_1){         
        // https://whs1pets.org/details/?ID=33804961
        //$option = get_option($this->plugin_name);                                      // load variable set in the admin screen  
        $option = get_option('cpws_gp01');                                        
        $display_url = $option['sponsor_url'];                                           // pull the url as set in the admin screen  
        //$display_url = $option ['cpws_wpsite_details_url'];                            // pull the url as set in the admin screen    
        $xpath = new DOMXPath($data_in_1);                                               // declare new Xpath for the dom object
        $arrgy = $xpath->query('//ID');                                                  // specific to the nodeName 'ID" here is defined the query/path
        foreach($arrgy as $item) {                                                       // cycle through all returned nodes              
            $make_url = $display_url.'?id='.$item->nodeValue;                           // create the complete url for the details link 
            $child = new DOMElement('SponsorUrl',$make_url);                            // create the new child element and assigns vale of new url
            $item->parentNode->appendChild($child);                                     // go up one to the parent and adds the new child node with data
        }
        return $data_in_1;                                                                       // return updates to where it came from
    }   
  
    function adds_inquire_link($data_in_1){         
        // https://whs1pets.org/details/?ID=33804961
        //$option = get_option($this->plugin_name);                                      // load variable set in the admin screen  
        $option = get_option('cpws_gp01');                                        
        $display_url = $option['inquire_url'];                                           // pull the url as set in the admin screen  
        //$display_url = $option ['cpws_wpsite_details_url'];                            // pull the url as set in the admin screen    
        $xpath = new DOMXPath($data_in_1);                                               // declare new Xpath for the dom object
        $arrgy = $xpath->query('//ID');                                                  // specific to the nodeName 'ID" here is defined the query/path
        foreach($arrgy as $item) {                                                       // cycle through all returned nodes              
            $make_url = $display_url.'?id='.$item->nodeValue;                           // create the complete url for the details link 
            $child = new DOMElement('InquireUrl',$make_url);                            // create the new child element and assigns vale of new url
            $item->parentNode->appendChild($child);                                     // go up one to the parent and adds the new child node with data
        }
        return $data_in_1;                                                                       // return updates to where it came from
    } 

    //======================================================================================================================================================================
    function adjust_intake_date_ad($data_in_1){
        $xpath = new DOMXPath($data_in_1);
        $arrgy = $xpath->query('//LastIntakeDate');
        foreach($arrgy as $item) {                                                                // iteration through symbolic path results
            $item->nodeValue = substr((string)$item->nodeValue,0, 10);
        }
        return $data_in_1;                                                                                // return $data_in_1 as "foreach" was working with paths
    }   
  
    //======================================================================================================================================================================
    function processes_data_AdoptableSearch($pets_data,$atts){         
        //include_once('partials/cpws_s-public-display_1.php' );                                                                         // php file that assembles the html for display of searc results
        $atts = array_change_key_case((array)$atts, CASE_LOWER);                                                                         // make lowercase  
        $pets_data = $this->get_pets_xml($atts);                                                                                         // returns from function as DOM xml, makes url and gets data from petpoint api
        $pets_data = $this->pets_xml_data_change_photo_url($pets_data);                                                                  // because all petpoint photo urls are http and not https
        // $pets_data = $this->pets_xml_data_change_default_url($pets_data);                                                                // check for default picture and reassign new picture = don't care at thing point
        // $pets_data = $this->remove_unwanted_categories($pets_data);                                                                      // postpone removing from xml list: remove from display page for now
        $pets_data = $this->adjust_age_format($pets_data);      																																				 // change from months to year & months
        $pets_data = $this->mod_category_BehaviorResult($pets_data);                                                                     // adds "Rated :" to each text
        // $pets_data = $this->mod_category_Location($pets_data);                                                                           // adds "@ :" to each text
        $pets_data = $this->adds_detail_link($pets_data);      																																				   // creates entry for local detail url
        // $pets_data = $this->adds_sponsor_link($pets_data);    																																				   // not used at this time
        // $pets_data = $this->adds_inquire_link($pets_data);    																																				   // not used at this time
        $pets_data = $this->adjust_pp_results($pets_data);                                                                               // currently does nothing
        (isset($atts['stage_filter_lr']) ? $pets_data = $this->apply_stage_filter_lr($pets_data,$atts['stage_filter_lr']):'');           // left to right character match to filter displaying filters
        (isset($atts['stage_filter_rl']) ? $pets_data = $this->apply_stage_filter_rl($pets_data,$atts['stage_filter_rl']):'');           // right to left character match to filter displaying filters 
        // resize main photo for good alignment on webpage
        // call php file to make web display from data  
        // $qq .='-'.print("<pre>".print_r($pets_xml_data,true)."</pre>").'<br/>'; // works just gets in the way for testing
        // $qq .='-'.print("<pre>".print_r($pets_xml_data,true)."</pre>").'<br/>';       // works just gets in the way for testing        

        return $pets_data;
    }  
  
    //======================================================================================================================================================================
    function processes_AdoptableDetails_data($pets_details,$pets_ID){         
        // [cpws details_page="Y" ] cpws_url_AdoptableDetails
        $pets_details = $this->AdoptableDetails_get_xml($pets_ID);                                                                      // returns from function as DOM xml, makes url and gets data from petpoint api  
        $pets_details = $this->AdoptableDetails_change_photo_urls($pets_details);                                                       // because all petpoint photo urls are http and not https
        $pets_details = $this->adjust_age_format($pets_details);                                                 // because all petpoint photo urls are http and not https
        $pets_details = $this->adjust_intake_date_ad($pets_details);                                             // because all petpoint photo urls are http and not https
  
        return $pets_details;  
    }  
  
    //======================================================================================================================================================================
    public function cpws_function($atts = [], $content = null, $tag = ''){                                                                 // this is the function that process the "wordpress shortcode"
        global $pets_data;                                                                                                               //
        global $pets_details;                                                                                                            //
        include_once('partials/cpws_make_html_AdoptableSearch_1.php' );                                                                  // php file that assembles the html for display of search results
        include_once('partials/cpws_make_html_AdoptableDetails_1.php' );                                                                 // php file that assembles the html for display of animal details  
        $atts = array_change_key_case((array)$atts, CASE_LOWER);                                                                         // make lowercase       
        if ( isset($atts['page'])) {                                                                                                     // check to see if the page options is set else issue fault
            switch ($atts['page']) {                                                                                                         // using switch statement to find requested page
            case 'AdoptableSearch' :                                                                                                   // AdoptableSearch page requested
                $pets_data = $this->processes_data_AdoptableSearch($pets_data,$atts);                                                    // make AdoptableSearch data
                return "<br/>".cpws_make_html_AdoptableSearch($pets_data)."<br/>";                                                       // make html page with AdoptableSearch data
                break;                                                                                                                   //
            case 'AdoptableDetails' :                                                                                                  // AdoptableDetails page requested      
                if( isset($_GET['id']) && !empty($_GET['id']) ){ 
                    $request_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
                    $pets_details = $this->processes_AdoptableDetails_data($pets_details,$request_id);                                    // make AdoptableDetails data		
                    return "<br/>".cpws_make_html_AdoptableDetails($pets_details)."<br/>";                                                // make html page with AdoptableDetails data          
                }else{
                    return "<br/> no animal found <br/>";
                }
                break;

            default : var_dump('page not found'); 
            }// end of switch 
        }
        else{
            // var_dump('add page type to shortcode');
        }
          
    }


    /**
     * Replace image URL's that use http with https
     *
     * @since 2.0.0
     */
    function fix_image_urls($data, $selectors) {
        if (empty($selectors)) return $data;
        if (!is_array($selectors)) {
            $selectors = [$selectors];
        }
        
        $array = $xpath = new DOMXPath($data);
        foreach ($selectors as $selector) {
            foreach($xpath->query($selector) as $item) {    // iteration through symbolic path results
                $item->nodeValue = $this->make_https($item->nodeValue);
            }
        }
        return $data; //  as "foreach" was working with paths
    }

    /**
     * Helper function for https replacement
     * @since 2.0.0
     */
    function make_https($string) {
        return str_replace("http://", "https://", $string); 
    }

    // Frontent/Gutenberg blocks stuff


    /**
     * Registers all block assets so that they can be enqueued through Gutenberg in
     * the corresponding context.
     *
     * Passes translations to JavaScript.
     */
    static function gutenberg_examples_01_register_block() {

        if ( ! function_exists( 'register_block_type' ) ) {
            // Gutenberg is not active.
            return;
        }

        wp_register_script(
            'gutenberg-examples-01',
            plugins_url( 'js/example-01-basic.js', __FILE__ ),
            array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'js/example-01-basic.js' )
        );
        
        register_block_type( 'gutenberg-examples/example-01-basic', array(
            'editor_script' => 'gutenberg-examples-01',
        ) );
    }

    /**
     * Registers all block assets so that they can be enqueued through Gutenberg in
     * the corresponding context.
     */
    static function innerBlockExample() {
        if ( ! function_exists( 'register_block_type' ) ) {
            // Gutenberg is not active.
            return;
        }

        wp_register_script(
            'gutenberg-examples-06',
            plugins_url( 'js/example-06-inner.js', __FILE__ ),
            [ 'wp-blocks', 'wp-element', 'wp-block-editor' ],
            filemtime( plugin_dir_path( __FILE__ ) . 'js/example-06-inner.js' ),
            true
        );

        register_block_type(
            'gutenberg-examples/example-06-inner',
            [
                'editor_script' => 'gutenberg-examples-06',
            ]
        );

    }
    
}
