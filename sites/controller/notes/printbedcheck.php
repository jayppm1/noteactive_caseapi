<?php  
class Controllernotesprintbedcheck extends Controller {
	private $error = array();
	
	public function index() {
	    $this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		$noteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		$printnoteurl = $this->url->link('form/form/printform', '' . $url, 'SSL');
		$firedrillnoteurl = $this->url->link('form/form/printmonthly_firredrill', '' . $url, 'SSL');
		$incidentnoteurl = $this->url->link('form/form/printincidentform', '' . $url, 'SSL');
		$innoteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		
		$this->language->load('notes/notes');
		
		$this->language->load('notes/notes');
		$this->load->model('notes/image');
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('notes/notes');
		$this->load->model('facilities/facilities');
		
		$this->load->model('notes/tags');
		
		$this->document->setTitle('Generate PDF');
		
		$this->load->model('notes/tags');
		
		$this->load->model('facilities/facilities');
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$facilities_id = $this->request->get['facilities_id'];
		}else{
			$facilities_id = $this->customer->getId();
		}
		
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$config_tag_status = $this->customer->isTag();
		$this->data['config_tag_status'] = $this->customer->isTag();
		
	
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$this->data['reports'] = array();
		
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		
			
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$searchdate = $this->request->get['searchdate'];;
			} else {
				$searchdate = date('m-d-Y',strtotime('now'));
			}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$notes_id = $this->request->get['notes_id'];
				$this->load->model('notes/notes');
				$notes_info = $this->model_notes_notes->getnotes($notes_id);	
				$notesid = $notes_info['parent_id'];
		} else {
			$notesid = '';
		}
		

		$data = array(
			'sort'  => $sort,
			'searchdate' => $searchdate,
			'notesid' => $notesid,
			'searchdate_app' => '1',
			'facilities_id' => $facilities_id,
			'is_bedchk'  => $this->request->get['is_bedchk'],
			'customer_key' => $this->session->data['webcustomer_key'],
			'order' => $order,
			'start'  => ($page - 1) * 10000,
			'limit'  => 10000
		); 
		
		
		$this->load->model('notes/notes');
		$results = $this->model_notes_notes->getnotess($data);	
		
		
		
		$journals = array();

		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('facilities/facilities');

		$this->load->model('setting/keywords');
		$this->load->model('setting/tags');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		
		
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
		$this->load->model('setting/image');
		
		$j=0;
		foreach ($results as $result) {
			
			
			$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
			
			$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
			$allimages = $this->model_notes_notes->getImages($result['notes_id']);
			$images = array();
			foreach ($allimages as $image) {
				
				$extension = $image['notes_media_extention'];
					if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
					}else
					if($extension == 'doc' || $extension == 'docx'){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
					}else
					if($extension == 'ppt' || $extension == 'pptx'){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
					}else
					if($extension == 'xls' || $extension == 'xlsx'){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
					}else
					if($extension == 'pdf'){
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
					}else{
						$keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
					}
				
				$images[] = array(
					'keyImageSrc' =>$keyImageSrc,// '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" style="margin-left: 4px;" />',
					'media_user_id' => $image['media_user_id'],
					'notes_type' => $image['notes_type'],
					'notes_file' => $image['notes_file'],
					'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
					'media_signature' => $image['media_signature'],
					'media_pin' => $image['media_pin'],
					'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id='.$image['notes_media_id'], 'SSL')
					
				);
			}
			
			$reminder_time = $reminder_info['reminder_time'];
			$reminder_title = $reminder_info['reminder_title'];

				if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
					$keyImageSrc1 = '<img src="'.$result['keyword_file_url'].'" wisth="35px" height="35px">';
						
				}else{
					$keyImageSrc1 = "";
				}
				
				
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$userPin = $result['notes_pin'];
				}else{
					$userPin = '';
				}
				
				
				if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
					$task_time = date('h:i A', strtotime($result['task_time']));
				}else{
					$task_time = "";
				}
				
				
				if ($config_tag_status == '1') {
					
					$alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
					
					if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
						$tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
						$privacy = $tagdata['privacy'];
						
						if($tagdata['privacy'] == '2'){
							if($this->session->data['unloack_success'] != '1'){
								$emp_tag_id = '';//$alltag['emp_tag_id'].':'.$tagdata['emp_first_name'];
							}else{
								$emp_tag_id = '';	
							}
						}else{
							$emp_tag_id = '';
						}
					}else{
						$emp_tag_id = '';
						$privacy = '';
						
					}
				}
				
				
				
				
				$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
				$noteskeywords = array();
				
				if($privacy == '2'){
					if($this->session->data['unloack_success'] == '1'){
						//$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
						
						if($allkeywords){
							$keyImageSrc12 = array();
							$keyname = array();
							$keyImageSrc11 = "";
							foreach ($allkeywords as $keyword) {
								$keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
								//$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
								//$keyname[] = $keyword['keyword_name'];
								//$keyname = array_unique($keyname);
								$noteskeywords[]= array(
									'keyword_file_url' =>$keyword['keyword_file_url'],
								);
							}
							
							//$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
							//$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
							$keyword_description = $result['notes_description'];
							
							$notes_description = $keyword_description;
						}else{
							$notes_description = $result['notes_description'];
						}
						
					}else{
						$notes_description = $result['notes_description'];
					}
				}else{
					//$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
					
					if($allkeywords){
							$keyImageSrc12 = array();
							$keyname = array();
							$keyImageSrc11 = "";
							foreach ($allkeywords as $keyword) {

								$keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
								//$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $keyword['keyword_name'];
								//$keyname[] = $keyword['keyword_name'];
								//$keyname = array_unique($keyname);
								
								$noteskeywords[]= array(
									'keyword_file_url' =>$keyword['keyword_file_url'],
								);
							}
							
							//$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
							//$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
							$keyword_description = $result['notes_description'];
							
							$notes_description =  $keyword_description;
					}else{
						$notes_description = $result['notes_description']; 
					} 
					
				}
				
					$allforms = $this->model_notes_notes->getforms($result['notes_id']);
					$forms = array();
					foreach ($allforms as $allform) {
						
						$forms[] = array(
								'form_type_id' => $allform['form_type_id'],
								'forms_id' => $allform['forms_id'],
								'design_forms' => $allform['design_forms'],
								'custom_form_type' => $allform['custom_form_type'],
								'notes_id' => $allform['notes_id'],
								'form_type' => $allform['form_type'],
								'notes_type' => $allform['notes_type'],
								'user_id' => $allform['user_id'],
								'signature' => $allform['signature'],
								'notes_pin' => $allform['notes_pin'],
								'incident_number' => $allform['incident_number'],
								'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added'])),
								
							); 
					}
				
				
				
				$notestasks = array();
				$grandtotal = 0;
				if($result['task_type'] == '1'){
					$alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
					foreach ($alltasks as $alltask) {
						$grandtotal = $grandtotal + $alltask['capacity'];
						$tags_ids_names = '';
						if($alltask['tags_ids'] != null && $alltask['tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['tags_ids']);
							
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);

								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$tags_ids_names .= $emp_tag_id.', ';

								}
							}
						}
						
						$ograndtotal = 0;
						$out_tags_ids_names = "";
						if($alltask['out_tags_ids'] != null && $alltask['out_tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['out_tags_ids']);
							$i=0;
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);

								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$out_tags_ids_names .= $emp_tag_id.', ';

								}
								$i++;
							}
							$ograndtotal = $i;
						}
						
						$notestasks[] = array(
								'notes_by_task_id' => $alltask['notes_by_task_id'],
								'locations_id' => $alltask['locations_id'],
								'task_type' => $alltask['task_type'],
								'task_content' => $alltask['task_content'],
								'user_id' => $alltask['user_id'],
								'signature' => $alltask['signature'],
								'notes_pin' => $alltask['notes_pin'],
								'task_time' => $alltask['task_time'],
								'media_url' => $alltask['media_url'],
								'capacity' => $alltask['capacity'],
								'location_name' => $alltask['location_name'],
								'location_type' => $alltask['location_type'],
								'notes_task_type' => $alltask['notes_task_type'],
								
								'task_comments' => $alltask['task_comments'],
								'role_call' => $alltask['role_call'],
								
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
								'room_current_date_time' => date('h:i A', strtotime($alltask['room_current_date_time'])),
								'tags_ids_names' => $tags_ids_names,
								'out_tags_ids_names' => $out_tags_ids_names,
								
							); 
							
					}
				}
				
				
				$notesmedicationtasks = array();
				if($result['task_type'] == '2'){
					$alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
					
					foreach ($alltmasks as $alltmask) {
						
						if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
							$taskTime = date('h:i A', strtotime($alltmask['task_time']));
						}
						
						$notesmedicationtasks[] = array(
								'notes_by_task_id' => $alltmask['notes_by_task_id'],
								'locations_id' => $alltmask['locations_id'],
								'task_type' => $alltmask['task_type'],
								'task_content' => $alltmask['task_content'],
								'user_id' => $alltmask['user_id'],
								'signature' => $alltmask['signature'],
								'notes_pin' => $alltmask['notes_pin'],
								'task_time' => $taskTime,
								'media_url' => $alltmask['media_url'],
								'capacity' => $alltmask['capacity'],
								'location_name' => $alltmask['location_name'],
								'location_type' => $alltmask['location_type'],
								'notes_task_type' => $alltmask['notes_task_type'],
								'tags_id' => $alltmask['tags_id'],
								'drug_name' => $alltmask['drug_name'],
								'dose' => $alltmask['dose'],
								'drug_type' => $alltmask['drug_type'],
								'quantity' => $alltmask['quantity'],
								'frequency' => $alltmask['frequency'],
								'instructions' => $alltmask['instructions'],
								'count' => $alltmask['count'],
								'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
								'task_comments' => $alltmask['task_comments'],
								'medication_file_upload' => $alltmask['medication_file_upload'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
								
								
							); 
							
							
					}
				
				
				}
				
				 
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
				
				$remdata = "";
				if($reminder_info != null && $reminder_info != ""){
					$remdata = "1";
				}else{
					$remdata = "2";
				}
				 
			
			$journals[] = array(
				'notes_id'    => $result['notes_id'],
				'visitor_log'    => $result['visitor_log'],
				'is_tag'    => $result['is_tag'],
				'is_archive'    => $result['is_archive'],
				'form_type'    => $result['form_type'],
				'generate_report'    => $result['generate_report'],
				'is_census'    => $result['is_census'],
				'is_android'    => $result['is_android'],
				'alltag'    => $alltag,
				'remdata'    => $remdata,
				'noteskeywords'    => $noteskeywords,
				'is_private'    => $result['is_private'],
				'share_notes'    => $result['share_notes'],
				'is_offline'    => $result['is_offline'],
				'review_notes'    => $result['review_notes'],
				'is_private_strike'    => $result['is_private_strike'],
				'checklist_status'    => $result['checklist_status'],
				'notes_type'    => $result['notes_type'],
				'strike_note_type'    => $result['strike_note_type'],
				'task_time'    => $task_time,
				'tag_privacy'    => $privacy,
				'incidentforms'    => $forms, 
				'notestasks'    => $notestasks, 
				'grandtotal'    => $grandtotal, 
				'ograndtotal'    => $ograndtotal, 
				'boytotals'    => $boytotals, 
				'girltotals'    => $girltotals, 
				'generaltotals'    => $generaltotals, 
				'residentstotals'    => $residentstotals, 
				'notesmedicationtasks'    => $notesmedicationtasks, 
				'task_type'    => $result['task_type'],
				'taskadded'    => $result['taskadded'],
				'assign_to'    => $result['assign_to'],
				'highlighter_value'   => $highlighterData['highlighter_value'],
				'notes_description'   => $notes_description,
				//'keyImageSrc'   => $keyImageSrc,
				//'fileOpen'   => $fileOpen,
				'images'   => $images,
				'notetime'   => date('h:i A', strtotime($result['notetime'])),
				'username'      => $result['user_id'],
				'notes_pin'      => $userPin,
				'signature'   => $result['signature'],
				'text_color_cut'   => $result['text_color_cut'],
				'text_color'   => $result['text_color'],
				'note_date'   => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date('m-d-Y', strtotime($result['date_added'])),
				'strike_user_name'   => $result['strike_user_id'],
				'strike_pin'   => $result['strike_pin'],
				'strike_signature'   => $result['strike_signature'],
				'strike_date_added'   => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
				'reminder_time'      => $reminder_time,
				'reminder_title'      => $reminder_title,
				'href'=>$this->url->link('notes/notes/insert', '' . '&reset=1&searchdate='.date('m-d-Y', strtotime($result['date_added'])) . $url, 'SSL'), 
				
			);
		}
		
	
		require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		
		

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('REPORT');
		$pdf->SetSubject('REPORT');
		$pdf->SetKeywords('REPORT');



		if ($this->config->get('pdf_report_image') && file_exists(DIR_SYSTEM . 'library/pdf_class/'.$this->config->get('pdf_report_image'))) {
			$imageLogo = $this->config->get('pdf_report_image');
			$PDF_HEADER_LOGO_WIDTH = "30";
						
		}else{
			$imageLogo = '4F-logo.png';
			$PDF_HEADER_LOGO_WIDTH = "30";
			$headerString = "";	
		}


			$date = str_replace('-', '/', $searchdate);
			$res = explode("/", $date);
			$searchdate2 = $res[1]."-".$res[0]."-".$res[2];
			
			$PDF_HEADER_TITLE = "Bed Check Report";
			//$headerString = 'Report Date: '. date('m/d/Y', strtotime($searchdate2)) ;
			$headerString = 'Report Date: '. date('m/d/Y', strtotime($searchdate2)) .' - '. $facilities_info['facility'];
			//$headerString = $facilities_info['facility'];
			
			$titleb = $PDF_HEADER_TITLE.'<br>'.$headerString;

		$pdf->SetHeaderData($imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE.'', $headerString);

		//$mytcpdfObject->setHtmlHeader('<table>...</table>');
		// set header and footer fonts
		//$pdf->setPrintHeader(false);
		//$pdf->setPrintFooter(false);


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
	$html .='<style>

    td {
        padding: 10px;
        margin: 10px;
       border: 1px solid #B8b8b8;
	   line-height:20.2px;
	   display:table-cell;
        padding:5px;
    }
	</style>
	<style>
		.btn {
		  border: 2px solid #fe963f;
			background-color: #fe963f;
			color: #fff;
		  padding: 10px 20px;
		  font-size: 16px;
		  cursor: pointer;
		}
		td {
		   border: 1px solid #B8b8b8;
		   line-height:30px;
		}
		body {
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
		}

		.top-container {
		  background-color: #2c3742;
		  padding: 30px;
		  text-align: center;
		}

		.header {
		  padding: 10px 16px;
		  background: #2c3742;
		  color: #f1f1f1;
		   height: 57px;
		  
		}

		.content {
		  padding: 16px;
		}

		.sticky {
		  position: fixed;
		  top: 0;
		  width: 98%;
		}

		.sticky + .content {
		  padding-top: 102px;
		}
		
		</style>
		<style type="text/css" media="print">
		@page 
		{
			size:  auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}

		
		@media print {
			a[href]:after {
				content: none !important;
			}
		}
		</style>
	';
		
		$this->load->model('createtask/createtask');
		$tasktype_info = $this->model_createtask_createtask->gettasktyperow('11');
		
		$html .='<div class="header" id="myHeader">
			<img class="note-logo" src="'.HTTPS_SERVER.'sites/view/digitalnotebook/image/note_logos.png" style="float: left;width: 43px;" />
			<div style="text-align: right;display:none">	
			<input type="button" class="btn" value="Print Report" />
			
			</div>
		</div><table width="100%" style="boder:none;" cellpadding="2" cellspacing="0" align="center">
		  <tr>
			
			<td colspan="5" style="text-align: left;"><h2 style="padding-bottom: 0;margin-bottom: 0;">'.$titleb.'</h2>
	
			</td>  
			
		  </tr>
		</table><table width="100%" style="boder:1px solid #000" cellpadding="2" cellspacing="0" align="center">';

		$html .='<thead>';
		
		if($tasktype_info['is_display_report'] == '1'){
        $html .='  <tr>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:15%">Room</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Time</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:40%">Client</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:5%">Total No</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:15%">Username</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:15%">Signature</td>';
		
		$html .='  </tr>';
		
		}
		
		if($tasktype_info['is_display_report'] == '2'){
        $html .='  <tr>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Date</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:10%">Time</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:50%">Description</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:15%">Username</td>';
        $html .='    <td valign="middle" style="text-align: left;background-color: #DAEEF3;width:15%">Signature</td>';
		
		$html .='  </tr>';
		
		}
		
		$html .=' </thead>';
	
		
		
		
		if($tasktype_info['generate_report'] == '1'){
			foreach($journals as $journal){
				
				
				if($tasktype_info['is_display_report'] == '1'){
					
					//var_dump($journal['generate_report']);
					if($journal['generate_report'] != '3'){
			
						$ctotal = 0;
						foreach($journal['notestasks'] as $notestask){
							
							
							
							$ctotal = $ctotal + $notestask['capacity'];
							
							$html .='<tr>';
							
							$html .='<td style="text-align:left;width:15%; line-height:20.2px;">'.$notestask['location_name'].'</td>';
							$html .='<td style="text-align:left;width:10%; line-height:20.2px;">'.$notestask['room_current_date_time'].'</td>';
							$html .='<td style="text-align:left;width:40%; line-height:20.2px;">';
							if($notestask['tags_ids_names']){
								$html .= $notestask['tags_ids_names'] .' | IN ';
							}
							
							if($notestask['out_tags_ids_names']){
								$html .= '<br>'.$notestask['out_tags_ids_names'].' | OUT ';
							}
							
							if($notestask['task_comments']){
								$html .=  $notestask['task_comments'];
							}
							
							$html .='</td>';
							$html .='<td style="text-align:left;width:5%; line-height:20.2px;">'.$notestask['capacity'].'</td>';
							$html .='<td style="text-align:left;width:15%; line-height:20.2px;">'.$journal['username'].'</td>';
							
							$html .='<td style="text-align:left;width:15%; line-height:20.2px;">';
							if($journal['username'] != null && $journal['username'] != "0"){ 
									
								if($journal['notes_type'] == "2"){ 
										$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px" style="    vertical-align: bottom;">';
								}elseif($journal['notes_type'] == "1"){  
									
										$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px" style="    vertical-align: bottom;">';
									 }elseif($journal['notes_pin'] != null && $journal['notes_pin'] != ""){  
										
										$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
									}else{ 
										//var_dump($journal['signature']);
										//echo "<hr>"; 
										$html .='<img style="text-align: center;" src="'.$journal['signature'].'" width="98px" height="29px" style="    vertical-align: bottom;">';
								}
									
							}
							
							$html .='</td>';
							
							$html .='</tr>';
						}
						//$i++;
					
					
					
						/*$html .='<tr>';
						$html .='<td style="text-align:left;width:20%; line-height:20.2px;">Total Boys</td>';
						$html .='<td style="text-align:left;width:80%; line-height:20.2px;">2</td>';
						$html .='</tr>';
						
						$html .='<tr>';
						$html .='<td style="text-align:left;width:20%; line-height:20.2px;">Total Girls</td>';
						$html .='<td style="text-align:left;width:80%; line-height:20.2px;">2</td>';
						$html .='</tr>';
						*/
						$html .='<tr>';
						$html .='<td colspan="3" style="text-align:right;width:65% line-height:20.2px;"><b>Total Clients IN </b></td>';
						$html .='<td style="text-align:left;width:35%; line-height:20.2px;">'.$ctotal.'</td>';
						$html .='<td colspan="2" style="text-align:left;width:35%; line-height:20.2px;"></td>';
						$html .='</tr>';
					}
				}
			
			
			
			if($tasktype_info['is_display_report'] == '2'){
				
				if($journal['generate_report'] != '3'){	
				$html .='<tr>';
			$html .='<td style="text-align:left;width:10%; line-height:20.2px;">'.$journal['date_added'].'</td>';
			$html .='<td style="text-align:left;width:10%; line-height:20.2px;">'.$journal['notetime'].'</td>';
			
			$cssStyle = "";
			if($journal['highlighter_value'] != null && $journal['highlighter_value'] != ""){
				$cssStyle .= 'background-color:'.$journal['highlighter_value'].'; ';
			}
			
			if($journal['text_color_cut'] == "1"){ 
				$cssStyle .= 'text-decoration: line-through;';
			}
					
			if($journal['text_color'] != null && $journal['text_color'] != ""){ 
				$cssStyle .= 'color:'.$journal['text_color'].';';
			}
					
			if(($journal['highlighter_value'] != null && $journal['highlighter_value'] != "") && ($journal['text_color'] == null && $journal['text_color'] == "")){ 
				//$cssStyle .= ';color:#FFF';
				/*if($journal['highlighter_value'] !='#ffff00'){
					$cssStyle .= ';color:#FFF;';
				}else{
					$cssStyle .= ';color:#000;';
				}*/
				
				if($journal['highlighter_value'] =='#ffff00'){
					$cssStyle .= 'color:#000;';
				}else if($journal['highlighter_value'] == '#ffffff'){
					$cssStyle .= 'color:#666;';
				}
				else{
					$cssStyle .= 'color:#FFF;';
				}
			}
			
			$html .='<td style="line-height:20.2px;width:50%;text-align:left;'.$cssStyle.'">';
			
				if($journal['generate_report'] == "3"){ 
				$html.='<img src="sites/view/digitalnotebook/image/generate-Report.png" width="35px" height="35px">';
				}
				if($journal['generate_report'] == "2"){ 
				$html.='<img src="sites/view/digitalnotebook/image/generate-Report.png" width="35px" height="35px">';
				}
				
				if($journal['is_census'] == "1"){ 
				$html.='<img src="sites/view/digitalnotebook/image/census.png" width="35px" height="35px">';
				}

				if($journal['is_offline'] == "1"){ 
				$html.='<img src="sites/view/digitalnotebook/image/wifi.png" width="35px" height="35px">';
				} 
				
				 if($journal['checklist_status'] == "1"){ 
					
					if($journal['taskadded'] == "2"){ 
					$html.='<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
					 } 
					
					
					
					if($journal['taskadded'] == "3"){ 
					$html.='<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
					} 
					
					
					if($journal['taskadded'] == "4"){ 
					$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete: ';
					 } 
					
				 }elseif($journal['checklist_status'] == "2"){ 
					$html.='<img src="sites/view/digitalnotebook/image/checklist-icon.png" width="35px" height="35px">';
				 }else{ 
				 
				if($journal['taskadded'] == "1"){ 
				$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Deleted: ';
				
				 } 
				
				 
				if($journal['taskadded'] == "2"){ 
				$html.='<img src="sites/view/javascript/task/image/complte-task.png" width="35px" height="35px">	';
				 } 
				
				
				 
				if($journal['taskadded'] == "3"){ 
				$html.='<img src="sites/view/javascript/task/image/incomplte-task-yellow-color.png" width="35px" height="35px">';
				 } 
				
				 
				if($journal['taskadded'] == "4"){ 
				$html.='<img src="sites/view/javascript/task/image/incomplte-task.png" width="35px" height="35px"> Incomplete: ';
				 } 
				 } 
				
				if($journal['noteskeywords']){ 
					foreach($journal['noteskeywords'] as $noteskeyword){ 
					
						$html.='<img src="'.$noteskeyword['keyword_file_url'].' " width="35px" height="35px">';
					} 
				}
			
			$html .= $journal['notes_description'];
			
			if($journal['notestasks'] != null && $journal['notestasks'] != ""){ 
		
					foreach($journal['notestasks'] as $notestask){ 
						$html .='<br> '.$notestask['task_content'].'';
					} 
					
					//$html .='<br>';
					if($journal['boytotals'][0] != null && $journal['boytotals'][0] != ""){ 
					$html .='Total  '.$journal['boytotals'][0]['loc_name'].': '.$journal['boytotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($journal['girltotals'][0] != null && $journal['girltotals'][0] != ""){ 
					$html .='Total  '.$journal['girltotals'][0]['loc_name'].': '.$journal['girltotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($journal['generaltotals'][0] != null && $journal['generaltotals'][0] != ""){ 
					$html .='Total  '.$journal['generaltotals'][0]['loc_name'].': '.$journal['generaltotals'][0]['total'].' ';
					$html .='<br>';
					}
					
					if($journal['residentstotals'][0] != null && $journal['residentstotals'][0] != ""){ 
					$html .='Total  '.$journal['residentstotals'][0]['loc_name'].': '.$journal['residentstotals'][0]['total'].' ';
					$html .='<br>';
					}
				
					
				} 

			$html .='</td>';
				$html .='<td style="text-align:left;width:15%; line-height:20.2px;">'.$journal['username'].'</td>';
				
				$html .='<td style="text-align:left;width:15%; line-height:20.2px;">';
				if($journal['username'] != null && $journal['username'] != "0"){ 
						
					if($journal['notes_type'] == "2"){ 
							$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/msg.png" width="35px" height="35px" style="    vertical-align: bottom;">';
					}elseif($journal['notes_type'] == "1"){  
						
							$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/final235x35.png" width="35px" height="35px" style="    vertical-align: bottom;">';
						 }elseif($journal['notes_pin'] != null && $journal['notes_pin'] != ""){  
							
							$html .='<img style="text-align: center;" src="sites/view/digitalnotebook/image/key.png" width="35px" height="35px" style="    vertical-align: bottom;">';
						}else{ 
							//var_dump($journal['signature']);
							//echo "<hr>"; 
							$html .='<img style="text-align: center;" src="'.$journal['signature'].'" width="98px" height="29px" style="    vertical-align: bottom;">';
					}
						
				}
				
				$html .='</td>';
				
				$html .='</tr>';
			}
			}
		}
		
		}
	
		$html .='</table>';

		//echo $html;
		
		
		$pdf->writeHTML($html, true, 0, true, 0);

		$pdf->lastPage();

		$pdf->Output('report_' . rand() . '.pdf', 'I');
		exit;
		

	}
}