<?
// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'mydatabase';
$username = 'root';
$password = 'root';

// Подключение к базе данных
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Проверка наличия таблицы users
$tableExists = $db->query("SHOW TABLES LIKE 'users'")->rowCount() > 0;

// Создание таблицы users, если она еще не существует
if (!$tableExists) {
    $query = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL
    )";

    if ($db->exec($query)) {
        echo 'Table created successfully';
    } else {
        echo 'Error creating table';
    }
}
?>