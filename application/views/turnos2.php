<!doctype html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Basic initialization</title>
    <link href="<?php echo base_url(); ?>css/admin/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>js/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dhtmlxscheduler.css" type="text/css" media="screen" title="no title" charset="utf-8">
</head>




<style type="text/css" media="screen">
    html, body{
        margin:0px;
        padding:0px;
        height:100%;
        overflow:hidden;
    }	
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
    .nav-sidebar > .active > a,
    .nav-sidebar > .active > a:hover,
    .nav-sidebar > .active > a:focus {
        color: #fff;
        background-color: #428bca;
    }
    .main {
        padding: 20px;
    }
    @media (min-width: 768px) {
        .main {
            padding-right: 40px;
            padding-left: 40px;
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
    .datatables div.form-div input[type="text"], .datatables div.form-div input[type="password"] { padding: 0px}    
    html, body{
        height:100%;
        overflow:hidden;
    }
</style>

<script type="text/javascript" charset="utf-8">
    function init() {
        scheduler.config.xml_date = "%Y-%m-%d %H:%i";
        scheduler.init('scheduler_here', new Date(2015, 0, 10), "week");
        scheduler.load("<?php echo base_url(); ?>data/events.xml");

    }
</script>

<body onload="init();">

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">FUTBOL5</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
                <form class="navbar-form navbar-right">
                    <input type="text" class="form-control" placeholder="Search...">
                </form>
            </div>
        </div>
    </nav>
    
     <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">

                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">MENU <span class="sr-only"></span></a></li>

                        <li><a href="<?php echo site_url('admin/clientes') ?>"><i class="fa fa-dashboard"></i> Clientes</a></li>
                        <li><a href="<?php echo site_url('admin/servicios') ?>"><i class="fa fa-edit"></i>Servicios</a></li>
                        <li><a href="<?php echo site_url('admin/usuarios') ?>"><i class="fa fa-edit"></i> Usuarios</a></li>
                    </ul>

                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="height:900px">
                    <h1 class="page-header"><?php echo strtoupper($this->uri->segment(2)); ?></h1>
                     <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
                        <div class="dhx_cal_navline">
                            <div class="dhx_cal_prev_button">&nbsp;</div>
                            <div class="dhx_cal_next_button">&nbsp;</div>
                            <div class="dhx_cal_today_button"></div>
                            <div class="dhx_cal_date"></div>
                            <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                            <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                            <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                        </div>
                        <div class="dhx_cal_header">
                        </div>
                        <div class="dhx_cal_data">
                        </div>
                    </div>
                </div>
            </div>
        </div>
       

    
</body>