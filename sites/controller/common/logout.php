<?php
class ControllerCommonLogout extends Controller {
	public function index() {
		$this->load->model ( 'facilities/facilities' );
		
		$data = array ();
		$data ['activationkey'] = $this->session->data ['activationkey'];
		$data ['username'] = $this->session->data ['username'];
		$data ['facilities_id'] = $this->customer->getId ();
		$data ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->model_facilities_facilities->updateFacilityLogout ( $data );
		
		$this->customer->logout ();
		
		/**
		 * **************
		 */
		$this->load->model ( 'licence/licence' );
		$this->model_licence_licence->closeuseractivation ();
		/**
		 * *************
		 */
		
		unset ( $this->session->data ['time_zone'] );
		unset ( $this->session->data ['share_note_otp'] );
		unset ( $this->session->data ['time_zone_1'] );
		// unset($this->session->data['token']);
		
		unset ( $this->session->data ['note_date_search'] );
		unset ( $this->session->data ['note_date_from'] );
		unset ( $this->session->data ['note_date_to'] );
		unset ( $this->session->data ['keyword'] );
		
		unset ( $this->session->data ['sms_user_id'] );
		unset ( $this->session->data ['search_user_id'] );
		
		unset ( $this->session->data ['notesdatas'] );
		unset ( $this->session->data ['advance_search'] );
		unset ( $this->session->data ['update_reminder'] );
		unset ( $this->session->data ['pagenumber'] );
		unset ( $this->session->data ['pagenumber_all'] );
		unset ( $this->session->data ['activationkey'] );
		unset ( $this->session->data ['username'] );
		unset ( $this->session->data ['session_key'] );
		unset ( $this->session->data ['isPrivate'] );
		unset ( $this->session->data ['licfacilities'] );
		unset ( $this->session->data ['review_user_id'] );
		unset ( $this->session->data ['session_cache_key'] );
		unset ( $this->session->data ['group'] );
		unset ( $this->session->data ['user_enroll_confirm'] );
		unset ( $this->session->data ['username_confirm'] );
		unset ( $this->session->data ['webuser_id'] );
		unset ( $this->session->data ['webcustomer_key'] );
		unset ( $this->session->data ['search_facilities_id'] );
		
		unset ( $this->session->data ['form_search'] );
				unset ( $this->session->data ['highlighter'] );
				unset ( $this->session->data ['activenote'] );
				unset ( $this->session->data ['review_user_id'] );
		
		unset ( $this->session->data ['formreturn_id'] );
		unset ( $this->session->data ['design_forms'] );
		unset ( $this->session->data ['formsids'] );
		unset ( $this->session->data ['session_notes_description'] );
		unset ( $this->session->data ['tasktype'] );
		unset ( $this->session->data ['group'] );
		
		
		unset ( $this->session->data ['facilityids222'] );
				unset ( $this->session->data ['locations222'] );
				unset ( $this->session->data ['tagsids222'] );
				unset ( $this->session->data ['userids222'] );
				
				
				unset($this->session->data ['late_entrycomments']);
		
		unset ( $this->session->data ['tagstatusid'] );
				unset ( $this->session->data ['tagclassificationid'] );
				unset ( $this->session->data ['manual_movement'] );
		
		$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		// $this->redirect($this->url->link('common/licence/activation','',
		// 'SSL'));
	}
	public function checkLogin() {
		$config_session_time_out = $this->config->get ( 'config_session_time_out' );
		
		if ($config_session_time_out == '5min') {
			$inactive = 36 * 5;
		} else if ($config_session_time_out == '10min') {
			$inactive = 36 * 10;
		} else if ($config_session_time_out == '15min') {
			$inactive = 36 * 15;
		} else if ($config_session_time_out == '20min') {
			$inactive = 36 * 20;
		} else if ($config_session_time_out == '25min') {
			$inactive = 36 * 25;
		} else if ($config_session_time_out == '30min') {
			$inactive = 36 * 30;
		} else if ($config_session_time_out == '45min') {
			$inactive = 36 * 45;
		} else if ($config_session_time_out == '1hour') {
			$inactive = 36 * 60;
		} else if ($config_session_time_out == '2hour') {
			$inactive = 36 * 60 * 60;
		} else if ($config_session_time_out == '3hour') {
			$inactive = 36 * 60 * 60 * 60;
		} else if ($config_session_time_out == '4hour') {
			$inactive = 36 * 60 * 60 * 60 * 60;
		} else if ($config_session_time_out == '5hour') {
			$inactive = 36 * 60 * 60 * 60 * 60 * 60;
		} else if ($config_session_time_out == '6hour') {
			$inactive = 36 * 60 * 60 * 60 * 60 * 60 * 60;
		} else if ($config_session_time_out == '7hour') {
			$inactive = 36 * 60 * 60 * 60 * 60 * 60 * 60 * 60;
		} else if ($config_session_time_out == '8hour') {
			$inactive = 36 * 60 * 60 * 60 * 60 * 60 * 60 * 60 * 60;
		} else {
			$inactive = 600;
		}
		
		$json = array ();
		
		if (isset ( $this->session->data ['timeout'] )) {
			
			$session_life = time () - $this->session->data ['timeout'];
			
			if ($session_life > $inactive) {
				$json ['success'] = '1';
			} else {
				$json ['success'] = '0';
			}
		} else {
			$this->session->data ['timeout'] = time ();
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
}
?>