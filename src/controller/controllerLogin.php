<?php

include_once "../model/modelLogin.php";

$log = new Login();

if($_POST['op'] == 1){
    $resp = $log -> registaUser($_POST['username'],$_POST['password'],$_FILES,$_POST['tpUser']);
    echo($resp);

}else if($_POST['op'] == 2){
    $resp = $log -> login($_POST['username'],$_POST['password']);
    echo($resp);
}else if($_POST['op'] == 3){
    $resp = $log -> logout();
    echo($resp);
}else if($_POST['op'] == 4){
    $resp = $log -> getTiposUser();
    echo($resp);
}


?>