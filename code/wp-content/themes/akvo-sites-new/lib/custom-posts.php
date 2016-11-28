<?php

	add_action( 'init', 'create_post_type' );
	
	add_action( 'init', 'create_taxonomies' );
	function create_taxonomies(){
		akvo_register_taxonomy('languages', 'Languages', 'Language', array('map', 'media', 'blog', 'news', 'video', 'testimonial'));
		akvo_register_taxonomy('countries', 'Locations', 'Location', array('map', 'media', 'blog', 'news', 'video', 'testimonial'));
		
		/* TYPES */
		akvo_register_taxonomy('types', 'Types', 'Type', array('media'));
		akvo_register_taxonomy('map-types', 'Types', 'Type', array('map'));
		akvo_register_taxonomy('video-types', 'Types', 'Type', array('video'));
		
		/* CATEGORY */
		akvo_register_taxonomy('media-category', 'Categories', 'Category', array('media'));
		akvo_register_taxonomy('map-category', 'Categories', 'Category', array('map'));
		akvo_register_taxonomy('blog-category', 'Categories', 'Category', array('blog'));
		akvo_register_taxonomy('news-category', 'Categories', 'Category', array('news'));
		akvo_register_taxonomy('video-category', 'Categories', 'Category', array('video'));
		akvo_register_taxonomy('testimonial-category', 'Categories', 'Category', array('testimonial'));
	}
	
	function akvo_register_taxonomy($slug, $plural_label, $singular_label, $post_types){
	
		$args = array(
			'labels'                     => array(
				'name'                       => _x( $plural_label, 'Taxonomy General Name', 'text_domain' ),
				'singular_name'              => _x( $singular_label, 'Taxonomy Singular Name', 'text_domain' ),
				'menu_name'                  => __( $plural_label, 'text_domain' ),
				'all_items'                  => __( 'All Items', 'text_domain' ),
				'parent_item'                => __( 'Parent Item', 'text_domain' ),
				'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
				'new_item_name'              => __( 'New Item Name', 'text_domain' ),
				'add_new_item'               => __( 'Add New Item', 'text_domain' ),
				'edit_item'                  => __( 'Edit Item', 'text_domain' ),
				'update_item'                => __( 'Update Item', 'text_domain' ),
				'view_item'                  => __( 'View Item', 'text_domain' ),
				'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
				'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
				'popular_items'              => __( 'Popular Items', 'text_domain' ),
				'search_items'               => __( 'Search Items', 'text_domain' ),
				'not_found'                  => __( 'Not Found', 'text_domain' ),
			),
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy($slug, $post_types, $args );
	}
	
	function akvo_register_post_type($slug, $plural_label, $singular_label, $menu_icon){
		register_post_type($slug,
    		array(
      			'labels' => array(
      							'name' 			=> __( $plural_label ),
      							'singular_name' => __( $singular_label )
      						),
      			'public' => true,
      			'has_archive' => true,
      			'menu_position' => 20,
      			'menu_icon' => $menu_icon,
      			'supports' => array(
        			'title',
	        		'editor',
    	    		'author', 
        			'thumbnail', 
        			'excerpt', 
        			'comments', 
      			),
    		)
  		);
	}
	
	function create_post_type() {
		
		akvo_register_post_type('blog', 'Blog posts', 'Blog post', 'dashicons-calendar-alt');
		akvo_register_post_type('news', 'News', 'News', 'dashicons-format-aside');
		akvo_register_post_type('video', 'Videos', 'Video', 'dashicons-media-video');
		akvo_register_post_type('media', 'Media Library', 'Media Item', 'dashicons-book');
		akvo_register_post_type('testimonial', 'Testimonials', 'Testimonial', 'dashicons-megaphone');
		akvo_register_post_type('map', 'Maps', 'Map', 'dashicons-location-alt');
		
  		register_post_type( 'carousel',
		    array(
		      	'labels' => array(
				'name' => __( 'Carousel' ),
		        'singular_name' => __( 'Carousel slide' )
      		),
		    'public' => true,
		    'has_archive' => false,      
		    'menu_position' => 20,
      		'menu_icon' => 'dashicons-images-alt',
      		'exclude_from_search' => true,
      		'supports' => array(
        		'title',
        		'editor',
        		'thumbnail', 
      		),
    	));

  		register_post_type( 'flow',
    		array(
		    	'labels' => array(
    			'name' => __( 'AKVO Flow' ),
    			'singular_name' => __( 'Flow item' )
    		),
    		'public' => true,
    		'has_archive' => true,
    		'menu_position' => 20,
    		'menu_icon' => 'dashicons-welcome-widgets-menus',
    		'supports' => array(
    			'title',
				'editor',
				'author', 
				'thumbnail', 
				'excerpt', 
      		),
    	));
	}

function convertYoutubeImg($string) {
  return preg_replace(
    "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
    "http://i1.ytimg.com/vi/$2/mqdefault.jpg",
    $string
  );
}

function convertYoutube($string) {
  return preg_replace(
      "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
      "<iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
      $string
    );
}

function truncate($string, $length, $stopanywhere=false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else{
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
}
/*
function blokmaker($cols, $types) {
  $titleAttrs = '';
  if ($types == 'video') {
    $thumb = convertYoutubeImg(get_post_meta( get_the_ID(), '_video_extra_boxes_url', true ));
    $thumb = '<img src="'.$thumb.'">';
  }
  elseif ($types == 'testimonial') {
    if (has_post_thumbnail()) {
      $thumb_id = get_post_thumbnail_id();
      if ($cols == 12) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-xlarge', true);
      if ($cols == 9 || $cols == 8) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-large', true);
      else $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-medium', true);
      //var_dump($thumb_id);
      $thumb = $thumb_url_array[0];
      $thumb = '<img src="'.$thumb.'">';
    }
    else {
      $thumb = convertYoutubeImg(get_post_meta( get_the_ID(), '_video_extra_boxes_url', true ));
      $thumb = '<img src="'.$thumb.'">';
    }
  }
  elseif ($types == 'media') {
    if (has_post_thumbnail()) {
      $thumb_id = get_post_thumbnail_id();
      if ($cols == 12) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-xlarge', true);
      if ($cols == 9 || $cols == 8) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-large', true);
      else $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumb-medium', true);
      //var_dump($thumb_id);
      $thumb = $thumb_url_array[0];
      $thumb = '<img src="'.$thumb.'">';
    }
    else {
      $filename = get_post_meta( get_the_ID(), '_media_lib_file', true );
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      if ($ext == 'pdf') $fa = 'fa-file-pdf-o';
      elseif (in_array($ext, array('doc','docx'), true )) $fa = 'fa-file-word-o';
      elseif (in_array($ext, array('xls','xlsx'), true )) $fa = 'fa-file-excel-o';
      elseif (in_array($ext, array('ppt','pptx'), true )) $fa = 'fa-file-powerpoint-o';
      elseif (in_array($ext, array('zip','rar','7z'), true )) $fa = 'fa-file-archive-o';
      else $fa = 'fa-file-o';
      $thumb = '<div class="icon-wrap"><i class="fa fa-inverse fa-4x '.$fa.'"></i></div>';
    }
  }
  elseif ($types == 'akvopedia') {
    $akvopedia_title_id = 'akvopedia-title-' . get_the_ID();
    $titleAttrs = ' id="' . $akvopedia_title_id . '"';
  }
  else {
    if (has_post_thumbnail()) {
      $thumb_id = get_post_thumbnail_id();
      if ($cols == 12) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
      if ($cols == 9 || $cols == 8) $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
      else $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
      //var_dump($thumb_id);
      $thumb = $thumb_url_array[0];
      $thumb = '<img src="'.$thumb.'">';
    }
    else {
      $thumb = get_template_directory_uri().'/dist/images/placeholder800x400-320x180.jpg';
      $thumb = '<img src="'.$thumb.'">';
    }
  }
  $type = $types;
  if ($types == 'post' || !isset($types)) $type = 'news';
  $title = get_the_title();
  if ($cols == 12) {$size = 'megagroot'; $title = truncate($title,180);}
  elseif ($cols == 9) {$size = 'groot'; $title = truncate($title,130);}
  elseif ($cols == 8) {$size = 'groot'; $title = truncate($title,100);}
  elseif ($cols == 6) {$size = 'middel'; $title = truncate($title,70);}
  elseif ($cols == 4) {$size = 'middel'; $title = truncate($title,40);}
  else {$size = 'klein'; $title = truncate($title,35);}
  
  ?>
  <div class="col-md-<?php echo $cols; ?> eq">
    <div class="box-wrap static <?php echo $size; ?> <?php if(is_front_page()) echo 'home'; ?>">
      <a href="<?php the_permalink(); ?>" class="boxlink"></a>
      <div class="header-wrap">
  <h2<?php echo $titleAttrs; ?>><?php echo $title; ?></h2>
      </div>
      <div <?php post_class('infobar'); ?>>
        <time class="updated date" datetime="<?= get_the_time('c'); ?>"><?= get_the_date(); ?></time>
        <span class="type"><span class="hidden-md"><?php _e($type, 'sage'); ?></span></span>
      </div>
      <?php echo $thumb; ?>
      <div class="excerpt">
        <?php the_advanced_excerpt(); ?>
      </div>
    </div>
  </div>
  <?php
}

function blokmaker_rsr($cols, $type, $title, $text, $date, $thumb, $link) {

  if ($cols == 12) {$title = truncate($title,180); $text = truncate($text,400); }
  elseif ($cols == 9) {$title = truncate($title,130); $text = truncate($text,300); }
  elseif ($cols == 8) {$title = truncate($title,100); $text = truncate($text,260); }
  elseif ($cols == 6) {$title = truncate($title,70); $text = truncate($text,200); }
  elseif ($cols == 4) {$title = truncate($title,40); $text = truncate($text,155); }
  else { $title = truncate($title,35); $text = truncate($text,145); }

  ?>
  <div class="col-md-<?php echo $cols; ?> eq">
    <div class="box-wrap dyno <?php if(is_front_page()) echo 'home'; ?>">
      <a href="<?php echo $link; ?>" class="boxlink" target="_blank"></a>
      <div class="header-wrap">
        <h2><?php echo $title; ?></h2>
      </div>
      <div class="infobar update">
        <time class="updated date" datetime="<?= get_the_time('c'); ?>"><?php echo $date; ?></time>
        <span class="type"><span class="hidden-md"><?php _e($type, 'sage'); ?></span></span>
      </div>
      <div class="thumb-wrapper">
        <img src="<?php echo $thumb; ?>">
      </div>
      <div class="excerpt">
        <?php echo $text; ?>
      </div>
    </div>
  </div>
  <?php

}
*/
add_action( 'after_setup_theme', 'akvo_custom_thumbnail_size' );
function akvo_custom_thumbnail_size(){
    add_image_size( 'thumb-small', 224, 126, true ); // Hard crop to exact dimensions (crops sides or top and bottom)
    add_image_size( 'thumb-medium', 320, 180, true ); 
    add_image_size( 'thumb-large', 640, 360, true );
    add_image_size( 'thumb-xlarge', 960, 540, true );
}

function show_flickr($id,$handle) {
  $output = "<style>.embed-container { position: relative; padding-bottom: 56.25%; padding-top: 30px; height: 0; overflow: hidden; max-width: 100%; height: auto; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style>";
  $output .= "<div class='flickr'><div class='embed-container'><iframe src='https://www.flickr.com/photos/";
  $output .= $handle;
  $output .= "/sets/";
  $output .= $id;
  $output .= "/player/' frameborder='0' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe></div></div>";
  return $output;
}

?>