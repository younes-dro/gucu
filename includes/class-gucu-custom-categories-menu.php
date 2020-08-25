<?php

/**
 * Display Custom Categories menu.
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display Custom Categories menu.
 * 
 * @class Gucu_Custom_Categories_Menu
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class Gucu_Custom_Categories_Menu {

    /** Parent Category ID * */
    private $parent_cat;

    /** Html * */
    public $html = '';

    public function __construct($parent_cat) {

        $this->parent_cat = $parent_cat;
    }

    public function get_html() {

        if ($this->parent_category()) {

            $this->html = '<article style="margin-top:14px;" class="gucu-custom-bibe page type-page status-publish has-post-thumbnail entry">';
            $this->html .='<ul class="gucu-cats">';
            $this->html .= '<li>';

            $parent = $this->parent_category();
            $this->html .='<a class="parent-cat" href="#">' . $parent->name . '</a>' . ' (' . $parent->count . ')';

            $args = array('parent' => $this->parent_cat, 'hide_empty' => false);
            $categories = get_categories($args);
            foreach ($categories as $category) {
                $has_children = ($this->category_has_children( $category->term_id )) ? true : false;
                $open_icon = ($has_children) ? '<span class="gucu-open ionicons ion-ios-add-circle-outline"></span>' :'';
                $this->html .= '<ul class="gucu-sub-cats">';
                $this->html .= '<li>';
                $this->html .= $open_icon . '<a class="parent-cat" href="#">' . $category->name . '</a>' . ' (' . $category->count . ')';
                // Child Cat
                if ( $has_children ) {
                    $this->html .= $this->get_sub_category( $category->term_id );
                }
                $this->html .= '</li>';
                $this->html .= '</ul>';
            }
            $this->html .= '</li>';
            $this->html .= '</ul></article>';
        } else {
            $this->html = 'This category does not exist !';
        }

        return $this->html;
    }

    private function parent_category() {

        $parent_cat = get_category($this->parent_cat);

        return $parent_cat;
    }

    private function get_sub_category($id) {
        $sub ='';
        
        $args = array('parent' => $id, 'hide_empty' => false);
        $categories = get_categories($args);
        foreach ($categories as $category) {
            $has_posts = ($category->count)? true : false;
            $open_icon = ($has_posts) ? '<span class="gucu-open ionicons ion-ios-add-circle-outline"></span>' :'';
            $sub .= '<ul class="gucu-sub-child-cats">';
            $sub .= '<li>';
            $sub .= $open_icon . '<a  class="parent-cat" href="#">' . $category->name . '</a>' . ' (' . $category->count . ')';
            if( $has_posts ){
                $sub .='<ul class="gucu-sub-child-cats">';
                $posts = get_posts( array ( 
                                            'category' => $category->term_id , 
                                            'post_status' =>'publish', 
                                            'orderby' => 'publish_date',
                                            'order' => 'ASC'
                                        ) 
                                );
                
                foreach ($posts as $post) {
//                    var_dump($post);
                    $sub .= '<li>';
                    $sub .= '<a href="'.get_permalink( $post->ID ).'">'.$post->post_title. '</a>';
                    $sub .='</li>';
                }
                $sub .='</ul>';
            }
            $sub .= '</li>';
            $sub .= '</ul>';
        }
        
        return $sub;
    }

    private function category_has_children($term_id = 0, $taxonomy = 'category') {

        $children = get_categories(array(
            'child_of' => $term_id,
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'ids'));

        return ( $children );
    }

    private function get_posts($category) {
        
    }

}
