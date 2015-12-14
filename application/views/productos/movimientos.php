<script>
    $(document).ready(function () {
	
        table_movimientos = $('#table_movimientos').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
              
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url($controller_movimientos . '/get_movimientos') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });
    
        get_all_products();
    });
    
    function reload_table_items()
    {
        table_items.ajax.reload(null, false); //reload datatable ajax
    }
    function reload_table_movimientos()
    {
        table_movimientos.ajax.reload(null, false); //reload datatable ajax
    }
    function delete_item(id, monto)
    {
        if (confirm('Esta seguro que desea eliminar?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url($controller_movimientos . '/delete_item') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
		    cant_items = cant_items-1;
                    reload_table_items();
                    descontar_monto_total(monto);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al eliminar');
                }
            });

        }
    }

    function add_stock(tipox)
    {
        save_method = 'add';

        $('#modal_form_stock').modal('show'); // show bootstrap modal
        
        if(tipox=='c'){
        	
        	es_devolucion = false;
        	
        	if(accion_anterior=='d'){
        		
        			delete_items_huerfanos();
        		
        			reload_table_movimientos();
                    
                    reset_form_actualizacion();

                   if(typeof table_items !== 'undefined')  table_items.clear().draw();
                    
                    $("#numero_factura").val('');
        		}
        	
        	accion_anterior = 'c';
        	
        	$("#tipo").val('c');

			$("#tipoi").val('c');	
			
        	$('.modal-title2').text('Actualizacion de stock'); // Set Title to Bootstrap modal title
        	
        }else{
        	if(tipox=='d'){
        		
        		es_devolucion = true;
        		
        		if(accion_anterior=='c'){
        			
        			delete_items_huerfanos();
        			
        			reload_table_movimientos();
                    
                    reset_form_actualizacion();

                   if(typeof table_items !== 'undefined') table_items.clear().draw();
        		}
        		
        		accion_anterior = 'd';
      			
      			$("#tipo").val('d');
      			
      			$("#tipoi").val('d');
      			
      			$("#numero_factura").val('-9999');
      			
      			$('.modal-title2').text('Devolución'); // Set Title to Bootstrap modal title  		
        	}
        }
      
        $("#codigo_s").focus();
    }

	function delete_items_huerfanos(){
		 
		 var url = "<?php echo site_url($controller_movimientos . '/delete_items_huerfanos') ?>";
       
		 $.ajax({
            url: url,
            type: "POST",
            
            dataType: "JSON",
            success: function (data)
            {
              
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error');
            }
        });
	}

    function save_actualizacion() {
        
        reset_errores2();
        
        var url = "<?php echo site_url($controller_movimientos . '/actualizar_stock') ?>";
        
        if(cant_items ==0){ alert("Debe cargar items"); return false; }
        
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_stock_cabecera').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status == false) {
                    $('#errores2').show();

                    $('#errores2').html(data.errores);
                    
                } else {
		    cant_items = 0;
                    reset_errores2();
                    
                    $('#modal_form_stock').modal('hide');
                    
                    reload_table();
                    
                    get_all_products();
                    
                    reload_table_movimientos();
                    
                    reset_form_actualizacion();

                    table_items.clear().draw();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }
    function save_item() {

        reset_errores2();
        
        var url = "<?php echo site_url($controller_movimientos . '/save_item') ?>";
        
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_stock_items').serialize(),
            dataType: "JSON",
            success: function (data)
            {

                if (data.status == false) {
                    
                    $('#errores2').show();
                    
                    $('#errores2').html(data.errores);
                    
                } else {
		    cant_items = cant_items+1;
                    save_item_cont = save_item_cont + 1;

                    set_monto_total();

                    reset_form_items();

                    reset_errores2();

                    if (save_item_cont > 1) {

                        reload_table_items();

                    } else {

                        get_items();
                    }

                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }
    function reset_form_items() {
        $('[name="nombre_s"]').val('');
        $('[name="stocka_s"]').val('');
        $('[name="precioa_s"]').val('');
        $('[name="precio_s"]').val('');
        $('[name="idproducto"]').val('');
        $('[name="cantidad"]').val('');
        $('[name="total_item"]').val('');
        $('[name="codigo_s"]').val('');
        $("#precio_s").css('background', '#fff');
    }
    function reset_form_actualizacion() {
        $('[name="monto"]').val('0.00');
        $('[name="numero_factura"]').val('');
        reset_errores2();
    }
    function reset_errores2() {
        $('#errores2').html('');
        $('#errores2').hide();
    }
    function get_all_products() {

        var url = "<?php echo site_url($controller_movimientos . '/get_all_products') ?>";
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            success: function (data)
            {
                productos = data;
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar');
            }
        });
    }
    function get_data_producto(value) {

        var l = productos.length;

        var encontro = false;
        
        var proveedor_id = $("[name='proveedor_s']").val();
       // alert(proveedor_id);

        i = 0;
        while ((encontro == false) && (i < l)) {

            if ((productos[i].codigo == value)&&(productos[i].proveedores_id == proveedor_id)) {
                encontro = true;
                $('[name="nombre_s"]').val(productos[i].nombre);
                $('[name="stocka_s"]').val(productos[i].stock);
                $('[name="precioa_s"]').val(productos[i].precio_mayorista);
                $('[name="precio_s"]').val(productos[i].precio_mayorista);
                $('[name="idproducto"]').val(productos[i].id);
            }
            i++;
        }
        if(!encontro){
        	alert("El codigo del producto no se han encontrado. Verifique el codigo o el proveedor seleccionado.");
        	reset_form_items();
        }
    }
    function set_monto_item() {
    	
    	if(!es_devolucion){
    	
	        var cant = $("#cantidad").val();
	
	        var precio_m = $("#precio_s").val();
	
	        cant = parseInt(cant);
	
	        precio_m = parseFloat(precio_m);
	
	        var tot = cant * precio_m;
	
	        tot = tot.toFixed(2);
	
	        $("#total_item").val(tot);
	   }else{
	   	 $("#total_item").val(0.00);
	   }
    }
    function set_monto_total() {
        
       if(!es_devolucion){
        
	        var monto_total = $("#monto").val();
	
	        var tot = $("#total_item").val();
	
	        tot = parseFloat(tot);
	
	        monto_total = parseFloat(monto_total);
	
	        monto_total = (monto_total + tot).toFixed(2);
	//alert(monto_total);
	        $("#monto").val(monto_total);
      }else{
		//alert(1);
      	$("#monto").val(0.00);
      }
    }
    function descontar_monto_total(m) {
    	
    	if(!es_devolucion){
    	
	        var monto_total = $("#monto").val();
	
	        m = parseFloat(m);
	
	        monto_total = parseFloat(monto_total);
	
	        monto_total = (monto_total - m).toFixed(2);
	
	        $("#monto").val(monto_total);
	    }{
	    	$("#monto").val(0.00);
	    }
    }
    function get_items() {
        table_items = $('#items').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "bFilter": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url($controller_movimientos . '/get_items') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });
    }
    function verify_precio(precio_n){
    	precio_n = parseFloat(precio_n);
    	var precio_anterior = parseFloat($("#precioa_s").val());
    	
    	if(precio_anterior != precio_n){
    		
    		$("#precio_s").css('background', 'red');
    	}
    }
    function delete_movimiento_obj(id){
    	 
    	 if (confirm('Esta seguro que desea eliminar?'))
	        {
	            // ajax delete data to database
	            $.ajax({
	                url: "<?php echo site_url($controller_movimientos . '/ajax_delete') ?>/" + id,
	                type: "POST",
	                dataType: "JSON",
	                success: function (data)
	                {
	                    reload_table();
	                    reload_table_movimientos();
	                    get_all_products();
	
	                },
	                error: function (jqXHR, textStatus, errorThrown)
	                {
	                    alert('Error al eliminar');
	                }
	            });
	
	        }
    }
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_stock" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title2"></h3>

            </div>
            <div class="modal-body form">
                <form class="form-inline" action="#" id="form_stock_cabecera" onsubmit="return false">
					<input type="hidden"  id="tipo" name="tipo" >
                    <div class="form-group">
                        <label for="fecha">Fecha</label><br>
                        <input type="text" class="form-control" name="fecha" style="width: 165px" value="<?= date("Y-m-d"); ?>">
                    </div>
                    <div class="form-group">
                        <label for="numero_factura">N&deg; Factura</label><br>
                        <input type="text" class="form-control" name="numero_factura" placeholder="N° Factura" id="numero_factura">
                    </div>

                    <div class="form-group">
                        <label for="proveedor_s">Proveedor</label><br>
                        <?php echo form_dropdown('proveedor_s', $proveedores, '', 'class="form-control"'); ?>
                    </div>

                    <div class="form-group">
                        <label for="monto">Monto</label><br>
                        <input type="text" class="form-control" name="monto" id="monto" placeholder="Monto" style="width: 100px" value="0.00">
                    </div>

                    <div class="form-group">
                        <label for=""></label><br>
                        <button type="submit" class="btn btn-success" onclick="save_actualizacion();">Grabar Actualizacion stock</button>
                    </div>
                    
                    
                </form>    
                
                <hr>
		<fieldset>
                <form class="form-inline" action="#" id="form_stock_items" onsubmit="return false">
                    <input type="hidden"  id="idproducto" name="idproducto" >
                     <input type="hidden"  id="tipoi" name="tipoi" >
                    <div class="form-group">
                        <label for="codigo_s" class="label_stock">Codigo</label><br>
                        <input type="text" class="form-control_facu" id="codigo_s" name="codigo_s" placeholder="Codigo" style="width: 145px" onchange="get_data_producto(this.value);">
                    </div>
                    <div class="form-group">
                        <label for="nombre_s" class="label_stock">Nombre</label><br>
                        <input type="text" class="form-control_facu" id="nombre_s" name="nombre_s" placeholder="Nombre" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stocka_s" class="label_stock">Stock Act.</label><br>
                        <input type="number" class="form-control_facu" id="stocka_s" name="stocka_s" placeholder="Stock" style="width: 75px" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stock_s" class="label_stock">Cantidad</label><br>
                        <input type="number" class="form-control_facu" id="cantidad" name="cantidad" placeholder="Cantidad" style="width: 75px" onchange="set_monto_item(this.value);">
                    </div>

                    <div class="form-group">
                        <label for="precioa_s" class="label_stock">Precio Act.</label><br>
                        <input type="text" class="form-control_facu" id="precioa_s" name="precioa_s" placeholder="Precio" style="width: 80px" readonly>
                    </div>        
                    <div class="form-group">
                        <label for="precio_s" class="label_stock">Precio</label><br>
                        <input type="text" class="form-control_facu" id="precio_s" name="precio_s"  placeholder="Precio" style="width: 80px" onchange="set_monto_item($('#cantidad').val());verify_precio(this.value)">
                    </div>
                    <div class="form-group" class="label_stock">
                        <label for="precio_s" class="label_stock">Total</label><br>
                        <input type="text" class="form-control_facu" id="total_item" name="total_item" style="width: 100px">
                    </div>
                    <div class="form-group">
                        <label for=""></label><br>
                        <button type="submit" class="btn btn-danger" onclick="save_item();">Grabar</button>
                    </div>
                </form>
		
                <br>  
                <table id="items" class="table table-striped table-bordered" cellspacing="0" style="width:100%; font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th style="width:100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
		</fieldset>
                <div class="alert alert-danger" id="errores2" style="display:none;"></div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->