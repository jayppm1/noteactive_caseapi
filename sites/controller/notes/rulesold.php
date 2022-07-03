<?php  
class Controllernotesrules extends Controller {  
	private $error = array();
   
  	
	public function sendSMS($results) {
		foreach($results as $result){
			//var_dump($result);
			//echo "<hr>";
			
			if($result['phone_number'] != null && $result['phone_number'] != '0'){
				$message = "Rules Created\n";
				$message .= $result['notetime']."\n";
				$message .= $ruleName .'-'.$ruleType.'-'.$rulevalue."\n";
				$message .= $result['notes_description'];
				
				
				require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

				$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
				$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
				$client = new Services_Twilio($account_sid, $auth_token); 

 
				$number = '+'.$result['phone_number'];
				$text = $message;
				$from = "+".$result['sms_number']; 
				
				$response = $client->account->sms_messages->create($from,$number,$text);
				
				/*$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
					[
						'api_key' =>  '3d77d86f',
						'api_secret' => 'ec615e849045a195',
						'to' => $result['phone_number'],
						'from' => $result['sms_number'],
						'text' => $message
					]
					);
						 
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				//var_dump($response);
				*/
			}
		}
	}
	
	public function sendEmail($results, $ruleName, $ruleType, $rulevalue) {
		
		if($this->config->get('config_mail_protocol')  == 'smtp'){				
					
			require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
			$mail = new PHPMailer;
						 
			$mail->Host = $this->config->get('config_smtp_host');   
						
			if($this->config->get('config_smtp_auth') == '1'){
				$mail->SMTPAuth = true;                           
			}
						
			$mail->Username = $this->config->get('config_smtp_username');        
			$mail->Password = $this->config->get('config_smtp_password');               
						
			if($this->config->get('config_smtp_ssl') == '1'){
				$mail->SMTPSecure = 'tls';                    
			}
						
			$mail->Port = $this->config->get('config_smtp_port');                            
			$mail->setFrom('support@noteactive.com', $this->config->get('config_name'));  
			$mail->addReplyTo('support@noteactive.com', $this->config->get('config_name'));  
		}
		
		//var_dump($mail);
		//echo "<hr>";
		//var_dump($results);
		
		foreach($results as $result){
			
			$message33 = "";
			$message33 .= $this->emailtemplate($result, $ruleName, $ruleType, $rulevalue,"7",$user_roles,$user_ids);
			
			//var_dump($message33);	 
			if($this->config->get('config_mail_protocol')  == 'smtp'){				
				
				//$mail->addAddress($result['email']); 
				$mail->addAddress('app-monitoring@noteactive.com'); 
				
				$mail->WordWrap = 50;                               
				$mail->isHTML(true);                       
						 
				$mail->Subject = 'This is an Automated Alert Email.';
						 
				$mail->msgHTML($message33);
				$mail->send();
				
				/*if(!$mail->send()) {
					echo 'Task Created';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
					exit;
				}
						 
				echo 'Message has been sent';
				die;
				*/
			
			}
		}
		
		
	}
	
	public function sendNotification($results) {
		
		
		if($results != null && $results != ""){
			$this->load->model('setting/highlighter');
			$this->load->model('facilities/facilities');
			$this->load->model('user/user');
			
			foreach($results as $result){
				//var_dump($result);
				//echo "<hr>";
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$facilities_info = $this->model_facilities_facilities->getfacilities($result['facilities_id']);
				//$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
				$user_info = $this->model_user_user->getUserByUsernamebynotes($result['user_id'], $result['facilities_id']);
								
				$json['rulenotes'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighterData['highlighter_value'],
					'notes_description'   => $result['notes_description'],
					'date_added' => date('j, F Y', strtotime($result['date_added'])),
					'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
					'notetime'   => date('h:i A', strtotime($result['notetime'])),
					'username'      => $result['user_id'],
					'email'      => $user_info['email'],
					'facility'     => $facilities_info['facility'],
				);
			}
			//var_dump($json);
			$json['total'] = '1'; 
		}else{ 
			$json['total'] = '0';
		}
		 
		//var_dump($json);
		
		
		$this->response->setOutput(json_encode($json));
	}

