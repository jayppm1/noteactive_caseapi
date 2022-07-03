<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerserviceschecklicence extends Controller {

	public function index() {
		
		try {
			
			$this->load->model('licence/licence');
			
			$fdata = array();
			$fdata['activationkey'] = $this->request->post['accesskey'];
			
			$result = $this->model_licence_licence->insert_activationkey($fdata);
			
			$json = array();
			
			if($_SERVER['HTTP_HOST']!="localhost"){
				$json['accesskey'] = $this->request->post['accesskey'];
				$json['id'] = 1;
			}else{
				$json['accesskey'] = $this->request->post['accesskey'];
				$json['id'] = 2;
			}
			
			$value = array($json);
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in checklicence index ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'checklicence', $activity_data2 );
		}
	}

	
}
 
 