<?php
    session_start();

    if(isset($_SESSION['utilizador'])){ 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'assets/main/docHead.html' ?>
  
  <script src="src/js/contentFunctions/clientes.js"></script>

</head>

<body>
  <?php include_once 'assets/main/navbar.php' ?>


  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        Novo Cliente
      </div>
      <div class="mx-5 my-5">
        <form class="row g-3">

          <div class="col-md-3">
            <label for="nif" class="form-label">NIF</label>
            <input type="number" class="form-control" id="nif">
          </div>
          <div class="col-md-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome">
          </div>
          <div class="col-md-3">
            <label for="morada" class="form-label">Morada</label>
            <input type="text" class="form-control" id="morada">
          </div>
          <div class="col-md-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="number" class="form-control" id="telefone">
          </div>
          <div class="col-md-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" >
          </div>
         
         


          <div class="col-12">
            <button type="button" class="btn btn-primary" onclick="regista_cliente()">Registar</button>
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