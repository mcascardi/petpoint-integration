<?php
namespace Petpoint\Frontend\Blocks;

defined('ABSPATH') || die();

class AdoptableSearch {

    function __construct() {
        if ( ! function_exists( 'register_block_type' ) ) {
            // Gutenberg is not active.
            return;
        }

        register_block_type( 'ppi/adoptable-search', [
            'attributes' => [
                
            ],
            'editor_script' => 'ppi-adoptable-search-script',
            'editor_style' => 'ppi-adoptable-search-editor-style',
            'style' => 'ppi-adoptable-search-style'
        ]);
    }
}

// EOF
