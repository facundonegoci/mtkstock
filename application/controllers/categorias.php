<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Categorias extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this -> load -> library('grocery_CRUD');
	}

	public function index() {
		$crud = new grocery_CRUD();

		$crud -> set_theme('datatables');

		$crud -> set_table('categoria') -> set_subject('categoria') -> columns('nombre');

		$crud -> required_fields('nombre');

		$crud -> unique_fields('nombre');

		$crud -> unset_texteditor('descripcion', 'full_text');

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