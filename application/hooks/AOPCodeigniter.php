<?php

class AOPCodeigniter extends CI_Hooks {

    private $CI;
    private $controller_excluidos = array('login', 'verifylogin');

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function is_logged_in() {

        $class = $this->CI->router->class;

        if (!in_array($class, $this->controller_excluidos)) {

            if ($this->CI->session->userdata('logged_in')) {

                $session_data = $this->CI->session->userdata('logged_in');

                $this->CI->user = $session_data['username'];

                //if(!$this->tiene_permisos($class,$session_data['tipousuario'])){ redirect('login', 'refresh');  }
                $this->CI->menu = $this->get_menu($session_data['rol']);
                
                $this->CI->filtro_por_rol = $this->get_filtro($session_data['rol'],$session_data['id']);
                
            } else {
            
                redirect('login', 'refresh');
            }
        }
    }

    public function get_menu($rol) {

        $html = '';

        $this->CI->db->select('*');
        $this->CI->db->from('menu');
        $this->CI->db->join('menu_usuario', 'menu.id = menu_usuario.idmenu');
        $this->CI->db->where('menu_usuario.rol_usuario', $rol);
        $this->CI->db->order_by("orden", "asc");
        $query = $this->CI->db->get();

        foreach ($query->result() as $row) {

            $html .= ' <li class="sub-menu">
                  <a href="javascript:;" class="">
                      <i class="icon-user"></i>
                      <span>' . $row->nombre . '</span>
                      <span class="arrow"></span>
                  </a>
                  <ul class="sub">
                      <li><a class="" href="' . site_url($row->add) . '">Agregar</a></li>
                      <li><a class="" href="' . site_url($row->list) . '">Listar</a></li>
                      
                  </ul>
              </li>';
        }
        return $html;
    }

    public function tiene_permisos($controller, $tipousuario) {
        $this->CI->db->select('*');
        $this->CI->db->from('permisos');
        $this->CI->db->where('idtipousuario', $tipousuario);
        $this->CI->db->where('habilitado', 'si');
        $query = $this->CI->db->get();

        foreach ($query->result() as $row) {

            if ($controller == $row->controller) {
                return true;
            }
        }
        return false;
    }
    function get_filtro($rol,$idusuario){
        
        switch ($rol) {
           /* case 1: //Administrador
                return array("usuarios_id",$idusuario);
                break;*/
            case 2: //Vendedor 
               return array("usuarios_id",$idusuario);
                break;
            case 3: //Encargado
                return array("usuarios_id",$idusuario);
                break;
            /*case 4: //Sistema
                return array("usuarios_id",$idusuario);
                break;*/
            default:
                return null;
        }
        
    }

}
