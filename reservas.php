<?php
    session_start();

    if(isset($_SESSION['utilizador'])){ 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'assets/main/docHead.html' ?>
  
  <script src="src/js/contentFunctions/reservas.js"></script>

</head>

<body>
  <?php include_once 'assets/main/navbar.php' ?>


  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        Nova Reserva
      </div>
      <div class="mx-5 my-5">
        <form class="row g-3">

          <div class="col-md-3">
              <label for="idCliente" class="form-label">Cliente</label>
              <select class="form-select select2" aria-label="Default select example" id="idCliente"></select>
          </div>
          <div class="col-md-3">
              <label for="idMesa" class="form-label">Mesa</label>
              <select class="form-select select2" aria-label="Default select example" id="idMesa"></select>
          </div>
          <div class="col-md-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" id="data">
          </div>
          <div class="col-md-3">
            <label for="hora" class="form-label">Hora</label>
            <input type="time" class="form-control" id="hora">
          </div>


          <div class="col-12">
            <button type="button" class="btn btn-primary" onclick="regista_reserva()">Registar</button>
          </div>
        </form>
      </div>
    </div>

    


</body>
</html>

<?php 
}else{
    echo "sem permissão!";
}
?>