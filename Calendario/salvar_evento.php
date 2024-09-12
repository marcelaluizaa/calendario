<?php
// Habilitar exibição de erros (para desenvolvimento)
// Remova ou comente estas linhas em produção
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
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
        $titulo = $data['titulo'];
        $horario = $data['horario'];
        $cor = isset($data['cor']) ? $data['cor'] : '#007bff';
        $inicio = $data['inicio'];
        $fim_horario = $data['fim_horario'];

        // Validação de horário
        if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $horario) || !preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $fim_horario)) {
            echo json_encode(["status" => "error", "message" => "Horário no formato inválido."]);
            exit;
        }

        // Verificar se o horário está ocupado
        $sql = "SELECT COUNT(*) FROM calendario WHERE DATE(inicio) = :inicio AND TIME(horario) = :horario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':horario', $horario);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(["status" => "error", "message" => "O horário selecionado já está ocupado."]);
            exit;
        }

        $horario = date('H:i:00', strtotime($data['horario']));
        $fim_horario = date('H:i:00', strtotime($data['fim_horario']));
        
        // Inserir os dados no banco de dados
        $sql = "INSERT INTO calendario (titulo, horario, cor, inicio, fim_horario) 
                VALUES (:titulo, :horario, :cor, :inicio, :fim_horario)";
        $stmt = $conn->prepare($sql);
        
        // Vincular os parâmetros aos valores
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':horario', $horario);
        $stmt->bindParam(':cor', $cor);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fim_horario', $fim_horario);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Evento salvo com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao salvar o evento."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dados incompletos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>
