<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

class Config_loader
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance(); //read manual: create libraries

        $dataX = array(); // set here all your vars to views

        $session_data = $this->CI->session->userdata('logged_in');

        $dataX['Mymenu'] = $this->get_menu($session_data['rol']);
        
        $dataX['inicio'] = $this->get_inicio($session_data['rol']);

        $dataX['usuarioactual'] = $session_data['username'];

        $this->CI->load->vars($dataX);
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

            $html .=' <li><a href="'.site_url($row->add).'"><i class="fa fa-edit"></i> '.strtoupper($row->nombre).'</a></li>   ';
           
        }
        return $html;
    }
    function get_inicio($rol){
        
        switch ($rol) {
            case 1: //Administrador
                return site_url("ventas/listado");
                break;
            case 2: //Vendedor 
               return site_url("ventas/listado");
                break;
            case 3: //Encargado
                return site_url("ventas/listado");
                break;
            case 4: //Sistema
                return site_url("panel");
                break;
            default:
                return null;
        }
    }
}