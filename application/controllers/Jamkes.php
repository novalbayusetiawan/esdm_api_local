<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jamkes extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
        $this->load->model("m_app_logs");
        $this->load->model("m_jamkes");
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

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $total = $this->m_jamkes->count_data($search, $acc_level, $id_perusahaan);
            $result = $this->m_jamkes->load_data($page, $perpage, $order_by, $search, $acc_level, $id_perusahaan);
            $data = array(
                "draw" => 0,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $result
            );
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamkes/dt_show",
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
            $data = $this->m_jamkes->get_one($id);
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamkes/get_one",
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
            $exist = $this->m_jamkes->is_exist($id);
            if ( ! $exist ) {
                $data = array(
                    "thn_prajamrek" => $this->input->post("thn_prajamrek"),
                    "rupiah_prajamrek" => $this->input->post("rupiah_prajamrek"),
                    "dollar_prajamrek" => $this->input->post("dollar_prajamrek"),
                    "thn_jamrek" => $this->input->post("thn_jamrek"),
                    "rupiah_jamrek" => $this->input->post("rupiah_jamrek"),
                    "dollar_jamrek" => $this->input->post("dollar_jamrek"),
                    "jenis_jamrek" => $this->input->post("jenis_jamrek"),
                    "bank_jamrek" => $this->input->post("bank_jamrek"),
                    "id_perusahaan" => $this->input->post("id_perusahaan")
                );
                $exe = $this->m_jamkes->insert($data);
                if ( $exe ) {
                    $status = True;
                    $message = "Insert Jaminan Kesungguhan Success";
                    $data["id"] = $this->m_jamkes->get_last_id();
                }
            } else {
                $status = False; $message = "Data already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "c",
                "created" => date("Y-m-d H:i:s"),
                "action" => "jamkes/insert",
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
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $prev_data = $this->m_jamkes->get_one($id);
            $data = array(
                "thn_prajamrek" => $this->input->post("thn_prajamrek"),
                "rupiah_prajamrek" => $this->input->post("rupiah_prajamrek"),
                "dollar_prajamrek" => $this->input->post("dollar_prajamrek"),
                "thn_jamrek" => $this->input->post("thn_jamrek"),
                "rupiah_jamrek" => $this->input->post("rupiah_jamrek"),
                "dollar_jamrek" => $this->input->post("dollar_jamrek"),
                "jenis_jamrek" => $this->input->post("jenis_jamrek"),
                "bank_jamrek" => $this->input->post("bank_jamrek"),
                "id_perusahaan" => $this->input->post("id_perusahaan")
            );
            $exe = $this->m_jamkes->update($data, $id);
            if ( $exe ) {
                $status = True;
                $message = "Update Jaminan Kesungguhan Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamkes/update",
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
            $data = $this->m_jamkes->get_one($id);
            $exe = $this->m_jamkes->delete($id);
            if ( $exe ) {
                $status = True;
                $message = "Delete Jamkes Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "jamkes/delete",
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
