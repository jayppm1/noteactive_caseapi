<?php
class Modelnotestranscription extends Model {

	public function addtranscription($data, $facility){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_transcript` SET 
		source_transcript = '" . $this->db->escape($data['source_transcript']) . "', 
		source_language = '" . $this->db->escape($data['source_lang']) . "', 
		target_transcript = '" . $this->db->escape($data['target_transcript']) . "', 
		target_language = '" . $this->db->escape($data['target_lang']) . "' ";
	
        $this->db->query($sql);
		
		$notes_by_transcript_id = $this->db->getLastId();
		
		return $notes_by_transcript_id;
	}
	
	public function updatetranscription ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$facilities_id = $fdata['facilities_id']; 
		$timezone_name = $fdata['facilitytimezone'];
       
        $timeZone = date_default_timezone_set($timezone_name);
        $noteDate = date('Y-m-d H:i:s', strtotime('now'));
        $date_added = (string) $noteDate;
        
        $notetime = date('H:i:s', strtotime('now'));
        
        if ($pdata['imgOutput']) {
            $data['imgOutput'] = $pdata['imgOutput'];
        } else {
            $data['imgOutput'] = $pdata['signature'];
        }
        
        $data['notes_pin'] = $pdata['notes_pin'];
        $data['user_id'] = $pdata['user_id'];
        
        $this->load->model('setting/tags');
        $tag_info = $this->model_setting_tags->getTag($fdata['tags_id']);
        
        $data['emp_tag_id'] = $tag_info['emp_tag_id'];
        $data['tags_id'] = $tag_info['tags_id'];
        
        
        if ($tag_info['emp_first_name']) {
            $emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
        } else {
            $emp_tag_id = $tag_info['emp_tag_id'];
        }
        
        if ($tag_info) {
            $medication_tags .= $emp_tag_id . ', ';
        }
        
        $description = '';
		$description .= ' Voice Transcription | ';
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $description .= ' | ' . $this->db->escape($pdata['comments']);
        }
        
        $data['notes_description'] = $description;
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
        $data['facilitytimezone'] = $timezone_name;
        
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
		
		$sqlt = "UPDATE `" . DB_PREFIX . "notes` SET is_comment = '2' where notes_id = '" . (int) $notes_id . "' ";
		$this->db->query($sqlt);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		if($fdata['notes_by_transcript_id'] != null && $fdata['notes_by_transcript_id'] != ""){
			$sql1s = "UPDATE `" . DB_PREFIX . "notes_by_transcript` SET notes_id='".$notes_id."', date_added = '".$this->db->escape($date_added)."', date_updated = '".$this->db->escape($date_added)."', facilities_id ='".$this->db->escape($facilities_id)."' WHERE notes_by_transcript_id = '" . (int)$fdata['notes_by_transcript_id'] . "' ";
			$this->db->query($sql1s);
		}
			
		$this->load->model('setting/tags');
        if (! empty($pdata['tagides'])) {
            foreach ($pdata['tagides'] as $tagid) {
                $tag_info = $this->model_setting_tags->getTag($tagid);
				$tadata = array();
                $this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'], $notes_id, $tag_info['tags_id'], $date_added,$tadata);
            }
        }
        	
        
        if ($facility['is_enable_add_notes_by'] == '1') {
            $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
            $this->db->query($sql122);
        }
        if ($facility['is_enable_add_notes_by'] == '3') {
            $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
            $this->db->query($sql13);
        }
        
        if ($facility['is_enable_add_notes_by'] == '1') {
            if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
                
        
                $notes_file = $this->session->data['local_notes_file'];
                $outputFolder = $this->session->data['local_image_dir'];
                require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
                $this->load->model('notes/notes');
                $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
                if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
                    $this->model_notes_notes->updateuserverified('2', $notes_id);
                }
        
                if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
                    $this->model_notes_notes->updateuserverified('1', $notes_id);
                }
        
                unlink($this->session->data['local_image_dir']);
                unset($this->session->data['username_confirm']);
                unset($this->session->data['local_image_dir']);
                unset($this->session->data['local_image_url']);
                unset($this->session->data['local_notes_file']);
            }
        }
		
        $this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('updatetranscription', $pdata, 'query');
        
        return $notes_id;
    }
	
	public function gettranscriptions ($notes_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_transcript` WHERE notes_id = '" . $notes_id . "'  ";
        $query = $this->db->query($sql);
        return $query->rows;
    }
	
}