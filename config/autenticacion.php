<?php
declare(strict_types=1);
require '../vendor/autoload.php';
require_once('config.php');

$intentos = 0; //<---

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();


	
if(isset($_GET['noControl'])){
		$noControl = urlencode($_GET['noControl']);
		
		$query = "SELECT * FROM alumnos WHERE noControl = '$noControl' AND habilitado2FA = '0'";
		$result = $conexion -> query($query);

		if($result -> num_rows == 1){        
			//echo "Encontrado y no habilitado";
			$clave = $g->generateSecret(); //Se venera clave de GoogleAuthenticator

			//------Elementos para el cifrado AES-256----------
			$llave = random_bytes(32); 

			//Se genera un vector de inicialización aleatorio de 256 bits
			$iv_length = openssl_cipher_iv_length('aes-256-cbc');
			$iv = openssl_random_pseudo_bytes($iv_length);

			//Se cifra el texto													//Se retorna como tipo binario
			//$claveCifrada = openssl_encrypt($clave, 'aes-256-cbc', $llave, OPENSSL_RAW_DATA, $iv);

			//Retorna de fomra automática el resultado en base64
			$claveCifradaBase64 = openssl_encrypt($clave, 'aes-256-cbc', $llave, 0, $iv); 

			//echo "Clave en B64: $claveCifradaBase64";

			
			//$claveCifradaBase64 = base64_encode($claveCifrada);
			//echo "Valor de la clave cifrada $claveCifradaBase64";
			
			$ivBase64 = base64_encode($iv);
			//echo "Valor de IV en B64: $ivBase64";

			//$llave = bin2hex($llave);

			//echo "Valor de llave aleatoria en hexadecimal: $llave";

			$llave = base64_encode($llave);
			//echo "Valor de llave aleatoria base64: $llave";

			$query = "UPDATE alumnos SET habilitado2FA = '1', codigo2FA = '$claveCifradaBase64', llave = '$llave', IV = '$ivBase64' WHERE noControl = '$noControl'";
			try {
                # Insertamos y comprobamos la insercion
                if ($conexion->query($query) === TRUE) {            
                    //echo "Ha sido habilitado!";
                }
                
            } catch(Exception $e) {
                echo "Error!!";
            
            }
		}else{        
			//echo "Encontrado y habilitado";
			$query = "SELECT codigo2FA, llave, IV FROM alumnos WHERE noControl = '$noControl'";
			$result = mysqli_query($conexion,$query);
			if($fila = mysqli_fetch_assoc($result)){
				//Se obtienen los valores de la base de datos
				$claveCifradaBase64 = $fila['codigo2FA'];
				//echo "Clave cifrada: $claveCifradaBase64";
				$llave = $fila['llave'];			
				//echo "  Llave: $llave";
				$ivBase64 = $fila['IV'];
				//echo "  Valor de IV: $ivBase64";

				//Se preparan los datos para descifrarlos
				//$claveCifrada = base64_decode($claveCifradaBase64);		
				
				//echo "Valor de clave Cifrada: $claveCifrada";

				$iv = base64_decode($ivBase64);	

				$llave = base64_decode($llave);

				//El valor de claveCifrada se maneja como binario, por lo que se usa OPENSSL_RAW_DATA
				//$clave = openssl_decrypt($claveCifrada, 'aes-256-cbc', $llave, OPENSSL_RAW_DATA, $iv);
				
				//Se considera el tipo de valor de claveCifradaBase64, por lo que la opción debe coincidir 
				$clave = openssl_decrypt($claveCifradaBase64, 'aes-256-cbc', $llave, 0, $iv);
				//echo "Clave descifrada: " . $clave . "\n";			
			}
		}
	}else{
		echo "No se recibió valor";
	}



$qr = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($noControl, $clave, 'PruebaAutenticacion');

if(isset($_POST['submit'])){
	$code = $_POST['pass-code'];
	if ($g->checkCode($clave, $code)) {
		header("Location: ../bienvenida.php?noControl=".urlencode($noControl));
	} else {
		#Verificar!
		$intentos ++;
		if($intentos == 3){
			$clave = $g->generateSecret();
			header("Location: ../index.php");
		}
	}
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Autenticación 2FA</title>
</head>
<body>
	<center>
		<h1>Autenticación de doble factor</h1>
		<div style="width: 50%; margin: 10px auto;">
			<img src ="<?=$qr;?>"><br>
			<label><?=$clave?></label>
		</div>
			<label>Ingrese la clave de seis dígitos al analizar el código con GoogleAuthenticator</label><br><br>
			<form method="POST">
				<input type="text" id="pass-code" placeholder="Clave" name="pass-code"><br><br>
				<input type="submit" value="Enviar" name="submit">
			</form>
	</center>
</body>
</html>