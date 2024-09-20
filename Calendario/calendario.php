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
<<<<<<< HEAD
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
            height: 200px; /* Ajuste o valor conforme necessário */
            overflow: hidden; /* Para evitar que o conteúdo extrapole a altura definida */
        }
        /* Ajusta a altura da área de conteúdo para eventos */
        .fc-daygrid-day-top {
            height: 10px; /* Ajuste a altura do cabeçalho do dia se necessário */
=======
            max-width: 1800px;
            margin: 0 auto;
        }
        #calendar {
            max-width: 1300px;
            margin: 0 auto;
        }
        .fc-daygrid-day-frame {
            position: relative;
        }
        .time-slot {
            height: calc(100% / 14); /* Divida o dia em 14 partes */
            border: 1px solid #ddd;
            position: absolute;
            left: 0;
            right: 0;
            overflow: hidden;
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
            font-size: 12px;
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
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
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
<<<<<<< HEAD
                   
                },
                events: {
                    url: 'fetch_eventos.php',
                    failure: function() {
                        alert('Erro ao carregar eventos!');
                    }
                },
                dateClick: function(info) {
                    // Preenche o campo de data inicial e atualiza os horários disponíveis
                    document.getElementById('eventStart').value = info.dateStr;
                    updateAvailableTimes(info.dateStr);

                    // Limpa o formulário do modal
                    document.getElementById('eventForm').reset();
                    document.getElementById('eventEndTime').value = '';
                    document.getElementById('eventModalLabel').textContent = 'Adicionar Evento';

                    // Mostra o modal
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    eventModal.show();
                },
                viewDidMount: function(view) {
                    // Ajusta a altura dos dias no início
                    const dayCells = document.querySelectorAll('.fc-daygrid-day-frame');
                    let maxHeight = 0;

                    // Calcula a altura máxima
                    dayCells.forEach(cell => {
                        const height = cell.clientHeight;
                        if (height > maxHeight) {
                            maxHeight = height;
                        }
                    });

                    // Define a altura máxima para todas as células
                    dayCells.forEach(cell => {
                        cell.style.height = `${maxHeight}px`;
                    });
=======
                    
                },
                events: 'fetch_eventos.php', // Carregar eventos já existentes
                select: function(info) {
                    document.getElementById('eventForm').reset();
                    document.getElementById('eventStart').value = info.startStr;
                    document.getElementById('eventEndTime').value = ''; // Limpar horário final
                    populateHorarios(info.startStr); // Atualizar horários de acordo com o dia selecionado
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    eventModal.show();
                },
                eventContent: function(arg) {
                    let customHtml = document.createElement('div');
                    customHtml.innerHTML = `<b>${arg.event.title}</b><br>Horário selecionado: ${arg.event.extendedProps.horario}`;
                    return { domNodes: [customHtml] };
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
                }
            });

            calendar.render();

