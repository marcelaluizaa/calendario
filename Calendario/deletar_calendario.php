<?php
// Configurações do banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

try {
    // Conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o id_evento foi passado pela URL
    if (isset($_GET['id_evento'])) {
        $id_evento = $_GET['id_evento'];

        // Verificar se o evento existe no banco de dados
        $sql = "SELECT * FROM calendario WHERE id_evento = :id_evento";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($evento) {
            // Se o evento existir, deletá-lo
            $sql = "DELETE FROM calendario WHERE id_evento = :id_evento";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
            $stmt->execute();

            // Redirecionar de volta para a lista de eventos com sucesso
            header("Location: lista_recebidos.php?msg=deletado");
            exit;
        } else {
            // Se o evento não for encontrado, redirecionar com erro
            header("Location: lista_recebidos.php?msg=nao_encontrado");
            exit;
        }
    } else {
        // Se o id_evento não foi fornecido, redirecionar com erro
        header("Location: lista_recebidos.php?msg=erro");
        exit;
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
