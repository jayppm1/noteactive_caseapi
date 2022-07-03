<?php
class Modelapinotification extends Model {
	
	public function sendnotification($data = array(), $ndata = array()) {
		
		$this->load->model('user/user_group');
		$this->load->model('user/user');
		if ($data['assign_to_type'] == "user") {
			if ($data['alert_type_email'] == 1) {
				$message33 = "";
				if($ndata['tasktimearray'] != null && $ndata['tasktimearray'] != ""){
					$message33 .= $this->emailtemplateinterval($data, $ndata['dateRange'], $ndata['tasktimearray']);
				}else{
					$message33 .= $this->emailtemplate($data, $ndata['dateRange'], $ndata['tasksTiming'], $ndata['weekd']);
				}
				
				$usermeails = array();
				if ($data['assign_to'] != "" && $data['assign_to'] != NULL) {
					foreach ($data['assign_to'] as $userid) {
						
						$user_info = $this->model_user_user->getUserbyupdate($userid);
						if ($user_info['email'] != null && $user_info['email'] != "") {
							$usermeails[] = $user_info['email'];
						}
						
					}
					
					
					if(!empty($usermeails)){
						$edata = array();
						$edata['message'] = $message33;
						$edata['subject'] = 'Task has been assigned to you';
						$edata['useremailids'] = $usermeails;
						//var_dump($edata);
						$email_status = $this->model_api_emailapi->sendmail($edata);
					}
					
				}
			}
			
			if ($data['alert_type_sms'] == 1) {
				if ($data['assign_to'] != "" && $data['assign_to'] != NULL) {
					foreach ($data['assign_to'] as $userid) {
						$user_info = $this->model_user_user->getUserbyupdate($userid);
						if ($user_info['phone_number'] != null && $user_info['phone_number'] != "") {
							$phone_number = $user_info['phone_number'];
							
							$message =  "";
							if($ndata['tasktimearray'] != null && $ndata['tasktimearray'] != ""){
								$message = "Task Assigned with following interval \n";
								foreach ($ndata['tasktimearray'] as $taskeTiming) {
									$message .= date('h:i A', strtotime($taskeTiming)) . "\n";
								}
							}else{
								$message .= "Task Assigned for " . date('h:i A', strtotime($ndata['tasksTiming'])) . "...\n";
							}
							
							$message .= "Task Type: " . $data['tasktype'] . "\n";
							
							$this->load->model('setting/tags');
							if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "") {
								$tags_info1 = $this->model_setting_tags->getTag($data['emp_tag_id']);
								
								if ($tags_info1['emp_first_name']) {
									$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
								} else {
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
								
								if ($tags_info1) {
									$message .= "Client Name: " . $emp_tag_id . "\n";
								}
							}
							
							if ($data['medication_tags'] != null && $data['medication_tags'] != "") {
								$tags_info1 = $this->model_setting_tags->getTag($data['medication_tags']);
								if ($tags_info1['emp_first_name']) {
									$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
								} else {
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
								
								if ($tags_info1) {
									$message .= "Client Name: " . $emp_tag_id . "\n";
								}
							}
							if ($data['visitation_tag_id'] != null && $data['visitation_tag_id'] != "") {
								$tags_info1 = $this->model_setting_tags->getTag($data['visitation_tag_id']);
								if ($tags_info1['emp_first_name']) {
									$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
								} else {
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
								
								if ($tags_info1) {
									$message .= "Client Name: " . $emp_tag_id . "\n";
								}
							}
							if ($data['transport_tags'] != null && $data['transport_tags'] != "") {
								
								$transport_tags1 = explode(',', $data['transport_tags']);
								
								$transport_tags = '';
								foreach ($transport_tags1 as $tag1) {
									$tags_info1 = $this->model_setting_tags->getTag($tag1);
									
									if ($tags_info1['emp_first_name']) {
										$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
									} else {
										$emp_tag_id = $tags_info1['emp_tag_id'];
									}
									
									if ($tags_info1) {
										$transport_tags .= $emp_tag_id . ', ';
									}
								}
								
								$message .= "Client Name: " . $transport_tags . "\n";
							}
							
							$message .= "Description: " . substr($ndata['description'], 0, 150) . ((strlen($ndata['description']) > 150) ? '..' : '') . "\n";
							
							$sdata = array();
							$sdata['message'] = $message;
							$sdata['phone_number'] = $phone_number;
							$sdata['facilities_id'] = $ndata['facilities_id'];
							//$sdata['is_task'] = 1;
							$response = $this->model_api_smsapi->sendsms($sdata);
							//var_dump($sdata);
							$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET message_sid = '" . $response->sid . "' WHERE id = '" . $ndata['task_id'] . "'";
							$query = $this->db->query($sql3);
						}
							
					}
				}
				
			}
		}
		
		
		if ($data['assign_to_type'] == "role") {
			if ($data['alert_type_email'] == 1) {
				$message33 = "";
				if($ndata['tasktimearray'] != null && $ndata['tasktimearray'] != ""){
					$message33 .= $this->emailtemplateinterval($data, $ndata['dateRange'], $ndata['tasktimearray']);
				}else{
					$message33 .= $this->emailtemplate($data, $ndata['dateRange'], $ndata['tasksTiming'], $ndata['weekd']);
				}
				
				$usermeails = array();
				if ($data['user_role_assign_ids'] != "" && $data['user_role_assign_ids'] != NULL) {
					
					foreach ($data['user_role_assign_ids'] as $user_role) {
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
						$edata['message'] = $message33;
						$edata['subject'] = 'Task has been assigned to you';
						$edata['useremailids'] = $usermeails;
						//var_dump($edata);
						$email_status = $this->model_api_emailapi->sendmail($edata);
					}
					
				}
			}
			
			if ($data['alert_type_sms'] == 1) {
				if ($data['user_role_assign_ids'] != "" && $data['user_role_assign_ids'] != NULL) {
					
					foreach ($data['user_role_assign_ids'] as $user_role) {
						$urole = array();
						$urole['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers($urole);
						if($tusers){
							foreach ($tusers as $userid) {
								if ($userid['phone_number'] != null && $userid['phone_number'] != "") {
									$phone_number = $userid['phone_number'];
									
									$message =  "";
									if($ndata['tasktimearray'] != null && $ndata['tasktimearray'] != ""){
										$message = "Task Assigned with following interval \n";
										foreach ($ndata['tasktimearray'] as $taskeTiming) {
											$message .= date('h:i A', strtotime($taskeTiming)) . "\n";
										}
									}else{
										$message .= "Task Assigned for " . date('h:i A', strtotime($ndata['tasksTiming'])) . "...\n";
									}
									
									$message .= "Task Type: " . $data['tasktype'] . "\n";
									
									$this->load->model('setting/tags');
									if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag($data['emp_tag_id']);
										
										if ($tags_info1['emp_first_name']) {
											$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($data['medication_tags'] != null && $data['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag($data['medication_tags']);
										if ($tags_info1['emp_first_name']) {
											$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($data['visitation_tag_id'] != null && $data['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag($data['visitation_tag_id']);
										if ($tags_info1['emp_first_name']) {
											$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($data['transport_tags'] != null && $data['transport_tags'] != "") {
										
										$transport_tags1 = explode(',', $data['transport_tags']);
										
										$transport_tags = '';
										foreach ($transport_tags1 as $tag1) {
											$tags_info1 = $this->model_setting_tags->getTag($tag1);
											
											if ($tags_info1['emp_first_name']) {
												$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									
									$message .= "Description: " . substr($ndata['description'], 0, 150) . ((strlen($ndata['description']) > 150) ? '..' : '') . "\n";
									
									$sdata = array();
									$sdata['message'] = $message;
									$sdata['phone_number'] = $phone_number;
									$sdata['facilities_id'] = $ndata['facilities_id'];
									//$sdata['is_task'] = 1;
									$response = $this->model_api_smsapi->sendsms($sdata);
									//var_dump($sdata);
									$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET message_sid = '" . $response->sid . "' WHERE id = '" . $ndata['task_id'] . "'";
									$query = $this->db->query($sql3);
								}
									
							}
						}
					}
				}
				
			}
		}
		
		
	}
	
	
	public function emailtemplateinterval ($result, $taskDate, $tasktimearray)
    {
        $html = "";
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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result['assignto'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you ' . $result['tasktype'] . '. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date('j, F Y', strtotime($taskDate)) . ' <br> ';
        
        foreach ($tasktimearray as $taskeTiming) {
            
            $html .= date('h:i A', strtotime($taskeTiming)) . '<br>';
        }
        
        $html .= '</p>
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

    public function emailtemplate ($result, $taskDate, $taskeTiming, $weekd)
    {
        $html = "";
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
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result['assignto'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you ' . $result['tasktype'] . '. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . $weekd . '&nbsp;' . date('j, F Y', strtotime($taskDate)) . '&nbsp;' . date('h:i A', strtotime($taskeTiming)) . '
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
	
}