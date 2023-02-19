<?php

namespace Petpoint\Core;

use Petpoint\Admin\Settings;
use Petpoint\Core\XmlParser;

/**
 *
 */
class WebApi {

    const API_VERSION = '1.0.2';
    public static $endpoint = 'http://ws.petango.com/webservices/wsAdoption.asmx/';

    // Most common API endpoint routes used
    public static $details = 'AdoptableDetails';
    public static $search = 'AdoptableSearch';

    private static $defaultData = [];

    public static $methods = [
        'AdoptableDetails',
        'AdoptableSearch',
        'AdoptableSearchWithStage',
        'AdoptionDetails',
        'AdoptionList',
        'HappyTailDetails',
        'HappyTailList',
        'MedicalViewReportPdf',
        'foundDetails',
        'foundDetailsForCompanyGroup',
        'foundSearch',
        'foundSearchForCompanyGroup',
        'foundSearchForCompanyGroupPageable',
        'foundSearchWithSite',
        'lostDetails',
        'lostDetailsForCompanyGroup',
        'lostSearch',
        'lostSearchForCompanyGroup',
        'lostSearchForCompanyGroupPageable',
        'AdoptiableSearchRestricted'
    ];
    
    function _construct() {
        // error_log("PP: WebApi Loaded");
        self::__setDefaultData();
    }

    // Didn't work yet :-|
    static function __setDefaultData() {
        // self::$defaultData = [
        //     'AdoptableDetails' => [
        //         'animalID' => 45598932
        //     ],
        //     'AdoptableSearch' => [
        //         'speciesID' => 1,
        //         'sex' => 'A',
        //         'ageGroup' => 'All',
        //         'location' => '',
        //         'site' => 0,
        //         'onHold' => 'A',
        //         'orderBy' => '',
        //         'primaryBreed' => 'All',
        //         'secondaryBreed' => 'All',
        //         'specialNeeds' => 'A',
        //         'noDogs' => 'A',
        //         'noCats' => 'A',
        //         'noKids' => 'A',
        //         // 'stageID' => 'A'
        //     ]
        // ];
    }

    /**
     * Do Post
     *
     * @param $method The method name we call
     * @param $body The http post data we send
     * @return mixed 
     */
    function _doPost($method, $body = []) {
        if (empty($method)) {
            return WP_Error("No API method was passed to _doPost()");
        }

        // FIXME: merge data being passed to the api with any defaults
        // array_replace_recursive($defaults, $body)

        $defaultData = [
            'AdoptableDetails' => [
                'animalID' => 45598932
            ],
            'AdoptableSearch' => [
                'speciesID' => 1,
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
            ]
        ];

        $body = shortcode_atts($defaultData[$method], $body);
        $body['authkey'] = Settings::getAuthkey();
        
        // Set headers if any, API returns Cache-Control private max-age=0
        $data = [
            // 'headers' => [],
            'body' => $body
        ];

        // if (WP_DEBUG) {
        //     echo "<h3>$method  Request Data:</h3><pre class='debug-data'>";
        //     print_r( $data );
        //     echo '</pre>';
        // }


        $response = wp_remote_post(self::$endpoint . $method, $data);
         
         if ( is_wp_error( $response ) ) {
             self::_get_wp_error($method, $response);
             return;
         }
         
         if (WP_DEBUG) {
             // To debug the response data inside the options page
             //echo $method . ' Response:<pre class="debug-data">';
             //print_r( $response );
             //echo '</pre>';
         }

         $this->response_code = $response['http_response']->get_status();
         $this->response = $response['http_response']->get_response_object();

         if ($this->response->success !== true) {
             $this->_doApiError();

             // Returns the unparsed data if it is not valid XML
             return [$this->method, $this->response->body];
         }

         // Returns parsed data if it was successful
         return XMLParser::getObject($this->response->body);
    }

     /**
     * Just test the things are working
     */
    public function _testPost($method, $testData = []) {
        $this->method = $method;
        if (empty($this->method))  {
            $this->method = 'AdoptableSearch';
        }
        

        // NOTE, in _doPost()
        // $values are default values to test for AdoptableSearch
        if ($this->getData($testData))
        

        // self::_validateResponse($result);
        return $this->result;
    }

    static function _validateResponse($result) {
        return $result;
    }

    /**
     * Just test the things are working
     */
    public function getData($data) {
        $this->result = $this->_doPost($this->method, $data);
        if  (is_wp_error($this->result)) {
            $this->_get_wp_error();
            return false;
        }

        return true;
    }

    /**
     * Get Adoption Listing, return the data needed for the results page
     */
    public function AdoptableSearch($attrs) {
        $this->method = 'AdoptableSearch';
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

        $params = shortcode_atts($defaults, $attrs);

        $transientKey = 'ppi_search_' . $params['speciesID'];
        // the getData call sets up the instance variables with the xml response
        if (WP_DEBUG_HEADER && !is_admin()) {
            echo "<h3>{$this->method} Request Data:</h3><pre class='debug-data'>"
                . print_r( $params, true )
                . '</pre>';
        }

        
        // self::_validateResponse($result);
        if ( false === ( $search = get_transient($transientKey) ) ) {
            if ($this->getData($params) === false) {
                delete_transient($transientKey);
                return [];
            }

            $search = XMLParser::_getAdoptableSearch($this->response->body);
            set_transient($transientKey, $search, HOUR_IN_SECONDS);
        }

        return $search;
    }

    /**
     * Get The Adoption Listing, return the data needed for the results page
     */
    public function getDetails($animalID) {
        $this->method = 'AdoptableDetails';
        $transientKey = 'ppi_details_' . $animalID;
        $params = [
            'animalID' => (
                (empty($animalID)) ?
                $this->defaultData[$this->method] : $animalID
            )
        ];

        if ( false === ( $details = get_transient($transientKey) ) ) {
            if ($this->getData($params) === false) {
                delete_transient($transientKey);
                return [];
            }

            $details = XMLParser::_getAdoptableDetails($this->response->body);
            set_transient($transientKey, $details, HOUR_IN_SECONDS);
        }
        return $details;
    }

    /**
     * Api Error Handler
     *
     * @return void
     */
    function _doApiError() {
        echo "<h2>PetPoint API ERROR ({$this->response_code}):</h2>"
            . "<h3>{$this->response->body}</h3>";

        if ($this->response_code == 404) {
            echo "<h3>That listing could not be found</h3>";
        }

        if (WP_DEBUG) {
            echo '<pre class="debug-data">';
            print_r($this->response);
            echo '</pre>';
        }
    }


    function _get_wp_error() {
        echo 'WP ERROR:<pre class="debug-data">';
        print_r( $this->result );
        echo '</pre>';
        
        $error_message = $this->result->get_error_message();
        
        echo "Something went wrong with the API method: {$this->method}: {$error_message}";
             
        $error_codes  = $this->result->get_error_codes();
        foreach ($error_codes as $code)
        {
            $error_messages = \get_error_messages($code);
            echo "<pre>HTTP API Errors: ". implode(PHP_EOL, $error_messages) . ' </pre>';
            echo '<pre>'.json_encode(\get_error_data($code)) . '</pre>';
        }
    }

}
