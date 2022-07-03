<?php
class Controllernotesgeneratereport extends Controller {

	public function index(){
	
		try {
		
		    //var_dump($this->request->post);die;
			unset ( $this->session->data ['timeout'] );
			$this->data ['searchUlr'] = $this->url->link ( 'notes/notes/insert', '' . $url, 'SSL' );
			
			$this->data ['error2'] = $this->request->get ['error2'];
			$this->load->model ( 'user/user' );
			$this->data ['form_outputkey'] = $this->formkey->outputKey ();
			$this->load->model ( 'facilities/online' );
			$datafa = array ();
			$datafa ['username'] = $this->session->data ['webuser_id'];
			$datafa ['activationkey'] = $this->session->data ['activationkey'];
			$datafa ['facilities_id'] = $this->customer->getId ();
			$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
			
			$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
			
			$this->load->model ( 'facilities/facilities' );
			$resulsst = $this->model_facilities_facilities->getfacilities ( $this->customer->getId () );
			
			if ($resulsst ['is_master_facility'] == '1') {
				if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
					$facilities_id = $this->session->data ['search_facilities_id'];
					$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$this->load->model ( 'setting/timezone' );
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
					$timezone_name = $timezone_info ['timezone_value'];
				} else {
					$facilities_id = $this->customer->getId ();
					$timezone_name = $this->customer->isTimezone ();
				}
			} else {
				$facilities_id = $this->customer->getId ();
				$timezone_name = $this->customer->isTimezone ();
			}
			date_default_timezone_set ( $timezone_name );
			
		
			
			$data = array (
					'status' => '1',
					'facilities_id' => $facilities_id 
			);
			
			$this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
			
		
			
			$this->load->model ( 'notes/notes' );
			$this->data ['tagassignotes'] = $this->model_notes_notes->gettagassigns ( $facilities_id );
			
			$this->load->model ( 'setting/highlighter' );
			$this->data ['highlighters'] = $this->model_setting_highlighter->gethighlighters ();
			
			// var_dump($this->data['highlighters']);
			
			$this->data ['note_date_from'] = date ( 'm-d-Y', strtotime ( 'now' ) );
			$this->data ['note_date_to'] = date ( 'm-d-Y', strtotime ( 'now' ) );
			
			$this->load->model ( 'createtask/createtask' );
			$this->data ['tasktypes'] = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
			
			$this->load->model ( 'setting/keywords' );
			
			$data3 = array (
					'facilities_id' => $facilities_id,
					'sort' => 'keyword_name' 
			);
			
			$this->data ['activenotes'] = $this->model_setting_keywords->getkeywords ( $data3 );
					
			$this->load->model ( 'resident/resident' );

			$data3 = array ();
			$data3 ['status'] = '1';
			$data3 ['facilities_id'] = $facilities_id;
			$data3 ['display_client'] = "";
			
			$this->data ['client_statuses'] = $this->model_resident_resident->getClientStatus ( $data3 );	
			
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id);
		
			$unique_id = $facility ['customer_key'];		
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			//$this->load->model ( 'notes/generatereport' );
			
			$shift_data3 = array ();	
			$shift_data3 ['facilities_id'] = $facilities_id;
			$shift_data3 ['customer_key'] = $customer_info ['activecustomer_id'];
			
			$this->load->model ( 'notes/shift' );
			$shifs = $this->model_notes_shift->getshifts ( $facility ['customer_key'] );
			$this->data ['shifts'] = array ();
			foreach ( $shifs as $shift ) {
				
				$this->data ['shifts'] [] = array (
					'shift_id' => $shift ['shift_id'],
					'shift_name' => $shift ['shift_name']
				);
			}
			
			$data3 = array ();
			$data3 ['status'] = '1';
			$data3 ['facilities_id'] = $facilities_id;
			$data3 ['customer_key'] = $customer_info ['activecustomer_id'];
			$data3 ['display_client'] = "";
					
			$this->data ['classifications'] = $this->model_resident_resident->getClientClassification ( $data3 );
			
			// var_dump($this->data['activenotes']);
			
			unset ( $this->session->data ['note_date_search'] );
			unset ( $this->session->data ['note_date_from'] );
			unset ( $this->session->data ['note_date_to'] );
			unset ( $this->session->data ['keyword'] );
			unset ( $this->session->data ['search_user_id'] );
			unset ( $this->session->data ['search_emp_tag_id'] );
			unset ( $this->session->data ['ssincedentform'] );
			unset ( $this->session->data ['highlighter'] );
			unset ( $this->session->data ['activenote'] );
			
			$this->load->model ( 'form/form' );
			$data3 = array ();
			$data3 ['status'] = '1';
			// $data3['order'] = 'sort_order';
			$data3 ['is_parent'] = '1';
			$data3 ['form_type'] = 'Database';
			$data3 ['facilities_id'] = $facilities_id;
			
			$custom_forms = $this->model_form_form->getforms ( $data3 );
			
