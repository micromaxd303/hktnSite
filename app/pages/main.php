<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");
}

    if (isset($_POST['personPage']))
    {
        header("Location: personal_page.php"); 
    }

    if (isset($_POST['createProject']))
    {
        header("Location: maket_new.php"); 
    }

    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    $query = $connection ->query("SELECT user_name, user_surname, user_phone, roles.role_name AS user_role FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
    
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
        <button class="logo-button" aria-label="Перейти на главную">
            <span class="sr-only"></span>
        </button>
        <div class="user-info">
            <?php   
            global $data;     
            echo '<p>', $data['user_role'], ' ', $data['user_name'], ' ', $data['user_surname'], '  ',$data['user_phone'], '</p>';
            ?>
            <form method="POST"><button class="profile-button" aria-label="Перейти в личный кабинет" name="personPage"></button></form>
        </div>
    </header>
    <main class="content">
        <h1 class="title">Макеты</h1>
        <div class="columns">

            <!-- Колонка "Открыт" -->
            <div class="column">
                <h2>Открыт</h2>
                <?php
                    global $connection;
                    $projects = $connection->query("SELECT project_name, preview, manager FROM projects WHERE stage=1");
                    $data = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach($data as $element)
                    {                       
                        echo '<div class="task-card">';
                        echo '<a href="/project1.html" class="task-link">';
                        echo '<img src="/images/',$element['preview'], '" alt="Проект" class="task-image"></a>';
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p> Проект еще не начат</p>';
                        echo '</div></div>';
                    }
                ?>
            </div>

            <!-- Колонка "В работе" -->
            <div class="column">
                <h2>В работе</h2>
                <?php
                    global $connection;
                    $projects = $connection->query("SELECT project_name, preview, manager, startTime, endTime FROM projects WHERE stage=2");
                    $data = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach($data as $element)
                    {                       
                        echo '<div class="task-card">';
                        echo '<a href="/project1.html" class="task-link">';
                        echo '<img src="/images/',$element['preview'], '" alt="Проект" class="task-image"></a>';
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '</p>';

                        echo '</div></div>';
                    }
                ?>
            </div>

            <!-- Колонка "Закончен" -->
            <div class="column">
                <h2>Закончен</h2>
                <?php
                    global $connection;
                    $projects = $connection->query("SELECT project_name, preview, manager, startTime, endTime FROM projects WHERE stage=3");
                    $data = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach($data as $element)
                    {                       
                        echo '<div class="task-card">';
                        echo '<a href="/project1.html" class="task-link">';
                        echo '<img src="/images/',$element['preview'], '" alt="Проект" class="task-image"></a>';
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '--', $element['endTime'], '</p>';

                        echo '</div></div>';
                    }
                ?>
            </div>

            <!-- Колонка "Сдан" -->
            <div class="column">
                <h2>Сдан</h2>
                <?php
                    global $connection;
                    $projects = $connection->query("SELECT project_name, preview, manager, startTime, endTime FROM projects WHERE stage=4");
                    $data = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach($data as $element)
                    {                       
                        echo '<div class="task-card">';
                        echo '<a href="/project1.html" class="task-link">';
                        echo '<img src="/images/',$element['preview'], '" alt="Проект" class="task-image"></a>';
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '--', $element['endTime'], '</p>';
                        echo '<p>Оценка: </p>';

                        echo '</div></div>';
                    }
                ?>
            </div>

        </div>
        <form method="POST"><button class="add-project" name="createProject">Добавить проект</button></form>
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
