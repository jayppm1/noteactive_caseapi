<?php  
class Controllernotesuploadcsv extends Controller {  
	private $error = array();
   
  	public function index() {
		$json = array ();
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->load->model('notes/notes');
		$this->load->model('user/user');
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm2()) {
			$row = 1;
			$insertCount = 0;
			$notinsertCount = 0;
			$arrResult = array ();
			if (($handle = fopen ( $this->request->files ["upload_csv"] ['tmp_name'], "r" )) !== FALSE) {
				
				while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
					
					$num = count ( $data );
					if ($row != '1') {
						
						$res1 = explode(" ",$data [3]);
						$date = str_replace('-', '/', $res1[0]);
						$res = explode("/", $date);
						$date_added = $res[2]."-".$res[0]."-".$res[1];
						
						$resn = explode(" ",$data [4]);
						$date2 = str_replace('-', '/', $resn[0]);
						$res2 = explode("/", $date2);
						$note_date = $res2[2]."-".$res2[0]."-".$res2[1];
						
						$notetime = date('H:i:s', strtotime($data [7]));
						
						$arrResult['date_added'] = $date_added;
						$arrResult['note_date'] = $note_date;
						$arrResult['notetime'] = $notetime;
						
						$arrResult['facilitytimezone'] = $data [18];
						
						$arrResult['notes_description'] = $data [13];
						$arrResult['text_color'] = $data [20];
						$arrResult['highlighter_id'] = $data [25];
						$arrResult['highlighter_value'] = $data [21];
						
						
						$user_info = $this->model_user_user->getUserbyupdatefacility ( $data [17] ,$data [10]);
						
						if($data [51] != null && $data [51] != ""){
							$arrResult['imgOutput'] = 'data:image/png;base64,'.$data [51];
						}else{
							$arrResult['notes_pin'] = $user_info['user_pin'];
						}
				
						
						$arrResult['user_id'] = $user_info['user_id'];
						$arrResult['notes_type'] = '5';
						
						
						$arrResult['phone_device_id'] = $data [9];
						$arrResult['device_unique_id'] = $data [8];
						
						$arrResult['is_android'] = '1';
						$arrResult['offline_csv'] = '1';
						if($data [44] != null && $data [44] != ""){
							$multipleimages = explode("##",$data [44]);
							$arrResult['multipleimages'] = $multipleimages;
						}
						
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($arrResult, $data [10]);
						
						
						$insertCount ++;
						
					}
					
					//echo "<hr>";
					
					$row ++;
				}
				fclose ( $handle );
			}
			//die;
			
			
			$this->session->data ['success'] = sprintf ( "CSV upload successfully ", $insertCount, $notinsertCount );
		}
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		
		$this->template = $this->config->get ( 'config_template' ) . '/template/notes/uploadcsv.php';
		$this->children = array (
				'common/headerpopup' 
		);
		$this->response->setOutput ( $this->render () );
  	}
	
	protected function validateForm2() {
		if ($this->request->files ["upload_csv"] ['name'] == null && $this->request->files ["upload_csv"] ['name'] == "") {
			$this->error ['warning'] = 'CSV is Required';
		}
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
}
?>