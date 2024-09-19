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

try {
    // Conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar todos os eventos em ordem crescente pelo ID
    $sql = "SELECT * FROM calendario ORDER BY id_evento ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista - Recebidos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .especialidade {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="especialidade">
            <h1>Lista - Recebidos</h1>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID do Evento</th>
                    <th>Título</th>
                    <th>Horário Inicial</th>
                    <th>Cor</th>
                    <th>Data</th>
                    <th>Horário Final</th>
                    <th>Alterar</th>
                    <th>Deletar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($eventos)): ?>
                    <?php foreach ($eventos as $evento): ?>
                    <tr>
                       <td><?php echo htmlspecialchars($evento['id_evento']); ?></td>
                       <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                       <td><?php echo htmlspecialchars(date('H:i', strtotime($evento['horario']))); ?></td>
                       <td>
                           <span style="display:inline-block; width: 20px; height: 20px; background-color: <?php echo htmlspecialchars($evento['cor']); ?>;"></span>
                           <?php echo htmlspecialchars($evento['cor']); ?>
                       </td>
                       <td>
                           <?php
                           $inicio = $evento['inicio'];
                           // Exibir a data no formato d/m/Y se for diferente de '0000-00-00 00:00:00'
                           echo ($inicio && $inicio != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($inicio)) : '';
                           ?>
                       </td>
                       <td><?php echo htmlspecialchars(date('H:i', strtotime($evento['fim_horario']))); ?></td>
                       <td><a class="btn btn-primary" href="alterar.php?id_evento=<?php echo htmlspecialchars($evento['id_evento']); ?>">Alterar</a></td>
                       <td><a class="btn btn-danger" href="deletar.php?id_evento=<?php echo htmlspecialchars($evento['id_evento']); ?>" onclick="return confirm('Tem certeza que deseja deletar este evento?');">Deletar</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nenhum evento encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a class="btn btn-primary" href="calendario.php">Voltar</a>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
