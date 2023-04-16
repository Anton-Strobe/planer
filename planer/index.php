<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myplaner";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);
// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

// Получение записей
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $description2 = $_POST['description2'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $notify_datetime = $_POST['notify_datetime'];
    $status = $_POST['status'];
    $event_id = $_POST['event_id'];

    switch ($action) {
        case 'add':
            $sql = "INSERT INTO events (title, description, description2, start_datetime, end_datetime, notify_datetime) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $title, $description, $description2, $start_datetime, $end_datetime, $notify_datetime);
            $stmt->execute();
            break;
        case 'edit':
            $sql = "UPDATE events 
                    SET title=?, description=?, description2=?, start_datetime=?, end_datetime=?, notify_datetime=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $description, $description2, $start_datetime, $end_datetime, $notify_datetime, $event_id);
            $stmt->execute();
            break;
        case 'delete':
            $sql = "DELETE FROM events WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            break;
        case 'done':
            $sql = "UPDATE events SET status='Сделано' WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();

            $_SESSION['response'] = [
                'status' => 'success',
                'message' => 'Событие успешно помечено как выполненное',
            ];
            break;

        case 'postpone':
            // Обновите дату и время события, если необходимо.
            $sql = "UPDATE events SET status='Отложено' WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();

            $_SESSION['response'] = [
                'status' => 'success',
                'message' => 'Событие успешно отложено',
            ];
            break;
    }
}

$sql = "SELECT * FROM events ORDER BY start_datetime";
$result = $conn->query($sql);
$result2 = $conn->query($sql);
$result3 = $conn->query($sql);
?>




<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Планировщик событий</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


</head>
<?php
include 'style1.php';
?>

