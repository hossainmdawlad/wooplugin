<?php
/**
 * Template used to display post content.
 *
 */


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
            echo '<h1>Hi there</h1>';
			while ( have_posts() ) :
                the_post();
                the_title( '<h1 class="entry-title">', '</h1>' );
                ?>
                <form class="video_upload_form" action="#">
                    <input type="file" name="upload_video" id="upload_video" />
                    <input type="submit" value="Upload" />
                </form>
                <?php
				the_content();

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
