<?php
require_once '../model/modelPedidos.php';

$execute =  new Pedido();

if($_POST['op'] == 1){
    $resultado = $execute -> regista(
                                                $_POST['idMesa'], 
                                                $_POST['idTipo']
    );
    echo($resultado);
}else if($_POST['op'] == 2){
    $resultado = $execute -> lista();
    echo($resultado);
}else if($_POST['op'] == 3){

    echo "A remover [pedido:".$_POST['pedidoID']." + cozinha:".$_POST['cozinhaID']."] ->";
    $resultado = $execute -> remove(
                                                $_POST['pedidoID'] , //this is a loop inside php with variable names,                                             
                                                $_POST['cozinhaID'] //ref:php table on                                               
    );
    echo($resultado);
}
else if($_POST['op'] == 4){
    $resultado = $prato -> getDados(
                                    $_POST['pedidoID'], 
                                    $_POST['cozinhaID']                                              
    );
    echo($resultado);
}
else if($_POST['op'] == 5){//guarda
    $resultado = $execute -> edita(
                                                $_POST['pedidoID'], 
                                                $_POST['cozinhaID']
    );
    echo($resultado);
}
else if($_POST['op'] == 7){
    $resultado = $execute -> getSelect_mesa();
    echo($resultado);
}
else if($_POST['op'] == 8){
    $resultado = $execute -> getSelect_pratos();
    echo($resultado);
}


?>