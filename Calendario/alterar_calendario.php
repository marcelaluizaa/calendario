<?php
// Conexão com o banco de dados
$host = "localhost";
$dbname = "ca";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificando se o id_evento foi passado na URL
    if (isset($_GET['id_evento'])) {
        $id_evento = $_GET['id_evento'];

        // Buscando os dados do evento no banco de dados
        $sql = "SELECT * FROM calendario WHERE id_evento = :id_evento";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o evento não for encontrado, redirecionar ou exibir uma mensagem
        if (!$evento) {
            echo "Evento não encontrado!";
            exit;
        }
    } else {
        echo "ID do evento não fornecido!";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Atualizar Evento</h2>

        <!-- Verificar se existe um erro na URL -->
        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'conflito'): ?>
            <div class="alert alert-danger" role="alert">
                Erro: Já existe um evento neste horário!
            </div>
        <?php endif; ?>

        <form action="atualizar_evento.php" method="POST">
            <!-- Campo oculto para enviar o ID do evento -->
            <input type="hidden" name="id_evento" value="<?php echo htmlspecialchars($evento['id_evento']); ?>">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título do Evento</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="horario" class="form-label">Horário Inicial</label>
                <select class="form-control" id="horario" name="horario" required></select>
            </div>

            <div class="mb-3">
                <label for="fim_horario" class="form-label">Horário de Fim</label>
                <input type="text" class="form-control" id="fim_horario" name="fim_horario" readonly required>
            </div>

            <div class="mb-3">
                <label for="cor" class="form-label">Cor do Evento</label>
                <input type="color" class="form-control" id="cor" name="cor" value="<?php echo htmlspecialchars($evento['cor']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="inicio" class="form-label">Data do Evento</label>
                <input type="date" class="form-control" id="inicio" name="inicio" value="<?php echo htmlspecialchars($evento['inicio']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Evento</button>
            <a class="btn btn-primary" href="lista_recebidos.php">Voltar</a>
        </form>
    </div>

</body>
</html>

<script>
    function gerarHorarios(inicio, intervalo, fim1, fim2) {
        let horarios = [];
        let horaAtual = new Date(inicio);

        while (horaAtual <= fim1) {
            let horas = String(horaAtual.getHours()).padStart(2, '0');
            let minutos = String(horaAtual.getMinutes()).padStart(2, '0');
            horarios.push(`${horas}:${minutos}`);
            horaAtual.setMinutes(horaAtual.getMinutes() + intervalo);
        }

        horaAtual = new Date(fim1);
        horaAtual.setHours(13, 10, 0, 0);

        while (horaAtual <= fim2) {
            let horas = String(horaAtual.getHours()).padStart(2, '0');
            let minutos = String(horaAtual.getMinutes()).padStart(2, '0');
            horarios.push(`${horas}:${minutos}`);
            horaAtual.setMinutes(horaAtual.getMinutes() + intervalo);
        }

        return horarios;
    }

    function populateHorarios(dataSelecionada, horarioSelecionado) {
        fetch('get_horarios_ocupados.php?data=' + dataSelecionada)
            .then(response => response.json())
            .then(horariosOcupados => {
                const horaInicio = new Date();
                horaInicio.setHours(8, 30, 0, 0);
                const intervalo = 35;
                const fim1 = new Date();
                fim1.setHours(11, 25, 0, 0);
                const fim2 = new Date();
                fim2.setHours(17, 15, 0, 0);

                const horarios = gerarHorarios(horaInicio, intervalo, fim1, fim2);
                const selectHorario = document.getElementById('horario');
                selectHorario.innerHTML = '';

                horarios.forEach(horario => {
                    let option = document.createElement('option');
                    option.value = horario;
                    option.textContent = horario;

                    if (horariosOcupados.includes(horario)) {
                        option.disabled = true; // Desabilitar horários ocupados
                        option.textContent += ' (horário ocupado)';
                    }

                    selectHorario.appendChild(option);
                });

                // Selecionar o horário inicial já salvo
                if (horarioSelecionado) {
                    selectHorario.value = horarioSelecionado;
                }

                document.getElementById('horario').addEventListener('change', function() {
                    let horarioSelecionado = this.value;
                    let partes = horarioSelecionado.split(':');
                    let fimHorario = new Date();
                    fimHorario.setHours(parseInt(partes[0]), parseInt(partes[1]) + 30); // Adicionar 30 minutos ao horário final
                    document.getElementById('fim_horario').value = fimHorario.toTimeString().slice(0, 5);
                });
            })
            .catch(error => console.error('Erro ao carregar horários ocupados:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Carregar os horários ao carregar a página
        const dataEvento = document.getElementById('inicio').value;
        const horarioInicial = "<?php echo htmlspecialchars($evento['horario']); ?>";
        populateHorarios(dataEvento, horarioInicial);
    });
</script>

