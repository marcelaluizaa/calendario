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

    // Verificar se a data foi enviada como parâmetro
    $start = isset($_GET['start']) ? $_GET['start'] : null;
    $end = isset($_GET['end']) ? $_GET['end'] : null;

    if ($start && $end) {
        // Consultar eventos dentro do intervalo de tempo fornecido pelo FullCalendar
        $sql = "SELECT id_evento, titulo, cor, inicio, fim 
                FROM calendario 
                WHERE inicio BETWEEN :start AND :end";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();

        // Buscar os resultados e retornar no formato JSON
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Certificar que sempre retornamos um array (mesmo que vazio)
        if (empty($eventos)) {
            echo json_encode([]);
            exit();
        }

        // Processar e formatar os eventos
        $resultados = [];
        foreach ($eventos as $evento) {
            $resultados[] = [
                'id' => $evento['id_evento'],
                'title' => $evento['titulo'],
                'start' => $evento['inicio'],  // `inicio` já é do tipo DATETIME
                'end' => $evento['fim'],       // Usando o campo `fim` para data e hora de término
                'color' => $evento['cor'],
            ];
        }

        echo json_encode($resultados);
    } else {
        echo json_encode(["status" => "error", "message" => "Parâmetros de data inválidos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>
