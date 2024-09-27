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

    // Obter os dados da requisição POST (JSON)
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar se os dados foram recebidos corretamente
    if (!empty($data)) {
        $titulo = $data['titulo'];
        $cor = isset($data['cor']) ? $data['cor'] : '#007bff';
        $inicio = $data['inicio'];
        $fim_horario = $data['fim_horario'];
        $horario = $data['horario'];

        // Verificar se o horário já está ocupado para a data
        $sqlCheck = "SELECT COUNT(*) FROM calendario WHERE inicio = :inicio AND horario = :horario";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':inicio', $inicio);
        $stmtCheck->bindParam(':horario', $horario);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Retornar uma mensagem de erro se o horário já estiver ocupado
            echo json_encode(["status" => "error", "message" => "Horário ocupado"]);
        } else {
            // Inserir os dados no banco de dados
            $sqlInsert = "INSERT INTO calendario (titulo, cor, inicio, fim_horario, horario) 
                          VALUES (:titulo, :cor, :inicio, :fim_horario, :horario)";
            $stmtInsert = $conn->prepare($sqlInsert);

            // Vincular os parâmetros
            $stmtInsert->bindParam(':titulo', $titulo);
            $stmtInsert->bindParam(':cor', $cor);
            $stmtInsert->bindParam(':inicio', $inicio);
            $stmtInsert->bindParam(':fim_horario', $fim_horario);
            $stmtInsert->bindParam(':horario', $horario);

            // Execução da inserção
            if ($stmtInsert->execute()) {
                $eventoId = $conn->lastInsertId();
                echo json_encode([
                    "status" => "success",
                    "message" => "Evento salvo com sucesso!",
                    "evento" => [
                        "id" => $eventoId,
                        "title" => $titulo,
                        "start" => $inicio,
                        "end" => $fim_horario,
                        "color" => $cor,
                        "horario" => $horario
                    ]
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erro ao salvar o evento."]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dados incompletos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro no servidor: " . $e->getMessage()]);
}
?>