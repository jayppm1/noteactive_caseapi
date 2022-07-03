<?php  
class Controllernotesrules extends Controller {  
	private $error = array();
   
  	public function index() {
		
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		$this->load->model('user/user');
		
		$rules = $this->model_notes_rules->getRules();
		//var_dump($rules);
		//echo "<hr>";
		
		foreach($rules as $rule){
			//$facilitiesids = explode(",", $rule['facilities_id']);
			//var_dump($rule['facilities_id']);
			if($rule['facilities_id'] != null && $rule['facilities_id'] != ""){
				$facilities = $this->model_facilities_facilities->getfacilityByID($rule['facilities_id']);
				//var_dump($facilities);
				//echo "<hr>";
				foreach($facilities as $facility){
					$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
					
					//var_dump($timezone_info['timezone_value']);
					//echo "<hr>";
					date_default_timezone_set($timezone_info['timezone_value']);
					$searchdate =  date('m-d-Y');
					
					$country_info = $this->model_setting_country->getCountry($facility['country_id']);
					$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
				
					//var_dump($rule['rules_operation']);
					//echo "<hr>";
					foreach($rule['rules_module'] as $rules_module){
						
						/*   highlighter  */
						if($rules_module['rules_type'] == '1'){
							
							if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'Highlighter', $highlighterData['highlighter_name']);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
							
						}
						
						if($rules_module['rules_type'] == '2'){
							
							if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
								$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE active_tag = '" . $rules_module['keyword_id'] . "'");
		
								$active_tagdata = $querya->row;
								
								
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and keyword_file = '".$active_tagdata['keyword_image']."'";
								
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'ActiveNote', $rules_module['keyword_id']);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
						}
						
						if($rules_module['rules_type'] == '3'){
							//var_dump($rules_module['color_id']);
							if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and text_color = '#".$rules_module['color_id']."'";
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
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
											
											$this->sendEmail($journals, $rule['rules_name'], 'Color', $color_id);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
							
						}
						
						if($rules_module['rules_type'] == '4'){
							$data4 = array(
								'searchdate' => $searchdate,
								'task_id' => $rules_module['task_id'],
								'searchdate_app' => '1',
							);
							//var_dump($data4);
							$results = $this->model_notes_notes->getnotess($data4);
						}
						
						if($rules_module['rules_type'] == '5'){
							//var_dump($rules_module['keyword_search']);
							if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
								$sqls = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sqls .= 'where 1 = 1 ';
								
								$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
								$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sqls .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sqls .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sqls;
								//echo "<hr>";
								
								$query = $this->db->query($sqls);
								
								if ($query->num_rows) {
									//var_dump($query->rows);
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'Keyword', $rules_module['keyword_search']);
										}
										/*   Notification  */
										
									}
									
									
									
								}
							}
						}
					
						//var_dump($results);
						
						//$keyarrays[] = $rule['rules_module'];
						//var_dump($rule['rules_module']);
						//echo "<hr>";
					}
				}
				
			}
	
		}
		//echo "<hr>";
		
		
		
  	}
	
	public function sendSMS($results) {
		//var_dump($results);
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
			$mail->setFrom('support@noteactive.com', 'Servitium');  
			$mail->addReplyTo('support@noteactive.com', 'Servitium');  
		}
		
		//var_dump($mail);
		//echo "<hr>";
		//var_dump($results);
		
		foreach($results as $result){
			
			$message33 = "";
			$message33 .= $this->emailtemplate($result, $ruleName, $ruleType, $rulevalue);
			
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
				$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
								
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

	public function emailtemplate($result, $ruleName, $ruleType, $rulevalue){
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>This is an Automated Alert Email</title>

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
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$ruleType.'- '.$rulevalue.'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['notes_description'].'
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$result['date_added'].'&nbsp;'.$result['notetime'].'
						</p>
					</td>
				</tr>
			</table></div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$result['facility'].'&nbsp;'.$result['address'].'&nbsp;'.$result['location'].'&nbsp;'.$result['zone_name'].'&nbsp;'.$result['zipcode'].', '.$result['contry_name'].'
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
return $html;
	}

	public function notification() {
		
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		$this->load->model('user/user');
		
		$rules = $this->model_notes_rules->getRules();
		//var_dump($rules);
		//echo "<hr>";
		$json = array();
		foreach($rules as $rule){
			
			$facilitiesids = explode(",", $rule['facilities_id']);
			//var_dump($facilitiesids);
			//echo "<hr>";
			$facilities_id = $this->customer->getId();
			//var_dump($facilities_id);
			
			if($rule['facilities_id'] != null && $rule['facilities_id'] != ""){
				
				if (in_array($facilities_id, $facilitiesids)){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
					
					//$facilities = $this->model_facilities_facilities->getfacilityByID($rule['facilities_id']);
					//var_dump($facilities);
					//echo "<hr>";
					//foreach($facilities as $facility){
						//$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
						
						//var_dump($timezone_info['timezone_value']);
						//echo "<hr>";
						//date_default_timezone_set($timezone_info['timezone_value']);
						$timezone_name = $this->customer->isTimezone();
						date_default_timezone_set($timezone_name);
						$searchdate =  date('m-d-Y');
						
						$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
						
						$country_info = $this->model_setting_country->getCountry($facility['country_id']);
						$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
					
						//var_dump($rule['rules_operation']);
						//echo "<hr>";
						foreach($rule['rules_module'] as $rules_module){
							
							/*   highlighter  */
							if($rules_module['rules_type'] == '1'){
								
								if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
									$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
									
									$sql .= 'where 1 = 1 ';
									
									$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
									$sql .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
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
											$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
											
											$json['rulenotes'][] = array(
												'notes_id'    => $result['notes_id'],
												'highlighter_value'   => $highlighterData['highlighter_value'],
												'notes_description'   => $result['notes_description'],
												'date_added' => date('j, F Y', strtotime($result['date_added'])),
												'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
												'notetime'   => date('h:i A', strtotime($result['notetime'])),
												'username'      => $result['user_id'],
												'email'      => $user_info['email'],
												'facility'     => $facility['facility'],
											);
										
										}
										
										
									}
								}
								
							}
							
							//var_dump($json);
							
							if($rules_module['rules_type'] == '2'){
							
								if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
									$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE active_tag = '" . $rules_module['keyword_id'] . "'");
			
									$active_tagdata = $querya->row;
									
									
									$sql2 = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
									
									$sql2 .= 'where 1 = 1 ';
									
									$sql2 .= " and keyword_file = '".$active_tagdata['keyword_image']."'";
									
									$sql2 .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql2 .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
									$sql2 .= " and status = '1' ORDER BY notetime DESC  ";
									
									//echo $sql;
									//echo "<hr>";
									
									$query = $this->db->query($sql2);
									//var_dump($query->rows);
									//echo "<hr>";
									if ($query->num_rows) {
										
										foreach($query->rows as $result){
											
											$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
											$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
											
											$json['rulenotes'][] = array(
												'notes_id'    => $result['notes_id'],
												'highlighter_value'   => $highlighterData['highlighter_value'],
												'notes_description'   => $result['notes_description'],
												'date_added' => date('j, F Y', strtotime($result['date_added'])),
												'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
												'notetime'   => date('h:i A', strtotime($result['notetime'])),
												'username'      => $result['user_id'],
												'email'      => $user_info['email'],
												'facility'     => $facility['facility'],
											);
										
										}
										
									}
								}
							}
							
							if($rules_module['rules_type'] == '3'){
								//var_dump($rules_module['color_id']);
								if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
									$sql3 = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
									
									$sql3 .= 'where 1 = 1 ';
									
									$sql3 .= " and text_color = '#".$rules_module['color_id']."'";
									$sql3 .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql3 .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
									$sql3 .= " and status = '1' ORDER BY notetime DESC  ";
									
									//echo $sql3;
									//echo "<hr>";
									
									$query = $this->db->query($sql3);
									
									if ($query->num_rows) {
										//var_dump($query->rows);
										//echo "<hr>";
										
										foreach($query->rows as $result){
												$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
												$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
												
												$json['rulenotes'][] = array(
													'notes_id'    => $result['notes_id'],
													'highlighter_value'   => $highlighterData['highlighter_value'],
													'notes_description'   => $result['notes_description'],
													'date_added' => date('j, F Y', strtotime($result['date_added'])),
													'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
													'notetime'   => date('h:i A', strtotime($result['notetime'])),
													'username'      => $result['user_id'],
													'email'      => $user_info['email'],
													'facility'     => $facility['facility'],
												);
												
												
										}
										
										
										
									}
								}
								
							}
							
							/*if($rules_module['rules_type'] == '4'){
								$data4 = array(
									'searchdate' => $searchdate,
									'task_id' => $rules_module['task_id'],
									'searchdate_app' => '1',
								);
								//var_dump($data4);
								$results = $this->model_notes_notes->getnotess($data4);
							}*/
							
							if($rules_module['rules_type'] == '5'){
								//var_dump($rules_module['keyword_search']);
								if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
									$sqls = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
									
									$sqls .= 'where 1 = 1 ';
									
									$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
									$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sqls .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
									$sqls .= " and status = '1' ORDER BY notetime DESC  ";
									
									//echo $sqls;
									//echo "<hr>";
									
									$query = $this->db->query($sqls);
									
									if ($query->num_rows) {
										//var_dump($query->rows);
										//echo "<hr>";
										
										foreach($query->rows as $result){
											$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
											$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
											
											$json['rulenotes'][] = array(
												'notes_id'    => $result['notes_id'],
												'highlighter_value'   => $highlighterData['highlighter_value'],
												'notes_description'   => $result['notes_description'],
												'date_added' => date('j, F Y', strtotime($result['date_added'])),
												'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
												'notetime'   => date('h:i A', strtotime($result['notetime'])),
												'username'      => $result['user_id'],
												'email'      => $user_info['email'],
												'facility'     => $facility['facility'],
											);
											
											
										}
									}
								}
							}
						
							//var_dump($results);
							//$keyarrays[] = $rule['rules_module'];
							//var_dump($rule['rules_module']);
							//echo "<hr>";
						}
					//}
				}
				
			}
	
		}
		
		//var_dump($json);
		
		$json['total'] = '1'; 
		
		$this->response->setOutput(json_encode($json));
		//echo "<hr>";
		
		
		
  	}
	
	
}
?>