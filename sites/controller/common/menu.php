<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		
		$data['text_scheduler'] = "Scheduler";
		$data['text_clients'] = "Clients";
		

		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		$data['clients'] = $this->url->link('setting/tags', 'token=' . $this->session->data['token'], 'SSL');
		$data['Scheduler'] = $this->url->link('common/calender', 'token=' . $this->session->data['token'], 'SSL');
			
		return $this->load->view('common/menu.php', $data);
	}
}