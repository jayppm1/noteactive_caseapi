<?php
class ModelCaseCategory extends Model {
	
	public function getCasecategory($case_category_id) {
		//$sql1 = "SELECT * FROM `" . DB_PREFIX . "case_category` where case_category_id = '" . $this->db->escape ( $case_category_id ) . "' ";
		
		$casecategorytype=1;
		$sql1="CALL casecategory_getByIdorCustKey('".(int)$casecategorytype."','" . (int)$case_category_id . "')";
		
		$q = $this->db->query ( $sql1 );
		return $q->row;
	}
	
	public function getCase($case_id) {
		//$sql1 = "SELECT * FROM `" . DB_PREFIX . "case` where case_id = '" . $this->db->escape ( $case_id ) . "' ";
		$casetype=1;
		$sql1="CALL case_getByCaseIdorCaseCategoryId('" .$casetype . "','" . (int)$case_id . "')";
		$q = $this->db->query ( $sql1 );
		return $q->row;
	}


	public function getCasebyNotes() {
		//$sql1 = "SELECT * FROM `" . DB_PREFIX . "notes` where case_id = '1' ";
		$case_id='1';
		$sql1="CALL case_getByNotes('" . (int)$case_id . "')";
		$q = $this->db->query ( $sql1 );
		return $q->rows;
	}


	public function getFormcaseId($data = array()) {
		/*$sql = "SELECT * FROM `" . DB_PREFIX . "case`"; 

		$sql .= 'where 1 = 1 ';

		if ($data ['from_id'] != null && $data ['from_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['from_id'] . "', forms) ";
		}
		
		if ($data ['case_id'] != null && $data ['case_id'] != "") {
			$sql .= " and case_id = '".$data ['case_id']."' ";
		}*/
		
		$case_id=$data ['case_id'];
		$form_id=$data ['from_id'];
		
		$sql="CALL case_getFormCaseId('".(int)$case_id."','".$this->db->escape ( $form_id )."')";

		$q = $this->db->query ( $sql );
		return $q->row;
	}

	public function getCaseparent($case_category_id) {
		//$sql1 = "SELECT * FROM `" . DB_PREFIX . "case` where case_category_id = '" . $this->db->escape ( $case_category_id ) . "' ";
		$casetype=2;
		$sql1="CALL case_getByCaseIdorCaseCategoryId('" .$casetype . "','" . (int)$case_category_id . "')" ;
		$q = $this->db->query ( $sql1 );
		return $q->rows;
	}
	
	public function getCasecategorybyuserid($customer_key) {
		//$sql1 = "SELECT * FROM `" . DB_PREFIX . "case_category` where customer_key = '" . $this->db->escape ( $customer_key ) . "' ";
		$casecategorytype=2;
		$sql1="CALL casecategory_getByIdorCustKey('".$casecategorytype."','" . (int)$customer_key . "')";
		$q = $this->db->query ( $sql1 );
		return $q->rows;
	}
	
	public function getCases($tags_id) { 
		//$tags_id = $this->request->get['tags_id'];
		/*$queryString = "SELECT DISTINCT T1.case_number FROM 
		(
		SELECT
		  dg_notes_by_case_file.case_number,
		  SUBSTRING_INDEX(SUBSTRING_INDEX(dg_notes_by_case_file.forms_ids, ',', numbers.n), ',', -1) form_id
		FROM
		  (SELECT 1 n UNION ALL
		   SELECT 2 UNION ALL 
		   SELECT 3 UNION ALL
		   SELECT 4 UNION ALL 
		   SELECT 5 UNION ALL 
		   SELECT 6 UNION ALL 
		   SELECT 7 UNION ALL 
		   SELECT 8 UNION ALL 
		   SELECT 9 UNION ALL 
		   SELECT 10 UNION ALL 
		   SELECT 11 UNION ALL 
		   SELECT 12 UNION ALL 
		   SELECT 13 UNION ALL 
		   SELECT 14 UNION ALL 
		   SELECT 15 UNION ALL 
		   SELECT 16 UNION ALL 
		   SELECT 17 UNION ALL 
		   SELECT 18 UNION ALL 
		   SELECT 19 UNION ALL 
		   SELECT 20) numbers 
		   INNER JOIN dg_notes_by_case_file
		  ON CHAR_LENGTH(dg_notes_by_case_file.forms_ids)
			 -CHAR_LENGTH(REPLACE(dg_notes_by_case_file.forms_ids, ',', ''))>=numbers.n-1
		ORDER BY
		  case_number, n
		)T1,
		(SELECT DISTINCT f.forms_id FROM dg_forms f, dg_notes_tags nt
		 WHERE 1 = 1 AND form_design_parent_id = 0 
		AND nt.notes_id = f.notes_id AND nt.tags_id = $tags_id)T2
		WHERE T1.form_id = T2.forms_id";
		echo $queryString;
		$q = $this->db->query ( $queryString );
		return $q->rows;*/
		//$this->response->setOutput ( json_encode ( $queryke->rows ) );
		
		
		//$queryString = "SELECT DISTINCT case_number,notes_by_case_file_id,case_status FROM dg_notes_by_case_file WHERE 1=1"; 
		
		//$queryString .= " AND find_in_set(".$tags_id.",tags_ids) ";
		
		//$queryString .= " ORDER BY date_added DESC ";

		$queryString = " CALL get_cases(" . $tags_id . ")";
		
		//$q = $this->db->query ( $queryString );
		//echo $queryString;
		$q = $this->db->query ( $queryString );
		return $q->rows;
	}
	
	
}
?>