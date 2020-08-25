<?php
/**
 * Template Name: Bible Template
 *
 */
get_header();
?>
<div class="content-sidebar-wrap">
    <main class="main" id="genesis-content">
        <?php
        while (have_posts()) :
            the_post();

            the_title('<h1 class="entry-header">', '</h1>');

            echo '<div class="entry-content">';
            the_content();
            echo '</div>';

        endwhile; // End of the loop.

        $gucu_custom_cat_menu = new Gucu_Custom_Categories_Menu(218);
//        echo '<pre>';
        echo $gucu_custom_cat_menu->get_html();
//        echo '</pre>';
//            $gucu_custom_cat_menu::test();
//            $bible = get_category(218, ARRAY_A);
//
//
//
//
//
//            $args = array('parent' => 218 , 'hide_empty' => false);
//
//            $categories = get_categories($args);
//
//            foreach ($categories as $category) {
//                
//                if ( category_has_children( $category->term_id ) ){
//                 echo '<i>has child</i>';   
//                }else{
//                    echo '<i>has no child</i>';   
//                }
//
//                echo '<p>Category: <a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__("View all posts in %s"), $category->name) . '" ' . '>' . $category->name . '</a> </p> ';
//                echo '<p> Description:' . $category->description . '</p>';
//                echo '<p> Post Count: ' . $category->count . '</p>';
//            }
        ?>

    </main>
</div><!-- ./content-sidebar-wrap -->
<?php
get_footer();
