<?
// Страница авторизации

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

if(isset($_POST['submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = $connection->query("SELECT user_id, user_password FROM users WHERE user_email='".$_POST['login']."' LIMIT 1");

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
?>
<form method="POST">
Логин <input name="login" type="text" required>

Пароль <input name="password" type="password" required>

<input name="submit" type="submit" value="Войти">
</form>



