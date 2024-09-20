<?php
header('Content-Type: application/json');

// Configurações de conexão com o banco de dados
$host = 'localhost'; // Substitua pelo seu host
$dbname = 'ca'; // Substitua pelo seu banco de dados
$username = 'root'; // Substitua pelo seu usuário
$password = ''; // Substitua pela sua senha

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtém os dados JSON do POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Valida os dados
    if (isset($data['titulo'], $data['inicio'], $data['fim_horario'], $data['horario'], $data['cor'])) {
        $titulo = $data['titulo'];
        $inicio = $data['inicio'];
        $fim_horario = $data['fim_horario'];
        $horario = $data['horario'];
        $cor = $data['cor'];

<<<<<<< HEAD
        // Verifica se o horário já está ocupado
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM calendario
            WHERE DATE(inicio) = DATE(:inicio) AND horario = :horario
        ");
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':horario', $horario);
        $stmt->execute();
        $count = $stmt->fetchColumn();
=======
        // Verificar se o horário já está ocupado
        $sqlCheck = "SELECT COUNT(*) FROM calendario 
                     WHERE inicio = :inicio 
<<<<<<< HEAD
                     AND (horario < :fim_horario AND fim_horario > :horario)";
=======
                     AND (horario <= :fim_horario AND fim_horario >= :horario)";
>>>>>>> 8d00f506d5cc340cc56621bf36b1300189d4ec60
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':inicio', $inicio);
        $stmtCheck->bindParam(':horario', $horario);
        $stmtCheck->bindParam(':fim_horario', $fim_horario);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07

        if ($count > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Horário já reservado.'
            ]);
            exit;
        }

        // Prepara e executa a inserção no banco de dados
        $sql = "INSERT INTO calendario (titulo, inicio, fim_horario, horario, cor) VALUES (:titulo, :inicio, :fim_horario, :horario, :cor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fim_horario', $fim_horario);
        $stmt->bindParam(':horario', $horario);
        $stmt->bindParam(':cor', $cor);

        if ($stmt->execute()) {
            // Recupera o último ID inserido
            $id = $pdo->lastInsertId();

            // Retorna a resposta de sucesso
            echo json_encode([
                'status' => 'success',
                'evento' => [
                    'id' => $id,
                    'title' => $titulo,
                    'start' => $inicio,
                    'end' => $fim_horario,
                    'color' => $cor
                ]
            ]);
        } else {
<<<<<<< HEAD
            throw new Exception('Erro ao salvar o evento.');
=======
            // Inserir os dados no banco de dados
            $sqlInsert = "INSERT INTO calendario (titulo, horario, cor, inicio, fim_horario) 
                          VALUES (:titulo, :horario, :cor, :inicio, :fim_horario)";
            $stmtInsert = $conn->prepare($sqlInsert);

            // Vincular os parâmetros aos valores
            $stmtInsert->bindParam(':titulo', $titulo);
            $stmtInsert->bindParam(':horario', $horario);
            $stmtInsert->bindParam(':cor', $cor);
            $stmtInsert->bindParam(':inicio', $inicio);
            $stmtInsert->bindParam(':fim_horario', $fim_horario);

            // Executar a consulta e verificar o resultado
            if ($stmtInsert->execute()) {
<<<<<<< HEAD
                // Obter o ID do evento recém inserido
                $eventoId = $conn->lastInsertId();

                // Retornar os dados do evento salvo
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
=======
                // Retornar resposta de sucesso
                echo json_encode(["status" => "success", "message" => "Evento salvo com sucesso!"]);
>>>>>>> 8d00f506d5cc340cc56621bf36b1300189d4ec60
            } else {
                // Retornar mensagem de erro
                echo json_encode(["status" => "error", "message" => "Erro ao salvar o evento."]);
            }
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
        }
    } else {
        throw new Exception('Dados incompletos.');
    }
} catch (PDOException $e) {
    // Erro de conexão ou execução SQL
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Erro geral
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