<<<<<<< HEAD
            // Função para atualizar a lista de horários disponíveis
            function updateAvailableTimes(date) {
                fetch(`fetch_available_times.php?date=${date}`)
=======
            // Função para gerar horários
            function gerarHorarios(inicio, intervalo, fim1, fim2) {
                let horarios = [];
                let horaAtual = new Date(inicio);

                // Intervalo 1: de 08:30 até 11:25
                while (horaAtual <= fim1) {
                    let horas = String(horaAtual.getHours()).padStart(2, '0');
                    let minutos = String(horaAtual.getMinutes()).padStart(2, '0');
                    horarios.push(`${horas}:${minutos}`);
                    horaAtual.setMinutes(horaAtual.getMinutes() + intervalo);
                }

                // Pular intervalo de almoço: de 11:25 até 13:10
                horaAtual = new Date(fim1);
                horaAtual.setHours(13, 10, 0, 0);

                // Intervalo 2: de 13:10 até 17:15
                while (horaAtual <= fim2) {
                    let horas = String(horaAtual.getHours()).padStart(2, '0');
                    let minutos = String(horaAtual.getMinutes()).padStart(2, '0');
                    horarios.push(`${horas}:${minutos}`);
                    horaAtual.setMinutes(horaAtual.getMinutes() + intervalo);
                }

                return horarios;
            }

            // Função para atualizar a lista de horários disponíveis
<<<<<<< HEAD
            function populateHorarios(dataSelecionada) {
                fetch('get_horarios_ocupados.php?data=' + dataSelecionada)
=======
            function populateHorarios() {
                fetch('get_horarios_ocupados.php')
>>>>>>> 8d00f506d5cc340cc56621bf36b1300189d4ec60
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            var select = document.getElementById('eventTime');
                            select.innerHTML = ''; // Limpa opções existentes
                            data.times.forEach(time => {
                                var option = document.createElement('option');
                                option.value = time;
                                option.textContent = time;
                                select.appendChild(option);
                            });
                        } else {
                            alert('Erro ao carregar horários disponíveis.');
                        }
                    })
                    .catch(error => console.error('Erro ao carregar horários disponíveis:', error));
            }

            // Função para calcular o fim do evento (35 minutos após o horário de início)
            document.getElementById('eventTime').addEventListener('change', function() {
                var selectedTime = this.value;
                if (this.selectedOptions[0].disabled) {
                    alert('Horário indisponível');
                    this.value = ''; // Limpar seleção se horário estiver indisponível
                    document.getElementById('eventEndTime').value = ''; // Limpar horário final
                } else {
                    var [hours, minutes] = selectedTime.split(':').map(Number);
                    var endTime = new Date();
                    endTime.setHours(hours);
                    endTime.setMinutes(minutes + 35); // Adiciona 35 minutos

                    var endHours = String(endTime.getHours()).padStart(2, '0');
                    var endMinutes = String(endTime.getMinutes()).padStart(2, '0');
                    document.getElementById('eventEndTime').value = `${endHours}:${endMinutes}`;
                }
            });

            // Função para salvar o evento
            document.getElementById('saveEventBtn').addEventListener('click', function() {
                var titulo = document.getElementById('eventTitle').value.trim();
                var horario = document.getElementById('eventTime').value;
                var fim_horario = document.getElementById('eventEndTime').value;
                var cor = document.getElementById('eventColor').value;
                var inicio = document.getElementById('eventStart').value;

                if (titulo && horario && fim_horario) {
                    // Criar objeto com os dados do evento
                    var eventData = {
                        titulo: titulo,
                        horario: horario,
                        inicio: inicio,
                        fim_horario: fim_horario,
                        cor: cor
                    };

                    // Enviar dados ao backend PHP usando fetch API
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
                            // Se o evento for salvo corretamente, adicionar ao calendário
                            var novoEvento = {
                                id: data.evento.id,
                                title: data.evento.title,
                                start: data.evento.start,
                                end: data.evento.end,
<<<<<<< HEAD
                                color: data.evento.color
                            };
                            calendar.addEvent(novoEvento);

                            // Fechar o modal
                            var eventModal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                            eventModal.hide();
=======
                                color: data.evento.color,
                                extendedProps: {
                                    horario: data.evento.horario
                                }
                            };

                            // Adiciona o evento ao calendário
                            calendar.addEvent(novoEvento);

                            // Fecha o modal
                            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                            eventModal.hide();
=======
                            // Redirecionar para lista_recebidos.php após sucesso
                            window.location.href = 'lista_recebidos.php';
>>>>>>> 8d00f506d5cc340cc56621bf36b1300189d4ec60
>>>>>>> cde088375a51c20d748577685ee828c54e1fad07
                        } else {
                            alert('Erro ao salvar evento: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao salvar evento:', error);
                        alert('Erro ao salvar evento. Verifique o console para mais detalhes.');
                    });
                } else {
                    alert('Por favor, preencha todos os campos.');
                }
            });
        });
    </script>
</body>
</html>
