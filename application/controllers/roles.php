<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('roles_model', 'obj_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->helper('url');

        $msg['output'] = $this->load->view('roles/view', '', true);

        $this->load->view('dashboard', $msg);
    }

    public function ajax_list() {
        $list = $this->obj_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $obj) {
            $no++;
            $row = array();
            $row[] = $obj->nombre;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_obj(' . "'" . $obj->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_obj(' . "'" . $obj->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->obj_model->count_all(),
            "recordsFiltered" => $this->obj_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->obj_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
        
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_delete($id) {
        $this->obj_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}
