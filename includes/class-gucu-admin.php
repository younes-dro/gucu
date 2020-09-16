<?php

/**
 * Admin .
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
 * @class Gucu_Ajax
 * @author Younes DRO <younesdro@gmail.com>
 * @version 4.2.0
 * @since 4.2.0
 */
class Gucu_Admin {
    
    
    public static function Attach_Category(){
        global $pagenow;

        if ( ( $pagenow === 'post-new.php' ) ||  isset( $_GET['post'] ) && self::post_is_in_descendant_category (BIBLE_ID , $_GET['post'] ) && $_GET['action'] == 'edit' ){
                add_action( 'add_meta_boxes', array ( 'Gucu_Admin' , 'commentary_cat' ) );
                add_action("save_post", array ( "Gucu_Admin" , "save_custom_meta_box" ), 10, 3 );    
        }        
    }
    
    public static function commentary_cat() {
        $screens = ['post'];
        foreach( $screens as $screen ) {
            add_meta_box(
                'mycpt_assignment',
                __( 'Assign Commentary Category', 'gucu') ,
                array ( 'Gucu_Admin' , 'assign_metabox' ),
                $screen,
                'side',
                'low',
                 array(
                    '__block_editor_compatible_meta_box' => true,
                    '__back_compat_meta_box'             => false,
                )
            );
        }
    }    
    
    public static function assign_metabox( $object ){
        
        
            wp_nonce_field(basename(__FILE__), "meta-box-nonce");
            $html = '';
            $args = array('parent' => COMMENTARY_ID, 'hide_empty' => false, 'orderby' => 'name' , 'order' => 'desc');
            $categories = get_categories($args);
            $html .= '<select name="gucu-meta-box">';
                    $html .='<option value="">---</option>';
                    foreach ($categories as $category) {
                        $html .= '<optgroup label="'.$category->name.'">';
                        $args = array('parent' => $category->term_id, 'hide_empty' => false, 'orderby' => 'name' , 'order' => 'desc');
                        $subcats = get_categories( $args );
                        foreach ($subcats as $subcat ) {
                           $selected = ( $subcat->term_id == get_post_meta($object->ID, "gucu-meta-box", true ) )  ? 'selected' : '' ;
                            $html .= '<option '. $selected . ' value="'.$subcat->term_id.'">'.$subcat->name.'</option>';
                        }
                        $html .= '<optgroup>';
                    }
            $html .='</select>';

            echo $html;             
        
     
    }
    
    public static function save_custom_meta_box( $post_id  ){
        
        
        if(isset($_POST["gucu-meta-box"]))
        {
            update_post_meta($post_id, "gucu-meta-box" , $_POST["gucu-meta-box"] );
        }   
        
    }
/**
 * Tests if any of a post's assigned categories are descendants of target categories
 *
 * @param int|array $cats The target categories. Integer ID or array of integer IDs
 * @param int|object $_post The post. Omit to test the current post in the Loop or main query
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @see get_term_by() You can get a category by name or slug, then pass ID to this function
 * @uses get_term_children() Passes $cats
 * @uses in_category() Passes $_post (can be empty)
 * @version 2.7
 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
 */
    public static function post_is_in_descendant_category( $cats, $_post = null ) {
        foreach ( (array) $cats as $cat ) {
            // get_term_children() accepts integer ID only
            $descendants = get_term_children( (int) $cat, 'category' );
            if ( $descendants && in_category( $descendants, $_post ) )
                return true;
        }
        return false;
    }  

}
