<?php
/**
 * Template Name: Bible Template
 *
 */


get_header(); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			the_title( '<h1 class="entry-title">', '</h1>' ); 
                        
                        the_content();
                        

		endwhile; // End of the loop.
                ?>
<div>
<select name="" id="">
    <option value="">Old Testament</option>
    <option value="">New Testament</option>
</select>
</div>
<?php

get_footer();