			$this->data ['custom_forms'] = array ();
			foreach ( $custom_forms as $custom_form ) {
				
				$this->data ['custom_forms'] [] = array (
						'forms_id' => $custom_form ['forms_id'],
						'form_name' => $custom_form ['form_name'],
						'form_href' => $this->url->link ( 'form/form', '' . '&forms_design_id=' . $custom_form ['forms_id'], 'SSL' ) 
				);
			}
			
			

            $this->data ['action'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/generatereport', '' , 'SSL' ) );			
			
			$this->data ['redirect_url'] = str_replace ( '&amp;', '&', $this->url->link ( 'notes/generatereport/getReport', '' , 'SSL' ) );
			
			$this->template = $this->config->get ( 'config_template' ) . '/template/notes/generate_report.php';
			
			$this->children = array (
				'common/headerpopup' 
			);
			
			$this->response->setOutput ( $this->render () );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Sites Notes Search' 
			);
			$this->model_activity_activity->addActivity ( 'SitesNotessearch', $activity_data2 );
			
			
		}
	
}

	
	
	public function getReport(){
		
		
		
		if ($this->request->get ['note_date_from'] != null && $this->request->get ['note_date_from'] != "") {
				
			$date = str_replace ( '-', '/', $this->request->get ['note_date_from'] );
			$res = explode ( "/", $date );
			$note_date_from = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			// $note_date_from = date('Y-m-d',
			// strtotime($this->session->data['note_date_from']));
		}
		if ($this->request->get ['note_date_to'] != null && $this->request->get ['note_date_to'] != "") {
			$date = str_replace ( '-', '/', $this->request->get ['note_date_to'] );
			$res = explode ( "/", $date );
			$note_date_to = $res [2] . "-" . $res [0] . "-" . $res [1];
			
			// $note_date_to = date('Y-m-d',
			// strtotime($this->session->data['note_date_to']));
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$facilities_id = $this->request->get['facilities_id'];
		}else{
			if ($this->session->data ['search_facilities_id'] != null && $this->session->data ['search_facilities_id'] != "") {
				$facilities_id = $this->session->data ['search_facilities_id'];
			} else {
				$facilities_id = $this->customer->getId ();
			}
		}
		
	  // $note_date_to = date('Y-m-d',strtotime($this->request->get['note_date_to']));
		
	   //$note_date_from = date('Y-m-d',strtotime($this->request->get['note_date_from']));	
	 
	   $date_to = DateTime::createFromFormat("m-d-Y" , $this->request->get['note_date_to']);
       $note_date_to=$date_to->format('Y-m-d');
	   
	   $date_from = DateTime::createFromFormat("m-d-Y" , $this->request->get['note_date_from']);
       $note_date_from=$date_from->format('Y-m-d');
	 
	 
	   
	 
	   $report_date= $note_date_from." to ".$note_date_to;	
		
		$this->language->load('notes/notes');
		$this->document->setTitle('Generate PDF');
		
		$this->load->model('notes/image');
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('notes/notes');
		$this->load->model('form/form');
		$this->load->model('facilities/facilities');
		
		
		$this->load->model('notes/tags');
		
		$this->load->model('setting/tags');
		$this->load->model ( 'setting/locations' );
		
		$noteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		$printnoteurl = $this->url->link('form/form/printform', '' . $url, 'SSL');
		$firedrillnoteurl = $this->url->link('form/form/printmonthly_firredrill', '' . $url, 'SSL');
		$incidentnoteurl = $this->url->link('form/form/printincidentform', '' . $url, 'SSL');
		$innoteurl = $this->url->link('form/form/printintakeform', '' . $url, 'SSL');
		
		$facilityinfo = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		
		$config_tag_status = $this->customer->isTag ();
		
		$this->data ['config_taskform_status'] = $this->customer->isTaskform ();
		$this->data ['config_noteform_status'] = $this->customer->isNoteform ();
		$this->data ['config_rules_status'] = $this->customer->isRule ();
		$this->data ['config_share_notes'] = $this->customer->isNotesShare ();
		$this->data ['config_multiple_activenote'] = $this->customer->isMactivenote ();
		
		$unique_id = $facilityinfo ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['client_info_notes'] );
		$customers = unserialize ( $customer_info ['setting_data'] );
		
		
		if($customers['date_format'] != null && $customers['date_format'] != ""){
			$date_format = $customers['date_format'];
		}else{
			$date_format = $this->language->get ( 'date_format_short_2' );
		}
		
		if($customers['time_format'] != null && $customers['time_format'] != ""){
			$time_format = $customers['time_format'];
		}else{
			$time_format = 'h:i A';
		}
		
	    $facility=$facilityinfo['facility'];
		
		$ddss = array ();
		if ($facilityinfo ['notes_facilities_ids'] != null && $facilityinfo ['notes_facilities_ids'] != "") {
			$this->data ['is_master_facility'] = '1';
			$ddss [] = $facilityinfo ['notes_facilities_ids'];
			
			$ddss [] = $this->customer->getId ();
			$sssssdd = implode ( ",", $ddss );
		} else {
			$this->data ['is_master_facility'] = '2';
		}
		
		
		$customer_key = $facilityinfo ['customer_key'];
		
		if ($this->request->get ['shift_id'] != null && $this->request->get ['shift_id'] != "") {
			$shift_data=$this->model_notes_notes->getshift($this->request->get ['shift_id']);
		}
		
		
		
		if ($this->request->get ['search_facilities_id'] != null && $this->request->get ['search_facilities_id'] != "") {
			$search_facilities_id = $this->request->get ['search_facilities_id'];
		}
		
		if($shift_data){
			
			$shift_start_time=$shift_data['shift_starttime'];
			$shift_end_time=$shift_data['shift_endtime'];
			$shift_name=$shift_data['shift_name'];
			
		}else{
			
			$shift_start_time='';
			$shift_end_time='';
			$shift_name='-';
			
		}
		
		
		$data = array (
			'sort' => $sort,
			'case_detail' => '',
			'order' => $order,
			'searchdate' => '',
			'searchdate_app' => '1',
			'facilities_id' => $facilities_id,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'group' => '',
			'search_facilities_id' => $search_facilities_id,					
			'search_time_start' => $shift_start_time,
			'search_time_to' => $shift_end_time,					
			'shift_name' => $shift_name,					
			'keyword' => $this->request->get ['keyword_search'],
			'form_search' => $this->request->get ['form_search'],
			'user_id' => $this->request->get ['user_id'],
			'highlighter' => '',
			'activenote' => '',
			'emp_tag_id' => $this->request->get ['emp_tag_id'],
			'advance_searchapp' => $this->request->get ['advance_search'],
			'tasktype' => '',
			'customer_key' => $customer_key,
			'case_number' => '',
			'advance_search'=>'1',
			'tag_classification_id' => '',
			'tag_status_id' => '',
			'manual_movement' => '',
			'notes_facilities_ids' => $sssssdd,
		);
		//var_dump($data);
		
		$this->load->model ( 'form/form' );
		
		$html = $this->model_form_form->getformreports ( $data);
		
		$this->document->setTitle ( 'Reprot' );
		require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF ( 'P', PDF_UNIT, 'A4', true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator ( PDF_CREATOR );
		$pdf->SetAuthor ( '' );
		$pdf->SetTitle ( 'REPORT' );
		$pdf->SetSubject ( 'REPORT' );
		$pdf->SetKeywords ( 'REPORT' );
		
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		if (@file_exists ( dirname ( __FILE__ ) . '/lang/eng.php' )) {
			require_once (dirname ( __FILE__ ) . '/lang/eng.php');
			$pdf->setLanguageArray ( $l );
		}
		
		$pdf->SetFont ( 'helvetica', '', 9 );
		$pdf->AddPage ();
		if($fromdatas['pdf_setting']['print_type']!='' && $fromdatas['pdf_setting']['print_type']!= null){
		
			if($fromdatas['pdf_setting']['print_type']=='2'){
				$temp_file_name = 'report_pdf'.date('YmdHis').'.zip';
				$zip = new ZipArchive();
				$file = $temp_file_name;
				$zip->open($file, ZipArchive::CREATE);
				
				$pdf->writeHTML ( $html, true, 0, true, 0 );

				$pdf->lastPage ();

				// $pdf->Output('example_049.pdf', 'D');

				$pdf->Output ( 'report_'.date('YmdHis').'.pdf', 'F' );
						
				$zip->addFile('report_'.date('YmdHis').'.pdf');	

                $delete_files[]='report_'.date('YmdHis').'.pdf';
			
			
			}else{
			 
				if($fromdatas['pdf_setting']['pdf_download']=='1'){
					$pdf->writeHTML ( $html, true, 0, true, 0 );

					$pdf->lastPage ();

					$pdf->Output ( 'report_'.date('YmdHis').'.pdf', 'I' );	
				}else{

					$temp_file_name = 'report_pdf'.date('YmdHis').'.zip';
					$zip = new ZipArchive();
					$file = $temp_file_name;
					$zip->open($file, ZipArchive::CREATE);
					
					$pdf->writeHTML ( $html, true, 0, true, 0 );

					$pdf->lastPage ();

					$pdf->Output ( 'report_'.date('YmdHis').'.pdf', 'F' );

					$zip->addFile('report_'.date('YmdHis').'.pdf');	
					$delete_files[]='report_'.date('YmdHis').'.pdf';
				 
				 
				}
		   
			}
		 
		 
		    $zip->close();
		
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$temp_file_name.'"');
			readfile($temp_file_name);
			
			foreach($delete_files as $delete_file){
				unlink($delete_file);
			}
			unlink($temp_file_name);		
		}else{
			
			//var_dump($notess);die;
			// output the HTML content
			$pdf->writeHTML ( $html, true, 0, true, 0 );
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			// reset pointer to the last page
			$pdf->lastPage ();
			
			// ---------------------------------------------------------
			
			// Close and output PDF document
			// $pdf->Output('example_049.pdf', 'D');
			
			$pdf->Output ( 'report_' . rand () . '.pdf', 'I' );
			
			
		}
		
		
		exit ();
		
	}

}




?>
	