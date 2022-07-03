<?php
class Modelnotessharenote extends Model {

	public function sharePdf($data) {
		$filename = 'NoteActive_Share_'.rand().'.pdf';
		$dirpath = DIR_IMAGE .'share/';		
		
		
		$username = $data['user_id'];
		$this->load->model('user/user');
		$userdetail = $this->model_user_user->getUser($username);
		$useremail  = $userdetail['email'];
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($data['user_id']);
		
		
		$notesid = $data['notes_id'];
		$this->load->model('notes/notes');
		$notedetail = $this->model_notes_notes->getnotes($notesid);
	
		
	
		$this->load->model('facilities/facilities');
		$facilityname = $this->model_facilities_facilities->getfacilities($notedetail['facilities_id']);
		
		$allforms = $this->model_notes_notes->getforms($notesid);
	
		//var_dump($facilityname);
		$allimages = $this->model_notes_notes->getImages($notesid);
		
		
		require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		
		$sharepasswd = mt_rand(100000, 999999);
		$protection = array();
		$sharenotes_assemble = $this->customer->isNotesShareAssemble();
		$sharenotes_copy = $this->customer->isNotesShareCopy();
		$sharenotes_modify = $this->customer->isNotesShareModify();
		$sharenotes_print = $this->customer->isNotesSharePrint();
		
		$sharenotes_send_email = $this->customer->isNotesShareSendEmail();
		
		$message334 = "";
		$message334 .= $this->emailtemplatePassword($user_info['username'], $sharepasswd);	
		
		$this->load->model('api/emailapi');
		
		if($sharenotes_send_email == '1'){
			if( $useremail != NULL && $useremail != ""){
				$user_email = $useremail;
			}
		
			$edata = array();
			$edata['message'] = $message334;
			$edata['subject'] = 'Shared PDF Password';
			$edata['user_email'] = $user_email;
				
			$email_status = $this->model_api_emailapi->sendmail($edata);
			
		}
		
		//var_dump($sharenotes_send_email);die;
		
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


if( $data['user_email'] != NULL && $data['user_email'] != ""){
	$user_email = $data['user_email']; 
}
				
$message33 = "";
$message33 .= $this->emailtemplate($notedetail, $username);	

$edata1 = array();
$edata1['message'] = $message33;
$edata1['subject'] = 'Shared Note';
$edata1['user_email'] = $user_email;
$edata1['dirpath'] = $dirpath;
$edata1['filename'] = $filename;
	
$email_status = $this->model_api_emailapi->sendmail($edata1);
			
	$quantity = 1;
	$this->db->query("UPDATE " . DB_PREFIX . "notes SET share_notes = (share_notes + " . (int)$quantity . ") WHERE notes_id = '" . (int)$notesid . "' ");

	$this->db->query("INSERT INTO `" . DB_PREFIX . "share_notes` SET notes_id = '" . $notesid . "', user_id = '" . $user_info['username'] . "', notes_pin = '" . $data['user_pin'] . "', email = '" . $data['user_email'] . "', share_notes_otp = '" . $sharepasswd . "', date_added = NOW(), share_type = 'single' ");
	
	
	$this->load->model('activity/activity');
	$adata['notes_id'] = $notesid;
	$adata['share_notes'] = $quantity;
	$adata['user_id'] = $data['user_id'];
	$adata['email'] = $data['user_email'];
	$adata['share_notes_otp'] = $sharepasswd;
	$adata['date_added'] = date('Y-m-d H:i:s');
	$this->model_activity_activity->addActivitySave('sharePdf', $adata, 'query');
	
		
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
		
	}
	
	public function validateForm2(){
		
		if($this->request->post['is_verification'] == "1"){
			if($this->request->post['reset_password_otp'] == ""){
				$this->error['warning'] = 'OPT required';
			}
			
			if($this->request->post['reset_password_otp'] != "" && $this->request->post['reset_password_otp'] != null){
				$this->load->model('user/user');
			
				$timezone_name = $this->customer->isTimezone();
				$timeZone = date_default_timezone_set($timezone_name);
				$date_added11 = date('Y-m-d H:i:s', strtotime('now'));
				
				$date_added1221 = date('Y-m-d 00:00:00', strtotime(' 0 minutes',strtotime($date_added11)));
				$current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes',strtotime($date_added11)));
				
				$data = array(
					'user_id' => $this->request->get['user_id'],
					'otp_type' => $this->request->get['otp_type'],
					'date_added_from' => $date_added1221,
					'date_added_to' => $current_date_plus,
					'facilities_id' => $this->customer->getId(),
					'notes_id' => $this->request->get['notes_id'],
					'share_note_otp' => $this->request->get['share_note_otp'],
				);
				$getUserdetail1 = $this->model_user_user->getuserOPT($data);
				
				if($this->request->post['reset_password_otp'] != $getUserdetail1['otp']){
					$this->error['warning'] = 'Please enter valid OPT';
				}
			}
		}else{
			if($this->request->post['userpin'] == ""){
				$this->error['warning'] = 'Userpin required';
			}
			if($this->request->post['userpin'] != "" && $this->request->post['userpin'] != null){
			
				$this->load->model('user/user'); 
				$userinfo = $this->model_user_user->getUserByAccessKey($this->request->get['accessKey']);
				if($this->request->post['userpin'] != $userinfo['user_pin']){
					$this->error['warning'] = 'Please enter valid Userpin';
				}
			}
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
		
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
?>