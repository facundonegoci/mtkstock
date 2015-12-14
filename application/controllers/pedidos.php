<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Pedidos extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this -> load -> library('grocery_CRUD');
	}

	public function index() {
		$crud = new grocery_CRUD();

		$crud -> set_theme('datatables');

		$crud -> set_table('pedidos') -> set_subject('Pedidos') -> columns('fecharegistro', 'idcliente', 'formadepago', 'montototal');

		//$crud -> required_fields('titulo', 'fechainicio', 'habilitado');

		//$crud -> unique_fields('nombre','categoria');

		//$crud -> set_field_upload('imagen', 'assets/uploads/files');

		$crud -> unset_texteditor('descripcion', 'full_text');

		$crud -> set_relation_n_n('productos', 'items', 'producto', 'idpedido', 'idproducto', 'nombre', 'priority');

		$crud -> display_as('montototal', 'Monto');
		$crud -> display_as('nombrecliente', 'Nombre cliente');
		$crud -> display_as('apellido', 'Apellido cliente');
		$crud -> display_as('formadepago', 'Forma de pago');
		$crud -> display_as('idcliente', 'Cliente');

		$crud -> unset_fields('fecharegistro');

		$crud -> unset_print();

		$output = $crud -> render();

		$this -> _example_output($output);
	}

	function _example_output($output = null) {
		$this -> load -> view('admin/example.php', $output);
	}

}
?>