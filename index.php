

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <form action="config/login.php" method="POST">
        <h1>Inicio de sesión</h1>
        <input type="text" id="noControl" placeholder="No. de Control" name="noControl" required><br><br>
        <input type="password" id="password" placeholder="Contraseña" name="password" required><br><br>
        <button type="submit">Ingresar</button>
    </form>
    <div>
        <p>¿No tiene cuenta?</p>
        <a href="registro.php">Registrarse</a>
    </div>
</body>
</html>