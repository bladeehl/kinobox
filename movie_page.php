<?php

if (!isset($_COOKIE['username'])) {
    header("Location: index.php"); // если не залогинен кидаем на страницу авторизации
    exit(); 
}

// Подключение к базе данных
$host = "localhost";
$username = "root";
$password = "";
$database = "kinobox";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
$movieId = intval($_GET['movie_id']);

$usernameFromCookie = $_COOKIE['username'];

// Получаем ID пользователя на основе login
$stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
$stmt->bind_param("s", $usernameFromCookie);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

if (!$userId) {
    die("Пользователь с таким именем не найден.");
}

// Обработка добавления в избранное
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    // Проверяем, есть ли уже этот фильм в избранном для данного пользователя
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if ($exists) {
        $message = "Фильм уже в избранном!";
    } else {
        // Добавляем фильм в избранное
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $movieId);
        if ($stmt->execute()) {
            $message = "Фильм добавлен в избранное!";
        } else {
            $message = "Ошибка добавления в избранное!";
        }
        $stmt->close();
    }
}

// Получение данных о фильме из API
$apiKey = '99N2KGM-G66MES4-K9WB2VV-YRP0BWG';
$baseUrl = 'https://api.kinopoisk.dev/v1.4';
$headers = [
    "X-API-KEY: $apiKey",
    "accept: application/json"
];

$url = "$baseUrl/movie/$movieId";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);

    if (!isset($data['id'])) {
        echo "Фильм не найден!";
        exit();
    }

    $title = $data['name'] ?? 'Нет данных';
    $posterUrl = $data['poster']['url'] ?? 'default-poster.jpg';
    $description = $data['description'] ?? 'Описание отсутствует.';
    $genres = isset($data['genres']) ? implode(', ', array_column($data['genres'], 'name')) : 'Нет данных';
    $country = isset($data['countries']) ? implode(', ', array_column($data['countries'], 'name')) : 'Нет данных';
} else {
    echo "Ошибка получения данных!";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="movie_page_styles.css">
    <script src="ShowNotification.js"></script>
    <script src="DropList.js"></script>
    
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
    <title><?php echo htmlspecialchars($title); ?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="notification" class="notification"></div>

    <main>
        <div class="movie-container">
            <div class="movie-poster">
                <img src="<?php echo htmlspecialchars($posterUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>">
            </div>
            <div class="movie-details">
                <h1><?php echo htmlspecialchars($title); ?></h1>
                <p><strong>Жанры:</strong> <?php echo htmlspecialchars($genres); ?></p>
                <p><strong>Страна производства:</strong> <?php echo htmlspecialchars($country); ?></p>
                <p><strong>Описание:</strong> <?php echo htmlspecialchars($description); ?></p>

                <!-- Форма для добавления в избранное -->
                <form method="post" action="">
                    <button type="submit" name="add_to_favorites" class="add-to-favorites">Добавить в избранное</button>
                </form>

                <!-- Сообщение о результате -->
                <?php if ($message): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            showNotification("<?php echo addslashes($message); ?>");
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>

