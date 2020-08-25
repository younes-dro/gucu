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
        echo $gucu_custom_cat_menu->get_html();
        ?>

    </main>
</div><!-- ./content-sidebar-wrap -->
<?php
get_footer();
?>

<style>
    span.ionicons.ion-ios-add-circle-outline,
    span.ion-ios-remove-circle-outline{
        font-size: 12px;
        transform: scale(2);
        display: inline-block;
        margin-right: 11px;
        cursor: pointer;        
    }
    ul.gucu-cats{
        
    }
    ul.gucu-sub-cats{
        /*display: none;*/
        margin-left: 12px;
    }
    ul.gucu-sub-child-cats{
        display: none;
        margin-left: 12px;
    }
    ul.gucu-sub-child-cats.open{
        display: block;
    }
</style>