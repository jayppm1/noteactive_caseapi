<?php
class Controllersyndbsyndb extends Controller {
	public function index() {
		$this->load->model ( 'syndb/syndb' );
		$fdata = array ();
		$fdata ['manual_link'] = $this->request->get ['manual_link'];
		$fdata ['schedule'] = $this->request->get ['schedule'];
		$this->model_syndb_syndb->addsync ( $fdata );
	}
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
	
	public function persecondScript() {
		
		
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
		$this->load->model ( 'createtask/createtask' );
		$this->load->model ( 'syndb/syndb' );
		//$this->db->query ( "DELETE FROM `" . DB_PREFIX . "session` WHERE data = 'false' " );
		
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if ($results != null && $results != "") {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$delete_startDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$delete_date = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$taskstart_date = date ( 'Y-m-d', strtotime ( "-1 day" ) );
				$taskend_date = date ( 'Y-m-d' );
				
				$complteteTaskLists = $this->model_createtask_createtask->gettaskListsdeleted ( $taskstart_date, $taskend_date, $tresult ['facilities_id'] );
				// var_dump($complteteTaskLists);die;
				if ($complteteTaskLists != null && $complteteTaskLists != "") {
					
					foreach ( $complteteTaskLists as $complteteTaskList ) {
						$this->model_syndb_syndb->autodeletetask ($taskstart_date, $complteteTaskList, $timezone_info);
					}
				}
				
			}
		}
		
		
		echo "Success";
		
	}
	
	
	public function createstreamProcess() {
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		// $response3 = $this->awsimageconfig->getSessionToken($data);
		
		$response3 = $this->awsimageconfig->stopstream ();
		var_dump ( $response3 );
		echo "<hr>";
		
		$response2 = $this->awsimageconfig->deletestream ();
		var_dump ( $response2 );
		echo "<hr>";
		
		// $response = $this->awsimageconfig->createStreamProcessoruser ( 47, $type );
		echo "createStreamProcessoruser  ====================== ";
		var_dump ( $response );
		echo "<hr>";
		
		// $startresponse = $this->awsimageconfig->startStream ();
		// echo "startStreamProcessor ====================== ";
		var_dump ( $startresponse );
		echo "<hr>";
		
		// $response4 = $this->awsimageconfig->describeStreamProcessor ();
		var_dump ( $response4 );
		echo "<hr>";
		
		// $response5 = $this->awsimageconfig->startFaceSearch();
		// var_dump($response5);
		// echo "<hr>";
		
		// $response3 = $this->awsimageconfig->getmedia ();
		var_dump ( $response3 );
		echo "<hr>";
		
		// $response4 = $this->awsimageconfig->getinvike ();
		var_dump ( $response4 );
		echo "<hr>";
		
		$response5 = $this->awsimageconfig->describeStreamProcessor ();
		var_dump ( $response5 );
		echo "<hr>";
		
		$response6 = $this->awsimageconfig->liststream ();
		var_dump ( $response6 );
		echo "<hr>";
	}
	
	/*
	 * public function speechToText(){
	 * if($this->config->get('config_transcription') == '1'){
	 * $this->load->model('notes/notes');
	 * $query = $this->db->query("SELECT * FROM dg_notes_media where audio_attach_type = '1' ");
	 * $numrow = $query->num_rows;
	 *
	 * if($numrow > 0){
	 *
	 * //var_dump($query->rows);
	 *
	 * $username = '70b54a4a-187f-46e4-9685-0f80cddc8b0c';
	 * $password = 'bWpYdfVSCWnF';
	 * $url = 'https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?model=en-US_BroadbandModel&continuous=true';
	 *
	 * // require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
	 *
	 * // echo rand();
	 * // var_dump($query->rows);
	 * foreach($query->rows as $row){
	 *
	 * $urrl = $row['audio_attach_url'];
	 *
	 *
	 *
	 * $filePath = DIR_IMAGE.'audio/';
	 * $filename = $filePath.$row['audio_upload_file'];
	 *
	 * $file = fopen($filename, 'r');
	 *
	 *
	 * $size = filesize($filename);
	 *
	 * $fildata = fread($file,$size);
	 * // var_dump($fildata);
	 * $headers = array( "Content-Type: audio/wav",
	 * "Transfer-Encoding: chunked");
	 *
	 * $ch = curl_init();
	 * curl_setopt($ch, CURLOPT_URL, $url);
	 * curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	 * curl_setopt($ch, CURLOPT_POST, TRUE);
	 * curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	 * curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	 * curl_setopt($ch, CURLOPT_POSTFIELDS, $fildata);
	 * curl_setopt($ch, CURLOPT_INFILE, $file);
	 * curl_setopt($ch, CURLOPT_INFILESIZE, $size);
	 * curl_setopt($ch, CURLOPT_VERBOSE, true);
	 * curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 * $executed = curl_exec($ch);
	 * curl_close($ch);
	 *
	 * $contents = json_decode($executed, true);
	 *
	 * $ndata = array();
	 * foreach($contents as $content){
	 * foreach($content as $a){
	 * foreach($a['alternatives'] as $b){
	 *
	 * $ndata[] = $b['transcript'];
	 * }
	 * }
	 * }
	 *
	 * $ncontent = implode(" ",$ndata);
	 *
	 * $notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
	 *
	 * $notes_description = $notes_data['notes_description'];
	 * $facilities_id = $notes_data['facilities_id'];
	 * $date_added = $notes_data['date_added'];
	 *
	 * $notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
	 * $formData = array();
	 * $formData['notes_description'] = $notesContent;
	 * $formData['facilities_id'] = $facilities_id;
	 * $formData['date_added'] = $date_added;
	 *
	 *
	 * $slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
	 * $this->db->query($slq1);
	 *
	 * $this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);
	 *
	 *
	 *
	 * unlink($filename);
	 * echo "Success";
	 *
	 * }
	 *
	 * }
	 *
	 * }
	 * }
	 */
	public function schedulereport() {
		$sqltawr = "SELECT * from " . DB_PREFIX . "scheduler_report where status = '1' ";
		if ($this->request->get ['scheduler_report_id'] != '' && $this->request->get ['scheduler_report_id'] != null) {
			$sqltawr .= " and  scheduler_report_id = '" . ( int ) $this->request->get ['scheduler_report_id'] . "' ";
		}
		
		$qttwr = $this->db->query ( $sqltawr );
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'journal/journal' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'api/emailapi' );
		
		$this->language->load ( 'notes/notes' );
		$this->load->model ( 'notes/notescomment' );
		
		if ($qttwr->num_rows > 0) {
			
			foreach ( $qttwr->rows as $reportdata ) {
				$notess = array ();
				// var_dump($reportdata['report_format']);
				$sqltawrre = "SELECT * from " . DB_PREFIX . "scheduler_form where scheduler_form_id = '" . $reportdata ['scheduler_form_id'] . "' ";
				$qttwrre = $this->db->query ( $sqltawrre );
				$report_form = $qttwrre->row;
				// echo "<hr>";
				// var_dump($report_form);
				// echo "<hr>";
				$useremails = array ();
				
				if ($reportdata ['user_roles'] != null && $reportdata ['user_roles'] != "") {
					$user_roles1 = explode ( ',', $reportdata ['user_roles'] );
					// var_dump($user_roles1);
					
					$this->load->model ( 'user/user_group' );
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $user_roles1 as $user_role ) {
						
						$urole = array ();
						$urole ['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers ( $urole );
						
						if ($tusers) {
							foreach ( $tusers as $tuser ) {
								if ($tuser ['email']) {
									$useremails [] = $tuser ['email'];
								}
							}
						}
					}
				}
				
				if ($reportdata ['user_ids'] != null && $reportdata ['user_ids'] != "") {
					$userids1 = explode ( ',', $reportdata ['user_ids'] );
					
					$this->load->model ( 'user/user' );
					$this->load->model ( 'setting/tags' );
					
					foreach ( $userids1 as $userid ) {
						
						$user_info = $this->model_user_user->getUserbyupdate ( $userid );
						
						if ($user_info) {
							if ($user_info ['email']) {
								$useremails [] = $user_info ['email'];
							}
						}
					}
				}
				
				$useremail_ids = implode ( ',', $useremails );
				// var_dump($useremail_ids);
				
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $reportdata ['facilities_id'] );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$noteDate1 = date ( 'Y-m-d', strtotime ( 'now' ) );
				$time1 = date ( 'h:i A', strtotime ( 'now' ) );
				$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
				
				if ($report_form ['facilities_id'] > 0) {
					$facilities_id = $report_form ['facilities_id'];
					$facilities_info222 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
					$facilities_info2 = ', Facility: ' . $facilities_info222 ['facility'];
					$facilities_info2_s = $facilities_info222 ['facility'];
				} else {
					$facilities_info2_s = " All facility ";
				}
				
				if ($report_form ['highlighter_id'] != '' && $report_form ['highlighter_id'] != null) {
					$highlighter_id = $report_form ['highlighter_id'];
					
					$highlighterData = $this->model_setting_highlighter->gethighlighter ( $highlighter_id );
					$highlighter_value2 = ', Highlighter: ' . $highlighterData ['highlighter_name'];
				}
				
				if ($report_form ['task_search'] != '' && $report_form ['task_search'] != null) {
					$task_search = $report_form ['task_search'];
				}
				
				if ($report_form ['form_search'] != '' && $report_form ['form_search'] != null) {
					$form_search = $report_form ['form_search'];
				}
				
				if ($report_form ['assign_to'] != '' && $report_form ['assign_to'] != null) {
					$assign_to = $report_form ['assign_to'];
				}
				
				if ($report_form ['tags_id'] != '' && $report_form ['tags_id'] != null) {
					$emp_tag_id = $report_form ['tags_id'];
				}
				
				if ($report_form ['username'] != '' && $report_form ['username'] != null) {
					$username = $report_form ['username'];
					$username2 = ', User: ' . $report_form ['username'];
				}
				
				if ($report_form ['keyword'] != '' && $report_form ['keyword'] != null) {
					$search_keyword = $report_form ['keyword'];
					$keyWord = ', Keyword: ' . $report_form ['keyword_name'];
				}
				
				if ($report_form ['time_from'] != '00:00:00') {
					$search_time_start = $report_form ['time_from'];
				}
				
				if ($report_form ['time_to'] != '00:00:00') {
					$search_time_to = $report_form ['time_to'];
				}
				
				if ($report_form ['task_type'] != '' && $report_form ['task_type'] != null) {
					$task_type = $report_form ['task_type'];
				}
				if ($report_form ['keyword_id'] != '' && $report_form ['keyword_id'] != null) {
					$keyword_id = $report_form ['keyword_id'];
				}
				if ($report_form ['relation_search'] != '' && $report_form ['relation_search'] != null) {
					$relation_search = $report_form ['relation_search'];
				}
				if ($report_form ['child_facility_search'] != '' && $report_form ['child_facility_search'] != null) {
					$child_facility_search = $report_form ['child_facility_search'];
				}
				
				$sort = 'date_added';
				$order = 'ASC';
				
				if ($this->request->get ['manual_link'] == '1') {
					
					if ($reportdata ['recurrence'] == '1') {
						
						if ($report_form ['date_from'] != '0000-00-00') {
							
							$start_date = new DateTime ( $report_form ['date_from'] );
							$since_start = $start_date->diff ( new DateTime ( $report_form ['date_to'] ) );
							
							if ($since_start->d > 0) {
								$filter_date_start = date ( 'm-d-Y', strtotime ( ' -' . $since_start->d . ' days', strtotime ( $date_added ) ) );
							} else {
								$filter_date_start = date ( 'm-d-Y' );
							}
							$filter_date_end = date ( 'm-d-Y' );
						} else {
							$filter_date_start = date ( 'm-d-Y' );
							$filter_date_end = date ( 'm-d-Y' );
						}
						
						$ffdata = array (
								'sort' => $sort,
								'order' => $order,
								'filter_date_start' => $filter_date_start,
								'filter_date_end' => $filter_date_end,
								'facilities_id' => $facilities_id,
								'task_type' => $task_type,
								'highlighter_id' => $highlighter_id,
								'task_search' => $task_search,
								'form_search' => $form_search,
								'assign_to' => $assign_to,
								'emp_tag_id' => $emp_tag_id1,
								'emp_tag_id1' => $emp_tag_id1,
								'user_id' => $username,
								'search_keyword' => $search_keyword,
								'search_time_start' => $search_time_start,
								'search_time_to' => $search_time_to,
								'keyword_id' => $keyword_id,
								'child_facility_search' => $child_facility_search,
								'customer_key' => $reportdata ['customer_key'],
								'start' => 0,
								'limit' => 50000 
						);
						
						// var_dump($ffdata);
						
						// echo "ma daily 1";
						
						if (IS_WAREHOUSE == '1') {
							$this->load->model ( 'syndb/syndb' );
							$fdata = array ();
							$fdata ['schedule'] = 1;
							$this->model_syndb_syndb->addsync ( $fdata );
						}
						
						// $sqls23d = "SELECT * FROM `" . DB_PREFIX . "scheduler_url` where `date_added` BETWEEN '".$date_added." 00:00:00 ' AND '".$date_added." 23:59:59' ";
						// $query4d = $this->db->query($sqls23d);
						
						// if($query4d->num_rows == 0){
						$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
						// die;
						foreach ( $nnotes as $nnote ) {
							
							$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
							$keyImageSrc11 = "";
							
							if ($nnote ['keyword_file'] == '1') {
								$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
								foreach ( $allkeywords as $keyword ) {
									$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
									$noteskeywords [] = array (
											'keyword_file_url' => $keyword ['keyword_file_url'] 
									);
								}
							}
							if ($nnote ['highlighter_id'] > 0) {
								$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
							} else {
								$highlighterData = array ();
							}
							
							$images = array ();
							if ($nnote ['notes_file'] == '1') {
								$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
								
								foreach ( $allimages as $image ) {
									$images [] = array (
											'media_user_id' => $image ['media_user_id'],
											'notes_type' => $image ['notes_type'],
											'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
											'media_signature' => $image ['media_signature'],
											'media_pin' => $image ['media_pin'],
											'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
									);
								}
							}
							
							$alltag = array ();
							if ($nnote ['emp_tag_id'] == '1') {
								$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
							} else {
								$alltag = array ();
							}
							
							if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
								$userPin = $nnote ['notes_pin'];
							} else {
								$userPin = '';
							}
							
							if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
								$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
							} else {
								$task_time = "";
							}
							
							$notestasks = array ();
							$grandtotal = 0;
							
							$ograndtotal = 0;
							
							if ($nnote ['task_type'] == '1') {
								$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
								
								foreach ( $alltasks as $alltask ) {
									$grandtotal = $grandtotal + $alltask ['capacity'];
									$tags_ids_names = '';
									
									if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
										
										foreach ( $tags_ids1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$tags_ids_names .= $emp_tag_id . ', ';
											}
										}
									}
									
									$out_tags_ids_names = "";
									$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
									
									if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
										$i = 0;
										
										$ooout = '1';
										// var_dump($tags_ids1);
										
										foreach ( $tags_ids1 as $tag1 ) {
											
											$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info12 ['emp_first_name']) {
												$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info12 ['emp_tag_id'];
											}
											
											if ($tags_info12) {
												$out_tags_ids_names .= $emp_tag_id . ', ';
											}
											
											$i ++;
										}
										
										// $ograndtotal = $i;
									} else {
										$ooout = '2';
									}
									
									// var_dump($ograndtotal);
									
									if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notestasks [] = array (
											'notes_by_task_id' => $alltask ['notes_by_task_id'],
											'locations_id' => $alltask ['locations_id'],
											'task_type' => $alltask ['task_type'],
											'task_content' => $alltask ['task_content'],
											'user_id' => $alltask ['user_id'],
											'signature' => $alltask ['signature'],
											'notes_pin' => $alltask ['notes_pin'],
											'task_time' => $alltask ['task_time'],
											// 'media_url' => $alltask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltask ['capacity'],
											'location_name' => $alltask ['location_name'],
											'location_type' => $alltask ['location_type'],
											'notes_task_type' => $alltask ['notes_task_type'],
											'task_comments' => $alltask ['task_comments'],
											'role_call' => $alltask ['role_call'],
											'medication_attach_url' => $medication_attach_url,
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
											'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
											'tags_ids_names' => $tags_ids_names,
											'out_tags_ids_names' => $out_tags_ids_names 
									);
								}
							}
							
							$notesmedicationtasks = array ();
							if ($nnote ['task_type'] == '2') {
								$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
								
								foreach ( $alltmasks as $alltmask ) {
									
									if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
										$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
									}
									
									if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notesmedicationtasks [] = array (
											'notes_by_task_id' => $alltmask ['notes_by_task_id'],
											'locations_id' => $alltmask ['locations_id'],
											'task_type' => $alltmask ['task_type'],
											'task_content' => $alltmask ['task_content'],
											'user_id' => $alltmask ['user_id'],
											'signature' => $alltmask ['signature'],
											'notes_pin' => $alltmask ['notes_pin'],
											'task_time' => $taskTime,
											// 'media_url' => $alltmask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltmask ['capacity'],
											'location_name' => $alltmask ['location_name'],
											'location_type' => $alltmask ['location_type'],
											'notes_task_type' => $alltmask ['notes_task_type'],
											'tags_id' => $alltmask ['tags_id'],
											'drug_name' => $alltmask ['drug_name'],
											'dose' => $alltmask ['dose'],
											'drug_type' => $alltmask ['drug_type'],
											'quantity' => $alltmask ['quantity'],
											'frequency' => $alltmask ['frequency'],
											'instructions' => $alltmask ['instructions'],
											'count' => $alltmask ['count'],
											'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
											'task_comments' => $alltmask ['task_comments'],
											'role_call' => $alltmask ['role_call'],
											'medication_file_upload' => $alltmask ['medication_file_upload'],
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
									);
								}
							}
							
							if ($nnote ['task_type'] == '6') {
								$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
							} else {
								$approvaltask = array ();
							}
							
							if ($nnote ['task_type'] == '3') {
								$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
							} else {
								$geolocation_info = array ();
							}
							
							if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
								$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
							} else {
								$original_task_time = "";
							}
							
							if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
								$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
							} else {
								$user_file = "";
							}
							
							$notescomments = array ();
							if ($nnote ['is_comment'] == '1') {
								$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
							} else {
								$allcomments = array ();
							}
							
							if ($allcomments) {
								foreach ( $allcomments as $allcomment ) {
									$commentskeywords = array ();
									if ($allcomment ['keyword_file'] == '1') {
										$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
									} else {
										$aallkeywords = array ();
									}
									
									if ($aallkeywords) {
										$keyImageSrc12 = array ();
										$keyname = array ();
										foreach ( $aallkeywords as $callkeyword ) {
											$commentskeywords [] = array (
													'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
													'notes_id' => $callkeyword ['notes_id'],
													'comment_id' => $callkeyword ['comment_id'],
													'keyword_id' => $callkeyword ['keyword_id'],
													'keyword_name' => $callkeyword ['keyword_name'],
													'keyword_file_url' => $callkeyword ['keyword_file_url'],
													'keyword_image' => $callkeyword ['keyword_image'],
													'img_icon' => $callkeyword ['keyword_file_url'] 
											);
										}
									}
									$notescomments [] = array (
											'comment_id' => $allcomment ['comment_id'],
											'notes_id' => $allcomment ['notes_id'],
											'facilities_id' => $allcomment ['facilities_id'],
											'comment' => $allcomment ['comment'],
											'user_id' => $allcomment ['user_id'],
											'notes_pin' => $allcomment ['notes_pin'],
											'signature' => $allcomment ['signature'],
											'user_file' => $allcomment ['user_file'],
											'is_user_face' => $allcomment ['is_user_face'],
											'date_added' => $allcomment ['date_added'],
											'comment_date' => $allcomment ['comment_date'],
											'notes_type' => $allcomment ['notes_type'],
											'commentskeywords' => $commentskeywords 
									);
								}
							}
							
							$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
							$forms = array ();
							foreach ( $allforms as $allform ) {
								
								if ($allform ['form_type'] == '3') {
									$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
								}
								
								$forms [] = array (
										'form_type_id' => $allform ['form_type_id'],
										'notes_id' => $allform ['notes_id'],
										'form_type' => $allform ['form_type'],
										'custom_form_type' => $allform ['custom_form_type'],
										'user_id' => $allform ['user_id'],
										'signature' => $allform ['signature'],
										'notes_pin' => $allform ['notes_pin'],
										'incident_number' => $allform ['incident_number'],
										'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
										'href' => $form_url 
								);
							}
							// var_dump($forms);
							
							$notess [] = array (
									'notes_id' => $nnote ['notes_id'],
									'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
									'noteskeywords' => $noteskeywords,
									'notescomments' => $notescomments,
									'forms' => $forms,
									'ooout' => $ooout,
									'images' => $images,
									'facility' => $result_info ['facility'],
									'highlighter_value' => $highlighterData ['highlighter_value'],
									'text_color' => $nnote ['text_color'],
									'text_color_cut' => $nnote ['text_color_cut'],
									'username' => $nnote ['user_id'],
									'notes_pin' => $userPin,
									'signature' => $nnote ['signature'],
									'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
									'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
									'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
									'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
									'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
									'is_offline' => $nnote ['is_offline'],
									'taskadded' => $nnote ['taskadded'],
									'checklist_status' => $nnote ['checklist_status'],
									'is_private' => $nnote ['is_private'],
									'share_notes' => $nnote ['share_notes'],
									'review_notes' => $nnote ['review_notes'],
									'is_private_strike' => $nnote ['is_private_strike'],
									'notes_type' => $nnote ['notes_type'],
									'strike_note_type' => $nnote ['strike_note_type'],
									'task_time' => $task_time,
									'assign_to' => $nnote ['assign_to'],
									'notestasks' => $notestasks,
									'notesmedicationtasks' => $notesmedicationtasks,
									
									'grandtotal' => $grandtotal,
									'ograndtotal' => $ograndtotal,
									'user_file' => $user_file,
									'is_user_face' => $nnote ['is_user_face'],
									'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
									'original_task_time' => $original_task_time,
									'geolocation_info' => $geolocation_info,
									'approvaltask' => $approvaltask,
									'notes_file' => $nnote ['notes_file'],
									'keyword_file' => $nnote ['keyword_file'],
									'emp_tag_id' => $nnote ['emp_tag_id'],
									'is_forms' => $nnote ['is_forms'],
									'is_reminder' => $nnote ['is_reminder'],
									'task_type' => $nnote ['task_type'],
									'visitor_log' => $nnote ['visitor_log'],
									'is_tag' => $nnote ['is_tag'],
									'is_archive' => $nnote ['is_archive'],
									'form_type' => $nnote ['form_type'],
									'generate_report' => $nnote ['generate_report'],
									'is_census' => $nnote ['is_census'],
									'is_android' => $nnote ['is_android'],
									'alltag' => $alltag,
									'remdata' => $remdata,
									
									'strike_user_name' => $nnote ['strike_user_id'],
									'strike_pin' => $nnote ['strike_pin'],
									'strike_signature' => $nnote ['strike_signature'],
									'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
							);
						}
						
						$PDF_HEADER_TITLE = $reportdata ['name'];
						$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
						
						$template = new Template ();
						$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
						$template->data ['journals'] = $notess;
						$template->data ['facility'] = $facility;
						$template->data ['note_info'] = $note_info;
						$template->data ['t2facility'] = $t2facility;
						$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
						$template->data ['headerString'] = $headerString;
						$template->data ['recurrence'] = $reportdata ['recurrence'];
						$template->data ['load'] = $this->load;
						
						if ($reportdata ['report_format'] == '1') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
						}
						if ($reportdata ['report_format'] == '0') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
						}
						
						$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
						$outputfolder222 = DIR_IMAGE . 'files/';
						
						$file_dir = $outputfolder222 . $filename;
						
						$fh = fopen ( $file_dir, 'w' );
						fwrite ( $fh, $html );
						fclose ( $fh );
						
						// echo "<hr>";
						$notes_file = $filename;
						$outputFolder = $file_dir;
						$s3file = "";
						if ($this->config->get ( 'enable_storage' ) == '1') {
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
							
							// var_dump($s3file);
						}
						
						if ($this->config->get ( 'enable_storage' ) == '2') {
							
							require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
							// uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL . $notes_file;
						}
						
						if ($this->config->get ( 'enable_storage' ) == '3') {
							$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
						}
						
						$message334 = "";
						
						$result = array ();
						$result ['s3file'] = $s3file;
						$result ['title'] = 'Automated Daily Report';
						$result ['message'] = "Automated Daily Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
						$result ['date_added'] = $date_added;
						
						$message334 .= $this->scheduleemailtemplate ( $result );
						
						$edata1 = array ();
						$edata1 ['message'] = $message334;
						$edata1 ['subject'] = 'Daily Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
						$edata1 ['useremailids'] = $useremails;
						
						$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
						
						$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', facilities_id = '" . $report_form ['facilities_id'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', recurrence = 'daily', scheduler_report_ids = '', is_group = '0',is_group_email = '1' ";
						
						$this->db->query ( $sql2 );
						$scheduler_url_id = $this->db->getLastId ();
						
						if ($this->config->get ( 'enable_storage' ) != '3') {
							// var_dump($file_dir);
							unlink ( $file_dir );
						}
						
						// unlink($dirpath.$filename);
					}
					
					// }
					// Weekly
					if ($reportdata ['recurrence'] == '2') {
						$dayName = date ( 'l', strtotime ( $date_added ) );
						$d = strtotime ( $date_added );
						$weekd = $reportdata ['recurnce_week'];
						
						$end_week = strtotime ( $weekd, $d );
						
						$filter_date_end = date ( "m-d-Y", $end_week );
						$end222 = date ( "Y-m-d", $end_week );
						// var_dump($filter_date_end);
						// var_dump($end222);
						$filter_date_start = date ( 'm-d-Y', strtotime ( '' . $weekd . ' last week' ) );
						// var_dump($filter_date_start);
						
						$ffdata = array (
								'sort' => $sort,
								'order' => $order,
								'filter_date_start' => $filter_date_start,
								'filter_date_end' => $filter_date_end,
								'facilities_id' => $facilities_id,
								'task_type' => $task_type,
								'highlighter_id' => $highlighter_id,
								'task_search' => $task_search,
								'form_search' => $form_search,
								'assign_to' => $assign_to,
								'emp_tag_id' => $emp_tag_id1,
								'emp_tag_id1' => $emp_tag_id1,
								'user_id' => $username,
								'search_keyword' => $search_keyword,
								'search_time_start' => $search_time_start,
								'search_time_to' => $search_time_to,
								'keyword_id' => $keyword_id,
								'child_facility_search' => $child_facility_search,
								'customer_key' => $reportdata ['customer_key'],
								'start' => 0,
								'limit' => 50000 
						);
						// var_dump($ffdata);
						// echo "ma week 1";
						if (IS_WAREHOUSE == '1') {
							$this->load->model ( 'syndb/syndb' );
							$fdata = array ();
							$fdata ['schedule'] = 1;
							$this->model_syndb_syndb->addsync ( $fdata );
						}
						
						$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
						
						foreach ( $nnotes as $nnote ) {
							
							$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
							$keyImageSrc11 = "";
							if ($nnote ['keyword_file'] == '1') {
								$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
								foreach ( $allkeywords as $keyword ) {
									$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
									$noteskeywords [] = array (
											'keyword_file_url' => $keyword ['keyword_file_url'] 
									);
								}
							}
							if ($nnote ['highlighter_id'] > 0) {
								$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
							} else {
								$highlighterData = array ();
							}
							$images = array ();
							if ($nnote ['notes_file'] == '1') {
								$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
								
								foreach ( $allimages as $image ) {
									$images [] = array (
											'media_user_id' => $image ['media_user_id'],
											'notes_type' => $image ['notes_type'],
											'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
											'media_signature' => $image ['media_signature'],
											'media_pin' => $image ['media_pin'],
											'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
									);
								}
							}
							$alltag = array ();
							if ($nnote ['emp_tag_id'] == '1') {
								$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
							} else {
								$alltag = array ();
							}
							
							if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
								$userPin = $nnote ['notes_pin'];
							} else {
								$userPin = '';
							}
							
							if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
								$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
							} else {
								$task_time = "";
							}
							
							$notestasks = array ();
							$grandtotal = 0;
							
							$ograndtotal = 0;
							
							if ($nnote ['task_type'] == '1') {
								$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
								
								foreach ( $alltasks as $alltask ) {
									$grandtotal = $grandtotal + $alltask ['capacity'];
									$tags_ids_names = '';
									
									if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
										
										foreach ( $tags_ids1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$tags_ids_names .= $emp_tag_id . ', ';
											}
										}
									}
									
									$out_tags_ids_names = "";
									$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
									
									if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
										$i = 0;
										
										$ooout = '1';
										// var_dump($tags_ids1);
										
										foreach ( $tags_ids1 as $tag1 ) {
											
											$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info12 ['emp_first_name']) {
												$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info12 ['emp_tag_id'];
											}
											
											if ($tags_info12) {
												$out_tags_ids_names .= $emp_tag_id . ', ';
											}
											
											$i ++;
										}
										
										// $ograndtotal = $i;
									} else {
										$ooout = '2';
									}
									
									// var_dump($ograndtotal);
									
									if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notestasks [] = array (
											'notes_by_task_id' => $alltask ['notes_by_task_id'],
											'locations_id' => $alltask ['locations_id'],
											'task_type' => $alltask ['task_type'],
											'task_content' => $alltask ['task_content'],
											'user_id' => $alltask ['user_id'],
											'signature' => $alltask ['signature'],
											'notes_pin' => $alltask ['notes_pin'],
											'task_time' => $alltask ['task_time'],
											// 'media_url' => $alltask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltask ['capacity'],
											'location_name' => $alltask ['location_name'],
											'location_type' => $alltask ['location_type'],
											'notes_task_type' => $alltask ['notes_task_type'],
											'task_comments' => $alltask ['task_comments'],
											'role_call' => $alltask ['role_call'],
											'medication_attach_url' => $medication_attach_url,
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
											'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
											'tags_ids_names' => $tags_ids_names,
											'out_tags_ids_names' => $out_tags_ids_names 
									);
								}
							}
							
							$notesmedicationtasks = array ();
							if ($nnote ['task_type'] == '2') {
								$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
								
								foreach ( $alltmasks as $alltmask ) {
									
									if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
										$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
									}
									
									if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notesmedicationtasks [] = array (
											'notes_by_task_id' => $alltmask ['notes_by_task_id'],
											'locations_id' => $alltmask ['locations_id'],
											'task_type' => $alltmask ['task_type'],
											'task_content' => $alltmask ['task_content'],
											'user_id' => $alltmask ['user_id'],
											'signature' => $alltmask ['signature'],
											'notes_pin' => $alltmask ['notes_pin'],
											'task_time' => $taskTime,
											// 'media_url' => $alltmask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltmask ['capacity'],
											'location_name' => $alltmask ['location_name'],
											'location_type' => $alltmask ['location_type'],
											'notes_task_type' => $alltmask ['notes_task_type'],
											'tags_id' => $alltmask ['tags_id'],
											'drug_name' => $alltmask ['drug_name'],
											'dose' => $alltmask ['dose'],
											'drug_type' => $alltmask ['drug_type'],
											'quantity' => $alltmask ['quantity'],
											'frequency' => $alltmask ['frequency'],
											'instructions' => $alltmask ['instructions'],
											'count' => $alltmask ['count'],
											'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
											'task_comments' => $alltmask ['task_comments'],
											'role_call' => $alltmask ['role_call'],
											'medication_file_upload' => $alltmask ['medication_file_upload'],
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
									);
								}
							}
							
							if ($nnote ['task_type'] == '6') {
								$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
							} else {
								$approvaltask = array ();
							}
							
							if ($nnote ['task_type'] == '3') {
								$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
							} else {
								$geolocation_info = array ();
							}
							
							if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
								$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
							} else {
								$original_task_time = "";
							}
							
							if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
								$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
							} else {
								$user_file = "";
							}
							
							$notescomments = array ();
							if ($nnote ['is_comment'] == '1') {
								$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
							} else {
								$allcomments = array ();
							}
							
							if ($allcomments) {
								foreach ( $allcomments as $allcomment ) {
									$commentskeywords = array ();
									if ($allcomment ['keyword_file'] == '1') {
										$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
									} else {
										$aallkeywords = array ();
									}
									
									if ($aallkeywords) {
										$keyImageSrc12 = array ();
										$keyname = array ();
										foreach ( $aallkeywords as $callkeyword ) {
											$commentskeywords [] = array (
													'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
													'notes_id' => $callkeyword ['notes_id'],
													'comment_id' => $callkeyword ['comment_id'],
													'keyword_id' => $callkeyword ['keyword_id'],
													'keyword_name' => $callkeyword ['keyword_name'],
													'keyword_file_url' => $callkeyword ['keyword_file_url'],
													'keyword_image' => $callkeyword ['keyword_image'],
													'img_icon' => $callkeyword ['keyword_file_url'] 
											);
										}
									}
									$notescomments [] = array (
											'comment_id' => $allcomment ['comment_id'],
											'notes_id' => $allcomment ['notes_id'],
											'facilities_id' => $allcomment ['facilities_id'],
											'comment' => $allcomment ['comment'],
											'user_id' => $allcomment ['user_id'],
											'notes_pin' => $allcomment ['notes_pin'],
											'signature' => $allcomment ['signature'],
											'user_file' => $allcomment ['user_file'],
											'is_user_face' => $allcomment ['is_user_face'],
											'date_added' => $allcomment ['date_added'],
											'comment_date' => $allcomment ['comment_date'],
											'notes_type' => $allcomment ['notes_type'],
											'commentskeywords' => $commentskeywords 
									);
								}
							}
							
							$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
							$forms = array ();
							foreach ( $allforms as $allform ) {
								
								if ($allform ['form_type'] == '3') {
									$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
								}
								
								$forms [] = array (
										'form_type_id' => $allform ['form_type_id'],
										'notes_id' => $allform ['notes_id'],
										'form_type' => $allform ['form_type'],
										'custom_form_type' => $allform ['custom_form_type'],
										'user_id' => $allform ['user_id'],
										'signature' => $allform ['signature'],
										'notes_pin' => $allform ['notes_pin'],
										'incident_number' => $allform ['incident_number'],
										'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
										'href' => $form_url 
								);
							}
							
							$notess [] = array (
									'notes_id' => $nnote ['notes_id'],
									'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
									'forms' => $forms,
									'noteskeywords' => $noteskeywords,
									'notescomments' => $notescomments,
									'ooout' => $ooout,
									'images' => $images,
									'facility' => $result_info ['facility'],
									'highlighter_value' => $highlighterData ['highlighter_value'],
									'text_color' => $nnote ['text_color'],
									'text_color_cut' => $nnote ['text_color_cut'],
									'username' => $nnote ['user_id'],
									'notes_pin' => $userPin,
									'signature' => $nnote ['signature'],
									'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
									'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
									'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
									'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
									'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
									'is_offline' => $nnote ['is_offline'],
									'taskadded' => $nnote ['taskadded'],
									'checklist_status' => $nnote ['checklist_status'],
									'is_private' => $nnote ['is_private'],
									'share_notes' => $nnote ['share_notes'],
									'review_notes' => $nnote ['review_notes'],
									'is_private_strike' => $nnote ['is_private_strike'],
									'notes_type' => $nnote ['notes_type'],
									'strike_note_type' => $nnote ['strike_note_type'],
									'task_time' => $task_time,
									'assign_to' => $nnote ['assign_to'],
									'notestasks' => $notestasks,
									'notesmedicationtasks' => $notesmedicationtasks,
									
									'grandtotal' => $grandtotal,
									'ograndtotal' => $ograndtotal,
									'user_file' => $user_file,
									'is_user_face' => $nnote ['is_user_face'],
									'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
									'original_task_time' => $original_task_time,
									'geolocation_info' => $geolocation_info,
									'approvaltask' => $approvaltask,
									'notes_file' => $nnote ['notes_file'],
									'keyword_file' => $nnote ['keyword_file'],
									'emp_tag_id' => $nnote ['emp_tag_id'],
									'is_forms' => $nnote ['is_forms'],
									'is_reminder' => $nnote ['is_reminder'],
									'task_type' => $nnote ['task_type'],
									'visitor_log' => $nnote ['visitor_log'],
									'is_tag' => $nnote ['is_tag'],
									'is_archive' => $nnote ['is_archive'],
									'form_type' => $nnote ['form_type'],
									'generate_report' => $nnote ['generate_report'],
									'is_census' => $nnote ['is_census'],
									'is_android' => $nnote ['is_android'],
									'alltag' => $alltag,
									'remdata' => $remdata,
									
									'strike_user_name' => $nnote ['strike_user_id'],
									'strike_pin' => $nnote ['strike_pin'],
									'strike_signature' => $nnote ['strike_signature'],
									'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
							);
						}
						
						$PDF_HEADER_TITLE = $reportdata ['name'];
						$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
						
						$template = new Template ();
						$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
						$template->data ['journals'] = $notess;
						$template->data ['facility'] = $facility;
						$template->data ['note_info'] = $note_info;
						$template->data ['t2facility'] = $t2facility;
						$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
						$template->data ['headerString'] = $headerString;
						$template->data ['recurrence'] = $reportdata ['recurrence'];
						$template->data ['load'] = $this->load;
						
						if ($reportdata ['report_format'] == '1') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
						}
						if ($reportdata ['report_format'] == '0') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
						}
						
						$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
						$outputfolder222 = DIR_IMAGE . 'files/';
						
						$file_dir = $outputfolder222 . $filename;
						
						$fh = fopen ( $file_dir, 'w' );
						fwrite ( $fh, $html );
						fclose ( $fh );
						
						// echo "<hr>";
						$notes_file = $filename;
						$outputFolder = $file_dir;
						$s3file = "";
						if ($this->config->get ( 'enable_storage' ) == '1') {
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
							
							// var_dump($s3file);
						}
						
						if ($this->config->get ( 'enable_storage' ) == '2') {
							
							require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
							// uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL . $notes_file;
						}
						
						if ($this->config->get ( 'enable_storage' ) == '3') {
							$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
						}
						
						$message334 = "";
						
						$result = array ();
						$result ['s3file'] = $s3file;
						$result ['title'] = 'Automated Weekly Report';
						$result ['message'] = "Automated Weekly Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
						$result ['date_added'] = $date_added;
						
						$message334 .= $this->scheduleemailtemplate ( $result );
						
						$edata1 = array ();
						$edata1 ['message'] = $message334;
						$edata1 ['subject'] = 'Weekly Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
						$edata1 ['useremailids'] = $useremails;
						
						$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
						
						$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', recurrence = 'weekly', scheduler_report_ids = '', is_group = '0',is_group_email = '1', facilities_id = '" . $report_form ['facilities_id'] . "' ";
						
						$this->db->query ( $sql2 );
						$scheduler_url_id = $this->db->getLastId ();
						
						if ($this->config->get ( 'enable_storage' ) != '3') {
							// var_dump($file_dir);
							unlink ( $file_dir );
							unlink ( $dirpath . $filename );
						}
					}
					// Monthly
					if ($reportdata ['recurrence'] == '3') {
						
						$dayName = date ( 'd', strtotime ( $date_added ) );
						// var_dump($dayName);
						
						$d = strtotime ( $date_added );
						$recurnce_monthly = $reportdata ['recurnce_monthly'];
						
						$filter_date_start = date ( 'm-d-Y', strtotime ( '-1 month', strtotime ( $date_added ) ) );
						$filter_date_end = date ( 'm-d-Y' );
						
						$ffdata = array (
								'sort' => $sort,
								'order' => $order,
								'filter_date_start' => $filter_date_start,
								'filter_date_end' => $filter_date_end,
								'facilities_id' => $facilities_id,
								'task_type' => $task_type,
								'highlighter_id' => $highlighter_id,
								'task_search' => $task_search,
								'form_search' => $form_search,
								'assign_to' => $assign_to,
								'emp_tag_id' => $emp_tag_id1,
								'emp_tag_id1' => $emp_tag_id1,
								'user_id' => $username,
								'search_keyword' => $search_keyword,
								'search_time_start' => $search_time_start,
								'search_time_to' => $search_time_to,
								'keyword_id' => $keyword_id,
								'child_facility_search' => $child_facility_search,
								'customer_key' => $reportdata ['customer_key'],
								'start' => 0,
								'limit' => 50000 
						);
						
						// echo "ma monthly 1";
						if (IS_WAREHOUSE == '1') {
							$this->load->model ( 'syndb/syndb' );
							$fdata = array ();
							$fdata ['schedule'] = 1;
							$this->model_syndb_syndb->addsync ( $fdata );
						}
						// $order_total = $this->model_journal_journal->getTotalnotess($ffdata);
						
						$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
						
						foreach ( $nnotes as $nnote ) {
							
							$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
							$keyImageSrc11 = "";
							if ($nnote ['keyword_file'] == '1') {
								$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
								foreach ( $allkeywords as $keyword ) {
									$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
									$noteskeywords [] = array (
											'keyword_file_url' => $keyword ['keyword_file_url'] 
									);
								}
							}
							if ($nnote ['highlighter_id'] > 0) {
								$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
							} else {
								$highlighterData = array ();
							}
							
							$images = array ();
							if ($nnote ['notes_file'] == '1') {
								$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
								
								foreach ( $allimages as $image ) {
									$images [] = array (
											'media_user_id' => $image ['media_user_id'],
											'notes_type' => $image ['notes_type'],
											'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
											'media_signature' => $image ['media_signature'],
											'media_pin' => $image ['media_pin'],
											'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
									);
								}
							}
							$alltag = array ();
							if ($nnote ['emp_tag_id'] == '1') {
								$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
							} else {
								$alltag = array ();
							}
							
							if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
								$userPin = $nnote ['notes_pin'];
							} else {
								$userPin = '';
							}
							
							if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
								$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
							} else {
								$task_time = "";
							}
							
							$notestasks = array ();
							$grandtotal = 0;
							
							$ograndtotal = 0;
							
							if ($nnote ['task_type'] == '1') {
								$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
								
								foreach ( $alltasks as $alltask ) {
									$grandtotal = $grandtotal + $alltask ['capacity'];
									$tags_ids_names = '';
									
									if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
										
										foreach ( $tags_ids1 as $tag1 ) {
											$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info1 ['emp_first_name']) {
												$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info1 ['emp_tag_id'];
											}
											
											if ($tags_info1) {
												$tags_ids_names .= $emp_tag_id . ', ';
											}
										}
									}
									
									$out_tags_ids_names = "";
									$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
									
									if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
										$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
										$i = 0;
										
										$ooout = '1';
										// var_dump($tags_ids1);
										
										foreach ( $tags_ids1 as $tag1 ) {
											
											$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
											
											if ($tags_info12 ['emp_first_name']) {
												$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
											} else {
												$emp_tag_id = $tags_info12 ['emp_tag_id'];
											}
											
											if ($tags_info12) {
												$out_tags_ids_names .= $emp_tag_id . ', ';
											}
											
											$i ++;
										}
										
										// $ograndtotal = $i;
									} else {
										$ooout = '2';
									}
									
									// var_dump($ograndtotal);
									
									if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notestasks [] = array (
											'notes_by_task_id' => $alltask ['notes_by_task_id'],
											'locations_id' => $alltask ['locations_id'],
											'task_type' => $alltask ['task_type'],
											'task_content' => $alltask ['task_content'],
											'user_id' => $alltask ['user_id'],
											'signature' => $alltask ['signature'],
											'notes_pin' => $alltask ['notes_pin'],
											'task_time' => $alltask ['task_time'],
											// 'media_url' => $alltask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltask ['capacity'],
											'location_name' => $alltask ['location_name'],
											'location_type' => $alltask ['location_type'],
											'notes_task_type' => $alltask ['notes_task_type'],
											'task_comments' => $alltask ['task_comments'],
											'role_call' => $alltask ['role_call'],
											'medication_attach_url' => $medication_attach_url,
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
											'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
											'tags_ids_names' => $tags_ids_names,
											'out_tags_ids_names' => $out_tags_ids_names 
									);
								}
							}
							
							$notesmedicationtasks = array ();
							if ($nnote ['task_type'] == '2') {
								$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
								
								foreach ( $alltmasks as $alltmask ) {
									
									if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
										$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
									}
									
									if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
										$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$media_url = "";
									}
									
									if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
										$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
									} else {
										$medication_attach_url = "";
									}
									
									$notesmedicationtasks [] = array (
											'notes_by_task_id' => $alltmask ['notes_by_task_id'],
											'locations_id' => $alltmask ['locations_id'],
											'task_type' => $alltmask ['task_type'],
											'task_content' => $alltmask ['task_content'],
											'user_id' => $alltmask ['user_id'],
											'signature' => $alltmask ['signature'],
											'notes_pin' => $alltmask ['notes_pin'],
											'task_time' => $taskTime,
											// 'media_url' => $alltmask['media_url'],
											'media_url' => $media_url,
											'capacity' => $alltmask ['capacity'],
											'location_name' => $alltmask ['location_name'],
											'location_type' => $alltmask ['location_type'],
											'notes_task_type' => $alltmask ['notes_task_type'],
											'tags_id' => $alltmask ['tags_id'],
											'drug_name' => $alltmask ['drug_name'],
											'dose' => $alltmask ['dose'],
											'drug_type' => $alltmask ['drug_type'],
											'quantity' => $alltmask ['quantity'],
											'frequency' => $alltmask ['frequency'],
											'instructions' => $alltmask ['instructions'],
											'count' => $alltmask ['count'],
											'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
											'task_comments' => $alltmask ['task_comments'],
											'role_call' => $alltmask ['role_call'],
											'medication_file_upload' => $alltmask ['medication_file_upload'],
											'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
									);
								}
							}
							
							if ($nnote ['task_type'] == '6') {
								$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
							} else {
								$approvaltask = array ();
							}
							
							if ($nnote ['task_type'] == '3') {
								$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
							} else {
								$geolocation_info = array ();
							}
							
							if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
								$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
							} else {
								$original_task_time = "";
							}
							
							if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
								$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
							} else {
								$user_file = "";
							}
							
							$notescomments = array ();
							if ($nnote ['is_comment'] == '1') {
								$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
							} else {
								$allcomments = array ();
							}
							
							if ($allcomments) {
								foreach ( $allcomments as $allcomment ) {
									$commentskeywords = array ();
									if ($allcomment ['keyword_file'] == '1') {
										$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
									} else {
										$aallkeywords = array ();
									}
									
									if ($aallkeywords) {
										$keyImageSrc12 = array ();
										$keyname = array ();
										foreach ( $aallkeywords as $callkeyword ) {
											$commentskeywords [] = array (
													'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
													'notes_id' => $callkeyword ['notes_id'],
													'comment_id' => $callkeyword ['comment_id'],
													'keyword_id' => $callkeyword ['keyword_id'],
													'keyword_name' => $callkeyword ['keyword_name'],
													'keyword_file_url' => $callkeyword ['keyword_file_url'],
													'keyword_image' => $callkeyword ['keyword_image'],
													'img_icon' => $callkeyword ['keyword_file_url'] 
											);
										}
									}
									$notescomments [] = array (
											'comment_id' => $allcomment ['comment_id'],
											'notes_id' => $allcomment ['notes_id'],
											'facilities_id' => $allcomment ['facilities_id'],
											'comment' => $allcomment ['comment'],
											'user_id' => $allcomment ['user_id'],
											'notes_pin' => $allcomment ['notes_pin'],
											'signature' => $allcomment ['signature'],
											'user_file' => $allcomment ['user_file'],
											'is_user_face' => $allcomment ['is_user_face'],
											'date_added' => $allcomment ['date_added'],
											'comment_date' => $allcomment ['comment_date'],
											'notes_type' => $allcomment ['notes_type'],
											'commentskeywords' => $commentskeywords 
									);
								}
							}
							
							$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
							$forms = array ();
							foreach ( $allforms as $allform ) {
								
								if ($allform ['form_type'] == '3') {
									$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
								}
								
								$forms [] = array (
										'form_type_id' => $allform ['form_type_id'],
										'notes_id' => $allform ['notes_id'],
										'form_type' => $allform ['form_type'],
										'custom_form_type' => $allform ['custom_form_type'],
										'user_id' => $allform ['user_id'],
										'signature' => $allform ['signature'],
										'notes_pin' => $allform ['notes_pin'],
										'incident_number' => $allform ['incident_number'],
										'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
										'href' => $form_url 
								);
							}
							$notess [] = array (
									'notes_id' => $nnote ['notes_id'],
									'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
									'forms' => $forms,
									'noteskeywords' => $noteskeywords,
									'notescomments' => $notescomments,
									'ooout' => $ooout,
									'images' => $images,
									'facility' => $result_info ['facility'],
									'highlighter_value' => $highlighterData ['highlighter_value'],
									'text_color' => $nnote ['text_color'],
									'text_color_cut' => $nnote ['text_color_cut'],
									'username' => $nnote ['user_id'],
									'notes_pin' => $userPin,
									'signature' => $nnote ['signature'],
									'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
									'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
									'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
									'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
									'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
									'is_offline' => $nnote ['is_offline'],
									'taskadded' => $nnote ['taskadded'],
									'checklist_status' => $nnote ['checklist_status'],
									'is_private' => $nnote ['is_private'],
									'share_notes' => $nnote ['share_notes'],
									'review_notes' => $nnote ['review_notes'],
									'is_private_strike' => $nnote ['is_private_strike'],
									'notes_type' => $nnote ['notes_type'],
									'strike_note_type' => $nnote ['strike_note_type'],
									'task_time' => $task_time,
									'assign_to' => $nnote ['assign_to'],
									'notestasks' => $notestasks,
									'notesmedicationtasks' => $notesmedicationtasks,
									
									'grandtotal' => $grandtotal,
									'ograndtotal' => $ograndtotal,
									'user_file' => $user_file,
									'is_user_face' => $nnote ['is_user_face'],
									'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
									'original_task_time' => $original_task_time,
									'geolocation_info' => $geolocation_info,
									'approvaltask' => $approvaltask,
									'notes_file' => $nnote ['notes_file'],
									'keyword_file' => $nnote ['keyword_file'],
									'emp_tag_id' => $nnote ['emp_tag_id'],
									'is_forms' => $nnote ['is_forms'],
									'is_reminder' => $nnote ['is_reminder'],
									'task_type' => $nnote ['task_type'],
									'visitor_log' => $nnote ['visitor_log'],
									'is_tag' => $nnote ['is_tag'],
									'is_archive' => $nnote ['is_archive'],
									'form_type' => $nnote ['form_type'],
									'generate_report' => $nnote ['generate_report'],
									'is_census' => $nnote ['is_census'],
									'is_android' => $nnote ['is_android'],
									'alltag' => $alltag,
									'remdata' => $remdata,
									
									'strike_user_name' => $nnote ['strike_user_id'],
									'strike_pin' => $nnote ['strike_pin'],
									'strike_signature' => $nnote ['strike_signature'],
									'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
							);
						}
						
						$PDF_HEADER_TITLE = $reportdata ['name'];
						$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
						
						$template = new Template ();
						$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
						$template->data ['journals'] = $notess;
						$template->data ['facility'] = $facility;
						$template->data ['note_info'] = $note_info;
						$template->data ['t2facility'] = $t2facility;
						$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
						$template->data ['headerString'] = $headerString;
						$template->data ['recurrence'] = $reportdata ['recurrence'];
						$template->data ['load'] = $this->load;
						
						if ($reportdata ['report_format'] == '1') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
						}
						if ($reportdata ['report_format'] == '0') {
							$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
						}
						
						$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
						$outputfolder222 = DIR_IMAGE . 'files/';
						
						$file_dir = $outputfolder222 . $filename;
						
						$fh = fopen ( $file_dir, 'w' );
						fwrite ( $fh, $html );
						fclose ( $fh );
						
						// echo "<hr>";
						$notes_file = $filename;
						$outputFolder = $file_dir;
						$s3file = "";
						if ($this->config->get ( 'enable_storage' ) == '1') {
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
							
							// var_dump($s3file);
						}
						
						if ($this->config->get ( 'enable_storage' ) == '2') {
							
							require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
							// uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL . $notes_file;
						}
						
						if ($this->config->get ( 'enable_storage' ) == '3') {
							$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
						}
						
						/*
						 * if($reportdata['report_type'] == '1'){
						 * require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
						 * $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						 * $pdf->SetCreator(PDF_CREATOR);
						 * $pdf->SetAuthor('');
						 * $pdf->SetTitle('REPORT');
						 * $pdf->SetSubject('REPORT');
						 * $pdf->SetKeywords('REPORT');
						 *
						 * $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						 *
						 * // set margins
						 * $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						 * $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						 * $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						 *
						 *
						 * // set auto page breaks
						 * $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						 *
						 * // set image scale factor
						 * $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						 * if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
						 * require_once(dirname(__FILE__).'/lang/eng.php');
						 * $pdf->setLanguageArray($l);
						 * }
						 *
						 * $pdf->SetFont('helvetica', '', 9);
						 * $pdf->AddPage();
						 *
						 * $pdf->writeHTML($html, true, 0, true, 0);
						 * $pdf->lastPage();
						 * $pdfname = 'report_' . rand() . '.pdf';
						 * $dirpath = DIR_IMAGE .'share/';
						 * $dirpath22 = DIR_IMAGE .'share/'.$pdfname;
						 * $pdf->Output($dirpath. $pdfname, 'F');
						 *
						 * $message33 = "";
						 * $message33 .= "Automated Monthly Report";
						 *
						 * $edata1 = array();
						 * $edata1['message'] = $message33;
						 * $edata1['subject'] = 'Monthly Report';
						 * $edata1['useremailids'] = $useremails;
						 * //$edata1['dirpath'] = $outputfolder222;
						 * //$edata1['filename'] = $notes_file;
						 *
						 * $email_status = $this->model_api_emailapi->sendmail($edata1);
						 *
						 * if($this->config->get('enable_storage') == '1'){
						 *
						 * $s3filepdf = $this->awsimageconfig->uploadFile($pdfname, $dirpath22, $nform['facilities_id']);
						 *
						 * //var_dump($s3filepdf);
						 * }
						 *
						 * if($this->config->get('enable_storage') == '2'){
						 * $notes_file = $pdfname;
						 * $outputFolder = $dirpath22;
						 * require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
						 * //uploadBlobSample($blobClient, $outputFolder, $notes_file);
						 * $s3filepdf = AZURE_URL. $pdfname;
						 * }
						 *
						 * if($this->config->get('enable_storage') == '3'){
						 * $s3filepdf = HTTP_SERVER . 'image/files/'.$pdfname;
						 * }
						 * }
						 *
						 * if($reportdata['report_type'] == '3'){
						 *
						 * }
						 */
						
						$message334 = "";
						
						$result = array ();
						$result ['s3file'] = $s3file;
						$result ['title'] = 'Automated Monthly Report';
						$result ['message'] = "Automated Monthly Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
						$result ['date_added'] = $date_added;
						
						$message334 .= $this->scheduleemailtemplate ( $result );
						
						$edata1 = array ();
						$edata1 ['message'] = $message334;
						$edata1 ['subject'] = 'Monthly Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
						$edata1 ['useremailids'] = $useremails;
						
						$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
						
						$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', scheduler_report_ids = '', is_group = '0', recurrence = 'monthly',is_group_email = '1', facilities_id = '" . $report_form ['facilities_id'] . "' ";
						
						$this->db->query ( $sql2 );
						$scheduler_url_id = $this->db->getLastId ();
						
						if ($this->config->get ( 'enable_storage' ) != '3') {
							// var_dump($file_dir);
							unlink ( $file_dir );
							// unlink($dirpath.$filename);
						}
					}
				} else {
					if ($reportdata ['recurrence'] == '1') {
						
						if ($report_form ['date_from'] != '0000-00-00') {
							
							$start_date = new DateTime ( $report_form ['date_from'] );
							$since_start = $start_date->diff ( new DateTime ( $report_form ['date_to'] ) );
							
							if ($since_start->d > 0) {
								$filter_date_start = date ( 'm-d-Y', strtotime ( ' -' . $since_start->d . ' days', strtotime ( $date_added ) ) );
							} else {
								$filter_date_start = date ( 'm-d-Y' );
							}
							$filter_date_end = date ( 'm-d-Y' );
						} else {
							$filter_date_start = date ( 'm-d-Y' );
							$filter_date_end = date ( 'm-d-Y' );
						}
						
						$scheduler_operation_time = date ( 'H:i:s', strtotime ( $reportdata ['scheduler_operation_time'] ) );
						
						var_dump ( $scheduler_operation_time );
						var_dump ( $currenttime );
						
						if ($currenttime >= $scheduler_operation_time) {
							echo 55555;
							$ffdata = array (
									'sort' => $sort,
									'order' => $order,
									'filter_date_start' => $filter_date_start,
									'filter_date_end' => $filter_date_end,
									'facilities_id' => $facilities_id,
									'task_type' => $task_type,
									'highlighter_id' => $highlighter_id,
									'task_search' => $task_search,
									'form_search' => $form_search,
									'assign_to' => $assign_to,
									'emp_tag_id' => $emp_tag_id1,
									'emp_tag_id1' => $emp_tag_id1,
									'user_id' => $username,
									'search_keyword' => $search_keyword,
									'search_time_start' => $search_time_start,
									'search_time_to' => $search_time_to,
									'child_facility_search' => $child_facility_search,
									'keyword_id' => $keyword_id,
									'customer_key' => $reportdata ['customer_key'],
									'start' => 0,
									'limit' => 50000 
							);
							
							// var_dump($ffdata);
							
							$sqls23d = "SELECT * FROM `" . DB_PREFIX . "scheduler_url` where `date_added` BETWEEN  '" . $noteDate1 . " 00:00:00 ' AND  '" . $noteDate1 . " 23:59:59' and is_created = '1' and recurrence = 'daily'  and scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "' ";
							$query4d = $this->db->query ( $sqls23d );
							
							if ($query4d->num_rows == 0) {
								
								echo "not manual daily 1 <hr>";
								if (IS_WAREHOUSE == '1') {
									$this->load->model ( 'syndb/syndb' );
									$fdata = array ();
									$fdata ['schedule'] = 1;
									$this->model_syndb_syndb->addsync ( $fdata );
								}
								$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
								// die;
								foreach ( $nnotes as $nnote ) {
									
									$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
									$keyImageSrc11 = "";
									if ($nnote ['keyword_file'] == '1') {
										$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
										foreach ( $allkeywords as $keyword ) {
											$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
											$noteskeywords [] = array (
													'keyword_file_url' => $keyword ['keyword_file_url'] 
											);
										}
									}
									if ($nnote ['highlighter_id'] > 0) {
										$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
									} else {
										$highlighterData = array ();
									}
									
									$images = array ();
									if ($nnote ['notes_file'] == '1') {
										$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
										
										foreach ( $allimages as $image ) {
											$images [] = array (
													'media_user_id' => $image ['media_user_id'],
													'notes_type' => $image ['notes_type'],
													'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
													'media_signature' => $image ['media_signature'],
													'media_pin' => $image ['media_pin'],
													'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
											);
										}
									}
									$alltag = array ();
									if ($nnote ['emp_tag_id'] == '1') {
										$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
									} else {
										$alltag = array ();
									}
									
									if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
										$userPin = $nnote ['notes_pin'];
									} else {
										$userPin = '';
									}
									
									if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
										$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
									} else {
										$task_time = "";
									}
									
									$notestasks = array ();
									$grandtotal = 0;
									
									$ograndtotal = 0;
									
									if ($nnote ['task_type'] == '1') {
										$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
										
										foreach ( $alltasks as $alltask ) {
											$grandtotal = $grandtotal + $alltask ['capacity'];
											$tags_ids_names = '';
											
											if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
												
												foreach ( $tags_ids1 as $tag1 ) {
													$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info1 ['emp_first_name']) {
														$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info1 ['emp_tag_id'];
													}
													
													if ($tags_info1) {
														$tags_ids_names .= $emp_tag_id . ', ';
													}
												}
											}
											
											$out_tags_ids_names = "";
											$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
											
											if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
												$i = 0;
												
												$ooout = '1';
												// var_dump($tags_ids1);
												
												foreach ( $tags_ids1 as $tag1 ) {
													
													$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info12 ['emp_first_name']) {
														$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info12 ['emp_tag_id'];
													}
													
													if ($tags_info12) {
														$out_tags_ids_names .= $emp_tag_id . ', ';
													}
													
													$i ++;
												}
												
												// $ograndtotal = $i;
											} else {
												$ooout = '2';
											}
											
											// var_dump($ograndtotal);
											
											if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notestasks [] = array (
													'notes_by_task_id' => $alltask ['notes_by_task_id'],
													'locations_id' => $alltask ['locations_id'],
													'task_type' => $alltask ['task_type'],
													'task_content' => $alltask ['task_content'],
													'user_id' => $alltask ['user_id'],
													'signature' => $alltask ['signature'],
													'notes_pin' => $alltask ['notes_pin'],
													'task_time' => $alltask ['task_time'],
													// 'media_url' => $alltask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltask ['capacity'],
													'location_name' => $alltask ['location_name'],
													'location_type' => $alltask ['location_type'],
													'notes_task_type' => $alltask ['notes_task_type'],
													'task_comments' => $alltask ['task_comments'],
													'role_call' => $alltask ['role_call'],
													'medication_attach_url' => $medication_attach_url,
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
													'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
													'tags_ids_names' => $tags_ids_names,
													'out_tags_ids_names' => $out_tags_ids_names 
											);
										}
									}
									
									$notesmedicationtasks = array ();
									if ($nnote ['task_type'] == '2') {
										$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
										
										foreach ( $alltmasks as $alltmask ) {
											
											if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
												$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
											}
											
											if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notesmedicationtasks [] = array (
													'notes_by_task_id' => $alltmask ['notes_by_task_id'],
													'locations_id' => $alltmask ['locations_id'],
													'task_type' => $alltmask ['task_type'],
													'task_content' => $alltmask ['task_content'],
													'user_id' => $alltmask ['user_id'],
													'signature' => $alltmask ['signature'],
													'notes_pin' => $alltmask ['notes_pin'],
													'task_time' => $taskTime,
													// 'media_url' => $alltmask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltmask ['capacity'],
													'location_name' => $alltmask ['location_name'],
													'location_type' => $alltmask ['location_type'],
													'notes_task_type' => $alltmask ['notes_task_type'],
													'tags_id' => $alltmask ['tags_id'],
													'drug_name' => $alltmask ['drug_name'],
													'dose' => $alltmask ['dose'],
													'drug_type' => $alltmask ['drug_type'],
													'quantity' => $alltmask ['quantity'],
													'frequency' => $alltmask ['frequency'],
													'instructions' => $alltmask ['instructions'],
													'count' => $alltmask ['count'],
													'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
													'task_comments' => $alltmask ['task_comments'],
													'role_call' => $alltmask ['role_call'],
													'medication_file_upload' => $alltmask ['medication_file_upload'],
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
											);
										}
									}
									
									if ($nnote ['task_type'] == '6') {
										$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
									} else {
										$approvaltask = array ();
									}
									
									if ($nnote ['task_type'] == '3') {
										$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
									} else {
										$geolocation_info = array ();
									}
									
									if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
										$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
									} else {
										$original_task_time = "";
									}
									
									if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
										$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
									} else {
										$user_file = "";
									}
									
									$notescomments = array ();
									if ($nnote ['is_comment'] == '1') {
										$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
									} else {
										$allcomments = array ();
									}
									
									if ($allcomments) {
										foreach ( $allcomments as $allcomment ) {
											$commentskeywords = array ();
											if ($allcomment ['keyword_file'] == '1') {
												$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
											} else {
												$aallkeywords = array ();
											}
											
											if ($aallkeywords) {
												$keyImageSrc12 = array ();
												$keyname = array ();
												foreach ( $aallkeywords as $callkeyword ) {
													$commentskeywords [] = array (
															'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
															'notes_id' => $callkeyword ['notes_id'],
															'comment_id' => $callkeyword ['comment_id'],
															'keyword_id' => $callkeyword ['keyword_id'],
															'keyword_name' => $callkeyword ['keyword_name'],
															'keyword_file_url' => $callkeyword ['keyword_file_url'],
															'keyword_image' => $callkeyword ['keyword_image'],
															'img_icon' => $callkeyword ['keyword_file_url'] 
													);
												}
											}
											$notescomments [] = array (
													'comment_id' => $allcomment ['comment_id'],
													'notes_id' => $allcomment ['notes_id'],
													'facilities_id' => $allcomment ['facilities_id'],
													'comment' => $allcomment ['comment'],
													'user_id' => $allcomment ['user_id'],
													'notes_pin' => $allcomment ['notes_pin'],
													'signature' => $allcomment ['signature'],
													'user_file' => $allcomment ['user_file'],
													'is_user_face' => $allcomment ['is_user_face'],
													'date_added' => $allcomment ['date_added'],
													'comment_date' => $allcomment ['comment_date'],
													'notes_type' => $allcomment ['notes_type'],
													'commentskeywords' => $commentskeywords 
											);
										}
									}
									
									$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
									$forms = array ();
									foreach ( $allforms as $allform ) {
										
										if ($allform ['form_type'] == '3') {
											$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
										}
										
										$forms [] = array (
												'form_type_id' => $allform ['form_type_id'],
												'notes_id' => $allform ['notes_id'],
												'form_type' => $allform ['form_type'],
												'custom_form_type' => $allform ['custom_form_type'],
												'user_id' => $allform ['user_id'],
												'signature' => $allform ['signature'],
												'notes_pin' => $allform ['notes_pin'],
												'incident_number' => $allform ['incident_number'],
												'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
												'href' => $form_url 
										);
									}
									
									$notess [] = array (
											'notes_id' => $nnote ['notes_id'],
											'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
											'forms' => $forms,
											'noteskeywords' => $noteskeywords,
											'notescomments' => $notescomments,
											'ooout' => $ooout,
											'images' => $images,
											'facility' => $result_info ['facility'],
											'highlighter_value' => $highlighterData ['highlighter_value'],
											'text_color' => $nnote ['text_color'],
											'text_color_cut' => $nnote ['text_color_cut'],
											'username' => $nnote ['user_id'],
											'notes_pin' => $userPin,
											'signature' => $nnote ['signature'],
											'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
											'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
											'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
											'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
											'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
											'is_offline' => $nnote ['is_offline'],
											'taskadded' => $nnote ['taskadded'],
											'checklist_status' => $nnote ['checklist_status'],
											'is_private' => $nnote ['is_private'],
											'share_notes' => $nnote ['share_notes'],
											'review_notes' => $nnote ['review_notes'],
											'is_private_strike' => $nnote ['is_private_strike'],
											'notes_type' => $nnote ['notes_type'],
											'strike_note_type' => $nnote ['strike_note_type'],
											'task_time' => $task_time,
											'assign_to' => $nnote ['assign_to'],
											'notestasks' => $notestasks,
											'notesmedicationtasks' => $notesmedicationtasks,
											
											'grandtotal' => $grandtotal,
											'ograndtotal' => $ograndtotal,
											'user_file' => $user_file,
											'is_user_face' => $nnote ['is_user_face'],
											'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
											'original_task_time' => $original_task_time,
											'geolocation_info' => $geolocation_info,
											'approvaltask' => $approvaltask,
											'notes_file' => $nnote ['notes_file'],
											'keyword_file' => $nnote ['keyword_file'],
											'emp_tag_id' => $nnote ['emp_tag_id'],
											'is_forms' => $nnote ['is_forms'],
											'is_reminder' => $nnote ['is_reminder'],
											'task_type' => $nnote ['task_type'],
											'visitor_log' => $nnote ['visitor_log'],
											'is_tag' => $nnote ['is_tag'],
											'is_archive' => $nnote ['is_archive'],
											'form_type' => $nnote ['form_type'],
											'generate_report' => $nnote ['generate_report'],
											'is_census' => $nnote ['is_census'],
											'is_android' => $nnote ['is_android'],
											'alltag' => $alltag,
											'remdata' => $remdata,
											
											'strike_user_name' => $nnote ['strike_user_id'],
											'strike_pin' => $nnote ['strike_pin'],
											'strike_signature' => $nnote ['strike_signature'],
											'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
									);
								}
								
								$PDF_HEADER_TITLE = $reportdata ['name'];
								$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
								
								$template = new Template ();
								$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
								$template->data ['journals'] = $notess;
								$template->data ['facility'] = $facility;
								$template->data ['note_info'] = $note_info;
								$template->data ['t2facility'] = $t2facility;
								$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
								$template->data ['headerString'] = $headerString;
								$template->data ['recurrence'] = $reportdata ['recurrence'];
								$template->data ['load'] = $this->load;
								
								if ($reportdata ['report_format'] == '1') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
								}
								if ($reportdata ['report_format'] == '0') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
								}
								
								// var_dump($html);
								// echo "<hr>";
								
								$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
								$outputfolder222 = DIR_IMAGE . 'files/';
								
								$file_dir = $outputfolder222 . $filename;
								
								$fh = fopen ( $file_dir, 'w' );
								fwrite ( $fh, $html );
								fclose ( $fh );
								
								// echo "<hr>";
								$notes_file = $filename;
								$outputFolder = $file_dir;
								$s3file = "";
								if ($this->config->get ( 'enable_storage' ) == '1') {
									
									$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
									
									// var_dump($s3file);
								}
								
								if ($this->config->get ( 'enable_storage' ) == '2') {
									
									require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
									// uploadBlobSample($blobClient, $outputFolder, $notes_file);
									$s3file = AZURE_URL . $notes_file;
								}
								
								if ($this->config->get ( 'enable_storage' ) == '3') {
									$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
								}
								
								$message334 = "";
								
								if ($reportdata ['is_group'] == '0') {
									$result = array ();
									$result ['s3file'] = $s3file;
									$result ['title'] = 'Automated Daily Report';
									$result ['message'] = "Automated Daily Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
									$result ['date_added'] = $date_added;
									
									$message334 .= $this->scheduleemailtemplate ( $result );
									
									$edata1 = array ();
									$edata1 ['message'] = $message334;
									$edata1 ['subject'] = 'Daily Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
									$edata1 ['useremailids'] = $useremails;
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
									$is_group_email = '1';
								} else {
									$is_group_email = '0';
								}
								
								$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', scheduler_report_ids = '" . $reportdata ['scheduler_report_ids'] . "', is_group = '" . $reportdata ['is_group'] . "', recurrence = 'daily',is_created = '1',is_group_email = '" . $is_group_email . "', facilities_id = '" . $report_form ['facilities_id'] . "' ";
								
								$this->db->query ( $sql2 );
								$scheduler_url_id = $this->db->getLastId ();
								
								if ($this->config->get ( 'enable_storage' ) != '3') {
									// var_dump($file_dir);
									unlink ( $file_dir );
								}
								
								// unlink($dirpath.$filename);
							}
						}
					}
					// Weekly
					if ($reportdata ['recurrence'] == '2') {
						$dayName = date ( 'l', strtotime ( $date_added ) );
						$d = strtotime ( $date_added );
						$weekd = $reportdata ['recurnce_week'];
						var_dump ( $dayName );
						var_dump ( $weekd );
						if ($dayName == $weekd) {
							$end_week = strtotime ( $weekd, $d );
							
							$filter_date_end = date ( "m-d-Y", $end_week );
							$end222 = date ( "Y-m-d", $end_week );
							// var_dump($filter_date_end);
							// var_dump($end222);
							$filter_date_start = date ( 'm-d-Y', strtotime ( '' . $weekd . ' last week' ) );
							// var_dump($filter_date_start);
							
							$ffdata = array (
									'sort' => $sort,
									'order' => $order,
									'filter_date_start' => $filter_date_start,
									'filter_date_end' => $filter_date_end,
									'facilities_id' => $facilities_id,
									'task_type' => $task_type,
									'highlighter_id' => $highlighter_id,
									'task_search' => $task_search,
									'form_search' => $form_search,
									'assign_to' => $assign_to,
									'emp_tag_id' => $emp_tag_id1,
									'emp_tag_id1' => $emp_tag_id1,
									'user_id' => $username,
									'search_keyword' => $search_keyword,
									'search_time_start' => $search_time_start,
									'search_time_to' => $search_time_to,
									'child_facility_search' => $child_facility_search,
									'keyword_id' => $keyword_id,
									'customer_key' => $reportdata ['customer_key'],
									'start' => 0,
									'limit' => 50000 
							);
							// var_dump($ffdata);
							
							$sqls23dw = "SELECT * FROM `" . DB_PREFIX . "scheduler_url` where `date_added` BETWEEN  '" . $noteDate1 . " 00:00:00 ' AND  '" . $noteDate1 . " 23:59:59' and is_created = '1' and recurrence = 'weekly' and scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "' ";
							$query4dw = $this->db->query ( $sqls23dw );
							
							if ($query4dw->num_rows == 0) {
								
								echo "not manual week 1 <hr>";
								if (IS_WAREHOUSE == '1') {
									$this->load->model ( 'syndb/syndb' );
									$fdata = array ();
									$fdata ['schedule'] = 1;
									$this->model_syndb_syndb->addsync ( $fdata );
								}
								$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
								
								foreach ( $nnotes as $nnote ) {
									
									$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
									$keyImageSrc11 = "";
									if ($nnote ['keyword_file'] == '1') {
										$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
										foreach ( $allkeywords as $keyword ) {
											$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
											$noteskeywords [] = array (
													'keyword_file_url' => $keyword ['keyword_file_url'] 
											);
										}
									}
									if ($nnote ['highlighter_id'] > 0) {
										$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
									} else {
										$highlighterData = array ();
									}
									
									$images = array ();
									if ($nnote ['notes_file'] == '1') {
										$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
										
										foreach ( $allimages as $image ) {
											$images [] = array (
													'media_user_id' => $image ['media_user_id'],
													'notes_type' => $image ['notes_type'],
													'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
													'media_signature' => $image ['media_signature'],
													'media_pin' => $image ['media_pin'],
													'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
											);
										}
									}
									
									$alltag = array ();
									if ($nnote ['emp_tag_id'] == '1') {
										$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
									} else {
										$alltag = array ();
									}
									
									if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
										$userPin = $nnote ['notes_pin'];
									} else {
										$userPin = '';
									}
									
									if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
										$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
									} else {
										$task_time = "";
									}
									
									$notestasks = array ();
									$grandtotal = 0;
									
									$ograndtotal = 0;
									
									if ($nnote ['task_type'] == '1') {
										$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
										
										foreach ( $alltasks as $alltask ) {
											$grandtotal = $grandtotal + $alltask ['capacity'];
											$tags_ids_names = '';
											
											if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
												
												foreach ( $tags_ids1 as $tag1 ) {
													$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info1 ['emp_first_name']) {
														$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info1 ['emp_tag_id'];
													}
													
													if ($tags_info1) {
														$tags_ids_names .= $emp_tag_id . ', ';
													}
												}
											}
											
											$out_tags_ids_names = "";
											$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
											
											if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
												$i = 0;
												
												$ooout = '1';
												// var_dump($tags_ids1);
												
												foreach ( $tags_ids1 as $tag1 ) {
													
													$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info12 ['emp_first_name']) {
														$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info12 ['emp_tag_id'];
													}
													
													if ($tags_info12) {
														$out_tags_ids_names .= $emp_tag_id . ', ';
													}
													
													$i ++;
												}
												
												// $ograndtotal = $i;
											} else {
												$ooout = '2';
											}
											
											// var_dump($ograndtotal);
											
											if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notestasks [] = array (
													'notes_by_task_id' => $alltask ['notes_by_task_id'],
													'locations_id' => $alltask ['locations_id'],
													'task_type' => $alltask ['task_type'],
													'task_content' => $alltask ['task_content'],
													'user_id' => $alltask ['user_id'],
													'signature' => $alltask ['signature'],
													'notes_pin' => $alltask ['notes_pin'],
													'task_time' => $alltask ['task_time'],
													// 'media_url' => $alltask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltask ['capacity'],
													'location_name' => $alltask ['location_name'],
													'location_type' => $alltask ['location_type'],
													'notes_task_type' => $alltask ['notes_task_type'],
													'task_comments' => $alltask ['task_comments'],
													'role_call' => $alltask ['role_call'],
													'medication_attach_url' => $medication_attach_url,
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
													'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
													'tags_ids_names' => $tags_ids_names,
													'out_tags_ids_names' => $out_tags_ids_names 
											);
										}
									}
									
									$notesmedicationtasks = array ();
									if ($nnote ['task_type'] == '2') {
										$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
										
										foreach ( $alltmasks as $alltmask ) {
											
											if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
												$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
											}
											
											if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notesmedicationtasks [] = array (
													'notes_by_task_id' => $alltmask ['notes_by_task_id'],
													'locations_id' => $alltmask ['locations_id'],
													'task_type' => $alltmask ['task_type'],
													'task_content' => $alltmask ['task_content'],
													'user_id' => $alltmask ['user_id'],
													'signature' => $alltmask ['signature'],
													'notes_pin' => $alltmask ['notes_pin'],
													'task_time' => $taskTime,
													// 'media_url' => $alltmask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltmask ['capacity'],
													'location_name' => $alltmask ['location_name'],
													'location_type' => $alltmask ['location_type'],
													'notes_task_type' => $alltmask ['notes_task_type'],
													'tags_id' => $alltmask ['tags_id'],
													'drug_name' => $alltmask ['drug_name'],
													'dose' => $alltmask ['dose'],
													'drug_type' => $alltmask ['drug_type'],
													'quantity' => $alltmask ['quantity'],
													'frequency' => $alltmask ['frequency'],
													'instructions' => $alltmask ['instructions'],
													'count' => $alltmask ['count'],
													'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
													'task_comments' => $alltmask ['task_comments'],
													'role_call' => $alltmask ['role_call'],
													'medication_file_upload' => $alltmask ['medication_file_upload'],
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
											);
										}
									}
									
									if ($nnote ['task_type'] == '6') {
										$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
									} else {
										$approvaltask = array ();
									}
									
									if ($nnote ['task_type'] == '3') {
										$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
									} else {
										$geolocation_info = array ();
									}
									
									if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
										$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
									} else {
										$original_task_time = "";
									}
									
									if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
										$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
									} else {
										$user_file = "";
									}
									
									$notescomments = array ();
									if ($nnote ['is_comment'] == '1') {
										$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
									} else {
										$allcomments = array ();
									}
									
									if ($allcomments) {
										foreach ( $allcomments as $allcomment ) {
											$commentskeywords = array ();
											if ($allcomment ['keyword_file'] == '1') {
												$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
											} else {
												$aallkeywords = array ();
											}
											
											if ($aallkeywords) {
												$keyImageSrc12 = array ();
												$keyname = array ();
												foreach ( $aallkeywords as $callkeyword ) {
													$commentskeywords [] = array (
															'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
															'notes_id' => $callkeyword ['notes_id'],
															'comment_id' => $callkeyword ['comment_id'],
															'keyword_id' => $callkeyword ['keyword_id'],
															'keyword_name' => $callkeyword ['keyword_name'],
															'keyword_file_url' => $callkeyword ['keyword_file_url'],
															'keyword_image' => $callkeyword ['keyword_image'],
															'img_icon' => $callkeyword ['keyword_file_url'] 
													);
												}
											}
											$notescomments [] = array (
													'comment_id' => $allcomment ['comment_id'],
													'notes_id' => $allcomment ['notes_id'],
													'facilities_id' => $allcomment ['facilities_id'],
													'comment' => $allcomment ['comment'],
													'user_id' => $allcomment ['user_id'],
													'notes_pin' => $allcomment ['notes_pin'],
													'signature' => $allcomment ['signature'],
													'user_file' => $allcomment ['user_file'],
													'is_user_face' => $allcomment ['is_user_face'],
													'date_added' => $allcomment ['date_added'],
													'comment_date' => $allcomment ['comment_date'],
													'notes_type' => $allcomment ['notes_type'],
													'commentskeywords' => $commentskeywords 
											);
										}
									}
									
									$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
									$forms = array ();
									foreach ( $allforms as $allform ) {
										
										if ($allform ['form_type'] == '3') {
											$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
										}
										
										$forms [] = array (
												'form_type_id' => $allform ['form_type_id'],
												'notes_id' => $allform ['notes_id'],
												'form_type' => $allform ['form_type'],
												'custom_form_type' => $allform ['custom_form_type'],
												'user_id' => $allform ['user_id'],
												'signature' => $allform ['signature'],
												'notes_pin' => $allform ['notes_pin'],
												'incident_number' => $allform ['incident_number'],
												'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
												'href' => $form_url 
										);
									}
									
									$notess [] = array (
											'notes_id' => $nnote ['notes_id'],
											'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
											'forms' => $forms,
											'noteskeywords' => $noteskeywords,
											'notescomments' => $notescomments,
											'ooout' => $ooout,
											'images' => $images,
											'facility' => $result_info ['facility'],
											'highlighter_value' => $highlighterData ['highlighter_value'],
											'text_color' => $nnote ['text_color'],
											'text_color_cut' => $nnote ['text_color_cut'],
											'username' => $nnote ['user_id'],
											'notes_pin' => $userPin,
											'signature' => $nnote ['signature'],
											'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
											'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
											'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
											'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
											'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
											'is_offline' => $nnote ['is_offline'],
											'taskadded' => $nnote ['taskadded'],
											'checklist_status' => $nnote ['checklist_status'],
											'is_private' => $nnote ['is_private'],
											'share_notes' => $nnote ['share_notes'],
											'review_notes' => $nnote ['review_notes'],
											'is_private_strike' => $nnote ['is_private_strike'],
											'notes_type' => $nnote ['notes_type'],
											'strike_note_type' => $nnote ['strike_note_type'],
											'task_time' => $task_time,
											'assign_to' => $nnote ['assign_to'],
											'notestasks' => $notestasks,
											'notesmedicationtasks' => $notesmedicationtasks,
											
											'grandtotal' => $grandtotal,
											'ograndtotal' => $ograndtotal,
											'user_file' => $user_file,
											'is_user_face' => $nnote ['is_user_face'],
											'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
											'original_task_time' => $original_task_time,
											'geolocation_info' => $geolocation_info,
											'approvaltask' => $approvaltask,
											'notes_file' => $nnote ['notes_file'],
											'keyword_file' => $nnote ['keyword_file'],
											'emp_tag_id' => $nnote ['emp_tag_id'],
											'is_forms' => $nnote ['is_forms'],
											'is_reminder' => $nnote ['is_reminder'],
											'task_type' => $nnote ['task_type'],
											'visitor_log' => $nnote ['visitor_log'],
											'is_tag' => $nnote ['is_tag'],
											'is_archive' => $nnote ['is_archive'],
											'form_type' => $nnote ['form_type'],
											'generate_report' => $nnote ['generate_report'],
											'is_census' => $nnote ['is_census'],
											'is_android' => $nnote ['is_android'],
											'alltag' => $alltag,
											'remdata' => $remdata,
											
											'strike_user_name' => $nnote ['strike_user_id'],
											'strike_pin' => $nnote ['strike_pin'],
											'strike_signature' => $nnote ['strike_signature'],
											'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
									);
								}
								
								$PDF_HEADER_TITLE = $reportdata ['name'];
								$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
								
								$template = new Template ();
								$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
								$template->data ['journals'] = $notess;
								$template->data ['facility'] = $facility;
								$template->data ['note_info'] = $note_info;
								$template->data ['t2facility'] = $t2facility;
								$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
								$template->data ['headerString'] = $headerString;
								$template->data ['recurrence'] = $reportdata ['recurrence'];
								$template->data ['load'] = $this->load;
								
								if ($reportdata ['report_format'] == '1') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
								}
								if ($reportdata ['report_format'] == '0') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
								}
								
								$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
								$outputfolder222 = DIR_IMAGE . 'files/';
								
								$file_dir = $outputfolder222 . $filename;
								
								$fh = fopen ( $file_dir, 'w' );
								fwrite ( $fh, $html );
								fclose ( $fh );
								
								// echo "<hr>";
								$notes_file = $filename;
								$outputFolder = $file_dir;
								$s3file = "";
								if ($this->config->get ( 'enable_storage' ) == '1') {
									
									$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
									
									// var_dump($s3file);
								}
								
								if ($this->config->get ( 'enable_storage' ) == '2') {
									
									require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
									// uploadBlobSample($blobClient, $outputFolder, $notes_file);
									$s3file = AZURE_URL . $notes_file;
								}
								
								if ($this->config->get ( 'enable_storage' ) == '3') {
									$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
								}
								
								$message334 = "";
								if ($reportdata ['is_group'] == '0') {
									$result = array ();
									$result ['s3file'] = $s3file;
									$result ['title'] = 'Automated Weekly Report';
									$result ['message'] = "Automated Weekly Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
									$result ['date_added'] = $date_added;
									
									$message334 .= $this->scheduleemailtemplate ( $result );
									
									$edata1 = array ();
									$edata1 ['message'] = $message334;
									$edata1 ['subject'] = 'Weekly Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
									$edata1 ['useremailids'] = $useremails;
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
									
									$is_group_email = '1';
								} else {
									$is_group_email = '0';
								}
								
								$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', scheduler_report_ids = '" . $reportdata ['scheduler_report_ids'] . "', is_group = '" . $reportdata ['is_group'] . "', recurrence = 'weekly',is_created = '1',is_group_email = '" . $is_group_email . "', facilities_id = '" . $report_form ['facilities_id'] . "' ";
								
								$this->db->query ( $sql2 );
								$scheduler_url_id = $this->db->getLastId ();
								
								if ($this->config->get ( 'enable_storage' ) != '3') {
									// var_dump($file_dir);
									unlink ( $file_dir );
									unlink ( $dirpath . $filename );
								}
							}
						}
					}
					// Monthly
					if ($reportdata ['recurrence'] == '3') {
						// echo 3434434;
						$dayName = date ( 'd', strtotime ( $date_added ) );
						// var_dump($dayName);
						
						$d = strtotime ( $date_added );
						$recurnce_monthly = $reportdata ['recurnce_monthly'];
						var_dump ( $dayName );
						var_dump ( $recurnce_monthly );
						if ($dayName == $recurnce_monthly) {
							$filter_date_start = date ( 'm-d-Y', strtotime ( '-1 month', strtotime ( $date_added ) ) );
							$filter_date_end = date ( 'm-d-Y' );
							
							$ffdata = array (
									'sort' => $sort,
									'order' => $order,
									'filter_date_start' => $filter_date_start,
									'filter_date_end' => $filter_date_end,
									'facilities_id' => $facilities_id,
									'task_type' => $task_type,
									'highlighter_id' => $highlighter_id,
									'task_search' => $task_search,
									'form_search' => $form_search,
									'assign_to' => $assign_to,
									'emp_tag_id' => $emp_tag_id1,
									'emp_tag_id1' => $emp_tag_id1,
									'user_id' => $username,
									'search_keyword' => $search_keyword,
									'search_time_start' => $search_time_start,
									'search_time_to' => $search_time_to,
									'child_facility_search' => $child_facility_search,
									'keyword_id' => $keyword_id,
									'customer_key' => $reportdata ['customer_key'],
									'start' => 0,
									'limit' => 50000 
							);
							
							// $order_total = $this->model_journal_journal->getTotalnotess($ffdata);
							$sqls23dm = "SELECT * FROM `" . DB_PREFIX . "scheduler_url` where `date_added` BETWEEN  '" . $noteDate1 . " 00:00:00 ' AND  '" . $noteDate1 . " 23:59:59' and is_created = '1' and recurrence = 'monthly' and scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "' ";
							$query4dm = $this->db->query ( $sqls23dm );
							
							if ($query4dm->num_rows == 0) {
								
								echo "not manual monthly 1";
								if (IS_WAREHOUSE == '1') {
									$this->load->model ( 'syndb/syndb' );
									$fdata = array ();
									$fdata ['schedule'] = 1;
									$this->model_syndb_syndb->addsync ( $fdata );
								}
								$nnotes = $this->model_journal_journal->getnotess ( $ffdata );
								
								foreach ( $nnotes as $nnote ) {
									
									$result_info = $this->model_facilities_facilities->getfacilities ( $nnote ['facilities_id'] );
									$keyImageSrc11 = "";
									if ($nnote ['keyword_file'] == '1') {
										$allkeywords = $this->model_notes_notes->getnoteskeywors ( $nnote ['notes_id'] );
										foreach ( $allkeywords as $keyword ) {
											$keyImageSrc11 .= '<img src="' . $keyword ['keyword_file_url'] . '" style="width:35px;height35px">';
											$noteskeywords [] = array (
													'keyword_file_url' => $keyword ['keyword_file_url'] 
											);
										}
									}
									if ($nnote ['highlighter_id'] > 0) {
										$highlighterData = $this->model_setting_highlighter->gethighlighter ( $nnote ['highlighter_id'] );
									} else {
										$highlighterData = array ();
									}
									$images = array ();
									if ($nnote ['notes_file'] == '1') {
										$allimages = $this->model_notes_notes->getImages ( $nnote ['notes_id'] );
										
										foreach ( $allimages as $image ) {
											$images [] = array (
													'media_user_id' => $image ['media_user_id'],
													'notes_type' => $image ['notes_type'],
													'media_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $image ['media_date_added'] ) ),
													'media_signature' => $image ['media_signature'],
													'media_pin' => $image ['media_pin'],
													'notes_file_url' => $this->url->link ( 'notes/notes/displayFile', '' . '&notes_media_id=' . $image ['notes_media_id'], 'SSL' ) 
											);
										}
									}
									$alltag = array ();
									if ($nnote ['emp_tag_id'] == '1') {
										$alltag = $this->model_notes_notes->getNotesTags ( $nnote ['notes_id'] );
									} else {
										$alltag = array ();
									}
									
									if ($nnote ['notes_pin'] != null && $nnote ['notes_pin'] != "") {
										$userPin = $nnote ['notes_pin'];
									} else {
										$userPin = '';
									}
									
									if ($nnote ['task_time'] != null && $nnote ['task_time'] != "00:00:00") {
										$task_time = date ( 'h:i A', strtotime ( $nnote ['task_time'] ) );
									} else {
										$task_time = "";
									}
									
									$notestasks = array ();
									$grandtotal = 0;
									
									$ograndtotal = 0;
									
									if ($nnote ['task_type'] == '1') {
										$alltasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '1' );
										
										foreach ( $alltasks as $alltask ) {
											$grandtotal = $grandtotal + $alltask ['capacity'];
											$tags_ids_names = '';
											
											if ($alltask ['tags_ids'] != null && $alltask ['tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['tags_ids'] );
												
												foreach ( $tags_ids1 as $tag1 ) {
													$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info1 ['emp_first_name']) {
														$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info1 ['emp_tag_id'];
													}
													
													if ($tags_info1) {
														$tags_ids_names .= $emp_tag_id . ', ';
													}
												}
											}
											
											$out_tags_ids_names = "";
											$ograndtotal = $ograndtotal + $alltask ['out_capacity'];
											
											if ($alltask ['out_tags_ids'] != null && $alltask ['out_tags_ids'] != "") {
												$tags_ids1 = explode ( ',', $alltask ['out_tags_ids'] );
												$i = 0;
												
												$ooout = '1';
												// var_dump($tags_ids1);
												
												foreach ( $tags_ids1 as $tag1 ) {
													
													$tags_info12 = $this->model_setting_tags->getTag ( $tag1 );
													
													if ($tags_info12 ['emp_first_name']) {
														$emp_tag_id = $tags_info12 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
													} else {
														$emp_tag_id = $tags_info12 ['emp_tag_id'];
													}
													
													if ($tags_info12) {
														$out_tags_ids_names .= $emp_tag_id . ', ';
													}
													
													$i ++;
												}
												
												// $ograndtotal = $i;
											} else {
												$ooout = '2';
											}
											
											// var_dump($ograndtotal);
											
											if ($alltask ['media_url'] != null && $alltask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltask ['medication_attach_url'] != null && $alltask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notestasks [] = array (
													'notes_by_task_id' => $alltask ['notes_by_task_id'],
													'locations_id' => $alltask ['locations_id'],
													'task_type' => $alltask ['task_type'],
													'task_content' => $alltask ['task_content'],
													'user_id' => $alltask ['user_id'],
													'signature' => $alltask ['signature'],
													'notes_pin' => $alltask ['notes_pin'],
													'task_time' => $alltask ['task_time'],
													// 'media_url' => $alltask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltask ['capacity'],
													'location_name' => $alltask ['location_name'],
													'location_type' => $alltask ['location_type'],
													'notes_task_type' => $alltask ['notes_task_type'],
													'task_comments' => $alltask ['task_comments'],
													'role_call' => $alltask ['role_call'],
													'medication_attach_url' => $medication_attach_url,
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltask ['date_added'] ) ),
													'room_current_date_time' => date ( 'h:i A', strtotime ( $alltask ['room_current_date_time'] ) ),
													'tags_ids_names' => $tags_ids_names,
													'out_tags_ids_names' => $out_tags_ids_names 
											);
										}
									}
									
									$notesmedicationtasks = array ();
									if ($nnote ['task_type'] == '2') {
										$alltmasks = $this->model_notes_notes->getnotesBytasks ( $nnote ['notes_id'], '2' );
										
										foreach ( $alltmasks as $alltmask ) {
											
											if ($alltmask ['task_time'] != null && $alltmask ['task_time'] != '00:00:00') {
												$taskTime = date ( 'h:i A', strtotime ( $alltmask ['task_time'] ) );
											}
											
											if ($alltmask ['media_url'] != null && $alltmask ['media_url'] != "") {
												$media_url = $this->url->link ( 'notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$media_url = "";
											}
											
											if ($alltmask ['medication_attach_url'] != null && $alltmask ['medication_attach_url'] != "") {
												$medication_attach_url = $this->url->link ( 'notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask ['notes_by_task_id'], 'SSL' );
											} else {
												$medication_attach_url = "";
											}
											
											$notesmedicationtasks [] = array (
													'notes_by_task_id' => $alltmask ['notes_by_task_id'],
													'locations_id' => $alltmask ['locations_id'],
													'task_type' => $alltmask ['task_type'],
													'task_content' => $alltmask ['task_content'],
													'user_id' => $alltmask ['user_id'],
													'signature' => $alltmask ['signature'],
													'notes_pin' => $alltmask ['notes_pin'],
													'task_time' => $taskTime,
													// 'media_url' => $alltmask['media_url'],
													'media_url' => $media_url,
													'capacity' => $alltmask ['capacity'],
													'location_name' => $alltmask ['location_name'],
													'location_type' => $alltmask ['location_type'],
													'notes_task_type' => $alltmask ['notes_task_type'],
													'tags_id' => $alltmask ['tags_id'],
													'drug_name' => $alltmask ['drug_name'],
													'dose' => $alltmask ['dose'],
													'drug_type' => $alltmask ['drug_type'],
													'quantity' => $alltmask ['quantity'],
													'frequency' => $alltmask ['frequency'],
													'instructions' => $alltmask ['instructions'],
													'count' => $alltmask ['count'],
													'createtask_by_group_id' => $alltmask ['createtask_by_group_id'],
													'task_comments' => $alltmask ['task_comments'],
													'role_call' => $alltmask ['role_call'],
													'medication_file_upload' => $alltmask ['medication_file_upload'],
													'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $alltmask ['date_added'] ) ) 
											);
										}
									}
									
									if ($nnote ['task_type'] == '6') {
										$approvaltask = $this->model_notes_notes->getapprovaltask ( $nnote ['task_id'] );
									} else {
										$approvaltask = array ();
									}
									
									if ($nnote ['task_type'] == '3') {
										$geolocation_info = $this->model_notes_notes->getGeolocation ( $nnote ['notes_id'] );
									} else {
										$geolocation_info = array ();
									}
									
									if ($nnote ['original_task_time'] != null && $nnote ['original_task_time'] != "00:00:00") {
										$original_task_time = date ( 'h:i A', strtotime ( $nnote ['original_task_time'] ) );
									} else {
										$original_task_time = "";
									}
									
									if ($nnote ['user_file'] != null && $nnote ['user_file'] != "") {
										$user_file = $this->url->link ( 'notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote ['notes_id'], 'SSL' );
									} else {
										$user_file = "";
									}
									
									$notescomments = array ();
									if ($nnote ['is_comment'] == '1') {
										$allcomments = $this->model_notes_notescomment->getcomments ( $nnote ['notes_id'] );
									} else {
										$allcomments = array ();
									}
									
									if ($allcomments) {
										foreach ( $allcomments as $allcomment ) {
											$commentskeywords = array ();
											if ($allcomment ['keyword_file'] == '1') {
												$aallkeywords = $this->model_notes_notescomment->getcommentskeywors ( $allcomment ['comment_id'] );
											} else {
												$aallkeywords = array ();
											}
											
											if ($aallkeywords) {
												$keyImageSrc12 = array ();
												$keyname = array ();
												foreach ( $aallkeywords as $callkeyword ) {
													$commentskeywords [] = array (
															'notes_by_keyword_id' => $callkeyword ['notes_by_keyword_id'],
															'notes_id' => $callkeyword ['notes_id'],
															'comment_id' => $callkeyword ['comment_id'],
															'keyword_id' => $callkeyword ['keyword_id'],
															'keyword_name' => $callkeyword ['keyword_name'],
															'keyword_file_url' => $callkeyword ['keyword_file_url'],
															'keyword_image' => $callkeyword ['keyword_image'],
															'img_icon' => $callkeyword ['keyword_file_url'] 
													);
												}
											}
											$notescomments [] = array (
													'comment_id' => $allcomment ['comment_id'],
													'notes_id' => $allcomment ['notes_id'],
													'facilities_id' => $allcomment ['facilities_id'],
													'comment' => $allcomment ['comment'],
													'user_id' => $allcomment ['user_id'],
													'notes_pin' => $allcomment ['notes_pin'],
													'signature' => $allcomment ['signature'],
													'user_file' => $allcomment ['user_file'],
													'is_user_face' => $allcomment ['is_user_face'],
													'date_added' => $allcomment ['date_added'],
													'comment_date' => $allcomment ['comment_date'],
													'notes_type' => $allcomment ['notes_type'],
													'commentskeywords' => $commentskeywords 
											);
										}
									}
									
									$allforms = $this->model_notes_notes->getforms ( $nnote ['notes_id'] );
									$forms = array ();
									foreach ( $allforms as $allform ) {
										
										if ($allform ['form_type'] == '3') {
											$form_url = HTTPS_SERVER . 'index.php?route=form/form/printform' . '&forms_id=' . $allform ['forms_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'];
										}
										
										$forms [] = array (
												'form_type_id' => $allform ['form_type_id'],
												'notes_id' => $allform ['notes_id'],
												'form_type' => $allform ['form_type'],
												'custom_form_type' => $allform ['custom_form_type'],
												'user_id' => $allform ['user_id'],
												'signature' => $allform ['signature'],
												'notes_pin' => $allform ['notes_pin'],
												'incident_number' => $allform ['incident_number'],
												'form_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) ),
												'href' => $form_url 
										);
									}
									$notess [] = array (
											'notes_id' => $nnote ['notes_id'],
											'notes_description' => $keyImageSrc11 . ' ' . $nnote ['notes_description'],
											'forms' => $forms,
											'noteskeywords' => $noteskeywords,
											'notescomments' => $notescomments,
											'ooout' => $ooout,
											'images' => $images,
											'facility' => $result_info ['facility'],
											'highlighter_value' => $highlighterData ['highlighter_value'],
											'text_color' => $nnote ['text_color'],
											'text_color_cut' => $nnote ['text_color_cut'],
											'username' => $nnote ['user_id'],
											'notes_pin' => $userPin,
											'signature' => $nnote ['signature'],
											'date_added' => date ( 'j, F Y h:i A', strtotime ( $nnote ['date_added'] ) ),
											'note_date' => date ( 'j, F Y h:i A', strtotime ( $nnote ['note_date'] ) ),
											'note_date_time' => date ( 'h:i A', strtotime ( $nnote ['note_date'] ) ),
											'date_added2' => date ( 'D F j, Y', strtotime ( $nnote ['date_added'] ) ),
											'notetime' => date ( 'h:i A', strtotime ( $nnote ['notetime'] ) ),
											'is_offline' => $nnote ['is_offline'],
											'taskadded' => $nnote ['taskadded'],
											'checklist_status' => $nnote ['checklist_status'],
											'is_private' => $nnote ['is_private'],
											'share_notes' => $nnote ['share_notes'],
											'review_notes' => $nnote ['review_notes'],
											'is_private_strike' => $nnote ['is_private_strike'],
											'notes_type' => $nnote ['notes_type'],
											'strike_note_type' => $nnote ['strike_note_type'],
											'task_time' => $task_time,
											'assign_to' => $nnote ['assign_to'],
											'notestasks' => $notestasks,
											'notesmedicationtasks' => $notesmedicationtasks,
											
											'grandtotal' => $grandtotal,
											'ograndtotal' => $ograndtotal,
											'user_file' => $user_file,
											'is_user_face' => $nnote ['is_user_face'],
											'is_approval_required_forms_id' => $nnote ['is_approval_required_forms_id'],
											'original_task_time' => $original_task_time,
											'geolocation_info' => $geolocation_info,
											'approvaltask' => $approvaltask,
											'notes_file' => $nnote ['notes_file'],
											'keyword_file' => $nnote ['keyword_file'],
											'emp_tag_id' => $nnote ['emp_tag_id'],
											'is_forms' => $nnote ['is_forms'],
											'is_reminder' => $nnote ['is_reminder'],
											'task_type' => $nnote ['task_type'],
											'visitor_log' => $nnote ['visitor_log'],
											'is_tag' => $nnote ['is_tag'],
											'is_archive' => $nnote ['is_archive'],
											'form_type' => $nnote ['form_type'],
											'generate_report' => $nnote ['generate_report'],
											'is_census' => $nnote ['is_census'],
											'is_android' => $nnote ['is_android'],
											'alltag' => $alltag,
											'remdata' => $remdata,
											
											'strike_user_name' => $nnote ['strike_user_id'],
											'strike_pin' => $nnote ['strike_pin'],
											'strike_signature' => $nnote ['strike_signature'],
											'strike_date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $nnote ['strike_date_added'] ) ) 
									);
								}
								
								$PDF_HEADER_TITLE = $reportdata ['name'];
								$headerString = "Date: " . $filter_date_start . ' To ' . $filter_date_end . $shift_starttime_hour1 . $facilities_info2 . $highlighter_value2 . $username2 . $keyWord;
								
								$template = new Template ();
								$template->data ['parent_id'] = $reportdata ['scheduler_report_id'];
								$template->data ['journals'] = $notess;
								$template->data ['facility'] = $facility;
								$template->data ['note_info'] = $note_info;
								$template->data ['t2facility'] = $t2facility;
								$template->data ['PDF_HEADER_TITLE'] = $PDF_HEADER_TITLE;
								$template->data ['headerString'] = $headerString;
								$template->data ['recurrence'] = $reportdata ['recurrence'];
								$template->data ['load'] = $this->load;
								
								if ($reportdata ['report_format'] == '1') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/dailyactivitylog.php' );
								}
								if ($reportdata ['report_format'] == '0') {
									$html = $template->fetch ( $this->config->get ( 'config_template' ) . '/template/report/default.php' );
								}
								
								$filename = 'report_' . date ( 'Ymd' ) . '_' . rand () . '.html';
								$outputfolder222 = DIR_IMAGE . 'files/';
								
								$file_dir = $outputfolder222 . $filename;
								
								$fh = fopen ( $file_dir, 'w' );
								fwrite ( $fh, $html );
								fclose ( $fh );
								
								// echo "<hr>";
								$notes_file = $filename;
								$outputFolder = $file_dir;
								$s3file = "";
								if ($this->config->get ( 'enable_storage' ) == '1') {
									
									$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $nform ['facilities_id'] );
									
									// var_dump($s3file);
								}
								
								if ($this->config->get ( 'enable_storage' ) == '2') {
									
									require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
									// uploadBlobSample($blobClient, $outputFolder, $notes_file);
									$s3file = AZURE_URL . $notes_file;
								}
								
								if ($this->config->get ( 'enable_storage' ) == '3') {
									$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
								}
								
								/*
								 * if($reportdata['report_type'] == '1'){
								 * require_once(DIR_SYSTEM . 'library/pdf_class/tcpdf.php');
								 * $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
								 * $pdf->SetCreator(PDF_CREATOR);
								 * $pdf->SetAuthor('');
								 * $pdf->SetTitle('REPORT');
								 * $pdf->SetSubject('REPORT');
								 * $pdf->SetKeywords('REPORT');
								 *
								 * $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
								 *
								 * // set margins
								 * $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
								 * $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
								 * $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
								 *
								 *
								 * // set auto page breaks
								 * $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
								 *
								 * // set image scale factor
								 * $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
								 * if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
								 * require_once(dirname(__FILE__).'/lang/eng.php');
								 * $pdf->setLanguageArray($l);
								 * }
								 *
								 * $pdf->SetFont('helvetica', '', 9);
								 * $pdf->AddPage();
								 *
								 * $pdf->writeHTML($html, true, 0, true, 0);
								 * $pdf->lastPage();
								 * $pdfname = 'report_' . rand() . '.pdf';
								 * $dirpath = DIR_IMAGE .'share/';
								 * $dirpath22 = DIR_IMAGE .'share/'.$pdfname;
								 * $pdf->Output($dirpath. $pdfname, 'F');
								 *
								 * $message33 = "";
								 * $message33 .= "Automated Monthly Report";
								 *
								 * $edata1 = array();
								 * $edata1['message'] = $message33;
								 * $edata1['subject'] = 'Monthly Report';
								 * $edata1['useremailids'] = $useremails;
								 * //$edata1['dirpath'] = $outputfolder222;
								 * //$edata1['filename'] = $notes_file;
								 *
								 * $email_status = $this->model_api_emailapi->sendmail($edata1);
								 *
								 * if($this->config->get('enable_storage') == '1'){
								 *
								 * $s3filepdf = $this->awsimageconfig->uploadFile($pdfname, $dirpath22, $nform['facilities_id']);
								 *
								 * //var_dump($s3filepdf);
								 * }
								 *
								 * if($this->config->get('enable_storage') == '2'){
								 * $notes_file = $pdfname;
								 * $outputFolder = $dirpath22;
								 * require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
								 * //uploadBlobSample($blobClient, $outputFolder, $notes_file);
								 * $s3filepdf = AZURE_URL. $pdfname;
								 * }
								 *
								 * if($this->config->get('enable_storage') == '3'){
								 * $s3filepdf = HTTP_SERVER . 'image/files/'.$pdfname;
								 * }
								 * }
								 *
								 * if($reportdata['report_type'] == '3'){
								 *
								 * }
								 */
								
								$message334 = "";
								if ($reportdata ['is_group'] == '0') {
									$result = array ();
									$result ['s3file'] = $s3file;
									$result ['title'] = 'Automated Monthly Report';
									$result ['message'] = "Automated Monthly Report <br> " . $PDF_HEADER_TITLE . ' <br>' . $headerString;
									$result ['date_added'] = $date_added;
									
									$message334 .= $this->scheduleemailtemplate ( $result );
									
									$edata1 = array ();
									$edata1 ['message'] = $message334;
									$edata1 ['subject'] = 'Monthly Report - ' . $facilities_info2_s . ' - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
									$edata1 ['useremailids'] = $useremails;
									
									$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
									
									$is_group_email = '1';
								} else {
									$is_group_email = '0';
								}
								
								$sql2 = "INSERT INTO `" . DB_PREFIX . "scheduler_url` SET html_file_url = '" . $this->db->escape ( $s3file ) . "', pdf_file_url = '" . $this->db->escape ( $s3filepdf ) . "', xls_file_url = '" . $this->db->escape ( $xls_file_url ) . "', email_ids= '" . $this->db->escape ( $useremail_ids ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "', customer_key = '" . $reportdata ['customer_key'] . "', scheduler_report_id = '" . $reportdata ['scheduler_report_id'] . "', scheduler_report_ids = '" . $reportdata ['scheduler_report_ids'] . "', is_group = '" . $reportdata ['is_group'] . "', recurrence = 'monthly',is_created = '1',is_group_email = '" . $is_group_email . "', facilities_id = '" . $report_form ['facilities_id'] . "' ";
								
								$this->db->query ( $sql2 );
								$scheduler_url_id = $this->db->getLastId ();
								
								if ($this->config->get ( 'enable_storage' ) != '3') {
									// var_dump($file_dir);
									unlink ( $file_dir );
									// unlink($dirpath.$filename);
								}
							}
						}
					}
				}
			}
		}
		
		if ($this->request->get ['manual_link'] != '1') {
			$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
			
			$sqltawre = "SELECT * from " . DB_PREFIX . "scheduler_url where is_group = '2' and is_group_email = '0' ";
			$qttwre = $this->db->query ( $sqltawre );
			// echo "<hr>";
			// var_dump($qttwre->num_rows);
			// die;
			
			$rurl = array ();
			$email_ids = array ();
			if ($qttwre->num_rows > 0) {
				
				foreach ( $qttwre->rows as $reporturl ) {
					// var_dump($reporturl['scheduler_report_id']);
					$rurl [$reporturl ['facilities_id']] [] = $reporturl ['html_file_url'];
					
					if ($reporturl ['email_ids'] != null && $reporturl ['email_ids'] != "") {
						$email_ids [] = $reporturl ['email_ids'];
					}
					
					if ($reporturl ['scheduler_report_ids'] != null && $reporturl ['scheduler_report_ids'] != "") {
						$scheduler_report_ids = explode ( ',', $reporturl ['scheduler_report_ids'] );
						
						foreach ( $scheduler_report_ids as $scheduler_report_ids22 ) {
							
							$sqltawreg = "SELECT * from " . DB_PREFIX . "scheduler_url where scheduler_report_id = '" . $scheduler_report_ids22 . "' and is_group_email = '0' ";
							$qttwreg = $this->db->query ( $sqltawreg );
							
							$greport_info = $qttwreg->row;
							// var_dump($greport_info['scheduler_report_id']);
							
							if ($greport_info != null && $greport_info != "") {
								if ($reporturl ['scheduler_report_id'] != $greport_info ['scheduler_report_id']) {
									$rurl [$greport_info ['facilities_id']] [] = $greport_info ['html_file_url'];
								}
								
								if ($greport_info ['email_ids'] != null && $greport_info ['email_ids'] != "") {
									$email_ids [] = $greport_info ['email_ids'];
								}
							}
							
							$sql3ess = "UPDATE `" . DB_PREFIX . "scheduler_url` SET is_group_email = '1' WHERE scheduler_url_id = '" . $greport_info ['scheduler_url_id'] . "'";
							$query = $this->db->query ( $sql3ess );
						}
					}
					
					// var_dump($rurl);
					// echo "<hr>";
					if ($email_ids != null && $email_ids != "") {
						foreach ( $email_ids as $email_id ) {
							$email_ids2 [] = explode ( ',', $email_id );
						}
					}
					// var_dump($email_ids2);
					$newids = array ();
					foreach ( $email_ids2 as $email_ids21 ) {
						foreach ( $email_ids21 as $email_ids212 ) {
							$newids [] = $email_ids212;
						}
					}
					$ids = array_unique ( $newids );
					// var_dump($ids);
					
					$sql3es = "UPDATE `" . DB_PREFIX . "scheduler_url` SET is_group_email = '1' WHERE scheduler_url_id = '" . $reporturl ['scheduler_url_id'] . "'";
					$query = $this->db->query ( $sql3es );
					// var_dump($rurl);
					$result = array ();
					$result ['rurl'] = $rurl;
					$result ['title'] = 'Automated Report';
					$result ['message'] = "Automated Report ";
					$result ['date_added'] = $date_added;
					
					$message334 .= $this->scheduleemailtemplategroup ( $result );
					// var_dump($message334);
					
					$edata1 = array ();
					$edata1 ['message'] = $message334;
					$edata1 ['subject'] = 'Report - ' . date ( 'j, F Y h:i A', strtotime ( $date_added ) );
					$edata1 ['useremailids'] = $ids;
					
					$email_status = $this->model_api_emailapi->sendmail ( $edata1 );
				}
			}
		}
		
		if ($this->request->get ['manual_link'] == '1') {
			echo json_encode ( 1 );
		} else {
			echo "Success";
		}
	}
	public function scheduleemailtemplategroup($result) {
		
		// var_dump($result);
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>' . $result ['title'] . '</title>

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
							<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $result ['title'] . '</h6></td>
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
								<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $result ['message'] . '</p>
								
							</td>
						</tr>
					</table>
				</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
					
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
							<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
							<td>';
		$html .= '<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;"></small></h4> ';
		foreach ( $result ['rurl'] as $keyff => $rurl ) {
			
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $keyff . "'" );
			$facility_info = $query->row;
			
			$html .= '<p>' . $facility_info ['facility'] . '</p> ';
			foreach ( $rurl as $rurl1 ) {
				$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
									<a target="_blank" href="' . $rurl1 . '">' . $rurl1 . '</a>
									</p> ';
			}
		}
		
		$html .= '</td>
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
							' . date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '
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
	public function scheduleemailtemplate($result) {
		
		// var_dump($result);
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>' . $result ['title'] . '</title>

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
							<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">' . $result ['title'] . '</h6></td>
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
								<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">' . $result ['message'] . '</p>
								
							</td>
						</tr>
					</table>
				</div>';
		
		$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
					
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
							<img src="' . HTTP_SERVER . 'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">' . $result ['facility'] . '</small></h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<a target="_blank" href="' . $result ['s3file'] . '">' . $result ['s3file'] . '</a>
								</p>
								
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
							' . date ( 'j, F Y', strtotime ( $result ['date_added'] ) ) . '
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
	public function speechToText() {
		if ($this->config->get ( 'config_transcription' ) == '1') {
			
			$this->load->model ( 'notes/notes' );
			$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "notes_media where audio_attach_type = '1' " );
			$numrow = $query->num_rows;
			
			if ($numrow > 0) {
				// $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
				
				$stturl = "https://speech.googleapis.com/v1p1beta1/speech:longrunningrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
				
				foreach ( $query->rows as $row ) {
					
					$urrl = $row ['audio_attach_url'];
					
					// $upload = file_get_contents($filename);
					$upload = file_get_contents ( $urrl );
					$upload = base64_encode ( $upload );
					$data = array (
							"config" => array(
								/*"encoding"  => "FLAC",
								"sampleRateHertz" => 16000,
								"enableSeparateRecognitionPerChannel" => true,
								"languageCode" => "en-US",
								"enableAutomaticPunctuation" => true,
								"enableSpeakerDiarization" => true,
								"enableWordTimeOffsets" => true,
								"diarizationSpeakerCount" =>  2,
								"useEnhanced" => true,
								"alternativeLanguageCodes" => ["fr-FR", "de-DE"],
								*/
								
								"sampleRateHertz" => 16000,
									'encoding' => 'FLAC',
									'languageCode' => 'en-US',
									'enableWordTimeOffsets' => false,
									'enableAutomaticPunctuation' => true,
									'useEnhanced' => true,
									"enableSpeakerDiarization" => true,
									"diarizationSpeakerCount" => 2,
									"model" => "phone_call" 
							),
							"audio" => array (
									"content" => $upload 
							) 
					);
					
					$jsonData = json_encode ( $data );
					// $headers = array( "Content-Type: audio/flac", "Transfer-Encoding: chunked");
					
					$headers = array (
							"Content-Type: application/json" 
					);
					$ch = curl_init ();
					
					curl_setopt ( $ch, CURLOPT_URL, $stturl );
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
					curl_setopt ( $ch, CURLOPT_POST, TRUE );
					curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, TRUE );
					curl_setopt ( $ch, CURLOPT_POST, true );
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $jsonData );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
					
					$results = curl_exec ( $ch );
					
					// var_dump($results);
					
					$contents = json_decode ( $results, true );
					
					$speech_name = $contents ['name'];
					
					$slq1 = "UPDATE " . DB_PREFIX . "notes_media SET audio_attach_type = '2',speech_name = '" . $this->db->escape ( $speech_name ) . "',is_updated = '1' where notes_media_id = '" . $row ['notes_media_id'] . "'";
					$this->db->query ( $slq1 );
					
					$slq122 = "UPDATE " . DB_PREFIX . "notes SET notes_conut = '0' where notes_id = '" . $row ['notes_id'] . "'";
					$this->db->query ( $slq122 );
					
					echo "Success";
					
					/*
					 * $ndata = array();
					 * foreach($contents["results"] as $content){
					 * foreach($content['alternatives'] as $b){
					 * $ndata[] = $b['transcript'];
					 * }
					 *
					 * }
					 *
					 *
					 * $ncontent = implode(" ",$ndata);
					 *
					 * $notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
					 *
					 * $notes_description = $notes_data['notes_description'];
					 * $facilities_id = $notes_data['facilities_id'];
					 * $date_added = $notes_data['date_added'];
					 *
					 * $notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
					 * $formData = array();
					 * $formData['notes_description'] = $notesContent;
					 * $formData['facilities_id'] = $facilities_id;
					 * $formData['date_added'] = $date_added;
					 *
					 *
					 * $slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
					 * $this->db->query($slq1);
					 *
					 * $this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);
					 *
					 *
					 *
					 * unlink($filename);
					 * echo "Success";
					 */
				}
			}
		}
		
		// $sqlta = "SELECT * from " . DB_PREFIX . "tags_enroll where upload_file_thumb = '' ";
		// $qtt = $this->db->query ( $sqlta );
		
		if ($qtt->num_rows > 0) {
			foreach ( $qtt->rows as $client ) {
				
				if ($client ['enroll_image'] != null && $client ['enroll_image'] != "") {
					
					$url_to_image = $client ['enroll_image'];
					
					$my_save_dir = DIR_IMAGE . 'files/';
					$filename = basename ( $url_to_image );
					// var_dump($filename);
					$extension = end ( explode ( ".", $filename ) );
					// var_dump($extension);
					$picture_filename = pathinfo ( $filename, PATHINFO_FILENAME );
					// var_dump($picture_filename);
					if ($this->config->get ( 'thumb_image_size' ) != null && $this->config->get ( 'thumb_image_size' ) != "") {
						$width = $this->config->get ( 'thumb_image_size' );
					} else {
						$width = 100;
					}
					
					if ($this->config->get ( 'thumb_image_size_height' ) != null && $this->config->get ( 'thumb_image_size_height' ) != "") {
						$height = $this->config->get ( 'thumb_image_size_height' );
					} else {
						$height = 100;
					}
					
					$path_to_image_directory = 'files/';
					$path_to_thumbs_directory = 'files/';
					
					$new_image_1 = "";
					$new_image = $picture_filename . '-' . $width;
					$new_image_1 = $new_image . "." . $extension;
					$outputFolder = DIR_IMAGE . $path_to_thumbs_directory . $new_image_1;
					
					$complete_save_loc = $my_save_dir . $filename;
					// var_dump($complete_save_loc);
					// if (!file_exists($outputFolder) || !is_file($outputFolder)) {
					
					if ($client ['upload_file_thumb'] == null && $client ['upload_file_thumb'] == "") {
						
						if (! file_exists ( $complete_save_loc ) || ! is_file ( $complete_save_loc )) {
							// file_put_contents($outputFolder, file_get_contents($client['upload_file']));
							
							// copy($client['upload_file'], $complete_save_loc);
							$ch = curl_init ( $url_to_image );
							$complete_save_loc = $my_save_dir . $filename;
							$fp = fopen ( $complete_save_loc, 'wb' );
							curl_setopt ( $ch, CURLOPT_FILE, $fp );
							curl_setopt ( $ch, CURLOPT_HEADER, 0 );
							curl_exec ( $ch );
							curl_close ( $ch );
							fclose ( $fp );
							
							// $this->Thumbnail($client['upload_file'], $complete_save_loc);
							// $this->compress($client['upload_file'], $complete_save_loc, 90);
						}
						
						/*
						 * if(preg_match('/[.](jpg)$/', $filename)) {
						 * $im = imagecreatefromjpeg(DIR_IMAGE .$path_to_image_directory.$filename);
						 *
						 * } else if (preg_match('/[.](gif)$/', $filename)) {
						 * $im = imagecreatefromgif(DIR_IMAGE .$path_to_image_directory . $filename);
						 * } else if (preg_match('/[.](png)$/', $filename)) {
						 * $im = imagecreatefrompng(DIR_IMAGE .$path_to_image_directory . $filename);
						 * }
						 *
						 * $ox = imagesx($im);
						 * $oy = imagesy($im);
						 *
						 * $nx = $final_width_of_image;
						 * $ny = floor($oy * ($final_width_of_image / $ox));
						 *
						 * $nm = imagecreatetruecolor($nx, $ny);
						 *
						 * imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
						 *
						 *
						 * echo "<hr>";
						 * echo DIR_IMAGE.$path_to_thumbs_directory.$new_image_1;
						 * echo "<hr>";
						 * imagejpeg($nm, DIR_IMAGE .$path_to_thumbs_directory . $new_image_1);
						 *
						 */
						
						// copy(DIR_IMAGE .$path_to_thumbs_directory. $filename, DIR_IMAGE .$path_to_thumbs_directory. $new_image_1);
						
						/*
						 * echo "<hr>";
						 * var_dump(DIR_IMAGE .$path_to_thumbs_directory. $filename);
						 *
						 * echo "<hr>";
						 * var_dump(DIR_IMAGE .$path_to_thumbs_directory. $new_image_1);
						 * echo "<hr>";
						 */
						
						// header('Content-type: image/jpeg');
						// $myimage = $this->resizeImage(DIR_IMAGE .$path_to_thumbs_directory. $filename, '150', '120');
						// print $myimage;
						
						// $quality = 100;
						// $this->image_handler(DIR_IMAGE .$path_to_thumbs_directory. $filename,DIR_IMAGE .$path_to_thumbs_directory. $new_image_1,'500','500',$quality,$wmsource);
						
						$image = new Image ( DIR_IMAGE . $path_to_thumbs_directory . $filename );
						$image->resize ( $width, $height, "h" );
						$image->save ( DIR_IMAGE . $path_to_thumbs_directory . $new_image_1 );
						
						$file16 = $path_to_thumbs_directory . $filename;
						
						// $this->load->model('setting/image');
						// $newfile84 = $this->model_setting_image->resize($file16, 50, 50);
						
						// var_dump($newfile84);
						// echo "<hr>";
						
						$notes_file = $new_image_1;
						$outputFolder = DIR_IMAGE . $path_to_thumbs_directory . $new_image_1;
						
						// var_dump($notes_file);
						// var_dump($outputFolder);
						$s3file = "";
						if ($this->config->get ( 'enable_storage' ) == '1') {
							
							// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							
							$sqlta2 = "SELECT * from " . DB_PREFIX . "tags where tags_id = '" . $client ['tags_id'] . "' ";
							$qtt2 = $this->db->query ( $sqlta2 );
							
							$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $qtt2->row ['facilities_id'] );
							
							// var_dump($s3file);
						}
						
						if ($this->config->get ( 'enable_storage' ) == '2') {
							/* AZURE */
							
							require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
							// uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL . $notes_file;
						}
						
						if ($this->config->get ( 'enable_storage' ) == '3') {
							$s3file = HTTP_SERVER . 'image/files/' . $notes_file;
						}
						
						$sqluf122 = "UPDATE `" . DB_PREFIX . "tags_enroll` SET upload_file_thumb = '" . $this->db->escape ( $s3file ) . "' WHERE tags_enroll_id = '" . $client ['tags_enroll_id'] . "' ";
						$this->db->query ( $sqluf122 );
						// echo "<hr>";
						if ($this->config->get ( 'enable_storage' ) != '3') {
							unlink ( $complete_save_loc );
							unlink ( $outputFolder );
						}
					}
				}
			}
		}
		
		$sqltaw = "SELECT * from " . DB_PREFIX . "tags where tags_status_in = 'Wait listed' and is_wait_list_task = '0' ";
		$qttw = $this->db->query ( $sqltaw );
		
		if ($qttw->num_rows > 0) {
			
			$this->load->model ( 'facilities/facilities' );
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'setting/timezone' );
			$this->load->model ( 'createtask/createtask' );
			
			foreach ( $qttw->rows as $clientw ) {
				
				if ($clientw ['is_wait_list_task'] == '0') {
					
					if ($clientw ['reminder_date'] != '0000-00-00 00:00:00') {
						// var_dump($clientw['reminder_date']);
						
						$reminder_date = date ( 'Y-m-d', strtotime ( $clientw ['reminder_date'] ) );
						
						$addtaskw = array ();
						$facilities_info = $this->model_facilities_facilities->getfacilities ( $clientw ['facilities_id'] );
						
						$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
						
						date_default_timezone_set ( $timezone_info ['timezone_value'] );
						
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = date ( 'Y-m-d', strtotime ( 'now' ) );
						
						if ($reminder_date >= $date_added) {
							// var_dump($reminder_date);
							// $taskTime = date('h:i A', strtotime('now'));
							
							$snooze_time71 = 3;
							$thestime61 = $clientw ['reminder_time']; // date('H:i:s');
							$taskTime = date ( "h:i A", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
							
							$current_time = date ( "H:i:s" );
							
							$time1 = date ( 'H:i:s' );
							
							$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $clientw ['reminder_date'] ) );
							$addtaskw ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $clientw ['reminder_date'] ) );
							
							$addtaskw ['recurrence'] = 'none';
							$addtaskw ['recurnce_week'] = '';
							$addtaskw ['recurnce_hrly'] = '';
							$addtaskw ['recurnce_month'] = '';
							$addtaskw ['recurnce_day'] = '';
							$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
							$addtaskw ['endtime'] = $taskTime;
							$addtaskw ['description'] = $clientw ['emp_first_name'] . ' ' . $clientw ['emp_last_name'] . ' Scheduled call back for wait listed screening';
							$addtaskw ['assignto'] = '';
							$addtaskw ['tasktype'] = '1';
							$addtaskw ['numChecklist'] = '';
							$addtaskw ['task_alert'] = '1';
							$addtaskw ['alert_type_sms'] = '';
							$addtaskw ['alert_type_notification'] = '1';
							$addtaskw ['alert_type_email'] = '';
							$addtaskw ['rules_task'] = '';
							
							$addtaskw ['locations_id'] = '';
							$addtaskw ['facilities_id'] = $clientw ['facilities_id'];
							$addtaskw ['emp_tag_id'] = $clientw ['tags_id'];
							
							// var_dump($addtaskw);
							// echo "<hr>";
							$sqlu = "UPDATE `" . DB_PREFIX . "tags` SET is_wait_list_task = '1' where tags_id = '" . $clientw ['tags_id'] . "' ";
							$this->db->query ( $sqlu );
							
							$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $clientw ['facilities_id'] );
						}
					}
				}
			}
		}
	}
	function image_handler($source_image, $destination, $tn_w = 100, $tn_h = 100, $quality = 80, $wmsource = false) {
		// The getimagesize functions provides an "imagetype" string contstant, which can be passed to the image_type_to_mime_type function for the corresponding mime type
		$info = getimagesize ( $source_image );
		$imgtype = image_type_to_mime_type ( $info [2] );
		// Then the mime type can be used to call the correct function to generate an image resource from the provided image
		switch ($imgtype) {
			case 'image/jpeg' :
				$source = imagecreatefromjpeg ( $source_image );
				break;
			case 'image/gif' :
				$source = imagecreatefromgif ( $source_image );
				break;
			case 'image/png' :
				$source = imagecreatefrompng ( $source_image );
				break;
			default :
				die ( 'Invalid image type.' );
		}
		// Now, we can determine the dimensions of the provided image, and calculate the width/height ratio
		$src_w = imagesx ( $source );
		$src_h = imagesy ( $source );
		$src_ratio = $src_w / $src_h;
		// Now we can use the power of math to determine whether the image needs to be cropped to fit the new dimensions, and if so then whether it should be cropped vertically or horizontally. We're just going to crop from the center to keep this simple.
		if ($tn_w / $tn_h > $src_ratio) {
			$new_h = $tn_w / $src_ratio;
			$new_w = $tn_w;
		} else {
			$new_w = $tn_h * $src_ratio;
			$new_h = $tn_h;
		}
		$x_mid = $new_w / 2;
		$y_mid = $new_h / 2;
		// Now actually apply the crop and resize!
		$newpic = imagecreatetruecolor ( round ( $new_w ), round ( $new_h ) );
		imagecopyresampled ( $newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h );
		$final = imagecreatetruecolor ( $tn_w, $tn_h );
		imagecopyresampled ( $final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h );
		// If a watermark source file is specified, get the information about the watermark as well. This is the same thing we did above for the source image.
		if ($wmsource) {
			$info = getimagesize ( $wmsource );
			$imgtype = image_type_to_mime_type ( $info [2] );
			switch ($imgtype) {
				case 'image/jpeg' :
					$watermark = imagecreatefromjpeg ( $wmsource );
					break;
				case 'image/gif' :
					$watermark = imagecreatefromgif ( $wmsource );
					break;
				case 'image/png' :
					$watermark = imagecreatefrompng ( $wmsource );
					break;
				default :
					die ( 'Invalid watermark type.' );
			}
			// Determine the size of the watermark, because we're going to specify the placement from the top left corner of the watermark image, so the width and height of the watermark matter.
			$wm_w = imagesx ( $watermark );
			$wm_h = imagesy ( $watermark );
			// Now, figure out the values to place the watermark in the bottom right hand corner. You could set one or both of the variables to "0" to watermark the opposite corners, or do your own math to put it somewhere else.
			$wm_x = $tn_w - $wm_w;
			$wm_y = $tn_h - $wm_h;
			// Copy the watermark onto the original image
			// The last 4 arguments just mean to copy the entire watermark
			imagecopy ( $final, $watermark, $wm_x, $wm_y, 0, 0, $tn_w, $tn_h );
		}
		// Ok, save the output as a jpeg, to the specified destination path at the desired quality.
		// You could use imagepng or imagegif here if you wanted to output those file types instead.
		if (Imagejpeg ( $final, $destination, $quality )) {
			return true;
		}
		// If something went wrong
		return false;
	}
	function resizeImage($filename, $newwidth, $newheight) {
		list ( $width, $height ) = getimagesize ( $filename );
		if ($width > $height && $newheight < $height) {
			$newheight = $height / ($width / $newwidth);
		} else if ($width < $height && $newwidth < $width) {
			$newwidth = $width / ($height / $newheight);
		} else {
			$newwidth = $width;
			$newheight = $height;
		}
		$thumb = imagecreatetruecolor ( $newwidth, $newheight );
		$source = imagecreatefromjpeg ( $filename );
		imagecopyresized ( $thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );
		return imagejpeg ( $thumb );
	}
	function Thumbnail($url, $filename, $width = 350, $height = true) {
		
		// download and create gd image
		$image = ImageCreateFromString ( file_get_contents ( $url ) );
		
		// calculate resized ratio
		// Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
		$height = $height === true ? (ImageSY ( $image ) * $width / ImageSX ( $image )) : $height;
		
		// create image
		$output = ImageCreateTrueColor ( $width, $height );
		ImageCopyResampled ( $output, $image, 0, 0, 0, 0, $width, $height, ImageSX ( $image ), ImageSY ( $image ) );
		
		// save image
		ImageJPEG ( $output, $filename, 95 );
		
		// return resized image
		return $output; // if you need to use it
	}
	function compress($source, $destination, $quality) {
		$info = getimagesize ( $source );
		
		if ($info ['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg ( $source );
		
		elseif ($info ['mime'] == 'image/gif')
			$image = imagecreatefromgif ( $source );
		
		elseif ($info ['mime'] == 'image/png')
			$image = imagecreatefrompng ( $source );
		
		imagejpeg ( $image, $destination, $quality );
		
		return $destination;
	}
	public function casedashboard() {
		$this->load->model ( 'notes/notes' );
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'notes/case' );
		$this->load->model ( 'facilities/facilities' );
		
		$data = array (
				'status' => 1,
				'discharge' => 1,
				'all_record' => 1,
				'sort' => 'emp_tag_id',
				'order' => 'ASC' 
		);
		
		$tags = $this->model_setting_tags->getTags ( $data );
		
		foreach ( $tags as $tag ) {
			$facility = $this->model_facilities_facilities->getfacilities ( $tag ['facilities_id'] );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
			
			date_default_timezone_set ( $timezone_info ['timezone_value'] );
			
			$startDate = date ( 'Y-m-d', strtotime ( '-1 day', strtotime ( 'now' ) ) );
			$endDate = date ( 'Y-m-d', strtotime ( '-1 day', strtotime ( 'now' ) ) );
			
			$start_date = date ( 'Y-m-d', strtotime ( '-1 day', strtotime ( 'now' ) ) );
			$current_date = date ( 'Y-m-d H:i:s', strtotime ( '-1 day', strtotime ( 'now' ) ) );
			
			// var_dump($tag['tags_id']);
			$data2 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			$ttotalnotes = $this->model_notes_case->getTotalnotessmain ( $data2 );
			
			$data12 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'emp_tag_id' => $tag ['tags_id'],
					'form_search' => 'all',
					'facilities_id' => $tag ['facilities_id'] 
			);
			$ttotalforms = $this->model_notes_case->getTotalnotessmain ( $data12 );
			
			$data1dd2 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'emp_tag_id' => $tag ['tags_id'],
					'task_search' => 'all',
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$ttotaltasks = $this->model_notes_case->getTotalnotessmain ( $data1dd2 );
			
			$data3 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					// 'discharge' => '1',
					'tags_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			$intakecount = $this->model_setting_tags->getTotalTags ( $data3 );
			
			$data4 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'discharge' => '2',
					'tags_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$dischargecount = $this->model_setting_tags->getTotalTags ( $data4 );
			
			$data5 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'activenote' => '44',
					'keyword' => 'incident',
					'search_acitvenote_with_keyword' => '1',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$incidentcount = $this->model_notes_case->getTotalnotessmain ( $data5 );
			
			$data11 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'activenote' => '38',
					'keyword' => 'medication',
					'search_acitvenote_with_keyword' => '1',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$pillcallcount = $this->model_notes_case->getTotalnotessmain ( $data11 );
			
			$data6 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'tasktype' => '25',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$sightandsoundcount = $this->model_notes_case->getTotalnotessmain ( $data6 );
			
			$data7 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'highlighter' => 'all',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$highlightercount = $this->model_notes_case->getTotalnotessmain ( $data7 );
			
			$data8 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'text_color' => '1',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$colorcount = $this->model_notes_case->getTotalnotessmain ( $data8 );
			
			$data9 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'review_notes' => '1',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$reviewcount = $this->model_notes_case->getTotalnotessmain ( $data9 );
			
			$data10 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'activenote' => 'all',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$activenotecount = $this->model_notes_case->getTotalnotessmain ( $data10 );
			
			$data11 = array (
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'tasktype' => '11',
					'emp_tag_id' => $tag ['tags_id'],
					'facilities_id' => $tag ['facilities_id'] 
			);
			
			$becdcheckcount = $this->model_notes_case->getTotalnotessmain ( $data11 );
			
			$casedata = array (
					'ttotaltasks' => $ttotaltasks,
					'ttotalnotes' => $ttotalnotes,
					'ttotalforms' => $ttotalforms,
					'intakecount' => $intakecount,
					'sightandsoundcount' => $sightandsoundcount,
					'incidentcount' => $incidentcount,
					'highlightercount' => $highlightercount,
					'colorcount' => $colorcount,
					'activenotecount' => $activenotecount,
					'medicationcount' => $pillcallcount,
					'bedcheckcount' => $becdcheckcount,
					'facilities_id' => $tag ['facilities_id'],
					'intake_date' => $tag ['date_added'],
					'discharge_date' => $tag ['discharge_date'],
					'roll_call' => $tag ['role_call'],
					'tags_id' => $tag ['tags_id'],
					'discharge' => $tag ['discharge'],
					'date_added' => $current_date,
					'date_updated' => $current_date,
					'start_date' => $start_date,
					'reviewcount' => $reviewcount 
			);
			
			// var_dump($casedata);
			
			$this->model_notes_case->insertTotal ( $casedata );
			
			// $sql = "UPDATE `" . DB_PREFIX . "notes` SET is_casecount = '1' where tags_id = '".$tag['tags_id']."' ";
			// $query = $this->db->query($sql);
		}
		
		echo "Success";
	}
	public function getMondaysInRange($dateFromString, $dateToString) {
		$dateFrom = new \DateTime ( $dateFromString );
		$dateTo = new \DateTime ( $dateToString );
		$dates = [ ];
		
		if ($dateFrom > $dateTo) {
			return $dates;
		}
		
		if (1 != $dateFrom->format ( 'N' )) {
			$dateFrom->modify ( 'next monday' );
		}
		
		while ( $dateFrom <= $dateTo ) {
			$dates [] = $dateFrom->format ( 'Y-m-d' );
			$dateFrom->modify ( '+1 week' );
		}
		
		return $dates;
	}
	public function futuretaskupdate() {
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'createtask/createtask' );
		
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'setting/country' );
		$this->load->model ( 'setting/zone' );
		$this->load->model ( 'setting/timezone' );
		
		$this->load->model ( 'notes/tags' );
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if (! empty ( $results )) {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$searchdate = date ( 'Y-m-d' );
				/* and DATE_FORMAT(date_added, '%Y-%M-%D') != DATE_FORMAT(end_recurrence_date, '%Y-%M-%D') */
				$sqlt = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '" . $tresult ['facilities_id'] . "' and (`end_recurrence_date` >  '" . $searchdate . " 23:59:59') and is_create_task = '0' ";
				$qt = $this->db->query ( $sqlt );
				
				// var_dump($qt->num_rows);
				// echo "<hr>";
				if ($qt->num_rows > 0) {
					foreach ( $qt->rows as $tasks ) {
						
						$start_date = $tasks ['date_added'];
						$start_date_time = date ( "H:i:s", strtotime ( $tasks ['date_added'] ) );
						$end_date = $tasks ['end_recurrence_date'];
						
						$s_date = date ( "Y-m-d", strtotime ( $start_date ) );
						$e_date = date ( "Y-m-d", strtotime ( $end_date ) );
						
						if ($tasks ['recurrence'] == "hourly") {
							if ($tasks ['recurnce_hrly_recurnce'] == "Daily") {
								if (! empty ( $tasks ['weekly_interval'] )) {
									$intervalday = explode ( ',', $tasks ['weekly_interval'] );
								}
							}
						}
						
						$ss_date = date ( "Y-m-d", strtotime ( "+1 day", strtotime ( $s_date ) ) );
						$iv = 0;
						sort ( $intervalday );
						
						while ( strtotime ( $ss_date ) <= strtotime ( $e_date ) ) {
							
							if (! empty ( $intervalday )) {
								foreach ( $intervalday as $day111 ) {
									
									$day_of_week = date ( 'w', strtotime ( $day111 ) );
									// var_dump($day_of_week);
									
									$day = date ( 'w', strtotime ( $ss_date ) );
									// var_dump($this->getMondaysInRange($s_date, $e_date));
									
									// $ss_date = date("Y-m-d", strtotime('next '.$day111));
									
									var_dump ( $ss_date );
									
									$ss_date = date ( "Y-m-d", strtotime ( $day111 ) );
									
									/*
									 * if($cur_mon == "" && $cur_mon == null){
									 * $cur_mon1 = $day111;
									 * }else{
									 * $cur_mon1 = $cur_mon;
									 * }
									 *
									 * var_dump($cur_mon1);
									 *
									 * $ss_date = date('Y-m-d', $cur_mon1);
									 */
									// $ss_date = $this->getMondaysInRange($s_date, $e_date);
									
									$s_date1 = $ss_date . ' ' . $start_date_time;
									
									$cur_mon = date ( 'Y-m-d', strtotime ( "next " . $day111 . "", $cur_mon1 ) );
									
									$fdate = $ss_date;
									$data = array (
											'date_added' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
											'end_recurrence_date' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
											'facilityId' => $tasks ['facilityId'],
											'task_date' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
											'task_time' => $tasks ['task_time'],
											'tasktype' => $tasks ['tasktype'],
											'description' => $tasks ['description'],
											'assign_to' => $tasks ['assign_to'],
											'recurrence' => $tasks ['recurrence'],
											'recurnce_hrly' => $tasks ['recurnce_hrly'],
											'recurnce_week' => $tasks ['recurnce_week'],
											'recurnce_month' => $tasks ['recurnce_month'],
											'recurnce_day' => $tasks ['recurnce_day'],
											'taskadded' => $tasks ['taskadded'],
											'endtime' => $tasks ['endtime'],
											'task_alert' => $tasks ['task_alert'],
											'alert_type_none' => $tasks ['alert_type_none'],
											'alert_type_sms' => $tasks ['alert_type_sms'],
											'alert_type_notification' => $tasks ['alert_type_notification'],
											'alert_type_email' => $tasks ['alert_type_email'],
											'checklist' => $tasks ['checklist'],
											'snooze_time' => $tasks ['snooze_time'],
											'snooze_dismiss' => $tasks ['snooze_dismiss'],
											'rules_task' => $tasks ['rules_task'],
											'task_form_id' => $tasks ['task_form_id'],
											'tags_id' => $tasks ['tags_id'],
											'pickup_locations_address' => $tasks ['pickup_locations_address'],
											'pickup_locations_time' => $tasks ['pickup_locations_time'],
											'pickup_locations_latitude' => $tasks ['pickup_locations_latitude'],
											'pickup_locations_longitude' => $tasks ['pickup_locations_longitude'],
											'dropoff_locations_address' => $tasks ['dropoff_locations_address'],
											'dropoff_locations_time' => $tasks ['dropoff_locations_time'],
											'dropoff_locations_latitude' => $tasks ['dropoff_locations_latitude'],
											'dropoff_locations_longitude' => $tasks ['dropoff_locations_longitude'],
											'transport_tags' => $tasks ['transport_tags'],
											'locations_id' => $tasks ['locations_id'],
											'task_complettion' => $tasks ['task_complettion'],
											'customs_forms_id' => $tasks ['customs_forms_id'],
											'emp_tag_id' => $tasks ['emp_tag_id'],
											'medication_tags' => $tasks ['medication_tags'],
											'completion_alert' => $tasks ['completion_alert'],
											'completion_alert_type_sms' => $tasks ['completion_alert_type_sms'],
											'completion_alert_type_email' => $tasks ['completion_alert_type_email'],
											'user_roles' => $tasks ['user_roles'],
											'userids' => $tasks ['userids'],
											'recurnce_hrly_perpetual' => $tasks ['recurnce_hrly_perpetual'],
											'due_date_time' => $tasks ['due_date_time'],
											'task_status' => $tasks ['task_status'],
											'task_completed' => $tasks ['task_completed'],
											'recurnce_hrly_recurnce' => $tasks ['recurnce_hrly_recurnce'],
											'completed_times' => $tasks ['completed_times'],
											'completed_alert' => $tasks ['completed_alert'],
											'completed_late_alert' => $tasks ['completed_late_alert'],
											'incomplete_alert' => $tasks ['incomplete_alert'],
											'deleted_alert' => $tasks ['deleted_alert'],
											'end_perpetual_task' => $tasks ['end_perpetual_task'],
											'is_transport' => $tasks ['is_transport'],
											'parent_id' => $tasks ['parent_id'],
											'is_send_reminder' => $tasks ['is_send_reminder'],
											'attachement_form' => $tasks ['attachement_form'],
											'tasktype_form_id' => $tasks ['tasktype_form_id'],
											'tagstatus_id' => $tasks ['tagstatus_id'],
											'task_group_by' => $tasks ['task_group_by'],
											'end_task' => $tasks ['end_task'],
											'formrules_id' => $tasks ['formrules_id'],
											'task_random_id' => $tasks ['task_random_id'],
											'form_due_date' => $tasks ['form_due_date'],
											'form_due_date_after' => $tasks ['form_due_date_after'],
											'recurnce_m' => $tasks ['recurnce_m'],
											'enable_requires_approval' => $tasks ['enable_requires_approval'],
											'approval_taskid' => $tasks ['approval_taskid'],
											'iswaypoint' => $tasks ['iswaypoint'],
											'original_task_time' => $tasks ['original_task_time'],
											'device_id' => $tasks ['device_id'],
											'is_approval_required_forms_id' => $tasks ['is_approval_required_forms_id'],
											'bed_check_location_ids' => $tasks ['bed_check_location_ids'],
											'complete_status' => $tasks ['complete_status'],
											'id' => $tasks ['id'] 
									);
									
									var_dump ( $data );
									echo "<hr>";
									
									/*
									 * $sqltc = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '".$tresult['facilities_id']."' and (`date_added` BETWEEN '".$ss_date." 00:00:00' AND '".$ss_date." 23:59:59') and is_create_task = '".$tasks['id']."' ";
									 * $qtc = $this->db->query($sqltc);
									 *
									 * if($qtc->num_rows == 0){
									 * $alltasks = $this->model_createtask_createtask->addcreatetask2($data, $tresult['facilities_id']);
									 * }
									 */
								}
							} else {
								$ss_date = date ( "Y-m-d", strtotime ( "+1 day", strtotime ( $ss_date ) ) );
								
								$s_date1 = $ss_date . ' ' . $start_date_time;
								$data = array (
										'date_added' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
										'end_recurrence_date' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
										'facilityId' => $tasks ['facilityId'],
										'task_date' => date ( "Y-m-d H:i:s", strtotime ( $s_date1 ) ),
										'task_time' => $tasks ['task_time'],
										'tasktype' => $tasks ['tasktype'],
										'description' => $tasks ['description'],
										'assign_to' => $tasks ['assign_to'],
										'recurrence' => $tasks ['recurrence'],
										'recurnce_hrly' => $tasks ['recurnce_hrly'],
										'recurnce_week' => $tasks ['recurnce_week'],
										'recurnce_month' => $tasks ['recurnce_month'],
										'recurnce_day' => $tasks ['recurnce_day'],
										'taskadded' => $tasks ['taskadded'],
										'endtime' => $tasks ['endtime'],
										'task_alert' => $tasks ['task_alert'],
										'alert_type_none' => $tasks ['alert_type_none'],
										'alert_type_sms' => $tasks ['alert_type_sms'],
										'alert_type_notification' => $tasks ['alert_type_notification'],
										'alert_type_email' => $tasks ['alert_type_email'],
										'checklist' => $tasks ['checklist'],
										'snooze_time' => $tasks ['snooze_time'],
										'snooze_dismiss' => $tasks ['snooze_dismiss'],
										'rules_task' => $tasks ['rules_task'],
										'task_form_id' => $tasks ['task_form_id'],
										'tags_id' => $tasks ['tags_id'],
										'pickup_locations_address' => $tasks ['pickup_locations_address'],
										'pickup_locations_time' => $tasks ['pickup_locations_time'],
										'pickup_locations_latitude' => $tasks ['pickup_locations_latitude'],
										'pickup_locations_longitude' => $tasks ['pickup_locations_longitude'],
										'dropoff_locations_address' => $tasks ['dropoff_locations_address'],
										'dropoff_locations_time' => $tasks ['dropoff_locations_time'],
										'dropoff_locations_latitude' => $tasks ['dropoff_locations_latitude'],
										'dropoff_locations_longitude' => $tasks ['dropoff_locations_longitude'],
										'transport_tags' => $tasks ['transport_tags'],
										'locations_id' => $tasks ['locations_id'],
										'task_complettion' => $tasks ['task_complettion'],
										'customs_forms_id' => $tasks ['customs_forms_id'],
										'emp_tag_id' => $tasks ['emp_tag_id'],
										'medication_tags' => $tasks ['medication_tags'],
										'completion_alert' => $tasks ['completion_alert'],
										'completion_alert_type_sms' => $tasks ['completion_alert_type_sms'],
										'completion_alert_type_email' => $tasks ['completion_alert_type_email'],
										'user_roles' => $tasks ['user_roles'],
										'userids' => $tasks ['userids'],
										'recurnce_hrly_perpetual' => $tasks ['recurnce_hrly_perpetual'],
										'due_date_time' => $tasks ['due_date_time'],
										'task_status' => $tasks ['task_status'],
										'task_completed' => $tasks ['task_completed'],
										'recurnce_hrly_recurnce' => $tasks ['recurnce_hrly_recurnce'],
										'completed_times' => $tasks ['completed_times'],
										'completed_alert' => $tasks ['completed_alert'],
										'completed_late_alert' => $tasks ['completed_late_alert'],
										'incomplete_alert' => $tasks ['incomplete_alert'],
										'deleted_alert' => $tasks ['deleted_alert'],
										'end_perpetual_task' => $tasks ['end_perpetual_task'],
										'is_transport' => $tasks ['is_transport'],
										'parent_id' => $tasks ['parent_id'],
										'is_send_reminder' => $tasks ['is_send_reminder'],
										'attachement_form' => $tasks ['attachement_form'],
										'tasktype_form_id' => $tasks ['tasktype_form_id'],
										'tagstatus_id' => $tasks ['tagstatus_id'],
										'task_group_by' => $tasks ['task_group_by'],
										'end_task' => $tasks ['end_task'],
										'formrules_id' => $tasks ['formrules_id'],
										'task_random_id' => $tasks ['task_random_id'],
										'form_due_date' => $tasks ['form_due_date'],
										'form_due_date_after' => $tasks ['form_due_date_after'],
										'recurnce_m' => $tasks ['recurnce_m'],
										'enable_requires_approval' => $tasks ['enable_requires_approval'],
										'approval_taskid' => $tasks ['approval_taskid'],
										'iswaypoint' => $tasks ['iswaypoint'],
										'original_task_time' => $tasks ['original_task_time'],
										'device_id' => $tasks ['device_id'],
										'is_approval_required_forms_id' => $tasks ['is_approval_required_forms_id'],
										'bed_check_location_ids' => $tasks ['bed_check_location_ids'],
										'complete_status' => $tasks ['complete_status'],
										'id' => $tasks ['id'] 
								);
								
								// var_dump($data);
								// echo "<hr>";
								
								$sqltc = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '" . $tresult ['facilities_id'] . "' and (`date_added` BETWEEN  '" . $ss_date . " 00:00:00' AND  '" . $ss_date . " 23:59:59') and is_create_task = '" . $tasks ['id'] . "' ";
								$qtc = $this->db->query ( $sqltc );
								
								if ($qtc->num_rows == 0) {
									$alltasks = $this->model_createtask_createtask->addcreatetask2 ( $data, $tresult ['facilities_id'] );
								}
							}
							
							$iv ++;
						}
						
						$slq1u = "UPDATE " . DB_PREFIX . "createtask SET end_recurrence_date = '" . $tasks ['task_date'] . "' where id = '" . $tasks ['id'] . "'";
						// $this->db->query($slq1u);
					}
				}
			}
		}
	}
	public function dashbordactivity() {
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'notes/tags' );
		$this->load->model ( 'syndb/syndb' );
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if (! empty ( $results )) {
			foreach ( $results as $tresult ) {
				
				$tnotes_total = array ();
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				$searchdate = date ( 'Y-m-d' );
				
				$startDate = date ( 'Y-m-d', strtotime ( "-1 day" ) );
				$endDate = date ( 'Y-m-d' );
				
				$date_added = date ( 'Y-m-d H:i:s', strtotime ( "-1 day" ) );
				
				$sqltnt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and status = '1' ";
				$query = $this->db->query ( $sqltnt );
				$total_notes = $query->row ['total'];
				
				$sqltnta = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$querya = $this->db->query ( $sqltnta );
				$total_activenote = $querya->row ['total'];
				
				$sqltnth = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and highlighter_id > '0' ";
				$queryh = $this->db->query ( $sqltnth );
				$total_highlighter = $queryh->row ['total'];
				
				$sqltntu = "SELECT DISTINCT COUNT(DISTINCT user_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and user_id != '' ";
				$queryu = $this->db->query ( $sqltntu );
				$total_active_user = $queryu->row ['total'];
				
				$sqltntt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and is_tag > 0 and form_type = '2' ";
				$queryt = $this->db->query ( $sqltntt );
				$total_intake_tags = $queryt->row ['total'];
				
				$sqltntf = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$queryf = $this->db->query ( $sqltntf );
				$total_forms = $queryf->row ['total'];
				
				$sqltntfi = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' and custom_form_type = '" . CUSTOME_INTAKEID . "' ";
				$queryfi = $this->db->query ( $sqltntfi );
				$total_screening = $queryfi->row ['total'];
				
				$sqltntai = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and keyword_id = '44' and notes_id > '0' ";
				$queryai = $this->db->query ( $sqltntai );
				$total_incident = $queryai->row ['total'];
				
				$sqltntait = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and is_monitor_time = '1' and notes_id > '0' ";
				$queryait = $this->db->query ( $sqltntait );
				$total_timed_activenote = $queryait->row ['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and text_color != '' ";
				$queryc = $this->db->query ( $sqltntc );
				$total_colour = $queryc->row ['total'];
				
				$sqltntm = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_media where facilities_id = '" . $tresult ['facilities_id'] . "' and `media_date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$querytm = $this->db->query ( $sqltntm );
				$total_media = $querytm->row ['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and task_id > '0' ";
				$queryc = $this->db->query ( $sqltntc );
				$total_task = $queryc->row ['total'];
				
				$tnotes_total = array (
						'total_notes' => $total_notes,
						'total_activenote' => $total_activenote,
						'total_highlighter' => $total_highlighter,
						'total_active_user' => $total_active_user,
						'total_intake_tags' => $total_intake_tags,
						'total_forms' => $total_forms,
						'total_screening' => $total_screening,
						'total_incident' => $total_incident,
						'total_timed_activenote' => $total_timed_activenote,
						'total_colour' => $total_colour,
						'total_media' => $total_media,
						'total_task' => $total_task,
						'facilities_id' => $tresult ['facilities_id'],
						'date_added' => $date_added,
						'date_updated' => $date_added,
						'status' => 1 
				);
				
				// var_dump($tnotes_total);
				// echo "<hr>";
				
				$sqla = "Select dashboard_activity_id from `" . DB_PREFIX . "dashboard_activity` where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' ";
				$querya = $this->db->query ( $sqla );
				
				$activity_info = $querya->row;
				
				if ($activity_info ['dashboard_activity_id'] != null && $activity_info ['dashboard_activity_id'] != "") {
					$this->model_syndb_syndb->updateTotal ( $tnotes_total, $activity_info ['dashboard_activity_id'] );
					
					$dashboard_activity_id = $activity_info ['dashboard_activity_id'];
				} else {
					$dashboard_activity_id = $this->model_syndb_syndb->insertTotal ( $tnotes_total );
				}
				
				$sqltn = "SELECT notes_id,notes_description,date_added from " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and sync_dashboard = '0' ";
				$qtno = $this->db->query ( $sqltn );
				
				// var_dump($qtno->num_rows);
				// echo "<hr>";
				if ($qtno->num_rows > 0) {
					foreach ( $qtno->rows as $note ) {
						// var_dump($note);
						// echo "<hr>";
						
						$sqlac = "Select dashboard_activity_keywords_id from `" . DB_PREFIX . "dashboard_activity_keywords` where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $startDate . " 23:59:59' and notes_id = '" . $note ['notes_id'] . "' ";
						$queryac = $this->db->query ( $sqlac );
						
						$dactivity_info = $queryac->row;
						
						$sqltnnt = "SELECT task_content from " . DB_PREFIX . "notes_by_task where facilities_id = '" . $tresult ['facilities_id'] . "' and notes_id = '" . $note ['notes_id'] . "' ";
						$qtnont = $this->db->query ( $sqltnnt );
						$taskcontent = "";
						if ($qtnont->num_rows > 0) {
							foreach ( $qtnont->rows as $notetask ) {
								$taskcontent .= $notetask ['task_content'] . ' ';
							}
						}
						
						$sqltnf = "SELECT form_description from " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and notes_id = '" . $note ['notes_id'] . "' ";
						$qtnof = $this->db->query ( $sqltnf );
						
						// var_dump($qtno->num_rows);
						// echo "<hr>";
						$form_description = "";
						if ($qtnof->num_rows > 0) {
							foreach ( $qtnof->rows as $noteform ) {
								$form_description .= $noteform ['form_description'] . ' ';
							}
						}
						
						if ($dactivity_info ['dashboard_activity_keywords_id'] != null && $dactivity_info ['dashboard_activity_keywords_id'] != "") {
							
							$usqla = "UPDATE `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							notes_description = '" . $this->db->escape ( $note ['notes_description'] ) . "'
							,task_content = '" . $this->db->escape ( $taskcontent ) . "'
							,form_description = '" . $this->db->escape ( $form_description ) . "'							
							where dashboard_activity_keywords_id = '" . $this->db->escape ( $dactivity_info ['dashboard_activity_keywords_id'] ) . "' ";
							$this->db->query ( $usqla );
						} else {
							
							$sqla = "INSERT INTO `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							dashboard_activity_id = '" . $this->db->escape ( $dashboard_activity_id ) . "'
							,notes_description = '" . $this->db->escape ( $note ['notes_description'] ) . "'
							,task_content = '" . $this->db->escape ( $taskcontent ) . "'
							,form_description = '" . $this->db->escape ( $form_description ) . "'
							,date_added = '" . $this->db->escape ( $note ['date_added'] ) . "'
							,date_updated = '" . $this->db->escape ( $note ['date_added'] ) . "'
							,facilities_id = '" . $tresult ['facilities_id'] . "'
							,notes_id = '" . $note ['notes_id'] . "'							
							";
							// echo "<hr>";
							$query = $this->db->query ( $sqla );
						}
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sql );
						
						$sqlk = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlk );
						
						$sqlkf = "UPDATE `" . DB_PREFIX . "forms` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlkf );
						
						$sqlkfm = "UPDATE `" . DB_PREFIX . "notes_media` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlkfm );
					}
				}
				
				/* current day */
				
				$cstartDate = date ( 'Y-m-d', strtotime ( 'now' ) );
				$cendDate = date ( 'Y-m-d' );
				
				$cdate_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$sqltnt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and status = '1' ";
				$query = $this->db->query ( $sqltnt );
				$total_notes = $query->row ['total'];
				
				$sqltnta = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$querya = $this->db->query ( $sqltnta );
				$total_activenote = $querya->row ['total'];
				
				$sqltnth = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and highlighter_id > '0' ";
				$queryh = $this->db->query ( $sqltnth );
				$total_highlighter = $queryh->row ['total'];
				
				$sqltntu = "SELECT DISTINCT COUNT(DISTINCT user_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and user_id != '' ";
				$queryu = $this->db->query ( $sqltntu );
				$total_active_user = $queryu->row ['total'];
				
				$sqltntt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and is_tag > 0 and form_type = '2' ";
				$queryt = $this->db->query ( $sqltntt );
				$total_intake_tags = $queryt->row ['total'];
				
				$sqltntf = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$queryf = $this->db->query ( $sqltntf );
				$total_forms = $queryf->row ['total'];
				
				$sqltntfi = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' and custom_form_type = '" . CUSTOME_INTAKEID . "' ";
				$queryfi = $this->db->query ( $sqltntfi );
				$total_screening = $queryfi->row ['total'];
				
				$sqltntai = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and keyword_id = '44' and notes_id > '0' ";
				$queryai = $this->db->query ( $sqltntai );
				$total_incident = $queryai->row ['total'];
				
				$sqltntait = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and is_monitor_time = '1' and notes_id > '0' ";
				$queryait = $this->db->query ( $sqltntait );
				$total_timed_activenote = $queryait->row ['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and text_color != '' ";
				$queryc = $this->db->query ( $sqltntc );
				$total_colour = $queryc->row ['total'];
				
				$sqltntm = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_media where facilities_id = '" . $tresult ['facilities_id'] . "' and `media_date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";
				$querytm = $this->db->query ( $sqltntm );
				$total_media = $querytm->row ['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' and status = '1' and task_id > '0' ";
				$queryc = $this->db->query ( $sqltntc );
				$total_task = $queryc->row ['total'];
				
				$tnotes_total = array (
						'total_notes' => $total_notes,
						'total_activenote' => $total_activenote,
						'total_highlighter' => $total_highlighter,
						'total_active_user' => $total_active_user,
						'total_intake_tags' => $total_intake_tags,
						'total_forms' => $total_forms,
						'total_screening' => $total_screening,
						'total_incident' => $total_incident,
						'total_timed_activenote' => $total_timed_activenote,
						'total_colour' => $total_colour,
						'total_media' => $total_media,
						'total_task' => $total_task,
						'facilities_id' => $tresult ['facilities_id'],
						'date_added' => $cdate_added,
						'date_updated' => $cdate_added,
						'status' => 1 
				);
				
				// var_dump($tnotes_total);
				// echo "<hr>";
				
				$sqla = "Select dashboard_activity_id from `" . DB_PREFIX . "dashboard_activity` where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' ";
				$querya = $this->db->query ( $sqla );
				
				$activity_info = $querya->row;
				
				if ($activity_info ['dashboard_activity_id'] != null && $activity_info ['dashboard_activity_id'] != "") {
					$this->model_syndb_syndb->updateTotal ( $tnotes_total, $activity_info ['dashboard_activity_id'] );
					
					$dashboard_activity_id = $activity_info ['dashboard_activity_id'];
				} else {
					$dashboard_activity_id = $this->model_syndb_syndb->insertTotal ( $tnotes_total );
				}
				
				$sqltn = "SELECT notes_id,notes_description,date_added from " . DB_PREFIX . "notes where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and sync_dashboard = '0' ";
				$qtno = $this->db->query ( $sqltn );
				
				// var_dump($qtno->num_rows);
				// echo "<hr>";
				if ($qtno->num_rows > 0) {
					foreach ( $qtno->rows as $note ) {
						// var_dump($note);
						// echo "<hr>";
						
						$sqlac = "Select dashboard_activity_keywords_id from `" . DB_PREFIX . "dashboard_activity_keywords` where facilities_id = '" . $tresult ['facilities_id'] . "' and `date_added` BETWEEN  '" . $cstartDate . " 00:00:00 ' AND  '" . $cstartDate . " 23:59:59' and notes_id = '" . $note ['notes_id'] . "' ";
						$queryac = $this->db->query ( $sqlac );
						
						$dactivity_info = $queryac->row;
						
						$sqltnnt = "SELECT task_content from " . DB_PREFIX . "notes_by_task where facilities_id = '" . $tresult ['facilities_id'] . "' and notes_id = '" . $note ['notes_id'] . "' ";
						$qtnont = $this->db->query ( $sqltnnt );
						$taskcontent = "";
						if ($qtnont->num_rows > 0) {
							foreach ( $qtnont->rows as $notetask ) {
								$taskcontent .= $notetask ['task_content'] . ' ';
							}
						}
						
						$sqltnf = "SELECT form_description from " . DB_PREFIX . "forms where facilities_id = '" . $tresult ['facilities_id'] . "' and notes_id = '" . $note ['notes_id'] . "' ";
						$qtnof = $this->db->query ( $sqltnf );
						
						// var_dump($qtno->num_rows);
						// echo "<hr>";
						$form_description = "";
						if ($qtnof->num_rows > 0) {
							foreach ( $qtnof->rows as $noteform ) {
								$form_description .= $noteform ['form_description'] . ' ';
							}
						}
						
						if ($dactivity_info ['dashboard_activity_keywords_id'] != null && $dactivity_info ['dashboard_activity_keywords_id'] != "") {
							
							$usqla = "UPDATE `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							notes_description = '" . $this->db->escape ( $note ['notes_description'] ) . "'
							,task_content = '" . $this->db->escape ( $taskcontent ) . "'
							,form_description = '" . $this->db->escape ( $form_description ) . "'							
							where dashboard_activity_keywords_id = '" . $this->db->escape ( $dactivity_info ['dashboard_activity_keywords_id'] ) . "' ";
							$this->db->query ( $usqla );
						} else {
							
							$sqla = "INSERT INTO `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							dashboard_activity_id = '" . $this->db->escape ( $dashboard_activity_id ) . "'
							,notes_description = '" . $this->db->escape ( $note ['notes_description'] ) . "'
							,task_content = '" . $this->db->escape ( $taskcontent ) . "'
							,form_description = '" . $this->db->escape ( $form_description ) . "'
							,date_added = '" . $this->db->escape ( $note ['date_added'] ) . "'
							,date_updated = '" . $this->db->escape ( $note ['date_added'] ) . "'
							,facilities_id = '" . $tresult ['facilities_id'] . "'
							,notes_id = '" . $note ['notes_id'] . "'							
							";
							// echo "<hr>";
							$query = $this->db->query ( $sqla );
						}
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sql );
						
						$sqlk = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlk );
						
						$sqlkf = "UPDATE `" . DB_PREFIX . "forms` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlkf );
						
						$sqlkfm = "UPDATE `" . DB_PREFIX . "notes_media` SET sync_dashboard = '1' where notes_id = '" . $note ['notes_id'] . "' ";
						$query = $this->db->query ( $sqlkfm );
					}
				}
			}
		}
		echo "1";
	}
	
	/*
	 * google API
	 * public function speechToText(){
	 *
	 * if($this->config->get('config_transcription') == '1'){
	 *
	 * $this->load->model('notes/notes');
	 * $query = $this->db->query("SELECT * FROM dg_notes_media where audio_attach_type = '1' ");
	 * $numrow = $query->num_rows;
	 *
	 * if($numrow > 0){
	 * $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
	 * foreach($query->rows as $row){
	 *
	 * $urrl = $row['audio_attach_url'];
	 *
	 * //$upload = file_get_contents($filename);
	 * $upload = file_get_contents($urrl);
	 * $upload = base64_encode($upload);
	 * $data = array(
	 * "config" => array(
	 * "encoding" => "FLAC",
	 * "sampleRate" => 16000,
	 * "languageCode" => "en-US",
	 * ),
	 * "audio" => array(
	 * "content" => $upload,
	 * )
	 * );
	 *
	 * $jsonData = json_encode($data);
	 * //$headers = array( "Content-Type: audio/flac", "Transfer-Encoding: chunked");
	 *
	 * $headers = array( "Content-Type: application/json");
	 * $ch = curl_init();
	 *
	 * curl_setopt($ch, CURLOPT_URL, $stturl);
	 * curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	 * curl_setopt($ch, CURLOPT_POST, TRUE);
	 * curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	 * curl_setopt($ch, CURLOPT_POST, true);
	 * curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	 * curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 *
	 * $results = curl_exec($ch);
	 *
	 * //var_dump($results);
	 *
	 * $contents = json_decode($results,true);
	 *
	 * $ndata = array();
	 *
	 *
	 * foreach($contents["results"] as $content){
	 * foreach($content['alternatives'] as $b){
	 * $ndata[] = $b['transcript'];
	 * }
	 *
	 * }
	 *
	 *
	 * $ncontent = implode(" ",$ndata);
	 *
	 * $notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
	 *
	 * $notes_description = $notes_data['notes_description'];
	 * $facilities_id = $notes_data['facilities_id'];
	 * $date_added = $notes_data['date_added'];
	 *
	 * $notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
	 * $formData = array();
	 * $formData['notes_description'] = $notesContent;
	 * $formData['facilities_id'] = $facilities_id;
	 * $formData['date_added'] = $date_added;
	 *
	 *
	 * $slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
	 * $this->db->query($slq1);
	 *
	 * $this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);
	 *
	 *
	 *
	 * unlink($filename);
	 * echo "Success";
	 *
	 * }
	 *
	 * }
	 *
	 * }
	 * }
	 */
	public function medicationTask() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags` where status = '1' ";
		$query = $this->db->query ( $sql );
		
		define ( 'HOUR', '1' );
		define ( 'MINUTES', '30' );
		define ( 'DAY', '1' );
		
		date_default_timezone_set ( 'US/Eastern' );
		
		$startDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		$endDate = date ( 'Y-m-d', strtotime ( 'now' ) );
		
		$current_stime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		// var_dump($current_stime);
		
		// $current_etime = date('H:i:s', strtotime("+".HOUR." hour"));
		$current_etime = date ( 'H:i:s', strtotime ( "+" . MINUTES . " minutes" ) );
		
		$this->load->model ( 'createtask/createtask' );
		
		if ($query->num_rows > 0) {
			
			foreach ( $query->rows as $row ) {
				// var_dump($row);
				// echo "<hr>";
				
				// $sql2 = "SELECT * FROM `" . DB_PREFIX . "medication` where create_task = '0' and status = '1' and tags_id = '".$row['tags_id']."' and `start_date` <= '".$startDate."' AND end_date >= '".$endDate."' ";
				
				// $sql2 = "select n.*,m.* from `" . DB_PREFIX . "medication` n left JOIN `" . DB_PREFIX . "medication_time` m on m.medication_id=n.medication_id where n.create_task = '0' and n.status = '1' and n.tags_id = '".$row['tags_id']."' and n.start_date <= '".$startDate."' AND n.end_date >= '".$endDate."' order by m.start_time ";
				
				$sql2 = "SELECT n.*, m.start_time as m_start_time, GROUP_CONCAT(m.medication_id SEPARATOR ',') as m_medication_id, GROUP_CONCAT(m.medication_time_id SEPARATOR ',') as m_medication_time_id FROM " . DB_PREFIX . "medication n JOIN " . DB_PREFIX . "medication_time m ON (m.medication_id=n.medication_id) where m.create_task = '0' and n.status = '1' and n.tags_id = '" . $row ['tags_id'] . "' and n.start_date <=  '" . $startDate . "' AND n.end_date >=  '" . $endDate . "'  and (m.`start_time` BETWEEN  '" . $current_stime . "' AND  '" . $current_etime . "') GROUP BY m.start_time order by m.start_time";
				
				// echo "<hr>";
				
				$query2 = $this->db->query ( $sql2 );
				// var_dump($query2->num_rows);
				
				if ($query2->num_rows > 0) {
					$medicineArray = array ();
					foreach ( $query2->rows as $row2 ) {
						// var_dump($row2);
						// echo "<hr>";
						
						$addtaskw = array ();
						
						if ($row2 ['m_start_time'] != null && $row2 ['m_start_time'] != "") {
							$snooze_time71 = 0;
							$thestime61 = $row2 ['m_start_time'];
						} else {
							$snooze_time71 = 0;
							$thestime61 = date ( 'H:i:s' );
						}
						
						$taskTime = date ( "H:i:s", strtotime ( "+" . $snooze_time71 . " minutes", strtotime ( $thestime61 ) ) );
						
						// $start_date = date('m-d-Y',strtotime($row2['start_date']));
						$start_date = date ( 'm-d-Y', strtotime ( 'now' ) );
						
						$date = str_replace ( '-', '/', $start_date );
						$res = explode ( "/", $date );
						$taskDate = $res [1] . "-" . $res [0] . "-" . $res [2];
						
						$end_date = date ( 'm-d-Y', strtotime ( $row2 ['end_date'] ) );
						$date2 = str_replace ( '-', '/', $end_date );
						$res2 = explode ( "/", $date2 );
						$end_recurrence_date = $res2 [1] . "-" . $res2 [0] . "-" . $res2 [2];
						
						$addtaskw ['taskDate'] = date ( 'm-d-Y', strtotime ( $taskDate ) );
						$addtaskw ['end_recurrence_date'] = date ( 'm-d-Y', strtotime ( $taskDate ) );
						$addtaskw ['recurrence'] = 'none';
						$addtaskw ['recurnce_week'] = '';
						$addtaskw ['recurnce_hrly'] = '';
						$addtaskw ['recurnce_month'] = '';
						$addtaskw ['recurnce_day'] = '';
						$addtaskw ['taskTime'] = $taskTime; // date('H:i:s');
						$addtaskw ['endtime'] = $stime8;
						$addtaskw ['description'] = 'Medication for ' . $row ['emp_first_name'] . ' ' . $row ['emp_last_name'];
						$addtaskw ['assignto'] = '';
						$addtaskw ['tasktype'] = '2';
						$addtaskw ['numChecklist'] = '';
						$addtaskw ['task_alert'] = '1';
						$addtaskw ['alert_type_sms'] = '';
						$addtaskw ['alert_type_notification'] = '1';
						$addtaskw ['alert_type_email'] = '';
						$addtaskw ['rules_task'] = '';
						
						$addtaskw ['locations_id'] = $row ['locations_id'];
						$addtaskw ['facilities_id'] = $row ['facilities_id'];
						$addtaskw ['tags_id'] = $row ['tags_id'];
						
						// var_dump($addtaskw);
						// echo "<hr>";
						$task_id = $this->model_createtask_createtask->addcreatetask ( $addtaskw, $row ['facilities_id'] );
						// var_dump($row2['m_medication_id']);
						$medicationids = explode ( ",", $row2 ['m_medication_id'] );
						// var_dump($medicationtimeids);
						
						foreach ( $medicationids as $medicationid ) {
							$sql2m = "SELECT * FROM `" . DB_PREFIX . "medication` where medication_id = '" . $medicationid . "' ";
							$querym = $this->db->query ( $sql2m );
							// var_dump($querym->row);
							// echo "<hr>";
							
							$sql = "INSERT INTO `" . DB_PREFIX . "createtask_by_medication` SET id = '" . $task_id . "', facilities_id = '" . $row ['facilities_id'] . "', locations_id = '" . $row ['locations_id'] . "', tags_id = '" . $row ['tags_id'] . "', medication_id = '" . $querym->row ['medication_id'] . "', drug_name = '" . $querym->row ['drug_name'] . "', dose = '" . $querym->row ['dose'] . "', drug_type = '" . $querym->row ['drug_type'] . "', quantity = '" . $querym->row ['quantity'] . "', frequency = '" . $querym->row ['frequency'] . "', start_time = '" . $taskTime . "', instructions = '" . $this->db->escape ( $querym->row ['instructions'] ) . "', count = '" . $querym->row ['count'] . "', complete_status = '0' ";
							
							$this->db->query ( $sql );
							// echo "<hr>";
							$sqlu = "UPDATE `" . DB_PREFIX . "medication_time` SET create_task = '1' where medication_id = '" . $medicationid . "' ";
							$this->db->query ( $sqlu );
						}
					}
				}
			}
		}
		
		echo "Success";
	}
	
	/*
	 * public function medicationTask(){
	 * $sql = "SELECT * FROM `" . DB_PREFIX . "tags` where status = '1' ";
	 * $query = $this->db->query($sql);
	 *
	 * define('HOUR', '1');
	 * define('DAY', '1');
	 *
	 * $startDate = date('Y-m-d', strtotime('now'));
	 * $endDate = date('Y-m-d', strtotime('now'));
	 *
	 *
	 * $current_stime = date('H:i:s', strtotime('now'));
	 * $current_etime = date('H:i:s', strtotime("+".HOUR." hour"));
	 *
	 *
	 * $this->load->model('createtask/createtask');
	 *
	 * if($query->num_rows > 0){
	 *
	 * foreach($query->rows as $row){
	 * //var_dump($row);
	 * //echo "<hr>";
	 *
	 * //$sql2 = "SELECT * FROM `" . DB_PREFIX . "medication` where create_task = '0' and status = '1' and tags_id = '".$row['tags_id']."' and `start_date` <= '".$startDate."' AND end_date >= '".$endDate."' ";
	 *
	 *
	 * $sql2 = "select n.*,m.* from `" . DB_PREFIX . "medication` n left JOIN `" . DB_PREFIX . "medication_time` m on m.medication_id=n.medication_id where n.create_task = '0' and n.status = '1' and n.tags_id = '".$row['tags_id']."' and n.start_date <= '".$startDate."' AND n.end_date >= '".$endDate."' order by m.start_time ";
	 *
	 * //echo "<hr>";
	 *
	 * $query2 = $this->db->query($sql2);
	 * //var_dump($query2->num_rows);
	 *
	 *
	 * if($query2->num_rows > 0){
	 * $medicineArray = array();
	 * foreach($query2->rows as $row2){
	 * //var_dump($row2);
	 * if($row2['start_time'] != null && $row2['start_time'] != ""){
	 * $snooze_time71 = 0;
	 * $thestime61 = $row2['start_time'];
	 * }else{
	 * $snooze_time71 = 0;
	 * $thestime61 = date('H:i:s');
	 * }
	 *
	 * $taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
	 *
	 * $sql = "INSERT INTO `" . DB_PREFIX . "createtask_by_medication` SET id = '', facilities_id = '" . $row['facilities_id']. "', locations_id = '" . $row['locations_id']. "', tags_id = '" . $row['tags_id'] . "', medication_id = '" . $row2['medication_id']. "', drug_name = '" . $row2['drug_name']. "', dose = '" . $row2['dose']. "', drug_type = '" . $row2['drug_type']. "', quantity = '" . $row2['quantity']. "', frequency = '" . $row2['frequency']. "', start_time = '" . $taskTime. "', instructions = '" . $this->db->escape($row2['instructions']). "', count = '" . $row2['count']. "', complete_status = '0' ";
	 * //$this->db->query($sql);
	 *
	 * $medicineArray[] = $row2['medication_id'];
	 *
	 *
	 * }
	 *
	 * var_dump($medicineArray);
	 *
	 * echo "<hr>";
	 * }
	 *
	 * }
	 * }
	 * }
	 */
	public function formrulenotification() {
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'notes/rules' );
		$this->load->model ( 'setting/highlighter' );
		$this->load->model ( 'setting/country' );
		$this->load->model ( 'setting/zone' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'user/user' );
		
		$this->load->model ( 'form/form' );
		
		// require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		require_once (DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		$sql = "SELECT * FROM " . DB_PREFIX . "formrules r LEFT JOIN " . DB_PREFIX . "formrules_tigger rt ON (r.rules_id = rt.rules_id) where r.status='1' ";
		$query = $this->db->query ( $sql );
		
		if ($query->num_rows) {
			foreach ( $query->rows as $rule ) {
				
				$allnotesIds = array ();
				
				$rulename = $rule ['rules_name'];
				$rules_id = $rule ['rules_id'];
				// var_dump($rules_id);
				
				$facility = $this->model_facilities_facilities->getfacilities ( $rule ['facilities_id'] );
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facility ['timezone_id'] );
				
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$current_date = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$current_time = date ( 'Y-m-d H:i', strtotime ( 'now' ) );
				
				if ($rule ['rules_operation'] == '2') {
					$onschedule_rules_modules = unserialize ( $rule ['onschedule_rules_module'] );
					$forms_fields_search = 'Task';
					foreach ( $onschedule_rules_modules as $rules_module ) {
						$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id from `" . DB_PREFIX . "notes` n ";
						$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
						$sqls .= 'where 1 = 1 ';
						
						if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
							$ddss [] = $facility ['task_facilities_ids'];
							// $ddss [] = $facilities_id;
							$sssssdd = implode ( ",", $ddss );
							$faculities_ids = $sssssdd;
							$sqls .= " and n.facilities_id in  (" . $faculities_ids . ") ";
						} else {
							$sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
						}
						
						// $sqls .= " and n.facilities_id = '" . $facility ['facilities_id'] . "'";
						$sqls .= " and f.custom_form_type = '" . $rule ['forms_id'] . "'";
						$sqls .= " and f.is_discharge = '0'";
						$sqls .= " and n.form_alert_send_email = '0' ";
						$sqls .= " and n.form_alert_send_sms = '0' ";
						$sqls .= " and n.form_snooze_dismiss = '2' ";
						
						$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
						
						$query = $this->db->query ( $sqls );
						// var_dump($query);
						
						if ($query->num_rows) {
							
							foreach ( $query->rows as $result ) {
								
								// var_dump($result);
								
								$date_added = $result ['date_added'];
								$form_due_date_after = $rules_module ['form_due_date_after'];
								
								switch ($rules_module ['form_due_date']) {
									
									case 'Month' :
										
										$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
										
										// var_dump($newdate);
										// if($newdate == $current_date){
										
										if ($rules_module ['formalerts'] != NULL && $rules_module ['formalerts'] != "") {
											foreach ( $rules_module ['formalerts'] as $formalerts ) {
												$task_alertselection = $formalerts ['task_alertselection'];
												
												// var_dump($formalerts);
												
												switch ($formalerts ['task_alertselection_before']) {
													
													case 'Month' :
														// var_dump($form_due_date_after);
														
														$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
														$newdatebefore = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $newdate ) ) . "-" . $task_alertselection . " month" ) );
														
														if ($current_date == $newdatebefore) {
															
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													
													case 'Days' :
														
														$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
														$newdatebefore = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $newdate ) ) . "-" . $task_alertselection . " day" ) );
														
														// var_dump($newdatebefore);
														if ($current_date == $newdatebefore) {
															
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													
													case 'Hours' :
														
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
														// $newdate = date('H:i',strtotime('+'.$form_due_date_after.' hour',strtotime($date_added)));
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' hour', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'Minutes' :
														
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " month" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' minutes', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is submitted' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is updated' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
												}
											}
										}
										
										// }
										
										break;
									
									case 'Days' :
										
										// var_dump($date_added);
										// var_dump($form_due_date_after);
										// echo "<hr>";
										
										$newdate = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
										
										// var_dump($newdate);
										
										if ($rules_module ['formalerts'] != NULL && $rules_module ['formalerts'] != "") {
											
											foreach ( $rules_module ['formalerts'] as $formalerts ) {
												$task_alertselection = $formalerts ['task_alertselection'];
												switch ($formalerts ['task_alertselection_before']) {
													
													case 'Days' :
														
														// var_dump($task_alertselection);
														// $newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day"));
														$newdatebefore = date ( "Y-m-d", strtotime ( date ( "Y-m-d H:i", strtotime ( $newdate ) ) . "-" . $task_alertselection . " day" ) );
														
														// var_dump($newdatebefore );
														
														if ($newdatebefore == $current_date) {
															
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													
													case 'Hours' :
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' hour', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'Minutes' :
														
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' minutes', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is submitted' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is updated' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
												}
											}
										}
										
										break;
									
									case 'Hours' :
										$newdate = date ( 'H:i', strtotime ( '+' . $form_due_date_after . ' hour', strtotime ( $date_added ) ) );
										
										if ($rules_module ['formalerts'] != NULL && $rules_module ['formalerts'] != "") {
											
											foreach ( $rules_module ['formalerts'] as $formalerts ) {
												$task_alertselection = $formalerts ['task_alertselection'];
												switch ($formalerts ['task_alertselection_before']) {
													
													case 'Hours' :
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' hour', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'Minutes' :
														
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' minutes', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is submitted' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is updated' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
												}
											}
										}
										
										break;
									case 'Minutes' :
										$newdate = date ( 'H:i', strtotime ( '+' . $form_due_date_after . ' minutes', strtotime ( $date_added ) ) );
										if ($rules_module ['formalerts'] != NULL && $rules_module ['formalerts'] != "") {
											
											foreach ( $rules_module ['formalerts'] as $formalerts ) {
												$task_alertselection = $formalerts ['task_alertselection'];
												switch ($formalerts ['task_alertselection_before']) {
													
													case 'Hours' :
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' hour', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'Minutes' :
														
														$newdate = date ( "Y-m-d H:i", strtotime ( date ( "Y-m-d H:i", strtotime ( $date_added ) ) . " +" . $form_due_date_after . " day" ) );
														$newdateafter = date ( 'Y-m-d H:i', strtotime ( '-' . $task_alertselection . ' minutes', strtotime ( $newdate ) ) );
														
														if ($newdateafter == $current_time) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is submitted' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
													case 'is updated' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
												}
											}
										}
										
										break;
									
									case 'is submitted' :
										$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
										
										if ($rules_module ['formalerts'] != NULL && $rules_module ['formalerts'] != "") {
											
											foreach ( $rules_module ['formalerts'] as $formalerts ) {
												$task_alertselection = $formalerts ['task_alertselection'];
												switch ($formalerts ['task_alertselection_before']) {
													
													case 'is submitted' :
														$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
														if ($newdate == $current_date) {
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
																	'form_due_date' => $rules_module ['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts ['rule_action'] 
															);
															
															/*
															 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 *
															 * if(in_array('1', $rules_module['action'])){
															 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															 * }
															 */
														}
														break;
												}
											}
										}
										
										break;
									
									case 'is updated' :
										$newdate = date ( 'Y-m-d', strtotime ( $date_added ) );
										if ($newdate == $current_date) {
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
													'form_due_date' => $rules_module ['form_due_date'],
													'rules_id' => $rules_id,
													'rule_action' => $formalerts ['rule_action'] 
											);
											
											/*
											 * $this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
											 *
											 * if(in_array('1', $rules_module['action'])){
											 * $this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
											 * }
											 */
										}
										
										break;
								}
							}
						}
					}
				}
				
				// var_dump($allnotesIds);
				// echo "<hr>";
				
				if ($allnotesIds != null && $allnotesIds != "") {
					
					foreach ( $allnotesIds as $allnotesId ) {
						$this->formSendEmail ( $allnotesId ['notes_id'], $allnotesId ['rules_type'], $allnotesId ['rules_value'], $allnotesId ['user_roles'], $allnotesId ['userids'], $allnotesId ['rules_id'] );
						
						if (in_array ( '1', $allnotesId ['rule_action'] )) {
							$this->formSendSMS ( $allnotesId ['notes_id'], $allnotesId ['rules_type'], $allnotesId ['rules_value'], $allnotesId ['user_roles'], $allnotesId ['userids'], $allnotesId ['rules_id'] );
						}
					}
					
					foreach ( $allnotesIds as $allnotesId ) {
						$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_email = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
						$query = $this->db->query ( $sql3e );
					}
					
					if (in_array ( '1', $allnotesId ['rule_action'] )) {
						foreach ( $allnotesIds as $allnotesId ) {
							$sql32e = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_sms = '1' WHERE notes_id = '" . $allnotesId ['notes_id'] . "'";
							$query = $this->db->query ( $sql32e );
						}
					}
				}
			}
		}
	}
	public function formSendSMS($notes_id, $rulename, $forms_fields_search, $user_roles, $userids, $rules_id) {
		$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '" . $notes_id . "' and form_alert_send_sms = '0' ";
		$query = $this->db->query ( $sqlsnote );
		
		$note_info = $query->row;
		if ($note_info != null && $note_info != "") {
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
			$facilityDetails ['rules_value'] = $forms_fields_search;
			
			$sqlsnot1e = "SELECT * FROM `" . DB_PREFIX . "createtask` where formrules_id = '" . $rules_id . "' and rules_task = '" . $notes_id . "'  ";
			$query1 = $this->db->query ( $sqlsnot1e );
			
			if ($query1->row != null && $query1->row != "") {
				$note_info1 = $query1->row;
			} else {
				$note_info1 = '';
			}
			
			$message = "Form Rule Reminder \n";
			
			if ($note_info1 != null && $note_info1 != "") {
				$message .= date ( 'h:i A', strtotime ( $note_info1 ['task_time'] ) ) . "\n";
			} else {
				$message .= date ( 'h:i A', strtotime ( $note_info ['notetime'] ) ) . "\n";
			}
			
			$message .= $rulename . ' | ' . $forms_fields_search . "\n";
			
			if ($note_info1 != null && $note_info1 != "") {
				$message .= substr ( $note_info1 ['description'], 0, 150 ) . ((strlen ( $note_info1 ['description'] ) > 150) ? '..' : '');
			} else {
				$message .= substr ( $note_info ['notes_description'], 0, 150 ) . ((strlen ( $note_info ['notes_description'] ) > 150) ? '..' : '');
			}
			
			// $message .= $note_info['notes_description'];
			
			if ($user_roles != null && $user_roles != "") {
				$user_roles1 = $user_roles; // explode(',',$result['user_roles']);
				
				$this->load->model ( 'user/user_group' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				$this->load->model ( 'api/smsapi' );
				
				foreach ( $user_roles1 as $user_role ) {
					
					$urole = array ();
					$urole ['user_group_id'] = $user_role;
					$tusers = $this->model_user_user->getUsers ( $urole );
					
					if ($tusers) {
						foreach ( $tusers as $tuser ) {
							// var_dump($tuser);
							if ($tuser ['phone_number']) {
								$sdata = array ();
								$sdata ['message'] = $message;
								$sdata ['phone_number'] = $tuser ['phone_number'];
								$sdata ['facilities_id'] = $note_info ['facilities_id'];
								$response = $this->model_api_smsapi->sendsms ( $sdata );
							}
						}
					}
				}
			}
			
			if ($userids != null && $userids != "") {
				$userids1 = $userids; // explode(',',$result['userids']);
				
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				$this->load->model ( 'api/smsapi' );
				
				foreach ( $userids1 as $userid ) {
					$user_info = $this->model_user_user->getUserbyupdate ( $userid );
					
					if ($user_info) {
						if ($user_info ['phone_number']) {
							
							$sdata = array ();
							$sdata ['message'] = $message;
							$sdata ['phone_number'] = $user_info ['phone_number'];
							$sdata ['facilities_id'] = $note_info ['facilities_id'];
							$response = $this->model_api_smsapi->sendsms ( $sdata );
						}
					}
				}
			}
		}
	}
	public function formSendEmail($notes_id, $rulename, $forms_fields_search, $user_roles, $userids, $rules_id) {
		$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '" . $notes_id . "' and form_alert_send_email = '0' ";
		$query = $this->db->query ( $sqlsnote );
		
		$note_info = $query->row;
		
		$sqlsnot1e = "SELECT * FROM `" . DB_PREFIX . "createtask` where formrules_id = '" . $rules_id . "' and rules_task = '" . $notes_id . "' ";
		$query1 = $this->db->query ( $sqlsnot1e );
		
		if ($query1->row != null && $query1->row != "") {
			$note_info1 = $query1->row;
		} else {
			$note_info1 = '';
		}
		
		// var_dump($note_info1);
		
		if ($note_info != null && $note_info != "") {
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
			$facilityDetails ['rules_type'] = '';
			$facilityDetails ['rules_value'] = $forms_fields_search;
			
			$message33 = "";
			$message33 .= $this->sendEmailtemplate ( $note_info, $rulename, 'Alerts', $forms_fields_search, $facilityDetails, $note_info1 );
			
			if ($user_roles != null && $user_roles != "") {
				$user_roles1 = $user_roles; // explode(',',$result['user_roles']);
				
				$this->load->model ( 'user/user_group' );
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				$useremailids = array ();
				
				foreach ( $user_roles1 as $user_role ) {
					
					$urole = array ();
					$urole ['user_group_id'] = $user_role;
					$tusers = $this->model_user_user->getUsers ( $urole );
					
					if ($tusers) {
						foreach ( $tusers as $tuser ) {
							// var_dump($tuser);
							if ($tuser ['email']) {
								$useremailids [] = $tuser ['email'];
							}
						}
					}
				}
			}
			
			if ($userids != null && $userids != "") {
				$userids1 = $userids; // explode(',',$result['userids']);
				
				$this->load->model ( 'user/user' );
				$this->load->model ( 'setting/tags' );
				
				foreach ( $userids1 as $userid ) {
					$user_info = $this->model_user_user->getUserbyupdate ( $userid );
					
					if ($user_info) {
						if ($user_info ['email']) {
							$useremailids [] = $user_info ['email'];
						}
					}
				}
			}
			
			$this->load->model ( 'api/emailapi' );
			
			$edata = array ();
			$edata ['message'] = $message33;
			$edata ['subject'] = 'Form Rule Reminder';
			$edata ['useremailids'] = $useremailids;
			
			$email_status = $this->model_api_emailapi->sendmail ( $edata );
		}
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
	public function incidentTasks() {
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/zone' );
		$this->load->model ( 'setting/timezone' );
		$this->load->model ( 'notes/notes' );
		
		$results = $this->model_facilities_facilities->getfacilitiess ( $data );
		
		if ($results != null && $results != "") {
			foreach ( $results as $tresult ) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone ( $tresult ['timezone_id'] );
				date_default_timezone_set ( $timezone_info ['timezone_value'] );
				
				$tasks = "SELECT * from " . DB_PREFIX . "tasktype where status = 1 and generate_report = 1 and forms_id != 0";
				$alltasks = $this->db->query ( $tasks );
				
				$date = date ( "Y-m-d" );
				$start = $date . " 00:00:00";
				$end = $date . " 23:59:59";
				if ($alltasks->num_rows > 0) {
					
					foreach ( $alltasks->rows as $tasks ) {
						
						$notestask = "SELECT * from " . DB_PREFIX . "notes where tasktype = " . $tasks ['task_id'] . " and generate_report = '0' and facilities_id = " . $tresult ['facilities_id'] . " and date_added BETWEEN '" . $start . "' AND '" . $end . "' and user_id != '" . SYSTEM_GENERATED . "' ";
						$allnotes = $this->db->query ( $notestask );
						
						if ($allnotes->num_rows > 0) {
							
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
							$data ['facilitytimezone'] = $timezone_name;
							$data ['date_added'] = $date_added;
							$data ['formsids'] = $tasks ['forms_id'];
							
							$data ['notes_description'] = 'REPORT Auto Generated';
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $tresult ['facilities_id'] );
							
							$data23 = array ();
							$data23 ['forms_design_id'] = $tasks ['forms_id'];
							$data23 ['notes_id'] = $notes_id;
							$data23 ['facilities_id'] = $tresult ['facilities_id'];
							
							$this->load->model ( 'form/form' );
							$formreturn_id = $this->model_form_form->addFormdata ( $data, $data23 );
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '" . $notes_id . "'";
							$this->db->query ( $slq1 );
							
							foreach ( $allnotes->rows as $notess ) {
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '" . $notess ['notes_id'] . "'";
								$this->db->query ( $slq1 );
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '3', task_group_by = '" . $notess ['task_group_by'] . "', parent_id = '" . $notess ['parent_id'] . "', task_type = '" . $notess ['task_type'] . "' where notes_id = '" . $notes_id . "'";
								$this->db->query ( $slq1 );
							}
						}
					}
				}
			}
		}
	}
	public function connectsftp() {
		try {
			define ( 'SFTPCONECTION', '1' );
			
			$this->registry->set ( 'sftpconnection', new Sftpconnection ( $this->registry ) );
			
			$status = $this->sftpconnection->uploadsftpFile ();
			
			var_dump ( $status );
			
			$files = glob ( DIR_IMAGE . 'sftp/*.csv' );
			
			foreach ( $files as $outputFolder ) {
				// var_dump($outputFolder);
				$file = basename ( $outputFolder );
				// var_dump($file);
				$s3file = $this->awsimageconfig->uploadFilesftp ( $file, $outputFolder );
				// var_dump($s3file);
				// echo "<hr>";
				unlink ( $outputFolder );
			}
			
			$pfiles = glob ( DIR_IMAGE . 'sftppicture/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE );
			// $pfiles = glob ( DIR_IMAGE . 'sftppicture/*' );
			
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'facilities/facilities' );
			foreach ( $pfiles as $poutputFolder ) {
				
				$pfile = basename ( $poutputFolder );
				
				$picture_filename = pathinfo ( $pfile, PATHINFO_FILENAME );
				$ps3file = $this->awsimageconfig->uploadsftpFile ( $pfile, $poutputFolder );
				
				$tag_info = $this->model_setting_tags->getTagsbyextID2 ( $picture_filename );
				
				$facilities_id = $tag_info ['facilities_id'];
				$tags_id = $tag_info ['tags_id'];
				
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$get_img = $this->model_setting_tags->getImage ( $tags_id );
				
				if ($get_img ['enroll_image'] == null && $get_img ['enroll_image'] == "") {
					
					if ($facilities_info ['is_client_facial'] == '1') {
						
						if ($tag_info ['emp_tag_id'] != null && $tag_info ['emp_tag_id'] != "") {
							$femp_tag_id = $tag_info ['emp_tag_id'];
							
							$outputFolderUrl = $ps3file;
							// require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
							
							$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag ( $outputFolderUrl, $femp_tag_id, $facilities_id );
							
							foreach ( $result_inser_user_img22 ['FaceRecords'] as $b ) {
								$FaceId = $b ['Face'] ['FaceId'];
								$ImageId = $b ['Face'] ['ImageId'];
							}
							
							$this->model_setting_tags->insertTagimageenroll ( $tags_id, $FaceId, $ImageId, $ps3file, $facilities_id );
						}
					} else {
						
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $ps3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
						
						$this->db->query ( $tsql );
					}
				}
				
				// var_dump($s3file);
				echo "<hr>";
				unlink ( $poutputFolder );
			}
		} catch ( Exception $e ) {
			var_dump ( $e->getMessage () );
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in syndb connectsftp ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'connectsftp', $activity_data2 );
		}
	}
	public function uploadpicture() {
		try {
			
			$pfiles = array (
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014300.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014585.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/11913200.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014538.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014554.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12012440.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014669.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014391.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014561.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014565.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014313.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014462.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014484.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12011214.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014488.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/11710652.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/11800878.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/11912229.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12011029.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12012739.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12012906.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12013424.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014181.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014490.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12014657.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12110034.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12110389.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12110395.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12110396.jpg',
					'https://louisiana.s3-us-gov-west-1.amazonaws.com/12110400.jpg' 
			);
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'facilities/facilities' );
			foreach ( $pfiles as $ps3file ) {
				
				$pfile = basename ( $ps3file );
				$picture_filename = pathinfo ( $pfile, PATHINFO_FILENAME );
				
				$tag_info = $this->model_setting_tags->getTagsbyextID2 ( $picture_filename );
				
				$facilities_id = $tag_info ['facilities_id'];
				$tags_id = $tag_info ['tags_id'];
				
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$get_img = $this->model_setting_tags->getImage ( $tags_id );
				
				if ($get_img ['enroll_image'] == null && $get_img ['enroll_image'] == "") {
					
					if ($facilities_info ['is_client_facial'] == '1') {
						
						if ($tag_info ['emp_tag_id'] != null && $tag_info ['emp_tag_id'] != "") {
							$femp_tag_id = $tag_info ['emp_tag_id'];
							
							$outputFolderUrl = $ps3file;
							
							$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag ( $outputFolderUrl, $femp_tag_id, $facilities_id );
							
							foreach ( $result_inser_user_img22 ['FaceRecords'] as $b ) {
								$FaceId = $b ['Face'] ['FaceId'];
								$ImageId = $b ['Face'] ['ImageId'];
							}
							
							$this->model_setting_tags->insertTagimageenroll ( $tags_id, $FaceId, $ImageId, $ps3file, $facilities_id );
						}
					} else {
						
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						echo $tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $ps3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
						
						$this->db->query ( $tsql );
					}
					
					echo "<hr>";
				}
			}
		} catch ( Exception $e ) {
			var_dump ( $e->getMessage () );
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in syndb uploadpicture ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'uploadpicture', $activity_data2 );
		}
	}
	
	
	
	
	
} 
	

/*
$hostname = "166.62.28.137";
			$username = "power_bi_user";
			$password = "power_bi_user";
			$dbname = "power_bi_db";

			$connection = mysql_connect($hostname, $username, $password);
			var_dump($connection);
			echo mysql_error();
			mysql_select_db($dbname, $connection);
			
			//Setup our query
			echo $query = "SELECT * FROM ". DB_PREFIX."user ";
			 
			//Run the Query
			$result = mysql_query($query);
			 
			//If the query returned results, loop through
			// each result
			var_dump($result);
			if($result)
			{
			  while($row = mysql_fetch_array($result))
			  {
				$name = $row['username'];
				echo "Name: " . $name; 

			  }
			}
			mysql_close($connection);
*/