<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones extends CI_Controller {

	var $usuario_id;

	public function __construct() {
		
		parent::__construct();

		$this -> load -> model('promociones_model', 'obj_model');
		
		$this -> load -> library('form_validation');

		$session_data = $this -> session -> userdata('logged_in');

		$this -> usuario_id = $session_data['id'];
	}

	public function index() {
		$this -> load -> helper('url');

		$this -> obj_model -> delete_items_huerfanos();

		$msg['productos_nombres'] = $this->get_name_all_products();
	
		$msg['output'] = $this -> load -> view('promociones/view', $msg, true);

		$this -> load -> view('dashboard', $msg);
	}
	
	public function delete_items_huerfanos(){
		$this -> obj_model -> delete_items_huerfanos();
		echo json_encode(1);
	}
	public function get_items($id='') {

		if($id==''){
			
			 $list = $this -> obj_model -> get_items($this -> usuario_id);
		}
		else{
			 $list = $this -> obj_model -> get_items_by_promocion($id, $this -> usuario_id);
		} 
		
		$data = array();
		
		$no = $_POST['start'];
		
		foreach ($list as $obj) {
			$no++;
			$row = array();

			foreach ($obj as $key => $value) {
				if ($key != 'id')
					$row[] = $obj -> $key;
			}
			//add html for action
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_item(' . "'" . $obj -> id . "'" . ',' . "'" . $obj -> precio_final . "'" . ')" style="padding:0px;">Eliminar</a>';

			$data[] = $row;
		}

		$output = array("draw" => $_POST['draw'], "recordsTotal" => 0, "recordsFiltered" => 0, "data" => $data, );
		//output to json format
		echo json_encode($output);
	}

	public function get_promociones() {

		$list = $this -> obj_model -> get_promociones();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $obj) {
			$no++;
			$row = array();

			foreach ($obj as $key => $value) {
				if ($key != 'id')
					$row[] = $obj -> $key;
			}
			//add html for action
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()"  onclick="edit_obj(' . "'" . $obj -> id . "'" . ')" title="Editar"><i class="glyphicon glyphicon-pencil" title="Editar"></i></a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()" onclick="delete_obj(' . "'" . $obj -> id . "'" . ')"  title="Eliminar"><i class="glyphicon glyphicon-trash"  title="Eliminar"></i></a>';
	
			$data[] = $row;
		}

		$output = array("draw" => $_POST['draw'], "recordsTotal" => $this -> obj_model -> count_all(), "recordsFiltered" => $this -> obj_model -> count_filtered(), "data" => $data, );
		//output to json format
		echo json_encode($output);
	}

	public function delete_item($id) {
		$this -> obj_model -> delete_item_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	public function ajax_delete($id) {
		$this -> obj_model -> delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	public function ajax_edit($id) {
		$data = $this -> obj_model -> get_by_id($id);
		echo json_encode($data);
	}
	public function save_promocion() {

		$this -> set_error_msg();

		$this -> form_validation -> set_rules('codigo_promocion', 'Codigo', 'required|xss_clean|trim|is_unique[promociones.codigo]');
		$this -> form_validation -> set_rules('nombre_promocion', 'Nombre', 'required|xss_clean|trim|max_length[200]');
		$this -> form_validation -> set_rules('fecha_inicio', 'Fecha inicio', 'required|xss_clean|trim|max_length[20]');
		$this -> form_validation -> set_rules('fecha_fin', 'Fecha fin', 'required|xss_clean|trim|max_length[20]');
		$this -> form_validation -> set_rules('monto_final', 'Monto final', 'trim|required|numeric|xss_clean|greater_than[-1]');
		$this -> form_validation -> set_rules('descuento', 'Descuento', 'trim|required|numeric|xss_clean|greater_than[-1]');
		$this -> form_validation -> set_rules('monto_original', 'Monto original', 'trim|required|numeric|xss_clean|greater_than[-1]');
		

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
				
			$data = array('codigo' => $this -> input -> post('codigo_promocion'),
						  'nombre' => $this -> input -> post('nombre_promocion'), 
						  'fecha_inicio' => $this -> input -> post('fecha_inicio'), 
						  'fecha_fin' => $this -> input -> post('fecha_fin'), 
						  'monto_final' => $this -> input -> post('monto_final'),
						  'descuento' => $this -> input -> post('descuento'),
						  'monto_original' => $this -> input -> post('monto_original'), 
						  'usuarios_id' => $this -> usuario_id, 'fecha_insert' => date('Y-m-d H:i:s'));
			
			$insert = $this -> obj_model -> save_promocion($data);

			echo json_encode(array("status" => $insert));
		}
	}

	public function update_promocion() {

		$this -> set_error_msg();

		$original_value = $this -> db -> query("SELECT codigo FROM promociones WHERE id = " . $this -> input -> post('id')) -> row() -> codigo;
		if ($this -> input -> post('codigo_promocion') != $original_value) {
			$is_unique = '|is_unique[promociones.codigo]';
		} else {
			$is_unique = '';
		}
		
		$this -> form_validation -> set_rules('codigo_promocion', 'Codigo', 'required|xss_clean|trim|' . $is_unique);
		$this -> form_validation -> set_rules('nombre_promocion', 'Nombre', 'required|xss_clean|trim|max_length[200]');
		$this -> form_validation -> set_rules('fecha_inicio', 'Fecha inicio', 'required|xss_clean|trim|max_length[20]');
		$this -> form_validation -> set_rules('fecha_fin', 'Fecha fin', 'required|xss_clean|trim|max_length[20]');
		$this -> form_validation -> set_rules('monto_final', 'Monto final', 'trim|required|numeric|xss_clean|greater_than[-1]');
		$this -> form_validation -> set_rules('descuento', 'Descuento', 'trim|required|numeric|xss_clean|greater_than[-1]');
		$this -> form_validation -> set_rules('monto_original', 'Monto original', 'trim|required|numeric|xss_clean|greater_than[-1]');
		

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
				
			$data = array('codigo' => 	$this -> input -> post('codigo_promocion'),
						  'nombre' => $this -> input -> post('nombre_promocion'), 
						  'fecha_inicio' => $this -> input -> post('fecha_inicio'), 
						  'fecha_fin' => $this -> input -> post('fecha_fin'), 
						  'monto_final' => $this -> input -> post('monto_final'),
						  'descuento' => $this -> input -> post('descuento'),
						  'monto_original' => $this -> input -> post('monto_original'), 
						  'usuarios_id' => $this -> usuario_id, 'fecha_insert' => date('Y-m-d H:i:s')
						 );
			
			$insert = $this -> obj_model -> update_promocion(array('id' => $this -> input -> post('id')), $data, $this -> input -> post('id'));
			
			//$insert = $this -> obj_model -> update_promocion($data);

			echo json_encode(array("status" => $insert));
		}
	}
	

	public function save_item($idpromocion='') {

		$this -> set_error_msg();

		$this -> form_validation -> set_rules('codigo', 'Codigo', 'required|xss_clean|trim');
		$this -> form_validation -> set_rules('cantidad', 'Cantidad', 'required|xss_clean|trim|integer|greater_than[0]');
		$this -> form_validation -> set_rules('precio', 'Precio', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('total_item', 'Total', 'trim|required|numeric|xss_clean');

		if($idpromocion ==''){
			$promo = -9999;	
		}else{
			$promo = $idpromocion;
		}

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
			$data = array('codigo' => $this -> input -> post('codigo'), 
			'nombre' => $this -> input -> post('nombre'), 
			'cantidad' => $this -> input -> post('cantidad'),
			 'idproducto' => $this -> input -> post('idproducto'), 
			 'precio' => $this -> input -> post('precio'), 
			 'precio_final' => $this -> input -> post('total_item'), 
			 'idpromocion' => -9999, 
			 'usuarios_id' => $this -> usuario_id, 
			 'fecha_insert' => date('Y-m-d H:i:s')
			 );
			$insert = $this -> obj_model -> save_item($data);
			echo json_encode(array("status" => TRUE));
		}
	}

	private function set_error_msg() {
		$this -> form_validation -> set_message('numeric', 'El campo %s debe ser un numero valido');
		$this -> form_validation -> set_message('integer', 'El campo %s debe ser un numero entero');
		$this -> form_validation -> set_message('greater_than', 'El campo %s debe ser un positivo');
	}
	public function get_all_products() {

		$res = $this -> obj_model -> get_all_products();

		echo json_encode($res);
	}
	public function get_name_all_products() {

		$res = $this -> obj_model -> get_name_all_products();

		return $res;
	}
}
