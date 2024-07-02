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
    <!-- cation when removing items, PHP ERROR Pedidos-->
    <h3>cation when removing items, PHP ERROR on Pedidos</h3>
    <?php include_once 'assets/main/navbar.php' ?>
    
    <div class="container">
        <div class="row mx-5 mb-5">
            <div id="tablePratosContainer"></div>
        </div>
    </div>
    
    <?php include_once 'assets/pratosModal.html' ?>
</body>
</html>
<?php 
}else{
    echo "sem permissão!";
}

?>