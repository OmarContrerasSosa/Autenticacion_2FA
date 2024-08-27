<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <form action="config/signup.php" method="POST">
        <h1>Registro de alumno</h1>
        <input type="text" id="noControl" placeholder="Número de control" name="noControl" required><br><br>
        <input type="text" id="user" placeholder="Nombre" name="user" required><br><br>
        <input type="password" id="password" placeholder="Contraseña" name="password" required><br><br>
        <input type="password" id="repassword" placeholder="Repetir Contraseña" name="repassword" required><br><br>
        <button type="submit">Ingresar</button>
    </form>

</body>
</html>