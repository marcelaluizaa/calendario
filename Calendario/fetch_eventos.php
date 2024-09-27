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
    // Conectar ao banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os eventos
    $sql = "SELECT id_evento AS id, titulo AS title, inicio AS start, 
            CONCAT(inicio, ' ', fim_horario) AS end, cor AS color, horario 
            FROM calendario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Retornar os eventos em formato JSON
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($eventos);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>