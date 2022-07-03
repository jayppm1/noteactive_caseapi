<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllercaseservicesdevicelog extends Controller { 
	private $error = array();
	
	public function index(){
		try{
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('devicelogindex', $this->request->post, 'request');
			$this->data['facilitiess'] = array();
			$json = array();
			$this->load->model('activity/activity');
		
			if($json['warning'] == null && $json['warning'] == ""){
				
				
				$adata['data'] = $this->request->post;
				$this->model_activity_activity->addActivitySave($this->request->post['keyname'], $adata, 'app');
				
				if($this->request->post['send_email'] == '1'){
					
					$message33 = "";
					$messagebody = 'APP LOG';
					$messagebody1 = 'The following app log detail.';
					$message33 .= $this->request->post['message'];
					
					$edata = array ();
					$edata ['message'] = $message33;
					$edata ['subject'] = $this->request->post['subject'];
					$edata ['user_email'] = 'note-sync@noteactive.com';
					$this->load->model ( 'api/emailapi' );
					$email_status = $this->model_api_emailapi->sendmail ( $edata );
					
					/*
					$edata = array();
                    $edata['message'] = $message;
                    $edata['facility'] = $this->customer->getfacility() ;
                    $edata['user_email'] = 'note-sync@noteactive.com' ;
                    $edata['when_date']= date("l");
                    $edata['notes_description'] = $message33;
                    $edata['who_user']= "";
                    $edata['type']="15";
					$edata ['subject'] = $this->request->post['subject'];
                   // $email_status = $this->model_api_emailapi->createMails($edata);
                    $email_status = $this->model_api_emailapi->sendmail($edata);
					*/
				}
			
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in deviceinlogdex '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_devicelogindex', $activity_data2);
		
		
		} 
	}
	
}