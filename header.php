<?php
if (!isset($_COOKIE['username'])) {
    header("Location: index.php");
    exit();
}
$username = $_COOKIE['username'];
?>
<header>
    <a href="main_page.php">
        <img src="logo.png" alt="Logo" class="logo">
    </a>
    <div class="search-box">
        <form action="search_results.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Введите название фильма..." id="search-query">
        </form>
    </div>
    <div class="user-menu">
        <img src="user.png" alt="User" class="user-icon" onclick="toggleDropdown()">
        <div id="user-dropdown" class="user-dropdown">
            <p class="username"><?php echo htmlspecialchars($username); ?></p>
            <form action="logout.php" method="post">
                <button type="submit" class="dropdown-btn">Выйти</button>
            </form>
            <a href="fav_list.php" class="dropdown-link">Список просмотренного</a>
        </div>
    </div>
</header>
