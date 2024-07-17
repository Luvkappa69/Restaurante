<?php
function encryptAES256($data, $key) {
    // Gera um IV (Initialization Vector) aleatório de 16 bytes
    $iv = openssl_random_pseudo_bytes(16);
    
    // Encripta os dados usando AES-256-CBC
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    // Retorna o IV concatenado com os dados encriptados, codificados em base64
    return base64_encode($iv . $encryptedData);
}


function decryptAES256($encryptedData, $key) {
    // Decodifica os dados encriptados de base64
    $decodedData = base64_decode($encryptedData);
    
    // Extrai o IV dos primeiros 16 bytes do dado decodificado
    $iv = substr($decodedData, 0, 16);
    
    // Extrai os dados encriptados restantes
    $encryptedData = substr($decodedData, 16);
    
    // Desencripta os dados usando AES-256-CBC
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    // Retorna os dados desencriptados
    return $decryptedData;
}

?>