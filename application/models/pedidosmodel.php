<?php
class PedidosModel extends CI_Model {

	function __construct() {
		// Llamar al constructor de CI_Model
		parent::__construct();
	}

	function get_all() {
		/* $this->db->select('id, imagen');

		 $this->db->order_by("fecha", "asc");

		 $query = $this->db->get('banner');

		 return $query->result();*/
	}

	public function insert($data) {

		$this -> db -> trans_start();

		$idpedido = $this -> insert_pedido($data,$this -> cart -> total());

		$total = $this -> insert_items($idpedido);

		$this -> db -> trans_complete();

		if ($this -> db -> trans_status() === FALSE) {
			return FALSE;
			//show_error('message', $status_code);
		} else {
			return TRUE;
		}

	}

	private function insert_pedido($data,$total) {
		$data['ip'] = $_SERVER['REMOTE_ADDR'];

		$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
		
		$data['montototal'] = $total;
 
		$this -> db -> insert('pedidos', $data);
		
		return $this->db->insert_id();

	}

	private function insert_items($idpedido) {
			
		$data = array();
		
		$i = 1;

		foreach ($this->cart->contents() as $items) {

			$data[] = array('idpedido' => $idpedido, 'idproducto' => $items['options']['idproducto'], 'cantidad' => $items['qty'], 'precio' => $items['price'], 'total' => $items['subtotal'], 'nombreproducto' => $items['name'], 'priority' => $i, 'cart_rowid' => $items['rowid'], );
			$i++;
		}

		$this -> db -> insert_batch('items', $data);

	}
}
?>
