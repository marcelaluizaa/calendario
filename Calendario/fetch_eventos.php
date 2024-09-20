<?php
<<<<<<< HEAD
// Habilitar exibição de erros (remover em produção)
=======
// Habilitar exibição de erros (para desenvolvimento)
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
// Configurações de conexão com o banco de dados
=======
// Configurações do banco de dados
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

header('Content-Type: application/json');

try {
<<<<<<< HEAD
    // Conectar ao banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os eventos
    $sql = "SELECT id_evento AS id, titulo AS title, inicio AS start, CONCAT(inicio, ' ', fim_horario) AS end, cor AS color
            FROM calendario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Buscar os eventos e retornar como JSON
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($eventos);
} catch (PDOException $e) {
    // Retornar mensagem de erro
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>
=======
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
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
