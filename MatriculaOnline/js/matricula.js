$(document).ready(function(){
    llamarPais();
    llamarAdmision();
    llamarInstitucion();
    llamarcomunas();
    llamarRegiones();

    $("#docAlmunReg").prop('disabled', true);
    $("#btnSubir").prop('disabled', true);
    $('#docSubir').hide(2000);

    $('#Mat').hide();
    $('#MatPay').hide();
    $('#otros').hide();
    $('#otrosPay').hide();
  });
  
  function mostrarDoc(i){

      if(i == 2){
        $('#docSubir').show(2000);
      }else{
        $('#docSubir').hide(2000);
      }
     }

     function llamarTipoDocMat(){
      let i= $('#ddl_TipoPago').val();
      if(i == 1){
        $('#Mat').show(2000);
        $('#MatPay').show(2000);
        $('#otros').hide(2000);
        $('#otrosPay').hide(2000);
      }else{
        $('#otros').show(2000);
        $('#otrosPay').show(2000);
        $('#Mat').hide(2000);
        $('#MatPay').hide(2000);
      }

     }
  function llamarPais(){
        $.ajax({
          type: "GET",
          url: "MatriculaOnline/function/funcionesSelect.php?getAllPais",
          data:"",
          success:function(msg){
           $("#ddl_paises").html(msg);
        }})
     }
  
     function llamarTipoDoc(){
      let tipo_admision = $("#ddl_archivos").val();
      $.ajax({
          type: "GET",
          url: "MatriculaOnline/function/funcionesSelect.php?getAllTipoDoc&tipo_admision=" +tipo_admision,
          data:"",
          success:function(msg){
           $("#subArchivos").html(msg);
        }})
     }
  
     function llamarAdmision(){
        $.ajax({
          type: "GET",
          url: "MatriculaOnline/function/funcionesSelect.php?getAllAdmision",
          data:"",
          success:function(msg){
           $("#ddl_admisiones").html(msg); 
           $('#instConv').hide();
        }})
     }
   
     function llamarInstitucion(){
        $.ajax({
          type: "GET",
          url: "MatriculaOnline/function/funcionesSelect.php?getAllInstitucion",
          data:"",
          success:function(msg){
           $("#ddl_convalidantes").html(msg);
        }})
     }
  
  
     function llamarProgCarrera(){
      let jornada = $("#jornada").val();
        $.ajax({
          type: "GET",
          url: "MatriculaOnline/function/funcionesSelect.php?getAllCarrProg&jornada="+jornada,
          data:"",
          success:function(msg){
           $("#ddl_carreras").html(msg);
        }})
     }
     
     function llamarTableDoc(){
      let idTxtNRut = $("#idTxtNRut").val();
        $.ajax({
          type: "GET",
          url: "MatriculaOnline/cargaArchivo.php?getDocForUser&idTxtNRut="+idTxtNRut,
          data:"",
          success:function(msg){
           $("#ddl_tableArchivo").html(msg);
        }})
     }
     function mostraSaldo(){
  
      const id = $("#ddl_carrera option:selected").attr("id");
      const modalidad = $("#ddl_carrera option:selected").attr("modalidad");
      const regimen = $("#ddl_carrera option:selected").attr("regimen");
      const matricula_anual = $("#ddl_carrera option:selected").attr("matricula_anual");
      const arancel_contado_anual = $("#ddl_carrera option:selected").attr("arancel_contado_anual");
      const matricula_semestral = $("#ddl_carrera option:selected").attr("matricula_semestral");
      const arancel_credito_semestral = $("#ddl_carrera option:selected").attr("arancel_credito_semestral");
  
      
      matricula_anualCL = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(matricula_anual);
      arancel_contado_anualCL = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(arancel_contado_anual);
      
      matricula_semestralCL = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(matricula_semestral);
      arancel_credito_semestralCL = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(arancel_credito_semestral);

      $("#matAnual").val(matricula_anualCL);
      $("#aranAnual").val(arancel_contado_anualCL);
  
      $("#matSemestral").val(matricula_semestralCL);
      $("#aranCreditoSemes").val(arancel_credito_semestralCL);

      $("#texto_modalidad").val(modalidad);
      $("#texto_regimen").val(regimen);
  
  
     }
  
     function mostraSubirArchivo(){
      $("#docAlmunReg").prop('disabled', false);
      $("#btnSubir").prop('disabled', false);
      const txt = $("#ddl_Subirarchivo option:selected").attr("nombre");
      $("#textoAdjunto").text(txt);
     }
  
     function mostrarInstituciones(){
     // alert('El valor es: ' + $("#ddl_viaAdmision").val())
      if($("#ddl_viaAdmision").val() == 2){
        $('#instConv').show(2000);
      }else{
        $('#instConv').hide(2000);
        $("#ddl_convalidante").val('0');
      }
     }
  

     function llamarcomunas(){
      $.ajax({
        type: "GET",
        url: "MatriculaOnline/function/funcionesSelect.php?getAllComunas",
        data:"",
        success:function(msg){
         $("#ddl_comuna").html(msg);
      }})
   }

   function llamarRegiones(){
    $.ajax({
      type: "GET",
      url: "MatriculaOnline/function/funcionesSelect.php?getAllRegion",
      data:"",
      success:function(msg){
       $("#ddl_region").html(msg);
    }})
   }
 /*=============================================
  VALIDAR EXTENSIÓN DE ARCHIVOS
  =============================================*/
   function fileValidation(){
    var fileInput = document.getElementById('docUdate');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.pdf)$/i;
    if(!allowedExtensions.exec(filePath)){
        Swal.fire(
          'A ocurrido un error!',
          'Solo puede cargar archivos .jpeg/.jpg/.pdf ',
          'error'
        );
        fileInput.value = '';
        return false;
    }else{       
        SubirArchivo(allowedExtensions.exec(filePath)[0]);
    }
}
 /*=============================================
  INSERTAR ARCHIVOS
  =============================================*/
  
  function SubirArchivo(tipo) {
    var fileInput = document.getElementById('docUdate');
    if($("#idTxtNRut").val() == '' || $("#idTxtNRut").val() == null
      ||$("#ddl_Subirarchivo").val() == '' || $("#ddl_Subirarchivo").val() == null
      ||$("#docUdate").val() == '' || $("#docUdate").val() == null
      ||$("#idUsuario").val() == '' || $("#idUsuario").val() == null
  ){
  Swal.fire(
    'A ocurrido un error!',
    'No se puedo insertar datos, porque existen campos vacios',
    'error'
  );
 
  fileInput.value = '';
  return false;
}else{

  
    let rut = $("#idTxtNRut").val();
    let id_tipo = $("#ddl_Subirarchivo option:selected").attr("tipo");
     //EL CAMPO FECHA SE GENERA EN PHP
    let nombre_archivo = $("#docUdate").val();
    let mime = tipo;
    
    let id_usuario = $("#idUsuario").val();
    //El archivo 
    let file = document.getElementById("docUdate").files[0];

  if (file) {
    let filereader = new FileReader();
    filereader.readAsDataURL(file);
    filereader.onload = function (evt) {
       let base64 = evt.target.result;
       let archivo = 'base64='+base64
  
       let dataString = 'rut='+rut+'&id_tipo='+id_tipo+'&nombre_archivo='+nombre_archivo+'&mime='+mime
                      +'&archivo='+archivo+'&id_usuario='+id_usuario;
  
       $.ajax({
              type: "POST",
              url: "MatriculaOnline/cargaArchivo.php?insertArchivo",
              data: dataString,
              success: function(data) {
                  
                if (data == 1){                      
                  Swal.fire(
                      'Operación exitosa!',
                      'El archivo se cargo correctamente'
                  );
                  llamarTableDoc();
                }else if(data == 3){
                  Swal.fire(
                      'A ocurrido un error!',
                      'Este documento ya fue ingresado',
                      'error'
                    );
                    fileInput.value = '';
                    llamarTableDoc();
                }else{
                  Swal.fire(
                    'A ocurrido un error!',
                    'No se puedo ingresar la matricula',
                    'error'
                  );
                  fileInput.value = '';
                  llamarTableDoc();
                }
                 
              }
  
          });
    }
  }
  }
   
}
  /*=============================================
  VALIDACIONES
  =============================================*/
