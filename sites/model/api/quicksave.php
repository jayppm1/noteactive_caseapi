<?php
class Modelapiquicksave extends Model {
	
	public function quicksave($data = array(), $data2) {
		
		$facilities_id = $data['facilities_id'];
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		
		return $warning11;
	}
	
}