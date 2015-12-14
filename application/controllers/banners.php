<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Banners extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this -> load -> library('grocery_CRUD');
	}

	public function index() {
		$crud = new grocery_CRUD();

		$crud -> set_theme('datatables');

		$crud -> set_table('banner') -> set_subject('Banners') -> columns('imagen');

		$crud -> required_fields('imagen');

		$crud -> set_field_upload('imagen', 'assets/uploads/files');
		
		$crud -> unset_fields('fecha');

		$crud -> unset_print();

		$output = $crud -> render();

		$this -> _example_output($output);
	}

	function _example_output($output = null) {
		$this -> load -> view('admin/example.php', $output);
	}

}
?>