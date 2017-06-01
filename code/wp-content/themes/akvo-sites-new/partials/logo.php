<?php
	$home_url = home_url('/');
						
	if ( is_multisite() ) {
		// should execute only for multisites
		$current_site = get_current_site();
		if( ICL_LANGUAGE_CODE == 'fr' && isset($current_site->domain) && $current_site->domain == "afrialliance.org" ) {
			$home_url = 'http://afrialliance.org/';
		}
	}
?>	
<a class="brand" href="<?php _e($home_url); ?>">
<?php if ( get_theme_mod( 'akvo_logo' ) ) : 
	/* set the image url */
	$image_url = esc_url( get_theme_mod( 'akvo_logo' ) );
	/* store the image ID in a var */
	$image_id = pn_get_attachment_id_from_url($image_url);
							
	$akvo_logo_size = get_theme_mod( 'akvo_logo_size' ) ? 'large' : 'medium';
							
    /* retrieve the thumbnail size of our image */
	$image_thumb = wp_get_attachment_image_src($image_id, $akvo_logo_size);
?>
	<img src='<?php echo $image_thumb[0]; ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
<?php else : ?>
	<img src="<?php bloginfo('template_url'); ?>/dist/images/logo-sample.svg">
<?php endif; ?>
	<p><?php bloginfo('description');?></p>
</a>