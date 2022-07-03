<?php
class Modelnotesnotescomment extends Model {

	public function addnotescomment($data, $facilities_id) {
        
        $timezone_name = $data['facilitytimezone'];
        
        date_default_timezone_set($timezone_name);
        
        $update_date = date('Y-m-d H:i:s', strtotime('now'));
		$date_added = date('Y-m-d H:i:s', strtotime('now'));
            
		$createdate1 = $data['comment_date'];
        $createtime1 = date('H:i:s');
        $createDate2 = $createdate1 . $createtime1;
        $comment_date = date('Y-m-d H:i:s', strtotime($createDate2));
		
		if ($facilities_id) {
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
			$unique_id = $facility['customer_key'];
		}
		
		$notes_pin = $data['notes_pin'];
       
		
		if($data['imgOutput'] != NULL && $data['imgOutput'] != ""){
			//$signature = $data['imgOutput'];
			
			$signature = '';
			if($data ['imgOutput']!='' && $data ['imgOutput']!= null){
				$this->load->model('api/savesignature');
				$sigdata = array();
				$sigdata['upload_file'] = $data ['imgOutput'];
				$sigdata['facilities_id'] = $facilities_id;
				$signaturestatus = $this->model_api_savesignature->savesignature($sigdata);
				
				$signature = $signaturestatus;
			}
			
		}else{
			//$signature = $data['signature'];
			$signature = '';
			if($data ['signature']!='' && $data ['signature']!= null){
				$this->load->model('api/savesignature');
				$sigdata = array();
				$sigdata['upload_file'] = $data ['signature'];
				$sigdata['facilities_id'] = $facilities_id;
				$signaturestatus = $this->model_api_savesignature->savesignature($sigdata);
				
				$signature = $signaturestatus;
			}
		}
		
		if($data['comment'] != NULL && $data['comment'] != ""){
			$notes_description = $data['comment'];
		}else{
			$notes_description = $data['notes_description'];
		}
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($data['user_id']);
		
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_comment` SET notes_id = '" . (int) $data['notes_id'] . "', facilities_id = '" . $facilities_id . "', comment = '" . $this->db->escape($notes_description) . "', notes_pin = '" . $this->db->escape($notes_pin) . "', user_id = '" . $this->db->escape($user_info['username']) . "', signature = '" . $signature . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "',  comment_date = '" . $comment_date . "', tags_id = '" . $this->db->escape($data['tags_id']) . "', notes_type = '" . $this->db->escape($data['notes_type']) . "', is_android='" . $data['is_android'] . "', phone_device_id='" . $this->db->escape($data['phone_device_id']) . "', device_unique_id='" . $this->db->escape($data['device_unique_id']) . "', unique_id = '" . $this->db->escape($unique_id) . "' ";
        $this->db->query($sql);
        $comment_id = $this->db->getLastId();
		
		if ($data['active_note_id'] != null && $data['active_note_id'] != "") {
            $this->load->model('setting/image');
            $keywords = explode(",", $data['active_note_id']);
            
            foreach ($keywords as $keyword) {
                
                $keyword_icon = '';
                $keyword_file = $keyword;
                
                $this->load->model('setting/keywords');
                $keywordData2 = $this->model_setting_keywords->getkeywordDetailbyid($keyword, $facilities_id);
				
				$notes_description2 = str_replace($keywordData2['keyword_name'], $keywordData2['keyword_name'], $notes_description);
                
                $sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $data['notes_id'] . "', keyword_id = '" . $this->db->escape($keywordData2['keyword_id']) . "', keyword_name = '" . $this->db->escape($keywordData2['keyword_name']) . "', keyword_file = '" . $this->db->escape($keywordData2['keyword_image']) . "', keyword_file_url = '" . $this->db->escape($keyword_icon) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape($unique_id) . "', date_added = '" . $date_added . "' , type = 'comment', comment_id = '" . $comment_id . "' ";
                $this->db->query($sqlm);
                
               
            }
            
            $sql1233 = "UPDATE `" . DB_PREFIX . "notes_by_comment` SET keyword_file = '1' WHERE comment_id = '" . (int) $comment_id . "'";
            $this->db->query($sql1233);
			
			
			
        }
		
		if ($data['keyword_file'] != null && $data['keyword_file'] != "") {
            $this->load->model('setting/image');
            $keywords = explode(",", $data['keyword_file']);
            
            foreach ($keywords as $keyword) {
                
                $keyword_icon = '';
                $keyword_file = $keyword;
                
                $this->load->model('setting/keywords');
                $keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($keyword, $facilities_id);
				
				$notes_description2 = str_replace($keywordData2['keyword_name'], $keywordData2['keyword_name'], $notes_description);
                
                $sqlm = "INSERT INTO `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $data['notes_id'] . "', keyword_id = '" . $this->db->escape($keywordData2['keyword_id']) . "', keyword_name = '" . $this->db->escape($keywordData2['keyword_name']) . "', keyword_file = '" . $this->db->escape($keywordData2['keyword_image']) . "', keyword_file_url = '" . $this->db->escape($keyword_icon) . "', keyword_status = '1', facilities_id = '" . $facilities_id . "', unique_id = '" . $this->db->escape($unique_id) . "', date_added = '" . $date_added . "' , type = 'comment', comment_id = '" . $comment_id . "' ";
                $this->db->query($sqlm);
                
               
            }
            
