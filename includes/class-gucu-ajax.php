<?php

/**
 * Ajax .
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display The bible chapters .
 * 
 * @class Gucu_Ajax
 * @author Younes DRO <younesdro@gmail.com>
 * @version 2.0.0
 * @since 2.0.0
 */
class Gucu_Ajax {
    
    public static $has_commentary;

    /**
     * 
     * @param init $book The boook id
     */
    public static function sendRequest( $request = '' ) {

        $book = $_POST['book'];
        $post  = $_POST['post'];
        if ( $request == 'grid'){
            echo self::buildGridPosts(  $book   );
        }else if( $request == 'fullpost'){
            echo self::fullPostContent( $post );
        }else{
            echo self::getChapters( $book , $post);
        }

        die();
    }
    
    public static function buildGridPosts( $book ){

       
        $grid  = '<div class="grid">';
        $grid .= '<h4>CHAPTER<span class="close-chapter ionicons ion-ios-close"></span></h4>';
        $grid .='<div class="grid-content">';
        $posts = self::getPosts( $book );
        
        foreach ($posts as $index=>$post ) {
            
            $grid .=  '<a class="chapter-number" href="#" data-slide-index="'. $index .'" data-book-id ="'.$book.'" data-post-id="'.$post->ID.'">'.$post->post_title .'</a>';
        }
        $grid .='</div>';//content grid
        $grid .= '</div>';//grid
        
        return $grid;
        
    }

    public static function getChapters( $book , $post ) {
            $category = get_category( $book );
            echo '<h4>' . $category->name . '</h4>';
            $chapters .='<div class="gucu-sub-child-cats">';
            $posts = self::getPosts( $book );

            foreach ($posts as $post) {
                
                if ( ! isset( self::$has_commentary  )){
                    self::hasCommentary( $post->ID );
                }

                /* $post_thumbnail_url = get_the_post_thumbnail_url($post->ID, 'featured-blog'); */

                $chapters .= '<div class="gucu-thumb">';
                /* $chapters .= '<img src="'.$post_thumbnail_url.'" />'; */
                $chapters .= '<a class="gucu-single-post" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                /* $chapters .= '<p class="large-excerpt">' . wp_trim_words($post->post_content, 50, '<a data-post-id="'.$post->ID.'" class="read-full-post" href="'.get_permalink($post->ID).'">... <span class="readmore">Read more</span></a>') . '</p>'; */
                /* $chapters .= '<p class="small-excerpt">' . wp_trim_words($post->post_content, 50, '<a data-post-id="'.$post->ID.'" class="read-full-post" href="'.get_permalink($post->ID).'">... <span class="readmore">Read more</span></a>') . '</p>'; */
                $chapters .= '<p class="small-excerpt">'; 
                $chapters .= $post->post_content; 
                $chapters .= '</p>';
                $chapters .='</div>'; // .gucu-thump
            }
            $chapters .='</div>'; // .gucu-sub-child-cats
            if ( self::$has_commentary  ){
                $chapters .= '<hr />';
                $chapters .='<h2>'.__(' Commentary' ,'gucu' ).'</h2>';  
                $commentaries = self::getPosts ( self::$has_commentary );
                foreach ($commentaries as $commentary) {
                   $chapters .= '<a class="gucu-single-commentary" href="' . get_permalink($commentary->ID) . '">' . $commentary->post_title . '</a>';
                   $chapters .= '<p class="commentary-excerpt">' .$commentary->post_excerpt .'<a href="'.get_permalink($commentary->ID).'" class="readmore">'.__( 'Read more...' , 'gucu') .'</a>' . '</p>';
                }
                
            }

            
            return $chapters;
        
    }
    public static function hasCommentary( $post_id ){
        
        $commenatry_cat = get_post_meta ( $post_id , 'gucu-meta-box' );
        
        return self::$has_commentary = $commenatry_cat[0];
      
    }
    public static function setHasCommentary( ){
        
    }
    public static function fullPostContent( $post ){
        
        $the_post = self::getPost( $post );
        /* $post_thumbnail_url = get_the_post_thumbnail_url($the_post->ID, 'featured-blog'); */
        $content .= '<header class="entry-header"><h1 class="entry-title" itemprop="headline">'.$the_post->post_title.'</h1></header>';
        /* $content .= '<img src="'.$post_thumbnail_url.'" />'; */
        $content .= '<div class="entry-content" itemprop="text">'.$the_post->post_content.'</div>';
        
        return $content;
    }


    public static function getPosts( $book ){
        return $posts = get_posts(array(
                'category' => $book,
                'post_status' => 'publish',
                'orderby' => 'id',
                'order' => 'ASC',
                'numberposts' => -1
                    )
            );
    }
    public static function getPost($id){
        return get_post($id);
    }

}
