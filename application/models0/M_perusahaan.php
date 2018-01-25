<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Perusahaan extends CI_Model {
    
    private $last_id = 0;
    
    public function get_last_id()
    {
        return $this->last_id;
    }

    public function is_exist($id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->count_all_results("perusahaan");
        
        $this->db->close();
        
        return $count > 0;
    }

    public function load_data($page, $perpage, $order_by, $search, $acc_level, $filter)
    {
        $this->load->database();
        
        $result = array();

        $offset = ($page - 1) * $perpage;
        $this->db->limit($perpage, $offset);

        $ex = explode("+", $order_by);
        if (count($ex) > 1) {
            $sort_field = $ex[0];
            $sort_dir = $ex[1];
            $this->db->order_by($sort_field, $sort_dir);
        }
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }
        
        if ($filter == "iupop"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks"){
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "Tidak CNC");
        }
        
        $query = $this->db->get("perusahaan");
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function count_data($search, $acc_level, $filter)
    {
        $this->load->database();
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }
        
        if ($filter == "iupop"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks"){
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "Tidak CNC");
        }
        
        $count = $this->db->count_all_results("perusahaan");
        
        $this->db->close();
        
        return $count;
    }

    public function get_expire_total($filter, $acc_level)
    {
        $this->load->database();
        
        if ($filter == "iupop"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks"){
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "Tidak CNC");
        }
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        $count = $this->db->count_all_results("perusahaan");
        // echo $this->db->last_query();
        
        $this->db->close();
        
        return $count;
    }

    public function get_one($id)
    {
        $this->load->database();
        
        $result = array();
        $this->db->where("`id`", $id);
        $query = $this->db->get("perusahaan");
        if ( $query->num_rows() > 0) {
            $result = $query->row_array();
        }
        
        $this->db->close();
        
        return $result;
    }

    public function get_data($filter, $acc_level)
    {
        $this->load->database();
        
        $result = array();
        
        if ($filter == "iupop"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks"){
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "Tidak CNC");
        }
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        $query = $this->db->get("perusahaan");
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function insert($data)
    {
        $this->load->database();
        
        $count = $this->db->insert("perusahaan", $data);
        $this->last_id = $this->db->insert_id();
        
        $this->db->close();
        
        return $count > 0;
    }

    public function update($data, $id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->update("perusahaan", $data);
        // echo $this->db->last_query();
        
        $this->db->close();
        
        return $count > 0;
    }

    public function delete($id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->delete("perusahaan");
        
        $this->db->close();
        
        return $count > 0;
    }
}
