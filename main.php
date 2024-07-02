<?php
    session_start();

    if(isset($_SESSION['utilizador'])){ 
?>

    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <?php include_once 'assets/main/docHead.html' ?>
        
        <script src="src/js/contentFunctions/login.js"></script>
        
    </head>
    
    <body>
        <?php include_once 'assets/main/navbar.php' ?>
        <?php include_once 'menu.php' ?>

    </body>
    </html>

<?php 
}else{
    echo "sem permissão!";
}

?>