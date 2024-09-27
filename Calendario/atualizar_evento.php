<?php
// Conexão com o banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se todos os dados foram enviados
    if (isset($_POST['id_evento'], $_POST['titulo'], $_POST['horario'], $_POST['fim_horario'], $_POST['cor'], $_POST['inicio'])) {

        // Verificar se já existe um evento no mesmo horário e data (exceto o evento atual)
        $sql = "SELECT COUNT(*) FROM calendario WHERE inicio = :inicio AND ((horario <= :fim_horario AND fim_horario >= :horario) AND id_evento != :id_evento)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':inicio', $_POST['inicio']);
        $stmt->bindParam(':horario', $_POST['horario']);
        $stmt->bindParam(':fim_horario', $_POST['fim_horario']);
        $stmt->bindParam(':id_evento', $_POST['id_evento']);
        $stmt->execute();

        $eventosConflitantes = $stmt->fetchColumn();

        if ($eventosConflitantes > 0) {
            // Se houver conflitos, redirecionar com mensagem de erro
            header("Location: alterar_calendario.php?id_evento=" . $_POST['id_evento'] . "&erro=conflito");
            exit;
        }

        // Se não houver conflitos, atualizar o evento no banco de dados
        $sql = "UPDATE calendario SET titulo = :titulo, horario = :horario, fim_horario = :fim_horario, cor = :cor, inicio = :inicio WHERE id_evento = :id_evento";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_evento', $_POST['id_evento'], PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $_POST['titulo']);
        $stmt->bindParam(':horario', $_POST['horario']);
        $stmt->bindParam(':fim_horario', $_POST['fim_horario']);
        $stmt->bindParam(':cor', $_POST['cor']);
        $stmt->bindParam(':inicio', $_POST['inicio']);
        $stmt->execute();

        // Redirecionar de volta à lista de eventos
        header("Location: lista_recebidos.php");
        exit;
    } else {
        echo "Dados incompletos!";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
