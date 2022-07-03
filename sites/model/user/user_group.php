<?php
class ModelUserUserGroup extends Model {
	
	/*public function addPermission($user_id, $type, $page) {
		$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

		if ($user_query->num_rows) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			if ($user_group_query->num_rows) {
				$data = unserialize($user_group_query->row['permission']);

				$data[$type][] = $page;

				$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
			}
		}
	}*/

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT user_group_id,name,permission,description,userview,useradd,useredit	,userdelete	,facilityview,facilityadd,facilityedit,facilitydelete,is_private,user_groupids,access_dashboard,share_notes,perpetual_task,enable_requires_approval,show_hidden_info,inventory_permission,enable_mark_final,enable_form_open,is_add_note FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		$user_group = array(
			'user_group_id'       => $query->row['user_group_id'],
			'name'       => $query->row['name'],
			'description'       => $query->row['description'],
			'enable_mark_final'       => $query->row['enable_mark_final'],
			'enable_form_open'       => $query->row['enable_form_open'],
			'is_add_note'       => $query->row['is_add_note'],
			'permission' => unserialize($query->row['permission']),
			'userview' => unserialize($query->row['userview']),
			'useradd' => unserialize($query->row['useradd']),
			'useredit' => unserialize($query->row['useredit']),
			'userdelete' => unserialize($query->row['userdelete']),
			'is_private' => $query->row['is_private'],
			'share_notes' => $query->row['share_notes'],
			'perpetual_task' => $query->row['perpetual_task'],
			'enable_requires_approval' => $query->row['enable_requires_approval'],
			'show_hidden_info' => $query->row['show_hidden_info'],
			'inventory_permission' => $query->row['inventory_permission'],
			'access_dashboard' => $query->row['access_dashboard'],
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT user_group_id,name,permission,description,userview,useradd,useredit	,userdelete	,facilityview,facilityadd,facilityedit,facilitydelete,is_private,user_groupids,access_dashboard,share_notes,perpetual_task,enable_requires_approval,show_hidden_info,enable_mark_final,enable_form_open,is_add_note FROM " . DB_PREFIX . "user_group";

		
		$sql .= " where 1 = 1 and user_group_id != '1' ";	
		if ($data['filter_name'] != null && $data['filter_name'] != "") {
			$sql .= " and name like '%".$data['filter_name']."%'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			
			
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			
			if ($facility_info['customer_key'] != null && $facility_info['customer_key'] != "") {
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($facility_info['customer_key']);
			
				$sql .= " and customer_key = '".$customer_info['activecustomer_id']."'";
			}
		}
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and customer_key = '".$data['customer_key']."'";
		}
		
		$sql .= " ORDER BY name";	

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		/*$cacheid = 'getUserGroups';
		
		$this->load->model('api/cache');
		$ruserroles = $this->model_api_cache->getcache($cacheid);
		
		if (!$ruserroles) {
			$query = $this->db->query($sql);
			$ruserroles = $query->rows;
			$this->model_api_cache->setcache($cacheid,$ruserroles);
		}
	
		return $ruserroles;
		*/
		
		
		$query = $this->db->query($sql);
		return $query->rows;
		
	}

	public function getTotalUserGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

		return $query->row['total'];
	}	
}
?>