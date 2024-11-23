<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");
}
    if (isset($_POST['exit']))
    {
        session_destroy();
        
        header("Location: auth.php");
    }
    if (isset($_POST['logo']))
    {
        header("Location: main.php");
    }

    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    $query = $connection ->query("SELECT user_name, user_surname, user_profilePic, user_rating, user_phone, roles.role_name AS user_role, roles.payment AS user_payment FROM users JOIN roles ON user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
    
    global $data;

    $data = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../styles/style_personal.css">
</head>
<body>
    <div class="main-container">
        <header class="header">
            <form method="POST"><button class="logo-button" name="logo" aria-label="Перейти на главную"></button></form>
            <div class="user-info">
                <?php   
                    global $data;     
                    echo '<p>', $data['user_role'], ' ', $data['user_name'], ' ', $data['user_surname'],'</p>';
                ?>
                <button class="profile-button" aria-label="Перейти в личный кабинет" name="personPage"></button>
            </div>
        </header>

        <div class="profile-container">
            <div class="profile-content">
                <div class="profile-left">
                    <div class="avatar-container">
                        <img src="../images/profile1.jpg" alt="Фото профиля">
                    </div>
                    <button class="upload-photo">Загрузить фото</button>
                    <form method="POST"><button class="delete-photo" name="exit">Выйти</button></form>
                </div>

                <div class="profile-right">
                    <div class="info-block">
                        <div>
                            <label>Имя:</label>
                            <?php
                                echo '<p>', $data['user_name'], '</p>';
                            ?>
                        </div>
                        <div>
                            <label>Фамилия:</label>
                            <?php
                                echo '<p>', $data['user_surname'], '</p>';
                            ?>
                        </div>
                    </div>

                    <div class="info-block">
                        <div>
                            <label>Номер телефона:</label>
                            <?php
                                echo '<p>', '+7927-935-89-77', '</p>';
                            ?>
                        </div>
                        <div>
                            <label>Должность:</label>
                            <?php
                                echo '<p>', $data['user_role'], '</p>';
                            ?>
                        </div>
                    </div>

                    <div class="info-block">
                        <div>
                            <label>Рейтинг:</label>
                            <?php
                                echo '<p>', $data['user_rating'], '</p>';
                            ?>
                        </div>
                        <div>
                            <label>Почасовая оплата:</label>
                            <?php
                                echo '<p>', $data['user_payment'], '</p>';
                            ?>
                        </div>
                    </div>

                    <!-- Новые кнопки -->
                    <div class="buttons">
                        <button class="edit-button">Редактировать</button>
                        <button class="admin-button">Админская панель</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

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
