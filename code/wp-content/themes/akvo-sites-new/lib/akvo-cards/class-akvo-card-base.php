<?php 
	
	class AKVO_CARD_BASE{
		
		var $shortcode_str;
		var $shortcode_slug;
		var $template;
		
		var $counters;
		
		function __construct(){
			
			/* HANDLE AJAX */
			add_action( "wp_ajax_".$this->shortcode_slug, array( $this, "ajax" ) );
			add_action( "wp_ajax_nopriv_".$this->shortcode_slug, array( $this, "ajax" ) );
			
			/* HANDLE SHORTCODE */
			add_shortcode( $this->shortcode_str, array( $this, 'shortcode' ) );
			
			$this->counters = array();
			
		}
		
		function get_counter( $label ){
			if( !isset($this->counters[$label]) ) { $this->counters[$label] = -1; }	/* reset offset counter if rsr-id or type has changed */
      		$this->counters[$label]++;
			return $this->counters[$label];
		}
		
		/* SHORTCODE FUNCTIONALITY */
		function shortcode( $atts ){}
		
		/* FUNCTION THAT HANDLES YOUR AJAX */
		function ajax(){}
		
		/* ABOVE FUNCTIONS ARE TO BE IMPLEMENTED BY THE CHILD CLASSES */
		
		
		function get_ajax_url($action, $atts, $dont_inc = array()){
			$url = admin_url('admin-ajax.php')."?action=".$action;
			foreach($atts as $key => $val){
				if(!in_array($key, $dont_inc)){
					$url .= "&".$key."=".$val;
				}	
			}
			return $url;
		}
		
		/* SPECIFIC TO RSR PROJECT UPDATES */
		function rsr_updates($atts){
			
			$data = array();
			$jsondata = self::get_json_data($atts['rsr-id']);
			
			$offset = self::get_offset( $atts ); 
			
			for($i = $offset; $i < $offset+$atts['posts_per_page']; $i++){
				
				$temp = self::parse_rsr_updates($jsondata->results[$i]);	/* PARSE JSON */
				$temp = self::add_extra_params($temp, $atts);				/* adding extra params */
				
				array_push($data, $temp);									/* ADD TO FINAL DATA */
			}
			return $data;
		}
		
		/* SPECIFIC TO RSR PROJECT */
		function rsr_project($atts){
			
			$data = array();
			$jsondata = self::get_json_data($atts['rsr-id']);
			
			// SINGULAR DATA
			if( !isset( $jsondata->results ) ){
				
				$temp = self::parse_rsr_project($jsondata);					/* PARSE JSON */
				$temp = self::add_extra_params($temp, $atts);				/* adding extra params */
				
				array_push($data, $temp);									/* ADD TO FINAL DATA */
			}
			else{
				// MULTIPLE VALUES
				$offset = self::get_offset( $atts );
				
				for($i = $offset; $i < $offset+$atts['posts_per_page']; $i++){
					$temp = self::parse_rsr_project($jsondata->results[$i]);
					
					/* adding extra params */
					$temp = self::add_extra_params($temp, $atts);
					
					array_push($data, $temp);
				}
			}
			
			return $data;
		}
		
		function get_base_url(){
			$base_url = 'http://rsr.akvo.org';
			/* from customise theme */
			$akvo_card_options = get_option('akvo_card');
			if($akvo_card_options && array_key_exists('akvoapp', $akvo_card_options)){
				$base_url = $akvo_card_options['akvoapp'];
			}
			return $base_url;
		}
		
		function get_date_format(){ return get_option('date_format'); }
		
		function get_json_data($data_feed_id){
			// Dependancy on the Data Feed Plugin
			$data = do_shortcode('[data_feed name="'.$data_feed_id.'"]');
			return json_decode( str_replace('&quot;', '"', $data) );
			
		}
		
		/* PARSING WP POST - CUSTOM POST TYPE */
		function parse_post($post){
			/* parsing post object */
			$akvo_card = array(
				'title'		=> '',
				'content'	=> '',
				'date'		=> '',
				'img'		=> '',
				'link'		=> '',
				'post_id'	=> '0'
			);
			$akvo_card['post_id'] = $post->ID;
			$akvo_card['img'] = akvo_featured_img($post->ID);
			$akvo_card['title'] = get_the_title($post->ID);
			$akvo_card['date'] = get_the_date(self::get_date_format(), $post->ID);
			$akvo_card['link'] = get_the_permalink($post->ID);
			$akvo_card['content'] = wp_trim_words(get_the_excerpt($post->ID));
			return $akvo_card;	
		}
		
		/* PARSING RSR PROJECT FROM JSON DATA */
		function parse_rsr_project($rsr_obj){
			/* parsing json object of rsr project */
			$akvo_card = array(
				'title'		=> '',
				'content'	=> '',
				'date'		=> '',
				'img'		=> '',
				'link'		=> '',
				'type-text'	=> 'RSR Project'
			);
			
			if(isset($rsr_obj->title)){
				$akvo_card['title'] = $rsr_obj->title;
			}
			
			if(isset($rsr_obj->project_plan_summary)){
				$akvo_card['content'] = truncate($rsr_obj->project_plan_summary, 130);
			}
			
			if(isset($rsr_obj->created_at)){
				$akvo_card['date'] = date(self::get_date_format(), strtotime($rsr_obj->created_at));
			}
			
			if(isset($rsr_obj->absolute_url)){
				$akvo_card['link'] = self::get_base_url().$rsr_obj->absolute_url;	
			}
			
			if(isset($rsr_obj->current_image)){
				$akvo_card['img'] = self::get_base_url().$rsr_obj->current_image;
			}
			
			return $akvo_card;
		}
		
		/* PARSING RSR PROJECT UPDATES FROM JSON DATA */
		function parse_rsr_updates($rsr_obj){
			
			$akvo_card = array(
				'title'		=> '',
				'content'	=> '',
				'date'		=> '',
				'img'		=> '',
				'link'		=> '',
				'type-text'	=> 'RSR Updates'
			);
			
			if($rsr_obj->title){ $akvo_card['title'] = $rsr_obj->title; }
			
			if($rsr_obj->text){ $akvo_card['content'] = truncate($rsr_obj->text, 130); }
			
			if($rsr_obj->created_at){ $akvo_card['date'] = date(self::get_date_format(), strtotime($rsr_obj->created_at));	}
			
			if($rsr_obj->photo){
				if( isset($rsr_obj->photo->original) ){
					$akvo_card['img'] = self::get_base_url().$rsr_obj->photo->original;
				}
				else{
					$akvo_card['img'] = self::get_base_url().$rsr_obj->photo;	
				}
			}
			
			
			if($rsr_obj->absolute_url){ $akvo_card['link'] = self::get_base_url().$rsr_obj->absolute_url; }
			
			return $akvo_card;
		}
		
		function get_types(){
			$post_type_arr = array(
				'news' 			=> 'News',
				'blog' 			=> 'Blog',
				'video' 		=> 'Videos',
				'testimonial' 	=> 'Testimonials',
				'project' 		=> 'RSR Updates',
				'rsr-project'	=> 'RSR Project',
				'map' 			=> 'Maps',
				'flow' 			=> 'Flow',
				'media' 		=> 'Media Library'
			);
			return $post_type_arr;
		}
		
		function form_shortcode($data){
			$shortcode = '['.$this->shortcode_str.' ';
        		
        	foreach($data as $key=>$val){
        		
        		$val = str_replace("[","&#91;",$val);
        		$val = str_replace("]","&#93;",$val);
        			
        		$shortcode .= $key.'="'.$val.'" ';
        	}
        	$shortcode .= ']';
        		
			return $shortcode;
		}
		
		/*
		
		TO BE REMOVED IN THE FUTURE 
			
		function slugify($text){
			
  			$text = preg_replace('~[^\pL\d]+~u', '-', $text);		// replace non letter or digits by - 
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);	// transliterate 
			$text = preg_replace('~[^-\w]+~', '', $text);			// remove unwanted characters 
			$text = trim($text, '-');								// trim	
			$text = preg_replace('~-+~', '-', $text);				// remove duplicate - 
			$text = strtolower($text);								// lowercase 
			
			if (empty($text)) {return 'n-a';}
			return $text;
		}
		*/
		
		/* FOR MEDIA TYPE, ADD EXTRA TYPES FROM TAXONOMY */
		function get_media_term_types( $post_id ){
			$types = array();
			$term_types = get_the_terms( $post_id, 'types' );
			
			if( is_array( $term_types ) && count( $term_types ) ){
				foreach( $term_types as $term_type ){
					array_push( $types, $term_type->name );
				}
			}
			
			return !empty ( implode( ',', $types ) ) ? implode( ',', $types ) : 'media';
		}
		
		/* add extra params from one array to another */
		function add_extra_params($data, $atts, $extras = array('type-text', 'type')){
			
			/* ONLY FOR MEDIA POSTS, GET EXTRA TYPES FROM TAXONOMY */
			if( $atts['template'] == 'list' && $atts['type'] == 'media' ){
				$atts['type'] = self::get_media_term_types( $data['post_id'] );	
			}
			
			foreach($extras as $extra){
				if($atts[$extra]){
					$data[$extra] = $atts[$extra];
				}	
			}
			return $data;
		}	
		
		/* WP QUERY */
		function wp_query($atts){
			$data = array();
			
			$query_atts = array(
				'post_type' 	=> $atts['type'],
        		'posts_per_page' => $atts['posts_per_page'],
        		'offset'	=> self::get_offset( $atts ),
			);
			
			/* TAXONOMY QUERY - CUSTOM TYPES AND TERMS */
			if( isset( $atts['filter_by'] ) ){
				
				$atts['filter_by'] = explode( ':',  $atts['filter_by'] );
				
				if( is_array( $atts['filter_by'] ) && ( count( $atts['filter_by'] ) > 1 ) ){
					
					$query_atts['tax_query'] = array(
						array(
							'taxonomy' => $atts['filter_by'][0],
							'field'    => 'slug',
							'terms'    => $atts['filter_by'][1],
						)
					);
					
				}
				
			}
			
			
			
			$query = new WP_Query( $query_atts );
			
			if ( $query->have_posts() ) { 
				while ( $query->have_posts() ) {
					$query->the_post();
					
          			$temp = self::parse_post($query->post);
          			
          			/* adding extra params */
					$temp = self::add_extra_params($temp, $atts);
					
					array_push($data, $temp);
          		}
				wp_reset_postdata();
			}
			return $data;
		}
		
		/* Iterate through RSR updates */
		function get_offset( $atts ){
			return (((int)$atts['page'] - 1) * (int)$atts['posts_per_page']) + (int)$atts['offset'];
		}
		
		function slugify( $text ){
			
			global $akvo;
			return $akvo->slugify( $text );
			
			
		}
	}