<?php
class ModelFormLinkedform extends Model {
	
	public function getFormdata($data,$forms_design_id) {
		
		
		$query = $this->db->query("SELECT forms_id,form_name,forms_fields,form_layout,forms_setting,display_image,display_signature,display_add_row,display_content_postion,form_image,relation_keyword_id,facilities,parent_id,page_number,is_client_active,form_type FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $forms_design_id . "' ");
		
		$form_data = array(); 
		
		
		if ($query->num_rows) {
			$result = $query->row;
			$fields = unserialize($result['forms_fields']);
			$sort_col = array();
			$link_form_fieldall = array(); 
			
			foreach ($fields as $key=> $row) {
				$sort_col[$key] = $row['forms_section_sort_order'];
				
				foreach($row['formfields'] as $aaa){
					//var_dump($aaa['link_form_field']);
					if($aaa['link_form'] == '1'){
						$link_form_fieldall[$aaa['key']] = $aaa['link_form_field'];
					}
				}
				
				//var_dump($row['formfields']);
				//echo "<hr>";
				$sort_colf = array();
				foreach($row['formfields'] as $key1=> $rowfield){
					$sort_colf[$key1] = $rowfield['sort_order'];
				}
				
				/*var_dump($sort_colf);
				echo "<hr>";
				var_dump($row['formfields']);
				echo "<hr>";
				*/
				array_multisort($sort_colf, SORT_ASC, $row['formfields']);
				
			}
			array_multisort($sort_col, SORT_ASC, $fields);
			
			
			
			
			
			$form_data = array( 
				'forms_id'      => $result['forms_id'],
				'display_image'      => $result['display_image'],
				'form_type'      => $result['form_type'],
				'is_final'      => $result['is_final'],
				'display_signature'      => $result['display_signature'],
				'forms_setting'      => $result['forms_setting'],
				'form_name'      => $result['form_name'],
				'display_add_row'      => $result['display_add_row'],
				'display_content_postion'      => $result['display_content_postion'],
				'display_observation'      => $result['display_observation'],
				'exiting_client'      => $result['exiting_client'],
				'relation_keyword_id'      => $result['relation_keyword_id'],
				'form_image'      => $result['form_image'],
				'parent_id'      => $result['parent_id'],
				'page_number'      => $result['page_number'],
				'is_client_active'      => $result['is_client_active'],
				'forms_fields'      => $fields,
				'link_form_fieldall'      => $link_form_fieldall,
			);
				
			//echo "<hr>";
			//var_dump($link_form_fieldall);
			//echo "<hr>";
			//var_dump($form_data);
			
			return $form_data;
		} else {
			return false;	
		}
		
		return $form_data;
		
		
	}
	

	

}