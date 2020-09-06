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

    /**
     * 
     * @param init $book The boook id
     */
    public static function sendRequest() {

        $book = $_POST['book'];

        echo self::getChapters( $book );

        die();
    }

    public static function getChapters($book) {
            $category = get_category($book);
            $parent_category = get_category_parents($category->term_id, false,'/');
            $c = explode('/', $parent_category);
            echo '<span>'. $c[0] .'</span>' . ' > ' . '<span>' . $c[1] .'</span>';
            echo '<h4>' . $category->name . '</h4>';
            $chapters .='<div class="gucu-sub-child-cats">';
            $posts = get_posts(array(
                'category' => $book,
                'post_status' => 'publish',
                'orderby' => 'publish_date',
                'order' => 'ASC',
                'numberposts' => -1
                    )
            );

            foreach ($posts as $post) {
                $post_thumbnail_url = get_the_post_thumbnail_url($post->ID, array('post-thumbnail'));

                $chapters .= '<div class="gucu-thumb">';
                $chapters .= '<img src="'.$post_thumbnail_url.'" />';
                $chapters .= '<a class="gucu-single-post" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                $chapters .= '<p class="large-excerpt">' . wp_trim_words($post->post_content, 50, '<a href="'.get_permalink($post->ID).'">... <span class="readmore">Read more</span></a>') . '</p>';
                $chapters .= '<p class="small-excerpt">' . wp_trim_words($post->post_content, 50, '<a href="'.get_permalink($post->ID).'">... <span class="readmore">Read more</span></a>') . '</p>';
                $chapters .='</div>';
            }
            $chapters .='</div>';
            
            return $chapters;
        
    }

}
