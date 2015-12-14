<?php
class BannersModel extends CI_Model {

	function __construct(){
		// Llamar al constructor de CI_Model
		parent::__construct();
	}

	function get_all()
	{
		 $this->db->select('id, imagen');
		
		 $this->db->order_by("fecha", "asc");
		 
		 $query = $this->db->get('banner');
		 
		 return $query->result();
	}
}
?>
