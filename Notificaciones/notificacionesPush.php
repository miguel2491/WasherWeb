<?php
$message = $_POST ['message']; // El mensaje que vayas a enviar
$title = $_POST['title']; // Título de la notificación
$path_to_fcm = 'https://fcm.googleapis.com/fcm/send';
$server_key='AAAA_YTXHaU:APA91bHMAq95ha-hiwv_trQ9uKdCjNoWpTwZnxuf3q9FCkkFIzuPQz7aYCEwyvfSxl9hrkrnuhLUUTRaou1cJP95Df2zDd4kAFiwJv1uEUP0SCnDmGDEgAoStYq4s7j1NRFeEqFHi2KT';
//$sql = 'Tu query donde buscas el Token del usuario que te interesa';
//$result = mysqli_query($con, $sql); // Conexión con la Base de Datos
//$row = mysqli_fetch_row($result);
$keyToken = "fihKnhC3KpI:APA91bFTWLTZXO4gQEMu0UKGsFgsTUEl8IqzW0Tt2SaTx5XXaYcDTmecN1N59SOTmzV7GgfsNYd7ySZOtLL0O00v--bwGfuFplNoHjedJeLXKddWLn4qm7jevS3X6w_j9RKGGstJnCXf";//$row[0]; // Obtención del Token

$headers = array(
'Authorization:key=' .$server_key,
'Content-Type:application/json',
'Content-Length: 0'
);

// Para un solo token, si es para varios usar 'registration_ids' en vez de 'to'.

$fields = array('to'=>$keyToken, 'notification'=>array('title'=>$title, 'body'=>$message));

$payload = json_encode($fields);

// Abrir la sesión
$curl_session = curl_init();
// Definir la URL a la que se le hará el post
curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
// Indicar el tipo de petición: POST
curl_setopt($curl_session, CURLOPT_POST, TRUE);

curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
// Recibimos una respuesta y la guardamos en una variable
curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec($curl_session);

curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE_v4);
// Definir cada uno de los parámetros
curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

//$result = curl_exeec($curl_session);
mysqli_close($con);

// Cerrar la sesion
curl_close($curl_session);

// Mostrar el resultado
print_r($remote_server_output);