function validarIncertar(){

  var correo = $("#texto_email").val();
  var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

if($("#idTxtNRut").val() == '' || $("#idTxtNRut").val() == null
  ||$("#idTxtNDocumento").val() == '' || $("#idTxtNDocumento").val() == null
  ||$("#id_tipoDocumento").val() == '0' || $("#ddl_carrera").val() == '0'
  ||$("#texto_nombre").val() == '' || $("#texto_nombre").val() == null
  ||$("#texto_apellidos").val() == '' || $("#texto_apellidos").val() == null
  ||correo == '' || correo == null
  ||$("#texto_celular").val() == '' || $("#texto_celular").val() == null
  ||$("#texto_fnacimiento").val() == '' || $("#texto_fnacimiento").val() == null
  ||$("#ddl_estadoCivil").val() == '0' || $("#ddl_genero").val() == '0'
  ||$("#ddl_pais").val() == '0' || $("#ddl_viaAdmision").val() == '0'  
  ||$("#texto_direccion").val() == '' || $("#texto_direccion").val() == null
  ||$("#ddl_comunas").val() == '0' || $("#ddl_regiones").val() == '0' 
  || $("#jornada").val() == '0'
  ){
  Swal.fire(
    'A ocurrido un error!',
    'No se puedo insertar datos, porque existen campos vacios',
    'error'
  );
  if (!regex.test(correo)) {
    Swal.fire(
      'A ocurrido un error!',
      'Formato de correo incorrecto',
      'error'
    )
}
  return false;
}else{
  insertarMatricula();
}
}
  /*=============================================
  INSERTAR MATRICULAS
  =============================================*/
  function insertarMatricula(){
    //var dataString = 'nombre= casv'
    let rut = $("#idTxtNRut").val();
    let pasaporte = $("#idTxtPasaporte").val();
    let NDocumento = $("#idTxtNDocumento").val();
    let tipoDocumento= $("#id_tipoDocumento").val();
    let nombre = $("#texto_nombre").val();
    let apellido = $("#texto_apellidos").val();
    let email = $("#texto_email").val();
    let tel_movil = $("#texto_celular").val();  
    let fnacimiento = $("#texto_fnacimiento").val();
    let estadoCivil = $("#ddl_estadoCivil").val();
    let genero = $("#ddl_genero").val();
    let pais = $("#ddl_pais").val();
    let direccion = $("#texto_direccion").val();
    let comunas = $("#ddl_comunas").val();
    let regiones = $("#ddl_regiones").val();
    let viaAdmision = $("#ddl_viaAdmision").val();
    let convalidante = $("#ddl_convalidante").val();
    let id_jornada = $("#jornada").val();
    let carrera = $("#ddl_carrera").val();
    let modalidad1_post = $("#texto_modalidad").val();
    let regimen = $("#ddl_carrera option:selected").attr("regimenID");;
  
    let dataString = 'rut='+rut+'&pasaporte='+pasaporte+'&NDocumento='+NDocumento+'&tipoDocumento='+tipoDocumento
                      +'&nombre='+nombre+'&apellido='+apellido+'&email='+email 
                      +'&tel_movil='+tel_movil+'&fnacimiento='+fnacimiento+'&estadoCivil='+estadoCivil
                      +'&genero='+genero+'&pais='+pais+'&direccion='+direccion +'&comunas='+ comunas +'&regiones='+regiones 
                      +'&viaAdmision='+viaAdmision +'&convalidante='+convalidante+'&id_jornada='+id_jornada 
                      +'&carrera='+carrera + '&modalidad1_post='+modalidad1_post + '&regimen='+regimen;
  
  
  $.ajax({
              type: "POST",
              url: "MatriculaOnline/function/funcionInsertar.php?postInsertar",
              data: dataString,
              success: function(data) {
                  if (data == 1){                      
                    Swal.fire(
                        'Operación exitosa!',
                        'Los datos se guardaron correctamente'
                    );
                    console.log(data);
                  }else{
                    Swal.fire(
                        'A ocurrido un error!',
                        'No se puedo ingresar la matricula',
                        'error'
                      )
                  }
                 
              }
  
          });
  }
  
  function finalizarCarga(){
    Swal.fire({
        title: 'Desea finalizar el proceso?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'Finalizado!',
            'Se a finalizado correctamente.',
            'success'
          );
          location.reload();
        }
      })
  }

