<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('proveedores_model', 'obj_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->helper('url');

        $msg['output'] = $this->load->view('proveedores/view', '', true);

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
        $this->form_validation->set_rules('cuit', 'CUIT', 'required|trim|numeric|xss_clean');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required|xss_clean|trim');
       // $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        $this->form_validation->set_rules('telefono', 'Telefono', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('provincia', 'Provincia', 'xss_clean|trim');
        $this->form_validation->set_rules('localidad', 'Localidad', 'xss_clean|trim');
        $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|trim');
        
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'cuit' => $this->input->post('cuit'),
                'localidad' => $this->input->post('localidad'),
                'provincia' => $this->input->post('provincia'),
                'cp' => $this->input->post('cp'),
                'direccion' => $this->input->post('direccion'),
                'email' => $this->input->post('email'),
                'telefono' => $this->input->post('telefono'),
                'observaciones' => $this->input->post('observaciones'),
                'fecha_insert' => date('Y-m-d H:i:s')
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_update() {
        
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean|trim');
        $this->form_validation->set_rules('cuit', 'CUIT', 'required|numeric|trim');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required|xss_clean|trim');
       // $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        $this->form_validation->set_rules('telefono', 'Telefono', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('provincia', 'Provincia', 'xss_clean|trim');
        $this->form_validation->set_rules('localidad', 'Localidad', 'xss_clean|trim');
        $this->form_validation->set_rules('observaciones', 'Observaciones', 'xss_clean|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
        
            $data = array(
                'nombre' => $this->input->post('nombre'),
                'cuit' => $this->input->post('cuit'),
                'localidad' => $this->input->post('localidad'),
                'provincia' => $this->input->post('provincia'),
                'cp' => $this->input->post('cp'),
                'direccion' => $this->input->post('direccion'),
                'email' => $this->input->post('email'),
                'telefono' => $this->input->post('telefono'),
                'observaciones' => $this->input->post('observaciones'),
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
    public function descargar_excel(){
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="proveedores_'.date("Y-m-d").'.xls"');
		header('Cache-Control: max-age=0');
		
		$_POST['start'] = 1;
		
		$_POST['length'] = 500000;
		
		$_POST['search']['value'] ='';
		
		$list = $this -> obj_model -> get_datatables();
		
		$table = '<table style=""><tr style="background:#5193d6; text-align:center;font-size:14px;font-family:Arial;font-weight:bold;color:#fff;">';

		$data ='';
		
		$i0 = 0;
		
		foreach ($list as $obj) {
			
			$data .='<tr style="">';
			
			foreach ($obj as $key => $value) {
				
				if(($i0==0)&&(($key != 'id'))) $table .='<td>'.strtoupper($key).'</td>';
				
				if ($key != 'id'){
					
					$data .= '<td style="vertical-align:middle;	border:1px solid #000000;border-width:0px 1px 1px 0px;text-align:left;padding:7px;	font-size:13px;	font-family:Arial;	font-weight:normal;	color:#000000;">'.$value.'</td>';
				}
			}
			$i0 = 1;
			$table .='</tr>';
			$data .= '</tr>';
		}
		$table .=$data.'</table>';
		echo $table;
	}
}
