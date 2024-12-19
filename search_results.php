<?php

if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo "Введите название фильма для поиска!";
    exit();
}

$query = htmlspecialchars(trim($_GET['query']));

$apiKey = '99N2KGM-G66MES4-K9WB2VV-YRP0BWG';
$baseUrl = 'https://api.kinopoisk.dev/v1.4';
$headers = [
    "X-API-KEY: $apiKey",
    "accept: application/json"
];

// Формируем запрос к API
$url = "$baseUrl/movie/search?page=1&limit=10&query=" . urlencode($query);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);

    if (!isset($data['docs']) || count($data['docs']) === 0) {
        echo "Фильмы по вашему запросу не найдены!";
        exit();
    }

    $movies = $data['docs']; // Список фильмов
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
    <link rel="stylesheet" href="search_results_styles.css">
    <script src="DropList.js"></script>
    <title>Результаты поиска: <?php echo htmlspecialchars($query); ?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <h1>Результаты поиска: "<?php echo htmlspecialchars($query); ?>"</h1>
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
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
