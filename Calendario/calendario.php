<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Eventos</title>
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        #calendar-container {
            max-width: 900px;
            margin: 0 auto;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div id="calendar-container">
        <div id='calendar'></div>
    </div>

    <!-- Modal para adicionar/editar eventos -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="eventModalLabel" class="modal-title">Adicionar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Título do Evento</label>
                            <input type="text" class="form-control" id="eventTitle" name="titulo" required>
                        </div>

                        <div class="mb-3">
                            <label for="eventTime" class="form-label">Horário Inicial</label>
                            <select class="form-control" id="eventTime" name="horario" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="eventEndTime" class="form-label">Horário de Fim</label>
                            <input type="text" class="form-control" id="eventEndTime" name="fim_horario" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="eventColor" class="form-label">Cor do Evento</label>
                            <input type="color" class="form-control form-control-color" id="eventColor" name="cor" value="#007bff">
                        </div>

                        <input type="hidden" id="eventStart" name="inicio">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveEventBtn">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/pt-br.js'></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        selectable: true,
        editable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        select: function(info) {
            document.getElementById('eventForm').reset();
            document.getElementById('eventStart').value = info.startStr;
            document.getElementById('eventEndTime').value = ''; // Limpar horário final
            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        },
        events: 'fetch_eventos.php',
        eventColor: '#378006'
    });
    calendar.render();

    // Função para gerar horários
    function gerarHorarios(inicio, fim, intervalo) {
        let horarios = [];
        let horaAtual = new Date(inicio);
        let horaFim = new Date(fim);

        while (horaAtual <= horaFim) {
            let horas = String(horaAtual.getHours()).padStart(2, '0');
            let minutos = String(horaAtual.getMinutes()).padStart(2, '0');
            horarios.push(`${horas}:${minutos}`);
            horaAtual.setMinutes(horaAtual.getMinutes() + intervalo);
        }
        return horarios;
    }

    // Horários antes do intervalo de almoço
    const inicioManha = new Date();
    inicioManha.setHours(8, 30, 0, 0); // Horário inicial
    const fimManha = new Date();
    fimManha.setHours(11, 25, 0, 0); // Horário final da manhã

    // Horários depois do intervalo de almoço
    const inicioTarde = new Date();
    inicioTarde.setHours(13, 10, 0, 0); // Horário inicial da tarde
    const fimTarde = new Date();
    fimTarde.setHours(17, 15, 0, 0); // Horário final da tarde

    const intervalo = 35;  // Intervalo em minutos

    const horariosManha = gerarHorarios(inicioManha, fimManha, intervalo);
    const horariosTarde = gerarHorarios(inicioTarde, fimTarde, intervalo);

    // Combina os horários da manhã e da tarde
    const horarios = horariosManha.concat(horariosTarde);

    // Popula o select de horários
    const selectHorario = document.getElementById('eventTime');

    horarios.forEach(horario => {
        let option = document.createElement('option');
        option.value = horario;
        option.textContent = horario;
        selectHorario.appendChild(option);
    });

    // Função para calcular o fim do evento (35 minutos após o horário de início)
    document.getElementById('eventTime').addEventListener('change', function() {
        var selectedTime = this.value;
        var [hours, minutes] = selectedTime.split(':').map(Number);
        
        var endTime = new Date();
        endTime.setHours(hours);
        endTime.setMinutes(minutes + 35); // Adiciona 35 minutos

        var endHours = String(endTime.getHours()).padStart(2, '0');
        var endMinutes = String(endTime.getMinutes()).padStart(2, '0');
        document.getElementById('eventEndTime').value = `${endHours}:${endMinutes}`;
    });

    // Função para salvar o evento
    document.getElementById('saveEventBtn').addEventListener('click', function() {
        var titulo = document.getElementById('eventTitle').value.trim();
        var horario = document.getElementById('eventTime').value;
        var fim_horario = document.getElementById('eventEndTime').value;
        var cor = document.getElementById('eventColor').value;
        var inicio = document.getElementById('eventStart').value;

        if (titulo && horario && fim_horario) {
            var eventData = {
                titulo: titulo,
                horario: horario,
                cor: cor,
                inicio: inicio,
                fim_horario: fim_horario
            };

            fetch('salvar_evento.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = 'lista_recebidos.php'; 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Erro:', error));
            
            var eventModal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
            eventModal.hide();
        } else {
            alert('O título, horário e horário final do evento são obrigatórios.');
        }
    });
});

    </script>
</body>
</html>
