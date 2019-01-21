<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly

class WpXMLSmackUCIAIOSEODataImport {

	public function push_aioseo_data($data_to_import) {
		global $xml_uci_admin;
		$aioseodata = $data_to_import;
		$data_array = $aioseodata['AIOSEO'];
		if( !empty($aioseodata)) {
			if(in_array('all-in-one-seo-pack/all_in_one_seo_pack.php', $xml_uci_admin->get_active_plugins()) || in_array('all-in-one-seo-pack-pro/all_in_one_seo_pack.php', $xml_uci_admin->get_active_plugins())) {//allinseo_customization
				$this->importDataForAIOSEOFields( $data_array, $xml_uci_admin->getImportAs(), $xml_uci_admin->getLastImportId());
			}
		}
	}

	public function importDataForAIOSEOFields ($data_array, $importas,$pID) {
		$createdFields = array();
		foreach($data_array as $dkey => $dvalue) {
			$createdFields[] = $dkey;
		}
		if(isset($data_array['keywords'])) {
			$custom_array['_aioseop_keywords'] = $data_array['keywords'];
		}
		if(isset($data_array['description'])) {
			$custom_array['_aioseop_description'] = $data_array['description'];
		}
		if(isset($data_array['title'])) {
			$custom_array['_aioseop_title'] = $data_array['title'];
		}
		if(isset($data_array['noindex'])) {
			$custom_array['_aioseop_noindex'] = $data_array['noindex'];
		}
		if(isset($data_array['nofollow'])) {
			$custom_array['_aioseop_nofollow'] = $data_array['nofollow'];
		}
		if(isset($data_array['custom_link'])) {
			$custom_array['_aioseop_custom_link'] = $data_array['custom_link'];
		}
		if(isset($data_array['noodp'])) {
			$custom_array['_aioseop_noodp'] = $data_array['noodp'];
		}
		if(isset($data_array['noydir'])) {
			$custom_array['_aioseop_noydir'] = $data_array['noydir'];
		}
		if(isset($data_array['titleatr'])) {
			$custom_array['_aioseop_titleatr'] = $data_array['titleatr'];
		}
		if(isset($data_array['menulabel'])) {
			$custom_array['_aioseop_menulabel'] = $data_array['menulabel'];
		}
		if(isset($data_array['disable'])) {
			$custom_array['_aioseop_disable'] = $data_array['disable'];
		}
		if(isset($data_array['disable_analytics'])) {
			$custom_array['_aioseop_disable_analytics'] = $data_array['disable_analytics'];
		}
		if(!empty ($custom_array)) {
			foreach ($custom_array as $custom_key => $custom_value) {
				update_post_meta($pID, $custom_key, $custom_value);
			}
		}
		return $createdFields;
	}
}

global $aioseoHelper;
$aioseoHelper = new WpXMLSmackUCIAIOSEODataImport();
#return new SmackUCIAIOSEODataImport();
