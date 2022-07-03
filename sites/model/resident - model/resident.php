<?php
class Modelresidentresident extends Model {
	
	public function gettagFormdata($tags_forms_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tags_forms WHERE tags_forms_id = '" . $tags_forms_id . "' ");
		return $query->row;
	}
	
	public function addtagFormdata($formdata, $data2) {
	
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
		
		$sql = "INSERT INTO " . DB_PREFIX . "tags_forms SET design_forms = '" . $this->db->escape(serialize($formdata['design_forms'])) . "',form_description = '" . $value . "', notes_id = '" . $data2['notes_id'] . "', facilities_id = '" . $data2['facilities_id'] . "', type = '3', forms_design_id = '".$data2['forms_design_id']."', tags_id = '".$data2['tags_id']."', upload_file = '".$formdata['upload_file']."', form_signature = '".$formdata['form_signature']."', date_added = NOW(), date_updated = NOW() , status = '1' ";
		
		$this->db->query($sql); 
		return $this->db->getLastId(); 
	}
	
	public function edittagsFormdata($formdata, $tags_forms_id, $upload_file) {
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
		
		
		$sql = "Update ". DB_PREFIX . "tags_forms SET design_forms = '" . $this->db->escape(serialize($formdata)) . "',form_description = '" . $value . "' WHERE tags_forms_id = '" . $tags_forms_id . "' ";
		
		
		$this->db->query($sql);
		
		if($upload_file){
			$sql2 = "Update ". DB_PREFIX . "forms SET upload_file = '" . $this->db->escape($upload_file) . "' WHERE forms_id = '" . $forms_id . "' ";
			$this->db->query($sql2);
		}
	}
	
	
	
	public function updateDischargeTag($tags_id){
		$sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET discharge = '1' where  tags_id = '".$tags_id."'");
	}
	public function updatetagrolecall($tags_id, $role_call){
		$sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET role_call = '".$role_call."' where  tags_id = '".$tags_id."'");
	}
	
	public function updatetagcolor($tags_id, $highliter_id, $text_highliter_div_cl){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tags_color` WHERE color_id = '" . $text_highliter_div_cl . "'");

		if($query->num_rows > 0){
			$sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_color` SET color_id = '#".$highliter_id."',tags_id = '".$tags_id."',date_updated = NOW()  where text_highliter_div_cl= '".$text_highliter_div_cl."' ");
		}else{
			$sql = $this->db->query("INSERT INTO  `" . DB_PREFIX . "tags_color` SET color_id = '#".$highliter_id."', tags_id = '".$tags_id."', text_highliter_div_cl = '".$text_highliter_div_cl."',date_added = NOW(),date_updated = NOW() ");
		}
	}
	
	
	public function getagsColors($tags_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tags_color WHERE tags_id = '" . $tags_id . "' ");
		return $query->rows;
	}
	
	
	public function getFormDatadesign($forms_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "design_forms WHERE forms_id = '" . $forms_id . "' ");
		return $query->row;
	}
	
	
	public function gettagsFormDatas($tags_forms_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tags_forms WHERE tags_forms_id = '" . $tags_forms_id . "' ");
		return $query->row;
	}
	
	function get_formbynotesid($notes_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags_forms` WHERE notes_id = '".$notes_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	
	public function gettagsforms($tags_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tags_forms";
		
		$sql .= " where 1 = 1 and status = '1' and tags_id = '".$tags_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function gettagmedicine($tags_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tags_medication";
		
		$sql .= " where 1 = 1 and status = '1' and tags_id = '".$tags_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function gettagModule($tags_id){
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '".$tags_id."'");
		
		$new_module = array();
		
		if($query->num_rows){
			foreach($query->rows as $rows){
				
				$new_module['new_module'][] = array(
				'tags_medication_details_id' => $rows['tags_medication_details_id'],
				'tags_medication_id' => $rows['tags_medication_id'],
				'drug_name' => $rows['drug_name'],
				'drug_mg' => $rows['drug_mg'],
				'drug_am' => $rows['drug_am'],
				'drug_pm' => $rows['drug_pm'],
				'drug_alertnate' => $rows['drug_alertnate'],
				'drug_prn' => $rows['drug_prn'],
				'instructions' => $rows['instructions'],
				'status' => $rows['status'],
				
				);
			}
		}
		return $new_module;
		
	}
	
	function addTagsMedication($data, $tags_id){
		
		
		$query1 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '".$tags_id."'");
				
		if($query1->num_rows > 0){
			$this->db->query("UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', status = '1' where tags_id = '".$tags_id."' ");
			
			$tags_medication_id = $query1->row['tags_medication_id'];
			
		}else{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', status = '1', tags_id = '" . $tags_id . "'");
			
			$tags_medication_id = $this->db->getLastId(); 
		}
		
		if(empty($this->request->post['medication'])){
			$this->db->query("DELETE FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . (int)$tags_id . "'");
		}
		
		if($data['new_module']){
			foreach($data['new_module'] as $mediactiondata){
				
				$drug_am = date('H:i:s', strtotime($mediactiondata['drug_am']));
				$drug_pm = date('H:i:s', strtotime($mediactiondata['drug_pm']));
				
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '".$mediactiondata['tags_medication_details_id']."'");
				
				if($query->num_rows > 0){
					$this->db->query("UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($mediactiondata['drug_name']) . "', drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) . "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "' where tags_medication_details_id = '".$mediactiondata['tags_medication_details_id']."' ");
				}else{
				
					$this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($mediactiondata['drug_name']) . "', drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) . "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "' ");
				}
			}
		}
		
		if($data['drug_name']){
			
			$drug_am = date('H:i:s', strtotime($data['drug_am']));
			$drug_pm = date('H:i:s', strtotime($data['drug_pm']));
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($data['drug_name']) . "', drug_mg = '" . $this->db->escape($data['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($data['drug_alertnate']) . "', drug_prn = '" . $this->db->escape($data['drug_prn']) . "', instructions = '" . $this->db->escape($data['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "' ");
		
		}
		
		
	}
	
	function get_medication($tags_medication_details_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '".$tags_medication_details_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	function get_medicationyname($drug_name , $tags_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE drug_name = '".$drug_name."' and tags_id = '".$tags_id."' ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
}