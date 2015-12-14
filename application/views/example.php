<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Panel de control</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>css/admin/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />

        <!-- Add custom CSS here -->
        <link href="<?php echo base_url(); ?>css/sb-admin.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>/css/login/font-awesome.min.css">
        <meta charset="utf-8" />
        <?php foreach ($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
        <?php foreach ($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
        <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>css/admin/style.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>css/admin/style-responsive.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>css/admin/style-default.css" rel="stylesheet" id="style_color" />

        <style type='text/css'>
            body
            {
                font-family: Arial;
                font-size: 14px;
            }
            a {
                color: blue;
                text-decoration: none;
                font-size: 14px;
            }
            a:hover
            {
                text-decoration: underline;
            }
            .datatables div.form-div input[type="text"], .datatables div.form-div input[type="password"] { padding:0;}
            body{background-color: #ffffff;}
            ul.unstyled, ol.unstyled {
                list-style-image: none;
                list-style-position: outside;
                list-style-type: none;
            </style>
        </head>
        <body>
            <div id="wrapper">

                <!-- Sidebar -->
                <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Navegacion</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">PANEL DE ADMINISTRACION</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav side-nav">
                            <li><a href="<?php echo site_url('admin/clientes') ?>"><i class="fa fa-dashboard"></i> Clientes</a></li>
                            <li><a href="<?php echo site_url('admin/servicios') ?>"><i class="fa fa-edit"></i>Servicios</a></li>
                            <li><a href="<?php echo site_url('admin/usuarios') ?>"><i class="fa fa-edit"></i> Usuarios</a></li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right navbar-user">
                            <!--
                                  <li class="dropdown messages-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge">7</span> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                          <li class="dropdown-header">7 New Messages</li>
                                          <li class="message-preview">
                                            <a href="#">
                                                  <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                                  <span class="name">John Smith:</span>
                                                  <span class="message">Hey there, I wanted to ask you something...</span>
                                                  <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                                            </a>
                                          </li>
                                          <li class="divider"></li>
                                          <li class="message-preview">
                                            <a href="#">
                                                  <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                                  <span class="name">John Smith:</span>
                                                  <span class="message">Hey there, I wanted to ask you something...</span>
                                                  <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                                            </a>
                                          </li>
                                          <li class="divider"></li>
                                          <li class="message-preview">
                                            <a href="#">
                                                  <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                                  <span class="name">John Smith:</span>
                                                  <span class="message">Hey there, I wanted to ask you something...</span>
                                                  <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                                            </a>
                                          </li>
                                          <li class="divider"></li>
                                          <li><a href="#">View Inbox <span class="badge">7</span></a></li>
                                    </ul>
                                  </li>
                                  <li class="dropdown alerts-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> Alerts <span class="badge">3</span> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                          <li><a href="#">Default <span class="label label-default">Default</span></a></li>
                                          <li><a href="#">Primary <span class="label label-primary">Primary</span></a></li>
                                          <li><a href="#">Success <span class="label label-success">Success</span></a></li>
                                          <li><a href="#">Info <span class="label label-info">Info</span></a></li>
                                          <li><a href="#">Warning <span class="label label-warning">Warning</span></a></li>
                                          <li><a href="#">Danger <span class="label label-danger">Danger</span></a></li>
                                          <li class="divider"></li>
                                          <li><a href="#">View All</a></li>
                                    </ul>
                                  </li>
                            -->
                            <li class="dropdown user-dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>Cuenta<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                      <!--<li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>
                                      <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>-->
                                    <!--<li class="divider"></li>-->
                                    <li><a href="<?php echo site_url('verifylogin/logout') ?>"><i class="fa fa-power-off"></i>Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>

                <div id="page-wrapper" style="margin-top: 25px">

                        <div class="row">
                            <div class="col-lg-12">
                                <h1><?php echo strtoupper($this->uri->segment(2)); ?><small></small></h1>

                                <?php if ($this->uri->segment(1) == "envioxx"): ?>

                                    <div class="row-fluid">

                                        <div class="span5">
                                            <!-- BEGIN PROGRESS PORTLET-->
                                            <div class="widget purple">
                                                <div class="widget-title">
                                                    <h4><i class="icon-tasks"></i> Progreso de envios </h4>
                                                    <span class="tools">
                                                        <a href="javascript:;" class="icon-chevron-down"></a>
                                                        <a href="javascript:;" class="icon-remove"></a>
                                                    </span>
                                                </div>
                                                <div class="widget-body">
                                                    <ul class="unstyled">

                                                        <li>
                                                            <span class="btn btn-inverse"> <i class="icon-cloud-upload"></i></span> Envio newsletter de prueba(2014-05-08)<strong class="label label-success"> 85%</strong>
                                                            <div class="space10"></div>
                                                            <div class="progress progress-success">
                                                                <div style="width: 85%;" class="bar"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="btn btn-inverse"> <i class="icon-cloud-upload"></i></span> Envio promocion mayo(2014-05-08)<strong class="label label-success"> 65%</strong>
                                                        <div class="space10"></div>
                                                        <div class="progress progress-danger">
                                                            <div style="width: 65%;" class="bar"></div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <?php endif; ?>    

                            <div>
                                <?php echo $output; ?>
                            </div>
                        </div>
                    </div><!-- /.row -->

                </div><!-- /#page-wrapper -->

            </div><!-- /#wrapper -->

            <!-- JavaScript --
            <script src="<?php //echo base_url();  ?>js/jquery-1.10.2.js"></script>
            -->
            <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>	

        </body>
    </html>
