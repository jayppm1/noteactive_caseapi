<?php

class Controllercaseresident extends Controller
{

    private $error = array();

    public function index ()
    {
        /*
         * if (!$this->customer->isLogged()) {
         *
         * $this->redirect($this->url->link('common/login', '', 'SSL'));
         * }
         */
        $this->document->setTitle('Clients');
        
        $this->data['facilityname'] = $this->customer->getfacility();
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if (($this->request->get['searchtag'] == '1')) {
            $url = "";
            if ($this->request->post['search_tags'] != null && $this->request->post['search_tags'] != "") {
                $url .= '&search_tags=' . $this->request->post['search_tags'];
            }
            
            $this->redirect($this->url->link('resident/resident', '' . $url, 'SSL'));
        }
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $this->data['search_tags'] = $this->request->get['search_tags'];
        }
        
        $this->data['male_url'] = $this->url->link('resident/resident&gender=1', '', 'SSL');
        $this->data['female_url'] = $this->url->link('resident/resident&gender=2', '', 'SSL');
        $this->data['total_url'] = $this->url->link('resident/resident', '', 'SSL');
        
        $this->data['notes_url'] = $this->url->link('notes/notes/insert', '', 'SSL');
        
        $this->data['sticky_note'] = $this->url->link('resident/resident/getstickynote&close=1', '', 'SSL');
        
        $this->data['dailycensus'] = $this->url->link('resident/dailycensus', '', 'SSL');
        $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
        
        $this->data['task_lists'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/headertasklist', '' . $url2, 'SSL'));
        
        $this->data['task_lists2'] = str_replace('&amp;', '&', $this->url->link('resident/resident/residentstatus', '' . $url2, 'SSL'));
        
        $this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
        
        $this->data['add_client_url1'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '', 'SSL'));
        // $this->data['add_client_url3'] = str_replace('&amp;',
        // '&',$this->url->link('form/form', '' .
        // '&forms_design_id='.CUSTOME_INTAKEID, 'SSL'));
        
        $this->load->model('setting/tags');
        
        $data3 = array();
        $data3 = array(
                'status' => 1,
                'discharge' => 1,
                'role_call' => '1',
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getUId()
        )
        // 'emp_tag_id_2' => $this->request->get['search_tags'],
        ;
        
        // $this->data['tags_total'] =
        // $this->model_setting_tags->getTotalTags($data3);
        
        $data4 = array();
        $data4 = array(
                'status' => 1,
                'discharge' => 1,
                'gender' => '1',
                'role_call' => '1',
                'facilities_id' => $this->customer->getUId()
        )
        // 'emp_tag_id_2' => $this->request->get['search_tags'],
        ;
        
        // $this->data['maletags_total'] =
        // $this->model_setting_tags->getTotalTags($data4);
        
        $data5 = array();
        $data5 = array(
                'status' => 1,
                'discharge' => 1,
                'gender' => '2',
                'role_call' => '1',
                'facilities_id' => $this->customer->getUId()
        )
        // 'emp_tag_id_2' => $this->request->get['search_tags'],
        
        ;
        
        // $this->data['femaletags_total'] =
        // $this->model_setting_tags->getTotalTags($data5);
        // var_dump($this->data['femaletags_total']);
        
        $data31 = array();
        $data31 = array(
                'status' => 1,
                'discharge' => 1,
                'role_call' => '1',
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getUId(),
                'emp_tag_id_2' => $this->request->get['search_tags']
        );
        
        $tags = $this->model_setting_tags->getTags($data31);
        
        $this->load->model('resident/resident');
        
        $this->load->model('createtask/createtask');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        $this->load->model('resident/resident');
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        $currentdate = date('d-m-Y');
        
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
            
            // $tasktypes =
            // $this->model_createtask_createtask->getTaskdetails();
            
            foreach ($tasktypes as $tasktype) {
                $taskTotal1 = 0;
                $taskTotal = 0;
                
                $taskTotal1 = $this->model_createtask_createtask->getCountTasklist($this->customer->getUId(), $currentdate, $top, '', $tag['tags_id']);
                
                // var_dump($taskTotal1 );
                
                $taskTotal = $taskTotal + $taskTotal1;
            }
            
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
            
