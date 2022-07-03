<?php
class Controlleruserrole extends Controller {
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
			$this->load->model('user/user_group');
			

			$data = array(
				'filter_name'  => $this->request->get['filter_name'],
				'start'       => 0,
				'facilities_id'       => $this->customer->getId(),
				
				'limit'       => 10
			);

			
			$results = $this->model_user_user_group->getUserGroups($data);

			if($results != null && $results != ""){
				foreach ($results as $result) {
					$json[] = array(
						'user_group_id' => $result['user_group_id'], 
						'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					);
				}	
			}			
		//}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
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
	
	public function autocomplete2() {
		
		try{ 
		$json = array();
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);

		$this->load->model('user/user_group');
		$this->load->model('user/user');

		$data = array(
			'filter_name'  => $this->request->get['filter_name'],
			'start'       => 0,
			'facilities_id'       => $this->customer->getId(),
			'limit'       => 10
		);

		
		$results = $this->model_user_user_group->getUserGroups($data);

		if($results != null && $results != ""){
			foreach ($results as $result) {
				
				$data = array(
					'user_group_id'   => $result['user_group_id'],
				);
				
				$results = $this->model_user_user->getUsers($data);
				$users = array();
				if($results != null && $results != ""){
					foreach ($results as $user) {
						$users[] = array(
							'user_id' => $user['user_id'], 
							'username' => strip_tags(html_entity_decode($user['username'], ENT_QUOTES, 'UTF-8'))
						);
					}	
				}
				if($results != null && $results != ""){
				
					$json[] = array(
						'user_group_id' => $result['user_group_id'], 
						'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
						'users'        => $users,
						
					);
				}
			}	
		}			

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in user role autocomplete2',
			);
			$this->model_activity_activity->addActivity('userrole_autocomplete2', $activity_data2);
		
		
		} 
	}
}
?>