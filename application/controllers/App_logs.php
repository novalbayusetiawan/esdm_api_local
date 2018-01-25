<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_logs extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("m_logs");
    }
}
