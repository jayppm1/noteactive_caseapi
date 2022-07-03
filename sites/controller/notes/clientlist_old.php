<?php   
class Controllernotesclientlist extends Controller {
	private $error = array();
	
	public function index() {
		try{
			
			$bed_name = $this->request->get['bed_name'];
			$bedcheck_occupancy = $this->request->get['bedcheck_occupancy'];
			
			$this->load->model('setting/bedchecktaskform');
			$this->load->model('setting/tags');
			
			$taskformlocs = $this->model_setting_bedchecktaskform->getruleModule($bed_name);
			// var_dump($taskformlocs);
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			
			$json = array();
			$json['bctf_module'] = array();
			 
			 
			foreach($taskformlocs['bctf_module'] as $result){
				
			if($bedcheck_occupancy == '1'){			
				//var_dump($bedcheck_occupancy );
				$locations_ids = $this->model_setting_tags->gettotalcountbyroom($result['locations_id']);
				
					if( $locations_ids >= "1" ){
						$json['bctf_module'][] = array(
							'task_form_location_id'  => $result['task_form_location_id'],
							'task_form_id'      => $result['task_form_id'],
							'location_name'       => $result['location_name'],
							'locations_id'       => $result['locations_id'],
							'location_detail' => $result['location_detail'],
							'current_occupency'     => $result['current_occupency'],
							'sort_order'              => $result['sort_order']
						);
					
					}
				}else{
					
					$json['bctf_module'][] = array(
						'task_form_location_id'             => $result['task_form_location_id'],
						'task_form_id'      => $result['task_form_id'],
						'location_name'       => $result['location_name'],
						'locations_id'       => $result['locations_id'],
						'location_detail' => $result['location_detail'],
						'current_occupency'     => $result['current_occupency'],
						'sort_order'              => $result['sort_order']
					);
					
				}
				
			}
			
			
			$this->response->setOutput(json_encode($json));
			
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Clientlist',
			);
			$this->model_activity_activity->addActivity('sitesclientlist', $activity_data2);
		
		
		
		} 
	
	
	
	}
	
}