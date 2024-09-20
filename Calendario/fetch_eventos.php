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

    // Consulta para obter todos os eventos
    $sql = "SELECT id_evento, titulo AS title, horario AS start, fim_horario AS end, cor AS color FROM calendario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Buscar os eventos
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    
    // Retornar os eventos em formato JSON
    echo json_encode($events);

} catch (PDOException $e) {
    // Retornar mensagem de erro de conexão ou consulta
    echo json_encode(["success" => false, "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>