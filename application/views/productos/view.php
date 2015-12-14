<style>

    table#items td{  padding: 0px;margin:0px;}
    table#table td{  padding: 1px;margin:0px;}
    table#table_movimientos td{  padding: 1px;margin:0px;}
    .datatable-scroll {
    overflow-x: auto;
    overflow-y: visible;
}

</style>
<button class="btn btn-success" onclick="add_obj()"><i class="glyphicon glyphicon-plus"></i>AGREGAR PRODUCTO</button>
<button class="btn btn-warning" onclick="add_stock('c')"><i class="glyphicon glyphicon-plus"></i>CARGAR STOCK</button>
<button class="btn btn-danger" onclick="add_stock('d')"><i class="glyphicon glyphicon-minus"></i>DEVOLUCION</button>
<button class="btn btn-danger" onclick="document.location='<?php echo site_url('productos/descargar_excel') ?>'"><i class="glyphicon glyphicon-download"></i>EXPORTAR PRODUCTOS</button>
<br />
<br />

<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#productos" data-toggle="tab">Productos</a></li>
    <li role="presentation"><a href="#movimientos" data-toggle="tab">Movimientos Stock</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="productos">
        <br>
        <table id="table" class="table table-striped table-bordered" cellspacing="0"  style="width:100%; font-size: 12px;">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Stock min.</th>
                    <th>Precio venta</th>
                    <th>Precio may.</th>
                    <th>Ganancia</th>
                   <!-- <th>Categoria</th>-->
                    <th style="width:60px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Stock min.</th>
                    <th>Precio venta</th>
                    <th>Precio may.</th>
                    <th>Ganancia</th>
                  <!--  <th>Categoria</th>-->
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
   
    </div>
    
    <div role="tabpanel" class="tab-pane" id="movimientos">
        <br>
        <table id="table_movimientos" class="table table-striped table-bordered" cellspacing="0"  style="width:100%; font-size: 12px;">
            <thead>
                <tr>
                    <th style="width:200px;">Fecha</th>
                    <th>N&deg; Factura</th>
                    <th>Monto</th>
                    <th>Proveedor</th>
                    <th>Tipo mov.</th>
               		 <th style="width:60px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>N&deg; Factura</th>
                    <th>Monto</th>
                    <th>Proveedor</th>
                    <th>Tipo mov.</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
        
    </div>
</div>    

<script type="text/javascript">

    var save_method; //for save method string
    var table;
    var productos;
    var save_item_cont = 0;
    var cant_items = 0;
    var accion_anterior = '';
    var es_devolucion = false;
    
    <?php $controller = 'productos'; ?>
    
    <?php $controller_movimientos = 'movimientos_stock'; ?>

	  $(document).ready(function () {
    	
    	$('#form, #form_stock_cabecera, #form_stock_items').on('keyup keypress', function(e) {
			  var code = e.keyCode || e.which;
			  if (code == 13) { 
			    e.preventDefault();
			    return false;
			  }
			});
	});

	/**
	 * FUNCIONALIDADES PRODUCTOS
	 * 	Alta
	 *  Modificacion
	 *  Eliminacion
	 *  Listado
	 * 
	 * */
    
</script>
<?php include_once('productos.php'); ?>
<?php include_once('movimientos.php'); ?>