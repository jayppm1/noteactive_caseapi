<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllerservicessupport extends Controller { 
	
	public function index() {
		
		
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		
		if (!$this->request->post['comment']) {
			$json['warning'] = 'Please insert required!.';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$this->load->model('notes/support');
				$support_id = $this->model_notes_support->addsupport($this->request->post, $this->request->post['facilities_id']);
					
				$sdata = array();
				$subject = "open ticket-". date('m-d-Y-H-i-s'); 
						
				$sdata['subject'] = $subject;
				$sdata['support_id'] = $support_id;
				$sdata['notes_description'] = $this->request->post['comment'];
				$sdata['user_id'] = $this->request->post['user_id'];
						
				
				$sresults = $this->model_notes_support->addsupportticket($sdata);
				$this->model_notes_support->updatesupport($sdata);
				$this->model_notes_support->updatesupportTicket($sdata, $sresults->ticket_id);
				
				$message33 = "";
				$message33 .= $this->emailtemplate($this->request->post, $sresults->ticket_id, '1');

				$this->load->model('api/emailapi');
				
				$edata = array();
				$edata['message'] = $message33;
				$edata['subject'] = 'Support has been created';
				$edata['user_email'] = $this->config->get('config_support_email');
				$edata['asupport_attachment_images'] = explode(",",$this->request->post['support_attachment_image']);
					
				$email_status = $this->model_api_emailapi->sendmail($edata);

				
				
				$this->data['facilitiess'][] = array(
					'warning'  => '1',
				);
				$error = true;
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
			
		
	
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
								<b>Facility:</b> '.$result['facility'].'<br>
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
	
}