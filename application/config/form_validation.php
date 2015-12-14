<?php
$config = array(
 'web/process_register' => array(
                array(
                        'field' => 'nombre',
                        'label' => 'Nombre',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'apellido',
                        'label' => 'Apellido',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'trim|required|valid_email|is_unique[clientes.email]'
                     ),
                array(
                        'field' => 'telefono',
                        'label' => 'Telefono',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                 array(
                        'field' => 'direccion',
                        'label' => 'Direccion',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'localidad',
                        'label' => 'Ciudad',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'cp',
                        'label' => 'Codigo Postal',
                        'rules' => 'trim|required|numeric|xss_clean'
                     ),
                 array(
                        'field' => 'provincia',
                        'label' => 'Provincia',
                        'rules' => 'trim|required|numeric|xss_clean'
                     ),         
                array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'trim|required|matches[confirmpassword]|md5'
                     ),
                array(
                        'field' => 'confirmpassword',
                        'label' => 'PasswordConfirmation',
                        'rules' => 'confirmpassword'
                     )
					 
  ),
 'email' => array(
	            array(
	                    'field' => 'emailaddress',
	                    'label' => 'EmailAddress',
	                    'rules' => 'required|valid_email'
	                 ),
	            array(
	                    'field' => 'name',
	                    'label' => 'Name',
	                    'rules' => 'required|alpha'
	                 ),
	            array(
	                    'field' => 'title',
	                    'label' => 'Title',
	                    'rules' => 'required'
	                 ),
	            array(
	                    'field' => 'message',
	                    'label' => 'MessageBody',
	                    'rules' => 'required'
	                     )
   ),
   'web/insert_pedido' => array(
                array(
                        'field' => 'nombre',
                        'label' => 'Nombre',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'apellido',
                        'label' => 'Apellido',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'trim|required|valid_email|'
                     ),
                array(
                        'field' => 'telefono',
                        'label' => 'Telefono',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                 array(
                        'field' => 'direccion',
                        'label' => 'Direccion',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'localidad',
                        'label' => 'Ciudad',
                        'rules' => 'trim|required|min_length[4]|max_length[200]|xss_clean'
                     ),
                array(
                        'field' => 'cp',
                        'label' => 'Codigo Postal',
                        'rules' => 'trim|required|numeric|xss_clean'
                     ),
               array(
                        'field' => 'provincia',
                        'label' => 'Provincia',
                        'rules' => 'trim|required|numeric|xss_clean'
                     ),         
               array(
                        'field' => 'formadepago',
                        'label' => 'Forma de pago',
                        'rules' => 'trim|required|numeric|xss_clean'
                     ) 
  )                        
);