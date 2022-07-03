<?php
class Controllersyndbperminute extends Controller {
	
	public function completeemailtemplate($result, $taskDate, $taskeTiming, $headerbody, $messagebody1) {
		$this->load->model ( 'setting/locations' );
		$bedcheckdata = array ();
		
		if ($result ['task_form_id'] != 0 && $result ['task_form_id'] != NULL) {
			$formDatas = $this->model_setting_locations->getformid ( $result ['task_form_id'] );
			
			foreach ( $formDatas as $formData ) {
				$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
				
				$locationDatab = array ();
				$location_type = "";
				
				$location_typea = $locData ['location_type'];
				if ($location_typea == '1') {
					$location_type .= "Boys";
				}
				
				if ($location_typea == '2') {
					$location_type .= "Girls";
				}
				
				if ($location_typea == '3') {
					$location_type .= "Inmates";
				}
				
				if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
					$upload_file = $locData ['upload_file'];
				} else {
					$upload_file = "";
				}
				$locationDatab [] = array (
						'locations_id' => $locData ['locations_id'],
						'location_name' => $locData ['location_name'],
						'location_address' => $locData ['location_address'],
						'location_detail' => $locData ['location_detail'],
						'capacity' => $locData ['capacity'],
						'location_type' => $location_type,
						'upload_file' => $upload_file,
						'nfc_location_tag' => $locData ['nfc_location_tag'],
						'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
						'gps_location_tag' => $locData ['gps_location_tag'],
						'gps_location_tag_required' => $locData ['gps_location_tag_required'],
						'latitude' => $locData ['latitude'],
						'longitude' => $locData ['longitude'],
						'other_location_tag' => $locData ['other_location_tag'],
						'other_location_tag_required' => $locData ['other_location_tag_required'],
						'other_type_id' => $locData ['other_type_id'],
						'facilities_id' => $locData ['facilities_id'] 
				);
				
				$bedcheckdata [] = array (
						'task_form_location_id' => $formData ['task_form_location_id'],
						'location_name' => $formData ['location_name'],
						'location_detail' => $formData ['location_detail'],
						'current_occupency' => $formData ['current_occupency'],
						'bedcheck_locations' => $locationDatab 
				);
			}
			
			/*
			 * $this->load->model('setting/bedchecktaskform');
			 * $taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
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
		
		// var_dump($bedcheckdata);
		
		$transport_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['transport_tags'] )) {
			$transport_tags1 = explode ( ',', $result ['transport_tags'] );
		} else {
			$transport_tags1 = array ();
		}
		
		foreach ( $transport_tags1 as $tag1 ) {
			$tags_info = $this->model_setting_tags->getTag ( $tag1 );
			
			if ($tags_info ['emp_first_name']) {
				$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info ['emp_tag_id'];
			}
			
			if ($tags_info) {
				$transport_tags [] = array (
						'tags_id' => $tags_info ['tags_id'],
						'emp_tag_id' => $emp_tag_id 
				);
			}
		}
		
		$medication_tags = array ();
		$this->load->model ( 'setting/tags' );
		
		if (! empty ( $result ['medication_tags'] )) {
			$medication_tags1 = explode ( ',', $result ['medication_tags'] );
		} else {
			$medication_tags1 = array ();
		}
		
		foreach ( $medication_tags1 as $medicationtag ) {
			$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
			
			if ($tags_info1 ['emp_first_name']) {
				$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'];
			} else {
				$emp_tag_id = $tags_info1 ['emp_tag_id'];
			}
			
			if ($tags_info1) {
				
				$drugs = array ();
				
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
				
				foreach ( $mdrugs as $mdrug ) {
					
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
					
					$drugs [] = array (
							'drug_name' => $mdrug_info ['drug_name'] 
					);
				}
				
				$medication_tags [] = array (
						'tags_id' => $tags_info1 ['tags_id'],
						'emp_tag_id' => $emp_tag_id,
						'tagsmedications' => $drugs 
				);
			}
		}
		
		if ($result ['visitation_tag_id']) {
			$visitation_tag = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
			
			if ($visitation_tag ['emp_first_name']) {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'];
			} else {
				$visitation_tag_id = $visitation_tag ['emp_tag_id'];
			}
		} else {
			$visitation_tag_id = "";
		}
		
		// die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>' . $headerbody . '</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $headerbody . '</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['assign_to'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $headerbody . ' - ' . $messagebody1 . '</p>
							
						</td>
					</tr>
				</table>
			</div>';
		
		if (($medication_tags != null && $medication_tags != "") || ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")) {
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
			
			$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
			// $html .= $result['description'];
			
			if ($medication_tags != null && $medication_tags != "") {
				foreach ( $medication_tags as $medication_tag ) {
					$html .= 'Client Name: ' . $medication_tag ['emp_tag_id'] . '<br>';
					foreach ( $medication_tag ['tagsmedications'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			if ($medications != null && $medications != "") {
				foreach ( $medications as $medication ) {
					foreach ( $medication ['medications_drugs'] as $drug ) {
						$html .= 'Drug Name: ' . $drug ['drug_name'] . '<br>';
						$html .= 'Dose: ' . $drug ['dose'] . '<br>';
						$html .= 'Drug Type: ' . $drug ['drug_type'] . '<br>';
						$html .= 'Quantity: ' . $drug ['quantity'] . '<br>';
						$html .= 'Instructions: ' . $drug ['instructions'] . '<br>';
						$html .= 'Count: ' . $drug ['count'];
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			// var_dump($tasklist['transport_tags']);
			
			if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
				if ($transport_tags) {
					foreach ( $transport_tags as $tag ) {
						$html .= 'Client Name: ' . $tag ['emp_tag_id'] . '<br>';
					}
				}
				
				$html .= '<br>Pickup Address: ' . $result ['pickup_locations_address'] . '<br>';
				$html .= 'Pickup Time: ' . date ( 'h:i A', strtotime ( $result ['pickup_locations_time'] ) ) . '<br>';
				$html .= 'Dropoff Address: ' . $result ['dropoff_locations_address'] . '<br>';
				$html .= 'Dropoff Time: ' . date ( 'h:i A', strtotime ( $result ['dropoff_locations_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($visitation_tag_id != null && $visitation_tag_id != "") {
				
				$html .= 'Client Name: ' . $visitation_tag_id . '<br>';
				
				$html .= '<br>Start Address: ' . $result ['visitation_start_address'] . '<br>';
				$html .= 'Start Time: ' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '<br>';
				$html .= 'Appoitment Address: ' . $result ['visitation_appoitment_address'] . '<br>';
				$html .= 'Appoitment Time: ' . date ( 'h:i A', strtotime ( $result ['visitation_appoitment_time'] ) ) . '<br>';
				
				$html .= "<div style='border-bottom:1px solid #eee;'></div>";
			}
			
			if ($bedcheckdata != null && $bedcheckdata != "") {
				foreach ( $bedcheckdata as $bedcheckda ) {
					
					$html .= 'Location Name: ' . $bedcheckda ['location_name'] . '<br>';
					foreach ( $bedcheckda ['bedcheck_locations'] as $bedcheck_location ) {
						// $html .= 'Location Name: '.$bedcheck_location['location_name'].'<br>';
						$html .= 'Capacity: ' . $bedcheck_location ['capacity'] . '<br>';
						$html .= 'Type: ' . $bedcheck_location ['location_type'] . '<br>';
						$html .= 'Location Detail: ' . $bedcheck_location ['location_detail'] . '<br>';
						$html .= 'Location Address: ' . $bedcheck_location ['location_address'] . '';
						$html .= "<div style='border-bottom:1px solid #eee;'></div>";
					}
				}
			}
			
			$html .= '</p>';
			
			$html .= '</td>
					</tr>
				</table>
			
			</div>';
		}
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: ' . $result ['tasktype'] . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . $result ['description'] . '
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . date ( 'j, F Y', strtotime ( $taskDate ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $taskeTiming ) ) . '
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
		
		return $html;
	}
	
	public function emailtemplate($result) {
		
		// var_dump($result);
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Warehouse Status Report</title>

	<style>
	@media screen and (max-width:500px) {
	   h6 {
			font-size: 12px !important;
		}
	}
	</style>
	</head>
	 
	<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

	<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
		<tr>
			<td></td>
			<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
				

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
					<table style="width: 100%;">
						<tr>
							<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
							<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Status Report</h6></td>
						</tr>
					</table>
				</div>
				
			</td>
			<td></td>
		</tr>
	</table>

	<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
		<tr>
			<td></td>
			<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
					<table>
						<tr>
							<td>
								
								<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['company_name'] . '!</h1>
								<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse successfully the following is the details :</p>
								
							</td>
						</tr>
					</table>
				</div>';
		foreach ( $result ['facilities'] as $result_f ) {
			
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $result_f . "'" );
			$facility_info = $query->row;
			
			$query1 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "activecustomer` WHERE customer_key = '" . $facility_info ['customer_key'] . "'" );
			$customer_info = $query1->row;
			
			$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '" . $result ['endDate'] . " 00:00:00 ' AND  '" . $result ['endDate'] . " 23:59:59' and facilities_id = '" . ( int ) $result_f . "' and notes_conut = '1' ";
			$query2 = $this->db->query ( $sql );
			$facility_notes = $query2->row ['total'];
			
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
						
						<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
							<tr>
								<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
								<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
								<td>
									<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total No. Records: ' . $facility_notes . '</small></h4>
									<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
									' . $customer_info ['company_name'] . ' | ' . $facility_info ['facility'] . '
									</p>
								</td>
							</tr>
						</table>
					
					</div>';
		}
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							' . date ( 'j, F Y', strtotime ( $result ['endDate'] ) ) . '
							</p>
						</td>
					</tr>
				</table></div>
				

			</td>
			<td></td>
		</tr>
	</table>

	</body>
	</html>';
		return $html;
	}
	
	public function alertemailtemplate() {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Warehouse Alert</title>

		<style>
		@media screen and (max-width:500px) {
		   h6 {
				font-size: 12px !important;
			}
		}
		</style>
		</head>
		 
		<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

		<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
			<tr>
				<td></td>
				<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
					

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
						<table style="width: 100%;">
							<tr>
								<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
								<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Alert</h6></td>
							</tr>
						</table>
					</div>
					
				</td>
				<td></td>
			</tr>
		</table>

		<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
			<tr>
				<td></td>
				<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
						<table>
							<tr>
								<td>
									
									<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello Admin!</h1>
									<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse error</p>
									
								</td>
							</tr>
						</table>
					</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
							<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								' . date ( 'j, F Y', strtotime ( $result ['startDate'] ) ) . '
								</p>
							</td>
						</tr>
					</table></div>
					

				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>';
		return $html;
	}
	
	public function sendEmailtemplate($result, $ruleName, $ruleType, $rulevalue, $facilityData, $note_info1) {
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Form Rule Reminder</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Form Rule Reminder</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $facilityData ['username'] . '!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive ' . $ruleName . '! Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $facilityData ['href'] . '">
						<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $ruleType . '- ' . $rulevalue . '</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
		
		if ($note_info1 != null && $note_info1 != "") {
			$html .= $note_info1 ['description'];
		} else {
			$html .= $result ['notes_description'];
		}
		
		$html .= '</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
		if ($note_info1 != null && $note_info1 != "") {
			$html .= date ( 'j, F Y', strtotime ( $note_info1 ['task_date'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $note_info1 ['task_time'] ) );
		} else {
			$html .= date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '&nbsp;' . date ( 'h:i A', strtotime ( $result ['notetime'] ) );
		}
		
		$html .= '</p>
					</td>
				</tr>
			</table></div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						' . $facilityData ['facility'] . '&nbsp;' . $facilityData ['address'] . '&nbsp;' . $facilityData ['location'] . '&nbsp;' . $facilityData ['zone_name'] . '&nbsp;' . $facilityData ['zipcode'] . ', ' . $facilityData ['contry_name'] . '
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
		return $html;
	}
	
	
	public function deleteextrafiles() {
		
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "session` WHERE data = 'false' " );
		
		$sqlu1 = "UPDATE `" . DB_PREFIX . "medication_time` SET create_task = '0' ";
		$this->db->query ( $sqlu1 );
		
		// $sqlu12 = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET create_task = '0' ";
		// $this->db->query($sqlu12);
		
		$sqlu122 = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_email='0',form_alert_send_sms='0' WHERE form_alert_send_email='1' and form_snooze_dismiss='2' ";
		$this->db->query ( $sqlu122 );
		
		$sql221 = "DELETE FROM `" . DB_PREFIX . "tags` WHERE status = 0 ";
		$this->db->query ( $sql221 );
		
		
		$ddddf = DIR_IMAGE . 'facerecognition';
		$filesss = glob ( $ddddf . '/*' );
		foreach ( $filesss as $filess ) {
			if (is_file ( $filess ))
				unlink ( $filess );
		}
		
		$ddddfd = DIR_IMAGE . 'files';
		$filesdss = glob ( $ddddfd . '/*' );
		foreach ( $filesdss as $fidless ) {
			if (is_file ( $fidless ))
				unlink ( $fidless );
		}
		
	}
	
	public function sessiontimeout() {
		
		$this->load->model ( 'notes/notes' );
		
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/timezone' );
	
		$this->load->model ( 'licence/licence' );
		
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if ($results != null && $results != "") {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$delete_startDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$delete_date = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$taskstart_date = date ( 'Y-m-d', strtotime ( "-1 day" ) );
				$taskend_date = date ( 'Y-m-d' );
				
				
				
				$config_session_time_out = $this->config->get ( 'config_session_time_out' );
				
				if ($config_session_time_out == '5min') {
					$inactive = 5;
				} else if ($config_session_time_out == '10min') {
					$inactive = 10;
				} else if ($config_session_time_out == '15min') {
					$inactive = 15;
				} else if ($config_session_time_out == '20min') {
					$inactive = 20;
				} else if ($config_session_time_out == '25min') {
					$inactive = 25;
				} else if ($config_session_time_out == '30min') {
					$inactive = 30;
				} else if ($config_session_time_out == '45min') {
					$inactive = 45;
				} else if ($config_session_time_out == '1hour') {
					$inactive = 60;
				} else if ($config_session_time_out == '2hour') {
					$inactive = 120;
				} else if ($config_session_time_out == '3hour') {
					$inactive = 180;
				} else if ($config_session_time_out == '4hour') {
					$inactive = 240;
				} else if ($config_session_time_out == '5hour') {
					$inactive = 300;
				} else if ($config_session_time_out == '6hour') {
					$inactive = 360;
				} else if ($config_session_time_out == '7hour') {
					$inactive = 420;
				} else if ($config_session_time_out == '8hour') {
					$inactive = 480;
				} else {
					$inactive = 600;
				}
				
				// var_dump($timezone_info['timezone_value']);
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				// var_dump($inactive);
				// echo "<hr>";
				$thestime = date ( 'H:i:s' );
				$stime = date ( "H:i:s", strtotime ( "-" . $inactive . " minutes", strtotime ( $thestime ) ) );
				
				$noteDate2 = date ( 'Y-m-d', strtotime ( 'now' ) );
				$noteDate = $noteDate2 . ' ' . $stime;
				
				if (KILLSESSION == 1) {
					$faresults = $this->model_licence_licence->getfacilitiesOnline2 ( $tresult ['facilities_id'] );
					// var_dump($faresults);
					// echo "<hr>";
					$webkey = array ();
					$redata = array ();
					foreach ( $faresults as $faresult ) {
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( $faresult ['date_added'] ) );
						// var_dump($date_added);
						// echo "<hr>";
						// echo $date_added .'<'. $noteDate;
						// echo "<hr>";
						
						if ($date_added < $noteDate) {
							// echo $date_added .'<'. $noteDate;
							// echo "<hr>";
							$this->model_licence_licence->updateSession ( $faresult ['facility_login'] );
							
							// $sqlu = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '0' WHERE facilities_id = '" . (int)$faresult['facilities_id'] . "' and username = '".$faresult['username']."' ";
							$sqlu = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '0' WHERE facility_online_id = '" . ( int ) $faresult ['facility_online_id'] . "' ";
							
							$this->db->query ( $sqlu );
							
							$webkey [] = $faresult ['facility_login'];
							$redata ['facilities_id'] [] = $faresult ['facilities_id'];
							$redata ['facilities_id'] [] = $faresult ['facilities_id'];
							$redata ['facilities_id'] [] = $faresult ['facilities_id'];
						}
					}
				}
			}
		}
		
		echo "Success";
		
	}
	
	public function taskreminder() {
		
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'setting/country' );
		$this->load->model ( 'setting/zone' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'user/user_group' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		
		
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if ($results != null && $results != "") {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$delete_startDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$delete_date = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				
				$taskstart_date = date ( 'Y-m-d', strtotime ( "-1 day" ) );
				$taskend_date = date ( 'Y-m-d' );
				
				$thestime = date ( 'H:i:s' );
				$stime = date ( "H:i:s", strtotime ( "-" . $inactive . " minutes", strtotime ( $thestime ) ) );
				
				$noteDate2 = date ( 'Y-m-d', strtotime ( 'now' ) );
				$noteDate = $noteDate2 . ' ' . $stime;
				
				$noteDate3 = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$sqlt = "SELECT * from " . DB_PREFIX . "createtask where completion_alert = '1' and taskadded = '0' and is_send_reminder = '0' and facilityId = '" . $tresult ['facilities_id'] . "' and (`date_added` BETWEEN  '" . $noteDate3 . " 00:00:00' AND  '" . $noteDate3 . " 23:59:59') ";
				$qt = $this->db->query ( $sqlt );
				
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'facilities/facilities' );
				
				if ($qt->num_rows > 0) {
					foreach ( $qt->rows as $result1 ) {
						
						if ($result1 ['reminder_alert'] == '1') {
							
							$sqltre = "SELECT * from " . DB_PREFIX . "createtask_reminder where id = '" . $result1 ['id'] . "' and is_reminder = '0' ";
							$qtre = $this->db->query ( $sqltre );
							if ($qtre->num_rows > 0) {
								foreach ( $qtre->rows as $result1re ) {
									
									// $completed_times = explode(",",$result1re['completed_times']);
									
									$facilities_info = $this->model_facilities_facilities->getfacilities ( $result1 ['facilityId'] );
									
									$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
									
									date_default_timezone_set ( $timezone_info ['timezone_value'] );
									
									$currentTime = date ( 'H:i', strtotime ( 'now' ) );
									
									if ($result1re ['action'] == 'plus') {
										
										$completed_time1 = date ( 'H:i', strtotime ( ' +' . $result1re ['minute'] . ' minutes', strtotime ( $result1 ['task_time'] ) ) );
									}
									if ($result1re ['action'] == 'minus') {
										$completed_time1 = date ( 'H:i', strtotime ( ' -' . $result1re ['minute'] . ' minutes', strtotime ( $result1 ['task_time'] ) ) );
									}
									
									// foreach($completed_times as $completed_time){
									// $completed_time1 = date('H:i', strtotime($completed_time));
									// var_dump($currentTime);
									// var_dump($completed_time1);
									// echo "<hr>";
									
									if ($currentTime == $completed_time1) {
										
										if ($result1 ['user_roles'] != null && $result1 ['user_roles'] != "") {
											$user_roles1 = explode ( ',', $result1 ['user_roles'] );
											
											$this->load->model ( 'user/user_group' );
											$this->load->model ( 'user/user' );
											$this->load->model ( 'setting/tags' );
											
											$this->load->model ( 'api/emailapi' );
											$this->load->model ( 'api/smsapi' );
											
											foreach ( $user_roles1 as $user_role ) {
												
												$urole = array ();
												$urole ['user_group_id'] = $user_role;
												$tusers = $this->model_user_user->getUsers ( $urole );
												
												if ($tusers) {
													foreach ( $tusers as $tuser ) {
														
														if ($tuser ['phone_number']) {
															if ($result1 ['completion_alert_type_sms'] == '1') {
																$message = "Task Reminder " . date ( 'h:i A', strtotime ( $result1 ['task_time'] ) ) . "...\n";
																$message .= "Task Type: " . $result1 ['tasktype'] . "\n";
																
																if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
																	$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
																	
																	if ($tags_info1 ['emp_first_name']) {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																	} else {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'];
																	}
																	
																	if ($tags_info1) {
																		$message .= "Client Name: " . $emp_tag_id . "\n";
																	}
																}
																
																if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
																	$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
																	if ($tags_info1 ['emp_first_name']) {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																	} else {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'];
																	}
																	
																	if ($tags_info1) {
																		$message .= "Client Name: " . $emp_tag_id . "\n";
																	}
																}
																if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
																	$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
																	if ($tags_info1 ['emp_first_name']) {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																	} else {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'];
																	}
																	
																	if ($tags_info1) {
																		$message .= "Client Name: " . $emp_tag_id . "\n";
																	}
																}
																if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
																	
																	$transport_tags1 = explode ( ',', $result ['transport_tags'] );
																	
																	$transport_tags = '';
																	foreach ( $transport_tags1 as $tag1 ) {
																		$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
																		
																		if ($tags_info1 ['emp_first_name']) {
																			$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																		} else {
																			$emp_tag_id = $tags_info1 ['emp_tag_id'];
																		}
																		
																		if ($tags_info1) {
																			$transport_tags .= $emp_tag_id . ', ';
																		}
																	}
																	
																	$message .= "Client Name: " . $transport_tags . "\n";
																}
																$message .= "Description: " . substr ( $result1 ['description'], 0, 150 ) . ((strlen ( $result1 ['description'] ) > 150) ? '..' : '') . "\n";
																// $message .= "Description: ".$result1['description']."\n";
																
																$sdata = array ();
																$sdata ['message'] = $message;
																$sdata ['phone_number'] = $tuser ['phone_number'];
																$sdata ['facilities_id'] = $result1 ['facilityId'];
																$response = $this->model_api_smsapi->sendsms ( $sdata );
															}
														}
														
														if ($tuser ['email']) {
															if ($result1 ['completion_alert_type_email'] == '1') {
																
																$message33 = "";
																$messagebody = 'Task Reminder';
																$messagebody1 = 'The following task details.';
																$message33 .= $this->completeemailtemplate ( $result1, $result1 ['date_added'], $result1 ['task_time'], $messagebody, $messagebody1 );
																
																// var_dump($message33);
																// die;
																
																$edata = array ();
																$edata ['message'] = $message33;
																$edata ['subject'] = 'Task Reminder';
																$edata ['user_email'] = $tuser ['email'];
																
																$email_status = $this->model_api_emailapi->sendmail ( $edata );
															}
														}
													}
												}
											}
										}
										
										if ($result1 ['userids'] != null && $result1 ['userids'] != "") {
											$userids1 = explode ( ',', $result1 ['userids'] );
											
											$this->load->model ( 'user/user' );
											$this->load->model ( 'setting/tags' );
											
											$this->load->model ( 'api/emailapi' );
											$this->load->model ( 'api/smsapi' );
											
											foreach ( $userids1 as $userid ) {
												
												$user_info = $this->model_user_user->getUserbyupdate ( $userid );
												
												if ($user_info) {
													
													if ($user_info ['phone_number']) {
														if ($result1 ['completion_alert_type_sms'] == '1') {
															$message = "Task Reminder " . date ( 'h:i A', strtotime ( $result1 ['task_time'] ) ) . "...\n";
															$message .= "Task Type: " . $result1 ['tasktype'] . "\n";
															
															if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
																$tags_info1 = $this->model_setting_tags->getTag ( $result ['emp_tag_id'] );
																
																if ($tags_info1 ['emp_first_name']) {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																} else {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'];
																}
																
																if ($tags_info1) {
																	$message .= "Client Name: " . $emp_tag_id . "\n";
																}
															}
															
															if ($result ['medication_tags'] != null && $result ['medication_tags'] != "") {
																$tags_info1 = $this->model_setting_tags->getTag ( $result ['medication_tags'] );
																if ($tags_info1 ['emp_first_name']) {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																} else {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'];
																}
																
																if ($tags_info1) {
																	$message .= "Client Name: " . $emp_tag_id . "\n";
																}
															}
															if ($result ['visitation_tag_id'] != null && $result ['visitation_tag_id'] != "") {
																$tags_info1 = $this->model_setting_tags->getTag ( $result ['visitation_tag_id'] );
																if ($tags_info1 ['emp_first_name']) {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																} else {
																	$emp_tag_id = $tags_info1 ['emp_tag_id'];
																}
																
																if ($tags_info1) {
																	$message .= "Client Name: " . $emp_tag_id . "\n";
																}
															}
															if ($result ['transport_tags'] != null && $result ['transport_tags'] != "") {
																
																$transport_tags1 = explode ( ',', $result ['transport_tags'] );
																
																$transport_tags = '';
																foreach ( $transport_tags1 as $tag1 ) {
																	$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
																	
																	if ($tags_info1 ['emp_first_name']) {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
																	} else {
																		$emp_tag_id = $tags_info1 ['emp_tag_id'];
																	}
																	
																	if ($tags_info1) {
																		$transport_tags .= $emp_tag_id . ', ';
																	}
																}
																
																$message .= "Client Name: " . $transport_tags . "\n";
															}
															// $message .= "Description: ".$result1['description']."\n";
															
															$message .= "Description: " . substr ( $result1 ['description'], 0, 150 ) . ((strlen ( $result1 ['description'] ) > 150) ? '..' : '') . "\n";
															
															$sdata = array ();
															$sdata ['message'] = $message;
															$sdata ['phone_number'] = $user_info ['phone_number'];
															$sdata ['facilities_id'] = $result1 ['facilityId'];
															$response = $this->model_api_smsapi->sendsms ( $sdata );
														}
													}
													
													if ($user_info ['email']) {
														if ($result1 ['completion_alert_type_email'] == '1') {
															
															$message33 = "";
															$messagebody = 'Task Reminder ';
															$messagebody1 = 'The following task details.';
															$message33 .= $this->completeemailtemplate ( $result1, $result1 ['date_added'], $result1 ['task_time'], $messagebody, $messagebody1 );
															
															// var_dump($message33);
															// die;
															
															$edata = array ();
															$edata ['message'] = $message33;
															$edata ['subject'] = 'Task Reminder';
															$edata ['user_email'] = $user_info ['email'];
															
															$email_status = $this->model_api_emailapi->sendmail ( $edata );
														}
													}
												}
											}
										}
										
										$sqlu = "UPDATE `" . DB_PREFIX . "createtask_reminder` SET is_reminder = '1' WHERE createtask_reminder_id = '" . ( int ) $result1re ['createtask_reminder_id'] . "' ";
										$this->db->query ( $sqlu );
										
										$barray1 = array ();
										$barray1 ['createtask_reminder_id'] = $result1re ['createtask_reminder_id'];
										$barray1 ['userids'] = $result1 ['userids'];
										$barray1 ['user_roles'] = $result1 ['user_roles'];
										$barray1 ['minute'] = $result1re ['minute'];
										$barray1 ['action'] = $result1re ['action'];
										$barray1 ['task_time'] = $result1 ['task_time'];
										$barray1 ['id'] = $result1 ['id'];
										$barray1 ['response'] = $message33;
										
										$this->load->model ( 'activity/activity' );
										$this->model_activity_activity->addActivitySave ( 'taskreminder', $barray1, 'query' );
									}
									/*
									 * $sqlut = "UPDATE `" . DB_PREFIX . "createtask` SET is_send_reminder = '1' where id = '".$result1['id']."' ";
									 * $this->db->query($sqlut);
									 */
									
									// }
								}
							}
						}
					}
				}
			}
		}
			
		echo "Success";
	}
	
	public function taskextend() {
		
		
		$this->load->model ( 'notes/notes' );
		
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
		
		$this->load->model ( 'setting/timezone' );
		
		
		$this->load->model ( 'createtask/createtask' );
		
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if ($results != null && $results != "") {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				
				
				
				// var_dump($timezone_info['timezone_value']);
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$delete_startDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$delete_date = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				
				
				$taskstart_date = date ( 'Y-m-d', strtotime ( "-1 day" ) );
				$taskend_date = date ( 'Y-m-d' );
				
				
				$thestime = date ( 'H:i:s' );
				$stime = date ( "H:i:s", strtotime ( "-" . $inactive . " minutes", strtotime ( $thestime ) ) );
				
				$noteDate2 = date ( 'Y-m-d', strtotime ( 'now' ) );
				$noteDate = $noteDate2 . ' ' . $stime;
				
				
				// $noteDate = date('Y-m-d', strtotime('now'));
				$noteDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$sqlbedinfo = "SELECT max(id) as id FROM `" . DB_PREFIX . "createtask` WHERE ";
				// $sqlbedinfo .= " `end_recurrence_date` BETWEEN '".$noteDate." 00:00:00' AND '".$noteDate." 23:59:59' group by task_group_by ";
				$sqlbedinfo .= " `task_date` BETWEEN  '" . $noteDate . " 00:00:00' AND  '" . $noteDate . " 23:59:59' and facilityId = '" . $tresult ['facilities_id'] . "' group by task_group_by ";
				$sqlbedinfo .= " ORDER BY `task_time` DESC ";
				
				$bed = $this->db->query ( $sqlbedinfo );
				
				if ($bed->num_rows > 0) {
					foreach ( $bed->rows as $row ) {
						
						$sqlt = "SELECT * from " . DB_PREFIX . "createtask WHERE id = '" . $row ['id'] . "' ";
						$qts = $this->db->query ( $sqlt );
						
						if ($qts->row ['recurrence'] == 'hourly') {
							$sqltn = "SELECT COUNT(notes_id) AS total FROM " . DB_PREFIX . "notes WHERE task_group_by = '" . $qts->row ['task_group_by'] . "' and end_task = '1' ";
							$qtsn = $this->db->query ( $sqltn );
							
							if ($qtsn->row ['total'] == '0') {
								// $sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
								// $query = $this->db->query ( $sql4 );
								
								$taskinfo = $qts->row;
								
								if ($taskinfo ['recurnce_hrly_recurnce'] == "Daily") {
									
									if ($taskinfo ['weekly_interval'] != null && $taskinfo ['weekly_interval'] != "") {
										$intervalday = explode ( ',', $taskinfo ['weekly_interval'] );
										$current_day = date ( 'l' );
										
										$task_date1 = date ( 'Y-m-d', strtotime ( $taskinfo ['task_date'] ) );
										$end_recurrence_date1 = date ( 'Y-m-d', strtotime ( $taskinfo ['end_recurrence_date'] ) );
										$newtask = $task_date1 . ' ' . $taskinfo ['task_time'];
										$newtaskend = $end_recurrence_date1 . ' ' . $taskinfo ['endtime'];
										if (in_array ( $current_day, $intervalday )) {
											if ($newtask < $newtaskend) {
												// echo 22;
											} else {
												$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
												$query = $this->db->query ( $sql4 );
											}
										} else {
											if ($data ['task_time'] < $data ['endtime']) {
												// echo 444;
											} else {
												$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
												$query = $this->db->query ( $sql4 );
											}
										}
									} else {
										$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
										$query = $this->db->query ( $sql4 );
									}
								} else {
									
									if ($taskinfo ['task_time'] < $taskinfo ['endtime']) {
									} else {
										$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
										$query = $this->db->query ( $sql4 );
									}
								}
							}
						} elseif ($qts->row ['recurrence'] == 'Perpetual') {
						} else {
							$sqltn = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes WHERE task_group_by = '" . $qts->row ['task_group_by'] . "' and end_task = '1' ";
							$qtsn = $this->db->query ( $sqltn );
							
							if ($qtsn->row ['total'] == '0') {
								$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '" . $row ['id'] . "'";
								$query = $this->db->query ( $sql4 );
							}
						}
					}
				}
				
				$noteDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$sqlbedinfo1 = "SELECT * FROM `" . DB_PREFIX . "createtask` WHERE ";
				$sqlbedinfo1 .= " `task_date` BETWEEN  '" . $noteDate . " 00:00:00' AND  '" . $noteDate . " 23:59:59' and is_transport = '1' and facilityId = '" . $tresult ['facilities_id'] . "' ";
				
				$beddd = $this->db->query ( $sqlbedinfo1 );
				
			
				if ($beddd->num_rows > 0) {
					foreach ( $beddd->rows as $alltask ) {
						
						$task_info = $this->model_createtask_createtask->gettasktyperowByName ( $alltask ['tasktype'], $alltask ['facilityId'] );
						
						if ($task_info ['auto_extend'] == '1') {
							
							$originaltasktime = $alltask ['task_time'];
							$new_task_time = date ( "H:i:s", strtotime ( "+" . $task_info ['auto_extend_time'] . " minutes", strtotime ( $alltask ['task_time'] ) ) );
							
							$tasktime = date ( "H:i", strtotime ( "-3 minutes", strtotime ( $alltask ['task_time'] ) ) );
							
							$facility = $this->model_facilities_facilities->getfacilities ( $alltask ['facilityId'] );
							$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
							date_default_timezone_set ( $timezone_info ['timezone_value'] );
							
							$currenttime = date ( "H:i" );
							
							if ($tasktime <= $currenttime) {
								if ($alltask ['original_task_time'] == '00:00:00') {
									
									$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET original_task_time = '" . $originaltasktime . "',task_time = '" . $new_task_time . "'  WHERE id = '" . $alltask ['id'] . "'";
									
									$query = $this->db->query ( $sql3 );
								} else {
									
									$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET task_time = '" . $new_task_time . "'  WHERE id = '" . $alltask ['id'] . "'";
									$query = $this->db->query ( $sql3 );
								}
							}
						}
					}
				}
				
			}
		}
		echo "Success";
	}
	
	public function tasktypereport() {
		
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'setting/country' );
		$this->load->model ( 'setting/zone' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'user/user_group' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		
		$this->load->model ( 'form/form' );
		$tasksql = "SELECT * from " . DB_PREFIX . "tasktype where status = 1 and generate_report = 1 and forms_id != 0";
		$alltasks = $this->db->query ( $tasksql );
		
		$date = date ( "Y-m-d" );
		$start = $date . " 00:00:00";
		$end = $date . " 23:59:59";
		
		if ($alltasks->num_rows > 0) {
			
			foreach ( $alltasks->rows as $rtask ) {
				// var_dump($rtask['tasktype_name']);
				
				/* BED CHECK */
				// var_dump($rtask ['type']);
				if ($rtask ['type'] == '6') {
					$sqlbed = "SELECT * from " . DB_PREFIX . "notes where task_type = '1' and generate_report = '0' and tasktype = '" . $rtask ['task_id'] . "' and end_task = '1' and ( `date_added` BETWEEN '" . $date . " 00:00:00 ' AND '" . $date . " 23:59:59' ) and user_id != '" . SYSTEM_GENERATED . "'  ";
					
					$q1 = $this->db->query ( $sqlbed );
					
					if ($q1->num_rows > 0) {
						
						foreach ( $q1->rows as $row1 ) {
							
							$facilities_info = $this->model_facilities_facilities->getfacilities ( $row1 ['facilities_id'] );
							$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
							
							date_default_timezone_set ( $timezone_info ['timezone_value'] );
							
							$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							$date_added = ( string ) $noteDate;
							
							$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
							$time1 = date ( 'h:i A', strtotime ( 'now' ) );
							
							$data = array ();
							
							$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
							$data ['imgOutput'] = '';
							
							$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
							$data ['user_id'] = SYSTEM_GENERATED;
							
							$data ['notetime'] = $notetime;
							$data ['note_date'] = $date_added;
							$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
							
							$data ['date_added'] = $date_added;
							
							$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row1 ['notes_id'] . "'";
							$q2 = $this->db->query ( $sql2 );
							
							$tags_id = $q2->row ['tags_id'];
							
							$this->load->model ( 'setting/tags' );
							$tags_info = $this->model_setting_tags->getTag ( $tags_id );
							
							$data ['emp_tag_id'] = $tags_info ['emp_tag_id'];
							$data ['tags_id'] = $tags_info ['tags_id'];
							
							$data ['notes_description'] = ' REPORT Auto Generated | Bed Check ';
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $row1 ['facilities_id'] );
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $row1 ['notes_id'] . "'";
							$this->db->query ( $slq1 );
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '3', task_group_by = '" . $row1 ['task_group_by'] . "', parent_id = '" . $row1 ['parent_id'] . "', task_type = '" . $row1 ['task_type'] . "' where notes_id = '" . $notes_id . "'";
							$this->db->query ( $slq1 );
						}
					}
				} else if ($rtask ['type'] == '3') {
					$psql = "SELECT * from " . DB_PREFIX . "notes where end_perpetual_task = '2' and generate_report = '0' and tasktype = '" . $rtask ['task_id'] . "' and recurrence = 'Perpetual' and ( `date_added` BETWEEN '" . $date . " 00:00:00 ' AND '" . $date . " 23:59:59' )  ";
					$qq = $this->db->query ( $psql );
					
					/* and linked_id = '0' */
					$this->load->model ( 'notes/notes' );
					$this->load->model ( 'facilities/facilities' );
					// echo "<hr>";
					// var_dump($qq->num_rows);
					
					if ($qq->num_rows > 0) {
						foreach ( $qq->rows as $row2 ) {
							$facilities_info = $this->model_facilities_facilities->getfacilities ( $row2 ['facilities_id'] );
							
							$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
							
							date_default_timezone_set ( $timezone_info ['timezone_value'] );
							
							$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							$date_added = ( string ) $noteDate;
							
							$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
							$time1 = date ( 'h:i A', strtotime ( 'now' ) );
							
							$data = array ();
							
							$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
							$data ['imgOutput'] = '';
							
							$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
							$data ['user_id'] = SYSTEM_GENERATED;
							
							$data ['notetime'] = $notetime;
							$data ['note_date'] = $date_added;
							$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
							
							$data ['date_added'] = $date_added;
							
							$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row2 ['notes_id'] . "'";
							$q2 = $this->db->query ( $sql2 );
							
							$tags_id = $q2->row ['tags_id'];
							
							$this->load->model ( 'setting/tags' );
							$tags_info = $this->model_setting_tags->getTag ( $tags_id );
							
							$data ['emp_tag_id'] = $tags_info ['emp_tag_id'];
							$data ['tags_id'] = $tags_info ['tags_id'];
							
							$data ['notes_description'] = ' REPORT Auto Generated | Sight and Sound ';
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $row2 ['facilities_id'] );
							
							$facilities_info = $this->model_facilities_facilities->getfacilities ( $row2 ['facilities_id'] );
							
							$data2 = array ();
							
							if ($rtask ['forms_id'] == '42') {
								$data2 ['design_forms'] [0] [0] ['date_93638826'] = $noteDate1;
								$data2 ['design_forms'] [0] [0] ['time_33135211'] = $time1;
								
								$data2 ['design_forms'] [0] [0] ['select_92904727'] = 'Critical Watch';
								$data2 ['design_forms'] [0] [0] ['text_19577683'] = $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
								$data2 ['design_forms'] [0] [0] ['text_19577683_1_tags_id'] = $tags_info ['tags_id'];
								
								$data2 ['design_forms'] [0] [0] ['facility_10996239'] = $facilities_info ['facilities_id'];
								
								$data2 ['design_forms'] [0] [0] ['date_20911611'] = $noteDate1;
								$data2 ['design_forms'] [0] [0] ['time_37226865'] = $time1;
								
								$data2 ['design_forms'] [0] [0] [0] ['checkbox_21606733'] = 'Yes';
								$data2 ['design_forms'] [0] [0] [0] ['checkbox_73331139'] = 'Yes';
								$data2 ['design_forms'] [0] [0] [0] ['checkbox_96143240'] = 'Yes';
								$data2 ['design_forms'] [0] [0] ['select_83462311'] = '';
								$data2 ['design_forms'] [0] [0] ['textarea_10157958'] = '';
							} else {
								$data2 ['design_forms'] [0] [0] ['date_93638826'] = $noteDate1;
								$data2 ['design_forms'] [0] [0] ['time_33135211'] = $time1;
								$data2 ['design_forms'] [0] [0] ['select_35510589'] = 'Yes';
								$data2 ['design_forms'] [0] [0] ['select_93830432'] = 'Yes';
								$data2 ['design_forms'] [0] [0] ['text_61453229'] = $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
								$data2 ['design_forms'] [0] [0] ['text_61453229_1_tags_id'] = $tags_info ['tags_id'];
								
								$data2 ['design_forms'] [0] [0] ['date_82208178'] = date ( 'm-d-Y', strtotime ( $tags_info ['dob'] ) );
								
								$data2 ['design_forms'] [0] [0] ['text8'] = '';
								$data2 ['design_forms'] [0] [0] ['text9'] = '';
								
								if ($tags_info ['select_82298274'] == '1') {
									$select10 = 'Male';
								} else {
									$select10 = 'Female';
								}
								
								$data2 ['design_forms'] [0] [0] ['select_82298274'] = $select10;
								$data2 ['design_forms'] [0] [0] ['text_64107947'] = $facilities_info ['facility'];
								$data2 ['design_forms'] [0] [0] ['text12'] = '';
								
								$data2 ['design_forms'] [0] [0] ['0'] ['checkbox_45658071'] = 'Sucide Risk';
								$data2 ['design_forms'] [0] [0] ['1'] ['checkbox_45658071'] = '';
								$data2 ['design_forms'] [0] [0] ['2'] ['checkbox_45658071'] = '';
								$data2 ['design_forms'] [0] [0] ['3'] ['checkbox_45658071'] = '';
								$data2 ['design_forms'] [0] [0] ['4'] ['checkbox_45658071'] = '';
								
								$data2 ['design_forms'] [0] [0] ['date_48860525'] = $noteDate1;
								$data2 ['design_forms'] [0] [0] ['time_41789102'] = $time1;
								
								$data2 ['signature'] [0] [0] ['signature14'] = '';
								$data2 ['signature'] [0] [0] ['signature18'] = '';
								
								$data2 ['design_forms'] [0] [0] ['date_31171166'] = $noteDate1;
								$data2 ['design_forms'] [0] [0] ['time_88128841'] = $time1;
								
								$data2 ['design_forms'] [0] [0] ['text21'] = $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
								$data2 ['design_forms'] [0] [0] ['text22'] = ''; // $tags_info['ssn'];
							}
							
							$data23 = array ();
							$data23 ['forms_design_id'] = $rtask ['forms_id'];
							$data23 ['notes_id'] = $notes_id;
							$data23 ['tags_id'] = $tags_id;
							$data23 ['facilities_id'] = $row2 ['facilities_id'];
							
							$this->load->model ( 'form/form' );
							$formreturn_id = $this->model_form_form->addFormdata ( $data2, $data23 );
							
							$tempdata = $this->model_form_form->getFormDatabynotesid ( $rtask ['forms_id'], $row2 ['parent_id'] );
							
							$editdata = array ();
							
							// var_dump($tempdata);
							
							$design_forms = unserialize ( $tempdata ['design_forms'] );
							
							$formmedias = $this->model_form_form->getFormmedia ( $tempdata ['forms_id'] );
							$formsimages = array ();
							$formssigns = array ();
							if ($formmedias != null && $formmedias != "") {
								foreach ( $formmedias as $formmedia ) {
									
									if ($formmedia ['media_type'] == '1') {
										$formsimages [$formmedia ['media_name']] [] = $formmedia ['media_url'];
									}
									
									if ($formmedia ['media_type'] == '2') {
										$formssigns [$formmedia ['media_name']] [] = $formmedia ['media_url'];
									}
								}
							}
							
							if (! empty ( $tempdata )) {
								$archive_forms_id = $this->model_form_form->editFormdata ( $design_forms, $formreturn_id, $tempdata ['upload_file'], $tempdata ['image'], $formsimages, $formssigns, $tempdata ['is_final'], '', $editdata );
							}
							
							$slq1 = "UPDATE " . DB_PREFIX . "forms SET tags_id = '" . $tags_id . "',parent_id = '" . $row2 ['parent_id'] . "' where forms_id = '" . $formreturn_id . "'";
							$this->db->query ( $slq1 );
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $row2 ['notes_id'] . "'";
							$this->db->query ( $slq1 );
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '" . $notes_id . "'";
							$this->db->query ( $slq1 );
						}
					}
				} else {
					
					$notestask = "SELECT * from " . DB_PREFIX . "notes where tasktype = '" . $rtask ['task_id'] . "' and generate_report = '0' and end_task = '1'  and date_added BETWEEN '" . $start . "' AND '" . $end . "' and user_id != '" . SYSTEM_GENERATED . "' and notes_id IN (SELECT MAX(notes_id) FROM " . DB_PREFIX . "notes GROUP BY task_group_by ) order by  end_perpetual_task DESC";
					$allnotes = $this->db->query ( $notestask );
					
					// var_dump($allnotes->num_rows);
					// var_dump($allnotes->rows);
					
					// die;
					// echo "<hr>";
					
					if ($allnotes->num_rows > 0) {
						foreach ( $allnotes->rows as $row ) {
							
							if ($row ['recurrence'] == "Perpetual") {
								// var_dump($row['end_perpetual_task']);
								// var_dump($row['linked_id']);
								if ($row ['end_perpetual_task'] == "2") {
									if ($row ['linked_id'] > 0) {
										
										$data = array ();
										
										$facilities_info = $this->model_facilities_facilities->getfacilities ( $row ['facilities_id'] );
										
										$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
										
										date_default_timezone_set ( $timezone_info ['timezone_value'] );
										
										$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
										$date_added = ( string ) $noteDate;
										$update_date = ( string ) $noteDate;
										
										$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
										$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
										$time1 = date ( 'h:i A', strtotime ( 'now' ) );
										
										$data ['imgOutput'] = '';
										
										$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
										$data ['user_id'] = SYSTEM_GENERATED;
										
										$data ['notetime'] = $notetime;
										$data ['note_date'] = $date_added;
										$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
										
										$data ['date_added'] = $date_added;
										
										if ($row ['linked_id'] > 0) {
											// var_dump($rtask['forms_id']);
											// var_dump($row['linked_id']);
											$tempdata = $this->model_form_form->getFormDatabynotesid ( $rtask ['forms_id'], $row ['linked_id'] );
											
											// var_dump($tempdata);
											
											$this->load->model ( 'setting/tags' );
											$tags_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
											
											if (empty ( $tags_info )) {
												$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row ['notes_id'] . "'";
												$q2 = $this->db->query ( $sql2 );
												
												$tags_id = $q2->row ['tags_id'];
												
												$this->load->model ( 'setting/tags' );
												$tags_info = $this->model_setting_tags->getTag ( $tags_id );
												
												// var_dump($tags_info);
											}
											
											$editdata = array ();
											
											// var_dump($tempdata);
											
											$design_forms = unserialize ( $tempdata ['design_forms'] );
											// var_dump($design_forms);
											// echo "<hr>";
											
											if (! empty ( $tempdata )) {
												$archive_forms_id = $this->model_form_form->editFormdata ( $design_forms, $tempdata ['forms_id'], $tempdata ['upload_file'], $tempdata ['image'], $tempdata ['signature'], $tempdata ['form_signature'], $tempdata ['is_final'], '', $editdata );
											}
											$this->load->model ( 'setting/tags' );
											$tag_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
											
											$tdata = array ();
											$tdata ['tags_id'] = $tempdata ['tags_id'];
											$tdata ['emp_tag_id'] = $tag_info ['emp_tag_id'];
											$tdata ['notes_id'] = $tempdata ['notes_id'];
											$tdata ['forms_id'] = $tempdata ['forms_id'];
											$tdata ['formreturn_id'] = $tempdata ['forms_id'];
											$tdata ['forms_design_id'] = $tempdata ['custom_form_type'];
											$tdata ['form_parent_id'] = $tempdata ['parent_id'];
											$tdata ['archive_forms_id'] = $archive_forms_id;
											$tdata ['task_id'] = '0';
											$tdata ['facilities_id'] = $row ['facilities_id'];
											$tdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
											// var_dump($tdata);
										} else {
											$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row ['notes_id'] . "'";
											$q2 = $this->db->query ( $sql2 );
											
											$tags_id = $q2->row ['tags_id'];
											
											$this->load->model ( 'setting/tags' );
											$tags_info = $this->model_setting_tags->getTag ( $tags_id );
										}
										
										$data ['emp_tag_id'] = $tags_info ['emp_tag_id'];
										$data ['tags_id'] = $tags_info ['tags_id'];
										
										$data ['notes_description'] = ' REPORT Auto Generated | ' . $rtask ['tasktype_name'];
										
										// var_dump($data);
										
										if ($tempdata ['forms_id'] != null && $tempdata ['forms_id'] != "") {
											$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $row ['facilities_id'] );
											
											// var_dump($tempdata);
											$fdata34 = array ();
											$fdata34 ['notes_id'] = $notes_id;
											$fdata34 ['archive_notes_id'] = $tempdata ['notes_id'];
											// $fdata34['archive_forms_id'] = $archive_forms_id;
											$fdata34 ['archive_forms_id'] = $archive_forms_id;
											$fdata34 ['forms_id'] = $tempdata ['forms_id'];
											$fdata34 ['update_date'] = $update_date;
											
											// var_dump($fdata34);
											// die;
											
											$this->model_form_form->updateformnotesinfo ( $fdata34 );
											$this->model_form_form->updateformnotesinfo2 ( $fdata34 );
											
											// echo "<hr>";
											// die;
											
											$slq1 = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $row ['parent_id'] . "' where forms_id = '" . $tempdata ['forms_id'] . "'";
											$this->db->query ( $slq1 );
											
											$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $row ['notes_id'] . "'";
											$this->db->query ( $slq1 );
											
											$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '" . $notes_id . "'";
											$this->db->query ( $slq1 );
											// echo "<hr>";
										}
									}
								}
							} elseif ($row ['recurrence'] != "Perpetual") {
								// var_dump($row ['linked_id']);
								
								if ($row ['linked_id'] > 0) {
									
									$data = array ();
									
									$facilities_info = $this->model_facilities_facilities->getfacilities ( $row ['facilities_id'] );
									
									$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
									
									date_default_timezone_set ( $timezone_info ['timezone_value'] );
									
									$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
									$date_added = ( string ) $noteDate;
									$update_date = ( string ) $noteDate;
									
									$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
									$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
									$time1 = date ( 'h:i A', strtotime ( 'now' ) );
									
									$data ['imgOutput'] = '';
									
									$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
									$data ['user_id'] = SYSTEM_GENERATED;
									
									$data ['notetime'] = $notetime;
									$data ['note_date'] = $date_added;
									$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
									
									$data ['date_added'] = $date_added;
									
									if ($row ['linked_id'] > 0) {
										// var_dump($rtask['forms_id']);
										// var_dump($row['linked_id']);
										$tempdata = $this->model_form_form->getFormDatabynotesid ( $rtask ['forms_id'], $row ['linked_id'] );
										
										// var_dump($tempdata);
										
										$this->load->model ( 'setting/tags' );
										$tags_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
										
										if (empty ( $tags_info )) {
											$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row ['notes_id'] . "'";
											$q2 = $this->db->query ( $sql2 );
											
											$tags_id = $q2->row ['tags_id'];
											
											$this->load->model ( 'setting/tags' );
											$tags_info = $this->model_setting_tags->getTag ( $tags_id );
											
											// var_dump($tags_info);
										}
										
										$editdata = array ();
										
										// var_dump($tempdata);
										
										$design_forms = unserialize ( $tempdata ['design_forms'] );
										// var_dump($design_forms);
										// echo "<hr>";
										
										if (! empty ( $tempdata )) {
											$archive_forms_id = $this->model_form_form->editFormdata ( $design_forms, $tempdata ['forms_id'], $tempdata ['upload_file'], $tempdata ['image'], $tempdata ['signature'], $tempdata ['form_signature'], $tempdata ['is_final'], '', $editdata );
										}
										$this->load->model ( 'setting/tags' );
										$tag_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
										
										$tdata = array ();
										$tdata ['tags_id'] = $tempdata ['tags_id'];
										$tdata ['emp_tag_id'] = $tag_info ['emp_tag_id'];
										$tdata ['notes_id'] = $tempdata ['notes_id'];
										$tdata ['forms_id'] = $tempdata ['forms_id'];
										$tdata ['formreturn_id'] = $tempdata ['forms_id'];
										$tdata ['forms_design_id'] = $tempdata ['custom_form_type'];
										$tdata ['form_parent_id'] = $tempdata ['parent_id'];
										$tdata ['archive_forms_id'] = $archive_forms_id;
										$tdata ['task_id'] = '0';
										$tdata ['facilities_id'] = $row ['facilities_id'];
										$tdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
										// var_dump($tdata);
									} else {
										$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row ['notes_id'] . "'";
										$q2 = $this->db->query ( $sql2 );
										
										$tags_id = $q2->row ['tags_id'];
										
										$this->load->model ( 'setting/tags' );
										$tags_info = $this->model_setting_tags->getTag ( $tags_id );
									}
									
									$data ['emp_tag_id'] = $tags_info ['emp_tag_id'];
									$data ['tags_id'] = $tags_info ['tags_id'];
									
									$data ['notes_description'] = ' REPORT Auto Generated | ' . $rtask ['tasktype_name'];
									
									// var_dump($data);
									
									if ($tempdata ['forms_id'] != null && $tempdata ['forms_id'] != "") {
										$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $row ['facilities_id'] );
										
										// var_dump($tempdata);
										$fdata34 = array ();
										$fdata34 ['notes_id'] = $notes_id;
										$fdata34 ['archive_notes_id'] = $tempdata ['notes_id'];
										// $fdata34['archive_forms_id'] = $archive_forms_id;
										$fdata34 ['archive_forms_id'] = $archive_forms_id;
										$fdata34 ['forms_id'] = $tempdata ['forms_id'];
										$fdata34 ['update_date'] = $update_date;
										
										// var_dump($fdata34);
										// die;
										
										$this->model_form_form->updateformnotesinfo ( $fdata34 );
										$this->model_form_form->updateformnotesinfo2 ( $fdata34 );
										
										// echo "<hr>";
										// die;
										
										$slq1 = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $row ['parent_id'] . "' where forms_id = '" . $tempdata ['forms_id'] . "'";
										$this->db->query ( $slq1 );
										
										$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $row ['notes_id'] . "'";
										$this->db->query ( $slq1 );
										
										$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '" . $notes_id . "'";
										$this->db->query ( $slq1 );
										// echo "<hr>";
									}
								} else {
									$data = array ();
									
									$facilities_info = $this->model_facilities_facilities->getfacilities ( $row ['facilities_id'] );
									
									$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
									
									date_default_timezone_set ( $timezone_info ['timezone_value'] );
									
									$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
									$date_added = ( string ) $noteDate;
									$update_date = ( string ) $noteDate;
									
									$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
									$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
									$time1 = date ( 'h:i A', strtotime ( 'now' ) );
									
									$data ['imgOutput'] = '';
									
									$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
									$data ['user_id'] = SYSTEM_GENERATED;
									
									$data ['notetime'] = $notetime;
									$data ['note_date'] = $date_added;
									$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
									
									$data ['date_added'] = $date_added;
									
									if ($row ['notes_id'] > 0) {
										// var_dump($rtask['forms_id']);
										// var_dump($row['linked_id']);
										$tempdata = $this->model_form_form->getFormDatabynotesid ( $rtask ['forms_id'], $row ['notes_id'] );
										
										// var_dump($tempdata);
										
										$this->load->model ( 'setting/tags' );
										$tags_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
										
										if (empty ( $tags_info )) {
											$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '" . $row ['notes_id'] . "'";
											$q2 = $this->db->query ( $sql2 );
											
											$tags_id = $q2->row ['tags_id'];
											
											$this->load->model ( 'setting/tags' );
											$tags_info = $this->model_setting_tags->getTag ( $tags_id );
											
											// var_dump($tags_info);
										}
										
										$editdata = array ();
										
										// var_dump($tempdata);
										
										$design_forms = unserialize ( $tempdata ['design_forms'] );
										// var_dump($design_forms);
										// echo "<hr>";
										
										if (! empty ( $tempdata )) {
											$archive_forms_id = $this->model_form_form->editFormdata ( $design_forms, $tempdata ['forms_id'], $tempdata ['upload_file'], $tempdata ['image'], $tempdata ['signature'], $tempdata ['form_signature'], $tempdata ['is_final'], '', $editdata );
										}
										$this->load->model ( 'setting/tags' );
										$tag_info = $this->model_setting_tags->getTag ( $tempdata ['tags_id'] );
										
										$tdata = array ();
										$tdata ['tags_id'] = $tempdata ['tags_id'];
										$tdata ['emp_tag_id'] = $tag_info ['emp_tag_id'];
										$tdata ['notes_id'] = $tempdata ['notes_id'];
										$tdata ['forms_id'] = $tempdata ['forms_id'];
										$tdata ['formreturn_id'] = $tempdata ['forms_id'];
										$tdata ['forms_design_id'] = $tempdata ['custom_form_type'];
										$tdata ['form_parent_id'] = $tempdata ['parent_id'];
										$tdata ['archive_forms_id'] = $archive_forms_id;
										$tdata ['task_id'] = '0';
										$tdata ['facilities_id'] = $row ['facilities_id'];
										$tdata ['facilitytimezone'] = $timezone_info ['timezone_value'];
										// var_dump($tdata);
										
										$data ['emp_tag_id'] = $tags_info ['emp_tag_id'];
										$data ['tags_id'] = $tags_info ['tags_id'];
										
										$data ['notes_description'] = ' REPORT Auto Generated | ' . $rtask ['tasktype_name'];
										
										// var_dump($data);
										
										$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $row ['facilities_id'] );
										
										// var_dump($tempdata);
										$fdata34 = array ();
										$fdata34 ['notes_id'] = $notes_id;
										$fdata34 ['archive_notes_id'] = $tempdata ['notes_id'];
										// $fdata34['archive_forms_id'] = $archive_forms_id;
										$fdata34 ['archive_forms_id'] = $archive_forms_id;
										$fdata34 ['forms_id'] = $tempdata ['forms_id'];
										$fdata34 ['update_date'] = $update_date;
										
										// var_dump($fdata34);
										// die;
										
										$this->model_form_form->updateformnotesinfo ( $fdata34 );
										$this->model_form_form->updateformnotesinfo2 ( $fdata34 );
										
										// echo "<hr>";
										// die;
										
										$slq1 = "UPDATE " . DB_PREFIX . "forms SET parent_id = '" . $row ['parent_id'] . "' where forms_id = '" . $tempdata ['forms_id'] . "'";
										$this->db->query ( $slq1 );
										
										$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $row ['notes_id'] . "'";
										$this->db->query ( $slq1 );
										
										$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '" . $notes_id . "'";
										$this->db->query ( $slq1 );
										// echo "<hr>";
									}
								}
							}
						}
					}
				}
			}
		}
			
		echo "Success";
		
	}
	
	
	public function outofthecelllog() {
		
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
	
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'user/user_group' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );

		
		$this->load->model ( 'notes/clientstatus' );
		$this->load->model ( 'customer/customer' );
		
		$data3 = array ();
		$customforms = $this->model_notes_clientstatus->getclientstatuss ( $data3 );
		foreach ( $customforms as $customform ) {
			$rule_action_content = unserialize ( $customform ['rule_action_content'] );
			
			if ($rule_action_content ['out_from_cell'] == "1") {
				
				$hourout_arr [] = $customform ['tag_status_id'];
			}
		}
		
		if ($hourout_arr != null && $hourout_arr != "") {
			$hourout_arr = implode ( ",", $hourout_arr );
			$rolecalls = $hourout_arr;
			
			$htasksql = "SELECT * from " . DB_PREFIX . "tags where status = 1 and discharge = 0 and tags_status_in = 'Admitted' and notes_id > 0 and ( role_call IN (" . $rolecalls . ") or fixed_status_id IN (" . $rolecalls . ")) ";
			$sqlhs = $this->db->query ( $htasksql );
			
			$this->load->model ( 'api/permision' );
			$this->load->model ( 'notes/clientstatus' );
			$this->load->model ( 'resident/resident' );
			
			if ($sqlhs->num_rows > 0) {
				foreach ( $sqlhs->rows as $htask ) {
					$facility = $this->model_facilities_facilities->getfacilities ( $htask ['facilities_id'] );
					
					$unique_id = $facility ['customer_key'];
					
					$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
					
					$client_info = unserialize ( $customer_info ['client_info_notes'] );
					
					if (! empty ( $customer_info ['setting_data'] )) {
						$customers = unserialize ( $customer_info ['setting_data'] );
					}
					
					if ($customers ['rules_operation'] == '1') {
						
						$rules_start_time = date ( 'H:i:s', strtotime ( $customers ['rules_start_time'] ) );
						$rules_end_time = date ( 'H:i:s', strtotime ( $customers ['rules_end_time'] ) );
						
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
						$facilitytimezone = $timezone_info ['timezone_value'];
						
						date_default_timezone_set ( $facilitytimezone );
						$current_date_user = date ( 'Y-m-d' );
						
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = ( string ) $noteDate;
						
						$noteDate1 = date ( 'm-d-Y', strtotime ( 'now' ) );
						$time1 = date ( 'h:i A', strtotime ( 'now' ) );
						
						$data = array ();
						
						$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						$data ['imgOutput'] = '';
						
						if ($notetime >= $rules_end_time) {
							
							$data ['notes_pin'] = SYSTEM_GENERATED_PIN;
							$data ['user_id'] = SYSTEM_GENERATED;
							
							$data ['notetime'] = $notetime;
							$data ['note_date'] = $date_added;
							$data ['facilitytimezone'] = $timezone_info ['timezone_value'];
							
							$data ['date_added'] = $date_added;
							
							$data ['emp_tag_id'] = $htask ['emp_tag_id'];
							$data ['tags_id'] = $htask ['tags_id'];
							
							$clientinfo = $this->model_api_permision->getclientinfo ( $htask ['facilities_id'], $htask );
							$cname = $clientinfo ['name'];
							
							$taskcontent = $cname;
							
							$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $htask ['role_call'] );
							$roleCall66 = $clientstatus_info ['name'];
							
							$caltime = " | ";
							$caltime1 = "";
							$status_total_time = 0;
							// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
							
							if ($clientstatus_info ['track_time'] == 1) {
								
								$notes_data = $this->model_notes_notes->getnotes ( $htask ['notes_id'] );
								// echo '<pre>'; print_r($notes_data); echo '</pre>';
								$current_date = date ( 'Y-m-d H:i:s' );
								$start_date = new DateTime ( $notes_data ['date_added'] );
								$since_start = $start_date->diff ( new DateTime ( $current_date ) );
								
								if ($since_start->y > 0) {
									$caltime .= $since_start->y . ' years ';
									$status_total_time = 60 * 24 * 365 * $since_start->y;
								}
								
								if ($since_start->m > 0) {
									$caltime .= $since_start->m . ' months ';
									$status_total_time += 60 * 24 * 30 * $since_start->m;
								}
								
								if ($since_start->d > 0) {
									$caltime .= $since_start->d . ' days ';
									$status_total_time += 60 * 24 * $since_start->d;
								}
								
								if ($since_start->h > 0) {
									$caltime .= $since_start->h . ' hours ';
									$status_total_time += 60 * $since_start->h;
								}
								
								if ($since_start->i > 0) {
									$caltime .= $since_start->i . ' minutes ';
									$status_total_time += $since_start->i;
								}
								
								// $caltime.= ' in '.$roleCall66 . ' | ';
								$caltime1 .= $roleCall66;
							} else {
								$caltime1 .= $roleCall66;
							}
							
							$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus ( $customers ['defaultrole_call'] );
							
							$roleCall2 = $clientstatus_info2 ['name'];
							
							$data ['notes_description'] = ' Out of Cell time not tracked | ' . $taskcontent . ' status changed ' . $caltime1 . ' to | ' . $roleCall2 . $caltime;
							
							$data ['status_total_time'] = $status_total_time;
							
							$data ['tag_status_id'] = $customers ['defaultrole_call'];
							//$data ['move_notes_id'] = $htask ['notes_id'];
							
							$data ['substatus_ids'] = $htask ['tag_status_ids'];
							$data ['substatus_idscomment'] = $htask ['comments'];
							$data ['fixed_status_id'] = $htask ['fixed_status_id'];
							$data ['move_notes_id'] = $htask ['notes_id'];
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $htask ['facilities_id'] );
							
							if($htask ['notes_id'] > 0){
								$cdatam = array ();
								$cdatam ['notes_id'] = $htask ['notes_id'];
								$cdatam ['move_notes_id'] = $notes_id;
								$cdatam ['tags_id'] = $htask ['tags_id'];
								$cdatam ['status_total_time'] = $status_total_time;
								
								
								$this->model_resident_resident->updateclientStatusnotes ( $cdatam );
							}
							
							$sql12 = "UPDATE `" . DB_PREFIX . "tags` SET notes_id = '0' where tags_id = '" . $htask ['tags_id'] . "'";
							$this->db->query ( $sql12 );
							if ($htask ['fixed_status_id'] > 0) {
								$fixclientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $htask ['fixed_status_id'] );
								
								if ($ruleaction_ontent ['forms_id'] == "" && $ruleaction_ontent ['forms_id'] == null) {
									$sql1d2 = "UPDATE `" . DB_PREFIX . "tags` SET fixed_status_id = '0',tag_status_ids='',comments='' where tags_id = '" . $htask ['tags_id'] . "'";
									$this->db->query ( $sql1d2 );
								}
							}
							
							if ($clientstatus_info ['track_time'] == 1) {
								$tmdata = array ();
								$tmdata ['notes_id'] = $notes_id;
								$tmdata ['facilities_id'] = $htask ['facilities_id'];
								$tmdata ['unique_id'] = $facility ['customer_key'];
								$tmdata ['tags_id'] = $htask ['tags_id'];
								$tmdata ['tag_status_id'] = $clientstatus_info ['tag_status_id'];
								$tmdata ['new_tag_status_id'] = $clientstatus_info2 ['tag_status_id'];
								$tmdata ['keyword_id'] = '';
								$tmdata ['types'] = 1;
								$tmdata ['years'] = $since_start->y;
								$tmdata ['months'] = $since_start->m;
								$tmdata ['days'] = $since_start->d;
								$tmdata ['hours'] = $since_start->h;
								$tmdata ['minutes'] = $since_start->i;
								$tmdata ['date_added'] = date ( 'Y-m-d H:i:s' );
								$this->model_resident_resident->addtracktime ( $tmdata );
							}
							
							$this->model_resident_resident->updatetagrolecall ( $htask ['tags_id'], $customers ['defaultrole_call'] );
						}
					}
				}
			}
		}
		
		echo "Success";
	}
	
	
	public function appnotify() {
		try {
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
	
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'user/user' );
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'user/user_group' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		
		
		$nksqld = "SELECT * from " . DB_PREFIX . "device_details where status = 1 and is_deletd = 0 and registration_id != '' ";
		$nksd = $this->db->query ( $nksqld );
		
		if ($nksd->num_rows > 0) {
			foreach ( $nksd->rows as $ndform ) {
				
				if ($ndform ['facilities_id'] > 0) {
					
					$registration_id = $ndform ['registration_id'];
					$d = array ();
					$d ['facilities_id'] = $ndform ['facilities_id'];
					$rules = $this->model_notes_rules->getRules ( $d );
					// var_dump($rules);
					// echo "<hr>";
					$json = array ();
					$notesIds = array ();
					$tnotesIds = array ();
					$facilityDetails = array ();
					
					$andRuleArray = array ();
					$nrulesvalue = "";
					$rulename = "";
					$rulesvalue = "";
					$andrulesValues = array ();
					$andrulesTaskValues = array ();
					$andrulesActionValues = array ();
					$andrulesActionValues2 = array ();
					
					$rowModule = array ();
					
					$facilities_id = $ndform ['facilities_id'];
					
					$this->load->model ( 'facilities/facilities' );
					$this->load->model ( 'setting/timezone' );
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					
					// $timezone_name = $this->request->post['facilitytimezone'];
					$timezone_name = $timezone_info ['timezone_value'];
					
					$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					
					if ($facility ['android_audio_file'] != NULL && $facility ['android_audio_file'] != "") {
						$facility_android_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility ['android_audio_file'];
					} else {
						$facility_android_audio_file = '';
					}
					
					if ($facility ['ios_audio_file'] != NULL && $facility ['ios_audio_file'] != "") {
						$facility_ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility ['ios_audio_file'];
					} else {
						$facility_ios_audio_file = '';
					}
					
					$config_task_status = $facility ['config_task_status'];
					$config_rules_status = $facility ['config_rules_status'];
					
					//$country_info = $this->model_setting_country->getCountry ( $facility ['country_id'] );
					//$zone_info = $this->model_setting_zone->getZone ( $facility ['zone_id'] );
					
					date_default_timezone_set ( $timezone_name );
					
					$currenttimes = date ( 'H:i' );
					$searchdate = date ( 'm-d-Y' );
					
					$current_date_user = date ( 'Y-m-d' );
					
					if ($rules) {
						foreach ( $rules as $rule ) {
							$allnotesIds = array ();
							$allrulename = array ();
							if ($currenttimes == '23:59') {
								$sql = "update `" . DB_PREFIX . "rules` set snooze_dismiss = '0' where rules_id ='" . $rule ['rules_id'] . "'";
								$this->db->query ( $sql );
							}
							
							if ($config_rules_status == '1') {
								if ($rule ['rules_operation'] == 2) {
									foreach ( $rule ['onschedule_rules_module'] as $onschedule_rules_module ) {
										
										// var_dump($rule['rules_operation_recurrence']);
										
										if ($rule ['rules_operation_recurrence'] == '1') {
											
											$date = str_replace ( '-', '/', $searchdate );
											$res = explode ( "/", $date );
											$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1] . ' ' . date ( 'H:i:s' );
											
											$snooze_time71 = 1;
											$thestime61 = date ( 'H:i:s' );
											$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
											
											// var_dump($changedDate);
											$dailytime = date ( 'H:i' );
											
											// var_dump($dailytime);
											
											$rules_operation_time = date ( 'H:i', strtotime ( $rule ['rules_operation_time'] ) );
											
											// var_dump($rules_operation_time);
											// echo "<hr>";
											if ($dailytime == $rules_operation_time) {
												
												$onschedule_description = nl2br ( $onschedule_rules_module ['onschedule_description'] );
												
												/* sms */
												if ($onschedule_rules_module ['onschedule_action'] == '1') {
													
													if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
														
														$user_roles1 = $onschedule_rules_module ['ouser_roles'];
														
														foreach ( $user_roles1 as $user_role ) {
															$urole = array ();
															$urole ['user_group_id'] = $user_role;
															$tusers = $this->model_user_user->getUsers ( $urole );
															
															if ($tusers) {
																foreach ( $tusers as $tuser ) {
																	if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
																		$number = $tuser ['phone_number'];
																		
																		$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																		
																		$sdata = array ();
																		$sdata ['message'] = $message;
																		$sdata ['phone_number'] = $tuser ['phone_number'];
																		$sdata ['facilities_id'] = $facilities_id;
																		
																		$response = $this->model_api_smsapi->sendsms ( $sdata );
																	}
																}
															}
														}
													}
													
													if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
														$userids1 = $onschedule_rules_module ['ouserids'];
														
														foreach ( $userids1 as $userid ) {
															$user_info = $this->model_user_user->getUserbyupdate ( $userid );
															if ($user_info) {
																if ($user_info ['phone_number'] != 0) {
																	$number = $user_info ['phone_number'];
																	
																	$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																	
																	$sdata = array ();
																	$sdata ['message'] = $message;
																	$sdata ['phone_number'] = $user_info ['phone_number'];
																	$sdata ['facilities_id'] = $facilities_id;
																	$response = $this->model_api_smsapi->sendsms ( $sdata );
																}
															}
														}
													}
													
													if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
														$number = '19045832155';
														
														$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
														
														$sdata = array ();
														$sdata ['message'] = $message;
														$sdata ['phone_number'] = '19045832155';
														$sdata ['facilities_id'] = $facilities_id;
														$response = $this->model_api_smsapi->sendsms ( $sdata );
													}
													
													// $response = $client->account->sms_messages->create($from,$number,$text);
												}
												
												/* Email */
												if ($onschedule_rules_module ['onschedule_action'] == '2') {
													
													$onschedule_description51125e2 = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
													
													$resultd = array ();
													$resultd ['notes_id'] = '';
													$resultd ['highlighter_value'] = '';
													$resultd ['notes_description'] = $onschedule_description51125e2;
													$resultd ['date_added'] = date ( 'j, F Y', strtotime ( $changedDate ) );
													$resultd ['note_date'] = date ( 'j, F Y', strtotime ( $changedDate ) );
													$resultd ['notetime'] = date ( 'h:i A', strtotime ( $taskTime ) );
													$resultd ['username'] = $result ['user_id'];
													$resultd ['email'] = $user_info ['email'];
													$resultd ['phone_number'] = $user_info ['phone_number'];
													$resultd ['sms_number'] = $facility ['sms_number'];
													$resultd ['facility'] = $facility ['facility'];
													$resultd ['address'] = $facility ['address'];
													$resultd ['location'] = $facility ['location'];
													$resultd ['zipcode'] = $facility ['zipcode'];
													$resultd ['contry_name'] = $country_info ['name'];
													$resultd ['zone_name'] = $zone_info ['name'];
													$resultd ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
													
													$message33 = "";
													
													$rulevalue = date ( 'h:i A', strtotime ( $taskTime ) );
													$message33 .= $this->emailtemplate ( $resultd, $rule ['rules_name'], 'Daily', $rulevalue );
													
													$useremailids = array ();
													
													if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
														
														$user_roles1 = $onschedule_rules_module ['ouser_roles'];
														
														foreach ( $user_roles1 as $user_role ) {
															$urole = array ();
															$urole ['user_group_id'] = $user_role;
															$tusers = $this->model_user_user->getUsers ( $urole );
															
															if ($tusers) {
																foreach ( $tusers as $tuser ) {
																	if ($tuser ['email'] != null && $tuser ['email'] != "") {
																		
																		$useremailids [] = $tuser ['email'];
																	}
																}
															}
														}
													}
													
													if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
														$userids1 = $onschedule_rules_module ['ouserids'];
														
														foreach ( $userids1 as $userid ) {
															$user_info = $this->model_user_user->getUserbyupdate ( $userid );
															if ($user_info) {
																if ($user_info ['email']) {
																	
																	$useremailids [] = $user_info ['email'];
																}
															}
														}
													}
													
													if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
														
														$user_email = 'app-monitoring@noteactive.com';
													}
													
													$edata = array ();
													$edata ['message'] = $message33;
													$edata ['subject'] = 'This is an Automated Alert Email.';
													$edata ['useremailids'] = $useremailids;
													$edata ['user_email'] = $user_email;
													
													$email_status = $this->model_api_emailapi->sendmail ( $edata );
												}
												
												/* Notification */
												if ($onschedule_rules_module ['onschedule_action'] == '3') {
													
													$onschedule_description51125n2 = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
													
													if ($rule ['snooze_dismiss'] != '2') {
														$json ['rulenotes'] [] = array (
																'notes_id' => '',
																'rules_id' => $rule ['rules_id'],
																'facilities_info' => $facilities_info,
																'highlighter_value' => '',
																'notes_description' => $onschedule_description51125n2,
																'date_added' => date ( 'j, F Y', strtotime ( $changedDate ) ),
																'note_date' => date ( 'j, F Y h:i A', strtotime ( $changedDate ) ),
																'notetime' => date ( 'h:i A', strtotime ( $taskTime ) ),
																'username' => '',
																'email' => '',
																'facility' => '' 
														);
														
														$json ['total'] = '1';
													}
												}
												
												/* Create Task */
												if ($onschedule_rules_module ['onschedule_action'] == '4') {
													
													$sqls23d = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $onschedule_rules_module ['task_random_id'] . "' and taskadded = '0' ";
													$query4d = $this->db->query ( $sqls23d );
													
													if ($query4d->num_rows == 0) {
														
														$addtaskd = array ();
														
														/*
														 * if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
														 * $snooze_time71 = 0;
														 * $thestime61 = $onschedule_rules_module['taskTime'];
														 * }else{
														 * $snooze_time71 = 10;
														 * $thestime61 = date('H:i:s');
														 * }
														 */
														
														$snooze_time71 = 1;
														$thestime61 = date ( 'H:i:s' );
														// var_dump($thestime6);
														
														$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
														
														$date = str_replace ( '-', '/', $onschedule_rules_module ['taskDate'] );
														$res = explode ( "/", $date );
														$taskDate = $res [1] . "-" . $res [0] . "-" . $res [2];
														
														$date2 = str_replace ( '-', '/', $onschedule_rules_module ['end_recurrence_date'] );
														$res2 = explode ( "/", $date2 );
														$end_recurrence_date = $res2 [1] . "-" . $res2 [0] . "-" . $res2 [2];
														
														$addtaskd ['taskDate'] = date ( 'm-d-Y', strtotime ( $taskDate ) );
														$addtaskd ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $end_recurrence_date ) );
														$addtaskd ['recurrence'] = $onschedule_rules_module ['recurrence'];
														$addtaskd ['recurnce_week'] = $onschedule_rules_module ['recurnce_week'];
														$addtaskd ['recurnce_hrly'] = $onschedule_rules_module ['recurnce_hrly'];
														$addtaskd ['recurnce_month'] = $onschedule_rules_module ['recurnce_month'];
														$addtaskd ['recurnce_day'] = $onschedule_rules_module ['recurnce_day'];
														$addtaskd ['taskTime'] = $taskTime; // date('H:i:s');
														$addtaskd ['endtime'] = $stime8;
														
														$onschedule_description11 = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
														
														$addtaskd ['description'] = $onschedule_rules_module ['description'] . ' ' . $onschedule_description11;
														
														$addtaskd ['assignto'] = $onschedule_rules_module ['assign_to'];
														
														$addtaskd ['facilities_id'] = $facilities_id;
														$addtaskd ['task_form_id'] = $onschedule_rules_module ['task_form_id'];
														
														if ($onschedule_rules_module ['transport_tags'] != null && $onschedule_rules_module ['transport_tags'] != "") {
															$addtaskd ['transport_tags'] = explode ( ',', $onschedule_rules_module ['transport_tags'] );
														}
														
														$addtaskd ['pickup_facilities_id'] = $onschedule_rules_module ['pickup_facilities_id'];
														$addtaskd ['pickup_locations_address'] = $onschedule_rules_module ['pickup_locations_address'];
														$addtaskd ['pickup_locations_time'] = $onschedule_rules_module ['pickup_locations_time'];
														
														$addtaskd ['dropoff_facilities_id'] = $onschedule_rules_module ['dropoff_facilities_id'];
														$addtaskd ['dropoff_locations_address'] = $onschedule_rules_module ['dropoff_locations_address'];
														$addtaskd ['dropoff_locations_time'] = $onschedule_rules_module ['dropoff_locations_time'];
														
														$addtaskd ['tasktype'] = $onschedule_rules_module ['tasktype'];
														$addtaskd ['numChecklist'] = $onschedule_rules_module ['numChecklist'];
														$addtaskd ['task_alert'] = $onschedule_rules_module ['task_alert'];
														$addtaskd ['alert_type_sms'] = $onschedule_rules_module ['alert_type_sms'];
														$addtaskd ['alert_type_notification'] = $onschedule_rules_module ['alert_type_notification'];
														$addtaskd ['alert_type_email'] = $onschedule_rules_module ['alert_type_email'];
														$addtaskd ['rules_task'] = $onschedule_rules_module ['task_random_id'];
														
														$addtaskd ['recurnce_hrly_recurnce'] = $onschedule_rules_module ['recurnce_hrly_recurnce'];
														$addtaskd ['daily_endtime'] = $onschedule_rules_module ['daily_endtime'];
														
														if ($onschedule_rules_module ['daily_times'] != null && $onschedule_rules_module ['daily_times'] != "") {
															$addtaskd ['daily_times'] = explode ( ',', $onschedule_rules_module ['daily_times'] );
														}
														
														if ($onschedule_rules_module ['medication_tags'] != null && $onschedule_rules_module ['medication_tags'] != "") {
															$addtaskd ['medication_tags'] = explode ( ',', $onschedule_rules_module ['medication_tags'] );
															
															$aa = urldecode ( $onschedule_rules_module ['tags_medication_details_ids'] );
															$aa1 = unserialize ( $aa );
															
															$tags_medication_details_ids = array ();
															foreach ( $aa1 as $key => $mresult ) {
																$tags_medication_details_ids [$key] = $mresult;
															}
															$addtaskd ['tags_medication_details_ids'] = $tags_medication_details_ids;
														}
														
														$addtaskd ['emp_tag_id'] = $onschedule_rules_module ['emp_tag_id'];
														
														$addtaskd ['recurnce_hrly_perpetual'] = $onschedule_rules_module ['recurnce_hrly_perpetual'];
														$addtaskd ['completion_alert'] = $onschedule_rules_module ['completion_alert'];
														$addtaskd ['completion_alert_type_sms'] = $onschedule_rules_module ['completion_alert_type_sms'];
														$addtaskd ['completion_alert_type_email'] = $onschedule_rules_module ['completion_alert_type_email'];
														
														if ($onschedule_rules_module ['user_roles'] != null && $onschedule_rules_module ['user_roles'] != "") {
															$addtaskd ['user_roles'] = explode ( ',', $onschedule_rules_module ['user_roles'] );
														}
														
														if ($onschedule_rules_module ['userids'] != null && $onschedule_rules_module ['userids'] != "") {
															$addtaskd ['userids'] = explode ( ',', $onschedule_rules_module ['userids'] );
														}
														$addtaskd ['task_status'] = $onschedule_rules_module ['task_status'];
														
														$addtaskd ['visitation_tag_id'] = $onschedule_rules_module ['visitation_tag_id'];
														
														if ($onschedule_rules_module ['visitation_tags'] != null && $onschedule_rules_module ['visitation_tags'] != "") {
															$addtaskd ['visitation_tags'] = explode ( ',', $onschedule_rules_module ['visitation_tags'] );
														}
														$addtaskd ['visitation_start_facilities_id'] = $onschedule_rules_module ['visitation_start_facilities_id'];
														$addtaskd ['visitation_start_address'] = $onschedule_rules_module ['visitation_start_address'];
														$addtaskd ['visitation_start_time'] = $onschedule_rules_module ['visitation_start_time'];
														$addtaskd ['visitation_appoitment_facilities_id'] = $onschedule_rules_module ['visitation_appoitment_facilities_id'];
														$addtaskd ['visitation_appoitment_address'] = $onschedule_rules_module ['visitation_appoitment_address'];
														$addtaskd ['visitation_appoitment_time'] = $onschedule_rules_module ['visitation_appoitment_time'];
														$addtaskd ['complete_endtime'] = $onschedule_rules_module ['complete_endtime'];
														
														if ($onschedule_rules_module ['completed_times'] != null && $onschedule_rules_module ['completed_times'] != "") {
															$addtaskd ['completed_times'] = explode ( ',', $onschedule_rules_module ['completed_times'] );
														}
														$addtaskd ['completed_alert'] = $onschedule_rules_module ['completed_alert'];
														$addtaskd ['completed_late_alert'] = $onschedule_rules_module ['completed_late_alert'];
														$addtaskd ['incomplete_alert'] = $onschedule_rules_module ['incomplete_alert'];
														$addtaskd ['deleted_alert'] = $onschedule_rules_module ['deleted_alert'];
														$addtaskd ['attachement_form'] = $onschedule_rules_module ['attachement_form'];
														$addtaskd ['tasktype_form_id'] = $onschedule_rules_module ['tasktype_form_id'];
														
														$addtaskd ['reminder_alert'] = $onschedule_rules_module ['reminder_alert'];
														if ($onschedule_rules_module ['reminderminus'] != null && $onschedule_rules_module ['reminderminus'] != "") {
															$addtaskd ['reminderminus'] = explode ( ',', $onschedule_rules_module ['reminderminus'] );
														}
														
														if ($onschedule_rules_module ['reminderplus'] != null && $onschedule_rules_module ['reminderplus'] != "") {
															$addtaskd ['reminderplus'] = explode ( ',', $onschedule_rules_module ['reminderplus'] );
														}
														
														$addtaskd ['assign_to_type'] = $onschedule_rules_module ['assign_to_type'];
														if ($onschedule_rules_module ['user_assign_to'] != null && $onschedule_rules_module ['user_assign_to'] != "") {
															$addtaskd ['assign_to'] = explode ( ',', $onschedule_rules_module ['user_assign_to'] );
														}
														if ($onschedule_rules_module ['user_role_assign_ids'] != null && $onschedule_rules_module ['user_role_assign_ids'] != "") {
															$addtaskd ['user_role_assign_ids'] = explode ( ',', $onschedule_rules_module ['user_role_assign_ids'] );
														}
														
														$this->load->model ( 'createtask/createtask' );
														$this->model_createtask_createtask->addcreatetask ( $addtaskd, $facilities_id );
													}
												}
											}
										}
										
										if ($rule ['rules_operation_recurrence'] == '2') {
											$onschedule_description = nl2br ( $onschedule_rules_module ['onschedule_description'] );
											$date = str_replace ( '-', '/', $searchdate );
											$res = explode ( "/", $date );
											$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1] . ' ' . date ( 'H:i:s' );
											
											$snooze_time71 = 1;
											$thestime61 = date ( 'H:i:s' );
											$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
											// var_dump($changedDate);
											$dailytime = date ( 'H:i' );
											
											// var_dump($dailytime);
											
											$rules_operation_time = date ( 'H:i', strtotime ( $rule ['rules_operation_time'] ) );
											// var_dump($rules_operation_time);
											
											$currentDay = date ( 'l' );
											// var_dump($currentDay);
											
											$recurnce_week = $rule ['recurnce_week'];
											
											if ($currentDay == $recurnce_week) {
												// var_dump($recurnce_week);
												// echo "<hr>";
												if ($dailytime == $rules_operation_time) {
													// var_dump($recurnce_week);
													// echo "<hr>";
													/* sms */
													if ($onschedule_rules_module ['onschedule_action'] == '1') {
														// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
														/*
														 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
														 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
														 * $client = new Services_Twilio($account_sid, $auth_token);
														 */
														
														if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
															
															$user_roles1 = $onschedule_rules_module ['ouser_roles'];
															
															foreach ( $user_roles1 as $user_role ) {
																$urole = array ();
																$urole ['user_group_id'] = $user_role;
																$tusers = $this->model_user_user->getUsers ( $urole );
																
																if ($tusers) {
																	foreach ( $tusers as $tuser ) {
																		if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
																			$number = $tuser ['phone_number'];
																			
																			$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																			
																			$sdata = array ();
																			$sdata ['message'] = $message;
																			$sdata ['phone_number'] = $tuser ['phone_number'];
																			$sdata ['facilities_id'] = $facilities_id;
																			$response = $this->model_api_smsapi->sendsms ( $sdata );
																		}
																	}
																}
															}
														}
														
														if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
															$userids1 = $onschedule_rules_module ['ouserids'];
															
															foreach ( $userids1 as $userid ) {
																$user_info = $this->model_user_user->getUserbyupdate ( $userid );
																if ($user_info) {
																	if ($user_info ['phone_number'] != 0) {
																		$number = $user_info ['phone_number'];
																		
																		$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																		
																		$sdata = array ();
																		$sdata ['message'] = $message;
																		$sdata ['phone_number'] = $user_info ['phone_number'];
																		$sdata ['facilities_id'] = $facilities_id;
																		$response = $this->model_api_smsapi->sendsms ( $sdata );
																	}
																}
															}
														}
														
														if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
															$number = '19045832155';
															
															$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
															
															$sdata = array ();
															$sdata ['message'] = $message;
															$sdata ['phone_number'] = '19045832155';
															$sdata ['facilities_id'] = $facilities_id;
															$response = $this->model_api_smsapi->sendsms ( $sdata );
														}
													}
													
													/* Email */
													if ($onschedule_rules_module ['onschedule_action'] == '2') {
														
														$onschedule_description51125n2e = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
														
														$resultw = array ();
														$resultw ['notes_id'] = '';
														$resultw ['highlighter_value'] = '';
														$resultw ['notes_description'] = $onschedule_description51125n2e;
														$resultw ['date_added'] = date ( 'j, F Y', strtotime ( $changedDate ) );
														$resultw ['note_date'] = date ( 'j, F Y', strtotime ( $changedDate ) );
														$resultw ['notetime'] = date ( 'h:i A', strtotime ( $taskTime ) );
														$resultw ['username'] = $result ['user_id'];
														$resultw ['email'] = $user_info ['email'];
														$resultw ['phone_number'] = $user_info ['phone_number'];
														$resultw ['sms_number'] = $facility ['sms_number'];
														$resultw ['facility'] = $facility ['facility'];
														$resultw ['address'] = $facility ['address'];
														$resultw ['location'] = $facility ['location'];
														$resultw ['zipcode'] = $facility ['zipcode'];
														$resultw ['contry_name'] = $country_info ['name'];
														$resultw ['zone_name'] = $zone_info ['name'];
														$resultw ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
														
														$message33 = "";
														
														$rulevalue = date ( 'h:i A', strtotime ( $rule ['rules_operation_time'] ) );
														$message33 .= $this->emailtemplate ( $resultd, $rule ['rules_name'], 'Week', $rulevalue );
														
														$useremailids = array ();
														
														if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
															
															$user_roles1 = $onschedule_rules_module ['ouser_roles'];
															
															foreach ( $user_roles1 as $user_role ) {
																$urole = array ();
																$urole ['user_group_id'] = $user_role;
																$tusers = $this->model_user_user->getUsers ( $urole );
																
																if ($tusers) {
																	foreach ( $tusers as $tuser ) {
																		if ($tuser ['email'] != null && $tuser ['email'] != "") {
																			$useremailids [] = $tuser ['email'];
																		}
																	}
																}
															}
														}
														
														if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
															$userids1 = $onschedule_rules_module ['ouserids'];
															
															foreach ( $userids1 as $userid ) {
																$user_info = $this->model_user_user->getUserbyupdate ( $userid );
																if ($user_info) {
																	if ($user_info ['email']) {
																		$useremailids [] = $user_info ['email'];
																	}
																}
															}
														}
														
														if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
															$user_email = 'app-monitoring@noteactive.com';
														}
														
														$edata = array ();
														$edata ['message'] = $message33;
														$edata ['subject'] = 'This is an Automated Alert Email.';
														$edata ['useremailids'] = $useremailids;
														$edata ['user_email'] = $user_email;
														
														$email_status = $this->model_api_emailapi->sendmail ( $edata );
													}
													
													/* Notification */
													if ($onschedule_rules_module ['onschedule_action'] == '3') {
														
														$onschedule_description511n25n2e = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
														
														if ($rule ['snooze_dismiss'] != '2') {
															$json ['rulenotes'] [] = array (
																	'notes_id' => '',
																	'rules_id' => $rule ['rules_id'],
																	'facilities_info' => $facilities_info,
																	'highlighter_value' => '',
																	'notes_description' => $onschedule_description511n25n2e,
																	'date_added' => date ( 'j, F Y', strtotime ( $changedDate ) ),
																	'note_date' => date ( 'j, F Y h:i A', strtotime ( $changedDate ) ),
																	'notetime' => date ( 'h:i A', strtotime ( $taskTime ) ),
																	'username' => '',
																	'email' => '',
																	'facility' => '' 
															);
															
															$json ['total'] = '1';
														}
													}
													
													// var_dump($json['rulenotes']);
													
													/* Create Task */
													if ($onschedule_rules_module ['onschedule_action'] == '4') {
														
														$sqls23w = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $onschedule_rules_module ['task_random_id'] . "' and taskadded = '0' ";
														$query4w = $this->db->query ( $sqls23w );
														
														if ($query4w->num_rows == 0) {
															
															$addtaskw = array ();
															
															/*
															 * if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
															 * $snooze_time71 = 0;
															 * $thestime61 = $onschedule_rules_module['taskTime'];
															 * }else{
															 * $snooze_time71 = 10;
															 * $thestime61 = date('H:i:s');
															 * }
															 */
															
															$snooze_time71 = 1;
															$thestime61 = date ( 'H:i:s' );
															// var_dump($thestime6);
															
															$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
															
															$date = str_replace ( '-', '/', $onschedule_rules_module ['taskDate'] );
															$res = explode ( "/", $date );
															$taskDate = $res [1] . "-" . $res [0] . "-" . $res [2];
															
															$date2 = str_replace ( '-', '/', $onschedule_rules_module ['end_recurrence_date'] );
															$res2 = explode ( "/", $date2 );
															$end_recurrence_date = $res2 [1] . "-" . $res2 [0] . "-" . $res2 [2];
															
															$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $taskDate ) );
															$addtaskw ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $end_recurrence_date ) );
															$addtaskw ['recurrence'] = $onschedule_rules_module ['recurrence'];
															$addtaskw ['recurnce_week'] = $onschedule_rules_module ['recurnce_week'];
															$addtaskw ['recurnce_hrly'] = $onschedule_rules_module ['recurnce_hrly'];
															$addtaskw ['recurnce_month'] = $onschedule_rules_module ['recurnce_month'];
															$addtaskw ['recurnce_day'] = $onschedule_rules_module ['recurnce_day'];
															$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
															$addtaskw ['endtime'] = $stime8;
															
															$onschedule_description1112 = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
															
															$addtaskw ['description'] = $onschedule_rules_module ['description'] . ' ' . $onschedule_description1112;
															
															$addtaskw ['assignto'] = $onschedule_rules_module ['assign_to'];
															
															$addtaskw ['facilities_id'] = $facilities_id;
															$addtaskw ['task_form_id'] = $onschedule_rules_module ['task_form_id'];
															
															if ($onschedule_rules_module ['transport_tags'] != null && $onschedule_rules_module ['transport_tags'] != "") {
																$addtaskw ['transport_tags'] = explode ( ',', $onschedule_rules_module ['transport_tags'] );
															}
															
															$addtaskw ['pickup_facilities_id'] = $onschedule_rules_module ['pickup_facilities_id'];
															$addtaskw ['pickup_locations_address'] = $onschedule_rules_module ['pickup_locations_address'];
															$addtaskw ['pickup_locations_time'] = $onschedule_rules_module ['pickup_locations_time'];
															
															$addtaskw ['dropoff_facilities_id'] = $onschedule_rules_module ['dropoff_facilities_id'];
															$addtaskw ['dropoff_locations_address'] = $onschedule_rules_module ['dropoff_locations_address'];
															$addtaskw ['dropoff_locations_time'] = $onschedule_rules_module ['dropoff_locations_time'];
															
															$addtaskw ['tasktype'] = $onschedule_rules_module ['tasktype'];
															$addtaskw ['numChecklist'] = $onschedule_rules_module ['numChecklist'];
															$addtaskw ['task_alert'] = $onschedule_rules_module ['task_alert'];
															$addtaskw ['alert_type_sms'] = $onschedule_rules_module ['alert_type_sms'];
															$addtaskw ['alert_type_notification'] = $onschedule_rules_module ['alert_type_notification'];
															$addtaskw ['alert_type_email'] = $onschedule_rules_module ['alert_type_email'];
															$addtaskw ['rules_task'] = $onschedule_rules_module ['task_random_id'];
															
															$addtaskw ['recurnce_hrly_recurnce'] = $onschedule_rules_module ['recurnce_hrly_recurnce'];
															$addtaskw ['daily_endtime'] = $onschedule_rules_module ['daily_endtime'];
															
															if ($onschedule_rules_module ['daily_times'] != null && $onschedule_rules_module ['daily_times'] != "") {
																$addtaskw ['daily_times'] = explode ( ',', $onschedule_rules_module ['daily_times'] );
															}
															
															if ($onschedule_rules_module ['medication_tags'] != null && $onschedule_rules_module ['medication_tags'] != "") {
																$addtaskw ['medication_tags'] = explode ( ',', $onschedule_rules_module ['medication_tags'] );
																
																$aa = urldecode ( $onschedule_rules_module ['tags_medication_details_ids'] );
																$aa1 = unserialize ( $aa );
																
																$tags_medication_details_ids = array ();
																foreach ( $aa1 as $key => $mresult ) {
																	$tags_medication_details_ids [$key] = $mresult;
																}
																$addtaskw ['tags_medication_details_ids'] = $tags_medication_details_ids;
															}
															
															$addtaskw ['emp_tag_id'] = $onschedule_rules_module ['emp_tag_id'];
															
															$addtaskw ['recurnce_hrly_perpetual'] = $onschedule_rules_module ['recurnce_hrly_perpetual'];
															$addtaskw ['completion_alert'] = $onschedule_rules_module ['completion_alert'];
															$addtaskw ['completion_alert_type_sms'] = $onschedule_rules_module ['completion_alert_type_sms'];
															$addtaskw ['completion_alert_type_email'] = $onschedule_rules_module ['completion_alert_type_email'];
															
															if ($onschedule_rules_module ['user_roles'] != null && $onschedule_rules_module ['user_roles'] != "") {
																$addtaskw ['user_roles'] = explode ( ',', $onschedule_rules_module ['user_roles'] );
															}
															
															if ($onschedule_rules_module ['userids'] != null && $onschedule_rules_module ['userids'] != "") {
																$addtaskw ['userids'] = explode ( ',', $onschedule_rules_module ['userids'] );
															}
															$addtaskw ['task_status'] = $onschedule_rules_module ['task_status'];
															
															$addtaskw ['visitation_tag_id'] = $onschedule_rules_module ['visitation_tag_id'];
															
															if ($onschedule_rules_module ['visitation_tags'] != null && $onschedule_rules_module ['visitation_tags'] != "") {
																$addtaskw ['visitation_tags'] = explode ( ',', $onschedule_rules_module ['visitation_tags'] );
															}
															$addtaskw ['visitation_start_facilities_id'] = $onschedule_rules_module ['visitation_start_facilities_id'];
															$addtaskw ['visitation_start_address'] = $onschedule_rules_module ['visitation_start_address'];
															$addtaskw ['visitation_start_time'] = $onschedule_rules_module ['visitation_start_time'];
															$addtaskw ['visitation_appoitment_facilities_id'] = $onschedule_rules_module ['visitation_appoitment_facilities_id'];
															$addtaskw ['visitation_appoitment_address'] = $onschedule_rules_module ['visitation_appoitment_address'];
															$addtaskw ['visitation_appoitment_time'] = $onschedule_rules_module ['visitation_appoitment_time'];
															$addtaskw ['complete_endtime'] = $onschedule_rules_module ['complete_endtime'];
															
															if ($onschedule_rules_module ['completed_times'] != null && $onschedule_rules_module ['completed_times'] != "") {
																$addtaskw ['completed_times'] = explode ( ',', $onschedule_rules_module ['completed_times'] );
															}
															$addtaskw ['completed_alert'] = $onschedule_rules_module ['completed_alert'];
															$addtaskw ['completed_late_alert'] = $onschedule_rules_module ['completed_late_alert'];
															$addtaskw ['incomplete_alert'] = $onschedule_rules_module ['incomplete_alert'];
															$addtaskw ['deleted_alert'] = $onschedule_rules_module ['deleted_alert'];
															$addtaskw ['attachement_form'] = $onschedule_rules_module ['attachement_form'];
															$addtaskw ['tasktype_form_id'] = $onschedule_rules_module ['tasktype_form_id'];
															
															$addtaskw ['reminder_alert'] = $onschedule_rules_module ['reminder_alert'];
															if ($onschedule_rules_module ['reminderminus'] != null && $onschedule_rules_module ['reminderminus'] != "") {
																$addtaskw ['reminderminus'] = explode ( ',', $onschedule_rules_module ['reminderminus'] );
															}
															
															if ($onschedule_rules_module ['reminderplus'] != null && $onschedule_rules_module ['reminderplus'] != "") {
																$addtaskw ['reminderplus'] = explode ( ',', $onschedule_rules_module ['reminderplus'] );
															}
															$addtaskw ['assign_to_type'] = $onschedule_rules_module ['assign_to_type'];
															if ($onschedule_rules_module ['user_assign_to'] != null && $onschedule_rules_module ['user_assign_to'] != "") {
																$addtaskw ['assign_to'] = explode ( ',', $onschedule_rules_module ['user_assign_to'] );
															}
															if ($onschedule_rules_module ['user_role_assign_ids'] != null && $onschedule_rules_module ['user_role_assign_ids'] != "") {
																$addtaskw ['user_role_assign_ids'] = explode ( ',', $onschedule_rules_module ['user_role_assign_ids'] );
															}
															
															$this->load->model ( 'createtask/createtask' );
															$this->model_createtask_createtask->addcreatetask ( $addtaskw, $facilities_id );
														}
													}
												}
											}
										}
										
										if ($rule ['rules_operation_recurrence'] == '3') {
											$onschedule_description = nl2br ( $onschedule_rules_module ['onschedule_description'] );
											$date = str_replace ( '-', '/', $searchdate );
											$res = explode ( "/", $date );
											$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1] . ' ' . date ( 'H:i:s' );
											
											$snooze_time71 = 1;
											$thestime61 = date ( 'H:i:s' );
											$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
											
											// var_dump($changedDate);
											$dailytime = date ( 'H:i' );
											
											// var_dump($dailytime);
											
											$rules_operation_time = date ( 'H:i', strtotime ( $rule ['rules_operation_time'] ) );
											// var_dump($rules_operation_time);
											
											$currentdate = date ( 'd-m-Y' );
											
											$recurnce_day = $rule ['recurnce_day'];
											$currentMonth = date ( 'm' );
											$currentYear = date ( 'Y' );
											$recurnce_day_date = $recurnce_day . "-" . $currentMonth . "-" . $currentYear;
											
											if ($currentdate == $recurnce_day_date) {
												// var_dump($recurnce_day);
												// echo "<hr>";
												if ($dailytime == $rules_operation_time) {
													
													/* sms */
													if ($onschedule_rules_module ['onschedule_action'] == '1') {
														
														if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
															
															$user_roles1 = $onschedule_rules_module ['ouser_roles'];
															
															foreach ( $user_roles1 as $user_role ) {
																$urole = array ();
																$urole ['user_group_id'] = $user_role;
																$tusers = $this->model_user_user->getUsers ( $urole );
																
																if ($tusers) {
																	foreach ( $tusers as $tuser ) {
																		if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
																			$number = $tuser ['phone_number'];
																			
																			$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																			
																			$sdata = array ();
																			$sdata ['message'] = $message;
																			$sdata ['phone_number'] = $tuser ['phone_number'];
																			$sdata ['facilities_id'] = $facilities_id;
																			$response = $this->model_api_smsapi->sendsms ( $sdata );
																		}
																	}
																}
															}
														}
														
														if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
															$userids1 = $onschedule_rules_module ['ouserids'];
															
															foreach ( $userids1 as $userid ) {
																$user_info = $this->model_user_user->getUserbyupdate ( $userid );
																if ($user_info) {
																	if ($user_info ['phone_number'] != 0) {
																		$number = $user_info ['phone_number'];
																		
																		$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
																		
																		$sdata = array ();
																		$sdata ['message'] = $message;
																		$sdata ['phone_number'] = $user_info ['phone_number'];
																		$sdata ['facilities_id'] = $facilities_id;
																		$response = $this->model_api_smsapi->sendsms ( $sdata );
																	}
																}
															}
														}
														
														if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
															$number = '19045832155';
															
															$message = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
															
															$sdata = array ();
															$sdata ['message'] = $message;
															$sdata ['phone_number'] = '19045832155';
															$sdata ['facilities_id'] = $facilities_id;
															$response = $this->model_api_smsapi->sendsms ( $sdata );
														}
														
														// $response = $client->account->sms_messages->create($from,$number,$text);
													}
													
													/* Email */
													if ($onschedule_rules_module ['onschedule_action'] == '2') {
														
														$onschedule_description511n2r5n2e = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
														
														$resultm = array ();
														$resultm ['notes_id'] = '';
														$resultm ['highlighter_value'] = '';
														$resultm ['notes_description'] = $onschedule_description511n2r5n2e;
														$resultm ['date_added'] = date ( 'j, F Y', strtotime ( $changedDate ) );
														$resultm ['note_date'] = date ( 'j, F Y', strtotime ( $changedDate ) );
														$resultm ['notetime'] = date ( 'h:i A', strtotime ( $taskTime ) );
														$resultm ['username'] = $result ['user_id'];
														$resultm ['email'] = $user_info ['email'];
														$resultm ['phone_number'] = $user_info ['phone_number'];
														$resultm ['sms_number'] = $facility ['sms_number'];
														$resultm ['facility'] = $facility ['facility'];
														$resultm ['address'] = $facility ['address'];
														$resultm ['location'] = $facility ['location'];
														$resultm ['zipcode'] = $facility ['zipcode'];
														$resultm ['contry_name'] = $country_info ['name'];
														$resultm ['zone_name'] = $zone_info ['name'];
														$resultm ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
														
														$message33 = "";
														
														$rulevalue = date ( 'h:i A', strtotime ( $rule ['rules_operation_time'] ) );
														$message33 .= $this->emailtemplate ( $resultd, $rule ['rules_name'], 'Month', $rulevalue );
														
														$useremailids = array ();
														if ($onschedule_rules_module ['ouser_roles'] != null && $onschedule_rules_module ['ouser_roles'] != "") {
															
															$user_roles1 = $onschedule_rules_module ['ouser_roles'];
															
															foreach ( $user_roles1 as $user_role ) {
																$urole = array ();
																$urole ['user_group_id'] = $user_role;
																$tusers = $this->model_user_user->getUsers ( $urole );
																
																if ($tusers) {
																	foreach ( $tusers as $tuser ) {
																		if ($tuser ['email'] != null && $tuser ['email'] != "") {
																			$useremailids [] = $tuser ['email'];
																		}
																	}
																}
															}
														}
														
														if ($onschedule_rules_module ['ouserids'] != null && $onschedule_rules_module ['ouserids'] != "") {
															$userids1 = $onschedule_rules_module ['ouserids'];
															
															foreach ( $userids1 as $userid ) {
																$user_info = $this->model_user_user->getUserbyupdate ( $userid );
																if ($user_info) {
																	if ($user_info ['email']) {
																		$useremailids [] = $user_info ['email'];
																	}
																}
															}
														}
														
														if (($onschedule_rules_module ['ouserids'] == null && $onschedule_rules_module ['ouserids'] == "") && ($onschedule_rules_module ['ouser_roles'] == null && $onschedule_rules_module ['ouser_roles'] == "")) {
															$user_email = 'app-monitoring@noteactive.com';
														}
														
														$edata = array ();
														$edata ['message'] = $message33;
														$edata ['subject'] = 'This is an Automated Alert Email.';
														$edata ['useremailids'] = $useremailids;
														$edata ['user_email'] = $user_email;
														
														$email_status = $this->model_api_emailapi->sendmail ( $edata );
													}
													
													/* Notification */
													if ($onschedule_rules_module ['onschedule_action'] == '3') {
														
														$onschedule_descriptiodn511n2r5n2e = substr ( $onschedule_description, 0, 350 ) . ((strlen ( $onschedule_description ) > 350) ? '..' : '');
														
														if ($rule ['snooze_dismiss'] != '2') {
															$json ['rulenotes'] [] = array (
																	'notes_id' => '',
																	'rules_id' => $rule ['rules_id'],
																	'facilities_info' => $facilities_info,
																	'highlighter_value' => '',
																	'notes_description' => $onschedule_descriptiodn511n2r5n2e,
																	'date_added' => date ( 'j, F Y', strtotime ( $changedDate ) ),
																	'note_date' => date ( 'j, F Y h:i A', strtotime ( $changedDate ) ),
																	'notetime' => date ( 'h:i A', strtotime ( $taskTime ) ),
																	'username' => '',
																	'email' => '',
																	'facility' => '' 
															);
															
															$json ['total'] = '1';
														}
													}
													
													// var_dump($json['rulenotes']);
													
													/* Create Task */
													if ($onschedule_rules_module ['onschedule_action'] == '4') {
														
														$sqls23m = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $onschedule_rules_module ['task_random_id'] . "' and taskadded = '0' ";
														$query4m = $this->db->query ( $sqls23m );
														
														if ($query4m->num_rows == 0) {
															$addtaskm = array ();
															
															/*
															 * if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
															 * $snooze_time71 = 0;
															 * $thestime61 = $onschedule_rules_module['taskTime'];
															 * }else{
															 * $snooze_time71 = 10;
															 * $thestime61 = date('H:i:s');
															 * }
															 */
															$snooze_time71 = 1;
															$thestime61 = date ( 'H:i:s' );
															// var_dump($thestime6);
															
															$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
															
															$date = str_replace ( '-', '/', $onschedule_rules_module ['taskDate'] );
															$res = explode ( "/", $date );
															$taskDate = $res [1] . "-" . $res [0] . "-" . $res [2];
															
															$date2 = str_replace ( '-', '/', $onschedule_rules_module ['end_recurrence_date'] );
															$res2 = explode ( "/", $date2 );
															$end_recurrence_date = $res2 [1] . "-" . $res2 [0] . "-" . $res2 [2];
															
															$addtaskm ['taskDate'] = date ( 'm-d-Y', strtotime ( $taskDate ) );
															$addtaskm ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $end_recurrence_date ) );
															$addtaskm ['recurrence'] = $onschedule_rules_module ['recurrence'];
															$addtaskm ['recurnce_week'] = $onschedule_rules_module ['recurnce_week'];
															$addtaskm ['recurnce_hrly'] = $onschedule_rules_module ['recurnce_hrly'];
															$addtaskm ['recurnce_month'] = $onschedule_rules_module ['recurnce_month'];
															$addtaskm ['recurnce_day'] = $onschedule_rules_module ['recurnce_day'];
															$addtaskm ['taskTime'] = $taskTime; // date('H:i:s');
															$addtaskm ['endtime'] = $stime8;
															
															$onschedule_description511252 = substr ( $onschedule_description, 0, 150 ) . ((strlen ( $onschedule_description ) > 150) ? '..' : '');
															
															$addtaskm ['description'] = $onschedule_rules_module ['description'] . ' ' . $onschedule_description511252;
															
															$addtaskm ['assignto'] = $onschedule_rules_module ['assign_to'];
															
															$addtaskm ['facilities_id'] = $facilities_id;
															$addtaskm ['task_form_id'] = $onschedule_rules_module ['task_form_id'];
															
															if ($onschedule_rules_module ['transport_tags'] != null && $onschedule_rules_module ['transport_tags'] != "") {
																$addtaskm ['transport_tags'] = explode ( ',', $onschedule_rules_module ['transport_tags'] );
															}
															
															$addtaskm ['pickup_facilities_id'] = $onschedule_rules_module ['pickup_facilities_id'];
															$addtaskm ['pickup_locations_address'] = $onschedule_rules_module ['pickup_locations_address'];
															$addtaskm ['pickup_locations_time'] = $onschedule_rules_module ['pickup_locations_time'];
															
															$addtaskm ['dropoff_facilities_id'] = $onschedule_rules_module ['dropoff_facilities_id'];
															$addtaskm ['dropoff_locations_address'] = $onschedule_rules_module ['dropoff_locations_address'];
															$addtaskm ['dropoff_locations_time'] = $onschedule_rules_module ['dropoff_locations_time'];
															
															$addtaskm ['tasktype'] = $onschedule_rules_module ['tasktype'];
															$addtaskm ['numChecklist'] = $onschedule_rules_module ['numChecklist'];
															$addtaskm ['task_alert'] = $onschedule_rules_module ['task_alert'];
															$addtaskm ['alert_type_sms'] = $onschedule_rules_module ['alert_type_sms'];
															$addtaskm ['alert_type_notification'] = $onschedule_rules_module ['alert_type_notification'];
															$addtaskm ['alert_type_email'] = $onschedule_rules_module ['alert_type_email'];
															$addtaskm ['rules_task'] = $onschedule_rules_module ['task_random_id'];
															
															$addtaskm ['recurnce_hrly_recurnce'] = $onschedule_rules_module ['recurnce_hrly_recurnce'];
															$addtaskm ['daily_endtime'] = $onschedule_rules_module ['daily_endtime'];
															
															if ($onschedule_rules_module ['daily_times'] != null && $onschedule_rules_module ['daily_times'] != "") {
																$addtaskm ['daily_times'] = explode ( ',', $onschedule_rules_module ['daily_times'] );
															}
															
															if ($onschedule_rules_module ['medication_tags'] != null && $onschedule_rules_module ['medication_tags'] != "") {
																$addtaskm ['medication_tags'] = explode ( ',', $onschedule_rules_module ['medication_tags'] );
																
																$aa = urldecode ( $onschedule_rules_module ['tags_medication_details_ids'] );
																$aa1 = unserialize ( $aa );
																
																$tags_medication_details_ids = array ();
																foreach ( $aa1 as $key => $mresult ) {
																	$tags_medication_details_ids [$key] = $mresult;
																}
																$addtaskm ['tags_medication_details_ids'] = $tags_medication_details_ids;
															}
															
															$addtaskm ['emp_tag_id'] = $onschedule_rules_module ['emp_tag_id'];
															
															$addtaskm ['recurnce_hrly_perpetual'] = $onschedule_rules_module ['recurnce_hrly_perpetual'];
															$addtaskm ['completion_alert'] = $onschedule_rules_module ['completion_alert'];
															$addtaskm ['completion_alert_type_sms'] = $onschedule_rules_module ['completion_alert_type_sms'];
															$addtaskm ['completion_alert_type_email'] = $onschedule_rules_module ['completion_alert_type_email'];
															
															if ($onschedule_rules_module ['user_roles'] != null && $onschedule_rules_module ['user_roles'] != "") {
																$addtaskm ['user_roles'] = explode ( ',', $onschedule_rules_module ['user_roles'] );
															}
															
															if ($onschedule_rules_module ['userids'] != null && $onschedule_rules_module ['userids'] != "") {
																$addtaskm ['userids'] = explode ( ',', $onschedule_rules_module ['userids'] );
															}
															$addtaskm ['task_status'] = $onschedule_rules_module ['task_status'];
															
															$addtaskm ['visitation_tag_id'] = $onschedule_rules_module ['visitation_tag_id'];
															
															if ($onschedule_rules_module ['visitation_tags'] != null && $onschedule_rules_module ['visitation_tags'] != "") {
																$addtaskm ['visitation_tags'] = explode ( ',', $onschedule_rules_module ['visitation_tags'] );
															}
															$addtaskm ['visitation_start_facilities_id'] = $onschedule_rules_module ['visitation_start_facilities_id'];
															$addtaskm ['visitation_start_address'] = $onschedule_rules_module ['visitation_start_address'];
															$addtaskm ['visitation_start_time'] = $onschedule_rules_module ['visitation_start_time'];
															$addtaskm ['visitation_appoitment_facilities_id'] = $onschedule_rules_module ['visitation_appoitment_facilities_id'];
															$addtaskm ['visitation_appoitment_address'] = $onschedule_rules_module ['visitation_appoitment_address'];
															$addtaskm ['visitation_appoitment_time'] = $onschedule_rules_module ['visitation_appoitment_time'];
															$addtaskm ['complete_endtime'] = $onschedule_rules_module ['complete_endtime'];
															
															if ($onschedule_rules_module ['completed_times'] != null && $onschedule_rules_module ['completed_times'] != "") {
																$addtaskm ['completed_times'] = explode ( ',', $onschedule_rules_module ['completed_times'] );
															}
															$addtaskm ['completed_alert'] = $onschedule_rules_module ['completed_alert'];
															$addtaskm ['completed_late_alert'] = $onschedule_rules_module ['completed_late_alert'];
															$addtaskm ['incomplete_alert'] = $onschedule_rules_module ['incomplete_alert'];
															$addtaskm ['deleted_alert'] = $onschedule_rules_module ['deleted_alert'];
															$addtaskm ['attachement_form'] = $onschedule_rules_module ['attachement_form'];
															$addtaskm ['tasktype_form_id'] = $onschedule_rules_module ['tasktype_form_id'];
															
															$addtaskm ['reminder_alert'] = $onschedule_rules_module ['reminder_alert'];
															if ($onschedule_rules_module ['reminderminus'] != null && $onschedule_rules_module ['reminderminus'] != "") {
																$addtaskm ['reminderminus'] = explode ( ',', $onschedule_rules_module ['reminderminus'] );
															}
															
															if ($onschedule_rules_module ['reminderplus'] != null && $onschedule_rules_module ['reminderplus'] != "") {
																$addtaskm ['reminderplus'] = explode ( ',', $onschedule_rules_module ['reminderplus'] );
															}
															$addtaskm ['assign_to_type'] = $onschedule_rules_module ['assign_to_type'];
															if ($onschedule_rules_module ['user_assign_to'] != null && $onschedule_rules_module ['user_assign_to'] != "") {
																$addtaskm ['assign_to'] = explode ( ',', $onschedule_rules_module ['user_assign_to'] );
															}
															if ($onschedule_rules_module ['user_role_assign_ids'] != null && $onschedule_rules_module ['user_role_assign_ids'] != "") {
																$addtaskm ['user_role_assign_ids'] = explode ( ',', $onschedule_rules_module ['user_role_assign_ids'] );
															}
															
															$this->load->model ( 'createtask/createtask' );
															$this->model_createtask_createtask->addcreatetask ( $addtaskm, $facilities_id );
														}
													}
												}
											}
										}
									}
								}
							}
							
							if ($config_rules_status == '1') {
								if ($rule ['rules_operation'] == '1') {
									$andrulesValues = array ();
									$andrulesTaskValues = array ();
									$andrulesActionValues = array ();
									$andrulesActionValues2 = array ();
									foreach ( $rule ['rules_module'] as $rules_module ) {
										// $rowModule = array();
										// var_dump($rules_module);
										// echo "<hr>";
										
										if ($rule ['rules_operator'] == '1') {
											
											if ($rules_module ['highlighter_id'] != null && $rules_module ['highlighter_id'] != "") {
												$andrulesValues ['highlighter_id'] = $rules_module ['highlighter_id'];
											}
											
											if ($rules_module ['keyword_id'] != null && $rules_module ['keyword_id'] != "") {
												$querya = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . $rules_module ['keyword_id'] . "'" );
												
												$active_tagdata = $querya->row;
												$andrulesValues ['keyword_image'] = $active_tagdata ['keyword_image'];
											}
											
											if ($rules_module ['color_id'] != null && $rules_module ['color_id'] != "") {
												$andrulesValues ['color_id'] = $rules_module ['color_id'];
											}
											
											if ($rules_module ['keyword_search'] != null && $rules_module ['keyword_search'] != "") {
												$andrulesValues ['keyword_search'] = $rules_module ['keyword_search'];
											}
										}
										
										if ($rule ['rules_operator'] == '2') {
											
											if ($rules_module ['rules_type'] == '1') {
												
												if ($rules_module ['highlighter_id'] != null && $rules_module ['highlighter_id'] != "") {
													$sql = "SELECT  notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email  FROM `" . DB_PREFIX . "notes`";
													
													$sql .= 'where 1 = 1 ';
													
													$sql .= " and highlighter_id = '" . $rules_module ['highlighter_id'] . "'";
													// $sql .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													
													if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
														$ddss [] = $facility ['task_facilities_ids'];
														$ddss [] = $facilities_id;
														$sssssdd = implode ( ",", $ddss );
														$faculities_ids = $sssssdd;
														$sql .= " and facilities_id in  (" . $faculities_ids . ") ";
													} else {
														$sql .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													}
													$sql .= " and `snooze_dismiss` != '2' ";
													
													$date = str_replace ( '-', '/', $searchdate );
													$res = explode ( "/", $date );
													$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
													
													$startDate = $changedDate;
													$endDate = $changedDate;
													
													$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
													
													$sql .= " and status = '1' ORDER BY notetime DESC  ";
													
													// echo $sql;
													// echo "<hr>";
													
													$query = $this->db->query ( $sql );
													// var_dump($query->num_rows);
													// echo "<hr>";
													if ($query->num_rows) {
														// var_dump($query->rows);
														// echo "<hr>";
														foreach ( $query->rows as $result ) {
															$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
															
															$allnotesIds [] = array (
																	'notes_id' => $result ['notes_id'],
																	'rules_type' => 'Highlighter',
																	'rules_value' => $highlighterData ['highlighter_name'] 
															);
														}
													}
												}
											}
											
											// var_dump($json);
											
											if ($rules_module ['rules_type'] == '2') {
												
												if ($rules_module ['keyword_id'] != null && $rules_module ['keyword_id'] != "") {
													$querya = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . $rules_module ['keyword_id'] . "'" );
													
													$active_tagdata1 = $querya->row;
													
													// $sql2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
													
													$sql2 = "SELECT n.* FROM `" . DB_PREFIX . "notes` n ";
													
													$sql2 .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
													
													$sql2 .= 'where 1 = 1 ';
													
													$sql2 .= " and nk.keyword_file = '" . $active_tagdata1 ['keyword_image'] . "'";
													
													if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
														$ddss [] = $facility ['task_facilities_ids'];
														$ddss [] = $facilities_id;
														$sssssdd = implode ( ",", $ddss );
														$faculities_ids = $sssssdd;
														$sql2 .= " and n.facilities_id in  (" . $faculities_ids . ") ";
													} else {
														$sql2 .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
													}
													// $sql2 .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
													
													$sql2 .= " and n.snooze_dismiss != '2' ";
													
													$date = str_replace ( '-', '/', $searchdate );
													$res = explode ( "/", $date );
													$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
													
													$startDate = $changedDate;
													$endDate = $changedDate;
													
													$sql2 .= " and (n.`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
													
													$sql2 .= " and n.status = '1' ORDER BY n.notetime DESC  ";
													
													// echo $sql2;
													// echo "<hr>";
													
													$query = $this->db->query ( $sql2 );
													// var_dump($query->rows);
													// echo "<hr>";
													if ($query->num_rows) {
														
														foreach ( $query->rows as $result ) {
															$allnotesIds [] = array (
																	'notes_id' => $result ['notes_id'],
																	'rules_type' => 'ActiveNote',
																	'rules_value' => $rules_module ['keyword_id'] 
															);
														}
													}
												}
											}
											
											if ($rules_module ['rules_type'] == '3') {
												// var_dump($rules_module['color_id']);
												if ($rules_module ['color_id'] != null && $rules_module ['color_id'] != "") {
													$sql3 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
													
													$sql3 .= 'where 1 = 1 ';
													
													$sql3 .= " and text_color = '#" . $rules_module ['color_id'] . "'";
													if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
														$ddss [] = $facility ['task_facilities_ids'];
														$ddss [] = $facilities_id;
														$sssssdd = implode ( ",", $ddss );
														$faculities_ids = $sssssdd;
														$sql3 .= " and facilities_id in  (" . $faculities_ids . ") ";
													} else {
														$sql3 .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													}
													
													// $sql3 .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													$sql3 .= " and `snooze_dismiss` != '2' ";
													
													$date = str_replace ( '-', '/', $searchdate );
													$res = explode ( "/", $date );
													$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
													
													$startDate = $changedDate;
													$endDate = $changedDate;
													
													$sql3 .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
													
													$sql3 .= " and status = '1' ORDER BY notetime DESC  ";
													
													// echo $sql3;
													// echo "<hr>";
													
													$query = $this->db->query ( $sql3 );
													
													if ($query->num_rows) {
														// var_dump($query->rows);
														// echo "<hr>";
														
														foreach ( $query->rows as $result ) {
															
															if ($rules_module ['color_id'] == '008000') {
																$color_id = "Green";
															}
															if ($rules_module ['color_id'] == 'FF0000') {
																$color_id = "Red";
															}
															if ($rules_module ['color_id'] == '0000FF') {
																$color_id = "Blue";
															}
															if ($rules_module ['color_id'] == '000000') {
																$color_id = "Black";
															}
															$allnotesIds [] = array (
																	'notes_id' => $result ['notes_id'],
																	'rules_type' => 'Color',
																	'rules_value' => $color_id 
															);
														}
													}
												}
											}
											
											if ($rules_module ['rules_type'] == '5') {
												// var_dump($rules_module['keyword_search']);
												if ($rules_module ['keyword_search'] != null && $rules_module ['keyword_search'] != "") {
													$sqls = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
													
													$sqls .= 'where 1 = 1 ';
													
													$sqls .= " and LOWER(notes_description) like '%" . strtolower ( $rules_module ['keyword_search'] ) . "%'";
													
													if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
														$ddss [] = $facility ['task_facilities_ids'];
														$ddss [] = $facilities_id;
														$sssssdd = implode ( ",", $ddss );
														$faculities_ids = $sssssdd;
														$sqls .= " and facilities_id in  (" . $faculities_ids . ") ";
													} else {
														$sqls .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													}
													// $sqls .= " and facilities_id = '" . $facility ['facilities_id'] . "'";
													$sqls .= " and `snooze_dismiss` != '2' ";
													
													$date = str_replace ( '-', '/', $searchdate );
													$res = explode ( "/", $date );
													$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
													
													$startDate = $changedDate;
													$endDate = $changedDate;
													
													$sqls .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
													
													$sqls .= " and status = '1' ORDER BY notetime DESC  ";
													
													// echo $sqls;
													// echo "<hr>";
													
													$query = $this->db->query ( $sqls );
													
													if ($query->num_rows) {
														// var_dump($query->rows);
														// echo "<hr>";
														
														foreach ( $query->rows as $result ) {
															$allnotesIds [] = array (
																	'notes_id' => $result ['notes_id'],
																	'rules_type' => 'Keyword',
																	'rules_value' => $rules_module ['keyword_search'] 
															);
														}
													}
												}
											}
										}
									}
								}
							}
							
							/* end trigger loop */
							
							if (! empty ( $andrulesValues )) {
								$sql = "SELECT n.* FROM `" . DB_PREFIX . "notes` n ";
								
								if ($andrulesValues ['keyword_image'] != null && $andrulesValues ['keyword_image'] != "") {
									$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
								}
								
								$sql .= 'where 1 = 1 ';
								
								if ($andrulesValues ['highlighter_id'] != null && $andrulesValues ['highlighter_id'] != "") {
									$sql .= " and n.highlighter_id = '" . $andrulesValues ['highlighter_id'] . "'";
								}
								
								if ($andrulesValues ['keyword_image'] != null && $andrulesValues ['keyword_image'] != "") {
									
									$sql .= " and nk.keyword_file = '" . $andrulesValues ['keyword_image'] . "'";
								}
								
								if ($andrulesValues ['color_id'] != null && $andrulesValues ['color_id'] != "") {
									
									$sql .= " and n.text_color = '#" . $andrulesValues ['color_id'] . "'";
								}
								
								if ($andrulesValues ['keyword_search'] != null && $andrulesValues ['keyword_search'] != "") {
									$sql .= " and LOWER(n.notes_description) like '%" . strtolower ( $andrulesValues ['keyword_search'] ) . "%'";
								}
								if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
									$ddss [] = $facility ['task_facilities_ids'];
									$ddss [] = $facilities_id;
									$sssssdd = implode ( ",", $ddss );
									$faculities_ids = $sssssdd;
									$sql .= " and n.facilities_id in  (" . $faculities_ids . ") ";
								} else {
									$sql .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
								}
								
								// $sql .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
								$sql .= " and n.`snooze_dismiss` != '2' ";
								
								$date = str_replace ( '-', '/', $searchdate );
								$res = explode ( "/", $date );
								$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
								
								$startDate = $changedDate;
								$endDate = $changedDate;
								
								$sql .= " and (n.`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
								
								$sql .= " and n.status = '1' ORDER BY n.notetime DESC  ";
								
								// echo "<hr>";
								// echo $sql;
								// echo "<hr>";
								
								$query = $this->db->query ( $sql );
								// var_dump($query->num_rows);
								
								// die;
								// echo "<hr>";
								if ($query->num_rows) {
									// var_dump($query->rows);
									// echo "<hr>";
									
									foreach ( $query->rows as $result ) {
										// $user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										$user_info = $this->model_user_user->getUserByUsernamebynotes ( $result ['user_id'], $result ['facilities_id'] );
										
										if ($andrulesValues ['highlighter_id'] != null && $andrulesValues ['highlighter_id'] != "") {
											$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
										}
										
										$nrulesvalue = "";
										
										if ($andrulesValues ['highlighter_id'] != null && $andrulesValues ['highlighter_id'] != "") {
											$nrulesvalue .= 'Highlighter: ' . $highlighterData ['highlighter_name'] . ' and ';
										}
										
										if ($andrulesValues ['keyword_image'] != null && $andrulesValues ['keyword_image'] != "") {
											
											$querya = $this->db->query ( "SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues ['keyword_image'] . "'" );
											
											$active_tagdata = $querya->row;
											$nrulesvalue .= ' ActiveNote: ' . $active_tagdata ['keyword_name'] . ' and ';
										}
										
										if ($andrulesValues ['color_id'] != null && $andrulesValues ['color_id'] != "") {
											
											if ($andrulesValues ['color_id'] == '008000') {
												$color_id = "Green";
											}
											if ($andrulesValues ['color_id'] == 'FF0000') {
												$color_id = "Red";
											}
											if ($andrulesValues ['color_id'] == '0000FF') {
												$color_id = "Blue";
											}
											if ($andrulesValues ['color_id'] == '000000') {
												$color_id = "Black";
											}
											
											$nrulesvalue .= ' Color: ' . $color_id . ' and ';
										}
										
										if ($andrulesValues ['keyword_search'] != null && $andrulesValues ['keyword_search'] != "") {
											$nrulesvalue .= ' Keyword: ' . $andrulesValues ['keyword_search'] . ' ';
										}
										
										$allnotesIds [] = array (
												'notes_id' => $result ['notes_id'],
												'rules_type' => '',
												'rules_value' => $nrulesvalue 
										);
									}
								}
							}
							
							// var_dump($allnotesIds);
							// var_dump($rule['rules_name']);
							// var_dump($rule['rule_action_content']);
							// echo "<hr>";
							if ($allnotesIds != null && $allnotesIds != "") {
								if (in_array ( '3', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										$notesIds [] = $allnotesId ['notes_id'];
									}
								}
								
								if (in_array ( '1', $rule ['rule_action'] )) {
									
									foreach ( $allnotesIds as $allnotesId ) {
										
										$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
										$sqls2 .= 'where 1 = 1 ';
										$sqls2 .= " and notes_id = '" . $allnotesId ['notes_id'] . "'";
										$sqls2 .= " and send_sms = '0'";
										
										$query = $this->db->query ( $sqls2 );
										
										$note_info = $query->row;
										
										if ($query->num_rows) {
											$message = "Rules Created \n";
											$message .= date ( 'h:i A', strtotime ( $note_info ['notetime'] ) ) . "\n";
											$message .= $rule ['rules_name'] . '-' . $allnotesId ['rules_type'] . '-' . $allnotesId ['rules_value'] . "\n";
											$message .= substr ( $note_info ['notes_description'], 0, 150 ) . ((strlen ( $note_info ['notes_description'] ) > 150) ? '..' : '');
											
											// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
											$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
											
											if ($user_info ['phone_number'] != null && $user_info ['phone_number'] != '0') {
												$phone_number = $user_info ['phone_number'];
											}
											
											$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
											$query = $this->db->query ( $sql3e );
											
											$sdata = array ();
											$sdata ['message'] = $message;
											$sdata ['phone_number'] = $phone_number;
											$sdata ['facilities_id'] = $facilities_id;
											$response = $this->model_api_smsapi->sendsms ( $sdata );
											
											if ($rule ['rule_action_content'] ['auser_roles'] != null && $rule ['rule_action_content'] ['auser_roles'] != "") {
												
												$user_roles1 = $rule ['rule_action_content'] ['auser_roles'];
												
												foreach ( $user_roles1 as $user_role ) {
													$urole = array ();
													$urole ['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers ( $urole );
													
													if ($tusers) {
														foreach ( $tusers as $tuser ) {
															if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
																$number = $tuser ['phone_number'];
																
																$sdata = array ();
																$sdata ['message'] = $message;
																$sdata ['phone_number'] = $tuser ['phone_number'];
																$sdata ['facilities_id'] = $facilities_id;
																$response = $this->model_api_smsapi->sendsms ( $sdata );
															}
														}
													}
												}
											}
											
											if ($rule ['rule_action_content'] ['auserids'] != null && $rule ['rule_action_content'] ['auserids'] != "") {
												$userids1 = $rule ['rule_action_content'] ['auserids'];
												
												foreach ( $userids1 as $userid ) {
													$user_info = $this->model_user_user->getUserbyupdate ( $userid );
													if ($user_info) {
														if ($user_info ['phone_number'] != 0) {
															$number = $user_info ['phone_number'];
															
															$sdata = array ();
															$sdata ['message'] = $message;
															$sdata ['phone_number'] = $user_info ['phone_number'];
															$sdata ['facilities_id'] = $facilities_id;
															$response = $this->model_api_smsapi->sendsms ( $sdata );
														}
													}
												}
											}
										}
									}
								}
								
								if (in_array ( '2', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										
										$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
										$sqls2 .= 'where 1 = 1 ';
										$sqls2 .= " and notes_id = '" . $allnotesId ['notes_id'] . "'";
										$sqls2 .= " and send_email = '0'";
										
										$query = $this->db->query ( $sqls2 );
										
										$note_info = $query->row;
										
										if ($query->num_rows) {
											
											// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
											$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
											
											$facilityDetails ['username'] = $result ['user_id'];
											$facilityDetails ['email'] = $user_info ['email'];
											$facilityDetails ['phone_number'] = $user_info ['phone_number'];
											$facilityDetails ['sms_number'] = $facility ['sms_number'];
											$facilityDetails ['facility'] = $facility ['facility'];
											$facilityDetails ['address'] = $facility ['address'];
											$facilityDetails ['location'] = $facility ['location'];
											$facilityDetails ['zipcode'] = $facility ['zipcode'];
											$facilityDetails ['contry_name'] = $country_info ['name'];
											$facilityDetails ['zone_name'] = $zone_info ['name'];
											$facilityDetails ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
											$facilityDetails ['rules_name'] = $rule ['rules_name'];
											$facilityDetails ['rules_type'] = $allnotesId ['rules_type'];
											$facilityDetails ['rules_value'] = $allnotesId ['rules_value'];
											
											$message33 = "";
											
											$message33 .= $this->sendEmailtemplate ( $note_info, $rule ['rules_name'], $allnotesId ['rules_type'], $allnotesId ['rules_value'], $facilityDetails );
											
											$useremailids = array ();
											
											$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
											$query = $this->db->query ( $sql3e );
											
											if ($rule ['rule_action_content'] ['auser_roles'] != null && $rule ['rule_action_content'] ['auser_roles'] != "") {
												
												$user_roles1 = $rule ['rule_action_content'] ['auser_roles'];
												
												foreach ( $user_roles1 as $user_role ) {
													$urole = array ();
													$urole ['user_group_id'] = $user_role;
													$tusers = $this->model_user_user->getUsers ( $urole );
													
													if ($tusers) {
														foreach ( $tusers as $tuser ) {
															if ($tuser ['email'] != null && $tuser ['email'] != "") {
																
																$useremailids [] = $tuser ['email'];
															}
														}
													}
												}
											}
											
											if ($rule ['rule_action_content'] ['auserids'] != null && $rule ['rule_action_content'] ['auserids'] != "") {
												$userids1 = $rule ['rule_action_content'] ['auserids'];
												
												foreach ( $userids1 as $userid ) {
													$user_info = $this->model_user_user->getUserbyupdate ( $userid );
													if ($user_info) {
														if ($user_info ['email']) {
															$useremailids [] = $user_info ['email'];
														}
													}
												}
											}
											
											if ($user_info ['email'] != null && $user_info ['email'] != "") {
												$user_email = $user_info ['email'];
											}
											
											$edata = array ();
											$edata ['message'] = $message33;
											$edata ['subject'] = 'This is an Automated Alert Email.';
											$edata ['useremailids'] = $useremailids;
											$edata ['user_email'] = $user_email;
											
											$email_status = $this->model_api_emailapi->sendmail ( $edata );
										}
									}
								}
								
								if (in_array ( '4', $rule ['rule_action'] ) && $rule ['rules_id'] != '') {
									
									// echo '<pre>'; print_r($rule['rule_action']); echo '</pre>';
									
									foreach ( $allnotesIds as $allnotesId ) {
										
										$tnotesIds [] = $allnotesId ['notes_id'];
										
										$this->load->model ( 'createtask/createtask' );
										$this->load->model ( 'user/user' );
										// echo 'createtask/createtask-'.
										
										$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (" . implode ( ',', $tnotesIds ) . ") and status = '1' and text_color_cut = '0' and `snooze_dismiss` != '2' ";
										
										$query2 = $this->db->query ( $sqlst2 );
										
										$thestime6 = date ( 'H:i:s' );
										// var_dump($thestime6);
										$snooze_time7 = 60;
										$stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
										// var_dump($stime8);
										
										// echo '<pre>BBBBBB-'; print_r($query2->rows); echo '</pre>';
										
										foreach ( $query2->rows as $tresult ) {
											
											foreach ( $rule ['rule_action'] as $key => $val ) {
												
												if ($rule ['rule_action_content'] [$key] ['task_random_id'] != null && $rule ['rule_action_content'] [$key] ['task_random_id'] != "") {
													
													$rowModule ['taskDate'] = $rule ['rule_action_content'] [$key] ['taskDate'];
													
													$rowModule ['recurrence'] = $rule ['rule_action_content'] [$key] ['recurrence'];
													$rowModule ['recurnce_week'] = $rule ['rule_action_content'] [$key] ['recurnce_week'];
													$rowModule ['recurnce_hrly'] = $rule ['rule_action_content'] [$key] ['recurnce_hrly'];
													$rowModule ['recurnce_month'] = $rule ['rule_action_content'] [$key] ['recurnce_month'];
													$rowModule ['recurnce_day'] = $rule ['rule_action_content'] [$key] ['recurnce_day'];
													$rowModule ['end_recurrence_date'] = $rule ['rule_action_content'] [$key] ['end_recurrence_date'];
													$rowModule ['taskTime'] = $rule ['rule_action_content'] [$key] ['taskTime'];
													$rowModule ['endtime'] = $rule ['rule_action_content'] [$key] ['endtime'];
													$rowModule ['tasktype'] = $rule ['rule_action_content'] [$key] ['tasktype'];
													$rowModule ['numChecklist'] = $rule ['rule_action_content'] [$key] ['numChecklist'];
													$rowModule ['task_alert'] = $rule ['rule_action_content'] [$key] ['task_alert'];
													$rowModule ['alert_type_sms'] = $rule ['rule_action_content'] [$key] ['alert_type_sms'];
													$rowModule ['alert_type_notification'] = $rule ['rule_action_content'] [$key] ['alert_type_notification'];
													$rowModule ['alert_type_email'] = $rule ['rule_action_content'] [$key] ['alert_type_email'];
													$rowModule ['description'] = $rule ['rule_action_content'] [$key] ['description'];
													$rowModule ['assignto'] = $rule ['rule_action_content'] [$key] ['assign_to'];
													$rowModule ['facilities_id'] = $facilities_id;
													$rowModule ['task_form_id'] = $rule ['rule_action_content'] [$key] ['task_form_id'];
													$rowModule ['transport_tags'] = $rule ['rule_action_content'] [$key] ['transport_tags'];
													$rowModule ['pickup_facilities_id'] = $rule ['rule_action_content'] [$key] ['pickup_facilities_id'];
													$rowModule ['pickup_locations_address'] = $rule ['rule_action_content'] [$key] ['pickup_locations_address'];
													$rowModule ['pickup_locations_time'] = $rule ['rule_action_content'] [$key] ['pickup_locations_time'];
													$rowModule ['dropoff_facilities_id'] = $rule ['rule_action_content'] [$key] ['dropoff_facilities_id'];
													$rowModule ['dropoff_locations_address'] = $rule ['rule_action_content'] [$key] ['dropoff_locations_address'];
													$rowModule ['dropoff_locations_time'] = $rule ['rule_action_content'] [$key] ['dropoff_locations_time'];
													$rowModule ['recurnce_hrly_recurnce'] = $rule ['rule_action_content'] [$key] ['recurnce_hrly_recurnce'];
													$rowModule ['daily_endtime'] = $rule ['rule_action_content'] [$key] ['daily_endtime'];
													$rowModule ['daily_times'] = $rule ['rule_action_content'] [$key] ['daily_times'];
													$rowModule ['medication_tags'] = $rule ['rule_action_content'] [$key] ['medication_tags'];
													$rowModule ['tags_medication_details_ids'] = $rule ['rule_action_content'] [$key] ['tags_medication_details_ids'];
													$rowModule ['emp_tag_id'] = $rule ['rule_action_content'] [$key] ['emp_tag_id'];
													$rowModule ['recurnce_hrly_perpetual'] = $rule ['rule_action_content'] [$key] ['recurnce_hrly_perpetual'];
													$rowModule ['completion_alert'] = $rule ['rule_action_content'] [$key] ['completion_alert'];
													$rowModule ['completion_alert_type_sms'] = $rule ['rule_action_content'] [$key] ['completion_alert_type_sms'];
													$rowModule ['completion_alert_type_email'] = $rule ['rule_action_content'] [$key] ['completion_alert_type_email'];
													$rowModule ['user_roles'] = $rule ['rule_action_content'] [$key] ['user_roles'];
													$rowModule ['userids'] = $rule ['rule_action_content'] [$key] ['userids'];
													$rowModule ['task_status'] = $rule ['rule_action_content'] [$key] ['task_status'];
													$rowModule ['visitation_tag_id'] = $rule ['rule_action_content'] [$key] ['visitation_tag_id'];
													$rowModule ['visitation_tags'] = $rule ['rule_action_content'] [$key] ['visitation_tags'];
													$rowModule ['visitation_start_facilities_id'] = $rule ['rule_action_content'] [$key] ['visitation_start_facilities_id'];
													$rowModule ['visitation_start_address'] = $rule ['rule_action_content'] [$key] ['visitation_start_address'];
													$rowModule ['visitation_start_time'] = $rule ['rule_action_content'] [$key] ['visitation_start_time'];
													$rowModule ['visitation_appoitment_facilities_id'] = $rule ['rule_action_content'] [$key] ['visitation_appoitment_facilities_id'];
													$rowModule ['visitation_appoitment_address'] = $rule ['rule_action_content'] [$key] ['visitation_appoitment_address'];
													$rowModule ['visitation_appoitment_time'] = $rule ['rule_action_content'] [$key] ['visitation_appoitment_time'];
													$rowModule ['complete_endtime'] = $rule ['rule_action_content'] [$key] ['complete_endtime'];
													$rowModule ['completed_times'] = $rule ['rule_action_content'] [$key] ['completed_times'];
													
													$rowModule ['completed_alert'] = $rule ['rule_action_content'] [$key] ['completed_alert'];
													$rowModule ['completed_late_alert'] = $rule ['rule_action_content'] [$key] ['completed_late_alert'];
													$rowModule ['incomplete_alert'] = $rule ['rule_action_content'] [$key] ['incomplete_alert'];
													$rowModule ['deleted_alert'] = $rule ['rule_action_content'] [$key] ['deleted_alert'];
													$rowModule ['attachement_form'] = $rule ['rule_action_content'] [$key] ['attachement_form'];
													$rowModule ['tasktype_form_id'] = $rule ['rule_action_content'] [$key] ['tasktype_form_id'];
													
													$rowModule ['reminder_alert'] = $rule ['rule_action_content'] [$key] ['reminder_alert'];
													
													if ($rule ['rule_action_content'] [$key] ['reminderminus'] != null && $rule ['rule_action_content'] [$key] ['reminderminus'] != "") {
														$rowModule ['reminderminus'] = explode ( ',', $rule ['rule_action_content'] [$key] ['reminderminus'] );
													}
													
													if ($rule ['rule_action_content'] [$key] ['reminderplus'] != null && $rule ['rule_action_content'] [$key] ['reminderplus'] != "") {
														$rowModule ['reminderplus'] = explode ( ',', $rule ['rule_action_content'] [$key] ['reminderplus'] );
													}
													
													$rowModule ['assign_to_type'] = $rule ['rule_action_content'] [$key] ['assign_to_type'];
													
													if ($rule ['rule_action_content'] ['user_assign_to'] != null && $rule ['rule_action_content'] [$key] ['user_assign_to'] != "") {
														$rowModule ['assign_to'] = explode ( ',', $rule ['rule_action_content'] [$key] ['user_assign_to'] );
													}
													
													if ($rule ['rule_action_content'] [$key] ['user_role_assign_ids'] != null && $rule ['rule_action_content'] [$key] ['user_role_assign_ids'] != "") {
														$rowModule ['user_role_assign_ids'] = explode ( ',', $rule ['rule_action_content'] [$key] ['user_role_assign_ids'] );
													}
													
													// echo '<pre>rowModule-'; print_r($rowModule); echo '</pre>';
													
													/* ---------------Create task start--------------------------- */
													// var_dump($rowModule);
													
													// echo 'createtask-'.
													$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $tresult ['notes_id'] . "' and task_random_id = '" . $rule ['rule_action_content'] [$key] ['task_random_id'] . "' and taskadded = '0'  ";
													$query4 = $this->db->query ( $sqls23 );
													
													// echo "<hr>";
													// echo 'AAA-';
													$query4->num_rows;
													
													if ($query4->num_rows == 0) {
														$addtask = array ();
														
														/*
														 * if($rowModule['taskTime'] != null && $rowModule['taskTime'] != ""){
														 * $snooze_time71 = 0;
														 * $thestime61 = $rowModule['taskTime'];
														 * }else{
														 * $snooze_time71 = 10;
														 * $thestime61 = date('H:i:s');
														 * }
														 */
														
														$snooze_time71 = 1;
														$thestime61 = date ( 'H:i:s' );
														
														$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
														
														// $addtask['taskDate'] = date('m-d-Y');
														$addtask ['taskDate'] = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
														
														// $addtask['end_recurrence_date'] = date('m-d-Y');
														
														$addtask ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
														
														$addtask ['recurrence'] = $rowModule ['recurrence'];
														$addtask ['recurnce_week'] = $rowModule ['recurnce_week'];
														$addtask ['recurnce_hrly'] = $rowModule ['recurnce_hrly'];
														$addtask ['recurnce_month'] = $rowModule ['recurnce_month'];
														$addtask ['recurnce_day'] = $rowModule ['recurnce_day'];
														$addtask ['taskTime'] = $taskTime; // date('H:i:s');
														$addtask ['endtime'] = $stime8;
														
														// $notes_description123 = 'notes_description';
														$notes_description123 = substr ( $tresult ['notes_description'], 0, 150 ) . ((strlen ( $tresult ['notes_description'] ) > 150) ? '..' : '');
														
														$addtask ['description'] = $rowModule ['description'] . ' ' . $notes_description123;
														
														if ($rowModule ['assign_to']) {
															$addtask ['assignto'] = $rowModule ['assign_to'];
														} else {
															// $userinfo = $this->model_user_user->getUserByUsernamebynotes($tresult['user_id'], $tresult['facilities_id']);
															$addtask ['assignto'] = $tresult ['user_id'];
														}
														
														$addtask ['facilities_id'] = $rowModule ['facilities_id'];
														$addtask ['task_form_id'] = $rowModule ['task_form_id'];
														
														$tagss = array ();
														if ($rowModule ['transport_tags'] != null && $rowModule ['transport_tags'] != "") {
															$tagss [] = explode ( ',', $rowModule ['transport_tags'] );
														}
														
														if ($tresult ['emp_tag_id'] == '1') {
															$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $tresult ['notes_id'] );
															foreach ( $alltags as $alltag ) {
																$tagss [] = $alltag ['tags_id'];
															}
														}
														
														$tagss1 = array_unique ( $tagss );
														
														$addtask ['transport_tags'] = $tagss1;
														
														$addtask ['pickup_facilities_id'] = $rowModule ['pickup_facilities_id'];
														$addtask ['pickup_locations_address'] = $rowModule ['pickup_locations_address'];
														$addtask ['pickup_locations_time'] = $rowModule ['pickup_locations_time'];
														
														$addtask ['dropoff_facilities_id'] = $rowModule ['dropoff_facilities_id'];
														$addtask ['dropoff_locations_address'] = $rowModule ['dropoff_locations_address'];
														$addtask ['dropoff_locations_time'] = $rowModule ['dropoff_locations_time'];
														
														$addtask ['tasktype'] = $rowModule ['tasktype'];
														$addtask ['numChecklist'] = $rowModule ['numChecklist'];
														$addtask ['task_alert'] = $rowModule ['task_alert'];
														$addtask ['alert_type_sms'] = $rowModule ['alert_type_sms'];
														$addtask ['alert_type_notification'] = $rowModule ['alert_type_notification'];
														$addtask ['alert_type_email'] = $rowModule ['alert_type_email'];
														$addtask ['rules_task'] = $tresult ['notes_id'];
														// $addtask['rules_task'] = $rule['rule_action_content'][$key]['task_random_id'];
														
														$addtask ['recurnce_hrly_recurnce'] = $rowModule ['recurnce_hrly_recurnce'];
														$addtask ['daily_endtime'] = $rowModule ['daily_endtime'];
														
														if ($rowModule ['daily_times'] != null && $rowModule ['daily_times'] != "") {
															$addtask ['daily_times'] = explode ( ',', $rowModule ['daily_times'] );
														}
														
														if ($rowModule ['medication_tags'] != null && $rowModule ['medication_tags'] != "") {
															$addtask ['medication_tags'] = explode ( ',', $rowModule ['medication_tags'] );
															
															$aa = urldecode ( $rowModule ['tags_medication_details_ids'] );
															$aa1 = unserialize ( $aa );
															
															$tags_medication_details_ids = array ();
															foreach ( $aa1 as $key => $mresult ) {
																$tags_medication_details_ids [$key] = $mresult;
															}
															$addtask ['tags_medication_details_ids'] = $tags_medication_details_ids;
														}
														if ($rowModule ['emp_tag_id'] != null && $rowModule ['emp_tag_id'] != "") {
															$addtask ['emp_tag_id'] = $rowModule ['emp_tag_id'];
														} else {
															$addtask ['emp_tag_id'] = $tagss1 [0];
														}
														
														$addtask ['recurnce_hrly_perpetual'] = $rowModule ['recurnce_hrly_perpetual'];
														$addtask ['completion_alert'] = $rowModule ['completion_alert'];
														$addtask ['completion_alert_type_sms'] = $rowModule ['completion_alert_type_sms'];
														$addtask ['completion_alert_type_email'] = $rowModule ['completion_alert_type_email'];
														
														if ($rowModule ['user_roles'] != null && $rowModule ['user_roles'] != "") {
															$addtask ['user_roles'] = explode ( ',', $rowModule ['user_roles'] );
														}
														
														if ($rowModule ['userids'] != null && $rowModule ['userids'] != "") {
															$addtask ['userids'] = explode ( ',', $rowModule ['userids'] );
														}
														$addtask ['task_status'] = $rowModule ['task_status'];
														
														$addtask ['visitation_tag_id'] = $rowModule ['visitation_tag_id'];
														
														if ($rowModule ['visitation_tags'] != null && $rowModule ['visitation_tags'] != "") {
															$addtask ['visitation_tags'] = explode ( ',', $rowModule ['visitation_tags'] );
														}
														$addtask ['visitation_start_facilities_id'] = $rowModule ['visitation_start_facilities_id'];
														$addtask ['visitation_start_address'] = $rowModule ['visitation_start_address'];
														$addtask ['visitation_start_time'] = $rowModule ['visitation_start_time'];
														$addtask ['visitation_appoitment_facilities_id'] = $rowModule ['visitation_appoitment_facilities_id'];
														$addtask ['visitation_appoitment_address'] = $rowModule ['visitation_appoitment_address'];
														$addtask ['visitation_appoitment_time'] = $rowModule ['visitation_appoitment_time'];
														$addtask ['complete_endtime'] = $rowModule ['complete_endtime'];
														
														if ($rowModule ['completed_times'] != null && $rowModule ['completed_times'] != "") {
															$addtask ['completed_times'] = explode ( ',', $rowModule ['completed_times'] );
														}
														$addtask ['completed_alert'] = $rowModule ['completed_alert'];
														$addtask ['completed_late_alert'] = $rowModule ['completed_late_alert'];
														$addtask ['incomplete_alert'] = $rowModule ['incomplete_alert'];
														$addtask ['deleted_alert'] = $rowModule ['deleted_alert'];
														$addtask ['attachement_form'] = $rowModule ['attachement_form'];
														$addtask ['tasktype_form_id'] = $rowModule ['tasktype_form_id'];
														
														$addtask ['reminder_alert'] = $rowModule ['reminder_alert'];
														if ($rowModule ['reminderminus'] != null && $rowModule ['reminderminus'] != "") {
															$addtask ['reminderminus'] = explode ( ',', $rowModule ['reminderminus'] );
														}
														
														if ($rowModule ['reminderplus'] != null && $rowModule ['reminderplus'] != "") {
															$addtask ['reminderplus'] = explode ( ',', $rowModule ['reminderplus'] );
														}
														
														$addtask ['assign_to_type'] = $rowModule ['assign_to_type'];
														if ($rowModule ['user_assign_to'] != null && $rowModule ['user_assign_to'] != "") {
															$addtask ['assign_to'] = explode ( ',', $rowModule ['user_assign_to'] );
														}
														
														if ($rowModule ['user_role_assign_ids'] != null && $rowModule ['user_role_assign_ids'] != "") {
															$addtask ['user_role_assign_ids'] = explode ( ',', $rowModule ['user_role_assign_ids'] );
														}
														
														$sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2',form_snooze_dismiss = '2', rule_keyword_task = '1' where notes_id ='" . $tresult ['notes_id'] . "'";
														$this->db->query ( $sqlw );
														
														$task_id = $this->model_createtask_createtask->addcreatetask ( $addtask, $facilities_id );
														
														$sqlw2 = "update `" . DB_PREFIX . "createtask` set task_random_id = '" . $rule ['rule_action_content'] [$key] ['task_random_id'] . "' where id ='" . $task_id . "'";
														$this->db->query ( $sqlw2 );
														
														// echo '<pre>addtask-'; print_r($addtask); echo '</pre>';
														
														// $sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2' where notes_id ='".$tresult['notes_id']."'";
														// $this->db->query($sqlw);
														
														$rowModule = array ();
													}
												}
												
												/* ---------------Create Task End----------------------------- */
											} // foreach
										} // foreach // $allnotesIds
									} // tresult
								} // if
								
								if (in_array ( '5', $rule ['rule_action'] )) {
									
									foreach ( $allnotesIds as $allnotesId ) {
										if ($rule ['rule_action_content'] ['highlighter_id'] != null && $rule ['rule_action_content'] ['highlighter_id'] != "") {
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$this->model_notes_notes->updateNoteHigh ( $allnotesId ['notes_id'], $rule ['rule_action_content'] ['highlighter_id'], $update_date );
										}
									}
								}
								
								if (in_array ( '6', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										if ($rule ['rule_action_content'] ['color_id'] != null && $rule ['rule_action_content'] ['color_id'] != "") {
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$this->model_notes_notes->updateNoteColor ( $allnotesId ['notes_id'], $rule ['rule_action_content'] ['color_id'], $update_date );
										}
									}
								}
							}
						}
					}
					
					// var_dump($facilityDetails);
					// var_dump($json['rulenotes']);
					
					// var_dump($rowModule);
					$tnotesIds = array_unique ( $tnotesIds );
					// var_dump($tnotesIds);
					
					/*
					 * if ($tnotesIds != null && $tnotesIds != "") {
					 * $this->load->model ( 'createtask/createtask' );
					 * $sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (" . implode ( ',', $tnotesIds ) . ") and status = '1' and text_color_cut = '0' and `snooze_dismiss` != '2' ";
					 *
					 * $query2 = $this->db->query ( $sqlst2 );
					 *
					 * $thestime6 = date ( 'H:i:s' );
					 * // var_dump($thestime6);
					 * $snooze_time7 = 60;
					 * $stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
					 * // var_dump($stime8);
					 *
					 * foreach ( $query2->rows as $tresult ) {
					 *
					 * $sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $tresult ['notes_id'] . "' ";
					 * $query4 = $this->db->query ( $sqls23 );
					 *
					 * if ($query4->num_rows == 0) {
					 * $addtask = array ();
					 *
					 *
					 * $snooze_time71 = 1;
					 * $thestime61 = date ( 'H:i:s' );
					 *
					 * $taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
					 *
					 * $addtask ['taskDate'] = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
					 * $addtask ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
					 * $addtask ['recurrence'] = $rowModule ['recurrence'];
					 * $addtask ['recurnce_week'] = $rowModule ['recurnce_week'];
					 * $addtask ['recurnce_hrly'] = $rowModule ['recurnce_hrly'];
					 * $addtask ['recurnce_month'] = $rowModule ['recurnce_month'];
					 * $addtask ['recurnce_day'] = $rowModule ['recurnce_day'];
					 * $addtask ['taskTime'] = $taskTime; // date('H:i:s');
					 * $addtask ['endtime'] = $stime8;
					 *
					 * $notes_description123 = substr ( $tresult ['notes_description'], 0, 150 ) . ((strlen ( $tresult ['notes_description'] ) > 150) ? '..' : '');
					 *
					 * $addtask ['description'] = $rowModule ['description'] . ' ' . $notes_description123;
					 *
					 * if ($rowModule ['assign_to']) {
					 * $addtask ['assignto'] = $rowModule ['assign_to'];
					 * } else {
					 * $addtask ['assignto'] = $result ['user_id'];
					 * }
					 *
					 * $addtask ['facilities_id'] = $rowModule ['facilities_id'];
					 * $addtask ['task_form_id'] = $rowModule ['task_form_id'];
					 *
					 * if ($rowModule ['transport_tags'] != null && $rowModule ['transport_tags'] != "") {
					 * $addtask ['transport_tags'] = explode ( ',', $rowModule ['transport_tags'] );
					 * }
					 *
					 * $addtask ['pickup_facilities_id'] = $rowModule ['pickup_facilities_id'];
					 * $addtask ['pickup_locations_address'] = $rowModule ['pickup_locations_address'];
					 * $addtask ['pickup_locations_time'] = $rowModule ['pickup_locations_time'];
					 *
					 * $addtask ['dropoff_facilities_id'] = $rowModule ['dropoff_facilities_id'];
					 * $addtask ['dropoff_locations_address'] = $rowModule ['dropoff_locations_address'];
					 * $addtask ['dropoff_locations_time'] = $rowModule ['dropoff_locations_time'];
					 *
					 * $addtask ['tasktype'] = $rowModule ['tasktype'];
					 * $addtask ['numChecklist'] = $rowModule ['numChecklist'];
					 * $addtask ['task_alert'] = $rowModule ['task_alert'];
					 * $addtask ['alert_type_sms'] = $rowModule ['alert_type_sms'];
					 * $addtask ['alert_type_notification'] = $rowModule ['alert_type_notification'];
					 * $addtask ['alert_type_email'] = $rowModule ['alert_type_email'];
					 * $addtask ['rules_task'] = $tresult ['notes_id'];
					 *
					 * $addtask ['recurnce_hrly_recurnce'] = $rowModule ['recurnce_hrly_recurnce'];
					 * $addtask ['daily_endtime'] = $rowModule ['daily_endtime'];
					 *
					 * if ($rowModule ['daily_times'] != null && $rowModule ['daily_times'] != "") {
					 * $addtask ['daily_times'] = explode ( ',', $rowModule ['daily_times'] );
					 * }
					 *
					 * if ($rowModule ['medication_tags'] != null && $rowModule ['medication_tags'] != "") {
					 * $addtask ['medication_tags'] = explode ( ',', $rowModule ['medication_tags'] );
					 *
					 * $aa = urldecode ( $rowModule ['tags_medication_details_ids'] );
					 * $aa1 = unserialize ( $aa );
					 *
					 * $tags_medication_details_ids = array ();
					 * foreach ( $aa1 as $key => $mresult ) {
					 * $tags_medication_details_ids [$key] = $mresult;
					 * }
					 * $addtask ['tags_medication_details_ids'] = $tags_medication_details_ids;
					 * }
					 *
					 * $addtask ['emp_tag_id'] = $rowModule ['emp_tag_id'];
					 *
					 * $addtask ['recurnce_hrly_perpetual'] = $rowModule ['recurnce_hrly_perpetual'];
					 * $addtask ['completion_alert'] = $rowModule ['completion_alert'];
					 * $addtask ['completion_alert_type_sms'] = $rowModule ['completion_alert_type_sms'];
					 * $addtask ['completion_alert_type_email'] = $rowModule ['completion_alert_type_email'];
					 *
					 * if ($rowModule ['user_roles'] != null && $rowModule ['user_roles'] != "") {
					 * $addtask ['user_roles'] = explode ( ',', $rowModule ['user_roles'] );
					 * }
					 *
					 * if ($rowModule ['userids'] != null && $rowModule ['userids'] != "") {
					 * $addtask ['userids'] = explode ( ',', $rowModule ['userids'] );
					 * }
					 * $addtask ['task_status'] = $rowModule ['task_status'];
					 *
					 * $addtask ['visitation_tag_id'] = $rowModule ['visitation_tag_id'];
					 *
					 * if ($rowModule ['visitation_tags'] != null && $rowModule ['visitation_tags'] != "") {
					 * $addtask ['visitation_tags'] = explode ( ',', $rowModule ['visitation_tags'] );
					 * }
					 * $addtask ['visitation_start_facilities_id'] = $rowModule ['visitation_start_facilities_id'];
					 * $addtask ['visitation_start_address'] = $rowModule ['visitation_start_address'];
					 * $addtask ['visitation_start_time'] = $rowModule ['visitation_start_time'];
					 * $addtask ['visitation_appoitment_facilities_id'] = $rowModule ['visitation_appoitment_facilities_id'];
					 * $addtask ['visitation_appoitment_address'] = $rowModule ['visitation_appoitment_address'];
					 * $addtask ['visitation_appoitment_time'] = $rowModule ['visitation_appoitment_time'];
					 * $addtask ['complete_endtime'] = $rowModule ['complete_endtime'];
					 *
					 * if ($rowModule ['completed_times'] != null && $rowModule ['completed_times'] != "") {
					 * $addtask ['completed_times'] = explode ( ',', $rowModule ['completed_times'] );
					 * }
					 * $addtask ['completed_alert'] = $rowModule ['completed_alert'];
					 * $addtask ['completed_late_alert'] = $rowModule ['completed_late_alert'];
					 * $addtask ['incomplete_alert'] = $rowModule ['incomplete_alert'];
					 * $addtask ['deleted_alert'] = $rowModule ['deleted_alert'];
					 * $addtask ['attachement_form'] = $rowModule ['attachement_form'];
					 * $addtask ['tasktype_form_id'] = $rowModule ['tasktype_form_id'];
					 *
					 * $addtask ['reminder_alert'] = $rowModule ['reminder_alert'];
					 * if ($rowModule ['reminderminus'] != null && $rowModule ['reminderminus'] != "") {
					 * $addtask ['reminderminus'] = explode ( ',', $rowModule ['reminderminus'] );
					 * }
					 *
					 * if ($rowModule ['reminderplus'] != null && $rowModule ['reminderplus'] != "") {
					 * $addtask ['reminderplus'] = explode ( ',', $rowModule ['reminderplus'] );
					 * }
					 *
					 * $addtask ['assign_to_type'] = $rowModule ['assign_to_type'];
					 * if ($rowModule ['user_assign_to'] != null && $rowModule ['user_assign_to'] != "") {
					 * $addtask ['assign_to'] = explode ( ',', $rowModule ['user_assign_to'] );
					 * }
					 *
					 * if ($rowModule ['user_role_assign_ids'] != null && $rowModule ['user_role_assign_ids'] != "") {
					 * $addtask ['user_role_assign_ids'] = explode ( ',', $rowModule ['user_role_assign_ids'] );
					 * }
					 *
					 * $this->model_createtask_createtask->addcreatetask ( $addtask, $facilities_id );
					 *
					 * $sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2',form_snooze_dismiss = '2' where notes_id ='" . $tresult ['notes_id'] . "'";
					 * $this->db->query ( $sqlw );
					 *
					 * $rowModule = array ();
					 * }
					 * }
					 * }
					 */
					
					$notesIds = array_unique ( $notesIds );
					
					if ($notesIds != null && $notesIds != "") {
						
						$thestime = date ( 'H:i:s' );
						// var_dump($thestime);
						$snooze_time = 0;
						$stime = date ( "H:i:s", strtotime ( "+" . $snooze_time . " minutes", strtotime ( $thestime ) ) );
						
						// var_dump($stime);
						
						$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (" . implode ( ',', $notesIds ) . ") and snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
						
						$query = $this->db->query ( $sqls2 );
						
						$config_tag_status = $this->customer->isTag ();
						
						if ($query->num_rows) {
							
							foreach ( $query->rows as $result ) {
								
								// echo $thestime.'<='.$result['snooze_time'];
								if ($thestime >= $result ['snooze_time']) {
									$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
									// $user_info = $this->model_user_user->getUserByUsername($result['user_id']);
									$user_info = $this->model_user_user->getUserByUsernamebynotes ( $result ['user_id'], $result ['facilities_id'] );
									
									if ($config_tag_status == '1') {
										if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
											$tagdata = $this->model_notes_tags->getTagbyEMPID ( $result ['emp_tag_id'] );
											$privacy = $tagdata ['privacy'];
											
											$emp_tag_id = $result ['emp_tag_id'] . ': ';
										} else {
											$emp_tag_id = '';
											$privacy = '';
										}
									}
									
									$notes_description_32 = html_entity_decode ( str_replace ( '&#039;', '\'', $result ['notes_description'] ) );
									
									$notes_description_2 = substr ( $notes_description_32, 0, 350 ) . ((strlen ( $notes_description_32 ) > 350) ? '..' : '');
									
									if ($privacy == '2') {
										if ($this->session->data ['unloack_success'] == '1') {
											$notes_description = $keyImageSrc1 . '&nbsp;' . $emp_tag_id . $notes_description_2;
										} else {
											$notes_description = $emp_tag_id;
										}
									} else {
										$notes_description = $keyImageSrc1 . '&nbsp;' . $emp_tag_id . $notes_description_2;
									}
									
									$json ['rulenotes'] [] = array (
											'notes_id' => $result ['notes_id'],
											'rules_id' => '',
											'highlighter_value' => '',
											'notes_description' => $notes_description,
											'date_added' => date ( 'j, F Y', strtotime ( $result ['date_added'] ) ),
											'note_date' => date ( 'j, F Y h:i A', strtotime ( $result ['note_date'] ) ),
											'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
											'username' => $result ['user_id'],
											'email' => $user_info ['email'],
											'facility' => $facility ['facility'],
											'facilities_info' => $facilities_info,
											
											'android_audio_file' => $facility_android_audio_file,
											'ios_audio_file' => $facility_ios_audio_file 
									);
									
									$json ['total'] = '1';
									$json ['formrules'] = array ();
								} else {
									if ($json ['rulenotes'] == null && $json ['rulenotes'] == "") {
										$json ['rulenotes'] = array ();
										$json ['total'] = '0';
										$json ['status'] = true;
										$json ['formrules'] = array ();
									}
								}
							}
						} else {
							$json ['rulenotes'] = array ();
							$json ['total'] = '0';
							$json ['status'] = true;
							$json ['formrules'] = array ();
						}
					} else {
						if ($json ['rulenotes'] == null && $json ['rulenotes'] == "") {
							$json ['rulenotes'] = array ();
							$json ['total'] = '0';
							$json ['status'] = true;
							$json ['formrules'] = array ();
						}
					}
					
					// $timezone_name = $this->customer->isTimezone();
					
					if ($config_task_status == '1') {
						
						$timeZone = date_default_timezone_set ( $timezone_name );
						
						$this->load->model ( 'createtask/createtask' );
						
						$tasktypes = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
						
						// var_dump($tasktypes);
						
						foreach ( $tasktypes as $tasktype ) {
							
							if ($tasktype ['android_audio_file'] != NULL && $tasktype ['android_audio_file'] != "") {
								$android_audio_file = HTTP_SERVER . 'image/ringtone/' . $tasktype ['android_audio_file'];
							} else {
								$android_audio_file = '';
							}
							
							if ($tasktype ['ios_audio_file'] != NULL && $tasktype ['ios_audio_file'] != "") {
								$ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $tasktype ['ios_audio_file'];
							} else {
								$ios_audio_file = '';
							}
							
							$data1 = array ();
							
							$currentdate = date ( 'd-m-Y' );
							$data1 ['currentdate'] = $currentdate;
							$data1 ['notification'] = '1';
							$data1 ['top'] = '2';
							$data1 ['snooze_dismiss'] = '2';
							$data1 ['subfacilities_id'] = 1;
							$data1 ['facilities_id'] = $facilities_id;
							$data1 ['task_id'] = $tasktype ['task_id'];
							
							$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $data1 );
							
							// var_dump($data1);
							// var_dump($compltetecountTaskLists);
							
							$compltetecountTaskLists1 = $compltetecountTaskLists1 + $compltetecountTaskLists;
							
							$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists ( $data1 );
							
							$tthestime = date ( 'H:i:s' );
							// var_dump($tthestime);
							
							$snooze_time = 0;
							$tstime = date ( "H:i:s", strtotime ( "+" . $snooze_time . " minutes", strtotime ( $tthestime ) ) );
							// var_dump($tstime);
							
							if ($compltetecountTaskLists > 0) {
								
								$this->load->model ( 'setting/locations' );
								$this->load->model ( 'setting/tags' );
								
								foreach ( $complteteTaskLists as $list ) {
									if ($tthestime >= $list ['snooze_time']) {
										
										$url2 = "";
										if ($list ['formreturn_id'] > 0) {
											$url2 .= '&forms_id=' . $list ['formreturn_id'];
											$this->load->model ( 'form/form' );
											$result_info = $this->model_form_form->getFormDatas ( $list ['formreturn_id'] );
											if ($result_info ['notes_id'] != null && $result_info ['notes_id'] != "") {
												$url2 .= '&notes_id=' . $result_info ['notes_id'];
											}
										}
										
										if ($list ['checklist'] == "incident_form") {
											$insert_href = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/taskforminsert', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
											$attachement_form = '0';
										} elseif ($list ['checklist'] == "bed_check") {
											$insert_href = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/checklistform', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
											$attachement_form = '0';
										} elseif (is_numeric ( $list ['checklist'] )) {
											$insert_href = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['checklist'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] . $url2 ) );
											$attachement_form = '1';
										} elseif ($list ['attachement_form'] == '1') {
											$insert_href = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['tasktype_form_id'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] . $url2 ) );
											$attachement_form = $list ['attachement_form'];
										} else {
											$insert_href = str_replace ( '&amp;', '&', $this->url->link ( 'services/apptask/jsonSavetask', '' . 'task_id=' . $list ['id'] ) );
											$attachement_form = '0';
										}
										
										$bedcheckdata = array ();
										
										if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
											
											if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
												$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
											} else {
												$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
											}
											
											foreach ( $formDatas as $formData ) {
												
												$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
												
												$locationDatab = array ();
												
												$locationDatab [] = array (
														'locations_id' => $locData ['locations_id'],
														'location_name' => $locData ['location_name'],
														'location_address' => $locData ['location_address'],
														'location_detail' => $locData ['location_detail'],
														'capacity' => $locData ['capacity'],
														'location_type' => $locData ['location_type'],
														'nfc_location_tag' => $locData ['nfc_location_tag'],
														'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
														'gps_location_tag' => $locData ['gps_location_tag'],
														'gps_location_tag_required' => $locData ['gps_location_tag_required'],
														'latitude' => $locData ['latitude'],
														'longitude' => $locData ['longitude'],
														'other_location_tag' => $locData ['other_location_tag'],
														'other_location_tag_required' => $locData ['other_location_tag_required'],
														'other_type_id' => $locData ['other_type_id'],
														'facilities_id' => $locData ['facilities_id'] 
												);
												
												$bedcheckdata [] = array (
														'task_form_location_id' => $formData ['task_form_location_id'],
														'location_name' => $formData ['location_name'],
														'location_detail' => $formData ['location_detail'],
														'current_occupency' => $formData ['current_occupency'],
														'bedcheck_locations' => $locationDatab 
												);
											}
											
											/*
											 * $this->load->model('setting/bedchecktaskform');
											 * $taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
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
										
										$medications = array ();
										
										/*
										 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
										 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
										 * $locationData = array();
										 * $locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
										 *
										 * $locationData[] = array(
										 * 'locations_id' =>$locData['locations_id'],
										 * 'location_name' =>$locData['location_name'],
										 * 'location_address' =>$locData['location_address'],
										 * 'location_detail' =>$locData['location_detail'],
										 * 'capacity' =>$locData['capacity'],
										 * 'location_type' =>$locData['location_type'],
										 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
										 * 'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
										 * 'gps_location_tag' =>$locData['gps_location_tag'],
										 * 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
										 * 'latitude' =>$locData['latitude'],
										 * 'longitude' =>$locData['longitude'],
										 * 'other_location_tag' =>$locData['other_location_tag'],
										 * 'other_location_tag_required' =>$locData['other_location_tag_required'],
										 * 'other_type_id' =>$locData['other_type_id'],
										 * 'facilities_id' =>$locData['facilities_id']
										 *
										 * );
										 *
										 * $medications[] = array(
										 * 'tags_id' =>$tags_info['tags_id'],
										 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
										 * 'emp_first_name' =>$tags_info['emp_first_name'],
										 * 'emp_last_name' =>$tags_info['emp_last_name'],
										 * 'doctor_name' =>$tags_info['doctor_name'],
										 * 'emergency_contact' =>$tags_info['emergency_contact'],
										 * 'dob' =>$tags_info['dob'],
										 * 'medications_locations' =>$locationData
										 * );
										 *
										 * }
										 */
										
										$transport_tags = array ();
										$this->load->model ( 'setting/tags' );
										
										if (! empty ( $list ['transport_tags'] )) {
											$transport_tags1 = explode ( ',', $list ['transport_tags'] );
										} else {
											$transport_tags1 = array ();
										}
										
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info ['emp_first_name']) {
												$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
											} else {
												$emp_tag_id = $tags_info ['emp_tag_id'];
											}
											
											if ($tags_info) {
												$transport_tags [] = array (
														'tags_id' => $tags_info ['tags_id'],
														'emp_tag_id' => $emp_tag_id 
												);
											}
										}
										
										$medication_tags = array ();
										$this->data ['medication_tags'] = array ();
										$this->load->model ( 'setting/tags' );
										
										if (! empty ( $list ['medication_tags'] )) {
											$medication_tags1 = explode ( ',', $list ['medication_tags'] );
										} else {
											$medication_tags1 = array ();
										}
										
										foreach ( $medication_tags1 as $medicationtag ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												
												$locationData = array ();
												$locData = $this->model_setting_locations->getlocation ( $tags_info1 ['locations_id'] );
												
												if ($locData) {
													$locationData [] = array (
															'locations_id' => $locData ['locations_id'],
															'location_name' => $locData ['location_name'],
															'location_address' => $locData ['location_address'],
															'location_detail' => $locData ['location_detail'],
															'capacity' => $locData ['capacity'],
															'location_type' => $locData ['location_type'],
															'nfc_location_tag' => $locData ['nfc_location_tag'],
															'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
															'gps_location_tag' => $locData ['gps_location_tag'],
															'gps_location_tag_required' => $locData ['gps_location_tag_required'],
															'latitude' => $locData ['latitude'],
															'longitude' => $locData ['longitude'],
															'other_location_tag' => $locData ['other_location_tag'],
															'other_location_tag_required' => $locData ['other_location_tag_required'],
															'other_type_id' => $locData ['other_type_id'],
															'facilities_id' => $locData ['facilities_id'] 
													);
												}
												
												if ($tags_info1 ['upload_file'] != null && $tags_info1 ['upload_file'] != "") {
													$upload_file2 = $tags_info1 ['upload_file'];
												} else {
													$upload_file2 = "";
												}
												
												$drugs = array ();
												
												$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
												
												foreach ( $mdrugs as $mdrug ) {
													
													$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
													
													$drugs [] = array (
															'tags_medication_details_id' => $mdrug ['tags_medication_details_id'],
															'drug_name' => $mdrug_info ['drug_name'],
															'tags_medication_id' => $mdrug_info ['tags_medication_id'],
															'drug_mg' => $mdrug_info ['drug_mg'],
															'drug_alertnate' => $mdrug_info ['drug_alertnate'],
															'drug_prn' => $mdrug_info ['drug_prn'],
															'instructions' => $mdrug_info ['instructions'],
															'drug_am' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_am'] ) ),
															'drug_pm' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_pm'] ) ),
															'upload_file' => $upload_file2,
															
															'createtask_by_group_id' => '',
															'facilities_id' => $mdrug_info ['facilities_id'],
															'locations_id' => '',
															'tags_id' => $mdrug_info ['tags_id'],
															'medication_id' => $mdrug_info ['tags_medication_id'],
															'dose' => '',
															'drug_type' => '',
															'quantity' => '',
															'frequency' => '',
															'start_time' => '',
															'count' => '',
															'complete_status' => '' 
													);
												}
												
												$medication_tags [] = array (
														'tags_id' => $tags_info1 ['tags_id'],
														'upload_file' => $upload_file2,
														'emp_tag_id' => $tags_info1 ['emp_tag_id'],
														'emp_tag_id_full' => $emp_tag_id,
														'emp_first_name' => $tags_info1 ['emp_first_name'],
														'tags_pin' => $tags_info1 ['tags_pin'],
														'emp_last_name' => $tags_info1 ['emp_last_name'],
														'doctor_name' => $tags_info1 ['doctor_name'],
														'emergency_contact' => $tags_info1 ['emergency_contact'],
														'dob' => $tags_info1 ['dob'],
														'medications_locations' => $locationData,
														'medications_drugs' => $drugs 
												);
											}
										}
										
										if ($list ['visitation_tag_id']) {
											$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
											
											if ($visitation_tag ['emp_first_name']) {
												$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
											} else {
												$visitation_tag_id = $visitation_tag ['emp_tag_id'];
											}
										}
										
										$taskstarttime = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
										
										$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'], $list ['facilityId'] );
										
										if ($tasktype_info ['custom_completion_rule'] == '1') {
											$addTime = $tasktype_info ['config_task_complete'];
										} else {
											$addTime = $this->config->get ( 'config_task_complete' );
										}
										
										$currenttimePlus = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
										
										$tasktypetype = $tasktype_info ['type'];
										$is_task_rule = $tasktype_info ['is_task_rule'];
										if ($is_task_rule != '1') {
											if ($tasktypetype != '5') {
												if ($currenttimePlus >= $taskstarttime) {
													$taskDuration = '1';
												} else {
													if ($list ['is_pause'] == '1') {
														$taskDuration = '1';
													} else {
														$taskDuration = '2';
													}
												}
											} else {
												$taskDuration = '1';
											}
										} else {
											$taskDuration = '1';
										}
										
										if ($list ['snooze_time'] != null && $list ['snooze_time'] != "00:00:00") {
											$snooze_time = date ( 'h:i A', strtotime ( $list ['snooze_time'] ) );
										} else {
											$snooze_time = '';
										}
										
										$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $list ['facilityId'] );
										
										if ($list ['emp_tag_id']) {
											$visitation_tag = $this->model_setting_tags->getTag ( $list ['emp_tag_id'] );
											
											if ($visitation_tag ['emp_first_name']) {
												$emp_tag_id = $visitation_tag ['emp_last_name'] . ', ' . $visitation_tag ['emp_first_name'];
											} else {
												$emp_tag_id = $visitation_tag ['emp_tag_id'];
											}
											
											$rresults = $this->model_setting_locations->getlocation ( $visitation_tag ['room'] );
											$location_name = " | " . $rresults ['location_name'];
											
											$emp_extid = ' | ' . $visitation_tag ['emp_extid'];
											$ssn = $visitation_tag ['ssn'];
										} else {
											$emp_tag_id = "";
											$location_name = "";
											$emp_extid = "";
											$ssn = "";
										}
										
										$json ['tasklits'] [] = array (
												'taskDuration' => $taskDuration,
												'emp_tag_id' => $emp_tag_id,
												'location_name' => $location_name,
												'emp_extid' => $emp_extid,
												'ssn' => $ssn,
												'assign_to' => $list ['assign_to'],
												'required_assign' => $list ['required_assign'],
												'facilities_id' => $list ['facilityId'],
												'task_group_by' => $list ['task_group_by'],
												'iswaypoint' => $list ['iswaypoint'],
												'enable_requires_approval' => $list ['enable_requires_approval'],
												'is_approval_required_forms_id' => $list ['is_approval_required_forms_id'],
												'attachement_form' => $attachement_form,
												'tasktype_form_id' => $list ['tasktype_form_id'],
												'recurrence' => $list ['recurrence'],
												'tasktype' => $list ['tasktype'],
												'checklist' => $list ['checklist'],
												'task_complettion' => $list ['task_complettion'],
												'device_id' => $list ['device_id'],
												'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
												'id' => $list ['id'],
												'description' => html_entity_decode ( str_replace ( '&#039;', '\'', $list ['description'] ) ) . ' ' . $emp_tag_id . ' ' . $ssn . ' ' . $location_name,
												'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
												'snooze_time' => $snooze_time,
												'strice_href' => str_replace ( '&amp;', '&', $this->url->link ( 'services/apptask/jsonUpdateStriketask', '' . 'task_id=' . $list ['id'] ) ),
												// 'incident_form_href' => $incident_form_href,
												// 'bed_check_href' => $bed_check_href,
												'insert_href' => $insert_href,
												'task_form_id' => $list ['task_form_id'],
												'tags_id' => $list ['tags_id'],
												'pickup_facilities_id' => $list ['pickup_facilities_id'],
												'pickup_locations_address' => $list ['pickup_locations_address'],
												'pickup_locations_time' => $list ['pickup_locations_time'],
												'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
												'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
												'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
												'dropoff_locations_address' => $list ['dropoff_locations_address'],
												'dropoff_locations_time' => $list ['dropoff_locations_time'],
												'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
												'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
												'transport_tags' => $transport_tags,
												// 'medications' =>$medications,
												'bedchecks' => $bedcheckdata,
												
												'medication_tags' => $medication_tags,
												'visitation_tags' => $list ['visitation_tags'],
												'visitation_tag_id' => $visitation_tag_id,
												'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
												'visitation_start_address' => $list ['visitation_start_address'],
												'visitation_start_time' => date ( 'h:i A', strtotime ( $list ['visitation_start_time'] ) ),
												'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
												'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
												'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
												'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
												'visitation_appoitment_time' => date ( 'h:i A', strtotime ( $list ['visitation_appoitment_time'] ) ),
												'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
												'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'],
												
												'android_audio_file' => $android_audio_file,
												'ios_audio_file' => $ios_audio_file,
												'facilities_info' => $facilities_info2 
										);
										
										$json ['total'] = $compltetecountTaskLists1;
									}
									/*
									 * else{
									 * $json['tasklits'] = array();
									 *
									 * }
									 */
								}
							} else {
								// $json['status'] = true;
								// $json['tasklits'] = array();
							}
						}
						
						$json ['status'] = true;
						
						$datasms1 = array ();
						
						$currentdate = date ( 'd-m-Y' );
						$datasms1 ['currentdate'] = $currentdate;
						$datasms1 ['alert_type_sms'] = '1';
						$datasms1 ['top'] = '2';
						// $datasms1['send_sms'] = '1';
						$datasms1 ['facilities_id'] = $facilities_id;
						
						$compltetecountsmsTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $datasms1 );
						
						$compltetesmsTaskLists = $this->model_createtask_createtask->getallTaskLists ( $datasms1 );
						
						$tthestimes = date ( 'H:i:s' );
						// var_dump($tthestime);
						
						$snooze_time = 0;
						$tsmsstime = date ( "H:i:s", strtotime ( "+" . $snooze_time . " minutes", strtotime ( $tthestimes ) ) );
						
						if ($compltetecountsmsTaskLists > 0) {
							// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
							foreach ( $compltetesmsTaskLists as $task ) {
								
								if ($task ['send_sms'] == '0') {
									$username = $task ['assign_to'];
									$this->load->model ( 'user/user' );
									$this->load->model ( 'setting/tags' );
									$userData = $this->model_user_user->getUser ( $username );
									
									if ($userData ['phone_number'] != 0) {
										$phone_number = $userData ['phone_number'];
									}
									// var_dump($phone_number);
									
									$message = "Task due at " . date ( 'h:i A', strtotime ( $task ['task_time'] ) ) . "...\n";
									$message .= "Task Type: " . $task ['tasktype'] . "\n";
									
									if ($task ['emp_tag_id'] != null && $task ['emp_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task ['emp_tag_id'] );
										
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									
									if ($task ['medication_tags'] != null && $task ['medication_tags'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task ['medication_tags'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($task ['visitation_tag_id'] != null && $task ['visitation_tag_id'] != "") {
										$tags_info1 = $this->model_setting_tags->getTag ( $task ['visitation_tag_id'] );
										if ($tags_info1 ['emp_first_name']) {
											$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
										} else {
											$emp_tag_id = $tags_info1 ['emp_tag_id'];
										}
										
										if ($tags_info1) {
											$message .= "Client Name: " . $emp_tag_id . "\n";
										}
									}
									if ($task ['transport_tags'] != null && $task ['transport_tags'] != "") {
										
										$transport_tags1 = explode ( ',', $task ['transport_tags'] );
										
										$transport_tags = '';
										foreach ( $transport_tags1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$transport_tags .= $emp_tag_id . ', ';
											}
										}
										
										$message .= "Client Name: " . $transport_tags . "\n";
									}
									
									$message .= "Description: " . substr ( $task ['description'], 0, 150 ) . ((strlen ( $task ['description'] ) > 150) ? '..' : '') . "\n";
									// $message .= "______________________\n";
									// $message .= "REPLY WITH ID ".$task['id']."@ to Mark it complete.";
									
									$sdata = array ();
									$sdata ['message'] = $message;
									$sdata ['phone_number'] = $phone_number;
									$sdata ['facilities_id'] = $facilities_id;
									// $sdata['is_task'] = 1;
									$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET message_sid = '" . $response->sid . "', send_sms = '1' WHERE id = '" . $task ['id'] . "'";
									$query = $this->db->query ( $sql3 );
									
									$response = $this->model_api_smsapi->sendsms ( $sdata );
								}
							}
						}
						
						$dataemail1 = array ();
						
						$currentdate = date ( 'd-m-Y' );
						$dataemail1 ['currentdate'] = $currentdate;
						$dataemail1 ['alert_type_email'] = '1';
						$dataemail1 ['top'] = '2';
						// $dataemail1['send_email'] = '1';
						$dataemail1 ['facilities_id'] = $facilities_id;
						
						$compltetecountemailTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $dataemail1 );
						
						$complteteemailTaskLists = $this->model_createtask_createtask->getallTaskLists ( $dataemail1 );
						
						$tthestimes = date ( 'H:i:s' );
						// var_dump($tthestime);
						
						$snooze_time = 0;
						$tsmsstime = date ( "H:i:s", strtotime ( "+" . $snooze_time . " minutes", strtotime ( $tthestimes ) ) );
						
						if ($compltetecountemailTaskLists > 0) {
							foreach ( $complteteemailTaskLists as $task ) {
								
								if ($task ['send_email'] == '0') {
									$message33 = "";
									$message33 .= $this->taskemailtemplate ( $task, $task ['date_added'], $task ['task_time'] );
									
									if ($task ['assign_to'] != "" && $task ['assign_to'] != NULL) {
										$username = $task ['assign_to'];
										$this->load->model ( 'user/user' );
										$userEmail = $this->model_user_user->getUser ( $username );
										
										if ($userEmail ['email'] != null && $userEmail ['email'] != "") {
											$user_email = $userEmail ['email'];
										}
									}
									
									$edata = array ();
									$edata ['message'] = $message33;
									$edata ['subject'] = 'Task has been assigned to you';
									$edata ['user_email'] = $user_email;
									
									$sql3e = "UPDATE `" . DB_PREFIX . "createtask` SET send_email = '1' WHERE id = '" . $task ['id'] . "'";
									$query = $this->db->query ( $sql3e );
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata );
								}
							}
						}
					}
					
					// var_dump($json);
					
					$this->load->model ( 'form/form' );
					
					$fnotesIdsemail = array ();
					$andRuleArrayemail = array ();
					
					$fnotesIdssms = array ();
					$andRuleArraysms = array ();
					
					$fnotesIdstask = array ();
					$andRuleArraytask = array ();
					
					$rowModule = array ();
					
					$fnotesIds = array ();
					$ftnotesIds = array ();
					
					if ($facility ['config_taskform_status'] == '1') {
						
						$data3s = array (
								'facilities_id' => $facilities_id 
						);
						
						$frules = $this->model_form_form->getRules ( $data3s );
					}
					
					if ($frules) {
						date_default_timezone_set ( $timezone_name );
						
						$searchdate = date ( 'm-d-Y' );
						
						$current_date = date ( 'Y-m-d', strtotime ( 'now' ) );
						$current_time = date ( 'H:i', strtotime ( 'now' ) );
						
						//$country_info = $this->model_setting_country->getCountry ( $facility ['country_id'] );
						
						//$zone_info = $this->model_setting_zone->getZone ( $facility ['zone_id'] );
						
						foreach ( $frules as $rule ) {
							
							$allnotesIds = array ();
							$rulename = $rule ['rules_name'];
							
							// var_dump($rule['forms_id']);
							/* Trigger */
							if ($rule ['rules_operation'] == '1') {
								
								foreach ( $rule ['rules_module'] as $rules_module ) {
									
									// var_dump($rules_module);
									// echo "<hr>";
									
									$forms_fields_search = '';
									
									$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id,f.tags_id,f.design_forms from `" . DB_PREFIX . "notes` n ";
									$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
									
									$sqls .= 'where 1 = 1 ';
									
									// var_dump($rules_module['forms_fields_values']);
									if ($rules_module ['forms_fields_values'] != null && $rules_module ['forms_fields_values'] != "") {
										if (is_array ( $rules_module ['forms_fields_values'] )) {
											$i = 0;
											$sqls .= " and ( ";
											foreach ( $rules_module ['forms_fields_values'] as $key2 => $b ) {
												
												$forms_fields = explode ( "##", $rules_module ['forms_fields_value'] );
												
												// var_dump($forms_fields);
												
												$fkeyword = $forms_fields [1] . ':' . $b;
												
												if ($i != '0') {
													$sqls .= ' or ';
												}
												
												$sqls .= "  LOWER(f.rules_form_description) LIKE '%" . strtolower ( $fkeyword ) . "%' ";
												$i ++;
												
												$forms_fields_search .= $forms_fields [2] . ' | ' . $b . ' ';
											}
											
											$sqls .= " ) ";
											
											$i = 0;
										}
									}
									
									if ($rules_module ['forms_fields_search'] != null && $rules_module ['forms_fields_search'] != "") {
										
										$forms_fields = explode ( "##", $rules_module ['forms_fields_value'] );
										$fkeyword = $forms_fields [1] . ':' . $rules_module ['forms_fields_search'];
										$sqls .= " and ( LOWER(f.rules_form_description) LIKE '%" . strtolower ( $fkeyword ) . "%' ) ";
										$forms_fields_search = $forms_fields [2] . ' | ' . $rules_module ['forms_fields_search'];
									}
									
									if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
										$ddss [] = $facility ['task_facilities_ids'];
										$ddss [] = $facilities_id;
										$sssssdd = implode ( ",", $ddss );
										$faculities_ids = $sssssdd;
										$sqls .= " and n.facilities_id in  (" . $faculities_ids . ") ";
									} else {
										$sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
									}
									
									// $sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
									$sqls .= " and f.custom_form_type = '" . $rule ['forms_id'] . "'";
									$sqls .= " and f.is_discharge = '0'";
									$sqls .= " and n.form_trigger_snooze_dismiss != '2' ";
									
									$date = str_replace ( '-', '/', $searchdate );
									$res = explode ( "/", $date );
									$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
									
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sqls .= " and ( n.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or f.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
									
									$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
									
									// var_dump($forms_fields_search);
									// echo $sqls;
									// echo "<hr>";
									
									$query = $this->db->query ( $sqls );
									
									if ($query->num_rows) {
										// var_dump($query->rows);
										// echo "<hr>";
										foreach ( $query->rows as $result ) {
											
											$date_added = $result ['date_added'];
											// var_dump($form_due_date_after);
											
											$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
											
											$allnotesIds [] = array (
													'notes_id' => $result ['notes_id'],
													'rules_type' => $rule ['rules_name'],
													'rules_value' => $forms_fields_search,
													'user_roles' => '',
													'userids' => '',
													'date_added' => $date_added,
													'form_due_date_after' => '',
													'newdate' => $newdate,
													'new_time' => '',
													'form_due_date' => '',
													'rules_operation' => $rule ['rules_operation'] 
											);
											
											if (in_array ( '4', $rule ['rule_action'] )) {
												
												// var_dump($rule['rule_action_content']['task_random_id']);
												// echo "<hr>";
												
												$thestime6 = date ( 'H:i:s' );
												// var_dump($thestime6);
												$snooze_time7 = 60;
												$stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
												
												$sqls23 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "createtask` where form_due_date = '" . $rules_module ['form_due_date'] . "' and form_due_date_after = '" . $rules_module ['form_due_date_after'] . "' and rules_task = '" . $result ['notes_id'] . "' and form_rules_operation = '" . $rule ['rules_operation'] . "' ";
												
												$query4 = $this->db->query ( $sqls23 );
												
												if ($query4->row ['total'] == 0) {
													
													$addtask = array ();
													
													$snooze_time71 = 0;
													$thestime61 = date ( 'H:i:s' );
													
													$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
													
													if ($date_wise_task_time == '1') {
														$taskTime1 = $newdate;
														
														$thestime61 = date ( 'H:i:s', strtotime ( $taskTime1 ) );
														// var_dump($thestime6);
														$snooze_time71 = 60;
														$stime81 = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
													} else {
														$taskTime1 = $taskTime;
														$stime81 = $stime8;
													}
													
													if ($date_wise_task == '1') {
														$taskDate = date ( 'm-d-Y', strtotime ( $newdate ) );
														$end_recurrence_date = date ( 'm-d-Y', strtotime ( $newdate ) );
													} else {
														$taskDate = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
														$end_recurrence_date = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
													}
													
													$addtask ['taskDate'] = $taskDate;
													$addtask ['end_recurrence_date'] = $end_recurrence_date;
													$addtask ['recurrence'] = $rule ['rule_action_content'] ['recurrence'];
													$addtask ['recurnce_week'] = $rule ['rule_action_content'] ['recurnce_week'];
													$addtask ['recurnce_hrly'] = $rule ['rule_action_content'] ['recurnce_hrly'];
													$addtask ['recurnce_month'] = $rule ['rule_action_content'] ['recurnce_month'];
													$addtask ['recurnce_day'] = $rule ['rule_action_content'] ['recurnce_day'];
													$addtask ['taskTime'] = $taskTime1; // date('H:i:s');
													$addtask ['endtime'] = $stime81;
													$addtask ['description'] = $forms_fields_search . ' | ' . $rule ['rule_action_content'] ['description'] . ' ' . $result ['notes_description'];
													
													if ($rule ['rule_action_content'] ['assign_to']) {
														$addtask ['assignto'] = $rule ['rule_action_content'] ['assign_to'];
													} else {
														$addtask ['assignto'] = $result ['user_id'];
													}
													
													$addtask ['facilities_id'] = $facilities_id;
													$addtask ['task_form_id'] = $rule ['rule_action_content'] ['task_form_id'];
													if ($rule ['rule_action_content'] ['transport_tags'] != null && $rule ['rule_action_content'] ['transport_tags'] != "") {
														$addtask ['transport_tags'] = explode ( ',', $rule ['rule_action_content'] ['transport_tags'] );
													}
													
													$addtask ['pickup_facilities_id'] = $rule ['rule_action_content'] ['pickup_facilities_id'];
													$addtask ['pickup_locations_address'] = $rule ['rule_action_content'] ['pickup_locations_address'];
													$addtask ['pickup_locations_time'] = $rule ['rule_action_content'] ['pickup_locations_time'];
													
													$addtask ['dropoff_facilities_id'] = $rule ['rule_action_content'] ['dropoff_facilities_id'];
													$addtask ['dropoff_locations_address'] = $rule ['rule_action_content'] ['dropoff_locations_address'];
													$addtask ['dropoff_locations_time'] = $rule ['rule_action_content'] ['dropoff_locations_time'];
													
													$addtask ['tasktype'] = $rule ['rule_action_content'] ['tasktype'];
													$addtask ['numChecklist'] = $rule ['rule_action_content'] ['numChecklist'];
													$addtask ['task_alert'] = $rule ['rule_action_content'] ['task_alert'];
													$addtask ['alert_type_sms'] = $rule ['rule_action_content'] ['alert_type_sms'];
													$addtask ['alert_type_notification'] = $rule ['rule_action_content'] ['alert_type_notification'];
													$addtask ['alert_type_email'] = $rule ['rule_action_content'] ['alert_type_email'];
													$addtask ['rules_task'] = $result ['notes_id'];
													
													$addtask ['recurnce_hrly_recurnce'] = $rule ['rule_action_content'] ['recurnce_hrly_recurnce'];
													$addtask ['daily_endtime'] = $rule ['rule_action_content'] ['daily_endtime'];
													
													if ($rule ['rule_action_content'] ['daily_times'] != null && $rule ['rule_action_content'] ['daily_times'] != "") {
														$addtask ['daily_times'] = explode ( ',', $rule ['rule_action_content'] ['daily_times'] );
													}
													
													if ($rule ['rule_action_content'] ['medication_tags'] != null && $rule ['rule_action_content'] ['medication_tags'] != "") {
														$addtask ['medication_tags'] = explode ( ',', $rule ['rule_action_content'] ['medication_tags'] );
														
														$aa = urldecode ( $rule ['rule_action_content'] ['tags_medication_details_ids'] );
														$aa1 = unserialize ( $aa );
														
														$tags_medication_details_ids = array ();
														foreach ( $aa1 as $key => $mresult ) {
															$tags_medication_details_ids [$key] = $mresult;
														}
														$addtask ['tags_medication_details_ids'] = $tags_medication_details_ids;
													}
													
													if ($rule ['rule_action_content'] ['emp_tag_id']) {
														$addtask ['emp_tag_id'] = $rule ['rule_action_content'] ['emp_tag_id'];
													} elseif ($result ['tags_id'] > 0) {
														$addtask ['emp_tag_id'] = $result ['tags_id'];
													} else {
														
														/*
														 * $form_info = $this->model_form_form->getFormDatas($result['forms_id']);
														 *
														 * $design_forms = unserialize($form_info['design_forms']);
														 * //var_dump($design_forms);
														 * $tags_id = $design_forms[0][0]['tags_id'];
														 */
														
														$formdata = unserialize ( $result ['design_forms'] );
														
														foreach ( $formdata as $design_forms ) {
															foreach ( $design_forms as $key => $design_form ) {
																foreach ( $design_form as $key2 => $b ) {
																	
																	$arrss = explode ( "_1_", $key2 );
																	// var_dump($arrss);
																	// echo "<hr>";
																	if ($arrss [1] == 'tags_id') {
																		// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																		// var_dump($design_form[$arrss[0]]);
																		// echo "<hr>";
																		if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
																			if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
																				
																				// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																				
																				$tags_id = $design_form [$arrss [0] . '_1_' . $arrss [1]];
																			}
																		}
																	}
																	
																	if ($arrss [1] == 'tags_ids') {
																		// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																		if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
																			foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
																				
																				// var_dump($idst);
																				$tags_id = $idst;
																			}
																		}
																		// echo "<hr>";
																	}
																}
															}
														}
														$addtask ['emp_tag_id'] = $tags_id;
													}
													
													$addtask ['recurnce_hrly_perpetual'] = $rule ['rule_action_content'] ['recurnce_hrly_perpetual'];
													$addtask ['completion_alert'] = $rule ['rule_action_content'] ['completion_alert'];
													$addtask ['completion_alert_type_sms'] = $rule ['rule_action_content'] ['completion_alert_type_sms'];
													$addtask ['completion_alert_type_email'] = $rule ['rule_action_content'] ['completion_alert_type_email'];
													
													if ($rule ['rule_action_content'] ['user_roles'] != null && $rule ['rule_action_content'] ['user_roles'] != "") {
														$addtask ['user_roles'] = explode ( ',', $rule ['rule_action_content'] ['user_roles'] );
													}
													
													if ($rule ['rule_action_content'] ['userids'] != null && $rule ['rule_action_content'] ['userids'] != "") {
														$addtask ['userids'] = explode ( ',', $rule ['rule_action_content'] ['userids'] );
													}
													$addtask ['task_status'] = $rule ['rule_action_content'] ['task_status'];
													
													$addtask ['visitation_tag_id'] = $rule ['rule_action_content'] ['visitation_tag_id'];
													
													if ($rule ['rule_action_content'] ['visitation_tags'] != null && $rule ['rule_action_content'] ['visitation_tags'] != "") {
														$addtask ['visitation_tags'] = explode ( ',', $rule ['rule_action_content'] ['visitation_tags'] );
													}
													$addtask ['visitation_start_facilities_id'] = $rule ['rule_action_content'] ['visitation_start_facilities_id'];
													$addtask ['visitation_start_address'] = $rule ['rule_action_content'] ['visitation_start_address'];
													$addtask ['visitation_start_time'] = $rule ['rule_action_content'] ['visitation_start_time'];
													$addtask ['visitation_appoitment_facilities_id'] = $rule ['rule_action_content'] ['visitation_appoitment_facilities_id'];
													$addtask ['visitation_appoitment_address'] = $rule ['rule_action_content'] ['visitation_appoitment_address'];
													$addtask ['visitation_appoitment_time'] = $rule ['rule_action_content'] ['visitation_appoitment_time'];
													$addtask ['complete_endtime'] = $rule ['rule_action_content'] ['complete_endtime'];
													
													if ($rule ['rule_action_content'] ['completed_times'] != null && $rule ['rule_action_content'] ['completed_times'] != "") {
														$addtask ['completed_times'] = explode ( ',', $rule ['rule_action_content'] ['completed_times'] );
													}
													$addtask ['completed_alert'] = $rule ['rule_action_content'] ['completed_alert'];
													$addtask ['completed_late_alert'] = $rule ['rule_action_content'] ['completed_late_alert'];
													$addtask ['incomplete_alert'] = $rule ['rule_action_content'] ['incomplete_alert'];
													$addtask ['deleted_alert'] = $rule ['rule_action_content'] ['deleted_alert'];
													$addtask ['attachement_form'] = $rule ['rule_action_content'] ['attachement_form'];
													$addtask ['tasktype_form_id'] = $rule ['rule_action_content'] ['tasktype_form_id'];
													
													$addtask ['reminder_alert'] = $rule ['reminder_alert'];
													if ($rule ['reminderminus'] != null && $rule ['reminderminus'] != "") {
														$addtask ['reminderminus'] = explode ( ',', $rule ['reminderminus'] );
													}
													
													if ($rule ['reminderplus'] != null && $rule ['reminderplus'] != "") {
														$addtask ['reminderplus'] = explode ( ',', $rule ['reminderplus'] );
													}
													
													$addtask ['assign_to_type'] = $rule ['assign_to_type'];
													if ($rule ['user_assign_to'] != null && $rule ['user_assign_to'] != "") {
														$addtask ['assign_to'] = explode ( ',', $rule ['user_assign_to'] );
													}
													if ($rule ['user_role_assign_ids'] != null && $rule ['user_role_assign_ids'] != "") {
														$addtask ['user_role_assign_ids'] = explode ( ',', $rule ['user_role_assign_ids'] );
													}
													
													// var_dump($addtask);
													// echo "<hr>";
													
													// $sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
													// $this->db->query($sqlw);
													
													$task_id = $this->model_createtask_createtask->addcreatetask ( $addtask, $facilities_id );
													
													$fdat6a = array ();
													$fdat6a ['formrules_id'] = $rule ['rules_id'];
													$fdat6a ['form_due_date'] = $rules_module ['form_due_date'];
													$fdat6a ['form_due_date_after'] = $rules_module ['form_due_date_after'];
													$fdat6a ['task_id'] = $task_id;
													$fdat6a ['form_rules_operation'] = $rule ['rules_operation'];
													$this->model_createtask_createtask->updateformruletask ( $fdat6a );
												}
											}
										}
									}
								}
							}
							
							/* TASK */
							
							if ($rule ['rules_operation'] == '2') {
								
								$forms_fields_search = 'Task';
								foreach ( $rule ['onschedule_rules_module'] as $onschedule_rules_module ) {
									$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id,f.tags_id from `" . DB_PREFIX . "notes` n ";
									$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
									$sqls .= 'where 1 = 1 ';
									
									if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
										$ddss [] = $facility ['task_facilities_ids'];
										$ddss [] = $facilities_id;
										$sssssdd = implode ( ",", $ddss );
										$faculities_ids = $sssssdd;
										$sqls .= " and n.facilities_id in  (" . $faculities_ids . ") ";
									} else {
										$sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
									}
									
									// $sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
									$sqls .= " and f.custom_form_type = '" . $rule ['forms_id'] . "'";
									$sqls .= " and n.form_snooze_dismiss != '2' ";
									
									$date = str_replace ( '-', '/', $searchdate );
									$res = explode ( "/", $date );
									$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
									$startDate = $changedDate;
									$endDate = $changedDate;
									$sqls .= " and ( n.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or f.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
									$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
									
									// echo $sqls;
									// echo "<hr>";
									
									$query = $this->db->query ( $sqls );
									// var_dump($query->num_rows);
									
									if ($query->num_rows) {
										
										foreach ( $query->rows as $result ) {
											$date_added = $result ['date_added'];
											$form_due_date_after = $onschedule_rules_module ['form_due_date_after'];
											// var_dump($result['notes_id']);
											// var_dump($form_due_date_after);
											
											switch ($onschedule_rules_module ['form_due_date']) {
												case 'Month' :
													
													$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
													$date_wise_task = '1';
													
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => $newdate,
															'new_time' => '',
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
												
												case 'Days' :
													
													$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
													
													$date_wise_task = '1';
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => $newdate,
															'new_time' => '',
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
												
												case 'Date' :
													if ($form_due_date_after == $current_date) {
													}
													break;
												case 'Hours' :
													$newdate = date ( 'H:i', strtotime ( '+' . $form_due_date_after . ' hour', strtotime ( $date_added ) ) );
													
													$date_wise_task_time = '1';
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => '',
															'new_time' => $newdate,
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
												case 'Minutes' :
													$newdate = date ( 'H:i', strtotime ( '+' . $form_due_date_after . ' minutes', strtotime ( $date_added ) ) );
													
													$date_wise_task_time = '1';
													
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => '',
															'new_time' => $newdate,
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
												case 'is submitted' :
													$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => $newdate,
															'new_time' => '',
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
												
												case 'is updated' :
													$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
													$allnotesIds [] = array (
															'notes_id' => $result ['notes_id'],
															'rules_type' => $rule ['rules_name'],
															'rules_value' => $forms_fields_search,
															'user_roles' => $formalerts ['user_roles'],
															'userids' => $formalerts ['userids'],
															'date_added' => $date_added,
															'form_due_date_after' => $form_due_date_after,
															'newdate' => $newdate,
															'new_time' => '',
															'form_due_date' => $onschedule_rules_module ['form_due_date'],
															'onschedule_action' => $onschedule_rules_module ['onschedule_action'],
															'rules_operation' => $rule ['rules_operation'] 
													);
													
													break;
											}
											
											if ($onschedule_rules_module ['onschedule_action'] == '4') {
												// var_dump($newdate);
												// echo "<hr>";
												
												$thestime6 = date ( 'H:i:s' );
												// var_dump($thestime6);
												$snooze_time7 = 60;
												$stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
												
												$sqls23 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "createtask` where task_random_id = '" . $onschedule_rules_module ['task_random_id'] . "' and rules_task = '" . $result ['notes_id'] . "' and form_rules_operation = '" . $rule ['rules_operation'] . "' ";
												$query4 = $this->db->query ( $sqls23 );
												
												if ($query4->row ['total'] == 0) {
													
													$addtask = array ();
													
													$snooze_time71 = 0;
													$thestime61 = date ( 'H:i:s' );
													
													$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
													
													if ($date_wise_task_time == '1') {
														$taskTime1 = $taskTime;
														
														$thestime61 = date ( 'H:i:s', strtotime ( $taskTime1 ) );
														// var_dump($thestime6);
														$snooze_time71 = 60;
														$stime81 = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
													} else {
														$taskTime1 = $taskTime;
														$stime81 = $stime8;
													}
													
													if ($date_wise_task == '1') {
														$taskDate = date ( 'm-d-Y', strtotime ( $newdate ) );
														$end_recurrence_date = date ( 'm-d-Y', strtotime ( $newdate ) );
													} else {
														$taskDate = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
														$end_recurrence_date = date ( 'm-d-Y', strtotime ( $result ['date_added'] ) );
													}
													
													$addtask ['taskDate'] = $taskDate;
													
													if ($rule ['rules_operation_recurrence'] == '1') {
														$addtask ['end_recurrence_date'] = $end_recurrence_date;
													}
													
													if ($rule ['rules_operation_recurrence'] == '3') {
														
														if ($rule ['end_recurrence_date'] != null && $rule ['end_recurrence_date'] != "0000-00-00 00:00:00") {
															$addtask ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $rule ['end_recurrence_date'] ) );
														} else {
															$addtask ['end_recurrence_date'] = $end_recurrence_date;
														}
													}
													
													$addtask ['recurrence'] = $onschedule_rules_module ['recurrence'];
													$addtask ['recurnce_week'] = $onschedule_rules_module ['recurnce_week'];
													$addtask ['recurnce_hrly'] = $onschedule_rules_module ['recurnce_hrly'];
													$addtask ['recurnce_month'] = $onschedule_rules_module ['recurnce_month'];
													$addtask ['recurnce_day'] = $onschedule_rules_module ['recurnce_day'];
													$addtask ['taskTime'] = $taskTime1; // date('H:i:s');
													$addtask ['endtime'] = $stime81;
													$addtask ['description'] = $onschedule_rules_module ['description'] . ' ' . $result ['notes_description'];
													
													if ($onschedule_rules_module ['assign_to']) {
														$addtask ['assignto'] = $onschedule_rules_module ['assign_to'];
													} else {
														$addtask ['assignto'] = $result ['user_id'];
													}
													
													$addtask ['facilities_id'] = $facilities_id;
													$addtask ['task_form_id'] = $onschedule_rules_module ['task_form_id'];
													if ($onschedule_rules_module ['transport_tags'] != null && $onschedule_rules_module ['transport_tags'] != "") {
														$addtask ['transport_tags'] = explode ( ',', $onschedule_rules_module ['transport_tags'] );
													}
													
													$addtask ['pickup_facilities_id'] = $onschedule_rules_module ['pickup_facilities_id'];
													$addtask ['pickup_locations_address'] = $onschedule_rules_module ['pickup_locations_address'];
													$addtask ['pickup_locations_time'] = $onschedule_rules_module ['pickup_locations_time'];
													
													$addtask ['dropoff_facilities_id'] = $onschedule_rules_module ['dropoff_facilities_id'];
													$addtask ['dropoff_locations_address'] = $onschedule_rules_module ['dropoff_locations_address'];
													$addtask ['dropoff_locations_time'] = $onschedule_rules_module ['dropoff_locations_time'];
													
													$addtask ['tasktype'] = $onschedule_rules_module ['tasktype'];
													$addtask ['numChecklist'] = $onschedule_rules_module ['numChecklist'];
													$addtask ['task_alert'] = $onschedule_rules_module ['task_alert'];
													$addtask ['alert_type_sms'] = $onschedule_rules_module ['alert_type_sms'];
													$addtask ['alert_type_notification'] = $onschedule_rules_module ['alert_type_notification'];
													$addtask ['alert_type_email'] = $onschedule_rules_module ['alert_type_email'];
													$addtask ['rules_task'] = $result ['notes_id'];
													
													$addtask ['recurnce_hrly_recurnce'] = $onschedule_rules_module ['recurnce_hrly_recurnce'];
													$addtask ['daily_endtime'] = $onschedule_rules_module ['daily_endtime'];
													
													if ($onschedule_rules_module ['daily_times'] != null && $onschedule_rules_module ['daily_times'] != "") {
														$addtask ['daily_times'] = explode ( ',', $onschedule_rules_module ['daily_times'] );
													}
													
													if ($onschedule_rules_module ['medication_tags'] != null && $onschedule_rules_module ['medication_tags'] != "") {
														$addtask ['medication_tags'] = explode ( ',', $onschedule_rules_module ['medication_tags'] );
														
														$aa = urldecode ( $onschedule_rules_module ['tags_medication_details_ids'] );
														$aa1 = unserialize ( $aa );
														
														$tags_medication_details_ids = array ();
														foreach ( $aa1 as $key => $mresult ) {
															$tags_medication_details_ids [$key] = $mresult;
														}
														$addtask ['tags_medication_details_ids'] = $tags_medication_details_ids;
													}
													
													if ($onschedule_rules_module ['emp_tag_id']) {
														$addtask ['emp_tag_id'] = $onschedule_rules_module ['emp_tag_id'];
													} elseif ($result ['tags_id'] > 0) {
														$addtask ['emp_tag_id'] = $result ['tags_id'];
													} else {
														
														/*
														 * $form_info = $this->model_form_form->getFormDatas($result['forms_id']);
														 *
														 * $design_forms = unserialize($form_info['design_forms']);
														 * //var_dump($design_forms);
														 * $tags_id = $design_forms[0][0]['tags_id'];
														 */
														
														$formdata = unserialize ( $result ['design_forms'] );
														
														foreach ( $formdata as $design_forms ) {
															foreach ( $design_forms as $key => $design_form ) {
																foreach ( $design_form as $key2 => $b ) {
																	
																	$arrss = explode ( "_1_", $key2 );
																	// var_dump($arrss);
																	// echo "<hr>";
																	if ($arrss [1] == 'tags_id') {
																		// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																		// var_dump($design_form[$arrss[0]]);
																		// echo "<hr>";
																		if ($design_form [$arrss [0]] != null && $design_form [$arrss [0]] != "") {
																			if ($design_form [$arrss [0] . '_1_' . $arrss [1]] != null && $design_form [$arrss [0] . '_1_' . $arrss [1]] != "") {
																				
																				// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																				
																				$tags_id = $design_form [$arrss [0] . '_1_' . $arrss [1]];
																			}
																		}
																	}
																	
																	if ($arrss [1] == 'tags_ids') {
																		// var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																		if (! empty ( $design_form [$arrss [0] . '_1_' . $arrss [1]] )) {
																			foreach ( $design_form [$arrss [0] . '_1_' . $arrss [1]] as $idst ) {
																				
																				// var_dump($idst);
																				$tags_id = $idst;
																			}
																		}
																		// echo "<hr>";
																	}
																}
															}
														}
														$addtask ['emp_tag_id'] = $tags_id;
													}
													
													$addtask ['recurnce_hrly_perpetual'] = $onschedule_rules_module ['recurnce_hrly_perpetual'];
													$addtask ['completion_alert'] = $onschedule_rules_module ['completion_alert'];
													$addtask ['completion_alert_type_sms'] = $onschedule_rules_module ['completion_alert_type_sms'];
													$addtask ['completion_alert_type_email'] = $onschedule_rules_module ['completion_alert_type_email'];
													
													if ($onschedule_rules_module ['user_roles'] != null && $onschedule_rules_module ['user_roles'] != "") {
														$addtask ['user_roles'] = explode ( ',', $onschedule_rules_module ['user_roles'] );
													}
													
													if ($onschedule_rules_module ['userids'] != null && $onschedule_rules_module ['userids'] != "") {
														$addtask ['userids'] = explode ( ',', $onschedule_rules_module ['userids'] );
													}
													$addtask ['task_status'] = $onschedule_rules_module ['task_status'];
													
													$addtask ['visitation_tag_id'] = $onschedule_rules_module ['visitation_tag_id'];
													
													if ($onschedule_rules_module ['visitation_tags'] != null && $onschedule_rules_module ['visitation_tags'] != "") {
														$addtask ['visitation_tags'] = explode ( ',', $onschedule_rules_module ['visitation_tags'] );
													}
													$addtask ['visitation_start_facilities_id'] = $onschedule_rules_module ['visitation_start_facilities_id'];
													$addtask ['visitation_start_address'] = $onschedule_rules_module ['visitation_start_address'];
													$addtask ['visitation_start_time'] = $onschedule_rules_module ['visitation_start_time'];
													$addtask ['visitation_appoitment_facilities_id'] = $onschedule_rules_module ['visitation_appoitment_facilities_id'];
													$addtask ['visitation_appoitment_address'] = $onschedule_rules_module ['visitation_appoitment_address'];
													$addtask ['visitation_appoitment_time'] = $onschedule_rules_module ['visitation_appoitment_time'];
													$addtask ['complete_endtime'] = $onschedule_rules_module ['complete_endtime'];
													
													if ($onschedule_rules_module ['completed_times'] != null && $onschedule_rules_module ['completed_times'] != "") {
														$addtask ['completed_times'] = explode ( ',', $onschedule_rules_module ['completed_times'] );
													}
													$addtask ['completed_alert'] = $onschedule_rules_module ['completed_alert'];
													$addtask ['completed_late_alert'] = $onschedule_rules_module ['completed_late_alert'];
													$addtask ['incomplete_alert'] = $onschedule_rules_module ['incomplete_alert'];
													$addtask ['deleted_alert'] = $onschedule_rules_module ['deleted_alert'];
													$addtask ['attachement_form'] = $onschedule_rules_module ['attachement_form'];
													$addtask ['tasktype_form_id'] = $onschedule_rules_module ['tasktype_form_id'];
													
													$addtask ['reminder_alert'] = $onschedule_rules_module ['reminder_alert'];
													if ($onschedule_rules_module ['reminderminus'] != null && $onschedule_rules_module ['reminderminus'] != "") {
														$addtask ['reminderminus'] = explode ( ',', $onschedule_rules_module ['reminderminus'] );
													}
													
													if ($onschedule_rules_module ['reminderplus'] != null && $onschedule_rules_module ['reminderplus'] != "") {
														$addtask ['reminderplus'] = explode ( ',', $onschedule_rules_module ['reminderplus'] );
													}
													
													$addtask ['assign_to_type'] = $onschedule_rules_module ['assign_to_type'];
													if ($onschedule_rules_module ['user_assign_to'] != null && $onschedule_rules_module ['user_assign_to'] != "") {
														$addtask ['assign_to'] = explode ( ',', $onschedule_rules_module ['user_assign_to'] );
													}
													if ($onschedule_rules_module ['user_role_assign_ids'] != null && $onschedule_rules_module ['user_role_assign_ids'] != "") {
														$addtask ['user_role_assign_ids'] = explode ( ',', $onschedule_rules_module ['user_role_assign_ids'] );
													}
													
													// $sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
													// $this->db->query($sqlw);
													
													$task_id = $this->model_createtask_createtask->addcreatetask ( $addtask, $facilities_id );
													
													$fdat6a = array ();
													$fdat6a ['formrules_id'] = $rule ['rules_id'];
													$fdat6a ['task_random_id'] = $onschedule_rules_module ['task_random_id'];
													$fdat6a ['task_id'] = $task_id;
													$fdat6a ['rules_operation_recurrence'] = $rule ['rules_operation_recurrence'];
													$fdat6a ['form_rules_operation'] = $rule ['rules_operation'];
													$this->model_createtask_createtask->updateformruletask2 ( $fdat6a );
												}
											}
										}
									}
								}
							}
							
							/**
							 * ************** ACTION ********************
							 */
							
							// var_dump($allnotesIds);
							// echo "<hr>";
							
							// var_dump($allnotesIds);
							
							// die;
							
							if ($allnotesIds != null && $allnotesIds != "") {
								
								if (in_array ( '3', $rule ['rule_action'] )) {
									
									// var_dump($allnotesIds);
									
									foreach ( $allnotesIds as $allnotesId ) {
										$fnotesIds [] = $allnotesId ['notes_id'];
										$andRuleArray [] = $rule ['rules_name'] . ' ' . $allnotesId ['rules_value'];
									}
								}
								
								if (in_array ( '4', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										if ($allnotesId ['rules_operation'] == '1') {
											$this->model_notes_notes->updatenotesrule ( $allnotesId ['notes_id'] );
										}
										
										if ($allnotesId ['rules_operation'] == '2') {
											$this->model_notes_notes->updatenotesruletask ( $allnotesId ['notes_id'] );
										}
									}
								}
								
								if ($onschedule_rules_module ['onschedule_action'] == '4') {
									foreach ( $allnotesIds as $allnotesId ) {
										if ($allnotesId ['rules_operation'] == '1') {
											$this->model_notes_notes->updatenotesrule ( $allnotesId ['notes_id'] );
										}
										
										if ($allnotesId ['rules_operation'] == '2') {
											$this->model_notes_notes->updatenotesruletask ( $allnotesId ['notes_id'] );
										}
									}
								}
								
								if (in_array ( '1', $rule ['rule_action'] )) {
									
									foreach ( $allnotesIds as $allnotesId ) {
										
										if ($allnotesId ['onschedule_action'] != '4') {
											
											$note_info = $this->model_notes_notes->getnotes_by_form ( $allnotesId ['notes_id'] );
											
											if ($note_info ['notes_id'] != null && $note_info ['notes_id'] != "") {
												
												/*
												 * $sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
												 * $sqls2 .= 'where 1 = 1 ';
												 * $sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
												 * $sqls2 .= " and form_send_sms = '0'";
												 *
												 * $query = $this->db->query($sqls2);
												 *
												 * $note_info = $query->row;
												 *
												 * if ($query->num_rows) {
												 */
												$message = "Rules Created \n";
												$message .= date ( 'h:i A', strtotime ( $note_info ['notetime'] ) ) . "\n";
												$message .= $rule ['rules_name'] . '-' . $allnotesId ['rules_type'] . '-' . $allnotesId ['rules_value'] . "\n";
												$message .= substr ( $note_info ['notes_description'], 0, 150 ) . ((strlen ( $note_info ['notes_description'] ) > 150) ? '..' : '');
												
												// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
												$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
												
												if ($user_info ['phone_number'] != null && $user_info ['phone_number'] != '0') {
													$phone_number = $user_info ['phone_number'];
												}
												
												$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
												$query = $this->db->query ( $sql3e );
												
												$sdata = array ();
												$sdata ['message'] = $message;
												$sdata ['phone_number'] = $phone_number;
												$sdata ['facilities_id'] = $facilities_id;
												$response = $this->model_api_smsapi->sendsms ( $sdata );
												
												if ($rule ['rule_action_content'] ['auser_roles'] != null && $rule ['rule_action_content'] ['auser_roles'] != "") {
													
													$user_roles1 = $rule ['rule_action_content'] ['auser_roles'];
													
													foreach ( $user_roles1 as $user_role ) {
														$urole = array ();
														$urole ['user_group_id'] = $user_role;
														$tusers = $this->model_user_user->getUsers ( $urole );
														
														if ($tusers) {
															foreach ( $tusers as $tuser ) {
																if ($tuser ['phone_number'] != null && $tuser ['phone_number'] != "") {
																	$number = $tuser ['phone_number'];
																	
																	$sdata = array ();
																	$sdata ['message'] = $message;
																	$sdata ['phone_number'] = $tuser ['phone_number'];
																	$sdata ['facilities_id'] = $facilities_id;
																	$response = $this->model_api_smsapi->sendsms ( $sdata );
																}
															}
														}
													}
												}
												
												if ($rule ['rule_action_content'] ['auserids'] != null && $rule ['rule_action_content'] ['auserids'] != "") {
													$userids1 = $rule ['rule_action_content'] ['auserids'];
													
													foreach ( $userids1 as $userid ) {
														$user_info = $this->model_user_user->getUserbyupdate ( $userid );
														if ($user_info) {
															if ($user_info ['phone_number'] != 0) {
																$number = $user_info ['phone_number'];
																
																$sdata = array ();
																$sdata ['message'] = $message;
																$sdata ['phone_number'] = $user_info ['phone_number'];
																$sdata ['facilities_id'] = $facilities_id;
																$response = $this->model_api_smsapi->sendsms ( $sdata );
															}
														}
													}
												}
											}
										}
									}
								}
								
								if (in_array ( '2', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										if ($allnotesId ['onschedule_action'] != '4') {
											/*
											 * $sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
											 * $sqls2 .= 'where 1 = 1 ';
											 * $sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
											 * $sqls2 .= " and form_send_email = '0'";
											 *
											 * $query = $this->db->query($sqls2);
											 *
											 * $note_info = $query->row;
											 *
											 * if ($query->num_rows) {
											 */
											$note_info = $this->model_notes_notes->getnotes_by_form2 ( $allnotesId ['notes_id'] );
											
											if ($note_info ['notes_id'] != null && $note_info ['notes_id'] != "") {
												
												// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
												$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
												
												$facilityDetails ['username'] = $result ['user_id'];
												$facilityDetails ['email'] = $user_info ['email'];
												$facilityDetails ['phone_number'] = $user_info ['phone_number'];
												$facilityDetails ['sms_number'] = $facility ['sms_number'];
												$facilityDetails ['facility'] = $facility ['facility'];
												$facilityDetails ['address'] = $facility ['address'];
												$facilityDetails ['location'] = $facility ['location'];
												$facilityDetails ['zipcode'] = $facility ['zipcode'];
												$facilityDetails ['contry_name'] = $country_info ['name'];
												$facilityDetails ['zone_name'] = $zone_info ['name'];
												$facilityDetails ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
												$facilityDetails ['rules_name'] = $rule ['rules_name'];
												$facilityDetails ['rules_type'] = $allnotesId ['rules_type'];
												$facilityDetails ['rules_value'] = $allnotesId ['rules_value'];
												
												$message33 = "";
												
												$message33 .= $this->sendEmailtemplate ( $note_info, $rule ['rules_name'], $allnotesId ['rules_type'], $allnotesId ['rules_value'], $facilityDetails );
												
												$useremailids = array ();
												
												$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
												$query = $this->db->query ( $sql3e );
												
												if ($rule ['rule_action_content'] ['auser_roles'] != null && $rule ['rule_action_content'] ['auser_roles'] != "") {
													
													$user_roles1 = $rule ['rule_action_content'] ['auser_roles'];
													
													foreach ( $user_roles1 as $user_role ) {
														$urole = array ();
														$urole ['user_group_id'] = $user_role;
														$tusers = $this->model_user_user->getUsers ( $urole );
														
														if ($tusers) {
															foreach ( $tusers as $tuser ) {
																if ($tuser ['email'] != null && $tuser ['email'] != "") {
																	
																	$useremailids [] = $tuser ['email'];
																}
															}
														}
													}
												}
												
												if ($rule ['rule_action_content'] ['auserids'] != null && $rule ['rule_action_content'] ['auserids'] != "") {
													$userids1 = $rule ['rule_action_content'] ['auserids'];
													
													foreach ( $userids1 as $userid ) {
														$user_info = $this->model_user_user->getUserbyupdate ( $userid );
														if ($user_info) {
															if ($user_info ['email']) {
																$useremailids [] = $user_info ['email'];
															}
														}
													}
												}
												
												if ($user_info ['email'] != null && $user_info ['email'] != "") {
													$user_email = $user_info ['email'];
												}
												
												$edata = array ();
												$edata ['message'] = $message33;
												$edata ['subject'] = 'This is an Automated Alert Email.';
												$edata ['useremailids'] = $useremailids;
												$edata ['user_email'] = $user_email;
												
												$email_status = $this->model_api_emailapi->sendmail ( $edata );
											}
										}
									}
								}
								
								if (in_array ( '5', $rule ['rule_action'] )) {
									
									foreach ( $allnotesIds as $allnotesId ) {
										if ($rule ['rule_action_content'] ['highlighter_id'] != null && $rule ['rule_action_content'] ['highlighter_id'] != "") {
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$this->model_notes_notes->updateNoteHigh ( $allnotesId ['notes_id'], $rule ['rule_action_content'] ['highlighter_id'], $update_date );
										}
									}
								}
								
								if (in_array ( '6', $rule ['rule_action'] )) {
									foreach ( $allnotesIds as $allnotesId ) {
										if ($rule ['rule_action_content'] ['color_id'] != null && $rule ['rule_action_content'] ['color_id'] != "") {
											$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
											$this->model_notes_notes->updateNoteColor ( $allnotesId ['notes_id'], $rule ['rule_action_content'] ['color_id'], $update_date );
										}
									}
								}
							}
						}
					}
					
					$fnotesIdssms = array_unique ( $fnotesIdssms );
					if ($fnotesIdssms != null && $fnotesIdssms != "") {
						foreach ( $fnotesIdssms as $notes_id ) {
							
							$note_info = $this->model_notes_notes->getnotes_by_form ( $notes_id );
							
							if ($note_info ['notes_id'] != null && $note_info ['notes_id'] != "") {
								
								/*
								 * $sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_sms = '0' ";
								 * $query = $this->db->query($sqlsnote);
								 *
								 * $note_info = $query->row;
								 * if($note_info != null && $note_info != ""){
								 */
								
								// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
								$facility = $this->model_facilities_facilities->getfacilities ( $note_info ['facilities_id'] );
								//$country_info = $this->model_setting_country->getCountry ( $facility ['country_id'] );
								
								$message = "Form Rule Created \n";
								$message .= date ( 'h:i A', strtotime ( $note_info ['notetime'] ) ) . "\n";
								$message .= $rulename . '-Form Rule-' . $andRuleArraysms [$note_info ['notes_id']] . "\n";
								$message .= $note_info ['notes_description'];
								if ($user_info ['phone_number'] != null && $user_info ['phone_number'] != '0') {
									$phone_number = $user_info ['phone_number'];
								}
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '" . $note_info ['notes_id'] . "'";
								$query = $this->db->query ( $sql3e );
								
								$sdata = array ();
								$sdata ['message'] = $message;
								$sdata ['phone_number'] = $phone_number;
								$sdata ['facilities_id'] = $facilities_id;
								$response = $this->model_api_smsapi->sendsms ( $sdata );
							}
						}
					}
					
					/**
					 * **EMAIL CODE ***
					 */
					$fnotesIdsemail = array_unique ( $fnotesIdsemail );
					// var_dump($fnotesIdsemail);
					if ($fnotesIdsemail != null && $fnotesIdsemail != "") {
						foreach ( $fnotesIdsemail as $notes_id ) {
							/*
							 * $sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_email = '0' ";
							 * $query = $this->db->query($sqlsnote);
							 *
							 * $note_info = $query->row;
							 * if($note_info != null && $note_info != ""){
							 */
							$note_info = $this->model_notes_notes->getnotes_by_form2 ( $notes_id );
							
							if ($note_info ['notes_id'] != null && $note_info ['notes_id'] != "") {
								
								// $user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes ( $note_info ['user_id'], $note_info ['facilities_id'] );
								$facility = $this->model_facilities_facilities->getfacilities ( $note_info ['facilities_id'] );
								//$country_info = $this->model_setting_country->getCountry ( $facility ['country_id'] );
								//$zone_info = $this->model_setting_zone->getZone ( $facility ['zone_id'] );
								$facilityDetails ['username'] = $note_info ['user_id'];
								$facilityDetails ['email'] = $user_info ['email'];
								$facilityDetails ['phone_number'] = $user_info ['phone_number'];
								$facilityDetails ['sms_number'] = $facility ['sms_number'];
								$facilityDetails ['facility'] = $facility ['facility'];
								$facilityDetails ['address'] = $facility ['address'];
								$facilityDetails ['location'] = $facility ['location'];
								$facilityDetails ['zipcode'] = $facility ['zipcode'];
								$facilityDetails ['contry_name'] = $country_info ['name'];
								$facilityDetails ['zone_name'] = $zone_info ['name'];
								$facilityDetails ['href'] = $this->url->link ( 'common/login', '', 'SSL' );
								$facilityDetails ['rules_name'] = $rulename;
								$facilityDetails ['rules_type'] = 'Form Rule';
								$facilityDetails ['rules_value'] = $andRuleArrayemail [$note_info ['notes_id']];
								
								$message33 = "";
								$message33 .= $this->sendEmailtemplate1 ( $note_info, $rulename, 'Form Rule', $andRuleArrayemail [$note_info ['notes_id']], $facilityDetails );
								
								if ($user_info ['email'] != null && $user_info ['email'] != "") {
									$user_email = $user_info ['email'];
								}
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '" . $note_info ['notes_id'] . "'";
								$query = $this->db->query ( $sql3e );
								
								$edata = array ();
								$edata ['message'] = $message33;
								$edata ['subject'] = 'This is an Automated Alert Email.';
								$edata ['user_email'] = $user_email;
								
								$email_status = $this->model_api_emailapi->sendmail ( $edata );
							}
						}
					}
					
					// var_dump($facilityDetails);
					// var_dump($json['rulenotes']);
					
					// echo "<hr>";
					// var_dump($andRuleArray);
					// echo "<hr>";
					// var_dump($rowModule);
					$ftnotesIds = array_unique ( $ftnotesIds );
					// echo "<hr>";
					// var_dump($ftnotesIds);
					// die;
					
					if ($ftnotesIds != null && $ftnotesIds != "") {
						$this->load->model ( 'createtask/createtask' );
						$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (" . implode ( ',', $ftnotesIds ) . ") and status = '1' and text_color_cut = '0' and `form_snooze_dismiss` != '2' and `form_create_task` = '0' ";
						
						$query2 = $this->db->query ( $sqlst2 );
						
						$thestime6 = date ( 'H:i:s' );
						// var_dump($thestime6);
						$snooze_time7 = 60;
						$stime8 = date ( "h:i A", strtotime ( "+" . $snooze_time7 . " minutes", strtotime ( $thestime6 ) ) );
						// var_dump($stime8);
						
						foreach ( $query2->rows as $tresult ) {
							
							$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '" . $tresult ['notes_id'] . "' ";
							$query4 = $this->db->query ( $sqls23 );
							if ($query4->num_rows == 0) {
								$addtask = array ();
								
								/*
								 * if($rowModule['taskTime'] != null && $rowModule['taskTime'] != ""){
								 * $snooze_time71 = 0;
								 * $thestime61 = $rowModule['taskTime'];
								 * }else{
								 * $snooze_time71 = 10;
								 * $thestime61 = date('H:i:s');
								 * }
								 */
								
								$snooze_time71 = 0;
								$thestime61 = date ( 'H:i:s' );
								
								$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
								
								if ($rowModule ['date_wise_task_time'] == '1') {
									$taskTime1 = $rowModule ['taskTime'];
									
									$thestime61 = date ( 'H:i:s', strtotime ( $taskTime1 ) );
									// var_dump($thestime6);
									$snooze_time71 = 60;
									$stime81 = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
								} else {
									$taskTime1 = $taskTime;
									$stime81 = $stime8;
								}
								
								if ($rowModule ['date_wise_task'] == '1') {
									$taskDate = $rowModule ['taskDate'];
									$end_recurrence_date = $rowModule ['end_recurrence_date'];
								} else {
									$taskDate = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
									$end_recurrence_date = date ( 'm-d-Y', strtotime ( $tresult ['date_added'] ) );
								}
								
								$addtask ['taskDate'] = $taskDate;
								$addtask ['end_recurrence_date'] = $end_recurrence_date;
								$addtask ['recurrence'] = $rowModule ['recurrence'];
								$addtask ['recurnce_week'] = $rowModule ['recurnce_week'];
								$addtask ['recurnce_hrly'] = $rowModule ['recurnce_hrly'];
								$addtask ['recurnce_month'] = $rowModule ['recurnce_month'];
								$addtask ['recurnce_day'] = $rowModule ['recurnce_day'];
								$addtask ['taskTime'] = $taskTime1; // date('H:i:s');
								$addtask ['endtime'] = $stime81;
								$addtask ['description'] = $rowModule ['description'] . ' ' . $tresult ['notes_description'];
								
								if ($rowModule ['assign_to']) {
									$addtask ['assignto'] = $rowModule ['assign_to'];
								} else {
									$addtask ['assignto'] = $result ['user_id'];
								}
								
								$addtask ['facilities_id'] = $rowModule ['facilities_id'];
								$addtask ['task_form_id'] = $rowModule ['task_form_id'];
								if ($rowModule ['transport_tags'] != null && $rowModule ['transport_tags'] != "") {
									$addtask ['transport_tags'] = explode ( ',', $rowModule ['transport_tags'] );
								}
								
								$addtask ['pickup_facilities_id'] = $rowModule ['pickup_facilities_id'];
								$addtask ['pickup_locations_address'] = $rowModule ['pickup_locations_address'];
								$addtask ['pickup_locations_time'] = $rowModule ['pickup_locations_time'];
								
								$addtask ['dropoff_facilities_id'] = $rowModule ['dropoff_facilities_id'];
								$addtask ['dropoff_locations_address'] = $rowModule ['dropoff_locations_address'];
								$addtask ['dropoff_locations_time'] = $rowModule ['dropoff_locations_time'];
								
								$addtask ['tasktype'] = $rowModule ['tasktype'];
								$addtask ['numChecklist'] = $rowModule ['numChecklist'];
								$addtask ['task_alert'] = $rowModule ['task_alert'];
								$addtask ['alert_type_sms'] = $rowModule ['alert_type_sms'];
								$addtask ['alert_type_notification'] = $rowModule ['alert_type_notification'];
								$addtask ['alert_type_email'] = $rowModule ['alert_type_email'];
								$addtask ['rules_task'] = $tresult ['notes_id'];
								
								$addtask ['recurnce_hrly_recurnce'] = $rowModule ['recurnce_hrly_recurnce'];
								$addtask ['daily_endtime'] = $rowModule ['daily_endtime'];
								
								if ($rowModule ['daily_times'] != null && $rowModule ['daily_times'] != "") {
									$addtask ['daily_times'] = explode ( ',', $rowModule ['daily_times'] );
								}
								
								if ($rowModule ['medication_tags'] != null && $rowModule ['medication_tags'] != "") {
									$addtask ['medication_tags'] = explode ( ',', $rowModule ['medication_tags'] );
									
									$aa = urldecode ( $rowModule ['tags_medication_details_ids'] );
									$aa1 = unserialize ( $aa );
									
									$tags_medication_details_ids = array ();
									foreach ( $aa1 as $key => $mresult ) {
										$tags_medication_details_ids [$key] = $mresult;
									}
									$addtask ['tags_medication_details_ids'] = $tags_medication_details_ids;
								}
								
								$addtask ['emp_tag_id'] = $rowModule ['emp_tag_id'];
								
								$addtask ['recurnce_hrly_perpetual'] = $rowModule ['recurnce_hrly_perpetual'];
								$addtask ['completion_alert'] = $rowModule ['completion_alert'];
								$addtask ['completion_alert_type_sms'] = $rowModule ['completion_alert_type_sms'];
								$addtask ['completion_alert_type_email'] = $rowModule ['completion_alert_type_email'];
								
								if ($rowModule ['user_roles'] != null && $rowModule ['user_roles'] != "") {
									$addtask ['user_roles'] = explode ( ',', $rowModule ['user_roles'] );
								}
								
								if ($rowModule ['userids'] != null && $rowModule ['userids'] != "") {
									$addtask ['userids'] = explode ( ',', $rowModule ['userids'] );
								}
								$addtask ['task_status'] = $rowModule ['task_status'];
								
								$addtask ['visitation_tag_id'] = $rowModule ['visitation_tag_id'];
								
								if ($rowModule ['visitation_tags'] != null && $rowModule ['visitation_tags'] != "") {
									$addtask ['visitation_tags'] = explode ( ',', $rowModule ['visitation_tags'] );
								}
								$addtask ['visitation_start_facilities_id'] = $rowModule ['visitation_start_facilities_id'];
								$addtask ['visitation_start_address'] = $rowModule ['visitation_start_address'];
								$addtask ['visitation_start_time'] = $rowModule ['visitation_start_time'];
								$addtask ['visitation_appoitment_facilities_id'] = $rowModule ['visitation_appoitment_facilities_id'];
								$addtask ['visitation_appoitment_address'] = $rowModule ['visitation_appoitment_address'];
								$addtask ['visitation_appoitment_time'] = $rowModule ['visitation_appoitment_time'];
								$addtask ['complete_endtime'] = $rowModule ['complete_endtime'];
								
								if ($rowModule ['completed_times'] != null && $rowModule ['completed_times'] != "") {
									$addtask ['completed_times'] = explode ( ',', $rowModule ['completed_times'] );
								}
								$addtask ['completed_alert'] = $rowModule ['completed_alert'];
								$addtask ['completed_late_alert'] = $rowModule ['completed_late_alert'];
								$addtask ['incomplete_alert'] = $rowModule ['incomplete_alert'];
								$addtask ['deleted_alert'] = $rowModule ['deleted_alert'];
								$addtask ['attachement_form'] = $rowModule ['attachement_form'];
								$addtask ['tasktype_form_id'] = $rowModule ['tasktype_form_id'];
								
								$addtask ['reminder_alert'] = $rowModule ['reminder_alert'];
								if ($rowModule ['reminderminus'] != null && $rowModule ['reminderminus'] != "") {
									$addtask ['reminderminus'] = explode ( ',', $rowModule ['reminderminus'] );
								}
								
								if ($rowModule ['reminderplus'] != null && $rowModule ['reminderplus'] != "") {
									$addtask ['reminderplus'] = explode ( ',', $rowModule ['reminderplus'] );
								}
								
								$addtask ['assign_to_type'] = $rowModule ['assign_to_type'];
								if ($rowModule ['user_assign_to'] != null && $rowModule ['user_assign_to'] != "") {
									$addtask ['assign_to'] = explode ( ',', $rowModule ['user_assign_to'] );
								}
								
								if ($rowModule ['user_role_assign_ids'] != null && $rowModule ['user_role_assign_ids'] != "") {
									$addtask ['user_role_assign_ids'] = explode ( ',', $rowModule ['user_role_assign_ids'] );
								}
								
								$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1' where notes_id ='" . $tresult ['notes_id'] . "'";
								$this->db->query ( $sqlw );
								
								$task_id = $this->model_createtask_createtask->addcreatetask ( $addtask, $facilities_id );
								$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '" . $rowModule ['rules_id'] . "' where id ='" . $task_id . "'";
								$this->db->query ( $sqlw2 );
								
								$rowModule = array ();
							}
						}
					}
					
					$fnotesIds = array_unique ( $fnotesIds );
					
					if ($fnotesIds != null && $fnotesIds != "") {
						
						$thestime = date ( 'H:i:s' );
						// var_dump($thestime);
						$snooze_time = 0;
						$stime = date ( "H:i:s", strtotime ( "+" . $snooze_time . " minutes", strtotime ( $thestime ) ) );
						
						// var_dump($stime);
						
						$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (" . implode ( ',', $fnotesIds ) . ") and form_snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
						
						$query = $this->db->query ( $sqls2 );
						
						$config_tag_status = $facility ['config_tag_status'];
						
						if ($query->num_rows) {
							
							foreach ( $query->rows as $result ) {
								
								// echo $thestime.'<='.$result['snooze_time'];
								if ($thestime >= $result ['form_snooze_time']) {
									$highlighterData = $this->model_setting_highlighter->gethighlighter ( $result ['highlighter_id'] );
									// $user_info = $this->model_user_user->getUserByUsername($result['user_id']);
									$user_info = $this->model_user_user->getUserByUsernamebynotes ( $result ['user_id'], $result ['facilities_id'] );
									
									if ($config_tag_status == '1') {
										if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
											$tagdata = $this->model_notes_tags->getTagbyEMPID ( $result ['emp_tag_id'] );
											$privacy = $tagdata ['privacy'];
											
											$emp_tag_id = $result ['emp_tag_id'] . ': ';
										} else {
											$emp_tag_id = '';
											$privacy = '';
										}
									}
									
									if ($privacy == '2') {
										if ($this->session->data ['unloack_success'] == '1') {
											$notes_description = $keyImageSrc1 . '&nbsp;' . $emp_tag_id . $result ['notes_description'];
										} else {
											$notes_description = $emp_tag_id;
										}
									} else {
										$notes_description = $keyImageSrc1 . '&nbsp;' . $emp_tag_id . $result ['notes_description'];
									}
									
									if (! empty ( $andRuleArray )) {
										$note_d = $andRuleArray [0] . ' ';
									}
									
									$json ['formrules'] [] = array (
											'notes_id' => $result ['notes_id'],
											'rules_id' => '',
											'highlighter_value' => $highlighterData ['highlighter_value'],
											'notes_description' => $note_d . $notes_description,
											'date_added' => date ( 'j, F Y', strtotime ( $result ['date_added'] ) ),
											'note_date' => date ( 'j, F Y h:i A', strtotime ( $result ['note_date'] ) ),
											'notetime' => date ( 'h:i A', strtotime ( $result ['notetime'] ) ),
											'username' => $result ['user_id'],
											'email' => $user_info ['email'],
											'facility' => $facility ['facility'],
											'facilities_info' => $facilities_info,
											
											'android_audio_file' => $facility_android_audio_file,
											'ios_audio_file' => $facility_ios_audio_file 
									);
									
									$json ['total'] = '1';
									$json ['status'] = true;
								}
							}
						}
					}
					
					// var_dump($current_date_user);
					// var_dump($facilities_info);
					// var_dump($json);
					
					$json ['facility_setting'] = array ();
					$json ['updated_users'] = array ();
					$json ['updated_tags'] = array ();
					$json ['updated_keywords'] = array ();
					$json ['updated_hlighters'] = array ();
					$udata7 = array ();
					$udata7 = array (
							'facilities_id' => $facilities_id,
							'current_date_user' => $current_date_user 
					);
					/*
					 * $this->load->model('api/updatesetting');
					 * $this->load->model('setting/image');
					 * $this->load->model('notes/image');
					 * $facility_detail = $this->model_api_updatesetting->getfacilitiessetting($udata7);
					 * $updated_users = $this->model_api_updatesetting->getupdateusers($udata7);
					 * $updated_tags = $this->model_api_updatesetting->getupdatetags($udata7);
					 * $updated_keywords = $this->model_api_updatesetting->getupdatekeywords($udata7);
					 * $updated_hlighters = $this->model_api_updatesetting->getupdatehlighters($udata7);
					 * //var_dump($facility_detail);
					 *
					 * $json['facility_setting'] = $facility_detail;
					 * $json['updated_users'] = $updated_users;
					 * $json['updated_tags'] = $updated_tags;
					 * $json['updated_keywords'] = $updated_keywords;
					 * $json['updated_hlighters'] = $updated_hlighters;
					 */
					
					if ($this->config->get ( 'active_notification' ) == '1') {
						if ((! empty ( $json ['tasklits'] )) || (! empty ( $json ['rulenotes'] )) || (! empty ( $json ['formrules'] ))) {
							
							$this->load->model ( 'api/notify' );
							// var_dump($json);die;
							// var_dump($device_detail);
							// foreach($device_details as $device_detail){
							$this->model_api_notify->sendnotification ( $json, $registration_id );
							// }
						}
					}
				}
			}
		}
		
		
		} catch ( Exception $e ) {
			
			var_dump($e->getMessage () );
			
		}
		
		
		
		
	}
	
	
	
	public function acanotify() {
		
		
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		
		$this->load->model ( 'setting/highlighter' );
	
		$this->load->model ( 'setting/timezone' );
		
		$this->load->model ( 'notes/tags' );
		
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'user/user_group' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		
		
		$this->load->model ( 'api/emailapi' );
		$this->load->model ( 'api/smsapi' );
		
		$this->load->model ( 'customer/customer' );
		$this->load->model ( 'setting/keywords' );
		$this->load->model ( 'setting/locations' );
		
		$this->language->load ( 'notes/notes' );	
		
		$this->load->model('notes/acarules');
		
		//$acas = $this->model_notes_acarules->getacaruleautonote();
		$count=1;
		foreach ( $acas as $aca ) {
			//echo '<pre>aaa'; print_r($aca); echo '</pre>'; //die;	
			$faci_arr = explode(',',$aca['facilities_id']);
			foreach($faci_arr AS $frow){
				$data = array();
				$data['keyword_id'] = $aca['keyword_id'];
				$data['rules_type'] = $aca['rules_type'];
				$data['rules_start_time'] = $aca['rules_start_time'];
				$data['rules_end_time'] = $aca['rules_end_time'];
				$data['rules_operation'] = $aca['rules_operation'];
				$data['recurnce_week_from'] = $aca['recurnce_week_from'];
				$data['recurnce_week_to'] = $aca['recurnce_week_to'];
				$data['recurnce_day_from'] = $aca['recurnce_day_from'];
				$data['recurnce_day_to'] = $aca['recurnce_day_to'];
				$data['facilities_id'] = $frow;
				
				$notesData = $this->model_notes_acarules->getotalnote($data);
				
				$data['keyword_id'] = $aca['keyword_id'];
				$data['rules_type'] = $aca['rules_type'];
				$data['rules_start_time'] = $aca['rules_start_time'];
				$data['rules_end_time'] = $aca['rules_end_time'];
				$data['rules_operation'] = $aca['rules_operation'];
				$data['recurnce_week_from'] = $aca['recurnce_week_from'];
				$data['recurnce_week_to'] = $aca['recurnce_week_to'];
				$data['recurnce_day_from'] = $aca['recurnce_day_from'];
				$data['recurnce_day_to'] = $aca['recurnce_day_to'];
				//$data['facilities_ids'] = $aca['facilities_id'];
				$data['facilities_id'] = $frow;
				
				$nactive = $this->model_notes_acarules->getnoteactivedetails($data);
				echo '<pre>'.$aca ['keyword_name'].'-'; print_r($nactive); echo '</pre>'; //die;
				$missed_time_arr = array();
				$facilities_id = $nactive['facilities_id'];
				
				
				$facility = $this->model_facilities_facilities->getfacilities ( $frow );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
				$unique_id = $facility ['customer_key'];
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				$setting_data = unserialize($customer_info ['setting_data']);
				$setting_data['time_format'];
				if($setting_data['time_format']!=''){
					$time_format = $setting_data['time_format'];
				}else{
					$time_format = 'h:i A';
				}
			
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
				//echo '<br>AAA-'.$timezone_info ['timezone_value'];
				
				echo '<br>Current Time-'.date('Y-m-d h:i:s A');
				
				$current_time = strtotime(date('Y-m-d H:i'));
				$missed_time_interval=0;
				if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==1){
					$missed_time_interval = $aca['missed_time'];
				}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==2){
					$missed_time_interval = $aca['missed_time']*60;
				}else if(isset($aca['missed_duration_type']) && $aca['missed_duration_type']==3){
					$missed_time_interval = $aca['missed_time']*60*24;
				}
				
				if($missed_time_interval!=0){
					$missed_time_interval = $missed_time_interval*60;
				}
				
				$interval=0;
				
				if(isset($aca['duration_type']) && $aca['duration_type']==1){
					$interval = $aca['interval'];
				}else if(isset($aca['duration_type']) && $aca['duration_type']==2){
					$interval = $aca['interval']*60;
				}else if(isset($aca['duration_type']) && $aca['duration_type']==3){
					$interval = $aca['interval']*60*24;
				}
				
				if($interval!=0){
					$interval = $interval*60;
				}
				
				$notification_interval=0;
				
				if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==1){
					$notification_interval = $aca['notification_interval'];
				}else if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==2){
					$notification_interval = $aca['notification_interval']*60;
				}else if(isset($aca['notification_duration_type']) && $aca['notification_duration_type']==3){
					$notification_interval = $aca['notification_interval']*60*24;
				}
				
				if($notification_interval!=0){
					$notification_interval = $notification_interval*60;
				}
				
				$is_custom_offset_interval=0;
				if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==1){
					$is_custom_offset_interval = $aca['is_custom_offset'];
				}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==2){
					$is_custom_offset_interval = $aca['is_custom_offset']*60;
				}else if(isset($aca['is_custom_offset_duration_type']) && $aca['is_custom_offset_duration_type']==3){
					$is_custom_offset_interval = $aca['is_custom_offset']*60*24;
				}
				
				if($is_custom_offset_interval!=0){
					$is_custom_offset_interval = $is_custom_offset_interval*60;
				}
				
				$current_date = date('Y-m-d');
				$start_time = $aca['rules_start_time'];
				$end_time = $aca['rules_end_time'];
				$rules_start_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$rules_end_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));		
				$rules_end_time = strtotime($aca['rules_end_time']);
				
				$date1 = strtotime(date('Y-m-d', strtotime($nactive['date_added'])));
				$date2 = strtotime(date('Y-m-d'));
				
				$current_date = date('Y-m-d');
				$start_time = $aca['rules_start_time'];
				
				if($date1 < $date2){
					$note_added_date = strtotime($current_date.' '.$start_time);
					$note_added_date2 = strtotime($current_date.' '.$start_time);
					$note_added_date3 = strtotime($current_date.' '.$start_time);
				}else{
					$note_added_date = strtotime($nactive['date_added']);
					$note_added_date2 = strtotime($nactive['date_added']);
					$note_added_date3 = strtotime($nactive['date_added']);
				}
			
			
			
			
			
			if($setting_data['enable_standards']==1){
				
				
				$keywords = $this->model_setting_keywords->getkeywordDetail ( $aca['keyword_id'] );
			
				//echo '<pre>'; print_r($keywords); echo '</pre>'; //die;
				
				if($keywords['keyword_image']!=''){
					$keyword_image = $keywords['keyword_image'];
				}else{
					$keyword_image = '';
				}
					
				
				if($aca['no_of_recurrence']!='' && $aca['missed_count']>0){
					$count_arr = array();
					$missed_time_arr=array();
				}else if(is_array($nactive) && $nactive!=0){
					
					$data2 = array();
					if(isset($aca['is_missed']) && $aca['is_missed']==1){
						
						if($note_added_date!=''){	
							
							if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
								$note_added_date = $note_added_date+180;
							}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!="" && 	$is_custom_offset_interval!=0){
								$note_added_date = $note_added_date + $is_custom_offset_interval;	
							}else{
								$note_added_date = $note_added_date+$interval;
								$note_added_date = $note_added_date+$missed_time_interval;
							}
							
							$missed_time = date('Y-m-d h:i:s A',$note_added_date);
							
							$missed_time_arr[] = $missed_time;
						
							echo '<pre>missed_arr-'; print_r($missed_time_arr); echo '</pre>'; //die;	
							
							if(!empty($missed_time_arr)){
							
								foreach($missed_time_arr AS $mrow){
									
									$plus_mrow  = $current_time+30;
									
									if($current_time <= strtotime($mrow) && strtotime($mrow) <= $plus_mrow  && $keyword_image!=''){
										echo 'missed inner-'.$mrow;
										if($aca['no_of_recurrence']!='' && $aca['missed_count']>0){
											return false;
										}else{
											$data2 ['imgOutput'] = '';
											$data2 ['notes_pin'] = SYSTEM_GENERATED_PIN;
											$data2 ['user_id'] = SYSTEM_GENERATED;
											$data2 ['note_date'] = date('d-m-Y H:i:s');
											$data2 ['keyword_file'] = $keyword_image;
											$data2 ['multi_keyword_file'] = $keyword_image;
											$data2 ['activenoteids'] = $aca['keyword_id'];
											$data2 ['notetime'] = date('h:i A');
											$data2 ['notes_description'] = $aca['keyword_name'].' missed at '.date('h:i A',strtotime($mrow));
											
											
											//var_dump($data2);
											
											
											if(!empty($data2)){
												$facilities_id = $frow;
												$notes_id = $this->model_notes_notes->jsonaddnotes ( $data2, $facilities_id );
												$mfdata=array();
												$mfdata['notes_id'] = $notes_id;
												$mfdata['is_comment'] = 4;
												$this->model_notes_acarules->missed_flag_insert($mfdata);	
											}
											
											if($aca['is_missed_notification']!="" && $aca['is_missed_notification']==1){
												
												$rule_action_arr = explode(',',$aca['rule_action']);
												if(in_array('1',$rule_action_arr)){ //sms
													$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
													$where = $facilities['facility'];
													$notes_description = str_replace('I','',$aca['keyword_name']).' missed at '.date('h:i A',strtotime($mrow)) .' on '.$where;
													if($aca['user_roles']!=""){
														$user_roles_arr = explode(',',$aca['user_roles']);
														foreach($user_roles_arr as $row){
															$urole = array();
															$urole['user_group_id'] = $row;
															$tusers = $this->model_user_user->getUsers($urole);
															if($tusers){
																foreach ($tusers as $tuser) {
																	if($tuser['phone_number'] != null && $tuser['phone_number'] != '0'){
																		$message = "Role - Standards\n";
																		$message .= $notes_description;
																		$sdata = array();
																		$sdata['message'] = $message;
																		$sdata['phone_number'] = $user_info['phone_number'];
																		$sdata['facilities_id'] = $fid;	
																		$response = $this->model_api_smsapi->sendsms($sdata);
																	}
																}
															}
														}
													}
													

													if($aca['auserids']!=""){
														$auserids_arr = explode(',',$aca['auserids']);
														foreach($auserids_arr as $row){
															
															$user_info = $this->model_user_user->getUserbyupdate($row);	
															if(!empty($user_info)){
																
																if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
																	$message = "Role - Standards\n";
																	$message .= $notes_description;
																	$sdata = array();
																	$sdata['message'] = $message;
																	$sdata['phone_number'] = $user_info['phone_number'];
																	$sdata['facilities_id'] = $fid;	
																	$response = $this->model_api_smsapi->sendsms($sdata);
																}
															}
														}
													}
												
												}
												
												if(in_array('2',$rule_action_arr)){ //email
													
													$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
													
													$where = $facilities['facility'];
													$emailData = array();
													$useremailids = array();
													$emailData ['keyword_file'] = $keyword_image;
													$emailData ['notes_description'] = str_replace('I','',$aca['keyword_name']).' missed at '.date('h:i A',strtotime($mrow));
													$emailData ['date_added'] = date('m-d-Y');
													$emailData ['notetime'] = date('h:i A');
													$emailData['keyword_name'] = $aca ['keyword_name'];
													$emailData['facilities_id'] = $facilities_id;
													$emailData['rule_name'] = 'Standards';
													$emailData['where'] = $where;
												
													if($aca['user_roles']!=''){
														
														$user_roles_arr = explode(',',$aca['user_roles']);
														
														foreach($user_roles_arr as $row){
															$urole = array();
															$urole['user_group_id'] = $row;
															$tusers = $this->model_user_user->getUsers($urole);
															
															if($tusers){
																foreach ($tusers as $tuser) {
																	if($tuser['email'] != null && $tuser['email'] != ""){
																		$useremailids[] = $tuser['email'];
																	}
																}
															}
														}	
													}
												
													if($aca['auserids']!=''){
														$user_ids_arr = explode(',',$aca['auserids']);
														foreach($user_ids_arr as $row){
															$user_info = $this->model_user_user->getUserbyupdate($row);
															if ($user_info) {
																if($user_info['email']){
																	$useremailids[] = $user_info['email'];
																}
															}
														}		
													}
												
													echo $message33 = $this->acaemailtemplate($emailData);	
														
													$edata = array();
													$edata['message'] = $message33;
													$edata['subject'] = 'This is an Automated Alert Email.';
													$edata['useremailids'] = $useremailids;
													$edata['user_email'] = $user_email;
													//echo '<pre>'; print_r($edata); echo '</pre>'; //die;
													if(!empty($useremailids)){
														$email_status = $this->model_api_emailapi->sendmail($edata);
													}					
												}
											}	
										}
									}
								} 
							}
						}
					}
					
					$current_date = date('Y-m-d');
					$start_time = $aca['rules_start_time'];
					$rules_start_time2 = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
					
					echo '<br>key-'.$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
					
					
					
					/*if($current_time < strtotime($rules_start_time2)){
						
						$is_custom_offset_interval2 = $is_custom_offset_interval;
						
						if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
							$note_added_date2 = $note_added_date2-120;
							
							//$note_added_date2x = $note_added_date2+120;
							//$note_added_date2 = $note_added_date2-180;
							
							//echo '<br>key-'.$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							//echo '<br>val-'.$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x);
							
							
							
							
							
						}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!=""){
							$note_added_date2 = $note_added_date2 - $is_custom_offset_interval2;
						}else{
							$note_added_date2 = $note_added_date2-$interval;
						}
					
					}else{*/
					
						if($aca['is_task_rule']==1 && $aca['is_custom_offset']==""){
							$note_added_date2x = $note_added_date2+120;
							$note_added_date2 = $note_added_date2+180;
							
							echo '<br>key-'.$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							echo '<br>val-'.$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x);
							
						
						}else if($aca['is_task_rule']==1 && $aca['is_custom_offset']!=""){
							$note_added_date2x = $note_added_date2;
							$note_added_date2 = $note_added_date2+$is_custom_offset_interval;
							echo '<br>key2-'.$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							echo '<br>val2-'.$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x+120);	
						
						}else{
							//echo '<br>notification_interval-'.$notification_interval;
							$note_added_date2x = $note_added_date2;
							$note_added_date2 = $note_added_date2 + $interval;
							echo '<br>key3-'.$missed_note_time = date('Y-m-d h:i:s A',$note_added_date2);
							echo '<br>val3-'.$notification_time2 = date('Y-m-d h:i:s A',$note_added_date2x+$notification_interval);
						}
					//}
					
					
					
					$notification_time_arr[$missed_note_time] = $notification_time2;
					
					
					echo '<pre>notification_arr-'.$aca ['keyword_name']; print_r($notification_time_arr); echo '</pre>'; //die;
					
					
					
					
					$notification_data = array();
					
					if(!empty($notification_time_arr)){
					
						foreach($notification_time_arr AS $key=>$nrow){
							
							$plus_nrow  = $current_time+30;
							
							$notification_intervalggg = $notification_interval;
							
							$notification_intervalggg = strtotime($key);
							
							if($current_time <= strtotime($nrow) && strtotime($nrow) <= $plus_nrow  && $notification_intervalggg <= $rules_end_time){
								
								//echo '<br>AAAAAAAAAAAAAA-'.date('h:i A',$notification_intervalggg).'<='.date('h:i A',$rules_end_time);
								
								$rule_action_arr = explode(',',$aca['rule_action']);

								if(in_array('1',$rule_action_arr)){ //sms
									
									$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
									
									$where = $facilities['facility'];
			
									$notes_description = str_replace('I','',$aca['keyword_name']).' - scheduled at '.date('h:i A',strtotime($key)) . ' on '.$where;
								
									if($aca['user_roles']!=""){
										$user_roles_arr = explode(',',$aca['user_roles']);
										foreach($user_roles_arr as $row){
											
											$urole = array();
											$urole['user_group_id'] = $row;
											$tusers = $this->model_user_user->getUsers($urole);
											if($tusers){
												foreach ($tusers as $tuser) {
										
													if($tuser['phone_number'] != null && $tuser['phone_number'] != '0'){
														$message = "Role - Standards\n";
														$message .= $notes_description;
														$sdata = array();
														$sdata['message'] = $message;
														$sdata['phone_number'] = $user_info['phone_number'];
														$sdata['facilities_id'] = $frow;	
														$response = $this->model_api_smsapi->sendsms($sdata);
													}
												}
											}
										}
									}
								

									if($aca['auserids']!=""){
										$auserids_arr = explode(',',$aca['auserids']);
										foreach($auserids_arr as $row){
											
											$user_info = $this->model_user_user->getUserbyupdate($row);	
											if(!empty($user_info)){
												
												if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
													$message = "Role - Standards\n";
													$message .= $notes_description;
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = $user_info['phone_number'];
													$sdata['facilities_id'] = $frow;	
													$response = $this->model_api_smsapi->sendsms($sdata);
												}
											}
										}
									}
									
								}
								
								if(in_array('2',$rule_action_arr)){ //email
								
									$facilities = $this->model_facilities_facilities->getfacilities ( $frow );
									
									$where = $facilities['facility'];
									$emailData = array();
									$useremailids = array();
									
									if($keyword_image!=''){
										$emailData ['keyword_file'] = $keyword_image;
									}else{
										$emailData ['keyword_file']='';
									}
									
									$emailData ['notes_description'] = str_replace('I','',$aca['keyword_name']).' - scheduled at '.date('h:i A',strtotime($key));
									$emailData ['date_added'] = date('m-d-Y',strtotime($key));
									$emailData ['notetime'] = date('h:i A',strtotime($key));
									$emailData['keyword_name'] = $aca ['keyword_name'];
									$emailData['facilities_id'] = $frow;
									$emailData['rule_name'] = 'Standards';
									$emailData['where'] = $where;
									
									if($aca['user_roles']!=''){
										
										$user_roles_arr = explode(',',$aca['user_roles']);
										
										foreach($user_roles_arr as $row){
											$urole = array();
											$urole['user_group_id'] = $row;
											$tusers = $this->model_user_user->getUsers($urole);
											
											if($tusers){
												foreach ($tusers as $tuser) {
													if($tuser['email'] != null && $tuser['email'] != ""){
														$useremailids[] = $tuser['email'];
													}
												}
											}
										}	
									}
									
									if($aca['auserids']!=''){
										$user_ids_arr = explode(',',$aca['auserids']);
										foreach($user_ids_arr as $row){
											$user_info = $this->model_user_user->getUserbyupdate($row);
											if ($user_info) {
												if($user_info['email']){
													$useremailids[] = $user_info['email'];
												}
											}
										}		
									}
									
									echo $message33 = $this->acaemailtemplate($emailData);	
											
									$edata = array();
									$edata['message'] = $message33;
									$edata['subject'] = 'This is an Automated Alert Email.';
									$edata['useremailids'] = $useremailids;
									$edata['user_email'] = $user_email;
									if(!empty($useremailids)){
										$email_status = $this->model_api_emailapi->sendmail($edata);
									}			
								}
							}
						}
					}
					$missed_time_arr = array();	
					$notification_time_arr = array();
				}
			}
		}
		
		}
		echo "Success";
		
	}
	
	
	
	
	public function acaemailtemplate($result){
		
		//echo '<pre>tttt'; print_r($result); echo '</pre>'; //die;
		
		
		if($result['keyword_file']!="" && $result['keyword_file']!=NULL){
			$keyword_file = $result['keyword_file'];
		}else{
			$keyword_file = '';
		}
		
		
		
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>This is an Automated Alert Email</title>

		<style>
		@media screen and (max-width:500px) {
		   h6 {
				font-size: 12px !important;
			}
		}
		</style>
		</head>
 
		<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

		<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
			<tr>
				<td></td>
				<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
					

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
						<table style="width: 100%;">
							<tr>
								<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
								<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">This is an Automated Alert Email</h6></td>
							</tr>
						</table>
					</div>
					
				</td>
				<td></td>
			</tr>
		</table>

		<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
			<tr>
				<td></td>
				<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
						<table>
							<tr>
								<td>
									
									<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello User!</h1>
									<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$result['rule_name'].'! Please review the details below for further information or actions:</p>
									
								</td>
							</tr>
						</table>
					</div>
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
						
						<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
							<tr>
								<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></td>
								<td>
									<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$result['rule_name'].'</small></h4>
									<div style="float: left;"><img src="'.$keyword_file.'" style="width:30px;" /><p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;"></div><div style="margin-top: 8px;">'.$result['notes_description'].'</p></div>
								</td>
							</tr>
						</table>
					
					</div>
					
					
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;">
							<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								'.$result['date_added'].'&nbsp;'.$result['notetime'].'
								</p>
							</td>
						</tr>
					</table></div>
					
					
					
					<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="' . $result ['href'] . '">
					<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">'.$result['where'].'</p>
					</td>
				</tr>
			</table></div>
					
					
					
					
					
				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>';
		return $html;
	}
	
}