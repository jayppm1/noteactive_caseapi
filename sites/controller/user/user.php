<?php  
class ControllerUserUser extends Controller {
	private $error = array();

	public function autocomplete() {
		
		try{ 
		$json = array();
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);

		//if (isset($this->request->get['filter_name'])) {
			$this->load->model('user/user');

			$data = array(
				'filter_name'   => $this->request->get['filter_name'],
				'user_group_id'   => $this->request->get['user_group_id'],
				'facilities_id' => $this->customer->getId(),
				'start'      	=> 0,
				'limit'      	=> 10
			);

			
			$results = $this->model_user_user->getUsers($data);

			if($results != null && $results != ""){
				foreach ($results as $result) {
					$json[] = array(
						'user_id' => $result['user_id'], 
						'username'        => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8'))
					);
				}	
			}			
		//}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['username'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in user role autocomplete',
			);
			$this->model_activity_activity->addActivity('userrole_autocomplete', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	public function searchUser2(){
		$json = array();
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);

		if($this->request->get['user_group_id'] != null && $this->request->get['user_group_id'] != "") {
			$this->load->model('user/user');

			if (isset($this->request->get['user_id'])) {
				$user_id = $this->request->get['user_id'];
			} else {
				$user_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'user_id'  => $user_id,
				'facilities_id'  => $this->customer->getId(),
				'allusers'  => $this->request->get['allusers'],
				'user_group_id'  => $this->request->get['user_group_id'],
				'start'        => 0,
				'limit'        => $limit
			);
			
			
			
			$users = $this->model_user_user->getUsersByFacilityUser($data);
			
			foreach ($users as $user) {
				$json[] = array(
					'username' => $user['username'],
					'user_id' => $user['user_id'],
					'email' => $user['email'],
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	
}
?>