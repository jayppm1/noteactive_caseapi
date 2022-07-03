<?php
class ControllerCommonColumnleft extends Controller {
	
	public function index() {
		//$data['title'] = $this->document->getTitle();
		
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['menu'] = $this->load->controller('common/menu');

			return $this->load->view('common/column_left.php', $data);
		}
	}
}   