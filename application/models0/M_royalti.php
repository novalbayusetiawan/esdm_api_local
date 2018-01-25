<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_royalti extends CI_Model {
    
    private $last_id = 0;
    
    public function get_last_id()
    {
        return $this->last_id;
    }

    public function is_exist($id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->count_all_results("royalti");
        
        $this->db->close();
        
        return $count > 0;
    }

    public function load_data($page, $perpage, $order_by, $search, $acc_level, $id_perusahaan)
    {
        $this->load->database();
        
        $result = array();

        $offset = ($page - 1) * $perpage;
        $this->db->select("perusahaan.nama_perusahaan, royalti.*");
        $this->db->limit($perpage, $offset);

        $ex = explode("+", $order_by);
        if (count($ex) > 1) {
            $sort_field = $ex[0];
            $sort_dir = $ex[1];
            $this->db->order_by($sort_field, $sort_dir);
        }
        
        if ($id_perusahaan) {
            $this->db->where("royalti.id_perusahaan", $id_perusahaan);
        }
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }
        
        $this->db->join("perusahaan", "perusahaan.id = royalti.id_perusahaan", "right");
        $query = $this->db->get("royalti");
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function count_data($search, $acc_level, $id_perusahaan)
    {
        $this->load->database();
        
        if ($id_perusahaan) {
            $this->db->where("royalti.id_perusahaan", $id_perusahaan);
        }
        
        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }
        
        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }
        $this->db->join("perusahaan", "perusahaan.id = royalti.id_perusahaan", "right");
        $count = $this->db->count_all_results("royalti");
        
        $this->db->close();
        
        return $count;
    }

    public function get_data_by_perusahaan($id_perusahaan)
    {
        $this->load->database();
        
        $result = array();
        $this->db->where("id_perusahaan", $id_perusahaan);
        $query = $this->db->get("royalti");
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function get_one($id)
    {
        $this->load->database();
        
        $result = array();
        $this->db->where("`id`", $id);
        $query = $this->db->get("royalti");
        if ( $query->num_rows() > 0) {
            $result = $query->row_array();
        }
        
        $this->db->close();
        
        return $result;
    }

    public function insert($data)
    {
        $this->load->database();
        
        $count = $this->db->insert("royalti", $data);
        $this->last_id = $this->db->insert_id();
        
        $this->db->close();
        
        return $count > 0;
    }

    public function update($data, $id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->update("royalti", $data);
        
        $this->db->close();
        
        return $count > 0;
    }

    public function delete($id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->delete("royalti");
        
        $this->db->close();
        
        return $count > 0;
    }
}
