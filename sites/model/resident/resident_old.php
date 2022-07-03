<?php

class Modelresidentresident extends Model
{

    public function tagmedication ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 
			$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
				$timezone_name = $fdata['facilitytimezone'];
			}
			
		}else{
			$facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
		}
		
		
       
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
        
        $data['keyword_file'] = MEDICATION_ICON;
        
        $this->load->model('setting/keywords');
        
        $keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file'],$facilities_id);
        
        /*
         * $medicationf = "";
         * foreach($this->session->data['medication'] as $key=>$medication){
         *
         * $medication_info =
         * $this->model_resident_resident->get_medication($medication);
         * $medicationf .= $medication_info['drug_name'].', ';
         *
         * }
         */
        
        /*
         * if($this->request->post['comments'] != null &&
         * $this->request->post['comments']){
         * $comments = ' | '.$this->request->post['comments'];
         * }
         */
        
        if ($tag_info['emp_first_name']) {
            //$emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
			
			$this->load->model('setting/locations');
			$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
			$this->load->model('api/permision');
			$clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
			$cname = $clientinfo['name'];
			$emp_tag_id = $cname;//$tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
        } else {
            $emp_tag_id = $tag_info['emp_tag_id'];
        }
        
        if ($tag_info) {
            $medication_tags .= $emp_tag_id . ', ';
        }
        
        $description = '';
        $description .= $keywordData2['keyword_name'];
        $description .= ' | ';
        $description .= ' Completed | ' . date('h:i A', strtotime($notetime)) . ' ';
        $description .= ' Medication given to | ';
        $description .= ' ' . $medication_tags;
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $description .= ' | ' . $this->db->escape($pdata['comments']);
        }
        // $description .= ' | ';
        
        // $data['notes_description'] = $keywordData2['keyword_name'].' | '.
        // $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
        // '.$medicationf . $comments;
        
        $data['notes_description'] = $description;
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
		
		
		
        
        // var_dump($data);
        
        // die;
        
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        if ($fdata['medication_tags']) {
            $this->load->model('setting/tags');
            $this->load->model('createtask/createtask');
            
            // var_dump($this->request->get['medication_tags']);
            
            $medication_tags1 = explode(',', $fdata['medication_tags']);
            
            $date_added = date('Y-m-d H:i:s', strtotime('now'));
            
            foreach ($medication_tags1 as $medicationtag) {
                $drugs = array();
                $mdrug_info = $this->model_resident_resident->get_medication($medicationtag);
                
                if ($mdrug_info) {
                    
                    //$task_content = 'Resident ' . $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
					
					$this->load->model('setting/locations');
					$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
					
					$task_content = 'Resident ' . $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
                    
                    $tdata1 = array();
                    $tdata1['notes_id'] = $notes_id;
                    $tdata1['task_content'] = $task_content;
                    $tdata1['date_added'] = $date_added;
                    $tdata1['tags_id'] = $tag_info['tags_id'];
                    $tdata1['drug_name'] = $mdrug_info['drug_name'];
                    $tdata1['dose'] = $mdrug_info['dose'];
                    $tdata1['drug_type'] = $mdrug_info['drug_type'];
                    $tdata1['frequency'] = $mdrug_info['frequency'];
                    $tdata1['instructions'] = $mdrug_info['instructions'];
                    $tdata1['count'] = $mdrug_info['count'];
                    $tdata1['task_type'] = '2';
                    
                    $this->model_createtask_createtask->insertTaskmedicine($mdrug_info, $data, $tdata1);
                }
            }
            
            $update_date = date('Y-m-d H:i:s', strtotime('now'));
            
            if ($tag_info['emp_tag_id'] != null && $tag_info['emp_tag_id'] != "") {
                $this->load->model('notes/notes');
                
                $this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'], $notes_id, $tag_info['tags_id'], $update_date);
            }
        }
        
        $this->model_notes_notes->updatenotetags_med($notes_id);
        return $notes_id;
    }

    public function tagmedication2 ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];
			}
			
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];
		}
		
       
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
        
        // $data['keyword_file'] = MEDICATION_ICON;
        
        // $this->load->model('setting/keywords');
        
        // $keywordData2 =
        // $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
        
        /*
         * $medicationf = "";
         * foreach($this->session->data['medication'] as $key=>$medication){
         *
         * $medication_info =
         * $this->model_resident_resident->get_medication($medication);
         * $medicationf .= $medication_info['drug_name'].', ';
         *
         * }
         */
        
        /*
         * if($this->request->post['comments'] != null &&
         * $this->request->post['comments']){
         * $comments = ' | '.$this->request->post['comments'];
         * }
         */
        
        if ($tag_info['emp_first_name']) {
            $emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
			
			$this->load->model('setting/locations');
			$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
			$this->load->model('api/permision');
			$clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
			$cname = $clientinfo['name'];
			$emp_tag_id = $cname;//$tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
        } else {
            $emp_tag_id = $tag_info['emp_tag_id'];
        }
        
        if ($tag_info) {
            $medication_tags .= $emp_tag_id . ' ';
        }
        
        $description = '';
        // $description .= $keywordData2['keyword_name'];
        // $description .= ' | ';
        // $description .= ' Completed for | '.date('h:i A',
        // strtotime($notetime)) .' ';
        $description .= ' Health Form updated | ';
        $description .= ' ' . $medication_tags;
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $description .= ' | ' . $this->db->escape($pdata['comments']);
        }
        
        // $description .= ' | ';
        
        // $data['notes_description'] = $keywordData2['keyword_name'].' | '.
        // $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
        // '.$medicationf . $comments;
        
        $data['notes_description'] = $description;
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
        
        // var_dump($data);
        
        // die;
		
		
        
        $this->model_notes_notes->updatetagsmedicinearchive1($fdata['tags_id']);
        
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        $archive_tags_medication_id = $fdata['archive_tags_medication_id'];
        
        $mdata2 = array();
        $mdata2['notes_id'] = $notes_id;
        $mdata2['tags_id'] = $fdata['tags_id'];
        $mdata2['archive_tags_medication_id'] = $archive_tags_medication_id;
        
        $this->model_notes_notes->updatetagsmedicinearchive2($mdata2);
        return $notes_id;
    }

    public function allrolecallsign ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
				$timezone_name = $fdata['facilitytimezone'];
			}
			
		}else{
			$facilities_id = $fdata['facilities_id']; 
			$timezone_name = $fdata['facilitytimezone'];
		}
		
        
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
        
        $tagname = "";
        
        // var_dump($this->session->data['tagsids']);
        // var_dump($this->session->data['role_calls']);
        
        /*
         * if(empty($this->session->data['tagsids'])){
         * $girl1 = 0;
         * $boy1 = 0;
         * $nboy1 = 0;
         * $total1 = 0;
         *
         * $outtags = array();
         *
         * //var_dump($this->session->data['role_calls']);
         * //echo "<hr>";
         * $tagname111 = array();
         * foreach($this->session->data['role_calls'] as $key=>$rolecall){
         * $outtags[$key] = $rolecall['role_call'];
         * $tag_info = $this->model_setting_tags->getTag($key);
         *
         * $this->model_resident_resident->updatetagrolecall($key, '1');
         *
         * $emp_tag_id = $tag_info['emp_tag_id'];
         * $tags_id = $tag_info['tags_id'];
         *
         * $tagname .=
         * $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
         *
         * }
         *
         * //$tagname .= implode(", ",$tagname111);
         *
         * $outtags2 = $this->model_setting_tags->getrolecallby($outtags,
         * $this->customer->getId());
         *
         * if($outtags2){
         * foreach($outtags2 as $outtag){
         * $this->model_resident_resident->updatetagrolecall($outtag['tags_id'],
         * '2');
         * }
         * }
         *
         *
         *
         * //$data['emp_tag_id'] = $emp_tag_id;
         * //$data['tags_id'] = $tags_id;
         *
         * $data['keyword_file'] = HEADCOUNT_ICON;
         *
         * $this->load->model('setting/keywords');
         * $keywordData2 =
         * $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
         *
         *
         * if($this->request->post['comments'] != null &&
         * $this->request->post['comments']){
         * $comments = ' | '.$this->request->post['comments'];
         * }
         *
         *
         *
         * if($this->request->post['customlistvalues_ids']){
         *
         * $this->load->model('notes/notes');
         *
         * foreach($this->request->post['customlistvalues_ids'] as
         * $customlistvalues_id){
         *
         * $custom_info =
         * $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
         *
         * $customlistvalues_name = $custom_info['customlistvalues_name'];
         *
         * $description1 .= ' | '.$customlistvalues_name;
         *
         * }
         *
         * $data['customlistvalues_ids'] =
         * $this->request->post['customlistvalues_ids'];
         *
         * }
         *
         *
         * $this->load->model('facilities/facilities');
         * $facilityinfo =
         * $this->model_facilities_facilities->getfacilities($this->customer->getId());
         * $this->load->model('notes/notes');
         *
         * if($facilityinfo['config_tags_customlist_id'] !=NULL &&
         * $facilityinfo['config_tags_customlist_id'] !=""){
         * $d2 = array();
         * $d2['customlistvalueids'] =
         * $facilityinfo['config_tags_customlist_id'];
         * $customlistvalues =
         * $this->model_notes_notes->getcustomlistvalues($d2);
         * if($customlistvalues){
         *
         * foreach($customlistvalues as $customlistvalue){
         *
         * $customlistvalues_total =
         * $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'],
         * $customlistvalue['gender'] ,'1', $this->customer->getId());
         *
         * if($customlistvalues_total > 0 ){
         * $total1 = $total1 + $customlistvalues_total;
         * $boygirl .= $customlistvalues_total .'
         * '.$customlistvalue['customlistvalues_name'].' ';
         *
         * $boygirl .= 'and ';
         * }
         *
         * }
         * }
         * }
         *
         * $boygirl .= $total1.' Total ';
         *
         *
         * $intag = $tagname .' | '.$boygirl.' are IN the facility';
         *
         *
         * $outtags2 = $this->model_setting_tags->getrolecallby($outtags,
         * $this->customer->getId());
         *
         * //var_dump($outtags2);
         *
         * $girl12 = 0;
         * $boy12 = 0;
         * $tagname211 = array();
         * if($outtags2){
         *
         * foreach($outtags2 as $outtag){
         * $tag_info = $this->model_setting_tags->getTag($outtag['tags_id']);
         *
         * $emp_tag_id = $tag_info['emp_tag_id'];
         * $tags_id = $tag_info['tags_id'];
         *
         * $tagname2 .=
         * $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
         *
         * //var_dump($tag_info['gender']);
         *
         *
         * }
         * //$tagname2 .= implode(", ",$tagname211);
         *
         *
         * $total12 = 0;
         * if($facilityinfo['config_tags_customlist_id'] !=NULL &&
         * $facilityinfo['config_tags_customlist_id'] !=""){
         * $d2 = array();
         * $d2['customlistvalueids'] =
         * $facilityinfo['config_tags_customlist_id'];
         * $customlistvalues =
         * $this->model_notes_notes->getcustomlistvalues($d2);
         * if($customlistvalues){
         *
         * foreach($customlistvalues as $customlistvalue){
         *
         * $customlistvalues_total =
         * $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'],
         * $customlistvalue['gender'] ,'2', $this->customer->getId());
         *
         * if($customlistvalues_total > 0 ){
         * $total12 = $total12 + $customlistvalues_total;
         * $boygirl2 .= $customlistvalues_total .'
         * '.$customlistvalue['customlistvalues_name'].' ';
         *
         * $boygirl2 .= 'and ';
         * }
         *
         * }
         * }
         * }
         *
         * $boygirl2 .= $total12.' Total ';
         *
         *
         * $outtag = ' | '. $tagname2.$boygirl2.' Clients are OUT of the
         * facility';
         * }
         *
         * $tag_content = $intag .' '. $outtag;
         *
         *
         * $fdataa = array();
         * $fdataa['is_monitor_time'] = '1';
         * $fdataa['facilities_id'] = $this->customer->getId();
         * $fdataa['date_added'] = date('Y-m-d', strtotime('now'));
         *
         * $signnotes_infos =
         * $this->model_notes_notes->getNotebyactivenotes($fdataa);
         *
         * $sign_users = "";
         * $sign_users1 = array();
         * if($signnotes_infos != null && $signnotes_infos != ""){
         * $sign_users .= " | STAFF ";
         * foreach($signnotes_infos as $signnotes_info){
         * $sign_users .= $signnotes_info['user_id'].',';
         * }
         *
         * //$sign_users .= implode(", ",$sign_users1);
         * }
         *
         * $data['notes_description'] = $keywordData2['keyword_name'].' | ' .
         * $tag_content .$description1. $comments .$sign_users ;
         *
         * }else{
         */
        
        // var_dump($this->session->data['tagsids']);
        // echo "<hr>";
        // var_dump($this->session->data['role_calls']);
		
		$afacilities = array();
		foreach($fdata['tagsids'] as $key1 => $tagsid){
			$tag_info = $this->model_setting_tags->getTag($key1);
			$afacilities[] = array(
				'tags_id'=>$key1,
				'role_call'=>$tagsid,
				'facilities_id'=>$tag_info['facilities_id'],
			);
			
		}
		
		$role_calltagsids = $this->groupArray($afacilities, "facilities_id", false, true);
		$abc = array();
			$tagnamesss = "";
			
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ($facilities_id);
			/*if($facilities_info['no_distribution'] == '1'){
				foreach ($role_calltagsids as $rolecalls) {
			
					$tagname = "";
					$tagname2 = "";
					$tagnamesss_out = "";
					foreach($rolecalls as $rolecall){
						foreach ($fdata['role_calls'] as $key => $role) {
							if ($rolecall['tags_id'] == $key) {
								
								$abc[] = $key;
								$tag_info = $this->model_setting_tags->getTag($key);
								$emp_tag_id = $tag_info['emp_tag_id'];
								$tags_id = $tag_info['tags_id'];
								//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
								
								$this->load->model('setting/locations');
								$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
								
								$tagname .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
								
								$tagnamesss = 1;
								
								$this->model_resident_resident->updatetagrolecall($key, '1');
							}
						}
						
						if (! in_array($rolecall['tags_id'], $abc)) {
							// var_dump($tags_id);
							$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							//$tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model('setting/locations');
							$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
							
							$tagname2 .= $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							
							// var_dump($tag_info['role_call']);
							if ($rolecall['role_call'] == $tag_info['role_call']) {
								$tagnamesss_out = 1;
							} else {
								$tagnamesss_out = 2;
							}
							
							$this->model_resident_resident->updatetagrolecall($tags_id, '2');
						}
					}
				
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if (! empty ( $customer_info ['setting_data'])) {
					$customers = unserialize($customer_info ['setting_data']);
					
					if($customers['in_name'] != null && $customers['in_name'] != ""){
						$in_name = $customers['in_name'].' ';
					}else{
						$in_name = ' returned to the Cell ';
					}
					
					if($customers['out_name'] != null && $customers['out_name'] != ""){
						$out_name = $customers['out_name'].' ';
					}else{
						$out_name = ' left the Cell ';
					}
					
				}else{
					$in_name = ' returned to the Cell ';
					$out_name = ' left the Cell ';
				}
				
				$inname = "";
				if ($tagnamesss == 1) {
					if ($tagname != null && $tagname != "") {
						$inname = $tagname . $in_name;
					}
				} else {
					if ($tagname != null && $tagname != "") {
						$inname = $tagname;
					}
				}
				
				$outname = "";
			
				if ($tagnamesss_out == 1) {
					if ($tagname2 != null && $tagname2 != "") {
						$outname = ' | ' . $tagname2 . $out_name;
					}
				} else {
					if ($tagname2 != null && $tagname2 != "") {
						$outname = $tagname2;
					}
				}

				if ($pdata['new_module']) {
					$description1 = "";
					$this->load->model('notes/notes');
					
					foreach ($pdata['new_module'] as $customlistvalues_id) {

						if($customlistvalues_id['checkin']=="1"){

							$description1 .= ' | ' . $customlistvalues_id['customlistvalues_name'];

						}
						
						
					}
					
					$data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
				}
				
				if ($pdata['comments'] != null && $pdata['comments']) {
					$comments = ' | ' . $pdata['comments'];
				}
				
				
				$data['notes_description'] = $inname . $outname . $description1 . $comments;
				
				$data['date_added'] = $date_added;
				$data['note_date'] = $date_added;
				$data['notetime'] = $notetime;
				
				$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
			
			}
				
			}*/
			
			
			foreach ($role_calltagsids as $facilities_id1 => $rolecalls) {
			
				$tagname = "";
				$tagname2 = "";
				$tagnamesss_out = "";
				$tags_id_list = array();
				foreach($rolecalls as $rolecall){
					foreach ($fdata['role_calls'] as $key => $role) {
						if ($rolecall['tags_id'] == $key) {
							
							$abc[] = $key;
							$tag_info = $this->model_setting_tags->getTag($key);
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							$tags_id_list[] = $tag_info['tags_id'];
							//$data ['tags_id_list1'] = $tag_info['tags_id'];
							//$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
							
							$this->load->model('setting/locations');
							$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
							$this->load->model('api/permision');
							$clientinfo = $this->model_api_permision->getclientinfo($facilities_id1, $tag_info);
							$cname = $clientinfo['name'];
							$tagname .= $cname;//$tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
							
							$tagnamesss = 1;
							
							$this->model_resident_resident->updatetagrolecall($key, '1');
						}
					}
					
					if (! in_array($rolecall['tags_id'], $abc)) {
						// var_dump($tags_id);
						$tag_info = $this->model_setting_tags->getTag($rolecall['tags_id']);
						$emp_tag_id = $tag_info['emp_tag_id'];
						$tags_id = $tag_info['tags_id'];
						//$data ['tags_id_list1'] = $tag_info['tags_id'];
						$tags_id_list[] = $tag_info['tags_id'];
						//$tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						$this->load->model('setting/locations');
						$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
						$this->load->model('api/permision');
						$clientinfo = $this->model_api_permision->getclientinfo($facilities_id1, $tag_info);
						$cname = $clientinfo['name'];
						$tagname2 .= $cname;//$tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
						
						// var_dump($tag_info['role_call']);
						if ($rolecall['role_call'] == $tag_info['role_call']) {
							$tagnamesss_out = 1;
						} else {
							$tagnamesss_out = 2;
						}
						
						$this->model_resident_resident->updatetagrolecall($tags_id, '2');
					}
				}
			
			
				/*if($rolecall['role_call'] == '1'){
					if($rolecall['role_call'] == $tag_info['role_call']){
						$tagnamesss = 1;
					}else{
						$tagnamesss = 2;
					}
					
					$emp_tag_id = $tag_info['emp_tag_id'];
					$tags_id = $tag_info['tags_id'];
					$tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
					$this->model_resident_resident->updatetagrolecall($rolecall['tags_id'], $rolecall['role_call']);
				}
			
				if($rolecall['role_call'] == '2'){
					
					$emp_tag_id = $tag_info['emp_tag_id'];
					$tags_id = $tag_info['tags_id'];
					$tagname2 .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].' | ';
					
					if($rolecall['role_call'] == $tag_info['role_call']){
						$tagnamesss_out = 1;
					}else{
						$tagnamesss_out = 2;
					}
					
					$this->model_resident_resident->updatetagrolecall($rolecall['tags_id'], $rolecall['role_call']);
				}*/
			
	
			/*foreach ($fdata['tagsids'] as $key1 => $tagsid) {
				
				foreach ($fdata['role_calls'] as $key => $rolecall) {
					if ($key1 == $key) {
						
						$abc[] = $key;
						$tag_info = $this->model_setting_tags->getTag($key);
						$emp_tag_id = $tag_info['emp_tag_id'];
						$tags_id = $tag_info['tags_id'];
						$tagname .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
						
						// var_dump($tag_info['role_call']);
						if ($tagsid == $tag_info['role_call']) {
							$tagnamesss = 1;
						} else {
							$tagnamesss = 2;
						}
						
						$this->model_resident_resident->updatetagrolecall($key, '1');
					}
				}
				
				if (! in_array($key1, $abc)) {
					// var_dump($tags_id);
					$tag_info = $this->model_setting_tags->getTag($key1);
					$emp_tag_id = $tag_info['emp_tag_id'];
					$tags_id = $tag_info['tags_id'];
					$tagname2 .= $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . ' | ';
					
					// var_dump($tag_info['role_call']);
					if ($tagsid == $tag_info['role_call']) {
						$tagnamesss_out = 1;
					} else {
						$tagnamesss_out = 2;
					}
					
					$this->model_resident_resident->updatetagrolecall($tags_id, '2');
				}
			}*/
			
			// var_dump($tagnamesss);
			
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id1 );
			$unique_id = $facility ['customer_key'];
			
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			if (! empty ( $customer_info ['setting_data'])) {
				$customers = unserialize($customer_info ['setting_data']);
				
				if($customers['in_name'] != null && $customers['in_name'] != ""){
					$in_name = $customers['in_name'].' ';
				}else{
					$in_name = ' returned to the Cell ';
				}
				
				if($customers['out_name'] != null && $customers['out_name'] != ""){
					$out_name = $customers['out_name'].' ';
				}else{
					$out_name = ' left the Cell ';
				}
				
			}else{
				$in_name = ' returned to the Cell ';
				$out_name = ' left the Cell ';
			}
			
			$inname = "";
			if ($tagnamesss == 1) {
				if ($tagname != null && $tagname != "") {
					$inname = $tagname . $in_name;
				}
			} else {
				if ($tagname != null && $tagname != "") {
					$inname = $tagname;
				}
			}
			
			$outname = "";
		
			if ($tagnamesss_out == 1) {
				if ($tagname2 != null && $tagname2 != "") {
					$outname = ' | ' . $tagname2 . $out_name;
				}
			} else {
				if ($tagname2 != null && $tagname2 != "") {
					$outname = $tagname2;
				}
			}

			if ($pdata['new_module']) {
				$description1 = "";
				$this->load->model('notes/notes');
				
				foreach ($pdata['new_module'] as $customlistvalues_id) {

					if($customlistvalues_id['checkin']=="1"){

						$description1 .= ' | ' . $customlistvalues_id['customlistvalues_name'];

					}
					
					////$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
					
				   // $customlistvalues_name = $custom_info['customlistvalues_name'];
					
					
				}
				
				$data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
			}
			
			/*if ($pdata['customlistvalues_ids']) {
				
				$this->load->model('notes/notes');
				
				foreach ($pdata['customlistvalues_ids'] as $customlistvalues_id) {
					
					$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
					
					$customlistvalues_name = $custom_info['customlistvalues_name'];
					
					$description1 .= ' | ' . $customlistvalues_name;
				}
				
				$data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
			}*/
			
			if ($pdata['comments'] != null && $pdata['comments']) {
				$comments = ' | ' . $pdata['comments'];
			}
			
			
			$data['notes_description'] = $inname . $outname . $description1 . $comments;
			$data ['tags_id_list'] = $tags_id_list;
			$data['date_added'] = $date_added;
			$data['note_date'] = $date_added;
			$data['notetime'] = $notetime;
			//echo "<hr>";
			//var_dump($data);
			//die;
			if($facilities_id1 != null && $facilities_id1 != ""){
				$facilities_id2 = $facilities_id1;
			}else{
				$facilities_id2 = $facilities_id;
			}
			
			//var_dump($data);
			//var_dump($facilities_id2);
			//echo "<hr>";
			
			$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id2);
		
		}
		
		
        return $notes_id;
    }

    public function activenote ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id'];
			$timezone_name = $fdata['facilitytimezone'];	
			}
		}else{
			$facilities_id = $fdata['facilities_id'];
			$timezone_name = $fdata['facilitytimezone'];			
		}
		
        
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
		
		$this->load->model('setting/locations');
		$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
		$this->load->model('api/permision');
		$clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
		$cname = $clientinfo['name'];
		$tagname = $cname;//$tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
        
        $this->load->model('setting/keywords');
        $keywordData2 = $this->model_setting_keywords->getkeywordDetail($fdata['keyword_id']);
        
        $data['keyword_file'] = $keywordData2['keyword_image'];
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $comments = ' | ' . $pdata['comments'];
        }
        
        //$data['notes_description'] = $keywordData2['keyword_name'] . ' | ' . $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'] . '' . $comments;
        $data['notes_description'] = $keywordData2['keyword_name'] . ' | ' . $tagname . '' . $comments;
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
		
		
        
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        return $notes_id;
    }

    public function rolecallsign ($pdata, $fdata){

        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
			}
			
		}else{
			$facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
		}
		
       
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
        
        if ($fdata['discharge'] == "1") {
            $data['keyword_file'] = DISCHARGE_ICON;
            
            $this->load->model('setting/keywords');
            $keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file'],$facilities_id);
        }
        
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$unique_id = $facility ['customer_key'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		if (! empty ( $customer_info ['setting_data'])) {
			$customers = unserialize($customer_info ['setting_data']);
			
			if($fdata['role_call'] == '1'){
				if($customers['in_name'] != null && $customers['in_name'] != ""){
					$roleCall = $customers['in_name'];
				}else{
					$roleCall = ' returned to the Cell ';
				}
			}
			
			if($fdata['role_call'] == '2'){
				if($customers['out_name'] != null && $customers['out_name'] != ""){
					$roleCall = $customers['out_name'];
				}else{
					$roleCall = ' left the Cell ';
				}
			}
			
		}else{
			if($fdata['role_call'] == '1'){
				$roleCall = ' returned to the Cell ';
			}
			
			if($fdata['role_call'] == '2'){
				$roleCall = ' left the Cell ';
			}
		}
		
        /*if ($fdata['role_call'] == '1') {
            $roleCall = "returned to ";
        }
        
        if ($fdata['role_call'] == '2') {
            $roleCall = "left ";
        }*/
        
        if ($fdata['discharge'] == "1") {
            $roleCall = "Discharged to";
        }
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $comments = ' | ' . $pdata['comments'];
        }
        
        if ($pdata['new_module']) {
            
            $this->load->model('notes/notes');
            
            foreach ($pdata['new_module'] as $customlistvalues_id) {

                if($customlistvalues_id['checkin']=="1"){

                    $description1 .= ' | ' . $customlistvalues_id['customlistvalues_name'];

                }
                
                ////$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
                
               // $customlistvalues_name = $custom_info['customlistvalues_name'];
                
                
            }
            
            $data['customlistvalues_ids'] = $pdata['customlistvalues_ids'];
        }
		
        if ($fdata['discharge'] == "1") {
            $this->load->model('api/permision');
			$clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
			$cname = $clientinfo['name'];
            $data['notes_description'] = $keywordData2['keyword_name'] . ' | ' . $cname . '' . $description1 . $comments;
            
            $this->load->model('createtask/createtask');
            $alldatas = $this->model_createtask_createtask->getalltaskbyid($fdata['tags_id']);
            
            if ($alldatas != NULL && $alldatas != "") {
                foreach ($alldatas as $alldata) {
                    $result = $this->model_createtask_createtask->getStrikedatadetails($alldata['id']);
                    $taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists($result, $facilities_id, '1');
                    $this->model_createtask_createtask->updatetaskStrike($alldata['id']);
                    $this->model_createtask_createtask->deteteIncomTask($facilities_id);
                }
            }
        } else {
			
			 $this->load->model('api/permision');
			$clientinfo = $this->model_api_permision->getclientinfo($facilities_id, $tag_info);
			$cname = $clientinfo['name'];
			
            $data['notes_description'] = $cname . ' | ' . $form_name . ' has ' . $roleCall  . $description1 . $comments;
        }
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
        
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        if ($fdata['discharge'] == "1") {
            $this->load->model('setting/tags');
            $this->model_setting_tags->addcurrentTagarchive($fdata['tags_id']);
            $this->model_setting_tags->updatecurrentTagarchive($fdata['tags_id'], $notes_id);
            
            $this->model_resident_resident->updateDischargeTag($fdata['tags_id'], $date_added);
        } else {
            $this->model_resident_resident->updatetagrolecall($fdata['tags_id'], $fdata['role_call']);
        }
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        return $notes_id;
    }

    public function residentstatussign ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
			$facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];	
			}
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];
		}
		
       
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
        
        $data['notetime'] = $notetime;
        $data['note_date'] = $date_added;
        $data['facilitytimezone'] = $timezone_name;
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $comments = ' | ' . $pdata['comments'];
        }
        
        $tagstatus = array();
        
        $currentdate = date('Y-m-d');
        
        $data2 = array(
                'currentdate' => $currentdate,
                'tags_id' => $fdata['tags_id']
        );
        
        $this->load->model('resident/resident');
        $task_infos = $this->model_resident_resident->getResidentstatus($data2);
        
        $totaltask_infos = $this->model_resident_resident->getTotalResidentstatus($data2);
        
        foreach ($task_infos as $taskinfo) {
            $tagstatus_info = $this->model_resident_resident->getTagstatusbyId($taskinfo['tagstatus_id']);
            $tagstatus[] = array(
                    'task_id' => $taskinfo['id']
            );
        }
        
        $this->load->model('form/form');
        $form_infos = $this->model_form_form->getformstatus($data2);
        $totalform_infos = $this->model_form_form->gettotalformstatus($data2s);
        
        foreach ($form_infos as $formdata) {
            $tagstatus_info = $this->model_resident_resident->getTagstatusbyId($formdata['tagstatus_id']);
            
            $tagstatus[] = array(
                    'forms_id' => $formdata['forms_id']
            )
            ;
        }
        
        if ($fdata['childstatus'] == 'high') {
            $childstatus = 'High';
        }
        
        if ($fdata['childstatus'] == 'moderate') {
            $childstatus = 'Moderate';
        }
        if ($fdata['childstatus'] == 'normal') {
            $childstatus = 'Normal';
        }
		
        $data['notes_description'] = ' Client Status turned to ' . $childstatus . ' ' . $comments;
        $data['date_added'] = $date_added;
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        if ($tagstatus != NULL && $tagstatus != "") {
            $this->load->model('resident/resident');
            $tagstatus_id = $this->model_resident_resident->addTagstatus($tagstatus, $fdata['childstatus'], $fdata['tags_id'], $notes_id);
        } else {
            $this->load->model('resident/resident');
            $tagstatus_id = $this->model_resident_resident->addTagstatus2($fdata['childstatus'], $fdata['tags_id'], $notes_id);
        }
        
        $this->model_notes_notes->updateclient_status($notes_id);
        
        if ($pdata['emp_tag_id'] != null && $pdata['emp_tag_id'] != "") {
            
            $update_date = date('Y-m-d H:i:s', strtotime('now'));
            $this->model_notes_notes->updateNotesTag($pdata['emp_tag_id'], $notes_id, $pdata['tags_id'], $update_date);
        }
		
		if ($fdata['tags_id'] != null && $fdata['tags_id'] != "") {
			
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
			
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET modify_date = '".$date_added."' where  tags_id = '" . $fdata['tags_id'] . "'";
			$sql = $this->db->query($sql1);
		}
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        return $notes_id;
    }

    public function gettagsFormDatas ($tags_forms_id)
    {
        $query = $this->db->query("SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM " . DB_PREFIX . "tags_forms WHERE tags_forms_id = '" . $tags_forms_id . "' ");
        return $query->row;
    }

    function get_formbynotesid ($notes_id)
    {
        $sql = "SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM `" . DB_PREFIX . "tags_forms` WHERE notes_id = '" . $notes_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function gettagsforms ($tags_id)
    {
        $sql = "SELECT tags_forms_id,tags_id,notes_id,forms_design_id,forms_id,facilities_id,design_forms,form_description,rules_form_description,user_id,signature,notes_pin,form_date_added,notes_type,type,date_added,date_updated,status,form_signature,upload_file,is_discharge FROM " . DB_PREFIX . "tags_forms";
        
        $sql .= " where 1 = 1 and status = '1' and is_discharge = '0' and tags_id = '" . $tags_id . "' ";
        
        $query = $this->db->query($sql);
        
        return $query->rows;
    }

    public function updateDischargeTag ($tags_id, $date_added)
    {
        $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET discharge = '1', discharge_date = '" . $date_added . "', modify_date = '" . $date_added . "' where  tags_id = '" . $tags_id . "'");
        
        $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_medication` SET is_discharge = '1' where  tags_id = '" . $tags_id . "'");
        $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_medication_details` SET is_discharge = '1', is_schedule_medication = '0' where  tags_id = '" . $tags_id . "'");
        
        $sql = $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET is_discharge = '1' where  tags_id = '" . $tags_id . "' ");
        $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tagstatus` SET is_discharge = '1' where  tags_id = '" . $tags_id . "' ");
        // $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_forms` SET
		// is_discharge = '1' where tags_id = '".$tags_id."' and forms_design_id =
		// '".CUSTOME_INTAKEID."' ");
	
		$this->load->model('activity/activity');
		$data['tags_id'] = $tags_id;
		$data['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave('updateDischargeTag', $data, 'query');
    }

    public function updatetagrolecall ($tags_id, $role_call)
    {
		
		$this->load->model('setting/tags');
        $tag_info = $this->model_setting_tags->getTag($tags_id);
		
		$this->load->model('facilities/facilities');
					
		$facilities_info = $this->model_facilities_facilities->getfacilities($tag_info['facilities_id']);
			
		$this->load->model('setting/timezone');
			
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		$facilitytimezone = $timezone_info['timezone_value'];
		$timeZone = date_default_timezone_set($timezone_name);
		$date_added = date('Y-m-d H:i:s', strtotime('now'));
		
        $sql1 = "UPDATE `" . DB_PREFIX . "tags` SET role_call = '" . $role_call . "', modify_date = '".$date_added."' where  tags_id = '" . $tags_id . "'";
        $sql = $this->db->query($sql1);
		
		$this->load->model('activity/activity');
		$data['tags_id'] = $tags_id;
		$data['role_call'] = $role_call;
		$this->model_activity_activity->addActivitySave('updatetagrolecall', $data, 'query');
    }

    public function updatetagcolor ($tags_id, $highliter_id, $text_highliter_div_cl)
    {
        $query = $this->db->query("SELECT color_id FROM `" . DB_PREFIX . "tags_color` WHERE color_id = '" . $text_highliter_div_cl . "'");
        
        if ($query->num_rows > 0) {
            $sql = $this->db->query("UPDATE `" . DB_PREFIX . "tags_color` SET color_id = '#" . $highliter_id . "',tags_id = '" . $tags_id . "',date_updated = NOW()  where text_highliter_div_cl= '" . $text_highliter_div_cl . "' ");
        } else {
            $sql = $this->db->query("INSERT INTO  `" . DB_PREFIX . "tags_color` SET color_id = '#" . $highliter_id . "', tags_id = '" . $tags_id . "', text_highliter_div_cl = '" . $text_highliter_div_cl . "',date_added = NOW(),date_updated = NOW() ");
        }
		
		$this->load->model('activity/activity');
		$data['tags_id'] = $tags_id;
		$data['highliter_id'] = $highliter_id;
		$data['text_highliter_div_cl'] = $text_highliter_div_cl;
		$this->model_activity_activity->addActivitySave('updatetagcolor', $data, 'query');
    }

    public function getagsColors ($tags_id)
    {
        $query = $this->db->query("SELECT tags_color_id,tags_id,color_id,text_highliter_div_cl,date_added,date_updated FROM " . DB_PREFIX . "tags_color WHERE tags_id = '" . $tags_id . "' ");
        return $query->rows;
    }

    public function getFormDatadesign ($forms_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design WHERE forms_id = '" . $forms_id . "' ");
        return $query->row;
    }

    public function gettagmedicine ($tags_id, $is_archive, $notes_id)
    {
        if ($is_archive == '1') {
            $sql = "SELECT archive_tags_medication_id,tags_medication_id,tags_id,medication_fields,is_schedule,is_discharge,is_archive,notes_id FROM " . DB_PREFIX . "archive_tags_medication";
        } else {
            $sql = "SELECT tags_medication_id,tags_id,medication_fields,is_schedule,is_discharge FROM " . DB_PREFIX . "tags_medication";
        }
        // $sql = "SELECT * FROM " . DB_PREFIX . "tags_medication";
        
        $sql .= " where 1 = 1 and status = '1' and is_discharge = '0' and tags_id = '" . $tags_id . "' ";
        
        if ($is_archive == '1') {
            $sql .= " and notes_id = '" . $this->db->escape($notes_id) . "'";
        }
        $query = $this->db->query($sql);
        
        return $query->row;
    }

    public function gettagModule ($tags_id, $is_archive, $notes_id)
    {
        if ($is_archive == '1') {
            $query = $this->db->query(
                    "SELECT archive_tags_medication_details_id,archive_tags_medication_id,tags_medication_details_id	,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime	,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,is_archive,notes_id,drug_mg,drug_alertnate,drug_prn,tags_medication_details_ids,type,type_name,drug_am,drug_pm,image FROM `" .
                             DB_PREFIX . "archive_tags_medication_details` WHERE tags_id = '" . $tags_id . "' and notes_id = '" . $notes_id . "' and is_discharge = '0' and status = '1' ");
        } else {
            $query = $this->db->query(
                    "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,drug_mg,drug_alertnate,drug_prn,tags_medication_details_ids,type,type_name,drug_am,drug_pm,image FROM `" . DB_PREFIX .
                             "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' and status = '1' ");
        }
        
        $new_module = array();
        
        if ($query->num_rows) {
            foreach ($query->rows as $rows) {
                
                $sql = "SELECT tags_medication_details_time_id,start_time,tags_medication_details_id,tags_medication_id,tags_id,create_task FROM `" . DB_PREFIX . "tags_medication_details_time` WHERE tags_medication_details_id = '" . $rows['tags_medication_details_id'] . "'";
                
                $queryrow = $this->db->query($sql);
                
                $dates = array();
                
                foreach ($queryrow->rows as $startdates) {
                    $dates[] = array(
                            'start_time' => date('h:i A', strtotime($startdates['start_time']))
                    );
                }
                
                if ($rows['end_recurrence_date'] != null && $rows['end_recurrence_date'] != "0000-00-00 00:00:00") {
                    $end_recurrence_date = date('m-d-Y', strtotime($rows['end_recurrence_date']));
                }
                
                if ($rows['daily_endtime'] != null && $rows['daily_endtime'] != "19:00:00") {
                    $daily_endtime = date('h:i A', strtotime($rows['daily_endtime']));
                }
                
                if ($rows['daily_times']) {
                    $daily_times = explode(',', $rows['daily_times']);
                } else {
                    $daily_times = array();
                }
                
                if ($rows['recurnce_week']) {
                    $recurnce_week = explode(',', $rows['recurnce_week']);
                }
                
                if ($rows['date_from'] != null && $rows['date_from'] != "0000-00-00") {
                    $date_from = date('m-d-Y', strtotime($rows['date_from']));
                } else {
                    $date_from = date('m-d-Y');
                }
                
                if ($rows['date_to'] != null && $rows['date_to'] != "0000-00-00") {
                    $date_to = date('m-d-Y', strtotime($rows['date_to']));
                } else {
                    $date_to = date('m-d-Y', strtotime("+1 days"));
                }
                
                if ($rows['tags_medication_details_ids']) {
                    $tags_medication_details_ids = explode(',', $rows['tags_medication_details_ids']);
                } else {
                    $tags_medication_details_ids = array();
                }
                
                $new_module['new_module'][] = array(
                        'tags_medication_details_id' => $rows['tags_medication_details_id'],
                        'tags_medication_id' => $rows['tags_medication_id'],
                        'drug_name' => $rows['drug_name'],
                        'drug_mg' => $rows['drug_mg'],
                        'drug_am' => $rows['drug_am'],
                        'drug_pm' => $rows['drug_pm'],
                        'drug_alertnate' => $rows['drug_alertnate'],
                        'drug_prn' => $rows['drug_prn'],
                        'instructions' => $rows['instructions'],
                        'status' => $rows['status'],
                        'image' => $rows['image'],
                        
                        'recurrence' => $rows['recurrence'],
                        'recurnce_hrly_recurnce' => $rows['recurnce_hrly_recurnce'],
                        'end_recurrence_date' => $end_recurrence_date,
                        'daily_endtime' => $daily_endtime,
                        'daily_times' => $daily_times,
                        'recurnce_hrly' => $rows['recurnce_hrly'],
                        'recurnce_week' => $recurnce_week,
                        'recurnce_month' => $rows['recurnce_month'],
                        'recurnce_day' => $rows['recurnce_day'],
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'is_schedule_medication' => $rows['is_schedule_medication'],
                        'type' => $rows['type'],
                        'type_name' => $rows['type_name'],
                        
                        'start_time' => $dates,
                        'tags_medication_details_ids' => $tags_medication_details_ids
                )
                ;
            }
			
			
        }
        return $new_module;
    }

    function addTagsMedication ($data, $tags_id)
    {
		/*$deledeids = array();
		if ($data['new_module']) {
            foreach ($data['new_module'] as $mediactiondata) {
				
				$deledeids[] = $mediactiondata['tags_medication_details_id'];
			}
			var_dump($deledeids);
		}*/
		
		
		$this->db->query("delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' ");
		//$this->db->query("delete FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and status = '0' ");
		
        $query1 = $this->db->query("SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' ");
        
        if ($query1->num_rows > 0) {
            
            $this->db->query(
                    "INSERT INTO `" . DB_PREFIX . "archive_tags_medication` SET tags_medication_id = '" . $this->db->escape($query1->row['tags_medication_id']) . "' , medication_fields = '" . $this->db->escape($query1->row['medication_fields']) . "', is_schedule = '" . $this->db->escape($query1->row['is_schedule']) . "' , status = '1', tags_id = '" . $query1->row['tags_id'] . "', is_archive = '1' ");
            $archive_tags_medication_id = $this->db->getLastId();
            
            $query12 = $this->db->query(
                    "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,tags_medication_details_ids,is_updated,is_schedule_id FROM `" . DB_PREFIX .
                             "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' ");
            
            if ($query12->num_rows > 0) {
                
                foreach ($query12->rows as $mrow) {
                    
                    $this->db->query(
                            "INSERT INTO `" . DB_PREFIX . "archive_tags_medication_details` SET tags_medication_details_id = '" . $this->db->escape($mrow['tags_medication_details_id']) . "', drug_name = '" . $this->db->escape($mrow['drug_name']) . "', drug_mg = '" . $this->db->escape($mrow['drug_mg']) . "', drug_am = '" . $this->db->escape($mrow['drug_am']) . "', drug_pm = '" .
                                     $this->db->escape($mrow['drug_pm']) . "', drug_alertnate = '" . $this->db->escape($mrow['drug_alertnate']) . "', drug_prn = '" . $this->db->escape($mrow['drug_prn']) . "', instructions = '" . $this->db->escape($mrow['instructions']) . "', status = '" . $mrow['status'] . "', tags_id = '" . $mrow['tags_id'] . "', tags_medication_id = '" .
                                     $mrow['tags_medication_id'] . "', recurrence = '" . $this->db->escape($mrow['recurrence']) . "', recurnce_hrly = '" . $this->db->escape($mrow['recurnce_hrly']) . "', end_recurrence_date = '" . $mrow['end_recurrence_date'] . "', recurnce_day = '" . $this->db->escape($mrow['recurnce_day']) . "', recurnce_month = '" . $this->db->escape($mrow['recurnce_month']) .
                                     "', recurnce_week = '" . $this->db->escape($mrow['recurnce_week']) . "', recurnce_hrly_recurnce = '" . $mrow['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $mrow['daily_endtime'] . "', daily_times = '" . $mrow['daily_times'] . "', date_from = '" . $mrow['date_from'] . "', date_to = '" . $mrow['date_to'] . "', is_schedule_medication = '" .
                                     $mrow['is_schedule_medication'] . "', tags_medication_details_ids = '" . $mrow['tags_medication_details_ids'] . "', is_updated = '" . $mrow['is_updated'] . "', type_name = '" . $mrow['type_name'] . "', type = '" . $mrow['type'] . "', image = '" . $mrow['image'] . "', is_schedule_id = '" . $mrow['is_schedule_id'] . "', archive_tags_medication_id = '" . $archive_tags_medication_id . "', is_archive = '1' ");
                }
            }
        }
        
        if ($query1->num_rows > 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', is_schedule = '" . $this->db->escape($data['is_schedule']) . "', status = '1' where tags_id = '" . $tags_id . "' ");
            
            $tags_medication_id = $query1->row['tags_medication_id'];
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', is_schedule = '" . $this->db->escape($data['is_schedule']) . "' , status = '1', tags_id = '" . $tags_id . "'");
            
            $tags_medication_id = $this->db->getLastId();
        }
        
        /*
         * if(empty($this->request->post['medication'])){
         * $this->db->query("DELETE FROM `" . DB_PREFIX .
         * "tags_medication_details` WHERE tags_id = '" . (int)$tags_id . "' and
         * is_discharge = '0' ");
         *
         * $this->db->query("DELETE FROM `" . DB_PREFIX .
         * "tags_medication_details_time` WHERE tags_id = '" . (int)$tags_id .
         * "' ");
         * }
         */
        
        if ($data['new_module']) {
            foreach ($data['new_module'] as $mediactiondata) {
                
                //$drug_am = date('H:i:s', strtotime($mediactiondata['drug_am']));
                //$drug_pm = date('H:i:s', strtotime($mediactiondata['drug_pm']));
				
				$drug_am = $mediactiondata['drug_am'];
                $drug_pm = $mediactiondata['drug_pm'];
                
                $date1 = str_replace('-', '/', $mediactiondata['end_recurrence_date']);
                $res1 = explode("/", $date1);
                $dateRange1 = $res1[2] . "-" . $res1[0] . "-" . $res1[1];
                
                $time1 = date('H:i:s');
                $end_recurrence_date = $dateRange1 . ' ' . $time1;
                
                if ($mediactiondata['is_schedule_medication'] == '1') {
                    $date21 = str_replace('-', '/', $mediactiondata['date_from']);
                    $res12 = explode("/", $date21);
                    $date_from = $res12[2] . "-" . $res12[0] . "-" . $res12[1];
                    
                    $date21q = str_replace('-', '/', $mediactiondata['date_to']);
                    $res122 = explode("/", $date21q);
                    $date_to = $res122[2] . "-" . $res122[0] . "-" . $res122[1];
                    
                    $daily_times = implode(',', $mediactiondata['daily_times']);
                } else {
                    $daily_times = '';
                    $date_from = '';
                    $date_to = '';
                }
                
                $recurnce_week = implode(',', $mediactiondata['recurnce_week']);
                
                $daily_endtime = date('H:i:s', strtotime($mediactiondata['daily_endtime']));
                
                $tags_medication_details_ids = implode(',', $mediactiondata['tags_medication_details_ids']);
                
                $sssql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,is_updated,is_schedule_id,tags_medication_details_ids FROM `" . DB_PREFIX .
                         "tags_medication_details` WHERE tags_medication_details_id = '" . $mediactiondata['tags_medication_details_id'] . "'";
                $query = $this->db->query($sssql);
                
                if ($query->num_rows > 0) {
                    
                    $inids = $mediactiondata['tags_medication_details_ids'];
                    
                    if ($query->row['tags_medication_details_ids'] != null && $query->row['tags_medication_details_ids'] != "") {
                        
                        $dbids = explode(",", $query->row['tags_medication_details_ids']);
                    } else {
                        $dbids = array();
                    }
                    $c1 = count($inids);
                    $c2 = count($dbids);
                    
                    $ssss = array();
                    $sssa1a = array();
                    $sssa1a[] = $mediactiondata['tags_medication_details_id'];
                    
                    if ($mediactiondata['tags_medication_details_ids'] != null && $mediactiondata['tags_medication_details_ids'] != "") {
                        $tags_medication_details_ids222 = $mediactiondata['tags_medication_details_ids'];
                    } else {
                        $tags_medication_details_ids222 = array();
                    }
                    
                    $arrr_mss = array();
                    $arrr_mss = array_merge($sssa1a, $tags_medication_details_ids222);
                    
                    $ssss = array_unique($arrr_mss);
                    
                    // var_dump($ssss);
                    // echo "<hr>";
                    
                    $ssss_ids = implode(',', $ssss);
                    
                    $this->load->model('setting/tags');
                    $this->load->model('setting/timezone');
                    $this->load->model('facilities/facilities');
                    $tag_info = $this->model_setting_tags->getTag($tags_id);
                    $facilities_info = $this->model_facilities_facilities->getfacilities($tag_info['facilities_id']);
                    
                    $timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
                    
                    date_default_timezone_set($timezone_info['timezone_value']);
                    $current_date = date('Y-m-d', strtotime('now'));
                    
                    if ($c1 == $c2) {
                        if ($current_date >= $date_to) {
                            $is_updated = "0";
                            $create_task = "1";
                        } else {
                            $is_updated = "1";
                            $create_task = "0";
                        }
                    } else {
                        if ($mediactiondata['is_schedule_medication'] == '1') {
                            
                            $sqlt2 = "SELECT count(*) as total from " . DB_PREFIX . "createtask_medications where tags_medication_details_id in (" . $ssss_ids . ") and tags_id = '" . $tags_id . "' ";
                            $qt2 = $this->db->query($sqlt2);
                            
                            if ($qt2->row['total'] > 0) {
                                $is_updated = "1";
                                $create_task = "0";
                            } else {
                                
                                if ($current_date >= $date_to) {
                                    $is_updated = "0";
                                    $create_task = "1";
                                } else {
                                    $is_updated = "1";
                                    $create_task = "0";
                                }
                            }
                        } else {
                            $is_updated = "0";
                            $create_task = "1";
                        }
                    }
					
					//var_dump($create_task);
					
                    $this->db->query(
                            "UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($mediactiondata['drug_name']) . "', drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) .
                                     "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape($mediactiondata['recurrence']) . "', recurnce_hrly = '" .
                                     $this->db->escape($mediactiondata['recurnce_hrly']) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape($recurnce_day) . "', recurnce_month = '" . $this->db->escape($recurnce_month) . "', recurnce_week = '" . $this->db->escape($recurnce_week) . "', recurnce_hrly_recurnce = '" .
                                     $mediactiondata['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata['is_schedule_medication'] . "', type = '" . $mediactiondata['type'] . "', type_name = '" . $mediactiondata['type_name'] . "', image = '" . $mediactiondata['image'] . "', is_updated = '" . $is_updated . "',create_task = '" . $create_task .
                                     "', tags_medication_details_ids = '" . $tags_medication_details_ids . "' where tags_medication_details_id = '" . $mediactiondata['tags_medication_details_id'] . "' ");
                    
                    if ($mediactiondata['start_time']) {
                        foreach ($mediactiondata['start_time'] as $time) {
                            
                            $tasksTiming = date('H:i:s', strtotime($time));
                            
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape($tasksTiming) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' ");
                        }
                    }
                } else {
                    
                    $this->db->query(
                            "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($mediactiondata['drug_name']) . "', drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) .
                                     "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '0', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape($mediactiondata['recurrence']) . "', recurnce_hrly = '" .
                                     $this->db->escape($mediactiondata['recurnce_hrly']) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape($recurnce_day) . "', recurnce_month = '" . $this->db->escape($recurnce_month) . "', recurnce_week = '" . $this->db->escape($recurnce_week) . "', recurnce_hrly_recurnce = '" .
                                     $mediactiondata['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata['is_schedule_medication'] . "', type = '" . $mediactiondata['type'] . "', type_name = '" . $mediactiondata['type_name'] . "', image = '" . $mediactiondata['image'] . "', is_updated = '" . $mediactiondata['is_updated'] . "', tags_medication_details_ids = '" .
                                     $tags_medication_details_ids . "' ");
                    
                    $tags_medication_details_id = $this->db->getLastId();
                    
                    if ($mediactiondata['start_time']) {
                        foreach ($mediactiondata['start_time'] as $time) {
                            
                            $tasksTiming = date('H:i:s', strtotime($time));
                            
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape($tasksTiming) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' ");
                        }
                    }
                }
            }
        }
        
        if ($data['drug_name']) {
            
            //$drug_am = date('H:i:s', strtotime($data['drug_am']));
           // $drug_pm = date('H:i:s', strtotime($data['drug_pm']));
			
			$drug_am = $data['drug_am'];
            $drug_pm = $data['drug_pm'];
            
            $this->db->query(
                    "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($data['drug_name']) . "', drug_mg = '" . $this->db->escape($data['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($data['drug_alertnate']) . "', drug_prn = '" .
                             $this->db->escape($data['drug_prn']) . "', instructions = '" . $this->db->escape($data['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "' ");
            
            $tags_medication_details_id = $this->db->getLastId();
            
            if ($data['new_module2']) {
                foreach ($data['new_module2'] as $mediactiondata) {
                    
                    if ($mediactiondata['start_time']) {
                        foreach ($mediactiondata['start_time'] as $time) {
                            
                            $tasksTiming = date('H:i:s', strtotime($time));
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication_details_time` SET start_time = '" . $this->db->escape($tasksTiming) . "', tags_medication_id = '" . $tags_medication_id . "', tags_medication_details_id = '" . $tags_medication_details_id . "', tags_id = '" . $tags_id . "' ");
                        }
                    }
                }
            }
        }
        
        $query1add = $this->db->query("SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' ");
        
        if ($query1add->num_rows == 1) {
            
            $this->db->query(
                    "INSERT INTO `" . DB_PREFIX . "archive_tags_medication` SET tags_medication_id = '" . $this->db->escape($query1add->row['tags_medication_id']) . "' , medication_fields = '" . $this->db->escape($query1add->row['medication_fields']) . "', is_schedule = '" . $this->db->escape($query1add->row['is_schedule']) . "' , status = '1', tags_id = '" . $query1add->row['tags_id'] .
                             "', is_archive = '1' ");
            $archive_tags_medication_id = $this->db->getLastId();
            
            $query12 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' ");
            
            if ($query12->num_rows > 0) {
                
                foreach ($query12->rows as $mrow) {
                    
                    $this->db->query(
                            "INSERT INTO `" . DB_PREFIX . "archive_tags_medication_details` SET tags_medication_details_id = '" . $this->db->escape($mrow['tags_medication_details_id']) . "', drug_name = '" . $this->db->escape($mrow['drug_name']) . "', drug_mg = '" . $this->db->escape($mrow['drug_mg']) . "', drug_am = '" . $this->db->escape($mrow['drug_am']) . "', drug_pm = '" .
                                     $this->db->escape($mrow['drug_pm']) . "', drug_alertnate = '" . $this->db->escape($mrow['drug_alertnate']) . "', drug_prn = '" . $this->db->escape($mrow['drug_prn']) . "', instructions = '" . $this->db->escape($mrow['instructions']) . "', status = '" . $mrow['status'] . "', tags_id = '" . $mrow['tags_id'] . "', tags_medication_id = '" .
                                     $mrow['tags_medication_id'] . "', recurrence = '" . $this->db->escape($mrow['recurrence']) . "', recurnce_hrly = '" . $this->db->escape($mrow['recurnce_hrly']) . "', end_recurrence_date = '" . $mrow['end_recurrence_date'] . "', recurnce_day = '" . $this->db->escape($mrow['recurnce_day']) . "', recurnce_month = '" . $this->db->escape($mrow['recurnce_month']) .
                                     "', recurnce_week = '" . $this->db->escape($mrow['recurnce_week']) . "', recurnce_hrly_recurnce = '" . $mrow['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $mrow['daily_endtime'] . "', daily_times = '" . $mrow['daily_times'] . "', date_from = '" . $mrow['date_from'] . "', date_to = '" . $mrow['date_to'] . "', is_schedule_medication = '" .
                                     $mrow['is_schedule_medication'] . "', type = '" . $mediactiondata['type'] . "', type_name = '" . $mediactiondata['type_name'] . "', image = '" . $mediactiondata['image'] . "', tags_medication_details_ids = '" . $mrow['tags_medication_details_ids'] . "', is_updated = '" . $mrow['is_updated'] . "', is_schedule_id = '" . $mrow['is_schedule_id'] . "', archive_tags_medication_id = '" . $archive_tags_medication_id . "', is_archive = '1' ");
                }
            }
        }
		
		$this->load->model('activity/activity');
		$data['tags_id'] = $tags_id;
		$this->model_activity_activity->addActivitySave('addTagsMedication', $data, 'query');
        
        return $archive_tags_medication_id;
    }

    function get_medication ($tags_medication_details_id)
    {
        $sql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" .
                 $tags_medication_details_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    function get_medicationyname ($drug_name, $tags_id)
    {
        $sql = "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,instructions,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge FROM `" . DB_PREFIX . "tags_medication_details` WHERE drug_name = '" . $drug_name .
                 "' and tags_id = '" . $tags_id . "' ";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getFormmedia ($tags_forms_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tags_forms_media WHERE tags_forms_id = '" . $tags_forms_id . "' ");
        return $query->rows;
    }

    public function getResidentstatus ($data)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "createtask WHERE emp_tag_id = '" . $data['tags_id'] . "' and recurrence = 'Perpetual' and date_added BETWEEN '" . $data['currentdate'] . " 00:00:00 ' AND '" . $data['currentdate'] . " 23:59:59' ");
        return $query->rows;
    }

    public function getTotalResidentstatus ($data)
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "createtask WHERE emp_tag_id = '" . $data['tags_id'] . "' and recurrence = 'Perpetual' and date_added BETWEEN '" . $data['currentdate'] . " 00:00:00 ' AND '" . $data['currentdate'] . " 23:59:59' ");
        return $query->row['total'];
    }

    public function addTagstatus ($tagstatus, $status, $tags_id, $notes_id)
    {
        $sql = "SELECT tagstatus_id,tags_id,task_id,forms_id,parent_id,notes_id,status,is_discharge FROM `" . DB_PREFIX . "tagstatus` where tags_id = '" . $tags_id . "'";
        $this->db->query($sql);
        
        // if($query->row == null && $query->row == ""){
        
        foreach ($tagstatus as $tstatus) {
            
            $sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $tstatus['task_id'] . "',forms_id = '" . $tstatus['forms_id'] . "',notes_id = '" . $tstatus['notes_id'] . "',  status = '" . $status . "', tags_id = '" . $tags_id . "', parent_id = '" . $notes_id . "' ";
            $this->db->query($sql);
            
            $tagstatus_id = $this->db->getLastId();
            
            if ($tstatus['task_id']) {
                $sql = "UPDATE `" . DB_PREFIX . "createtask` SET tagstatus_id = '" . $tagstatus_id . "' WHERE id = '" . $tstatus['task_id'] . "'";
                $query = $this->db->query($sql);
            }
            
            if ($tstatus['forms_id']) {
                $sql = "UPDATE `" . DB_PREFIX . "forms` SET tagstatus_id = '" . $tagstatus_id . "' WHERE forms_id = '" . $tstatus['forms_id'] . "'";
                $query = $this->db->query($sql);
            }
            
            if ($tstatus['notes_id']) {
                $sql = "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '" . $tagstatus_id . "', notes_conut ='0' WHERE notes_id = '" . $tstatus['notes_id'] . "'";
                $query = $this->db->query($sql);
            }
        }
		
		$this->load->model('activity/activity');
		$datan = array();
		
		$datan['tagstatus'] = $tagstatus;
		$datan['status'] = $status;
		$datan['tags_id'] = $tags_id;
		$datan['notes_id'] = $notes_id;
		$this->model_activity_activity->addActivitySave('addTagstatus', $datan, 'query');
        
        return $tagstatus_id;
    }

    public function addTagstatus2 ($status, $tags_id, $notes_id)
    {
        $sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET task_id = '" . $tstatus['task_id'] . "',forms_id = '" . $tstatus['forms_id'] . "',notes_id = '" . $tstatus['notes_id'] . "',  status = '" . $status . "', tags_id = '" . $tags_id . "', parent_id = '" . $notes_id . "' ";
        $this->db->query($sql);
        
        $tagstatus_id = $this->db->getLastId();
        
        $sql = "UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '" . $tagstatus_id . "', notes_conut ='0' WHERE notes_id = '" . $tstatus['notes_id'] . "'";
        $query = $this->db->query($sql);
		
		$datan = array();
		$datan['status'] = $status;
		$datan['tags_id'] = $tags_id;
		$datan['notes_id'] = $notes_id;
		
		$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('addTagstatus2', $datan, 'query');
        
        return $tagstatus_id;
    }

    public function getTagstatusbyId ($tags_id)
    {
        $sql = "SELECT tagstatus_id,tags_id,task_id,forms_id,parent_id,notes_id,status,is_discharge FROM `" . DB_PREFIX . "tagstatus` where tags_id = '" . $tags_id . "' and is_discharge = '0' order by tagstatus_id DESC limit 0, 1 ";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function addassignteam ($data2, $data)
    {
        $this->load->model('user/user');
        // var_dump($data['userids']);
        
        // die;
        
        // $user_roles = implode(',',$data['user_roles']);
        // $userids = implode(',',$data['userids']);
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($data2['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			}else{
				 $facilities_id = $data2['facilities_id']; 
			}
		}else{
			 $facilities_id = $data2['facilities_id']; 
		}
        
        $query = $this->db->query("SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and facilities_id = '" . $facilities_id . "' ");
        
        if ($query->num_rows > 0) {
            foreach ($query->rows as $mrow) {
                $this->db->query(
                        "INSERT INTO `" . DB_PREFIX . "archive_tags_assign_team` SET tags_assign_team_id = '" . $this->db->escape($mrow['tags_assign_team_id']) . "', tags_id = '" . $this->db->escape($mrow['tags_id']) . "', emp_tag_id = '" . $this->db->escape($mrow['emp_tag_id']) . "', facilities_id = '" . $this->db->escape($mrow['facilities_id']) . "',user_roles = '" .
                                 $this->db->escape($mrow['user_roles']) . "', userids = '" . $this->db->escape($mrow['userids']) . "', date_added = '" . $this->db->escape($mrow['date_added']) . "' , status = '" . $mrow['status'] . "' , is_case = '" . $mrow['is_case'] . "', is_archive = '1', notes_id = '" . $data['notes_id'] . "' ");
                $archive_tags_assign_team_id = $this->db->getLastId();
            }
        }
        
        $query11 = $this->db->query("SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and facilities_id = '" . $facilities_id . "' ");
        
        if ($query11->num_rows == 0) {
            
            // var_dump($query11->num_rows);
            
            foreach ($data['userids'] as $userids) {
                
                $user_info = $this->model_user_user->getUserbyupdate($userids);
                
                $sqla = "INSERT INTO " . DB_PREFIX . "archive_tags_assign_team SET tags_id = '" . $this->db->escape($data2['tags_id']) . "',facilities_id = '" . $this->db->escape($facilities_id) . "',user_roles = '" . $this->db->escape($user_info['user_group_id']) . "', userids = '" . $this->db->escape($userids) . "', date_added = '" . $this->db->escape($data2['date_added']) .
                         "' , status = '1' , is_case = '1', is_archive = '1', notes_id = '" . $data['notes_id'] . "' ";
                
                $this->db->query($sqla);
            }
        }
        
        // die;
        
        $queryu = $this->db->query("DELETE FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and facilities_id = '" . $facilities_id . "' ");
        
        foreach ($data['userids'] as $userids) {
            
            $user_info = $this->model_user_user->getUserbyupdate($userids);
            
            $sql = "INSERT INTO " . DB_PREFIX . "tags_assign_team SET tags_id = '" . $this->db->escape($data2['tags_id']) . "',facilities_id = '" . $this->db->escape($facilities_id) . "',user_roles = '" . $this->db->escape($user_info['user_group_id']) . "', userids = '" . $this->db->escape($userids) . "', date_added = '" . $this->db->escape($data2['date_added']) .
                     "' , status = '1' , is_case = '1' ";
            
            $this->db->query($sql);
            $tags_assign_team_id = $this->db->getLastId();
        }
		
		
		
		$this->db->query("UPDATE `" . DB_PREFIX . "tags` SET modify_date = '".$data2['date_added']."' WHERE tags_id = '" . $this->db->escape($data2['tags_id']) . "'");
		
		$datan = array();
		$datan['data2'] = $data2;
		$datan['data'] = $data;
		
		$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('addassignteam', $datan, 'query');
    }

    public function getassignteam ($data2)
    {
		
			$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($data2['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			}else{
			 $facilities_id = $data2['facilities_id']; 	
			}
		}else{
			 $facilities_id = $data2['facilities_id']; 
		}
		
        if ($data2['is_archive'] == '2') {
            $query = $this->db->query("SELECT archive_tags_assign_team_id,tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case,is_archive,notes_id FROM `" . DB_PREFIX . "archive_tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and notes_id = '" . $data2['notes_id'] . "' and facilities_id = '" . $facilities_id .
                     "' group by user_roles ");
        } else {
            $sql = "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and facilities_id = '" . $facilities_id . "' group by user_roles ";
            $query = $this->db->query($sql);
        }
        
        return $query->rows;
    }

    public function getassignteamUsers ($data2)
    {
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($data2['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			}else{
				$facilities_id = $data2['facilities_id']; 
			}
		}else{
			 $facilities_id = $data2['facilities_id']; 
		}
        if ($data2['is_archive'] == '2') {
            $query = $this->db->query(
                    "SELECT archive_tags_assign_team_id,tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case,is_archive,notes_id FROM `" . DB_PREFIX . "archive_tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and notes_id = '" . $data2['notes_id'] . "' and facilities_id = '" . $facilities_id . "' and user_roles = '" .
                             $data2['user_roles'] . "' ");
        } else {
            $sql = "SELECT tags_assign_team_id,tags_id,emp_tag_id,facilities_id,user_roles,userids,date_added,status,is_case FROM `" . DB_PREFIX . "tags_assign_team` WHERE tags_id = '" . $data2['tags_id'] . "' and facilities_id = '" . $facilities_id . "' and user_roles = '" . $data2['user_roles'] . "' ";
            $query = $this->db->query($sql);
        }
        
        return $query->rows;
    }

    public function tagsassign ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
			}
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
		}
        
        
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
            //$emp_tag_id = $tag_info['emp_tag_id'] . ':' . $tag_info['emp_first_name'];
			$this->load->model('setting/locations');
			$location_info = $this->model_setting_locations->getlocation($tag_info['room']);
			
			$emp_tag_id = $tag_info['emp_last_name'] . ', ' . $tag_info['emp_first_name'] .' | '.$tag_info ['ssn'].' | '.$location_info ['location_name'].' | ';
        } else {
            $emp_tag_id = $tag_info['emp_tag_id'];
        }
        
        if ($tag_info) {
            $medication_tags .= $emp_tag_id . ' ';
        }
        
        $description = '';
        
        $description .= ' Team Assignment Updated. | ';
        $description .= ' ' . $medication_tags;
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $description .= ' | ' . $this->db->escape($pdata['comments']);
        }
        
        $data['notes_description'] = $description;
        
        $data['date_added'] = $date_added;
        $data['note_date'] = $date_added;
        $data['notetime'] = $notetime;
        
        $this->model_notes_notes->updatetagsassign1($fdata['tags_id']);
		
		
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        
        if ($facility['is_enable_add_notes_by'] == '1') {
            $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int) $notes_id . "' ";
            $this->db->query($sql122);
        }
        if ($facility['is_enable_add_notes_by'] == '3') {
            $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int) $notes_id . "' ";
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
        
        $data2 = array();
        $data2['tags_id'] = $fdata['tags_id'];
        $data2['date_added'] = $date_added;
        $data2['facilities_id'] = $facilities_id;
        
        $data3 = array();
        $data3['user_roles'] = explode(',', $fdata['user_roles']);
        $data3['userids'] = explode(',', $fdata['userids']);
        $data3['notes_id'] = $notes_id;
        
        $this->model_resident_resident->addassignteam($data2, $data3);
        
        $this->model_notes_notes->updatetagsassign23($fdata['tags_id'], $notes_id);
        
        return $notes_id;
    }

    public function dailycensus ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];
			}
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			  $timezone_name = $fdata['facilitytimezone'];
		}
       
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
        
        $data['notetime'] = $notetime;
        $data['note_date'] = $date_added;
        $data['facilitytimezone'] = $timezone_name;
        
        $data['notes_description'] = 'Daily Census has been added';
        
        $data['date_added'] = $date_added;
		
		
        
        $notes_id = $this->model_notes_notes->addnotes($data, $facilities_id);
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        
        if ($facility['is_enable_add_notes_by'] == '1') {
            $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int) $notes_id . "' ";
            $this->db->query($sql122);
        }
        if ($facility['is_enable_add_notes_by'] == '3') {
            $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int) $notes_id . "' ";
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
        
        $this->load->model('setting/tags');
        $date_added = date('Y-m-d H:i:s', strtotime('now'));
        $this->model_setting_tags->addCensus($pdata, $notes_id, $date_added, $facilities_id, $timezone_name);
        
        return $notes_id;
    }
	
	
	 public function clientfile ($pdata, $fdata)
    {
        $this->load->model('notes/notes');
        $data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
			 $facilities_id  = $this->session->data['search_facilities_id']; 
			 $facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			}else{
				 $facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
			}
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
		}
		
        
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
        
        $data['notetime'] = $notetime;
        $data['note_date'] = $date_added;
        $data['facilitytimezone'] = $timezone_name;
        
        if ($pdata['comments'] != null && $pdata['comments']) {
            $comments = ' | ' . $pdata['comments'];
        }
        
        
        $data['notes_description'] = ' New File upload  ' . $comments;
        $data['date_added'] = $date_added;
		
		
		
        $notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
		
		$formData = array();
		$formData['media_user_id'] = $pdata['user_id'];
		if ($pdata['imgOutput']) {
            $formData['media_signature'] = $pdata['imgOutput'];
        } else {
            $formData['media_signature'] = $pdata['signature'];
        }
		$formData['media_pin'] = $pdata['notes_pin'];
		$formData['facilities_id'] = $facilities_id;
		
		$formData['noteDate'] = $date_added;
		
		$this->model_notes_notes->updateNoteFile($notes_id, $fdata['notes_file'], $fdata['extention'], $formData);
        
      
		$this->db->query("UPDATE `" . DB_PREFIX . "tags` SET modify_date = '".$date_added."' WHERE tags_id = '" . $this->db->escape($fdata['tags_id']) . "'");
        
        if ($pdata['emp_tag_id'] != null && $pdata['emp_tag_id'] != "") {
            
            $update_date = date('Y-m-d H:i:s', strtotime('now'));
            $this->model_notes_notes->updateNotesTag($pdata['emp_tag_id'], $notes_id, $pdata['tags_id'], $update_date);
        }
        
        $this->load->model('facilities/facilities');
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        	
        
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
        
        return $notes_id;
    }
	
	public function addformmedicine($mediactiondata, $tags_id){
		
		$query2 = $this->db->query("SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' ");
		
		if ($query2->num_rows > 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', is_schedule = '" . $this->db->escape($data['is_schedule']) . "', status = '1' where tags_id = '" . $tags_id . "' and is_discharge = '0' ");
            
            $tags_medication_id = $query2->row['tags_medication_id'];
        } else {
		
			$this->db->query("INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape(serialize($data['medication_fields'])) . "', is_schedule = '" . $this->db->escape($data['is_schedule']) . "' , status = '1', tags_id = '" . $tags_id . "'");
					
			$tags_medication_id = $this->db->getLastId();
		
		}
		
		$query1 = $this->db->query("SELECT tags_medication_details_id FROM `" . DB_PREFIX . "tags_medication_details` WHERE drug_name = '" . $mediactiondata['drug_name'] . "' and tags_id = '" . $tags_id . "' ");
		
		if ($query1->num_rows > 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) .  "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape($mediactiondata['recurrence']) . "', recurnce_hrly = '" .
		   $this->db->escape($mediactiondata['recurnce_hrly']) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape($recurnce_day) . "', recurnce_month = '" . $this->db->escape($recurnce_month) . "', recurnce_week = '" . $this->db->escape($recurnce_week) . "', recurnce_hrly_recurnce = '" .
			 $mediactiondata['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata['is_schedule_medication'] . "', is_updated = '" . $mediactiondata['is_updated'] . "', tags_medication_details_ids = '" .
			 $tags_medication_details_ids . "' where tags_medication_details_id = '" . $query1->row['tags_medication_details_id'] . "' ");
            
            $tags_medication_details_id = $query1->row['tags_medication_details_id'];
        } else {
				
			$sql =   "INSERT INTO `" . DB_PREFIX . "tags_medication_details` SET drug_name = '" . $this->db->escape($mediactiondata['drug_name']) . "', drug_mg = '" . $this->db->escape($mediactiondata['drug_mg']) . "', drug_am = '" . $this->db->escape($drug_am) . "', drug_pm = '" . $this->db->escape($drug_pm) . "', drug_alertnate = '" . $this->db->escape($mediactiondata['drug_alertnate']) .  "', drug_prn = '" . $this->db->escape($mediactiondata['drug_prn']) . "', instructions = '" . $this->db->escape($mediactiondata['instructions']) . "', status = '1', tags_id = '" . $tags_id . "', tags_medication_id = '" . $tags_medication_id . "', recurrence = '" . $this->db->escape($mediactiondata['recurrence']) . "', recurnce_hrly = '" .
		   $this->db->escape($mediactiondata['recurnce_hrly']) . "', end_recurrence_date = '" . $end_recurrence_date . "', recurnce_day = '" . $this->db->escape($recurnce_day) . "', recurnce_month = '" . $this->db->escape($recurnce_month) . "', recurnce_week = '" . $this->db->escape($recurnce_week) . "', recurnce_hrly_recurnce = '" .
			 $mediactiondata['recurnce_hrly_recurnce'] . "', daily_endtime = '" . $daily_endtime . "', daily_times = '" . $daily_times . "', date_from = '" . $date_from . "', date_to = '" . $date_to . "', is_schedule_medication = '" . $mediactiondata['is_schedule_medication'] . "', is_updated = '" . $mediactiondata['is_updated'] . "', tags_medication_details_ids = '" .
			 $tags_medication_details_ids . "' ";
			$this->db->query($sql );
			$tags_medication_details_id = $this->db->getLastId();
		
		}
                    
	}
	
	 public function get_medicationyname22 ($emp_tag_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "createtask WHERE medication_tags = '" . $emp_tag_id . "' ");
        return $query->rows;
    }
	
	
	public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
		$temp = array();
		foreach($arr as $key => $value) {
			$groupValue = $value[$group];
			if(!$preserveGroupKey)
			{
				unset($arr[$key][$group]);
			}
			if(!array_key_exists($groupValue, $temp)) {
				$temp[$groupValue] = array();
			}

			if(!$preserveSubArrays){
				$data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
			} else {
				$data = $arr[$key];
			}
			$temp[$groupValue][] = $data;
		}
		return $temp;
	}
}