<?php
// Configurações de conexão com o banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

// Habilitar exibição de erros (para desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    // Conectar ao banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter a data da consulta
    $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

    // Consulta para obter os horários ocupados na data selecionada
    $sql = "SELECT horario FROM calendario WHERE DATE(inicio) = :data";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':data', $data);
    $stmt->execute();

    // Retornar os horários ocupados em formato JSON
    $horariosOcupados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($horariosOcupados);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>