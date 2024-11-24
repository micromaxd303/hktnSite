<?php

	session_start();

    if(!isset($_SESSION['username'])) {
    header("Location: auth.php");
} 
        $connection = new PDO('mysql:host=mysql;dbname=albiDB;charset=utf8', 'root', 'root');

        if (isset($_POST['personPage']))
        {
            header("Location: personal_page.php"); 
        }
        if (isset($_POST['logo']))
        {
            header("Location: main.php");
        }
        if (isset($_POST['startTask']))
        {
            $connection->query("UPDATE tasks SET startTime='".date('Y-m-d H:i:s')."', whoComplete='".$_SESSION['id']."' WHERE id='".$_POST['id']."'");
            header("Location: maket.php?id=".$_GET['id']."");
        }
        if(isset($_POST['endTask']))
        {
            if($_POST['progress'] >= 100)
            {
                $connection->query("UPDATE tasks SET endTime='".date('Y-m-d H:i:s')."', whoComplete='".$_SESSION['id']."', completed=true WHERE id='".$_POST['id']."'");
            }
            else
                $connection->query("UPDATE tasks SET whoComplete=0 WHERE id='".$_POST['id']."'");
            
            header("Location: maket.php?id=".$_GET['id'].""); 
        }
        if(isset($_POST['trackProgress']))
        {
            $tmpValue = $_POST['value'];
            if($tmpValue > 100)
                $tmpValue = 100;
            else if($tmpValue < 0)
                $tmpValue = 0;

            $connection->query("UPDATE tasks SET progress=$tmpValue WHERE id='".$_POST['id']."'");
            
            header("Location: maket.php?id=".$_GET['id'].""); 
        }
        if(isset($_POST['setRating']))
        {
            $tmpValue = $_POST['value'];
            if($tmpValue > 10)
                $tmpValue = 10;
            else if($tmpValue < 0)
                $tmpValue = 0;

            if($tmpValue == 0)
                $connection->query("UPDATE tasks SET startTime=NULL, endTime=NULL, whoComplete=0, progress=0, completed=false WHERE id='".$_POST['id']."'");
            else
            $connection->query("UPDATE tasks SET rating=$tmpValue, completed=true WHERE id='".$_POST['id']."'");
            
            header("Location: maket.php?id=".$_GET['id'].""); 
        }
        

        $query = $connection ->query("SELECT user_name, user_surname, user_phone, user_role, roles.role_name AS user_role_name FROM users JOIN roles ON users.user_role = roles.role_id WHERE user_id='".$_SESSION['id']."'");
        global $data;
        $data = $query->fetch(PDO::FETCH_ASSOC);

        $query = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$_SESSION['id']."'");
        $profilePic = $query->fetch(PDO::FETCH_ASSOC);

        $query = $connection->query("SELECT * FROM projects WHERE id='".$_GET['id']."'");
        $projectData = $query->fetch(PDO::FETCH_ASSOC);
        $projectPic = $connection->query("SELECT images.pic_name AS project_pic FROM projects JOIN images ON preview=images.id WHERE projects.id='".$projectData['id']."'")->fetch(PDO::FETCH_ASSOC);    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maket</title>
    <link rel="stylesheet" href="../styles/style_maket.css">
    <style>
        .header {
            transition: top 0.3s;
        }
        .header.hidden {
            top: -100px;
        }
    </style>
