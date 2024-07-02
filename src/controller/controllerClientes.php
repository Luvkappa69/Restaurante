<?php
require_once '../model/modelClientes.php';

$cliente =  new Cliente();

if($_POST['op'] == 1){
    $resultado = $cliente -> regista(
                                                $_POST['nif'], 
                                                $_POST['nome'], 
                                                $_POST['morada'], 
                                                $_POST['telefone'], 
                                                $_POST['email']

    );
    echo($resultado);
}else if($_POST['op'] == 2){
    $resultado = $cliente -> lista();
    echo($resultado);
}else if($_POST['op'] == 3){
    $resultado = $cliente -> remove(
                                                $_POST['nif']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 4){
    $resultado = $cliente -> getDados(
                                                $_POST['nif']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 5){
    $resultado = $cliente -> edita(
                                                $_POST['nif'], 
                                                $_POST['nome'], 
                                                $_POST['morada'], 
                                                $_POST['telefone'], 
                                                $_POST['email'], 
                                                $_POST['old_key']
    );
    echo($resultado);
}

?>