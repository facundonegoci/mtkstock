<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {

    var $table = 'productos';
    var $table_actualizacion = 'movimiento_stock';
    var $table_item = 'productos_has_movimiento_stock';
    var $column = array('productos.codigo', 'productos.nombre', 'productos.stock', 'productos.stock_minimo', 'productos.categorias_productos_id','productos.precio_mayorista');
    var $column_movimientos = array('movimiento_stock.fecha', 'movimiento_stock.numero_factura', 'movimiento_stock.monto', 'proveedores.nombre');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
        $this->db->select('productos.id, productos.codigo, productos.nombre, productos.stock, productos.stock_minimo, productos.precio_venta_final,productos.precio_mayorista,(productos.precio_venta_final-productos.precio_mayorista) as Ganancia/*, categorias_productos.nombre AS Categoria*/');
        $this->db->from($this->table);
        $this->db->join('categorias_productos', 'categorias_productos.id = productos.categorias_productos_id');
        $this->db->where('productos.borrado', 'no');
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
    }

    public function get_all_products() {
        $this->db->select('productos.id, productos.codigo, productos.nombre, productos.stock, productos.stock_minimo, productos.precio_mayorista, categorias_productos.nombre AS Categoria');
        $this->db->from($this->table);
        $this->db->join('categorias_productos', 'categorias_productos.id = productos.categorias_productos_id');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->result();
    }

    private function update_items($idmovimiento, $data) {
        $this->db->where('movimiento_stock_id', '-9999');
        $this->db->where('usuarios_id', $data['usuarios_id']);
        $this->db->update($this->table_item, array('movimiento_stock_id' => $idmovimiento));
    }
 
}
