<?php

/**
 * Shortcode .
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Admin Custom script .
 * 
 * @class Gucu_ShortCode
 * @author Younes DRO <younesdro@gmail.com>
 * @version 4.2.0
 * @since 4.2.0
 */
class Gucu_ShortCode {

    public static function add_shortcode(){
        
        add_shortcode ( 'gucu_tooltip' , array ( 'Gucu_ShortCode' , 'display_tooltip' ) );
    }
    public static function display_tooltip( $atts ){
        
        extract( $atts );
        $tooltip = get_post( $id ); 
        
        $html  .= '<span class="tooltip-container">';
        $html  .= '<span class="ionicons ion-ios-chatbubbles"></span>';
      $html .= '<span class="tooltip-content">'.$tooltip->post_content.'</span>';
        $html .= '</span>';
        
        return $html;
    }
}
