<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

header('Content-Type: application/json');

try {
    // Conectar ao banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pegar a data enviada por GET
    $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

    // Buscar horários ocupados para a data selecionada
    $sql = "SELECT TIME_FORMAT(inicio, '%H:%i') as horario_ocupado 
            FROM calendario 
            WHERE DATE(inicio) = :data";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':data', $data);
    $stmt->execute();
    $horariosOcupados = $stmt->fetchAll(PDO::FETCH_COLUMN); // Retorna um array de horários

    echo json_encode($horariosOcupados); // Retorna no formato correto
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>
