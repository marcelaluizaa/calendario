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
            throw new Exception('Erro ao salvar o evento.');
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
