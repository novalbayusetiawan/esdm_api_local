<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
        $this->load->model("m_app_logs");
        $this->load->model("m_user");
    }

    public function update()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $id = $this->input->post("id");
        $username = $this->input->post("username");
        $last_username = $this->input->post("last_username");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $username_exist = $this->m_user->is_username_exist($username, $last_username);
            if ( ! $username_exist) {
                $prev_data = array(
                    "username" => $last_username
                );
                $data = array(
                    "username" => $username
                );
                $exe = $this->m_user->update($data, $id);
                if ( $exe ) {
                    $status = True;
                    $message = "Update Account Success";
                }
            } else {
                $status = False;
                $message = "Username already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "account/update",
                "created" => date("Y-m-d H:i:s"),
                "action" => "u",
                "activity" => json_encode(array(
                    "from" => $prev_data,
                    "to" => $data
                ))
            );
            $this->m_app_logs->insert($log_activity);
            
        } else {
            $status = False;
            $message = "Application not Registered";
        }

        $output = array(
            "status" => $status,
            "message" => $message,
            "result" => $data
        );
        echo json_encode($output);
    }

    public function update_password()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");
        
        $new_password = $this->input->post("new_password");
        $old_password = $this->input->post("old_password");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $is_oldpass_correct = $this->m_user->is_oldpass_correct($old_password, $id);
            if ($is_oldpass_correct) {
                $prev_data = array(
                    "password" => $old_password
                );
                $data = array(
                    "password" => $new_password
                );
                $exe = $this->m_user->update($data, $id);
                if ( $exe ) {
                    $status = True;
                    $message = "Update Password Success";
                }
            } else {
                $status = False;
                $message = "Old Password is not correct";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "account/update_password",
                "created" => date("Y-m-d H:i:s"),
                "action" => "u",
                "activity" => json_encode(array(
                    "from" => $prev_data,
                    "to" => $data
                ))
            );
            $this->m_app_logs->insert($log_activity);
        
        } else {
            $status = False;
            $message = "Application not Registered";
        }

        $output = array(
            "status" => $status,
            "message" => $message,
            "result" => $data
        );
        echo json_encode($output);
    }
}
