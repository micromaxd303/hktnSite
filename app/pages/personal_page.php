<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");
}
    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    if (isset($_POST['exit']))
    {
        session_destroy();
        
        header("Location: auth.php");
    }
    if (isset($_POST['logo']))
    {
        header("Location: main.php");
    }
    if (isset($_FILES['uploadedFile']))
    {
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];

        if(getimagesize($fileTmpPath) !== false)
        {   
            $id = $connection->query("SELECT id FROM images ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $connection->query("UPDATE users SET user_profilePic='".$id['id']."'+1 WHERE user_id='".$_SESSION['id']."'");

            $fileName = "profile" . $id['id'] + 1 . ".jpg";
          
            $uploadDir = '../images/';
            $destination = $uploadDir . basename($fileName);
    
            if (!move_uploaded_file($fileTmpPath, $destination)) 
            {
                echo "Ошибка при сохранении изображения.";
            }
            else
            {
                $connection->query("INSERT INTO images (pic_name) VALUES('".$fileName."')");                
            }

            header("Refresh: 0");
        }
    }

    global $data, $profilePic;
    $query = $connection->query("SELECT user_name, user_surname, user_profilePic, user_rating, user_phone, user_role, roles.role_name AS user_role_name, roles.payment AS user_payment FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");    
    $data = $query->fetch(PDO::FETCH_ASSOC);

    $query = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$_SESSION['id']."'");
    $profilePic = $query->fetch(PDO::FETCH_ASSOC);

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
                    echo '<p>', $data['user_role_name'], ' ', $data['user_name'], ' ', $data['user_surname'],'</p>';
                ?>
                <button class="profile-button" aria-label="Перейти в личный кабинет" name="personPage">
                        <?php
                            echo "<img src='../images/", $profilePic['pic_name'] , "' alt='Фото профиля' class='profile-avatar'>";                         
                        ?>
                </button>
            </div>
        </header>

        <div class="profile-container">
            <div class="profile-content">
                <div class="profile-left">
                    
                    <div class="avatar-container">
                        <?php
                            echo "<img src='../images/", $profilePic['pic_name'] , "' alt='Фото профиля'>";                         
                        ?>
                    </div>

                        <form id="uploadForm" method="post" enctype="multipart/form-data" name="uploadedFile" style="display: none;">
                            <input type="file" id="fileInput" name="uploadedFile" accept="image/png, image/jpeg" required>
                        </form>
                        
                        <!-- Кнопка для выбора и загрузки -->
                        <button id="uploadButton" class="uploadButton">Загрузить фото</button>

                    <button class="delete-photo" name="exit">Выйти</button>
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
                                echo '<p>', $data['user_role_name'], '</p>';
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
                        <?php
                            if( !($data['user_role'] > 1)) {
                                echo '<button class="admin-button">Админская панель</button>';
                            }
                        ?>
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
    <script>
        const uploadButton = document.getElementById('uploadButton');
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');

        uploadButton.addEventListener('click', () => {
            // Открыть окно выбора файла
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            // Автоматически отправить форму после выбора файла
            if (fileInput.files.length > 0) {
                uploadForm.submit();
            }
        });
    </script>
</body>
</html>
