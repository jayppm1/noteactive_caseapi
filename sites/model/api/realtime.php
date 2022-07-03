<?php
class Modelapirealtime extends Model {
	
	public function addrealtime($realdata) {
		/*if($this->config->get('config_realtime_data') == '1'){
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($realdata['facilities_id']);
			$this->load->model('setting/timezone');
						
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$facilitytimezone = $timezone_info['timezone_value'];
			$timeZone = date_default_timezone_set($timezone_name);
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
			
			$this->load->model('notes/notes');
			$notes_info = $this->model_notes_notes->getnotes($realdata['notes_id']);
				
			
			$this->load->model('setting/highlighter');
			$highlighter_info = $this->model_setting_highlighter->gethighlighter($notes_info['highlighter_id']);
			
			$ttsql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes_by_keyword` where notes_id = '".$realdata['notes_id']."' ";
			$query111 = $this->db->query($ttsql);
			$activenote_total = $query111->row['total'];
			
			if($activenote_total > 0){
			
				$ttsql2 = "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` where notes_id = '".$realdata['notes_id']."' ";
				$query1112 = $this->db->query($ttsql2);
				
				//var_dump($query1112->rows);
				
				$ssbi = array();
				$ssbi2 = array();
				foreach($query1112->rows as $ddkeword){
					$ssbi[] = $ddkeword['keyword_name'];
					$ssbi2[] = $ddkeword['keyword_id'];
				
					$ssbiname = implode(',', $ssbi);
					$ssbiid = implode(',', $ssbi2);
					
					$date_added = date('Y-m-d',strtotime($notes_info['date_added']));
					$notetime = $date_added .' '.$notes_info['notetime'];
					
					if($highlighter_info['highlighter_name']){
						$highlighter_name = $highlighter_info['highlighter_name'];
					}else{
						$highlighter_name = "";
					}
					
					$ddd = array();
					$ddd['notes_id'] = $realdata['notes_id'];
					$ddd['facilities_id'] = $realdata['facilities_id'];
					$ddd['facility_name'] = $facilities_info['facility'];
					$ddd['notes_description'] =  $notes_info['notes_description'];
					 
					$ddd['highlighter_id'] =  $notes_info['highlighter_id'];
					$ddd['highlighter_name'] =  $highlighter_name;
					$ddd['date_added'] = $notes_info['date_added'];
					$ddd['user_id'] = $notes_info['user_id'];
					$ddd['notetime'] = $notetime;
					$ddd['note_date'] = $notes_info['note_date'];
					$ddd['active_notes'] =  $activenote_total;
					$ddd['keyword_names'] =  $ddkeword['keyword_name'];
					$ddd['keyword_ids'] =  $ddkeword['keyword_id'];
					
					
					
					$this->addpowerbinotes($ddd);
				}
			
			}else{
				
				$date_added = date('Y-m-d',strtotime($notes_info['date_added']));
				$notetime = $date_added .' '.$notes_info['notetime'];
				
				if($highlighter_info['highlighter_name']){
					$highlighter_name = $highlighter_info['highlighter_name'];
				}else{
					$highlighter_name = "";
				}
				
				$ddd = array();
				$ddd['notes_id'] = $realdata['notes_id'];
				$ddd['facilities_id'] = $realdata['facilities_id'];
				$ddd['facility_name'] = $facilities_info['facility'];
				$ddd['notes_description'] =  $notes_info['notes_description'];
				 
				$ddd['highlighter_id'] =  $notes_info['highlighter_id'];
				$ddd['highlighter_name'] =  $highlighter_name;
				$ddd['date_added'] = $notes_info['date_added'];
				$ddd['user_id'] = $notes_info['user_id'];
				$ddd['notetime'] = $notetime;
				$ddd['note_date'] = $notes_info['note_date'];
				$ddd['active_notes'] =  '0';
				$ddd['keyword_names'] =  '';
				$ddd['keyword_ids'] = '';
				
				
				
				$this->addpowerbinotes($ddd);
			}
		}*/
	}
	
	
	public function addpowerbinotes($data){
		
		$data2 = array(
			'notes_id' => $data['notes_id'],
			'facilities_id' => $data['facilities_id'],
			'facility_name' => $data['facility_name'],
			'notes_description' => $data['notes_description'],
			'highlighter_id' => $data['highlighter_id'],
			'highlighter_name' => $data['highlighter_name'],
			'date_added' => $data['date_added'],
			'notetime' => $data['notetime'],
			'note_date' => $data['note_date'],
			'user_id' => $data['user_id'],
			'active_notes' => $data['active_notes'],
			'keyword_names' => $data['keyword_names'],
			'keyword_ids' => $data['keyword_ids'],
			'notes_count' =>'1' ,
		);
		
			
		 
		$payload = '['.json_encode($data2).']';
		
		
		//$ch = curl_init('https://api.powerbi.com/beta/6372002c-2860-43a8-b8a2-7fa21b5b117f/datasets/c17ac4ce-27f5-476d-b2d3-3f8c55d0f818/rows?key=1vjWcUmagi78UBujLSuIYgY2rCgmzfaJAFaUp2HpmB8BwWtp3e4pDvGDyAPaiZzPPyWqNsf3KNHLDzrHyxJMRg%3D%3D');
		
		//$ch = curl_init('https://api.powerbi.com/beta/6372002c-2860-43a8-b8a2-7fa21b5b117f/datasets/c8b858d6-0126-46bd-8348-6b923949dc4e/rows?key=d6uMBzr70XSGGHaNsxM3%2BN9olRDs54OFU7P%2B%2FXN7R0EwzGjA6HSdVyV5RcDScL%2B4GFNdIIRQrKKrsF0Hulr%2BIQ%3D%3D');
		$ch = curl_init(REALTIMEURL);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload))
		);
		 
		
		$result = curl_exec($ch);
		 
		return $result;	
	}
	
}
	
	