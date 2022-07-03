<?php   
class Controllernotessupport extends Controller {
	private $error = array();
	
		public function index() {
			
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
				
				if($this->config->get('config_mail_protocol')  == 'smtp'){			
					$message33 = "";
					$message33 .= $this->emailtemplate($this->request->post, $sresults->ticket_id, '1');	

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
					$mail->setFrom('app-monitoring@noteactive.com', 'Demo');  
					$mail->addReplyTo('app-monitoring@noteactive.com', 'Demo');  
								
					if($this->config->get('config_support_email') != null && $this->config->get('config_support_email') != ""){
						$mail->addAddress($this->config->get('config_support_email')); 
					}else{
						$mail->addAddress('app-monitoring@noteactive.com'); 
					}
								
					$mail->WordWrap = 50;                               
					$mail->isHTML(true);                       
									 
					$mail->Subject = 'Support has been created';
					$mail->msgHTML($message33);
					$mail->msgHTML($message33);
									
					$asupport_attachment_images = explode(",",$this->request->post['support_attachment_image']);
				
					if($asupport_attachment_images){
						foreach($asupport_attachment_images as $asupport_attachment_image){
							$mail->addAttachment(DIR_IMAGE.'support/'.$asupport_attachment_image);
						}
					}
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
		
		$this->template = $this->config->get('config_template') . '/template/notes/support.tpl';
		$this->response->setOutput($this->render());
		}
		
		
		public function validateForm() {
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
								<b>Customer ID:</b> '.$result['customer_id'].'<br>
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
				
				//echo "<hr>";
				//var_dump($notes_description);
				
				//echo "<hr>";
				$fresult_info = $this->model_notes_support->getfaqs($notes_description);
				//echo "<hr>";
				//var_dump($fresult_info); 
				
				if($fresult_info){
					//echo $fresult_info['questions'].": ". $fresult_info['answer'];
					
					if($fresult_info['questions'] != 'Yes' && $fresult_info['questions'] != 'No'){
						$this->model_notes_support->addsupportHistory($fresult_info, $_SESSION['support_id']);
					}
					
					if($fresult_info['questions'] == 'Yes'){
					
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
					
					if($fresult_info['questions'] == 'No'){
						$this->model_notes_support->deletesupportHistory($_SESSION['support_id']);
						unset($_SESSION['support_confirmation']);
						unset($_SESSION['support_id']);
						
						$smstext = 'Please provide an email we can contact you on for this issue.'; //$fresult_info['answer']; 
						$number = '+'.$from;
						$from = "+".$to;
						$response = $client->messages->create(
							$number,
							array(
							'from' => $config_support_number,
							'body' => $smstext
							)
						);
						return;
					}
				}
				
				
				$support_infobynumber = $this->model_notes_support->getsupportbynumber($to);
				
				
				if($support_infobynumber){
					var_dump($support_infobynumber);
					
					echo "<hr>";
					
				}
				
				
				
				if($_SESSION['support_id'] != '' && $_SESSION['support_id'] != null){
					
					if($_SESSION['support_confirmation'] == 'Yes'){
						$shistories = $this->model_notes_support->getsupportHistories($_SESSION['support_id']);
						
						if($shistories){
							$hdata = "";
							foreach($shistories as $shistory){
								$hdata .= $shistory['comment']. ' ';
							}
						}
						
						$support_info = $this->model_notes_support->getsupport($_SESSION['support_id']);
						
						$subject = "open ticket-". date('m-d-Y-H-i-s'); 
						
						$sdata = array();
						$sdata['support_id'] = $_SESSION['support_id'];
						$sdata['notes_description'] = $notes_description .' '.$hdata;
						
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
							
							$number = '+'.$from;
							$from = "+".$to;
							$response = $client->messages->create(
								$number,
								array(
								'from' => $config_support_number,
								'body' => $smstext
								)
							);
							
							
							if($this->config->get('config_mail_protocol')  == 'smtp'){			
									$message33 = "";
									$message33 .= $this->emailtemplate($sdata, $sresults->ticket_id, '2');				
									
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
									$mail->setFrom('app-monitoring@noteactive.com', 'Demo');  
									$mail->addReplyTo('app-monitoring@noteactive.com', 'Demo');  
									
									if($this->config->get('config_support_email') != null && $this->config->get('config_support_email') != ""){
										$mail->addAddress($this->config->get('config_support_email')); 
									}else{
										$mail->addAddress('app-monitoring@noteactive.com'); 
									}
									
										$mail->WordWrap = 50;                               
										$mail->isHTML(true);                       
										 
										$mail->Subject = 'Support has been created';
										$mail->msgHTML($message33);
										$mail->msgHTML($message33);
										
										$asupport_attachment_images = explode(",",$this->request->post['support_attachment_image']);
					
										if($asupport_attachment_images){
											foreach($asupport_attachment_images as $asupport_attachment_image){
												$mail->addAttachment(DIR_IMAGE.'support/'.$asupport_attachment_image);
											}
										}
										$mail->send();
										echo $smstext;
										
										/*if(!$mail->send()) {
										   echo 'Task Created';
										   echo 'Mailer Error: ' . $mail->ErrorInfo;
										   exit;
										}
										echo 'Message has been sent';
										die;
										*/
										
							}
							
							return;
						}
						
						//die;
					}
				}
				
				
				if($support_infobynumber == null && $support_infobynumber == ""){
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
							
							var_dump($response);
							
							echo $smstext;
							return;
							
							
						}
							
					}
				}
				//echo "<hr>222222222";
				//var_dump($_SESSION['support_id']); 
				if($_SESSION['support_id'] == null && $_SESSION['support_id'] == ""){
					$smstext = "Hello! I am your automated support ticket creation system. Before we begin, please provide me with your Asset or Customer ID. It should be attached to the back of your device.";
						
						$number = '+'.$from;
						//$from = "+".$to;
						echo $smstext;	
						
						echo "<hr>1111 ".$config_support_number.' ---- '.$number;
						$response = $client->messages->create(
							$number,
							array(
							'from' => $config_support_number,
							'body' => $smstext
							)
						);
						
						var_dump($response); 
						
						return;
				}  
				
			
		}
	}
			
}    
