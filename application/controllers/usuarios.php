<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('usuarios_model', 'obj_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->helper('url');

        $msg['roles'] = $this->get_roles();

        $msg['output'] = $this->load->view('usuarios/view', $msg, true);

        $this->load->view('dashboard', $msg);
    }

    public function ajax_list() {
        $list = $this->obj_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $obj) {
            $no++;
            $row = array();

            foreach ($obj as $key => $value) {
                if ($key != 'id')
                    $row[] = $obj->$key;
            }
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
        $this->form_validation->set_rules('apellido', 'Apellido', 'required|xss_clean|trim');
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|is_unique[usuarios.usuario]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5|xss_clean');
        $this->form_validation->set_rules('roles_id', 'Rol', 'required|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'apellido' => $this->input->post('apellido'),
                'usuario' => $this->input->post('usuario'),
                'password' => $this->input->post('password'),
                'roles_id' => $this->input->post('roles_id'),
                'email' => $this->input->post('email'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {

        $original_value = $this->db->query("SELECT usuario FROM usuarios WHERE id = " . $this->input->post('id'))->row()->usuario;
        if ($this->input->post('usuario') != $original_value) {
            $is_unique = '|is_unique[usuarios.usuario]';
        } else {
            $is_unique = '';
        }

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('apellido', 'Apellido', 'required|xss_clean|trim');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        if ($this->input->post('password') != '')
            $this->form_validation->set_rules('password', 'Email', 'trim|required|md5|xss_clean');
        $this->form_validation->set_rules('roles_id', 'Rol', 'required|xss_clean|trim');
        $this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|xss_clean|' . $is_unique);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                'nombre' => $this->input->post('nombre'),
                'apellido' => $this->input->post('apellido'),
                'usuario' => $this->input->post('usuario'),
                'roles_id' => $this->input->post('roles_id'),
                'email' => $this->input->post('email'),
                'fecha_update' => date('Y-m-d H:i:s')
            );
            if ($this->input->post('password') != '')
                $data['password'] = $this->input->post('password');
            $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_delete($id) {
        $this->obj_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function get_roles() {

        $query = $this->db->get_where('roles', array('borrado' => 'no'));

        $options = array();

        foreach ($query->result() as $row) {
            $options[$row->id] = $row->nombre;
        }
        return $options;
    }

}
