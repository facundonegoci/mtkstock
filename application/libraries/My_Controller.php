<?php
class MY_Controller extends CI_Controller {  
    var $global_data = array();  
  
    function MY_Controller() {  
        parent::Controller();  
  
        $this->global_data['Mymenu'] = $this->menu;  
  
        // other common stuff; for example you may want a global cart, login/logout, etc.  
    }  
  
    // create a simple wrapper for the CI load->view() method  
    // but first, merge the global and local data into one array  
    function display_view($view, $local_data = array()) {  
        $data = array_merge($this->global_data, $local_data);  
  
        return $this->load->view($view, $data);  
    }  
} 