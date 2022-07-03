<?php  
class Controllersyndblicenceuserid extends Controller {
	
	public function index() {
		
		 if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$start = ($page - 1) * 100;
        $limit = 100;
		
		$sqltawr = "SELECT * FROM `" . DB_PREFIX . "user`";

		$sqltawr .= 'where 1 = 1 ';
		$sqltawr .= " LIMIT " . (int)$start . "," . (int)$limit;
			
		echo $sqltawr;
		
		$qttwr = $this->db->query($sqltawr);
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$configUrl = $this->config->get('config_ssl');
		} else {
			$configUrl = HTTP_SERVER;
		}
		
		if($qttwr->num_rows > 0){
			
			foreach($qttwr->rows as $user){
				
				$url =LICENCE_URL;
				$ch = curl_init($url);
				$fields = array(
				'activationkey' => $user['activationKey'],
				'username' => $user['username'],
				'user_id' => $user['user_id'],
				'type' =>'webupdateuserid',
				'url' => $configUrl
				); 
				var_dump($fields);
				
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
				
				var_dump($results);
				echo "<hr>";
			}
			
		}
		echo "ALL UPDATED";
	}
	
}