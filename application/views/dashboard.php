<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>PANEL DE CONTROL</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

        <link href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet">

        <script src="<?php echo base_url('js/jquery/jquery-2.1.4.min.js') ?>"></script>
      
      	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      
        <script src="<?php echo base_url('js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.js') ?>"></script>
        
       <script src="<?php echo base_url('js/js_mtk.js') ?>"></script>

        <style>
			body {
				padding-top: 50px;
			}
			.sub-header {
				padding-bottom: 10px;
				border-bottom: 1px solid #eee;
			}

			.navbar-fixed-top {
				border: 0;
			}
			.sidebar {
				display: none;
			}
			@media (min-width: 768px) {
				.sidebar {
					position: fixed;
					top: 51px;
					bottom: 0;
					left: 0;
					z-index: 1000;
					display: block;
					padding: 20px;
					overflow-x: hidden;
					overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
					background-color: #f5f5f5;
					border-right: 1px solid #eee;
				}
			}
			/* Sidebar navigation */
			.nav-sidebar {
				margin-right: -21px; /* 20px padding + 1px border */
				margin-bottom: 20px;
				margin-left: -20px;
			}
			.nav-sidebar > li > a {
				padding-right: 20px;
				padding-left: 20px;
			}
			.nav-sidebar > .active > a, .nav-sidebar > .active > a:hover, .nav-sidebar > .active > a:focus {
				color: #fff;
				background-color: #428bca;
			}
			.main {
				padding: 10px;
			}
			@media (min-width: 768px) {
				.main {
					padding-right: 10px;
					padding-left: 10px;
				}
			}
			.main .page-header {
				margin-top: 0;
			}
			.placeholders {
				margin-bottom: 30px;
				text-align: center;
			}
			.placeholders h4 {
				margin-bottom: 0;
			}
			.placeholder {
				margin-bottom: 20px;
			}
			.placeholder img {
				display: inline-block;
				border-radius: 50%;
			}
			.datatables div.form-div input[type="text"], .datatables div.form-div input[type="password"
				] {
				padding: 0px
				}
				html, body {
					height: 100%;
					width: 99%;
				}
				.form-control_facu {
					background-color: #fff;
					background-image: none;
					border: 1px solid #ccc;
					border-radius: 4px;
					box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
					color: #000;
					display: block;
					font-size: 12px;
					height: 21px;
					line-height: 0;
					padding: 0;
					transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
					width: 100%;
				}
				.form-control120 {
					width: 112px;
					background-color: #fff;
					background-image: none;
					border: 1px solid #ccc;
					border-radius: 4px;
					box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
					color: #000;
					display: block;
					font-size: 12px;
					height: 25px;
					line-height: 1.42857;
					padding: 1px;
					transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
				}
				.label_stock {
					display: inline-block;
					font-size: 12px;
					font-weight: 700;
					margin-bottom: 1px;
					max-width: 100%;
				}
				.label_left {
					text-align: left;
					font-size: 12px;
				}
	
        </style>     

    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">SISTEMA DE STOCK</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Inicio</a></li>
                        <li><a href="<?php echo site_url('verifylogin/logout') ?>">Salir</a></li>
                    </ul>
                    
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">

                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">MENU <span class="sr-only"></span></a></li>
						
						<?php echo $Mymenu; ?>
						
                       <!-- <li><a href="<?php echo site_url('roles') ?>"><i class="fa fa-edit"></i>Roles</a></li>
                        <li><a href="<?php echo site_url('categorias_productos') ?>"><i class="fa fa-edit"></i>Categorias productos</a></li>   
                        <li><a href="<?php echo site_url('usuarios') ?>"><i class="fa fa-dashboard"></i>Usuarios</a></li>
                        <li><a href="<?php echo site_url('productos') ?>"><i class="fa fa-edit"></i>Productos</a></li>
                        <li><a href="<?php echo site_url('promociones') ?>"><i class="fa fa-edit"></i>Promociones</a></li>
                        <li><a href="<?php echo site_url('ventas') ?>"><i class="fa fa-edit"></i>Nueva Venta</a></li>
                        <li><a href="<?php echo site_url('ventas/listado') ?>"><i class="fa fa-edit"></i> Listado Ventas</a></li>
                        <li><a href="<?php echo site_url('proveedores') ?>"><i class="fa fa-edit"></i>Proveedores</a></li>-->
                    </ul>

                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="">
                    <h1 class="page-header" style="font-size: 25px"><?php echo strtoupper($this -> uri -> segment(1)); ?></h1>

                        <?php echo $output; ?>
                
                </div>
            </div>
        </div>
  
    </body>
</html>
