<?php
$link = mysqli_connect("localhost", "root", "", "kinobox");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['reg-username'];
    $login = $_POST['reg-login'];
    $password = $_POST['reg-password'];

    $username = mysqli_real_escape_string($link, $username);
    $login = mysqli_real_escape_string($link, $login);
    $password = mysqli_real_escape_string($link, $password);

    $query = "SELECT * FROM users WHERE login = '$login'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        header("Location: index.php?error=2"); // Логин уже существует
        exit();
    } else {
        // Проверка на уникальность имени
        $query = "SELECT * FROM users WHERE name = '$username'";
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) > 0) {
            header("Location: index.php?error=3"); // Имя уже существует
            exit();
        } else {
            // Добавление нового пользователя
            $query = "INSERT INTO users (name, login, pass) VALUES ('$username', '$login', '$password')";
            if (mysqli_query($link, $query)) {
                setcookie('username', $login, time() + 60 * 60 * 24 * 30, "/"); // кука с логином
                header("Location: main_page.php"); //Идём на main_page
            } else {
                echo "Ошибка!";
            }
        }
    }
}
mysqli_close($link);
?>
