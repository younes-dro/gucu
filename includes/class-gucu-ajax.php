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

        $book       = esc_attr ( $_POST['book'] );
        $post       = esc_attr( $_POST['post'] );
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
            $chapters = '';
           //$chapters .= self::getBreadcrumbs($post);
            $chapters .='<div class="gucu-sub-child-cats">';
            $post = self::getPost( $post );
                
            $chapters .= '<header class="entry-header">';
            $chapters .= '<h1 class="entry-title" itemprop="headline">';
            $chapters .= '<a class="entry-title-link" rel="bookmark" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
            $chapters .= '</h1>';
            $chapters .= '<p class="entry-meta"><time class="entry-time" itemprop="datePublished">'.__('Published on:','gucu').'<br>'.get_the_date(get_option( 'date_format' ), $post->ID ).'</time> </p>';
            $chapters .= '</header>';
            $chapters .= '<div class="entry-content" itemprop="text">';
            $chapters .= '<p class="small-excerpt">'; 
            $chapters .= apply_filters('the_content', $post->post_content ); 
            $chapters .= '</p>';
            $chapters .='</div>'; // .entry-content
            $chapters .='</div>'; // .gucu-sub-child-cats
            
        return $chapters;
        
    }
    public static function getBreadcrumbs( $post ){
        $html = '';
        
        $html .= genesis_breadcrumb();
        
        
        return $html;
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
