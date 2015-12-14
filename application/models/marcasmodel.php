<?php
class MarcasModel extends CI_Model {

		
	function __construct(){
		// Llamar al constructor de CI_Model
		parent::__construct();
	}

	function get_all($limit)
	{
		
		 $this->db->select('id, nombre, imagen');
		
		 $this->db->order_by("nombre", "asc");
		 
		 $this->db->limit($limit);
		 
		 $query = $this->db->get('marcas');
		 
		 return $query->result();
	}
}
?>
