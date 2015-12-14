
<button class="btn btn-success" onclick="add_promocion()"><i class="glyphicon glyphicon-plus"></i>AGREGAR PROMOCION</button>
<br />

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="productos">
        <br>
        <table id="table" class="table table-striped table-bordered" cellspacing="0"  style="width:100%; font-size: 12px;">
            <thead>
                <tr>
					<th>Codigo</th>
                    <th>Nombre</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Monto final</th>
                    <th>Monto original</th>
                   
                    <th style="width:60px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                   <th>Codigo</th>
                   <th>Nombre</th>
                   <th>Fecha inicio</th>
                   <th>Fecha fin</th>
                   <th>Monto final</th>
                   <th>Monto original</th>
                   <!--  <th>Categoria</th>-->
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
    
    <?php $controller = 'promociones'; ?>
    
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
<?php include_once('promociones.php'); ?>