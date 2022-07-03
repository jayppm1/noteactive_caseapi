<?php  
class ControllerCommonActivity extends Controller { 

	private $error = array();

	public function index() { 
	
		//var_dump($this->customer->getId());
		
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		
		$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
		
		$this->load->model('facilities/facilities');
		$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		$this->data['action_checkin'] =  $this->url->link('common/activity/checkin', '', 'SSL');
		$this->data['action_checkout'] = $this->url->link('common/activity/checkout','', 'SSL');	
		$this->data['action_listing'] = $this->url->link('common/activity/listing','', 'SSL');	
		
		$this->data['action_userpin'] = $this->url->link('common/activity/userpin','', 'SSL');	
		
		$this->template = $this->config->get('config_template') . '/template/common/activity.tpl';
		$this->response->setOutput($this->render());
	}
	
	
	public function checkin(){
		
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
		
			$this->load->model('facilities/facilities');
			$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				$this->load->model('activitylog/activitylog');
					
					
					$timezone_name = $this->customer->isTimezone();
					$timeZone = date_default_timezone_set($timezone_name);
					$noteDate = date('Y-m-d H:i:s', strtotime('now'));
					
					
				if($this->request->get['activitylog_id']){ 
					
					$this->model_activitylog_activitylog->edit($this->request->post,$this->request->get['activitylog_id']);
					$activitylog_id = $this->request->get['activitylog_id'];
				}else{
					$this->load->model('activitylog/activitylog');
				$activitylog_id = $this->model_activitylog_activitylog->add($this->request->post, $this->customer->getId(), $noteDate);
				}
				
				
				$this->redirect($this->url->link('common/activity/verifydetails&activitylog_id='.$activitylog_id ,'', 'SSL'));
			}
	
			if($this->request->get['activitylog_id'] != NULL && $this->request->get['activitylog_id'] != "" ){
				$this->load->model('activitylog/activitylog');
				$logdata = 	$this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
				
			}
			
			$url = "";
			if($this->request->get['activitylog_id'] != NULL && $this->request->get['activitylog_id'] != "" ){
				$url .= '&activitylog_id='.$this->request->get['activitylog_id'];
			}
	
			$this->data['action'] = $this->url->link('common/activity/checkin',''.$url, 'SSL');
			$this->data['back'] = $this->url->link('common/activity',''.$url, 'SSL');
			
			if (isset($this->request->post['first_name'])) {
				$this->data['first_name'] = $this->request->post['first_name'];
			} elseif($logdata) {
				$this->data['first_name'] = $logdata['first_name'];
			}else{
				$this->data['first_name'] = '';
			}

	
			if (isset($this->request->post['last_name'])) {
				$this->data['last_name'] = $this->request->post['last_name'];
			} elseif($logdata) {
				$this->data['last_name'] = $logdata['last_name'];
			}else{
				$this->data['last_name'] = '';
			}
			
			
			if (isset($this->request->post['company_name'])) {
				$this->data['company_name'] = $this->request->post['company_name'];
			} elseif($logdata) {
				$this->data['company_name'] = $logdata['company_name'];
			}else{
				$this->data['company_name'] = '';
			}
			
			
			if (isset($this->request->post['personvisiting'])) {
				$this->data['personvisiting'] = $this->request->post['personvisiting'];
			} elseif($logdata) {
				$this->data['personvisiting'] = $logdata['personvisiting'];
			}else{
				$this->data['personvisiting'] = '';
			}
			
			
			if (isset($this->request->post['reason'])) {
				$this->data['reason'] = $this->request->post['reason'];
			} elseif($logdata) {
				$this->data['reason'] = $logdata['reason'];
			}else{
				$this->data['reason'] = '';
			}
	
	
	
		if (isset($this->error['first_name'])) {
				$this->data['error_first_name'] = $this->error['first_name'];
			} else {
				$this->data['error_first_name'] = '';
			}

			
			if (isset($this->error['last_name'])) {
				$this->data['error_last_name'] = $this->error['last_name'];
			} else {
				$this->data['error_last_name'] = '';
			}
			
			
			if (isset($this->error['company_name'])) {
				$this->data['error_company_name'] = $this->error['company_name'];
			} else {
				$this->data['error_company_name'] = '';
			}
			
			
			if (isset($this->error['personvisiting'])) {
				$this->data['error_personvisiting'] = $this->error['personvisiting'];
			} else {
				$this->data['error_personvisiting'] = '';
			}
			
			
			if (isset($this->error['reason'])) {
				$this->data['error_reason'] = $this->error['reason'];
			} else {
				$this->data['error_reason'] = '';
			}
		
