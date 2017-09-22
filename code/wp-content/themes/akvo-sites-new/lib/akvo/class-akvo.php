<?php

	class Akvo{
		
		public $header_options;
		public $search_flag = true;
		
		public $text_domain = 'sage';
		
		
		function __construct(){
		
			
			$this->init_header_options();
			
			add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_items' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 100 );
			
			add_action( 'init', array( $this, 'custom_posts' ) );
			
			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
			
			add_action( 'excerpt_more', array( $this, 'excerpt_more' ) );
			
			
			$this->add_support_link();
			
			
			
			
		}
		
		function init_header_options(){
			
			// get header options
			$this->header_options = get_option('sage_header_options');
			
			if( ! is_array( $this->header_options ) ){
				
				$this->header_options = array();
				
			}
			
			// get search is enabled/disabled
			if($this->header_options && isset($this->header_options['hide_search']) && $this->header_options['hide_search']){
				$this->search_flag = false;
			}
			
			if($this->header_options && !isset($this->header_options['search_text'])){
				
				$this->header_options['search_text'] = "Search " . get_bloginfo("name");
				
			}
			
		}
		
		function add_support_link(){
			
			/* SUPPORT LINK */
			add_action( 'admin_notices', function(){
				include "templates/support.php";
			} );
			
			/* REMOVE HELP FROM DASHBOARD */
			add_filter( 'contextual_help', function($old_help, $screen_id, $screen){
				$screen->remove_help_tabs();
    			return $old_help;
			}, 999, 3 );
			
			// REMOVE LINKS FROM TOP ADMIN BAR
			add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
				// REMOVE LOGO
				$wp_admin_bar->remove_node( 'wp-logo' );
				$wp_admin_bar->remove_node( 'new-post' );
		
				$wp_admin_bar->add_node(  array(
					'id'    => 'akvo-sites-support',
					'title' => 'Support',
					'href'  => 'http://sitessupport.akvo.org',
					'meta'  => array( 'class' => 'my-toolbar-page' )
				) );
			}, 999 );
			
		}
		
		
		
		
		
		function after_setup_theme(){
		
			/* Make theme available for translation. Community translations can be found at https://github.com/roots/sage-translations */
			load_theme_textdomain($this->text_domain, get_template_directory() . '/lang');
		
			/* Enable plugins to manage the document title. http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag */
			add_theme_support('title-tag');
		
			/* Register wp_nav_menu() menus. http://codex.wordpress.org/Function_Reference/register_nav_menus */
  			register_nav_menus(['primary_navigation' => __('Primary Navigation', $this->text_domain)]);

			/* Add post thumbnails */
			add_theme_support('post-thumbnails');
		
			/* Add post formats */
  			add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

			/* Add HTML5 markup for captions */
			add_theme_support('html5', ['caption', 'comment-form', 'comment-list']);
			
			/* CUSTOM IMAGE SIZES */
			add_image_size( 'thumb-small', 224, 126, true ); // Hard crop to exact dimensions (crops sides or top and bottom)
    		add_image_size( 'thumb-medium', 320, 180, true ); 
    		add_image_size( 'thumb-large', 640, 360, true );
    		add_image_size( 'thumb-xlarge', 960, 540, true );
		}
		
		
		/* CUSTOM POST TYPES AND TAXONOMIES */
		function custom_posts(){
			
			/* LANGUAGE AND COUNTRIES FOR ALL TYPES */
			$this->register_taxonomy('languages', 'Languages', 'Language', array('map', 'media', 'blog', 'news', 'video', 'testimonial'));
			$this->register_taxonomy('countries', 'Locations', 'Location', array('map', 'media', 'blog', 'news', 'video', 'testimonial'));
		
			/* TYPES */
			$this->register_taxonomy('types', 'Types', 'Type', array('media'));
			$this->register_taxonomy('map-types', 'Types', 'Type', array('map'));
			$this->register_taxonomy('video-types', 'Types', 'Type', array('video'));
		
			/* CATEGORY */
			$this->register_taxonomy('media-category', 'Categories', 'Category', array('media'));
			$this->register_taxonomy('map-category', 'Categories', 'Category', array('map'));
			$this->register_taxonomy('blog-category', 'Categories', 'Category', array('blog'));
			$this->register_taxonomy('news-category', 'Categories', 'Category', array('news'));
			$this->register_taxonomy('video-category', 'Categories', 'Category', array('video'));
			$this->register_taxonomy('testimonial-category', 'Categories', 'Category', array('testimonial'));
		
			/* REGISTER POST TYPES */
			$this->register_post_type('blog', 'Blog posts', 'Blog post', 'dashicons-calendar-alt');
			$this->register_post_type('news', 'News', 'News', 'dashicons-format-aside');
			$this->register_post_type('video', 'Videos', 'Video', 'dashicons-media-video');
			$this->register_post_type('media', 'Media Library', 'Media Item', 'dashicons-book');
			$this->register_post_type('testimonial', 'Testimonials', 'Testimonial', 'dashicons-megaphone');
			$this->register_post_type('map', 'Maps', 'Map', 'dashicons-location-alt');
			$this->register_post_type('carousel', 'Carousel', 'Carousel slide', 'dashicons-images-alt', true);
			$this->register_post_type('flow', 'Akvo Flow', 'Flow item', 'dashicons-welcome-widgets-menus');
		}
		
		function load_scripts(){
			
			wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false, null);
  			wp_enqueue_style( 'sage_css', get_template_directory_uri().'/dist/styles/main.css', false, '2.1.2');
  		
			/* SELECTED FONTS FROM THE CUSTOMIZE */
			$font_face = $this->selected_fonts();
			
			/* LIST OF ALL THE FONTS */
			$google_fonts = $this->fonts();
		
			// ENQUEUE FONTS THAT ARE SELECTED
			foreach( $google_fonts as $google_font ){
				if( in_array( $google_font['name'], $font_face ) ){
					wp_enqueue_style( $google_font['slug'], $google_font['url'], false, null);
				}
			}
		
			/* COMMENTS REPLY JS */
			if (is_single() && comments_open() && get_option('thread_comments')) { wp_enqueue_script('comment-reply'); }
		
			wp_enqueue_script('bootstrap_js', get_template_directory_uri().'/dist/scripts/bootstrap.min.js', ['jquery'], '1.0.1', true);
			wp_enqueue_script('akvo_js', get_template_directory_uri().'/dist/scripts/main.js', ['jquery'], "1.0.3", true);
		}
		
		function remove_dashboard_items(){
			global $wp_meta_boxes;
 
	    	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    		unset($wp_meta_boxes['dashboard']['normal']['core']['tribe_dashboard_widget']);
		}
		
		function selected_fonts(){
			
			// GET FONTS SELECTED THROUGH CUSTOMIZE
			$custom_akvo_fonts = array(
				'body'	=>  get_theme_mod('akvo_font'),
				'nav'	=> 	get_theme_mod('akvo_font_nav'),
				'head'	=> 	get_theme_mod('akvo_font_head')
			);
		
			$font_face = array();
		
			foreach( $custom_akvo_fonts as $font ){
				// CHECK IF FONT IS EMPTY
				if( count($font) ){
					$font_face[] = $font;
				}
			
			}
		
			// DEFAULT FONT IF NONE IS SELECTED THROUGH CUSTOMIZE
			if( ! count( $font_face ) ){
				$font_face[] = "Open Sans";
			}
			
			return $font_face;
		}
		
		function fonts(){
			
			$fonts_arr = array(
  				array(
  					'slug'	=> 'opensans',
	  				'name'	=> 'Open Sans',
  					'url'	=> '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic'
  				),
  				array(
  					'slug'	=> 'roboto',
	  				'name'	=> 'Roboto',
  					'url'	=> '//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic'
  				),
  				array(
  					'slug'	=> 'lora',
	  				'name'	=> 'Lora',
  					'url'	=> '//fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic'
  				),
  				array(
  					'slug'	=> 'raleway',
	  				'name'	=> 'Raleway',
  					'url'	=> '//fonts.googleapis.com/css?family=Raleway:400,700'
  				),
	  			array(
  					'slug'	=> 'merriweather',
  					'name'	=> 'Merriweather',
  					'url'	=> '//fonts.googleapis.com/css?family=Merriweather:400,400italic,700,700italic'
	  			),
  				array(
  					'slug'	=> 'arvo',
  					'name'	=> 'Arvo',
  					'url'	=> '//fonts.googleapis.com/css?family=Arvo:400,700,400italic,700italic'
	  			),
  				array(
  					'slug'	=> 'muli',
  					'name'	=> 'Muli',
  					'url'	=> '//fonts.googleapis.com/css?family=Muli:400,400italic'
	  			),
  				array(
  					'slug'	=> 'nunito',
  					'name'	=> 'Nunito',
  					'url'	=> '//fonts.googleapis.com/css?family=Nunito:400,700'
  				),
	  			array(
  					'slug'	=> 'alegreya',
  					'name'	=> 'Alegreya',
  					'url'	=> '//fonts.googleapis.com/css?family=Alegreya:400italic,700italic,400,700'
  				),
	  			array(
  					'slug'	=> 'exo2',
  					'name'	=> 'Exo 2',
  					'url'	=> '//fonts.googleapis.com/css?family=Exo+2:400,400italic,700,700italic'
	  			),
  				array(
  					'slug'	=> 'crimson',
  					'name'	=> 'Crimson Text',
  					'url'	=> '//fonts.googleapis.com/css?family=Crimson+Text:400,400italic,700,700italic'
	  			),
  				array(
  					'slug'	=> 'lobster',
  					'name'	=> 'Lobster Two',
  					'url'	=> '//fonts.googleapis.com/css?family=Lobster+Two:400,400italic,700,700italic'
  				),
	  			array(
  					'slug'	=> 'maven',
  					'name'	=> 'Maven Pro',
  					'url'	=> '//fonts.googleapis.com/css?family=Maven+Pro:400,500,700,900'
  				),
	  		);
	  		
	  		$fonts_arr = apply_filters('akvo_fonts', $fonts_arr);
	  		
	  		return $fonts_arr;
	  		
		}
		
		function register_taxonomy($slug, $plural_label, $singular_label, $post_types){
	
			$args = array(
				'labels' => array(
					'name'                       => _x( $plural_label, 'Taxonomy General Name', $this->text_domain ),
					'singular_name'              => _x( $singular_label, 'Taxonomy Singular Name', $this->text_domain ),
					'menu_name'                  => __( $plural_label, $this->text_domain ),
					'all_items'                  => __( 'All Items', $this->text_domain ),
					'parent_item'                => __( 'Parent Item', $this->text_domain ),
					'parent_item_colon'          => __( 'Parent Item:', $this->text_domain ),
					'new_item_name'              => __( 'New Item Name', $this->text_domain ),
					'add_new_item'               => __( 'Add New Item', $this->text_domain ),
					'edit_item'                  => __( 'Edit Item', $this->text_domain ),
					'update_item'                => __( 'Update Item', $this->text_domain ),
					'view_item'                  => __( 'View Item', $this->text_domain ),
					'separate_items_with_commas' => __( 'Separate items with commas', $this->text_domain ),
					'add_or_remove_items'        => __( 'Add or remove items', $this->text_domain ),
					'choose_from_most_used'      => __( 'Choose from the most used', $this->text_domain ),
					'popular_items'              => __( 'Popular Items', $this->text_domain ),
					'search_items'               => __( 'Search Items', $this->text_domain ),
					'not_found'                  => __( 'Not Found', $this->text_domain ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
			);
			register_taxonomy($slug, $post_types, $args );
		}
		
		function register_post_type($slug, $plural_label, $singular_label, $menu_icon, $exclude_from_search = false){
			
			register_post_type($slug, array(
      			'labels' => array('name' => __( $plural_label ), 'singular_name' => __( $singular_label )),
      			'public' 				=> true,
      			'has_archive' 			=> true,
      			'menu_position' 		=> 20,
      			'menu_icon' 			=> $menu_icon,
      			'exclude_from_search' 	=> $exclude_from_search,
      			'supports' 				=> array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    		));
    		
		}
		
		function excerpt_more(){
			return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', $this->text_domain) . '</a>';
		}
		
	}
	
	
	global $akvo;
	
	$akvo = new Akvo;