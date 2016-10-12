<?php

/**
 * @author OnTheGo Systems
 */
class WPML_Menu_Element extends WPML_Term_Element {

	/**
	 * WPML_Menu_Element constructor.
	 *
	 * @param int           $id
	 * @param SitePress     $sitepress
	 * @param WPML_WP_Cache $wpml_cache
	 */
	public function __construct( $id, SitePress $sitepress, WPML_WP_Cache $wpml_cache = null ) {
		$this->taxonomy = 'nav_menu';
		parent::__construct( $id, $sitepress, $this->taxonomy, $wpml_cache );
	}

	/**
	 * @param null|stdClass $element_data null, or a standard object containing at least the `translation_id`, `language_code`, `element_id`, `source_language_code`, `element_type`, and `original` properties.
	 *
	 * @return WPML_Menu_Element
	 * @throws \InvalidArgumentException
	 */
	function get_new_instance( $element_data ) {
		return new WPML_Menu_Element( $element_data->element_id, $this->sitepress, $this->wpml_cache );
	}
}