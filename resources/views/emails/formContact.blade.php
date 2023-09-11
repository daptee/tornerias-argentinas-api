<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar contraseña</title>
</head>
<body>
    <p>
        Hola. Te informamos que ha llegado un nuevo contacto proveniente de la web. Te dejamos a continuacion los datos: <br><br>

        Nombre: {{ $data['name'] }} <br>
        Mensaje: {{ $data['text'] }} <br>
        Email: {{ $data['email'] }} <br>

        <br>
        No demores en responderle. <br>
        El equipo de Tornerias Argentinas
    </p>
</body>
</html>