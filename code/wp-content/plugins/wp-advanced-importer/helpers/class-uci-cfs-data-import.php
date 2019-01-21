<?php
class WpXMLSmackUCICFSDataImport {

	public function push_cfs_data($data_to_import) {
		global $xml_uci_admin;
		$cfsdata = $data_to_import;
		$data_array = $cfsdata['CFS'];
		if(!empty($cfsdata)) {
			if(in_array('custom-field-suite/cfs.php', $xml_uci_admin->get_active_plugins()) )  {
				$this->importDataCFSFields($data_array, $xml_uci_admin->getImportAs(), $xml_uci_admin->getLastImportId());
			}
		}
	}

	public function importDataCFSFields ($data_array, $importas, $pID) {
		global $xml_uci_admin;
		global $wpdb;
		$cfs_data = $xml_uci_admin->CFSFields();
		foreach ($data_array as $dkey => $dvalue) {
			if(array_key_exists($dkey,$cfs_data['CFS'])){
				if($cfs_data['CFS'][$dkey]['type'] == 'hyperlink'){
					$linksfields = explode('|', $dvalue);
					$linksarr['url'] = $linksfields[0];
					$linksarr['text'] = $linksfields[1];
					$linksarr['target'] = $linksfields[2];
					$darray[$cfs_data['CFS'][$dkey]['name']] = $linksarr;
				}
				elseif($cfs_data['CFS'][$dkey]['type'] == 'file'){
					$darray[$cfs_data['CFS'][$dkey]['name']] = $xml_uci_admin->set_featureimage($dvalue, $pID);
				}elseif($cfs_data['CFS'][$dkey]['type'] == 'select'){
					if( strpos($dvalue, ',') !== false )
					{
						$multifields = explode(',', $dvalue);
						foreach($multifields as $mk => $mv){
							$meta_id = add_post_meta($pID, $cfs_data['CFS'][$dkey]['name'], $mv);
							$this->insert_cfs_values($cfs_data,$pID,$meta_id,$cfs_data['CFS'][$dkey]['name']);
						}
					}else{
						$darray[$cfs_data['CFS'][$dkey]['name']] = $dvalue;
					}
				}elseif($cfs_data['CFS'][$dkey]['type'] == 'relationship'){
					$relations = explode(',', $dvalue);
					foreach($relations as $rk => $rv){
						$relationid = $wpdb->get_col($wpdb->prepare("select ID from $wpdb->posts where post_title = %s and post_type != %s",$rv,'revision'));
						$meta_id = add_post_meta($pID, $cfs_data['CFS'][$dkey]['name'], $relationid[0]);
						$this->insert_cfs_values($cfs_data,$pID,$meta_id,$cfs_data['CFS'][$dkey]['name']);
					}
				}
				elseif($cfs_data['CFS'][$dkey]['type'] == 'term'){
					$relationterms = explode(',', $dvalue);
					foreach($relationterms as $rtk => $rtv){
						$termid = $xml_uci_admin->get_requested_term_details($pID, $rtv);
						$meta_id = add_post_meta($pID, $cfs_data['CFS'][$dkey]['name'], $termid);
						$this->insert_cfs_values($cfs_data,$pID,$meta_id,$cfs_data['CFS'][$dkey]['name']);
					}
				}elseif($cfs_data['CFS'][$dkey]['type'] == 'user'){
					$users = explode(',', $dvalue);
					foreach($users as $uk => $uv){
						$userdata = $xml_uci_admin->get_from_user_details($uv);
						$meta_id = add_post_meta($pID, $cfs_data['CFS'][$dkey]['name'], $userdata['user_id']);
						$this->insert_cfs_values($cfs_data,$pID,$meta_id,$cfs_data['CFS'][$dkey]['name']);
					}
				}
				else{
					$darray[$dkey] = $dvalue;
				}
			}
		}
		if($darray){
			foreach($darray as $mkey => $mval){
				$metaid = update_post_meta($pID, $mkey, $mval);
				$this->insert_cfs_values($cfs_data,$pID,$metaid,$mkey);
			}
		}

	}

	public function insert_cfs_values($cfs_data,$pID,$metaid,$mkey) {
		global $wpdb;
		$wpdb->insert($wpdb->prefix.'cfs_values',
			array('field_id' => $cfs_data['CFS'][$mkey]['fieldid'],
			      'meta_id' => $metaid,
			      'post_id' => $pID,
			),
			array('%s','%s','%s')
		);
	}
}
global $cfsHelper;
$cfsHelper = new WpXMLSmackUCICFSDataImport();