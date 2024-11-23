<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");


   
}
    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    $query = $connection ->query("SELECT user_name, user_surname, user_email, roles.role_name AS user_role FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
    $data = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style_main.css">
    <title>Макеты</title>
    <style>
        .header {
            transition: top 0.3s;
        }
        .header.hidden {
            top: -100px; /* Adjust this value based on your header's height */
        }
    </style>
</head>
<body class="backgroundreg">
    <header class="header">
        <img src="../images/logo.png" alt="Логотип" class="logo">
        <div class="user-info">
            <?php   
            global $data;     
            echo '<p>', $data['user_role'], ' ', $data['user_name'], ' ', $data['user_surname'], '  ',$data['user_email'], '</p>';
            ?>
            <button class="profile-button" aria-label="Перейти в личный кабинет"></button>
        </div>
    </header>
    <main class="content">
        <h1 class="title">Макеты</h1>
        <div class="columns">

            <!-- Колонка "Открыт" -->
            <div class="column">
                <h2>Открыт</h2>
                <div class="task-card">
                    <a href="/project1.html" class="task-link">
                        <img src="/images/project1.png" alt="Проект 1" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Дом на холме</h3>
                        <p>Проект индивидуального коттеджа.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
            </div>

            <!-- Колонка "В работе" -->
            <div class="column">
                <h2>В работе</h2>
                <div class="task-card">
                    <a href="/project3.html" class="task-link">
                        <img src="/images/project3.png" alt="Проект 3" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Солнечная вилла</h3>
                        <p>Проект загородного дома с бассейном.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project4.html" class="task-link">
                        <img src="/images/project4.png" alt="Проект 4" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Торговый центр</h3>
                        <p>Коммерческая недвижимость.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
            </div>

            <!-- Колонка "Закончен" -->
            <div class="column">
                <h2>Закончен</h2>
                <div class="task-card">
                    <a href="/project5.html" class="task-link">
                        <img src="/images/project5.png" alt="Проект 5" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Эко-дом</h3>
                        <p>Экологичное строительство.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project6.html" class="task-link">
                        <img src="/images/project6.png" alt="Проект 6" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Клубный дом</h3>
                        <p>Уютная городская резиденция.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project2.html" class="task-link">
                        <img src="/images/project2.png" alt="Проект 2" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Апартаменты «Лайт»</h3>
                        <p>Современный жилой дом.</p>
                    </div>
                </div>

            </div>

            <!-- Колонка "Сдан" -->
            <div class="column">
                <h2>Сдан</h2>
                <div class="task-card">
                    <a href="/project7.html" class="task-link">
                        <img src="/images/project7.png" alt="Проект 7" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>Офисное здание</h3>
                        <p>Многофункциональное бизнес-здание.</p>
                    </div>
                </div>
                <div class="task-card">
                    <a href="/project8.html" class="task-link">
                        <img src="/images/project8.png" alt="Проект 8" class="task-image">
                    </a>
                    <div class="task-info">
                        <h3>ТЦ «Гранд Плаза»</h3>
                        <p>Модернизированный торговый центр.</p>
                    </div>
                </div>
            </div>

        </div>
        <button class="add-project">Добавить проект</button>
    </main>
    <script>
        let lastScrollTop = 0;
        const header = document.querySelector('.header');

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                // Scrolling down
                header.classList.add('hidden');
            } else {
                // Scrolling up
                header.classList.remove('hidden');
            }
            lastScrollTop = scrollTop;
        });
    </script>
</body>
</html>
