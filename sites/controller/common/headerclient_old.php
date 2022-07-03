<?php   
class ControllerCommonheaderclient extends Controller {
	protected function index() {
		try{

			/*if (!$this->customer->isLogged()) {
				$this->redirect($this->url->link('common/login', '', 'SSL'));
			}*/
			
			$this->load->model('facilities/online');
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->customer->getId();
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->language->load('notes/notes');
			
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);

			
			
			$this->data['facilityname'] = $this->customer->getfacility();
			
			
			if($facility['notes_facilities_ids'] != null && $facility['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
			
			$this->data['masterUlr'] = $this->url->link('resident/resident/masterfacility', '', 'SSL');
				

			if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !=""){
				if($this->session->data['search_facilities_id'] != $this->customer->getId()){
					$this->data['search_facilities_id'] = $this->session->data['search_facilities_id'];
				 
					$searchf_name =  $this->model_facilities_facilities->getfacilities($this->session->data['search_facilities_id']);
					$this->data['searchf_name'] = $searchf_name['facility'];
				}
				 
			}
			
			if($this->session->data['search_facilities_id'] !=NULL && $this->session->data['search_facilities_id'] !='' ){
				$facilities_id = $this->session->data['search_facilities_id'];
			}else{
				$facilities_id = $this->customer->getId();
			}
			
			
			if($this->request->get['route'] == 'resident/resident'){
				$this->document->setTitle('Clients');
				$this->data['heading_title'] = 'Clients';
				$this->data['is_client'] = '1';
				
				$this->data['total_url'] = $this->url->link('resident/resident', '', 'SSL');
				
				
				$this->load->model('setting/tags');

				 $facilities_is_master = $this->model_facilities_facilities->getfacilities($facilities_id);
				 
				 if($facilities_is_master['is_master_facility'] == 0){
					$is_master_facility = 1;
				}else{
					$is_master_facility = $facilities_is_master['is_master_facility'];
				}


         $data31333 = array();
        $data31333 = array(
                'status' => 1,
                'discharge' => 1,
                // 'role_call' => '1',
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'is_master'=>$is_master_facility,
                'facilities_id' => $facilities_id,
                'emp_tag_id_2' => $search_tags,
               /* 'search_tags_tag_id' => $this->request->get['search_tags_tag_id'],*/
                'wait_list' => $this->request->get['wait_list'],
                'all_record' => '1',
                'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        $tags_total_2 = $this->model_setting_tags->getTotalTags($data31333);

        $this->data['tags_total_2']=$tags_total_2;
        
             $data10 = array();
				$data10 = array(
					'status' => 1,
					'discharge' => 1,					
					'role_call' => '2',
					'facilities_id' => $facilities_id,
					'is_master'=>$is_master_facility,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
					
				);
				
				$this->data['total_out_tags'] = $this->model_setting_tags->getTotalTags($data10);



			
				$data3 = array();
				$data3 = array(
					'status' => 1,
					'discharge' => 1,
					'role_call' => '1',
					'gender2' => $this->request->get['gender'],
					'sort' => 'emp_first_name',
					'is_master'=>$is_master_facility,
					'facilities_id' => $facilities_id,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
				);
				
				$this->data['tags_total'] = $this->model_setting_tags->getTotalTags($data3);

				/*var_dump(expression);
				die;*/

				
				$data4 = array();
				$data4 = array(
					'status' => 1,
					'discharge' => 1,
					'gender' => '1',
					'role_call' => '1',
					'facilities_id' => $facilities_id,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
				);
				
				$this->data['maletags_total'] = $this->model_setting_tags->getTotalTags($data4);


               //get out tags

				/*$data10 = array();
				$data10 = array(
					'status' => 1,
					'discharge' => 1,					
					'role_call' => '2',
					'facilities_id' => $facilities_id,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
					
				);
				
				$this->data['total_out_tags'] = $this->model_setting_tags->getTotalTags($data10);*/






				
				$data5 = array();
				$data5 = array(
					'status' => 1,
					'discharge' => 1,
					'gender' => '2',
					'role_call' => '1',
					'facilities_id' => $facilities_id,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
					
				);
				
				$this->data['femaletags_total'] = $this->model_setting_tags->getTotalTags($data5);
				//var_dump($this->data['femaletags_total']);
				
				
				$data5n = array();
				$data5n = array(
					'status' => 1,
					'discharge' => 1,
					'gender' => '3',
					'role_call' => '1',
					'facilities_id' => $facilities_id,
					//'emp_tag_id_2' => $this->request->get['search_tags'],
					'wait_list' => $this->request->get['wait_list'],
					'all_record' => '1',
					
				);
				
				$this->data['nontags_total'] = $this->model_setting_tags->getTotalTags($data5n);
				//var_dump($this->data['nontags_total']);
				
			}elseif($this->request->get['route'] == 'resident/dailycensus'){
				$this->document->setTitle('Census');
				$this->data['heading_title'] = 'Census';
				$this->data['is_client'] = '2';
				
				$this->data['total_url'] = $this->url->link('resident/dailycensus', '', 'SSL');
				
			}
			
			
			$this->data['title'] = $this->document->getTitle();
		
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
				$this->data['error'] = $this->session->data['error'];
				
				unset($this->session->data['error']);
			} else {
				$this->data['error'] = '';
			}

			$this->data['base'] = $server;
			
			$this->data['role_call'] = $this->request->get['role_call'];
			
			
			////$this->data['male_url'] = $this->url->link('resident/resident&gender=1', '' . $url1, 'SSL');
			//$this->data['female_url'] = $this->url->link('resident/resident&gender=2', '' . $url1, 'SSL');
            
            $this->data['total_in_url'] = $this->url->link('resident/resident&role_call=1', '' . $url1, 'SSL');
			$this->data['total_out_url'] = $this->url->link('resident/resident&role_call=2', '' . $url1, 'SSL');
      

			$this->data['non_url'] = $this->url->link('resident/resident&gender=3', '' . $url1, 'SSL');
			
			$this->data['total_url2'] = $this->url->link('resident/resident', '', 'SSL');
			
			
			$this->data['notes_url'] = $this->url->link('notes/notes/insert', '', 'SSL');
			
			$this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
			
			$this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
			$this->data['logout'] = $this->url->link('common/logout', '' , 'SSL');
			
			$this->data['task_lists'] = str_replace('&amp;', '&',$this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL'));
			
			$this->data['task_lists2'] = str_replace('&amp;', '&',$this->url->link('resident/resident/residentstatus', '' . $url2, 'SSL'));
			
			 $this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
			
			$this->data['add_client_url1'] = str_replace('&amp;', '&',$this->url->link('notes/tags/addclient','', 'SSL')); 
			//$this->data['add_client_url3'] = str_replace('&amp;', '&',$this->url->link('form/form', '' . '&forms_design_id='.CUSTOME_INTAKEID, 'SSL')); 
			
			$this->data['assignteam'] = str_replace('&amp;', '&', $this->url->link('resident/assignteam', '', 'SSL'));
			
			
			
			
			$this->template = $this->config->get('config_template') . '/template/common/headerclient.php';

			$this->render();
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common headerclient',
			);
			$this->model_activity_activity->addActivity('sitesheaderclient', $activity_data2);
		
		
		} 
	} 	
}
?>
