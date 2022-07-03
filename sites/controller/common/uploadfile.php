<?php

class ControllerCommonuploadfile extends Controller
{

    private $error = array();

    public function uploadFile ()
    {
        $json = array();
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        if ($this->request->files["file"] != null && $this->request->files["file"] != "") {
            
            $extension = end(explode(".", $this->request->files["file"]["name"]));
            
            if ($this->request->files["file"]["size"] < 42214400) {
                $neextension = strtolower($extension);
                // if($neextension != 'mp4' && $neextension != 'mp3' &&
                // $neextension != 'flv' && $neextension != '3gp' &&
                // $neextension != 'wav' && $neextension != 'mkv' &&
                // $neextension != 'avi'){
                
                $notes_file = 'devbolb' . rand() . '.' . $extension;
                $outputFolder = $this->request->files["file"]["tmp_name"];
                
                // require_once(DIR_SYSTEM .
                // 'library/azure_storage/config.php');
                
                // uploadBlobSample($blobClient, $outputFolder, $notes_file);
                
                // move_uploaded_file($this->request->files["file"]["tmp_name"],
                // $outputFolder);
                
                //require_once (DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
                
                $json['success'] = '1';
                $json['notes_media_extention'] = $extension;
                $json['notes_file'] = $s3file;
                $json['notes_file_url'] = $s3file;
                
                /*
                 * }else{
                 * $json['error'] = 'video or audio file not valid!';
                 * }
                 */
            } else {
                $json['error'] = 'Maximum size file upload!';
            }
        } else {
            $json['error'] = 'Please select file!';
        }
        
        $this->response->setOutput(json_encode($json));
    }

    public function uploadFilelocal ()
    {
        $json = array();
        
        if ($this->request->files["file"] != null && $this->request->files["file"] != "") {
            
            $extension = end(explode(".", $this->request->files["file"]["name"]));
            
            if ($this->request->files["file"]["size"] < 42214400) {
                $neextension = strtolower($extension);
                // if($neextension != 'mp4' && $neextension != 'mp3' &&
                // $neextension != 'flv' && $neextension != '3gp' &&
                // $neextension != 'wav' && $neextension != 'mkv' &&
                // $neextension != 'avi'){
                
                $support_image = uniqid() . "." . $extension;
                $outputFolder = DIR_IMAGE . 'facerecognition/' . $support_image;
                $outputFolder_url = HTTPS_SERVER . 'image/facerecognition/' . $support_image;
                
                move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);
                
                $json['success'] = '1';
                $json['notes_file'] = $outputFolder_url;
                $json['notes_file_url'] = $outputFolder_url;
                
                /*
                 * }else{
                 * $json['error'] = 'video or audio file not valid!';
                 * }
                 */
            } else {
                $json['error'] = 'Maximum size file upload!';
            }
        } else {
            $json['error'] = 'Please select file!';
        }
        
        $this->response->setOutput(json_encode($json));
    }
}