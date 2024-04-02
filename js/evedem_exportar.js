

/*=============================================
EVALUACIÓN
=============================================*/
function llamarBecasTBL(){
   let anoPer = $('select[name="cmbPeriodos"] option:selected').text();
   if(anoPer =='Ver Todo') {
      anoPer = '0'
   }
   $("#ap").val(anoPer);
    $.ajax({
      type: "GET",
      url: "models/evdem_exportar.model.php?getAllExportar&anoPer="+anoPer,
      data:"",
      success:function(data){
       $("#tblEvdem").html(data);
      

       setTimeout(() => {
				
         $(document).ready(function() {
            $('#myTable').DataTable();
            $(document).ready(function() {
               var table = $('#example').DataTable({
                  "columnDefs": [{
                     "visible": false,
                     "targets": 2
                  }],
                  "order": [
                     [2, 'asc']
                  ],
                  "displayLength": 25,
                  "drawCallback": function(settings) {
                     var api = this.api();
                     var rows = api.rows({
                        page: 'current'
                     }).nodes();
                     var last = null;
                     api.column(2, {
                        page: 'current'
                     }).data().each(function(group, i) {
                        if (last !== group) {
                           $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                           last = group;
                        }
                     });
                  }
               });
               // Order by the grouping
               $('#example tbody').on('click', 'tr.group', function() {
                  var currentOrder = table.order()[0];
                  if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                     table.order([2, 'desc']).draw();
                  } else {
                     table.order([2, 'asc']).draw();
                  }
               });
            });
         });

         /*  table.clear().draw();

    //destroy datatable
    table.destroy()*/
    
      $('#tblExporEvdem').DataTable({
         "pageLength": 20,
         "language": {
         "sProcessing": "Procesando...",
         "sLengthMenu": "Mostrar MENU registros",
         "sZeroRecords": "No se encontraron resultados",
         "sEmptyTable": "Ningún dato disponible en esta tabla",
         "info": "Mostrando _START_ de _END_ Total de Registros _TOTAL_",
         "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
         "sInfoFiltered": "(filtrado de un total de MAX registros)",
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

         dom: 'Bfrtip',
         buttons: [
            'excel', 'csv'
         ]
      });
   }, 1000);
       
    }})
 }

