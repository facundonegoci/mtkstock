<div class="alert alert-success" role="alert" style="font-size: 25px;font-weight: bold;display:none;" id="success_v">VENTA GRABADA CORRECTAMENTE</div>
<div class="alert alert-danger" role="alert"  style="font-size: 25px;font-weight: bold;display:none;" id="error_v">OCURRIO UN ERROR AL GRABAR LA VENTA</div>
<div class="modal-body form">
	
	<form class="form-inline" action="#" id="form_stock_cabecera" onsubmit="return false">
		<input type="hidden"  id="items_i" name="items_i" >
		
		<div class="form-group">
			<label for="fecha">Vendedor</label>
			<br>
			<input type="text" class="form-control" name="vendedor" style="width: 165px" value="<?= $usuarioactual; ?>" readonly>
		</div>
		
		<div class="form-group">
			<label for="fecha">Fecha</label>
			<br>
			<input type="text" class="form-control" name="fecha" style="width: 165px" value="<?= date("Y-m-d"); ?>" readonly>
		</div>

		<div class="form-group">
			<label for="monto">Monto</label>
			<br>
			<input type="text" class="form-control" name="monto_total" id="monto_total" readonly style="height:45px;font-size:25px;text-align:right;background:#d35400;color:#fff;font-weight:bold;width:150px;" value="0.00">
		</div>

		<div class="form-group">
			<label for=""></label>
			<br>
			<button type="submit" class="btn btn-success" onclick="save_venta();" style="width: 150px;font-size: 20px;margin-top: 4px;">
				Grabar Venta
			</button>
		</div>

	</form>

	<hr>
	<fieldset>
		<form class="form-inline" action="#" id="form_stock_items" onsubmit="return false" style="border:1px solid #ccc; padding: 10px">
			<input type="hidden"  id="idproducto" name="idproducto" >
			
			<input type="hidden"  id="idproducto" name="idproducto" >
			<input type="hidden"  id="unidad_venta" name="unidad_venta" >
			<input type="hidden"  id="cantidad_por_venta" name="cantidad_por_venta" >
		
			<div class="form-group">
				<label for="codigo" class="">Codigo</label>
				<br>
				<input type="text" class="form-control" id="codigo" name="codigo"  style="width: 145px" onchange="get_data_producto(this.value);">
			</div>
			<div class="form-group">
				<label for="nombre" class="">Nombre</label>
				<br>
				 <?php 
          
					$str = ''; 
                	foreach ($productos_nombres as $key) {
						$str .='"'.$key->nombre.'",';
					}
					$str = substr($str, 0,-1);
					
                ?>
                <input type="text" class="form-control" id="nombre" name="nombre" data-source='[<?php echo $str; ?>]'  data-provide="typeahead" autocomplete="off" onchange="get_data_producto_by_name(this.value);">
			</div>

			<div class="form-group">
				<label for="precio_unitario" class="">Precio</label>
				<br>
				<input type="text" class="form-control" id="precio_unitario" name="precio_unitario"  style="width: 80px" readonly="">
			</div>

			<div class="form-group">
				<label for="cantidad" class="">Cantidad</label>
				<br>
				<input type="number" class="form-control" id="cantidad" name="cantidad"  style="width: 75px" onchange="set_monto_item(this.value);">
			</div>

			
			<div class="form-group" class="">
				<label for="precio_total" class="">Total</label>
				<br>
				<input type="text" class="form-control" id="precio_total" name="precio_total" style="color: #000;font-size: 20px; font-weight: bold;text-align: right; width: 120px;">
			</div>
			<div class="form-group">
				<label for=""></label>
				<br>
				<button type="submit" class="btn btn-danger" onclick="save_item();">
					Grabar
				</button>
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
					<th>Actualizar</th>
					<th style="width:100px;">Acciones</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</fieldset>
	<div class="alert alert-danger" id="errores2" style="display:none;"></div>

