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

    <div class="container">
        <div class="row mx-5 mb-5">
            <div id="tableReservas"></div>
        </div>
    </div>
    
    <?php include_once 'assets/reservasModal.html' ?>
</body>
</html>
<?php 
}else{
    echo "sem permissão!";
}

?>