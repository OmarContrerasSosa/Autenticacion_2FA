<?php
    require_once('config.php');
    $noControl = $_POST['noControl'];

    $password = $_POST['password'];

    //$password = hash('sha256',$password);

    //Verifica que la contraseña ingresada coincida con la contraseña almacenada
    $query = "SELECT contraseña FROM alumnos WHERE noControl = '$noControl'";
    $result = mysqli_query($conexion,$query);
    if($fila = mysqli_fetch_assoc($result)){
        $hash = $fila['contraseña'];
    }

    $query = "SELECT * FROM alumnos WHERE noControl = '$noControl'"; //Ya no necesario?
    $result = $conexion -> query($query);
    
    if($result -> num_rows == 1 && password_verify($password,$hash)){        
        header("Location: ../config/autenticacion.php?noControl=".urlencode($noControl));
        exit;
    }else{        
        header("Location: ../index.php");
        echo "Usuario no registrado";
    }

?>