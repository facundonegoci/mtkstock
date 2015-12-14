<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {

	var $usuario_id;

	public function __construct() {
			
		parent::__construct();

		$this -> load -> model('productos_model', 'obj_model');
		
		$this -> load -> model('movimientos_stock_model', 'movimiento');
		
		$this -> load -> library('form_validation');

		$session_data = $this -> session -> userdata('logged_in');

		$this -> usuario_id = $session_data['id'];
	}

	public function index() {

		$this -> load -> helper('url');

		$this -> movimiento -> delete_items_huerfanos();

		$msg['categorias'] = $this -> get_categorias_productos();

		$msg['proveedores'] = $this -> get_proveedores();

		$msg['output'] = $this -> load -> view('productos/view', $msg, true);

		$this -> load -> view('dashboard', $msg);
	}


	public function ajax_list() {
		$list = $this -> obj_model -> get_datatables();
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
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()"  onclick="edit_obj(' . "'" . $obj -> id . "'" . ')" title="Editar"><i class="glyphicon glyphicon-pencil" title="Editar"></i></a>
                  <a class="btn btn-sm btn-danger" href="javascript:void()"  onclick="delete_obj(' . "'" . $obj -> id . "'" . ')"  title="Eliminar"><i class="glyphicon glyphicon-trash"  title="Eliminar"></i></a>';

			$data[] = $row;
		}

		$output = array("draw" => $_POST['draw'], "recordsTotal" => $this -> obj_model -> count_all(), "recordsFiltered" => $this -> obj_model -> count_filtered(), "data" => $data, );
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id) {
		$data = $this -> obj_model -> get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add() {

		$this -> set_error_msg();

		$this -> form_validation -> set_rules('codigo', 'Codigo', 'required|xss_clean|trim|numeric|is_unique[productos.codigo]');
		$this -> form_validation -> set_rules('nombre', 'Nombre', 'required|xss_clean|trim|max_length[200]');
		$this -> form_validation -> set_rules('stock', 'Stock', 'trim|required|integer|xss_clean|greater_than[0]');
		$this -> form_validation -> set_rules('stock_minimo', 'Stock minimo', 'trim|required|integer|xss_clean|greater_than[0]');
		$this -> form_validation -> set_rules('precio_venta_final', 'Precio venta final', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_venta_siniva', 'Precio venta sin iva', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_mayorista', 'Precio mayorista', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_mayorista_siniva', 'Precio mayorista sin iva', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('habilitado_venta', 'Habilitado venta', 'required|xss_clean|trim');
		$this -> form_validation -> set_rules('categorias_productos_id', 'Categoria', 'trim|required|integer|xss_clean');
		$this -> form_validation -> set_rules('proveedores_id', 'Proveedor', 'trim|required|integer|xss_clean');
		$this -> form_validation -> set_rules('lugar_venta', 'Lugar venta', 'trim|required|xss_clean');
		
		$this -> form_validation -> set_rules('unidad_venta', 'Unidad', 'trim|required|xss_clean');
		$this -> form_validation -> set_rules('cantidad_por_venta', 'Cant. por venta', 'trim|required|integer|xss_clean');
		

		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {
			$data = array('codigo' => $this -> input -> post('codigo'), 'nombre' => $this -> input -> post('nombre'), 'stock' => $this -> input -> post('stock'), 'stock_minimo' => $this -> input -> post('stock_minimo'), 'precio_venta_final' => $this -> input -> post('precio_venta_final'), 'precio_venta_siniva' => $this -> input -> post('precio_venta_siniva'), 'precio_mayorista' => $this -> input -> post('precio_mayorista'), 'precio_mayorista_siniva' => $this -> input -> post('precio_mayorista_siniva'), 'habilitado_venta' => $this -> input -> post('habilitado_venta'), 'habilitado_venta' => $this -> input -> post('habilitado_venta'), 'categorias_productos_id' => $this -> input -> post('categorias_productos_id'), 'proveedores_id' => $this -> input -> post('proveedores_id'),'lugar_venta' => $this -> input -> post('lugar_venta') ,'usuarios_id' => $this -> usuario_id, 'fecha_insert' => date('Y-m-d H:i:s'), 'unidad_venta' => $this->input->post('unidad_venta'), 'cantidad_por_venta' => $this->input->post('cantidad_por_venta'));
			$insert = $this -> obj_model -> save($data);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_update() {

		$this -> set_error_msg();

		$original_value = $this -> db -> query("SELECT codigo FROM productos WHERE id = " . $this -> input -> post('id')) -> row() -> codigo;
		if ($this -> input -> post('codigo') != $original_value) {
			$is_unique = '|is_unique[productos.codigo]';
		} else {
			$is_unique = '';
		}

		$this -> form_validation -> set_rules('codigo', 'Codigo', 'required|xss_clean|trim|numeric|' . $is_unique);
		$this -> form_validation -> set_rules('nombre', 'Nombre', 'required|xss_clean|trim|max_length[200]');
		$this -> form_validation -> set_rules('stock', 'Stock', 'trim|required|integer|xss_clean|greater_than[0]');
		$this -> form_validation -> set_rules('stock_minimo', 'Stock minimo', 'trim|required|integer|xss_clean|greater_than[0]');
		$this -> form_validation -> set_rules('precio_venta_final', 'Precio venta final', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_venta_siniva', 'Precio venta sin iva', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_mayorista', 'Precio mayorista', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('precio_mayorista_siniva', 'Precio mayorista sin iva', 'trim|required|numeric|xss_clean');
		$this -> form_validation -> set_rules('habilitado_venta', 'Habilitado venta', 'required|xss_clean|trim');
		$this -> form_validation -> set_rules('categorias_productos_id', 'Categoria', 'trim|required|integer|xss_clean');
		$this -> form_validation -> set_rules('proveedores_id', 'Proveedor', 'trim|required|integer|xss_clean');
		$this -> form_validation -> set_rules('lugar_venta', 'Lugar venta', 'trim|required|xss_clean');
		
		$this -> form_validation -> set_rules('unidad_venta', 'Unidad', 'trim|required|xss_clean');
		$this -> form_validation -> set_rules('cantidad_por_venta', 'Cant. por venta', 'trim|required|integer|xss_clean');
		
		if ($this -> form_validation -> run() == FALSE) {
			echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
		} else {

			$data = array('codigo' => $this -> input -> post('codigo'), 'nombre' => $this -> input -> post('nombre'), 'stock' => $this -> input -> post('stock'), 'stock_minimo' => $this -> input -> post('stock_minimo'), 'precio_venta_final' => $this -> input -> post('precio_venta_final'), 'precio_venta_siniva' => $this -> input -> post('precio_venta_siniva'), 'precio_mayorista' => $this -> input -> post('precio_mayorista'), 'precio_mayorista_siniva' => $this -> input -> post('precio_mayorista_siniva'), 'habilitado_venta' => $this -> input -> post('habilitado_venta'), 'categorias_productos_id' => $this -> input -> post('categorias_productos_id'), 'proveedores_id' => $this -> input -> post('proveedores_id'),'lugar_venta' => $this -> input -> post('lugar_venta'), 'usuarios_id' => $this -> usuario_id, 'fecha_update' => date('Y-m-d H:i:s'), 'unidad_venta' => $this->input->post('unidad_venta'), 'cantidad_por_venta' => $this->input->post('cantidad_por_venta'));

			$this -> obj_model -> update(array('id' => $this -> input -> post('id')), $data, $this -> input -> post('id'));
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_delete($id) {
		$this -> obj_model -> delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function get_categorias_productos() {

		$query = $this -> db ->order_by('nombre') -> get_where('categorias_productos', array('borrado' => 'no'));

		$options = array();

		foreach ($query->result() as $row) {
			$options[$row -> id] = $row -> nombre;
		}
		return $options;
	}

	private function get_proveedores() {

		$query = $this -> db ->order_by('nombre','asc') -> get_where('proveedores', array('borrado' => 'no'));

		$options = array();

		foreach ($query->result() as $row) {
			$options[$row -> id] = $row -> nombre;
		}
		return $options;
	}

	public function get_all_products() {

		$res = $this -> obj_model -> get_all_products();

		echo json_encode($res);
	}

	private function set_error_msg() {
		$this -> form_validation -> set_message('numeric', 'El campo %s debe ser un numero valido');
		$this -> form_validation -> set_message('integer', 'El campo %s debe ser un numero entero');
		$this -> form_validation -> set_message('greater_than', 'El campo %s debe ser un positivo');
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
