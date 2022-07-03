<?php
class Modelsettingkeywords extends Model {
	
	public function getkeywords($data = array()) {
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			
			$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword where status = '1' ";
			
			$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
			
			 if ($data['monitor_time'] == "6") {
				$sql .= " and monitor_time = '0' ";
			}
			
			if ($data['monitor_time'] == "1") {
				//$sql .= " and (monitor_time = '0') ";
				$sql .= " and (monitor_time = '0' or monitor_time = '4' or monitor_time = '11' ) ";
			}
			
			if ($data['monitor_time'] == "2") {
				$sql .= " and (monitor_time = '1' or monitor_time = '2' or monitor_time = '3' or monitor_time = '7' or monitor_time = '8' or monitor_time = '9' or monitor_time = '10') ";
				//$sql .= " and monitor_time != '0' and monitor_time != '2' ";
			}
			
			$sql .= " and display_activenote = '0' ";
			
			if ($data['sort']){
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY keyword_name";	
			}
			//$sql .= " ORDER BY sort_order";	
				
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			//$sql .= " limit 0, 10";
			
			//echo $sql;
			//echo "<hr>";
			$query = $this->db->query($sql);
			return $query->rows;
			
			/*$cacheid = $data['facilities_id'].'.getkeywords';
			
			$this->load->model('api/cache');
			$rkeywords = $this->model_api_cache->getcache($cacheid);
			
			if (!$rkeywords) {
				$query = $this->db->query($sql);
				$rkeywords = $query->rows;
				$this->model_api_cache->setcache($cacheid,$rkeywords);
			}
		
			return $rkeywords;
			*/
		}else{
			return "";
		}
	}
	
	public function getkeyword($data) {
		$data = trim($data);
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword where keyword_name LIKE '%".$this->db->escape($data)."%' ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordByTag($data) {
		//$data = trim($data);
		/*$datas = explode(" ",$data);
		$data2 = " ";
		$i= 0;
		foreach($datas as $data1){
			/*if($i == 0){
				$data2 .= ' AND ';
			}*/
			/*if($i != 0){
				$data2 .= ' OR ';
			}
			$data2 .= "active_tag = '".$data1."'";
			
		$i++;
		}*/
		
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword where active_tag = '".$this->db->escape($data)."' ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	
	public function getkeywordDetail($keyword_id) {
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$keyword_id . "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordDetaildesc($keyword_image, $facilities_id) {
		
		$sql = "";	
		if ($facilities_id != "" && $facilities_id != null) {
			$sql .= " and FIND_IN_SET('".$facilities_id."',facilities_id) ";
		}
			
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . trim($keyword_image) . "' ".$sql." ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordsbyhashtag($active_tag, $facilities_id) {
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword where (active_tag = '".$this->db->escape($active_tag)."' or relation_hastag = '" . $this->db->escape($active_tag) . "') and FIND_IN_SET('".$facilities_id."',facilities_id) ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordDetailbyid($keyword_id, $facilities_id) {
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . $keyword_id . "' and FIND_IN_SET('".$facilities_id."',facilities_id) ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordDetailbyidreview($facilities_id) {
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword WHERE monitor_time = '11' and FIND_IN_SET('".$facilities_id."',facilities_id) ";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getkeywordinNotes($data = array()) {

$sql = "SELECT DISTINCT
nk.keyword_id
FROM
`dg_notes` n
LEFT JOIN dg_forms f
ON f.notes_id = n.notes_id
LEFT JOIN dg_notes_by_task nb
ON nb.notes_id = n.notes_id
LEFT JOIN dg_notes_tags nt
ON nt.notes_id = n.notes_id
LEFT JOIN dg_notes_by_keyword nk
ON nk.notes_id = n.notes_id
WHERE 1 = 1 AND n.status = 1 AND ( nt.tags_id = '".$data['tags_id']."' or nb.tags_id = '".$data['tags_id']."' or FIND_IN_SET(nb.tags_ids,'".$data['tags_id']."') or FIND_IN_SET(nb.out_tags_ids, '".$data['tags_id']."') ) ";


$query = $this->db->query($sql);
return $query->rows;
}

public function getkeywords2($data = array()) {
		
		if ($data['keyword_ids'] != 0) {
			
			$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "notes_by_keyword n LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id where 1 = '1' ";
			
			$sql .= " and nt.tags_id = '". $data['tags_id']."' ";
			
			if ($data['keyword_ids'] != null && $data['keyword_ids'] != "") {
				$sql .= " and n.keyword_id IN (". $data['keyword_ids'].") group by keyword_id";
			}
			
			$query = $this->db->query($sql);
			return $query->rows;
		
		}else{
			return "";
		}
	}
	
	public function getkeywordData($data=array()) {
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status,display_activenote FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'";
		if ($data['monitor_time'] == "6") {
				$sql .= " and monitor_time = '0' ";
			}
			
			if ($data['monitor_time'] == "1") {
				//$sql .= " and (monitor_time = '0') ";
				$sql .= " and (monitor_time = '0' or monitor_time = '4' or monitor_time = '11' ) ";
			}
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
}
?>