function limpiarCampos(){
    $("#idTxtNRut").val('');
    $("#idTxtPasaporte").val('');
    $("#idTxtNDocumento").val('');
    $("#id_tipoDocumento").val('0');
    $("#texto_nombre").val('');

    $("#texto_apellidos").val('');
    $("#texto_email").val('');
    $("#texto_celular").val(''); 
   // $("#texto_fnacimiento").val();
    $("#ddl_estadoCivil").val('0');
    $("#ddl_genero").val('0');
    $("#ddl_pais").val('0');
    $("#texto_direccion").val('');
    $("#ddl_viaAdmision").val('0');
    $("#ddl_convalidante").val('0');
    $("#jornada").val('0');
    $("#ddl_carrera").val('0');
}

function deleteArchivo(id){
  Swal.fire({
    title: 'Desea eliminar el documento?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK'
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        type: "GET",
        url: "MatriculaOnline/cargaArchivo.php?eliminarArchivo&id="+id,
        success: function(data) {
            if (data == 1){
              Swal.fire(
                'Finalizado!',
                'Se elimino correctamente.',
                'success'
              );
              llamarTableDoc();  
            }else{
              
            }
        }

    });


     // limpiarCampos();
    }
  })
}

function pagarMatricula(){
 let matAnual =  $("#matAnual").val();
 let aranAnual = $("#aranAnual").val(); 

 if(matAnual == '' || matAnual == null
 ||aranAnual == '' || aranAnual == null

){
Swal.fire(
'A ocurrido un error!',
'No se puedo pagar, existen campos vacios',
'error'
);
return false;
}else{


 Swal.fire({
  title: 'Desea ejecutar el pago?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'OK'
}).then((result) => {
  if (result.isConfirmed) {
          
            Swal.fire(
              'Finalizado!',
              'Su pago de matricula es de ' + matAnual + ' y de arancel es de ' + aranAnual,
              'success'
            );
      }

})
}
}
function pagarMatriculaOtros(){
  let matSemestral =  $("#matSemestral").val();
  let aranCreditoSemes = $("#aranAnual").val(); 
 
  if(matSemestral == '' || matSemestral == null
    ||aranCreditoSemes == '' || aranCreditoSemes == null
 
 ){
 Swal.fire(
 'A ocurrido un error!',
 'No se puedo pagar, existen campos vacios',
 'error'
 );
 return false;
 }else{

  Swal.fire({
   title: 'Desea ejecutar el pago?',
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'OK'
 }).then((result) => {
   if (result.isConfirmed) {
           
             Swal.fire(
               'Finalizado!',
               'Su pago de matricula semestral es de ' + matSemestral + ' y de arancel semestral es de ' + aranCreditoSemes,
               'success'
             );
       }
 
 })
}
 
 }
  $(document).ready(function(){
      $("#sgu_fancybox").fancybox({
          'autoScale'			: false,
          'autoDimensions'	: true,
          'titleShow'         : false,
          'titlePosition'     : 'inside',
          'transitionIn'		: 'elastic',
          'transitionOut'		: 'elastic',
          'width'				: 1000,
          'height'			: 550,
          'maxHeight'			: 600,
          'afterClose'		: function () { location.reload(true); },
          'type'				: 'iframe'
      });
  });
  

  $(document).ready(function(){
      $("#sgu_fancybox_small").fancybox({
          'autoScale'			: false,
          'autoDimensions'	: true,
          'titleShow'         : false,
          'titlePosition'     : 'inside',
          'transitionIn'		: 'elastic',
          'transitionOut'		: 'elastic',
          'width'				: 600,
          'height'			: 550,
          'maxHeight'			: 550,
          'afterClose'		: function () { location.reload(true); },
          'type'				: 'iframe'
      });
  });


  function esRut() {
   
      if (document.getElementById("id_tipoDocumento").value === "R") {
        //document.getElementById("idTxtPasaporte").disabled = true;
        //document.getElementById("idTxtNDocumento").disabled = false;
        //document.getElementById("idTxtNRut").disabled = false;
        document.getElementById("idTxtPasaporte").value = "";
  
        //document.getElementById("div_rut").style.display="block";
        //document.getElementById("div_documento").style.display="block";
        document.getElementById("div_pasaporte").style.display="none";
        
        
        
      } else {
        document.getElementById("idTxtPasaporte").disabled = false;
        //document.getElementById("idTxtNDocumento").disabled = true;
        //document.getElementById("idTxtNRut").disabled = true;
        //document.getElementById("idTxtNRut").value = "";
        //document.getElementById("idTxtNDocumento").value = "";
  
  
        //document.getElementById("div_rut").style.display="none"; 
        //document.getElementById("div_documento").style.display="none";
        document.getElementById("div_pasaporte").style.display="block";
        }
      //textonpasaporte.style.display = ddltipoDocumento.value == "R" ? "block" : "none";
  }
  
  
  
   function llamarvalor(){
    alert('El valor es: ' + $("#ddl_pais").val())
   }
   
   
   function imprimirContrato(){
    location.href="http://10.1.1.88/contrato.php?id_contrato=51776&tipo=al_nuevo";
}
