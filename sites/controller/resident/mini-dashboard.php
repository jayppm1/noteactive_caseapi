<?php
class ControllerresidentminiDashboard extends Controller {
	private $error = array ();

	public function getCounts() { 
		if (! $this->customer->isLogged ()) {
			//$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datamd = array ();
		$strArray = explode('/',$this->request->get['route']);
		$details_of = end($strArray);
		$datamd ['username'] = $this->session->data ['webuser_id'];
		$datamd ['total_data'] = 0;
		$datamd ['activationkey'] = $this->session->data ['activationkey'];
		$datamd ['facilities_id'] = $this->customer->getId ();
		$datamd ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$datamd['details_of'] = $this->request->post['details_of'] ? $this->request->post['details_of'] : $details_of;
		$datamd['count_required'] = $this->request->post['count'];
		$datamd['total_for'] = $this->request->post['tg_id'];
		//$this->request->post['from'] = '2021-03-02'; //Dummy
		//$this->request->post['to'] = '2021-04-14'; //Dummy
		$datamd['from_date'] = isset($this->request->post['from']) ? date('Y-m-d', strtotime($this->request->post['from'])) : date ( 'Y-m-d' );
		$datamd['to_date'] = isset($this->request->post['to']) ? date('Y-m-d', strtotime($this->request->post['to'])) : date ( 'Y-m-d' );
		
		unset ( $this->session->data ['show_hidden_info'] );
		unset ( $this->session->data ['case_number'] );

		$this->load->model ( 'resident/minidashboard' );
		if($datamd['count_required']) { 
			$datamd ['total_data'] = $this->model_resident_minidashboard->getTotal ( $datamd );
		}
		
		$this->response->setOutput ( json_encode ( $datamd ) );
	}
	
	public function getOutOfTheCell() { 
		if (! $this->customer->isLogged ()) { 
			//$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datamd = array ();
		$datamd ['username'] = $this->session->data ['webuser_id'];
		$datamd ['total_data'] = 0;
		$datamd ['activationkey'] = $this->session->data ['activationkey'];
		$datamd ['facilities_id'] = $this->customer->getId ();
		$datamd ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$datamd['data_for'] = $this->request->post['tags_id'];
		$datamd['required_details'] = $this->request->post['required_details'];
		//$this->request->post['from'] = '2021-03-02'; //Dummy
		//$this->request->post['to'] = '2021-04-14'; //Dummy
		$datamd['from_date'] = isset($this->request->post['from']) ? date('Y-m-d', strtotime($this->request->post['from'])) : date ( 'Y-m-d' );
		$datamd['to_date'] = isset($this->request->post['to']) ? date('Y-m-d', strtotime($this->request->post['to'])) : date ( 'Y-m-d' );
		
		unset ( $this->session->data ['show_hidden_info'] );
		unset ( $this->session->data ['case_number'] );

		$this->load->model ( 'resident/minidashboard' );
		if($datamd['required_details']) { 
			//$datamd ['total_data'] = $this->model_resident_minidashboard->getOTCDetails ( $datamd );
		}

		$datamd = $this->model_resident_minidashboard->getOTCDetails ( $datamd );
		
		$this->response->setOutput ( json_encode ( $datamd ) );
	}

	public function getMovementData() { 
		if (! $this->customer->isLogged ()) { 
			//$this->redirect ( $this->url->link ( 'common/login', '', 'SSL' ) );
		}
		
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->load->model ( 'facilities/online' );
		$datamd = array ();
		$datamd ['username'] = $this->session->data ['webuser_id'];
		$datamd ['total_data'] = 0;
		$datamd ['activationkey'] = $this->session->data ['activationkey'];
		$datamd ['facilities_id'] = $this->customer->getId ();
		$datamd ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$datamd['data_for'] = $this->request->post['tags_id'];
		$datamd['required_details'] = $this->request->post['required_details'];
		//$this->request->post['from'] = '2021-03-02'; //Dummy
		//$this->request->post['to'] = '2021-04-14'; //Dummy
		$datamd['from_date'] = isset($this->request->post['from']) ? date('Y-m-d', strtotime($this->request->post['from'])) : date ( 'Y-m-d' );
		$datamd['to_date'] = isset($this->request->post['to']) ? date('Y-m-d', strtotime($this->request->post['to'])) : date ( 'Y-m-d' );
		
		unset ( $this->session->data ['show_hidden_info'] );
		unset ( $this->session->data ['case_number'] );

		$this->load->model ( 'resident/minidashboard' );
		if($datamd['required_details']) { 
			//$datamd ['total_data'] = $this->model_resident_minidashboard->getOTCDetails ( $datamd );
		}

		$datamd = $this->model_resident_minidashboard->getMovementDetails ( $datamd );
		
		$this->response->setOutput ( json_encode ( $datamd ) );
	}
	
}