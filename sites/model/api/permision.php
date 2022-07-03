<?php
class Modelapipermision extends Model {
	
	public function getpermision($facilities_id){
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		$unique_id = $facility['customer_key'];
		
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
		
		if($customer_info['permission'] != null && $customer_info['permission'] != ""){
			$ccpermission = $customer_info['permission'];
		}else{
			$ccpermission = "";
		}
		
		return $ccpermission;
	}
	
	
	public function getcustomerdatetime($facilities_id){
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		$unique_id = $facility['customer_key'];
		
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
		
		$customers = unserialize ( $customer_info ['setting_data'] );
			
			
		if($customers['date_format'] != null && $customers['date_format'] != ""){
			$date_format = $customers['date_format'];
		}else{
			$date_format = $this->language->get ( 'date_format_short_2' );
		}
		
		if($customers['time_format'] != null && $customers['time_format'] != ""){
			$time_format = $customers['time_format'];
		}else{
			$time_format = 'h:i A';
		}
		$timearray = array();
		$timearray['date_format'] = $date_format;
		$timearray['time_format'] = $time_format;
			
		return $timearray;
	}
	
	public function getcustomerid($facilities_id){
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		$unique_id = $facility['customer_key'];
		
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
		
		return $customer_info['activecustomer_id'];
	}
	
	public function getcustomerkey($facilities_id){
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		$unique_id = $facility['customer_key'];
		
		return $unique_id;
	}
	
	
	public function getclientinfo($facilities_id, $tag){
		$this->load->model ( 'facilities/facilities' );
		$this->load->model('setting/locations');

        $facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
        
        $unique_id = $facility ['customer_key'];

        // var_dump($unique_id); die;
        
        $this->load->model ( 'customer/customer' );
        
        $customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
        
       
        $client_info = unserialize($customer_info['client_info_notes']);

        $client_view_options2 = $client_info["client_info_notes"]; 

        $client_view_options = $client_view_options2;
		
		
		
		$json = array();
		
		$json['show_client_image'] = $client_info["show_client_image"];
		$json['show_form_tag'] = $client_info["show_form_tag"];
		$json['show_task'] = $client_info["show_task"];
		$json['show_case'] = $client_info["show_case"];
		
		if(isset($tag['emp_first_name']) && $tag['emp_first_name']!=''){
			$client_view_options = str_replace('[emp_first_name]', $tag['emp_first_name'], $client_view_options); 
		}else{
			$client_view_options = str_replace('[emp_first_name]', '', $client_view_options); 
		}


		if(isset($tag['emp_middle_name']) && $tag['emp_middle_name']!=''){
			$client_view_options = str_replace('[emp_middle_name]', $tag['emp_middle_name'], $client_view_options);
		} else{
			$client_view_options = str_replace('[emp_middle_name]', '', $client_view_options);
		} 

		if(isset($tag['emp_last_name']) && $tag['emp_last_name']!=''){
			$client_view_options = str_replace('[emp_last_name]', $tag['emp_last_name'], $client_view_options);
		} else{
			$client_view_options = str_replace('[emp_last_name]', '', $client_view_options);
		} 

		if(isset($tag['emergency_contact']) && $tag['emergency_contact']!=''){
			$client_view_options = str_replace('[emergency_contact]', $tag['emergency_contact'], $client_view_options);
		} else{
			$client_view_options = str_replace('[emergency_contact]', '', $client_view_options);
		} 

		if(isset($tag['facilities_id']) && $tag['facilities_id']!=''){
			 $result_info = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
			$client_view_options = str_replace('[facilities_id]', $result_info['facility'], $client_view_options); 
		} else{
			$client_view_options = str_replace('[facilities_id]', '', $client_view_options); 
		} 

		if(isset($tag['room']) && $tag['room']!=''){
			$rresults = $this->model_setting_locations->getlocation($tag['room']);
			$client_view_options = str_replace('[room]', $rresults['location_name'], $client_view_options);
		} else{
			$client_view_options = str_replace('[room]', '', $client_view_options);
		} 

		if(isset($tag['dob']) && $tag['dob']!=''){
			$client_view_options = str_replace('[dob]', $tag['dob'], $client_view_options);
		} else{
			$client_view_options = str_replace('[dob]', '', $client_view_options);
		}
		  
		if(isset($tag['gender']) && $tag['gender']!=''){  
			$client_view_options = str_replace('[gender]', $tag['gender'], $client_view_options);
		} else{
			$client_view_options = str_replace('[gender]', '', $client_view_options);
		}
		   
		if(isset($tag['age']) && $tag['age']!=''){  
			$client_view_options = str_replace('[age]', $tag['age'], $client_view_options); 
		} else{
			$client_view_options = str_replace('[age]', '', $client_view_options); 
		}
			
		if(isset($tag['ssn']) && $tag['ssn']!=NULL){  
			$client_view_options = str_replace('[ssn]', $tag['ssn'], $client_view_options);
		}else{
			$client_view_options = str_replace('[ssn]', '', $client_view_options);
		} 
		  
		if(isset($tag['emp_tag_id']) && $tag['emp_tag_id']!=''){
			$client_view_options = str_replace('[emp_tag_id]', $tag['emp_tag_id'], $client_view_options);
		} else{
			$client_view_options = str_replace('[emp_tag_id]', '', $client_view_options);
		}

		if(isset($tag['emp_extid']) && $tag['emp_extid']!=''){
			$client_view_options = str_replace('[emp_extid]', $tag['emp_extid'], $client_view_options);
		} else{
			$client_view_options = str_replace('[emp_extid]', '', $client_view_options);
		}
		if(isset($tag['ccn']) && $tag['ccn']!=''){
			$client_view_options = str_replace('[ccn]', $tag['ccn'], $client_view_options);
		} else{
			$client_view_options = str_replace('[ccn]', '', $client_view_options);
		}
		
		  
		if($client_view_options != "" && $client_view_options != null){
		  $client_view_options_flag = nl2br($client_view_options);
		}else{
			
			$this->load->model('setting/locations');
			$location_info = $this->model_setting_locations->getlocation($tag['room']);
			
			$fclientname = "";
			
			if($tag['emp_last_name'] != null && $tag['emp_last_name'] != ""){
				$fclientname .= $tag['emp_last_name'];
			}
			
			if($tag['emp_first_name'] != null && $tag['emp_first_name'] != ""){
				$fclientname .= ', ' .$tag['emp_first_name'];
			}
			
			if($tag['ssn'] != null && $tag['ssn'] != ""){
				$fclientname .= ' | ' .$tag['ssn'];
			}
			
			if($location_info ['location_name'] != null && $location_info ['location_name'] != ""){
				$fclientname .= ' | ' .$location_info ['location_name'];
			}
			
			$client_view_options_flag = $fclientname.' ';
		}
		
		$json['name'] = $client_view_options_flag;
		
		return $json;
		
	}
}
	
	