            // $form_info =
            // $this->model_form_form->gettagsformav($tag['tags_id']);
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
                    // 'form_href' =>
                    // $this->url->link('resident/resident/tagform', '' .
                    // '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
                    'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
            );
        }
        
        $this->load->model('setting/highlighter');
        $this->load->model('notes/image');
        
        $highlighters = $this->model_setting_highlighter->gethighlighters();
        
        $this->data['highlighters'] = array();
        foreach ($highlighters as $highlighter) {
            
            if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/' . $highlighter['highlighter_icon'])) {
                $image = $this->model_notes_image->resize('highlighter/' . $highlighter['highlighter_icon'], 50, 50);
            }
            
            $this->data['highlighters'][] = array(
                    'highlighter_id' => $highlighter['highlighter_id'],
                    'highlighter_icon' => $image,
                    'highlighter_name' => $highlighter['highlighter_name'],
                    'highlighter_value' => $highlighter['highlighter_value']
            );
        }
        
        $this->load->model('setting/keywords');
        
        $this->data['keywords'] = array();
        
        $data3 = array(
                'facilities_id' => $this->customer->getUId()
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
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
        }
        
        $this->data['close'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        
        $this->data['tag_forms'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagforms', '' . $url2, 'SSL'));
        
        $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '' . '&addclient=1&forms_design_id=' . CUSTOME_INTAKEID, 'SSL'));
        
        $this->data['add_tag_medication_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '', 'SSL'));
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '', 'SSL'));
        
        $this->data['activenote_url'] = $this->url->link('resident/resident/activenote', '', 'SSL');
        
        if (($this->request->post['all_roll_call'] == '1')) {
            
            $url2 = "";
            if ($this->request->post['all_roll_call'] != null && $this->request->post['all_roll_call'] != "") {
                $url2 .= '&all_roll_call=' . $this->request->post['all_roll_call'];
            }
            
            $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/allrolecallsign', '' . $url2, 'SSL'));
            
            $this->session->data['role_calls'] = $this->request->post['role_call'];
            
            $this->session->data['success2'] = 'Head Ciount updated Successfully! ';
        }
        
        if (isset($this->request->post['all_roll_call'])) {
            $this->data['all_roll_call'] = $this->request->post['all_roll_call'];
        } else {
            $this->data['all_roll_call'] = '';
        }
        
        if (isset($this->session->data['success2'])) {
            $this->data['success2'] = $this->session->data['success2'];
            
            unset($this->session->data['success2']);
        } else {
            $this->data['success2'] = '';
        }
        
        $this->template = $this->config->get('config_template') . '/template/resident/resident.php';
        $this->response->setOutput($this->render());
    }

    public function tagforms ()
    {
        if ($this->request->get['tags_id'] != '') {
            $this->load->model('setting/tags');
            $distag_info = $this->model_setting_tags->getTagbycheck($this->request->get['tags_id']);
            
            $this->data['distag_info'] = $distag_info;
            
            // var_dump($this->data['distag_info']);
        }
        
        $this->language->load('notes/notes');
        $this->load->model('setting/tags');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $search_tags = $this->request->get['search_tags'];
        $this->data['popup'] = $this->request->get['popup'];
        $srchdata = explode(":", $search_tags);
        
        $d3 = array();
        $d3['search_tags'] = $srchdata['1'];
        $search_tags = $this->model_setting_tags->getclients($d3);
        
        $tags_id = $this->request->get['tags_id'];
        $this->data['tags_id'] = $this->request->get['tags_id'];
        
        $this->data['add_client_url'] = str_replace('&amp;', '&', $this->url->link('notes/tags/addclient', '', 'SSL'));
        $this->data['add_tag_medication_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/tagsmedication', '', 'SSL'));
        
        $tag_info = $this->model_setting_tags->getTag($tags_id);
        
        $this->data['taginfo'] = $tag_info;
        
        if ($tag_info != null && $tag_info != "") {
            $this->data['name'] = $tag_info['emp_tag_id'] . ' : ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['name'] = 'All Client Forms';
        }
        
        $d1 = array();
        $d1['tags_id'] = $tags_id;
        $d1['form_type'] = '2';
        $client_info_sign = $this->model_notes_notes->getNoteform($d1);
        // var_dump($client_info_sign);
        
        $this->data['client_user_id'] = $client_info_sign['user_id'];
        $this->data['client_signature'] = $client_info_sign['signature'];
        $this->data['client_notes_pin'] = $client_info_sign['notes_pin'];
        $this->data['client_notes_type'] = $client_info_sign['notes_type'];
        
        if ($client_info_sign['note_date'] != null && $client_info_sign['note_date'] != "0000-00-00 00:00:00") {
            $this->data['client_form_date_added'] = date($this->language->get('date_format_short_2'), strtotime($client_info_sign['note_date']));
        } else {
            $this->data['client_form_date_added'] = '';
        }
        
        $d12 = array();
        $d12['tags_id'] = $tags_id;
        $d12['form_type'] = '1';
        
        $healthforn_info_sign = $this->model_notes_notes->getNoteform($d12);
        
        $this->data['health_user_id'] = $healthforn_info_sign['user_id'];
        $this->data['health_signature'] = $healthforn_info_sign['signature'];
        $this->data['health_notes_pin'] = $healthforn_info_sign['notes_pin'];
        $this->data['health_notes_type'] = $healthforn_info_sign['notes_type'];
        
        if ($healthforn_info_sign['note_date'] != null && $healthforn_info_sign['note_date'] != "0000-00-00 00:00:00") {
            $this->data['health_form_date_added'] = date($this->language->get('date_format_short_2'), strtotime($healthforn_info_sign['note_date']));
        } else {
            $this->data['health_form_date_added'] = '';
        }
        
        $this->load->model('resident/resident');
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_from']);
            $res = explode("/", $date);
            
            $note_date_from = $res[2] . "-" . $res[1] . "-" . $res[0];
        }
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $date = str_replace('-', '/', $this->request->get['note_date_to']);
            $res = explode("/", $date);
            $note_date_to = $res[2] . "-" . $res[1] . "-" . $res[0];
        }
        if (isset($this->request->get['fpage'])) {
            $fpage = $this->request->get['fpage'];
        } else {
            $fpage = 1;
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
                'group' => '1',
                'searchdate' => $searchdate,
                // 'facilities_id' => $this->customer->getUId(),
                'note_date_from' => $note_date_from,
                'note_date_to' => $note_date_to,
                
                'tags_id' => $tags_id,
                'start' => ($fpage - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        $form_total = $this->model_form_form->getTotalforms2($data);
        
        // var_dump($form_total);
        // die;
        
        $allforms = $this->model_form_form->gettagsforms($data);
        
        $this->data['tagsforms'] = array();
        
        foreach ($allforms as $allform) {
            
            $form_info = $this->model_form_form->getFormdata($allform['custom_form_type']);
            if ($allform['user_id'] != null && $allform['user_id'] != "") {
                $user_id = $allform['user_id'];
                $signature = $allform['signature'];
                $notes_pin = $allform['notes_pin'];
                $notes_type = $allform['notes_type'];
                
                if ($allform['form_date_added'] != null && $allform['form_date_added'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']));
                } else {
                    $form_date_added = '';
                }
            } else {
                
                $note_info = $this->model_notes_notes->getNote($allform['notes_id']);
                
                // var_dump($note_info);
                $user_id = $note_info['user_id'];
                $signature = $note_info['signature'];
                $notes_pin = $note_info['notes_pin'];
                $notes_type = $note_info['notes_type'];
                
                if ($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
                } else {
                    $form_date_added = '';
                }
            }
            
            $this->data['tagsforms'][] = array(
                    'forms_id' => $allform['forms_id'],
                    'form_name' => $form_info['form_name'],
                    'notes_type' => $notes_type,
                    'user_id' => $user_id,
                    'signature' => $signature,
                    'notes_pin' => $notes_pin,
                    'form_date_added' => $form_date_added,
                    
                    'form_href' => $this->url->link('form/form', '' . '&forms_id=' . $allform['forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id'], 'SSL')
            );
        }
        // var_dump($this->data['tagsforms']);
        
        $this->data['back_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
        $this->data['addform_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident/listingforms', '' . $url2, 'SSL'));
        
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
        
        if ($this->request->get['clpage'] != null && $this->request->get['clpage'] != "") {
            $url2 .= '&clpage=' . $this->request->get['clpage'];
        }
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        if ($this->request->get['allclients'] != null && $this->request->get['allclients'] != "") {
            $url2 .= '&allclients=' . $this->request->get['allclients'];
        }
        
        $pagination = new Pagination();
        $pagination->total = $form_total;
        $pagination->page = $fpage;
        $pagination->limit = $config_admin_limit;
        
        $pagination->text = ''; // $this->language->get('text_pagination');
        $pagination->url = $this->url->link('common/home', '' . $url2 . '&fpage={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();
        
        $this->template = $this->config->get('config_template') . '/template/case/tags_form.php';
        $this->response->setOutput($this->render());
    }

    public function listingforms ()
    {
        $this->language->load('notes/notes');
        $this->load->model('setting/tags');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        
        $config_tag_status = $this->customer->isTag();
        $this->data['config_tag_status'] = $this->customer->isTag();
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        
        $this->data['config_taskform_status'] = $this->customer->isTaskform();
        $this->data['config_noteform_status'] = $this->customer->isNoteform();
        $this->data['config_rules_status'] = $this->customer->isRule();
        $this->data['config_share_notes'] = $this->customer->isNotesShare();
        $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
        
        $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url, 'SSL');
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url, 'SSL');
        $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');
        
        $this->data['customIntake_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        $this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
        
        $this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL');
        
        $this->data['bedcheck_url'] = $this->url->link('notes/notes/generatePdf&is_bedchk=1', '' . $url2, 'SSL');
        
        $data3 = array(
                'facilities_id' => $this->customer->getUId()
        );
        $this->load->model('facilities/facilities');
        $results = $this->model_facilities_facilities->getfacilitiess($data3);
        
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
        
        $allforms = $this->model_form_form->gettagsforms();
        $this->data['tagsforms'] = array();
        
        foreach ($allforms as $allform) {
            
            $form_info = $this->model_form_form->getFormdata($allform['custom_form_type']);
            if ($allform['user_id'] != null && $allform['user_id'] != "") {
                $user_id = $allform['user_id'];
                $signature = $allform['signature'];
                $notes_pin = $allform['notes_pin'];
                $notes_type = $allform['notes_type'];
                
                if ($allform['form_date_added'] != null && $allform['form_date_added'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']));
                } else {
                    $form_date_added = '';
                }
            } else {
                
                $note_info = $this->model_notes_notes->getNote($allform['notes_id']);
                
                // var_dump($note_info);
                $user_id = $note_info['user_id'];
                $signature = $note_info['signature'];
                $notes_pin = $note_info['notes_pin'];
                $notes_type = $note_info['notes_type'];
                
                if ($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00") {
                    $form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
                } else {
                    $form_date_added = '';
                }
            }
            
            $this->data['tagsforms'][] = array(
                    'forms_id' => $allform['forms_id'],
                    'form_name' => $form_info['form_name'],
                    'notes_type' => $notes_type,
                    'user_id' => $user_id,
                    'signature' => $signature,
                    'notes_pin' => $notes_pin,
                    'form_date_added' => $form_date_added,
                    
                    'form_href' => $this->url->link('form/form', '' . '&forms_id=' . $allform['forms_id'] . '&tags_id=' . $allform['tags_id'] . '&notes_id=' . $allform['notes_id'] . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id'], 'SSL')
            );
        }
        // var_dump($this->data['tagsforms']);
        
        $this->template = $this->config->get('config_template') . '/template/resident/list_form.php';
        $this->response->setOutput($this->render());
    }
}