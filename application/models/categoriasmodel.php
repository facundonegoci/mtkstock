<?php
class CategoriasModel extends CI_Model {

		
	function __construct(){
		// Llamar al constructor de CI_Model
		parent::__construct();
	}

	function get_all()
	{
		
		 $this->db->select('id, nombre');
		
		 $this->db->order_by("nombre", "asc");
		 
		 $query = $this->db->get('categoria');
		 
		 return $query->result();
	}
	
	function get_by_id($nombre) {

		$this -> db -> select('id');

		$this -> db -> where("nombre", $nombre);

		$this -> db -> limit(1);

		$query = $this -> db -> get('categoria');
		
		if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		
		   return $row->id;

		} 

		return FALSE;

	}
}
?>
