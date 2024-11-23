<?
// Скрипт проверки

// Соединямся с БД

$connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $query = $connection->query("SELECT *,INET_NTOA(user_ip) AS user_ip FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = $query->fetch(PDO::FETCH_ASSOC);

    if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] != $_COOKIE['id'])
 or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['user_ip'] !== "0")))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/"); // httponly !!!

        print "Хм, что-то не получилось";
    }
    else
    {
        session_start();
        $_SESSION['username'] = $userdata['user_name'];
        $_SESSION['id'] = $userdata['user_id'];
        $_SESSION['time'] = time();

        header("Location: main.php");
    }
}
else
{
    echo "Пшел отсюда вон";
}
?>