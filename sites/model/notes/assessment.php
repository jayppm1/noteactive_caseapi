<?php
class Modelnotesassessment extends Model {

	public function getassessments($data = array()) {
		$sql = "SELECT assessment_id,assessment_name,incident_severity,restraint_involved,staff_par_certified,youth_ratio,	investigation_initiated,keyword_search,tigger_type,highlighter_id,color_id,keyword_id,	facilities_id,status,date_added,date_updated FROM `" . DB_PREFIX . "assessment`";

		$sql .= 'where 1 = 1 ';
		
		
		if ($data['keyword_search'] != null && $data['keyword_search'] != "") {
			$sql .= " and keyword_search like '%".$data['keyword_search']."%'";
		}
		
		if ($data['incident_severity'] != null && $data['incident_severity'] != "") {
			$sql .= " and incident_severity = '".$data['incident_severity']."'";
		}
		
		if ($data['restraint_involved'] != null && $data['restraint_involved'] != "") {
			$sql .= " and restraint_involved = '".$data['restraint_involved']."'";
		}
		
		if ($data['staff_par_certified'] != null && $data['staff_par_certified'] != "") {
			$sql .= " and staff_par_certified = '".$data['staff_par_certified']."'";
		}
		
		if ($data['youth_ratio'] != null && $data['youth_ratio'] != "") {
			$sql .= " and youth_ratio = '".$data['youth_ratio']."'";
		}
		
		if ($data['investigation_initiated'] != null && $data['investigation_initiated'] != "") {
			$sql .= " and investigation_initiated = '".$data['investigation_initiated']."'";
		}
		
		$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
		
		$sql .= " and status = '1'";
		
		$query = $this->db->query($sql);

		return $query->row;
	}

	
}
?>