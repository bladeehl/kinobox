<?php
$link = mysqli_connect("localhost", "root", "", "kinobox");

$login = $_POST['username']; 
$password = $_POST['password'];

$login = mysqli_real_escape_string($link, $login); //логин
$password = mysqli_real_escape_string($link, $password); //пароль

$query = "SELECT * FROM users WHERE login = '$login' AND pass = '$password'"; //чекаем в базе
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) > 0) {
    // cохраняем данные о пользователе в куки (на 30 дней)
    setcookie('username', $login, time() + 60 * 60 * 24 * 30, "/"); // кука с логином
    header("Location: main_page.php"); //если одобрено заходим на main_page
    exit();
} else {
    header("Location: index.php?error=1"); //на страницу авторизации с ошибкой
    exit();
}
?>
