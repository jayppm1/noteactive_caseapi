<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservices2sharenotes extends Controller { 
	
	public function index() {
		$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('sharenotesindex', $this->request->post, 'request');
		
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
		
		
		
		if (!$this->request->post['notes_id']) {
			$json['warning'] = 'You cannot share new notes.';
		}
		
		if ((utf8_strlen($this->request->post['user_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['user_email'])) {
			$json['warning'] = 'Email is required';
		}
		
		$facilities_id = $this->request->post['facilities_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$required_pin = $facilities_info['config_sharepin_status'];
		
		
		if($required_pin == '1'){
			if($this->request->post['user_pin'] == NULL && $this->request->post['user_pin'] == ""){
				$json['warning'] = 'Pin is required';
			}
		}
		
		if($this->request->post['user_id'] != NULL && $this->request->post['user_id'] != ""){
			
			$username = $this->request->post['user_id'];
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUser($username);
			
			if($userdetail['email'] == NULL && $userdetail['email'] == ""){
			
				$json['warning'] = 'Selected user donot have email address, please select another user';
			
			}
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($userdetail['customer_key'] != $customer_info['activecustomer_id']){
				$json['warning'] = $this->language->get('error_customer');
			}
			
			if($required_pin == '1'){
				if($this->request->post['user_pin'] != $userdetail['user_pin']){
					$json['warning'] = "Please provide a valid pin";
				}
			}
			
			$this->load->model('user/user_group');
			$userrole_info = $this->model_user_user_group->getUserGroup($userdetail['user_group_id']);
			
			if($userrole_info['share_notes'] != '1'){
				$json['warning'] = 'You are not authorized to share notes';
			}
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$filename = 'NoteActive_Share_'.rand().'.pdf';
			$dirpath = DIR_IMAGE .'share/';		
			
			$username = $this->request->post['user_id'];
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUser($username);
			$useremail  = $userdetail['email'];
			
			
			$notesid = $this->request->post['notes_id'];
			$this->load->model('notes/notes');
			$notedetail = $this->model_notes_notes->getnotes($notesid);
		
			//var_dump($notedetail);
		
		
			$this->load->model('facilities/facilities');
			$facilityname = $this->model_facilities_facilities->getfacilities($notedetail['facilities_id']);
			
			$allforms = $this->model_notes_notes->getforms($notesid);
		
			//var_dump($facilityname);
			$allimages = $this->model_notes_notes->getImages($notesid);
			
			$sharepasswd = mt_rand(100000, 999999);
			$protection = array();
			$sharenotes_assemble = $facilities_info['sharenotes_assemble'];
			$sharenotes_copy = $facilities_info['sharenotes_copy'];
			$sharenotes_modify = $facilities_info['sharenotes_modify'];
			$sharenotes_print = $facilities_info['sharenotes_print'];
			$sharenotes_send_email = $facilities_info['config_send_email_share_notes'];
			
			
			$message334 = "";
			$message334 .= $this->emailtemplatePassword($this->request->post['user_id'], $sharepasswd);	
			
			if($sharenotes_send_email == '1'){
				if( $useremail != NULL && $useremail != ""){
					$user_email = $useremail;
				}
			}
			
			$this->load->model('api/emailapi');
			
			/*$edata = array();
			$edata['message'] = $message334;
			$edata['subject'] = 'Shared PDF Password';
			$edata['user_email'] = $user_email;
				
			$email_status = $this->model_api_emailapi->sendmail($edata);*/

			$edata = array();
			$edata['message'] = $message334;
			//$edata['subject'] = 'Shared PDF Password';
			$edata['type'] = "11";
			$edata['username'] = $this->request->post['user_id'];
			$edata['who_user'] = $this->request->post['user_id'];
			$edata['when_date'] = date("l");
			$edata['password'] = $sharepasswd;
			$edata['user_email'] = $user_email;
				
			$email_status = $this->model_api_emailapi->createMails($edata);
			
			if($sharenotes_assemble == '1'){
				array_push($protection,$sharenotes_assemble);
			}
					
			if($sharenotes_copy == '1'){
				array_push($protection,$sharenotes_copy);
			}
					
			if($sharenotes_modify == '1'){
				array_push($protection,$sharenotes_modify);
			}
					
			if($sharenotes_print == '1'){
				array_push($protection,$sharenotes_print);
			}
			
			
			
			
			require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
		

			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetProtection($protection, $sharepasswd, "NoteActive2017#", 0, null);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('');
			$pdf->SetTitle('Shared Note');
			$pdf->SetSubject('Shared Note');
			$pdf->SetKeywords('Shared Note');
			
			if ($this->config->get('pdf_report_image') && file_exists(DIR_SYSTEM . 'library/pdf_class/'.$this->config->get('pdf_report_image'))) {
			$imageLogo = $this->config->get('pdf_report_image');
			$PDF_HEADER_LOGO_WIDTH = "30";
						
			}else{
				$imageLogo = '4F-logo.png';
				$PDF_HEADER_LOGO_WIDTH = "30";
				$headerString = "";	
			}
			
				
			$PDF_HEADER_TITLE = "Note detail";
			$headerString = "Email Note";

			$pdf->SetHeaderData($imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE.'', $headerString);
				
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);	
			
			
				// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage();
			
			
			$noteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
			$printnoteurl = $this->url->link('form/form/printform', '' . $url, 'SSL');
			$firedrillnoteurl = $this->url->link('form/form/printmonthly_firredrill', '' . $url, 'SSL');
			$incidentnoteurl = $this->url->link('form/form/printincidentform', '' . $url, 'SSL');
			$innoteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
			
			
			$html='';
		
			$html .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
			$html .='<thead>';
			$html .='  <tr>';
			$html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Note Date</td>';
			$html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:30%">Note Description</td>';
			$html .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Facility</td>';
			$html .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Username</td>';
			$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Signature/Pin</td>';
			$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Download</td>';
			$html .='  </tr>';
			$html .=' </thead>';
			$html .='<tr>';
			
			
			$html .='<td style="text-align:left;width:20%; line-height:20.2px;"><b>Created Date:</b><br> '.date('m-d-Y', strtotime($notedetail['note_date'])).'&nbsp;'.date('h:i A', strtotime($notedetail['notetime'])).'<br><b>Log Time:</b> <br>'.date('m-d-Y', strtotime($notedetail['date_added'])).'&nbsp;'.date('h:i A', strtotime($notedetail['note_date'])).'</td>';
		
			$cssStyle = "";
			if($notedetail['highlighter_value'] != null && $notedetail['highlighter_value'] != ""){
				$cssStyle .= 'background-color:'.$notedetail['highlighter_value'].'; ';
			}
			
			if($notedetail['text_color_cut'] == "1"){ 
				$cssStyle .= 'text-decoration: line-through;';
			}
					
			if($notedetail['text_color'] != null && $notedetail['text_color'] != ""){ 
				$cssStyle .= 'color:'.$notedetail['text_color'].';';
			}
					
			if(($notedetail['highlighter_value'] != null && $notedetail['highlighter_value'] != "") && ($notedetail['text_color'] == null && $notedetail['text_color'] == "")){ 
				//$cssStyle .= ';color:#FFF';
				/*if($notedetail['highlighter_value'] !='#ffff00'){
					$cssStyle .= ';color:#FFF;';
				}else{
					$cssStyle .= ';color:#000;';
				}*/
				
						if($notedetail['highlighter_value'] =='#ffff00'){
							$cssStyle .= 'color:#000;';
						}else if($notedetail['highlighter_value'] == '#ffffff'){
							$cssStyle .= 'color:#666;';
						}
						else{
							$cssStyle .= 'color:#FFF;';
						}
			}
		
		
		
		$html .='<td style="line-height:20.2px;width:30%;text-align:left;'.$cssStyle.'">';
		
		/*if($notedetail['keyword_file_url'] != null && $notedetail['keyword_file_url'] != ""){
			$html .='<img src="'.$notedetail['keyword_file_url'].'" width="35px" height="35px">';
			
		}*/
		
		if($notedetail['checklist_status'] == "1"){ 
			if($notedetail['taskadded'] == "2"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">';
			}
			if($notedetail['taskadded'] == "3"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
			}
			if($notedetail['taskadded'] == "4"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete: ';
			}
		
		}elseif($notedetail['checklist_status'] == "2"){
			$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/checklist-icon.png" width="35px" height="35px">';
		}else{
			
			if($notedetail['taskadded'] == "1"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Deleted: ';
			}
			if($notedetail['taskadded'] == "2"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">';
			}
			if($notedetail['taskadded'] == "3"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
			}
			if($notedetail['taskadded'] == "4"){
				$html .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px">';
			}
		}
		
		/*if($notedetail['task_type'] == "1"){ 
			$html .=  'Bed Check for ';
		} 
		
		if($notedetail['task_time'] != null && $notedetail['task_time'] != "00:00:00"){ 
			$html .=  date('h:i A', strtotime($notedetail['task_time']));
		} 
		
		if($notedetail['task_type'] == "1"){ 
			$html .=  'Completed. The following details were noted: ';
		}*/

		/*if($notedetail['keyword_file_url'] != null && $notedetail['keyword_file_url'] != ""){
			$html .='<img src="'.$notedetail['keyword_file_url'].'" width="35px" height="35px">';
			
		}*/
		
		$allkeywords = $this->model_notes_notes->getnoteskeywors($notesid);
		
		if($allkeywords){
			$keyImageSrc12 = array();
			$keyname = array();
			$keyImageSrc11 = "";
			$keyImageSrc11 .= '<table style="border:none;"><tr style="border:none;">';
					foreach ($allkeywords as $keyword) {
						$keyImageSrc11 .= '
							   <td style="border:none;width:40px;">
								 <img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px" style="float: left;
								vertical-align: bottom;
								width: 36px;display:block"> 
							   </td>
							   
							';
						$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
						$keyname[] = $keyword['keyword_name'];
						$keyname = array_unique($keyname);
					}
					 $keyImageSrc11 .= '</tr></table>';
			
			//$keyword_description = str_replace($keyname, $keyImageSrc12, $notedetail['notes_description']);
			$keyword_description = $keyImageSrc11.'&nbsp;'.$notedetail['notes_description'];
			
			$notes_description = $keyword_description;
		}else{
			$notes_description = $notedetail['notes_description'];
		}
		
		//$html .= ' '.$notedetail['notes_description'];
		$html .= ' '.$notes_description;
		
		if($notedetail['notestasks'] != null && $notedetail['notestasks'] != ""){ 
		//$html .='<br>';
					foreach($notedetail['notestasks'] as $notestask){ 
						$html .='<br> '.$notestask['task_content'].'';
					} 
					
					//$html .='<br>';
					if($notedetail['boytotals'][0] != null && $notedetail['boytotals'][0] != ""){ 
					$html .='Total  '.$notedetail['boytotals'][0]['loc_name'].': '.$notedetail['boytotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($notedetail['girltotals'][0] != null && $notedetail['girltotals'][0] != ""){ 
					$html .='Total  '.$notedetail['girltotals'][0]['loc_name'].': '.$notedetail['girltotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($notedetail['generaltotals'][0] != null && $notedetail['generaltotals'][0] != ""){ 
					$html .='Total  '.$notedetail['generaltotals'][0]['loc_name'].': '.$notedetail['generaltotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($notedetail['residentstotals'][0] != null && $notedetail['residentstotals'][0] != ""){ 
					$html .='Total  '.$notedetail['residentstotals'][0]['loc_name'].': '.$notedetail['residentstotals'][0]['total'].' ';
					$html .='<br>';
					}
				
					
				} 

			$html .='</td>';
			
			$html .='<td style="text-align:right;width:10%; line-height:20.2px;">'.$facilityname['facility'].'  </td>';
			
			$html .='<td style="text-align:right;width:10%; line-height:20.2px;">'.$notedetail['user_id'].'  </td>';
			
		
			
			$html .='<td style="text-align:center;width:15%; line-height:20.2px;">';
			
			
			if($notedetail['signature'] != null && $notedetail['signature'] != ""){
				
			$html .='<img style="text-align: center;" src="'.$notedetail['signature'].'" width="98px" height="29px">';
			}
			
			if($notedetail['notes_pin'] != null && $notedetail['notes_pin'] != ""){	
			$html .='<img style="text-align: center;" src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
			}
			
			
			
			
		$html .='</td>';
		
		
		$html .='<td style="text-align:center;width:15%; line-height:20.2px;">';
				
			if($allimages){
				foreach($allimages as $images){
				$html .='<a href="'.$images['notes_file'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/attachment_icons.png" width="35px" height="35px" ></a> ';
				}
			}
			
			
			if($allforms){
				foreach($allforms as $forms){
					if($forms['custom_form_type'] == '9'){
						$html .='<a href="'.$firedrillnoteurl.'&notes_id='.$result['notes_id'].'&forms_design_id='.$forms['custom_form_type'].'&forms_id='.$forms['forms_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if($forms['custom_form_type'] == '13'){
						$html .='<a href="'.$printnoteurl.'&notes_id='.$result['notes_id'].'&forms_design_id='.$forms['custom_form_type'].'&forms_id='.$forms['forms_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if($forms['custom_form_type'] == '10'){
						$html .='<a href="'.$incidentnoteurl.'&notes_id='.$result['notes_id'].'&forms_design_id='.$forms['custom_form_type'].'&forms_id='.$forms['forms_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
					if($forms['custom_form_type'] == '2'){
						$html .='<a href="'.$innoteurl.'&notes_id='.$result['notes_id'].'&forms_design_id='.$forms['custom_form_type'].'&forms_id='.$forms['forms_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/add_form.png" width="35px" height="35px" ></a> ';
					}
					
				
				}
			}
			
		
		$html .='</td>';
		
		$html .='</tr>';
		
		$html .='</table>';

		
	//var_dump($html);die;
	$pdf->writeHTML($html, true, 0, true, 0);
	$pdf->lastPage();
	
	//die;
	$pdf->Output($dirpath. $filename, 'F'); 
	//$pdf->Output(APP . 'webroot' . DS . 'files' . DS . 'pdf' . DS . 'filename.pdf', 'F');
	
	
	if( $this->request->post['user_email'] != NULL && $this->request->post['user_email'] != ""){
		$user_email = $this->request->post['user_email']; 
	}
					
	$message33 = "";
	$message33 .= $this->emailtemplate($notedetail, $username);	
	
	$edata1 = array();		
		$edata['user_email'] = $user_email;
		$edata['dirpath'] = $dirpath;
		$edata['filename'] = $filename;
		$edata['assignto_email'] = $assignto_email;	
		
			$edata['type'] = "12";
			$edata['username'] = $this->request->post['user_id'];
			$edata['who_user'] = $this->request->post['user_id'];
			$edata['when_date'] = date("l");
			
		
				
			$email_status = $this->model_api_emailapi->createMails($edata);
	
	
			$quantity = 1;
			$this->db->query("UPDATE " . DB_PREFIX . "notes SET share_notes = (share_notes + " . (int)$quantity . ") WHERE notes_id = '" . (int)$notesid . "' ");
			
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$is_android = $this->request->post['is_android'];
			}else{
				$is_android = '1';
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "share_notes` SET notes_id = '" . $notesid . "', user_id = '" . $this->request->post['user_id'] . "', notes_pin = '" . $this->request->post['user_pin'] . "', email = '" . $this->request->post['user_email'] . "', share_notes_otp = '" . $sharepasswd . "', date_added = NOW(), share_type = 'single', phone_device_id = '" . $this->request->post['phone_device_id'] . "', device_unique_id = '" . $this->request->post['device_unique_id'] . "', is_android = '" . $is_android . "' ");
			
					
				
				
			unlink($dirpath.$filename);
			/*if($allimages){
				foreach ($allimages as $image) {
					$mediafilename = 'NoteActive_Media_'.$image['notes_media_id'].'.'.$image['notes_media_extention'];
					$img2 = $dirpath.$mediafilename;
					unlink($img2);
				}
			}
			
			if($allforms){
				foreach($allforms as $forms){
					if($forms['form_type'] == '1'){
						$incidentfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
						$incident = $dirpath.$incidentfilename;
						unlink($incident);
					}
					
					if($forms['form_type'] == '2'){
						$checklistfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
						$check = $dirpath.$checklistfilename;
						unlink($check);
					}
				}
			}*/
		
		
			
			
			
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
	
	
	public function emailtemplate($result, $username){
	$html = "";
	$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Shared Notes</title>

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
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Shared Notes</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$username.'!</h1>
							
							
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
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Notes Shared</small></h4>';
							
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								Please find attached notes detail
								</p>';
							
							
							
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
	
	
	
	public function searchnoteshare(){
		
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
		
		
		$sharePage = $this->url->link('notes/sharenote/downloadmedia', '' . $url, 'SSL');
		
		
		if ((utf8_strlen($this->request->post['user_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['user_email'])) {
			$json['warning'] = 'Email is required';
		}
		
		$facilities_id = $this->request->post['facilities_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$required_pin = $facilities_info['config_sharepin_status'];
		
		
		if($required_pin == '1'){
			if($this->request->post['user_pin'] == NULL && $this->request->post['user_pin'] == ""){
				$json['warning'] = 'Pin is required';
			}
		}
		
		if($this->request->post['user_id'] != NULL && $this->request->post['user_id'] != ""){
			
			$username = $this->request->post['user_id'];
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUser($username);
			
			if($userdetail['email'] == NULL && $userdetail['email'] == ""){
			
				$json['warning'] = 'Selected user donot have email address, please select another user';
			
			}
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($userdetail['customer_key'] != $customer_info['activecustomer_id']){
				$json['warning'] = $this->language->get('error_customer');
			}
			
			if($required_pin == '1'){
				if($this->request->post['user_pin'] != $userdetail['user_pin']){
					$json['warning'] = "Please provide a valid pin";
				}
			}
			
			$this->load->model('user/user_group');
			$userrole_info = $this->model_user_user_group->getUserGroup($userdetail['user_group_id']);
			
			if($userrole_info['share_notes'] != '1'){
				$json['warning'] = 'You are not authorized to share notes';
			}
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
		
			$sharepasswd = mt_rand(100000, 999999);
			$protection = array();
			$sharenotes_assemble = $facilities_info['sharenotes_assemble'];
			$sharenotes_copy = $facilities_info['sharenotes_copy'];
			$sharenotes_modify = $facilities_info['sharenotes_modify'];
			$sharenotes_print = $facilities_info['sharenotes_print'];
			$sharenotes_send_email = $facilities_info['config_send_email_share_notes'];
			
			
			$message334 = "";
			$message334 .= $this->emailtemplatePassword($this->request->post['user_id'], $sharepasswd);	
			
			$useremail  = $userdetail['email'];
			
			if($sharenotes_send_email == '1'){
				if( $useremail != NULL && $useremail != ""){
					$user_email = $useremail;
				}
			}
						
			$this->load->model('api/emailapi');
			
			/*$edata = array();
			$edata['message'] = $message334;
			$edata['subject'] = 'Shared PDF Password';
			$edata['user_email'] = $user_email;
				
			$email_status = $this->model_api_emailapi->sendmail($edata);*/

				$edata = array();
			$edata['message'] = $message334;
			//$edata['subject'] = 'Shared PDF Password';
			$edata['type'] = "11";
			$edata['username'] = $this->request->post['user_id'];
			$edata['who_user'] = $this->request->post['user_id'];
			$edata['when_date'] = date("l");
			$edata['password'] = $sharepasswd;
			$edata['user_email'] = $user_email;
				
			$email_status = $this->model_api_emailapi->createMails($edata);
			
			
			if($sharenotes_assemble == '1'){
				array_push($protection,$sharenotes_assemble);
			}
					
			if($sharenotes_copy == '1'){
				array_push($protection,$sharenotes_copy);
			}
					
			if($sharenotes_modify == '1'){
				array_push($protection,$sharenotes_modify);
			}
					
			if($sharenotes_print == '1'){
				array_push($protection,$sharenotes_print);
			}

		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		
		$config_admin_limit = "100";
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'searchdate' => $searchdate,
			'searchdate_app' => '1',
			'facilities_id' => $this->request->post['facilities_id'],
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $this->request->post['keyword'],
			'form_search' => $this->request->post['form_search'],
			'user_id' => $this->request->post['search_user_id'],
			'highlighter' => $this->request->post['highlighter'],
			'activenote' => $this->request->post['activenote'],
			'emp_tag_id' => $this->request->post['emp_tag_id'],
			'advance_searchapp' => $this->request->post['advance_search'],
			'customer_key' => $this->session->data['webcustomer_key'],
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		$this->load->model('notes/notes');
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		$results = $this->model_notes_notes->getnotess($data);	
		$filename = 'NoteActive_Share_'.rand().'.pdf';
			$dirpath = DIR_IMAGE .'share/';	
			
		
		require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			$pdf->SetProtection($protection, $sharepasswd, "NoteActive2017#", 0, null);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor(''); 
			$pdf->SetTitle('Share Search Notes');
			$pdf->SetSubject('Share Search Notes');
			$pdf->SetKeywords('Share Search Notes');
			
			if ($this->config->get('pdf_report_image') && file_exists(DIR_SYSTEM . 'library/pdf_class/'.$this->config->get('pdf_report_image'))) {
			$imageLogo = $this->config->get('pdf_report_image');
			$PDF_HEADER_LOGO_WIDTH = "30";
						
			}else{
				$imageLogo = '4F-logo.png';
				$PDF_HEADER_LOGO_WIDTH = "30";
				$headerString = "";	
			}
			
			
		$PDF_HEADER_TITLE = "Search Notes detail";
		$headerString = "Email Search Notes";

		$pdf->SetHeaderData($imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE.'', $headerString);
			
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);	
		
		
			// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
			
		$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage();
		
		
		if($results){
		$searchresulthtml='';
		
		foreach($results as $result){
			
		$this->load->model('facilities/facilities');
		$facilityname = $this->model_facilities_facilities->getfacilities($result['facilities_id']);
		
		$allforms = $this->model_notes_notes->getforms($result['notes_id']);
		$allimages = $this->model_notes_notes->getImages($result['notes_id']);
		
		$searchresulthtml .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
		$searchresulthtml .='<thead>';
			$searchresulthtml .='  <tr>';
			$searchresulthtml .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Note Date</td>';
			$searchresulthtml .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:40%">Note Description</td>';
			
			$searchresulthtml .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Username</td>';
			$searchresulthtml .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Signature/Pin</td>';
			
			$searchresulthtml .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Facility</td>';
			
			$searchresulthtml .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Download Link</td>';
			
			
			$searchresulthtml .='  </tr>';
			$searchresulthtml .=' </thead>';
			
			$searchresulthtml .='<tr>';
			
			
			$searchresulthtml .='<td style="text-align:left;width:20%; line-height:20.2px;"><b>Created Date:</b><br> '.date('m-d-Y', strtotime($result['note_date'])).'&nbsp;'.date('h:i A', strtotime($result['notetime'])).'<br><b>Log Time:</b> <br>'.date('m-d-Y', strtotime($result['date_added'])).'&nbsp;'.date('h:i A', strtotime($result['note_date'])).'</td>';
		
			$cssStyle = "";
			if($result['highlighter_value'] != null && $result['highlighter_value'] != ""){
				$cssStyle .= 'background-color:'.$result['highlighter_value'].'; ';
			}
			
			if($result['text_color_cut'] == "1"){ 
				$cssStyle .= 'text-decoration: line-through;';
			}
					
			if($result['text_color'] != null && $result['text_color'] != ""){ 
				$cssStyle .= 'color:'.$result['text_color'].';';
			}
				
			if(($result['highlighter_value'] != null && $result['highlighter_value'] != "") && ($result['text_color'] == null && $result['text_color'] == "")){ 
				//$cssStyle .= ';color:#FFF';
				/*if($notedetail['highlighter_value'] !='#ffff00'){
					$cssStyle .= ';color:#FFF;';
				}else{
					$cssStyle .= ';color:#000;';
				}*/
				
						if($result['highlighter_value'] =='#ffff00'){
							$cssStyle .= 'color:#000;';
						}else if($result['highlighter_value'] == '#ffffff'){
							$cssStyle .= 'color:#666;';
						}
						else{
							$cssStyle .= 'color:#FFF;';
						}
			}
		
		
		
			$searchresulthtml .='<td style="line-height:20.2px;width:40%;text-align:left;'.$cssStyle.'">';
			
			
			
			/*if($result['keyword_file'] != NULL && $result['keyword_file'] != ""){
				$searchresulthtml .='<img src="'.$result['keyword_file_url'].'" width="35px" height="35px">';
				
			}*/
		
			if($result['checklist_status'] == "1"){ 
				if($result['taskadded'] == "2"){
					$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/complte-task.png" width="35px" height="35px" style="vertical-align: inherit;">';
				}
				if($result['taskadded'] == "3"){
					$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px" style="vertical-align: inherit;">';
				}
				if($result['taskadded'] == "4"){
					$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px" style="vertical-align: inherit;"> Incomplete: ';
				}
		
				}elseif($result['checklist_status'] == "2"){
					$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/checklist-icon.png" width="35px" height="35px" style="vertical-align: inherit;">';
				}else{
					
					if($result['taskadded'] == "1"){
						$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px" style="vertical-align: inherit;"> Deleted: ';
					}
					if($result['taskadded'] == "2"){
						$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/complte-task.png" width="35px" height="35px" style="vertical-align: inherit;">';
					}
					if($result['taskadded'] == "3"){
						$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px" style="vertical-align: inherit;">';
					}
					if($result['taskadded'] == "4"){
						$searchresulthtml .='<img src="'.HTTP_SERVER.'sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px" style="vertical-align: inherit;">';
					}
				}
		
				if($result['task_type'] == "1"){ 
					$searchresulthtml .=  'Bed Check for ';
				} 
				
				if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
					$searchresulthtml .=  date('h:i A', strtotime($result['task_time']));
				} 
				
				if($result['task_type'] == "1"){ 
					$searchresulthtml .=  'Completed. The following details were noted: ';
				} 
				
				$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
				
				if($allkeywords){
					$keyImageSrc12 = array();
					$keyname = array();
					$keyImageSrc11 = "";
					$keyImageSrc11 .= '<table style="border:none;"><tr style="border:none;">';
					foreach ($allkeywords as $keyword) {
						$keyImageSrc11 .= '
							   <td style="border:none;width:40px;">
								 <img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px" style="float: left;
								vertical-align: bottom;
								width: 36px;display:block"> 
							   </td>
							   
							';
						$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
						$keyname[] = $keyword['keyword_name'];
						$keyname = array_unique($keyname);
					}
					 $keyImageSrc11 .= '</tr></table>';
					
					//$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
					$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
					
					$notes_description = $keyword_description;
				}else{
					$notes_description = $result['notes_description'];
				}
				
				//$html .= ' '.$notedetail['notes_description'];
				$searchresulthtml .= ' '.$notes_description;
				//$searchresulthtml .= ' '.$result['notes_description'];
				
				if($result['notestasks'] != null && $result['notestasks'] != ""){ 
				//$html .='<br>';
					foreach($result['notestasks'] as $notestask){ 
						$searchresulthtml .='<br> '.$notestask['task_content'].'';
					} 
					
					//$html .='<br>';
					if($result['boytotals'][0] != null && $result['boytotals'][0] != ""){ 
					$searchresulthtml .='Total  '.$result['boytotals'][0]['loc_name'].': '.$result['boytotals'][0]['total'].' ';
					$searchresulthtml .='<br>';
					}
					
					if($result['girltotals'][0] != null && $result['girltotals'][0] != ""){ 
					$searchresulthtml .='Total  '.$result['girltotals'][0]['loc_name'].': '.$result['girltotals'][0]['total'].' ';
					$searchresulthtml .='<br>';
					}
					
					if($result['generaltotals'][0] != null && $result['generaltotals'][0] != ""){ 
					$searchresulthtml .='Total  '.$result['generaltotals'][0]['loc_name'].': '.$result['generaltotals'][0]['total'].' ';
					$searchresulthtml .='<br>';
					}
					
					if($result['residentstotals'][0] != null && $result['residentstotals'][0] != ""){ 
					$searchresulthtml .='Total  '.$result['residentstotals'][0]['loc_name'].': '.$result['residentstotals'][0]['total'].' ';
					$searchresulthtml .='<br>';
					}
				
					
				} 

			$searchresulthtml .='</td>';
			
			
			
			$searchresulthtml .='<td style="text-align:right;width:10%; line-height:20.2px;">'.$result['user_id'].'  </td>';
			$searchresulthtml .='<td style="text-align:center;width:15%; line-height:20.2px;">';
			
			if($result['signature'] != null && $result['signature'] != ""){
				
			$searchresulthtml .='<img style="text-align: center;" src="'.$result['signature'].'" width="98px" height="29px">';
			}
			
			if($result['notes_pin'] != null && $result['notes_pin'] != ""){	
			$searchresulthtml .='<img style="text-align: center;" src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/key.png" width="35px" height="35px">';
			}
			$searchresulthtml .='</td>';
			
			$searchresulthtml .='<td style="text-align:right;width:10%; line-height:20.2px;">'.$facilityname['facility'].'  </td>';
			
			$searchresulthtml .='<td style="text-align:right;width:10%; line-height:20.2px;">';  
			
			
			
			/*if($allforms || $allimages){
				$searchresulthtml .='<a href="'.$sharePage.'&notes_id='.$result['notes_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/attachment_icons.png" width="35px" height="35px" ></a> ';
				
			}*/
			
			if($allimages){
				foreach($allimages as $images){
				$searchresulthtml .='<a href="'.$images['notes_file'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/attachment_icons.png" width="35px" height="35px" ></a> ';
				}
			}
				
			$searchresulthtml .='</td>';
	 			
			$searchresulthtml .='</tr>';
			$searchresulthtml .='</table>';
			
			$quantity = 1;
			$notesid = $result['notes_id'];
			$this->db->query("UPDATE " . DB_PREFIX . "notes SET share_notes = (share_notes + " . (int)$quantity . ") WHERE notes_id = '" . (int)$notesid . "' ");

			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$is_android = $this->request->post['is_android'];
				}else{
					$is_android = '1';
				}
					
			$this->db->query("INSERT INTO `" . DB_PREFIX . "share_notes` SET notes_id = '" . $notesid . "', user_id = '" . $this->request->post['user_id'] . "', notes_pin = '" . $this->request->post['user_pin'] . "', email = '" . $this->request->post['user_email'] . "', share_notes_otp = '" . $sharepasswd . "', date_added = NOW(), share_type = 'search', phone_device_id = '" . $this->request->post['phone_device_id'] . "', device_unique_id = '" . $this->request->post['device_unique_id'] . "', is_android = '" . $is_android . "' ");
			
		}
		
		/*var_dump($searchresulthtml);die;*/
		
		$pdf->writeHTML($searchresulthtml, true, 0, true, 0);
			$pdf->lastPage();
			$searchfilename = 'NoteActive_Share_'.rand().'.pdf';
			$pdf->Output($dirpath. $searchfilename, 'F');
		
		}
		
		$message33 = "";
		$message33 .= $this->emailtemplate($notedetail, $username);	
		
		if( $useremail != NULL && $useremail != ""){
			$user_email = $useremail; 
		}
		
		if( $this->request->post['user_email'] != NULL && $this->request->post['user_email'] != ""){
			$assignto_email = $this->request->post['user_email']; 
		}
		
		$edata = array();		
		$edata['user_email'] = $user_email;
		$edata['dirpath'] = $dirpath;
		$edata['filename'] = $searchfilename;
		$edata['assignto_email'] = $assignto_email;	
		
			$edata['type'] = "12";
			$edata['username'] = $this->request->post['user_id'];
			$edata['who_user'] = $this->request->post['user_id'];
			$edata['when_date'] = date("l");
			
		
				
			$email_status = $this->model_api_emailapi->createMails($edata);
			
		//$email_status = $this->model_api_emailapi->sendmail($edata1);
		
		
			unlink($dirpath.$searchfilename);
		
		
		
			
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
	
	
	public function emailtemplatePassword($username, $password){
	$html = "";
	$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Shared PDF Password</title>

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
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Shared PDF Password</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$username.'!</h1>
							
							
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
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Shared PDF Password</small></h4>';
							
							
								$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								PDF that you have shared has been protected by a password. You may share this password with the person you sent the pdf to. By sharing this password you are attesting that the person receiving this password is authorized to view the shared data and you have the authority to share such data. The password is: '.$password.'
								</p>';
							
							
							
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