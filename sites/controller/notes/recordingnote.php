<?php
class Controllernotesrecordingnote extends Controller {
private $error = array();

	public function recordnote(){
	
			$this->template = $this->config->get('config_template') . '/template/notes/recordnote.php';
			$this->response->setOutput($this->render());
	
	
	}
	
	
}

?>