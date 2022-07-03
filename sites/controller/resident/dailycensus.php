<?php

class Controllerresidentdailycensus extends Controller
{

    private $error = array();

    public function index ()
    {
        if (! $this->customer->isLogged()) {
            $this->redirect($this->url->link('common/login', '', 'SSL'));
        }
        
        $this->document->setTitle('Census');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('facilities/online');
        $datafa = array();
        $datafa['username'] = $this->session->data['webuser_id'];
        $datafa['activationkey'] = $this->session->data['activationkey'];
        $datafa['facilities_id'] = $this->customer->getId();
        $datafa['ip'] = $this->request->server['REMOTE_ADDR'];
        
        $this->model_facilities_online->updatefacilitiesOnline2($datafa);
        
        $this->data['facilityname'] = $this->customer->getfacility();
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
            $this->load->model('form/form');
            
            $this->load->model('notes/notes');
            $this->load->model('resident/resident');
            
            $tdata = array();
            
            $tdata['facilities_id'] = $this->customer->getId();
            $tdata['facilitytimezone'] = $this->customer->isTimezone();
            $notes_id = $this->model_resident_resident->dailycensus($this->request->post, $tdata);
            
            $this->data['formadd'] = '1';
            
            $url2 = "";
            $url2 .= '&notes_id=' . $notes_id;
            
            $this->load->model('facilities/facilities');
            $facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
            
            if ($facility['is_enable_add_notes_by'] == '1' || $facility['is_enable_add_notes_by'] == '3') {
                $url2 .= '&census=1';
                $this->data['insert_url'] = str_replace('&amp;', '&', $this->url->link('common/authorization', '' . $url2, 'SSL'));
            } else {
                $this->data['insert_url'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus/insert2', '' . $url2, 'SSL'));
            }
            
            
        }
        
        $timezone_name = $this->customer->isTimezone();
        $timeZone = date_default_timezone_set($timezone_name);
        
        $currentDate = date('Y-m-d', strtotime('now'));
        
        $this->data['male_url'] = $this->url->link('resident/dailycensus&gender=1', '', 'SSL');
        $this->data['female_url'] = $this->url->link('resident/dailycensus&gender=2', '', 'SSL');
        $this->data['total_url'] = $this->url->link('resident/dailycensus', '', 'SSL');
        
        $this->data['notes_url'] = $this->url->link('notes/notes/insert', '', 'SSL');
        
        $this->data['clients_url'] = $this->url->link('resident/resident', '', 'SSL');
        $this->data['cancel_url'] = $this->url->link('resident/resident', '', 'SSL');
        $this->data['add_client_url'] = $this->url->link('notes/tags/addclient', '', 'SSL');
        
        $this->data['case_url'] = str_replace('&amp;', '&', $this->url->link('resident/cases/dashboard', '', 'SSL'));
        $this->data['notes_url'] = $this->url->link('notes/notes/insert&reset=1', '', 'SSL');
        
        $this->load->model('setting/tags');
        
        $datat3 = array();
        $datat3 = array(
                'status' => 1,
                'discharge' => 1,
                'role_call' => '1',
                // 'searchdate' => $currentDate,
                'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $tags = $this->model_setting_tags->getTags($datat3);
        
        $this->load->model('resident/resident');
        $this->load->model('setting/locations');
        
        foreach ($tags as $tag) {
            
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
            
            $tags_info12 = $this->model_setting_locations->getlocation($tag['room']);
            
            $this->data['tags'][] = array(
                    'name' => $tag['emp_first_name'] . ' ' . $tag['emp_last_name'],
                    'emp_first_name' => $tag['emp_first_name'],
                    'emp_tag_id' => $tag['emp_tag_id'],
                    'tags_id' => $tag['tags_id'],
                    'gender' => $tag['gender'],
                    'upload_file' => $tag['upload_file'],
                    'role_call' => $role_call,
                    'tagallforms' => $forms,
                    'tagcolors' => $tagcolors,
                    'age' => $tag['age'],
                    'tagstatus' => $tag['tagstatus'],
                    'med_mental_health' => $tag['med_mental_health'],
                    'alert_info' => $tag['alert_info'],
                    'prescription' => $tag['prescription'],
                    'restriction_notes' => $tag['restriction_notes'],
                    'room' => $tags_info12['location_name'],
                    'stickynote' => $tag['stickynote'],
                    'date_added' => date('m-d-Y', strtotime($tag['date_added'])),
                    'census' => $this->request->post['census']
            );
        }
        
        $this->load->model('form/form');
        $data3 = array();
        $data3['status'] = '1';
        // $data3['order'] = 'sort_order';
        $data3['is_parent'] = '1';
        $data3['facilities_id'] = $this->customer->getId();
        $custom_forms = $this->model_form_form->getforms($data3);
        
        $this->data['custom_forms'] = array();
        foreach ($custom_forms as $custom_form) {
            
            $this->data['custom_forms'][] = array(
                    'forms_id' => $custom_form['forms_id'],
                    'form_name' => $custom_form['form_name'],
                    'form_href' => $this->url->link('resident/resident/tagform', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
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
        
        if (isset($this->session->data['success_add_form'])) {
            $this->data['success_add_form'] = $this->session->data['success_add_form'];
            
            unset($this->session->data['success_add_form']);
        } else {
            $this->data['success_add_form'] = '';
        }
        
        $this->data['close'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus', '' . $url2, 'SSL'));
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus', '', 'SSL'));
        
        $this->data['activenote_url'] = $this->url->link('resident/resident/activenote', '', 'SSL');
        $this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
        
        $this->load->model('setting/shift');
        $data = array();
        $data['facilities_id'] = $this->customer->getId();
        $data['status'] = '1';
        $this->data['shifts'] = $this->model_setting_shift->getshifts($data);
        
        /*
         * if (isset($this->request->post['census'])) {
         * $this->data['census'] = $this->request->post['census'];
         * } else {
         * $this->data['census'] = array();
         * }
         */
        
        if (isset($this->request->post['shift_id'])) {
            $this->data['shift_id'] = $this->request->post['shift_id'];
        } else {
            $this->data['shift_id'] = '';
        }
        
        $timezone_name = $this->customer->isTimezone();
        date_default_timezone_set($timezone_name);
        
        if (isset($this->request->post['census_date'])) {
            $this->data['census_date'] = $this->request->post['census_date'];
        } else {
            $this->data['census_date'] = date('m-d-Y');
        }
        
        /*
         * if (isset($this->request->post['team_leader'])) {
         * $this->data['team_leader'] = $this->request->post['team_leader'];
         * } else {
         * $this->data['team_leader'] = '';
         * }
         */
        
        if (isset($this->request->post['team_leaders'])) {
            $userids11 = $this->request->post['team_leaders'];
        } else {
            $userids11 = array();
        }
        
        $this->data['team_leaders'] = array();
        $this->load->model('user/user');
        
        foreach ($userids11 as $userids112) {
            
            $user_info = $this->model_user_user->getUserbyupdate($userids112);
            
            if ($user_info) {
                $this->data['team_leaders'][] = array(
                        'user_id' => $userids112,
                        'username' => $user_info['username']
                );
            }
        }
        
        /*
         * if (isset($this->request->post['direct_care'])) {
         * $this->data['direct_care'] = $this->request->post['direct_care'];
         * } else {
         * $this->data['direct_care'] = '';
         * }
         */
        
        if (isset($this->request->post['spm'])) {
            $this->data['spm'] = $this->request->post['spm'];
        } else {
            $this->data['spm'] = '';
        }
        
        if (isset($this->request->post['as_spm'])) {
            $this->data['as_spm'] = $this->request->post['as_spm'];
        } else {
            $this->data['as_spm'] = '';
        }
        if (isset($this->request->post['case_manager'])) {
            $this->data['case_manager'] = $this->request->post['case_manager'];
        } else {
            $this->data['case_manager'] = '';
        }
        if (isset($this->request->post['food_services'])) {
            $this->data['food_services'] = $this->request->post['food_services'];
        } else {
            $this->data['food_services'] = '';
        }
        if (isset($this->request->post['educational_staff'])) {
            $this->data['educational_staff'] = $this->request->post['educational_staff'];
        } else {
            $this->data['educational_staff'] = '';
        }
        
        /*
         * if (isset($this->request->post['comment_box'])) {
         * $this->data['comment_box'] = $this->request->post['comment_box'];
         * } else {
         * $this->data['comment_box'] = '';
         * }
         */
        
        if (isset($this->request->post['userids'])) {
            $userids1 = $this->request->post['userids'];
        } elseif (! empty($task_info)) {
            $userids1 = explode(',', $task_info['userids']);
        } else {
            $userids1 = array();
        }
        
        $this->data['userids'] = array();
        $this->load->model('user/user');
        
        foreach ($userids1 as $userid) {
            
            $user_info = $this->model_user_user->getUserbyupdate($userid);
            
            if ($user_info) {
                $this->data['userids'][] = array(
                        'user_id' => $userid,
                        'username' => $user_info['username']
                );
            }
        }
        
        $datais = array();
        $datais = array(
                'status' => 1,
                'discharge' => 1,
                'form_type' => CUSTOME_INTAKEID,
                'currentdate' => $currentDate,
                // 'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getId()
        );
        
        $screenings_total = $this->model_form_form->gettotalformstatussc($datais);
        
        if (isset($this->request->post['screenings'])) {
            $this->data['screenings'] = $this->request->post['screenings'];
        } else {
            $this->data['screenings'] = $screenings_total;
        }
        
        $datai = array();
        $datai = array(
                'status' => 1,
                'discharge' => 1,
                // 'role_call' => '1',
                'searchdate' => $currentDate,
                // 'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getId()
        );
        
        $intakes_total = $this->model_setting_tags->getTotalTags($datai);
        // var_dump($intakes_total);
        
        if (isset($this->request->post['intakes_total'])) {
            $this->data['intakes_total'] = $this->request->post['intakes_total'];
        } else {
            $this->data['intakes_total'] = $intakes_total;
        }
        
        $data7 = array();
        $data7 = array(
                'status' => 1,
                'discharge' => 2,
                'searchdate_2' => $currentDate,
                // 'role_call' => '2',
                'facilities_id' => $this->customer->getId()
        );
        
        $dischargetags_total = $this->model_setting_tags->getTotalTags($data7);
        // var_dump($dischargetags_total);
        if (isset($this->request->post['discharge_total'])) {
            $this->data['discharge_total'] = $this->request->post['discharge_total'];
        } else {
            $this->data['discharge_total'] = $dischargetags_total;
        }
        
        $data6 = array();
        $data6 = array(
                'status' => 1,
                // 'searchdate' => $currentDate,
                'discharge' => 1,
                'role_call' => '2',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $offsitetags_total = $this->model_setting_tags->getTotalTags($data6);
        
        if (isset($this->request->post['offsite_total'])) {
            $this->data['offsite_total'] = $this->request->post['offsite_total'];
        } else {
            $this->data['offsite_total'] = $offsitetags_total;
        }
        
        $data3 = array();
        $data3 = array(
                'status' => 1,
                'discharge' => 1,
                'role_call' => '1',
                // 'searchdate' => $currentDate,
                // 'gender2' => $this->request->get['gender'],
                'sort' => 'emp_first_name',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $inhouse_total = $this->model_setting_tags->getTotalTags($data3);
        
        // var_dump($inhouse_total);
        // echo "<hr>";
        if (isset($this->request->post['inhouse_total'])) {
            $this->data['inhouse_total'] = $this->request->post['inhouse_total'];
        } else {
            $this->data['inhouse_total'] = $inhouse_total;
        }
        
        $data4 = array();
        $data4 = array(
                'status' => 1,
                'discharge' => 1,
                'date_added' => $currentDate,
                'gender' => '1',
                // 'role_call' => '1',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $males_total = $this->model_setting_tags->getTotalTags($data4);
        $this->data['maletags_total'] = $males_total;
        
        if (isset($this->request->post['males_total'])) {
            $this->data['males_total'] = $this->request->post['males_total'];
        } else {
            $this->data['males_total'] = $males_total;
        }
        
        $data5 = array();
        $data5 = array(
                'status' => 1,
                'date_added' => $currentDate,
                'discharge' => 1,
                'gender' => '2',
                // 'role_call' => '1',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $females_total = $this->model_setting_tags->getTotalTags($data5);
        $this->data['femaletags_total'] = $females_total;
        
        if (isset($this->request->post['females_total'])) {
            $this->data['females_total'] = $this->request->post['females_total'];
        } else {
            $this->data['females_total'] = $females_total;
        }
        $data51 = array();
        $data51 = array(
                'status' => 1,
                'date_added' => $currentDate,
                'discharge' => 1,
                'gender' => '3',
                // 'role_call' => '1',
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $non_specific_total = $this->model_setting_tags->getTotalTags($data51);
        $this->data['non_specific_total'] = $non_specific_total;
        
        if (isset($this->request->post['non_specific_total'])) {
            $this->data['non_specific_total'] = $this->request->post['non_specific_total'];
        } else {
            $this->data['non_specific_total'] = $non_specific_total;
        }
        
        $data8 = array();
        $data8 = array(
                'status' => 1,
                'discharge' => 1,
                'date_added' => $currentDate,
                'facilities_id' => $this->customer->getId(),
                'all_record' => '1'
        );
        
        $all_total = $this->model_setting_tags->getTotalTags($data8);
        $this->data['tags_total'] = $all_total;
        
        if (isset($this->request->post['all_total'])) {
            $this->data['all_total'] = $this->request->post['all_total'];
        } else {
            $this->data['all_total'] = $all_total;
        }
        
        if (isset($this->request->post['end_of_shift_status'])) {
            $this->data['end_of_shift_status'] = $this->request->post['end_of_shift_status'];
        } else {
            $this->data['end_of_shift_status'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        if (isset($this->session->data['success3'])) {
            $this->data['success3'] = $this->session->data['success3'];
            
            unset($this->session->data['success3']);
        } else {
            $this->data['success3'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['direct_care'])) {
            $this->data['error_direct_care'] = $this->error['direct_care'];
        } else {
            $this->data['error_direct_care'] = '';
        }
        if (isset($this->error['team_leader'])) {
            $this->data['error_team_leader'] = $this->error['team_leader'];
        } else {
            $this->data['error_team_leader'] = '';
        }
        
        $this->template = $this->config->get('config_template') . '/template/resident/dailycensus.php';
        
        $this->children = array(
                'common/headerclient',
                'common/footerclient'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validateForm ()
    {
        
        /*
         * if ($this->request->post['direct_care'] == '') {
         * $this->error['direct_care'] = 'This is required field';
         * }
         */
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['team_leaders'] == '') {
            $this->error['team_leader'] = 'This is required field';
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function insert2 ()
    {
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        
        $this->load->model('notes/notes');
        
        if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
            
            // var_dump($this->request->post);
            
            $this->model_notes_notes->updatenotes($this->request->post, $this->customer->getId(), $this->request->get['notes_id']);
            
            $notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
            $notes_description = $notes_info['notes_description'];
            
            if ($this->request->post['comments'] != null && $this->request->post['comments']) {
                $comments = ' | ' . $this->request->post['comments'];
            }
            
            $notes_description2 = $notes_description . $comments;
            
            $this->model_notes_notes->updatetagscences2($notes_description2, $this->request->get['notes_id']);
            
            $this->session->data['success3'] = '1';
            
            $url2 = "";
            if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
                $url2 .= '&searchdate=' . $this->request->get['searchdate'];
            }
            
            if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
                $url2 .= '&notes_id=' . $this->request->get['notes_id'];
            }
            
            $this->redirect(str_replace('&amp;', '&', $this->url->link('resident/dailycensus', '' . $url2, 'SSL')));
        }
        
        $this->data['entry_pin'] = $this->language->get('entry_pin');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['text_select'] = $this->language->get('text_select');
        
        $this->load->model('user/user');
        $this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());
        
        $this->data['config_tag_status'] = $this->customer->isTag();
        
        $url2 = "";
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
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
                $url2 .= '&page=' . $pagenumber_all;
            }
        }
        
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        $this->data['action2'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus/insert2', '' . $url2, 'SSL'));
        $this->data['redirect_url'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus', '' . $url2, 'SSL'));
        
        if (isset($this->session->data['pagenumber'])) {
            $this->data['pagenumber'] = $this->session->data['pagenumber'];
        } else {
            $this->data['pagenumber'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        if (isset($this->error['select_one'])) {
            $this->data['error_select_one'] = $this->error['select_one'];
        } else {
            $this->data['error_select_one'] = '';
        }
        
        if (isset($this->error['notes_pin'])) {
            $this->data['error_notes_pin'] = $this->error['notes_pin'];
        } else {
            $this->data['error_notes_pin'] = '';
        }
        
        if (isset($this->error['highlighter_id'])) {
            $this->data['error_highlighter_id'] = $this->error['highlighter_id'];
        } else {
            $this->data['error_highlighter_id'] = '';
        }
        
        if (isset($this->error['user_id'])) {
            $this->data['error_user_id'] = $this->error['user_id'];
        } else {
            $this->data['error_user_id'] = '';
        }
        
        if (isset($this->request->post['select_one'])) {
            $this->data['select_one'] = $this->request->post['select_one'];
        } else {
            if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
                $config_default_sign = '1'; // $this->config->get('config_default_sign');
            } else {
                $config_default_sign = '2';
            }
            $this->data['select_one'] = $config_default_sign;
        }
        
        if ($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != "") {
            $this->data['default_sign'] = '1'; // $this->config->get('config_default_sign');
        } else {
            $this->data['default_sign'] = '2';
        }
        
        if (isset($this->request->post['notes_pin'])) {
            $this->data['notes_pin'] = $this->request->post['notes_pin'];
        } elseif (! empty($notes_info)) {
            $this->data['notes_pin'] = $notes_info['notes_pin'];
        } else {
            $this->data['notes_pin'] = '';
        }
        
        $this->data['local_image_url'] = $this->session->data['local_image_url'];
        if (isset($this->request->post['user_id'])) {
            $this->data['user_id'] = $this->request->post['user_id'];
        } elseif (! empty($notes_info)) {
            $this->data['user_id'] = $notes_info['user_id'];
        }  elseif (! empty($this->session->data['username_confirm'])) {
            $this->data['user_id'] = $this->session->data['username_confirm'];
        }else {
            $this->data['user_id'] = '';
        }
        
        $this->load->model('setting/tags');
        $tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
        
        if (isset($this->request->post['emp_tag_id'])) {
            $this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id'] = $tag_info['emp_tag_id'];
        } else {
            $this->data['emp_tag_id'] = '';
        }
        
        if (isset($this->request->post['tags_id'])) {
            $this->data['tags_id'] = $this->request->post['tags_id'];
        } elseif (! empty($tag_info)) {
            $this->data['tags_id'] = $tag_info['tags_id'];
        } else {
            $this->data['tags_id'] = '';
        }
        
        if (isset($this->request->post['emp_tag_id_2'])) {
            $this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
        } elseif (! empty($tag_info)) {
            $this->data['emp_tag_id_2'] = $tag_info['emp_tag_id'] . ': ' . $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'];
        } else {
            $this->data['emp_tag_id_2'] = '';
        }
        
        if (isset($this->request->post['comments'])) {
            $this->data['comments'] = $this->request->post['comments'];
        } else {
            $this->data['comments'] = '';
        }
        
        $this->data['createtask'] = 1;
        
        $this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';
        $this->children = array(
                'common/headerpopup'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validateForm23 ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}
        
        if ($this->request->post['user_id'] != '') {
            $this->load->model('user/user');
            $user_info = $this->model_user_user->getUser($this->request->post['user_id']);
            
            if (empty($user_info)) {
                $this->error['user_id'] = $this->language->get('error_required');
            }
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->customer->getId());
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($user_info['customer_key'] != $customer_info['activecustomer_id']){
				$this->error['user_id'] = $this->language->get('error_customer');
			}
        }
        
        if ($this->request->post['select_one'] == '') {
            $this->error['select_one'] = $this->language->get('error_required');
        }
        
        if ($this->request->post['select_one'] == '1') {
            if ($this->request->post['notes_pin'] == '') {
                $this->error['notes_pin'] = $this->language->get('error_required');
            }
            if ($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != "") {
                $this->load->model('user/user');
                
                if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}
                
                if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
                    $this->error['warning'] = $this->language->get('error_exists');
                }
            }
        }
        
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function censusdetail ()
    {
        $this->document->setTitle('Census');
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->data['facilityname'] = $this->customer->getfacility();
        
        $this->load->model('resident/resident');
        $this->language->load('notes/notes');
        $this->load->model('notes/image');
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/notes');
        $this->load->model('facilities/facilities');
        
        $this->load->model('notes/tags');
        $this->load->model('setting/tags');
        
        $result = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
        
        $this->data['tags'] = array();
        if ($result) {
            
            $tags = $this->model_setting_tags->getTagsbyNotesID($this->request->get['notes_id']);
            
            $this->load->model('setting/locations');
            foreach ($tags as $tag) {
                $tag_info = $this->model_setting_tags->getTag($tag['tags_id']);
                
                $tags_info12 = $this->model_setting_locations->getlocation($tag_info['room']);
                
                $this->data['tags'][] = array(
                        'name' => $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'],
                        'emp_first_name' => $tag_info['emp_first_name'],
                        'emp_tag_id' => $tag_info['emp_tag_id'],
                        'tags_id' => $tag_info['tags_id'],
                        'gender' => $tag_info['gender'],
                        'upload_file' => $tag_info['upload_file'],
                        'age' => $tag_info['age'],
                        'tagstatus' => $tag_info['tagstatus'],
                        'med_mental_health' => $tag_info['med_mental_health'],
                        'alert_info' => $tag_info['alert_info'],
                        'prescription' => $tag_info['prescription'],
                        'restriction_notes' => $tag_info['restriction_notes'],
                        'room' => $tags_info12['location_name'],
                        'date_added' => date('m-d-Y', strtotime($tag_info['date_added'])),
                        
                        'lunch' => $tag['lunch'],
                        'dinner' => $tag['dinner'],
                        'breakfast' => $tag['breakfast'],
                        'refused' => $tag['refused'],
                        'stickynote' => $tag_info['stickynote']
                );
            }
        }
        
        $this->data['tags_detail_info'] = array();
        
        $tagsdetail_info = $this->model_setting_tags->getTagsdetailbyNotesID($this->request->get['notes_id']);
        
        $this->load->model('setting/shift');
        
        $shift_info = $this->model_setting_shift->getshift($tagsdetail_info['shift_id']);
        
        $this->data['tags_detail_info']['census_date'] = date('m-d-Y', strtotime($tagsdetail_info['census_date']));
        
        if ($tagsdetail_info['team_leader'] != NULL && $tagsdetail_info['team_leader'] != "") {
            $othervalues1 = explode(',', $tagsdetail_info['team_leader']);
            
            $this->load->model('user/user');
            
            $a1 = "";
            
            foreach ($othervalues1 as $othervalues11) {
                $user_info = $this->model_user_user->getUserbyupdate($othervalues11);
                $a1 .= $user_info['username'] . ',';
            }
        }
        
        $this->data['tags_detail_info']['team_leader'] = $a1;
        $this->data['tags_detail_info']['direct_care'] = $tagsdetail_info['direct_care'];
        
        if ($tagsdetail_info['comment_box'] != NULL && $tagsdetail_info['comment_box'] != "") {
            $othervalues = explode(',', $tagsdetail_info['comment_box']);
            
            $this->load->model('user/user');
            
            $a = "";
            $a111 = array();
            foreach ($othervalues as $othervalue) {
                $user_info = $this->model_user_user->getUserbyupdate($othervalue);
                $a .= $user_info['username'] . ',';
            }
        }
        
        $this->data['tags_detail_info']['comment_box'] = $a;
        $this->data['tags_detail_info']['spm'] = $tagsdetail_info['spm'];
        $this->data['tags_detail_info']['as_spm'] = $tagsdetail_info['as_spm'];
        $this->data['tags_detail_info']['case_manager'] = $tagsdetail_info['case_manager'];
        $this->data['tags_detail_info']['food_services'] = $tagsdetail_info['food_services'];
        $this->data['tags_detail_info']['educational_staff'] = $tagsdetail_info['educational_staff'];
        $this->data['tags_detail_info']['screenings'] = $tagsdetail_info['screenings'];
        $this->data['tags_detail_info']['intakes'] = $tagsdetail_info['intakes'];
        $this->data['tags_detail_info']['discharge'] = $tagsdetail_info['discharge'];
        $this->data['tags_detail_info']['offsite'] = $tagsdetail_info['offsite'];
        $this->data['tags_detail_info']['in_house'] = $tagsdetail_info['in_house'];
        $this->data['tags_detail_info']['males'] = $tagsdetail_info['males'];
        $this->data['tags_detail_info']['females'] = $tagsdetail_info['females'];
        $this->data['tags_detail_info']['non_specific_total'] = $tagsdetail_info['non_specific_total'];
        $this->data['tags_detail_info']['total'] = $tagsdetail_info['total'];
        $this->data['tags_detail_info']['end_of_shift_status'] = $tagsdetail_info['end_of_shift_status'];
        
        $this->data['tags_detail_info']['shift_name'] = $shift_info['shift_name'];
        
        $url2 = "";
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        if ($this->request->get['page'] != null && $this->request->get['page'] != "") {
            $url2 .= '&page=' . $this->request->get['page'];
        }
        if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
            $url2 .= '&notes_id=' . $this->request->get['notes_id'];
        }
        
        $this->data['close'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus', '' . $url2, 'SSL'));
        $this->data['print_url'] = str_replace('&amp;', '&', $this->url->link('resident/dailycensus/printcensus', '' . $url2, 'SSL'));
        
        $this->data['isandroid'] = $this->request->get['isandroid'];
        
        $this->template = $this->config->get('config_template') . '/template/resident/dailycensusdetil.php';
        
        $this->children = array(
                'common/headerpopup'
        );
        $this->response->setOutput($this->render());
    }

    public function printcensus ()
    {
        $this->load->model('resident/resident');
        $this->language->load('notes/notes');
        $this->load->model('notes/image');
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/notes');
        $this->load->model('facilities/facilities');
        
        $this->load->model('notes/tags');
        $this->load->model('setting/tags');
        $this->load->model('setting/locations');
        
        $result = $this->model_notes_notes->getnotes($this->request->get['notes_id']);
        
        $alltags = array();
        if ($result) {
            
            $tags = $this->model_setting_tags->getTagsbyNotesID($this->request->get['notes_id']);
            
            foreach ($tags as $tag) {
                $tag_info = $this->model_setting_tags->getTag($tag['tags_id']);
                $tags_info12 = $this->model_setting_locations->getlocation($tag_info['room']);
                
                $alltags[] = array(
                        'name' => $tag_info['emp_first_name'] . ' ' . $tag_info['emp_last_name'],
                        'emp_first_name' => $tag_info['emp_first_name'],
                        'emp_tag_id' => $tag_info['emp_tag_id'],
                        'tags_id' => $tag_info['tags_id'],
                        'gender' => $tag_info['gender'],
                        'upload_file' => $tag_info['upload_file'],
                        'age' => $tag_info['age'],
                        'tagstatus' => $tag_info['tagstatus'],
                        'med_mental_health' => $tag_info['med_mental_health'],
                        'alert_info' => $tag_info['alert_info'],
                        'prescription' => $tag_info['prescription'],
                        'restriction_notes' => $tag_info['restriction_notes'],
                        'room' => $tags_info12['location_name'],
                        'date_added' => date('m-d-Y', strtotime($tag_info['date_added'])),
                        'lunch' => $tag['lunch'],
                        'dinner' => $tag['dinner'],
                        'breakfast' => $tag['breakfast'],
                        'refused' => $tag['refused'],
                        'stickynote' => $tag_info['stickynote']
                );
            }
        }
        
        $tags_detail_info = array();
        
        $tagsdetail_info = $this->model_setting_tags->getTagsdetailbyNotesID($this->request->get['notes_id']);
        
        $this->load->model('setting/shift');
        
        $shift_info = $this->model_setting_shift->getshift($tagsdetail_info['shift_id']);
        
        $tags_detail_info['census_date'] = date('m-d-Y', strtotime($tagsdetail_info['census_date']));
        $a1 = "";
        if ($tagsdetail_info['team_leader'] != NULL && $tagsdetail_info['team_leader'] != "") {
            $othervalues1 = explode(',', $tagsdetail_info['team_leader']);
            
            $this->load->model('user/user');
            
            $a111 = array();
            foreach ($othervalues1 as $othervalues11) {
                $user_info = $this->model_user_user->getUserbyupdate($othervalues11);
                $a1 .= $user_info['username'] . ',';
            }
        }
        
        $tags_detail_info['team_leader'] = $a1;
        $tags_detail_info['direct_care'] = $tagsdetail_info['direct_care'];
        $a = "";
        if ($tagsdetail_info['comment_box'] != NULL && $tagsdetail_info['comment_box'] != "") {
            $othervalues = explode(',', $tagsdetail_info['comment_box']);
            
            $this->load->model('user/user');
            
            $numItems1 = count($othervalues) - 1;
            
            $i1 = 0;
            foreach ($othervalues as $othervalue) {
                $user_info = $this->model_user_user->getUserbyupdate($othervalue);
                if ($i1 == $numItems1) {
                    $a .= $user_info['username'];
                } else {
                    $a .= $user_info['username'] . ', ';
                }
                $i1 ++;
            }
        }
        
        $tags_detail_info['comment_box'] = $a;
        $tags_detail_info['spm'] = $tagsdetail_info['spm'];
        $tags_detail_info['as_spm'] = $tagsdetail_info['as_spm'];
        $tags_detail_info['case_manager'] = $tagsdetail_info['case_manager'];
        $tags_detail_info['food_services'] = $tagsdetail_info['food_services'];
        $tags_detail_info['educational_staff'] = $tagsdetail_info['educational_staff'];
        $tags_detail_info['screenings'] = $tagsdetail_info['screenings'];
        $tags_detail_info['intakes'] = $tagsdetail_info['intakes'];
        $tags_detail_info['discharge'] = $tagsdetail_info['discharge'];
        $tags_detail_info['offsite'] = $tagsdetail_info['offsite'];
        $tags_detail_info['in_house'] = $tagsdetail_info['in_house'];
        $tags_detail_info['males'] = $tagsdetail_info['males'];
        $tags_detail_info['females'] = $tagsdetail_info['females'];
        $tags_detail_info['non_specific_total'] = $tagsdetail_info['non_specific_total'];
        $tags_detail_info['total'] = $tagsdetail_info['total'];
        $tags_detail_info['end_of_shift_status'] = $tagsdetail_info['end_of_shift_status'];
        
        $tags_detail_info['shift_name'] = $shift_info['shift_name'];
        
        require_once (DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
        // create new PDF document
        
        $pageLayout = array(
                '612.00',
                '1008.00'
        );
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageLayout, true, 'UTF-8', false);
        
        // $pdf = new TCPDF('L', 'pt', $pageLayout, true, 'UTF-8', false);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('');
        $pdf->SetTitle('Census');
        $pdf->SetSubject('Census');
        $pdf->SetKeywords('Census');
        $pdf->SetMargins('5', '5', '5');
        $pdf->SetHeaderMargin('5');
        $pdf->SetFooterMargin('5');
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('helvetica', '', 20);
        $pdf->AddPage();
        
        $html = '';
        $html .= '<style>

		td {
			padding: 10px;
			margin: 10px;
		   border: 1px solid #B8b8b8;
		   line-height:40.2px;
		   display:table-cell;
			padding:5px;
		}
		</style>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;"  >Shift Review Revised 9/00, 1/03, 8/03,7/04, 3/05, 6/11</td>';
        $html .= '<td style="padding:20px;text-align:center;"  >Please reassess youth upon return from Baker Act.</td>';
        $html .= '<td style="padding:20px;text-align:left;"  >Crystal: 123-1245-2154 </td>';
        
        $html .= '</tr>';
        
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;"  >SHIFT: ' . $tags_detail_info['shift_name'] . '</td>';
        $html .= '<td style="padding:20px;text-align:center;"  >Runaways 369-7070</td>';
        $html .= '<td style="padding:20px;text-align:left;"  >Jackie Robles 352-216-3153 Wkend + TS</td>';
        
        $html .= '</tr>';
        
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:center;"  >CLIENTS</td>';
        $html .= '<td style="padding:20px;text-align:center;"  >CLIENT INFORMATION</td>';
        $html .= '<td style="padding:20px;text-align:center;"  >ON CALL FOR SHELTER:</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;" colspan="2" >';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        
        $html .= '<tr style="background:#ccc;">';
        // $html.='<td style="width:6%;"><b>Cr</b></td>';
        $html .= '<td style="width:6%;"><b>Room</b></td>';
        $html .= '<td style="width:10%;"><b>Intake Date</b></td>';
        $html .= '<td style="width:13%;padding:10px;"><b>Name</b></td>';
        $html .= '<td style="width:5%;"><b>Age</b></td>';
        $html .= '<td style="width:6%;"><b>Status</b></td>';
        $html .= '<td style="width:5%;"><b>Mental Health</b></td>';
        $html .= '<td style="width:8%;"><b>Alert Information</b></td>';
        $html .= '<td style="width:8%;"><b>Prescriptions</b></td>';
        $html .= '<td style="width:15%;"><b>Restriction/Notes/Goal</b></td>';
        $html .= '<td style="width:10%;"><b>Sticky Note</b></td>';
        $html .= '<td style="width:14%;"><b>Meal</b></td>';
        $html .= '</tr>';
        
        foreach ($alltags as $tag) {
            $html .= '<tr>';
            // $html.='<td style="width:6%;"></td>';
            $html .= '<td style="width:6%;">' . $tag['room'] . '</td>';
            $html .= '<td style="width:10%;">' . $tag['date_added'] . '</td>';
            $html .= '<td style="width:13%;padding:10px;">' . $tag['name'] . '</td>';
            $html .= '<td style="width:5%;">' . $tag['age'] . '</td>';
            $html .= '<td style="width:6%;">' . $tag['tagstatus'] . '</td>';
            $html .= '<td style="width:5%;">' . $tag['med_mental_health'] . '</td>';
            $html .= '<td style="width:8%;">' . $tag['alert_info'] . '</td>';
            $html .= '<td style="width:8%;">' . $tag['prescription'] . '</td>';
            $html .= '<td style="width:15%;">' . $tag['restriction_notes'] . '</td>';
            $html .= '<td style="width:10%;">' . $tag['stickynote'] . '</td>';
            $html .= '<td style="width:14%;">';
            
            $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
            $html .= '<tr>';
            $html .= '<td style="width:12%;border:none;">';
            
            if ($tag['breakfast'] == 'B') {
                $html .= '<img src="image/box_cross.png" width="25px" height="25px" alt="" />';
            } else {
                $html .= '<img src="image/box.png" width="25px" height="25px" alt="" />';
            }
            
            $html .= '</td>';
            $html .= '<td style="width:13%;border:none;">B </td>';
            
            $html .= '<td style="width:12%;border:none;">';
            
            if ($tag['lunch'] == 'L') {
                $html .= '<img src="image/box_cross.png" width="25px" height="25px" alt="" />';
            } else {
                $html .= '<img src="image/box.png" width="25px" height="25px" alt="" />';
            }
            
            $html .= '</td>';
            $html .= '<td style="width:13%;border:none;">L </td>';
            
            $html .= '<td style="width:12%;border:none;">';
            
            if ($tag['dinner'] == 'D') {
                $html .= '<img src="image/box_cross.png" width="25px" height="25px" alt="" />';
            } else {
                $html .= '<img src="image/box.png" width="25px" height="25px" alt="" />';
            }
            
            $html .= '</td>';
            $html .= '<td style="width:13%;border:none;">D </td>';
            
            $html .= '<td style="width:12%;border:none;">';
            if ($tag['refused'] == 'R') {
                $html .= '<img src="image/box_cross.png" width="25px" height="25px" alt="" />';
            } else {
                $html .= '<img src="image/box.png" width="25px" height="25px" alt="" />';
            }
            
            $html .= '</td>';
            $html .= '<td style="width:13%;border:none;">R </td>';
            $html .= '</tr>';
            $html .= '</table>';
            
            $html .= '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        $html .= '</td>';
        $html .= '<td style="padding:20px;text-align:left;"  >';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:left;width:100%;" colspan="2">';
        $html .= 'Mark : (C)789-9526 (H) 867-9884<br> Denise: (C)362-0510';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= 'Team Leader';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= $tags_detail_info['team_leader'];
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= 'Direct Care Workers';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:left;width:100%;" colspan="2">';
        $html .= 'OTHER STAFF:';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'SPM';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        if ($tags_detail_info['spm'] == 'SPM') {
            $html .= 'Y';
        } else {
            $html .= 'N';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Asst. SPM';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        if ($tags_detail_info['as_spm'] == 'Asst. SPM') {
            $html .= 'Y';
        } else {
            $html .= 'N';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'CASE MANAGER';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        if ($tags_detail_info['case_manager'] == 'CASE MANAGER') {
            $html .= 'Y';
        } else {
            $html .= 'N';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'FOOD SERVICES COORD';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        if ($tags_detail_info['food_services'] == 'FOOD SERVICES COORD') {
            $html .= 'Y';
        } else {
            $html .= 'N';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'EDUCATIONAL STAFF';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        if ($tags_detail_info['educational_staff'] == 'EDUCATIONAL STAFF') {
            $html .= 'Y';
        } else {
            $html .= 'N';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= 'Shift Staff';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= $tags_detail_info['comment_box'];
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= 'END OF SHIFT STATUS';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= $tags_detail_info['end_of_shift_status'];
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'TOTAL SCREENINGS:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['screenings'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'INTAKES:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['intakes'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'DISCHARGE:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['discharge'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Offsite:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['offsite'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'In house:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['in_house'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Males:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['males'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Females:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['females'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Non-Specific:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['non_specific_total'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'Total:';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:right;width:20%;" >';
        $html .= $tags_detail_info['total'];
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:left;width:100%;" colspan="2">';
        $html .= 'KEY CONTROL';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td style="padding:20px;text-align:center;width:100%;" colspan="2">';
        $html .= '';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:80%;" >';
        $html .= 'PASSED:';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 5 Sets';
        $html .= '</td>';
        
        $html .= '<td style="padding:20px;text-align:left;width:20%;" >';
        $html .= 'Y/N';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '</td>';
        
        $html .= '</tr>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"><b>VISITS: </b></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"><b>PRIVILEGES: </b></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"><b>APPOINTMENTS: </b></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"><b>COMMUNICATIONS: </b></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"><b>MONITOR: </b></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        
        $html .= '<td style="padding:20px;text-align:left;width:100%;" >';
        $html .= '<table width="100%" style="border:none;" cellpadding="2" cellspacing="0" >';
        $html .= '<tr>';
        $html .= '<td colspan="2"></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        /*
         * $html.='<table width="100%" style="border:none;" cellpadding="2"
         * cellspacing="0" >';
         * $html.='<tr>';
         *
         * $html.='<td style="padding:20px;text-align:left;width:100%;" >';
         * $html.='<table width="100%" style="border:none;" cellpadding="2"
         * cellspacing="0" >';
         * $html.='<tr>';
         * $html.='<td colspan="2"></td>';
         *
         * $html.='</tr>';
         * $html.='</table>';
         * $html.='</td>';
         *
         * $html.='</tr>';
         * $html.='</table>';
         *
         * $html.='<table width="100%" style="border:none;" cellpadding="2"
         * cellspacing="0" >';
         * $html.='<tr>';
         *
         * $html.='<td style="padding:20px;text-align:left;width:100%;" >';
         * $html.='<table width="100%" style="border:none;" cellpadding="2"
         * cellspacing="0" >';
         * $html.='<tr>';
         * $html.='<td colspan="2"></td>';
         *
         * $html.='</tr>';
         * $html.='</table>';
         * $html.='</td>';
         *
         * $html.='</tr>';
         * $html.='</table>';
         */
        
        $html .= '</table>';
        
        // var_dump($html);
        
        // die;
        
        $pdf->writeHTML($html, true, 0, true, 0);
        $pdf->lastPage();
        $pdf->Output('census_' . rand() . '.pdf', 'I');
        exit();
    }
}