			$this->template = $this->config->get('config_template') . '/template/activity/checkin.tpl';
			$this->response->setOutput($this->render());
		
	}
	
	
	public function verifydetails(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			
		$this->load->model('facilities/facilities');
		$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
	
		//var_dump($this->data['facility_info']['facility']);
		
		$this->load->model('setting/zone');
		$this->data['state_info'] = $this->model_setting_zone->getZonesByCountryId('223');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate2()) {
			
			$this->load->model('activitylog/activitylog');
			$this->model_activitylog_activitylog->update($this->request->get['activitylog_id'], $this->request->post);
			
			if($this->request->post['picture'] != null && $this->request->post['picture'] != ""){
				$this->model_activitylog_activitylog->update2($this->request->get['activitylog_id']);
			}
			
			$this->redirect($this->url->link('common/activity/visitordetails&activitylog_id='.$this->request->get['activitylog_id'] ,'', 'SSL'));
			
		}
		
		
		
			if($this->request->get['activitylog_id']){
				$this->load->model('activitylog/activitylog');
				$logdata = 	$this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
				//var_dump($logdata);
				
			}
	
	
			if (isset($this->request->post['driving_lic'])) {
				$this->data['driving_lic'] = $this->request->post['driving_lic'];
			} elseif($logdata) {
				$this->data['driving_lic'] = $logdata['driving_lic'];
			}else{
				$this->data['driving_lic'] = '';
			}
			
			
			if (isset($this->request->post['imgOutput'])) {
				$this->data['imgOutput'] = $this->request->post['imgOutput'];
			} elseif($logdata) {
				$this->data['imgOutput'] = $logdata['imgOutput'];
			}else{
				$this->data['imgOutput'] = '';
			}
			
			
			if (isset($this->request->post['state'])) {
				$this->data['state'] = $this->request->post['state'];
			} elseif($logdata) {
				$this->data['state'] = $logdata['state_id'];
			}else{
				$this->data['state'] = '';
			}
			
			if (isset($this->request->post['picture'])) {
				$this->data['picture'] = $this->request->post['picture'];
			} elseif($logdata) {
				$this->data['picture'] = $logdata['picture'];
			}else{
				$this->data['picture'] = '';
			}
		
		
			if (isset($this->error['driving_lic'])) {
					$this->data['error_driving_lic'] = $this->error['driving_lic'];
				} else {
					$this->data['error_driving_lic'] = '';
				}
			
			if (isset($this->error['picture'])) {
					$this->data['error_picture'] = $this->error['picture'];
				} else {
					$this->data['error_picture'] = '';
				}
			
			if (isset($this->error['state'])) {
					$this->data['error_state'] = $this->error['state'];
				} else {
					$this->data['error_state'] = '';
				}
		
			$url = "";
			if($this->request->get['activitylog_id'] != NULL && $this->request->get['activitylog_id'] != "" ){
				$url .= '&activitylog_id='.$this->request->get['activitylog_id'];
			}
	
			$this->data['action'] = $this->url->link('common/activity/verifydetails',''.$url, 'SSL');
			$this->data['back'] = $this->url->link('common/activity/checkin',''.$url, 'SSL');
		
		//$this->data['action'] = $this->url->link('common/activity/verifydetails&activitylog_id='.$this->request->get['activitylog_id'],'', 'SSL');
		//$this->data['back'] = $this->url->link('common/activity/checkin','', 'SSL');
		$this->data['webcam_url'] = $this->url->link('common/activity/webcam','', 'SSL');
		
		
		$this->template = $this->config->get('config_template') . '/template/activity/verify.tpl';
		$this->response->setOutput($this->render());	
	}
	
	public function checkout(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$timezone_name = $this->customer->isTimezone();
		$timeZone = date_default_timezone_set($timezone_name);
		
		$this->data['back'] = $this->url->link('common/activity','', 'SSL');
		$this->load->model('facilities/facilities');
		
		
		
		$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		
		$this->data['btnconfirm_url'] = $this->url->link('common/activity/confirmation','', 'SSL');
		
		
		$this->template = $this->config->get('config_template') . '/template/activity/checkout.tpl';
		$this->response->setOutput($this->render());
		
	}
	
	
	protected function validate() {
		
		if($this->request->post['first_name'] == "" && $this->request->post['first_name'] == NULL){
		
		$this->error['first_name'] = "First Name is required";
			
		}
		
		if($this->request->post['last_name'] == "" && $this->request->post['last_name'] == NULL){
			
			$this->error['last_name'] = "Last Name is required";
		}
	
		
		if($this->request->post['company_name'] == "" && $this->request->post['company_name'] == NULL){
			
		$this->error['company_name'] = "Company Name is required";	
		}
		
		if($this->request->post['personvisiting'] == "" && $this->request->post['personvisiting'] == NULL){
			
			$this->error['personvisiting'] = "Person Name is required";
		}
		
		if($this->request->post['reason'] == "" && $this->request->post['reason'] == NULL){
			
			$this->error['reason'] = "Reason is required";
			
		}
		
		
		
			if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	protected function validate2() {
	
		/*if($this->request->post['driving_lic'] == "" && $this->request->post['driving_lic'] == NULL){
			
			$this->error['driving_lic'] = "Driving Lic Number is required";
		}*/
	
		
		if($this->request->post['state'] == "" && $this->request->post['state'] == NULL){
			
			$this->error['state'] = "State is required";
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function visitordetails(){
		
		$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			
		$this->load->model('facilities/facilities');
		$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		$this->load->model('activitylog/activitylog');
		$this->data['activitylog'] = $this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
		
		
		
		$this->data['back'] = $this->url->link('common/activity','', 'SSL');
		$this->data['pdf_url'] = $this->url->link('common/activity/generatePdf&activitylog_id='.$this->request->get['activitylog_id'],'', 'SSL');
		$this->data['edit_url'] = $this->url->link('common/activity/checkin&activitylog_id='.$this->request->get['activitylog_id'],'', 'SSL');
		
		
		$this->data['save_edit_url'] = $this->url->link('common/activity/complete&activitylog_id='.$this->request->get['activitylog_id'],'', 'SSL');
		
		$this->template = $this->config->get('config_template') . '/template/activity/visitordetail.tpl';
		$this->response->setOutput($this->render());
	}
	
	public function webcam(){
	if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
	}
		$this->template = $this->config->get('config_template') . '/template/activity/webcam.tpl';
		$this->response->setOutput($this->render());
	}
	
	public function uploadwebcam(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
			exit;
		}

		$folder = DIR_IMAGE.'uploads/';
		$folder2 = HTTP_SERVER.'image/uploads/';
		$filename = md5($_SERVER['REMOTE_ADDR'].rand()).'.jpg';

		$original = $folder.$filename;

		// The JPEG snapshot is sent as raw input:
		$input = file_get_contents('php://input');

		if(md5($input) == '7d4df9cc423720b7f1f3d672b89362be'){
			// Blank image. We don't need this one.
			exit;
		}

		$result = file_put_contents($original, $input);
		if (!$result) {
			
			
			
			echo '{
				"error"		: 1,
				"message"	: "Failed save the image. Make sure you chmod the uploads folder and its subfolders to 777."
			}';
			exit;
		}

		$info = getimagesize($original);
		if($info['mime'] != 'image/jpeg'){
			unlink($original);
			exit;
		}

		// Moving the temporary file to the originals folder:
		rename($original,DIR_IMAGE.'uploads/original/'.$filename);
		$original = DIR_IMAGE.'uploads/original/'.$filename;

		// Using the GD library to resize 
		// the image into a thumbnail:

		$origImage	= imagecreatefromjpeg($original);
		$newImage	= imagecreatetruecolor(154,110);
		imagecopyresampled($newImage,$origImage,0,0,0,0,154,110,520,370); 

		imagejpeg($newImage,DIR_IMAGE.'uploads/thumbs/'.$filename);

		echo '{"status":1,"message":"Success!","filename":"'.HTTPS_SERVER.'image/uploads/original/'.$filename.'"}';
		
		
		//echo '{"status":1,"message":"Success!","filename":"'.$filename.'"}';
			}
			
	public function searchVisitor(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
		
		
		
		$this->load->model('activitylog/activitylog');
		
		$json = array();

		//if($this->request->get['activitylog_id'] != null && $this->request->get['activitylog_id'] != "") {

			if (isset($this->request->get['activitylog_id'])) {
				$activitylog_id = $this->request->get['activitylog_id'];
			} else {
				$activitylog_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'activitylog_id'  => $activitylog_id,
				'status'  => '3',
				'facilities_id'  => $this->customer->getId(),
				'start'        => 0,
				'limit'        => $limit
			);
			
			$users = $this->model_activitylog_activitylog->getLogs($data);
			
			foreach ($users as $user) {
				
				if($user['picture']){
					$imagdata = $user['picture'];
				}else{
					$imagdata = 'sites/view/digitalnotebook/image/user01.jpg';
				}  
				
				$json[] = array(
					'full_name' => $user['first_name'] .' '.$user['last_name'],
					'last_name' => $user['last_name'],
					'activitylog_id' => $user['activitylog_id'],
					'personvisiting' => $user['personvisiting'],
					'company_name' => $user['company_name'],
					'reason' => $user['reason'],
					'state_id' => $user['state_id'],
					'driving_lic' => $user['driving_lic'],
					'imgOutput' => $imagdata,
					'date_added' =>  date("D j, F Y", strtotime($user['date_added'])),
					'checkouttime' =>  date("h:i A", strtotime($user['date_added']))
					
				);	
			}
		//}

		$this->response->setOutput(json_encode($json));
		
		
	}		
	
	public function listing(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$this->load->model('facilities/facilities');
			$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			
			if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			} else {
				$sort = 'first_name';
			}

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'ASC';
			}

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if(isset($this->request->post['filter_date_start']) && isset($this->request->post['filter_date_end'])){
			$filter_date_start = $this->request->post['filter_date_start'];
			$this->data['filter_date_start'] = $this->request->post['filter_date_start'];
			$filter_date_end = $this->request->post['filter_date_end'];
			$this->data['filter_date_end'] = $this->request->post['filter_date_end'];
			
			}
			
			
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$noteTime =  date('H:i:s');
				
				$date = str_replace('-', '/', $this->request->get['searchdate']);
				$res = explode("/", $date);
				$createdate1 = $res[1]."-".$res[0]."-".$res[2];
				
				$searchdate = $this->request->get['searchdate'];
				
				$this->data['searchdate'] = date('D j, F Y', strtotime($createdate1));
				
			} else {
				$this->data['searchdate'] =  date('D j, F Y');
				$searchdate =  date('m-d-Y');
			}
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 .= '&searchdate=' . $this->request->get['searchdate'];
			}
			
			$this->data['fsearch_url'] = $this->url->link('common/activity/listing',''. $url2, 'SSL');
			
			
			if ($this->request->get['filterval'] != null && $this->request->get['filterval'] != "") {
				$url2 .= '&filterval=' . $this->request->get['filterval'];
			}
			
			$this->data['print_url'] = $this->url->link('common/activity/printVisiterlist',''. $url2, 'SSL');
			
			
			if($this->request->get['filterval'] != NULL && $this->request->get['filterval'] != ""){
				$interval = $this->request->get['filterval'];
				$this->data['filterval'] = $this->request->get['filterval'];
			}else{
				$interval = '';
			}
			
			
			$this->load->model('activitylog/activitylog');
				$data = array(
				'sort'  => $sort,
				'searchdate' => $searchdate,
				'facilities_id' => $this->customer->getId(),
				'filter_date_end' => $filter_date_end,
				'filter_date_start' => $filter_date_start,
				'status'  => $interval,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_front_limit'),
				'limit' => $this->config->get('config_front_limit')
				);

			$visitor_total = $this->model_activitylog_activitylog->getTotalLogs($data);
			$alllogs = $this->model_activitylog_activitylog->getLogs($data);
			
			$this->load->model('setting/zone');
			$this->data['results'] = array();
			
			foreach($alllogs as $allog){
				$stateinfo = $this->model_setting_zone->getZone($allog['state_id']);
				if($allog['date_updated'] != null && $allog['date_updated'] != '0000-00-00 00:00:00'){
					
					$checkout = date("D j, F Y", strtotime($allog['date_updated']));
					$ckhtime = date("h:i A", strtotime($allog['date_updated']));
				}else{
					$checkout = '-';
					$ckhtime = '-';
				}
				
				$this->data['results'][] = array(
				'activitylog_id' => $allog['activitylog_id'],
				'first_name' => $allog['first_name'],
				'last_name' => $allog['last_name'],
				'company_name' => $allog['company_name'],
				'personvisiting' => $allog['personvisiting'],
				'reason' => $allog['reason'],
				'state' => $stateinfo['name'],
				'picture' => $allog['picture'],
				'status' => $allog['status'],
				'checkin' =>  date("D j, F Y", strtotime($allog['date_added'])),
				'checkintime' =>  date("h:i A", strtotime($allog['date_added'])),
				
				'checkout' =>  $checkout,
				'checkouttime' =>  $ckhtime,
				'href' => $this->url->link('common/activity/generatePdf&activitylog_id='.$allog['activitylog_id'],'', 'SSL')
				
				);	
				
			}
		
		$pagination = new Pagination();
		$pagination->total = $visitor_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_front_limit');
		//$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('common/activity/listing', '' . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
			
			$this->data['home'] = $this->url->link('common/activity','', 'SSL');
			
			$this->data['search_url'] = $this->url->link('common/activity/listing','', 'SSL');
			
			//$this->data['edit_url'] = $this->url->link('common/activity/checkin&activitylog_id='.$this->request->get['activitylog_id'],'', 'SSL');
			
			$this->template = $this->config->get('config_template') . '/template/activity/listing.tpl';
			$this->response->setOutput($this->render());
		
	}	

	public function confirmation(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
					
		$this->load->model('activitylog/activitylog');
		$this->model_activitylog_activitylog->confirm($this->request->get['activitylog_id'], $date_added);
		
		
		$allData = array();
			$alllogs = $this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
			
			
			$first_name = $alllogs['first_name'];
			$last_name = $alllogs['last_name'];
			$company_name = $alllogs['company_name'];
			$personvisiting = $alllogs['personvisiting'];
			$reason = $alllogs['reason'];
			
			//$date_added = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkintime = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkouttime = date("Y-m-d H:i:s", strtotime($alllogs['date_updated']));
			
			$checkintime11 = date("h:i A", strtotime($alllogs['date_added']));
			
			$start_date = new DateTime($alllogs['date_added']);
			$since_start = $start_date->diff(new DateTime($alllogs['date_updated']));
			
			$caltime = "";
			$status_total_time = 0;
			/*if($since_start->days > 0){
			$caltime .=  $since_start->days.' days total | ';
			}*/
			if($since_start->y > 0){
				$caltime .= $since_start->y.' years ';
				$status_total_time = 60*24*365*$since_start->y;
			}

			if($since_start->m > 0){
				$caltime .= $since_start->m.' months ';
				$status_total_time += 60*24*30*$since_start->m;
			}

			if($since_start->d > 0){
				$caltime .= $since_start->d.' days ';
				$status_total_time += 60*24*$since_start->d;
			}

			if($since_start->h > 0){
				$caltime .= $since_start->h.' hours ';
				$status_total_time += 60*$since_start->h;
			}

			if($since_start->i > 0){
				$caltime .= $since_start->i.' minutes ';
				$status_total_time += $since_start->i;
			}
			/*if($since_start->s > 0){
			$caltime .= $since_start->s.' seconds ';
			}*/ 
			
			$notetime = date('H:i:s', strtotime('now'));
			
			$allData['status_total_time']  = $status_total_time;
			$allData['date_added']  = $date_added;
			$allData['note_date']  = $date_added;
			$allData['notetime']  = $notetime;
			$allData['facilitytimezone']  = $timezone_name;
			
			$allData['user_id']  = 'admin';
			$allData['notes_pin']  = '123';
			$allData['visitor_log']  = '2';
			
			//$allData['notes_description']  = 'Visitor Log Entry | '.$first_name.'&nbsp;'.$last_name.' | has CHECKED OUT TIME | '.$caltime.' | of the Facility';
			
			$allData['notes_description']  = 'Visitor Log Entry | '.$first_name.'&nbsp;'.$last_name.' | has CHECKED OUT of the facility at |'.$checkintime11.' | Time in Facility | '.$caltime.' ';
			
			
			$this->load->model('notes/notes');
			$this->model_notes_notes->jsonaddnotes($allData, $this->customer->getId());
			
			
			
		$this->redirect($this->url->link('common/activity' ,'', 'SSL'));
	}
	
	public function userpin(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$this->language->load('notes/notes');
		
		$this->load->model('facilities/facilities');
		$this->data['facility_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate3()) {
			
			$this->redirect($this->url->link('common/activity/listing' ,'', 'SSL'));	
		
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		

		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
	

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
			
		$this->data['back'] = $this->url->link('common/activity',''.$url, 'SSL');
		$this->data['action'] = $this->url->link('common/activity/userpin',''.$url, 'SSL');
		$this->template = $this->config->get('config_template') . '/template/activity/userpin.tpl';
		$this->response->setOutput($this->render());
	}
	
	protected function validate3() {
	
		if ($this->request->post['user_id'] == '') {
			$this->error['user_id'] = $this->language->get('error_required');
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$this->error['user_id'] = $this->language->get('error_required');
			}
		}
		
			if ($this->request->post['notes_pin'] == '') {
				$this->error['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['notes_pin'] = $this->language->get('error_exists');
				}
			}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function generatePdf(){
		/*if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}*/
		$this->document->setTitle('Visitor');
		//var_dump($this->request->get['activitylog_id']);
		
		$this->load->model('activitylog/activitylog');
		$visitorData = $this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
		
		 //$date_added = date("D j, F Y",strtotime($visitorData['date_added']); 
		 //$date_time = date("h:i A ", strtotime($activitylog['date_added']);
		
		require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,  array(80,120), true, 'UTF-8', false);

		$pdf = new TCPDF('P', 'mm', array('100','88'), true, 'UTF-8', false);
		//$pdf = new TCPDF('L', 'mm', array('79','108'), true, 'UTF-8', false);
		
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Visitor');
		$pdf->SetSubject('Visitor');
		$pdf->SetKeywords('Visitor');


/*
		if ($this->config->get('pdf_report_image') && file_exists(DIR_SYSTEM . 'library/pdf_class/'.$this->config->get('pdf_report_image'))) {
			$imageLogo = $this->config->get('pdf_report_image');
			$PDF_HEADER_LOGO_WIDTH = "30";
						
		}else{
			$imageLogo = '4F-logo.png';
			$PDF_HEADER_LOGO_WIDTH = "30";
			$headerString = "";	
		}

		$PDF_HEADER_TITLE = "Visitor Detail";
		$headerString = date('jS F Y');
		

		$pdf->SetHeaderData($imageLogo, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE.'', $headerString);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
*/

		$pdf->SetMargins('5', '5', '5');
		$pdf->SetHeaderMargin('5');
		$pdf->SetFooterMargin('0');

		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$pdf->SetFont('helvetica', '', 9);
		$pdf->AddPage();
 
		$html='';
		$html .='<style>

			td {
				padding: 10px;
				margin: 10px;
			  
			   line-height:20.2px;
			   display:table-cell;
				padding:5px;
			}
			</style>
			';
			
		$html .='<table  cellpadding="2" cellspacing="0" style="width="100%" border:1px solid #ddd;" align="center">';

				$html .='  <tr>';
				$html .='    <td style="text-align:center; width:100%; margin-bottom:15px; background-color:#fa801b; padding:7px; line-height:10.2px;">Visitor Badge ID</td>';	
				$html .='  </tr>';
				
				$html .='  <tr colspan="2">';
				$html .='	 <td style="text-align:center;width:100%; margin-bottom:10px; line-height:20.2px;"> '.date('D j, F Y h:i A', strtotime($visitorData['date_added'])).' </td>';
				$html .='  </tr>'; 
				
				
				$html .='  <tr colspan="2" style="margin-bottom:10px; margin-top:5px;" >';
				$html .='    <td style="text-align:center;width:100%; margin-bottom:10px; margin-top:5px; line-height:20.2px;"><img src="'.$visitorData['picture'].'" width="80px" ></td>';
				$html .='  </tr>';
				
				$html .='  <tr>';
				$html .='    <td style="text-align:center;width:100%;margin-bottom:10px;line-height:10.2px;"> '.$visitorData['first_name'].' &nbsp;&nbsp;'.$visitorData['last_name'].' </td>';	
				$html .='  </tr>';
				
				
			/*	$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: left;width:40%">First Name</td>';
				$html .='<td style="text-align:left;width:60%; line-height:20.2px;"> '.$visitorData['first_name'].' </td>';
				$html .='  </tr>';
				
				
				$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: left;width:40%">Last Name</td>';
				$html .='<td style="text-align:left;width:60%; line-height:20.2px;"> '.$visitorData['last_name'].' </td>';
				$html .='  </tr>';
				
				
				
		
				*/
				
				
				$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: left;width:40%; line-height:10.2px;">Company Name</td>';
				$html .='<td style="text-align:left;width:60%; line-height:10.2px;"> '.$visitorData['company_name'].' </td>';
				$html .='  </tr>';
				
				$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: left;width:40%;line-height:10.2px;">Person Visiting</td>';
				$html .='	 <td style="text-align:left;width:60%; line-height:10.2px;"> '.$visitorData['personvisiting'].' </td>';
				$html .='  </tr>';
				
				$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: left;width:40%;line-height:10.2px;">Reason</td>';
				$html .='    <td style="text-align:left;width:60%; line-height:10.2px;"> '.$visitorData['reason'].' </td>';
				$html .='  </tr>';
				
				$html .='  <tr>';
				$html .='    <td style="text-align:left; width:100%; background-color:#fa801b; padding:7px; line-height:10.2px;"></td>';	
				$html .='  </tr>';
				
		

		$html .='</table>';

		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();
		$pdf->Output('report_' . rand() . '.pdf', 'I');
		exit;
	}
	
	
	public function printVisiterlist(){
		/*if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}*/
	
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$noteTime =  date('H:i:s');
				
				$date = str_replace('-', '/', $this->request->get['searchdate']);
				$res = explode("/", $date);
				$createdate1 = $res[1]."-".$res[0]."-".$res[2];
				
				$searchdate = $this->request->get['searchdate'];
				
				$this->data['searchdate'] = date('D j, F Y', strtotime($createdate1));
				
			} else {
				$this->data['searchdate'] =  date('D j, F Y');
				$searchdate =  date('m-d-Y');
			}
			
			
			if($this->request->get['filterval'] != NULL && $this->request->get['filterval'] != ""){
				$interval = $this->request->get['filterval'];
				$this->data['filterval'] = $this->request->get['filterval'];
			}else{
				$interval = '';
			}
			
			if($this->request->post['facilities_id']){
				$facilities_id = $this->request->post['facilities_id'];
			}else{
				$facilities_id = $this->customer->getId();
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			
			$this->load->model('activitylog/activitylog');
				$data = array(
				'sort'  => $sort,
				'searchdate' => $searchdate,
				'facilities_id' => $facilities_id,
				'filter_date_end' => $filter_date_end,
				'filter_date_start' => $filter_date_start,
				'status'  => $interval,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_front_limit'),
				'limit' => $this->config->get('config_front_limit')
				);

			
			$alllogs = $this->model_activitylog_activitylog->getLogs($data);
			
			$this->load->model('setting/zone');
			$this->data['results'] = array();
			
			foreach($alllogs as $allog){
				$stateinfo = $this->model_setting_zone->getZone($allog['state_id']);
				if($allog['date_updated'] != null && $allog['date_updated'] != '0000-00-00 00:00:00'){
					
					$checkout = date("D j, F Y", strtotime($allog['date_updated']));
					$ckhtime = date("h:i A", strtotime($allog['date_updated']));
				}else{
					$checkout = '-';
					$ckhtime = '-';
				}
				
				if($allog['picture']){
					$picture = $allog['picture'];
				}else{
					$picture = "sites/view/digitalnotebook/image/user01.jpg";
				}
				
				$this->data['results'][] = array(
				'activitylog_id' => $allog['activitylog_id'],
				'first_name' => $allog['first_name'],
				'last_name' => $allog['last_name'],
				'company_name' => $allog['company_name'],
				'personvisiting' => $allog['personvisiting'],
				'reason' => $allog['reason'],
				'state' => $stateinfo['name'],
				'picture' => $picture,
				'status' => $allog['status'],
				'checkin' =>  date("D j, F Y", strtotime($allog['date_added'])),
				'checkintime' =>  date("h:i A", strtotime($allog['date_added'])),
				
				'checkout' =>  $checkout,
				'checkouttime' =>  $ckhtime,
				'href' => $this->url->link('common/activity/generatePdf&activitylog_id='.$allog['activitylog_id'],'', 'SSL')
				
				);	
				
			}
			
		$this->document->setTitle('Visitor List');
		$this->load->model('activitylog/activitylog');
		$visitorData = $this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
		
		require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,  PDF_PAGE_FORMAT, true, 'UTF-8', false);

		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('');
		$pdf->SetTitle('Visitor Log');
		$pdf->SetSubject('Visitor Log List');
		$pdf->SetKeywords('Visitor Log');

		$headerString = date("D j, F Y");
		
		$pdf->SetHeaderData('', '', 'Visitor Log'.'', $headerString);
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		$pdf->SetFont('helvetica', '', 9);
		$pdf->AddPage();
 
		$html='';
		$html .='<style>
			td {
				padding: 10px;
				margin: 10px;
			   line-height:20.2px;
			   display:table-cell;
				padding:5px;
			}
			</style>
			';
			
		$html .='<table  cellpadding="2" cellspacing="0" style="width="100%" border:1px solid #ddd;" align="center">';
		
				$html .='<thead>';
				$html .='  <tr>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:10%">Checkin Time</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:10%">Checkout Time</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:20%">Image</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">First Name</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Last Name</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Company Name</td>';
				$html .='    <td valign="middle" style="text-align: center;background-color: #DAEEF3;width:15%">Person Visiting</td>';
				$html .='  </tr>';
			   $html .=' </thead>';
	   
			   foreach($this->data['results'] as $result){
				
				$html .='<tr>';
				$html .='<td style="text-align:center;width:10%; line-height:20.2px;"><br> '.$result['checkintime'].'</td>';
				$html .='<td style="text-align:center;width:10%; line-height:20.2px;"><br> '.$result['checkouttime'].'</td>';
				$html .='<td style="text-align:center;width:20%; line-height:20.2px;"><br> <img src="'.$result['picture'].'" width="50px"  height="50px" ></td>';
				$html .='<td style="text-align:center;width:15%; line-height:20.2px;"><br> '.$result['first_name'].'</td>';
				$html .='<td style="text-align:center;width:15%; line-height:20.2px;"><br> '.$result['last_name'].'</td>';
				$html .='<td style="text-align:center;width:15%; line-height:20.2px;"><br> '.$result['company_name'].'</td>';
				$html .='<td style="text-align:center;width:15%; line-height:20.2px;"><br> '.$result['personvisiting'].'</td>';
				
				$html .='</tr>';
				
				}
		

		$html .='</table>';

		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();
		$pdf->Output('report_' . rand() . '.pdf', 'I');
		exit;
			
			
	
	}
	
	
	public function complete(){
		if (!$this->customer->isLogged()) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$allData = array();
		
		$timezone_name = $this->customer->isTimezone();
					$timeZone = date_default_timezone_set($timezone_name);
					$noteDate = date('Y-m-d H:i:s', strtotime('now'));
					$date_added = (string) $noteDate;
					
		$this->load->model('activitylog/activitylog');
			$alllogs = $this->model_activitylog_activitylog->getLog($this->request->get['activitylog_id']);
			
			
			$first_name = $alllogs['first_name'];
			$last_name = $alllogs['last_name'];
			$company_name = $alllogs['company_name'];
			$personvisiting = $alllogs['personvisiting'];
			$reason = $alllogs['reason'];
			
			
			$picture = $alllogs['picture'];
			$imgOutput = $alllogs['imgOutput'];
			
			$date_added = date("Y-m-d H:i:s", strtotime($alllogs['date_added']));
			$checkintime = date("h:i A", strtotime($alllogs['date_added']));
			
			$notetime = date('H:i:s', strtotime('now'));
			
			$allData['date_added']  = $date_added;
			$allData['note_date']  = $date_added;
			$allData['notetime']  = $notetime;
			$allData['facilitytimezone']  = $timezone_name;
			
			$allData['user_id']  = 'admin';
			$allData['notes_pin']  = '123';
			$allData['visitor_log']  = '1';
			
			if($picture){
				$allData['notes_file']  = $picture;
				$allData['notes_media_extention']  = 'jpg';
			}else{
				$allData['notes_file']  = $imgOutput;
				$allData['notes_media_extention']  = 'jpg';
			}
			
			$allData['notes_description']  = 'Visitor Log Entry | '.$first_name.'&nbsp;'.$last_name.' | has checked in to the facility at |'.$checkintime.' | to meet with | '.$personvisiting.' | for |'.$reason.' | ';

			
			$this->load->model('notes/notes');
			$this->model_notes_notes->jsonaddnotes($allData, $this->customer->getId());
		
		$this->redirect($this->url->link('common/activity' ,'', 'SSL'));
	}
	
	
}