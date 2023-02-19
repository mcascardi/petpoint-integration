<?php


namespace Petpoint\Core;

class XmlParser {

    function __construct() {
        call_user_func ([$this, $method], $data);
    }

    public static function getObject($data) {
        // echo  '<pre>' . htmlentities($data) . '</pre>';
        // return new \SimpleXmlElement($data);
        return simplexml_load_string($data);
    }

    /**
     * Inner function for parsing AdoptableSearch data
     */
    public static function _getAdoptableSearch($data) {
        $o = self::getObject($data);

        $a = [];
        foreach ($o->children() as $e) {
            $c = $e->children()->adoptableSearch;
            if (isset($c->ID)) {
                $a[] = $c;
            }
        }
        
        return self::xml2array($a);
    }

    
    public static function _getAdoptableDetails($data) {
        return self::xml2array(self::getObject($data));
    }

    public static function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? self::xml2array ( $node ) : $node;
        
        return $out;
    }
}

// EOF