</div>

<script>

	<?php $controller = 'ventas'; ?>

	var productos;
	var items = [];
	var table_items;
	var indice = 0;
	var cantidad_items = 0;
	
	var miIndice = 0;
	
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
	
	function get_all_promociones() {

        var url = "<?php echo site_url($controller . '/get_all_promociones') ?>";
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            success: function (data)
            {
                productos = productos.concat(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al obtener los productos');
            }
        });
    }
	
    function get_data_producto_by_name(str){

    	var tmp = str.split("|");
    	if(tmp[1])
    		get_data_producto(tmp[1]);
    }
   
    function get_data_producto(value) {

        var l = productos.length;

        var encontro = false;
        
        i = 0;
        
        while ((encontro == false) && (i < l)) {

            if ((productos[i].codigo == value)) {
                encontro = true;
                $('#codigo').val(productos[i].codigo);
                $('#nombre').val(productos[i].nombre);
                $('#precio_unitario').val(productos[i].precio_venta_final);
                $('#idproducto').val(productos[i].id);
				$('#unidad_venta').val(productos[i].unidad_venta);
				$('#cantidad_por_venta').val(productos[i].cantidad_por_venta);
                $('#cantidad').val(productos[i].cantidad_por_venta);
                set_monto_item();
            }
            i++;
        }
        if(!encontro){
        	alert("El codigo del producto no se han encontrado.");
        	//reset_form_items();
        }
    }
    function set_monto_item() {

        var cant = $("#cantidad").val();

		var cantidad_por_venta = $('#cantidad_por_venta').val();
		
        var precio_m = $("#precio_unitario").val();

        cant = parseInt(cant);
		
		cantidad_por_venta = parseInt(cantidad_por_venta);

        precio_m = parseFloat(precio_m);

        var tot = (cant * precio_m)/cantidad_por_venta;

        tot = tot.toFixed(2);

        $("#precio_total").val(tot);
	
    }
    function set_monto_total() {
 
        var monto_total = $("#monto_total").val();

        var tot = $("#precio_total").val();

        tot = parseFloat(tot);

        monto_total = parseFloat(monto_total);

        monto_total = (monto_total + tot).toFixed(2);

        $("#monto_total").val(monto_total);
    
    }
    function descontar_monto_total(m) {
    
        var monto_total = $("#monto_total").val();

        m = parseFloat(m);

        monto_total = parseFloat(monto_total);

        monto_total = (monto_total - m).toFixed(2);

        $("#monto_total").val(monto_total);
	 
    }
	function actualizar_cantidad_item(codigo, cant_nueva, indice) {
   
		$("#codigo").val(items[indice][0]);
		$("#nombre").val(items[indice][1]);
		$("#precio_unitario").val(items[indice][2]);
		$("#cantidad").val(cant_nueva);
		$("#idproducto").val(items[indice][7]);
		$("#unidad_venta").val(items[indice][9]);
		$("#cantidad_por_venta").val(items[indice][10]);
		
		set_monto_item(cant_nueva);
		
		delete_item(indice);
		
		save_item();
		
    }
    function save_item(){

    	var cantidad = parseInt($("#cantidad").val());
    	var codigo = $("#codigo").val();
    	var precio_unitario = $("#precio_unitario").val();
    	var precio_total = $("#precio_total").val();
    	var nombre = $("#nombre").val();
    	var idproducto = $("#idproducto").val();
		var unidad_venta = $("#unidad_venta").val();
		var cantidad_por_venta = $("#cantidad_por_venta").val();
    	
    	var del = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_item('+indice+')" style="padding:0px;">Eliminar</a>';
    	var update = '<input type="number" value="'+cantidad+'" onchange="actualizar_cantidad_item('+codigo+',this.value,'+indice+')" style="text-align: right; width: 80px;font-weight: bold;">';
    	
    	if(codigo ==''){ alert("Ingrese el codigo"); return false;}
    	if(cantidad ==''){ alert("Ingrese la cantidad"); return false;}
    	if(cantidad<=0){ alert("Ingrese correctamente la cantidad"); return false;}
    	
    	var item = [codigo, nombre, precio_unitario, cantidad, precio_total,update, del, idproducto, indice, unidad_venta, cantidad_por_venta];
    	
    	items.push(item);
    	
    	set_monto_total();
    	
    	if(typeof table_items === 'object'){
    		table_items.destroy();
			generate_table_items();
		}
		//$('#form_stock_items')[0].reset(); // reset form on modals
		
		$("#codigo").focus();
	
		$("#codigo").val("");

		$("#nombre").val("");

		$("#precio_initario").val(0.00);

		$("#precio_total").val(0.00);

		 $("#idproducto").val("");
		
		cantidad_items++;
		
		indice++;
    }
    function delete_item(i){
    	
    	var l = productos.length;

        var encontro = false;
        
        j = 0;
        
        while ((encontro == false) && (j < l)) {
        	
        	if(items[j][8] == i){
        		encontro = true;
        		descontar_monto_total(items[j][4]);
        		items.splice(j, 1);
				indice = indice -1;
        	}
        	j++;
        }
        cantidad_items--;
        table_items.destroy();
		generate_table_items();
    }
    
    function generate_table_items(){
    		table_items = $('#items').DataTable( {
	        data: items,
	        "destroy": true,
	        "bFilter": false,
            "paging": false,
            "ordering": false,
            "info": false,
	        columns: [
            { title: "Codigo" },
            { title: "Nombre" },
            { title: "Precio unitario" },
            { title: "Cantidad" },
            { title: "Total" },
			{ title: "Actualizar" },
            { title: "Accion" }
        ]
	    } );
	}
	function save_venta(){
		
		var cantidad_items = items.length;
		var tmp_i;
		for(i=0;i<cantidad_items;i++){
			tmp_i = items[i]; 
			tmp_i.splice(5, 1);
			items[i] = tmp_i;
		}
		
		if(cantidad_items >0){
			
		    var items_s = JSON.stringify( items );
		    
			$("#items_i").val(items_s);
			
			var url = "<?php echo site_url($controller . '/ajax_add') ?>";
			// ajax adding data to database
			var form = $('#form_stock_cabecera').serialize();
			
	       $.ajax({
	       	
	            url: url,
	            
	            type: "POST",
	            
	           // data: {"cabecera": $('#form_stock_cabecera').serialize(), "items": items_s},
	            data:  form,
	            
	            dataType: "JSON",
	            
	            success: function (data)
	            {
	                if (data.status == false) {
	                	
	                    $('#errores2').show();
	
	                    $('#errores2').html(data.errores);
	              
	                    $( "#error_v" ).show( "pulsate", {}, 1000, callback2 );
	                    
	                } else {
			   		    $( "#success_v" ).show( "pulsate", {}, 1000, callback );
	
			   			cantidad_items = 0;
	                    
	                    items = [];
	                    
	                   // reset_errores2();
	                   
	                    $('#form_stock_cabecera')[0].reset(); // reset form on modals
	                    
	                    table_items.destroy();
						
						generate_table_items();
						
						$("#codigo").focus();
	                }
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                alert('Error al guardar');
	            }
        });
		}else{
			
			alert("Debe cargar articulos");
			
			return false;
		}
		
	}

	function callback() {
      setTimeout(function() {
        $( "#success_v:visible" ).fadeOut();
      }, 1000 );
    }

	function callback2() {
      setTimeout(function() {
        $( "#error_v:visible" ).fadeOut();
      }, 1000 );
    }

    $(document).ready(function () {
    	
    	get_all_products();
    	
    	generate_table_items();
		
		get_all_promociones();
		
		$(document).keydown(function (e) {

			if(e.which===17){ save_venta(); }
			
		});
    	
    });
</script>
