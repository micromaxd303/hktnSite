<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");
}
    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    if (isset($_POST['logo']))
    {
        header("Location: main.php");
    }

    if (isset($_POST['createProject']))
    {
        $connection->query("INSERT INTO projects (project_name, preview, stage) VALUES('".$_POST['layout-name']."', 1, 1)");
        $id = $connection->query("SELECT id FROM projects ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        for($i = 0; $i < 5; $i++)
        {
            if(isset($_POST["stage$i-task"]))
            {
                foreach($_POST["stage$i-task"] as $task)
                {
                    $connection->query("INSERT INTO tasks (project, stage, task_name, progress) VALUES('".$id['id']."', $i+1, '$task', 0)");
                }
            }
            else
            {
                $tasks = ["Проектировка", "Изготовление", "Сборка"];    
                for($y = 0; $y < 3; $y++)
                {
                    $connection->query("INSERT INTO tasks (project, stage, task_name, progress) VALUES('".$id['id']."', $i+1, '".$tasks[$y]."', 0)");                    
                }        
            }
        }
        
    }

    $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

    $query = $connection ->query("SELECT user_name, user_surname, user_profilePic, user_rating, user_phone, roles.role_name AS user_role_name, roles.payment AS user_payment FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
    
    global $data;
    $data = $query->fetch(PDO::FETCH_ASSOC);

    $query = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$_SESSION['id']."'");
    $profilePic = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание нового макета</title>
    <style>
        .header {
            transition: top 0.3s;
        }
        .header.hidden {
            top: -100px; /* Adjust this value based on your header's height */
        }
    </style>
    <link rel="stylesheet" href="../styles/style_maket_new.css">
</head>
<body>
    <div class="main-container">
        <header class="header">
            <form method="POST"><button class="logo-button" name="logo" aria-label="Перейти на главную"></button></form>
                <span class="sr-only"></span>
            </button>
            <div class="user-info">
                <?php   
                global $data;     
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
        

        <div class="profile-container">
            <h1>Создать новый макет</h1>
            <form id="layout-form" action="#" method="post">
                <div class="info-block">
                    <label for="layout-name">Название макета:</label>
                    <input type="text" id="layout-name" name="layout-name" placeholder="Введите название" required>
                </div>
                <div class="info-block image-block">
                    <img src="../images/placeholder.png" alt="Плейсхолдер изображения" id="placeholder-image">
                    <button type="button" id="add-photo-button">Добавить фото</button>
                    <input type="file" id="upload-image" accept="image/*" style="display: none;">
                </div>


                <div id="stages-container">
                    <!-- Этапы -->
                </div>

                <div class="buttons">
                    <div id="planned-date-container" class="info-block">
                        <label for="planned-date">Планируемая дата сдачи:</label>
                        <button type="button" id="date-button">Выбрать дату</button>
                        <input type="date" id="date-picker" style="display: none;">
                    </div>                                    
                    <button type="submit" class="edit-button" name="createProject">Сохранить</button>
                    <button type="reset" class="delete-photo">Очистить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Названия этапов
        const stages = [
            "Здания",
            "Генеральный план",
            "Озеленение",
            "Малая архитектурная форма",
            "Электрика"
        ];

        // Инициализация этапов
        function initStages() {
            const stagesContainer = document.getElementById("stages-container");
            stagesContainer.innerHTML = ""; // Очистка контейнера

            stages.forEach((stage, index) => {
                const stageDiv = document.createElement("div");
                stageDiv.className = "stage";
                stageDiv.innerHTML = `
                    <h3>${stage}</h3>
                    <div id="tasks-stage-${index}" class="tasks-container"></div>
                    <button type="button" onclick="addTask(${index})">Добавить задачу</button>
                `;
                stagesContainer.appendChild(stageDiv);
            });
        }

        // Добавление задачи в этап
        function addTask(stageIndex) {
            const tasksContainer = document.getElementById(`tasks-stage-${stageIndex}`);
            const taskDiv = document.createElement("div");
            taskDiv.className = "task";
            taskDiv.innerHTML = `
                <input type="text" name="stage${stageIndex}-task[]" placeholder="Введите задачу" required>
                <button type="button" onclick="removeTask(this)">Удалить</button>
            `;
            tasksContainer.appendChild(taskDiv);
        }

        // Удаление задачи
        function removeTask(button) {
            button.parentElement.remove();
        }

        // Инициализация
        document.addEventListener("DOMContentLoaded", initStages);
    </script>
    <script>
        const dateButton = document.getElementById('date-button');
        const datePicker = document.getElementById('date-picker');

        // Открыть календарь сразу при нажатии на кнопку
        dateButton.addEventListener('click', () => {
            datePicker.showPicker(); // Открыть встроенный календарь
        });

        // Обновить текст кнопки после выбора даты
        datePicker.addEventListener('change', () => {
            const selectedDate = datePicker.value; // Получаем выбранную дату в формате YYYY-MM-DD
            if (selectedDate) {
                const [year, month, day] = selectedDate.split('-');
                const formattedDate = `${day}.${month}.${year}`; // Преобразуем в формат DD.MM.YYYY
                dateButton.textContent = `Выбрана дата: ${formattedDate}`; // Обновляем текст кнопки
            }
        });
    </script>
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
