<?php

class ControllerCommonHome extends Controller
{

    public function index ()
    {
        $this->language->load('common/home');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        
        $this->data['heading_title'] = $this->config->get('config_title');
        
        $this->load->model('notes/notes');
        
        if ($this->customer->isLogged()) {
            
            $this->redirect($this->url->link('notes/notes/insert', '', 'SSL'));
        }
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->data['column_date_start'] = $this->language->get('column_date_start');
        $this->data['column_date_end'] = $this->language->get('column_date_end');
        $this->data['column_title'] = $this->language->get('column_title');
        $this->data['column_username'] = $this->language->get('column_username');
        $this->data['column_facility'] = $this->language->get('column_facility');
        $this->data['column_file'] = $this->language->get('column_file');
        
        $data['button_view'] = $this->language->get('button_view');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        
        $config_admin_limit = 10;
        
        $this->data['notess'] = array();
        
        $data = array(
                'sort' => $sort,
                'order' => $order,
                // 'start' => ($page - 1) *
                // $this->config->get('config_admin_limit'),
                'limit' => $config_admin_limit
        );
        
        $results = $this->model_notes_notes->getnotess($data);
        
        foreach ($results as $result) {
            $action = array();
            
            $action[] = array(
                    'text' => $this->language->get('text_edit'),
                    'href' => $this->url->link('notes/notes/update', '' . '&notes_id=' . $result['notes_id'] . $url, 'SSL')
            );
            
            $this->data['notess'][] = array(
                    'notes_id' => $result['notes_id'],
                    'notes_description' => $result['notes_description'],
                    'shift_starttime_hour' => $result['shift_starttime_hour'],
                    'shift_starttime_minutes' => $result['shift_starttime_minutes'],
                    'shift_endtime_hour' => $result['shift_endtime_hour'],
                    'shift_endtime_minutes' => $result['shift_endtime_minutes'],
                    'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'selected' => isset($this->request->post['selected']) && in_array($result['notes_id'], $this->request->post['selected']),
                    'action' => $action
            );
        }
        
        $this->template = $this->config->get('config_template') . '/template/common/home.php';
        
        $this->children = array(
                'common/footer',
                'common/header'
        );
        
        $this->response->setOutput($this->render());
    }

    public function login ()
    {
        $route = '';
        
        if (isset($this->request->get['route'])) {
            $part = explode('/', $this->request->get['route']);
            
            if (isset($part[0])) {
                $route .= $part[0];
            }
            
            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }
        }
        
        $ignore = array(
                'common/login',
                'common/forgotten',
                'common/reset'
        );
        
        if (! $this->customer->isLogged() && ! in_array($route, $ignore)) {
            return $this->forward('common/login');
        }
        
        if (isset($this->request->get['route'])) {
            $ignore = array(
                    'common/login',
                    'common/logout',
                    'common/forgotten',
                    'common/reset',
                    'error/not_found',
                    'error/permission'
            );
            
            $config_ignore = array();
            
            if ($this->config->get('config_token_ignore')) {
                $config_ignore = unserialize($this->config->get('config_token_ignore'));
            }
            
            $ignore = array_merge($ignore, $config_ignore);
            
            if (! in_array($route, $ignore) && (! isset($this->request->get['token']) || ! isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return $this->forward('common/login');
            }
        } else {
            if (! isset($this->request->get['token']) || ! isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return $this->forward('common/login');
            }
        }
    }
}
?>