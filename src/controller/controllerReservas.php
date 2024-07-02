<?php
require_once '../model/modelReservas.php';

$reserva =  new Reserva();

if($_POST['op'] == 1){
    $resultado = $reserva -> regista(
                                                $_POST['idCliente'], 
                                                $_POST['idMesa'], 
                                                $_POST['data'], 
                                                $_POST['hora']
    );
    echo($resultado);
}else if($_POST['op'] == 2){
    $resultado = $reserva -> lista();
    echo($resultado);
}else if($_POST['op'] == 3){
    $resultado = $reserva -> remove(
                                                $_POST['id']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 4){
    $resultado = $reserva -> getDados(
                                                $_POST['id']                                               
    );
    echo($resultado);
}else if($_POST['op'] == 5){
    $resultado = $reserva -> edita(
                                                $_POST['idCliente'], 
                                                $_POST['idMesa'], 
                                                $_POST['data'], 
                                                $_POST['hora'],
                                                $_POST['estado'],
                                                $_POST['old_key']
    );
    echo($resultado);
}

else if($_POST['op'] == 6){
    $resultado = $reserva -> getSelect_cliente();
    echo($resultado);
}
else if($_POST['op'] == 7){
    $resultado = $reserva -> getSelect_mesa();
    echo($resultado);
}
else if($_POST['op'] == 8){
    $resultado = $reserva -> getSelect_estado();
    echo($resultado);
}

?>