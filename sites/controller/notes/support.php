<?php   
class Controllernotessupport extends Controller {
	private $error = array();
	
		public function index() {
			$this->load->model('facilities/online');
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->customer->getId();
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				
				$this->load->model('notes/support');
				$support_id = $this->model_notes_support->addsupport($this->request->post, $this->customer->getId());
					
				$sdata = array();
				$subject = "open ticket-". date('m-d-Y-H-i-s'); 
						
				$sdata['subject'] = $subject;
				$sdata['support_id'] = $support_id;
				$sdata['notes_description'] = $this->request->post['comment'];
				$sdata['user_id'] = $this->request->post['user_id'];
						
				
				$sresults = $this->model_notes_support->addsupportticket($sdata);
				$this->model_notes_support->updatesupport($sdata);
				$this->model_notes_support->updatesupportTicket($sdata, $sresults->ticket_id);
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);
					
					$message33 = "";
					//$message33 .= $this->emailtemplate($this->request->post, $sresults->ticket_id, '1');	
					
					$message33  .= "Facility: ".$this->customer->getfacility() . "<br>";
					$message33  .= "Username: ".$this->request->post['user_id'] . "<br>";
					$message33  .= "Url: ".HTTPS_SERVER . "<br>";
					$message33  .= " ".$this->request->post['comment'] . "<br>";
					$message33  .= "Email: ".$user_info['email'] . "<br>";
					
					
					
					
					$this->load->model('api/emailapi');
				
					
					$this->load->model('api/emailapi');

					 $edata = array();
                       $edata['message'] = $message;
                    $edata['facility'] = $this->customer->getfacility() ;
                   $edata['user_email'] = $this->config->get('config_support_email');
					$edata['asupport_attachment_images'] = explode(",",$this->request->post['support_attachment_image']);
                    $edata['when_date']=date("l");
                    $edata['who_user']=$this->request->post['user_id'];
                    $edata['type']="13";
					$edata['subject'] = $subject;
                    //$email_status = $this->model_api_emailapi->createMails($edata);
					$email_status = $this->model_api_emailapi->sendmail($edata);

					
				
