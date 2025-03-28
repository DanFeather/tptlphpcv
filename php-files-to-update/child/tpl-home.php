<?php
/**
 * Template Name: Home Page
 *
 *
 * @package Thematic
 * @subpackage Templates
 */

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();
?>

<div id="homeslides">
<?php
		$args = array(
		'order' => 'asc',
		'category'         => 3,
		'posts_per_page'   => '-1',
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'suppress_filters' => true );

		$home_sliders = get_posts( $args );
		echo "<ul class=\"bxslider\">";
		$i = 0;
		foreach($home_sliders as $s) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id($s->ID), 'full' );
			$url = $img['0'];
			$i++;
			echo "<li><div class=\"slidercontent\" style=\"display:none;visibility:hidden;\"><h1>".$s->post_title."</h1>".$s->post_content."</div>".
				"<div class=\"slideimg\"><img id=\"".$i."\" src=\"".$url."\" alt=\"".get_the_title($s->ID)."\" /></div>".

				"</li>\n";
		}
echo "</ul>";
?>
</div>

		<div id="container" class="homepg">

			<div id="content">
			<?php

	            // start the loop
	            while ( have_posts() ) : the_post();

				// action hook for placing content above #post
	            thematic_abovepost();
	        ?>

				<div class="entry-content">
					<div id="home-intro" class="fcol">
						<?php the_content(); ?>
					</div>
					<div id="home-img" class="fcol">
						<img src="<?php echo site_url(); ?>/wp-content/uploads/2017/12/lodge-interior-home1-1.jpg" alt="Pine Tavern Lodge Suite 3A Living Area" />
						<img id="home-img2" src="<?php echo site_url(); ?>/wp-content/uploads/2014/09/pine-tavern-view.jpg" alt="Pine Tavern Lodge view" />
					</div>
				</div>

			<?php

	        	// end loop
        		endwhile;

	        ?>


				
			</div><!-- #content -->

			<?php
				// action hook for placing content below #content
				thematic_belowcontent();
			?>


		</div><!-- #container -->

<?php
    // action hook for placing content below #container
    thematic_belowcontainer();

    // calling footer.php
    get_footer();
?>
