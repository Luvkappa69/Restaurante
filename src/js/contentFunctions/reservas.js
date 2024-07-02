let controllerPath = "src/controller/controllerReservas.php"


function regista_reserva() {
  if (
    $('#idCliente').val() ==-1 ||
    $('#idMesa').val() ==-1 ||
    $('#data').val() =="" ||
    $('#hora').val() =="" 
  ){
    return alerta("error", "Por favor preencha os campos ...");
}

  let dados = new FormData();
  dados.append('idCliente', $('#idCliente').val());
  dados.append('idMesa', $('#idMesa').val());
  dados.append('data', $('#data').val());
  dados.append('hora', $('#hora').val());

  dados.append('op', 1);


  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      if (msg == 0){
        alerta("error", "Reserva já presente nesta data e hora!");
      } else{
        alerta("success", msg);
      }
      
      listagem_reserva();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });

}













function listagem_reserva() {

  let dados = new FormData();
  dados.append('op', 2);


  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      
      if ($.fn.DataTable.isDataTable('#tableReservas')) {
        $('#tableReservas').DataTable().destroy();
      }
      $('#tableReservas').html(msg);
      $('#tableReservasTable').DataTable({
        "columnDefs": [{
          "targets": '_all',
          "defaultContent": ""
        }]})
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}











function remover_reserva(key) {

  let dados = new FormData();
  dados.append('op', 3);
  dados.append('id', key);

  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      alerta("success", msg);
      listagem_reserva();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}










function edita_reserva(key) {
  getSelect_estado()

  let dados = new FormData();
  dados.append('op', 4);
  dados.append('id', key);

  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      let obj = JSON.parse(msg);
      console.log(obj.idCliente)
      $('#idClienteEdit').val(obj.idCliente);
      $('#idMesaEdit').val(obj.idMesa);
      $('#dataEdit').val(obj.data);
      $('#horaEdit').val(obj.hora);
      $('#estadoEdit').val(obj.estado);

  
      $('#editModal_reserva').modal('toggle');
      $('#btnGuardarEdit_reserva').attr('onclick', 'guardaEdit_reserva(' + obj.id + ')')
    })
 
    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}








function guardaEdit_reserva(key) {

  let dados = new FormData();

  dados.append('idCliente', $('#idClienteEdit').val());
  dados.append('idMesa', $('#idMesaEdit').val());
  dados.append('data', $('#dataEdit').val());
  dados.append('hora', $('#horaEdit').val());
  dados.append('estado', $('#estadoEdit').val());

  dados.append('old_key', key);

  dados.append('op', 5);


  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      alerta("success", msg);
      listagem_reserva();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });

}











function alerta(icon, msg) {

  Swal.fire({
    title: "<strong>Feedback</strong>",
    icon: icon,
    text: msg,
    showCloseButton: true,
    focusConfirm: false,

  });
}





function getSelect_cliente() {

  let dados = new FormData();
  dados.append('op', 6);

  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      $('#idCliente').html(msg);
      $('#idClienteEdit').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}
function getSelect_mesa() {

  let dados = new FormData();
  dados.append('op', 7);

  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      $('#idMesa').html(msg);
      $('#idMesaEdit').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}
function getSelect_estado() {

  let dados = new FormData();
  dados.append('op', 8);

  $.ajax({
    url: controllerPath,
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })

    .done(function (msg) {
      $('#estadoEdit').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}






$(function () {
  listagem_reserva();
  $('#tableReservasTable').DataTable();
  $(".select2").select2();
  getSelect_cliente()
  getSelect_mesa()
});



