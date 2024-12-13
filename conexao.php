<?php
$hostname = "localhost";
$database = "teste_agora";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($hostname, $usuario, $senha, $database);

if ($mysqli->connect_errno) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}
