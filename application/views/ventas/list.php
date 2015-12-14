<button class="btn btn-success" onclick="document.location='<?php echo site_url('ventas') ?>'"><i class="glyphicon glyphicon-plus"></i>NUEVA VENTA</button>

<br />
<br />

<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Numero</th>
            <th>Fecha</th>
			<th>Monto</th>
			<th>Usuario</th>
            <th style="width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <th>Numero</th>
            <th>Fecha</th>
			<th>Monto</th>
			<th>Usuario</th>
            <th>Acciones</th>
        </tr>
    </tfoot>
</table>


<script type="text/javascript">

    var save_method; //for save method string
    var table;

	<?php $controller = 'ventas'; ?>

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
			"order": [[ 0, "desc" ]],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });
    });

    function add_obj()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Agregar'); // Set Title to Bootstrap modal title
    }

    function edit_obj(id)
    {
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
                $('[name="nombre"]').val(data.nombre);


                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Editar'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax
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
                if (data.status==false) {
                    $('#errores').show();    
                    $('#errores').html('');
                    $('#errores').html(data.errores);
                } else {
                     $('#errores').hide();
                    $('#modal_form').modal('hide');
                    reload_table();
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

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al eliminar');
                }
            });

        }
    }

</script>