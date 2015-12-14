<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Cliente extends CI_Controller {

	public function __construct() {
		parent::__construct();
	
	}

	public function index($id,$id2) {
		print "p1: ".urldecode($id);
		
		$data = array("nombre" => urldecode($id), "categorias_productos_id" => 26, 'lugar_venta' => 'c', 'habilitado_venta' => 'si', 'fecha_insert' => date('Y-m-d H:i:s'), 'proveedores_id' => 5, 'usuarios_id' => 6, 'precio_venta_final' => $id2);

        $this->db->insert('productos', $data);
     
	 	$idl = $this->db->insert_id();
		
		$data = array('codigo' => $idl);
	 
		$this->db->update('productos', $data, array('id' => $idl));
	}

}
?>