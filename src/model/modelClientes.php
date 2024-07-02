<?php
    require_once 'utilities/connection.php';
    require_once 'utilities/validadores.php';

    class Cliente{

        function regista(
                                $nif,
                                $nome,
                                $morada,
                                $telefone,
                                $email
                                ) {
            global $conn;
            $msg = "";
            $stmt = "";

            if(
                !validateNine($telefone) &&
                !validateNine($nif)
                
            ){
                
                $stmt = $conn->prepare("INSERT INTO clientes (nif, nome, morada, telefone, email) 
                VALUES (?, ?, ?, ?, ?)");
            
                $stmt->bind_param("issis", 
                                            $nif,
                                            $nome,
                                            $morada,
                                            $telefone,
                                            $email
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
            $msg = "<table class='table' id='tableClientesTable'>";
            $msg .= "<thead>";
            $msg .= "<tr>";
        
            $msg .= "<th>nif</th>";
            $msg .= "<th>nome</th>";
            $msg .= "<th>morada</th>";
            $msg .= "<th>telefone</th>";
            $msg .= "<th>email</th>";
          
            $msg .= "<td>Remover</td>";
            $msg .= "<td>Editar</td>";
        
            $msg .= "</tr>";
            $msg .= "</thead>";
            $msg .= "<tbody>";
        
            $stmt = $conn->prepare("SELECT * FROM clientes;"); 

            
        
            if ($stmt) { 
                if ($stmt->execute()) { 
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $msg .= "<tr>";

                            $msg .= "<th scope='row'>" . $row['nif'] . "</th>";
                            $msg .= "<td>" . $row['nome'] . "</td>";
                            $msg .= "<td>" . $row['morada'] . "</td>";
                            $msg .= "<td>" . $row['telefone'] . "</td>";
                            $msg .= "<td>" . $row['email'] . "</td>";

                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }
                        
                            if(isset($_SESSION['utilizador']) && $_SESSION['tipo'] == 1){ 

                                $msg .= "<td><button type='button' class='btn btn-danger' onclick='remover_cliente(" . $row['nif'] . ")'>Remover</button></td>";
                            }else{
                                $msg .= "<td><button type='button' class='btn btn-danger' disabled>Remover</button></td>";

                            }
                            $msg .= "<td><button type='button' class='btn btn-primary' onclick='edita_cliente(" . $row['nif'] . ")'>Editar</button></td>";
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
        
            $stmt = $conn->prepare("DELETE FROM clientes
                                    WHERE nif = ?");
        
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


            $stmt = $conn->prepare("SELECT * FROM clientes WHERE nif = ?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
    
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

    
            $stmt->close();
            $conn->close();

            
            return json_encode($row);  
        }
        










        function edita(
        $nif,
        $nome,
        $morada,
        $telefone,
        $email,
        $oldKEY) {
            global $conn;
          
            $msg = "";
            $stmt = "";
        
            $stmt = $conn->prepare("UPDATE clientes,reserva SET 
                                    nif = ?,
                                    nome = ?,
                                    morada = ?,
                                    telefone = ?,
                                    email = ?,
                                    reserva.idCliente = ?
                                    WHERE nif = ?;");
        
            if ($stmt) { 
                $stmt->bind_param("issisii",
                $nif,
                $nome,
                $morada,
                $telefone,
                $email,
                $nif,
                $oldKEY
            );
        
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
        


    }

    
?>