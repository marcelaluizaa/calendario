<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        #calendar-container {
            max-width: 2000px;
            margin: 0 auto;
        }
        #calendar {
            max-width: 1800px;
            margin: 0 auto;
        }
        .horario-ocupado {
            color: red;
            font-size: 12px;
            font-weight: bold;
        }
        .fc-event {
            position: relative;
        }
        /* Define uma altura fixa para os dias no view DayGrid */
        .fc-daygrid-day-frame {
            height: 200px;
            overflow: hidden;
        }
        .fc-daygrid-day-top {
            height: 10px;
        }
        .time-slot:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .time-slot.selected {
            background-color: #ffdddd;
        }
        .time-slot .time-label {
            position: absolute;
            top: 0;
            left: 5px;
            color: #333;
            font-size: 12px;
        }
        .time-slot .event-info {
            position: absolute;
            top: 50%;
            left: 5px;
            color: #fff;
            background-color: #d9534f;
            padding: 2px 5px;
            border-radius: 3px;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div id="calendar-container">
        <div id='calendar'></div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/pt-br.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

        function populateHorarios(dataSelecionada) {
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
                    const selectHorario = document.getElementById('eventTime');
                    selectHorario.innerHTML = '';

                    horarios.forEach(horario => {
                        let option = document.createElement('option');
                        option.value = horario;
                        option.textContent = horario;

                        if (horariosOcupados.includes(horario)) {
                            option.disabled = true;
                            option.textContent += ' (horário já selecionado)';
                        }

                        selectHorario.appendChild(option);
                    });

                    document.getElementById('eventTime').addEventListener('change', function() {
                        let horarioSelecionado = this.value;
                        let partes = horarioSelecionado.split(':');
                        let fimHorario = new Date();
                        fimHorario.setHours(parseInt(partes[0]), parseInt(partes[1]) + 30);
                        document.getElementById('eventEndTime').value = fimHorario.toTimeString().slice(0, 5);
                    });
                })
                .catch(error => console.error('Erro ao carregar horários ocupados:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                selectable: true,
                events: {
                    url: 'fetch_eventos.php',
                    failure: function() {
                        alert('Erro ao carregar eventos!');
                    }
                },
                select: function(info) {
                    document.getElementById('eventForm').reset();
                    document.getElementById('eventStart').value = info.startStr;
                    document.getElementById('eventEndTime').value = '';
                    populateHorarios(info.startStr);
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    eventModal.show();
                },
                eventContent: function(arg) {
                    let horario = arg.event.extendedProps.horario;
                    if (!horario || horario === '00:00:00') {
                        horario = '';
                    } else {
                        let partesHorario = horario.split(':');
                        horario = `${partesHorario[0]}:${partesHorario[1]}`;
                    }

                    let customHtml = document.createElement('div');
                    customHtml.innerHTML = `<b>${arg.event.title}</b><br>Horário: ${horario}`;
                    return { domNodes: [customHtml] };
                }
            });

            calendar.render();

            document.getElementById('saveEventBtn').addEventListener('click', function() {
                var titulo = document.getElementById('eventTitle').value.trim();
                var horario = document.getElementById('eventTime').value;
                var fim_horario = document.getElementById('eventEndTime').value;
                var cor = document.getElementById('eventColor').value;
                var inicio = document.getElementById('eventStart').value;

                if (titulo && horario) {
                    var evento = {
                        titulo: titulo,
                        cor: cor,
                        inicio: inicio,
                        fim_horario: fim_horario,
                        horario: horario
                    };

                    fetch('save_evento.php', {
                        method: 'POST',
                        body: JSON.stringify(evento),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        var eventModal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                        eventModal.hide();
                    })
                    .catch(error => console.error('Erro ao salvar evento:', error));
                }
            });
        });
    </script>
</body>
</html>
