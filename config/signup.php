<?php 

    require_once('config.php');

    $noControl = $_POST['noControl'];
    $nombre = $_POST['user'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    //Se genera el valor hash de la contraseña usando SHA-256
    /*
    $password = hash('sha256',$password);
    $repassword = hash('sha256',$repassword);
    */

    //Se genera el hash con sal. Se da uso del algoritmo BCRYPT. 60 carácteres.
    $password = password_hash($password,PASSWORD_BCRYPT);
    echo $password;
    

    //Se verifican las contraseñas
    if (password_verify($repassword,$password)) {

        //echo "Las contraseñas coinciden";
        $query = "SELECT * FROM alumnos WHERE noControl = '$noControl'";
        $result = $conexion -> query($query);
    
        if($result -> num_rows == 1){
            //header("Location: ../config/autenticacion.php");
            echo "Ya existe";
        }else{        
            //header("Location: ../index.php");
            $query = "INSERT INTO alumnos values('$noControl', '$nombre', '$password','null','null','null','0')";
            try {
                # Insertamos y comprobamos la insercion
                if ($conexion->query($query) === TRUE) {            
                    header("Location: ../index.php");
                }
                
            } catch(Exception $e) {
                echo "Error";
            
            }
        }
    } else {
        echo "Las contraseñas no coinciden. Por favor, verifica.";
    }
?>