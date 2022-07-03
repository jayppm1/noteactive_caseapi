<?php

class Controllerfacilitiesfacilities extends Controller
{

    private $error = array();

    public function country ()
    {
        $json = array();
        
        $this->load->model('setting/country');
        
        $country_info = $this->model_setting_country->getCountry($this->request->get['country_id']);
        
        if ($country_info) {
            $this->load->model('setting/zone');
            
            $json = array(
                    'country_id' => $country_info['country_id'],
                    'name' => $country_info['name'],
                    'iso_code_2' => $country_info['iso_code_2'],
                    'iso_code_3' => $country_info['iso_code_3'],
                    'address_format' => $country_info['address_format'],
                    'postcode_required' => $country_info['postcode_required'],
                    'zone' => $this->model_setting_zone->getZonesByCountryId($this->request->get['country_id']),
                    'status' => $country_info['status']
            );
        }
        
        $this->response->setOutput(json_encode($json));
    }
}
?>