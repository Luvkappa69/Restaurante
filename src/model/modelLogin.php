<?php

require_once 'utilities/connection.php';
require_once 'utilities/sha256Edit.php';

class Login {

    private $encryptionKey = 'your-encryption-key'; 

    function registaUser($username, $pw, $foto, $tpUser){
        global $conn;
        $msg = "";
        $state = false;

        $folder = md5(hash('sha256', $username));
        $upload = $this->uploads($foto, 'foto', "_FOTO", $folder);
        $upload = json_decode($upload, TRUE);

        $templatePic = "src/img/user/user.webp";
        
        // Encrypt the password
        $encryptedPassword = encryptAES256($pw, $this->encryptionKey);

        if($upload['flag']){
            $stmt = $conn->prepare("INSERT INTO utilizador (user, pw, idtuser, foto) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("ssis", $username, $encryptedPassword, $tpUser, $upload['target']);
        } else {
            $stmt = $conn->prepare("INSERT INTO utilizador (user, pw, idtuser, foto) VALUES (?, ?, ?, ?);");
            $stmt->bind_param("ssis", $username, $encryptedPassword, $tpUser, $templatePic);
        }

        if ($stmt->execute()) {
            $msg = "Registado com sucesso!";
            $state = true;
        } else {
            $msg = "Erro ao registar!";
        }

        $resp = json_encode(array(
            "flag" => $state,
            "msg" => $msg
        ));

        $stmt->close();
        $conn->close();

        return $resp;
    }

    function login($username, $pw){
        global $conn;
        $msg = "";
        $flag = true;
        session_start();

        $stmt = $conn->prepare("SELECT * FROM utilizador WHERE user = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            
            // Decrypt the stored password
            $decryptedPassword = decryptAES256($row['pw'], $this->encryptionKey);
            //check if user password input == decrypted password
            if ($pw === $decryptedPassword) {
                $msg = "Bem vindo, " . $row['user'];
                $_SESSION['utilizador'] = $row['user'];
                $_SESSION['tipo'] = $row['idtuser'];
                $_SESSION['foto'] = $row['foto'];

                date_default_timezone_set('Europe/Lisbon');
                $current_time = date('Y-m-d H:i:s');
                $log = $row['id'] . "-" . $row['user'] . "-" . $current_time;

                $this->log_Login($log);
            } else {
                $flag = false;
                $msg = "Erro! Dados Inválidos"; 
            }
        } else {
            $flag = false;
            $msg = "Erro! Dados Inválidos"; 
        }

        $stmt->close();
        $conn->close();

        return json_encode(array(
            "msg" => $msg,
            "flag" => $flag
        ));
    }

    function logout(){

        session_start();
        session_destroy();

        return("Obrigado!");
    }

    function getTiposUser(){

        global $conn;
        $msg = "<option value = '-1'>Escolha uma opção</option>";


        $stmt = $conn->prepare("SELECT * FROM tipouser");
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $msg .= "<option value = '".$row['id_user']."'>".$row['descricao_user']."</option>";
            }
        }else{
            $msg .= "<option value = '-1'>Sem tipos registados</option>";
        }

        $stmt->close();
        $conn->close();
        return $msg;
    }




    function uploads($img, $html_soul, $presetName, $pasta){

        $dir = "../imagens/regUsers/".$pasta."/";
        $dir1 = "src/imagens/regUsers/".$pasta."/";
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
    
                    $this -> log_Login($target);
            
                    $flag = move_uploaded_file($fonte, $target);
                    
                } 
            }
        }
        return (json_encode(array(
            "flag" => $flag,
            "target" => $targetBD
        )));
    }
    

    function log_Login($texto){
        $file = '../login_logs.txt';
        if (file_exists($file)) {
            $current = file_get_contents($file);
        } else {
            $current = '';
        }
        $current .= $texto."\n";
        file_put_contents($file, $current);
    }
}

?>