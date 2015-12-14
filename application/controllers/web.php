<?php

class Web extends CI_Controller {

    var $productos_ordenados_por = array('precio', 'nombre');
    var $productos_forma_ordenacion = array('asc', 'desc');
    var $ordenado_por;
    var $forma_ordenacion;
    var $cliente_logueado = '';

    function __construct() {

        parent::__construct();

        $this->load->library("pagination");

        $this->load->model('ServicioModel', 'servicio');

        $this->load->model('ClientesModel', 'cliente');

        if ($this->session->userdata('cliente_logged_in')) {

            $session_data = $this->session->userdata('cliente_logged_in');

            $this->cliente_logueado = $session_data['cliente_email'];
        }
    }

    public function index() {

        $data['opcion'] = "index";

       // $data['servicios'] = $this->servicio->get_all();

        $this->load->view('header', $data);

        $this->load->view('indexx', $data);

        $this->load->view('footer');
    }

    public function contact($res='555') {

        $data['opcion'] = "contact";

       // $data['servicios'] = $this->servicio->get_all();

        $this->load->view('header', $data);
        
        $data['res_consulta'] = '999';

        if ($res == 0) {

            $data['res_consulta'] = 0;
        } elseif ($res ==1) {
            $data['res_consulta'] = -1;
        }

        $this->load->view('contact', $data);

        $this->load->view('footer');
    }

    public function about() {

        $data['opcion'] = "about";

       // $data['servicios'] = $this->servicio->get_all();

        $this->load->view('header', $data);

        $this->load->view('about');

        $this->load->view('footer');
    }

    public function clientes() {

        $data['opcion'] = "clientes";

        $data['clientes'] = $this->cliente->get_all();

        $this->load->view('header', $data);

        $this->load->view('clientes');

        $this->load->view('footer');
    }
    
    public function servicios() {

        $data['opcion'] = "servicios";

        $data['servicios'] = $this->servicio->get_all();

        $this->load->view('header', $data);

        $this->load->view('servicios');

        $this->load->view('footer');
    }

    public function prxs() {

        $data['opcion'] = "clientes";

       // $data['servicios'] = $this->servicio->get_all_prxs();

        $this->load->view('header', $data);

        $this->load->view('servicios_1');

        $this->load->view('footer');
    }
    
    public function servicio($id) {

        $data['opcion'] = "servicios";

        if (!isset($id) || (!is_numeric($id))) {
            show_404();
            exit;
        }

       // $data['servicios'] = $this->servicio->get_all();

        $data['servicio_detail'] = $this->servicio->get_by_id($id);

        if ($data['servicio_detail'] == FALSE) {
            show_404();
            exit;
        }

        $this->load->view('header', $data);

        $this->load->view('servicio', $data);

        $this->load->view('footer');
    }

    public function enviar_consulta() {

        $this->load->library('email');

        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';

        $this->email->initialize($config);
        $this->email->from($this->input->post('email'), $this->input->post('username'));
        $this->email->to('fdkseguridad@gmail.com');
        $this->email->cc('info@feedbackseguridad.com.ar');
        $this->email->bcc('consultas@mtkstudio.com.ar');
        $this->email->subject("FEEDBACK SEGURIDAD: NUEVA CONSULTA DESDE LA WEB");
        $this->email->message('<html><head></head><body>
	                           <u>NOMBRE: </u>' . $this->input->post('username') . '<br><u>EMAIL: </u>' . $this->input->post('email') . '<br><u>ASUNTO: </u>' . $this->input->post('subject') . '<br><u>COMENTARIO: </u>' . $this->input->post('msg') . '</body></html>');

        $data = array('nombre' => $this->input->post('username'), 'email' => $this->input->post('email'), 'mensaje' => $this->input->post('msg'), 'asunto' => $this->input->post('subject'));

        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        $data['browser'] = $_SERVER['HTTP_USER_AGENT'];

        $this->db->insert('contacto', $data);

        if (!$this->email->send()) {
            header("Location: " . base_url() . "index.php/web/contact/1");
            exit;
        } else {
            header("Location: " . base_url() . "index.php/web/contact/0");
            exit;
            //$this->contact("ok");
        }
    }

}

?>