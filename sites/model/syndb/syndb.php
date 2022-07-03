<?php
class Modelsyndbsyndb extends Model {
	public function getfacilities($data = array()) {
		// $sql = "SELECT * FROM `" . DB_PREFIX . "facilities` where `date_added` BETWEEN '".$data['startDate']." 00:00:00 ' AND '".$data['endDate']." 23:59:59' ";
		$sql = "SELECT * FROM `" . DB_PREFIX . "facilities` where `update_date` BETWEEN '" . $data ['startDate'] . " 00:00:00' AND  '" . $data ['endDate'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user` where `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getKeywords($data = array()) {
		// $sql = "SELECT * FROM `" . DB_PREFIX . "keyword` where `date_added` BETWEEN '".$data['startDate']." 00:00:00 ' AND '".$data['endDate']." 23:59:59' ";
		$sql = "SELECT * FROM `" . DB_PREFIX . "keyword` where `update_date` BETWEEN '" . $data ['startDate'] . " 00:00:00' AND  '" . $data ['endDate'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gethighliters($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "highlighter` where `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getnotes($data = array()) {
		// $sql = "SELECT * FROM `" . DB_PREFIX . "notes` where `date_added` BETWEEN '".$data['startDate']." 00:00:00 ' AND '".$data['endDate']." 23:59:59' and `update_date` BETWEEN '".$data['endDate']." 00:00:00 ' AND '".$data['endDate']." 23:59:59' and notes_conut = '0' ";
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' and notes_conut = '0' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function deleteNotes($last_date) {
		$sql = "delete FROM `" . DB_PREFIX . "notes` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sql );
		
		/*$sql12 = "delete FROM `" . DB_PREFIX . "log_activity` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sql12 );
		
		$sql1 = "delete FROM `" . DB_PREFIX . "log_activity_save` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sql1 );
		
		$sql2 = "delete FROM `" . DB_PREFIX . "kinesis_data` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sql2 );
		
		$sql23 = "delete FROM `" . DB_PREFIX . "web_token` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sql23 );
		
		$sqlo = "delete FROM `" . DB_PREFIX . "user_otp` where `date_added` <= '" . $last_date . " 23:59:59' ";
		$query = $this->db->query ( $sqlo );
		*/
	}
	public function getNotesByMain($data = array()) {
		$sql = "SELECT * FROM `" . NEWDB_PREFIX . "notes` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' and `update_date` BETWEEN  '" . $data ['endDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		
		$query = $this->newdb->query ( $sql );
		
		return $query->rows;
	}
	public function deleteNotesMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes` where unique_id = '" . $config_unique_id . "' and notes_id = '" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getgetfacilitiesByMain($data = array()) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "facilities` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' " );
		
		return $query->rows;
	}
	public function deletegetfacilitiesMain($data = array()) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "facilities` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getgetusersByMain($data = array()) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "user` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' " );
		
		return $query->rows;
	}
	public function deleteusersMain($data = array()) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "user` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getkeywordsByMain($data = array()) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "keyword` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' " );
		
		return $query->rows;
	}
	public function deletegetkeywordsMain($data = array()) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "keyword` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->newdb->query ( $sql );
	}
	public function gethighlitersByMain($data = array()) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "keyword` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' " );
		