</head>
<body>
    <!-- Хедер -->
    <header class="header">
    <form method="POST"><button class="logo-button" name="logo" aria-label="Перейти на главную"></button></form>
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

    <!-- Контент -->
    <div class="main-container">
        <div class="project-header">
            <!-- Бокс для названия проекта -->
            <div class="project-title-box">
                <h1 class="project-title"><?php echo $projectData['project_name']; ?></h1>
            </div>
            <div class="project-title-box">
                <h1 class="project-title">Сдача макета <?php echo date("d-m-Y", strtotime($projectData['estimated_date'])); ?></h1>
            </div>
            <div class="project-title-box">
                <h1 class="project-title">Статус <?php echo $projectData['stage'];?>/4</h1>
            </div>
            
            <div class="project-overview">
                <!-- Изображение -->
                <div class="project-image">
                    <?php
                        echo '<img src="../images/', $projectPic['project_pic'], '"alt="Project Image">';
                    ?>
                    
                </div>
                <!-- Бокс со ссылками -->
                <div class="project-links-box">
                    <ul>
                        <li><a href="#link1">Link 1</a></li>
                        <li><a href="#link2">Link 2</a></li>
                        <li><a href="#link3">Link 3</a></li>
                        <li><a href="#link4">Link 4</a></li>
                        <li><a href="#link5">Link 5</a></li>
                        <li><a href="#link6">Link 6</a></li>
                        <li><a href="#link7">Link 7</a></li>
                        <li><a href="#link8">Link 8</a></li>
                        <li><a href="#link8">Link 8</a></li>
                        <li><a href="#link8">Link 8</a></li>
                        <li><a href="#link8">Link 8</a></li>
                        <li><a href="#link8">Link 8</a></li>
                        <li><a href="#link8">Link 8</a></li>
                    </ul>
                </div>
            </div>
        <!-- Этапы -->
        <div class="stages-container">
            <!-- Этап -->
            <div class="stage">
                <h3>Здания</h3>
                <div class="tasks-list">
                    <?php
                        $taskData = $connection->query("SELECT * FROM tasks WHERE project='".$projectData['id']."' AND stage=1")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($taskData as $element)
                        {
                            if($element['whoComplete']) 
                            {
                                $userData = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$element['whoComplete']."'")->fetch(PDO::FETCH_ASSOC);
                                $profilePic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$userData['user_id']."'")->fetch(PDO::FETCH_ASSOC);
                            }
                            else $userData = null;
                            
                            echo '<div class="task" style="border-left: 4px solid ';
                                if(!$userData) echo '#DC143C;">';
                                else if($element['progress'] < 100) echo '#FF8C00;">';
                                else if($element['rating'] == null) echo '#FFFF00;">';
                                else echo '#4caf50;">';

                                echo '<p class="task-text">'. $element['task_name'] .'</p> 
                                <div class="task-details"> 
                                    <div class="task-info">';
                                    echo '<p>Начало: '. $element['startTime']. '</p><p>Конец:' .$element['endTime']. '</p><p>Прогресс: '.$element['progress'].'%</p>';
                                    if($element['rating']) 
                                        echo '<p>Оценка: '.$element['rating'].'</p>';
                                echo '</div>';

                                    if($userData)
                                    {
                                        echo '<div class="worker-info">
                                            <div class="worker-details">
                                                <p> '.$userData['user_name'].'</p>
                                                <p>' .$userData['user_surname']. '</p>
                                            </div>
                                            <button class="avatar">';
                                                echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar">
                                            </button>
                                        </div>';  
                                    }

                                echo '</div> 
                                <div class="task-buttons">';
                                if(!$userData && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><button class="start-btn" name="startTask">Начать</button></form>';
                                    if($projectData['manager'] == $_SESSION['id'])
                                    {
                                    echo '<button class="add-worker-btn">Добавить сотрудника</button>';
                                    }
                                }
                                else if($userData['user_id'] == $_SESSION['id'] && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><input type="hidden" name="progress" value="'.$element['progress'].'"><button class="end-btn" name="endTask">Закончить</button></form>';
                                    
                                    echo '<input type="hidden" id="hidenProgressInput" name="id" value="'.$element['id'].'"><button class="progress-btn">Записать прогресс</button>';
                                }
                                if($element['progress'] >= 100 && $projectData['manager'] == $_SESSION['id'] && $element['rating'] == 0 && $element['completed'])
                                {
                                    echo '<input type="hidden" id="hidenRatingInput" name="id" value="'.$element['id'].'"><button class="evaluate-btn" name="rateTask">Оценить работу</button>';
                                }
    
                                echo '</div></div>';
                        }            
                    ?>
                </div>
            </div>
            <div class="stage">
                <h3>Генеральный план</h3>
                <div class="tasks-list">
                <?php
                        $taskData = $connection->query("SELECT * FROM tasks WHERE project='".$projectData['id']."' AND stage=2")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($taskData as $element)
                        {
                            if($element['whoComplete']) 
                            {
                                $userData = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$element['whoComplete']."'")->fetch(PDO::FETCH_ASSOC);
                                $profilePic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$userData['user_id']."'")->fetch(PDO::FETCH_ASSOC);
                            }
                            else $userData = null;
                            
                            echo '<div class="task" style="border-left: 4px solid ';
                                if(!$userData) echo '#DC143C;">';
                                else if($element['progress'] < 100) echo '#FF8C00;">';
                                else if($element['rating'] == null) echo '#FFFF00;">';
                                else echo '#4caf50;">';

                                echo '<p class="task-text">'. $element['task_name'] .'</p> 
                                <div class="task-details"> 
                                    <div class="task-info">';
                                    echo '<p>Начало: '. $element['startTime']. '</p><p>Конец:' .$element['endTime']. '</p><p>Прогресс: '.$element['progress'].'%</p>';
                                    if($element['rating']) 
                                        echo '<p>Оценка: '.$element['rating'].'</p>';
                                echo '</div>';

                                    if($userData)
                                    {
                                        echo '<div class="worker-info">
                                            <div class="worker-details">
                                                <p> '.$userData['user_name'].'</p>
                                                <p>' .$userData['user_surname']. '</p>
                                            </div>
                                            <button class="avatar">';
                                                echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar">
                                            </button>
                                        </div>';  
                                    }

                                echo '</div> 
                                <div class="task-buttons">';
                                if(!$userData && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><button class="start-btn" name="startTask">Начать</button></form>';
                                    if($projectData['manager'] == $_SESSION['id'])
                                    {
                                    echo '<button class="add-worker-btn">Добавить сотрудника</button>';
                                    }
                                }
                                else if($userData['user_id'] == $_SESSION['id'] && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><input type="hidden" name="progress" value="'.$element['progress'].'"><button class="end-btn" name="endTask">Закончить</button></form>';
                                    echo '<input type="hidden" id="hidenProgressInput" name="id" value="'.$element['id'].'"><button class="progress-btn">Записать прогресс</button>';
                                }
                                if($element['progress'] >= 100 && $projectData['manager'] == $_SESSION['id'] && $element['rating'] == 0 && $element['completed']) 
                                {
                                    echo '<input type="hidden" id="hidenRatingInput" name="id" value="'.$element['id'].'"><button class="evaluate-btn" name="rateTask">Оценить работу</button>';
                                }
    
                                echo '</div></div>';
                        }            
                    ?>
                </div>
            </div>
            <div class="stage">
                <h3>Озеленение</h3>
                <div class="tasks-list">
                <?php
                        $taskData = $connection->query("SELECT * FROM tasks WHERE project='".$projectData['id']."' AND stage=3")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($taskData as $element)
                        {
                            if($element['whoComplete']) 
                            {
                                $userData = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$element['whoComplete']."'")->fetch(PDO::FETCH_ASSOC);
                                $profilePic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$userData['user_id']."'")->fetch(PDO::FETCH_ASSOC);
                            }
                            else $userData = null;
                            
                            echo '<div class="task" style="border-left: 4px solid ';
                                if(!$userData) echo '#DC143C;">';
                                else if($element['progress'] < 100) echo '#FF8C00;">';
                                else if($element['rating'] == null) echo '#FFFF00;">';
                                else echo '#4caf50;">';

                                echo '<p class="task-text">'. $element['task_name'] .'</p> 
                                <div class="task-details"> 
                                    <div class="task-info">';
                                        echo '<p>Начало: '. $element['startTime']. '</p><p>Конец:' .$element['endTime']. '</p><p>Прогресс: '.$element['progress'].'%</p>';
                                        if($element['rating']) 
                                            echo '<p>Оценка: '.$element['rating'].'</p>';
                                    echo '</div>';

                                    if($userData)
                                    {
                                        echo '<div class="worker-info">
                                            <div class="worker-details">
                                                <p> '.$userData['user_name'].'</p>
                                                <p>' .$userData['user_surname']. '</p>
                                            </div>
                                            <button class="avatar">';
                                                echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar">
                                            </button>
                                        </div>';  
                                    }

                                echo '</div> 
                                <div class="task-buttons">';
                                if(!$userData && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><button class="start-btn" name="startTask">Начать</button></form>';
                                    if($projectData['manager'] == $_SESSION['id'])
                                    {
                                    echo '<button class="add-worker-btn">Добавить сотрудника</button>';
                                    }
                                }
                                else if($userData['user_id'] == $_SESSION['id'] && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><input type="hidden" name="progress" value="'.$element['progress'].'"><button class="end-btn" name="endTask">Закончить</button></form>';
                                    echo '<input type="hidden" id="hidenProgressInput" name="id" value="'.$element['id'].'"><button class="progress-btn">Записать прогресс</button>';
                                }
                                if($element['progress'] >= 100 && $projectData['manager'] == $_SESSION['id'] && $element['rating'] == 0 && $element['completed'])
                                {
                                    echo '<input type="hidden" id="hidenRatingInput" name="id" value="'.$element['id'].'"><button class="evaluate-btn" name="rateTask">Оценить работу</button>';
                                }
    
                                echo '</div></div>';
                        }            
                    ?>
                </div>
            </div>
            <div class="stage">
                <h3>Малая Архиктурная Форма</h3>
                <div class="tasks-list">
                <?php
                        $taskData = $connection->query("SELECT * FROM tasks WHERE project='".$projectData['id']."' AND stage=4")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($taskData as $element)
                        {
                            if($element['whoComplete']) 
                            {
                                $userData = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$element['whoComplete']."'")->fetch(PDO::FETCH_ASSOC);
                                $profilePic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$userData['user_id']."'")->fetch(PDO::FETCH_ASSOC);
                            }
                            else $userData = null;
                            
                            echo '<div class="task" style="border-left: 4px solid ';
                                if(!$userData) echo '#DC143C;">';
                                else if($element['progress'] < 100) echo '#FF8C00;">';
                                else if($element['rating'] == null) echo '#FFFF00;">';
                                else echo '#4caf50;">';

                                echo '<p class="task-text">'. $element['task_name'] .'</p> 
                                <div class="task-details"> 
                                    <div class="task-info">';
                                    echo '<p>Начало: '. $element['startTime']. '</p><p>Конец:' .$element['endTime']. '</p><p>Прогресс: '.$element['progress'].'%</p>';
                                    if($element['rating']) 
                                        echo '<p>Оценка: '.$element['rating'].'</p>';
                                echo '</div>';

                                    if($userData)
                                    {
                                        echo '<div class="worker-info">
                                            <div class="worker-details">
                                                <p> '.$userData['user_name'].'</p>
                                                <p>' .$userData['user_surname']. '</p>
                                            </div>
                                            <button class="avatar">';
                                                echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar">
                                            </button>
                                        </div>';  
                                    }

                                echo '</div> 
                                <div class="task-buttons">';
                                if(!$userData && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><button class="start-btn" name="startTask">Начать</button></form>';
                                    if($projectData['manager'] == $_SESSION['id'])
                                    {
                                    echo '<button class="add-worker-btn">Добавить сотрудника</button>';
                                    }
                                }
                                else if($userData['user_id'] == $_SESSION['id'] && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><input type="hidden" name="progress" value="'.$element['progress'].'"><button class="end-btn" name="endTask">Закончить</button></form>';
                                    echo '<input type="hidden" id="hidenProgressInput" name="id" value="'.$element['id'].'"><button class="progress-btn">Записать прогресс</button>';
                                }
                                if($element['progress'] >= 100 && $projectData['manager'] == $_SESSION['id'] && $element['rating'] == 0 && $element['completed'])
                                {
                                    echo '<input type="hidden" id="hidenRatingInput" name="id" value="'.$element['id'].'"><button class="evaluate-btn" name="rateTask">Оценить работу</button>';
                                }
    
                                echo '</div></div>';
                        }            
                    ?>
                </div>
            </div>
            <div class="stage">
                <h3>Электрика</h3>
                <div class="tasks-list">
                <?php
                        $taskData = $connection->query("SELECT * FROM tasks WHERE project='".$projectData['id']."' AND stage=5")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($taskData as $element)
                        {
                            if($element['whoComplete']) 
                            {
                                $userData = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$element['whoComplete']."'")->fetch(PDO::FETCH_ASSOC);
                                $profilePic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$userData['user_id']."'")->fetch(PDO::FETCH_ASSOC);
                            }
                            else $userData = null;
                            
                            echo '<div class="task" style="border-left: 4px solid ';
                                if(!$userData) echo '#DC143C;">';
                                else if($element['progress'] < 100) echo '#FF8C00;">';
                                else if($element['rating'] == null) echo '#FFFF00;">';
                                else echo '#4caf50;">';

                                echo '<p class="task-text">'. $element['task_name'] .'</p> 
                                <div class="task-details"> 
                                    <div class="task-info">';
                                    echo '<p>Начало: '. $element['startTime']. '</p><p>Конец:' .$element['endTime']. '</p><p>Прогресс: '.$element['progress'].'%</p>';
                                    if($element['rating']) 
                                        echo '<p>Оценка: '.$element['rating'].'</p>';
                                echo '</div>';

                                    if($userData)
                                    {
                                        echo '<div class="worker-info">
                                            <div class="worker-details">
                                                <p> '.$userData['user_name'].'</p>
                                                <p>' .$userData['user_surname']. '</p>
                                            </div>
                                            <button class="avatar">';
                                                echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar">
                                            </button>
                                        </div>';  
                                    }

                                echo '</div> 
                                <div class="task-buttons">';
                                if(!$userData && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><button class="start-btn" name="startTask">Начать</button></form>';
                                    if($projectData['manager'] == $_SESSION['id'])
                                    {
                                    echo '<button class="add-worker-btn">Добавить сотрудника</button>';
                                    }
                                }
                                else if($userData['user_id'] == $_SESSION['id'] && !$element['completed'])
                                {
                                    echo '<form method="post"><input type="hidden" name="id" value="'.$element['id'].'"><input type="hidden" name="progress" value="'.$element['progress'].'"><button class="end-btn" name="endTask">Закончить</button></form>';
                                    echo '<input type="hidden" id="hidenProgressInput" name="id" value="'.$element['id'].'"><button class="progress-btn">Записать прогресс</button>';
                                }
                                if($element['progress'] >= 100 && $projectData['manager'] == $_SESSION['id'] && $element['rating'] == 0 && $element['completed'])
                                {
                                    echo '<input type="hidden" id="hidenRatingInput" name="id" value="'.$element['id'].'"><button class="evaluate-btn" name="rateTask">Оценить работу</button>';
                                }
    
                                echo '</div></div>';
                        }            
                    ?>
                </div>
            </div>
            
        </div>

        <!-- Менеджер -->
        <div class="manager-section">
            <h2>Менеджер</h2>
            <div class="managers-container">
                <?php
                    if( !($data['user_role'] > 1) )
                    {
                        echo '<button class="add-button">';
                        echo '<span class="plus-icon">+</span>';
                        echo '</button>';
                    }
                    $manager = $connection->query("SELECT user_id, user_name, user_surname, user_profilePic FROM users WHERE user_id='".$projectData['manager']."'")->fetch(PDO::FETCH_ASSOC);
                    if($manager)
                    {
                        $managerPic = $connection->query("SELECT images.pic_name AS pic_name FROM users JOIN images ON user_profilePic=images.id WHERE user_id='".$manager['user_id']."'")->fetch(PDO::FETCH_ASSOC);

                        echo '<div class="worker-info"><div class="worker-details"><p>';
                        echo $manager['user_name'], '</p><p>', $manager['user_surname'], '</div><button class="avatar">';
                        echo '<img src="../images/', $profilePic['pic_name'], '" alt="Worker Avatar"></button></div>';
                    }
                ?>

            </div>
        </div>

        <!-- Работники -->
        <div class="workers-section">
            <h2>Сотрудники</h2>

            <div class="managers-container">
                <button class="add-button">
                    <span class="plus-icon">+</span>
                </button>

                <div class="worker-info">
                    <div class="worker-details">
                        <p>Name: John</p>
                        <p>Surname: Doe</p>
                    </div>
                    <button class="avatar">
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </button>
                </div>
                <div class="worker-info">
                    <div class="worker-details">
                        <p>Name: John</p>
                        <p>Surname: Doe</p>
                    </div>
                    <button class="avatar">
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </button>
                </div>
                <div class="worker-info">
                    <div class="worker-details">
                        <p>Name: John</p>
                        <p>Surname: Doe</p>
                    </div>
                    <button class="avatar">
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </button>
                </div>
                <div class="worker-info">
                    <div class="worker-details">
                        <p>Name: John</p>
                        <p>Surname: Doe</p>
                    </div>
                    <button class="avatar">
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </button>
                </div>
                <div class="worker-info">
                    <div class="worker-details">
                        <p>Name: John</p>
                        <p>Surname: Doe</p>
                    </div>
                    <button class="avatar">
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </button>
                </div>
            
            </div>
        </div>
    </div>

