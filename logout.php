<?php
// удаляем куку
setcookie('username', '', time() - 3600, '/'); 

// на страницу авторизации
header("Location: index.php");
exit();
?>
