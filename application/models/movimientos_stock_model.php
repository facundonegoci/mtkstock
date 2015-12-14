<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos_stock_model extends CI_Model {

	var $table = 'productos';
    var $table_actualizacion = 'movimiento_stock';
    var $table_item = 'productos_has_movimiento_stock';
    var $column = array('productos.codigo', 'productos.nombre', 'productos.stock', 'productos.stock_minimo', 'productos.categorias_productos_id');
    var $column_movimientos = array('movimiento_stock.fecha', 'movimiento_stock.numero_factura', 'movimiento_stock.monto', 'proveedores.nombre','movimiento_stock.tipo');
    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_items($idusuario) {
        $this->db->select('id,codigo, nombre, cantidad, precio_unitario, precio_total');
        $this->db->from('productos_has_movimiento_stock');
        $this->db->where('movimiento_stock_id', '-9999');
        $this->db->where('usuarios_id', $idusuario);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query_movimientos() {
        $this->db->select('movimiento_stock.id, movimiento_stock.fecha, movimiento_stock.numero_factura, movimiento_stock.monto, proveedores.nombre AS Proveedor, if(tipo="c", "Carga", "Devolucion") AS Tipo',false);
        $this->db->from('movimiento_stock');
        $this->db->join('proveedores', 'proveedores.id = movimiento_stock.proveedores_id');
		$this->db->where('movimiento_stock.borrado', 'no');
		$this->db->order_by('movimiento_stock.fecha', 'desc');

        $i = 0;
        foreach ($this->column_movimientos as $item) {
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

    function get_movimientos() {

        $this->_get_datatables_query_movimientos();

        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_movimientos() {
        $this->_get_datatables_query_movimientos();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_movimientos() {
        $this->db->from($this->table_actualizacion);
        $this->db->where('borrado', 'no');
        return $this->db->count_all_results();
    }
  
    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save_actualizacion($data) {

        /* grabo el movimeitno */
        $this->db->insert($this->table_actualizacion, $data);

        $idmovimiento = $this->db->insert_id();

        /* actualizo los items */
        $this->update_items($idmovimiento, $data);

        /* actualizo el stock y el precio de los items */
        $this->update_productos($idmovimiento);
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $data = array('borrado' => 'si', 'fecha_delete' => date('Y-m-d H:i:s'));
        $this->db->where('id', $id);
        $this->db->update($this->table_actualizacion, $data);
		
		$this->update_productos_by_delete_movimiento($id);
    }

    public function delete_items_huerfanos() {
        $this->db->where('movimiento_stock_id', '-9999');
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

    public function get_all_products() {
        $this->db->select('productos.id, productos.codigo, productos.nombre, productos.stock, productos.stock_minimo, productos.precio_mayorista, categorias_productos.nombre AS Categoria, productos.proveedores_id');
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

    private function update_productos($idmovimiento) {

        $items = $this->get_items_movimiento($idmovimiento);

        foreach ($items as $obj) {
			
            //$this->db->query('UPDATE productos SET stock = stock+' . $obj->cantidad . ', precio=' . $obj->precio_unitario . ' WHERE id =' . $obj->productos_id . ' LIMIT 1');
			if($obj->tipo == 'c'){
				$this->db->query('UPDATE productos SET stock = stock+' . $obj->cantidad . ' WHERE id =' . $obj->productos_id . ' LIMIT 1');
			}elseif($obj->tipo=='d'){
				$this->db->query('UPDATE productos SET stock = stock-' . $obj->cantidad . ' WHERE id =' . $obj->productos_id . ' LIMIT 1');
			}
        }
    }


	private function update_productos_by_delete_movimiento($idmovimiento) {

        $items = $this->get_items_movimiento($idmovimiento);

        foreach ($items as $obj) {
		
			if($obj->tipo == 'c'){
				$this->db->query('UPDATE productos SET stock = stock-' . $obj->cantidad . ' WHERE id =' . $obj->productos_id . ' LIMIT 1');
			}elseif($obj->tipo=='d'){
				$this->db->query('UPDATE productos SET stock = stock+' . $obj->cantidad . ' WHERE id =' . $obj->productos_id . ' LIMIT 1');
			}
        }
    }

    private function get_items_movimiento($idmovimiento) {
        $this->db->from($this->table_item);
        $this->db->where('movimiento_stock_id', $idmovimiento);
        $query = $this->db->get();
        return $query->result();
    }

}
