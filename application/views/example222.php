<!DOCTYPE html>

<html>
	
	<head>
		
	<meta charset="utf-8" />
	
	<title>Sistema Deportivo siniestro</title>
	
		<?php 
		foreach($css_files as $file): ?>
			<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
		<?php foreach($js_files as $file): ?>
			<script src="<?php echo $file; ?>"></script>
		<?php endforeach; ?>
		
		<style type='text/css'>
		
		body {
				margin: 0;
				padding: 0;
				font-family: Verdana, Arial, sans-serif;
				font-size: small;
				}
			
			#header {
				padding: 20px;
				background: #333;
				border-bottom: 1px solid #fff;
				}
			
			#header h1 {
				padding: 0;
				margin: 0;
				color: #ccc;
				font-family: Georgia, serif;
				font-weight: normal;
				font-size: 180%;
				}
			
			/* navigation */
			
			#nav {
				float: left;
				width: 100%;
				margin: 0;
				padding: 0;
				list-style: none;
				background: #ccc;
				border-bottom: 1px solid #999;
				}
			
			#nav li { 
				float: left;
				margin: 0;
				padding: 0;
				}
			
			#nav a {
				float: left;
				display: block;
				padding: 6px 30px 6px 5px;
				text-decoration: none;
				font-weight: bold;
				font-size: 90%;
				color: #666;
				background: #ccc url(../img/nav_slant.gif) no-repeat top right;
				}
			
			#nav #nav-1 a {
				padding-left: 20px;
				}
			
			#nav a:hover {
				color: #000;
				}
			
			/* more */
			
			ul#more {
				clear: left;
				margin-top: 60px;
			}
		</style>
        
	</head>
	
<body>

 	<div id="header">
		<h1>Panel de Administraci&oacute;n - Sportivo Siniestros F.C.</h1>
	</div>

	<ul id="nav">
		<li id="nav-1"><a href='<?php echo site_url('admin/usuarios')?>'>Usuarios</a></li>
		<li id="nav-1"><a href='<?php echo site_url('admin/fechas')?>'>Fechas</a></li>
		<li id="nav-2"><a href='<?php echo site_url('admin/estados')?>'>Estados</a></li>
		<li id="nav-3"><a href='<?php echo site_url('admin/partidos')?>'>Partidos</a></li>
		<li id="nav-4"><a href='<?php echo site_url('admin/posiciones')?>'>Posiciones jugadores</a></li>
		<li id="nav-5"><a href='<?php echo site_url('admin/jugadores')?>'>Jugadores</a></li>
		<li id="nav-5"><a href='<?php echo site_url('admin/jugadores_estadisticas')?>'>Jugadores E</a></li>
		<li id="nav-6"><a href='<?php echo site_url('admin/equipos')?>'>Equipos</a></li>
		<li id="nav-7"><a href='<?php echo site_url('admin/torneos')?>'>Torneos</a></li>
		<li id="nav-7"><a href='<?php echo site_url('admin/tabla_posiciones')?>'>Tabla de posiciones</a></li>
		<li id="nav-7"><a href='<?php echo site_url('admin/goleadores')?>'>Goleadores</a></li>
		<li id="nav-7"><a href='<?php echo site_url('admin/galeria')?>'>Galeria</a></li>
	</ul>

	<ul id="more">
		
	</ul>
	
	<div style='height:20px;'></div>  
    
    <div>
		<?php echo $output; ?>
    </div>
    
</body>

</html>
