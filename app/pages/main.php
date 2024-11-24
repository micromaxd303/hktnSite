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

    $query = $connection ->query("SELECT user_name, user_surname, user_phone, user_role, roles.role_name AS user_role_name FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
    global $data, $connection;
    $data = $query->fetch(PDO::FETCH_ASSOC);

    $query = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$_SESSION['id']."'");
    $profilePic = $query->fetch(PDO::FETCH_ASSOC);
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
            echo '<p>', $data['user_role_name'], '    ', $data['user_name'], ' ', $data['user_surname'], '</p>';
            ?>
            <form method="POST">
                <button class="profile-button" aria-label="Перейти в личный кабинет" name="personPage">
                        <?php
                            echo "<img src='../images/", $profilePic['pic_name'] , "' alt='Фото профиля' class='profile-avatar'>";                         
                        ?>
                </button>
            </form>
        </div>
    </header>
    <main class="content">
        <h1 class="title">Макеты</h1>
        <div class="columns">

            <!-- Колонка "Открыт" -->
            <div class="column">
                <h2>Открыт</h2>
                <?php
                    $projects = $connection->query("SELECT id, project_name, preview, manager FROM projects WHERE stage=1");
                    $projectData = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach(array_reverse($projectData) as $element)
                    {                       
                        $projectPic = $connection->query("SELECT images.pic_name AS project_pic FROM projects JOIN images ON preview=images.id WHERE projects.id='".$element['id']."'")->fetch(PDO::FETCH_ASSOC);    

                        echo '<form method="GET" action="/pages/maket.php" style="margin: 0; padding: 0;">';
                        echo '<input type="hidden" name="id" value="'.$element['id'].'">';
                        echo '<button type="submit" style="all: unset; display: block; width: 100%; height: 100%;">';
                        echo '<div class="task-card">';
                        echo '<img src="../images/', $projectPic['project_pic'], '" alt="Проект" class="task-image">';    
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p> Проект еще не начат</p>';
                        echo '</div></div></button></form>';

                    }
                ?>
            </div>

            <!-- Колонка "В работе" -->
            <div class="column">
                <h2>В работе</h2>
                <?php
                    $projects = $connection->query("SELECT id, project_name, preview, manager, startTime, endTime FROM projects WHERE stage=2");
                    $projectData = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach(array_reverse($projectData) as $element)
                    {                       
                        $projectPic = $connection->query("SELECT images.pic_name AS project_pic FROM projects JOIN images ON preview=images.id WHERE projects.id='".$element['id']."'")->fetch(PDO::FETCH_ASSOC);
                        echo '<form method="GET" action="/pages/maket.php" style="margin: 0; padding: 0;">';
                        echo '<input type="hidden" name="id" value="'.$element['id'].'">';
                        echo '<button type="submit" style="all: unset; display: block; width: 100%; height: 100%;">';
                        echo '<div class="task-card">';
                        echo '<img src="../images/', $projectPic['project_pic'], '" alt="Проект" class="task-image">';    
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '</p>';

                        echo '</div></div></button></form>';
                    }
                ?>
            </div>

            <!-- Колонка "Закончен" -->
            <div class="column">
                <h2>Закончен</h2>
                <?php
                    $projects = $connection->query("SELECT id, project_name, preview, manager, startTime, endTime FROM projects WHERE stage=3");
                    $projectData = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach(array_reverse($projectData) as $element)
                    {                
                        $projectPic = $connection->query("SELECT images.pic_name AS project_pic FROM projects JOIN images ON preview=images.id WHERE projects.id='".$element['id']."'")->fetch(PDO::FETCH_ASSOC);       
                        echo '<form method="GET" action="/pages/maket.php" style="margin: 0; padding: 0;">';
                        echo '<input type="hidden" name="id" value="'.$element['id'].'">';
                        echo '<button type="submit" style="all: unset; display: block; width: 100%; height: 100%;">';
                        echo '<div class="task-card">';
                        echo '<img src="../images/', $projectPic['project_pic'], '" alt="Проект" class="task-image">';    
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '--', $element['endTime'], '</p>';

                        echo '</div></div></button></form>';
                    }
                ?>
            </div>

            <!-- Колонка "Сдан" -->
            <div class="column">
                <h2>Сдан</h2>
                <?php
                    $projects = $connection->query("SELECT id, project_name, preview, manager, startTime, endTime FROM projects WHERE stage=4");
                    $projectData = $projects->fetchAll(PDO::FETCH_ASSOC);
                    foreach(array_reverse($projectData) as $element)
                    {    
                        $projectPic = $connection->query("SELECT images.pic_name AS project_pic FROM projects JOIN images ON preview=images.id WHERE projects.id='".$element['id']."'")->fetch(PDO::FETCH_ASSOC);                   
                        echo '<form method="GET" action="/pages/maket.php" style="margin: 0; padding: 0;">';
                        echo '<input type="hidden" name="id" value="'.$element['id'].'">';
                        echo '<button type="submit" style="all: unset; display: block; width: 100%; height: 100%;">';
                        echo '<div class="task-card">';
                        echo '<img src="../images/', $projectPic['project_pic'], '" alt="Проект" class="task-image">';    
                        echo '<div class="task-info">';
                        echo '<h3>', $element['project_name'], '</h3>';
                        echo '<p>', $element['startTime'], '--', $element['endTime'], '</p>';
                        echo '<p>Оценка: </p>';

                        echo '</div></div></button></form>';
                    }
                ?>
            </div>

        </div>
        <?php
            if( !($data['user_role'] > 1)) {
                echo '<form method="POST"><button class="add-project" name="createProject">Добавить проект</button></form>';
            }
        ?>
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
