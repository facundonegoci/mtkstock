<?php

class UsuariosModel extends CI_Model {

    var $title = '';
    var $content = '';
    var $date = '';

    function __construct() {
        // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function login($username, $password) {

        $this->db->select('id, usuario, password');
        $this->db->from('usuarios');
        $this->db->where('usuario = ' . "'" . $username . "'");
        $this->db->where('password = ' . "'" . $password . "'");
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>
