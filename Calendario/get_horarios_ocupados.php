<?php
// Habilitar exibição de erros (para desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações do banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

header('Content-Type: application/json');

try {
    // Conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar horários ocupados
    $sql = "SELECT horario FROM calendario WHERE DATE(inicio) = CURDATE()"; // Exemplo para o dia atual
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $horariosOcupados = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($horariosOcupados);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>
