<?php
    session_start();

    if(isset($_SESSION['utilizador'])){ 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'assets/main/docHead.html' ?>
  
  <script src="src/js/contentFunctions/pedidos.js"></script>

</head>

<body>
  <?php include_once 'assets/main/navbar.php' ?>




  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        Novo Pedido
      </div>
      <div class="mx-5 my-5">
        <form class="row g-3">

         
        <div class="col-md-6">
              <h2 for="idMesa" class="form-label">Mesa</h2>
              <select class="form-select select2" aria-label="Default select example" id="idMesa"></select>
          </div>


          <hr>


          <h3>cozinha:</h3>
          <div class="col-md-6">
              <label for="idTipo" class="form-label">Prato</label>
              <select class="form-select select2" aria-label="Default select example" id="idTipo"></select>
          </div>
          
         
          <hr>

          <div class="col-12">
            <button type="button" class="btn btn-primary" onclick="regista()">Registar</button>
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