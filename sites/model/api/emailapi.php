<?php
class Modelapiemailapi extends Model {
	
	public function sendmail($emaildata) {
		
		if($this->config->get('config_mail_protocol')  == 'smtp'){		
		
			require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
				
			$mail = new PHPMailer;
			
			/*$mail->SMTPDebug = 3; */
				 
			$mail->Host = $this->config->get('config_smtp_host');   
				
			//if($this->config->get('config_smtp_auth') == '1'){
				$mail->SMTPAuth = true;                           
			//}
				
			$mail->Username = $this->config->get('config_smtp_username');        
			$mail->Password = $this->config->get('config_smtp_password');               
				
			//if($this->config->get('config_smtp_ssl') == '1'){
				$mail->SMTPSecure = 'tls';                    
			//} 
			
			$mail->IsMAIL();
			
			$mail->IsSMTP();
				
			$mail->Port = $this->config->get('config_smtp_port');                            
			$mail->setFrom('system@noteactive.com', $this->config->get('config_name'));  
			$mail->addReplyTo('system@noteactive.com', $this->config->get('config_name'));  
				
			if($emaildata['user_email'] !="" && $emaildata['user_email']!= NULL){
				$mail->addAddress($emaildata['user_email']); 
			}else{
				if($emaildata['useremailids'] == "" && $emaildata['useremailids'] == NULL){
					//$mail->addAddress('app-monitoring@noteactive.com'); 
				}
			}
			
			if($emaildata['useremailids'] != "" && $emaildata['useremailids'] != NULL){
				foreach($emaildata['useremailids'] as $useremailids){
					$mail->addAddress($useremailids); 
				}
			}
		
				
			if($emaildata['assignto_email'] != "" && $emaildata['assignto_email'] != NULL){
				$mail->addAddress($emaildata['assignto_email']); 
			}
			
			$mail->WordWrap = 50;                               
			$mail->isHTML(true);                       
			 
			$mail->Subject = $emaildata['subject'];
			 
			$mail->msgHTML($emaildata['message']);		
			
			if($emaildata['asupport_attachment_images']){
				foreach($emaildata['asupport_attachment_images'] as $asupport_attachment_image){
					$mail->addAttachment(DIR_IMAGE.'support/'.$asupport_attachment_image);
				}
			}
			
			if($emaildata['filename']){
				$mail->addAttachment($emaildata['dirpath'].$emaildata['filename']);
			}
			/*
			echo "<hr>";
			var_dump($mail);
			echo "<hr>";
			*/
			
			if(!$mail->send()) {
			   //echo 'Mailer Error: ' . $mail->ErrorInfo;
			   
			    //$this->load->model('activity/activity');
				$data['subject'] = $emaildata['subject'];
				$data['user_email'] = $emaildata['user_email'];
				$data['useremailids'] = $emaildata['useremailids'];
				$data['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
				$data['filename'] = $emaildata['filename'];
				//$this->model_activity_activity->addActivitySave('sendmailerror', $data, 'query');
			   
			   //die;
			   return false;
			}else{
				
				//die;
				
				//$this->load->model('activity/activity');
				$data['subject'] = $emaildata['subject'];
				$data['user_email'] = $emaildata['user_email'];
				$data['useremailids'] = $emaildata['useremailids'];
				$data['message'] = $emaildata['message'];
				$data['filename'] = $emaildata['filename'];
				//$this->model_activity_activity->addActivitySave('sendmail', $data, 'query');
				
				return true;
			}
			
			
			//$mail->send();
			
		}
		
		
		if($this->config->get('config_mail_protocol')  == 'sendgrid'){	

			$url = 'https://api.sendgrid.com/';
			$user = $this->config->get('config_smtp_username');
			$pass = $this->config->get('config_smtp_password');

			$emailFrom = "support@noteactive.com";
			
			if($emaildata['user_email'] !="" && $emaildata['user_email']!= NULL){
				$email = $emaildata['user_email'];
			}elseif($emaildata['assignto_email'] != "" && $emaildata['assignto_email'] != NULL){
				$email = $emaildata['assignto_email'];
			}else{
				$email = "app-monitoring@noteactive.com";
			}
			
			if($emaildata['filename']){
				$filePath = $emaildata['dirpath'];
				$fileName = $emaildata['filename'];
				$dirname = $filePath.$fileName;
				$documentList = array(
					$fileName => "@" . realpath($dirname)
				);
			}
			
			if($emaildata['asupport_attachment_images']){
				foreach($emaildata['asupport_attachment_images'] as $asupport_attachment_image){
					//$mail->addAttachment(DIR_IMAGE.'support/'.$asupport_attachment_image);
					$dirname1 = DIR_IMAGE.'support/'.$asupport_attachment_image;
					$documentList = array(
						$asupport_attachment_image => "@" . realpath($dirname1)
					);
				}
			}
			
			
			$params = array(
				'api_user' => $user,
				'api_key' => $pass,
				 'to' => $email,
				 'subject' => $emaildata['subject'],
				 'html' => $emaildata['message'],
				 'text' => '',
				 'from' => $emailFrom,
				// 'files['.$fileName.']' => '@'.$filePath.'/'.$fileName
			 );
			
			
			if(count($documentList)>0){
				foreach($documentList as $fileName=>$documentPath){
					$params['files['.$fileName.']'] =  $documentPath;
				}
			}
			
			//var_dump($params);
			
			
			 $request = $url.'api/mail.send.json';

			 $session = curl_init($request);

			 curl_setopt ($session, CURLOPT_POST, true);
			 curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
			 curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			 curl_setopt($session, CURLOPT_HEADER, false);
			 curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			 
			 $response = curl_exec($session);
			 curl_close($session);
			
			 
		}
	}

	public function createMails($result){
     


      foreach ($result['assign_to'] as $users) {

      	$username = $this->model_api_notification->getUserName($users);

       $usernames[]=$username;
       
       }

      
        if($result['type'] != null && $result['type'] != ""){
		
		  $emailData = $this->model_api_notification->getEmailData($result);	

	    }

	    $html = "";

        if($emailData['type']=="1" && $result['type']=="1"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							}  


							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' ' . $result['tasktype'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';

                         if($result['tasktimearray']!="" && $result['tasktimearray']!=null){

                         $html.= date('j, F Y', strtotime($result['dateRange'])) . ' <br> ';
                          foreach ($result['tasktimearray'] as $taskeTiming) {
            
                         $html .= date('h:i A', strtotime($taskeTiming)) . '<br>';

                          }
                         }else if (($result['weekd']!="" && $result['dateRange']!="" && $result['tasksTiming']!="") && ($result['weekd']!=null && $result['dateRange']!=null && $result['tasksTiming']!=null)){

                        $html.= $result['weekd'] . '&nbsp;' . date('j, F Y', strtotime($result['dateRange'])) . '&nbsp;' . date('h:i A', strtotime($result['tasksTiming'])) ;

                         } else{

                         	 $html.= date('j, F Y', strtotime($result['date_added'])) . '&nbsp;' . date('h:i A', strtotime($result['task_time'])) ;


                         }						
						$html.=' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		}	
			

		$html.='

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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

              $usermeails = array();
              if ($result['user_role_assign_ids'] != "" && $result['user_role_assign_ids'] != NULL) {
					
					foreach ($result['user_role_assign_ids'] as $user_role) {
						$urole = array();
						$urole['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers($urole);
						if($tusers){
							foreach ($tusers as $userid) {
								if ($userid['email'] != null && $userid['email'] != "") {
									$usermeails[] = $userid['email'];
								}								
							}
						}
					}

					if(!empty($usermeails)){
						$edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['useremailids'] = $usermeails;
						//var_dump($edata);
						$email_status = $this->model_api_emailapi->sendmail($edata);
					}

				}


                $usermeails = array();
				if ($result['assign_to'] != "" && $result['assign_to'] != NULL) {
					foreach ($result['assign_to'] as $userid) {
						
						$user_info = $this->model_user_user->getUserbyupdate($userid);
						if ($user_info['email'] != null && $user_info['email'] != "") {
							$usermeails[] = $user_info['email'];
						}						
					}					
					
					if(!empty($usermeails)){
						$edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['useremailids'] = $usermeails;
						//var_dump($edata);
						$email_status = $this->model_api_emailapi->sendmail($edata);
					}
					
				}



} else if($emailData['type']=="2" && $result['type']=="2")
{

$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $emailData['email_header'] . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $emailData['email_header'] . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '; 

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							 $html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $emailData['email_header'] . ' - ' . $emailData['email_header'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}

		if($emailData['what_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['what_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';

		}

		if($emailData['when_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result['task_time'] ) ) . '
						</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['when_message'] . '
							</p>
					</td>
					
				</tr>
			</table></div>';

		}

		if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	
     
      if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';
		}
		$html.='
</table>
<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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


$edata = array ();
$edata ['message'] = $html;
$edata ['subject'] = $emailData['email_subject'];
$edata ['user_email'] = $result ['email'];								
$email_status = $this->model_api_emailapi->sendmail ( $edata );


}else if($emailData['type']=="3" && $result['type']=="3"){

	

$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $emailData['email_header'] . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $emailData['email_header'] . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '; 

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							 $html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $emailData['email_header'] . ' - ' . $emailData['email_header'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}

		if($emailData['what_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['what_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';

		}

		if($emailData['when_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result['task_time'] ) ) . '
						</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['when_message'] . '
							</p>
					</td>
					
				</tr>
			</table></div>';

		}

		if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	
     
      if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';
		}
		$html.='
</table>
<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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


$edata = array ();
$edata ['message'] = $html;
$edata ['subject'] = $emailData['email_subject'];
$edata ['user_email'] = $result ['email'];								
$email_status = $this->model_api_emailapi->sendmail ( $edata );

}else if($emailData['type']=="4" && $result['type']=="4"){

	


$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $emailData['email_header'] . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $emailData['email_header'] . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '; 

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							 $html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $emailData['email_header'] . ' - ' . $emailData['email_header'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}

		if($emailData['what_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['what_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';

		}

		if($emailData['when_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result['task_time'] ) ) . '
						</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['when_message'] . '
							</p>
					</td>
					
				</tr>
			</table></div>';

		}

		if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	
     
      if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';
		}
		$html.='
</table>
<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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


$edata = array ();
$edata ['message'] = $html;
$edata ['subject'] = $emailData['email_subject'];
$edata ['user_email'] = $result ['email'];								
$email_status = $this->model_api_emailapi->sendmail ( $edata );


}else if($emailData['type']=="5" && $result['type']=="5"){


$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $emailData['email_header'] . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $emailData['email_header'] . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '; 

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							 $html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $emailData['email_header'] . ' - ' . $emailData['email_header'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}

		if($emailData['what_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['what_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';

		}

		if($emailData['when_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result['task_time'] ) ) . '
						</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['when_message'] . '
							</p>
					</td>
					
				</tr>
			</table></div>';

		}

		if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	
     
      if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';
		}
		$html.='
</table>
<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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


$edata = array ();
$edata ['message'] = $html;
$edata ['subject'] = $emailData['email_subject'];
$edata ['user_email'] = $result ['email'];								
$email_status = $this->model_api_emailapi->sendmail ( $edata );


}else if($emailData['type']=="6" && $result['type']=="6"){

	

$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData =
			 * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
			 *
			 * foreach($taskformData as $frmData){
			 * $taskformsData[] = array(
			 * 'task_form_name' =>$frmData['task_form_name'],
			 * 'facilities_id' =>$frmData['facilities_id'],
			 * 'form_type' =>$frmData['form_type']
			 * );
			 * }
			 */
		}
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $emailData['email_header'] . '</title>

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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $emailData['email_header'] . '</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '; 

							foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							 $html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $emailData['email_header'] . ' - ' . $emailData['email_header'] . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name:
						// '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}

		if($emailData['what_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['what_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';

		}

		if($emailData['when_check']=="1"){
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $result['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result['task_time'] ) ) . '
						</p></br>

							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['when_message'] . '
							</p>
					</td>
					
				</tr>
			</table></div>';

		}		
     
      if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
							</p>
						</td>
						
					</tr>
				</table>
			
			</div>';
		}
      if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						 foreach($usernames as $username){

                           
                            $html.=$username['username'].' | ';

							} 
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	


		$html.='

</table>
<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
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


$edata = array ();
$edata ['message'] = $html;
$edata ['subject'] = $emailData['email_subject'];
$edata ['user_email'] = $result ['email'];								
$email_status = $this->model_api_emailapi->sendmail ( $edata );


} else if ($result['type']=="8" && $emailData['type']=="8"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['notes_description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

          $edata = array();
		  $edata['message'] = $html;
		 $edata['subject'] = $result['facility'].' | '.$emailData['email_subject'];
		  $edata['user_email'] = $result['user_email'];				
		  $email_status = $this->model_api_emailapi->sendmail($edata);

}else if ($result['type']=="9" && $emailData['type']=="9"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['notes_description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}  if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $result['facility'].' | '.$emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];			
						$email_status = $this->model_api_emailapi->sendmail($edata);

} else if ($result['type']=="10" && $emailData['type']=="10"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['notes_description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $result['facility'].' | '.$emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

}  else if ($result['type']=="11" && $emailData['type']=="11"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Shared PDF Password</small></h4>';
							
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								PDF that you have shared has been protected by a password. You may share this password with the person you sent the pdf to. By sharing this password you are attesting that the person receiving this password is authorized to view the shared data and you have the authority to share such data. The password is: '.$result['password'].'
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
	$edata['message'] = $html;
	$edata['subject'] = $result['facility'].' | '.$emailData['email_subject'];
	$edata['user_email'] = $result['user_email'];				


	$email_status = $this->model_api_emailapi->sendmail($edata);

} 

 /*else if ($result['type']=="13" && $emailData['type']=="13"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $result['facility'].' | '.$emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

}*/ /*else if ($result['type']=="14" && $emailData['type']=="14"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

}*/ else if ($result['type']=="14" && $emailData['type']=="14"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$result['rules_name'].'! Please review the details below for further information or actions: </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['rules_type'].'- '.$result['rules_value'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['notes_description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
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
		} if($emailData['who_check']=="1"){

			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$result['facility'].'&nbsp;'.$result['address'].'&nbsp;'.$result['location'].'&nbsp;'.$result['zone_name'].'&nbsp;'.$result['zipcode'].', '.$result['contry_name'].'
						</p><br>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

               $edata = array();
			   $edata['message'] = $html;
			   $edata['subject'] = $emailData['email_template'];
			   $edata['useremailids'] = $result['useremailids'];
			   $edata['user_email'] = $result['user_email'];					
			   $email_status = $this->model_api_emailapi->sendmail($edata);

}

/*else if ($result['type']=="19" && $emailData['type']=="19"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $result ['facility'] . '</small></h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<a target="_blank" href="' . $result ['s3file'] . '">' . $result ['s3file'] . '</a>
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

} else if ($result['type']=="20" && $emailData['type']=="20"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $result ['facility'] . '</small></h4>';
							foreach ( $result ['rurl'] as $keyff => $rurl ) {
			
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $keyff . "'" );
			$facility_info = $query->row;
			
			$html .= '<p>' . $facility_info ['facility'] . '</p> ';
			foreach ( $rurl as $rurl1 ) {
				$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
									<a target="_blank" href="' . $rurl1 . '">' . $rurl1 . '</a>
									</p> ';
			}
		}
						$html.='<br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						//$edata['user_email'] = $result['user_email'];
						$edata1 ['useremailids'] = $ids;					
						$email_status = $this->model_api_emailapi->sendmail($edata);

} else if ($result['type']=="11" && $emailData['type']=="11"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Shared PDF Password</small></h4>';				
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								PDF that you have shared has been protected by a password. You may share this password with the person you sent the pdf to. By sharing this password you are attesting that the person receiving this password is authorized to view the shared data and you have the authority to share such data. The password is: '.$result['password'].'
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}  if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];			
						$email_status = $this->model_api_emailapi->sendmail($edata);

}*/ else if ($result['type']=="11" && $emailData['type']=="11"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Notes Shared</small></h4>';
							
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								Please find attached notes detail
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}  if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];
						$edata1['dirpath'] = $result['dirpath'];
		$edata1['filename'] = $result['filename'];
		$edata1['assignto_email'] = $result['assignto_email'];	
						$email_status = $this->model_api_emailapi->sendmail($edata);

} else if ($result['type']=="12" && $emailData['type']=="12"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;"></p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Notes Shared</small></h4>';
							
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								Please find attached notes detail
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}  if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];
						$edata['asupport_attachment_images'] = $result['asupport_attachment_images'] ;
						
						$email_status = $this->model_api_emailapi->sendmail($edata);

}else if ($result['type']=="14" && $emailData['type']=="14"){


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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['username'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>';

                              if($result['support_type'] == '1'){
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Facility:</b> '.$result['facility'].'<br>
								<b>Username:</b> '.$result['who_user'].'<br>
								<b>Ticket ID:</b> '.$result['ticket_id'].'<br>
								<b>Url:</b> '.HTTPS_SERVER.'<br>
								<b>Comment:</b> '.$result['comment'].'
								</p>';
							
							}
							
							if($result['support_type'] == '2'){
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Company:</b> '.$result['Company_name'].'<br>
								<b>Username:</b> '.$result['username'].'<br>
								<b>Ticket ID:</b> '.$result['ticket_id'].'<br>
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
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

}else if ($result['type']=="15" && $emailData['type']=="15"){


  $html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>'.$emailData['email_header'].'</title>

<style>2
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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$emailData["email_header"].'</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ';
                           
                            $html.=$result['who_user'];

							$html.='!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$emailData['email_header'].' </p>
							
						</td>
					</tr>
				</table>
			</div>';
       
          if($emailData['what_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['notes_description'] . '
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'. $result['when_date'].' </p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
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
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/who.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';                           
                            $html.=$result['who_user'];						
							$html.='</p><br><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData ['who_message'] . '
							</p>
					</td>

					
					<td></td>
				</tr>
			</table></div>';

	}	

	if($emailData['where_check']=="1"){
			$html.='<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['facility'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $emailData['where_message'] . '
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

      $edata = array();
						$edata['message'] = $html;
						$edata['subject'] = $emailData['email_subject'];
						$edata['user_email'] = $result['user_email'];					
						$email_status = $this->model_api_emailapi->sendmail($edata);

}
	}
	
}