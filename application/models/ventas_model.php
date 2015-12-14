<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_model extends CI_Model {

    var $table = 'ventas';

    var $column = array('ventas.id', 'ventas.fecha', 'ventas.usuarios_id');

    var $order = array('id' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query() {
		
        $this->db->select('ventas.id, LPAD(ventas.id,5,"0") as numero, ventas.fecha_insert, ventas.monto_total, usuarios.nombre AS Usuario', FALSE);
        $this->db->from($this->table);
        $this->db->join('usuarios', 'ventas.usuarios_id = usuarios.id');
        $this->db->where('ventas.borrado', 'no');
		
		if($this->filtro_por_rol!=null) $this->db->where( $this->filtro_por_rol[0], $this->filtro_por_rol[1]);
		
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

    public function save($data, $items) {
        
		$this->db->trans_start();	
			
        $this->db->insert($this->table, $data);
				
        $id = $this->db->insert_id();

		$data2 = array();
		
		$cant = count($items);	
		
		for($i=0;$i<$cant;$i++){
			
			$data2[] = array("codigo" => $items[$i][0],
							"nombre" => $items[$i][1],
							"precio_unitario" => $items[$i][2],
							"cantidad" => $items[$i][3],
							"precio_total" => $items[$i][4],
							"idproducto" => $items[$i][6],
							"idventa" => $id,
							"unidad_venta" => $items[$i][8],
							"cantidad_por_venta" => $items[$i][9],
							"fecha_insert" => date('Y-m-d H:i:s'),
							"usuarios_id" => $this->usuario_id);
			
			if($this->startsWith($items[$i][0], '99-')){ // si es una promocion
			
				$productos_promociones = $this->get_productos_promocion($items[$i][6]); //obtengo los productos que pertenecen a la promocion
				
				foreach($productos_promociones as $producto){ //por cada producto descuento el stock.
					
					$this->db->query('UPDATE productos SET stock = stock-' . ($items[$i][3]*$producto->cantidad) . ' WHERE id =' . $producto->idproducto. ' LIMIT 1');
				}
			}else{
			
				$this->db->query('UPDATE productos SET stock = stock-' . $items[$i][3] . ' WHERE id =' . $items[$i][6]. ' LIMIT 1');
			}
		}
		
		$this->db->insert_batch('ventas_productos', $data2);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
		    return FALSE;
		}else{
			return TRUE;
		}
    }

	private function get_productos_promocion($id_promocion){
		
		$this->db->select('idproducto, cantidad', FALSE);
        $this->db->from('promocion_producto');
        $this->db->where('idpromocion', $id_promocion);
		$query = $this->db->get();
        return $query->result();
	}

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
    	
        $data = array('borrado' => 'si', 'fecha_delete' => date('Y-m-d H:i:s'));
		
		$this->db->trans_start();
		
        $this->db->where('id', $id);
		
        $this->db->update($this->table, $data);
		
		$this->db->from("ventas_productos");
		
        $this->db->where('idventa', $id);
        
        $query = $this->db->get();
		
        $res = $query->result();
		
		foreach ($res as $obj) {
		
			$this->db->query('UPDATE productos SET stock = stock+' . $obj->cantidad . ' WHERE id =' . $obj->idproducto . ' LIMIT 1');
			
        }
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
		    return FALSE;
		}else{
			return TRUE;
		}
    }

    public function get_all_products() {
        $this->db->select('productos.id, productos.codigo, productos.nombre, productos.precio_venta_final, categorias_productos.nombre AS Categoria, productos.unidad_venta, productos.cantidad_por_venta');
        $this->db->from('productos');
        $this->db->join('categorias_productos', 'categorias_productos.id = productos.categorias_productos_id');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->result();
    }

	public function get_all_promociones() {
        $this->db->select('promociones.id, promociones.codigo, promociones.nombre, promociones.monto_final AS precio_venta_final, "-" AS Categoria, "unidad" AS unidad_venta, 1 AS cantidad_por_venta', FALSE);
        $this->db->from('promociones');
        $this->db->where('promociones.borrado', 'no');
		$this->db->where('promociones.fecha_fin <=', date('Y-m-d'));
        $query = $this->db->get();
        return $query->result();
    }
	
    private function update_items($idmovimiento, $data) {
        $this->db->where('movimiento_stock_id', '-9999');
        $this->db->where('usuarios_id', $data['usuarios_id']);
        $this->db->update($this->table_item, array('movimiento_stock_id' => $idmovimiento));
    }
 
 	public function get_name_all_products() {
        $this->db->select('CONCAT(productos.nombre,"|",productos.codigo) AS nombre', false);
        $this->db->from('productos');
        $this->db->where('productos.borrado', 'no');
        $query = $this->db->get();
        return $query->result();
    }
 
	function startsWith($haystack, $needle){
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
    }
}