<!-- Всплывающее окно -->
<div id="popup" class="popup hidden">
        <div class="popup-content">
            <h3>Select a Worker</h3>
            <div class="popup-workers">
                <div class="worker-info">
                    <input type="checkbox" id="worker1" name="worker1">
                    <label for="worker1">
                        <div>
                            <p>Name: Andrey</p>
                            <p>Surname: Petrov</p>
                        </div>
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker2" name="worker2">
                    <label for="worker2">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker1-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker3" name="worker3">
                    <label for="worker3">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker2-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker4" name="worker4">
                    <label for="worker4">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker2-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker5" name="worker5">
                    <label for="worker5">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker2-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker6" name="worker6">
                    <label for="worker6">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker2-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
                <div class="worker-info">
                    <input type="checkbox" id="worker7" name="worker7">
                    <label for="worker7">
                        <div>
                            <p>Name: Sergey</p>
                            <p>Surname: Ivanov</p>
                        </div>
                        <img src="/images/worker2-avatar.jpg" alt="Worker Avatar">
                    </label>
                </div>
            </div>
            <button id="close-popup" class="close-popup">Close</button>
        </div>
    </div>
    
    <!-- Новый попап для "Оценить работу" -->
    <div id="evaluate-popup" class="popup hidden">
        <div class="popup-content">
            <h3>Оценить работу</h3>
            <form method="post">
                <input type="hidden" id="hidenRatingForm" name="id" value="">
                <input type="text" id="evaluation-input" name="value" placeholder="Введите оценку" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
                <button id="submit-evaluation" class="worker-button" name="setRating">Подтвердить</button>
            </form>
            <button id="close-evaluate-popup" class="close-popup">Закрыть</button>
        </div>
    </div>

        <!-- Новый попап для "Записать прогресс" -->
        <div id="progress-popup" class="popup hidden">
            <div class="popup-content">
                <h3>Записать прогресс</h3>
                <form method="post">
                    <input type="hidden" id="hidenProgressForm" name="id" value="">
                    <input type="text" id="progress-input" name="value" placeholder="Запишите прогресс" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
                    <button id="submit-progress" class="worker-button" name="trackProgress">Подтвердить</button>
                </form>
                <button id="close-progress-popup" class="close-popup">Закрыть</button>
            </div>
        </div>

    <script>


        // Скрытие/показ хедера при прокрутке
        let lastScrollTop = 0;
        const header = document.querySelector('.header');

        window.addEventListener('scroll', function () {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                header.classList.add('hidden');
            } else {
                header.classList.remove('hidden');
            }
            lastScrollTop = scrollTop;
        });

        // Открытие всплывающего окна при нажатии на кнопку "+"
        document.querySelectorAll('.add-button').forEach(button => {
            button.addEventListener('click', () => {
                const popup = document.getElementById('popup');
                if (popup) {
                    popup.classList.remove('hidden');
                    popup.classList.add('visible');
                }
            });
        });
        // Открытие всплывающего окна при нажатии на кнопку "Добавить сотрудника"
