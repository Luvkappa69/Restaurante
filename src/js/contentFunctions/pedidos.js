let controllerPath = "src/controller/controllerPedidos.php"


function regista() {
  if (
    $('#idMesa').val() == -1 ||
    $('#pratoSelect').val() == -1

  ){
    return alerta("error", "Por favor preencha os campos ...");
}

  let dados = new FormData();
  dados.append('idMesa', $('#idMesa').val());
  dados.append('idTipo', $('#pratoSelect').val());

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
      
      if ($.fn.DataTable.isDataTable('#tablePedidosTable')) {
        $('#tablePedidosTable').DataTable().destroy();
      }
      $('#tablePedidos').html(msg);
      $('#tablePedidosTable').DataTable({
        "columnDefs": [{
          "targets": '_all',
          "defaultContent": ""
        }]})

    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}










 
function remover(pedidoID, cozinhaID) {
  let dados = new FormData();
  dados.append('op', 3); // Assuming 'op' is an operation code for removal

  // Add pedidoID and cozinhaID to FormData
  dados.append('pedidoID', pedidoID); 
  dados.append('cozinhaID', cozinhaID); 

  // Replace 'controllerPath' with your actual PHP controller endpoint
  $.ajax({
    url: controllerPath, // Example endpoint
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData: false,
  })
  .done(function (msg) {
    alerta("success", msg);
    // Perform any additional actions after successful removal
    listagem(); // Assuming listagem() updates the UI after removal
  })
  .fail(function (jqXHR, textStatus) {
    alert("Request failed: " + textStatus);
  });
}










//edit pedido+cozinha
function edita(pedidoID,cozinhaID) {

  let dados = new FormData();
  dados.append('op', 4);
  dados.append('pedidoID', pedidoID); 
  dados.append('cozinhaID', cozinhaID); 

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

      $('#idEdit').val(obj[0].id);
      $('#idMesaEdit').val(obj[0].idMesa);
      $('#estadoEdit').val(obj[0].idEstado);
      $('#idTipoEdit').val(obj[1].idPrato);

  
      $('#editModal').modal('toggle');
      $('#btnGuardarEdit').attr('onclick', 'guardaEdit(' + obj[0].id + ', ' + obj[1].idPedido + ')')
    })
 
    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}





function guardaEdit(pedidoID, cozinhaID) {

  let dados = new FormData();

  dados.append('mesa', $('#idMesaEdit').val());
  dados.append('estado', $('#estadoEdit').val());
  dados.append('prato', $('#idTipoEdit').val());
  dados.append('old_pedidoID_key', pedidoID);
  dados.append('old_cozinhaID_key', cozinhaID);
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
      $('#editModal').modal('toggle');
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });

}


function getFatura(idMesa,pedidoID, cozinhaID) {
  $('#mesaFatura').val(idMesa)

  
  let dados = new FormData();
  dados.append('cozinhaID', cozinhaID);
  dados.append('pedidoID', pedidoID);
  dados.append('op', 11);
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
      $('#faturaModal').modal('show');

      alerta("info", "Fatura para "+ msg+ " €")
      $('#precoFatura').val(Number(msg))
      

      $('#btnGuardarFatura').attr('onclick', 'guardaFatura(' + pedidoID + ',' + cozinhaID + ', ' + Number(msg) + ')')

    
      
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}



function guardaFatura(pedidoID,cozinhaID, preco) {

  let dados = new FormData();
  if ( $('#clienteFatura').val() == -1){
    alerta("error", "Por favor selecione um cliente")
    return
  }
  dados.append('clienteFatura', $('#clienteFatura').val());
  dados.append('cozinhaID', cozinhaID);
  dados.append('pedidoID', pedidoID);
  dados.append('preco', preco);
  dados.append('op', 12);


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
      $('#faturaModal').modal('toggle');
    })

    .fail(function (jqXHR, textStatus) {
      alert("error", "Request failed: " + textStatus);
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
function getSelect_prato() {

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
      $('#pratoSelect').html(msg);
      $('#idTipoEdit').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}
function getSelect_estado() {

  let dados = new FormData();
  dados.append('op', 9);

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
      $('#estadoEdit').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}
function getSelect_clientes() {

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
      $('#clienteFatura').html(msg);
    })

    .fail(function (jqXHR, textStatus) {
      alert("Request failed: " + textStatus);
    });
}






$(function () {
  listagem();
  $('#tablePedidos').DataTable();
  $('#pratoSelect').select2();
  $('#idMesa').select2();
  getSelect_mesa()
  getSelect_prato()
  getSelect_estado()
  getSelect_clientes()
  
});



