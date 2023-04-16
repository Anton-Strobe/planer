<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;

    switch ($action) {
        case 'add':
            // добавление события
            break;
        case 'edit':
            $title = isset($_POST['title']) ? $_POST['title'] : null;
            $description = isset($_POST['description']) ? $_POST['description'] : null;
            $description2 = isset($_POST['description2']) ? $_POST['description2'] : null;
            $start_datetime = isset($_POST['start_datetime']) ? $_POST['start_datetime'] : null;
            $end_datetime = isset($_POST['end_datetime']) ? $_POST['end_datetime'] : null;
            $notify_datetime = isset($_POST['notify_datetime']) ? $_POST['notify_datetime'] : null;
            // редактирование события
            break;
        case 'delete':
            // удаление события
            break;
        case 'done':
            // завершение события
            break;
        case 'postpone':
            // отложить событие
            break;
    }
}
?> 

