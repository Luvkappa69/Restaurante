let controllerPath = "src/controller/controllerClientes.php"


function regista_cliente() {
  if (
    $('#nif').val() =="" ||
    $('#nome').val() =="" ||
    $('#morada').val() =="" ||
    $('#telefone').val() =="" ||
    $('#email').val() =="" 

  ){
    return alerta("error", "Por favor preencha os campos ...");
}

  let dados = new FormData();
  dados.append('nif', $('#nif').val());
  dados.append('nome', $('#nome').val());
  dados.append('morada', $('#morada').val());
  dados.append('telefone', $('#telefone').val());
  dados.append('email', $('#email').val());
  
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
        alerta("error", "Contacto incorreto, verifique (NIF, TELEFONE)");
      } else{
        alerta("success", msg);
      }
      
      listagem_cliente();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });

}













function listagem_cliente() {

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
      
      if ($.fn.DataTable.isDataTable('#tableClientes')) {
        $('#tableClientes').DataTable().destroy();
      }
      $('#tableClientes').html(msg);
      $('#tableClientesTable').DataTable({
        "columnDefs": [{
          "targets": '_all',
          "defaultContent": ""
        }]})
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}











function remover_cliente(key) {

  let dados = new FormData();
  dados.append('op', 3);
  dados.append('nif', key);

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
      listagem_cliente();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}










function edita_cliente(key) {

  let dados = new FormData();
  dados.append('op', 4);
  dados.append('nif', key);

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

      $('#nifEdit').val(obj.nif);
      $('#nomeEdit').val(obj.nome);
      $('#moradaEdit').val(obj.morada);
      $('#telefoneEdit').val(obj.telefone);
      $('#emailEdit').val(obj.email);
  
      $('#editModal_cliente').modal('toggle');
      $('#btnGuardarEdit_cliente').attr('onclick', 'guardaEdit_cliente(' + obj.nif + ')')
    })
 
    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}








function guardaEdit_cliente(key) {

  let dados = new FormData();

  dados.append('nif', $('#nifEdit').val());
  dados.append('nome', $('#nomeEdit').val());
  dados.append('morada', $('#moradaEdit').val());
  dados.append('telefone', $('#telefoneEdit').val());
  dados.append('email', $('#emailEdit').val());


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
      listagem_cliente();
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












$(function () {
  listagem_cliente();
  $('#tableClientesTable').DataTable();
});



