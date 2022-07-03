<?php

class Controllercaseheader extends Controller
{

    protected function index ()
    {
        try {
            
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $this->template = $this->config->get('config_template') . '/template/case/header.php';
            
            $this->render();
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Common header'
            );
            $this->model_activity_activity->addActivity('sitesheaderpopup', $activity_data2);
        }
    }
}
?>
