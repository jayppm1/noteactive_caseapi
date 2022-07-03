<?php
class Modelsettinghighlighter extends Model {
	
	
	public function gethighlighters($data = array()) {
		$sql = "SELECT highlighter_id,highlighter_name,highlighter_value,highlighter_icon FROM " . DB_PREFIX . "highlighter where status = '1' ORDER BY highlighter_name ASC";
		
		/*$query = $this->db->query($sql);*/
		
		/*$sql = "SELECT highlighter_id,highlighter_name,highlighter_value,highlighter_icon FROM " . DB_PREFIX . "highlighter where status = '1' ORDER BY highlighter_name ASC";
		
		$query = $this->db->prepare($sql);
		var_dump($query);
		*/
		
		$cacheid = 'gethighlighters';
		$this->load->model('api/cache');
		$rhighlighters = $this->model_api_cache->getcache($cacheid);
		
		if (!$rhighlighters) {
			$query = $this->db->query($sql);
			$rhighlighters = $query->rows;
			$this->model_api_cache->setcache($cacheid,$rhighlighters);
		}
	
		return $rhighlighters;
		
		//return $query->rows;
	}
	
	public function gethighlighter($highlighter_id) {
		$query = $this->db->query("SELECT DISTINCT highlighter_id,highlighter_name,highlighter_value,highlighter_icon FROM " . DB_PREFIX . "highlighter WHERE highlighter_id = '" . (int)$highlighter_id . "'");
		
		return $query->row;
	}
}
?>