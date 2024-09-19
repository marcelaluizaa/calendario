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

    // Obtém a data do GET
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    // Verifica se a data foi fornecida
    if ($date) {
        // Obtém os horários já reservados para a data
        $stmt = $pdo->prepare("
            SELECT horario
            FROM calendario
            WHERE DATE(inicio) = :date
        ");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $reservado = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Define os horários disponíveis
        $todosHorarios = [];
        for ($h = 9; $h <= 17; $h++) {
            for ($m = 0; $m < 60; $m += 30) {
                $hora = sprintf('%02d:%02d', $h, $m);
                if (!in_array($hora, $reservado)) {
                    $todosHorarios[] = $hora;
                }
            }
        }

        // Retorna os horários disponíveis
        echo json_encode([
            'status' => 'success',
            'times' => $todosHorarios
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data não fornecida.'
        ]);
    }
} catch (PDOException $e) {
    // Erro de conexão ou execução SQL
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
}
?>
