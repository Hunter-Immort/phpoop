<?php
// Подключение к базе данных
$db_host = 'localhost';
$db_name = 'mydatabase';
$db_user = 'root';
$db_password = 'root';

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Обработка POST-запроса от клиента
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Получение данных из формы
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];

  // Валидация данных формы
  $errors = array();

  if (empty($firstname)) {
    $errors[] = 'Имя обязательно для заполнения';
  }

  if (empty($lastname)) {
    $errors[] = 'Фамилия обязательна для заполнения';
  }

  if (empty($email)) {
    $errors[] = 'Email обязателен для заполнения';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email';
  }

  // Если есть ошибки, отправляем их клиенту в формате JSON
  if (!empty($errors)) {
    $response = array('success' => false, 'errors' => $errors);
    echo json_encode($response);
    exit;
  }

  // Запись данных в базу данных
  try {
    $stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Отправка ответа клиенту в формате JSON
    $response = array('success' => true);
    echo json_encode($response);
  } catch (PDOException $e) {
    // Отправка ответа клиенту в формате JSON в случае ошибки записи в базу данных
    $response = array('success' => false, 'errors' => array('Ошибка записи в базу данных'));
    echo json_encode($response);
  }
}
