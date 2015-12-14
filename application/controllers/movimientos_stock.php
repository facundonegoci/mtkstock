<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos_stock extends CI_Controller {

	var $usuario_id;

	public function __construct() {
		parent::__construct();

		$this -> load -> model('movimientos_stock_model', 'obj_model');
		$this -> load -> library('form_validation');

		$session_data = $this -> session -> userdata('logged_in');

		$this -> usuario_id = $session_data['id'];
	}

	public function delete_items_huerfanos(){
		$this -> obj_model -> delete_items_huerfanos();
		echo json_encode(1);
	}
	public function get_items() {

		$list = $this -> obj_model -> get_items($this -> usuario_id);
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
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_item(' . "'" . $obj -> id . "'" . ',' . "'" . $obj -> precio_total . "'" . ')" style="padding:0px;">Eliminar</a>';

			$data[] = $row;
		}

		$output = array("draw" => $_POST['draw'], "recordsTotal" => 0, "recordsFiltered" => 0, "data" => $data, );
		//output to json format
		echo json_encode($output);
	}

	public function get_movimientos() {

		$list = $this -> obj_model -> get_movimientos();
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
			$row[] = '<!--<a class="btn btn-sm btn-primary" href="javascript:void()"  onclick="edit_obj(' . "'" . $obj -> id . "'" . ')" title="Editar"><i class="glyphicon glyphicon-pencil" title="Editar"></i></a>-->
                  <a class="btn btn-sm btn-danger" href="javascript:void()" onclick="delete_movimiento_obj(' . "'" . $obj -> id . "'" . ')"  title="Eliminar"><i class="glyphicon glyphicon-trash"  title="Eliminar"></i></a>';
			
			
			//$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_item(' . "'" . $obj -> id . "'" . ')" style="padding:0px;">Eliminar</a>';

			$data[] = $row;
		}

		$output = array("draw" => $_POST['draw'], "recordsTotal" => $this -> obj_model -> count_all_movimientos(), "recordsFiltered" => $this -> obj_model -> count_filtered_movimientos(), "data" => $data, );
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
	public function actualizar_stock() {

		$this -> set_error_msg();

		$this -> form_validation -> set_rules('fecha', 'Fecha', 'required|xss_clean|trim');
		$this -> form_validation -> set_rules('numero_factura', 'N° Factura', 'required|xss_clean|trim|max_length[45]');
		$this -> form_validation -> set_rules('monto', 'Monto', 'trim|required|numeric|xss_clean|greater_than[-1]');
		$this -> form_validation -> set_rules('proveedor_s', 'Proveedor', 'trim|required|integer|xss_clean');

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
			$data = array('fecha' => $this -> input -> post('fecha'), 'numero_factura' => $this -> input -> post('numero_factura'), 'monto' => $this -> input -> post('monto'), 'proveedores_id' => $this -> input -> post('proveedor_s'), 
			'usuarios_id' => $this -> usuario_id, 'fecha_insert' => date('Y-m-d H:i:s'), 
			'tipo' =>  $this -> input -> post('tipo'));
			$insert = $this -> obj_model -> save_actualizacion($data);

			echo json_encode(array("status" => $insert));
		}
	}

	public function save_item() {

		$this -> set_error_msg();

		$this -> form_validation -> set_rules('codigo_s', 'Codigo', 'required|xss_clean|trim');
		$this -> form_validation -> set_rules('cantidad', 'Cantidad', 'required|xss_clean|trim|integer|greater_than[0]');
		$this -> form_validation -> set_rules('precio_s', 'Precio', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('total_item', 'Total', 'trim|required|numeric|xss_clean');

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
			$data = array('codigo' => $this -> input -> post('codigo_s'), 'nombre' => $this -> input -> post('nombre_s'), 'cantidad' => $this -> input -> post('cantidad'), 'productos_id' => $this -> input -> post('idproducto'), 'precio_anterior' => $this -> input -> post('precioa_s'), 'precio_unitario' => $this -> input -> post('precio_s'), 'stock_anterior' => $this -> input -> post('stocka_s'), 'precio_total' => $this -> input -> post('total_item'), 'movimiento_stock_id' => -9999, 'usuarios_id' => $this -> usuario_id, 'fecha_insert' => date('Y-m-d H:i:s'),'tipo' =>  $this -> input -> post('tipoi'));
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
}
