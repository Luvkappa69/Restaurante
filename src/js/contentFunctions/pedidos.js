let controllerPath = "src/controller/controllerPedidos.php"


function regista() {
  if (
    $('#nome').val() =="" ||
    $('#preco').val() =="" ||
    $('#idTipo').val() ==-1 ||
    $('#foto').val() =="" 
  ){
    return alerta("error", "Por favor preencha os campos ...");
}

  let dados = new FormData();
  dados.append('nome', $('#nome').val());
  dados.append('preco', $('#preco').val());
  dados.append('idTipo', $('#idTipo').val());
  dados.append('foto', $('#foto').prop('files')[0]); //image 🖼️
  
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

      alerta("success", msg);

      
      listagem();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });

}













function listagem() {

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
      
      if ($.fn.DataTable.isDataTable('#tablePratosTable')) {
        $('#tablePratosTable').DataTable().destroy();
      }
      $('#tablePratos').html(msg);
      $('#tablePratosTable').DataTable({
        "columnDefs": [{
          "targets": '_all',
          "defaultContent": ""
        }]})
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}











function remover(key) {

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
      listagem();
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}










function edita(key) {

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

      $('#idEdit').val(obj.id);
      $('#nomeEdit').val(obj.nome);
      $('#precoEdit').val(obj.preco);
      $('#idTipoEdit').val(obj.idTipo);

  
      $('#editModal_cliente').modal('toggle');
      $('#btnGuardarEdit_cliente').attr('onclick', 'guardaEdit_cliente(' + obj.nif + ')')
    })
 
    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}








function guardaEdit(key) {

  let dados = new FormData();

  dados.append('nome', $('#nomeEdit').val());
  dados.append('preco', $('#precoEdit').val());
  dados.append('idTipo', $('#idTipoEdit').val());
  dados.append('foto', $('#fotoEdit').prop('files')[0]); //image 🖼️


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
      listagem();
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





function getSelect_tipoPratos() {

  let dados = new FormData();
  dados.append('op', 10);



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
      $('#idTipo').html(msg);
      $('#idTipoEdit').html(msg);


    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}






$(function () {
  listagem();
  $('#tablePratos').DataTable();
  $('#idTipo').select2();
  getSelect_tipoPratos()
  
});



