<?php
class Modelnotessupport extends Model {

	public function addsupport($data) {
		
		$sql = "INSERT INTO `" . DB_PREFIX . "support` SET facilities_id = '" . $this->customer->getId() . "',user_id = '" . $data['user_id'] . "',support_by = '".$data['support_by']."',comment = '" . $data['comment'] . "',date_added = NOW() ";
		
		$this->db->query($sql);
		$support_id = $this->db->getLastId(); 
		
		$asupport_attachment_images = explode(",",$data['support_attachment_image']);
		
		if($asupport_attachment_images){
			foreach($asupport_attachment_images as $asupport_attachment_image){
				$sql1 = "INSERT INTO `" . DB_PREFIX . "support_attachment` SET support_id = '" . $support_id . "',support_attachment_image = '" . $asupport_attachment_image . "' ";
				$this->db->query($sql1);
			}
		}
		
		return $support_id; 
	}
	
	public function addsupportBySMS($data, $customer_number) {
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "support` WHERE customer_number = '" . $customer_number . "' and ticket_id = '0' ";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
		
			$userId = $data->FName .' '. $data->LName;
			
			$sql = "UPDATE `" . DB_PREFIX . "support` SET user_id = '" . $userId . "',email = '" . $data->Email_add . "', Contact_no = '".$data->Contact_no."', Company_name = '" . $data->Company_name . "', Activitation_key = '" . $data->Activitation_key . "', Address = '" . $data->Address . "', Android_id = '" . $data->Android_id . "', Server_url = '" . $data->Server_url . "', facilities = '" . $data->facilities . "', customer_id = '" . $data->customer_id . "', asset_id = '" . $data->asset_id . "', support_server_id = '" . $data->support_server_id . "', support_by = 'by_sms', customer_number = '" . $customer_number . "', date_added = NOW() where support_id = '".$query->row['support_id']."'  ";
			
			$this->db->query($sql);
			$support_id = $query->row['support_id']; 
		
		}else{
			$userId = $data->FName .' '. $data->LName;
			
			$sql = "INSERT INTO `" . DB_PREFIX . "support` SET user_id = '" . $userId . "',email = '" . $data->Email_add . "', Contact_no = '".$data->Contact_no."', Company_name = '" . $data->Company_name . "', Activitation_key = '" . $data->Activitation_key . "', Address = '" . $data->Address . "', Android_id = '" . $data->Android_id . "', Server_url = '" . $data->Server_url . "', facilities = '" . $data->facilities . "', customer_id = '" . $data->customer_id . "', asset_id = '" . $data->asset_id . "', support_server_id = '" . $data->support_server_id . "', support_by = 'by_sms', customer_number = '" . $customer_number . "', date_added = NOW() ";
			
			$this->db->query($sql);
			$support_id = $this->db->getLastId(); 
			
		}
		
		return $support_id; 
	}
	
	public function getsupport($support_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "support` WHERE support_id = '" . (int)$support_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function getsupportbynumber($customer_number){
		$sql = "SELECT * FROM `" . DB_PREFIX . "support` WHERE customer_number = '" . $customer_number . "' and ticket_id != '0'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function updatesupport($data) {
		$sql = "UPDATE `" . DB_PREFIX . "support` SET comment = '" . $data['notes_description'] . "', subject = '" . $this->db->escape($data['subject']) . "' WHERE support_id = '" . $data['support_id'] . "'";
		$this->db->query($sql);
	}
	
	public function updatesupportTicket($data, $ticket_id) {
		$sql = "UPDATE `" . DB_PREFIX . "support` SET ticket_id = '" . $this->db->escape($ticket_id) . "' WHERE support_id = '" . $data['support_id'] . "'";
		$this->db->query($sql);
	}
	public function addalernatesupportEmail($alertnate_email, $support_id) {
		$sql = "UPDATE `" . DB_PREFIX . "support` SET alertnate_email = '" . $this->db->escape($alertnate_email) . "' WHERE support_id = '" . $support_id . "'";
		$this->db->query($sql);
	}
	
	public function deletesupportHistory($support_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "support_history WHERE support_id = '" . (int)$support_id . "'");
	}
	
	public function getLicenceDetailBykey($notes_description){
		
		$url = LICENCE_URL;
		$ch = curl_init($url); 
		$fields = array(
			'customer_id' => $notes_description,
			'type' =>'getdetailBycustomerid'
		);

		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		curl_setopt($ch,CURLOPT_HEADER, false); 
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

		$response = curl_exec($ch);
		curl_close($ch);
						
		$sresults = json_decode($response);
		return $sresults;
	}
	
	public function getfaqs($questions){
		
		$sql = "select DISTINCT n.* from `" . DB_PREFIX . "support_questions` n ";
		
		$sql .= ' where 1 = 1 ';
		$sql .= " and n.status = '1' ";
		
		$sql .= " and ( LOWER(n.questions) like '%".strtolower($questions)."%') ";
		
		$query = $this->db->query($sql);
		
		return $query->row;
		
	}
	
	public function addsupportHistory($data, $support_id) {
		
		$sql = "INSERT INTO `" . DB_PREFIX . "support_history` SET comment = '" . $data['questions'] . "', support_id = '" . $support_id . "', date_added = NOW() ";
		
		$this->db->query($sql); 
		$support_history_id = $this->db->getLastId(); 
		
		return $support_history_id; 
	}
	
	
	public function getsupportHistories($support_id){
		$sql = "select * from `" . DB_PREFIX . "support_history` ";
		
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and support_id = '".$support_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function addsupportticket($sdata){
		
		$url = 'http://livelogbook.com/support/createticket.php';
		$ch = curl_init($url);
		$fields = array(
			'message' =>$sdata['notes_description'],
			'type' =>'insertsupport',
			'topic_id' => '1',
			'a' => 'open',
			'subject' => $sdata['subject'],
			'user_id' => '5',
			'username' => 'FNYFS',
			'email' => 'fnyfssupport@noteactive.com',
			/*'user_id' => $sdata['support_id'],
			'username' => $sdata['user_id'],
			'email' => $sdata['email'],*/
		);
						
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		curl_setopt($ch,CURLOPT_HEADER, false); 
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

		$response = curl_exec($ch);
		curl_close($ch);
		$results = json_decode($response);	
		
		return $results;		
		
	}
	
}