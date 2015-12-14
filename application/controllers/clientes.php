<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('grocery_CRUD');
    }

    public function index() {
        $crud = new grocery_CRUD();

        $crud->set_theme('datatables');

        $crud->set_table('clientes')->set_subject('Clientes')->columns('nombre');

        $crud->required_fields('nombre');

        $crud->unique_fields('nombre');

        $crud->unset_fields('fecharegistro');

        $crud->unset_print();

        $crud->set_field_upload('imagen', 'assets/uploads/files');

        $output = $crud->render();

        $this->_example_output($output);
    }

    function _example_output($output = null) {
        $this->load->view('admin/dashboard.php', $output);
    }

}

?>