<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_app_auth");
        $this->load->model("m_app_logs");
        $this->load->model("m_perusahaan");
        $this->load->model("m_iuran");
        $this->load->model("m_royalti");
        $this->load->model("m_jamkes");
        $this->load->model("m_jamrek_eks");
        $this->load->model("m_jamrek_pro");
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
        $filter = $this->input->post_get("filter");

        $id = $this->input->post("id");
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $total = $this->m_perusahaan->count_data($search, $acc_level, $filter);
            $result = $this->m_perusahaan->load_data($page, $perpage, $order_by, $search, $acc_level, $filter);
            $data = array(
                "draw" => 0,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $result
            );
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/dt_show",
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

    public function get_expire_total()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");

        $acc_level = $this->input->post_get("acc_level");
        $jenis = $this->input->post_get("jenis");
        
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $data = $this->m_perusahaan->get_expire_total($jenis, $acc_level);
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/get_expire_total",
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

    public function get_data()
    {
        header("Content-type: application/json");

        $app_id = $this->input->get("app_id");
        $app_token = $this->input->get("app_token");
        
        $filter = $this->input->post_get("filter");
        $acc_level = $this->input->post_get("acc_level");

        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $status = True;
            $message = "Showing data";
            $data = $this->m_perusahaan->get_data($filter, $acc_level);
            $i = 0;
            foreach($data as $row){
                $id_perusahaan = $row["id"];
                $data[$i]["jaminan"] = array();
                $data[$i]["jaminan"]["iuran"] = $this->m_iuran->get_data_by_perusahaan($id_perusahaan);
                $data[$i]["jaminan"]["royalti"] = $this->m_royalti->get_data_by_perusahaan($id_perusahaan);
                $data[$i]["jaminan"]["jamkes"] = $this->m_jamkes->get_data_by_perusahaan($id_perusahaan);
                $data[$i]["jaminan"]["jamrek_eks"] = $this->m_jamrek_eks->get_data_by_perusahaan($id_perusahaan);
                $data[$i]["jaminan"]["jamrek_pro"] = $this->m_jamrek_pro->get_data_by_perusahaan($id_perusahaan);
                $data[$i]["jaminan"]["jamtup"] = $this->m_jamtup->get_data_by_perusahaan($id_perusahaan);
                $i++;
            }
        
            // $log_activity = array(
                // "app_id" => $app_id,
                // "route" => "perusahaan/get_one",
                // "created" => date("Y-m-d H:i:s"),
                // "action" => "r",
                // "activity" => json_encode($data)
            // );
            // $this->m_app_logs->insert($log_activity);
        
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
            $data = $this->m_perusahaan->get_one($id);
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/get_one",
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
            $exist = $this->m_perusahaan->is_exist($id);
            if ( ! $exist ) {
                $data = array(
                    "nama_perusahaan" => $this->input->post("nama_perusahaan"),
                    "status" => $this->input->post("status"),
                    "nama_dirut" => $this->input->post("nama_dirut"),
                    "nama_saham" => $this->input->post("nama_saham"),
                    "alamat_kantor" => $this->input->post("alamat_kantor"),
                    "telp_perusahaan" => $this->input->post("telp_perusahaan"),
                    "telp_dirut" => $this->input->post("telp_dirut"),
                    "telp_saham" => $this->input->post("telp_saham"),
                    "email_kantor" => $this->input->post("email_kantor"),
                    "email_dirut" => $this->input->post("email_dirut"),
                    "email_saham" => $this->input->post("email_saham"),
                    "nama_ktt" => $this->input->post("nama_ktt"),
                    "hp_ktt" => $this->input->post("hp_ktt"),
                    "email_ktt" => $this->input->post("email_ktt"),
                    "sk_ktt" => $this->input->post("sk_ktt"),
                    "ktt_terbit" => $this->input->post("ktt_terbit"),
                    "ktt_berakhir" => $this->input->post("ktt_berakhir"),
                    "status_cnc" => $this->input->post("status_cnc"),
                    "tahap_cnc" => $this->input->post("tahap_cnc"),
                    "komoditas" => $this->input->post("komoditas"),
                    "komoditas_sama" => $this->input->post("komoditas_sama"),
                    "wadm" => $this->input->post("wadm"),
                    "wpa" => $this->input->post("wpa"),
                    "kfg" => $this->input->post("kfg"),
                    "kfi1" => $this->input->post("kfi1"),
                    "kfi2" => $this->input->post("kfi2"),
                    "kelurahan" => $this->input->post("kelurahan"),
                    "kecamatan" => $this->input->post("kecamatan"),
                    "kabupaten" => $this->input->post("kabupaten"),
                    "propinsi" => $this->input->post("propinsi"),
                    "no_jamtup" => $this->input->post("no_jamtup"),
                    "tereka" => $this->input->post("tereka"),
                    "terunjuk" => $this->input->post("terunjuk"),
                    "terukur" => $this->input->post("terukur"),
                    "terkira" => $this->input->post("terkira"),
                    "terbukti" => $this->input->post("terbukti"),
                    "jenis_penjualan" => $this->input->post("jenis_penjualan"),
                    "jumlah_penjualan" => $this->input->post("jumlah_penjualan"),
                    "moisture" => $this->input->post("moisture"),
                    "sulphur" => $this->input->post("sulphur"),
                    "ash" => $this->input->post("ash"),
                    "calori" => $this->input->post("calori"),
                    "ket" => $this->input->post("ket"),
                    "no_tinjau" => $this->input->post("no_tinjau"),
                    "luas_tinjau" => $this->input->post("luas_tinjau"),
                    "thn_tinjau1" => $this->input->post("thn_tinjau1"),
                    "thn_tinjau2" => $this->input->post("thn_tinjau2"),
                    "no_pu" => $this->input->post("no_pu"),
                    "luas_pu" => $this->input->post("luas_pu"),
                    "thn_pu1" => $this->input->post("thn_pu1"),
                    "thn_pu2" => $this->input->post("thn_pu2"),
                    "no_kpeks" => $this->input->post("no_kpeks"),
                    "luas_kpeks" => $this->input->post("luas_kpeks"),
                    "thn_kpeks1" => $this->input->post("thn_kpeks1"),
                    "thn_kpeks2" => $this->input->post("thn_kpeks2"),
                    "no_iupeks" => $this->input->post("no_iupeks"),
                    "luas_iupeks" => $this->input->post("luas_iupeks"),
                    "thn_iupeks1" => $this->input->post("thn_iupeks1"),
                    "thn_iupeks2" => $this->input->post("thn_iupeks2"),
                    "no_kpeksp" => $this->input->post("no_kpeksp"),
                    "luas_kpeksp" => $this->input->post("luas_kpeksp"),
                    "thn_kpeksp1" => $this->input->post("thn_kpeksp1"),
                    "thn_kpeksp2" => $this->input->post("thn_kpeksp2"),
                    "no_iupjual" => $this->input->post("no_iupjual"),
                    "luas_iupjual" => $this->input->post("luas_iupjual"),
                    "thn_iupjual1" => $this->input->post("thn_iupjual1"),
                    "thn_iupjual2" => $this->input->post("thn_iupjual2"),
                    "no_iupop" => $this->input->post("no_iupop"),
                    "luas_iupop" => $this->input->post("luas_iupop"),
                    "thn_iupop1" => $this->input->post("thn_iupop1"),
                    "thn_iupop2" => $this->input->post("thn_iupop2"),
                    "no_akta" => $this->input->post("no_akta"),
                    "no_npwp" => $this->input->post("no_npwp"),
                    "no_domisili" => $this->input->post("no_domisili"),
                    "no_tdper" => $this->input->post("no_tdper"),
                    "no_pajak" => $this->input->post("no_pajak"),
                    "no_kenapajak" => $this->input->post("no_kenapajak"),
                    "no_dagang" => $this->input->post("no_dagang"),
                    "no_ho" => $this->input->post("no_ho"),
                    "no_lingkungan" => $this->input->post("no_lingkungan"),
                    "no_kelayakan" => $this->input->post("no_kelayakan"),
                    "no_limbah" => $this->input->post("no_limbah"),
                    "no_air" => $this->input->post("no_air"),
                    "no_manfaatbbc" => $this->input->post("no_manfaatbbc"),
                    "no_bbc" => $this->input->post("no_bbc"),
                    "no_gudang" => $this->input->post("no_gudang"),
                    "no_handak" => $this->input->post("no_handak"),
                    "no_setling" => $this->input->post("no_setling"),
                    "no_kawasan" => $this->input->post("no_kawasan"),
                    "no_izinlj" => $this->input->post("no_izinlj"),
                    "no_ujp" => $this->input->post("no_ujp"),
                    "no_pelabuhan" => $this->input->post("no_pelabuhan"),
                    "no_campur" => $this->input->post("no_campur"),
                    "no_opkhusus" => $this->input->post("no_opkhusus"),
                    "no_cnc" => $this->input->post("no_cnc"),
                    "no_eksport" => $this->input->post("no_eksport"),
                    "no_fs" => $this->input->post("no_fs"),
                    "no_amdal" => $this->input->post("no_amdal"),
                    "no_rr" => $this->input->post("no_rr"),
                    "no_rp" => $this->input->post("no_rp"),
                    "no_rkttl" => $this->input->post("no_rkttl"),
                    "no_rkab" => $this->input->post("no_rkab"),
                    "no_jamrekeks" => $this->input->post("no_jamrekeks"),
                    "no_jamrekpro" => $this->input->post("no_jamrekpro"),
                    "no_jaminankesungguhan" => $this->input->post("no_jaminankesungguhan"),
                    "no_royalti" => $this->input->post("no_royalti"),
                    "no_iuran" => $this->input->post("no_iuran")
                );
                $exe = $this->m_perusahaan->insert($data);
                if ( $exe ) {
                    $status = True;
                    $message = "Insert Perusahaan Success";
                    $data["id"] = $this->m_perusahaan->get_last_id();
                }
            } else {
                $status = False; $message = "Data already exist";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/insert",
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
        $data = array();
        $valid = $this->m_app_auth->is_valid($app_id, $app_token);
        if ( $valid ) {
            $prev_data = $this->m_perusahaan->get_one($id);
            $data = array(
                "nama_perusahaan" => $this->input->post("nama_perusahaan"),
                "status" => $this->input->post("status"),
                "nama_dirut" => $this->input->post("nama_dirut"),
                "nama_saham" => $this->input->post("nama_saham"),
                "alamat_kantor" => $this->input->post("alamat_kantor"),
                "telp_perusahaan" => $this->input->post("telp_perusahaan"),
                "telp_dirut" => $this->input->post("telp_dirut"),
                "telp_saham" => $this->input->post("telp_saham"),
                "email_kantor" => $this->input->post("email_kantor"),
                "email_dirut" => $this->input->post("email_dirut"),
                "email_saham" => $this->input->post("email_saham"),
                "nama_ktt" => $this->input->post("nama_ktt"),
                "hp_ktt" => $this->input->post("hp_ktt"),
                "email_ktt" => $this->input->post("email_ktt"),
                "sk_ktt" => $this->input->post("sk_ktt"),
                "ktt_terbit" => $this->input->post("ktt_terbit"),
                "ktt_berakhir" => $this->input->post("ktt_berakhir"),
                "status_cnc" => $this->input->post("status_cnc"),
                "tahap_cnc" => $this->input->post("tahap_cnc"),
                "komoditas" => $this->input->post("komoditas"),
                "komoditas_sama" => $this->input->post("komoditas_sama"),
                "wadm" => $this->input->post("wadm"),
                "wpa" => $this->input->post("wpa"),
                "kfg" => $this->input->post("kfg"),
                "kfi1" => $this->input->post("kfi1"),
                "kfi2" => $this->input->post("kfi2"),
                "kelurahan" => $this->input->post("kelurahan"),
                "kecamatan" => $this->input->post("kecamatan"),
                "kabupaten" => $this->input->post("kabupaten"),
                "propinsi" => $this->input->post("propinsi"),
                "no_jamtup" => $this->input->post("no_jamtup"),
                "tereka" => $this->input->post("tereka"),
                "terunjuk" => $this->input->post("terunjuk"),
                "terukur" => $this->input->post("terukur"),
                "terkira" => $this->input->post("terkira"),
                "terbukti" => $this->input->post("terbukti"),
                "jenis_penjualan" => $this->input->post("jenis_penjualan"),
                "jumlah_penjualan" => $this->input->post("jumlah_penjualan"),
                "moisture" => $this->input->post("moisture"),
                "sulphur" => $this->input->post("sulphur"),
                "ash" => $this->input->post("ash"),
                "calori" => $this->input->post("calori"),
                "ket" => $this->input->post("ket"),
                "no_tinjau" => $this->input->post("no_tinjau"),
                "luas_tinjau" => $this->input->post("luas_tinjau"),
                "thn_tinjau1" => $this->input->post("thn_tinjau1"),
                "thn_tinjau2" => $this->input->post("thn_tinjau2"),
                "no_pu" => $this->input->post("no_pu"),
                "luas_pu" => $this->input->post("luas_pu"),
                "thn_pu1" => $this->input->post("thn_pu1"),
                "thn_pu2" => $this->input->post("thn_pu2"),
                "no_kpeks" => $this->input->post("no_kpeks"),
                "luas_kpeks" => $this->input->post("luas_kpeks"),
                "thn_kpeks1" => $this->input->post("thn_kpeks1"),
                "thn_kpeks2" => $this->input->post("thn_kpeks2"),
                "no_iupeks" => $this->input->post("no_iupeks"),
                "luas_iupeks" => $this->input->post("luas_iupeks"),
                "thn_iupeks1" => $this->input->post("thn_iupeks1"),
                "thn_iupeks2" => $this->input->post("thn_iupeks2"),
                "no_kpeksp" => $this->input->post("no_kpeksp"),
                "luas_kpeksp" => $this->input->post("luas_kpeksp"),
                "thn_kpeksp1" => $this->input->post("thn_kpeksp1"),
                "thn_kpeksp2" => $this->input->post("thn_kpeksp2"),
                "no_iupjual" => $this->input->post("no_iupjual"),
                "luas_iupjual" => $this->input->post("luas_iupjual"),
                "thn_iupjual1" => $this->input->post("thn_iupjual1"),
                "thn_iupjual2" => $this->input->post("thn_iupjual2"),
                "no_iupop" => $this->input->post("no_iupop"),
                "luas_iupop" => $this->input->post("luas_iupop"),
                "thn_iupop1" => $this->input->post("thn_iupop1"),
                "thn_iupop2" => $this->input->post("thn_iupop2"),
                "no_akta" => $this->input->post("no_akta"),
                "no_npwp" => $this->input->post("no_npwp"),
                "no_domisili" => $this->input->post("no_domisili"),
                "no_tdper" => $this->input->post("no_tdper"),
                "no_pajak" => $this->input->post("no_pajak"),
                "no_kenapajak" => $this->input->post("no_kenapajak"),
                "no_dagang" => $this->input->post("no_dagang"),
                "no_ho" => $this->input->post("no_ho"),
                "no_lingkungan" => $this->input->post("no_lingkungan"),
                "no_kelayakan" => $this->input->post("no_kelayakan"),
                "no_limbah" => $this->input->post("no_limbah"),
                "no_air" => $this->input->post("no_air"),
                "no_manfaatbbc" => $this->input->post("no_manfaatbbc"),
                "no_bbc" => $this->input->post("no_bbc"),
                "no_gudang" => $this->input->post("no_gudang"),
                "no_handak" => $this->input->post("no_handak"),
                "no_setling" => $this->input->post("no_setling"),
                "no_kawasan" => $this->input->post("no_kawasan"),
                "no_izinlj" => $this->input->post("no_izinlj"),
                "no_ujp" => $this->input->post("no_ujp"),
                "no_pelabuhan" => $this->input->post("no_pelabuhan"),
                "no_campur" => $this->input->post("no_campur"),
                "no_opkhusus" => $this->input->post("no_opkhusus"),
                "no_cnc" => $this->input->post("no_cnc"),
                "no_eksport" => $this->input->post("no_eksport"),
                "no_fs" => $this->input->post("no_fs"),
                "no_amdal" => $this->input->post("no_amdal"),
                "no_rr" => $this->input->post("no_rr"),
                "no_rp" => $this->input->post("no_rp"),
                "no_rkttl" => $this->input->post("no_rkttl"),
                "no_rkab" => $this->input->post("no_rkab"),
                "no_jamrekeks" => $this->input->post("no_jamrekeks"),
                "no_jamrekpro" => $this->input->post("no_jamrekpro"),
                "no_jaminankesungguhan" => $this->input->post("no_jaminankesungguhan"),
                "no_royalti" => $this->input->post("no_royalti"),
                "no_iuran" => $this->input->post("no_iuran")
            );
            $exe = $this->m_perusahaan->update($data, $id);
            if ( $exe ) {
                $status = True;
                $message = "Update Perusahaan Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/update",
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
            $data = $this->m_perusahaan->get_one($id);
            $exe = $this->m_perusahaan->delete($id);
            if ( $exe ) {
                $status = True;
                $message = "Delete Perusahaan Success";
            }
        
            $log_activity = array(
                "app_id" => $app_id,
                "route" => "perusahaan/delete",
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
