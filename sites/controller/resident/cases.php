<?php

class Controllerresidentcases extends Controller
{

    private $error = array();

    public function dashboard ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->load->model('resident/dashboard');
        $timezone_name = $this->customer->isTimezone();
        
        date_default_timezone_set($timezone_name);
        
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        if (($this->request->get['searchtag'] == '1')) {
            $url = "";
            if ($this->request->post['search_tags'] != null && $this->request->post['search_tags'] != "") {
                $url .= '&search_tags=' . $this->request->post['search_tags'];
            }
            
            $this->redirect($this->url->link('resident/cases/dashboard', '' . $url, 'SSL'));
        }
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $this->data['search_tags'] = $this->request->get['search_tags'];
        }
        
        if (isset($this->request->get['searchdate'])) {
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
            $currentdate = $createdate1;
            $currentdate1 = $createdate1;
            $enddate = $currentdate;
        } else {
            $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
            $enddate = date('d-m-Y', strtotime("+5 days"));
            $enddate2 = date('d-m-Y', strtotime("+1 days"));
            $enddate3 = date('d-m-Y', strtotime("+2 days"));
            $currentdate = date('d-m-Y');
            $currentdate1 = '';
        }
        
        $tdata = array();
        $tdata = array(
                'searchdate' => $currentdate,
                'enddate' => $enddate,
                'facilities_id' => $this->customer->getId()
        );
        $total_tasks = $this->model_resident_dashboard->getTotalTasks($tdata);
        
        $cdata = array();
        $cdata = array(
                'searchdate' => $currentdate,
                'enddate' => $enddate2, // date('Y-m-d')strtotime(' +1
                                        // days',strtotime($currentdate))),
                'facilities_id' => $this->customer->getId()
        );
        
        $critical_tasks = $this->model_resident_dashboard->gettotalCriticalTasks($cdata);
        
        $mdata = array();
        $mdata = array(
                'searchdate' => $currentdate,
                'enddate' => $enddate3,
                'facilities_id' => $this->customer->getId()
        );
        $moderate_tasks = $this->model_resident_dashboard->getTotalModerateTasks($mdata);
        
        $this->data['totaltasks'] = $total_tasks;
        $this->data['criticaltasks'] = $critical_tasks;
        $this->data['moderatetasks'] = $moderate_tasks;
        
        $signupweek = date('Y-m-d');
        
        for ($i = 0; $i < 7; $i ++) {
            $date32 = date('Y-m-d', strtotime("-" . $i . "days", strtotime($signupweek)));
            $dayName = date('D', strtotime($date32));
            if ($dayName == "Sun") {
                $start_date = $date32 . ' 00:00:00';
            }
        }
        
        for ($i = 0; $i < 7; $i ++) {
            $date44 = date('Y-m-d', strtotime("+" . $i . "days", strtotime($signupweek)));
            $dayName = date('D', strtotime($date44));
            if ($dayName == "Sat") {
                $end_date = $date44 . ' 23:59:59';
            }
        }
        
        $this->data['taskbydate'] = array();
        
        for ($i = 0; $i < 5; $i ++) {
            $date = date('Y-m-d', strtotime("+" . $i . "days", strtotime($start_date)));
            $date2 = date('m-d-Y', strtotime("+" . $i . "days", strtotime($start_date)));
            $countdaystasks = $this->model_resident_dashboard->getdaysTasks($date);
            $firstnote = $this->model_resident_dashboard->getfirstNote($date);
            
            $this->data['taskbydate'][] = array(
                    'dayname' => date('l', strtotime($date)),
                    'countdaystasks' => $countdaystasks,
                    'date' => date('d', strtotime($date)),
                    'username' => $firstnote['user_id'],
                    'href' => $this->url->link('resident/cases/dashboard', '' . '&searchdate=' . $date2, 'SSL')
            );
        }
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $this->data['searchclass'] = $this->request->get['searchdate'];
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        // var_dump($this->data['taskbydate']);
        
        $this->load->model('setting/tags');
        
        if ($this->request->get['search_tags'] == NULL && $this->request->get['search_tags'] == "") {
            if ($currentdate1 == "" && $currentdate1 == NULL) {
                $start_date1 = $start_date;
                $end_date1 = $end_date;
            } else {
                
                $start_date1 = '';
                $end_date1 = '';
            }
        } else {
            $start_date1 = '';
            $end_date1 = '';
        }
        
        $data3 = array(
                'start_date' => $start_date1,
                'end_date' => $end_date1,
                'searchdate' => $currentdate1,
                'emp_tag_id_2' => $this->request->get['search_tags'],
                'status' => 1,
                'discharge' => 1,
                // 'role_call' => '1',
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getId()
        );
        
        $this->data['tags_total'] = $this->model_setting_tags->getTotalTags($data3);
        // var_dump($this->data['tags_total']);
        
        $tags = $this->model_setting_tags->getTags($data3);
        
        $this->load->model('resident/resident');
        
        $this->load->model('createtask/createtask');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        $this->load->model('resident/resident');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate = date('d-m-Y');
        
        foreach ($tags as $tag) {
            
            $allform_info = $this->model_form_form->gettagsforma($tag['tags_id']);
            
            if ($allform_info != null && $allform_info != "") {
                $screenig_url = $this->url->link('form/form', '' . '&tags_forms_id=' . $allform_info['tags_forms_id'] . '&tags_id=' . $allform_info['tags_id'] . '&notes_id=' . $allform_info['notes_id'] . '&forms_design_id=' . $allform_info['custom_form_type'] . '&forms_id=' . $allform_info['forms_id'], 'SSL');
            } else {
                $screenig_url = '';
            }
            
            $allforms = $this->model_resident_resident->gettagsforms($tag['tags_id']);
            $forms = array();
            foreach ($allforms as $allform) {
                
                $forms[] = array(
                        'tags_forms_id' => $allform['tags_forms_id'],
                        'forms_design_id' => $allform['forms_design_id'],
                        'form_href' => $this->url->link('resident/resident/tagforms', '' . '&tags_forms_id=' . $allform['tags_forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['forms_design_id'] . '&forms_id=' . $allform['forms_id'], 'SSL')
                );
            }
            
            $alltagcolors = $this->model_resident_resident->getagsColors($tag['tags_id']);
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
            
            $tasksinfo = $this->model_createtask_createtask->getTaskas($tag['tags_id'], $currentdate);
            
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
            
            $taskTotal = $this->model_createtask_createtask->getCountTasklist($this->customer->getId(), $currentdate, $top, '', $tag['tags_id']);
            // var_dump($taskTotal);
            
            $d = array();
            $d['emp_tag_id'] = $tag['tags_id'];
            $d['searchdate'] = $currentdate;
            $d['start'] = 0;
            $d['limit'] = 1;
            $d['advance_search'] = 1;
            $d['advance_date_desc'] = 1;
			$d['customer_key'] = $this->session->data['webcustomer_key'];
            $d['facilities_id'] = $this->customer->getId();
            
            $lastnotesinfo = $this->model_notes_notes->getnotess($d);
            
            // var_dump($lastnotesinfo[0]['notes_description']);
            // echo "<hr>";
            
            $recenttasksinfos = $this->model_createtask_createtask->getrecentTaskdetails($d);
            
            $form_info = $this->model_form_form->gettagsformav($tag['tags_id']);
            if ($form_info) {
                $ndate_added = date('D F j, Y', strtotime($form_info['date_added'] . ' +90 day'));
            } else {
                $ndate_added = '';
            }
            
            $client_medicine = $this->model_resident_resident->gettagModule($tag['tags_id']);
            
            // $client_status =
            // $this->model_resident_resident->gettagstatsus($tag['tags_id']);
            
            $tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($tag['tags_id']);
            
            if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
                
                $status = $tagstatusinfo['status'];
            } else {
                $status = '';
            }
            
            $this->data['tags'][] = array(
                    'name' => $tag['emp_first_name'] . ' ' . $emp_last_name,
                    'emp_first_name' => $tag['emp_first_name'],
                    'emp_tag_id' => $tag['emp_tag_id'],
                    'tags_id' => $tag['tags_id'],
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
                    'tag_href' => $this->url->link('resident/cases/dashboard2', '' . $url2 . '&tags_id=' . $tag['tags_id'], 'SSL')
            );
        }
        
        $this->data['action'] = $this->url->link('resident/cases/dashboard', '', 'SSL');
        $this->data['searchaction'] = $this->url->link('resident/cases/dashboard', '', 'SSL');
        
        $this->data['criticaltask_url'] = $this->url->link('resident/cases/getTask', '' . '&criticaltask=1&taskpopoup=1' . $url2, 'SSL');
        $this->data['moderatetask_url'] = $this->url->link('resident/cases/getTask', '' . '&moderateTasks=1&taskpopoup=1' . $url2, 'SSL');
        $this->data['totaltask_url'] = $this->url->link('resident/cases/getTask', '' . '&totalTasks=1&taskpopoup=1' . $url2, 'SSL');
        
        $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '', 'SSL');
        $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '', 'SSL');
        $this->data['total_url'] = $this->url->link('resident/resident', '', 'SSL');
        
        $this->data['notes_url'] = $this->url->link('notes/notes/insert', '', 'SSL');
        
        $this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
        
        $this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
        $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
        
        $this->data['task_lists'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL'));
        
        $this->data['task_lists2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '' . $url2, 'SSL'));
        
        $this->data['add_client_url1'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', 'SSL'));
        
        $this->data['close'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->data['tag_forms'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagforms', '' . $url2, 'SSL'));
        
        $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '' . '&addclient=1&forms_design_id=' . CUSTOME_INTAKEID, 'SSL'));
        
        $this->data['add_tag_medication_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '', 'SSL'));
        
        $this->template = $this->config->get('config_template') . '/template/resident/dashboard.php';
        $this->children = array(
                'common/header',
                'common/footer'
        );
        
        $this->response->setOutput($this->render());
    }

    public function dashboard2 ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->data['facilityname'] = $this->customer->getfacility();
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            $url2 .= '&searchbox=' . $this->request->post['searchbox'];
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard2', '' . $url2, 'SSL')));
        }
        
        if (isset($this->error['keyword'])) {
            $this->data['error_keyword'] = $this->error['keyword'];
        } else {
            $this->data['error_keyword'] = '';
        }
        
        $this->load->model('setting/tags');
        $tags_id = $this->request->get['tags_id'];
        $this->data['tags_info'] = $this->model_setting_tags->getTag($tags_id);
        
        if (isset($this->request->get['searchdate'])) {
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
            $currentdate = ''; // $createdate1;
        } else {
            $this->data['note_date'] = date('D F j, Y'); // date('m-d-Y');
            
            $currentdate = ''; // date('d-m-Y');
        }
        $data3 = array();
        
        $data3 = array(
                
                'searchdate' => $currentdate,
                'keyword' => $this->request->get['searchbox'],
                'tags_id' => $this->request->get['tags_id'],
                'status' => 1,
                'facilities_id' => $this->customer->getId()
        );
        
        // $this->load->model('resident/tags');
        // $this->data['tasksData'] =
        // $this->model_resident_tags->getsearchResult($data3);
        // $this->data['tagsData'] = $this->model_resident_tags->getTag($data3);
        // $this->data['residentData'] =
        // $this->model_resident_tags->getresidentData($data3);
        // $formsDatas = $this->model_resident_tags->getformsData($data3);
        
        /*
         * foreach($formsDatas as $formdata ){
         * $this->data['froms'][] = array(
         * 'incident_number'=>$formdata['incident_number'],
         * 'href'=> $this->url->link('form/form', '' .
         * '&forms_design_id='.$formdata['custom_form_type'].
         * '&forms_id='.$formdata['forms_id'].
         * '&resident_id='.$formdata['resident_id'], 'SSL'),
         * );
         * }
         */
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        $this->data['action'] = $this->url->link('resident/resident/dashboard2', '' . $url2, 'SSL');
        
        $this->data['tag_forms'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagforms', '' . $url2, 'SSL'));
        $this->data['task_lists'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL'));
        $this->data['task_lists2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '' . $url2, 'SSL'));
        $this->load->model('resident/resident');
        
        $this->load->model('createtask/createtask');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        $this->load->model('resident/resident');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate = date('d-m-Y');
        
        $tasksinfo = $this->model_createtask_createtask->getTaskas($this->request->get['tags_id'], $currentdate);
        $tasksinfo1 = $tasksinfo * 100;
        
        $top = '1';
        $taskTotal = $this->model_createtask_createtask->getCountTasklist($this->customer->getId(), $currentdate, $top, '', $tag['tags_id']);
        
        $d = array();
        $d['emp_tag_id'] = $this->request->get['tags_id'];
        $d['searchdate'] = $currentdate;
        $d['start'] = 0;
        $d['limit'] = 1;
        $d['advance_search'] = 1;
        $d['advance_date_desc'] = 1;
        $d['facilities_id'] = $this->customer->getId();
        $d['customer_key'] = $this->session->data['webcustomer_key'];
        
        $lastnotesinfo = $this->model_notes_notes->getnotess($d);
        $recenttasksinfos = $this->model_createtask_createtask->getrecentTaskdetails($d);
        
        // var_dump($lastnotesinfo );
        
        $form_info = $this->model_form_form->gettagsformav($this->request->get['tags_id']);
        if ($form_info) {
            $ndate_added = date('D F j, Y', strtotime($form_info['date_added'] . ' +90 day'));
        } else {
            $ndate_added = '';
        }
        
        $client_medicine = $this->model_resident_resident->gettagModule($this->request->get['tags_id']);
        $tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($this->request->get['tags_id']);
        
        if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
            
            $status = $tagstatusinfo['status'];
        } else {
            $status = '';
        }
        
        $this->data['tag'] = array(
                
                'tasksinfo' => $tasksinfo1,
                'taskTotal' => $taskTotal,
                'recentnote' => $lastnotesinfo[0]['notes_description'],
                'recenttasks' => $recenttasksinfos['description'],
                'ndate_added' => $ndate_added,
                'client_medicine' => $client_medicine,
                'tagstatus_info' => $status
        )
        ;
        
        $this->template = $this->config->get('config_template') . '/template/resident/dashboard2.php';
        $this->children = array(
                'common/header',
                'common/footer',
                'notes/notes/insert'
        )
        // 'resident/cases/insert2',
        // 'notes/createtask/headertasklist',
        
        ;
        
        $this->response->setOutput($this->render());
    }

    public function insert2 ()
    {
        try {
            
            unset($this->session->data['timeout']);
            $this->language->load('notes/notes');
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $this->load->model('facilities/online');
            $datafa = array();
            $datafa['username'] = $this->session->data['webuser_id'];
            $datafa['activationkey'] = $this->session->data['activationkey'];
            $datafa['facilities_id'] = $this->customer->getId();
            $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
            
            $this->model_facilities_online->updatefacilitiesOnline2($datafa);
            
            $this->data['showLoader'] = "1";
            $this->data['cases_insert'] = "1";
            $this->document->setTitle($this->language->get('heading_title'));
            
            $this->load->model('notes/notes');
            
            // var_dump($this->customer->isLogged());
            if (! $this->customer->isLogged()) {
                
                $this->redirect($this->url->link('common/login', '', 'SSL'));
            }
            
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && $this->request->post['advance_search'] != '1') {
                
                /*
                 * $this->session->data['notesdatas'] =
                 * $this->request->post['arraynotes'];
                 * //$this->session->data['notesfiles'] = $this->request->files;
                 * $this->session->data['highlighter_id'] =
                 * $this->request->post['highlighter_id'];
                 * $this->session->data['text_color_cut'] =
                 * $this->request->post['text_color_cut'];
                 * $this->session->data['text_color'] =
                 * $this->request->post['text_color'];
                 * $this->session->data['note_date'] =
                 * $this->request->post['note_date'];
                 *
                 * $this->session->data['keyword_file'] =
                 * $this->request->post['keyword_file'];
                 *
                 * $this->session->data['notes_file'] =
                 * $this->request->post['notes_file'];
                 */
                $notes_id = $this->model_notes_notes->addnotes($this->request->post, $this->customer->getId());
                
                $this->session->data['notes_id'] = $notes_id;
                
                if ($this->session->data['isPrivate'] == '1') {
                    $this->session->data['success3'] = $this->language->get('text_success');
                } else {
                    $this->session->data['success2'] = $this->language->get('text_success');
                }
                $url2 = "";
                if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                    $url2 .= '&searchdate=' . $this->request->get['searchdate'];
                }
                
                if ($this->request->get['case'] != null && $this->request->get['case'] != "") {
                    $url2 .= '&case=' . $this->request->get['case'];
                }
                
                if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                    $url2 .= '&tags_id=' . $this->request->get['tags_id'];
                }
                
                /*
                 * if($this->request->get['case'] == "1"){
                 * $this->redirect($this->url->link('notes/notes/dashboard2', ''
                 * . $url2, 'SSL'));
                 * }else{
                 *
                 * $this->redirect($this->url->link('notes/notes/insert', '' .
                 * $url2, 'SSL'));
                 * }
                 */
                
                $this->redirect($this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
            }
            
            if ($this->request->post['advance_search'] == '1') {
                
                $sres = explode("/", $this->request->post['note_date_from']);
                
                $createdate1 = $sres[2] . "-" . $sres[0] . "-" . $sres[1];
                // var_dump($createdate1);
                
                $sres2 = explode("/", $this->request->post['note_date_to']);
                
                $createdate12 = $sres2[2] . "-" . $sres2[0] . "-" . $sres2[1];
                // var_dump($createdate12);
                
                // echo rand();
                
                // echo "<br>";
                
                $diff = date_diff($createdate1, $createdate12);
                
                // echo rand();
                // var_dump($diff->format("%R%a"));
                
                if ($createdate1 > $createdate12) {
                    $url2 .= '&error2=1';
                    $this->redirect($this->url->link('notes/notes/search', '' . $url2, 'SSL'));
                    return false;
                }
                
                // die;
            }
            
            $this->getForm();
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Notes Insert'
            );
            $this->model_activity_activity->addActivity('SitesNotesinsert', $activity_data2);
            
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function getTask ()
    {
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->get['searchbox'] != null && $this->request->get['searchbox'] != "") {
            $searchbox = $this->request->get['searchbox'];
        }
        
        // $this->data['resident_url'] = $this->url->link('resident/resident',
        // '', 'SSL');
        $this->data['add_resident_url'] = $this->url->link('resident/resident/insert', '' . '&reset=1', 'SSL');
        
        $this->data['resident_url'] = $this->url->link('resident/resident/insert', '' . '&reset=1' . $url, 'SSL');
        $this->data['resident_url_close'] = $this->url->link('resident/resident/insert', '' . '&reset=1', 'SSL');
        $this->data['support_url'] = $this->url->link('resident/support', '', 'SSL');
        $this->data['searchUlr'] = $this->url->link('resident/resident/search', '', 'SSL');
        
        $this->data['createtask_url'] = $this->url->link('resident/createtask', '' . $url2, 'SSL');
        $this->data['updatestriketask_url'] = $this->url->link('resident/createtask/updateStriketask', '' . $url2, 'SSL');
        
        $this->data['updatestriketask_url'] = $this->url->link('resident/createtask/updateStriketask', '' . $url2, 'SSL');
        $this->data['addtasktask_url'] = $this->url->link('resident/resident/index', '' . $url2, 'SSL');
        $this->data['inserttask_url'] = $this->url->link('resident/createtask/inserttask', '' . $url2, 'SSL');
        
        $this->data['checklist_url'] = str_replace('&amp;', '&', $this->url->link('resident/createtask/checklistform', '' . $url2, 'SSL'));
        $this->data['incident_url'] = str_replace('&amp;', '&', $this->url->link('resident/noteform/taskforminsert', '' . $url2, 'SSL'));
        
        $this->data['custom_form_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
        
        $this->data['reviewnoted_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/reviewresident', '' . $url2, 'SSL'));
        
        $this->data['resident_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/updateStrike', '' . $url2, 'SSL'));
        $this->data['update_strike_url_private'] = str_replace('&amp;', '&', $this->url->link('resident/resident/updateStrikeprivate', '' . $url2, 'SSL'));
        $this->data['alarm_url'] = $this->url->link('resident/resident/setAlarm', '', 'SSL');
        
        $this->data['logged'] = $this->customer->isLogged();
        
        $this->load->model('setting/highlighter');
        $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
        
        $route = $this->request->get['route'];
        if ($route == 'resident/resident') {
            $this->data['urlanchor'] = '1';
        } else {
            $this->data['urlanchor'] = '2';
        }
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->get['searchdate'])) {
            $res = explode("-", $this->request->get['searchdate']);
            $createdate1 = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_date'] = date('D j, F Y', strtotime($createdate1));
            $currentdate = $createdate1;
        } else {
            $this->data['note_date'] = date('D j, F Y'); // date('m-d-Y');
            
            $currentdate = date('d-m-Y');
        }
        
        if ($this->request->get['criticaltask'] == '1') {
            $this->data['pagename'] = "Critical Task";
            $enddate = date('Y-m-d', strtotime("+1 days"));
        } elseif ($this->request->get['moderateTasks'] == '1') {
            $this->data['pagename'] = "Moderate Tasks ";
            $enddate = date('Y-m-d', strtotime("+2 days"));
        }
        if ($this->request->get['totalTasks'] == '1') {
            $this->data['pagename'] = "Total Tasks";
            $enddate = date('Y-m-d', strtotime("+5 days"));
        } else {
            $enddate = '';
        }
        
        $this->data['task_date_d'] = $this->data['note_date'];
        $this->data['task_page_url'] = $this->url->link('resident/resident/insert', '' . $url2, 'SSL');
        
        $this->data['listtask'] = array();
        $this->data['listtask2'] = array();
        
        $config_task_status = $this->customer->isTask();
        
        $this->data['checkTask'] = $config_task_status;
        
        if ($config_task_status == '1') {
            
            $config_task_complete = $this->config->get('config_task_complete');
            
            if ($config_task_complete == '5min') {
                $addTime = '5';
            } else 
                if ($config_task_complete == '10min') {
                    $addTime = '10';
                } else 
                    if ($config_task_complete == '15min') {
                        $addTime = '15';
                    } else 
                        if ($config_task_complete == '20min') {
                            $addTime = '20';
                        } else 
                            if ($config_task_complete == '25min') {
                                $addTime = '25';
                            } else 
                                if ($config_task_complete == '30min') {
                                    $addTime = '30';
                                } else 
                                    if ($config_task_complete == '45min') {
                                        $addTime = '45';
                                    }
            
            $this->data['deleteTime'] = $deleteTime;
            
            $this->load->model('createtask/createtask');
            $top = '1';
            
            $tags_id = $this->request->get['tags_id'];
            
            $listtasks = $this->model_createtask_createtask->getTasklist($this->customer->getId(), $currentdate, $top, $tags_id, $enddate, $this->request->get['criticaltask'], $this->request->get['moderateTasks'], $searchbox);
            
            // var_dump($listtasks );
            
            $this->data['taskTotal'] = $this->model_createtask_createtask->getCountTasklist($this->customer->getId(), $currentdate, $top, '', $enddate);
            
            // date_default_timezone_set($this->session->data['time_zone_1']);
            $timezone_name = $this->customer->isTimezone();
            date_default_timezone_set($timezone_name);
            
            $currenttime = date('H:i:s', strtotime('now'));
            $currenttimePlus = date('H:i:s', strtotime(' +' . $addTime . ' minutes', strtotime('now')));
            $currentdate = date('Y-m-d', strtotime('now'));
            /*
             * var_dump($currenttime);
             * echo "<hr>";
             * var_dump($currenttimePlus);
             * echo "<hr>";
             */
            
            $this->load->model('setting/locations');
            $this->load->model('setting/tags');
            
            foreach ($listtasks as $list) {
                
                $taskstarttime = date('H:i:s', strtotime($list['task_time']));
                // var_dump($taskstarttime);
                // echo "<hr>=====";
                
                // echo $currenttimePlus .' >= '. $taskstarttime ;
                
                if ($currenttimePlus >= $taskstarttime) {
                    $taskDuration = '1';
                } else {
                    $taskDuration = '2';
                }
                
                $bedcheckdata = array();
                
                if ($list['task_form_id'] != 0 && $list['task_form_id'] != NULL) {
                    
                    $formDatas = $this->model_setting_locations->getformid($list['task_form_id']);
                    
                    foreach ($formDatas as $formData) {
                        
                        $locData = $this->model_setting_locations->getlocation($formData['locations_id']);
                        
                        $locationDatab = array();
                        $location_type = "";
                        
                        $location_typea = $locData['location_type'];
                        if ($location_typea == '1') {
                            $location_type .= "Boys";
                        }
                        
                        if ($location_typea == '2') {
                            $location_type .= "Girls";
                        }
                        
                        if ($location_typea == '3') {
                            $location_type .= "General";
                        }
                        
                        if ($locData['upload_file'] != null && $locData['upload_file'] != "") {
                            $upload_file = $locData['upload_file'];
                        } else {
                            $upload_file = "";
                        }
                        $locationDatab[] = array(
                                'locations_id' => $locData['locations_id'],
                                'location_name' => $locData['location_name'],
                                'location_address' => $locData['location_address'],
                                'location_detail' => $locData['location_detail'],
                                'capacity' => $locData['capacity'],
                                'location_type' => $location_type,
                                'upload_file' => $upload_file,
                                'nfc_location_tag' => $locData['nfc_location_tag'],
                                'nfc_location_tag_required' => $locData['nfc_location_tag_required'],
                                'gps_location_tag' => $locData['gps_location_tag'],
                                'gps_location_tag_required' => $locData['gps_location_tag_required'],
                                'latitude' => $locData['latitude'],
                                'longitude' => $locData['longitude'],
                                'other_location_tag' => $locData['other_location_tag'],
                                'other_location_tag_required' => $locData['other_location_tag_required'],
                                'other_type_id' => $locData['other_type_id'],
                                'facilities_id' => $locData['facilities_id']
                        )
                        ;
                        
                        $bedcheckdata[] = array(
                                'task_form_location_id' => $formData['task_form_location_id'],
                                'location_name' => $formData['location_name'],
                                'location_detail' => $formData['location_detail'],
                                'current_occupency' => $formData['current_occupency'],
                                'bedcheck_locations' => $locationDatab
                        );
                    }
                    
                    /*
                     * $this->load->model('setting/bedchecktaskform');
                     * $taskformData =
                     * $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
                     *
                     * foreach($taskformData as $frmData){
                     * $taskformsData[] = array(
                     * 'task_form_name' =>$frmData['task_form_name'],
                     * 'facilities_id' =>$frmData['facilities_id'],
                     * 'form_type' =>$frmData['form_type']
                     * );
                     * }
                     */
                }
                
                $medications = array();
                /*
                 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
                 * $tags_info =
                 * $this->model_setting_tags->getTag($list['tags_id']);
                 * $locationData = array();
                 * $locData =
                 * $this->model_setting_locations->getlocation($tags_info['locations_id']);
                 *
                 * $locationData[] = array(
                 * 'locations_id' =>$locData['locations_id'],
                 * 'location_name' =>$locData['location_name'],
                 * 'location_address' =>$locData['location_address'],
                 * 'location_detail' =>$locData['location_detail'],
                 * 'capacity' =>$locData['capacity'],
                 * 'location_type' =>$locData['location_type'],
                 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
                 * 'nfc_location_tag_required'
                 * =>$locData['nfc_location_tag_required'],
                 * 'gps_location_tag' =>$locData['gps_location_tag'],
                 * 'gps_location_tag_required'
                 * =>$locData['gps_location_tag_required'],
                 * 'latitude' =>$locData['latitude'],
                 * 'longitude' =>$locData['longitude'],
                 * 'other_location_tag' =>$locData['other_location_tag'],
                 * 'other_location_tag_required'
                 * =>$locData['other_location_tag_required'],
                 * 'other_type_id' =>$locData['other_type_id'],
                 * 'facilities_id' =>$locData['facilities_id']
                 *
                 * );
                 *
                 *
                 * if($tags_info['upload_file'] != null &&
                 * $tags_info['upload_file'] != ""){
                 * $upload_file2 = $tags_info['upload_file'];
                 * }else{
                 * $upload_file2 = "";
                 * }
                 *
                 *
                 *
                 * $drugDatas =
                 * $this->model_setting_tags->getDrugs($list['id']);
                 * $drugaData = array();
                 * foreach($drugDatas as $drugData){
                 * $drugaData[] = array(
                 * 'createtask_by_group_id'
                 * =>$drugData['createtask_by_group_id'],
                 * 'facilities_id' =>$drugData['facilities_id'],
                 * 'locations_id' =>$drugData['locations_id'],
                 * 'tags_id' =>$drugData['tags_id'],
                 * 'medication_id' =>$drugData['medication_id'],
                 * 'drug_name' =>$drugData['drug_name'],
                 * 'dose' =>$drugData['dose'],
                 * 'drug_type' =>$drugData['drug_type'],
                 * 'quantity' =>$drugData['quantity'],
                 * 'frequency' =>$drugData['frequency'],
                 * 'start_time' =>$drugData['start_time'],
                 * 'instructions' =>$drugData['instructions'],
                 * 'count' =>$drugData['count'],
                 * 'complete_status' =>$drugData['complete_status'],
                 * 'upload_file' =>$upload_file2,
                 * );
                 * }
                 *
                 *
                 * $medications[] = array(
                 * 'tags_id' =>$tags_info['tags_id'],
                 * 'upload_file' =>$upload_file2,
                 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
                 * 'emp_first_name' =>$tags_info['emp_first_name'],
                 * 'emp_last_name' =>$tags_info['emp_last_name'],
                 * 'doctor_name' =>$tags_info['doctor_name'],
                 * 'emergency_contact' =>$tags_info['emergency_contact'],
                 * 'dob' =>$tags_info['dob'],
                 * 'medications_locations' =>$locationData,
                 * 'medications_drugs' =>$drugaData
                 * );
                 *
                 * }
                 */
                
                $this->data['transport_tags'] = array();
                $this->load->model('setting/tags');
                
                if (! empty($list['transport_tags'])) {
                    $transport_tags1 = explode(',', $list['transport_tags']);
                } else {
                    $transport_tags1 = array();
                }
                
                foreach ($transport_tags1 as $tag1) {
                    $tags_info = $this->model_setting_tags->getTag($tag1);
                    
                    if ($tags_info['emp_first_name']) {
                        $emp_tag_id = $tags_info['emp_tag_id'] . ': ' . $tags_info['emp_first_name'] . ' ' . $tags_info['emp_last_name'];
                    } else {
                        $emp_tag_id = $tags_info['emp_tag_id'];
                    }
                    
                    if ($tags_info) {
                        $transport_tags[] = array(
                                'tags_id' => $tags_info['tags_id'],
                                'emp_tag_id' => $emp_tag_id
                        );
                    }
                }
                
                $this->load->model('setting/tags');
                $tags_info2 = $this->model_setting_tags->getTag($list['emp_tag_id']);
                
                $tags_info22 = "";
                if ($tags_info2['emp_tag_id']) {
                    $tags_info22 = $tags_info2['emp_tag_id'] . ' | ';
                }
                
                $task_date1 = date('Y-m-d', strtotime($list['task_date']));
                $due_date_time1 = date('Y-m-d', strtotime($list['end_recurrence_date']));
                
                $start = strtotime($task_date1);
                $end = strtotime($due_date_time1);
                // $date = strtotime($currentdate);
                // $date = strtotime("2017-07-26");
                // $percent = $this->date_progress($start, $end, $date);
                
                // "You are 52.46% there!"
                // echo 'You are ', round($percent, 2), '% there!';
                
                $taskcompleper = round($percent, 0);
                
                // var_dump($taskcompleper);
                // echo $taskcompleper;
                
                if ($taskcompleper >= 0 && $taskcompleper <= 10) {
                    $taskcompletebaar = "10";
                }
                if ($taskcompleper >= 11 && $taskcompleper <= 20) {
                    $taskcompletebaar = "20";
                }
                if ($taskcompleper >= 21 && $taskcompleper <= 30) {
                    $taskcompletebaar = "30";
                }
                if ($taskcompleper >= 31 && $taskcompleper <= 40) {
                    $taskcompletebaar = "40";
                }
                if ($taskcompleper >= 41 && $taskcompleper <= 50) {
                    $taskcompletebaar = "50";
                }
                if ($taskcompleper >= 51 && $taskcompleper <= 60) {
                    $taskcompletebaar = "60";
                }
                if ($taskcompleper >= 61 && $taskcompleper <= 70) {
                    $taskcompletebaar = "70";
                }
                if ($taskcompleper >= 71 && $taskcompleper <= 80) {
                    $taskcompletebaar = "80";
                }
                if ($taskcompleper >= 81 && $taskcompleper <= 90) {
                    $taskcompletebaar = "90";
                }
                if ($taskcompleper >= 91 && $taskcompleper <= 100) {
                    $taskcompletebaar = "100";
                }
                // var_dump($completed);
                
                $taskcompletebaar1 = 100 - $taskcompletebaar;
                
                $medication_tags = array();
                $this->data['medication_tags'] = array();
                $this->load->model('setting/tags');
                
                if (! empty($list['medication_tags'])) {
                    $medication_tags1 = explode(',', $list['medication_tags']);
                } else {
                    $medication_tags1 = array();
                }
                
                foreach ($medication_tags1 as $medicationtag) {
                    $tags_info1 = $this->model_setting_tags->getTag($medicationtag);
                    
                    if ($tags_info1['emp_first_name']) {
                        $emp_tag_id = $tags_info1['emp_tag_id'] . ': ' . $tags_info1['emp_first_name'] . ' ' . $tags_info1['emp_last_name'];
                    } else {
                        $emp_tag_id = $tags_info1['emp_tag_id'];
                    }
                    
                    if ($tags_info1) {
                        $medication_tags[] = array(
                                'tags_id' => $tags_info1['tags_id'],
                                'emp_tag_id' => $emp_tag_id,
                                'tagsmedications' => $this->model_setting_tags->getTagsMedicationdetails($medicationtag)
                        );
                    }
                }
                
                $this->data['listtask'][] = array(
                        'assign_to' => $list['assign_to'],
                        'taskcompletebaar' => $taskcompletebaar1,
                        
                        'tags_info2' => $tags_info22, // .':
                                                     // '.$tags_info2['emp_first_name'].'
                                                     // '.
                                                     // $tags_info2['emp_last_name'],
                        'due_date_time' => date('j, M Y', strtotime($list['end_recurrence_date'])),
                        
                        'tasktype' => $list['tasktype'],
                        'send_notification' => $list['send_notification'],
                        'checklist' => $list['checklist'],
                        'date' => date('j, M Y', strtotime($list['task_date'])),
                        'id' => $list['id'],
                        'description' => $list['description'],
                        'taskDuration' => $taskDuration,
                        'task_time' => date('h:i A', strtotime($list['task_time'])),
                        'task_form_id' => $list['task_form_id'],
                        'tags_id' => $list['tags_id'],
                        'pickup_facilities_id' => $list['pickup_facilities_id'],
                        'pickup_locations_address' => $list['pickup_locations_address'],
                        'pickup_locations_time' => date('h:i A', strtotime($list['pickup_locations_time'])),
                        'pickup_locations_latitude' => $list['pickup_locations_latitude'],
                        'pickup_locations_longitude' => $list['pickup_locations_longitude'],
                        'dropoff_facilities_id' => $list['dropoff_facilities_id'],
                        'dropoff_locations_address' => $list['dropoff_locations_address'],
                        'dropoff_locations_time' => date('h:i A', strtotime($list['dropoff_locations_time'])),
                        'dropoff_locations_latitude' => $list['dropoff_locations_latitude'],
                        'dropoff_locations_longitude' => $list['dropoff_locations_longitude'],
                        'transport_tags' => $transport_tags,
                        'medications' => $medications,
                        'bedchecks' => $bedcheckdata,
                        'medication_tags' => $medication_tags
                )
                ;
            }
        }
        
        if ($this->request->get['taskpopoup'] != NULL && $this->request->get['taskpopoup'] != "") {
            $this->template = $this->config->get('config_template') . '/template/resident/criticaltask.php';
        } else {
            $this->template = $this->config->get('config_template') . '/template/resident/task.php';
        }
        $this->response->setOutput($this->render());
    }

    protected function getForm ()
    {
        try {
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $this->load->model('notes/image');
            $this->load->model('setting/highlighter');
            $this->load->model('user/user');
            
            $this->load->model('facilities/online');
            $datafa = array();
            $datafa['username'] = $this->session->data['webuser_id'];
            $datafa['activationkey'] = $this->session->data['activationkey'];
            $datafa['facilities_id'] = $this->customer->getId();
            $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
            
            $this->model_facilities_online->updatefacilitiesOnline2($datafa);
            
            unset($this->session->data['media_user_id']);
            unset($this->session->data['media_signature']);
            unset($this->session->data['media_pin']);
            unset($this->session->data['emp_tag_id']);
            unset($this->session->data['tags_id']);
            
            $this->load->model('licence/licence');
            $resulta = '1'; // $this->model_licence_licence->checkloginlicence();
                            // var_dump($resulta);
            if ($resulta == 0) {
                
                $this->customer->logout();
                unset($this->session->data['time_zone_1']);
                unset($this->session->data['token']);
                
                unset($this->session->data['note_date_search']);
                unset($this->session->data['note_date_from']);
                
                unset($this->session->data['note_date_to']);
                
                unset($this->session->data['search_time_start']);
                unset($this->session->data['search_time_to']);
                
                unset($this->session->data['keyword']);
                unset($this->session->data['user_id']);
                unset($this->session->data['search_emp_tag_id']);
                unset($this->session->data['notesdatas']);
                unset($this->session->data['advance_search']);
                unset($this->session->data['update_reminder']);
                unset($this->session->data['pagenumber']);
                unset($this->session->data['pagenumber_all']);
                unset($this->session->data['activationkey']);
                unset($this->session->data['username']);
                unset($this->session->data['session_key']);
                unset($this->session->data['unloack_success']);
                unset($this->session->data['ssincedentform']);
                unset($this->session->data['ssbedcheckform']);
                unset($this->session->data['form_search']);
                unset($this->session->data['highlighter']);
                unset($this->session->data['activenote']);
                unset($this->session->data['isPrivate']);
                unset($this->session->data['review_user_id']);
                
                unset($this->session->data['formreturn_id']);
                unset($this->session->data['design_forms']);
                unset($this->session->data['formsids']);
                
                $this->redirect($this->url->link('common/login', '', 'SSL'));
            } else {
                $this->load->model('facilities/facilities');
                $this->load->model('licence/licence');
                
                $data = array();
                $data['activationkey'] = $this->session->data['activationkey'];
                $data['ip'] = $this->request->server['REMOTE_ADDR'];
                
                $ipresults = $this->model_facilities_facilities->resetFacilityLogin($data);
                // var_dump($ipresults);
                /*
                 * if($ipresults != null && $ipresults != ""){
                 * $this->customer->logout();
                 * unset($this->session->data['time_zone_1']);
                 * unset($this->session->data['token']);
                 *
                 * unset($this->session->data['note_date_search']);
                 * unset($this->session->data['note_date_from']);
                 * unset($this->session->data['note_date_to']);
                 * unset($this->session->data['keyword']);
                 * unset($this->session->data['user_id']);
                 * unset($this->session->data['notesdatas']);
                 * unset($this->session->data['advance_search']);
                 * unset($this->session->data['update_reminder']);
                 * unset($this->session->data['pagenumber']);
                 * unset($this->session->data['pagenumber_all']);
                 * unset($this->session->data['activationkey']);
                 * unset($this->session->data['username']);
                 *
                 * $this->redirect($this->url->link('common/login', '', 'SSL'));
                 * }
                 */
            }
            
            date_default_timezone_set($this->session->data['time_zone_1']);
            
            $this->load->model('facilities/online');
            $userId = $this->customer->isLogged();
            $ip = $this->request->server['REMOTE_ADDR'];
            $this->model_facilities_online->updatefacilitiesOnline2($userId, $ip);
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->post['advance_search'] != '1') {
                $config_admin_limit1 = $this->config->get('config_front_limit');
                if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
                    $config_admin_limit = $config_admin_limit1;
                } else {
                    $config_admin_limit = "50";
                }
                
                $timezone_name = $this->customer->isTimezone();
                date_default_timezone_set($timezone_name);
                
                $data = array(
                        'searchdate' => date('m-d-Y'),
                        'searchdate_app' => '1',
                        'facilities_id' => $this->customer->getId()
                );
                
                $this->load->model('notes/notes');
                $notes_total = $this->model_notes_notes->getTotalnotess($data);
                $pagenumber_all = ceil($notes_total / $config_admin_limit);
                
                if ($pagenumber_all != null && $pagenumber_all != "") {
                    if ($pagenumber_all > 1) {
                        $url2 .= '&page=' . $pagenumber_all;
                    }
                }
            }
            
            if ($this->request->get['case'] == "1") {
                
                $this->data['rediectUlr'] = str_replace('&amp;', '&', $this->url->link('notes/notes/dashboard2', '' . $url2, 'SSL'));
            } else {
                $this->data['rediectUlr'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
            }
            
            $this->data['resetUrl'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL'));
            $this->data['resetUrl_private'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL'));
            
            $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url, 'SSL');
            
            $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url, 'SSL');
            
            $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url, 'SSL');
            
            $this->data['attachment_sign_url'] = $this->url->link('notes/notes/attachmentSign', '' . $url2, 'SSL');
            
            $this->data['naotes_tags_url'] = $this->url->link('notes/notes/updateTags', '' . $url, 'SSL');
            
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['entry_facility'] = $this->language->get('entry_facility');
            $this->data['entry_time'] = $this->language->get('entry_time');
            $this->data['entry_notes_description'] = $this->language->get('entry_notes_description');
            $this->data['entry_highliter'] = $this->language->get('entry_highliter');
            $this->data['entry_pin'] = $this->language->get('entry_pin');
            $this->data['entry_upload_file'] = $this->language->get('entry_upload_file');
            $this->data['entry_timezone'] = $this->language->get('entry_timezone');
            
            $this->data['button_save'] = $this->language->get('button_save');
            $this->data['button_cancel'] = $this->language->get('button_cancel');
            $this->data['text_select'] = $this->language->get('text_select');
            
            $this->data['review'] = $this->request->get['review'];
            
            if ($this->request->get['reset'] == '1') {
                unset($this->session->data['note_date_search']);
                unset($this->session->data['note_date_from']);
                unset($this->session->data['note_date_to']);
                
                unset($this->session->data['search_time_start']);
                unset($this->session->data['search_time_to']);
                
                unset($this->session->data['keyword']);
                unset($this->session->data['user_id']);
                unset($this->session->data['search_emp_tag_id']);
                unset($this->session->data['notesdatas']);
                unset($this->session->data['advance_search']);
                unset($this->session->data['update_reminder']);
                unset($this->session->data['keyword_file']);
                unset($this->session->data['notes_id']);
                unset($this->session->data['pagenumber']);
                unset($this->session->data['unloack_success']);
                unset($this->session->data['ssincedentform']);
                unset($this->session->data['ssbedcheckform']);
                unset($this->session->data['form_search']);
                unset($this->session->data['highlighter']);
                unset($this->session->data['activenote']);
                unset($this->session->data['review_user_id']);
                
                unset($this->session->data['formreturn_id']);
                unset($this->session->data['design_forms']);
                unset($this->session->data['formsids']);
                unset($this->session->data['upload_file']);
                
                $url = "";
                
                if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                    $url .= '&searchdate=' . $this->request->get['searchdate'];
                }
                
                $config_admin_limit1 = $this->config->get('config_front_limit');
                if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
                    $config_admin_limit = $config_admin_limit1;
                } else {
                    $config_admin_limit = "50";
                }
                
                $timezone_name = $this->customer->isTimezone();
                date_default_timezone_set($timezone_name);
                
                $data = array(
                        'searchdate' => date('m-d-Y'),
                        'searchdate_app' => '1',
                        'facilities_id' => $this->customer->getId()
                );
                
                $this->load->model('notes/notes');
                $notes_total = $this->model_notes_notes->getTotalnotess($data);
                $pagenumber_all = ceil($notes_total / $config_admin_limit);
                
                if ($pagenumber_all != null && $pagenumber_all != "") {
                    if ($pagenumber_all > 1) {
                        $url .= '&page=' . $pagenumber_all;
                    }
                }
                
                $this->redirect($this->url->link('notes/notes/insert', '' . $url, 'SSL'));
            }
            
            $this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
            $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url, 'SSL');
            
            $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url, 'SSL');
            $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url, 'SSL');
            
            $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');
            
            $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url, 'SSL');
            
            $this->data['notess'] = array();
            
            if (isset($this->session->data['update_reminder'])) {
                $this->data['update_reminder'] = $this->session->data['update_reminder'];
            }
            
            if (isset($this->request->post['advance_search'])) {
                $this->session->data['advance_search'] = $this->request->post['advance_search'];
            }
            
            if (isset($this->request->post['note_date_search'])) {
                $this->data['note_date_search'] = $this->request->post['note_date_search'];
                $this->session->data['note_date_search'] = $this->request->post['note_date_search'];
            } else {
                $this->data['note_date_search'] = '';
            }
            
            if (isset($this->request->post['highlighter'])) {
                $this->data['highlighter'] = $this->request->post['highlighter'];
                $this->session->data['highlighter'] = $this->request->post['highlighter'];
            } else {
                $this->data['highlighter'] = '';
            }
            
            if (isset($this->request->post['activenote'])) {
                $this->data['activenote'] = $this->request->post['activenote'];
                $this->session->data['activenote'] = $this->request->post['activenote'];
            } else {
                $this->data['activenote'] = '';
            }
            
            if (isset($this->request->post['note_date_from'])) {
                $this->data['note_date_from'] = $this->request->post['note_date_from'];
                $this->session->data['note_date_from'] = $this->request->post['note_date_from'];
            } else {
                $this->data['note_date_from'] = '';
            }
            
            if (isset($this->request->post['note_date_to'])) {
                $this->data['note_date_to'] = $this->request->post['note_date_to'];
                $this->session->data['note_date_to'] = $this->request->post['note_date_to'];
            } else {
                $this->data['note_date_to'] = '';
            }
            
            if (isset($this->request->post['search_time_start'])) {
                $this->data['search_time_start'] = $this->request->post['search_time_start'];
                $this->session->data['search_time_start'] = $this->request->post['search_time_start'];
            } else {
                $this->data['search_time_start'] = '';
            }
            
            if (isset($this->request->post['search_time_to'])) {
                $this->data['search_time_to'] = $this->request->post['search_time_to'];
                $this->session->data['search_time_to'] = $this->request->post['search_time_to'];
            } else {
                $this->data['search_time_to'] = '';
            }
            
            if (isset($this->request->post['keyword'])) {
                $this->data['keyword'] = $this->request->post['keyword'];
                $this->session->data['keyword'] = $this->request->post['keyword'];
            } else {
                $this->data['keyword'] = '';
            }
            
            if (isset($this->request->post['form_search'])) {
                $this->data['form_search'] = $this->request->post['form_search'];
                $this->session->data['form_search'] = $this->request->post['form_search'];
            } else {
                $this->data['form_search'] = '';
            }
            
            if (isset($this->request->post['user_id'])) {
                $this->data['user_id'] = $this->request->post['user_id'];
                $this->session->data['user_id'] = $this->request->post['user_id'];
            } else {
                $this->data['user_id'] = '';
            }
            
            if (isset($this->request->post['search_emp_tag_id'])) {
                $this->data['search_emp_tag_id'] = $this->request->post['search_emp_tag_id'];
                $this->session->data['search_emp_tag_id'] = $this->request->post['search_emp_tag_id'];
            } else {
                $this->data['search_emp_tag_id'] = '';
            }
            
            if ($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != "") {
                $note_date_from = date('Y-m-d', strtotime($this->session->data['note_date_from']));
            }
            if ($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != "") {
                $note_date_to = date('Y-m-d', strtotime($this->session->data['note_date_to']));
            }
            
            $timezone_name = $this->customer->isTimezone();
            $timeZone = date_default_timezone_set($timezone_name);
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $noteTime = date('H:i:s');
                
                $date = str_replace('-', '/', $this->request->get['searchdate']);
                $res = explode("/", $date);
                $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
                
                $this->data['note_date'] = $changedDate . ' ' . $noteTime;
                $searchdate = $this->request->get['searchdate'];
                
                if (($searchdate) >= (date('m-d-Y'))) {
                    $this->data['back_date_check'] = "1";
                } else {
                    $this->data['back_date_check'] = "2";
                }
            } else {
                $this->data['note_date'] = date('Y-m-d H:i:s');
            }
            
            if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
                $noteTime = date('H:i:s');
                
                $date = str_replace('-', '/', $this->request->get['fromdate']);
                $res = explode("/", $date);
                $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
                
                $note_date_from = date('Y-m-d', strtotime($changedDate));
                
                $note_date_to = date('Y-m-d');
                $this->session->data['advance_search'] = '1';
                
                if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
                    $this->session->data['highlighter'] = $this->request->get['highlighter'];
                }
                
                if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
                    $this->session->data['activenote'] = $this->request->get['activenote'];
                }
            }
            
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }
            
            $config_admin_limit1 = $this->config->get('config_front_limit');
            if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
                $config_admin_limit = $config_admin_limit1;
            } else {
                $config_admin_limit = "50";
            }
            $data = array(
                    'sort' => $sort,
                    'order' => $order,
                    'searchdate' => $searchdate,
                    'searchdate_app' => '1',
                    'facilities_id' => $this->customer->getId(),
                    'note_date_from' => '2017-10-06',
                    'note_date_to' => date('Y-m-d'),
                    
                    'search_time_start' => $search_time_start,
                    'search_time_to' => $search_time_to,
                    
                    'keyword' => $this->session->data['keyword'],
                    'form_search' => $this->session->data['form_search'],
                    'user_id' => $this->session->data['user_id'],
                    'highlighter' => $this->session->data['highlighter'],
                    'activenote' => $this->session->data['activenote'],
                    'emp_tag_id' => $this->session->data['search_emp_tag_id'],
                    'emp_tag_id' => $this->request->get['tags_id'],
					'customer_key' => $this->session->data['webcustomer_key'],
                    'advance_searchapp' => '1',
                    'start' => ($page - 1) * $config_admin_limit,
                    'limit' => $config_admin_limit
            );
            // var_dump($data);
            
            // if($this->session->data['advance_search'] == '1'){
            $notes_total = $this->model_notes_notes->getTotalnotess($data);
            // }
            $results = $this->model_notes_notes->getnotess($data);
            
            $this->load->model('notes/tags');
            
            $config_tag_status = $this->customer->isTag();
            $this->data['config_tag_status'] = $this->customer->isTag();
            
            $this->data['config_taskform_status'] = $this->customer->isTaskform();
            $this->data['config_noteform_status'] = $this->customer->isNoteform();
            $this->data['config_rules_status'] = $this->customer->isRule();
            $this->data['config_share_notes'] = $this->customer->isNotesShare();
            $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
            
            $this->data['unloack_success'] = $this->session->data['unloack_success'];
            // require_once(DIR_APPLICATION . 'aws/getItem.php');
            
            $facilityinfo = $this->model_facilities_facilities->getfacilities($this->customer->getId());
            // var_dump($facilityinfo);
            
            foreach ($results as $result) {
                
                $highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
                
                $reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
                
                $allimages = $this->model_notes_notes->getImages($result['notes_id']);
                $images = array();
                foreach ($allimages as $image) {
                    
                    $extension = $image['notes_media_extention'];
                    if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp') {
                        $keyImageSrc = '<img src="sites/view/digitalnotebook/image/Photos-icon.png" width="35px" height="35px" alt="" />';
                    } else 
                        if ($extension == 'doc' || $extension == 'docx') {
                            $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ms_word_DOC_icon.png" width="35px" height="35px" alt="" />';
                        } else 
                            if ($extension == 'ppt' || $extension == 'pptx') {
                                $keyImageSrc = '<img src="sites/view/digitalnotebook/image/ppt.png" width="35px" height="35px" alt="" />';
                            } else 
                                if ($extension == 'xls' || $extension == 'xlsx') {
                                    $keyImageSrc = '<img src="sites/view/digitalnotebook/image/excel-icon.png" width="35px" height="35px" alt="" />';
                                } else 
                                    if ($extension == 'pdf') {
                                        $keyImageSrc = '<img src="sites/view/digitalnotebook/image/pdf.png" width="35px" height="35px" alt="" />';
                                    } else {
                                        $keyImageSrc = '<img src="sites/view/digitalnotebook/image/attachment.png" width="35px" height="35px" alt="" />';
                                    }
                    
                    $images[] = array(
                            'keyImageSrc' => $keyImageSrc, // '<img
                                                          // src="sites/view/digitalnotebook/image/attachment.png"
                                                          // width="35px"
                                                          // height="35px"
                                                          // alt=""
                                                          // style="margin-left:
                                                          // 4px;" />',
                            'media_user_id' => $image['media_user_id'],
                            'notes_type' => $image['notes_type'],
                            'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
                            'media_signature' => $image['media_signature'],
                            'media_pin' => $image['media_pin'],
                            'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id=' . $image['notes_media_id'], 'SSL')
                    )
                    ;
                }
                
                $reminder_time = $reminder_info['reminder_time'];
                $reminder_title = $reminder_info['reminder_title'];
                
                if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
                    $keyImageSrc1 = '<img src="' . $result['keyword_file_url'] . '" wisth="35px" height="35px">';
                } else {
                    $keyImageSrc1 = "";
                }
                
                /*
                 * if($result['notes_file'] != null && $result['notes_file'] !=
                 * ""){
                 * $keyImageSrc = '<img
                 * src="sites/view/digitalnotebook/image/attachment.png"
                 * width="35px" height="35px" alt="" style="margin-left: 4px;"
                 * />';
                 *
                 * //$fileOpen = $this->url->link('notes/notes/openFile', '' .
                 * '&openfile='.$result['notes_file'] . $url, 'SSL');
                 * $fileOpen = HTTP_SERVER .'image/files/'.
                 * $result['notes_file'];
                 *
                 * }else{
                 * $keyImageSrc = '';
                 * $fileOpen = "";
                 *
                 * }
                 */
                
                if ($result['notes_pin'] != null && $result['notes_pin'] != "") {
                    $userPin = $result['notes_pin'];
                } else {
                    $userPin = '';
                }
                
                if ($result['task_time'] != null && $result['task_time'] != "00:00:00") {
                    $task_time = date('h:i A', strtotime($result['task_time']));
                } else {
                    $task_time = "";
                }
                
                if ($config_tag_status == '1') {
                    
                    $alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
                    
                    if ($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != "") {
                        $tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
                        $privacy = $tagdata['privacy'];
                        
                        $emp_tag_id = ''; // $alltag['emp_tag_id'].': ';
                    } else {
                        $emp_tag_id = '';
                        $privacy = '';
                    }
                }
                
                $allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
                $noteskeywords = array();
                
                if ($privacy == '2') {
                    if ($this->session->data['unloack_success'] == '1') {
                        // $notes_description = $keyImageSrc1 .'&nbsp;'.
                        // $emp_tag_id . $result['notes_description'];
                        
                        if ($allkeywords) {
                            $keyImageSrc12 = array();
                            $keyname = array();
                            $keyImageSrc11 = "";
                            foreach ($allkeywords as $keyword) {
                                $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                                $keyImageSrc12[] = $keyImageSrc11 . '&nbsp;' . $keyword['keyword_name'];
                                $keyname[] = $keyword['keyword_name'];
                                $keyname = array_unique($keyname);
                            }
                            
                            // $keyword_description = str_replace($keyname,
                            // $keyImageSrc12, $result['notes_description']);
                            $keyword_description = $keyImageSrc11 . '&nbsp;' . $result['notes_description'];
                            
                            $notes_description = $emp_tag_id . $keyword_description;
                        } else {
                            $notes_description = $emp_tag_id . $result['notes_description'];
                        }
                    } else {
                        $notes_description = $emp_tag_id;
                    }
                } else {
                    // $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id
                    // . $result['notes_description'];
                    
                    if ($allkeywords) {
                        $keyImageSrc12 = array();
                        $keyname = array();
                        $keyImageSrc11 = "";
                        foreach ($allkeywords as $keyword) {
                            
                            $keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" wisth="35px" height="35px">';
                            // $keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' .
                        // $keyword['keyword_name'];
                            // $keyname[] = $keyword['keyword_name'];
                            // $keyname = array_unique($keyname);
                        }
                        
                        // $keyword_description = str_replace($keyname,
                        // $keyImageSrc12, $result['notes_description']);
                        $keyword_description = $keyImageSrc11 . '&nbsp;' . $result['notes_description'];
                        
                        $notes_description = $emp_tag_id . $keyword_description;
                    } else {
                        $notes_description = $emp_tag_id . $result['notes_description'];
                    }
                }
                
                /*
                 * if($result['notes_id'] != null && $result['notes_id'] != ""){
                 * $notesID = (string) $result['notes_id'];
                 *
                 * $response = $dynamodb->scan([
                 * 'TableName' => 'incidentform',
                 * 'ProjectionExpression' => 'incidentform_id, notes_id,
                 * user_id, signature, notes_pin, form_date_added ',
                 * 'ExpressionAttributeValues' => [
                 * ':val1' => ['N' => $notesID]] ,
                 * 'FilterExpression' => 'notes_id = :val1',
                 * ]);
                 *
                 *
                 * //$response = $dynamodb->scan($params);
                 *
                 * //var_dump($response['Items']);
                 * //echo '<hr> ';
                 *
                 * $forms = array();
                 * foreach($response['Items'] as $item){
                 * $form_date_added1 =
                 * str_replace("&nbsp;","",$item['form_date_added']['S']);
                 * if($form_date_added1 != null && $form_date_added1 != ""){
                 * $form_date_added =
                 * date($this->language->get('date_format_short_2'),
                 * strtotime($item['form_date_added']['S']));
                 * }else{
                 * $form_date_added = "";
                 * }
                 * $forms[] = array(
                 * 'incidentform_id' => $item['incidentform_id']['N'],
                 * 'notes_id' => $item['notes_id']['N'],
                 * 'user_id' => str_replace("&nbsp;","",$item['user_id']['S']),
                 * 'signature' =>
                 * str_replace("&nbsp;","",$item['signature']['S']),
                 * 'notes_pin' =>
                 * str_replace("&nbsp;","",$item['notes_pin']['S']),
                 * 'form_date_added' => $form_date_added,
                 *
                 * );
                 * }
                 * }else{
                 * $forms = array();
                 * }
                 */
                
                if ($facilityinfo['config_noteform_status'] == '1') {
                    $allforms = $this->model_notes_notes->getforms($result['notes_id']);
                    $forms = array();
                    foreach ($allforms as $allform) {
                        
                        $forms[] = array(
                                'form_type_id' => $allform['form_type_id'],
                                'forms_id' => $allform['forms_id'],
                                'design_forms' => $allform['design_forms'],
                                'custom_form_type' => $allform['custom_form_type'],
                                'notes_id' => $allform['notes_id'],
                                'form_type' => $allform['form_type'],
                                'notes_type' => $allform['notes_type'],
                                'user_id' => $allform['user_id'],
                                'signature' => $allform['signature'],
                                'notes_pin' => $allform['notes_pin'],
                                'incident_number' => $allform['incident_number'],
                                'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']))
                        )
                        ;
                    }
                }
                
                $notestasks = array();
                if ($result['task_type'] == '1') {
                    $alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
                    
                    $boytotal = 0;
                    $girltotal = 0;
                    $generaltotal = 0;
                    $residencetotal = 0;
                    foreach ($alltasks as $alltask) {
                        
                        $notestasks[] = array(
                                'notes_by_task_id' => $alltask['notes_by_task_id'],
                                'locations_id' => $alltask['locations_id'],
                                'task_type' => $alltask['task_type'],
                                'task_content' => $alltask['task_content'],
                                'user_id' => $alltask['user_id'],
                                'signature' => $alltask['signature'],
                                'notes_pin' => $alltask['notes_pin'],
                                'task_time' => $alltask['task_time'],
                                'media_url' => $alltask['media_url'],
                                'capacity' => $alltask['capacity'],
                                'location_name' => $alltask['location_name'],
                                'location_type' => $alltask['location_type'],
                                'notes_task_type' => $alltask['notes_task_type'],
                                'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added']))
                        )
                        ;
                        
                        if ($alltask['location_type'] == 'Boys') {
                            $boytotal = $boytotal + $alltask['capacity'];
                        }
                        
                        if ($alltask['location_type'] == 'Girls') {
                            $girltotal = $girltotal + $alltask['capacity'];
                        }
                        
                        if ($alltask['location_type'] == 'General') {
                            $generaltotal = $generaltotal + $alltask['capacity'];
                        }
                    }
                    
                    $residencetotal = $boytotal + $girltotal + $generaltotal;
                    
                    $boytotals = array();
                    if ($boytotal > 0) {
                        $boytotals[] = array(
                                'total' => $boytotal,
                                'loc_name' => 'Boys'
                        );
                    }
                    
                    $girltotals = array();
                    if ($girltotal > 0) {
                        $girltotals[] = array(
                                'total' => $girltotal,
                                'loc_name' => 'Girls'
                        );
                    }
                    
                    $generaltotals = array();
                    if ($generaltotal > 0) {
                        $generaltotals[] = array(
                                'total' => $generaltotal,
                                'loc_name' => 'General'
                        );
                    }
                    
                    $residentstotals = array();
                    if ($residencetotal > 0) {
                        $residentstotals[] = array(
                                'total' => $residencetotal,
                                'loc_name' => 'Residents'
                        );
                    }
                }
                
                $notesmedicationtasks = array();
                if ($result['task_type'] == '2') {
                    $alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
                    
                    foreach ($alltmasks as $alltmask) {
                        
                        if ($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00') {
                            $taskTime = date('h:i A', strtotime($alltmask['task_time']));
                        }
                        
                        $notesmedicationtasks[] = array(
                                'notes_by_task_id' => $alltmask['notes_by_task_id'],
                                'locations_id' => $alltmask['locations_id'],
                                'task_type' => $alltmask['task_type'],
                                'task_content' => $alltmask['task_content'],
                                'user_id' => $alltmask['user_id'],
                                'signature' => $alltmask['signature'],
                                'notes_pin' => $alltmask['notes_pin'],
                                'task_time' => $taskTime,
                                'media_url' => $alltmask['media_url'],
                                'capacity' => $alltmask['capacity'],
                                'location_name' => $alltmask['location_name'],
                                'location_type' => $alltmask['location_type'],
                                'notes_task_type' => $alltmask['notes_task_type'],
                                'tags_id' => $alltmask['tags_id'],
                                'drug_name' => $alltmask['drug_name'],
                                'dose' => $alltmask['dose'],
                                'drug_type' => $alltmask['drug_type'],
                                'quantity' => $alltmask['quantity'],
                                'frequency' => $alltmask['frequency'],
                                'instructions' => $alltmask['instructions'],
                                'count' => $alltmask['count'],
                                'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
                                'task_comments' => $alltmask['task_comments'],
                                'medication_file_upload' => $alltmask['medication_file_upload'],
                                'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added']))
                        )
                        ;
                    }
                }
                
                $this->data['notess'][] = array(
                        'notes_id' => $result['notes_id'],
                        'alltag' => $alltag,
                        'is_private' => $result['is_private'],
                        'share_notes' => $result['share_notes'],
                        'is_offline' => $result['is_offline'],
                        'visitor_log' => $result['visitor_log'],
                        'review_notes' => $result['review_notes'],
                        'is_private_strike' => $result['is_private_strike'],
                        'checklist_status' => $result['checklist_status'],
                        'notes_type' => $result['notes_type'],
                        'strike_note_type' => $result['strike_note_type'],
                        'task_time' => $task_time,
                        'tag_privacy' => $privacy,
                        'incidentforms' => $forms,
                        'notestasks' => $notestasks,
                        'boytotals' => $boytotals,
                        'girltotals' => $girltotals,
                        'generaltotals' => $generaltotals,
                        'residentstotals' => $residentstotals,
                        'notesmedicationtasks' => $notesmedicationtasks,
                        'task_type' => $result['task_type'],
                        'taskadded' => $result['taskadded'],
                        'assign_to' => $result['assign_to'],
                        'highlighter_value' => $highlighterData['highlighter_value'],
                        'notes_description' => $notes_description,
                        // 'keyImageSrc' => $keyImageSrc,
                        // 'fileOpen' => $fileOpen,
                        'images' => $images,
                        'notetime' => date('h:i A', strtotime($result['notetime'])),
                        'username' => $result['user_id'],
                        'notes_pin' => $userPin,
                        'signature' => $result['signature'],
                        'text_color_cut' => $result['text_color_cut'],
                        'text_color' => $result['text_color'],
                        'note_date' => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
                        'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                        'date_added' => date('m-d-Y', strtotime($result['date_added'])),
                        'strike_user_name' => $result['strike_user_id'],
                        'strike_pin' => $result['strike_pin'],
                        'strike_signature' => $result['strike_signature'],
                        'strike_date_added' => date($this->language->get('date_format_short_2'), strtotime($result['strike_date_added'])),
                        'reminder_time' => $reminder_time,
                        'reminder_title' => $reminder_title,
                        'href' => $this->url->link('notes/notes/insert', '' . '&reset=1&searchdate=' . date('m-d-Y', strtotime($result['date_added'])) . $url, 'SSL')
                )
                ;
            }
            
            $this->data['reviews'] = array();
            
            $data2 = array(
                    'searchdate' => $searchdate,
                    'facilities_id' => $this->customer->getId()
            );
            
            $reviewsresults = $this->model_notes_notes->getreviews($data2);
            
            foreach ($reviewsresults as $review_info) {
                if ($review_info['user_id'] != null && $review_info['user_id'] != "") {
                    $reviewuser_info = $this->model_user_user->getUser($review_info['user_id']);
                    $reviewusername = $reviewuser_info['username'];
                } else {
                    $reviewusername = '';
                }
                
                if ($review_info['date_added'] != null && $review_info['date_added'] != "0000-00-00 00:00:00") {
                    $reviewDate = date($this->language->get('date_format_short_2'), strtotime($review_info['date_added']));
                } else {
                    $reviewDate = '';
                }
                
                if ($review_info['note_date'] != null && $review_info['note_date'] != "0000-00-00 00:00:00") {
                    $reviewnote_date = date($this->language->get('date_format_short_2'), strtotime($review_info['note_date']));
                } else {
                    $reviewnote_date = '';
                }
                
                if ($review_info['signature'] != null && $review_info['signature'] != "") {
                    
                    $review_signature = $review_info['signature'];
                } else {
                    $review_signature = '';
                }
                
                $this->data['reviews'][] = array(
                        'review_date' => $reviewDate,
                        'review_note_date' => $reviewnote_date,
                        'review_username' => $reviewusername,
                        'review_signature' => $review_signature,
                        'notes_pin' => $review_info['notes_pin'],
                        'notes_type' => $review_info['notes_type']
                );
            }
            
            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            } else {
                $this->data['error_warning'] = '';
            }
            
            if (isset($this->session->data['success_attachment'])) {
                $this->data['success_attachment'] = $this->session->data['success_attachment'];
                
                unset($this->session->data['success_attachment']);
            } else {
                $this->data['success_attachment'] = '';
            }
            
            if (isset($this->session->data['success'])) {
                $this->data['success'] = $this->session->data['success'];
                
                unset($this->session->data['success']);
            } else {
                $this->data['success'] = '';
            }
            
            if (isset($this->session->data['success2'])) {
                $this->data['success2'] = $this->session->data['success2'];
                
                unset($this->session->data['success2']);
            } else {
                $this->data['success2'] = '';
            }
            if (isset($this->session->data['success3'])) {
                $this->data['success3'] = $this->session->data['success3'];
                
                unset($this->session->data['success3']);
            } else {
                $this->data['success3'] = '';
            }
            
            if (isset($this->error['notes_description'])) {
                $this->data['error_notes_description'] = $this->error['notes_description'];
            } else {
                $this->data['error_notes_description'] = '';
            }
            
            if (isset($this->error['notetime'])) {
                $this->data['error_notetime'] = $this->error['notetime'];
            } else {
                $this->data['error_notetime'] = '';
            }
            
            if (isset($this->error['notes_file'])) {
                $this->data['error_notes_file'] = $this->error['notes_file'];
            } else {
                $this->data['error_notes_file'] = '';
            }
            
            $this->data['currentTime'] = date('m-d-Y', strtotime('now'));
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['route'] == "notes/notes/dashboard2") {
                $url2 .= '&case=1';
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            if (! isset($this->request->get['notes_id'])) {
                $this->data['action'] = $this->url->link('notes/notes/insert', '' . $url2, 'SSL');
            } else {
                $this->data['action'] = $this->url->link('notes/notes/update', '' . '&notes_id=' . $this->request->get['notes_id'] . $url, 'SSL');
            }
            
            if ($this->request->get['case'] != null && $this->request->get['case'] != "") {
                $url2 .= '&case=' . $this->request->get['case'];
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            }
            
            $this->data['cancel'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
            
            $this->data['addNotes'] = str_replace('&amp;', '&', $this->url->link('notes/notes/insert2', '' . $url2, 'SSL'));
            
            $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
            
            $this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url, 'SSL');
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
                $url2 .= '&fromdate=' . $this->request->get['fromdate'];
            }
            if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
                $url2 .= '&highlighter=' . $this->request->get['highlighter'];
            }
            $this->data['reviewUrl'] = $this->url->link('notes/notes/review', '' . '&review=1' . $url2, 'SSL');
            
            if ($this->session->data['notes_id'] == null && $this->session->data['notes_id'] == "") {
                $notes_info = $this->model_notes_notes->getnotes($this->session->data['notes_id']);
            }
            
            if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
                
                $this->load->model('notes/tags');
                $taginfo = $this->model_notes_tags->getTag($this->request->get['tags_id']);
            }
            
            if (isset($this->request->post['notes_description'])) {
                $this->data['notes_description'] = $this->request->post['notes_description'];
            } elseif (! empty($taginfo)) {
                
                if ($this->request->get['route'] != "notes/notes/dashboard2") {
                    $this->data['notes_description'] = $taginfo['emp_first_name'] . ' ' . $taginfo['emp_last_name'];
                }
            } else {
                $this->data['notes_description'] = '';
            }
            
            if (isset($this->request->post['keyword_file'])) {
                $this->data['keyword_file'] = $this->request->post['keyword_file'];
                if ($this->request->post['keyword_file'] && file_exists(DIR_IMAGE . 'icon/' . $this->request->post['keyword_file'])) {
                    $keyword_file = $this->model_notes_image->resize('icon/' . $this->request->post['keyword_file'], 20, 20);
                    
                    $this->data['keyword_file_img'] = '<img src="' . $keyword_file . '">';
                } else {
                    $this->data['keyword_file_img'] = "";
                }
            } elseif (! empty($notes_info['keyword_file'])) {
                $this->data['keyword_file'] = $notes_info['keyword_file'];
                
                if ($notes_info['keyword_file'] && file_exists(DIR_IMAGE . 'icon/' . $notes_info['keyword_file'])) {
                    $keyword_file = $this->model_notes_image->resize('icon/' . $notes_info['keyword_file'], 20, 20);
                    
                    $this->data['keyword_file_img'] = '<img src="' . $keyword_file . '">';
                } else {
                    $this->data['keyword_file_img'] = "";
                }
            } else {
                $this->data['keyword_file'] = '';
            }
            
            if (isset($this->request->post['highlighter_id'])) {
                $this->data['highlighter_id'] = $this->request->post['highlighter_id'];
            } elseif (! empty($notes_info)) {
                $this->data['highlighter_id'] = $notes_info['highlighter_id'];
            } else {
                $this->data['highlighter_id'] = '';
            }
            
            if (isset($this->request->post['tags_id'])) {
                $this->data['tags_id'] = $this->request->post['tags_id'];
            } elseif (! empty($taginfo)) {
                $this->data['tags_id'] = $taginfo['tags_id'];
            } else {
                $this->data['tags_id'] = '';
            }
            
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $this->data['configUrl'] = $this->config->get('config_ssl');
            } else {
                $this->data['configUrl'] = HTTP_SERVER;
            }
            
            $this->load->model('setting/highlighter');
            
            $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
            
            $this->load->model('setting/keywords');
            
            $this->data['keywords'] = array();
            
            $data3 = array(
                    'facilities_id' => $this->customer->getId()
            );
            
            $keywords = $this->model_setting_keywords->getkeywords($data3);
            
            foreach ($keywords as $keyword) {
                if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/' . $keyword['keyword_image'])) {
                    $image = $this->model_notes_image->resize('icon/' . $keyword['keyword_image'], 35, 35);
                }
                $this->data['keywords'][] = array(
                        'keyword_id' => $keyword['keyword_id'],
                        'keyword_name' => $keyword['keyword_name'],
                        'keyword_name2' => str_replace(array(
                                "\r",
                                "\n"
                        ), '', $keyword['keyword_name']),
                        'keyword_image' => $keyword['keyword_image'],
                        'img_icon' => $image
                );
            }
            
            $this->load->model('facilities/facilities');
			$data = array (
					'facilities_id' => $this->customer->getId() 
			);
            $results = $this->model_facilities_facilities->getfacilitiess($data);
            
            foreach ($results as $result) {
                
                $this->data['facilitiess'][] = array(
                        'facilities_id' => $result['facilities_id'],
                        'facility' => $result['facility']
                );
            }
            
            $this->load->model('form/form');
            $data3 = array();
            $data3['status'] = '1';
            // $data3['order'] = 'sort_order';
            $data3['is_parent'] = '1';
            $data3['facilities_id'] = $this->customer->getUId();
            $custom_forms = $this->model_form_form->getforms($data3);
            
            $this->data['custom_forms'] = array();
            foreach ($custom_forms as $custom_form) {
                
                $this->data['custom_forms'][] = array(
                        'forms_id' => $custom_form['forms_id'],
                        'form_name' => $custom_form['form_name'],
                        'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
                );
            }
            
            /*
             * $this->load->model('user/user');
             * $this->data['users'] =
             * $this->model_user_user->getUsersByFacility($this->customer->getId());
             */
            $this->data['note_time'] = date('h:i A');
            
            $this->data['notetime'] = date('h:i A');
            
            // if($this->session->data['advance_search'] == '1'){
            
            $url = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url .= '&searchdate=' . $this->request->get['searchdate'];
            }
            if ($this->request->get['review'] != null && $this->request->get['review'] != "") {
                $url .= '&review=' . $this->request->get['review'];
            }
            if ($this->request->get['fromdate'] != null && $this->request->get['fromdate'] != "") {
                $url .= '&fromdate=' . $this->request->get['fromdate'];
            }
            if ($this->request->get['highlighter'] != null && $this->request->get['highlighter'] != "") {
                $url .= '&highlighter=' . $this->request->get['highlighter'];
            }
            if ($this->request->get['activenote'] != null && $this->request->get['activenote'] != "") {
                $url .= '&activenote=' . $this->request->get['activenote'];
            }
            
            $this->session->data['pagenumber'] = ceil($notes_total / $config_admin_limit);
            
            if ($this->session->data['pagenumber'] > 0) {
                $this->data['pagenumber'] = $this->session->data['pagenumber'];
            } else {
                $this->data['pagenumber'] = 1;
            }
            
            if (isset($this->request->get['page'])) {
                $this->data['hide_text'] = $this->request->get['page'];
            } else {
                $this->data['hide_text'] = 1;
            }
            
            if (isset($this->request->get['page'])) {
                $this->data['pagination_review'] = $this->request->get['page'];
            } else {
                $this->data['pagination_review'] = 1;
            }
            
            $count = ceil($notes_total / 200);
            
            if ($count > 1) {
                $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnotepage', '' . $url, 'SSL');
            } else {
                $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url, 'SSL');
            }
            
            $pagination = new Pagination();
            $pagination->total = $notes_total;
            $pagination->page = $page;
            $pagination->limit = $config_admin_limit;
            
            $pagination->text = ''; // $this->language->get('text_pagination');
            $pagination->url = $this->url->link('notes/notes/insert', '' . $url . '&page={page}', 'SSL');
            
            $this->data['pagination'] = $pagination->render();
            // }
            
            $this->data['showLoaderafter'] = "2";
            $this->template = $this->config->get('config_template') . '/template/notes/notes_form.php';
            
            /*
             * $this->children = array(
             * 'common/header',
             * 'common/footer'
             * );
             */
            
            $this->response->setOutput($this->render());
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Notes getform'
            );
            $this->model_activity_activity->addActivity('SitesNotesgetform', $activity_data2);
            
            // echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
