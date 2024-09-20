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
            height: calc(100% / 14);
            /* Divida o dia em 14 partes */
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
                            <input type="text" class="form-control" id="eventEndTime" name="fim" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="eventColor" class="form-label">Cor do Evento</label>
                            <input type="color" class="form-control form-control-color" id="eventColor" name="cor"
                                value="#007bff">
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
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            // Criar a instância do modal uma vez
            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                selectable: true,
                editable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                },
                // Carregar eventos já existentes no calendário
                events: function (fetchInfo, successCallback, failureCallback) {
                    fetch('fetch_eventos.php?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Dados recebidos do servidor:', data);
                            let eventos = data.map(event => {
                                return {
                                    id: event.id,
                                    title: event.title,
                                    start: event.start,
                                    end: event.end,
                                    color: event.color
                                };
                            });
                            successCallback(eventos);
                        })
                        .catch(error => {
                            console.error('Erro ao carregar eventos:', error);
                            failureCallback(error);
                        });
                },

                select: function (info) {
                    document.getElementById('eventStart').value = info.startStr;
                    document.getElementById('eventEndTime').value = ''; // Limpar horário final
                    populateHorarios(info.startStr); // Atualizar horários de acordo com o dia selecionado
                    eventModal.show(); // Exibir o modal
                },
            });
            calendar.render();

            // Função para gerar uma lista de horários
            function gerarHorarios(horaInicio, intervalo, fim1, fim2) {
                let horarios = [];
                let horaAtual = new Date(horaInicio);

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
                console.log('Populando horários para a data:', dataSelecionada);
                fetch('get_horarios_ocupados.php?data=' + dataSelecionada)
                    .then(response => response.json())
                    .then(horariosOcupados => {
                        console.log('Horários ocupados:', horariosOcupados);

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

                            if (Array.isArray(horariosOcupados) && horariosOcupados.includes(horario)) {
                                option.disabled = true;
                                option.textContent += ' (horário indisponível)';
                            }
                            selectHorario.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao buscar horários ocupados:', error);
                    });
            }

            // Função para calcular o fim do evento
            document.getElementById('eventTime').addEventListener('change', function () {
                var selectedTime = this.value;
                if (this.selectedOptions[0].disabled) {
                    alert('Horário indisponível');
                    this.value = '';
                    document.getElementById('eventEndTime').value = '';
                } else {
                    var [hours, minutes] = selectedTime.split(':').map(Number);
                    var endTime = new Date();
                    endTime.setHours(hours);
                    endTime.setMinutes(minutes + 35);

                    var endHours = String(endTime.getHours()).padStart(2, '0');
                    var endMinutes = String(endTime.getMinutes()).padStart(2, '0');
                    document.getElementById('eventEndTime').value = `${endHours}:${endMinutes}`;
                }
            });

            // Função para salvar o evento
            document.getElementById('saveEventBtn').addEventListener('click', function () {
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
                        fim: fim_horario
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
                                var novoEvento = {
                                    id: data.evento.id,
                                    title: data.evento.title,
                                    start: data.evento.start,
                                    end: data.evento.end,
                                    color: data.evento.color
                                };

                                calendar.addEvent(novoEvento);
                                eventModal.hide();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Erro ao salvar o evento:', error));
                } else {
                    alert('O título, horário e horário final do evento são obrigatórios.');
                }
            });

        });
    </script>
</body>

</html>
