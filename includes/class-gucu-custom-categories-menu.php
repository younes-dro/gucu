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
 * @version 2.0.0
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

            $this->html = '<article class="gucu-custom-bibe page type-page status-publish has-post-thumbnail entry">';
            $this->html .= '<div class="container-cat">';
            
            $args = array('parent' => $this->parent_cat, 'hide_empty' => false, 'orderby' => 'name' , 'order' => 'desc');
            $categories = get_categories($args);
            foreach ($categories as $category) {
                $has_children = ($this->category_has_children( $category->term_id )) ? true : false;
                $this->html .= '<div class="container-subcat">';
                $this->html .=  '<a class="parent-cat" href="#">' . $category->name . '</a>' ;
                // Books
                if ( $has_children ) {
                    $this->html .= $this->get_drop_down_menu( $category->term_id );
                }
                
                $this->html .= '</div>';
            }
            $this->html .= '</div>';
            $this->html .='<div class="posts-numbers"></div>';
            $this->html .='<p class="content-chapters"></p>';
            $this->html .= '</article>';
            
        } else {
            $this->html = 'This category does not exist !';
        }

        return $this->html;
    }

    private function parent_category() {

        $parent_cat = get_category($this->parent_cat);

        return $parent_cat;
    }
    
    private function get_drop_down_menu( $id ){
    
        $args = array('parent' => $id, 'hide_empty' => false, 'orderby' => 'id');  
        $books = get_categories($args);
        $books_menu = '<select name="gucu-books-bible" id="gucu-books-bible-'.$id.'" class="gucu-books-bible">';
        $books_menu .= '<option value=""></option>';
        foreach ($books as $book ) {
            $books_menu .= '<option value="'.$book->term_id.'">';
            $books_menu .= $book->name;
            $books_menu .= '</option>';
        }
        $books_menu .= '</select>';
        
        return $books_menu;
    }
    private function get_sub_category($id) {
        $sub ='';
        
        $args = array('parent' => $id, 'hide_empty' => false, 'orderby' => 'id');
        $categories = get_categories($args);
        foreach ($categories as $category) {
            $has_posts = ($category->count) ? true : false;
            $open_icon = ($has_posts) ? '<span class="gucu-open ionicons ion-ios-add-circle-outline"></span>' :'';
            $sub .= '<ul class="gucu-sub-child-cats">';
            $sub .= '<li>';
            $sub .= $open_icon . '<a  class="parent-cat" href="#">' . $category->name . '</a>';
            if( $has_posts ){
                $sub .='<ul class="gucu-sub-child-cats">';
                $posts = get_posts( array ( 
                                            'category' => $category->term_id , 
                                            'post_status' =>'publish', 
                                            'orderby' => 'publish_date',
                                            'order' => 'ASC',
                                            'numberposts' => -1
                                        ) 
                                );

                foreach ($posts as $post) {
                    $post_thumbnail_url = get_the_post_thumbnail_url( $post->ID , array( 'post-thumbnail' ) );
                    $background_url = ( $post_thumbnail_url ) ? 'style="background-image:url(' .  $post_thumbnail_url .')"' : '';
                    
                    $sub .= '<li class="gucu-thumb" '.$background_url.'>';
                    $sub .= '<a class="gucu-single-post" href="'.get_permalink( $post->ID ).'">'.$post->post_title. '</a>';
                    $sub .=  '<p>'. wp_trim_words($post->post_content, 50, '... <span class="readmore">Read more</span>').'</p>';
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
