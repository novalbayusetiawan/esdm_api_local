<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {
    
    private $last_id = 0;
    
    public function get_last_id()
    {
        return $this->last_id;
    }

    public function is_exist($id)
    {
        $this->load->database();
        
        $this->db->where("id", $id);
        $count = $this->db->count_all_results("user");
        
        $this->db->close();
        
        return $count > 0;
    }

    public function is_oldpass_correct($password, $id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $this->db->where("password", $password);
        $count = $this->db->count_all_results("user");
        
        $this->db->close();
        
        return $count > 0;
    }

    public function is_username_exist($new_username, $old_username = Null)
    {
        $this->load->database();
        
        if ($old_username) {
            $this->db->where("username !=", $old_username);
        }
        $this->db->where("username", $new_username);
        $count = $this->db->count_all_results("user");
        // echo $this->db->last_query();
        
        $this->db->close();
        
        return $count > 0;
    }

    public function load_data($page, $perpage, $order_by, $search)
    {
        $this->load->database();
        
        $result = array();

        $offset = ($page - 1) * $perpage;
        $this->db->select("id, username, nama_wilayah");
        $this->db->limit($perpage, $offset);

        $ex = explode("+", $order_by);
        if (count($ex) > 1) {
            $sort_field = $ex[0];
            $sort_dir = $ex[1];
            $this->db->order_by($sort_field, $sort_dir);
        }

        if ($search) {
            $this->db->where("id like '%{$search}%'");
            $this->db->or_where("username like '%{$search}%'");
            $this->db->or_where("nama_wilayah like '%{$search}%'");
        }
        
        $query = $this->db->get("user");
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function count_data($search)
    {
        $this->load->database();

        if ($search) {
            $this->db->where("id like '%{$search}%'");
            $this->db->or_where("username like '%{$search}%'");
            $this->db->or_where("nama_wilayah like '%{$search}%'");
        }
        
        $count = $this->db->count_all_results("user");
        
        $this->db->close();
        
        return $count;
    }
    
    public function get_regions()
    {
        $this->load->database();
        
        $sql = "SELECT DISTINCT(nama_wilayah) AS region FROM user ORDER BY region DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        
        $this->db->close();
        
        return $result;
    }

    public function get_one($id)
    {
        $this->load->database();
        
        $result = array();
        $this->db->where("`id`", $id);
        $query = $this->db->get("user");
        if ( $query->num_rows() > 0) {
            $result = $query->row_array();
            unset($result["password"]);
        }

        $this->db->close();
        
        return $result;
    }

    public function get_signed($username, $password)
    {
        $this->load->database();
        
        $result = array();
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $query = $this->db->get("user");
        if ( $query->num_rows() > 0) {
            $result = $query->row_array();
            unset($result["password"]);
        }

        $this->db->close();
        
        return $result;
    }

    public function is_signed($username, $password)
    {
        $this->load->database();
        
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $count = $this->db->count_all_results("user");
        
        $this->db->close();
        
        return $count > 0;
    }

    public function insert($data)
    {
        $this->load->database();
        
        $count = $this->db->insert("user", $data);
        $this->last_id = $this->db->insert_id();
        
        $this->db->close();
        
        return $count > 0;
    }

    public function update($data, $id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->update("user", $data);
        
        $this->db->close();
        
        return $count > 0;
    }

    public function delete($id)
    {
        $this->load->database();
        
        $this->db->where("`id`", $id);
        $count = $this->db->delete("user");
        
        $this->db->close();
        
        return $count > 0;
    }
}
