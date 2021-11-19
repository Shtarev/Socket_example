<?php

$host = "127.0.0.1";
$port = 25003;
$arr = array();
$output = '';
$kolvo = 5;
$count = 0;
$while_go = true;
$response = "response.json";
$data = "data.txt";

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket");
socket_bind($socket, $host, $port) or die("Could not bind to socket");
socket_listen($socket, 3) or die("Could not set up socket listener");

while ($while_go) {
    $spawn = socket_accept($socket) or die("Could not accept incoming connection");    
    $input = socket_read($spawn, 1024) or die("Could not read input");
    switch ($input)
    {
        case "_":
        if($output) {
            socket_write($spawn, $output, strlen ($output));
        }
        else {
            socket_write($spawn, "_", strlen ("_"));
            file_put_contents($response, $count);
            }
        break;

        default:
        $count++;
        if($count < 5) {
            array_push($arr, $input); 
            $output .= "$count) $input <br>";
            socket_write($spawn, $output, strlen ($output));
            file_put_contents($response, json_encode($output, JSON_UNESCAPED_UNICODE));
        }
        else {
            array_push($arr, $input);
            $output .= "$count) $input <br>Stop Socket Server";
            socket_write($spawn, $output, strlen ($output));
            file_put_contents($response, json_encode($output, JSON_UNESCAPED_UNICODE));
            $while_go = false;
        }
        break;
    }
    socket_close($spawn);
} 
socket_close($socket);

foreach($arr as $key => $value) {
    file_put_contents($data, "$key - $value".PHP_EOL, FILE_APPEND | LOCK_EX);
}
?>