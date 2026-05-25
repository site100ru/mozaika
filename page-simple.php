<?php

/**
 * Template Name: Простая страница
 * Template Post Type: page
 */

include 'header.php';

?>


<!-- Home section -->
<div id="sp-home" class="scroll-points"></div>
<section class="site-wrap" style="height: 400px; z-index: auto;">
	<div class="jobs-home-section" style="min-height: 400px;"></div>

	<?php get_template_part('template-parts/header-section/header-section'); ?>

	<div class="container">
		<div class="row align-items-center home-section-height min-home-section-height">
			<div class="col-xl-10 col-xxl-9">
				<h1 class="home-title" style="color: #fff;"><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
</section>
<!-- /Home section -->


<!-- Content -->
<section class="pt-4 bg-white" style="padding-bottom: 145px;">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumbs">
					<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">
						<a href="<?php echo home_url('/'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ico/breadcrumbs-icon.svg"></a>
						/ <?php the_title(); ?>
					</nav>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							the_content();
						endwhile;
					endif;
				?>
			</div>
		</div>
	</div>
</section>
<!-- /Content -->


<?php include 'footer.php'; ?>