document.querySelectorAll('.add-worker-btn').forEach(button => {
    button.addEventListener('click', () => {
        const popup = document.getElementById('popup');
        if (popup) {
            popup.classList.remove('hidden');
            popup.classList.add('visible');
        }
    });
});

    // Открытие нового попапа при нажатии на "Оценить работу"
    document.querySelectorAll('.evaluate-btn').forEach(button => {
    button.addEventListener('click', () => {
        const value = document.getElementById('hidenRatingInput').value;
        document.getElementById('hidenRatingForm').value = value;
        const evaluatePopup = document.getElementById('evaluate-popup');
        if (evaluatePopup) {
            evaluatePopup.classList.remove('hidden');
            evaluatePopup.classList.add('visible');
        }
    });
});

// Закрытие нового попапа
document.getElementById('close-evaluate-popup').addEventListener('click', (e) => {
    e.stopPropagation();
    const evaluatePopup = document.getElementById('evaluate-popup');
    if (evaluatePopup) {
        evaluatePopup.classList.remove('visible');
        evaluatePopup.classList.add('hidden');
    }
});

    // Открытие нового попапа при нажатии на "Записать прогресс"
    document.querySelectorAll('.progress-btn').forEach(button => {
    button.addEventListener('click', () => {
        const value = document.getElementById('hidenProgressInput').value;
        document.getElementById('hidenProgressForm').value = value;
        const evaluatePopup = document.getElementById('progress-popup');
        if (evaluatePopup) {
            evaluatePopup.classList.remove('hidden');
            evaluatePopup.classList.add('visible');
        }
    });
});

