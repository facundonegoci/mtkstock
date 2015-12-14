<script>

            
    $(document).ready(function () {
	
        table = $('#table').DataTable({
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
                "url": "<?php echo site_url($controller . '/get_promociones') ?>",
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
    	if(save_method == 'update'){
    		
    		var newUrl = "<?php echo site_url($controller . '/get_items') ?>/"+$("#id").val();
		}else{
			
			var newUrl = "<?php echo site_url($controller . '/get_items') ?>";
		}

		if(typeof table_items === 'object'){
			table_items.ajax.url(newUrl).load();
		}else{
			get_items(newUrl);
		}
 
    }
    function reload_table_movimientos()
    {
       table.ajax.reload(null, false); //reload datatable ajax
    }
    function delete_item(id, monto)
    {
        if (confirm('Esta seguro que desea eliminar?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url($controller . '/delete_item') ?>/" + id,
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

    function add_promocion()
    {
    	delete_items_huerfanos();
    	
        save_method = 'add';
        
        $('#form_stock_cabecera')[0].reset(); // reset form on modals

		$('.modal-title2').text('Agregar Promocion'); // Set Title to Bootstrap modal title

        $('#modal_form_stock').modal('show'); // show bootstrap modal
        
        $("#codigo").focus();
        
        reload_table_items();
   
    }

	function delete_items_huerfanos(){
		 
		 var url = "<?php echo site_url($controller . '/delete_items_huerfanos') ?>";
       
		 $.ajax({
            url: url,
            type: "POST",
            
            dataType: "JSON",
            success: function (data)
            {
              
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al eliminar basura');
            }
        });
	}

    function save_actualizacion() {
        
        reset_errores2();
        
        if(save_method == 'add'){ 
        	var url = "<?php echo site_url($controller . '/save_promocion') ?>";
        	
        	if(cant_items ==0){ alert("Debe cargar items"); return false; }
        	
        }else{
        	var url = "<?php echo site_url($controller . '/update_promocion') ?>";
        }
       
	    //para grabar correctamente el codigo de la promocion:
		var tmpc = $("#codigo_promocion").val();
		
		if (tmpc.substring(0, 3) != "99-") {
			var tmpc = "99-"+tmpc;
		
			$("#codigo_promocion").val(tmpc);
		}

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
    	
    	if(save_method == 'update'){
    		
    		  var url = "<?php echo site_url($controller . '/save_item') ?>/"+$("#id").val();
    	}
		else{
			  var url = "<?php echo site_url($controller . '/save_item') ?>";
		}
        reset_errores2();

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
						if(typeof table_items === 'object'){ reload_table_items(); }else{ get_items("<?php echo site_url($controller . '/get_items') ?>"); }	
                    }

                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al guardar el item');
            }
        });
    }
    function reset_form_items() {
        $('[name="nombre"]').val('');
        $('[name="precio"]').val('');
        $('[name="idproducto"]').val('');
        $('[name="cantidad"]').val('');
        $('[name="total_item"]').val('');
        $('[name="codigo"]').val('');
        $("#precio_s").css('background', '#fff');
    }
    function reset_form_actualizacion() {
		$('[name="codigo_promocion"]').val('');
		$('[name="nombre_promocion"]').val('');
        $('[name="monto"]').val('0.00');
        $('[name="numero_factura"]').val('');
        reset_errores2();
    }
    function reset_errores2() {
        $('#errores2').html('');
        $('#errores2').hide();
    }
    function get_all_products() {

        var url = "<?php echo site_url($controller . '/get_all_products') ?>";
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
                alert('Error al obtener los productos');
            }
        });
    }
    function get_data_producto(value) {

        var l = productos.length;

        var encontro = false;
        
        i = 0;
        
        while ((encontro == false) && (i < l)) {

            if ((productos[i].codigo == value)) {
                encontro = true;
                $('[name="codigo"]').val(productos[i].codigo);
                $('[name="nombre"]').val(productos[i].nombre);
                $('[name="precio"]').val(productos[i].precio_venta_final);
                $('[name="idproducto"]').val(productos[i].id);
            }
            i++;
        }
        if(!encontro){
        	alert("El codigo del producto no se han encontrado.");
        	reset_form_items();
        }
    }
    function set_monto_item() {

        var cant = $("#cantidad").val();

        var precio_m = $("#precio").val();

        cant = parseInt(cant);

        precio_m = parseFloat(precio_m);

        var tot = cant * precio_m;

        tot = tot.toFixed(2);

        $("#total_item").val(tot);
	
    }
    function set_monto_total() {
 
        var monto_total = $("#monto_original").val();

        var tot = $("#total_item").val();

        tot = parseFloat(tot);

        monto_total = parseFloat(monto_total);

        monto_total = (monto_total + tot).toFixed(2);

        $("#monto_original").val(monto_total);
        
        $("#monto_final").val(monto_total);
        
        aplicar_descuento($("#descuento").val());
    
    }
    function descontar_monto_total(m) {
    
        var monto_total = $("#monto_original").val();

        m = parseFloat(m);

        monto_total = parseFloat(monto_total);

        monto_total = (monto_total - m).toFixed(2);

        $("#monto_original").val(monto_total);
        
        $("#monto_final").val(monto_total);
        
        aplicar_descuento($("#descuento").val());
	 
    }
    function get_items(url) {
    	//alert(url);
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
                "url": url,
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
    	var precio_anterior = parseFloat($("#precio").val());
    	
    	if(precio_anterior != precio_n){
    		
    		$("#precio").css('background', 'red');
    	}
    }
    function delete_obj(id){
    	 
    	 if (confirm('Esta seguro que desea eliminar?'))
	        {
	            // ajax delete data to database
	            $.ajax({
	                url: "<?php echo site_url($controller . '/ajax_delete') ?>/" + id,
	                type: "POST",
	                dataType: "JSON",
	                success: function (data)
	                {

	                    reload_table_movimientos();
	            
	
	                },
	                error: function (jqXHR, textStatus, errorThrown)
	                {
	                    alert('Error al eliminar');
	                }
	            });
	
	        }
    }
    function aplicar_descuento(descuento){
    	
    	if(descuento>0)    	
    	{
    		var monto_original = parseFloat($("#monto_original").val());
    	
    		var tmp_per = ((parseFloat(descuento)*monto_original)/100).toFixed(2);

			var mf = monto_original - tmp_per;

	        $("#monto_final").val(mf);
        
       }
    }

    function get_data_producto_by_name(str){

    	var tmp = str.split("|");
    	if(tmp[1])
    		get_data_producto(tmp[1]);
    }
     function edit_obj(id)
	 {
        delete_items_huerfanos();
        
        save_method = 'update';

		$('#form_stock_cabecera')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url($controller . '/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
            	
                $('[name="id"]').val(data.id);
                $('[name="fecha_inicio"]').val(data.fecha_inicio);
                $('[name="nombre_promocion"]').val(data.nombre);
				$('[name="codigo_promocion"]').val(data.codigo);
                $('[name="fecha_fin"]').val(data.fecha_fin);
                $('[name="monto_original"]').val(data.monto_original);
                $('[name="monto_final"]').val(data.monto_final);
                $('[name="descuento"]').val(data.descuento);

               	$('.modal-title2').text('Editar Promocion'); // Set Title to Bootstrap modal title
			
			    $('#modal_form_stock').modal('show'); // show bootstrap modal
			        
			    $("#codigo").focus();
			    reload_table_items();

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
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
                <form class="form-inline" action="#" id="form_stock_cabecera" onsubmit="return false" style="border: 1px solid #AAA; padding: 5px">
					<input type="hidden"  id="tipo" name="tipo" >
					<div class="form-group">
                        <label for="nombre_promocion">Codigo</label><br>
                       <span class="glyphicon glyphicon-warning-sign" aria-hidden="true" title="El sistema por defecto coloca al comienzo del codigo ingresado 99-"></span><input type="text" class="form-control" name="codigo_promocion" id="codigo_promocion" style="width: 165px" value="">
                        <input type="hidden" class="form-control" name="id" id="id" style="width: 200px" value="">
                    </div>
					 <div class="form-group">
                        <label for="nombre_promocion">Nombre</label><br>
                        <input type="text" class="form-control" name="nombre_promocion" style="width: 270px" value="">
                        <input type="hidden" class="form-control" name="id" id="id" style="width: 200px" value="">
                    </div>
                    <div class="form-group">
                    	
                        <label for="fecha_inicio">Fecha inicio</label><br>
                        <input type="text" class="form-control" name="fecha_inicio" style="width: 100px" value="<?= date("Y-m-d"); ?>">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha fin</label><br>
                        <input type="text" class="form-control" name="fecha_fin" style="width: 100px" id="fecha_fin">
                    </div>
					<br><br>
                    <div class="form-group">
                        <label for="monto_original">Monto original</label><br>
                        <input type="text" class="form-control" name="monto_original" id="monto_original" style="width: 150px;text-align:right;font-size: 20px;" value="0.00" readonly="">
                    </div>
                    
                     <div class="form-group">
                        <label for="descuento">Descuento</label><br>
                        <input type="text" class="form-control" name="descuento" id="descuento" style="width: 150px;text-align:right;font-size: 20px;" value="0" onchange="aplicar_descuento(this.value);">
                    </div>

                    <div class="form-group">
                        <label for="monto_fnal">Monto final</label><br>
                        <input type="text" class="form-control" name="monto_final" id="monto_final" style="background:#d35400;color:#fff;font-weight:bold;width:150px;text-align:right;font-size:24px;" value="0.00">
                    </div>

                    <div class="form-group">
                        <label for=""></label><br>
                        <button type="submit" class="btn btn-success" onclick="save_actualizacion();" style="width: 200px;font-weight: bold;font-size:20px">Grabar promocion</button>
                    </div>
                    
                    
                </form>    
                
                <hr>
		
                <form class="form-inline" action="#" id="form_stock_items" onsubmit="return false">
                    <input type="hidden"  id="idproducto" name="idproducto" >
                     <input type="hidden"  id="tipoi" name="tipoi" >
                    <div class="form-group">
                        <label for="codigo" class="label_stock">Codigo</label><br>
                        <input type="text" class="form-control_facu" id="codigo" name="codigo" placeholder="Codigo" style="width: 145px" onchange="get_data_producto(this.value);">
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="label_stock">Nombre</label><br>
                        <?php 
                        	//print_r($productos_nombres);
							$str = ''; 
                        	foreach ($productos_nombres as $key) {
								$str .='"'.$key->nombre.'",';
							}
							$str = substr($str, 0,-1);
							//echo $str;
							//data-source='["Dove Men Care - Extra Fresh|791293012063","Shampoo Caspa Control - Clear|7891150014367","Dove Men Care - Invisible Dry|7791293022819","Magdalenas con chips 250g - Pozo|7790077000722","Chocolate air Blanco - cofler|7790580103415","Dove men care Silver control|7791293018874","Knorr Quick Zapallo|7794000598508"]'
                        ?>
                        <input type="text" class="form-control_facu" id="nombre" name="nombre" data-source='[<?php echo $str; ?>]'  data-provide="typeahead" autocomplete="off" onchange="get_data_producto_by_name(this.value);">
                    </div>

                    <div class="form-group">
                        <label for="cantidad" class="label_stock">Cantidad</label><br>
                        <input type="number" class="form-control_facu" id="cantidad" name="cantidad" placeholder="Cantidad" style="width: 75px" onchange="set_monto_item(this.value);">
                    </div>
  
                    <div class="form-group">
                        <label for="precio" class="label_stock">Precio</label><br>
                        <input type="text" class="form-control_facu" id="precio" name="precio"  placeholder="Precio" style="width: 80px" onchange="set_monto_item($('#cantidad').val());verify_precio(this.value)" readonly="">
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
                            <th  style="width:100px;">Codigo</th>
                            <th style="WIDTH: 250px">Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th style="width:100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

		
                <div class="alert alert-danger" id="errores2" style="display:none;"></div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->