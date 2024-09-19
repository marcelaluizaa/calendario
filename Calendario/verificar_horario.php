<?php
// Configurações do banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

try {
    // Conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter o horário e a data da requisição GET
    $horario = isset($_GET['horario']) ? $_GET['horario'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    if ($horario && $data) {
        // Verificar se o horário está ocupado
        $sql = "SELECT COUNT(*) FROM calendario WHERE DATE(inicio) = :data AND TIME(horario) = :horario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':horario', $horario);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(["status" => "ocupado", "message" => "O horário selecionado já está ocupado."]);
        } else {
            echo json_encode(["status" => "disponivel", "message" => "O horário está disponível."]);
        }
    } else {
        echo json_encode(["status" => "erro", "message" => "Horário ou data não fornecidos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "erro", "message" => "Erro na conexão: " . $e->getMessage()]);
}
?>