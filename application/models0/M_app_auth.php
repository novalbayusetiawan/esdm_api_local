<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_app_auth extends CI_Model {

    public function is_valid($app_id, $app_token)
    {
        $this->load->database("web_api");
        
        $this->db->where("app_id", $app_id);
        $this->db->where("app_token", $app_token);
        $count = $this->db->count_all_results("app_auth");
        
        $this->db->close();
        
        return $count > 0;
    }
}
