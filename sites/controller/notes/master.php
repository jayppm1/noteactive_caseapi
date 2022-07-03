<?php

class Controllernotesmaster extends Controller
{

    private $error = array();

    public function index ()
	{
		
		
			$this->load->model('facilities/facilities');
			$result =  $this->model_facilities_facilities->getfacilities($this->customer->getId());
			
			$ddss = array();
			if($result['notes_facilities_ids'] != null && $result['notes_facilities_ids'] != ""){
				$this->data['is_master_facility']  =  '1' ; 
				$ddss[] = $result['notes_facilities_ids'];
			}else{
				$this->data['is_master_facility']  =  '2' ; 
			}
			
			$ddss[] = $this->customer->getId();
			$sssssdd = implode(",",$ddss);
				
			$dataaaa = array();
			$dataaaa['facilities'] = $sssssdd;
			$mfacilities =  $this->model_facilities_facilities->getfacilitiess($dataaaa);
				
			$masterfacilities = array();
			foreach($mfacilities as $mfacility){
				$masterfacilities[] = array(
				  'name' => $mfacility['facility'],
				  'facilities_id' => $mfacility['facilities_id'],
				  'href' => str_replace('&amp;', '&', $this->url->link('notes/notes/insert&search_facilities_id='.$mfacility['facilities_id'], '', 'SSL')),
				);
				
			}
				
			$this->data['masterfacilities'] = $masterfacilities;
		
			$this->data['allturl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert&search_facilities_id=All', '', 'SSL'));
			$this->data['reseturl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert&searchall=1', '', 'SSL'));
		
		$this->template = $this->config->get('config_template') . '/template/notes/master.php';
        
        $this->children = array(
            'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }
	
}