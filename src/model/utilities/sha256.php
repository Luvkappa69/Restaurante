<?php
function encryptAES256($data, $key) {
    // Gera um IV (Initialization Vector) aleatório de 16 bytes
    $iv = openssl_random_pseudo_bytes(16);
    
    // Encripta os dados usando AES-256-CBC
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    
    // Retorna o IV concatenado com os dados encriptados, codificados em base64
    return base64_encode($iv . $encryptedData);
}

// Exemplo de uso
$key = 'your-encryption-key'; // Deve ter 32 bytes para AES-256
$data = 'Texto a ser encriptado';

$encryptedData = encryptAES256($data, $key);
echo "Texto encriptado: " . $encryptedData;


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

// Exemplo de uso
$key = 'your-encryption-key'; // Deve ter 32 bytes para AES-256
$encryptedData = 'Texto encriptado recebido';

$decryptedData = decryptAES256($encryptedData, $key);
echo "Texto desencriptado: " . $decryptedData;
?>