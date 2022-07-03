<?php

class ControllerCommonheaderpopup extends Controller
{

    protected function index ()
    {
        try {
            /*
             * if (!$this->customer->isLogged()) {
             * $this->redirect($this->url->link('common/login', '', 'SSL'));
             * }
             */
            $this->template = $this->config->get('config_template') . '/template/common/headerpopup.php';
            
            $this->render();
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Common headerpopup'
            );
            $this->model_activity_activity->addActivity('sitesheaderpopup', $activity_data2);
        }
    }
}
?>
