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
?>