<?php

class Controllercaseclients extends Controller
{

    public function index ()
    {
        $this->language->load('common/home');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $this->data['heading_title'] = $this->config->get('config_title');
        
        /*
         * if (($this->request->get['searchtag'] == '1')) {
         * $url = "";
         * if ($this->request->post['search_tags'] != null &&
         * $this->request->post['search_tags'] != "") {
         * $url .= '&search_tags=' . $this->request->post['search_tags'];
         *
         * $this->redirect($this->url->link('common/home', ''. $url, 'SSL'));
         * }else{
         *
         * $this->redirect($this->url->link('common/home', ''. $url, 'SSL'));
         * }
         * }
         */
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $this->data['search_tags'] = $this->request->get['search_tags'];
            
            $search_tags1 = explode(":", $this->request->get['search_tags']);
            $search_tags = $search_tags1[0];
        }
        if ($this->request->get['allclients'] != null && $this->request->get['allclients'] != "") {
            $this->data['allclients'] = $this->request->get['allclients'];
        }
        
        /*
         * if ($this->request->get['tags_id'] != null &&
         * $this->request->get['tags_id'] != "") {
         * $url2 .= '&tags_id=' . $this->request->get['tags_id'];
         *
         * }
         */
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $url2 .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        
        $this->data['action222'] = str_replace('&amp;', '&', $this->url->link('common/home', '' . $url2, 'SSL'));
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('common/home', '', 'SSL'));
        $this->data['action_all'] = str_replace('&amp;', '&', $this->url->link('common/home', '', 'SSL'));
        
        $this->load->model('setting/tags');
        
        if (isset($this->request->get['clpage'])) {
            $clpage = $this->request->get['clpage'];
        } else {
            $clpage = 1;
        }
        
        $config_admin_limit1 = $this->config->get('config_front_limit');
        if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
            $config_admin_limit = $config_admin_limit1;
        } else {
            $config_admin_limit = "50";
        }
        
        if ($this->request->get['allclients'] != '1') {
            $discharge = '1';
        }
        
        $data = array(
                'emp_tag_id_2' => $search_tags,
                'status' => 1,
                'discharge' => $discharge,
                // 'role_call' => '1',
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getUId(),
                'start' => ($clpage - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        $tags_total = $this->model_setting_tags->getTotalTags($data);
        
        // var_dump($tags_total);
        
        $tags = $this->model_setting_tags->getTags($data);
        
        $this->load->model('form/form');
        $this->load->model('resident/resident');
        $this->load->model('createtask/createtask');
        $this->load->model('notes/notes');
        
        foreach ($tags as $tag) {
            
            // $allform_info =
            // $this->model_form_form->gettagsforma($tag['tags_id']);
            
            if ($allform_info != null && $allform_info != "") {
                $screenig_url = $this->url->link('form/form', '' . '&tags_forms_id=' . $allform_info['tags_forms_id'] . '&tags_id=' . $allform_info['tags_id'] . '&notes_id=' . $allform_info['notes_id'] . '&forms_design_id=' . $allform_info['custom_form_type'] . '&forms_id=' . $allform_info['forms_id'], 'SSL');
            } else {
                $screenig_url = '';
            }
            
            // $allforms =
            // $this->model_resident_resident->gettagsforms($tag['tags_id']);
            $forms = array();
            foreach ($allforms as $allform) {
                
                $forms[] = array(
                        'tags_forms_id' => $allform['tags_forms_id'],
                        'forms_design_id' => $allform['forms_design_id'],
                        'form_href' => $this->url->link('resident/resident/tagforms', '' . '&tags_forms_id=' . $allform['tags_forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['forms_design_id'] . '&forms_id=' . $allform['forms_id'], 'SSL')
                );
            }
            
            // $alltagcolors =
            // $this->model_resident_resident->getagsColors($tag['tags_id']);
            $tagcolors = array();
            foreach ($alltagcolors as $alltagcolor) {
                
                $tagcolors[] = array(
                        'color_id' => $alltagcolor['color_id'],
                        'text_highliter_div_cl' => $alltagcolor['text_highliter_div_cl']
                );
            }
            $role_call = array();
            
            if (isset($this->request->post['role_call'])) {
                $role_call[] = $this->request->post['role_call'];
            } elseif ($tag['role_call']) {
                $role_call[] = $tag['role_call'];
            } else {
                $role_call[] = array();
            }
            
            // $tasksinfo =
            // $this->model_createtask_createtask->getTaskas($tag['tags_id'],
            // $currentdate);
            
            $tasksinfo1 = $tasksinfo * 100;
            
            // var_dump($tasksinfo1);
            
            if ($tag['privacy'] == '2') {
                $upload_file = '';
                $emp_last_name = mb_substr($tag['emp_last_name'], 0, 1);
            } else {
                $upload_file = $tag['upload_file'];
                $emp_last_name = $tag['emp_last_name'];
            }
            
            $addTime = $this->config->get('config_task_complete');
            
            // $this->data['deleteTime'] = $deleteTime;
            
            $top = '1';
            
            // $taskTotal =
            // $this->model_createtask_createtask->getCountTasklist($this->customer->getUId(),
            // $currentdate, $top, '', $tag['tags_id']);
            // var_dump($taskTotal);
            
            $d = array();
            $d['emp_tag_id'] = $tag['tags_id'];
            $d['searchdate'] = $currentdate;
            $d['start'] = 0;
            $d['limit'] = 1;
            $d['advance_search'] = 1;
            $d['advance_date_desc'] = 1;
            $d['facilities_id'] = $this->customer->getUId();
            
            // $lastnotesinfo = $this->model_notes_notes->getnotess($d);
            
            // var_dump($lastnotesinfo[0]['notes_description']);
            // echo "<hr>";
            
            // $recenttasksinfos =
            // $this->model_createtask_createtask->getrecentTaskdetails($d);
            
            $form_info = $this->model_form_form->gettagsformav($tag['tags_id']);
            if ($form_info) {
                $ndate_added = date('D F j, Y', strtotime($form_info['date_added'] . ' +90 day'));
            } else {
                $ndate_added = '';
            }
            
            // $client_medicine =
            // $this->model_resident_resident->gettagModule($tag['tags_id']);
            
            // $client_status =
            // $this->model_resident_resident->gettagstatsus($tag['tags_id']);
            
            // $tagstatusinfo =
            // $this->model_resident_resident->getTagstatusbyId($tag['tags_id']);
            
            if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
                
                $status = $tagstatusinfo['status'];
            } else {
                $status = '';
            }
            
            $this->data['tags'][] = array(
                    'name' => $tag['emp_first_name'] . ' ' . $emp_last_name,
                    'emp_first_name' => $tag['emp_first_name'],
                    'emp_tag_id' => $tag['emp_tag_id'],
                    'ssn' => $tag['ssn'],
                    'age' => $tag['age'],
                    'room' => $tag['room'],
                    'tags_id' => $tag['tags_id'],
                    'date_added' => date('m-d-Y', strtotime($tag['date_added'])),
                    'gender' => $tag['gender'],
                    'upload_file' => $upload_file,
                    'privacy' => $tag['privacy'],
                    'stickynote' => $tag['stickynote'],
                    'role_call' => $role_call,
                    'tagallforms' => $forms,
                    'tagcolors' => $tagcolors,
                    'tasksinfo' => $tasksinfo1,
                    'taskTotal' => $taskTotal,
                    'recentnote' => $lastnotesinfo[0]['notes_description'],
                    'recenttasks' => $recenttasksinfos['description'],
                    'ndate_added' => $ndate_added,
                    'client_medicine' => $client_medicine,
                    'tagstatus_info' => $status,
                    'screenig_url' => $screenig_url,
                    'tag_href' => $this->url->link('common/home', '' . $url2 . '&tags_id=' . $tag['tags_id'], 'SSL')
            );
        }
        
        $url2 = "";
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['review'] != null && $this->request->get['review'] != "") {
            $url2 .= '&review=' . $this->request->get['review'];
        }
        if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
            $url2 .= '&fromdate=' . $this->request->get['fromdate'];
        }
        if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
            $url2 .= '&highlighter=' . $this->request->get['highlighter'];
        }
        if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
            $url2 .= '&activenote=' . $this->request->get['activenote'];
        }
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $url2 .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
            $url2 .= '&page=' . $this->request->get['page'];
        }
        
        if ($this->request->get['fpage'] != null && $this->request->get['fpage'] != "") {
            $url2 .= '&fpage=' . $this->request->get['fpage'];
        }
        if ($this->request->get['allclients'] != null && $this->request->get['allclients'] != "") {
            $url2 .= '&allclients=' . $this->request->get['allclients'];
        }
        
        $pagination = new Pagination();
        $pagination->total = $tags_total;
        $pagination->page = $clpage;
        $pagination->limit = $config_admin_limit;
        
        $pagination->text = ''; // $this->language->get('text_pagination');
        $pagination->url = $this->url->link('common/home', '' . $url2 . '&clpage={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();
        
        $this->template = $this->config->get('config_template') . '/template/case/clients.php';
        $this->response->setOutput($this->render());
    }
}