<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper('url');

        $msg['output'] = $this->load->view('control', '', true);

        $this->load->view('dashboard', $msg);
    }

}
