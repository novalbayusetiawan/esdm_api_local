<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
        $this->load->model("m_app_logs");
        $this->load->model("m_user");
    }

    public function dt_show()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $page = $this->input->post_get("page")? $this->input->post_get("page") : 1;
        $perpage = $this->input->post_get("perpage")? $this->input->post_get("perpage") :  10;
        $order_by = $this->input->post_get("order_by")? $this->input->post_get("order_by") : "";
        $search = $this->input->post_get("search");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $total = $this->m_user->count_data($search);
            $result = $this->m_user->load_data($page, $perpage, $order_by, $search);
            $data = array(
                "draw" => 0,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $result
            );
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/dt_show",
                "created" => date("Y-m-d H:i:s"),
                "action" => "r",
                "activity" => json_encode($data)
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

    public function get_regions()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $data = $this->m_user->get_regions();
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/get_regions",
                "created" => date("Y-m-d H:i:s"),
                "action" => "r",
                "activity" => json_encode($data)
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

    public function get_one()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $data = $this->m_user->get_one($id);
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/get_one",
                "created" => date("Y-m-d H:i:s"),
                "action" => "r",
                "activity" => json_encode($data)
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

    public function insert()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $exist = $this->m_user->is_exist($id);
            if ( ! $exist ) {
                $data = array(
                    "username" => $this->input->post("username"),
                    "password" => $this->input->post("password"),
                    "nama_wilayah" => $this->input->post("nama_wilayah")
                );
                $exe = $this->m_user->insert($data);
                if ( $exe ) {
                    $status = True;
                    $message = "Insert User Success";
                    $data["id"] = $this->m_user->get_last_id();
                }
            } else {
                $status = False; $message = "Data already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/insert",
                "created" => date("Y-m-d H:i:s"),
                "action" => "c",
                "activity" => json_encode($data)
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
            $prev_data = $this->m_user->get_one($id);
            $username_exist = $this->m_user->is_username_exist($username, $last_username);
            if ( ! $username_exist) {
                $data = array(
                    "username" => $this->input->post("username"),
                    "nama_wilayah" => $this->input->post("nama_wilayah")
                );
                $exe = $this->m_user->update($data, $id);
                if ( $exe ) {
                    $status = True;
                    $message = "Update User Success";
                }
            } else {
                $status = False;
                $message = "Username already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/update",
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

    public function delete()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $data = $this->m_user->get_one($id);
            $exe = $this->m_user->delete($id);
            if ( $exe ) {
                $status = True;
                $message = "Delete User Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/delete",
                "created" => date("Y-m-d H:i:s"),
                "action" => "d",
                "activity" => json_encode($data)
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

    public function signin()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $signed = $this->m_user->is_signed($username, $password);
            if ( $signed ) {
                $status = True;
                $message = "Redirecting .....";
                $data = $this->m_user->get_signed($username, $password);
            } else {
                $status = False;
                $message = "Wrong username or Password";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "user/signin",
                "created" => date("Y-m-d H:i:s"),
                "action" => "r",
                "activity" => json_encode($data)
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
