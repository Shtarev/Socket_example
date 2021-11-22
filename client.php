<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Socket_example</title>
</head>
<body>
<?php
$host = "127.0.0.1";
$port = 25003;
$response = "";
$server_socket = "http://test.loc/server.php";

if(isset($_POST['message']) && $_POST['message'] != '') {
    $message = $_POST['message'];
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket");
    $result = socket_connect($socket, $host, $port) or die("Could not connect to server");  
    socket_write($socket, $message, strlen($message)) or die("Could not send data to server");
    $response = socket_read($socket, 1024) or die("Could not read server response");
    socket_close($socket);
}
else {
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $server_socket);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
    curl_exec($ch);
    curl_close($ch);
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket");
    $result = socket_connect($socket, $host, $port) or die("Could not connect to server");
    socket_write($socket, '_', strlen('_')) or die("Could not send data to server");
    socket_close($socket);
}
?>
<br>
<form method="post" action="">
    <input type="text" name="message"/><br><br>
    <input type="submit" name="submit" value="Submit"/>
</form>
<p>Response:</p>
<div id="response"></div>  
<script>
setInterval(()=>{
fetch('http://test.loc/response.json')  
    .then((response) => {  
        return response.json();  
    })  
    .then((data) => {
        console.log(data)
        if(data) {
            document.getElementById('response').innerHTML = data;
        }
    })  
}, 1000);
</script>
</body>
</html>
