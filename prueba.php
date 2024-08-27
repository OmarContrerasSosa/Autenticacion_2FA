<?php
// Datos a cifrar
$data = "Datos a cifrar";

// Generar una clave aleatoria de 32 bytes (256 bits)
$key = random_bytes(32);

// Convertir la clave a formato base64 para su almacenamiento en la base de datos
$key_base64 = base64_encode($key);
//echo "Llave en base 64: $key_base64\n";

// Generar un IV aleatorio de la longitud adecuada para AES-256-CBC (16 bytes, 128 bits)
$iv_length = openssl_cipher_iv_length('aes256');
$iv = openssl_random_pseudo_bytes($iv_length);

// Cifrar datos
$ciphertext_base64 = openssl_encrypt($data, 'aes256', $key, 0, $iv);
//echo "Texto cifrado directamente en base64: $ciphertext_base64\n";

// Codificar IV en base64
$iv_base64 = base64_encode($iv);
//echo "IV (Base64): $iv_base64\n";

// Para descifrar, decodificar el IV y el texto cifrado
//$ciphertext = base64_decode($ciphertext_base64);
$iv_decoded = base64_decode($iv_base64);
$key_decoded = base64_decode($key_base64);

// Descifrar los datos
$decrypted_data = openssl_decrypt($ciphertext_base64, 'aes256', $key_decoded, 0, $iv_decoded);

echo "Datos descifrados: " . $decrypted_data . "\n";
?>

