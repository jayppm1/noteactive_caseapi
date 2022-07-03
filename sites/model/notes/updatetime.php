<?php
class Modelnotesupdatetime extends Model {

	public function updatetime($data, $data2) {
        
        $timezone_name = $data['facilitytimezone'];
        
        date_default_timezone_set($timezone_name);
        
        $update_date = date('Y-m-d H:i:s', strtotime('now'));
		$date_added = date('Y-m-d H:i:s', strtotime('now'));
           
		if ($data2['facilities_id']) {
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($data2['facilities_id']);
			$unique_id = $facility['customer_key'];
		}
		
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($data['user_id']);
		
		
		
		$notetime1 = date('H:i:s',strtotime($data2['notetime']));
		$notes_pin = $data['notes_pin'];
       
		
		if($data['imgOutput'] != NULL && $data['imgOutput'] != ""){
			$signature = $data['imgOutput'];
		}else{
			$signature = $data['signature'];
		}
		
		$this->load->model ( 'notes/notes' );
		$note_info = $this->model_notes_notes->getnotes ( $data2['notes_id'] );
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_time` SET notes_id = '" . (int) $data2['notes_id'] . "', facilities_id = '" . $data2['facilities_id'] . "', notes_pin = '" . $this->db->escape($notes_pin) . "', user_id = '" . $this->db->escape($user_info['username']) . "', signature = '" . $signature . "', notetime = '" . $notetime1 . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', notes_type = '" . $this->db->escape($data['notes_type']) . "', comments = '" . $this->db->escape($data2['comments']) . "', original_notetime = '" . $note_info['notetime'] . "', unique_id = '" . $this->db->escape($unique_id) . "' ";
	
        $this->db->query($sql);
        $time_id = $this->db->getLastId();
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_conut ='0', notetime = '" . $notetime1 . "', update_date = '" . $date_added . "', form_type = '7' where notes_id = '" . ( int ) $data2['notes_id'] . "' ";
		$this->db->query ( $sql );
		
		/*if($data2['comments'] != null && $data2['comments'] != ""){
			$notes_description = $note_info['notes_description']. ' | '. $data2['comments'];
			$sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape($notes_description) . "' where notes_id = '" . ( int ) $data2['notes_id'] . "' ";
			$this->db->query ( $sql );
		}*/
		
		
		
		return $time_id; 	
	}
	
	public function updateuserpicture($user_file, $time_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_by_time` SET user_file = '" . $this->db->escape ( $user_file ) . "' where time_id = '" . ( int ) $time_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['time_id'] = $time_id;
		$adata ['user_file'] = $user_file;
		$this->model_activity_activity->addActivitySave ( 'updateuserpicture', $adata, 'query' );
	}
	public function updateuserverified($is_user_face, $time_id) {
		$sql = "UPDATE `" . DB_PREFIX . "notes_by_time` SET is_user_face = '" . $this->db->escape ( $is_user_face ) . "' where time_id = '" . ( int ) $time_id . "' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['time_id'] = $time_id;
		$adata ['is_user_face'] = $is_user_face;
		$this->model_activity_activity->addActivitySave ( 'updateuserverified', $adata, 'query' );
	}
	 
}
?>