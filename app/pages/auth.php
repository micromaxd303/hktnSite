<?
// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Соединямся с БД
$connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

if(isset($_POST['login'])) // Авторизация
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = $connection->query("SELECT user_id, user_password FROM users WHERE user_phone='".$_POST['phone']."' LIMIT 1");

    $data = $query->fetch(PDO::FETCH_ASSOC);
    
    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password'])))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

            // Переводим IP в строку
        $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";

        // Записываем в БД новый хеш авторизации и IP

        $connection->query("UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");

        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30); // httponly !!!

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php"); exit();
    }
    else
    {
        print "Вы ввели неправильный логин/пароль";
    }
}


else if(isset($_POST['regist'])) // Регистрация
{
    $err = [];
    // проверям почту
    if (!preg_match("/^[0-9]{11}$/", $_POST['phone'])) 
    {
      $err[] = "Введен неправильный номер!";
    }

    // проверяем, не сущестует ли пользователя с такой же почтой
    $query = $connection->query("SELECT user_id FROM users WHERE user_phone='".$_POST['phone']."'");
    $tables = $query->fetch(PDO::FETCH_COLUMN);
    
    if($tables)
    {
        $err[] = "Пользователь с такой почтой уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {

        // Убераем лишние пробелы и делаем двойное хеширование
        $password = md5(md5(trim($_POST['password'])));

        $connection->query("INSERT INTO users SET user_role=7, user_name='".$_POST['name']."', user_surname='".$_POST['surname']."', user_phone='".$_POST['phone']."', user_password='".$password."'");
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
<html >
<head>
  <meta charset="UTF-8">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons'>

  <link rel="stylesheet" href="../styles/auth.css">

  
</head>

<body>
  <div class="mainPage">
      <div class="BackgroundImage">
      <!-- Form-->
      <div class="form">
        <div class="form-toggle"></div>
        <div class="form-panel one">
          <div class="form-header">
            <h1>Войти в аккаунт</h1>
          </div>
          <div class="form-content">
            <form method="post">
              <div class="form-group">
                <label for="username">Номер телефона</label>
                <input type="text" id="phone" name="phone" required="required"/>
              </div>
              <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required="required"/>
              </div>
              <div class="form-group">
                <button type="submit" name="login">Войти</button>
              </div>
            </form>
            
          </div>
        </div>
        <div class="form-panel one">
          <div class="form-header">
            <h1>Зарегистрироваться</h1>
          </div>
          <div class="form-content">
        
          <form method="post">
            <div class="form-group">
              <label for="username">Имя</label>
              <input type="text" id="username" name="name" required="required"/>
            </div>
            <div class="form-group">
              <label for="surname">Фамилия</label>
              <input type="text" id="surname" name="surname" required="required"/>
            </div>
            <div class="form-group">
              <label for="phone">Номер телефона</label>
              <input type="phone" id="phone" name="phone" required="required"/>
            </div>
            <div class="form-group">
              <label for="password">Пароль</label>
              <input type="password" id="password" name="password" required="required"/>
            </div>
            <div class="form-group">
              <button type="submit" name="regist">Зарегистрироваться</button>
            </div>
          </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://codepen.io/andytran/pen/vLmRVp.js'></script>

    <script src="../js/auth.js"></script>

</body>
</html>
