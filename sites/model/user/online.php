<?php
class ModeluserOnline extends Model {	
	public function whosonline($ip, $user_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW())");

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "user_online` SET `ip` = '" . $this->db->escape($ip) . "', `user_id` = '" . (int)$user_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = NOW()");
	}
}
?>