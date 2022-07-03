<?php

class ControllerCaseSearchresult extends Controller
{

    public function super_unique ($array, $key)
    {
        $temp_array = [];
        foreach ($array as &$v) {
            if (! isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] = & $v;
        }
        $array = array_values($temp_array);
        return $array;
    }

    public function index ()
    {
        $this->language->load('common/home');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        
        $this->data['heading_title'] = $this->config->get('config_title');
        
        $this->data['error2'] = $this->request->get['error2'];
        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $this->data['tagdata'] = $this->model_setting_tags->getTag($this->request->get['tags_id']);
        }
        
        $url2 = "";
        $url3 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 = '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 .= '&searchdate=' . $this->request->get['searchdate'];
        }
        
        if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
            $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
            $this->data['tags_id'] = $this->request->get['tags_id'];
        }
        
        if ($this->request->get['search_tags'] != null && $this->request->get['search_tags'] != "") {
            $this->data['search_tags'] = $this->request->get['search_tags'];
            
            $search_tags1 = explode(":", $this->request->get['search_tags']);
            $search_tags = $search_tags1[0];
        }
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            // $url2 .= '&note_date_from=' .
            // $this->request->get['note_date_from'];
            $url3 .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            // $url2 .= '&note_date_to=' . $this->request->get['note_date_to'];
            $url3 .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        
		
		//var_dump($url2);
		
        $this->data['action222'] = str_replace('&amp;', '&', $this->url->link('common/home', '' . $url3, 'SSL'));
        
        $this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url2, 'SSL');
        $this->data['printUlr'] = $this->url->link('notes/notes/generatePdf', '' . $url2, 'SSL');
        
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
            $this->data['facilities_id'] = $facilities_id;
        } else {
            $facilities_id = $this->customer->getId();
            $this->data['facilities_id'] = $facilities_id;
            
            if (! $this->customer->isLogged()) {
                $this->redirect($this->url->link('common/login', '', 'SSL'));
            }
        }
        $this->load->model('facilities/facilities');
        $this->load->model('setting/timezone');
        
        $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
        $timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
        
        date_default_timezone_set($timezone_info['timezone_value']);
        $current_date_m = date('Y-m', strtotime('now'));
		
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $this->load->model('notes/notes');
            $this->load->model('form/form');
            $this->load->model('createtask/createtask');
            $this->load->model('setting/tags');
            $this->load->model('notes/case');
            
            // $years = array("2018","2017");
            // $years = array("2018");
            // $months =
            // array("12","11","10","09","08","07","06","05","04","03","02","01");
            
            // var_dump($m);
            // $months =
            // array("12","11","10","09","08","07","06","05","04","03","02","01");
            
            $years = array(
                    date('Y'),
                   // date('Y', strtotime('-1 years'))
            );
            $m = date('Y-m');
            
            /*
             * for($i = $m; $i >= ($m-2); $i--){
             *
             * $mmm = strlen($i);
             * if($mmm == '1'){
             * $monval = '0'.$i;
             * }else{
             * $monval = $i;
             * }
             * $months[] = (string)$monval;
             * }
             */

            if($this->request->get['archive']){

                $currentm_date = date('Y-m-d');

                //$m = date('Y-m',strtotime($this->request->get['note_date_from']));

               // $currentmlast = date("Y-m", strtotime(date("Y-m", strtotime($this->request->get['note_date_from'])). "+1 month"));
                //$currentmlast2 = date("Y-m", strtotime(date("Y-m", strtotime($this->request->get['note_date_to'])). "+2 month"));


                $currentmlast = date("Y-m", strtotime($this->request->get['note_date_from']));
                $currentmlast2= date("Y-m", strtotime($this->request->get['note_date_to']));



            }else{
                $currentm_date = date('Y-m-d');
                $currentmlast = date("Y-m", strtotime(date("Y-m-d H:i", strtotime($currentm_date)) . "-1 month"));
                $currentmlast2 = date("Y-m", strtotime(date("Y-m-d H:i", strtotime($currentm_date)) . "-3 month"));
            }

            $months = array(
                $m,
                $currentmlast,
                $currentmlast2
            );
			
			//echo '<pre>'; print_r($months); echo '</pre>';
            
            //foreach ($years as $year) {
                
            $notemonths = array();

            foreach ($months as $month) {
				//var_dump($month);
                
				$res = explode("-", $month);
				//var_dump($res);
				$year = $res[0];
				$month = $res[1];
                $lastDayThisMonth = date("t");
                
                $note_date_from = '01-' . $month . '-' . $year;
                $note_date_to = $lastDayThisMonth . '-' . $month . '-' . $year;
                
                $date_current_to = $year . '-' . $month;
                
                
                //print_r($date_current_to);
               // print_r($current_date_m);
                //echo "<hr>";
                 
                if ($date_current_to == $current_date_m) {
                    $dd_dates = array();
                    $dd_dates2 = array();
                    $in_dates = array();
                    $in_dates2 = array();
                    
                    $data2 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $note_date_from22 = $year . '-' . $month . '-01';
                    
                    $ttotalnotes = $this->model_notes_case->getTotalnotessmain($data2);
                    
                    $data12 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'form_search' => 'all',
                            'facilities_id' => $facilities_id
                    );
                    
                    $ttotalformss = $this->model_notes_case->getTotalnotessmain($data12);
                    
                    // var_dump($ttotalformss);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $ttotalforms = $ttotalformss;
                    } else {
                        $ttotalforms = '0';
                    }
                    
                    $data1dd2 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'task_search' => 'all',
                            'facilities_id' => $facilities_id
                    );
                    
                    $ttotaltaskss = $this->model_notes_case->getTotalnotessmain($data1dd2);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $ttotaltasks = $ttotaltaskss;
                    } else {
                        $ttotaltasks = '0';
                    }
                    
                    $data3 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            // 'discharge' => '1',
                            'tags_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $intakecounts = $this->model_setting_tags->getTotalTags($data3);
                    // var_dump($intakecounts);
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $intakecount = $intakecounts;
                    } else {
                        $intakecount = '0';
                    }
                    
                    $data4 = array(
                            'dnote_date_from' => $note_date_from,
                            'dnote_date_to' => $note_date_to,
                            'discharge' => '2',
                            'tags_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $dischargecounts = $this->model_setting_tags->getTotalTags($data4);
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $dischargecount = $dischargecounts;
                    } else {
                        $dischargecount = '0';
                    }
                    
                    $data5 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'activenote' => '44',
                            'keyword' => 'incident',
                            'search_acitvenote_with_keyword' => '1',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $incidentcounts = $this->model_notes_case->getTotalnotessmain($data5);
                    
                    $data11 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'activenote' => '38',
                            'keyword' => 'medication',
                            'search_acitvenote_with_keyword' => '1',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $pillcallcount = $this->model_notes_case->getTotalnotessmain($data11);
                    
                    // var_dump($incidentcounts);
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        
                        $incidentcount = $incidentcounts;
                        
                        // $this->load->model('setting/tags');
                        // $tag_info =
                    // $this->model_setting_tags->getTag($this->request->get['tags_id']);
                    } else {
                        $incidentcount = '0';
                    }
                    
                    $data6 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'tasktype' => '25',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $sightandsoundcounts = $this->model_notes_case->getTotalnotessmain($data6);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $sightandsoundcount = $sightandsoundcounts;
                    } else {
                        $sightandsoundcount = '0';
                    }
                    
                    $data7 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'highlighter' => 'all',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $highlightercounts = $this->model_notes_case->getTotalnotessmain($data7);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $highlightercount = $highlightercounts;
                    } else {
                        $highlightercount = '0';
                    }
                    
                    $data8 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'text_color' => '1',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $colorcounts = $this->model_notes_case->getTotalnotessmain($data8);
                    
                    // var_dump($colorcounts);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $colorcount = $colorcounts;
                    } else {
                        $colorcount = '0';
                    }
                    
                    $data9 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'review_notes' => '1',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $reviewcounts = $this->model_notes_case->getTotalnotessmain($data9);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $reviewcount = $reviewcounts;
                    } else {
                        $reviewcount = '0';
                    }
                    
                    $data10 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'activenote' => 'all',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $activenotecounts = $this->model_notes_case->getTotalnotessmain($data10);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $activenotecount = $activenotecounts;
                    } else {
                        $activenotecount = '0';
                    }
                    
                    $data11 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'tasktype' => '11',
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                    );
                    
                    $becdcheckcounts = $this->model_notes_case->getTotalnotessmain($data11);
                    
                    if ($this->request->get['tags_id'] != NULL && $this->request->get['tags_id'] != '') {
                        $becdcheckcount = $becdcheckcounts;
                    } else {
                        $becdcheckcount = '0';
                    }
                    
                    $intakecounts_1 = $this->model_setting_tags->getcaseTotalTags($data3);
                    
                    // var_dump($intakecounts_1);
                    
                    if (! empty($intakecounts_1)) {

                        foreach ($intakecounts_1 as $intakecounts_11) {
                            if ($intakecounts_11['a_discharge_date'] != "null" && $intakecounts_11['a_discharge_date'] != "") {
                                $dd_dates[] = date('m-d-Y', strtotime($intakecounts_11['a_discharge_date']));
                                $dd_dates2[] = date('m-Y', strtotime($intakecounts_11['a_discharge_date']));
                            } else {
                                if ($intakecounts_11['t_discharge_date'] != "null" && $intakecounts_11['t_discharge_date'] != "") {
                                    $dd_dates[] = date('m-d-Y', strtotime($intakecounts_11['t_discharge_date']));
                                    $dd_dates2[] = date('m-Y', strtotime($intakecounts_11['t_discharge_date']));
                                }
                            }
                            if ($intakecounts_11['a_date_added'] != "null" && $intakecounts_11['a_date_added'] != "") {
                                $in_dates[] = date('m-d-Y', strtotime($intakecounts_11['a_date_added']));
                                $in_dates2[] = date('m-Y', strtotime($intakecounts_11['a_date_added']));
                            } else {
                                if ($intakecounts_11['t_date_added'] != "null" && $intakecounts_11['t_date_added'] != "") {
                                    $in_dates[] = date('m-d-Y', strtotime($intakecounts_11['t_date_added']));
                                    $in_dates2[] = date('m-Y', strtotime($intakecounts_11['t_date_added']));
                                }
                            }
                        }

                    }
                    
                    // var_dump($in_dates);
                    $dd_dates1 = array_unique($dd_dates);
                    $dd_dates12 = array_unique($dd_dates2);
                    
                    $in_dates1 = array_unique($in_dates);
                    $in_dates12 = array_unique($in_dates2);
                    
                    // var_dump($dd_dates12);
                    //var_dump($ttotalnotes);
                    
                    if ($ttotalnotes > 0) {

                        $notemonths[] = array(
                            'dd_dates' => $dd_dates1,
                            'in_dates' => $in_dates1,
                            
                            'dd_dates12' => $dd_dates12,
                            'in_dates12' => $in_dates12,
                            
                            'month11' => date('m-Y', strtotime($note_date_from22)),
                            'month' => date('F , Y', strtotime($note_date_from22)),
                            'ttotalnotes' => $ttotalnotes,
                            'ttotalnotes_url' => $this->url->link('case/searchresult/report', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            'ttotalforms' => $ttotalforms,
                            'ttotalforms_url' => $this->url->link('case/searchresult/report&form=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            'ttotaltasks' => $ttotaltasks,
                            
                            'ttotaltasks_url' => $this->url->link('case/searchresult/report&task=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalsightandsound_url' => $this->url->link('case/searchresult/report&sightandsound=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalincident_url' => $this->url->link('case/searchresult/report&incident=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'totalhighlighter_url' => $this->url->link('case/searchresult/report&highlighter=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalactivenote_url' => $this->url->link('case/searchresult/report&activenote=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalcolor_url' => $this->url->link('case/searchresult/report&color=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalreview_url' => $this->url->link('case/searchresult/report&review=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'print_url' => $this->url->link('notes/notes/generatePdf', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            'intakecount' => $intakecount,
                            'dischargecount' => $dischargecount,
                            'incidentcount' => $incidentcount,
                            'sightandsoundcount' => $sightandsoundcount,
                            
                            'highlightercount' => $highlightercount,
                            'activenotecount' => $activenotecount,
                            'colorcount' => $colorcount,
                            'reviewcount' => $reviewcount,
                            'becdcheckcount' => $becdcheckcount,
                            
                            'pillcallcount' => $pillcallcount,
                            'ttotalpillcall_url' => $this->url->link('case/searchresult/report&pillcall=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL')
                        );
                    }
                } else {

                    if ($current_date_m > $year . '-' . $month) {
                        $data2 = array(
                            'note_date_from' => $note_date_from,
                            'note_date_to' => $note_date_to,
                            'emp_tag_id' => $this->request->get['tags_id'],
                            'facilities_id' => $facilities_id
                        );
                        
                        $note_date_from22 = $year . '-' . $month . '-01';
                        
                        $casedetails = $this->model_notes_case->getcasedetails($data2);
                        
                         //var_dump($casedetails);
                        // echo "<hr>";
                        
                        if ($casedetails > 0) {
                            $ttotalnotes = 0;
                            $ttotalforms = 0;
                            $ttotaltasks = 0;
                            $incidentcount = 0;
                            $sightandsoundcount = 0;
                            $highlightercount = 0;
                            $activenotecount = 0;
                            $colorcount = 0;
                            $reviewcount = 0;
                            $becdcheckcount = 0;
                            $pillcallcount = 0;
                            $dd_dates = array();
                            $dd_dates2 = array();
                            $in_dates = array();
                            $in_dates2 = array();

                            foreach ($casedetails as $casedetail) {
                                $ttotalnotes = $ttotalnotes + $casedetail['notescount'];
                                $ttotalforms = $ttotalforms + $casedetail['formscount'];
                                $ttotaltasks = $ttotaltasks + $casedetail['taskcount'];
                                $incidentcount = $incidentcount + $casedetail['incidentcount'];
                                $sightandsoundcount = $sightandsoundcount + $casedetail['sightandsoundcount'];
                                $highlightercount = $highlightercount + $casedetail['highlightercount'];
                                $activenotecount = $activenotecount + $casedetail['activenotecount'];
                                $colorcount = $colorcount + $casedetail['colourcount'];
                                $reviewcount = $reviewcount + $casedetail['reviewcount'];
                                $becdcheckcount = $becdcheckcount + $casedetail['becdcheckcount'];
                                $pillcallcount = $pillcallcount + $casedetail['pillcallcount'];
                                
                                if ($casedetail['discharge_date'] != "0000-00-00 00:00:00") {
                                    $dd_dates[] = date('m-d-Y', strtotime($casedetail['discharge_date']));
                                    $dd_dates2[] = date('m-Y', strtotime($casedetail['discharge_date']));
                                }
                                if ($casedetail['intake_date'] != "0000-00-00 00:00:00") {
                                    $in_dates[] = date('m-d-Y', strtotime($casedetail['intake_date']));
                                    $in_dates2[] = date('m-Y', strtotime($casedetail['intake_date']));
                                }
                            }
                            
                            $dd_dates1 = array_unique($dd_dates);
                            $dd_dates12 = array_unique($dd_dates2);
                            
                            $in_dates1 = array_unique($in_dates);
                            $in_dates12 = array_unique($in_dates2);
                            
                            // var_dump($dd_dates12);
                            // var_dump($in_dates12);
                            
                            $notemonths[] = array(
                                    'dd_dates' => $dd_dates1,
                                    'in_dates' => $in_dates1,
                                    
                                    'dd_dates12' => $dd_dates12,
                                    'in_dates12' => $in_dates12,
                                    
                                    'month11' => date('m-Y', strtotime($note_date_from22)),
                                    'month' => date('F , Y', strtotime($note_date_from22)),
                                    'ttotalnotes' => $ttotalnotes,
                                    'ttotalnotes_url' => $this->url->link('case/searchresult/report', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    'ttotalforms' => $ttotalforms,
                                    'ttotalforms_url' => $this->url->link('case/searchresult/report&form=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    'ttotaltasks' => $ttotaltasks,
                                    
                                    'ttotaltasks_url' => $this->url->link('case/searchresult/report&task=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'ttotalsightandsound_url' => $this->url->link('case/searchresult/report&sightandsound=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'ttotalincident_url' => $this->url->link('case/searchresult/report&incident=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'totalhighlighter_url' => $this->url->link('case/searchresult/report&highlighter=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'ttotalactivenote_url' => $this->url->link('case/searchresult/report&activenote=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'ttotalcolor_url' => $this->url->link('case/searchresult/report&color=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'ttotalreview_url' => $this->url->link('case/searchresult/report&review=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    
                                    'print_url' => $this->url->link('notes/notes/generatePdf', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                                    // 'intakecount'=> $intakecount,
                                    // 'dischargecount'=> $dischargecount,
                                    'incidentcount' => $incidentcount,
                                    'sightandsoundcount' => $sightandsoundcount,
                                    
                                    'highlightercount' => $highlightercount,
                                    'activenotecount' => $activenotecount,
                                    'colorcount' => $colorcount,
                                    'reviewcount' => $reviewcount,
                                    'becdcheckcount' => $becdcheckcount,
                                    
                                    'pillcallcount' => $pillcallcount,
                                    'ttotalpillcall_url' => $this->url->link('case/searchresult/report&pillcall=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL')
                            )
                            ;
                        }
                    }
                }
            }
                
                $this->data['noteyears'][] = array(
                    'year' => $year,
                    'notemonths' => $notemonths,
                    'lastdate' => $note_date_from
                );

                //echo '<pre>'; print_r($this->data['noteyears']); echo '</pre>';


            //}
        }
        
        $this->template = $this->config->get('config_template') . '/template/case/report.php';
        
        $this->response->setOutput($this->render());
    }

    public function report ()
    {
        $this->language->load('common/home');
        
        $this->language->load('notes/notes');
        
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        $this->data['heading_title'] = $this->config->get('config_title');
        
        $this->data['error2'] = $this->request->get['error2'];
        
        $url2 = "";
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $url2 = '&searchdate=' . $this->request->get['searchdate'];
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
        
        if ($this->request->get['last_notesID'] != null && $this->request->get['last_notesID'] != "") {
            $url2 .= '&last_notesID=' . $this->request->get['last_notesID'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url2 .= '&tags_id=' . $this->request->get['tags_id'];
        }
        
        $this->data['searchUlr'] = $this->url->link('notes/notes/search', '' . $url2, 'SSL');
        $this->data['printUlr'] = $this->url->link('notes/notes/generatePdf', '' . $url2, 'SSL');
        
        if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = $this->customer->getId();
        }
        
        $this->data['config_taskform_status'] = $this->customer->isTaskform();
        $this->data['config_noteform_status'] = $this->customer->isNoteform();
        $this->data['config_rules_status'] = $this->customer->isRule();
        $this->data['config_share_notes'] = $this->customer->isNotesShare();
        $this->data['config_multiple_activenote'] = $this->customer->isMactivenote();
        
        $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url2, 'SSL');
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL');
        $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url2, 'SSL');
        
        $this->data['customIntake_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        $this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
        
        $this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL');
        
        $this->data['bedcheck_url'] = $this->url->link('notes/notes/generatePdf&is_bedchk=1', '' . $url2, 'SSL');
        
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL');
        $this->data['customIntake_url'] = $this->url->link('notes/tags/addclient', '' . $url2, 'SSL');
        
        $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url2, 'SSL');
        
        $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url2, 'SSL');
        
        $this->data['attachment_sign_url'] = $this->url->link('notes/notes/attachmentSign', '' . $url2, 'SSL');
        
        $this->data['naotes_tags_url'] = $this->url->link('notes/notes/updateTags', '' . $url2, 'SSL');
        
        $this->data['medication_url'] = $this->url->link('resident/resident/tagsmedication', '' . $url2, 'SSL');
        
        $this->data['censusdetail_url'] = $this->url->link('resident/dailycensus/censusdetail', '' . $url2, 'SSL');
        $this->data['updatetag_url'] = $this->url->link('notes/tags/updateclient', '' . $url2, 'SSL');
        $this->data['bedcheck_url'] = $this->url->link('notes/notes/generatePdf&is_bedchk=1', '' . $url2, 'SSL');
        
        $this->data['assignteam_url'] = $this->url->link('resident/assignteam', '' . $url2, 'SSL');
        
        $this->data['resetUrl'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
        $this->data['form_url'] = $this->url->link('notes/noteform/forminsert', '' . $url, 'SSL');
        
        $this->data['record_url'] = $this->url->link('notes/recordingnote/recordnote', '' . $url, 'SSL');
        $this->data['sharenote_url'] = $this->url->link('notes/sharenote/addnote', '' . $url, 'SSL');
        
        $this->data['check_list_form_url'] = $this->url->link('notes/createtask/noteschecklistform', '' . $url, 'SSL');
        
        $this->data['custom_form_form_url'] = $this->url->link('form/form', '' . $url, 'SSL');
        
        $this->data['sharenotes_Url'] = $this->url->link('notes/sharenote/searchnoteshare', '' . $url2, 'SSL');
        $this->load->model('notes/notes');
        $this->load->model('form/form');
        $this->load->model('createtask/createtask');
        
        $this->data['tagassignotes'] = $this->model_notes_notes->gettagassigns($facilities_id);
        
        $this->load->model('setting/highlighter');
        $this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters();
        
        $this->data['note_date_from'] = date('m-d-Y', strtotime('now'));
        $this->data['note_date_to'] = date('m-d-Y', strtotime('now'));
        
        $this->load->model('createtask/createtask');
        $this->data['tasktypes'] = $this->model_createtask_createtask->getTaskdetails($facilities_id);
        
        $this->load->model('setting/keywords');
        
        $data3 = array(
                'facilities_id' => $facilities_id,
                'sort' => 'keyword_name'
        );
        
        $this->data['activenotes'] = $this->model_setting_keywords->getkeywords($data3);
        
        // var_dump($this->data['activenotes']);
        
        $this->load->model('form/form');
        $data3 = array();
        $data3['status'] = '1';
        // $data3['order'] = 'sort_order';
        $data3['is_parent'] = '1';
        $data3['facilities_id'] = $facilities_id;
        
        $custom_forms = $this->model_form_form->getforms($data3);
        
        $this->data['custom_forms'] = array();
        foreach ($custom_forms as $custom_form) {
            
            $this->data['custom_forms'][] = array(
                    'forms_id' => $custom_form['forms_id'],
                    'form_name' => $custom_form['form_name'],
                    'form_href' => $this->url->link('form/form', '' . '&forms_design_id=' . $custom_form['forms_id'], 'SSL')
            );
        }
        
        // $this->load->model('resident/report');
        // $this->data['assigntos'] =
        // $this->model_resident_report->getassigns();
        
        $this->load->model('notes/image');
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/tags');
        
        unset($this->session->data['media_user_id']);
        unset($this->session->data['media_signature']);
        unset($this->session->data['media_pin']);
        unset($this->session->data['emp_tag_id']);
        unset($this->session->data['tags_id']);
        
        $this->data['notess'] = array();
        
        $this->data['action'] = str_replace('&amp;', '&', $this->url->link('common/home', '' . $url2, 'SSL'));
        // $this->data['rediectUlr'] = str_replace('&amp;', '&',
        // $this->url->link('common/home', '' . $url2, 'SSL'));
        
        if (isset($this->session->data['update_reminder'])) {
            $this->data['update_reminder'] = $this->session->data['update_reminder'];
        }
        
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
        
        if ($this->session->data['note_date_from'] != null && $this->session->data['note_date_from'] != "") {
            
            $date = str_replace('-', '/', $this->session->data['note_date_from']);
            $res = explode("/", $date);
            $note_date_from = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            // $note_date_from = date('Y-m-d',
        // strtotime($this->session->data['note_date_from']));
        }
        if ($this->session->data['note_date_to'] != null && $this->session->data['note_date_to'] != "") {
            $date = str_replace('-', '/', $this->session->data['note_date_to']);
            $res = explode("/", $date);
            $note_date_to = $res[2] . "-" . $res[0] . "-" . $res[1];
            
            // $note_date_to = date('Y-m-d',
        // strtotime($this->session->data['note_date_to']));
        }
        
        $timezone_name = $this->customer->isTimezone();
        $timeZone = date_default_timezone_set($timezone_name);
        if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
            $noteTime = date('H:i:s');
            
            $date = str_replace('-', '/', $this->request->get['searchdate']);
            $res = explode("/", $date);
            $changedDate = $res[1] . "-" . $res[0] . "-" . $res[2];
            
            $this->data['note_datenew'] = $changedDate . ' ' . $noteTime;
            $searchdate = $this->request->get['searchdate'];
            $this->data['searchdate'] = $this->request->get['searchdate'];
            
            if (($searchdate) >= (date('m-d-Y'))) {
                $this->data['back_date_check'] = "1";
            } else {
                $this->data['back_date_check'] = "2";
            }
        } else {
            $this->data['note_datenew'] = date('Y-m-d H:i:s');
            $this->data['searchdate'] = date('m-d-Y');
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
        
        
            
		$this->data['case_detail'] = "1";
		
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$tags_id = $this->request->get['tags_id'];
			$search_emp_tag_id = $tags_id;
			$case_detail = '1';
		}
        
        if ($this->request->get['form'] == "1") {
            $form_search = 'all';
        } else {
            $form_search = $this->session->data['form_search'];
        }
        
        if ($this->request->get['sightandsound'] == "1") {
            $tasktype = '25';
        } else {
            $tasktype = $this->session->data['tasktype'];
        }
        
        if ($this->request->get['task'] == "1") {
            $task_search = 'all';
        }
        
        if ($this->request->get['highlighter'] == "1") {
            $highlighter = 'all';
        } else {
            $highlighter = $this->session->data['highlighter'];
        }
        
        if ($this->request->get['search_user_id'] == "1") {
            $search_user_id = 'all';
        } else {
            $search_user_id = $this->session->data['search_user_id'];
        }
        
        if ($this->session->data['advance_search'] != null && $this->session->data['advance_search'] != "") {
            $advance_search = $this->session->data['advance_search'];
        } else {
            $advance_search = '1';
        }
        
        if ($this->request->get['review'] == '1') {
            $review_notes = '1';
        }
        
        if ($this->request->get['color'] == '1') {
            $text_color = '1';
        }
        
        if ($this->request->get['incident'] == "1") {
            $keyword = 'incident';
            $activenote = '44';
            $search_acitvenote_with_keyword = '1';
        } elseif ($this->request->get['pillcall'] == "1") {
            $activenote = '38';
            $keyword = 'medication';
            $search_acitvenote_with_keyword = '1';
        } elseif ($this->request->get['activenote'] == "1") {
            $activenote = 'all';
        } else {
            $keyword = $this->session->data['keyword'];
            $activenote = $this->session->data['activenote'];
        }
        
        $data = array(
                'sort' => $sort,
                'case_detail' => $case_detail,
                'search_acitvenote_with_keyword' => $search_acitvenote_with_keyword,
                'order' => $order,
                'group' => '1',
                'searchdate' => $searchdate,
                'searchdate_app' => '1',
                'facilities_id' => $facilities_id,
                'note_date_from' => $note_date_from,
                'note_date_to' => $note_date_to,
                'task_search' => $task_search,
                
                'search_time_start' => $this->session->data['search_time_start'],
                'search_time_to' => $this->session->data['search_time_to'],
				'customer_key' => $this->session->data['webcustomer_key'],
                
                'keyword' => $keyword,
                'text_color' => $text_color,
                'review_notes' => $review_notes,
                'form_search' => $form_search,
                'user_id' => $search_user_id,
                'highlighter' => $highlighter,
                'activenote' => $activenote,
                'emp_tag_id' => $search_emp_tag_id,
                'advance_searchapp' => $advance_search,
                'tasktype' => $tasktype,
                'start' => ($page - 1) * $config_admin_limit,
                'limit' => $config_admin_limit
        );
        
        //var_dump($data);
        
        // die;
        
        $this->load->model('notes/case');
        $notes_total = $this->model_notes_case->getTotalnotess($data);
        // var_dump($notes_total);
        
        $this->load->model('notes/notes');
        $this->load->model('facilities/facilities');
        $last_notesID = $this->model_notes_notes->getLastNotesID($facilities_id, $searchdate);
        
        $this->data['last_notesID'] = $last_notesID['notes_id'];
        
        $results = $this->model_notes_case->getnotess($data);
        
        // var_dump($results);
        // var_dump($data);
        
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
        
        $facilityinfo = $this->model_facilities_facilities->getfacilities($facilities_id);
        // var_dump($facilityinfo);
        
        // $nkey = $this->session->data['session_cache_key'];
        // $this->cache->delete('notes'.$nkey);
        
        // var_dump($results );
        
        foreach ($results as $result) {
            
            $this->cache->delete('note' . $result['notes_id']);
            
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
                                                      // height="35px" alt=""
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
             * if($result['notes_file'] != null && $result['notes_file'] != ""){
             * $keyImageSrc = '<img
             * src="sites/view/digitalnotebook/image/attachment.png"
             * width="35px" height="35px" alt="" style="margin-left: 4px;" />';
             *
             * //$fileOpen = $this->url->link('notes/notes/openFile', '' .
             * '&openfile='.$result['notes_file'] . $url, 'SSL');
             * $fileOpen = HTTP_SERVER .'image/files/'. $result['notes_file'];
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
            
            // if ($config_tag_status == '1') {
            
            $alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
            
            if ($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != "") {
                $tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
                $privacy = $tagdata['privacy'];
                
                if ($tagdata['privacy'] == '2') {
                    if ($this->session->data['unloack_success'] != '1') {
                        $emp_tag_id = $alltag['emp_tag_id'] . ':' . $tagdata['emp_first_name'];
                    } else {
                        $emp_tag_id = '';
                    }
                } else {
                    $emp_tag_id = '';
                }
            } else {
                $emp_tag_id = '';
                $privacy = '';
            }
            // }
            
            $allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
            $noteskeywords = array();
            
            if ($privacy == '2') {
                if ($this->session->data['unloack_success'] == '1') {
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
                            $noteskeywords[] = array(
                                    'keyword_file_url' => $keyword['keyword_file_url']
                            );
                        }
                        
                        // $keyword_description = str_replace($keyname,
                        // $keyImageSrc12, $result['notes_description']);
                        // $keyword_description =
                        // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                        $keyword_description = $result['notes_description'];
                        
                        $notes_description = $emp_tag_id . $keyword_description;
                    } else {
                        $notes_description = $emp_tag_id . $result['notes_description'];
                    }
                } else {
                    $notes_description = $emp_tag_id;
                }
            } else {
                // $notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id .
                // $result['notes_description'];
                
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
                        
                        $noteskeywords[] = array(
                                'keyword_file_url' => $keyword['keyword_file_url']
                        );
                    }
                    
                    // $keyword_description = str_replace($keyname,
                    // $keyImageSrc12, $result['notes_description']);
                    // $keyword_description =
                    // $keyImageSrc11.'&nbsp;'.$result['notes_description'];
                    $keyword_description = $result['notes_description'];
                    
                    $notes_description = $emp_tag_id . $keyword_description;
                } else {
                    $notes_description = $emp_tag_id . $result['notes_description'];
                }
            }
            
            // if($facilityinfo['config_noteform_status'] == '1'){
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
                        'href' => $this->url->link('form/form', '' . '&forms_design_id=' . $allform['custom_form_type'] . '&forms_id=' . $allform['forms_id'] . '&notes_id=' . $result['notes_id'], 'SSL'),
                        'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']))
                )
                ;
            }
            
            // }
            
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
                    
                    if ($alltask['location_type'] == 'Inmates') {
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
                            'loc_name' => 'Inmates'
                    );
                }
                
                $residentstotals = array();
                if ($residencetotal > 0) {
                    $residentstotals[] = array(
                            'total' => $residencetotal,
                            'loc_name' => 'Count'
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
            
            $reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
            
            $remdata = "";
            if ($reminder_info != null && $reminder_info != "") {
                $remdata = "1";
            } else {
                $remdata = "2";
            }
            
            $this->data['notess'][] = array(
                    'notes_id' => $result['notes_id'],
                    'visitor_log' => $result['visitor_log'],
                    'is_tag' => $result['is_tag'],
                    'form_type' => $result['form_type'],
                    'generate_report' => $result['generate_report'],
                    'is_census' => $result['is_census'],
                    'is_android' => $result['is_android'],
                    'emp_tag_id' => $alltag['emp_tag_id'],
                    'alltag' => $alltag,
                    'remdata' => $remdata,
                    'noteskeywords' => $noteskeywords,
                    'is_private' => $result['is_private'],
                    'share_notes' => $result['share_notes'],
                    'is_offline' => $result['is_offline'],
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
                    'note_date' => date('m-d-Y h:i A', strtotime($result['note_date'])),
                    'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                    'date_added' => date('m-d-Y h:i A', strtotime($result['date_added'])),
                    'date_added2' => date('D F j, Y', strtotime($result['date_added'])),
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
        if ($this->request->get['clpage'] != null && $this->request->get['clpage'] != "") {
            $url .= '&clpage=' . $this->request->get['clpage'];
        }
        
        if ($this->request->get['fpage'] != null && $this->request->get['fpage'] != "") {
            $url .= '&fpage=' . $this->request->get['fpage'];
        }
        
        if ($this->request->get['note_date_from'] != null && $this->request->get['note_date_from'] != "") {
            $url .= '&note_date_from=' . $this->request->get['note_date_from'];
        }
        
        if ($this->request->get['note_date_to'] != null && $this->request->get['note_date_to'] != "") {
            $url .= '&note_date_to=' . $this->request->get['note_date_to'];
        }
        
        if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
            $url .= '&tags_id=' . $this->request->get['tags_id'];
        }
        if ($this->request->get['form'] != null && $this->request->get['form'] != "") {
            $url .= '&form=' . $this->request->get['form'];
        }
        if ($this->request->get['sightandsound'] != null && $this->request->get['sightandsound'] != "") {
            $url .= '&sightandsound=' . $this->request->get['sightandsound'];
        }
        if ($this->request->get['incident'] != null && $this->request->get['incident'] != "") {
            $url .= '&incident=' . $this->request->get['incident'];
        }
        if ($this->request->get['task'] != null && $this->request->get['task'] != "") {
            $url .= '&task=' . $this->request->get['task'];
        }
        if ($this->request->get['reporthighlighter'] != null && $this->request->get['reporthighlighter'] != "") {
            $url .= '&reporthighlighter=' . $this->request->get['reporthighlighter'];
        }
        if ($this->request->get['reportactivenote'] != null && $this->request->get['reportactivenote'] != "") {
            $url .= '&reportactivenote=' . $this->request->get['reportactivenote'];
        }
        if ($this->request->get['search_user_id'] != null && $this->request->get['search_user_id'] != "") {
            $url .= '&search_user_id=' . $this->request->get['search_user_id'];
        }
        if ($this->request->get['review'] != null && $this->request->get['review'] != "") {
            $url .= '&review=' . $this->request->get['review'];
        }
        if ($this->request->get['color'] != null && $this->request->get['color'] != "") {
            $url .= '&color=' . $this->request->get['color'];
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
        $pagination->url = $this->url->link('case/searchresult/report', '' . $url . '&page={page}', 'SSL');
        
        $this->data['pagination'] = $pagination->render();
        
        $this->template = $this->config->get('config_template') . '/template/case/allreport.php';
        
        $this->children = array(
                'case/header'
        )
        ;
        
        $this->response->setOutput($this->render());
    }

    public function getData ()
    {
        if ($this->request->get['lastdate'] != null && $this->request->get['lastdate'] != "") {
            
            $json = array();
            
            if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
                $facilities_id = $this->request->get['facilities_id'];
            } else {
                $facilities_id = $this->customer->getId();
                
                if (! $this->customer->isLogged()) {
                    $this->redirect($this->url->link('common/login', '', 'SSL'));
                }
            }
            
            $this->load->model('notes/notes');
            $this->load->model('form/form');
            $this->load->model('createtask/createtask');
            $this->load->model('setting/tags');
            $this->load->model('notes/case');
            
            $this->load->model('facilities/facilities');
            $this->load->model('setting/timezone');
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $facility = $this->model_facilities_facilities->getfacilities($facilities_id);
            $timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
            
            date_default_timezone_set($timezone_info['timezone_value']);
            $current_date_m = date('Y-m-d', strtotime('now'));
            
            $lastdate = $this->request->get['lastdate'];
            
            $date = str_replace('-', '/', $lastdate);
            $res = explode("/", $date);
            $dateRange = $res[2] . "-" . $res[1] . "-" . $res[0];
            
            // $years = array(date('Y', strtotime($dateRange)));
            $m = date('m', strtotime($dateRange));
            
            /*
             * for($i = $m-1; $i >= ($m-3); $i--){
             *
             * $mmm = strlen($i);
             * if($mmm == '1'){
             * $monval = '0'.$i;
             * }else{
             * $monval = $i;
             * }
             * $months[] = (string)$monval;
             * }
             */
			 
			 
			 if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$this->data['tags_id'] = $this->request->get['tags_id'];
			}
            
            $currentm_date = $dateRange;
            $currentm = date("m-Y", strtotime(date("Y-m-d H:i", strtotime($currentm_date)) . "-1 month"));
            
            $currentmlast = date("m-Y", strtotime(date("Y-m-d H:i", strtotime($currentm_date)) . "-2 month"));
            
            $currentmlast2 = date("m-Y", strtotime(date("Y-m-d H:i", strtotime($currentm_date)) . "-3 month"));
            
            $months = array(
                    $currentm,
                    $currentmlast,
                    $currentmlast2
            );
            
            // var_dump($months);
            
            // foreach($years as $year){
            foreach ($months as $month) {
                
                $lastDayThisMonth = date("t");
                
                // $note_date_from = '01-'.$month.'-'.$year;
                $note_date_from = '01-' . $month;
                // $note_date_to = $lastDayThisMonth.'-'.$month.'-'.$year;
                $note_date_to = $lastDayThisMonth . '-' . $month;
                $date_current_to = $year . '-' . $month;
                
                $data2 = array(
                        'note_date_from' => $note_date_from,
                        'note_date_to' => $note_date_to,
                        'emp_tag_id' => $this->request->get['tags_id'],
                        'facilities_id' => $facilities_id
                );
                
                $res11 = explode("-", $month);
                
                // $note_date_from22 = $year.'-'.$month.'-01';
                $note_date_from22 = $res11[1] . '-' . $res11[0] . '-01';
                
                // var_dump($data2);
                
                $casedetails = $this->model_notes_case->getcasedetails($data2);
                
                // var_dump($casedetails);
                // echo "<hr>";
                
                if (! empty($casedetails)) {
                    $ttotalnotes = 0;
                    $ttotalforms = 0;
                    $ttotaltasks = 0;
                    $incidentcount = 0;
                    $sightandsoundcount = 0;
                    $highlightercount = 0;
                    $activenotecount = 0;
                    $colorcount = 0;
                    $reviewcount = 0;
                    $becdcheckcount = 0;
                    $pillcallcount = 0;
                    $dd_dates = array();
                    $dd_dates2 = array();
                    $in_dates = array();
                    $in_dates2 = array();
                    foreach ($casedetails as $casedetail) {
                        $ttotalnotes = $ttotalnotes + $casedetail['notescount'];
                        $ttotalforms = $ttotalforms + $casedetail['formscount'];
                        $ttotaltasks = $ttotaltasks + $casedetail['taskcount'];
                        $incidentcount = $incidentcount + $casedetail['incidentcount'];
                        $sightandsoundcount = $sightandsoundcount + $casedetail['sightandsoundcount'];
                        $highlightercount = $highlightercount + $casedetail['highlightercount'];
                        $activenotecount = $activenotecount + $casedetail['activenotecount'];
                        $colorcount = $colorcount + $casedetail['colourcount'];
                        $reviewcount = $reviewcount + $casedetail['reviewcount'];
                        $becdcheckcount = $becdcheckcount + $casedetail['becdcheckcount'];
                        $pillcallcount = $pillcallcount + $casedetail['pillcallcount'];
                        
                        if ($casedetail['discharge_date'] != "0000-00-00 00:00:00") {
                            $dd_dates[] = date('m-d-Y', strtotime($casedetail['discharge_date']));
                            $dd_dates2[] = date('m-Y', strtotime($casedetail['discharge_date']));
                        }
                        if ($casedetail['intake_date'] != "0000-00-00 00:00:00") {
                            $in_dates[] = date('m-d-Y', strtotime($casedetail['intake_date']));
                            $in_dates2[] = date('m-Y', strtotime($casedetail['intake_date']));
                        }
                    }
                    
                    $dd_dates1 = array_unique($dd_dates);
                    $dd_dates12 = array_unique($dd_dates2);
                    
                    $in_dates1 = array_unique($in_dates);
                    $in_dates12 = array_unique($in_dates2);
                    
                    // var_dump($dd_dates12);
                    // var_dump($in_dates12);
                    
                    $notemonths[] = array(
                            'dd_dates' => $dd_dates1,
                            'in_dates' => $in_dates1,
                            
                            'dd_dates12' => $dd_dates12,
                            'in_dates12' => $in_dates12,
                            
                            'month11' => date('m-Y', strtotime($note_date_from22)),
                            'month' => date('F , Y', strtotime($note_date_from22)),
                            'ttotalnotes' => $ttotalnotes,
                            'ttotalnotes_url' => $this->url->link('case/searchresult/report', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            'ttotalforms' => $ttotalforms,
                            'ttotalforms_url' => $this->url->link('case/searchresult/report&form=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            'ttotaltasks' => $ttotaltasks,
                            
                            'ttotaltasks_url' => $this->url->link('case/searchresult/report&task=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalsightandsound_url' => $this->url->link('case/searchresult/report&sightandsound=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalincident_url' => $this->url->link('case/searchresult/report&incident=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'totalhighlighter_url' => $this->url->link('case/searchresult/report&highlighter=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalactivenote_url' => $this->url->link('case/searchresult/report&activenote=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalcolor_url' => $this->url->link('case/searchresult/report&color=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'ttotalreview_url' => $this->url->link('case/searchresult/report&review=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            
                            'print_url' => $this->url->link('notes/notes/generatePdf', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL'),
                            // 'intakecount'=> $intakecount,
                            // 'dischargecount'=> $dischargecount,
                            'incidentcount' => $incidentcount,
                            'sightandsoundcount' => $sightandsoundcount,
                            
                            'highlightercount' => $highlightercount,
                            'activenotecount' => $activenotecount,
                            'colorcount' => $colorcount,
                            'reviewcount' => $reviewcount,
                            'becdcheckcount' => $becdcheckcount,
                            
                            'pillcallcount' => $pillcallcount,
                            'ttotalpillcall_url' => $this->url->link('case/searchresult/report&pillcall=1', '' . $url2 . '&note_date_from=' . $note_date_from . '&note_date_to=' . $note_date_to, 'SSL')
                    )
                    ;
                }
            }
            
            // var_dump($this->data['tags_id']);
            
            $noteyears[] = array(
                    // 'year'=> $year,
                    'notemonths' => $notemonths,
                    'lastdate' => $note_date_from
            )
            ;
            
            // }
        }
        
        if ($notemonths) {
            $this->data['displaydata'] = '1';
        } else {
            $this->data['displaydata'] = '0';
        }
        
        // var_dump($months);
        
        $template = new Template();
        $template->data['noteyears'] = $noteyears;
        $template->data['tags_id'] = $this->request->get['tags_id'];
        $template->data['displaydata'] = 'ajax';
        // if($ttotalnotes > 0){
        // $template->data['stop_ajax'] = 1;
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/case/ajaxreport.php')) {
            $html = $template->fetch($this->config->get('config_template') . '/template/case/ajaxreport.php');
        }
        
        $this->response->setOutput(json_encode($html));
        
        /*
         * }else{
         * $html = "";
         *
         * $this->response->setOutput(json_encode($html));
         * }
         */
    }
}
	