<?php
class Modellicencelicence extends Model {
	
	public function insert_activationkey($data) {
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		if ($this->session->data['session_key'] != null && $this->session->data['session_key'] != "") {
			$session_key = $this->session->data['session_key'];
		} else {
			//$session_key = 0;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$data['activationkey'],
		'type' =>'webCheckActivation',
		'url' => $configUrl,
		'session_key' => $session_key
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
	
	public function submit_activation_details($data) {
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
	$url =LICENCE_URL;
		
		$ch = curl_init($url);
		$fields = array(
		'activitation_key' =>$data['activationkey'],
		'type' =>'Update',
		'url' => $configUrl,
		'name' => $data['fname'],
		'lname' => $data['lname'],
		'email' => $data['email'],
		'add' => $data['add'],
		'contact' => $data['contact'],
		'company' => $data['company'],
		'message' => $data['message']
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
		
	
		$results2 = json_decode($response);
		return $results2;
	
		
	}

	
	public function checkuseractivation(){
		
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
			} else {
				$configUrl = HTTP_SERVER;
			}
		
		$url =LICENCE_URL;
			$ch = curl_init($url);
			$fields = array(
			'activationkey' =>$this->session->data['activationkey'],
			'type' => 'webUpdateActivation',
			'url' => $configUrl,
			'session_key' => $this->session->data['session_key']
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
			
		
	}
	
	public function closeuseractivation(){
		
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
			} else {
				$configUrl = HTTP_SERVER;
			}
		
		
			$url =LICENCE_URL;
			$ch = curl_init($url);
			$fields = array(
			'activationkey' =>$this->session->data['activationkey'],
			'type' =>'webUpdateLogoutActivation',
			'url' => $configUrl
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
	}

	
	public function check_licence(){
		
	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'type' =>'webCheckLicence',
		'url' => $configUrl
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
	
	
	public function addfacilityActivation($activationkey){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "facility_licence` set activationkey = '".$activationkey."' "; 
		$this->db->query($sql);
	}
	
	
	public function checkloginlicence(){
		
	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$this->session->data['activationkey'],
		'type' =>'weblogGetLicence',
		'url' => $configUrl,
		'session_key' => $this->session->data['session_key']
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
	
	
	public function webresetactivationkey($activationkey){
		
	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$activationkey,
		'type' =>'reseWebtLicenceKey',
		'url' => $configUrl,
		'session_key' => $this->session->data['session_key']
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
	
	
	public function webgetUseernameactivationkey($activationkey){
		
	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$activationkey,
		'type' =>'getUsernameWebtLicenceKey',
		'url' => $configUrl,
		'session_key' => $this->session->data['session_key']
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
	
	
	public function getfacilitiesOnline() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facility_online` ");
		return $query->rows;
	}
	
	
	public function getfacilitiesOnline2($facilities_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "facility_online`  WHERE facilities_id = '" . (int)$facilities_id . "' and facility_login != '' ";
		$query = $this->db->query($sql);
		return $query->rows;
	} 
	
	public function updateSession($activationkey){
		
	if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$activationkey,
		'type' =>'weblogupdateLicence',
		'url' => $configUrl
		);
		
		//var_dump($fields);
		
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
	
	
	public function webdualCheckActivation($data) {
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		if ($this->session->data['session_key'] != null && $this->session->data['session_key'] != "") {
			$session_key = $this->session->data['session_key'];
		} else {
			//$session_key = 0;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' =>$data['activationkey'],
		'type' =>'webdualCheckActivation',
		'url' => $configUrl,
		'session_key' => $session_key
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
	
	public function addKeyactivation($activationKey, $username, $user_id){
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		$url =LICENCE_URL;
		$ch = curl_init($url);
		$fields = array(
		'activationkey' => $activationKey,
		'username' => $username,
		'user_id' => $user_id,
		'type' =>'webuserAddActivationKey',
		'url' => $configUrl
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
	
	public function checkaccessKey($accesskey){
			if ($this->request->server['HTTPS']) {
				$configUrl = HTTPS_CATALOG;
			} else {
				$configUrl = HTTP_CATALOG;
			}
			$url =LICENCE_URL;
			$ch = curl_init($url);
			$fields = array(
			'activationkey' => $accesskey,
			'type' =>'webcheckKey',
			'url' => $configUrl
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