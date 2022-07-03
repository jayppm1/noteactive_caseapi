<?php
class ModelFormForm extends Model {
	
	public function getFormdata($form_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $form_id . "' ");
	return $query->row;
	}
	
	public function addFormdata($formdata, $data2) {
		
		$value = '';
		foreach($formdata['design_forms'] as $newdata){
			
			if (is_array($newdata)){
				foreach($newdata as $b){
					
					$value .= $b;
					if($b){
					$value .= ' ';	
					}
				}
			}else{
				$value .= $newdata;
				
				if($newdata){
					$value .= ' ';		
				}
			}
		}
		
		$form_data = $this->getFormdata($data2['forms_design_id']);
		
		$form_name = $form_data['form_name'];
		
		$sql = "INSERT INTO " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape(serialize($formdata['design_forms'])) . "',form_description = '" . $value . "', notes_id = '" . $data2['notes_id'] . "', facilities_id = '" . $data2['facilities_id'] . "', form_type = '3', custom_form_type = '".$data2['forms_design_id']."', upload_file = '".$formdata['upload_file']."', form_signature = '".$formdata['form_signature']."', incident_number='".$form_name."', date_added = NOW(), date_updated = NOW() ";
		
		$this->db->query($sql); 
		return $this->db->getLastId(); 
	}
	
	public function editFormdata($formdata, $forms_id, $upload_file) {
		$value = '';
		
		foreach($formdata as $newdata){
			
			if (is_array($newdata)){
				foreach($newdata as $b){
					
					$value .= $b;
					if($b){
					$value .= ' ';	
					}
				}
			}else{
				$value .= $newdata;
				
				if($newdata){
					$value .= ' ';		
				}
			}
		}
		
		
		$sql = "Update ". DB_PREFIX . "forms SET design_forms = '" . $this->db->escape(serialize($formdata)) . "',form_description = '" . $value . "' WHERE forms_id = '" . $forms_id . "' ";
		
		$this->db->query($sql);
		
		
		if($upload_file){
			$sql2 = "Update ". DB_PREFIX . "forms SET upload_file = '" . $this->db->escape($upload_file) . "' WHERE forms_id = '" . $forms_id . "' ";
			$this->db->query($sql2);
		}
	}
	
	
	
	public function updatenote($notes_id, $formreturn_id){
		$sql = $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET notes_id = '".$notes_id."' where  forms_id = '".$formreturn_id."'");
		
		$form_info = $this->getFormDatas($formreturn_id);
		$formdesign_info = $this->getFormDatadesign($form_info['custom_form_type']);
		$relation_keyword_id = $formdesign_info['relation_keyword_id'];
				
					
		if($relation_keyword_id){
			$this->load->model('notes/notes');
			$noteDetails = $this->model_notes_notes->getnotes($notes_id);
						
			$this->load->model('setting/keywords');
			$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
						
			$data3 = array();
			$data3['keyword_file'] = $keyword_info['keyword_image'];
			$data3['notes_description'] = $noteDetails['notes_description'];
						
			$this->model_notes_notes->addactiveNote($data3, $notes_id);
		}
	}
	
	
	public function getFormDatadesign($forms_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $forms_id . "' ");
		return $query->row;
	}
	
	
	public function getFormDatas($forms_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms WHERE forms_id = '" . $forms_id . "' ");
		return $query->row;
	}
	
	public function getforms($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "forms_design";
		
		$sql .= " where 1 = 1 and status = '1' ";
		if ($data['sort']){
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}
		
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getFormwithNotes($notes_id, $custom_form_type){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms WHERE notes_id = '" . $notes_id . "' and custom_form_type = '".$custom_form_type."' ");
		return $query->row;
	}
}