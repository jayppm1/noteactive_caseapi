<?php
class Modelinventoryinventorytype extends Model {
	
	public function addinventorytype($data) {
		$facilitiesid = implode(',',$data['facility']);
		
		$sql = "INSERT INTO " . DB_PREFIX . "inventorytype SET name = '" . $this->db->escape($data['name']) . "',status = '" . $this->db->escape($data['status']) . "', customer_key = '" . $this->session->data['customer_key'] . "',  facilities_id = '" . $facilitiesid . "', date_added = NOW(),date_updated = NOW() ";
		$this->db->query($sql);
	}
	
	public function editinventorytype($inventorytype_id, $data) {
		
		$facilitiesid = implode(',',$data['facility']);
		
		$this->db->query("UPDATE " . DB_PREFIX . "inventorytype SET name = '" . $this->db->escape($data['name']) . "',status = '" . $this->db->escape($data['status']) . "',date_updated = NOW(),  facilities_id = '" . $facilitiesid . "'  WHERE inventorytype_id = '" . (int)$inventorytype_id . "'");
	}
	
	public function deleteinventorytype($inventorytype_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "inventorytype WHERE inventorytype_id = '" . (int)$inventorytype_id . "'");
	}

	
	public function getinventorytype($inventorytype_id) {
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "inventorytype WHERE inventorytype_id = '" . (int)$inventorytype_id . "'");		
		//return $query->row;
		
		$sql="CALL usp_getInvTypeByInvTypeId('" . (int)$inventorytype_id . "')";
		$query = $this->db->query($sql);
		
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "inventorytype WHERE inventorytype_id = '" . (int)$inventorytype_id . "'");		
		return $query->row;
	}
	
	public function getinventorys($data = array()) {

      
		$sql = "SELECT * FROM " . DB_PREFIX . "inventorytype";
		
		$sql.= " where 1 = 1 and status =1  ";
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities_id) ";
		}
		
		$sql .= " ORDER BY inventorytype_id";	
			
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
	
	public function getTotalinventorys($data = array()) {
		$sql = " where 1 = 1 and status =1  ";
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities_id) ";
		}
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "inventorytype ".$sql." ");
		
		return $query->row['total'];
	}	

   public function getInventoryTypes(){
      	$tablefields= array();
   	   	$query = $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".DB_DATABASE."' AND `TABLE_NAME`='". DB_PREFIX ."inventorytype' AND `COLUMN_NAME` IN ('name','inventorytype_id','status','date_added','date_updated','	nfc_location_tag','	nfc_tag_required','location_address','latitude','longitude',
   	   		'other_type_id','customer_key','maintenance','description','facilities_id','quantity') ORDER BY `COLUMN_NAME`" );
   	   	foreach($query->rows as $values){
			$tablefields[] = array(
			'title_en' => $values['COLUMN_NAME'],
			'key' => $values['COLUMN_NAME'],
			);
		}
		return $tablefields;
   }

}
?>