				$this->session->data['success'] = $this->language->get('text_success');
			}
			
			
		$this->data['action'] = $this->url->link('notes/support', '' , 'SSL');
		//$this->load->model('notes/support');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['subject'])) {
			$this->data['error_subject'] = $this->error['subject'];
		} else {
			$this->data['error_subject'] = '';
		}
		
		if (isset($this->error['comment'])) {
			$this->data['error_comment'] = $this->error['comment'];
		} else {
			$this->data['error_comment'] = '';
		}
		
		/*if (isset($this->request->post['facility'])) {
			$this->data['facility'] = $this->request->post['facility'];
		} else {
			$this->data['facility'] = $this->customer->getfacility();
		}*/
		
		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		/*
		if (isset($this->request->post['facilities_id'])) {
			$this->data['facilities_id'] = $this->request->post['facilities_id'];
		} else {
			$this->data['facilities_id'] = $this->customer->getId();
		}*/
		
		if (isset($this->request->post['subject'])) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		if (isset($this->request->post['support_by'])) {
			$this->data['support_by'] = $this->request->post['support_by'];
		} else {
			$this->data['support_by'] = 'by_email';
		}
		if (isset($this->request->post['support_attachment_image'])) {
			$this->data['support_attachment_image'] = $this->request->post['support_attachment_image'];
		} else {
			$this->data['support_attachment_image'] = '';
		}
		
		$this->template = $this->config->get('config_template') . '/template/notes/support.php';
		
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());
		}
		
		
		public function validateForm() {
		    
		    if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
		        $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
		    }
			if ($this->request->post['comment'] == NULL && $this->request->post['comment'] == "") {
				$this->error['comment'] = 'This is required field';
			}
			
			/*if ($this->request->post['subject'] == NULL && $this->request->post['subject'] == "") {
				$this->error['subject'] = 'This is required field';
			}*/
			
			if (!$this->error) {
				return true;
			} else {
				return false;
			}
			
		}
		
		public function emailtemplate($result, $ticket_id, $type){
		$html = "";
		
		
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Support has been created</title>

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
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Support has been created</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello Support!</h1>
							
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Support Email Created</small></h4>';
							
							if($type == '1'){
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Facility:</b> '.$this->customer->getfacility().'<br>
								<b>Username:</b> '.$result['user_id'].'<br>
								<b>Ticket ID:</b> '.$ticket_id.'<br>
								<b>Url:</b> '.HTTPS_SERVER.'<br>
								<b>Comment:</b> '.$result['comment'].'
								</p>';
							
							}
							
							if($type == '2'){
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Company:</b> '.$result['Company_name'].'<br>
								<b>Username:</b> '.$result['username'].'<br>
								<b>Ticket ID:</b> '.$ticket_id.'<br>
								<b>Email ID:</b> '.$result['email'].'<br>
								<b>Contact:</b> '.$result['Contact_no'].'<br>
								<b>Address:</b> '.$result['Address'].'<br>
								<b>Asset ID:</b> '.$result['asset_id'].'<br>
								
								<b>Alternate Email:</b> '.$result['alertnate_email'].'<br>
								<b>Customer ID:</b> '.$result['customer_id'].'<br>
								<b>Customer Number:</b> '.$result['customer_number'].'<br>
								
								<b>Url:</b> '.str_replace("index.php?route=services/","",$result['Server_url']).'<br>
								<b>Comment:</b> '.$result['notes_description'].'
								</p>';
							}
							
						$html .= '</td>
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
	
	
	public function uploadFile(){
		unset($this->session->data['timeout']);
		$json = array();
		$this->data['form_outputkey'] = $this->formkey->outputKey();
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		if($this->request->files["file"] != null && $this->request->files["file"] != ""){

			$extension = end(explode(".", $this->request->files["file"]["name"]));

			if($this->request->files["file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
				//if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
					
					$support_image = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'support/' . $support_image;
					$outputFolder_url = HTTPS_SERVER.'image/support/' . $support_image;
					
					
					move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);
					
					$json['success'] = '1';
					$json['notes_file'] = $support_image;
					$json['notes_file_url'] = $outputFolder_url;
			
				/*}else{
					$json['error'] = 'video or audio file not valid!';
				}*/
			}else{
					$json['error'] = 'Maximum size file upload!';
			}

		}else{
			$json['error'] = 'Please select file!';
		}


		$this->response->setOutput(json_encode($json));
	}
			
	public function createTicket(){
		$request = array_merge($_GET, $_POST);
		session_start();
		//var_dump($request);
		//echo "<hr>"; 
		
		$this->load->model('notes/support');
					
		
		if($request['Body'] != null && $request['Body'] != ""){
			
			$this->load->model('notes/notes');

			 $to = str_replace("+","",$request['To']);
			 $from = str_replace("+","",$request['From']);
			 $sid = $request['MessageSid'];
			 $Accountid = $request['AccountSid'];
			 $notedata = $request['Body'];
			 $attachment = $request['MediaUrl0'];
			 
			 $config_support_number = $this->config->get('config_support_number');
			 
			 require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
			
				$this->load->model('user/user'); 
				$query = "SELECT * FROM " . DB_PREFIX . "user where phone_number = '".$from."' ";
				$user_Data = $this->db->query($query);
				
				//var_dump($user_Data);
				
				if($user_Data->row ==null && $user_Data->row == ""){
					$smstext = "Please provide your Asset or Customer ID which should be on a label on the back of your tablet ";
					//echo "NO USER";
					$number = '+'.$from;
					$from = "+".$to;
					$response = $client->messages->create(
						$number,
						array(
						'from' => $config_support_number,
						'body' => $smstext
						)
					);
					echo $smstext;  
					return;
				}
				
				$notes_description = str_replace("'","&#039;", html_entity_decode($notedata, ENT_QUOTES));
				
				//var_dump($notes_description);
				//echo "<hr>";
				
				$support_infobynumber = $this->model_notes_support->getsupportbynumber($to);
				//var_dump($support_infobynumber);
				//echo "<hr>";
				
				
				switch ($notes_description) {
					case "Yes":
					
						//var_dump($_SESSION['support_confirmation']);
						//echo "<hr>";
						
						if($_SESSION['support_confirmation'] == null && $_SESSION['support_confirmation'] == ""){
							if($support_infobynumber){
								$_SESSION['support_id'] = $support_infobynumber['support_id'];
								$cname = $support_infobynumber['user_id'];
								$email = $support_infobynumber['email'];
						
								$_SESSION['support_confirmation_exiting_no'] = '1';
								$this->model_notes_support->updatesupportStatus($_SESSION['support_id']);
								
								$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
								
								$support_id = $this->model_notes_support->addsupportBySMSbyExiting($support_info, $to);
								
								$_SESSION['support_id'] = $support_id;
								
								$_SESSION['support_confirmation'] = $notes_description;
								
								$smstext = "Great! Thank you. Let's begin. Please describe in detail your issue.";
								$number = '+'.$from;
								$from = "+".$to;
								$response = $client->messages->create(
									$number,
									array(
									'from' => $config_support_number,
									'body' => $smstext
									)
								);
								echo $smstext;
								return;
							}
						}
						
						if($_SESSION['support_confirmation'] == null && $_SESSION['support_confirmation'] == ""){
							$fresult_info = $this->model_notes_support->getfaqs($notes_description);
							//echo "<hr>";
							//var_dump($fresult_info); 
							
							if($fresult_info){
								if($fresult_info['questions'] == 'Yes'){
									$_SESSION['support_id'] = $support_infobynumber['support_id'];
									$cname = $support_infobynumber['user_id'];
									$email = $support_infobynumber['email'];
							
									$_SESSION['support_confirmation_exiting_no'] = '1';
									$this->model_notes_support->updatesupportStatus($_SESSION['support_id']);
									
									$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
									
									$support_id = $this->model_notes_support->addsupportBySMSbyExiting($support_info, $to);
									
									$_SESSION['support_id'] = $support_id;
									
									$_SESSION['support_confirmation'] = $notes_description;
									
										
									$smstext = "Great! Thank you. Let's begin. Please describe in detail your issue."; //"Please text us your issue. ";
									$number = '+'.$from;
									$from = "+".$to;
									$response = $client->messages->create(
										$number,
										array(
										'from' => $config_support_number,
										'body' => $smstext
										)
									);
									echo $smstext;
									return;
								}
									
							}
						}
						
						if($_SESSION['support_id'] != '' && $_SESSION['support_id'] != null){
							if($_SESSION['support_email_update'] == null && $_SESSION['support_email_update'] == ""){
								if($notes_description == 'Yes'){
								
									$_SESSION['support_confirmation_email'] = '1';
									
									$smstext = "Please confirm Yes/No."; 
									$number = '+'.$from;
									$from = "+".$to;
									$response = $client->messages->create(
										$number,
										array(
										'from' => $config_support_number,
										'body' => $smstext
										)
									);
									echo $smstext;
									return;
								}
							}
						}
						break;
					case "No":
						
						
						if($support_infobynumber){
							if($_SESSION['support_confirmation_exiting_no'] == null && $_SESSION['support_confirmation_exiting_no'] == ""){
								$_SESSION['support_id'] = $support_infobynumber['support_id'];
								$cname = $support_infobynumber['user_id'];
								$email = $support_infobynumber['email'];
						
								$_SESSION['support_confirmation_exiting_no'] = '1';
								$this->model_notes_support->updatesupportStatus($_SESSION['support_id']);
								
								$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
								
								$support_id = $this->model_notes_support->addsupportBySMSbyExiting($support_info, $to);
								
								$_SESSION['support_id'] = $support_id;
								
								$smstext = "Great! Thank you ".$cname." Here is the email I have. ".$email.". I will be sending updates on this email. Is this correct? Yes/No";
									
									$number = '+'.$from;
									//$from = "+".$to;
										
									$response = $client->messages->create(
										$number,
										array(
										'from' => $config_support_number,
										'body' => $smstext
										)
									);
									echo $smstext;
									return;
							}
								
							if($_SESSION['support_id'] != '' && $_SESSION['support_id'] != null){
								if($_SESSION['support_confirmation_exiting_no'] != null && $_SESSION['support_confirmation_exiting_no'] != ""){
									
									if($notes_description == 'No'){
										$_SESSION['support_email_no'] = '1';
										$smstext = 'Please provide your email id for this issue.'; 
										$number = '+'.$from;
										$from = "+".$to;
										$response = $client->messages->create(
											$number,
											array(
											'from' => $config_support_number,
											'body' => $smstext
											)
										);
										echo $smstext;
										return;
									}
								}
							}
						}
						
						if($_SESSION['support_confirmation_exiting_no'] == null && $_SESSION['support_confirmation_exiting_no'] == ""){
							$fresult_info = $this->model_notes_support->getfaqs($notes_description);
							//echo "<hr>";
							//var_dump($fresult_info); 
						
							if($fresult_info){
								if($_SESSION['support_email_no'] != '' && $_SESSION['support_email_no'] != null){
									if($fresult_info['questions'] == 'No'){
										$this->model_notes_support->deletesupportHistory($_SESSION['support_id']);
										unset($_SESSION['support_confirmation']);
										unset($_SESSION['support_id']);
										unset($_SESSION['ucounter']);
										unset($_SESSION['fcounter']);
										
										unset($_SESSION['support_confirmation_email']);
										unset($_SESSION['support_email_no']);
										unset($_SESSION['support_email_update']);
										unset($_SESSION['support_confirmation_1']);
										unset($_SESSION['support_confirmation_exiting_no']);
										
										$smstext = 'Please provide an email we can contact you on for this issue.'; 
										$number = '+'.$from;
										$from = "+".$to;
										$response = $client->messages->create(
											$number,
											array(
											'from' => $config_support_number,
											'body' => $smstext
											)
										);
										echo $smstext;
										return;
									}
								}
							
							}
						}
					
						if($_SESSION['support_id'] != '' && $_SESSION['support_id'] != null){
							if($_SESSION['support_confirmation'] == null && $_SESSION['support_confirmation'] == ""){
								
								if($notes_description == 'No'){
									$_SESSION['support_email_no'] = '1';
									$smstext = 'Please provide your email id for this issue.'; 
									$number = '+'.$from;
									$from = "+".$to;
									$response = $client->messages->create(
										$number,
										array(
										'from' => $config_support_number,
										'body' => $smstext
										)
									);
									echo $smstext;
									return;
								}
							}
						}
						break;
					default:
						
						if($support_infobynumber){
							if($_SESSION['support_confirmation'] != 'Yes'){
								if($_SESSION['support_email_no'] == null && $_SESSION['support_email_no'] == ""){
									$_SESSION['support_id'] = $support_infobynumber['support_id'];
									$cname = $support_infobynumber['user_id'];
									$email = $support_infobynumber['email'];
									
									if($fcounter == null && $fcounter == ""){
								
										if(!strlen($fcounter)) {
											$fcounter = 0;
										}
										
										$fcounter++;
										
										$_SESSION['fcounter'] = $fcounter;
										 
										$smstext = "Thank you  ".$cname.", shall I submit the above text as a new issue? Yes/No ";
										$number = '+'.$from;
										$from = "+".$to;
										$response = $client->messages->create(
											$number,
											array(
											'from' => $config_support_number,
											'body' => $smstext
											)
										);
									
										echo $smstext;
										return;
									}
								}
							}
						}
						
						if($_SESSION['support_email_no'] != null && $_SESSION['support_email_no'] != ""){
							if(preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $notes_description)){
								$_SESSION['support_email_update'] = '1';
								$this->model_notes_support->addalernatesupportEmail($notes_description, $_SESSION['support_id']);
								$smstext = "Please confirm Yes/No.";  
								$number = '+'.$from;
								$from = "+".$to;
								$response = $client->messages->create(
									$number,
									array(
									'from' => $config_support_number,
									'body' => $smstext
									)
								);
								echo $smstext;
								return;
							}
						}
						
						//var_dump($_SESSION['support_id']);
						//var_dump($_SESSION['support_confirmation']);
						
						if($_SESSION['support_id'] != '' && $_SESSION['support_id'] != null){
							$fresult_info = $this->model_notes_support->getfaqs($notes_description);
							//echo "<hr>";
							//var_dump($fresult_info); 
							
							if($fresult_info){
								
								$this->model_notes_support->addsupportHistory($fresult_info, $_SESSION['support_id']);
								
							}
						
							if($_SESSION['support_confirmation'] == 'Yes'){
								$shistories = $this->model_notes_support->getsupportHistories($_SESSION['support_id']);
								
								if($shistories){
									$hdata = "";
									foreach($shistories as $shistory){
										$hdata .= $shistory['comment']. ' ';
									}
								}
								
								$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
								
								$subject = "open ticket-". date('m-d-Y-H-i-s').'-'.$support_info['Company_name']; 
								
								$sdata = array();
								$sdata['support_id'] = $_SESSION['support_id'];
								
								$html2 = '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Company:</b> '.$support_info['Company_name'].'<br>
								<b>Username:</b> '.$support_info['user_id'].'<br>
								<b>Email ID:</b> '.$support_info['email'].'<br>
								<b>Contact:</b> '.$support_info['Contact_no'].'<br>
								<b>Address:</b> '.$support_info['Address'].'<br>
								<b>Asset ID:</b> '.$support_info['asset_id'].'<br>
								
								<b>Alternate Email:</b> '.$support_info['alertnate_email'].'<br>
								<b>Customer ID:</b> '.$support_info['customer_id'].'<br>
								<b>Customer Number:</b> '.$to.'<br>
								<b>Url:</b> '.str_replace("index.php?route=services/","",$support_info['Server_url']).'<br>
								<b>Comment:</b> '.$notes_description .' '.$hdata.'
								</p>';
								
								$sdata['notes_description'] = $html2;
								
								$sdata['subject'] = $subject;
								$sdata['user_id'] = $support_info['support_id'];
								$sdata['username'] = $support_info['user_id'];
								$sdata['email'] = $support_info['email'];
								$sdata['Contact_no'] = $support_info['Contact_no'];
								$sdata['Company_name'] = $support_info['Company_name'];
								$sdata['Address'] = $support_info['Address'];
								$sdata['Server_url'] = $support_info['Server_url'];
								$sdata['customer_id'] = $support_info['customer_id'];
								$sdata['asset_id'] = $support_info['asset_id'];
								$sdata['alertnate_email'] = $support_info['alertnate_email'];
								$sdata['support_server_id'] = $support_info['support_server_id'];
							
								$sdata['support_by'] = 'by_sms';   
								$sdata['customer_number'] = $to;   
								
								//var_dump($sdata);
								
								$sresults = $this->model_notes_support->addsupportticket($sdata);
								$this->model_notes_support->updatesupport($sdata);
								$this->model_notes_support->updatesupportTicket($sdata, $sresults->ticket_id);
								
								if($sresults->success == '1'){
									$smstext = "Thank you for contacting us and we apologize for any inconvenience. We have reported your issue to the support team. We will get back to you shortly.";
									
									unset($_SESSION['support_confirmation']);
									unset($_SESSION['support_id']);
									unset($_SESSION['ucounter']);
									unset($_SESSION['fcounter']);
									
									unset($_SESSION['support_confirmation_email']);
									unset($_SESSION['support_email_no']);
									unset($_SESSION['support_email_update']);
									unset($_SESSION['support_confirmation_1']);
									unset($_SESSION['support_confirmation_exiting_no']);
									
									$number = '+'.$from;
									$from = "+".$to;
									$response = $client->messages->create(
										$number,
										array(
										'from' => $config_support_number,
										'body' => $smstext
										)
									);
									
									
									$message33 = "";
									$message33 .= $this->emailtemplate($sdata, $sresults->ticket_id, '2');	

									if($this->config->get('config_support_email') != null && $this->config->get('config_support_email') != ""){
										$user_email = $this->config->get('config_support_email'); 
									}
									
									$this->load->model('api/emailapi');
									
									 $sdata = array();
								
								
								
								$sdata['user_id'] = $support_info['support_id'];
								$sdata['username'] = $support_info['user_id'];
								$sdata['email'] = $support_info['email'];
								$sdata['Contact_no'] = $support_info['Contact_no'];
								$sdata['Company_name'] = $support_info['Company_name'];
								$sdata['Address'] = $support_info['Address'];
								$sdata['support_type'] = "2";
								$sdata['Server_url'] = $support_info['Server_url'];
								$sdata['customer_id'] = $support_info['customer_id'];
								$sdata['asset_id'] = $support_info['asset_id'];
								$sdata['alertnate_email'] = $support_info['alertnate_email'];
								$sdata['support_server_id'] = $support_info['support_server_id'];
							
								$sdata['support_by'] = 'by_call';   
								$sdata['customer_number'] = $from;
								$sdata['type'] = "14"; 
									
									//$edata = array();
									//$edata['message'] = $message33;
									//$edata['subject'] = 'Support has been created';
								$sdata['user_email'] = $user_email;
										
						$email_status = $this->model_api_emailapi->creaMails($sdata);
									
									
									
									return;
								}
								
							}
						}
					
					
					
						$customer_infos = $this->model_notes_support->getLicenceDetailBykey($notes_description);
						
						//var_dump($customer_infos);
						//echo "<hr>";
						
						if($customer_infos->status){
							foreach($customer_infos->result as $sresult){
									
								$support_id = $this->model_notes_support->addsupportBySMS($sresult, $to);
									
								//var_dump($support_id);

								break;
							}
								
							$_SESSION['support_id'] = $support_id;
							if($support_id != null && $support_id != ""){
								//var_dump($support_id);
								
								$smstext = "Great! Thank you ".$sresult->FName." ".$sresult->LName." Here is the email I have. ".$sresult->Email_add.". I will be sending updates on this email. Is this correct? Yes/No";
								//echo $smstext;	
								$number = '+'.$from;
								//$from = "+".$to;
									
								$response = $client->messages->create(
									$number,
									array(
									'from' => $config_support_number,
									'body' => $smstext
									)
								);
								echo $smstext;
								return;
							}
								
						}
						
						 
				}
				
				$ucounter = $_SESSION['ucounter'];

						if(!strlen($ucounter)) {
							$ucounter = 0;
						}
						$ucounter++;

						$_SESSION['ucounter'] = $ucounter;
						if($_SESSION['ucounter'] >= 5){
							//var_dump($_SESSION['ucounter']);
							$smstext = "Hello! Please call support number (850) 559-2228.";
								
							$number = '+'.$from;
							//$from = "+".$to;
							
							$response = $client->messages->create(
								$number,
								array(
								'from' => $config_support_number,
								'body' => $smstext
								)
							);
							echo $smstext;	
							return;
						}
						
						//echo "<hr>222222222";
						//var_dump($_SESSION['support_id']); 
						if($_SESSION['support_id'] == null && $_SESSION['support_id'] == ""){
							$smstext = "Hello! I am your automated support ticket creation system. Before we begin, please provide me with your Asset or Customer ID. It should be attached to the back of your device.";
								
								$number = '+'.$from;
								//$from = "+".$to;
									
								$response = $client->messages->create(
									$number,
									array(
									'from' => $config_support_number,
									'body' => $smstext
									)
								);
								echo $smstext;
								return;
						}
			
		}
	}
		

	public function callrecording(){
		$request = array_merge($_GET, $_POST);
		session_start();
		//var_dump($request);
		//echo "<hr>"; 
		header('Content-type: text/xml');
		
		$this->load->model('notes/support');
			$this->load->model('notes/notes');
	//	if($request['From'] != null && $request['From'] != ""){
			 $to = str_replace("+","",$request['To']);
			 $from = str_replace("+","",$request['From']);
			 $callsid = $request['CallSid'];
			 $Accountid = $request['AccountSid'];
			 $notedata = $request['Body'];
			 $attachment = $request['MediaUrl0'];
			 
			 $config_support_number = $this->config->get('config_support_number');
			 
			 require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
			
				$this->load->model('user/user'); 
				$query = "SELECT * FROM " . DB_PREFIX . "user where phone_number = '".$from."' ";
				$user_Data = $this->db->query($query);
				
				//var_dump($user_Data);
				
				if($user_Data->row ==null && $user_Data->row == ""){
					$smstext = "Please provide your Asset or Customer ID which should be on a label on the back of your tablet ";
					//echo "NO USER";
					$number = '+'.$from;
					$from = "+".$to;
					$response = $client->messages->create(
						$number,
						array(
						'from' => $config_support_number,
						'body' => $smstext
						)
					);
					//echo $smstext;  
					return;
				}
				
				$notes_description = str_replace("'","&#039;", html_entity_decode($notedata, ENT_QUOTES));
				
				//var_dump($notes_description);
				//echo "<hr>";
				
				$support_infobynumber = $this->model_notes_support->getsupportbynumber($from);
				//var_dump($support_infobynumber);
				//echo "<hr>"; 
				if($support_infobynumber){
					$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
					$support_id = $this->model_notes_support->addsupportBySMSbyExiting($support_info, $from);
					$_SESSION['support_id'] = $support_id;
					
				}else{
					$support_id = $this->model_notes_support->addsupportBySMSbyExiting($support_infobynumber, $from);
					$_SESSION['support_id'] = $support_id;
				}
				switch ($notes_description) {
					case "Yes":
						echo 'Yes';
					break;
					case "No":
						echo 'No';
					break;
					
					default:
						
						
						$response = new Twilio\Twiml();

						$response->say('This is NoteActive Support Voicemail system. Please leave a detailed message with your customer name, asset ID, and a detailed message. We will get back to you shortly.');
						
						//$response->play('http://demo.noteactive.com/image/support/Recording.mp3');
						
						/*, array('voice' => 'alice')
						$response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));
						*/
						$result = $response->record(); 
						
						/*'transcribe=true', 'maxLength' => 30 array('transcribe' => true) */
					
						$response->hangup(); 
						
						//$callsid = "CA734431587177d6d972577a15fecc2f45";
						//$callsid = "CAf824f20b59e60cbe43f70c2d7d4f5904";
						//$sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
						//$token = 'b88f54390acfa7e61d3c9b86a84ecb05';
						
						require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
						$client = new Services_Twilio($sid, $token);
						
						
						$urldatas = array();
						
						foreach ($client->account->recordings->getIterator(0, 50, array(
							'CallSid' => $callsid  )) as $recording1
						) {
							
							$urldatas[] = array(
								'recording_url' => $recording1->uri,
							);
							
							/*foreach ($client->account->transcriptions as $transcription) {
								//var_dump($transcription->recording_sid);
								if($recording1->sid == $transcription->recording_sid){
								//echo $transcription->transcription_text;
									$urldatas[] = array(
										'transcription_text' => $transcription->transcription_text,
									);
								}
								//echo "<hr>"; 
							}*/
							
							
							/*$transcriptions = $client->account->transcriptions(array(
							'Sid' => $recording1->sid  )); 
							 
							foreach ($transcriptions as $transcription) { 
								echo $transcription->sid; 
							}*/
							
							
							  
						} 
						$html4 = "";
						if($urldatas){
							foreach($urldatas as $urldata){ 
								
								$html4 .= "<div>".addslashes($urldata['transcription_text'])."</div>";
								$html4 .= '<div>';
								//$html4 .= "<a href='https://api.twilio.com'".$urldata['recording_url']."'.mov'>Recording</a>";
								
								if($urldata['recording_url']){
									$linkurl = 'https://api.twilio.com'.$urldata['recording_url'].".mov";
									$html4 .= '<a href="'.$linkurl.'">Play Recording</a>';
								}
								
								$html4 .= '</div>';  
								
							}
						
								//var_dump($html4);  
						 

								$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
							
								$subject = "Call Support-". date('m-d-Y-H-i-s').'-'.$support_info['Company_name']; 
								
								$sdata = array();
								$sdata['support_id'] = $_SESSION['support_id'];
								
								$html2 = '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Company:</b> '.$support_info['Company_name'].'<br>
								<b>Username:</b> '.$support_info['user_id'].'<br>
								<b>Email ID:</b> '.$support_info['email'].'<br>
								<b>Contact:</b> '.$support_info['Contact_no'].'<br>
								<b>Address:</b> '.$support_info['Address'].'<br>
								<b>Asset ID:</b> '.$support_info['asset_id'].'<br>
								
								<b>Alternate Email:</b> '.$support_info['alertnate_email'].'<br>
								<b>Customer ID:</b> '.$support_info['customer_id'].'<br>
								<b>Customer Number:</b> '.$from.'<br>
								<b>Url:</b> '.str_replace("index.php?route=services/","",$support_info['Server_url']).'<br>
								<b>Comment:</b> '.$html4 .'
								</p>';
								
								$sdata['notes_description'] = $html2;
								
								$sdata['subject'] = $subject;
								$sdata['user_id'] = $support_info['support_id'];
								$sdata['username'] = $support_info['user_id'];
								$sdata['email'] = $support_info['email'];
								$sdata['Contact_no'] = $support_info['Contact_no'];
								$sdata['Company_name'] = $support_info['Company_name'];
								$sdata['Address'] = $support_info['Address'];
								$sdata['Server_url'] = $support_info['Server_url'];
								$sdata['customer_id'] = $support_info['customer_id'];
								$sdata['asset_id'] = $support_info['asset_id'];
								$sdata['alertnate_email'] = $support_info['alertnate_email'];
								$sdata['support_server_id'] = $support_info['support_server_id'];
							
								$sdata['support_by'] = 'by_call';   
								$sdata['customer_number'] = $from;   
								
								//echo "<hr><hr><hr><hr><hr>";
								//var_dump($sdata);
								
								$sresults = $this->model_notes_support->addsupportticket($sdata);
								
								//var_dump($sresults);
								
								$this->model_notes_support->updatesupport($sdata);
								$this->model_notes_support->updatesupportTicket($sdata, $sresults->ticket_id);
								
								$this->model_notes_support->updatesupportStatus($_SESSION['support_id']);
								
								if($sresults->success == '1'){
									unset($_SESSION['support_id']);
									
									
									$message33 = "";
									$message33 .= $this->emailtemplate($sdata, $sresults->ticket_id, '2');
									
									if($this->config->get('config_support_email') != null && $this->config->get('config_support_email') != ""){
										$user_email = $this->config->get('config_support_email'); 
									}
									
									$this->load->model('api/emailapi');
									
									
								$sdata['user_id'] = $support_info['support_id'];
								$sdata['username'] = $support_info['user_id'];
								$sdata['email'] = $support_info['email'];
								$sdata['Contact_no'] = $support_info['Contact_no'];
								$sdata['Company_name'] = $support_info['Company_name'];
								$sdata['Address'] = $support_info['Address'];
								$sdata['support_type'] = "2";
								$sdata['Server_url'] = $support_info['Server_url'];
								$sdata['customer_id'] = $support_info['customer_id'];
								$sdata['asset_id'] = $support_info['asset_id'];
								$sdata['alertnate_email'] = $support_info['alertnate_email'];
								$sdata['support_server_id'] = $support_info['support_server_id'];
							
								$sdata['support_by'] = 'by_call';   
								$sdata['customer_number'] = $from; 
									
									//$edata = array();
								$edata['message'] = $message33;
								$edata['subject'] = 'Support has been created';
								$sdata['user_email'] = $user_email;
								$sdata['type'] = "14";
										
								$email_status = $this->model_api_emailapi->sendmail($sdata);
										
								//$email_status = $this->model_api_emailapi->createMails($sdata);
									
									
									
									
								}
							}
						
						echo $response;
						
				//}
			}
					
	}
}    
