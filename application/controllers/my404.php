<?php
class my404 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->output->set_status_header('404');

        $this->load->model('ServicioModel', 'servicio');

        $data['servicios'] = $this->servicio->get_all();

        $data['opcion'] = 'error_404'; // View name
        
        $this->load->view('header', $data);

        $data['content'] = 'error_404'; // View name

        $this->load->view('404',$data);//loading in my template

        $this->load->view('footer');
    }
}
?>