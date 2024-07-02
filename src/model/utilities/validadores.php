<?php

function validateNine($nine) {
  if ($nine == "" || strlen($nine) != 9 || !is_numeric($nine)) {
    return true;
  }
  return false;
}

function verifica($data, $hora, $conn) {
  $stmt = $conn->prepare("SELECT * FROM reserva
  WHERE data = ? and hora = ?;");
  $stmt->bind_param("ss", $data, $hora);
  $stmt->execute();

  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $stmt->close();
 

  if ($row){
    return true;
  }else{
    return false;
  } 
}

?>