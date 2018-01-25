<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jamtup extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
        $this->load->model("m_app_logs");
        $this->load->model("m_jamtup");
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
        $acc_level = $this->input->post_get("acc_level");
        $id_perusahaan = $this->input->post_get("id_perusahaan");

        $id = $this->input->post("id_jamtup");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $total = $this->m_jamtup->count_data($search, $acc_level, $id_perusahaan);
            $result = $this->m_jamtup->load_data($page, $perpage, $order_by, $search, $acc_level, $id_perusahaan);
            $data = array(
                "draw" => 0,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $result
            );
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamtup/dt_show",
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

        $id = $this->input->post("id_jamtup");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $data = $this->m_jamtup->get_one($id);
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamtup/get_one",
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

        $id = $this->input->post("id_jamtup");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $exist = $this->m_jamtup->is_exist($id);
            if ( ! $exist ) {
                $data = array(
                    "thn_prajamtup" => $this->input->post("thn_prajamtup"),
                    "rupiah_prajamtup" => $this->input->post("rupiah_prajamtup"),
                    "dollar_prajamtup" => $this->input->post("dollar_prajamtup"),
                    "thn_jamtup" => $this->input->post("thn_jamtup"),
                    "rupiah_jamtup" => $this->input->post("rupiah_jamtup"),
                    "dollar_jamtup" => $this->input->post("dollar_jamtup"),
                    "jenis_jamtup" => $this->input->post("jenis_jamtup"),
                    "bank_jamtup" => $this->input->post("bank_jamtup"),
                    "id_perusahaan" => $this->input->post("id_perusahaan")
                );
                $exe = $this->m_jamtup->insert($data);
                if ( $exe ) {
                    $status = True;
                    $message = "Insert Jamtup Success";
                    $data["id_jamtup"] = $this->m_jamtup->get_last_id();
                }
            } else {
                $status = False; $message = "Data already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamtup/insert",
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

        $id = $this->input->post("id_jamtup");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $prev_data = $this->m_jamtup->get_one($id);
            $data = array(
                "thn_prajamtup" => $this->input->post("thn_prajamtup"),
                "rupiah_prajamtup" => $this->input->post("rupiah_prajamtup"),
                "dollar_prajamtup" => $this->input->post("dollar_prajamtup"),
                "thn_jamtup" => $this->input->post("thn_jamtup"),
                "rupiah_jamtup" => $this->input->post("rupiah_jamtup"),
                "dollar_jamtup" => $this->input->post("dollar_jamtup"),
                "jenis_jamtup" => $this->input->post("jenis_jamtup"),
                "bank_jamtup" => $this->input->post("bank_jamtup"),
                "id_perusahaan" => $this->input->post("id_perusahaan")
            );
            $exe = $this->m_jamtup->update($data, $id);
            if ( $exe ) {
                $status = True;
                $message = "Update Jamtup Success";
            }
            
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamtup/update",
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

        $id = $this->input->post("id_jamtup");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $data = $this->m_jamtup->get_one($id);
            $exe = $this->m_jamtup->delete($id);
            if ( $exe ) {
                $status = True;
                $message = "Delete Jamtup Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamtup/delete",
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
}