<body>
    <div style="margin-left: 10%;" class="container">


        <form action="" method="post">
            <input type="hidden" name="event_id">

            <!-- Кнопки "Добавить", "Редактировать", "Удалить" и "Очистить" -->
            <div class="form-group">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="collapse" href="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.553 6.776a.5.5 0 0 1 .67-.223L8 9.44l5.776-2.888a.5.5 0 1 1 .448.894l-6 3a.5.5 0 0 1-.448 0l-6-3a.5.5 0 0 1-.223-.67z" />
                        </svg></button>
                    <button type="reset" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-device-hdd" viewBox="0 0 16 16">
                            <path d="M12 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm0 11a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm-7.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM5 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM8 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                            <path d="M12 7a4 4 0 0 1-3.937 4c-.537.813-1.02 1.515-1.181 1.677a1.102 1.102 0 0 1-1.56-1.559c.1-.098.396-.314.795-.588A4 4 0 0 1 8 3a4 4 0 0 1 4 4Zm-1 0a3 3 0 1 0-3.891 2.865c.667-.44 1.396-.91 1.955-1.268.224-.144.483.115.34.34l-.62.96A3.001 3.001 0 0 0 11 7Z" />
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4Z" />
                        </svg></button>
                    <button type="submit" class="btn btn-primary" onclick="location.reload()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-repeat" viewBox="0 0 16 16">
                            <path d="M11 5.466V4H5a4 4 0 0 0-3.584 5.777.5.5 0 1 1-.896.446A5 5 0 0 1 5 3h6V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192Zm3.81.086a.5.5 0 0 1 .67.225A5 5 0 0 1 11 13H5v1.466a.25.25 0 0 1-.41.192l-2.36-1.966a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192V12h6a4 4 0 0 0 3.585-5.777.5.5 0 0 1 .225-.67Z" />
                        </svg></button>
                </div>
            </div>



            <!-- Развернутая форма -->
            <div class="collapse" id="collapseForm">
            <?php while ($row2 = $result2->fetch_assoc()) : ?>
                    <button type="button" class="btn btn-primary edit-btn" data-title="<?php echo $row2['title']; ?>" data-description="<?php echo $row2['description']; ?>" data-description2="<?php echo $row2['description2']; ?>" data-start="<?php echo $row2['start_datetime']; ?>" data-end="<?php echo $row2['end_datetime']; ?>" data-notify="<?php echo $row2['notify_datetime']; ?>" data-id="<?php echo $row2['id']; ?>"
                    style="color: <?php echo $row2['description2']; ?>; border-radius: 25%;">★</button><?php endwhile; ?><br>
                <button type="submit" class="btn btn-primary" name="action" value="add">Добавить</button>
                <button type="submit" class="btn btn-secondary" name="action" value="edit">Сохранить</button>
                <button type="submit" class="btn btn-danger" name="action" value="delete">Удалить</button><br>
                
                <div class="form-group">
                    <label for="title">Заголовок:</label>
                    <textarea type="text" class="form-control" id="title" name="title" style="height: 100px;" required></textarea>
                </div>
                <div class="form-group">
                    <label for="description">Текст:</label>
                    <textarea class="form-control" id="description" name="description" style="height: 200px;" required></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Степень:</label>


                    <select style="height: 200px;" multiple class="form-control" id="exampleFormControlSelect2" id="description2" name="description2" required>
                        <option style="color: #FFA07A;" value="#FFA07A">★</option>
                        <option style="color: #FF69B4;" value="#FF69B4">★</option>
                        <option style="color: #B0C4DE;" value="#B0C4DE">★</option>
                        <option style="color: #FFD700;" value="#FFD700">★</option>
                        <option style="color: #7B68EE;" value="#7B68EE">★</option>
                        <option style="color: #FF7F50;" value="#FF7F50">★</option>
                        <option style="color: #A0522D;" value="#A0522D">★</option>
                        <option style="color: #DC143C;" value="#DC143C">★</option>
                        <option style="color: #00CED1;" value="#00CED1">★</option>
                        <option style="color: #9932CC;" value="#9932CC">★</option>
                        <option style="color: #00FF7F;" value="#00FF7F">★</option>
                        <option style="color: #F8D7DA;" value="#F8D7DA">★</option>
                        <option style="color: #FFC0CB;" value="#FFC0CB">★</option>
                        <option style="color: #FFE4C4;" value="#FFE4C4">★</option>
                        <option style="color: #FFF0F5;" value="#FFF0F5">★</option>
                        <option style="color: #F0E68C;" value="#F0E68C">★</option>
                        <option style="color: #BC8F8F;" value="#BC8F8F">★</option>
                        <option style="color: #90EE90;" value="#90EE90">★</option>
                        <option style="color: #AFEEEE;" value="#AFEEEE">★</option>
                        <option style="color: #ADD8E6;" value="#ADD8E6">★</option>
                        <option style="color: #B0E0E6;" value="#B0E0E6">★</option>
                        <option style="color: #87CEFA;" value="#87CEFA">★</option>
                        <option style="color: #F0FFF0;" value="#F0FFF0">★</option>
                        <option style="color: #F5DEB3;" value="#F5DEB3">★</option>
                        <option style="color: #FFDAB9;" value="#FFDAB9">★</option>
                        <option style="color: #FFEBCD;" value="#FFEBCD">★</option>
                        <option style="color: #E6E6FA;" value="#E6E6FA">★</option>
                        <option style="color: #FFFACD;" value="#FFFACD">★</option>
                        <option style="color: #E0FFFF;" value="#E0FFFF">★</option>
                        <option style="color: #F0E8D0;" value="#F0E8D0">★</option>
                        <option style="color: #D3D3D3;" value="#D3D3D3">★</option>



                        <!-- Добавьте больше опций, если необходимо -->
                    </select>
                </div>



                <div class="form-group">
                    <label for="start_datetime">Начало:</label>
                    <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="end_datetime">Окончание:</label>
                    <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="notify_datetime">Уведомление:</label>
                    <input type="datetime-local" class="form-control" id="notify_datetime" name="notify_datetime" value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>



            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>
                        <pre style="color: #ffffff9e;">Заголовок          </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Текст                                     </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Степень </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Начало               </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Окончание            </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Уведомление           </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Статус  </pre>
                    </th>
                    <th>
                        <pre style="color: #ffffff9e;">Управление </pre>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <button type="button" data-toggle="collapse" href="#collapseForm" aria-expanded="false" aria-controls="collapseForm" class="btn btn-primary edit-btn" data-title="<?php echo $row['title']; ?>" data-description="<?php echo $row['description']; ?>" data-description2="<?php echo $row['description2']; ?>" data-start="<?php echo $row['start_datetime']; ?>" data-end="<?php echo $row['end_datetime']; ?>" data-notify="<?php echo $row['notify_datetime']; ?>" data-id="<?php echo $row['id']; ?>"
                    style="color: <?php echo $row['description2']; ?>; border-radius: 25%;">★</button>
                    <tr class="<?php echo $class; ?>">
                        <td>
                            <pre style="color: #ffffff9e;"><?php echo $row['title']; ?></pre>
                        </td>
                        <td>
                            <pre style="color: #ffffff9e;"><?php echo $row['description']; ?></pre>
                        </td>
                        <td style="color: <?php echo $row['description2']; ?>; border-radius: 25%;">★</td>
                        <td class="datetime"><?php echo $row['start_datetime']; ?></td>
                        <td class="datetime"><?php echo $row['end_datetime']; ?></td>
                        <td class="datetime"><?php echo $row['notify_datetime']; ?></td>
                        <td><?php echo $row['status']; ?></td>

                        <td>

                            <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" data-toggle="collapse" href="#collapseForm" aria-expanded="false" aria-controls="collapseForm" class="btn btn-primary edit-btn" data-title="<?php echo $row['title']; ?>" data-description="<?php echo $row['description']; ?>" data-description2="<?php echo $row['description2']; ?>" data-start="<?php echo $row['start_datetime']; ?>" data-end="<?php echo $row['end_datetime']; ?>" data-notify="<?php echo $row['notify_datetime']; ?>" data-id="<?php echo $row['id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks-grid" viewBox="0 0 16 16">
                                        <path d="M2 10h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1zm9-9h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm0 9a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-3zm0-10a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2h-3zM2 9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H2zm7 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-3zM0 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.354.854a.5.5 0 1 0-.708-.708L3 3.793l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0l2-2z" />
                                    </svg></button>
                                <button type="button" class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-fill-dash" viewBox="0 0 16 16">
                                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1ZM8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1Z" />
                                        <path d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12.31 12.31 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7Zm6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.552 4.552 0 0 1 .23-2.002Zm-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.507 4.507 0 0 1-1.3-1.905Z" />
                                    </svg></button>
                            </div>

                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script>
        // Получаем все элементы <td> с классом "datetime"
        var datetimes = document.querySelectorAll('.datetime');

        // Для каждого элемента создаем элементы <span> для даты и времени
        datetimes.forEach(function(datetime) {
            var dateSpan = document.createElement('span');
            dateSpan.className = 'date';
            dateSpan.textContent = datetime.textContent.split(' ')[0];

            var timeSpan = document.createElement('span');
            timeSpan.className = 'time';
            timeSpan.textContent = datetime.textContent.split(' ')[1];

            // Очищаем содержимое элемента <td>
            datetime.textContent = '';

            // Добавляем элементы <span> в элемент <td>
            datetime.appendChild(dateSpan);
            datetime.appendChild(document.createTextNode(' '));
            datetime.appendChild(timeSpan);
        });



        $(document).ready(function() {
            // заполнение формы при редактировании
            $(document).on('click', '.edit-btn', function() {
                var title = $(this).data('title');
                var description = $(this).data('description');
                var description2 = $(this).data('description2');
                var start_datetime = $(this).data('start');
                var end_datetime = $(this).data('end');
                var notify_datetime = $(this).data('notify');
                var event_id = $(this).data('id');
                $('textarea[name=title]').val(title);
                $('textarea[name=description]').val(description);
                $('select[name=description2]').val(description2);
                $('input[name=start_datetime]').val(start_datetime);
                $('input[name=end_datetime]').val(end_datetime);
                $('input[name=notify_datetime]').val(notify_datetime);
                $('input[name=event_id]').val(event_id);
            });

            // подтверждение удаления
            $(document).on('click', '.delete-btn', function() {
                if (confirm('Вы уверены, что хотите удалить это событие?')) {
                    var event_id = $(this).data('id');
                    $('input[name=event_id]').val(event_id);
                    $('button[name=action][value=delete]').click();
                }
            });

            // Обработка кнопки "Сделано"
            $(document).on('click', '.done-btn', function() {
                var event_id = $(this).data('id');
                $('input[name=event_id]').val(event_id);
                $('button[name=action][value=done]').click();
            });

            // Обработка кнопки " Отложить"
            $(document).on('click', ".postpone-btn", function() {
                var event_id = $(this).data("id");
                $("input[name=event_id]").val(event_id);
                $('button[name=action][value=postpone]').click();
            });
        });
    </script>
</body>

</html>