		return $query->rows;
	}
	public function deletegethighlitersMain($data = array()) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "keyword` where unique_id = '" . $data ['config_unique_id'] . "' and `date_added` BETWEEN  '" . $data ['startDate'] . " 00:00:00 ' AND  '" . $data ['endDate'] . " 23:59:59' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getattachmentsByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_media` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletegetattachmentsMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_media` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getattahmentds($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_media` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getformsByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "forms` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletegetformsMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "forms` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getforms($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "forms` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettaskformsByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletegettaskformsMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function gettaskforms($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_task` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettagsByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_tags` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletegettagsMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_tags` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function gettags($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_tags` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getnoteskeywordsByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_keyword` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletenoteskeywordsMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_keyword` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function genoteskeywords($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getsharenotesByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "share_notes` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletesharenotesMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "share_notes` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function gesharenotes($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "share_notes` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function genotesscensus_details($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_census_detail` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function getapproval_tasknotesByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_approval_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deleteapproval_tasknotesMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_approval_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function geapproval_tasknotes($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_approval_task` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettravel_tasknotesByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_travel_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletetravel_tasknotesMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_travel_task` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getravel_tasknotes($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_travel_task` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getcreatetask_by_transportnotesByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_createtask_by_transport` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletecreatetask_by_transportnotesMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_createtask_by_transport` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function gecreatetask_by_transportnotes($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_createtask_by_transport` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getnotes_by_commentByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_comment` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletenotes_by_commentMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_comment` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getnotes_by_comments($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_comment` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getnotes_by_moveByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_facility` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletenotes_by_moveMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_facility` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getnotes_by_moves($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_facility` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getnotes_by_transcriptByMain($notes_id, $config_unique_id) {
		$query = $this->newdb->query ( "SELECT * FROM `" . NEWDB_PREFIX . "notes_by_facility` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' " );
		
		return $query->rows;
	}
	public function deletenotes_by_transcriptMain($notes_id, $config_unique_id) {
		$sql = "delete FROM `" . NEWDB_PREFIX . "notes_by_facility` where unique_id = '" . $config_unique_id . "' and `notes_id` ='" . $notes_id . "' ";
		$query = $this->newdb->query ( $sql );
	}
	public function getnotes_by_transcripts($notes_id, $config_unique_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_facility` where `notes_id` ='" . $notes_id . "' ";
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function insertTotal($data = array()) {
		$sql = "INSERT INTO `" . DB_PREFIX . "dashboard_activity` SET 
		total_notes = '" . $this->db->escape ( $data ['total_notes'] ) . "'
		,total_activenote = '" . $this->db->escape ( $data ['total_activenote'] ) . "'
		,total_highlighter = '" . $this->db->escape ( $data ['total_highlighter'] ) . "'
		,total_active_user = '" . $this->db->escape ( $data ['total_active_user'] ) . "'
		,total_intake_tags = '" . $this->db->escape ( $data ['total_intake_tags'] ) . "'
		,total_forms = '" . $this->db->escape ( $data ['total_forms'] ) . "'
		,total_screening = '" . $this->db->escape ( $data ['total_screening'] ) . "'
		,total_incident = '" . $this->db->escape ( $data ['total_incident'] ) . "'
		,total_timed_activenote = '" . $this->db->escape ( $data ['total_timed_activenote'] ) . "'
		,total_colour = '" . $this->db->escape ( $data ['total_colour'] ) . "'
		,total_media = '" . $this->db->escape ( $data ['total_media'] ) . "'
		,total_task = '" . $this->db->escape ( $data ['total_task'] ) . "'
		,date_added = '" . $this->db->escape ( $data ['date_added'] ) . "'
		,date_updated = '" . $this->db->escape ( $data ['date_updated'] ) . "'
		,facilities_id = '" . $data ['facilities_id'] . "'
		,status = '" . $data ['status'] . "'
		
		";
		// echo "<hr>";
		$query = $this->db->query ( $sql );
		
		return $this->db->getLastId ();
	}
	public function updateTotal($data = array(), $dashboard_activity_id) {
		$usql = "UPDATE `" . DB_PREFIX . "dashboard_activity` SET 
		 total_notes = '" . $this->db->escape ( $data ['total_notes'] ) . "'
		,total_activenote = '" . $this->db->escape ( $data ['total_activenote'] ) . "'
		,total_highlighter = '" . $this->db->escape ( $data ['total_highlighter'] ) . "'
		,total_active_user = '" . $this->db->escape ( $data ['total_active_user'] ) . "'
		,total_intake_tags = '" . $this->db->escape ( $data ['total_intake_tags'] ) . "'
		,total_forms = '" . $this->db->escape ( $data ['total_forms'] ) . "'
		,total_screening = '" . $this->db->escape ( $data ['total_screening'] ) . "'
		,total_incident = '" . $this->db->escape ( $data ['total_incident'] ) . "'
		,total_timed_activenote = '" . $this->db->escape ( $data ['total_timed_activenote'] ) . "'
		,total_colour = '" . $this->db->escape ( $data ['total_colour'] ) . "'
		,total_media = '" . $this->db->escape ( $data ['total_media'] ) . "'
		,total_task = '" . $this->db->escape ( $data ['total_task'] ) . "'
		,date_added = '" . $this->db->escape ( $data ['date_added'] ) . "'
		,date_updated = '" . $this->db->escape ( $data ['date_updated'] ) . "'
		,facilities_id = '" . $data ['facilities_id'] . "'
		,status = '" . $data ['status'] . "'
		
		where dashboard_activity_id = '" . $this->db->escape ( $dashboard_activity_id ) . "' ";
		$this->db->query ( $usql );
	}
	public function addsync($data = array()) {
		
		
		if(IS_WAREHOUSE == '1'){
			
		$this->load->model ( 'syndb/syndb' );
		$this->load->model ( 'activity/activity' );
		$this->load->model ( 'customer/customer' );
		
		// echo "sssss <hr>";
		
		$manual_link = $data ['manual_link'];
		
		if ($manual_link == '1') {
			define ( 'MONTH', '12' );
			define ( 'DAY', '1' );
			
			/* $startDate = date('Y-m-d', strtotime("-".MONTH." Months")); */
			$startDate = date ( 'Y-m-d', strtotime ( "-" . DAY . " day" ) );
			// $endDate = date('Y-m-d');
			$endDate = date ( 'Y-m-d' );
		} else {
			define ( 'MONTH', '12' );
			define ( 'DAY', '1' );
			
			/* $startDate = date('Y-m-d', strtotime("-".MONTH." Months")); */
			$startDate = date ( 'Y-m-d', strtotime ( "-" . DAY . " day" ) );
			$endDate = date ( 'Y-m-d' );
			// $endDate = date('Y-m-d', strtotime("-".DAY." day"));
		}
		
		// $config_unique_id = $this->config->get('config_unique_id');
		
		$datamodel = array ();
		
		$datamodel ['startDate'] = $startDate;
		$datamodel ['endDate'] = $endDate;
		$datamodel ['config_unique_id'] = $config_unique_id;
		
		try {
			/*
			 * $hostname = NEWDB_HOSTNAME;
			 * $username = NEWDB_USERNAME;
			 * $password = NEWDB_PASSWORD;
			 * $dbname = NEWDB_DATABASE;
			 *
			 * $connection = mysql_connect($hostname, $username, $password);
			 * var_dump($connection);
			 * echo mysql_error();
			 * mysql_select_db($dbname, $connection);
			 *
			 * //Setup our query
			 * echo $query = "SELECT * FROM ". NEWDB_PREFIX."user ";
			 *
			 * //Run the Query
			 * $result = mysql_query($query);
			 *
			 * //If the query returned results, loop through
			 * // each result
			 * var_dump($result);
			 *
			 * while($row = mysql_fetch_array($result))
			 * {
			 * $name = $row['username'];
			 * echo "Name: " . $name;
			 *
			 * }
			 */
			
			/*
			 * $livefacilities = $this->model_syndb_syndb->getgetfacilitiesByMain($datamodel);
			 * //var_dump($livefacilities);die;
			 * if($livefacilities != null && $livefacilities != ""){
			 * $this->model_syndb_syndb->deletegetfacilitiesMain($datamodel);
			 * }
			 */
			
			$facilities = $this->model_syndb_syndb->getfacilities ( $datamodel );
			
			if ($facilities != null && $facilities != "") {
				foreach ( $facilities as $facility ) {
					
					$fsql = "INSERT INTO " . NEWDB_PREFIX . "facilities SET facilities_id = '" . $facility ['facilities_id'] . "', timezone_id = '" . $facility ['timezone_id'] . "', facility = '" . $this->db->escape ($facility ['facility']) . "', password = '" . $facility ['password'] . "', salt = '" . $facility ['salt'] . "', firstname = '" . $this->db->escape ($facility ['firstname']) . "', lastname = '" . $this->db->escape ($facility ['lastname']) . "', email = '" . $this->db->escape ($facility ['email']) . "', code = '" . $facility ['code'] . "', ip = '" . $facility ['ip'] . "', status = '" . $facility ['status'] . "', date_added = '" . $facility ['date_added'] . "', description = '" . $this->db->escape ($facility ['description']) . "', address = '" . $this->db->escape ($facility ['address']) . "', location = '" . $this->db->escape ($facility ['location']) . "', country_id = '" . $facility ['country_id'] . "', zone_id = '" . $facility ['zone_id'] . "', zipcode = '" . $this->db->escape ($facility ['zipcode']) . "', users = '" . $this->db->escape ($facility ['users']) . "', config_task_status = '" . $facility ['config_task_status'] . "', config_tag_status = '" . $facility ['config_tag_status'] . "', sms_number = '" . $this->db->escape ($facility ['sms_number']) . "', config_taskform_status = '" . $facility ['config_taskform_status'] . "', config_noteform_status = '" . $facility ['config_noteform_status'] . "', config_rules_status = '" . $facility ['config_rules_status'] . "', config_display_camera = '" . $facility ['config_display_camera'] . "', latitude = '" . $facility ['latitude'] . "', longitude = '" . $facility ['longitude'] . "', config_display_dashboard = '" . $facility ['config_display_dashboard'] . "', config_share_notes = '" . $facility ['config_share_notes'] . "', config_sharepin_status = '" . $facility ['config_sharepin_status'] . "'
				, config_multiple_activenote = '" . $facility ['config_multiple_activenote'] . "'
				, sharenotes_print = '" . $facility ['sharenotes_print'] . "'
				, sharenotes_modify = '" . $facility ['sharenotes_modify'] . "'
				, sharenotes_copy = '" . $facility ['sharenotes_copy'] . "'
				, sharenotes_assemble = '" . $facility ['sharenotes_assemble'] . "'
				, config_send_email_share_notes = '" . $facility ['config_send_email_share_notes'] . "'
				, config_rolecall_customlist_id = '" . $facility ['config_rolecall_customlist_id'] . "'
				, config_tags_customlist_id = '" . $facility ['config_tags_customlist_id'] . "'
				, update_date = '" . $facility ['update_date'] . "'
				, config_bedcheck_customlist_id = '" . $facility ['config_bedcheck_customlist_id'] . "'
				, form_print_layout = '" . $facility ['form_print_layout'] . "'
				, config_face_recognition = '" . $facility ['config_face_recognition'] . "'
				, customer_key = '" . $facility ['customer_key'] . "'
				, iframe_url = '" . $this->db->escape ($facility ['iframe_url']) . "'
				, face_similar_percent = '" . $facility ['face_similar_percent'] . "'
				, allow_face_without_verified = '" . $facility ['allow_face_without_verified'] . "'
				, allow_quick_save = '" . $facility ['allow_quick_save'] . "'
				, display_attchament = '" . $facility ['display_attchament'] . "'
				, is_web_notification = '" . $facility ['is_web_notification'] . "'
				, web_audio_file = '" . $facility ['web_audio_file'] . "'
				, web_is_snooze = '" . $facility ['web_is_snooze'] . "'
				, web_is_dismiss = '" . $facility ['web_is_dismiss'] . "'
				, is_android_notification = '" . $facility ['is_android_notification'] . "'
				, android_audio_file = '" . $facility ['android_audio_file'] . "'
				, is_android_snooze = '" . $facility ['is_android_snooze'] . "'
				, is_android_dismiss = '" . $facility ['is_android_dismiss'] . "'
				, is_ios_notification = '" . $facility ['is_ios_notification'] . "'
				, ios_audio_file = '" . $facility ['ios_audio_file'] . "'
				, is_ios_snooze = '" . $facility ['is_ios_snooze'] . "'
				, is_ios_dismiss = '" . $facility ['is_ios_dismiss'] . "'
				, device_ids = '" . $facility ['device_ids'] . "'
				, device_username = '" . $facility ['device_username'] . "'
				, device_token = '" . $facility ['device_token'] . "'
				, is_enable_beacon = '" . $facility ['is_enable_beacon'] . "'
				, beacon_range = '" . $facility ['beacon_range'] . "'
				, beacon_data_type_range = '" . $facility ['beacon_data_type_range'] . "'
				, config_current_location = '" . $facility ['config_current_location'] . "'
				, is_discharge_form_enable = '" . $facility ['is_discharge_form_enable'] . "'
				, discharge_form_id = '" . $facility ['discharge_form_id'] . "'
				, is_data_sync = '" . $facility ['is_data_sync'] . "'
				, data_sync_date_to = '" . $facility ['data_sync_date_to'] . "'
				, data_sync_date_from = '" . $facility ['data_sync_date_from'] . "'
				, is_fingerprint_enable = '" . $facility ['is_fingerprint_enable'] . "'
				, is_sms_enable = '" . $facility ['is_sms_enable'] . "'
				, is_pin_enable = '" . $facility ['is_pin_enable'] . "'
				, is_email_enable = '" . $facility ['is_email_enable'] . "'
				, is_enable_add_notes_by = '" . $facility ['is_enable_add_notes_by'] . "'
				, is_client_facial = '" . $facility ['is_client_facial'] . "'
				, is_master_facility = '" . $facility ['is_master_facility'] . "'
				, notes_facilities_ids = '" . $facility ['notes_facilities_ids'] . "'
				, client_facilities_ids = '" . $facility ['client_facilities_ids'] . "'
				, unique_id = '" . $facility ['customer_key'] . "'
				ON DUPLICATE KEY UPDATE 
				timezone_id = '" . $facility ['timezone_id'] . "', facility = '" . $this->db->escape ($facility ['facility']) . "', password = '" . $facility ['password'] . "', salt = '" . $facility ['salt'] . "', firstname = '" . $this->db->escape ($facility ['firstname']) . "', lastname = '" . $this->db->escape ($facility ['lastname']) . "', email = '" . $this->db->escape ($facility ['email']) . "', code = '" . $facility ['code'] . "', ip = '" . $facility ['ip'] . "', status = '" . $facility ['status'] . "', date_added = '" . $facility ['date_added'] . "', description = '" . $this->db->escape ($facility ['description']) . "', address = '" . $this->db->escape ($facility ['address']) . "', location = '" . $this->db->escape ($facility ['location']) . "', country_id = '" . $facility ['country_id'] . "', zone_id = '" . $facility ['zone_id'] . "', zipcode = '" . $this->db->escape ($facility ['zipcode']) . "', users = '" . $this->db->escape ($facility ['users']) . "', config_task_status = '" . $facility ['config_task_status'] . "', config_tag_status = '" . $facility ['config_tag_status'] . "', sms_number = '" . $this->db->escape ($facility ['sms_number']) . "', config_taskform_status = '" . $facility ['config_taskform_status'] . "', config_noteform_status = '" . $facility ['config_noteform_status'] . "', config_rules_status = '" . $facility ['config_rules_status'] . "', config_display_camera = '" . $facility ['config_display_camera'] . "', latitude = '" . $facility ['latitude'] . "', longitude = '" . $facility ['longitude'] . "', config_display_dashboard = '" . $facility ['config_display_dashboard'] . "', config_share_notes = '" . $facility ['config_share_notes'] . "', config_sharepin_status = '" . $facility ['config_sharepin_status'] . "'
				, config_multiple_activenote = '" . $facility ['config_multiple_activenote'] . "'
				, sharenotes_print = '" . $facility ['sharenotes_print'] . "'
				, sharenotes_modify = '" . $facility ['sharenotes_modify'] . "'
				, sharenotes_copy = '" . $facility ['sharenotes_copy'] . "'
				, sharenotes_assemble = '" . $facility ['sharenotes_assemble'] . "'
				, config_send_email_share_notes = '" . $facility ['config_send_email_share_notes'] . "'
				, config_rolecall_customlist_id = '" . $facility ['config_rolecall_customlist_id'] . "'
				, config_tags_customlist_id = '" . $facility ['config_tags_customlist_id'] . "'
				, update_date = '" . $facility ['update_date'] . "'
				, config_bedcheck_customlist_id = '" . $facility ['config_bedcheck_customlist_id'] . "'
				, form_print_layout = '" . $facility ['form_print_layout'] . "'
				, config_face_recognition = '" . $facility ['config_face_recognition'] . "'
				, customer_key = '" . $facility ['customer_key'] . "'
				, iframe_url = '" . $this->db->escape ($facility ['iframe_url']) . "'
				, face_similar_percent = '" . $facility ['face_similar_percent'] . "'
				, allow_face_without_verified = '" . $facility ['allow_face_without_verified'] . "'
				, allow_quick_save = '" . $facility ['allow_quick_save'] . "'
				, display_attchament = '" . $facility ['display_attchament'] . "'
				, is_web_notification = '" . $facility ['is_web_notification'] . "'
				, web_audio_file = '" . $facility ['web_audio_file'] . "'
				, web_is_snooze = '" . $facility ['web_is_snooze'] . "'
				, web_is_dismiss = '" . $facility ['web_is_dismiss'] . "'
				, is_android_notification = '" . $facility ['is_android_notification'] . "'
				, android_audio_file = '" . $facility ['android_audio_file'] . "'
				, is_android_snooze = '" . $facility ['is_android_snooze'] . "'
				, is_android_dismiss = '" . $facility ['is_android_dismiss'] . "'
				, is_ios_notification = '" . $facility ['is_ios_notification'] . "'
				, ios_audio_file = '" . $facility ['ios_audio_file'] . "'
				, is_ios_snooze = '" . $facility ['is_ios_snooze'] . "'
				, is_ios_dismiss = '" . $facility ['is_ios_dismiss'] . "'
				, device_ids = '" . $facility ['device_ids'] . "'
				, device_username = '" . $facility ['device_username'] . "'
				, device_token = '" . $facility ['device_token'] . "'
				, is_enable_beacon = '" . $facility ['is_enable_beacon'] . "'
				, beacon_range = '" . $facility ['beacon_range'] . "'
				, beacon_data_type_range = '" . $facility ['beacon_data_type_range'] . "'
				, config_current_location = '" . $facility ['config_current_location'] . "'
				, is_discharge_form_enable = '" . $facility ['is_discharge_form_enable'] . "'
				, discharge_form_id = '" . $facility ['discharge_form_id'] . "'
				, is_data_sync = '" . $facility ['is_data_sync'] . "'
				, data_sync_date_to = '" . $facility ['data_sync_date_to'] . "'
				, data_sync_date_from = '" . $facility ['data_sync_date_from'] . "'
				, is_fingerprint_enable = '" . $facility ['is_fingerprint_enable'] . "'
				, is_sms_enable = '" . $facility ['is_sms_enable'] . "'
				, is_pin_enable = '" . $facility ['is_pin_enable'] . "'
				, is_email_enable = '" . $facility ['is_email_enable'] . "'
				, is_enable_add_notes_by = '" . $facility ['is_enable_add_notes_by'] . "'
				, is_client_facial = '" . $facility ['is_client_facial'] . "'
				, is_master_facility = '" . $facility ['is_master_facility'] . "'
				, notes_facilities_ids = '" . $facility ['notes_facilities_ids'] . "'
				, client_facilities_ids = '" . $facility ['client_facilities_ids'] . "'
				, unique_id = '" . $facility ['customer_key'] . "' ";
					
					$this->newdb->query ( $fsql );
				}
			}
			
			/*
			 * $livekeywords = $this->model_syndb_syndb->getkeywordsByMain($datamodel);
			 * if($livekeywords != null && $livekeywords != ""){
			 * $this->model_syndb_syndb->deletegetkeywordsMain($datamodel);
			 * }
			 */
			
			$keywords = $this->model_syndb_syndb->getKeywords ( $datamodel );
			
			if ($keywords != null && $keywords != "") {
				foreach ( $keywords as $keyword ) {
					
					$query1 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "activecustomer` WHERE customer_key = '" . $keyword ['customer_key'] . "'" );
					$customer_info = $query1->row;
					
					$sql = "INSERT INTO " . NEWDB_PREFIX . "keyword SET keyword_id = '" . $keyword ['keyword_id'] . "', keyword_name = '" . $keyword ['keyword_name'] . "', keyword_value = '" . $keyword ['keyword_value'] . "', active_tag = '" . $keyword ['active_tag'] . "', sort_order = '" . $keyword ['sort_order'] . "', status = '" . $keyword ['status'] . "', date_added = '" . $keyword ['date_added'] . "', keyword_image = '" . $keyword ['keyword_image'] . "', facilities_id = '" . $keyword ['facilities_id'] . "', relation_keyword_id = '" . $keyword ['relation_keyword_id'] . "', unique_id = '" . $customer_info ['customer_key'] . "' , monitor_time = '" . $keyword ['monitor_time'] . "', monitor_time_image = '" . $keyword ['monitor_time_image'] . "', relation_hastag = '" . $keyword ['relation_hastag'] . "', end_relation_keyword = '" . $keyword ['end_relation_keyword'] . "', update_date = '" . $keyword ['update_date'] . "', customer_key = '" . $keyword ['customer_key'] . "', is_special = '" . $keyword ['is_special'] . "', keyword_ids = '" . $keyword ['keyword_ids'] . "'
				ON DUPLICATE KEY UPDATE 
				keyword_name = '" . $keyword ['keyword_name'] . "', keyword_value = '" . $keyword ['keyword_value'] . "', active_tag = '" . $keyword ['active_tag'] . "', sort_order = '" . $keyword ['sort_order'] . "', status = '" . $keyword ['status'] . "', date_added = '" . $keyword ['date_added'] . "', keyword_image = '" . $keyword ['keyword_image'] . "', facilities_id = '" . $keyword ['facilities_id'] . "', relation_keyword_id = '" . $keyword ['relation_keyword_id'] . "', unique_id = '" . $customer_info ['customer_key'] . "' , monitor_time = '" . $keyword ['monitor_time'] . "', monitor_time_image = '" . $keyword ['monitor_time_image'] . "', relation_hastag = '" . $keyword ['relation_hastag'] . "', end_relation_keyword = '" . $keyword ['end_relation_keyword'] . "', update_date = '" . $keyword ['update_date'] . "', customer_key = '" . $keyword ['customer_key'] . "', is_special = '" . $keyword ['is_special'] . "', keyword_ids = '" . $keyword ['keyword_ids'] . "' ";
					
					$this->newdb->query ( $sql );
				}
			}
			
			// echo "11111111111111";
			
			/*
			 * $liveusers = $this->model_syndb_syndb->getgetusersByMain($datamodel);
			 * if($liveusers != null && $liveusers != ""){
			 * $this->model_syndb_syndb->deleteusersMain($datamodel);
			 * }
			 *
			 * $users = $this->model_syndb_syndb->getusers($datamodel);
			 *
			 * if($users != null && $users != ""){
			 * foreach($users as $user){
			 *
			 * $this->newdb->query("INSERT INTO `" . NEWDB_PREFIX . "user` SET user_id = '" . $user['user_id'] . "', user_group_id = '" . $user['user_group_id'] . "', username = '" . $user['username'] . "', password = '" . $user['password'] . "', salt = '" . $user['salt'] . "', firstname = '" . $user['firstname'] . "', lastname = '" . $user['lastname'] . "', email = '" . $user['email'] . "', code = '" . $user['code'] . "', ip = '" . $user['ip'] . "', status = '" . $user['status'] . "', date_added = '" . $user['date_added'] . "', user_pin = '" . $user['user_pin'] . "', facilities = '" . $user['facilities'] . "', phone_number = '" . $user['phone_number'] . "', parent_id = '" . $user['parent_id'] . "', activationKey = '" . $user['activationKey'] . "', default_facilities_id = '" . $user['default_facilities_id'] . "', default_highlighter_id = '" . $user['default_highlighter_id'] . "', default_color = '" . $user['default_color'] . "', user_otp = '" . $user['user_otp'] . "', message_sid = '" . $user['message_sid'] . "', facilities_display = '" . $user['facilities_display'] . "', unique_id = '" . $config_unique_id . "' ");
			 * }
			 * $activity_data2 = array(
			 * 'data' => 'sync users data successfully in warehouse ',
			 * );
			 * $this->model_activity_activity->addActivity('users', $activity_data2);
			 *
			 * }else{
			 *
			 * $activity_data2 = array(
			 * 'data' => 'no users data sync in warehouse because no data in given date',
			 * );
			 * $this->model_activity_activity->addActivity('users', $activity_data2);
			 * }
			 */
			// echo "222222222222222222";
			
			/*
			 * //echo "3333333333333333333";
			 * $livehighliters = $this->model_syndb_syndb->gethighlitersByMain($datamodel);
			 * if($livehighliters != null && $livehighliters != ""){
			 * $this->model_syndb_syndb->deletegethighlitersMain($datamodel);
			 * }
			 *
			 * $highliters = $this->model_syndb_syndb->gethighliters($datamodel);
			 *
			 * if($highliters != null && $highliters != ""){
			 * foreach($highliters as $highliter){
			 *
			 * $sql = "INSERT INTO " . NEWDB_PREFIX . "highlighter SET highlighter_id = '" . $highliter['highlighter_id'] . "', highlighter_name = '" . $highliter['highlighter_name'] . "', highlighter_value = '" . $highliter['highlighter_value'] . "', sort_order = '" . $highliter['sort_order'] . "', status = '" . $highliter['status'] . "', date_added = '" . $highliter['date_added'] . "', highlighter_icon = '" . $highliter['highlighter_icon'] . "', unique_id = '" . $config_unique_id . "' ";
			 * $this->newdb->query($sql);
			 * }
			 * $activity_data4 = array(
			 * 'data' => 'sync highliters data successfully in warehouse ',
			 * );
			 * $this->model_activity_activity->addActivity('highliters', $activity_data4);
			 *
			 * }else{
			 *
			 * $activity_data4 = array(
			 * 'data' => 'no highliters data sync in warehouse because no data in given date',
			 * );
			 * $this->model_activity_activity->addActivity('highliters', $activity_data4);
			 * }
			 *
			 */
			
			// echo "4444444444444444444444";
			
			/*
			 * $livenotes = $this->model_syndb_syndb->getNotesByMain($datamodel);
			 *
			 *
			 * if($livenotes != null && $livenotes != ""){
			 * $this->model_syndb_syndb->deleteNotesMain($datamodel);
			 * }
			 */
			
			$notes = $this->model_syndb_syndb->getnotes ( $datamodel );
			
			if ($notes != null && $notes != "") {
				foreach ( $notes as $note1 ) {
					$this->model_syndb_syndb->deleteNotesMain ( $note1 ['notes_id'], $config_unique_id );
				}
				
				$ttotalnotes = 0;
				$ttotalforms = 0;
				$ttotaltasks = 0;
				$activenotecount = 0;
				$ttotaltags = 0;
				
				$facilities = array ();
				
				foreach ( $notes as $note ) {
					
					// $facilities[] = $note['facilities_id'];
					$notes_description = $note ['notes_description'];
					
					$config_unique_id = $note ['unique_id'];
					$facilities [$config_unique_id] [] = $note ['facilities_id'];
					$ttotalnotes = $ttotalnotes + 1;
					
					$sql = "INSERT INTO `" . NEWDB_PREFIX . "notes` SET notes_id = '" . $note ['notes_id'] . "', facilities_id = '" . $note ['facilities_id'] . "', notes_description = '" . $this->db->escape ( $notes_description ) . "', highlighter_id = '" . $note ['highlighter_id'] . "', notes_pin = '" . $this->db->escape ( $note ['notes_pin'] ) . "', notes_file = '" . $this->db->escape ( $note ['notes_file'] ) . "', date_added = '" . $note ['date_added'] . "', status = '" . $note ['status'] . "', user_id = '" . $this->db->escape ( $note ['user_id'] ) . "', signature = '" . $note ['signature'] . "', signature_image = '" . $note ['signature_image'] . "', notetime = '" . $note ['notetime'] . "', note_date = '" . $note ['note_date'] . "', text_color_cut = '" . $note ['text_color_cut'] . "',  text_color = '" . $note ['text_color'] . "',  strike_user_id = '" . $this->db->escape ( $note ['strike_user_id'] ) . "', strike_date_added = '" . $note ['strike_date_added'] . "', strike_signature = '" . $note ['strike_signature'] . "', strike_signature_image = '" . $note ['strike_signature_image'] . "', strike_pin = '" . $this->db->escape ( $note ['strike_pin'] ) . "', global_utc_timezone = '" . $note ['global_utc_timezone'] . "', keyword_file_url = '" . $note ['keyword_file_url'] . "', highlighter_value = '" . $note ['highlighter_value'] . "', keyword_file = '" . $note ['keyword_file'] . "', taskadded = '" . $note ['taskadded'] . "', task_time = '" . $note ['task_time'] . "', assign_to = '" . $note ['assign_to'] . "', emp_tag_id = '" . $note ['emp_tag_id'] . "', notes_type = '" . $note ['notes_type'] . "', checklist_status = '" . $note ['checklist_status'] . "', snooze_time = '" . $note ['snooze_time'] . "', snooze_dismiss = '" . $note ['snooze_dismiss'] . "', send_sms = '" . $note ['send_sms'] . "', send_email = '" . $note ['send_email'] . "', notes_search_keword = '" . $note ['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note ['strike_note_type'] . "', audio_attach_url = '" . $note ['audio_attach_url'] . "', task_type = '" . $note ['task_type'] . "', tags_id = '" . $note ['tags_id'] . "', update_date = '" . $note ['update_date'] . "', medication_attach_url = '" . $note ['medication_attach_url'] . "', is_private = '" . $note ['is_private'] . "', is_private_strike = '" . $note ['is_private_strike'] . "', assessment_id = '" . $note ['assessment_id'] . "', review_notes = '" . $note ['review_notes'] . "', share_notes = '" . $note ['share_notes'] . "', rule_highlighter_task = '" . $note ['rule_highlighter_task'] . "', rule_activenote_task = '" . $note ['rule_activenote_task'] . "', rule_color_task = '" . $note ['rule_color_task'] . "', rule_keyword_task = '" . $note ['rule_keyword_task'] . "', is_offline = '" . $note ['is_offline'] . "', notes_conut = '" . $note ['notes_conut'] . "', tasktype = '" . $note ['tasktype'] . "', visitor_log = '" . $note ['visitor_log'] . "', task_id = '" . $note ['task_id'] . "', task_date = '" . $note ['task_date'] . "', parent_id = '" . $note ['parent_id'] . "', end_perpetual_task = '" . $note ['end_perpetual_task'] . "', recurrence = '" . $note ['recurrence'] . "', customlistvalues_id = '" . $note ['customlistvalues_id'] . "', generate_report = '" . $note ['generate_report'] . "', is_android = '" . $note ['is_android'] . "', is_census = '" . $note ['is_census'] . "', is_tag = '" . $note ['is_tag'] . "', form_type = '" . $note ['form_type'] . "', tagstatus_id = '" . $note ['tagstatus_id'] . "', task_group_by = '" . $note ['task_group_by'] . "', end_task = '" . $note ['end_task'] . "', form_snooze_dismiss = '" . $note ['form_snooze_dismiss'] . "', form_send_sms = '" . $note ['form_send_sms'] . "', form_send_email = '" . $note ['form_send_email'] . "', form_snooze_time = '" . $note ['form_snooze_time'] . "', form_create_task = '" . $note ['form_create_task'] . "', form_alert_send_email = '" . $note ['form_alert_send_email'] . "', form_alert_send_sms = '" . $note ['form_alert_send_sms'] . "', is_archive = '" . $note ['is_archive'] . "', phone_device_id = '" . $note ['phone_device_id'] . "', original_task_time = '" . $note ['original_task_time'] . "', is_forms = '" . $note ['is_forms'] . "', is_reminder = '" . $note ['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note ['form_trigger_snooze_dismiss'] . "', user_file = '" . $note ['user_file'] . "', is_user_face = '" . $note ['is_user_face'] . "', is_approval_required_forms_id = '" . $note ['is_approval_required_forms_id'] . "', is_casecount = '" . $note ['is_casecount'] . "', device_unique_id = '" . $note ['device_unique_id'] . "', sync_dashboard = '" . $note ['sync_dashboard'] . "', strike_user_file = '" . $note ['strike_user_file'] . "', strike_is_user_face = '" . $note ['strike_is_user_face'] . "', linked_id = '" . $note ['linked_id'] . "', parent_facilities_id = '" . $note ['parent_facilities_id'] . "', task_form_id = '" . $note ['task_form_id'] . "', shift_id = '" . $note ['shift_id'] . "'
				
				ON DUPLICATE KEY UPDATE 
				
				facilities_id = '" . $note ['facilities_id'] . "', notes_description = '" . $this->db->escape ( $notes_description ) . "', highlighter_id = '" . $note ['highlighter_id'] . "', notes_pin = '" . $note ['notes_pin'] . "', notes_file = '" . $note ['notes_file'] . "', date_added = '" . $note ['date_added'] . "', status = '" . $note ['status'] . "', user_id = '" . $this->db->escape ( $note ['user_id'] ) . "', signature = '" . $note ['signature'] . "', signature_image = '" . $note ['signature_image'] . "', notetime = '" . $note ['notetime'] . "', note_date = '" . $note ['note_date'] . "', text_color_cut = '" . $note ['text_color_cut'] . "',  text_color = '" . $note ['text_color'] . "',  strike_user_id = '" . $this->db->escape ( $note ['strike_user_id'] ) . "', strike_date_added = '" . $note ['strike_date_added'] . "', strike_signature = '" . $note ['strike_signature'] . "', strike_signature_image = '" . $note ['strike_signature_image'] . "', strike_pin = '" . $note ['strike_pin'] . "', global_utc_timezone = '" . $note ['global_utc_timezone'] . "', keyword_file_url = '" . $note ['keyword_file_url'] . "', highlighter_value = '" . $note ['highlighter_value'] . "', keyword_file = '" . $note ['keyword_file'] . "', taskadded = '" . $note ['taskadded'] . "', task_time = '" . $note ['task_time'] . "', assign_to = '" . $note ['assign_to'] . "', emp_tag_id = '" . $note ['emp_tag_id'] . "', notes_type = '" . $note ['notes_type'] . "', checklist_status = '" . $note ['checklist_status'] . "', snooze_time = '" . $note ['snooze_time'] . "', snooze_dismiss = '" . $note ['snooze_dismiss'] . "', send_sms = '" . $note ['send_sms'] . "', send_email = '" . $note ['send_email'] . "', notes_search_keword = '" . $note ['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note ['strike_note_type'] . "', audio_attach_url = '" . $note ['audio_attach_url'] . "', task_type = '" . $note ['task_type'] . "', tags_id = '" . $note ['tags_id'] . "', update_date = '" . $note ['update_date'] . "', medication_attach_url = '" . $note ['medication_attach_url'] . "', is_private = '" . $note ['is_private'] . "', is_private_strike = '" . $note ['is_private_strike'] . "', assessment_id = '" . $note ['assessment_id'] . "', review_notes = '" . $note ['review_notes'] . "', share_notes = '" . $note ['share_notes'] . "', rule_highlighter_task = '" . $note ['rule_highlighter_task'] . "', rule_activenote_task = '" . $note ['rule_activenote_task'] . "', rule_color_task = '" . $note ['rule_color_task'] . "', rule_keyword_task = '" . $note ['rule_keyword_task'] . "', is_offline = '" . $note ['is_offline'] . "', notes_conut = '" . $note ['notes_conut'] . "', tasktype = '" . $note ['tasktype'] . "', visitor_log = '" . $note ['visitor_log'] . "', task_id = '" . $note ['task_id'] . "', task_date = '" . $note ['task_date'] . "', parent_id = '" . $note ['parent_id'] . "', end_perpetual_task = '" . $note ['end_perpetual_task'] . "', recurrence = '" . $note ['recurrence'] . "', customlistvalues_id = '" . $note ['customlistvalues_id'] . "', generate_report = '" . $note ['generate_report'] . "', is_android = '" . $note ['is_android'] . "', is_census = '" . $note ['is_census'] . "', is_tag = '" . $note ['is_tag'] . "', form_type = '" . $note ['form_type'] . "', tagstatus_id = '" . $note ['tagstatus_id'] . "', task_group_by = '" . $note ['task_group_by'] . "', end_task = '" . $note ['end_task'] . "', form_snooze_dismiss = '" . $note ['form_snooze_dismiss'] . "', form_send_sms = '" . $note ['form_send_sms'] . "', form_send_email = '" . $note ['form_send_email'] . "', form_snooze_time = '" . $note ['form_snooze_time'] . "', form_create_task = '" . $note ['form_create_task'] . "', form_alert_send_email = '" . $note ['form_alert_send_email'] . "', form_alert_send_sms = '" . $note ['form_alert_send_sms'] . "', is_archive = '" . $note ['is_archive'] . "', phone_device_id = '" . $note ['phone_device_id'] . "', original_task_time = '" . $note ['original_task_time'] . "', is_forms = '" . $note ['is_forms'] . "', is_reminder = '" . $note ['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note ['form_trigger_snooze_dismiss'] . "', user_file = '" . $note ['user_file'] . "', is_user_face = '" . $note ['is_user_face'] . "', is_approval_required_forms_id = '" . $note ['is_approval_required_forms_id'] . "', is_casecount = '" . $note ['is_casecount'] . "', device_unique_id = '" . $note ['device_unique_id'] . "', sync_dashboard = '" . $note ['sync_dashboard'] . "', strike_user_file = '" . $note ['strike_user_file'] . "', strike_is_user_face = '" . $note ['strike_is_user_face'] . "', linked_id = '" . $note ['linked_id'] . "', parent_facilities_id = '" . $note ['parent_facilities_id'] . "', task_form_id = '" . $note ['task_form_id'] . "', shift_id = '" . $note ['shift_id'] . "' ";
					
					$this->newdb->query ( $sql );
					
					$liveattachmentss = $this->model_syndb_syndb->getattachmentsByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($liveattachmentss != null && $liveattachmentss != "") {
						$this->model_syndb_syndb->deletegetattachmentsMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$attachments = $this->model_syndb_syndb->getattahmentds ( $note ['notes_id'], $config_unique_id );
					
					if ($attachments != null && $attachments != "") {
						foreach ( $attachments as $attachment ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_media SET notes_media_id = '" . $attachment ['notes_media_id'] . "', notes_file = '" . $attachment ['notes_file'] . "', notes_id = '" . $attachment ['notes_id'] . "', deleted = '" . $attachment ['deleted'] . "', status = '" . $attachment ['status'] . "', notes_media_extention = '" . $attachment ['notes_media_extention'] . "', media_user_id = '" . $this->db->escape ( $attachment ['media_user_id'] ) . "', media_date_added = '" . $attachment ['media_date_added'] . "', media_signature = '" . $attachment ['media_signature'] . "', media_signature_image = '" . $attachment ['media_signature_image'] . "', media_pin = '" . $attachment ['media_pin'] . "', update_media = '" . $attachment ['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment ['notes_type'] . "', audio_attach_url = '" . $attachment ['audio_attach_url'] . "', audio_attach_type = '" . $attachment ['audio_attach_type'] . "', audio_upload_file = '" . $attachment ['audio_upload_file'] . "', facilities_id = '" . $attachment ['facilities_id'] . "', speech_name = '" . $attachment ['speech_name'] . "', is_updated = '" . $attachment ['is_updated'] . "' , phone_device_id = '" . $attachment ['phone_device_id'] . "' , is_android = '" . $attachment ['is_android'] . "' , sync_dashboard = '" . $attachment ['sync_dashboard'] . "' , user_file = '" . $attachment ['user_file'] . "' , is_user_face = '" . $attachment ['is_user_face'] . "' 
						ON DUPLICATE KEY UPDATE 
						notes_file = '" . $attachment ['notes_file'] . "', notes_id = '" . $attachment ['notes_id'] . "', deleted = '" . $attachment ['deleted'] . "', status = '" . $attachment ['status'] . "', notes_media_extention = '" . $attachment ['notes_media_extention'] . "', media_user_id = '" . $this->db->escape ( $attachment ['media_user_id'] ) . "', media_date_added = '" . $attachment ['media_date_added'] . "', media_signature = '" . $attachment ['media_signature'] . "', media_signature_image = '" . $attachment ['media_signature_image'] . "', media_pin = '" . $attachment ['media_pin'] . "', update_media = '" . $attachment ['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment ['notes_type'] . "', audio_attach_url = '" . $attachment ['audio_attach_url'] . "', audio_attach_type = '" . $attachment ['audio_attach_type'] . "', audio_upload_file = '" . $attachment ['audio_upload_file'] . "', facilities_id = '" . $attachment ['facilities_id'] . "', speech_name = '" . $attachment ['speech_name'] . "', is_updated = '" . $attachment ['is_updated'] . "' , phone_device_id = '" . $attachment ['phone_device_id'] . "' , is_android = '" . $attachment ['is_android'] . "' , sync_dashboard = '" . $attachment ['sync_dashboard'] . "' , user_file = '" . $attachment ['user_file'] . "' , is_user_face = '" . $attachment ['is_user_face'] . "'    ";
							$this->newdb->query ( $sql );
						}
					}
					
					$liveforms = $this->model_syndb_syndb->getformsByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($liveforms != null && $liveforms != "") {
						$this->model_syndb_syndb->deletegetformsMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$forms = $this->model_syndb_syndb->getforms ( $note ['notes_id'], $config_unique_id );
					
					if ($forms != null && $forms != "") {
						foreach ( $forms as $form ) {
							
							$ttotalforms = $ttotalforms + 1;
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "forms SET forms_id = '" . $form ['forms_id'] . "', form_type_id = '" . $form ['form_type_id'] . "', form_type = '" . $form ['form_type'] . "', form_description = '" . $this->db->escape ( $form ['form_description'] ) . "', date_added = '" . $form ['date_added'] . "', notes_id = '" . $form ['notes_id'] . "', user_id = '" . $this->db->escape ( $form ['user_id'] ) . "', signature = '" . $form ['signature'] . "', notes_pin = '" . $form ['notes_pin'] . "', form_date_added = '" . $form ['form_date_added'] . "', incident_number = '" . $this->db->escape ( $form ['incident_number'] ) . "', facilities_id = '" . $form ['facilities_id'] . "', notes_type = '" . $form ['notes_type'] . "', assessment_id = '" . $form ['assessment_id'] . "', custom_form_type = '" . $form ['custom_form_type'] . "', design_forms = '" . $this->db->escape ( $form ['design_forms'] ) . "', date_updated = '" . $form ['date_updated'] . "', unique_id = '" . $config_unique_id . "', upload_file = '" . $form ['upload_file'] . "', tags_id = '" . $form ['tags_id'] . "', parent_id = '" . $form ['parent_id'] . "', is_discharge = '" . $form ['is_discharge'] . "', tagstatus_id = '" . $form ['tagstatus_id'] . "', rules_form_description = '" . $this->db->escape ( $form ['rules_form_description'] ) . "', is_archive = '" . $this->db->escape ( $form ['is_archive'] ) . "', is_final = '" . $this->db->escape ( $form ['is_final'] ) . "', is_approval_required = '" . $this->db->escape ( $form ['is_approval_required'] ) . "', is_approved = '" . $this->db->escape ( $form ['is_approved'] ) . "', phone_device_id = '" . $this->db->escape ( $form ['phone_device_id'] ) . "', is_android = '" . $this->db->escape ( $form ['is_android'] ) . "', form_parent_id = '" . $this->db->escape ( $form ['form_parent_id'] ) . "', form_design_parent_id = '" . $this->db->escape ( $form ['form_design_parent_id'] ) . "', page_number = '" . $this->db->escape ( $form ['page_number'] ) . "', status = '" . $this->db->escape ( $form ['status'] ) . "', sync_dashboard = '" . $this->db->escape ( $form ['sync_dashboard'] ) . "', user_file = '" . $this->db->escape ( $form ['user_file'] ) . "', is_user_face = '" . $this->db->escape ( $form ['is_user_face'] ) . "', destination_facilities_id = '" . $this->db->escape ( $form ['destination_facilities_id'] ) . "', html_file_url = '" . $this->db->escape ( $form ['html_file_url'] ) . "'
						ON DUPLICATE KEY UPDATE 
						form_type_id = '" . $form ['form_type_id'] . "', form_type = '" . $form ['form_type'] . "', form_description = '" . $this->db->escape ( $form ['form_description'] ) . "', date_added = '" . $form ['date_added'] . "', notes_id = '" . $form ['notes_id'] . "', user_id = '" . $this->db->escape ( $form ['user_id'] ) . "', signature = '" . $form ['signature'] . "', notes_pin = '" . $form ['notes_pin'] . "', form_date_added = '" . $form ['form_date_added'] . "', incident_number = '" . $this->db->escape ( $form ['incident_number'] ) . "', facilities_id = '" . $form ['facilities_id'] . "', notes_type = '" . $form ['notes_type'] . "', assessment_id = '" . $form ['assessment_id'] . "', custom_form_type = '" . $form ['custom_form_type'] . "', design_forms = '" . $this->db->escape ( $form ['design_forms'] ) . "', date_updated = '" . $form ['date_updated'] . "', unique_id = '" . $config_unique_id . "', upload_file = '" . $form ['upload_file'] . "', tags_id = '" . $form ['tags_id'] . "', parent_id = '" . $form ['parent_id'] . "', is_discharge = '" . $form ['is_discharge'] . "', tagstatus_id = '" . $form ['tagstatus_id'] . "', rules_form_description = '" . $this->db->escape ( $form ['rules_form_description'] ) . "', is_archive = '" . $this->db->escape ( $form ['is_archive'] ) . "', is_final = '" . $this->db->escape ( $form ['is_final'] ) . "', is_approval_required = '" . $this->db->escape ( $form ['is_approval_required'] ) . "', is_approved = '" . $this->db->escape ( $form ['is_approved'] ) . "', phone_device_id = '" . $this->db->escape ( $form ['phone_device_id'] ) . "', is_android = '" . $this->db->escape ( $form ['is_android'] ) . "', form_parent_id = '" . $this->db->escape ( $form ['form_parent_id'] ) . "', form_design_parent_id = '" . $this->db->escape ( $form ['form_design_parent_id'] ) . "', page_number = '" . $this->db->escape ( $form ['page_number'] ) . "', status = '" . $this->db->escape ( $form ['status'] ) . "', sync_dashboard = '" . $this->db->escape ( $form ['sync_dashboard'] ) . "', user_file = '" . $this->db->escape ( $form ['user_file'] ) . "', is_user_face = '" . $this->db->escape ( $form ['is_user_face'] ) . "', destination_facilities_id = '" . $this->db->escape ( $form ['destination_facilities_id'] ) . "', html_file_url = '" . $this->db->escape ( $form ['html_file_url'] ) . "' ";
							$this->newdb->query ( $sql );
						}
					}
					
					$livetaskforms = $this->model_syndb_syndb->gettaskformsByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livetaskforms != null && $livetaskforms != "") {
						$this->model_syndb_syndb->deletegettaskformsMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$taskforms = $this->model_syndb_syndb->gettaskforms ( $note ['notes_id'], $config_unique_id );
					
					if ($taskforms != null && $taskforms != "") {
						foreach ( $taskforms as $taskform ) {
							$ttotaltasks = $ttotaltasks + 1;
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_task SET notes_by_task_id = '" . $taskform ['notes_by_task_id'] . "', notes_id = '" . $taskform ['notes_id'] . "', locations_id = '" . $taskform ['locations_id'] . "', task_type = '" . $taskform ['task_type'] . "', task_content = '" . $this->db->escape ( $taskform ['task_content'] ) . "', user_id = '" . $this->db->escape ( $taskform ['user_id'] ) . "', date_added = '" . $taskform ['date_added'] . "', signature = '" . $taskform ['signature'] . "', notes_pin = '" . $taskform ['notes_pin'] . "', notes_type = '" . $taskform ['notes_type'] . "', task_time = '" . $taskform ['task_time'] . "', media_url = '" . $taskform ['media_url'] . "', capacity = '" . $taskform ['capacity'] . "', location_name = '" . $this->db->escape ( $taskform ['location_name'] ) . "', location_type = '" . $taskform ['location_type'] . "', notes_task_type = '" . $taskform ['notes_task_type'] . "', tags_id = '" . $taskform ['tags_id'] . "', drug_name = '" . $this->db->escape ( $taskform ['drug_name'] ) . "', dose = '" . $taskform ['dose'] . "', drug_type = '" . $taskform ['drug_type'] . "', quantity = '" . $taskform ['quantity'] . "', frequency = '" . $taskform ['frequency'] . "', instructions = '" . $this->db->escape ( $taskform ['instructions'] ) . "', count = '" . $taskform ['count'] . "', createtask_by_group_id = '" . $taskform ['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape ( $taskform ['task_comments'] ) . "', medication_attach_url = '" . $taskform ['medication_attach_url'] . "', medication_file_upload = '" . $taskform ['medication_file_upload'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $taskform ['facilities_id'] . "', tags_medication_id = '" . $taskform ['tags_medication_id'] . "', tags_medication_details_id = '" . $taskform ['tags_medication_details_id'] . "', task_customlistvalues_id = '" . $taskform ['task_customlistvalues_id'] . "', tags_ids = '" . $taskform ['tags_ids'] . "', room_current_date_time = '" . $taskform ['room_current_date_time'] . "', complete_status = '" . $taskform ['complete_status'] . "', role_call = '" . $taskform ['role_call'] . "', out_tags_ids = '" . $taskform ['out_tags_ids'] . "', out_capacity = '" . $taskform ['out_capacity'] . "', sync_dashboard = '" . $taskform ['sync_dashboard'] . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $taskform ['notes_id'] . "', locations_id = '" . $taskform ['locations_id'] . "', task_type = '" . $taskform ['task_type'] . "', task_content = '" . $this->db->escape ( $taskform ['task_content'] ) . "', user_id = '" . $this->db->escape ( $taskform ['user_id'] ) . "', date_added = '" . $taskform ['date_added'] . "', signature = '" . $taskform ['signature'] . "', notes_pin = '" . $taskform ['notes_pin'] . "', notes_type = '" . $taskform ['notes_type'] . "', task_time = '" . $taskform ['task_time'] . "', media_url = '" . $taskform ['media_url'] . "', capacity = '" . $taskform ['capacity'] . "', location_name = '" . $this->db->escape ( $taskform ['location_name'] ) . "', location_type = '" . $taskform ['location_type'] . "', notes_task_type = '" . $taskform ['notes_task_type'] . "', tags_id = '" . $taskform ['tags_id'] . "', drug_name = '" . $this->db->escape ( $taskform ['drug_name'] ) . "', dose = '" . $taskform ['dose'] . "', drug_type = '" . $taskform ['drug_type'] . "', quantity = '" . $taskform ['quantity'] . "', frequency = '" . $taskform ['frequency'] . "', instructions = '" . $this->db->escape ( $taskform ['instructions'] ) . "', count = '" . $taskform ['count'] . "', createtask_by_group_id = '" . $taskform ['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape ( $taskform ['task_comments'] ) . "', medication_attach_url = '" . $taskform ['medication_attach_url'] . "', medication_file_upload = '" . $taskform ['medication_file_upload'] . "', unique_id = '" . $config_unique_id . "', facilities_id = '" . $taskform ['facilities_id'] . "', tags_medication_id = '" . $taskform ['tags_medication_id'] . "', tags_medication_details_id = '" . $taskform ['tags_medication_details_id'] . "', task_customlistvalues_id = '" . $taskform ['task_customlistvalues_id'] . "', tags_ids = '" . $taskform ['tags_ids'] . "', room_current_date_time = '" . $taskform ['room_current_date_time'] . "', complete_status = '" . $taskform ['complete_status'] . "', role_call = '" . $taskform ['role_call'] . "', out_tags_ids = '" . $taskform ['out_tags_ids'] . "', out_capacity = '" . $taskform ['out_capacity'] . "', sync_dashboard = '" . $taskform ['sync_dashboard'] . "' ";
							$this->newdb->query ( $sql );
						}
					}
					
					$livetags = $this->model_syndb_syndb->gettagsByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livetags != null && $livetags != "") {
						$this->model_syndb_syndb->deletegettagsMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$ntags = $this->model_syndb_syndb->gettags ( $note ['notes_id'], $config_unique_id );
					
					if ($ntags != null && $ntags != "") {
						foreach ( $ntags as $ntag ) {
							
							$ttotaltags = $ttotaltags + 1;
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_tags SET notes_tags_id = '" . $ntag ['notes_tags_id'] . "', emp_tag_id = '" . $ntag ['emp_tag_id'] . "', tags_id = '" . $ntag ['tags_id'] . "', notes_id = '" . $ntag ['notes_id'] . "', user_id = '" . $ntag ['user_id'] . "', date_added = '" . $ntag ['date_added'] . "', signature = '" . $ntag ['signature'] . "', signature_image = '" . $ntag ['signature_image'] . "', notes_pin = '" . $ntag ['notes_pin'] . "', notes_type = '" . $ntag ['notes_type'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $ntag ['facilities_id'] . "', is_census = '" . $ntag ['is_census'] . "', lunch = '" . $ntag ['lunch'] . "', dinner = '" . $ntag ['dinner'] . "', breakfast = '" . $ntag ['breakfast'] . "', refused = '" . $ntag ['refused'] . "', phone_device_id = '" . $ntag ['phone_device_id'] . "', is_android = '" . $ntag ['is_android'] . "', forms_id = '" . $ntag ['forms_id'] . "', user_file = '" . $ntag ['user_file'] . "', is_user_face = '" . $ntag ['is_user_face'] . "', destination_id = '" . $ntag ['destination_id'] . "', destination_date = '" . $ntag ['destination_date'] . "', destination_status = '" . $ntag ['destination_status'] . "'
							ON DUPLICATE KEY UPDATE 
							emp_tag_id = '" . $ntag ['emp_tag_id'] . "', tags_id = '" . $ntag ['tags_id'] . "', notes_id = '" . $ntag ['notes_id'] . "', user_id = '" . $ntag ['user_id'] . "', date_added = '" . $ntag ['date_added'] . "', signature = '" . $ntag ['signature'] . "', signature_image = '" . $ntag ['signature_image'] . "', notes_pin = '" . $ntag ['notes_pin'] . "', notes_type = '" . $ntag ['notes_type'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $ntag ['facilities_id'] . "', is_census = '" . $ntag ['is_census'] . "', lunch = '" . $ntag ['lunch'] . "', dinner = '" . $ntag ['dinner'] . "', breakfast = '" . $ntag ['breakfast'] . "', refused = '" . $ntag ['refused'] . "', phone_device_id = '" . $ntag ['phone_device_id'] . "', is_android = '" . $ntag ['is_android'] . "', forms_id = '" . $ntag ['forms_id'] . "', user_file = '" . $ntag ['user_file'] . "', is_user_face = '" . $ntag ['is_user_face'] . "', destination_id = '" . $ntag ['destination_id'] . "', destination_date = '" . $ntag ['destination_date'] . "', destination_status = '" . $ntag ['destination_status'] . "' ";
							$this->newdb->query ( $sql );
						}
					}
					
					$livenoteskeywords = $this->model_syndb_syndb->getnoteskeywordsByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livenoteskeywords != null && $livenoteskeywords != "") {
						$this->model_syndb_syndb->deletenoteskeywordsMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$noteskeywords = $this->model_syndb_syndb->genoteskeywords ( $note ['notes_id'], $config_unique_id );
					
					if ($noteskeywords != null && $noteskeywords != "") {
						foreach ( $noteskeywords as $noteskeyword ) {
							
							$activenotecount = $activenotecount + 1;
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_keyword SET notes_by_keyword_id = '" . $noteskeyword ['notes_by_keyword_id'] . "', notes_id = '" . $noteskeyword ['notes_id'] . "', keyword_id = '" . $noteskeyword ['keyword_id'] . "', keyword_name = '" . $this->db->escape ( $noteskeyword ['keyword_name'] ) . "', keyword_file = '" . $noteskeyword ['keyword_file'] . "', keyword_file_url = '" . $noteskeyword ['keyword_file_url'] . "', keyword_status = '" . $noteskeyword ['keyword_status'] . "', active_tag = '" . $noteskeyword ['active_tag'] . "', facilities_id = '" . $noteskeyword ['facilities_id'] . "', date_added = '" . $noteskeyword ['date_added'] . "', unique_id = '" . $config_unique_id . "' , is_monitor_time = '" . $noteskeyword ['is_monitor_time'] . "' , user_id = '" . $noteskeyword ['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword ['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword ['sync_dashboard'] . "', type = '" . $noteskeyword ['type'] . "', comment_id = '" . $noteskeyword ['comment_id'] . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $noteskeyword ['notes_id'] . "', keyword_id = '" . $noteskeyword ['keyword_id'] . "', keyword_name = '" . $this->db->escape ( $noteskeyword ['keyword_name'] ) . "', keyword_file = '" . $noteskeyword ['keyword_file'] . "', keyword_file_url = '" . $noteskeyword ['keyword_file_url'] . "', keyword_status = '" . $noteskeyword ['keyword_status'] . "', active_tag = '" . $noteskeyword ['active_tag'] . "', facilities_id = '" . $noteskeyword ['facilities_id'] . "', date_added = '" . $noteskeyword ['date_added'] . "', unique_id = '" . $config_unique_id . "', is_monitor_time = '" . $noteskeyword ['is_monitor_time'] . "' , user_id = '" . $noteskeyword ['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword ['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword ['sync_dashboard'] . "', type = '" . $noteskeyword ['type'] . "', comment_id = '" . $noteskeyword ['comment_id'] . "'
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$notescensus_detail = $this->model_syndb_syndb->genotesscensus_details ( $note ['notes_id'], $config_unique_id );
					
					if ($notescensus_detail != null && $notescensus_detail != "") {
						
						$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_census_detail SET notes_census_detail_id = '" . $notescensus_detail ['notes_census_detail_id'] . "', notes_id = '" . $notescensus_detail ['notes_id'] . "', tags_id = '" . $notescensus_detail ['tags_id'] . "', shift_id = '" . $notescensus_detail ['shift_id'] . "', date_added = '" . $notescensus_detail ['date_added'] . "', census_date = '" . $notescensus_detail ['census_date'] . "', team_leader = '" . $this->db->escape ( $notescensus_detail ['team_leader'] ) . "', direct_care = '" . $this->db->escape ( $notescensus_detail ['direct_care'] ) . "', comment_box = '" . $this->db->escape ( $notescensus_detail ['comment_box'] ) . "', spm = '" . $this->db->escape ( $notescensus_detail ['spm'] ) . "', as_spm = '" . $this->db->escape ( $notescensus_detail ['as_spm'] ) . "', case_manager = '" . $this->db->escape ( $notescensus_detail ['case_manager'] ) . "', food_services = '" . $this->db->escape ( $notescensus_detail ['food_services'] ) . "', educational_staff = '" . $this->db->escape ( $notescensus_detail ['educational_staff'] ) . "', screenings = '" . $this->db->escape ( $notescensus_detail ['screenings'] ) . "', intakes = '" . $this->db->escape ( $notescensus_detail ['intakes'] ) . "', discharge = '" . $this->db->escape ( $notescensus_detail ['discharge'] ) . "', offsite = '" . $this->db->escape ( $notescensus_detail ['offsite'] ) . "', in_house = '" . $this->db->escape ( $notescensus_detail ['in_house'] ) . "', males = '" . $this->db->escape ( $notescensus_detail ['males'] ) . "', females = '" . $this->db->escape ( $notescensus_detail ['females'] ) . "', total = '" . $this->db->escape ( $notescensus_detail ['total'] ) . "', end_of_shift_status = '" . $this->db->escape ( $notescensus_detail ['end_of_shift_status'] ) . "', staff = '" . $this->db->escape ( $notescensus_detail ['staff'] ) . "', facilities_id = '" . $notescensus_detail ['facilities_id'] . "', unique_id = '" . $config_unique_id . "' 
						ON DUPLICATE KEY UPDATE 
						notes_id = '" . $notescensus_detail ['notes_id'] . "', tags_id = '" . $notescensus_detail ['tags_id'] . "', shift_id = '" . $notescensus_detail ['shift_id'] . "', date_added = '" . $notescensus_detail ['date_added'] . "', census_date = '" . $notescensus_detail ['census_date'] . "', team_leader = '" . $this->db->escape ( $notescensus_detail ['team_leader'] ) . "', direct_care = '" . $this->db->escape ( $notescensus_detail ['direct_care'] ) . "', comment_box = '" . $this->db->escape ( $notescensus_detail ['comment_box'] ) . "', spm = '" . $this->db->escape ( $notescensus_detail ['spm'] ) . "', as_spm = '" . $this->db->escape ( $notescensus_detail ['as_spm'] ) . "', case_manager = '" . $this->db->escape ( $notescensus_detail ['case_manager'] ) . "', food_services = '" . $this->db->escape ( $notescensus_detail ['food_services'] ) . "', educational_staff = '" . $this->db->escape ( $notescensus_detail ['educational_staff'] ) . "', screenings = '" . $this->db->escape ( $notescensus_detail ['screenings'] ) . "', intakes = '" . $this->db->escape ( $notescensus_detail ['intakes'] ) . "', discharge = '" . $this->db->escape ( $notescensus_detail ['discharge'] ) . "', offsite = '" . $this->db->escape ( $notescensus_detail ['offsite'] ) . "', in_house = '" . $this->db->escape ( $notescensus_detail ['in_house'] ) . "', males = '" . $this->db->escape ( $notescensus_detail ['males'] ) . "', females = '" . $this->db->escape ( $notescensus_detail ['females'] ) . "', total = '" . $this->db->escape ( $notescensus_detail ['total'] ) . "', end_of_shift_status = '" . $this->db->escape ( $notescensus_detail ['end_of_shift_status'] ) . "', staff = '" . $this->db->escape ( $notescensus_detail ['staff'] ) . "', facilities_id = '" . $notescensus_detail ['facilities_id'] . "', unique_id = '" . $config_unique_id . "' 
							";
						$this->newdb->query ( $sql );
					}
					
					$livesharenotes = $this->model_syndb_syndb->getsharenotesByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livesharenotes != null && $livesharenotes != "") {
						$this->model_syndb_syndb->deletesharenotesMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$sharenotes = $this->model_syndb_syndb->gesharenotes ( $note ['notes_id'], $config_unique_id );
					
					if ($sharenotes != null && $sharenotes != "") {
						foreach ( $sharenotes as $sharenote ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "share_notes SET share_notes_id = '" . $sharenote ['share_notes_id'] . "', notes_id = '" . $sharenote ['notes_id'] . "', user_id = '" . $this->db->escape ( $sharenote ['user_id'] ) . "', notes_pin = '" . $sharenote ['notes_pin'] . "', email = '" . $sharenote ['email'] . "', date_added = '" . $sharenote ['date_added'] . "', share_type = '" . $sharenote ['share_type'] . "', share_notes_otp = '" . $sharenote ['share_notes_otp'] . "', phone_device_id = '" . $sharenote ['phone_device_id'] . "', device_unique_id = '" . $sharenote ['device_unique_id'] . "', is_android = '" . $sharenote ['is_android'] . "'
							
							, unique_id = '" . $config_unique_id . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $sharenote ['notes_id'] . "', user_id = '" . $this->db->escape ( $sharenote ['user_id'] ) . "', notes_pin = '" . $sharenote ['notes_pin'] . "', email = '" . $sharenote ['email'] . "', date_added = '" . $sharenote ['date_added'] . "', share_type = '" . $sharenote ['share_type'] . "', share_notes_otp = '" . $sharenote ['share_notes_otp'] . "', phone_device_id = '" . $sharenote ['phone_device_id'] . "', device_unique_id = '" . $sharenote ['device_unique_id'] . "', is_android = '" . $sharenote ['is_android'] . "'
							
							, unique_id = '" . $config_unique_id . "' ";
							$this->newdb->query ( $sql );
						}
					}
					
					$liveapproval_tasknotes = $this->model_syndb_syndb->getapproval_tasknotesByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($liveapproval_tasknotes != null && $liveapproval_tasknotes != "") {
						$this->model_syndb_syndb->deleteapproval_tasknotesMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$approval_tasknotes = $this->model_syndb_syndb->geapproval_tasknotes ( $note ['notes_id'], $config_unique_id );
					
					if ($approval_tasknotes != null && $approval_tasknotes != "") {
						foreach ( $approval_tasknotes as $approval_tasknote ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_approval_task SET 
							id = '" . $approval_tasknote ['id'] . "'
							, facilities_id = '" . $approval_tasknote ['facilities_id'] . "'
							, task_date = '" . $this->db->escape ( $approval_tasknote ['task_date'] ) . "'
							, task_time = '" . $this->db->escape ( $approval_tasknote ['task_time'] ) . "'
							, date_added = '" . $this->db->escape ( $approval_tasknote ['date_added'] ) . "'
							, tasktype = '" . $this->db->escape ( $approval_tasknote ['tasktype'] ) . "'
							, description = '" . $this->db->escape ( $approval_tasknote ['description'] ) . "'
							, assign_to = '" . $this->db->escape ( $approval_tasknote ['assign_to'] ) . "'
							
							, recurrence = '" . $this->db->escape ( $approval_tasknote ['recurrence'] ) . "'
							, end_recurrence_date = '" . $this->db->escape ( $approval_tasknote ['end_recurrence_date'] ) . "'
							, recurnce_hrly = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly'] ) . "'
							, recurnce_week = '" . $this->db->escape ( $approval_tasknote ['recurnce_week'] ) . "'
							, recurnce_month = '" . $this->db->escape ( $approval_tasknote ['recurnce_month'] ) . "'
							, recurnce_day = '" . $this->db->escape ( $approval_tasknote ['recurnce_day'] ) . "'
							, taskadded = '" . $this->db->escape ( $approval_tasknote ['taskadded'] ) . "'
							, endtime = '" . $this->db->escape ( $approval_tasknote ['endtime'] ) . "'
							, task_alert = '" . $this->db->escape ( $approval_tasknote ['task_alert'] ) . "'
							, alert_type_none = '" . $this->db->escape ( $approval_tasknote ['alert_type_none'] ) . "'
							, alert_type_sms = '" . $this->db->escape ( $approval_tasknote ['alert_type_sms'] ) . "'
							, alert_type_notification = '" . $this->db->escape ( $approval_tasknote ['alert_type_notification'] ) . "'
							, alert_type_email = '" . $this->db->escape ( $approval_tasknote ['alert_type_email'] ) . "'
							, checklist = '" . $this->db->escape ( $approval_tasknote ['checklist'] ) . "'
							, snooze_time = '" . $this->db->escape ( $approval_tasknote ['snooze_time'] ) . "'
							, snooze_dismiss = '" . $this->db->escape ( $approval_tasknote ['snooze_dismiss'] ) . "'
							
							, rules_task = '" . $this->db->escape ( $approval_tasknote ['rules_task'] ) . "'
							, message_sid = '" . $this->db->escape ( $approval_tasknote ['message_sid'] ) . "'
							, send_sms = '" . $this->db->escape ( $approval_tasknote ['send_sms'] ) . "'
							, send_email = '" . $this->db->escape ( $approval_tasknote ['send_email'] ) . "'
							, send_notification = '" . $this->db->escape ( $approval_tasknote ['send_notification'] ) . "'
							, task_form_id = '" . $this->db->escape ( $approval_tasknote ['task_form_id'] ) . "'
							, tags_id = '" . $this->db->escape ( $approval_tasknote ['tags_id'] ) . "'
							, pickup_facilities_id = '" . $this->db->escape ( $approval_tasknote ['pickup_facilities_id'] ) . "'
							, pickup_locations_address = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_address'] ) . "'
							, pickup_locations_time = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_time'] ) . "'
							, pickup_locations_latitude = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_latitude'] ) . "'
							, pickup_locations_longitude = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_longitude'] ) . "'
							, dropoff_facilities_id = '" . $this->db->escape ( $approval_tasknote ['dropoff_facilities_id'] ) . "'
							, dropoff_locations_address = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_address'] ) . "'
							, dropoff_locations_time = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_time'] ) . "'
							
							, dropoff_locations_latitude = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_latitude'] ) . "'
							, dropoff_locations_longitude = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_longitude'] ) . "'
							, transport_tags = '" . $this->db->escape ( $approval_tasknote ['transport_tags'] ) . "'
							, locations_id = '" . $this->db->escape ( $approval_tasknote ['locations_id'] ) . "'
							, task_complettion = '" . $this->db->escape ( $approval_tasknote ['task_complettion'] ) . "'
							, device_id = '" . $this->db->escape ( $approval_tasknote ['device_id'] ) . "'
							, customs_forms_id = '" . $this->db->escape ( $approval_tasknote ['customs_forms_id'] ) . "'
							, emp_tag_id = '" . $this->db->escape ( $approval_tasknote ['emp_tag_id'] ) . "'
							, medication_tags = '" . $this->db->escape ( $approval_tasknote ['medication_tags'] ) . "'
							, completion_alert = '" . $this->db->escape ( $approval_tasknote ['completion_alert'] ) . "'
							, completion_alert_type_sms = '" . $this->db->escape ( $approval_tasknote ['completion_alert_type_sms'] ) . "'
							, completion_alert_type_email = '" . $this->db->escape ( $approval_tasknote ['completion_alert_type_email'] ) . "'
							, user_roles = '" . $this->db->escape ( $approval_tasknote ['user_roles'] ) . "'
							, userids = '" . $this->db->escape ( $approval_tasknote ['userids'] ) . "'
							, recurnce_hrly_perpetual = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly_perpetual'] ) . "'
							, due_date_time = '" . $this->db->escape ( $approval_tasknote ['due_date_time'] ) . "'
							, task_status = '" . $this->db->escape ( $approval_tasknote ['task_status'] ) . "'
							, task_completed = '" . $this->db->escape ( $approval_tasknote ['task_completed'] ) . "'
							, recurnce_hrly_recurnce = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly_recurnce'] ) . "'
							, visitation_tags = '" . $this->db->escape ( $approval_tasknote ['visitation_tags'] ) . "'
							, visitation_tag_id = '" . $this->db->escape ( $approval_tasknote ['visitation_tag_id'] ) . "'
							, visitation_start_facilities_id = '" . $this->db->escape ( $approval_tasknote ['visitation_start_facilities_id'] ) . "'
							, visitation_start_address = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address'] ) . "'
							, visitation_start_time = '" . $this->db->escape ( $approval_tasknote ['visitation_start_time'] ) . "'
							, visitation_start_address_latitude = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address_latitude'] ) . "'
							, visitation_start_address_longitude = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address_longitude'] ) . "'
							, visitation_appoitment_facilities_id = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_facilities_id'] ) . "'
							, visitation_appoitment_address = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address'] ) . "'							
							, visitation_appoitment_time = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_time'] ) . "'
							, visitation_appoitment_address_latitude = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address_latitude'] ) . "'
							, visitation_appoitment_address_longitude = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address_longitude'] ) . "'
							, completed_times = '" . $this->db->escape ( $approval_tasknote ['completed_times'] ) . "'							
							, completed_alert = '" . $this->db->escape ( $approval_tasknote ['completed_alert'] ) . "'
							, completed_late_alert = '" . $this->db->escape ( $approval_tasknote ['completed_late_alert'] ) . "'
							, incomplete_alert = '" . $this->db->escape ( $approval_tasknote ['incomplete_alert'] ) . "'
							, deleted_alert = '" . $this->db->escape ( $approval_tasknote ['deleted_alert'] ) . "'
							, end_perpetual_task = '" . $this->db->escape ( $approval_tasknote ['end_perpetual_task'] ) . "'
							, is_transport = '" . $this->db->escape ( $approval_tasknote ['is_transport'] ) . "'
							, parent_id = '" . $this->db->escape ( $approval_tasknote ['parent_id'] ) . "'
							, is_send_reminder = '" . $this->db->escape ( $approval_tasknote ['is_send_reminder'] ) . "'
							, attachement_form = '" . $this->db->escape ( $approval_tasknote ['attachement_form'] ) . "'
							, tasktype_form_id = '" . $this->db->escape ( $approval_tasknote ['tasktype_form_id'] ) . "'
							, tagstatus_id = '" . $this->db->escape ( $approval_tasknote ['tagstatus_id'] ) . "'
							, task_group_by = '" . $this->db->escape ( $approval_tasknote ['task_group_by'] ) . "'
							, end_task = '" . $this->db->escape ( $approval_tasknote ['end_task'] ) . "'
							, formrules_id = '" . $this->db->escape ( $approval_tasknote ['formrules_id'] ) . "'
							, task_random_id = '" . $this->db->escape ( $approval_tasknote ['task_random_id'] ) . "'
							, form_due_date = '" . $this->db->escape ( $approval_tasknote ['form_due_date'] ) . "'
							, form_due_date_after = '" . $this->db->escape ( $approval_tasknote ['form_due_date_after'] ) . "'
							, recurnce_m = '" . $this->db->escape ( $approval_tasknote ['recurnce_m'] ) . "'
							, phone_device_id = '" . $this->db->escape ( $approval_tasknote ['phone_device_id'] ) . "'
							, enable_requires_approval = '" . $this->db->escape ( $approval_tasknote ['enable_requires_approval'] ) . "'
							, approval_taskid = '" . $this->db->escape ( $approval_tasknote ['approval_taskid'] ) . "'
							, notes_id = '" . $this->db->escape ( $approval_tasknote ['notes_id'] ) . "'
							, status = '" . $this->db->escape ( $approval_tasknote ['status'] ) . "'
							
							, iswaypoint = '" . $this->db->escape ( $approval_tasknote ['iswaypoint'] ) . "'
							, original_task_time = '" . $this->db->escape ( $approval_tasknote ['original_task_time'] ) . "'
							
							, response = '" . $this->db->escape ( $approval_tasknote ['response'] ) . "'
							, distance_text = '" . $this->db->escape ( $approval_tasknote ['distance_text'] ) . "'
							, distance_value = '" . $this->db->escape ( $approval_tasknote ['distance_value'] ) . "'
							, duration_text = '" . $this->db->escape ( $approval_tasknote ['duration_text'] ) . "'
							, duration_value = '" . $this->db->escape ( $approval_tasknote ['duration_value'] ) . "'
							
							, bed_check_location_ids = '" . $this->db->escape ( $approval_tasknote ['bed_check_location_ids'] ) . "'
							
							, is_approval_required_forms_id = '" . $this->db->escape ( $approval_tasknote ['is_approval_required_forms_id'] ) . "'
							, is_approval_required_tags_id = '" . $this->db->escape ( $approval_tasknote ['is_approval_required_tags_id'] ) . "'
							, is_android = '" . $this->db->escape ( $approval_tasknote ['is_android'] ) . "'
							, customer_key = '" . $this->db->escape ( $approval_tasknote ['customer_key'] ) . "'
							, complete_status = '" . $this->db->escape ( $approval_tasknote ['complete_status'] ) . "'
							, weekly_interval = '" . $this->db->escape ( $approval_tasknote ['weekly_interval'] ) . "'
							, is_create_task = '" . $this->db->escape ( $approval_tasknote ['is_create_task'] ) . "'
							, required_approval = '" . $this->db->escape ( $approval_tasknote ['required_approval'] ) . "'
							, linked_id = '" . $this->db->escape ( $approval_tasknote ['linked_id'] ) . "'
							, formreturn_id = '" . $this->db->escape ( $approval_tasknote ['formreturn_id'] ) . "'
							
							, target_facilities_id = '" . $this->db->escape ( $approval_tasknote ['target_facilities_id'] ) . "'
							, is_pause = '" . $this->db->escape ( $approval_tasknote ['is_pause'] ) . "'
							, pause_date = '" . $this->db->escape ( $approval_tasknote ['pause_date'] ) . "'
							, pause_time = '" . $this->db->escape ( $approval_tasknote ['pause_time'] ) . "'
							, user_role_assign_ids = '" . $this->db->escape ( $approval_tasknote ['user_role_assign_ids'] ) . "'
							, assign_to_type = '" . $this->db->escape ( $approval_tasknote ['assign_to_type'] ) . "'
							
							, unique_id = '" . $config_unique_id . "'
							 
							 ON DUPLICATE KEY UPDATE  
							 
							 facilities_id = '" . $approval_tasknote ['facilities_id'] . "'
							, task_date = '" . $this->db->escape ( $approval_tasknote ['task_date'] ) . "'
							, task_time = '" . $this->db->escape ( $approval_tasknote ['task_time'] ) . "'
							, date_added = '" . $this->db->escape ( $approval_tasknote ['date_added'] ) . "'
							, tasktype = '" . $this->db->escape ( $approval_tasknote ['tasktype'] ) . "'
							, description = '" . $this->db->escape ( $approval_tasknote ['description'] ) . "'
							, assign_to = '" . $this->db->escape ( $approval_tasknote ['assign_to'] ) . "'
							
							, recurrence = '" . $this->db->escape ( $approval_tasknote ['recurrence'] ) . "'
							, end_recurrence_date = '" . $this->db->escape ( $approval_tasknote ['end_recurrence_date'] ) . "'
							, recurnce_hrly = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly'] ) . "'
							, recurnce_week = '" . $this->db->escape ( $approval_tasknote ['recurnce_week'] ) . "'
							, recurnce_month = '" . $this->db->escape ( $approval_tasknote ['recurnce_month'] ) . "'
							, recurnce_day = '" . $this->db->escape ( $approval_tasknote ['recurnce_day'] ) . "'
							, taskadded = '" . $this->db->escape ( $approval_tasknote ['taskadded'] ) . "'
							, endtime = '" . $this->db->escape ( $approval_tasknote ['endtime'] ) . "'
							, task_alert = '" . $this->db->escape ( $approval_tasknote ['task_alert'] ) . "'
							, alert_type_none = '" . $this->db->escape ( $approval_tasknote ['alert_type_none'] ) . "'
							, alert_type_sms = '" . $this->db->escape ( $approval_tasknote ['alert_type_sms'] ) . "'
							, alert_type_notification = '" . $this->db->escape ( $approval_tasknote ['alert_type_notification'] ) . "'
							, alert_type_email = '" . $this->db->escape ( $approval_tasknote ['alert_type_email'] ) . "'
							, checklist = '" . $this->db->escape ( $approval_tasknote ['checklist'] ) . "'
							, snooze_time = '" . $this->db->escape ( $approval_tasknote ['snooze_time'] ) . "'
							, snooze_dismiss = '" . $this->db->escape ( $approval_tasknote ['snooze_dismiss'] ) . "'
							
							, rules_task = '" . $this->db->escape ( $approval_tasknote ['rules_task'] ) . "'
							, message_sid = '" . $this->db->escape ( $approval_tasknote ['message_sid'] ) . "'
							, send_sms = '" . $this->db->escape ( $approval_tasknote ['send_sms'] ) . "'
							, send_email = '" . $this->db->escape ( $approval_tasknote ['send_email'] ) . "'
							, send_notification = '" . $this->db->escape ( $approval_tasknote ['send_notification'] ) . "'
							, task_form_id = '" . $this->db->escape ( $approval_tasknote ['task_form_id'] ) . "'
							, tags_id = '" . $this->db->escape ( $approval_tasknote ['tags_id'] ) . "'
							, pickup_facilities_id = '" . $this->db->escape ( $approval_tasknote ['pickup_facilities_id'] ) . "'
							, pickup_locations_address = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_address'] ) . "'
							, pickup_locations_time = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_time'] ) . "'
							, pickup_locations_latitude = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_latitude'] ) . "'
							, pickup_locations_longitude = '" . $this->db->escape ( $approval_tasknote ['pickup_locations_longitude'] ) . "'
							, dropoff_facilities_id = '" . $this->db->escape ( $approval_tasknote ['dropoff_facilities_id'] ) . "'
							, dropoff_locations_address = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_address'] ) . "'
							, dropoff_locations_time = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_time'] ) . "'
							
							, dropoff_locations_latitude = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_latitude'] ) . "'
							, dropoff_locations_longitude = '" . $this->db->escape ( $approval_tasknote ['dropoff_locations_longitude'] ) . "'
							, transport_tags = '" . $this->db->escape ( $approval_tasknote ['transport_tags'] ) . "'
							, locations_id = '" . $this->db->escape ( $approval_tasknote ['locations_id'] ) . "'
							, task_complettion = '" . $this->db->escape ( $approval_tasknote ['task_complettion'] ) . "'
							, device_id = '" . $this->db->escape ( $approval_tasknote ['device_id'] ) . "'
							, customs_forms_id = '" . $this->db->escape ( $approval_tasknote ['customs_forms_id'] ) . "'
							, emp_tag_id = '" . $this->db->escape ( $approval_tasknote ['emp_tag_id'] ) . "'
							, medication_tags = '" . $this->db->escape ( $approval_tasknote ['medication_tags'] ) . "'
							, completion_alert = '" . $this->db->escape ( $approval_tasknote ['completion_alert'] ) . "'
							, completion_alert_type_sms = '" . $this->db->escape ( $approval_tasknote ['completion_alert_type_sms'] ) . "'
							, completion_alert_type_email = '" . $this->db->escape ( $approval_tasknote ['completion_alert_type_email'] ) . "'
							, user_roles = '" . $this->db->escape ( $approval_tasknote ['user_roles'] ) . "'
							, userids = '" . $this->db->escape ( $approval_tasknote ['userids'] ) . "'
							, recurnce_hrly_perpetual = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly_perpetual'] ) . "'
							, due_date_time = '" . $this->db->escape ( $approval_tasknote ['due_date_time'] ) . "'
							, task_status = '" . $this->db->escape ( $approval_tasknote ['task_status'] ) . "'
							, task_completed = '" . $this->db->escape ( $approval_tasknote ['task_completed'] ) . "'
							, recurnce_hrly_recurnce = '" . $this->db->escape ( $approval_tasknote ['recurnce_hrly_recurnce'] ) . "'
							, visitation_tags = '" . $this->db->escape ( $approval_tasknote ['visitation_tags'] ) . "'
							, visitation_tag_id = '" . $this->db->escape ( $approval_tasknote ['visitation_tag_id'] ) . "'
							, visitation_start_facilities_id = '" . $this->db->escape ( $approval_tasknote ['visitation_start_facilities_id'] ) . "'
							, visitation_start_address = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address'] ) . "'
							, visitation_start_time = '" . $this->db->escape ( $approval_tasknote ['visitation_start_time'] ) . "'
							, visitation_start_address_latitude = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address_latitude'] ) . "'
							, visitation_start_address_longitude = '" . $this->db->escape ( $approval_tasknote ['visitation_start_address_longitude'] ) . "'
							, visitation_appoitment_facilities_id = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_facilities_id'] ) . "'
							, visitation_appoitment_address = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address'] ) . "'							
							, visitation_appoitment_time = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_time'] ) . "'
							, visitation_appoitment_address_latitude = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address_latitude'] ) . "'
							, visitation_appoitment_address_longitude = '" . $this->db->escape ( $approval_tasknote ['visitation_appoitment_address_longitude'] ) . "'
							, completed_times = '" . $this->db->escape ( $approval_tasknote ['completed_times'] ) . "'							
							, completed_alert = '" . $this->db->escape ( $approval_tasknote ['completed_alert'] ) . "'
							, completed_late_alert = '" . $this->db->escape ( $approval_tasknote ['completed_late_alert'] ) . "'
							, incomplete_alert = '" . $this->db->escape ( $approval_tasknote ['incomplete_alert'] ) . "'
							, deleted_alert = '" . $this->db->escape ( $approval_tasknote ['deleted_alert'] ) . "'
							, end_perpetual_task = '" . $this->db->escape ( $approval_tasknote ['end_perpetual_task'] ) . "'
							, is_transport = '" . $this->db->escape ( $approval_tasknote ['is_transport'] ) . "'
							, parent_id = '" . $this->db->escape ( $approval_tasknote ['parent_id'] ) . "'
							, is_send_reminder = '" . $this->db->escape ( $approval_tasknote ['is_send_reminder'] ) . "'
							, attachement_form = '" . $this->db->escape ( $approval_tasknote ['attachement_form'] ) . "'
							, tasktype_form_id = '" . $this->db->escape ( $approval_tasknote ['tasktype_form_id'] ) . "'
							, tagstatus_id = '" . $this->db->escape ( $approval_tasknote ['tagstatus_id'] ) . "'
							, task_group_by = '" . $this->db->escape ( $approval_tasknote ['task_group_by'] ) . "'
							, end_task = '" . $this->db->escape ( $approval_tasknote ['end_task'] ) . "'
							, formrules_id = '" . $this->db->escape ( $approval_tasknote ['formrules_id'] ) . "'
							, task_random_id = '" . $this->db->escape ( $approval_tasknote ['task_random_id'] ) . "'
							, form_due_date = '" . $this->db->escape ( $approval_tasknote ['form_due_date'] ) . "'
							, form_due_date_after = '" . $this->db->escape ( $approval_tasknote ['form_due_date_after'] ) . "'
							, recurnce_m = '" . $this->db->escape ( $approval_tasknote ['recurnce_m'] ) . "'
							, phone_device_id = '" . $this->db->escape ( $approval_tasknote ['phone_device_id'] ) . "'
							, enable_requires_approval = '" . $this->db->escape ( $approval_tasknote ['enable_requires_approval'] ) . "'
							, approval_taskid = '" . $this->db->escape ( $approval_tasknote ['approval_taskid'] ) . "'
							, notes_id = '" . $this->db->escape ( $approval_tasknote ['notes_id'] ) . "'
							, status = '" . $this->db->escape ( $approval_tasknote ['status'] ) . "'
							, iswaypoint = '" . $this->db->escape ( $approval_tasknote ['iswaypoint'] ) . "'
							, original_task_time = '" . $this->db->escape ( $approval_tasknote ['original_task_time'] ) . "'
							
							, response = '" . $this->db->escape ( $approval_tasknote ['response'] ) . "'
							, distance_text = '" . $this->db->escape ( $approval_tasknote ['distance_text'] ) . "'
							, distance_value = '" . $this->db->escape ( $approval_tasknote ['distance_value'] ) . "'
							, duration_text = '" . $this->db->escape ( $approval_tasknote ['duration_text'] ) . "'
							, duration_value = '" . $this->db->escape ( $approval_tasknote ['duration_value'] ) . "'
							
							, bed_check_location_ids = '" . $this->db->escape ( $approval_tasknote ['bed_check_location_ids'] ) . "'
							
							, is_approval_required_forms_id = '" . $this->db->escape ( $approval_tasknote ['is_approval_required_forms_id'] ) . "'
							, is_approval_required_tags_id = '" . $this->db->escape ( $approval_tasknote ['is_approval_required_tags_id'] ) . "'
							, is_android = '" . $this->db->escape ( $approval_tasknote ['is_android'] ) . "'
							, customer_key = '" . $this->db->escape ( $approval_tasknote ['customer_key'] ) . "'
							, complete_status = '" . $this->db->escape ( $approval_tasknote ['complete_status'] ) . "'
							, weekly_interval = '" . $this->db->escape ( $approval_tasknote ['weekly_interval'] ) . "'
							, is_create_task = '" . $this->db->escape ( $approval_tasknote ['is_create_task'] ) . "'
							, required_approval = '" . $this->db->escape ( $approval_tasknote ['required_approval'] ) . "'
							, linked_id = '" . $this->db->escape ( $approval_tasknote ['linked_id'] ) . "'
							, formreturn_id = '" . $this->db->escape ( $approval_tasknote ['formreturn_id'] ) . "'
							
							, target_facilities_id = '" . $this->db->escape ( $approval_tasknote ['target_facilities_id'] ) . "'
							, is_pause = '" . $this->db->escape ( $approval_tasknote ['is_pause'] ) . "'
							, pause_date = '" . $this->db->escape ( $approval_tasknote ['pause_date'] ) . "'
							, pause_time = '" . $this->db->escape ( $approval_tasknote ['pause_time'] ) . "'
							, user_role_assign_ids = '" . $this->db->escape ( $approval_tasknote ['user_role_assign_ids'] ) . "'
							, assign_to_type = '" . $this->db->escape ( $approval_tasknote ['assign_to_type'] ) . "'
							
							
							, unique_id = '" . $config_unique_id . "'
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$livetravel_tasknotes = $this->model_syndb_syndb->gettravel_tasknotesByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livetravel_tasknotes != null && $livetravel_tasknotes != "") {
						$this->model_syndb_syndb->deletetravel_tasknotesMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$travel_tasknotes = $this->model_syndb_syndb->getravel_tasknotes ( $note ['notes_id'], $config_unique_id );
					
					if ($travel_tasknotes != null && $travel_tasknotes != "") {
						foreach ( $travel_tasknotes as $travel_tasknote ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_travel_task SET 
							travel_task_id = '" . $travel_tasknote ['travel_task_id'] . "'
							, notes_id = '" . $travel_tasknote ['notes_id'] . "'
							, keyword_id = '" . $this->db->escape ( $travel_tasknote ['keyword_id'] ) . "'
							, pickup_locations_address = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_address'] ) . "'
							, pickup_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_latitude'] ) . "'
							, pickup_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_longitude'] ) . "'
							, dropoff_locations_address = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_address'] ) . "'
							, dropoff_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_latitude'] ) . "'
							
							, dropoff_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_longitude'] ) . "'
							, current_locations_address = '" . $this->db->escape ( $travel_tasknote ['current_locations_address'] ) . "'
							, current_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['current_locations_latitude'] ) . "'
							, current_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['current_locations_longitude'] ) . "'
							, facilities_id = '" . $this->db->escape ( $travel_tasknote ['facilities_id'] ) . "'
							, date_added = '" . $this->db->escape ( $travel_tasknote ['date_added'] ) . "'
							, type = '" . $this->db->escape ( $travel_tasknote ['type'] ) . "'
							, google_url = '" . $this->db->escape ( $travel_tasknote ['google_url'] ) . "'
							
							, current_google_url = '" . $this->db->escape ( $travel_tasknote ['current_google_url'] ) . "'
							, tags_id = '" . $this->db->escape ( $travel_tasknote ['tags_id'] ) . "'
							, travel_state = '" . $this->db->escape ( $travel_tasknote ['travel_state'] ) . "'
							, location_tracking_url = '" . $this->db->escape ( $travel_tasknote ['location_tracking_url'] ) . "'
							, location_tracking_time_start = '" . $this->db->escape ( $travel_tasknote ['location_tracking_time_start'] ) . "'
							, location_tracking_time_end = '" . $this->db->escape ( $travel_tasknote ['location_tracking_time_end'] ) . "'
							, location_tracking_route = '" . $this->db->escape ( $travel_tasknote ['location_tracking_route'] ) . "'
							, waypoint_google_url = '" . $this->db->escape ( $travel_tasknote ['waypoint_google_url'] ) . "'
							, google_map_image_url = '" . $this->db->escape ( $travel_tasknote ['google_map_image_url'] ) . "'
							
							, pickup_locations_address_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_address_2'] ) . "'
							, pickup_locations_latitude_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_latitude_2'] ) . "'
							, pickup_locations_longitude_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_longitude_2'] ) . "'
							, dropoff_locations_address_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_address_2'] ) . "'
							, dropoff_locations_latitude_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_latitude_2'] ) . "'
							, dropoff_locations_longitude_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_longitude_2'] ) . "'
							, pick_up_tags_id = '" . $this->db->escape ( $travel_tasknote ['pick_up_tags_id'] ) . "'
							, is_pick_up = '" . $this->db->escape ( $travel_tasknote ['is_pick_up'] ) . "'
							, is_drop_off = '" . $this->db->escape ( $travel_tasknote ['is_drop_off'] ) . "'
							
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 notes_id = '" . $travel_tasknote ['notes_id'] . "'
							 , keyword_id = '" . $this->db->escape ( $travel_tasknote ['keyword_id'] ) . "'
							, pickup_locations_address = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_address'] ) . "'
							, pickup_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_latitude'] ) . "'
							, pickup_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_longitude'] ) . "'
							, dropoff_locations_address = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_address'] ) . "'
							, dropoff_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_latitude'] ) . "'
							
							, dropoff_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_longitude'] ) . "'
							, current_locations_address = '" . $this->db->escape ( $travel_tasknote ['current_locations_address'] ) . "'
							, current_locations_latitude = '" . $this->db->escape ( $travel_tasknote ['current_locations_latitude'] ) . "'
							, current_locations_longitude = '" . $this->db->escape ( $travel_tasknote ['current_locations_longitude'] ) . "'
							, facilities_id = '" . $this->db->escape ( $travel_tasknote ['facilities_id'] ) . "'
							, date_added = '" . $this->db->escape ( $travel_tasknote ['date_added'] ) . "'
							, type = '" . $this->db->escape ( $travel_tasknote ['type'] ) . "'
							, google_url = '" . $this->db->escape ( $travel_tasknote ['google_url'] ) . "'
							
							, current_google_url = '" . $this->db->escape ( $travel_tasknote ['current_google_url'] ) . "'
							, tags_id = '" . $this->db->escape ( $travel_tasknote ['tags_id'] ) . "'
							, travel_state = '" . $this->db->escape ( $travel_tasknote ['travel_state'] ) . "'
							, location_tracking_url = '" . $this->db->escape ( $travel_tasknote ['location_tracking_url'] ) . "'
							, location_tracking_time_start = '" . $this->db->escape ( $travel_tasknote ['location_tracking_time_start'] ) . "'
							, location_tracking_time_end = '" . $this->db->escape ( $travel_tasknote ['location_tracking_time_end'] ) . "'
							, location_tracking_route = '" . $this->db->escape ( $travel_tasknote ['location_tracking_route'] ) . "'
							, waypoint_google_url = '" . $this->db->escape ( $travel_tasknote ['waypoint_google_url'] ) . "'
							, google_map_image_url = '" . $this->db->escape ( $travel_tasknote ['google_map_image_url'] ) . "'
							
							, pickup_locations_address_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_address_2'] ) . "'
							, pickup_locations_latitude_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_latitude_2'] ) . "'
							, pickup_locations_longitude_2 = '" . $this->db->escape ( $travel_tasknote ['pickup_locations_longitude_2'] ) . "'
							, dropoff_locations_address_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_address_2'] ) . "'
							, dropoff_locations_latitude_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_latitude_2'] ) . "'
							, dropoff_locations_longitude_2 = '" . $this->db->escape ( $travel_tasknote ['dropoff_locations_longitude_2'] ) . "'
							, pick_up_tags_id = '" . $this->db->escape ( $travel_tasknote ['pick_up_tags_id'] ) . "'
							, is_pick_up = '" . $this->db->escape ( $travel_tasknote ['is_pick_up'] ) . "'
							, is_drop_off = '" . $this->db->escape ( $travel_tasknote ['is_drop_off'] ) . "'
							
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$livecreatetask_by_transportnotes = $this->model_syndb_syndb->getcreatetask_by_transportnotesByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($livecreatetask_by_transportnotes != null && $livecreatetask_by_transportnotes != "") {
						$this->model_syndb_syndb->deletecreatetask_by_transportnotesMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$createtask_by_transportnotes = $this->model_syndb_syndb->gecreatetask_by_transportnotes ( $note ['notes_id'], $config_unique_id );
					
					if ($createtask_by_transportnotes != null && $createtask_by_transportnotes != "") {
						foreach ( $createtask_by_transportnotes as $createtask_by_transportnote ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_createtask_by_transport SET 
							createtask_by_transport_id = '" . $createtask_by_transportnote ['createtask_by_transport_id'] . "'
							, id = '" . $createtask_by_transportnote ['id'] . "'
							, locations_address = '" . $this->db->escape ( $createtask_by_transportnote ['locations_address'] ) . "'
							, latitude = '" . $this->db->escape ( $createtask_by_transportnote ['latitude'] ) . "'
							, longitude = '" . $this->db->escape ( $createtask_by_transportnote ['longitude'] ) . "'
							, complete_status = '" . $this->db->escape ( $createtask_by_transportnote ['complete_status'] ) . "'
							, place_id = '" . $this->db->escape ( $createtask_by_transportnote ['place_id'] ) . "'
							, notes_id = '" . $this->db->escape ( $createtask_by_transportnote ['notes_id'] ) . "'
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 id = '" . $createtask_by_transportnote ['id'] . "'
							, locations_address = '" . $this->db->escape ( $createtask_by_transportnote ['locations_address'] ) . "'
							, latitude = '" . $this->db->escape ( $createtask_by_transportnote ['latitude'] ) . "'
							, longitude = '" . $this->db->escape ( $createtask_by_transportnote ['longitude'] ) . "'
							, complete_status = '" . $this->db->escape ( $createtask_by_transportnote ['complete_status'] ) . "'
							, place_id = '" . $this->db->escape ( $createtask_by_transportnote ['place_id'] ) . "'
							, notes_id = '" . $this->db->escape ( $createtask_by_transportnote ['notes_id'] ) . "'
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$notebycomments = $this->model_syndb_syndb->getnotes_by_commentByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($notebycomments != null && $notebycomments != "") {
						$this->model_syndb_syndb->deletenotes_by_commentMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$notebycomments1 = $this->model_syndb_syndb->getnotes_by_comments ( $note ['notes_id'], $config_unique_id );
					
					if ($notebycomments1 != null && $notebycomments1 != "") {
						foreach ( $notebycomments1 as $notebycomment ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_comment SET 
							comment_id = '" . $notebycomment ['comment_id'] . "'
							, notes_id = '" . $notebycomment ['notes_id'] . "'
							, facilities_id = '" . $this->db->escape ( $notebycomment ['facilities_id'] ) . "'
							, comment = '" . $this->db->escape ( $notebycomment ['comment'] ) . "'
							, user_id = '" . $this->db->escape ( $notebycomment ['user_id'] ) . "'
							, notes_pin = '" . $this->db->escape ( $notebycomment ['notes_pin'] ) . "'
							, signature = '" . $this->db->escape ( $notebycomment ['signature'] ) . "'
							, user_file = '" . $this->db->escape ( $notebycomment ['user_file'] ) . "'
							, is_user_face = '" . $this->db->escape ( $notebycomment ['is_user_face'] ) . "'
							, date_added = '" . $this->db->escape ( $notebycomment ['date_added'] ) . "'
							, date_updated = '" . $this->db->escape ( $notebycomment ['date_updated'] ) . "'
							, comment_date = '" . $this->db->escape ( $notebycomment ['comment_date'] ) . "'
							, notes_type = '" . $this->db->escape ( $notebycomment ['notes_type'] ) . "'
							, strike_user_id = '" . $this->db->escape ( $notebycomment ['strike_user_id'] ) . "'
							, strike_date_added = '" . $this->db->escape ( $notebycomment ['strike_date_added'] ) . "'
							, strike_signature = '" . $this->db->escape ( $notebycomment ['strike_signature'] ) . "'
							, strike_user_file = '" . $this->db->escape ( $notebycomment ['strike_user_file'] ) . "'
							, strike_is_user_face = '" . $this->db->escape ( $notebycomment ['strike_is_user_face'] ) . "'
							, device_unique_id = '" . $this->db->escape ( $notebycomment ['device_unique_id'] ) . "'
							, phone_device_id = '" . $this->db->escape ( $notebycomment ['phone_device_id'] ) . "'
							, is_android = '" . $this->db->escape ( $notebycomment ['is_android'] ) . "'
							, tags_id = '" . $this->db->escape ( $notebycomment ['tags_id'] ) . "'
							, keyword_file = '" . $this->db->escape ( $notebycomment ['keyword_file'] ) . "'
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 notes_id = '" . $notebycomment ['notes_id'] . "'
							, facilities_id = '" . $this->db->escape ( $notebycomment ['facilities_id'] ) . "'
							, comment = '" . $this->db->escape ( $notebycomment ['comment'] ) . "'
							, user_id = '" . $this->db->escape ( $notebycomment ['user_id'] ) . "'
							, notes_pin = '" . $this->db->escape ( $notebycomment ['notes_pin'] ) . "'
							, signature = '" . $this->db->escape ( $notebycomment ['signature'] ) . "'
							, user_file = '" . $this->db->escape ( $notebycomment ['user_file'] ) . "'
							, is_user_face = '" . $this->db->escape ( $notebycomment ['is_user_face'] ) . "'
							, date_added = '" . $this->db->escape ( $notebycomment ['date_added'] ) . "'
							, date_updated = '" . $this->db->escape ( $notebycomment ['date_updated'] ) . "'
							, comment_date = '" . $this->db->escape ( $notebycomment ['comment_date'] ) . "'
							, notes_type = '" . $this->db->escape ( $notebycomment ['notes_type'] ) . "'
							, strike_user_id = '" . $this->db->escape ( $notebycomment ['strike_user_id'] ) . "'
							, strike_date_added = '" . $this->db->escape ( $notebycomment ['strike_date_added'] ) . "'
							, strike_signature = '" . $this->db->escape ( $notebycomment ['strike_signature'] ) . "'
							, strike_user_file = '" . $this->db->escape ( $notebycomment ['strike_user_file'] ) . "'
							, strike_is_user_face = '" . $this->db->escape ( $notebycomment ['strike_is_user_face'] ) . "'
							, device_unique_id = '" . $this->db->escape ( $notebycomment ['device_unique_id'] ) . "'
							, phone_device_id = '" . $this->db->escape ( $notebycomment ['phone_device_id'] ) . "'
							, is_android = '" . $this->db->escape ( $notebycomment ['is_android'] ) . "'
							, tags_id = '" . $this->db->escape ( $notebycomment ['tags_id'] ) . "'
							, keyword_file = '" . $this->db->escape ( $notebycomment ['keyword_file'] ) . "'
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$notebymoves = $this->model_syndb_syndb->getnotes_by_moveByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($notebymoves != null && $notebymoves != "") {
						$this->model_syndb_syndb->deletenotes_by_moveMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$notebymoves1 = $this->model_syndb_syndb->getnotes_by_moves ( $note ['notes_id'], $config_unique_id );
					
					if ($notebymoves1 != null && $notebymoves1 != "") {
						foreach ( $notebymoves1 as $notebymove ) {
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_facility SET 
							notes_by_facility_id = '" . $notebymove ['notes_by_facility_id'] . "'
							, facilities_id = '" . $notebymove ['facilities_id'] . "'
							, notes_id = '" . $this->db->escape ( $notebymove ['notes_id'] ) . "'
							, parent_id = '" . $this->db->escape ( $notebymove ['parent_id'] ) . "'
							, date_added = '" . $this->db->escape ( $notebymove ['date_added'] ) . "'
							, move_facilities_id = '" . $this->db->escape ( $notebymove ['move_facilities_id'] ) . "'
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 facilities_id = '" . $notebymove ['facilities_id'] . "'
							, notes_id = '" . $this->db->escape ( $notebymove ['notes_id'] ) . "'
							, parent_id = '" . $this->db->escape ( $notebymove ['parent_id'] ) . "'
							, date_added = '" . $this->db->escape ( $notebymove ['date_added'] ) . "'
							, move_facilities_id = '" . $this->db->escape ( $notebymove ['move_facilities_id'] ) . "'
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query ( $sql );
						}
					}
					
					$notebytranscripts = $this->model_syndb_syndb->getnotes_by_transcriptByMain ( $note ['notes_id'], $config_unique_id );
					
					if ($notebytranscripts != null && $notebytranscripts != "") {
						$this->model_syndb_syndb->deletenotes_by_transcriptMain ( $note ['notes_id'], $config_unique_id );
					}
					
					$notebytranscripts1 = $this->model_syndb_syndb->getnotes_by_transcripts ( $note ['notes_id'], $config_unique_id );
					
					if ($notebytranscripts1 != null && $notebytranscripts1 != "") {
						foreach ( $notebytranscripts1 as $notebytranscript ) {
							
							$sqltr = "INSERT INTO " . NEWDB_PREFIX . "notes_by_transcript SET
							notes_by_transcript_id = '" . $notebytranscript ['notes_by_transcript_id'] . "'
							, facilities_id = '" . $notebytranscript ['facilities_id'] . "'
							, notes_id = '" . $this->db->escape ( $notebytranscript ['notes_id'] ) . "'
							, source_transcript = '" . $this->db->escape ( $notebytranscript ['source_transcript'] ) . "'
							, source_language = '" . $this->db->escape ( $notebytranscript ['source_language'] ) . "'
							, target_transcript = '" . $this->db->escape ( $notebytranscript ['target_transcript'] ) . "'
							, target_language = '" . $this->db->escape ( $notebytranscript ['target_language'] ) . "'
							, user_id = '" . $this->db->escape ( $notebytranscript ['user_id'] ) . "'
							, signature = '" . $this->db->escape ( $notebytranscript ['signature'] ) . "'
							, is_user_face = '" . $this->db->escape ( $notebytranscript ['is_user_face'] ) . "'
							, user_file = '" . $this->db->escape ( $notebytranscript ['user_file'] ) . "'
							, date_updated = '" . $this->db->escape ( $notebytranscript ['date_updated'] ) . "'
							, date_added = '" . $this->db->escape ( $notebytranscript ['date_added'] ) . "'
							, notes_type = '" . $this->db->escape ( $notebytranscript ['notes_type'] ) . "'
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE
							 facilities_id = '" . $notebytranscript ['facilities_id'] . "'
							, notes_id = '" . $this->db->escape ( $notebytranscript ['notes_id'] ) . "'
							, source_transcript = '" . $this->db->escape ( $notebytranscript ['source_transcript'] ) . "'
							, source_language = '" . $this->db->escape ( $notebytranscript ['source_language'] ) . "'
							, target_transcript = '" . $this->db->escape ( $notebytranscript ['target_transcript'] ) . "'
							, target_language = '" . $this->db->escape ( $notebytranscript ['target_language'] ) . "'
							, user_id = '" . $this->db->escape ( $notebytranscript ['user_id'] ) . "'
							, signature = '" . $this->db->escape ( $notebytranscript ['signature'] ) . "'
							, is_user_face = '" . $this->db->escape ( $notebytranscript ['is_user_face'] ) . "'
							, user_file = '" . $this->db->escape ( $notebytranscript ['user_file'] ) . "'
							, date_updated = '" . $this->db->escape ( $notebytranscript ['date_updated'] ) . "'
							, date_added = '" . $this->db->escape ( $notebytranscript ['date_added'] ) . "'
							, notes_type = '" . $this->db->escape ( $notebytranscript ['notes_type'] ) . "'
							, unique_id = '" . $config_unique_id . "'
				
							";
							$this->newdb->query ( $sqltr );
						}
					}
					
					$sqlc = "UPDATE `" . DB_PREFIX . "notes` SET  notes_conut='1' WHERE notes_id = '" . ( int ) $note ['notes_id'] . "' ";
					
					$this->db->query ( $sqlc );
				}
				
				
				$activity_data5 = array (
						'data' => 'sync notes data successfully in warehouse ' 
				);
				$this->model_activity_activity->addActivity ( 'notes', $activity_data5 );
				
				$subjet = 'Warehouse Status Report';
				
				$reminderval = array ();
				$reminderval = array (
						'ttotalnotes' => $ttotalnotes,
						'ttotalforms' => $ttotalforms,
						'ttotaltasks' => $ttotaltasks,
						'activenotecount' => $activenotecount,
						'ttotaltags' => $ttotaltags 
				);
				
				$cutomer_email = array ();
				foreach ( $facilities as $key => $result_f1 ) {
					
					$customer_info = $this->model_customer_customer->getcustomerid ( $key );
					
					$message = array ();
					$facilities3 = array_unique ( $result_f1 );
					$message ['facilities'] = $facilities3;
					$message ['startDate'] = $startDate;
					$message ['endDate'] = $endDate;
					$message ['config_unique_id'] = $config_unique_id;
					$message ['company_name'] = $customer_info ['company_name'];
					$message ['reminderval'] = $reminderval;
					if ($customer_info ['cutomer_email'] != null && $customer_info ['cutomer_email'] != "") {
						$cutomer_email = explode ( ";", $customer_info ['cutomer_email'] );
					}
					
					$message ['emails'] = $cutomer_email;
					$message ['manual_link'] = $manual_link;
					$message ['schedule'] = $data ['schedule'];
					
					// $this->sendMail($subjet, $message);
				}
				/*
				 * $message = array();
				 *
				 * $facilities = array_unique($facilities);
				 *
				 * $message['facilities'] = $facilities;
				 * $message['startDate'] = $startDate;
				 * $message['endDate'] = $endDate;
				 * $message['config_unique_id'] = $config_unique_id;
				 *
				 * $this->sendMail($subjet, $message);
				 */
			} else {
				$subjet = 'Warehouse Status Report';
				
				$message = array ();
				
				$message ['facilities'] = $facilitie1s;
				$message ['startDate'] = $startDate;
				$message ['endDate'] = $endDate;
				$message ['company_name'] = $customer_info ['company_name'];
				$message ['config_unique_id'] = $config_unique_id;
				$message ['schedule'] = $data ['schedule'];
				
				$message ['emails'] = $cutomer_email;
				
				// $this->sendMail($subjet, $message);
				
				$activity_data5 = array (
						'data' => 'no notes data sync in warehouse because no data in given date' . $startDate . '' 
				);
				$this->model_activity_activity->addActivity ( 'notes', $activity_data5 );
			}
			
			
			$threemonth = date ( 'Y-m-d', strtotime ( '-3 Months' ) );
			
			$last_date = date ( 'Y-m-d', strtotime ( $threemonth . '-1 day' ) );
			// var_dump($last_date);
			
			$this->model_syndb_syndb->deleteNotes ( $last_date );
			
			
			if ($data ['schedule'] == '1') {
			} else {
				
				if ($manual_link == '1') {
					echo json_encode ( 1 );
				} else {
					echo "Success";
				}
			}
			
			// var_dump($backupDatas);
			// die;
		} catch ( Exception $e ) {
			$subjet = 'Warehouse Alert';
			
			$message = array ();
			$message ['startDate'] = $startDate;
			$message ['endDate'] = $endDate;
			$message ['config_unique_id'] = $config_unique_id;
			$message ['manual_link'] = $manual_link;
			$message ['schedule'] = $data ['schedule'];
			
			$this->sendMail ( $subjet, $message );
			
			$activity_data5 = array (
					'data' => 'warehouse sync error ' .$e->getMessage()
			);
			$this->model_activity_activity->addActivity ( 'notes', $activity_data5 );
		}
		}
	}
	public function sendMail($subjet, $results) {
		/*
		 * $message33 = 'Hello,';
		 * $message33 .= '<br><br>';
		 * $message33 .= $message.' <br><br>';
		 * $message33 .='Thank You<br> '.$this->config->get('config_name').'';
		 */
		
		/*
		 * if($results['manual_link'] == '1'){
		 * //$message33 = $this->alertemailtemplate($results);
		 * }else{
		 *
		 * if($results['schedule'] == '1'){
		 *
		 * }else{
		 * $message33 = $this->emailtemplate($results);
		 * }
		 */
		$message33 = $this->alertemailtemplate ( $results );
		
		// }
		
		// var_dump($message33 );die;
		
		$this->load->model ( 'api/emailapi' );
		
		$edata = array ();
		$edata ['message'] = $message33;
		$edata ['subject'] = $subjet;
		
		$emailid = array ();
		
		$emailid [] = 'note-sync@noteactive.com';
		// $edata['user_email'] = 'app-monitoring@noteactive.com';
		
		// $emailid = array_merge($emailid, $results['emails']);
		$edata ['useremailids'] = $emailid;
		
		// var_dump($edata);die;
		
		$email_status = $this->model_api_emailapi->sendmail ( $edata );
	}
	public function emailtemplate($result) {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Warehouse Status Report</title>

	<style>
	@media screen and (max-width:500px) {
	   h6 {
			font-size: 12px !important;
		}
	}
	</style>
	</head>
	 
	<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

	<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
		<tr>
			<td></td>
			<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
				

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
					<table style="width: 100%;">
						<tr>
							<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
							<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Status Report</h6></td>
						</tr>
					</table>
				</div>
				
			</td>
			<td></td>
		</tr>
	</table>

	<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
		<tr>
			<td></td>
			<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
					<table>
						<tr>
							<td>
								
								<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['company_name'] . '!</h1>
								<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse successfully the following is the details :</p>
								
							</td>
						</tr>
					</table>
				</div>';
		foreach ( $result ['facilities'] as $result_f ) {
			
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $result_f . "'" );
			$facility_info = $query->row;
			
			$query1 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "activecustomer` WHERE customer_key = '" . $facility_info ['customer_key'] . "'" );
			$customer_info = $query1->row;
			
			// var_dump($result['manual_link']);
			
			$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $result ['endDate'] . " 00:00:00 ' AND  '" . $result ['endDate'] . " 23:59:59' and facilities_id = '" . ( int ) $result_f . "' and notes_conut = '1' ";
			
			$query2 = $this->db->query ( $sql );
			$facility_notes = $query2->row ['total'];
			
			$sqlf = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $result ['endDate'] . " 00:00:00 ' AND  '" . $result ['endDate'] . " 23:59:59' and facilities_id = '" . ( int ) $result_f . "' and notes_conut = '1' and is_forms = '1' ";
			
			$query2f = $this->db->query ( $sqlf );
			$facility_forms = $query2f->row ['total'];
			
			$sqlk = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $result ['endDate'] . " 00:00:00 ' AND  '" . $result ['endDate'] . " 23:59:59' and facilities_id = '" . ( int ) $result_f . "' and notes_conut = '1' and keyword_file = '1' ";
			
			$query2k = $this->db->query ( $sqlk );
			$facility_activenote = $query2k->row ['total'];
			
			$sqlt = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $result ['endDate'] . " 00:00:00 ' AND  '" . $result ['endDate'] . " 23:59:59' and facilities_id = '" . ( int ) $result_f . "' and notes_conut = '1' and task_id > '0' ";
			
			$query2t = $this->db->query ( $sqlt );
			$facility_task = $query2t->row ['total'];
			
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
						
						<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
							<tr>
								<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
								<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
								<td>
									<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total No. Records: ' . $facility_notes . '</small>
									<br>
									
									<small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total Forms: ' . $facility_forms . '</small><br>
									<small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total Task: ' . $facility_task . '</small><br>
									<small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total ActiveNotes: ' . $facility_activenote . '</small></h4>
									
									<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
									' . $customer_info ['company_name'] . ' | ' . $facility_info ['facility'] . '
									</p>
								</td>
							</tr>
						</table>
					
					</div>';
		}
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . date ( 'j, F Y', strtotime ( $result ['endDate'] ) ) . '
							</p>
						</td>
					</tr>
				</table></div>
				

			</td>
			<td></td>
		</tr>
	</table>

	</body>
	</html>';
		return $html;
	}
	public function alertemailtemplate() {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Warehouse Alert</title>

		<style>
		@media screen and (max-width:500px) {
		   h6 {
				font-size: 12px !important;
			}
		}
		</style>
		</head>
		 
		<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

		<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
			<tr>
				<td></td>
				<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
					

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
						<table style="width: 100%;">
							<tr>
								<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
								<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Alert</h6></td>
							</tr>
						</table>
					</div>
					
				</td>
				<td></td>
			</tr>
		</table>

		<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
			<tr>
				<td></td>
				<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
						<table>
							<tr>
								<td>
									
									<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello Admin!</h1>
									<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse error</p>
									
								</td>
							</tr>
						</table>
					</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
							<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								' . date ( 'j, F Y', strtotime ( $result ['startDate'] ) ) . '
								</p>
							</td>
						</tr>
					</table></div>
					

				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>';
		return $html;
	}
	
	
	
	
	
public function  autodeletetask($taskstart_date, $complteteTaskList = array(), $timezone_info){
	$result = array ();

	$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $complteteTaskList ['tasktype'], $complteteTaskList ['facilityId'] );
	
	if ($tasktype_info ['auto_extend'] == '0') {
		if ($tasktype_info ['custom_completion_rule'] == '1') {
			$config_task_after_complete = $tasktype_info ['config_task_after_complete'];
			$config_task_deleted_time = $tasktype_info ['config_task_deleted_time'];
			$deleteTime = $config_task_deleted_time;
		} else {
			$config_task_after_complete = $this->config->get ( 'config_task_after_complete' );
			$config_task_deleted_time = $this->config->get ( 'config_task_deleted_time' );
			$deleteTime = $config_task_deleted_time;
		}
		
		// echo date('H:i:s', strtotime($complteteTaskList['task_time']));
		// echo "<hr>";
		
		$taskstarttime = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $deleteTime . ' minutes', strtotime ( $complteteTaskList ['task_time'] ) ) );
		// var_dump($taskstarttime);
		// echo "<hr>";
		
		$currenttime = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		/*
		 * echo "TASK TIME ". $taskstarttime . " =========== CURRENT TIME ".$currenttime;
		 * var_dump($taskstart_date);
		 * var_dump($complteteTaskList['task_date']);
		 * echo "<hr>";
		 */
		
		$result ['facilityId'] = $complteteTaskList ['facilityId'];
		$result ['description'] = $complteteTaskList ['description'];
		$result ['date_added'] = $complteteTaskList ['date_added'];
		$result ['task_time'] = $complteteTaskList ['task_time'];
		$result ['id'] = $complteteTaskList ['id'];
		$result ['assign_to'] = $complteteTaskList ['assign_to'];
		$result ['checklist'] = $complteteTaskList ['checklist'];
		$result ['tasktype'] = $complteteTaskList ['tasktype'];
		$result ['parent_id'] = $complteteTaskList ['parent_id'];
		$result ['facilitytimezone'] = $timezone_info ['timezone_value'];
		$result ['is_pause'] = $complteteTaskList ['is_pause'];
		$result ['task_form_id'] = $complteteTaskList ['task_form_id'];
		$result ['task_action'] = $complteteTaskList ['task_action'];
		$result ['task_count'] = $complteteTaskList ['task_count'];
		
		$taskstartdate = date ( 'Y-m-d', strtotime ( $complteteTaskList ['task_date'] ) );
		
		if ($taskstart_date == $taskstartdate) {
			
			$result ['is_back_date'] = 1;
			
			$notes_id = $this->model_createtask_createtask->insertTaskLists ( $result, $complteteTaskList ['facilityId'], '0' );
			
			if ($complteteTaskList ['medication_tags']) {
				$this->load->model ( 'setting/tags' );
				
				$medication_tags1 = explode ( ',', $complteteTaskList ['medication_tags'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				foreach ( $medication_tags1 as $medicationtag ) {
					$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
					
					if ($tags_info1 ['emp_first_name']) {
						$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
					} else {
						$emp_tag_id = $tags_info1 ['emp_tag_id'];
					}
					
					if ($tags_info1) {
						
						$drugs = array ();
						
						$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
						
						foreach ( $mdrugs as $tasklocation ) {
							
							$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $tasklocation ['tags_medication_details_id'] );
							
							$task_content = 'Resident ' . $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
							
							$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
							notes_id = '" . $notes_id . "', locations_id ='" . $tasklocation ['locations_id'] . "', task_type= '2', task_content = '" . $this->db->escape ( $task_content ) . "', signature= '" . $tasklocation ['medication_signature'] . "', user_id= '" . $tasklocation ['medication_user_id'] . "', date_added = '" . $date_added . "', notes_pin = '" . $tasklocation ['medication_notes_pin'] . "', notes_type = '" . $this->request->post ['notes_type'] . "', task_time = '" . $tasklocation ['task_time'] . "' , media_url = '" . $tasklocation ['media_url'] . "', capacity = '" . $tasklocation ['capacity'] . "', location_name = '" . $tasklocation ['location_name'] . "', location_type = '" . $tasklocation ['location_type'] . "', notes_task_type = '2', tags_id = '" . $tags_info1 ['tags_id'] . "', drug_name = '" . $mdrug_info ['drug_name'] . "', dose = '" . $mdrug_info ['dose'] . "', drug_type = '" . $mdrug_info ['drug_type'] . "', quantity = '" . $tasklocation ['quantity'] . "', frequency = '" . $mdrug_info ['frequency'] . "', instructions = '" . $mdrug_info ['instructions'] . "', count = '" . $mdrug_info ['count'] . "', createtask_by_group_id = '" . $tasklocation ['createtask_by_group_id'] . "', task_comments = '" . $tasklocation ['comments'] . "', medication_attach_url = '" . $tasklocation ['medication_attach_url'] . "',medication_file_upload='1' , tags_medication_details_id = '" . $tasklocation ['tags_medication_details_id'] . "' , tags_medication_id = '" . $tasklocation ['tags_medication_id'] . "'  ";
							
							$this->db->query ( $sql2 );
							$notes_by_task_id = $this->db->getLastId ();
						}
					}
				}
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
					$tadata = array ();
					$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date, $tadata );
				}
			}
			
			
			$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
			
			if ($relation_keyword_id) {
				$this->load->model ( 'notes/notes' );
				$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->load->model ( 'setting/keywords' );
				$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
				
				$data3 = array ();
				$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
				$data3 ['notes_description'] = $noteDetails ['notes_description'];
				
				$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
			}
			
			$this->model_createtask_createtask->updateIncomtaskNote ( $complteteTaskList ['id'], $complteteTaskList ['facilityId'] );
			
			$this->model_createtask_createtask->deteteIncomTask ( $complteteTaskList ['facilityId'] );
		}
		
		if ($currenttime > $taskstarttime) {
			// var_dump($complteteTaskLists);
			// echo "TRUE ";
			
			// var_dump($tresult['facilities_id']);
			
			if ($complteteTaskList ['enable_requires_approval'] == '2') {
				
				$declineTaskLists = $this->model_createtask_createtask->getdeclinetasksLists ( $complteteTaskList ['id'] );
				
				$approvaltaskdate = date ( 'Y-m-d H:i', strtotime ( $complteteTaskList ['date_added'] ) );
				$declinetaskdate = date ( 'Y-m-d H:i', strtotime ( $declineTaskLists ['date_added'] ) );
				
				if ($approvaltaskdate == $declinetaskdate) {
					$notes_id = $this->model_createtask_createtask->insertTaskLists ( $result, $complteteTaskList ['facilityId'], '0' );
					
					if ($complteteTaskList ['medication_tags']) {
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $complteteTaskList ['medication_tags'] );
						
						date_default_timezone_set ( $timezone_info ['timezone_value'] );
						
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
							
							if ($tags_info1 ['emp_first_name']) {
								$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
							} else {
								$emp_tag_id = $tags_info1 ['emp_tag_id'];
							}
							
							if ($tags_info1) {
								
								$drugs = array ();
								
								$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
								
								foreach ( $mdrugs as $tasklocation ) {
									
									$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $tasklocation ['tags_medication_details_id'] );
									
									$task_content = 'Resident ' . $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									
									$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
									notes_id = '" . $notes_id . "', locations_id ='" . $tasklocation ['locations_id'] . "', task_type= '2', task_content = '" . $this->db->escape ( $task_content ) . "', signature= '" . $tasklocation ['medication_signature'] . "', user_id= '" . $tasklocation ['medication_user_id'] . "', date_added = '" . $date_added . "', notes_pin = '" . $tasklocation ['medication_notes_pin'] . "', notes_type = '" . $this->request->post ['notes_type'] . "', task_time = '" . $tasklocation ['task_time'] . "' , media_url = '" . $tasklocation ['media_url'] . "', capacity = '" . $tasklocation ['capacity'] . "', location_name = '" . $tasklocation ['location_name'] . "', location_type = '" . $tasklocation ['location_type'] . "', notes_task_type = '2', tags_id = '" . $tags_info1 ['tags_id'] . "', drug_name = '" . $mdrug_info ['drug_name'] . "', dose = '" . $mdrug_info ['dose'] . "', drug_type = '" . $mdrug_info ['drug_type'] . "', quantity = '" . $tasklocation ['quantity'] . "', frequency = '" . $mdrug_info ['frequency'] . "', instructions = '" . $mdrug_info ['instructions'] . "', count = '" . $mdrug_info ['count'] . "', createtask_by_group_id = '" . $tasklocation ['createtask_by_group_id'] . "', task_comments = '" . $tasklocation ['comments'] . "', medication_attach_url = '" . $tasklocation ['medication_attach_url'] . "',medication_file_upload='1' , tags_medication_details_id = '" . $tasklocation ['tags_medication_details_id'] . "' , tags_medication_id = '" . $tasklocation ['tags_medication_id'] . "'  ";
									
									$this->db->query ( $sql2 );
									$notes_by_task_id = $this->db->getLastId ();
								}
							}
						}
						
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
							$this->load->model ( 'notes/notes' );
							$tadata = array ();
							$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date, $tadata );
						}
					}
					
					
					$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
					
					if ($relation_keyword_id) {
						$this->load->model ( 'notes/notes' );
						$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
						
						$this->load->model ( 'setting/keywords' );
						$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
						
						$data3 = array ();
						$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
						$data3 ['notes_description'] = $noteDetails ['notes_description'];
						
						$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
					}
					
					$this->model_createtask_createtask->updateIncomtaskNote ( $complteteTaskList ['id'], $complteteTaskList ['facilityId'] );
					
					$this->model_createtask_createtask->deteteIncomTask ( $complteteTaskList ['facilityId'] );
				}
			} else {
				$notes_id = $this->model_createtask_createtask->insertTaskLists ( $result, $complteteTaskList ['facilityId'], '0' );
				
				if ($complteteTaskList ['medication_tags']) {
					$this->load->model ( 'setting/tags' );
					
					$medication_tags1 = explode ( ',', $complteteTaskList ['medication_tags'] );
					
					date_default_timezone_set ( $timezone_info ['timezone_value'] );
					
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					foreach ( $medication_tags1 as $medicationtag ) {
						$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
						
						if ($tags_info1 ['emp_first_name']) {
							$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
						} else {
							$emp_tag_id = $tags_info1 ['emp_tag_id'];
						}
						
						if ($tags_info1) {
							
							$drugs = array ();
							
							$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
							
							foreach ( $mdrugs as $tasklocation ) {
								
								$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $tasklocation ['tags_medication_details_id'] );
								
								$task_content = 'Resident ' . $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
								
								$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
								notes_id = '" . $notes_id . "', locations_id ='" . $tasklocation ['locations_id'] . "', task_type= '2', task_content = '" . $this->db->escape ( $task_content ) . "', signature= '" . $tasklocation ['medication_signature'] . "', user_id= '" . $tasklocation ['medication_user_id'] . "', date_added = '" . $date_added . "', notes_pin = '" . $tasklocation ['medication_notes_pin'] . "', notes_type = '" . $this->request->post ['notes_type'] . "', task_time = '" . $tasklocation ['task_time'] . "' , media_url = '" . $tasklocation ['media_url'] . "', capacity = '" . $tasklocation ['capacity'] . "', location_name = '" . $tasklocation ['location_name'] . "', location_type = '" . $tasklocation ['location_type'] . "', notes_task_type = '2', tags_id = '" . $tags_info1 ['tags_id'] . "', drug_name = '" . $mdrug_info ['drug_name'] . "', dose = '" . $mdrug_info ['dose'] . "', drug_type = '" . $mdrug_info ['drug_type'] . "', quantity = '" . $tasklocation ['quantity'] . "', frequency = '" . $mdrug_info ['frequency'] . "', instructions = '" . $mdrug_info ['instructions'] . "', count = '" . $mdrug_info ['count'] . "', createtask_by_group_id = '" . $tasklocation ['createtask_by_group_id'] . "', task_comments = '" . $tasklocation ['comments'] . "', medication_attach_url = '" . $tasklocation ['medication_attach_url'] . "',medication_file_upload='1' , tags_medication_details_id = '" . $tasklocation ['tags_medication_details_id'] . "' , tags_medication_id = '" . $tasklocation ['tags_medication_id'] . "'  ";
								
								$this->db->query ( $sql2 );
								$notes_by_task_id = $this->db->getLastId ();
							}
						}
					}
					
					$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
						$tadata = array ();
						$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date, $tadata );
					}
				}
				
				
				$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
				
				if ($relation_keyword_id) {
					$this->load->model ( 'notes/notes' );
					$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					$data3 = array ();
					$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
					$data3 ['notes_description'] = $noteDetails ['notes_description'];
					
					$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
				}
				
				$this->model_createtask_createtask->updateIncomtaskNote ( $complteteTaskList ['id'], $complteteTaskList ['facilityId'] );
				
				$this->model_createtask_createtask->deteteIncomTask ( $complteteTaskList ['facilityId'] );
			}
		}
		}
	}
}
