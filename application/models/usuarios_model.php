<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    var $table = 'usuarios';
    var $column = array('usuarios.nombre','usuarios.apellido','usuarios.usuario','usuarios.email','usuarios.roles_id');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
        $this->db->select('usuarios.id, usuarios.nombre, usuarios.apellido, usuarios.usuario, usuarios.email, roles.nombre AS Rol');
        $this->db->from($this->table);
        $this->db->join('roles', 'roles.id = usuarios.roles_id');
        $this->db->where('usuarios.borrado', 'no');
        $i = 0;
        foreach ($this->column as $item) {
            if ($_POST['search']['value'])
                ($i === 0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        $this->db->where('borrado', 'no');
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $data = array('borrado' => 'si', 'fecha_delete' => date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
      
        /*$this->db->where('id', $id);
        $this->db->delete($this->table);*/
    }
    function login($username, $password) {

        $this->db->select('id, usuario, password, roles_id');
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
