<?php

class ClientesModel extends CI_Model {

    function __construct() {
        // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function get_all() {
        $this->db->select('*');

        $this->db->order_by("nombre", "asc");

        $query = $this->db->get('clientes');

        return $query->result();
    }

    public function insert($data) {

        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        $data['browser'] = $_SERVER['HTTP_USER_AGENT'];

        $this->db->insert('clientes', $data);
    }

    function login($email, $password) {

        $this->db->select('id, email, password');
        $this->db->from('clientes');
        $this->db->where('email = ' . "'" . $email . "'");
        $this->db->where('password = ' . "'" . $password . "'");
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_by_email($email) {

        $query = $this->db->get_where('clientes', array('email' => $email), 1);
        if ($query->num_rows() > 0) {
            $row = $query->row();

            return $row;
        } else {
            return FALSE;
        }
    }

}

?>
