<?php get_header();?>
	<div class="container" id="main-page-container">
		<div class="row">
			<div class="col-md-12">
				<?php if(have_posts()):?>
         			<?php while ( have_posts() ) : the_post();?>
         				<article>
         					<header>
								<h3 class='text-center'><?php the_title();?></h3>
							</header>
         					<div class="meta">
								<div class="row">
									<div class="col-lg-12">
										<time class="updated date" datetime="<?php the_time('c'); ?>"><?php the_date(); ?></time>
										<span <?php post_class('type'); ?>><?php _e(get_post_type()); ?></span>
										<div class="social">
											<?php if (function_exists('synved_social_share_markup')) echo synved_social_share_markup(); ?>
										</div>
         							</div>
            					</div>
          					</div>
          					<div class='content'>
	        					<?php the_content();?>
	        				</div>	
         				</article>
            		<?php endwhile;?>
          		<?php endif;?>
         	</div>
		</div>
	</div><!-- End of Main Body Content -->
<?php get_footer();?>	
	
	