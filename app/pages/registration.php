<?
// Страница регистрации нового пользователя

// Соединямся с БД
$connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

if(isset($_POST['submit']))
{
    $err = [];

    // проверям почту
    if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) 
    {
      $err[] = "Введена неправивльная почта!";
    }

    // проверяем, не сущестует ли пользователя с такой же почтой
    $query = $connection->query("SELECT user_id FROM users WHERE user_email='".$_POST['login']."'");
    $tables = $query->fetch(PDO::FETCH_COLUMN);
    
    if($tables)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {

        $login = $_POST['login'];

        // Убераем лишние пробелы и делаем двойное хеширование
        $password = md5(md5(trim($_POST['password'])));

        $connection->query("INSERT INTO users SET user_email='".$login."', user_password='".$password."'");
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Wavy login form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <style type="text/css">
    <?php
    include "../styles/style.css";
    ?>
  </style>

</head>
<body>
<!-- partial:index.partial.html -->
<form class="login" method="POST">
  <input name="login" type="text" placeholder="Username" required>
  <input name="password" type="password" placeholder="Password" required>
  <button name="submit">Login</button>
</form>

<!-- partial -->
  
</body>
</html>
