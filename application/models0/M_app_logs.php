<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_app_logs extends CI_Model {
    
    private $last_id = 0;
    
    public function get_last_id()
    {
        return $this->last_id;
    }

    public function insert($data)
    {
        $this->load->database("web_api");
        
        $count = $this->db->insert("app_logs", $data);
        $this->last_id = $this->db->insert_id();
        
        $this->db->close();
        
        return $count > 0;
    }
}
