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

    public function load_data($page, $perpage, $order_by, $search, $acc_level, $filter, $advanced_filter = array())
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

		// $this->db->where("perusahaan.thn_iupop2 >= curdate()");
		if (is_array($advanced_filter)){
			if (count($advanced_filter)){
				if(isset($advanced_filter["filter_status"])){
					foreach($advanced_filter["filter_status"] as $row1){
						if($row1 == "aktif"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
									} elseif ($row == "iupeks"){
										$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
									}
								}
							}else {
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
							}
						}elseif($row1 == "expired"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
									} elseif ($row == "iupeks"){
										$this->db->where("perusahaan.thn_iupeks2 < curdate()");
									}
								}
							} else {
								$this->db->where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
							}
						}elseif($row1 == "warning"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
									} elseif ($row == "iupeks"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							} else{
								$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}
						}
					}
				}
				if(isset($advanced_filter["filter_iup"])){
					foreach($advanced_filter["filter_iup"] as $row1){
						if($row1 == "iupop"){
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
									}  elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
							}
						}elseif($row1 == "iupeks"){
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupeks2 < curdate()");
									} elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}
						}else{
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
										$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
										$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
									} elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
										$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}

						}
					}
				}

				if(isset($advanced_filter["filter_cnc"])){
					foreach($advanced_filter["filter_cnc"] as $row){
						$this->db->where("perusahaan.status_cnc", $row);
					}
				}

				if(isset($advanced_filter["filter_cabut"])){
					foreach($advanced_filter["filter_cabut"] as $row){
						if ($row == "layakcabut"){
							$this->db->where("perusahaan.thn_iupop2 < curdate()");
							$this->db->where("perusahaan.thn_iupeks2 < curdate()");
						}
						elseif($row == "sudahcabut"){
							$this->db->where("perusahaan.no_cabut != ''");
						}
					}
				}
			}
		}

        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }

        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }

        if ($filter == "iupop_aktip"){
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_exp"){
        	$this->db->where("perusahaan.no_iupop != ''");
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks_aktip"){
            $this->db->where("perusahaan.thn_iupeks2 >= curdate()");
        }
        elseif ($filter == "iupeks_exp"){
        	// $this->db->where("perusahaan.no_iupop","''");
        	$this->db->where("perusahaan.no_iupeks != ''");
            $this->db->where("perusahaan.thn_iupeks1", "0000-00-00");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }

        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "non cnc");
        }
        elseif ($filter == "cabut_layak"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cabut_sudah"){
            $this->db->where("perusahaan.no_cabut != ''");
        }

        if ($filter == "iupop_aktip_paser"){
            $this->db->where("perusahaan.kabupaten", "Paser");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_ppu"){
            $this->db->where("perusahaan.kabupaten", "Penajam Paser Utara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_balikpapan"){
            $this->db->where("perusahaan.kabupaten", "Balikpapan");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_samarinda"){
            $this->db->where("perusahaan.kabupaten", "Samarinda");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kukar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Kartanegara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kubar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Barat");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_mahulu"){
            $this->db->where("perusahaan.kabupaten", "Mahakam Ulu");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kutim"){
            $this->db->where("perusahaan.kabupaten", "Kutai Timur");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_bontang"){
            $this->db->where("perusahaan.kabupaten", "Bontang");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_berau"){
            $this->db->where("perusahaan.kabupaten", "Berau");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }

        $query = $this->db->get("perusahaan");
        $result = $query->result_array();

        $this->db->close();
		file_put_contents("./test2.txt", $this->db->last_query());
		file_put_contents("./test3.txt", json_encode($this->input->post()));

        return $result;
    }

    public function count_data($search, $acc_level, $filter, $advanced_filter = array())
    {
        $this->load->database();

		// $this->db->where("perusahaan.thn_iupop2 >= curdate()");
		if (is_array($advanced_filter)){
			if (count($advanced_filter)){
				if(isset($advanced_filter["filter_status"])){
					foreach($advanced_filter["filter_status"] as $row1){
						if($row1 == "aktif"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
									} elseif ($row == "iupeks"){
										$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
									}
								}
							}else {
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
							}
						}elseif($row1 == "expired"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
									} elseif ($row == "iupeks"){
										$this->db->where("perusahaan.thn_iupeks2 < curdate()");
									}
								}
							} else {
								$this->db->where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
							}
						}elseif($row1 == "warning"){
							if(isset($advanced_filter["filter_iup"])){
								foreach($advanced_filter["filter_iup"] as $row){
									if($row == "iupop"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
									} elseif ($row == "iupeks"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							} else{
								$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}
						}
					}
				}
				if(isset($advanced_filter["filter_iup"])){
					foreach($advanced_filter["filter_iup"] as $row1){
						if($row1 == "iupop"){
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
									}  elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
							}
						}elseif($row1 == "iupeks"){
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupeks2 < curdate()");
									} elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupeks2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}
						}else{
							if(isset($advanced_filter["filter_status"])){
								foreach($advanced_filter["filter_status"] as $row){
									if($row == "aktif"){
										$this->db->where("perusahaan.thn_iupop2 >= curdate()");
										$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
									} elseif ($row == "expired"){
										$this->db->where("perusahaan.thn_iupop2 < curdate()");
										$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
									} elseif ($row == "warning"){
										$this->db->where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
										$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
									}
								}
							}else{
								$this->db->where("perusahaan.thn_iupop2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 >= curdate()");
								$this->db->or_where("perusahaan.thn_iupop2 < curdate()");
								$this->db->or_where("perusahaan.thn_iupeks2 < curdate()");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupop2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupop2, curdate()) < 365)");
								$this->db->or_where("(DATEDIFF(perusahaan.thn_iupeks2, curdate()) > 0 and DATEDIFF(perusahaan.thn_iupeks2, curdate()) < 365)");
							}

						}
					}
				}

				if(isset($advanced_filter["filter_cnc"])){
					foreach($advanced_filter["filter_cnc"] as $row){
						$this->db->where("perusahaan.status_cnc", $row);
					}
				}

				if(isset($advanced_filter["filter_cabut"])){
					foreach($advanced_filter["filter_cabut"] as $row){
						if ($row == "layakcabut"){
							$this->db->where("perusahaan.thn_iupop2 < curdate()");
							$this->db->where("perusahaan.thn_iupeks2 < curdate()");
						}
						elseif($row == "sudahcabut"){
							$this->db->where("perusahaan.no_cabut != ''");
						}
					}
				}
			}
		}

        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }

        if ($search) {
            $this->db->where("perusahaan.nama_perusahaan like '%{$search}%'");
        }

        if ($filter == "iupop_aktip"){
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_exp"){
        	$this->db->where("perusahaan.no_iupop != ''");
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks_aktip"){
            $this->db->where("perusahaan.thn_iupeks2 >= curdate()");
        }
        elseif ($filter == "iupeks_exp"){
        	// $this->db->where("perusahaan.no_iupop","''");
        	$this->db->where("perusahaan.no_iupeks != ''");
            $this->db->where("perusahaan.thn_iupeks1", "0000-00-00");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "non cnc");
        }
        elseif ($filter == "cabut_layak"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cabut_sudah"){
            $this->db->where("perusahaan.no_cabut != ''");
        }

        if ($filter == "iupop_aktip_paser"){
            $this->db->where("perusahaan.kabupaten", "Paser");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_ppu"){
            $this->db->where("perusahaan.kabupaten", "Penajam Paser Utara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_balikpapan"){
            $this->db->where("perusahaan.kabupaten", "Balikpapan");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_samarinda"){
            $this->db->where("perusahaan.kabupaten", "Samarinda");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kukar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Kartanegara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kubar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Barat");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_mahulu"){
            $this->db->where("perusahaan.kabupaten", "Mahakam Ulu");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kutim"){
            $this->db->where("perusahaan.kabupaten", "Kutai Timur");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_bontang"){
            $this->db->where("perusahaan.kabupaten", "Bontang");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_berau"){
            $this->db->where("perusahaan.kabupaten", "Berau");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }

        $count = $this->db->count_all_results("perusahaan");
		file_put_contents("./test4.txt", $this->db->last_query());

        $this->db->close();

        return $count;
    }

    public function get_expire_total($filter, $acc_level)
    {
        $this->load->database();
		$result = 0;

        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }

        if ($filter == "iupop_aktip"){
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_exp"){
        	$this->db->where("perusahaan.no_iupop != ''");
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks_aktip"){
            $this->db->where("perusahaan.thn_iupeks2 >= curdate()");
        }
        elseif ($filter == "iupeks_exp"){
            // $this->db->where("perusahaan.no_iupop","''");
            $this->db->where("perusahaan.no_iupeks != ''");
            $this->db->where("perusahaan.thn_iupeks1", "0000-00-00");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "non cnc");
        }
        elseif ($filter == "cabut_layak"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cabut_sudah"){
            $this->db->where("perusahaan.no_cabut != ''");
        }

        if ($filter == "iupop_aktip_paser"){
            $this->db->where("perusahaan.kabupaten", "Paser");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_ppu"){
            $this->db->where("perusahaan.kabupaten", "Penajam Paser Utara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_balikpapan"){
            $this->db->where("perusahaan.kabupaten", "Balikpapan");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_samarinda"){
            $this->db->where("perusahaan.kabupaten", "Samarinda");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kukar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Kartanegara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kubar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Barat");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_mahulu"){
            $this->db->where("perusahaan.kabupaten", "Mahakam Ulu");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kutim"){
            $this->db->where("perusahaan.kabupaten", "Kutai Timur");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_bontang"){
            $this->db->where("perusahaan.kabupaten", "Bontang");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_berau"){
            $this->db->where("perusahaan.kabupaten", "Berau");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
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

        if ($acc_level != "semua") {
            $this->db->where("perusahaan.kabupaten like '%{$acc_level}'");
        }

        if ($filter == "iupop_aktip"){
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_exp"){
            $this->db->where("perusahaan.no_iupop != ''");
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
        }
        elseif ($filter == "iupeks_aktip"){
            $this->db->where("perusahaan.thn_iupeks2 >= curdate()");
        }
        elseif ($filter == "iupeks_exp"){
            // $this->db->where("perusahaan.no_iupop","''");
            $this->db->where("perusahaan.no_iupeks != ''");
            $this->db->where("perusahaan.thn_iupeks1", "0000-00-00");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cnc"){
            $this->db->where("perusahaan.status_cnc", "CNC");
        }
        elseif ($filter == "noncnc"){
            $this->db->where("perusahaan.status_cnc", "non cnc");
        }
        elseif ($filter == "cabut_layak"){
            $this->db->where("perusahaan.thn_iupop2 < curdate()");
            $this->db->where("perusahaan.thn_iupeks2 < curdate()");
        }
        elseif ($filter == "cabut_sudah"){
            $this->db->where("perusahaan.no_cabut != ''");
        }

        if ($filter == "iupop_aktip_paser"){
            $this->db->where("perusahaan.kabupaten", "Paser");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_ppu"){
            $this->db->where("perusahaan.kabupaten", "Penajam Paser Utara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_balikpapan"){
            $this->db->where("perusahaan.kabupaten", "Balikpapan");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_samarinda"){
            $this->db->where("perusahaan.kabupaten", "Samarinda");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kukar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Kartanegara");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kubar"){
            $this->db->where("perusahaan.kabupaten", "Kutai Barat");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_mahulu"){
            $this->db->where("perusahaan.kabupaten", "Mahakam Ulu");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_kutim"){
            $this->db->where("perusahaan.kabupaten", "Kutai Timur");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_bontang"){
            $this->db->where("perusahaan.kabupaten", "Bontang");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
        }
        elseif ($filter == "iupop_aktip_berau"){
            $this->db->where("perusahaan.kabupaten", "Berau");
            $this->db->where("perusahaan.thn_iupop2 >= curdate()");
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
