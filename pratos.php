<?php
    session_start();

    if(isset($_SESSION['utilizador'])){ 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'assets/main/docHead.html' ?>
  
  <script src="src/js/contentFunctions/pratos.js"></script>

</head>

<body>
  <?php include_once 'assets/main/navbar.php' ?>


  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        Novo Prato
      </div>
      <div class="mx-5 my-5">
        <form class="row g-3">

          <div class="col-md-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome">
          </div>
          <div class="col-md-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" class="form-control" id="preco">
          </div>
          <div class="col-md-3">
              <label for="idTipo" class="form-label">Tipo de Prato</label>
              <select class="form-select select2" aria-label="Default select example" id="idTipo"></select>
          </div>
          <div class="col-md-6">
              <label for="foto" class="form-label">Foto</label>
              <input type="file" class="form-control" id="foto">
          </div>
         


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