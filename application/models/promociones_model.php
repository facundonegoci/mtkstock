<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones_model extends CI_Model {

	var $table = 'promociones';
    var $table_actualizacion = 'promociones';
    var $table_item = 'promocion_producto';
    var $column = array('codigo', 'nombre', 'fecha_inicio', 'fecha_fin', 'monto_final', 'monto_original');
    
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_items($idusuario) {
        $this->db->select('id,codigo, nombre, cantidad, precio, precio_final');
        $this->db->from($this->table_item);
        $this->db->where('idpromocion', '-9999');
        $this->db->where('usuarios_id', $idusuario);
        $query = $this->db->get();
        return $query->result();
    }

	function get_items_by_promocion($id, $idusuario) {
        $this->db->select('id,codigo, nombre, cantidad, precio, precio_final');
        $this->db->from($this->table_item);
       //$this->db->where('idpromocion', $id);
		
		 $where = "idpromocion = $id OR (idpromocion = -9999 AND usuarios_id = $idusuario)";
     	 $this->db->where($where,NULL,FALSE);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query() {
        $this->db->select('id, codigo, nombre, fecha_inicio, fecha_fin, monto_final, monto_original');
        $this->db->from($this->table);
		$this->db->where('borrado', 'no');
		$this->db->order_by('fecha_inicio', 'desc');

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

    function get_promociones() {

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

    public function save_promocion($data) {

        /* grabo el movimeitno */
        $this->db->insert($this->table, $data);

        $idpromocion = $this->db->insert_id();

        /* actualizo los items */
        $this->update_items($idpromocion, $data);
    }

    public function update_promocion($where, $data, $id) {
        $this->db->update($this->table, $data, $where);
		
		/* actualizo los items */
        $this->update_items($id, $data);
		
        return true;
    }

    public function delete_by_id($id) {
        $data = array('borrado' => 'si', 'fecha_delete' => date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function delete_items_huerfanos() {
        $this->db->where('idpromocion', '-9999');
        $this->db->delete($this->table_item);
    }

    public function delete_item_by_id($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_item);
    }

    public function save_item($data) {
        $this->db->insert($this->table_item, $data);
        return $this->db->insert_id();
    }

    private function update_items($id, $data) {
        $this->db->where('idpromocion', '-9999');
        $this->db->where('usuarios_id', $data['usuarios_id']);
        $this->db->update($this->table_item, array('idpromocion' => $id));
    }

    private function get_items_promocion($id) {
        $this->db->from($this->table_item);
        $this->db->where('idpromocion', $id);
        $query = $this->db->get();
        return $query->result();
    }


	public function get_all_products() {
        $this->db->select('productos.id, productos.codigo, productos.nombre, productos.precio_venta_final');
        $this->db->from('productos');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->result();
    }
	public function get_name_all_products() {
        $this->db->select('CONCAT(productos.nombre,"|",productos.codigo) AS nombre', false);
        $this->db->from('productos');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->result();
    }
}
