<?php
class Controllernotessharenote extends Controller {
	private $error = array();

	public function addnote() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			$filename = 'NoteActive_Share_'.rand().'.pdf';
			$dirpath = DIR_IMAGE .'share/';		
			
			$username = $this->request->post['user_id'];
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUser($username);
			$useremail  = $userdetail['email'];
			
			
			$notesid = $this->request->get['notes_id'];
			$this->load->model('notes/notes');
			$notedetail = $this->model_notes_notes->getnotes($notesid);
		
			//var_dump($useremail);
		
		
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
			
		if($allforms){
			require_once(DIR_APPLICATION . 'aws/getItem.php');
			foreach($allforms as $forms){
				
				if($forms['form_type'] == '1'){
					$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

					
					$pdf->SetProtection($protection, $sharepasswd, "ourcodeworld-master", 0, null);
					
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
					
					
					
					$response = $dynamodb->getItem([
						'TableName' => DYNAMODBINCIDENT,
						'Key' => [
							'incidentform_id' => [ 'N' => $forms['form_type_id'] ] 
						]
					]);
					
					$formhtml='';
				
					$formhtml .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">CCC Incident Number</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.$forms['incident_number'].'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Program Code</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['program_code']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">CCC Duty Officer</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['duty_officer']['S']).'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Program Name</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['program_name']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Region</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['region']['S']).'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Report Date</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['report_date']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Date</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_date']['S']).'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Report Time</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['report_time']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Time</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_time']['S']).'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Place of Occurrence</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['place_of_occurrence']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Icon</td>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%"></td>';
					
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">';
					
					$formhtml .='<table>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;">PAR Restraint Involved</td>';
					$formhtml .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['restraint_involved']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;">Was Staff PAR Certified:</td>';
					$formhtml .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['staff_par_certified']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;">Staff to Youth Ratio:</td>';
					$formhtml .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['staff_to_youth_ratio']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;">Was Internal Investigation Initiated ?</td>';
					$formhtml .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['investigation_initiated']['S']).'</td>';
					$formhtml .='</tr>';
					$formhtml .='</table>';
					$formhtml .='</td>';
					
					
					$formhtml .='<tr>';
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Category</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_category']['S']).'</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">TagID</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['emp_tag_id']['S']).'</td>';
					$formhtml .='</tr>';
					
					
					$formhtml .='<tr>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Background Information</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['background_information']['S']).'</td>';
					
					
					$formhtml .='</tr>';
					
					
					
					$formhtml .='<tr>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Immediate Action Taken</td>';
					
					$formhtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","", $response['Item']['immediate_action_taken']['S']).'</td>';
					$formhtml .='</tr>';
					$formhtml .='</table>';
					
					//var_dump($formhtml);
					
					$pdf->writeHTML($formhtml, true, 0, true, 0);
			
					$pdf->lastPage();

					
					$incidentfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
					
					$pdf->Output($dirpath. $incidentfilename, 'F');
						
					
				}	
				
				
				if($forms['form_type'] == '2'){
					$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$pdf->SetProtection($protection, $sharepasswd, "ourcodeworld-master", 0, null);

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
					
					
					$cresponse = $dynamodb->getItem([
					'TableName' => DYNAMODBCHECKLIST,
						'Key' => [
							'checklist_id' => [ 'N' => $forms['form_type_id'] ] 
						]
					]);
					
					$checklisthtml='';
				
					$checklisthtml .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
					
					$checklisthtml .='<tr>';
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location A</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_1']['S'])){
						$form_date_1 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_1']['S'])));
					}else{
						$form_date_1 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_1.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys1']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['girl1']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box1']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					
					
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location B</td>';
					
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_2']['S'])){
						$form_date_2 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_2']['S'])));
					}else{
						$form_date_2 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_2.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_2']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_2']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box2']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					
					
					$checklisthtml .='<tr>';
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location C</td>';
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_3']['S'])){
						$form_date_3 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_3']['S'])));
					}else{
						$form_date_3 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_3.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_3']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_3']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box3']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location D</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_4']['S'])){
						$form_date_4 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_4']['S'])));
					}else{
						$form_date_4 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_4.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_4']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_4']['S']).'</td>';
					
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box4']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location E</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_5']['S'])){
						$form_date_5 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_5']['S'])));
					}else{
						$form_date_5 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_5.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['boys_5']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_5']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box5']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					$checklisthtml .='</table>';
					
					//var_dump($checklisthtml);
					
					$pdf->writeHTML($checklisthtml, true, 0, true, 0);
					$pdf->lastPage();

					$bedcheckfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
					
					$pdf->Output($dirpath. $bedcheckfilename, 'F');
					
				}
			
			}
		}
		

			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetProtection($protection, $sharepasswd, "ourcodeworld-master", 0, null);

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
			$html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:40%">Note Description</td>';
			$html .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Facility</td>';
			$html .='    <td valign="middle" style="text-align: right;background-color: #DAEEF3;width:10%">Username</td>';
			$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Signature/Pin</td>';
			//$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Download</td>';
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
		
		
		
		$html .='<td style="line-height:20.2px;width:40%;text-align:left;'.$cssStyle.'">';
		
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
		
		if($notedetail['task_type'] == "1"){ 
			$html .=  'Bed Check for ';
		} 
		
		if($notedetail['task_time'] != null && $notedetail['task_time'] != "00:00:00"){ 
			$html .=  date('h:i A', strtotime($notedetail['task_time']));
		} 
		
		if($notedetail['task_type'] == "1"){ 
			$html .=  'Completed. The following details were noted: ';
		}

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
		
		$html .='</tr>';
		
		$html .='</table>';

		
	//var_dump($html);die;
	$pdf->writeHTML($html, true, 0, true, 0);
	$pdf->lastPage();
	
	//die;
	$pdf->Output($dirpath. $filename, 'F'); 
	//$pdf->Output(APP . 'webroot' . DS . 'files' . DS . 'pdf' . DS . 'filename.pdf', 'F');
	
	
		if($this->config->get('config_mail_protocol')  == 'smtp'){			
					$message33 = "";
					$message33 .= $this->emailtemplate($notedetail, $username);	
					
					
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
					$mail->setFrom('app-monitoring@noteactive.com', $this->config->get('config_name'));  
					$mail->addReplyTo('app-monitoring@noteactive.com', $this->config->get('config_name'));  
								
					/*if( $useremail != NULL && $useremail != ""){
						$mail->addAddress($useremail); 
					}*/
					
					if( $this->request->post['user_email'] != NULL && $this->request->post['user_email'] != ""){
						$mail->addAddress($this->request->post['user_email']); 
					}
					
					
								
					$mail->WordWrap = 50;                               
					$mail->isHTML(true);                       
									 
					$mail->Subject = 'Shared Note';
					$mail->msgHTML($message33);
					$mail->msgHTML($message33);
									
					$mail->addAttachment($dirpath.$filename);
					
					
					if($allimages){
						foreach ($allimages as $image) {
							$url = $image['notes_file'];
							$mediafilename = 'NoteActive_Media_'.$image['notes_media_id'].'.'.$image['notes_media_extention'];
							$img = $dirpath.$mediafilename;
							file_put_contents($img, file_get_contents($url));
							
							$mail->addAttachment($img);
						
						}
					}
					
					if($allforms){
						foreach ($allforms as $forms) {
							if($forms['form_type'] == '1'){
								$incidentfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
								$incident1 = $dirpath.$incidentfilename;
								$mail->addAttachment($incident1);
							}
							
							if($forms['form_type'] == '2'){
								$checklistfilename = 'NoteActive_Form_'.$forms['form_type_id'].'.pdf';
								$check2 = $dirpath.$checklistfilename;
								$mail->addAttachment($check2);
							}
						
						}
					}
				
					$mail->send();
							
					$quantity = 1;
					$this->db->query("UPDATE " . DB_PREFIX . "notes SET share_notes = (share_notes + " . (int)$quantity . ") WHERE notes_id = '" . (int)$notesid . "' ");

					$this->db->query("INSERT INTO `" . DB_PREFIX . "share_notes` SET notes_id = '" . $notesid . "', user_id = '" . $this->request->post['user_id'] . "', notes_pin = '" . $this->request->post['user_pin'] . "', email = '" . $this->request->post['user_email'] . "', share_notes_otp = '" . $sharepasswd . "', date_added = NOW(), share_type = 'single' ");
					
					
						$mail->ClearAddresses();
						$mail->ClearAttachments();
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
						$mail->setFrom('app-monitoring@noteactive.com', $this->config->get('config_name'));  
						$mail->addReplyTo('app-monitoring@noteactive.com', $this->config->get('config_name'));  
						
						if($sharenotes_send_email == '1'){
							if( $useremail != NULL && $useremail != ""){
								$mail->addAddress($useremail); 
							}
						}else{
							$mail->addAddress($this->config->get('config_email')); 
						}
						
						$message334 = "";
						$message334 .= $this->emailtemplatePassword($notedetail, $username, $sharepasswd);	
									
						$mail->WordWrap = 50;                               
						$mail->isHTML(true);                       
										 
						$mail->Subject = 'Shared PDF Password';
						$mail->msgHTML($message334);
						$mail->msgHTML($message334);
						$mail->send();
					
					
				}
				
			unlink($dirpath.$filename);
			if($allimages){
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
			}
		
		$this->session->data['success'] = "Send email successfully! ";
		
	}
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['userpin'])) {
			$this->data['error_userpin'] = $this->error['userpin'];
		} else {
			$this->data['error_userpin'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
	
		if (isset($this->error['user_email'])) {
			$this->data['error_email'] = $this->error['user_email'];
		} else {
			$this->data['error_email'] = '';
		}
			
		if (isset($this->request->post['user_pin'])) {
			$this->data['user_pin'] = $this->request->post['user_pin'];
		} else {
			$this->data['user_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if (isset($this->request->post['user_email'])) {
			$this->data['user_email'] = $this->request->post['user_email'];
		} else {
			$this->data['user_email'] = '';
		}
		
		
		$this->data['config_share_notes'] = $this->customer->isNotesShare();
		$this->data['config_sharepin_status'] = $this->customer->isSharePin();
			
		$this->template = $this->config->get('config_template') . '/template/notes/sharenote.tpl';
		$this->response->setOutput($this->render());
	
	}
	
	
	protected function validateForm(){
		if($this->session->data['advance_search'] != '1'){
			if($this->request->get['notes_id'] == NULL && $this->request->get['notes_id'] == ""){
				$this->error['warning'] = 'You cannot share new notes';
			}
		}
		
		if ((utf8_strlen($this->request->post['user_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['user_email'])) {
			$this->error['user_email'] = 'Email is required';
		}
		
		$required_pin = $this->customer->isSharePin();
		
		if($required_pin == '1'){
			if($this->request->post['user_pin'] == NULL && $this->request->post['user_pin'] == ""){
				$this->error['userpin'] = 'Pin is required';
			}
		}
		
		if($this->request->post['user_id'] != NULL && $this->request->post['user_id'] != ""){
			
			$username = $this->request->post['user_id'];
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUser($username);
			
			if($userdetail['email'] == NULL && $userdetail['email'] == ""){
			
				$this->error['erroremail'] = 'Selected user donot have email address, please select another user';
			
			}
			
			if($required_pin == '1'){
				if($this->request->post['user_pin'] != $userdetail['user_pin']){
					$this->error['userpin'] = "Please provide a valid pin";
				}
			}
			
			
			
			$this->load->model('user/user_group');
			$userrole_info = $this->model_user_user_group->getUserGroup($userdetail['user_group_id']);
			
			if($userrole_info['share_notes'] != '1'){
				$this->error['warning'] = 'You are not authorized to share notes';
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
	
	public function emailtemplatePassword($result, $username, $password){
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
	
	public function searchnoteshare(){
		
		$sharePage = $this->url->link('notes/sharenote/downloadmedia', '' . $url, 'SSL');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		/*	
			var_dump($this->session->data['note_date_search']);
			var_dump($this->session->data['note_date_from']);
			var_dump($this->session->data['note_date_to']);
			var_dump($this->session->data['keyword']);
			var_dump($this->session->data['user_id']);
			var_dump($this->session->data['search_emp_tag_id']);
			var_dump($this->session->data['ssincedentform']);
			var_dump($this->session->data['highlighter']);
			var_dump($this->session->data['activenote']);
		*/
			
		if($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->session->data['note_date_from']));
		}
		if($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->session->data['note_date_to']));
		}
		
		
		$timezone_name = $this->customer->isTimezone();
		$timeZone = date_default_timezone_set($timezone_name);
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$noteTime =  date('H:i:s');
			
			$date = str_replace('-', '/', $this->request->get['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[1]."-".$res[0]."-".$res[2];
			
			$this->data['note_date'] = $changedDate.' '.$noteTime;
			$searchdate = $this->request->get['searchdate'];
			
			if( ($searchdate) >= (date('m-d-Y')) ) {
				$this->data['back_date_check'] = "1";
			}else{
				$this->data['back_date_check'] = "2";
			}
		} else {
			$this->data['note_date'] =  date('Y-m-d H:i:s');
		}
		
		
		if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
			$noteTime =  date('H:i:s');
			
			$date = str_replace('-', '/', $this->request->get['fromdate']);
			$res = explode("/", $date);
			$changedDate = $res[1]."-".$res[0]."-".$res[2];
			
			$note_date_from = date('Y-m-d', strtotime($changedDate));
			
			$note_date_to =  date('Y-m-d');
			$this->session->data['advance_search'] = '1';
			
			
			if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
				$this->session->data['highlighter'] = $this->request->get['highlighter'];
			}
			
			if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
				$this->session->data['activenote'] = $this->request->get['activenote'];
			}
			
			
		} 
		
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		
		$config_admin_limit = "100";
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'searchdate' => $searchdate,
			'searchdate_app' => '1',
			'facilities_id' => $this->customer->getId(),
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $this->session->data['keyword'],
			'form_search' => $this->session->data['form_search'],
			'user_id' => $this->session->data['user_id'],
			'highlighter' => $this->session->data['highlighter'],
			'activenote' => $this->session->data['activenote'],
			'emp_tag_id' => $this->session->data['search_emp_tag_id'],
			'advance_searchapp' => $this->session->data['advance_search'],
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		$this->load->model('notes/notes');
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		
		
		$results = $this->model_notes_notes->getnotess($data);	
			
		//var_dump($results);
		
		$filename = 'search_'.rand().'.pdf';
			$dirpath = DIR_IMAGE .'share/';	
			
			$sharepasswd = mt_rand(100000, 999999);
			$protection = array();
			$sharenotes_assemble = $this->customer->isNotesShareAssemble();
			$sharenotes_copy = $this->customer->isNotesShareCopy();
			$sharenotes_modify = $this->customer->isNotesShareModify();
			$sharenotes_print = $this->customer->isNotesSharePrint();
			$sharenotes_send_email = $this->customer->isNotesShareSendEmail();
			
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
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetProtection($protection, $sharepasswd, "ourcodeworld-master", 0, null);

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
			
			
			
			if($allforms || $allimages){
				$searchresulthtml .='<a href="'.$sharePage.'&notes_id='.$result['notes_id'].'" target="_blank"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/image/attachment_icons.png" width="35px" height="35px" ></a> ';
				
			}
				
			$searchresulthtml .='</td>';
	 			
			$searchresulthtml .='</tr>';
			$searchresulthtml .='</table>';
			
			$quantity = 1;
			$notesid = $result['notes_id'];
			$this->db->query("UPDATE " . DB_PREFIX . "notes SET share_notes = (share_notes + " . (int)$quantity . ") WHERE notes_id = '" . (int)$notesid . "' ");		
					
			$this->db->query("INSERT INTO `" . DB_PREFIX . "share_notes` SET notes_id = '" . $notesid . "', user_id = '" . $this->request->post['user_id'] . "', notes_pin = '" . $this->request->post['user_pin'] . "', email = '" . $this->request->post['user_email'] . "', share_notes_otp = '" . $sharepasswd . "', date_added = NOW(), share_type = 'search' ");
			
		}
		
		/*var_dump($searchresulthtml);die;*/
		
		$pdf->writeHTML($searchresulthtml, true, 0, true, 0);
			$pdf->lastPage();
			$searchfilename = 'NoteActive_Share_'.rand().'.pdf';
			$pdf->Output($dirpath. $searchfilename, 'F');
		
		}
		
		if($this->config->get('config_mail_protocol')  == 'smtp'){			
					$message33 = "";
					$message33 .= $this->emailtemplate($notedetail, $username);	
					
					
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
					$mail->setFrom('app-monitoring@noteactive.com', $this->config->get('config_name'));  
					$mail->addReplyTo('app-monitoring@noteactive.com', $this->config->get('config_name'));  
								
					if( $useremail != NULL && $useremail != ""){
						$mail->addAddress($useremail); 
					}
					
					if( $this->request->post['user_email'] != NULL && $this->request->post['user_email'] != ""){
						$mail->addAddress($this->request->post['user_email']); 
					}
					
					
								
					$mail->WordWrap = 50;                               
					$mail->isHTML(true);                       
									 
					$mail->Subject = 'Shared Note';
					$mail->msgHTML($message33);
					$mail->msgHTML($message33);
									
					$mail->addAttachment($dirpath.$searchfilename);

					$mail->send();
					
					
						$mail->ClearAddresses();
						$mail->ClearAttachments();
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
						$mail->setFrom('app-monitoring@noteactive.com', $this->config->get('config_name'));  
						$mail->addReplyTo('app-monitoring@noteactive.com', $this->config->get('config_name'));  
						
						if($sharenotes_send_email == '1'){
							if( $useremail != NULL && $useremail != ""){
								$mail->addAddress($useremail); 
							}
						}else{
							$mail->addAddress($this->config->get('config_email')); 
						}
						
						$message334 = "";
						$message334 .= $this->emailtemplatePassword($notedetail, $username, $sharepasswd);	
									
						$mail->WordWrap = 50;                               
						$mail->isHTML(true);                       
										 
						$mail->Subject = 'Shared PDF Password';
						$mail->msgHTML($message334);
						$mail->msgHTML($message334);
						$mail->send();
						
				}
			unlink($dirpath.$searchfilename);
			$this->session->data['success'] = "Send email successfully! ";
		
	}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['userpin'])) {
			$this->data['error_userpin'] = $this->error['userpin'];
		} else {
			$this->data['error_userpin'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
	
		if (isset($this->error['user_email'])) {
			$this->data['error_email'] = $this->error['user_email'];
		} else {
			$this->data['error_email'] = '';
		}
			
		if (isset($this->request->post['user_pin'])) {
			$this->data['user_pin'] = $this->request->post['user_pin'];
		} else {
			$this->data['user_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if (isset($this->request->post['user_email'])) {
			$this->data['user_email'] = $this->request->post['user_email'];
		} else {
			$this->data['user_email'] = '';
		}
		
		
		$this->data['config_share_notes'] = $this->customer->isNotesShare();
		$this->data['config_sharepin_status'] = $this->customer->isSharePin();
		
		$url2 = "";
		if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
			$url2 .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['action2'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url2, 'SSL');
		
		$this->template = $this->config->get('config_template') . '/template/notes/sharenote.tpl';
		$this->response->setOutput($this->render());	
	}
	
	public function searchnotepage(){
			
		if($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->session->data['note_date_from']));
		}
		if($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->session->data['note_date_to']));
		}
		
		
		$timezone_name = $this->customer->isTimezone();
		$timeZone = date_default_timezone_set($timezone_name);
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$noteTime =  date('H:i:s');
			
			$date = str_replace('-', '/', $this->request->get['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[1]."-".$res[0]."-".$res[2];
			
			$this->data['note_date'] = $changedDate.' '.$noteTime;
			$searchdate = $this->request->get['searchdate'];
			
			if( ($searchdate) >= (date('m-d-Y')) ) {
				$this->data['back_date_check'] = "1";
			}else{
				$this->data['back_date_check'] = "2";
			}
		} else {
			$this->data['note_date'] =  date('Y-m-d H:i:s');
		}
		
		
		if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
			$noteTime =  date('H:i:s');
			
			$date = str_replace('-', '/', $this->request->get['fromdate']);
			$res = explode("/", $date);
			$changedDate = $res[1]."-".$res[0]."-".$res[2];
			
			$note_date_from = date('Y-m-d', strtotime($changedDate));
			
			$note_date_to =  date('Y-m-d');
			$this->session->data['advance_search'] = '1';
			
			
			if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
				$this->session->data['highlighter'] = $this->request->get['highlighter'];
			}
			
			if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
				$this->session->data['activenote'] = $this->request->get['activenote'];
			}
			
			
		} 
		
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		
		$config_admin_limit = "100";
		
		
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'searchdate' => $searchdate,
			'searchdate_app' => '1',
			'facilities_id' => $this->customer->getId(),
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $this->session->data['keyword'],
			'form_search' => $this->session->data['form_search'],
			'user_id' => $this->session->data['user_id'],
			'highlighter' => $this->session->data['highlighter'],
			'activenote' => $this->session->data['activenote'],
			'emp_tag_id' => $this->session->data['search_emp_tag_id'],
			'advance_searchapp' => $this->session->data['advance_search'],
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		$this->load->model('notes/notes');
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		//var_dump($notes_total);
		
		$this->data['notes_totals'] = ceil($notes_total/100);
		$this->data['sharenotes_limit'] = 100;
		//var_dump($this->data['order_totals']);
		
		
		$this->data['sharePage'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url, 'SSL');
			
		
		
		$this->template = $this->config->get('config_template') . '/template/notes/sharenotepage.tpl';
		$this->response->setOutput($this->render());
		
		
	}
	
	
	public function downloadmedia(){
			
			$notes_id = $this->request->get['notes_id'];
			$this->load->model('notes/notes');
			//$notedetail = $this->model_notes_notes->getnotes($notesid);
			
			$dirpath = DIR_IMAGE .'share/';		
			
			$allforms = $this->model_notes_notes->getforms($notes_id);
			$allimages = $this->model_notes_notes->getImages($notes_id);
			
			require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
			
			
			if($allforms){
			
			require_once(DIR_APPLICATION . 'aws/getItem.php');
			
			foreach($allforms as $forms){
				
				if($forms['form_type'] == '1'){
					
					$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
					
				
					$response = $dynamodb->getItem([
						'TableName' => DYNAMODBINCIDENT,
						'Key' => [
							'incidentform_id' => [ 'N' => $forms['form_type_id'] ] 
						]
					]);
					
					
					
					$formhtml2='';
				
					$formhtml2 .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">CCC Incident Number</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.$forms['incident_number'].'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Program Code</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['program_code']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">CCC Duty Officer</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['duty_officer']['S']).'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Program Name</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['program_name']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Region</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['region']['S']).'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Report Date</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['report_date']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Date</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_date']['S']).'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Report Time</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['report_time']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Time</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_time']['S']).'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Place of Occurrence</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['place_of_occurrence']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Icon</td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%"></td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">';
					
					$formhtml2 .='<table>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">PAR Restraint Involved</td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['restraint_involved']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">Was Staff PAR Certified:</td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['staff_par_certified']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">Staff to Youth Ratio:</td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['staff_to_youth_ratio']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">Was Internal Investigation Initiated ?</td>';
					$formhtml2 .='<td valign="middle" style="text-align: left;">'.str_replace("&nbsp;","",$response['Item']['investigation_initiated']['S']).'</td>';
					$formhtml2 .='</tr>';
					$formhtml2 .='</table>';
					$formhtml2 .='</td>';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Incident Category</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['incident_category']['S']).'</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">TagID</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['emp_tag_id']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Background Information</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","",$response['Item']['background_information']['S']).'</td>';
					$formhtml2 .='</tr>';
					
					$formhtml2 .='<tr>';
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">Immediate Action Taken</td>';
					
					$formhtml2 .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:20%">'.str_replace("&nbsp;","", $response['Item']['immediate_action_taken']['S']).'</td>';
					$formhtml2 .='</tr>';
					$formhtml2 .='</table>';
					
					$pdf->writeHTML($formhtml2, true, 0, true, 0);
					$pdf->lastPage();

					
					$incidentfilename = 'NoteActive_Form_Search_'.$forms['form_type_id'].'.pdf';
					
					$pdf->Output($dirpath. $incidentfilename, 'F');
					
					echo "<a target='_blank' href='".HTTP_SERVER."image/share/".$incidentfilename."'><img src='sites/view/digitalnotebook/image/add_form.png' width='35px' height='35px'></a><br>";
						
					
				}	
				
				
				
				if($forms['form_type'] == '2'){
					$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
					
					
					$cresponse = $dynamodb->getItem([
					'TableName' => DYNAMODBCHECKLIST,
						'Key' => [
							'checklist_id' => [ 'N' => $forms['form_type_id'] ] 
						]
					]);
					
					$checklisthtml='';
				
					$checklisthtml .='<table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">';
					
					$checklisthtml .='<tr>';
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location A</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_1']['S'])){
						$form_date_1 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_1']['S'])));
					}else{
						$form_date_1 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_1.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys1']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['girl1']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box1']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location B</td>';
					
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_2']['S'])){
						$form_date_2 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_2']['S'])));
					}else{
						$form_date_2 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_2.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_2']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_2']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box2']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					$checklisthtml .='<tr>';
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location C</td>';
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_3']['S'])){
						$form_date_3 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_3']['S'])));
					}else{
						$form_date_3 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_3.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_3']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_3']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box3']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location D</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_4']['S'])){
						$form_date_4 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_4']['S'])));
					}else{
						$form_date_4 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_4.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['boys_4']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_4']['S']).'</td>';
					
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box4']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					
					$checklisthtml .='<tr>';
					
					$checklisthtml .='<td style="text-align: left;background-color: #DAEEF3;width:20%">Bed Location E</td>';
					
					if(str_replace("&nbsp;","",$cresponse['Item']['form_date_5']['S'])){
						$form_date_5 = date('m-d-Y h:i A', strtotime(str_replace("&nbsp;","",$cresponse['Item']['form_date_5']['S'])));
					}else{
						$form_date_5 = '';
					}
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.$form_date_5.'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Boys</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['boys_5']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Girls</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'.str_replace("&nbsp;","",$cresponse['Item']['girl_5']['S']).'</td>';
					
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Status</td>';
					$checklisthtml .='<td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">'. str_replace("&nbsp;","",$cresponse['Item']['chk_box5']['S']).'</td>';
					
					$checklisthtml .='</tr>';
					
					$checklisthtml .='</table>';
					
					//var_dump($checklisthtml);
					
					$pdf->writeHTML($checklisthtml, true, 0, true, 0);
					$pdf->lastPage();

					$bedcheckfilename = 'NoteActive_Form_Search_'.$forms['form_type_id'].'.pdf';
					
					$pdf->Output($dirpath. $bedcheckfilename, 'F');
					echo "<a target='_blank' href='".HTTP_SERVER."image/share/".$bedcheckfilename."'><img src='sites/view/digitalnotebook/image/checklist-icon.png' width='35px' height='35px'> </a><br>";
				}
					
			}
		
			}
			
		
			
			if($allimages){
				foreach ($allimages as $image) {
					
					$url = $image['notes_file'];
					
						$mediafilename = 'NoteActive_Media_'.$image['notes_media_id'].'.'.$image['notes_media_extention'];
						$img = $dirpath.$mediafilename;
						
						//file_put_contents($img, file_get_contents($url));
						
						echo "<a target='_blank' href='".$url ."'><img src='sites/view/digitalnotebook/image/attachment_icons.png'></a><br>";
						
						}
					}
			
			
			
			
					
		
	}
}