<?php

if (!isset($_COOKIE['username'])) {
    header("Location: index.php"); // если не залогинен кидаем на страницу авторизации
    exit(); 
}

$host = "localhost";
$username = "root";
$password = "";
$database = "kinobox";

$conn = mysqli_connect($host, $username, $password, $database);
mysqli_set_charset($conn, "utf8");

$usernameFromCookie = $_COOKIE['username'];
$query = "SELECT id FROM users WHERE login = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $usernameFromCookie);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
$userId = $user['id'];

// Удаление фильма из избранного
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie_id'])) {
    $deleteMovieId = intval($_POST['delete_movie_id']);
    $deleteQuery = "DELETE FROM favorites WHERE user_id = ? AND movie_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "ii", $userId, $deleteMovieId);
    mysqli_stmt_execute($deleteStmt);

    if (mysqli_stmt_affected_rows($deleteStmt) > 0) {
        echo "<script>alert('Фильм успешно удалён из избранного!');</script>";
    } else {
        echo "<script>alert('Ошибка удаления фильма из избранного.');</script>";
    }
}

// Получаем список ID фильмов из избранного
$query = "SELECT movie_id FROM favorites WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$favorites = [];
while ($row = mysqli_fetch_assoc($result)) {
    $favorites[] = $row['movie_id'];
}

if (empty($favorites)) {
    die("У вас нет избранных фильмов.");
}

// API настройки
$apiKey = '99N2KGM-G66MES4-K9WB2VV-YRP0BWG';
$baseUrl = 'https://api.kinopoisk.dev/v1.4';
$headers = [
    "X-API-KEY: $apiKey",
    "accept: application/json"
];

$movies = [];
foreach ($favorites as $movieId) {
    $url = "$baseUrl/movie/$movieId";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response) {
        $movies[] = json_decode($response, true);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="search_results_styles.css">
    <style>
        .delete-button {
    background-color: #F44336;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
    margin-left: 10px;
}

.delete-button:hover {
    background-color: #D32F2F;
}

.delete-form {
    display: inline-block;
    margin-left: 20px;
}

    </style>
    <script src="DropList.js"></script>
    <title>Избранное</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <h1>Избранные фильмы</h1>
        <div class="results-container">
            <?php foreach ($movies as $movie): ?>
                <?php
                $movieId = $movie['id'] ?? 0;
                $posterUrl = $movie['poster']['url'] ?? 'default-poster.jpg';
                $title = $movie['name'] ?? 'Нет данных';
                ?>
<a href="movie_page.php?movie_id=<?php echo $movieId; ?>" class="movie-item">
    <img src="<?php echo htmlspecialchars($posterUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="movie-poster">
    <div class="movie-title"><?php echo htmlspecialchars($title); ?></div>
    <form method="POST" class="delete-form">
        <input type="hidden" name="delete_movie_id" value="<?php echo $movieId; ?>">
        <button type="submit" class="delete-button">Удалить</button>
    </form>
</a>

            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
