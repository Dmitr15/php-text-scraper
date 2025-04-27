<?php
error_reporting(E_ERROR | E_PARSE);
require_once("app/lab4_19.php");
require_once("app/preset1.php");
require_once("app/preset2.php");
require_once("app/preset3.php");

if ($_GET["preset"] == 1) {
    $res = $html_preset_1;
}
elseif ($_GET["preset"] == 2) {
    $res = $html_preset_2;
}
elseif ($_GET["preset"] == 3) {
    $res = $html_preset_3;
}
else {
    $res = $_POST["description"];
}

$task1 = start_with_dash($res);//прям реч
$task2 = insert_commas($res);//перед “а” и “но”.
$task3 = table_of_contents($res);//работающее оглавление
$task4 = clear_formatting($res);//Чистка форматирования



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/safari-pinned-tab.svg" type="image/x-icon">
    <title>Lab 4</title>
    
</head>

<body>
    <div class="container mt-3 col-md-12 mx-auto">
        <form method="POST">
            <div class="row">
                <div class="col-sm-12">
                    <textarea type="text" name="description" id="descriptionName" class="form-control" placeholder="Введите текст"><?=$res?></textarea>
                </div>
                <div class="col-12 text-center filters-btns">
                    <input type="submit" value="Отправить" class="btn btn-primary">
                </div>
            </div>
        </form>
        <div class="container mx-auto">
            <?php if(!empty($res)):?>
                <h2>Решенные задачи:</h2>
                <h3><?php echo htmlspecialchars("Задание 11. Автоматически сформировать работающее оглавление по заголовкам 1-3 уровня.");?></h3>
                <?php echo($task3);?>
                <h3><?php echo htmlspecialchars("Задание 3. Вывести только прямую речь (абзацы <p>, начинающиеся с длинного тире).");?></h3>
                <?php echo($task1);?>
                <h3><?php echo htmlspecialchars("Задание 6. Автоматически расставить запятые перед “а” и “но”. Заменить три точки на спецзнак многоточия.");?></h3>
                <?php echo($task2);?>
                
                <h3><?php echo htmlspecialchars("Задание 19. “Чистка форматирования”. Убрать из исходного html все виды визуального форматирования, оставить только функциональные и структурные элементы, теги таблиц и ссылок.");?></h3>
                <?php echo($task4);?>
            <?php endif; ?>
        </div>

    </div>

</body>

</html>