// Закрытие нового попапа
document.getElementById('close-progress-popup').addEventListener('click', (e) => {
    e.stopPropagation();
    const evaluatePopup = document.getElementById('progress-popup');
    if (evaluatePopup) {
        evaluatePopup.classList.remove('visible');
        evaluatePopup.classList.add('hidden');
    }
});

// Логика кнопки "Подтвердить"
document.getElementById('submit-evaluation').addEventListener('click', () => {
    const input = document.getElementById('evaluation-input').value;
    if (input) {
        alert(`Вы ввели оценку: ${input}`);
        const evaluatePopup = document.getElementById('evaluate-popup');
        evaluatePopup.classList.remove('visible');
        evaluatePopup.classList.add('hidden');
    } else {
        alert('Пожалуйста, введите оценку.');
    }
});
        

        // Закрытие окна при нажатии на кнопку "Close"
        document.getElementById('close-popup').addEventListener('click', (e) => {
            e.stopPropagation();
            const popup = document.getElementById('popup');
            if (popup) {
                popup.classList.remove('visible');
                popup.classList.add('hidden');
            }
        });

        // Закрытие окна при клике вне содержимого
        document.getElementById('popup').addEventListener('click', (e) => {
            if (e.target === document.getElementById('popup')) {
                const popup = document.getElementById('popup');
                if (popup) {
                    popup.classList.remove('visible');
                    popup.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>
