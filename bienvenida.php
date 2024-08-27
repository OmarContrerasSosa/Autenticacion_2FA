<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>
</head>
<body>
    <h1>Ingreso al sistema exitoso <?php if(isset($_GET['noControl'])){$noControl = urlencode($_GET['noControl']); echo $noControl;} ?></h1>
</body>
</html>