<?php
header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: application/json');
header('Content-Type: text/html; charset=utf-8');

class Controllercaseservicesupdatedata extends Controller
{

    public function getupdatekeywords ()
    {
        try {
			  $this->data['facilitiess'] = array();
            
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
				
             
            
			if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
				$this->load->model('facilities/facilities');
				$facility_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				
				if($facility_info){
					$updated_keywords = array();
					$facilities_id = $this->request->post['facilities_id'];
					
					$this->load->model('setting/timezone');
					$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
					
					
					$timezone_name = $timezone_info['timezone_value'];
					date_default_timezone_set($timezone_name);
					$current_date_user =  date('Y-m-d');
					
					$udata7 = array();
					$udata7 = array(
						'facilities_id' => $facilities_id,
						'current_date_user' => $current_date_user,
					);
					$this->load->model('api/updatesetting');
					$this->load->model('setting/image'); 
					$this->load->model('notes/image'); 
					$updated_keywords = $this->model_api_updatesetting->getupdatekeywordsurl($udata7);
					
					$this->data['facilitiess'][] = array(
						
						'updated_keywords'  => $updated_keywords,
						'facilities_id'  => $facilities_id,
					);
				
					$error = true;
				
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => "Please Send Facility id",
					);
					$error = false;
				}
			
			}else{
				$this->data['facilitiess'][] = array(
					'warning'  => "Please Send facility id",
				);
				$error = false;
			}
            
            $value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			
        } catch (Exception $e) {
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in wearservice getupdatekeywords'
            );
            $this->model_activity_activity->addActivity('getupdatekeywords', $activity_data2);
        }
    }
	
	
}