	public function emailtemplate($result, $ruleName, $ruleType, $rulevalue,$type,$user_roles=array(),$user_ids=array()){

     if($type != null && $type !=""){

     	 $edata = array();
		 $edata['type'] = $type;

		 $emailData = $this->model_api_notification->getEmailData($edata);	

	    }



	$html = "";

   if($emailData['type']=="7" && $type=="7"){

		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>'.$emailData['email_header'].'</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">This is an Automated Alert Email</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$result['username'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$ruleName.'! Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>';

			 if($emailData['what_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$ruleType.'- '.$rulevalue.'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['notes_description'].'
							</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['what_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		}
			if($emailData['when_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$result['date_added'].'&nbsp;'.$result['notetime'].'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['when_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		}
			if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$result['facility'].'&nbsp;'.$result['address'].'&nbsp;'.$result['location'].'&nbsp;'.$result['zone_name'].'&nbsp;'.$result['zipcode'].', '.$result['contry_name'].'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['where_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		}	
		 if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where </h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['username'].'
							</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		}	

		$html.='<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>

		
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						
						<td align="left" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_footer"].'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';



$useremailids = array();
											if($user_roles != null && $user_roles != ""){
													
												$user_roles1 = $user_roles;

												foreach ($user_roles1 as $user_role) {
													$urole = array();
													$urole['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers($urole);
													
													if($tusers){
														foreach ($tusers as $tuser) {
															if($tuser['email'] != null && $tuser['email'] != ""){
																$useremailids[] = $tuser['email'];
															}
														}
													}
												}
												
											}
												
											if($user_ids != null && $user_ids != ""){
												$userids1 = $user_ids;
						
												foreach ($userids1 as $userid) {
													$user_info = $this->model_user_user->getUserbyupdate($userid);
													if ($user_info) {
														if($user_info['email']){
															$useremailids[] = $user_info['email'];
														}
													}
												}
												
											}
											
											if(($user_ids == null && $user_ids == "") && ($user_roles == null && $user_roles == "")){
												$user_email = 'app-monitoring@noteactive.com';
											}
												
											
											$edata = array();
											$edata['message'] = $message33;
											$edata['subject'] = $emailData['email_subject'];
											$edata['useremailids'] = $useremailids;
											$edata['user_email'] = $user_email;
												
											$email_status = $this->model_api_emailapi->sendmail($edata);	


}
}

	public function notification() {
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);

		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('user/user');
		$this->load->model('notes/tags');
		$this->load->model('createtask/createtask');
		
		$this->load->model('user/user_group');
		$this->load->model('user/user');
		$this->load->model('setting/tags');
		
		$config_rules_status = $this->customer->isRule();
		
		
		//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
		
		//require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		//require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		$this->load->model('api/emailapi');
		$this->load->model('api/smsapi');
		
		$this->load->model('facilities/facilities');
		$resulsst =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		if($resulsst['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			$facilities_id  = $this->session->data['search_facilities_id']; 
			$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $this->customer->getId(); 
				$timezone_name = $this->customer->isTimezone();
			}
			 
		}else{
			 $facilities_id = $this->customer->getId(); 
			 $timezone_name = $this->customer->isTimezone();
		}
		
		$d = array();
		$d['facilities_id'] = $facilities_id;
		
		
		$rules = $this->model_notes_rules->getRules($d);
		//var_dump($rules);
		//echo "<hr>";
		
		
		
		$json = array();
		$notesIds = array();
		
		$tnotesIds = array();
		$facilityDetails = array();
		
		$andRuleArray = array();
		$nrulesvalue = "";
		$rulename = "";
		$rulesvalue = "";
		$andrulesValues = array();
		$andrulesTaskValues = array();
		$andrulesActionValues = array();
		$andrulesActionValues2 = array();
		
		$rowModule = array();
		
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		//var_dump($facility);
		
		if($facility['web_audio_file'] !=NULL && $facility['web_audio_file'] !=""){
			$facility_web_audio_file = HTTP_SERVER .'image/ringtone/'.$facility['web_audio_file']; 
		}else{
			$facility_web_audio_file = '';
		}
		
					
		$country_info = $this->model_setting_country->getCountry($facility['country_id']);
		$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
		
		
		date_default_timezone_set($timezone_name);
					
		$currenttimes = date('H:i');
		$searchdate =  date('m-d-Y');
		$last_date = date('Y-m-d', strtotime("-10 day"));
		$sql22 = "DELETE FROM `" . DB_PREFIX . "forms` WHERE `date_added` <= '".$last_date." 23:59:59' and notes_id = 0 ";
		$this->db->query($sql22);
		
		/*
		$sql221 = "DELETE FROM `" . DB_PREFIX . "tags` WHERE `date_added` <= '".$last_date." 23:59:59' and status = 0 ";
		$this->db->query($sql221);
		*/
		
		if($rules){
			foreach($rules as $rule){
				$allnotesIds = array();		
				$allrulename = array();		
				if($currenttimes == '23:59'){
					$sql = "update `" . DB_PREFIX . "rules` set snooze_dismiss = '0' where rules_id ='".$rule['rules_id']."'";
					$this->db->query($sql);
				}
				
				if($config_rules_status == '1'){
					if($rule['rules_operation'] == 2){
						foreach($rule['onschedule_rules_module'] as $onschedule_rules_module){
							
							//var_dump($rule['rules_operation_recurrence']);
							
							if($rule['rules_operation_recurrence'] == '1'){
								
								$date = str_replace('-', '/', $searchdate);
								$res = explode("/", $date);
								$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
								
								$snooze_time71 = 0;
								$thestime61 = date('H:i:s');
								$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								
								//var_dump($changedDate);
								$dailytime = date('H:i');
								
								//var_dump($dailytime);
								
								$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
								
								//var_dump($rules_operation_time);
								//echo "<hr>";
								if($dailytime == $rules_operation_time){
									
									$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
									
									
										/* sms */
										if($onschedule_rules_module['onschedule_action'] == '1'){
											
											
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
													
													$user_roles1 = $onschedule_rules_module['ouser_roles'];

													foreach ($user_roles1 as $user_role) {
														$urole = array();
														$urole['user_group_id'] = $user_role;
														$tusers = $this->model_user_user->getUsers($urole);
														
														if($tusers){
															foreach ($tusers as $tuser) {
																if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
																	$number = $tuser['phone_number']; 
																
																	$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
																	
																	$sdata = array();
																	$sdata['message'] = $message;
																	$sdata['phone_number'] = $tuser['phone_number'];
																	$sdata['facilities_id'] = $facilities_id;
																		
																	$response = $this->model_api_smsapi->sendsms($sdata);
												
																	
																}
															}
														}
													}
													
												}
												
												if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
													$userids1 = $onschedule_rules_module['ouserids'];
							
													foreach ($userids1 as $userid) {
														$user_info = $this->model_user_user->getUserbyupdate($userid);
														if ($user_info) {
															if($user_info['phone_number'] != 0){
																$number = $user_info['phone_number']; 
																
																$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
																
																$sdata = array();
																$sdata['message'] = $message;
																$sdata['phone_number'] = $user_info['phone_number'];
																$sdata['facilities_id'] = $facilities_id;	
																$response = $this->model_api_smsapi->sendsms($sdata);
											
																
															}
														}
													}
													
												}
												
												if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
													$number = '19045832155';
													
													$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
													
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = '19045832155';
													$sdata['facilities_id'] = $facilities_id;	
													$response = $this->model_api_smsapi->sendsms($sdata);
											
													
												}
												
											
											
											//$response = $client->account->sms_messages->create($from,$number,$text);
										}
										
										/* Email */
										if($onschedule_rules_module['onschedule_action'] == '2'){
											
											$onschedule_description51125e2 = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
											
											$resultd = array();
											$resultd['notes_id'] = '';
											$resultd['highlighter_value'] = '';
											$resultd['notes_description'] = $onschedule_description51125e2;
											$resultd['date_added'] = date('j, F Y', strtotime($changedDate));
											$resultd['note_date'] = date('j, F Y', strtotime($changedDate));
											$resultd['notetime'] = date('h:i A', strtotime($taskTime));
											$resultd['username'] = $result['user_id'];
											$resultd['email'] = $user_info['email'];
											$resultd['phone_number'] = $user_info['phone_number'];
											$resultd['sms_number'] = $facility['sms_number'];
											$resultd['facility'] = $facility['facility'];
											$resultd['address'] = $facility['address'];
											$resultd['location'] = $facility['location'];
											$resultd['zipcode']= $facility['zipcode'];
											$resultd['contry_name'] = $country_info['name'];
											$resultd['zone_name'] = $zone_info['name'];
											$resultd['href'] = $this->url->link('common/login', '', 'SSL');
											
											
											$message33 = "";
												
											$rulevalue = date('h:i A', strtotime($taskTime));
											$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Daily', $rulevalue,"7",$onschedule_rules_module['ouser_roles'],$onschedule_rules_module['ouserids']);
											/*
											$useremailids = array();
											
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
													
												$user_roles1 = $onschedule_rules_module['ouser_roles'];

												foreach ($user_roles1 as $user_role) {
													$urole = array();
													$urole['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers($urole);
													
													if($tusers){
														foreach ($tusers as $tuser) {
															if($tuser['email'] != null && $tuser['email'] != ""){
																
																$useremailids[] = $tuser['email'];
															}
														}
													}
												}
												
											}
											
											if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
												$userids1 = $onschedule_rules_module['ouserids'];
						
												foreach ($userids1 as $userid) {
													$user_info = $this->model_user_user->getUserbyupdate($userid);
													if ($user_info) {
														if($user_info['email']){
															
															$useremailids[] = $user_info['email'];
														}
													}
												}
												
											}
											
											if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
												
												$user_email = 'app-monitoring@noteactive.com';
											}
											
											$edata = array();
											$edata['message'] = $message33;
											$edata['subject'] = 'This is an Automated Alert Email.';
											$edata['useremailids'] = $useremailids;
											$edata['user_email'] = $user_email;
												
											$email_status = $this->model_api_emailapi->sendmail($edata);*/
											 
										}
									
									/* Notification */
									if($onschedule_rules_module['onschedule_action'] == '3'){
										
										$onschedule_description51125n2 = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
										
										if($rule['snooze_dismiss'] != '2'){
											$json['rulenotes'][] = array(
												'notes_id'    => '',
												'rules_id'    => $rule['rules_id'],
												'highlighter_value'   => '',
												'notes_description'   => $onschedule_description51125n2,
												'date_added' => date('j, F Y', strtotime($changedDate)),
												'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
												'notetime'   => date('h:i A', strtotime($taskTime)),
												'username'      => '',
												'email'      => '',
												'facility'     => '',
											);
											
											$json['total'] = '1'; 
										}
									}
									
									
									
									/* Create Task */
									if($onschedule_rules_module['onschedule_action'] == '4'){
										
										$sqls23d = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' and taskadded = '0' ";
											$query4d = $this->db->query($sqls23d);
											
											if($query4d->num_rows == 0){
												
												$addtaskd = array();
						
												/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
													$snooze_time71 = 0;
													$thestime61 = $onschedule_rules_module['taskTime'];
												}else{
													$snooze_time71 = 10;
													$thestime61 = date('H:i:s');
												}*/
												
												$snooze_time71 = 1;
												$thestime61 = date('H:i:s');
												//var_dump($thestime6);
												
												$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
												
												
												$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
												$res = explode("/", $date);
												$taskDate = $res[1]."-".$res[0]."-".$res[2];
													
												
												$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
												$res2 = explode("/", $date2);
												$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
												
												
												$addtaskd['taskDate'] = date('m-d-Y', strtotime($taskDate));
												$addtaskd['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
												$addtaskd['recurrence'] = $onschedule_rules_module['recurrence'];
												$addtaskd['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
												$addtaskd['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
												$addtaskd['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
												$addtaskd['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
												$addtaskd['taskTime'] = $taskTime; //date('H:i:s');
												$addtaskd['endtime'] = $stime8;
												
												$onschedule_description11 = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
												
												$addtaskd['description'] = $onschedule_rules_module['description'].' '.$onschedule_description11;
												
												$addtaskd['assignto'] = $onschedule_rules_module['assign_to'];
												
												$addtaskd['facilities_id'] = $facilities_id;
												$addtaskd['task_form_id'] = $onschedule_rules_module['task_form_id'];
												
												if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
												$addtaskd['transport_tags'] = explode(',',$onschedule_rules_module['transport_tags']);
												}
												
												$addtaskd['pickup_facilities_id'] = $onschedule_rules_module['pickup_facilities_id'];
												$addtaskd['pickup_locations_address'] = $onschedule_rules_module['pickup_locations_address'];
												$addtaskd['pickup_locations_time'] = $onschedule_rules_module['pickup_locations_time'];
												
												$addtaskd['dropoff_facilities_id'] = $onschedule_rules_module['dropoff_facilities_id'];
												$addtaskd['dropoff_locations_address'] = $onschedule_rules_module['dropoff_locations_address'];
												$addtaskd['dropoff_locations_time'] = $onschedule_rules_module['dropoff_locations_time'];
												
												$addtaskd['tasktype'] = $onschedule_rules_module['tasktype'];
												$addtaskd['numChecklist'] = $onschedule_rules_module['numChecklist'];
												$addtaskd['task_alert'] = $onschedule_rules_module['task_alert'];
												$addtaskd['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
												$addtaskd['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
												$addtaskd['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
												$addtaskd['rules_task'] = $onschedule_rules_module['task_random_id'];
												
												
												$addtaskd['recurnce_hrly_recurnce'] = $onschedule_rules_module['recurnce_hrly_recurnce'];
												$addtaskd['daily_endtime'] = $onschedule_rules_module['daily_endtime'];
												
												if($onschedule_rules_module['daily_times'] != null && $onschedule_rules_module['daily_times'] !=""){
													$addtaskd['daily_times'] =  explode(',',$onschedule_rules_module['daily_times']);
												}
												
												if($onschedule_rules_module['medication_tags'] != null && $onschedule_rules_module['medication_tags'] !=""){
													$addtaskd['medication_tags'] =  explode(',',$onschedule_rules_module['medication_tags']);
												
												
													$aa  = urldecode($onschedule_rules_module['tags_medication_details_ids']); 
													$aa1  = unserialize($aa); 
																	
													$tags_medication_details_ids = array();
													foreach($aa1 as $key=>$mresult){
														$tags_medication_details_ids[$key] = $mresult;
													}
													$addtaskd['tags_medication_details_ids'] = $tags_medication_details_ids;
												
												}
												
												$addtaskd['emp_tag_id'] = $onschedule_rules_module['emp_tag_id'];
												
												$addtaskd['recurnce_hrly_perpetual'] = $onschedule_rules_module['recurnce_hrly_perpetual'];
												$addtaskd['completion_alert'] = $onschedule_rules_module['completion_alert'];
												$addtaskd['completion_alert_type_sms'] = $onschedule_rules_module['completion_alert_type_sms'];
												$addtaskd['completion_alert_type_email'] = $onschedule_rules_module['completion_alert_type_email'];
												
												if($onschedule_rules_module['user_roles'] != null && $onschedule_rules_module['user_roles'] !=""){
													$addtaskd['user_roles'] =  explode(',',$onschedule_rules_module['user_roles']);
												}
												
												if($onschedule_rules_module['userids'] != null && $onschedule_rules_module['userids'] !=""){
													$addtaskd['userids'] =  explode(',',$onschedule_rules_module['userids']);
												}
												$addtaskd['task_status'] = $onschedule_rules_module['task_status'];
												
												$addtaskd['visitation_tag_id'] = $onschedule_rules_module['visitation_tag_id'];
												
												if($onschedule_rules_module['visitation_tags'] != null && $onschedule_rules_module['visitation_tags'] !=""){
													$addtaskd['visitation_tags'] =  explode(',',$onschedule_rules_module['visitation_tags']);
												}
												$addtaskd['visitation_start_facilities_id'] = $onschedule_rules_module['visitation_start_facilities_id'];
												$addtaskd['visitation_start_address'] = $onschedule_rules_module['visitation_start_address'];
												$addtaskd['visitation_start_time'] = $onschedule_rules_module['visitation_start_time'];
												$addtaskd['visitation_appoitment_facilities_id'] = $onschedule_rules_module['visitation_appoitment_facilities_id'];
												$addtaskd['visitation_appoitment_address'] = $onschedule_rules_module['visitation_appoitment_address'];
												$addtaskd['visitation_appoitment_time'] = $onschedule_rules_module['visitation_appoitment_time'];
												$addtaskd['complete_endtime'] = $onschedule_rules_module['complete_endtime'];
												
												if($onschedule_rules_module['completed_times'] != null && $onschedule_rules_module['completed_times'] !=""){
													$addtaskd['completed_times'] =  explode(',',$onschedule_rules_module['completed_times']);
												}
												$addtaskd['completed_alert'] = $onschedule_rules_module['completed_alert'];
												$addtaskd['completed_late_alert'] = $onschedule_rules_module['completed_late_alert'];
												$addtaskd['incomplete_alert'] = $onschedule_rules_module['incomplete_alert'];
												$addtaskd['deleted_alert'] = $onschedule_rules_module['deleted_alert'];
												$addtaskd['attachement_form'] = $onschedule_rules_module['attachement_form'];
												$addtaskd['tasktype_form_id'] = $onschedule_rules_module['tasktype_form_id'];
												
												$addtaskd['reminder_alert'] = $onschedule_rules_module['reminder_alert'];
												if($onschedule_rules_module['reminderminus'] != null && $onschedule_rules_module['reminderminus'] !=""){
													$addtaskd['reminderminus'] =  explode(',',$onschedule_rules_module['reminderminus']);
												}
												
												if($onschedule_rules_module['reminderplus'] != null && $onschedule_rules_module['reminderplus'] !=""){
													$addtaskd['reminderplus'] =  explode(',',$onschedule_rules_module['reminderplus']);
												}
												
												$addtaskd['assign_to_type'] = $onschedule_rules_module['assign_to_type'];
												if($onschedule_rules_module['user_assign_to'] != null && $onschedule_rules_module['user_assign_to'] !=""){
													$addtaskd['assign_to'] =  explode(',',$onschedule_rules_module['user_assign_to']);
												}
												if($onschedule_rules_module['user_role_assign_ids'] != null && $onschedule_rules_module['user_role_assign_ids'] !=""){
													$addtaskd['user_role_assign_ids'] =  explode(',',$onschedule_rules_module['user_role_assign_ids']);
												}
												
												
												
												$this->load->model('createtask/createtask');
												$this->model_createtask_createtask->addcreatetask($addtaskd, $facilities_id);
											}	
									}
								}
										
							}

							if($rule['rules_operation_recurrence'] == '2'){
								$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
								$date = str_replace('-', '/', $searchdate);
								$res = explode("/", $date);
								$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
								
								
								$snooze_time71 = 1;
								$thestime61 = date('H:i:s');
								$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								//var_dump($changedDate);
								$dailytime = date('H:i');
								
								//var_dump($dailytime);
								
								$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
								//var_dump($rules_operation_time);
								
								$currentDay = date('l');
								//var_dump($currentDay);
								
								$recurnce_week = $rule['recurnce_week'];
								
								if($currentDay == $recurnce_week){
									//var_dump($recurnce_week);
									//echo "<hr>";
									if($dailytime == $rules_operation_time){
										//var_dump($recurnce_week);
										//echo "<hr>";
										/* sms */
										if($onschedule_rules_module['onschedule_action'] == '1'){
											//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
											/*
											$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
											$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
											$client = new Services_Twilio($account_sid, $auth_token); 
											*/
											
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
												
												$user_roles1 = $onschedule_rules_module['ouser_roles'];

												foreach ($user_roles1 as $user_role) {
													$urole = array();
													$urole['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers($urole);
													
													if($tusers){
														foreach ($tusers as $tuser) {
															if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
																$number = $tuser['phone_number']; 
															
																$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
																
																$sdata = array();
																$sdata['message'] = $message;
																$sdata['phone_number'] = $tuser['phone_number'];
																$sdata['facilities_id'] = $facilities_id;	
																$response = $this->model_api_smsapi->sendsms($sdata);
											
																
															}
														}
													}
												}
												
											}
											
											if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
												$userids1 = $onschedule_rules_module['ouserids'];
						
												foreach ($userids1 as $userid) {
													$user_info = $this->model_user_user->getUserbyupdate($userid);
													if ($user_info) {
														if($user_info['phone_number'] != 0){
															$number = $user_info['phone_number']; 
															
															$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
															
															$sdata = array();
															$sdata['message'] = $message;
															$sdata['phone_number'] = $user_info['phone_number'];
															$sdata['facilities_id'] = $facilities_id;	
															$response = $this->model_api_smsapi->sendsms($sdata);
										
															
														}
													}
												}
												
											}
											
											if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
												
												
												$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
												
												$sdata = array();
												$sdata['message'] = $message;
												$sdata['phone_number'] = '19045832155';
												$sdata['facilities_id'] = $facilities_id;	
												$response = $this->model_api_smsapi->sendsms($sdata);
										
												
											}
											
											
										}
										
										/* Email */
										if($onschedule_rules_module['onschedule_action'] == '2'){
											
											$onschedule_description51125n2e = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
											
											$resultw = array();
											$resultw['notes_id'] = '';
											$resultw['highlighter_value'] = '';
											$resultw['notes_description'] = $onschedule_description51125n2e;
											$resultw['date_added'] = date('j, F Y', strtotime($changedDate));
											$resultw['note_date'] = date('j, F Y', strtotime($changedDate));
											$resultw['notetime'] = date('h:i A', strtotime($taskTime));
											$resultw['username'] = $result['user_id'];
											$resultw['email'] = $user_info['email'];
											$resultw['phone_number'] = $user_info['phone_number'];
											$resultw['sms_number'] = $facility['sms_number'];
											$resultw['facility'] = $facility['facility'];
											$resultw['address'] = $facility['address'];
											$resultw['location'] = $facility['location'];
											$resultw['zipcode']= $facility['zipcode'];
											$resultw['contry_name'] = $country_info['name'];
											$resultw['zone_name'] = $zone_info['name'];
											$resultw['href'] = $this->url->link('common/login', '', 'SSL');
											
											
											$message33 = "";
												
											$rulevalue = date('h:i A', strtotime($rule['rules_operation_time']));
											$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Week', $rulevalue,"7",$onschedule_rules_module['ouser_roles'],$onschedule_rules_module['ouserids']);
											
											/*$useremailids = array();
											
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
													
													$user_roles1 = $onschedule_rules_module['ouser_roles'];

													foreach ($user_roles1 as $user_role) {
														$urole = array();
														$urole['user_group_id'] = $user_role;
														$tusers = $this->model_user_user->getUsers($urole);
														
														if($tusers){
															foreach ($tusers as $tuser) {
																if($tuser['email'] != null && $tuser['email'] != ""){
																	$useremailids[] = $tuser['email'];
																}
															}
														}
													}
													
												}
												
												if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
													$userids1 = $onschedule_rules_module['ouserids'];
							
													foreach ($userids1 as $userid) {
														$user_info = $this->model_user_user->getUserbyupdate($userid);
														if ($user_info) {
															if($user_info['email']){
																$useremailids[] = $user_info['email'];
															}
														}
													}
													
												}
												
												if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
													$user_email = 'app-monitoring@noteactive.com';
												}
											
											$edata = array();
											$edata['message'] = $message33;
											$edata['subject'] = 'This is an Automated Alert Email.';
											$edata['useremailids'] = $useremailids;
											$edata['user_email'] = $user_email;
												
											$email_status = $this->model_api_emailapi->sendmail($edata);*/
											
										}
										
										
										
										/* Notification */
										if($onschedule_rules_module['onschedule_action'] == '3'){
											
											$onschedule_description511n25n2e = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
											
											if($rule['snooze_dismiss'] != '2'){
												$json['rulenotes'][] = array(
													'notes_id'    => '',
													'rules_id'    => $rule['rules_id'],
													'highlighter_value'   => '',
													'notes_description'   => $onschedule_description511n25n2e,
													'date_added' => date('j, F Y', strtotime($changedDate)),
													'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
													'notetime'   => date('h:i A', strtotime($taskTime)),
													'username'      => '',
													'email'      => '',
													'facility'     => '',
												);
												
												$json['total'] = '1'; 
											}
											
										}
										
										//var_dump($json['rulenotes']);
										
										/* Create Task */
										if($onschedule_rules_module['onschedule_action'] == '4'){
											
											$sqls23w = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' and taskadded = '0' ";
											$query4w = $this->db->query($sqls23w);
											
											if($query4w->num_rows == 0){
												
												$addtaskw = array();
						
												/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
													$snooze_time71 = 0;
													$thestime61 = $onschedule_rules_module['taskTime'];
												}else{
													$snooze_time71 = 10;
													$thestime61 = date('H:i:s');
												}*/
												
												$snooze_time71 = 1;
												$thestime61 = date('H:i:s');
												//var_dump($thestime6);
												
												$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
												
												
												$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
												$res = explode("/", $date);
												$taskDate = $res[1]."-".$res[0]."-".$res[2];
													
												
												$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
												$res2 = explode("/", $date2);
												$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
												
												
												$addtaskw['taskDate'] = date('m-d-Y', strtotime($taskDate));
												$addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
												$addtaskw['recurrence'] = $onschedule_rules_module['recurrence'];
												$addtaskw['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
												$addtaskw['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
												$addtaskw['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
												$addtaskw['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
												$addtaskw['taskTime'] = $taskTime; //date('H:i:s');
												$addtaskw['endtime'] = $stime8;
												
												$onschedule_description1112 = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
												
												$addtaskw['description'] = $onschedule_rules_module['description'].' '.$onschedule_description1112;
												
												$addtaskw['assignto'] = $onschedule_rules_module['assign_to'];
												
												$addtaskw['facilities_id'] = $facilities_id;
												$addtaskw['task_form_id'] = $onschedule_rules_module['task_form_id'];
												
												if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
												$addtaskw['transport_tags'] = explode(',',$onschedule_rules_module['transport_tags']);
												}
												
												$addtaskw['pickup_facilities_id'] = $onschedule_rules_module['pickup_facilities_id'];
												$addtaskw['pickup_locations_address'] = $onschedule_rules_module['pickup_locations_address'];
												$addtaskw['pickup_locations_time'] = $onschedule_rules_module['pickup_locations_time'];
												
												$addtaskw['dropoff_facilities_id'] = $onschedule_rules_module['dropoff_facilities_id'];
												$addtaskw['dropoff_locations_address'] = $onschedule_rules_module['dropoff_locations_address'];
												$addtaskw['dropoff_locations_time'] = $onschedule_rules_module['dropoff_locations_time'];
												
												
												$addtaskw['tasktype'] = $onschedule_rules_module['tasktype'];
												$addtaskw['numChecklist'] = $onschedule_rules_module['numChecklist'];
												$addtaskw['task_alert'] = $onschedule_rules_module['task_alert'];
												$addtaskw['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
												$addtaskw['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
												$addtaskw['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
												$addtaskw['rules_task'] = $onschedule_rules_module['task_random_id'];
												
												$addtaskw['recurnce_hrly_recurnce'] = $onschedule_rules_module['recurnce_hrly_recurnce'];
												$addtaskw['daily_endtime'] = $onschedule_rules_module['daily_endtime'];
												
												if($onschedule_rules_module['daily_times'] != null && $onschedule_rules_module['daily_times'] !=""){
													$addtaskw['daily_times'] =  explode(',',$onschedule_rules_module['daily_times']);
												}
												
												if($onschedule_rules_module['medication_tags'] != null && $onschedule_rules_module['medication_tags'] !=""){
													$addtaskw['medication_tags'] =  explode(',',$onschedule_rules_module['medication_tags']);
												
												
													$aa  = urldecode($onschedule_rules_module['tags_medication_details_ids']); 
													$aa1  = unserialize($aa); 
																	
													$tags_medication_details_ids = array();
													foreach($aa1 as $key=>$mresult){
														$tags_medication_details_ids[$key] = $mresult;
													}
													$addtaskw['tags_medication_details_ids'] = $tags_medication_details_ids;
												
												}
												
												$addtaskw['emp_tag_id'] = $onschedule_rules_module['emp_tag_id'];
												
												$addtaskw['recurnce_hrly_perpetual'] = $onschedule_rules_module['recurnce_hrly_perpetual'];
												$addtaskw['completion_alert'] = $onschedule_rules_module['completion_alert'];
												$addtaskw['completion_alert_type_sms'] = $onschedule_rules_module['completion_alert_type_sms'];
												$addtaskw['completion_alert_type_email'] = $onschedule_rules_module['completion_alert_type_email'];
												
												if($onschedule_rules_module['user_roles'] != null && $onschedule_rules_module['user_roles'] !=""){
													$addtaskw['user_roles'] =  explode(',',$onschedule_rules_module['user_roles']);
												}
												
												if($onschedule_rules_module['userids'] != null && $onschedule_rules_module['userids'] !=""){
													$addtaskw['userids'] =  explode(',',$onschedule_rules_module['userids']);
												}
												$addtaskw['task_status'] = $onschedule_rules_module['task_status'];
												
												$addtaskw['visitation_tag_id'] = $onschedule_rules_module['visitation_tag_id'];
												
												if($onschedule_rules_module['visitation_tags'] != null && $onschedule_rules_module['visitation_tags'] !=""){
													$addtaskw['visitation_tags'] =  explode(',',$onschedule_rules_module['visitation_tags']);
												}
												$addtaskw['visitation_start_facilities_id'] = $onschedule_rules_module['visitation_start_facilities_id'];
												$addtaskw['visitation_start_address'] = $onschedule_rules_module['visitation_start_address'];
												$addtaskw['visitation_start_time'] = $onschedule_rules_module['visitation_start_time'];
												$addtaskw['visitation_appoitment_facilities_id'] = $onschedule_rules_module['visitation_appoitment_facilities_id'];
												$addtaskw['visitation_appoitment_address'] = $onschedule_rules_module['visitation_appoitment_address'];
												$addtaskw['visitation_appoitment_time'] = $onschedule_rules_module['visitation_appoitment_time'];
												$addtaskw['complete_endtime'] = $onschedule_rules_module['complete_endtime'];
												
												if($onschedule_rules_module['completed_times'] != null && $onschedule_rules_module['completed_times'] !=""){
													$addtaskw['completed_times'] =  explode(',',$onschedule_rules_module['completed_times']);
												}
												$addtaskw['completed_alert'] = $onschedule_rules_module['completed_alert'];
												$addtaskw['completed_late_alert'] = $onschedule_rules_module['completed_late_alert'];
												$addtaskw['incomplete_alert'] = $onschedule_rules_module['incomplete_alert'];
												$addtaskw['deleted_alert'] = $onschedule_rules_module['deleted_alert'];
												$addtaskw['attachement_form'] = $onschedule_rules_module['attachement_form'];
												$addtaskw['tasktype_form_id'] = $onschedule_rules_module['tasktype_form_id'];
												
												$addtaskw['reminder_alert'] = $onschedule_rules_module['reminder_alert'];
												if($onschedule_rules_module['reminderminus'] != null && $onschedule_rules_module['reminderminus'] !=""){
													$addtaskw['reminderminus'] =  explode(',',$onschedule_rules_module['reminderminus']);
												}
												
												if($onschedule_rules_module['reminderplus'] != null && $onschedule_rules_module['reminderplus'] !=""){
													$addtaskw['reminderplus'] =  explode(',',$onschedule_rules_module['reminderplus']);
												}
												$addtaskw['assign_to_type'] = $onschedule_rules_module['assign_to_type'];
												if($onschedule_rules_module['user_assign_to'] != null && $onschedule_rules_module['user_assign_to'] !=""){
													$addtaskw['assign_to'] =  explode(',',$onschedule_rules_module['user_assign_to']);
												}
												if($onschedule_rules_module['user_role_assign_ids'] != null && $onschedule_rules_module['user_role_assign_ids'] !=""){
													$addtaskw['user_role_assign_ids'] =  explode(',',$onschedule_rules_module['user_role_assign_ids']);
												}
												
												$this->load->model('createtask/createtask');
												$this->model_createtask_createtask->addcreatetask($addtaskw, $facilities_id);
												
											}
										}
										
									}
								}
								
								
							}
										
							if($rule['rules_operation_recurrence'] == '3'){
								$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
								$date = str_replace('-', '/', $searchdate);
								$res = explode("/", $date);
								$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
								
								$snooze_time71 = 1;
								$thestime61 = date('H:i:s');
								$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								
								//var_dump($changedDate);
								$dailytime = date('H:i');
								
								//var_dump($dailytime);
								
								$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
								//var_dump($rules_operation_time);
								
								$currentdate = date('d-m-Y');
								
								$recurnce_day = $rule['recurnce_day'];
								$currentMonth = date('m');
								$currentYear = date('Y');
								$recurnce_day_date = $recurnce_day."-".$currentMonth."-".$currentYear;
								
								
								if($currentdate == $recurnce_day_date){
									//var_dump($recurnce_day);
									//echo "<hr>";
									if($dailytime == $rules_operation_time){
										
										/* sms */
										if($onschedule_rules_module['onschedule_action'] == '1'){
											
											
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
													
												$user_roles1 = $onschedule_rules_module['ouser_roles'];

												foreach ($user_roles1 as $user_role) {
													$urole = array();
													$urole['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers($urole);
													
													if($tusers){
														foreach ($tusers as $tuser) {
															if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
																$number = $tuser['phone_number']; 
															
																$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
																
																$sdata = array();
																$sdata['message'] = $message;
																$sdata['phone_number'] = $tuser['phone_number'];
																$sdata['facilities_id'] = $facilities_id;	
																$response = $this->model_api_smsapi->sendsms($sdata);
											
																
															}
														}
													}
												}
												
											}
											
											if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
												$userids1 = $onschedule_rules_module['ouserids'];
						
												foreach ($userids1 as $userid) {
													$user_info = $this->model_user_user->getUserbyupdate($userid);
													if ($user_info) {
														if($user_info['phone_number'] != 0){
															$number = $user_info['phone_number']; 
															
															$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
															
															$sdata = array();
															$sdata['message'] = $message;
															$sdata['phone_number'] = $user_info['phone_number'];
															$sdata['facilities_id'] = $facilities_id;	
															$response = $this->model_api_smsapi->sendsms($sdata);
										
															
														}
													}
												}
												
											}
											
											if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
												$number = '19045832155';
												
												$message = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
												
												$sdata = array();
												$sdata['message'] = $message;
												$sdata['phone_number'] = '19045832155';
												$sdata['facilities_id'] = $facilities_id;	
												$response = $this->model_api_smsapi->sendsms($sdata);
										
												
											}
											
											//$response = $client->account->sms_messages->create($from,$number,$text);
										}
										
										/* Email */
										if($onschedule_rules_module['onschedule_action'] == '2'){
											
											$onschedule_description511n2r5n2e = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
											
											$resultm = array();
											$resultm['notes_id'] = '';
											$resultm['highlighter_value'] = '';
											$resultm['notes_description'] = $onschedule_description511n2r5n2e;
											$resultm['date_added'] = date('j, F Y', strtotime($changedDate));
											$resultm['note_date'] = date('j, F Y', strtotime($changedDate));
											$resultm['notetime'] = date('h:i A', strtotime($taskTime));
											$resultm['username'] = $result['user_id'];
											$resultm['email'] = $user_info['email'];
											$resultm['phone_number'] = $user_info['phone_number'];
											$resultm['sms_number'] = $facility['sms_number'];
											$resultm['facility'] = $facility['facility'];
											$resultm['address'] = $facility['address'];
											$resultm['location'] = $facility['location'];
											$resultm['zipcode']= $facility['zipcode'];
											$resultm['contry_name'] = $country_info['name'];
											$resultm['zone_name'] = $zone_info['name'];
											$resultm['href'] = $this->url->link('common/login', '', 'SSL');
											
											
											
											$message33 = "";
												
											$rulevalue = date('h:i A', strtotime($rule['rules_operation_time']));
											$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Month', $rulevalue,"7",$onschedule_rules_module['ouser_roles'],$onschedule_rules_module['ouserids']);
											
											/*$useremailids = array();
											if($onschedule_rules_module['ouser_roles'] != null && $onschedule_rules_module['ouser_roles'] != ""){
													
												$user_roles1 = $onschedule_rules_module['ouser_roles'];

												foreach ($user_roles1 as $user_role) {
													$urole = array();
													$urole['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers($urole);
													
													if($tusers){
														foreach ($tusers as $tuser) {
															if($tuser['email'] != null && $tuser['email'] != ""){
																$useremailids[] = $tuser['email'];
															}
														}
													}
												}
												
											}
												
											if($onschedule_rules_module['ouserids'] != null && $onschedule_rules_module['ouserids'] != ""){
												$userids1 = $onschedule_rules_module['ouserids'];
						
												foreach ($userids1 as $userid) {
													$user_info = $this->model_user_user->getUserbyupdate($userid);
													if ($user_info) {
														if($user_info['email']){
															$useremailids[] = $user_info['email'];
														}
													}
												}
												
											}
											
											if(($onschedule_rules_module['ouserids'] == null && $onschedule_rules_module['ouserids'] == "") && ($onschedule_rules_module['ouser_roles'] == null && $onschedule_rules_module['ouser_roles'] == "")){
												$user_email = 'app-monitoring@noteactive.com';
											}
												
											
											$edata = array();
											$edata['message'] = $message33;
											$edata['subject'] = 'This is an Automated Alert Email.';
											$edata['useremailids'] = $useremailids;
											$edata['user_email'] = $user_email;
												
											$email_status = $this->model_api_emailapi->sendmail($edata);*/
											 
											
											
											
											
										}
										
										/* Notification */
										if($onschedule_rules_module['onschedule_action'] == '3'){
											
											$onschedule_descriptiodn511n2r5n2e = substr($onschedule_description, 0, 350) .((strlen($onschedule_description) > 350) ? '..' : '');
											
											if($rule['snooze_dismiss'] != '2'){
												$json['rulenotes'][] = array(
													'notes_id'    => '',
													'rules_id'    => $rule['rules_id'],
													'highlighter_value'   => '',
													'notes_description'   => $onschedule_descriptiodn511n2r5n2e,
													'date_added' => date('j, F Y', strtotime($changedDate)),
													'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
													'notetime'   => date('h:i A', strtotime($taskTime)),
													'username'      => '',
													'email'      => '',
													'facility'     => '',
												);
												
												$json['total'] = '1'; 
											} 
										}
										
										//var_dump($json['rulenotes']);
										
										/* Create Task */
										if($onschedule_rules_module['onschedule_action'] == '4'){
											
											$sqls23m = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' and taskadded = '0' ";
											$query4m = $this->db->query($sqls23m);
											
											if($query4m->num_rows == 0){
												$addtaskm = array();
						
												/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
													$snooze_time71 = 0;
													$thestime61 = $onschedule_rules_module['taskTime'];
												}else{
													$snooze_time71 = 10;
													$thestime61 = date('H:i:s');
												}
												*/
												$snooze_time71 = 1;
												$thestime61 = date('H:i:s');
												//var_dump($thestime6);
												
												$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
												
												
												$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
												$res = explode("/", $date);
												$taskDate = $res[1]."-".$res[0]."-".$res[2];
													
												
												$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
												$res2 = explode("/", $date2);
												$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
												
												
												$addtaskm['taskDate'] = date('m-d-Y', strtotime($taskDate));
												$addtaskm['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
												$addtaskm['recurrence'] = $onschedule_rules_module['recurrence'];
												$addtaskm['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
												$addtaskm['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
												$addtaskm['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
												$addtaskm['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
												$addtaskm['taskTime'] = $taskTime; //date('H:i:s');
												$addtaskm['endtime'] = $stime8;
												
												$onschedule_description511252 = substr($onschedule_description, 0, 150) .((strlen($onschedule_description) > 150) ? '..' : '');
												
												$addtaskm['description'] = $onschedule_rules_module['description'].' '.$onschedule_description511252;
												
												$addtaskm['assignto'] = $onschedule_rules_module['assign_to'];
												
												$addtaskm['facilities_id'] = $facilities_id;
												$addtaskm['task_form_id'] = $onschedule_rules_module['task_form_id'];
												
												if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
												$addtaskm['transport_tags'] = explode(',',$onschedule_rules_module['transport_tags']);
												}
												
												$addtaskm['pickup_facilities_id'] = $onschedule_rules_module['pickup_facilities_id'];
												$addtaskm['pickup_locations_address'] = $onschedule_rules_module['pickup_locations_address'];
												$addtaskm['pickup_locations_time'] = $onschedule_rules_module['pickup_locations_time'];
												
												$addtaskm['dropoff_facilities_id'] = $onschedule_rules_module['dropoff_facilities_id'];
												$addtaskm['dropoff_locations_address'] = $onschedule_rules_module['dropoff_locations_address'];
												$addtaskm['dropoff_locations_time'] = $onschedule_rules_module['dropoff_locations_time'];
												
												$addtaskm['tasktype'] = $onschedule_rules_module['tasktype'];
												$addtaskm['numChecklist'] = $onschedule_rules_module['numChecklist'];
												$addtaskm['task_alert'] = $onschedule_rules_module['task_alert'];
												$addtaskm['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
												$addtaskm['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
												$addtaskm['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
												$addtaskm['rules_task'] = $onschedule_rules_module['task_random_id'];
												
												
												$addtaskm['recurnce_hrly_recurnce'] = $onschedule_rules_module['recurnce_hrly_recurnce'];
												$addtaskm['daily_endtime'] = $onschedule_rules_module['daily_endtime'];
												
												if($onschedule_rules_module['daily_times'] != null && $onschedule_rules_module['daily_times'] !=""){
													$addtaskm['daily_times'] =  explode(',',$onschedule_rules_module['daily_times']);
												}
												
												if($onschedule_rules_module['medication_tags'] != null && $onschedule_rules_module['medication_tags'] !=""){
													$addtaskm['medication_tags'] =  explode(',',$onschedule_rules_module['medication_tags']);
												
												
													$aa  = urldecode($onschedule_rules_module['tags_medication_details_ids']); 
													$aa1  = unserialize($aa); 
																	
													$tags_medication_details_ids = array();
													foreach($aa1 as $key=>$mresult){
														$tags_medication_details_ids[$key] = $mresult;
													}
													$addtaskm['tags_medication_details_ids'] = $tags_medication_details_ids;
												
												}
												
												$addtaskm['emp_tag_id'] = $onschedule_rules_module['emp_tag_id'];
												
												$addtaskm['recurnce_hrly_perpetual'] = $onschedule_rules_module['recurnce_hrly_perpetual'];
												$addtaskm['completion_alert'] = $onschedule_rules_module['completion_alert'];
												$addtaskm['completion_alert_type_sms'] = $onschedule_rules_module['completion_alert_type_sms'];
												$addtaskm['completion_alert_type_email'] = $onschedule_rules_module['completion_alert_type_email'];
												
												if($onschedule_rules_module['user_roles'] != null && $onschedule_rules_module['user_roles'] !=""){
													$addtaskm['user_roles'] =  explode(',',$onschedule_rules_module['user_roles']);
												}
												
												if($onschedule_rules_module['userids'] != null && $onschedule_rules_module['userids'] !=""){
													$addtaskm['userids'] =  explode(',',$onschedule_rules_module['userids']);
												}
												$addtaskm['task_status'] = $onschedule_rules_module['task_status'];
												
												$addtaskm['visitation_tag_id'] = $onschedule_rules_module['visitation_tag_id'];
												
												if($onschedule_rules_module['visitation_tags'] != null && $onschedule_rules_module['visitation_tags'] !=""){
													$addtaskm['visitation_tags'] =  explode(',',$onschedule_rules_module['visitation_tags']);
												}
												$addtaskm['visitation_start_facilities_id'] = $onschedule_rules_module['visitation_start_facilities_id'];
												$addtaskm['visitation_start_address'] = $onschedule_rules_module['visitation_start_address'];
												$addtaskm['visitation_start_time'] = $onschedule_rules_module['visitation_start_time'];
												$addtaskm['visitation_appoitment_facilities_id'] = $onschedule_rules_module['visitation_appoitment_facilities_id'];
												$addtaskm['visitation_appoitment_address'] = $onschedule_rules_module['visitation_appoitment_address'];
												$addtaskm['visitation_appoitment_time'] = $onschedule_rules_module['visitation_appoitment_time'];
												$addtaskm['complete_endtime'] = $onschedule_rules_module['complete_endtime'];
												
												if($onschedule_rules_module['completed_times'] != null && $onschedule_rules_module['completed_times'] !=""){
													$addtaskm['completed_times'] =  explode(',',$onschedule_rules_module['completed_times']);
												}
												$addtaskm['completed_alert'] = $onschedule_rules_module['completed_alert'];
												$addtaskm['completed_late_alert'] = $onschedule_rules_module['completed_late_alert'];
												$addtaskm['incomplete_alert'] = $onschedule_rules_module['incomplete_alert'];
												$addtaskm['deleted_alert'] = $onschedule_rules_module['deleted_alert'];
												$addtaskm['attachement_form'] = $onschedule_rules_module['attachement_form'];
												$addtaskm['tasktype_form_id'] = $onschedule_rules_module['tasktype_form_id'];
												
												$addtaskm['reminder_alert'] = $onschedule_rules_module['reminder_alert'];
												if($onschedule_rules_module['reminderminus'] != null && $onschedule_rules_module['reminderminus'] !=""){
													$addtaskm['reminderminus'] =  explode(',',$onschedule_rules_module['reminderminus']);
												}
												
												if($onschedule_rules_module['reminderplus'] != null && $onschedule_rules_module['reminderplus'] !=""){
													$addtaskm['reminderplus'] =  explode(',',$onschedule_rules_module['reminderplus']);
												}
												$addtaskm['assign_to_type'] = $onschedule_rules_module['assign_to_type'];
												if($onschedule_rules_module['user_assign_to'] != null && $onschedule_rules_module['user_assign_to'] !=""){
													$addtaskm['assign_to'] =  explode(',',$onschedule_rules_module['user_assign_to']);
												}
												if($onschedule_rules_module['user_role_assign_ids'] != null && $onschedule_rules_module['user_role_assign_ids'] !=""){
													$addtaskm['user_role_assign_ids'] =  explode(',',$onschedule_rules_module['user_role_assign_ids']);
												}
												
												$this->load->model('createtask/createtask');
												$this->model_createtask_createtask->addcreatetask($addtaskm, $facilities_id);
											}
										}
										
									}
								}
							}
						}
					}
				}
				
				
				if($config_rules_status == '1'){
					if($rule['rules_operation'] == '1'){
						$andrulesValues = array();
						$andrulesTaskValues = array();
						$andrulesActionValues = array();
						$andrulesActionValues2 = array();
						foreach($rule['rules_module'] as $rules_module){
							
							//var_dump($rules_module);
							//echo "<hr>";

							if($rule['rules_operator'] == '1'){
									
								if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
									$andrulesValues['highlighter_id'] = $rules_module['highlighter_id'];
									
								}
										
								if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
									$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . $rules_module['keyword_id'] . "'");
				
									$active_tagdata = $querya->row;
									$andrulesValues['keyword_image'] =	$active_tagdata['keyword_image'];
									
								}
										
								if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
									$andrulesValues['color_id'] =	$rules_module['color_id'];

								}
									
								if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
									$andrulesValues['keyword_search'] =	$rules_module['keyword_search'];
									
								}
							}
							
							if($rule['rules_operator'] == '2'){
								
								
								if($rules_module['rules_type'] == '1'){
									
									if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
										$sql = "SELECT  notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email  FROM `" . DB_PREFIX . "notes`";
										
										$sql .= 'where 1 = 1 ';
										
										$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
										$sql .= " and facilities_id = '".$facility['facilities_id']."'";
										$sql .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
										$sql .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sql;
										//echo "<hr>";
										 
										$query = $this->db->query($sql);
										//var_dump($query->num_rows);
										//echo "<hr>";
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											foreach($query->rows as $result){
												$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
												
												$allnotesIds[] = array(
													'notes_id' => $result['notes_id'],
													'rules_type' => 'Highlighter',
													'rules_value' =>$highlighterData['highlighter_name'],
												);
											
											}
											
											
										}
									}
									
								}
								
								//var_dump($json);
								
								if($rules_module['rules_type'] == '2'){
								
									if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
										$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . $rules_module['keyword_id'] . "'");
				
										$active_tagdata1 = $querya->row;
										
										//$sql2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sql2 = "SELECT n.* FROM `" . DB_PREFIX . "notes` n ";
										
										$sql2 .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
										
										$sql2 .= 'where 1 = 1 ';
										
										$sql2 .= " and nk.keyword_file = '".$active_tagdata1['keyword_image']."'";
										
										$sql2 .= " and n.facilities_id = '".$facility['facilities_id']."'";
										
										$sql2 .= " and n.snooze_dismiss != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql2 .= " and (n.`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
										$sql2 .= " and n.status = '1' ORDER BY n.notetime DESC  ";
										
										//echo $sql2;
										//echo "<hr>";
										
										$query = $this->db->query($sql2);
										//var_dump($query->rows);
										//echo "<hr>";
										if ($query->num_rows) {
											
											foreach($query->rows as $result){
												$allnotesIds[] = array(
													'notes_id' => $result['notes_id'],
													'rules_type' => 'ActiveNote',
													'rules_value' => $rules_module['keyword_id']
												);
											}
											
										}
									}
								}
								
								if($rules_module['rules_type'] == '3'){
									//var_dump($rules_module['color_id']);
									if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
										$sql3 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sql3 .= 'where 1 = 1 ';
										
										$sql3 .= " and text_color = '#".$rules_module['color_id']."'";
										$sql3 .= " and facilities_id = '".$facility['facilities_id']."'";
										$sql3 .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sql3 .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sql3;
										//echo "<hr>";
										
										$query = $this->db->query($sql3);
										
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											
											foreach($query->rows as $result){
												
												if($rules_module['color_id'] == '008000'){
													$color_id = "Green";
												}
												if($rules_module['color_id'] == 'FF0000'){
													$color_id = "Red";
												}
												if($rules_module['color_id'] == '0000FF'){
													$color_id = "Blue";
												}
												if($rules_module['color_id'] == '000000'){
													$color_id = "Black";
												}
												$allnotesIds[] = array(
													'notes_id' => $result['notes_id'],
													'rules_type' => 'Color',
													'rules_value' => $color_id
												);
												
											}
											
										}
									}
									
								}
								
								
								if($rules_module['rules_type'] == '5'){
									//var_dump($rules_module['keyword_search']);
									if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
										$sqls = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sqls .= 'where 1 = 1 ';
										
										$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
										$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
										$sqls .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sqls .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sqls;
										//echo "<hr>";
										
										$query = $this->db->query($sqls);
										
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											
											foreach($query->rows as $result){
												$allnotesIds[] = array(
													'notes_id' => $result['notes_id'],
													'rules_type' => 'Keyword',
													'rules_value' => $rules_module['keyword_search']
												);
												
											}
										}
									}
								}
								
							}
						}
					}
				}
				
				/* end trigger loop */
				
				//var_dump($andrulesValues);
				if(!empty($andrulesValues)){
					$sql = "SELECT n.* FROM `" . DB_PREFIX . "notes` n ";
					
					if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
						$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
					}
					
					$sql .= 'where 1 = 1 ';
					
					if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
					$sql .= " and n.highlighter_id = '".$andrulesValues['highlighter_id']."'";
					
					}
					
					if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
					
					$sql .= " and nk.keyword_file = '".$andrulesValues['keyword_image']."'";
					
					}
					
					if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
						
						$sql .= " and n.text_color = '#".$andrulesValues['color_id']."'";
					}
					
					if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
						$sql .= " and LOWER(n.notes_description) like '%".strtolower($andrulesValues['keyword_search'])."%'";
					}
					
					$sql .= " and n.facilities_id = '".$facility['facilities_id']."'";
					$sql .= " and n.`snooze_dismiss` != '2' ";
					
					$date = str_replace('-', '/', $searchdate);
					$res = explode("/", $date);
					$changedDate = $res[2]."-".$res[0]."-".$res[1];
					
					$startDate = $changedDate;
					$endDate = $changedDate;
						
					$sql .= " and (n.`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
						
					
					
					$sql .= " and n.status = '1' ORDER BY n.notetime DESC  ";
					
					//echo "<hr>";
					//echo $sql;
					//echo "<hr>";
					 
					$query = $this->db->query($sql);
					//var_dump($query->num_rows);
					
					//die;
					//echo "<hr>";
					if ($query->num_rows) {
						//var_dump($query->rows);
						//echo "<hr>";
						
						foreach($query->rows as $result){
							$user_info = $this->model_user_user->getUserByUsernamebynotes($result['user_id'], $result['facilities_id']);
							
							if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
								$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
							}
							
							$nrulesvalue = "";
							
							if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
								$nrulesvalue .= 'Highlighter: '.$highlighterData['highlighter_name'].' and ';
							}
							
							
							if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
								
								$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues['keyword_image'] . "'");

								$active_tagdata = $querya->row;
								$nrulesvalue .= ' ActiveNote: '.$active_tagdata['keyword_name'].' and ';
							
							}
							
							if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
								
								if($andrulesValues['color_id'] == '008000'){
									$color_id = "Green";
								}
								if($andrulesValues['color_id'] == 'FF0000'){
									$color_id = "Red";
								}
								if($andrulesValues['color_id'] == '0000FF'){
									$color_id = "Blue";
								}
								if($andrulesValues['color_id'] == '000000'){
									$color_id = "Black";
								}
							
								$nrulesvalue .= ' Color: '.$color_id.' and ';
							}
							
							if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
								$nrulesvalue .= ' Keyword: '.$andrulesValues['keyword_search'].' ';
							}
							
							$allnotesIds[] = array(
								'notes_id' => $result['notes_id'],
								'rules_type' => '',
								'rules_value' =>$nrulesvalue,
							);
						}
					}
					
				}
				
				//var_dump($allnotesIds);
				//var_dump($rule['rules_name']);
				//var_dump($rule['rule_action_content']);
				//echo "<hr>";
				if($allnotesIds != null && $allnotesIds != ""){
					if(in_array('3', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							$notesIds[] = $allnotesId['notes_id'];
						}
					}
					
					if(in_array('1', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							
							$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
							$sqls2 .= 'where 1 = 1 ';
							$sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
							$sqls2 .= " and send_sms = '0'";
							
							$query = $this->db->query($sqls2);
							
							$note_info = $query->row;
							
							if ($query->num_rows) {
								$message = "Rules Created \n";
								$message .= date('h:i A', strtotime($note_info['notetime']))."\n";
								$message .= $rule['rules_name'] .'-'.$allnotesId['rules_type'].'-'.$allnotesId['rules_value']."\n";
								$message .= substr($note_info['notes_description'], 0, 150) .((strlen($note_info['notes_description']) > 150) ? '..' : '');
								
								//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);;
								
								if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
									$phone_number = $user_info['phone_number'];
								}
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
								$query = $this->db->query($sql3e);
								
								$sdata = array();
								$sdata['message'] = $message;
								$sdata['phone_number'] = $phone_number;
								$sdata['facilities_id'] = $facilities_id;	
								$response = $this->model_api_smsapi->sendsms($sdata);
								
								
								
								
								if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
									
									$user_roles1 = $rule['rule_action_content']['auser_roles'];
									
									foreach ($user_roles1 as $user_role) {
										$urole = array();
										$urole['user_group_id'] = $user_role;
										$tusers = $this->model_user_user->getUsers($urole);
										
										if($tusers){
											foreach ($tusers as $tuser) {
												if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
													$number = $tuser['phone_number']; 
													
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = $tuser['phone_number'];
													$sdata['facilities_id'] = $facilities_id;	
													$response = $this->model_api_smsapi->sendsms($sdata);
													
													
												}
											}
										}
									}
									
								}
								
								if($rule['rule_action_content']['auserids'] != null && $rule['rule_action_content']['auserids'] != ""){
									$userids1 = $rule['rule_action_content']['auserids'];
			
									foreach ($userids1 as $userid) {
										$user_info = $this->model_user_user->getUserbyupdate($userid);
										if ($user_info) {
											if($user_info['phone_number'] != 0){
												$number = $user_info['phone_number']; 
												
												$sdata = array();
												$sdata['message'] = $message;
												$sdata['phone_number'] = $user_info['phone_number'];
												$sdata['facilities_id'] = $facilities_id;	
												$response = $this->model_api_smsapi->sendsms($sdata);
												
												
											}
										}
									}
									
								}
								
								
								
							}
						}
					}
					
					if(in_array('2', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							
							$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
							$sqls2 .= 'where 1 = 1 ';
							$sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
							$sqls2 .= " and send_email = '0'";
							
							$query = $this->db->query($sqls2);
							
							$note_info = $query->row;
							
							if ($query->num_rows) {
								
								//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
								
								$facilityDetails['username'] = $result['user_id'];
								$facilityDetails['email'] = $user_info['email'];
								$facilityDetails['phone_number'] = $user_info['phone_number'];
								$facilityDetails['sms_number'] = $facility['sms_number'];
								$facilityDetails['facility'] = $facility['facility'];
								$facilityDetails['address'] = $facility['address'];
								$facilityDetails['location'] = $facility['location'];
								$facilityDetails['zipcode']= $facility['zipcode'];
								$facilityDetails['contry_name'] = $country_info['name'];
								$facilityDetails['zone_name'] = $zone_info['name'];
								$facilityDetails['href'] = $this->url->link('common/login', '', 'SSL');
								$facilityDetails['rules_name'] = $rule['rules_name'];
								$facilityDetails['rules_type'] = $allnotesId['rules_type'];
								$facilityDetails['rules_value'] = $allnotesId['rules_value'];
								
								
								
								$message33 = "";
																
								$message33 .= $this->sendEmailtemplate($note_info, $rule['rules_name'], $allnotesId['rules_type'], $allnotesId['rules_value'], $facilityDetails,"7");
								
								$useremailids = array();
								
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
								$query = $this->db->query($sql3e);
								
								if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
												
									$user_roles1 = $rule['rule_action_content']['auser_roles'];

									foreach ($user_roles1 as $user_role) {
										$urole = array();
										$urole['user_group_id'] = $user_role;
										$tusers = $this->model_user_user->getUsers($urole);
										
										if($tusers){
											foreach ($tusers as $tuser) {
												if($tuser['email'] != null && $tuser['email'] != ""){
												
													$useremailids[] = $tuser['email'];
												}
											}
										}
									}
									
								}
								
								if($rule['rule_action_content']['auserids'] != null && $rule['rule_action_content']['auserids'] != ""){
									$userids1 = $rule['rule_action_content']['auserids'];
			
									foreach ($userids1 as $userid) {
										$user_info = $this->model_user_user->getUserbyupdate($userid);
										if ($user_info) {
											if($user_info['email']){
												$useremailids[] = $user_info['email'];
											}
										}
									}
									
								}
								
								
								if($user_info['email'] != null && $user_info['email'] != ""){
									$user_email = $user_info['email'];
								}
								
								$edata = array();
								$edata['message'] = $message33;
								$edata['subject'] = 'This is an Automated Alert Email.';
								$edata['useremailids'] = $useremailids;
								$edata['user_email'] = $user_email;
								$edata['type'] = "7";
									
								$email_status = $this->model_api_emailapi->sendmail($edata);
								
								
								
							}
						}
					}
					
					if(in_array('4', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							
							$tnotesIds[] = $allnotesId['notes_id'];
							
							$rowModule['taskDate'] = $rule['rule_action_content']['taskDate'];
							$rowModule['recurrence'] = $rule['rule_action_content']['recurrence'];
							$rowModule['recurnce_week'] = $rule['rule_action_content']['recurnce_week'];
							$rowModule['recurnce_hrly'] = $rule['rule_action_content']['recurnce_hrly'];
							$rowModule['recurnce_month'] = $rule['rule_action_content']['recurnce_month'];
							$rowModule['recurnce_day'] = $rule['rule_action_content']['recurnce_day'];
							$rowModule['end_recurrence_date'] = $rule['rule_action_content']['end_recurrence_date'];
							$rowModule['taskTime'] = $rule['rule_action_content']['taskTime'];
							$rowModule['endtime'] = $rule['rule_action_content']['endtime'];
							$rowModule['tasktype'] = $rule['rule_action_content']['tasktype'];
							$rowModule['numChecklist'] = $rule['rule_action_content']['numChecklist'];
							$rowModule['task_alert'] = $rule['rule_action_content']['task_alert'];
							$rowModule['alert_type_sms'] = $rule['rule_action_content']['alert_type_sms'];
							$rowModule['alert_type_notification'] = $rule['rule_action_content']['alert_type_notification'];
							$rowModule['alert_type_email'] = $rule['rule_action_content']['alert_type_email'];
							$rowModule['description'] = $rule['rule_action_content']['description'];
							
							$rowModule['assignto'] = $rule['rule_action_content']['assign_to'];
							$rowModule['facilities_id'] = $facilities_id;
							$rowModule['task_form_id'] = $rule['rule_action_content']['task_form_id'];
							$rowModule['transport_tags'] = $rule['rule_action_content']['transport_tags'];
							$rowModule['pickup_facilities_id'] = $rule['rule_action_content']['pickup_facilities_id'];
							$rowModule['pickup_locations_address'] = $rule['rule_action_content']['pickup_locations_address'];
							$rowModule['pickup_locations_time'] = $rule['rule_action_content']['pickup_locations_time'];
							$rowModule['dropoff_facilities_id'] = $rule['rule_action_content']['dropoff_facilities_id'];
							$rowModule['dropoff_locations_address'] = $rule['rule_action_content']['dropoff_locations_address'];
							$rowModule['dropoff_locations_time'] = $rule['rule_action_content']['dropoff_locations_time'];
							
							
							$rowModule['recurnce_hrly_recurnce'] = $rule['rule_action_content']['recurnce_hrly_recurnce'];
							$rowModule['daily_endtime'] = $rule['rule_action_content']['daily_endtime'];
							$rowModule['daily_times'] = $rule['rule_action_content']['daily_times'];
							$rowModule['medication_tags'] = $rule['rule_action_content']['medication_tags'];
							$rowModule['tags_medication_details_ids'] = $rule['rule_action_content']['tags_medication_details_ids'];
							$rowModule['emp_tag_id'] = $rule['rule_action_content']['emp_tag_id'];
							$rowModule['recurnce_hrly_perpetual'] = $rule['rule_action_content']['recurnce_hrly_perpetual'];
							$rowModule['completion_alert'] = $rule['rule_action_content']['completion_alert'];
							$rowModule['completion_alert_type_sms'] = $rule['rule_action_content']['completion_alert_type_sms'];
							$rowModule['completion_alert_type_email'] = $rule['rule_action_content']['completion_alert_type_email'];
							$rowModule['user_roles'] = $rule['rule_action_content']['user_roles'];
							$rowModule['userids'] = $rule['rule_action_content']['userids'];
							$rowModule['task_status'] = $rule['rule_action_content']['task_status'];
							$rowModule['visitation_tag_id'] = $rule['rule_action_content']['visitation_tag_id'];
							$rowModule['visitation_tags'] = $rule['rule_action_content']['visitation_tags'];
							$rowModule['visitation_start_facilities_id'] = $rule['rule_action_content']['visitation_start_facilities_id'];
							$rowModule['visitation_start_address'] = $rule['rule_action_content']['visitation_start_address'];
							$rowModule['visitation_start_time'] = $rule['rule_action_content']['visitation_start_time'];
							$rowModule['visitation_appoitment_facilities_id'] = $rule['rule_action_content']['visitation_appoitment_facilities_id'];
							$rowModule['visitation_appoitment_address'] = $rule['rule_action_content']['visitation_appoitment_address'];
							$rowModule['visitation_appoitment_time'] = $rule['rule_action_content']['visitation_appoitment_time'];
							$rowModule['complete_endtime'] = $rule['rule_action_content']['complete_endtime'];
							$rowModule['completed_times'] = $rule['rule_action_content']['completed_times'];
							
							$rowModule['completed_alert'] = $rule['rule_action_content']['completed_alert'];
							$rowModule['completed_late_alert'] = $rule['rule_action_content']['completed_late_alert'];
							$rowModule['incomplete_alert'] = $rule['rule_action_content']['incomplete_alert'];
							$rowModule['deleted_alert'] = $rule['rule_action_content']['deleted_alert'];
							$rowModule['attachement_form'] = $rule['rule_action_content']['attachement_form'];
							$rowModule['tasktype_form_id'] = $rule['rule_action_content']['tasktype_form_id'];
							
							$rowModule['reminder_alert'] = $rule['rule_action_content']['reminder_alert'];
							if($rule['rule_action_content']['reminderminus'] != null && $rule['rule_action_content']['reminderminus'] !=""){
								$rowModule['reminderminus'] =  explode(',',$rule['rule_action_content']['reminderminus']);
							}
							
							if($rule['rule_action_content']['reminderplus'] != null && $rule['rule_action_content']['reminderplus'] !=""){
								$rowModule['reminderplus'] =  explode(',',$rule['rule_action_content']['reminderplus']);
							}
							
							$rowModule['assign_to_type'] = $rule['rule_action_content']['assign_to_type'];
							if($rule['rule_action_content']['user_assign_to'] != null && $rule['rule_action_content']['user_assign_to'] !=""){
								$rowModule['assign_to'] =  explode(',',$rule['rule_action_content']['user_assign_to']);
							}
							
							if($rule['rule_action_content']['user_role_assign_ids'] != null && $rule['rule_action_content']['user_role_assign_ids'] !=""){
								$rowModule['user_role_assign_ids'] =  explode(',',$rule['rule_action_content']['user_role_assign_ids']);
							}
						
							
						}
					}
					
					
					if(in_array('5', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							if( $rule['rule_action_content']['highlighter_id'] != null &&  $rule['rule_action_content']['highlighter_id'] != ""){
								$update_date = date('Y-m-d H:i:s', strtotime('now')); 
								$this->model_notes_notes->updateNoteHigh($allnotesId['notes_id'], $rule['rule_action_content']['highlighter_id'], $update_date);
							}
						}
						
					}
					
					if(in_array('6', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							if( $rule['rule_action_content']['color_id'] != null &&  $rule['rule_action_content']['color_id'] != ""){
								$update_date = date('Y-m-d H:i:s', strtotime('now')); 
								$this->model_notes_notes->updateNoteColor($allnotesId['notes_id'], $rule['rule_action_content']['color_id'], $update_date);
							}
							
						}
						
					}
				}
			}
		}
		
		
		//var_dump($notesIds);
		//die;
		//var_dump($facilityDetails);
		//var_dump($json['rulenotes']);
		
		//echo "<hr>";
		//var_dump($andRuleArray);
		//echo "<hr>";
		//var_dump($rowModule);
		$tnotesIds = array_unique($tnotesIds);
		//echo "<hr>";
		//var_dump($tnotesIds);
		//die;
		
		
		if($tnotesIds != null && $tnotesIds != ""){
			$this->load->model('createtask/createtask');
			$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$tnotesIds).") and status = '1' and text_color_cut = '0' and `snooze_dismiss` != '2' ";
			
			$query2 = $this->db->query($sqlst2);
			
			$thestime6 = date('H:i:s');
			//var_dump($thestime6);
			$snooze_time7 = 60;
			$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
			//var_dump($stime8);
			
			
			foreach($query2->rows as $tresult){
				
				$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$tresult['notes_id']."' ";
				$query4 = $this->db->query($sqls23);
				
				
				
				if($query4->num_rows == 0){
					$addtask = array();
					
					/*if($rowModule['taskTime'] != null && $rowModule['taskTime'] != ""){
						$snooze_time71 = 0;
						$thestime61 = $rowModule['taskTime'];
					}else{
						$snooze_time71 = 10;
						$thestime61 = date('H:i:s');
					}*/
					
					$snooze_time71 = 1;
					$thestime61 = date('H:i:s');
					
					
					
					$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
					
					$addtask['taskDate'] = date('m-d-Y', strtotime($tresult['date_added']));
					$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($tresult['date_added']));
					$addtask['recurrence'] = $rowModule['recurrence'];
					$addtask['recurnce_week'] = $rowModule['recurnce_week'];
					$addtask['recurnce_hrly'] = $rowModule['recurnce_hrly'];
					$addtask['recurnce_month'] = $rowModule['recurnce_month'];
					$addtask['recurnce_day'] = $rowModule['recurnce_day'];
					$addtask['taskTime'] = $taskTime; //date('H:i:s');
					$addtask['endtime'] = $stime8;
					
					$notes_description123 = substr($tresult['notes_description'], 0, 150) .((strlen($tresult['notes_description']) > 150) ? '..' : '');
					
					$addtask['description'] = $rowModule['description'].' '.$notes_description123;
					
					if($rowModule['assign_to']){
						$addtask['assignto'] = $rowModule['assign_to'];
					}else{
						$addtask['assignto'] = $result['user_id'];
					}
													
					$addtask['facilities_id'] = $rowModule['facilities_id'];
					$addtask['task_form_id'] = $rowModule['task_form_id'];
					if($rowModule['transport_tags'] != null && $rowModule['transport_tags'] !=""){
						$addtask['transport_tags'] = explode(',',$rowModule['transport_tags']);
					}
																	
					$addtask['pickup_facilities_id'] = $rowModule['pickup_facilities_id'];
					$addtask['pickup_locations_address'] = $rowModule['pickup_locations_address'];
					$addtask['pickup_locations_time'] = $rowModule['pickup_locations_time'];
																	
					$addtask['dropoff_facilities_id'] = $rowModule['dropoff_facilities_id'];
					$addtask['dropoff_locations_address'] = $rowModule['dropoff_locations_address'];
					$addtask['dropoff_locations_time'] = $rowModule['dropoff_locations_time'];
					
					$addtask['tasktype'] = $rowModule['tasktype'];
					$addtask['numChecklist'] = $rowModule['numChecklist'];
					$addtask['task_alert'] = $rowModule['task_alert'];
					$addtask['alert_type_sms'] = $rowModule['alert_type_sms'];
					$addtask['alert_type_notification'] = $rowModule['alert_type_notification'];
					$addtask['alert_type_email'] = $rowModule['alert_type_email'];
					$addtask['rules_task'] = $tresult['notes_id'];
					
					
					$addtask['recurnce_hrly_recurnce'] = $rowModule['recurnce_hrly_recurnce'];
					$addtask['daily_endtime'] = $rowModule['daily_endtime'];
					
					if($rowModule['daily_times'] != null && $rowModule['daily_times'] !=""){
						$addtask['daily_times'] =  explode(',',$rowModule['daily_times']);
					}
					
					if($rowModule['medication_tags'] != null && $rowModule['medication_tags'] !=""){
						$addtask['medication_tags'] =  explode(',',$rowModule['medication_tags']);
					
					
						$aa  = urldecode($rowModule['tags_medication_details_ids']); 
						$aa1  = unserialize($aa); 
										
						$tags_medication_details_ids = array();
						foreach($aa1 as $key=>$mresult){
							$tags_medication_details_ids[$key] = $mresult;
						}
						$addtask['tags_medication_details_ids'] = $tags_medication_details_ids;
					
					}
					
					$addtask['emp_tag_id'] = $rowModule['emp_tag_id'];
					
					$addtask['recurnce_hrly_perpetual'] = $rowModule['recurnce_hrly_perpetual'];
					$addtask['completion_alert'] = $rowModule['completion_alert'];
					$addtask['completion_alert_type_sms'] = $rowModule['completion_alert_type_sms'];
					$addtask['completion_alert_type_email'] = $rowModule['completion_alert_type_email'];
					
					if($rowModule['user_roles'] != null && $rowModule['user_roles'] !=""){
						$addtask['user_roles'] =  explode(',',$rowModule['user_roles']);
					}
					
					if($rowModule['userids'] != null && $rowModule['userids'] !=""){
						$addtask['userids'] =  explode(',',$rowModule['userids']);
					}
					$addtask['task_status'] = $rowModule['task_status'];
					
					$addtask['visitation_tag_id'] = $rowModule['visitation_tag_id'];
					
					if($rowModule['visitation_tags'] != null && $rowModule['visitation_tags'] !=""){
						$addtask['visitation_tags'] =  explode(',',$rowModule['visitation_tags']);
					}
					$addtask['visitation_start_facilities_id'] = $rowModule['visitation_start_facilities_id'];
					$addtask['visitation_start_address'] = $rowModule['visitation_start_address'];
					$addtask['visitation_start_time'] = $rowModule['visitation_start_time'];
					$addtask['visitation_appoitment_facilities_id'] = $rowModule['visitation_appoitment_facilities_id'];
					$addtask['visitation_appoitment_address'] = $rowModule['visitation_appoitment_address'];
					$addtask['visitation_appoitment_time'] = $rowModule['visitation_appoitment_time'];
					$addtask['complete_endtime'] = $rowModule['complete_endtime'];
					
					if($rowModule['completed_times'] != null && $rowModule['completed_times'] !=""){
						$addtask['completed_times'] =  explode(',',$rowModule['completed_times']);
					}
					$addtask['completed_alert'] = $rowModule['completed_alert'];
					$addtask['completed_late_alert'] = $rowModule['completed_late_alert'];
					$addtask['incomplete_alert'] = $rowModule['incomplete_alert'];
					$addtask['deleted_alert'] = $rowModule['deleted_alert'];
					$addtask['attachement_form'] = $rowModule['attachement_form'];
					$addtask['tasktype_form_id'] = $rowModule['tasktype_form_id'];
					
					$addtask['reminder_alert'] = $rowModule['reminder_alert'];
					if($rowModule['reminderminus'] != null && $rowModule['reminderminus'] !=""){
						$addtask['reminderminus'] =  explode(',',$rowModule['reminderminus']);
					}
					
					if($rowModule['reminderplus'] != null && $rowModule['reminderplus'] !=""){
						$addtask['reminderplus'] =  explode(',',$rowModule['reminderplus']);
					}
					
					$addtask['assign_to_type'] = $rowModule['assign_to_type'];
					if($rowModule['user_assign_to'] != null && $rowModule['user_assign_to'] !=""){
						$addtask['assign_to'] =  explode(',',$rowModule['user_assign_to']);
					}
					
					if($rowModule['user_role_assign_ids'] != null && $rowModule['user_role_assign_ids'] !=""){
						$addtask['user_role_assign_ids'] =  explode(',',$rowModule['user_role_assign_ids']);
					}
					
					$sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2',form_snooze_dismiss = '2', rule_keyword_task = '1' where notes_id ='".$tresult['notes_id']."'";
					$this->db->query($sqlw); 
						
					$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
					
					//$sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2' where notes_id ='".$tresult['notes_id']."'";
					//$this->db->query($sqlw); 
					
					$rowModule = array();
				}
			}
		}
		
	
		$notesIds = array_unique($notesIds);
	
		if($notesIds != null && $notesIds != ""){
			
			$thestime = date('H:i:s');
			//var_dump($thestime);
			$snooze_time = 0;
			$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
			
			//var_dump($stime);
					
			$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$notesIds).") and snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
			
			$query = $this->db->query($sqls2);
			
			$config_tag_status = $this->customer->isTag();
			
			
			if ($query->num_rows) {
				
				foreach($query->rows as $result){
					
					//echo $thestime.'<='.$result['snooze_time'];
					if($thestime >= $result['snooze_time']){
						$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
						//$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
						$user_info = $this->model_user_user->getUserByUsernamebynotes($result['user_id'], $result['facilities_id']);
						
						if ($config_tag_status == '1') {
							if($result['emp_tag_id'] != null && $result['emp_tag_id'] != ""){
								$tagdata = $this->model_notes_tags->getTagbyEMPID($result['emp_tag_id']);
								$privacy = $tagdata['privacy'];
								
								$emp_tag_id = $result['emp_tag_id'].': ';
							}else{
								$emp_tag_id = '';
								$privacy = '';
							}
						}
						
						
						$notes_description_2 = substr($result['notes_description'], 0, 350) .((strlen($result['notes_description']) > 350) ? '..' : '');
						
						if($privacy == '2'){
							if($this->session->data['unloack_success'] == '1'){
								$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $notes_description_2;
							}else{
								$notes_description = $emp_tag_id;
							}
						}else{
							$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $notes_description_2;
						}
						
						if(!empty($andRuleArray)){
							$note_d = $andRuleArray[$result['notes_id']] .' ';
						}
						
						$json['rulenotes'][] = array(
							'notes_id'    => $result['notes_id'],
							'rules_id'    => '',
							'highlighter_value'   => $highlighterData['highlighter_value'],
							'notes_description'   => $note_d.$notes_description,
							'date_added' => date('j, F Y', strtotime($result['date_added'])),
							'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
							'notetime'   => date('h:i A', strtotime($result['notetime'])),
							'username'      => $result['user_id'],
							'email'      => $user_info['email'],
							'facility'     => $facility['facility'],
							
							'web_audio_file' =>$facility_web_audio_file,
							'is_web_notification' =>$facility['is_web_notification'],
							'web_is_snooze' =>$facility['web_is_snooze'],
							'web_is_dismiss' =>$facility['web_is_dismiss'],
						);
						
						$json['total'] = '1'; 
					}else{
						if($json['rulenotes'] == null && $json['rulenotes'] == ""){
							$json['rulenotes'] = array();
							$json['total'] = '0'; 
						}
					}
					
				}
				
			}else{
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
			}
			
		}else{
			if($json['rulenotes'] == null && $json['rulenotes'] == ""){
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
			}
		}
		
		$config_task_status = $this->customer->isTask();
		
		if($config_task_status == '1'){
		
		
			
		$timeZone = date_default_timezone_set($timezone_name);
				
		$this->load->model('createtask/createtask');
		

		$tasktypes = $this->model_createtask_createtask->getTaskdetails($this->customer->getId());
		
		//var_dump($tasktypes);
		
		foreach($tasktypes as $tasktype){
			
			if($tasktype['web_audio_file'] !=NULL && $tasktype['web_audio_file'] !=""){
				$web_audio_file = HTTP_SERVER .'image/ringtone/'.$tasktype['web_audio_file']; 
			}else{
				$web_audio_file = '';
			}
			
			
		$data1 = array();
				
		$currentdate = date('d-m-Y');
		$data1['currentdate'] = $currentdate;
		$data1['notification'] = '1';
		$data1['top'] = '1';
		$data1['snooze_dismiss'] = '2';
		$data1['send_notification'] = '1';
		$data1['facilities_id'] = $facilities_id;
		$data1['subfacilities_id'] = 1;
		$data1['task_id'] = $tasktype['task_id'];
				
		$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists($data1); 
		$compltetecountTaskLists1 = $compltetecountTaskLists1 +  $compltetecountTaskLists;
		//var_dump($compltetecountTaskLists);
		//echo "<hr>";
		$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists($data1);
		//var_dump($complteteTaskLists);
		//echo "<hr>";
		
		$tthestime = date('H:i:s');
		
		
		$snooze_time = 0;
		$tstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestime)));
		//var_dump($tstime);		
		
		if($compltetecountTaskLists > 0){
			
			
			
			$this->load->model('setting/locations');
			$this->load->model('setting/tags');
			
			foreach($complteteTaskLists as $list){
				if($tthestime >= $list['snooze_time']){
					
					
					$url2 = "";
					if ($list['formreturn_id'] > 0) {
						$url2 .= '&forms_id=' . $list['formreturn_id'];
						
						$this->load->model('form/form');
						$result_info = $this->model_form_form->getFormDatas($list['formreturn_id']);
						if ($result_info['notes_id'] != null && $result_info['notes_id'] != "") {
							$url2 .= '&notes_id=' . $result_info['notes_id'];
						}
					}
					
					
					
					if($list['checklist'] == "incident_form"){
						$incident_form_href = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert&taskrefress=2', '' . 'task_id=' . $list['id']));
						$attachement_form = '0';
					}elseif($list['checklist'] == "bed_check"){
						$bed_check_href = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform&taskrefress=2', '' . 'task_id=' . $list['id']));
						$attachement_form = '0';
						
					}elseif($list['attachement_form'] == '1'){
						$custom_form_href2 = str_replace('&amp;', '&', $this->url->link('form/form&taskrefress=2', '' . 'forms_design_id=' . $list['tasktype_form_id']. '&task_id=' . $list['id']. $url2));
						
						$attachement_form = $list['attachement_form'];
					}elseif(is_numeric($list['checklist'])){
						$custom_form_href = str_replace('&amp;', '&', $this->url->link('form/form&taskrefress=2', '' . 'forms_design_id=' . $list['checklist']. '&task_id=' . $list['id']. $url2));
						
						$attachement_form = '1';
					}elseif($list['enable_requires_approval'] == '2'){
						$custom_form_href2 = str_replace('&amp;', '&', $this->url->link('notes/createtask/approvalurl&taskrefress=2', '' . '&task_id=' . $list['id']));
						
						$attachement_form = $list['attachement_form'];
					}else{
						$insert_href = str_replace('&amp;', '&', $this->url->link('notes/createtask/inserttask&taskrefress=2', '' . 'task_id=' . $list['id']));
						$attachement_form = '0';
					}
					
					$bedcheckdata = array();
				
					if($list['task_form_id'] != 0 && $list['task_form_id'] != NULL ){
						
						
						if($list['bed_check_location_ids'] != null && $list['bed_check_location_ids'] != ""){
							$formDatas = $this->model_setting_locations->getformid2($list['bed_check_location_ids']);	
						}else{
							$formDatas = $this->model_setting_locations->getformid($list['task_form_id']);	
						}
							
						foreach($formDatas as $formData){
							
							
							$locData = $this->model_setting_locations->getlocation($formData['locations_id']);
						
								$locationDatab = array();
								$location_type = "";
							
								$location_typea = $locData['location_type'];
								if($location_typea == '1'){
									$location_type .= "Boys";
								} 
								
								if($location_typea == '2'){
									$location_type .= "Girls";
								}
								
								if($location_typea == '3'){
									$location_type .= "Inmates";
								}
							
							
							if($locData['upload_file'] != null && $locData['upload_file'] != ""){
									$upload_file = $locData['upload_file'];
								}else{
									$upload_file = "";
								}
								$locationDatab[] = array(
									'locations_id' =>$locData['locations_id'],
									'location_name' =>$locData['location_name'],
									'location_address' =>$locData['location_address'],
									'location_detail' =>$locData['location_detail'],
									'capacity' =>$locData['capacity'],
									'location_type' =>$location_type,
									'upload_file' =>$upload_file,
									'nfc_location_tag' =>$locData['nfc_location_tag'],
									'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
									'gps_location_tag' =>$locData['gps_location_tag'],
									'gps_location_tag_required' =>$locData['gps_location_tag_required'],
									'latitude' =>$locData['latitude'],
									'longitude' =>$locData['longitude'],
									'other_location_tag' =>$locData['other_location_tag'],
									'other_location_tag_required' =>$locData['other_location_tag_required'],
									'other_type_id' =>$locData['other_type_id'],
									'facilities_id' =>$locData['facilities_id']
									
								);
							
							
							  
							$bedcheckdata[] = array(
								'task_form_location_id' =>$formData['task_form_location_id'],
								'location_name' =>$formData['location_name'],
								'location_detail' =>$formData['location_detail'],
								'current_occupency' =>$formData['current_occupency'],
								'bedcheck_locations' =>$locationDatab
								);
						}
					
						/*$this->load->model('setting/bedchecktaskform');
						$taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
					
						foreach($taskformData  as $frmData){
							$taskformsData[] = array(
							'task_form_name' =>$frmData['task_form_name'],
							'facilities_id' =>$frmData['facilities_id'],
							'form_type' =>$frmData['form_type']
							);
						}*/
					 
					}
					 $medications = array(); 
					
					/*if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
						$tags_info = $this->model_setting_tags->getTag($list['tags_id']);
						$locationData = array();
						$locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
					
							$locationData[] = array(
								'locations_id' =>$locData['locations_id'],
								'location_name' =>$locData['location_name'],
								'location_address' =>$locData['location_address'],
								'location_detail' =>$locData['location_detail'],
								'capacity' =>$locData['capacity'],
								'location_type' =>$locData['location_type'],
								'nfc_location_tag' =>$locData['nfc_location_tag'],
								'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
								'gps_location_tag' =>$locData['gps_location_tag'],
								'gps_location_tag_required' =>$locData['gps_location_tag_required'],
								'latitude' =>$locData['latitude'],
								'longitude' =>$locData['longitude'],
								'other_location_tag' =>$locData['other_location_tag'],
								'other_location_tag_required' =>$locData['other_location_tag_required'],
								'other_type_id' =>$locData['other_type_id'],
								'facilities_id' =>$locData['facilities_id']
								
							);
							
							
							if($tags_info['upload_file'] != null && $tags_info['upload_file'] != ""){
									$upload_file2 = $tags_info['upload_file'];
								}else{
									$upload_file2 = "";
								}
							
							
							$drugaData = array();
							$drugDatas = $this->model_setting_tags->getDrugs($list['id']);
						
							foreach($drugDatas as $drugData){
								$drugaData[] = array(
									'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
									'facilities_id' =>$drugData['facilities_id'],
									'locations_id' =>$drugData['locations_id'],
									'tags_id' =>$drugData['tags_id'],
									'medication_id' =>$drugData['medication_id'],
									'drug_name' =>$drugData['drug_name'],
									'dose' =>$drugData['dose'],
									'drug_type' =>$drugData['drug_type'],
									'quantity' =>$drugData['quantity'],
									'frequency' =>$drugData['frequency'],
									'start_time' =>$drugData['start_time'],
									'instructions' =>$drugData['instructions'],
									'count' =>$drugData['count'],
									'complete_status' =>$drugData['complete_status'],
									'upload_file' =>$upload_file2,
								);
							}
						
						
						$medications[] = array(
								'tags_id' =>$tags_info['tags_id'],
								'upload_file' =>$upload_file2,
								'emp_tag_id' =>$tags_info['emp_tag_id'],
								'emp_first_name' =>$tags_info['emp_first_name'],
								'emp_last_name' =>$tags_info['emp_last_name'],
								'doctor_name' =>$tags_info['doctor_name'],
								'emergency_contact' =>$tags_info['emergency_contact'],
								'dob' =>$tags_info['dob'],
								'medications_locations' =>$locationData,
								'medications_drugs' =>$drugaData
								);
								
					}*/
					
					$transport_tags = array();
					$this->load->model('setting/tags');
					
					if (!empty($list['transport_tags'])) {		
						$transport_tags1 = explode(',',$list['transport_tags']);
					} else {
						$transport_tags1 = array();
					}

					foreach ($transport_tags1 as $tag1) {
						$tags_info = $this->model_setting_tags->getTag($tag1);

						if($tags_info['emp_first_name']){
							$emp_tag_id = $tags_info['emp_tag_id'].': '.$tags_info['emp_first_name'].' '. $tags_info['emp_last_name'];
						}else{
							$emp_tag_id = $tags_info['emp_tag_id'];
						}
						
						if ($tags_info) {
							$transport_tags[] = array(
								'tags_id' => $tags_info['tags_id'],
								'emp_tag_id'        => $emp_tag_id
							);
						}
					}
					
					$medication_tags = array();
					$this->data['medication_tags'] = array();
					$this->load->model('setting/tags');
					
					if (!empty($list['medication_tags'])) {		
						$medication_tags1 = explode(',',$list['medication_tags']);
					} else {
						$medication_tags1 = array();
					}

					foreach ($medication_tags1 as $medicationtag) {
						$tags_info1 = $this->model_setting_tags->getTag($medicationtag);

						if($tags_info1['emp_first_name']){
							$emp_tag_id = $tags_info1['emp_tag_id'].': '.$tags_info1['emp_first_name'].' '. $tags_info1['emp_last_name'];
						}else{
							$emp_tag_id = $tags_info1['emp_tag_id'];
						}
						
						if ($tags_info1) {
							
							$drugs = array();
							
							$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($list['id'], $medicationtag);
							
							foreach($mdrugs as $mdrug){
								
								$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($mdrug['tags_medication_details_id']);
								
								$drugs[] = array(
									'drug_name' =>$mdrug_info['drug_name']
								);
							}
							
							
							$medication_tags[] = array(
								'tags_id' => $tags_info1['tags_id'],
								'emp_tag_id'        => $emp_tag_id,
								'tagsmedications' => $drugs, 
							);
						}
					}
					
					
					if($list['visitation_tag_id']){
						$visitation_tag = $this->model_setting_tags->getTag($list['visitation_tag_id']);
						
						if($visitation_tag['emp_first_name']){
							$visitation_tag_id = $visitation_tag['emp_tag_id'].': '.$visitation_tag['emp_first_name'].' '. $visitation_tag['emp_last_name'];
						}else{
							$visitation_tag_id = $visitation_tag['emp_tag_id'];
						}
					}
					
					
					
					$taskstarttime = date('H:i:s', strtotime($list['task_time']));
				
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($list['tasktype'],$list['facilityId']);
					
					if($tasktype_info['custom_completion_rule'] == '1'){
						$addTime = $tasktype_info['config_task_complete'];
					}else{
						$addTime = $this->config->get('config_task_complete');
					}
					
					$currenttimePlus = date('H:i:s', strtotime(' +'.$addTime.' minutes',strtotime('now')));
					
					if($tasktype_info['task_id'] != '10'){
						if($currenttimePlus >= $taskstarttime){
							$taskDuration = '1';
						}else{
							if($list['is_pause'] == '1'){
								$taskDuration = '1';
							}else{
								$taskDuration = '2';
							}
						}
					}else{
						$taskDuration = '1';
					}
					
					$json['tasklits'][] = array(
					'taskDuration' =>$taskDuration,
					'assign_to' =>$list['assign_to'],
					'tasktype' =>$list['tasktype'],
					'attachement_form' =>$list['attachement_form'],
					'enable_requires_approval' =>$list['enable_requires_approval'],
					'tasktype_form_id' =>$list['tasktype_form_id'],
					'checklist' =>$list['checklist'],
					'date' => date('j, M Y', strtotime($list['task_date'])),
					'id' =>$list['id'],
					'description' =>$list['description'],
					'task_time' =>date('h:i A', strtotime($list['task_time'])),
					'strice_href' => str_replace('&amp;', '&', $this->url->link('notes/createtask/updateStriketask', '' . 'task_id=' . $list['id'])),
					'incident_form_href' => $incident_form_href,
					'bed_check_href' => $bed_check_href,
					
					'insert_href' => $insert_href,
					'task_form_id' =>  $list['task_form_id'],
					'tags_id' =>$list['tags_id'],
					'pickup_facilities_id' => $list['pickup_facilities_id'],
					'pickup_locations_address' =>$list['pickup_locations_address'],
					'pickup_locations_time' =>$list['pickup_locations_time'],
					'pickup_locations_latitude' =>$list['pickup_locations_latitude'],
					'pickup_locations_longitude' =>$list['pickup_locations_longitude'],
					'dropoff_facilities_id' =>$list['dropoff_facilities_id'],
					'dropoff_locations_address' =>$list['dropoff_locations_address'],
					'dropoff_locations_time' =>$list['dropoff_locations_time'],
					'dropoff_locations_latitude' =>$list['dropoff_locations_latitude'],
					'dropoff_locations_longitude' =>$list['dropoff_locations_longitude'],
					'transport_tags' =>$transport_tags,
					'medications' =>$medications,
					'bedchecks' =>$bedcheckdata,
					
					'custom_form_href' => $custom_form_href,
					'custom_form_href2' => $custom_form_href2,
					'medication_tags' =>$medication_tags,
					'visitation_tags' => $list['visitation_tags'],
					'visitation_tag_id' =>$visitation_tag_id,
					'visitation_start_facilities_id' =>$list['visitation_start_facilities_id'],
					'visitation_start_address' =>$list['visitation_start_address'],
					'visitation_start_time' =>date('h:i A', strtotime($list['visitation_start_time'])),
					'visitation_start_address_latitude' =>$list['visitation_start_address_latitude'],
					'visitation_start_address_longitude' =>$list['visitation_start_address_longitude'],
					'visitation_appoitment_facilities_id' =>$list['visitation_appoitment_facilities_id'],
					'visitation_appoitment_address' =>$list['visitation_appoitment_address'],
					'visitation_appoitment_time' =>date('h:i A', strtotime($list['visitation_appoitment_time'])),
					'visitation_appoitment_address_latitude' =>$list['visitation_appoitment_address_latitude'],
					'visitation_appoitment_address_longitude' =>$list['visitation_appoitment_address_longitude'],
					
					'web_audio_file' =>$web_audio_file,
					'is_web_notification' =>$tasktype['is_web_notification'],
					'web_is_snooze' =>$tasktype['web_is_snooze'],
					'web_is_dismiss' =>$tasktype['web_is_dismiss'],
					
					
					
					
					);
					
					$json['total'] = $compltetecountTaskLists1;
					
					
					$sql32 = "UPDATE `" . DB_PREFIX . "createtask` SET send_notification = '1' WHERE id = '".$list['id']."'";			
					$query = $this->db->query($sql32);	
					
				}
				/*else{
					$json['tasklits'] = array();
					
				}*/
			}
					
			
		}else{
			//$json['tasklits'] = array();
		}
		
		}
		  
		//var_dump($json['tasklits']);
		
		//$json['status'] = true;
		
		
		$datasms1 = array();
				
		$currentdate = date('d-m-Y');
		$datasms1['currentdate'] = $currentdate;
		$datasms1['alert_type_sms'] = '1';
		$datasms1['top'] = '1';
		//$datasms1['send_sms'] = '1';
		$datasms1['facilities_id'] = $facilities_id;
				
		$compltetecountsmsTaskLists = $this->model_createtask_createtask->getCountallTaskLists($datasms1); 
		
		//var_dump($compltetecountsmsTaskLists);
				
		$compltetesmsTaskLists = $this->model_createtask_createtask->getallTaskLists($datasms1);
		
		//var_dump($compltetesmsTaskLists);
		
		$tthestimes = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tsmsstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestimes)));
		
		if($compltetecountsmsTaskLists > 0){
			//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
			foreach($compltetesmsTaskLists as $task){
				
				if($task['send_sms'] == '0'){
					$username = $task['assign_to'];
					$this->load->model('user/user');
					$this->load->model('setting/tags');
					$userData = $this->model_user_user->getUser($username);
									
									
					if($userData['phone_number'] != 0){
						$phone_number = $userData['phone_number'];
					}
					//var_dump($phone_number);
					
									
					$message = "Task due at ".date('h:i A',strtotime($task['task_time']))."...\n";
					$message .= "Task Type: ". $task['tasktype']."\n";
					
					if($task['emp_tag_id'] != null && $task['emp_tag_id'] != ""){
							$tags_info1 = $this->model_setting_tags->getTag($task['emp_tag_id']);
						
							if($tags_info1['emp_first_name']){
								$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
								
							if ($tags_info1) {
								$message .= "Client Name: ". $emp_tag_id."\n";
							}
					}
					
					if($task['medication_tags'] != null && $task['medication_tags'] != ""){
						$tags_info1 = $this->model_setting_tags->getTag($task['medication_tags']);
							if($tags_info1['emp_first_name']){
								$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
								
							if ($tags_info1) {
								$message .= "Client Name: ". $emp_tag_id."\n";
							}
					}
					if($task['visitation_tag_id'] != null && $task['visitation_tag_id'] != ""){
						$tags_info1 = $this->model_setting_tags->getTag($task['visitation_tag_id']);
							if($tags_info1['emp_first_name']){
								$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
								
							if ($tags_info1) {
								$message .= "Client Name: ". $emp_tag_id."\n";
							}
					}
					if($task['transport_tags'] != null && $task['transport_tags'] != ""){
						
						$transport_tags1 = explode(',',$task['transport_tags']);
						
						$transport_tags = '';
						foreach ($transport_tags1 as $tag1) {
							$tags_info1 = $this->model_setting_tags->getTag($tag1);

							if($tags_info1['emp_first_name']){
								$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
								
							if ($tags_info1) {
								$transport_tags .= $emp_tag_id.', ';

							}
						}
						
						$message .= "Client Name: ". $transport_tags."\n";
					}
									
					$message .= "Description: ".substr($task['description'], 0, 150) .((strlen($task['description']) > 150) ? '..' : '')."\n";
					//$message .= "______________________\n";
					//$message .= "REPLY WITH ID ".$task['id']."@ to Mark it complete.";
					
					$sdata = array();
					$sdata['message'] = $message;
					$sdata['phone_number'] = $phone_number;
					$sdata['facilities_id'] = $facilities_id;
					//$sdata['is_task'] = 1;
					$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET message_sid = '".$response->sid."', send_sms = '1' WHERE id = '".$task['id']."'";			
					$query = $this->db->query($sql3);	
						
					$response = $this->model_api_smsapi->sendsms($sdata);
					
					
										
					
				
				}
			}
		}
		
		
		$dataemail1 = array();
				
		$currentdate = date('d-m-Y');
		$dataemail1['currentdate'] = $currentdate;
		$dataemail1['alert_type_email'] = '1';
		$dataemail1['top'] = '1';
		//$dataemail1['send_email'] = '1';
		$dataemail1['facilities_id'] = $facilities_id;
				
		$compltetecountemailTaskLists = $this->model_createtask_createtask->getCountallTaskLists($dataemail1); 
				
		$complteteemailTaskLists = $this->model_createtask_createtask->getallTaskLists($dataemail1);
		
		$tthestimes = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tsmsstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestimes)));
		
		if($compltetecountemailTaskLists > 0){
			foreach($complteteemailTaskLists as $task){
				
				if($task['send_email'] == '0'){
					$message33 = "";

					$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
					$message33 .= $this->taskemailtemplate($task, $task['date_added'], $task['task_time'],$facilities_info['facility'],"1");
					
					//var_dump($message33);die;
					
					if($task['assign_to'] !="" && $task['assign_to']!= NULL){
						$username = $task['assign_to'];
						$this->load->model('user/user');
						$userEmail = $this->model_user_user->getUser($username);

						//$mail->addAddress($userEmail['email']); 
						//$mail->addAddress('app-monitoring@noteactive.com'); 
						if($userEmail['email'] != null && $userEmail['email'] != ""){
							$user_email = $userEmail['email']; 
						}
					}
					
					$edata = array();
					$edata['message'] = $message33;
					$edata['subject'] = 'Task has been assigned to you';
					$edata['user_email'] = $user_email;
					
					$sql3e = "UPDATE `" . DB_PREFIX . "createtask` SET send_email = '1' WHERE id = '".$task['id']."'";			
					$query = $this->db->query($sql3e);
					
					$email_status = $this->model_api_emailapi->sendmail($edata);
					
					
				
				}
			}
		}
		
		}
		//var_dump($json);
		
		
		if($this->config->get('active_notification') == '1'){
			if( (!empty($json['tasklits'])) || (!empty($json['rulenotes'])) || (!empty($json['formrules'])) ){
				$this->load->model('api/notify');
				
				$this->load->model('notes/device');
				$device_detail = $this->model_notes_device->getdeviceweb($this->session->data['session_key'], $this->session->data['activationkey'], $this->customer->getId());
				
				$this->model_api_notify->websendnotification($json, $device_detail['token']);
			}
		}
		
		if($this->config->get('active_notification') == '2'){
			
			$template = new Template();
			$template->data['ruleresults'] = $json;
			$template->data['config_task_deleted_time'] = $this->config->get('config_task_deleted_time');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/notification/rules.php')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/notification/rules.php');
			} 
			
			//var_dump($html);
			
			$json1 = array();
			$json1['html'] = $html;
			$json1['total'] = $json['total'];
			
			$this->response->setOutput(json_encode($json1));
		}
		
		
  	}
	
	public function updateNotification(){
		$json = array();
		
		
		
		if(($this->request->post["notes_id"] == null && $this->request->post["notes_id"] == "") && ($this->request->post["task_id"] == null && $this->request->post["task_id"] == "") && ($this->request->post["rules_id"] == null && $this->request->post["rules_id"] == "")){
			$json['warning'] = 'Please check the checkbox';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			if($this->request->post["notes_id"] != null && $this->request->post["notes_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['notes_id'] as $notes_id) {
						$snooze_time = $this->request->post['snooze_time'];
						
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_time = '" . $stime . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['notes_id'] as $notes_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_dismiss = '2',form_snooze_dismiss = '2' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			
			if($this->request->post["rules_id"] != null && $this->request->post["rules_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['rules_id'] as $rules_id) {
						
						$sql = "SELECT * FROM " . DB_PREFIX . "rules where rules_id = '" . (int)$rules_id . "' and status='1' and snooze_dismiss != '2' ";
						$query = $this->db->query($sql);
						
						$snooze_time = $this->request->post['snooze_time'];
						
						$thestime = $query->row['rules_operation_time'] ; //date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "rules` SET rules_operation_time = '" . $stime . "' WHERE rules_id = '" . (int)$rules_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['rules_id'] as $rules_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "rules` SET snooze_dismiss = '2' WHERE rules_id = '" . (int)$rules_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			if($this->request->post["task_id"] != null && $this->request->post["task_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['task_id'] as $task_id) {
						$snooze_time = $this->request->post['snooze_time'];
					
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						 $sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_time = '" . $stime . "' WHERE id = '" . (int)$task_id . "' ";
						
						
						$this->db->query($sqlsn);
						
						$sql32 = "UPDATE `" . DB_PREFIX . "createtask` SET send_notification = '0' WHERE id = '".$task_id."'";			
						$query = $this->db->query($sql32);	
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update task successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['task_id'] as $task_id) {
						$sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_dismiss = '2' WHERE id = '" . (int)$task_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss task successfully!';
				}
			}
		}
		
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function taskemailtemplate($result, $taskDate, $taskeTiming,$facility,$type){

		 if($type != null && $type !=""){

     	 $edata = array();
		 $edata['type'] = $type;

		 $emailData = $this->model_api_notification->getEmailData($edata);	

	    }


		$html = "";

		 if($emailData['type']=="1" && $type=="1"){
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Task has been assigned to you</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Task has been assigned to you</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$result['assignto'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you '.$result['tasktype'].'. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>';

			if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['description'].'
							</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['what_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		}if($emailData['when_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.date('j, F Y', strtotime($taskDate)).'&nbsp;'.date('h:i A',strtotime($taskeTiming)).'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['when_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		}
		if($emailData['who_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'.
						$result['assignto'].'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		}if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$facility.'
							</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['where_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		}$html.='<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>

		
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						
						<td align="left" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_footer"].'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';		

}
return $html;
}

	public function sendEmailtemplate($result, $ruleName, $ruleType, $rulevalue, $facilityData,$type){

		 if($type != null && $type !=""){

     	 $edata = array();
		 $edata['type'] = $type;

		 $emailData = $this->model_api_notification->getEmailData($edata);	

	    }
		$html = "";

		 if($emailData['type']=="7" && $type=="7"){


		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>'.$emailData['email_header'].'</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData['email_header'].'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$facilityData['username'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$ruleName.'! Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>';

			 if($emailData['what_check']=="1"){


			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$facilityData['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$ruleType.'- '.$rulevalue.'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.substr($result['notes_description'], 0, 350) .((strlen($result['notes_description']) > 350) ? '..' : '').'
							</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['what_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		};

		 if($emailData['when_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.date('j, F Y', strtotime($result['date_added'])).'&nbsp;'.date('h:i A', strtotime($result['notetime'])).'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['when_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		};
		 if($emailData['where_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$facilityData['facility'].'&nbsp;'.$facilityData['address'].'&nbsp;'.$facilityData['location'].'&nbsp;'.$facilityData['zone_name'].'&nbsp;'.$facilityData['zipcode'].', '.$facilityData['contry_name'].'
						</p>
						<br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['where_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		};
		if($emailData['who_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'.
						$facilityData['username'].'
						</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>
				</tr>
			</table></div>';
		};
		$html.='<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>

		
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						
						<td align="left" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_footer"].'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';

return $html;			

}

}

	
	public function notificationweb(){
		
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationKey'] = $this->session->data['activationkey'];
		$datafa['session_id'] = $this->session->data['session_key'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		$datafa['browser'] = $this->request->server['HTTP_USER_AGENT'];
		$datafa['token'] = $this->request->post['regId'];
		
		$this->load->model('notes/device');
		
		$this->model_notes_device->registerwebdevice($datafa);
	}
	
}
?>