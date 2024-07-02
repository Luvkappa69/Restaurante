<?php
require_once '../model/modelPedidos.php';

$prato =  new Pedido();

if($_POST['op'] == 1){
    $resultado = $prato -> regista(
                                                $_POST['nome'], 
                                                $_POST['preco'], 
                                                $_POST['idTipo'], 
                                                $_FILES

    );
    echo($resultado);
}else if($_POST['op'] == 2){
    $resultado = $prato -> lista();
    echo($resultado);
}else if($_POST['op'] == 3){
    $resultado = $prato -> remove(
                                                $_POST['id']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 4){
    $resultado = $prato -> getDados(
                                                $_POST['id']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 5){
    $resultado = $prato -> edita(
                                                $_POST['nome'], 
                                                $_POST['preco'], 
                                                $_POST['idTipo'], 
                                                $_FILES, 
                                                $_POST['old_key']
    );
    echo($resultado);
}
else if($_POST['op'] == 10){
    $resultado = $prato -> getSelect_tipoPrato();
    echo($resultado);
}

?>