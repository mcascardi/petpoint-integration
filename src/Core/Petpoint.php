<?php

class Petpoint {
    
    public function no_longer_needed_validate($input) {
        // All checkboxes inputs
        $valid = array();
        $valid['pp_authkey']                  = sanitize_text_field($input['pp_authkey']);		
        $valid['cpws_url_AdoptableSearch']    = 'http://ws.petango.com/webservices/wsAdoption.asmx/AdoptableSearch'; 
        $valid['cpws_url_AdoptableDetails']   = 'http://ws.petango.com/webservices/wsAdoption.asmx/AdoptableDetails';     
        $valid['cpws_wpsite_details_url']     = sanitize_text_field($input['cpws_wpsite_details_url']);	      
        $valid['main_width']                  = sanitize_text_field($input['main_width']);	
        $valid['site_1_name']                 = sanitize_text_field($input['site_1_name']);
        $valid['site_1_css']                  = sanitize_text_field($input['site_1_css']);
        $valid['site_2_name']                 = sanitize_text_field($input['site_2_name']);
        $valid['site_2_css']                  = sanitize_text_field($input['site_2_css']);   
        $valid['pp_display_NodeName']         = (isset($input['pp_display_NodeName']) && !empty($input['pp_display_NodeName'])) ? 1 : 0;                  //    
        $valid['pp_display_sponsor_button']   = (isset($input['pp_display_sponsor_button']) && !empty($input['pp_display_sponsor_button'])) ? 1 : 0;      //         
        $valid['pp_display_icon_row']         = (isset($input['pp_display_icon_row']) && !empty($input['pp_display_icon_row'])) ? 1 : 0;                  //           
        $valid['rm_specialneeds']             = (isset($input['rm_specialneeds']) && !empty($input['rm_specialneeds'])) ? 1 : 0;                          // 
        $valid['pp_xml_id']                   = (isset($input['pp_xml_id']) && !empty($input['pp_xml_id'])) ? 1 : 0;                                      // petpoint "A"number
        $valid['pp_xml_arn']                  = (isset($input['pp_xml_arn']) && !empty($input['pp_xml_arn'])) ? 1 : 0;                                    // not available to all animals
        $valid['pp_xml_name']                 = (isset($input['pp_xml_name']) && !empty($input['pp_xml_name'])) ? 1 : 0;                                  // character length can be an issue
        $valid['pp_xml_featured']             = (isset($input['pp_xml_featured']) && !empty($input['pp_xml_featured'])) ? 1 : 0;                          // bolean : y or n
        $valid['pp_xml_species']              = (isset($input['pp_xml_species']) && !empty($input['pp_xml_species'])) ? 1 : 0;                            // Species according to petpoint
        $valid['pp_xml_sex']                  = (isset($input['pp_xml_sex']) && !empty($input['pp_xml_sex'])) ? 1 : 0;                                    // bolean : M or F
        $valid['pp_xml_primarybreed']         = (isset($input['pp_xml_primarybreed']) && !empty($input['pp_xml_primarybreed'])) ? 1 : 0;                  // Primary Breed
        $valid['pp_xml_secondarybreed']       = (isset($input['pp_xml_secondarybreed']) && !empty($input['pp_xml_secondarybreed'])) ? 1 : 0;              // Secondary Breed
        $valid['pp_xml_sn']                   = (isset($input['pp_xml_sn']) && !empty($input['pp_xml_sn'])) ? 1 : 0;                                      // spay or neutered
        $valid['pp_xml_age']                  = (isset($input['pp_xml_age']) && !empty($input['pp_xml_age'])) ? 1 : 0;                                    // age in monrths
        $valid['pp_xml_agegroup']             = (isset($input['pp_xml_agegroup']) && !empty($input['pp_xml_agegroup'])) ? 1 : 0;                          // logic age groups from petpoint
        $valid['pp_xml_photo']                = (isset($input['pp_xml_photo']) && !empty($input['pp_xml_photo'])) ? 1 : 0;                                // returns url location
        $valid['pp_xml_location']             = (isset($input['pp_xml_location']) && !empty($input['pp_xml_location'])) ? 1 : 0;                          // typicaly kennel group
        $valid['pp_xml_sublocation']          = (isset($input['pp_xml_sublocation']) && !empty($input['pp_xml_sublocation'])) ? 1 : 0;                    // typicaly kennel number
        $valid['pp_xml_onhold']               = (isset($input['pp_xml_onhold']) && !empty($input['pp_xml_onhold'])) ? 1 : 0;                              // bolean : y or n
        $valid['pp_xml_nodogs']               = (isset($input['pp_xml_nodogs']) && !empty($input['pp_xml_nodogs'])) ? 1 : 0;                              // bolean : y or n
        $valid['pp_xml_nocats']               = (isset($input['pp_xml_nocats']) && !empty($input['pp_xml_nocats'])) ? 1 : 0;                              // bolean : y or n
        $valid['pp_xml_nokids']               = (isset($input['pp_xml_nokids']) && !empty($input['pp_xml_nokids'])) ? 1 : 0;                              // bolean : y or n
        $valid['pp_xml_specialneeds']         = (isset($input['pp_xml_specialneeds']) && !empty($input['pp_xml_specialneeds'])) ? 1 : 0;                  // bolean : y or n
        $valid['pp_xml_behaviortestlist']     = (isset($input['pp_xml_behaviortestlist']) && !empty($input['pp_xml_behaviortestlist'])) ? 1 : 0;          // can be blank
        $valid['pp_xml_behaviorresult']       = (isset($input['pp_xml_behaviorresult']) && !empty($input['pp_xml_behaviorresult'])) ? 1 : 0;              // such as family rating or safer results
        $valid['pp_xml_memolist']             = (isset($input['pp_xml_memolist']) && !empty($input['pp_xml_memolist'])) ? 1 : 0;                          // not available to all shelters
        $valid['pp_xml_stage']                = (isset($input['pp_xml_stage']) && !empty($input['pp_xml_stage'])) ? 1 : 0;                                // used for display filters
        $valid['pp_xml_animaltype']           = (isset($input['pp_xml_animaltype']) && !empty($input['pp_xml_animaltype'])) ? 1 : 0;                      // similar to species
        $valid['pp_xml_wildlifeintakeinjury'] = (isset($input['pp_xml_wildlifeintakeinjury']) && !empty($input['pp_xml_wildlifeintakeinjury'])) ? 1 : 0;  // can be blank
        $valid['pp_xml_wildlifeintakecause']  = (isset($input['pp_xml_wildlifeintakecause']) && !empty($input['pp_xml_wildlifeintakecause'])) ? 1 : 0;    // can be blank
        $valid['pp_xml_buddyid']              = (isset($input['pp_xml_buddyid']) && !empty($input['pp_xml_buddyid'])) ? 1 : 0;                            // can be blank
        $valid['pp_xml_chipnumber']           = (isset($input['pp_xml_chipnumber']) && !empty($input['pp_xml_chipnumber'])) ? 1 : 0;                      // can be blank

        return $valid;
    }


    
}
