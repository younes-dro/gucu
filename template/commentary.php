<?php
/**
 * Template Name: Commentary Template
 */


get_header(); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			the_title( '<h1 class="entry-title">', '</h1>' ); 
                        
                        the_content();
                        

		endwhile; // End of the loop.
		?>
<?php 
get_footer();