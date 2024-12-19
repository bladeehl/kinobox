<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main_page_styles.css">
    <script src="DropList.js"></script>
    <title>Главная страница</title>
</head>
<body>
   <?php include 'header.php'; ?>

    <main>
        <h2>Выбор редакции</h2>
        <div class="favorites-container">
            <?php
            // API Key и настройки
            $apiKey = '99N2KGM-G66MES4-K9WB2VV-YRP0BWG';
            $baseUrl = 'https://api.kinopoisk.dev/v1.4';
            $headers = [
                "X-API-KEY: $apiKey",
                "accept: application/json"
            ];

            // список на главной строке
            $favoriteTitles = ['good fellas', 'mr. fox', 'амели', 'watchmen', 'малхолланд'];

            foreach ($favoriteTitles as $query) {
                $url = "$baseUrl/movie/search?page=1&limit=1&query=" . urlencode($query);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $response = curl_exec($ch);
                curl_close($ch);

                if ($response) {
                    $data = json_decode($response, true);

                    if (isset($data['docs'][0])) {
                        $movie = $data['docs'][0];
                        $posterUrl = $movie['poster']['url'] ?? 'default-poster.jpg';
                        $title = $movie['name'] ?? 'Нет данных';
                        $movieId = $movie['id'] ?? 0;

                        echo "
                        <div class='movie-card'>
                            <a href='movie_page.php?movie_id=$movieId'>
                                <img src='$posterUrl' alt='$title'>
                                <h3>$title</h3>
                            </a>
                        </div>";
                    }
                }
            }
            ?>
        </div>
    </main>
</body>
</html>
