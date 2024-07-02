<?php
    require_once 'utilities/connection.php';
    require_once 'utilities/validadores.php';

    class Reserva{

        function regista(
                                $idCliente,
                                $idMesa,
                                $data,
                                $hora
                                ) {
            global $conn;
            $msg = "";
            $stmt = "";
            $estado = 3;                  
            if(!verifica($data, $hora, $conn) ){
                
                $stmt = $conn->prepare("INSERT INTO reserva (idCliente, idMesa, data, hora, estado) 
                VALUES (?, ?, ?, ?, ?);");
            
                $stmt->bind_param("iissi", 
                                            $idCliente,
                                            $idMesa,
                                            $data,
                                            $hora,
                                            $estado
                                            );
            
                if ($stmt->execute()) {
                    $msg = "Registado com sucesso!";
                } else {
                    $msg = "Erro ao registar: " . $stmt->error;  
                } 

                $stmt->close();
                

            }
            else{
                $msg = 0;
            }
            
            
            $conn->close();
            return $msg;
        }
        








        function lista() {
            global $conn;
            $msg = "<table class='table' id='tableReservasTable'>";
            $msg .= "<thead>";
            $msg .= "<tr>";
        
            $msg .= "<th>Cliente</th>";
            $msg .= "<th>Mesa</th>";
            $msg .= "<th>Data</th>";
            $msg .= "<th>Hora</th>";
            $msg .= "<th>Estado</th>";
            
            
            $msg .= "<td>Remover</td>";
            $msg .= "<td>Editar</td>";
        
            $msg .= "</tr>";
            $msg .= "</thead>";
            $msg .= "<tbody>";
        
            $stmt = $conn->prepare("SELECT * FROM reserva;"); 

            
        
            if ($stmt) { 
                if ($stmt->execute()) { 
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $msg .= "<tr>";

                            $msg .= "<th scope='row'>" . $row['idCliente'] . "</th>";
                            $msg .= "<td>" . $row['idMesa'] . "</td>";
                            $msg .= "<td>" . $row['data'] . "</td>";
                            $msg .= "<td>" . $row['hora'] . "</td>";
                            $msg .= "<td>" . $row['estado'] . "</td>";

                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }
                        
                            if(isset($_SESSION['utilizador']) && $_SESSION['tipo'] == 1 && $row['estado'] == 1){ 

                                $msg .= "<td><button type='button' class='btn btn-danger' onclick='remover_reserva(" . $row['id'] . ")'>Remover</button></td>";
                            }else{
                                $msg .= "<td><button type='button' class='btn btn-danger' disabled>Remover</button></td>";

                            }
                            $msg .= "<td><button type='button' class='btn btn-primary' onclick='edita_reserva(" . $row['id'] . ")'>Editar</button></td>";
                            $msg .= "</tr>";
                        }
                        $result->free(); 
                    } else {
                        $msg .= "<tr>";

                        $msg .= "<th scope='row'>Sem resultados</th>";
                        $msg .= "<td>Sem resultados</td>";
                        $msg .= "<td>Sem resultados</td>";                   
                        $msg .= "<td>Sem resultados</td>";                   
                        $msg .= "<td>Sem resultados</td>";                                    

                        $msg .= "<td></td>";
                        $msg .= "<td></td>";
                        $msg .= "</tr>";
                    }
                } else {
                    echo "Error executing query: " . $stmt->error; 
                }
                $stmt->close(); 
            } else {
                echo "Error preparing statement: " . $conn->error; 
            }
        
            $msg .= "</tbody>";
            $msg .= "</table>";

            $conn->close();
        
            return $msg;
        }
        
        














        function remove($codigo) {
            global $conn;
        
            $msg = "";
            $stmt = "";
        
            $stmt = $conn->prepare("DELETE FROM reserva
                                    WHERE id = ?");
        
            $stmt->bind_param("i", $codigo); 
        
            if ($stmt->execute()) {
                $msg = "Removido com sucesso!";
            } else {
                $msg = "Erro ao remover: " . $stmt->error; 
            }
        
            $stmt->close();
            $conn->close();

        
            return $msg;
        }
        












        function getDados($codigo) {
            global $conn;


            $stmt = $conn->prepare("SELECT * FROM reserva WHERE id = ?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
    
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

    
            $stmt->close();
            $conn->close();

            
            return json_encode($row);  
        }
        










        function edita(
        $idCliente,
        $idMesa,
        $data,
        $hora,
        $estado,
        $oldKEY) {
            global $conn;
          
            $msg = "";
            $stmt = "";
        
            $stmt = $conn->prepare("UPDATE reserva SET 
                                    idCliente = ?,
                                    idMesa = ?,
                                    data = ?,
                                    hora = ?,
                                    estado = ?
                                    WHERE id = ? ;");
        
            if ($stmt) { 
                $stmt->bind_param("iissii",
                $idCliente,
                $idMesa,
                $data,
                $hora,
                $estado,
                $oldKEY);
        
                if ($stmt->execute()) {
                    $msg = "Edição efetuada";
                } else {
                    $msg = "Erro ao editar: " . $stmt->error; 
                }
                $stmt->close(); 
            } else {
                $msg = "Erro ao preparar a declaração: " . $conn->error;  
            }

            $conn->close();
        
            return $msg;

        }

        function getSelect_cliente(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM clientes;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $msg .= "<option value = '".$row['nif']."'>".$row['nome']."</option>";
                }
            } else {
                $msg .= "<option value = '-1'>Sem clientes</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }
        function getSelect_mesa(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM mesas;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $msg .= "<option value = '".$row['id']."'>".$row['nome']."</option>";
                }
            } else {
                $msg .= "<option value = '-1'>Sem Mesas</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }
        function getSelect_estado(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM estadoreserva;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $msg .= "<option value = '".$row['id']."'>".$row['descricao']."</option>";
                }
            } else {
                $msg .= "<option value = '-1'>Sem Estados</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }
        


    }

    
?>