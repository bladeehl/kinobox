<?php
// Проверяем, авторизован ли пользователь
if (isset($_COOKIE['username'])) {
    header("Location: main_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KinoBox - Регистрация</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notification {
            display: none;
            background-color: #f44336;
            color: white;
            padding: 15px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script src="ShowNotification.js"></script>
    <script src="ChangeForms.js"></script>
</head>
<body>
    <div class="form-wrapper">
        <!-- Логотип -->
        <img src="logo.png" alt="Logo" class="logo">
        
        <!-- Контейнер для формы -->
        <div class="login-box" id="form-box">
            <!-- Форма Авторизации -->
            <form id="auth-form" action="auth.php" method="post">
                <h1 class="logo">Авторизация</h1>
                <label for="username">Логин</label>
                <input type="text" name="username" id="username" placeholder="Введите логин" required>

                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" placeholder="Введите пароль" required>

                <div class="register-link">
                    <p>Нет аккаунта? <a href="#" id="show-register">Зарегистрируйтесь!</a></p>
                </div>

                <button type="submit" class="submit-btn">Войти</button>
            </form>

            <form id="register-form" action="registration.php" method="post" style="display: none;">
                <h1 class="logo">Регистрация</h1>
                <label for="reg-username">Имя пользователя</label>
                <input type="text" name="reg-username" id="reg-username" placeholder="Введите имя" required>

                <label for="reg-login">Логин</label>
                <input type="text" name="reg-login" id="reg-login" placeholder="Придумайте логин" required>

                <label for="reg-password">Пароль</label>
                <input type="password" name="reg-password" id="reg-password" placeholder="Придумайте пароль" required>

                <div class="register-link">
                    <p>Есть аккаунт? <a href="#" id="show-auth">Войдите!</a></p>
                </div>

                <button type="submit" class="submit-btn">Зарегистрироваться</button>
            </form>
        </div>
    </div>

    <!-- Блок для уведомлений -->
    <div id="notification" class="notification"></div>

<?php

if (isset($_GET['error'])) {
    $messages = [
        1 => "Неверный логин или пароль",
        2 => "Логин занят",
        3 => "Имя занято"
    ];
    $error = $_GET['error'];
    if (isset($messages[$error])) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() {
            showNotification('{$messages[$error]}');
        });</script>";
    }
}
?>

</body>
</html>
