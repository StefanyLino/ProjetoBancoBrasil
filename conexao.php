<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adiciona a coluna 'imagem' à tabela 'produtos' se ela não existir
$sql = "SHOW COLUMNS FROM produtos LIKE 'imagem'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE produtos ADD COLUMN imagem VARCHAR(255)";
    $conn->query($sql);
}

// Adiciona a coluna 'imagem' à tabela 'fornecedores' se ela não existir
$sql = "SHOW COLUMNS FROM fornecedores LIKE 'imagem'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE fornecedores ADD COLUMN imagem VARCHAR(255)";
    $conn->query($sql);
}
?>