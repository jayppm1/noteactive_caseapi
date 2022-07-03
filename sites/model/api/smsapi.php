<?php
class Modelapismsapi extends Model {
	
	
	public function sendsms($smsdata) {
		
		//require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		
			$ajson = $this->smsapi->send($smsdata);
			$this->load->model('activity/activity');
			$data['from'] = $smsdata['phone_number'];
			$data['to'] = $ajson['sms_number'];
			$data['response'] = $ajson['response'];
			$data['message'] = $smsdata['message'] . $sserrorm;
			$this->model_activity_activity->addActivitySave('sendsms', $data, 'query');	
		
			/*
			$hostname = "localhost";
			$username = "digitalnotebook";
			$password = "16SxBi+3TMfV";
			$dbname = "digitalnotebook";
			
			$connection = mysql_connect($hostname, $username, $password);
			//var_dump($connection);
			mysql_error();
			mysql_select_db($dbname, $connection);
			

			$query = "INSERT INTO `nexmosms`(`msisdn`, `to`, `messageId`, `text`, `type`, `timestamp`) VALUES ('".$response->sid."','".$response->to."','".$response->sid."','".$response->body."','".$response->from."',NOW()) ";
			$result = mysql_query($query);
			
			$message = "Task Created\n";
			$message .= date('h:i A',strtotime($tasksTiming))."\n";
			$message .= $data['tasktype']."\n";
			$message .= $data['description'];
			
			$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
				[
				  'api_key' =>  '3d77d86f',
				  'api_secret' => 'ec615e849045a195',
				  'to' => $phone_number,
				  'from' => '13233209334',
				  'text' => $message
				]
			);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			
			
		*/
	}
	
}