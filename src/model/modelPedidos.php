<?php
    require_once 'utilities/connection.php';
    //require_once 'utilities/validadores.php';

    class Pedido{

        function regista($idMesa, $idTipo) {
            global $conn;
            $msg = "";
            $stmt = "";
        

            $estadoPreset = 4;
        
            $stmt = $conn->prepare("INSERT INTO pedido (idMesa, idEstado) VALUES (?, ?);");
            if ($stmt === false) {
                return "Erro ao preparar statement: " . $conn->error;
            }
            $stmt->bind_param("ii", $idMesa, $estadoPreset);
        
            if ($stmt->execute()) {
                $pedidoID = $conn->insert_id;
                $msg = "Registado com sucesso: 'pedido' na";
            } else {
                $msg = "Erro ao registar na tabela 'pedido': " . $stmt->error;
                $stmt->close();
                return $msg;
            }
            $stmt->close();





            $stmt = $conn->prepare("INSERT INTO cozinha (idPedido, idPrato) VALUES (?, ?);");
            $stmt->bind_param("ii", $pedidoID, $idTipo);
        
            if ($stmt->execute()) {
                $msg .= "'cozinha'!";
            } else {
                $msg .= " Erro ao registar na tabela 'cozinha': " . $stmt->error;
            }
            $stmt->close();
        



            $conn->close();
        
            return $msg;
        }
        
        
        







        function lista() {
            global $conn;
            $msg = "<table class='table' id='tablePedidosTable'>";
            $msg .= "<thead>";
            $msg .= "<tr>";
        
            $msg .= "<th>Pedido ID</th>";
            $msg .= "<th>Cozinha ID</th>";
            $msg .= "<th>Nome da Mesa</th>";
            $msg .= "<th>Estado</th>";
            $msg .= "<th>Remover</th>";
            $msg .= "<th>Editar</th>";
            $msg .= "<th>Faturar</th>";
        
            $msg .= "</tr>";
            $msg .= "</thead>";
            $msg .= "<tbody>";
        
            $stmt = $conn->prepare("
                SELECT 
                    pedido.*, 
                    pedido.id as pedidoID, 
                    cozinha.id as cozinhaID, 
                    mesas.nome as nomeMesa, 
                    estadopedido.descricao as descEstado
                FROM 
                    pedido, 
                    cozinha, 
                    mesas, 
                    estadopedido
                WHERE 
                    pedido.idMesa = mesas.id 
                    AND pedido.idEstado = estadopedido.id
                    AND cozinha.idPedido = pedido.id
            ");
        
            if ($stmt) {
                if ($stmt->execute()) {
                    $result = $stmt->get_result(); 
                    while ($row = $result->fetch_assoc()) {





                        //get numero da mesa - before table creation
                        $stmt_ForMesa = $conn->prepare("SELECT 
                        pedido.*, 
                        pedido.id as pedidoID, 
                        cozinha.id as cozinhaID,
                        cozinha.idPrato as pratoID,
                        mesas.nome as nomeMesa, 
                        estadopedido.descricao as descEstado,
                        pratos.preco as precoPrato
                        FROM 
                        pedido, 
                        cozinha, 
                        mesas, 
                        estadopedido,
                        pratos
                        WHERE 
                        pedido.idMesa = mesas.id 
                        AND pedido.idEstado = estadopedido.id
                        AND cozinha.idPedido = pedido.id
                        AND cozinha.idPrato = pratos.id
                        AND pedido.id = ?");


                        $stmt_ForMesa->bind_param("i", $row['pedidoID']);
                        $stmt_ForMesa->execute();
                        $result_cozinha = $stmt_ForMesa->get_result();
                        $row_cozinha = $result_cozinha->fetch_assoc();
                        $preco = $row_cozinha['idMesa'];
                        $stmt_ForMesa->close();





                        //continue the rest of the table... adding mesa on getfatura()
                        $msg .= "<tr>";
                        $msg .= "<td>" . $row['pedidoID'] . "</td>";
                        $msg .= "<td>" . $row['cozinhaID'] . "</td>";
                        $msg .= "<td>" . $row['nomeMesa'] . "</td>";
                        $msg .= "<td>" . $row['descEstado'] . "</td>";

                        if ($row['descEstado'] == "Finalizado" ){
                            $msg .= "<td><button type='button' class='btn btn-danger' disabled >Remover</button></td>";
                            $msg .= "<td><button type='button' class='btn btn-primary' onclick='edita(" . $row['pedidoID'] . ", " . $row['cozinhaID'] . ")'>Editar</button></td>";
                            $msg .= "<td><button type='button' class='btn btn-secondary' disabled >Faturar</button></td>";

                        }else{
                            $msg .= "<td><button type='button' class='btn btn-danger' onclick='remover(" . $row['pedidoID'] . ", " . $row['cozinhaID'] . ")'>Remover</button></td>";
                            $msg .= "<td><button type='button' class='btn btn-primary' onclick='edita(" . $row['pedidoID'] . ", " . $row['cozinhaID'] . ")'>Editar</button></td>";
                            $msg .= "<td><button type='button' class='btn btn-secondary' onclick='getFatura(" . $row['idMesa'] . "," . $row['pedidoID'] . ", " . $row['cozinhaID'] . ")'>Faturar</button></td>";
                        }
                        $msg .= "</tr>";
                    }
                } else {
                    $msg .= "<tr><td colspan='6'>Error executing query: " . $stmt->error . "</td></tr>";
                }
            } else {
                $msg .= "<tr><td colspan='6'>Error preparing query: " . $conn->error . "</td></tr>";
            }
        
            $msg .= "</tbody>";
            $msg .= "</table>";
        
            $stmt->close();
            $conn->close();
        
            return $msg;
        }
        
        
        














        function remove($pedidoID, $cozinhaID) {
            global $conn;
            $msg = "";
            $stmt1 = "";
            $stmt2 = "";
        
            $conn->begin_transaction();
        
            try {
                $stmt1 = $conn->prepare("DELETE FROM pedido WHERE id = ?");
                $stmt1->bind_param("i", $pedidoID);
                $stmt1->execute();
        
                $stmt2 = $conn->prepare("DELETE FROM cozinha WHERE idPedido = ?");
                $stmt2->bind_param("i", $pedidoID);
                $stmt2->execute();

                $conn->commit();
        
                $msg = "Registros removidos com sucesso!";
            } catch (Exception $e) {
                // ROLLBACK ";)" transaction  ";)" if any query fails
                $conn->rollback();
                // ROLLBACK ";)" transaction  ";)" if any query fails
                $msg = "Erro ao remover registros: " . $e->getMessage();
            }

            if ($stmt1) $stmt1->close();
            if ($stmt2) $stmt2->close();
            $conn->close();
        
            return $msg;
        }
        
        
        












        function getDados($pedidoID,$cozinhaID) {
            global $conn;
            $stmt1 = "";
            $stmt2 = "";
        
            $stmt1 = $conn->prepare("SELECT * FROM pedido WHERE id = ?");
            $stmt1->bind_param("i", $pedidoID);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $row1 = $result->fetch_assoc();
            $stmt1->close();
    
            $stmt2 = $conn->prepare("SELECT * FROM cozinha WHERE id = ?");
            $stmt2->bind_param("i", $cozinhaID);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $row2 = $result->fetch_assoc();
            $stmt2->close();

            $row =array($row1, $row2);

            return json_encode($row);  
        }


        function getFaturaPratoCozinha($cozinhaID,$pedidoID) {
            global $conn;

            $stmt_ForPreco = $conn->prepare("SELECT 
                    pedido.*, 
                    pedido.id as pedidoID, 
                    cozinha.id as cozinhaID,
                    cozinha.idPrato as pratoID,
                    mesas.nome as nomeMesa, 
                    estadopedido.descricao as descEstado,
                    pratos.preco as precoPrato
                FROM 
                    pedido, 
                    cozinha, 
                    mesas, 
                    estadopedido,
                    pratos
                WHERE 
                    pedido.idMesa = mesas.id 
                    AND pedido.idEstado = estadopedido.id
                    AND cozinha.idPedido = pedido.id
                    AND cozinha.idPrato = pratos.id
                    AND pedido.id = ?");


            $stmt_ForPreco->bind_param("i", $pedidoID);
            $stmt_ForPreco->execute();
            $result_cozinha = $stmt_ForPreco->get_result();
            $row_cozinha = $result_cozinha->fetch_assoc();
            $preco = $row_cozinha['precoPrato'];
            $stmt_ForPreco->close();
             
            return json_encode($preco);  
        }
        
        
        function emiteFatura($clienteFatura, $cozinhaID,$pedidoID, $preco){
            global $conn;
            $stmt1 = "";

            //get estado pedido
            $stmt1 = $conn->prepare("SELECT * FROM pedido WHERE id = ?");
            $stmt1->bind_param("i", $pedidoID);
            $stmt1->execute();
            $result = $stmt1->get_result();
            $row1 = $result->fetch_assoc();
            $stmt1->close();


            if ($row1['idEstado'] == 3){
                return "O Pedido já foi finalizado...";
            }
            
            //get mesa
            $stmt3 = "";
            $stmt3 = $conn->prepare("SELECT * FROM mesas WHERE id = ?");
            $stmt3->bind_param("i", $row1['idMesa']);
            $stmt3->execute();
            $result = $stmt3->get_result();
            $row3 = $result->fetch_assoc();
            $stmt3->close();


            //get preco do prato
            $stmt4 = "";
            $stmt4 = $conn->prepare("SELECT 
                    pedido.*, 
                    pedido.id as pedidoID, 
                    cozinha.id as cozinhaID,
                    cozinha.idPrato as pratoID,
                    mesas.nome as nomeMesa, 
                    estadopedido.descricao as descEstado,
                    pratos.preco as precoPrato
                FROM 
                    pedido, 
                    cozinha, 
                    mesas, 
                    estadopedido,
                    pratos
                WHERE 
                    pedido.idMesa = mesas.id 
                    AND pedido.idEstado = estadopedido.id
                    AND cozinha.idPedido = pedido.id
                    AND cozinha.idPrato = pratos.id
                    AND pedido.id = ?;");
            $stmt4->bind_param("i", $pedidoID);
            $stmt4->execute();
            $result = $stmt4->get_result();
            $row4 = $result->fetch_assoc();
            $stmt4->close();



            


            $content = "*************** FATURA ***************\n";
            $content .= "                ".md5($row1['id'] . date("YmdHis"))." \n";
            $content .= "*************** MESA ***************\n";
            $content .= "                ".$row3['nome'] . "\n";
            $content .= "*************** Pedido **************\n";
            $content .= "                ".$row1['id'] . "\n";
            $content .= "*************** Preco ***************\n";
            $content .= "                ".$row4['precoPrato'] . "\n";
            $content .= "*************** Nif ***************\n";
            $content .= "                ".$clienteFatura . "\n";


            
            $folderName = '../faturas';
            $fileName = md5($row1['id'] . date("YmdHis")) . ".txt";
            $fileRoute = $folderName . '/' . $fileName;

            if (!file_exists($folderName)) {
                if (mkdir($folderName, 0777, true)) {
                    echo "Folder Created";
                } else {
                    echo "Failed to create folder";
                    exit; 
                }
            }

            if (file_put_contents($fileRoute, $content) !== false) {
                echo "Fatura Emitida >> ";
            } else {
                echo "Erro na Emissao da Fatura";
                $error = error_get_last();
                echo "Error details: " . $error['message'];
            }

            //UPDATE estado pedido 
            $stmt2 = "";
            $presetEstado= 3;
            $stmt2 = $conn->prepare("UPDATE pedido SET pedido.idEstado = ? WHERE idEstado = ? and pedido.id= ?");
            $stmt2->bind_param("iii",$presetEstado, $row1['idEstado'], $row1['id']);
            $stmt2->execute();
            $stmt2->close();
            echo("Estado do pedido atualizado com sucesso");

        }


        function wFatura($fileName , $content){
            $folderName = "faturas";
            $fileRoute= "$folderName/$fileName";

            if(!file_exists($folderName)) {
                mkdir($folderName,0777, true);
            }else {
                echo "Cannot create folder";
                return 0;
            }

            if(file_put_contents($fileRoute, $content) !== false) {
                echo "Fatura Emitida";
            } else {
                echo "Erro na Emissao da Fatura";
            }

        }






            function edita(
                $mesa,
                $estado,
                $prato,
                $old_pedidoID_key,
                $old_cozinhaID_key
            ) {
            global $conn;

            $msg = "";
            $stmt = "";

            $stmt = $conn->prepare("UPDATE pedido, cozinha SET 
                                    pedido.idMesa = ?,
                                    pedido.idEstado = ?,
                                    cozinha.idPrato = ?
                                    WHERE pedido.id = ? and cozinha.idPedido = ?;");

            if ($stmt) { 
                $stmt->bind_param("iiiii",
                                        $mesa,
                                        $estado,
                                        $prato,
                                        $old_pedidoID_key,
                                        $old_cozinhaID_key
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
        
        

        



















        function uploads($img, $html_soul, $presetName, $pasta){

            $dir = "../imagens/".$pasta."/";
            $dir1 = "src/imagens/".$pasta."/";
            $flag = false;
            $targetBD = "";
        
            if(!is_dir($dir)){
                if(!mkdir($dir, 0777, TRUE)){
                    die ("Erro não é possivel criar o diretório");
                }
            }
        
            if(array_key_exists($html_soul, $img)){
                if(is_array($img)){
                    if(is_uploaded_file($img[$html_soul]['tmp_name'])){
                        $fonte = $img[$html_soul]['tmp_name'];
                        $ficheiro = $img[$html_soul]['name'];
                        $end = explode(".",$ficheiro);
                        $extensao = end($end);
                
                        $newName =$presetName.date("YmdHis").".".$extensao;
                
                        $target = $dir.$newName;
                        $targetBD = $dir1.$newName;
        
                        $this -> wFicheiro($target);
                
                        $flag = move_uploaded_file($fonte, $target);
                        
                    } 
                }
            }
            return (json_encode(array(
                "flag" => $flag,
                "target" => $targetBD
            )));
        }
        
        function wFicheiro($texto){
            $file = '..//prato_Upload_logs.txt';
            if (file_exists($file)) {
                $current = file_get_contents($file);
            } else {
                $current = '';
            }
            $current .= $texto."\n";
            file_put_contents($file, $current);
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
        function getSelect_pratos(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM pratos;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $msg .= "<option value = '".$row['id']."'>".$row['nome']."</option>";
                }
            } else {
                $msg .= "<option value = '-1'>Sem Pratos</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }
        function getSelect_estado(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM estadopedido;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if ($row['id'] == 3){
                        
                    }else{
                        $msg .= "<option value = '".$row['id']."'>".$row['descricao']."</option>";
                    }
                    
                }
            } else {
                $msg .= "<option value = '-1'>Sem Estados</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }
        function getSelect_clientes(){
            global $conn;
            $msg = "<option value = '-1'>Escolha uma opção</option>";
            $stmt = "";

            $stmt = $conn->prepare("SELECT * FROM clientes;");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $msg .= "<option value = '".$row['nif']."'>".$row['nome']." - ".$row['nif']." </option>";
                }
            } else {
                $msg .= "<option value = '-1'>Sem Pratos</option>";
            }
            $stmt->close(); 
            $conn->close();
            
            return $msg;
        }










    }

    
?>