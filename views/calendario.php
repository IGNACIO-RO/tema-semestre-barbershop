<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}
include __DIR__ . '/layouts/header.php';
?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<div class="PANEL-PRINCIPAL">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <div class="CONTENIDO">
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        <div class="BLOQUE">
            <div class="HEADER">
                <h2>Calendario de Citas</h2>
            </div>
            <div class="CUERPO">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'timeGridWeek',
        slotMinTime: '08:00:00',
        slotMaxTime: '21:00:00',
        height: 700,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '../controllers/CalendarController.php'
    });
    calendar.render();
});
</script>
<?php include __DIR__ . '/layouts/footer.php'; ?>