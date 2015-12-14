<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Turnos extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $msg['output'] = $this->load->view('admin/turnos', '', true);

        $this->load->view('admin/dashboard', $msg);
    }

    function _example_output($output = null) {
        
    }

    function save_evento() {
       
        $tmp = json_decode($_POST['myData']);
        
        $data = array();
        
        $data['text'] = $tmp->text;
        
        $data['start_date'] = str_replace('.000Z','',str_replace('T', ' ', $tmp->start_date));     
        
        $data['end_date'] = str_replace('.000Z','',str_replace('T', ' ', $tmp->end_date));
        
        $data['tipo_turnos_id'] = 1;
        
        $data['clientes_id'] = 1;

        $data['fecha_insert'] = date('Y-m-dd H:i:s');
        
        $this -> db -> insert('turnos', $data);

        echo json_encode('{
            status : 200,
            response:{
                ' . implode(",", $_POST) . '
             }
          )');
    }

}

?>