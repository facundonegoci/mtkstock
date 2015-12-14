<script>
	/**
	 * FUNCIONALIDADES PRODUCTOS
	 * 	Alta
	 *  Modificacion
	 *  Eliminacion
	 *  Listado
	 * 
	 * */

	/* 1 - LISTADO DE PRODUCTOS*/
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
	                "url": "<?php echo site_url($controller . '/ajax_list') ?>",
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
		});
		function reload_table()
	    {
	        table.ajax.reload(null, false); //reload datatable ajax
	    }
		/*2 - ALTA PRODUCTO */
		function add_obj() {
			
	        save_method = 'add';
	        $('#form')[0].reset(); // reset form on modals
	        $('#modal_form').modal('show'); // show bootstrap modal
	        $('.modal-title').text('Agregar'); // Set Title to Bootstrap modal title
	       
			setTimeout(function() {  $( "#codigo" ).focus(); }, 1000);
	    }
	    /*EDITAR PRODUCTO*/
	    function edit_obj(id)
	    {
	    	$('#errores').hide();
	         $('#errores').html('');
	    	
	        save_method = 'update';
	        $('#form')[0].reset(); // reset form on modals
	
	        //Ajax Load data from ajax
	        $.ajax({
	            url: "<?php echo site_url($controller . '/ajax_edit/') ?>/" + id,
	            type: "GET",
	            dataType: "JSON",
	            success: function (data)
	            {
	                $('[name="id"]').val(data.id);
	                $('[name="codigo"]').val(data.codigo);
	                $('[name="nombre"]').val(data.nombre);
	                $('[name="stock"]').val(data.stock);
	                $('[name="stock_minimo"]').val(data.stock_minimo);
	                $('[name="precio_venta_final"]').val(data.precio_venta_final);
	                $('[name="precio_venta_siniva"]').val(data.precio_venta_siniva);
	                $('[name="precio_mayorista"]').val(data.precio_mayorista);
	                $('[name="precio_mayorista_siniva"]').val(data.precio_mayorista_siniva);
	                $('[name="habilitado_venta"]').val(data.habilitado_venta);
	                $('[name="proveedores_id"]').val(data.proveedores_id);
	                $('[name="categorias_productos_id"]').val(data.categorias_productos_id);
	                $('[name="lugar_venta"]').val(data.lugar_venta);
					$('[name="unidad_venta"]').val(data.unidad_venta);
					$('[name="cantidad_por_venta"]').val(data.cantidad_por_venta);
	                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
	                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title
					setTimeout(function() {  $( "#codigo" ).focus(); }, 1000);
	
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                alert('Error get data from ajax');
	            }
	        });
	    }
	    function save()
	    {
	        var url;
	        if (save_method == 'add')
	        {
	            url = "<?php echo site_url($controller . '/ajax_add') ?>";
	        }
	        else
	        {
	            url = "<?php echo site_url($controller . '/ajax_update') ?>";
	        }
	
	        // ajax adding data to database
	        $.ajax({
	            url: url,
	            type: "POST",
	            data: $('#form').serialize(),
	            dataType: "JSON",
	            success: function (data)
	            {
	                if (data.status == false) {
	                    $('#errores').show();
	                    $('#errores').html('');
	                    $('#errores').html(data.errores);
	                } else {
	                    $('#errores').hide();
	                    $('#modal_form').modal('hide');
	                    reload_table();
	                    get_all_products();
	                }
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                alert('Error al guardar');
	            }
	        });
	    }
	
	    function delete_obj(id)
	    {
	        if (confirm('Esta seguro que desea eliminar?'))
	        {
	            // ajax delete data to database
	            $.ajax({
	                url: "<?php echo site_url($controller . '/ajax_delete') ?>/" + id,
	                type: "POST",
	                dataType: "JSON",
	                success: function (data)
	                {
	                    $('#modal_form').modal('hide');
	                    reload_table();
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
<div class="modal fade" id="modal_form" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Formulario <?= $controller ?></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Codigo</label>
                            <div class="col-md-7">
                                <input name="codigo" placeholder="Codigo" class="form-control" type="text" maxlength="45" required="required" id="codigo">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Nombre</label>
                            <div class="col-md-7">
                                <input name="nombre" placeholder="Nombre" class="form-control" type="text" maxlength="200" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Stock</label>
                            <div class="col-md-7" style="width: 140px">
                                <input name="stock" placeholder="0" class="form-control120" type="number" maxlength="20" required="required" style="text-align: right;">
                            </div>
                       <!-- </div>

                        <div class="form-group">-->
                            <label class="control-labelx col-md-4 label_left" style="width:75px;padding: 0">Stock min.</label>
                            <div class="col-md-7" style="width: 140px;padding-left: 7px">
                                <input name="stock_minimo" placeholder="0" class="form-control120" type="number" maxlength="20" required="required" style="text-align: right;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Precio venta final:</label>
                            <div class="col-md-7">
                                <input name="precio_venta_final" placeholder="0.00" class="form-control120" type="text" maxlength="10" required="required" style="text-align: right;">
                            </div>
                        </div>

						<div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Precio venta sin iva:</label>
                            <div class="col-md-7">
                                <input name="precio_venta_siniva" placeholder="0.00" class="form-control120" type="text" maxlength="10" required="required" style="text-align: right;">
                            </div>                       
                       </div>

                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Precio mayorista</label>
                            <div class="col-md-7">
                                <input name="precio_mayorista" placeholder="0.00" class="form-control120" type="text" maxlength="10" required="required" style="text-align: right;">
                            </div>
                        </div>

						<div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Precio mayorista sin iva</label>
                            <div class="col-md-7">
                                <input name="precio_mayorista_siniva" placeholder="0.00" class="form-control120" type="text" maxlength="10" required="required" style="text-align: right;">
                            </div>
                        </div>

						 <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Unidad</label>
                            <div class="col-md-7" style="width: 140px">
                                <?php
								$css = 'style="width:120px;"';
								$unidad_venta = array('peso' => 'Peso', 'unidad' => 'Unidad');

								echo form_dropdown('unidad_venta', $unidad_venta,'', $css);
                                ?>
                            </div>

                            <label class="control-labelx col-md-4 label_left" style="width:75px;padding: 0">Cant por venta.</label>
                            <div class="col-md-7" style="width: 140px;padding-left: 7px">
                                <input name="cantidad_por_venta" class="form-control120" type="number" maxlength="11" required="required" style="text-align: right;">
                            </div>
                        </div>
			   

                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Categoria</label>
                            <div class="col-md-7" style="width: 140px">
                                <?php echo form_dropdown('categorias_productos_id', $categorias, '', $css); ?>
                            </div>
                        
                            <label class="control-labelx col-md-4 label_left" style="width:72px;padding: 0">Proveedor</label>
                            <div class="col-md-7"  style="width: 140px;padding-left: 7px">
                                <?php echo form_dropdown('proveedores_id', $proveedores, '', $css); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-labelx col-md-4 label_left">Habilitado venta</label>
                            <div class="col-md-7" style="width: 140px">
                                <?php
                                $hab_venta = array("si" => "Si", "no" => "No");
                                echo form_dropdown('habilitado_venta', $hab_venta, '', $css);
                                ?>
                            </div>
                       
                            <label class="control-labelx col-md-4 label_left" style="width:72px;padding: 0">Lugar venta</label>
                            <div class="col-md-7" style="width: 140px;padding-left: 7px">
                                <?php
								$lugar_venta = array('b' => 'Barra', 'c' => 'Cocina', 'k' => 'Kiosco');

								echo form_dropdown('lugar_venta', $lugar_venta,'', $css);
                                ?>
                            </div>
                        </div>

                        <div class="alert alert-danger" id="errores" style="display:none;"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->