            $sql1233 = "UPDATE `" . DB_PREFIX . "notes_by_comment` SET keyword_file = '1' WHERE comment_id = '" . (int) $comment_id . "'";
            $this->db->query($sql1233);
			
			
			
        }
		
		$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_conut ='0', is_comment ='1',update_date = '" . $update_date . "' WHERE notes_id = '" . (int) $data['notes_id'] . "'";
        $this->db->query($sql1);
	
		return $comment_id; 	
	}
	
	 public function getexistnotes ($data, $facilities_id)
    {
        $sqle = "SELECT notes_id,facilities_id,comment_id,phone_device_id,device_unique_id FROM `" . DB_PREFIX . "notes_by_comment` WHERE facilities_id = '" . (int) $facilities_id . "' and phone_device_id = '" . $this->db->escape($data['phone_device_id']) . "' and device_unique_id = '" . $this->db->escape($data['device_unique_id']) . "' ";
		
        $query = $this->db->query($sqle);
        
        return $query->row;
    }
	
	public function updateuserpicture ($user_file, $comment_id)
    {
        $sql = "UPDATE `" . DB_PREFIX . "notes_by_comment` SET user_file = '" . $this->db->escape($user_file) . "' where comment_id = '" . (int) $comment_id . "' ";
        $this->db->query($sql);
		
		$this->load->model('activity/activity');
		$adata['comment_id'] = $comment_id;
		$adata['user_file'] = $user_file;
		$this->model_activity_activity->addActivitySave('updateuserpicture', $adata, 'query');
    }

    public function updateuserverified ($is_user_face, $comment_id)
    {
        $sql = "UPDATE `" . DB_PREFIX . "notes_by_comment` SET is_user_face = '" . $this->db->escape($is_user_face) . "' where comment_id = '" . (int) $comment_id . "' ";
        $this->db->query($sql);
		
		$this->load->model('activity/activity');
		$adata['comment_id'] = $comment_id;
		$adata['is_user_face'] = $is_user_face;
		$this->model_activity_activity->addActivitySave('updateuserverified', $adata, 'query');
    }
	
	public function getcommentskeywors ($comment_id)
    {
        $notes_keywords = array();
        $this->load->model('notes/image');
		
		$keyword_image = "";
		
        $sql = "SELECT notes_by_keyword_id,notes_id,keyword_id,keyword_name,keyword_file,keyword_file_url,keyword_status,active_tag,facilities_id,date_added,is_monitor_time,user_id,override_monitor_time_user_id,comment_id FROM `" . DB_PREFIX . "notes_by_keyword` WHERE comment_id = '" . (int) $comment_id . "' and keyword_status = '1' ";

        $query = $this->db->query($sql);
        
        foreach ($query->rows as $result) {
            
			$sql1 = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" .  $result['keyword_file'] . "' and FIND_IN_SET('". $result['facilities_id']."', facilities_id) ";
            $queryd = $this->db->query($sql1);
             
            $keyword_info = $queryd->row;
			
			$image = "";
			if($keyword_info['keyword_image'] != null && $keyword_info['keyword_image'] != ""){
				
				//$image = $this->model_notes_image->resize('icon/'.$keyword_info['keyword_image'], 54, 54);
				//$image = HTTP_SERVER . 'image/icon/' . $keyword_info['keyword_image'];
				$image = $keyword_info['keyword_image'];
				$keyword_image = $keyword_info['keyword_image'];
				
			}else{
			
				$sql1 = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int) $result['keyword_id'] . "'";
				$queryd = $this->db->query($sql1);
				
				$keyword_info = $queryd->row;
				
				if($keyword_info['keyword_image'] != null && $keyword_info['keyword_image'] != ""){
					//$image = $this->model_notes_image->resize('icon/'.$keyword_info['keyword_image'], 54, 54);
					//$image = HTTP_SERVER . 'image/icon/' . $keyword_info['keyword_image'];
					$image = $keyword_info['keyword_image'];
					$keyword_image = $keyword_info['keyword_image'];
				}
            }
			
			if($keyword_info['keyword_name'] != null && $keyword_info['keyword_name'] != ""){
				$notes_keywords[] = array(
					'notes_by_keyword_id' => $result['notes_by_keyword_id'],
					'notes_id' => $result['notes_id'],
					'comment_id' => $result['comment_id'],
					'keyword_id' => $keyword_info['keyword_id'],
					'keyword_name' => $keyword_info['keyword_name'],
					'keyword_file' => $keyword_info['keyword_file'],
					'keyword_image' => $keyword_image,
					'keyword_file_url' => $image,
					'keyword_status' => $result['keyword_status'],
					'active_tag' => $result['active_tag'],
					'facilities_id' => $result['facilities_id'],
					'date_added' => $result['date_added'],
					'is_monitor_time' => $result['is_monitor_time'],
					'user_id' => $result['user_id'],
					'override_monitor_time_user_id' => $result['override_monitor_time_user_id']
				);
			}
        }
        
        return $notes_keywords;
    }
	
	public function getcomments ($notes_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_comment` WHERE notes_id = '" . $notes_id . "'  ";
        $query = $this->db->query($sql);
        return $query->rows;
    }
	
	public function updatecomment($data,$notes_id,$comment_id)
    {
		
		$noteDate = date('Y-m-d H:i:s', strtotime('now'));
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($data['user_id']);
		
		$signature = '';
		$this->load->model('notes/notes');
		if($data ['imgOutput']!='' && $data ['imgOutput']!= null){
			
			$notes_info = $this->model_notes_notes->getNote ( $notes_id );
			$notes_description = $notes_info ['notes_description'];
			$facilities_id = $notes_info ['facilities_id'];
			
			$this->load->model('api/savesignature');
			$sigdata = array();
			$sigdata['upload_file'] = $data ['imgOutput'];
			$sigdata['facilities_id'] = $facilities_id;
			$signaturestatus = $this->model_api_savesignature->savesignature($sigdata);
			
			$signature = $signaturestatus;
		}
		
		$qsll = "UPDATE `" . DB_PREFIX . "notes_by_comment` SET 
			notes_id = '" . $notes_id . "', 
			signature = '" . $signature . "', 
			user_id = '" . $user_info['username'] . "', 
			date_updated = '" . $noteDate . "' ,
			tags_id = '" . $data['tags_id'] . "' ,
			notes_pin = '" . $data['notes_pin'] . "' 
			WHERE comment_id = '" . (int) $comment_id . "' ";
       
			$this->db->query($qsll);
		
		  $sql = "UPDATE `" . DB_PREFIX . "notes` SET is_comment = '1',update_date = '" . $noteDate . "' where notes_id = '" . (int) $notes_id . "' ";
		  $this->db->query($sql);
		  
		  
		$sql1233 = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET notes_id = '" . $notes_id . "' WHERE comment_id = '" . (int) $comment_id . "'";
        $this->db->query($sql1233);
		
		
		$this->load->model('activity/activity');
		$adata['comment_id'] = $comment_id;
		$this->model_activity_activity->addActivitySave('updateComment', $adata, 'query');
    }
	
}
?>