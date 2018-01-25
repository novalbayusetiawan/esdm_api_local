<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_auth extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
    }

    public function is_valid()
    {
        header("Content-type: application/json");

        $today = date("Y-m-d");
        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);

        if ($valid) {
            $status = True;
            $message = "Application Registered";
            $access_token = hash("sha256",$today.$app_id.$app_token);
        } else {
            $status = False;
            $message = "Application not Registered";
            $access_token = False;
        }

        $output = array(
            "status" => $status,
            "message" => $message,
            "result" => array(
                "access_token" => $access_token
            )
        );
        echo json_encode($output);
    }
}
